<?php
require_once 'pendukung.php';

class StrukModel {
    public function simpanStruk($dataStruk) {
        $sql = "INSERT INTO struk (id_pesanan, no_nota, total_bayar, metode_pembayaran, waktu_transaksi, waktu_cetak) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        return Database::jalankan($sql, 'isdss', [
            $dataStruk['id_pesanan'],
            $dataStruk['no_nota'],
            $dataStruk['total_bayar'],
            $dataStruk['metode_pembayaran'],
            $dataStruk['waktu_transaksi']
        ])->affected_rows > 0;
    }
}
?>