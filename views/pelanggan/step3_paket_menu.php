<?php
// Langkah 3. Data dari Reservasi::stepPaket: $draft, $paketList, $pesanRsv
$draft = $draft ?? []; $paketList = $paketList ?? [];
$rpR = function ($n) { return 'Rp' . number_format((float)$n, 0, ',', '.'); };
$qtyAwal = (int)($draft['qty'] ?? 1);
?>
<div class="rv">
<?php $langkah = 3; require BASE_PATH . '/views/pelanggan/_stepper.php'; ?>

<form method="POST" action="index.php?page=reservasi-paket-simpan" id="formPaket">
<div class="cols" style="grid-template-columns:8fr 4fr">
    <section class="card">
        <h2>3. Pilih paket menu</h2>
        <?php if (!empty($pesanRsv)): ?><div class="notice"><?= eRv($pesanRsv['teks']) ?></div><?php endif; ?>

        <?php if (!$paketList): ?>
            <p class="muted">Belum ada paket menu tersedia. Silakan hubungi admin.</p>
        <?php else: ?>
        <div class="picks">
            <?php foreach ($paketList as $p): ?>
                <label class="pick">
                    <input type="radio" name="paket_id" value="<?= (int)$p['id_paket'] ?>" data-harga="<?= (float)$p['harga'] ?>"
                           <?= (int)($draft['paketId'] ?? 0) === (int)$p['id_paket'] ? 'checked' : '' ?> required>
                    <span class="box">
                        <h3><?= eRv($p['nama_paket']) ?></h3>
                        <span class="muted" style="display:block;margin-bottom:10px"><?= eRv($p['deskripsi']) ?></span>
                        <span class="harga" style="font-size:20px"><?= $rpR($p['harga']) ?></span>
                    </span>
                </label>
            <?php endforeach; ?>
        </div>

        <div class="field" style="margin-top:20px;max-width:200px">
            <label for="qty">Jumlah paket</label>
            <input class="in" type="number" id="qty" name="qty" min="1" max="<?= Reservasi::MAKS_QTY ?>" value="<?= $qtyAwal ?>" required>
        </div>
        <?php endif; ?>
    </section>

    <aside class="card sum" style="position:sticky;top:16px">
        <h3>Ringkasan</h3>
        <p><span>Subtotal paket</span><span id="rSub">Rp0</span></p>
        <p><span>Service fee</span><span id="rFee"><?= $rpR(Reservasi::SERVICE_FEE) ?></span></p>
        <p class="big"><span>Total</span><span class="harga" id="rTot">Rp0</span></p>
        <p><span>DP yang dibayar</span><b id="rDp">Rp0</b></p>
        <p><span>Sisa di kasir</span><span id="rSisa">Rp0</span></p>
        <p class="muted" style="border:0">DP dipotong dari total saat pelunasan di kasir.</p>
        <button type="submit" class="btn" style="width:100%;margin-top:6px" id="lanjut" <?= $paketList ? '' : 'disabled' ?>>Lanjut bayar DP</button>
        <a class="btn btn--ghost" style="width:100%;margin-top:8px" href="index.php?page=reservasi-lantai">← Kembali</a>
    </aside>
</div>
</form>
</div>

<script>
(function () {
    const FEE = <?= Reservasi::SERVICE_FEE ?>, DPMIN = <?= Reservasi::DP_MINIMUM ?>, MAKS = <?= Reservasi::MAKS_QTY ?>;
    const qty = document.getElementById('qty');
    const rp = n => 'Rp' + Math.round(n).toLocaleString('id-ID');
    // Tampilan saja; angka resmi dihitung ulang di server
    function hitung() {
        const p = document.querySelector('input[name=paket_id]:checked');
        const q = Math.max(1, Math.min(MAKS, parseInt(qty && qty.value, 10) || 1));
        const sub = p ? parseFloat(p.dataset.harga) * q : 0;
        const tot = p ? sub + FEE : 0, dp = Math.min(DPMIN, tot);
        document.getElementById('rSub').textContent = rp(sub);
        document.getElementById('rTot').textContent = rp(tot);
        document.getElementById('rDp').textContent = rp(dp);
        document.getElementById('rSisa').textContent = rp(tot - dp);
    }
    document.querySelectorAll('input[name=paket_id]').forEach(r => r.addEventListener('change', hitung));
    if (qty) qty.addEventListener('input', hitung);
    hitung();
})();
</script>