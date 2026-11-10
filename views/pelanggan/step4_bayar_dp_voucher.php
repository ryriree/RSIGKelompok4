<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../koneksi.php';

if (!isset($_SESSION['draft_reservasi'])) {
    header('Location: reservasi_step1.php');
    exit;
}

$draft = $_SESSION['draft_reservasi'];

// Eksekusi Simpan Reservasi Ke Database
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bayar_dp'])) {
    try {
        $pdo->beginTransaction();

        // 1. Simpan Ke Tabel reservasi
        $stmtRes = $pdo->prepare("INSERT INTO reservasi (kode_booking, nama_pemesan, kontak_pemesanan, tanggal, slot_waktu, lantai, jumlah_orang, dp, total, status, status_dp) 
            VALUES (:kode, :nama, :kontak, :tgl, :slot, :lantai, :jml, 50000.00, 50000.00, 'Confirmed', 'Lunas')");
        
        $stmtRes->execute([
            ':kode'   => $draft['kode_booking'],
            ':nama'   => $draft['nama_pemesan'],
            ':kontak' => $draft['kontak_pemesanan'],
            ':tgl'    => $draft['tanggal'],
            ':slot'   => $draft['slot_waktu'],
            ':lantai' => $draft['lantai'] ?? 1,
            ':jml'    => $draft['jumlah_orang']
        ]);
        
        $id_reservasi = $pdo->lastInsertId();

        // 2. Simpan Catatan Pembayaran DP
        $stmtPay = $pdo->prepare("INSERT INTO pembayaran (kode_transaksi, id_reservasi, metode, total, status, waktu_bayar) 
            VALUES (:kode_tr, :id_res, 'QRIS', 50000.00, 'Lunas', NOW())");
        $stmtPay->execute([
            ':kode_tr' => 'TRX-' . date('YmdHis'),
            ':id_res'  => $id_reservasi
        ]);

        $pdo->commit();
        
        // Simpan info sukses untuk halaman e-voucher final
        $_SESSION['booking_sukses'] = $draft;
        unset($_SESSION['draft_reservasi']);
        
    } catch (Exception $e) {
        $pdo->rollBack();
        $errorMsg = "Gagal memproses transaksi: " . $e->getMessage();
    }
}

$bookingSukses = $_SESSION['booking_sukses'] ?? null;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Step 4 - e-Voucher Reservasi | Maky Chicken</title>
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
<body class="bg-cream-bg text-text-main font-body min-h-screen pt-10 pb-16 flex items-center justify-center">
    <div class="max-w-xl w-full px-6">

        <?php if (!$bookingSukses): ?>
            <!-- FORM BAYAR DP (BEFORE PAID) -->
            <div class="bg-cream-light rounded-3xl p-8 border border-cream-border shadow-xl space-y-6">
                <div class="text-center space-y-2">
                    <span class="px-3 py-1 bg-yellow-accent text-text-main text-xs font-bold rounded-full uppercase">Langkah Terakhir</span>
                    <h1 class="text-2xl font-display text-red-brand">PEMBAYARAN DP RESERVASI</h1>
                    <p class="text-xs text-text-muted">Scan QRIS di bawah ini untuk menyelesaikan konfirmasi tempat</p>
                </div>

                <div class="p-4 bg-white rounded-2xl border border-cream-border text-center space-y-3">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=MAKY-CHICKEN-DP" alt="QRIS" class="mx-auto border p-2 rounded-xl">
                    <p class="font-display text-xl text-red-brand">Rp50.000</p>
                    <p class="text-[11px] text-text-muted">Maky Chicken QRIS Official</p>
                </div>

                <form method="POST" action="">
                    <button type="submit" name="bayar_dp" value="1" class="w-full py-4 bg-red-brand text-white font-bold rounded-full text-sm shadow-lg hover:bg-red-dark transition-all flex items-center justify-center gap-2">
                        KONFIRMASI PEMBAYARAN & TERBITKAN E-VOUCHER
                        <span class="material-symbols-outlined">verified</span>
                    </button>
                </form>
            </div>

        <?php else: ?>
            <!-- STRUK E-VOUCHER (AFTER PAID) -->
            <div class="bg-cream-light rounded-3xl p-8 border-2 border-red-brand shadow-2xl space-y-6 text-center">
                <div class="w-16 h-16 rounded-full bg-green-100 text-green-700 flex items-center justify-center mx-auto">
                    <span class="material-symbols-outlined text-4xl">check_circle</span>
                </div>
                
                <div>
                    <span class="text-xs text-green-700 font-bold uppercase tracking-wider">RESERVASI CONFIRMED</span>
                    <h1 class="text-3xl font-display text-red-brand mt-1"><?= htmlspecialchars($bookingSukses['kode_booking']) ?></h1>
                    <p class="text-xs text-text-muted mt-1">Tunjukkan e-Voucher ini kepada kasir/pelayan saat tiba di restoran.</p>
                </div>

                <div class="p-5 bg-cream-bg rounded-2xl border border-cream-border text-left space-y-2 text-xs">
                    <p><strong>Nama Pemesan:</strong> <?= htmlspecialchars($bookingSukses['nama_pemesan']) ?></p>
                    <p><strong>Tanggal & Waktu:</strong> <?= htmlspecialchars($bookingSukses['tanggal']) ?> - <?= htmlspecialchars($bookingSukses['slot_waktu']) ?></p>
                    <p><strong>Jumlah Tamu:</strong> <?= htmlspecialchars($bookingSukses['jumlah_orang']) ?> Tamu</p>
                    <p><strong>Lokasi Meja:</strong> Lantai <?= htmlspecialchars($bookingSukses['lantai'] ?? 1) ?> (Meja 08)</p>
                    <p><strong>Status DP:</strong> <span class="text-green-700 font-bold">LUNAS (Rp50.000)</span></p>
                </div>

                <a href="reservasi_step1.php" class="block w-full py-3.5 bg-red-brand text-white font-bold rounded-full text-xs shadow-md hover:bg-red-dark">
                    SELESAI / BUAT RESERVASI BARU
                </a>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>