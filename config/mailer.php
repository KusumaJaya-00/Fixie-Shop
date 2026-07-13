<?php

return [
    'smtp_host'     => $_ENV['SMTP_HOST'],
    'smtp_port'     => (int) $_ENV['SMTP_PORT'],
    'smtp_auth'     => filter_var($_ENV['SMTP_AUTH'] ?? true, FILTER_VALIDATE_BOOLEAN),
    'smtp_secure'   => $_ENV['SMTP_SECURE'],
    'smtp_username' => $_ENV['SMTP_USERNAME'],
    'smtp_password' => $_ENV['SMTP_PASSWORD'],
    'from_name'     => $_ENV['SMTP_FROM_NAME'] ?? 'Fixie Shop',
];
