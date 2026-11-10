<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UI-06: Memesan via QR Code - Maky Chicken</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f6f9; color: #222; }
        header { background: #0b2a5b; color: #fff; padding: 14px 20px; display: flex; justify-content: space-between; align-items: center; }
        .container { max-width: 600px; margin: 16px auto; padding: 0 12px; }
        .card { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 16px; margin-bottom: 12px; }
        .item-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; border-bottom: 1px solid #eee; padding-bottom: 8px; }
        .qty-control button { width: 28px; height: 28px; border: 1px solid #0b2a5b; background: #fff; color: #0b2a5b; font-weight: bold; border-radius: 4px; cursor: pointer; }
        .qty-control span { margin: 0 8px; font-weight: bold; }
        textarea { width: 100%; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; padding: 6px; font-size: 12px; }
        .btn-order { width: 100%; background: #ff8c42; color: #fff; border: none; padding: 12px; border-radius: 6px; font-size: 16px; font-weight: bold; cursor: pointer; }
        .btn-reset { background: #d9534f; color: #fff; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; margin-bottom: 12px; }
    </style>
</head>
<body>
    <header>
        <strong>Maky Chicken - Pesan Meja <span id="lblMeja">05</span></strong>
        <span style="font-size:12px;">Dine-In Self Order</span>
    </header>

    <div class="container">
        <div class="card">
            <h3>Pilih Menu Pesanan</h3>
            <div id="itemList"></div>
            <button class="btn-reset" onclick="resetCart()">Reset Pilihan</button>
        </div>

        <div class="card">
            <h3>Ringkasan Pesanan</h3>
            <p>Estimasi Penyiapan: <strong id="lblEstimasi">0 Menit</strong></p>
            <label><strong>Opsi Pengantaran:</strong></label><br>
            <input type="radio" name="opsiAntar" value="Diantar ke meja" checked> Diantar ke meja
            <input type="radio" name="opsiAntar" value="Ambil sendiri"> Ambil sendiri
            <hr>
            <h4>Total Tagihan: <span id="lblTotal">Rp 0</span></h4>
            <button class="btn-order" onclick="submitPesanan()">Pesan Sekarang</button>
        </div>
    </div>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const noMeja = urlParams.get('meja') || 5;
        document.getElementById('lblMeja').textContent = noMeja;

        const catalog = [
            { id: 1, nama: 'Nasi Goreng Spesial', harga: 25000 },
            { id: 2, nama: 'Ayam Crispy Dada', harga: 18000 },
            { id: 3, nama: 'Es Teh Manis', harga: 6000 }
        ];

        let cart = {};

        function renderItems() {
            const list = document.getElementById('itemList');
            list.innerHTML = catalog.map(item => `
                <div class="item-row">
                    <div>
                        <strong>${item.nama}</strong><br>
                        <small>Rp ${item.harga.toLocaleString('id-ID')}</small>
                        <textarea maxlength="200" placeholder="Catatan khusus (alergi/varian)..." onchange="updateNote(${item.id}, this.value)"></textarea>
                    </div>
                    <div class="qty-control">
                        <button onclick="changeQty(${item.id}, -1)">-</button>
                        <span>${cart[item.id]?.qty || 0}</span>
                        <button onclick="changeQty(${item.id}, 1)">+</button>
                    </div>
                </div>
            `).join('');
            updateSummary();
        }

        function changeQty(id, delta) {
            if (!cart[id]) cart[id] = { qty: 0, note: '' };
            cart[id].qty = Math.max(0, cart[id].qty + delta);
            renderItems();
        }

        function updateNote(id, val) {
            if (!cart[id]) cart[id] = { qty: 0, note: '' };
            cart[id].note = val;
        }

        function resetCart() {
            cart = {};
            renderItems();
        }

        function updateSummary() {
            let total = 0;
            let totalQty = 0;
            catalog.forEach(item => {
                const q = cart[item.id]?.qty || 0;
                total += q * item.harga;
                totalQty += q;
            });
            const estimasi = totalQty > 0 ? 5 + (2 * totalQty) : 0;
            document.getElementById('lblTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('lblEstimasi').textContent = estimasi + ' Menit';
        }

        function submitPesanan() {
            const totalQty = Object.values(cart).reduce((a, b) => a + b.qty, 0);
            if (totalQty === 0) {
                alert('Silakan pilih minimal 1 item menu!');
                return;
            }
            alert('Pesanan berhasil dikirim ke kasir & dapur!');
        }

        renderItems();
    </script>
</body>
</html>