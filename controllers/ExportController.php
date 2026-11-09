<?php
require_once __DIR__ . '/../models/Transaksi.php';
require_once __DIR__ . '/../models/Kas.php';

class ExportController {
    private $berkasExcel;
    private $transaksiModel;
    private $kasModel;

    public function __construct() {
        $this->transaksiModel = new Transaksi();
        $this->kasModel = new Kas();
    }

    public function tekanExportData() {
        $tanggal = $_GET['tanggal'] ?? date('Y-m-d');
        $this->exportData($tanggal);
    }

    public function exportData($tanggal) {
        $dataPenjualan = $this->transaksiModel->getDataPenjualan($tanggal);
        $dataKas = $this->kasModel->getDataKas($tanggal);

        $this->berkasExcel = $this->buatBerkasExcel($dataPenjualan, $dataKas);
        $this->unduhBerkas($this->berkasExcel);
        return $this->berkasExcel;
    }

    public function buatBerkasExcel($dataPenjualan, $dataKas) {
        // Membuat Format CSV yang bisa langsung dibuka rapi di Microsoft Excel
        $filename = "Laporan_Penjualan_MakyChicken_" . date('Ymd') . ".csv";
        
        $output = fopen('php://memory', 'w');
        
        // Header Excel
        fputcsv($output, ['=========================================================']);
        fputcsv($output, ['LAPORAN PENJUALAN & AUDIT KAS - MAKY CHICKEN']);
        fputcsv($output, ['Tanggal:', date('d-m-Y')]);
        fputcsv($output, ['=========================================================']);
        fputcsv($output, []);

        // Table Summary
        fputcsv($output, ['RINGKASAN OMASET & KAS']);
        fputcsv($output, ['Total Pendapatan', 'Rp ' . number_format($dataPenjualan['ringkasan']['total_pendapatan'] ?? 0, 0, ',', '.')]);
        fputcsv($output, ['Total Transaksi', ($dataPenjualan['ringkasan']['total_trx'] ?? 0) . ' TRX']);
        fputcsv($output, ['Kas Sistem (POS)', 'Rp ' . number_format($dataKas['kas_sistem'] ?? 0, 0, ',', '.')]);
        fputcsv($output, ['Kas Fisik Kasir', 'Rp ' . number_format($dataKas['kas_fisik'] ?? 0, 0, ',', '.')]);
        fputcsv($output, ['Selisih Kas', 'Rp ' . number_format($dataKas['selisih'] ?? 0, 0, ',', '.')]);
        fputcsv($output, ['Status Selisih', $dataKas['status_selisih'] ?? 'Sesuai']);
        fputcsv($output, []);

        // Detail Transaksi
        fputcsv($output, ['Waktu', 'ID Transaksi', 'Pelanggan / Meja', 'Rincian Menu', 'Metode Bayar', 'Total']);
        foreach ($dataPenjualan['daftar_transaksi'] as $row) {
            fputcsv($output, [
                $row['waktu'],
                '#' . $row['id_transaksi_kode'],
                $row['nama_pelanggan'] ?? 'Pelanggan',
                $row['rincian_menu'],
                $row['metode_pembayaran'],
                'Rp ' . number_format($row['total_bayar'], 0, ',', '.')
            ]);
        }

        fseek($output, 0);
        return [
            'stream' => $output,
            'filename' => $filename
        ];
    }

    public function unduhBerkas($berkas) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $berkas['filename'] . '"');
        fpassthru($berkas['stream']);
        exit();
    }
}