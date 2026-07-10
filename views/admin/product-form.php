<div>
    <a href="/admin/products" class="text-sm text-brand hover:underline">&larr; Kembali</a>
    <h1 class="text-xl font-bold mt-2 mb-6"><?= isset($product) ? 'Edit Produk' : 'Tambah Produk' ?></h1>

    <form method="POST" enctype="multipart/form-data" class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="title" class="block text-sm font-medium mb-1">Judul Produk <span class="text-red-500">*</span></label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($_POST['title'] ?? ($product['title'] ?? '')) ?>"
                       class="w-full rounded-lg border <?= isset($errors['title']) ? 'border-red-500' : 'border-gray-300' ?> px-3 py-2 focus:ring-2 focus:ring-brand"
                       required>
                <?php if (isset($errors['title'])): ?>
                    <p class="text-red-600 text-sm mt-1"><?= htmlspecialchars($errors['title']) ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label for="brand" class="block text-sm font-medium mb-1">Brand</label>
                <input type="text" id="brand" name="brand" value="<?= htmlspecialchars($_POST['brand'] ?? ($product['brand'] ?? '')) ?>"
                       class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-brand">
            </div>

            <div>
                <label for="category_id" class="block text-sm font-medium mb-1">Kategori <span class="text-red-500">*</span></label>
                <select id="category_id" name="category_id"
                        class="w-full rounded-lg border <?= isset($errors['category_id']) ? 'border-red-500' : 'border-gray-300' ?> px-3 py-2 focus:ring-2 focus:ring-brand"
                        required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php $selectedCat = (int) ($_POST['category_id'] ?? ($product['category_id'] ?? 0)); ?>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $selectedCat === (int) $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['category_id'])): ?>
                    <p class="text-red-600 text-sm mt-1"><?= htmlspecialchars($errors['category_id']) ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label for="price" class="block text-sm font-medium mb-1">Harga (Rp) <span class="text-red-500">*</span></label>
                <input type="number" id="price" name="price" min="0" step="0.01"
                       value="<?= htmlspecialchars($_POST['price'] ?? ($product['price'] ?? '')) ?>"
                       class="w-full rounded-lg border <?= isset($errors['price']) ? 'border-red-500' : 'border-gray-300' ?> px-3 py-2 focus:ring-2 focus:ring-brand"
                       required>
                <?php if (isset($errors['price'])): ?>
                    <p class="text-red-600 text-sm mt-1"><?= htmlspecialchars($errors['price']) ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label for="stock" class="block text-sm font-medium mb-1">Stok <span class="text-red-500">*</span></label>
                <input type="number" id="stock" name="stock" min="0"
                       value="<?= htmlspecialchars($_POST['stock'] ?? ($product['stock'] ?? '')) ?>"
                       class="w-full rounded-lg border <?= isset($errors['stock']) ? 'border-red-500' : 'border-gray-300' ?> px-3 py-2 focus:ring-2 focus:ring-brand"
                       required>
                <?php if (isset($errors['stock'])): ?>
                    <p class="text-red-600 text-sm mt-1"><?= htmlspecialchars($errors['stock']) ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label for="frame_size" class="block text-sm font-medium mb-1">Ukuran Frame</label>
                <input type="text" id="frame_size" name="frame_size" value="<?= htmlspecialchars($_POST['frame_size'] ?? ($product['frame_size'] ?? '')) ?>"
                       class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-brand"
                       placeholder="Contoh: M, L, XL, 52cm">
            </div>

            <div>
                <label for="color" class="block text-sm font-medium mb-1">Warna</label>
                <input type="text" id="color" name="color" value="<?= htmlspecialchars($_POST['color'] ?? ($product['color'] ?? '')) ?>"
                       class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-brand"
                       placeholder="Contoh: Hitam, Putih, Biru">
            </div>

            <div class="flex items-center">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1"
                           <?= (isset($_POST['is_active']) || (isset($product) && $product['is_active'])) ? 'checked' : '' ?>
                           class="rounded border-gray-300 text-brand focus:ring-brand">
                    <span class="text-sm font-medium">Produk Aktif</span>
                </label>
            </div>
        </div>

        <div>
            <label for="description" class="block text-sm font-medium mb-1">Deskripsi</label>
            <textarea id="description" name="description" rows="4"
                      class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-brand"><?= htmlspecialchars($_POST['description'] ?? ($product['description'] ?? '')) ?></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Foto Produk</label>
            <p class="text-xs text-gray-500 mb-2">Format: JPG/PNG. Maks 2MB per file. Bisa pilih banyak file sekaligus.</p>
            <input type="file" name="images[]" multiple accept=".jpg,.jpeg,.png"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-brand file:px-3 file:py-1 file:text-white file:text-sm file:font-medium hover:file:bg-brand-dark">
        </div>

        <?php if (isset($product) && !empty($images)): ?>
            <div>
                <p class="text-sm font-medium mb-2">Foto Saat Ini</p>
                <div class="grid grid-cols-3 md:grid-cols-4 gap-3">
                    <?php foreach ($images as $img): ?>
                        <div class="relative group">
                            <img src="/uploads/<?= htmlspecialchars($img['image_path']) ?>" alt="Foto produk"
                                 class="w-full h-24 object-cover rounded-lg border <?= $img['is_primary'] ? 'border-2 border-brand' : 'border-gray-200' ?>">
                            <?php if ($img['is_primary']): ?>
                                <span class="absolute top-1 left-1 bg-brand text-white text-[10px] px-1.5 py-0.5 rounded-full font-medium">Utama</span>
                            <?php endif; ?>
                            <div class="absolute bottom-1 right-1 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <?php if (!$img['is_primary']): ?>
                                    <a href="/admin/products/primary?image_id=<?= $img['id'] ?>&product_id=<?= $product['id'] ?>"
                                       class="bg-white text-xs rounded px-1.5 py-0.5 shadow hover:bg-gray-100"
                                       title="Jadikan utama">Utama</a>
                                <?php endif; ?>
                                <a href="/admin/products/delete-image?image_id=<?= $img['id'] ?>&product_id=<?= $product['id'] ?>"
                                   class="bg-white text-xs rounded px-1.5 py-0.5 shadow text-red-600 hover:bg-gray-100"
                                   onclick="return confirm('Hapus foto ini?')"
                                   title="Hapus">Hapus</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="flex justify-end gap-2 pt-2">
            <a href="/admin/products" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-700 font-medium hover:bg-gray-50">Batal</a>
            <button type="submit" class="rounded-lg bg-brand px-4 py-2 text-sm text-white font-medium hover:bg-brand-dark">
                <?= isset($product) ? 'Simpan Perubahan' : 'Simpan' ?>
            </button>
        </div>
    </form>
</div>
