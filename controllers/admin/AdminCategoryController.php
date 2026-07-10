<?php

class AdminCategoryController
{
    public function __construct(private PDO $db) {}

    public function index(): void
    {
        requireAdmin();

        $title = 'Kelola Kategori';
        ob_start();
        require __DIR__ . '/../../views/admin/categories.php';
        $content = ob_get_clean();
        require __DIR__ . '/../../views/components/admin-layout.php';
    }

    // Proses tambah kategori, redirect balik ke halaman produk
    public function store(): void
    {
        requireAdmin();

        $name = trim($_POST['name'] ?? '');
        if ($name !== '') {
            $category = new Category($this->db);
            $category->create($name);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Kategori berhasil ditambahkan.'];
        }

        header('Location: /admin/products');
        exit;
    }

    // Proses ubah nama kategori
    public function update(): void
    {
        requireAdmin();

        $id = (int) ($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');

        if ($id > 0 && $name !== '') {
            $category = new Category($this->db);
            $category->update($id, $name);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Kategori berhasil diperbarui.'];
        }

        header('Location: /admin/products');
        exit;
    }

    // Hapus kategori (ditolak kalau masih dipakai produk)
    public function delete(): void
    {
        requireAdmin();

        $id = (int) ($_GET['id'] ?? 0);
        if ($id > 0) {
            $productModel = new Product($this->db);
            if ($productModel->countByCategory($id) > 0) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Kategori tidak bisa dihapus karena masih digunakan produk.'];
            } else {
                $category = new Category($this->db);
                $category->delete($id);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Kategori berhasil dihapus.'];
            }
        }

        header('Location: /admin/products');
        exit;
    }
}
