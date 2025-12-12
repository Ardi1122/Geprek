-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 12, 2025 at 01:47 PM
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
-- Database: `db_geprek`
--

-- --------------------------------------------------------

--
-- Table structure for table `bahan_baku`
--

CREATE TABLE `bahan_baku` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `stok` decimal(10,2) NOT NULL,
  `satuan` varchar(20) NOT NULL,
  `harga_beli` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bahan_baku`
--

INSERT INTO `bahan_baku` (`id`, `nama`, `stok`, `satuan`, `harga_beli`, `updated_at`, `created_at`) VALUES
(5, 'Ayam Fillet', 3.40, 'kg', 50000, '2025-12-12 12:27:46', '2025-12-12 12:27:46'),
(6, 'Sambal', 760.00, 'gram', 15000, '2025-12-12 12:27:46', '2025-12-12 12:27:46'),
(7, 'Beras', 1.95, 'kg', 15000, '2025-12-12 12:27:46', '2025-12-12 12:27:46'),
(8, 'Air', 3.75, 'liter', 5000, '2025-12-12 12:27:46', '2025-12-12 12:27:46'),
(9, 'Teh', 0.90, 'kg', 30000, '2025-12-12 12:27:46', '2025-12-12 12:27:46'),
(10, 'Es Batu', 0.50, 'kg', 7000, '2025-12-12 12:27:46', '2025-12-12 12:27:46');

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id` int(11) NOT NULL,
  `transaksi_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_satuan` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id`, `transaksi_id`, `menu_id`, `jumlah`, `harga_satuan`, `subtotal`) VALUES
(10, 8, 3, 2, 15000, 30000),
(11, 9, 3, 3, 15000, 45000),
(12, 9, 5, 3, 5000, 15000),
(13, 10, 3, 2, 15000, 30000),
(14, 10, 5, 2, 5000, 10000);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `kategori` enum('makanan','minuman','cemilan') NOT NULL,
  `harga` int(11) NOT NULL,
  `deskripsi` text NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `status` enum('tersedia','habis') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `nama`, `kategori`, `harga`, `deskripsi`, `gambar`, `status`, `created_at`) VALUES
(3, 'Ayam Geprek', 'makanan', 15000, 'Ayam geprek dengan sambal bawang standar, cocok untuk semua selera', '693bde6836be7.jpg', 'tersedia', '2025-12-12 09:20:40'),
(4, 'Ayam Geprek Sambal Ijo', 'makanan', 15000, 'Ayam geprek dengan sambal ijo khas pedas segar.', '693bdee78202c.jpg', 'tersedia', '2025-12-12 09:22:47'),
(5, 'Es Teh', 'minuman', 5000, 'Minuman es teh manis segar.', '693bdf4267970.jpg', 'tersedia', '2025-12-12 09:24:18'),
(6, 'Kentang Goreng', 'cemilan', 8000, 'Kentang goreng renyah cocok sebagai pendamping ayam geprek.', '693bdfa510aab.jpg', 'tersedia', '2025-12-12 09:25:57'),
(7, 'Ayam Geprek Mozzarella', 'makanan', 15000, 'tes', '693c09fa41899.jpg', 'tersedia', '2025-12-12 12:26:34');

-- --------------------------------------------------------

--
-- Table structure for table `menu_bahan`
--

CREATE TABLE `menu_bahan` (
  `id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `bahan_id` int(11) NOT NULL,
  `jumlah_pakai` decimal(10,2) NOT NULL,
  `satuan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_bahan`
--

INSERT INTO `menu_bahan` (`id`, `menu_id`, `bahan_id`, `jumlah_pakai`, `satuan`) VALUES
(5, 3, 6, 20.00, 1),
(6, 3, 7, 0.15, 1),
(7, 3, 5, 0.12, 1),
(8, 5, 8, 0.25, 1),
(9, 5, 10, 0.10, 1),
(10, 5, 9, 0.02, 1);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_bayar` int(11) NOT NULL,
  `uang_bayar` int(11) NOT NULL,
  `kembalian` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `user_id`, `total_bayar`, `uang_bayar`, `kembalian`, `created_at`) VALUES
(8, 6, 30000, 30000, 0, '2025-12-12 09:47:10'),
(9, 11, 60000, 100000, 40000, '2025-12-12 10:23:46'),
(10, 6, 40000, 100000, 60000, '2025-12-12 12:27:46');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('kasir','pemilik') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `nama`, `password`, `role`) VALUES
(6, 'admin', '$2y$10$D9M6DW2mN5OcsFG6Lyoq7OLn7Yq7zyH87zuYd83tDIe1JfxsukL3a', 'pemilik'),
(11, 'Ardi', '$2y$10$it0gjm/DgtULvTeekNQEFeIDnldCODQGhq9P2dGpk3pR0jmJDVPPm', 'kasir'),
(12, 'Fauzan', '$2y$10$oFIitVWz1ebeh84XeV8jEO45uR2Py0WB4JXZtLgB9sjFPr4dNRauu', 'kasir'),
(13, 'Ade', '$2y$10$mUD3.GLEEautTgufHWSNquqSkEF/PH.XxNExYHD7GXgRT655lGQja', 'kasir'),
(14, 'Aulia', '$2y$10$wikCFZ31lrHlIVRlzZ0ocOBU2bmIWomFzrRE8RvKwOHYkSzbWIWJO', 'kasir');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bahan_baku`
--
ALTER TABLE `bahan_baku`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaksi_id` (`transaksi_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_bahan`
--
ALTER TABLE `menu_bahan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_id` (`menu_id`),
  ADD KEY `bahan_id` (`bahan_id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bahan_baku`
--
ALTER TABLE `bahan_baku`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `menu_bahan`
--
ALTER TABLE `menu_bahan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `menu_bahan`
--
ALTER TABLE `menu_bahan`
  ADD CONSTRAINT `menu_bahan_ibfk_1` FOREIGN KEY (`bahan_id`) REFERENCES `bahan_baku` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `menu_bahan_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
