<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../koneksi.php';

if (!isset($_SESSION['draft_reservasi'])) {
    header('Location: reservasi_step1.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['draft_reservasi']['lantai'] = (int)($_POST['lantai'] ?? 1);
    $_SESSION['draft_reservasi']['no_meja'] = (int)($_POST['no_meja'] ?? 8);
    header('Location: reservasi_step3.php');
    exit;
}

$draft = $_SESSION['draft_reservasi'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Step 2 - Pilih Meja & Lantai | Maky Chicken</title>
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
                <div class="flex items-center gap-3 bg-red-brand text-white px-4 py-3 rounded-xl shadow-md">
                    <div class="w-8 h-8 rounded-full bg-cream-light text-red-brand flex items-center justify-center font-display">2</div>
                    <div class="flex flex-col min-w-0"><span class="text-[10px] text-yellow-accent font-bold uppercase tracking-wider">LANGKAH 2</span><span class="font-display text-sm truncate">Pilih Meja & Lantai</span></div>
                </div>
                <div class="flex items-center gap-3 bg-white/60 text-text-muted px-4 py-3 rounded-xl opacity-70">
                    <div class="w-8 h-8 rounded-full bg-cream-bg flex items-center justify-center font-bold">3</div>
                    <div class="flex flex-col min-w-0"><span class="text-[10px] font-bold uppercase">LANGKAH 3</span><span class="font-bold text-sm truncate">Pre-order Menu</span></div>
                </div>
                <div class="flex items-center gap-3 bg-white/60 text-text-muted px-4 py-3 rounded-xl opacity-70">
                    <div class="w-8 h-8 rounded-full bg-cream-bg flex items-center justify-center font-bold">4</div>
                    <div class="flex flex-col min-w-0"><span class="text-[10px] font-bold uppercase">LANGKAH 4</span><span class="font-bold text-sm truncate">Bayar DP & e-Voucher</span></div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- RINGKASAN KIRI -->
            <div class="lg:col-span-4 bg-cream-light p-6 rounded-3xl border border-cream-border shadow-sm space-y-4">
                <h3 class="font-display text-lg text-red-brand border-b border-cream-border pb-2">Detail Pemesanan</h3>
                <div class="space-y-2 text-xs text-text-muted">
                    <p><strong>Nama Pemesan:</strong> <?= htmlspecialchars($draft['nama_pemesan']) ?></p>
                    <p><strong>Tanggal:</strong> <?= htmlspecialchars($draft['tanggal']) ?></p>
                    <p><strong>Waktu:</strong> <?= htmlspecialchars($draft['slot_waktu']) ?></p>
                    <p><strong>Jumlah Tamu:</strong> <?= htmlspecialchars($draft['jumlah_orang']) ?> Tamu</p>
                </div>
            </div>

            <!-- DENAH MEJA RIGHT -->
            <div class="lg:col-span-8 bg-cream-light p-8 rounded-3xl border border-cream-border shadow-lg space-y-6">
                <h1 class="text-2xl font-display text-text-main">2. PILIH LANTAI & POSISI MEJA</h1>
                
                <form method="POST" action="" class="space-y-6">
                    <input type="hidden" name="lantai" id="input_lantai" value="1">
                    <input type="hidden" name="no_meja" id="input_no_meja" value="8">

                    <!-- Pilihan Lantai -->
                    <div class="flex gap-4">
                        <button type="button" class="px-6 py-2.5 rounded-full bg-red-brand text-white font-bold text-xs shadow-sm">Lantai 1 (Indoor AC)</button>
                        <button type="button" class="px-6 py-2.5 rounded-full bg-cream-bg text-text-muted font-bold text-xs border border-cream-border">Lantai 2 (Outdoor Smoking Area)</button>
                    </div>

                    <!-- Layout Grid Meja -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-2">
                        <?php for ($i = 1; $i <= 8; $i++): 
                            $isSelected = ($i === 8);
                            $bgClass = $isSelected ? 'bg-red-brand text-white border-red-dark shadow-md' : 'bg-white text-text-main hover:border-red-brand border-cream-border';
                        ?>
                            <div class="meja-card p-5 rounded-2xl border transition-all cursor-pointer text-center space-y-2 <?= $bgClass ?>" data-meja="<?= $i ?>">
                                <span class="material-symbols-outlined text-2xl">table_restaurant</span>
                                <h4 class="font-display text-lg">MEJA 0<?= $i ?></h4>
                                <span class="text-[10px] block opacity-80">Kapasitas 4 Pax</span>
                            </div>
                        <?php endfor; ?>
                    </div>

                    <div class="flex justify-between items-center pt-6 border-t border-cream-border">
                        <a href="reservasi_step1.php" class="px-6 py-3 rounded-full text-red-brand font-bold text-xs hover:underline flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">arrow_back</span> KEMBALI
                        </a>
                        <button type="submit" class="px-8 py-4 bg-red-brand text-white rounded-full font-bold text-xs shadow-lg hover:bg-red-dark transition-all flex items-center gap-2">
                            LANJUT PRE-ORDER MENU
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>