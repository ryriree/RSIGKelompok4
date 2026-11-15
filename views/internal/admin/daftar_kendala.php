<?php
// Data dari LaporanKendalaController: $daftarLaporan, $ringkasan, $keyword, $jenis, $status,
// $halaman, $totalHalaman, $totalData, $pesan
$isAdmin = strtolower($_SESSION['role'] ?? '') === 'admin';
$daftarLaporan = $daftarLaporan ?? [];
if (!function_exists('e')) {
    function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
}
if (!function_exists('qsLaporan')) {
    function qsLaporan($changes = []) { return 'index.php?' . http_build_query(array_merge($_GET, $changes)); }
}
?>
<style>
.lk{font-size:13px}
.lk .cards{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px;margin-bottom:24px}
.lk .card{background:#fff;border:1.5px solid var(--cream-300);border-radius:24px;padding:22px;box-shadow:var(--shadow-1)}
.lk .card.warning{background:var(--yellow-500);border-color:var(--yellow-500)}
.lk .card small{font-weight:700;color:var(--ink-600);font-size:11px}
.lk .card strong{display:block;font:30px var(--font-display);color:var(--red-600);margin:12px 0 5px}
.lk .card.success strong{color:var(--green-600)}.lk .card.warning strong{color:var(--ink-900)}
.lk .filters{display:flex;gap:12px;flex-wrap:wrap;background:#fff;border:1.5px solid var(--cream-300);border-radius:20px;padding:16px;margin-bottom:22px}
.lk .filters input,.lk .filters select{padding:11px 14px;border:1.5px solid var(--cream-300);border-radius:24px;background:#fff;font:inherit}
.lk .filters input{flex:1;min-width:180px}
.lk .btn{border:0;border-radius:24px;background:var(--red-600);color:#fff;padding:11px 18px;font:700 12px var(--font-body);cursor:pointer;text-decoration:none;display:inline-block}
.lk .btn:hover{background:var(--red-700)}
.lk .tablebox{background:#fff;border:1.5px solid var(--cream-300);border-radius:20px;overflow:hidden}
.lk .tablehead{padding:18px 22px;display:flex;justify-content:space-between;gap:10px;font-weight:700;color:var(--red-600);flex-wrap:wrap}
.lk table{width:100%;border-collapse:collapse}
.lk th{background:var(--red-600);color:#fff;text-align:left;padding:14px 12px;font-size:10px;letter-spacing:.06em}
.lk td{padding:16px 12px;border-bottom:1px solid var(--cream-300);vertical-align:middle}
.lk tr:nth-child(even) td{background:var(--cream-50)}
.lk .badge{display:inline-block;padding:6px 12px;border-radius:20px;font-size:10px;font-weight:700;white-space:nowrap}
.lk .pending{background:var(--yellow-100);color:#a65d00}.lk .diproses{background:var(--blue-100);color:var(--blue-600)}
.lk .selesai{background:var(--green-100);color:var(--green-600)}
.lk .jenis{background:var(--red-100);color:var(--red-600)}.lk .jenis.feedback{background:var(--blue-100);color:var(--blue-600)}
.lk .pagination{display:flex;gap:8px;justify-content:flex-end;padding:16px}
.lk .pagination a{padding:7px 12px;border:1px solid var(--cream-300);border-radius:50%;color:var(--ink-900);text-decoration:none}
.lk .pagination a.active{background:var(--red-600);color:#fff}
.lk .alert{padding:12px 16px;background:var(--green-100);color:var(--green-600);font-weight:600;border-radius:12px;margin-bottom:16px}
.lk .empty{text-align:center;padding:30px;color:var(--ink-600)}
.lk .tscroll{overflow-x:auto}.lk table{min-width:850px}

.lk-overlay{position:fixed;inset:0;background:rgba(42,16,16,.58);backdrop-filter:blur(5px);display:none;align-items:center;justify-content:center;padding:20px;z-index:1000}
.lk-overlay.open{display:flex}
.lk-modal{background:#fff;border:2px solid var(--cream-300);border-radius:26px;padding:32px;width:min(540px,100%);max-height:92vh;overflow:auto;box-shadow:0 24px 60px rgba(42,16,16,.28);font-size:13px}
.lk-modal .modal-head{display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid var(--cream-300);padding-bottom:12px;margin-bottom:16px}
.lk-modal h2{font:21px var(--font-display);text-transform:uppercase;color:var(--red-600);margin:0}
.lk-modal .modal-close{border:0;background:transparent;color:var(--ink-600);font-size:23px;cursor:pointer}
.lk-modal .detail-box{display:flex;gap:12px;background:var(--blue-100);border:1px solid var(--blue-600);border-radius:17px;padding:14px 16px;margin-bottom:16px;color:#173f73;overflow-wrap:anywhere}
.lk-modal .detail-content{font-size:12px;line-height:1.55}.lk-modal .detail-content strong{display:block;margin-bottom:3px}
.lk-modal .detail-content p{margin:0;color:var(--ink-900)}
.lk-modal label{display:block;font-weight:700;font-size:12px;margin:0 0 9px}
.lk-modal .status-options{display:flex;gap:10px;flex-wrap:wrap;margin:0 0 24px}
.lk-modal .status-option{display:inline-flex;align-items:center;gap:8px;border:1px solid var(--cream-300);border-radius:999px;background:#fff;padding:11px 17px;font:600 12px var(--font-body);cursor:pointer;margin:0}
.lk-modal .status-option.active{background:var(--blue-600);border-color:#164d91;color:#fff}
.lk-modal .status-option input{position:absolute;opacity:0;pointer-events:none}
.lk-modal textarea{display:block;width:100%;min-height:96px;resize:vertical;padding:12px 14px;margin:0 0 16px;border:1px solid var(--cream-300);border-radius:16px;font:13px/1.6 var(--font-body);box-sizing:border-box}
.lk-modal .modal-actions{display:flex;justify-content:flex-end;gap:10px;border-top:1px solid var(--cream-300);padding-top:12px}
.lk-modal .btn{border:0;border-radius:999px;background:var(--red-600);color:#fff;padding:12px 19px;font:700 11px var(--font-body);cursor:pointer;text-transform:uppercase}
.lk-modal .btn-secondary{background:#fff;color:var(--ink-900);border:1px solid var(--cream-300)}
@media(max-width:900px){.lk .cards{grid-template-columns:1fr}}
</style>

<div class="lk">
<p style="font-size:12px;color:var(--text-muted);margin-bottom:16px">Daftar laporan dan masukan pelanggan.</p>

<?php if (!empty($pesan)): ?><div class="alert"><?= e($pesan) ?></div><?php endif; ?>

<section class="cards">
    <div class="card"><small>TOTAL LAPORAN</small><strong><?= (int)($ringkasan['total'] ?? 0) ?> Laporan</strong><span>Akumulasi keluhan dan masukan pelanggan</span></div>
    <div class="card warning"><small>PENDING / PERLU TINDAKAN</small><strong><?= (int)($ringkasan['pending'] ?? 0) ?> Laporan</strong><span>Perlu tindak lanjut</span></div>
    <div class="card success"><small>SELESAI DITANGANI</small><strong><?= (int)($ringkasan['selesai'] ?? 0) ?> Laporan</strong><span>Telah direspons</span></div>
</section>

<form class="filters" method="GET" action="index.php">
    <input type="hidden" name="page" value="laporan-kendala">
    <input name="cari" placeholder="Cari nama pelanggan, kontak, atau kata kunci" value="<?= e($keyword ?? '') ?>">
    <select name="jenis">
        <option value="Semua">Semua Jenis</option>
        <option value="Komplain" <?= ($jenis ?? '') === 'Komplain' ? 'selected' : '' ?>>Komplain</option>
        <option value="Feedback" <?= ($jenis ?? '') === 'Feedback' ? 'selected' : '' ?>>Feedback</option>
    </select>
    <select name="status">
        <option value="Semua">Semua Status</option>
        <?php foreach (['Pending', 'Diproses', 'Selesai'] as $s): ?>
            <option value="<?= e($s) ?>" <?= ($status ?? '') === $s ? 'selected' : '' ?>><?= e($s) ?></option>
        <?php endforeach; ?>
    </select>
    <button class="btn">Terapkan</button>
</form>

<section class="tablebox">
    <div class="tablehead"><span>DAFTAR MASUKAN PELANGGAN TERBARU</span><span>Menampilkan <?= count($daftarLaporan) ?> dari <?= (int)($totalData ?? 0) ?> laporan</span></div>
    <div class="tscroll">
    <table>
        <thead><tr>
            <th>TANGGAL &amp; WAKTU</th><th>NAMA PELANGGAN &amp; KONTAK</th><th>JENIS LAPORAN</th>
            <th>ISI PESAN / DETAIL</th><th>STATUS</th><?php if ($isAdmin): ?><th>AKSI</th><?php endif; ?>
        </tr></thead>
        <tbody>
        <?php if (!$daftarLaporan): ?>
            <tr><td colspan="<?= $isAdmin ? 6 : 5 ?>" class="empty">Belum ada laporan yang sesuai dengan pencarian.</td></tr>
        <?php else: foreach ($daftarLaporan as $laporan): $statusClass = strtolower($laporan['status']); ?>
            <tr>
                <td><strong><?= e(date('d/m/Y', strtotime($laporan['tanggal_kirim']))) ?></strong><br><small><?= e(date('H:i', strtotime($laporan['tanggal_kirim']))) ?> WIB</small></td>
                <td><strong><?= e($laporan['nama']) ?></strong><br><small><?= e($laporan['kontak']) ?></small></td>
                <td><span class="badge jenis <?= strtolower($laporan['jenis_laporan']) === 'feedback' ? 'feedback' : '' ?>"><?= e($laporan['jenis_laporan']) ?></span></td>
                <td><?= e($laporan['isi_pesan']) ?><?php if (!empty($laporan['catatan_penanganan'])): ?><br><small><b>Catatan:</b> <?= e($laporan['catatan_penanganan']) ?></small><?php endif; ?></td>
                <td><span class="badge <?= e($statusClass) ?>"><?= e($laporan['status']) ?></span></td>
                <?php if ($isAdmin): ?>
                <td><button class="btn js-ubah-status" type="button"
                    data-id="<?= (int)$laporan['id_laporan'] ?>"
                    data-nama="<?= e($laporan['nama']) ?>"
                    data-kontak="<?= e($laporan['kontak']) ?>"
                    data-jenis="<?= e($laporan['jenis_laporan']) ?>"
                    data-isi="<?= e($laporan['isi_pesan']) ?>"
                    data-status="<?= e($laporan['status']) ?>"
                    data-catatan="<?= e($laporan['catatan_penanganan'] ?? '') ?>">Ubah Status</button></td>
                <?php endif; ?>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
    </div>
    <div class="pagination">
        <?php for ($i = 1; $i <= ($totalHalaman ?? 1); $i++): ?>
            <a class="<?= $i === ($halaman ?? 1) ? 'active' : '' ?>" href="<?= e(qsLaporan(['halaman' => $i])) ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
</section>

<?php if ($isAdmin): ?>
<div class="lk-overlay" id="modalStatus" aria-hidden="true">
  <div class="lk-modal" role="dialog" aria-modal="true" aria-labelledby="judulModal">
    <div class="modal-head"><h2 id="judulModal">Ubah Status Penanganan</h2><button type="button" class="modal-close" id="tutupModal" aria-label="Tutup">&times;</button></div>
    <div class="detail-box"><span>ⓘ</span><div class="detail-content"><strong>Detail Laporan Terpilih:</strong><p id="detailTeks"></p></div></div>
    <form method="POST" action="index.php?page=ubah-status-laporan">
      <input type="hidden" name="id_laporan" id="id_laporan">
      <label>PILIH STATUS BARU *</label>
      <div class="status-options" role="radiogroup">
        <label class="status-option" data-status-option="Pending"><input type="radio" name="status" value="Pending" required>◷ Pending</label>
        <label class="status-option" data-status-option="Diproses"><input type="radio" name="status" value="Diproses">↻ Diproses</label>
        <label class="status-option" data-status-option="Selesai"><input type="radio" name="status" value="Selesai">✓ Selesai</label>
      </div>
      <label for="catatan">CATATAN TINDAKAN / RESPONSE (opsional)</label>
      <textarea name="catatan_penanganan" id="catatan" rows="3" placeholder="Tuliskan tindakan atau respons penanganan laporan..."></textarea>
      <div class="modal-actions">
        <button type="button" class="btn btn-secondary" id="batalModal">Batal</button>
        <button type="submit" class="btn">Simpan Perubahan Status</button>
      </div>
    </form>
  </div>
</div>
<script>
(function () {
    const modal = document.getElementById('modalStatus');
    const detailTeks = document.getElementById('detailTeks');
    const labels = Array.from(document.querySelectorAll('[data-status-option]'));
    function pilihStatus(v) {
        labels.forEach(l => { const r = l.querySelector('input'); r.checked = r.value === v; l.classList.toggle('active', r.checked); });
    }
    labels.forEach(l => l.addEventListener('click', function () { pilihStatus(this.dataset.statusOption); }));
    document.querySelectorAll('.js-ubah-status').forEach(btn => btn.addEventListener('click', function () {
        document.getElementById('id_laporan').value = this.dataset.id;
        document.getElementById('catatan').value = this.dataset.catatan;
        detailTeks.textContent = 'Pelanggan: ' + this.dataset.nama + ' (' + this.dataset.kontak + ') — ' + this.dataset.jenis + ': “' + this.dataset.isi + '”';
        pilihStatus(this.dataset.status);
        modal.classList.add('open');
    }));
    function tutup() { modal.classList.remove('open'); }
    document.getElementById('batalModal').addEventListener('click', tutup);
    document.getElementById('tutupModal').addEventListener('click', tutup);
    modal.addEventListener('click', ev => { if (ev.target === modal) tutup(); });
    document.addEventListener('keydown', ev => { if (ev.key === 'Escape') tutup(); });
})();
</script>
<?php endif; ?>
</div>