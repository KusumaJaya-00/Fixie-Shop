<?php

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

define('UPLOAD_MAX_SIZE', 2 * 1024 * 1024); // 2MB
define('UPLOAD_ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png']);
define('UPLOAD_MAX_WIDTH', 800);
define('UPLOAD_PATH', __DIR__ . '/../public/uploads/');

/**
 * Upload array file dari input name="images[]".
 * Return: ['success' => [nama_file, ...], 'errors' => [pesan_error, ...]]
 */
function uploadImages(array $files): array
{
    $result = ['success' => [], 'errors' => []];

    if (empty($files['name'][0])) {
        return $result;
    }

    $manager = new ImageManager(new Driver());

    $total = count($files['name']);
    for ($i = 0; $i < $total; $i++) {
        // Skip file yang gagal diupload — petakan kode error PHP ke pesan ramah
        if ($files['error'][$i] !== UPLOAD_ERR_OK) {
            $errorMsg = match ($files['error'][$i]) {
                UPLOAD_ERR_INI_SIZE   => 'ukuran melebihi batas maksimal PHP',
                UPLOAD_ERR_FORM_SIZE  => 'ukuran melebihi batas maksimal form',
                UPLOAD_ERR_PARTIAL    => 'file hanya terupload sebagian',
                UPLOAD_ERR_NO_FILE    => 'tidak ada file yang dipilih',
                UPLOAD_ERR_NO_TMP_DIR => 'folder temporary tidak ditemukan',
                UPLOAD_ERR_CANT_WRITE => 'gagal menulis file ke disk',
                default               => 'error tidak dikenal',
            };
            $result['errors'][] = htmlspecialchars($files['name'][$i]) . ': ' . $errorMsg;
            continue;
        }

        $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));

        // Validasi ekstensi
        if (!in_array($ext, UPLOAD_ALLOWED_EXTENSIONS)) {
            $result['errors'][] = htmlspecialchars($files['name'][$i]) . ': tipe file tidak diizinkan (hanya JPG/PNG)';
            continue;
        }

        // Validasi ukuran
        if ($files['size'][$i] > UPLOAD_MAX_SIZE) {
            $result['errors'][] = htmlspecialchars($files['name'][$i]) . ': ukuran melebihi 2MB';
            continue;
        }

        // Generate nama unik & resize pakai Intervention Image
        $filename = 'product_' . uniqid() . '.' . $ext;
        $destPath = UPLOAD_PATH . $filename;

        try {
            $image = $manager->decode($files['tmp_name'][$i]);
            $image->scaleDown(width: UPLOAD_MAX_WIDTH)
                  ->save($destPath);

            $result['success'][] = $filename;
        } catch (\Exception $e) {
            $result['errors'][] = htmlspecialchars($files['name'][$i]) . ': gagal memproses gambar';
            error_log('Upload error: ' . $e->getMessage());
        }
    }

    return $result;
}

/**
 * Hapus file dari folder uploads.
 */
function deleteImage(string $filename): bool
{
    $path = UPLOAD_PATH . $filename;
    if (file_exists($path)) {
        return unlink($path);
    }
    return false;
}
