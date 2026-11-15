<?php
// Layout bersama semua halaman staff. Konten halaman masuk lewat $content.
$role    = strtolower($_SESSION['role'] ?? '');
$namaUser = $_SESSION['nama'] ?? 'Staff';
$initials = strtoupper(substr($namaUser, 0, 2));
$aktif   = $_GET['page'] ?? '';

$semuaMenu = require BASE_PATH . '/config/menu.php';
$menus = array_filter($semuaMenu, fn($m) => $m['roles'] === ['*'] || in_array($role, $m['roles']));

// Kelompokkan per group & cari judul halaman aktif
$grup = [];
$judul = 'Dashboard';
foreach ($menus as $m) {
    $grup[$m['group']][] = $m;
    if ($m['id'] === $aktif) $judul = $m['label'];
}

$ICON = [
    'home'  => 'M3 11l9-8 9 8v9a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z',
    'pos'   => 'M6 2h12v20l-3-2-3 2-3-2-3 2zM9 7h6M9 11h6',
    'chart' => 'M4 20V10M10 20V4M16 20v-8M22 20H2',
    'cal'   => 'M5 4h14a1 1 0 0 1 1 1v15H4V5a1 1 0 0 1 1-1zM4 9h16M8 2v4M16 2v4',
    'leg'   => 'M15 4a5 5 0 0 1-3 8l-6 7a2 2 0 0 1-3-3l7-6a5 5 0 0 1 5-6z',
    'users' => 'M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM2 21v-1a6 6 0 0 1 12 0v1M17 4a4 4 0 0 1 0 7M22 21v-1a5 5 0 0 0-4-5',
    'msg'   => 'M21 12a8 8 0 0 1-11.5 7.2L3 21l1.8-5.5A8 8 0 1 1 21 12z',
    'list'  => 'M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01',
];
function ikon($ICON, $n) {
    return '<svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="' . ($ICON[$n] ?? $ICON['home']) . '"/></svg>';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($judul) ?> | Maky Chicken</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Titan+One&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
    :root {
        --red-100: #FBE0DD; --red-600: #B3131B; --red-700: #8E0F18; --red-900: #5E0A10;
        --cream-50: #FFFAF0; --cream-100: #FFF3DC; --cream-200: #FBE6BE; --cream-300: #F2D49A;
        --yellow-100: #FFF0C2; --yellow-500: #FFB21E; --yellow-600: #F28C0F;
        --blue-100: #DCEBFA; --blue-600: #1F5FAE; --green-600: #1E8E4E; --green-100: #D9F2E3;
        --ink-900: #2A1010; --ink-600: #6B4A45;
        --primary: var(--red-600); --primary-hover: var(--red-700); --bg: var(--cream-100); --surface: #fff;
        --border: var(--cream-300); --text: var(--ink-900); --text-muted: var(--ink-600);
        --accent: var(--yellow-500); --info: var(--blue-600); --success: var(--green-600);
        --sp-1: 4px; --sp-2: 8px; --sp-3: 12px; --sp-4: 16px; --sp-5: 24px; --sp-6: 32px; --sp-7: 48px;
        --shadow-1: 0 2px 8px rgba(142,15,24,.08); --shadow-2: 0 10px 28px rgba(142,15,24,.14); --shadow-3: 0 18px 44px rgba(94,10,16,.22);
        --font-display: 'Titan One', 'Arial Black', sans-serif; --font-body: 'Poppins', system-ui, sans-serif;
    }
    * { box-sizing: border-box; margin: 0; }
    html, body { height: 100%; }
    body { font-family: var(--font-body); background: var(--cream-50); color: var(--text); line-height: 1.6; font-variant-numeric: tabular-nums; }
    button, input, select { font: inherit; color: inherit; }
    :focus-visible { outline: 3px solid var(--info); outline-offset: 2px; }
    svg.i { width: 1.15em; height: 1.15em; fill: none; stroke: currentColor; stroke-width: 1.75; stroke-linecap: round; stroke-linejoin: round; flex: none; }
    h1, h2, h3, .disp { font-family: var(--font-display); font-weight: 400; text-transform: uppercase; line-height: 1.02; text-wrap: balance; }

    .mc-app { display: grid; grid-template-columns: 248px 1fr; min-height: 100vh; }
    .side { background: var(--red-900); color: var(--cream-50); padding: var(--sp-4); position: sticky; top: 0; height: 100vh; overflow: auto; display: flex; flex-direction: column; gap: var(--sp-2); }
    .brand { display: flex; gap: var(--sp-3); align-items: center; padding-bottom: var(--sp-4); border-bottom: 1px solid rgba(255,250,240,.15); margin-bottom: var(--sp-2); }
    .logo { width: 44px; height: 44px; border-radius: 50%; background: var(--accent); color: var(--red-900); display: grid; place-items: center; flex: none; font-weight: 700; }
    .brand b { font-family: var(--font-display); font-weight: 400; font-size: 18px; letter-spacing: .04em; display: block; line-height: 1.1; color: #fff; }
    .brand small { color: var(--accent); font-weight: 700; letter-spacing: .12em; font-size: 10px; text-transform: uppercase; }
    .sidesearch { width: 100%; height: 44px; border-radius: 999px; border: 1.5px solid rgba(255,250,240,.2); background: rgba(255,250,240,.06); padding: 0 var(--sp-4); color: var(--cream-50); }
    .sidesearch::placeholder { color: rgba(255,250,240,.6); }
    #nav { display: flex; flex-direction: column; gap: var(--sp-2); }
    .grp { font-size: 11px; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: rgba(255,250,240,.55); margin: var(--sp-4) var(--sp-2) var(--sp-2); }
    .nav { display: flex; align-items: center; gap: var(--sp-3); min-height: 44px; padding: var(--sp-2) var(--sp-4); border-radius: 999px; border: 0; background: none; color: var(--cream-50); font-weight: 600; font-size: 14px; text-align: left; cursor: pointer; width: 100%; text-decoration: none; margin-bottom: 4px; }
    .nav:hover { background: rgba(255,250,240,.1); }
    .nav[aria-current=page] { background: var(--accent); color: var(--ink-900); box-shadow: var(--shadow-2); }

    .main { padding: var(--sp-5) var(--sp-6) var(--sp-7); min-width: 0; }
    .top { display: flex; justify-content: space-between; align-items: flex-start; gap: var(--sp-4); flex-wrap: wrap; margin-bottom: var(--sp-5); }
    .top h1 { font-size: clamp(30px,4vw,40px); color: var(--red-600); }
    .top p { font-size: 12px; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: var(--text-muted); margin-top: var(--sp-1); }
    .userbox { display: flex; gap: var(--sp-3); align-items: center; flex-wrap: wrap; }
    .pill-user { display: flex; gap: var(--sp-3); align-items: center; background: #fff; border: 1.5px solid var(--border); border-radius: 999px; padding: 6px 20px 6px 6px; }
    .av { width: 36px; height: 36px; border-radius: 50%; background: var(--primary); color: var(--cream-50); display: grid; place-items: center; font-weight: 700; font-size: 12px; }
    .pill-user b { display: block; font-size: 14px; line-height: 1.2; } .pill-user small { font-size: 12px; color: var(--text-muted); }

    .mc-btn { display: inline-flex; align-items: center; justify-content: center; gap: var(--sp-2); min-height: 44px; padding: 0 var(--sp-5); border-radius: 999px; border: 1.5px solid transparent; font-weight: 700; font-size: 13px; letter-spacing: .08em; text-transform: uppercase; cursor: pointer; transition: transform .15s, background .15s; background: var(--primary); color: var(--cream-50); text-decoration: none; }
    .mc-btn:hover { transform: translateY(-1px); background: var(--primary-hover); } .mc-btn:active { transform: none; }
    .mc-btn--outline { background: transparent; color: var(--primary); border-color: var(--primary); } .mc-btn--outline:hover { background: var(--red-100); }
    .mc-btn--ghost { background: transparent; color: var(--text); } .mc-btn--ghost:hover { background: var(--cream-200); }
    .mc-btn--danger { background: var(--red-700); color: #fff; }

    .mc-card { background: #fff; border: 1.5px solid var(--border); border-radius: 24px; box-shadow: var(--shadow-1); padding: var(--sp-5); }
    .mc-card--accent { background: var(--accent); border-color: var(--accent); }
    .hero { background: linear-gradient(135deg, var(--red-600), var(--red-700)); color: var(--cream-50); border-radius: 24px; padding: var(--sp-6); display: flex; justify-content: space-between; align-items: center; gap: var(--sp-5); box-shadow: var(--shadow-2); margin-bottom: var(--sp-5); }
    .hero h2 { font-size: clamp(34px,5vw,56px); color: #fff; margin: var(--sp-3) 0; }
    .hero p { max-width: 60ch; color: var(--cream-50); }
    .tag { display: inline-flex; gap: var(--sp-2); align-items: center; border-radius: 999px; padding: 4px 14px; font-size: 13px; font-weight: 600; border: 1.5px solid rgba(255,250,240,.35); color: var(--yellow-500); }
    .hero .art { width: 150px; height: 150px; border-radius: 24px; background: var(--cream-100); display: grid; place-items: center; color: var(--primary); flex: none; border: 4px solid var(--cream-300); overflow: hidden; }

    .sec-title { display: flex; justify-content: space-between; align-items: center; gap: var(--sp-3); margin-bottom: var(--sp-4); flex-wrap: wrap; }
    .sec-title h3 { font-size: 22px; color: var(--ink-900); }
    .pilltag { background: var(--red-100); color: var(--red-700); border-radius: 999px; font-size: 11px; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; padding: 4px 14px; }
    .quick { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--sp-4); }
    .q { background: var(--cream-50); border: 1.5px solid var(--border); border-radius: 16px; padding: var(--sp-4); text-align: left; cursor: pointer; display: flex; flex-direction: column; gap: var(--sp-1); min-height: 44px; text-decoration: none; color: inherit; }
    .q:hover { box-shadow: var(--shadow-2); transform: translateY(-1px); }
    .q .ico { width: 40px; height: 40px; border-radius: 12px; display: grid; place-items: center; margin-bottom: var(--sp-2); }
    .q b { font-size: 15px; line-height: 1.3; } .q span.d { font-size: 12px; color: var(--text-muted); }

    .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: var(--sp-4); margin-top: var(--sp-5); }
    .stat .n { font-family: var(--font-display); color: var(--primary); font-size: clamp(28px,3.4vw,40px); line-height: 1.1; margin: var(--sp-2) 0; }
    .mc-card--accent .n { color: var(--ink-900); }
    .stat small { color: var(--text-muted); font-size: 13px; display: block; }
    .mc-card--accent small { color: var(--ink-900); }

    .overlay { position: fixed; inset: 0; background: rgba(94,10,16,.45); display: none; place-items: center; padding: var(--sp-4); z-index: 50; }
    .overlay.on { display: grid; }
    .modal { background: #fff; border-radius: 24px; box-shadow: var(--shadow-3); padding: var(--sp-5); max-width: 440px; width: 100%; }
    .modal h3 { font-size: 24px; margin-bottom: var(--sp-2); color: var(--primary); }
    .modal .aksi { display: flex; justify-content: space-between; margin-top: var(--sp-5); }
    .toast { position: fixed; right: var(--sp-4); bottom: var(--sp-4); background: var(--ink-900); color: var(--cream-50); border-radius: 16px; padding: var(--sp-3) var(--sp-5); box-shadow: var(--shadow-3); font-weight: 500; opacity: 0; transform: translateY(12px); transition: .2s; pointer-events: none; z-index: 60; }
    .toast.on { opacity: 1; transform: none; }

    @media(max-width: 860px) {
        .mc-app { grid-template-columns: 1fr; }
        .side { position: static; height: auto; flex-direction: row; flex-wrap: nowrap; overflow-x: auto; align-items: center; }
        .brand { border: 0; padding: 0; margin: 0; } .brand div { display: none; } .grp, .sidesearch { display: none; }
        #nav { flex-direction: row; }
        .nav { width: auto; white-space: nowrap; margin: 0; }
        .main { padding: var(--sp-4); } .hero { flex-direction: column; align-items: flex-start; }
    }
    </style>
</head>
<body>
<div class="mc-app">

    <!-- SIDEBAR (dibuat otomatis dari config/menu.php sesuai role) -->
    <aside class="side" aria-label="Navigasi staff">
        <div class="brand">
            <span class="logo">MC</span>
            <div>
                <b>MAKY CHICKEN</b>
                <small>Sistem Restoran</small>
            </div>
        </div>

        <input id="navSearch" class="sidesearch" type="search" placeholder="Cari menu... Ctrl /" aria-label="Cari menu navigasi">

        <nav id="nav">
            <?php foreach ($grup as $namaGrup => $items): ?>
                <div class="navgrp">
                    <div class="grp"><?= htmlspecialchars($namaGrup) ?></div>
                    <?php foreach ($items as $m): ?>
                        <a class="nav" href="<?= url($m['id']) ?>" <?= $m['id'] === $aktif ? 'aria-current="page"' : '' ?>>
                            <?= ikon($ICON, $m['icon']) ?><span><?= htmlspecialchars($m['label']) ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </nav>
    </aside>

    <!-- AREA KONTEN -->
    <main class="main">
        <header class="top">
            <div>
                <h1><?= htmlspecialchars($judul) ?></h1>
                <p>Portal operasional &amp; kontrol staff restoran</p>
            </div>

            <div class="userbox">
                <div class="pill-user">
                    <span class="av"><?= htmlspecialchars($initials) ?></span>
                    <div>
                        <b><?= htmlspecialchars($namaUser) ?></b>
                        <small>Staff Operasional <?= htmlspecialchars(ucfirst($role)) ?></small>
                    </div>
                </div>
                <button class="mc-btn mc-btn--outline" data-act="logout">LOG OUT</button>
            </div>
        </header>

        <!-- ISI HALAMAN DARI VIEW MASING-MASING FITUR -->
        <?= $content ?>
    </main>
</div>

<!-- Modal Konfirmasi Logout -->
<div class="overlay" id="ov">
    <div class="modal" role="dialog" aria-modal="true">
        <h3 id="mT" class="disp"></h3>
        <p id="mP"></p>
        <div class="aksi">
            <button class="mc-btn mc-btn--ghost" id="mNo">Batal</button>
            <button class="mc-btn mc-btn--danger" id="mYes">Ya</button>
        </div>
    </div>
</div>

<div class="toast" id="toast" role="status"></div>

<script>
const $ = s => document.querySelector(s);

// Toast bisa dipanggil dari view mana pun: toast('Pesan')
let tT;
function toast(m) {
    const t = $('#toast');
    t.textContent = m;
    t.classList.add('on');
    clearTimeout(tT);
    tT = setTimeout(() => t.classList.remove('on'), 3000);
}

// Kotak konfirmasi bisa dipanggil dari view mana pun: confirmBox(judul, teks, labelYa, fungsi)
function confirmBox(title, text, yes, fn) {
    $('#mT').textContent = title;
    $('#mP').textContent = text;
    $('#mYes').textContent = yes;
    $('#ov').classList.add('on');
    $('#mNo').focus();
    $('#mYes').onclick = () => { $('#ov').classList.remove('on'); fn(); };
    $('#mNo').onclick = () => $('#ov').classList.remove('on');
}

document.addEventListener('click', e => {
    const t = e.target.closest('button');
    if (!t) return;
    if (t.dataset.act === 'logout') {
        confirmBox('Keluar dari sistem?', 'Anda akan kembali ke halaman login.', 'Log Out', () => {
            window.location.href = 'index.php?page=logout';
        });
    }
});

// Pencarian menu di sidebar
$('#navSearch').addEventListener('input', e => {
    const q = e.target.value.toLowerCase();
    document.querySelectorAll('#nav .navgrp').forEach(g => {
        let ada = false;
        g.querySelectorAll('.nav').forEach(a => {
            const cocok = a.textContent.toLowerCase().includes(q);
            a.style.display = cocok ? '' : 'none';
            if (cocok) ada = true;
        });
        g.style.display = ada ? '' : 'none';
    });
});
document.addEventListener('keydown', e => {
    if (e.ctrlKey && e.key === '/') { e.preventDefault(); $('#navSearch').focus(); }
});
</script>
</body>
</html>