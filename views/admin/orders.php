<?php
// Helper: kembalikan class Tailwind badge sesuai status order
function orderStatusBadge(string $status): string
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

function orderStatusLabel(string $status): string
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

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold">Kelola Pesanan</h1>
        <p class="text-gray-500 text-sm mt-1">Daftar semua transaksi masuk.</p>
    </div>
</div>

<?php if (empty($orders)): ?>
    <div class="rounded-xl border border-gray-200 bg-white p-12 text-center shadow-sm">
        <p class="text-gray-500 text-sm">Belum ada pesanan masuk.</p>
    </div>
<?php else: ?>
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">No. Order</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Nama Buyer</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600">Total</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Status</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Tanggal</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($orders as $order): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono text-gray-700">
                            #<?= htmlspecialchars($order['id']) ?>
                        </td>
                        <td class="px-4 py-3 text-gray-900">
                            <?= htmlspecialchars($order['buyer_name']) ?>
                        </td>
                        <td class="px-4 py-3 text-right text-gray-900">
                            Rp<?= number_format($order['total_price'], 0, ',', '.') ?>
                        </td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-0.5 text-xs font-medium <?= orderStatusBadge($order['status']) ?>">
                                <?= orderStatusLabel($order['status']) ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500">
                            <?= htmlspecialchars(date('d M Y', strtotime($order['created_at']))) ?>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="/admin/orders/detail?id=<?= $order['id'] ?>"
                               class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
