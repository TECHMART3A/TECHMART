<?php
require_once '../../config/database.php';

$db = getDB();

$id = $_GET['id'] ?? null;

if (!$id || !ctype_digit((string)$id)) {
    die('ID produk tidak valid!');
}

$stmt = $db->prepare("SELECT * FROM barang WHERE id = ?");
$stmt->execute([$id]);
$barang = $stmt->fetch();

if (!$barang) {
    die('Produk tidak ditemukan!');
}

$kategori = $db->query(
    "SELECT * FROM kategori ORDER BY nama_kategori"
)->fetchAll();

$supplier = $db->query(
    "SELECT * FROM supplier ORDER BY nama_supplier"
)->fetchAll();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode = trim($_POST['kode_barang']);
    $nama = trim($_POST['nama_barang']);
    $merek = trim($_POST['merek']);
    $kategori_id = $_POST['kategori_id'];
    $supplier_id = $_POST['supplier_id'] ?: null;
    $harga_beli = $_POST['harga_beli'];
    $harga_jual = $_POST['harga_jual'];
    $stok_minimum = $_POST['stok_minimum'];
    $deskripsi = trim($_POST['deskripsi']);

    $gambar = $barang['gambar'] ?? null;
    $gambarBaru = null;

    if (
        $kode === '' || $nama === '' || $kategori_id === '' ||
        !is_numeric($harga_beli) || !is_numeric($harga_jual) ||
        !ctype_digit((string)$stok_minimum) ||
        $harga_beli < 0 || $harga_jual < 0
    ) {
        $error = 'Periksa kembali data produk!';
    } else {
        try {
            // Upload foto baru jika dipilih
            if (
                isset($_FILES['gambar']) &&
                $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE
            ) {
                if ($_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
                    throw new Exception('Upload foto gagal. Coba pilih foto lagi.');
                }

                if ($_FILES['gambar']['size'] > 2 * 1024 * 1024) {
                    throw new Exception('Ukuran foto maksimal 2 MB.');
                }

                $tmp = $_FILES['gambar']['tmp_name'];
                $info = getimagesize($tmp);

                if ($info === false) {
                    throw new Exception('File harus berupa gambar yang valid.');
                }

                $jenisGambar = [
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png',
                    'image/webp' => 'webp'
                ];

                if (!isset($jenisGambar[$info['mime']])) {
                    throw new Exception('Format foto harus JPG, PNG, atau WEBP.');
                }

                $folder = '../../assets/img/produk/';

                if (!is_dir($folder)) {
                    throw new Exception('Folder penyimpanan foto belum tersedia.');
                }

                $namaFile = bin2hex(random_bytes(16)) . '.' . $jenisGambar[$info['mime']];

                if (!move_uploaded_file($tmp, $folder . $namaFile)) {
                    throw new Exception('Foto gagal disimpan.');
                }

                $gambarBaru = 'assets/img/produk/' . $namaFile;
                $gambar = $gambarBaru;
            }

            $stmt = $db->prepare(
                "UPDATE barang SET
                kode_barang = ?, nama_barang = ?, merek = ?,
                kategori_id = ?, supplier_id = ?,
                harga_beli = ?, harga_jual = ?,
                stok_minimum = ?, deskripsi = ?, gambar = ?
                WHERE id = ?"
            );

            $stmt->execute([
                $kode, $nama, $merek, $kategori_id,
                $supplier_id, $harga_beli, $harga_jual,
                $stok_minimum, $deskripsi, $gambar, $id
            ]);

            // Hapus foto lama setelah perubahan berhasil
            if ($gambarBaru && !empty($barang['gambar'])) {
                $fotoLama = '../../' . $barang['gambar'];

                if (is_file($fotoLama)) {
                    unlink($fotoLama);
                }
            }

            header('Location: index.php');
            exit;

        } catch (Exception $e) {
            // Hapus foto baru jika proses penyimpanan gagal
            if ($gambarBaru && is_file('../../' . $gambarBaru)) {
                unlink('../../' . $gambarBaru);
            }

            $error = $e->getMessage();
        } catch (PDOException $e) {
            if ($gambarBaru && is_file('../../' . $gambarBaru)) {
                unlink('../../' . $gambarBaru);
            }

            $error = 'Gagal mengubah produk. Periksa kode produk.';
        }
    }
}

require_once '../../layout/header.php';
?>

<h2>Edit Produk</h2>

<p style="color:red;"><?= htmlspecialchars($error) ?></p>

<form method="POST" enctype="multipart/form-data">

    <label>Kode Produk</label><br>
    <input type="text" name="kode_barang"
        value="<?= htmlspecialchars($barang['kode_barang']) ?>" required><br><br>

    <label>Nama Produk</label><br>
    <input type="text" name="nama_barang"
        value="<?= htmlspecialchars($barang['nama_barang']) ?>" required><br><br>

    <label>Merek</label><br>
    <input type="text" name="merek"
        value="<?= htmlspecialchars($barang['merek'] ?? '') ?>"><br><br>

    <label>Kategori</label><br>
    <select name="kategori_id" required>
        <?php foreach ($kategori as $k): ?>
            <option value="<?= $k['id'] ?>"
                <?= $barang['kategori_id'] == $k['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($k['nama_kategori']) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Supplier</label><br>
    <select name="supplier_id">
        <option value="">Belum Ditentukan</option>
        <?php foreach ($supplier as $s): ?>
            <option value="<?= $s['id'] ?>"
                <?= $barang['supplier_id'] == $s['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($s['nama_supplier']) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Harga Beli</label><br>
    <input type="number" name="harga_beli" min="0" step="0.01"
        value="<?= htmlspecialchars($barang['harga_beli']) ?>" required><br><br>

    <label>Harga Jual</label><br>
    <input type="number" name="harga_jual" min="0" step="0.01"
        value="<?= htmlspecialchars($barang['harga_jual']) ?>" required><br><br>

    <label>Stok Minimum</label><br>
    <input type="number" name="stok_minimum" min="0"
        value="<?= htmlspecialchars($barang['stok_minimum']) ?>" required><br><br>

    <label>Deskripsi</label><br>
    <textarea name="deskripsi"><?= htmlspecialchars($barang['deskripsi'] ?? '') ?></textarea><br><br>

    <p>Stok saat ini: <?= htmlspecialchars($barang['stok']) ?></p>

    <label>Foto Produk Saat Ini</label><br>

    <?php if (!empty($barang['gambar'])): ?>
        <img src="../../<?= htmlspecialchars($barang['gambar']) ?>"
             alt="Foto produk"
             style="width:150px; height:120px; object-fit:contain;"><br><br>
    <?php else: ?>
        <p>Belum ada foto produk.</p>
    <?php endif; ?>

    <label>Ganti Foto Produk</label><br>
    <input type="file" name="gambar"
        accept=".jpg,.jpeg,.png,.webp">
    <small>Format JPG, PNG, WEBP. Maksimal 2 MB.</small><br><br>

    <button type="submit">Simpan Perubahan</button>
    <a href="index.php">Kembali</a>

</form>

<?php require_once '../../layout/footer.php'; ?>