<?php
// Konten saja. Variabel $tanggal, $dataPenjualan, $dataKas dikirim oleh LaporanPenjualanController
$tanggalAktif = $tanggal ?? date('Y-m-d');
$dataKas   = is_array($dataKas ?? null) ? $dataKas : [];
$rg        = $dataPenjualan['ringkasan'] ?? [];
$daftarTrx = $dataPenjualan['daftar_transaksi'] ?? [];
$topMenu   = $dataPenjualan['top_menu'] ?? [];
$jumlahTrx = count($daftarTrx);

$rpL = function ($n) { return 'Rp' . number_format((float)$n, 0, ',', '.'); };
$totalPend = (float)($rg['total_pendapatan'] ?? 0);
$totalTrx  = (int)($rg['total_trx'] ?? 0);
$qris  = (float)($rg['total_qris'] ?? 0);   $nQris  = (int)($rg['count_qris'] ?? 0);
$tunai = (float)($rg['total_tunai'] ?? 0);  $nTunai = (int)($rg['count_tunai'] ?? 0);
$debit = (float)($rg['total_debit'] ?? 0);  $nDebit = (int)($rg['count_debit'] ?? 0);
$pct = function ($v) use ($totalPend) { return $totalPend > 0 ? round($v / $totalPend * 100, 1) : 0; };
$avg = function ($v, $n) { return $n > 0 ? $v / $n : 0; };
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

<style>
.lp .pill-status { white-space: nowrap; flex-shrink: 0; }
.lp { font-size: 13px; line-height: 1.5; }
.lp .text-muted { color: var(--text-muted); }
.lp .page-desc { font-size: 12px; font-weight: 500; color: var(--text-muted); }

.lp .action-group { display: flex; gap: 12px; align-items: center; flex-wrap: wrap; justify-content: flex-start; margin-bottom: 24px; }
.lp .date-pill { display: flex; align-items: center; gap: 8px; background: #fff; border: 1.5px solid var(--cream-300); border-radius: 999px; padding: 4px 16px; font-weight: 700; font-size: 12px; color: var(--ink-900); }
.lp .date-pill input { border: 0; background: transparent; outline: none; font-weight: 700; color: inherit; cursor: pointer; }

.lp .mc-btn { min-height: 42px; padding: 0 20px; font-size: 11px; letter-spacing: .06em; }
.lp .mc-btn--primary { background: var(--red-600); color: #fff; }
.lp .mc-btn--primary:hover { background: var(--red-700); }
.lp .mc-btn--info { background: var(--info); color: #fff; }
.lp .mc-btn--info:hover { background: #174E90; }
.lp .mc-btn--ghost { border-color: var(--border); }

.lp .kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
.lp .kpi-card { background: #fff; border: 1.5px solid var(--cream-300); border-radius: 18px; padding: 18px; position: relative; box-shadow: var(--shadow-1); }
.lp .kpi-title { font-size: 10px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--text-muted); display: block; margin-bottom: 4px; }
.lp .kpi-val { font-family: var(--font-display); font-size: 26px; color: var(--red-600); line-height: 1.1; margin-bottom: 6px; }
.lp .kpi-sub { font-size: 11px; font-weight: 500; color: var(--text-muted); }
.lp .kpi-icon-corner { position: absolute; top: 16px; right: 16px; color: var(--text-muted); opacity: .6; font-size: 14px; }

.lp .mid-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 28px; }
.lp .card-ui { background: #fff; border: 1.5px solid var(--cream-300); border-radius: 20px; padding: 22px; box-shadow: var(--shadow-1); }
.lp .card-header-flex { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; gap: 12px; }
.lp .card-header-flex h3 { font-size: 18px; color: var(--red-600); line-height: 1.1; }
.lp .card-header-flex p { font-size: 11px; color: var(--text-muted); margin-top: 2px; }

.lp .ratio-pill-bar { background: var(--cream-100); border-radius: 999px; height: 26px; display: flex; overflow: hidden; margin-bottom: 16px; border: 1px solid var(--cream-300); font-weight: 700; font-size: 10px; }
.lp .ratio-qris { background: var(--red-600); color: #fff; display: flex; align-items: center; justify-content: center; }
.lp .ratio-tunai { background: var(--yellow-500); color: var(--ink-900); display: flex; align-items: center; justify-content: center; }
.lp .method-subgrid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px; }
.lp .method-box { background: var(--cream-50); border: 1.5px solid var(--cream-200); border-radius: 14px; padding: 12px 14px; }
.lp .method-box small { font-size: 10px; font-weight: 600; color: var(--text-muted); display: block; margin-bottom: 2px; }
.lp .method-box b { font-family: var(--font-display); font-size: 20px; display: block; line-height: 1.1; font-weight: 400; }
.lp .service-breakdown { border-top: 1px dashed var(--cream-300); padding-top: 14px; }
.lp .service-row { display: flex; justify-content: space-between; align-items: center; padding: 6px 0; font-size: 12px; }
.lp .service-row span { font-weight: 600; }
.lp .service-row b { font-weight: 700; }

.lp .table-topmenu { width: 100%; border-collapse: separate; border-spacing: 0; font-size: 12px; }
.lp .table-topmenu th { background: var(--red-600); color: #fff; font-size: 10px; letter-spacing: .08em; text-transform: uppercase; padding: 10px 12px; text-align: left; }
.lp .table-topmenu th:first-child { border-top-left-radius: 12px; }
.lp .table-topmenu th:last-child { border-top-right-radius: 12px; text-align: right; }
.lp .table-topmenu td { padding: 10px 12px; border-bottom: 1px solid var(--cream-200); vertical-align: middle; }
.lp .rank-circle { width: 22px; height: 22px; border-radius: 50%; background: var(--yellow-500); color: var(--ink-900); font-weight: 700; font-size: 11px; display: grid; place-items: center; }

.lp .table-main-wrap { border: 1.5px solid var(--cream-300); border-radius: 18px; overflow: hidden; background: #fff; overflow-x: auto; }
.lp .table-main { width: 100%; border-collapse: collapse; font-size: 12px; text-align: left; }
.lp .table-main th { background: var(--red-600); color: #fff; font-size: 10px; letter-spacing: .08em; text-transform: uppercase; padding: 14px 16px; }
.lp .table-main td { padding: 12px 16px; border-bottom: 1px solid var(--cream-200); vertical-align: middle; }
.lp .table-main tr:nth-child(even) { background: var(--cream-50); }
.lp .table-main tr:hover { background: var(--cream-100); }

.lp .pill-status { display: inline-flex; align-items: center; gap: 4px; padding: 2px 10px; border-radius: 999px; font-size: 10px; font-weight: 700; }
.lp .pill-qris { background: var(--blue-100); color: var(--info); }
.lp .pill-tunai { background: var(--yellow-100); color: var(--yellow-600); }
.lp .pill-dinein { background: var(--green-100); color: var(--success); }
.lp .pill-takeaway { background: var(--yellow-100); color: var(--yellow-600); }
.lp .pill-reservasi { background: var(--blue-100); color: var(--info); }

.lp .pagination-bar { display: flex; justify-content: space-between; align-items: center; padding: 12px 20px; font-size: 11px; color: var(--text-muted); font-weight: 600; background: #fff; border-top: 1px solid var(--cream-200); flex-wrap: wrap; gap: 8px; }

.kas-overlay { position: fixed; inset: 0; background: rgba(94,10,16,.45); display: flex; align-items: center; justify-content: center; padding: 16px; z-index: 1000; backdrop-filter: blur(4px); opacity: 0; pointer-events: none; transition: opacity .25s ease-in-out; }
.kas-overlay.on { opacity: 1; pointer-events: auto; }
.kas-modal { background: #fff; border-radius: 24px; box-shadow: var(--shadow-3); padding: 24px; max-width: 440px; width: 100%; border: 1.5px solid var(--cream-300); transform: translateY(20px) scale(.95); transition: transform .25s cubic-bezier(.34,1.56,.64,1); font-size: 13px; }
.kas-overlay.on .kas-modal { transform: none; }

@media (max-width: 1024px) {
    .lp .kpi-grid { grid-template-columns: repeat(2, 1fr); }
    .lp .mid-grid { grid-template-columns: 1fr; }
}
</style>

<div class="lp">

    <p class="page-desc" style="margin-bottom:16px;">Ringkasan omset harian, audit kas fisik kasir, serta riwayat transaksi penjualan.</p>

    <div class="action-group">
        <form action="index.php" method="GET" class="date-pill">
            <input type="hidden" name="page" value="laporan-penjualan">
            <span style="color:var(--text-muted); font-size:10px;">TANGGAL:</span>
            <input type="date" name="tanggal" value="<?= htmlspecialchars($tanggalAktif) ?>" onchange="this.form.submit()">
        </form>

        <a href="index.php?page=export-excel&tanggal=<?= urlencode($tanggalAktif) ?>" class="mc-btn mc-btn--info">
            <i class="bi bi-download"></i> EXPORT DATA (.XLSX)
        </a>

        <button type="button" class="mc-btn mc-btn--primary" onclick="openKasModal()">
            + MASUKKAN JUMLAH KAS
        </button>
    </div>

    <!-- 4 KARTU KPI -->
    <section class="kpi-grid">
        <div class="kpi-card">
            <span class="kpi-title">TOTAL PENDAPATAN</span>
            <i class="bi bi-graph-up-arrow kpi-icon-corner"></i>
            <div class="kpi-val"><?= $rpL($totalPend) ?></div>
            <div class="kpi-sub">Rata-rata: <?= $rpL($avg($totalPend, $totalTrx)) ?> / trx</div>
        </div>

        <div class="kpi-card">
            <span class="kpi-title">TOTAL TRANSAKSI</span>
            <i class="bi bi-receipt-cutoff kpi-icon-corner"></i>
            <div class="kpi-val" style="color:var(--ink-900);"><?= $totalTrx ?> <span style="font-size:16px;">Trx</span></div>
            <div class="kpi-sub">
                <span style="color:var(--info);">● <?= $nQris ?> QRIS</span> •
                <span style="color:var(--yellow-600);">● <?= $nTunai ?> Tunai</span>
                <?php if ($nDebit > 0): ?> • <span>● <?= $nDebit ?> Debit</span><?php endif; ?>
            </div>
        </div>

        <div class="kpi-card">
            <span class="kpi-title">KAS SISTEM (OTOMATIS)</span>
            <i class="bi bi-laptop kpi-icon-corner"></i>
            <div class="kpi-val" style="color:var(--info);"><?= $rpL($dataKas['kas_sistem'] ?? 0) ?></div>
            <div class="kpi-sub">● SISTEM POS</div>
        </div>

        <div class="kpi-card">
            <div style="display:flex; justify-content:space-between;">
                <span class="kpi-title">KAS FISIK & AUDIT</span>
                <a href="#" style="color:var(--info); font-size:10px; font-weight:700; text-decoration:none;" onclick="openKasModal(); return false;">UBAH</a>
            </div>
            <div class="kpi-val" style="color:var(--ink-900);"><?= $rpL($dataKas['kas_fisik'] ?? 0) ?></div>
            <div class="kpi-sub">
                <?php
                $st = $dataKas['status_selisih'] ?? 'Sesuai';
                $badgeClass = ($st == 'Sesuai') ? 'pill-dinein' : (($st == 'Surplus') ? 'pill-tunai' : 'pill-qris');
                ?>
                <span class="pill-status <?= $badgeClass ?>">● Status Kasir: <?= htmlspecialchars($st) ?> (<?= $rpL($dataKas['selisih'] ?? 0) ?>)</span>
            </div>
        </div>
    </section>

    <!-- BARIS TENGAH -->
    <section class="mid-grid">
        <div class="card-ui">
            <div class="card-header-flex">
                <div>
                    <h3 class="disp">PENDAPATAN BERDASARKAN METODE BAYAR & PEMESANAN</h3>
                    <p>Rincian perputaran omset harian berdasarkan kanal bayar QRIS & Tunai</p>
                </div>
                <span class="pill-status pill-dinein">● <?= htmlspecialchars(date('d/m/Y', strtotime($tanggalAktif))) ?></span>
            </div>

            <div class="ratio-pill-bar">
                <?php if ($totalPend > 0): ?>
                    <?php if ($qris > 0): ?><div class="ratio-qris" style="width:<?= $pct($qris) ?>%"><?= $pct($qris) ?>% QRIS</div><?php endif; ?>
                    <?php if ($tunai > 0): ?><div class="ratio-tunai" style="width:<?= $pct($tunai) ?>%"><?= $pct($tunai) ?>% TUNAI</div><?php endif; ?>
                    <?php if ($debit > 0): ?><div style="width:<?= $pct($debit) ?>%;background:var(--info);color:#fff;display:flex;align-items:center;justify-content:center"><?= $pct($debit) ?>% DEBIT</div><?php endif; ?>
                <?php else: ?>
                    <div style="width:100%;display:flex;align-items:center;justify-content:center;color:var(--text-muted)">Belum ada transaksi</div>
                <?php endif; ?>
            </div>

            <div class="method-subgrid">
                <div class="method-box">
                    <small>● QRIS Digital <span style="color:var(--info);">● <?= $pct($qris) ?>%</span></small>
                    <b style="color:var(--red-600);"><?= $rpL($qris) ?></b>
                    <small style="margin-top:4px;"><?= $nQris ?> Transaksi (Avg <?= $rpL($avg($qris, $nQris)) ?>)</small>
                </div>
                <div class="method-box">
                    <small>● Kasir Tunai <span style="color:var(--yellow-600);">● <?= $pct($tunai) ?>%</span></small>
                    <b style="color:var(--yellow-600);"><?= $rpL($tunai) ?></b>
                    <small style="margin-top:4px;"><?= $nTunai ?> Transaksi (Avg <?= $rpL($avg($tunai, $nTunai)) ?>)</small>
                </div>
            </div>

            <div class="service-breakdown">
                <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                    <span style="font-size:10px; font-weight:700; color:var(--text-muted); text-transform:uppercase;">RINCIAN JENIS PEMESANAN</span>
                    <span style="font-size:10px; font-weight:700; color:var(--text-muted);">3 Kategori Layanan</span>
                </div>
                <?php
                $layanan = [
                    ['Dine In',          'total_dine_in',   'count_dine_in'],
                    ['Take Away',        'total_take_away', 'count_take_away'],
                    ['Reservasi Online', 'total_reservasi', 'count_reservasi'],
                ];
                foreach ($layanan as [$nama, $kTotal, $kCount]): ?>
                <div class="service-row">
                    <span><?= $nama ?> <small class="text-muted">(<?= (int)($rg[$kCount] ?? 0) ?> Transaksi | <?= $pct((float)($rg[$kTotal] ?? 0)) ?>%)</small></span>
                    <b><?= $rpL($rg[$kTotal] ?? 0) ?></b>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="card-ui">
            <div class="card-header-flex">
                <div>
                    <h3 class="disp">MENU TERLARIS HARI INI</h3>
                    <p>Peringkat item menu dengan perputaran tertinggi</p>
                </div>
                <span class="pill-status pill-dinein">● Top 5</span>
            </div>

            <table class="table-topmenu">
                <thead>
                    <tr>
                        <th style="width:40px;">NO</th>
                        <th>NAMA MENU</th>
                        <th>TERJUAL</th>
                        <th>SUBTOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($topMenu)): ?>
                        <?php foreach ($topMenu as $idx => $m): ?>
                        <tr>
                            <td><span class="rank-circle"><?= $idx + 1 ?></span></td>
                            <td>
                                <b style="color:var(--ink-900);"><?= htmlspecialchars($m['nama_menu']) ?></b><br>
                                <small style="color:var(--text-muted); font-size:10px;"><?= htmlspecialchars($m['kategori']) ?></small>
                            </td>
                            <td><b><?= (int)$m['total_terjual'] ?> porsi</b></td>
                            <td style="text-align:right;"><b style="color:var(--red-600);"><?= $rpL($m['total_subtotal']) ?></b></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" style="text-align:center;padding:20px;color:var(--text-muted)">Belum ada penjualan menu pada tanggal ini.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- DETAIL TRANSAKSI -->
    <section class="card-ui">
        <div class="card-header-flex">
            <div>
                <h3 class="disp">DETAIL TRANSAKSI PENJUALAN</h3>
                <p>Catatan riwayat transaksi kasir dan QR digital secara real-time</p>
            </div>
            <span class="pill-status pill-tunai">● Total: <?= $jumlahTrx ?> Transaksi</span>
        </div>

        <div class="table-main-wrap">
            <table class="table-main">
                <thead>
                    <tr>
                        <th>WAKTU</th>
                        <th>ID TRANSAKSI</th>
                        <th>PELANGGAN / METODE PESANAN</th>
                        <th>RINCIAN MENU</th>
                        <th>METODE BAYAR</th>
                        <th style="text-align:right;">TOTAL BAYAR</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($daftarTrx)): ?>
                        <?php foreach ($daftarTrx as $trx): ?>
                        <?php
                            $mPesanan = $trx['metode_pesanan'] ?? 'Dine In';
                            $badgePesananClass = 'pill-dinein';
                            if (stripos($mPesanan, 'Take Away') !== false) {
                                $badgePesananClass = 'pill-takeaway';
                            } elseif (stripos($mPesanan, 'Reservasi') !== false) {
                                $badgePesananClass = 'pill-reservasi';
                            }
                        ?>
                        <tr>
                            <td><b><?= htmlspecialchars($trx['waktu']) ?></b></td>
                            <td><b style="color:var(--red-600);">#<?= htmlspecialchars($trx['id_transaksi_kode']) ?></b></td>
                            <td>
                                <b><?= htmlspecialchars($trx['nama_pelanggan'] ?? 'Pelanggan Restoran') ?></b>
                                <?php if (!empty($trx['no_meja'])): ?><small class="text-muted"> · Meja <?= htmlspecialchars($trx['no_meja']) ?></small><?php endif; ?><br>
                                <span class="pill-status <?= $badgePesananClass ?>">● <?= htmlspecialchars($mPesanan) ?></span>
                            </td>
                            <td><?= htmlspecialchars($trx['rincian_menu']) ?></td>
                            <td><span class="pill-status <?= ($trx['metode_pembayaran'] ?? '') == 'QRIS' ? 'pill-qris' : 'pill-tunai' ?>">● <?= htmlspecialchars($trx['metode_pembayaran']) ?></span></td>
                            <td style="text-align:right;"><b style="font-size:14px;"><?= $rpL($trx['total_bayar']) ?></b></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" style="text-align:center;padding:28px;color:var(--text-muted)">Belum ada transaksi pada tanggal ini.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="pagination-bar">
                <span>Menampilkan <?= $jumlahTrx ?> transaksi pada <?= htmlspecialchars(date('d/m/Y', strtotime($tanggalAktif))) ?></span>
            </div>
        </div>
    </section>
</div>

<!-- MODAL INPUT KAS FISIK -->
<div class="kas-overlay" id="kasModal" onclick="if(event.target===this) closeKasModal()">
    <div class="kas-modal">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
            <h3 class="disp" style="color:var(--red-600); font-size:18px;">INPUT JUMLAH KAS FISIK</h3>
            <button type="button" onclick="closeKasModal()" style="border:0; background:none; font-weight:700; cursor:pointer; font-size:18px; color:var(--text-muted);">✕</button>
        </div>

        <form action="index.php?page=simpan-kas" method="POST" style="display:flex; flex-direction:column; gap:16px;">
            <input type="hidden" name="tanggal" value="<?= htmlspecialchars($tanggalAktif) ?>">
            <p style="font-size:12px; color:var(--text-muted);">Masukkan total uang kas fisik nyata di laci kasir untuk audit selisih otomatis.</p>

            <div>
                <label style="font-size:10px; font-weight:700; text-transform:uppercase; display:block; margin-bottom:4px;">JUMLAH UANG KAS FISIK NYATA (RP) *</label>
                <input type="number" name="kas_fisik" style="width:100%; min-height:44px; border-radius:14px; border:1.5px solid var(--cream-300); padding:0 14px; font-weight:700;" placeholder="0" value="<?= htmlspecialchars((string)($dataKas['kas_fisik'] ?? '')) ?>" required>
            </div>

            <div style="background:var(--cream-50); border:1.5px solid var(--cream-200); border-radius:14px; padding:12px;">
                <div style="display:flex; justify-content:space-between; font-size:12px; margin-bottom:4px;">
                    <span style="color:var(--text-muted);">Kas Sistem (POS):</span>
                    <b><?= $rpL($dataKas['kas_sistem'] ?? 0) ?></b>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:12px; margin-bottom:8px;">
                    <span style="color:var(--text-muted);">Kas Fisik Kasir:</span>
                    <b><?= $rpL($dataKas['kas_fisik'] ?? 0) ?></b>
                </div>
                <hr style="border:0; border-top:1px dashed var(--cream-300); margin:4px 0;">
                <div style="display:flex; justify-content:space-between; align-items:center; font-size:12px; margin-top:6px;">
                    <b>Hasil Selisih:</b>
                    <span class="pill-status pill-dinein" style="background:var(--green-100); color:var(--success);">● <?= htmlspecialchars($dataKas['status_selisih'] ?? 'Sesuai') ?> (<?= $rpL($dataKas['selisih'] ?? 0) ?>)</span>
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:8px;">
                <button type="button" class="mc-btn mc-btn--ghost" onclick="closeKasModal()">BATAL</button>
                <button type="submit" class="mc-btn" style="background:var(--red-600); color:#fff;">SIMPAN DATA KAS</button>
            </div>
        </form>
    </div>
</div>

<script>
function openKasModal()  { document.getElementById('kasModal').classList.add('on'); }
function closeKasModal() { document.getElementById('kasModal').classList.remove('on'); }
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeKasModal(); });
</script>