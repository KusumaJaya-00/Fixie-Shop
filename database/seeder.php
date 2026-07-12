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
        'category_id' => 2,
        'sku' => 'FX-01',
        'title' => 'Frame Set Crit-D 2024 DELUXE Track',
        'brand' => 'Engine 11',
        'description' => 'Introducing the ultimate fixed gear crit race frame, meticulously engineered for uncompromising speed, agility, and control. This frame is purpose-built to excel in the high-intensity, adrenaline-fueled environment of fixed gear crit racing, where split-second decisions and lightning-fast maneuverability are paramount.',
        'price' => 15000000,
        'stock' => 10,
        'frame_size' => '52cm',
        'color' => 'White Soft',
        'is_active' => 1,
        'images' => [
            ['path' => 'Crit-D 2024 DELUXE Track.png', 'is_primary' => 1]
            
        ]
    ],
    [
        'category_id' => 2,
        'sku' => 'FX-02',
        'title' => 'Frame Set Crit-D DCC Edition Track',
        'brand' => 'Engine 11',
        'description' => 'Introducing the CritD: The Ultimate Fixed Gear Crit Race Frame by Team DCC x Engine11',
        'price' => 18500000,
        'stock' => 5,
        'frame_size' => '54cm',
        'color' => 'Green',
        'is_active' => 1,
        'images' => [
            ['path' => 'Crit-D DCC Edition Track.png', 'is_primary' => 1]
        ]
    ],
    [
        'category_id' => 2,
        'sku' => 'FX-03',
        'title' => 'Frame Set Crit-D x DCC 2026 Edition Track',
        'brand' => 'Engine 11',
        'description' => 'Introducing the CritD: The Ultimate Fixed Gear Crit Race Frame by Team DCC x Engine11',
        'price' => 16500000,
        'stock' => 0, // Habis untuk test
        'frame_size' => '50cm',
        'color' => 'Kuning',
        'is_active' => 1,
        'images' => [
            ['path' => 'Crit-D x DCC 2026 Edition Track.png', 'is_primary' => 1]
        ] // Tanpa foto untuk test placeholder
    ],
    
    // ===== SEPEDA FIXIE (complete bike) =====
    [
        'category_id' => 1,
        'sku' => 'SFX-01',
        'title' => 'TSUNAMI SNM100 Complete Fixie Bike (Raw Polished)',
        'brand' => 'TSUNAMI',
        'description' => 'Sepeda fixie lengkap siap pakai dari TSUNAMI, seri SNM100 dengan finishing raw polished.',
        'price' => 8500000,
        'stock' => 8,
        'frame_size' => '52cm',
        'color' => 'Raw Polished',
        'is_active' => 1,
        'images' => [
            ['path' => 'TSUNAMI SNM100 Complete Fixie Bike (Raw Polish).png', 'is_primary' => 1]
        ]
    ],
    [
        'category_id' => 1,
        'sku' => 'SFX-02',
        'title' => 'TSUNAMI SNM100 Complete Fixie Bike (Rose Gold)',
        'brand' => 'TSUNAMI',
        'description' => 'Sepeda fixie lengkap siap pakai dari TSUNAMI, seri SNM100 dengan warna rose gold.',
        'price' => 8500000,
        'stock' => 6,
        'frame_size' => '52cm',
        'color' => 'Rose Gold',
        'is_active' => 1,
        'images' => [
            ['path' => 'TSUNAMI SNM100 Complete Fixie Bike (Rose Gold).png', 'is_primary' => 1]
        ]
    ],
    [
        'category_id' => 1,
        'sku' => 'SFX-03',
        'title' => 'TSUNAMI SNM100 ELITE Complete Fixie Bike (Spark Teal)',
        'brand' => 'TSUNAMI',
        'description' => 'Versi ELITE dari SNM100, upgrade groupset & komponen, warna spark teal.',
        'price' => 12500000,
        'stock' => 5,
        'frame_size' => '54cm',
        'color' => 'Spark Teal',
        'is_active' => 1,
        'images' => [
            ['path' => 'TSUNAMI SNM100 ELITE Complete Fixie Bike (Spark Teal).png', 'is_primary' => 1]
        ]
    ],
    [
        'category_id' => 1,
        'sku' => 'SFX-04',
        'title' => 'TSUNAMI SNM100 ELITE Complete Fixie Bike (Spark Green)',
        'brand' => 'TSUNAMI',
        'description' => 'Versi ELITE dari SNM100, upgrade groupset & komponen, warna spark green.',
        'price' => 12500000,
        'stock' => 5,
        'frame_size' => '54cm',
        'color' => 'Spark Green',
        'is_active' => 1,
        'images' => [
            ['path' => 'TSUNAMI SNM100 ELITE Complete Fixie Bike (Spark Gray).png', 'is_primary' => 1]
        ]
    ],

    // ===== WHEELSET =====
    [
        'category_id' => 3,
        'sku' => 'WHL-01',
        'title' => 'WEAPON C3 Carbon Fiber Classical Trispoke Wheel',
        'brand' => 'WEAPON',
        'description' => 'Wheelset carbon fiber model trispoke klasik dari WEAPON, ringan dan aerodinamis.',
        'price' => 4500000,
        'stock' => 10,
        'frame_size' => null,
        'color' => 'Carbon Black',
        'is_active' => 1,
        'images' => [
            ['path' => 'WEAPON C3 Carbon Fiber Classical Trispoke Wheel.png', 'is_primary' => 1]
        ]
    ],
    [
        'category_id' => 3,
        'sku' => 'WHL-02',
        'title' => 'WEAPON C60 60mm Fixie Carbon Fiber Wheel (Ultralight)',
        'brand' => 'WEAPON',
        'description' => 'Wheelset carbon fiber 60mm dari WEAPON, dibuat ultralight untuk performa maksimal.',
        'price' => 5500000,
        'stock' => 8,
        'frame_size' => null,
        'color' => 'Carbon Black',
        'is_active' => 1,
        'images' => [
            ['path' => 'WEAPON C60 60mm Fixie Carbon Fiber Wheel (Ultralight).png', 'is_primary' => 1]
        ]
    ],

    // ===== SPAREPART =====
    [
        'category_id' => 4,
        'sku' => 'SPT-01',
        'title' => 'E11 Track Chainring Black',
        'brand' => 'E11',
        'description' => 'Chainring track khusus fixie dari E11, warna hitam.',
        'price' => 450000,
        'stock' => 15,
        'frame_size' => null,
        'color' => 'Black',
        'is_active' => 1,
        'images' => [
            ['path' => 'E11 Track Chainring Black.png', 'is_primary' => 1]
        ]
    ],
    [
        'category_id' => 4,
        'sku' => 'SPT-02',
        'title' => 'WEAPON TURBO D29 Disc Track Crankset',
        'brand' => 'WEAPON',
        'description' => 'Crankset track dengan disc, seri TURBO D29 dari WEAPON.',
        'price' => 1350000,
        'stock' => 10,
        'frame_size' => null,
        'color' => 'Black',
        'is_active' => 1,
        'images' => [
            ['path' => 'WEAPON TURBO D29 Disc Track Crankset.png', 'is_primary' => 1]
        ]
    ],
    [
        'category_id' => 4,
        'sku' => 'SPT-03',
        'title' => 'WEAPON Bullet Carbon Fiber Stem',
        'brand' => 'WEAPON',
        'description' => 'Stem carbon fiber ringan model Bullet dari WEAPON.',
        'price' => 650000,
        'stock' => 12,
        'frame_size' => null,
        'color' => 'Carbon Black',
        'is_active' => 1,
        'images' => [
            ['path' => 'WEAPON Bullet Carbon Fiber Stem.png', 'is_primary' => 1]
        ]
    ],
    [
        'category_id' => 4,
        'sku' => 'SPT-04',
        'title' => 'WEAPON ELITE Fixie Chain',
        'brand' => 'WEAPON',
        'description' => 'Rantai fixie kualitas tinggi seri ELITE dari WEAPON.',
        'price' => 250000,
        'stock' => 20,
        'frame_size' => null,
        'color' => 'Silver',
        'is_active' => 1,
        'images' => [
            ['path' => 'WEAPON ELITE Fixie Chain.png', 'is_primary' => 1]
        ]
    ],
    [
        'category_id' => 4,
        'sku' => 'SPT-05',
        'title' => 'WEAPON Seat Clamp (30mm)',
        'brand' => 'WEAPON',
        'description' => 'Seat clamp diameter 30mm dari WEAPON, kokoh dan ringan.',
        'price' => 150000,
        'stock' => 25,
        'frame_size' => null,
        'color' => 'Black',
        'is_active' => 1,
        'images' => [
            ['path' => 'WEAPON Seat Clamp (30mm).png', 'is_primary' => 1]
        ]
    ],
    [
        'category_id' => 4,
        'sku' => 'SPT-06',
        'title' => 'WEAPON Seal Bearing Headset (Black) (41mm-41mm)',
        'brand' => 'WEAPON',
        'description' => 'Headset seal bearing ukuran 41mm-41mm dari WEAPON, warna hitam.',
        'price' => 350000,
        'stock' => 15,
        'frame_size' => null,
        'color' => 'Black',
        'is_active' => 1,
        'images' => [
            ['path' => 'WEAPON Seal Bearing Headset (Black) ( 41mm-41mm).png', 'is_primary' => 1]
        ]
    ],

    // ===== AKSESORIS =====
    [
        'category_id' => 5,
        'sku' => 'AKS-01',
        'title' => 'WEAPON Black Flower Handle Bar Tape (Pure Black)',
        'brand' => 'WEAPON',
        'description' => 'Bar tape motif flower dari WEAPON, warna pure black.',
        'price' => 120000,
        'stock' => 30,
        'frame_size' => null,
        'color' => 'Pure Black',
        'is_active' => 1,
        'images' => [
            ['path' => 'WEAPON Black Flower Handle Bar Tape (Pure Black).png', 'is_primary' => 1]
        ]
    ],
    [
        'category_id' => 5,
        'sku' => 'AKS-02',
        'title' => '700x25c Fixie Inner Tube & Tire Continental',
        'brand' => 'Continental',
        'description' => 'Paket ban luar & ban dalam ukuran 700x25c dari Continental untuk fixie.',
        'price' => 300000,
        'stock' => 20,
        'frame_size' => null,
        'color' => 'Black',
        'is_active' => 1,
        'images' => [
            ['path' => '700x25c Fixie Inner Tube & Tire Continental.png', 'is_primary' => 1]
        ]
    ],

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