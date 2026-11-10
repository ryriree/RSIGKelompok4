
<?php

$notifSukses = $_SESSION['notif_sukses'] ?? null;
$notifError  = $_SESSION['notif_error'] ?? null;
unset($_SESSION['notif_sukses'], $_SESSION['notif_error']);

$daftarPesanan  = $daftarPesanan  ?? [];
$pesananDipilih = $pesananDipilih ?? null;
$statistik      = $statistik      ?? ['menungguTunai' => 0, 'lunasHariIni' => 0, 'totalKasMasuk' => 0];
$statusBayar    = $statusBayar    ?? 'Menunggu Pembayaran';

$namaKasir = $_SESSION['user']['nama'] ?? '-';
$shiftKerja = $_SESSION['user']['shift'] ?? '-';

if (!function_exists('rp')) {
    function rp($angka) { return 'Rp' . number_format((float) $angka, 0, ',', '.'); }
}

$totalAntrean = count($daftarPesanan);
$kodeDipilih  = $pesananDipilih['kodePesanan'] ?? '';
$totalTagihan = $pesananDipilih['totalHarga'] ?? 0;
$metodeAwal   = ($pesananDipilih['metode'] ?? 'QRIS') === 'QRIS' ? 'QRIS' : 'Tunai';
$lunas        = ($statusBayar === 'Lunas');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antrean POS Kasir - Maky Chicken</title>

    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f5f6f8; color: #222; }
        header { background: #0b2a5b; color: white; padding: 18px; }
        .layout { display: flex; min-height: 100vh; }
        aside { width: 200px; background: #0b2a5b; padding: 20px; color: white; }
        aside a { display: block; color: white; text-decoration: none; padding: 10px; border-bottom: 1px solid #ffffff44; }
        aside a.aktif { background: #ffffff22; font-weight: bold; }
        main { flex: 1; padding: 25px; }
        .cards { display: flex; gap: 12px; flex-wrap: wrap; }
        .card { background: white; border: 1px solid #ddd; border-radius: 7px; padding: 16px; margin-bottom: 15px; }
        .stat { flex: 1; min-width: 130px; }
        a.pesanan { display: block; color: inherit; text-decoration: none; border-left: 5px solid #ff8c42; }
        a.pesanan:hover, a.pesanan.dipilih { background: #fff4eb; }
        .status { color: #b76b00; font-weight: bold; }
        .status.lunas { color: #1e7e34; }
        input, select { padding: 10px; width: 100%; box-sizing: border-box; margin: 8px 0; }
        button { padding: 10px 14px; border: none; border-radius: 5px; background: #0b2a5b; color: white; cursor: pointer; margin: 4px; }
        button.sekunder { background: white; color: #0b2a5b; border: 1px solid #0b2a5b; }
        button:disabled { opacity: .45; cursor: not-allowed; }
        .hasil { font-weight: bold; margin-top: 12px; }
        .notif-sukses { background: #e6f4ea; color: #1e7e34; border: 1px solid #1e7e34; border-radius: 6px; padding: 12px; margin-bottom: 15px; }
        .notif-error { background: #fdecea; color: #b3261e; border: 1px solid #b3261e; border-radius: 6px; padding: 12px; margin-bottom: 15px; }
    </style>
</head>

<body>

<header>
    <strong>MAKY CHICKEN - POS KASIR</strong>
</header>

<div class="layout">

    <aside>
        <h3>Menu Operasional</h3>
        <a href="index.php?page=antrean-pos" class="aktif">Antrean POS Kasir</a>
        <a href="index.php?page=dashboard-laporan">Dashboard Laporan</a>
        <a href="index.php?page=kelola-menu">Kelola Menu</a>
        <a href="index.php?page=laporan-kendala">Laporan Kendala</a>
    </aside>

    <main>

        <h2>Antrean Pesanan Kasir</h2>

        <?php if ($notifSukses): ?>
            <div class="notif-sukses"><?= htmlspecialchars($notifSukses); ?></div>
        <?php endif; ?>
        <?php if ($notifError): ?>
            <div class="notif-error"><?= htmlspecialchars($notifError); ?></div>
        <?php endif; ?>

        <p>Kasir Bertugas: <?= htmlspecialchars($namaKasir); ?></p>
        <p>Shift Kerja: <?= htmlspecialchars($shiftKerja); ?></p>
        <p>Jam Operasional: 08:00 - 22:00 WIB</p>
        <p>Status Shift: Aktif</p>

        <input type="search" id="cari" placeholder="Cari meja / nomor pesanan" oninput="cariPesanan()">

        <div class="cards">
            <div class="card stat">
                <p>Total Antrean</p>
                <h2><?= $totalAntrean; ?></h2>
            </div>

            <div class="card stat">
                <p>Menunggu Tunai</p>
                <h2><?= (int) $statistik['menungguTunai']; ?></h2>
            </div>

            <div class="card stat">
                <p>Lunas Hari Ini</p>
                <h2><?= (int) $statistik['lunasHariIni']; ?></h2>
            </div>

            <div class="card stat">
                <p>Total Kas Masuk</p>
                <h2><?= rp($statistik['totalKasMasuk']); ?></h2>
            </div>
        </div>

        <div class="card">
            <h3>Daftar Antrean Pesanan</h3>

            <?php if (empty($daftarPesanan)): ?>
                <p>Belum ada pesanan.</p>
            <?php endif; ?>

            <?php foreach ($daftarPesanan as $p): ?>
                <?php
                    $namaItem = [];
                    foreach ($p['daftarItem'] as $it) {
                        $namaItem[] = $it['nama'] . ' x ' . (int) $it['jumlah'];
                    }
                    $sudahLunas = ($p['statusPesanan'] ?? '') === 'Lunas';
                ?>
                <a class="card pesanan <?= ($p['kodePesanan'] === $kodeDipilih) ? 'dipilih' : ''; ?>"
                   href="index.php?page=antrean-pos&kode=<?= urlencode($p['kodePesanan']); ?>">
                    <strong>#<?= htmlspecialchars($p['kodePesanan']); ?> - Meja <?= htmlspecialchars($p['nomorMeja']); ?></strong>
                    <p><?= htmlspecialchars(implode(', ', $namaItem)); ?></p>
                    <p>Total: <?= rp($p['totalHarga']); ?></p>
                    <span class="status <?= $sudahLunas ? 'lunas' : ''; ?>"><?= htmlspecialchars($p['statusPesanan'] ?? 'Menunggu Pembayaran'); ?></span>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="card">
            <h3>Verifikasi Pembayaran</h3>

            <form action="index.php?page=verifikasi-bayar" method="POST">
                <input type="hidden" name="kode_pesanan" value="<?= htmlspecialchars($kodeDipilih); ?>">

                <p>Nomor Pesanan: <strong><?= $kodeDipilih !== '' ? htmlspecialchars($kodeDipilih) : '-'; ?></strong></p>

                <p>Total Tagihan Bersih:</p>
                <h2 id="total" data-nilai="<?= (float) $totalTagihan; ?>"><?= rp($totalTagihan); ?></h2>

                <label>Metode Pembayaran</label>
                <select name="metode" id="metode">
                    <option value="QRIS"  <?= $metodeAwal === 'QRIS'  ? 'selected' : ''; ?>>QRIS</option>
                    <option value="Tunai" <?= $metodeAwal === 'Tunai' ? 'selected' : ''; ?>>Tunai</option>
                </select>

                <label>Nominal Uang Diterima Pelanggan</label>
                <input type="number" name="uang_diterima" id="uang" min="0"
                       placeholder="Masukkan nominal uang" oninput="hitungKembalian()">

                <p>Uang Kembalian Pelanggan:</p>
                <h3 id="kembalian">Rp0</h3>

                <button type="button" class="sekunder" onclick="nominalCepat(50000)">Rp50.000</button>
                <button type="button" class="sekunder" onclick="nominalCepat(100000)">Rp100.000</button>
                <button type="button" class="sekunder" onclick="nominalCepat(200000)">Rp200.000</button>

                <div>
                    <button type="submit" <?= ($kodeDipilih === '' || $lunas) ? 'disabled' : ''; ?>>
                        Verifikasi &amp; Selesaikan Pembayaran
                    </button>

                    <button type="submit" class="sekunder"
                            formaction="index.php?page=cetak-struk" formnovalidate
                            <?= $lunas ? '' : 'disabled'; ?>>
                        Cetak Struk
                    </button>

                    <button type="submit" class="sekunder"
                            formaction="index.php?page=batal-transaksi" formnovalidate
                            <?= ($kodeDipilih === '' || $lunas) ? 'disabled' : ''; ?>>
                        Batalkan Transaksi
                    </button>
                </div>
            </form>

            <?php if ($kodeDipilih !== ''): ?>
                <p class="hasil">
                    <?php if ($lunas): ?>
                        Pembayaran berhasil. Status: Lunas. Pesanan diteruskan ke dapur/bar.
                    <?php else: ?>
                        Status pembayaran: <?= htmlspecialchars($statusBayar); ?>.
                    <?php endif; ?>
                </p>
            <?php endif; ?>
        </div>

    </main>
</div>

<script>
    function rupiah(angka) {
        return 'Rp' + angka.toLocaleString('id-ID');
    }

    function nominalCepat(nominal) {
        document.getElementById('uang').value = nominal;
        hitungKembalian();
    }

    function hitungKembalian() {
        const total = Number(document.getElementById('total').dataset.nilai);
        const uang = Number(document.getElementById('uang').value);
        document.getElementById('kembalian').textContent =
            rupiah(Math.max(0, uang - total));
    }

    function cariPesanan() {
        const kata = document.getElementById('cari').value.toLowerCase();

        document.querySelectorAll('a.pesanan').forEach(function (item) {
            item.style.display =
                item.textContent.toLowerCase().includes(kata) ? 'block' : 'none';
        });
    }
</script>

</body>
</html>