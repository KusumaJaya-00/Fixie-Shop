<?php

function checkLogin(): bool
{
    return isset($_SESSION['user_id']);
}

function getUser(): ?array
{
    if (!checkLogin()) {
        return null;
    }
    return [
        'id'        => $_SESSION['user_id'],
        'role_name' => $_SESSION['role_name'],
        'name'      => $_SESSION['user_name'],
        'email'     => $_SESSION['user_email'],
    ];
}

function isAdmin(): bool
{
    return checkLogin() && ($_SESSION['role_name'] ?? '') === 'admin';
}

function requireAdmin(): void
{
    if (!checkLogin()) {
        header('Location: /admin/login');
        exit;
    }
    if (!isAdmin()) {
        http_response_code(403);
        echo 'Akses ditolak. Hanya admin yang bisa mengakses halaman ini.';
        exit;
    }
}

function redirectIfAuthenticated(): void
{
    if (!checkLogin()) {
        return;
    }
    if (isAdmin()) {
        header('Location: /admin');
        exit;
    }
    header('Location: /');
    exit;
}
