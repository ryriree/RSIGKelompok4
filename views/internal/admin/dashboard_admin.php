
<?php
$jumlahReservasi = $jumlahReservasi ?? 0;
$jumlahStaff = $jumlahStaff ?? 0;
$reservasiHariIni = $reservasiHariIni ?? 0;
$daftarReservasi = $daftarReservasi ?? [];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Maky Chicken</title>

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

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h2 {
            margin: 0;
            color: #17233f;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,.04);
        }

        .card p {
            color: #777;
            font-size: 14px;
            margin: 0 0 15px;
        }

        .card h3 {
            font-size: 28px;
            color: #17233f;
            margin: 0;
        }

        .table-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
        }

        th, td {
            padding: 14px 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }

        th {
            color: #687084;
            background: #f8f9fc;
        }

        .status {
            background: #e7f5ec;
            color: #21834a;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .empty {
            color: #777;
            text-align: center;
            padding: 25px;
        }

        @media (max-width: 700px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
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

    <a href="index.php?page=dashboard-admin" class="active">
        Dashboard
    </a>

    <a href="index.php?page=daftar-reservasi">
        Kelola Reservasi
    </a>

    <a href="index.php?page=daftar-staff">
        Kelola Staff
    </a>

    <a href="index.php?page=logout">
        Logout
    </a>
</aside>

<main class="main">

    <div class="header">
        <h2>Dashboard Admin</h2>
        <span>Administrator</span>
    </div>

    <div class="cards">

        <div class="card">
            <p>Total Reservasi</p>
            <h3><?= (int) $jumlahReservasi ?></h3>
        </div>

        <div class="card">
            <p>Reservasi Hari Ini</p>
            <h3><?= (int) $reservasiHariIni ?></h3>
        </div>

        <div class="card">
            <p>Total Staff</p>
            <h3><?= (int) $jumlahStaff ?></h3>
        </div>

    </div>

    <div class="table-card">

        <h3>Reservasi Terbaru</h3>

        <?php if (!empty($daftarReservasi)): ?>

            <table>
                <thead>
                    <tr>
                        <th>Kode Booking</th>
                        <th>Nama Pemesan</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($daftarReservasi as $reservasi): ?>
                        <tr>
                            <td>
                                <?= htmlspecialchars($reservasi['kode_booking'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($reservasi['nama_pemesan'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($reservasi['tanggal'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($reservasi['slot_waktu'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
                            </td>
                            <td>
                                <span class="status">
                                    <?= htmlspecialchars($reservasi['status'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
                                </span>
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
