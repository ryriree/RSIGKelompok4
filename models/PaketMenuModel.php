<?php
require_once __DIR__ . '/pendukung.php';

class PaketMenuModel
{
    private PDO $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function get_data_paket($paketId)
    {
        $stmt = $this->conn->prepare(
            "SELECT id_paket, nama_paket, deskripsi, harga
             FROM paket_menu
             WHERE id_paket = ?"
        );

        $stmt->execute([(int) $paketId]);

        return $stmt->fetch() ?: null;
    }

    public function getall_data_paket()
    {
        $stmt = $this->conn->query(
            "SELECT id_paket, nama_paket, deskripsi, harga
             FROM paket_menu
             ORDER BY nama_paket ASC"
        );

        return $stmt->fetchAll();
    }
}