<?php
require_once '../../config/database.php';

$db = getDB();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode = strtoupper(trim($_POST['kode_kategori']));
    $nama = trim($_POST['nama_kategori']);
    $deskripsi = trim($_POST['deskripsi']);

    if ($kode === '' || $nama === '') {
        $error = 'Kode dan nama kategori wajib diisi!';
    } else {
        try {
            $stmt = $db->prepare(
                "INSERT INTO kategori
                (kode_kategori, nama_kategori, deskripsi)
                VALUES (?, ?, ?)"
            );

            $stmt->execute([$kode, $nama, $deskripsi]);

            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $error = 'Gagal menambahkan kategori. Kode mungkin sudah digunakan.';
        }
    }
}

require_once '../../layout/header.php';
?>

<h2>Tambah Kategori Produk</h2>

<p style="color:red;">
    <?= htmlspecialchars($error) ?>
</p>

<form method="POST">
    <label>Kode Kategori</label><br>
    <input type="text" name="kode_kategori" required>
    <br><br>

    <label>Nama Kategori</label><br>
    <input type="text" name="nama_kategori" required>
    <br><br>

    <label>Deskripsi</label><br>
    <textarea name="deskripsi"></textarea>
    <br><br>

    <button type="submit">Simpan</button>
    <a href="index.php">Kembali</a>
</form>

<?php require_once '../../layout/footer.php'; ?>