<?php
// Antrean POS Kasir. Data dari PosController: $antrean, $aktif, $items, $ringkasan, $pesanPos
if (!function_exists('ePos')) {
    function ePos($v) { return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8'); }
}
$antrean   = $antrean ?? [];
$aktif     = $aktif ?? null;
$items     = $items ?? [];
$ringkasan = $ringkasan ?? ['jumlah' => 0, 'total' => 0];
$totalAktif = $aktif ? (float)$aktif['total_harga'] : 0;
$rp = function ($n) { return 'Rp' . number_format((float)$n, 0, ',', '.'); };

// Tombol nominal cepat: uang pas + pembulatan ke atas
$chips = [];
if ($totalAktif > 0) {
    $chips = array_unique([
        (int)$totalAktif,
        (int)(ceil($totalAktif / 10000) * 10000),
        (int)(ceil($totalAktif / 50000) * 50000),
        (int)(ceil($totalAktif / 100000) * 100000),
    ]);
    sort($chips);
}
?>
<style>
.ps { font-size: 13px; }
.ps .desc { font-size: 12px; color: var(--text-muted); margin-bottom: 16px; }
.ps .notice { padding: 12px 16px; margin-bottom: 18px; border-radius: 12px; font-size: 12px; font-weight: 600; }
.ps .notice.ok { background: var(--green-100); color: var(--success); }
.ps .notice.error { background: var(--red-100); color: var(--red-700); }

.ps .kpi-grid { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 16px; margin-bottom: 24px; }
.ps .kpi { background: #fff; border: 1.5px solid var(--cream-300); border-radius: 20px; padding: 18px 20px; box-shadow: var(--shadow-1); }
.ps .kpi-label { font-size: 10px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--text-muted); }
.ps .kpi-value { font-family: var(--font-display); font-size: 34px; line-height: 1; margin: 12px 0 0; color: var(--red-600); }
.ps .kpi-value.green { color: var(--success); }

.ps .grid { display: grid; grid-template-columns: 5fr 7fr; gap: 24px; align-items: start; }
.ps .card { background: #fff; border: 1.5px solid var(--cream-300); border-radius: 20px; box-shadow: var(--shadow-1); }
.ps .card-head { display: flex; justify-content: space-between; align-items: center; gap: 10px; padding: 16px 18px; font-family: var(--font-display); font-size: 16px; color: var(--red-600); text-transform: uppercase; }
.ps .cari { padding: 0 18px 12px; }
.ps .cari input { width: 100%; height: 40px; border: 1.5px solid var(--cream-300); border-radius: 999px; padding: 0 16px; background: var(--cream-50); outline: none; font-size: 12px; }
.ps .queue { display: flex; flex-direction: column; gap: 10px; padding: 0 14px 16px; max-height: 620px; overflow-y: auto; }
.ps .q-item { display: block; text-decoration: none; color: inherit; border: 1.5px solid var(--cream-300); border-radius: 16px; padding: 12px 14px; background: var(--cream-50); }
.ps .q-item:hover { border-color: var(--red-600); }
.ps .q-item.active { border-color: var(--red-600); background: #fff; box-shadow: 0 0 0 2px var(--red-600); }
.ps .q-top { display: flex; justify-content: space-between; gap: 8px; align-items: flex-start; }
.ps .q-kode { font-weight: 700; font-size: 13px; }
.ps .q-meta { font-size: 11px; color: var(--text-muted); margin-top: 2px; }
.ps .q-total { font-weight: 700; color: var(--red-600); margin-top: 8px; font-size: 14px; }
.ps .badge { display: inline-block; border-radius: 999px; padding: 4px 10px; font-size: 10px; font-weight: 700; white-space: nowrap; background: var(--cream-200); color: var(--red-700); }
.ps .empty { padding: 30px 18px; text-align: center; color: var(--text-muted); }

.ps .detail { padding: 20px; display: flex; flex-direction: column; gap: 16px; }
.ps .d-head { display: flex; justify-content: space-between; align-items: center; gap: 10px; border-bottom: 1.5px solid var(--cream-200); padding-bottom: 12px; }
.ps .d-head h2 { margin: 0; font-size: 17px; color: var(--red-600); text-transform: uppercase; }
.ps .d-sub { font-size: 10px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--text-muted); }
.ps .meja { background: var(--cream-50); border: 1.5px solid var(--cream-300); border-radius: 999px; padding: 6px 14px; font-weight: 700; font-size: 12px; white-space: nowrap; }

.ps .items { width: 100%; border-collapse: collapse; font-size: 12px; }
.ps .items th { text-align: left; font-size: 10px; letter-spacing: .06em; text-transform: uppercase; color: var(--text-muted); padding: 6px 4px; border-bottom: 1px solid var(--cream-200); }
.ps .items td { padding: 8px 4px; border-bottom: 1px solid var(--cream-200); vertical-align: top; }
.ps .items .r { text-align: right; white-space: nowrap; }
.ps .catatan { display: block; font-size: 10px; color: var(--text-muted); font-style: italic; }

.ps .tabs { display: grid; grid-template-columns: repeat(3, 1fr); gap: 6px; padding: 4px; background: var(--cream-50); border: 1.5px solid var(--cream-300); border-radius: 999px; }
.ps .tab { border: 0; border-radius: 999px; background: transparent; padding: 10px; font-size: 11px; font-weight: 700; cursor: pointer; }
.ps .tab.active { background: var(--red-600); color: #fff; }

.ps .total-box { display: flex; justify-content: space-between; align-items: center; gap: 10px; padding: 16px 18px; background: var(--cream-50); border: 1.5px solid var(--cream-300); border-radius: 16px; }
.ps .total-box .nilai { font-family: var(--font-display); font-size: 30px; color: var(--red-600); }

.ps .field label { display: block; font-size: 11px; font-weight: 700; margin-bottom: 6px; }
.ps .field input { width: 100%; border: 1.5px solid var(--cream-300); border-radius: 14px; padding: 12px 14px; font-size: 18px; font-weight: 700; outline: none; }
.ps .chips { display: flex; flex-wrap: wrap; gap: 8px; }
.ps .chip { border: 1.5px solid var(--cream-300); background: var(--cream-50); border-radius: 999px; padding: 9px 14px; font-size: 11px; font-weight: 700; cursor: pointer; }
.ps .chip.active { background: var(--red-600); border-color: var(--red-600); color: #fff; }

.ps .kembalian { display: flex; justify-content: space-between; align-items: center; padding: 14px 18px; border: 1.5px dashed var(--cream-300); border-radius: 16px; }
.ps .kembalian .nilai { font-family: var(--font-display); font-size: 26px; color: var(--success); }
.ps .kembalian.kurang .nilai { color: var(--red-700); }

.ps .btn-primary { width: 100%; border: 0; border-radius: 999px; background: var(--red-600); color: #fff; padding: 15px 20px; font-size: 12px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; cursor: pointer; }
.ps .btn-primary:hover { background: var(--red-700); }
.ps .btn-primary:disabled { opacity: .5; cursor: not-allowed; }

@media (max-width: 1000px) { .ps .grid { grid-template-columns: 1fr; } .ps .kpi-grid { grid-template-columns: 1fr; } }
</style>

<div class="ps">
    <p class="desc">Antrean pesanan yang menunggu pembayaran di kasir. Pilih pesanan, terima pembayaran, lalu selesaikan.</p>

    <?php if (!empty($pesanPos)): ?>
        <div class="notice <?= ePos($pesanPos['tipe']) ?>"><?= ePos($pesanPos['teks']) ?></div>
    <?php endif; ?>

    <section class="kpi-grid">
        <article class="kpi"><div class="kpi-label">Total Antrean</div><div class="kpi-value"><?= count($antrean) ?></div></article>
        <article class="kpi"><div class="kpi-label">Lunas Hari Ini</div><div class="kpi-value green"><?= (int)$ringkasan['jumlah'] ?></div></article>
        <article class="kpi"><div class="kpi-label">Pemasukan Hari Ini</div><div class="kpi-value"><?= $rp($ringkasan['total']) ?></div></article>
    </section>

    <div class="grid">
        <!-- KIRI: ANTREAN -->
        <section class="card">
            <div class="card-head"><span>Daftar Antrean</span><span class="badge"><?= count($antrean) ?> pesanan</span></div>
            <div class="cari"><input type="search" id="cariAntrean" placeholder="Cari meja / kode pesanan..."></div>
            <div class="queue" id="daftarAntrean">
                <?php if (!$antrean): ?>
                    <div class="empty">Tidak ada pesanan yang menunggu pembayaran.</div>
                <?php else: foreach ($antrean as $p):
                    $isAktif = $aktif && $p['kode_pesanan'] === $aktif['kode_pesanan'];
                    $cari = mb_strtolower($p['kode_pesanan'] . ' meja ' . ($p['no_meja'] ?? ''), 'UTF-8');
                ?>
                    <a class="q-item <?= $isAktif ? 'active' : '' ?>" data-cari="<?= ePos($cari) ?>"
                       href="index.php?page=pos&kode=<?= urlencode($p['kode_pesanan']) ?>">
                        <div class="q-top">
                            <div>
                                <div class="q-kode">#<?= ePos($p['kode_pesanan']) ?></div>
                                <div class="q-meta">
                                    <?= $p['no_meja'] ? 'Meja ' . ePos($p['no_meja']) : '-' ?> · <?= ePos($p['jenis_pesanan']) ?>
                                    · <?= ePos(date('H:i', strtotime($p['waktu_pesan']))) ?>
                                </div>
                            </div>
                            <span class="badge"><?= ePos($p['status']) ?></span>
                        </div>
                        <div class="q-total"><?= $rp($p['total_harga']) ?></div>
                    </a>
                <?php endforeach; endif; ?>
            </div>
        </section>

        <!-- KANAN: DETAIL & PEMBAYARAN -->
        <section class="card">
            <?php if (!$aktif): ?>
                <div class="empty">Pilih pesanan dari antrean untuk memproses pembayaran.</div>
            <?php else: ?>
            <div class="detail">
                <div class="d-head">
                    <div>
                        <div class="d-sub">Pembayaran Pesanan</div>
                        <h2>#<?= ePos($aktif['kode_pesanan']) ?></h2>
                    </div>
                    <div class="meja"><?= $aktif['no_meja'] ? 'Meja ' . ePos($aktif['no_meja']) : ePos($aktif['jenis_pesanan']) ?></div>
                </div>

                <table class="items">
                    <thead><tr><th>Item</th><th class="r">Qty</th><th class="r">Subtotal</th></tr></thead>
                    <tbody>
                    <?php if (!$items): ?>
                        <tr><td colspan="3" style="text-align:center;padding:16px">Rincian item tidak ditemukan.</td></tr>
                    <?php else: foreach ($items as $it): ?>
                        <tr>
                            <td>
                                <?= ePos($it['nama_menu']) ?>
                                <?php if (!empty($it['catatan'])): ?><span class="catatan">Catatan: <?= ePos($it['catatan']) ?></span><?php endif; ?>
                            </td>
                            <td class="r">x<?= (int)$it['jumlah'] ?></td>
                            <td class="r"><?= $rp($it['subtotal']) ?></td>
                        </tr>
                    <?php endforeach; endif; ?>
                    </tbody>
                </table>

                <form method="POST" action="index.php?page=pos-bayar" id="formBayar" style="display:flex;flex-direction:column;gap:16px">
                    <input type="hidden" name="kode_pesanan" value="<?= ePos($aktif['kode_pesanan']) ?>">
                    <input type="hidden" name="metode" id="metode" value="Tunai">

                    <div class="tabs">
                        <button type="button" class="tab active" data-metode="Tunai">Tunai</button>
                        <button type="button" class="tab" data-metode="QRIS">QRIS</button>
                        <button type="button" class="tab" data-metode="Debit EDC">Debit EDC</button>
                    </div>

                    <div class="total-box">
                        <div><div class="d-sub">Total Tagihan</div></div>
                        <div class="nilai"><?= $rp($totalAktif) ?></div>
                    </div>

                    <div id="blokTunai" style="display:flex;flex-direction:column;gap:16px">
                        <div class="field">
                            <label for="uang">Uang Diterima (Rp) *</label>
                            <input type="text" inputmode="numeric" name="uang_diterima" id="uang"
                                   value="<?= number_format($totalAktif, 0, ',', '.') ?>" autocomplete="off">
                        </div>
                        <div class="chips">
                            <?php foreach ($chips as $i => $c): ?>
                                <button type="button" class="chip" data-nominal="<?= (int)$c ?>">
                                    <?= $rp($c) ?><?= $c == (int)$totalAktif ? ' (Pas)' : '' ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                        <div class="kembalian" id="boxKembalian">
                            <span class="d-sub">Kembalian</span>
                            <span class="nilai" id="kembalian">Rp0</span>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary" id="tombolBayar">Verifikasi &amp; Selesaikan Pembayaran</button>
                </form>
            </div>
            <?php endif; ?>
        </section>
    </div>
</div>

<script>
(function () {
    // Pencarian antrean
    const cari = document.getElementById('cariAntrean');
    if (cari) cari.addEventListener('input', function () {
        const q = this.value.toLocaleLowerCase('id');
        document.querySelectorAll('#daftarAntrean .q-item').forEach(el => {
            el.hidden = !el.dataset.cari.includes(q);
        });
    });

    const form = document.getElementById('formBayar');
    if (!form) return;

    const total = <?= (int)$totalAktif ?>;
    const uang = document.getElementById('uang');
    const metode = document.getElementById('metode');
    const blokTunai = document.getElementById('blokTunai');
    const kembalianEl = document.getElementById('kembalian');
    const boxKembalian = document.getElementById('boxKembalian');

    const fmt = n => n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    const baca = () => parseInt(uang.value.replace(/\D/g, ''), 10) || 0;

    function hitung() {
        const diterima = baca();
        const selisih = diterima - total;
        boxKembalian.classList.toggle('kurang', selisih < 0);
        kembalianEl.textContent = selisih < 0 ? 'Kurang Rp' + fmt(-selisih) : 'Rp' + fmt(selisih);
        document.querySelectorAll('.chip').forEach(c => {
            c.classList.toggle('active', parseInt(c.dataset.nominal, 10) === diterima);
        });
    }

    uang.addEventListener('input', function () {
        const n = baca();
        this.value = n ? fmt(n) : '';
        hitung();
    });

    document.querySelectorAll('.chip').forEach(c => c.addEventListener('click', function () {
        uang.value = fmt(parseInt(this.dataset.nominal, 10));
        hitung();
    }));

    document.querySelectorAll('.tab').forEach(t => t.addEventListener('click', function () {
        document.querySelectorAll('.tab').forEach(x => x.classList.remove('active'));
        this.classList.add('active');
        metode.value = this.dataset.metode;
        blokTunai.style.display = metode.value === 'Tunai' ? 'flex' : 'none';
    }));

    form.addEventListener('submit', function (e) {
        if (metode.value === 'Tunai' && baca() < total) {
            e.preventDefault();
            alert('Uang diterima kurang dari total tagihan.');
            return;
        }
        document.getElementById('tombolBayar').disabled = true; // cegah klik ganda
    });

    hitung();
})();
</script>