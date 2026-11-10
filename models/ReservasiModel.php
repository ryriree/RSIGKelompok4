<?php
require_once __DIR__ . '/pendukung.php';

class ReservasiModel
{
    private PDO $conn;

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

    public function simpan_reservasi($dataReservasi, $status)
    {
        if (
            empty($_SESSION['draft_detail']['paketId']) ||
            empty($_SESSION['draft_detail']['qty'])
        ) {
            return false;
        }

        try {
            $this->conn->beginTransaction();

            $sql = "INSERT INTO reservasi
                (
                    kode_booking,
                    nama_pemesan,
                    kontak_pemesanan,
                    tanggal,
                    slot_waktu,
                    jumlah_orang,
                    lantai,
                    subtotal,
                    service_fee,
                    total,
                    dp,
                    status,
                    status_dp
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                $dataReservasi['kode_booking'],
                $dataReservasi['nama_pemesan'],
                $dataReservasi['kontak_pemesanan'],
                $dataReservasi['tanggal'],
                $dataReservasi['slot_waktu'],
                $dataReservasi['jumlah_orang'],
                $dataReservasi['lantai'],
                $dataReservasi['subtotal'],
                $dataReservasi['service_fee'],
                $dataReservasi['total'],
                $dataReservasi['dp'],
                $status,
                'Lunas'
            ]);

            $idReservasi = $this->conn->lastInsertId();

            $detail = $this->conn->prepare(
                "INSERT INTO detail_reservasi
                (id_reservasi, id_paket, qty, subtotal)
                VALUES (?, ?, ?, ?)"
            );

            $detail->execute([
                $idReservasi,
                $_SESSION['draft_detail']['paketId'],
                $_SESSION['draft_detail']['qty'],
                $dataReservasi['subtotal']
            ]);

            $this->conn->commit();
            return true;

        } catch (Throwable $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }

            error_log($e->getMessage());
            return false;
        }
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
            $kode = 'BK-' . strtoupper(bin2hex(random_bytes(4)));

            $stmt = $this->conn->prepare(
                "INSERT INTO reservasi
                (
                    kode_booking,
                    nama_pemesan,
                    kontak_pemesanan,
                    tanggal,
                    slot_waktu,
                    jumlah_orang,
                    lantai,
                    status,
                    status_dp
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, 'Confirmed', 'Belum Dibayar')"
            );

            return $stmt->execute([
                $kode,
                $data['nama_pemesan'],
                $data['kontak_pemesanan'],
                $data['tanggal'],
                $data['slot_waktu'],
                (int) $data['jumlah_orang'],
                (int) $data['lantai']
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