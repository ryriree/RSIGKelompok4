<?php
require_once __DIR__ . '/../config/Database.php';

class PesananModel {
    private $conn;
    private $table_name = "pesanan";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function createPesanan($kodePesanan, $noMeja, $jenisPesanan, $totalHarga, $status = 'Dalam Proses') {
        $query = "INSERT INTO " . $this->table_name . " (kode_pesanan, no_meja, jenis_pesanan, status, total_harga, waktu_pesan) 
                  VALUES (:kode_pesanan, :no_meja, :jenis_pesanan, :status, :total_harga, NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':kode_pesanan', $kodePesanan);
        $stmt->bindParam(':no_meja', $noMeja);
        $stmt->bindParam(':jenis_pesanan', $jenisPesanan);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':total_harga', $totalHarga);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function getAllPesanan() {
        $query = "SELECT p.*, 
                    (SELECT GROUP_CONCAT(CONCAT(m.nama_menu, ' x', dp.jumlah, IF(dp.catatan != '', CONCAT(' (', dp.catatan, ')'), '')) SEPARATOR ', ')
                     FROM detail_pesanan dp 
                     JOIN menu m ON dp.id_menu = m.id_menu 
                     WHERE dp.id_pesanan = p.id_pesanan) AS rincian_item
                  FROM " . $this->table_name . " p 
                  ORDER BY p.id_pesanan DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function updateStatus($idPesanan, $statusBaru) {
        $query = "UPDATE " . $this->table_name . " SET status = :status WHERE id_pesanan = :id_pesanan";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $statusBaru);
        $stmt->bindParam(':id_pesanan', $idPesanan);
        return $stmt->execute();
    }
}
?>