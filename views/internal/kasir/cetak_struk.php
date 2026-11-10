<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<meta content="web_dashboard" name="shell-type">
<title>POS Kasir - Cetak Struk Pesanan</title>
<link href="https://fonts.googleapis.com" rel="preconnect">
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&amp;family=Titan+One&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script id="tailwind-config">
tailwind.config = {
    darkMode: "class",
    theme: {
      extend: {
        "colors": {
          "outline": "#8f6f6c",
          "surface-container-lowest": "#ffffff",
          "secondary-fixed": "#ffddb0",
          "background": "#fff8f1",
          "inverse-surface": "#363021",
          "primary-fixed-dim": "#ffb3ac",
          "success": "#1E8E4E",
          "secondary": "#805600",
          "cream-border": "#F2D49A",
          "surface-container-low": "#fef2db",
          "ink-primary": "#2A1010",
          "primary-container": "#b3131b",
          "primary-fixed": "#ffdad6",
          "tertiary-container": "#1559a8",
          "red-700": "#8E0F18",
          "surface-container-high": "#f3e7d0",
          "on-secondary-container": "#6b4800",
          "red-900": "#5E0A10",
          "red-soft": "#FBE0DD",
          "surface-variant": "#ede1cb",
          "surface-dim": "#e4d9c3",
          "error-container": "#ffdad6",
          "surface-container": "#f9edd6",
          "on-background": "#201b0d",
          "tertiary": "#004283",
          "on-error": "#ffffff",
          "yellow-hover": "#F28C0F",
          "error": "#ba1a1a",
          "surface-container-highest": "#ede1cb",
          "inverse-on-surface": "#fbf0d9",
          "ink-muted": "#6B4A45",
          "on-tertiary": "#ffffff",
          "on-primary-container": "#ffc3bd",
          "on-error-container": "#93000a",
          "on-tertiary-fixed": "#001b3d",
          "surface": "#fff8f1",
          "on-primary": "#ffffff",
          "surface-tint": "#ba1a1f",
          "primary": "#8b000d",
          "on-tertiary-container": "#bbd2ff",
          "on-surface": "#201b0d",
          "surface-bright": "#fff8f1",
          "inverse-primary": "#ffb3ac",
          "secondary-fixed-dim": "#ffba44",
          "on-tertiary-fixed-variant": "#00468b",
          "outline-variant": "#e4beba",
          "on-secondary-fixed": "#281800",
          "blue-soft": "#DCEBFA",
          "secondary-container": "#ffb21e",
          "tertiary-fixed": "#d6e3ff",
          "tertiary-fixed-dim": "#a9c7ff",
          "on-secondary-fixed-variant": "#614000",
          "on-secondary": "#ffffff",
          "cream-surface": "#FFFAF0",
          "on-surface-variant": "#5b403d",
          "on-primary-fixed": "#410003",
          "on-primary-fixed-variant": "#93000f"
        },
        "fontFamily": {
          "body-md": ["Poppins", "sans-serif"],
          "display-lg": ["Titan One", "cursive"],
          "display-md": ["Titan One", "cursive"]
        }
      }
    }
}
</script>
<style>
@layer base{html,body{margin:0;padding:0;}body{overscroll-behavior:none;}}
::-webkit-scrollbar{display:none;}
@media print {
  body * { visibility: hidden; }
  #printable-receipt, #printable-receipt * { visibility: visible; }
  #printable-receipt {
    position: absolute;
    left: 0;
    top: 0;
    width: 80mm;
    margin: 0;
    padding: 10px;
    background: #ffffff !important;
    box-shadow: none !important;
  }
}
</style>
</head>
<body class="bg-surface-container font-body-md text-on-surface antialiased">

<!-- Sidebar Navigasi POS -->
<aside class="fixed left-0 top-0 h-full w-64 bg-red-900 z-50 flex flex-col justify-between py-6 px-4 shadow-[0_10px_28px_rgba(94,10,16,0.24)]">
  <div class="flex flex-col gap-6">
    <div class="flex items-center gap-3 px-2">
      <div class="w-9 h-9 rounded-full bg-secondary-container flex items-center justify-center font-display-md text-primary text-xl shadow-inner">M</div>
      <div class="flex flex-col">
        <span class="font-display-md text-secondary-container leading-none text-lg">MAKY</span>
        <span class="text-[10px] tracking-wider uppercase text-on-primary-container font-semibold">Chicken POS</span>
      </div>
    </div>
    <div class="h-px w-full bg-primary-container/40 my-1"></div>
    <nav class="flex flex-col gap-2">
      <a class="flex items-center gap-3 px-4 py-3 rounded-full bg-secondary-container text-ink-primary font-bold shadow-md" href="index.php?action=cetak_struk">
        <span class="material-symbols-outlined text-xl">point_of_sale</span>
        <span>Kasir (POS Struk)</span>
      </a>
      <a class="flex items-center gap-3 px-4 py-3 rounded-full text-surface-container-highest hover:bg-primary-container hover:text-on-primary transition-all font-semibold" href="index.php?action=status_pelayan">
        <span class="material-symbols-outlined text-xl">table_restaurant</span>
        <span>Antrean Pelayan</span>
      </a>
      <a class="flex items-center gap-3 px-4 py-3 rounded-full text-surface-container-highest hover:bg-primary-container hover:text-on-primary transition-all font-semibold" href="index.php?action=melihat_menu">
        <span class="material-symbols-outlined text-xl">restaurant_menu</span>
        <span>Katalog Menu</span>
      </a>
    </nav>
  </div>
  <div class="p-3 bg-red-700/60 rounded-xl flex items-center justify-between">
    <div class="flex items-center gap-2">
      <div class="w-2.5 h-2.5 rounded-full bg-success"></div>
      <span class="text-xs text-surface-container-lowest font-medium">Database Connected</span>
    </div>
    <span class="material-symbols-outlined text-secondary-fixed text-lg">wifi</span>
  </div>
</aside>

<div class="pl-64">
  <!-- Top Header -->
  <header class="fixed top-0 left-64 right-0 h-16 bg-cream-surface/90 backdrop-blur-xl shadow-sm z-40 flex items-center justify-between px-6">
    <div class="flex items-center gap-3">
      <div class="flex items-center gap-2 bg-surface-container-low px-3 py-1 rounded-full">
        <span class="w-2 h-2 rounded-full bg-yellow-hover"></span>
        <span class="text-xs text-ink-muted uppercase font-bold">Outlet Utama #01</span>
      </div>
    </div>
    <div class="flex items-center gap-4">
      <div class="flex items-center gap-2 bg-surface-container-lowest px-4 py-1.5 rounded-full shadow-sm">
        <span class="material-symbols-outlined text-ink-muted text-base">schedule</span>
        <span class="text-xs text-ink-primary font-semibold"><?= date('H:i \W\I\B') ?></span>
      </div>
      <div class="flex items-center gap-3 pl-2">
        <div class="text-right hidden sm:block">
          <p class="text-xs font-bold text-ink-primary leading-tight">Siti Rahmawati</p>
          <p class="text-[11px] text-ink-muted">Staff Kasir</p>
        </div>
      </div>
    </div>
  </header>

  <!-- Content Workspace -->
  <main class="relative pt-20 pb-12 px-6 w-full min-h-screen bg-surface-container">
    <div class="space-y-6 max-w-[1400px] w-full mx-auto">
      
      <!-- Title Bar -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 class="font-display-lg text-3xl text-primary tracking-wide">CETAK STRUK PESANAN</h1>
          <p class="text-sm text-ink-muted">Pilih pesanan pelanggan dari antrean database untuk meninjau dan mencetak struk.</p>
        </div>
        <div class="flex items-center gap-3">
          <div class="flex items-center gap-2.5 px-4 py-2 bg-surface-container-lowest rounded-full shadow-sm">
            <span class="relative flex h-2.5 w-2.5">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-success opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-success"></span>
            </span>
            <span class="text-xs text-success font-bold tracking-wide">Thermal Printer Ready</span>
          </div>
          <button onclick="window.location.reload()" class="p-2.5 bg-surface-container-lowest hover:bg-surface-variant text-ink-primary rounded-full shadow-sm transition" title="Muat Ulang Data">
            <span class="material-symbols-outlined text-lg">sync</span>
          </button>
        </div>
      </div>

      <!-- Main Dual Panel Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- LEFT PANEL: Order Queue List (7 cols) -->
        <div class="lg:col-span-7 flex flex-col gap-4">
          <div class="bg-surface-container-lowest p-3 rounded-xl shadow-sm flex items-center justify-between">
            <span class="text-xs font-bold text-ink-primary px-3">Riwayat Transaksi Terbuka</span>
            <span class="text-xs text-ink-muted">Total: <?= count($riwayat ?? []) ?> Transaksi</span>
          </div>

          <div class="flex flex-col gap-3.5">
            <?php if (!empty($riwayat)): ?>
              <?php foreach ($riwayat as $p): ?>
                <?php 
                  $isSelected = ($strukAktif && $strukAktif['pesanan']['id_pesanan'] == $p['id_pesanan']);
                  $isLunas = isset($p['metode']) || strtolower($p['status']) === 'selesai' || strtolower($p['status']) === 'lunas';
                ?>
                <a href="index.php?action=cetak_struk&id=<?= $p['id_pesanan'] ?>" 
                   class="block cursor-pointer relative p-5 rounded-xl transition-all duration-200 border <?= $isSelected ? 'bg-white border-primary shadow-md ring-2 ring-primary/20' : 'bg-surface-container-lowest border-transparent hover:border-slate-300 shadow-sm' ?>">
                  
                  <div class="flex items-start justify-between gap-4 mb-2">
                    <div class="flex items-center gap-3">
                      <span class="px-3.5 py-1 rounded-full <?= $isSelected ? 'bg-primary text-white' : 'bg-surface-variant text-ink-primary' ?> font-display-md text-xs uppercase tracking-wider">
                        <?= $p['no_meja'] ? 'MEJA ' . htmlspecialchars($p['no_meja']) : htmlspecialchars($p['jenis_pesanan'] ?? 'Take Away') ?>
                      </span>
                      <span class="text-xs text-ink-muted flex items-center gap-1">
                        <span class="material-symbols-outlined text-xs">schedule</span>
                        <?= date('H:i \W\I\B', strtotime($p['waktu_pesan'] ?? 'now')) ?>
                      </span>
                      <span class="px-2 py-0.5 rounded bg-slate-100 text-[11px] font-mono font-bold text-slate-600">
                        <?= htmlspecialchars($p['kode_pesanan']) ?>
                      </span>
                    </div>

                    <?php if ($isLunas): ?>
                      <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-success/15 text-success text-xs font-bold">
                        <span class="w-2 h-2 rounded-full bg-success"></span> Lunas
                      </span>
                    <?php else: ?>
                      <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-secondary-fixed text-on-secondary-container text-xs font-bold">
                        <span class="w-2 h-2 rounded-full bg-yellow-hover"></span> Belum Bayar
                      </span>
                    <?php endif; ?>
                  </div>

                  <div class="flex items-center justify-between gap-4">
                    <div class="min-w-0 flex-1">
                      <p class="text-xs text-ink-muted mt-1">
                        Metode: <strong class="text-ink-primary"><?= htmlspecialchars($p['metode'] ?? 'Tunai') ?></strong>
                      </p>
                    </div>
                    <div class="text-right flex-shrink-0">
                      <div class="font-display-md text-lg text-primary">
                        Rp <?= number_format($p['total_harga'], 0, ',', '.') ?>
                      </div>
                    </div>
                  </div>
                </a>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="p-8 text-center bg-white rounded-xl text-ink-muted">
                Belum ada data pesanan di database.
              </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- RIGHT PANEL: Thermal Receipt Live Preview (5 cols) -->
        <div class="lg:col-span-5 flex flex-col gap-4 sticky top-20">
          <div class="flex items-center justify-between px-1">
            <div class="flex items-center gap-2">
              <span class="material-symbols-outlined text-primary text-lg">receipt_long</span>
              <span class="text-xs font-bold uppercase tracking-wider text-ink-primary">Pratinjau Fisik Struk</span>
            </div>
            <span class="text-xs text-ink-muted bg-surface-container-low px-2.5 py-1 rounded-full">Format Kertas 80mm</span>
          </div>

          <?php if ($strukAktif): ?>
            <?php 
              $pesanan = $strukAktif['pesanan'];
              $items = $strukAktif['items'];
            ?>
            <!-- Realistic Thermal Paper Visual -->
            <div id="printable-receipt" class="relative bg-white rounded-2xl shadow-xl overflow-hidden p-6 font-mono text-ink-primary border border-slate-200">
              
              <!-- Header Struk -->
              <div class="text-center space-y-1 mb-4">
                <h2 class="font-display-lg text-2xl text-primary leading-tight">MAKY CHICKEN</h2>
                <p class="text-xs font-bold font-sans text-ink-primary">RESTORAN AYAM GEPREK OTENTIK</p>
                <p class="text-[11px] text-ink-muted font-sans">Jl. Veteran No. 10, Malang</p>
                <p class="text-[11px] text-ink-muted font-sans">Telp: (0341) 555-899 | NPWP: 89.231.002.3-421.000</p>
                <div class="pt-2">
                  <span class="inline-block px-3 py-0.5 rounded-full bg-slate-100 font-sans text-[11px] font-bold uppercase">
                    STRUK PEMBAYARAN
                  </span>
                </div>
              </div>

              <div class="border-b-2 border-dashed border-slate-300 my-3"></div>

              <!-- Metadata Transaksi -->
              <div class="space-y-1 font-sans text-xs text-slate-700">
                <div class="flex justify-between">
                  <span class="text-slate-500">No. Nota:</span>
                  <span class="font-bold font-mono"><?= htmlspecialchars($pesanan['kode_transaksi'] ?? $pesanan['kode_pesanan']) ?></span>
                </div>
                <div class="flex justify-between">
                  <span class="text-slate-500">No. Pesanan:</span>
                  <span class="font-bold font-mono text-primary">#<?= htmlspecialchars($pesanan['id_pesanan']) ?></span>
                </div>
                <div class="flex justify-between">
                  <span class="text-slate-500">Meja / Tipe:</span>
                  <span class="font-bold"><?= $pesanan['no_meja'] ? 'Meja ' . htmlspecialchars($pesanan['no_meja']) : htmlspecialchars($pesanan['jenis_pesanan'] ?? 'Take Away') ?></span>
                </div>
                <div class="flex justify-between">
                  <span class="text-slate-500">Waktu:</span>
                  <span><?= date('d/m/Y H:i \W\I\B', strtotime($pesanan['waktu_bayar'] ?? $pesanan['waktu_pesan'] ?? 'now')) ?></span>
                </div>
                <div class="flex justify-between">
                  <span class="text-slate-500">Kasir:</span>
                  <span>Siti Rahmawati</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-slate-500">Metode Bayar:</span>
                  <span class="font-bold text-slate-900"><?= htmlspecialchars($pesanan['metode'] ?? 'Tunai') ?></span>
                </div>
              </div>

              <div class="border-b-2 border-dashed border-slate-300 my-3"></div>

              <!-- Item Pesanan -->
              <div class="space-y-2.5 font-sans text-xs">
                <?php foreach ($items as $it): ?>
                  <div class="flex justify-between items-start">
                    <div class="flex-1 pr-2">
                      <div class="font-bold text-slate-800"><?= htmlspecialchars($it['nama_menu']) ?> x<?= $it['jumlah'] ?></div>
                      <?php if (!empty($it['catatan'])): ?>
                        <div class="text-[11px] text-amber-700 italic">Catatan: <?= htmlspecialchars($it['catatan']) ?></div>
                      <?php endif; ?>
                      <div class="text-[11px] text-slate-500">@ Rp <?= number_format($it['harga'], 0, ',', '.') ?></div>
                    </div>
                    <div class="font-bold font-mono text-slate-900">Rp <?= number_format($it['subtotal'], 0, ',', '.') ?></div>
                  </div>
                <?php endforeach; ?>
              </div>

              <div class="border-b-2 border-dashed border-slate-300 my-3"></div>

              <!-- Calculations -->
              <div class="space-y-1 font-sans text-xs text-slate-600">
                <div class="flex justify-between">
                  <span>Subtotal:</span>
                  <span class="font-mono font-medium text-slate-900">Rp <?= number_format($pesanan['total_harga'], 0, ',', '.') ?></span>
                </div>
                <div class="flex justify-between">
                  <span>Pajak Restoran (PB1):</span>
                  <span class="font-mono text-slate-900">Termasuk</span>
                </div>
              </div>

              <div class="border-b-2 border-dashed border-slate-400 my-3"></div>

              <div class="flex justify-between items-baseline py-1">
                <span class="font-display-md text-lg text-slate-900">TOTAL</span>
                <span class="font-display-lg text-2xl text-primary font-bold">
                  Rp <?= number_format($pesanan['total_harga'], 0, ',', '.') ?>
                </span>
              </div>

              <div class="space-y-1 font-sans text-xs text-slate-600 pt-1">
                <div class="flex justify-between">
                  <span>Tunai/Bayar:</span>
                  <span class="font-mono font-medium text-slate-900">Rp <?= number_format($pesanan['uang_diterima'] ?? $pesanan['total_harga'], 0, ',', '.') ?></span>
                </div>
                <div class="flex justify-between">
                  <span>Kembalian:</span>
                  <span class="font-mono font-bold text-emerald-600">Rp <?= number_format($pesanan['kembalian'] ?? 0, 0, ',', '.') ?></span>
                </div>
              </div>

              <!-- Barcode Mockup -->
              <div class="pt-5 text-center">
                <div class="inline-block bg-slate-900 text-white text-[10px] px-4 py-1 font-mono tracking-widest rounded">
                  *<?= htmlspecialchars($pesanan['kode_pesanan']) ?>*
                </div>
                <p class="text-[11px] font-sans font-bold text-slate-800 mt-3">Terima kasih atas kunjungan Anda!</p>
                <p class="text-[10px] font-sans text-slate-500 italic">"Ayam krispi juicy bumbu meresap, pedas nagih!"</p>
              </div>

            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col gap-2.5 pt-2">
              <button onclick="window.print()" class="w-full py-3.5 px-6 rounded-full bg-primary hover:bg-primary-container text-white font-bold text-xs uppercase tracking-wider flex items-center justify-center gap-2 shadow-lg transition">
                <span class="material-symbols-outlined text-xl">print</span>
                <span>CETAK STRUK SEKARANG</span>
              </button>
            </div>
          <?php else: ?>
            <div class="bg-white p-8 rounded-2xl text-center text-ink-muted shadow-sm">
              <span class="material-symbols-outlined text-4xl text-slate-300 mb-2">receipt</span>
              <p>Pilih transaksi di panel kiri untuk melihat pratinjau dan mencetak struk.</p>
            </div>
          <?php endif; ?>

        </div>
      </div>

    </div>
  </main>
</div>

</body>
</html>