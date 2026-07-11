<h1 class="text-2xl font-bold mb-6">Katalog Produk</h1>

<!-- Filter -->
<form method="GET" action="/products" class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm mb-6">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Search -->
        <div class="col-span-2 md:col-span-4">
            <label class="block text-sm font-medium mb-1">Cari Produk</label>
            <input type="text" name="search" value="<?= htmlspecialchars($filters['search'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                   placeholder="Nama produk..."
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-brand focus:outline-none">
        </div>

        <!-- Kategori -->
        <div>
            <label class="block text-sm font-medium mb-1">Kategori</label>
            <select name="category_id" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-brand focus:outline-none">
                <option value="">Semua</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($filters['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Brand -->
        <div>
            <label class="block text-sm font-medium mb-1">Brand</label>
            <input type="text" name="brand" value="<?= htmlspecialchars($filters['brand'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                   placeholder="Contoh: Cinelli"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-brand focus:outline-none">
        </div>

        <!-- Warna -->
        <div>
            <label class="block text-sm font-medium mb-1">Warna</label>
            <input type="text" name="color" value="<?= htmlspecialchars($filters['color'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                   placeholder="Contoh: Hitam"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-brand focus:outline-none">
        </div>

        <!-- Frame Size -->
        <div>
            <label class="block text-sm font-medium mb-1">Frame Size</label>
            <input type="text" name="frame_size" value="<?= htmlspecialchars($filters['frame_size'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                   placeholder="Contoh: 52cm"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-brand focus:outline-none">
        </div>

        <!-- Harga Min -->
        <div>
            <label class="block text-sm font-medium mb-1">Harga Min</label>
            <input type="number" name="price_min" value="<?= htmlspecialchars($filters['price_min'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                   placeholder="0" min="0"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-brand focus:outline-none">
        </div>

        <!-- Harga Max -->
        <div>
            <label class="block text-sm font-medium mb-1">Harga Max</label>
            <input type="number" name="price_max" value="<?= htmlspecialchars($filters['price_max'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                   placeholder="0" min="0"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-brand focus:outline-none">
        </div>

        <!-- Tombol -->
        <div class="col-span-2 md:col-span-4 flex gap-2">
            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-brand px-4 py-2 text-sm text-white font-medium hover:bg-brand-dark">
                Cari
            </button>
            <a href="/products" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-700 font-medium hover:bg-gray-50">
                Reset
            </a>
        </div>
    </div>
</form>

<!-- Grid Produk -->
<?php if (empty($products)): ?>
    <div class="text-center py-16">
        <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
        </svg>
        <p class="text-gray-500 font-medium">Belum ada produk</p>
        <p class="text-gray-400 text-sm mt-1">Produk yang tersedia akan tampil di sini.</p>
    </div>
<?php else: ?>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <?php foreach ($products as $product): ?>
            <?php require __DIR__ . '/../components/product-card.php'; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
