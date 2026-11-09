<?php
// Ambil data user aktif dari PHP Session (Fallback default jika diakses tanpa login)
$namaUser = $_SESSION['user']['nama'] ?? 'Budi Santoso';
$roleUser = $_SESSION['user']['role'] ?? 'Admin'; // Pilihan role: Admin, Manajer, Kasir, Pelayan
$initials = strtoupper(substr($namaUser, 0, 2));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Staff | Maky Chicken</title>
    
    <!-- Import Font Resmi Titan One & Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Titan+One&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Hubungkan ke CSS Utama Proyek -->
    <link rel="stylesheet" href="css/maky.css">

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

    /* Layout Sidebar & Main Area */
    .mc-app { display: grid; grid-template-columns: 248px 1fr; min-height: 100vh; }
    .side { background: var(--red-900); color: var(--cream-50); padding: var(--sp-4); position: sticky; top: 0; height: 100vh; overflow: auto; display: flex; flex-direction: column; gap: var(--sp-2); }
    .brand { display: flex; gap: var(--sp-3); align-items: center; padding-bottom: var(--sp-4); border-bottom: 1px solid rgba(255,250,240,.15); margin-bottom: var(--sp-2); }
    .logo { width: 44px; height: 44px; border-radius: 50%; background: var(--accent); color: var(--red-900); display: grid; place-items: center; flex: none; font-weight: 700; }
    .brand b { font-family: var(--font-display); font-weight: 400; font-size: 18px; letter-spacing: .04em; display: block; line-height: 1.1; color: #fff; }
    .brand small { color: var(--accent); font-weight: 700; letter-spacing: .12em; font-size: 10px; text-transform: uppercase; }
    .sidesearch { width: 100%; height: 44px; border-radius: 999px; border: 1.5px solid rgba(255,250,240,.2); background: rgba(255,250,240,.06); padding: 0 var(--sp-4); color: var(--cream-50); }
    .sidesearch::placeholder { color: rgba(255,250,240,.6); }
    .grp { font-size: 11px; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: rgba(255,250,240,.55); margin: var(--sp-4) var(--sp-2) 0; }
    .nav { display: flex; align-items: center; gap: var(--sp-3); min-height: 44px; padding: var(--sp-2) var(--sp-4); border-radius: 999px; border: 0; background: none; color: var(--cream-50); font-weight: 600; font-size: 14px; text-align: left; cursor: pointer; width: 100%; text-decoration: none; }
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
    .toast { position: fixed; right: var(--sp-4); bottom: var(--sp-4); background: var(--ink-900); color: var(--cream-50); border-radius: 16px; padding: var(--sp-3) var(--sp-5); box-shadow: var(--shadow-3); font-weight: 500; opacity: 0; transform: translateY(12px); transition: .2s; pointer-events: none; z-index: 60; }
    .toast.on { opacity: 1; transform: none; }

    @media(max-width: 860px) {
        .mc-app { grid-template-columns: 1fr; }
        .side { position: static; height: auto; flex-direction: row; flex-wrap: nowrap; overflow-x: auto; align-items: center; }
        .brand { border: 0; padding: 0; margin: 0; } .brand div { display: none; } .grp, .sidesearch { display: none; }
        .nav { width: auto; white-space: nowrap; }
        .main { padding: var(--sp-4); } .hero { flex-direction: column; align-items: flex-start; }
    }
    </style>
</head>
<body>
<div class="mc-app">
    <!-- SIDEBAR NAVIGASI STAFF -->
    <aside class="side" aria-label="Navigasi staff">
        <div class="brand">
            <span class="logo">MC</span>
            <div>
                <b>MAKY CHICKEN</b>
                <small>Sistem Restoran</small>
            </div>
        </div>
        
        <input id="navSearch" class="sidesearch" type="search" placeholder="Cari menu... Ctrl /" aria-label="Cari menu navigasi">
        
        <nav id="nav" style="display:contents"></nav>
    </aside>

    <!-- AREA KONTEN UTAMA -->
    <main class="main">
        <header class="top">
            <div>
                <h1 id="pgTitle">DASHBOARD</h1>
                <p id="pgSub">Portal operasional &amp; kontrol staff restoran</p>
            </div>
            
            <div class="userbox">
                <!-- PROFILE USER STATIS DARI SESSION LOGIN -->
                <div class="pill-user">
                    <span class="av"><?= htmlspecialchars($initials) ?></span>
                    <div>
                        <b><?= htmlspecialchars($namaUser) ?></b>
                        <small id="roleLbl">Staff Operasional <?= htmlspecialchars($roleUser) ?></small>
                    </div>
                </div>
                
                <button class="mc-btn mc-btn--outline" data-act="logout">LOG OUT</button>
            </div>
        </header>

        <!-- DASHBOARD OVERVIEW CONTENT -->
        <div id="views">
            <section class="hero">
                <div>
                    <span class="tag"><?= date('l, d F Y') ?></span>
                    <h2>HALO, <?= strtoupper(htmlspecialchars($namaUser)) ?></h2>
                    <p>Selamat datang kembali di Dashboard Staff Maky Chicken. Kelola pesanan, reservasi, laporan penjualan, dan layanan restoran dalam satu tempat.</p>
                </div>
                <div class="art">
                    <svg class="i" viewBox="0 0 24 24" style="width:72px;height:72px;stroke:var(--primary);fill:none;stroke-width:1.75;"><path d="M6 13h12a2 2 0 0 1 0 4H6a2 2 0 0 1 0-4zM8 13V9a4 4 0 0 1 8 0v4"/></svg>
                </div>
            </section>

            <section class="mc-card mb-4">
                <div class="sec-title">
                    <h3>AKSES CEPAT OPERASIONAL</h3>
                    <span class="pilltag">PINTASAN UTAMA</span>
                </div>
                <div class="quick">
                    <button class="q" data-go="pos">
                        <span class="ico" style="background:var(--red-100);color:var(--primary)">
                            <svg class="i" viewBox="0 0 24 24"><path d="M6 2h12v20l-3-2-3 2-3-2-3 2zM9 7h6M9 11h6"/></svg>
                        </span>
                        <b>Antrean POS / Kasir Baru</b>
                        <span class="d">Proses order & bayar</span>
                    </button>
                    <button class="q" data-go="laporan-penjualan">
                        <span class="ico" style="background:var(--green-100);color:var(--success)">
                            <svg class="i" viewBox="0 0 24 24"><path d="M3 6h18v12H3zM12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/></svg>
                        </span>
                        <b>Input Jumlah Kas Fisik</b>
                        <span class="d">Rekonsiliasi kas</span>
                    </button>
                    <button class="q" data-go="laporan-penjualan">
                        <span class="ico" style="background:var(--blue-100);color:var(--info)">
                            <svg class="i" viewBox="0 0 24 24"><path d="M12 3v12M7 11l5 5 5-5M4 21h16"/></svg>
                        </span>
                        <b>Export Laporan Penjualan (.XLSX)</b>
                        <span class="d">Unduh laporan penjualan</span>
                    </button>
                </div>
            </section>

            <div class="stats">
                <div class="mc-card stat">
                    <span class="label">PESANAN MASUK HARI INI ● Realtime Sync</span>
                    <div class="n">42 Pesanan</div>
                    <small>34 Selesai · 8 Sedang Diproses Dapur</small>
                </div>
                <div class="mc-card stat">
                    <span class="label">PENDAPATAN HARI INI ● Omset Bersih</span>
                    <div class="n">Rp3.850.000</div>
                    <small>Rp2.600.000 QRIS · Rp1.250.000 Tunai</small>
                </div>
                <div class="mc-card mc-card--accent stat">
                    <span class="label" style="color:var(--ink-900)">RESERVASI CONFIRMED ★ Hari Ini</span>
                    <div class="n">8 Reservasi</div>
                    <small>Lantai 1 & Lantai 2 · Semua DP Terverifikasi</small>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Modal Konfirmasi -->
<div class="overlay" id="ov">
    <div class="modal" role="dialog" aria-modal="true">
        <h3 id="mT" class="disp"></h3>
        <p id="mP"></p>
        <div class="d-flex justify-content-between mt-4">
            <button class="mc-btn mc-btn--ghost" id="mNo">Batal</button>
            <button class="mc-btn mc-btn--danger" id="mYes">Ya</button>
        </div>
    </div>
</div>

<!-- Toast Notifikasi -->
<div class="toast" id="toast" role="status"></div>

<script>
/* ===== Icon Toolkit ===== */
const P = {
    home: 'M3 11l9-8 9 8v9a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z',
    pos: 'M6 2h12v20l-3-2-3 2-3-2-3 2zM9 7h6M9 11h6',
    chart: 'M4 20V10M10 20V4M16 20v-8M22 20H2',
    cal: 'M5 4h14a1 1 0 0 1 1 1v15H4V5a1 1 0 0 1 1-1zM4 9h16M8 2v4M16 2v4',
    leg: 'M15 4a5 5 0 0 1-3 8l-6 7a2 2 0 0 1-3-3l7-6a5 5 0 0 1 5-6z',
    users: 'M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM2 21v-1a6 6 0 0 1 12 0v1M17 4a4 4 0 0 1 0 7M22 21v-1a5 5 0 0 0-4-5',
    msg: 'M21 12a8 8 0 0 1-11.5 7.2L3 21l1.8-5.5A8 8 0 1 1 21 12z'
};
const ic = n => `<svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="${P[n]||P.home}"/></svg>`;
const $ = s => document.querySelector(s);

let tT;
function toast(m) {
    const t = $('#toast');
    t.textContent = m;
    t.classList.add('on');
    clearTimeout(tT);
    tT = setTimeout(() => t.classList.remove('on'), 3000);
}

function confirmBox(title, text, yes, fn) {
    $('#mT').textContent = title;
    $('#mP').textContent = text;
    $('#mYes').textContent = yes;
    $('#ov').classList.add('on');
    $('#mNo').focus();
    $('#mYes').onclick = () => { $('#ov').classList.remove('on'); fn(); };
    $('#mNo').onclick = () => $('#ov').classList.remove('on');
}

/* ===== STRUKTUR NAVIGASI SIDEBAR SESUAI PILIHAN RUTE ===== */
const NAV = [
    // 1. OVERVIEW
    { id: 'internal-dashboard', t: 'Dashboard', ic: 'home', r: 'AMKP', g: 'OVERVIEW' },
    { id: 'pos', t: 'Antrean POS Kasir', ic: 'pos', r: 'KA', g: 'OVERVIEW' },
    
    // 2. MANAJEMEN OPERASIONAL
    { id: 'laporan-penjualan', t: 'Laporan Penjualan', ic: 'chart', r: 'AM', g: 'MANAJEMEN OPERASIONAL' },
    { id: 'reservasi', t: 'Manajemen Reservasi', ic: 'cal', r: 'A', g: 'MANAJEMEN OPERASIONAL' },
    { id: 'kelola-menu', t: 'Kelola Menu', ic: 'leg', r: 'KA', g: 'MANAJEMEN OPERASIONAL' },
    
    // 3. PENGATURAN & LAINNYA
    { id: 'kelola-akun', t: 'Kelola Akun Staff', ic: 'users', r: 'A', g: 'PENGATURAN & LAINNYA' },
    { id: 'laporan-kendala', t: 'Komplain & Feedback', ic: 'msg', r: 'AM', g: 'PENGATURAN & LAINNYA' }
];

const CURRENT_ROLE = '<?= htmlspecialchars($roleUser) ?>';
let cur = 'internal-dashboard';
const can = n => n.r.includes(CURRENT_ROLE[0]);

function renderNav() {
    const q = $('#navSearch').value.toLowerCase();
    let h = '', g = '';
    NAV.filter(n => can(n) && n.t.toLowerCase().includes(q)).forEach(n => {
        if (n.g !== g) {
            g = n.g;
            h += `<div class="grp">${g}</div>`;
        }
        h += `<button class="nav" data-go="${n.id}" ${n.id === cur ? 'aria-current="page"' : ''}>${ic(n.ic)}<span>${n.t}</span></button>`;
    });
    $('#nav').innerHTML = h;
}

// PENGALIHAN BERPINDAH HALAMAN LANGSUNG KE ROUTER INDEX.PHP
function go(id) {
    window.location.href = 'index.php?page=' + id;
}

document.addEventListener('click', e => {
    const t = e.target.closest('button');
    if (!t) return;
    const d = t.dataset;
    if ('go' in d) go(d.go);
    else if (d.act === 'logout') {
        confirmBox('Keluar dari sistem?', 'Anda akan kembali ke halaman utama.', 'Log Out', () => {
            window.location.href = 'index.php?page=logout';
        });
    }
});

$('#navSearch').addEventListener('input', renderNav);
renderNav();
</script>
</body>
</html>