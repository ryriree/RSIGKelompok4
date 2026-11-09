<?php
require_once __DIR__ . '/../config/Database.php';

class LaporanKendala {
    private $conn;

    // Attributes sesuai Class Diagram & Schema DDL
    private $idLaporan;
    private $nama;
    private $kontak;
    private $jenisLaporan;
    private $isiPesan;
    private $status;
    private $tanggalKirim;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // PUBLIC METHOD Sesuai Pseudocode: simpanLaporan(...) -> boolean
    public function simpanLaporan($nama, $kontak, $jenisLaporan, $isiPesan, $status = 'Pending') {
        try {
            $query = "INSERT INTO laporan_kendala (nama, kontak, jenis_laporan, isi_pesan, status, tanggal_kirim) 
                      VALUES (:nama, :kontak, :jenis_laporan, :isi_pesan, :status, NOW())";
            
            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute([
                ':nama'          => $nama,
                ':kontak'        => $kontak,
                ':jenis_laporan' => $jenisLaporan,
                ':isi_pesan'     => $isiPesan,
                ':status'        => $status
            ]);

            return $result;
        } catch (PDOException $e) {
            error_log("Error Simpan Laporan Kendala: " . $e->getMessage());
            return false;
        }
    }
}