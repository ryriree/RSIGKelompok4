<?php
// Merekam sesi login jika nanti dibutuhkan
session_start();

// Panggil konfigurasi Database & Controller yang dibutuhkan
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/controllers/KasController.php';

// Menangkap parameter halaman dari URL (Default ke halaman 'home')
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Routing Saklar Utama (MVC Router)
switch ($page) {

    // ========================================================
    // HALAMAN INTERNAL (STAFF / ADMIN)
    // ========================================================
    case 'internal-dashboard':
        // Tampilan Dashboard Staff & Audit Kas Penjualan
        require_once __DIR__ . '/views/internal/admin/dashboard_staff.php';
        break;

    case 'internal-kas':
        // Eksekusi Simpan Data Kas Fisik (UC-15) via Controller
        $kasController = new KasController();
        $kasController->simpanKas();
        break;


    // ========================================================
    // HALAMAN PELANGGAN (PUBLIC)
    // ========================================================
    case 'form-kendala':
        // Tampilan Form Pelaporan Kendala / Feedback (UC-08)
        require_once __DIR__ . '/views/pelanggan/form_kendala.php';
        break;

    case 'home':
    default:
        // Halaman Utama / Landing Page (Sementara diarahkan ke Form Kendala atau Dashboard)
        require_once __DIR__ . '/views/pelanggan/dashboard.php';
        break;
}