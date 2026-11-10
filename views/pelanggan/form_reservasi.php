
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
    <title>Reservasi - Maky Chicken</title>
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
            max-width: 650px;
            margin: 45px auto;
            padding: 0 20px;
        }

        .card {
            background: white;
            padding: 32px;
            border-radius: 14px;
            box-shadow: 0 4px 15px rgba(0,0,0,.06);
        }

        h2 {
            margin-top: 0;
            color: #17233f;
        }

        .subtitle {
            color: #777;
            margin-bottom: 28px;
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

        input, select {
            width: 100%;
            padding: 13px;
            border: 1px solid #d5d9e2;
            border-radius: 8px;
            font-size: 14px;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #263c68;
        }

        .error {
            background: #ffeaea;
            color: #b42318;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 18px;
        }

        button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            background: #17233f;
            color: white;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
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
        <h2>Reservasi Meja</h2>
        <p class="subtitle">
            Isi informasi reservasi untuk melanjutkan pemesanan meja.
        </p>

        <?php if (!empty($pesanError)): ?>
            <div class="error">
                <?= htmlspecialchars($pesanError, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form action="index.php?page=proses-reservasi" method="POST">

            <div class="form-group">
                <label for="tanggal">Tanggal Reservasi</label>
                <input
                    type="date"
                    id="tanggal"
                    name="tanggal"
                    min="<?= date('Y-m-d') ?>"
                    value="<?= htmlspecialchars($tanggal, ENT_QUOTES, 'UTF-8') ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="slot_waktu">Waktu Reservasi</label>
                <select id="slot_waktu" name="slot_waktu" required>
                    <option value="">Pilih waktu</option>
                    <?php
                    $daftarWaktu = [
                        '10:00-12:00',
                        '12:00-14:00',
                        '14:00-16:00',
                        '16:00-18:00',
                        '18:00-20:00'
                    ];

                    foreach ($daftarWaktu as $waktu):
                    ?>
                        <option value="<?= $waktu ?>"
                            <?= $slotWaktu === $waktu ? 'selected' : '' ?>>
                            <?= $waktu ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="jumlah_orang">Jumlah Tamu</label>
                <input
                    type="number"
                    id="jumlah_orang"
                    name="jumlah_orang"
                    min="1"
                    value="<?= (int) $jumlahOrang ?>"
                    required
                >
            </div>

            <button type="submit">
                Lanjutkan
            </button>

        </form>
    </div>
</div>

</body>
</html>
