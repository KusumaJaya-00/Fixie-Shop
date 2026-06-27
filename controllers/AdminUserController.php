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
        require __DIR__ . '/../views/admin/users/index.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/components/admin-layout.php';
    }

    public function create(): void
    {
        requireAdmin();
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name     = trim($_POST['name'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $roleId   = (int)($_POST['role_id'] ?? 2);

            if ($name === '') {
                $errors['name'] = 'Nama wajib diisi.';
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Email tidak valid.';
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
                        'password' => password_hash($password, PASSWORD_DEFAULT),
                        'role_id'  => $roleId,
                    ]);
                    $_SESSION['flash'] = ['type' => 'success', 'message' => 'User berhasil ditambahkan.'];
                    header('Location: /admin/users');
                    exit;
                }
            }
        }

        $title = 'Tambah User';
        ob_start();
        require __DIR__ . '/../views/admin/users/create.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/components/admin-layout.php';
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
            $name   = trim($_POST['name'] ?? '');
            $email  = trim($_POST['email'] ?? '');
            $roleId = (int)($_POST['role_id'] ?? 2);

            if ($name === '') {
                $errors['name'] = 'Nama wajib diisi.';
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Email tidak valid.';
            }

            if (empty($errors)) {
                $existing = $userModel->findByEmail($email);
                if ($existing && (int)$existing['id'] !== $id) {
                    $errors['email'] = 'Email sudah digunakan user lain.';
                } else {
                    $data = [
                        'name'    => $name,
                        'email'   => $email,
                        'role_id' => $roleId,
                    ];

                    $password = $_POST['password'] ?? '';
                    if ($password !== '') {
                        $data['password'] = password_hash($password, PASSWORD_DEFAULT);
                    }

                    $userModel->update($id, $data);
                    $_SESSION['flash'] = ['type' => 'success', 'message' => 'User berhasil diperbarui.'];
                    header('Location: /admin/users');
                    exit;
                }
            }
        }

        $title = 'Edit User';
        ob_start();
        require __DIR__ . '/../views/admin/users/edit.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/components/admin-layout.php';
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
