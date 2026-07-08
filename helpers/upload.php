<?php

use Intervention\Image\ImageManager;

define('UPLOAD_MAX_SIZE', 2 * 1024 * 1024); // 2MB
define('UPLOAD_ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png']);
define('UPLOAD_MAX_WIDTH', 800);
define('UPLOAD_PATH', __DIR__ . '/../public/uploads/');

/**
 * Upload array file dari input name="images[]".
 * Return array: ['success' => [nama_file, ...], 'errors' => [pesan_error, ...]]
 */
function uploadImages(array $files): array
{
    $result = ['success' => [], 'errors' => []];

    if (empty($files['name'][0])) {
        return $result;
    }

    $manager = new ImageManager('gd');

    $total = count($files['name']);
    for ($i = 0; $i < $total; $i++) {
        if ($files['error'][$i] !== UPLOAD_ERR_OK) {
            $result['errors'][] = 'File gagal diupload (error #' . $files['error'][$i] . ')';
            continue;
        }

        $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));

        if (!in_array($ext, UPLOAD_ALLOWED_EXTENSIONS)) {
            $result['errors'][] = htmlspecialchars($files['name'][$i]) . ': tipe file tidak diizinkan (hanya JPG/PNG)';
            continue;
        }

        if ($files['size'][$i] > UPLOAD_MAX_SIZE) {
            $result['errors'][] = htmlspecialchars($files['name'][$i]) . ': ukuran melebihi 2MB';
            continue;
        }

        $filename = uniqid('img_', true) . '.' . $ext;
        $destPath = UPLOAD_PATH . $filename;

        try {
            $manager->decode($files['tmp_name'][$i])
                    ->scaleDown(width: UPLOAD_MAX_WIDTH)
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
 * Hapus file dari uploads.
 */
function deleteImage(string $filename): bool
{
    $path = UPLOAD_PATH . $filename;
    if (file_exists($path)) {
        return unlink($path);
    }
    return false;
}
