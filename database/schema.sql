-- ===================================================
-- DATABASE SCHEMA: SISTEM INFORMASI RESTORAN MAKY CHICKEN
-- ===================================================
CREATE DATABASE IF NOT EXISTS db_restoran;
USE db_restoran;

-- 1. TABEL USERS (Auth & Hak Akses Staff)
CREATE TABLE IF NOT EXISTS users (
    id_user INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    role VARCHAR(20) NOT NULL,
    status_akun VARCHAR(10) DEFAULT 'Aktif',
    akun_diblokir BOOLEAN DEFAULT FALSE
);

-- 2. TABEL MENU (Daftar Makanan & Minuman)
CREATE TABLE IF NOT EXISTS menu (
    id_menu INT AUTO_INCREMENT PRIMARY KEY,
    nama_menu VARCHAR(100) NOT NULL,
    kategori VARCHAR(50) NOT NULL,
    harga DECIMAL(12, 2) NOT NULL,
    deskripsi TEXT NULL,
    gambar VARCHAR(255) NULL,
    status_tersedia BOOLEAN NOT NULL DEFAULT TRUE
);

-- 3. TABEL PAKET MENU (Paket Reservasi/Khusus)
CREATE TABLE IF NOT EXISTS paket_menu (
    id_paket INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nama_paket VARCHAR(100) NOT NULL,
    harga DECIMAL(12,2) NOT NULL,
    deskripsi TEXT
);

-- 4. TABEL RESERVASI (Reservasi Tempat & Paket)
CREATE TABLE IF NOT EXISTS reservasi (
    id_reservasi INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    kode_booking VARCHAR(20) NOT NULL,
    nama_pemesan VARCHAR(100) NOT NULL,
    kontak_pemesanan VARCHAR(30) NOT NULL,
    tanggal DATE NOT NULL,
    slot_waktu VARCHAR(20) NOT NULL,
    lantai INT,
    jumlah_orang INT NOT NULL,
    subtotal DECIMAL(12,2) DEFAULT 0,
    dp DECIMAL(12,2) DEFAULT 400000,
    service_fee DECIMAL(12,2) DEFAULT 0,
    total DECIMAL(12,2) DEFAULT 0,
    status VARCHAR(20) NOT NULL,
    status_dp VARCHAR(20) NOT NULL,
    file_evoucher VARCHAR(255)
);

-- 5. TABEL DETAIL RESERVASI (Breakdown Paket di Reservasi)
CREATE TABLE IF NOT EXISTS detail_reservasi (
    id_detail_reservasi INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_reservasi INT NOT NULL,
    id_paket INT NOT NULL,
    qty INT NOT NULL,
    subtotal DECIMAL(12,2) NOT NULL,
    CONSTRAINT FK_detail_reservasi_reservasi FOREIGN KEY (id_reservasi)
        REFERENCES reservasi (id_reservasi),
    CONSTRAINT FK_detail_reservasi_paket_menu FOREIGN KEY (id_paket)
        REFERENCES paket_menu (id_paket)
);

-- 6. TABEL PESANAN (Header Transaksi Pesanan/Meja)
CREATE TABLE IF NOT EXISTS pesanan (
    id_pesanan INT AUTO_INCREMENT PRIMARY KEY,
    kode_pesanan VARCHAR(20) NOT NULL UNIQUE,
    no_meja INT NULL,
    jenis_pesanan VARCHAR(30) NOT NULL DEFAULT 'Dine In',
    opsi_antar VARCHAR(30) NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'Menunggu Pembayaran',
    total_harga DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    estimasi_menit INT NULL,
    waktu_pesan DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    waktu_sajikan DATETIME NULL
);

-- 7. TABEL DETAIL PESANAN (Rincian Item Menu yang Dipesan)
CREATE TABLE IF NOT EXISTS detail_pesanan (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    id_pesanan INT NOT NULL,
    id_menu INT NOT NULL,
    jumlah INT NOT NULL DEFAULT 1,
    subtotal DECIMAL(12, 2) NOT NULL,
    catatan VARCHAR(255) NULL,
    CONSTRAINT FK_detail_pesanan_pesanan 
        FOREIGN KEY (id_pesanan) REFERENCES pesanan(id_pesanan) ON DELETE CASCADE,
    CONSTRAINT FK_detail_pesanan_menu 
        FOREIGN KEY (id_menu) REFERENCES menu(id_menu) ON DELETE RESTRICT
);

-- 8. TABEL PEMBAYARAN (Proses Pembayaran POS Kasir & Reservasi)
CREATE TABLE IF NOT EXISTS pembayaran (
    id_pembayaran INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    kode_transaksi VARCHAR(30) NOT NULL,
    id_pesanan INT NULL,
    id_reservasi INT NULL,
    metode VARCHAR(20) NOT NULL,
    total DECIMAL(12,2) NOT NULL,
    uang_diterima DECIMAL(12,2) NULL,
    kembalian DECIMAL(12,2) NULL,
    status VARCHAR(25) NOT NULL,
    waktu_bayar DATETIME NULL,
    CONSTRAINT FK_pembayaran_pesanan
        FOREIGN KEY (id_pesanan) REFERENCES pesanan (id_pesanan),
    CONSTRAINT FK_pembayaran_reservasi
        FOREIGN KEY (id_reservasi) REFERENCES reservasi (id_reservasi)
);

-- 9. TABEL KAS FISIK (Audit Kasir Harian - UC-15)
CREATE TABLE IF NOT EXISTS kas (
    id_kas INT AUTO_INCREMENT PRIMARY KEY,
    tanggal DATE NOT NULL UNIQUE,
    kas_sistem DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    kas_fisik DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    selisih DECIMAL(12, 2) DEFAULT 0.00,
    status_selisih VARCHAR(20) DEFAULT 'Sesuai'
);

-- 10. TABEL TRANSAKSI (Rekapitulasi Laporan Penjualan - UC-14)
CREATE TABLE IF NOT EXISTS transaksi (
    id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
    id_pesanan INT NOT NULL,
    id_pembayaran INT NOT NULL,
    id_kas INT NOT NULL,
    metode_pembayaran VARCHAR(20) NOT NULL,
    metode_pesanan VARCHAR(20) NOT NULL,
    status_pembayaran VARCHAR(20) NOT NULL,
    tanggal DATE NOT NULL,
    total_bayar DECIMAL(12, 2) NOT NULL,
    CONSTRAINT FK_transaksi_pesanan FOREIGN KEY (id_pesanan) REFERENCES pesanan(id_pesanan),
    CONSTRAINT FK_transaksi_pembayaran FOREIGN KEY (id_pembayaran) REFERENCES pembayaran(id_pembayaran),
    CONSTRAINT FK_transaksi_kas FOREIGN KEY (id_kas) REFERENCES kas(id_kas)
);

-- 11. TABEL STRUK (Nota Cetak Kasir)
CREATE TABLE IF NOT EXISTS struk (
    id_struk INT AUTO_INCREMENT PRIMARY KEY,
    id_pesanan INT NOT NULL,
    no_nota VARCHAR(30) NOT NULL UNIQUE,
    total_bayar DECIMAL(12, 2) NOT NULL,
    metode_pembayaran VARCHAR(20) NOT NULL,
    waktu_transaksi DATETIME NULL,
    waktu_cetak DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT FK_struk_pesanan 
        FOREIGN KEY (id_pesanan) REFERENCES pesanan(id_pesanan) ON DELETE CASCADE
);

-- 12. TABEL LAPORAN KENDALA (Komplain & Feedback Pelanggan - UC-08)
CREATE TABLE IF NOT EXISTS laporan_kendala (
    id_laporan INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    kontak VARCHAR(50) NOT NULL,
    jenis_laporan VARCHAR(50) NOT NULL,
    isi_pesan TEXT NOT NULL,
    status VARCHAR(20) DEFAULT 'Pending',
    tanggal_kirim DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_user_penangan INT NULL,
    catatan_penanganan TEXT NULL,
    tanggal_diperbarui DATETIME NULL,
    CONSTRAINT FK_laporan_kendala_users
        FOREIGN KEY (id_user_penangan) REFERENCES users(id_user) ON DELETE SET NULL
);

-- 13. TABEL KONTAK ADMIN (Daftar Nomor WA Admin)
CREATE TABLE IF NOT EXISTS kontak_admin (
    id_kontak INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    nomor_whatsapp VARCHAR(20) NOT NULL,
    CONSTRAINT FK_kontak_admin_users
        FOREIGN KEY (id_user) REFERENCES users (id_user)
);