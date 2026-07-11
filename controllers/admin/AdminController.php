<?php

class AdminController
{
    public function __construct(private PDO $db) {}

    public function index(): void
    {
        requireAdmin();

        $userModel = new User($this->db);
        $totalUsers = count($userModel->all());

        $title = 'Dashboard';
        ob_start();
        require __DIR__ . '/../../views/admin/dashboard.php';
        $content = ob_get_clean();
        require __DIR__ . '/../../views/components/admin-layout.php';
    }
}
