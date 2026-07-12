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

// Tambah seed produk untuk testing
$products = [
    [
        'category_id' => 1,
        'sku' => 'FX-01',
        'title' => 'Sepeda Fixie Classic Blue',
        'brand' => 'Classic',
        'description' => 'Sepeda fixie klasik warna biru dengan desain elegan dan rangka kokoh.',
        'price' => 1500000,
        'stock' => 10,
        'frame_size' => '52cm',
        'color' => 'Biru',
        'is_active' => 1,
        'images' => [
            ['path' => 'fixie-blue-primary.jpg', 'is_primary' => 1],
            ['path' => 'fixie-blue-side.jpg', 'is_primary' => 0]
        ]
    ],
    [
        'category_id' => 1,
        'sku' => 'FX-02',
        'title' => 'Sepeda Fixie Stealth Black',
        'brand' => 'Stealth',
        'description' => 'Sepeda fixie hitam doff bergaya minimalis perkotaan modern.',
        'price' => 1850000,
        'stock' => 5,
        'frame_size' => '54cm',
        'color' => 'Hitam',
        'is_active' => 1,
        'images' => [
            ['path' => 'fixie-black-primary.jpg', 'is_primary' => 1]
        ]
    ],
    [
        'category_id' => 1,
        'sku' => 'FX-03',
        'title' => 'Sepeda Fixie Neon Yellow',
        'brand' => 'Neon',
        'description' => 'Sepeda fixie kuning neon terang agar mudah terlihat di malam hari.',
        'price' => 1650000,
        'stock' => 0, // Habis untuk test
        'frame_size' => '50cm',
        'color' => 'Kuning',
        'is_active' => 1,
        'images' => [] // Tanpa foto untuk test placeholder
    ]
];

$stmtProd = $pdo->prepare(
    'INSERT INTO products (category_id, sku, title, brand, description, price, stock, frame_size, color, is_active)
     VALUES (:category_id, :sku, :title, :brand, :description, :price, :stock, :frame_size, :color, :is_active)'
);

$stmtImg = $pdo->prepare(
    'INSERT INTO product_images (product_id, image_path, is_primary)
     VALUES (:product_id, :image_path, :is_primary)'
);

foreach ($products as $p) {
    $stmtProd->execute([
        ':category_id' => $p['category_id'],
        ':sku'         => $p['sku'],
        ':title'       => $p['title'],
        ':brand'       => $p['brand'],
        ':description' => $p['description'],
        ':price'       => $p['price'],
        ':stock'       => $p['stock'],
        ':frame_size'  => $p['frame_size'],
        ':color'       => $p['color'],
        ':is_active'   => $p['is_active']
    ]);
    $productId = (int)$pdo->lastInsertId();
    echo "Produk dibuat: {$p['title']} (ID: $productId)\n";

    foreach ($p['images'] as $img) {
        $stmtImg->execute([
            ':product_id' => $productId,
            ':image_path' => $img['path'],
            ':is_primary' => $img['is_primary']
        ]);
    }
}

echo "Seed selesai.\n";