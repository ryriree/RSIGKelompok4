<?php
// Ambil Notifikasi Session jika ada
$notifSukses = $_SESSION['notif_sukses'] ?? null;
$notifError  = $_SESSION['notif_error'] ?? null;
unset($_SESSION['notif_sukses'], $_SESSION['notif_error']);

// Cek data user terlogin (opsional)
$namaLoggedIn = $_SESSION['user']['nama'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us & Melaporkan Kendala - Maky Chicken</title>
    
    <!-- Google Fonts: Titan One & Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Titan+One&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- CSS Design System Project -->
    <link rel="stylesheet" href="css/maky.css">

    <style>
    :root {
        --red-100: #FBE0DD; --red-600: #B3131B; --red-700: #8E0F18; --red-900: #5E0A10;
        --cream-50: #FFFAF0; --cream-100: #FFF3DC; --cream-200: #FBE6BE; --cream-300: #F2D49A;
        --yellow-100: #FFF0C2; --yellow-500: #FFB21E; --yellow-600: #F28C0F;
        --blue-100: #DCEBFA; --blue-600: #1F5FAE; --green-600: #1E8E4E; --green-100: #D9F2E3;
        --ink-900: #2A1010; --ink-600: #6B4A45;
        --primary: var(--red-600); --font-display: 'Titan One', sans-serif; --font-body: 'Poppins', sans-serif;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: var(--font-body); background: var(--cream-50); color: var(--ink-900); line-height: 1.5; font-size: 13px; }
    h1, h2, h3, .disp { font-family: var(--font-display); font-weight: 400; text-transform: uppercase; }

    /* ========================================================= */
    /* NAVBAR HEADER IDENTIK DASHBOARD.PHP                       */
    /* ========================================================= */
    .public-nav { display: flex; justify-content: space-between; align-items: center; padding: 16px 48px; background: var(--cream-50); border-bottom: 1.5px solid var(--cream-200); position: sticky; top: 0; z-index: 50; }
    .nav-logo { font-family: var(--font-display); font-size: 20px; color: var(--red-600); text-decoration: none; letter-spacing: .04em; }
    .nav-links { display: flex; gap: 24px; align-items: center; }
    .nav-links a { text-decoration: none; color: var(--ink-900); font-weight: 600; font-size: 13px; transition: color .2s; }
    .nav-links a:hover { color: var(--red-600); }
    .nav-links a.active { background: var(--red-600); color: #fff; padding: 6px 18px; border-radius: 999px; }
    .cart-btn { background: var(--yellow-500); color: var(--ink-900); padding: 6px 18px; border-radius: 999px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 8px; font-size: 12px; }

    /* ========================================================= */
    /* LAYOUT KONTEN UTAMA FORM KENDALA UI-08                    */
    /* ========================================================= */
    .content-container { max-width: 680px; margin: 32px auto 64px; padding: 0 16px; }

    /* BLUE BANNER MAKSIMAL 1X24 JAM (UI-08) */
    .blue-notice-banner { background: var(--blue-100); color: var(--blue-600); border-radius: 14px; padding: 12px 18px; font-size: 11.5px; font-weight: 600; display: flex; align-items: center; gap: 10px; margin-bottom: 24px; border: 1px solid rgba(31,95,174,0.2); }

    .hero-title-section { text-align: center; margin-bottom: 28px; }
    .tag-pill { background: var(--red-100); color: var(--red-600); border-radius: 999px; padding: 3px 14px; font-size: 10px; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; display: inline-block; margin-bottom: 8px; }
    .hero-title-section h1 { font-size: 34px; color: var(--red-600); letter-spacing: .02em; line-height: 1.1; margin-bottom: 8px; }
    .hero-title-section p { color: var(--ink-600); font-size: 12px; max-width: 480px; margin: 0 auto; }

    /* FORM CARD STYLING */
    .form-card { background: #fff; border: 1.5px solid var(--cream-300); border-radius: 24px; padding: 32px; box-shadow: 0 4px 16px rgba(142,15,24,0.06); }
    .form-group { margin-bottom: 20px; }
    .form-label { display: block; font-weight: 700; font-size: 12px; margin-bottom: 6px; color: var(--ink-900); }
    .form-control { width: 100%; min-height: 44px; border-radius: 14px; border: 1.5px solid var(--cream-300); background: var(--cream-50); padding: 0 16px; font-family: var(--font-body); font-size: 13px; font-weight: 500; outline: none; transition: border-color .2s; }
    .form-control:focus { border-color: var(--red-600); background: #fff; }
    textarea.form-control { min-height: 110px; padding: 12px 16px; resize: vertical; }

    /* RADIO PILL SELECTOR JENIS LAPORAN (UI-08) */
    .radio-group { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 4px; }
    .radio-pill { position: relative; }
    .radio-pill input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }
    .radio-pill label { display: inline-flex; align-items: center; gap: 8px; padding: 8px 18px; border-radius: 999px; border: 1.5px solid var(--cream-300); background: #fff; font-size: 11.5px; font-weight: 700; cursor: pointer; color: var(--ink-900); transition: all .2s ease; }
    .radio-pill input[type="radio"]:checked + label { background: var(--red-600); color: #fff; border-color: var(--red-600); box-shadow: 0 4px 12px rgba(179,19,27,0.25); }

    /* UPLOAD FILE ZONE (UI-08) */
    .upload-box { border: 2px dashed var(--cream-300); border-radius: 16px; padding: 24px 16px; text-align: center; background: var(--cream-50); cursor: pointer; transition: all .2s ease; }
    .upload-box:hover { border-color: var(--yellow-500); background: var(--cream-100); }
    .upload-icon { width: 36px; height: 36px; border-radius: 50%; background: var(--yellow-100); color: var(--yellow-600); display: grid; place-items: center; margin: 0 auto 8px; font-size: 16px; }

    /* BUTTON ACTION */
    .btn-submit { width: 100%; min-height: 48px; border-radius: 999px; background: var(--red-600); color: #fff; font-family: var(--font-display); font-size: 14px; border: 0; cursor: pointer; letter-spacing: .06em; text-transform: uppercase; transition: background .2s; margin-top: 8px; display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; }
    .btn-submit:hover { background: var(--red-700); }
    .btn-cancel { display: block; text-align: center; color: var(--ink-600); font-weight: 700; font-size: 11px; letter-spacing: .08em; text-transform: uppercase; text-decoration: none; margin-top: 14px; }
    .btn-cancel:hover { color: var(--red-600); }

    /* WHATSAPP ADMIN BOX (UI-08) */
    .help-box-wa { background: var(--cream-100); border: 1.5px solid var(--cream-200); border-radius: 20px; padding: 20px 24px; margin-top: 28px; display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap; }
    .help-wa-left { display: flex; align-items: center; gap: 14px; }
    .help-wa-icon { width: 42px; height: 42px; border-radius: 12px; background: var(--green-600); color: #fff; display: grid; place-items: center; font-size: 20px; flex: none; }
    .help-wa-text b { font-size: 13px; color: var(--ink-900); display: block; line-height: 1.2; }
    .help-wa-text small { font-size: 11px; color: var(--ink-600); }
    .btn-wa-action { background: var(--green-600); color: #fff; padding: 10px 20px; border-radius: 999px; font-weight: 700; font-size: 12px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: background .2s; }
    .btn-wa-action:hover { background: #166D3B; color: #fff; }

    /* ========================================================= */
    /* FOOTER PUBLIK IDENTIK DASHBOARD.PHP                       */
    /* ========================================================= */
    .footer-public { background: var(--cream-100); border-top: 1.5px solid var(--cream-200); padding: 48px 48px 24px; margin-top: 64px; }
    .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1.5fr; gap: 32px; margin-bottom: 40px; }
    .footer-col h4 { font-family: var(--font-display); font-size: 14px; color: var(--red-600); margin-bottom: 12px; }
    .footer-col p, .footer-col li { font-size: 11px; color: var(--ink-600); list-style: none; line-height: 1.6; }
    .footer-col ul { display: flex; flex-direction: column; gap: 6px; }
    .footer-col a { text-decoration: none; color: inherit; }
    .footer-bottom { border-top: 1px solid var(--cream-300); padding-top: 20px; display: flex; justify-content: space-between; font-size: 10px; color: var(--ink-600); }
    </style>
</head>
<body>

    <!-- NAVBAR PUBLIC HEADER (PERSIS IDENTIK DASHBOARD.PHP) -->
    <header class="public-nav">
        <a href="index.php?page=home" class="nav-logo">MAKY CHICKEN</a>
        <nav class="nav-links">
            <a href="index.php?page=home">Home</a>
            <a href="index.php?page=menu">Menu</a>
            <a href="index.php?page=reservasi">Reservasi</a>
            <!-- TAMBAHKAN CLASS ACTIVE PADA CONTACT US -->
            <a href="index.php?page=form-kendala" class="active">Contact Us</a>
        </nav>
        <a href="index.php?page=keranjang" class="cart-btn">
            <i class="bi bi-bag-fill"></i> Keranjang (2)
        </a>
    </header>

    <!-- MAIN CONTENT FORM KENDALA (UI-08) -->
    <main class="content-container">
        <!-- BANNER INFORMASI MAKSIMAL 1X24 JAM -->
        <div class="blue-notice-banner">
            <i class="bi bi-info-circle-fill fs-5"></i>
            <span>Laporan Anda akan ditindaklanjuti langsung oleh manajemen Maky Chicken maksimal 1x24 jam kerja.</span>
        </div>

        <!-- HERO TITLE -->
        <div class="hero-title-section">
            <span class="tag-pill">● MASUKAN PELANGGAN</span>
            <h1 class="disp">SUARA ANDA SANGAT BERHARGA</h1>
            <p>Bantu kami meningkatkan kualitas makanan dan pelayanan Maky Chicken. Sampaikan komplain atau saran Anda di bawah ini.</p>
        </div>

        <!-- NOTIFIKASI UMPAN BALIK -->
        <?php if ($notifSukses): ?>
            <div style="background:var(--green-100); color:var(--green-600); border:1px solid var(--green-600); border-radius:14px; padding:14px; margin-bottom:20px; font-weight:600; font-size:12px;">
                <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($notifSukses); ?>
            </div>
        <?php endif; ?>

        <?php if ($notifError): ?>
            <div style="background:var(--red-100); color:var(--red-600); border:1px solid var(--red-600); border-radius:14px; padding:14px; margin-bottom:20px; font-weight:600; font-size:12px;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($notifError); ?>
            </div>
        <?php endif; ?>

        <!-- KARTU FORMULIR PELAPORAN KENDALA UI-08 -->
        <div class="form-card">
            <form action="index.php?page=kirim-kendala" method="POST" enctype="multipart/form-data">
                
                <!-- NAMA LENGKAP -->
                <div class="form-group">
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text" name="nama" class="form-control" placeholder="Masukkan nama Anda (e.g. Budi Santoso)" value="<?= htmlspecialchars($namaLoggedIn); ?>" required>
                    <small style="font-size:10px; color:var(--ink-600); margin-top:4px; display:block;">Gunakan nama sesuai pemesanan bila terkait pesanan tertentu.</small>
                </div>

                <!-- NOMOR WHATSAPP / KONTAK -->
                <div class="form-group">
                    <label class="form-label">Nomor WhatsApp / Kontak *</label>
                    <input type="text" name="kontak" class="form-control" placeholder="Contoh: 08123456789" required>
                    <small style="font-size:10px; color:var(--ink-600); margin-top:4px; display:block;">Kami akan menghubungi nomor ini untuk tindak lanjut resolusi.</small>
                </div>

                <!-- JENIS LAPORAN (RADIO PILL UI-08) -->
                <div class="form-group">
                    <label class="form-label">Jenis Laporan *</label>
                    <div class="radio-group">
                        <div class="radio-pill">
                            <input type="radio" id="j1" name="jenis_laporan" value="Komplain Pelayanan / Rasa" checked>
                            <label for="j1"><i class="bi bi-exclamation-circle-fill"></i> Komplain Pelayanan / Rasa</label>
                        </div>
                        <div class="radio-pill">
                            <input type="radio" id="j2" name="jenis_laporan" value="Feedback & Saran">
                            <label for="j2"><i class="bi bi-chat-left-heart-fill"></i> Feedback & Saran</label>
                        </div>
                        <div class="radio-pill">
                            <input type="radio" id="j3" name="jenis_laporan" value="Pertanyaan Menu & Fasilitas">
                            <label for="j3"><i class="bi bi-question-circle-fill"></i> Pertanyaan Menu & Fasilitas</label>
                        </div>
                    </div>
                </div>

                <!-- ISI PESAN / LAPORAN -->
                <div class="form-group">
                    <label class="form-label">Isi Pesan / Laporan *</label>
                    <textarea name="isi_pesan" class="form-control" placeholder="Tuliskan detail komplain atau masukan Anda secara jelas (misal: rasa sambal, ketepatan pesanan, kebersihan area meja)..." required></textarea>
                </div>

                <!-- LAMPIRAN FOTO / STRUK (UI-08) -->
                <div class="form-group">
                    <label class="form-label">Lampiran Foto / Struk Pembayaran (Opsional)</label>
                    <div class="upload-box" onclick="document.getElementById('fileInput').click()">
                        <div class="upload-icon">
                            <i class="bi bi-image"></i>
                        </div>
                        <b style="display:block; font-size:12px; color:var(--ink-900);">Klik untuk unggah foto makanan atau struk transaksi</b>
                        <small style="font-size:10px; color:var(--ink-600);">Format PNG, JPG maks 5MB</small>
                        <input type="file" id="fileInput" name="lampiran" style="display:none;" onchange="showFileName(this)">
                        <div id="fileSelected" style="margin-top:8px; font-weight:700; font-size:11px; color:var(--red-600); display:none;"></div>
                    </div>
                </div>

                <!-- TOMBOL EKSEKUSI KIRIM LAPORAN -->
                <button type="submit" class="btn-submit">
                    KIRIM LAPORAN <i class="bi bi-send-fill ms-1"></i>
                </button>
                <a href="index.php?page=home" class="btn-cancel">BATAL</a>
            </form>
        </div>

        <!-- WHATSAPP ADMIN BOX (UI-08) -->
        <div class="help-box-wa">
            <div class="help-wa-left">
                <div class="help-wa-icon">
                    <i class="bi bi-chat-dots-fill"></i>
                </div>
                <div class="help-wa-text">
                    <b>Butuh Bantuan Langsung?</b>
                    <small>Hubungi Admin Maky Chicken via WhatsApp untuk respon instan.</small>
                </div>
            </div>
            
            <a href="index.php?page=hubungi-wa" target="_blank" class="btn-wa-action">
                <i class="bi bi-whatsapp fs-6"></i> Chat WhatsApp Admin Sekarang
            </a>
        </div>
    </main>

    <!-- FOOTER PUBLIC (PERSIS IDENTIK DASHBOARD.PHP) -->
    <footer class="footer-public">
        <div class="footer-grid">
            <div class="footer-col">
                <h4 class="disp">MAKY CHICKEN</h4>
                <p>Sensasi ayam goreng renyah bumbu rempah otentik keluarga, disajikan hangat dengan cita rasa pedas gurih tiada tanding.</p>
                <div style="margin-top:12px; display:flex; gap:8px;">
                    <span style="background:#fff; border:1px solid var(--cream-300); padding:2px 10px; border-radius:999px; font-weight:700; font-size:10px; color:var(--green-600);">100% Halal</span>
                    <span style="background:#fff; border:1px solid var(--cream-300); padding:2px 10px; border-radius:999px; font-weight:700; font-size:10px; color:var(--yellow-600);">Crispy Juara</span>
                </div>
            </div>

            <div class="footer-col">
                <h4 class="disp" style="color:var(--ink-900);">Jam Operasional</h4>
                <ul>
                    <li><b>Senin - Kamis:</b> 10.00 - 22.00</li>
                    <li><b>Jumat:</b> 13.00 - 23.00</li>
                    <li><b>Sabtu - Minggu:</b> 09.00 - 23.30</li>
                </ul>
            </div>

            <div class="footer-col">
                <h4 class="disp" style="color:var(--ink-900);">Navigasi Cepat</h4>
                <ul>
                    <li><a href="index.php?page=home">Beranda Utama</a></li>
                    <li><a href="index.php?page=menu">Katalog Menu Paket</a></li>
                    <li><a href="index.php?page=reservasi">Booking Meja & Event</a></li>
                    <li><a href="index.php?page=form-kendala">Pusat Masukan & Bantuan</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4 class="disp" style="color:var(--ink-900);">Lokasi & Kontak</h4>
                <p><i class="bi bi-geo-alt-fill text-danger me-1"></i> Jl. Kuliner Lezat No. 88, Kawasan Santai, Jakarta Selatan</p>
                <p class="mt-2"><i class="bi bi-whatsapp text-success me-1"></i> +<?= htmlspecialchars($kontakAdmin ?? '6281234567890'); ?> (WhatsApp)</p>
                <p><i class="bi bi-envelope-fill text-primary me-1"></i> halo@makychicken.id</p>
                
                <a href="index.php?page=reservasi" class="btn-submit" style="min-height:36px; font-size:11px; margin-top:12px;">Pesan Meja Sekarang</a>
            </div>
        </div>

        <div class="footer-bottom">
            <span>© 2026 Maky Chicken Culinary Group. Hak Cipta Dilindungi.</span>
            <div style="display:flex; gap:16px;">
                <a href="#">Syarat & Ketentuan</a>
                <a href="#">Kebijakan Privasi</a>
                <a href="index.php?page=form-kendala">Layanan Pelanggan</a>
            </div>
        </div>
    </footer>

    <script>
    function showFileName(input) {
        const fileDiv = document.getElementById('fileSelected');
        if (input.files && input.files[0]) {
            fileDiv.style.display = 'block';
            fileDiv.innerHTML = `<i class="bi bi-paperclip"></i> 1 file dipilih: <b>${input.files[0].name}</b>`;
        }
    }
    </script>
</body>
</html>