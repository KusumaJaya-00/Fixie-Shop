<?php

class AdminProductController
{
    public function __construct(private PDO $db) {}

    public function index(): void
    {
        requireAdmin();

        $title = 'Kelola Produk';
        ob_start();
        require __DIR__ . '/../../views/admin/products.php';
        $content = ob_get_clean();
        require __DIR__ . '/../../views/components/admin-layout.php';
    }
}
