<?php
// Koneksi database (PDO).
// SALIN file ini menjadi config/database.php lalu isi sesuai MySQL lokalmu.
$dbHost     = '127.0.0.1';   // alamat server MySQL (lokal)
$dbName     = 'fixie_shop';  // nama database
$dbUser     = 'root';        // username MySQL (XAMPP/laragon default: root)
$dbPassword = '';            // password MySQL (XAMPP/laragon default: kosong)

// alamat koneksi: jenis db (mysql), host, & nama database
$connection = "mysql:host={$dbHost};dbname={$dbName}";

$pdoOptions = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // error -> jadi exception (bisa di-try/catch)
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,         // hasil query -> array nama kolom: $row['title']
    PDO::ATTR_EMULATE_PREPARES   => false,                   // pakai prepared statement asli MySQL
];

try {
    return new PDO($connection, $dbUser, $dbPassword, $pdoOptions);
} catch (PDOException $e) {
    die('Koneksi database gagal: ' . $e->getMessage());
}