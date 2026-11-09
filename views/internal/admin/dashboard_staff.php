<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dashboard Staff | Maky Chicken</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Titan+One&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
/* ===== Token (DESIGN.md) ===== */
:root{
  --red-100:#FBE0DD;--red-600:#B3131B;--red-700:#8E0F18;--red-900:#5E0A10;
  --cream-50:#FFFAF0;--cream-100:#FFF3DC;--cream-200:#FBE6BE;--cream-300:#F2D49A;
  --yellow-100:#FFF0C2;--yellow-500:#FFB21E;--yellow-600:#F28C0F;
  --blue-100:#DCEBFA;--blue-600:#1F5FAE;--green-600:#1E8E4E;--green-100:#D9F2E3;
  --ink-900:#2A1010;--ink-600:#6B4A45;
  --primary:var(--red-600);--primary-hover:var(--red-700);--bg:var(--cream-100);--surface:#fff;
  --border:var(--cream-300);--text:var(--ink-900);--text-muted:var(--ink-600);
  --accent:var(--yellow-500);--info:var(--blue-600);--success:var(--green-600);
  --sp-1:4px;--sp-2:8px;--sp-3:12px;--sp-4:16px;--sp-5:24px;--sp-6:32px;--sp-7:48px;
  --shadow-1:0 2px 8px rgba(142,15,24,.08);--shadow-2:0 10px 28px rgba(142,15,24,.14);--shadow-3:0 18px 44px rgba(94,10,16,.22);
  --font-display:'Titan One','Arial Black',sans-serif;--font-body:'Poppins',system-ui,sans-serif;
}
*{box-sizing:border-box;margin:0}
html,body{height:100%}
body{font-family:var(--font-body);background:var(--cream-50);color:var(--text);line-height:1.6;font-variant-numeric:tabular-nums}
button,input,select{font:inherit;color:inherit}
:focus-visible{outline:3px solid var(--info);outline-offset:2px}
svg.i{width:1.15em;height:1.15em;fill:none;stroke:currentColor;stroke-width:1.75;stroke-linecap:round;stroke-linejoin:round;flex:none}
h1,h2,h3,.disp{font-family:var(--font-display);font-weight:400;text-transform:uppercase;line-height:1.02;text-wrap:balance}

/* ===== Layout ===== */
.mc-app{display:grid;grid-template-columns:248px 1fr;min-height:100vh}
.side{background:var(--red-900);color:var(--cream-50);padding:var(--sp-4);position:sticky;top:0;height:100vh;overflow:auto;display:flex;flex-direction:column;gap:var(--sp-2)}
.brand{display:flex;gap:var(--sp-3);align-items:center;padding-bottom:var(--sp-4);border-bottom:1px solid rgba(255,250,240,.15);margin-bottom:var(--sp-2)}
.logo{width:44px;height:44px;border-radius:50%;background:var(--accent);color:var(--red-900);display:grid;place-items:center;flex:none}
.brand b{font-family:var(--font-display);font-weight:400;font-size:18px;letter-spacing:.04em;display:block;line-height:1.1}
.brand small{color:var(--accent);font-weight:700;letter-spacing:.12em;font-size:10px;text-transform:uppercase}
.sidesearch{width:100%;height:44px;border-radius:999px;border:1.5px solid rgba(255,250,240,.2);background:rgba(255,250,240,.06);padding:0 var(--sp-4);color:var(--cream-50)}
.sidesearch::placeholder{color:rgba(255,250,240,.6)}
.grp{font-size:11px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:rgba(255,250,240,.55);margin:var(--sp-4) var(--sp-2) 0}
.nav{display:flex;align-items:center;gap:var(--sp-3);min-height:44px;padding:var(--sp-2) var(--sp-4);border-radius:999px;border:0;background:none;color:var(--cream-50);font-weight:600;font-size:14px;text-align:left;cursor:pointer;width:100%}
.nav:hover{background:rgba(255,250,240,.1)}
.nav[aria-current=page]{background:var(--accent);color:var(--ink-900);box-shadow:var(--shadow-2)}
.main{padding:var(--sp-5) var(--sp-6) var(--sp-7);min-width:0}
.top{display:flex;justify-content:space-between;align-items:flex-start;gap:var(--sp-4);flex-wrap:wrap;margin-bottom:var(--sp-5)}
.top h1{font-size:clamp(30px,4vw,40px)}
.top p{font-size:12px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--text-muted);margin-top:var(--sp-1)}
.userbox{display:flex;gap:var(--sp-3);align-items:center;flex-wrap:wrap}
.pill-user{display:flex;gap:var(--sp-3);align-items:center;background:#fff;border:1.5px solid var(--border);border-radius:999px;padding:6px 20px 6px 6px}
.av{width:36px;height:36px;border-radius:50%;background:var(--primary);color:var(--cream-50);display:grid;place-items:center;font-weight:700;font-size:12px}
.pill-user b{display:block;font-size:14px;line-height:1.2}.pill-user small{font-size:12px;color:var(--text-muted)}
.mc-select,.mc-input{min-height:44px;border-radius:16px;border:1.5px solid var(--border);background:#fff;padding:0 var(--sp-4);width:100%}
.roleSel{width:auto;border-radius:999px;font-weight:600;font-size:14px}

/* ===== Komponen ===== */
.mc-btn{display:inline-flex;align-items:center;justify-content:center;gap:var(--sp-2);min-height:44px;padding:0 var(--sp-5);border-radius:999px;border:1.5px solid transparent;font-weight:700;font-size:13px;letter-spacing:.08em;text-transform:uppercase;cursor:pointer;transition:transform .15s,background .15s;background:var(--primary);color:var(--cream-50)}
.mc-btn:hover{transform:translateY(-1px);background:var(--primary-hover)}.mc-btn:active{transform:none}
.mc-btn:disabled{opacity:.45;cursor:not-allowed;transform:none}
.mc-btn--accent{background:var(--accent);color:var(--ink-900)}.mc-btn--accent:hover{background:var(--yellow-600);color:var(--ink-900)}
.mc-btn--info{background:var(--info);color:#fff}.mc-btn--info:hover{background:#123C73}
.mc-btn--outline{background:transparent;color:var(--primary);border-color:var(--primary)}.mc-btn--outline:hover{background:var(--red-100)}
.mc-btn--ghost{background:transparent;color:var(--text)}.mc-btn--ghost:hover{background:var(--cream-200)}
.mc-btn--danger{background:var(--red-700)}
.mc-btn--sm{min-height:44px;padding:0 var(--sp-4);font-size:12px}
.mc-card{background:#fff;border:1.5px solid var(--border);border-radius:24px;box-shadow:var(--shadow-1);padding:var(--sp-5)}
.mc-card--accent{background:var(--accent);border-color:var(--accent)}
.hero{background:linear-gradient(135deg,var(--red-600),var(--red-700));color:var(--cream-50);border-radius:24px;padding:var(--sp-6);display:flex;justify-content:space-between;align-items:center;gap:var(--sp-5);box-shadow:var(--shadow-2);margin-bottom:var(--sp-5)}
.hero h2{font-size:clamp(34px,5vw,56px);color:#fff;margin:var(--sp-3) 0}
.hero p{max-width:60ch;color:var(--cream-50)}
.tag{display:inline-flex;gap:var(--sp-2);align-items:center;border-radius:999px;padding:4px 14px;font-size:13px;font-weight:600;border:1.5px solid rgba(255,250,240,.35);color:var(--yellow-500)}
.hero .art{width:150px;height:150px;border-radius:24px;background:var(--cream-100);display:grid;place-items:center;color:var(--primary);flex:none;border:4px solid var(--cream-300)}
.hero .art svg{width:72px;height:72px}
.label{font-size:11px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--text-muted)}
.sec-title{display:flex;justify-content:space-between;align-items:center;gap:var(--sp-3);margin-bottom:var(--sp-4);flex-wrap:wrap}
.sec-title h3{font-size:22px}
.pilltag{background:var(--red-100);color:var(--red-700);border-radius:999px;font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;padding:4px 14px}
.quick{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:var(--sp-4)}
.q{background:var(--cream-50);border:1.5px solid var(--border);border-radius:16px;padding:var(--sp-4);text-align:left;cursor:pointer;display:flex;flex-direction:column;gap:var(--sp-1);min-height:44px}
.q:hover{box-shadow:var(--shadow-2);transform:translateY(-1px)}
.q .ico{width:40px;height:40px;border-radius:12px;display:grid;place-items:center;margin-bottom:var(--sp-2)}
.q b{font-size:15px;line-height:1.3}.q span.d{font-size:12px;color:var(--text-muted)}
.stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:var(--sp-4);margin-top:var(--sp-5)}
.stat .n{font-family:var(--font-display);color:var(--primary);font-size:clamp(28px,3.4vw,40px);line-height:1.1;margin:var(--sp-2) 0}
.mc-card--accent .n{color:var(--ink-900)}
.stat small{color:var(--text-muted);font-size:13px;display:block}
.mc-card--accent small{color:var(--ink-900)}
.mc-badge{display:inline-flex;align-items:center;gap:6px;border-radius:999px;padding:2px 12px;font-size:12px;font-weight:700;white-space:nowrap}
.mc-badge::before{content:"";width:7px;height:7px;border-radius:50%;background:currentColor}
.b-pending{background:var(--yellow-100);color:var(--yellow-600)}.b-process{background:var(--blue-100);color:var(--blue-600)}
.b-done{background:var(--green-100);color:var(--success)}.b-danger{background:var(--red-100);color:var(--red-700)}.b-neutral{background:var(--cream-200);color:var(--text-muted)}
.mc-chip{min-height:44px;padding:0 var(--sp-4);border-radius:999px;border:1.5px solid var(--border);background:#fff;font-weight:600;font-size:13px;cursor:pointer}
.mc-chip[aria-pressed=true]{background:var(--primary);border-color:var(--primary);color:var(--cream-50)}
.row{display:flex;gap:var(--sp-2);flex-wrap:wrap;align-items:center}
.orders{display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:var(--sp-4)}
.order h4{font-family:var(--font-display);font-weight:400;font-size:20px;text-transform:uppercase}
.order ul{list-style:none;padding:0;margin:var(--sp-3) 0;display:grid;gap:var(--sp-1)}
.order li{background:var(--cream-50);border-radius:12px;padding:6px 12px;font-size:14px}
.total{display:flex;justify-content:space-between;align-items:center;border-top:1.5px dashed var(--border);padding-top:var(--sp-3);margin:var(--sp-3) 0}
.price{font-family:var(--font-display);color:var(--primary);font-size:20px}
.mc-table-wrap{overflow-x:auto;border-radius:16px;border:1.5px solid var(--border);background:#fff}
.mc-table{width:100%;border-collapse:collapse;font-size:14px;min-width:640px}
.mc-table th{background:var(--primary);color:#fff;font-size:12px;letter-spacing:.08em;text-transform:uppercase;text-align:left;padding:var(--sp-3) var(--sp-4)}
.mc-table td{padding:var(--sp-3) var(--sp-4);vertical-align:middle}
.mc-table tbody tr:nth-child(even){background:var(--cream-50)}
.is-num{text-align:right!important}
.toolbar{display:flex;gap:var(--sp-3);flex-wrap:wrap;margin-bottom:var(--sp-4);align-items:center}
.toolbar .mc-input{max-width:320px}
.field{display:grid;gap:var(--sp-1);margin-bottom:var(--sp-3)}.field label{font-weight:600;font-size:14px}
.help{font-size:13px;color:var(--text-muted)}.field.err .mc-input{border-color:var(--red-700)}.field.err .help{color:var(--red-700)}
.bars{display:flex;align-items:flex-end;gap:var(--sp-3);height:160px;padding-top:var(--sp-3)}
.bars div{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:flex-end;height:100%;font-size:11px;color:var(--text-muted);gap:4px}
.bars i{display:block;width:100%;max-width:44px;background:var(--primary);border-radius:8px 8px 0 0;min-height:4px}
.two{display:grid;grid-template-columns:2fr 1fr;gap:var(--sp-4);margin:var(--sp-4) 0}
.mc-alert{border-radius:16px;padding:var(--sp-3) var(--sp-4);font-size:14px;margin-top:var(--sp-3);font-weight:500}
.al-ok{background:var(--green-100);color:var(--success)}.al-warn{background:var(--yellow-100);color:var(--yellow-600)}.al-err{background:var(--red-100);color:var(--red-700)}
.overlay{position:fixed;inset:0;background:rgba(94,10,16,.45);display:none;place-items:center;padding:var(--sp-4);z-index:50}
.overlay.on{display:grid}
.modal{background:#fff;border-radius:24px;box-shadow:var(--shadow-3);padding:var(--sp-5);max-width:440px;width:100%}
.modal h3{font-size:24px;margin-bottom:var(--sp-2)}
.modal .row{justify-content:space-between;margin-top:var(--sp-5)}
.toast{position:fixed;right:var(--sp-4);bottom:var(--sp-4);background:var(--ink-900);color:var(--cream-50);border-radius:16px;padding:var(--sp-3) var(--sp-5);box-shadow:var(--shadow-3);font-weight:500;opacity:0;transform:translateY(12px);transition:.2s;pointer-events:none;z-index:60}
.toast.on{opacity:1;transform:none}
.sw{position:relative;width:52px;height:30px;border-radius:999px;border:0;background:var(--cream-300);cursor:pointer;flex:none}
.sw::after{content:"";position:absolute;top:3px;left:3px;width:24px;height:24px;border-radius:50%;background:#fff;transition:.15s}
.sw[aria-checked=true]{background:var(--success)}.sw[aria-checked=true]::after{left:25px}
.view{display:none}.view.on{display:block}
@media(max-width:860px){
  .mc-app{grid-template-columns:1fr}
  .side{position:static;height:auto;flex-direction:row;flex-wrap:nowrap;overflow-x:auto;align-items:center}
  .brand{border:0;padding:0;margin:0}.brand div{display:none}.grp,.sidesearch{display:none}
  .nav{width:auto;white-space:nowrap}
  .main{padding:var(--sp-4)}.two{grid-template-columns:1fr}.hero{flex-direction:column;align-items:flex-start}
}
@media(prefers-reduced-motion:reduce){*{transition:none!important}}
</style>
</head>
<body>
<div class="mc-app">
  <aside class="side" aria-label="Navigasi staff">
    <div class="brand"><span class="logo" id="logoIc"></span><div><b>Maky Chicken</b><small>Sistem Restoran</small></div></div>
    <input id="navSearch" class="sidesearch" type="search" placeholder="Cari menu... Ctrl /" aria-label="Cari menu navigasi">
    <nav id="nav" style="display:contents"></nav>
  </aside>
  <main class="main">
    <header class="top">
      <div><h1 id="pgTitle">Dashboard</h1><p id="pgSub">Portal operasional &amp; kontrol staff restoran</p></div>
      <div class="userbox">
        <select id="role" class="mc-select roleSel" aria-label="Peran login (simulasi UC-04)">
          <option>Admin</option><option>Manajer</option><option selected>Kasir</option><option>Pelayan</option>
        </select>
        <div class="pill-user"><span class="av">RN</span><div><b>Reyhan Nasrul</b><small id="roleLbl">Staff Operasional Kasir</small></div></div>
        <button class="mc-btn mc-btn--outline" data-act="logout">Log Out</button>
      </div>
    </header>
    <div id="views"></div>
  </main>
</div>
<div class="overlay" id="ov"><div class="modal" role="dialog" aria-modal="true"><h3 id="mT"></h3><p id="mP"></p><div class="row"><button class="mc-btn mc-btn--ghost" id="mNo">Batal</button><button class="mc-btn mc-btn--danger" id="mYes">Ya</button></div></div></div>
<div class="toast" id="toast" role="status"></div>

<script>
/* ===== Util ===== */
const P={home:'M3 11l9-8 9 8v9a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z',pos:'M6 2h12v20l-3-2-3 2-3-2-3 2zM9 7h6M9 11h6',cook:'M6 13h12a2 2 0 0 1 0 4H6a2 2 0 0 1 0-4zM8 13V9a4 4 0 0 1 8 0v4',chart:'M4 20V10M10 20V4M16 20v-8M22 20H2',cal:'M5 4h14a1 1 0 0 1 1 1v15H4V5a1 1 0 0 1 1-1zM4 9h16M8 2v4M16 2v4',menu:'M4 6h16M4 12h16M4 18h10',users:'M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM2 21v-1a6 6 0 0 1 12 0v1M17 4a4 4 0 0 1 0 7M22 21v-1a5 5 0 0 0-4-5',msg:'M21 12a8 8 0 0 1-11.5 7.2L3 21l1.8-5.5A8 8 0 1 1 21 12z',bolt:'M13 2L4 14h7l-1 8 9-12h-7z',cash:'M3 6h18v12H3zM12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6z',dl:'M12 3v12M7 11l5 5 5-5M4 21h16',leg:'M15 4a5 5 0 0 1-3 8l-6 7a2 2 0 0 1-3-3l7-6a5 5 0 0 1 5-6z'};
const ic=n=>`<svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="${P[n]}"/></svg>`;
const rp=n=>'Rp'+n.toLocaleString('id-ID');
const $=s=>document.querySelector(s);
const esc=s=>String(s).replace(/[&<>"]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));
let tT;function toast(m){const t=$('#toast');t.textContent=m;t.classList.add('on');clearTimeout(tT);tT=setTimeout(()=>t.classList.remove('on'),3000)}
function confirmBox(title,text,yes,fn){$('#mT').textContent=title;$('#mP').textContent=text;$('#mYes').textContent=yes;$('#ov').classList.add('on');$('#mNo').focus();
  $('#mYes').onclick=()=>{$('#ov').classList.remove('on');fn()};$('#mNo').onclick=()=>$('#ov').classList.remove('on')}
const BADGE={'Baru':'pending','Diproses':'process','Selesai':'done','Menunggu Pembayaran':'pending','Lunas':'done','Gagal':'danger','Pending DP':'pending','Confirmed':'done','Dibatalkan':'neutral','Pending':'pending','Aktif':'done','Nonaktif':'neutral','Diblokir':'danger','Tersedia':'done','Habis':'neutral'};
const badge=s=>`<span class="mc-badge b-${BADGE[s]}">${s}</span>`;

/* ===== Data contoh ===== */
const orders=[
 {id:'MC-8942',meja:12,items:[['Ayam Geprek Sambal Korek',2,28000],['Crispy Chicken Burger',1,32000],['Es Teh Manis Jumbo',2,8000]],metode:'QRIS',bayar:'Menunggu Pembayaran',status:'Baru'},
 {id:'MC-8943',meja:5,items:[['Paket Ayam Krispi Gurih',2,26000],['Es Teh Manis Jumbo',2,8000]],metode:'Tunai',bayar:'Menunggu Pembayaran',status:'Baru'},
 {id:'MC-8941',meja:8,items:[['Chicken Tenders & Fries',1,25000],['Maky Chicken Burger',1,32000]],metode:'QRIS',bayar:'Lunas',status:'Diproses'},
 {id:'MC-8938',meja:3,items:[['Paket Ayam Geprek Juara',3,28000]],metode:'Tunai',bayar:'Lunas',status:'Selesai'}];
const tot=o=>o.items.reduce((a,[,q,p])=>a+q*p,0);
const resv=[
 {k:'RES-1049',n:'Budi Santoso',t:'25 Okt 2026',s:'18:30',l:'Lantai 1',o:6,st:'Confirmed'},
 {k:'RES-1050',n:'Sari Wulandari',t:'25 Okt 2026',s:'19:30',l:'Lantai 2',o:4,st:'Pending DP'},
 {k:'RES-1051',n:'Andi Pratama',t:'26 Okt 2026',s:'12:00',l:'Lantai 1',o:8,st:'Confirmed'},
 {k:'RES-1047',n:'Nadia Putri',t:'24 Okt 2026',s:'18:30',l:'Lantai 2',o:5,st:'Dibatalkan'}];
const menus=[
 {n:'Paket Ayam Geprek Juara',c:'Paket Ayam',h:28000,a:true},{n:'Paket Ayam Krispi Gurih',c:'Paket Ayam',h:26000,a:true},
 {n:'Maky Chicken Burger',c:'Ala Carte',h:32000,a:true},{n:'Chicken Tenders & Fries',c:'Ala Carte',h:25000,a:false},
 {n:'Es Teh Manis Jumbo',c:'Minuman',h:8000,a:true}];
const staff=[
 {n:'Reyhan Nasrul',u:'reyhan.k',r:'Kasir',s:'Aktif'},{n:'Dewi Lestari',u:'dewi.p',r:'Pelayan',s:'Aktif'},
 {n:'Bima Aditya',u:'bima.m',r:'Manajer',s:'Aktif'},{n:'Rina Maharani',u:'rina.p',r:'Pelayan',s:'Nonaktif'}];
const kompl=[
 {id:'KF-021',n:'Aulia R.',j:'Komplain',m:'Sambal terlalu asin dan nasi dingin.',t:'19 Okt',s:'Pending'},
 {id:'KF-020',n:'Dimas P.',j:'Feedback',m:'Pelayanan cepat, burgernya enak!',t:'18 Okt',s:'Selesai'},
 {id:'KF-019',n:'Maya S.',j:'Komplain',m:'Pesanan datang 40 menit.',t:'18 Okt',s:'Diproses'}];
const jam=[['10',3],['12',9],['14',5],['16',4],['18',11],['20',8]];
const SYS_CASH=3850000;let kasFisik=null;

/* ===== Navigasi & peran (UC-04, aktor per use case) ===== */
const NAV=[
 {id:'dash',t:'Dashboard',ic:'home',r:'AMKP',g:'Overview',sub:'Portal operasional & kontrol staff restoran'},
 {id:'pos',t:'Antrean POS Kasir',ic:'pos',r:'KA',g:'Overview',sub:'UC-10, 11, 12 · Pesanan otomatis, verifikasi pembayaran, cetak struk'},
 {id:'status',t:'Status Pesanan',ic:'cook',r:'PA',g:'Overview',sub:'UC-13 · Pantau dan ubah status pesanan'},
 {id:'laporan',t:'Laporan Penjualan',ic:'chart',r:'AM',g:'Manajemen Operasional',sub:'UC-14, 15, 16 · Dashboard penjualan, kas, export Excel'},
 {id:'resv',t:'Manajemen Reservasi',ic:'cal',r:'A',g:'Manajemen Operasional',sub:'UC-02 · Kelola reservasi pelanggan'},
 {id:'menu',t:'Kelola Menu',ic:'leg',r:'KA',g:'Manajemen Operasional',sub:'UC-09 · Kelola ketersediaan menu'},
 {id:'akun',t:'Kelola Akun Staff',ic:'users',r:'A',g:'Pengaturan & Lainnya',sub:'UC-03 · Tambah, nonaktifkan, blokir akun'},
 {id:'komp',t:'Komplain & Feedback',ic:'msg',r:'AM',g:'Pengaturan & Lainnya',sub:'UC-17, 18 · Laporan dari pelanggan'}];
const RL={Admin:'Admin Restoran',Manajer:'Manajer Restoran',Kasir:'Staff Operasional Kasir',Pelayan:'Pelayan'};
let cur='dash';const role=()=>$('#role').value;const can=n=>n.r.includes(role()[0]==='A'?'A':role()[0]==='M'?'M':role()[0]==='K'?'K':'P');
function renderNav(){const q=$('#navSearch').value.toLowerCase();let h='',g='';
  NAV.filter(n=>can(n)&&n.t.toLowerCase().includes(q)).forEach(n=>{if(n.g!==g){g=n.g;h+=`<div class="grp">${g}</div>`}
   h+=`<button class="nav" data-go="${n.id}" ${n.id===cur?'aria-current="page"':''}>${ic(n.ic)}<span>${n.t}</span></button>`});
  $('#nav').innerHTML=h;$('#roleLbl').textContent=RL[role()]}
function go(id){const n=NAV.find(x=>x.id===id);if(!n||!can(n))id='dash';cur=id;const m=NAV.find(x=>x.id===id);
  $('#pgTitle').textContent=id==='dash'?'Dashboard':m.t;$('#pgSub').textContent=m.sub;renderNav();$('#views').innerHTML=V[id]();window.scrollTo(0,0)}

/* ===== View ===== */
const V={
dash(){const lunas=orders.filter(o=>o.bayar==='Lunas'),omset=lunas.reduce((a,o)=>a+tot(o),0),
  qr=lunas.filter(o=>o.metode==='QRIS').reduce((a,o)=>a+tot(o),0),conf=resv.filter(r=>r.st==='Confirmed').length;
  const q=[['pos','pos','Antrean POS / Kasir','Terima order & verifikasi bayar','var(--red-100)','var(--primary)'],['laporan','cash','Input Jumlah Kas Fisik','Rekonsiliasi kas','var(--green-100)','var(--success)'],['status','cook','Update Status Pesanan','Pantau antrean masak','var(--yellow-100)','var(--yellow-600)'],['laporan','dl','Export Laporan (.xlsx)','Unduh laporan penjualan','var(--blue-100)','var(--info)']]
   .filter(([id])=>can(NAV.find(n=>n.id===id)));
  return `<section class="hero"><div><span class="tag">${ic('cal')} ${new Date().toLocaleDateString('id-ID',{weekday:'long',day:'numeric',month:'long',year:'numeric'})}</span>
   <h2>Halo, Reyhan</h2><p>Selamat datang kembali di Dashboard Staff Maky Chicken. Kelola pesanan, reservasi, laporan penjualan, dan layanan restoran dalam satu tempat.</p></div><div class="art">${ic('cook')}</div></section>
  <section class="mc-card"><div class="sec-title"><h3>Akses Cepat Operasional</h3><span class="pilltag">Pintasan utama</span></div>
   <div class="quick">${q.map(([id,i,t,d,bg,c])=>`<button class="q" data-go="${id}"><span class="ico" style="background:${bg};color:${c}">${ic(i)}</span><b>${t}</b><span class="d">${d}</span></button>`).join('')||'<p class="help">Tidak ada pintasan untuk peran ini.</p>'}</div></section>
  <div class="stats">
   <div class="mc-card stat"><span class="label">Pesanan masuk hari ini</span><div class="n">${orders.length} Pesanan</div><small>${orders.filter(o=>o.status==='Selesai').length} selesai · ${orders.filter(o=>o.status==='Diproses').length} diproses · ${orders.filter(o=>o.status==='Baru').length} baru</small></div>
   <div class="mc-card stat"><span class="label">Pendapatan hari ini (lunas)</span><div class="n">${rp(omset)}</div><small>${rp(qr)} QRIS · ${rp(omset-qr)} Tunai</small></div>
   <div class="mc-card mc-card--accent stat"><span class="label" style="color:var(--ink-900)">Reservasi Confirmed</span><div class="n">${conf} Reservasi</div><small>DP Rp400.000 terverifikasi</small></div></div>`},
pos(){return `<div class="sec-title"><span class="mc-badge b-done">Pesanan otomatis masuk</span></div><div class="orders">${orders.map((o,i)=>`
  <article class="mc-card order"><div class="row" style="justify-content:space-between"><span class="label">Meja ${o.meja}</span>${badge(o.bayar)}</div>
  <h4>Order #${o.id}</h4><ul>${o.items.map(([n,q,p])=>`<li>${n} ×${q} <b style="float:right">${rp(q*p)}</b></li>`).join('')}</ul>
  <div class="total"><span class="label">Total · ${o.metode}</span><span class="price">${rp(tot(o))}</span></div>
  <div class="row">${o.bayar==='Lunas'?`<button class="mc-btn mc-btn--info mc-btn--sm" data-print="${i}">${ic('pos')} Cetak Struk</button>`:
   `<button class="mc-btn mc-btn--sm" data-verify="${i}">Verifikasi</button>`}</div></article>`).join('')}</div>`},
status(){return `<div class="orders">${orders.map((o,i)=>`<article class="mc-card order"><div class="row" style="justify-content:space-between"><span class="label">Meja ${o.meja}</span>${badge(o.status)}</div>
  <h4>Order #${o.id}</h4><ul>${o.items.map(([n,q])=>`<li>${n} ×${q}</li>`).join('')}</ul>
  <div class="row" role="group" aria-label="Ubah status">${['Baru','Diproses','Selesai'].map(s=>`<button class="mc-chip" aria-pressed="${o.status===s}" data-set="${i}|${s}">${s}</button>`).join('')}</div></article>`).join('')}</div>`},
laporan(){const adm=role()==='Admin',tr=orders.filter(o=>o.bayar==='Lunas'),sel=kasFisik===null?null:kasFisik-SYS_CASH;
  return `<div class="toolbar"><div class="row" role="tablist">${['Hari ini','7 Hari','Bulan ini'].map((t,i)=>`<button class="mc-chip" aria-pressed="${i===0}" data-per>${t}</button>`).join('')}</div>
   <input class="mc-input" type="date" aria-label="Filter tanggal" style="max-width:190px"><button class="mc-btn mc-btn--info" data-act="export" ${adm?'':'disabled'}>${ic('dl')} Export Excel</button></div>
  <div class="stats" style="margin:0 0 var(--sp-4)">
   <div class="mc-card stat"><span class="label">Total penjualan</span><div class="n">${rp(SYS_CASH)}</div></div>
   <div class="mc-card stat"><span class="label">Jumlah transaksi</span><div class="n">${tr.length+40}</div></div>
   <div class="mc-card stat"><span class="label">Kas fisik</span><div class="n">${kasFisik===null?'-':rp(kasFisik)}</div></div>
   <div class="mc-card mc-card--accent stat"><span class="label" style="color:var(--ink-900)">Selisih</span><div class="n">${sel===null?'-':rp(sel)}</div></div></div>
  <div class="two"><div class="mc-card"><h3 style="font-size:20px">Transaksi per jam</h3><div class="bars">${jam.map(([j,v])=>`<div><i style="height:${v*8}%"></i>${j}:00</div>`).join('')}</div></div>
   <div class="mc-card"><h3 style="font-size:20px">Masukkan jumlah kas</h3>
    <div class="field" id="kasF"><label for="kas">Kas fisik (Rp)</label><input id="kas" class="mc-input" inputmode="numeric" placeholder="3850000" ${adm?'':'disabled'}><span class="help" id="kasH">Kas sistem hari ini: ${rp(SYS_CASH)}</span></div>
    <button class="mc-btn mc-btn--block" style="width:100%" data-act="kas" ${adm?'':'disabled'}>Simpan</button>
    ${adm?'':'<p class="help" style="margin-top:8px">Hanya Admin yang dapat mengisi kas.</p>'}
    ${sel===null?'':`<div class="mc-alert ${sel===0?'al-ok':sel>0?'al-warn':'al-err'}">${sel===0?'Sesuai: kas fisik sama dengan kas sistem.':sel>0?'Lebih '+rp(sel)+' dari kas sistem.':'Kurang '+rp(-sel)+' dari kas sistem.'}</div>`}</div></div>
  <div class="mc-table-wrap"><table class="mc-table"><thead><tr><th>Kode</th><th>Meja</th><th>Metode</th><th>Status</th><th class="is-num">Total</th></tr></thead><tbody>
   ${orders.map(o=>`<tr><td>#${o.id}</td><td>${o.meja}</td><td>${o.metode}</td><td>${badge(o.bayar)}</td><td class="is-num">${rp(tot(o))}</td></tr>`).join('')}</tbody></table></div>`},
resv(){return `<div class="toolbar"><input class="mc-input" placeholder="Cari nama / kode booking" data-filter="resv" aria-label="Cari reservasi"></div>
  <div class="mc-table-wrap"><table class="mc-table"><thead><tr><th>Kode</th><th>Atas nama</th><th>Jadwal</th><th>Area</th><th class="is-num">Orang</th><th>Status</th><th>Aksi</th></tr></thead><tbody id="rb">${resvRows('')}</tbody></table></div>`},
menu(){return `<div class="mc-table-wrap"><table class="mc-table"><thead><tr><th>Menu</th><th>Kategori</th><th class="is-num">Harga</th><th>Status</th><th>Tersedia</th></tr></thead><tbody>
  ${menus.map((m,i)=>`<tr><td><b>${m.n}</b></td><td>${m.c}</td><td class="is-num">${rp(m.h)}</td><td>${badge(m.a?'Tersedia':'Habis')}</td>
  <td><button class="sw" role="switch" aria-checked="${m.a}" aria-label="Ketersediaan ${esc(m.n)}" data-sw="${i}"></button></td></tr>`).join('')}</tbody></table></div>`},
akun(){return `<div class="mc-table-wrap"><table class="mc-table"><thead><tr><th>Nama</th><th>Username</th><th>Peran</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
  ${staff.map((s,i)=>`<tr><td><b>${s.n}</b></td><td>${s.u}</td><td>${s.r}</td><td>${badge(s.s)}</td><td>${s.s==='Aktif'?`<button class="mc-btn mc-btn--outline mc-btn--sm" data-off="${i}">Nonaktifkan</button>`:`<button class="mc-btn mc-btn--sm" data-on="${i}">Aktifkan</button>`}</td></tr>`).join('')}</tbody></table></div>`},
komp(){const adm=role()==='Admin';return `<div class="toolbar"><input class="mc-input" placeholder="Cari nama atau isi pesan" data-filter="komp" aria-label="Cari laporan"></div>
  <div class="mc-table-wrap"><table class="mc-table"><thead><tr><th>ID</th><th>Pelanggan</th><th>Jenis</th><th>Pesan</th><th>Tanggal</th><th>Status</th></tr></thead><tbody id="kb">${kompRows('')}</tbody></table></div>
  ${adm?'':'<p class="help" style="margin-top:8px">Manajer hanya dapat melihat laporan.</p>'}`}
};
function resvRows(q){return resv.map((r,i)=>({r,i})).filter(({r})=>(r.n+r.k).toLowerCase().includes(q)).map(({r,i})=>`<tr><td>#${r.k}</td><td>${r.n}</td><td>${r.t}, ${r.s} WIB</td><td>${r.l}</td><td class="is-num">${r.o}</td><td>${badge(r.st)}</td>
  <td>${r.st==='Dibatalkan'?'':`<button class="mc-btn mc-btn--outline mc-btn--sm" data-cancel="${i}">Batalkan</button>`}</td></tr>`).join('')}
function kompRows(q){const adm=role()==='Admin';return kompl.map((k,i)=>({k,i})).filter(({k})=>(k.n+k.m).toLowerCase().includes(q)).map(({k,i})=>`<tr><td>${k.id}</td><td>${k.n}</td><td>${k.j}</td><td style="max-width:260px">${esc(k.m)}</td><td>${k.t}</td>
  <td>${adm?`<select class="mc-select" style="min-height:44px;width:auto" data-ks="${i}" aria-label="Ubah status ${k.id}">${['Pending','Diproses','Selesai'].map(s=>`<option ${s===k.s?'selected':''}>${s}</option>`).join('')}</select>`:badge(k.s)}</td></tr>`).join('')}

/* ===== Event ===== */
document.addEventListener('click',e=>{const t=e.target.closest('button');if(!t)return;const d=t.dataset;
 if('go' in d)go(d.go);
 else if('verify' in d){orders[d.verify].bayar='Lunas';toast('Pembayaran terverifikasi. Struk siap dicetak.');go(cur)}
 else if('print' in d){toast('Struk #'+orders[d.print].id+' dikirim ke printer.')}
 else if('set' in d){const[i,s]=d.set.split('|');orders[i].status=s;toast('Status pesanan diperbarui menjadi '+s+'.');go(cur)}
 else if('sw' in d){const m=menus[d.sw];m.a=!m.a;toast(m.n+(m.a?' tersedia.':' ditandai habis.'));go(cur)}
 else if('cancel' in d){const r=resv[d.cancel];confirmBox('Batalkan reservasi?','Reservasi #'+r.k+' atas nama '+r.n+' akan dibatalkan.','Ya, batalkan',()=>{r.st='Dibatalkan';toast('Reservasi dibatalkan.');go(cur)})}
 else if('off' in d){const s=staff[d.off];confirmBox('Nonaktifkan akun?',s.n+' tidak akan bisa login sampai diaktifkan kembali.','Nonaktifkan',()=>{s.s='Nonaktif';toast('Akun dinonaktifkan.');go(cur)})}
 else if('on' in d){staff[d.on].s='Aktif';toast('Akun diaktifkan.');go(cur)}
 else if('per' in d){t.parentElement.querySelectorAll('.mc-chip').forEach(c=>c.setAttribute('aria-pressed',c===t))}
 else if(d.act==='export'){toast('Laporan penjualan.xlsx berhasil diunduh.')}
 else if(d.act==='logout'){confirmBox('Keluar dari sistem?','Anda akan kembali ke halaman login.','Log Out',()=>toast('Berhasil keluar.'))}
 else if(d.act==='kas'){const v=$('#kas').value.trim(),f=$('#kasF'),h=$('#kasH');
   if(v===''||isNaN(v)||Number(v)<0){f.classList.add('err');h.textContent=v===''?'Jumlah kas wajib diisi.':Number(v)<0?'Jumlah kas tidak boleh negatif.':'Masukkan angka saja, tanpa titik atau huruf.';return}
   kasFisik=Number(v);toast('Kas fisik tersimpan.');go('laporan')}});
document.addEventListener('input',e=>{const f=e.target.dataset.filter;if(!f)return;const q=e.target.value.toLowerCase();
 $(f==='resv'?'#rb':'#kb').innerHTML=f==='resv'?resvRows(q):kompRows(q)});
document.addEventListener('change',e=>{if('ks' in e.target.dataset){kompl[e.target.dataset.ks].s=e.target.value;toast('Status laporan diubah menjadi '+e.target.value+'.')}});
$('#role').addEventListener('change',()=>go('dash'));
$('#navSearch').addEventListener('input',renderNav);
document.addEventListener('keydown',e=>{if(e.ctrlKey&&e.key==='/'){e.preventDefault();$('#navSearch').focus()}if(e.key==='Escape')$('#ov').classList.remove('on')});
$('#logoIc').innerHTML=ic('leg');go('dash');
</script>
</body>
</html>