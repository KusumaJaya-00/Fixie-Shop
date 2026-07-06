<?php

class Product
{
    public function __construct(private PDO $db) {}

    /**
     * Ambil semua produk aktif dengan kategori & foto utama.
     * Mendukung filter opsional (category_id, brand, color, frame_size, price_min, price_max, search).
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
}
