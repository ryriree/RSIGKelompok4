<?php
// Include koneksi database
require_once 'database.php';

$database = new Database();
$db = $database->getConnection();

// Ambil kode pesanan atau ID pesanan dari parameter GET (Default: TRX-20261025-0142)
$kode_pesanan = isset($_GET['kode']) ? $_GET['kode'] : 'TRX-20261025-0142';

// 1. Query Data Pesanan & Pembayaran dari tabel `pesanan` dan `pembayaran`
$query_pesanan = "SELECT p.*, pb.metode, pb.total, pb.uang_diterima, pb.kembalian, pb.status as status_bayar, pb.waktu_bayar 
                  FROM pesanan p 
                  LEFT JOIN pembayaran pb ON p.id_pesanan = pb.id_pesanan 
                  WHERE p.kode_pesanan = :kode_pesanan LIMIT 1";
$stmt = $db->prepare($query_pesanan);
$stmt->bindParam(':kode_pesanan', $kode_pesanan);
$stmt->execute();
$data_transaksi = $stmt->fetch();

if (!$data_transaksi) {
    // Fallback data dummy jika tidak ditemukan
    $data_transaksi = [
        'id_pesanan' => 1,
        'kode_pesanan' => 'TRX-20261025-0142',
        'no_meja' => 8,
        'jenis_pesanan' => 'Dine In',
        'status' => 'Selesai',
        'total_harga' => 110000.00,
        'waktu_pesan' => '2026-10-25 14:32:00',
        'metode' => 'Tunai',
        'uang_diterima' => 150000.00,
        'kembalian' => 40000.00,
        'status_bayar' => 'Lunas',
        'waktu_bayar' => '2026-10-25 14:35:00'
    ];
}

$id_pesanan = $data_transaksi['id_pesanan'];
$no_meja = $data_transaksi['no_meja'] ?? 8;

// 2. Query Detail Item Menu dari tabel `detail_pesanan` dan `menu`
$query_detail = "SELECT dp.*, m.nama_menu, m.deskripsi, m.harga 
                 FROM detail_pesanan dp 
                 JOIN menu m ON dp.id_menu = m.id_menu 
                 WHERE dp.id_pesanan = :id_pesanan";
$stmt_detail = $db->prepare($query_detail);
$stmt_detail->bindParam(':id_pesanan', $id_pesanan);
$stmt_detail->execute();
$items = $stmt_detail->fetchAll();

// Hitung subtotal & pajak PB1 (10%)
$subtotal = 0;
foreach ($items as $item) {
    $subtotal += $item['subtotal'];
}
if ($subtotal == 0) {
    $subtotal = 100000.00;
}
$pajak_pb1 = $subtotal * 0.10;
$total_bayar = $data_transaksi['total_harga'] > 0 ? $data_transaksi['total_harga'] : ($subtotal + $pajak_pb1);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<meta content="web_blank" name="shell-type">
<title>Struk Pembayaran — #<?php echo htmlspecialchars($data_transaksi['kode_pesanan']); ?> — Maky Chicken</title>
<link href="https://fonts.googleapis.com" rel="preconnect">
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
<link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@700;800;900&amp;family=Plus+Jakarta+Sans:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<style>
  @layer base { html, body { margin: 0; padding: 0; } body { overscroll-behavior: none; } main>:first-child { margin-top: 0 !important; } main>:last-child { margin-bottom: 0 !important; } }
  ::-webkit-scrollbar { display: none; }
</style>
<script src="https://cdn.tailwindcss.com"></script>
<script id="tailwind-config">
tailwind.config={darkMode:"class",theme:{extend:{"colors":{"primary-fixed-dim":"#ffb3ac","on-background":"#2e1414","surface-container-lowest":"#ffffff","on-primary-container":"#ffc3bd","error-container":"#ffdad6","surface-container":"#ffe9e8","primary":"#8b000d","error":"#ba1a1a","blue-info":"#1F5FAE","green-status":"#1E8E4E","surface-container-highest":"#ffdad8","tertiary":"#004283","surface-container-low":"#fff0ef","on-tertiary-fixed-variant":"#00468b","yellow-accent":"#FFB21E","inverse-primary":"#ffb3ac","on-secondary-fixed":"#281800","on-surface-variant":"#5b403d","on-tertiary-fixed":"#001b3d","cream-light":"#FFFAF0","text-muted":"#6B4A45","tertiary-container":"#1559a8","on-tertiary":"#ffffff","on-tertiary-container":"#bbd2ff","on-error-container":"#93000a","on-surface":"#2e1414","on-primary-fixed-variant":"#93000f","red-light":"#FBE0DD","on-secondary":"#ffffff","surface-dim":"#ffcecd","surface-bright":"#fff8f7","red-brand":"#B3131B","on-primary":"#ffffff","tertiary-fixed":"#d6e3ff","red-dark":"#8E0F18","inverse-on-surface":"#ffedeb","on-primary-fixed":"#410003","blue-light":"#DCEBFA","surface":"#fff8f7","text-main":"#2A1010","primary-container":"#b3131b","surface-container-high":"#ffe1e0","cream-border":"#F2D49A","surface-variant":"#ffdad8","secondary-fixed":"#ffddb0","background":"#fff8f7","yellow-dark":"#F28C0F","on-secondary-fixed-variant":"#614000","surface-tint":"#ba1a1f","secondary-container":"#ffb21e","primary-fixed":"#ffdad6","cream-bg":"#FFF3DC","tertiary-fixed-dim":"#a9c7ff","secondary-fixed-dim":"#ffba44","on-error":"#ffffff","secondary":"#805600","outline":"#8f6f6c","inverse-surface":"#472827","outline-variant":"#e4beba","on-secondary-container":"#6b4800"},"borderRadius":{"DEFAULT":"1rem","lg":"2rem","xl":"3rem","full":"9999px"},"spacing":{"space-md":"1rem","gutter":"1rem","margin":"1rem","space-lg":"1.5rem","space-xs":"0.25rem","space-xl":"2rem","space-sm":"0.5rem"},"fontFamily":{"display-lg":["Epilogue"],"label-sm":["Plus Jakarta Sans"],"label-md":["Plus Jakarta Sans"],"label-lg":["Plus Jakarta Sans"],"headline-lg":["Epilogue"],"headline-sm":["Epilogue"],"headline-md":["Epilogue"],"body-sm":["Plus Jakarta Sans"],"body-md":["Plus Jakarta Sans"],"title-md":["Plus Jakarta Sans"],"display-lg-mobile":["Epilogue"],"body-lg":["Plus Jakarta Sans"]}}}}
</script>
</head>
<body class="bg-cream-bg font-body-md text-on-surface antialiased min-h-screen flex items-center justify-center p-space-md">
<main class="w-full flex items-center justify-center">
<div class="flex flex-col w-full items-center justify-center py-6 px-4">
<!-- Top Navigation & Context Bar -->
<header class="w-full max-w-[560px] flex items-center justify-between gap-3 mb-6">
<a class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-cream-light text-red-brand font-label-lg shadow-sm hover:bg-surface-container transition-colors" href="javascript:history.back()">
<span class="material-symbols-outlined text-[18px]">arrow_back</span>
<span>Kembali</span>
</a>
<div class="flex items-center gap-2">
<span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-cream-light text-text-main font-label-md shadow-sm">
<span class="w-2 h-2 rounded-full bg-green-status animate-pulse"></span>
Meja <?php echo htmlspecialchars($no_meja); ?> · Lt 1
</span>
<span class="hidden sm:inline-flex px-3 py-1.5 rounded-full bg-cream-light text-text-muted font-label-md shadow-sm">
<?php echo date('d M Y', strtotime($data_transaksi['waktu_pesan'])); ?>
</span>
</div>
</header>

<!-- Digital Receipt Card -->
<section class="w-full max-w-[560px] bg-cream-light rounded-lg shadow-xl relative overflow-hidden transition-all duration-300">
<div class="w-full h-3 bg-red-brand"></div>
<div class="p-6 sm:p-8 flex flex-col gap-6">
<!-- Receipt Header -->
<div class="flex flex-col items-center text-center">
<div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-red-brand text-cream-light shadow-md mb-2">
<span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">local_fire_department</span>
</div>
<h1 class="font-headline-lg text-headline-lg text-red-brand tracking-tight font-black uppercase">
MAKY CHICKEN
</h1>
<p class="font-label-md text-yellow-dark tracking-wide font-bold mt-0.5">
Hangat, Ramai, Pedas Gurih
</p>
<p class="font-body-sm text-text-muted mt-2 max-w-xs leading-relaxed">
Jl. Pemuda No. 88, Central Foodcourt, Lantai 1<br>
Telp: (021) 8892-1001 · makychicken.id
</p>
</div>

<!-- Divider Notch -->
<div class="relative w-full flex items-center justify-center my-1">
<div class="w-full border-b-2 border-dashed border-cream-border"></div>
<div class="absolute -left-10 w-6 h-6 bg-cream-bg rounded-full shadow-inner"></div>
<div class="absolute -right-10 w-6 h-6 bg-cream-bg rounded-full shadow-inner"></div>
</div>

<!-- Transaction Status & Meta -->
<div class="bg-surface-bright p-4 rounded-xl flex flex-col gap-3 shadow-sm">
<div class="flex items-center justify-between">
<div class="flex flex-col">
<span class="font-label-sm text-text-muted uppercase tracking-wider">No. Invoice</span>
<span class="font-title-md text-text-main font-bold tracking-tight">#INV-<?php echo htmlspecialchars($data_transaksi['kode_pesanan']); ?></span>
</div>
<span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-green-status/10 text-green-status font-label-md font-bold">
<span class="material-symbols-outlined text-base" style="font-variation-settings: 'FILL' 1;">check_circle</span>
LUNAS
</span>
</div>
<div class="grid grid-cols-2 gap-2 pt-2 border-t border-dashed border-cream-border text-left">
<div>
<span class="block font-label-sm text-text-muted">Waktu Transaksi</span>
<span class="block font-body-sm text-text-main font-semibold"><?php echo date('d/m/Y, H:i', strtotime($data_transaksi['waktu_bayar'] ?? $data_transaksi['waktu_pesan'])); ?> WIB</span>
</div>
<div>
<span class="block font-label-sm text-text-muted">Kasir Bertugas</span>
<span class="block font-body-sm text-text-main font-semibold">Siti Rahmawati (POS 01)</span>
</div>
<div>
<span class="block font-label-sm text-text-muted">Tipe Pesanan</span>
<span class="block font-body-sm text-text-main font-semibold"><?php echo htmlspecialchars($data_transaksi['jenis_pesanan']); ?></span>
</div>
<div>
<span class="block font-label-sm text-text-muted">Nomor Meja</span>
<span class="block font-body-sm text-red-brand font-bold">Meja <?php echo htmlspecialchars($no_meja); ?> (Lt. 1)</span>
</div>
</div>
</div>

<!-- Items Section -->
<div class="flex flex-col gap-4">
<div class="flex items-center justify-between pb-2 border-b border-cream-border">
<span class="font-label-md text-text-muted uppercase tracking-wider">Rincian Item</span>
<span class="font-label-md text-text-muted uppercase tracking-wider">Subtotal</span>
</div>
<?php foreach ($items as $item): ?>
<div class="flex items-start justify-between gap-3">
<div class="flex gap-2.5 items-start">
<span class="inline-flex items-center justify-center w-6 h-6 rounded-md bg-cream-bg text-red-brand font-label-md font-bold shrink-0 mt-0.5"><?php echo $item['jumlah']; ?>x</span>
<div class="flex flex-col">
<span class="font-body-md text-text-main font-bold"><?php echo htmlspecialchars($item['nama_menu']); ?></span>
<span class="font-body-sm text-text-muted"><?php echo htmlspecialchars($item['catatan'] ?? $item['deskripsi']); ?> (@ Rp<?php echo number_format($item['harga'] ?? ($item['subtotal'] / $item['jumlah']), 0, ',', '.'); ?>)</span>
</div>
</div>
<span class="font-body-md text-text-main font-bold tabular-nums text-right whitespace-nowrap">Rp<?php echo number_format($item['subtotal'], 0, ',', '.'); ?></span>
</div>
<?php endforeach; ?>
</div>

<div class="w-full border-b-2 border-dashed border-cream-border"></div>

<!-- Financial Calculation -->
<div class="flex flex-col gap-2">
<div class="flex justify-between items-center text-text-muted font-body-md">
<span>Subtotal Makanan &amp; Minuman</span>
<span class="tabular-nums font-semibold text-text-main">Rp<?php echo number_format($subtotal, 0, ',', '.'); ?></span>
</div>
<div class="flex justify-between items-center text-text-muted font-body-md">
<span>PB1 Pajak Restoran (10%)</span>
<span class="tabular-nums font-semibold text-text-main">Rp<?php echo number_format($pajak_pb1, 0, ',', '.'); ?></span>
</div>
<div class="flex justify-between items-center text-text-muted font-body-md">
<div class="flex items-center gap-1.5">
<span>Biaya Layanan Meja</span>
<span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-bold bg-green-status/10 text-green-status uppercase">Gratis</span>
</div>
<span class="tabular-nums font-semibold text-text-main">Rp0</span>
</div>
<div class="mt-3 p-4 rounded-xl bg-surface-container flex items-center justify-between">
<div class="flex flex-col">
<span class="font-label-sm text-red-dark uppercase tracking-wider font-bold">Total Pembayaran</span>
<span class="font-body-sm text-text-muted">Sudah termasuk pajak</span>
</div>
<span class="font-headline-lg text-[32px] leading-tight text-red-brand font-black tabular-nums tracking-tight">
Rp<?php echo number_format($total_bayar, 0, ',', '.'); ?>
</span>
</div>
</div>

<!-- Payment Breakdown -->
<div class="bg-surface-bright p-3.5 rounded-xl border border-dashed border-cream-border flex flex-col gap-2 text-sm">
<div class="flex justify-between items-center">
<span class="font-body-sm text-text-muted">Metode Pembayaran:</span>
<span class="font-title-md text-text-main font-bold flex items-center gap-1.5">
<span class="material-symbols-outlined text-base text-yellow-dark" style="font-variation-settings: 'FILL' 1;">payments</span>
<?php echo htmlspecialchars($data_transaksi['metode'] ?? 'Tunai'); ?>
</span>
</div>
<div class="flex justify-between items-center">
<span class="font-body-sm text-text-muted">Uang Diterima:</span>
<span class="font-body-md font-semibold text-text-main tabular-nums">Rp<?php echo number_format($data_transaksi['uang_diterima'] ?? $total_bayar, 0, ',', '.'); ?></span>
</div>
<div class="flex justify-between items-center pt-1.5 border-t border-cream-border">
<span class="font-body-sm text-text-muted font-bold">Kembalian:</span>
<span class="font-body-md font-black text-green-status tabular-nums">Rp<?php echo number_format($data_transaksi['kembalian'] ?? 0, 0, ',', '.'); ?></span>
</div>
</div>

<!-- Barcode & Footer -->
<div class="flex flex-col items-center justify-center pt-2 gap-3">
<div class="w-full flex flex-col items-center gap-1">
<svg class="w-64 h-12 text-text-main" fill="currentColor" viewBox="0 0 240 40" xmlns="http://www.w3.org/2000/svg">
<rect height="40" width="3" x="0" y="0"></rect><rect height="40" width="2" x="5" y="0"></rect><rect height="40" width="5" x="10" y="0"></rect><rect height="40" width="2" x="18" y="0"></rect><rect height="40" width="4" x="23" y="0"></rect><rect height="40" width="1" x="30" y="0"></rect><rect height="40" width="6" x="34" y="0"></rect><rect height="40" width="2" x="43" y="0"></rect><rect height="40" width="3" x="48" y="0"></rect><rect height="40" width="5" x="54" y="0"></rect><rect height="40" width="2" x="62" y="0"></rect><rect height="40" width="4" x="67" y="0"></rect><rect height="40" width="3" x="74" y="0"></rect><rect height="40" width="1" x="80" y="0"></rect><rect height="40" width="5" x="84" y="0"></rect><rect height="40" width="3" x="92" y="0"></rect><rect height="40" width="2" x="98" y="0"></rect><rect height="40" width="4" x="103" y="0"></rect><rect height="40" width="6" x="110" y="0"></rect><rect height="40" width="2" x="119" y="0"></rect><rect height="40" width="4" x="124" y="0"></rect><rect height="40" width="1" x="131" y="0"></rect><rect height="40" width="5" x="135" y="0"></rect><rect height="40" width="3" x="143" y="0"></rect><rect height="40" width="2" x="149" y="0"></rect><rect height="40" width="5" x="154" y="0"></rect><rect height="40" width="1" x="162" y="0"></rect><rect height="40" width="4" x="166" y="0"></rect><rect height="40" width="3" x="173" y="0"></rect><rect height="40" width="2" x="179" y="0"></rect><rect height="40" width="5" x="184" y="0"></rect><rect height="40" width="2" x="192" y="0"></rect><rect height="40" width="4" x="197" y="0"></rect><rect height="40" width="3" x="204" y="0"></rect><rect height="40" width="5" x="210" y="0"></rect><rect height="40" width="2" x="218" y="0"></rect><rect height="40" width="4" x="223" y="0"></rect><rect height="40" width="2" x="230" y="0"></rect><rect height="40" width="5" x="235" y="0"></rect>
</svg>
<span class="font-body-sm text-text-muted tracking-widest text-[11px]"><?php echo htmlspecialchars($data_transaksi['kode_pesanan']); ?></span>
</div>
<p class="font-body-sm text-text-muted text-center max-w-xs mt-2">
Terima kasih atas kunjungan Anda di Maky Chicken!<br>
<span class="font-semibold text-text-main">Simpan struk ini sebagai bukti pembayaran sah.</span>
</p>
</div>
</div>
<!-- Thermal Edge Bottom -->
<div class="w-full flex h-3 overflow-hidden bg-cream-light">
<svg class="w-full h-3 text-cream-bg" fill="currentColor" preserveAspectRatio="none" viewBox="0 0 100 10">
<polygon points="0,10 5,0 10,10 15,0 20,10 25,0 30,10 35,0 40,10 45,0 50,10 55,0 60,10 65,0 70,10 75,0 80,10 85,0 90,10 95,0 100,10"></polygon>
</svg>
</div>
</section>

<!-- Action Bar -->
<footer class="w-full max-w-[560px] flex flex-col gap-3 mt-6">
<button class="w-full h-[52px] rounded-full bg-red-brand hover:bg-red-dark text-cream-light font-title-md flex items-center justify-center gap-2 shadow-lg hover:shadow-xl transition-all duration-200 active:scale-[0.99] cursor-pointer" onclick="handleDownload()">
<span class="material-symbols-outlined text-2xl">download</span>
<span>UNDUH STRUK DIGITAL (PDF)</span>
</button>
<div class="grid grid-cols-2 gap-3">
<button class="h-12 rounded-full bg-blue-info/10 text-blue-info hover:bg-blue-info/20 font-label-lg flex items-center justify-center gap-2 transition-all cursor-pointer" onclick="handleShareWA()">
<span class="material-symbols-outlined text-xl">share</span>
<span>Kirim ke WhatsApp</span>
</button>
<button class="h-12 rounded-full bg-cream-light text-red-brand hover:bg-surface-container font-label-lg flex items-center justify-center gap-2 shadow-sm transition-all cursor-pointer" onclick="window.print()">
<span class="material-symbols-outlined text-xl">print</span>
<span>Cetak Thermal</span>
</button>
</div>
</footer>

<!-- Toast -->
<div class="fixed bottom-6 z-50 transform translate-y-20 opacity-0 transition-all duration-300 pointer-events-none px-4 py-3 rounded-full bg-inverse-surface text-inverse-on-surface font-label-md flex items-center gap-2 shadow-lg" id="toast">
<span class="material-symbols-outlined text-green-status text-lg">check_circle</span>
<span id="toast-text">Struk digital siap diunduh</span>
</div>
</div>
</main>
<script>
  function showToast(message) {
    const toast = document.getElementById('toast');
    const toastText = document.getElementById('toast-text');
    toastText.textContent = message;
    toast.classList.remove('translate-y-20', 'opacity-0', 'pointer-events-none');
    toast.classList.add('translate-y-0', 'opacity-100');
    setTimeout(() => {
      toast.classList.add('translate-y-20', 'opacity-0', 'pointer-events-none');
      toast.classList.remove('translate-y-0', 'opacity-100');
    }, 2800);
  }

  function handleDownload() {
    showToast('Mengunduh Invoice PDF #<?php echo htmlspecialchars($data_transaksi['kode_pesanan']); ?>...');
  }

  function handleShareWA() {
    showToast('Tautan struk digital disalin untuk WhatsApp!');
  }
</script>
</body>
</html>