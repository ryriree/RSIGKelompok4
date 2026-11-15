<?php
// roles: ['*'] = semua staff | atau daftar role huruf kecil
// id    : harus sama dengan nama route di index.php
// icon  : nama ikon yang ada di views/layouts/staff.php
return [
    // OVERVIEW
    ['id' => 'internal-dashboard', 'label' => 'Dashboard',          'icon' => 'home',  'group' => 'OVERVIEW',               'roles' => ['*']],
    ['id' => 'pos',                'label' => 'Antrean POS Kasir',  'icon' => 'pos',   'group' => 'OVERVIEW',               'roles' => ['kasir']],
    ['id' => 'status-pesanan',     'label' => 'Status Pesanan',     'icon' => 'list',  'group' => 'OVERVIEW',               'roles' => ['pelayan']],

    // MANAJEMEN OPERASIONAL
    ['id' => 'laporan-penjualan',  'label' => 'Laporan Penjualan',  'icon' => 'chart', 'group' => 'MANAJEMEN OPERASIONAL',  'roles' => ['manajer', 'admin']],
    ['id' => 'reservasi',          'label' => 'Manajemen Reservasi','icon' => 'cal',   'group' => 'MANAJEMEN OPERASIONAL',  'roles' => ['admin']],
    ['id' => 'kelola-menu',        'label' => 'Kelola Menu',        'icon' => 'leg',   'group' => 'MANAJEMEN OPERASIONAL',  'roles' => ['kasir']],

    // PENGATURAN & LAINNYA
    ['id' => 'kelola-akun',        'label' => 'Kelola Akun Staff',  'icon' => 'users', 'group' => 'PENGATURAN & LAINNYA',   'roles' => ['admin']],
    ['id' => 'laporan-kendala',    'label' => 'Komplain & Feedback','icon' => 'msg',   'group' => 'PENGATURAN & LAINNYA',   'roles' => ['admin', 'manajer']],
];