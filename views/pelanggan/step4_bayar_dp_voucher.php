<?php
// Langkah 4. Mode bayar: $draft, $simulasi, $pesanRsv. Mode voucher: $voucher
$voucher = $voucher ?? null; $draft = $draft ?? [];
$rpR = function ($n) { return 'Rp' . number_format((float)$n, 0, ',', '.'); };
?>
<div class="rv">
<?php if ($voucher): ?>
<?php $langkah = 5; require BASE_PATH . '/views/pelanggan/_stepper.php'; ?>
<style>
.rv .vc { max-width: 520px; margin: 0 auto; }
.rv .vc-ok { text-align: center; margin-bottom: 18px; }
.rv .vc-ok h1 { font-size: clamp(24px, 5vw, 34px); color: var(--success); }
.rv .vc-paper { position: relative; background: #fff; border: 2px dashed var(--primary); border-radius: 24px; padding: 28px 30px; text-align: center; }
.rv .vc-kode { font-family: var(--font-display); font-size: clamp(28px, 6vw, 40px); color: var(--primary); margin: 8px 0; }
.rv .vc-tag { display: inline-block; background: var(--primary); color: var(--cream-50); font-size: 11px; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; border-radius: 999px; padding: 3px 14px; }
.rv .vc-st { display: inline-block; background: var(--green-100); color: var(--success); font-weight: 700; font-size: 12px; border-radius: 999px; padding: 3px 14px; margin-top: 8px; }
.rv .vc .sum { text-align: left; margin-top: 16px; }
.rv .vc-aksi { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 18px; }
.rv .vc-aksi .btn { flex: 1; }
@media print { body * { visibility: hidden; } #voucher, #voucher * { visibility: visible; } #voucher { position: absolute; left: 0; top: 0; width: 100%; } }
</style>
<div class="vc">
    <div class="vc-ok"><h1>Reservasi dikonfirmasi</h1><p class="muted">DP sudah diterima. Simpan atau cetak e-Voucher ini.</p></div>

    <div class="vc-paper" id="voucher">
        <span class="vc-tag">e-Voucher reservasi</span>
        <div class="vc-kode"><?= eRv($voucher['kode_booking']) ?></div>
        <div><b><?= eRv($voucher['nama_pemesan']) ?></b> · <?= (int)$voucher['jumlah_orang'] ?> orang</div>
        <div><span class="vc-st"><?= eRv($voucher['status']) ?> · DP <?= eRv($voucher['status_dp']) ?></span></div>
        <div class="sum">
            <p><span>Tanggal</span><span><?= eRv(date('d/m/Y', strtotime($voucher['tanggal']))) ?></span></p>
            <p><span>Waktu</span><span><?= eRv($voucher['slot_waktu']) ?></span></p>
            <p><span>Lokasi</span><span>Lantai <?= (int)$voucher['lantai'] ?> · <?= eRv(Reservasi::LANTAI[(int)$voucher['lantai']] ?? '') ?></span></p>
            <p><span>Paket</span><span><?= eRv($voucher['nama_paket'] ?? '-') ?> ×<?= (int)($voucher['qty'] ?? 0) ?></span></p>
            <p><span>Total tagihan</span><span><?= $rpR($voucher['total']) ?></span></p>
            <p><span>DP dibayar</span><b><?= $rpR($voucher['dp']) ?></b></p>
            <p style="border:0"><span>Sisa di kasir</span><span><?= $rpR(max(0, (float)$voucher['total'] - (float)$voucher['dp'])) ?></span></p>
        </div>
        <p class="muted" style="margin-top:8px">Tunjukkan voucher ini kepada kasir/pelayan saat tiba.</p>
    </div>

    <div class="vc-aksi">
        <button type="button" class="btn btn--info" onclick="window.print()">Cetak / Simpan PDF</button>
        <a class="btn btn--accent" href="index.php?page=reservasi-unduh">Unduh .txt</a>
        <a class="btn btn--outline" href="index.php?page=form-reservasi">Reservasi baru</a>
    </div>
</div>

<?php else: ?>
<?php $langkah = 4; require BASE_PATH . '/views/pelanggan/_stepper.php'; ?>
<style>
.rv .qr { width: 200px; height: 200px; margin: 8px auto 12px; border-radius: 16px; border: 1.5px solid var(--border); background: repeating-conic-gradient(var(--ink-900) 0 25%, #fff 0 50%) 0 0/24px 24px; }
.rv .nominal { font-family: var(--font-display); font-size: 36px; color: var(--primary); }
.rv .demo { background: var(--yellow-100); border-radius: 14px; padding: 12px 16px; font-size: 12px; margin-top: 14px; text-align: left; }
</style>
<div class="cols">
    <aside class="card sum">
        <h3>Ringkasan reservasi</h3>
        <p><span>Nama</span><span><?= eRv($draft['namaPemesan']) ?></span></p>
        <p><span>Jadwal</span><span><?= eRv(date('d/m/Y', strtotime($draft['tanggal']))) ?> · <?= eRv($draft['slotWaktu']) ?></span></p>
        <p><span>Tamu</span><span><?= (int)$draft['jumlahOrang'] ?> orang</span></p>
        <p><span>Lokasi</span><span>Lantai <?= (int)$draft['lantai'] ?></span></p>
        <p><span><?= eRv($draft['paketNama']) ?> ×<?= (int)$draft['qty'] ?></span><span><?= $rpR($draft['subtotal']) ?></span></p>
        <p><span>Service fee</span><span><?= $rpR($draft['serviceFee']) ?></span></p>
        <p class="big"><span>Total</span><span class="harga"><?= $rpR($draft['total']) ?></span></p>
        <p style="border:0"><span>Sisa di kasir</span><span><?= $rpR($draft['total'] - $draft['dp']) ?></span></p>
        <a class="btn btn--ghost" style="width:100%" href="index.php?page=reservasi-paket">← Ubah paket</a>
    </aside>

    <section class="card" style="text-align:center">
        <h2>4. Bayar DP</h2>
        <?php if (!empty($pesanRsv)): ?><div class="notice <?= $pesanRsv['tipe'] === 'ok' ? 'ok' : '' ?>" style="text-align:left"><?= eRv($pesanRsv['teks']) ?></div><?php endif; ?>

        <div class="qr" role="img" aria-label="Kode QRIS DP"></div>
        <div class="nominal"><?= $rpR($draft['nominalDP']) ?></div>
        <p class="muted">Referensi: <?= eRv($draft['idTransaksi']) ?></p>
        <p class="muted" style="margin-top:8px">Pindai dengan aplikasi pembayaran, lalu e-Voucher terbit setelah pembayaran terverifikasi.</p>

        <?php if (!empty($simulasi)): ?>
            <form method="POST" action="index.php?page=reservasi-bayar-konfirmasi" style="margin-top:16px">
                <input type="hidden" name="id_transaksi" value="<?= eRv($draft['idTransaksi']) ?>">
                <button type="submit" class="btn" style="width:100%" id="tombolBayar">Simulasi: saya sudah bayar</button>
            </form>
            <div class="demo"><b>Mode simulasi.</b> Belum terhubung ke payment gateway, jadi tombol ini hanya untuk demo.</div>
        <?php endif; ?>

        <div class="actions" style="justify-content:center">
            <a class="btn btn--outline btn--sm" href="index.php?page=reservasi-bayar&ulang=1">Buat QR baru</a>
            <form method="POST" action="index.php?page=reservasi-draft-batal" style="margin:0" onsubmit="return confirm('Batalkan reservasi ini?')">
                <button type="submit" class="btn btn--ghost btn--sm">Batalkan reservasi</button>
            </form>
        </div>
    </section>
</div>
<script>
document.querySelector('#tombolBayar')?.closest('form').addEventListener('submit', function () {
    document.getElementById('tombolBayar').disabled = true; // cegah klik ganda
});
</script>
<?php endif; ?>
</div>