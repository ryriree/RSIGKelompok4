<?php
// Invoice pelanggan. Data dari BayarController: $invoice ['pesanan', 'items']
if (!function_exists('eIv')) {
    function eIv($v) { return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8'); }
}
$rp = function ($n) { return 'Rp' . number_format((float)$n, 0, ',', '.'); };
$ps    = $invoice['pesanan'];
$items = $invoice['items'];
$tunai = ($ps['metode'] ?? '') === 'Tunai';
?>
<style>
.iv { padding: 28px 0; max-width: 560px; margin: 0 auto; }
.iv .ok { text-align: center; margin-bottom: 18px; }
.iv .ok h1 { font-size: clamp(26px, 5vw, 38px); color: var(--success); }
.iv .ok p { color: var(--text-muted); font-size: 14px; }
.iv .paper { background: #fff; border: 1.5px solid var(--border); border-top: 10px solid var(--primary); border-radius: 20px; box-shadow: var(--shadow-2); padding: 24px; font-size: 14px; }
.iv .c { text-align: center; }
.iv .nama { font-family: var(--font-display); color: var(--primary); font-size: 24px; text-transform: uppercase; }
.iv .muted { color: var(--text-muted); font-size: 12px; }
.iv hr { border: 0; border-top: 2px dashed var(--border); margin: 14px 0; }
.iv .row { display: flex; justify-content: space-between; gap: 10px; margin: 4px 0; }
.iv .row .k { color: var(--text-muted); }
.iv .lunas { display: inline-block; background: var(--green-100); color: var(--success); font-weight: 700; font-size: 12px; border-radius: 999px; padding: 2px 14px; }
.iv .it { display: flex; justify-content: space-between; gap: 10px; margin: 8px 0; }
.iv .it small { display: block; color: var(--text-muted); font-size: 11px; }
.iv .tot { display: flex; justify-content: space-between; align-items: center; background: var(--cream-50); border-radius: 14px; padding: 12px 14px; margin-top: 6px; }
.iv .tot b { font-family: var(--font-display); font-weight: 400; font-size: 26px; color: var(--primary); }
.iv .aksi { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 18px; }
.iv .aksi .btn { flex: 1; }
@media print {
    body * { visibility: hidden; }
    #invoice, #invoice * { visibility: visible; }
    #invoice { position: absolute; left: 0; top: 0; width: 100%; box-shadow: none; border-radius: 0; }
}
</style>

<div class="iv">
    <div class="ok">
        <h1>Pembayaran berhasil</h1>
        <p>Pesananmu sudah diteruskan ke dapur. Selamat menikmati!</p>
    </div>

    <div class="paper" id="invoice">
        <div class="c">
            <div class="nama">Maky Chicken</div>
            <div class="muted">Jl. Merdeka Kuliner No. 88 · info@makychicken.id</div>
            <div style="margin-top:8px"><span class="lunas">LUNAS</span></div>
        </div>
        <hr>
        <div class="row"><span class="k">No. Invoice</span><b><?= eIv($ps['kode_transaksi'] ?? $ps['kode_pesanan']) ?></b></div>
        <div class="row"><span class="k">Kode pesanan</span><span><?= eIv($ps['kode_pesanan']) ?></span></div>
        <div class="row"><span class="k">Meja / tipe</span><span>Meja <?= eIv($ps['no_meja']) ?> · <?= eIv($ps['jenis_pesanan']) ?></span></div>
        <div class="row"><span class="k">Waktu bayar</span><span><?= eIv(date('d/m/Y H:i', strtotime($ps['waktu_bayar'] ?? $ps['waktu_pesan']))) ?> WIB</span></div>
        <div class="row"><span class="k">Metode</span><span><?= eIv($ps['metode']) ?></span></div>
        <hr>
        <?php foreach ($items as $it): ?>
            <div class="it">
                <div>
                    <?= (int)$it['jumlah'] ?>× <?= eIv($it['nama_menu']) ?>
                    <small>@ <?= $rp($it['harga']) ?></small>
                    <?php if (!empty($it['catatan'])): ?><small>Catatan: <?= eIv($it['catatan']) ?></small><?php endif; ?>
                </div>
                <div><?= $rp($it['subtotal']) ?></div>
            </div>
        <?php endforeach; ?>
        <hr>
        <div class="tot"><span>Total</span><b><?= $rp($ps['total_harga']) ?></b></div>
        <div class="muted" style="margin-top:6px">Harga sudah termasuk pajak restoran (PB1).</div>
        <?php if ($tunai): ?>
            <div class="row" style="margin-top:10px"><span class="k">Tunai</span><span><?= $rp($ps['uang_diterima'] ?? $ps['total_harga']) ?></span></div>
            <div class="row"><span class="k">Kembalian</span><span><?= $rp($ps['kembalian'] ?? 0) ?></span></div>
        <?php endif; ?>
        <hr>
        <div class="c muted">Terima kasih atas kunjungan Anda di Maky Chicken!<br>Simpan invoice ini sebagai bukti pembayaran.</div>
    </div>

    <div class="aksi">
        <button type="button" class="btn btn--info" onclick="window.print()">Cetak / Simpan PDF</button>
        <a class="btn btn--outline" href="index.php?page=pesan&meja=<?= (int)$ps['no_meja'] ?>">Pesan Lagi</a>
        <a class="btn btn--ghost" href="index.php?page=home">Beranda</a>
    </div>
</div>