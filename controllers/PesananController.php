<?php
require_once __DIR__ . '/../models/MenuModel.php';
require_once __DIR__ . '/../models/PesananModel.php';
require_once __DIR__ . '/../models/DetailPesananModel.php';

class PesananController {
    private $menuModel;
    private $pesananModel;
    private $detailPesananModel;

    public function __construct() {
        $this->menuModel = new MenuModel();
        $this->pesananModel = new PesananModel();
        $this->detailPesananModel = new DetailPesananModel();
    }

    public function formPesanQR() {
        $menus = $this->menuModel->getAllMenu();
        $noMeja = isset($_GET['meja']) ? $_GET['meja'] : '12';
        require_once __DIR__ . '/../views/pelanggan/pesan_qr.php';
    }

    public function submitPesanan() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $noMeja = $_POST['no_meja'];
            $jenisPesanan = $_POST['jenis_pesanan'] ?? 'Dine In';
            $items = $_POST['items'] ?? [];

            $totalHarga = 0;
            $orderItems = [];

            foreach ($items as $idMenu => $data) {
                $qty = (int)$data['jumlah'];
                if ($qty > 0) {
                    $harga = (float)$data['harga'];
                    $subtotal = $qty * $harga;
                    $totalHarga += $subtotal;
                    $orderItems[] = [
                        'id_menu' => $idMenu,
                        'jumlah' => $qty,
                        'subtotal' => $subtotal,
                        'catatan' => $data['catatan'] ?? ''
                    ];
                }
            }

            if (!empty($orderItems)) {
                $kodePesanan = 'TRX-' . date('Ymd') . '-' . rand(1000, 9999);
                $idPesanan = $this->pesananModel->createPesanan($kodePesanan, $noMeja, $jenisPesanan, $totalHarga, 'Dalam Proses');

                if ($idPesanan) {
                    foreach ($orderItems as $item) {
                        $this->detailPesananModel->addDetail($idPesanan, $item['id_menu'], $item['jumlah'], $item['subtotal'], $item['catatan']);
                    }
                    echo "<script>alert('Pesanan berhasil dibuat! Kode: " . $kodePesanan . "'); window.location.href='index.php?action=status_pelayan';</script>";
                    exit();
                }
            }
        }
    }

    public function statusPelayan() {
        $antreanPesanan = $this->pesananModel->getAllPesanan();
        require_once __DIR__ . '/../views/internal/pelayan/status_pesanan.php';
    }

    public function updateStatus() {
        if (isset($_POST['id_pesanan']) && isset($_POST['status'])) {
            $this->pesananModel->updateStatus($_POST['id_pesanan'], $_POST['status']);
            header("Location: index.php?action=status_pelayan");
            exit();
        }
    }
}
?>