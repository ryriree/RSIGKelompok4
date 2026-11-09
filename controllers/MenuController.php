<?php
require_once __DIR__ . '/../models/Menu.php';

class MenuController
{
    private $menuModel;

    public function __construct()
    {
        $this->menuModel = new Menu();
    }

    /**
     * UC-09: hanya pengguna dengan role Kasir yang boleh mengakses
     * halaman maupun proses tambah, ubah, dan penonaktifan menu.
     */
    private function cekAksesKasir()
    {
        $role = $_SESSION['user']['role'] ?? '';

        if ($role !== 'Kasir') {
            http_response_code(403);
            exit('Akses ditolak. Pengelolaan menu hanya dapat dilakukan oleh Kasir.');
        }
    }

    public function tampilkanMenu()
    {
        $this->cekAksesKasir();

        $daftarMenu = $this->menuModel->getSemuaMenu();
        $pesanMenu = $_SESSION['pesan_menu'] ?? '';
        unset($_SESSION['pesan_menu']);

        require __DIR__ . '/../views/internal/kasir/kelola_menu.php';
    }

    public function simpanMenu()
    {
        $this->cekAksesKasir();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=kelola-menu');
            exit;
        }

        $id = (int) ($_POST['id_menu'] ?? 0);
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

            $_SESSION['pesan_menu'] = $berhasil
                ? 'Data menu berhasil disimpan.'
                : 'Data menu gagal disimpan.';
        }

        header('Location: index.php?page=kelola-menu');
        exit;
    }

    public function ubahKetersediaan()
    {
        $this->cekAksesKasir();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=kelola-menu');
            exit;
        }

        $id = (int) ($_POST['id_menu'] ?? 0);
        $tersedia = (int) ($_POST['status_tersedia'] ?? 0) === 1 ? 1 : 0;

        $berhasil = $id > 0 && $this->menuModel->ubahKetersediaan($id, $tersedia);
        $_SESSION['pesan_menu'] = $berhasil
            ? 'Ketersediaan menu berhasil diperbarui.'
            : 'Ketersediaan menu gagal diperbarui.';

        header('Location: index.php?page=kelola-menu');
        exit;
    }
}
