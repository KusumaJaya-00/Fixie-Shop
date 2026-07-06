<?php
// Jalankan sekali: php database/seeder.php
$pdo = require __DIR__ . '/../config/database.php';

$users = [
    ['role_id' => 1, 'name' => 'Admin Gaul',  'email' => 'admin@gmail.com', 'password' => 'password', 'phone' => '08123456789'],
    ['role_id' => 2, 'name' => 'Asep Turbo', 'email' => 'user@gmail.com', 'password' => 'password', 'phone' => '08987654321'],
];

$stmt = $pdo->prepare(
    'INSERT INTO users (role_id, name, email, password, phone)
     VALUES (:role_id, :name, :email, :password, :phone)'
);

foreach ($users as $u) {
    $stmt->execute([
        ':role_id'  => $u['role_id'],
        ':name'     => $u['name'],
        ':email'    => $u['email'],
        ':password' => password_hash($u['password'], PASSWORD_DEFAULT),
        ':phone'    => $u['phone'],
    ]);
    echo "User dibuat: {$u['email']}\n";
}
echo "Seed selesai.\n";