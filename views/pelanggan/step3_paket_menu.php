<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../koneksi.php';

if (!isset($_SESSION['draft_reservasi'])) {
    header('Location: reservasi_step1.php');
    exit;
}

// Ambil Menu dari DB db_restoran
$stmtMenu = $pdo->query("SELECT * FROM menu WHERE status_tersedia = TRUE ORDER BY kategori ASC");
$daftar_menu = $stmtMenu->fetchAll();

// Ambil Paket dari DB db_restoran
$stmtPaket = $pdo->query("SELECT * FROM paket_menu");
$daftar_paket = $stmtPaket->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['draft_reservasi']['cart'] = $_POST['cart'] ?? [];
    header('Location: reservasi_step4.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Step 3 - Pre-order Menu | Maky Chicken</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Titan+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'red-brand': '#B3131B',
                        'red-dark': '#8E0F18',
                        'cream-bg': '#FFF3DC',
                        'cream-light': '#FFFAF0',
                        'cream-border': '#F2D49A',
                        'text-main': '#2A1010',
                        'text-muted': '#6B4A45',
                        'yellow-accent': '#FFB21E'
                    },
                    fontFamily: {
                        headline: ['Epilogue'],
                        body: ['Plus Jakarta Sans'],
                        display: ['Titan One']
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-cream-bg text-text-main font-body min-h-screen pt-10 pb-16">
    <div class="max-w-7xl mx-auto px-6">
        <!-- STEPPER BAR -->
        <div class="w-full bg-cream-light rounded-2xl p-4 shadow-sm border border-cream-border mb-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div class="flex items-center gap-3 bg-white/60 text-text-muted px-4 py-3 rounded-xl opacity-70">
                    <div class="w-8 h-8 rounded-full bg-cream-bg flex items-center justify-center font-bold">1</div>
                    <div class="flex flex-col min-w-0"><span class="text-[10px] font-bold uppercase">LANGKAH 1</span><span class="font-bold text-sm truncate">Jadwal & Tamu</span></div>
                </div>
                <div class="flex items-center gap-3 bg-white/60 text-text-muted px-4 py-3 rounded-xl opacity-70">
                    <div class="w-8 h-8 rounded-full bg-cream-bg flex items-center justify-center font-bold">2</div>
                    <div class="flex flex-col min-w-0"><span class="text-[10px] font-bold uppercase">LANGKAH 2</span><span class="font-bold text-sm truncate">Pilih Meja</span></div>
                </div>
                <div class="flex items-center gap-3 bg-red-brand text-white px-4 py-3 rounded-xl shadow-md">
                    <div class="w-8 h-8 rounded-full bg-cream-light text-red-brand flex items-center justify-center font-display">3</div>
                    <div class="flex flex-col min-w-0"><span class="text-[10px] text-yellow-accent font-bold uppercase tracking-wider">LANGKAH 3</span><span class="font-display text-sm truncate">Pre-order Menu</span></div>
                </div>
                <div class="flex items-center gap-3 bg-white/60 text-text-muted px-4 py-3 rounded-xl opacity-70">
                    <div class="w-8 h-8 rounded-full bg-cream-bg flex items-center justify-center font-bold">4</div>
                    <div class="flex flex-col min-w-0"><span class="text-[10px] font-bold uppercase">LANGKAH 4</span><span class="font-bold text-sm truncate">Bayar DP & e-Voucher</span></div>
                </div>
            </div>
        </div>

        <form method="POST" action="">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <div class="lg:col-span-8 space-y-6">
                    <h1 class="text-2xl font-display text-text-main">3. PILIH PAKET / MENU PRE-ORDER</h1>

                    <!-- PAKET MENU RESERVASI -->
                    <?php if (!empty($daftar_paket)): ?>
                        <div class="space-y-3">
                            <h2 class="font-display text-lg text-red-brand">Paket Hemat Kunjungan</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <?php foreach ($daftar_paket as $pkt): ?>
                                    <div class="bg-cream-light p-5 rounded-2xl border border-cream-border shadow-sm flex flex-col justify-between">
                                        <div>
                                            <span class="px-2 py-0.5 bg-yellow-accent text-text-main text-[10px] font-bold rounded-md uppercase">Paket Pilihan</span>
                                            <h3 class="font-display text-lg mt-2"><?= htmlspecialchars($pkt['nama_paket']) ?></h3>
                                            <p class="text-xs text-text-muted mt-1"><?= htmlspecialchars($pkt['deskripsi']) ?></p>
                                        </div>
                                        <div class="mt-4 pt-3 border-t border-cream-border flex items-center justify-between">
                                            <span class="font-bold text-red-brand text-sm">Rp<?= number_format($pkt['harga'], 0, ',', '.') ?></span>
                                            <input type="number" name="cart[paket_<?= $pkt['id_paket'] ?>]" min="0" value="0" class="w-16 p-2 bg-white border border-cream-border rounded-xl text-center font-bold text-sm">
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- MENU SATUAN -->
                    <div class="space-y-3 pt-4">
                        <h2 class="font-display text-lg text-red-brand">Menu Satuan Makanan & Minuman</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            <?php foreach ($daftar_menu as $m): ?>
                                <div class="bg-cream-light p-4 rounded-2xl border border-cream-border shadow-sm flex flex-col justify-between">
                                    <div>
                                        <h3 class="font-display text-sm text-text-main"><?= htmlspecialchars($m['nama_menu']) ?></h3>
                                        <p class="text-[11px] text-text-muted mt-1 line-clamp-2"><?= htmlspecialchars($m['deskripsi']) ?></p>
                                    </div>
                                    <div class="mt-4 flex items-center justify-between border-t border-cream-border pt-2">
                                        <span class="font-bold text-red-brand text-xs">Rp<?= number_format($m['harga'], 0, ',', '.') ?></span>
                                        <input type="number" name="cart[menu_<?= $m['id_menu'] ?>]" min="0" value="0" class="w-14 p-1 bg-white border border-cream-border rounded-lg text-center font-bold text-xs">
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- RIGHT ACTION SIDEBAR -->
                <div class="lg:col-span-4 bg-cream-light p-6 rounded-3xl border border-cream-border shadow-lg space-y-6 sticky top-28">
                    <h3 class="font-display text-lg text-text-main">Ringkasan DP Reservasi</h3>
                    <div class="p-4 bg-yellow-accent/20 rounded-2xl border border-yellow-accent/40 space-y-2">
                        <span class="text-xs text-text-muted block">Total Deposit (DP) Meja:</span>
                        <span class="font-display text-2xl text-red-brand">Rp50.000</span>
                        <p class="text-[10px] text-text-muted">*DP dipotong saat pelunasan di kasir.</p>
                    </div>

                    <div class="flex flex-col gap-3">
                        <button type="submit" class="w-full py-4 bg-red-brand text-white rounded-full font-bold text-xs shadow-lg hover:bg-red-dark transition-all flex items-center justify-center gap-2">
                            LANJUT BAYAR DP & VOUCHER
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </button>
                        <a href="reservasi_step2.php" class="w-full py-3 text-center text-red-brand font-bold text-xs hover:underline">
                            &larr; KEMBALI KE DENAH MEJA
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>
</html>