<?php
require_once __DIR__ . '/../models/LaporanKendala.php';
require_once __DIR__ . '/../models/KontakAdmin.php';

class MelaporkanKendalaController {
    private $nama;
    private $kontak;
    private $jenisLaporan;
    private $isiPesan;
    private $kontakAdmin;
    private $tautanSaluran;

    // METHOD 1: Menampilkan halaman bantuan & form pelaporan kendala (TANPA SYARAT LOGIN)
    public function pilihMenuContactUs() {
        $kontakAdminModel = new KontakAdmin();
        $this->kontakAdmin = $kontakAdminModel->getKontakAdmin();
        $this->tautanSaluran = $this->buatTautanSaluran($this->kontakAdmin);

        // Variables dipasing ke boundary view
        $kontakAdmin = $this->kontakAdmin;
        $tautanSaluran = $this->tautanSaluran;
        
        require_once __DIR__ . '/../views/pelanggan/form_kendala.php';
    }

    // METHOD 2: Mengambil nomor WhatsApp admin dari database
    public function ambilKontakAdmin() {
        $kontakAdminModel = new KontakAdmin();
        $this->kontakAdmin = $kontakAdminModel->getKontakAdmin();
        return $this->kontakAdmin;
    }

    // METHOD 3: Membentuk format URL WhatsApp API
    public function buatTautanSaluran($kontakAdmin) {
        $cleanNumber = preg_replace('/[^0-9]/', '', $kontakAdmin);
        if (substr($cleanNumber, 0, 1) === '0') {
            $cleanNumber = '62' . substr($cleanNumber, 1);
        }
        $pesanDefault = rawurlencode("Halo Admin Maky Chicken, saya ingin bertanya/menyampaikan kendala.");
        $this->tautanSaluran = "https://wa.me/" . $cleanNumber . "?text=" . $pesanDefault;
        return $this->tautanSaluran;
    }

    // METHOD 4: Redirect/Arahkan ke WhatsApp Admin
    public function tekanHubungi() {
        $kontak = $this->ambilKontakAdmin();
        $tautan = $this->buatTautanSaluran($kontak);
        $this->arahkanKeSaluranKomunikasi($tautan);
    }

    // METHOD 5: Eksekusi Redirection ke luar aplikasi
    public function arahkanKeSaluranKomunikasi($tautan) {
        header("Location: " . $tautan);
        exit();
    }

    // METHOD 6: Menyimpan state form sementara
    public function isiForm($nama, $kontak, $jenisLaporan, $isiPesan) {
        $this->nama = trim($nama);
        $this->kontak = trim($kontak);
        $this->jenisLaporan = trim($jenisLaporan);
        $this->isiPesan = trim($isiPesan);
    }

    // METHOD 7: Validasi kelengkapan data inputan
    public function validasiDataNama($nama, $kontak, $jenisLaporan, $isiPesan) {
        if (!empty($nama) && !empty($kontak) && !empty($jenisLaporan) && !empty($isiPesan)) {
            return true;
        }
        return false;
    }

    // METHOD 8: Menangani Submit Form Kendala dari Pelanggan Publik
    public function tekanKirim() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = $_POST['nama'] ?? '';
            $kontak = $_POST['kontak'] ?? '';
            $jenisLaporan = $_POST['jenis_laporan'] ?? '';
            $isiPesan = $_POST['isi_pesan'] ?? '';

            $this->isiForm($nama, $kontak, $jenisLaporan, $isiPesan);

            if ($this->validasiDataNama($this->nama, $this->kontak, $this->jenisLaporan, $this->isiPesan)) {
                $statusKirim = $this->kirimLaporan($this->nama, $this->kontak, $this->jenisLaporan, $this->isiPesan);
                
                if ($statusKirim) {
                    $_SESSION['notif_sukses'] = "Laporan Anda telah berhasil terkirim dan akan ditindaklanjuti maksimal 1x24 jam kerja.";
                } else {
                    $_SESSION['notif_error'] = "Gagal menyimpan laporan ke sistem. Silakan coba beberapa saat lagi.";
                }
            } else {
                $_SESSION['notif_error'] = "Mohon lengkapi seluruh bidang formulir yang bertanda bintang (*).";
            }
        }
        
        header("Location: index.php?page=form-kendala");
        exit();
    }

    // METHOD 9: Memanggil Model LaporanKendala.simpanLaporan(...)
    public function kirimLaporan($nama, $kontak, $jenisLaporan, $isiPesan) {
        $laporanModel = new LaporanKendala();
        return $laporanModel->simpanLaporan($nama, $kontak, $jenisLaporan, $isiPesan, 'Pending');
    }

    public function tampilkanKontakAdmin($kontakAdmin) {
        echo htmlspecialchars($kontakAdmin);
    }

    public function tampilkanNotifikasiBerhasil($pesan) {
        echo '<div class="alert alert-success">' . htmlspecialchars($pesan) . '</div>';
    }

    public function tampilkanPesanKesalahan($pesan) {
        echo '<div class="alert alert-danger">' . htmlspecialchars($pesan) . '</div>';
    }
}