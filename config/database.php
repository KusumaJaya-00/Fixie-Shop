<?php
// Koneksi database (PDO) membaca dari file .env di root project.
$envFile = __DIR__ . '/../.env';
$env = [];

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $key = trim($parts[0]);
            $value = trim($parts[1]);
            if (preg_match('/^"([^"]*)"$/', $value, $matches) || preg_match('/^\'([^\']*)\'$/', $value, $matches)) {
                $value = $matches[1];
            }
            $env[$key] = $value;
        }
    }
}

$dbHost     = $env['DB_HOST'] ?? '127.0.0.1';
$dbName     = $env['DB_NAME'] ?? 'fixie_shop';
$dbUser     = $env['DB_USER'] ?? 'root';
$dbPassword = $env['DB_PASSWORD'] ?? '';

$connection = "mysql:host={$dbHost};dbname={$dbName}";

$pdoOptions = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    return new PDO($connection, $dbUser, $dbPassword, $pdoOptions);
} catch (PDOException $e) {
    die('Koneksi database gagal: ' . $e->getMessage());
}