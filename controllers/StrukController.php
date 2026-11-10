<?php
require_once __DIR__ . '/../models/StrukModel.php';

class StrukController {
    private $strukModel;

    public function __construct() {
        $this->strukModel = new StrukModel();
    }

    public function index() {
        $riwayat = $this->strukModel->getRiwayatTransaksi();
        $selectedId = $_GET['id'] ?? ($riwayat[0]['id_pesanan'] ?? null);
        $strukAktif = $selectedId ? $this->strukModel->getStrukDetail($selectedId) : null;

        require_once __DIR__ . '/../views/internal/kasir/cetak_struk.php';
    }
}
?>