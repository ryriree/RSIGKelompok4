<?php
// UC-09 - Konten Kelola Ketersediaan Menu
// Data dari MenuController: $daftarMenu, $pesanMenu
if (!function_exists('eMenu')) {
    function eMenu($value) {
        return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
    }
}
$daftarMenu = $daftarMenu ?? [];
$totalMenu = count($daftarMenu);
$totalTersedia = 0;
$kategoriAktif = [];
$semuaKategori = [];
foreach ($daftarMenu as $item) {
    $kategori = trim((string)($item['kategori'] ?? ''));
    if ($kategori !== '') {
        $semuaKategori[$kategori] = true;
        if ((int)($item['status_tersedia'] ?? 0) === 1) $kategoriAktif[$kategori] = true;
    }
    if ((int)($item['status_tersedia'] ?? 0) === 1) $totalTersedia++;
}
$totalHabis = $totalMenu - $totalTersedia;
$persentase = $totalMenu ? round($totalTersedia / $totalMenu * 100, 1) : 0;
?>
<style>
.mn { font-size: 13px; }
.mn .desc { font-size: 12px; color: var(--text-muted); margin-bottom: 16px; }
.mn .notice { padding: 12px 16px; margin-bottom: 18px; border-radius: 12px; background: var(--green-100); color: var(--success); font-size: 12px; font-weight: 600; }

.mn .kpi-grid { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 16px; margin-bottom: 24px; }
.mn .kpi { background: #fff; border: 1.5px solid var(--cream-300); border-radius: 20px; padding: 18px 20px; box-shadow: var(--shadow-1); }
.mn .kpi.accent { background: var(--yellow-500); border-color: var(--yellow-500); }
.mn .kpi-label { display: flex; justify-content: space-between; align-items: center; font-size: 10px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--text-muted); }
.mn .kpi.accent .kpi-label, .mn .kpi.accent .kpi-note { color: var(--ink-900); }
.mn .kpi-value { font-family: var(--font-display); font-size: 40px; line-height: 1; margin: 12px 0 5px; color: var(--red-600); }
.mn .kpi-value.green { color: var(--success); }
.mn .kpi-value.dark { color: var(--ink-900); }
.mn .kpi-note { font-size: 10px; color: var(--text-muted); }

.mn .toolbar { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; padding: 16px 18px; background: #fff; border: 1.5px solid var(--cream-300); border-radius: 20px; box-shadow: var(--shadow-1); margin-bottom: 24px; }
.mn .search-box { flex: 1 1 220px; }
.mn .search-box input { width: 100%; height: 42px; border: 1.5px solid var(--cream-300); border-radius: 999px; padding: 0 16px; background: var(--cream-50); outline: none; font-size: 12px; }
.mn .filters { display: flex; gap: 6px; flex-wrap: wrap; }
.mn .filter-btn { border: 0; border-radius: 999px; background: var(--cream-50); padding: 9px 14px; font-size: 10px; font-weight: 700; cursor: pointer; }
.mn .filter-btn.active { background: var(--red-600); color: #fff; }
.mn .btn-primary { border: 0; border-radius: 999px; background: var(--red-600); color: #fff; padding: 11px 20px; font-size: 10px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; cursor: pointer; }
.mn .btn-primary:hover { background: var(--red-700); }
.mn .add-btn { margin-left: auto; white-space: nowrap; }
.mn .btn-light { border: 1.5px solid var(--cream-300); background: #fff; border-radius: 999px; padding: 10px 18px; font-size: 10px; font-weight: 700; cursor: pointer; }

.mn .editor { display: none; background: #fff; border: 1.5px solid var(--cream-300); border-radius: 20px; padding: 20px; margin: 0 0 24px; box-shadow: var(--shadow-1); }
.mn .editor.open { display: block; }
.mn .editor-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
.mn .editor-head h2 { font-size: 19px; color: var(--red-600); margin: 0; }
.mn .editor-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 13px; }
.mn .field { display: flex; flex-direction: column; gap: 6px; }
.mn .field label { font-size: 11px; font-weight: 700; }
.mn .field input, .mn .field textarea { width: 100%; border: 1.5px solid var(--cream-300); border-radius: 12px; padding: 10px 12px; font-size: 12px; outline: none; }
.mn .field textarea { min-height: 75px; resize: vertical; }
.mn .available-check { display: flex; align-items: center; gap: 8px; margin: 14px 0; font-size: 12px; }
.mn .form-actions { display: flex; justify-content: flex-end; gap: 9px; }

.mn .table-card { background: #fff; border: 1.5px solid var(--cream-300); border-radius: 20px; overflow: hidden; box-shadow: var(--shadow-1); }
.mn .table-head { padding: 18px 20px; font-family: var(--font-display); font-size: 17px; color: var(--red-600); text-transform: uppercase; }
.mn .table-scroll { overflow-x: auto; }
.mn .menu-table { width: 100%; min-width: 800px; border-collapse: collapse; font-size: 12px; }
.mn .menu-table th { background: var(--red-600); color: #fff; padding: 14px 16px; text-align: left; font-size: 10px; letter-spacing: .06em; text-transform: uppercase; }
.mn .menu-table td { padding: 13px 16px; border-bottom: 1px solid var(--cream-200); vertical-align: middle; }
.mn .menu-table tbody tr:nth-child(even) { background: var(--cream-50); }
.mn .menu-name { font-weight: 700; font-size: 13px; }
.mn .menu-desc { display: block; color: var(--text-muted); font-size: 10px; max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.mn .category { display: inline-block; background: var(--blue-100); color: var(--info); border-radius: 999px; padding: 5px 11px; font-size: 10px; font-weight: 700; white-space: nowrap; }
.mn .price { font-weight: 700; white-space: nowrap; }
.mn .status { display: inline-block; border-radius: 999px; padding: 5px 11px; font-size: 10px; font-weight: 700; white-space: nowrap; }
.mn .status.yes { background: var(--green-100); color: var(--success); }
.mn .status.no { background: var(--red-100); color: var(--red-700); }
.mn .actions { display: flex; gap: 7px; align-items: center; }
.mn .action-btn { border: 0; border-radius: 999px; padding: 8px 12px; font-size: 10px; font-weight: 700; white-space: nowrap; cursor: pointer; }
.mn .edit-btn { background: var(--blue-100); color: var(--info); }
.mn .toggle-btn { background: var(--cream-200); color: var(--red-700); }
.mn .table-foot { padding: 15px 18px; color: var(--text-muted); font-size: 11px; }

@media (max-width: 1100px) { .mn .kpi-grid { grid-template-columns: repeat(2, minmax(0,1fr)); } }
@media (max-width: 760px) { .mn .editor-grid { grid-template-columns: 1fr; } .mn .add-btn { margin-left: 0; width: 100%; } }
</style>

<div class="mn">
    <p class="desc">Manajemen katalog produk, status stok, dan pembaruan menu restoran.</p>

    <?php if (!empty($pesanMenu)): ?><div class="notice"><?= eMenu($pesanMenu) ?></div><?php endif; ?>

    <section class="kpi-grid">
        <article class="kpi"><div class="kpi-label">Total Menu Terdaftar</div><div class="kpi-value"><?= $totalMenu ?></div><div class="kpi-note">Seluruh katalog menu restoran</div></article>
        <article class="kpi"><div class="kpi-label">Menu Tersedia</div><div class="kpi-value green"><?= $totalTersedia ?></div><div class="kpi-note"><?= $persentase ?>% menu siap dipesan</div></article>
        <article class="kpi"><div class="kpi-label">Menu Habis (Sold Out)</div><div class="kpi-value"><?= $totalHabis ?></div><div class="kpi-note">Perlu pembaruan ketersediaan menu</div></article>
        <article class="kpi accent"><div class="kpi-label">Kategori Aktif</div><div class="kpi-value dark"><?= count($kategoriAktif) ?></div><div class="kpi-note"><?= eMenu(implode(', ', array_keys($kategoriAktif))) ?></div></article>
    </section>

    <section class="toolbar">
        <div class="search-box"><input type="search" id="cariMenu" placeholder="Cari nama menu..."></div>
        <div class="filters" id="filterKategori">
            <button type="button" class="filter-btn active" data-kategori="all">Semua (<?= $totalMenu ?>)</button>
            <?php foreach (array_keys($semuaKategori) as $kategori): ?>
                <button type="button" class="filter-btn" data-kategori="<?= eMenu($kategori) ?>"><?= eMenu($kategori) ?></button>
            <?php endforeach; ?>
        </div>
        <button type="button" class="btn-primary add-btn" id="tombolTambah">＋ Tambah Menu Baru</button>
    </section>

    <section class="editor" id="formEditor">
        <div class="editor-head"><h2 class="disp" id="judulEditor">Tambah Menu Baru</h2><button type="button" class="btn-light" id="tutupEditor">Tutup ✕</button></div>
        <form method="POST" action="index.php?page=simpan-menu" id="formMenu">
            <input type="hidden" name="id_menu" id="id_menu">
            <div class="editor-grid">
                <div class="field"><label for="nama_menu">Nama Menu *</label><input type="text" name="nama_menu" id="nama_menu" maxlength="150" required></div>
                <div class="field"><label for="kategori">Kategori *</label><input type="text" name="kategori" id="kategori" maxlength="100" list="kategoriList" required>
                    <datalist id="kategoriList"><?php foreach (array_keys($semuaKategori) as $kat): ?><option value="<?= eMenu($kat) ?>"><?php endforeach; ?></datalist></div>
                <div class="field"><label for="harga">Harga Satuan (Rp) *</label><input type="number" name="harga" id="harga" min="0" required></div>
                <div class="field"><label for="deskripsi">Deskripsi Menu</label><textarea name="deskripsi" id="deskripsi" placeholder="Deskripsi singkat menu..."></textarea></div>
            </div>
            <label class="available-check"><input type="checkbox" name="status_tersedia" id="status_tersedia" value="1" checked> Menu tersedia dan dapat dipesan pelanggan</label>
            <div class="form-actions"><button type="button" class="btn-light" id="batalEditor">Batal</button><button type="submit" class="btn-primary">Simpan Menu</button></div>
        </form>
    </section>

    <section class="table-card">
        <div class="table-head">Daftar Menu Restoran</div>
        <div class="table-scroll"><table class="menu-table">
            <thead><tr><th>Nama Menu</th><th>Kategori</th><th>Harga Satuan</th><th>Status Ketersediaan</th><th>Aksi Pengelolaan</th></tr></thead>
            <tbody id="daftarMenuBody">
            <?php if (!$daftarMenu): ?>
                <tr><td colspan="5" style="text-align:center;padding:30px">Belum ada data menu.</td></tr>
            <?php else: foreach ($daftarMenu as $m): $tersedia = (int)($m['status_tersedia'] ?? 0) === 1; ?>
                <tr class="baris-menu" data-nama="<?= eMenu(mb_strtolower((string)($m['nama_menu'] ?? ''), 'UTF-8')) ?>" data-kategori="<?= eMenu($m['kategori'] ?? '') ?>">
                    <td><div class="menu-name"><?= eMenu($m['nama_menu'] ?? '') ?></div><span class="menu-desc"><?= eMenu($m['deskripsi'] ?? 'Menu Maky Chicken') ?></span></td>
                    <td><span class="category"><?= eMenu($m['kategori'] ?? '-') ?></span></td>
                    <td class="price">Rp<?= number_format((float)($m['harga'] ?? 0), 0, ',', '.') ?></td>
                    <td><span class="status <?= $tersedia ? 'yes' : 'no' ?>"><?= $tersedia ? '● TERSEDIA' : '● HABIS' ?></span></td>
                    <td><div class="actions">
                        <button type="button" class="action-btn edit-btn tombol-edit"
                            data-id="<?= (int)$m['id_menu'] ?>" data-nama="<?= eMenu($m['nama_menu'] ?? '') ?>"
                            data-kategori="<?= eMenu($m['kategori'] ?? '') ?>" data-harga="<?= eMenu($m['harga'] ?? 0) ?>"
                            data-deskripsi="<?= eMenu($m['deskripsi'] ?? '') ?>" data-status="<?= $tersedia ? '1' : '0' ?>">✎ UBAH</button>
                        <form method="POST" action="index.php?page=ubah-ketersediaan" style="margin:0">
                            <input type="hidden" name="id_menu" value="<?= (int)$m['id_menu'] ?>">
                            <input type="hidden" name="status_tersedia" value="<?= $tersedia ? '0' : '1' ?>">
                            <button type="submit" class="action-btn toggle-btn"><?= $tersedia ? '◉ NONAKTIFKAN' : '✓ AKTIFKAN' ?></button>
                        </form>
                    </div></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table></div>
        <div class="table-foot">Menampilkan <?= $totalMenu ?> menu dalam katalog restoran.</div>
    </section>
</div>

<script>
(function () {
    const editor = document.getElementById('formEditor'), form = document.getElementById('formMenu');
    function openEditor() { editor.classList.add('open'); editor.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
    function resetEditor() {
        form.reset();
        document.getElementById('id_menu').value = '';
        document.getElementById('judulEditor').textContent = 'Tambah Menu Baru';
        document.getElementById('status_tersedia').checked = true;
    }
    document.getElementById('tombolTambah').addEventListener('click', () => { resetEditor(); openEditor(); });
    document.getElementById('tutupEditor').addEventListener('click', () => editor.classList.remove('open'));
    document.getElementById('batalEditor').addEventListener('click', () => { resetEditor(); editor.classList.remove('open'); });
    document.querySelectorAll('.tombol-edit').forEach(btn => btn.addEventListener('click', function () {
        document.getElementById('id_menu').value = this.dataset.id;
        document.getElementById('nama_menu').value = this.dataset.nama;
        document.getElementById('kategori').value = this.dataset.kategori;
        document.getElementById('harga').value = this.dataset.harga;
        document.getElementById('deskripsi').value = this.dataset.deskripsi;
        document.getElementById('status_tersedia').checked = this.dataset.status === '1';
        document.getElementById('judulEditor').textContent = 'Ubah Data Menu';
        openEditor();
    }));
    const search = document.getElementById('cariMenu'); let active = 'all';
    function filter() {
        const q = search.value.toLocaleLowerCase('id');
        document.querySelectorAll('.baris-menu').forEach(row => {
            row.hidden = !(row.dataset.nama.includes(q) && (active === 'all' || row.dataset.kategori === active));
        });
    }
    search.addEventListener('input', filter);
    document.querySelectorAll('.filter-btn').forEach(btn => btn.addEventListener('click', function () {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active'); active = this.dataset.kategori; filter();
    }));
})();
</script>