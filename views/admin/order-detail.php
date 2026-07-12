<?php
// Helper lokal — badge & label status (sama dengan orders.php)
function detailStatusBadge(string $status): string
{
    return match ($status) {
        'pending'   => 'bg-amber-100 text-amber-700',
        'paid'      => 'bg-green-100 text-green-700',
        'shipped'   => 'bg-blue-100 text-blue-700',
        'done'      => 'bg-gray-100 text-gray-600',
        'cancelled' => 'bg-red-100 text-red-700',
        default     => 'bg-gray-100 text-gray-600',
    };
}

function detailStatusLabel(string $status): string
{
    return match ($status) {
        'pending'   => 'Pending',
        'paid'      => 'Paid',
        'shipped'   => 'Dikirim',
        'done'      => 'Selesai',
        'cancelled' => 'Dibatalkan',
        default     => $status,
    };
}
?>

<?php
// Hitung subtotal tiap item untuk ditampilkan
$subtotals = [];
$totalItems = 0;
foreach ($order['items'] as $item) {
    $subtotals[$item['id']] = $item['qty'] * $item['price'];
    $totalItems += $subtotals[$item['id']];
}
?>

<div class="mb-6 flex items-center gap-4">
    <a href="/admin/orders"
       class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50">
        &larr; Kembali
    </a>
    <div>
        <h1 class="text-2xl font-bold">Pesanan #<?= htmlspecialchars($order['id']) ?></h1>
        <p class="text-gray-500 text-sm mt-0.5">
            <?= htmlspecialchars(date('d M Y, H:i', strtotime($order['created_at']))) ?>
        </p>
    </div>
    <span class="ml-2 rounded-full px-3 py-1 text-sm font-medium <?= detailStatusBadge($order['status']) ?>">
        <?= detailStatusLabel($order['status']) ?>
    </span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Kiri: Item Pesanan -->
    <div class="lg:col-span-2 space-y-6">

        <!-- Tabel Item -->
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100">
                <h2 class="font-semibold text-gray-900">Item Pesanan</h2>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-4 py-2 font-semibold text-gray-600">Produk</th>
                        <th class="text-right px-4 py-2 font-semibold text-gray-600">Harga</th>
                        <th class="text-right px-4 py-2 font-semibold text-gray-600">Qty</th>
                        <th class="text-right px-4 py-2 font-semibold text-gray-600">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($order['items'] as $item): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <?php if (!empty($item['primary_image'])): ?>
                                        <img src="/<?= htmlspecialchars($item['primary_image']) ?>"
                                             alt="<?= htmlspecialchars($item['title']) ?>"
                                             class="w-10 h-10 rounded-lg object-cover shrink-0">
                                    <?php else: ?>
                                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 text-xs shrink-0">
                                            N/A
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <p class="font-medium text-gray-900"><?= htmlspecialchars($item['title']) ?></p>
                                        <p class="text-xs text-gray-400"><?= htmlspecialchars($item['sku']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right text-gray-700">
                                Rp<?= number_format($item['price'], 0, ',', '.') ?>
                            </td>
                            <td class="px-4 py-3 text-right text-gray-700">
                                <?= (int) $item['qty'] ?>
                            </td>
                            <td class="px-4 py-3 text-right font-medium text-gray-900">
                                Rp<?= number_format($subtotals[$item['id']], 0, ',', '.') ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="border-t border-gray-200 bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-right text-gray-700">Subtotal</td>
                        <td class="px-4 py-3 text-right font-medium text-gray-900">
                            Rp<?= number_format($totalItems, 0, ',', '.') ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-right text-gray-700">Ongkos Kirim</td>
                        <td class="px-4 py-3 text-right font-medium text-gray-900">
                            Rp<?= number_format($order['shipping_cost'], 0, ',', '.') ?>
                        </td>
                    </tr>
                    <tr class="border-t border-gray-300">
                        <td colspan="3" class="px-4 py-3 text-right font-semibold text-gray-700">Total</td>
                        <td class="px-4 py-3 text-right font-bold text-gray-900">
                            Rp<?= number_format($order['total_price'], 0, ',', '.') ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Info Buyer -->
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm p-4">
            <h2 class="font-semibold text-gray-900 mb-3">Info Pembeli</h2>
            <dl class="text-sm space-y-1">
                <div class="flex gap-2">
                    <dt class="w-20 text-gray-500 shrink-0">Nama</dt>
                    <dd class="text-gray-900"><?= htmlspecialchars($order['buyer_name']) ?></dd>
                </div>
                <div class="flex gap-2">
                    <dt class="w-20 text-gray-500 shrink-0">Email</dt>
                    <dd class="text-gray-900"><?= htmlspecialchars($order['buyer_email']) ?></dd>
                </div>
                <div class="flex gap-2">
                    <dt class="w-20 text-gray-500 shrink-0">No. HP</dt>
                    <dd class="text-gray-900"><?= htmlspecialchars($order['buyer_phone']) ?></dd>
                </div>
            </dl>
        </div>

        <!-- Alamat Pengiriman -->
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm p-4">
            <h2 class="font-semibold text-gray-900 mb-3">Alamat Pengiriman</h2>
            <p class="text-sm text-gray-700 whitespace-pre-line"><?= htmlspecialchars($order['shipping_address']) ?></p>
        </div>

    </div>

    <!-- Kanan: Pembayaran & Aksi -->
    <div class="space-y-6">

        <!-- Data Pembayaran -->
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm p-4">
            <h2 class="font-semibold text-gray-900 mb-3">Bukti Pembayaran</h2>

            <?php if ($payment): ?>
                <dl class="text-sm space-y-1 mb-4">
                    <div class="flex gap-2">
                        <dt class="w-28 text-gray-500 shrink-0">Jumlah Transfer</dt>
                        <dd class="font-medium text-gray-900">
                            Rp<?= number_format($payment['amount'], 0, ',', '.') ?>
                        </dd>
                    </div>
                    <div class="flex gap-2">
                        <dt class="w-28 text-gray-500 shrink-0">Status</dt>
                        <dd>
                            <?php if ($payment['status'] === 'verified'): ?>
                                <span class="rounded-full px-2 py-0.5 text-xs font-medium bg-green-100 text-green-700">Terverifikasi</span>
                            <?php else: ?>
                                <span class="rounded-full px-2 py-0.5 text-xs font-medium bg-amber-100 text-amber-700">Belum Diverifikasi</span>
                            <?php endif; ?>
                        </dd>
                    </div>
                    <?php if ($payment['paid_at'] && $payment['status'] === 'verified'): ?>
                        <div class="flex gap-2">
                            <dt class="w-28 text-gray-500 shrink-0">Tgl Verifikasi</dt>
                            <dd class="text-gray-900">
                                <?= htmlspecialchars(date('d M Y, H:i', strtotime($payment['paid_at']))) ?>
                            </dd>
                        </div>
                    <?php endif; ?>
                </dl>

                <!-- Gambar bukti transfer -->
                <?php if (!empty($payment['proof_image'])): ?>
                    <a href="/uploads/payments/<?= htmlspecialchars($payment['proof_image']) ?>"
                       target="_blank"
                       class="block rounded-lg overflow-hidden border border-gray-200">
                        <img src="/uploads/payments/<?= htmlspecialchars($payment['proof_image']) ?>"
                             alt="Bukti Transfer"
                             class="w-full object-cover max-h-64">
                    </a>
                    <p class="text-xs text-gray-400 mt-1 text-center">Klik untuk buka penuh</p>
                <?php endif; ?>

            <?php else: ?>
                <p class="text-sm text-gray-400">Buyer belum mengupload bukti transfer.</p>
            <?php endif; ?>
        </div>

        <!-- Aksi -->
        <?php
        $status  = $order['status'];
        $hasUnverifiedPayment = $payment && $payment['status'] === 'unverified';
        ?>

        <?php if ($status === 'pending' && $hasUnverifiedPayment): ?>
            <!-- Verifikasi pembayaran -->
            <form method="POST" action="/admin/orders/verify"
                  onsubmit="return confirm('Verifikasi pembayaran ini? Stok produk akan dikurangi.')">
                <input type="hidden" name="order_id" value="<?= (int) $order['id'] ?>">
                <button type="submit"
                        class="w-full inline-flex items-center justify-center rounded-lg bg-brand px-4 py-2 text-white font-medium hover:bg-brand-dark">
                    Verifikasi Pembayaran
                </button>
            </form>

        <?php elseif ($status === 'pending' && !$payment): ?>
            <!-- Batalkan order (belum ada bukti) -->
            <form method="POST" action="/admin/orders/status"
                  onsubmit="return confirm('Batalkan order ini?')">
                <input type="hidden" name="order_id" value="<?= (int) $order['id'] ?>">
                <input type="hidden" name="new_status" value="cancelled">
                <button type="submit"
                        class="w-full inline-flex items-center justify-center rounded-lg border border-red-300 bg-white px-4 py-2 text-red-600 font-medium hover:bg-red-50">
                    Batalkan Order
                </button>
            </form>

        <?php elseif ($status === 'paid'): ?>
            <!-- Tandai dikirim -->
            <form method="POST" action="/admin/orders/status"
                  onsubmit="return confirm('Tandai order ini sudah dikirim?')">
                <input type="hidden" name="order_id" value="<?= (int) $order['id'] ?>">
                <input type="hidden" name="new_status" value="shipped">
                <button type="submit"
                        class="w-full inline-flex items-center justify-center rounded-lg bg-brand px-4 py-2 text-white font-medium hover:bg-brand-dark">
                    Tandai Dikirim
                </button>
            </form>

        <?php elseif ($status === 'shipped'): ?>
            <!-- Tandai selesai -->
            <form method="POST" action="/admin/orders/status"
                  onsubmit="return confirm('Tandai order ini selesai?')">
                <input type="hidden" name="order_id" value="<?= (int) $order['id'] ?>">
                <input type="hidden" name="new_status" value="done">
                <button type="submit"
                        class="w-full inline-flex items-center justify-center rounded-lg bg-brand px-4 py-2 text-white font-medium hover:bg-brand-dark">
                    Tandai Selesai
                </button>
            </form>

        <?php elseif ($status === 'done'): ?>
            <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-center text-sm text-gray-500">
                Order ini sudah selesai.
            </div>

        <?php elseif ($status === 'cancelled'): ?>
            <div class="rounded-xl border border-red-100 bg-red-50 p-4 text-center text-sm text-red-600">
                Order ini telah dibatalkan.
            </div>
        <?php endif; ?>

        <!-- Link invoice (jika sudah paid/shipped/done) -->
        <?php if (in_array($status, ['paid', 'shipped', 'done'])): ?>
            <?php $invoicePath = __DIR__ . '/../../public/invoices/inv-' . $order['id'] . '.pdf'; ?>
            <?php if (file_exists($invoicePath)): ?>
                <a href="/invoices/inv-<?= (int) $order['id'] ?>.pdf"
                   target="_blank"
                   class="w-full inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    Download Invoice PDF
                </a>
            <?php endif; ?>
        <?php endif; ?>

    </div>
</div>
