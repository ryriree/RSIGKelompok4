<?php
require_once __DIR__ . '/../config/Database.php';

class Menu {
    private $conn;
    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }
    public function getSemuaMenu() {
        $stmt = $this->conn->query("SELECT * FROM menu ORDER BY kategori, nama_menu");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function tambahMenu($nama, $kategori, $harga, $deskripsi, $tersedia) {
        $sql = "INSERT INTO menu (nama_menu, kategori, harga, deskripsi, status_tersedia) VALUES (:nama, :kategori, :harga, :deskripsi, :tersedia)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':nama'=>$nama, ':kategori'=>$kategori, ':harga'=>$harga, ':deskripsi'=>$deskripsi, ':tersedia'=>$tersedia]);
    }
    public function ubahMenu($id, $nama, $kategori, $harga, $deskripsi, $tersedia) {
        $sql = "UPDATE menu SET nama_menu=:nama, kategori=:kategori, harga=:harga, deskripsi=:deskripsi, status_tersedia=:tersedia WHERE id_menu=:id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':nama'=>$nama, ':kategori'=>$kategori, ':harga'=>$harga, ':deskripsi'=>$deskripsi, ':tersedia'=>$tersedia, ':id'=>(int)$id]);
    }
    public function ubahKetersediaan($id, $tersedia) {
        $stmt = $this->conn->prepare("UPDATE menu SET status_tersedia=:tersedia WHERE id_menu=:id");
        return $stmt->execute([':tersedia'=>(int)$tersedia, ':id'=>(int)$id]);
    }
}
