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
            <a href="/admin/users" class="block rounded-lg px-3 py-2 hover:bg-slate-700">User</a>
        </nav>
        <div class="px-3 py-4 border-t border-slate-700 space-y-1">
            <a href="/" class="block rounded-lg px-3 py-2 text-sm hover:bg-slate-700">Halaman Utama</a>
            <a href="/logout" class="block rounded-lg px-3 py-2 text-sm hover:bg-slate-700">Logout</a>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-h-screen">
        <header class="h-14 bg-white border-b border-gray-200 flex items-center justify-end px-6">
            <div class="relative">
                <button onclick="toggleProfile()" class="rounded-full p-1.5 hover:bg-gray-100 transition" title="Profil">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A9 9 0 0112 15a9 9 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </button>
                <div id="profile-dropdown" class="hidden absolute right-0 mt-2 w-44 rounded-lg border border-gray-200 bg-white shadow-lg z-50 py-1 text-sm">
                    <a href="/profile" class="block px-4 py-2 hover:bg-gray-50">Profil</a>
                    <a href="/admin" class="block px-4 py-2 hover:bg-gray-50">Dashboard</a>
                    <hr class="border-gray-100 my-1">
                    <a href="/logout" class="block px-4 py-2 text-red-600 hover:bg-red-50">Logout</a>
                </div>
            </div>
        </header>

        <main class="flex-1 p-6">
            <?php require __DIR__ . '/flash.php'; ?>
            <?= $content ?>
        </main>
    </div>
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
