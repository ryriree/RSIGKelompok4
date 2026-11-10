<?php
require_once __DIR__ . '/../models/PesananModel.php';
require_once __DIR__ . '/../models/PembayaranModel.php';

class PembayaranController {
    private $pesananModel;
    private $pembayaranModel;

    public function __construct() {
        $this->pesananModel = new PesananModel();
        $this->pembayaranModel = new PembayaranModel();
    }

    // PC-07: Validasi metode pembayaran
    public function validasiMetode($metode) {
        return in_array($metode, [
            'QRIS',
            'Bayar di Kasir',
            'Tunai'
        ], true);
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
            return [
                'berhasil' => false,
                'pesan' => 'Metode pembayaran tidak valid'
            ];
        }

        // Mengambil data pesanan
        $dataPesanan = $this->pesananModel->get_data_pesanan($kodePesanan);

        if (empty($dataPesanan)) {
            return [
                'berhasil' => false,
                'pesan' => 'Pesanan tidak ditemukan'
            ];
        }

        $total = $dataPesanan['totalHarga'];

        // Memeriksa pembayaran sebelumnya
        $dataLama = $this->pembayaranModel->get_data_pembayaran($kodePesanan);

        if (!empty($dataLama) && $dataLama['status'] === 'Lunas') {
            return [
                'berhasil' => false,
                'pesan' => 'Pesanan sudah dibayar'
            ];
        }

        // Menyimpan pembayaran baru
        if (!empty($dataLama) &&
            $dataLama['status'] === 'Menunggu Pembayaran') {

            $simpan = [
                'idPembayaran' => $dataLama['id_pembayaran'],
                'status' => $dataLama['status'],
                'kodeTransaksi' => $dataLama['kode_transaksi']
            ];

        } else {
            $simpan = $this->pembayaranModel->simpan_pembayaran(
                $kodePesanan,
                $metode,
                $total
            );

            if ($simpan === null) {
                return [
                    'berhasil' => false,
                    'pesan' => 'Pembayaran gagal disimpan'
                ];
            }
        }

        // Proses pembayaran QRIS
        if ($metode === 'QRIS') {
            $simpan['kodeQRIS'] = $this->buatKodeQRIS($total);
            $simpan['berhasil'] = true;

            return $simpan;
        }

        // Pembayaran dilakukan langsung di kasir
        if ($uangDiterima === null) {
            $simpan['berhasil'] = true;
            return $simpan;
        }

        // Memvalidasi pembayaran tunai
        if (!$this->validasiUang($uangDiterima, $total)) {
            return [
                'berhasil' => false,
                'pesan' => 'Nominal uang tidak mencukupi'
            ];
        }

        $kembalian = $this->hitungKembalian(
            $uangDiterima,
            $total
        );

        $this->pembayaranModel->simpan_uang_tunai(
            $simpan['idPembayaran'],
            $uangDiterima,
            $kembalian
        );

        $statusTerbaru = $this->updateStatusPembayaran(
            $simpan['idPembayaran'],
            'Lunas'
        );

        return [
            'berhasil' => true,
            'idPembayaran' => $simpan['idPembayaran'],
            'status' => $statusTerbaru,
            'kodeTransaksi' => $simpan['kodeTransaksi'],
            'kembalian' => $kembalian
        ];
    }

    // Membuat kode QRIS berdasarkan nominal pembayaran
    public function buatKodeQRIS($total) {
        return 'QRIS-' . strtoupper(bin2hex(random_bytes(6)))
            . '-' . (int) $total;
    }

    // SD-20: Callback dari payment gateway
    public function callbackPembayaran($idPembayaran, $status) {
        if ($status === 'Berhasil') {
            return $this->updateStatusPembayaran(
                $idPembayaran,
                'Lunas'
            );
        } else {
            return $this->updateStatusPembayaran(
                $idPembayaran,
                'Gagal'
            );
        }
    }

    // Memperbarui status pembayaran dan pesanan
    public function updateStatusPembayaran($idPembayaran, $status) {
        $statusTerbaru = $this->pembayaranModel
            ->ubah_status_pembayaran($idPembayaran, $status);

        if ($statusTerbaru === 'Lunas') {
            $kodePesanan = $this->pembayaranModel
                ->get_kode_pesanan($idPembayaran);

            $this->pembayaranModel->update_status_pesanan(
                $kodePesanan,
                'Diproses'
            );
        }

        return $statusTerbaru;
    }

    // SD-20: Mengambil status pembayaran untuk kasir
    public function getStatusPembayaran($kodePesanan) {
        return $this->pembayaranModel
            ->get_status_pembayaran($kodePesanan);
    }

    // SD-20: Mengambil data struk pembayaran
    public function cetakStruk($kodePesanan) {
        $data = $this->pembayaranModel
            ->get_data_pembayaran($kodePesanan);

        if (empty($data) || $data['status'] !== 'Lunas') {
            return null;
        }

        return [
            'kodeTransaksi' => $data['kode_transaksi'],
            'metode' => $data['metode'],
            'total' => $data['total'],
            'uangDiterima' => $data['uang_diterima'],
            'kembalian' => $data['kembalian'],
            'waktuBayar' => $data['waktu_bayar']
        ];
    }
}
?>