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
}
