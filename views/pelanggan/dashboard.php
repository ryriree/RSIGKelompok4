<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Maky Chicken | Pelanggan</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Titan+One&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{
  --red-100:#FBE0DD;--red-600:#B3131B;--red-700:#8E0F18;--red-900:#5E0A10;
  --cream-50:#FFFAF0;--cream-100:#FFF3DC;--cream-200:#FBE6BE;--cream-300:#F2D49A;
  --yellow-100:#FFF0C2;--yellow-500:#FFB21E;--yellow-600:#F28C0F;
  --blue-100:#DCEBFA;--blue-600:#1F5FAE;--green-600:#1E8E4E;--green-100:#D9F2E3;
  --ink-900:#2A1010;--ink-600:#6B4A45;
  --primary:var(--red-600);--primary-hover:var(--red-700);--border:var(--cream-300);--text:var(--ink-900);--text-muted:var(--ink-600);
  --accent:var(--yellow-500);--info:var(--blue-600);--success:var(--green-600);
  --shadow-1:0 2px 8px rgba(142,15,24,.08);--shadow-2:0 10px 28px rgba(142,15,24,.14);--shadow-3:0 18px 44px rgba(94,10,16,.22);
  --font-display:'Titan One','Arial Black',sans-serif;--font-body:'Poppins',system-ui,sans-serif;
}
*{box-sizing:border-box;margin:0}
body{font-family:var(--font-body);background:var(--cream-100);color:var(--text);line-height:1.6;font-variant-numeric:tabular-nums;overflow-x:hidden}
button,input,select,textarea{font:inherit;color:inherit}
:focus-visible{outline:3px solid var(--info);outline-offset:2px}
svg.i{width:1.15em;height:1.15em;fill:none;stroke:currentColor;stroke-width:1.75;stroke-linecap:round;stroke-linejoin:round;flex:none}
h1,h2,h3,h4,.disp{font-family:var(--font-display);font-weight:400;text-transform:uppercase;line-height:1.02;text-wrap:balance}
.wrap{max-width:1200px;margin:0 auto;padding:0 16px}
section{padding:64px 0}

/* Hero + navbar */
.hero{background:var(--primary);color:var(--cream-50);padding:20px 0 0;position:relative}
.nav{background:#fff;color:var(--text);border-radius:24px;box-shadow:var(--shadow-2);display:flex;align-items:center;justify-content:space-between;gap:16px;padding:10px 16px 10px 20px;flex-wrap:wrap}
.brand{display:flex;align-items:center;gap:12px;font-family:var(--font-display);color:var(--primary);font-size:22px;text-transform:uppercase}
.logo{width:40px;height:40px;border-radius:50%;background:var(--accent);color:var(--red-900);display:grid;place-items:center;border:2px solid var(--primary)}
.links{display:flex;gap:8px;flex-wrap:wrap}
.links a{min-height:44px;padding:0 14px;display:inline-flex;align-items:center;font-weight:700;font-size:13px;letter-spacing:.08em;text-transform:uppercase;color:var(--text);text-decoration:none;border-bottom:3px solid transparent}
.links a:hover,.links a.on{color:var(--primary);border-color:var(--primary)}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;min-height:44px;padding:0 24px;border-radius:999px;border:1.5px solid transparent;font-weight:700;font-size:13px;letter-spacing:.08em;text-transform:uppercase;cursor:pointer;transition:transform .15s,background .15s;background:var(--primary);color:var(--cream-50);text-decoration:none}
.btn:hover{transform:translateY(-1px);background:var(--primary-hover)}.btn:active{transform:none}.btn:disabled{opacity:.45;cursor:not-allowed;transform:none}
.btn--accent{background:var(--accent);color:var(--ink-900)}.btn--accent:hover{background:var(--yellow-600);color:var(--ink-900)}
.btn--info{background:var(--info)}.btn--info:hover{background:#123C73}
.btn--outline{background:transparent;color:var(--primary);border-color:var(--primary)}.btn--outline:hover{background:var(--red-100);color:var(--primary)}
.btn--ghost{background:transparent;color:var(--text)}.btn--ghost:hover{background:var(--cream-200);color:var(--text)}
.btn--sm{padding:0 16px;font-size:12px}.btn--block{width:100%}
.hero-grid{display:grid;grid-template-columns:1.1fr .9fr;gap:32px;align-items:center;padding:48px 0 96px}
.tagline{display:inline-flex;align-items:center;gap:8px;border:1.5px solid rgba(255,250,240,.4);border-radius:999px;padding:4px 16px;font-size:12px;font-weight:700;letter-spacing:.1em;text-transform:uppercase}
.hero h1{font-size:clamp(40px,6.5vw,76px);color:var(--cream-50);margin:16px 0}
.hero p{max-width:55ch;margin-bottom:24px}
.cta{display:flex;gap:12px;flex-wrap:wrap}
.photo{aspect-ratio:4/3;border-radius:24px;background:var(--cream-200);color:var(--primary);display:grid;place-items:center;transform:rotate(3deg);box-shadow:var(--shadow-3);border:4px solid rgba(255,250,240,.6);position:relative}
.photo svg{width:96px;height:96px}
.photo .float{position:absolute;left:-16px;bottom:-16px;transform:rotate(-3deg);background:var(--accent);color:var(--ink-900);border-radius:999px;padding:8px 20px;font-weight:700;box-shadow:var(--shadow-2)}
.wave{display:block;width:100%;height:56px;position:absolute;bottom:-1px;left:0}

/* Status cards */
.cards2{display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-top:-24px}
.card{background:#fff;border:1.5px solid var(--border);border-radius:24px;box-shadow:var(--shadow-1);padding:24px}
.card header{display:flex;justify-content:space-between;align-items:flex-start;gap:8px;flex-wrap:wrap;margin-bottom:8px}
.chip-label{background:var(--primary);color:var(--cream-50);border-radius:999px;font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;padding:2px 14px}
.card h3{font-size:24px;margin:8px 0 4px}.muted{color:var(--text-muted);font-size:14px}
.items{list-style:none;padding:0;margin:12px 0;display:grid;gap:6px}
.items li{display:flex;justify-content:space-between;background:var(--cream-50);border-radius:12px;padding:6px 12px;font-size:14px}
.items b,.price{font-family:var(--font-display);color:var(--primary);font-weight:400}
.foot{display:flex;justify-content:space-between;align-items:center;gap:12px;border-top:1.5px dashed var(--border);padding-top:12px;margin-top:12px;flex-wrap:wrap}
.info3{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin:12px 0}
.info3 div{background:var(--cream-50);border:1.5px solid var(--border);border-radius:16px;text-align:center;padding:8px}
.info3 small{display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted)}.info3 b{font-family:var(--font-display);font-weight:400;color:var(--primary)}
.mc-badge{display:inline-flex;align-items:center;gap:6px;border-radius:999px;padding:2px 12px;font-size:12px;font-weight:700;white-space:nowrap}
.mc-badge::before{content:"";width:7px;height:7px;border-radius:50%;background:currentColor}
.b-pending{background:var(--yellow-100);color:var(--yellow-600)}.b-process{background:var(--blue-100);color:var(--blue-600)}.b-done{background:var(--green-100);color:var(--success)}.b-danger{background:var(--red-100);color:var(--red-700)}.b-neutral{background:var(--cream-200);color:var(--text-muted)}

/* Menu */
.sec-head{text-align:center;margin-bottom:32px}
.sec-head h2{font-size:clamp(30px,5vw,48px);color:var(--primary);margin:8px 0}
.cats{display:flex;gap:8px;background:#fff;border:1.5px solid var(--border);border-radius:999px;padding:6px;overflow-x:auto;margin-bottom:24px}
.cat{flex:1;min-height:44px;border:0;border-radius:999px;background:none;font-weight:700;font-size:13px;letter-spacing:.08em;text-transform:uppercase;cursor:pointer;display:flex;gap:8px;align-items:center;justify-content:center;padding:0 20px;white-space:nowrap}
.cat[aria-selected=true]{background:var(--primary);color:var(--cream-50);box-shadow:var(--shadow-1)}
.grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:24px}
.mcard{background:#fff;border:1.5px solid var(--border);border-radius:24px;overflow:hidden;box-shadow:var(--shadow-1);display:flex;flex-direction:column;cursor:pointer;transition:box-shadow .15s}
.mcard:hover{box-shadow:var(--shadow-2)}
.mcard .ph{aspect-ratio:4/3;background:var(--cream-200);color:var(--primary);display:grid;place-items:center;position:relative}
.mcard .ph svg{width:56px;height:56px}
.ribbon{position:absolute;top:12px;left:12px;background:var(--accent);color:var(--ink-900);font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;border-radius:999px;padding:2px 12px}
.mcard .bd{padding:16px;display:flex;flex-direction:column;gap:6px;flex:1}
.mcard h4{font-family:var(--font-body);text-transform:none;font-size:17px;font-weight:600;line-height:1.3}
.mcard p{font-size:13px;color:var(--text-muted);display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.mcard .bt{display:flex;justify-content:space-between;align-items:center;margin-top:auto;border-top:1.5px solid var(--cream-200);padding-top:12px}
.price{font-size:20px}
.is-unavailable .ph{filter:grayscale(.7);opacity:.7}

/* Forms */
.formcard{max-width:760px;margin:0 auto}
.stepper{display:flex;gap:8px;margin-bottom:24px;flex-wrap:wrap}
.step{flex:1;min-width:120px;text-align:center;padding:8px;border-radius:999px;background:#fff;border:1.5px solid var(--border);font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--text-muted)}
.step.on{background:var(--primary);border-color:var(--primary);color:var(--cream-50)}.step.ok{background:var(--green-100);border-color:var(--green-100);color:var(--success)}
.field{display:grid;gap:4px;margin-bottom:16px}.field label{font-weight:600;font-size:14px}
.in{min-height:44px;border-radius:16px;border:1.5px solid var(--border);background:#fff;padding:0 16px;width:100%}
textarea.in{padding:12px 16px;min-height:120px;resize:vertical}
.help{font-size:13px;color:var(--text-muted)}.field.err .in{border-color:var(--red-700)}.field.err .help{color:var(--red-700)}
.chips{display:flex;gap:8px;flex-wrap:wrap}
.chip{min-height:44px;padding:0 18px;border-radius:999px;border:1.5px solid var(--border);background:#fff;font-weight:600;font-size:14px;cursor:pointer}
.chip[aria-pressed=true]{background:var(--primary);border-color:var(--primary);color:var(--cream-50)}.chip:disabled{opacity:.45;cursor:not-allowed;text-decoration:line-through}
.choice{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.pick{background:#fff;border:2px solid var(--border);border-radius:24px;padding:24px;text-align:center;cursor:pointer}
.pick[aria-pressed=true]{border-color:var(--primary);background:var(--red-100)}.pick h4{font-size:22px;color:var(--primary)}
.row{display:flex;gap:12px;justify-content:space-between;margin-top:24px;flex-wrap:wrap}
.alert{border-radius:16px;padding:12px 16px;font-size:14px;font-weight:500;margin-bottom:16px}
.al-info{background:var(--blue-100);color:var(--blue-600)}.al-err{background:var(--red-100);color:var(--red-700)}.al-ok{background:var(--green-100);color:var(--success)}
.qris{text-align:center}.qr{width:200px;height:200px;margin:12px auto;border-radius:16px;border:1.5px solid var(--border);background:
 repeating-conic-gradient(var(--ink-900) 0 25%,#fff 0 50%) 0 0/24px 24px;}
.nominal{font-family:var(--font-display);font-size:36px;color:var(--primary)}
.voucher{position:relative;background:#fff;border:2px dashed var(--primary);border-radius:24px;padding:24px 32px;text-align:center}
.voucher::before,.voucher::after{content:"";position:absolute;top:50%;width:28px;height:28px;border-radius:50%;background:var(--cream-100);border:2px solid var(--primary);transform:translateY(-50%)}
.voucher::before{left:-16px;border-left-color:transparent}.voucher::after{right:-16px;border-right-color:transparent}
.code{font-family:var(--font-display);font-size:40px;color:var(--primary);margin:8px 0}
.contact{display:grid;grid-template-columns:1.2fr .8fr;gap:24px;max-width:1000px;margin:0 auto}
.band{background:var(--accent);color:var(--ink-900);border-radius:24px;padding:24px;display:flex;flex-direction:column;gap:12px;justify-content:center}
.band h3{font-size:26px}

/* Cart bar, modal, toast */
.cartbar{position:fixed;left:50%;bottom:16px;transform:translate(-50%,120%);width:min(560px,calc(100% - 32px));background:var(--primary);color:var(--cream-50);border-radius:999px;box-shadow:var(--shadow-3);padding:8px 8px 8px 24px;display:flex;justify-content:space-between;align-items:center;gap:12px;transition:transform .2s;z-index:40}
.cartbar.on{transform:translate(-50%,0)}
.cartbar b{font-family:var(--font-display);font-weight:400;font-size:18px}
.overlay{position:fixed;inset:0;background:rgba(94,10,16,.5);display:none;place-items:center;padding:16px;z-index:50;overflow:auto}
.overlay.on{display:grid}
.modal{background:#fff;border-radius:24px;box-shadow:var(--shadow-3);padding:24px;max-width:480px;width:100%}
.modal h3{font-size:26px;margin-bottom:8px}
.qty{display:inline-flex;align-items:center;gap:4px;border:1.5px solid var(--border);border-radius:999px}
.qty button{width:44px;height:44px;border:0;background:none;border-radius:999px;font-size:20px;cursor:pointer;font-weight:700}
.qty button:hover{background:var(--cream-200)}
.line{display:flex;justify-content:space-between;align-items:center;gap:8px;padding:8px 0;border-bottom:1.5px dashed var(--border)}
.receipt{border:1.5px dashed var(--border);border-radius:16px;padding:16px;background:var(--cream-50)}
.toast{position:fixed;left:50%;top:16px;transform:translate(-50%,-150%);background:var(--ink-900);color:var(--cream-50);border-radius:16px;padding:12px 24px;box-shadow:var(--shadow-3);z-index:60;transition:transform .2s;max-width:calc(100% - 32px)}
.toast.on{transform:translate(-50%,0)}
footer{background:var(--cream-50);border-top:1.5px solid var(--border);padding:48px 0 24px}
.fgrid{display:grid;grid-template-columns:1.3fr 1fr 1fr 1.2fr;gap:32px}
footer h4{font-size:16px;margin-bottom:12px}footer a{display:block;color:var(--text-muted);text-decoration:none;font-size:14px;padding:4px 0}footer a:hover{color:var(--primary)}
footer p{font-size:14px;color:var(--text-muted)}
.copy{border-top:1.5px solid var(--border);margin-top:32px;padding-top:16px;font-size:12px;color:var(--text-muted)}
@media(max-width:860px){.hero-grid,.cards2,.contact,.fgrid{grid-template-columns:1fr}.hero-grid{padding-bottom:80px}.photo{transform:none}.cards2{margin-top:0}.links{order:3;width:100%;overflow-x:auto;flex-wrap:nowrap}.choice{grid-template-columns:1fr}}
@media(prefers-reduced-motion:reduce){*{transition:none!important}}
</style>
</head>
<body>
<header class="hero" id="home">
  <div class="wrap">
    <nav class="nav" aria-label="Navigasi utama">
      <a class="brand" href="#home" style="text-decoration:none"><span class="logo" id="lg"></span>Maky Chicken</a>
      <div class="links"><a class="on" href="#home">Home</a><a href="#menu">Menu</a><a href="#reservasi">Reservasi</a><a href="#contact">Contact Us</a></div>
      <button class="btn btn--accent btn--sm" id="cartBtn"><span id="cartIc"></span>Keranjang (<span id="cartN">0</span>)</button>
    </nav>
    <div class="hero-grid">
      <div>
        <span class="tagline" id="tg"></span>
        <h1>Rasa yang beda,<br>pasti ketagihan!</h1>
        <p>Sensasi ayam krispi renyah keemasan berpadu sambal korek pedas gurih khas Maky Chicken. Pesan langsung dari meja tanpa antre kasir atau booking lantai favoritmu sekarang!</p>
        <div class="cta">
          <a class="btn btn--accent" href="#menu" id="qrBtn"></a>
          <a class="btn btn--accent" href="#reservasi" id="rsBtn"></a>
          <a class="btn btn--accent" href="#contact" id="hbBtn"></a>
        </div>
      </div>
      <div class="photo" role="img" aria-label="Foto Ayam Geprek Sambal Korek"><span id="phIc"></span><span class="float">Sambal Bawang Korek</span></div>
    </div>
  </div>
  <svg class="wave" viewBox="0 0 1440 56" preserveAspectRatio="none" aria-hidden="true"><path d="M0 28C240 56 480 0 720 24s480 28 720 4V56H0z" fill="#FFF3DC"/></svg>
</header>

<main>
<section style="padding-top:0"><div class="wrap">
  <div class="cards2" id="status"></div>
</div></section>

<section id="menu" style="padding-top:32px"><div class="wrap">
  <div class="sec-head"><span class="chip-label">Katalog Pilihan</span><h2>Menu favorit Maky Chicken</h2><p class="muted">Dibuat dari ayam segar pilihan dengan bumbu marinasi rahasia gurih meresap sampai ke tulang.</p></div>
  <div class="cats" role="tablist" id="cats"></div>
  <div class="grid" id="grid"></div>
</div></section>

<section id="reservasi" style="background:var(--cream-50)"><div class="wrap">
  <div class="sec-head"><span class="chip-label">Reservasi Online</span><h2>Booking meja favoritmu</h2><p class="muted">DP Rp400.000 dibayar via QRIS. e-Voucher terbit setelah pembayaran terverifikasi.</p></div>
  <div class="formcard"><div class="stepper" id="stepper"></div><div class="card" id="rsv"></div></div>
</div></section>

<section id="contact"><div class="wrap">
  <div class="sec-head"><span class="chip-label">Contact Us</span><h2>Laporkan kendala</h2><p class="muted">Sampaikan komplain atau feedback setelah pesananmu selesai.</p></div>
  <div class="contact">
    <form class="card" id="kf" novalidate>
      <div class="field" data-f="nama"><label for="nm">Nama</label><input id="nm" class="in" autocomplete="name"><span class="help"></span></div>
      <div class="field" data-f="kontak"><label for="kt">Kontak (WhatsApp / email)</label><input id="kt" class="in"><span class="help"></span></div>
      <div class="field"><label>Jenis laporan</label><div class="chips" id="jenis"><button type="button" class="chip" aria-pressed="true">Komplain</button><button type="button" class="chip" aria-pressed="false">Feedback</button></div></div>
      <div class="field" data-f="pesan"><label for="ps">Isi pesan</label><textarea id="ps" class="in"></textarea><span class="help"></span></div>
      <div class="row" style="margin-top:8px"><button type="reset" class="btn btn--ghost">Batal</button><button class="btn" type="submit">Kirim</button></div>
    </form>
    <aside class="band"><h3>Butuh respon cepat?</h3><p>Hubungi Admin langsung lewat WhatsApp pada jam operasional.</p><a class="btn btn--info" id="wa" href="https://wa.me/628123456789" target="_blank" rel="noopener"></a></aside>
  </div>
</div></section>
</main>

<footer><div class="wrap"><div class="fgrid">
  <div><h4 style="color:var(--primary);font-size:22px">Maky Chicken</h4><p>Restoran ayam krispi &amp; geprek modern dengan cita rasa Nusantara otentik. Pemesanan meja digital dan reservasi keluarga.</p></div>
  <div><h4>Navigasi</h4><a href="#home">Beranda</a><a href="#menu">Daftar Menu</a><a href="#reservasi">Reservasi Lantai</a><a href="#contact">Bantuan &amp; FAQ</a></div>
  <div><h4>Jam Operasional</h4><p><b>Senin - Jumat:</b><br>10:00 - 22:00 WIB</p><p style="margin-top:8px"><b>Sabtu - Minggu:</b><br>09:00 - 23:00 WIB</p></div>
  <div><h4>Hubungi Kami</h4><p>Jl. Merdeka Kuliner No. 88, Jakarta Selatan</p><p>info@makychicken.id</p><p>WhatsApp Admin: +62 812-3456-7890</p></div>
</div><div class="copy">© 2026 Maky Chicken Indonesia. Seluruh hak cipta dilindungi.</div></div></footer>

<div class="cartbar" id="cartbar"><span><span id="cbN">0</span> item · <b id="cbT">Rp0</b></span><button class="btn btn--accent btn--sm" id="cbBtn">Lihat pesanan</button></div>
<div class="overlay" id="ov"><div class="modal" role="dialog" aria-modal="true" id="md"></div></div>
<div class="toast" id="toast" role="status"></div>

<script>
const P={leg:'M15 4a5 5 0 0 1-3 8l-6 7a2 2 0 0 1-3-3l7-6a5 5 0 0 1 5-6z',cart:'M3 4h2l2.5 11h9L19 8H6M9 20a1 1 0 1 0 0-.01M17 20a1 1 0 1 0 0-.01',qr:'M3 3h7v7H3zM14 3h7v7h-7zM3 14h7v7H3zM14 14h3v3h-3zM20 14v7M14 20h3',cal:'M5 4h14a1 1 0 0 1 1 1v15H4V5a1 1 0 0 1 1-1zM4 9h16M8 2v4M16 2v4',phone:'M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z',star:'M12 3l2.7 5.6 6.1.9-4.4 4.3 1 6.1L12 17l-5.4 2.9 1-6.1L3.2 9.500l6.1-.9z',doc:'M6 2h9l5 5v15H6zM14 2v6h6M9 13h8M9 17h8',dl:'M12 3v12M7 11l5 5 5-5M4 21h16',clock:'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18zM12 7v5l3 2',check:'M5 12l5 5 9-10',wa:'M21 12a8 8 0 0 1-11.5 7.2L3 21l1.8-5.5A8 8 0 1 1 21 12z',cup:'M5 8h12v6a5 5 0 0 1-5 5H10a5 5 0 0 1-5-5zM17 10h2a2 2 0 0 1 0 4h-2',list:'M4 6h16M4 12h16M4 18h10'};
const ic=n=>`<svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="${P[n]}"/></svg>`;
const rp=n=>'Rp'+n.toLocaleString('id-ID');const $=s=>document.querySelector(s);
let tT;function toast(m){const t=$('#toast');t.textContent=m;t.classList.add('on');clearTimeout(tT);tT=setTimeout(()=>t.classList.remove('on'),3000)}
const BADGE={'Baru':'pending','Diproses':'process','Selesai':'done','Menunggu Pembayaran':'pending','Lunas':'done','Gagal':'danger','Pending DP':'pending','Confirmed':'done','Tersedia':'done','Habis':'neutral'};
const badge=s=>`<span class="mc-badge b-${BADGE[s]}">${s}</span>`;
$('#lg').innerHTML=ic('leg');$('#cartIc').innerHTML=ic('cart');$('#phIc').innerHTML=ic('leg');
$('#tg').innerHTML=ic('star')+' Ayam geprek &amp; krispi juara no. 1';
$('#qrBtn').innerHTML=ic('qr')+' Pesan via QR';$('#rsBtn').innerHTML=ic('cal')+' Reservasi Table';$('#hbBtn').innerHTML=ic('phone')+' Hubungi Admin';
$('#wa').innerHTML=ic('wa')+' Hubungi Admin';

/* ===== Data ===== */
const CATS=[['Paket Ayam','leg'],['Ala Carte','list'],['Minuman','cup'],['Dessert','star']];
const MENU=[
 {id:1,c:'Paket Ayam',n:'Paket Ayam Geprek Juara',d:'Ayam goreng crispy digeprek dengan sambal korek bawang pedas mantap, nasi hangat dan lalapan.',h:28000,t:'Populer',a:true},
 {id:2,c:'Paket Ayam',n:'Paket Ayam Krispi Gurih',d:'Potongan paha atau dada renyah gurih bumbu rempah otentik, disajikan dengan nasi.',h:26000,t:'Favorit',a:true},
 {id:3,c:'Ala Carte',n:'Maky Chicken Burger',d:'Fillet paha ayam krispi tebal dengan keju leleh cheddar dan saus signature gurih.',h:32000,a:true},
 {id:4,c:'Ala Carte',n:'Chicken Tenders & Fries',d:'Strips dada ayam tanpa tulang renyah keemasan, kentang goreng gurih, dan saus.',h:25000,a:false},
 {id:5,c:'Minuman',n:'Es Teh Manis Jumbo',d:'Teh seduh segar dengan es batu dan gula pas, ukuran jumbo.',h:8000,a:true},
 {id:6,c:'Minuman',n:'Es Jeruk Peras',d:'Jeruk peras segar tanpa pengawet.',h:12000,a:true},
 {id:7,c:'Dessert',n:'Pisang Goreng Madu',d:'Pisang goreng renyah dengan siraman madu dan taburan keju.',h:15000,a:true}];
const MY={order:{id:'MC-8942',meja:12,waktu:'Hari ini, 13:10 WIB',status:'Diproses',bayar:'Menunggu Pembayaran',metode:'QRIS',items:[['Ayam Geprek Sambal Korek',2,28000],['Crispy Chicken Burger',1,32000],['Es Teh Manis Jumbo',2,8000]]},
 res:{k:'RES-1049',n:'Bpk. Budi Santoso',o:6,t:'25 Okt 2026',s:'18:30 WIB',l:'Lantai 1',st:'Confirmed'}};
let cart={},cat='Paket Ayam',tab=0;

/* ===== Status pesanan & reservasi (UC-06, UC-19, UC-01) ===== */
function renderStatus(){const o=MY.order,r=MY.res,tt=o.items.reduce((a,[,q,p])=>a+q*p,0);
 $('#status').innerHTML=`<article class="card"><header><span class="chip-label">Pesanan Meja ${o.meja}</span>${badge(o.status)}</header>
  <h3>Order #${o.id}</h3><p class="muted">Waktu pemesanan: ${o.waktu}</p>
  <ul class="items">${o.items.map(([n,q,p])=>`<li><span>${n} ×${q}</span><b style="font-size:15px">${rp(q*p)}</b></li>`).join('')}</ul>
  <div class="foot"><span class="muted">${ic('clock')} Estimasi: 12-15 menit</span><button class="btn btn--info btn--sm" id="invBtn">${ic('doc')} Lihat Invoice</button></div></article>
 <article class="card"><header><span class="chip-label">Booking Meja</span>${badge(r.st)}</header>
  <h3>Reservasi #${r.k}</h3><p class="muted">Atas nama: ${r.n} (${r.o} orang)</p>
  <div class="info3"><div><small>Tanggal</small><b>${r.t}</b></div><div><small>Slot waktu</small><b>${r.s}</b></div><div><small>Area</small><b>${r.l}</b></div></div>
  <div class="foot"><span class="muted">${ic('check')} DP lunas: Rp400.000</span><button class="btn btn--accent btn--sm" id="vcBtn">${ic('dl')} Unduh e-Voucher</button></div></article>`;
 $('#invBtn').onclick=()=>openInvoice();$('#vcBtn').onclick=()=>openVoucher(r)}

/* ===== Menu (UC-05) ===== */
function renderCats(){$('#cats').innerHTML=CATS.map(([c,i])=>`<button class="cat" role="tab" aria-selected="${c===cat}" data-c="${c}">${ic(i)}${c}</button>`).join('')}
function renderMenu(){$('#grid').innerHTML=MENU.filter(m=>m.c===cat).map(m=>`<article class="mcard ${m.a?'':'is-unavailable'}" data-d="${m.id}"><div class="ph">${ic('leg')}${m.t?`<span class="ribbon">${m.t}</span>`:''}</div>
  <div class="bd"><h4>${m.n}</h4><p>${m.d}</p><div class="bt"><span class="price">${rp(m.h)}</span>${m.a?`<button class="btn btn--accent btn--sm" data-add="${m.id}">+ Tambah</button>`:badge('Habis')}</div></div></article>`).join('')}
$('#cats').onclick=e=>{const b=e.target.closest('[data-c]');if(b){cat=b.dataset.c;renderCats();renderMenu()}};
$('#grid').onclick=e=>{const a=e.target.closest('[data-add]');
 if(a){const id=a.dataset.add;cart[id]=(cart[id]||0)+1;updCart();toast(MENU.find(m=>m.id==id).n+' ditambahkan.');return}
 const c=e.target.closest('[data-d]');if(c)openDetail(+c.dataset.d)};
const cnt=()=>Object.values(cart).reduce((a,b)=>a+b,0),sum=()=>Object.entries(cart).reduce((a,[id,q])=>a+q*MENU.find(m=>m.id==id).h,0);
function updCart(){const n=cnt();$('#cartN').textContent=n;$('#cbN').textContent=n;$('#cbT').textContent=rp(sum());$('#cartbar').classList.toggle('on',n>0)}

/* ===== Modal ===== */
const md=$('#md');function open(h){md.innerHTML=h;$('#ov').classList.add('on');const f=md.querySelector('button,input');f&&f.focus()}
function close(){$('#ov').classList.remove('on')}
$('#ov').onclick=e=>{if(e.target.id==='ov')close()};document.addEventListener('keydown',e=>{if(e.key==='Escape')close()});
function openDetail(id){const m=MENU.find(x=>x.id===id);
 open(`<div class="mcard" style="cursor:default;box-shadow:none;border:0"><div class="ph" style="border-radius:16px">${ic('leg')}</div></div><h3 style="margin-top:12px">${m.n}</h3><p class="muted">${m.d}</p><p class="price" style="margin:8px 0">${rp(m.h)}</p>
 <div class="row" style="margin-top:12px"><button class="btn btn--ghost" id="x">Tutup</button>${m.a?`<button class="btn btn--accent" id="ad">+ Tambah ke keranjang</button>`:badge('Habis')}</div>`);
 $('#x').onclick=close;const ad=$('#ad');if(ad)ad.onclick=()=>{cart[id]=(cart[id]||0)+1;updCart();close();toast('Ditambahkan ke keranjang.')}}
function openCart(){const ids=Object.keys(cart);
 if(!ids.length){open(`<h3>Keranjang kosong</h3><p class="muted">Pilih menu favoritmu terlebih dulu.</p><div class="row"><button class="btn" id="x">Pilih Menu</button></div>`);$('#x').onclick=()=>{close();location.hash='#menu'};return}
 open(`<h3>Ringkasan pesanan</h3><div class="field" style="margin-top:12px"><label for="mj">Nomor meja</label><select id="mj" class="in">${[...Array(20)].map((_,i)=>`<option>${i+1}</option>`).join('')}</select><span class="help">Nomor meja ada di stiker QR pada mejamu.</span></div>
 ${ids.map(id=>{const m=MENU.find(x=>x.id==id);return `<div class="line"><div><b style="font-weight:600">${m.n}</b><br><span class="muted">${rp(m.h)}</span></div><div class="qty"><button data-q="${id}|-1" aria-label="Kurangi">−</button><span style="min-width:20px;text-align:center">${cart[id]}</span><button data-q="${id}|1" aria-label="Tambah">+</button></div></div>`}).join('')}
 <div class="line" style="border:0"><span class="muted">Total</span><span class="price">${rp(sum())}</span></div>
 <div class="row"><button class="btn btn--outline" id="x">Ubah Pesanan</button><button class="btn" id="ok">Konfirmasi Pesanan</button></div>`);
 $('#x').onclick=close;$('#ok').onclick=()=>openPay($('#mj').value);
 md.querySelectorAll('[data-q]').forEach(b=>b.onclick=()=>{const[id,d]=b.dataset.q.split('|');cart[id]+=+d;if(cart[id]<=0)delete cart[id];updCart();openCart()})}
let metode='QRIS';
function openPay(meja){const total=sum();
 open(`<h3>Pembayaran</h3><p class="muted">Meja ${meja} · Total <b>${rp(total)}</b></p>
 <div class="choice" style="margin:16px 0" id="mt"><button class="pick" aria-pressed="${metode==='Tunai'}" data-m="Tunai"><h4>Tunai</h4><span class="muted">Bayar di kasir</span></button><button class="pick" aria-pressed="${metode==='QRIS'}" data-m="QRIS"><h4>QRIS</h4><span class="muted">Scan &amp; bayar</span></button></div>
 <div id="pb"></div><div class="row"><button class="btn btn--ghost" id="x">Kembali</button><button class="btn" id="go">Bayar</button></div>`);
 const pb=()=>{$('#pb').innerHTML=metode==='QRIS'?`<div class="qris"><div class="qr" role="img" aria-label="Kode QRIS"></div><div class="nominal">${rp(total)}</div><p class="muted">Pindai dengan aplikasi pembayaran favoritmu.</p></div>`:`<div class="alert al-info">Tunjukkan kode transaksi <b>MC-${Math.floor(9000+Math.random()*999)}</b> ke kasir untuk membayar.</div>`;$('#go').textContent=metode==='QRIS'?'Saya sudah bayar':'Kirim ke kasir'};pb();
 $('#mt').onclick=e=>{const b=e.target.closest('[data-m]');if(!b)return;metode=b.dataset.m;$('#mt').querySelectorAll('.pick').forEach(p=>p.setAttribute('aria-pressed',p===b));pb()};
 $('#x').onclick=openCart;
 $('#go').onclick=()=>{MY.order={id:'MC-'+Math.floor(9000+Math.random()*999),meja,waktu:'Baru saja',status:'Baru',bayar:metode==='QRIS'?'Lunas':'Menunggu Pembayaran',metode,items:Object.keys(cart).map(id=>{const m=MENU.find(x=>x.id==id);return[m.n,cart[id],m.h]})};
  cart={};updCart();renderStatus();close();toast('Pesanan terkirim ke kasir dan dapur.')}}
function openInvoice(){const o=MY.order,tt=o.items.reduce((a,[,q,p])=>a+q*p,0);
 open(`<h3>Invoice</h3><div class="receipt"><div class="line"><b>#${o.id}</b>${badge(o.bayar)}</div><p class="muted">Meja ${o.meja} · ${o.waktu} · ${o.metode}</p>
 ${o.items.map(([n,q,p])=>`<div class="line"><span>${n} ×${q}</span><span>${rp(q*p)}</span></div>`).join('')}<div class="line" style="border:0"><b>Total</b><span class="price" style="font-size:24px">${rp(tt)}</span></div></div>
 <div class="row"><button class="btn btn--ghost" id="x">Tutup</button><button class="btn btn--info" id="pr">${ic('dl')} Unduh</button></div>`);$('#x').onclick=close;$('#pr').onclick=()=>toast('Invoice diunduh.')}
function openVoucher(r){open(`<div class="voucher"><span class="chip-label">e-Voucher Reservasi</span><div class="code">${r.k}</div><p><b>${r.n}</b> · ${r.o} orang</p><p class="muted">${r.t}, ${r.s} · ${r.l}</p><p style="margin-top:8px">${badge('Confirmed')}</p></div>
 <div class="row"><button class="btn btn--ghost" id="x">Tutup</button><button class="btn btn--accent" id="dl">${ic('dl')} Unduh e-Voucher</button></div>`);$('#x').onclick=close;$('#dl').onclick=()=>toast('e-Voucher berhasil diunduh.')}
$('#cartBtn').onclick=$('#cbBtn').onclick=openCart;

/* ===== Reservasi 4 langkah (UC-01) ===== */
const R={tgl:'',slot:'',org:'',lantai:'',paket:0,gagal:false};const SL=['11:00','13:00','15:00','18:30','19:30'],FULL=['13:00'];
const STP=['Jadwal','Lantai','Paket menu','Bayar DP'];
function renderRsv(msg=''){$('#stepper').innerHTML=STP.map((s,i)=>`<div class="step ${i===tab?'on':i<tab?'ok':''}">${i+1}. ${s}</div>`).join('');const b=$('#rsv');
 if(tab===0)b.innerHTML=`${msg}<div class="field"><label for="tg2">Tanggal</label><input id="tg2" type="date" class="in" value="${R.tgl}"></div>
  <div class="field"><label>Slot waktu</label><div class="chips">${SL.map(s=>`<button class="chip" data-s="${s}" aria-pressed="${R.slot===s}" ${FULL.includes(s)?'disabled title="Jadwal penuh"':''}>${s}</button>`).join('')}</div><span class="help">Slot yang dicoret sudah penuh.</span></div>
  <div class="field"><label for="og">Jumlah orang</label><input id="og" type="number" min="1" max="20" class="in" value="${R.org}"></div><div class="row"><span></span><button class="btn" id="nx">Lanjut</button></div>`;
 if(tab===1)b.innerHTML=`${msg}<div class="choice">${['Lantai 1','Lantai 2'].map(l=>`<button class="pick" data-l="${l}" aria-pressed="${R.lantai===l}"><h4>${l}</h4><span class="muted">${l==='Lantai 1'?'Dekat kasir, suasana ramai':'Lebih tenang, cocok untuk keluarga'}</span></button>`).join('')}</div>
  <div class="row"><button class="btn btn--ghost" id="bk">Kembali</button><button class="btn" id="nx">Lanjut</button></div>`;
 if(tab===2)b.innerHTML=`${msg}<div class="grid" style="grid-template-columns:repeat(auto-fill,minmax(200px,1fr))">${MENU.filter(m=>m.c==='Paket Ayam').map(m=>`<button class="pick" data-p="${m.id}" aria-pressed="${R.paket===m.id}" style="text-align:left"><h4 style="font-size:16px">${m.n}</h4><span class="price" style="font-size:18px">${rp(m.h)}</span></button>`).join('')}</div>
  <div class="row"><button class="btn btn--ghost" id="bk">Kembali</button><button class="btn" id="nx">Lanjut</button></div>`;
 if(tab===3){const m=MENU.find(x=>x.id===R.paket),tot=m.h*R.org;b.innerHTML=`${R.gagal?'<div class="alert al-err">Pembayaran belum terverifikasi. Pastikan nominal sesuai lalu coba lagi.</div>':''}
  <div class="receipt"><div class="line"><span>${R.tgl} · ${R.slot} WIB</span><span>${R.lantai}</span></div><div class="line"><span>${m.n} ×${R.org}</span><span>${rp(tot)}</span></div><div class="line" style="border:0"><b>DP</b><span class="price">Rp400.000</span></div></div>
  <div class="qris"><div class="qr" role="img" aria-label="Kode QRIS DP"></div><div class="nominal">Rp400.000</div><p class="muted">Selesaikan dalam <b id="cd">05:00</b></p></div>
  <div class="row"><button class="btn btn--ghost" id="bk">Kembali</button><button class="btn" id="nx">${R.gagal?'Coba Lagi':'Bayar DP'}</button></div>`;cdStart()}
 const nx=$('#nx');if(nx)nx.onclick=next;const bk=$('#bk');if(bk)bk.onclick=()=>{tab--;renderRsv()}}
let cdT;function cdStart(){clearInterval(cdT);let s=300;cdT=setInterval(()=>{s--;const e=$('#cd');if(!e||s<0){clearInterval(cdT);return}e.textContent=String(Math.floor(s/60)).padStart(2,'0')+':'+String(s%60).padStart(2,'0')},1000)}
const err=t=>`<div class="alert al-err">${t}</div>`;
function next(){
 if(tab===0){R.tgl=$('#tg2').value;R.org=$('#og').value;if(!R.tgl||!R.slot||!(+R.org>0))return renderRsv(err('Lengkapi tanggal, slot waktu, dan jumlah orang (minimal 1).'));}
 if(tab===1&&!R.lantai)return renderRsv(err('Pilih Lantai 1 atau Lantai 2.'));
 if(tab===2&&!R.paket)return renderRsv(err('Pilih satu paket menu.'));
 if(tab===3){if(!R.gagal&&Math.random()<.0){}R.gagal=false;clearInterval(cdT);
  const k='RES-'+Math.floor(1100+Math.random()*800);const m={k,n:'Pelanggan',o:R.org,t:R.tgl,s:R.slot+' WIB',l:R.lantai,st:'Confirmed'};MY.res=m;renderStatus();
  $('#rsv').innerHTML=`<div class="alert al-ok">Pembayaran DP terverifikasi. Reservasimu Confirmed.</div><div class="voucher"><span class="chip-label">e-Voucher Reservasi</span><div class="code">${k}</div><p class="muted">${R.tgl}, ${R.slot} WIB · ${R.lantai} · ${R.org} orang</p><p style="margin-top:8px">${badge('Confirmed')}</p></div>
   <div class="row"><button class="btn btn--ghost" id="rs">Reservasi Baru</button><button class="btn btn--accent" id="dl">${ic('dl')} Unduh e-Voucher</button></div>`;
  $('#dl').onclick=()=>toast('e-Voucher berhasil diunduh.');$('#rs').onclick=()=>{Object.assign(R,{tgl:'',slot:'',org:'',lantai:'',paket:0});tab=0;renderRsv()};
  $('#stepper').innerHTML=STP.map((s,i)=>`<div class="step ok">${i+1}. ${s}</div>`).join('');toast('Reservasi dikonfirmasi.');return}
 tab++;renderRsv()}
$('#rsv').addEventListener('click',e=>{const s=e.target.closest('[data-s]'),l=e.target.closest('[data-l]'),p=e.target.closest('[data-p]');
 if(s||l||p){R.tgl=$('#tg2')?$('#tg2').value:R.tgl;R.org=$('#og')?$('#og').value:R.org;if(s)R.slot=s.dataset.s;if(l)R.lantai=l.dataset.l;if(p)R.paket=+p.dataset.p;renderRsv()}});

/* ===== Komplain & Feedback (UC-08) ===== */
$('#jenis').onclick=e=>{const b=e.target.closest('.chip');if(b)$('#jenis').querySelectorAll('.chip').forEach(c=>c.setAttribute('aria-pressed',c===b))};
$('#kf').addEventListener('submit',e=>{e.preventDefault();let ok=true;
 [['nama','#nm','Nama wajib diisi.'],['kontak','#kt','Kontak wajib diisi agar admin bisa menghubungi kamu.'],['pesan','#ps','Isi pesan wajib diisi, jelaskan kendala atau masukanmu.']].forEach(([k,s,m])=>{
  const f=document.querySelector(`[data-f=${k}]`),bad=!$(s).value.trim();f.classList.toggle('err',bad);f.querySelector('.help').textContent=bad?m:'';if(bad)ok=false});
 if(ok){e.target.reset();toast('Laporan terkirim. Status awal: Pending.')}});

/* ===== Init ===== */
renderStatus();renderCats();renderMenu();renderRsv();updCart();
</script>
</body>
</html>