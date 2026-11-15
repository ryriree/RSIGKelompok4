<?php
// Langkah 2. Data dari Reservasi::stepLantai: $draft, $pesanRsv
$draft = $draft ?? [];
?>
<div class="rv">
<?php $langkah = 2; require BASE_PATH . '/views/pelanggan/_stepper.php'; ?>

<div class="cols">
    <aside class="card sum">
        <h3>Detail pemesanan</h3>
        <p><span>Nama</span><span><?= eRv($draft['namaPemesan']) ?></span></p>
        <p><span>Tanggal</span><span><?= eRv(date('d/m/Y', strtotime($draft['tanggal']))) ?></span></p>
        <p><span>Waktu</span><span><?= eRv($draft['slotWaktu']) ?></span></p>
        <p><span>Tamu</span><span><?= (int)$draft['jumlahOrang'] ?> orang</span></p>
    </aside>

    <section class="card">
        <h2>2. Pilih lantai</h2>
        <?php if (!empty($pesanRsv)): ?><div class="notice"><?= eRv($pesanRsv['teks']) ?></div><?php endif; ?>

        <form method="POST" action="index.php?page=reservasi-lantai-simpan">
            <div class="picks">
                <?php foreach (Reservasi::LANTAI as $no => $label): ?>
                    <label class="pick">
                        <input type="radio" name="lantai" value="<?= $no ?>" <?= (int)($draft['lantai'] ?? 0) === $no ? 'checked' : '' ?> required>
                        <span class="box">
                            <h3>Lantai <?= $no ?></h3>
                            <span class="muted"><?= eRv($label) ?></span>
                        </span>
                    </label>
                <?php endforeach; ?>
            </div>
            <p class="muted" style="margin-top:12px">Penempatan meja diatur oleh pelayan saat kamu tiba.</p>

            <div class="actions">
                <a class="btn btn--ghost" href="index.php?page=form-reservasi">← Kembali</a>
                <button type="submit" class="btn">Lanjut pilih paket menu</button>
            </div>
        </form>
    </section>
</div>
</div>