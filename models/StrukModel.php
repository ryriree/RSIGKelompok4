<?php
require_once __DIR__ . '/../config/Database.php';

class StrukModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Riwayat transaksi yang sudah lunas (terbaru dulu)
    public function getRiwayatTransaksi() {
        $sql = "SELECT p.*, pay.kode_transaksi, pay.metode, pay.uang_diterima, pay.kembalian, pay.waktu_bayar
                FROM pembayaran pay
                JOIN pesanan p ON p.id_pesanan = pay.id_pesanan
                WHERE pay.status = 'Lunas'
                ORDER BY pay.waktu_bayar DESC, pay.id_pembayaran DESC
                LIMIT 50";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStrukDetail($idPesanan) {
        $sql = "SELECT p.*, pay.kode_transaksi, pay.metode, pay.uang_diterima, pay.kembalian, pay.waktu_bayar
                FROM pembayaran pay
                JOIN pesanan p ON p.id_pesanan = pay.id_pesanan
                WHERE p.id_pesanan = :id AND pay.status = 'Lunas'
                ORDER BY pay.id_pembayaran DESC LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $idPesanan]);
        $pesanan = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$pesanan) return null;

        $sqlItem = "SELECT dp.jumlah, dp.subtotal, dp.catatan, m.nama_menu, m.harga
                    FROM detail_pesanan dp
                    JOIN menu m ON dp.id_menu = m.id_menu
                    WHERE dp.id_pesanan = :id";
        $stmtI = $this->conn->prepare($sqlItem);
        $stmtI->execute([':id' => $idPesanan]);

        return ['pesanan' => $pesanan, 'items' => $stmtI->fetchAll(PDO::FETCH_ASSOC)];
    }
}