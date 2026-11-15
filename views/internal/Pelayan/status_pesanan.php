<?php
// Antrean pelayan. Data dari PesananController: $antreanPesanan, $pesanPesanan
if (!function_exists('ePl')) {
    function ePl($v) { return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8'); }
}
$antreanPesanan = $antreanPesanan ?? [];
$totalProses = 0; $totalSelesai = 0;
foreach ($antreanPesanan as $it) {
    if ($it['status'] === 'Selesai') $totalSelesai++; else $totalProses++;
}
?>
<style>
.st { font-size: 13px; }
.st .desc { font-size: 12px; color: var(--text-muted); margin-bottom: 16px; }
.st .notice { padding: 12px 16px; margin-bottom: 18px; border-radius: 12px; background: var(--green-100); color: var(--success); font-size: 12px; font-weight: 600; }
.st .kpi-grid { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 16px; margin-bottom: 24px; }
.st .kpi { background: #fff; border: 1.5px solid var(--cream-300); border-radius: 20px; padding: 18px 20px; box-shadow: var(--shadow-1); }
.st .kpi.accent { background: var(--yellow-500); border-color: var(--yellow-500); }
.st .kpi-label { font-size: 10px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--text-muted); }
.st .kpi.accent .kpi-label { color: var(--ink-900); }
.st .kpi-value { font-family: var(--font-display); font-size: 38px; line-height: 1; margin: 12px 0 0; color: var(--red-600); }
.st .kpi-value.green { color: var(--success); }
.st .kpi-value.dark { color: var(--ink-900); }

.st .cards { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; align-items: start; }
.st .order { background: #fff; border: 1.5px solid var(--cream-300); border-left: 8px solid var(--yellow-500); border-radius: 20px; padding: 18px; box-shadow: var(--shadow-1); display: flex; flex-direction: column; gap: 14px; }
.st .order.done { border-left-color: var(--success); }
.st .o-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 8px; }
.st .o-meja { font-family: var(--font-display); font-size: 22px; }
.st .o-kode { font-size: 11px; color: var(--text-muted); margin-top: 4px; }
.st .badge { display: inline-block; border-radius: 999px; padding: 4px 10px; font-size: 10px; font-weight: 700; white-space: nowrap; background: var(--cream-200); color: var(--red-700); }
.st .badge.done { background: var(--green-100); color: var(--success); }
.st .tipe { display: inline-block; margin-left: 6px; border-radius: 999px; padding: 3px 9px; font-size: 10px; font-weight: 700; background: var(--cream-200); color: var(--text-muted); vertical-align: middle; }
.st .rincian { background: var(--cream-50); border: 1px solid var(--cream-200); border-radius: 14px; padding: 12px 14px; }
.st .rincian b { display: block; font-size: 10px; letter-spacing: .06em; text-transform: uppercase; margin-bottom: 6px; }
.st .rincian p { margin: 0; font-size: 12px; line-height: 1.5; }
.st .o-foot { display: flex; justify-content: space-between; align-items: center; font-size: 11px; color: var(--text-muted); border-top: 1px solid var(--cream-200); padding-top: 10px; }
.st .o-foot .tot { font-weight: 700; color: var(--red-600); font-size: 14px; }
.st .btn-done { width: 100%; border: 0; border-radius: 999px; background: var(--success); color: #fff; padding: 12px; font-size: 11px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; cursor: pointer; }
.st .btn-done:hover { filter: brightness(.92); }
.st .btn-off { width: 100%; border: 1.5px solid var(--cream-300); border-radius: 999px; background: var(--cream-50); color: var(--text-muted); padding: 12px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
.st .empty { grid-column: 1 / -1; background: #fff; border: 1.5px solid var(--cream-300); border-radius: 20px; padding: 50px 20px; text-align: center; color: var(--text-muted); }

@media (max-width: 760px) { .st .kpi-grid { grid-template-columns: 1fr; } }
</style>

<div class="st">
    <p class="desc">Pantau pesanan yang sudah dibayar dan tandai selesai setelah diantar ke meja.</p>

    <?php if (!empty($pesanPesanan)): ?><div class="notice"><?= ePl($pesanPesanan) ?></div><?php endif; ?>

    <section class="kpi-grid">
        <article class="kpi"><div class="kpi-label">Dalam Proses / Perlu Diantar</div><div class="kpi-value"><?= $totalProses ?></div></article>
        <article class="kpi"><div class="kpi-label">Selesai Hari Ini</div><div class="kpi-value green"><?= $totalSelesai ?></div></article>
        <article class="kpi accent"><div class="kpi-label">Estimasi Waktu Saji</div><div class="kpi-value dark">10-15 mnt</div></article>
    </section>

    <section class="cards">
        <?php if (!$antreanPesanan): ?>
            <div class="empty">Tidak ada antrean pesanan aktif saat ini.<br><small>Pesanan akan muncul di sini setelah dibayar di kasir.</small></div>
        <?php else: foreach ($antreanPesanan as $p): $selesai = $p['status'] === 'Selesai'; ?>
            <article class="order <?= $selesai ? 'done' : '' ?>">
                <div class="o-head">
                    <div>
                        <div class="o-meja">
                            <?= $p['no_meja'] ? 'MEJA ' . ePl($p['no_meja']) : 'TAKE AWAY' ?>
                            <span class="tipe"><?= ePl($p['jenis_pesanan']) ?></span>
                        </div>
                        <div class="o-kode">Kode: <b><?= ePl($p['kode_pesanan']) ?></b></div>
                    </div>
                    <span class="badge <?= $selesai ? 'done' : '' ?>"><?= $selesai ? 'Selesai' : 'Dalam Proses' ?></span>
                </div>

                <div class="rincian">
                    <b>Rincian Pesanan &amp; Catatan</b>
                    <p><?= !empty($p['rincian_item']) ? ePl($p['rincian_item']) : '<i>Tidak ada rincian item.</i>' ?></p>
                </div>

                <div class="o-foot">
                    <span>Dipesan: <b><?= ePl(date('H:i', strtotime($p['waktu_pesan']))) ?> WIB</b></span>
                    <span class="tot">Rp<?= number_format((float)$p['total_harga'], 0, ',', '.') ?></span>
                </div>

                <?php if (!$selesai): ?>
                    <form method="POST" action="index.php?page=pesanan-status" style="margin:0">
                        <input type="hidden" name="id_pesanan" value="<?= (int)$p['id_pesanan'] ?>">
                        <input type="hidden" name="status" value="Selesai">
                        <button type="submit" class="btn-done">✓ Tandai Selesai / Sudah Diantar</button>
                    </form>
                <?php else: ?>
                    <div class="btn-off" style="text-align:center">Pesanan Sudah Selesai</div>
                <?php endif; ?>
            </article>
        <?php endforeach; endif; ?>
    </section>
</div>