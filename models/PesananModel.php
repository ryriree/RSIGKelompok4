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

    // Pesanan pelanggan: header + detail dalam satu transaksi.
    // Mengembalikan kode_pesanan, atau null bila gagal.
    public function buatPesananLengkap($noMeja, $jenisPesanan, $totalHarga, array $items) {
        try {
            $this->conn->beginTransaction();

            $kode = null;
            for ($i = 0; $i < 5 && $kode === null; $i++) {
                $coba = 'TRX-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
                $cek = $this->conn->prepare("SELECT 1 FROM pesanan WHERE kode_pesanan = :k");
                $cek->execute([':k' => $coba]);
                if (!$cek->fetchColumn()) $kode = $coba;
            }
            if ($kode === null) throw new RuntimeException('Gagal membuat kode pesanan');

            $in = $this->conn->prepare("INSERT INTO pesanan (kode_pesanan, no_meja, jenis_pesanan, status, total_harga, waktu_pesan)
                                        VALUES (:kode, :meja, :jenis, 'Menunggu Pembayaran', :total, NOW())");
            $in->execute([':kode' => $kode, ':meja' => $noMeja, ':jenis' => $jenisPesanan, ':total' => $totalHarga]);
            $idPesanan = (int)$this->conn->lastInsertId();

            $det = $this->conn->prepare("INSERT INTO detail_pesanan (id_pesanan, id_menu, jumlah, subtotal, catatan)
                                         VALUES (:p, :m, :j, :s, :c)");
            foreach ($items as $it) {
                $det->execute([':p' => $idPesanan, ':m' => $it['id_menu'], ':j' => $it['jumlah'],
                               ':s' => $it['subtotal'], ':c' => $it['catatan']]);
            }

            $this->conn->commit();
            return $kode;
        } catch (Throwable $e) {
            if ($this->conn->inTransaction()) $this->conn->rollBack();
            error_log($e->getMessage());
            return null;
        }
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

    // Antrean pelayan: pesanan yang sudah dibayar/diproses + selesai hari ini
    public function getAntreanPelayan() {
        $query = "SELECT p.*,
                    (SELECT GROUP_CONCAT(CONCAT(m.nama_menu, ' x', dp.jumlah, IF(dp.catatan IS NOT NULL AND dp.catatan <> '', CONCAT(' (', dp.catatan, ')'), '')) SEPARATOR ', ')
                     FROM detail_pesanan dp
                     JOIN menu m ON dp.id_menu = m.id_menu
                     WHERE dp.id_pesanan = p.id_pesanan) AS rincian_item
                  FROM " . $this->table_name . " p
                  WHERE p.status IN ('Diproses', 'Dalam Proses')
                     OR (p.status = 'Selesai' AND DATE(p.waktu_pesan) = CURDATE())
                  ORDER BY (p.status = 'Selesai') ASC, p.waktu_pesan ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($idPesanan, $statusBaru) {
        $query = "UPDATE " . $this->table_name . "
                  SET status = :status,
                      waktu_sajikan = IF(:status2 = 'Selesai', NOW(), waktu_sajikan)
                  WHERE id_pesanan = :id_pesanan";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':status'     => $statusBaru,
            ':status2'    => $statusBaru,
            ':id_pesanan' => $idPesanan
        ]);
    }
}
?>