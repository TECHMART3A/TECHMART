<?php
require_once '../../config/database.php';

$db = getDB();

$id = $_GET['id'] ?? null;

if (!$id || !ctype_digit((string)$id)) {
    die('ID supplier tidak valid!');
}

$stmt = $db->prepare("SELECT * FROM supplier WHERE id = ?");
$stmt->execute([$id]);
$supplier = $stmt->fetch();

if (!$supplier) {
    die('Supplier tidak ditemukan!');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode = strtoupper(trim($_POST['kode_supplier']));
    $nama = trim($_POST['nama_supplier']);
    $telepon = trim($_POST['telepon']);
    $email = trim($_POST['email']);
    $alamat = trim($_POST['alamat']);

    if ($kode === '' || $nama === '') {
        $error = 'Kode dan nama supplier wajib diisi!';
    } elseif ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid!';
    } else {
        try {
            $stmt = $db->prepare(
                "UPDATE supplier SET
                kode_supplier = ?,
                nama_supplier = ?,
                telepon = ?,
                email = ?,
                alamat = ?
                WHERE id = ?"
            );

            $stmt->execute([
                $kode, $nama, $telepon, $email, $alamat, $id
            ]);

            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $error = 'Gagal mengubah supplier. Periksa kode supplier.';
        }
    }
}

require_once '../../layout/header.php';
?>

<h2>Edit Supplier</h2>

<p style="color:red;"><?= htmlspecialchars($error) ?></p>

<form method="POST">
    <label>Kode Supplier</label><br>
    <input type="text" name="kode_supplier"
        value="<?= htmlspecialchars($supplier['kode_supplier']) ?>" required><br><br>

    <label>Nama Supplier</label><br>
    <input type="text" name="nama_supplier"
        value="<?= htmlspecialchars($supplier['nama_supplier']) ?>" required><br><br>

    <label>Nomor Telepon</label><br>
    <input type="text" name="telepon"
        value="<?= htmlspecialchars($supplier['telepon'] ?? '') ?>"><br><br>

    <label>Email</label><br>
    <input type="email" name="email"
        value="<?= htmlspecialchars($supplier['email'] ?? '') ?>"><br><br>

    <label>Alamat</label><br>
    <textarea name="alamat"><?= htmlspecialchars($supplier['alamat'] ?? '') ?></textarea><br><br>

    <button type="submit">Simpan Perubahan</button>
    <a href="index.php">Kembali</a>
</form>

<?php require_once '../../layout/footer.php'; ?>