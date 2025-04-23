-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3000
-- Generation Time: Apr 23, 2025 at 02:11 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `distributor_beras`
--

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '2025_04_15_110107_create_tb_produsen', 2),
(3, '2025_04_15_110908_create_tb_beras', 2),
(4, '2025_04_15_111231_create_tb_pemesanan', 2),
(5, '2025_04_15_111605_create_tb_transaksi', 2);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('Aoe1i9cpSTpjRQQBkh7CloGEZ266NBpckXmkzJtE', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'YToyOntzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MDp7fX1zOjY6Il90b2tlbiI7czo0MDoicTZrTmdwVDlWelN6WERyNzRYUElZZ2V0M3F3SWRHVGQ1M1dIZWJzcyI7fQ==', 1745407311),
('hC9S11ZrOP0hfYM9jTUjjzdldfjY7icCZyLUrkku', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicGxUS2xjbnNNRFdxYk9QVUpUMzRjUjc0YXRUZ0FsTG9zVG5qZmlocCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtzOjE6IjEiO30=', 1745417247),
('jqJAb3jZ37yRYn8jvCcPX3msZ6RSwJ1WHKfd5WIg', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'YToyOntzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MDp7fX1zOjY6Il90b2tlbiI7czo0MDoiUE9NMjNmSnlMTWtaMEF3TDhOS0dOVGRuMkNjZFd3RE1jZFFrUVJxWiI7fQ==', 1745388831),
('q1GwXrtcoGmSYTp1JDru4S57VfnjIvoDFPAkSGqN', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'YToyOntzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MDp7fX1zOjY6Il90b2tlbiI7czo0MDoid005eHBxMGJpVFpsYmNuSGxHNjN6ZGdXS1BjeTM1eTdoM2tNeDJyUyI7fQ==', 1745337809);

-- --------------------------------------------------------

--
-- Table structure for table `tb_beras`
--

CREATE TABLE `tb_beras` (
  `id_beras` bigint UNSIGNED NOT NULL,
  `nama_beras` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_produsen` bigint UNSIGNED NOT NULL,
  `jenis_beras` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga_jual` int NOT NULL,
  `stok_awal` int NOT NULL,
  `stok_tersedia` int NOT NULL,
  `tgl_produksi` date NOT NULL,
  `tgl_kadaluarsa` date NOT NULL,
  `kualitas_beras` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sertifikat_beras` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_beras` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tb_beras`
--

INSERT INTO `tb_beras` (`id_beras`, `nama_beras`, `id_produsen`, `jenis_beras`, `harga_jual`, `stok_awal`, `stok_tersedia`, `tgl_produksi`, `tgl_kadaluarsa`, `kualitas_beras`, `sertifikat_beras`, `status_beras`, `created_at`, `updated_at`) VALUES
(1, 'Beras Putih ABC001', 2, 'D', 98000, 55, 55, '2025-04-21', '2026-04-21', 'Esse a repellendus', 'Nobis obcaecati anim', 'Tersedia', '2025-04-23 00:58:43', '2025-04-23 19:54:10'),
(3, 'Beras Merah  ABC003', 4, 'A', 88000, 60, 0, '2025-04-22', '2026-04-22', 'Hic magna velit labo', 'Similique culpa quod', 'Kosong', '2025-04-23 19:53:56', '2025-04-23 19:54:24');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pemesanan`
--

CREATE TABLE `tb_pemesanan` (
  `id_pemesanan` bigint UNSIGNED NOT NULL,
  `id_produsen` bigint UNSIGNED NOT NULL,
  `id_beras` bigint UNSIGNED NOT NULL,
  `jmlh` int NOT NULL,
  `tgl_pemesanan` date NOT NULL,
  `status_pesanan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_produsen`
--

CREATE TABLE `tb_produsen` (
  `id_produsen` bigint UNSIGNED NOT NULL,
  `nama_produsen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_beras` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga_beras` int NOT NULL,
  `jml_stok` int NOT NULL,
  `status_stok` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_pendaftaran` date NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tb_produsen`
--

INSERT INTO `tb_produsen` (`id_produsen`, `nama_produsen`, `alamat`, `no_telp`, `email`, `jenis_beras`, `harga_beras`, `jml_stok`, `status_stok`, `tgl_pendaftaran`, `created_at`, `updated_at`) VALUES
(1, 'Beras ABC000', 'Jl ABC No.099 Jayapura', '0812345678901', 'berasABC000@example.net', 'A', 98000, 20, 'Tersedia', '2025-04-17', '2025-04-17 23:24:17', '2025-04-20 03:25:27'),
(2, 'Beras ABC001', 'Jln ABCD No.098 Jayapura', '081234567891', 'berasABC001@example.net', 'C', 95000, 35, 'Tersedia', '2025-04-17', '2025-04-17 23:34:44', '2025-04-20 03:22:53'),
(3, 'Beras ABC002', 'Jln ABC No.03 Jayapura Selatan', '081234567892', 'BerasABC002@example.net', 'D', 90000, 0, 'Kosong', '2025-04-20', '2025-04-20 00:36:50', '2025-04-20 03:23:35'),
(4, 'Beras ABC003', 'Jln ABCD No.55 Jayapura Selatan', '0812345678902', 'berasABC003@gmail.com', 'B', 89000, 15, 'Tersedia', '2025-04-20', '2025-04-20 00:41:03', '2025-04-20 03:23:58'),
(5, 'Beras ABC004', 'Jln. ABCD No.01 Jayapura', '082345678904', 'berasABC004@example.org', 'C', 87000, 50, 'Tersedia', '2025-04-20', '2025-04-20 02:52:15', '2025-04-20 02:55:21');

-- --------------------------------------------------------

--
-- Table structure for table `tb_transaksi`
--

CREATE TABLE `tb_transaksi` (
  `id_transaksi` bigint UNSIGNED NOT NULL,
  `id_pemesanan` bigint UNSIGNED NOT NULL,
  `tgl_transaksi` date NOT NULL,
  `jmlh` int NOT NULL,
  `harga_satuan` int NOT NULL,
  `total_harga` int NOT NULL,
  `metode_pembayaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_pembayaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_pengiriman` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_pengiriman` date NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('Admin','Produsen','Pemilik') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Produsen',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `username`, `password`, `role`, `created_at`, `updated_at`) VALUES
('1', 'admin', 'admin', '$2y$12$kxINiU5NIwVYSaKLR8BNv.sDSSMkK2gTrhYiQM0BTGQlKKxsonTlO', 'Admin', '2025-04-11 13:15:41', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tb_beras`
--
ALTER TABLE `tb_beras`
  ADD PRIMARY KEY (`id_beras`),
  ADD UNIQUE KEY `tb_beras_nama_beras_unique` (`nama_beras`),
  ADD KEY `tb_beras_id_produsen_foreign` (`id_produsen`);

--
-- Indexes for table `tb_pemesanan`
--
ALTER TABLE `tb_pemesanan`
  ADD PRIMARY KEY (`id_pemesanan`),
  ADD KEY `tb_pemesanan_id_produsen_foreign` (`id_produsen`),
  ADD KEY `tb_pemesanan_id_beras_foreign` (`id_beras`);

--
-- Indexes for table `tb_produsen`
--
ALTER TABLE `tb_produsen`
  ADD PRIMARY KEY (`id_produsen`),
  ADD UNIQUE KEY `tb_produsen_nama_produsen_unique` (`nama_produsen`),
  ADD UNIQUE KEY `tb_produsen_email_unique` (`email`);

--
-- Indexes for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `tb_transaksi_id_pemesanan_foreign` (`id_pemesanan`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tb_beras`
--
ALTER TABLE `tb_beras`
  MODIFY `id_beras` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_pemesanan`
--
ALTER TABLE `tb_pemesanan`
  MODIFY `id_pemesanan` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_produsen`
--
ALTER TABLE `tb_produsen`
  MODIFY `id_produsen` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  MODIFY `id_transaksi` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_beras`
--
ALTER TABLE `tb_beras`
  ADD CONSTRAINT `tb_beras_id_produsen_foreign` FOREIGN KEY (`id_produsen`) REFERENCES `tb_produsen` (`id_produsen`) ON DELETE CASCADE;

--
-- Constraints for table `tb_pemesanan`
--
ALTER TABLE `tb_pemesanan`
  ADD CONSTRAINT `tb_pemesanan_id_beras_foreign` FOREIGN KEY (`id_beras`) REFERENCES `tb_beras` (`id_beras`) ON DELETE CASCADE,
  ADD CONSTRAINT `tb_pemesanan_id_produsen_foreign` FOREIGN KEY (`id_produsen`) REFERENCES `tb_produsen` (`id_produsen`) ON DELETE CASCADE;

--
-- Constraints for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD CONSTRAINT `tb_transaksi_id_pemesanan_foreign` FOREIGN KEY (`id_pemesanan`) REFERENCES `tb_pemesanan` (`id_pemesanan`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
