<?php
require_once __DIR__ . '/../models/ReservasiModel.php';

class KelolaReservasi
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (strtolower($_SESSION['role'] ?? '') !== 'admin') {
            http_response_code(403);
            exit('Akses ditolak');
        }
    }

    // ================== HALAMAN (GET) ==================

    // ?page=reservasi
        // ?page=reservasi  (opsional &q=kata)
 public function index()
{
    $q = trim($_GET['q'] ?? '');
    $model = new ReservasiModel();
    $data = $q !== '' ? $model->cari_data_reservasi($q) : $model->getall_data_reservasi();

    $this->render('daftar', ['daftarReservasi' => $data, 'data' => $data, 'q' => $q]);
}
    // ?page=reservasi-tambah-form
    public function form_tambah()
    {
        $this->tampilkan_form_tambah();
    }

    // ?page=reservasi-ubah-form&id=3
    public function form_ubah()
    {
        $id = (int) ($_GET['id'] ?? 0);
        $data = (new ReservasiModel())->get_data_reservasi($id);

        if (!$data) {
            $this->flash('Data reservasi tidak ditemukan.');
            redirect('reservasi');
        }
        $this->tampilkan_form_ubah($data);
    }

    // ================== AKSI (POST) -> redirect ==================

    // ?page=reservasi-tambah
    public function proses_tambah()
    {
        $this->wajibPost();
        $data = $_POST;

        if (!$this->validasi_data($data)) {
            $this->flash('Data tidak valid. Periksa kembali isian form.');
            redirect('reservasi-tambah-form');
        }

        $ok = (new ReservasiModel())->tambah_data_reservasi($data);
        $this->flash($ok ? 'Reservasi berhasil ditambahkan.' : 'Gagal menambahkan reservasi.', $ok);
        redirect('reservasi');
    }

    // ?page=reservasi-ubah
    public function proses_ubah()
    {
        $this->wajibPost();
        $data = $_POST;

        if (!$this->validasi_data($data) || empty($data['id_reservasi'])) {
            $this->flash('Data tidak valid. Periksa kembali isian form.');
            redirect('reservasi');
        }

        $ok = (new ReservasiModel())->ubah_data_reservasi($data);
        $this->flash($ok ? 'Reservasi berhasil diubah.' : 'Gagal mengubah reservasi.', $ok);
        redirect('reservasi');
    }

    // ?page=reservasi-batal
    public function proses_batal()
    {
        $this->wajibPost();
        $id = (int) ($_POST['id_reservasi'] ?? 0);

        $ok = (new ReservasiModel())->ubah_status_atau_hapus_data($id);
        $this->flash($ok ? 'Reservasi dibatalkan.' : 'Gagal membatalkan reservasi.', $ok);
        redirect('reservasi');
    }

    // ?page=reservasi-status-dp
    public function proses_status_dp()
    {
        $this->wajibPost();
        $id = (int) ($_POST['id_reservasi'] ?? 0);
        $statusDP = $_POST['status_dp'] ?? '';

        if (!in_array($statusDP, ['Belum Dibayar', 'Lunas'], true)) {
            $this->flash('Status DP tidak valid.');
            redirect('reservasi');
        }

        $ok = (new ReservasiModel())->ubah_data_status_dp($id, $statusDP);
        $this->flash($ok ? 'Status DP diperbarui.' : 'Gagal memperbarui status DP.', $ok);
        redirect('reservasi');
    }

    // ================== VALIDASI ==================

    private function validasi_data($data)
    {
        if (
            empty($data['nama_pemesan']) ||
            empty($data['kontak_pemesanan']) ||
            empty($data['tanggal']) ||
            empty($data['slot_waktu']) ||
            (int) ($data['jumlah_orang'] ?? 0) < 1 ||
            !in_array((int) ($data['lantai'] ?? 0), [1, 2], true)
        ) {
            return false;
        }

        $date = DateTime::createFromFormat('Y-m-d', $data['tanggal']);
        return $date && $date->format('Y-m-d') === $data['tanggal'];
    }

    // ================== TAMPILAN ==================

    public function tampilkan_daftar_reservasi($data)
    {
        $this->render('daftar', ['daftarReservasi' => $data, 'data' => $data]);
    }

    public function tampilkan_form_tambah()
    {
        $this->render('tambah');
    }

    public function tampilkan_form_ubah($data)
    {
        $this->render('ubah', ['dataUbah' => $data]);
    }

    // ================== HELPER ==================

    private function render($mode, $vars = [])
    {
        $flash = $_SESSION['flash_reservasi'] ?? null;
        unset($_SESSION['flash_reservasi']);

        extract($vars);
        require BASE_PATH . '/views/internal/admin/KelolaReservasi.php';  // $mode: daftar | tambah | ubah
    }

    private function flash($pesan, $sukses = false)
    {
        $_SESSION['flash_reservasi'] = ['pesan' => $pesan, 'sukses' => $sukses];
    }

    private function wajibPost()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('reservasi');
        }
    }
}