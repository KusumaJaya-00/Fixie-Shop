<?php

try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
} catch (Throwable $e) {
    http_response_code(500);
    echo '<h2>Konfigurasi Aplikasi Belum Siap</h2>';
    echo '<p>Salin file <code>.env.example</code> menjadi <code>.env</code>, lalu isi kredensial yang diperlukan.</p>';
    exit;
}

$dbHost     = $_ENV['DB_HOST'];
$dbName     = $_ENV['DB_NAME'];
$dbUser     = $_ENV['DB_USER'];
$dbPassword = $_ENV['DB_PASSWORD'];

$dsn = "mysql:host={$dbHost};dbname={$dbName}";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    return new PDO($dsn, $dbUser, $dbPassword, $options);
} catch (PDOException $e) {
    die('Koneksi database gagal.');
}
