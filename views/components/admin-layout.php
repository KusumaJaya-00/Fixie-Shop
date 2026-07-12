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
<body class="bg-gray-50 text-gray-900 font-sans h-screen overflow-hidden flex">
    <aside class="w-60 bg-slate-900 text-white flex flex-col shrink-0 h-screen sticky top-0">
        <div class="h-14 flex items-center px-4 text-lg font-bold border-b border-slate-700 shrink-0">
            Fixie Shop
        </div>
        <nav class="flex-1 py-4 space-y-1 px-3 text-sm overflow-y-auto">
            <a href="/admin" class="block rounded-lg px-3 py-2 hover:bg-slate-700">Dashboard</a>
            <a href="/admin/products" class="block rounded-lg px-3 py-2 hover:bg-slate-700">Produk</a>
            <a href="/admin/orders" class="block rounded-lg px-3 py-2 hover:bg-slate-700">Pesanan</a>
            <a href="/admin/users" class="block rounded-lg px-3 py-2 hover:bg-slate-700">User</a>
        </nav>
        <div class="px-3 py-4 border-t border-slate-700 space-y-1 shrink-0">
            <a href="/" class="block rounded-lg px-3 py-2 text-sm hover:bg-slate-700">Halaman Utama</a>
            <a href="/logout" class="block rounded-lg px-3 py-2 text-sm hover:bg-slate-700">Logout</a>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-screen">
        <main class="flex-1 p-6 overflow-y-auto">
            <?php require __DIR__ . '/flash.php'; ?>
            <?= $content ?>
        </main>
    </div>
</body>
</html>
