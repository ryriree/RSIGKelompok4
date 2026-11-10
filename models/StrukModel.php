<?php
require_once __DIR__ . '/../config/Database.php';

class StrukModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getRiwayatTransaksi() {
        $query = "SELECT p.*, pay.kode_transaksi, pay.metode, pay.uang_diterima, pay.kembalian, pay.waktu_bayar 
                  FROM pesanan p 
                  LEFT JOIN pembayaran pay ON p.id_pesanan = pay.id_pesanan 
                  ORDER BY p.id_pesanan DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getStrukDetail($idPesanan) {
        $queryPesanan = "SELECT p.*, pay.kode_transaksi, pay.metode, pay.uang_diterima, pay.kembalian, pay.waktu_bayar 
                        FROM pesanan p 
                        LEFT JOIN pembayaran pay ON p.id_pesanan = pay.id_pesanan 
                        WHERE p.id_pesanan = :id_pesanan LIMIT 1";
        $stmtP = $this->conn->prepare($queryPesanan);
        $stmtP->bindParam(':id_pesanan', $idPesanan);
        $stmtP->execute();
        $pesanan = $stmtP->fetch();

        if (!$pesanan) return null;

        $queryItems = "SELECT dp.*, m.nama_menu, m.harga 
                       FROM detail_pesanan dp 
                       JOIN menu m ON dp.id_menu = m.id_menu 
                       WHERE dp.id_pesanan = :id_pesanan";
        $stmtI = $this->conn->prepare($queryItems);
        $stmtI->bindParam(':id_pesanan', $idPesanan);
        $stmtI->execute();
        $items = $stmtI->fetchAll();

        return [
            'pesanan' => $pesanan,
            'items' => $items
        ];
    }
}
?>