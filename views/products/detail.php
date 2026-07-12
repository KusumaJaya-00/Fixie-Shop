<?php
$e = fn(string $v) => htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
$outOfStock = (int) $product['stock'] === 0;
?>

<a href="/" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-brand mb-6">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
    Kembali ke Katalog
</a>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <!-- Galeri Foto -->
    <div class="space-y-3">
        <?php if (empty($images)): ?>
            <div class="aspect-[4/3] rounded-xl overflow-hidden bg-gray-100">
                <img src="/assets/img/no-image.png" alt="Tidak ada foto" class="w-full h-full object-cover">
            </div>
        <?php else: ?>
            <div class="aspect-[4/3] rounded-xl overflow-hidden bg-gray-100">
                <img id="main-image"
                     src="/uploads/<?= $e($images[0]['image_path']) ?>"
                     alt="<?= $e($product['title']) ?>"
                     class="w-full h-full object-cover">
            </div>
            <?php if (count($images) > 1): ?>
                <div class="flex gap-2 overflow-x-auto">
                    <?php foreach ($images as $i => $img): ?>
                        <button type="button"
                                class="gallery-thumb flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden border-2 <?= $i === 0 ? 'border-brand' : 'border-gray-200' ?> hover:border-brand transition"
                                data-src="/uploads/<?= $e($img['image_path']) ?>">
                            <img src="/uploads/<?= $e($img['image_path']) ?>"
                                 alt="Foto <?= $i + 1 ?>"
                                 class="w-full h-full object-cover">
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Info Produk -->
    <div class="space-y-4">
        <p class="text-sm text-gray-500"><?= $e($product['category_name']) ?></p>
        <h1 class="text-2xl font-bold"><?= $e($product['title']) ?></h1>

        <?php if (!empty($product['brand'])): ?>
            <p class="text-sm text-gray-500">Brand: <span class="text-gray-900 font-medium"><?= $e($product['brand']) ?></span></p>
        <?php endif; ?>

        <p class="text-2xl font-bold text-brand">Rp<?= number_format($product['price'], 0, ',', '.') ?></p>

        <!-- Spesifikasi -->
        <div class="grid grid-cols-2 gap-3 text-sm">
            <?php if (!empty($product['frame_size'])): ?>
                <div>
                    <span class="text-gray-500">Frame Size</span>
                    <p class="font-medium"><?= $e($product['frame_size']) ?></p>
                </div>
            <?php endif; ?>
            <?php if (!empty($product['color'])): ?>
                <div>
                    <span class="text-gray-500">Warna</span>
                    <p class="font-medium"><?= $e($product['color']) ?></p>
                </div>
            <?php endif; ?>
            <div>
                <span class="text-gray-500">Stok</span>
                <?php if ($outOfStock): ?>
                    <p><span class="rounded-full bg-red-600 px-2 py-0.5 text-xs font-medium text-white">Stok Habis</span></p>
                <?php else: ?>
                    <p class="font-medium"><?= (int) $product['stock'] ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Form Tambah ke Keranjang -->
        <form method="POST" action="/cart/add" class="flex items-end gap-3 pt-2">
            <input type="hidden" name="_csrf_token" value="<?= generateCsrfToken() ?>">
            <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
            <div>
                <label class="block text-sm font-medium mb-1">Jumlah</label>
                <div class="inline-flex items-center rounded-lg border border-gray-300">
                    <button type="button" id="qty-dec" aria-label="Kurangi jumlah"
                            class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100 rounded-l-lg transition <?= $outOfStock ? 'opacity-50 cursor-not-allowed' : '' ?>"
                            <?= $outOfStock ? 'disabled' : '' ?>>−</button>
                    <input type="text" id="qty" name="qty" value="1" readonly
                           data-max="<?= (int) $product['stock'] ?>"
                           class="w-10 h-8 text-center text-sm border-x border-gray-300 bg-white"
                           <?= $outOfStock ? 'disabled' : '' ?>>
                    <button type="button" id="qty-inc" aria-label="Tambah jumlah"
                            class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100 rounded-r-lg transition <?= $outOfStock ? 'opacity-50 cursor-not-allowed' : '' ?>"
                            <?= $outOfStock ? 'disabled' : '' ?>>+</button>
                </div>
            </div>
            <button type="submit"
                    class="inline-flex items-center justify-center rounded-lg px-6 py-2 text-white font-medium transition <?= $outOfStock ? 'bg-gray-400 opacity-50 cursor-not-allowed' : 'bg-brand hover:bg-brand-dark' ?>"
                    <?= $outOfStock ? 'disabled' : '' ?>>
                <?= $outOfStock ? 'Stok Habis' : 'Tambah ke Keranjang' ?>
            </button>
        </form>

        <!-- Deskripsi -->
        <?php if (!empty($product['description'])): ?>
            <div class="pt-4 border-t border-gray-200">
                <h2 class="text-lg font-semibold mb-2">Deskripsi</h2>
                <div class="text-sm text-gray-700 leading-relaxed">
                    <?= nl2br($e($product['description'])) ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.querySelectorAll('.gallery-thumb').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.getElementById('main-image').src = this.dataset.src;
        document.querySelectorAll('.gallery-thumb').forEach(function(b) {
            b.classList.replace('border-brand', 'border-gray-200');
        });
        this.classList.replace('border-gray-200', 'border-brand');
    });
});

(function() {
    var input = document.getElementById('qty');
    var dec = document.getElementById('qty-dec');
    var inc = document.getElementById('qty-inc');
    if (!input || !dec || !inc) return;
    var max = parseInt(input.dataset.max) || 1;

    function update() {
        var v = parseInt(input.value) || 1;
        dec.disabled = v <= 1;
        inc.disabled = v >= max;
        dec.classList.toggle('opacity-50', v <= 1);
        dec.classList.toggle('cursor-not-allowed', v <= 1);
        inc.classList.toggle('opacity-50', v >= max);
        inc.classList.toggle('cursor-not-allowed', v >= max);
    }

    dec.addEventListener('click', function() {
        var v = parseInt(input.value) || 1;
        if (v > 1) { input.value = v - 1; update(); }
    });

    inc.addEventListener('click', function() {
        var v = parseInt(input.value) || 1;
        if (v < max) { input.value = v + 1; update(); }
    });

    update();
})();
</script>
