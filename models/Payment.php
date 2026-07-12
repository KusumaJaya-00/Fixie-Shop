<?php

class Payment
{
    public function __construct(private PDO $db) {}

    /**
     * Simpan bukti transfer baru, status default 'unverified'.
     * $data wajib berisi: order_id, proof_image, amount.
     */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO payments (order_id, proof_image, amount, status)
             VALUES (:order_id, :proof_image, :amount, :status)'
        );
        $stmt->execute([
            ':order_id'    => $data['order_id'],
            ':proof_image' => $data['proof_image'],
            ':amount'      => $data['amount'],
            ':status'      => 'unverified',
        ]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Ambil data pembayaran berdasarkan order_id.
     */
    public function findByOrder(int $orderId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM payments WHERE order_id = ?');
        $stmt->execute([$orderId]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Verifikasi pembayaran: set status = 'verified' dan paid_at = NOW().
     * Dipanggil saat admin menyetujui bukti transfer.
     */
    public function verify(int $orderId): void
    {
        $stmt = $this->db->prepare(
            "UPDATE payments SET status = 'verified', paid_at = NOW() WHERE order_id = ?"
        );
        $stmt->execute([$orderId]);
    }
}
