<?php
class AuthController
{
    public function __construct(private PDO $db)
    {
    }

    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name     = trim($_POST['name'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm  = $_POST['password_confirm'] ?? '';

            $errors = [];

            if ($name === '') {
                $errors['name'] = 'Nama lengkap wajib diisi.';
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Format email tidak valid.';
            }
            if (strlen($password) < 8) {
                $errors['password'] = 'Password minimal 8 karakter.';
            }
            if ($password !== $confirm) {
                $errors['password_confirm'] = 'Konfirmasi password tidak cocok.';
            }

            if (empty($errors)) {
                $user = new User($this->db);
                if ($user->findByEmail($email)) {
                    $errors['email'] = 'Email sudah terdaftar.';
                } else {
                    $hashed = password_hash($password, PASSWORD_DEFAULT);
                    $user->create([
                        'name'     => $name,
                        'email'    => $email,
                        'password' => $hashed,
                        'role_id'  => 2,
                    ]);
                    $_SESSION['flash'] = ['type' => 'success', 'message' => 'Registrasi berhasil. Silakan login.'];
                    header('Location: /login');
                    exit;
                }
            }
        }

        $title = 'Register';
        ob_start();
        require __DIR__ . '/../views/auth/register.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/components/public-layout.php';
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $errors = [];

            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Email tidak valid.';
            }
            if ($password === '') {
                $errors['password'] = 'Password wajib diisi.';
            }

            if (empty($errors)) {
                $user = new User($this->db);
                $data = $user->findByEmail($email);

                if ($data && password_verify($password, $data['password'])) {
                    $_SESSION['user_id']    = (int)$data['id'];
                    $_SESSION['role_name']  = $data['role_name'];
                    $_SESSION['user_name']  = $data['name'];
                    $_SESSION['user_email'] = $data['email'];

                    header('Location: /');
                    exit;
                }

                $errors['email'] = 'Email atau password salah.';
            }
        }

        $title = 'Login';
        ob_start();
        require __DIR__ . '/../views/auth/login.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/components/public-layout.php';
    }

    public function adminLogin(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $errors = [];

            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Email tidak valid.';
            }
            if ($password === '') {
                $errors['password'] = 'Password wajib diisi.';
            }

            if (empty($errors)) {
                $user = new User($this->db);
                $data = $user->findByEmail($email);

                if ($data && $data['role_name'] === 'admin' && password_verify($password, $data['password'])) {
                    $_SESSION['user_id']    = (int)$data['id'];
                    $_SESSION['role_name']  = $data['role_name'];
                    $_SESSION['user_name']  = $data['name'];
                    $_SESSION['user_email'] = $data['email'];

                    header('Location: /admin');
                    exit;
                }

                $errors['email'] = 'Email atau password salah.';
            }
        }

        $title = 'Admin Login';
        ob_start();
        require __DIR__ . '/../views/auth/admin-login.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/components/admin-layout.php';
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: /login');
        exit;
    }
}
