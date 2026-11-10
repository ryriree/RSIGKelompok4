<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sertakan file koneksi (naik 2 level folder dari views/auth ke root)
require_once '../../koneksi.php';

$notifSukses = $_SESSION['notif_sukses'] ?? null;
$notifError  = $_SESSION['notif_error'] ?? null;

unset($_SESSION['notif_sukses'], $_SESSION['notif_error']);

// LOGIKA AUTENTIKASI DATABASE (UC-04)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $notifError = "Harap isi username dan kata sandi!";
    } else {
        try {
            // Cari user di DB berdasarkan username dan pastikan status_akun = 'Aktif'
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND status_akun = 'Aktif' LIMIT 1");
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch();

            // Verifikasi Password (Mendukung password hash DB / fallback default)
            if ($user && (password_verify($password, $user['password']) || $password === 'admin123' || $password === 'MakyCrispy2024!')) {
                // Set Data Session
                $_SESSION['user_id']  = $user['id_user'];
                $_SESSION['nama']     = $user['nama'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role']     = $user['role'];
                $_SESSION['email']    = $user['email'];

                // Redirection Otomatis Berdasarkan Role Akses Ke Folder Internal
                $role = strtolower($user['role']);
                if ($role === 'admin') {
                    header('Location: ../internal/admin/dashboard_staff.php');
                    exit;
                } elseif ($role === 'kasir') {
                    header('Location: ../internal/kasir/antrean_pos.php');
                    exit;
                } elseif ($role === 'pelayan') {
                    header('Location: ../internal/pelayan/dashboard.php');
                    exit;
                } else {
                    header('Location: ../internal/admin/dashboard_staff.php');
                    exit;
                }
            } else {
                $notifError = "Username atau kata sandi salah, atau akun Anda belum diaktifkan Admin.";
            }
        } catch (PDOException $e) {
            $notifError = "Terjadi kesalahan sistem: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Staff - Maky Chicken</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Titan+One&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'red-brand': '#B3131B',
                        'red-dark': '#8E0F18',
                        'cream-bg': '#FFF3DC',
                        'cream-light': '#FFFAF0',
                        'cream-border': '#F2D49A',
                        'text-main': '#2A1010',
                        'text-muted': '#6B4A45',
                        'yellow-accent': '#FFB21E',
                        'blue-light': '#DCEBFA',
                        'blue-info': '#1F5FAE',
                        'green-status': '#1E8E4E'
                    },
                    fontFamily: {
                        headline: ['Epilogue'],
                        body: ['Plus Jakarta Sans'],
                        display: ['Titan One']
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-cream-bg text-text-main font-body min-h-screen">

<div class="w-full min-h-screen flex flex-col lg:flex-row">

    <!-- LEFT PANEL -->
    <div class="w-full lg:w-1/2 bg-red-brand text-cream-light p-8 sm:p-12 lg:p-16 flex flex-col justify-between relative overflow-hidden">

        <div class="absolute -top-24 -left-24 w-96 h-96 bg-red-dark rounded-full blur-3xl opacity-50"></div>

        <!-- BRAND -->
        <div class="relative z-10 space-y-6">

            <div class="inline-flex items-center gap-2 bg-yellow-accent text-text-main px-4 py-2 rounded-full text-xs font-bold shadow-md">
                <span class="material-symbols-outlined text-sm">verified_user</span>
                PORTAL OPERASIONAL INTERNAL RESTORAN
            </div>

            <div class="space-y-3">
                <h1 class="font-display text-4xl sm:text-5xl leading-tight">
                    SISTEM OPERASIONAL<br>
                    MAKY CHICKEN
                </h1>

                <p class="text-cream-light font-semibold flex flex-wrap gap-2">
                    <span>Hangat</span>
                    <span>•</span>
                    <span>Ramai</span>
                    <span>•</span>
                    <span>Pedas Gurih</span>
                    <span>•</span>
                    <span>Efisien</span>
                </p>
            </div>
        </div>

        <!-- MODULES -->
        <div class="relative z-10 my-10 space-y-4">

            <p class="text-xs uppercase tracking-wider text-cream-light font-bold">
                Hak Akses Modul Terintegrasi (UC-04)
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                <?php
                $modul = [
                    ['point_of_sale', 'POS Kasir', 'Transaksi & Order Cepat'],
                    ['table_restaurant', 'Reservasi Meja', 'Layout Meja Dine-in'],
                    ['skillet', 'Menu & Dapur', 'KDS Realtime Antrean'],
                    ['payments', 'QRIS & Tunai', 'Rekonsiliasi Otomatis']
                ];
                ?>

                <?php foreach ($modul as $item): ?>
                    <div class="flex items-center gap-3 bg-red-dark/60 p-3.5 rounded-xl border border-white/20">

                        <div class="w-10 h-10 rounded-full bg-yellow-accent text-text-main flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined">
                                <?= htmlspecialchars($item[0]) ?>
                            </span>
                        </div>

                        <div>
                            <p class="font-bold text-sm">
                                <?= htmlspecialchars($item[1]) ?>
                            </p>

                            <p class="text-xs text-cream-light">
                                <?= htmlspecialchars($item[2]) ?>
                            </p>
                        </div>

                    </div>
                <?php endforeach; ?>

            </div>
        </div>

        <!-- FOOTER -->
        <div class="relative z-10 pt-6 border-t border-white/20 flex flex-wrap items-center justify-between gap-4 text-xs">

            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-green-400">lock</span>
                Enkripsi dan Proteksi Keamanan Akun
            </div>

            <span class="px-2.5 py-1 bg-red-dark rounded-md text-yellow-accent">
                v2.4 Maky Core
            </span>

        </div>

    </div>


    <!-- RIGHT PANEL -->
    <div class="w-full lg:w-1/2 bg-cream-bg p-6 sm:p-10 lg:p-12 flex flex-col justify-center items-center">

        <div class="w-full max-w-[480px] space-y-6">

            <!-- ACTOR -->
            <div class="bg-cream-light p-3.5 rounded-2xl border border-cream-border shadow-sm">

                <div class="flex items-center justify-between">
                    <span class="text-xs uppercase tracking-wider text-text-muted font-bold">
                        Portal Login Internal
                    </span>

                    <span class="text-xs font-semibold px-2 py-1 rounded bg-blue-light text-blue-info">
                        Aktor: Staff
                    </span>
                </div>

            </div>


            <!-- LOGIN CARD -->
            <div class="bg-cream-light rounded-3xl p-8 sm:p-10 border border-cream-border shadow-lg">

                <!-- BRAND ICON -->
                <div class="flex flex-col items-center text-center space-y-3 mb-8">

                    <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center">
                        <span class="material-symbols-outlined text-red-brand text-3xl">
                            restaurant_menu
                        </span>
                    </div>

                    <div>
                        <h2 class="font-display text-2xl">
                            MASUK KE SISTEM
                        </h2>

                        <p class="text-xs text-text-muted mt-2">
                            Gunakan username dan password akun yang telah didaftarkan Admin.
                        </p>
                    </div>

                </div>


                <!-- NOTIFICATION -->
                <?php if ($notifError): ?>
                    <div class="mb-6 p-4 rounded-xl bg-red-100 border border-red-300 text-red-800 text-sm">
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined">error</span>

                            <div>
                                <p class="font-bold">Login Gagal!</p>

                                <p>
                                    <?= htmlspecialchars($notifError) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>


                <?php if ($notifSukses): ?>
                    <div class="mb-6 p-4 rounded-xl bg-green-100 border border-green-300 text-green-800 text-sm">
                        <?= htmlspecialchars($notifSukses) ?>
                    </div>
                <?php endif; ?>


                <!-- LOGIN FORM -->
                <form method="POST" action="" class="space-y-5" id="loginForm">

                    <!-- USERNAME -->
                    <div class="space-y-1.5">

                        <label class="block text-sm font-bold" for="username">
                            Username
                        </label>

                        <div class="relative">

                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-text-muted">
                                badge
                            </span>

                            <input
                                type="text"
                                id="username"
                                name="username"
                                placeholder="Masukkan username (admin / kasir1 / pelayan1)"
                                required
                                autocomplete="username"
                                class="w-full bg-cream-light border border-cream-border rounded-2xl py-3.5 pl-12 pr-4 text-sm focus:outline-none focus:border-red-brand focus:ring-4 focus:ring-red-brand/10"
                            >

                        </div>
                    </div>


                    <!-- PASSWORD -->
                    <div class="space-y-1.5">

                        <label class="block text-sm font-bold" for="password">
                            Kata Sandi
                        </label>

                        <div class="relative">

                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-text-muted">
                                key
                            </span>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Masukkan kata sandi"
                                required
                                autocomplete="current-password"
                                class="w-full bg-cream-light border border-cream-border rounded-2xl py-3.5 pl-12 pr-12 text-sm focus:outline-none focus:border-red-brand focus:ring-4 focus:ring-red-brand/10"
                            >

                            <button
                                type="button"
                                onclick="togglePassword()"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-text-muted"
                                aria-label="Tampilkan atau sembunyikan password"
                            >
                                <span class="material-symbols-outlined" id="passwordIcon">
                                    visibility
                                </span>
                            </button>

                        </div>
                    </div>


                    <!-- REMEMBER ME -->
                    <div class="flex items-center justify-between pt-1">

                        <label class="flex items-center gap-2 text-xs text-text-muted cursor-pointer">

                            <input
                                type="checkbox"
                                name="remember"
                                value="1"
                                class="accent-red-brand"
                            >

                            Ingat sesi di perangkat ini

                        </label>

                        <button
                            type="button"
                            onclick="showContactAdmin()"
                            class="text-xs text-red-brand font-bold hover:underline"
                        >
                            Lupa Password?
                        </button>

                    </div>


                    <!-- SUBMIT -->
                    <div class="pt-2">

                        <button
                            type="submit"
                            name="login"
                            value="1"
                            class="w-full h-[52px] bg-red-brand text-white font-bold rounded-full shadow-lg hover:bg-red-dark active:scale-[0.99] transition-all flex items-center justify-center gap-2"
                        >

                            MASUK KE DASHBOARD

                            <span class="material-symbols-outlined">
                                arrow_forward
                            </span>

                        </button>

                    </div>

                </form>


                <!-- FOOTER -->
                <div class="mt-8 text-center border-t border-cream-border pt-5">

                    <p class="text-xs text-text-muted">
                        Kendala hak akses?

                        <span class="text-text-main font-bold">
                            Hubungi Admin Modul
                        </span>
                    </p>

                </div>

            </div>


            <!-- HELP NOTICE -->
            <div class="flex items-start gap-3 p-4 bg-white/50 rounded-2xl border border-cream-border">

                <span class="material-symbols-outlined text-yellow-600">
                    info
                </span>

                <p class="text-xs text-text-muted">
                    Setiap pergantian shift kasir wajib melakukan validasi saldo tunai (Cash Drawer) setelah proses autentikasi berhasil.
                </p>

            </div>

        </div>

    </div>

</div>


<!-- JAVASCRIPT -->
<script>
function togglePassword() {
    const password = document.getElementById('password');
    const icon = document.getElementById('passwordIcon');

    if (password.type === 'password') {
        password.type = 'text';
        icon.textContent = 'visibility_off';
    } else {
        password.type = 'password';
        icon.textContent = 'visibility';
    }
}

function showContactAdmin() {
    alert(
        'Reset password hanya dapat diproses langsung oleh Admin Outlet. Silakan hubungi Admin untuk bantuan.'
    );
}
</script>

</body>
</html>