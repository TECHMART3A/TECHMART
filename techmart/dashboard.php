<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}

require_once 'config/database.php';
$db = getDB();

// Menghitung jumlah data
$totalKategori = $db->query(
    "SELECT COUNT(*) FROM kategori"
)->fetchColumn();

$totalProduk = $db->query(
    "SELECT COUNT(*) FROM barang"
)->fetchColumn();

$totalSupplier = $db->query(
    "SELECT COUNT(*) FROM supplier"
)->fetchColumn();

$totalTransaksi = $db->query(
    "SELECT COUNT(*) FROM transaksi_stok"
)->fetchColumn();

// Mengambil produk dengan stok menipis
$stmt = $db->query(
    "SELECT kode_barang, nama_barang, stok, stok_minimum
     FROM barang
     WHERE stok <= stok_minimum
     ORDER BY stok ASC"
);

$stokMenipis = $stmt->fetchAll();

require_once 'layout/header.php';
?>

<div class="dashboard-heading">
    <div>
        <h2>Dashboard</h2>
        <p>Ringkasan informasi inventaris TECHMART.</p>
    </div>
</div>

<div class="welcome-box">
    <h3>Halo, <?= htmlspecialchars($_SESSION['nama']) ?>!</h3>
    <p>Selamat datang di Sistem Manajemen Inventaris TECHMART.</p>
</div>

<div class="dashboard-cards">

    <div class="stat-card">
        <div class="stat-icon">▤</div>
        <div>
            <p>Total Kategori</p>
            <h2><?= $totalKategori ?></h2>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">▣</div>
        <div>
            <p>Total Produk</p>
            <h2><?= $totalProduk ?></h2>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">♙</div>
        <div>
            <p>Total Supplier</p>
            <h2><?= $totalSupplier ?></h2>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">⇄</div>
        <div>
            <p>Total Transaksi</p>
            <h2><?= $totalTransaksi ?></h2>
        </div>
    </div>

</div>

<div class="dashboard-section">
    <h3>Perhatian Stok Produk</h3>
    <p>Daftar produk yang stoknya sudah mencapai batas minimum.</p>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Stok Saat Ini</th>
                    <th>Stok Minimum</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($stokMenipis): ?>
                    <?php $no = 1; ?>
                    <?php foreach ($stokMenipis as $b): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($b['kode_barang']) ?></td>
                            <td><?= htmlspecialchars($b['nama_barang']) ?></td>
                            <td><?= $b['stok'] ?></td>
                            <td><?= $b['stok_minimum'] ?></td>
                            <td>
                                <span class="status-warning">
                                    Stok Menipis
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="empty-data">
                            Semua stok produk dalam kondisi aman.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'layout/footer.php'; ?>