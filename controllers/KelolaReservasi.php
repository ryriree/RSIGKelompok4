<?php
require_once __DIR__ . '/../models/ReservasiModel.php';

class KelolaReservasi
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (($_SESSION['role'] ?? '') !== 'Admin') {
            http_response_code(403);
            exit('Akses ditolak');
        }
    }

    public function get_reservasi($idReservasi = null)
    {
        $model = new ReservasiModel();

        if ($idReservasi === null) {
            $this->tampilkan_daftar_reservasi(
                $model->getall_data_reservasi()
            );
            return;
        }

        $data = $model->get_data_reservasi($idReservasi);

        if ($data) {
            $this->tampilkan_form_ubah($data);
        }
    }

    public function form_tambah()
    {
        $this->tampilkan_form_tambah();
    }

    public function tambah_reservasi($data)
    {
        if (!$this->validasi_data($data)) {
            $this->tampilkan_pesan_kesalahan();
            return false;
        }

        $model = new ReservasiModel();
        $ok = $model->tambah_data_reservasi($data);

        if ($ok) {
            $this->tampilkan_daftar_reservasi(
                $model->getall_data_reservasi()
            );
        }

        return $ok;
    }

    public function ubah_reservasi($data)
    {
        if (!$this->validasi_data($data) || empty($data['id_reservasi'])) {
            $this->tampilkan_pesan_kesalahan();
            return false;
        }

        $model = new ReservasiModel();
        $ok = $model->ubah_data_reservasi($data);

        if ($ok) {
            $this->tampilkan_daftar_reservasi(
                $model->getall_data_reservasi()
            );
        }

        return $ok;
    }

    private function validasi_data($data)
    {
        if (
            empty($data['nama_pemesan']) ||
            empty($data['kontak_pemesanan']) ||
            empty($data['tanggal']) ||
            empty($data['slot_waktu']) ||
            (int) ($data['jumlah_orang'] ?? 0) < 1 ||
            !in_array((int) ($data['lantai'] ?? 0), [1, 2], true)
        ) {
            return false;
        }

        $date = DateTime::createFromFormat('Y-m-d', $data['tanggal']);

        return $date && $date->format('Y-m-d') === $data['tanggal'];
    }

    public function batalkan_reservasi($idReservasi)
    {
        $model = new ReservasiModel();
        $ok = $model->ubah_status_atau_hapus_data($idReservasi);

        if ($ok) {
            $this->tampilkan_daftar_reservasi(
                $model->getall_data_reservasi()
            );
        }

        return $ok;
    }

    public function batal_konfirmasi()
    {
        $this->tutup_dialog_konfirmasi();
    }

    public function ubah_status_dp($idReservasi, $statusDP)
    {
        if (!in_array($statusDP, ['Belum Dibayar', 'Lunas'], true)) {
            return false;
        }

        $model = new ReservasiModel();
        $ok = $model->ubah_data_status_dp($idReservasi, $statusDP);

        if ($ok) {
            $this->tampilkan_daftar_reservasi(
                $model->getall_data_reservasi()
            );
        }

        return $ok;
    }

    public function tampilkan_daftar_reservasi($data)
    {
        Respon::kirim('daftar_reservasi', $data);
    }

    public function tampilkan_form_tambah()
    {
        Respon::kirim('form_tambah');
    }

    public function tampilkan_form_ubah($data)
    {
        Respon::kirim('form_ubah', $data);
    }

    public function tampilkan_pesan_kesalahan()
    {
        Respon::kirim('pesan_kesalahan');
    }

    public function tutup_dialog_konfirmasi()
    {
        Respon::kirim('tutup_dialog');
    }
}