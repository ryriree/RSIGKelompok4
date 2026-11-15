<?php
// Pembayaran & invoice pelanggan (publik, diakses lewat kode pesanan)
class PembayaranController {
    // true = tombol "simulasi bayar QRIS" tampil (demo tugas). Set false bila ada payment gateway sungguhan.
    const QRIS_SIMULASI = true;

    private $pembayaranModel;
    private $strukModel;

    public function __construct() {
        $this->pembayaranModel = new PembayaranModel();
        $this->strukModel      = new StrukModel();
    }

    // =====================================================
    // METHOD LAMA (nama & parameter dipertahankan)
    // =====================================================

    // PC-07: Validasi metode pembayaran
    public function validasiMetode($metode) {
        return in_array($metode, ['QRIS', 'Bayar di Kasir', 'Tunai'], true);
    }

    // Validasi nominal uang yang diterima
    public function validasiUang($uangDiterima, $total) {
        return ($uangDiterima >= $total);
    }

    // Menghitung uang kembalian pelanggan
    public function hitungKembalian($uangDiterima, $total) {
        return $uangDiterima - $total;
    }

    // PC-07: Memproses pembayaran pelanggan
    public function prosesBayar($kodePesanan, $metode, $uangDiterima = null) {
        if (!$this->validasiMetode($metode)) {
            return ['berhasil' => false, 'pesan' => 'Metode pembayaran tidak valid'];
        }

        $dataPesanan = $this->pembayaranModel->getPesananByKode($kodePesanan);
        if (empty($dataPesanan)) {
            return ['berhasil' => false, 'pesan' => 'Pesanan tidak ditemukan'];
        }
        if ($dataPesanan['status'] === 'Dibatalkan') {
            return ['berhasil' => false, 'pesan' => 'Pesanan sudah dibatalkan'];
        }

        $total = (float)$dataPesanan['total_harga'];

        // Memeriksa pembayaran sebelumnya
        $dataLama = $this->pembayaranModel->get_data_pembayaran($kodePesanan);
        if (!empty($dataLama) && $dataLama['status'] === 'Lunas') {
            return ['berhasil' => false, 'pesan' => 'Pesanan sudah dibayar'];
        }

        // Pakai pembayaran yang masih menunggu, atau buat baru
        if (!empty($dataLama) && $dataLama['status'] === 'Menunggu Pembayaran') {
            $simpan = [
                'idPembayaran'  => (int)$dataLama['id_pembayaran'],
                'status'        => $dataLama['status'],
                'kodeTransaksi' => $dataLama['kode_transaksi']
            ];
        } else {
            $simpan = $this->pembayaranModel->simpan_pembayaran($kodePesanan, $metode, $total);
            if ($simpan === null) {
                return ['berhasil' => false, 'pesan' => 'Pembayaran gagal disimpan'];
            }
        }

        // Pembayaran QRIS: menunggu callback
        if ($metode === 'QRIS') {
            $simpan['kodeQRIS'] = $this->buatKodeQRIS($total);
            $simpan['berhasil'] = true;
            return $simpan;
        }

        // Bayar di kasir: belum ada uang diterima, tunggu kasir
        if ($uangDiterima === null) {
            $simpan['berhasil'] = true;
            return $simpan;
        }

        // Pembayaran tunai
        if (!$this->validasiUang($uangDiterima, $total)) {
            return ['berhasil' => false, 'pesan' => 'Nominal uang tidak mencukupi'];
        }

        $kembalian = $this->hitungKembalian($uangDiterima, $total);
        $this->pembayaranModel->simpan_uang_tunai($simpan['idPembayaran'], $uangDiterima, $kembalian);
        $statusTerbaru = $this->updateStatusPembayaran($simpan['idPembayaran'], 'Lunas');

        return [
            'berhasil'      => $statusTerbaru === 'Lunas',
            'idPembayaran'  => $simpan['idPembayaran'],
            'status'        => $statusTerbaru,
            'kodeTransaksi' => $simpan['kodeTransaksi'],
            'kembalian'     => $kembalian
        ];
    }

    // Membuat kode QRIS berdasarkan nominal pembayaran
    public function buatKodeQRIS($total) {
        return 'QRIS-' . strtoupper(bin2hex(random_bytes(6))) . '-' . (int)$total;
    }

    // SD-20: Callback dari payment gateway
    public function callbackPembayaran($idPembayaran, $status) {
        return $this->updateStatusPembayaran($idPembayaran, $status === 'Berhasil' ? 'Lunas' : 'Gagal');
    }

    // Memperbarui status pembayaran dan pesanan.
    // Untuk 'Lunas', semuanya (pembayaran, pesanan -> Diproses, transaksi, kas) dicatat atomik oleh model.
    public function updateStatusPembayaran($idPembayaran, $status) {
        if ($status !== 'Lunas') {
            return $this->pembayaranModel->ubah_status_pembayaran($idPembayaran, $status);
        }

        $kode = $this->pembayaranModel->get_kode_pesanan($idPembayaran);
        if (!$kode) return null;

        $pesanan = $this->pembayaranModel->getPesananByKode($kode);
        $data    = $this->pembayaranModel->get_data_pembayaran($kode);
        if (!$pesanan || !$data) return null;
        if ($data['status'] === 'Lunas') return 'Lunas';

        $total     = (float)$pesanan['total_harga'];
        $uang      = $data['uang_diterima'] !== null ? (int)$data['uang_diterima'] : (int)$total;
        $kembalian = $data['kembalian'] !== null ? (int)$data['kembalian'] : 0;

        $hasil = $this->pembayaranModel->bayarKasir((int)$pesanan['id_pesanan'], $data['metode'], $total, $uang, $kembalian);
        return $hasil['ok'] ? 'Lunas' : $data['status'];
    }

    // SD-20: Mengambil status pembayaran untuk kasir
    public function getStatusPembayaran($kodePesanan) {
        return $this->pembayaranModel->get_status_pembayaran($kodePesanan);
    }

    // SD-20: Mengambil data struk pembayaran
    public function cetakStruk($kodePesanan) {
        $data = $this->pembayaranModel->get_data_pembayaran($kodePesanan);
        if (empty($data) || $data['status'] !== 'Lunas') return null;

        return [
            'kodeTransaksi' => $data['kode_transaksi'],
            'metode'        => $data['metode'],
            'total'         => $data['total'],
            'uangDiterima'  => $data['uang_diterima'],
            'kembalian'     => $data['kembalian'],
            'waktuBayar'    => $data['waktu_bayar']
        ];
    }

    // =====================================================
    // METHOD UNTUK ROUTE (membaca $_GET / $_POST, tanpa argumen)
    // =====================================================

    private function flash($tipe, $teks) {
        $_SESSION['pesan_bayar'] = ['tipe' => $tipe, 'teks' => $teks];
    }

    private function cariPesanan() {
        $kode = trim((string)($_GET['kode'] ?? $_POST['kode'] ?? ''));
        return $kode !== '' ? $this->pembayaranModel->getPesananByKode($kode) : false;
    }

    private function sudahLunas($idPesanan) {
        return $this->strukModel->getStrukDetail((int)$idPesanan) !== null;
    }

    // ?page=bayar&kode=...
    public function index() {
        $pesanan = $this->cariPesanan();
        if (!$pesanan) {
            $_SESSION['pesan_pesan'] = ['tipe' => 'error', 'teks' => 'Pesanan tidak ditemukan.'];
            redirect('pesan');
        }
        if ($this->sudahLunas($pesanan['id_pesanan'])) {
            redirect('invoice&kode=' . urlencode($pesanan['kode_pesanan']));
        }

        $pesanBayar = $_SESSION['pesan_bayar'] ?? null;
        unset($_SESSION['pesan_bayar']);

        $items    = $this->pembayaranModel->getItemPesanan((int)$pesanan['id_pesanan']);
        $simulasi = self::QRIS_SIMULASI;
        $kodeQris = 'QRIS-' . strtoupper(substr(hash('sha256', $pesanan['kode_pesanan']), 0, 12));

        require BASE_PATH . '/views/pelanggan/Pembayaran.php';
    }

    // ?page=bayar-qris (POST): simulasi konfirmasi QRIS, lewat prosesBayar + callbackPembayaran
    public function konfirmasiQris() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('pesan');

        $pesanan = $this->cariPesanan();
        if (!$pesanan) redirect('pesan');
        $kode = $pesanan['kode_pesanan'];
        $back = 'bayar&kode=' . urlencode($kode);

        if (!self::QRIS_SIMULASI) {
            $this->flash('error', 'Verifikasi QRIS otomatis belum tersedia. Silakan bayar di kasir.');
            redirect($back);
        }

        $hasil = $this->prosesBayar($kode, 'QRIS');
        if (!empty($hasil['berhasil'])) {
            $this->callbackPembayaran($hasil['idPembayaran'], 'Berhasil');
        }

        if ($this->sudahLunas($pesanan['id_pesanan'])) {
            redirect('invoice&kode=' . urlencode($kode));
        }
        $this->flash('error', $hasil['pesan'] ?? 'Pembayaran belum berhasil. Silakan coba lagi.');
        redirect($back);
    }

    // ?page=bayar-status&kode=... (JSON, dipolling halaman bayar)
    public function status() {
        $pesanan = $this->cariPesanan();
        $lunas = $pesanan ? ($this->getStatusPembayaran($pesanan['kode_pesanan']) === 'Lunas') : false;

        header('Content-Type: application/json');
        header('Cache-Control: no-store');
        echo json_encode(['lunas' => $lunas]);
    }

    // ?page=invoice&kode=...
    public function invoice() {
        $pesanan = $this->cariPesanan();
        if (!$pesanan) {
            $_SESSION['pesan_pesan'] = ['tipe' => 'error', 'teks' => 'Pesanan tidak ditemukan.'];
            redirect('pesan');
        }

        $invoice = $this->strukModel->getStrukDetail((int)$pesanan['id_pesanan']);
        if (!$invoice) {
            redirect('bayar&kode=' . urlencode($pesanan['kode_pesanan']));
        }

        require BASE_PATH . '/views/pelanggan/struk.php';
    }
}