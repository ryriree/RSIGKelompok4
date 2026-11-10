<?php
require_once 'pendukung.php';
require_once 'PesananModel.php';
require_once 'DetailPesananModel.php';
require_once 'StrukModel.php';

class StrukController {
    private $pesananModel;
    private $detailModel;
    private $strukModel;

    public function __construct() {
        $this->pesananModel = new PesananModel();
        $this->detailModel = new DetailPesananModel();
        $this->strukModel = new StrukModel();
    }

    public function prosesDataPesanan($idPesanan) {
        $dataPesanan = $this->pesananModel->cariPesananById((int)$idPesanan);
        $daftarItem = $this->detailModel->cariDetailByPesanan((int)$idPesanan);
        $dataStruk = $this->buatDataStruk($dataPesanan, $daftarItem);

        if ($this->cekKelengkapanData($dataStruk)) {
            $statusSimpan = $this->strukModel->simpanStruk($dataStruk);
            if ($statusSimpan) {
                Respon::kirim('kirimDataKePrinter', ['dataStruk' => $dataStruk, 'daftarItem' => $daftarItem]);
            }
        } else {
            Respon::kirim('beriTahuDataTidakLengkap', ['pesan' => 'Data pesanan tidak lengkap untuk cetak struk']);
        }
    }

    private function buatDataStruk($dataPesanan, $daftarItem) {
        $total = 0;
        foreach ($daftarItem as $item) {
            $total += $item['subtotal'];
        }
        return [
            'id_pesanan' => $dataPesanan['id_pesanan'],
            'no_nota' => 'MC-' . date('Ymd') . '-' . sprintf('%04d', $dataPesanan['id_pesanan']),
            'total_bayar' => $total,
            'metode_pembayaran' => $dataPesanan['metode_pembayaran'] ?? 'Tunai',
            'waktu_transaksi' => $dataPesanan['waktu_pesan']
        ];
    }

    private function cekKelengkapanData($dataStruk) {
        if (empty($dataStruk['no_nota']) || $dataStruk['total_bayar'] <= 0 || empty($dataStruk['metode_pembayaran'])) {
            return false;
        }
        return true;
    }
}
?>