<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UI-12: Mencetak Struk Pesanan - Kasir POS</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #eaeded; }
        .layout { display: flex; height: 100vh; }
        aside { width: 220px; background: #0b2a5b; color: white; padding: 20px; }
        aside h3 { margin-top: 0; }
        main { flex: 1; padding: 20px; display: flex; gap: 20px; }
        .list-section { flex: 1; background: white; border-radius: 8px; padding: 16px; border: 1px solid #ccc; }
        .preview-section { width: 340px; background: white; border-radius: 8px; padding: 16px; border: 1px solid #ccc; display: flex; flex-direction: column; justify-content: space-between; }
        .order-card { padding: 12px; border: 1px solid #ddd; margin-bottom: 8px; border-radius: 6px; cursor: pointer; }
        .order-card.selected { border-color: #0b2a5b; background: #e6edf9; }
        .receipt-paper { background: #fffdf8; border: 1px dashed #aaa; padding: 16px; font-family: 'Courier New', monospace; font-size: 13px; }
        .btn-print { background: #28a745; color: white; border: none; padding: 12px; border-radius: 6px; font-weight: bold; cursor: pointer; width: 100%; margin-top: 8px; }
        .btn-reprint { background: #0b2a5b; color: white; border: none; padding: 12px; border-radius: 6px; font-weight: bold; cursor: pointer; width: 100%; margin-top: 8px; }
    </style>
</head>
<body>
    <div class="layout">
        <aside>
            <h3>POS Kasir</h3>
            <p>Shift: Pagi</p>
            <p>Kasir: Henry</p>
        </aside>

        <main>
            <div class="list-section">
                <h2>Riwayat Transaksi Harian</h2>
                <div class="order-card selected" onclick="selectOrder('MC-20261109-0001', 'Dine-In Meja 05', 'Rp 43.000', 'Tunai')">
                    <strong>Nota: MC-20261109-0001</strong><br>
                    Meja 05 | Total: Rp 43.000 | Tunai
                </div>
                <div class="order-card" onclick="selectOrder('MC-20261109-0002', 'Dine-In Meja 02', 'Rp 68.000', 'QRIS')">
                    <strong>Nota: MC-20261109-0002</strong><br>
                    Meja 02 | Total: Rp 68.000 | QRIS
                </div>
            </div>

            <div class="preview-section">
                <div>
                    <h3>Pratinjau Struk</h3>
                    <div class="receipt-paper" id="receiptContent">
                        <center>
                            <strong>MAKY CHICKEN</strong><br>
                            Jl. Veteran No. 10, Malang<br>
                            --------------------------------<br>
                        </center>
                        No. Nota: <span id="rNota">MC-20261109-0001</span><br>
                        Waktu   : 2026-11-09 12:30<br>
                        Tipe    : <span id="rTipe">Dine-In Meja 05</span><br>
                        Metode  : <span id="rMetode">Tunai</span><br>
                        --------------------------------<br>
                        Nasi Goreng Spesial x1  Rp 25.000<br>
                        Ayam Crispy Dada x1     Rp 18.000<br>
                        --------------------------------<br>
                        TOTAL : <strong id="rTotal">Rp 43.000</strong><br>
                        --------------------------------<br>
                        <center>Terima Kasih Atas Kunjungan Anda</center>
                    </div>
                </div>
                <div>
                    <button class="btn-print" onclick="cetakStruk('Cetak Pertama')">Cetak Struk Fisik</button>
                    <button class="btn-reprint" onclick="cetakStruk('Re-Print')">Cetak Ulang (Re-Print)</button>
                </div>
            </div>
        </main>
    </div>

    <script>
        function selectOrder(nota, tipe, total, metode) {
            document.getElementById('rNota').textContent = nota;
            document.getElementById('rTipe').textContent = tipe;
            document.getElementById('rTotal').textContent = total;
            document.getElementById('rMetode').textContent = metode;
        }

        function cetakStruk(jenis) {
            alert('Perintah ' + jenis + ' berhasil dikirim ke printer thermal kasir (Max 3 detik).');
        }
    </script>
</body>
</html>