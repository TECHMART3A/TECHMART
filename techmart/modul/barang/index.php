<?php
require_once '../../config/database.php';

$db = getDB();

$cari = trim($_GET['cari'] ?? '');
$kategori_id = $_GET['kategori_id'] ?? '';

$sql = "SELECT barang.*, kategori.nama_kategori,
        supplier.nama_supplier
        FROM barang
        JOIN kategori ON barang.kategori_id = kategori.id
        LEFT JOIN supplier ON barang.supplier_id = supplier.id
        WHERE (barang.nama_barang LIKE ?
        OR barang.kode_barang LIKE ?)";

$params = ["%$cari%", "%$cari%"];

if ($kategori_id !== '') {
    $sql .= " AND barang.kategori_id = ?";
    $params[] = $kategori_id;
}

$sql .= " ORDER BY barang.nama_barang ASC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$dataBarang = $stmt->fetchAll();

$kategori = $db->query(
    "SELECT * FROM kategori ORDER BY nama_kategori"
)->fetchAll();

require_once '../../layout/header.php';
?>

<div class="page-heading">
    <h2>Data Produk TECHMART</h2>
    <p>Kelola informasi dan persediaan produk toko.</p>
</div>

<a href="tambah.php" class="btn-add">
    + Tambah Produk
</a>

<br><br>

<form method="GET">
    <input type="text" name="cari"
        placeholder="Cari nama atau kode produk..."
        value="<?= htmlspecialchars($cari) ?>">

    <select name="kategori_id">
        <option value="">Semua Kategori</option>

        <?php foreach ($kategori as $k): ?>
            <option value="<?= $k['id'] ?>"
                <?= (string)$kategori_id === (string)$k['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($k['nama_kategori']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Cari / Filter</button>
    <a href="index.php">Reset</a>
</form>

<div class="table-container">
    <table class="product-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama Produk</th>
                <th>Merek</th>
                <th>Kategori</th>
                <th>Supplier</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>Stok</th>
                <th>Status Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            <?php if ($dataBarang): ?>
                <?php $no = 1; ?>

                <?php foreach ($dataBarang as $b): ?>
                    <tr>
                        <td><?= $no++ ?></td>

                        <td><?= htmlspecialchars($b['kode_barang']) ?></td>

                        <td><?= htmlspecialchars($b['nama_barang']) ?></td>

                        <td><?= htmlspecialchars($b['merek'] ?? '') ?></td>

                        <td><?= htmlspecialchars($b['nama_kategori']) ?></td>

                        <td><?= htmlspecialchars($b['nama_supplier'] ?? '-') ?></td>

                        <td>Rp <?= number_format($b['harga_beli'], 0, ',', '.') ?></td>

                        <td>Rp <?= number_format($b['harga_jual'], 0, ',', '.') ?></td>

                        <td><?= $b['stok'] ?></td>

                        <td>
                            <?php if ($b['stok'] <= $b['stok_minimum']): ?>
                                <span class="status-warning">Stok Menipis</span>
                            <?php else: ?>
                                <span class="status-safe">Aman</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <div class="action-buttons">
                                <a href="edit.php?id=<?= $b['id'] ?>"
                                   class="btn-edit">
                                    Edit
                                </a>

                                <a href="hapus.php?id=<?= $b['id'] ?>"
                                   class="btn-delete"
                                   onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                    Hapus
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>

            <?php else: ?>
                <tr>
                    <td colspan="11" class="empty-data">
                        Belum ada data produk.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../layout/footer.php'; ?>