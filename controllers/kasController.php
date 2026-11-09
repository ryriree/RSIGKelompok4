<?php
require_once __DIR__ . '/../models/Kas.php';
require_once __DIR__ . '/../models/Transaksi.php';

class KasController {
    private $dataKas;
    private $kasFisik;
    private $kasModel;
    private $transaksiModel;

    public function __construct() {
        $this->kasModel = new Kas();
        $this->transaksiModel = new Transaksi();
    }

    public function isiJumlahKasFisik($jumlah) {
        $this->kasFisik = floatval($jumlah);
    }

    public function validasiData($kasFisik) {
        return ($kasFisik >= 0);
    }

    public function hitungSelisih($kasFisik, $kasSistem) {
        $selisih = $kasFisik - $kasSistem;
        if ($selisih == 0) {
            $status = "Sesuai";
        } else if ($selisih > 0) {
            $status = "Surplus";
        } else {
            $status = "Defisit";
        }

        return [
            'selisih' => $selisih,
            'statusSelisih' => $status
        ];
    }

    public function tekanSimpan() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tanggal = $_POST['tanggal'] ?? date('Y-m-d');
            $kasFisikInput = floatval($_POST['kas_fisik']);

            if ($this->validasiData($kasFisikInput)) {
                $this->isiJumlahKasFisik($kasFisikInput);
                $this->simpanKas($tanggal, $this->kasFisik);
            } else {
                $this->tampilkanPesanKesalahan("Jumlah kas fisik tidak boleh negatif!");
            }
        }
    }

    public function simpanKas($tanggal, $kasFisik) {
        // Ambil kas sistem terbaru dari akumulasi transaksi
        $kasSistem = $this->transaksiModel->getTotalKasSistem($tanggal);
        $hasil = $this->hitungSelisih($kasFisik, $kasSistem);

        $statusBerhasil = $this->kasModel->simpanDataKas(
            $tanggal,
            $kasFisik,
            $kasSistem,
            $hasil['selisih'],
            $hasil['statusSelisih']
        );

        if ($statusBerhasil) {
            header("Location: index.php?page=laporan-penjualan&tanggal=" . $tanggal);
            exit();
        } else {
            $this->tampilkanPesanKesalahan("Gagal menyimpan data kas!");
            return false;
        }
    }

    public function gantiDataKasLama($tanggal, $kasFisik) {
        $this->simpanKas($tanggal, $kasFisik);
    }

    public function perbaruiDashboardKas($kasFisik, $kasSistem, $selisih, $statusSelisih) {
        // Logika alokasi tampilan kas
    }

    public function tampilkanNotifikasiBerhasil($pesan) {
        echo "<script>alert('".$pesan."');</script>";
    }

    public function tampilkanPesanKesalahan($pesan) {
        echo "<script>alert('".$pesan."'); window.history.back();</script>";
        exit();
    }
}