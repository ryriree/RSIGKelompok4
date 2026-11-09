<?php
// Memulai session untuk autentikasi & profil user/pelanggan
session_start();

// Panggil konfigurasi Database & Controller Utama
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/controllers/LaporanPenjualanController.php';
require_once __DIR__ . '/controllers/KasController.php';
require_once __DIR__ . '/controllers/ExportController.php';
require_once __DIR__ . '/controllers/MelaporkanKendalaController.php';

// Menangkap rute halaman dari parameter URL (Default utama: 'home' / Dashboard Pelanggan)
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Routing Saklar Utama (MVC Router)
switch ($page) {

    // ========================================================
    // HALAMAN UTAMA / DASHBOARD PELANGGAN (PUBLIC FRONTEND)
    // ========================================================
    case 'home':
        // Menampilkan Dashboard Pelanggan (File: views/pelanggan/dashboard.php)
        if (file_exists(__DIR__ . '/views/pelanggan/dashboard.php')) {
            require_once __DIR__ . '/views/pelanggan/dashboard.php';
        } else {
            require_once __DIR__ . '/views/dashboard.php';
        }
        break;

    case 'form-kendala':
        // UC-08: Halaman Form Melaporkan Kendala / Contact Us
        $kendalaController = new MelaporkanKendalaController();
        $kendalaController->pilihMenuContactUs();
        break;

    case 'kirim-kendala':
        // UC-08: Submit Form Laporan Kendala Pelanggan
        $kendalaController = new MelaporkanKendalaController();
        $kendalaController->tekanKirim();
        break;

    case 'hubungi-wa':
        // UC-08: Direct Link Chat WhatsApp Admin
        $kendalaController = new MelaporkanKendalaController();
        $kendalaController->tekanHubungi();
        break;

    // ========================================================
    // HALAMAN INTERNAL RESTORAN (STAFF / ADMIN / KASIR)
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
        header("Location: index.php?page=home");
        exit();

    // Rute Default / Fallback: Kembali ke Dashboard Pelanggan (dashboard.php)
    default:
        if (file_exists(__DIR__ . '/views/pelanggan/dashboard.php')) {
            require_once __DIR__ . '/views/pelanggan/dashboard.php';
        } else {
            require_once __DIR__ . '/views/dashboard.php';
        }
        break;
}