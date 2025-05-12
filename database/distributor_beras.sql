-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3000
-- Generation Time: May 12, 2025 at 04:26 PM
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
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '2025_04_15_110107_create_tb_produsen', 1),
(3, '2025_04_15_110908_create_tb_beras', 1),
(4, '2025_04_15_111231_create_tb_pemesanan', 1),
(5, '2025_04_15_111605_create_tb_transaksi', 1),
(6, '2025_04_26_231914_create_tb_gudang_table', 1),
(7, '2025_04_26_233515_drop_columns_from_tb_produsen_table', 1),
(8, '2025_04_29_191329_update_tb_produsen_table', 1),
(9, '2025_05_05_200700_rename_metode_pembayaran_to_bukti_bayar', 2),
(10, '2025_05_05_224322_make_tgl_transaksi_nullable', 3),
(11, '2025_05_06_001414_make_tgl_pengiriman_nullable', 4);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('igDM90OiA6QsUr464M4IDAdDWlgf4ctZkLrAnprg', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiemk1ZERXWW9PNTdHSlhQUTVacGc1eEpQT3BtbEl3SFE0akljT1ZKRSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9iZXJhcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtzOjE6IjEiO30=', 1747066994),
('x8q0KFkvPoRjrbXv1X0bxilhJdFUwqP5yNXgDfmx', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MDp7fX1zOjY6Il90b2tlbiI7czo0MDoib05VUHVLUzZ1UjNicUdqVEhkSTRCWmNCSWlUV1NxMm9yWXkwYmE0ciI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO3M6MToiMiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wZW1pbGlrL3RyYW5zYWtzaSI7fX0=', 1747066994);

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
(1, 'ABC', 1, 'Putih', 88000, 100, 100, '2025-05-02', '2026-05-02', 'Baik', '/storage/upload/sertifikat_beras/Produsen01/sertifikat-Beras Putih 01-05-05-2025.jpg', 'Tersedia', '2025-05-02 01:55:11', '2025-05-10 01:23:24'),
(3, 'Kelopak', 1, 'Hitam', 99000, 100, 80, '2025-05-02', '2026-05-02', 'Baik', '/storage/upload/sertifikat_beras/Produsen01/sertifikat-Beras Hitam 01-05-05-2025.jpg', 'Tersedia', '2025-05-03 00:21:24', '2025-05-10 01:24:27'),
(4, 'Hoki', 1, 'Merah', 90000, 100, 100, '2025-05-05', '2026-05-05', 'Baik', '	/storage/upload/sertifikat_beras/Produsen01/sertifikat-Beras Merah ABCD-05-05-2025.jpg', 'Tersedia', '2025-05-05 19:01:31', '2025-05-10 01:23:04'),
(5, 'Mahkota', 1, 'Putih', 98000, 100, 100, '2025-05-05', '2026-05-05', 'Baik', '	/storage/upload/sertifikat_beras/Produsen01/sertifikat-Beras Putih Mahkota-05-05-2025.jpg', 'Tersedia', '2025-05-05 19:16:09', '2025-05-10 01:23:39'),
(9, 'Dewa', 2, 'Putih', 93000, 250, 250, '2025-05-09', '2026-04-09', 'Baik', '/storage/upload/sertifikat_beras/Produsen02/sertifikat-Beras Putih Dewa-10-05-2025.jpg', 'Tersedia', '2025-05-10 00:44:32', '2025-05-10 00:54:42'),
(10, 'Tulip Premium', 2, 'Hitam', 95000, 300, 300, '2025-05-09', '2026-05-09', 'Baik', '/storage/upload/sertifikat_beras/Produsen02/sertifikat-Tulip Premium-10-05-2025.jpg', 'Tersedia', '2025-05-10 00:47:30', '2025-05-10 00:47:30'),
(11, 'Raja Premium', 3, 'Putih', 94000, 215, 215, '2025-05-04', '2026-06-04', 'Baik', '/storage/upload/sertifikat_beras/Produsen 03/sertifikat-Raja Premium-10-05-2025.jpg', 'Tersedia', '2025-05-10 03:11:14', '2025-05-10 03:11:14'),
(12, 'Naga', 3, 'Ketan', 96000, 256, 256, '2025-05-06', '2026-05-06', 'Baik', '/storage/upload/sertifikat_beras/Produsen 03/sertifikat-Naga-10-05-2025.jpg', 'Tersedia', '2025-05-10 03:13:14', '2025-05-10 03:13:14');

-- --------------------------------------------------------

--
-- Table structure for table `tb_gudang`
--

CREATE TABLE `tb_gudang` (
  `id_gudang` bigint UNSIGNED NOT NULL,
  `id_beras` bigint UNSIGNED NOT NULL,
  `id_produsen` bigint UNSIGNED NOT NULL,
  `stok_awal` int NOT NULL DEFAULT '0',
  `rusak` int NOT NULL DEFAULT '0',
  `hilang` int NOT NULL DEFAULT '0',
  `stok_sisa` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tb_gudang`
--

INSERT INTO `tb_gudang` (`id_gudang`, `id_beras`, `id_produsen`, `stok_awal`, `rusak`, `hilang`, `stok_sisa`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 90, 5, 1, 84, '2025-05-02 17:46:58', '2025-05-05 09:04:44'),
(3, 3, 1, 100, 15, 0, 85, '2025-05-05 08:38:32', '2025-05-05 09:05:01'),
(4, 4, 1, 80, 5, 0, 25, '2025-05-06 06:41:15', '2025-05-06 06:41:15'),
(5, 11, 3, 100, 2, 0, 98, '2025-05-11 10:08:02', '2025-05-11 10:08:02');

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

--
-- Dumping data for table `tb_pemesanan`
--

INSERT INTO `tb_pemesanan` (`id_pemesanan`, `id_produsen`, `id_beras`, `jmlh`, `tgl_pemesanan`, `status_pesanan`, `catatan`, `created_at`, `updated_at`) VALUES
(2, 1, 4, 80, '2025-05-05', 'Telah Dikonfirmasi', 'Stok di gudang menipis', '2025-05-05 21:35:48', '2025-05-06 00:34:17'),
(3, 2, 10, 100, '2025-05-11', 'Telah Dikonfirmasi', '-', '2025-05-11 17:35:51', '2025-05-11 17:36:31'),
(4, 3, 12, 100, '2025-05-11', 'Telah Dikonfirmasi', '-', '2025-05-11 17:43:36', '2025-05-11 17:45:20');

-- --------------------------------------------------------

--
-- Table structure for table `tb_produsen`
--

CREATE TABLE `tb_produsen` (
  `id_produsen` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nama_produsen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_pendaftaran` date DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tb_produsen`
--

INSERT INTO `tb_produsen` (`id_produsen`, `user_id`, `nama_produsen`, `alamat`, `no_telp`, `email`, `tgl_pendaftaran`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 'Produsen01', 'Jl. ABC No. 01, Kota Jayapura', '081234567801', 'Produsen01@example.net', '2025-04-30', 1, '2025-04-30 00:34:31', '2025-04-30 23:58:12'),
(2, 4, 'Produsen02', 'Jl. ABC No. 02, Kota Jayapura', '081234567802', 'Produsen02@example.net', '2025-04-30', 1, '2025-04-30 00:36:21', '2025-05-10 00:15:52'),
(3, 5, 'Produsen03', 'Jl. ABC No. 15, Kota Jayapura', '081234567893', 'Produsen03@example.net', '2025-05-10', 1, '2025-05-10 00:14:19', '2025-05-10 00:17:38');

-- --------------------------------------------------------

--
-- Table structure for table `tb_transaksi`
--

CREATE TABLE `tb_transaksi` (
  `id_transaksi` bigint UNSIGNED NOT NULL,
  `id_pemesanan` bigint UNSIGNED NOT NULL,
  `tgl_transaksi` date DEFAULT NULL,
  `jmlh` int NOT NULL,
  `harga_satuan` int NOT NULL,
  `total_harga` int NOT NULL,
  `bukti_bayar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_pembayaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_pengiriman` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_pengiriman` date DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tb_transaksi`
--

INSERT INTO `tb_transaksi` (`id_transaksi`, `id_pemesanan`, `tgl_transaksi`, `jmlh`, `harga_satuan`, `total_harga`, `bukti_bayar`, `status_pembayaran`, `status_pengiriman`, `tgl_pengiriman`, `catatan`, `created_at`, `updated_at`) VALUES
(1, 2, '2025-05-06', 80, 90000, 7200000, '/storage/upload/bukti_bayar_/06-05-2025/1-06-05-2025-06-05-2025.jpg', 'Selesai', 'Dijadwalkan', '2025-05-07', '-', '2025-05-06 00:34:16', '2025-05-07 00:43:43'),
(2, 3, '2025-05-11', 100, 95000, 9500000, '/storage/upload/bukti_bayar_/11-05-2025/2-11-05-2025-11-05-2025.jpg', 'Pending', 'Pending', NULL, '-', '2025-05-11 17:36:30', '2025-05-11 17:42:02'),
(3, 4, NULL, 100, 96000, 9600000, NULL, 'Pending', 'Pending', NULL, '-', '2025-05-11 17:45:20', '2025-05-11 17:45:20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('Admin','Produsen','Pemilik') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `username`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', '$2y$12$VrjehOJkzDYqtv/aHiCxD.I7GJrnr44MZ.GG/kBbMiH0ZH7yzaOEW', 'Admin', '2025-04-29 16:32:51', NULL),
(2, 'pemilik', 'pemilik', '$2y$12$UUHA9jjmiZVSO6aFOleW4.R1V/UjecfmOui9Ev5GcoERKQ/33Ql/y', 'Pemilik', '2025-04-29 16:32:52', NULL),
(3, 'Produsen01', 'Produsen01', '$2y$12$HKhAZCoWWoFf7g//J3lMru2BGdd1tJbttLLduFGd/chpoYZSiuCDK', 'Produsen', '2025-04-29 16:34:31', '2025-04-29 16:34:31'),
(4, 'Produsen02', 'Produsen02', '$2y$12$RIflLIvB8DK5IMVoaojPNO66mDFFDrEhCr/C5kQOFKEQodg6Rylru', 'Produsen', '2025-04-29 16:36:21', '2025-04-29 16:36:21'),
(5, 'Produsen 03', 'Produsen03', '$2y$12$W96pKuyh5SWiba12m3VXwu49B10AxDyecqvu0XnZmx8jl4i.12W/.', 'Produsen', '2025-05-09 16:14:19', '2025-05-09 16:14:19');

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
-- Indexes for table `tb_gudang`
--
ALTER TABLE `tb_gudang`
  ADD PRIMARY KEY (`id_gudang`),
  ADD KEY `tb_gudang_id_beras_foreign` (`id_beras`),
  ADD KEY `tb_gudang_id_produsen_foreign` (`id_produsen`);

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
  ADD UNIQUE KEY `tb_produsen_email_unique` (`email`),
  ADD KEY `tb_produsen_user_id_foreign` (`user_id`);

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tb_beras`
--
ALTER TABLE `tb_beras`
  MODIFY `id_beras` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tb_gudang`
--
ALTER TABLE `tb_gudang`
  MODIFY `id_gudang` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tb_pemesanan`
--
ALTER TABLE `tb_pemesanan`
  MODIFY `id_pemesanan` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tb_produsen`
--
ALTER TABLE `tb_produsen`
  MODIFY `id_produsen` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  MODIFY `id_transaksi` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_beras`
--
ALTER TABLE `tb_beras`
  ADD CONSTRAINT `tb_beras_id_produsen_foreign` FOREIGN KEY (`id_produsen`) REFERENCES `tb_produsen` (`id_produsen`) ON DELETE CASCADE;

--
-- Constraints for table `tb_gudang`
--
ALTER TABLE `tb_gudang`
  ADD CONSTRAINT `tb_gudang_id_beras_foreign` FOREIGN KEY (`id_beras`) REFERENCES `tb_beras` (`id_beras`) ON DELETE CASCADE,
  ADD CONSTRAINT `tb_gudang_id_produsen_foreign` FOREIGN KEY (`id_produsen`) REFERENCES `tb_produsen` (`id_produsen`) ON DELETE CASCADE;

--
-- Constraints for table `tb_pemesanan`
--
ALTER TABLE `tb_pemesanan`
  ADD CONSTRAINT `tb_pemesanan_id_beras_foreign` FOREIGN KEY (`id_beras`) REFERENCES `tb_beras` (`id_beras`) ON DELETE CASCADE,
  ADD CONSTRAINT `tb_pemesanan_id_produsen_foreign` FOREIGN KEY (`id_produsen`) REFERENCES `tb_produsen` (`id_produsen`) ON DELETE CASCADE;

--
-- Constraints for table `tb_produsen`
--
ALTER TABLE `tb_produsen`
  ADD CONSTRAINT `tb_produsen_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD CONSTRAINT `tb_transaksi_id_pemesanan_foreign` FOREIGN KEY (`id_pemesanan`) REFERENCES `tb_pemesanan` (`id_pemesanan`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
