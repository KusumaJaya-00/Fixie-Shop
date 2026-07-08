<?php

class Product
{
    public function __construct(private PDO $db) {}

    /**
     * Ambil semua produk aktif dengan filter opsional.
     */
    public function all(array $filters = []): array
    {
        $sql = 'SELECT p.*, c.name AS category_name, pi.image_path AS primary_image
                FROM products p
                JOIN categories c ON p.category_id = c.id
                LEFT JOIN product_images pi ON pi.product_id = p.id AND pi.is_primary = 1
                WHERE p.is_active = 1';

        $conditions = [];
        $params = [];

        if (!empty($filters['category_id'])) {
            $conditions[] = 'p.category_id = :category_id';
            $params[':category_id'] = (int) $filters['category_id'];
        }

        if (!empty($filters['brand'])) {
            $conditions[] = 'p.brand = :brand';
            $params[':brand'] = $filters['brand'];
        }

        if (!empty($filters['color'])) {
            $conditions[] = 'p.color = :color';
            $params[':color'] = $filters['color'];
        }

        if (!empty($filters['frame_size'])) {
            $conditions[] = 'p.frame_size = :frame_size';
            $params[':frame_size'] = $filters['frame_size'];
        }

        if (!empty($filters['price_min'])) {
            $conditions[] = 'p.price >= :price_min';
            $params[':price_min'] = (float) $filters['price_min'];
        }

        if (!empty($filters['price_max'])) {
            $conditions[] = 'p.price <= :price_max';
            $params[':price_max'] = (float) $filters['price_max'];
        }

        if (!empty($filters['search'])) {
            $conditions[] = 'p.title LIKE :search';
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        if ($conditions) {
            $sql .= ' AND ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY p.created_at DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Ambil semua produk (termasuk non-aktif) — untuk admin.
     */
    public function allForAdmin(array $filters = []): array
    {
        $sql = 'SELECT p.*, c.name AS category_name, pi.image_path AS primary_image
                FROM products p
                JOIN categories c ON p.category_id = c.id
                LEFT JOIN product_images pi ON pi.product_id = p.id AND pi.is_primary = 1';

        $conditions = [];
        $params = [];

        if (!empty($filters['search'])) {
            $conditions[] = 'p.title LIKE :search';
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        if (!empty($filters['category_id'])) {
            $conditions[] = 'p.category_id = :category_id';
            $params[':category_id'] = (int) $filters['category_id'];
        }

        if ($conditions) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY p.created_at DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT p.*, c.name AS category_name
             FROM products p
             JOIN categories c ON p.category_id = c.id
             WHERE p.id = ?'
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO products (category_id, sku, title, brand, description, price, stock, frame_size, color, is_active)
             VALUES (:category_id, :sku, :title, :brand, :description, :price, :stock, :frame_size, :color, :is_active)'
        );
        $stmt->execute([
            ':category_id' => $data['category_id'],
            ':sku'         => $data['sku'],
            ':title'       => $data['title'],
            ':brand'       => $data['brand'] ?? null,
            ':description' => $data['description'] ?? null,
            ':price'       => $data['price'],
            ':stock'       => $data['stock'],
            ':frame_size'  => $data['frame_size'] ?? null,
            ':color'       => $data['color'] ?? null,
            ':is_active'   => $data['is_active'] ?? 1,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE products SET
                category_id = :category_id,
                title = :title,
                brand = :brand,
                description = :description,
                price = :price,
                stock = :stock,
                frame_size = :frame_size,
                color = :color,
                is_active = :is_active
             WHERE id = :id'
        );
        $stmt->execute([
            ':category_id' => $data['category_id'],
            ':title'       => $data['title'],
            ':brand'       => $data['brand'] ?? null,
            ':description' => $data['description'] ?? null,
            ':price'       => $data['price'],
            ':stock'       => $data['stock'],
            ':frame_size'  => $data['frame_size'] ?? null,
            ':color'       => $data['color'] ?? null,
            ':is_active'   => $data['is_active'] ?? 1,
            ':id'          => $id,
        ]);
        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM products WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }

    // ========== METHOD GAMBAR ==========

    public function getImages(int $productId): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM product_images WHERE product_id = ? ORDER BY is_primary DESC, id ASC'
        );
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }

    public function addImage(int $productId, string $imagePath, bool $isPrimary = false): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO product_images (product_id, image_path, is_primary) VALUES (?, ?, ?)'
        );
        $stmt->execute([$productId, $imagePath, $isPrimary ? 1 : 0]);
        return (int) $this->db->lastInsertId();
    }

    public function setPrimaryImage(int $imageId, int $productId): bool
    {
        $this->db->prepare(
            'UPDATE product_images SET is_primary = 0 WHERE product_id = ?'
        )->execute([$productId]);

        $stmt = $this->db->prepare(
            'UPDATE product_images SET is_primary = 1 WHERE id = ? AND product_id = ?'
        );
        $stmt->execute([$imageId, $productId]);
        return $stmt->rowCount() > 0;
    }

    public function deleteImage(int $imageId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM product_images WHERE id = ?');
        $stmt->execute([$imageId]);
        $image = $stmt->fetch() ?: null;

        if ($image) {
            $del = $this->db->prepare('DELETE FROM product_images WHERE id = ?');
            $del->execute([$imageId]);
        }

        return $image;
    }

    /**
     * Hitung jumlah produk dalam suatu kategori.
     */
    public function countByCategory(int $categoryId): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM products WHERE category_id = ?');
        $stmt->execute([$categoryId]);
        return (int) $stmt->fetchColumn();
    }
}
