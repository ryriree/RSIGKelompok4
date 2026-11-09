<?php
require_once __DIR__ . '/../config/Database.php';

/**
 * Model khusus kebutuhan internal (Admin/Manajer) untuk mengelola laporan.
 * Model LaporanKendala.php milik sisi Customer tetap dipertahankan.
 */
class LaporanKendalaInternal {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getRingkasan() {
        $sql = "SELECT COUNT(*) AS total,
                       COALESCE(SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END), 0) AS pending,
                       COALESCE(SUM(CASE WHEN status = 'Selesai' THEN 1 ELSE 0 END), 0) AS selesai
                FROM laporan_kendala";
        return $this->conn->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    public function getDaftarLaporan($keyword = '', $jenis = 'Semua', $status = 'Semua', $limit = 5, $offset = 0) {
        $sql = "SELECT lk.*, u.nama AS nama_penangan
                FROM laporan_kendala lk
                LEFT JOIN users u ON lk.id_user_penangan = u.id_user
                WHERE (lk.nama LIKE :keyword OR lk.kontak LIKE :keyword OR lk.isi_pesan LIKE :keyword)";
        $params = [':keyword' => '%' . $keyword . '%'];

        if ($jenis !== 'Semua' && in_array($jenis, ['Komplain', 'Feedback'], true)) {
            $sql .= " AND lk.jenis_laporan = :jenis";
            $params[':jenis'] = $jenis;
        }
        if ($status !== 'Semua' && in_array($status, ['Pending', 'Diproses', 'Selesai'], true)) {
            $sql .= " AND lk.status = :status";
            $params[':status'] = $status;
        }

        $sql .= " ORDER BY lk.tanggal_kirim DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', max(1, (int)$limit), PDO::PARAM_INT);
        $stmt->bindValue(':offset', max(0, (int)$offset), PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function hitungDaftarLaporan($keyword = '', $jenis = 'Semua', $status = 'Semua') {
        $sql = "SELECT COUNT(*) FROM laporan_kendala
                WHERE (nama LIKE :keyword OR kontak LIKE :keyword OR isi_pesan LIKE :keyword)";
        $params = [':keyword' => '%' . $keyword . '%'];

        if ($jenis !== 'Semua' && in_array($jenis, ['Komplain', 'Feedback'], true)) {
            $sql .= " AND jenis_laporan = :jenis";
            $params[':jenis'] = $jenis;
        }
        if ($status !== 'Semua' && in_array($status, ['Pending', 'Diproses', 'Selesai'], true)) {
            $sql .= " AND status = :status";
            $params[':status'] = $status;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function getLaporanById($idLaporan) {
        $stmt = $this->conn->prepare('SELECT * FROM laporan_kendala WHERE id_laporan = :id');
        $stmt->execute([':id' => (int)$idLaporan]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function ubahStatusLaporan($idLaporan, $status, $catatan, $idUser) {
        $sql = "UPDATE laporan_kendala
                SET status = :status,
                    catatan_penanganan = :catatan,
                    id_user_penangan = :id_user,
                    tanggal_diperbarui = CURRENT_TIMESTAMP
                WHERE id_laporan = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':status' => $status,
            ':catatan' => ($catatan !== '' ? $catatan : null),
            ':id_user' => (int)$idUser,
            ':id' => (int)$idLaporan
        ]);
    }
}
