<?php
require_once '../../config/database.php';

$db = getDB();

$id = $_GET['id'] ?? null;

if (!$id || !ctype_digit((string)$id)) {
    die('ID produk tidak valid!');
}

$stmt = $db->prepare(
    "SELECT COUNT(*) FROM transaksi_stok WHERE barang_id = ?"
);
$stmt->execute([$id]);

if ($stmt->fetchColumn() > 0) {
    die('Produk tidak dapat dihapus karena sudah memiliki riwayat transaksi stok.');
}

$stmt = $db->prepare("DELETE FROM barang WHERE id = ?");
$stmt->execute([$id]);

header('Location: index.php');
exit;
?>