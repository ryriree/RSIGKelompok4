<?php
// UC: Antrean POS Kasir & Pembayaran
class PosController {
    private $model;

    public function __construct() {
        $this->model = new PembayaranModel();
    }

    // Tampilan antrean + detail pesanan terpilih
    public function index() {
        $pesanPos = $_SESSION['pesan_pos'] ?? null;
        unset($_SESSION['pesan_pos']);

        $antrean = $this->model->getAntrean();

        $kode  = trim((string)($_GET['kode'] ?? ''));
        $aktif = null;
        foreach ($antrean as $p) {
            if ($p['kode_pesanan'] === $kode) { $aktif = $p; break; }
        }
        if (!$aktif && $antrean) $aktif = $antrean[0];

        $items     = $aktif ? $this->model->getItemPesanan((int)$aktif['id_pesanan']) : [];
        $ringkasan = $this->model->getRingkasanHariIni();

        require BASE_PATH . '/views/internal/kasir/Kasir_pos.php';
    }

    // Proses bayar (POST) -> redirect + flash
    public function bayar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('pos');

        $kode   = trim((string)($_POST['kode_pesanan'] ?? ''));
        $metode = (string)($_POST['metode'] ?? 'Tunai');
        $uang   = (int)preg_replace('/\D/', '', (string)($_POST['uang_diterima'] ?? '0'));

        if (!in_array($metode, ['Tunai', 'QRIS', 'Debit EDC'], true)) {
            $this->flash('error', 'Metode pembayaran tidak valid.');
            redirect('pos');
        }

        $pesanan = $this->model->getPesananByKode($kode);
        if (!$pesanan) {
            $this->flash('error', 'Pesanan tidak ditemukan.');
            redirect('pos');
        }

        $total = (float)$pesanan['total_harga'];
        $back  = 'pos&kode=' . urlencode($kode);

        if ($metode === 'Tunai') {
            if ($uang < $total) {
                $this->flash('error', 'Nominal uang diterima kurang dari total tagihan.');
                redirect($back);
            }
            $kembalian = $uang - $total;
        } else {
            $uang      = (int)$total;
            $kembalian = 0;
        }

        $hasil = $this->model->bayarKasir((int)$pesanan['id_pesanan'], $metode, $total, $uang, $kembalian);

        if ($hasil['ok']) {
            $msg = 'Pembayaran #' . $kode . ' berhasil (' . $metode . ').';
            if ($metode === 'Tunai') {
                $msg .= ' Kembalian: Rp' . number_format($kembalian, 0, ',', '.');
            }
            $this->flash('ok', $msg);
            redirect('struk&id=' . (int)$pesanan['id_pesanan']);
        }

        $this->flash('error', $hasil['pesan']);
        redirect($back);
    }

    private function flash($tipe, $teks) {
        $_SESSION['pesan_pos'] = ['tipe' => $tipe, 'teks' => $teks];
    }
}