<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UI-05: Melihat Menu - Maky Chicken</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; margin: 0; background: #f8f9fa; color: #333; }
        header { background: #0b2a5b; color: #fff; padding: 16px 24px; display: flex; justify-content: space-between; align-items: center; }
        .nav-btn { background: #ff8c42; border: none; color: white; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .container { max-width: 900px; margin: 24px auto; padding: 0 16px; }
        .tabs { display: flex; gap: 8px; overflow-x: auto; padding-bottom: 8px; margin-bottom: 20px; }
        .tab-btn { padding: 10px 18px; border: 1px solid #0b2a5b; background: white; color: #0b2a5b; border-radius: 20px; cursor: pointer; font-weight: 600; white-space: nowrap; }
        .tab-btn.active { background: #0b2a5b; color: white; }
        .grid-menu { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 16px; }
        .card-menu { background: white; border: 1px solid #e0e0e0; border-radius: 8px; padding: 16px; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .card-menu h3 { margin: 0 0 8px 0; color: #0b2a5b; }
        .card-menu p { font-size: 14px; color: #666; margin: 0 0 12px 0; }
        .price { font-size: 16px; font-weight: bold; color: #d9534f; margin-bottom: 12px; }
        .btn-detail { background: #0b2a5b; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer; }
        .btn-disabled { background: #cccccc; color: #666666; border: none; padding: 8px 12px; border-radius: 4px; cursor: not-allowed; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; }
        .modal-content { background: white; padding: 24px; border-radius: 8px; width: 90%; max-width: 400px; }
    </style>
</head>
<body>
    <header>
        <h2>Maky Chicken - Menu</h2>
        <button class="nav-btn" onclick="location.href='pesan_qr.php?meja=05'">Pesan via QR</button>
    </header>

    <div class="container">
        <div class="tabs">
            <button class="tab-btn active" onclick="filterKategori('semua')">Semua Menu</button>
            <button class="tab-btn" onclick="filterKategori('Makanan')">Makanan</button>
            <button class="tab-btn" onclick="filterKategori('Minuman')">Minuman</button>
            <button class="tab-btn" onclick="filterKategori('Camilan')">Camilan</button>
        </div>

        <div class="grid-menu" id="menuContainer"></div>
    </div>

    <div class="modal" id="detailModal">
        <div class="modal-content">
            <h3 id="modalTitle"></h3>
            <p id="modalDesc"></p>
            <p class="price" id="modalPrice"></p>
            <button class="btn-detail" onclick="tutupModal()">Tutup</button>
        </div>
    </div>

    <script>
        const dataMenu = [
            { id: 1, nama: 'Nasi Goreng Spesial', kategori: 'Makanan', harga: 25000, desc: 'Nasi goreng dengan telur dan ayam suwir', tersedia: true },
            { id: 2, nama: 'Ayam Crispy Dada', kategori: 'Makanan', harga: 18000, desc: 'Dada ayam goreng renyah bumbu khas', tersedia: true },
            { id: 3, nama: 'Es Teh Manis', kategori: 'Minuman', harga: 6000, desc: 'Es teh segar manis pas', tersedia: true },
            { id: 4, nama: 'Kentang Goreng', kategori: 'Camilan', harga: 15000, desc: 'French fries gurih renyah', tersedia: false }
        ];

        function renderMenu(items) {
            const container = document.getElementById('menuContainer');
            if (items.length === 0) {
                container.innerHTML = '<p>Menu pada kategori ini tidak tersedia.</p>';
                return;
            }
            container.innerHTML = items.map(m => `
                <div class="card-menu">
                    <div>
                        <h3>${m.nama}</h3>
                        <p>${m.desc}</p>
                    </div>
                    <div>
                        <div class="price">Rp ${m.harga.toLocaleString('id-ID')}</div>
                        ${m.tersedia ? 
                            `<button class="btn-detail" onclick="lihatDetail(${m.id})">Lihat Detail</button>` : 
                            `<button class="btn-disabled" disabled>STOK HABIS</button>`}
                    </div>
                </div>
            `).join('');
        }

        function filterKategori(kat) {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            event.target.classList.add('active');
            if (kat === 'semua') renderMenu(dataMenu);
            else renderMenu(dataMenu.filter(m => m.kategori === kat));
        }

        function lihatDetail(id) {
            const m = dataMenu.find(item => item.id === id);
            document.getElementById('modalTitle').textContent = m.nama;
            document.getElementById('modalDesc').textContent = m.desc;
            document.getElementById('modalPrice').textContent = 'Rp ' + m.harga.toLocaleString('id-ID');
            document.getElementById('detailModal').style.display = 'flex';
        }

        function tutupModal() {
            document.getElementById('detailModal').style.display = 'none';
        }

        renderMenu(dataMenu);
    </script>
</body>
</html>