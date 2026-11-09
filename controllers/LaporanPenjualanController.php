<?php
require_once __DIR__ . '/../models/Transaksi.php';
require_once __DIR__ . '/../models/Kas.php';

class LaporanPenjualanController {
    private $dataPenjualan;
    private $tanggal;
    private $transaksiModel;
    private $kasModel;

    public function __construct() {
        $this->transaksiModel = new Transaksi();
        $this->kasModel = new Kas();
    }

    public function pilihMenuLaporanPenjualan() {
        $this->tanggal = $_GET['tanggal'] ?? date('Y-m-d');
        $this->pilihTanggalFilter($this->tanggal);
    }

    public function pilihTanggalFilter($tanggal) {
        $this->tanggal = $tanggal;
        $this->dataPenjualan = $this->ambilDataPenjualan($tanggal);
        $dataKas = $this->kasModel->getDataKas($tanggal);

        // Jika kas_sistem di DB belum ter-sync, ambil kas sistem realtime
        if ($dataKas['kas_sistem'] == 0) {
            $dataKas['kas_sistem'] = $this->transaksiModel->getTotalKasSistem($tanggal);
        }

        $this->tampilkanDashboard($this->dataPenjualan, $dataKas);
    }

    public function ambilDataPenjualan($tanggal) {
        return $this->transaksiModel->getDataPenjualan($tanggal);
    }

    public function tampilkanDashboard($dataPenjualan, $dataKas) {
        $tanggal = $this->tanggal;
        // Panggil View HalamanDashboardPenjualan
        require_once __DIR__ . '/../views/internal/admin/laporan_penjualan.php';
    }

    public function tampilkanMuatUlang($pesan) {
        echo "<script>console.log('".$pesan."');</script>";
    }
}