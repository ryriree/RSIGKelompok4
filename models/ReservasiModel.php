<?php
require_once __DIR__ . '/pendukung.php';

class ReservasiModel
{
    private PDO $conn;
    public $galat = '';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function get_data_jadwal($tanggal, $slotWaktu)
    {
        $sql = "SELECT jumlah_orang
                FROM reservasi
                WHERE tanggal = ?
                AND slot_waktu = ?
                AND status <> 'Dibatalkan'";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$tanggal, $slotWaktu]);

        return $stmt->fetchAll();
    }

    // [slot_waktu => jumlah tamu terisi] untuk satu tanggal
    public function get_terisi_per_slot($tanggal)
    {
        $stmt = $this->conn->prepare(
            "SELECT slot_waktu, SUM(jumlah_orang) AS terisi
             FROM reservasi
             WHERE tanggal = ? AND status <> 'Dibatalkan'
             GROUP BY slot_waktu"
        );
        $stmt->execute([$tanggal]);

        $hasil = [];
        foreach ($stmt->fetchAll() as $r) {
            $hasil[$r['slot_waktu']] = (int) $r['terisi'];
        }
        return $hasil;
    }

    // (tidak dipakai lagi oleh alur pelanggan, dibiarkan agar tidak merusak kode lain)
    public function simpan_draft_lantai($lantai)
    {
        $_SESSION['draft_detail']['lantai'] = (int) $lantai;
        return true;
    }

    public function simpan_data_detail_pesanan($paketId, $qty)
    {
        $_SESSION['draft_detail']['paketId'] = (int) $paketId;
        $_SESSION['draft_detail']['qty'] = (int) $qty;
        return true;
    }

    // Reservasi pelanggan setelah DP terverifikasi: reservasi + detail + pembayaran DP dalam satu transaksi.
    // Mengembalikan kode booking, atau false (alasan ada di $this->galat).
    public function simpan_reservasi($d, $status)
    {
        $this->galat = '';

        try {
            $this->conn->beginTransaction();

            // Kunci baris jadwal ini lalu cek ulang kapasitas (mencegah dua pelanggan merebut slot terakhir)
            $cek = $this->conn->prepare(
                "SELECT COALESCE(SUM(jumlah_orang), 0) FROM reservasi
                 WHERE tanggal = ? AND slot_waktu = ? AND status <> 'Dibatalkan' FOR UPDATE"
            );
            $cek->execute([$d['tanggal'], $d['slot_waktu']]);
            if ((int) $cek->fetchColumn() + (int) $d['jumlah_orang'] > (int) $d['kapasitas']) {
                $this->conn->rollBack();
                $this->galat = 'Maaf, jadwal ini baru saja penuh. Pilih slot lain.';
                return false;
            }

            $kode = null;
            for ($i = 0; $i < 5 && $kode === null; $i++) {
                $coba = 'RES-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(2)));
                $ada = $this->conn->prepare("SELECT 1 FROM reservasi WHERE kode_booking = ?");
                $ada->execute([$coba]);
                if (!$ada->fetchColumn()) $kode = $coba;
            }
            if ($kode === null) throw new RuntimeException('Gagal membuat kode booking');

            $stmt = $this->conn->prepare(
                "INSERT INTO reservasi
                 (kode_booking, nama_pemesan, kontak_pemesanan, tanggal, slot_waktu, jumlah_orang,
                  lantai, subtotal, service_fee, total, dp, status, status_dp)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Lunas')"
            );
            $stmt->execute([
                $kode, $d['nama_pemesan'], $d['kontak_pemesanan'], $d['tanggal'], $d['slot_waktu'],
                $d['jumlah_orang'], $d['lantai'], $d['subtotal'], $d['service_fee'], $d['total'],
                $d['dp'], $status
            ]);
            $idReservasi = (int) $this->conn->lastInsertId();

            $det = $this->conn->prepare(
                "INSERT INTO detail_reservasi (id_reservasi, id_paket, qty, subtotal) VALUES (?, ?, ?, ?)"
            );
            $det->execute([$idReservasi, $d['paket_id'], $d['qty'], $d['subtotal']]);

            $pay = $this->conn->prepare(
                "INSERT INTO pembayaran (kode_transaksi, id_reservasi, metode, total, status, waktu_bayar)
                 VALUES (?, ?, 'QRIS', ?, 'Lunas', NOW())"
            );
            $pay->execute([$d['kode_transaksi'], $idReservasi, $d['dp']]);

            $this->conn->commit();
            return $kode;
        } catch (Throwable $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log($e->getMessage());
            $this->galat = 'Reservasi gagal disimpan. Silakan coba lagi.';
            return false;
        }
    }

    // Data e-voucher (reservasi + paket) berdasarkan kode booking
    public function get_voucher($kodeBooking)
    {
        $stmt = $this->conn->prepare(
            "SELECT r.*, d.qty, p.nama_paket
             FROM reservasi r
             LEFT JOIN detail_reservasi d ON d.id_reservasi = r.id_reservasi
             LEFT JOIN paket_menu p ON p.id_paket = d.id_paket
             WHERE r.kode_booking = ? LIMIT 1"
        );
        $stmt->execute([$kodeBooking]);
        return $stmt->fetch() ?: null;
    }

    public function getall_data_reservasi()
    {
        $stmt = $this->conn->query(
            "SELECT *
             FROM reservasi
             ORDER BY tanggal DESC, slot_waktu ASC"
        );

        return $stmt->fetchAll();
    }

    public function cari_data_reservasi($q)
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM reservasi
             WHERE nama_pemesan LIKE ? OR kode_booking LIKE ?
             ORDER BY tanggal DESC, slot_waktu ASC"
        );
        $like = '%' . $q . '%';
        $stmt->execute([$like, $like]);
        return $stmt->fetchAll();
    }

    public function get_data_reservasi($idReservasi)
    {
        $stmt = $this->conn->prepare(
            "SELECT *
             FROM reservasi
             WHERE id_reservasi = ?"
        );

        $stmt->execute([(int) $idReservasi]);

        return $stmt->fetch() ?: null;
    }

    public function tambah_data_reservasi($data)
    {
        try {
            $kode = 'RES-' . date('Ymd') . '-' . random_int(100, 999);
            $statusDp = in_array($data['status_dp'] ?? '', ['Lunas', 'Belum Dibayar'], true)
                ? $data['status_dp'] : 'Belum Dibayar';
            $dp = 50000.00;

            $stmt = $this->conn->prepare(
                "INSERT INTO reservasi
                 (kode_booking, nama_pemesan, kontak_pemesanan, tanggal, slot_waktu,
                  lantai, jumlah_orang, dp, total, status, status_dp)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Confirmed', ?)"
            );

            return $stmt->execute([
                $kode,
                $data['nama_pemesan'],
                $data['kontak_pemesanan'],
                $data['tanggal'],
                $data['slot_waktu'],
                (int) $data['lantai'],
                (int) $data['jumlah_orang'],
                $dp,
                $dp,
                $statusDp
            ]);
        } catch (Throwable $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function ubah_data_reservasi($data)
    {
        try {
            $stmt = $this->conn->prepare(
                "UPDATE reservasi
                 SET nama_pemesan = ?,
                     kontak_pemesanan = ?,
                     tanggal = ?,
                     slot_waktu = ?,
                     jumlah_orang = ?,
                     lantai = ?
                 WHERE id_reservasi = ?"
            );

            return $stmt->execute([
                $data['nama_pemesan'],
                $data['kontak_pemesanan'],
                $data['tanggal'],
                $data['slot_waktu'],
                (int) $data['jumlah_orang'],
                (int) $data['lantai'],
                (int) $data['id_reservasi']
            ]);

        } catch (Throwable $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function ubah_status_atau_hapus_data($idReservasi)
    {
        $stmt = $this->conn->prepare(
            "UPDATE reservasi
             SET status = 'Dibatalkan'
             WHERE id_reservasi = ?"
        );

        return $stmt->execute([(int) $idReservasi]);
    }

    public function ubah_data_status_dp($idReservasi, $statusDP)
    {
        $stmt = $this->conn->prepare(
            "UPDATE reservasi
             SET status_dp = ?
             WHERE id_reservasi = ?"
        );

        return $stmt->execute([
            $statusDP,
            (int) $idReservasi
        ]);
    }
}