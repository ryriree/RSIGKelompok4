
<?php
$dataReservasi = $dataReservasi ?? [];

$tanggal = $dataReservasi['tanggal'] ?? '';
$slotWaktu = $dataReservasi['slot_waktu'] ?? '';
$jumlahOrang = $dataReservasi['jumlah_orang'] ?? 1;

$pesanError = $pesanError ?? null;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Lantai dan Meja - Maky Chicken</title>

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

        .navbar {
            background: #17233f;
            color: white;
            padding: 20px 8%;
            font-size: 21px;
            font-weight: bold;
        }

        .container {
            max-width: 750px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 4px 15px rgba(0,0,0,.06);
        }

        h2 {
            color: #17233f;
            margin-top: 0;
        }

        .info {
            background: #f0f3f9;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            line-height: 1.8;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 9px;
        }

        select {
            width: 100%;
            padding: 13px;
            border: 1px solid #d5d9e2;
            border-radius: 8px;
            font-size: 14px;
        }

        .error {
            background: #ffeaea;
            color: #b42318;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 18px;
        }

        .buttons {
            display: flex;
            gap: 12px;
        }

        button, .back {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
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

        button:hover {
            background: #263c68;
        }
    </style>
</head>

<body>

<div class="navbar">
    Maky Chicken
</div>

<div class="container">
    <div class="card">

        <h2>Pilih Lantai dan Meja</h2>
        <p>Silakan pilih lokasi meja untuk reservasi Anda.</p>

        <div class="info">
            <strong>Detail Reservasi</strong><br>
            Tanggal:
            <?= htmlspecialchars($tanggal, ENT_QUOTES, 'UTF-8') ?><br>

            Waktu:
            <?= htmlspecialchars($slotWaktu, ENT_QUOTES, 'UTF-8') ?><br>

            Jumlah Tamu:
            <?= (int) $jumlahOrang ?> orang
        </div>

        <?php if (!empty($pesanError)): ?>
            <div class="error">
                <?= htmlspecialchars($pesanError, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form action="index.php?page=proses-pilih-lantai" method="POST">

            <input type="hidden" name="tanggal"
                   value="<?= htmlspecialchars($tanggal, ENT_QUOTES, 'UTF-8') ?>">

            <input type="hidden" name="slot_waktu"
                   value="<?= htmlspecialchars($slotWaktu, ENT_QUOTES, 'UTF-8') ?>">

            <input type="hidden" name="jumlah_orang"
                   value="<?= (int) $jumlahOrang ?>">

            <div class="form-group">
                <label for="lantai">Pilih Lantai</label>
                <select name="lantai" id="lantai" required>
                    <option value="">Pilih lantai</option>
                    <option value="1">Lantai 1</option>
                    <option value="2">Lantai 2</option>
                </select>
            </div>

            <div class="form-group">
                <label for="meja">Nomor Meja</label>
                <select name="meja" id="meja" required>
                    <option value="">Pilih meja</option>
                    <option value="M01">Meja 01</option>
                    <option value="M02">Meja 02</option>
                    <option value="M03">Meja 03</option>
                    <option value="M04">Meja 04</option>
                    <option value="M05">Meja 05</option>
                </select>
            </div>

            <div class="buttons">
                <a href="index.php?page=form-reservasi" class="back">
                    Kembali
                </a>

                <button type="submit">
                    Lanjutkan
                </button>
            </div>

        </form>

    </div>
</div>

</body>
</html>
