<?php
require_once 'pendukung.php';

class PesananModel {
    public function simpanPesanan($noMeja, $items, $opsiAntar, $estimasiMenit) {
        $db = Database::koneksi();
        $db->begin_transaction();
        try {
            $kodePesanan = 'MC-' . date('YmdHis') . '-' . rand(100, 999);
            $totalHarga = 0;

            foreach ($items as $item) {
                $totalHarga += $item['subtotal'];
            }

            $sql = "INSERT INTO pesanan (kode_pesanan, no_meja, status, total_harga, estimasi_menit, opsi_antar, jenis_pesanan, waktu_pesan) 
                    VALUES (?, ?, 'Menunggu Pembayaran', ?, ?, ?, 'Dine In', NOW())";
            $stmt = Database::jalankan($sql, 'sidss', [$kodePesanan, $noMeja, $totalHarga, $estimasiMenit, $opsiAntar]);
            $idPesanan = $stmt->insert_id;

            $sqlDetail = "INSERT INTO detail_pesanan (id_pesanan, id_menu, jumlah, subtotal, catatan) VALUES (?, ?, ?, ?, ?)";
            foreach ($items as $item) {
                Database::jalankan($sqlDetail, 'iiids', [
                    $idPesanan, $item['id_menu'], $item['jumlah'], $item['subtotal'], $item['catatan']
                ]);
            }

            $db->commit();
            return ['id_pesanan' => $idPesanan, 'kode_pesanan' => $kodePesanan];
        } catch (Exception $e) {
            $db->rollback();
            return false;
        }
    }

    public function getPesananAktif() {
        $sql = "SELECT p.*, GROUP_CONCAT(CONCAT(m.nama_menu, ' x', dp.jumlah) SEPARATOR ', ') as ringkasan_item 
                FROM pesanan p 
                JOIN detail_pesanan dp ON p.id_pesanan = dp.id_pesanan 
                JOIN menu m ON dp.id_menu = m.id_menu 
                WHERE p.status IN ('Dalam Proses', 'Siap Disajikan') 
                GROUP BY p.id_pesanan 
                ORDER BY p.waktu_pesan ASC";
        return Database::jalankan($sql)->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getByFilter($noMeja, $status) {
        $sql = "SELECT p.*, GROUP_CONCAT(CONCAT(m.nama_menu, ' x', dp.jumlah) SEPARATOR ', ') as ringkasan_item 
                FROM pesanan p 
                JOIN detail_pesanan dp ON p.id_pesanan = dp.id_pesanan 
                JOIN menu m ON dp.id_menu = m.id_menu 
                WHERE (? IS NULL OR p.no_meja = ?) AND (? IS NULL OR p.status = ?)
                GROUP BY p.id_pesanan ORDER BY p.waktu_pesan ASC";
        $paramMeja = $noMeja > 0 ? $noMeja : null;
        $paramStatus = !empty($status) ? $status : null;
        return Database::jalankan($sql, 'iiss', [$paramMeja, $paramMeja, $paramStatus, $paramStatus])->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getDetailPesanan($idPesanan) {
        $sqlPesanan = "SELECT * FROM pesanan WHERE id_pesanan = ?";
        $pesanan = Database::jalankan($sqlPesanan, 'i', [$idPesanan])->get_result()->fetch_assoc();

        if (!$pesanan) return null;

        $sqlDetail = "SELECT dp.*, m.nama_menu, m.harga 
                      FROM detail_pesanan dp 
                      JOIN menu m ON dp.id_menu = m.id_menu 
                      WHERE dp.id_pesanan = ?";
        $items = Database::jalankan($sqlDetail, 'i', [$idPesanan])->get_result()->fetch_all(MYSQLI_ASSOC);

        $pesanan['items'] = $items;
        return $pesanan;
    }

    public function setSelesai($idPesanan, $waktuSajikan) {
        $sql = "UPDATE pesanan SET status = 'Selesai', waktu_sajikan = ? WHERE id_pesanan = ?";
        return Database::jalankan($sql, 'si', [$waktuSajikan, $idPesanan])->affected_rows > 0;
    }

    public function cariPesananById($idPesanan) {
        $sql = "SELECT * FROM pesanan WHERE id_pesanan = ?";
        return Database::jalankan($sql, 'i', [$idPesanan])->get_result()->fetch_assoc();
    }
}
?>