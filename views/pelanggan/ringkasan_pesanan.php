
<?php
$dataReservasi = $dataReservasi ?? [];
$detailPaket = $detailPaket ?? [];

$namaPemesan = $dataReservasi['nama_pemesan'] ?? '-';
$kontak = $dataReservasi['kontak_pemesanan'] ?? '-';
$tanggal = $dataReservasi['tanggal'] ?? '-';
$slotWaktu = $dataReservasi['slot_waktu'] ?? '-';
$lantai = $dataReservasi['lantai'] ?? '-';
$jumlahOrang = $dataReservasi['jumlah_orang'] ?? 0;

$subtotal = 0;

foreach ($detailPaket as $paket) {
    $subtotal += (float) ($paket['subtotal'] ?? 0);
}

$serviceFee = (float) ($dataReservasi['service_fee'] ?? 0);
$dp = 400000;
$total = $subtotal + $serviceFee;

$formatRupiah = function ($angka) {
    return 'Rp' . number_format((float) $angka, 0, ',', '.');
};
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ringkasan Reservasi - Maky Chicken</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            background: #f5f6fa;
            color: #252b3b;
        }

        .navbar {
            background: #17233f;
            color: white;
            padding: 20px 8%;
            font-size: 21px;
            font-weight: bold;
        }

        .container {
            max-width: 750px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 4px 15px rgba(0,0,0,.06);
        }

        h2 {
            color: #17233f;
            margin-top: 0;
        }

        .section {
            margin-top: 28px;
        }

        .section h3 {
            color: #17233f;
            font-size: 17px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 12px;
        }

        .info-row, .total-row {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            padding: 10px 0;
            font-size: 14px;
        }

        .info-row span:first-child {
            color: #777;
        }

        .menu-item {
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }

        .menu-name {
            font-weight: bold;
        }

        .menu-detail {
            display: flex;
            justify-content: space-between;
            color: #777;
            font-size: 14px;
            margin-top: 7px;
        }

        .total-section {
            background: #f0f3f9;
            padding: 18px;
            border-radius: 9px;
            margin-top: 20px;
        }

        .total-row.grand {
            border-top: 1px solid #d5d9e2;
            margin-top: 8px;
            padding-top: 15px;
            font-weight: bold;
            color: #17233f;
        }

        .dp-info {
            background: #fff7e6;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 14px;
            line-height: 1.6;
        }

        .buttons {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }

        button, .back {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
        }

        button {
            background: #17233f;
            color: white;
        }

        .back {
            background: #e9edf5;
            color: #17233f;
        }

        button:hover {
            background: #263c68;
        }

        .empty {
            color: #777;
            font-size: 14px;
        }
    </style>
</head>

<body>

<div class="navbar">
    Maky Chicken
</div>

<div class="container">
    <div class="card">

        <h2>Ringkasan Reservasi</h2>
        <p>Periksa kembali detail reservasi sebelum melanjutkan pembayaran.</p>

        <div class="section">
            <h3>Informasi Pemesan</h3>

            <div class="info-row">
                <span>Nama Pemesan</span>
                <strong><?= htmlspecialchars($namaPemesan, ENT_QUOTES, 'UTF-8') ?></strong>
            </div>

            <div class="info-row">
                <span>Kontak</span>
                <strong><?= htmlspecialchars($kontak, ENT_QUOTES, 'UTF-8') ?></strong>
            </div>
        </div>

        <div class="section">
            <h3>Detail Reservasi</h3>

            <div class="info-row">
                <span>Tanggal</span>
                <strong><?= htmlspecialchars($tanggal, ENT_QUOTES, 'UTF-8') ?></strong>
            </div>

            <div class="info-row">
                <span>Waktu</span>
                <strong><?= htmlspecialchars($slotWaktu, ENT_QUOTES, 'UTF-8') ?></strong>
            </div>

            <div class="info-row">
                <span>Lantai</span>
                <strong><?= htmlspecialchars((string) $lantai, ENT_QUOTES, 'UTF-8') ?></strong>
            </div>

            <div class="info-row">
                <span>Jumlah Tamu</span>
                <strong><?= (int) $jumlahOrang ?> orang</strong>
            </div>
        </div>

        <div class="section">
            <h3>Paket Pre-order</h3>

            <?php if (!empty($detailPaket)): ?>

                <?php foreach ($detailPaket as $paket): ?>

                    <?php
                    $namaPaket = $paket['nama_paket'] ?? 'Paket Menu';
                    $qty = (int) ($paket['qty'] ?? 0);
                    $hargaSubtotal = (float) ($paket['subtotal'] ?? 0);
                    ?>

                    <div class="menu-item">
                        <div class="menu-name">
                            <?= htmlspecialchars($namaPaket, ENT_QUOTES, 'UTF-8') ?>
                        </div>

                        <div class="menu-detail">
                            <span><?= $qty ?> paket</span>
                            <strong><?= $formatRupiah($hargaSubtotal) ?></strong>
                        </div>
                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <p class="empty">Belum ada paket yang dipilih.</p>

            <?php endif; ?>
        </div>

        <div class="total-section">

            <div class="total-row">
                <span>Subtotal Paket</span>
                <strong><?= $formatRupiah($subtotal) ?></strong>
            </div>

            <div class="total-row">
                <span>Service Fee</span>
                <strong><?= $formatRupiah($serviceFee) ?></strong>
            </div>

            <div class="total-row grand">
                <span>Total Pesanan</span>
                <strong><?= $formatRupiah($total) ?></strong>
            </div>

        </div>

        <div class="dp-info">
            <strong>Pembayaran Down Payment (DP)</strong><br>
            Jumlah DP yang harus dibayarkan:
            <strong><?= $formatRupiah($dp) ?></strong><br>
            E-Voucher akan diberikan setelah pembayaran DP terverifikasi.
        </div>

        <form action="index.php?page=proses-pembayaran" method="POST">

            <div class="buttons">
                <a href="index.php?page=katalog-menu" class="back">
                    Kembali
                </a>

                <button type="submit">
                    Lanjutkan Pembayaran
                </button>
            </div>

        </form>

    </div>
</div>

</body>
</html>
