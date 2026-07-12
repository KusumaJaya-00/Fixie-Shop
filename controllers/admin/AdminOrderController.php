<?php

class AdminOrderController
{
    private Order   $orderModel;
    private Payment $paymentModel;

    public function __construct(private PDO $db)
    {
        $this->orderModel   = new Order($db);
        $this->paymentModel = new Payment($db);
    }

    // ========== DAFTAR ORDER ==========

    public function index(): void
    {
        requireAdmin();

        $orders = $this->orderModel->allForAdmin();

        $title = 'Kelola Pesanan';
        ob_start();
        require __DIR__ . '/../../views/admin/orders.php';
        $content = ob_get_clean();
        require __DIR__ . '/../../views/components/admin-layout.php';
    }

    // ========== DETAIL ORDER ==========

    public function detail(): void
    {
        requireAdmin();

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            http_response_code(404);
            echo 'Order tidak ditemukan.';
            return;
        }

        $order = $this->orderModel->findWithItems($id);
        if (!$order) {
            http_response_code(404);
            echo 'Order tidak ditemukan.';
            return;
        }

        $payment = $this->paymentModel->findByOrder($id);

        $title = 'Detail Pesanan #' . $id;
        ob_start();
        require __DIR__ . '/../../views/admin/order-detail.php';
        $content = ob_get_clean();
        require __DIR__ . '/../../views/components/admin-layout.php';
    }

    // ========== VERIFIKASI PEMBAYARAN (pending -> paid) ==========

    public function verify(): void
    {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/orders');
            exit;
        }

        $id = filter_input(INPUT_POST, 'order_id', FILTER_VALIDATE_INT);
        if (!$id) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'ID order tidak valid.'];
            header('Location: /admin/orders');
            exit;
        }

        $order = $this->orderModel->findWithItems($id);
        if (!$order) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Order tidak ditemukan.'];
            header('Location: /admin/orders');
            exit;
        }

        // Validasi: hanya boleh dari status pending
        if ($order['status'] !== 'pending') {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Order bukan dalam status pending.'];
            header('Location: /admin/orders/detail?id=' . $id);
            exit;
        }

        $payment = $this->paymentModel->findByOrder($id);
        if (!$payment || $payment['status'] !== 'unverified') {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Bukti transfer tidak ditemukan atau sudah diverifikasi.'];
            header('Location: /admin/orders/detail?id=' . $id);
            exit;
        }

        try {
            // 1. Verifikasi payment
            $this->paymentModel->verify($id);

            // 2. Update status order -> paid
            $this->orderModel->updateStatus($id, 'paid');

            // 3. Kurangi stok tiap produk di order (aturan bisnis SPEC.md)
            foreach ($order['items'] as $item) {
                $this->orderModel->reduceStock((int) $item['product_id'], (int) $item['qty']);
            }

            // 4. Generate invoice PDF (sub-task 4)
            try {
                generateInvoicePdf($id);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Pembayaran berhasil diverifikasi. Status order diubah ke Paid.'];
            } catch (Exception $e) {
                error_log('Generate invoice PDF error for order ID ' . $id . ': ' . $e->getMessage());
                $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Pembayaran terverifikasi, tapi invoice gagal dibuat, coba generate ulang'];
            }
        } catch (Exception $e) {
            error_log('Verify payment error: ' . $e->getMessage());
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Terjadi kesalahan saat memverifikasi pembayaran.'];
        }

        header('Location: /admin/orders/detail?id=' . $id);
        exit;
    }

    // ========== UPDATE STATUS (paid->shipped, shipped->done, pending->cancelled) ==========

    public function updateStatus(): void
    {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/orders');
            exit;
        }

        $id        = filter_input(INPUT_POST, 'order_id', FILTER_VALIDATE_INT);
        $newStatus = trim($_POST['new_status'] ?? '');

        if (!$id || $newStatus === '') {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Data tidak valid.'];
            header('Location: /admin/orders');
            exit;
        }

        $order = $this->orderModel->find($id);
        if (!$order) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Order tidak ditemukan.'];
            header('Location: /admin/orders');
            exit;
        }

        // Peta transisi yang diizinkan (searah, tidak bisa lompat/mundur)
        $allowed = [
            'paid'    => 'shipped',
            'shipped' => 'done',
            'pending' => 'cancelled',
        ];

        $currentStatus = $order['status'];

        if (!isset($allowed[$currentStatus]) || $allowed[$currentStatus] !== $newStatus) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Transisi status tidak valid.'];
            header('Location: /admin/orders/detail?id=' . $id);
            exit;
        }

        try {
            $this->orderModel->updateStatus($id, $newStatus);

            $label = match ($newStatus) {
                'shipped'   => 'Dikirim',
                'done'      => 'Selesai',
                'cancelled' => 'Dibatalkan',
                default     => $newStatus,
            };

            $_SESSION['flash'] = ['type' => 'success', 'message' => "Status order diubah ke {$label}."];
        } catch (Exception $e) {
            error_log('Update order status error: ' . $e->getMessage());
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Terjadi kesalahan saat mengubah status.'];
        }

        header('Location: /admin/orders/detail?id=' . $id);
        exit;
    }
}
