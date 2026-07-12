<?php
// Label & warna badge sesuai status order
function statusBadge(string $status): string
{
    return match ($status) {
        'pending'  => '<span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-700">Pending</span>',
        'paid'     => '<span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Paid</span>',
        'shipped'  => '<span class="rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-700">Dikirim</span>',
        'done'     => '<span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Selesai</span>',
        'cancelled'=> '<span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Batal</span>',
        default    => '<span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">' . htmlspecialchars($status) . '</span>',
    };
}

// Format harga: Rp1.500.000
function formatRupiah(float|int $amount): string
{
    return 'Rp' . number_format($amount, 0, ',', '.');
}

// Format tanggal: 07 Jul 2025
function formatTanggal(string $datetime): string
{
    $bulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
    $ts    = strtotime($datetime);
    return sprintf('%02d %s %s', date('j', $ts), $bulan[(int) date('n', $ts) - 1], date('Y', $ts));
}

// Step tracker — kembalikan array langkah [label, aktif]
function trackingSteps(string $status): array
{
    $cancelled = $status === 'cancelled';
    $steps = [
        ['label' => 'Pending',  'done' => true],
        ['label' => 'Paid',     'done' => in_array($status, ['paid', 'shipped', 'done'])],
        ['label' => 'Dikirim',  'done' => in_array($status, ['shipped', 'done'])],
        ['label' => 'Selesai',  'done' => $status === 'done'],
    ];
    return ['steps' => $steps, 'cancelled' => $cancelled];
}
?>

<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-900">Pesanan Saya</h1>

    <?php if (empty($orders)): ?>
        <!-- Empty state -->
        <div class="flex flex-col items-center justify-center rounded-xl border border-gray-200 bg-white py-16 shadow-sm">
            <svg class="mb-4 h-16 w-16 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 12h3.75M9 15h3.75M9 18h3.75M6 21h12a2.25 2.25 0 0 0 2.25-2.25V7.5a2.25 2.25 0 0 0-2.25-2.25H6A2.25 2.25 0 0 0 3.75 7.5V18.75A2.25 2.25 0 0 0 6 21ZM3.75 7.5V6a2.25 2.25 0 0 1 2.25-2.25h1.5" />
            </svg>
            <p class="text-lg font-semibold text-gray-500">Belum ada pesanan</p>
            <p class="mt-1 text-sm text-gray-400">Yuk mulai belanja sepeda fixie impianmu!</p>
            <a href="/" class="mt-6 inline-flex items-center justify-center rounded-lg bg-brand px-5 py-2 text-sm font-medium text-white hover:bg-brand-dark">
                Belanja Sekarang
            </a>
        </div>
    <?php else: ?>
        <?php foreach ($orders as $order):
            $tracking  = trackingSteps($order['status']);
            $showInvoice = in_array($order['status'], ['paid', 'shipped', 'done']);
        ?>
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <!-- Header order -->
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-gray-100 px-4 py-3">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-semibold text-gray-700">
                        #<?= htmlspecialchars((string) $order['id']) ?>
                    </span>
                    <?= statusBadge($order['status']) ?>
                </div>
                <span class="text-xs text-gray-400">
                    <?= htmlspecialchars(formatTanggal($order['created_at'])) ?>
                </span>
            </div>

            <!-- Daftar item singkat -->
            <div class="px-4 py-3">
                <?php if (!empty($order['items'])): ?>
                    <ul class="space-y-1 text-sm text-gray-700">
                        <?php 
                        $subtotalItems = 0;
                        foreach ($order['items'] as $item): 
                            $itemTotal = (float) $item['price'] * (int) $item['qty'];
                            $subtotalItems += $itemTotal;
                        ?>
                            <li class="flex items-center justify-between">
                                <span>
                                    <?= htmlspecialchars($item['title']) ?>
                                    <span class="text-gray-400">× <?= (int) $item['qty'] ?></span>
                                </span>
                                <span class="text-gray-500">
                                    <?= htmlspecialchars(formatRupiah($itemTotal)) ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-sm text-gray-400">Tidak ada item.</p>
                    <?php $subtotalItems = 0; ?>
                <?php endif; ?>

                <!-- Alamat Pengiriman -->
                <?php if (!empty($order['shipping_address'])): ?>
                    <div class="mt-3 rounded-lg bg-gray-50 px-3 py-2">
                        <p class="text-xs font-medium text-gray-600 mb-1">Dikirim ke:</p>
                        <p class="text-xs text-gray-700 line-clamp-2"><?= htmlspecialchars($order['shipping_address']) ?></p>
                    </div>
                <?php endif; ?>

                <!-- Rincian Harga -->
                <div class="mt-3 space-y-1 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal Produk</span>
                        <span><?= htmlspecialchars(formatRupiah($subtotalItems)) ?></span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Ongkos Kirim</span>
                        <span><?= htmlspecialchars(formatRupiah((float) $order['shipping_cost'])) ?></span>
                    </div>
                    <div class="flex justify-between border-t border-gray-200 pt-2 font-bold text-gray-900">
                        <span>Total</span>
                        <span class="text-brand"><?= htmlspecialchars(formatRupiah((float) $order['total_price'])) ?></span>
                    </div>
                </div>
            </div>

            <!-- Tracking steps -->
            <div class="border-t border-gray-100 px-4 py-3">
                <?php if ($tracking['cancelled']): ?>
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                        <span class="text-xs font-medium text-red-600">Pesanan dibatalkan</span>
                    </div>
                <?php else: ?>
                    <ol class="flex items-center gap-0">
                        <?php foreach ($tracking['steps'] as $i => $step): ?>
                            <li class="flex flex-1 items-center <?= $i < count($tracking['steps']) - 1 ? 'after:flex-1 after:border-t-2 after:border-dashed after:content-[\'\'] ' . ($step['done'] ? 'after:border-green-400' : 'after:border-gray-200') : '' ?>">
                                <div class="flex flex-col items-center">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-full text-xs font-bold
                                        <?= $step['done'] ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-400' ?>">
                                        <?php if ($step['done']): ?>
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                            </svg>
                                        <?php else: ?>
                                            <?= $i + 1 ?>
                                        <?php endif; ?>
                                    </span>
                                    <span class="mt-1 text-[10px] <?= $step['done'] ? 'font-medium text-green-700' : 'text-gray-400' ?>">
                                        <?= htmlspecialchars($step['label']) ?>
                                    </span>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                <?php endif; ?>
            </div>

            <!-- Aksi -->
            <?php if ($showInvoice): ?>
            <div class="flex justify-end border-t border-gray-100 px-4 py-3">
                <a href="/invoice/download?id=<?= (int) $order['id'] ?>"
                   class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">
                    <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Download Invoice
                </a>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
