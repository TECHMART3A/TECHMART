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
$error = '';

$dataBarang = $db->query(
    "SELECT id, kode_barang, nama_barang, stok
     FROM barang ORDER BY nama_barang"
)->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $barang_id = $_POST['barang_id'] ?? '';
    $jenis = $_POST['jenis'] ?? '';
    $jumlah = $_POST['jumlah'] ?? '';
    $keterangan = trim($_POST['keterangan'] ?? '');

    if (
        !ctype_digit((string)$barang_id) ||
        !in_array($jenis, ['MASUK', 'KELUAR'], true) ||
        !ctype_digit((string)$jumlah) ||
        (int)$jumlah <= 0
    ) {
        $error = 'Periksa kembali produk, jenis, dan jumlah transaksi!';
    } else {
        try {
            $db->beginTransaction();

            // Kunci data produk agar stok tidak berubah bersamaan
            $stmt = $db->prepare(
                "SELECT stok FROM barang WHERE id = ? FOR UPDATE"
            );
            $stmt->execute([$barang_id]);
            $barang = $stmt->fetch();

            if (!$barang) {
                throw new Exception('Produk tidak ditemukan!');
            }

            $stokSekarang = (int)$barang['stok'];
            $jumlah = (int)$jumlah;

            if ($jenis === 'KELUAR' && $jumlah > $stokSekarang) {
                throw new Exception('Stok tidak mencukupi untuk transaksi keluar!');
            }

            if ($jenis === 'MASUK') {
                $stokBaru = $stokSekarang + $jumlah;
            } else {
                $stokBaru = $stokSekarang - $jumlah;
            }

            // Simpan transaksi
            $kode = 'TRX-' . date('YmdHis') . '-' . random_int(100, 999);

            $stmt = $db->prepare(
                "INSERT INTO transaksi_stok
                (kode_transaksi, barang_id, user_id, jenis, jumlah, keterangan)
                VALUES (?, ?, ?, ?, ?, ?)"
            );

            $stmt->execute([
                $kode,
                $barang_id,
                $_SESSION['user_id'],
                $jenis,
                $jumlah,
                $keterangan
            ]);

            // Perbarui stok produk
            $stmt = $db->prepare(
                "UPDATE barang SET stok = ? WHERE id = ?"
            );
            $stmt->execute([$stokBaru, $barang_id]);

            $db->commit();

            header('Location: index.php');
            exit;

        } catch (Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }

            $error = $e->getMessage();
        }
    }
}

require_once '../../layout/header.php';
?>

<h2>Tambah Transaksi Stok</h2>

<p style="color:red;">
    <?= htmlspecialchars($error) ?>
</p>

<form method="POST">
    <label>Pilih Produk</label><br>
    <select name="barang_id" required>
        <option value="">-- Pilih Produk --</option>
        <?php foreach ($dataBarang as $b): ?>
            <option value="<?= $b['id'] ?>">
                <?= htmlspecialchars($b['kode_barang']) ?> -
                <?= htmlspecialchars($b['nama_barang']) ?>
                (Stok: <?= $b['stok'] ?>)
            </option>
        <?php endforeach; ?>
    </select>

    <br><br>

    <label>Jenis Transaksi</label><br>
    <select name="jenis" required>
        <option value="">-- Pilih Jenis --</option>
        <option value="MASUK">Stok Masuk</option>
        <option value="KELUAR">Stok Keluar</option>
    </select>

    <br><br>

    <label>Jumlah</label><br>
    <input type="number" name="jumlah" min="1" required>

    <br><br>

    <label>Keterangan</label><br>
    <textarea name="keterangan"></textarea>

    <br><br>

    <button type="submit">Simpan Transaksi</button>
    <a href="index.php">Kembali</a>
</form>

<?php require_once '../../layout/footer.php'; ?>