<?php

$env = require __DIR__ . '/env.php';

$dsn = 'mysql:host=' . $env['DB_HOST'] . ';dbname=' . $env['DB_NAME'];
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    return new PDO($dsn, $env['DB_USER'], $env['DB_PASSWORD'], $options);
} catch (PDOException $e) {
    die('Koneksi database gagal.');
}
