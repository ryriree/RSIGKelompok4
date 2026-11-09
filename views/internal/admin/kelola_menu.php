<?php
// UC-09 - View Kelola Ketersediaan Menu
// Data yang diharapkan dari controller: $daftarMenu dan opsional $pesanMenu.
if (!function_exists('eMenu')) {
    function eMenu($value) {
        return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
    }
}
$namaUser = $_SESSION['user']['nama'] ?? 'Reyhan Nasrul';
$roleUser = $_SESSION['user']['role'] ?? 'Staff Operasional Kasir';
$initials = strtoupper(substr($namaUser, 0, 1));
$daftarMenu = $daftarMenu ?? [];
$totalMenu = count($daftarMenu);
$totalTersedia = 0;
$kategoriAktif = [];
$semuaKategori = [];
foreach ($daftarMenu as $item) {
    $kategori = trim((string)($item['kategori'] ?? ''));
    if ($kategori !== '') {
        // Semua kategori tetap tersedia untuk filter dan pilihan form.
        $semuaKategori[$kategori] = true;
        // Kategori dihitung aktif jika minimal ada satu menu yang tersedia.
        if ((int)($item['status_tersedia'] ?? 0) === 1) {
            $kategoriAktif[$kategori] = true;
        }
    }
    if ((int)($item['status_tersedia'] ?? 0) === 1) $totalTersedia++;
}
$totalHabis = $totalMenu - $totalTersedia;
$persentase = $totalMenu ? round($totalTersedia / $totalMenu * 100, 1) : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kelola Ketersediaan Menu | Maky Chicken</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Titan+One&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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

:root{--red-900:#5E0A10;--red-700:#8E0F18;--red-600:#B3131B;--cream-50:#FFFAF0;--cream-100:#FFF3DC;--cream-200:#FBE6BE;--cream-300:#F2D49A;--yellow-500:#FFB21E;--blue-100:#DCEBFA;--blue-700:#123C73;--green-600:#1E8E4E;--green-100:#D8F2E2;--ink-900:#2A1010;--ink-600:#6B4A45;--font-display:'Titan One','Arial Black',sans-serif;--font-body:'Poppins',system-ui,sans-serif}
*{box-sizing:border-box}html,body{margin:0;min-height:100%;font-family:var(--font-body);background:var(--cream-50);color:var(--ink-900);font-size:13px}button,input,select,textarea{font:inherit;color:inherit}
.mc-app{display:grid;grid-template-columns:248px minmax(0,1fr);min-height:100vh}
.side{background:var(--red-900);color:var(--cream-50);padding:20px 16px;position:sticky;top:0;height:100vh;overflow-y:auto;display:flex;flex-direction:column}
.brand{display:flex;gap:12px;align-items:center;padding:8px 8px 18px;border-bottom:1px solid rgba(255,250,240,.15);margin-bottom:12px}
.logo{width:40px;height:40px;border-radius:50%;background:var(--red-700);color:white;display:grid;place-items:center;flex:none;font-size:18px}
.brand b{font-family:var(--font-display);font-size:17px;color:#fff;display:block;line-height:1.1;font-weight:400}.brand small{color:var(--yellow-500);font-weight:700;font-size:9px;letter-spacing:.12em;text-transform:uppercase}
.sidesearch-wrap{position:relative;margin:4px 0 10px}.sidesearch{width:100%;height:38px;border-radius:999px;border:1.5px solid rgba(255,250,240,.2);background:rgba(255,250,240,.06);padding:0 14px 0 36px;color:#fff;font-size:12px}.sidesearch::placeholder{color:rgba(255,250,240,.55)}.search-ico{position:absolute;left:13px;top:8px;color:rgba(255,250,240,.65)}
.grp{font-size:10px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:rgba(255,250,240,.48);margin:15px 8px 6px}
.nav{display:flex;align-items:center;gap:11px;min-height:40px;padding:7px 13px;border-radius:999px;color:rgba(255,250,240,.9);font-weight:600;font-size:13px;text-decoration:none;margin:2px 0;transition:.15s}.nav:hover{background:rgba(255,250,240,.1)}.nav[aria-current=page]{background:var(--yellow-500);color:var(--ink-900);font-weight:700;box-shadow:0 5px 14px #0002}
.side-bottom{margin-top:auto;padding-top:16px;border-top:1px solid rgba(255,250,240,.15);display:flex;align-items:center;gap:10px}.avatar-small{width:34px;height:34px;border-radius:50%;background:var(--yellow-500);color:var(--ink-900);display:grid;place-items:center;font-weight:800;flex:none}.side-bottom b{display:block;font-size:12px;color:#fff}.side-bottom small{display:block;color:rgba(255,250,240,.65);font-size:10px}
.main{min-width:0;padding:28px 32px 44px;background:var(--cream-50)}
.topbar{display:flex;justify-content:space-between;align-items:flex-start;gap:16px;flex-wrap:wrap;margin-bottom:26px}.topbar h1{font-family:var(--font-display);font-weight:400;text-transform:uppercase;font-size:28px;line-height:1.05;color:var(--red-900);margin:0}.topbar p{margin:5px 0 0;color:var(--ink-600);font-size:12px}.top-actions{display:flex;align-items:center;gap:12px}.user-pill{display:flex;align-items:center;gap:10px;background:#fff;border:1px solid var(--cream-300);border-radius:999px;padding:6px 16px 6px 7px}.user-avatar{width:34px;height:34px;border-radius:50%;background:var(--red-600);color:#fff;display:grid;place-items:center;font-weight:700}.user-pill b{display:block;font-size:12px}.user-pill small{display:block;color:var(--ink-600);font-size:10px}.logout{padding:9px 17px;border:1px solid var(--red-600);border-radius:999px;color:var(--red-600);text-decoration:none;font-size:10px;font-weight:800;letter-spacing:.05em}
.kpi-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px;margin-bottom:28px}.kpi{background:#fff;border:1px solid var(--cream-300);border-radius:24px;padding:19px 20px;min-height:145px;box-shadow:0 3px 9px #5e0a100d}.kpi.accent{background:var(--yellow-500);border-color:var(--yellow-500)}.kpi-label{display:flex;justify-content:space-between;align-items:center;font-size:10px;font-weight:800;letter-spacing:.06em;text-transform:uppercase;color:var(--ink-600)}.kpi.accent .kpi-label{color:var(--ink-900)}.kpi-icon{width:34px;height:34px;border-radius:50%;display:grid;place-items:center;background:var(--cream-100);color:var(--red-600);font-size:16px}.kpi-value{font-family:var(--font-display);font-size:42px;line-height:1;margin:12px 0 5px;color:var(--red-600)}.kpi-value.green{color:var(--green-600)}.kpi-value.dark{color:var(--ink-900)}.kpi-note{font-size:10px;color:var(--ink-600)}.kpi.accent .kpi-note{color:var(--ink-900)}
.toolbar{display:flex;align-items:center;gap:12px;flex-wrap:wrap;padding:17px 18px;background:#fff;border-radius:24px;box-shadow:0 3px 9px #5e0a100d;margin-bottom:28px}.search-box{flex:1 1 220px;position:relative}.search-box input{width:100%;height:42px;border:1px solid var(--cream-300);border-radius:999px;padding:0 16px 0 42px;background:var(--cream-50);outline:none;font-size:12px}.search-box span{position:absolute;left:15px;top:10px;color:var(--ink-600)}.filters{display:flex;gap:6px;flex-wrap:wrap}.filter-btn{border:0;border-radius:999px;background:var(--cream-50);padding:9px 14px;font-size:10px;font-weight:800;cursor:pointer}.filter-btn.active{background:var(--red-600);color:#fff}.btn-primary{border:0;border-radius:999px;background:var(--red-600);color:#fff;padding:11px 20px;font-size:10px;font-weight:800;letter-spacing:.06em;text-transform:uppercase;cursor:pointer;box-shadow:0 4px 10px #8e0f1830}.btn-primary:hover{background:var(--red-700)}.add-btn{margin-left:auto;white-space:nowrap}
.editor{display:none;background:#fff;border:1px solid var(--cream-300);border-radius:20px;padding:20px;margin:-8px 0 24px;box-shadow:0 3px 9px #5e0a100d}.editor.open{display:block}.editor-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px}.editor-head h2{font:400 19px var(--font-display);color:var(--red-600);text-transform:uppercase;margin:0}.editor-grid{display:grid;grid-template-columns:1fr 1fr;gap:13px}.field{display:flex;flex-direction:column;gap:6px}.field label{font-size:11px;font-weight:700}.field input,.field textarea,.field select{width:100%;border:1px solid var(--cream-300);border-radius:12px;padding:10px 12px;font-size:12px;outline:none}.field textarea{min-height:75px;resize:vertical}.field.full{grid-column:1/-1}.available-check{display:flex;align-items:center;gap:8px;margin:14px 0;font-size:12px}.form-actions{display:flex;justify-content:flex-end;gap:9px}.btn-light{border:1px solid var(--cream-300);background:#fff;border-radius:999px;padding:10px 18px;font-size:10px;font-weight:800;cursor:pointer}
.table-card{background:#fff;border-radius:22px;overflow:hidden;box-shadow:0 3px 9px #5e0a100d}.table-head{padding:18px 20px;font:400 17px var(--font-display);color:var(--red-600);text-transform:uppercase}.table-scroll{overflow-x:auto}.menu-table{width:100%;min-width:900px;border-collapse:collapse;font-size:12px}.menu-table th{background:var(--red-600);color:#fff;padding:16px;text-align:left;font-size:10px;letter-spacing:.06em;text-transform:uppercase}.menu-table td{padding:13px 16px;border-bottom:1px solid var(--cream-200);vertical-align:middle}.menu-table tbody tr:nth-child(even){background:var(--cream-50)}.menu-name{font-weight:700;font-size:13px}.menu-desc{display:block;color:var(--ink-600);font-size:10px;max-width:250px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.category{display:inline-block;background:var(--blue-100);color:var(--blue-700);border-radius:999px;padding:5px 11px;font-size:10px;font-weight:800;white-space:nowrap}.price{font-weight:700;white-space:nowrap}.status{display:inline-block;border-radius:999px;padding:5px 11px;font-size:10px;font-weight:800;white-space:nowrap}.status.yes{background:var(--green-100);color:var(--green-600)}.status.no{background:var(--red-100);color:var(--red-700)}.actions{display:flex;gap:7px;align-items:center}.action-btn{border:0;border-radius:999px;padding:8px 12px;font-size:10px;font-weight:700;white-space:nowrap;cursor:pointer}.edit-btn{background:var(--blue-100);color:var(--blue-700)}.toggle-btn{background:var(--cream-200);color:var(--red-700)}.table-foot{padding:15px 18px;color:var(--ink-600);font-size:11px}.notice{padding:12px 16px;margin-bottom:18px;border-radius:12px;background:var(--green-100);color:var(--green-600);font-size:12px}
@media(max-width:1100px){.kpi-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.main{padding:24px}}
@media(max-width:760px){.mc-app{grid-template-columns:1fr}.side{position:relative;height:auto}.main{padding:20px 14px}.kpi-grid{grid-template-columns:1fr 1fr}.editor-grid{grid-template-columns:1fr}.field.full{grid-column:auto}.add-btn{margin-left:0;width:100%}.topbar h1{font-size:23px}}
@media(max-width:430px){.kpi-grid{grid-template-columns:1fr}.top-actions{width:100%;justify-content:space-between}}

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
</style>
</head>
<body>
<div class="mc-app">

<aside class="side" aria-label="Navigasi staff">
    <div class="brand"><span class="logo">MC</span><div><b>MAKY CHICKEN</b><small>Sistem Restoran</small></div></div>
    <input id="navSearch" class="sidesearch" type="search" placeholder="Cari menu... Ctrl /" aria-label="Cari menu navigasi">
    <div class="grp">OVERVIEW</div>
    <a class="nav" href="index.php?page=internal-dashboard"><span>⌂</span> Dashboard</a>
    <a class="nav" href="index.php?page=pos"><span>▤</span> Antrean POS Kasir</a>
    <div class="grp">MANAJEMEN OPERASIONAL</div>
    <a class="nav" href="index.php?page=laporan-penjualan"><span>▥</span> Laporan Penjualan</a>
    <a class="nav" href="index.php?page=reservasi"><span>▦</span> Manajemen Reservasi</a>
    <a class="nav" href="index.php?page=kelola-menu" aria-current="page"><span>⚒</span> Kelola Menu</a>
    <div class="grp">PENGATURAN &amp; LAINNYA</div>
    <a class="nav" href="index.php?page=kelola-akun"><span>♙</span> Kelola Akun Staff</a>
    <a class="nav" href="index.php?page=laporan-kendala" data-active="laporan-kendala"><span>☏</span> Komplain &amp; Feedback</a>
    <div style="margin-top:auto;padding-top:16px;color:rgba(255,250,240,.7);font-size:11px">MAKY CHICKEN · PORTAL STAFF</div>
</aside>


<main class="main">
    <header class="topbar">
        <div><h1>Kelola Ketersediaan Menu</h1><p>Manajemen Katalog Produk, Status Stok, &amp; Pembaruan Menu Restoran</p></div>
        <div class="top-actions">
            <div class="user-pill"><div class="user-avatar"><?= eMenu($initials) ?></div><div><b><?= eMenu($namaUser) ?></b><small><?= eMenu($roleUser) ?></small></div></div>
            <a class="logout" href="index.php?page=logout">⇥ KELUAR</a>
        </div>
    </header>

    <?php if (!empty($pesanMenu)): ?><div class="notice"><?= eMenu($pesanMenu) ?></div><?php endif; ?>

    <section class="kpi-grid">
        <article class="kpi"><div class="kpi-label">Total Menu Terdaftar <span class="kpi-icon">✕</span></div><div class="kpi-value"><?= $totalMenu ?></div><div class="kpi-note">Seluruh katalog menu restoran</div></article>
        <article class="kpi"><div class="kpi-label">Menu Tersedia <span class="kpi-icon">●</span></div><div class="kpi-value green"><?= $totalTersedia ?></div><div class="kpi-note"><?= $persentase ?>% menu siap dipesan</div></article>
        <article class="kpi"><div class="kpi-label">Menu Habis (Sold Out) <span class="kpi-icon">⊖</span></div><div class="kpi-value"><?= $totalHabis ?></div><div class="kpi-note">Perlu pembaruan ketersediaan menu</div></article>
        <article class="kpi accent"><div class="kpi-label">Kategori Aktif <span class="kpi-icon">◆</span></div><div class="kpi-value dark"><?= count($kategoriAktif) ?></div><div class="kpi-note"><?= eMenu(implode(', ', array_keys($kategoriAktif))) ?></div></article>
    </section>

    <section class="toolbar">
        <div class="search-box"><span>⌕</span><input type="search" id="cariMenu" placeholder="Cari nama menu..."></div>
        <div class="filters" id="filterKategori">
            <button type="button" class="filter-btn active" data-kategori="all">Semua (<?= $totalMenu ?>)</button>
            <?php foreach (array_keys($semuaKategori) as $kategori): ?><button type="button" class="filter-btn" data-kategori="<?= eMenu($kategori) ?>"><?= eMenu($kategori) ?></button><?php endforeach; ?>
        </div>
        <button type="button" class="btn-primary add-btn" id="tombolTambah">＋ Tambah Menu Baru</button>
    </section>

    <section class="editor" id="formEditor">
        <div class="editor-head"><h2 id="judulEditor">Tambah Menu Baru</h2><button type="button" class="btn-light" id="tutupEditor">Tutup ✕</button></div>
        <form method="POST" action="index.php?page=simpan-menu" id="formMenu">
            <input type="hidden" name="id_menu" id="id_menu">
            <div class="editor-grid">
                <div class="field"><label for="nama_menu">Nama Menu *</label><input type="text" name="nama_menu" id="nama_menu" maxlength="150" required></div>
                <div class="field"><label for="kategori">Kategori *</label><input type="text" name="kategori" id="kategori" maxlength="100" list="kategoriList" required><datalist id="kategoriList"><?php foreach(array_keys($semuaKategori) as $kat): ?><option value="<?= eMenu($kat) ?>"><?php endforeach; ?></datalist></div>
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
            <thead><tr><th>Foto &amp; Nama Menu</th><th>Kategori</th><th>Harga Satuan</th><th>Status Ketersediaan</th><th>Aksi Pengelolaan</th></tr></thead>
            <tbody id="daftarMenuBody">
            <?php if (!$daftarMenu): ?><tr><td colspan="5" style="text-align:center;padding:30px">Belum ada data menu.</td></tr>
            <?php else: foreach ($daftarMenu as $m): $tersedia = (int)($m['status_tersedia'] ?? 0) === 1; ?>
                <tr class="baris-menu" data-nama="<?= eMenu(mb_strtolower((string)($m['nama_menu'] ?? ''),'UTF-8')) ?>" data-kategori="<?= eMenu($m['kategori'] ?? '') ?>">
                    <td><div class="menu-name"><?= eMenu($m['nama_menu'] ?? '') ?></div><span class="menu-desc"><?= eMenu($m['deskripsi'] ?? 'Menu Maky Chicken') ?></span></td>
                    <td><span class="category"><?= eMenu($m['kategori'] ?? '-') ?></span></td>
                    <td class="price">Rp<?= number_format((float)($m['harga'] ?? 0),0,',','.') ?></td>
                    <td><span class="status <?= $tersedia?'yes':'no' ?>"><?= $tersedia?'● TERSEDIA':'● HABIS' ?></span></td>
                    <td><div class="actions">
                        <button type="button" class="action-btn edit-btn tombol-edit" data-id="<?= (int)$m['id_menu'] ?>" data-nama="<?= eMenu($m['nama_menu'] ?? '') ?>" data-kategori="<?= eMenu($m['kategori'] ?? '') ?>" data-harga="<?= eMenu($m['harga'] ?? 0) ?>" data-deskripsi="<?= eMenu($m['deskripsi'] ?? '') ?>" data-status="<?= $tersedia?'1':'0' ?>">✎ UBAH</button>
                        <form method="POST" action="index.php?page=ubah-ketersediaan" style="margin:0"><input type="hidden" name="id_menu" value="<?= (int)$m['id_menu'] ?>"><input type="hidden" name="status_tersedia" value="<?= $tersedia?'0':'1' ?>"><button type="submit" class="action-btn toggle-btn"><?= $tersedia?'◉ NONAKTIFKAN':'✓ AKTIFKAN' ?></button></form>
                    </div></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table></div>
        <div class="table-foot">Menampilkan <?= $totalMenu ?> menu dalam katalog restoran.</div>
    </section>
</main>
</div>
<script>
(function(){
 const editor=document.getElementById('formEditor'),form=document.getElementById('formMenu');
 function openEditor(){editor.classList.add('open');editor.scrollIntoView({behavior:'smooth',block:'start'});}
 function resetEditor(){form.reset();document.getElementById('id_menu').value='';document.getElementById('judulEditor').textContent='Tambah Menu Baru';document.getElementById('status_tersedia').checked=true;}
 document.getElementById('tombolTambah').addEventListener('click',()=>{resetEditor();openEditor();});
 document.getElementById('tutupEditor').addEventListener('click',()=>editor.classList.remove('open'));
 document.getElementById('batalEditor').addEventListener('click',()=>{resetEditor();editor.classList.remove('open');});
 document.querySelectorAll('.tombol-edit').forEach(btn=>btn.addEventListener('click',function(){
  document.getElementById('id_menu').value=this.dataset.id;document.getElementById('nama_menu').value=this.dataset.nama;document.getElementById('kategori').value=this.dataset.kategori;document.getElementById('harga').value=this.dataset.harga;document.getElementById('deskripsi').value=this.dataset.deskripsi;document.getElementById('status_tersedia').checked=this.dataset.status==='1';document.getElementById('judulEditor').textContent='Ubah Data Menu';openEditor();
 }));
 const search=document.getElementById('cariMenu');let active='all';
 function filter(){const q=search.value.toLocaleLowerCase('id');document.querySelectorAll('.baris-menu').forEach(row=>{row.hidden=!(row.dataset.nama.includes(q)&&(active==='all'||row.dataset.kategori===active));});}
 search.addEventListener('input',filter);
 document.querySelectorAll('.filter-btn').forEach(btn=>btn.addEventListener('click',function(){document.querySelectorAll('.filter-btn').forEach(b=>b.classList.remove('active'));this.classList.add('active');active=this.dataset.kategori;filter();}));
})();
</script>
</body>
</html>
