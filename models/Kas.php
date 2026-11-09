<?php
require_once __DIR__ . '/../config/Database.php';

class Kas {
    private $conn;

    // Atribut entitas Kas
    private $idKas;
    private $kasFisik;
    private $kasSistem;
    private $selisih;
    private $statusSelisih;
    private $tanggal;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // PUBLIC METHOD: Memeriksa ketersediaan catatan kas harian
    public function cekKasTanggal($tanggal) {
        $query = "SELECT COUNT(*) FROM kas WHERE DATE(tanggal) = :tanggal";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':tanggal' => $tanggal]);
        return $stmt->fetchColumn() > 0;
    }

    // PUBLIC METHOD: Query data kas berdasarkan tanggal
    public function getDataKas($tanggal) {
        $query = "SELECT * FROM kas WHERE DATE(tanggal) = :tanggal LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':tanggal' => $tanggal]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return [
                'kas_sistem' => 0.00,
                'kas_fisik' => 0.00,
                'selisih' => 0.00,
                'status_selisih' => 'Sesuai'
            ];
        }
        return $data;
    }

    // PUBLIC METHOD: Menyimpan / memperbarui record kas fisik & selisih
    public function simpanDataKas($tanggal, $kasFisik, $kasSistem, $selisih, $statusSelisih) {
        if ($this->cekKasTanggal($tanggal)) {
            // Update Data Kas Lama
            $query = "UPDATE kas SET kas_fisik = :kasFisik, kas_sistem = :kasSistem, selisih = :selisih, status_selisih = :statusSelisih 
                      WHERE DATE(tanggal) = :tanggal";
        } else {
            // Insert Data Kas Baru
            $query = "INSERT INTO kas (tanggal, kas_fisik, kas_sistem, selisih, status_selisih) 
                      VALUES (:tanggal, :kasFisik, :kasSistem, :selisih, :statusSelisih)";
        }

        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':tanggal' => $tanggal,
            ':kasFisik' => $kasFisik,
            ':kasSistem' => $kasSistem,
            ':selisih' => $selisih,
            ':statusSelisih' => $statusSelisih
        ]);
    }
}