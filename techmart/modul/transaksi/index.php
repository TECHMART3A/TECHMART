<?php
require_once '../../config/database.php';

$db = getDB();

$sql = "SELECT transaksi_stok.*,
        barang.kode_barang,
        barang.nama_barang,
        users.nama AS nama_user
        FROM transaksi_stok
        JOIN barang ON transaksi_stok.barang_id = barang.id
        JOIN users ON transaksi_stok.user_id = users.id
        ORDER BY transaksi_stok.tanggal DESC";

$dataTransaksi = $db->query($sql)->fetchAll();

require_once '../../layout/header.php';
?>

<div class="page-heading">
    <h2>Riwayat Transaksi Stok</h2>
    <p>Kelola dan pantau seluruh aktivitas stok produk TECHMART.</p>
</div>

<a href="tambah.php" class="btn-add">
    + Tambah Transaksi
</a>

<div class="table-container">
    <table class="transaction-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Transaksi</th>
                <th>Tanggal</th>
                <th>Produk</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Petugas</th>
                <th>Keterangan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            <?php if ($dataTransaksi): ?>
                <?php $no = 1; ?>

                <?php foreach ($dataTransaksi as $t): ?>
                    <tr>
                        <td><?= $no++ ?></td>

                        <td>
                            <?= htmlspecialchars($t['kode_transaksi']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($t['tanggal']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($t['kode_barang']) ?> -
                            <?= htmlspecialchars($t['nama_barang']) ?>
                        </td>

                        <td>
                            <?php if ($t['jenis'] === 'MASUK'): ?>
                                <span class="status-safe">MASUK</span>
                            <?php else: ?>
                                <span class="status-warning">KELUAR</span>
                            <?php endif; ?>
                        </td>

                        <td><?= $t['jumlah'] ?></td>

                        <td>
                            <?= htmlspecialchars($t['nama_user']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($t['keterangan'] ?? '') ?>
                        </td>

                        <td>
                            <?php if ($t['status'] === 'AKTIF'): ?>
                                <span class="status-safe">AKTIF</span>
                            <?php else: ?>
                                <span class="status-warning">BATAL</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if ($t['status'] === 'AKTIF'): ?>
                                <form method="POST"
                                      action="hapus.php"
                                      class="cancel-form"
                                      onsubmit="return confirm('Yakin ingin membatalkan transaksi ini?')">

                                    <input type="hidden"
                                           name="id"
                                           value="<?= $t['id'] ?>">

                                    <button type="submit" class="btn-cancel">
                                        Batalkan
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="cancelled-label">
                                    Sudah dibatalkan
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

            <?php else: ?>
                <tr>
                    <td colspan="10" class="empty-data">
                        Belum ada transaksi stok.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../layout/footer.php'; ?>