<?php
require_once '../../config/database.php';

$db = getDB();
$error = '';

$kategori = $db->query(
    "SELECT * FROM kategori ORDER BY nama_kategori"
)->fetchAll();

$supplier = $db->query(
    "SELECT * FROM supplier ORDER BY nama_supplier"
)->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode = trim($_POST['kode_barang'] ?? '');
    $nama = trim($_POST['nama_barang'] ?? '');
    $merek = trim($_POST['merek'] ?? '');
    $kategori_id = $_POST['kategori_id'] ?? '';
    $supplier_id = !empty($_POST['supplier_id']) ? $_POST['supplier_id'] : null;
    $harga_beli = $_POST['harga_beli'] ?? '';
    $harga_jual = $_POST['harga_jual'] ?? '';
    $stok = $_POST['stok'] ?? '';
    $stok_minimum = $_POST['stok_minimum'] ?? '';
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    $gambar = null;

    if (
        $kode === '' || $nama === '' || $kategori_id === '' ||
        !is_numeric($harga_beli) || !is_numeric($harga_jual) ||
        !ctype_digit((string)$stok) ||
        !ctype_digit((string)$stok_minimum) ||
        $harga_beli < 0 || $harga_jual < 0
    ) {
        $error = 'Periksa kembali data produk yang dimasukkan!';
    } else {
        try {
            // Proses upload foto jika dipilih
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

                $mime = $info['mime'];

                $jenisGambar = [
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png',
                    'image/webp' => 'webp'
                ];

                if (!isset($jenisGambar[$mime])) {
                    throw new Exception('Format foto harus JPG, PNG, atau WEBP.');
                }

                $folder = '../../assets/img/produk/';

                if (!is_dir($folder)) {
                    throw new Exception('Folder penyimpanan foto belum tersedia.');
                }

                $namaFile = bin2hex(random_bytes(16)) . '.' . $jenisGambar[$mime];

                if (!move_uploaded_file($tmp, $folder . $namaFile)) {
                    throw new Exception('Foto gagal disimpan ke folder produk.');
                }

                $gambar = 'assets/img/produk/' . $namaFile;
            }

            $stmt = $db->prepare(
                "INSERT INTO barang
                (kode_barang, nama_barang, merek, kategori_id,
                supplier_id, harga_beli, harga_jual, stok,
                stok_minimum, deskripsi, gambar)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );

            $stmt->execute([
                $kode, $nama, $merek, $kategori_id,
                $supplier_id, $harga_beli, $harga_jual,
                $stok, $stok_minimum, $deskripsi, $gambar
            ]);

            header('Location: index.php');
            exit;

        } catch (Exception $e) {
            $error = $e->getMessage();
        } catch (PDOException $e) {
            $error = 'Gagal menyimpan produk. Periksa kode produk dan kategori.';
        }
    }
}

require_once '../../layout/header.php';
?>

<h2>Tambah Produk</h2>

<p style="color:red;">
    <?= htmlspecialchars($error) ?>
</p>

<form method="POST" enctype="multipart/form-data">

    <label>Kode Produk</label><br>
    <input type="text" name="kode_barang" required><br><br>

    <label>Nama Produk</label><br>
    <input type="text" name="nama_barang" required><br><br>

    <label>Merek</label><br>
    <input type="text" name="merek"><br><br>

    <label>Kategori</label><br>
    <select name="kategori_id" required>
        <option value="">Pilih Kategori</option>
        <?php foreach ($kategori as $k): ?>
            <option value="<?= $k['id'] ?>">
                <?= htmlspecialchars($k['nama_kategori']) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Supplier</label><br>
    <select name="supplier_id">
        <option value="">Belum Ditentukan</option>
        <?php foreach ($supplier as $s): ?>
            <option value="<?= $s['id'] ?>">
                <?= htmlspecialchars($s['nama_supplier']) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Harga Beli</label><br>
    <input type="number" name="harga_beli" min="0" step="0.01" required><br><br>

    <label>Harga Jual</label><br>
    <input type="number" name="harga_jual" min="0" step="0.01" required><br><br>

    <label>Stok Awal</label><br>
    <input type="number" name="stok" min="0" required><br><br>

    <label>Stok Minimum</label><br>
    <input type="number" name="stok_minimum" min="0" required><br><br>

    <label>Deskripsi</label><br>
    <textarea name="deskripsi"></textarea><br><br>

    <label>Foto Produk</label><br>
    <input
        type="file"
        name="gambar"
        accept=".jpg,.jpeg,.png,.webp"
    >
    <small>Format JPG, PNG, WEBP. Maksimal 2 MB.</small>
    <br><br>

    <button type="submit">Simpan Produk</button>
    <a href="index.php">Kembali</a>

</form>

<?php require_once '../../layout/footer.php'; ?>