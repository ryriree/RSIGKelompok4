<?php
require_once __DIR__ . '/../models/MenuModel.php';
require_once __DIR__ . '/../models/PesananModel.php';

class PesananController {
    const MAKS_MEJA = 20;

    private $menuModel;
    private $pesananModel;

    public function __construct() {
        $this->menuModel = new MenuModel();
        $this->pesananModel = new PesananModel();
    }

    private function flash($tipe, $teks) {
        $_SESSION['pesan_pesan'] = ['tipe' => $tipe, 'teks' => $teks];
    }

    // ===== PELANGGAN: ?page=pesan&meja=12 =====
    public function formPesan() {
        $pesanPesan = $_SESSION['pesan_pesan'] ?? null;
        unset($_SESSION['pesan_pesan']);

        $noMeja = (int)($_GET['meja'] ?? 0);
        if ($noMeja < 1 || $noMeja > self::MAKS_MEJA) $noMeja = 0;

        $menus = $this->menuModel->getAllMenu();
        require BASE_PATH . '/views/pelanggan/pesan_qr.php';
    }

    // ===== PELANGGAN: ?page=pesan-kirim (POST) =====
    // Harga SELALU diambil dari database, bukan dari form.
    public function kirimPesanan() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('pesan');

        $noMeja = (int)($_POST['no_meja'] ?? 0);
        if ($noMeja < 1 || $noMeja > self::MAKS_MEJA) {
            $this->flash('error', 'Pilih nomor meja terlebih dulu.');
            redirect('pesan');
        }
        $back = 'pesan&meja=' . $noMeja;

        $daftar = [];
        foreach ($this->menuModel->getAllMenu() as $m) {
            $daftar[(int)$m['id_menu']] = $m;
        }

        $total = 0;
        $orderItems = [];
        foreach ((array)($_POST['items'] ?? []) as $idMenu => $data) {
            $data = (array)$data;
            $id   = (int)$idMenu;
            $qty  = min(50, (int)($data['jumlah'] ?? 0));
            if ($qty < 1 || !isset($daftar[$id])) continue;

            $m = $daftar[$id];
            if (!(int)$m['status_tersedia']) {
                $this->flash('error', 'Maaf, menu "' . $m['nama_menu'] . '" sedang habis.');
                redirect($back);
            }

            $subtotal = $qty * (float)$m['harga'];
            $total += $subtotal;
            $orderItems[] = [
                'id_menu'  => $id,
                'jumlah'   => $qty,
                'subtotal' => $subtotal,
                'catatan'  => mb_substr(trim((string)($data['catatan'] ?? '')), 0, 255),
            ];
        }

        if (!$orderItems) {
            $this->flash('error', 'Pilih minimal satu menu.');
            redirect($back);
        }

        $kode = $this->pesananModel->buatPesananLengkap($noMeja, 'Dine In', $total, $orderItems);
        if (!$kode) {
            $this->flash('error', 'Pesanan gagal dibuat. Silakan coba lagi.');
            redirect($back);
        }

        redirect('bayar&kode=' . urlencode($kode));
    }

    // ===== STAFF: PELAYAN =====
    public function statusPelayan() {
        $pesanPesanan = $_SESSION['pesan_pesanan'] ?? null;
        unset($_SESSION['pesan_pesanan']);

        $antreanPesanan = $this->pesananModel->getAntreanPelayan();
        require BASE_PATH . '/views/internal/Pelayan/status_pesanan.php';
    }

    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('status-pesanan');

        $id     = (int)($_POST['id_pesanan'] ?? 0);
        $status = (string)($_POST['status'] ?? '');

        if ($id > 0 && $status === 'Selesai') {
            $this->pesananModel->updateStatus($id, $status);
            $_SESSION['pesan_pesanan'] = 'Pesanan ditandai selesai.';
        }
        redirect('status-pesanan');
    }
}
?>