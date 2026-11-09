<?php
require_once __DIR__ . '/../config/Database.php';

class KontakAdmin {
    private $conn;
    private $nomorWhatsapp;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // PUBLIC METHOD Sesuai Pseudocode: getKontakAdmin() -> String
    public function getKontakAdmin() {
        $query = "SELECT nomor_whatsapp FROM kontak_admin ORDER BY id_kontak ASC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && !empty($row['nomor_whatsapp'])) {
            $this->nomorWhatsapp = $row['nomor_whatsapp'];
        } else {
            // Fallback default nomor WhatsApp jika database belum di-seed
            $this->nomorWhatsapp = '6281234567890';
        }

        return $this->nomorWhatsapp;
    }
}