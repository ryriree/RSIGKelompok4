<?php
// Data dari KelolaStaff: $daftarStaff, $flash, $idSaya
$daftarStaff = $daftarStaff ?? [];
$q = $_GET['q'] ?? '';
$hitung = fn($r) => count(array_filter($daftarStaff, fn($u) => strtolower($u['role']) === $r));
?>
<style>
.st{font-size:13px}
.st .desc{font-size:12px;color:var(--text-muted);margin-bottom:16px}
.st .notif{padding:12px 16px;border-radius:14px;font-weight:600;font-size:12px;margin-bottom:16px;overflow-wrap:anywhere}
.st .notif--ok{background:var(--green-100);color:var(--success);border:1px solid #b7e4c7}
.st .notif--err{background:var(--red-100);color:var(--red-700);border:1px solid #f1b5b0}
.st .bar{display:flex;gap:12px;align-items:center;flex-wrap:wrap;margin-bottom:20px}
.st .search{flex:1;min-width:240px;max-width:420px}
.st .search input{width:100%;height:42px;border-radius:999px;border:1.5px solid var(--cream-300);background:#fff;padding:0 18px;font-size:12px;outline:none}
.st .count{font-size:12px;color:var(--text-muted);margin-left:auto}
.st .btn{display:inline-flex;align-items:center;justify-content:center;min-height:42px;padding:0 20px;border-radius:999px;border:0;background:var(--red-600);color:#fff;font-weight:700;font-size:11px;letter-spacing:.06em;text-transform:uppercase;cursor:pointer}
.st .btn:hover{background:var(--red-700)}
.st .btn--ghost{background:transparent;color:var(--text);border:1.5px solid var(--border)}
.st .btn--sm{min-height:30px;padding:0 12px;font-size:10px}
.st .btn--ok{background:var(--success)}
.st .btn--info{background:var(--info)}
.st .kpi{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:20px}
.st .kpi div{background:#fff;border:1.5px solid var(--cream-300);border-radius:18px;padding:16px 18px;box-shadow:var(--shadow-1)}
.st .kpi span{font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);display:block}
.st .kpi b{font-family:var(--font-display);font-weight:400;font-size:26px;display:block;margin-top:4px}
.st .wrap{background:#fff;border:1.5px solid var(--cream-300);border-radius:20px;overflow-x:auto;box-shadow:var(--shadow-1)}
.st table{width:100%;border-collapse:collapse;font-size:12px;min-width:820px}
.st th{background:var(--red-600);color:#fff;font-size:10px;letter-spacing:.08em;text-transform:uppercase;padding:14px;text-align:left}
.st td{padding:12px 14px;border-bottom:1px solid var(--cream-200);vertical-align:middle}
.st tr:nth-child(even) td{background:var(--cream-50)}
.st .pill{display:inline-flex;padding:2px 10px;border-radius:999px;font-size:10px;font-weight:700;white-space:nowrap}
.st .p-ok{background:var(--green-100);color:var(--success)}
.st .p-bad{background:var(--red-100);color:var(--red-700)}
.st .p-wait{background:var(--yellow-100);color:var(--yellow-600)}
.st .p-info{background:var(--blue-100);color:var(--info)}
.st .aksi{display:flex;gap:6px;justify-content:center;flex-wrap:wrap}
.st .aksi form{margin:0}
.st .kosong{text-align:center;padding:32px;color:var(--text-muted);font-style:italic}
.st-overlay{position:fixed;inset:0;background:rgba(94,10,16,.45);display:flex;align-items:center;justify-content:center;padding:16px;z-index:1000;backdrop-filter:blur(4px);opacity:0;pointer-events:none;transition:opacity .25s}
.st-overlay.on{opacity:1;pointer-events:auto}
.st-modal{background:#fff;border-radius:24px;box-shadow:var(--shadow-3);padding:24px;max-width:460px;width:100%;border:1.5px solid var(--cream-300);font-size:13px;max-height:92vh;overflow-y:auto}
.st-modal label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;margin-bottom:4px}
.st-modal input,.st-modal select{width:100%;min-height:42px;border-radius:12px;border:1.5px solid var(--cream-300);padding:0 12px;background:#fff;font-size:12px}
.st-modal .row{margin-bottom:12px}
@media(max-width:1024px){.st .kpi{grid-template-columns:repeat(2,1fr)}}
</style>

<div class="st">
    <p class="desc">Kelola hak akses pengguna internal dan status otentikasi login.</p>

    <?php if (!empty($flash)): ?>
        <div class="notif <?= $flash['sukses'] ? 'notif--ok' : 'notif--err' ?>"><?= htmlspecialchars($flash['pesan']) ?></div>
    <?php endif; ?>

    <div class="bar">
        <form method="GET" action="index.php" class="search">
            <input type="hidden" name="page" value="kelola-akun">
            <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Cari nama / username / email...">
        </form>
        <button type="button" class="btn" onclick="bukaStaff()">+ Tambah Akun Staff</button>
        <span class="count">Terdaftar <b><?= count($daftarStaff) ?></b> akun</span>
    </div>

    <div class="kpi">
        <div><span>Total Pengguna</span><b><?= count($daftarStaff) ?></b></div>
        <div><span style="color:var(--red-600)">Admin</span><b style="color:var(--red-600)"><?= $hitung('admin') ?></b></div>
        <div><span style="color:var(--yellow-600)">Kasir</span><b style="color:var(--yellow-600)"><?= $hitung('kasir') ?></b></div>
        <div><span style="color:var(--info)">Pelayan / Manajer</span><b style="color:var(--info)"><?= $hitung('pelayan') + $hitung('manajer') ?></b></div>
    </div>

    <div class="wrap">
        <table>
            <thead><tr>
                <th>Staff &amp; ID</th><th>Username</th><th>Email</th><th>Role</th><th>Status</th><th style="text-align:center">Aksi</th>
            </tr></thead>
            <tbody>
            <?php if (!$daftarStaff): ?>
                <tr><td colspan="6" class="kosong">Tidak ada data staff yang sesuai.</td></tr>
            <?php else: foreach ($daftarStaff as $s):
                $diblokir = (int)($s['akun_diblokir'] ?? 0) === 1 || $s['status_akun'] === 'Diblokir';
                $aktif    = $s['status_akun'] === 'Aktif' && !$diblokir;
                $saya     = (int)$s['id_user'] === (int)$idSaya;
            ?>
                <tr>
                    <td><b><?= htmlspecialchars($s['nama']) ?></b><?= $saya ? ' <span class="pill p-info">Anda</span>' : '' ?><br><small style="color:var(--text-muted)">ID: #USR-<?= (int)$s['id_user'] ?></small></td>
                    <td><b style="color:var(--red-600)">@<?= htmlspecialchars($s['username']) ?></b></td>
                    <td><?= htmlspecialchars($s['email']) ?></td>
                    <td><span class="pill p-info"><?= htmlspecialchars($s['role']) ?></span></td>
                    <td>
                        <?php if ($diblokir): ?><span class="pill p-bad">DIBLOKIR</span>
                        <?php elseif ($aktif): ?><span class="pill p-ok">AKTIF</span>
                        <?php else: ?><span class="pill p-wait">NONAKTIF</span><?php endif; ?>
                    </td>
                    <td><div class="aksi">
                        <button type="button" class="btn btn--sm btn--info js-ubah-staff"
                            data-id="<?= (int)$s['id_user'] ?>" data-nama="<?= htmlspecialchars($s['nama'], ENT_QUOTES) ?>"
                            data-username="<?= htmlspecialchars($s['username'], ENT_QUOTES) ?>" data-email="<?= htmlspecialchars($s['email'], ENT_QUOTES) ?>"
                            data-role="<?= htmlspecialchars($s['role'], ENT_QUOTES) ?>" data-saya="<?= $saya ? '1' : '0' ?>">Ubah</button>

                        <?php if ($diblokir): ?>
                            <form method="POST" action="index.php?page=staff-buka-blokir" onsubmit="return confirm('Buka blokir akun ini?')">
                                <input type="hidden" name="id_user" value="<?= (int)$s['id_user'] ?>">
                                <button class="btn btn--sm btn--ok" type="submit">Buka Blokir</button>
                            </form>
                        <?php elseif (!$saya): ?>
                            <form method="POST" action="index.php?page=staff-status" onsubmit="return confirm('Ubah status akun ini?')">
                                <input type="hidden" name="id_user" value="<?= (int)$s['id_user'] ?>">
                                <button class="btn btn--sm btn--ghost" type="submit"><?= $aktif ? 'Nonaktifkan' : 'Aktifkan' ?></button>
                            </form>
                        <?php endif; ?>

                        <form method="POST" action="index.php?page=staff-reset-password" onsubmit="return confirm('Reset password akun ini? Password acak baru akan dibuat.')">
                            <input type="hidden" name="id_user" value="<?= (int)$s['id_user'] ?>">
                            <button class="btn btn--sm" type="submit">Reset Password</button>
                        </form>
                    </div></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="st-overlay" id="stModal" onclick="if(event.target===this) tutupStaff()">
    <div class="st-modal">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
            <h3 class="disp" id="stJudul" style="color:var(--red-600);font-size:20px">TAMBAH AKUN STAFF</h3>
            <button type="button" onclick="tutupStaff()" style="border:0;background:none;font-size:18px;cursor:pointer;color:var(--text-muted)">✕</button>
        </div>
        <form method="POST" action="index.php?page=staff-simpan" id="stForm" style="margin-top:12px">
            <input type="hidden" name="id_user" id="st_id">
            <div class="row"><label>Nama Lengkap *</label><input type="text" name="nama" id="st_nama" required></div>
            <div class="row"><label>Username *</label><input type="text" name="username" id="st_username" required></div>
            <div class="row"><label>Email *</label><input type="email" name="email" id="st_email" required></div>
            <div class="row"><label>Role *</label>
                <select name="role" id="st_role">
                    <?php foreach (['Kasir','Pelayan','Manajer','Admin'] as $r): ?><option value="<?= $r ?>"><?= $r ?></option><?php endforeach; ?>
                </select>
                <small id="stRoleNote" style="display:none;color:var(--text-muted)">Role akun Anda sendiri tidak dapat diubah.</small>
            </div>
            <div class="row" id="stPwRow"><label>Kata Sandi Awal * (min. 8 karakter)</label><input type="password" name="password" id="st_password" minlength="8" autocomplete="new-password"></div>
            <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:8px">
                <button type="button" class="btn btn--ghost" onclick="tutupStaff()">Batal</button>
                <button type="submit" class="btn">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    const m = document.getElementById('stModal');
    const $ = id => document.getElementById(id);
    window.bukaStaff = function () {
        $('stForm').reset(); $('st_id').value = '';
        $('stJudul').textContent = 'TAMBAH AKUN STAFF';
        $('stPwRow').style.display = ''; $('st_password').required = true;
        $('st_role').disabled = false; $('stRoleNote').style.display = 'none';
        m.classList.add('on');
    };
    window.tutupStaff = () => m.classList.remove('on');
    document.querySelectorAll('.js-ubah-staff').forEach(b => b.addEventListener('click', function () {
        $('stForm').reset();
        $('st_id').value = this.dataset.id; $('st_nama').value = this.dataset.nama;
        $('st_username').value = this.dataset.username; $('st_email').value = this.dataset.email;
        $('st_role').value = this.dataset.role;
        $('stJudul').textContent = 'UBAH DATA STAFF';
        $('stPwRow').style.display = 'none'; $('st_password').required = false;
        const saya = this.dataset.saya === '1';
        $('st_role').disabled = saya; $('stRoleNote').style.display = saya ? 'block' : 'none';
        m.classList.add('on');
    }));
    document.addEventListener('keydown', e => { if (e.key === 'Escape') tutupStaff(); });
    // select yang disabled tidak terkirim; controller memakai role lama untuk akun sendiri
    $('stForm').addEventListener('submit', () => { $('st_role').disabled = false; });
})();
</script>