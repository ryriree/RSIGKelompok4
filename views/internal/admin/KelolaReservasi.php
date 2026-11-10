<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sertakan file koneksi (naik 3 level folder dari views/internal/admin ke root)
require_once '../../../koneksi.php';

$notifSukses = $_SESSION['notif_sukses'] ?? null;
$notifError  = $_SESSION['notif_error'] ?? null;
unset($_SESSION['notif_sukses'], $_SESSION['notif_error']);

// 1. PROSES TAMBAH RESERVASI MANUAL (OFFLINE / WALK-IN)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_reservasi'])) {
    $nama     = trim($_POST['nama_pemesan'] ?? '');
    $kontak   = trim($_POST['kontak_pemesanan'] ?? '');
    $tanggal  = $_POST['tanggal'] ?? '';
    $slot     = $_POST['slot_waktu'] ?? '18:30 WIB';
    $lantai   = (int)($_POST['lantai'] ?? 1);
    $pax      = (int)($_POST['jumlah_orang'] ?? 2);
    $statusDp = $_POST['status_dp'] ?? 'Lunas';
    $dpNominal = 50000.00;

    if (empty($nama) || empty($kontak) || empty($tanggal)) {
        $_SESSION['notif_error'] = "Harap lengkapi semua bidang form reservasi!";
    } else {
        try {
            $kodeBooking = 'RES-' . date('Ymd') . '-' . rand(100, 999);
            $stmt = $pdo->prepare("INSERT INTO reservasi (kode_booking, nama_pemesan, kontak_pemesanan, tanggal, slot_waktu, lantai, jumlah_orang, dp, total, status, status_dp) 
                VALUES (:kode, :nama, :kontak, :tgl, :slot, :lantai, :pax, :dp, :total, 'Confirmed', :status_dp)");
            
            $stmt->execute([
                ':kode'      => $kodeBooking,
                ':nama'      => $nama,
                ':kontak'    => $kontak,
                ':tgl'       => $tanggal,
                ':slot'      => $slot,
                ':lantai'    => $lantai,
                ':pax'       => $pax,
                ':dp'        => $dpNominal,
                ':total'     => $dpNominal,
                ':status_dp' => $statusDp
            ]);

            $_SESSION['notif_sukses'] = "Reservasi offline berhasil dibuat dengan Kode: $kodeBooking";
            header("Location: admin_reservasi.php");
            exit;
        } catch (PDOException $e) {
            $_SESSION['notif_error'] = "Gagal menyimpan reservasi: " . $e->getMessage();
        }
    }
}

// 2. PROSES AKSI (BATALKAN RESERVASI / UPDATE STATUS DP)
if (isset($_GET['action'])) {
    $idRes = (int)($_GET['id'] ?? 0);
    
    if ($_GET['action'] === 'batalkan' && $idRes > 0) {
        $stmt = $pdo->prepare("UPDATE reservasi SET status = 'Dibatalkan', status_dp = 'Batal' WHERE id_reservasi = :id");
        $stmt->execute([':id' => $idRes]);
        $_SESSION['notif_sukses'] = "Reservasi telah berhasil dibatalkan.";
        header("Location: admin_reservasi.php");
        exit;
    }
    
    if ($_GET['action'] === 'lunas_dp' && $idRes > 0) {
        $stmt = $pdo->prepare("UPDATE reservasi SET status_dp = 'Lunas', status = 'Confirmed' WHERE id_reservasi = :id");
        $stmt->execute([':id' => $idRes]);
        $_SESSION['notif_sukses'] = "Status DP berhasil diperbarui menjadi Lunas.";
        header("Location: admin_reservasi.php");
        exit;
    }
}

// 3. SEARCH & FETCH DATA RESERVASI FROM DATABASE
$search = trim($_GET['q'] ?? '');
if (!empty($search)) {
    $stmt = $pdo->prepare("SELECT * FROM reservasi WHERE nama_pemesan LIKE :q OR kode_booking LIKE :q ORDER BY id_reservasi DESC");
    $stmt->execute([':q' => "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM reservasi ORDER BY id_reservasi DESC");
}
$listReservasi = $stmt->fetchAll();

// 4. STATISTIK RINGKAS
$totalAll       = count($listReservasi);
$totalConfirmed = count(array_filter($listReservasi, fn($r) => $r['status'] === 'Confirmed'));
$totalPending   = count(array_filter($listReservasi, fn($r) => $r['status'] === 'Pending'));
$totalBatal     = count(array_filter($listReservasi, fn($r) => $r['status'] === 'Dibatalkan'));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UC-02 Kelola Data Reservasi Pelanggan - Maky Chicken</title>

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
                        'yellow-accent': '#FFB21E',
                        'green-status': '#1E8E4E'
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

<body class="bg-cream-bg text-text-main font-body min-h-screen p-4 sm:p-8">

    <div class="max-w-7xl mx-auto space-y-6">

        <!-- HEADER SECTION -->
        <div class="bg-red-brand text-cream-light p-6 sm:p-8 rounded-3xl shadow-xl flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-red-dark rounded-full blur-2xl opacity-50"></div>
            
            <div class="relative z-10 space-y-1">
                <div class="inline-flex items-center gap-2 bg-yellow-accent text-text-main px-3 py-1 rounded-full text-xs font-bold shadow-sm">
                    <span class="material-symbols-outlined text-sm">table_restaurant</span>
                    MODUL AKTOR: ADMIN / STAFF
                </div>
                <h1 class="font-display text-2xl sm:text-3xl tracking-wide">
                    MANAJEMEN RESERVASI MEJA (UC-02)
                </h1>
                <p class="text-xs text-cream-light/90">
                    Kelola reservasi pelanggan, entri pesanan walk-in/offline, serta konfirmasi status DP.
                </p>
            </div>

            <div class="relative z-10 flex gap-2">
                <button onclick="toggleModalTambah()" class="px-5 py-3 bg-yellow-accent text-text-main font-bold rounded-full text-xs shadow-md hover:bg-amber-400 transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm">add_circle</span>
                    + RESERVASI OFFLINE
                </button>
                <a href="dashboard_staff.php" class="px-4 py-3 bg-red-dark text-white rounded-full text-xs font-bold hover:bg-black/20 transition-all flex items-center">
                    &larr; DASHBOARD
                </a>
            </div>
        </div>

        <!-- NOTIFIKASI -->
        <?php if ($notifSukses): ?>
            <div class="p-4 bg-green-100 border border-green-300 text-green-800 text-xs font-bold rounded-2xl flex items-center gap-2">
                <span class="material-symbols-outlined">check_circle</span>
                <?= htmlspecialchars($notifSukses) ?>
            </div>
        <?php endif; ?>
        <?php if ($notifError): ?>
            <div class="p-4 bg-red-light border border-red-300 text-red-brand text-xs font-bold rounded-2xl flex items-center gap-2">
                <span class="material-symbols-outlined">error</span>
                <?= htmlspecialchars($notifError) ?>
            </div>
        <?php endif; ?>

        <!-- METRIC CARDS -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-cream-light p-5 rounded-2xl border border-cream-border shadow-sm">
                <span class="text-[11px] font-bold text-text-muted uppercase tracking-wider block">Total Reservasi</span>
                <span class="font-display text-2xl text-text-main mt-1 block"><?= $totalAll ?></span>
            </div>
            <div class="bg-cream-light p-5 rounded-2xl border border-cream-border shadow-sm">
                <span class="text-[11px] font-bold text-green-status uppercase tracking-wider block">Terkonfirmasi</span>
                <span class="font-display text-2xl text-green-status mt-1 block"><?= $totalConfirmed ?></span>
            </div>
            <div class="bg-cream-light p-5 rounded-2xl border border-cream-border shadow-sm">
                <span class="text-[11px] font-bold text-yellow-600 uppercase tracking-wider block">Menunggu Konfirmasi</span>
                <span class="font-display text-2xl text-yellow-600 mt-1 block"><?= $totalPending ?></span>
            </div>
            <div class="bg-cream-light p-5 rounded-2xl border border-cream-border shadow-sm">
                <span class="text-[11px] font-bold text-red-brand uppercase tracking-wider block">Dibatalkan</span>
                <span class="font-display text-2xl text-red-brand mt-1 block"><?= $totalBatal ?></span>
            </div>
        </div>

        <!-- SEARCH & FILTER BAR -->
        <div class="bg-cream-light p-4 rounded-2xl border border-cream-border shadow-sm flex flex-col sm:flex-row justify-between items-center gap-3">
            <form method="GET" action="" class="w-full sm:w-96 relative flex items-center">
                <span class="material-symbols-outlined absolute left-3 text-text-muted text-lg">search</span>
                <input 
                    type="text" 
                    name="q" 
                    value="<?= htmlspecialchars($search) ?>" 
                    placeholder="Cari Kode Booking / Nama Pemesan..." 
                    class="w-full bg-white border border-cream-border rounded-full py-2.5 pl-10 pr-4 text-xs focus:outline-none focus:border-red-brand"
                >
            </form>
            <div class="text-xs text-text-muted">
                Menampilkan <strong><?= count($listReservasi) ?></strong> data reservasi
            </div>
        </div>

        <!-- TABEL RESERVASI -->
        <div class="bg-cream-light rounded-3xl border border-cream-border shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-red-brand text-cream-light text-[11px] uppercase font-bold tracking-wider">
                        <tr>
                            <th class="p-4">Kode Booking</th>
                            <th class="p-4">Nama Pemesan</th>
                            <th class="p-4">Kontak (WA)</th>
                            <th class="p-4">Jadwal & Waktu</th>
                            <th class="p-4 text-center">Lantai / Meja</th>
                            <th class="p-4 text-center">Tamu</th>
                            <th class="p-4">Status DP</th>
                            <th class="p-4">Status Booking</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cream-border text-xs">
                        <?php if (empty($listReservasi)): ?>
                            <tr>
                                <td colspan="9" class="p-8 text-center text-text-muted italic">
                                    Belum ada data reservasi yang tercatat dalam database.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($listReservasi as $r): ?>
                                <tr class="hover:bg-amber-50/60 transition-colors">
                                    <td class="p-4 font-bold text-red-brand font-mono">
                                        <?= htmlspecialchars($r['kode_booking']) ?>
                                    </td>
                                    <td class="p-4 font-bold text-text-main">
                                        <?= htmlspecialchars($r['nama_pemesan']) ?>
                                    </td>
                                    <td class="p-4 text-text-muted">
                                        <?= htmlspecialchars($r['kontak_pemesanan']) ?>
                                    </td>
                                    <td class="p-4">
                                        <div class="font-semibold"><?= htmlspecialchars($r['tanggal']) ?></div>
                                        <div class="text-[10px] text-text-muted"><?= htmlspecialchars($r['slot_waktu']) ?></div>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="px-2.5 py-1 bg-cream-bg rounded-lg font-bold border border-cream-border">
                                            Lantai <?= htmlspecialchars($r['lantai']) ?>
                                        </span>
                                    </td>
                                    <td class="p-4 text-center font-bold">
                                        <?= htmlspecialchars($r['jumlah_orang']) ?> Pax
                                    </td>
                                    <td class="p-4">
                                        <?php if ($r['status_dp'] === 'Lunas'): ?>
                                            <span class="px-2.5 py-1 bg-green-100 text-green-700 font-bold rounded-full text-[10px] inline-flex items-center gap-1">
                                                <span class="material-symbols-outlined text-xs">check_circle</span> LUNAS
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2.5 py-1 bg-amber-100 text-amber-800 font-bold rounded-full text-[10px] inline-flex items-center gap-1">
                                                <span class="material-symbols-outlined text-xs">pending</span> PENDING
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-4">
                                        <?php if ($r['status'] === 'Confirmed'): ?>
                                            <span class="px-2.5 py-1 bg-blue-100 text-blue-700 font-bold rounded-full text-[10px]">Confirmed</span>
                                        <?php elseif ($r['status'] === 'Dibatalkan'): ?>
                                            <span class="px-2.5 py-1 bg-red-100 text-red-700 font-bold rounded-full text-[10px]">Dibatalkan</span>
                                        <?php else: ?>
                                            <span class="px-2.5 py-1 bg-gray-100 text-gray-700 font-bold rounded-full text-[10px]"><?= htmlspecialchars($r['status']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <?php if ($r['status_dp'] !== 'Lunas' && $r['status'] !== 'Dibatalkan'): ?>
                                                <a href="?action=lunas_dp&id=<?= $r['id_reservasi'] ?>" title="Tandai DP Lunas" class="p-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                                    <span class="material-symbols-outlined text-sm">payments</span>
                                                </a>
                                            <?php endif; ?>

                                            <?php if ($r['status'] !== 'Dibatalkan'): ?>
                                                <a href="?action=batalkan&id=<?= $r['id_reservasi'] ?>" onclick="return confirm('Apakah Anda yakin ingin membatalkan reservasi ini?')" title="Batalkan Reservasi" class="p-1.5 bg-red-brand text-white rounded-lg hover:bg-red-dark">
                                                    <span class="material-symbols-outlined text-sm">cancel</span>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-[10px] text-text-muted italic">-</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL FORM TAMBAH RESERVASI OFFLINE / WALK-IN -->
    <div id="modalTambah" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
        <div class="bg-cream-light w-full max-w-lg rounded-3xl p-6 sm:p-8 border border-cream-border shadow-2xl space-y-4 relative">
            <button onclick="toggleModalTambah()" class="absolute top-5 right-5 text-text-muted hover:text-red-brand">
                <span class="material-symbols-outlined">close</span>
            </button>

            <div class="space-y-1">
                <h3 class="font-display text-xl text-red-brand">INPUT RESERVASI OFFLINE</h3>
                <p class="text-xs text-text-muted">Entri reservasi langsung di outlet (walk-in atau telepon).</p>
            </div>

            <form method="POST" action="" class="space-y-4 pt-2">
                <input type="hidden" name="tambah_reservasi" value="1">

                <div class="space-y-1">
                    <label class="block text-xs font-bold">Nama Pemesan *</label>
                    <input type="text" name="nama_pemesan" required class="w-full bg-white border border-cream-border rounded-xl p-3 text-xs focus:outline-none focus:border-red-brand" placeholder="Masukkan nama pemesan">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="block text-xs font-bold">No. WhatsApp *</label>
                        <input type="tel" name="kontak_pemesanan" required class="w-full bg-white border border-cream-border rounded-xl p-3 text-xs focus:outline-none focus:border-red-brand" placeholder="08xxxxxxxx">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-bold">Tanggal Kunjungan *</label>
                        <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required class="w-full bg-white border border-cream-border rounded-xl p-3 text-xs focus:outline-none focus:border-red-brand">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div class="space-y-1">
                        <label class="block text-xs font-bold">Slot Waktu</label>
                        <select name="slot_waktu" class="w-full bg-white border border-cream-border rounded-xl p-3 text-xs focus:outline-none focus:border-red-brand">
                            <option value="11:30 WIB">11:30 WIB</option>
                            <option value="13:00 WIB">13:00 WIB</option>
                            <option value="17:00 WIB">17:00 WIB</option>
                            <option value="18:30 WIB" selected>18:30 WIB</option>
                            <option value="19:45 WIB">19:45 WIB</option>
                            <option value="20:30 WIB">20:30 WIB</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-bold">Lantai</label>
                        <select name="lantai" class="w-full bg-white border border-cream-border rounded-xl p-3 text-xs focus:outline-none focus:border-red-brand">
                            <option value="1">Lantai 1</option>
                            <option value="2">Lantai 2</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-bold">Jumlah Tamu</label>
                        <input type="number" name="jumlah_orang" value="4" min="1" class="w-full bg-white border border-cream-border rounded-xl p-3 text-xs focus:outline-none focus:border-red-brand">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold">Status DP (Uang Muka Rp50.000)</label>
                    <select name="status_dp" class="w-full bg-white border border-cream-border rounded-xl p-3 text-xs focus:outline-none focus:border-red-brand">
                        <option value="Lunas" selected>LUNAS (Bayar Tunai/QRIS)</option>
                        <option value="Pending">PENDING (Bayar Nanti)</option>
                    </select>
                </div>

                <div class="pt-3">
                    <button type="submit" class="w-full py-3.5 bg-red-brand text-white font-bold rounded-full text-xs shadow-lg hover:bg-red-dark transition-all">
                        SIMPAN RESERVASI BARU
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT MODAL -->
    <script>
        function toggleModalTambah() {
            const modal = document.getElementById('modalTambah');
            modal.classList.toggle('hidden');
        }
    </script>
</body>
</html>