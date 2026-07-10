<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold">Produk</h1>
        <p class="text-gray-500 text-sm mt-1">Kelola semua produk toko.</p>
    </div>
    <a href="/admin/products/create" class="inline-flex items-center rounded-lg bg-brand px-4 py-2 text-white text-sm font-medium hover:bg-brand-dark">
        + Tambah Produk
    </a>
</div>

<?php if (empty($products)): ?>
    <!-- Empty state kalau belum ada produk -->
    <div class="rounded-xl border border-gray-200 bg-white p-12 text-center shadow-sm">
        <p class="text-gray-400 text-lg">Belum ada produk</p>
        <p class="text-gray-400 text-sm mt-1">Klik "Tambah Produk" untuk memulai.</p>
    </div>
<?php else: ?>
    <!-- Tabel daftar produk -->
    <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left">
                <tr>
                    <th class="px-4 py-3 font-semibold text-gray-600">Foto</th>
                    <th class="px-4 py-3 font-semibold text-gray-600">SKU</th>
                    <th class="px-4 py-3 font-semibold text-gray-600">Judul</th>
                    <th class="px-4 py-3 font-semibold text-gray-600">Kategori</th>
                    <th class="px-4 py-3 font-semibold text-gray-600 text-right">Harga</th>
                    <th class="px-4 py-3 font-semibold text-gray-600 text-right">Stok</th>
                    <th class="px-4 py-3 font-semibold text-gray-600">Status</th>
                    <th class="px-4 py-3 font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($products as $p): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <?php if ($p['primary_image']): ?>
                                <img src="/uploads/<?= htmlspecialchars($p['primary_image']) ?>" alt="<?= htmlspecialchars($p['title']) ?>"
                                     class="w-12 h-12 object-cover rounded-lg">
                            <?php else: ?>
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 text-xs">No<img></div>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 font-mono text-xs text-gray-500"><?= htmlspecialchars($p['sku']) ?></td>
                        <td class="px-4 py-3 font-medium"><?= htmlspecialchars($p['title']) ?></td>
                        <td class="px-4 py-3 text-gray-600"><?= htmlspecialchars($p['category_name']) ?></td>
                        <td class="px-4 py-3 text-right">Rp<?= number_format($p['price'], 0, ',', '.') ?></td>
                        <td class="px-4 py-3 text-right"><?= (int) $p['stock'] ?></td>
                        <td class="px-4 py-3">
                            <?php if ($p['is_active']): ?>
                                <span class="inline-block rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Aktif</span>
                            <?php else: ?>
                                <span class="inline-block rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <a href="/admin/products/edit?id=<?= $p['id'] ?>"
                                   class="rounded-lg border border-gray-300 bg-white px-3 py-1 text-xs font-medium text-gray-700 hover:bg-gray-50">
                                    Edit
                                </a>
                                <a href="/admin/products/delete?id=<?= $p['id'] ?>"
                                   class="rounded-lg border border-red-200 bg-white px-3 py-1 text-xs font-medium text-red-600 hover:bg-red-50"
                                   onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                    Hapus
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
