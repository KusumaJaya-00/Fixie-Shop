<?php

$envFile = __DIR__ . '/../.env';

if (!file_exists($envFile)) {
    http_response_code(500);
    echo '<h2>Konfigurasi Aplikasi Belum Siap</h2>';
    echo '<p>Salin file <code>.env.example</code> menjadi <code>.env</code>, lalu isi kredensial database dan SMTP sesuai lingkungan kamu.</p>';
    exit;
}

$env = [];
$lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($lines as $line) {
    if (strpos(trim($line), '#') === 0) {
        continue;
    }
    $parts = explode('=', $line, 2);
    if (count($parts) !== 2) {
        continue;
    }
    $key = trim($parts[0]);
    $value = trim($parts[1]);
    if (preg_match('/^"([^"]*)"$/', $value, $matches) || preg_match('/^\'([^\']*)\'$/', $value, $matches)) {
        $value = $matches[1];
    }
    $env[$key] = $value;
}

return $env;
