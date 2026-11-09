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