<?php

class AdminOrderController
{
    public function __construct(private PDO $db) {}

    public function index(): void
    {
        requireAdmin();

        $title = 'Kelola Pesanan';
        ob_start();
        require __DIR__ . '/../../views/admin/orders.php';
        $content = ob_get_clean();
        require __DIR__ . '/../../views/components/admin-layout.php';
    }
}
