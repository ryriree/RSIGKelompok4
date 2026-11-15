<?php
require_once __DIR__ . '/../models/StrukModel.php';

class StrukController {
    private $strukModel;

    public function __construct() {
        $this->strukModel = new StrukModel();
    }

    public function index() {
        $pesanStruk = $_SESSION['pesan_pos'] ?? null;
        unset($_SESSION['pesan_pos']);

        $riwayat    = $this->strukModel->getRiwayatTransaksi();
        $selectedId = (int)($_GET['id'] ?? ($riwayat[0]['id_pesanan'] ?? 0));
        $strukAktif = $selectedId ? $this->strukModel->getStrukDetail($selectedId) : null;

        require BASE_PATH . '/views/internal/kasir/cetak_struk.php';
    }
}