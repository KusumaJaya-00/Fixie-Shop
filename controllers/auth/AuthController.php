<?php
class AuthController
{
    public function __construct(private PDO $db)
    {
    }

    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['_csrf_token'] ?? '')) {
                $errors['general'] = 'Sesi tidak valid. Silakan reload halaman.';
            } else {
                $name     = trim($_POST['name'] ?? '');
                $email    = trim($_POST['email'] ?? '');
                $phone    = trim($_POST['phone'] ?? '');
                $password = $_POST['password'] ?? '';
                $confirm  = $_POST['password_confirm'] ?? '';

                $errors = [];

                if ($name === '') {
                    $errors['name'] = 'Nama lengkap wajib diisi.';
                }
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors['email'] = 'Format email tidak valid.';
                }
                if ($phone === '' || !preg_match('/^08[0-9]{8,11}$/', $phone)) {
                    $errors['phone'] = 'No. HP wajib diisi (10-13 digit angka).';
                }
                if (strlen($password) < 8) {
                    $errors['password'] = 'Password minimal 8 karakter.';
                }
                if ($password !== $confirm) {
                    $errors['password_confirm'] = 'Konfirmasi password tidak cocok.';
                }

                if (empty($errors)) {
                    try {
                        $user = new User($this->db);
                        if ($user->findByEmail($email)) {
                            $errors['email'] = 'Email sudah terdaftar.';
                        } else {
                            $hashed = password_hash($password, PASSWORD_DEFAULT);
                            $user->create([
                                'name'     => $name,
                                'email'    => $email,
                                'password' => $hashed,
                                'phone'    => $phone,
                                'role_id'  => 2,
                            ]);
                            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Registrasi berhasil. Silakan login.'];
                            header('Location: /login');
                            exit;
                        }
                    } catch (PDOException $e) {
                        error_log('Register error: ' . $e->getMessage());
                        $errors['general'] = 'Terjadi kesalahan sistem. Silakan coba lagi.';
                    }
                }
            }
        }

        $title = 'Register';
        ob_start();
        require __DIR__ . '/../../views/auth/register.php';
        $content = ob_get_clean();
        require __DIR__ . '/../../views/components/public-layout.php';
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['_csrf_token'] ?? '')) {
                $errors['general'] = 'Sesi tidak valid. Silakan reload halaman.';
            } else {
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
                if (!checkRateLimit('login_' . $email, 5, 300)) {
                    $errors['general'] = 'Terlalu banyak percobaan login. Silakan coba lagi nanti.';
                } else {
                try {
                    $user = new User($this->db);
                    $data = $user->findByEmail($email);

                    if ($data && password_verify($password, $data['password'])) {
                        $_SESSION['user_id']    = (int)$data['id'];
                        $_SESSION['role_name']  = $data['role_name'];
                        $_SESSION['user_name']  = $data['name'];
                        $_SESSION['user_email'] = $data['email'];

                        $redirect = $_POST['redirect'] ?? $_GET['redirect'] ?? '/';
                        if (!is_string($redirect) || $redirect === '' || $redirect[0] !== '/' || str_starts_with($redirect, '//')) {
                            $redirect = '/';
                        }
                        header('Location: ' . $redirect);
                        exit;
                    }

                    $errors['email'] = 'Email atau password salah.';
                } catch (PDOException $e) {
                    error_log('Login error: ' . $e->getMessage());
                    $errors['general'] = 'Terjadi kesalahan sistem. Silakan coba lagi.';
                }
                }
            }
        }
        }

        $title = 'Login';
        ob_start();
        require __DIR__ . '/../../views/auth/login.php';
        $content = ob_get_clean();
        require __DIR__ . '/../../views/components/public-layout.php';
    }

    public function adminLogin(): void
    {
        redirectIfAuthenticated();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['_csrf_token'] ?? '')) {
                $errors['general'] = 'Sesi tidak valid. Silakan reload halaman.';
            } else {
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
                if (!checkRateLimit('admin_login_' . $email, 3, 300)) {
                    $errors['general'] = 'Terlalu banyak percobaan login. Silakan coba lagi nanti.';
                } else {
                try {
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
                } catch (PDOException $e) {
                    error_log('Admin login error: ' . $e->getMessage());
                    $errors['general'] = 'Terjadi kesalahan sistem. Silakan coba lagi.';
                }
                }
            }
        }
        }

        $title = 'Admin Login';
        ob_start();
        require __DIR__ . '/../../views/auth/admin-login.php';
        $content = ob_get_clean();
        require __DIR__ . '/../../views/components/public-layout.php';
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: /login');
        exit;
    }
}
