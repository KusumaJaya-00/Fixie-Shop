<?php

// Controller keranjang belanja: lihat isi cart, tambah, ubah qty, hapus item.
// Cart disimpan di session (bukan tabel DB), formatnya [product_id => qty].
class CartController
{
    public function __construct(private PDO $db) {}

    // Tampilkan isi keranjang, sekalian sinkronin ulang tiap item ke data produk terbaru
    public function index(): void
    {
        if (!checkLogin()) {
            header('Location: /login');
            exit;
        }

        $productModel = new Product($this->db);
        $cart = $_SESSION['cart'] ?? [];
        $items = [];
        $total = 0;

        foreach ($cart as $productId => $qty) {
            $product = $productModel->find($productId);
            // Produk sudah dihapus/dinonaktifkan admin sejak dimasukkan ke cart -> buang dari session
            if (!$product || (int) $product['is_active'] === 0) {
                unset($_SESSION['cart'][$productId]);
                continue;
            }

            $images = $productModel->getImages($productId);
            $primaryImage = $images[0]['image_path'] ?? null;

            // Stok bisa berkurang (dibeli orang lain) setelah item masuk cart, jadi qty di session
            // perlu disesuaikan lagi ke stok terkini biar gak nampilin jumlah yang gak valid
            if ($qty > (int) $product['stock']) {
                $qty = (int) $product['stock'];
                $_SESSION['cart'][$productId] = $qty;
            }

            $subtotal = $product['price'] * $qty;
            $total += $subtotal;

            $items[] = [
                'product'       => $product,
                'qty'           => $qty,
                'subtotal'      => $subtotal,
                'primary_image' => $primaryImage,
            ];
        }

        $title = 'Keranjang Belanja';
        ob_start();
        require __DIR__ . '/../../views/cart/cart.php';
        $content = ob_get_clean();
        require __DIR__ . '/../../views/components/public-layout.php';
    }

    // Tambah 1 produk ke keranjang (atau nambahin qty kalau produknya udah ada di cart)
    public function add(): void
    {
        // Wajib login sebelum bisa belanja, soalnya cart nantinya nempel ke checkout & order milik user
        if (!checkLogin()) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Silakan login dulu.'];
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /');
            exit;
        }

        if (!verifyCsrfToken($_POST['_csrf_token'] ?? '')) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Token tidak valid, silakan coba lagi.'];
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
            exit;
        }

        $productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
        $qty = filter_input(INPUT_POST, 'qty', FILTER_VALIDATE_INT);

        if (!$productId || !$qty || $qty < 1) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Data tidak valid.'];
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
            exit;
        }

        $productModel = new Product($this->db);
        $product = $productModel->find($productId);

        if (!$product || (int) $product['is_active'] === 0) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Produk tidak ditemukan.'];
            header('Location: /');
            exit;
        }

        $currentQty = $_SESSION['cart'][$productId] ?? 0;
        $newQty = $currentQty + $qty;

        // Cek stok terbaru dari DB, jangan percaya angka yang mungkin udah nyangkut di session
        if ($newQty > (int) $product['stock']) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Jumlah melebihi stok tersedia (' . (int) $product['stock'] . ').'];
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
            exit;
        }

        $_SESSION['cart'][$productId] = $newQty;
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Produk ditambahkan ke keranjang.'];
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/cart'));
        exit;
    }

    // Ubah qty item yang sudah ada di keranjang (dipanggil dari tombol +/- di halaman cart)
    public function update(): void
    {
        if (!checkLogin()) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Silakan login dulu.'];
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /cart');
            exit;
        }

        if (!verifyCsrfToken($_POST['_csrf_token'] ?? '')) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Token tidak valid, silakan coba lagi.'];
            header('Location: /cart');
            exit;
        }

        $productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
        $qty = filter_input(INPUT_POST, 'qty', FILTER_VALIDATE_INT);

        if (!$productId || !$qty || $qty < 1) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Data tidak valid.'];
            header('Location: /cart');
            exit;
        }

        $productModel = new Product($this->db);
        $product = $productModel->find($productId);

        if (!$product || (int) $product['is_active'] === 0) {
            unset($_SESSION['cart'][$productId]);
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Produk tidak tersedia lagi.'];
            header('Location: /cart');
            exit;
        }

        if ($qty > (int) $product['stock']) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Jumlah melebihi stok tersedia (' . (int) $product['stock'] . ').'];
            header('Location: /cart');
            exit;
        }

        $_SESSION['cart'][$productId] = $qty;
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Jumlah diperbarui.'];
        header('Location: /cart');
        exit;
    }

    // Hapus 1 item dari keranjang
    public function remove(): void
    {
        if (!checkLogin()) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Silakan login dulu.'];
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /cart');
            exit;
        }

        if (!verifyCsrfToken($_POST['_csrf_token'] ?? '')) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Token tidak valid, silakan coba lagi.'];
            header('Location: /cart');
            exit;
        }

        $productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);

        if ($productId && isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Produk dihapus dari keranjang.'];
        }

        header('Location: /cart');
        exit;
    }
}
