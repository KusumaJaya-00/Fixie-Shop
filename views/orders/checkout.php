<?php
$e = fn(string $v) => htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
?>

<h1 class="text-2xl font-bold mb-6">Checkout</h1>

<!-- Ringkasan Pesanan -->
<div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm mb-6">
    <h2 class="text-lg font-semibold mb-4">Ringkasan Pesanan</h2>
    
    <div class="space-y-4">
        <?php foreach ($items as $item):
            $product = $item['product'];
            $imgSrc = $item['primary_image']
                ? '/uploads/' . $e($item['primary_image'])
                : '/assets/img/no-image.png';
        ?>
            <div class="flex items-center gap-4 pb-4 border-b border-gray-100 last:border-0">
                <img src="<?= $imgSrc ?>" alt="<?= $e($product['title']) ?>"
                     class="w-16 h-16 rounded-lg object-cover bg-gray-100 flex-shrink-0" loading="lazy">
                
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold"><?= $e($product['title']) ?></p>
                    <p class="text-sm text-gray-500 mt-0.5">
                        <?= (int) $item['qty'] ?> × Rp<?= number_format($product['price'], 0, ',', '.') ?>
                    </p>
                </div>
                
                <p class="text-sm font-bold text-brand">Rp<?= number_format($item['subtotal'], 0, ',', '.') ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center">
        <p class="text-lg font-semibold">Total</p>
        <p class="text-2xl font-bold text-brand">Rp<?= number_format($total, 0, ',', '.') ?></p>
    </div>
</div>

<!-- Form Upload Bukti Transfer -->
<div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
    <h2 class="text-lg font-semibold mb-4">Upload Bukti Transfer</h2>
    
    <form method="POST" action="/checkout" enctype="multipart/form-data" id="checkoutForm">
        <input type="hidden" name="_csrf_token" value="<?= generateCsrfToken() ?>">
        
        <div class="mb-6">
            <label for="shipping_address" class="block text-sm font-medium mb-2">
                Alamat Pengiriman <span class="text-red-600">*</span>
            </label>
            <textarea id="shipping_address" name="shipping_address" rows="3" required
                      class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-brand"
                      placeholder="Masukkan alamat lengkap pengiriman"></textarea>
            <p class="text-xs text-gray-500 mt-1">Alamat harus diisi lengkap (jalan, RT/RW, kelurahan, kecamatan, kota, kode pos).</p>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium mb-2">
                Pilih Ongkos Kirim <span class="text-red-600">*</span>
            </label>
            <div class="space-y-2">
                <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-300 hover:border-brand cursor-pointer transition">
                    <input type="radio" name="shipping_cost" value="15000" required
                           class="w-4 h-4 text-brand focus:ring-2 focus:ring-brand">
                    <div class="flex-1">
                        <p class="text-sm font-medium">Reguler</p>
                        <p class="text-xs text-gray-500">3-5 hari kerja</p>
                    </div>
                    <p class="text-sm font-semibold text-brand">Rp15.000</p>
                </label>
                <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-300 hover:border-brand cursor-pointer transition">
                    <input type="radio" name="shipping_cost" value="30000" required
                           class="w-4 h-4 text-brand focus:ring-2 focus:ring-brand">
                    <div class="flex-1">
                        <p class="text-sm font-medium">Express</p>
                        <p class="text-xs text-gray-500">1-2 hari kerja</p>
                    </div>
                    <p class="text-sm font-semibold text-brand">Rp30.000</p>
                </label>
            </div>
        </div>

        <div class="mb-6">
            <label for="proof" class="block text-sm font-medium mb-2">
                Bukti Transfer <span class="text-red-600">*</span>
            </label>
            <input type="file" id="proof" name="proof" accept="image/*" required
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-brand">
            <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG. Maksimal 2MB.</p>
        </div>

        <div class="flex gap-3">
            <a href="/cart"
               class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-6 py-2 text-sm text-gray-700 font-medium hover:bg-gray-50 transition">
                Kembali ke Keranjang
            </a>
            <button type="submit" id="submitBtn"
                    class="inline-flex items-center justify-center rounded-lg bg-brand px-6 py-2 text-sm text-white font-medium hover:bg-brand-dark transition">
                Proses Checkout
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.textContent = 'Memproses...';
    btn.classList.add('opacity-50', 'cursor-not-allowed');
});
</script>
