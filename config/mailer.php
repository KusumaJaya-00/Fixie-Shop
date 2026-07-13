<?php
// Konfigurasi SMTP Mailer membaca dari file .env di root project.
$envFile = __DIR__ . '/../.env';
$env = [];

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Abaikan komentar
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $key = trim($parts[0]);
            $value = trim($parts[1]);
            
            // Hapus kutip ganda atau tunggal jika ada
            if (preg_match('/^"([^"]*)"$/', $value, $matches) || preg_match('/^\'([^\']*)\'$/', $value, $matches)) {
                $value = $matches[1];
            }
            
            $env[$key] = $value;
        }
    }
}

return [
    'smtp_host'     => $env['SMTP_HOST'] ?? 'smtp.gmail.com',
    'smtp_port'     => (int) ($env['SMTP_PORT'] ?? 587),
    'smtp_auth'     => filter_var($env['SMTP_AUTH'] ?? true, FILTER_VALIDATE_BOOLEAN),
    'smtp_secure'   => $env['SMTP_SECURE'] ?? 'tls', // tls atau ssl
    'smtp_username' => $env['SMTP_USERNAME'] ?? '',
    'smtp_password' => $env['SMTP_PASSWORD'] ?? '',
    'from_name'     => $env['SMTP_FROM_NAME'] ?? 'Fixie Shop',
];
