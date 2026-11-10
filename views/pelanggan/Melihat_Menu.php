<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maky Chicken - Katalog Menu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen text-slate-800">

    
    <header class="bg-[#0B192C] text-white sticky top-0 z-40 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center font-bold text-xl text-white shadow-lg">
                        M
                    </div>
                    <div>
                        <h1 class="font-bold text-lg leading-tight tracking-wide">MAKY CHICKEN</h1>
                        <p class="text-xs text-slate-400">Restoran & Ayam Geprek Otentik</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="index.php?action=pesan_qr" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-xl text-sm font-semibold transition flex items-center space-x-2 shadow-md">
                        <i class="fa-solid fa-qrcode"></i>
                        <span>Pesan via QR</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

   
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        
        <!-- Search & Filter Bar -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Daftar Menu</h2>
                <p class="text-sm text-slate-500">Pilih hidangan favoritmu dari dapur kami</p>
            </div>
            
            
            <?php $activeKategori = $_GET['kategori'] ?? ''; ?>
            <div class="flex items-center space-x-2 overflow-x-auto pb-2 md:pb-0 scrollbar-none">
                <a href="index.php?action=melihat_menu" 
                   class="px-4 py-2 rounded-xl text-sm font-semibold whitespace-nowrap transition <?= empty($activeKategori) ? 'bg-[#0B192C] text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' ?>">
                    Semua
                </a>
                <a href="index.php?action=melihat_menu&kategori=Makanan" 
                   class="px-4 py-2 rounded-xl text-sm font-semibold whitespace-nowrap transition <?= $activeKategori === 'Makanan' ? 'bg-[#0B192C] text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' ?>">
                    <i class="fa-solid fa-drumstick-bite mr-1"></i> Makanan
                </a>
                <a href="index.php?action=melihat_menu&kategori=Minuman" 
                   class="px-4 py-2 rounded-xl text-sm font-semibold whitespace-nowrap transition <?= $activeKategori === 'Minuman' ? 'bg-[#0B192C] text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' ?>">
                    <i class="fa-solid fa-glass-water mr-1"></i> Minuman
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php if (!empty($menus)): ?>
                <?php foreach ($menus as $m): ?>
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between overflow-hidden group">
                        
                        <div class="relative bg-slate-100 h-40 flex items-center justify-center p-4">
                            
                            <?php if ($m['kategori'] === 'Minuman'): ?>
                                <i class="fa-solid fa-whiskey-glass text-6xl text-slate-300 group-hover:scale-110 transition duration-300"></i>
                            <?php else: ?>
                                <i class="fa-solid fa-utensils text-6xl text-slate-300 group-hover:scale-110 transition duration-300"></i>
                            <?php endif; ?>

                            <div class="absolute top-3 right-3">
                                <?php if ($m['status_tersedia']): ?>
                                    <span class="bg-emerald-50 text-emerald-600 border border-emerald-200 text-xs font-bold px-2.5 py-1 rounded-lg flex items-center space-x-1">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <span>Tersedia</span>
                                    </span>
                                <?php else: ?>
                                    <span class="bg-rose-50 text-rose-600 border border-rose-200 text-xs font-bold px-2.5 py-1 rounded-lg flex items-center space-x-1">
                                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                        <span>Stok Habis</span>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <span class="absolute bottom-3 left-3 bg-slate-900/70 text-white backdrop-blur-md text-xs font-medium px-2.5 py-0.5 rounded-md">
                                <?= htmlspecialchars($m['kategori']) ?>
                            </span>
                        </div>

                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="font-bold text-slate-900 text-base mb-1 group-hover:text-orange-500 transition">
                                    <?= htmlspecialchars($m['nama_menu']) ?>
                                </h3>
                                <p class="text-xs text-slate-500 line-clamp-2 mb-4 leading-relaxed">
                                    <?= htmlspecialchars($m['deskripsi']) ?>
                                </p>
                            </div>

                            <div>
                                <div class="flex items-baseline justify-between mb-4">
                                    <span class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Harga</span>
                                    <span class="text-lg font-extrabold text-orange-600">
                                        Rp <?= number_format($m['harga'], 0, ',', '.') ?>
                                    </span>
                                </div>

                                <?php if ($m['status_tersedia']): ?>
                                    <button onclick="openDetailModal('<?= htmlspecialchars(addslashes($m['nama_menu'])) ?>', 'Rp <?= number_format($m['harga'], 0, ',', '.') ?>', '<?= htmlspecialchars(addslashes($m['deskripsi'])) ?>', '<?= htmlspecialchars($m['kategori']) ?>')" 
                                            class="w-full bg-slate-100 hover:bg-[#0B192C] hover:text-white text-slate-700 py-2.5 rounded-xl font-semibold text-sm transition duration-200 flex items-center justify-center space-x-2">
                                        <i class="fa-solid fa-circle-info"></i>
                                        <span>Lihat Detail</span>
                                    </button>
                                <?php else: ?>
                                    <button disabled class="w-full bg-slate-100 text-slate-400 py-2.5 rounded-xl font-semibold text-sm cursor-not-allowed">
                                        Tidak Dapat Dipesan
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-slate-200">
                    <i class="fa-solid fa-box-open text-4xl text-slate-300 mb-3"></i>
                    <p class="text-slate-500 font-medium">Tidak ada menu yang ditemukan untuk kategori ini.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <div id="detailModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100 transform transition-all">
            <div class="flex items-start justify-between border-b border-slate-100 pb-4 mb-4">
                <div>
                    <span id="modalKategori" class="text-xs font-bold uppercase tracking-wider text-orange-500 bg-orange-50 px-2 py-0.5 rounded"></span>
                    <h3 id="modalTitle" class="text-xl font-bold text-slate-900 mt-1"></h3>
                </div>
                <button onclick="closeDetailModal()" class="text-slate-400 hover:text-slate-600 p-1">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <div class="mb-6">
                <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-1">Deskripsi Menu</label>
                <p id="modalDeskripsi" class="text-sm text-slate-600 leading-relaxed bg-slate-50 p-3 rounded-xl border border-slate-100"></p>
            </div>

            <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                <div>
                    <span class="text-xs text-slate-400 block">Harga Satuan</span>
                    <span id="modalHarga" class="text-xl font-extrabold text-orange-600"></span>
                </div>
                <a href="index.php?action=pesan_qr" class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md transition flex items-center space-x-2">
                    <i class="fa-solid fa-cart-plus"></i>
                    <span>Pesan Sekarang</span>
                </a>
            </div>
        </div>
    </div>

    <script>
        function openDetailModal(title, harga, deskripsi, kategori) {
            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalHarga').innerText = harga;
            document.getElementById('modalDeskripsi').innerText = deskripsi;
            document.getElementById('modalKategori').innerText = kategori;
            document.getElementById('detailModal').classList.remove('hidden');
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }
    </script>
</body>
</html>