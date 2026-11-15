<?php
// Halaman bayar. Data dari BayarController: $pesanan, $items, $pesanBayar, $simulasi, $kodeQris
if (!function_exists('eBy')) {
    function eBy($v) { return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8'); }
}
$rp = function ($n) { return 'Rp' . number_format((float)$n, 0, ',', '.'); };
$batal = ($pesanan['status'] ?? '') === 'Dibatalkan';
?>
<style>
.by { padding: 28px 0; }
.by .head { text-align: center; margin-bottom: 22px; }
.by .head h1 { font-size: clamp(26px, 5vw, 42px); color: var(--primary); }
.by .head p { color: var(--text-muted); font-size: 14px; }
.by .notice { padding: 12px 16px; margin-bottom: 16px; border-radius: 14px; font-size: 13px; font-weight: 600; background: var(--red-100); color: var(--red-700); }
.by .cols { display: grid; grid-template-columns: 5fr 7fr; gap: 24px; align-items: start; }
.by .card { background: #fff; border: 1.5px solid var(--border); border-radius: 24px; box-shadow: var(--shadow-1); padding: 22px; }
.by .card h2 { font-size: 20px; color: var(--primary); margin-bottom: 12px; }
.by .kode { font-family: var(--font-display); color: var(--primary); font-size: 24px; }
.by .muted { color: var(--text-muted); font-size: 13px; }
.by .line { display: flex; justify-content: space-between; gap: 10px; padding: 8px 0; border-bottom: 1.5px dashed var(--border); font-size: 14px; }
.by .line small { display: block; color: var(--text-muted); font-size: 11px; font-style: italic; }
.by .total { display: flex; justify-content: space-between; align-items: center; margin-top: 14px; padding: 14px 16px; background: var(--cream-50); border: 1.5px solid var(--border); border-radius: 16px; }
.by .total b { font-family: var(--font-display); font-weight: 400; font-size: 28px; color: var(--primary); }
.by .tabs { display: grid; grid-template-columns: 1fr 1fr; gap: 6px; padding: 4px; background: var(--cream-50); border: 1.5px solid var(--border); border-radius: 999px; margin-bottom: 18px; }
.by .tab { min-height: 44px; border: 0; border-radius: 999px; background: none; font-weight: 700; font-size: 13px; letter-spacing: .06em; text-transform: uppercase; cursor: pointer; }
.by .tab.on { background: var(--primary); color: var(--cream-50); }
.by .panel { text-align: center; }
.by .panel[hidden] { display: none; }
.by .qr { width: 200px; height: 200px; margin: 8px auto 12px; border-radius: 16px; border: 1.5px solid var(--border); background: repeating-conic-gradient(var(--ink-900) 0 25%, #fff 0 50%) 0 0/24px 24px; }
.by .nominal { font-family: var(--font-display); font-size: 34px; color: var(--primary); }
.by .info { background: var(--blue-100); color: var(--blue-600); border-radius: 14px; padding: 12px 16px; font-size: 13px; margin: 14px 0; text-align: left; }
.by .demo { background: var(--yellow-100); color: var(--ink-900); border-radius: 14px; padding: 12px 16px; font-size: 12px; margin-top: 14px; text-align: left; }
.by .codebox { background: var(--cream-50); border: 2px dashed var(--primary); border-radius: 18px; padding: 16px; margin: 8px 0 14px; }
.by .wait { display: inline-flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text-muted); }
.by .dot { width: 9px; height: 9px; border-radius: 50%; background: var(--yellow-600); animation: byp 1.2s infinite; }
@keyframes byp { 50% { opacity: .25; } }
@media (max-width: 860px) { .by .cols { grid-template-columns: 1fr; } }
</style>

<div class="by">
    <div class="head">
        <h1>Pembayaran</h1>
        <p>Meja <?= eBy($pesanan['no_meja']) ?> · <?= eBy($pesanan['jenis_pesanan']) ?></p>
    </div>

    <?php if (!empty($pesanBayar)): ?><div class="notice"><?= eBy($pesanBayar['teks']) ?></div><?php endif; ?>

    <div class="cols">
        <section class="card">
            <h2>Rincian pesanan</h2>
            <div class="muted">Kode pesanan</div>
            <div class="kode"><?= eBy($pesanan['kode_pesanan']) ?></div>
            <div style="margin-top:12px">
                <?php foreach ($items as $it): ?>
                    <div class="line">
                        <div>
                            <?= (int)$it['jumlah'] ?>× <?= eBy($it['nama_menu']) ?>
                            <?php if (!empty($it['catatan'])): ?><small><?= eBy($it['catatan']) ?></small><?php endif; ?>
                        </div>
                        <div><?= $rp($it['subtotal']) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="total"><span>Total bayar</span><b><?= $rp($pesanan['total_harga']) ?></b></div>
            <p class="muted" style="margin-top:8px">Harga sudah termasuk pajak restoran (PB1).</p>
        </section>

        <section class="card">
            <?php if ($batal): ?>
                <h2>Pesanan dibatalkan</h2>
                <p class="muted">Pesanan ini sudah dibatalkan dan tidak dapat dibayar.</p>
                <p style="margin-top:14px"><a class="btn" href="index.php?page=pesan">Buat Pesanan Baru</a></p>
            <?php else: ?>
            <h2>Pilih metode</h2>
            <div class="tabs">
                <button type="button" class="tab on" data-p="qris">QRIS</button>
                <button type="button" class="tab" data-p="kasir">Bayar di Kasir</button>
            </div>

            <div class="panel" id="p-qris">
                <div class="qr" role="img" aria-label="Kode QRIS"></div>
                <div class="nominal"><?= $rp($pesanan['total_harga']) ?></div>
                <p class="muted">Referensi: <?= eBy($kodeQris) ?></p>
                <div class="info">Pindai dengan aplikasi pembayaran favoritmu, lalu pesanan otomatis diproses setelah pembayaran terverifikasi.</div>
                <?php if ($simulasi): ?>
                    <form method="POST" action="index.php?page=bayar-qris">
                        <input type="hidden" name="kode" value="<?= eBy($pesanan['kode_pesanan']) ?>">
                        <button type="submit" class="btn" style="width:100%">Simulasi: saya sudah bayar</button>
                    </form>
                    <div class="demo"><b>Mode simulasi.</b> Belum terhubung ke payment gateway, jadi tombol ini hanya untuk demo.</div>
                <?php endif; ?>
            </div>

            <div class="panel" id="p-kasir" hidden>
                <p class="muted">Tunjukkan kode ini ke kasir:</p>
                <div class="codebox">
                    <div class="kode" id="kodeTeks"><?= eBy($pesanan['kode_pesanan']) ?></div>
                    <div class="muted">Meja <?= eBy($pesanan['no_meja']) ?> · <?= $rp($pesanan['total_harga']) ?></div>
                </div>
                <button type="button" class="btn btn--outline btn--sm" id="salinKode">Salin kode</button>
                <div class="info" style="margin-top:16px">Setelah kasir memverifikasi pembayaran, halaman ini pindah sendiri ke invoice dan pesananmu langsung diproses dapur.</div>
                <div class="wait"><span class="dot"></span> Menunggu kasir...</div>
            </div>
            <?php endif; ?>
        </section>
    </div>
</div>

<script>
(function () {
    document.querySelectorAll('.by .tab').forEach(t => t.addEventListener('click', function () {
        document.querySelectorAll('.by .tab').forEach(x => x.classList.toggle('on', x === this));
        document.getElementById('p-qris').hidden = this.dataset.p !== 'qris';
        document.getElementById('p-kasir').hidden = this.dataset.p !== 'kasir';
    }));

    const salin = document.getElementById('salinKode');
    if (salin) salin.addEventListener('click', async function () {
        try { await navigator.clipboard.writeText(document.getElementById('kodeTeks').textContent.trim()); toast('Kode disalin.'); }
        catch (e) { toast('Salin manual kode di atas.'); }
    });

    // Cek status tiap 5 detik; pindah ke invoice begitu lunas
    <?php if (!$batal): ?>
    const kode = <?= json_encode($pesanan['kode_pesanan']) ?>;
    setInterval(async function () {
        try {
            const r = await fetch('index.php?page=bayar-status&kode=' + encodeURIComponent(kode), { cache: 'no-store' });
            const d = await r.json();
            if (d.lunas) location.href = 'index.php?page=invoice&kode=' + encodeURIComponent(kode);
        } catch (e) { /* abaikan, coba lagi */ }
    }, 5000);
    <?php endif; ?>
})();
</script>