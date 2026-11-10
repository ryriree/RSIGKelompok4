<?php
// Include koneksi database
require_once 'database.php';

$database = new Database();
$db = $database->getConnection();

// Ambil kode pesanan dari parameter GET atau gunakan default teratas (#MC-8821 / TRX-20261025-0142)
$kode_pesanan = isset($_GET['kode']) ? $_GET['kode'] : 'TRX-20261025-0142';

// 1. Query Daftar Pesanan Aktif untuk Sidebar / List Antrean (Tabel `pesanan`)
$query_list = "SELECT p.*, 
               (SELECT SUM(dp.subtotal) FROM detail_pesanan dp WHERE dp.id_pesanan = p.id_pesanan) as hitung_total 
               FROM pesanan p 
               ORDER BY p.waktu_pesan DESC";
$stmt_list = $db->prepare($query_list);
$stmt_list->execute();
$daftar_pesanan = $stmt_list->fetchAll();

// 2. Query Detail Pesanan yang sedang dipilih
$query_pesanan = "SELECT * FROM pesanan WHERE kode_pesanan = :kode_pesanan LIMIT 1";
$stmt = $db->prepare($query_pesanan);
$stmt->bindParam(':kode_pesanan', $kode_pesanan);
$stmt->execute();
$pesanan_aktif = $stmt->fetch();

if (!$pesanan_aktif) {
    // Fallback jika tidak ditemukan
    $pesanan_aktif = [
        'id_pesanan' => 1,
        'kode_pesanan' => 'TRX-20261025-0142',
        'no_meja' => 8,
        'jenis_pesanan' => 'Dine In',
        'status' => 'Menunggu Pembayaran',
        'total_harga' => 110000.00,
        'waktu_pesan' => date('Y-m-d H:i:s')
    ];
}

$id_pesanan_aktif = $pesanan_aktif['id_pesanan'];
$no_meja = $pesanan_aktif['no_meja'] ?? 8;

// 3. Query Rincian Item Menu untuk Pesanan Aktif (`detail_pesanan` & `menu`)
$query_detail = "SELECT dp.*, m.nama_menu, m.deskripsi 
                 FROM detail_pesanan dp 
                 JOIN menu m ON dp.id_menu = m.id_menu 
                 WHERE dp.id_pesanan = :id_pesanan";
$stmt_detail = $db->prepare($query_detail);
$stmt_detail->bindParam(':id_pesanan', $id_pesanan_aktif);
$stmt_detail->execute();
$items_aktif = $stmt_detail->fetchAll();

// Hitung subtotal & total bayar
$subtotal = 0;
foreach ($items_aktif as $item) {
    $subtotal += $item['subtotal'];
}
if ($subtotal == 0) {
    $subtotal = $pesanan_aktif['total_harga'] > 0 ? $pesanan_aktif['total_harga'] : 110000.00;
}
$pajak_pb1 = $subtotal * 0.10;
$total_bersih = $subtotal + ($pesanan_aktif['jenis_pesanan'] == 'Dine In' ? 0 : 0); // Sesuai total harga tersimpan
$total_bersih = $pesanan_aktif['total_harga'] > 0 ? $pesanan_aktif['total_harga'] : 110000.00;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<meta content="web_dashboard" name="shell-type">
<title>Kasir POS — Maky Chicken</title>
<link href="https://fonts.googleapis.com" rel="preconnect">
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
<link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@700;800;900&amp;family=Plus+Jakarta+Sans:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<style>
  @layer base { html, body { margin: 0; padding: 0; } body { overscroll-behavior: none; } }
  ::-webkit-scrollbar { display: none; }
</style>
<script src="https://cdn.tailwindcss.com"></script>
<script id="tailwind-config">
tailwind.config={darkMode:"class",theme:{extend:{"colors":{"primary-fixed-dim":"#ffb3ac","on-background":"#2e1414","surface-container-lowest":"#ffffff","on-primary-container":"#ffc3bd","error-container":"#ffdad6","surface-container":"#ffe9e8","primary":"#8b000d","error":"#ba1a1a","blue-info":"#1F5FAE","green-status":"#1E8E4E","surface-container-highest":"#ffdad8","tertiary":"#004283","surface-container-low":"#fff0ef","on-tertiary-fixed-variant":"#00468b","yellow-accent":"#FFB21E","inverse-primary":"#ffb3ac","on-secondary-fixed":"#281800","on-surface-variant":"#5b403d","on-tertiary-fixed":"#001b3d","cream-light":"#FFFAF0","text-muted":"#6B4A45","tertiary-container":"#1559a8","on-tertiary":"#ffffff","on-tertiary-container":"#bbd2ff","on-error-container":"#93000a","on-surface":"#2e1414","on-primary-fixed-variant":"#93000f","red-light":"#FBE0DD","on-secondary":"#ffffff","surface-dim":"#ffcecd","surface-bright":"#fff8f7","red-brand":"#B3131B","on-primary":"#ffffff","tertiary-fixed":"#d6e3ff","red-dark":"#8E0F18","inverse-surface":"#ffedeb","on-primary-fixed":"#410003","blue-light":"#DCEBFA","surface":"#fff8f7","text-main":"#2A1010","primary-container":"#b3131b","surface-container-high":"#ffe1e0","cream-border":"#F2D49A","surface-variant":"#ffdad8","secondary-fixed":"#ffddb0","background":"#fff8f7","yellow-dark":"#F28C0F","on-secondary-fixed-variant":"#614000","surface-tint":"#ba1a1f","secondary-container":"#ffb21e","primary-fixed":"#ffdad6","cream-bg":"#FFF3DC","tertiary-fixed-dim":"#a9c7ff","secondary-fixed-dim":"#ffba44","on-error":"#ffffff","secondary":"#805600","outline":"#8f6f6c","inverse-surface":"#472827","outline-variant":"#e4beba","on-secondary-container":"#6b4800"},"borderRadius":{"DEFAULT":"1rem","lg":"2rem","xl":"3rem","full":"9999px"},"spacing":{"space-md":"1rem","gutter":"1rem","margin":"1rem","space-lg":"1.5rem","space-xs":"0.25rem","space-xl":"2rem","space-sm":"0.5rem"},"fontFamily":{"display-lg":["Epilogue"],"label-sm":["Plus Jakarta Sans"],"label-md":["Plus Jakarta Sans"],"label-lg":["Plus Jakarta Sans"],"headline-lg":["Epilogue"],"headline-sm":["Epilogue"],"headline-md":["Epilogue"],"body-sm":["Plus Jakarta Sans"],"body-md":["Plus Jakarta Sans"],"title-md":["Plus Jakarta Sans"],"display-lg-mobile":["Epilogue"],"body-lg":["Plus Jakarta Sans"]}}}}
</script>
</head>
<body class="bg-cream-bg font-body-md text-on-surface antialiased">
<!-- SIDEBAR NAVIGATION -->
<aside class="fixed left-0 top-0 h-full w-72 bg-primary z-50 flex flex-col justify-between shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
<div class="flex flex-col">
<div class="h-20 px-space-lg flex items-center gap-space-sm">
<div class="w-10 h-10 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container font-headline-md text-headline-md font-bold">M</div>
<div class="flex flex-col"><span class="font-headline-md text-headline-sm font-bold text-cream-light leading-none">MAKY CHICKEN</span><span class="font-label-sm text-label-sm text-primary-fixed-dim uppercase tracking-wider mt-1">Staff &amp; Kasir POS</span></div></div>
<div class="px-space-md py-space-sm"><span class="font-label-sm text-label-sm uppercase tracking-wider text-primary-fixed-dim px-space-sm">Menu Operasional</span></div>
<nav class="flex flex-col gap-space-xs px-space-md">
<a aria-current="page" class="flex items-center gap-space-sm px-space-md py-3 rounded-full transition-all duration-200 bg-secondary-container text-on-secondary-container font-bold shadow-md" href="#"><span class="material-symbols-outlined text-[20px]">point_of_sale</span><span>Antrean Kasir POS</span></a>
<a class="flex items-center gap-space-sm px-space-md py-3 rounded-full text-cream-light hover:bg-primary-container transition-all duration-200 font-label-lg text-label-lg" href="#"><span class="material-symbols-outlined text-[20px]">analytics</span><span>Dashboard Laporan</span></a>
<a class="flex items-center gap-space-sm px-space-md py-3 rounded-full text-cream-light hover:bg-primary-container transition-all duration-200 font-label-lg text-label-lg" href="#"><span class="material-symbols-outlined text-[20px]">restaurant_menu</span><span>Kelola Menu</span></a>
<a class="flex items-center gap-space-sm px-space-md py-3 rounded-full text-cream-light hover:bg-primary-container transition-all duration-200 font-label-lg text-label-lg" href="#"><span class="material-symbols-outlined text-[20px]">report_problem</span><span>Laporan Kendala</span></a>
</nav></div>
<div class="p-space-md"><div class="bg-red-dark rounded-DEFAULT p-space-md flex flex-col gap-space-xs"><div class="flex items-center gap-space-xs"><span class="w-2.5 h-2.5 rounded-full bg-green-status animate-pulse"></span><span class="font-label-sm text-label-sm text-cream-light font-bold">Terminal POS #01</span></div><span class="font-body-sm text-body-sm text-primary-fixed-dim">Mode Staff Aktif • Maky Express</span></div></div>
</aside>

<!-- MAIN WRAPPER -->
<div class="pl-72 min-h-screen flex flex-col">
<!-- TOPBAR -->
<header class="fixed top-0 left-72 right-0 h-20 bg-cream-light/95 backdrop-blur-xl z-40 shadow-[0_1px_8px_rgba(42,16,16,0.06)]">
<div class="h-20 w-full px-space-xl flex items-center justify-between">
<div class="flex items-center gap-space-lg">
<div class="flex items-center gap-space-sm bg-cream-bg px-space-md py-space-xs rounded-full border border-cream-border"><span class="material-symbols-outlined text-primary text-[20px]">badge</span><div class="flex flex-col"><span class="font-label-sm text-label-sm text-text-muted leading-none">Kasir Bertugas</span><span class="font-label-md text-label-md text-text-main font-bold leading-tight">Siti Rahmawati</span></div></div>
<div class="flex items-center gap-space-sm bg-cream-bg px-space-md py-space-xs rounded-full border border-cream-border"><span class="material-symbols-outlined text-secondary-container text-[20px]">wb_sunny</span><div class="flex flex-col"><span class="font-label-sm text-label-sm text-text-muted leading-none">Shift Kerja</span><span class="font-label-md text-label-md text-text-main font-bold leading-tight">Shift Pagi (08:00 - 16:00)</span></div></div></div>
<div class="flex items-center gap-space-md">
<div class="flex items-center gap-space-xs bg-cream-bg px-space-md py-space-xs rounded-full text-text-muted border border-cream-border"><span class="material-symbols-outlined text-green-status text-[18px]">schedule</span><span class="font-label-md text-label-md font-semibold text-text-main">Buka: 08:00 - 22:00 WIB</span></div>
<div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center"><span class="material-symbols-outlined text-on-primary text-[18px]">person</span></div></div></div>
</header>

<!-- CONTENT CONTAINER -->
<main class="w-full pt-20 bg-cream-bg flex-1 px-space-xl py-space-lg max-w-[1280px]">
<div class="flex flex-col w-full gap-space-lg">
<div class="flex flex-col lg:flex-row lg:items-center justify-between gap-space-md">
<div class="flex flex-col gap-space-xs">
<div class="inline-flex items-center gap-space-xs px-space-md py-1 rounded-full bg-primary text-cream-light w-fit shadow-sm">
<span class="material-symbols-outlined text-[16px]">point_of_sale</span>
<span class="font-label-sm text-label-sm uppercase tracking-wider">KASIR OPERASIONAL POS</span>
</div>
<h1 class="font-display-lg text-display-lg text-red-brand uppercase tracking-tight">ANTREAN PESANAN &amp; PEMBAYARAN KASIR</h1>
<p class="font-body-md text-body-md text-text-muted">Kelola verifikasi pembayaran tunai &amp; cetak struk pesanan meja real-time</p>
</div>
<div class="flex items-center flex-wrap gap-space-sm">
<div class="inline-flex items-center gap-space-xs px-space-md py-2 rounded-full bg-cream-light border border-cream-border text-text-main shadow-sm">
<span class="w-2.5 h-2.5 rounded-full bg-green-status animate-pulse"></span>
<span class="font-label-md text-label-md font-bold">Shift Pagi (Aktif)</span>
</div>
<div class="relative flex items-center min-w-[280px]">
<span class="material-symbols-outlined absolute left-4 text-text-muted text-[20px]">search</span>
<input class="w-full pl-11 pr-4 py-2.5 rounded-full bg-cream-light border border-cream-border font-body-md text-body-md text-text-main placeholder:text-text-muted focus:outline-none focus:border-red-brand shadow-sm transition-all" placeholder="Cari Meja / No. Pesanan..." type="text">
</div>
</div>
</div>

<!-- STATS CARDS -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-space-md">
<div class="flex items-center gap-space-md p-space-md bg-cream-light rounded-DEFAULT border border-cream-border shadow-[0_6px_16px_-4px_rgba(42,16,16,0.08)]">
<div class="w-12 h-12 rounded-full bg-cream-bg flex items-center justify-center text-primary shrink-0"><span class="material-symbols-outlined text-[24px]">receipt_long</span></div>
<div class="flex flex-col min-w-0">
<span class="font-label-sm text-label-sm uppercase tracking-wider text-text-muted truncate">Total Antrean</span>
<span class="font-headline-lg text-headline-lg text-text-main tabular-nums"><?php echo count($daftar_pesanan); ?> <span class="font-headline-sm text-headline-sm">Pesanan</span></span>
</div>
</div>
<div class="flex items-center gap-space-md p-space-md bg-cream-light rounded-DEFAULT border border-cream-border shadow-[0_6px_16px_-4px_rgba(42,16,16,0.08)]">
<div class="w-12 h-12 rounded-full bg-yellow-accent/20 flex items-center justify-center text-yellow-dark shrink-0"><span class="material-symbols-outlined text-[24px]">payments</span></div>
<div class="flex flex-col min-w-0">
<span class="font-label-sm text-label-sm uppercase tracking-wider text-text-muted truncate">Menunggu Tunai</span>
<span class="font-headline-lg text-headline-lg text-yellow-dark tabular-nums">2</span>
</div>
</div>
<div class="flex items-center gap-space-md p-space-md bg-cream-light rounded-DEFAULT border border-cream-border shadow-[0_6px_16px_-4px_rgba(42,16,16,0.08)]">
<div class="w-12 h-12 rounded-full bg-green-status/15 flex items-center justify-center text-green-status shrink-0"><span class="material-symbols-outlined text-[24px]">task_alt</span></div>
<div class="flex flex-col min-w-0">
<span class="font-label-sm text-label-sm uppercase tracking-wider text-text-muted truncate">Lunas Hari Ini</span>
<span class="font-headline-lg text-headline-lg text-green-status tabular-nums">18</span>
</div>
</div>
<div class="flex items-center gap-space-md p-space-md bg-cream-light rounded-DEFAULT border border-cream-border shadow-[0_6px_16px_-4px_rgba(42,16,16,0.08)]">
<div class="w-12 h-12 rounded-full bg-red-light flex items-center justify-center text-red-brand shrink-0"><span class="material-symbols-outlined text-[24px]">account_balance_wallet</span></div>
<div class="flex flex-col min-w-0">
<span class="font-label-sm text-label-sm uppercase tracking-wider text-text-muted truncate">Total Kas Masuk</span>
<span class="font-headline-lg text-headline-lg text-red-brand tabular-nums truncate">Rp2.450.000</span>
</div>
</div>
</div>

<!-- GRID CONTENT -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg items-start">
<!-- LEFT: DAFTAR ANTREAN DINAMIS -->
<div class="lg:col-span-5 flex flex-col gap-space-md">
<div class="flex items-center justify-between px-space-xs">
<div class="flex items-center gap-space-xs">
<span class="material-symbols-outlined text-primary text-[20px]">format_list_bulleted</span>
<h2 class="font-headline-sm text-headline-sm text-text-main">Daftar Antrean Kasir</h2>
</div>
<span class="font-label-sm text-label-sm uppercase tracking-wider px-2 py-0.5 rounded-full bg-cream-border text-text-muted font-bold"><?php echo count($daftar_pesanan); ?> Antrean</span>
</div>
<div class="flex flex-col gap-space-sm" id="queueList">
<?php foreach ($daftar_pesanan as $p): 
    $isActive = ($p['kode_pesanan'] === $pesanan_aktif['kode_pesanan']);
?>
<a href="?kode=<?php echo $p['kode_pesanan']; ?>" class="block no-underline">
<div class="relative flex flex-col gap-space-sm p-space-md bg-cream-light rounded-lg <?php echo $isActive ? 'border-2 border-red-brand shadow-[0_0_0_2px_#B3131B,0_8px_20px_-2px_rgba(179,19,27,0.15)]' : 'border border-cream-border shadow-[0_6px_16px_-4px_rgba(42,16,16,0.08)] hover:bg-surface-container-low'; ?> transition-all cursor-pointer">
<div class="flex items-start justify-between gap-space-xs">
<div class="flex flex-col">
<div class="flex items-center gap-space-xs">
<span class="font-headline-sm text-headline-sm <?php echo $isActive ? 'text-red-brand' : 'text-text-main'; ?>">#<?php echo htmlspecialchars($p['kode_pesanan']); ?></span>
<span class="font-body-sm text-body-sm text-text-muted">• <?php echo date('H:i', strtotime($p['waktu_pesan'])); ?> WIB</span>
</div>
<span class="font-title-md text-title-md text-text-main">Meja <?php echo htmlspecialchars($p['no_meja'] ?? '-'); ?> <span class="font-body-sm text-body-sm text-text-muted">(<?php echo htmlspecialchars($p['jenis_pesanan']); ?>)</span></span>
</div>
<span class="px-space-sm py-1 rounded-full <?php echo $p['status'] == 'Selesai' ? 'bg-green-status/15 text-green-status' : 'bg-cream-bg border border-cream-border text-yellow-dark'; ?> font-label-sm text-label-sm font-bold flex items-center gap-1">
<?php echo htmlspecialchars($p['status']); ?>
</span>
</div>
<div class="w-full border-t border-dashed border-cream-border my-space-xs"></div>
<div class="flex items-center justify-between pt-space-xs">
<span class="font-label-md text-label-md uppercase tracking-wider text-text-muted">Total Pembayaran</span>
<span class="font-headline-md text-headline-md text-red-brand tabular-nums">Rp<?php echo number_format($p['total_harga'], 0, ',', '.'); ?></span>
</div>
</div>
</a>
<?php endforeach; ?>
</div>
</div>

<!-- RIGHT: DETAIL PEMBAYARAN & VERIFIKASI TUNAI AKTIF -->
<div class="lg:col-span-7 flex flex-col gap-space-md">
<div class="bg-cream-light rounded-lg border-2 border-cream-border p-space-xl shadow-[0_8px_24px_rgba(42,16,16,0.12)] flex flex-col gap-space-lg">
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-space-sm border-b border-cream-border pb-space-md">
<div class="flex items-center gap-space-sm">
<div class="w-10 h-10 rounded-full bg-red-brand text-cream-light flex items-center justify-center">
<span class="material-symbols-outlined text-[20px]">attach_money</span>
</div>
<div>
<span class="font-label-sm text-label-sm text-text-muted uppercase tracking-wider">Verifikasi Transaksi #<?php echo htmlspecialchars($pesanan_aktif['kode_pesanan']); ?></span>
<h3 class="font-headline-md text-headline-md text-text-main uppercase">MODE BAYAR TUNAI (CASH)</h3>
</div>
</div>
<div class="flex items-center gap-space-xs bg-cream-bg px-space-md py-1.5 rounded-full border border-cream-border self-start sm:self-auto">
<span class="material-symbols-outlined text-red-brand text-[18px]">table_restaurant</span>
<span class="font-label-md text-label-md text-text-main font-bold">Meja <?php echo htmlspecialchars($no_meja); ?></span>
</div>
</div>

<!-- TAB METODE PEMBAYARAN -->
<div class="grid grid-cols-3 gap-space-xs p-1 bg-cream-bg rounded-full border border-cream-border">
<button class="py-2.5 px-space-md rounded-full bg-red-brand text-cream-light font-label-lg text-label-lg font-bold shadow-sm flex items-center justify-center gap-space-xs transition-all" type="button">
<span class="material-symbols-outlined text-[18px]">payments</span><span>Tunai (Aktif)</span>
</button>
<button class="py-2.5 px-space-md rounded-full text-text-main hover:bg-cream-light font-label-lg text-label-lg font-bold flex items-center justify-center gap-space-xs transition-all" type="button">
<span class="material-symbols-outlined text-[18px]">qr_code_2</span><span>QRIS Terverifikasi</span>
</button>
<button class="py-2.5 px-space-md rounded-full text-text-main hover:bg-cream-light font-label-lg text-label-lg font-bold flex items-center justify-center gap-space-xs transition-all" type="button">
<span class="material-symbols-outlined text-[18px]">credit_card</span><span>Debit EDC</span>
</button>
</div>

<!-- TOTAL TAGIHAN BERSIH -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between p-space-lg rounded-DEFAULT bg-cream-bg border border-cream-border">
<div class="flex flex-col">
<span class="font-label-sm text-label-sm uppercase tracking-wider text-text-muted">Total Tagihan Bersih</span>
<span class="font-body-sm text-body-sm text-text-muted">Termasuk item pesanan &amp; rincian menu</span>
</div>
<div class="text-right mt-2 sm:mt-0">
<span class="font-display-lg text-display-lg text-red-brand tabular-nums tracking-tight" data-value="<?php echo $total_bersih; ?>" id="billTotal">Rp<?php echo number_format($total_bersih, 0, ',', '.'); ?></span>
</div>
</div>

<!-- INPUT NOMINAL UANG DITERIMA -->
<div class="flex flex-col gap-space-sm">
<label class="font-label-lg text-label-lg text-text-main font-bold flex items-center justify-between" for="cashReceived">
<span>Nominal Uang Diterima Pelanggan (Rp)*</span>
<span class="font-body-sm text-body-sm text-text-muted">Ketik langsung / gunakan tombol cepat</span>
</label>
<div class="relative flex items-center">
<span class="absolute left-5 font-headline-md text-headline-md text-text-muted select-none">Rp</span>
<input class="w-full pl-16 pr-5 py-3.5 rounded-DEFAULT bg-cream-light border-2 border-cream-border font-headline-lg text-headline-lg text-text-main tabular-nums focus:outline-none focus:border-red-brand shadow-inner transition-all" id="cashReceived" type="text" value="<?php echo number_format($total_bersih, 0, ',', '.'); ?>">
</div>
</div>

<!-- TOMBOL CEPAT NOMINAL -->
<div class="flex flex-col gap-space-xs">
<span class="font-label-sm text-label-sm uppercase tracking-wider text-text-muted">Pilihan Nominal Cepat:</span>
<div class="grid grid-cols-2 sm:grid-cols-4 gap-space-sm">
<button class="quick-chip py-3 px-space-md rounded-full bg-red-brand text-cream-light border border-red-brand font-label-lg text-label-lg font-bold tabular-nums transition-all text-center shadow-sm" onclick="setNominal(<?php echo $total_bersih; ?>)" type="button" aria-pressed="true">
Rp<?php echo number_format($total_bersih, 0, ',', '.'); ?> <span class="block font-label-sm text-label-sm font-normal text-cream-light">(Uang Pas)</span>
</button>
<button class="quick-chip py-3 px-space-md rounded-full bg-cream-bg border border-cream-border hover:border-red-brand text-text-main font-label-lg text-label-lg font-bold tabular-nums transition-all text-center" onclick="setNominal(<?php echo $total_bersih + 10000; ?>)" type="button">
Rp<?php echo number_format($total_bersih + 10000, 0, ',', '.'); ?>
</button>
<button class="quick-chip py-3 px-space-md rounded-full bg-cream-bg border border-cream-border hover:border-red-brand text-text-main font-label-lg text-label-lg font-bold tabular-nums transition-all text-center" onclick="setNominal(<?php echo $total_bersih + 40000; ?>)" type="button">
Rp<?php echo number_format($total_bersih + 40000, 0, ',', '.'); ?>
</button>
<button class="quick-chip py-3 px-space-md rounded-full bg-cream-bg border border-cream-border hover:border-red-brand text-text-main font-label-lg text-label-lg font-bold tabular-nums transition-all text-center" onclick="setNominal(<?php echo $total_bersih + 90000; ?>)" type="button">
Rp<?php echo number_format($total_bersih + 90000, 0, ',', '.'); ?>
</button>
</div>
</div>

<!-- KEMBALIAN -->
<div class="flex flex-col p-space-lg rounded-DEFAULT bg-cream-bg border-2 border-cream-border shadow-sm">
<div class="flex items-center justify-between pb-space-xs border-b border-dashed border-cream-border">
<span class="font-label-md text-label-md uppercase tracking-wider text-text-muted font-bold">UANG KEMBALIAN PELANGGAN</span>
<span class="px-space-sm py-0.5 rounded-full bg-green-status/15 text-green-status font-label-sm text-label-sm font-bold flex items-center gap-1">
<span class="material-symbols-outlined text-[14px]">check_circle</span> Kembalian Siap Diserahkan
</span>
</div>
<div class="flex flex-col sm:flex-row sm:items-center justify-between pt-space-sm gap-space-xs">
<span class="font-body-md text-body-md text-text-muted">Kembalian pecahan uang fisik:</span>
<span class="font-display-lg text-display-lg text-red-brand tabular-nums tracking-tight" id="changeDisplay">Rp0</span>
</div>
</div>

<!-- AKSI TOMBOL -->
<div class="flex flex-col gap-space-sm pt-space-xs">
<button class="w-full h-14 rounded-full bg-red-brand hover:bg-red-dark text-cream-light font-headline-sm text-headline-sm uppercase tracking-wide shadow-md flex items-center justify-center gap-space-sm transition-all transform active:scale-[0.99]" onclick="completePayment()" type="button">
<span class="material-symbols-outlined text-[24px]">verified</span>
<span>VERIFIKASI &amp; SELESAIKAN PEMBAYARAN</span>
</button>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-space-sm">
<button class="h-12 rounded-full bg-blue-info text-cream-light hover:bg-tertiary transition-all font-label-lg text-label-lg font-bold flex items-center justify-center gap-space-xs shadow-sm" onclick="printReceipt()" type="button">
<span class="material-symbols-outlined text-[20px]">receipt</span>
<span>CETAK STRUK (UC-12)</span>
</button>
<button class="h-12 rounded-full border border-red-brand text-red-brand hover:bg-red-light transition-all font-label-lg text-label-lg font-bold flex items-center justify-center gap-space-xs" type="button">
<span class="material-symbols-outlined text-[20px]">cancel</span>
<span>BATALKAN TRANSAKSI</span>
</button>
</div>
</div>
</div>
</div>
</div>
</div>
</main>
</div>

<script>
  const totalBill = <?php echo $total_bersih; ?>;

  function formatRupiah(num) {
    return 'Rp' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  }

  function setNominal(amount) {
    const input = document.getElementById('cashReceived');
    input.value = amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    
    const change = Math.max(0, amount - totalBill);
    document.getElementById('changeDisplay').innerText = formatRupiah(change);

    const buttons = document.querySelectorAll('.quick-chip');
    buttons.forEach(btn => {
      btn.setAttribute('aria-pressed', 'false');
      btn.classList.remove('bg-red-brand', 'text-cream-light', 'border-red-brand', 'shadow-sm');
      btn.classList.add('bg-cream-bg', 'text-text-main', 'border-cream-border');
      
      if (btn.innerText.includes(amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."))) {
        btn.setAttribute('aria-pressed', 'true');
        btn.classList.remove('bg-cream-bg', 'text-text-main', 'border-cream-border');
        btn.classList.add('bg-red-brand', 'text-cream-light', 'border-red-brand', 'shadow-sm');
      }
    });
  }

  document.getElementById('cashReceived').addEventListener('input', function(e) {
    let raw = this.value.replace(/[^0-9]/g, '');
    let num = parseInt(raw, 10) || 0;
    this.value = num ? num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") : '';
    
    let change = Math.max(0, num - totalBill);
    document.getElementById('changeDisplay').innerText = formatRupiah(change);
  });

  function completePayment() {
    alert('Pembayaran pesanan #<?php echo htmlspecialchars($pesanan_aktif['kode_pesanan']); ?> sebesar ' + formatRupiah(totalBill) + ' berhasil diverifikasi.');
  }

  function printReceipt() {
    alert('Mencetak struk pesanan #<?php echo htmlspecialchars($pesanan_aktif['kode_pesanan']); ?> via printer kasir...');
  }
</script>
</body>
</html>