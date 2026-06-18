-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 10, 2026 at 10:22 AM
-- Server version: 8.0.30
-- PHP Version: 8.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bpmn_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_id` bigint UNSIGNED DEFAULT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `disposisis`
--

CREATE TABLE `disposisis` (
  `id` bigint UNSIGNED NOT NULL,
  `surat_masuk_id` bigint UNSIGNED NOT NULL,
  `dari_user_id` bigint UNSIGNED NOT NULL,
  `ke_user_id` bigint UNSIGNED NOT NULL,
  `instruksi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tenggat_waktu` date DEFAULT NULL,
  `prioritas` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `draf_surats`
--

CREATE TABLE `draf_surats` (
  `id` bigint UNSIGNED NOT NULL,
  `surat_masuk_id` bigint UNSIGNED DEFAULT NULL,
  `judul_draf` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nomor_draf` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_draf` date DEFAULT NULL,
  `ringkasan` text COLLATE utf8mb4_unicode_ci,
  `catatan_staf` text COLLATE utf8mb4_unicode_ci,
  `dibuat_oleh` bigint UNSIGNED NOT NULL,
  `file_draf` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `versi` int NOT NULL DEFAULT '1',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu_reviu',
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, '0000_01_01_000000_create_unit_kerjas_table', 1),
(2, '0001_01_01_000000_create_users_table', 1),
(3, '0001_01_01_000001_create_cache_table', 1),
(4, '0001_01_01_000002_create_jobs_table', 1),
(5, '2026_06_09_041103_create_surat_masuks_table', 1),
(6, '2026_06_09_041104_create_disposisis_table', 1),
(7, '2026_06_09_041104_create_draf_surats_table', 1),
(8, '2026_06_09_041105_create_reviu_surats_table', 1),
(9, '2026_06_09_041106_create_activity_logs_table', 1),
(10, '2026_06_09_041106_create_surat_finals_table', 1),
(11, '2026_06_09_044908_create_notifications_table', 1),
(12, '2026_06_09_070954_add_tenggat_waktu_and_prioritas_to_disposisis_table', 1),
(13, '2026_06_09_223321_add_columns_to_draf_surats_table', 2),
(14, '2026_06_10_082821_create_template_surats_table', 3),
(15, '2026_06_10_103451_add_konten_to_template_surats_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviu_surats`
--

CREATE TABLE `reviu_surats` (
  `id` bigint UNSIGNED NOT NULL,
  `draf_surat_id` bigint UNSIGNED NOT NULL,
  `reviewer_id` bigint UNSIGNED NOT NULL,
  `tingkat` enum('1','2','final') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('menunggu','disetujui','revisi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `catatan_reviu` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('18VJHULLtcjHg4PhAwrp7lIW1LqpyoR5whavwQxV', NULL, '158.140.182.35', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 'eyJfdG9rZW4iOiJBVjRhY3I3VU9jeW1zQ0VCYzc1SENTcW9INjZ1OVFOcmJQclU5dUV5IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9mbGFzaGNhcmQtdHJhaWxpbmctZmx5aW5nLm5ncm9rLWZyZWUuZGV2XC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1781066096),
('6pMJFOEBRQ0tDy8lrFNfdzKeMAv5WQsaOLTvgIUS', 1, '114.10.24.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJpWVRqRWoxWnJjN1d1bktxVGxBNXZNaEFnZlc4eTdyQ3FQdWF0UHBsIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9mbGFzaGNhcmQtdHJhaWxpbmctZmx5aW5nLm5ncm9rLWZyZWUuZGV2XC9hZG1pblwvdGVtcGxhdGUtc3VyYXQiLCJyb3V0ZSI6ImFkbWluLnRlbXBsYXRlLXN1cmF0LmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9', 1781074454),
('8VjEkpuFtcLw7sa8eDe6u9pOQ1WsM9MquY78Uysd', NULL, '2402:8780:1012:22ed:b9e4:355c:58c0:2625', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJaRTVBSUZpRDB1dVBqMTI1Z1lZZVZocEZTc2c5MUlIRnNwZVhjc1E3IiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL2ZsYXNoY2FyZC10cmFpbGluZy1mbHlpbmcubmdyb2stZnJlZS5kZXZcL2FkbWluXC90ZW1wbGF0ZS1zdXJhdCJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cHM6XC9cL2ZsYXNoY2FyZC10cmFpbGluZy1mbHlpbmcubmdyb2stZnJlZS5kZXZcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1781085460),
('B0zvsOR3EFv9LW1wwG4MlvAQ2ji7PPgkjNeZhsKN', NULL, '2402:8780:1012:22ed:b9e4:355c:58c0:2625', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJ6aGN3WmFUUTd1cFp0Q2VhMVR0cXFMdWhDQU82UDlMZ2o5dHFhb2FrIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9mbGFzaGNhcmQtdHJhaWxpbmctZmx5aW5nLm5ncm9rLWZyZWUuZGV2XC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1781086413),
('cHlTK8kkuWmk7CJXHJGcCc49vnFh8v0P1kSEM0RX', 6, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJWdHdRVmk5TDRVNXM5TDVnZFZzT0pRZlBvYjVnTjNPWURSMHhzY3JuIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL3Byb2ZpbGUiLCJyb3V0ZSI6InByb2ZpbGUuZWRpdCJ9LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6Nn0=', 1781023374),
('K7bancK215P8xy1BBH1zDB6QIIxP3SvQMF23yOiF', 1, '2402:8780:1012:22ed:b9e4:355c:58c0:2625', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJ4Z05GM2d2Mk16dWFmVXM0a0pUQVNSSFl1Sm9DeTdKU1JLWkY3czRIIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9mbGFzaGNhcmQtdHJhaWxpbmctZmx5aW5nLm5ncm9rLWZyZWUuZGV2XC9hZG1pblwvdGVtcGxhdGUtc3VyYXQiLCJyb3V0ZSI6ImFkbWluLnRlbXBsYXRlLXN1cmF0LmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9', 1781064422),
('mrWixL4iJjfzOtBwjl3T5sz3bP9vRvvrtEKsaiXM', NULL, '2a03:2880:18ff:57::', 'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)', 'eyJfdG9rZW4iOiJ3dk1IZUZESUhxQVNWVzA1c0VVMXcycGo0ZjhTUXFXRTRHNlJjcEFFIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9mbGFzaGNhcmQtdHJhaWxpbmctZmx5aW5nLm5ncm9rLWZyZWUuZGV2XC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1781055840),
('uz3ioS7oFOndKu7Md3bGn1WlU3k70Urnn4fLEqpD', NULL, '2a03:2880:18ff:4f::', 'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)', 'eyJfdG9rZW4iOiJzOXJUaFFzRGsyczc2OVV3MWN1WXpPUGkyd3MxV3FjajNCZnBEREc2IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9mbGFzaGNhcmQtdHJhaWxpbmctZmx5aW5nLm5ncm9rLWZyZWUuZGV2Iiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1781055839),
('wKLYGqK99MiWJDNGrlHPq7Xmup7PjgIUE8QhPLFm', 5, '158.140.182.35', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJNNGlQRDA1d2E1enRqUlFwdERUZERtRjdWa3BJUm5mcnlyeUxGNDVOIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cHM6XC9cL2ZsYXNoY2FyZC10cmFpbGluZy1mbHlpbmcubmdyb2stZnJlZS5kZXZcL3N0YWZcL2RyYWYtc3VyYXRcL2NyZWF0ZT9kaXNwb3Npc2lfaWQ9OSZzdXJhdF9tYXN1a19pZD0xMCIsInJvdXRlIjoic3RhZi5kcmFmLXN1cmF0LmNyZWF0ZSJ9LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6NX0=', 1781059509),
('wuauJ9q6wR2L2Unw8YNa7Vj0e2uzKJO6Q5MmBImS', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJCd1NOWkxoRmtRUTV0UlQwcUVqZlJmM2JsS09JdzlmcG84b0F4ZW1iIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAzXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1781055110);

-- --------------------------------------------------------

--
-- Table structure for table `surat_finals`
--

CREATE TABLE `surat_finals` (
  `id` bigint UNSIGNED NOT NULL,
  `surat_masuk_id` bigint UNSIGNED NOT NULL,
  `draf_surat_id` bigint UNSIGNED NOT NULL,
  `nomor_surat_final` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_ttd` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ditandatangani_oleh` bigint UNSIGNED DEFAULT NULL,
  `tanggal_ttd` date DEFAULT NULL,
  `file_distribusi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `via` enum('email','fisik','keduanya') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'email',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `surat_masuks`
--

CREATE TABLE `surat_masuks` (
  `id` bigint UNSIGNED NOT NULL,
  `nomor_agenda` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nomor_surat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_surat` date NOT NULL,
  `tanggal_terima` date NOT NULL,
  `asal_surat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `perihal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_surat` enum('fisik','digital') COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_surat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `status` enum('diterima','diproses','menunggu_reviu','revisi','selesai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'diterima',
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `template_surats`
--

CREATE TABLE `template_surats` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_template` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `konten` longtext COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uploaded_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `unit_kerjas`
--

CREATE TABLE `unit_kerjas` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `level` enum('biro','bagian','sub_tim','lainnya') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'lainnya',
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `unit_kerjas`
--

INSERT INTO `unit_kerjas` (`id`, `nama`, `kode`, `parent_id`, `level`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'Biro Keuangan & BMN', 'BKB', NULL, 'biro', NULL, '2026-06-09 10:36:33', '2026-06-09 10:36:33'),
(2, 'Subbagian Tata Usaha Biro', 'TU', 1, 'bagian', NULL, '2026-06-09 10:36:33', '2026-06-09 10:36:33'),
(3, 'Bagian Pelaksanaan Anggaran', 'BPA', 1, 'bagian', NULL, '2026-06-09 10:36:33', '2026-06-09 10:36:33'),
(4, 'Sub Tim Perbendaharaan', 'STP', 3, 'sub_tim', NULL, '2026-06-09 10:36:33', '2026-06-09 10:36:33');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','tata_usaha','kepala_bagian','kepala_sub_tim','staf','kepala_biro') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'staf',
  `jabatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_kerja_id` bigint UNSIGNED DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `nip`, `email_verified_at`, `password`, `role`, `jabatan`, `unit_kerja_id`, `avatar`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Budi Santoso', 'admin@bkbmn.go.id', NULL, '2026-06-09 10:36:34', '$2y$12$CNCBkZ8dZfjyWvvinmnNaOGB.YWrYZbtFaNKEROaaUIxmD18rZLeK', 'admin', 'Admin Persuratan', 1, NULL, NULL, '2026-06-09 10:36:34', '2026-06-09 10:36:34'),
(2, 'Ahmad Riza', 'tu@bkbmn.go.id', NULL, '2026-06-09 10:36:34', '$2y$12$k0OId1LJqhJzg0ePwsFLwerr19v6Ma0a6wBdQ11MWRXJ7V7qZKjuW', 'tata_usaha', 'Staf Tata Usaha', 2, NULL, NULL, '2026-06-09 10:36:34', '2026-06-09 10:36:34'),
(3, 'Siti Nurhaliza', 'kabag@bkbmn.go.id', NULL, '2026-06-09 10:36:35', '$2y$12$/ChiNBuQ4E./KSLMTD6Szu2BkBZTlwwFmpKMInNwssiTM2l2oJnnq', 'kepala_bagian', 'Kepala Bagian Keuangan', 3, NULL, NULL, '2026-06-09 10:36:35', '2026-06-09 10:36:35'),
(4, 'Rizki Maulana', 'kasubtim@bkbmn.go.id', NULL, '2026-06-09 10:36:35', '$2y$12$aWYNv5J1ipSKv6coGI0MdOx267Zlqa0HImhbZfJDfuBxNKXbDa6oK', 'kepala_sub_tim', 'Kepala Sub Tim', 4, NULL, NULL, '2026-06-09 10:36:35', '2026-06-09 10:36:35'),
(5, 'Andi Wijaya', 'staf@bkbmn.go.id', NULL, '2026-06-09 10:36:35', '$2y$12$pFtS6D98bDBZOTjdAy4nB.Piaj4PziZLn/.rDjtAiCbygXtei..L6', 'staf', 'Staf Pelaksana', 4, NULL, NULL, '2026-06-09 10:36:35', '2026-06-09 10:36:35'),
(6, 'Dr. Hendra Gunawan', 'kabiro@bkbmn.go.id', NULL, '2026-06-09 10:36:36', '$2y$12$Pd2neP4fWRwEy7GJmjuSHe3go6WztIQGu7fzGbub1j5SXaG81ASQO', 'kepala_biro', 'Kepala Biro', 1, NULL, NULL, '2026-06-09 10:36:36', '2026-06-09 10:36:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `disposisis`
--
ALTER TABLE `disposisis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `disposisis_surat_masuk_id_foreign` (`surat_masuk_id`),
  ADD KEY `disposisis_dari_user_id_foreign` (`dari_user_id`),
  ADD KEY `disposisis_ke_user_id_foreign` (`ke_user_id`);

--
-- Indexes for table `draf_surats`
--
ALTER TABLE `draf_surats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `draf_surats_surat_masuk_id_foreign` (`surat_masuk_id`),
  ADD KEY `draf_surats_dibuat_oleh_foreign` (`dibuat_oleh`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `reviu_surats`
--
ALTER TABLE `reviu_surats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviu_surats_draf_surat_id_foreign` (`draf_surat_id`),
  ADD KEY `reviu_surats_reviewer_id_foreign` (`reviewer_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `surat_finals`
--
ALTER TABLE `surat_finals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `surat_finals_surat_masuk_id_foreign` (`surat_masuk_id`),
  ADD KEY `surat_finals_draf_surat_id_foreign` (`draf_surat_id`),
  ADD KEY `surat_finals_ditandatangani_oleh_foreign` (`ditandatangani_oleh`);

--
-- Indexes for table `surat_masuks`
--
ALTER TABLE `surat_masuks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `surat_masuks_nomor_surat_unique` (`nomor_surat`),
  ADD KEY `surat_masuks_created_by_foreign` (`created_by`);

--
-- Indexes for table `template_surats`
--
ALTER TABLE `template_surats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `template_surats_uploaded_by_foreign` (`uploaded_by`);

--
-- Indexes for table `unit_kerjas`
--
ALTER TABLE `unit_kerjas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unit_kerjas_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_nip_unique` (`nip`),
  ADD KEY `users_unit_kerja_id_foreign` (`unit_kerja_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `disposisis`
--
ALTER TABLE `disposisis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `draf_surats`
--
ALTER TABLE `draf_surats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `reviu_surats`
--
ALTER TABLE `reviu_surats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `surat_finals`
--
ALTER TABLE `surat_finals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `surat_masuks`
--
ALTER TABLE `surat_masuks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `template_surats`
--
ALTER TABLE `template_surats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `unit_kerjas`
--
ALTER TABLE `unit_kerjas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `disposisis`
--
ALTER TABLE `disposisis`
  ADD CONSTRAINT `disposisis_dari_user_id_foreign` FOREIGN KEY (`dari_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `disposisis_ke_user_id_foreign` FOREIGN KEY (`ke_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `disposisis_surat_masuk_id_foreign` FOREIGN KEY (`surat_masuk_id`) REFERENCES `surat_masuks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `draf_surats`
--
ALTER TABLE `draf_surats`
  ADD CONSTRAINT `draf_surats_dibuat_oleh_foreign` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `draf_surats_surat_masuk_id_foreign` FOREIGN KEY (`surat_masuk_id`) REFERENCES `surat_masuks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviu_surats`
--
ALTER TABLE `reviu_surats`
  ADD CONSTRAINT `reviu_surats_draf_surat_id_foreign` FOREIGN KEY (`draf_surat_id`) REFERENCES `draf_surats` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviu_surats_reviewer_id_foreign` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `surat_finals`
--
ALTER TABLE `surat_finals`
  ADD CONSTRAINT `surat_finals_ditandatangani_oleh_foreign` FOREIGN KEY (`ditandatangani_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `surat_finals_draf_surat_id_foreign` FOREIGN KEY (`draf_surat_id`) REFERENCES `draf_surats` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `surat_finals_surat_masuk_id_foreign` FOREIGN KEY (`surat_masuk_id`) REFERENCES `surat_masuks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `surat_masuks`
--
ALTER TABLE `surat_masuks`
  ADD CONSTRAINT `surat_masuks_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `template_surats`
--
ALTER TABLE `template_surats`
  ADD CONSTRAINT `template_surats_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `unit_kerjas`
--
ALTER TABLE `unit_kerjas`
  ADD CONSTRAINT `unit_kerjas_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `unit_kerjas` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_unit_kerja_id_foreign` FOREIGN KEY (`unit_kerja_id`) REFERENCES `unit_kerjas` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
