<?php

class Order
{
    public function __construct(private PDO $db) {}

    // ========== ORDER ==========

    /**
     * Buat order baru, kembalikan id yang baru dibuat.
     */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO orders (buyer_id, total_price, shipping_cost, shipping_address, status)
             VALUES (:buyer_id, :total_price, :shipping_cost, :shipping_address, :status)'
        );
        $stmt->execute([
            ':buyer_id'         => $data['buyer_id'],
            ':total_price'      => $data['total_price'],
            ':shipping_cost'    => $data['shipping_cost'] ?? 0,
            ':shipping_address' => $data['shipping_address'],
            ':status'           => $data['status'] ?? 'pending',
        ]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Insert banyak baris order_items sekaligus.
     * $items = array of ['product_id' => int, 'qty' => int, 'price' => float]
     * Harga disimpan sebagai snapshot saat checkout.
     */
    public function createItems(int $orderId, array $items): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO order_items (order_id, product_id, qty, price)
             VALUES (:order_id, :product_id, :qty, :price)'
        );
        foreach ($items as $item) {
            $stmt->execute([
                ':order_id'   => $orderId,
                ':product_id' => $item['product_id'],
                ':qty'        => $item['qty'],
                ':price'      => $item['price'],
            ]);
        }
    }

    /**
     * Ambil 1 order by id.
     */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM orders WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Ambil order lengkap beserta daftar item (join judul produk & gambar utama).
     * Mengembalikan array order dengan key 'items' berisi baris-baris order_items.
     */
    public function findWithItems(int $id): ?array
    {
        // Ambil data order + info buyer
        $stmt = $this->db->prepare(
            'SELECT o.*, u.name AS buyer_name, u.email AS buyer_email, u.phone AS buyer_phone
             FROM orders o
             JOIN users u ON u.id = o.buyer_id
             WHERE o.id = ?'
        );
        $stmt->execute([$id]);
        $order = $stmt->fetch() ?: null;

        if ($order === null) {
            return null;
        }

        // Ambil item beserta judul produk dan gambar utama
        $stmt = $this->db->prepare(
            'SELECT oi.*, p.title, p.sku,
                    pi.image_path AS primary_image
             FROM order_items oi
             JOIN products p ON p.id = oi.product_id
             LEFT JOIN product_images pi ON pi.product_id = oi.product_id AND pi.is_primary = 1
             WHERE oi.order_id = ?'
        );
        $stmt->execute([$id]);
        $order['items'] = $stmt->fetchAll();

        return $order;
    }

    /**
     * Semua order milik satu buyer, urut terbaru dulu — untuk halaman "Pesanan Saya".
     */
    public function allByBuyer(int $buyerId): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM orders
             WHERE buyer_id = ?
             ORDER BY created_at DESC'
        );
        $stmt->execute([$buyerId]);
        return $stmt->fetchAll();
    }

    /**
     * Semua order untuk panel admin, join nama buyer, urut terbaru dulu.
     */
    public function allForAdmin(): array
    {
        $stmt = $this->db->prepare(
            'SELECT o.*, u.name AS buyer_name, u.email AS buyer_email
             FROM orders o
             JOIN users u ON u.id = o.buyer_id
             ORDER BY o.created_at DESC'
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Update kolom status order.
     * Transisi yang valid: pending->paid->shipped->done, atau pending->cancelled.
     * Validasi transisi dilakukan di controller, bukan di sini.
     */
    public function updateStatus(int $id, string $status): void
    {
        $stmt = $this->db->prepare('UPDATE orders SET status = ? WHERE id = ?');
        $stmt->execute([$status, $id]);
    }

    /**
     * Kurangi stok produk saat pembayaran diverifikasi (status menjadi 'paid').
     * Menggunakan stock >= qty sebagai guard agar stok tidak minus.
     * Dipanggil dari controller, bukan otomatis di model.
     */
    public function reduceStock(int $productId, int $qty): void
    {
        $stmt = $this->db->prepare(
            'UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?'
        );
        $stmt->execute([$qty, $productId, $qty]);
    }

    // ========== STATISTIK (untuk dashboard admin) ==========

    /**
     * Jumlah total order — untuk ringkasan dashboard.
     */
    public function countAll(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM orders')->fetchColumn();
    }

    /**
     * Jumlah order yang masih pending.
     */
    public function countPending(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'")->fetchColumn();
    }

    /**
     * Ambil order pending terbaru — untuk dashboard admin.
     */
    public function getRecentPending(int $limit = 5): array
    {
        $stmt = $this->db->prepare(
            'SELECT o.*, u.name AS buyer_name
             FROM orders o
             JOIN users u ON u.id = o.buyer_id
             WHERE o.status = ?
             ORDER BY o.created_at DESC
             LIMIT ?'
        );
        $stmt->execute(['pending', $limit]);
        return $stmt->fetchAll();
    }

    /**
     * Total omzet dari order yang sudah paid/shipped/done.
     */
    public function sumRevenue(): float
    {
        $stmt = $this->db->prepare(
            "SELECT COALESCE(SUM(total_price), 0)
             FROM orders
             WHERE status IN ('paid', 'shipped', 'done')"
        );
        $stmt->execute();
        return (float) $stmt->fetchColumn();
    }
}
