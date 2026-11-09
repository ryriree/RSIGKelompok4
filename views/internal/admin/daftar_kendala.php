<?php
$namaUser = $_SESSION['user']['nama'] ?? 'Pengguna';
$roleUser = $_SESSION['user']['role'] ?? '';
$isAdmin = ($roleUser === 'Admin');
$pesan = $_SESSION['pesan_laporan'] ?? '';
unset($_SESSION['pesan_laporan']);
function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function qsLaporan($changes = []) {
    $params = array_merge($_GET, $changes);
    return 'index.php?' . http_build_query($params);
}
?>
<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Laporan Komplain & Feedback - Maky Chicken</title>
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Titan+One&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="public/css/maky.css">
<style>

:root{--red-100:#FBE0DD;--red-600:#B3131B;--red-700:#8E0F18;--red-900:#5E0A10;--cream-50:#FFFAF0;--cream-100:#FFF3DC;--cream-200:#FBE6BE;--cream-300:#F2D49A;--yellow-100:#FFF0C2;--yellow-500:#FFB21E;--yellow-600:#F28C0F;--blue-100:#DCEBFA;--blue-600:#1F5FAE;--green-600:#1E8E4E;--green-100:#D9F2E3;--ink-900:#2A1010;--ink-600:#6B4A45;--primary:var(--red-600);--primary-hover:var(--red-700);--border:var(--cream-300);--text:var(--ink-900);--text-muted:var(--ink-600);--accent:var(--yellow-500);--info:var(--blue-600);--success:var(--green-600);--sp-1:4px;--sp-2:8px;--sp-3:12px;--sp-4:16px;--sp-5:24px;--sp-6:32px;--sp-7:48px;--shadow-1:0 2px 8px rgba(142,15,24,.08);--shadow-2:0 10px 28px rgba(142,15,24,.14);--shadow-3:0 18px 44px rgba(94,10,16,.22);--font-display:'Titan One','Arial Black',sans-serif;--font-body:'Poppins',system-ui,sans-serif}
.mc-app{display:grid;grid-template-columns:248px minmax(0,1fr);min-height:100vh}
.side{background:var(--red-900);color:var(--cream-50);padding:var(--sp-4);position:sticky;top:0;height:100vh;overflow:auto;display:flex;flex-direction:column;gap:var(--sp-2)}
.brand{display:flex;gap:var(--sp-3);align-items:center;padding-bottom:var(--sp-4);border-bottom:1px solid rgba(255,250,240,.15);margin-bottom:var(--sp-2)}
.logo{width:44px;height:44px;border-radius:50%;background:var(--accent);color:var(--red-900);display:grid;place-items:center;flex:none;font-weight:700}
.brand b{font-family:var(--font-display);font-weight:400;font-size:18px;letter-spacing:.04em;display:block;line-height:1.1;color:#fff}
.brand small{color:var(--accent);font-weight:700;letter-spacing:.12em;font-size:10px;text-transform:uppercase}
.sidesearch{width:100%;height:44px;border-radius:999px;border:1.5px solid rgba(255,250,240,.2);background:rgba(255,250,240,.06);padding:0 var(--sp-4);color:var(--cream-50)}
.sidesearch::placeholder{color:rgba(255,250,240,.6)}
.grp{font-size:11px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:rgba(255,250,240,.55);margin:var(--sp-4) var(--sp-2) 0}
.nav{display:flex;align-items:center;gap:var(--sp-3);min-height:44px;padding:var(--sp-2) var(--sp-4);border-radius:999px;border:0;background:none;color:var(--cream-50);font-weight:600;font-size:14px;text-align:left;cursor:pointer;width:100%;text-decoration:none}
.nav:hover{background:rgba(255,250,240,.1)}
.nav[aria-current=page]{background:var(--accent);color:var(--ink-900);box-shadow:var(--shadow-2)}
.main{padding:var(--sp-5) var(--sp-6) var(--sp-7);min-width:0}
.top{display:flex;justify-content:space-between;align-items:flex-start;gap:var(--sp-4);flex-wrap:wrap;margin-bottom:var(--sp-5)}
.top h1{font-family:var(--font-display);font-weight:400;text-transform:uppercase;line-height:1.02;color:var(--red-600)}
.top p{font-size:12px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--text-muted);margin-top:var(--sp-1)}
.userbox{display:flex;gap:var(--sp-3);align-items:center;flex-wrap:wrap}
.pill-user{display:flex;gap:var(--sp-3);align-items:center;background:#fff;border:1.5px solid var(--border);border-radius:999px;padding:6px 20px 6px 6px}
.av{width:36px;height:36px;border-radius:50%;background:var(--primary);color:var(--cream-50);display:grid;place-items:center;font-weight:700;font-size:12px}
.pill-user b{display:block;font-size:14px;line-height:1.2}.pill-user small{font-size:12px;color:var(--text-muted)}
.mc-btn{display:inline-flex;align-items:center;justify-content:center;gap:var(--sp-2);min-height:44px;padding:0 var(--sp-5);border-radius:999px;border:1.5px solid transparent;font-weight:700;font-size:13px;letter-spacing:.08em;text-transform:uppercase;cursor:pointer;transition:transform .15s,background .15s;background:var(--primary);color:var(--cream-50);text-decoration:none}
.mc-btn:hover{transform:translateY(-1px);background:var(--primary-hover)}
.mc-btn--outline{background:transparent;color:var(--primary);border-color:var(--primary)}
@media(max-width:860px){.mc-app{grid-template-columns:1fr}.side{position:static;height:auto;flex-direction:row;flex-wrap:nowrap;overflow-x:auto;align-items:center}.brand{border:0;padding:0;margin:0}.brand div{display:none}.grp,.sidesearch{display:none}.nav{width:auto;white-space:nowrap}.main{padding:var(--sp-4)}}

body{margin:0;background:var(--cream-100);color:var(--ink-900);font:13px var(--font-body)}
.cards{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px;margin-bottom:24px}
.card{background:#fff;border:1.5px solid var(--cream-300);border-radius:24px;padding:22px;box-shadow:var(--shadow-1)}
.card.warning{background:var(--yellow-500);border-color:var(--yellow-500)}.card.success strong{color:var(--green-600)}
.card small{font-weight:700;color:var(--ink-600);font-size:11px}.card strong{display:block;font:30px var(--font-display);color:var(--red-600);margin:12px 0 5px}.card.warning strong{color:var(--ink-900)}
.filters{display:flex;gap:12px;flex-wrap:wrap;background:#fff;border:1px solid var(--cream-300);border-radius:20px;padding:16px;margin-bottom:22px}
.filters input,.filters select{padding:11px 14px;border:1px solid var(--cream-300);border-radius:24px;background:#fff;font:inherit}.filters input{flex:1;min-width:180px}
.btn{border:0;border-radius:24px;background:var(--red-600);color:#fff;padding:11px 18px;font:700 12px var(--font-body);cursor:pointer;text-decoration:none;display:inline-block}.btn:hover{background:var(--red-700)}
.tablebox{background:#fff;border:1px solid var(--cream-300);border-radius:20px;overflow:hidden}.tablehead{padding:18px 22px;display:flex;justify-content:space-between;gap:10px;font-weight:700;color:var(--red-600)}
table{width:100%;border-collapse:collapse}th{background:var(--red-600);color:#fff;text-align:left;padding:14px 12px;font-size:10px;letter-spacing:.06em}td{padding:16px 12px;border-bottom:1px solid var(--cream-300);vertical-align:middle}tr:nth-child(even){background:var(--cream-50)}
.badge{display:inline-block;padding:6px 12px;border-radius:20px;font-size:10px;font-weight:700}.pending{background:var(--yellow-100);color:#a65d00}.diproses{background:var(--blue-100);color:var(--blue-600)}.selesai{background:var(--green-100);color:var(--green-600)}.jenis{background:var(--red-100);color:var(--red-600)}.jenis.feedback{background:var(--blue-100);color:var(--blue-600)}
.pagination{display:flex;gap:8px;justify-content:flex-end;padding:16px}.pagination a{padding:7px 12px;border:1px solid var(--cream-300);border-radius:50%;color:var(--ink-900);text-decoration:none}.pagination a.active{background:var(--red-600);color:#fff}.alert{padding:12px 16px;background:var(--green-100);border-radius:12px;margin-bottom:16px}.empty{text-align:center;padding:30px;color:var(--ink-600)}
.modal-overlay{position:fixed;inset:0;background:rgba(42,16,16,.58);backdrop-filter:blur(5px);-webkit-backdrop-filter:blur(5px);display:none;align-items:center;justify-content:center;padding:20px;z-index:1000}.modal-overlay.open{display:flex}.modal{background:#fff;border:2px solid var(--cream-300);border-radius:26px;padding:32px;width:min(540px,100%);max-height:92vh;overflow:auto;box-shadow:0 24px 60px rgba(42,16,16,.28)}.modal-head{display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid var(--cream-300);padding-bottom:12px;margin-bottom:16px}.modal h2{font:21px var(--font-display);text-transform:uppercase;color:var(--red-600);margin:0}.modal-close{border:0;background:transparent;color:var(--ink-600);font-size:23px;cursor:pointer;line-height:1;padding:3px 5px}.detail-box{display:flex;gap:12px;background:var(--blue-100);border:1px solid var(--blue-600);border-radius:17px;padding:14px 16px;margin-bottom:16px;color:#173f73;overflow-wrap:anywhere}.detail-icon{font-size:19px;line-height:1.2;color:var(--blue-600);flex:none}.detail-content{font-size:12px;line-height:1.55}.detail-content strong{display:block;color:#173f73;font-size:13px;margin-bottom:3px}.detail-content p{margin:0;color:var(--ink-900)}.modal label{display:block;font-weight:700;font-size:12px;margin:0 0 9px}.required{color:var(--red-600)}.status-options{display:flex;gap:10px;flex-wrap:wrap;margin:0 0 24px}.status-option{display:inline-flex;align-items:center;gap:8px;border:1px solid var(--cream-300);border-radius:999px;background:#fff;color:var(--ink-900);padding:11px 17px;font:600 12px var(--font-body);cursor:pointer;transition:.15s}.status-option:hover{border-color:var(--yellow-600);background:var(--cream-50)}.status-option.active{background:var(--blue-600);border-color:#164d91;color:#fff;box-shadow:0 3px 8px rgba(31,95,174,.25)}.status-option input{position:absolute;opacity:0;pointer-events:none}.status-help{font-size:11px;color:var(--ink-600);margin:-1px 0 17px}.modal textarea{display:block;width:100%;min-height:96px;resize:vertical;padding:12px 14px;margin:0 0 8px;border:1px solid var(--cream-300);border-radius:16px;font:13px/1.6 var(--font-body);color:var(--ink-900);box-sizing:border-box}.modal textarea:focus{outline:2px solid rgba(31,95,174,.18);border-color:var(--blue-600)}.note-help{font-size:11px;color:var(--ink-600);margin:0 0 16px}.modal-actions{display:flex;justify-content:flex-end;gap:10px;border-top:1px solid var(--cream-300);padding-top:9px}.modal-actions .btn{min-height:44px;padding:0 19px;border-radius:999px;font-size:11px;letter-spacing:.04em;text-transform:uppercase}.modal-actions .btn-secondary{background:#fff;color:var(--ink-900);border:1px solid var(--cream-300)}.modal-actions .btn-secondary:hover{background:var(--cream-50)}.modal-actions .btn-save{background:var(--red-600)}.modal-actions .btn-save:hover{background:var(--red-700)}@media(max-width:560px){.modal{padding:22px 18px;border-radius:22px}.modal h2{font-size:17px}.status-options{gap:7px}.status-option{padding:10px 12px}.modal-actions{flex-direction:column-reverse}.modal-actions .btn{width:100%}}
@media(max-width:900px){.cards{grid-template-columns:1fr}.tablebox{overflow-x:auto}table{min-width:850px}}
</style></head><body><div class="mc-app">

<aside class="side" aria-label="Navigasi staff">
    <div class="brand"><span class="logo">MC</span><div><b>MAKY CHICKEN</b><small>Sistem Restoran</small></div></div>
    <input id="navSearch" class="sidesearch" type="search" placeholder="Cari menu... Ctrl /" aria-label="Cari menu navigasi">
    <div class="grp">OVERVIEW</div>
    <a class="nav" href="index.php?page=internal-dashboard"><span>⌂</span> Dashboard</a>
    <a class="nav" href="index.php?page=pos"><span>▤</span> Antrean POS Kasir</a>
    <div class="grp">MANAJEMEN OPERASIONAL</div>
    <a class="nav" href="index.php?page=laporan-penjualan"><span>▥</span> Laporan Penjualan</a>
    <a class="nav" href="index.php?page=reservasi"><span>▦</span> Manajemen Reservasi</a>
    <a class="nav" href="index.php?page=kelola-menu" data-active="kelola-menu"><span>⚒</span> Kelola Menu</a>
    <div class="grp">PENGATURAN &amp; LAINNYA</div>
    <a class="nav" href="index.php?page=kelola-akun"><span>♙</span> Kelola Akun Staff</a>
    <a class="nav" href="index.php?page=laporan-kendala" data-active="laporan-kendala" aria-current="page"><span>☏</span> Komplain &amp; Feedback</a>
    <div style="margin-top:auto;padding-top:16px;color:rgba(255,250,240,.7);font-size:11px">MAKY CHICKEN · PORTAL STAFF</div>
</aside>

<main class="main"><header class="top"><div><h1 style="font-size:clamp(30px,4vw,40px)">Laporan Komplain &amp; Feedback</h1><p>Daftar laporan dan masukan pelanggan</p></div><div class="userbox"><div class="pill-user"><span class="av"><?= e(substr($namaUser,0,2)) ?></span><div><b><?= e($namaUser) ?></b><small><?= e($roleUser) ?></small></div></div><a class="mc-btn mc-btn--outline" href="index.php?page=logout">LOG OUT</a></div></header>
<?php if ($pesan): ?><div class="alert"><?= e($pesan) ?></div><?php endif; ?>
<section class="cards"><div class="card"><small>TOTAL LAPORAN</small><strong><?= (int)($ringkasan['total'] ?? 0) ?> Laporan</strong><span>Akumulasi keluhan dan masukan pelanggan</span></div><div class="card warning"><small>PENDING / PERLU TINDAKAN</small><strong><?= (int)($ringkasan['pending'] ?? 0) ?> Laporan</strong><span>Perlu tindak lanjut</span></div><div class="card success"><small>SELESAI DITANGANI</small><strong><?= (int)($ringkasan['selesai'] ?? 0) ?> Laporan</strong><span>Telah direspons</span></div></section>
<form class="filters" method="GET"><input type="hidden" name="page" value="laporan-kendala"><input name="cari" placeholder="Cari nama pelanggan, kontak, atau kata kunci" value="<?= e($keyword) ?>"><select name="jenis"><option value="Semua">Semua Jenis</option><option value="Komplain" <?= $jenis==='Komplain'?'selected':'' ?>>Komplain</option><option value="Feedback" <?= $jenis==='Feedback'?'selected':'' ?>>Feedback</option></select><select name="status"><option value="Semua">Semua Status</option><?php foreach(['Pending','Diproses','Selesai'] as $s): ?><option value="<?= e($s) ?>" <?= $status===$s?'selected':'' ?>><?= e($s) ?></option><?php endforeach; ?></select><button class="btn">Terapkan</button></form>
<section class="tablebox"><div class="tablehead"><span>DAFTAR MASUKAN PELANGGAN TERBARU</span><span>Menampilkan <?= count($daftarLaporan) ?> dari <?= (int)$totalData ?> laporan</span></div><table><thead><tr><th>TANGGAL &amp; WAKTU</th><th>NAMA PELANGGAN &amp; KONTAK</th><th>JENIS LAPORAN</th><th>ISI PESAN / DETAIL MASUKAN</th><th>STATUS PENANGANAN</th><?php if($isAdmin): ?><th>AKSI</th><?php endif; ?></tr></thead><tbody>
<?php if (!$daftarLaporan): ?><tr><td colspan="<?= $isAdmin?6:5 ?>" class="empty">Belum ada laporan yang sesuai dengan pencarian.</td></tr><?php else: foreach($daftarLaporan as $laporan): $statusClass=strtolower($laporan['status']); ?><tr><td><strong><?= e(date('d/m/Y',strtotime($laporan['tanggal_kirim']))) ?></strong><br><small><?= e(date('H:i',strtotime($laporan['tanggal_kirim']))) ?> WIB</small></td><td><strong><?= e($laporan['nama']) ?></strong><br><small><?= e($laporan['kontak']) ?></small></td><td><span class="badge jenis <?= strtolower($laporan['jenis_laporan'])==='feedback'?'feedback':'' ?>"><?= e($laporan['jenis_laporan']) ?></span></td><td><?= e($laporan['isi_pesan']) ?><?php if(!empty($laporan['catatan_penanganan'])): ?><br><small><b>Catatan:</b> <?= e($laporan['catatan_penanganan']) ?></small><?php endif; ?></td><td><span class="badge <?= e($statusClass) ?>"><?= e($laporan['status']) ?></span></td>
<?php if($isAdmin): ?><td><button class="btn js-ubah-status" type="button"
data-id="<?= (int)$laporan['id_laporan'] ?>"
data-nama="<?= e($laporan['nama']) ?>"
data-kontak="<?= e($laporan['kontak']) ?>"
data-jenis="<?= e($laporan['jenis_laporan']) ?>"
data-isi="<?= e($laporan['isi_pesan']) ?>"
data-status="<?= e($laporan['status']) ?>"
data-catatan="<?= e($laporan['catatan_penanganan'] ?? '') ?>">Ubah Status</button></td><?php endif; ?></tr><?php endforeach; endif; ?>
</tbody></table><div class="pagination"><?php for($i=1;$i<=$totalHalaman;$i++): ?><a class="<?= $i===$halaman?'active':'' ?>" href="<?= e(qsLaporan(['halaman'=>$i])) ?>"><?= $i ?></a><?php endfor; ?></div></section>
</main></div>
<?php if($isAdmin): ?>
<div class="modal-overlay" id="modalStatus" aria-hidden="true"><div class="modal" role="dialog" aria-modal="true" aria-labelledby="judulModal"><div class="modal-head"><h2 id="judulModal">Ubah Status Penanganan</h2><button type="button" class="modal-close" id="tutupModal" aria-label="Tutup">&times;</button></div><div id="detailLaporan" class="detail-box"><span class="detail-icon">ⓘ</span><div class="detail-content"><strong>Detail Laporan Terpilih:</strong><p id="detailTeks"></p></div></div><form method="POST" action="index.php?page=ubah-status-laporan"><input type="hidden" name="id_laporan" id="id_laporan"><label>PILIH STATUS BARU <span class="required">*</span></label><div class="status-options" role="radiogroup" aria-label="Pilih status baru"><label class="status-option" data-status-option="Pending"><input type="radio" name="status" value="Pending" required><span>◷</span> Pending</label><label class="status-option" data-status-option="Diproses"><input type="radio" name="status" value="Diproses"><span>↻</span> Diproses</label><label class="status-option" data-status-option="Selesai"><input type="radio" name="status" value="Selesai"><span>✓</span> Selesai</label></div><p class="status-help">Status akan otomatis diperbarui dan dapat dipantau oleh tim kasir &amp; manajer.</p><label for="catatan">CATATAN TINDAKAN / RESPONSE <span style="font-weight:400">(Opsional)</span></label><textarea name="catatan_penanganan" id="catatan" rows="3" placeholder="Tuliskan tindakan atau respons penanganan laporan..."></textarea><p class="note-help">Catatan disimpan di log riwayat penanganan keluhan restoran Maky Chicken.</p><div class="modal-actions"><button type="button" class="btn btn-secondary" id="batalModal">Batal</button><button type="submit" class="btn btn-save">▣ &nbsp; Simpan Perubahan Status</button></div></form></div></div>
<script>
document.addEventListener('DOMContentLoaded',function(){
 const modal=document.getElementById('modalStatus');
 const detailTeks=document.getElementById('detailTeks');
 const statusLabels=Array.from(document.querySelectorAll('[data-status-option]'));
 function pilihStatus(value){
  statusLabels.forEach(function(label){
   const radio=label.querySelector('input');
   radio.checked=radio.value===value;
   label.classList.toggle('active',radio.checked);
  });
 }
 statusLabels.forEach(function(label){label.addEventListener('click',function(){pilihStatus(this.dataset.statusOption);});});
 document.querySelectorAll('.js-ubah-status').forEach(function(btn){
  btn.addEventListener('click',function(){
   document.getElementById('id_laporan').value=this.dataset.id;
   document.getElementById('catatan').value=this.dataset.catatan;
   detailTeks.replaceChildren();
   const customer=document.createElement('span');
   customer.innerHTML='<b>Pelanggan:</b> ';
   detailTeks.appendChild(customer);
   detailTeks.appendChild(document.createTextNode(this.dataset.nama+' ('+this.dataset.kontak+') — '));
   const kind=document.createElement('i');kind.textContent=this.dataset.jenis+': ';
   detailTeks.appendChild(kind);
   detailTeks.appendChild(document.createTextNode('“'+this.dataset.isi+'”'));
   pilihStatus(this.dataset.status);
   modal.classList.add('open');modal.setAttribute('aria-hidden','false');
   document.body.style.overflow='hidden';
  });
 });
 function closeModal(){modal.classList.remove('open');modal.setAttribute('aria-hidden','true');document.body.style.overflow='';}
 document.getElementById('batalModal').addEventListener('click',closeModal);
 document.getElementById('tutupModal').addEventListener('click',closeModal);
 modal.addEventListener('click',function(ev){if(ev.target===modal)closeModal();});
 document.addEventListener('keydown',function(ev){if(ev.key==='Escape'&&modal.classList.contains('open'))closeModal();});
});
</script>
<?php endif; ?></body></html>
