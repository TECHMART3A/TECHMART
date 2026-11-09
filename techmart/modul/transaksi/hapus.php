<?php
require_once '../../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

$db = getDB();

$id = $_POST['id'] ?? '';

if (!ctype_digit((string)$id)) {
    die('ID transaksi tidak valid!');
}

try {
    $db->beginTransaction();

    // Ambil dan kunci transaksi
    $stmt = $db->prepare(
        "SELECT * FROM transaksi_stok
         WHERE id = ? FOR UPDATE"
    );
    $stmt->execute([$id]);
    $transaksi = $stmt->fetch();

    if (!$transaksi) {
        throw new Exception('Transaksi tidak ditemukan!');
    }

    if ($transaksi['status'] !== 'AKTIF') {
        throw new Exception('Transaksi sudah pernah dibatalkan!');
    }

    // Ambil dan kunci stok produk
    $stmt = $db->prepare(
        "SELECT stok FROM barang WHERE id = ? FOR UPDATE"
    );
    $stmt->execute([$transaksi['barang_id']]);
    $barang = $stmt->fetch();

    if (!$barang) {
        throw new Exception('Produk tidak ditemukan!');
    }

    $stok = (int)$barang['stok'];
    $jumlah = (int)$transaksi['jumlah'];

    if ($transaksi['jenis'] === 'MASUK') {
        if ($stok < $jumlah) {
            throw new Exception(
                'Pembatalan tidak bisa dilakukan karena stok saat ini tidak mencukupi untuk dikurangi.'
            );
        }

        $stokBaru = $stok - $jumlah;
    } else {
        $stokBaru = $stok + $jumlah;
    }

    // Kembalikan stok
    $stmt = $db->prepare(
        "UPDATE barang SET stok = ? WHERE id = ?"
    );
    $stmt->execute([$stokBaru, $transaksi['barang_id']]);

    // Tandai transaksi sebagai batal
    $stmt = $db->prepare(
        "UPDATE transaksi_stok SET status = 'BATAL' WHERE id = ?"
    );
    $stmt->execute([$id]);

    $db->commit();

    header('Location: index.php');
    exit;

} catch (Throwable $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }

    die('Gagal membatalkan transaksi: ' . htmlspecialchars($e->getMessage()));
}
?>