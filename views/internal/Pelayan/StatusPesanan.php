<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UI-13: Mengatur Status Pesanan - Tablet Pelayan</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f0f2f5; }
        header { background: #0b2a5b; color: white; padding: 14px 20px; display: flex; justify-content: space-between; align-items: center; }
        .filter-bar { background: white; padding: 12px 20px; display: flex; gap: 12px; border-bottom: 1px solid #ddd; }
        input, select { padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        .grid-orders { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px; padding: 20px; }
        .card-order { background: white; border-radius: 8px; border-left: 6px solid #ff8c42; padding: 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.08); }
        .card-order.ready { border-left-color: #28a745; }
        .badge { background: #eee; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .badge-deliver { background: #e6edf9; color: #0b2a5b; }
        .custom-note { background: #fff9e6; border: 1px dashed #ffe0b2; padding: 8px; margin: 8px 0; border-radius: 4px; font-size: 13px; }
        .btn-done { background: #28a745; color: white; border: none; padding: 10px; width: 100%; border-radius: 4px; font-weight: bold; cursor: pointer; margin-top: 8px; }
    </style>
</head>
<body>
    <header>
        <h2>Tablet Floor Server - Antrean Pesanan</h2>
        <span>Pelayan Bertugas: Budi</span>
    </header>

    <div class="filter-bar">
        <input type="text" id="filterMeja" placeholder="Cari No. Meja..." onkeyup="filterData()">
        <select id="filterStatus" onchange="filterData()">
            <option value="">Semua Status</option>
            <option value="Dalam Proses">Dalam Proses</option>
            <option value="Siap Disajikan">Siap Disajikan</option>
        </select>
    </div>

    <div class="grid-orders" id="orderGrid"></div>

    <script>
        const orders = [
            { id: 101, meja: 5, status: 'Siap Disajikan', antar: 'Diantar ke meja', note: 'Ayam bagian dada, es teh manis tawar', items: 'Nasi Goreng Spesial x1, Ayam Crispy Dada x1, Es Teh x1' },
            { id: 102, meja: 2, status: 'Dalam Proses', antar: 'Diantar ke meja', note: 'Sambal dipisah', items: 'Ayam Crispy Paha x2, Nasi Putih x2' }
        ];

        function renderOrders(data) {
            const grid = document.getElementById('orderGrid');
            grid.innerHTML = data.map(o => `
                <div class="card-order ${o.status === 'Siap Disajikan' ? 'ready' : ''}">
                    <div style="display:flex; justify-content:space-between;">
                        <h3>Meja ${o.meja}</h3>
                        <span class="badge ${o.antar === 'Diantar ke meja' ? 'badge-deliver' : ''}">${o.antar}</span>
                    </div>
                    <p>Status: <strong>${o.status}</strong></p>
                    <p><strong>Item:</strong> ${o.items}</p>
                    <div class="custom-note"><strong>Catatan Khusus:</strong> ${o.note}</div>
                    ${o.status === 'Siap Disajikan' ? 
                        `<button class="btn-done" onclick="markDone(${o.id})">Tandai Selesai (2 Taps)</button>` : 
                        `<button disabled style="width:100%; padding:10px; border:none; background:#ccc;">Sedang Dimasak</button>`}
                </div>
            `).join('');
        }

        function filterData() {
            const mejaVal = document.getElementById('filterMeja').value.trim();
            const statusVal = document.getElementById('filterStatus').value;

            const filtered = orders.filter(o => {
                const matchMeja = !mejaVal || o.meja.toString().includes(mejaVal);
                const matchStatus = !statusVal || o.status === statusVal;
                return matchMeja && matchStatus;
            });
            renderOrders(filtered);
        }

        function markDone(id) {
            const idx = orders.findIndex(o => o.id === id);
            if (idx !== -1) {
                orders.splice(idx, 1);
                alert('Status pesanan diperbarui menjadi Selesai. Timestamp tersimpan ke basis data.');
                renderOrders(orders);
            }
        }

        renderOrders(orders);
    </script>
</body>
</html>