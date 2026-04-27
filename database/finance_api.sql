-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for finance_api
CREATE DATABASE IF NOT EXISTS `finance_api` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `finance_api`;

-- Dumping structure for table finance_api.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance_api.cache: ~0 rows (approximately)

-- Dumping structure for table finance_api.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance_api.cache_locks: ~0 rows (approximately)

-- Dumping structure for table finance_api.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categories_user_id_foreign` (`user_id`),
  CONSTRAINT `categories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance_api.categories: ~7 rows (approximately)
REPLACE INTO `categories` (`id`, `name`, `user_id`, `created_at`, `updated_at`) VALUES
	(1, 'Gaji', 1, '2026-04-27 02:54:40', '2026-04-27 02:54:40'),
	(2, 'Investasi', 1, '2026-04-27 02:54:40', '2026-04-27 02:54:40'),
	(3, 'Makanan', 1, '2026-04-27 02:54:40', '2026-04-27 02:54:40'),
	(4, 'Transportasi', 1, '2026-04-27 02:54:40', '2026-04-27 02:54:40'),
	(5, 'Belanja', 1, '2026-04-27 02:54:40', '2026-04-27 02:54:40'),
	(6, 'Hiburan', 1, '2026-04-27 02:54:40', '2026-04-27 02:54:40'),
	(7, 'Tagihan', 1, '2026-04-27 02:54:40', '2026-04-27 02:54:40'),
	(11, 'Bayar Spp', 1, '2026-04-27 07:14:21', '2026-04-27 07:14:21'),
	(12, 'Gaji STMIK', 3, '2026-04-27 09:17:10', '2026-04-27 09:17:10');

-- Dumping structure for table finance_api.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance_api.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table finance_api.goals
CREATE TABLE IF NOT EXISTS `goals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_amount` bigint NOT NULL,
  `current_amount` bigint NOT NULL DEFAULT '0',
  `target_date` date DEFAULT NULL,
  `status` enum('active','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `goals_user_id_foreign` (`user_id`),
  CONSTRAINT `goals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance_api.goals: ~0 rows (approximately)

-- Dumping structure for table finance_api.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance_api.jobs: ~0 rows (approximately)

-- Dumping structure for table finance_api.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance_api.job_batches: ~0 rows (approximately)

-- Dumping structure for table finance_api.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance_api.migrations: ~1 rows (approximately)
REPLACE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2024_01_01_000003_create_categories_table', 1),
	(5, '2024_01_01_000004_create_transactions_table', 1),
	(6, '2026_04_27_095132_create_personal_access_tokens_table', 1),
	(7, '2024_01_01_000005_create_goals_table', 2),
	(8, '2026_04_27_142616_add_deleted_at_to_transactions_table', 2);

-- Dumping structure for table finance_api.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance_api.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table finance_api.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance_api.personal_access_tokens: ~4 rows (approximately)
REPLACE INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
	(1, 'App\\Models\\User', 1, 'auth-token', '5cc366beff19f4aa307741bb47d4936bd22cb4e7f8cfa227979f41fcb655307f', '["*"]', NULL, NULL, '2026-04-27 03:11:09', '2026-04-27 03:11:09'),
	(2, 'App\\Models\\User', 1, 'auth-token', '9f14d4217d00ab1ad4516c3b99f933dfd0f1a2af0ad40cf4b5460603cbef2c79', '["*"]', NULL, NULL, '2026-04-27 03:11:14', '2026-04-27 03:11:14'),
	(4, 'App\\Models\\User', 1, 'auth-token', 'fd50428b2a59c8c4cc59b22c81242a020dbb5343debd26aea2eb6b5c8aaea71c', '["*"]', NULL, NULL, '2026-04-27 03:49:03', '2026-04-27 03:49:03'),
	(6, 'App\\Models\\User', 1, 'auth-token', '8fa4ef9505e5ce47146a85b3e7350b801e48e4df687503e19d13851df0cc0866', '["*"]', '2026-04-27 09:24:29', NULL, '2026-04-27 05:07:21', '2026-04-27 09:24:29'),
	(9, 'App\\Models\\User', 3, 'auth-token', 'f00ea9bff4e8c36c8d31c54fd2992cd0742ff630a9d75d54a944c762f78531b7', '["*"]', NULL, NULL, '2026-04-27 09:16:31', '2026-04-27 09:16:31'),
	(12, 'App\\Models\\User', 3, 'auth-token', 'e715cd6ad74940a97d76babc2b8f94dd6525d2bb2a6fcc8c6416750c0415c00f', '["*"]', '2026-04-27 10:58:35', NULL, '2026-04-27 10:20:30', '2026-04-27 10:58:35');

-- Dumping structure for table finance_api.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance_api.sessions: ~0 rows (approximately)

-- Dumping structure for table finance_api.transactions
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `category_id` bigint unsigned NOT NULL,
  `type` enum('income','expense') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` bigint NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transactions_user_id_foreign` (`user_id`),
  KEY `transactions_category_id_foreign` (`category_id`),
  CONSTRAINT `transactions_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance_api.transactions: ~13 rows (approximately)
REPLACE INTO `transactions` (`id`, `user_id`, `category_id`, `type`, `amount`, `description`, `date`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(20, 1, 11, 'income', 500000, 'habsm', '2026-04-27', '2026-04-27 07:33:33', '2026-04-27 07:33:36', '2026-04-27 07:33:36'),
	(21, 1, 11, 'income', 500000, 'vsbs', '2026-04-27', '2026-04-27 07:35:01', '2026-04-27 07:35:06', '2026-04-27 07:35:06'),
	(22, 1, 11, 'expense', 500000, 'makan', '2026-04-27', '2026-04-27 07:39:49', '2026-04-27 07:39:57', '2026-04-27 07:39:57'),
	(23, 1, 11, 'expense', 500, 'prhrb', '2026-04-27', '2026-04-27 07:40:30', '2026-04-27 07:40:43', '2026-04-27 07:40:43'),
	(24, 1, 1, 'income', 2000000, 'Gaji', '2026-04-27', '2026-04-27 07:42:11', '2026-04-27 07:42:18', '2026-04-27 07:42:18'),
	(25, 3, 12, 'expense', 500000, 'Kampus kntl', '2026-04-27', '2026-04-27 09:18:10', '2026-04-27 09:18:23', '2026-04-27 09:18:23'),
	(26, 3, 12, 'income', 1000000, 'Gajian Stmik bagus', '2026-04-27', '2026-04-27 09:18:46', '2026-04-27 09:18:46', NULL),
	(27, 3, 12, 'expense', 250000, 'makan', '2026-04-27', '2026-04-27 09:48:30', '2026-04-27 09:48:30', NULL);

-- Dumping structure for table finance_api.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance_api.users: ~0 rows (approximately)
REPLACE INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Test User', 'test@example.com', NULL, '$2y$12$dO0q77ZSzo6xVNgN3n39ie1/r4MwQnuUQyum25.wCcJbXP.Rbv0na', NULL, '2026-04-27 02:54:40', '2026-04-27 02:54:40'),
	(3, 'hasbi kana abdilah', 'hasbi@gmail.com', NULL, '$2y$12$qWss60TAZsScz3uHn.WPoelRxtL9lpZqhDM7NxtHIIPUZMhgJCg0S', NULL, '2026-04-27 09:16:31', '2026-04-27 10:15:06');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
