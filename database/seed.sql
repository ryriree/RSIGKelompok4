-- ===================================================
-- SEED DATA: SISTEM INFORMASI RESTORAN MAKY CHICKEN
-- ===================================================
USE db_restoran;

-- 1. DATA AKUN STAFF & ADMIN (Untuk UC-04 Login)
INSERT INTO users (username, password, nama, email, role, status_akun, akun_diblokir) VALUES
('admin', '$2y$10$92IXMOqn3Jg3PfnxD3iB.54.16M0n7Xi', 'Budi Santoso', 'admin@makychicken.com', 'Admin', 'Aktif', FALSE),
('kasir1', '$2y$10$92IXMOqn3Jg3PfnxD3iB.54.16M0n7Xi', 'Siti Rahmawati', 'kasir1@makychicken.com', 'Kasir', 'Aktif', FALSE),
('pelayan1', '$2y$10$92IXMOqn3Jg3PfnxD3iB.54.16M0n7Xi', 'Andi Wijaya', 'pelayan1@makychicken.com', 'Pelayan', 'Aktif', FALSE);

-- 2. DATA MENU RESTORAN (Untuk UC-05/06 Menu & QR)
INSERT INTO menu (nama_menu, kategori, harga, deskripsi, status_tersedia) VALUES
('Paket Ayam Geprek Juara', 'Makanan', 25000.00, 'Ayam geprek crispy sambal korek pedas gurih khas Maky Chicken.', TRUE),
('Paket Ayam Krispi Gurih', 'Makanan', 25000.00, 'Ayam goreng crispy bumbu rempah otentik keluarga.', TRUE),
('Maky Chicken Burger', 'Makanan', 22000.00, 'Burger chicken crispy dengan keju melt dan saus spesial.', TRUE),
('Chicken Tenders & Fries', 'Makanan', 25000.00, 'Daging dada ayam crispy tanpa tulang dengan kentang goreng.', TRUE),
('Es Teh Manis Segar Jumbo', 'Minuman', 6000.00, 'Es teh manis dingin segar gelas jumbo 500ml.', TRUE);

-- 3. DATA PAKET MENU RESERVASI (Untuk UC-01 Reservasi)
INSERT INTO paket_menu (nama_paket, harga, deskripsi) VALUES
('Paket Keluarga Mantap 6 Pax', 240000.00, '6 Ayam Crispy + 6 Nasi + 6 Es Teh Jumbo + 2 Snack Box.'),
('Paket Rombongan Hemat 10 Pax', 380000.00, '10 Ayam Geprek + 10 Nasi + 10 Es Teh Jumbo + 3 Snack Box.');

-- 4. DATA KAS FISIK HARIAN (Untuk UC-15 Audit Kas Fisik)
INSERT INTO kas (tanggal, kas_sistem, kas_fisik, selisih, status_selisih) VALUES
(CURRENT_DATE(), 2500000.00, 2500000.00, 0.00, 'Sesuai');

-- 5. DATA PESANAN DUMMY (Untuk Tampilan Dashboard UC-14)
INSERT INTO pesanan (kode_pesanan, no_meja, jenis_pesanan, status, total_harga) VALUES
('TRX-20261025-0142', 12, 'Dine In', 'Selesai', 104000.00),
('TRX-20261025-0141', NULL, 'Take Away', 'Selesai', 52000.00);

-- 6. DATA DETAIL PESANAN
INSERT INTO detail_pesanan (id_pesanan, id_menu, jumlah, subtotal, catatan) VALUES
(1, 1, 2, 50000.00, 'Pedas Level 3'),
(1, 5, 2, 12000.00, 'Es Banyak'),
(2, 2, 2, 50000.00, 'Dada semua');

-- 7. DATA PEMBAYARAN
INSERT INTO pembayaran (kode_transaksi, id_pesanan, metode, total, uang_diterima, kembalian, status, waktu_bayar) VALUES
('PAY-20261025-001', 1, 'QRIS', 104000.00, 104000.00, 0.00, 'Lunas', NOW()),
('PAY-20261025-002', 2, 'Tunai', 52000.00, 100000.00, 48000.00, 'Lunas', NOW());

-- 8. DATA TRANSAKSI LAPORAN
INSERT INTO transaksi (id_pesanan, id_pembayaran, id_kas, metode_pembayaran, metode_pesanan, status_pembayaran, tanggal, total_bayar) VALUES
(1, 1, 1, 'QRIS', 'Dine In', 'Lunas', CURRENT_DATE(), 104000.00),
(2, 2, 1, 'Tunai', 'Take Away', 'Lunas', CURRENT_DATE(), 52000.00);

-- 9. DATA KONDISI LAPORAN KENDALA (Untuk UC-08 Komplain & Feedback)
INSERT INTO laporan_kendala (nama, kontak, jenis_laporan, isi_pesan, status) VALUES
('Siti Rahmawati', '081234567890', 'Pelayanan', 'Waktu penyajian agak lama saat jam makan siang.', 'Pending');

-- 10. DATA KONTAK ADMIN
INSERT INTO kontak_admin (id_user, nomor_whatsapp) VALUES
(1, '081234567890');

INSERT INTO users (username, password, nama, email, role, status_akun, akun_diblokir) VALUES
('manajer1', '$2y$10$92IXMOqn3Jg3PfnxD3iB.54.16M0n7Xi', 'Bima Aditya', 'manajer@makychicken.com', 'Manajer', 'Aktif', FALSE);

INSERT INTO reservasi (id_reservasi, kode_booking, nama_pemesan, kontak_pemesanan, tanggal, slot_waktu, lantai, jumlah_orang, subtotal, dp, service_fee, total, status, status_dp) VALUES
(1, 'RES-20261025-001', 'Keluarga Hendra', '081234567890', '2026-10-25', '14:00 WIB', 2, 6, 380000.00, 400000.00, 20000.00, 400000.00, 'Confirmed', 'Lunas'),
(2, 'RES-20261025-002', 'Sari Wulandari', '085712345678', '2026-10-25', '18:30 WIB', 1, 4, 230000.00, 250000.00, 20000.00, 250000.00, 'Confirmed', 'Lunas'),
(3, 'RES-20261025-003', 'Bima Aditya', '081987654321', '2026-10-25', '19:00 WIB', 1, 8, 520000.00, 550000.00, 30000.00, 550000.00, 'Confirmed', 'Lunas');

-- 2. Sisipkan Header Pesanan Terkait Reservasi (Tabel pesanan)
INSERT INTO pesanan (id_pesanan, kode_pesanan, no_meja, jenis_pesanan, status, total_harga, waktu_pesan) VALUES
(3, 'TRX-20251025-0140', 15, 'Reservasi (Lt. 2)', 'Selesai', 400000.00, '2026-10-25 14:02:00'),
(4, 'TRX-20251025-0143', 4, 'Reservasi (Lt. 1)', 'Selesai', 250000.00, '2026-10-25 18:30:00'),
(5, 'TRX-20251025-0144', 8, 'Reservasi (Lt. 1)', 'Selesai', 550000.00, '2026-10-25 19:00:00');

-- 3. Sisipkan Rincian Item Menu Reservasi (Tabel detail_pesanan)
INSERT INTO detail_pesanan (id_pesanan, id_menu, jumlah, subtotal) VALUES
(3, 1, 6, 168000.00), -- Paket Ayam Geprek
(3, 3, 6, 48000.00),  -- Es Teh Jumbo
(4, 2, 4, 104000.00), -- Paket Ayam Krispi
(4, 3, 4, 32000.00),
(5, 1, 8, 224000.00),
(5, 4, 4, 128000.00);

-- 4. Sisipkan Proses Pembayaran DP/Pelunasan (Tabel pembayaran)
INSERT INTO pembayaran (id_pembayaran, kode_transaksi, id_pesanan, id_reservasi, metode, total, status, waktu_bayar) VALUES
(3, 'TRX-20251025-0140', 3, 1, 'QRIS', 400000.00, 'Lunas', '2026-10-25 14:02:00'),
(4, 'TRX-20251025-0143', 4, 2, 'Tunai', 250000.00, 'Lunas', '2026-10-25 18:30:00'),
(5, 'TRX-20251025-0144', 5, 3, 'QRIS', 550000.00, 'Lunas', '2026-10-25 19:00:00');

-- 5. Rekapitulasi Laporan Penjualan Reservasi (Tabel transaksi)
INSERT INTO transaksi (id_transaksi, id_pesanan, id_pembayaran, id_kas, metode_pembayaran, metode_pesanan, status_pembayaran, tanggal, total_bayar) VALUES
(3, 3, 3, 1, 'QRIS', 'Reservasi (Lt. 2)', 'Lunas', '2026-10-25', 400000.00),
(4, 4, 4, 1, 'Tunai', 'Reservasi (Lt. 1)', 'Lunas', '2026-10-25', 250000.00),
(5, 5, 5, 1, 'QRIS', 'Reservasi (Lt. 1)', 'Lunas', '2026-10-25', 550000.00);