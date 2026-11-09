<?php
require_once '../../config/database.php';

$db = getDB();

$id = $_GET['id'] ?? null;

if (!$id || !ctype_digit((string) $id)) {
    die('ID kategori tidak valid!');
}

// Periksa apakah kategori masih digunakan oleh produk
$stmt = $db->prepare(
    "SELECT COUNT(*) FROM barang WHERE kategori_id = ?"
);
$stmt->execute([$id]);

if ($stmt->fetchColumn() > 0) {
    die('Kategori tidak bisa dihapus karena masih digunakan oleh produk.');
}

// Hapus kategori
$stmt = $db->prepare("DELETE FROM kategori WHERE id = ?");
$stmt->execute([$id]);

header('Location: index.php');
exit;
?>