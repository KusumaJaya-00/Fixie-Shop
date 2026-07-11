<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../helpers/auth.php';
require __DIR__ . '/../helpers/upload.php';
require __DIR__ . '/../helpers/invoice.php';
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
} elseif ($uri === '/profile') {
    $controller = new ProfileController($pdo);
    $controller->index();
} elseif ($uri === '/logout') {
    $controller = new AuthController($pdo);
    $controller->logout();
} elseif ($uri === '/admin') {
    $controller = new AdminController($pdo);
    $controller->index();
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
} elseif ($uri === '/admin/products/create') {
    $controller = new AdminProductController($pdo);
    $controller->create();
} elseif ($uri === '/admin/products/edit') {
    $controller = new AdminProductController($pdo);
    $controller->edit();
} elseif ($uri === '/admin/products/delete') {
    $controller = new AdminProductController($pdo);
    $controller->delete();
} elseif ($uri === '/admin/products/primary') {
    $controller = new AdminProductController($pdo);
    $controller->setPrimary();
} elseif ($uri === '/admin/products/delete-image') {
    $controller = new AdminProductController($pdo);
    $controller->deleteImage();
} elseif ($uri === '/admin/products') {
    $controller = new AdminProductController($pdo);
    $controller->index();
} elseif ($uri === '/admin/orders') {
    $controller = new AdminOrderController($pdo);
    $controller->index();
} elseif ($uri === '/admin/orders/detail') {
    $controller = new AdminOrderController($pdo);
    $controller->detail();
} elseif ($uri === '/admin/orders/verify') {
    $controller = new AdminOrderController($pdo);
    $controller->verify();
} elseif ($uri === '/admin/orders/status') {
    $controller = new AdminOrderController($pdo);
    $controller->updateStatus();
} elseif ($uri === '/admin/categories/store') {
    $controller = new AdminCategoryController($pdo);
    $controller->store();
} elseif ($uri === '/admin/categories/update') {
    $controller = new AdminCategoryController($pdo);
    $controller->update();
} elseif ($uri === '/admin/categories/delete') {
    $controller = new AdminCategoryController($pdo);
    $controller->delete();
} elseif ($uri === '/admin/categories') {
    $controller = new AdminCategoryController($pdo);
    $controller->index();
} else {
    http_response_code(404);
    echo 'Halaman tidak ditemukan';
}
