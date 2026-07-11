<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Katalog Produk</h1>
</div>

<form method="GET" action="/products" class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm mb-6">
    <!-- Search Bar -->
    <div class="flex gap-2 mb-4">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="<?= htmlspecialchars($filters['search'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                   placeholder="Cari produk..."
                   class="w-full rounded-lg border border-gray-300 bg-gray-50 pl-9 pr-3 py-2 text-sm focus:ring-2 focus:ring-brand focus:outline-none focus:bg-white">
        </div>
        <button type="submit" class="rounded-lg bg-brand px-5 py-2 text-sm text-white font-medium hover:bg-brand-dark whitespace-nowrap">
            Cari
        </button>
    </div>

    <!-- Filter Row -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 mb-3">
        <select name="category_id" class="rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:ring-2 focus:ring-brand focus:outline-none focus:bg-white">
            <option value="">Semua Kategori</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= ($filters['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="brand" class="rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:ring-2 focus:ring-brand focus:outline-none focus:bg-white">
            <option value="">Semua Brand</option>
            <?php foreach ($brands as $b): ?>
                <option value="<?= htmlspecialchars($b, ENT_QUOTES, 'UTF-8') ?>" <?= ($filters['brand'] ?? '') === $b ? 'selected' : '' ?>>
                    <?= htmlspecialchars($b, ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="color" class="rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:ring-2 focus:ring-brand focus:outline-none focus:bg-white">
            <option value="">Semua Warna</option>
            <?php foreach ($colors as $c): ?>
                <option value="<?= htmlspecialchars($c, ENT_QUOTES, 'UTF-8') ?>" <?= ($filters['color'] ?? '') === $c ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c, ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="frame_size" class="rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:ring-2 focus:ring-brand focus:outline-none focus:bg-white">
            <option value="">Semua Ukuran</option>
            <?php foreach ($frameSizes as $fs): ?>
                <option value="<?= htmlspecialchars($fs, ENT_QUOTES, 'UTF-8') ?>" <?= ($filters['frame_size'] ?? '') === $fs ? 'selected' : '' ?>>
                    <?= htmlspecialchars($fs, ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div class="flex items-center gap-1">
            <input type="number" name="price_min" value="<?= htmlspecialchars($filters['price_min'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                   placeholder="Min" min="0"
                   class="w-full rounded-lg border border-gray-300 bg-gray-50 px-2 py-2 text-sm focus:ring-2 focus:ring-brand focus:outline-none focus:bg-white">
            <span class="text-gray-400 shrink-0">—</span>
            <input type="number" name="price_max" value="<?= htmlspecialchars($filters['price_max'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                   placeholder="Max" min="0"
                   class="w-full rounded-lg border border-gray-300 bg-gray-50 px-2 py-2 text-sm focus:ring-2 focus:ring-brand focus:outline-none focus:bg-white">
        </div>

        <div class="flex gap-2">
            <button type="submit" class="flex-1 rounded-lg bg-brand px-3 py-2 text-sm text-white font-medium hover:bg-brand-dark">
                Filter
            </button>
            <a href="/products" class="flex-1 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-600 font-medium hover:bg-gray-50 text-center">
                Reset
            </a>
        </div>
    </div>

    <!-- Sort & Result Count -->
    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
        <p class="text-sm text-gray-500">Menampilkan <?= count($products) ?> produk</p>
        <div class="flex items-center gap-2">
            <label class="text-sm text-gray-500 hidden sm:inline">Urutkan:</label>
            <select name="sort" onchange="this.form.submit()" class="rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm focus:ring-2 focus:ring-brand focus:outline-none">
                <option value="terbaru" <?= ($filters['sort'] ?? 'terbaru') === 'terbaru' ? 'selected' : '' ?>>Terbaru</option>
                <option value="termurah" <?= ($filters['sort'] ?? '') === 'termurah' ? 'selected' : '' ?>>Termurah</option>
                <option value="termahal" <?= ($filters['sort'] ?? '') === 'termahal' ? 'selected' : '' ?>>Termahal</option>
                <option value="nama-asc" <?= ($filters['sort'] ?? '') === 'nama-asc' ? 'selected' : '' ?>>A-Z</option>
                <option value="nama-desc" <?= ($filters['sort'] ?? '') === 'nama-desc' ? 'selected' : '' ?>>Z-A</option>
            </select>
        </div>
    </div>
</form>

<?php if (empty($products)): ?>
    <div class="text-center py-16">
        <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
        </svg>
        <p class="text-gray-500 font-medium">Produk tidak ditemukan</p>
        <p class="text-gray-400 text-sm mt-1">Coba ubah kata kunci atau filter yang digunakan.</p>
    </div>
<?php else: ?>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <?php foreach ($products as $product): ?>
            <?php require __DIR__ . '/../components/product-card.php'; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>