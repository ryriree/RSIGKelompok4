<?php
// Konten dashboard saja. Sidebar, header, dan CSS ada di views/layouts/staff.php
$namaUser = $_SESSION['nama'] ?? 'Staff';
$role     = strtolower($_SESSION['role'] ?? '');

$pintasan = [
    ['page' => 'pos',               'judul' => 'Antrean POS / Kasir Baru',        'ket' => 'Proses order & bayar',     'roles' => ['kasir'],
     'bg' => 'var(--red-100)',   'warna' => 'var(--primary)', 'svg' => 'M6 2h12v20l-3-2-3 2-3-2-3 2zM9 7h6M9 11h6'],
    ['page' => 'status-pesanan',    'judul' => 'Status Pesanan',                  'ket' => 'Pantau pesanan meja',      'roles' => ['pelayan'],
     'bg' => 'var(--red-100)',   'warna' => 'var(--primary)', 'svg' => 'M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01'],
    ['page' => 'laporan-penjualan', 'judul' => 'Input Jumlah Kas Fisik',          'ket' => 'Rekonsiliasi kas',         'roles' => ['manajer', 'admin'],
     'bg' => 'var(--green-100)', 'warna' => 'var(--success)', 'svg' => 'M3 6h18v12H3zM12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6z'],
    ['page' => 'laporan-penjualan', 'judul' => 'Export Laporan Penjualan (.XLSX)','ket' => 'Unduh laporan penjualan',  'roles' => ['manajer', 'admin'],
     'bg' => 'var(--blue-100)',  'warna' => 'var(--info)',    'svg' => 'M12 3v12M7 11l5 5 5-5M4 21h16'],
    ['page' => 'reservasi',         'judul' => 'Manajemen Reservasi',             'ket' => 'Atur booking meja',        'roles' => ['admin'],
     'bg' => 'var(--blue-100)',  'warna' => 'var(--info)',    'svg' => 'M5 4h14a1 1 0 0 1 1 1v15H4V5a1 1 0 0 1 1-1zM4 9h16M8 2v4M16 2v4'],
    ['page' => 'kelola-menu',       'judul' => 'Kelola Menu',                     'ket' => 'Tambah & ubah menu',       'roles' => ['kasir'],
     'bg' => 'var(--cream-200)', 'warna' => 'var(--primary)', 'svg' => 'M15 4a5 5 0 0 1-3 8l-6 7a2 2 0 0 1-3-3l7-6a5 5 0 0 1 5-6z'],
    ['page' => 'laporan-kendala',   'judul' => 'Komplain & Feedback',             'ket' => 'Lihat laporan pelanggan',  'roles' => ['admin', 'manajer'],
     'bg' => 'var(--cream-200)', 'warna' => 'var(--primary)', 'svg' => 'M21 12a8 8 0 0 1-11.5 7.2L3 21l1.8-5.5A8 8 0 1 1 21 12z'],
];
$pintasan = array_filter($pintasan, fn($p) => in_array($role, $p['roles']));
?>

<section class="hero">
    <div>
        <span class="tag"><?= date('l, d F Y') ?></span>
        <h2>HALO, <?= strtoupper(htmlspecialchars($namaUser)) ?></h2>
        <p>Selamat datang kembali di Dashboard Staff Maky Chicken. Kelola pesanan, reservasi, laporan penjualan, dan layanan restoran dalam satu tempat.</p>
    </div>
    <div class="art">
        <svg class="i" viewBox="0 0 24 24" style="width:72px;height:72px;stroke:var(--primary);fill:none;stroke-width:1.75;"><path d="M6 13h12a2 2 0 0 1 0 4H6a2 2 0 0 1 0-4zM8 13V9a4 4 0 0 1 8 0v4"/></svg>
    </div>
</section>

<?php if (!empty($pintasan)): ?>
<section class="mc-card">
    <div class="sec-title">
        <h3>AKSES CEPAT OPERASIONAL</h3>
        <span class="pilltag">PINTASAN UTAMA</span>
    </div>
    <div class="quick">
        <?php foreach ($pintasan as $p): ?>
            <a class="q" href="<?= url($p['page']) ?>">
                <span class="ico" style="background:<?= $p['bg'] ?>;color:<?= $p['warna'] ?>">
                    <svg class="i" viewBox="0 0 24 24"><path d="<?= $p['svg'] ?>"/></svg>
                </span>
                <b><?= htmlspecialchars($p['judul']) ?></b>
                <span class="d"><?= htmlspecialchars($p['ket']) ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<div class="stats">
    <div class="mc-card stat">
        <span class="label">PESANAN MASUK HARI INI ● Realtime Sync</span>
        <div class="n">42 Pesanan</div>
        <small>34 Selesai · 8 Sedang Diproses Dapur</small>
    </div>
    <div class="mc-card stat">
        <span class="label">PENDAPATAN HARI INI ● Omset Bersih</span>
        <div class="n">Rp3.850.000</div>
        <small>Rp2.600.000 QRIS · Rp1.250.000 Tunai</small>
    </div>
    <div class="mc-card mc-card--accent stat">
        <span class="label" style="color:var(--ink-900)">RESERVASI CONFIRMED ★ Hari Ini</span>
        <div class="n">8 Reservasi</div>
        <small>Lantai 1 & Lantai 2 · Semua DP Terverifikasi</small>
    </div>
</div>