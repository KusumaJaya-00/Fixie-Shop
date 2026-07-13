<?php
/**
 * Komponen card produk — reusable.
 * Variabel yang harus tersedia: $product (array dari query Product::all)
 */
// Produk bisa aja belum punya foto sama sekali (belum diupload admin), makanya fallback ke gambar placeholder
$img = $product['primary_image'] ?? null;
$imgSrc = $img ? '/uploads/' . htmlspecialchars($img, ENT_QUOTES, 'UTF-8') : '/assets/img/no-image.png';
$outOfStock = (int) $product['stock'] === 0;
?>
<a href="/product?id=<?= $product['id'] ?>" class="block rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden hover:shadow-md transition group">
    <div class="aspect-square bg-gray-100 relative overflow-hidden">
        <img src="<?= $imgSrc ?>"
             alt="<?= htmlspecialchars($product['title'], ENT_QUOTES, 'UTF-8') ?>"
             class="w-full h-full object-cover group-hover:scale-105 transition"
             loading="lazy">
        <?php if ($outOfStock): ?>
            <span class="absolute top-2 left-2 rounded-full bg-red-600 px-2 py-0.5 text-xs font-medium text-white">Habis</span>
        <?php endif; ?>
    </div>
    <div class="p-4 space-y-1">
        <p class="text-xs text-gray-500"><?= htmlspecialchars($product['brand'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
        <h3 class="text-sm font-semibold leading-snug line-clamp-2"><?= htmlspecialchars($product['title'], ENT_QUOTES, 'UTF-8') ?></h3>
        <p class="text-brand font-bold text-sm">Rp<?= number_format($product['price'], 0, ',', '.') ?></p>
    </div>
</a>
