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

    public function get_form_login()
    {
        $this->tampilkan_form_login();
    }

    public function login($username, $password)
    {
        $this->username = trim($username);
        $model = new UserModel();
        $user = $model->get_data_user($this->username, $password);

        if ($user !== null && !empty($user['akun_diblokir'])) {
            $this->tampilkan_akun_diblokir();
            return false;
        }

        if ($user !== null && ($user['status_akun'] ?? '') === 'Aktif') {
            session_regenerate_id(true);
            $_SESSION['userId'] = $user['id_user'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['waktuLogin'] = date('Y-m-d H:i:s');
            $_SESSION['percobaan_gagal'][$this->username] = 0;

            if ($user['role'] === 'Admin') {
                $this->tampilkan_dashboard_admin($user);
            } else {
                $this->tampilkan_dashboard_operasional($user);
            }

            return true;
        }

        $jumlah = $this->hitung_percobaan_gagal();

        if ($jumlah >= self::BATAS_GAGAL) {
            $model->blokir_akun($this->username);
            $this->tampilkan_akun_diblokir();
        } else {
            $this->tampilkan_login_gagal();
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
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
        $this->tampilkan_form_login();
    }

    public function tampilkan_form_login()
    {
        Respon::kirim('form_login');
    }

    public function tampilkan_login_gagal()
    {
        Respon::kirim('login_gagal', ['pesan' => 'Login gagal']);
    }

    public function tampilkan_akun_diblokir()
    {
        Respon::kirim('akun_diblokir', ['pesan' => 'Akun diblokir. Hubungi Admin.']);
    }

    public function tampilkan_dashboard_operasional($data)
    {
        Respon::kirim('dashboard_operasional', $data);
    }

    public function tampilkan_dashboard_admin($data)
    {
        Respon::kirim('dashboard_admin', $data);
    }
}