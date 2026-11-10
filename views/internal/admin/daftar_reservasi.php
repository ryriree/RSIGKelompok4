
<?php
$daftarReservasi = $daftarReservasi ?? [];
$pesanSukses = $pesanSukses ?? null;
$pesanError = $pesanError ?? null;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Reservasi - Maky Chicken</title>

    <style>
        * { box-sizing: border-box; font-family: Arial, sans-serif; }

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

        .sidebar a:hover, .sidebar a.active {
            background: #30466f;
            color: white;
        }

        .main {
            margin-left: 230px;
            padding: 35px;
        }

        h2 { color: #17233f; }

        .table-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            overflow-x: auto;
        }

        .alert {
            padding: 12px;
            border-radius: 7px;
            margin-bottom: 18px;
        }

        .success { background: #e7f5ec; color: #21834a; }
        .error { background: #ffeaea; color: #b42318; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            min-width: 750px;
        }

        th, td {
            padding: 13px 10px;
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

        @media(max-width:700px) {
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
    <a href="index.php?page=daftar-reservasi" class="active">Kelola Reservasi</a>
    <a href="index.php?page=daftar-staff">Kelola Staff</a>
    <a href="index.php?page=logout">Logout</a>
</aside>

<main class="main">

    <h2>Kelola Reservasi</h2>
    <p>Daftar reservasi pelanggan Maky Chicken.</p>

    <?php if ($pesanSukses): ?>
        <div class="alert success">
            <?= htmlspecialchars($pesanSukses, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <?php if ($pesanError): ?>
        <div class="alert error">
            <?= htmlspecialchars($pesanError, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <div class="table-card">

        <h3>Data Reservasi Pelanggan</h3>

        <?php if (!empty($daftarReservasi)): ?>

            <table>
                <thead>
                    <tr>
                        <th>Kode Booking</th>
                        <th>Nama Pemesan</th>
                        <th>Kontak</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Jumlah Tamu</th>
                        <th>Status DP</th>
                        <th>Status Reservasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($daftarReservasi as $reservasi): ?>
                        <tr>
                            <td><?= htmlspecialchars($reservasi['kode_booking'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($reservasi['nama_pemesan'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($reservasi['kontak_pemesanan'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($reservasi['tanggal'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($reservasi['slot_waktu'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= (int) ($reservasi['jumlah_orang'] ?? 0) ?> orang</td>
                            <td><?= htmlspecialchars($reservasi['status_dp'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($reservasi['status'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <a class="action"
                                   href="index.php?page=form-ubah-reservasi&id=<?= urlencode((string) ($reservasi['id_reservasi'] ?? '')) ?>">
                                    Ubah
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php else: ?>

            <div class="empty">
                Belum ada data reservasi.
            </div>

        <?php endif; ?>

    </div>

</main>

</body>
</html>
