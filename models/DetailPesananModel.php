<?php
require_once __DIR__ . '/../config/Database.php';

class DetailPesananModel {
    private $conn;
    private $table_name = "detail_pesanan";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function addDetail($idPesanan, $idMenu, $jumlah, $subtotal, $catatan = '') {
        $query = "INSERT INTO " . $this->table_name . " (id_pesanan, id_menu, jumlah, subtotal, catatan) 
                  VALUES (:id_pesanan, :id_menu, :jumlah, :subtotal, :catatan)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_pesanan', $idPesanan);
        $stmt->bindParam(':id_menu', $idMenu);
        $stmt->bindParam(':jumlah', $jumlah);
        $stmt->bindParam(':subtotal', $subtotal);
        $stmt->bindParam(':catatan', $catatan);
        return $stmt->execute();
    }
}
?>