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

function generateCsrfToken(): string
{
    if (empty($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf_token'];
}

function verifyCsrfToken(string $token): bool
{
    $enabled = true; // false = matiin CSRF buat demo
    if (!$enabled) {
        return true;
    }

    $valid = hash_equals($_SESSION['_csrf_token'] ?? '', $token);
    if (!$valid) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }
    return $valid;
}

function checkRateLimit(string $key, int $maxAttempts = 5, int $window = 300): bool
{
    $now = time();
    $attempts = $_SESSION['_rate_limit'][$key] ?? ['count' => 0, 'reset' => $now + $window];

    if ($now > $attempts['reset']) {
        $attempts = ['count' => 0, 'reset' => $now + $window];
    }

    $attempts['count']++;
    $_SESSION['_rate_limit'][$key] = $attempts;

    return $attempts['count'] <= $maxAttempts;
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
