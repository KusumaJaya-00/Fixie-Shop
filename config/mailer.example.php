<?php
// Konfigurasi SMTP Mailer.
// SALIN file ini menjadi config/mailer.php lalu sesuaikan dengan kredensial SMTP Anda.
return [
    'smtp_host'     => 'smtp.gmail.com',
    'smtp_port'     => 587,
    'smtp_auth'     => true,
    'smtp_secure'   => 'tls', // tls atau ssl
    'smtp_username' => 'your_email@gmail.com', // Email Gmail Anda
    'smtp_password' => 'your_app_password',   // App Password Gmail (16 digit) atau Mailtrap Password
    'from_name'     => 'Fixie Shop',
];
