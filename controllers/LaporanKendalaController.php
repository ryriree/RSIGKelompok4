<?php
require_once __DIR__ . '/../models/LaporanKendalaInternal.php';

class LaporanKendalaController {
    private $laporanModel;

    public function __construct() {
        $this->laporanModel = new LaporanKendalaInternal();
    }

    private function cekRole($roles) {
        $role = $_SESSION['user']['role'] ?? '';
        if (!in_array($role, $roles, true)) {
            http_response_code(403);
            echo '<h2>Akses ditolak</h2><p>Anda tidak memiliki hak akses untuk halaman ini.</p>';
            exit;
        }
    }

    public function tampilkanDaftar() {
        $this->cekRole(['Admin', 'Manajer']);
        $keyword = trim($_GET['cari'] ?? '');
        $jenis = $_GET['jenis'] ?? 'Semua';
        $status = $_GET['status'] ?? 'Semua';
        $halaman = max(1, (int)($_GET['halaman'] ?? 1));
        $perHalaman = 5;

        $totalData = $this->laporanModel->hitungDaftarLaporan($keyword, $jenis, $status);
        $totalHalaman = max(1, (int)ceil($totalData / $perHalaman));
        $halaman = min($halaman, $totalHalaman);
        $daftarLaporan = $this->laporanModel->getDaftarLaporan(
            $keyword, $jenis, $status, $perHalaman, ($halaman - 1) * $perHalaman
        );
        $ringkasan = $this->laporanModel->getRingkasan();

        require __DIR__ . '/../views/internal/admin/daftar_kendala.php';
    }

    public function ubahStatus() {
        $this->cekRole(['Admin']);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=laporan-kendala');
            exit;
        }

        $idLaporan = (int)($_POST['id_laporan'] ?? 0);
        $status = $_POST['status'] ?? '';
        $catatan = trim($_POST['catatan_penanganan'] ?? '');
        $idUser = (int)($_SESSION['user']['id_user'] ?? 0);
        $statusValid = ['Pending', 'Diproses', 'Selesai'];

        if ($idLaporan < 1 || !in_array($status, $statusValid, true) || $idUser < 1 || !$this->laporanModel->getLaporanById($idLaporan)) {
            $_SESSION['pesan_laporan'] = 'Data perubahan tidak valid atau laporan tidak ditemukan.';
        } elseif ($this->laporanModel->ubahStatusLaporan($idLaporan, $status, $catatan, $idUser)) {
            $_SESSION['pesan_laporan'] = 'Status penanganan laporan berhasil diperbarui.';
        } else {
            $_SESSION['pesan_laporan'] = 'Status laporan gagal diperbarui.';
        }

        header('Location: index.php?page=laporan-kendala');
        exit;
    }
}
