<?php
// Langkah 1. Data dari Reservasi::get_form_reservasi: $draft, $old, $pesanRsv
$draft = $draft ?? []; $old = $old ?? [];
$tgl    = $old['tanggal'] ?? $draft['tanggal'] ?? date('Y-m-d');
$slotPl = $old['slot'] ?? $draft['slotWaktu'] ?? '';
$org    = (int)($old['jumlah'] ?? $draft['jumlahOrang'] ?? 4);
$nama   = $draft['namaPemesan'] ?? '';
$kontak = $draft['kontakPemesanan'] ?? '';
?>
<div class="rv">
<?php $langkah = 1; require BASE_PATH . '/views/pelanggan/_stepper.php'; ?>

<div class="cols">
    <aside class="card">
        <h3>Reservasi meja</h3>
        <p class="muted">Isi jadwal kunjungan dan jumlah tamu untuk mengunci meja. Tahap berikutnya: pilih lantai, pilih paket menu, lalu bayar DP.</p>
        <p class="muted" style="margin-top:12px">DP maksimal <b>Rp<?= number_format(Reservasi::DP_MINIMUM, 0, ',', '.') ?></b> dibayar via QRIS. e-Voucher terbit setelah pembayaran terverifikasi.</p>
    </aside>

    <section class="card">
        <h2>1. Jadwal &amp; jumlah tamu</h2>
        <?php if (!empty($pesanRsv)): ?><div class="notice <?= $pesanRsv['tipe'] === 'ok' ? 'ok' : '' ?>"><?= eRv($pesanRsv['teks']) ?></div><?php endif; ?>

        <form method="POST" action="index.php?page=reservasi-jadwal" id="formJadwal">
            <input type="hidden" name="slot_waktu" id="slotVal" value="<?= eRv($slotPl) ?>">
            <input type="hidden" name="jumlah_orang" id="orgVal" value="<?= $org ?>">

            <div class="field">
                <label for="tanggal">Tanggal kunjungan</label>
                <input class="in" type="date" id="tanggal" name="tanggal" value="<?= eRv($tgl) ?>" min="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="field">
                <label>Waktu kedatangan</label>
                <div class="chips" id="slots">
                    <?php foreach (Reservasi::SLOT as $s): ?>
                        <button type="button" class="slot <?= $s === $slotPl ? 'on' : '' ?>" data-v="<?= eRv($s) ?>"><?= eRv($s) ?></button>
                    <?php endforeach; ?>
                </div>
                <span class="muted">Slot yang dicoret sudah penuh.</span>
            </div>

            <div class="field">
                <label>Jumlah tamu</label>
                <div class="chips" style="align-items:center">
                    <button type="button" class="slot" id="kurang" aria-label="Kurangi">−</button>
                    <b id="orgTeks" style="min-width:80px;text-align:center"><?= $org ?> tamu</b>
                    <button type="button" class="slot" id="tambah" aria-label="Tambah">+</button>
                </div>
            </div>

            <div class="row2">
                <div class="field"><label for="nama">Nama pemesan</label><input class="in" id="nama" name="nama_pemesan" maxlength="100" value="<?= eRv($nama) ?>" required></div>
                <div class="field"><label for="kontak">Nomor WhatsApp</label><input class="in" id="kontak" name="kontak_pemesanan" type="tel" maxlength="20" value="<?= eRv($kontak) ?>" required></div>
            </div>

            <div class="actions">
                <a class="btn btn--ghost" href="index.php?page=home">Batal</a>
                <button type="submit" class="btn" id="lanjut">Lanjut pilih lantai</button>
            </div>
        </form>
    </section>
</div>
</div>

<script>
(function () {
    const MAKS = <?= Reservasi::MAKS_TAMU ?>, KAP = <?= Reservasi::KAPASITAS_MAKSIMAL ?>;
    const tgl = document.getElementById('tanggal'), slotVal = document.getElementById('slotVal'),
          orgVal = document.getElementById('orgVal'), lanjut = document.getElementById('lanjut');
    const slots = document.querySelectorAll('#slots .slot');
    let sisa = {}, tamu = parseInt(orgVal.value, 10) || 4;

    function pilih(v) {
        slotVal.value = v;
        slots.forEach(b => b.classList.toggle('on', b.dataset.v === v));
    }
    function terapkan() {
        slots.forEach(b => {
            const s = (b.dataset.v in sisa) ? sisa[b.dataset.v] : KAP;
            const penuh = s < tamu;
            b.disabled = penuh;
            b.title = penuh ? 'Jadwal penuh untuk ' + tamu + ' tamu' : '';
            if (penuh && b.dataset.v === slotVal.value) pilih('');
        });
        lanjut.disabled = !slotVal.value;
    }
    async function muat() {
        try {
            const r = await fetch('index.php?page=reservasi-slot&tanggal=' + encodeURIComponent(tgl.value), { cache: 'no-store' });
            sisa = (await r.json()).sisa || {};
        } catch (e) { sisa = {}; }
        terapkan();
    }
    function setTamu(n) {
        tamu = Math.max(1, Math.min(MAKS, n));
        orgVal.value = tamu;
        document.getElementById('orgTeks').textContent = tamu + ' tamu';
        terapkan();
    }

    slots.forEach(b => b.addEventListener('click', () => { pilih(b.dataset.v); terapkan(); }));
    document.getElementById('kurang').addEventListener('click', () => setTamu(tamu - 1));
    document.getElementById('tambah').addEventListener('click', () => setTamu(tamu + 1));
    tgl.addEventListener('change', muat);
    muat();
})();
</script>