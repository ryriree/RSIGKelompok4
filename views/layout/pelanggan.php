<?php // Layout pelanggan. Variabel dari dispatcher: $content ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Maky Chicken</title>
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
h1,h2,h3,h4,.disp{font-family:var(--font-display);font-weight:400;text-transform:uppercase;line-height:1.05}
.wrap{max-width:1200px;margin:0 auto;padding:0 16px}
.topbar{background:var(--primary);padding:16px 0}
.nav{background:#fff;border-radius:24px;box-shadow:var(--shadow-2);display:flex;align-items:center;justify-content:space-between;gap:16px;padding:10px 16px 10px 20px;flex-wrap:wrap}
.brand{display:flex;align-items:center;gap:12px;font-family:var(--font-display);color:var(--primary);font-size:22px;text-transform:uppercase;text-decoration:none}
.logo{width:40px;height:40px;border-radius:50%;background:var(--accent);color:var(--red-900);display:grid;place-items:center;border:2px solid var(--primary)}
.links{display:flex;gap:8px;flex-wrap:wrap}
.links a{min-height:44px;padding:0 14px;display:inline-flex;align-items:center;font-weight:700;font-size:13px;letter-spacing:.08em;text-transform:uppercase;color:var(--text);text-decoration:none;border-bottom:3px solid transparent}
.links a:hover,.links a.on{color:var(--primary);border-color:var(--primary)}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;min-height:44px;padding:0 24px;border-radius:999px;border:1.5px solid transparent;font-weight:700;font-size:13px;letter-spacing:.08em;text-transform:uppercase;cursor:pointer;background:var(--primary);color:var(--cream-50);text-decoration:none;transition:transform .15s,background .15s}
.btn:hover{transform:translateY(-1px);background:var(--primary-hover)}
.btn:disabled{opacity:.45;cursor:not-allowed;transform:none}
.btn--accent{background:var(--accent);color:var(--ink-900)}.btn--accent:hover{background:var(--yellow-600);color:var(--ink-900)}
.btn--info{background:var(--info)}
.btn--outline{background:transparent;color:var(--primary);border-color:var(--primary)}.btn--outline:hover{background:var(--red-100)}
.btn--ghost{background:transparent;color:var(--text)}.btn--ghost:hover{background:var(--cream-200)}
.btn--sm{padding:0 16px;font-size:12px}
footer.site{background:var(--cream-50);border-top:1.5px solid var(--border);padding:32px 0 20px;margin-top:40px}
footer.site p{font-size:13px;color:var(--text-muted)}
footer.site .t{font-family:var(--font-display);color:var(--primary);font-size:20px;text-transform:uppercase}
.toast{position:fixed;left:50%;top:16px;transform:translate(-50%,-150%);background:var(--ink-900);color:var(--cream-50);border-radius:16px;padding:12px 24px;box-shadow:var(--shadow-3);z-index:60;transition:transform .2s;max-width:calc(100% - 32px)}
.toast.on{transform:translate(-50%,0)}
@media(max-width:860px){.links{order:3;width:100%;overflow-x:auto;flex-wrap:nowrap}}
@media print{.topbar,footer.site,.toast{display:none!important}}
</style>
</head>
<body>
<header class="topbar"><div class="wrap">
  <nav class="nav" aria-label="Navigasi utama">
    <a class="brand" href="index.php?page=home"><span class="logo">M</span>Maky Chicken</a>
    <div class="links">
      <a href="index.php?page=home">Home</a>
      <a href="index.php?page=menu-pelanggan">Menu</a>
      <a href="index.php?page=pesan">Pesan</a>
      <a href="index.php?page=form-reservasi">Reservasi</a>
      <a href="index.php?page=form-kendala">Contact Us</a>
    </div>
  </nav>
</div></header>

<main class="wrap"><?= $content ?></main>

<footer class="site"><div class="wrap">
  <div class="t">Maky Chicken</div>
  <p>Jl. Merdeka Kuliner No. 88 · info@makychicken.id · © 2026 Maky Chicken Indonesia</p>
</div></footer>

<div class="toast" id="toast" role="status"></div>
<script>
let _tt; function toast(m){const t=document.getElementById('toast');t.textContent=m;t.classList.add('on');clearTimeout(_tt);_tt=setTimeout(()=>t.classList.remove('on'),3000)}
</script>
</body>
</html>