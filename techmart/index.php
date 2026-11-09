<?php
require_once 'config/database.php';

$db = getDB();

// =====================================
// FUNGSI KEAMANAN OUTPUT HTML
// =====================================
function esc($data) {
    return htmlspecialchars((string) $data, ENT_QUOTES, 'UTF-8');
}

// =====================================
// 1. MENGAMBIL DATA STATISTIK
// =====================================
$totalBarang = $db->query(
    "SELECT COUNT(*) FROM barang"
)->fetchColumn();

$totalStok = $db->query(
    "SELECT COALESCE(SUM(stok), 0) FROM barang"
)->fetchColumn();

$totalKategori = $db->query(
    "SELECT COUNT(*) FROM kategori"
)->fetchColumn();

$totalSupplier = $db->query(
    "SELECT COUNT(*) FROM supplier"
)->fetchColumn();

// =====================================
// 2. MENGAMBIL DATA KATEGORI
// =====================================
$stmtKategori = $db->query(
    "SELECT * FROM kategori ORDER BY nama_kategori ASC"
);

$daftarKategori = $stmtKategori->fetchAll(PDO::FETCH_ASSOC);

// =====================================
// 3. PENCARIAN DAN FILTER PRODUK
// =====================================
$keyword = trim($_GET['cari'] ?? '');
$kategoriFilter = $_GET['kategori'] ?? '';

$sql = "SELECT
            barang.*,
            kategori.nama_kategori
        FROM barang
        INNER JOIN kategori
            ON barang.kategori_id = kategori.id
        WHERE 1=1";

$params = [];

if ($keyword !== '') {
    $sql .= " AND (
        barang.nama_barang LIKE :nama
        OR barang.kode_barang LIKE :kode
        OR barang.merek LIKE :merek
    )";

    $params[':nama'] = "%$keyword%";
    $params[':kode'] = "%$keyword%";
    $params[':merek'] = "%$keyword%";
}

if (
    $kategoriFilter !== '' &&
    ctype_digit((string) $kategoriFilter)
) {
    $sql .= " AND barang.kategori_id = :kategori";
    $params[':kategori'] = (int) $kategoriFilter;
}

$sql .= " ORDER BY barang.nama_barang ASC";

$stmtProduk = $db->prepare($sql);
$stmtProduk->execute($params);

$daftarProduk = $stmtProduk->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TECHMART - Toko Elektronik</title>

    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        /* =====================================
           PENGATURAN DASAR
        ===================================== */
        body {
            background: #f3f6fa;
            color: #26364a;
        }

        /* =====================================
           HEADER
        ===================================== */
        .public-header {
            background: #142d4e;
            color: white;
            padding: 18px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .public-brand h1 {
            color: #ffffff;
            font-size: 26px;
            letter-spacing: 1px;
        }

        .public-brand p {
            font-size: 12px;
            color: #cbd5e1;
            margin-top: 4px;
        }

        .public-nav {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .public-nav a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }

        .public-nav .login-link {
            background: white;
            color: #142d4e;
            padding: 10px 18px;
            border-radius: 7px;
        }

        /* =====================================
           HERO / SELAMAT DATANG
        ===================================== */
        .hero {
            background: linear-gradient(120deg, #142d4e, #24558a);
            color: white;
            padding: 65px 7%;
        }

        .hero-content {
            max-width: 750px;
        }

        .hero h2 {
            color: #ffffff !important;
            font-size: 36px;
            line-height: 1.3;
            margin-bottom: 15px;
        }

        .hero p {
            color: #e2eaf4;
            line-height: 1.7;
            margin-bottom: 25px;
        }

        .hero-button {
            display: inline-block;
            background: white;
            color: #142d4e;
            text-decoration: none;
            padding: 12px 22px;
            border-radius: 7px;
            font-weight: bold;
        }

        .hero-button:hover {
            background: #e2eaf4;
        }

        /* =====================================
           CONTAINER
        ===================================== */
        .public-container {
            width: 86%;
            max-width: 1250px;
            margin: 35px auto;
        }

        /* =====================================
           JUDUL BAGIAN
        ===================================== */
        .section-title {
            margin-bottom: 20px;
        }

        .section-title h2 {
            color: #142d4e !important;
            font-size: 23px;
            margin-bottom: 7px;
        }

        .section-title p {
            color: #718096;
            font-size: 14px;
        }

        /* =====================================
           STATISTIK INVENTARIS
        ===================================== */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 23px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
            border-left: 4px solid #24558a;
        }

        .stat-card p {
            color: #718096;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .stat-card h3 {
            color: #142d4e !important;
            font-size: 27px;
        }

        /* =====================================
           PENCARIAN DAN FILTER
        ===================================== */
        .search-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }

        .search-box input,
        .search-box select {
            flex: 1;
            min-width: 180px;
            padding: 12px;
            border: 1px solid #d5deea;
            border-radius: 7px;
            font-size: 14px;
        }

        .search-box button {
            background: #142d4e;
            color: white;
            border: none;
            border-radius: 7px;
            padding: 12px 22px;
            cursor: pointer;
            font-weight: bold;
        }

        .search-box button:hover {
            background: #24558a;
        }

        .reset-link {
            display: flex;
            align-items: center;
            color: #24558a;
            text-decoration: none;
            font-size: 13px;
        }

        /* =====================================
           KATALOG PRODUK
        ===================================== */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .product-card {
            background: white;
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #e7edf4;
        }

        .product-image {
            width: 100%;
            height: 190px;
            object-fit: contain;
            background: #f8fafc;
            border-radius: 9px;
            margin-bottom: 16px;
            padding: 10px;
        }

        .product-image-placeholder {
            width: 100%;
            height: 190px;
            background: #f1f5f9;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            font-size: 14px;
            margin-bottom: 16px;
        }

        .product-category {
            display: inline-block;
            background: #eaf1f9;
            color: #24558a;
            padding: 6px 10px;
            border-radius: 5px;
            font-size: 11px;
            margin-bottom: 14px;
        }

        .product-card h3 {
            color: #142d4e !important;
            font-size: 18px;
            margin-bottom: 8px;
        }

        .product-code {
            font-size: 12px;
            color: #8a97a8;
            margin-bottom: 15px;
        }

        .product-price {
            color: #24558a;
            font-size: 19px;
            font-weight: bold;
            margin-bottom: 12px;
        }

        .product-stock {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 12px;
        }

        /* =====================================
           STATUS STOK
        ===================================== */
        .stock-badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 5px;
            font-size: 11px;
            font-weight: bold;
        }

        .stock-safe {
            background: #dcfce7;
            color: #166534;
        }

        .stock-low {
            background: #fee2e2;
            color: #b91c1c;
        }

        .stock-empty {
            background: #e5e7eb;
            color: #374151;
        }

        /* =====================================
           PRODUK TIDAK DITEMUKAN
        ===================================== */
        .empty-product {
            background: white;
            padding: 35px;
            border-radius: 10px;
            text-align: center;
            color: #718096;
            grid-column: 1 / -1;
        }

        .empty-product h3 {
            color: #142d4e;
            margin-bottom: 8px;
        }

        /* =====================================
           FOOTER
        ===================================== */
        .public-footer {
            background: #142d4e;
            color: white;
            text-align: center;
            padding: 25px 15px;
            margin-top: 50px;
        }

        .public-footer h3 {
            color: #ffffff;
        }

        .public-footer p {
            font-size: 13px;
            color: #cbd5e1;
            margin: 5px;
        }

        /* =====================================
           RESPONSIVE TABLET
        ===================================== */
        @media (max-width: 800px) {
            .stat-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .product-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .hero h2 {
                font-size: 29px;
            }
        }

        /* =====================================
           RESPONSIVE HP
        ===================================== */
        @media (max-width: 550px) {
            .public-header {
                padding: 18px 5%;
            }

            .public-nav {
                width: 100%;
                justify-content: space-between;
                gap: 10px;
            }

            .public-container {
                width: 92%;
            }

            .stat-grid,
            .product-grid {
                grid-template-columns: 1fr;
            }

            .hero {
                padding: 45px 6%;
            }

            .hero h2 {
                font-size: 27px;
            }

            .search-box {
                flex-direction: column;
            }

            .search-box input,
            .search-box select,
            .search-box button {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <!-- =====================================
         HEADER
    ===================================== -->
    <header class="public-header">

        <div class="public-brand">
            <h1>TECHMART</h1>
            <p>Your Needs, Our Technology.</p>
        </div>

        <nav class="public-nav">
            <a href="index.php">Beranda</a>
            <a href="#produk">Produk</a>
            <a href="auth/login.php" class="login-link">
                Portal Petugas
            </a>
        </nav>

    </header>

    <!-- =====================================
         HERO
    ===================================== -->
    <section class="hero">

        <div class="hero-content">

            <h2>Selamat Datang di TECHMART</h2>

            <p>
                Temukan berbagai kebutuhan elektronik berkualitas.
                TECHMART menyediakan informasi produk dan ketersediaan
                stok secara mudah dan praktis.
            </p>

            <a href="#produk" class="hero-button">
                Lihat Katalog Produk
            </a>

        </div>

    </section>

    <!-- =====================================
         STATISTIK DAN KATALOG
    ===================================== -->
    <div class="public-container">

        <!-- STATISTIK -->
        <div class="section-title">
            <h2>Informasi Inventaris</h2>
            <p>Ringkasan data inventaris produk TECHMART.</p>
        </div>

        <div class="stat-grid">

            <div class="stat-card">
                <p>Total Jenis Produk</p>
                <h3><?= number_format((int) $totalBarang) ?></h3>
            </div>

            <div class="stat-card">
                <p>Total Stok Produk</p>
                <h3><?= number_format((int) $totalStok) ?></h3>
            </div>

            <div class="stat-card">
                <p>Total Kategori</p>
                <h3><?= number_format((int) $totalKategori) ?></h3>
            </div>

            <div class="stat-card">
                <p>Total Supplier</p>
                <h3><?= number_format((int) $totalSupplier) ?></h3>
            </div>

        </div>

        <!-- KATALOG PRODUK -->
        <section id="produk">

            <div class="section-title">
                <h2>Katalog Produk</h2>
                <p>
                    Lihat produk elektronik yang tersedia di TECHMART.
                </p>
            </div>

            <!-- FORM PENCARIAN DAN FILTER -->
            <form method="GET" action="index.php" class="search-box">

                <input
                    type="text"
                    name="cari"
                    placeholder="Cari nama, kode, atau merek produk..."
                    value="<?= esc($keyword) ?>"
                >

                <select name="kategori">

                    <option value="">Semua Kategori</option>

                    <?php foreach ($daftarKategori as $kategori): ?>

                        <option
                            value="<?= (int) $kategori['id'] ?>"
                            <?= ((string) $kategoriFilter === (string) $kategori['id']) ? 'selected' : '' ?>
                        >
                            <?= esc($kategori['nama_kategori']) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

                <button type="submit">Cari Produk</button>

                <a href="index.php" class="reset-link">Reset</a>

            </form>

            <!-- DAFTAR PRODUK -->
            <div class="product-grid">

                <?php if (count($daftarProduk) > 0): ?>

                    <?php foreach ($daftarProduk as $produk): ?>

                        <div class="product-card">

                            <!-- FOTO PRODUK -->
                            <?php
                            $gambar = $produk['gambar'] ?? '';
                            $fileGambar = __DIR__ . '/' . $gambar;
                            ?>

                            <?php if (
                                !empty($gambar) &&
                                is_file($fileGambar)
                            ): ?>

                                <img
                                    src="<?= esc($gambar) ?>"
                                    alt="<?= esc($produk['nama_barang']) ?>"
                                    class="product-image"
                                >

                            <?php else: ?>

                                <div class="product-image-placeholder">
                                    Foto belum tersedia
                                </div>

                            <?php endif; ?>

                            <!-- INFORMASI PRODUK -->
                            <span class="product-category">
                                <?= esc($produk['nama_kategori']) ?>
                            </span>

                            <h3>
                                <?= esc($produk['nama_barang']) ?>
                            </h3>

                            <p class="product-code">
                                Kode: <?= esc($produk['kode_barang']) ?>

                                <?php if (!empty($produk['merek'])): ?>
                                    | <?= esc($produk['merek']) ?>
                                <?php endif; ?>
                            </p>

                            <div class="product-price">
                                Rp <?= number_format(
                                    (float) $produk['harga_jual'],
                                    0,
                                    ',',
                                    '.'
                                ) ?>
                            </div>

                            <p class="product-stock">
                                Stok tersedia:
                                <?= number_format((int) $produk['stok']) ?>
                            </p>

                            <!-- STATUS STOK -->
                            <?php if ((int) $produk['stok'] <= 0): ?>

                                <span class="stock-badge stock-empty">
                                    Habis
                                </span>

                            <?php elseif (
                                (int) $produk['stok'] <=
                                (int) $produk['stok_minimum']
                            ): ?>

                                <span class="stock-badge stock-low">
                                    Stok Kritis
                                </span>

                            <?php else: ?>

                                <span class="stock-badge stock-safe">
                                    Tersedia
                                </span>

                            <?php endif; ?>

                        </div>

                    <?php endforeach; ?>

                <?php else: ?>

                    <div class="empty-product">
                        <h3>Produk Tidak Ditemukan</h3>
                        <p>
                            Belum ada produk yang sesuai dengan
                            pencarian kamu.
                        </p>
                    </div>

                <?php endif; ?>

            </div>

        </section>

    </div>

    <!-- =====================================
         FOOTER
    ===================================== -->
    <footer class="public-footer">

        <h3>TECHMART</h3>

        <p>Your Needs, Our Technology.</p>

        <p>
            &copy; <?= date('Y') ?> TECHMART.
            All Rights Reserved.
        </p>

    </footer>

</body>
</html>