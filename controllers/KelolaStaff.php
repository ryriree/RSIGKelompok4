<?php
require_once __DIR__ . '/../models/UserModel.php';

class KelolaStaff
{
    const ROLE_VALID = [
        'Admin',
        'Manajer',
        'Kasir',
        'Pelayan'
    ];

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (($_SESSION['role'] ?? '') !== 'Admin') {
            http_response_code(403);
            exit('Akses ditolak');
        }
    }

    public function get_staff($idUser = null)
    {
        $model = new UserModel();

        if ($idUser === null) {
            $this->tampilkan_daftar_staff(
                $model->getall_data_staff()
            );
            return;
        }

        $data = $model->get_data_staff($idUser);

        if ($data) {
            $this->tampilkan_form_ubah($data);
        }
    }

    public function form_tambah()
    {
        $this->tampilkan_form_tambah();
    }

    public function tambah_staff(
        $nama,
        $email,
        $username,
        $role,
        $password
    ) {
        $data = compact('nama', 'email', 'username', 'role', 'password');

        if (!$this->validasi_data($data) || strlen($password) < 8) {
            $this->tampilkan_pesan_data_tidak_valid();
            return false;
        }

        $data['status_akun'] = 'Aktif';

        $model = new UserModel();
        $ok = $model->tambah_data_staff($data);

        if ($ok) {
            $this->tampilkan_daftar_staff(
                $model->getall_data_staff()
            );
        }

        return $ok;
    }

    public function ubah_staff($idUser, $dataStaff)
    {
        if (!$this->validasi_data($dataStaff)) {
            $this->tampilkan_pesan_data_tidak_valid();
            return false;
        }

        $model = new UserModel();
        $ok = $model->ubah_data_staff($idUser, $dataStaff);

        if ($ok) {
            $this->tampilkan_daftar_staff(
                $model->getall_data_staff()
            );
        }

        return $ok;
    }

    private function validasi_data($data)
    {
        return
            !empty($data['nama']) &&
            !empty($data['email']) &&
            !empty($data['username']) &&
            filter_var($data['email'], FILTER_VALIDATE_EMAIL) &&
            in_array($data['role'], self::ROLE_VALID, true);
    }

    public function nonaktifkan_staff($idUser)
    {
        $model = new UserModel();
        $ok = $model->ubah_status_akun($idUser, 'Nonaktif');

        if ($ok) {
            $this->tampilkan_daftar_staff(
                $model->getall_data_staff()
            );
        }

        return $ok;
    }

    public function batal_konfirmasi()
    {
        $this->tutup_dialog_konfirmasi();
    }

    public function tampilkan_daftar_staff($data)
    {
        Respon::kirim('daftar_staff', $data);
    }

    public function tampilkan_form_tambah()
    {
        Respon::kirim('form_tambah');
    }

    public function tampilkan_form_ubah($data)
    {
        Respon::kirim('form_ubah', $data);
    }

    public function tampilkan_pesan_data_tidak_valid()
    {
        Respon::kirim('data_tidak_valid');
    }

    public function tutup_dialog_konfirmasi()
    {
        Respon::kirim('tutup_dialog');
    }
}