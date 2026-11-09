<?php
require_once 'config/database.php';

$db = getDB();

$username = 'admin';
$password = 'admin123';
$nama = 'Administrator TECHMART';

// Periksa apakah akun admin sudah ada
$cek = $db->prepare("SELECT id FROM users WHERE username = ?");
$cek->execute([$username]);

if ($cek->fetch()) {
    echo "Akun admin sudah tersedia. Tidak perlu membuat ulang.";
} else {
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (nama, username, password, role)
            VALUES (?, ?, ?, ?)";

    $stmt = $db->prepare($sql);
    $stmt->execute([
        $nama,
        $username,
        $password_hash,
        'admin'
    ]);

    echo "Akun admin TECHMART berhasil dibuat!";
}
?>