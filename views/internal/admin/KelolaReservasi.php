<?php
// Tampilan saja. Data dikirim oleh KelolaReservasi (controller):
// $daftarReservasi, $q (kata kunci), $flash ['pesan','sukses']
$daftarReservasi = $daftarReservasi ?? [];
$q = $q ?? '';

$totalAll       = count($daftarReservasi);
$totalConfirmed = count(array_filter($daftarReservasi, fn($r) => $r['status'] === 'Confirmed'));
$totalPending   = count(array_filter($daftarReservasi, fn($r) => $r['status'] === 'Pending'));
$totalBatal     = count(array_filter($daftarReservasi, fn($r) => $r['status'] === 'Dibatalkan'));
?>
<style>
.rs { font-size: 13px; }
.rs .desc { font-size: 12px; color: var(--text-muted); margin-bottom: 16px; }
.rs .bar { display: flex; gap: 12px; align-items: center; flex-wrap: wrap; margin-bottom: 20px; }
.rs .search { display: flex; flex: 1; min-width: 240px; max-width: 420px; }
.rs .search input { width: 100%; height: 42px; border-radius: 999px; border: 1.5px solid var(--cream-300); background: #fff; padding: 0 18px; font-size: 12px; outline: none; }
.rs .search input:focus { border-color: var(--red-600); }
.rs .count { font-size: 12px; color: var(--text-muted); margin-left: auto; }
.rs .btn { display: inline-flex; align-items: center; justify-content: center; min-height: 42px; padding: 0 20px; border-radius: 999px; border: 0; background: var(--red-600); color: #fff; font-weight: 700; font-size: 11px; letter-spacing: .06em; text-transform: uppercase; cursor: pointer; }
.rs .btn:hover { background: var(--red-700); }
.rs .btn--ghost { background: transparent; color: var(--text); border: 1.5px solid var(--border); }
.rs .btn--sm { min-height: 30px; padding: 0 12px; font-size: 10px; }
.rs .btn--ok { background: var(--success); }

.rs .notif { padding: 12px 16px; border-radius: 14px; font-weight: 600; font-size: 12px; margin-bottom: 16px; }
.rs .notif--ok  { background: var(--green-100); color: var(--success); border: 1px solid #b7e4c7; }
.rs .notif--err { background: var(--red-100); color: var(--red-700); border: 1px solid #f1b5b0; }

.rs .kpi { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 20px; }
.rs .kpi div { background: #fff; border: 1.5px solid var(--cream-300); border-radius: 18px; padding: 16px 18px; box-shadow: var(--shadow-1); }
.rs .kpi span { font-size: 10px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--text-muted); display: block; }
.rs .kpi b { font-family: var(--font-display); font-weight: 400; font-size: 28px; display: block; margin-top: 4px; }

.rs .wrap { background: #fff; border: 1.5px solid var(--cream-300); border-radius: 20px; overflow-x: auto; box-shadow: var(--shadow-1); }
.rs table { width: 100%; border-collapse: collapse; font-size: 12px; }
.rs th { background: var(--red-600); color: #fff; font-size: 10px; letter-spacing: .08em; text-transform: uppercase; padding: 14px 14px; text-align: left; white-space: nowrap; }
.rs td { padding: 12px 14px; border-bottom: 1px solid var(--cream-200); vertical-align: middle; }
.rs tr:nth-child(even) td { background: var(--cream-50); }
.rs .kode { font-weight: 700; color: var(--red-600); font-family: monospace; white-space: nowrap; }
.rs .pill { display: inline-flex; padding: 2px 10px; border-radius: 999px; font-size: 10px; font-weight: 700; white-space: nowrap; }
.rs .p-ok   { background: var(--green-100); color: var(--success); }
.rs .p-wait { background: var(--yellow-100); color: var(--yellow-600); }
.rs .p-info { background: var(--blue-100); color: var(--info); }
.rs .p-bad  { background: var(--red-100); color: var(--red-700); }
.rs .p-gray { background: #eee; color: #555; }
.rs .aksi { display: flex; gap: 6px; justify-content: center; }
.rs .aksi form { margin: 0; }
.rs .kosong { text-align: center; padding: 32px; color: var(--text-muted); font-style: italic; }

.rs-overlay { position: fixed; inset: 0; background: rgba(94,10,16,.45); display: flex; align-items: center; justify-content: center; padding: 16px; z-index: 1000; backdrop-filter: blur(4px); opacity: 0; pointer-events: none; transition: opacity .25s; }
.rs-overlay.on { opacity: 1; pointer-events: auto; }
.rs-modal { background: #fff; border-radius: 24px; box-shadow: var(--shadow-3); padding: 24px; max-width: 520px; width: 100%; border: 1.5px solid var(--cream-300); font-size: 13px; max-height: 92vh; overflow-y: auto; }
.rs-modal label { display: block; font-size: 10px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; }
.rs-modal input, .rs-modal select { width: 100%; min-height: 42px; border-radius: 12px; border: 1.5px solid var(--cream-300); padding: 0 12px; background: #fff; font-size: 12px; }
.rs-modal .row { display: grid; gap: 12px; margin-bottom: 12px; }
.rs-modal .r2 { grid-template-columns: 1fr 1fr; }
.rs-modal .r3 { grid-template-columns: 1fr 1fr 1fr; }

@media (max-width: 1024px) { .rs .kpi { grid-template-columns: repeat(2, 1fr); } }
</style>

<div class="rs">
    <p class="desc">Kelola reservasi pelanggan, entri pesanan walk-in/offline, serta konfirmasi status DP.</p>

    <?php if (!empty($flash)): ?>
        <div class="notif <?= $flash['sukses'] ? 'notif--ok' : 'notif--err' ?>"><?= htmlspecialchars($flash['pesan']) ?></div>
    <?php endif; ?>

    <div class="bar">
        <form method="GET" action="index.php" class="search">
            <input type="hidden" name="page" value="reservasi">
            <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Cari kode booking / nama pemesan...">
        </form>
        <button type="button" class="btn" onclick="bukaModalRs()">+ Reservasi Offline</button>
        <span class="count">Menampilkan <b><?= $totalAll ?></b> data reservasi</span>
    </div>

    <div class="kpi">
        <div><span>Total Reservasi</span><b><?= $totalAll ?></b></div>
        <div><span style="color:var(--success)">Terkonfirmasi</span><b style="color:var(--success)"><?= $totalConfirmed ?></b></div>
        <div><span style="color:var(--yellow-600)">Menunggu Konfirmasi</span><b style="color:var(--yellow-600)"><?= $totalPending ?></b></div>
        <div><span style="color:var(--red-600)">Dibatalkan</span><b style="color:var(--red-600)"><?= $totalBatal ?></b></div>
    </div>

    <div class="wrap">
        <table>
            <thead>
                <tr>
                    <th>Kode Booking</th><th>Nama Pemesan</th><th>Kontak (WA)</th><th>Jadwal</th>
                    <th style="text-align:center">Lantai</th><th style="text-align:center">Tamu</th>
                    <th>Status DP</th><th>Status Booking</th><th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($daftarReservasi)): ?>
                <tr><td colspan="9" class="kosong">Belum ada data reservasi yang tercatat.</td></tr>
            <?php else: foreach ($daftarReservasi as $r):
                $batal = $r['status'] === 'Dibatalkan';
                $lunas = $r['status_dp'] === 'Lunas';
            ?>
                <tr>
                    <td class="kode"><?= htmlspecialchars($r['kode_booking']) ?></td>
                    <td><b><?= htmlspecialchars($r['nama_pemesan']) ?></b></td>
                    <td><?= htmlspecialchars($r['kontak_pemesanan']) ?></td>
                    <td><b><?= htmlspecialchars($r['tanggal']) ?></b><br><small style="color:var(--text-muted)"><?= htmlspecialchars($r['slot_waktu']) ?></small></td>
                    <td style="text-align:center"><b>Lt. <?= htmlspecialchars($r['lantai']) ?></b></td>
                    <td style="text-align:center"><b><?= (int)$r['jumlah_orang'] ?> Pax</b></td>
                    <td><span class="pill <?= $lunas ? 'p-ok' : 'p-wait' ?>"><?= $lunas ? 'LUNAS' : 'BELUM DIBAYAR' ?></span></td>
                    <td>
                        <?php if ($r['status'] === 'Confirmed'): ?><span class="pill p-info">Confirmed</span>
                        <?php elseif ($batal): ?><span class="pill p-bad">Dibatalkan</span>
                        <?php else: ?><span class="pill p-gray"><?= htmlspecialchars($r['status']) ?></span><?php endif; ?>
                    </td>
                    <td>
                        <div class="aksi">
                            <?php if (!$lunas && !$batal): ?>
                                <form method="POST" action="index.php?page=reservasi-status-dp">
                                    <input type="hidden" name="id_reservasi" value="<?= (int)$r['id_reservasi'] ?>">
                                    <input type="hidden" name="status_dp" value="Lunas">
                                    <button class="btn btn--sm btn--ok" type="submit">Lunaskan DP</button>
                                </form>
                            <?php endif; ?>
                            <?php if (!$batal): ?>
                                <form method="POST" action="index.php?page=reservasi-batal" onsubmit="return confirm('Yakin membatalkan reservasi ini?')">
                                    <input type="hidden" name="id_reservasi" value="<?= (int)$r['id_reservasi'] ?>">
                                    <button class="btn btn--sm" type="submit">Batalkan</button>
                                </form>
                            <?php else: ?>
                                <span style="color:var(--text-muted)">-</span>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL RESERVASI OFFLINE -->
<div class="rs-overlay" id="rsModal" onclick="if(event.target===this) tutupModalRs()">
    <div class="rs-modal">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
            <h3 class="disp" style="color:var(--red-600); font-size:20px;">INPUT RESERVASI OFFLINE</h3>
            <button type="button" onclick="tutupModalRs()" style="border:0; background:none; font-size:18px; cursor:pointer; color:var(--text-muted);">✕</button>
        </div>
        <p style="font-size:12px; color:var(--text-muted); margin-bottom:16px;">Entri reservasi langsung di outlet (walk-in atau telepon).</p>

        <form method="POST" action="index.php?page=reservasi-tambah">
            <div class="row">
                <div><label>Nama Pemesan *</label><input type="text" name="nama_pemesan" required placeholder="Nama pemesan"></div>
            </div>
            <div class="row r2">
                <div><label>No. WhatsApp *</label><input type="tel" name="kontak_pemesanan" required placeholder="08xxxxxxxx"></div>
                <div><label>Tanggal *</label><input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required></div>
            </div>
            <div class="row r3">
                <div><label>Slot Waktu</label>
                    <select name="slot_waktu">
                        <?php foreach (['11:30 WIB','13:00 WIB','14:00 WIB','17:00 WIB','18:30 WIB','19:00 WIB','19:45 WIB','20:30 WIB'] as $s): ?>
                            <option value="<?= $s ?>" <?= $s === '18:30 WIB' ? 'selected' : '' ?>><?= $s ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div><label>Lantai</label><select name="lantai"><option value="1">Lantai 1</option><option value="2">Lantai 2</option></select></div>
                <div><label>Jumlah Tamu</label><input type="number" name="jumlah_orang" value="4" min="1"></div>
            </div>
            <div class="row">
                <div><label>Status DP (uang muka Rp50.000)</label>
                    <select name="status_dp">
                        <option value="Lunas">LUNAS (bayar tunai/QRIS)</option>
                        <option value="Belum Dibayar">BELUM DIBAYAR (bayar nanti)</option>
                    </select>
                </div>
            </div>
            <div style="display:flex; justify-content:flex-end; gap:8px; margin-top:8px;">
                <button type="button" class="btn btn--ghost" onclick="tutupModalRs()">Batal</button>
                <button type="submit" class="btn">Simpan Reservasi</button>
            </div>
        </form>
    </div>
</div>

<script>
function bukaModalRs()  { document.getElementById('rsModal').classList.add('on'); }
function tutupModalRs() { document.getElementById('rsModal').classList.remove('on'); }
document.addEventListener('keydown', e => { if (e.key === 'Escape') tutupModalRs(); });
</script>