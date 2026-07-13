<?php

$env = require __DIR__ . '/env.php';

return [
    'smtp_host'     => $env['SMTP_HOST'],
    'smtp_port'     => (int) $env['SMTP_PORT'],
    'smtp_auth'     => filter_var($env['SMTP_AUTH'] ?? true, FILTER_VALIDATE_BOOLEAN),
    'smtp_secure'   => $env['SMTP_SECURE'],
    'smtp_username' => $env['SMTP_USERNAME'],
    'smtp_password' => $env['SMTP_PASSWORD'],
    'from_name'     => $env['SMTP_FROM_NAME'] ?? 'Fixie Shop',
];
