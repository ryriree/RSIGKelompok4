<?php
require_once 'pendukung.php';

class DetailPesananModel {
    public function cariDetailByPesanan($idPesanan) {
        $sql = "SELECT dp.*, m.nama_menu, m.harga 
                FROM detail_pesanan dp 
                JOIN menu m ON dp.id_menu = m.id_menu 
                WHERE dp.id_pesanan = ?";
        return Database::jalankan($sql, 'i', [$idPesanan])->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>