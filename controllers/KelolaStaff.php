<?php
require_once __DIR__ . '/../models/UserModel.php';

class KelolaStaff
{
    const ROLE_VALID = ['Admin', 'Manajer', 'Kasir', 'Pelayan'];

    // ?page=kelola-akun (opsional &q=kata)
    public function index()
    {
        $q = trim($_GET['q'] ?? '');
        $model = new UserModel();
        $daftarStaff = $q !== '' ? $model->cari_data_staff($q) : $model->getall_data_staff();

        $flash = $_SESSION['flash_staff'] ?? null;
        unset($_SESSION['flash_staff']);
        $idSaya = (int) ($_SESSION['userId'] ?? 0);

        require BASE_PATH . '/views/internal/admin/KelolaAkunStaff.php';
    }

    // ?page=staff-simpan (POST): id_user kosong = tambah, terisi = ubah
    public function simpan()
    {
        $this->wajibPost();
        $model = new UserModel();

        $id   = (int) ($_POST['id_user'] ?? 0);
        $data = [
            'nama'     => trim($_POST['nama'] ?? ''),
            'email'    => trim($_POST['email'] ?? ''),
            'username' => trim($_POST['username'] ?? ''),
            'role'     => $_POST['role'] ?? '',
            'password' => $_POST['password'] ?? '',
        ];

        if (!$this->validasi_data($data)) {
            $this->flash('Data tidak valid. Periksa nama, email, username, dan role.');
            redirect('kelola-akun');
        }
        if ($model->username_sudah_ada($data['username'], $id)) {
            $this->flash('Username "' . $data['username'] . '" sudah dipakai staff lain.');
            redirect('kelola-akun');
        }

        if ($id > 0) {
            // Admin tidak boleh mengubah role akunnya sendiri
            if ($id === (int) ($_SESSION['userId'] ?? 0)) {
                $lama = $model->get_data_staff($id);
                $data['role'] = $lama['role'] ?? $data['role'];
            }
            $ok = $model->ubah_data_staff($id, $data);
            $this->flash($ok ? 'Data staff berhasil diubah.' : 'Gagal mengubah data staff.', $ok);
        } else {
            if (strlen($data['password']) < 8) {
                $this->flash('Kata sandi awal minimal 8 karakter.');
                redirect('kelola-akun');
            }
            $data['status_akun'] = 'Aktif';
            $ok = $model->tambah_data_staff($data);
            $this->flash($ok ? 'Akun staff baru berhasil didaftarkan.' : 'Gagal menyimpan akun staff.', $ok);
        }
        redirect('kelola-akun');
    }

    // ?page=staff-status (POST): Aktif <-> Nonaktif
    public function ubah_status()
    {
        $this->wajibPost();
        $id = (int) ($_POST['id_user'] ?? 0);

        if ($id === (int) ($_SESSION['userId'] ?? 0)) {
            $this->flash('Anda tidak dapat menonaktifkan akun Anda sendiri.');
            redirect('kelola-akun');
        }

        $model = new UserModel();
        $staff = $model->get_data_staff($id);
        if (!$staff || $staff['status_akun'] === 'Diblokir') {
            $this->flash('Akun tidak ditemukan, atau sedang diblokir (gunakan Buka Blokir).');
            redirect('kelola-akun');
        }

        $baru = $staff['status_akun'] === 'Aktif' ? 'Nonaktif' : 'Aktif';
        $ok = $model->ubah_status_akun($id, $baru);
        $this->flash($ok ? "Status akun menjadi $baru." : 'Gagal mengubah status akun.', $ok);
        redirect('kelola-akun');
    }

    // ?page=staff-buka-blokir (POST)
    public function buka_blokir()
    {
        $this->wajibPost();
        $id = (int) ($_POST['id_user'] ?? 0);
        $ok = $id > 0 && (new UserModel())->buka_blokir($id);
        $this->flash($ok ? 'Blokir akun dibuka. Akun aktif kembali.' : 'Gagal membuka blokir.', $ok);
        redirect('kelola-akun');
    }

    // ?page=staff-reset-password (POST): password acak, ditampilkan sekali
    public function reset_password()
    {
        $this->wajibPost();
        $id = (int) ($_POST['id_user'] ?? 0);

        $model = new UserModel();
        $staff = $model->get_data_staff($id);
        if (!$staff) {
            $this->flash('Akun tidak ditemukan.');
            redirect('kelola-akun');
        }

        $baru = substr(bin2hex(random_bytes(6)), 0, 10);
        $ok = $model->ubah_password($id, $baru);
        $this->flash(
            $ok ? 'Password @' . $staff['username'] . ' direset menjadi: ' . $baru . ' (catat sekarang, tidak ditampilkan lagi).'
                : 'Gagal mereset password.',
            $ok
        );
        redirect('kelola-akun');
    }

    private function validasi_data($d)
    {
        return $d['nama'] !== '' && $d['username'] !== ''
            && filter_var($d['email'], FILTER_VALIDATE_EMAIL)
            && in_array($d['role'], self::ROLE_VALID, true);
    }

    private function flash($pesan, $sukses = false)
    {
        $_SESSION['flash_staff'] = ['pesan' => $pesan, 'sukses' => $sukses];
    }

    private function wajibPost()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('kelola-akun');
        }
    }
}