<?php
require_once __DIR__ . '/../models/Menu.php';
class MenuController {
    private $menuModel;
    public function __construct() { $this->menuModel = new Menu(); }
    private function cekAkses() {
        // UC-09 pada spesifikasi kelompok menggunakan aktor Kasir.
        $role = $_SESSION['user']['role'] ?? '';
        if (!in_array($role, ['Kasir', 'Admin'], true)) {
            http_response_code(403); exit('Akses ditolak. Fitur pengelolaan menu hanya untuk Kasir/Admin.');
        }
    }
    public function tampilkanMenu() {
        $this->cekAkses();
        $daftarMenu = $this->menuModel->getSemuaMenu();
        $pesanMenu = $_SESSION['pesan_menu'] ?? '';
        unset($_SESSION['pesan_menu']);
        require __DIR__ . '/../views/internal/admin/kelola_menu.php';
    }
    public function simpanMenu() {
        $this->cekAkses();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php?page=kelola-menu'); exit; }
        $id = (int)($_POST['id_menu'] ?? 0);
        $nama = trim($_POST['nama_menu'] ?? '');
        $kategori = trim($_POST['kategori'] ?? '');
        $harga = filter_var($_POST['harga'] ?? null, FILTER_VALIDATE_FLOAT);
        $deskripsi = trim($_POST['deskripsi'] ?? '');
        $tersedia = isset($_POST['status_tersedia']) ? 1 : 0;
        if ($nama === '' || $kategori === '' || $harga === false || $harga < 0) {
            $_SESSION['pesan_menu'] = 'Data menu tidak valid. Periksa nama, kategori, dan harga.';
        } else {
            $berhasil = $id > 0
                ? $this->menuModel->ubahMenu($id, $nama, $kategori, $harga, $deskripsi, $tersedia)
                : $this->menuModel->tambahMenu($nama, $kategori, $harga, $deskripsi, $tersedia);
            $_SESSION['pesan_menu'] = $berhasil ? 'Data menu berhasil disimpan.' : 'Data menu gagal disimpan.';
        }
        header('Location: index.php?page=kelola-menu'); exit;
    }
    public function ubahKetersediaan() {
        $this->cekAkses();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id_menu'] ?? 0);
            $tersedia = (int)($_POST['status_tersedia'] ?? 0) === 1 ? 1 : 0;
            $_SESSION['pesan_menu'] = ($id > 0 && $this->menuModel->ubahKetersediaan($id, $tersedia)) ? 'Ketersediaan menu berhasil diperbarui.' : 'Ketersediaan menu gagal diperbarui.';
        }
        header('Location: index.php?page=kelola-menu'); exit;
    }
}
