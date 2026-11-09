<?php
require_once '../../config/database.php';

$db = getDB();

$id = $_GET['id'] ?? null;

$stmt = $db->prepare("SELECT * FROM kategori WHERE id = ?");
$stmt->execute([$id]);
$kategori = $stmt->fetch();

if (!$kategori) {
    die('Kategori tidak ditemukan!');
}

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
                "UPDATE kategori
                SET kode_kategori = ?,
                    nama_kategori = ?,
                    deskripsi = ?
                WHERE id = ?"
            );

            $stmt->execute([$kode, $nama, $deskripsi, $id]);

            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $error = 'Gagal mengubah kategori. Kode mungkin sudah digunakan.';
        }
    }
}

require_once '../../layout/header.php';
?>

<h2>Edit Kategori Produk</h2>

<p style="color:red;">
    <?= htmlspecialchars($error) ?>
</p>

<form method="POST">
    <label>Kode Kategori</label><br>
    <input type="text" name="kode_kategori"
        value="<?= htmlspecialchars($kategori['kode_kategori']) ?>"
        required>
    <br><br>

    <label>Nama Kategori</label><br>
    <input type="text" name="nama_kategori"
        value="<?= htmlspecialchars($kategori['nama_kategori']) ?>"
        required>
    <br><br>

    <label>Deskripsi</label><br>
    <textarea name="deskripsi"><?= htmlspecialchars($kategori['deskripsi'] ?? '') ?></textarea>
    <br><br>

    <button type="submit">Simpan Perubahan</button>
    <a href="index.php">Kembali</a>
</form>

<?php require_once '../../layout/footer.php'; ?>