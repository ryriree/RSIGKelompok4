<?php
require_once __DIR__ . '/pendukung.php';

class UserModel
{
    private PDO $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function get_data_user($username, $password)
    {
        $stmt = $this->conn->prepare(
            "SELECT id_user, nama, username, password, role,
                    status_akun, akun_diblokir
             FROM users
             WHERE username = ?"
        );

        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if (!$user) {
            return null;
        }

        if (
            (int) $user['akun_diblokir'] === 1 ||
            $user['status_akun'] !== 'Aktif'
        ) {
            return [
                'status_akun' => $user['status_akun'],
                'akun_diblokir' => 1
            ];
        }

        if (!password_verify($password, $user['password'])) {
            return null;
        }

        unset($user['password']);

        return $user;
    }

    public function blokir_akun($username)
    {
        $stmt = $this->conn->prepare(
            "UPDATE users
             SET akun_diblokir = 1,
                 status_akun = 'Diblokir'
             WHERE username = ?"
        );

        return $stmt->execute([$username]);
    }

    public function getall_data_staff()
    {
        $stmt = $this->conn->query(
            "SELECT id_user, nama, email, username, role, status_akun
             FROM users
             ORDER BY nama ASC"
        );

        return $stmt->fetchAll();
    }

    public function get_data_staff($idUser)
    {
        $stmt = $this->conn->prepare(
            "SELECT id_user, nama, email, username, role, status_akun
             FROM users
             WHERE id_user = ?"
        );

        $stmt->execute([(int) $idUser]);

        return $stmt->fetch() ?: null;
    }

    public function tambah_data_staff($dataStaff)
    {
        try {
            $hash = password_hash(
                $dataStaff['password'],
                PASSWORD_BCRYPT
            );

            $stmt = $this->conn->prepare(
                "INSERT INTO users
                (nama, email, username, password, role, status_akun)
                VALUES (?, ?, ?, ?, ?, ?)"
            );

            return $stmt->execute([
                $dataStaff['nama'],
                $dataStaff['email'],
                $dataStaff['username'],
                $hash,
                $dataStaff['role'],
                $dataStaff['status_akun'] ?? 'Aktif'
            ]);

        } catch (Throwable $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function ubah_data_staff($idUser, $dataStaff)
    {
        $stmt = $this->conn->prepare(
            "UPDATE users
             SET nama = ?,
                 email = ?,
                 username = ?,
                 role = ?
             WHERE id_user = ?"
        );

        return $stmt->execute([
            $dataStaff['nama'],
            $dataStaff['email'],
            $dataStaff['username'],
            $dataStaff['role'],
            (int) $idUser
        ]);
    }

    public function ubah_status_akun($idUser, $status)
    {
        $stmt = $this->conn->prepare(
            "UPDATE users
             SET status_akun = ?
             WHERE id_user = ?"
        );

        return $stmt->execute([
            $status,
            (int) $idUser
        ]);
    }
}