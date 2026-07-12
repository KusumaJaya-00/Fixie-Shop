<?php

class AdminProductController
{
    public function __construct(private PDO $db) {}

    // Tampilkan daftar semua produk + kategori (untuk modal)
    public function index(): void
    {
        requireAdmin();

        $productModel = new Product($this->db);
        $products = $productModel->allForAdmin();

        // Ambil data kategori untuk modal kelola kategori
        $categoryModel = new Category($this->db);
        $categories = $categoryModel->all();

        $title = 'Kelola Produk';
        ob_start();
        require __DIR__ . '/../../views/admin/products.php';
        $content = ob_get_clean();
        require __DIR__ . '/../../views/components/admin-layout.php';
    }

    // Form tambah produk (GET) / proses simpan (POST)
    public function create(): void
    {
        requireAdmin();

        $categoryModel = new Category($this->db);
        $categories = $categoryModel->all();
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['_csrf_token'] ?? '')) {
                $errors['general'] = 'Sesi tidak valid. Silakan reload halaman.';
            } else {
            $data = $this->validateInput($errors);
            if (empty($errors)) {
                $productModel = new Product($this->db);
                $data['sku'] = $this->generateSku();
                $productId = $productModel->create($data);

                $uploadErrors = $this->handleImageUpload($productId);

                // Tampilkan sukses produk + peringatan kalau ada foto gagal
                if ($uploadErrors) {
                    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Produk berhasil ditambahkan, tapi foto gagal: ' . $uploadErrors];
                } else {
                    $_SESSION['flash'] = ['type' => 'success', 'message' => 'Produk berhasil ditambahkan.'];
                }
                header('Location: /admin/products');
                exit;
            }
        }
        }

        $title = 'Tambah Produk';
        ob_start();
        require __DIR__ . '/../../views/admin/product-form.php';
        $content = ob_get_clean();
        require __DIR__ . '/../../views/components/admin-layout.php';
    }

    // Form edit produk (GET) / proses update (POST)
    public function edit(): void
    {
        requireAdmin();

        $id = (int) ($_GET['id'] ?? 0);
        $productModel = new Product($this->db);
        $product = $productModel->find($id);

        if (!$product) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Produk tidak ditemukan.'];
            header('Location: /admin/products');
            exit;
        }

        $categoryModel = new Category($this->db);
        $categories = $categoryModel->all();
        $images = $productModel->getImages($id);
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['_csrf_token'] ?? '')) {
                $errors['general'] = 'Sesi tidak valid. Silakan reload halaman.';
            } else {
            $data = $this->validateInput($errors);
            if (empty($errors)) {
                $productModel->update($id, $data);
                $uploadErrors = $this->handleImageUpload($id);

                // Tampilkan sukses edit + peringatan kalau ada foto gagal
                if ($uploadErrors) {
                    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Produk berhasil diperbarui, tapi foto gagal: ' . $uploadErrors];
                } else {
                    $_SESSION['flash'] = ['type' => 'success', 'message' => 'Produk berhasil diperbarui.'];
                }
                header('Location: /admin/products');
                exit;
            }
        }
        }

        $title = 'Edit Produk';
        ob_start();
        require __DIR__ . '/../../views/admin/product-form.php';
        $content = ob_get_clean();
        require __DIR__ . '/../../views/components/admin-layout.php';
    }

    // Hapus produk + file fotonya
    public function delete(): void
    {
        requireAdmin();

        $id = (int) ($_GET['id'] ?? 0);
        if ($id > 0) {
            $productModel = new Product($this->db);

            // Hapus file gambar dari folder sebelum hapus record
            $images = $productModel->getImages($id);
            foreach ($images as $img) {
                deleteImage($img['image_path']);
            }

            $productModel->delete($id);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Produk berhasil dihapus.'];
        }

        header('Location: /admin/products');
        exit;
    }

    // Set foto utama
    public function setPrimary(): void
    {
        requireAdmin();

        $imageId = (int) ($_GET['image_id'] ?? 0);
        $productId = (int) ($_GET['product_id'] ?? 0);

        if ($imageId > 0 && $productId > 0) {
            $productModel = new Product($this->db);
            $productModel->setPrimaryImage($imageId, $productId);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Foto utama berhasil diubah.'];
        }

        header('Location: /admin/products/edit?id=' . $productId);
        exit;
    }

    // Hapus satu foto
    public function deleteImage(): void
    {
        requireAdmin();

        $imageId = (int) ($_GET['image_id'] ?? 0);
        $productId = (int) ($_GET['product_id'] ?? 0);

        if ($imageId > 0) {
            $productModel = new Product($this->db);
            $image = $productModel->deleteImage($imageId);
            if ($image) {
                deleteImage($image['image_path']);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Foto berhasil dihapus.'];
            }
        }

        header('Location: /admin/products/edit?id=' . $productId);
        exit;
    }

    // ========== PRIVATE HELPERS ==========

    // Validasi & kumpulkan input produk
    private function validateInput(array &$errors): array
    {
        $data = [];
        $data['title'] = trim($_POST['title'] ?? '');
        $data['category_id'] = (int) ($_POST['category_id'] ?? 0);
        $data['price'] = (float) ($_POST['price'] ?? 0);
        $data['stock'] = (int) ($_POST['stock'] ?? 0);
        $data['brand'] = trim($_POST['brand'] ?? '');
        $data['description'] = trim($_POST['description'] ?? '');
        $data['frame_size'] = trim($_POST['frame_size'] ?? '');
        $data['color'] = trim($_POST['color'] ?? '');
        $data['is_active'] = isset($_POST['is_active']) ? 1 : 0;

        if ($data['title'] === '') {
            $errors['title'] = 'Judul produk wajib diisi.';
        }
        if ($data['category_id'] < 1) {
            $errors['category_id'] = 'Kategori wajib dipilih.';
        }
        if ($data['price'] <= 0) {
            $errors['price'] = 'Harga harus lebih dari 0.';
        }
        if ($data['stock'] < 0) {
            $errors['stock'] = 'Stok tidak boleh negatif.';
        }

        return $data;
    }

    // Upload & simpan record foto, return string error atau null
    private function handleImageUpload(int $productId): ?string
    {
        if (empty($_FILES['images']['name'][0])) {
            return null;
        }

        $productModel = new Product($this->db);
        $uploadResult = uploadImages($_FILES['images']);

        foreach ($uploadResult['success'] as $filename) {
            $existingImages = $productModel->getImages($productId);
            $isPrimary = empty($existingImages);
            $productModel->addImage($productId, $filename, $isPrimary);
        }

        return !empty($uploadResult['errors'])
            ? implode('. ', $uploadResult['errors'])
            : null;
    }

    // Generate SKU unik
    private function generateSku(): string
    {
        return 'PRD-' . strtoupper(substr(uniqid(), -6));
    }
}
