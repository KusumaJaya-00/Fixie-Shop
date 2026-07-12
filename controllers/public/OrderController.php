<?php

class OrderController
{
    public function __construct(private PDO $db) {}

    public function checkoutPage(): void
    {
        if (!checkLogin()) {
            header('Location: /login');
            exit;
        }

        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Keranjang Anda kosong.'];
            header('Location: /cart');
            exit;
        }

        $productModel = new Product($this->db);
        $items = [];
        $total = 0;

        foreach ($cart as $productId => $qty) {
            $product = $productModel->find($productId);
            if (!$product || (int) $product['is_active'] === 0) {
                unset($_SESSION['cart'][$productId]);
                continue;
            }

            $images = $productModel->getImages($productId);
            $primaryImage = $images[0]['image_path'] ?? null;

            $subtotal = $product['price'] * $qty;
            $total += $subtotal;

            $items[] = [
                'product'       => $product,
                'qty'           => $qty,
                'subtotal'      => $subtotal,
                'primary_image' => $primaryImage,
            ];
        }

        if (empty($items)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Tidak ada produk valid di keranjang.'];
            header('Location: /cart');
            exit;
        }

        $title = 'Checkout';
        ob_start();
        require __DIR__ . '/../../views/orders/checkout.php';
        $content = ob_get_clean();
        require __DIR__ . '/../../views/components/public-layout.php';
    }

    /** Halaman daftar pesanan milik buyer yang sedang login. */
    public function myOrders(): void
    {
        if (!checkLogin()) {
            header('Location: /login');
            exit;
        }

        $buyerId    = (int) $_SESSION['user_id'];
        $orderModel = new Order($this->db);
        $orders     = $orderModel->allByBuyer($buyerId);

        // Ambil item untuk setiap order
        foreach ($orders as &$order) {
            $full          = $orderModel->findWithItems((int) $order['id']);
            $order['items'] = $full['items'] ?? [];
        }
        unset($order);

        $title   = 'Pesanan Saya';
        ob_start();
        require __DIR__ . '/../../views/orders/my-orders.php';
        $content = ob_get_clean();
        require __DIR__ . '/../../views/components/public-layout.php';
    }

    /** Download invoice PDF untuk satu order milik buyer yang login. */
    public function downloadInvoice(): void
    {
        if (!checkLogin()) {
            header('Location: /login');
            exit;
        }

        $orderId    = (int) ($_GET['id'] ?? 0);
        $orderModel = new Order($this->db);
        $order      = $orderModel->find($orderId);

        // Cek order ada & milik buyer yang login
        if (!$order || (int) $order['buyer_id'] !== (int) $_SESSION['user_id']) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Pesanan tidak ditemukan.'];
            header('Location: /my-orders');
            exit;
        }

        // Invoice hanya tersedia setelah paid
        $invoiceStatuses = ['paid', 'shipped', 'done'];
        if (!in_array($order['status'], $invoiceStatuses, true)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Invoice belum tersedia untuk pesanan ini.'];
            header('Location: /my-orders');
            exit;
        }

        $filePath = __DIR__ . '/../../public/invoices/inv-' . $orderId . '.pdf';

        if (!file_exists($filePath)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Invoice belum tersedia, silakan hubungi admin.'];
            header('Location: /my-orders');
            exit;
        }

        // Kirim file ke browser sebagai download
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="inv-' . $orderId . '.pdf"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }

    public function process(): void
    {
        if (!checkLogin()) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /checkout');
            exit;
        }

        if (!verifyCsrfToken($_POST['_csrf_token'] ?? '')) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Token tidak valid, silakan coba lagi.'];
            header('Location: /checkout');
            exit;
        }

        $buyerId = $_SESSION['user_id'];
        $cart = $_SESSION['cart'] ?? [];

        if (empty($cart)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Keranjang Anda kosong.'];
            header('Location: /cart');
            exit;
        }

        // Validasi shipping_address
        $shippingAddress = trim($_POST['shipping_address'] ?? '');
        if ($shippingAddress === '') {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Alamat pengiriman wajib diisi.'];
            header('Location: /checkout');
            exit;
        }

        // Validasi shipping_cost
        $shippingCost = filter_input(INPUT_POST, 'shipping_cost', FILTER_VALIDATE_INT);
        $allowedShippingCosts = [15000, 30000];
        if (!in_array($shippingCost, $allowedShippingCosts, true)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Pilihan ongkos kirim tidak valid.'];
            header('Location: /checkout');
            exit;
        }

        if (empty($_FILES['proof']) || $_FILES['proof']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Bukti transfer wajib diupload.'];
            header('Location: /checkout');
            exit;
        }

        $productModel = new Product($this->db);
        $items = [];
        $subtotalPrice = 0;

        foreach ($cart as $productId => $qty) {
            $product = $productModel->find($productId);

            if (!$product || (int) $product['is_active'] === 0) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Produk "' . htmlspecialchars($product['title'] ?? 'Unknown') . '" tidak tersedia lagi.'];
                header('Location: /cart');
                exit;
            }

            if ($qty > (int) $product['stock']) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Stok produk "' . htmlspecialchars($product['title']) . '" tidak cukup (tersisa: ' . (int) $product['stock'] . ').'];
                header('Location: /cart');
                exit;
            }

            $items[] = [
                'product_id' => $productId,
                'qty'        => $qty,
                'price'      => $product['price'],
            ];

            $subtotalPrice += $product['price'] * $qty;
        }

        // Total price = subtotal produk + ongkos kirim
        $totalPrice = $subtotalPrice + $shippingCost;

        $files = [
            'name'     => [$_FILES['proof']['name']],
            'type'     => [$_FILES['proof']['type']],
            'tmp_name' => [$_FILES['proof']['tmp_name']],
            'error'    => [$_FILES['proof']['error']],
            'size'     => [$_FILES['proof']['size']],
        ];

        $uploadResult = uploadImages($files);

        if (empty($uploadResult['success'])) {
            $errorMsg = $uploadResult['errors'][0] ?? 'Gagal mengupload bukti transfer.';
            $_SESSION['flash'] = ['type' => 'error', 'message' => $errorMsg];
            header('Location: /checkout');
            exit;
        }

        $proofImage = $uploadResult['success'][0];

        try {
            $this->db->beginTransaction();

            $orderModel = new Order($this->db);
            $orderId = $orderModel->create([
                'buyer_id'         => $buyerId,
                'total_price'      => $totalPrice,
                'shipping_cost'    => $shippingCost,
                'shipping_address' => $shippingAddress,
                'status'           => 'pending',
            ]);

            $orderModel->createItems($orderId, $items);

            $paymentModel = new Payment($this->db);
            $paymentModel->create([
                'order_id'    => $orderId,
                'proof_image' => $proofImage,
                'amount'      => $totalPrice,
            ]);

            $this->db->commit();

            unset($_SESSION['cart']);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Checkout berhasil! Pesanan Anda sedang diproses. Kami akan menghubungi Anda setelah pembayaran diverifikasi.'];
            header('Location: /');
            exit;

        } catch (PDOException $e) {
            $this->db->rollBack();

            if (isset($proofImage)) {
                deleteImage($proofImage);
            }

            error_log('Checkout error: ' . $e->getMessage());
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Terjadi kesalahan sistem. Silakan coba lagi atau hubungi admin.'];
            header('Location: /checkout');
            exit;
        }
    }
}
