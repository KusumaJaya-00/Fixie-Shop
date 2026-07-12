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
    <nav class="bg-slate-900/95 backdrop-blur text-white sticky top-0 z-50 shadow-lg">
        <div class="max-w-6xl mx-auto px-4 flex items-center justify-between h-14">
            <a href="/" class="text-lg font-bold">Fixie Shop</a>
            <div class="flex items-center gap-4 text-sm">
                <a href="/" class="hover:text-brand">Katalog</a>
                <?php if (checkLogin()): ?>
                    <a href="/cart" class="hover:text-brand">Keranjang</a>
                    <div class="relative">
                        <button onclick="toggleProfile()" class="rounded-full p-1.5 hover:bg-slate-700 transition" title="Profil">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A9 9 0 0112 15a9 9 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </button>
                        <div id="profile-dropdown" class="hidden absolute right-0 mt-2 w-44 rounded-lg border border-gray-200 bg-white shadow-lg z-50 py-1 text-sm text-gray-700">
                            <a href="/profile" class="block px-4 py-2 hover:bg-gray-50">Profil</a>
                            <?php if (isAdmin()): ?>
                                <a href="/admin" class="block px-4 py-2 hover:bg-gray-50">Dashboard</a>
                            <?php endif; ?>
                            <hr class="border-gray-100 my-1">
                            <a href="/logout" class="block px-4 py-2 text-red-600 hover:bg-red-50">Logout</a>
                        </div>
                    </div>
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
<script>
function toggleProfile() {
    document.getElementById('profile-dropdown').classList.toggle('hidden');
}
document.addEventListener('click', function(e) {
    const dd = document.getElementById('profile-dropdown');
    if (dd && !dd.parentElement.contains(e.target)) {
        dd.classList.add('hidden');
    }
});
</script>
</body>
</html>
