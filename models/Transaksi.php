<?php
require_once __DIR__ . '/../config/Database.php';

class Transaksi {
    private $conn;

    private $idTransaksi;
    private $daftarMenu = array();
    private $metodePembayaran;
    private $metodePesanan;
    private $statusPembayaran;
    private $tanggal;
    private $totalBayar;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getDataPenjualan($tanggal) {
        // 1. Ringkasan pendapatan, metode bayar, dan jenis pesanan
        $querySummary = "SELECT
                            COUNT(t.id_transaksi) as total_trx,
                            COALESCE(SUM(t.total_bayar), 0) as total_pendapatan,
                            COALESCE(SUM(CASE WHEN t.metode_pembayaran = 'QRIS' THEN t.total_bayar ELSE 0 END), 0) as total_qris,
                            COALESCE(SUM(CASE WHEN t.metode_pembayaran = 'Tunai' THEN t.total_bayar ELSE 0 END), 0) as total_tunai,
                            COUNT(CASE WHEN t.metode_pembayaran = 'QRIS' THEN 1 END) as count_qris,
                            COUNT(CASE WHEN t.metode_pembayaran = 'Tunai' THEN 1 END) as count_tunai,
                            COALESCE(SUM(CASE WHEN t.metode_pembayaran = 'Debit EDC' THEN t.total_bayar ELSE 0 END), 0) as total_debit,
                            COUNT(CASE WHEN t.metode_pembayaran = 'Debit EDC' THEN 1 END) as count_debit,
                            COALESCE(SUM(CASE WHEN t.metode_pesanan LIKE '%Dine In%' THEN t.total_bayar ELSE 0 END), 0) as total_dine_in,
                            COALESCE(SUM(CASE WHEN t.metode_pesanan LIKE '%Take Away%' THEN t.total_bayar ELSE 0 END), 0) as total_take_away,
                            COALESCE(SUM(CASE WHEN t.metode_pesanan LIKE '%Reservasi%' THEN t.total_bayar ELSE 0 END), 0) as total_reservasi,
                            COUNT(CASE WHEN t.metode_pesanan LIKE '%Dine In%' THEN 1 END) as count_dine_in,
                            COUNT(CASE WHEN t.metode_pesanan LIKE '%Take Away%' THEN 1 END) as count_take_away,
                            COUNT(CASE WHEN t.metode_pesanan LIKE '%Reservasi%' THEN 1 END) as count_reservasi
                         FROM transaksi t
                         WHERE DATE(t.tanggal) = :tanggal AND t.status_pembayaran = 'Lunas'";

        $stmt = $this->conn->prepare($querySummary);
        $stmt->execute([':tanggal' => $tanggal]);
        $ringkasan = $stmt->fetch(PDO::FETCH_ASSOC);

        // 2. Top 5 menu terlaris
        $queryTopMenu = "SELECT
                            m.nama_menu,
                            m.kategori,
                            SUM(dp.jumlah) as total_terjual,
                            SUM(dp.subtotal) as total_subtotal
                         FROM detail_pesanan dp
                         JOIN pesanan p ON dp.id_pesanan = p.id_pesanan
                         JOIN transaksi t ON p.id_pesanan = t.id_pesanan
                         JOIN menu m ON dp.id_menu = m.id_menu
                         WHERE DATE(t.tanggal) = :tanggal AND t.status_pembayaran = 'Lunas'
                         GROUP BY m.id_menu, m.nama_menu, m.kategori
                         ORDER BY total_terjual DESC LIMIT 5";

        $stmtMenu = $this->conn->prepare($queryTopMenu);
        $stmtMenu->execute([':tanggal' => $tanggal]);
        $topMenu = $stmtMenu->fetchAll(PDO::FETCH_ASSOC);

        // 3. Detail transaksi pesanan
        $queryDetail = "SELECT
                            DATE_FORMAT(p.waktu_pesan, '%H:%i WIB') as waktu,
                            p.kode_pesanan as id_transaksi_kode,
                            COALESCE(r.nama_pemesan, 'Pelanggan Restoran') as nama_pelanggan,
                            p.no_meja,
                            t.metode_pesanan,
                            t.metode_pembayaran,
                            t.total_bayar,
                            GROUP_CONCAT(CONCAT(dp.jumlah, 'x ', m.nama_menu) SEPARATOR ', ') as rincian_menu
                        FROM transaksi t
                        JOIN pesanan p ON t.id_pesanan = p.id_pesanan
                        JOIN detail_pesanan dp ON p.id_pesanan = dp.id_pesanan
                        JOIN menu m ON dp.id_menu = m.id_menu
                        LEFT JOIN pembayaran pem ON t.id_pembayaran = pem.id_pembayaran
                        LEFT JOIN reservasi r ON pem.id_reservasi = r.id_reservasi
                        WHERE DATE(t.tanggal) = :tanggal AND t.status_pembayaran = 'Lunas'
                        GROUP BY t.id_transaksi, p.kode_pesanan, r.nama_pemesan, p.no_meja, t.metode_pesanan, t.metode_pembayaran, t.total_bayar, p.waktu_pesan
                        ORDER BY p.waktu_pesan DESC";

        $stmtDetail = $this->conn->prepare($queryDetail);
        $stmtDetail->execute([':tanggal' => $tanggal]);
        $daftarTransaksi = $stmtDetail->fetchAll(PDO::FETCH_ASSOC);

        // 4. DP reservasi online (dicatat di pembayaran, bukan transaksi)
        $stmtDp = $this->conn->prepare(
            "SELECT COUNT(*) AS jml,
                    COALESCE(SUM(total), 0) AS nilai,
                    COALESCE(SUM(CASE WHEN metode = 'QRIS'  THEN total ELSE 0 END), 0) AS qris,
                    COALESCE(SUM(CASE WHEN metode = 'Tunai' THEN total ELSE 0 END), 0) AS tunai,
                    COALESCE(SUM(CASE WHEN metode = 'QRIS'  THEN 1 ELSE 0 END), 0) AS n_qris,
                    COALESCE(SUM(CASE WHEN metode = 'Tunai' THEN 1 ELSE 0 END), 0) AS n_tunai
             FROM pembayaran
             WHERE id_reservasi IS NOT NULL AND status = 'Lunas' AND DATE(waktu_bayar) = :tanggal"
        );
        $stmtDp->execute([':tanggal' => $tanggal]);
        $dp = $stmtDp->fetch(PDO::FETCH_ASSOC);

        $ringkasan['total_trx']         += (int)$dp['jml'];
        $ringkasan['total_pendapatan']  += (float)$dp['nilai'];
        $ringkasan['total_qris']        += (float)$dp['qris'];
        $ringkasan['total_tunai']       += (float)$dp['tunai'];
        $ringkasan['count_qris']        += (int)$dp['n_qris'];
        $ringkasan['count_tunai']       += (int)$dp['n_tunai'];
        $ringkasan['total_reservasi']   += (float)$dp['nilai'];
        $ringkasan['count_reservasi']   += (int)$dp['jml'];

        $stmtRsv = $this->conn->prepare(
            "SELECT DATE_FORMAT(pem.waktu_bayar, '%H:%i WIB') AS waktu,
                    r.kode_booking AS id_transaksi_kode,
                    r.nama_pemesan AS nama_pelanggan,
                    NULL AS no_meja,
                    'Reservasi' AS metode_pesanan,
                    pem.metode AS metode_pembayaran,
                    pem.total AS total_bayar,
                    CONCAT('DP reservasi ', COALESCE(pk.nama_paket, '')) AS rincian_menu
             FROM pembayaran pem
             JOIN reservasi r ON r.id_reservasi = pem.id_reservasi
             LEFT JOIN detail_reservasi d ON d.id_reservasi = r.id_reservasi
             LEFT JOIN paket_menu pk ON pk.id_paket = d.id_paket
             WHERE pem.status = 'Lunas' AND DATE(pem.waktu_bayar) = :tanggal"
        );
        $stmtRsv->execute([':tanggal' => $tanggal]);
        $daftarTransaksi = array_merge($daftarTransaksi, $stmtRsv->fetchAll(PDO::FETCH_ASSOC));
        usort($daftarTransaksi, function ($a, $b) { return strcmp($b['waktu'], $a['waktu']); });

        return [
            'ringkasan' => $ringkasan,
            'top_menu' => $topMenu,
            'daftar_transaksi' => $daftarTransaksi
        ];
    }

    public function getTotalKasSistem($tanggal) {
        $query = "SELECT COALESCE(SUM(total_bayar), 0) as total FROM transaksi WHERE DATE(tanggal) = :tanggal AND status_pembayaran = 'Lunas'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':tanggal' => $tanggal]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return floatval($res['total']);
    }
}