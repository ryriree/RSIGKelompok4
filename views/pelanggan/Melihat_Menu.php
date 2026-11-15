<?php
// Katalog menu pelanggan. Data dari MenuController::menuPelanggan: $menus
if (!function_exists('eMl')) {
    function eMl($v) { return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8'); }
}
$menus = $menus ?? [];
$kategoriList = array_values(array_unique(array_filter(array_column($menus, 'kategori'))));
$jmlTersedia = 0;
foreach ($menus as $m) { if ((int)$m['status_tersedia'] === 1) $jmlTersedia++; }

// Ikon tab (path SVG sederhana)
$ikonPath = [
    'semua'      => 'M4 6h16M4 12h16M4 18h10',
    'paket ayam' => 'M15 4a5 5 0 0 1-3 8l-6 7a2 2 0 0 1-3-3l7-6a5 5 0 0 1 5-6z',
    'minuman'    => 'M5 8h12v6a5 5 0 0 1-5 5H10a5 5 0 0 1-5-5zM17 10h2a2 2 0 0 1 0 4h-2',
    'dessert'    => 'M12 3l2.7 5.6 6.1.9-4.4 4.3 1 6.1L12 17l-5.4 2.9 1-6.1L3.2 9.5l6.1-.9z',
    'default'    => 'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18zM12 8v8M8 12h8',
];
$ikon = function ($nama) use ($ikonPath) {
    $p = $ikonPath[mb_strtolower($nama, 'UTF-8')] ?? $ikonPath['default'];
    return '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="' . $p . '"/></svg>';
};
$emoji = function ($kat) { return $kat === 'Minuman' ? '🥤' : '🍗'; };
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Maky Chicken | Menu</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Titan+One&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{
  --red:#B3131B;--red-d:#8E0F18;--red-soft:#FBE0DD;--cream:#FFF3DC;--cream-50:#FFFAF0;--line:#F2D49A;
  --yellow:#FFB21E;--blue:#1F5FAE;--blue-d:#123C73;--green:#1E8E4E;--ink:#2A1010;--muted:#6B4A45;
  --disp:'Titan One','Arial Black',sans-serif;--body:'Poppins',system-ui,sans-serif;
}
*{box-sizing:border-box;margin:0}
body{font-family:var(--body);background:var(--cream);color:var(--ink);line-height:1.5}
button{font:inherit;color:inherit}
:focus-visible{outline:3px solid var(--blue);outline-offset:2px}
.wrap{max-width:1180px;margin:0 auto;padding:0 20px}

/* Hero */
.hero{background:var(--red);color:#fff;padding:28px 0 96px}
.top{display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap;padding-bottom:22px;border-bottom:1px solid rgba(255,255,255,.15)}
.brand{font-family:var(--disp);font-size:30px;letter-spacing:.02em;text-transform:uppercase;color:#fff;text-decoration:none}
.pills{display:flex;gap:10px;flex-wrap:wrap}
.pill{background:var(--red-d);color:#fff;text-decoration:none;font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;border-radius:999px;padding:11px 20px}
.pill:hover{background:#6f0a11}
.pill.on{background:var(--yellow);color:var(--ink)}
.badge-top{display:inline-block;background:var(--yellow);color:var(--ink);font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:999px;padding:5px 14px;margin-top:34px}
.hero-row{display:flex;justify-content:space-between;align-items:flex-end;gap:20px;flex-wrap:wrap}
.hero h1{font-family:var(--disp);font-weight:400;text-transform:uppercase;font-size:clamp(40px,7vw,76px);line-height:1.02;margin-top:14px}
.avail{background:#fff;color:#0d6b37;font-size:13px;font-weight:600;border-radius:999px;padding:9px 20px;display:inline-flex;align-items:center;gap:9px;margin-bottom:10px}
.avail::before{content:"";width:9px;height:9px;border-radius:50%;background:var(--green)}

/* Tabs */
.tabs{background:#fff;border-radius:22px;box-shadow:0 8px 24px rgba(142,15,24,.12);padding:7px;display:flex;gap:4px;margin-top:-52px;position:relative;overflow-x:auto}
.tab{flex:1 1 0;min-width:104px;border:0;background:none;border-radius:16px;padding:14px 10px 12px;display:flex;flex-direction:column;align-items:center;gap:8px;font-size:11px;font-weight:700;letter-spacing:.09em;text-transform:uppercase;cursor:pointer;color:var(--ink)}
.tab svg{color:var(--red)}
.tab:hover{background:var(--cream-50)}
.tab[aria-selected=true]{background:var(--red);color:#fff}
.tab[aria-selected=true] svg{color:#fff}

/* Kartu menu */
.grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:24px;padding:40px 0 64px}
.card{background:#fff;border-radius:28px;padding:16px;box-shadow:0 4px 16px rgba(142,15,24,.08);display:flex;flex-direction:column}
.card[hidden]{display:none}
.card .ph{position:relative;aspect-ratio:1.4;border-radius:18px;overflow:hidden;background:#FBE6BE;display:grid;place-items:center;font-size:56px}
.card .ph img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
.tag{position:absolute;top:12px;left:12px;background:#fff;color:var(--red);font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;border-radius:999px;padding:4px 14px;border:1px solid var(--red-soft);z-index:1}
.card h3{font-size:17px;font-weight:700;line-height:1.3;margin:16px 0 6px}
.card p{font-size:13px;color:var(--muted);display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;min-height:2.9em}
.card .bt{display:flex;justify-content:space-between;align-items:center;gap:8px;margin-top:auto;padding-top:14px;border-top:1px solid #F7E9CB}
.card .bd{display:flex;flex-direction:column;flex:1}
.price{font-family:var(--disp);color:var(--red);font-size:20px}
.btn-d{border:0;background:var(--blue);color:#fff;border-radius:999px;padding:11px 20px;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;cursor:pointer}
.btn-d:hover{background:var(--blue-d)}
.card.habis{background:#FFFBF2;box-shadow:none}
.card.habis .ph,.card.habis h3,.card.habis p{opacity:.55}
.card.habis .ph{filter:grayscale(.5)}
.card.habis .tag{color:#8a7a74;border-color:#eee}
.card.habis .price{color:#c9bfb9}
.habis-tag{display:inline-flex;align-items:center;gap:6px;background:#F1EEEA;color:#8a7a74;border:1px solid #E5E0DA;border-radius:999px;padding:8px 16px;font-size:12px;font-weight:600}
.habis-tag::before{content:"";width:6px;height:6px;border-radius:50%;background:#b5aca6}
.kosong{grid-column:1/-1;text-align:center;padding:48px 16px;color:var(--muted)}

/* Modal */
.ov{position:fixed;inset:0;background:rgba(94,10,16,.5);display:none;place-items:center;padding:16px;z-index:50}
.ov.on{display:grid}
.modal{background:#fff;border-radius:28px;max-width:440px;width:100%;padding:20px;box-shadow:0 18px 44px rgba(94,10,16,.3)}
.modal .ph{aspect-ratio:1.6;border-radius:18px;overflow:hidden;background:#FBE6BE;display:grid;place-items:center;font-size:64px;position:relative}
.modal .ph img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
.m-kat{display:inline-block;margin-top:14px;background:var(--red-soft);color:var(--red);font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;border-radius:999px;padding:3px 12px}
.modal h3{font-size:22px;margin:6px 0}
.modal p{font-size:14px;color:var(--muted)}
.m-foot{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-top:18px;padding-top:14px;border-top:1px solid #F7E9CB}
.btn-p{background:var(--red);color:#fff;text-decoration:none;border-radius:999px;padding:12px 22px;font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase}
.btn-p:hover{background:var(--red-d)}
.btn-x{border:0;background:none;font-size:13px;font-weight:700;color:var(--muted);cursor:pointer;padding:8px}

footer{border-top:1px solid #F2E3C2;padding:20px 0 28px;font-size:12px;color:var(--muted)}
footer .wrap{display:flex;justify-content:space-between;gap:16px;flex-wrap:wrap}
footer b{font-family:var(--disp);font-weight:400;color:var(--red);text-transform:uppercase}
footer nav{display:flex;gap:24px}
footer a{color:var(--muted);text-decoration:none}
</style>
</head>
<body>

<header class="hero"><div class="wrap">
    <div class="top">
        <a class="brand" href="index.php?page=home">Maky Chicken</a>
        <nav class="pills" aria-label="Navigasi">
            <a class="pill" href="index.php?page=home">Home</a>
            <a class="pill on" href="index.php?page=menu-pelanggan">Melihat Menu</a>
            <a class="pill" href="index.php?page=pesan">Pesan via QR</a>
            <a class="pill" href="index.php?page=form-kendala">Contact Us</a>
        </nav>
    </div>
    <span class="badge-top">Menu Restoran</span>
    <div class="hero-row">
        <h1>Pilih rasa<br>yang kamu mau</h1>
        <span class="avail"><?= $jmlTersedia ?> menu tersedia hari ini</span>
    </div>
</div></header>

<main class="wrap">
    <div class="tabs" role="tablist" id="tabs">
        <button class="tab" role="tab" aria-selected="true" data-kat="all"><?= $ikon('semua') ?>Semua</button>
        <?php foreach ($kategoriList as $k): ?>
            <button class="tab" role="tab" aria-selected="false" data-kat="<?= eMl($k) ?>"><?= $ikon($k) ?><?= eMl($k) ?></button>
        <?php endforeach; ?>
    </div>

    <div class="grid" id="grid">
    <?php if (!$menus): ?>
        <div class="kosong">Menu belum tersedia.</div>
    <?php else: foreach ($menus as $m):
        $ada = (int)$m['status_tersedia'] === 1;
        $rpTeks = 'Rp' . number_format((float)$m['harga'], 0, ',', '.');
    ?>
        <article class="card <?= $ada ? '' : 'habis' ?>" data-kat="<?= eMl($m['kategori']) ?>"
                 data-nama="<?= eMl($m['nama_menu']) ?>" data-desk="<?= eMl($m['deskripsi']) ?>"
                 data-harga="<?= eMl($rpTeks) ?>" data-gambar="<?= eMl($m['gambar'] ?? '') ?>"
                 data-emoji="<?= $emoji($m['kategori']) ?>">
            <div class="ph">
                <span class="tag"><?= eMl($m['kategori']) ?></span>
                <?php if (!empty($m['gambar'])): ?>
                    <img src="<?= eMl($m['gambar']) ?>" alt="<?= eMl($m['nama_menu']) ?>" loading="lazy">
                <?php else: ?><?= $emoji($m['kategori']) ?><?php endif; ?>
            </div>
            <div class="bd">
                <h3><?= eMl($m['nama_menu']) ?></h3>
                <p><?= eMl($m['deskripsi']) ?></p>
                <div class="bt">
                    <span class="price"><?= eMl($rpTeks) ?></span>
                    <?php if ($ada): ?>
                        <button type="button" class="btn-d" data-detail>Lihat Detail</button>
                    <?php else: ?>
                        <span class="habis-tag">Habis</span>
                    <?php endif; ?>
                </div>
            </div>
        </article>
    <?php endforeach; endif; ?>
        <div class="kosong" id="kosong" hidden>Tidak ada menu pada kategori ini.</div>
    </div>
</main>

<footer><div class="wrap">
    <div>© <?= date('Y') ?> <b>Maky Chicken</b> - Cita Rasa Juara Selera Kita</div>
    <nav><a href="index.php?page=form-kendala">Bantuan</a><a href="#">Syarat &amp; Ketentuan</a><a href="#">Kebijakan Privasi</a></nav>
</div></footer>

<div class="ov" id="ov"><div class="modal" role="dialog" aria-modal="true" aria-labelledby="mNama">
    <div class="ph" id="mPh"></div>
    <span class="m-kat" id="mKat"></span>
    <h3 id="mNama"></h3>
    <p id="mDesk"></p>
    <div class="m-foot">
        <div class="price" id="mHarga"></div>
        <div>
            <button type="button" class="btn-x" id="mTutup">Tutup</button>
            <a class="btn-p" href="index.php?page=pesan">Pesan Sekarang</a>
        </div>
    </div>
</div></div>

<script>
(function () {
    // Filter kategori (di browser)
    const tabs = document.querySelectorAll('#tabs .tab');
    tabs.forEach(t => t.addEventListener('click', function () {
        tabs.forEach(x => x.setAttribute('aria-selected', x === this));
        const k = this.dataset.kat;
        let ada = 0;
        document.querySelectorAll('#grid .card').forEach(c => {
            c.hidden = !(k === 'all' || c.dataset.kat === k);
            if (!c.hidden) ada++;
        });
        document.getElementById('kosong').hidden = ada > 0;
    }));

    // Modal detail
    const ov = document.getElementById('ov');
    function tutup() { ov.classList.remove('on'); }
    document.getElementById('grid').addEventListener('click', function (e) {
        const b = e.target.closest('[data-detail]');
        if (!b) return;
        const c = b.closest('.card'), d = c.dataset;
        document.getElementById('mNama').textContent = d.nama;
        document.getElementById('mKat').textContent = d.kat;
        document.getElementById('mDesk').textContent = d.desk;
        document.getElementById('mHarga').textContent = d.harga;
        const ph = document.getElementById('mPh');
        ph.textContent = '';
        if (d.gambar) {
            const img = document.createElement('img');
            img.src = d.gambar; img.alt = d.nama;
            ph.appendChild(img);
        } else { ph.textContent = d.emoji; }
        ov.classList.add('on');
    });
    document.getElementById('mTutup').addEventListener('click', tutup);
    ov.addEventListener('click', e => { if (e.target === ov) tutup(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') tutup(); });
})();
</script>
</body>
</html>