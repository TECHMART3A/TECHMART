<?php
// Konfigurasi database TECHMART

define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'techmart_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Koneksi database menggunakan PDO
function getDB() {
    try {
        $db = new PDO(
            "mysql:host=" . DB_HOST .
            ";port=" . DB_PORT .
            ";dbname=" . DB_NAME .
            ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );

        return $db;

    } catch (PDOException $e) {
        die("Koneksi database gagal: " . $e->getMessage());
    }
}
?>