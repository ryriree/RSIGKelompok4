<?php
// Pesan dari meja. Data dari PesananController: $menus, $noMeja, $pesanPesan
if (!function_exists('ePs')) {
    function ePs($v) { return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8'); }
}
$menus      = $menus ?? [];
$noMeja     = (int)($noMeja ?? 0);
$pesanPesan = $pesanPesan ?? null;
$kategoriList = array_values(array_unique(array_column($menus, 'kategori')));
?>
<style>
.po { padding: 28px 0 130px; }
.po .head { text-align: center; margin-bottom: 18px; }
.po .head h1 { font-size: clamp(28px, 5vw, 44px); color: var(--primary); }
.po .head p { color: var(--text-muted); font-size: 14px; }
.po .notice { padding: 12px 16px; margin-bottom: 16px; border-radius: 14px; font-size: 13px; font-weight: 600; background: var(--red-100); color: var(--red-700); }
.po .meja-box { display: flex; align-items: center; justify-content: center; gap: 10px; margin: 14px 0 6px; flex-wrap: wrap; font-weight: 600; font-size: 14px; }
.po .meja-box select { min-height: 44px; border: 1.5px solid var(--border); border-radius: 999px; padding: 0 16px; background: #fff; font-weight: 700; }
.po .tools { display: flex; gap: 10px; flex-wrap: wrap; margin: 20px 0; }
.po .tools input { flex: 1 1 220px; min-height: 44px; border: 1.5px solid var(--border); border-radius: 999px; padding: 0 18px; background: #fff; outline: none; }
.po .chips { display: flex; gap: 8px; flex-wrap: wrap; }
.po .chip { min-height: 44px; padding: 0 18px; border-radius: 999px; border: 1.5px solid var(--border); background: #fff; font-weight: 700; font-size: 13px; cursor: pointer; }
.po .chip.on { background: var(--primary); border-color: var(--primary); color: var(--cream-50); }
.po .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 20px; }
.po .card { background: #fff; border: 1.5px solid var(--border); border-radius: 22px; box-shadow: var(--shadow-1); overflow: hidden; display: flex; flex-direction: column; }
.po .card.dipilih { border-color: var(--primary); box-shadow: 0 0 0 2px var(--primary); }
.po .card.habis { opacity: .65; }
.po .ph { aspect-ratio: 16/9; background: var(--cream-200); display: grid; place-items: center; font-size: 44px; position: relative; }
.po .tag-habis { position: absolute; top: 10px; left: 10px; background: var(--ink-900); color: #fff; font-size: 11px; font-weight: 700; border-radius: 999px; padding: 2px 12px; text-transform: uppercase; }
.po .bd { padding: 14px; display: flex; flex-direction: column; gap: 6px; flex: 1; }
.po .bd h3 { font-family: var(--font-body); text-transform: none; font-size: 16px; font-weight: 600; line-height: 1.3; }
.po .bd p { font-size: 12px; color: var(--text-muted); }
.po .cat { width: 100%; min-height: 38px; border: 1.5px solid var(--border); border-radius: 12px; padding: 0 12px; font-size: 12px; outline: none; background: var(--cream-50); }
.po .bt { display: flex; justify-content: space-between; align-items: center; margin-top: auto; padding-top: 10px; border-top: 1.5px solid var(--cream-200); }
.po .harga { font-family: var(--font-display); color: var(--primary); font-size: 18px; }
.po .qty { display: inline-flex; align-items: center; border: 1.5px solid var(--border); border-radius: 999px; background: var(--cream-50); }
.po .qty button { width: 40px; height: 40px; border: 0; background: none; font-size: 20px; font-weight: 700; cursor: pointer; border-radius: 999px; }
.po .qty button:hover { background: var(--cream-200); }
.po .qty input { width: 32px; text-align: center; border: 0; background: none; font-weight: 700; padding: 0; }
.po .bar { position: fixed; left: 50%; bottom: 14px; transform: translateX(-50%); width: min(720px, calc(100% - 24px)); background: var(--primary); color: var(--cream-50); border-radius: 999px; padding: 10px 10px 10px 24px; display: flex; justify-content: space-between; align-items: center; gap: 12px; box-shadow: var(--shadow-3); z-index: 40; }
.po .bar b { font-family: var(--font-display); font-weight: 400; font-size: 20px; }
.po .bar small { display: block; font-size: 11px; opacity: .85; }
.po .empty { grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--text-muted); }
</style>

<div class="po">
    <div class="head">
        <h1>Pesan dari meja</h1>
        <p>Pilih menu, atur jumlah, lalu lanjut ke pembayaran.</p>
    </div>

    <?php if (!empty($pesanPesan)): ?><div class="notice"><?= ePs($pesanPesan['teks']) ?></div><?php endif; ?>

    <form method="POST" action="index.php?page=pesan-kirim" id="formPesan">
        <div class="meja-box">
            <label for="no_meja">Nomor meja:</label>
            <select name="no_meja" id="no_meja" required>
                <?php if (!$noMeja): ?><option value="">Pilih meja</option><?php endif; ?>
                <?php for ($i = 1; $i <= PesananController::MAKS_MEJA; $i++): ?>
                    <option value="<?= $i ?>" <?= $i === $noMeja ? 'selected' : '' ?>>Meja <?= $i ?></option>
                <?php endfor; ?>
            </select>
        </div>

        <div class="tools">
            <input type="search" id="cariMenu" placeholder="Cari menu...">
            <div class="chips" id="filterKat">
                <button type="button" class="chip on" data-kat="all">Semua</button>
                <?php foreach ($kategoriList as $k): ?>
                    <button type="button" class="chip" data-kat="<?= ePs($k) ?>"><?= ePs($k) ?></button>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="grid">
            <?php if (!$menus): ?>
                <div class="empty">Menu belum tersedia.</div>
            <?php else: foreach ($menus as $m):
                $id = (int)$m['id_menu'];
                $ada = (int)$m['status_tersedia'] === 1;
            ?>
                <article class="card <?= $ada ? '' : 'habis' ?>" <?= $ada ? 'data-id="' . $id . '"' : '' ?>
                         data-harga="<?= (float)$m['harga'] ?>"
                         data-nama="<?= ePs(mb_strtolower((string)$m['nama_menu'], 'UTF-8')) ?>"
                         data-kat="<?= ePs($m['kategori']) ?>">
                    <div class="ph">
                        <?= $m['kategori'] === 'Minuman' ? '🥤' : '🍗' ?>
                        <?php if (!$ada): ?><span class="tag-habis">Stok habis</span><?php endif; ?>
                    </div>
                    <div class="bd">
                        <h3><?= ePs($m['nama_menu']) ?></h3>
                        <p><?= ePs($m['deskripsi']) ?></p>
                        <?php if ($ada): ?>
                            <input class="cat" type="text" maxlength="200" name="items[<?= $id ?>][catatan]" placeholder="Catatan (mis. pedas level 3 / tanpa es)">
                        <?php endif; ?>
                        <div class="bt">
                            <span class="harga">Rp<?= number_format((float)$m['harga'], 0, ',', '.') ?></span>
                            <?php if ($ada): ?>
                                <div class="qty">
                                    <button type="button" data-d="-1" aria-label="Kurangi">−</button>
                                    <input type="number" class="qty-val" name="items[<?= $id ?>][jumlah]" value="0" min="0" max="50" readonly>
                                    <button type="button" data-d="1" aria-label="Tambah">+</button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; endif; ?>
        </div>

        <div class="bar">
            <div><small><span id="jmlItem">0</span> item dipesan</small><b id="totalHarga">Rp0</b></div>
            <div style="display:flex;gap:8px">
                <button type="button" class="btn btn--ghost btn--sm" id="resetPilihan" style="color:#fff">Reset</button>
                <button type="submit" class="btn btn--accent" id="tombolPesan" disabled>Pesan &amp; Bayar</button>
            </div>
        </div>
    </form>
</div>

<script>
(function () {
    const form = document.getElementById('formPesan');
    const fmt = n => 'Rp' + n.toLocaleString('id-ID');
    let kat = 'all';

    function hitung() {
        let q = 0, t = 0;
        form.querySelectorAll('.card[data-id]').forEach(c => {
            const n = parseInt(c.querySelector('.qty-val').value, 10) || 0;
            q += n; t += n * parseFloat(c.dataset.harga);
            c.classList.toggle('dipilih', n > 0);
        });
        document.getElementById('jmlItem').textContent = q;
        document.getElementById('totalHarga').textContent = fmt(t); // tampilan saja; total asli dihitung server
        document.getElementById('tombolPesan').disabled = q === 0;
    }

    form.addEventListener('click', function (e) {
        const b = e.target.closest('[data-d]');
        if (!b) return;
        const i = b.closest('.card').querySelector('.qty-val');
        i.value = Math.max(0, Math.min(50, (parseInt(i.value, 10) || 0) + parseInt(b.dataset.d, 10)));
        hitung();
    });

    function saring() {
        const q = document.getElementById('cariMenu').value.toLocaleLowerCase('id');
        form.querySelectorAll('.card').forEach(c => {
            c.hidden = !(c.dataset.nama.includes(q) && (kat === 'all' || c.dataset.kat === kat));
        });
    }
    document.getElementById('cariMenu').addEventListener('input', saring);
    document.querySelectorAll('#filterKat .chip').forEach(b => b.addEventListener('click', function () {
        document.querySelectorAll('#filterKat .chip').forEach(x => x.classList.remove('on'));
        this.classList.add('on'); kat = this.dataset.kat; saring();
    }));

    document.getElementById('resetPilihan').addEventListener('click', () => {
        form.querySelectorAll('.qty-val').forEach(i => i.value = 0);
        hitung();
    });
    form.addEventListener('submit', () => { document.getElementById('tombolPesan').disabled = true; });
    hitung();
})();
</script>