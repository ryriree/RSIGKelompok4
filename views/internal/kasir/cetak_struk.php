<?php
// Cetak struk. Data dari StrukController: $riwayat, $strukAktif, $pesanStruk
if (!function_exists('eSk')) {
    function eSk($v) { return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8'); }
}
$riwayat    = $riwayat ?? [];
$strukAktif = $strukAktif ?? null;
$rp = function ($n) { return 'Rp' . number_format((float)$n, 0, ',', '.'); };
?>
<style>
.sk { font-size: 13px; }
.sk .desc { font-size: 12px; color: var(--text-muted); margin-bottom: 16px; }
.sk .notice { padding: 12px 16px; margin-bottom: 18px; border-radius: 12px; background: var(--green-100); color: var(--success); font-size: 12px; font-weight: 600; }
.sk .grid { display: grid; grid-template-columns: 7fr 5fr; gap: 24px; align-items: start; }
.sk .card { background: #fff; border: 1.5px solid var(--cream-300); border-radius: 20px; box-shadow: var(--shadow-1); }
.sk .card-head { display: flex; justify-content: space-between; align-items: center; padding: 16px 18px; font-family: var(--font-display); font-size: 16px; color: var(--red-600); text-transform: uppercase; }
.sk .list { display: flex; flex-direction: column; gap: 10px; padding: 0 14px 16px; max-height: 640px; overflow-y: auto; }
.sk .r-item { display: block; text-decoration: none; color: inherit; border: 1.5px solid var(--cream-300); border-radius: 16px; padding: 12px 14px; background: var(--cream-50); }
.sk .r-item:hover { border-color: var(--red-600); }
.sk .r-item.active { border-color: var(--red-600); background: #fff; box-shadow: 0 0 0 2px var(--red-600); }
.sk .r-top { display: flex; justify-content: space-between; gap: 8px; align-items: center; }
.sk .r-kode { font-weight: 700; font-size: 13px; }
.sk .r-meta { font-size: 11px; color: var(--text-muted); margin-top: 2px; }
.sk .r-total { font-weight: 700; color: var(--red-600); font-size: 14px; white-space: nowrap; }
.sk .badge { display: inline-block; border-radius: 999px; padding: 4px 10px; font-size: 10px; font-weight: 700; background: var(--green-100); color: var(--success); }
.sk .empty { padding: 30px 18px; text-align: center; color: var(--text-muted); }

.sk .side { position: sticky; top: 90px; display: flex; flex-direction: column; gap: 14px; }
.sk .paper { background: #fff; border: 1.5px solid var(--cream-300); border-radius: 16px; padding: 22px; font-family: 'Courier New', monospace; font-size: 12px; color: var(--ink-900); box-shadow: var(--shadow-1); }
.sk .paper h2 { text-align: center; margin: 0; font-size: 20px; color: var(--red-600); }
.sk .paper .c { text-align: center; font-size: 11px; color: var(--text-muted); margin: 2px 0; }
.sk .paper hr { border: 0; border-top: 2px dashed #bbb; margin: 12px 0; }
.sk .row { display: flex; justify-content: space-between; gap: 10px; margin: 3px 0; }
.sk .row .k { color: #666; }
.sk .it { display: flex; justify-content: space-between; gap: 10px; margin: 6px 0; }
.sk .it small { display: block; color: #777; font-size: 10px; }
.sk .total { display: flex; justify-content: space-between; font-size: 16px; font-weight: 700; color: var(--red-600); }
.sk .thanks { text-align: center; font-weight: 700; margin-top: 12px; }
.sk .btn-print { width: 100%; border: 0; border-radius: 999px; background: var(--red-600); color: #fff; padding: 14px; font-size: 12px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; cursor: pointer; }
.sk .btn-print:hover { background: var(--red-700); }

@media (max-width: 1000px) { .sk .grid { grid-template-columns: 1fr; } .sk .side { position: static; } }
@media print {
    body * { visibility: hidden; }
    #printable-receipt, #printable-receipt * { visibility: visible; }
    #printable-receipt { position: absolute; left: 0; top: 0; width: 80mm; margin: 0; padding: 10px; border: 0; box-shadow: none; border-radius: 0; }
}
</style>

<div class="sk">
    <p class="desc">Riwayat pembayaran yang sudah lunas. Pilih transaksi untuk melihat dan mencetak struk.</p>

    <?php if (!empty($pesanStruk)): ?><div class="notice"><?= eSk($pesanStruk['teks']) ?></div><?php endif; ?>

    <div class="grid">
        <section class="card">
            <div class="card-head"><span>Riwayat Transaksi Lunas</span><span class="badge"><?= count($riwayat) ?> transaksi</span></div>
            <div class="list">
                <?php if (!$riwayat): ?>
                    <div class="empty">Belum ada transaksi lunas.</div>
                <?php else: foreach ($riwayat as $p):
                    $isAktif = $strukAktif && (int)$strukAktif['pesanan']['id_pesanan'] === (int)$p['id_pesanan'];
                ?>
                    <a class="r-item <?= $isAktif ? 'active' : '' ?>" href="index.php?page=struk&id=<?= (int)$p['id_pesanan'] ?>">
                        <div class="r-top">
                            <div>
                                <div class="r-kode">#<?= eSk($p['kode_pesanan']) ?></div>
                                <div class="r-meta">
                                    <?= $p['no_meja'] ? 'Meja ' . eSk($p['no_meja']) : eSk($p['jenis_pesanan']) ?>
                                    · <?= eSk($p['metode']) ?>
                                    · <?= eSk(date('d/m H:i', strtotime($p['waktu_bayar'] ?? $p['waktu_pesan']))) ?>
                                </div>
                            </div>
                            <div class="r-total"><?= $rp($p['total_harga']) ?></div>
                        </div>
                    </a>
                <?php endforeach; endif; ?>
            </div>
        </section>

        <aside class="side">
            <?php if (!$strukAktif): ?>
                <div class="card"><div class="empty">Pilih transaksi di sebelah kiri untuk melihat struk.</div></div>
            <?php else:
                $ps = $strukAktif['pesanan'];
                $items = $strukAktif['items'];
                $tunai = ($ps['metode'] ?? '') === 'Tunai';
            ?>
            <div id="printable-receipt" class="paper">
                <h2>MAKY CHICKEN</h2>
                <div class="c">Jl. Veteran No. 10, Malang</div>
                <div class="c">Telp: (0341) 555-899</div>
                <div class="c"><b>STRUK PEMBAYARAN</b></div>
                <hr>
                <div class="row"><span class="k">No. Nota</span><span><?= eSk($ps['kode_transaksi'] ?? $ps['kode_pesanan']) ?></span></div>
                <div class="row"><span class="k">Pesanan</span><span><?= eSk($ps['kode_pesanan']) ?></span></div>
                <div class="row"><span class="k">Meja/Tipe</span><span><?= $ps['no_meja'] ? 'Meja ' . eSk($ps['no_meja']) : eSk($ps['jenis_pesanan']) ?></span></div>
                <div class="row"><span class="k">Waktu</span><span><?= eSk(date('d/m/Y H:i', strtotime($ps['waktu_bayar'] ?? $ps['waktu_pesan']))) ?></span></div>
                <div class="row"><span class="k">Kasir</span><span><?= eSk($_SESSION['nama'] ?? '-') ?></span></div>
                <div class="row"><span class="k">Metode</span><span><?= eSk($ps['metode']) ?></span></div>
                <hr>
                <?php foreach ($items as $it): ?>
                    <div class="it">
                        <div>
                            <?= eSk($it['nama_menu']) ?> x<?= (int)$it['jumlah'] ?>
                            <small>@ <?= $rp($it['harga']) ?></small>
                            <?php if (!empty($it['catatan'])): ?><small>Catatan: <?= eSk($it['catatan']) ?></small><?php endif; ?>
                        </div>
                        <div><?= $rp($it['subtotal']) ?></div>
                    </div>
                <?php endforeach; ?>
                <hr>
                <div class="total"><span>TOTAL</span><span><?= $rp($ps['total_harga']) ?></span></div>
                <?php if ($tunai): ?>
                    <div class="row"><span class="k">Tunai</span><span><?= $rp($ps['uang_diterima'] ?? $ps['total_harga']) ?></span></div>
                    <div class="row"><span class="k">Kembalian</span><span><?= $rp($ps['kembalian'] ?? 0) ?></span></div>
                <?php endif; ?>
                <hr>
                <div class="thanks">Terima kasih atas kunjungan Anda!</div>
            </div>
            <button type="button" class="btn-print" onclick="window.print()">Cetak Struk Sekarang</button>
            <?php endif; ?>
        </aside>
    </div>
</div>