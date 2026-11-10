
<?php
$daftarStaff = $daftarStaff ?? [];
$pesanSukses = $pesanSukses ?? null;
$pesanError = $pesanError ?? null;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Staff - Maky Chicken</title>

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

        h2 {
            color: #17233f;
        }

        .table-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            overflow-x: auto;
        }

        .top-action {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 18px;
        }

        .add-button {
            background: #17233f;
            color: white;
            text-decoration: none;
            padding: 11px 16px;
            border-radius: 7px;
            font-size: 13px;
            font-weight: bold;
        }

        .alert {
            padding: 12px;
            border-radius: 7px;
            margin-bottom: 18px;
        }

        .success {
            background: #e7f5ec;
            color: #21834a;
        }

        .error {
            background: #ffeaea;
            color: #b42318;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        th, td {
            padding: 14px 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }

        th {
            background: #f8f9fc;
            color: #687084;
        }

        .action {
            display: inline-block;
            padding: 7px 10px;
            border-radius: 6px;
            background: #e9edf5;
            color: #17233f;
            text-decoration: none;
            font-size: 12px;
        }

        .empty {
            text-align: center;
            color: #777;
            padding: 25px;
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

    <h2>Kelola Akun Staff</h2>
    <p>Kelola informasi dan akun staff restoran.</p>

    <?php if (!empty($pesanSukses)): ?>
        <div class="alert success">
            <?= htmlspecialchars($pesanSukses, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($pesanError)): ?>
        <div class="alert error">
            <?= htmlspecialchars($pesanError, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <div class="table-card">

        <div class="top-action">
            <a href="index.php?page=form-tambah-staff" class="add-button">
                + Tambah Staff
            </a>
        </div>

        <?php if (!empty($daftarStaff)): ?>

            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Staff</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Status Akun</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($daftarStaff as $index => $staff): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>

                            <td>
                                <?= htmlspecialchars($staff['nama'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($staff['username'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($staff['email'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
                            </td>

                            <td>
                                <?= !empty($staff['akun_diblokir'])
                                    ? 'Diblokir'
                                    : htmlspecialchars($staff['status_akun'] ?? 'Aktif', ENT_QUOTES, 'UTF-8') ?>
                            </td>

                            <td>
                                <a class="action"
                                   href="index.php?page=form-ubah-staff&id=<?= urlencode((string) ($staff['id_user'] ?? '')) ?>">
                                    Ubah
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php else: ?>

            <div class="empty">
                Belum ada data staff.
            </div>

        <?php endif; ?>

    </div>

</main>

</body>
</html>
