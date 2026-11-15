<?php
require_once __DIR__ . '/../config/Database.php';

class PembayaranModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // ================= POS KASIR =================

    // Antrean = pesanan yang belum punya pembayaran Lunas
    public function getAntrean() {
        $sql = "SELECT p.id_pesanan, p.kode_pesanan, p.no_meja, p.jenis_pesanan,
                       p.status, p.total_harga, p.waktu_pesan
                FROM pesanan p
                WHERE p.status NOT IN ('Dibatalkan', 'Selesai')
                  AND NOT EXISTS (
                      SELECT 1 FROM pembayaran b
                      WHERE b.id_pesanan = p.id_pesanan AND b.status = 'Lunas'
                  )
                ORDER BY p.waktu_pesan ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPesananByKode($kode) {
        $stmt = $this->conn->prepare("SELECT * FROM pesanan WHERE kode_pesanan = :kode LIMIT 1");
        $stmt->execute([':kode' => $kode]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getItemPesanan($idPesanan) {
        $sql = "SELECT dp.jumlah, dp.subtotal, dp.catatan, m.nama_menu, m.harga
                FROM detail_pesanan dp
                JOIN menu m ON dp.id_menu = m.id_menu
                WHERE dp.id_pesanan = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $idPesanan]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRingkasanHariIni() {
        $sql = "SELECT COUNT(*) AS jumlah, COALESCE(SUM(total), 0) AS total
                FROM pembayaran
                WHERE status = 'Lunas' AND DATE(waktu_bayar) = CURDATE()";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: ['jumlah' => 0, 'total' => 0];
    }

    // Simpan pembayaran Lunas + ubah status pesanan + catat transaksi (satu transaksi DB)
    public function bayarKasir($idPesanan, $metode, $total, $uang, $kembalian) {
        try {
            $this->conn->beginTransaction();

            $cek = $this->conn->prepare("SELECT COUNT(*) FROM pembayaran WHERE id_pesanan = :id AND status = 'Lunas'");
            $cek->execute([':id' => $idPesanan]);
            if ((int)$cek->fetchColumn() > 0) {
                $this->conn->rollBack();
                return ['ok' => false, 'pesan' => 'Pesanan ini sudah dibayar.'];
            }

            // Pakai baris pembayaran yang belum lunas (mis. dari pelanggan) bila ada
            $lama = $this->conn->prepare("SELECT id_pembayaran, kode_transaksi FROM pembayaran
                                          WHERE id_pesanan = :id AND status <> 'Lunas'
                                          ORDER BY id_pembayaran DESC LIMIT 1");
            $lama->execute([':id' => $idPesanan]);
            $row = $lama->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $idBayar = (int)$row['id_pembayaran'];
                $kodeTrx = $row['kode_transaksi'];
                $up = $this->conn->prepare("UPDATE pembayaran
                        SET metode = :m, total = :t, uang_diterima = :u, kembalian = :k,
                            status = 'Lunas', waktu_bayar = NOW()
                        WHERE id_pembayaran = :id");
                $up->execute([':m' => $metode, ':t' => $total, ':u' => $uang, ':k' => $kembalian, ':id' => $idBayar]);
            } else {
                $kodeTrx = 'PAY-' . date('Ymd') . '-' . random_int(1000, 9999);
                $in = $this->conn->prepare("INSERT INTO pembayaran
                        (kode_transaksi, id_pesanan, metode, total, uang_diterima, kembalian, status, waktu_bayar)
                        VALUES (:kode, :id, :m, :t, :u, :k, 'Lunas', NOW())");
                $in->execute([':kode' => $kodeTrx, ':id' => $idPesanan, ':m' => $metode,
                              ':t' => $total, ':u' => $uang, ':k' => $kembalian]);
                $idBayar = (int)$this->conn->lastInsertId();
            }

            // Status pesanan hanya diubah bila masih menunggu pembayaran
            $st = $this->conn->prepare("UPDATE pesanan SET status = 'Diproses'
                                        WHERE id_pesanan = :id AND status = 'Menunggu Pembayaran'");
            $st->execute([':id' => $idPesanan]);

            // Baris kas harian (id_kas wajib di tabel transaksi)
            $this->conn->exec("INSERT IGNORE INTO kas (tanggal) VALUES (CURDATE())");
            $idKas = (int)$this->conn->query("SELECT id_kas FROM kas WHERE tanggal = CURDATE()")->fetchColumn();

            $jenis = $this->conn->prepare("SELECT jenis_pesanan FROM pesanan WHERE id_pesanan = :id");
            $jenis->execute([':id' => $idPesanan]);
            $metodePesanan = mb_substr((string)$jenis->fetchColumn(), 0, 20);

            $tr = $this->conn->prepare("INSERT INTO transaksi
                    (id_pesanan, id_pembayaran, id_kas, metode_pembayaran, metode_pesanan,
                     status_pembayaran, tanggal, total_bayar)
                    VALUES (:p, :b, :k, :mp, :mo, 'Lunas', CURDATE(), :t)");
            $tr->execute([':p' => $idPesanan, ':b' => $idBayar, ':k' => $idKas,
                          ':mp' => $metode, ':mo' => $metodePesanan, ':t' => $total]);

            $this->conn->commit();
            return ['ok' => true, 'kode_transaksi' => $kodeTrx];
        } catch (Throwable $e) {
            if ($this->conn->inTransaction()) $this->conn->rollBack();
            return ['ok' => false, 'pesan' => 'Gagal menyimpan pembayaran: ' . $e->getMessage()];
        }
    }

    // ================= METHOD LAMA (dipakai PembayaranController sisi pelanggan) =================

    public function simpan_pembayaran($kodePesanan, $metode, $total) {
        $pesanan = $this->getPesananByKode($kodePesanan);
        if (!$pesanan) return null;

        $kodeTrx = 'PAY-' . date('Ymd') . '-' . random_int(1000, 9999);
        $stmt = $this->conn->prepare("INSERT INTO pembayaran (kode_transaksi, id_pesanan, metode, total, status)
                                      VALUES (:kode, :id, :m, :t, 'Menunggu Pembayaran')");
        $ok = $stmt->execute([':kode' => $kodeTrx, ':id' => $pesanan['id_pesanan'], ':m' => $metode, ':t' => $total]);

        if ($ok) {
            return [
                'idPembayaran'  => (int)$this->conn->lastInsertId(),
                'status'        => 'Menunggu Pembayaran',
                'kodeTransaksi' => $kodeTrx
            ];
        }
        return null;
    }

    public function get_data_pembayaran($kodePesanan) {
        $sql = "SELECT p.* FROM pembayaran p
                JOIN pesanan s ON p.id_pesanan = s.id_pesanan
                WHERE s.kode_pesanan = :kode
                ORDER BY p.id_pembayaran DESC LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':kode' => $kodePesanan]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function get_status_pembayaran($kodePesanan) {
        $data = $this->get_data_pembayaran($kodePesanan);
        return $data['status'] ?? null;
    }

    public function simpan_uang_tunai($idPembayaran, $uangDiterima, $kembalian) {
        $stmt = $this->conn->prepare("UPDATE pembayaran SET uang_diterima = :u, kembalian = :k
                                      WHERE id_pembayaran = :id");
        return $stmt->execute([':u' => $uangDiterima, ':k' => $kembalian, ':id' => $idPembayaran]);
    }

    public function ubah_status_pembayaran($idPembayaran, $status) {
        $stmt = $this->conn->prepare("UPDATE pembayaran
                SET status = :s1, waktu_bayar = IF(:s2 = 'Lunas', NOW(), waktu_bayar)
                WHERE id_pembayaran = :id");
        $stmt->execute([':s1' => $status, ':s2' => $status, ':id' => $idPembayaran]);

        $q = $this->conn->prepare("SELECT status FROM pembayaran WHERE id_pembayaran = :id");
        $q->execute([':id' => $idPembayaran]);
        return $q->fetchColumn() ?: null;
    }

    public function get_kode_pesanan($idPembayaran) {
        $sql = "SELECT s.kode_pesanan FROM pembayaran p
                JOIN pesanan s ON p.id_pesanan = s.id_pesanan
                WHERE p.id_pembayaran = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $idPembayaran]);
        return $stmt->fetchColumn() ?: null;
    }

    public function update_status_pesanan($kodePesanan, $status) {
        $stmt = $this->conn->prepare("UPDATE pesanan SET status = :s WHERE kode_pesanan = :kode");
        return $stmt->execute([':s' => $status, ':kode' => $kodePesanan]);
    }
}