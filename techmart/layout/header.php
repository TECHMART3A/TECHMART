<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$baseUrl = '/techmart';

if (!isset($_SESSION['user_id'])) {
    header("Location: $baseUrl/auth/login.php");
    exit;
}

$halaman = $_SERVER['PHP_SELF'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TECHMART - Sistem Inventaris</title>

    <link rel="stylesheet"
          href="<?= $baseUrl ?>/assets/css/style.css">
</head>
<body>

<div class="app-container">

    <aside class="sidebar">
        <div class="brand">
            <h2>TECHMART</h2>
            <p>Your Needs, Our Technology.</p>
        </div>

        <div class="menu-title">MENU UTAMA</div>

        <nav class="sidebar-menu">
            <a href="<?= $baseUrl ?>/dashboard.php"
               class="<?= str_contains($halaman, 'dashboard.php') ? 'active' : '' ?>">
                <span>▦</span> Dashboard
            </a>

            <a href="<?= $baseUrl ?>/modul/kategori/index.php"
               class="<?= str_contains($halaman, '/kategori/') ? 'active' : '' ?>">
                <span>▤</span> Kategori
            </a>

            <a href="<?= $baseUrl ?>/modul/barang/index.php"
               class="<?= str_contains($halaman, '/barang/') ? 'active' : '' ?>">
                <span>▣</span> Produk
            </a>

            <a href="<?= $baseUrl ?>/modul/supplier/index.php"
               class="<?= str_contains($halaman, '/supplier/') ? 'active' : '' ?>">
                <span>♙</span> Supplier
            </a>

            <a href="<?= $baseUrl ?>/modul/transaksi/index.php"
               class="<?= str_contains($halaman, '/transaksi/') ? 'active' : '' ?>">
                <span>⇄</span> Transaksi Stok
            </a>
        </nav>

        <div class="sidebar-bottom">
            <p>Login sebagai</p>
            <strong><?= htmlspecialchars($_SESSION['nama']) ?></strong>
            <a href="<?= $baseUrl ?>/auth/logout.php">↪ Logout</a>
        </div>
    </aside>

    <div class="main-area">

        <div class="topbar">
            <button class="menu-toggle" id="menuToggle" type="button">
                ☰
            </button>
            
            <div>
                <h3>Sistem Manajemen Inventaris</h3>
                <p>Selamat datang di TECHMART</p>
            </div>
            <div class="admin-label">
                Administrator
            </div>
        </div>

        <main>