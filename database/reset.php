<?php
// Reset database: hapus DB lama, buat ulang dari fixie_shop.sql, lalu seed.
// Jalankan: php database/reset.php
// HANYA untuk development — ini MENGHAPUS semua data!

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$dbHost     = $_ENV['DB_HOST'];
$dbName     = $_ENV['DB_NAME'];
$dbUser     = $_ENV['DB_USER'];
$dbPassword = $_ENV['DB_PASSWORD'];

// 1) Hapus database lama — connect tanpa指定 specific database
$pdo = new PDO("mysql:host={$dbHost}", $dbUser, $dbPassword, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);
$pdo->exec("DROP DATABASE IF EXISTS `{$dbName}`");
echo "Database lama dihapus.\n";

// 2) Buat ulang database
$pdo->exec("CREATE DATABASE `{$dbName}`");
$pdo->exec("USE `{$dbName}`");

// 3) Jalankan ulang skema
$pdo->exec(file_get_contents(__DIR__ . '/fixie_shop.sql'));
echo "Skema & data awal (roles, kategori) dibuat.\n";

// 4) Jalankan seeder
require __DIR__ . '/seeder.php';
echo "Selesai! Database fresh + seeded.\n";
