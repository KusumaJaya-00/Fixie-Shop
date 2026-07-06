<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Fixie Shop') ?> — Fixie Shop</title>
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
<body class="bg-gray-50 text-gray-900 font-sans min-h-screen flex flex-col">
    <nav class="bg-slate-900 text-white">
        <div class="max-w-6xl mx-auto px-4 flex items-center justify-between h-14">
            <a href="/" class="text-lg font-bold">Fixie Shop</a>
            <div class="flex items-center gap-4 text-sm">
                <a href="/" class="hover:text-brand">Katalog</a>
                <?php if (checkLogin()): ?>
                    <a href="/cart" class="hover:text-brand">Keranjang</a>
                    <a href="/profile" class="hover:text-brand">Profile</a>
                    <span class="text-gray-400"><?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></span>
                    <a href="/logout" class="hover:text-brand">Logout</a>
                <?php else: ?>
                    <a href="/login" class="hover:text-brand">Login</a>
                    <a href="/register" class="hover:text-brand">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="flex-1 max-w-6xl mx-auto px-4 py-6 w-full">
        <?php require __DIR__ . '/flash.php'; ?>
        <?= $content ?>
    </main>

    <footer class="bg-slate-900 text-white text-center text-sm py-4">
        &copy; <?= date('Y') ?> Fixie Shop. All rights reserved.
    </footer>
</body>
</html>
