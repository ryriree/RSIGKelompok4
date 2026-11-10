<?php
$tanggal = $tanggal ?? '';
$slotWaktu = $slotWaktu ?? '';
$pesan = $pesan ?? 'Mohon maaf, jadwal reservasi yang kamu pilih sudah penuh.';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Penuh - Maky Chicken</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f6f8;
            margin: 0;
            color: #222;
        }

        nav {
            background: #0b2a5b;
            color: white;
            padding: 18px 30px;
        }

        .container {
            max-width: 600px;
            margin: 70px auto;
            padding: 20px;
        }

        .card {
            background: white;
            padding: 35px 25px;
            border-radius: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .ikon {
            font-size: 55px;
            margin-bottom: 15px;
        }

        h2 {
            color: #0b2a5b;
        }

        .pesan {
            color: #666;
            line-height: 1.6;
        }

        .detail {
            background: #f0f4fa;
            padding: 15px;
            border-radius: 7px;
            margin: 20px 0;
            text-align: left;
        }

        .detail p {
            margin: 8px 0;
        }

        .tombol {
            display: inline-block;
            padding: 12px 20px;
            background: #0b2a5b;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 12px;
        }
    </style>
</head>

<body>

<nav>
    <strong>MAKY CHICKEN</strong>
</nav>

<div class="container">
    <div class="card">

        <div class="ikon">!</div>

        <h2>Jadwal Reservasi Penuh</h2>

        <p class="pesan">
            <?= htmlspecialchars($pesan, ENT_QUOTES, 'UTF-8'); ?>
        </p>

        <?php if ($tanggal !== '' || $slotWaktu !== ''): ?>
            <div class="detail">
                <strong>Detail Jadwal yang Dipilih</strong>

                <?php if ($tanggal !== ''): ?>
                    <p>
                        Tanggal:
                        <?= htmlspecialchars($tanggal, ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                <?php endif; ?>

                <?php if ($slotWaktu !== ''): ?>
                    <p>
                        Waktu:
                        <?= htmlspecialchars($slotWaktu, ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <p class="pesan">
            Silakan pilih tanggal atau waktu reservasi lainnya.
        </p>

        <a class="tombol" href="index.php?page=form-reservasi">
            Pilih Jadwal Lain
        </a>

    </div>
</div>

</body>
</html>