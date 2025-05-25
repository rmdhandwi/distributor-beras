-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3000
-- Generation Time: May 25, 2025 at 06:46 PM
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
(11, '2025_05_06_001414_make_tgl_pengiriman_nullable', 4),
(16, '2025_05_20_183427_create_tb_detail_beras', 5),
(17, '2025_05_22_190149_create_tb_detail_pemesanan', 6),
(18, '2025_05_23_170605_create_tb_detail_gudang', 6);

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
('9kK3tXazVqLQUgGF8Wu0VsV5qJnCGmBIrm3WhWkO', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YToyOntzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MDp7fX1zOjY6Il90b2tlbiI7czo0MDoiVGVYNW01VVlmek1aUHNNbFpZWVhTV3NpMmMyT1EyckMwc1daYkp0byI7fQ==', 1748198776),
('dhkZgfcQBL3KBpvdAxz8IdMTHcf2ftNQwrNCQ0nT', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YToyOntzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MDp7fX1zOjY6Il90b2tlbiI7czo0MDoienkxRTY1a25oWElkZzV1N0Y1aGF3THByaHQ1TWFnVnRHSEFaQkwwaSI7fQ==', 1748198780);

-- --------------------------------------------------------

--
-- Table structure for table `tb_beras`
--

CREATE TABLE `tb_beras` (
  `id_beras` bigint UNSIGNED NOT NULL,
  `nama_beras` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_produsen` bigint UNSIGNED NOT NULL,
  `jenis_beras` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stok_tersedia` int NOT NULL,
  `tgl_produksi` date NOT NULL,
  `kualitas_beras` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sertifikat_beras` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_beras` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tb_beras`
--

INSERT INTO `tb_beras` (`id_beras`, `nama_beras`, `id_produsen`, `jenis_beras`, `stok_tersedia`, `tgl_produksi`, `kualitas_beras`, `sertifikat_beras`, `status_beras`, `created_at`, `updated_at`) VALUES
(13, 'ABC', 1, 'Putih', 3250, '2025-05-21', 'Premium', '/storage/upload/sertifikat_beras/Produsen01/sertifikat-ABC-21-05-2025.jpg', 'Tersedia', '2025-05-21 04:11:11', '2025-05-23 19:14:34'),
(20, 'Kelopak', 1, 'Hitam', 1800, '2025-05-22', 'Premium', '/storage/upload/sertifikat_beras/Produsen01/sertifikat-Kelopak-22-05-2025.jpg', 'Tersedia', '2025-05-22 01:06:06', '2025-05-22 01:06:06'),
(21, 'Hoki', 1, 'Merah', 2850, '2025-05-22', 'Premium', '/storage/upload/sertifikat_beras/Produsen01/sertifikat-Hoki-22-05-2025.jpg', 'Tersedia', '2025-05-22 01:24:16', '2025-05-22 01:33:10'),
(22, 'Mahkota', 1, 'Ketan', 3250, '2025-05-22', 'Premium', '/storage/upload/sertifikat_beras/Produsen01/sertifikat-Mahkota-22-05-2025.jpg', 'Tersedia', '2025-05-22 01:41:06', '2025-05-23 01:00:02'),
(23, 'Mawar', 1, 'Putih', 2700, '2025-05-22', 'Medium', '/storage/upload/sertifikat_beras/Produsen01/sertifikat-Mawar-22-05-2025.jpg', 'Tersedia', '2025-05-22 23:44:03', '2025-05-23 00:42:08'),
(24, 'Nyoba', 1, 'Putih', 2000, '2025-05-22', 'Premium', '/storage/upload/sertifikat_beras/Produsen01/sertifikat-Nyoba-23-05-2025.jpg', 'Tersedia', '2025-05-23 00:58:10', '2025-05-23 18:10:08'),
(25, 'Dewa', 2, 'Putih', 4000, '2025-05-23', 'Premium', '/storage/upload/sertifikat_beras/Produsen02/sertifikat-Dewa-23-05-2025.jpg', 'Tersedia', '2025-05-23 19:40:48', '2025-05-23 19:40:48'),
(26, 'Tulip', 2, 'Hitam', 4800, '2025-05-23', 'Premium', '/storage/upload/sertifikat_beras/Produsen02/sertifikat-Tulip-23-05-2025.jpg', 'Tersedia', '2025-05-23 19:42:45', '2025-05-23 19:42:45'),
(27, 'Merpati', 2, 'Putih', 5100, '2025-05-23', 'Medium', '/storage/upload/sertifikat_beras/Produsen02/sertifikat-Merpati-23-05-2025.jpg', 'Tersedia', '2025-05-23 19:44:05', '2025-05-23 19:44:05'),
(28, 'Raja', 3, 'Putih', 6000, '2025-05-24', 'Premium', '/storage/upload/sertifikat_beras/Produsen 03/sertifikat-Raja-25-05-2025.jpg', 'Tersedia', '2025-05-25 00:39:35', '2025-05-25 00:39:35'),
(29, 'Naga', 3, 'Ketan', 4600, '2025-05-24', 'Premium', '/storage/upload/sertifikat_beras/Produsen 03/sertifikat-Naga-25-05-2025.jpg', 'Tersedia', '2025-05-25 00:41:41', '2025-05-25 00:41:41');

-- --------------------------------------------------------

--
-- Table structure for table `tb_detail_beras`
--

CREATE TABLE `tb_detail_beras` (
  `id_detail` bigint UNSIGNED NOT NULL,
  `id_beras` bigint UNSIGNED NOT NULL,
  `berat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` int NOT NULL,
  `harga` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tb_detail_beras`
--

INSERT INTO `tb_detail_beras` (`id_detail`, `id_beras`, `berat`, `jumlah`, `harga`, `created_at`, `updated_at`) VALUES
(1, 13, '10', 100, 115000, '2025-05-21 04:11:11', '2025-05-23 19:14:34'),
(2, 13, '20', 50, 215500, '2025-05-21 04:11:11', '2025-05-23 19:14:34'),
(3, 13, '50', 25, 550000, '2025-05-21 04:11:11', '2025-05-23 19:14:34'),
(7, 20, '10', 45, 255000, '2025-05-22 01:06:06', '2025-05-22 01:06:06'),
(8, 20, '20', 30, 513000, '2025-05-22 01:06:06', '2025-05-22 01:06:06'),
(9, 20, '50', 15, 1280000, '2025-05-22 01:06:06', '2025-05-22 01:06:06'),
(10, 21, '10', 60, 292000, '2025-05-22 01:24:16', '2025-05-22 01:32:26'),
(12, 21, '50', 45, 1465000, '2025-05-22 01:24:16', '2025-05-22 01:33:10'),
(16, 23, '20', 60, 230000, '2025-05-22 23:44:03', '2025-05-22 23:44:03'),
(17, 22, '10', 100, 138000, '2025-05-23 00:39:00', '2025-05-23 00:40:51'),
(18, 22, '20', 50, 280000, '2025-05-23 00:40:51', '2025-05-23 00:40:51'),
(19, 23, '50', 30, 580000, '2025-05-23 00:42:08', '2025-05-23 00:42:08'),
(24, 22, '50', 25, 700000, '2025-05-23 01:00:02', '2025-05-23 01:00:02'),
(25, 24, '10', 40, 120000, '2025-05-23 18:10:09', '2025-05-23 18:10:09'),
(26, 24, '20', 30, 240000, '2025-05-23 18:10:09', '2025-05-23 18:10:09'),
(27, 24, '50', 20, 565000, '2025-05-23 18:10:09', '2025-05-23 18:10:09'),
(28, 25, '10', 80, 108000, '2025-05-23 19:40:48', '2025-05-23 19:40:48'),
(29, 25, '20', 60, 218000, '2025-05-23 19:40:48', '2025-05-23 19:40:48'),
(30, 25, '50', 40, 550000, '2025-05-23 19:40:48', '2025-05-23 19:40:48'),
(31, 26, '10', 75, 138000, '2025-05-23 19:42:45', '2025-05-23 19:42:45'),
(32, 26, '20', 65, 280000, '2025-05-23 19:42:45', '2025-05-23 19:42:45'),
(33, 26, '50', 55, 700000, '2025-05-23 19:42:45', '2025-05-23 19:42:45'),
(34, 27, '20', 80, 200000, '2025-05-23 19:44:05', '2025-05-23 19:44:05'),
(35, 27, '50', 70, 505000, '2025-05-23 19:44:05', '2025-05-23 19:44:05'),
(36, 28, '10', 90, 108000, '2025-05-25 00:39:35', '2025-05-25 00:39:35'),
(37, 28, '20', 80, 220000, '2025-05-25 00:39:35', '2025-05-25 00:39:35'),
(38, 28, '50', 70, 550000, '2025-05-25 00:39:35', '2025-05-25 00:39:35'),
(39, 29, '20', 85, 245000, '2025-05-25 00:41:41', '2025-05-25 00:41:41'),
(40, 29, '50', 58, 580000, '2025-05-25 00:41:42', '2025-05-25 00:41:42');

-- --------------------------------------------------------

--
-- Table structure for table `tb_detail_gudang`
--

CREATE TABLE `tb_detail_gudang` (
  `id_detail_gudang` bigint UNSIGNED NOT NULL,
  `id_gudang` bigint UNSIGNED NOT NULL,
  `berat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stok_awal` int NOT NULL DEFAULT '0',
  `rusak` int NOT NULL DEFAULT '0',
  `hilang` int NOT NULL DEFAULT '0',
  `stok_sisa` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tb_detail_gudang`
--

INSERT INTO `tb_detail_gudang` (`id_detail_gudang`, `id_gudang`, `berat`, `stok_awal`, `rusak`, `hilang`, `stok_sisa`, `created_at`, `updated_at`) VALUES
(1, 7, '10', 86, 3, 2, 81, '2025-05-23 15:04:35', '2025-05-23 15:27:36'),
(3, 7, '50', 10, 1, 3, 6, '2025-05-23 15:14:42', '2025-05-23 15:27:36'),
(4, 8, '20', 70, 3, 2, 65, '2025-05-23 15:25:13', '2025-05-23 15:26:47'),
(5, 8, '10', 90, 1, 3, 86, '2025-05-23 15:26:47', '2025-05-23 15:26:47'),
(6, 8, '50', 50, 2, 1, 47, '2025-05-23 15:26:47', '2025-05-23 15:26:47'),
(7, 7, '20', 78, 2, 1, 75, '2025-05-23 15:27:36', '2025-05-23 15:27:36');

-- --------------------------------------------------------

--
-- Table structure for table `tb_detail_pemesanan`
--

CREATE TABLE `tb_detail_pemesanan` (
  `id_detail_pemesanan` bigint UNSIGNED NOT NULL,
  `id_pemesanan` bigint UNSIGNED NOT NULL,
  `berat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` int NOT NULL DEFAULT '0',
  `harga_satuan` int NOT NULL,
  `total_harga` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tb_detail_pemesanan`
--

INSERT INTO `tb_detail_pemesanan` (`id_detail_pemesanan`, `id_pemesanan`, `berat`, `jumlah`, `harga_satuan`, `total_harga`, `created_at`, `updated_at`) VALUES
(1, 5, '10', 10, 108000, 1080000, '2025-05-24 18:21:13', '2025-05-24 18:21:13'),
(2, 5, '20', 20, 220000, 4400000, '2025-05-24 18:21:13', '2025-05-24 18:21:13'),
(3, 5, '50', 30, 550000, 16500000, '2025-05-24 18:21:13', '2025-05-24 18:21:13'),
(4, 6, '10', 25, 108000, 2700000, '2025-05-24 19:08:58', '2025-05-24 19:08:58'),
(6, 7, '20', 10, 245000, 2450000, '2025-05-25 16:17:24', '2025-05-25 16:17:24');

-- --------------------------------------------------------

--
-- Table structure for table `tb_gudang`
--

CREATE TABLE `tb_gudang` (
  `id_gudang` bigint UNSIGNED NOT NULL,
  `id_beras` bigint UNSIGNED NOT NULL,
  `id_produsen` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tb_gudang`
--

INSERT INTO `tb_gudang` (`id_gudang`, `id_beras`, `id_produsen`, `created_at`, `updated_at`) VALUES
(7, 24, 1, '2025-05-23 15:04:35', '2025-05-23 15:04:35'),
(8, 27, 2, '2025-05-23 15:25:12', '2025-05-23 15:25:12');

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
(5, 3, 28, 60, '2025-05-25', 'Telah Dikonfirmasi', 'Stok menipis', '2025-05-25 02:21:12', '2025-05-26 03:19:20'),
(6, 2, 25, 25, '2025-05-26', 'Pending', '-', '2025-05-25 03:08:58', '2025-05-26 00:04:07'),
(7, 3, 29, 10, '2025-05-26', 'Telah Dikonfirmasi', '-', '2025-05-26 00:17:24', '2025-05-26 00:56:25');

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
  `total_bayar` int NOT NULL,
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

INSERT INTO `tb_transaksi` (`id_transaksi`, `id_pemesanan`, `tgl_transaksi`, `jmlh`, `total_bayar`, `bukti_bayar`, `status_pembayaran`, `status_pengiriman`, `tgl_pengiriman`, `catatan`, `created_at`, `updated_at`) VALUES
(8, 7, '2025-05-26', 10, 2450000, '/storage/upload/bukti_bayar_/26-05-2025/8-26-05-2025-26-05-2025.jpg', 'Selesai', 'Selesai', '2025-05-26', '- terdapat 2 karung yang rusak', '2025-05-26 00:56:25', '2025-05-26 03:20:26'),
(9, 5, NULL, 60, 21980000, NULL, 'Pending', 'Pending', NULL, '-', '2025-05-26 03:19:20', '2025-05-26 03:19:20');

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
-- Indexes for table `tb_detail_beras`
--
ALTER TABLE `tb_detail_beras`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `tb_detail_beras_id_beras_foreign` (`id_beras`);

--
-- Indexes for table `tb_detail_gudang`
--
ALTER TABLE `tb_detail_gudang`
  ADD PRIMARY KEY (`id_detail_gudang`),
  ADD KEY `tb_detail_gudang_id_gudang_foreign` (`id_gudang`);

--
-- Indexes for table `tb_detail_pemesanan`
--
ALTER TABLE `tb_detail_pemesanan`
  ADD PRIMARY KEY (`id_detail_pemesanan`),
  ADD KEY `tb_detail_pemesanan_id_pemesanan_foreign` (`id_pemesanan`);

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tb_beras`
--
ALTER TABLE `tb_beras`
  MODIFY `id_beras` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `tb_detail_beras`
--
ALTER TABLE `tb_detail_beras`
  MODIFY `id_detail` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `tb_detail_gudang`
--
ALTER TABLE `tb_detail_gudang`
  MODIFY `id_detail_gudang` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tb_detail_pemesanan`
--
ALTER TABLE `tb_detail_pemesanan`
  MODIFY `id_detail_pemesanan` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tb_gudang`
--
ALTER TABLE `tb_gudang`
  MODIFY `id_gudang` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tb_pemesanan`
--
ALTER TABLE `tb_pemesanan`
  MODIFY `id_pemesanan` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tb_produsen`
--
ALTER TABLE `tb_produsen`
  MODIFY `id_produsen` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  MODIFY `id_transaksi` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
-- Constraints for table `tb_detail_beras`
--
ALTER TABLE `tb_detail_beras`
  ADD CONSTRAINT `tb_detail_beras_id_beras_foreign` FOREIGN KEY (`id_beras`) REFERENCES `tb_beras` (`id_beras`) ON DELETE CASCADE;

--
-- Constraints for table `tb_detail_gudang`
--
ALTER TABLE `tb_detail_gudang`
  ADD CONSTRAINT `tb_detail_gudang_id_gudang_foreign` FOREIGN KEY (`id_gudang`) REFERENCES `tb_gudang` (`id_gudang`) ON DELETE CASCADE;

--
-- Constraints for table `tb_detail_pemesanan`
--
ALTER TABLE `tb_detail_pemesanan`
  ADD CONSTRAINT `tb_detail_pemesanan_id_pemesanan_foreign` FOREIGN KEY (`id_pemesanan`) REFERENCES `tb_pemesanan` (`id_pemesanan`) ON DELETE CASCADE;

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
