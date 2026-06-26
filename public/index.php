<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
$pdo = require __DIR__ . '/../config/database.php';

$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'home':
        echo '<h1>Toko Online Sepeda Fixie</h1>';
        break;
    default:
        http_response_code(404);
        echo 'Halaman tidak ditemukan';
}