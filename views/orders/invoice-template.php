<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice #<?= htmlspecialchars((string) $order['id']) ?></title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333333;
            font-size: 14px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }
        .header {
            margin-bottom: 30px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            vertical-align: top;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            text-transform: uppercase;
        }
        .title {
            text-align: right;
            font-size: 24px;
            font-weight: bold;
            color: #111827;
        }
        .info-table {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }
        .info-table td {
            width: 50%;
            vertical-align: top;
        }
        .info-title {
            font-weight: bold;
            color: #4b5563;
            margin-bottom: 5px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .details-table th {
            background-color: #f3f4f6;
            color: #374151;
            font-weight: bold;
            text-align: left;
            padding: 10px;
            font-size: 12px;
            border-bottom: 2px solid #e5e7eb;
        }
        .details-table td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 13px;
        }
        .text-right {
            text-align: right;
        }
        .total-box {
            margin-top: 20px;
            text-align: right;
        }
        .total-table {
            width: 300px;
            margin-left: auto;
            border-collapse: collapse;
        }
        .total-table td {
            padding: 8px 10px;
            font-size: 13px;
        }
        .total-row td {
            border-top: 2px solid #e5e7eb;
            font-weight: bold;
            font-size: 16px;
            color: #2563eb;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 11px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <!-- Bagian Header Toko & Judul Invoice -->
        <div class="header">
            <table class="header-table">
                <tr>
                    <td>
                        <div class="logo">FIXIE SHOP</div>
                        <div style="color: #6b7280; font-size: 12px; margin-top: 5px;">
                            Penyedia Sepeda Fixie Terbaik & Berkualitas
                        </div>
                    </td>
                    <td class="title">INVOICE</td>
                </tr>
            </table>
        </div>

        <hr style="border: 0; border-top: 1px solid #e5e7eb; margin-bottom: 25px;">

        <!-- Informasi Transaksi & Pelanggan -->
        <table class="info-table">
            <tr>
                <td>
                    <div class="info-title">Penerima</div>
                    <strong><?= htmlspecialchars($order['buyer_name']) ?></strong><br>
                    Email: <?= htmlspecialchars($order['buyer_email']) ?><br>
                    No. HP: <?= htmlspecialchars($order['buyer_phone']) ?>
                </td>
                <td>
                    <div class="info-title">Detail Pesanan</div>
                    Nomor Order: <strong>#<?= htmlspecialchars((string) $order['id']) ?></strong><br>
                    Tanggal: <?= date('d M Y H:i', strtotime($order['created_at'])) ?><br>
                    Status: <span style="color: #16a34a; font-weight: bold;">PAID</span>
                </td>
            </tr>
        </table>

        <!-- Tabel Item Pesanan -->
        <table class="details-table">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>SKU</th>
                    <th class="text-right">Harga Satuan</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order['items'] as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['title']) ?></td>
                        <td><?= htmlspecialchars($item['sku'] ?? '-') ?></td>
                        <td class="text-right">Rp<?= number_format($item['price'], 0, ',', '.') ?></td>
                        <td class="text-right"><?= (int) $item['qty'] ?></td>
                        <td class="text-right">Rp<?= number_format($item['price'] * $item['qty'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Perhitungan Total -->
        <div class="total-box">
            <table class="total-table">
                <tr class="total-row">
                    <td>Total Bayar:</td>
                    <td class="text-right">Rp<?= number_format($order['total_price'], 0, ',', '.') ?></td>
                </tr>
            </table>
        </div>

        <!-- Bagian Footer / Keterangan Tambahan -->
        <div class="footer">
            <p>Terima kasih telah berbelanja di Fixie Shop!</p>
            <p>Ini adalah bukti pembayaran resmi yang sah dan dihasilkan secara otomatis oleh sistem kami.</p>
        </div>
    </div>
</body>
</html>
