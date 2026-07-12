<?php

use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Generate invoice dalam format PDF menggunakan Dompdf.
 * Mengembalikan path absolut file PDF yang berhasil disimpan.
 */
function generateInvoicePdf(int $orderId): string
{
    global $pdo;

    $orderModel = new Order($pdo);
    $order = $orderModel->findWithItems($orderId);

    if (!$order) {
        throw new Exception("Order #{$orderId} tidak ditemukan untuk membuat invoice.");
    }

    // Render template HTML ke string
    ob_start();
    require __DIR__ . '/../views/orders/invoice-template.php';
    $html = ob_get_clean();

    // Buat folder public/invoices jika belum ada
    $dirPath = __DIR__ . '/../public/invoices';
    if (!is_dir($dirPath)) {
        mkdir($dirPath, 0755, true);
    }

    // Inisialisasi Dompdf dengan opsi standar
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $filePath = $dirPath . "/inv-{$orderId}.pdf";

    // Simpan file PDF ke direktori publik
    if (file_put_contents($filePath, $dompdf->output()) === false) {
        throw new Exception("Gagal menulis file PDF ke {$filePath}");
    }

    return $filePath;
}

/**
 * Stub placeholder — akan diisi di sub-task 4 (Dompdf) & 5 (PHPMailer).
 * Generate invoice PDF dan kirim ke email buyer.
 */
function generateAndSendInvoice(int $orderId): void
{
    // generate PDF dengan Dompdf
    generateInvoicePdf($orderId);
    // TODO ST-5: kirim PDF ke email buyer dengan PHPMailer
}

