<?php
require_once 'pendukung.php';
require_once 'PesananModel.php';
require_once 'MenuModel.php';

class PesananController {
    private $pesananModel;
    private $menuModel;

    public function __construct() {
        $this->pesananModel = new PesananModel();
        $this->menuModel = new MenuModel();
    }

    public function bukaMenu($noMeja) {
        if ($noMeja <= 0) return Respon::kirim('error', ['pesan' => 'QR Code meja tidak valid']);
        $daftarMenu = $this->menuModel->getMenuTersedia();
        Respon::kirim('tampilkanDaftarMenu', ['noMeja' => $noMeja, 'daftarMenu' => $daftarMenu]);
    }

    public function hitungEstimasi($items) {
        $totalItem = 0;
        foreach ($items as $item) {
            $totalItem += (int)$item['jumlah'];
        }
        return 5 + (2 * $totalItem);
    }

    public function kirimPesanan($noMeja, $items, $opsiAntar = 'Diantar ke meja') {
        if (empty($items)) return Respon::kirim('error', ['pesan' => 'Keranjang pesanan kosong']);
        
        $estimasiMenit = $this->hitungEstimasi($items);
        $hasil = $this->pesananModel->simpanPesanan($noMeja, $items, $opsiAntar, $estimasiMenit);

        if ($hasil) {
            Respon::kirim('tampilkanBerhasil', [
                'id_pesanan' => $hasil['id_pesanan'],
                'kode_pesanan' => $hasil['kode_pesanan'],
                'estimasi_menit' => $estimasiMenit
            ]);
        } else {
            Respon::kirim('error', ['pesan' => 'Gagal menyimpan pesanan']);
        }
    }

    public function ambilPesananAktif() {
        $daftarPesanan = $this->pesananModel->getPesananAktif();
        Respon::kirim('tampilkanDaftarPesanan', ['daftarPesanan' => $daftarPesanan]);
    }

    public function filterPesanan($noMeja, $status) {
        $daftarFilter = $this->pesananModel->getByFilter((int)$noMeja, $status);
        if ($daftarFilter !== null) {
            Respon::kirim('tampilkanDaftarFilter', ['daftarFilter' => $daftarFilter]);
        } else {
            Respon::kirim('tampilkanPesanError', ['pesan' => 'Data pesanan tidak ditemukan']);
        }
    }

    public function ambilDetail($idPesanan) {
        $detail = $this->pesananModel->getDetailPesanan((int)$idPesanan);
        if ($detail !== null) {
            Respon::kirim('tampilkanDetailPesanan', ['detail' => $detail]);
        } else {
            Respon::kirim('tampilkanStatusTidakAda', ['pesan' => 'Pesanan tidak ditemukan']);
        }
    }

    public function updateStatus($idPesanan, $status = 'Selesai') {
        $detail = $this->pesananModel->getDetailPesanan((int)$idPesanan);
        if ($detail === null) {
            return Respon::kirim('tampilkanStatusTidakAda', ['pesan' => 'Pesanan tidak ditemukan']);
        }
        
        if ($status !== 'Selesai' || $detail['status'] !== 'Siap Disajikan') {
            return Respon::kirim('tampilkanPesanError', ['pesan' => 'Status pesanan tidak valid untuk diselesaikan']);
        }

        $waktuSekarang = date('Y-m-d H:i:s');
        $berhasil = $this->pesananModel->setSelesai((int)$idPesanan, $waktuSekarang);

        if ($berhasil) {
            Respon::kirim('tampilkanStatusBerhasil', ['pesan' => 'Status pesanan berhasil diperbarui menjadi Selesai']);
        } else {
            Respon::kirim('tampilkanPesanError', ['pesan' => 'Gagal memperbarui status pesanan']);
        }
    }
}
?>