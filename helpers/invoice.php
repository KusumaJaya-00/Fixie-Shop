<?php

use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
 * Kirim email invoice ke buyer menggunakan PHPMailer.
 */
function sendInvoiceEmail(int $orderId, string $pdfPath): void
{
    global $pdo;

    // Load data order lengkap
    $orderModel = new Order($pdo);
    $order = $orderModel->findWithItems($orderId);

    if (!$order) {
        throw new Exception("Order #{$orderId} tidak ditemukan untuk mengirim invoice.");
    }

    // Load konfigurasi mailer
    $configPath = __DIR__ . '/../config/mailer.php';
    if (!file_exists($configPath)) {
        throw new Exception("File konfigurasi config/mailer.php tidak ditemukan.");
    }
    $mailConfig = require $configPath;

    $mail = new PHPMailer(true);

    // Setup server SMTP
    $mail->isSMTP();
    $mail->Host       = $mailConfig['smtp_host'];
    $mail->SMTPAuth   = $mailConfig['smtp_auth'];
    $mail->Username   = $mailConfig['smtp_username'];
    $mail->Password   = $mailConfig['smtp_password'];
    $mail->SMTPSecure = $mailConfig['smtp_secure'] === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = $mailConfig['smtp_port'];

    // Pengirim & Penerima
    $mail->setFrom($mailConfig['smtp_username'], $mailConfig['from_name']);
    $mail->addAddress($order['buyer_email'], $order['buyer_name']);

    // Lampiran PDF
    if (!file_exists($pdfPath)) {
        throw new Exception("File invoice PDF tidak ditemukan di path: {$pdfPath}");
    }
    $mail->addAttachment($pdfPath, "invoice-{$orderId}.pdf");

    // Konten Email
    $mail->isHTML(true);
    $mail->Subject = "Invoice Pesanan #{$orderId} - Fixie Shop";
    
    // Format tanggal & harga
    $orderDate = date('d M Y H:i', strtotime($order['created_at']));
    $shippingCost = (float) ($order['shipping_cost'] ?? 0);
    $formattedShipping = 'Rp' . number_format($shippingCost, 0, ',', '.');
    $formattedTotal = 'Rp' . number_format($order['total_price'], 0, ',', '.');

    // Email Body
    $mail->Body = "
        <div style='font-family: Arial, sans-serif; line-height: 1.6; color: #333333; max-width: 600px; margin: 0 auto; border: 1px solid #e5e7eb; padding: 20px; border-radius: 8px;'>
            <h2 style='color: #2563eb; margin-top: 0;'>Pembayaran Terverifikasi!</h2>
            <p>Halo <strong>" . htmlspecialchars($order['buyer_name']) . "</strong>,</p>
            <p>Terima kasih atas pembayaran Anda. Pembayaran untuk pesanan <strong>#{$orderId}</strong> telah berhasil kami verifikasi.</p>
            
            <table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>
                <tr>
                    <td style='padding: 8px 0; border-bottom: 1px solid #f3f4f6;'><strong>Nomor Order:</strong></td>
                    <td style='padding: 8px 0; border-bottom: 1px solid #f3f4f6; text-align: right;'>#{$orderId}</td>
                </tr>
                <tr>
                    <td style='padding: 8px 0; border-bottom: 1px solid #f3f4f6;'><strong>Tanggal Pesanan:</strong></td>
                    <td style='padding: 8px 0; border-bottom: 1px solid #f3f4f6; text-align: right;'>{$orderDate}</td>
                </tr>
                <tr>
                    <td style='padding: 8px 0; border-bottom: 1px solid #f3f4f6;'><strong>Ongkos Kirim:</strong></td>
                    <td style='padding: 8px 0; border-bottom: 1px solid #f3f4f6; text-align: right;'>{$formattedShipping}</td>
                </tr>
                <tr>
                    <td style='padding: 8px 0; border-bottom: 1px solid #f3f4f6; color: #2563eb;'><strong>Total Pembayaran:</strong></td>
                    <td style='padding: 8px 0; border-bottom: 1px solid #f3f4f6; text-align: right; font-weight: bold; color: #2563eb;'>{$formattedTotal}</td>
                </tr>
            </table>

            <p><strong>Alamat Pengiriman:</strong><br>" . nl2br(htmlspecialchars($order['shipping_address'])) . "</p>

            <p>Kami telah melampirkan file invoice resmi dalam format PDF pada email ini untuk kenyamanan Anda.</p>
            <p>Pesanan Anda saat ini sedang disiapkan untuk pengiriman. Kami akan menginformasikan nomor resi pengiriman setelah pesanan dikirim.</p>
            <hr style='border: 0; border-top: 1px solid #e5e7eb; margin: 20px 0;'>
            <p style='font-size: 12px; color: #9ca3af; text-align: center; margin-bottom: 0;'>
                Fixie Shop &copy; " . date('Y') . ". Seluruh hak cipta dilindungi.
            </p>
        </div>
    ";

    $mail->AltBody = "Halo " . $order['buyer_name'] . ",\n\nTerima kasih atas pembayaran Anda. Pembayaran untuk pesanan #{$orderId} telah berhasil diverifikasi.\n\nDetail Pesanan:\n- Nomor Order: #{$orderId}\n- Tanggal Pesanan: {$orderDate}\n- Ongkos Kirim: {$formattedShipping}\n- Total Pembayaran: {$formattedTotal}\n- Alamat Kirim: " . $order['shipping_address'] . "\n\nInvoice PDF resmi telah dilampirkan pada email ini.\n\nSalam hangat,\nFixie Shop";

    $mail->send();
}

/**
 * Stub placeholder — akan diisi di sub-task 4 (Dompdf) & 5 (PHPMailer).
 * Generate invoice PDF dan kirim ke email buyer.
 */
function generateAndSendInvoice(int $orderId): void
{
    // generate PDF dengan Dompdf
    $pdfPath = generateInvoicePdf($orderId);
    // kirim PDF ke email buyer dengan PHPMailer
    sendInvoiceEmail($orderId, $pdfPath);
}

