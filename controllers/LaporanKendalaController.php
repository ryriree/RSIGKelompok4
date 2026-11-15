<?php
require_once __DIR__ . '/../models/LaporanKendalaInternal.php';

class LaporanKendalaController
{
    private $laporanModel;

    public function __construct()
    {
        $this->laporanModel = new LaporanKendalaInternal();
    }

    // ?page=laporan-kendala
    public function tampilkanDaftar()
    {
        $keyword    = trim($_GET['cari'] ?? '');
        $jenis      = $_GET['jenis'] ?? 'Semua';
        $status     = $_GET['status'] ?? 'Semua';
        $halaman    = max(1, (int)($_GET['halaman'] ?? 1));
        $perHalaman = 5;

        $totalData    = $this->laporanModel->hitungDaftarLaporan($keyword, $jenis, $status);
        $totalHalaman = max(1, (int)ceil($totalData / $perHalaman));
        $halaman      = min($halaman, $totalHalaman);
        $daftarLaporan = $this->laporanModel->getDaftarLaporan(
            $keyword, $jenis, $status, $perHalaman, ($halaman - 1) * $perHalaman
        );
        $ringkasan = $this->laporanModel->getRingkasan();

        $pesan = $_SESSION['pesan_laporan'] ?? '';
        unset($_SESSION['pesan_laporan']);

        require BASE_PATH . '/views/internal/admin/daftar_kendala.php';
    }

    // ?page=ubah-status-laporan (POST, admin)
    public function ubahStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('laporan-kendala');
        }

        $idLaporan = (int)($_POST['id_laporan'] ?? 0);
        $status    = $_POST['status'] ?? '';
        $catatan   = trim($_POST['catatan_penanganan'] ?? '');
        $idUser    = (int)($_SESSION['userId'] ?? 0);
        $valid     = ['Pending', 'Diproses', 'Selesai'];

        if ($idLaporan < 1 || !in_array($status, $valid, true) || $idUser < 1 || !$this->laporanModel->getLaporanById($idLaporan)) {
            $_SESSION['pesan_laporan'] = 'Data perubahan tidak valid atau laporan tidak ditemukan.';
        } elseif ($this->laporanModel->ubahStatusLaporan($idLaporan, $status, $catatan, $idUser)) {
            $_SESSION['pesan_laporan'] = 'Status penanganan laporan berhasil diperbarui.';
        } else {
            $_SESSION['pesan_laporan'] = 'Status laporan gagal diperbarui.';
        }

        redirect('laporan-kendala');
    }
}