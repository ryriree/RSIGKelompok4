
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Data dari Controller
$dataReservasi = $dataReservasi ?? [];
$dataPesanan = $dataPesanan ?? [];
$hasilBayar = $hasilBayar ?? null;

$kodeBooking = $dataReservasi['kode_booking']
    ?? $dataReservasi['kodeBooking']
    ?? '-';

$namaPemesan = $dataReservasi['nama_pemesan']
    ?? $dataReservasi['namaPemesan']
    ?? '-';

$tanggal = $dataReservasi['tanggal'] ?? '-';
$slotWaktu = $dataReservasi['slot_waktu'] ?? '-';
$jumlahOrang = $dataReservasi['jumlah_orang'] ?? '-';
$lantai = $dataReservasi['lantai'] ?? '-';

$daftarItem = $dataPesanan['daftarItem']
    ?? $dataPesanan['detail']
    ?? [];

$subtotal = $dataPesanan['subtotal'] ?? 0;

// DP wajib sesuai Modul 2 FN
$nominalDP = 400000;

$statusPembayaran = $dataReservasi['status_dp']
    ?? $dataReservasi['status_pembayaran']
    ?? 'Menunggu Pembayaran';

$statusReservasi = $dataReservasi['status'] ?? '';

$pembayaranTerverifikasi =
    ($statusPembayaran === 'Lunas') ||
    ($statusReservasi === 'Confirmed');

$qrImage = $qrisImage ?? null;

$waktuBatas = $waktuBatas ?? null;

$notifSukses = $_SESSION['notif_sukses'] ?? null;
$notifError = $_SESSION['notif_error'] ?? null;

unset($_SESSION['notif_sukses'], $_SESSION['notif_error']);

if (!function_exists('rupiah')) {
    function rupiah($angka) {
        return 'Rp' . number_format((float)$angka, 0, ',', '.');
    }
}

if (!function_exists('esc')) {
    function esc($value) {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Bayar DP & E-Voucher - Maky Chicken</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f6f8;
            color: #252525;
        }

        nav {
            background: #0b2a5b;
            color: white;
            padding: 18px 7%;
        }

        .container {
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
        }

        .card {
            background: white;
            padding: 24px;
            border-radius: 10px;
            border: 1px solid #e3e3e3;
            margin-bottom: 20px;
        }

        h2, h3 {
            color: #0b2a5b;
        }

        .stepper {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            gap: 8px;
        }

        .step {
            flex: 1;
            text-align: center;
            padding: 12px 5px;
            border-radius: 7px;
            background: #e8eaf0;
            color: #666;
            font-size: 13px;
        }

        .step.active {
            background: #0b2a5b;
            color: white;
        }

        .step.done {
            background: #dce9df;
            color: #24633a;
        }

        .kode {
            font-size: 22px;
            font-weight: bold;
            color: #0b2a5b;
        }

        .status {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 20px;
            background: #fff1d6;
            color: #956000;
            font-size: 13px;
            font-weight: bold;
        }

        .detail {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .detail-item {
            padding: 12px;
            background: #f7f8fa;
            border-radius: 7px;
        }

        .label {
            color: #777;
            font-size: 13px;
            margin-bottom: 6px;
        }

        .value {
            font-weight: bold;
        }

        .item-menu {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .total {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            font-size: 17px;
            font-weight: bold;
        }

        .dp {
            background: #eef3fb;
            padding: 18px;
            border-radius: 8px;
            margin-top: 15px;
        }

        .dp strong {
            font-size: 24px;
            color: #0b2a5b;
        }

        .qris {
            text-align: center;
            padding: 20px;
            border: 1px dashed #0b2a5b;
            border-radius: 8px;
        }

        .qris img {
            width: 210px;
            max-width: 100%;
            margin: 15px auto;
        }

        .button {
            display: inline-block;
            padding: 12px 18px;
            background: #0b2a5b;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .button-secondary {
            background: white;
            color: #0b2a5b;
            border: 1px solid #0b2a5b;
        }

        .notification {
            padding: 13px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .success {
            background: #e6f4ea;
            color: #24633a;
        }

        .error {
            background: #fdecea;
            color: #b3261e;
        }

        .voucher {
            border: 2px dashed #0b2a5b;
            padding: 22px;
            text-align: center;
            border-radius: 10px;
        }

        .barcode {
            font-size: 30px;
            letter-spacing: 5px;
            margin: 20px 0;
        }

        .timer {
            color: #b3261e;
            font-weight: bold;
        }

        @media (max-width: 600px) {
            .detail {
                grid-template-columns: 1fr;
            }

            .step {
                font-size: 11px;
            }
        }
    </style>
</head>

<body>

<nav>
    <strong>MAKY CHICKEN</strong>
</nav>

<div class="container">

    <?php if ($notifSukses): ?>
        <div class="notification success">
            <?= esc($notifSukses); ?>
        </div>
    <?php endif; ?>

    <?php if ($notifError): ?>
        <div class="notification error">
            <?= esc($notifError); ?>
        </div>
    <?php endif; ?>

    <!-- Indikator Langkah Reservasi -->
    <div class="stepper">
        <div class="step done">1. Jadwal & Tamu</div>
        <div class="step done">2. Lantai & Meja</div>
        <div class="step done">3. Pre-order</div>
        <div class="step active">4. Pembayaran</div>
    </div>

    <!-- Ringkasan Reservasi -->
    <div class="card">
        <h2>Bayar DP & E-Voucher</h2>

        <p>Kode Reservasi</p>
        <div class="kode"><?= esc($kodeBooking); ?></div>

        <p>
            Status:
            <span class="status">
                <?= esc($pembayaranTerverifikasi
                    ? 'Pembayaran Lunas'
                    : 'Menunggu Pembayaran'); ?>
            </span>
        </p>

        <hr>

        <h3>Detail Reservasi Meja</h3>

        <div class="detail">
            <div class="detail-item">
                <div class="label">Nama Pemesan</div>
                <div class="value"><?= esc($namaPemesan); ?></div>
            </div>

            <div class="detail-item">
                <div class="label">Tanggal Reservasi</div>
                <div class="value"><?= esc($tanggal); ?></div>
            </div>

            <div class="detail-item">
                <div class="label">Waktu Reservasi</div>
                <div class="value"><?= esc($slotWaktu); ?></div>
            </div>

            <div class="detail-item">
                <div class="label">Jumlah Tamu</div>
                <div class="value"><?= esc($jumlahOrang); ?> Orang</div>
            </div>

            <div class="detail-item">
                <div class="label">Lantai</div>
                <div class="value">Lantai <?= esc($lantai); ?></div>
            </div>
        </div>
    </div>

    <!-- Daftar Pre-order -->
    <div class="card">
        <h3>Pre-order Menu Terdaftar</h3>

        <?php if (!empty($daftarItem)): ?>

            <?php foreach ($daftarItem as $item): ?>
                <?php
                    $nama = $item['nama']
                        ?? $item['nama_paket']
                        ?? 'Menu';

                    $jumlah = (int)($item['jumlah']
                        ?? $item['qty']
                        ?? 1);

                    $harga = (float)($item['harga'] ?? 0);
                    $totalItem = $harga * $jumlah;
                ?>

                <div class="item-menu">
                    <span>
                        <?= esc($nama); ?> x <?= $jumlah; ?>
                    </span>

                    <strong><?= rupiah($totalItem); ?></strong>
                </div>
            <?php endforeach; ?>

        <?php else: ?>
            <p>Belum ada detail menu yang ditampilkan.</p>
        <?php endif; ?>

        <div class="total">
            <span>Subtotal Pre-order</span>
            <span><?= rupiah($subtotal); ?></span>
        </div>

        <div class="dp">
            <p>Deposit Uang Muka (DP Wajib)</p>
            <strong><?= rupiah($nominalDP); ?></strong>

            <p>
                DP akan diperhitungkan sebagai bagian dari tagihan
                saat pelanggan melakukan pembayaran di kasir.
            </p>
        </div>

        <div class="total">
            <span>Total yang Dibayar Sekarang</span>
            <span><?= rupiah($nominalDP); ?></span>
        </div>
    </div>

    <?php if (!$pembayaranTerverifikasi): ?>

        <!-- Pembayaran QRIS -->
        <div class="card">
            <h3>Pembayaran DP melalui QRIS</h3>

            <p>
                Silakan lakukan pembayaran sesuai nominal DP
                yang tertera.
            </p>

            <div class="qris">

                <?php if (!empty($qrImage)): ?>
                    <img src="<?= esc($qrImage); ?>" alt="QRIS Pembayaran">
                <?php else: ?>
                    <p>
                        QRIS belum tersedia. QR harus diberikan
                        oleh sistem pembayaran.
                    </p>
                <?php endif; ?>

                <h3><?= rupiah($nominalDP); ?></h3>

                <?php if (!empty($waktuBatas)): ?>
                    <p class="timer">
                        Batas pembayaran: <?= esc($waktuBatas); ?>
                    </p>
                <?php endif; ?>

                <form method="POST" action="">
                    <input type="hidden" name="kode_booking"
                           value="<?= esc($kodeBooking); ?>">

                    <button class="button" type="submit"
                            name="aksi" value="verifikasi_pembayaran">
                        Cek Status Pembayaran
                    </button>
                </form>
            </div>
        </div>

    <?php else: ?>

        <!-- E-Voucher setelah pembayaran terverifikasi -->
        <div class="card">
            <div class="voucher">
                <h2>E-VOUCHER RESERVASI</h2>
                <p>MAKY CHICKEN</p>

                <hr>

                <h3><?= esc($kodeBooking); ?></h3>

                <p>Nama: <?= esc($namaPemesan); ?></p>
                <p>Tanggal: <?= esc($tanggal); ?></p>
                <p>Waktu: <?= esc($slotWaktu); ?></p>
                <p>Lantai: <?= esc($lantai); ?></p>
                <p>Jumlah Tamu: <?= esc($jumlahOrang); ?> Orang</p>

                <div class="barcode">
                    ||| || ||| || |||
                </div>

                <p>Pembayaran DP telah terverifikasi.</p>
                <strong>Reservasi Terkonfirmasi</strong>
            </div>

            <form method="POST" action="">
                <input type="hidden" name="kode_booking"
                       value="<?= esc($kodeBooking); ?>">

                <button class="button" type="submit"
                        name="aksi" value="unduh_evoucher">
                    Unduh E-Voucher
                </button>
            </form>
        </div>

    <?php endif; ?>

    <a class="button button-secondary" href="javascript:history.back()">
        Kembali
    </a>

</div>

</body>
</html>
