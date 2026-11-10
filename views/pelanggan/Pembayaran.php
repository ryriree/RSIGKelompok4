
<?php

$notifSukses = $_SESSION['notif_sukses'] ?? null;
$notifError  = $_SESSION['notif_error'] ?? null;
unset($_SESSION['notif_sukses'], $_SESSION['notif_error']);

$dataPesanan   = $dataPesanan   ?? ['kodePesanan' => '-', 'nomorMeja' => '-', 'lantai' => '-', 'daftarItem' => [], 'totalHarga' => 0];
$hasilBayar    = $hasilBayar    ?? null;
$metodeDipilih = $metodeDipilih ?? null;
$statusBayar   = $statusBayar   ?? 'Menunggu Pembayaran';

if (!function_exists('rp')) {
    function rp($angka) { return 'Rp' . number_format((float) $angka, 0, ',', '.'); }
}

$subtotal = 0;
foreach ($dataPesanan['daftarItem'] as $item) {
    $subtotal += $item['harga'] * $item['jumlah'];
}
$total = $dataPesanan['totalHarga'];
$biaya = $total - $subtotal;

$kodePesanan = $dataPesanan['kodePesanan'];
$lunas       = ($statusBayar === 'Lunas');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Maky Chicken</title>

    <style>
        body { font-family: Arial, sans-serif; background: #f5f6f8; margin: 0; color: #222; }
        nav { background: #0b2a5b; color: white; padding: 16px 25px; }
        .container { max-width: 650px; margin: 25px auto; padding: 20px; }
        .card { background: white; padding: 20px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 15px; }
        .total { font-size: 22px; font-weight: bold; color: #0b2a5b; }
        .metode { display: block; padding: 14px; border: 1px solid #ddd; border-radius: 6px; margin: 10px 0; cursor: pointer; }
        button, .tombol { padding: 11px 16px; border: none; border-radius: 5px; background: #0b2a5b; color: white; cursor: pointer; text-decoration: none; display: inline-block; font-size: 14px; }
        button.sekunder, .tombol.sekunder { background: white; color: #0b2a5b; border: 1px solid #0b2a5b; }
        .qris { text-align: center; padding: 20px; border: 2px dashed #0b2a5b; margin-top: 15px; }
        .pesan { margin-top: 12px; font-weight: bold; }
        .notif-sukses { background: #e6f4ea; color: #1e7e34; border: 1px solid #1e7e34; border-radius: 6px; padding: 12px; margin-bottom: 15px; }
        .notif-error { background: #fdecea; color: #b3261e; border: 1px solid #b3261e; border-radius: 6px; padding: 12px; margin-bottom: 15px; }
        .kode { font-size: 22px; font-weight: bold; letter-spacing: 1px; color: #0b2a5b; }
    </style>
</head>

<body>

<nav>
    <strong>MAKY CHICKEN</strong>
</nav>

<div class="container">

    <?php if ($notifSukses): ?>
        <div class="notif-sukses"><?= htmlspecialchars($notifSukses); ?></div>
    <?php endif; ?>
    <?php if ($notifError): ?>
        <div class="notif-error"><?= htmlspecialchars($notifError); ?></div>
    <?php endif; ?>

    <div class="card">
        <a class="tombol sekunder" href="index.php?page=pesan-qr">Kembali</a>

        <h2>Pembayaran Pesanan</h2>

        <p>Info Meja: Meja <?= htmlspecialchars($dataPesanan['nomorMeja']); ?> - Lantai <?= htmlspecialchars($dataPesanan['lantai']); ?></p>
        <p>Nomor Pesanan: #<?= htmlspecialchars($kodePesanan); ?></p>
        <p>Status Pesanan: <?= $lunas ? 'Lunas' : 'Menunggu Pembayaran'; ?></p>
    </div>

    <div class="card">
        <h3>Rincian Tagihan</h3>

        <?php foreach ($dataPesanan['daftarItem'] as $item): ?>
            <p><?= htmlspecialchars($item['nama']); ?> x <?= (int) $item['jumlah']; ?> - <?= rp($item['harga'] * $item['jumlah']); ?></p>
        <?php endforeach; ?>

        <p>Subtotal: <?= rp($subtotal); ?></p>
        <p>PB1 dan Biaya Layanan: <?= rp($biaya); ?></p>

        <hr>

        <p class="total">Total Bayar: <?= rp($total); ?></p>
    </div>

    <?php if ($lunas): ?>
        <div class="card">
            <h3>Pembayaran Berhasil</h3>
            <p>Tiket pesanan sudah diteruskan ke dapur/bar.</p>
            <a class="tombol" href="index.php?page=invoice&kode=<?= urlencode($kodePesanan); ?>">Lihat Invoice</a>
        </div>

    <?php else: ?>
        <div class="card">
            <h3>Pilih Metode Pembayaran</h3>

            <form action="index.php?page=proses-bayar" method="POST">
                <input type="hidden" name="kode_pesanan" value="<?= htmlspecialchars($kodePesanan); ?>">

                <label class="metode">
                    <input type="radio" name="metode" value="QRIS" required
                           <?= ($metodeDipilih === 'QRIS') ? 'checked' : ''; ?>>
                    QRIS Dinamis
                </label>

                <label class="metode">
                    <input type="radio" name="metode" value="Bayar di Kasir" required
                           <?= ($metodeDipilih === 'Bayar di Kasir') ? 'checked' : ''; ?>>
                    Bayar di Kasir
                </label>

                <button type="submit">Konfirmasi Pembayaran</button>
            </form>

            <?php if ($hasilBayar && $metodeDipilih === 'QRIS' && !empty($hasilBayar['kodeQRIS'])): ?>
                <div class="qris">
                    <h3>QRIS Pembayaran</h3>
                    <p class="kode"><?= htmlspecialchars($hasilBayar['kodeQRIS']); ?></p>
                    <p>Total Transfer: <?= rp($total); ?></p>
                    <p>Batas Waktu: 15 Menit</p>

                    <a class="tombol" href="index.php?page=cek-status-bayar&kode=<?= urlencode($kodePesanan); ?>">
                        Cek Status Pembayaran
                    </a>
                </div>
                <p class="pesan">Menunggu konfirmasi pembayaran QRIS.</p>

            <?php elseif ($hasilBayar && $metodeDipilih === 'Bayar di Kasir'): ?>
                <div class="qris">
                    <h3>Kode Transaksi</h3>
                    <p class="kode"><?= htmlspecialchars($hasilBayar['kodeTransaksi']); ?></p>
                    <p>Tunjukkan kode ini kepada kasir untuk menyelesaikan pembayaran.</p>

                    <a class="tombol" href="index.php?page=cek-status-bayar&kode=<?= urlencode($kodePesanan); ?>">
                        Cek Status Pembayaran
                    </a>
                </div>
                <p class="pesan">Kode transaksi berhasil dibuat. Silakan bayar di kasir.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</div>

</body>
</html>