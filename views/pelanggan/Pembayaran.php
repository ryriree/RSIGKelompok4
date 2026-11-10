<?php
// Include koneksi database
require_once 'database.php';

$database = new Database();
$db = $database->getConnection();

// Ambil id_pesanan atau kode_pesanan dari URL (Contoh default: 1 atau #MC-8821)
// Dalam implementasi nyata, ini bisa dikirim via GET parameter misal: pembayaran.php?kode=TRX-20261025-0142
$kode_pesanan = isset($_GET['kode']) ? $_GET['kode'] : 'TRX-20261025-0142';

// 1. Query Data Pesanan & Meja dari tabel `pesanan`
$query_pesanan = "SELECT * FROM pesanan WHERE kode_pesanan = :kode_pesanan LIMIT 1";
$stmt = $db->prepare($query_pesanan);
$stmt->bindParam(':kode_pesanan', $kode_pesanan);
$stmt->execute();
$pesanan = $stmt->fetch();

if (!$pesanan) {
    // Fallback jika data tidak ditemukan, gunakan data dummy pertama
    $pesanan = [
        'id_pesanan' => 1,
        'kode_pesanan' => 'TRX-20261025-0142',
        'no_meja' => 8,
        'jenis_pesanan' => 'Dine In',
        'status' => 'Menunggu Pembayaran',
        'total_harga' => 110000.00
    ];
}

$id_pesanan = $pesanan['id_pesanan'];
$no_meja = $pesanan['no_meja'] ?? 8;
$status_pesanan = $pesanan['status'];

// 2. Query Detail Item Pesanan dari tabel `detail_pesanan` dan `menu`
$query_detail = "SELECT dp.*, m.nama_menu, m.deskripsi 
                 FROM detail_pesanan dp 
                 JOIN menu m ON dp.id_menu = m.id_menu 
                 WHERE dp.id_pesanan = :id_pesanan";
$stmt_detail = $db->prepare($query_detail);
$stmt_detail->bindParam(':id_pesanan', $id_pesanan);
$stmt_detail->execute();
$items = $stmt_detail->fetchAll();

// Hitung subtotal dari item jika belum ada
$subtotal = 0;
foreach ($items as $item) {
    $subtotal += $item['subtotal'];
}
if ($subtotal == 0) {
    $subtotal = 100000.00; // Default jika data kosong
}

// Hitung Pajak PB1 (10%)
$pajak_pb1 = $subtotal * 0.10;
$total_bayar = $subtotal + $pajak_pb1;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Pembayaran — Maky Chicken</title>
<link href="https://fonts.googleapis.com" rel="preconnect">
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=Titan+One&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'mc-primary': '#B3131B',
            'mc-primary-dark': '#8E0F18',
            'mc-primary-light': '#FBE0DD',
            'mc-bg': '#FFF3DC',
            'mc-card': '#FFFFFF',
            'mc-card-subtle': '#FFFDF9',
            'mc-accent': '#FFB21E',
            'mc-accent-dark': '#D48600',
            'mc-accent-soft': '#FFF1D6',
            'mc-info': '#1F5FAE',
            'mc-info-light': '#DCEBFA',
            'mc-text': '#2A1010',
            'mc-text-muted': '#725049',
            'mc-border': '#F4DCB5',
            'mc-border-light': '#FBEBD0',
          },
          fontFamily: {
            display: ['"Titan One"', 'cursive', 'sans-serif'],
            body: ['"Poppins"', 'sans-serif'],
          },
          boxShadow: {
            'mc-warm': '0 12px 32px -8px rgba(179, 19, 27, 0.08), 0 4px 16px -2px rgba(42, 16, 16, 0.05)',
            'mc-elevated': '0 18px 40px -10px rgba(179, 19, 27, 0.14), 0 6px 20px -2px rgba(42, 16, 16, 0.06)',
            'mc-pill': '0 4px 14px rgba(179, 19, 27, 0.28)',
          },
          borderRadius: {
            'mc-card': '24px',
            'mc-pill': '999px',
          }
        }
      }
    }
  </script>
<style>
    body {
      background-color: #FFF3DC;
      color: #2A1010;
      font-family: 'Poppins', sans-serif;
    }
    .font-titan {
      font-family: 'Titan One', cursive, sans-serif;
      letter-spacing: 0.02em;
    }
    .mc-navbar {
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
    }
    .bg-pattern {
      background-image: radial-gradient(#F0D4A0 1.2px, transparent 1.2px);
      background-size: 28px 28px;
    }
  </style>
</head>
<body class="bg-mc-bg text-mc-text min-h-screen flex flex-col bg-pattern selection:bg-mc-accent/30 selection:text-mc-primary">

<!-- Floating Capsule Navbar -->
<header class="sticky top-4 z-40 px-6 max-w-[1240px] w-full mx-auto">
<nav class="mc-navbar bg-white/90 border border-mc-border/80 rounded-mc-pill px-6 py-3 shadow-mc-warm flex items-center justify-between gap-4 transition-all">
<div class="flex items-center gap-4">
<button aria-label="Kembali ke pesanan" class="w-10 h-10 rounded-full flex items-center justify-center text-mc-primary bg-mc-bg hover:bg-mc-accent-soft active:scale-95 transition-all" onclick="history.back()" title="Kembali">
<span class="material-symbols-outlined text-[22px]">arrow_back</span>
</button>
<div class="flex items-center gap-2.5">
<div class="w-9 h-9 rounded-full bg-mc-primary text-white flex items-center justify-center font-titan text-lg shadow-sm">
            M
          </div>
<div>
<div class="flex items-center gap-2">
<span class="font-titan text-mc-primary text-lg tracking-normal leading-none">MAKY CHICKEN</span>
<span class="hidden sm:inline-block text-[11px] font-bold uppercase tracking-wider text-white bg-mc-primary px-2 py-0.5 rounded-full"><?php echo htmlspecialchars($pesanan['jenis_pesanan']); ?></span>
</div>
<p class="text-xs text-mc-text-muted font-medium">Kasir &amp; Pembayaran Digital</p>
</div>
</div>
</div>
<div class="flex items-center gap-3">
<div class="flex items-center gap-2 bg-mc-accent-soft border border-mc-accent/30 px-3.5 py-1.5 rounded-mc-pill">
<span class="w-2.5 h-2.5 rounded-full bg-mc-accent animate-ping"></span>
<span class="text-xs font-bold text-mc-text tracking-wide uppercase">MEJA <?php echo htmlspecialchars($no_meja); ?></span>
<span class="text-mc-text-muted text-xs hidden md:inline font-normal">· Lt. 1</span>
</div>
<div class="hidden sm:flex items-center gap-2 bg-white px-3 py-1.5 rounded-mc-pill border border-mc-border-light text-xs font-semibold text-mc-text-muted">
<span class="material-symbols-outlined text-mc-primary text-[18px]">receipt_long</span>
<span>#<?php echo htmlspecialchars($pesanan['kode_pesanan']); ?></span>
</div>
<div class="w-9 h-9 rounded-full bg-mc-bg border border-mc-border flex items-center justify-center text-mc-primary" title="Pengguna Meja <?php echo htmlspecialchars($no_meja); ?>">
<span class="material-symbols-outlined text-[20px]">person</span>
</div>
</div>
</nav>
</header>

<!-- Main Layout Container -->
<main class="flex-1 w-full max-w-[1240px] mx-auto px-6 pt-8 pb-16">
<div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
<div>
<div class="inline-flex items-center gap-2 px-3 py-1 bg-mc-accent/20 rounded-full mb-2 border border-mc-accent/30">
<span class="w-2 h-2 rounded-full bg-mc-accent-dark animate-pulse"></span>
<span class="text-xs font-bold uppercase tracking-wider text-mc-text">Tahap Akhir Transaksi</span>
</div>
<h1 class="font-titan text-3xl lg:text-4xl text-mc-primary tracking-wide">PILIH METODE PEMBAYARAN</h1>
<p class="text-sm md:text-base text-mc-text-muted mt-1 font-medium">Selesaikan transaksi sebelum batas waktu sesi pemesanan berakhir.</p>
</div>
<div class="flex items-center gap-3 bg-white px-4 py-2.5 rounded-2xl border border-mc-border shadow-sm self-start md:self-auto">
<span class="flex h-3 w-3 relative">
<span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
<span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
</span>
<div class="text-left">
<span class="text-[11px] font-semibold text-mc-text-muted uppercase block leading-none">Status Pesanan</span>
<span class="text-xs font-bold text-mc-accent-dark"><?php echo htmlspecialchars($status_pesanan); ?></span>
</div>
</div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
<!-- LEFT COLUMN: Rincian Tagihan & Detail Pesanan -->
<section aria-label="Rincian Tagihan" class="lg:col-span-5 flex flex-col gap-6">
<div class="bg-white rounded-mc-card p-5 border border-mc-border/70 shadow-mc-warm flex items-center justify-between">
<div class="flex items-center gap-3.5">
<div class="w-12 h-12 rounded-2xl bg-mc-primary-light flex items-center justify-center text-mc-primary shrink-0">
<span class="material-symbols-outlined text-[26px]">receipt_long</span>
</div>
<div>
<div class="flex items-center gap-2">
<span class="font-titan text-xl text-mc-text">#<?php echo htmlspecialchars($pesanan['kode_pesanan']); ?></span>
<span class="px-2.5 py-0.5 bg-mc-accent-soft text-mc-accent-dark font-bold text-xs rounded-full border border-mc-accent/30">Meja <?php echo htmlspecialchars($no_meja); ?></span>
</div>
<span class="text-xs text-mc-text-muted font-medium">Lantai 1 · Dine-In Maky Central</span>
</div>
</div>
<span class="px-3 py-1 bg-amber-100 text-amber-800 border border-amber-300 text-xs font-bold rounded-mc-pill whitespace-nowrap">
          <?php echo htmlspecialchars($status_pesanan); ?>
        </span>
</div>

<div class="bg-white rounded-mc-card p-6 border border-mc-border/70 shadow-mc-warm">
<div class="flex items-center justify-between pb-4 border-b border-mc-border-light">
<div class="flex items-center gap-2.5">
<div class="w-8 h-8 rounded-full bg-mc-primary/10 flex items-center justify-center text-mc-primary">
<span class="material-symbols-outlined text-[19px]">restaurant_menu</span>
</div>
<h2 class="font-titan text-lg text-mc-text tracking-wide">Rincian Tagihan</h2>
</div>
<span class="text-xs font-bold text-mc-primary bg-mc-primary-light px-3 py-1 rounded-mc-pill"><?php echo count($items); ?> Menu Dipesan</span>
</div>

<!-- Dinamis Items List dari Database -->
<div class="py-4 space-y-3.5 divide-y divide-dashed divide-mc-border-light">
<?php if (!empty($items)): ?>
    <?php foreach ($items as $item): ?>
    <div class="pt-2 first:pt-0 flex items-start justify-between gap-3">
        <div class="flex items-start gap-3 min-w-0">
            <span class="w-6 h-6 rounded-lg bg-mc-bg border border-mc-border flex items-center justify-center font-bold text-xs text-mc-primary shrink-0 mt-0.5"><?php echo $item['jumlah']; ?>x</span>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-mc-text leading-tight truncate"><?php echo htmlspecialchars($item['nama_menu']); ?></p>
                <p class="text-xs text-mc-text-muted"><?php echo htmlspecialchars($item['catatan'] ?? $item['deskripsi']); ?></p>
            </div>
        </div>
        <span class="font-bold text-sm text-mc-text tabular-nums shrink-0">Rp<?php echo number_format($item['subtotal'], 0, ',', '.'); ?></span>
    </div>
    <?php endforeach; ?>
<?php else: ?>
    <p class="text-xs text-mc-text-muted text-center py-2">Tidak ada rincian item.</p>
<?php endif; ?>
</div>

<!-- Subtotal & Calculations Box -->
<div class="mt-4 pt-4 border-t border-mc-border/80 bg-mc-card-subtle -mx-6 -mb-6 p-6 rounded-b-mc-card space-y-2.5">
<div class="flex justify-between items-center text-xs sm:text-sm text-mc-text-muted">
<span>Subtotal Pesanan</span>
<span class="font-semibold text-mc-text tabular-nums">Rp<?php echo number_format($subtotal, 0, ',', '.'); ?></span>
</div>
<div class="flex justify-between items-center text-xs sm:text-sm text-mc-text-muted">
<div class="flex items-center gap-1">
<span>PB1 Restoran (10%)</span>
<span class="material-symbols-outlined text-[15px] text-mc-text-muted cursor-help" title="Pajak Restoran Resmi 10%">info</span>
</div>
<span class="font-semibold text-mc-text tabular-nums">Rp<?php echo number_format($pajak_pb1, 0, ',', '.'); ?></span>
</div>
<div class="flex justify-between items-center text-xs sm:text-sm text-emerald-700">
<span>Biaya Layanan &amp; Meja</span>
<span class="font-bold uppercase tracking-wider text-xs bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full">GRATIS</span>
</div>
<div class="pt-3 mt-2 border-t border-mc-border flex items-center justify-between">
<div>
<span class="font-titan text-base text-mc-text block">Total Bayar</span>
<span class="text-[11px] text-mc-text-muted">Termasuk pajak &amp; rincian menu</span>
</div>
<div class="text-right">
<span class="font-titan text-2xl lg:text-3xl text-mc-primary tabular-nums tracking-normal">Rp<?php echo number_format($total_bayar, 0, ',', '.'); ?></span>
</div>
</div>
</div>
</div>

<div class="p-4 rounded-2xl bg-white/70 border border-mc-border-light flex items-center gap-3 text-xs text-mc-text-muted">
<span class="material-symbols-outlined text-mc-primary text-[22px] shrink-0">verified_user</span>
<p>Transaksi dilindungi enkripsi standar Bank Indonesia. Bukti bayar otomatis tercatat di sistem kasir Maky Chicken.</p>
</div>
</section>

<!-- RIGHT COLUMN: Payment Methods & Actions -->
<section aria-label="Pilihan Metode Pembayaran" class="lg:col-span-7 flex flex-col gap-6">
<div class="bg-white rounded-mc-card p-6 lg:p-7 border border-mc-border/70 shadow-mc-warm">
<div class="flex items-center justify-between mb-5">
<div>
<span class="text-xs font-bold text-mc-primary uppercase tracking-widest block">Metode Tersedia</span>
<h2 class="font-titan text-xl text-mc-text tracking-wide">PILIHAN PEMBAYARAN</h2>
</div>
<span class="text-xs font-semibold text-mc-text-muted bg-mc-bg px-3 py-1 rounded-mc-pill border border-mc-border">2 Opsi Pembayaran</span>
</div>

<div class="space-y-4">
<!-- METHOD 1: QRIS DINAMIS -->
<div class="border-2 border-mc-primary bg-mc-primary-light/40 rounded-2xl p-5 transition-all cursor-pointer relative shadow-sm" id="option-qris" onclick="selectPayment('qris')">
<div class="flex items-center justify-between">
<div class="flex items-center gap-3.5">
<div class="w-11 h-11 rounded-xl bg-mc-primary text-white flex items-center justify-center shadow-md shrink-0">
<span class="material-symbols-outlined text-[24px]">qr_code_scanner</span>
</div>
<div>
<div class="flex items-center gap-2">
<h3 class="font-titan text-lg text-mc-text">QRIS DINAMIS</h3>
<span class="text-[11px] font-bold bg-mc-accent text-mc-text px-2 py-0.5 rounded-full tracking-wide">PRAKTIS &amp; CEPAT</span>
</div>
<p class="text-xs text-mc-text-muted mt-0.5">BCA Mobile, GoPay, OVO, ShopeePay, Dana, LinkAja &amp; Semua Bank</p>
</div>
</div>
<div class="w-7 h-7 rounded-full bg-mc-primary flex items-center justify-center text-white shrink-0 shadow-sm" id="radio-qris">
<span class="material-symbols-outlined text-[18px] font-bold">check</span>
</div>
</div>

<div class="mt-5 pt-5 border-t border-mc-primary/20" id="content-qris">
<div class="bg-white rounded-2xl p-5 border border-mc-border/60 shadow-sm flex flex-col md:flex-row items-center gap-6">
<div class="flex flex-col items-center shrink-0">
<div class="p-3 bg-white border-2 border-mc-border rounded-2xl shadow-inner relative group">
<svg class="w-48 h-48 text-mc-text" fill="currentColor" viewBox="0 0 100 100">
<path d="M5 5h30v30H5V5zm4 4v22h22V9H9zm4 4h14v14H13V13zM65 5h30v30H65V5zm4 4v22h22V9H69zm4 4h14v14H73V13zM5 65h30v30H5V65zm4 4v22h22V69H9zm4 4h14v14H13V73zM42 5h8v8h-8zM52 5h6v12h-6zM42 17h16v6H42zM45 27h13v8H45zM5 42h8v8H5zM17 42h18v6H17zM5 52h22v6H5zM29 50h6v10h-6zM42 42h8v8H8zM54 42h8v16h-8zM42 54h8v8h-8zM65 42h10v6H65zM79 42h16v8H79zM65 52h6v14h-6zM75 54h10v6H75zM89 54h6v12h-6zM42 66h6v12h-6zM52 66h10v6H52zM45 82h13v13H45zM65 70h8v8h-8zM77 70h18v6H77zM65 82h12v13H65zM81 80h14v6H81zM81 90h14v5H81z"></path>
<rect class="fill-mc-primary" height="16" rx="4" width="16" x="42" y="42"></rect>
<path d="M47 46h6v8h-6z" fill="#FFF3DC"></path>
</svg>
</div>
<span class="text-[11px] font-semibold text-mc-text-muted mt-2 tracking-wider">NMID: ID1020088921001</span>
</div>
<div class="flex-1 flex flex-col justify-between w-full h-full text-center md:text-left">
<div>
<div class="flex flex-wrap items-center justify-center md:justify-start gap-2 mb-2.5">
<span class="px-2.5 py-1 bg-mc-info-light text-mc-info rounded-mc-pill text-xs font-bold flex items-center gap-1">
<span class="material-symbols-outlined text-[15px]">bolt</span> QRIS DINAMIS
</span>
<div class="flex items-center gap-1.5 px-3 py-1 bg-mc-bg rounded-mc-pill border border-mc-border text-xs font-semibold text-mc-text">
<span class="material-symbols-outlined text-mc-primary text-[15px] animate-pulse">timer</span>
<span class="text-mc-text-muted">Batas Waktu:</span>
<span class="tabular-nums font-bold text-mc-primary" id="countdown">06:26</span>
</div>
</div>
<div class="mb-3">
<span class="text-xs text-mc-text-muted block">Total Nominal Transfer:</span>
<span class="font-titan text-2xl lg:text-3xl text-mc-primary tabular-nums">Rp<?php echo number_format($total_bayar, 0, ',', '.'); ?></span>
</div>
<p class="text-xs text-mc-text-muted leading-relaxed mb-4">
Buka aplikasi perbankan digital atau dompet digital (GoPay, OVO, BCA, DANA, ShopeePay), lalu arahkan kamera ke kode QR di atas.
</p>
</div>
<div class="grid grid-cols-2 gap-2.5 w-full pt-1">
<button class="h-10 px-4 rounded-mc-pill bg-mc-bg hover:bg-mc-accent-soft text-mc-primary border border-mc-border font-bold text-xs flex items-center justify-center gap-1.5 active:scale-95 transition-all" onclick="event.stopPropagation(); showToast('QR Code tersimpan ke folder Unduhan');" type="button">
<span class="material-symbols-outlined text-[17px]">download</span> Unduh QR
</button>
<button class="h-10 px-4 rounded-mc-pill bg-mc-bg hover:bg-mc-accent-soft text-mc-text border border-mc-border font-bold text-xs flex items-center justify-center gap-1.5 active:scale-95 transition-all" onclick="event.stopPropagation(); resetTimer();" type="button">
<span class="material-symbols-outlined text-[17px]">refresh</span> Refresh QR
</button>
</div>
</div>
</div>
</div>
</div>

<!-- METHOD 2: TUNAI / KASIR -->
<div class="border border-mc-border bg-white hover:border-mc-primary/40 rounded-2xl p-5 transition-all cursor-pointer relative shadow-sm" id="option-cash" onclick="selectPayment('cash')">
<div class="flex items-center justify-between">
<div class="flex items-center gap-3.5">
<div class="w-11 h-11 rounded-xl bg-mc-bg border border-mc-border text-mc-text-muted flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-[24px]">payments</span>
</div>
<div>
<div class="flex items-center gap-2">
<h3 class="font-titan text-lg text-mc-text">TUNAI / KASIR</h3>
<span class="text-[11px] font-bold bg-stone-100 text-stone-600 px-2 py-0.5 rounded-full border border-stone-200">OFFLINE</span>
</div>
<p class="text-xs text-mc-text-muted mt-0.5">Bayar langsung di konter kasir dengan uang tunai, kartu debit, atau EDC</p>
</div>
</div>
<div class="w-7 h-7 rounded-full bg-mc-bg border border-mc-border flex items-center justify-center text-transparent shrink-0" id="radio-cash">
<span class="material-symbols-outlined text-[18px]">check</span>
</div>
</div>

<div class="hidden mt-5 pt-5 border-t border-mc-border-light space-y-3.5" id="content-cash">
<div class="bg-mc-bg/60 p-4 rounded-xl border border-mc-border/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
<div>
<span class="text-[11px] font-bold text-mc-text-muted uppercase tracking-wider block">KODE TRANSAKSI PEMESANAN</span>
<span class="font-titan text-2xl text-mc-primary tracking-wide"><?php echo htmlspecialchars($pesanan['kode_pesanan']); ?></span>
</div>
<button class="px-4 py-2 rounded-mc-pill bg-mc-primary text-white hover:bg-mc-primary-dark font-bold text-xs flex items-center justify-center gap-1.5 self-start sm:self-auto shadow-sm active:scale-95 transition-all" onclick="event.stopPropagation(); navigator.clipboard.writeText('<?php echo htmlspecialchars($pesanan['kode_pesanan']); ?>'); showToast('Kode Transaksi berhasil disalin!');" type="button">
<span class="material-symbols-outlined text-[16px]">content_copy</span> Salin Kode
</button>
</div>
<div class="flex items-start gap-3 p-3 bg-mc-accent-soft rounded-xl border border-mc-accent/30 text-xs text-mc-text leading-relaxed">
<span class="material-symbols-outlined text-mc-accent-dark text-[20px] shrink-0 mt-0.5">info</span>
<p>Tunjukkan <strong>Nomor Meja <?php echo htmlspecialchars($no_meja); ?></strong> atau <strong>Kode <?php echo htmlspecialchars($pesanan['kode_pesanan']); ?></strong> ke petugas kasir. Setelah pembayaran diverifikasi kasir, pesanan akan langsung dipersiapkan oleh dapur.</p>
</div>
</div>
</div>
</div>

<div class="mt-8 pt-6 border-t border-mc-border/70 flex flex-col sm:flex-row items-center gap-4">
<button class="w-full sm:flex-1 h-14 rounded-mc-pill bg-mc-primary hover:bg-mc-primary-dark text-white font-titan text-base tracking-wide flex items-center justify-center gap-2 shadow-mc-pill active:scale-[0.98] transition-all cursor-pointer" onclick="checkStatus()" type="button">
<span class="material-symbols-outlined text-[22px]">verified</span>
<span>CEK STATUS PEMBAYARAN</span>
</button>
<button class="w-full sm:w-auto px-7 h-14 rounded-mc-pill bg-white hover:bg-mc-bg text-mc-primary border-2 border-mc-primary/30 font-bold text-sm tracking-wide flex items-center justify-center hover:border-mc-primary active:scale-95 transition-all" onclick="history.back()" type="button">
UBAH PESANAN
</button>
</div>
</div>

<div class="flex items-center justify-between px-6 py-4 rounded-2xl bg-mc-card/80 border border-mc-border text-xs text-mc-text-muted">
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-mc-info text-[20px]">support_agent</span>
<span>Butuh bantuan kasir atau pelayan ke meja Anda?</span>
</div>
<button class="text-mc-primary font-bold hover:underline" onclick="showToast('Pelayan sedang menuju Meja <?php echo htmlspecialchars($no_meja); ?>');">
Panggil Pelayan
</button>
</div>
</section>
</div>
</main>

<!-- Notification Toast -->
<div class="fixed bottom-8 left-1/2 -translate-x-1/2 px-6 py-3 bg-mc-text text-white rounded-mc-pill text-xs md:text-sm font-semibold shadow-2xl flex items-center gap-2.5 opacity-0 pointer-events-none transition-all duration-300 z-50 border border-white/20" id="toast">
<span class="material-symbols-outlined text-mc-accent text-[18px]">info</span>
<span id="toast-message">Memeriksa pembayaran secara berkala...</span>
</div>

<script>
    let timeLeft = 14 * 60 + 48;
    const timerElem = document.getElementById('countdown');
     
    const timerInterval = setInterval(() => {
      if (timeLeft <= 0) {
        clearInterval(timerInterval);
        timerElem.innerText = '00:00';
        return;
      }
      timeLeft--;
      const minutes = Math.floor(timeLeft / 60).toString().padStart(2, '0');
      const seconds = (timeLeft % 60).toString().padStart(2, '0');
      timerElem.innerText = `${minutes}:${seconds}`;
    }, 1000);

    function resetTimer() {
      timeLeft = 15 * 60;
      timerElem.innerText = '15:00';
      showToast('QR Code diperbarui untuk 15 menit ke depan');
    }

    function selectPayment(type) {
      const optQris = document.getElementById('option-qris');
      const optCash = document.getElementById('option-cash');
      const radioQris = document.getElementById('radio-qris');
      const radioCash = document.getElementById('radio-cash');
      const contentQris = document.getElementById('content-qris');
      const contentCash = document.getElementById('content-cash');

      if (type === 'qris') {
        optQris.className = 'border-2 border-mc-primary bg-mc-primary-light/40 rounded-2xl p-5 transition-all cursor-pointer relative shadow-sm';
        optCash.className = 'border border-mc-border bg-white hover:border-mc-primary/40 rounded-2xl p-5 transition-all cursor-pointer relative shadow-sm';
         
        radioQris.className = 'w-7 h-7 rounded-full bg-mc-primary flex items-center justify-center text-white shrink-0 shadow-sm';
        radioCash.className = 'w-7 h-7 rounded-full bg-mc-bg border border-mc-border flex items-center justify-center text-transparent shrink-0';
         
        contentQris.classList.remove('hidden');
        contentCash.classList.add('hidden');
      } else {
        optCash.className = 'border-2 border-mc-primary bg-mc-primary-light/40 rounded-2xl p-5 transition-all cursor-pointer relative shadow-sm';
        optQris.className = 'border border-mc-border bg-white hover:border-mc-primary/40 rounded-2xl p-5 transition-all cursor-pointer relative shadow-sm';
         
        radioCash.className = 'w-7 h-7 rounded-full bg-mc-primary flex items-center justify-center text-white shrink-0 shadow-sm';
        radioQris.className = 'w-7 h-7 rounded-full bg-mc-bg border border-mc-border flex items-center justify-center text-transparent shrink-0';
         
        contentCash.classList.remove('hidden');
        contentQris.classList.add('hidden');
      }
    }

    function checkStatus() {
      showToast('Menghubungi server gateway perbankan...');
      setTimeout(() => {
        showToast('Pesanan #<?php echo htmlspecialchars($pesanan['kode_pesanan']); ?>: Pembayaran sedang diverifikasi kasir');
      }, 1500);
    }

    let toastTimer;
    function showToast(msg) {
      const toast = document.getElementById('toast');
      const toastMsg = document.getElementById('toast-message');
      toastMsg.innerText = msg;
      toast.classList.remove('opacity-0', 'pointer-events-none');
      toast.classList.add('translate-y-[-8px]');
       
      clearTimeout(toastTimer);
      toastTimer = setTimeout(() => {
        toast.classList.add('opacity-0', 'pointer-events-none');
        toast.classList.remove('translate-y-[-8px]');
      }, 3000);
    }
</script>
</body>
</html>