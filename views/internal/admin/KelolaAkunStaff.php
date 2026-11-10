<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sertakan file koneksi (naik 3 level folder dari views/internal/admin ke root)
require_once '../../../koneksi.php';

$notifSukses = $_SESSION['notif_sukses'] ?? null;
$notifError  = $_SESSION['notif_error'] ?? null;
unset($_SESSION['notif_sukses'], $_SESSION['notif_error']);

// 1. PROSES TAMBAH AKUN STAFF BARU
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_staff'])) {
    $nama     = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $role     = $_POST['role'] ?? 'Pelayan';
    $password = $_POST['password'] ?? 'admin123';

    if (empty($nama) || empty($username) || empty($email) || empty($password)) {
        $_SESSION['notif_error'] = "Harap isi semua bidang formulir registrasi staff!";
    } else {
        try {
            // Cek apakah username sudah ada
            $stmtCek = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :usr");
            $stmtCek->execute([':usr' => $username]);
            if ($stmtCek->fetchColumn() > 0) {
                $_SESSION['notif_error'] = "Username '@$username' sudah digunakan oleh staff lain!";
            } else {
                // Enkripsi Password
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare("INSERT INTO users (username, password, nama, email, role, status_akun) 
                    VALUES (:usr, :pwd, :nama, :email, :role, 'Aktif')");
                $stmt->execute([
                    ':usr'   => $username,
                    ':pwd'   => $passwordHash,
                    ':nama'  => $nama,
                    ':email' => $email,
                    ':role'  => $role
                ]);

                $_SESSION['notif_sukses'] = "Berhasil mendaftarkan akun staff baru: $nama ($role)";
                header("Location: admin_staff.php");
                exit;
            }
        } catch (PDOException $e) {
            $_SESSION['notif_error'] = "Gagal menyimpan akun staff: " . $e->getMessage();
        }
    }
}

// 2. PROSES UPDATE STATUS AKUN & RESET PASSWORD
if (isset($_GET['action']) && isset($_GET['id'])) {
    $idUser = (int)$_GET['id'];
    $action = $_GET['action'];

    if ($action === 'toggle_status' && $idUser > 0) {
        $stmt = $pdo->prepare("UPDATE users SET status_akun = IF(status_akun = 'Aktif', 'Non-Aktif', 'Aktif') WHERE id_user = :id");
        $stmt->execute([':id' => $idUser]);
        $_SESSION['notif_sukses'] = "Status akun staff berhasil diperbarui.";
        header("Location: admin_staff.php");
        exit;
    }

    if ($action === 'reset_password' && $idUser > 0) {
        // Reset password default ke 'admin123'
        $newHash = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = :pwd WHERE id_user = :id");
        $stmt->execute([':pwd' => $newHash, ':id' => $idUser]);
        $_SESSION['notif_sukses'] = "Password staff berhasil di-reset menjadi: admin123";
        header("Location: admin_staff.php");
        exit;
    }
}

// 3. AMBIL DATA USERS DARI DATABASE (DENGAN FILTER SEARCH)
$search = trim($_GET['q'] ?? '');
if (!empty($search)) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE nama LIKE :q OR username LIKE :q OR email LIKE :q ORDER BY id_user DESC");
    $stmt->execute([':q' => "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM users ORDER BY id_user DESC");
}
$listStaff = $stmt->fetchAll();

// 4. STATISTIK RINGKAS AKUN
$totalStaff  = count($listStaff);
$totalAdmin  = count(array_filter($listStaff, fn($u) => strtolower($u['role']) === 'admin'));
$totalKasir  = count(array_filter($listStaff, fn($u) => strtolower($u['role']) === 'kasir'));
$totalPelayan= count(array_filter($listStaff, fn($u) => strtolower($u['role']) === 'pelayan'));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UC-03 Manajemen Akun Staff - Maky Chicken</title>

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
                        'red-light': '#FBE0DD',
                        'cream-bg': '#FFF3DC',
                        'cream-light': '#FFFAF0',
                        'cream-border': '#F2D49A',
                        'text-main': '#2A1010',
                        'text-muted': '#6B4A45',
                        'yellow-accent': '#FFB21E',
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

<body class="bg-cream-bg text-text-main font-body min-h-screen p-4 sm:p-8">

    <div class="max-w-7xl mx-auto space-y-6">

        <!-- HEADER SECTION -->
        <div class="bg-red-brand text-cream-light p-6 sm:p-8 rounded-3xl shadow-xl flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-red-dark rounded-full blur-2xl opacity-50"></div>
            
            <div class="relative z-10 space-y-1">
                <div class="inline-flex items-center gap-2 bg-yellow-accent text-text-main px-3 py-1 rounded-full text-xs font-bold shadow-sm">
                    <span class="material-symbols-outlined text-sm">badge</span>
                    MODUL AKTOR: ADMIN SYSTEM
                </div>
                <h1 class="font-display text-2xl sm:text-3xl tracking-wide">
                    MANAJEMEN AKUN STAFF (UC-03)
                </h1>
                <p class="text-xs text-cream-light/90">
                    Kelola hak akses pengguna internal (Admin, Kasir, Pelayan) dan status otentikasi login.
                </p>
            </div>

            <div class="relative z-10 flex gap-2">
                <button onclick="toggleModalTambah()" class="px-5 py-3 bg-yellow-accent text-text-main font-bold rounded-full text-xs shadow-md hover:bg-amber-400 transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm">person_add</span>
                    + TAMBAH AKUN STAFF
                </button>
                <a href="dashboard_staff.php" class="px-4 py-3 bg-red-dark text-white rounded-full text-xs font-bold hover:bg-black/20 transition-all flex items-center">
                    &larr; DASHBOARD
                </a>
            </div>
        </div>

        <!-- NOTIFIKASI -->
        <?php if ($notifSukses): ?>
            <div class="p-4 bg-green-100 border border-green-300 text-green-800 text-xs font-bold rounded-2xl flex items-center gap-2">
                <span class="material-symbols-outlined">check_circle</span>
                <?= htmlspecialchars($notifSukses) ?>
            </div>
        <?php endif; ?>
        <?php if ($notifError): ?>
            <div class="p-4 bg-red-light border border-red-300 text-red-brand text-xs font-bold rounded-2xl flex items-center gap-2">
                <span class="material-symbols-outlined">error</span>
                <?= htmlspecialchars($notifError) ?>
            </div>
        <?php endif; ?>

        <!-- METRIC CARDS -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-cream-light p-5 rounded-2xl border border-cream-border shadow-sm">
                <span class="text-[11px] font-bold text-text-muted uppercase tracking-wider block">Total Pengguna</span>
                <span class="font-display text-2xl text-text-main mt-1 block"><?= $totalStaff ?> Akun</span>
            </div>
            <div class="bg-cream-light p-5 rounded-2xl border border-cream-border shadow-sm">
                <span class="text-[11px] font-bold text-red-brand uppercase tracking-wider block">Admin</span>
                <span class="font-display text-2xl text-red-brand mt-1 block"><?= $totalAdmin ?> Staff</span>
            </div>
            <div class="bg-cream-light p-5 rounded-2xl border border-cream-border shadow-sm">
                <span class="text-[11px] font-bold text-yellow-600 uppercase tracking-wider block">Kasir POS</span>
                <span class="font-display text-2xl text-yellow-600 mt-1 block"><?= $totalKasir ?> Staff</span>
            </div>
            <div class="bg-cream-light p-5 rounded-2xl border border-cream-border shadow-sm">
                <span class="text-[11px] font-bold text-blue-600 uppercase tracking-wider block">Pelayan / Dapur</span>
                <span class="font-display text-2xl text-blue-600 mt-1 block"><?= $totalPelayan ?> Staff</span>
            </div>
        </div>

        <!-- SEARCH BAR -->
        <div class="bg-cream-light p-4 rounded-2xl border border-cream-border shadow-sm flex flex-col sm:flex-row justify-between items-center gap-3">
            <form method="GET" action="" class="w-full sm:w-96 relative flex items-center">
                <span class="material-symbols-outlined absolute left-3 text-text-muted text-lg">search</span>
                <input 
                    type="text" 
                    name="q" 
                    value="<?= htmlspecialchars($search) ?>" 
                    placeholder="Cari Nama Staff / Username / Email..." 
                    class="w-full bg-white border border-cream-border rounded-full py-2.5 pl-10 pr-4 text-xs focus:outline-none focus:border-red-brand"
                >
            </form>
            <div class="text-xs text-text-muted">
                Terdaftar <strong><?= count($listStaff) ?></strong> akun operasional
            </div>
        </div>

        <!-- TABEL DATA STAFF -->
        <div class="bg-cream-light rounded-3xl border border-cream-border shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-red-brand text-cream-light text-[11px] uppercase font-bold tracking-wider">
                        <tr>
                            <th class="p-4">Staff & ID</th>
                            <th class="p-4">Username</th>
                            <th class="p-4">Email</th>
                            <th class="p-4">Role Akses</th>
                            <th class="p-4">Status Akun</th>
                            <th class="p-4 text-center">Aksi Manajemen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cream-border text-xs">
                        <?php if (empty($listStaff)): ?>
                            <tr>
                                <td colspan="6" class="p-8 text-center text-text-muted italic">
                                    Tidak ada data staff yang sesuai dengan pencarian.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($listStaff as $s): ?>
                                <tr class="hover:bg-amber-50/60 transition-colors">
                                    <td class="p-4">
                                        <div class="font-bold text-text-main"><?= htmlspecialchars($s['nama']) ?></div>
                                        <div class="text-[10px] text-text-muted font-mono">ID: #USR-<?= $s['id_user'] ?></div>
                                    </td>
                                    <td class="p-4 font-mono text-red-brand font-bold">
                                        @<?= htmlspecialchars($s['username']) ?>
                                    </td>
                                    <td class="p-4 text-text-muted">
                                        <?= htmlspecialchars($s['email']) ?>
                                    </td>
                                    <td class="p-4">
                                        <?php 
                                        $role = strtolower($s['role']);
                                        $badgeColor = 'bg-gray-100 text-gray-800';
                                        if ($role === 'admin') $badgeColor = 'bg-red-100 text-red-800 border-red-200';
                                        elseif ($role === 'kasir') $badgeColor = 'bg-amber-100 text-amber-800 border-amber-200';
                                        elseif ($role === 'pelayan') $badgeColor = 'bg-blue-100 text-blue-800 border-blue-200';
                                        ?>
                                        <span class="px-2.5 py-1 font-bold rounded-lg border text-[10px] <?= $badgeColor ?>">
                                            <?= htmlspecialchars($s['role']) ?>
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <?php if ($s['status_akun'] === 'Aktif'): ?>
                                            <span class="px-2.5 py-1 bg-green-100 text-green-700 font-bold rounded-full text-[10px] inline-flex items-center gap-1">
                                                <span class="material-symbols-outlined text-xs">check_circle</span> AKTIF
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2.5 py-1 bg-red-100 text-red-700 font-bold rounded-full text-[10px] inline-flex items-center gap-1">
                                                <span class="material-symbols-outlined text-xs">block</span> NON-AKTIF
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <!-- Toggle Status (Aktif/Non-Aktif) -->
                                            <a href="?action=toggle_status&id=<?= $s['id_user'] ?>" 
                                               onclick="return confirm('Apakah Anda yakin ingin mengubah status akun staff ini?')" 
                                               title="Ubah Status Akun" 
                                               class="p-1.5 bg-cream-bg text-text-main border border-cream-border rounded-lg hover:bg-yellow-accent">
                                                <span class="material-symbols-outlined text-sm">sync</span>
                                            </a>

                                            <!-- Reset Password -->
                                            <a href="?action=reset_password&id=<?= $s['id_user'] ?>" 
                                               onclick="return confirm('Reset password akun ini menjadi \'admin123\'?')" 
                                               title="Reset Password Default" 
                                               class="p-1.5 bg-red-brand text-white rounded-lg hover:bg-red-dark">
                                                <span class="material-symbols-outlined text-sm">lock_reset</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL FORM TAMBAH STAFF BARU -->
    <div id="modalTambah" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
        <div class="bg-cream-light w-full max-w-md rounded-3xl p-6 sm:p-8 border border-cream-border shadow-2xl space-y-4 relative">
            <button onclick="toggleModalTambah()" class="absolute top-5 right-5 text-text-muted hover:text-red-brand">
                <span class="material-symbols-outlined">close</span>
            </button>

            <div class="space-y-1">
                <h3 class="font-display text-xl text-red-brand">TAMBAH AKUN STAFF BARU</h3>
                <p class="text-xs text-text-muted">Buat kredensial login untuk petugas operasional restoran.</p>
            </div>

            <form method="POST" action="" class="space-y-4 pt-2">
                <input type="hidden" name="tambah_staff" value="1">

                <div class="space-y-1">
                    <label class="block text-xs font-bold">Nama Lengkap Staff *</label>
                    <input type="text" name="nama" required class="w-full bg-white border border-cream-border rounded-xl p-3 text-xs focus:outline-none focus:border-red-brand" placeholder="Contoh: Budi Santoso">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold">Username Login *</label>
                    <input type="text" name="username" required class="w-full bg-white border border-cream-border rounded-xl p-3 text-xs focus:outline-none focus:border-red-brand" placeholder="Contoh: kasir2 / pelayan3">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold">Alamat Email Staff *</label>
                    <input type="email" name="email" required class="w-full bg-white border border-cream-border rounded-xl p-3 text-xs focus:outline-none focus:border-red-brand" placeholder="staff@makychicken.com">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="block text-xs font-bold">Role Hak Akses</label>
                        <select name="role" class="w-full bg-white border border-cream-border rounded-xl p-3 text-xs focus:outline-none focus:border-red-brand">
                            <option value="Kasir">Kasir</option>
                            <option value="Pelayan">Pelayan</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-bold">Kata Sandi Awal *</label>
                        <input type="text" name="password" value="admin123" required class="w-full bg-white border border-cream-border rounded-xl p-3 text-xs focus:outline-none focus:border-red-brand">
                    </div>
                </div>

                <div class="pt-3">
                    <button type="submit" class="w-full py-3.5 bg-red-brand text-white font-bold rounded-full text-xs shadow-lg hover:bg-red-dark transition-all">
                        SIMPAN & DAFTARKAN STAFF
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT MODAL -->
    <script>
        function toggleModalTambah() {
            const modal = document.getElementById('modalTambah');
            modal.classList.toggle('hidden');
        }
    </script>
</body>
</html>