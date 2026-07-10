<?php
class AdminUserController
{
    public function __construct(private PDO $db)
    {
    }

    public function index(): void
    {
        requireAdmin();
        $userModel = new User($this->db);
        $users = $userModel->all();

        $title = 'Kelola User';
        ob_start();
        require __DIR__ . '/../../views/admin/users/users.php';
        $content = ob_get_clean();
        require __DIR__ . '/../../views/components/admin-layout.php';
    }

    public function create(): void
    {
        requireAdmin();
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['_csrf_token'] ?? '')) {
                $errors['general'] = 'Sesi tidak valid. Silakan reload halaman.';
            } else {
            $name     = trim($_POST['name'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $phone    = trim($_POST['phone'] ?? '');
            $password = $_POST['password'] ?? '';
            $roleId   = (int)($_POST['role_id'] ?? 2);

            if ($name === '') {
                $errors['name'] = 'Nama wajib diisi.';
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Email tidak valid.';
            }
            if ($phone !== '' && !preg_match('/^08[0-9]{8,11}$/', $phone)) {
                $errors['phone'] = 'No. HP harus 10-13 digit angka.';
            }
            if (strlen($password) < 8) {
                $errors['password'] = 'Password minimal 8 karakter.';
            }

            if (empty($errors)) {
                $user = new User($this->db);
                if ($user->findByEmail($email)) {
                    $errors['email'] = 'Email sudah digunakan.';
                } else {
                    $user->create([
                        'name'     => $name,
                        'email'    => $email,
                        'phone'    => $phone,
                        'password' => password_hash($password, PASSWORD_DEFAULT),
                        'role_id'  => $roleId,
                    ]);
                    $_SESSION['flash'] = ['type' => 'success', 'message' => 'User berhasil ditambahkan.'];
                    header('Location: /admin/users');
                    exit;
                }
            }
            }
        }

        $title = 'Tambah User';
        ob_start();
        require __DIR__ . '/../../views/admin/users/create.php';
        $content = ob_get_clean();
        require __DIR__ . '/../../views/components/admin-layout.php';
    }

    public function edit(): void
    {
        requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        $userModel = new User($this->db);
        $user = $userModel->find($id);

        if (!$user) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'User tidak ditemukan.'];
            header('Location: /admin/users');
            exit;
        }

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['_csrf_token'] ?? '')) {
                $errors['general'] = 'Sesi tidak valid. Silakan reload halaman.';
            } else {
            $name   = trim($_POST['name'] ?? '');
            $email  = trim($_POST['email'] ?? '');
            $phone  = trim($_POST['phone'] ?? '');
            $roleId = (int)($_POST['role_id'] ?? 2);

            if ($name === '') {
                $errors['name'] = 'Nama wajib diisi.';
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Email tidak valid.';
            }
            if ($phone !== '' && !preg_match('/^08[0-9]{8,11}$/', $phone)) {
                $errors['phone'] = 'No. HP harus 10-13 digit angka.';
            }

            if (empty($errors)) {
                $existing = $userModel->findByEmail($email);
                if ($existing && (int)$existing['id'] !== $id) {
                    $errors['email'] = 'Email sudah digunakan user lain.';
                } else {
                    $userModel->update($id, [
                        'name'    => $name,
                        'email'   => $email,
                        'phone'   => $phone,
                        'role_id' => $roleId,
                    ]);
                    $_SESSION['flash'] = ['type' => 'success', 'message' => 'User berhasil diperbarui.'];
                    header('Location: /admin/users');
                    exit;
                }
            }
        }
        }

        $title = 'Edit User';
        ob_start();
        require __DIR__ . '/../../views/admin/users/edit.php';
        $content = ob_get_clean();
        require __DIR__ . '/../../views/components/admin-layout.php';
    }

    public function delete(): void
    {
        requireAdmin();
        $id = (int)($_GET['id'] ?? 0);

        if ($id === (int)($_SESSION['user_id'] ?? 0)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Tidak bisa menghapus akun sendiri.'];
        } elseif ($id > 0) {
            $userModel = new User($this->db);
            $userModel->delete($id);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'User berhasil dihapus.'];
        }

        header('Location: /admin/users');
        exit;
    }
}
