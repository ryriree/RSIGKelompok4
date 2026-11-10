<?php
require_once __DIR__ . '/../config/Database.php';

class MenuModel {
    private $conn;
    private $table_name = "menu";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllMenu($kategori = null) {
        if ($kategori && $kategori !== 'Semua') {
            $query = "SELECT * FROM " . $this->table_name . " WHERE kategori = :kategori ORDER BY nama_menu ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':kategori', $kategori);
        } else {
            $query = "SELECT * FROM " . $this->table_name . " ORDER BY nama_menu ASC";
            $stmt = $this->conn->prepare($query);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>