<h1 class="text-2xl font-bold">Dashboard</h1>
<p class="text-gray-500 mt-1 mb-6">Selamat datang di dashboard Fixie Shop.</p>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <p class="text-sm text-gray-500">Total Produk</p>
        <p class="text-2xl font-bold mt-1"><?= $totalProducts ?></p>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <p class="text-sm text-gray-500">Total Pesanan</p>
        <p class="text-2xl font-bold mt-1"><?= $totalOrders ?></p>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <p class="text-sm text-gray-500">Omzet</p>
        <p class="text-2xl font-bold mt-1">Rp <?= number_format($totalRevenue, 0, ',', '.') ?></p>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <p class="text-sm text-gray-500">Total User</p>
        <p class="text-2xl font-bold mt-1"><?= $totalUsers ?></p>
    </div>
</div>

<div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold">Pesanan Baru (Pending)</h2>
        <a href="/admin/orders" class="text-sm text-brand hover:underline">Lihat Semua</a>
    </div>

    <?php if (empty($pendingOrders)): ?>
        <p class="text-gray-400 text-sm py-4 text-center">Belum ada pesanan pending.</p>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-gray-500 border-b">
                    <tr>
                        <th class="pb-2 font-medium">Order #</th>
                        <th class="pb-2 font-medium">Pembeli</th>
                        <th class="pb-2 font-medium">Total</th>
                        <th class="pb-2 font-medium">Tanggal</th>
                        <th class="pb-2 font-medium"></th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <?php foreach ($pendingOrders as $order): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 font-mono text-xs">#<?= $order['id'] ?></td>
                            <td class="py-3"><?= htmlspecialchars($order['buyer_name']) ?></td>
                            <td class="py-3">Rp <?= number_format($order['total_price'], 0, ',', '.') ?></td>
                            <td class="py-3 text-gray-500"><?= date('d M Y H:i', strtotime($order['created_at'])) ?></td>
                            <td class="py-3">
                                <a href="/admin/orders/detail?id=<?= $order['id'] ?>"
                                   class="text-brand hover:underline text-xs">Detail</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
