<?php
// Memulai session untuk autentikasi & profil user
session_start();

// Panggil konfigurasi Database & Controller utama
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/controllers/LaporanPenjualanController.php';
require_once __DIR__ . '/controllers/KasController.php';
require_once __DIR__ . '/controllers/ExportController.php';

// Menangkap rute halaman dari parameter URL (Default: internal-dashboard)
$page = isset($_GET['page']) ? $_GET['page'] : 'internal-dashboard';

// Routing Saklar Utama (MVC Router)
switch ($page) {

    // ========================================================
    // HALAMAN INTERNAL (STAFF / ADMIN / KASIR / PELAYAN)
    // ========================================================
    case 'internal-dashboard':
        require_once __DIR__ . '/views/internal/admin/dashboard_staff.php';
        break;

    case 'laporan-penjualan':
        // UC-14: Dashboard Laporan Penjualan (Eksekusi Controller)
        $controller = new LaporanPenjualanController();
        $controller->pilihMenuLaporanPenjualan();
        break;

    case 'simpan-kas':
        // UC-15: Eksekusi Simpan Kas Fisik Kasir
        $kasController = new KasController();
        $kasController->tekanSimpan();
        break;

    case 'export-excel':
        // UC-16: Eksekusi Download File Excel Laporan Penjualan
        $exportController = new ExportController();
        $exportController->tekanExportData();
        break;

    // Rute Modul Tim Lain (Diteruskan ke view masing-masing folder aktor)
    case 'pos':
        require_once __DIR__ . '/views/internal/kasir/pos_kasir.php';
        break;

    case 'reservasi':
        require_once __DIR__ . '/views/internal/admin/manajemen_reservasi.php';
        break;

    case 'kelola-menu':
        require_once __DIR__ . '/views/internal/admin/kelola_menu.php';
        break;

    case 'kelola-akun':
        require_once __DIR__ . '/views/internal/admin/kelola_akun.php';
        break;

    case 'laporan-kendala':
        require_once __DIR__ . '/views/internal/admin/daftar_kendala.php';
        break;

    case 'logout':
        session_destroy();
        header("Location: index.php?page=internal-dashboard");
        exit();

    // ========================================================
    // HALAMAN PELANGGAN (PUBLIC)
    // ========================================================
    case 'form-kendala':
        require_once __DIR__ . '/views/pelanggan/form_kendala.php';
        break;

    default:
        require_once __DIR__ . '/views/internal/admin/dashboard_staff.php';
        break;
}