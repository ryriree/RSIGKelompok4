<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<meta content="web_dashboard" name="shell-type">
<title>Antrean Pesanan Pelayan - Maky Chicken</title>
<link href="https://fonts.googleapis.com" rel="preconnect">
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&amp;family=Titan+One&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
<style>
@layer base{html,body{margin:0;padding:0;}body{overscroll-behavior:none;}main>:first-child{margin-top:0!important;}main>:last-child{margin-bottom:0!important;}}
::-webkit-scrollbar{display:none;}
</style>
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
</head>
<body class="bg-surface-container font-body-md text-on-surface antialiased">

<!-- Sidebar Navigasi Restoran -->
<aside class="fixed left-0 top-0 h-full w-64 bg-red-900 z-50 flex flex-col justify-between py-6 px-4 shadow-[0_10px_28px_rgba(94,10,16,0.24)]">
  <div class="flex flex-col gap-6">
    <div class="flex items-center gap-3 px-2">
      <div class="w-9 h-9 rounded-full bg-secondary-container flex items-center justify-center font-display-md text-primary text-xl shadow-inner">M</div>
      <div class="flex flex-col">
        <span class="font-display-md text-secondary-container leading-none text-lg">MAKY</span>
        <span class="text-[10px] tracking-wider uppercase text-on-primary-container font-semibold">Chicken Floor</span>
      </div>
    </div>
    <div class="h-px w-full bg-primary-container/40 my-1"></div>
    <nav class="flex flex-col gap-2">
      <a class="flex items-center gap-3 px-4 py-3 rounded-full text-surface-container-highest hover:bg-primary-container hover:text-on-primary transition-all font-semibold" href="index.php?action=cetak_struk">
        <span class="material-symbols-outlined text-xl">point_of_sale</span>
        <span>Kasir (POS)</span>
      </a>
      <a class="flex items-center gap-3 px-4 py-3 rounded-full bg-secondary-container text-ink-primary font-bold shadow-md" href="index.php?action=status_pelayan">
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
      <span class="text-xs text-surface-container-lowest font-medium">Tablet Server Active</span>
    </div>
    <span class="material-symbols-outlined text-secondary-fixed text-lg">wifi</span>
  </div>
</aside>

<div class="pl-64">
  <!-- Top Header Bar -->
  <header class="fixed top-0 left-64 right-0 h-16 bg-cream-surface/90 backdrop-blur-xl shadow-sm z-40 flex items-center justify-between px-6">
    <div class="flex items-center gap-3">
      <div class="flex items-center gap-2 bg-surface-container-low px-3 py-1 rounded-full">
        <span class="w-2 h-2 rounded-full bg-yellow-hover"></span>
        <span class="text-xs text-ink-muted uppercase font-bold">Floor Server Station</span>
      </div>
    </div>
    <div class="flex items-center gap-4">
      <div class="flex items-center gap-2 bg-surface-container-lowest px-4 py-1.5 rounded-full shadow-sm">
        <span class="material-symbols-outlined text-ink-muted text-base">schedule</span>
        <span class="text-xs text-ink-primary font-semibold"><?= date('H:i \W\I\B') ?></span>
      </div>
      <div class="flex items-center gap-3 pl-2">
        <div class="text-right hidden sm:block">
          <p class="text-xs font-bold text-ink-primary leading-tight">Andi Wijaya</p>
          <p class="text-[11px] text-ink-muted">Staff Pelayan / Runner</p>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content Area -->
  <main class="relative pt-20 pb-12 px-6 w-full min-h-screen bg-surface-container">
    <div class="flex flex-col w-full space-y-6 max-w-[1400px] mx-auto">
      
      <!-- Top Title Bar & Actions -->
      <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
          <div class="flex items-center gap-2 mb-1">
            <span class="w-2.5 h-2.5 rounded-full bg-primary animate-ping"></span>
            <span class="text-xs uppercase tracking-wider text-red-700 font-bold">KITCHEN &amp; RUNNER DISPLAY SYSTEM</span>
          </div>
          <h1 class="font-display-lg text-3xl text-primary tracking-wide uppercase">ANTREAN PESANAN PELAYAN</h1>
          <p class="text-sm text-ink-muted">Monitor status pesanan makanan &amp; pengantaran meja pelanggan secara real-time</p>
        </div>
        <div class="flex items-center gap-3">
          <button onclick="window.location.reload()" class="w-11 h-11 rounded-full bg-cream-surface flex items-center justify-center text-ink-primary shadow-sm hover:bg-surface-container-high transition" title="Muat Ulang Pesanan" type="button">
            <span class="material-symbols-outlined text-xl">refresh</span>
          </button>
        </div>
      </div>

      <?php
        // Perhitungan Statistik Dinamis dari Database
        $totalProses = 0;
        $totalSelesai = 0;
        if (!empty($antreanPesanan)) {
            foreach ($antreanPesanan as $item) {
                if ($item['status'] === 'Selesai') {
                    $totalSelesai++;
                } else {
                    $totalProses++;
                }
            }
        }
      ?>

      <!-- 4 Stat Metrics Row -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-cream-surface rounded-2xl p-5 shadow-sm flex flex-col justify-between border border-cream-border/50">
          <div class="flex items-center justify-between">
            <span class="text-xs text-ink-muted font-bold tracking-wider uppercase">DALAM PROSES</span>
            <span class="w-8 h-8 rounded-full bg-red-soft flex items-center justify-center text-primary">
              <span class="material-symbols-outlined text-lg">skillet</span>
            </span>
          </div>
          <div class="mt-3 flex items-baseline gap-2">
            <span class="font-display-lg text-3xl text-primary leading-none"><?= $totalProses ?></span>
            <span class="text-xs text-red-700 font-semibold">Pesanan aktif</span>
          </div>
        </div>

        <div class="bg-secondary-container rounded-2xl p-5 shadow-sm flex flex-col justify-between">
          <div class="flex items-center justify-between">
            <span class="text-xs text-on-secondary-container font-bold tracking-wider uppercase">SIAP SAJI / ANTAR</span>
            <span class="w-8 h-8 rounded-full bg-surface-container-lowest/80 flex items-center justify-center text-secondary">
              <span class="material-symbols-outlined text-lg">room_service</span>
            </span>
          </div>
          <div class="mt-3 flex items-baseline gap-2">
            <span class="font-display-lg text-3xl text-ink-primary leading-none"><?= $totalProses ?></span>
            <span class="text-xs text-on-secondary-container font-semibold">Perlu diantar</span>
          </div>
        </div>

        <div class="bg-cream-surface rounded-2xl p-5 shadow-sm flex flex-col justify-between border border-cream-border/50">
          <div class="flex items-center justify-between">
            <span class="text-xs text-ink-muted font-bold tracking-wider uppercase">PESANAN SELESAI</span>
            <span class="w-8 h-8 rounded-full bg-green-100/70 flex items-center justify-center text-success">
              <span class="material-symbols-outlined text-lg">check_circle</span>
            </span>
          </div>
          <div class="mt-3 flex items-baseline gap-2">
            <span class="font-display-lg text-3xl text-success leading-none"><?= $totalSelesai ?></span>
            <span class="text-xs text-success font-semibold">Sudah disajikan</span>
          </div>
        </div>

        <div class="bg-cream-surface rounded-2xl p-5 shadow-sm flex flex-col justify-between border border-cream-border/50">
          <div class="flex items-center justify-between">
            <span class="text-xs text-ink-muted font-bold tracking-wider uppercase">ESTIMASI WAKTU SAJI</span>
            <span class="w-8 h-8 rounded-full bg-blue-soft flex items-center justify-center text-tertiary">
              <span class="material-symbols-outlined text-lg">timer</span>
            </span>
          </div>
          <div class="mt-3 flex items-baseline gap-2">
            <span class="font-display-lg text-3xl text-ink-primary leading-none">10-15</span>
            <span class="text-xs text-ink-primary font-bold">Menit</span>
          </div>
        </div>
      </div>

      <!-- Main Order Cards Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-start">
        <?php if (!empty($antreanPesanan)): ?>
          <?php foreach ($antreanPesanan as $p): ?>
            <?php 
              $isSelesai = ($p['status'] === 'Selesai');
            ?>
            <div class="bg-surface-container-lowest rounded-2xl p-5 shadow-md border-l-8 <?= $isSelesai ? 'border-success' : 'border-secondary-container' ?> flex flex-col justify-between space-y-4 hover:shadow-lg transition-all">
              
              <!-- Card Header -->
              <div class="flex items-start justify-between">
                <div>
                  <div class="flex items-center gap-2">
                    <span class="font-display-md text-2xl text-ink-primary">
                      <?= $p['no_meja'] ? 'MEJA ' . htmlspecialchars($p['no_meja']) : 'TAKE AWAY' ?>
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full bg-surface-container-high text-ink-muted text-xs font-semibold">
                      <?= htmlspecialchars($p['jenis_pesanan'] ?? 'Dine In') ?>
                    </span>
                  </div>
                  <p class="text-xs text-ink-muted mt-1 font-mono">
                    Kode: <strong><?= htmlspecialchars($p['kode_pesanan']) ?></strong>
                  </p>
                </div>
                
                <?php if ($isSelesai): ?>
                  <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-success/15 text-success text-xs font-bold">
                    <span class="w-2 h-2 rounded-full bg-success"></span> Selesai
                  </span>
                <?php else: ?>
                  <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-secondary-fixed text-on-secondary-container text-xs font-bold">
                    <span class="w-2 h-2 rounded-full bg-yellow-hover animate-pulse"></span> Dalam Proses
                  </span>
                <?php endif; ?>
              </div>

              <!-- Item Detail & Notes Box -->
              <div class="space-y-2 py-2 bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                <p class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Rincian Pesanan &amp; Catatan:</p>
                <p class="text-xs text-slate-800 leading-relaxed font-medium">
                  <?= !empty($p['rincian_item']) ? nl2br(htmlspecialchars($p['rincian_item'])) : '<span class="italic text-slate-400">Tidak ada rincian item.</span>' ?>
                </p>
              </div>

              <!-- Total & Waktu -->
              <div class="flex items-center justify-between text-xs text-ink-muted pt-1 border-t border-slate-100">
                <span>Waktu Pesan: <strong><?= date('H:i \W\I\B', strtotime($p['waktu_pesan'] ?? 'now')) ?></strong></span>
                <span class="font-display-md text-primary text-sm font-bold">
                  Rp <?= number_format($p['total_harga'], 0, ',', '.') ?>
                </span>
              </div>

              <!-- Action Button Form -->
              <div class="pt-2">
                <?php if (!$isSelesai): ?>
                  <form action="index.php?action=update_status" method="POST">
                    <input type="hidden" name="id_pesanan" value="<?= $p['id_pesanan'] ?>">
                    <input type="hidden" name="status" value="Selesai">
                    <button type="submit" class="w-full h-11 rounded-full bg-success hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider transition shadow flex items-center justify-center gap-2">
                      <span class="material-symbols-outlined text-lg">check_circle</span>
                      <span>TANDAI SELESAI / SUDAH DIANTAR</span>
                    </button>
                  </form>
                <?php else: ?>
                  <button disabled class="w-full h-11 rounded-full bg-slate-100 text-slate-400 font-bold text-xs uppercase tracking-wider flex items-center justify-center gap-2 cursor-not-allowed border border-slate-200">
                    <span class="material-symbols-outlined text-lg">verified</span>
                    <span>PESANAN SUDAH SELESAI</span>
                  </button>
                <?php endif; ?>
              </div>

            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-span-full py-16 text-center bg-white rounded-2xl shadow-sm text-ink-muted">
            <span class="material-symbols-outlined text-5xl text-slate-300 mb-2">table_restaurant</span>
            <p class="text-base font-semibold">Tidak ada antrean pesanan aktif saat ini.</p>
            <p class="text-xs text-slate-400">Pesanan baru yang dibuat via QR atau Kasir akan otomatis muncul di sini.</p>
          </div>
        <?php endif; ?>
      </div>

    </div>
  </main>
</div>

</body>
</html>