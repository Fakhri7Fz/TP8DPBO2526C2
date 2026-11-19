<?php

require_once __DIR__ . '/../config.php';

// Kelas dasar yang mengelola koneksi PDO dan eksekusi query terparameterisasi.
class DB
{
    private $conn;
    private $result;

    public function __construct()
    {
        try {
            // Membuat koneksi menggunakan nilai yang didefinisikan di Config.
            $this->conn = new PDO(
                "mysql:host=" . Config::$db_host . ";dbname=" . Config::$db_name . ";charset=utf8mb4",
                Config::$db_user,
                Config::$db_pass
            );

            // Memastikan kegagalan query dilaporkan sebagai exception.
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            die("Koneksi database gagal.");
        }
    }

    // Menyiapkan dan menjalankan query dengan parameter agar nilai tidak disisipkan ke SQL.
    public function execute($query, $params = [])
    {
        $this->result = $this->conn->prepare($query);
        $this->result->execute($params);

        return $this->result;
    }

    // Mengambil satu baris hasil dari query terakhir dalam bentuk associative array.
    public function getResult()
    {
        return $this->result->fetch(PDO::FETCH_ASSOC);
    }
}
