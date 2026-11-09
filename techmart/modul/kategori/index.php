<?php
require_once '../../config/database.php';

$db = getDB();

$stmt = $db->query(
    "SELECT * FROM kategori ORDER BY nama_kategori ASC"
);

$dataKategori = $stmt->fetchAll();

require_once '../../layout/header.php';
?>

<div class="page-heading">
    <h2>Data Kategori Produk</h2>
    <p>Kelola kategori produk TECHMART.</p>
</div>

<a href="tambah.php" class="btn-add">
    + Tambah Kategori
</a>

<div class="table-container">
    <table class="category-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Kategori</th>
                <th>Nama Kategori</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            <?php if (count($dataKategori) > 0): ?>
                <?php $no = 1; ?>

                <?php foreach ($dataKategori as $kategori): ?>
                    <tr>
                        <td><?= $no++ ?></td>

                        <td><?= htmlspecialchars($kategori['kode_kategori']) ?></td>

                        <td><?= htmlspecialchars($kategori['nama_kategori']) ?></td>

                        <td><?= htmlspecialchars($kategori['deskripsi'] ?? '') ?></td>

                        <td>
                            <div class="action-buttons">
                                <a href="edit.php?id=<?= $kategori['id'] ?>"
                                   class="btn-edit">
                                    Edit
                                </a>

                                <a href="hapus.php?id=<?= $kategori['id'] ?>"
                                   class="btn-delete"
                                   onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                                    Hapus
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>

            <?php else: ?>
                <tr>
                    <td colspan="5" class="empty-data">
                        Belum ada data kategori.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../layout/footer.php'; ?>