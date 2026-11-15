<?php
require_once __DIR__ . '/../models/MenuModel.php';

class MenuController
{
    private $menuModel;

    public function __construct()
    {
        $this->menuModel = new MenuModel();
    }

    // ?page=kelola-menu
    public function tampilkanMenu()
    {
        $daftarMenu = $this->menuModel->getSemuaMenu();
        $pesanMenu  = $_SESSION['pesan_menu'] ?? '';
        unset($_SESSION['pesan_menu']);

        require BASE_PATH . '/views/internal/kasir/kelola_menu.php';
    }

    // ?page=simpan-menu (POST)
    public function simpanMenu()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('kelola-menu');
        }

        $id        = (int) ($_POST['id_menu'] ?? 0);
        $nama      = trim($_POST['nama_menu'] ?? '');
        $kategori  = trim($_POST['kategori'] ?? '');
        $harga     = filter_var($_POST['harga'] ?? null, FILTER_VALIDATE_FLOAT);
        $deskripsi = trim($_POST['deskripsi'] ?? '');
        $tersedia  = isset($_POST['status_tersedia']) ? 1 : 0;

        if ($nama === '' || $kategori === '' || $harga === false || $harga < 0) {
            $_SESSION['pesan_menu'] = 'Data menu tidak valid. Periksa nama, kategori, dan harga.';
        } else {
            $berhasil = $id > 0
                ? $this->menuModel->ubahMenu($id, $nama, $kategori, $harga, $deskripsi, $tersedia)
                : $this->menuModel->tambahMenu($nama, $kategori, $harga, $deskripsi, $tersedia);

            $_SESSION['pesan_menu'] = $berhasil ? 'Data menu berhasil disimpan.' : 'Data menu gagal disimpan.';
        }

        redirect('kelola-menu');
    }

    // ?page=ubah-ketersediaan (POST)
    public function ubahKetersediaan()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('kelola-menu');
        }

        $id       = (int) ($_POST['id_menu'] ?? 0);
        $tersedia = (int) ($_POST['status_tersedia'] ?? 0) === 1 ? 1 : 0;

        $berhasil = $id > 0 && $this->menuModel->ubahKetersediaan($id, $tersedia);
        $_SESSION['pesan_menu'] = $berhasil
            ? 'Ketersediaan menu berhasil diperbarui.'
            : 'Ketersediaan menu gagal diperbarui.';

        redirect('kelola-menu');
    }

        // ?page=menu-pelanggan (publik)
            // ?page=menu-pelanggan (publik)
    public function menuPelanggan()
    {
        $kategori = $_GET['kategori'] ?? null;
        $menus    = $this->menuModel->getAllMenu($kategori);

        require BASE_PATH . '/views/pelanggan/Melihat_Menu.php';
    }
}