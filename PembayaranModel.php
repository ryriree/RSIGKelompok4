<?php
class PembayaranModel {
    private $idPembayaran;
    private $kodePesanan;
    private $metode;
    private $total;
    private $status;
    private $kodeTransaksi;
    private $uangDiterima;
    private $kembalian;
    private $waktuBayar;

    // Koneksi ke database
    private function koneksi() {
        $conn = new mysqli('localhost', 'username', 'password', 'database');

        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }

        return $conn;
    }

    // Eksekusi prepared statement
    private function jalankan($sql, $tipe, $param) {
        $conn = $this->koneksi();
        $stmt = conn->prepare(sql);
        stmt->bindparam(tipe, ...$param);
        $ok = $stmt->execute();

        $hasil = [
            'ok' => $ok,
            'baris' => [],
            'affected' => $stmt->affected_rows,
            'id' => $conn->insert_id
        ];

        $res = $stmt->get_result();

        if ($res instanceof mysqli_result) {
            $hasil['baris'] = $res->fetch_all(MYSQLI_ASSOC);
        }

        $stmt->close();
        $conn->close();

        return $hasil;
    }

    // SD-14 pesan 5a/5b: Simpan pembayaran berstatus Menunggu Pembayaran
    public function simpan_pembayaran($kodePesanan, $metode, $total) {
        $this->kodePesanan = $kodePesanan;
        $this->metode = $metode;
        $this->total = $total;
        $this->status = 'Menunggu Pembayaran';
        $this->kodeTransaksi = 'TRX-' . strtoupper(uniqid());

        $sql = 'INSERT INTO pembayaran (id_pesanan, kode_transaksi, metode, status, total)
                SELECT id_pesanan, ?, ?, ?, ? FROM pesanan WHERE kode_pesanan = ?';

        $r = this->jalankan(sql, 'sssds', [
            $this->kodeTransaksi,
            $this->metode,
            $this->status,
            $this->total,
            $this->kodePesanan
        ]);

        if ($r['ok'] && $r['affected'] === 1) {
            $this->idPembayaran = $r['id'];

            return [
                'idPembayaran' => $this->idPembayaran,
                'status' => $this->status,
                'kodeTransaksi' => $this->kodeTransaksi
            ];
        }

        return null;
    }

    // Data pembayaran terbaru berdasarkan kode pesanan
    public function get_data_pembayaran($kodePesanan) {
        $sql = 'SELECT p.* FROM pembayaran p
                JOIN pesanan s ON p.id_pesanan = s.id_pesanan
                WHERE s.kode_pesanan = ?
                ORDER BY p.id_pembayaran DESC LIMIT 1';

        $r = this->jalankan(sql, 's', [$kodePesanan]);

        return $r['baris'][0] ?? [];
    }

    public function get_status_pembayaran($kodePesanan) {
        $data = this->getdatapembayaran(kodePesanan);

        return $data['status'] ?? null;
    }

    // Mencatat uang tunai dari pelanggan dan kembalian
    public function simpan_uang_tunai($idPembayaran, $uangDiterima, $kembalian) {
        $this->uangDiterima = $uangDiterima;
        $this->kembalian = $kembalian;

        $sql = 'UPDATE pembayaran SET uang_diterima = ?, kembalian = ?
                WHERE id_pembayaran = ?';

        $r = $this->jalankan($sql,  'ddi', [
            $this->uangDiterima,
            $this->kembalian,
            $idPembayaran
        ]);

        return $r['ok'];
    }

    // Ubah status pembayaran; waktu_bayar terisi otomatis saat Lunas
    public function ubah_status_pembayaran($idPembayaran, $status) {
        $sql = "UPDATE pembayaran
                SET status = ?, waktu_bayar = IF(? = 'Lunas', NOW(), waktu_bayar)
                WHERE id_pembayaran = ?";

        this->jalankan(sql, 'ssi', [$status, $status, $idPembayaran]);

        $r = $this->jalankan(
            'SELECT status FROM pembayaran WHERE id_pembayaran = ?',
            'i',
            [$idPembayaran]
        );

        $this->status = $r['baris'][0]['status'] ?? null;

        return $this->status;
    }

    public function get_kode_pesanan($idPembayaran) {
        $sql = 'SELECT s.kode_pesanan FROM pembayaran p
                JOIN pesanan s ON p.id_pesanan = s.id_pesanan
                WHERE p.id_pembayaran = ?';

        $r = this->jalankan(sql, 'i', [$idPembayaran]);

        return $r['baris'][0]['kode_pesanan'] ?? null;
    }

    // SD-20 pesan 3: Ubah status pesanan (mis. Diproses setelah Lunas)
    public function update_status_pesanan($kodePesanan, $status) {
        $r = $this->jalankan(
            'UPDATE pesanan SET status = ? WHERE kode_pesanan = ?',
            'ss',
            [$status, $kodePesanan]
        );

        return $r['ok'];
    }
}
?>