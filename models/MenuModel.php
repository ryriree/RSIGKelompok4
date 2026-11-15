<?php
require_once __DIR__ . '/../config/Database.php';

class MenuModel
{
    private $conn;
    private $table_name = "menu";

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Dipakai halaman pelanggan (filter kategori)
    public function getAllMenu($kategori = null)
    {
        if ($kategori && $kategori !== 'Semua') {
            $stmt = $this->conn->prepare("SELECT * FROM {$this->table_name} WHERE kategori = ? ORDER BY nama_menu ASC");
            $stmt->execute([$kategori]);
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM {$this->table_name} ORDER BY nama_menu ASC");
            $stmt->execute();
        }
        return $stmt->fetchAll();
    }

    // Dipakai halaman Kelola Menu
    public function getSemuaMenu()
    {
        return $this->getAllMenu();
    }

    public function tambahMenu($nama, $kategori, $harga, $deskripsi, $tersedia)
    {
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO {$this->table_name} (nama_menu, kategori, harga, deskripsi, status_tersedia)
                 VALUES (?, ?, ?, ?, ?)"
            );
            return $stmt->execute([$nama, $kategori, $harga, $deskripsi, $tersedia]);
        } catch (Throwable $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function ubahMenu($id, $nama, $kategori, $harga, $deskripsi, $tersedia)
    {
        try {
            $stmt = $this->conn->prepare(
                "UPDATE {$this->table_name}
                 SET nama_menu = ?, kategori = ?, harga = ?, deskripsi = ?, status_tersedia = ?
                 WHERE id_menu = ?"
            );
            return $stmt->execute([$nama, $kategori, $harga, $deskripsi, $tersedia, $id]);
        } catch (Throwable $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function ubahKetersediaan($id, $tersedia)
    {
        try {
            $stmt = $this->conn->prepare("UPDATE {$this->table_name} SET status_tersedia = ? WHERE id_menu = ?");
            return $stmt->execute([$tersedia, $id]);
        } catch (Throwable $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}