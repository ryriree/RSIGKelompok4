<?php
// Memulai session untuk autentikasi & profil user/pelanggan
session_start();
date_default_timezone_set('Asia/Jakarta');
define('BASE_PATH', __DIR__);

// Konfigurasi Database
require_once BASE_PATH . '/config/Database.php';

// Autoload: controller & model dimuat otomatis saat class dipakai
// (menggantikan require_once controller satu per satu & class_exists di tiap case)
spl_autoload_register(function ($class) {
    foreach (['controllers', 'models'] as $dir) {
        $file = BASE_PATH . "/$dir/$class.php";
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// ========================================================
// HELPER
// ========================================================
function url($page)      { return 'index.php?page=' . $page; }
function redirect($page) { header('Location: ' . url($page)); exit; }

// ========================================================
// TABEL ROUTE
// roles: null         = publik (tanpa login)
//        ['*']        = semua staff yang sudah login
//        ['kasir',..] = hanya role tertentu (huruf kecil)
// Isi salah satu: 'c' + 'm' (controller + method) ATAU 'view' (file view langsung)
// ========================================================
$routes = [

    // ===== DASHBOARD PELANGGAN (PUBLIC, tanpa login) =====
    'home'          => ['view' => 'pelanggan/dashboard', 'roles' => null],
    'menu-pelanggan' => ['c' => 'MenuController',   'm' => 'menuPelanggan', 'roles' => null],
        // ===== PELANGGAN: Pesan -> Bayar -> Invoice =====
    'pesan'        => ['c' => 'PesananController',    'm' => 'formPesan',      'roles' => null, 'layout' => 'pelanggan'],
    'pesan-kirim'  => ['c' => 'PesananController',    'm' => 'kirimPesanan',   'roles' => null],
    'bayar'        => ['c' => 'PembayaranController', 'm' => 'index',          'roles' => null, 'layout' => 'pelanggan'],
    'bayar-qris'   => ['c' => 'PembayaranController', 'm' => 'konfirmasiQris', 'roles' => null],
    'bayar-status' => ['c' => 'PembayaranController', 'm' => 'status',         'roles' => null],
    'invoice'      => ['c' => 'PembayaranController', 'm' => 'invoice',        'roles' => null, 'layout' => 'pelanggan'],
    
    // UC-08: Melaporkan Kendala / Contact Us
    'form-kendala'  => ['c' => 'MelaporkanKendalaController', 'm' => 'pilihMenuContactUs', 'roles' => null],
    'kirim-kendala' => ['c' => 'MelaporkanKendalaController', 'm' => 'tekanKirim',          'roles' => null],
    'hubungi-wa'    => ['c' => 'MelaporkanKendalaController', 'm' => 'tekanHubungi',        'roles' => null],

    // ===== AUTH STAFF =====
    'login'        => ['c' => 'Auth', 'm' => 'get_form_login', 'roles' => null],
    'proses-login' => ['c' => 'Auth', 'm' => 'proses_login',   'roles' => null],
    'logout'       => ['c' => 'Auth', 'm' => 'logout',         'roles' => null],

    // ===== DASHBOARD STAFF (semua role yang sudah login) =====
    'internal-dashboard' => ['view' => 'internal/admin/dashboard_staff', 'roles' => ['*'], 'layout' => 'staff'],
    // ===== ADMIN =====
       // ===== PELANGGAN: Reservasi 4 langkah =====
    'form-reservasi'             => ['c' => 'Reservasi', 'm' => 'get_form_reservasi',  'roles' => null, 'layout' => 'pelanggan'],
    'reservasi-slot'             => ['c' => 'Reservasi', 'm' => 'slotTerisi',          'roles' => null],
    'reservasi-jadwal'           => ['c' => 'Reservasi', 'm' => 'prosesJadwal',        'roles' => null],
    'reservasi-lantai'           => ['c' => 'Reservasi', 'm' => 'stepLantai',          'roles' => null, 'layout' => 'pelanggan'],
    'reservasi-lantai-simpan'    => ['c' => 'Reservasi', 'm' => 'prosesLantai',        'roles' => null],
    'reservasi-paket'            => ['c' => 'Reservasi', 'm' => 'stepPaket',           'roles' => null, 'layout' => 'pelanggan'],
    'reservasi-paket-simpan'     => ['c' => 'Reservasi', 'm' => 'prosesPaket',         'roles' => null],
    'reservasi-bayar'            => ['c' => 'Reservasi', 'm' => 'stepBayar',           'roles' => null, 'layout' => 'pelanggan'],
    'reservasi-bayar-konfirmasi' => ['c' => 'Reservasi', 'm' => 'konfirmasiBayar',     'roles' => null],
    'reservasi-voucher'          => ['c' => 'Reservasi', 'm' => 'voucher',             'roles' => null, 'layout' => 'pelanggan'],
    'reservasi-unduh'            => ['c' => 'Reservasi', 'm' => 'unduhVoucher',        'roles' => null],
    'reservasi-draft-batal'      => ['c' => 'Reservasi', 'm' => 'batalkan_reservasi',  'roles' => null],
   

    // ===== Kelola Akun Staff (Admin) =====
    'kelola-akun'          => ['c' => 'KelolaStaff', 'm' => 'index',          'roles' => ['admin'], 'layout' => 'staff'],
    'staff-simpan'         => ['c' => 'KelolaStaff', 'm' => 'simpan',         'roles' => ['admin']],
    'staff-status'         => ['c' => 'KelolaStaff', 'm' => 'ubah_status',    'roles' => ['admin']],
    'staff-buka-blokir'    => ['c' => 'KelolaStaff', 'm' => 'buka_blokir',    'roles' => ['admin']],
    'staff-reset-password' => ['c' => 'KelolaStaff', 'm' => 'reset_password', 'roles' => ['admin']],
    'laporan-kendala'     => ['c' => 'LaporanKendalaController', 'm' => 'tampilkanDaftar', 'roles' => ['admin', 'manajer'], 'layout' => 'staff'],
    'ubah-status-laporan' => ['c' => 'LaporanKendalaController', 'm' => 'ubahStatus',      'roles' => ['admin']],
    // UC-14: Dashboard Laporan Penjualan
    'laporan-penjualan' => ['c' => 'LaporanPenjualanController', 'm' => 'pilihMenuLaporanPenjualan', 'roles' => ['manajer', 'admin'], 'layout' => 'staff'],
      // UC-15: Simpan Kas Fisik
    'simpan-kas'        => ['c' => 'KasController',  'm' => 'tekanSimpan',       'roles' => ['admin']],
    // UC-16: Download Excel Laporan Penjualan
    'export-excel'      => ['c' => 'ExportController', 'm' => 'tekanExportData', 'roles' => ['admin']],

    // ===== KASIR =====
        // ===== KASIR =====
    'pos'       => ['c' => 'PosController', 'm' => 'index', 'roles' => ['kasir'], 'layout' => 'staff'],
    'pos-bayar' => ['c' => 'PosController', 'm' => 'bayar', 'roles' => ['kasir']],
    'struk'     => ['c' => 'StrukController', 'm' => 'index',  'roles' => ['kasir'], 'layout' => 'staff'],
    // Kelola Menu (tampil, simpan tambah/edit, toggle aktif/nonaktif)
      'kelola-menu'       => ['c' => 'MenuController', 'm' => 'tampilkanMenu',    'roles' => ['kasir'], 'layout' => 'staff'],
      'simpan-menu'       => ['c' => 'MenuController', 'm' => 'simpanMenu',       'roles' => ['kasir']],
      'ubah-ketersediaan' => ['c' => 'MenuController', 'm' => 'ubahKetersediaan', 'roles' => ['kasir']],

    // ===== PELAYAN =====
     'status-pesanan' => ['c' => 'PesananController', 'm' => 'statusPelayan', 'roles' => ['pelayan'], 'layout' => 'staff'],
    'pesanan-status' => ['c' => 'PesananController', 'm' => 'updateStatus',  'roles' => ['pelayan']],
];

// ========================================================
// DISPATCHER
// ========================================================
$page = $_GET['page'] ?? 'home';

// Rute tidak dikenal -> kembali ke Dashboard Pelanggan
if (!isset($routes[$page])) {
    $page = 'home';
}

$route = $routes[$page];

// Guard: cek login & role untuk halaman staff
if ($route['roles'] !== null) {
    if (empty($_SESSION['userId'])) {
        redirect('login');
    }

    $role = strtolower($_SESSION['role'] ?? '');

    if ($route['roles'] !== ['*'] && !in_array($role, $route['roles'])) {
        http_response_code(403);
        exit('Anda tidak punya akses ke halaman ini');
    }
}

// Eksekusi (output ditampung dulu agar bisa dibungkus layout)
ob_start();

if (isset($route['c'])) {
    $controller = new $route['c']();
    $controller->{$route['m']}();
} else {
    require BASE_PATH . '/views/' . $route['view'] . '.php';
}

$content = ob_get_clean();

$layout = $route['layout'] ?? null;

if ($layout === 'staff') {
    require BASE_PATH . '/views/layout/staff.php';
} elseif ($layout === 'pelanggan') {
    require BASE_PATH . '/views/layout/pelanggan.php';
} else {
    echo $content;
}