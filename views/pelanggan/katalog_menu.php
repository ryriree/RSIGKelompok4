
<?php
$daftarPaket = $daftarPaket ?? [];
$pesanError = $pesanError ?? null;

function formatRupiahKatalog($angka)
{
    return 'Rp' . number_format((float) $angka, 0, ',', '.');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Menu - Maky Chicken</title>

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
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        h2 {
            color: #17233f;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #777;
            margin-bottom: 28px;
        }

        .menu-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 20px;
        }

        .menu-card {
            background: white;
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 4px 15px rgba(0,0,0,.06);
        }

        .menu-card h3 {
            color: #17233f;
            margin-top: 0;
        }

        .description {
            color: #777;
            font-size: 14px;
            min-height: 45px;
            line-height: 1.5;
        }

        .price {
            color: #17233f;
            font-weight: bold;
            font-size: 18px;
            margin: 18px 0;
        }

        .quantity {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quantity label {
            font-size: 14px;
            font-weight: bold;
        }

        .quantity input {
            width: 75px;
            padding: 9px;
            border: 1px solid #d5d9e2;
            border-radius: 7px;
        }

        .error {
            background: #ffeaea;
            color: #b42318;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .submit-button {
            width: 100%;
            margin-top: 28px;
            padding: 14px;
            border: none;
            border-radius: 8px;
            background: #17233f;
            color: white;
            font-weight: bold;
            font-size: 15px;
            cursor: pointer;
        }

        .submit-button:hover {
            background: #263c68;
        }

        .empty {
            background: white;
            padding: 25px;
            border-radius: 10px;
            color: #777;
            text-align: center;
        }
    </style>
</head>

<body>

<div class="navbar">
    Maky Chicken
</div>

<div class="container">

    <h2>Pilih Paket Menu</h2>
    <p class="subtitle">
        Pilih paket menu yang ingin dipesan untuk reservasi Anda.
    </p>

    <?php if (!empty($pesanError)): ?>
        <div class="error">
            <?= htmlspecialchars($pesanError, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($daftarPaket)): ?>

        <form action="index.php?page=proses-pilih-menu" method="POST">

            <div class="menu-list">

                <?php foreach ($daftarPaket as $paket): ?>

                    <?php
                    $idPaket = $paket['id_paket'] ?? '';
                    $namaPaket = $paket['nama_paket'] ?? 'Nama Paket';
                    $harga = $paket['harga'] ?? 0;
                    $deskripsi = $paket['deskripsi'] ?? '-';
                    ?>

                    <div class="menu-card">

                        <h3>
                            <?= htmlspecialchars($namaPaket, ENT_QUOTES, 'UTF-8') ?>
                        </h3>

                        <p class="description">
                            <?= htmlspecialchars($deskripsi, ENT_QUOTES, 'UTF-8') ?>
                        </p>

                        <div class="price">
                            <?= formatRupiahKatalog($harga) ?>
                        </div>

                        <div class="quantity">
                            <label for="paket_<?= htmlspecialchars((string) $idPaket, ENT_QUOTES, 'UTF-8') ?>">
                                Jumlah
                            </label>

                            <input
                                type="number"
                                id="paket_<?= htmlspecialchars((string) $idPaket, ENT_QUOTES, 'UTF-8') ?>"
                                name="paket[<?= htmlspecialchars((string) $idPaket, ENT_QUOTES, 'UTF-8') ?>]"
                                min="0"
                                value="0"
                            >
                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

            <button type="submit" class="submit-button">
                Lihat Ringkasan Pesanan
            </button>

        </form>

    <?php else: ?>

        <div class="empty">
            Belum ada paket menu yang tersedia.
        </div>

    <?php endif; ?>

</div>

</body>
</html>
