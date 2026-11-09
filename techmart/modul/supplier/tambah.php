<?php
require_once '../../config/database.php';

$db = getDB();
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
                "INSERT INTO supplier
                (kode_supplier, nama_supplier, telepon, email, alamat)
                VALUES (?, ?, ?, ?, ?)"
            );

            $stmt->execute([
                $kode, $nama, $telepon, $email, $alamat
            ]);

            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $error = 'Gagal menambahkan supplier. Kode mungkin sudah digunakan.';
        }
    }
}

require_once '../../layout/header.php';
?>

<h2>Tambah Supplier</h2>

<p style="color:red;"><?= htmlspecialchars($error) ?></p>

<form method="POST">
    <label>Kode Supplier</label><br>
    <input type="text" name="kode_supplier" required><br><br>

    <label>Nama Supplier</label><br>
    <input type="text" name="nama_supplier" required><br><br>

    <label>Nomor Telepon</label><br>
    <input type="text" name="telepon"><br><br>

    <label>Email</label><br>
    <input type="email" name="email"><br><br>

    <label>Alamat</label><br>
    <textarea name="alamat"></textarea><br><br>

    <button type="submit">Simpan Supplier</button>
    <a href="index.php">Kembali</a>
</form>

<?php require_once '../../layout/footer.php'; ?>