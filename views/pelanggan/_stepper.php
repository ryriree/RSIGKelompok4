<?php
// Partial: stepper + gaya bersama reservasi. Pakai: $langkah (1-4, atau 5 = semua selesai)
if (!function_exists('eRv')) {
    function eRv($v) { return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8'); }
}
$rvJudul = ['Jadwal & Tamu', 'Pilih Lantai', 'Paket Menu', 'Bayar DP & e-Voucher'];
?>
<style>
.rv { padding: 28px 0 56px; }
.rv .steps { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 24px; }
.rv .stp { background: #fff; border: 1.5px solid var(--border); border-radius: 16px; padding: 10px 14px; display: flex; gap: 10px; align-items: center; font-size: 12px; font-weight: 700; color: var(--text-muted); }
.rv .stp b { width: 28px; height: 28px; border-radius: 50%; background: var(--cream-100); color: var(--primary); display: grid; place-items: center; font-family: var(--font-display); font-weight: 400; flex: none; }
.rv .stp.on { background: var(--primary); border-color: var(--primary); color: #fff; }
.rv .stp.ok { background: var(--green-100); border-color: var(--green-100); color: var(--success); }
.rv .cols { display: grid; grid-template-columns: 4fr 8fr; gap: 24px; align-items: start; }
.rv .card { background: #fff; border: 1.5px solid var(--border); border-radius: 24px; box-shadow: var(--shadow-1); padding: 24px; }
.rv .card h2 { font-size: 22px; color: var(--primary); margin-bottom: 14px; }
.rv .card h3 { font-size: 17px; color: var(--primary); margin-bottom: 10px; }
.rv .muted { color: var(--text-muted); font-size: 13px; }
.rv .notice { padding: 12px 16px; margin-bottom: 16px; border-radius: 14px; font-size: 13px; font-weight: 600; background: var(--red-100); color: var(--red-700); }
.rv .notice.ok { background: var(--green-100); color: var(--success); }
.rv .sum p { display: flex; justify-content: space-between; gap: 10px; font-size: 13px; padding: 7px 0; border-bottom: 1.5px dashed var(--border); }
.rv .sum p span:first-child { color: var(--text-muted); }
.rv .sum p.big { border: 0; font-weight: 700; font-size: 15px; }
.rv .field { margin-bottom: 16px; }
.rv .field > label { display: block; font-weight: 600; font-size: 14px; margin-bottom: 4px; }
.rv .in { width: 100%; min-height: 48px; border: 1.5px solid var(--border); border-radius: 16px; padding: 0 16px; background: #fff; }
.rv .row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.rv .chips { display: flex; gap: 8px; flex-wrap: wrap; }
.rv .slot { min-height: 44px; padding: 0 16px; border-radius: 999px; border: 1.5px solid var(--border); background: #fff; font-weight: 600; font-size: 13px; cursor: pointer; }
.rv .slot.on { background: var(--primary); border-color: var(--primary); color: #fff; }
.rv .slot:disabled { opacity: .4; text-decoration: line-through; cursor: not-allowed; }
.rv .picks { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 16px; }
.rv .pick { display: block; cursor: pointer; position: relative; }
.rv .pick input { position: absolute; opacity: 0; pointer-events: none; }
.rv .pick .box { display: block; height: 100%; border: 2px solid var(--border); border-radius: 20px; padding: 18px; background: #fff; }
.rv .pick input:checked + .box { border-color: var(--primary); background: var(--red-100); }
.rv .pick input:focus-visible + .box { outline: 3px solid var(--info); outline-offset: 2px; }
.rv .actions { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-top: 22px; padding-top: 18px; border-top: 1.5px solid var(--border); flex-wrap: wrap; }
.rv .harga { font-family: var(--font-display); color: var(--primary); }
@media (max-width: 860px) { .rv .cols { grid-template-columns: 1fr; } .rv .steps { grid-template-columns: 1fr 1fr; } .rv .row2 { grid-template-columns: 1fr; } }
</style>
<div class="steps">
    <?php foreach ($rvJudul as $i => $j): $n = $i + 1; $cls = $n < $langkah ? 'ok' : ($n == $langkah ? 'on' : ''); ?>
        <div class="stp <?= $cls ?>"><b><?= $n ?></b><span><?= eRv($j) ?></span></div>
    <?php endforeach; ?>
</div>