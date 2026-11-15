<?php
require_once __DIR__ . '/../models/ReservasiModel.php';
require_once __DIR__ . '/../models/PaketMenuModel.php';

// Simulasi payment gateway. BERHASIL hanya setelah tombol simulasi ditekan di sesi ini.
// Ganti isi cekStatusTransaksi() dengan panggilan gateway sungguhan bila ada.
class PaymentGateway
{
    public static function buatQRIS($nominal)
    {
        return [
            'idTransaksi' => 'TRX-' . strtoupper(bin2hex(random_bytes(4))),
            'qris'        => 'QRIS-' . strtoupper(bin2hex(random_bytes(6))) . '-' . (int) $nominal
        ];
    }

    public static function cekStatusTransaksi($idTransaksi)
    {
        return (($_SESSION['qris_simulasi_bayar'] ?? null) === $idTransaksi) ? 'BERHASIL' : 'MENUNGGU';
    }
}

class Reservasi
{
    const KAPASITAS_MAKSIMAL = 40;   // tamu per tanggal + slot
    const SERVICE_FEE        = 10000;
    const DP_MINIMUM         = 400000;
    const MAKS_TAMU          = 20;
    const MAKS_QTY           = 20;
    const QRIS_SIMULASI      = true; // false bila sudah ada gateway sungguhan
    const SLOT   = ['11:30 WIB', '13:00 WIB', '17:00 WIB', '18:30 WIB', '19:45 WIB', '20:30 WIB'];
    const LANTAI = [1 => 'Indoor AC', 2 => 'Outdoor Smoking Area'];

    private $d = [];       // draft reservasi (disimpan di session)
    public $galat = '';    // alasan kegagalan terakhir

    public function __construct()
    {
        $this->d = $_SESSION['draft_reservasi'] ?? [];
    }

    // ---------- helper ----------
    private function simpan_draft() { $_SESSION['draft_reservasi'] = $this->d; }
    private function tolak($teks)   { $this->galat = $teks; return false; }
    private function flash($tipe, $teks) { $_SESSION['pesan_rsv'] = ['tipe' => $tipe, 'teks' => $teks]; }

    private function ambilPesan()
    {
        $p = $_SESSION['pesan_rsv'] ?? null;
        unset($_SESSION['pesan_rsv']);
        return $p;
    }

    private function wajibPost()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('form-reservasi');
    }

    // Pastikan langkah sebelumnya sudah terisi; kalau belum, kembalikan ke langkah itu
    private function wajib($tahap)
    {
        $urut = [
            ['tanggal', 'form-reservasi'], ['slotWaktu', 'form-reservasi'], ['jumlahOrang', 'form-reservasi'],
            ['namaPemesan', 'form-reservasi'], ['kontakPemesanan', 'form-reservasi'],
            ['lantai', 'reservasi-lantai'], ['paketId', 'reservasi-paket'],
        ];
        $batas = ['jadwal' => 5, 'lantai' => 6, 'paket' => 7][$tahap];
        for ($i = 0; $i < $batas; $i++) {
            if (empty($this->d[$urut[$i][0]])) {
                $this->flash('error', 'Lengkapi langkah sebelumnya terlebih dulu.');
                redirect($urut[$i][1]);
            }
        }
    }

    // Reservasi baru saja selesai (draft sudah kosong): arahkan ke voucher
    private function kalauSudahSelesai()
    {
        if (!$this->d && !empty($_SESSION['reservasi_sukses'])) redirect('reservasi-voucher');
    }

    // =====================================================
    // LANGKAH 1: Jadwal & Tamu
    // =====================================================
    public function get_form_reservasi()
    {
        $pesanRsv = $this->ambilPesan();
        $draft = $this->d;
        $old = $_SESSION['rsv_old'] ?? [];
        unset($_SESSION['rsv_old']);

        require BASE_PATH . '/views/pelanggan/step1_jadwal_tamu.php';
    }

    // JSON: sisa kapasitas per slot pada satu tanggal (dipakai step 1 untuk mencoret slot penuh)
    public function slotTerisi()
    {
        $tanggal = (string) ($_GET['tanggal'] ?? '');
        $date = DateTime::createFromFormat('Y-m-d', $tanggal);
        $sisa = [];

        if ($date && $date->format('Y-m-d') === $tanggal) {
            $terisi = (new ReservasiModel())->get_terisi_per_slot($tanggal);
            foreach (self::SLOT as $s) {
                $sisa[$s] = max(0, self::KAPASITAS_MAKSIMAL - ($terisi[$s] ?? 0));
            }
        }

        header('Content-Type: application/json');
        header('Cache-Control: no-store');
        echo json_encode(['sisa' => $sisa]);
    }

    public function set_data_pemesan($nama, $kontak)
    {
        $nama = trim((string) $nama);
        $kontak = trim((string) $kontak);

        if ($nama === '' || mb_strlen($nama) > 100) {
            return $this->tolak('Nama pemesan wajib diisi (maksimal 100 karakter).');
        }
        if (!preg_match('/^\+?[0-9][0-9\s\-]{7,18}$/', $kontak)) {
            return $this->tolak('Nomor WhatsApp tidak valid.');
        }

        $this->d['namaPemesan'] = $nama;
        $this->d['kontakPemesanan'] = $kontak;
        $this->simpan_draft();
        return true;
    }

    public function cek_ketersediaan($tanggal, $slotWaktu, $jumlahOrang)
    {
        $date = DateTime::createFromFormat('Y-m-d', (string) $tanggal);
        if (!$date || $date->format('Y-m-d') !== $tanggal || $tanggal < date('Y-m-d')) {
            return $this->tolak('Tanggal kunjungan tidak valid.');
        }
        if (!in_array($slotWaktu, self::SLOT, true)) {
            return $this->tolak('Pilih slot waktu kedatangan.');
        }
        $jumlah = (int) $jumlahOrang;
        if ($jumlah < 1 || $jumlah > self::MAKS_TAMU) {
            return $this->tolak('Jumlah tamu harus 1 sampai ' . self::MAKS_TAMU . ' orang.');
        }

        $jadwal = (new ReservasiModel())->get_data_jadwal($tanggal, $slotWaktu);
        $terisi = array_sum(array_column($jadwal, 'jumlah_orang'));
        if ($terisi + $jumlah > self::KAPASITAS_MAKSIMAL) {
            return $this->tolak('Jadwal ' . $slotWaktu . ' sudah penuh untuk ' . $jumlah . ' tamu. Pilih slot atau tanggal lain.');
        }

        $this->d['tanggal'] = $tanggal;
        $this->d['slotWaktu'] = $slotWaktu;
        $this->d['jumlahOrang'] = $jumlah;
        $this->simpan_draft();
        return true;
    }

    // POST: ?page=reservasi-jadwal
    public function prosesJadwal()
    {
        $this->wajibPost();

        $ok = $this->set_data_pemesan($_POST['nama_pemesan'] ?? '', $_POST['kontak_pemesanan'] ?? '')
           && $this->cek_ketersediaan($_POST['tanggal'] ?? '', $_POST['slot_waktu'] ?? '', $_POST['jumlah_orang'] ?? 0);

        if (!$ok) {
            $_SESSION['rsv_old'] = [
                'tanggal' => (string) ($_POST['tanggal'] ?? ''),
                'slot'    => (string) ($_POST['slot_waktu'] ?? ''),
                'jumlah'  => (int) ($_POST['jumlah_orang'] ?? 4),
            ];
            $this->flash('error', $this->galat);
            redirect('form-reservasi');
        }
        redirect('reservasi-lantai');
    }

    // =====================================================
    // LANGKAH 2: Lantai
    // =====================================================
    public function stepLantai()
    {
        $this->wajib('jadwal');
        $pesanRsv = $this->ambilPesan();
        $draft = $this->d;
        require BASE_PATH . '/views/pelanggan/step2_pilih_lantai_meja.php';
    }

    public function simpan_lantai($lantai)
    {
        if (!array_key_exists((int) $lantai, self::LANTAI)) {
            return $this->tolak('Pilih Lantai 1 atau Lantai 2.');
        }
        $this->d['lantai'] = (int) $lantai;
        $this->simpan_draft();
        return true;
    }

    // POST: ?page=reservasi-lantai-simpan
    public function prosesLantai()
    {
        $this->wajibPost();
        $this->wajib('jadwal');

        if ($this->simpan_lantai($_POST['lantai'] ?? 0)) redirect('reservasi-paket');

        $this->flash('error', $this->galat);
        redirect('reservasi-lantai');
    }

    // =====================================================
    // LANGKAH 3: Paket menu
    // =====================================================
    public function stepPaket()
    {
        $this->wajib('lantai');
        $pesanRsv = $this->ambilPesan();
        $draft = $this->d;
        $paketList = (new PaketMenuModel())->getall_data_paket();
        require BASE_PATH . '/views/pelanggan/step3_paket_menu.php';
    }

    // Subtotal selalu dihitung server dari harga di database
    public function simpan_menu($paketId, $qty)
    {
        $qty = (int) $qty;
        if ($qty < 1 || $qty > self::MAKS_QTY) {
            return $this->tolak('Jumlah paket harus 1 sampai ' . self::MAKS_QTY . '.');
        }

        $paket = (new PaketMenuModel())->get_data_paket($paketId);
        if (!$paket) {
            return $this->tolak('Pilih salah satu paket menu.');
        }

        $subtotal = (float) $paket['harga'] * $qty;
        $total = $subtotal + self::SERVICE_FEE;

        $this->d['paketId']    = (int) $paketId;
        $this->d['paketNama']  = $paket['nama_paket'];
        $this->d['qty']        = $qty;
        $this->d['subtotal']   = $subtotal;
        $this->d['serviceFee'] = self::SERVICE_FEE;
        $this->d['total']      = $total;
        $this->d['dp']         = min((float) self::DP_MINIMUM, $total);
        unset($this->d['idTransaksi'], $this->d['nominalDP'], $this->d['kodeQRIS']); // total berubah, QR lama tidak berlaku
        $this->simpan_draft();
        return true;
    }

    // POST: ?page=reservasi-paket-simpan
    public function prosesPaket()
    {
        $this->wajibPost();
        $this->wajib('lantai');

        if ($this->simpan_menu($_POST['paket_id'] ?? 0, $_POST['qty'] ?? 0)) redirect('reservasi-bayar');

        $this->flash('error', $this->galat);
        redirect('reservasi-paket');
    }

    // =====================================================
    // LANGKAH 4: Bayar DP & e-Voucher
    // =====================================================
    public function buat_qris($nominalDP)
    {
        $nominalDP = (float) $nominalDP;

        if ($nominalDP < (float) $this->d['dp'] || $nominalDP > (float) $this->d['total']) {
            return $this->tolak('Nominal DP tidak valid.');
        }

        $hasil = PaymentGateway::buatQRIS($nominalDP);
        $this->d['nominalDP']   = $nominalDP;
        $this->d['idTransaksi'] = $hasil['idTransaksi'];
        $this->d['kodeQRIS']    = $hasil['qris'];
        $this->simpan_draft();
        return $hasil;
    }

    public function retry_pembayaran()
    {
        unset($this->d['idTransaksi'], $this->d['nominalDP'], $this->d['kodeQRIS']);
        return $this->buat_qris($this->d['dp']);
    }

    public function stepBayar()
    {
        $this->kalauSudahSelesai();
        $this->wajib('paket');

        if (isset($_GET['ulang']) || empty($this->d['idTransaksi'])) {
            $this->retry_pembayaran();
        }

        $pesanRsv = $this->ambilPesan();
        $draft = $this->d;
        $simulasi = self::QRIS_SIMULASI;
        $voucher = null;
        require BASE_PATH . '/views/pelanggan/step4_bayar_dp_voucher.php';
    }

    public function verifikasi_pembayaran($idTransaksi)
    {
        if ($idTransaksi === '' || $idTransaksi !== ($this->d['idTransaksi'] ?? null)) {
            return $this->tolak('Kode transaksi tidak cocok. Muat ulang halaman pembayaran.');
        }
        if (PaymentGateway::cekStatusTransaksi($idTransaksi) !== 'BERHASIL') {
            return $this->tolak('Pembayaran belum terverifikasi. Pastikan nominal sesuai lalu coba lagi.');
        }

        $d = $this->d;
        $model = new ReservasiModel();
        $kode = $model->simpan_reservasi([
            'nama_pemesan'     => $d['namaPemesan'],
            'kontak_pemesanan' => $d['kontakPemesanan'],
            'tanggal'          => $d['tanggal'],
            'slot_waktu'       => $d['slotWaktu'],
            'jumlah_orang'     => $d['jumlahOrang'],
            'lantai'           => $d['lantai'],
            'subtotal'         => $d['subtotal'],
            'service_fee'      => $d['serviceFee'],
            'total'            => $d['total'],
            'dp'               => $d['nominalDP'],
            'paket_id'         => $d['paketId'],
            'qty'              => $d['qty'],
            'kode_transaksi'   => $idTransaksi,
            'kapasitas'        => self::KAPASITAS_MAKSIMAL,
        ], 'Confirmed');

        if (!$kode) {
            return $this->tolak($model->galat ?: 'Reservasi gagal disimpan.');
        }

        $_SESSION['reservasi_sukses'] = $kode;
        unset($_SESSION['draft_reservasi'], $_SESSION['qris_simulasi_bayar']);
        $this->d = [];
        return true;
    }

    // POST: ?page=reservasi-bayar-konfirmasi
    public function konfirmasiBayar()
    {
        $this->wajibPost();
        $this->kalauSudahSelesai();
        $this->wajib('paket');

        $id = (string) ($_POST['id_transaksi'] ?? '');

        // Mode simulasi: tombol ini menggantikan notifikasi gateway
        if (self::QRIS_SIMULASI && $id !== '' && $id === ($this->d['idTransaksi'] ?? null)) {
            $_SESSION['qris_simulasi_bayar'] = $id;
        }

        if ($this->verifikasi_pembayaran($id)) redirect('reservasi-voucher');

        $this->flash('error', $this->galat);
        redirect('reservasi-bayar');
    }

    // =====================================================
    // e-Voucher (hanya untuk reservasi yang baru saja diselesaikan di browser ini)
    // =====================================================
    public function voucher()
    {
        $kode = $_SESSION['reservasi_sukses'] ?? null;
        $voucher = $kode ? (new ReservasiModel())->get_voucher($kode) : null;
        if (!$voucher) redirect('form-reservasi');

        $pesanRsv = null;
        $draft = [];
        require BASE_PATH . '/views/pelanggan/step4_bayar_dp_voucher.php';
    }

    public function unduhVoucher()
    {
        $kode = $_SESSION['reservasi_sukses'] ?? null;
        if (!$kode) redirect('form-reservasi');
        $this->unduh_evoucher($kode);
    }

    public function unduh_evoucher($kodeBooking)
    {
        $v = (new ReservasiModel())->get_voucher($kodeBooking);
        if (!$v) redirect('form-reservasi');

        $isi = "E-VOUCHER RESERVASI MAKY CHICKEN\n"
             . "================================\n"
             . "Kode Booking : {$v['kode_booking']}\n"
             . "Atas Nama    : {$v['nama_pemesan']}\n"
             . "Tanggal      : " . date('d/m/Y', strtotime($v['tanggal'])) . "\n"
             . "Waktu        : {$v['slot_waktu']}\n"
             . "Lokasi       : Lantai {$v['lantai']} (" . (self::LANTAI[(int) $v['lantai']] ?? '-') . ")\n"
             . "Jumlah Tamu  : {$v['jumlah_orang']} orang\n"
             . "Paket        : " . ($v['nama_paket'] ?? '-') . " x" . ($v['qty'] ?? 0) . "\n"
             . "DP Dibayar   : Rp" . number_format((float) $v['dp'], 0, ',', '.') . " (" . $v['status_dp'] . ")\n"
             . "Status       : {$v['status']}\n"
             . "================================\n"
             . "Tunjukkan voucher ini kepada kasir/pelayan saat tiba.\n";

        $namaFile = 'evoucher_' . preg_replace('/[^A-Za-z0-9-]/', '', $kodeBooking) . '.txt';
        header('Content-Type: text/plain; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $namaFile . '"');
        echo $isi;
        exit;
    }

    // POST: ?page=reservasi-draft-batal
    public function batalkan_reservasi()
    {
        $this->wajibPost();
        unset($_SESSION['draft_reservasi'], $_SESSION['draft_detail'], $_SESSION['qris_simulasi_bayar'], $_SESSION['rsv_old']);
        $this->d = [];
        $this->flash('ok', 'Reservasi dibatalkan. Data yang kamu isi sudah dihapus.');
        redirect('form-reservasi');
    }
}