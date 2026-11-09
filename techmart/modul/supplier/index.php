<?php
require_once '../../config/database.php';

$db = getDB();

$stmt = $db->query(
    "SELECT * FROM supplier ORDER BY nama_supplier ASC"
);

$dataSupplier = $stmt->fetchAll();

require_once '../../layout/header.php';
?>

<div class="page-heading">
    <h2>Data Supplier TECHMART</h2>
    <p>Kelola informasi pemasok produk TECHMART.</p>
</div>

<a href="tambah.php" class="btn-add">
    + Tambah Supplier
</a>

<div class="table-container">
    <table class="supplier-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Supplier</th>
                <th>Nama Supplier</th>
                <th>Telepon</th>
                <th>Email</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            <?php if ($dataSupplier): ?>
                <?php $no = 1; ?>

                <?php foreach ($dataSupplier as $s): ?>
                    <tr>
                        <td><?= $no++ ?></td>

                        <td><?= htmlspecialchars($s['kode_supplier']) ?></td>

                        <td><?= htmlspecialchars($s['nama_supplier']) ?></td>

                        <td><?= htmlspecialchars($s['telepon'] ?? '') ?></td>

                        <td><?= htmlspecialchars($s['email'] ?? '') ?></td>

                        <td><?= htmlspecialchars($s['alamat'] ?? '') ?></td>

                        <td>
                            <div class="action-buttons">
                                <a href="edit.php?id=<?= $s['id'] ?>"
                                   class="btn-edit">
                                    Edit
                                </a>

                                <a href="hapus.php?id=<?= $s['id'] ?>"
                                   class="btn-delete"
                                   onclick="return confirm('Yakin ingin menghapus supplier ini?')">
                                    Hapus
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>

            <?php else: ?>
                <tr>
                    <td colspan="7" class="empty-data">
                        Belum ada data supplier.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../layout/footer.php'; ?>