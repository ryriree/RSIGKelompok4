
<?php
$dataStaff = $dataStaff ?? [];
$pesanError = $pesanError ?? null;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Staff - Maky Chicken</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            background: #f5f6fa;
            color: #252b3b;
        }

        .sidebar {
            position: fixed;
            width: 230px;
            height: 100vh;
            background: #17233f;
            padding: 28px 20px;
            color: white;
        }

        .brand {
            font-size: 21px;
            font-weight: bold;
            margin-bottom: 40px;
        }

        .sidebar a {
            display: block;
            color: #dce2ef;
            text-decoration: none;
            padding: 13px;
            margin-bottom: 8px;
            border-radius: 7px;
            font-size: 14px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #30466f;
            color: white;
        }

        .main {
            margin-left: 230px;
            padding: 35px;
        }

        .form-card {
            max-width: 650px;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,.04);
        }

        h2 {
            color: #17233f;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 8px;
        }

        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #d5d9e2;
            border-radius: 7px;
            font-size: 14px;
        }

        .alert {
            background: #ffeaea;
            color: #b42318;
            padding: 12px;
            border-radius: 7px;
            margin-bottom: 18px;
        }

        .buttons {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }

        button, .back {
            flex: 1;
            padding: 13px;
            border: none;
            border-radius: 7px;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
        }

        button {
            background: #17233f;
            color: white;
        }

        .back {
            background: #e9edf5;
            color: #17233f;
        }

        @media (max-width: 700px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .main {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
</head>

<body>

<aside class="sidebar">
    <div class="brand">Maky Chicken</div>

    <a href="index.php?page=dashboard-admin">Dashboard</a>
    <a href="index.php?page=daftar-reservasi">Kelola Reservasi</a>
    <a href="index.php?page=daftar-staff" class="active">Kelola Staff</a>
    <a href="index.php?page=logout">Logout</a>
</aside>

<main class="main">

    <h2>Ubah Akun Staff</h2>
    <p>Perbarui informasi akun staff yang dipilih.</p>

    <div class="form-card">

        <?php if (!empty($pesanError)): ?>
            <div class="alert">
                <?= htmlspecialchars($pesanError, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form action="index.php?page=proses-ubah-staff" method="POST">

            <input type="hidden" name="id_user"
                   value="<?= htmlspecialchars((string)($dataStaff['id_user'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">

            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama"
                       value="<?= htmlspecialchars($dataStaff['nama'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                       required>
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username"
                       value="<?= htmlspecialchars($dataStaff['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                       required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email"
                       value="<?= htmlspecialchars($dataStaff['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                       required>
            </div>

            <div class="form-group">
                <label for="password">Password Baru</label>
                <input type="password" id="password" name="password"
                       placeholder="Kosongkan jika tidak ingin mengubah password"
                       minlength="8">
            </div>

            <div class="form-group">
                <label for="status_akun">Status Akun</label>
                <select id="status_akun" name="status_akun" required>
                    <option value="Aktif"
                        <?= ($dataStaff['status_akun'] ?? '') === 'Aktif' ? 'selected' : '' ?>>
                        Aktif
                    </option>

                    <option value="Nonaktif"
                        <?= ($dataStaff['status_akun'] ?? '') === 'Nonaktif' ? 'selected' : '' ?>>
                        Nonaktif
                    </option>
                </select>
            </div>

            <div class="buttons">
                <a href="index.php?page=daftar-staff" class="back">
                    Kembali
                </a>

                <button type="submit">
                    Simpan Perubahan
                </button>
            </div>

        </form>

    </div>

</main>

</body>
</html>
