<?php
$dataReservasi = $dataReservasi ?? [];

$kodeBooking = $dataReservasi['kode_booking'] ?? '-';
$namaPemesan = $dataReservasi['nama_pemesan'] ?? '-';
$kontak = $dataReservasi['kontak_pemesanan'] ?? '-';
$tanggal = $dataReservasi['tanggal'] ?? '-';
$slotWaktu = $dataReservasi['slot_waktu'] ?? '-';
$lantai = $dataReservasi['lantai'] ?? '-';
$jumlahOrang = $dataReservasi['jumlah_orang'] ?? 0;
$dp = $dataReservasi['dp'] ?? 400000;
$statusDP = $dataReservasi['status_dp'] ?? 'Lunas';

$fileEvoucher = $dataReservasi['file_evoucher'] ?? null;

function e($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function rupiah($angka) {
    return 'Rp' . number_format((float) $angka, 0, ',', '.');
}

$pembayaranBerhasil = ($statusDP === 'Lunas');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Voucher Reservasi - Maky Chicken</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f6f8;
            margin: 0;
            color: #222;
        }

        nav {
            background: #0b2a5b;
            color: white;
            padding: 18px 30px;
        }

        .container {
            max-width: 700px;
            margin: 35px auto;
            padding: 20px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }

        .judul {
            text-align: center;
            color: #0b2a5b;
        }

        .status {
            text-align: center;
            background: #e6f4ea;
            color: #1e7e34;
            padding: 12px;
            border-radius: 6px;
            font-weight: bold;
            margin: 20px 0;
        }

        .kode {
            text-align: center;
            font-size: 25px;
            font-weight: bold;
            letter-spacing: 2px;
            color: #0b2a5b;
            padding: 15px;
            border: 2px dashed #0b2a5b;
            border-radius: 8px;
            margin: 20px 0;
        }

        .detail {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }

        .label {
            color: #666;
        }

        .nilai {
            font-weight: bold;
            text-align: right;
        }

        .total {
            font-size: 20px;
            color: #0b2a5b;
            font-weight: bold;
        }

        .pesan {
            background: #f0f4fa;
            padding: 15px;
            border-radius: 6px;
            line-height: 1.6;
        }

        .tombol {
            display: inline-block;
            padding: 12px 18px;
            background: #0b2a5b;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }

        .tengah {
            text-align: center;
        }

        @media print {
            nav, .aksi {
                display: none;
            }

            body {
                background: white;
            }

            .container {
                margin: 0 auto;
            }
        }
    </style>
</head>

<body>

<nav>
    <strong>MAKY CHICKEN</strong>
</nav>

<div class="container">

    <?php if ($pembayaranBerhasil): ?>

        <div class="card">

            <h2 class="judul">E-VOUCHER RESERVASI</h2>

            <div class="status">
                Pembayaran DP Berhasil
            </div>

            <p class="tengah">
                Terima kasih telah melakukan reservasi di Maky Chicken.
                Berikut adalah bukti reservasi kamu.
            </p>

            <div class="kode">
                <?= e($kodeBooking); ?>
            </div>

            <h3>Informasi Pemesan</h3>

            <div class="detail">
                <span class="label">Nama Pemesan</span>
                <span class="nilai"><?= e($namaPemesan); ?></span>
            </div>

            <div class="detail">
                <span class="label">Kontak</span>
                <span class="nilai"><?= e($kontak); ?></span>
            </div>

            <h3>Detail Reservasi</h3>

            <div class="detail">
                <span class="label">Tanggal</span>
                <span class="nilai"><?= e($tanggal); ?></span>
            </div>

            <div class="detail">
                <span class="label">Waktu</span>
                <span class="nilai"><?= e($slotWaktu); ?></span>
            </div>

            <div class="detail">
                <span class="label">Lantai</span>
                <span class="nilai"><?= e($lantai); ?></span>
            </div>

            <div class="detail">
                <span class="label">Jumlah Tamu</span>
                <span class="nilai"><?= e($jumlahOrang); ?> orang</span>
            </div>

            <h3>Informasi Pembayaran</h3>

            <div class="detail">
                <span class="label">DP Dibayarkan</span>
                <span class="nilai total"><?= rupiah($dp); ?></span>
            </div>

            <div class="detail">
                <span class="label">Status Pembayaran</span>
                <span class="nilai"><?= e($statusDP); ?></span>
            </div>

            <div class="pesan">
                <strong>Informasi:</strong>
                <p>
                    Silakan tunjukkan e-Voucher ini kepada staff
                    saat datang ke Maky Chicken. Pastikan datang
                    sesuai tanggal dan waktu reservasi.
                </p>
            </div>

            <div class="tengah aksi">
                <button class="tombol" onclick="window.print()">
                    Cetak E-Voucher
                </button>
            </div>

        </div>

    <?php else: ?>

        <div class="card tengah">
            <h2>Pembayaran Belum Terverifikasi</h2>
            <p>E-Voucher belum dapat ditampilkan karena pembayaran DP belum berhasil diverifikasi.</p>
        </div>

    <?php endif; ?>

</div>

</body>
</html>