<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../helpers/auth.php';
$pdo = require __DIR__ . '/../config/database.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($uri === '/' || $uri === '/products') {
    $controller = new ProductController($pdo);
    $controller->index();
} elseif ($uri === '/product') {
    $controller = new ProductController($pdo);
    $controller->show();
} elseif ($uri === '/cart') {
    $controller = new CartController($pdo);
    $controller->index();
} elseif ($uri === '/cart/add') {
    $controller = new CartController($pdo);
    $controller->add();
} elseif ($uri === '/cart/update') {
    $controller = new CartController($pdo);
    $controller->update();
} elseif ($uri === '/cart/remove') {
    $controller = new CartController($pdo);
    $controller->remove();
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
} elseif ($uri === '/admin') {
    requireAdmin();
    echo '<h1>Admin Overview</h1>';
} elseif ($uri === '/admin/users') {
    $controller = new AdminUserController($pdo);
    $controller->index();
} elseif ($uri === '/admin/users/create') {
    $controller = new AdminUserController($pdo);
    $controller->create();
} elseif ($uri === '/admin/users/edit') {
    $controller = new AdminUserController($pdo);
    $controller->edit();
} elseif ($uri === '/admin/users/delete') {
    $controller = new AdminUserController($pdo);
    $controller->delete();
} else {
    http_response_code(404);
    echo 'Halaman tidak ditemukan';
}
