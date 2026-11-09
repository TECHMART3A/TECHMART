-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 07, 2026 at 06:16 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `techmart_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id` int(11) NOT NULL,
  `kode_barang` varchar(30) NOT NULL,
  `nama_barang` varchar(150) NOT NULL,
  `merek` varchar(100) DEFAULT NULL,
  `kategori_id` int(11) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `harga_beli` decimal(15,2) NOT NULL DEFAULT 0.00,
  `harga_jual` decimal(15,2) NOT NULL DEFAULT 0.00,
  `stok` int(11) NOT NULL DEFAULT 0,
  `stok_minimum` int(11) NOT NULL DEFAULT 0,
  `deskripsi` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id`, `kode_barang`, `nama_barang`, `merek`, `kategori_id`, `supplier_id`, `harga_beli`, `harga_jual`, `stok`, `stok_minimum`, `deskripsi`, `gambar`, `created_at`, `updated_at`) VALUES
(1, 'HP001', 'Samsung galaxy A56 5G', 'Samsung', 3, 1, 4600000.00, 5300000.00, 10, 4, 'Smartphone Samsung Galaxy A56 5G', 'assets/img/produk/3e9bc5ebc1e2391fc7abcbe2af3aba31.jpg', '2026-11-05 06:13:48', '2026-11-07 02:43:45'),
(3, 'TV001', 'TV Polytron 24 inch', 'Polytron', 4, 3, 5000000.00, 5900000.00, 4, 5, 'Produk Televisi Polytron 24 inch', 'assets/img/produk/a699c020e4bc079f190ae9c421c8d26d.jpg', '2026-11-06 05:27:11', '2026-11-07 02:46:01'),
(4, 'LP001', 'Laptop Samsung', 'Samsung', 3, 3, 12000000.00, 15000000.00, 5, 10, 'Laptop Samsung Ativ Book 9 Litte', 'assets/img/produk/3fd5685201398b35663bf2ce96fe2951.jpg', '2026-11-06 06:55:18', '2026-11-06 06:55:18'),
(5, 'AC001', 'AC AQA-KCR19VSW', 'AQUA', 7, 4, 13900000.00, 14500000.00, 9, 5, 'Produk AC AQA-KCR19VSW', 'assets/img/produk/f9351e87a0b38b964073823b6a6c3076.jpg', '2026-11-07 02:55:25', '2026-11-07 03:06:07'),
(6, 'MC001', 'Mesin cuci Sanken TW-927HGY', 'Sanken', 7, 9, 2990000.00, 3500000.00, 10, 6, 'Produk Sanken TW-927HGY', 'assets/img/produk/346c217fb9bec4f7258e96b8c35c19fa.jpg', '2026-11-07 03:16:23', '2026-11-07 03:28:22'),
(7, 'KS-001', 'Kulkas Side By Side Polytron New Belleza Inverter 472 Liter - PRS 480X', 'Polytron', 7, 8, 8185000.00, 10192000.00, 11, 5, 'Produk  Kulkas Side By Side Polytron New Belleza Inverter 472 Liter - PRS 480X', 'assets/img/produk/f15e56bd53eeea8e2ee29d79a114b0b9.jpg', '2026-11-07 03:22:00', '2026-11-07 03:22:00'),
(8, 'KC-001', 'Kamera Canon EOS 3000D Kit 18-55mm', 'Canon', 10, 11, 4999000.00, 5893000.00, 5, 6, 'Produk Kamera Canon EOS 3000D Kit 18-55mm', 'assets/img/produk/f1f14e3dddc380d7c39ca32095df53e2.jpg', '2026-11-07 03:26:28', '2026-11-07 03:26:28'),
(9, 'CCTV-001', 'Kris Kamera CCTV Bullet 2Mp', 'Kris', 11, 7, 650000.00, 799000.00, 8, 4, 'Produk  Kris Kamera CCTV Bullet 2Mp', 'assets/img/produk/48e246f229e58ea5dd0b9c4f769669ef.jpg', '2026-11-07 03:35:25', '2026-11-07 03:35:25'),
(10, 'PB-001', 'Printer Brother Colour Multifunction Laser MFC', 'Brother', 12, 5, 32850000.00, 40000000.00, 4, 5, 'Produk Printer Brother Colour Multifunction Laser MFC', 'assets/img/produk/4f41053d0e076fd42fb83b6883018ccc.jpg', '2026-11-07 03:39:35', '2026-11-07 03:39:35'),
(11, 'RC-001', 'Rice Cooker Miyako MCM 528', 'Miyako', 7, 1, 3000000.00, 3800000.00, 8, 6, 'Produk Rice Cooker Miyako MCM 528', 'assets/img/produk/26065f12fd3b83c86e010d2efdf02cab.jpg', '2026-11-07 03:42:22', '2026-11-07 04:00:43'),
(12, 'VC-001', 'Vacuum Cleaner 60L Krisbow', 'Krisbow', 9, NULL, 15000000.00, 10000000.00, 6, 4, 'Produk Vacuum Cleaner 60L Krisbow', 'assets/img/produk/6157e7383f9a45710445bdf028ff9269.jpg', '2026-11-07 03:48:12', '2026-11-07 03:48:12'),
(13, 'AA-001', 'Audio Speaker Acapella.', 'AcapellAudio Arts', 6, 1, 5500000.00, 5000000.00, 9, 7, 'Produk Audio Speaker Acapella.', 'assets/img/produk/3ddd48f56aff9ebff2a002bcf36e7c05.jpg', '2026-11-07 03:55:23', '2026-11-07 03:55:23'),
(14, 'RP-001', 'Rak Piring Milano Dish', 'Milano', 8, 7, 217000.00, 310000.00, 4, 3, 'Produk Rak Piring', 'assets/img/produk/ebc3fc1201a29c857fcde30ef9b6a192.jpg', '2026-11-07 03:59:49', '2026-11-07 03:59:49'),
(15, 'KG-001', 'kompor gas 2 tungku rinnai ri-522c', 'Rinnai', 8, 1, 379000.00, 350000.00, 7, 6, 'Produk Kompor gas rinnai', 'assets/img/produk/838255813f882d2b40d51dedd6a2c2f0.jpg', '2026-11-07 04:04:19', '2026-11-07 04:04:19');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `kode_kategori` varchar(20) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `kode_kategori`, `nama_kategori`, `deskripsi`, `created_at`) VALUES
(3, 'KAT-HP', 'Smartphone & Tablet', 'Kategori smartphone dan tablet', '2026-11-05 06:10:29'),
(4, 'KAT-TV', 'Televisi & Proyektor', 'kategori televisi dan proyektor', '2026-11-06 05:24:01'),
(5, 'KAT-KL', 'Komputer & Laptop', 'Kategori komputer dan laptop', '2026-11-07 00:38:58'),
(6, 'KAT-AM', 'Audio & Musik', 'Kategori audio dan musik', '2026-11-07 00:43:24'),
(7, 'KAT-RT', 'Peralatan Rumah Tangga', 'Kategori peralatan rumah tangga', '2026-11-07 00:47:41'),
(8, 'KAT-DR', 'Peralatan Dapur', 'Kategori peralatan dapur', '2026-11-07 00:49:04'),
(9, 'KAT-BER', 'Perawatan Rumah', 'Kategori perawatan rumah', '2026-11-07 00:50:10'),
(10, 'KAT-FT', 'Kamera', 'Kategori kamera', '2026-11-07 00:51:09'),
(11, 'KAT-CT', 'Keamanan', 'Kategori keamanan', '2026-11-07 00:52:02'),
(12, 'KAT-TR', 'Mesin Kantor', 'Kategori mesin kantor', '2026-11-07 00:53:29');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` int(11) NOT NULL,
  `kode_supplier` varchar(20) NOT NULL,
  `nama_supplier` varchar(150) NOT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id`, `kode_supplier`, `nama_supplier`, `telepon`, `email`, `alamat`, `created_at`) VALUES
(1, 'SUP001', 'PT.Sumber Teknologi Indonesia', '081298765432', 'Suplier@tecmart.test', 'Jakata Selatan,Indonesia', '2026-11-05 06:24:21'),
(3, 'SUP002', 'PT.LISA BERKAH', '089123957684', 'lisaberkah@gmail.com', 'Cirebon, Jawa Barat, Indonesia', '2026-11-06 05:28:26'),
(4, 'SUP003', 'Gadget Zone', '081234567801', 'gadgedzone@gmail.com', 'Tangerang,Indonesia', '2026-11-07 01:03:28'),
(5, 'SUP004', 'Pusat Elektronik', '081234567802', 'pusatelektronik@gmail.com', 'Bekasi,Indonesia', '2026-11-07 01:06:26'),
(6, 'SUP005', 'PT. TAYS Elektro', '081234567803', 'tayselektro@gmail.com', 'Cirebon, Indonesia', '2026-11-07 01:11:14'),
(7, 'SUP006', 'TikaMart', '081234567804', 'tikamart@gmail.com', 'Bali, Indonesia', '2026-11-07 01:29:04'),
(8, 'SUP007', 'Gudang April Elektronik', '081234567805', 'gudangaprilelektronik@gmail.com', 'Semarang, Indonesia', '2026-11-07 02:17:34'),
(9, 'SUP008', 'Yulia Group', '081234567806', 'yuliagroup@gmail.com', 'Jakarta Selatan, Indonesia', '2026-11-07 02:18:37'),
(10, 'SUP009', 'PT. Sinar Abadi', '081234567807', 'sinarabadi@gmail.com', 'Demak, Indonesia', '2026-11-07 02:19:56'),
(11, 'SUP010', 'Point Electronics', '081234567808', 'pointelectronics@gmail.com', 'Jakarta, Indonesia', '2026-11-07 02:20:57');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_stok`
--

CREATE TABLE `transaksi_stok` (
  `id` int(11) NOT NULL,
  `kode_transaksi` varchar(30) NOT NULL,
  `barang_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `jenis` enum('MASUK','KELUAR') NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_satuan` decimal(15,2) DEFAULT 0.00,
  `tanggal` datetime DEFAULT current_timestamp(),
  `keterangan` text DEFAULT NULL,
  `status` enum('AKTIF','BATAL') DEFAULT 'AKTIF'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi_stok`
--

INSERT INTO `transaksi_stok` (`id`, `kode_transaksi`, `barang_id`, `user_id`, `jenis`, `jumlah`, `harga_satuan`, `tanggal`, `keterangan`, `status`) VALUES
(1, 'TRX-20261105073041-367', 1, 1, 'MASUK', 5, 0.00, '2026-11-05 13:30:41', 'Pembelian Stok Tambahan', 'BATAL'),
(2, 'TRX-20261105073155-933', 1, 1, 'KELUAR', 3, 0.00, '2026-11-05 13:31:55', 'Pengeluaran Barang untuk Penjualan', 'BATAL'),
(3, 'TRX-20261106063629-713', 3, 1, 'KELUAR', 7, 0.00, '2026-11-06 12:36:29', 'pengeluaran untuk penjualan', 'AKTIF');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','petugas') DEFAULT 'petugas',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'Administrator TECHMART', 'admin', '$2y$10$o9yLUQFEt0zjzbql3q/T3.mpZrQvzaUDgLRQG7FLhXiykvvBhiBMK', 'admin', '2026-11-05 04:27:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_barang` (`kode_barang`),
  ADD KEY `fk_barang_kategori` (`kategori_id`),
  ADD KEY `fk_barang_supplier` (`supplier_id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_kategori` (`kode_kategori`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_supplier` (`kode_supplier`);

--
-- Indexes for table `transaksi_stok`
--
ALTER TABLE `transaksi_stok`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_transaksi` (`kode_transaksi`),
  ADD KEY `fk_transaksi_barang` (`barang_id`),
  ADD KEY `fk_transaksi_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `transaksi_stok`
--
ALTER TABLE `transaksi_stok`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `fk_barang_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_barang_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `transaksi_stok`
--
ALTER TABLE `transaksi_stok`
  ADD CONSTRAINT `fk_transaksi_barang` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transaksi_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
