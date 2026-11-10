<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Maky Chicken - UC-06: Pesan via QR (Meja <?= htmlspecialchars($noMeja) ?>)</title>
<!-- Google Fonts: Titan One & Poppins -->
<link href="https://fonts.googleapis.com" rel="preconnect">
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&amp;family=Titan+One&amp;display=swap" rel="stylesheet">
<!-- FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- Tailwind CSS CDN -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'mc-red': '#B3131B',
            'mc-red-dark': '#8E0E14',
            'mc-yellow': '#FFB21E',
            'mc-yellow-hover': '#E69F16',
            'mc-cream': '#FFF3DC',
            'mc-cream-light': '#FFF9F0',
            'mc-dark': '#2B2321',
            'mc-gray': '#6B5E59',
            'mc-gray-light': '#E8E1D9',
            'mc-info-bg': '#DCEBFA',
            'mc-info-border': '#1F5FAE',
            'mc-info-text': '#0F396B'
          },
          fontFamily: {
            brand: ['"Titan One"', 'cursive', 'sans-serif'],
            sans: ['Poppins', 'sans-serif']
          },
          boxShadow: {
            'mc-card': '0 8px 24px -4px rgba(179, 19, 27, 0.08), 0 2px 6px -1px rgba(0, 0, 0, 0.04)',
            'mc-bar': '0 -6px 20px rgba(43, 35, 33, 0.12)'
          }
        }
      }
    };
  </script>
<style data-purpose="brand-tokens">
    .font-brand {
      font-family: 'Titan One', cursive, sans-serif;
      letter-spacing: 0.02em;
    }
    .mc-price {
      font-family: 'Titan One', cursive, sans-serif;
      color: #B3131B;
      letter-spacing: -0.01em;
    }
  </style>
</head>
<body class="bg-mc-cream text-mc-dark font-sans antialiased min-h-screen flex flex-col pb-28">

<form action="index.php?action=submit_pesanan" method="POST" id="form-pesanan">
  <input type="hidden" name="no_meja" value="<?= htmlspecialchars($noMeja) ?>">
  <input type="hidden" name="jenis_pesanan" value="Dine In">

  <!-- BEGIN: TopNavbar -->
  <header class="bg-mc-red text-white shadow-md sticky top-0 z-40">
    <div class="max-w-[1280px] mx-auto px-6 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <a class="flex items-center gap-2 group" href="index.php?action=melihat_menu">
          <div class="w-10 h-10 rounded-full bg-mc-yellow flex items-center justify-center text-mc-red font-brand text-2xl shadow-inner group-hover:scale-105 transition-transform">
            M
          </div>
          <span class="font-brand text-2xl tracking-wide text-white drop-shadow-sm">MAKY CHICKEN</span>
        </a>
      </div>
      <a href="index.php?action=melihat_menu" class="text-xs bg-white/10 hover:bg-white/20 text-white px-3 py-1.5 rounded-lg transition font-medium">
        <i class="fa-solid fa-utensils mr-1"></i> Lihat Katalog
      </a>
    </div>
  </header>
  <!-- END: TopNavbar -->

  <!-- BEGIN: HeroHeader -->
  <section class="bg-mc-red text-white pt-6 pb-10 px-6 shadow-inner relative overflow-hidden">
    <div class="absolute -top-24 right-1/4 w-96 h-96 bg-white/5 rounded-full pointer-events-none"></div>
    <div class="max-w-[1280px] mx-auto flex flex-col items-center justify-center text-center relative z-10">
      
      <!-- Table Badge Indicator Dinamis -->
      <div class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-mc-yellow text-mc-dark text-xs font-bold tracking-wider uppercase mb-2.5 shadow-sm">
        <span>MEJA <?= htmlspecialchars($noMeja) ?> • DINE IN</span>
      </div>

      <div class="w-full flex items-center justify-between relative">
        <div class="w-32 hidden md:block"></div>
        <h1 class="font-brand text-3xl md:text-5xl tracking-normal text-white drop-shadow-md mx-auto">
          PESAN DARI MEJA
        </h1>
        <div class="flex items-center gap-2 px-3.5 py-1 rounded-full bg-white/95 text-mc-dark text-xs font-bold tracking-wide shadow-sm">
          <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
          <span>QR Terbaca / Database Connected</span>
        </div>
      </div>
    </div>
  </section>
  <!-- END: HeroHeader -->

  <!-- BEGIN: MainContent -->
  <main class="max-w-[1280px] mx-auto px-6 -mt-4 w-full flex-grow relative z-20">
    <!-- Notification Banner -->
    <section class="bg-mc-info-bg border-2 border-mc-info-border rounded-xl p-3.5 mb-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-mc-info-text shadow-sm">
      <div class="flex items-center gap-2 text-sm font-medium">
        <svg class="w-5 h-5 text-mc-info-border flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
        </svg>
        <span>Pilih menu dan jumlahnya. Pesanan akan langsung tersimpan ke database untuk <strong>Meja <?= htmlspecialchars($noMeja) ?></strong>.</span>
      </div>
      <div class="flex items-center gap-2 text-xs font-bold bg-white/70 px-3 py-1 rounded-lg border border-mc-info-border/30">
        <span>Ringkasan Keranjang</span>
        <span class="w-1.5 h-1.5 rounded-full bg-mc-info-border"></span>
        <span id="quick-cart-summary">0 Menu Terpilih</span>
      </div>
    </section>

    <!-- Menu Grid: Loop Data Asli Database (seed.sql) -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" data-purpose="menu-catalog">
      <?php if (!empty($menus)): ?>
        <?php foreach ($menus as $m): ?>
          <?php $id = $m['id_menu']; ?>
          
          <?php if ($m['status_tersedia']): ?>
            <!-- Card Menu Available -->
            <article class="bg-white rounded-2xl overflow-hidden p-3.5 flex flex-col justify-between shadow-mc-card border border-mc-red/10 hover:shadow-lg transition-all duration-200">
              <div>
                <div class="relative w-full aspect-[4/3] rounded-xl overflow-hidden bg-amber-50 mb-3 flex items-center justify-center">
                  <?php if ($m['kategori'] === 'Minuman'): ?>
                    <i class="fa-solid fa-glass-water text-5xl text-amber-300"></i>
                  <?php else: ?>
                    <i class="fa-solid fa-drumstick-bite text-5xl text-amber-400"></i>
                  <?php endif; ?>
                </div>

                <h3 class="font-bold text-base text-mc-dark mb-1 line-clamp-1 leading-snug">
                  <?= htmlspecialchars($m['nama_menu']) ?>
                </h3>
                <p class="text-xs text-mc-gray line-clamp-2 leading-relaxed mb-3">
                  <?= htmlspecialchars($m['deskripsi']) ?>
                </p>

                <!-- Hidden Input Harga -->
                <input type="hidden" name="items[<?= $id ?>][harga]" value="<?= $m['harga'] ?>">

                <!-- Field Catatan Khusus Pelanggan -->
                <div class="mb-3">
                  <input type="text" 
                         name="items[<?= $id ?>][catatan]" 
                         placeholder="Catatan (misal: Pedas level 3 / No Es)" 
                         class="w-full text-xs px-2.5 py-1.5 rounded-lg border border-gray-200 focus:border-mc-red focus:ring-1 focus:ring-mc-red bg-slate-50">
                </div>
              </div>

              <div>
                <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                  <span class="mc-price text-lg">Rp<?= number_format($m['harga'], 0, ',', '.') ?></span>
                  
                  <!-- Control Counter Qty (Interaktif JS) -->
                  <div class="flex items-center gap-1 bg-mc-cream px-2 py-1 rounded-full border border-mc-yellow">
                    <button type="button" onclick="changeQty(<?= $id ?>, -1, <?= $m['harga'] ?>)" class="w-6 h-6 rounded-full bg-white text-mc-dark font-bold text-xs flex items-center justify-center hover:bg-gray-100 shadow-sm">-</button>
                    <input type="number" 
                           id="qty-<?= $id ?>" 
                           name="items[<?= $id ?>][jumlah]" 
                           value="0" 
                           min="0" 
                           readonly
                           class="w-8 text-center text-xs font-bold bg-transparent border-none p-0 focus:ring-0">
                    <button type="button" onclick="changeQty(<?= $id ?>, 1, <?= $m['harga'] ?>)" class="w-6 h-6 rounded-full bg-mc-yellow text-mc-dark font-bold text-xs flex items-center justify-center hover:bg-mc-yellow-hover shadow-sm">+</button>
                  </div>
                </div>
              </div>
            </article>

          <?php else: ?>
            <!-- Card Menu Stok Habis -->
            <article class="bg-white/80 rounded-2xl overflow-hidden p-3.5 flex flex-col justify-between shadow-sm border border-gray-200 opacity-75">
              <div>
                <div class="relative w-full aspect-[4/3] rounded-xl overflow-hidden bg-gray-100 mb-3 flex items-center justify-center">
                  <i class="fa-solid fa-ban text-4xl text-gray-300"></i>
                  <div class="absolute inset-0 bg-black/10 flex items-center justify-center">
                    <span class="bg-black/70 text-white font-bold text-xs px-2.5 py-1 rounded-full uppercase tracking-wider">Stok Habis</span>
                  </div>
                </div>
                <h3 class="font-bold text-base text-mc-dark/70 mb-1 line-clamp-1 leading-snug"><?= htmlspecialchars($m['nama_menu']) ?></h3>
                <p class="text-xs text-mc-gray/70 line-clamp-2 leading-relaxed mb-4"><?= htmlspecialchars($m['deskripsi']) ?></p>
              </div>
              <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                <span class="font-brand text-lg text-gray-400">Rp<?= number_format($m['harga'], 0, ',', '.') ?></span>
                <div class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-gray-100 text-gray-500 font-semibold text-xs border border-gray-300">
                  <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                  <span>Habis</span>
                </div>
              </div>
            </article>
          <?php endif; ?>

        <?php endforeach; ?>
      <?php endif; ?>
    </section>
  </main>
  <!-- END: MainContent -->

  <!-- BEGIN: FloatingOrderBar -->
  <aside class="fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-t border-mc-red/20 shadow-mc-bar py-3.5 px-6">
    <div class="max-w-[1280px] mx-auto flex items-center justify-between gap-4">
      <div class="flex items-center gap-4">
        <div class="bg-mc-cream border border-mc-yellow rounded-xl px-3.5 py-1.5 text-center">
          <div class="text-[10px] uppercase font-bold text-mc-gray">Meja Pemesan</div>
          <div class="font-brand text-mc-red text-base leading-none">NO. <?= htmlspecialchars($noMeja) ?></div>
        </div>
        <div class="border-l border-gray-200 pl-4">
          <div class="text-xs text-mc-gray flex items-center gap-1.5 font-medium">
            <span>Ringkasan Pesanan:</span>
            <span class="font-bold text-mc-dark" id="cart-item-count">0 Item</span>
          </div>
          <div class="text-lg font-bold text-mc-dark flex items-baseline gap-2">
            <span class="text-xs text-mc-gray">Total Tagihan:</span>
            <span class="font-brand text-xl text-mc-red" id="cart-total-price">Rp0</span>
          </div>
        </div>
      </div>
      
      <div class="flex items-center gap-3">
        <button class="px-4 py-2.5 rounded-xl border border-gray-300 text-xs font-semibold text-mc-gray hover:bg-gray-50 transition-colors" onclick="resetOrder()" type="button">
          Reset Pilihan
        </button>
        <button type="submit" id="btn-submit-order" disabled class="px-6 py-2.5 rounded-xl bg-gray-300 text-gray-500 font-brand text-sm tracking-wide shadow-md transition-all flex items-center gap-2 cursor-not-allowed">
          <span>PESAN SEKARANG</span>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
          </svg>
        </button>
      </div>
    </div>
  </aside>
  <!-- END: FloatingOrderBar -->
</form>

<!-- BEGIN: SiteFooter -->
<footer class="mt-16 border-t border-mc-gray-light bg-mc-cream-light py-6 text-center text-xs text-mc-gray">
  <div class="max-w-[1280px] mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-3">
    <div class="flex items-center gap-2">
      <span class="font-brand text-mc-red text-sm">MAKY CHICKEN RESTO</span>
      <span class="text-gray-400">|</span>
      <span>Sistem Pemesanan Mandiri Meja</span>
    </div>
    <div>
      <span>Terhubung ke DB db_restoran • Kasir &amp; Dapur Real-time</span>
    </div>
  </div>
</footer>
<!-- END: SiteFooter -->

<!-- BEGIN: Cart Interactivity Script -->
<script>
    let cartItems = {};

    function formatRupiah(number) {
      return 'Rp' + number.toLocaleString('id-ID');
    }

    function changeQty(id, delta, price) {
      const inputEl = document.getElementById('qty-' + id);
      let currentQty = parseInt(inputEl.value) || 0;
      let newQty = currentQty + delta;
      
      if (newQty < 0) newQty = 0;
      inputEl.value = newQty;

      if (newQty > 0) {
        cartItems[id] = { qty: newQty, price: price };
      } else {
        delete cartItems[id];
      }

      updateCartUI();
    }

    function updateCartUI() {
      let totalQty = 0;
      let totalPrice = 0;

      for (let id in cartItems) {
        totalQty += cartItems[id].qty;
        totalPrice += (cartItems[id].qty * cartItems[id].price);
      }

      document.getElementById('cart-item-count').innerText = `${totalQty} Item`;
      document.getElementById('cart-total-price').innerText = formatRupiah(totalPrice);
      document.getElementById('quick-cart-summary').innerText = `${totalQty} Menu Terpilih`;

      const submitBtn = document.getElementById('btn-submit-order');
      if (totalQty > 0) {
        submitBtn.disabled = false;
        submitBtn.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
        submitBtn.classList.add('bg-mc-red', 'hover:bg-mc-red-dark', 'text-white', 'cursor-pointer');
      } else {
        submitBtn.disabled = true;
        submitBtn.classList.remove('bg-mc-red', 'hover:bg-mc-red-dark', 'text-white', 'cursor-pointer');
        submitBtn.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
      }
    }

    function resetOrder() {
      for (let id in cartItems) {
        const inputEl = document.getElementById('qty-' + id);
        if (inputEl) inputEl.value = 0;
      }
      cartItems = {};
      updateCartUI();
    }
</script>

</body>
</html>