<?php
// Reset database: hapus DB lama, buat ulang dari fixie_shop.sql, lalu seed.
// Jalankan: php database/reset.php
// ⚠️ HANYA untuk development — ini MENGHAPUS semua data!

$pdo = require __DIR__ . '/../config/database.php';

// 1) Hapus database lama
$pdo->exec("DROP DATABASE IF EXISTS {$dbName}");
echo "Database lama dihapus.\n";

// 2) Jalankan ulang skema
$pdo->exec(file_get_contents(__DIR__ . '/fixie_shop.sql'));
echo "Skema & data awal (roles, kategori) dibuat.\n";

// 3) Jalankan seeder
require __DIR__ . '/seeder.php';
echo "✅ Selesai! Database fresh + seeded.\n";