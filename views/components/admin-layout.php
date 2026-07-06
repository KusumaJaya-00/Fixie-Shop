<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Admin') ?> — Admin Fixie Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                colors: { brand: { DEFAULT: '#2563eb', dark: '#1d4ed8' } },
                fontFamily: { sans: ['Poppins', 'sans-serif'] }
            } }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-900 font-sans min-h-screen flex">
    <aside class="w-60 bg-slate-900 text-white flex flex-col shrink-0">
        <div class="h-14 flex items-center px-4 text-lg font-bold border-b border-slate-700">
            Fixie Shop
        </div>
        <nav class="flex-1 py-4 space-y-1 px-3 text-sm">
            <a href="/admin" class="block rounded-lg px-3 py-2 hover:bg-slate-700">Dashboard</a>
            <a href="/admin/products" class="block rounded-lg px-3 py-2 hover:bg-slate-700">Produk</a>
            <a href="/admin/orders" class="block rounded-lg px-3 py-2 hover:bg-slate-700">Pesanan</a>
            <a href="/admin/categories" class="block rounded-lg px-3 py-2 hover:bg-slate-700">Kategori</a>
            <a href="/admin/users" class="block rounded-lg px-3 py-2 hover:bg-slate-700">User</a>
        </nav>
        <div class="px-3 py-4 border-t border-slate-700">
            <a href="/logout" class="block rounded-lg px-3 py-2 text-sm hover:bg-slate-700">Logout</a>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-h-screen">
        <header class="h-14 bg-white border-b border-gray-200 flex items-center px-6">
            <span class="text-sm text-gray-500">
                Halo, <span class="font-semibold text-gray-900"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></span>
            </span>
        </header>

        <main class="flex-1 p-6">
            <?php require __DIR__ . '/flash.php'; ?>
            <?= $content ?>
        </main>
    </div>
</body>
</html>
