
<?php
$pesanError = $pesanError ?? null;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Reservasi - Maky Chicken</title>

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

        .form-card {
            max-width: 650px;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,.04);
        }

        h2 { color: #17233f; }

        .form-group { margin-bottom: 20px; }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 14px;
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

    <h2>Tambah Reservasi</h2>
    <p>Masukkan informasi reservasi pelanggan.</p>

    <div class="form-card">

        <?php if (!empty($pesanError)): ?>
            <div class="alert">
                <?= htmlspecialchars($pesanError, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form action="index.php?page=proses-tambah-reservasi" method="POST">

            <div class="form-group">
                <label for="nama_pemesan">Nama Pemesan</label>
                <input type="text" id="nama_pemesan" name="nama_pemesan"
                       placeholder="Masukkan nama pelanggan" required>
            </div>

            <div class="form-group">
                <label for="kontak_pemesanan">Nomor Kontak</label>
                <input type="text" id="kontak_pemesanan" name="kontak_pemesanan"
                       placeholder="Masukkan nomor telepon" required>
            </div>

            <div class="form-group">
                <label for="tanggal">Tanggal Reservasi</label>
                <input type="date" id="tanggal" name="tanggal"
                       min="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="form-group">
                <label for="slot_waktu">Waktu Reservasi</label>
                <select id="slot_waktu" name="slot_waktu" required>
                    <option value="">Pilih waktu</option>
                    <option value="10:00-12:00">10:00-12:00</option>
                    <option value="12:00-14:00">12:00-14:00</option>
                    <option value="14:00-16:00">14:00-16:00</option>
                    <option value="16:00-18:00">16:00-18:00</option>
                    <option value="18:00-20:00">18:00-20:00</option>
                </select>
            </div>

            <div class="form-group">
                <label for="lantai">Lantai</label>
                <select id="lantai" name="lantai" required>
                    <option value="">Pilih lantai</option>
                    <option value="1">Lantai 1</option>
                    <option value="2">Lantai 2</option>
                </select>
            </div>

            <div class="form-group">
                <label for="jumlah_orang">Jumlah Tamu</label>
                <input type="number" id="jumlah_orang" name="jumlah_orang"
                       min="1" placeholder="Masukkan jumlah tamu" required>
            </div>

            <div class="buttons">
                <a href="index.php?page=daftar-reservasi" class="back">
                    Kembali
                </a>

                <button type="submit">
                    Simpan Reservasi
                </button>
            </div>

        </form>

    </div>

</main>

</body>
</html>
