<?php
require_once __DIR__ . '/../models/ReservasiModel.php';
require_once __DIR__ . '/../models/PaketMenuModel.php';

class PaymentGateway
{
    public static function buatQRIS($nominal)
    {
        return [
            'idTransaksi' => 'TRX-' . strtoupper(bin2hex(random_bytes(4))),
            'qris' => 'SIMULASI-' . (int) $nominal
        ];
    }

    public static function cekStatusTransaksi($idTransaksi)
    {
        return 'MENUNGGU';
    }
}

class Reservasi
{
    const KAPASITAS_MAKSIMAL = 40;
    const SERVICE_FEE = 10000;
    const DP_MINIMUM = 400000;

    private $atribut = [
        'tanggal',
        'slotWaktu',
        'jumlahOrang',
        'lantai',
        'paketId',
        'qty',
        'subtotal',
        'serviceFee',
        'total',
        'dp',
        'nominalDP',
        'idTransaksi',
        'kodeBooking',
        'namaPemesan',
        'kontakPemesanan'
    ];

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        foreach ($this->atribut as $a) {
            $this->$a = $_SESSION['draft_reservasi'][$a] ?? null;
        }
    }

    private function simpan_draft()
    {
        foreach ($this->atribut as $a) {
            $_SESSION['draft_reservasi'][$a] = $this->$a;
        }
    }

    public function get_form_reservasi()
    {
        $this->tampilkan_form_reservasi();
    }

    public function set_data_pemesan($nama, $kontak)
    {
        $this->namaPemesan = trim($nama);
        $this->kontakPemesanan = trim($kontak);
        $this->simpan_draft();
    }

    public function cek_ketersediaan($tanggal, $slotWaktu, $jumlahOrang)
    {
        $date = DateTime::createFromFormat('Y-m-d', $tanggal);

        if (
            !$date ||
            $date->format('Y-m-d') !== $tanggal ||
            $tanggal < date('Y-m-d') ||
            (int) $jumlahOrang < 1
        ) {
            $this->tampilkan_jadwal_penuh();
            return false;
        }

        $this->tanggal = $tanggal;
        $this->slotWaktu = $slotWaktu;
        $this->jumlahOrang = (int) $jumlahOrang;

        $model = new ReservasiModel();
        $jadwal = $model->get_data_jadwal($tanggal, $slotWaktu);

        $terisi = array_sum(array_column($jadwal, 'jumlah_orang'));

        if ($terisi + $this->jumlahOrang <= self::KAPASITAS_MAKSIMAL) {
            $this->simpan_draft();
            $this->tampilkan_pilihan_lantai();
            return true;
        }

        $this->tampilkan_jadwal_penuh();
        return false;
    }

    public function simpan_lantai($lantai)
    {
        if (!in_array((int) $lantai, [1, 2], true)) {
            return false;
        }

        $this->lantai = (int) $lantai;

        (new ReservasiModel())->simpan_draft_lantai($this->lantai);

        $this->simpan_draft();
        $this->tampilkan_katalog_menu();

        return true;
    }

    public function batalkan_reservasi()
    {
        unset($_SESSION['draft_reservasi'], $_SESSION['draft_detail']);
        $this->tutup_form_reservasi();
    }

    public function simpan_menu($paketId, $qty)
    {
        $qty = (int) $qty;

        if ($qty < 1) {
            return false;
        }

        $paket = (new PaketMenuModel())->get_data_paket($paketId);

        if (!$paket) {
            return false;
        }

        $this->paketId = (int) $paketId;
        $this->qty = $qty;
        $this->subtotal = (float) $paket['harga'] * $qty;
        $this->serviceFee = self::SERVICE_FEE;
        $this->total = $this->subtotal + $this->serviceFee;
        $this->dp = self::DP_MINIMUM;

        (new ReservasiModel())->simpan_data_detail_pesanan(
            $this->paketId,
            $qty
        );

        $this->simpan_draft();

        $this->tampilkan_ringkasan_pesanan(
            $this->subtotal,
            $this->serviceFee,
            $this->total,
            $this->dp
        );

        return true;
    }

    public function buat_qris($nominalDP)
    {
        $nominalDP = (float) $nominalDP;

        if (
            $nominalDP < self::DP_MINIMUM ||
            $nominalDP > $this->total
        ) {
            return false;
        }

        $this->nominalDP = $nominalDP;
        $hasil = PaymentGateway::buatQRIS($nominalDP);
        $this->idTransaksi = $hasil['idTransaksi'];

        $this->simpan_draft();
        $this->tampilkan_qris($nominalDP);

        return $hasil;
    }

    public function verifikasi_pembayaran($idTransaksi)
    {
        $status = PaymentGateway::cekStatusTransaksi($idTransaksi);

        if ($status !== 'BERHASIL') {
            $this->tampilkan_pembayaran_gagal();
            return false;
        }

        $kode = 'BK-' . strtoupper(bin2hex(random_bytes(4)));

        $data = [
            'kode_booking' => $kode,
            'nama_pemesan' => $this->namaPemesan,
            'kontak_pemesanan' => $this->kontakPemesanan,
            'tanggal' => $this->tanggal,
            'slot_waktu' => $this->slotWaktu,
            'jumlah_orang' => $this->jumlahOrang,
            'lantai' => $this->lantai,
            'subtotal' => $this->subtotal,
            'service_fee' => $this->serviceFee,
            'total' => $this->total,
            'dp' => $this->nominalDP
        ];

        if ((new ReservasiModel())->simpan_reservasi($data, 'Confirmed')) {
            $this->kodeBooking = $kode;
            $this->simpan_draft();
            $this->tampilkan_evoucher($kode);
            return true;
        }

        $this->tampilkan_pembayaran_gagal();
        return false;
    }

    public function retry_pembayaran()
    {
        $this->tampilkan_qris($this->nominalDP);
    }

    public function unduh_evoucher($kodeBooking)
    {
        $dir = __DIR__ . '/../evoucher';

        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $namaFile = 'evoucher_' .
            preg_replace('/[^A-Za-z0-9-]/', '', $kodeBooking) .
            '.txt';

        $path = $dir . '/' . $namaFile;

        $isi = "E-VOUCHER RESERVASI MAKY CHICKEN\n"
            . "Kode Booking: {$kodeBooking}\n"
            . "Status: Menunggu verifikasi pembayaran\n"
            . "Jumlah Tamu: {$this->jumlahOrang}\n";

        file_put_contents($path, $isi);

        return $path;
    }

    public function tampilkan_form_reservasi()
    {
        Respon::kirim('form_reservasi');
    }

    public function tampilkan_jadwal_penuh()
    {
        Respon::kirim('jadwal_penuh');
    }

    public function tampilkan_pilihan_lantai()
    {
        Respon::kirim('pilihan_lantai');
    }

    public function tutup_form_reservasi()
    {
        Respon::kirim('tutup_form');
    }

    public function tampilkan_katalog_menu()
    {
        Respon::kirim('katalog_menu');
    }

    public function tampilkan_ringkasan_pesanan($subtotal, $serviceFee, $total, $dp)
    {
        Respon::kirim(
            'ringkasan_pesanan',
            compact('subtotal', 'serviceFee', 'total', 'dp')
        );
    }

    public function tampilkan_qris($nominalDP)
    {
        Respon::kirim('qris', ['nominalDP' => $nominalDP]);
    }

    public function tampilkan_pembayaran_gagal()
    {
        Respon::kirim('pembayaran_gagal');
    }

    public function tampilkan_evoucher($kodeBooking)
    {
        Respon::kirim('evoucher', ['kodeBooking' => $kodeBooking]);
    }
}