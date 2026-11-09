<?php
// Ambil data user dari session login
$namaUser = $_SESSION['user']['nama'] ?? 'Budi Santoso';
$roleUser = $_SESSION['user']['role'] ?? 'Super Admin Cabang';
$initials = strtoupper(substr($namaUser, 0, 2));
$tanggalAktif = $tanggal ?? date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Laporan Penjualan - Maky Chicken</title>
    
    <!-- Google Fonts: Titan One & Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Titan+One&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- CSS Utama Project -->
    <link rel="stylesheet" href="css/maky.css">

    <style>
    :root {
        --red-100: #FBE0DD; --red-600: #B3131B; --red-700: #8E0F18; --red-900: #5E0A10;
        --cream-50: #FFFAF0; --cream-100: #FFF3DC; --cream-200: #FBE6BE; --cream-300: #F2D49A;
        --yellow-100: #FFF0C2; --yellow-500: #FFB21E; --yellow-600: #F28C0F;
        --blue-100: #DCEBFA; --blue-600: #1F5FAE; --green-600: #1E8E4E; --green-100: #D9F2E3;
        --ink-900: #2A1010; --ink-600: #6B4A45;
        --primary: var(--red-600); --primary-hover: var(--red-700);
        --border: var(--cream-300); --text: var(--ink-900); --text-muted: var(--ink-600);
        --accent: var(--yellow-500); --info: var(--blue-600); --success: var(--green-600);
        --shadow-1: 0 2px 8px rgba(142,15,24,.08); --shadow-2: 0 10px 28px rgba(142,15,24,.14);
        --shadow-3: 0 18px 44px rgba(94,10,16,.25);
        --font-display: 'Titan One', 'Arial Black', sans-serif; --font-body: 'Poppins', system-ui, sans-serif;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: var(--font-body); background: var(--cream-50); color: var(--text); line-height: 1.5; font-size: 13px; }
    button, input { font: inherit; color: inherit; }
    h1, h2, h3, .disp { font-family: var(--font-display); font-weight: 400; text-transform: uppercase; line-height: 1.05; }

    /* Layout Main Grid */
    .mc-app { display: grid; grid-template-columns: 240px 1fr; min-height: 100vh; }
    
    /* Sidebar Layout UI-14 */
    .side { background: var(--red-900); color: var(--cream-50); padding: 20px 16px; position: sticky; top: 0; height: 100vh; overflow-y: auto; display: flex; flex-direction: column; justify-content: space-between; }
    .brand { display: flex; gap: 12px; align-items: center; padding-bottom: 16px; border-bottom: 1px solid rgba(255,250,240,.15); margin-bottom: 8px; }
    .logo { width: 40px; height: 40px; border-radius: 50%; background: var(--accent); color: var(--red-900); display: grid; place-items: center; font-weight: 700; flex: none; font-size: 14px; }
    .brand b { font-family: var(--font-display); font-size: 17px; color: #fff; display: block; line-height: 1.1; }
    .brand small { color: var(--accent); font-weight: 700; font-size: 9px; letter-spacing: .12em; text-transform: uppercase; }
    
    .sidesearch-wrap { position: relative; margin-bottom: 8px; }
    .sidesearch { width: 100%; height: 38px; border-radius: 999px; border: 1.5px solid rgba(255,250,240,.2); background: rgba(255,250,240,.06); padding: 0 16px 0 36px; color: #fff; outline: none; font-size: 12px; }
    .sidesearch::placeholder { color: rgba(255,250,240,.5); }
    .sidesearch-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: rgba(255,250,240,.5); font-size: 13px; }
    
    .grp { font-size: 10px; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: rgba(255,250,240,.45); margin: 16px 8px 6px; }
    .nav { display: flex; align-items: center; gap: 10px; min-height: 40px; padding: 6px 14px; border-radius: 999px; color: rgba(255,250,240,.8); font-weight: 600; font-size: 13px; text-decoration: none; border: 0; background: none; cursor: pointer; text-align: left; width: 100%; transition: all .2s ease; }
    .nav:hover { background: rgba(255,250,240,.12); color: #fff; }
    .nav[aria-current=page] { background: var(--accent); color: var(--ink-900); font-weight: 700; box-shadow: var(--shadow-2); }

    /* Footer Sidebar Profile UI-14 */
    .user-footer { display: flex; align-items: center; gap: 10px; padding-top: 16px; border-top: 1px solid rgba(255,250,240,.15); margin-top: 16px; }
    .user-av { width: 34px; height: 34px; border-radius: 50%; background: var(--primary); color: #fff; font-weight: 700; font-size: 11px; display: grid; place-items: center; flex: none; border: 1.5px solid var(--accent); }
    .user-info b { display: block; font-size: 12px; color: #fff; line-height: 1.2; }
    .user-info small { font-size: 10px; color: rgba(255,250,240,.6); display: block; }

    /* Main Container UI-14 */
    .main { padding: 28px 36px 48px; min-width: 0; background: var(--cream-50); }
    .top-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap; margin-bottom: 24px; }
    .top-header h1 { font-size: 32px; color: var(--red-600); letter-spacing: .02em; }
    .top-header p { font-size: 12px; font-weight: 500; color: var(--text-muted); }

    /* Top Controls Bar UI-14 */
    .action-group { display: flex; gap: 12px; align-items: center; flex-wrap: wrap; }
    .date-pill { display: flex; align-items: center; gap: 8px; background: #fff; border: 1.5px solid var(--cream-300); border-radius: 999px; padding: 4px 16px; font-weight: 700; font-size: 12px; color: var(--ink-900); }
    .date-pill input { border: 0; background: transparent; outline: none; font-weight: 700; color: inherit; cursor: pointer; }

    .mc-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; min-height: 42px; padding: 0 20px; border-radius: 999px; border: 1.5px solid transparent; font-weight: 700; font-size: 11px; letter-spacing: .06em; text-transform: uppercase; cursor: pointer; text-decoration: none; transition: all .2s ease; }
    .mc-btn--primary { background: var(--red-600); color: #fff; }
    .mc-btn--primary:hover { background: var(--red-700); color: #fff; transform: translateY(-1px); box-shadow: var(--shadow-1); }
    .mc-btn--info { background: var(--info); color: #fff; } 
    .mc-btn--info:hover { background: #174E90; color: #fff; transform: translateY(-1px); }
    .mc-btn--ghost { background: transparent; color: var(--text); border-color: var(--border); }

    /* 4 Card KPI Grid UI-14 */
    .kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
    .kpi-card { background: #fff; border: 1.5px solid var(--cream-300); border-radius: 18px; padding: 18px; position: relative; box-shadow: var(--shadow-1); }
    .kpi-title { font-size: 10px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--text-muted); display: block; margin-bottom: 4px; }
    .kpi-val { font-family: var(--font-display); font-size: 26px; color: var(--red-600); line-height: 1.1; margin-bottom: 6px; }
    .kpi-sub { font-size: 11px; font-weight: 500; color: var(--text-muted); }
    .kpi-icon-corner { position: absolute; top: 16px; right: 16px; color: var(--text-muted); opacity: 0.6; font-size: 14px; }

    /* Middle Row Layout (2 Equal Cards UI-14) */
    .mid-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 28px; }
    .card-ui { background: #fff; border: 1.5px solid var(--cream-300); border-radius: 20px; padding: 22px; box-shadow: var(--shadow-1); }
    .card-header-flex { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; }
    .card-header-flex h3 { font-size: 18px; color: var(--red-600); line-height: 1.1; }
    .card-header-flex p { font-size: 11px; color: var(--text-muted); margin-top: 2px; }

    /* Progress & Ratio Box UI-14 */
    .ratio-pill-bar { background: var(--cream-100); border-radius: 999px; height: 26px; display: flex; overflow: hidden; margin-bottom: 16px; border: 1px solid var(--cream-300); font-weight: 700; font-size: 10px; }
    .ratio-qris { background: var(--red-600); color: #fff; display: flex; align-items: center; justify-content: center; width: 70.4%; }
    .ratio-tunai { background: var(--yellow-500); color: var(--ink-900); display: flex; align-items: center; justify-content: center; width: 29.6%; }

    .method-subgrid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px; }
    .method-box { background: var(--cream-50); border: 1.5px solid var(--cream-200); border-radius: 14px; padding: 12px 14px; }
    .method-box small { font-size: 10px; font-weight: 600; color: var(--text-muted); display: block; margin-bottom: 2px; }
    .method-box b { font-family: var(--font-display); font-size: 20px; display: block; line-height: 1.1; }

    .service-breakdown { border-top: 1px dashed var(--cream-300); padding-top: 14px; }
    .service-row { display: flex; justify-content: space-between; align-items: center; padding: 6px 0; font-size: 12px; }
    .service-row span { font-weight: 600; }
    .service-row b { font-weight: 700; }

    /* Table Top 5 Menu Terlaris UI-14 */
    .table-topmenu { width: 100%; border-collapse: separate; border-spacing: 0; font-size: 12px; }
    .table-topmenu th { background: var(--red-600); color: #fff; font-size: 10px; letter-spacing: .08em; text-transform: uppercase; padding: 10px 12px; text-align: left; }
    .table-topmenu th:first-child { border-top-left-radius: 12px; }
    .table-topmenu th:last-child { border-top-right-radius: 12px; text-align: right; }
    .table-topmenu td { padding: 10px 12px; border-bottom: 1px solid var(--cream-200); vertical-align: middle; }
    .rank-circle { width: 22px; height: 22px; border-radius: 50%; background: var(--yellow-500); color: var(--ink-900); font-weight: 700; font-size: 11px; display: grid; place-items: center; }

    /* Detail Transaksi Penjualan Table UI-14 */
    .table-main-wrap { border: 1.5px solid var(--cream-300); border-radius: 18px; overflow: hidden; background: #fff; }
    .table-main { width: 100%; border-collapse: collapse; font-size: 12px; text-align: left; }
    .table-main th { background: var(--red-600); color: #fff; font-size: 10px; letter-spacing: .08em; text-transform: uppercase; padding: 14px 16px; }
    .table-main td { padding: 12px 16px; border-bottom: 1px solid var(--cream-200); vertical-align: middle; }
    .table-main tr:nth-child(even) { background: var(--cream-50); }
    .table-main tr:hover { background: var(--cream-100); }

    /* Badges UI-14 */
    .pill-status { display: inline-flex; align-items: center; gap: 4px; padding: 2px 10px; border-radius: 999px; font-size: 10px; font-weight: 700; }
    .pill-qris { background: var(--blue-100); color: var(--info); }
    .pill-tunai { background: var(--yellow-100); color: var(--yellow-600); }
    .pill-dinein { background: var(--green-100); color: var(--success); }
    .pill-takeaway { background: var(--yellow-100); color: var(--yellow-600); }
    .pill-reservasi { background: var(--blue-100); color: var(--info); }

    .pagination-bar { display: flex; justify-content: space-between; align-items: center; padding: 12px 20px; font-size: 11px; color: var(--text-muted); font-weight: 600; background: #fff; border-top: 1px solid var(--cream-200); }
    .page-btn { padding: 4px 10px; border-radius: 8px; border: 1px solid var(--cream-300); background: #fff; cursor: pointer; font-weight: 600; text-decoration: none; color: inherit; }
    .page-btn.active { background: var(--red-600); color: #fff; border-color: var(--red-600); }

    /* Modal Animation Popup */
    .overlay { position: fixed; inset: 0; background: rgba(94, 10, 16, 0.45); display: flex; align-items: center; justify-content: center; padding: 16px; z-index: 1000; backdrop-filter: blur(4px); opacity: 0; pointer-events: none; transition: opacity 0.25s ease-in-out; }
    .overlay.on { opacity: 1; pointer-events: auto; }
    .modal-card { background: #fff; border-radius: 24px; box-shadow: var(--shadow-3); padding: 24px; max-width: 440px; width: 100%; border: 1.5px solid var(--cream-300); transform: translateY(20px) scale(0.95); transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1); }
    .overlay.on .modal-card { transform: translateY(0) scale(1); }

    @media(max-width: 1024px) {
        .kpi-grid { grid-template-columns: repeat(2, 1fr); }
        .mid-grid { grid-template-columns: 1fr; }
    }
    @media(max-width: 860px) {
        .mc-app { grid-template-columns: 1fr; }
        .side { position: static; height: auto; }
    }
    </style>
</head>
<body>

<div class="mc-app">
    <!-- SIDEBAR NAVIGASI STAFF (EXACT UI-14) -->
    <aside class="side">
        <div>
            <div class="brand">
                <span class="logo">MC</span>
                <div>
                    <b>MAKY CHICKEN</b>
                    <small>SISTEM RESTORAN</small>
                </div>
            </div>

            <div class="sidesearch-wrap">
                <i class="bi bi-search sidesearch-icon"></i>
                <input type="search" class="sidesearch" placeholder="Cari menu... Ctrl /">
            </div>

            <div class="grp">OVERVIEW</div>
            <a href="index.php?page=internal-dashboard" class="nav">
                <i class="bi bi-grid-fill"></i> Dashboard
            </a>
            <a href="index.php?page=pos" class="nav">
                <i class="bi bi-receipt"></i> Antrean POS Kasir
            </a>
            <a href="#" class="nav">
                <i class="bi bi-shop"></i> Status Dapur / Pesanan
            </a>

            <div class="grp">MANAJEMEN OPERASIONAL</div>
            <a href="index.php?page=laporan-penjualan" class="nav" aria-current="page">
                <i class="bi bi-bar-chart-line-fill"></i> Laporan Penjualan
            </a>
            <a href="index.php?page=reservasi" class="nav">
                <i class="bi bi-calendar-check"></i> Manajemen Reservasi
            </a>
            <a href="index.php?page=kelola-menu" class="nav">
                <i class="bi bi-egg-fried"></i> Kelola Menu
            </a>

            <div class="grp">PENGATURAN & LAINNYA</div>
            <a href="index.php?page=kelola-akun" class="nav">
                <i class="bi bi-people"></i> Kelola Akun Staff
            </a>
            <a href="index.php?page=laporan-kendala" class="nav">
                <i class="bi bi-chat-left-dots"></i> Komplain & Feedback
            </a>
        </div>

        <!-- USER PROFILE FOOTER SIDEBAR -->
        <div class="user-footer">
            <span class="user-av"><?= htmlspecialchars($initials) ?></span>
            <div class="user-info">
                <b><?= htmlspecialchars($namaUser) ?></b>
                <small><?= htmlspecialchars($roleUser) ?></small>
            </div>
        </div>
    </aside>

    <!-- KONTEN UTAMA UI-14 -->
    <main class="main">
        <!-- HEADER ATAS UI-14 -->
        <header class="top-header">
            <div>
                <h1 class="disp">DASHBOARD LAPORAN PENJUALAN</h1>
                <p>Ringkasan omset harian, audit kas fisik kasir, serta riwayat transaksi penjualan.</p>
            </div>

            <div class="action-group">
                <form action="index.php" method="GET" class="date-pill">
                    <input type="hidden" name="page" value="laporan-penjualan">
                    <span style="color:var(--text-muted); font-size:10px;">TANGGAL:</span>
                    <input type="date" name="tanggal" value="<?= htmlspecialchars($tanggalAktif); ?>" onchange="this.form.submit()">
                </form>

                <a href="index.php?page=export-excel&tanggal=<?= $tanggalAktif; ?>" class="mc-btn mc-btn--info">
                    <i class="bi bi-download"></i> EXPORT DATA (.XLSX)
                </a>

                <button class="mc-btn mc-btn--primary" onclick="openModal()">
                    + MASUKKAN JUMLAH KAS
                </button>
            </div>
        </header>

        <!-- 4 KARTU KPI TOP ROW UI-14 -->
        <section class="kpi-grid">
            <!-- 1. TOTAL PENDAPATAN -->
            <div class="kpi-card">
                <span class="kpi-title">TOTAL PENDAPATAN</span>
                <i class="bi bi-graph-up-arrow kpi-icon-corner"></i>
                <div class="kpi-val">Rp<?= number_format($dataPenjualan['ringkasan']['total_pendapatan'] ?? 8450000, 0, ',', '.'); ?></div>
                <div class="kpi-sub">Rata-rata: Rp59.500 / trx</div>
            </div>

            <!-- 2. TOTAL TRANSAKSI -->
            <div class="kpi-card">
                <span class="kpi-title">TOTAL TRANSAKSI</span>
                <i class="bi bi-receipt-cutoff kpi-icon-corner"></i>
                <div class="kpi-val" style="color:var(--ink-900);"><?= $dataPenjualan['ringkasan']['total_trx'] ?? 142; ?> <span style="font-size:16px;">Trx</span></div>
                <div class="kpi-sub">
                    <span style="color:var(--info);">● 100 QRIS</span> • 
                    <span style="color:var(--yellow-600);">● 42 Tunai</span>
                </div>
            </div>

            <!-- 3. KAS SISTEM (OTOMATIS) -->
            <div class="kpi-card">
                <span class="kpi-title">KAS SISTEM (OTOMATIS)</span>
                <i class="bi bi-laptop kpi-icon-corner"></i>
                <div class="kpi-val" style="color:var(--info);">Rp<?= number_format($dataKas['kas_sistem'] ?? 2500000, 0, ',', '.'); ?></div>
                <div class="kpi-sub">● SISTEM POS</div>
            </div>

            <!-- 4. KAS FISIK & AUDIT -->
            <div class="kpi-card">
                <div style="display:flex; justify-content:space-between;">
                    <span class="kpi-title">KAS FISIK & AUDIT</span>
                    <a href="#" style="color:var(--info); font-size:10px; font-weight:700; text-decoration:none;" onclick="openModal()">UBAH</a>
                </div>
                <div class="kpi-val" style="color:var(--ink-900);">Rp<?= number_format($dataKas['kas_fisik'] ?? 2500000, 0, ',', '.'); ?></div>
                <div class="kpi-sub">
                    <?php 
                    $st = $dataKas['status_selisih'] ?? 'Sesuai'; 
                    $badgeClass = ($st == 'Sesuai') ? 'pill-dinein' : (($st == 'Surplus') ? 'pill-tunai' : 'pill-qris');
                    ?>
                    <span class="pill-status <?= $badgeClass; ?>">● Status Kasir: <?= $st; ?> (Rp<?= number_format($dataKas['selisih'] ?? 0, 0, ',', '.'); ?>)</span>
                </div>
            </div>
        </section>

        <!-- MIDDLE ROW (2 EQUAL COLUMNS UI-14) -->
        <section class="mid-grid">
            <!-- LEFT: PENDAPATAN METODE BAYAR & PEMESANAN -->
            <div class="card-ui">
                <div class="card-header-flex">
                    <div>
                        <h3 class="disp">PENDAPATAN BERDASARKAN METODE BAYAR & PEMESANAN</h3>
                        <p>Rincian perputaran omset harian berdasarkan kanal bayar QRIS & Tunai</p>
                    </div>
                    <span class="pill-status pill-dinein">● Hari Ini</span>
                </div>

                <!-- PROGRESS BAR RATIO -->
                <div class="ratio-pill-bar">
                    <div class="ratio-qris">70.4% QRIS</div>
                    <div class="ratio-tunai">29.6% TUNAI</div>
                </div>

                <!-- SUBGRID METHODS -->
                <div class="method-subgrid">
                    <div class="method-box">
                        <small>● QRIS Digital <span style="color:var(--info);">● 70.4%</span></small>
                        <b style="color:var(--red-600);">Rp5.950.000</b>
                        <small style="margin-top:4px;">100 Transaksi (Avg Rp59.500)</small>
                    </div>
                    <div class="method-box">
                        <small>● Kasir Tunai <span style="color:var(--yellow-600);">● 29.6%</span></small>
                        <b style="color:var(--yellow-600);">Rp2.500.000</b>
                        <small style="margin-top:4px;">42 Transaksi (Avg Rp59.524)</small>
                    </div>
                </div>

                <!-- RINCIAN JENIS PEMESANAN (DINE IN, TAKE AWAY, RESERVASI ONLINE) -->
                <div class="service-breakdown">
                    <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                        <span style="font-size:10px; font-weight:700; color:var(--text-muted); text-transform:uppercase;">RINCIAN JENIS PEMESANAN</span>
                        <span style="font-size:10px; font-weight:700; color:var(--text-muted);">3 Kategori Layanan</span>
                    </div>

                    <div class="service-row">
                        <span>Dine In <small class="text-muted">(<?= $dataPenjualan['ringkasan']['count_dine_in'] ?? 92; ?> Transaksi | 60.4%)</small></span>
                        <b>Rp<?= number_format($dataPenjualan['ringkasan']['total_dine_in'] ?? 5100000, 0, ',', '.'); ?></b>
                    </div>
                    <div class="service-row">
                        <span>Take Away <small class="text-muted">(<?= $dataPenjualan['ringkasan']['count_take_away'] ?? 34; ?> Transaksi | 25.4%)</small></span>
                        <b>Rp<?= number_format($dataPenjualan['ringkasan']['total_take_away'] ?? 2150000, 0, ',', '.'); ?></b>
                    </div>
                    <div class="service-row">
                        <span>Reservasi Online <small class="text-muted">(<?= $dataPenjualan['ringkasan']['count_reservasi'] ?? 16; ?> Transaksi | 14.2%)</small></span>
                        <b>Rp<?= number_format($dataPenjualan['ringkasan']['total_reservasi'] ?? 1200000, 0, ',', '.'); ?></b>
                    </div>
                </div>
            </div>

            <!-- RIGHT: MENU TERLARIS HARI INI (TOP 5 UI-14) -->
            <div class="card-ui">
                <div class="card-header-flex">
                    <div>
                        <h3 class="disp">MENU TERLARIS HARI INI</h3>
                        <p>Peringkat item menu dengan perputaran tertinggi</p>
                    </div>
                    <span class="pill-status pill-dinein">● Top 5</span>
                </div>

                <table class="table-topmenu">
                    <thead>
                        <tr>
                            <th style="width:40px;">NO</th>
                            <th>NAMA MENU</th>
                            <th>TERJUAL</th>
                            <th>SUBTOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($dataPenjualan['top_menu'])): ?>
                            <?php foreach ($dataPenjualan['top_menu'] as $idx => $m): ?>
                            <tr>
                                <td><span class="rank-circle"><?= $idx + 1; ?></span></td>
                                <td>
                                    <b style="color:var(--ink-900);"><?= htmlspecialchars($m['nama_menu']); ?></b><br>
                                    <small style="color:var(--text-muted); font-size:10px;"><?= htmlspecialchars($m['kategori']); ?></small>
                                </td>
                                <td><b><?= $m['total_terjual']; ?> porsi</b></td>
                                <td style="text-align:right;"><b style="color:var(--red-600);">Rp<?= number_format($m['total_subtotal'], 0, ',', '.'); ?></b></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td><span class="rank-circle">1</span></td>
                                <td>
                                    <b style="color:var(--ink-900);">Paket Ayam Geprek Juara</b><br>
                                    <small style="color:var(--text-muted); font-size:10px;">Pedas Level 3 Favorit</small>
                                </td>
                                <td><b>78 porsi</b></td>
                                <td style="text-align:right;"><b style="color:var(--red-600);">Rp1.950.000</b></td>
                            </tr>
                            <tr>
                                <td><span class="rank-circle" style="background:var(--cream-200);">2</span></td>
                                <td>
                                    <b>Paket Ayam Krispi Gurih</b><br>
                                    <small style="color:var(--text-muted); font-size:10px;">Original Rempah</small>
                                </td>
                                <td><b>54 porsi</b></td>
                                <td style="text-align:right;"><b style="color:var(--red-600);">Rp1.350.000</b></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- BOTTOM ROW: DETAIL TRANSAKSI PENJUALAN TABLE UI-14 -->
        <section class="card-ui">
            <div class="card-header-flex">
                <div>
                    <h3 class="disp">DETAIL TRANSAKSI PENJUALAN</h3>
                    <p>Catatan riwayat transaksi kasir dan QR digital secara real-time</p>
                </div>
                <span class="pill-status pill-tunai">● Total: <?= count($dataPenjualan['daftar_transaksi'] ?? 142); ?> Transaksi</span>
            </div>

            <div class="table-main-wrap">
                <table class="table-main">
                    <thead>
                        <tr>
                            <th>WAKTU</th>
                            <th>ID TRANSAKSI</th>
                            <th>PELANGGAN / METODE PESANAN</th>
                            <th>RINCIAN MENU</th>
                            <th>METODE BAYAR</th>
                            <th style="text-align:right;">TOTAL BAYAR</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($dataPenjualan['daftar_transaksi'])): ?>
                            <?php foreach ($dataPenjualan['daftar_transaksi'] as $trx): ?>
                            <?php
                                $mPesanan = $trx['metode_pesanan'] ?? 'Dine In';
                                $badgePesananClass = 'pill-dinein';
                                if (stripos($mPesanan, 'Take Away') !== false) {
                                    $badgePesananClass = 'pill-takeaway';
                                } else if (stripos($mPesanan, 'Reservasi') !== false) {
                                    $badgePesananClass = 'pill-reservasi';
                                }
                            ?>
                            <tr>
                                <td><b><?= htmlspecialchars($trx['waktu']); ?></b></td>
                                <td><b style="color:var(--red-600);">#<?= htmlspecialchars($trx['id_transaksi_kode']); ?></b></td>
                                <td>
                                    <b><?= htmlspecialchars($trx['nama_pelanggan'] ?? 'Pelanggan Restoran'); ?></b><br>
                                    <span class="pill-status <?= $badgePesananClass; ?>">● <?= htmlspecialchars($mPesanan); ?></span>
                                </td>
                                <td><?= htmlspecialchars($trx['rincian_menu']); ?></td>
                                <td><span class="pill-status <?= ($trx['metode_pembayaran'] ?? '') == 'QRIS' ? 'pill-qris' : 'pill-tunai'; ?>">● <?= htmlspecialchars($trx['metode_pembayaran']); ?></span></td>
                                <td style="text-align:right;"><b style="font-size:14px;">Rp<?= number_format($trx['total_bayar'], 0, ',', '.'); ?></b></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!-- DEFAULT DUMMY ROWS SAMA PERSIS GAMBAR UI-14 -->
                            <tr>
                                <td><b>14:28 WIB</b></td>
                                <td><b style="color:var(--red-600);">#TRX-20251025-0142</b></td>
                                <td>Budi Santoso <br><span class="pill-status pill-dinein">● Dine in</span></td>
                                <td>2x Ayam Geprek Juara, 2x Es Teh Jumbo</td>
                                <td><span class="pill-status pill-qris">● QRIS</span></td>
                                <td style="text-align:right;"><b style="font-size:14px;">Rp104.000</b></td>
                            </tr>
                            <tr>
                                <td><b>14:15 WIB</b></td>
                                <td><b style="color:var(--red-600);">#TRX-20251025-0141</b></td>
                                <td>Siti Rahmawati <br><span class="pill-status pill-takeaway">● Take Away</span></td>
                                <td>1x Paket Ayam Krispi, 1x Es Teh Jumbo</td>
                                <td><span class="pill-status pill-tunai">● Tunai</span></td>
                                <td style="text-align:right;"><b style="font-size:14px;">Rp58.000</b></td>
                            </tr>
                            <tr>
                                <td><b>14:02 WIB</b></td>
                                <td><b style="color:var(--red-600);">#TRX-20251025-0140</b></td>
                                <td>Keluarga Hendra <br><span class="pill-status pill-reservasi">● Reservasi (Lt. 2)</span></td>
                                <td>Paket Keluarga Mantap 6 Pax</td>
                                <td><span class="pill-status pill-qris">● QRIS</span></td>
                                <td style="text-align:right;"><b style="font-size:14px;">Rp400.000</b></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- PAGINATION BAR FOOTER UI-14 -->
                <div class="pagination-bar">
                    <span>Menampilkan 1-10 dari total 142 transaksi</span>
                    <div style="display:flex; gap:6px; align-items:center;">
                        <a href="#" class="page-btn">← Sebelumnya</a>
                        <a href="#" class="page-btn active">1</a>
                        <a href="#" class="page-btn">2</a>
                        <a href="#" class="page-btn">3</a>
                        <span>...</span>
                        <a href="#" class="page-btn">15</a>
                        <a href="#" class="page-btn">Selanjutnya →</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>

<!-- MODAL ANIMATED INPUT KAS FISIK -->
<div class="overlay" id="kasModal" onclick="if(event.target===this) closeModal()">
    <div class="modal-card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
            <h3 class="disp" style="color:var(--red-600); font-size:18px;">INPUT JUMLAH KAS FISIK</h3>
            <button onclick="closeModal()" style="border:0; background:none; font-weight:700; cursor:pointer; font-size:18px; color:var(--text-muted);">✕</button>
        </div>
        
        <form action="index.php?page=simpan-kas" method="POST" style="display:flex; flex-direction:column; gap:16px;">
            <input type="hidden" name="tanggal" value="<?= $tanggalAktif; ?>">
            <p style="font-size:12px; color:var(--text-muted);">Masukkan total uang kas fisik nyata di laci kasir untuk audit selisih otomatis.</p>
            
            <div>
                <label style="font-size:10px; font-weight:700; text-transform:uppercase; display:block; margin-bottom:4px;">JUMLAH UANG KAS FISIK NYATA (RP) *</label>
                <input type="number" name="kas_fisik" class="mc-input" style="width:100%; min-height:44px; border-radius:14px; border:1.5px solid var(--cream-300); padding:0 14px; font-weight:700;" placeholder="2500000" value="<?= $dataKas['kas_fisik'] ?? ''; ?>" required autofocus>
            </div>

            <div style="background:var(--cream-50); border:1.5px solid var(--cream-200); border-radius:14px; padding:12px;">
                <div style="display:flex; justify-content:space-between; font-size:12px; margin-bottom:4px;">
                    <span style="color:var(--text-muted);">Kas Sistem (POS):</span>
                    <b>Rp<?= number_format($dataKas['kas_sistem'] ?? 2500000, 0, ',', '.'); ?></b>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:12px; margin-bottom:8px;">
                    <span style="color:var(--text-muted);">Kas Fisik Kasir:</span>
                    <b>Rp<?= number_format($dataKas['kas_fisik'] ?? 2500000, 0, ',', '.'); ?></b>
                </div>
                <hr style="border:0; border-top:1px dashed var(--cream-300); margin:4px 0;">
                <div style="display:flex; justify-content:space-between; align-items:center; font-size:12px; margin-top:6px;">
                    <b>Hasil Selisih:</b>
                    <span class="pill-status pill-dinein">● <?= $dataKas['status_selisih'] ?? 'Sesuai'; ?> (Rp<?= number_format($dataKas['selisih'] ?? 0, 0, ',', '.'); ?>)</span>
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:8px;">
                <button type="button" class="mc-btn mc-btn--ghost" onclick="closeModal()">BATAL</button>
                <button type="submit" class="mc-btn mc-btn--primary">SIMPAN DATA KAS</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('kasModal').classList.add('on');
}
function closeModal() {
    document.getElementById('kasModal').classList.remove('on');
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});
</script>
</body>
</html>