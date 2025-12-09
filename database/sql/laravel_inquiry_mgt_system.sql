-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 01, 2024 at 05:10 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel_inquiry_mgt_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`),
  UNIQUE KEY `admins_username_unique` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `admins`
--

TRUNCATE TABLE `admins`;
--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `username`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'superadmin@mailinator.com', 'superadmin', NULL, '$2y$10$MoZjmQjYOFOEoYcnyw.WruPYy8Z5XmC0qY86EHqNZfXpUvEDQg1Dy', NULL, '2024-06-29 02:26:27', '2024-06-29 02:26:27');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `failed_jobs`
--

TRUNCATE TABLE `failed_jobs`;
-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

DROP TABLE IF EXISTS `inquiries`;
CREATE TABLE IF NOT EXISTS `inquiries` (
  `id` int NOT NULL AUTO_INCREMENT,
  `unique_id` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email_id` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `mobile` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `company` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `designation` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `country` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: Disabled, 1: Enabled',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` tinyint NOT NULL DEFAULT '0' COMMENT '0: No, 1: Yes',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='User Marketing Sales Records';

--
-- Truncate table before insert `inquiries`
--

TRUNCATE TABLE `inquiries`;
-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `migrations`
--

TRUNCATE TABLE `migrations`;
--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2020_07_24_184706_create_permission_tables', 1),
(5, '2020_09_12_043205_create_admins_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `model_has_permissions`
--

TRUNCATE TABLE `model_has_permissions`;
-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `model_has_roles`
--

TRUNCATE TABLE `model_has_roles`;
--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\Admin', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `password_resets`
--

TRUNCATE TABLE `password_resets`;
-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1: Active, 0: De-Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `permissions`
--

TRUNCATE TABLE `permissions`;
--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `group_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'dashboard.view', 'admin', 'dashboard', 1, '2024-06-29 02:26:27', '2024-06-29 02:26:27'),
(2, 'dashboard.edit', 'admin', 'dashboard', 1, '2024-06-29 02:26:27', '2024-06-29 02:26:27'),
(3, 'inquiry.create', 'admin', 'inquiry', 1, '2024-06-29 02:26:27', '2024-06-29 02:26:27'),
(4, 'inquiry.view', 'admin', 'inquiry', 1, '2024-06-29 02:26:27', '2024-06-29 02:26:27'),
(5, 'inquiry.edit', 'admin', 'inquiry', 1, '2024-06-29 02:26:27', '2024-06-29 02:26:27'),
(6, 'inquiry.delete', 'admin', 'inquiry', 1, '2024-06-29 02:26:27', '2024-06-29 02:26:27'),
(7, 'inquiry.approve', 'admin', 'inquiry', 1, '2024-06-29 02:26:27', '2024-06-29 02:26:27'),
(8, 'admin.create', 'admin', 'admin', 1, '2024-06-29 02:26:27', '2024-06-29 02:26:27'),
(9, 'admin.view', 'admin', 'admin', 1, '2024-06-29 02:26:27', '2024-06-29 02:26:27'),
(10, 'admin.edit', 'admin', 'admin', 1, '2024-06-29 02:26:27', '2024-06-29 02:26:27'),
(11, 'admin.delete', 'admin', 'admin', 1, '2024-06-29 02:26:27', '2024-06-29 02:26:27'),
(12, 'admin.approve', 'admin', 'admin', 1, '2024-06-29 02:26:27', '2024-06-29 02:26:27'),
(13, 'role.create', 'admin', 'role', 1, '2024-06-29 02:26:28', '2024-06-29 02:26:28'),
(14, 'role.view', 'admin', 'role', 1, '2024-06-29 02:26:28', '2024-06-29 02:26:28'),
(15, 'role.edit', 'admin', 'role', 1, '2024-06-29 02:26:28', '2024-06-29 02:26:28'),
(16, 'role.delete', 'admin', 'role', 1, '2024-06-29 02:26:28', '2024-06-29 02:26:28'),
(17, 'role.approve', 'admin', 'role', 1, '2024-06-29 02:26:28', '2024-06-29 02:26:28'),
(18, 'profile.view', 'admin', 'profile', 1, '2024-06-29 02:26:28', '2024-06-29 02:26:28'),
(19, 'profile.edit', 'admin', 'profile', 1, '2024-06-29 02:26:28', '2024-06-29 02:26:28'),
(20, 'profile.delete', 'admin', 'profile', 1, '2024-06-29 02:26:28', '2024-06-29 02:26:28'),
(21, 'profile.update', 'admin', 'profile', 1, '2024-06-29 02:26:28', '2024-06-29 02:26:28'),
(22, 'permission.create', 'admin', 'permission', 1, '2024-06-29 02:26:27', '2024-06-29 02:26:27'),
(23, 'permission.view', 'admin', 'permission', 1, '2024-06-29 02:26:27', '2024-06-29 02:26:27'),
(24, 'permission.edit', 'admin', 'permission', 1, '2024-06-29 02:26:27', '2024-06-29 02:26:27'),
(25, 'permission.delete', 'admin', 'permission', 1, '2024-06-29 02:26:27', '2024-06-29 02:26:27'),
(26, 'customer.create', 'admin', 'customer', 1, '2024-06-29 02:26:27', '2024-06-29 02:26:27'),
(27, 'customer.view', 'admin', 'customer', 1, '2024-06-29 02:26:27', '2024-06-29 02:26:27'),
(28, 'customer.edit', 'admin', 'customer', 1, '2024-06-29 02:26:27', '2024-06-29 02:26:27'),
(29, 'customer.delete', 'admin', 'customer', 1, '2024-06-29 02:26:27', '2024-06-29 02:26:27'),
(30, 'customer.approve', 'admin', 'customer', 1, '2024-06-29 02:26:27', '2024-06-29 02:26:27'),
(31, 'user.create', 'admin', 'user', 1, '2024-06-29 02:26:27', '2024-06-29 02:26:27'),
(32, 'user.view', 'admin', 'user', 1, '2024-06-29 02:26:27', '2024-06-29 02:26:27'),
(33, 'user.edit', 'admin', 'user', 1, '2024-06-29 02:26:27', '2024-06-29 02:26:27'),
(34, 'user.delete', 'admin', 'user', 1, '2024-06-29 02:26:27', '2024-06-29 02:26:27'),
(35, 'user.approve', 'admin', 'user', 1, '2024-06-29 02:26:27', '2024-06-29 02:26:27');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `roles`
--

TRUNCATE TABLE `roles`;
--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'superadmin', 'admin', '2024-06-29 02:26:27', '2024-06-29 02:26:27');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `role_has_permissions`
--

TRUNCATE TABLE `role_has_permissions`;
--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `users`
--

TRUNCATE TABLE `users`;
--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Gautam Kakadiya', 'gk@devotiontech.io', NULL, '$2y$10$MoZjmQjYOFOEoYcnyw.WruPYy8Z5XmC0qY86EHqNZfXpUvEDQg1Dy', NULL, '2024-06-29 02:26:27', '2024-06-29 02:26:27');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
