<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold">Produk</h1>
        <p class="text-gray-500 text-sm mt-1">Kelola semua produk toko.</p>
    </div>
    <!-- Tombol kelola kategori (modal) + tambah produk -->
    <div class="flex gap-2">
        <button type="button" onclick="document.getElementById('modal-kategori').classList.remove('hidden')"
                class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Kelola Kategori
        </button>
        <a href="/admin/products/create" class="inline-flex items-center rounded-lg bg-brand px-4 py-2 text-white text-sm font-medium hover:bg-brand-dark">
            + Tambah Produk
        </a>
    </div>
</div>

<?php if (empty($products)): ?>
    <div class="rounded-xl border border-gray-200 bg-white p-12 text-center shadow-sm">
        <p class="text-gray-400 text-lg">Belum ada produk</p>
        <p class="text-gray-400 text-sm mt-1">Klik "Tambah Produk" untuk memulai.</p>
    </div>
<?php else: ?>
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

<!-- Modal Kelola Kategori -->
<div id="modal-kategori" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40" onclick="if(event.target===this) this.classList.add('hidden')">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg mx-4 max-h-[80vh] flex flex-col">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-bold">Kelola Kategori</h2>
            <button type="button" onclick="document.getElementById('modal-kategori').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-700 text-xl leading-none">&times;</button>
        </div>

        <div class="p-6 overflow-y-auto">
            <!-- Form tambah kategori -->
            <form method="POST" action="/admin/categories/store" class="flex gap-2 mb-4">
                    <input type="hidden" name="_csrf_token" value="<?= generateCsrfToken() ?>">
                <input type="text" name="name" placeholder="Nama kategori baru" required
                       class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-brand">
                <button type="submit" class="rounded-lg bg-brand px-4 py-2 text-sm text-white font-medium hover:bg-brand-dark shrink-0">Tambah</button>
            </form>

            <?php if (empty($categories)): ?>
                <p class="text-gray-400 text-sm text-center py-4">Belum ada kategori.</p>
            <?php else: ?>
                <!-- Daftar kategori + inline edit -->
                <div class="space-y-2">
                    <?php foreach ($categories as $cat): ?>
                        <!-- Tiap kategori: tampilan baca ↔ edit inline -->
                        <form method="POST" action="/admin/categories/update" class="rounded-lg border border-gray-200 px-4 py-2.5 flex items-center justify-between">
                            <input type="hidden" name="_csrf_token" value="<?= generateCsrfToken() ?>">
                            <input type="hidden" name="id" value="<?= $cat['id'] ?>">

                            <!-- Tampilan baca: teks biasa + tombol Edit/Hapus -->
                            <span id="cat-text-<?= $cat['id'] ?>" class="text-sm font-medium flex-1"><?= htmlspecialchars($cat['name']) ?></span>
                            <input id="cat-input-<?= $cat['id'] ?>" type="text" name="name"
                                   value="<?= htmlspecialchars($cat['name']) ?>" required
                                   class="hidden flex-1 rounded-lg border border-gray-300 px-3 py-1.5 text-sm focus:ring-2 focus:ring-brand">

                            <div id="cat-actions-<?= $cat['id'] ?>" class="flex gap-2 shrink-0 ml-2">
                                <button type="button" onclick="editKategori(<?= $cat['id'] ?>)"
                                        class="text-xs text-brand hover:underline">Edit</button>
                                <a href="/admin/categories/delete?id=<?= $cat['id'] ?>"
                                   class="text-xs text-red-600 hover:underline"
                                   onclick="return confirm('Yakin ingin menghapus kategori ini?')">Hapus</a>
                            </div>

                            <!-- Tampilan edit: input + tombol Simpan/Batal -->
                            <div id="cat-edit-actions-<?= $cat['id'] ?>" class="hidden gap-2 shrink-0 ml-2">
                                <button type="submit" class="rounded-lg bg-brand px-3 py-1.5 text-xs text-white font-medium hover:bg-brand-dark">Simpan</button>
                                <button type="button" onclick="batalEdit(<?= $cat['id'] ?>, '<?= htmlspecialchars($cat['name'], ENT_QUOTES) ?>')"
                                        class="rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs text-gray-700 font-medium hover:bg-gray-50">Batal</button>
                            </div>
                        </form>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function editKategori(id) {
    document.getElementById('cat-text-' + id).classList.add('hidden');
    document.getElementById('cat-actions-' + id).classList.add('hidden');
    document.getElementById('cat-input-' + id).classList.remove('hidden');
    document.getElementById('cat-edit-actions-' + id).classList.remove('hidden');
    document.getElementById('cat-input-' + id).focus();
}

function batalEdit(id, originalName) {
    document.getElementById('cat-text-' + id).classList.remove('hidden');
    document.getElementById('cat-actions-' + id).classList.remove('hidden');
    document.getElementById('cat-input-' + id).classList.add('hidden');
    document.getElementById('cat-edit-actions-' + id).classList.add('hidden');
    document.getElementById('cat-input-' + id).value = originalName;
}
</script>