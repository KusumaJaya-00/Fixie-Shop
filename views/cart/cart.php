<?php
// View halaman keranjang: list item cart + total + tombol checkout.
// Data ($items, $total) sudah disiapkan & disinkronkan ke stok terbaru oleh CartController::index().
$e = fn(string $v) => htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
?>

<h1 class="text-2xl font-bold mb-6">Keranjang Belanja</h1>

<?php if (empty($items)): ?>
    <div class="text-center py-16">
        <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
        </svg>
        <p class="text-gray-500 font-medium">Keranjang kamu masih kosong</p>
        <p class="text-gray-400 text-sm mt-1">Yuk cari sepeda fixie favoritmu!</p>
        <a href="/" class="inline-flex items-center justify-center rounded-lg bg-brand px-4 py-2 text-white font-medium hover:bg-brand-dark mt-4">
            Lihat Katalog
        </a>
    </div>
<?php else: ?>
    <div class="space-y-4">
        <?php foreach ($items as $item):
            $product = $item['product'];
            $imgSrc = $item['primary_image']
                ? '/uploads/' . $e($item['primary_image'])
                : '/assets/img/no-image.png';
        ?>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                <a href="/product?id=<?= (int) $product['id'] ?>" class="flex-shrink-0">
                    <img src="<?= $imgSrc ?>" alt="<?= $e($product['title']) ?>"
                         class="w-20 h-20 rounded-lg object-cover bg-gray-100" loading="lazy">
                </a>

                <div class="flex-1 min-w-0">
                    <a href="/product?id=<?= (int) $product['id'] ?>" class="text-sm font-semibold hover:text-brand transition">
                        <?= $e($product['title']) ?>
                    </a>
                    <p class="text-sm text-gray-500 mt-0.5">Rp<?= number_format($product['price'], 0, ',', '.') ?></p>
                </div>

                <!-- Update qty: tombol +/- ubah value lalu langsung submit form (lihat script di bawah),
                     jadi tiap klik itu request POST baru ke /cart/update -->
                <form method="POST" action="/cart/update" class="cart-qty-form flex items-center gap-2">
                    <input type="hidden" name="_csrf_token" value="<?= generateCsrfToken() ?>">
                    <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
                    <div class="inline-flex items-center rounded-lg border border-gray-300">
                        <button type="button" aria-label="Kurangi jumlah"
                                class="qty-dec w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100 rounded-l-lg transition">−</button>
                        <input type="text" name="qty" value="<?= (int) $item['qty'] ?>"
                               data-max="<?= (int) $product['stock'] ?>"
                               class="qty-val w-10 h-8 text-center text-sm border-x border-gray-300 bg-white" readonly>
                        <button type="button" aria-label="Tambah jumlah"
                                class="qty-inc w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100 rounded-r-lg transition">+</button>
                    </div>
                </form>

                <p class="text-sm font-bold text-brand w-28 text-right">Rp<?= number_format($item['subtotal'], 0, ',', '.') ?></p>

                <!-- Hapus: minta konfirmasi dulu biar gak kehapus gara-gara salah klik -->
                <form method="POST" action="/cart/remove"
                      onsubmit="return confirm('Hapus produk ini dari keranjang?')">
                    <input type="hidden" name="_csrf_token" value="<?= generateCsrfToken() ?>">
                    <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
                    <button type="submit" class="text-red-600 hover:text-red-700 transition p-1" title="Hapus">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Total & Checkout -->
    <div class="mt-6 rounded-xl border border-gray-200 bg-white p-4 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <p class="text-lg font-semibold">Total: <span class="text-brand">Rp<?= number_format($total, 0, ',', '.') ?></span></p>
        <a href="/checkout"
           class="inline-flex items-center justify-center rounded-lg bg-brand px-6 py-2 text-white font-medium hover:bg-brand-dark transition">
            Checkout
        </a>
    </div>
<?php endif; ?>

<script>
// Tiap form qty per item ditangani sendiri-sendiri, klik +/- langsung form.submit()
// (reload halaman) supaya subtotal & total di server selalu ke-update pakai harga/stok terbaru
document.querySelectorAll('.cart-qty-form').forEach(function(form) {
    var input = form.querySelector('.qty-val');
    var dec = form.querySelector('.qty-dec');
    var inc = form.querySelector('.qty-inc');
    var max = parseInt(input.dataset.max) || 1;

    function updateBtns() {
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
        if (v > 1) { input.value = v - 1; form.submit(); }
    });

    inc.addEventListener('click', function() {
        var v = parseInt(input.value) || 1;
        if (v < max) { input.value = v + 1; form.submit(); }
    });

    updateBtns();
});
</script>
