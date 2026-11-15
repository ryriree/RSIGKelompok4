<?php
require_once __DIR__ . '/../models/UserModel.php';

class Auth
{
    const BATAS_GAGAL = 3;
    private $username;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    // GET ?page=login
    public function get_form_login()
    {
        if (!empty($_SESSION['userId'])) redirect('internal-dashboard');

        $pesan = $_SESSION['flash_login'] ?? null;
        unset($_SESSION['flash_login']);
        require BASE_PATH . '/views/auth/login_staff.php';   // pakai variabel $pesan
    }

    // POST ?page=proses-login
    public function proses_login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('login');

        $ok = $this->login($_POST['username'] ?? '', $_POST['password'] ?? '');
        redirect($ok ? 'internal-dashboard' : 'login');
    }

    public function login($username, $password)
    {
        $this->username = trim($username);
        $model = new UserModel();
        $user  = $model->get_data_user($this->username, $password);

        if ($user !== null && !empty($user['akun_diblokir'])) {
            $_SESSION['flash_login'] = 'Akun diblokir. Hubungi Admin.';
            return false;
        }

        if ($user !== null && ($user['status_akun'] ?? '') === 'Aktif') {
            session_regenerate_id(true);
            $_SESSION['userId']     = $user['id_user'];
            $_SESSION['role']       = $user['role'];
            $_SESSION['nama']       = $user['nama'] ?? '';   // sesuaikan nama kolom, dipakai di sidebar
            $_SESSION['waktuLogin'] = date('Y-m-d H:i:s');
            $_SESSION['percobaan_gagal'][$this->username] = 0;
            return true;
        }

        if ($this->hitung_percobaan_gagal() >= self::BATAS_GAGAL) {
            $model->blokir_akun($this->username);
            $_SESSION['flash_login'] = 'Akun diblokir. Hubungi Admin.';
        } else {
            $_SESSION['flash_login'] = 'Login gagal. Username atau password salah.';
        }
        return false;
    }

    private function hitung_percobaan_gagal()
    {
        $jumlah = ($_SESSION['percobaan_gagal'][$this->username] ?? 0) + 1;
        $_SESSION['percobaan_gagal'][$this->username] = $jumlah;
        return $jumlah;
    }

    public function logout()
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
        redirect('login');
    }
}