<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../helpers/auth.php';
$pdo = require __DIR__ . '/../config/database.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($uri === '/' || $uri === '/home') {
    echo '<h1>Toko Online Sepeda Fixie</h1>';
} elseif ($uri === '/register') {
    $controller = new AuthController($pdo);
    $controller->register();
} elseif ($uri === '/login') {
    $controller = new AuthController($pdo);
    $controller->login();
} elseif ($uri === '/admin/login') {
    $controller = new AuthController($pdo);
    $controller->adminLogin();
} elseif ($uri === '/logout') {
    $controller = new AuthController($pdo);
    $controller->logout();
} else {
    http_response_code(404);
    echo 'Halaman tidak ditemukan';
}
