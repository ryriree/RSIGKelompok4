<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../koneksi.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = $_POST['tanggal'] ?? '';
    $slot_waktu = $_POST['slot_waktu'] ?? '';
    $jumlah_orang = (int)($_POST['jumlah_orang'] ?? 4);
    $nama_pemesan = trim($_POST['nama_pemesan'] ?? '');
    $kontak_pemesanan = trim($_POST['kontak_pemesanan'] ?? '');
    $catatan = trim($_POST['catatan'] ?? '');

    if (empty($tanggal) || empty($slot_waktu) || empty($nama_pemesan) || empty($kontak_pemesanan)) {
        $error = 'Harap isi semua kolom wajib!';
    } else {
        // Simpan sementara ke session sebelum pilih lantai & meja
        $_SESSION['draft_reservasi'] = [
            'kode_booking' => 'RES-' . date('Ymd') . '-' . rand(100, 999),
            'tanggal' => $tanggal,
            'slot_waktu' => $slot_waktu,
            'jumlah_orang' => $jumlah_orang,
            'nama_pemesan' => $nama_pemesan,
            'kontak_pemesanan' => $kontak_pemesanan,
            'catatan' => $catatan
        ];
        header('Location: reservasi_step2.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Step 1 - Jadwal & Tamu | Maky Chicken</title>
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
                        'red-light': '#FBE0DD',
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
<body class="bg-cream-bg text-text-main font-body min-h-screen">
    <!-- HEADER -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-cream-light/95 backdrop-blur-md shadow-sm border-b border-cream-border">
        <div class="h-20 max-w-7xl mx-auto px-6 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-red-brand flex items-center justify-center text-cream-light font-black text-xl shadow-md">M</div>
                <div class="flex flex-col">
                    <span class="font-display text-headline-sm text-red-brand leading-none">MAKY CHICKEN</span>
                    <span class="text-xs text-text-muted uppercase tracking-wider font-semibold">Crispy Fried Perfection</span>
                </div>
            </div>
            <nav class="flex items-center gap-2 bg-cream-bg px-2 py-1.5 rounded-full border border-cream-border">
                <a class="px-5 py-2 rounded-full text-xs font-bold bg-red-brand text-white shadow-md" href="#">Reservasi Meja</a>
                <a class="px-5 py-2 rounded-full text-xs text-text-muted hover:text-red-brand font-semibold" href="#">Menu Spesial</a>
            </nav>
        </div>
    </header>

    <main class="w-full pt-28 pb-16">
        <div class="max-w-7xl mx-auto px-6">
            <!-- STEPPER BAR -->
            <div class="w-full bg-cream-light rounded-2xl p-4 shadow-sm border border-cream-border mb-10">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <div class="flex items-center gap-3 bg-red-brand text-white px-4 py-3 rounded-xl shadow-md">
                        <div class="w-8 h-8 rounded-full bg-cream-light text-red-brand flex items-center justify-center font-display">1</div>
                        <div class="flex flex-col min-w-0">
                            <span class="text-[10px] text-yellow-accent font-bold uppercase tracking-wider">LANGKAH 1</span>
                            <span class="font-display text-sm truncate">Jadwal & Tamu</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 bg-white/60 text-text-muted px-4 py-3 rounded-xl opacity-70">
                        <div class="w-8 h-8 rounded-full bg-cream-bg flex items-center justify-center font-bold">2</div>
                        <div class="flex flex-col min-w-0">
                            <span class="text-[10px] uppercase font-bold">LANGKAH 2</span>
                            <span class="font-bold text-sm truncate">Pilih Meja & Lantai</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 bg-white/60 text-text-muted px-4 py-3 rounded-xl opacity-70">
                        <div class="w-8 h-8 rounded-full bg-cream-bg flex items-center justify-center font-bold">3</div>
                        <div class="flex flex-col min-w-0">
                            <span class="text-[10px] uppercase font-bold">LANGKAH 3</span>
                            <span class="font-bold text-sm truncate">Pre-order Menu</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 bg-white/60 text-text-muted px-4 py-3 rounded-xl opacity-70">
                        <div class="w-8 h-8 rounded-full bg-cream-bg flex items-center justify-center font-bold">4</div>
                        <div class="flex flex-col min-w-0">
                            <span class="text-[10px] uppercase font-bold">LANGKAH 4</span>
                            <span class="font-bold text-sm truncate">Bayar DP & e-Voucher</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MAIN FORM -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-red-brand text-white rounded-3xl p-6 shadow-xl relative overflow-hidden">
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 rounded-full bg-yellow-accent text-text-main text-xs uppercase font-bold">Form Kunjungan</span>
                            <span class="text-xs text-cream-light/80">Draft Sesi Active</span>
                        </div>
                        <h2 class="text-3xl font-display">MAKY RESERVATION</h2>
                        <p class="text-xs text-red-light mt-2">Lengkapi data kunjungan Anda untuk mengunci alokasi meja santap lezat di Maky Chicken.</p>
                    </div>
                    <div class="bg-cream-light rounded-3xl p-6 shadow-sm border border-cream-border space-y-3">
                        <h3 class="font-display text-lg">Informasi Penting</h3>
                        <p class="text-xs text-text-muted">DP sebesar <strong>Rp50.000</strong> akan dipotong langsung dari total tagihan akhir Anda di kasir outlet.</p>
                    </div>
                </div>

                <div class="lg:col-span-8 bg-cream-light rounded-3xl p-8 shadow-lg border border-cream-border">
                    <h1 class="text-2xl font-display text-text-main mb-6">1. PILIH JADWAL & JUMLAH TAMU</h1>
                    
                    <?php if ($error): ?>
                        <div class="p-4 mb-6 bg-red-light text-red-brand rounded-2xl font-bold text-sm flex items-center gap-2">
                            <span class="material-symbols-outlined">warning</span>
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="" class="space-y-6">
                        <input type="hidden" name="slot_waktu" id="input_slot_waktu" value="18:30 WIB">
                        <input type="hidden" name="jumlah_orang" id="input_jumlah_orang" value="4">

                        <div class="space-y-2">
                            <label class="block text-sm font-bold" for="tanggal">Tanggal Kunjungan</label>
                            <input class="w-full h-14 px-5 bg-white text-text-main rounded-2xl border border-cream-border focus:outline-none focus:ring-4 focus:ring-red-brand/10 font-medium" id="tanggal" name="tanggal" type="date" value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold">Waktu Kedatangan (Slot Jam)</label>
                            <div class="grid grid-cols-3 md:grid-cols-6 gap-3">
                                <?php 
                                $slots = ['11:30 WIB', '13:00 WIB', '17:00 WIB', '18:30 WIB', '19:45 WIB', '20:30 WIB'];
                                foreach ($slots as $slot): 
                                    $active = ($slot === '18:30 WIB') ? 'bg-red-brand text-white font-bold shadow-md' : 'bg-white text-text-main hover:bg-cream-bg';
                                ?>
                                    <button class="time-slot-btn py-3 px-2 rounded-2xl text-xs text-center border border-cream-border transition-all <?= $active ?>" type="button" data-value="<?= $slot ?>"><?= $slot ?></button>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold">Jumlah Tamu</label>
                            <div class="flex items-center gap-4 bg-cream-bg p-4 rounded-2xl border border-cream-border">
                                <button class="w-10 h-10 rounded-full bg-white text-text-main flex items-center justify-center font-bold border border-cream-border shadow-sm" id="btn-dec-guest" type="button">-</button>
                                <span class="font-bold text-red-brand text-lg" id="guest-count-display">4 Tamu</span>
                                <button class="w-10 h-10 rounded-full bg-white text-text-main flex items-center justify-center font-bold border border-cream-border shadow-sm" id="btn-inc-guest" type="button">+</button>
                            </div>
                        </div>

                        <div class="space-y-4 pt-2">
                            <label class="block text-sm font-bold">Data Pemesan</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input class="w-full h-14 px-4 bg-white rounded-2xl border border-cream-border focus:outline-none focus:ring-4 focus:ring-red-brand/10 text-sm font-medium" name="nama_pemesan" placeholder="Nama Lengkap Pemesan" type="text" required>
                                <input class="w-full h-14 px-4 bg-white rounded-2xl border border-cream-border focus:outline-none focus:ring-4 focus:ring-red-brand/10 text-sm font-medium" name="kontak_pemesanan" placeholder="Nomor WhatsApp" type="tel" required>
                            </div>
                            <textarea class="w-full p-4 bg-white rounded-2xl border border-cream-border focus:outline-none focus:ring-4 focus:ring-red-brand/10 text-sm font-medium" name="catatan" placeholder="Catatan Khusus (Opsional, contoh: Acara ulang tahun, minta baby chair)" rows="2"></textarea>
                        </div>

                        <button class="w-full py-4 bg-red-brand text-white rounded-full font-bold shadow-lg hover:bg-red-dark active:scale-[0.99] transition-all flex items-center justify-center gap-2" type="submit">
                            LANJUT KE PILIH MEJA & LANTAI
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script>
        const timeBtns = document.querySelectorAll('.time-slot-btn');
        const inputSlot = document.getElementById('input_slot_waktu');
        timeBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                timeBtns.forEach(b => b.className = 'time-slot-btn py-3 px-2 rounded-2xl text-xs text-center border border-cream-border transition-all bg-white text-text-main');
                btn.className = 'time-slot-btn py-3 px-2 rounded-2xl text-xs text-center border border-cream-border transition-all bg-red-brand text-white font-bold shadow-md';
                inputSlot.value = btn.dataset.value;
            });
        });

        let guestCount = 4;
        const display = document.getElementById('guest-count-display');
        const inputGuest = document.getElementById('input_jumlah_orang');
        document.getElementById('btn-dec-guest').onclick = () => { if(guestCount > 1) guestCount--; display.textContent = guestCount + ' Tamu'; inputGuest.value = guestCount; };
        document.getElementById('btn-inc-guest').onclick = () => { guestCount++; display.textContent = guestCount + ' Tamu'; inputGuest.value = guestCount; };
    </script>
</body>
</html>