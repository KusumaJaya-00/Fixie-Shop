<?php

class ProfileController
{
    public function __construct(private PDO $db) {}

    public function index(): void
    {
        if (!checkLogin()) {
            header('Location: /login');
            exit;
        }

        $userId = (int)$_SESSION['user_id'];
        $user = new User($this->db);
        $data = $user->find($userId);

        if (!$data) {
            session_destroy();
            header('Location: /login');
            exit;
        }

        $errors = [];
        $profileSuccess = false;
        $passwordSuccess = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['_csrf_token'] ?? '')) {
                $errors['general'] = 'Sesi tidak valid. Silakan reload halaman.';
            } else {
            $action = $_GET['action'] ?? '';

            if ($action === 'update_profile') {
                $name  = trim($_POST['name'] ?? '');
                $phone = trim($_POST['phone'] ?? '');

                if ($name === '') {
                    $errors['name'] = 'Nama lengkap wajib diisi.';
                }
                if ($phone === '' || !preg_match('/^08[0-9]{8,11}$/', $phone)) {
                    $errors['phone'] = 'Nomor HP wajib diisi 10-13 digit angka.';
                }

                if (empty($errors)) {
                    $user->updateProfile($userId, $name, $phone);
                    $_SESSION['user_name'] = $name;
                    $_SESSION['flash'] = ['type' => 'success', 'message' => 'Profil berhasil diperbarui.'];
                    header('Location: /profile');
                    exit;
                }
            } elseif ($action === 'change_password') {
                $oldPassword    = $_POST['old_password'] ?? '';
                $newPassword    = $_POST['new_password'] ?? '';
                $confirmPassword = $_POST['new_password_confirm'] ?? '';

                if (!password_verify($oldPassword, $data['password'])) {
                    $errors['old_password'] = 'Password lama tidak sesuai.';
                }
                if (strlen($newPassword) < 8) {
                    $errors['new_password'] = 'Password baru minimal 8 karakter.';
                }
                if ($newPassword !== $confirmPassword) {
                    $errors['new_password_confirm'] = 'Konfirmasi password tidak cocok.';
                }

                if (empty($errors)) {
                    $user->updatePassword($userId, $newPassword);
                    $_SESSION['flash'] = ['type' => 'success', 'message' => 'Password berhasil diubah.'];
                    header('Location: /profile');
                    exit;
                }
            }
        }
        }

        $title = 'Akun Saya';
        ob_start();
        require __DIR__ . '/../../views/auth/profile.php';
        $content = ob_get_clean();
        require __DIR__ . '/../../views/components/public-layout.php';
    }
}
