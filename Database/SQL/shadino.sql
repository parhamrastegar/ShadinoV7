-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 06, 2025 at 03:39 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shadino`
--

-- --------------------------------------------------------

--
-- Table structure for table `ads`
--

DROP TABLE IF EXISTS `ads`;
CREATE TABLE IF NOT EXISTS `ads` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` enum('cake','decoration','photography','music','ceremony','catering','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `budget_min` decimal(10,0) NOT NULL,
  `budget_max` decimal(10,0) NOT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_date` date NOT NULL,
  `deadline` date NOT NULL,
  `status` enum('active','closed','completed') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `images` json DEFAULT NULL,
  `views` int DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_category` (`category`),
  KEY `idx_city` (`city`),
  KEY `idx_status` (`status`),
  KEY `idx_event_date` (`event_date`),
  KEY `idx_deadline` (`deadline`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ads`
--

INSERT INTO `ads` (`id`, `user_id`, `title`, `description`, `category`, `budget_min`, `budget_max`, `city`, `event_date`, `deadline`, `status`, `images`, `views`, `created_at`, `updated_at`) VALUES
(1, 1, 'نیاز به کیک تولد برای ۲۰ نفر', 'کیک تولد سفارشی با طراحی مدرن برای جشن تولد ۲۵ سالگی', 'cake', 500000, 800000, 'تهران', '2024-02-15', '2024-02-10', 'active', NULL, 0, '2025-08-20 06:49:37', '2025-08-20 06:49:37'),
(2, 1, 'تزیین سالن برای مراسم عقد', 'تزیین کامل سالن با گل و بادکنک برای ۵۰ نفر', 'decoration', 1000000, 1500000, 'تهران', '2024-03-01', '2024-02-25', 'active', NULL, 0, '2025-08-20 06:49:37', '2025-08-20 06:49:37'),
(3, 12, 'کیک تولد', 'یک کیک تولد ۱۶ سالگی برای پسرم', 'cake', 500000, 1000000, 'isfahan', '2025-08-28', '2025-08-28', 'active', NULL, 3, '2025-08-21 09:11:29', '2025-08-21 09:13:48'),
(4, 12, 'چیدمان بادکنک در تولد', 'چیدمان بادکنک در تولدچیدمان بادکنک در تولد', 'decoration', 10000000, 10000000, 'other', '2025-08-31', '2025-08-31', 'active', NULL, 2, '2025-08-21 09:38:56', '2025-08-21 09:39:09');

-- --------------------------------------------------------

--
-- Table structure for table `deliveries`
--

DROP TABLE IF EXISTS `deliveries`;
CREATE TABLE IF NOT EXISTS `deliveries` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ad_id` int NOT NULL,
  `delivery_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `business_id` int NOT NULL,
  `pickup_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `delivery_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('assigned','picked_up','delivered','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'assigned',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `business_id` (`business_id`),
  KEY `idx_ad_id` (`ad_id`),
  KEY `idx_delivery_id` (`delivery_id`),
  KEY `idx_status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sender_id` int NOT NULL,
  `receiver_id` int NOT NULL,
  `ad_id` int DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `receiver_id` (`receiver_id`),
  KEY `idx_sender_receiver` (`sender_id`,`receiver_id`),
  KEY `idx_ad_id` (`ad_id`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('proposal','message','delivery','general') COLLATE utf8mb4_unicode_ci NOT NULL,
  `related_id` int DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_is_read` (`is_read`),
  KEY `idx_type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proposals`
--

DROP TABLE IF EXISTS `proposals`;
CREATE TABLE IF NOT EXISTS `proposals` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ad_id` int NOT NULL,
  `business_id` int NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `extra_services` json DEFAULT NULL,
  `contact` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `delivery_time` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','accepted','rejected') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_proposal` (`ad_id`,`business_id`),
  KEY `idx_ad_id` (`ad_id`),
  KEY `idx_business_id` (`business_id`),
  KEY `idx_status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
CREATE TABLE IF NOT EXISTS `reports` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reporter_id` int NOT NULL,
  `reported_user_id` int DEFAULT NULL,
  `ad_id` int DEFAULT NULL,
  `reason` enum('spam','inappropriate','fraud','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','reviewed','resolved') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `reported_user_id` (`reported_user_id`),
  KEY `ad_id` (`ad_id`),
  KEY `idx_reporter_id` (`reporter_id`),
  KEY `idx_status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('customer','business','delivery') COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mobile` (`mobile`),
  KEY `idx_mobile` (`mobile`),
  KEY `idx_role` (`role`),
  KEY `idx_city` (`city`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `mobile`, `password`, `role`, `city`, `email`, `avatar`, `bio`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'سارا احمدی', '09123456789', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 'تهران', 'sara@example.com', NULL, NULL, 1, '2025-08-20 06:49:37', '2025-08-20 06:49:37'),
(2, 'علی محمدی', '09123456788', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'business', 'تهران', 'ali@example.com', NULL, NULL, 1, '2025-08-20 06:49:37', '2025-08-20 06:49:37'),
(3, 'رضا کریمی', '09123456787', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'delivery', 'تهران', 'reza@example.com', NULL, NULL, 1, '2025-08-20 06:49:37', '2025-08-20 06:49:37'),
(4, 'علی احمدی', '09903673788', '$2y$10$nOX9JGz/39VcbZZPbHazduzVFiigrNXqSxGq7EG4/zpIW8ucWkL06', 'customer', 'bandar-abbas', NULL, NULL, NULL, 1, '2025-08-20 07:20:00', '2025-08-20 07:20:00'),
(5, 'علی احمدی', '09903673777', '$2y$10$gQ9IVEx7DbBl4oD3zfW9UOB2K0HhYW4tJvAiuJjzS1RYvPCjE3J8a', 'customer', 'bandar-abbas', NULL, NULL, NULL, 1, '2025-08-21 03:49:18', '2025-08-21 03:49:18'),
(6, 'تست', '09888888888', '$2y$10$sS.qze12WtcbXFS2l8.IDuccbUp8CTQYNJMVS.o/E8TsIRyDhopRW', 'customer', 'tabriz', NULL, NULL, NULL, 1, '2025-08-21 04:09:42', '2025-08-21 04:09:42'),
(7, 'علی احمدی پور', '09903673799', '$2y$10$XbiQVI3FXEagLYBCeJ./Cu12u61bDhgzYKeTnBzCVHPp/sWI5f0n.', 'customer', 'bandar-abbas', NULL, NULL, NULL, 1, '2025-08-21 07:10:04', '2025-08-21 07:10:04'),
(8, 'محمد محمدی', '09777777777', '$2y$10$wCf/kFVTqCqkoJJRf94hQe8D8yvNLKb1ZW6M3YSOWVMCc6xzVVB32', 'customer', 'rasht', NULL, NULL, NULL, 1, '2025-08-21 07:13:26', '2025-08-21 07:13:26'),
(9, 'محمد محمدی زاده', '09111111111', '$2y$10$v35S.i.FfzPSSpVP3nxgw.5uf7rEveH/xkdhjZqwXs9anqX637L8u', 'delivery', 'other', NULL, NULL, NULL, 1, '2025-08-21 07:18:14', '2025-09-06 06:17:44'),
(10, 'کاربر بیزنس', '09111111122', '$2y$10$zhtivjGMkHjYoul/UsdEl.na4WQ./TppgBrNLAeDJ7iyPdVNCuyye', 'business', 'tehran', NULL, NULL, NULL, 1, '2025-08-21 07:19:41', '2025-08-21 07:33:54'),
(11, 'کاربر پیک', '09999999999', '$2y$10$6r2Xyqi9ZxXxnwI70z/0M.fb33A/CMoRohVDBmVrc2ufO0pdDpN9O', 'customer', 'bandar-abbas', NULL, NULL, NULL, 1, '2025-08-21 07:36:52', '2025-08-21 07:36:52'),
(12, 'رصا احمدی پور', '09666666666', '$2y$10$krbvaqiHG.bAYQaa/3AlB.xBbjAXupAG4IdMxyMfQvqrN4PQgIWEK', 'customer', 'rasht', NULL, NULL, NULL, 1, '2025-08-21 09:03:41', '2025-08-21 09:03:41'),
(13, 'محمد محمدی زاده', '09888888877', '$2y$10$QCxKcgExLEs1j09Oq9HWo.FPM06bF77Txp9UCmYEINFm5NNlClX3i', 'customer', 'shiraz', NULL, NULL, NULL, 1, '2025-08-21 09:46:31', '2025-08-21 09:46:31'),
(14, 'رضا احمدی', '09999999988', '$2y$10$fMtsRUNGDH83itdZrTI39OOS68m17orrq8X080W733kCHf7JJ1CCe', 'customer', 'bandar-abbas', NULL, NULL, NULL, 1, '2025-09-06 05:03:37', '2025-09-06 05:03:37'),
(15, 'محمد محمدی', '09888888880', '$2y$10$CzgfmHxiCKHaybaA3y/T8.MKia3at9iI.MPIG4RJxeD8d9oHSh6WC', 'customer', 'karaj', NULL, NULL, NULL, 1, '2025-09-06 06:16:00', '2025-09-06 06:16:00'),
(16, 'تست', '09111888888', '$2y$10$jyBqW/CFkRQrwfebOghIBeuDk6AhQ2pJwpOzUN7sqSTVNu3ACei3u', 'customer', 'mashhad', NULL, NULL, NULL, 1, '2025-09-06 06:18:18', '2025-09-06 06:18:18'),
(17, 'کاربر پیک', '09157967158', '$2y$10$1M4Z4tfl1v2mUdxTIQT4/ekN9bNAjhy884TG0rhvgNPeoNIz/JtVa', 'delivery', 'mashhad', NULL, NULL, NULL, 1, '2025-09-06 18:32:43', '2025-09-06 18:33:08'),
(18, 'تست', '09111111777', '$2y$10$LslonsVk/K3ntluIKyZ9VOkcmC6EYKbmBfI9hFfhDBA8Yu3fjHbg6', 'customer', 'isfahan', NULL, NULL, NULL, 1, '2025-09-06 18:34:47', '2025-09-06 18:34:47'),
(19, 'علی احمدی اصل', '09777777776', '$2y$10$hoKMYOK52B/QEGDw8.JeWexxzC6T9FJ2Nd1Qhcts.J.VEgkOq4lVu', 'customer', 'kermanshah', NULL, NULL, NULL, 1, '2025-09-06 18:38:26', '2025-09-06 18:38:26'),
(20, 'علی احمدی پور', '09888888882', '$2y$10$phqXEXVeDVJOY/ensMU66.IWqFrzj9e8pv1s0P/TJt03/vv.SA0ky', 'customer', 'rasht', NULL, NULL, NULL, 1, '2025-09-06 18:42:34', '2025-09-06 18:42:34'),
(21, 'تست', '09111911122', '$2y$10$F.43/r7DykDeE48W7zMR..ygargidVw2UZKBI497UfQt6TjTVRU12', 'delivery', 'other', NULL, NULL, NULL, 1, '2025-09-06 18:48:50', '2025-09-06 18:48:50'),
(22, 'پیک تست', '09123456792', '$2y$12$g9lZDOCY3lrzCdZ4yh5UH.jxOdTsjb2TTnaw2dRQ3nRMv26jNsGQ6', 'delivery', 'tehran', NULL, NULL, NULL, 1, '2025-09-06 19:02:24', '2025-09-06 19:02:24');

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

DROP TABLE IF EXISTS `user_profiles`;
CREATE TABLE IF NOT EXISTS `user_profiles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `profile_data` json DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `user_id`, `profile_data`, `created_at`, `updated_at`) VALUES
(1, 3, '{\"vehicle_type\": \"motorcycle\", \"delivery_city\": \"tehran\", \"license_number\": \"123456789\", \"experience_years\": 2}', '2025-09-06 19:01:45', '2025-09-06 19:01:45'),
(2, 2, '{\"business_name\": \"فروشگاه کیک\", \"business_address\": \"تهران، خیابان ولیعصر\", \"business_license\": \"987654321\", \"business_category\": \"cake\"}', '2025-09-06 19:01:45', '2025-09-06 19:01:45'),
(3, 22, '{\"vehicle_type\": \"motorcycle\", \"delivery_city\": \"tehran\", \"license_number\": \"TEST123456\", \"experience_years\": 1}', '2025-09-06 19:02:24', '2025-09-06 19:02:24');

-- --------------------------------------------------------

--
-- Table structure for table `user_ratings`
--

DROP TABLE IF EXISTS `user_ratings`;
CREATE TABLE IF NOT EXISTS `user_ratings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `rater_id` int NOT NULL,
  `rating` tinyint NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_rating` (`user_id`,`rater_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_rater_id` (`rater_id`),
  KEY `idx_rating` (`rating`),
  KEY `idx_created_at` (`created_at`)
) ;

--
-- Dumping data for table `user_ratings`
--

INSERT INTO `user_ratings` (`id`, `user_id`, `rater_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 5, 'خدمات عالی و کیفیت بالا', '2025-09-06 05:06:10', '2025-09-06 05:06:10'),
(2, 1, 3, 4, 'خیلی راضی بودم', '2025-09-06 05:06:10', '2025-09-06 05:06:10'),
(3, 2, 1, 5, 'پیشنهادات خوبی ارائه داد', '2025-09-06 05:06:10', '2025-09-06 05:06:10'),
(4, 2, 3, 3, 'قابل قبول بود', '2025-09-06 05:06:10', '2025-09-06 05:06:10'),
(5, 3, 1, 4, 'تحویل به موقع', '2025-09-06 05:06:10', '2025-09-06 05:06:10'),
(6, 3, 2, 5, 'بسیار سریع و دقیق', '2025-09-06 05:06:10', '2025-09-06 05:06:10'),
(7, 1, 14, 5, 'تست رفع مشکل', '2025-09-06 05:17:12', '2025-09-06 05:17:12'),
(8, 2, 14, 5, 'تست', '2025-09-06 05:17:40', '2025-09-06 05:17:40');

-- --------------------------------------------------------

--
-- Table structure for table `user_stats`
--

DROP TABLE IF EXISTS `user_stats`;
CREATE TABLE IF NOT EXISTS `user_stats` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `total_ratings` int DEFAULT '0',
  `average_rating` decimal(3,2) DEFAULT '0.00',
  `total_comments` int DEFAULT '0',
  `last_rating_date` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_average_rating` (`average_rating`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_stats`
--

INSERT INTO `user_stats` (`id`, `user_id`, `total_ratings`, `average_rating`, `total_comments`, `last_rating_date`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 4.67, 3, '2025-09-06 05:17:12', '2025-09-06 05:06:10', '2025-09-06 05:17:12'),
(2, 2, 3, 4.33, 3, '2025-09-06 05:17:40', '2025-09-06 05:06:10', '2025-09-06 05:17:40'),
(3, 3, 2, 4.50, 2, '2025-09-06 05:06:10', '2025-09-06 05:06:10', '2025-09-06 05:06:10'),
(4, 4, 0, NULL, 0, NULL, '2025-09-06 05:06:10', '2025-09-06 05:06:10'),
(5, 5, 0, NULL, 0, NULL, '2025-09-06 05:06:10', '2025-09-06 05:06:10'),
(6, 6, 0, NULL, 0, NULL, '2025-09-06 05:06:10', '2025-09-06 05:06:10'),
(7, 7, 0, NULL, 0, NULL, '2025-09-06 05:06:10', '2025-09-06 05:06:10'),
(8, 8, 0, NULL, 0, NULL, '2025-09-06 05:06:10', '2025-09-06 05:06:10'),
(9, 9, 0, NULL, 0, NULL, '2025-09-06 05:06:10', '2025-09-06 05:06:10'),
(10, 10, 0, NULL, 0, NULL, '2025-09-06 05:06:10', '2025-09-06 05:06:10'),
(11, 11, 0, NULL, 0, NULL, '2025-09-06 05:06:10', '2025-09-06 05:06:10'),
(12, 12, 0, NULL, 0, NULL, '2025-09-06 05:06:10', '2025-09-06 05:06:10'),
(13, 13, 0, NULL, 0, NULL, '2025-09-06 05:06:10', '2025-09-06 05:06:10'),
(14, 14, 0, NULL, 0, NULL, '2025-09-06 05:06:10', '2025-09-06 05:06:10'),
(15, 15, 0, 0.00, 0, NULL, '2025-09-06 06:16:09', '2025-09-06 06:16:09'),
(16, 16, 0, 0.00, 0, NULL, '2025-09-06 06:18:22', '2025-09-06 06:18:22'),
(17, 17, 0, 0.00, 0, NULL, '2025-09-06 18:32:49', '2025-09-06 18:32:49'),
(18, 18, 0, 0.00, 0, NULL, '2025-09-06 18:34:54', '2025-09-06 18:34:54'),
(19, 19, 0, 0.00, 0, NULL, '2025-09-06 18:38:30', '2025-09-06 18:38:30'),
(20, 20, 0, 0.00, 0, NULL, '2025-09-06 18:42:43', '2025-09-06 18:42:43'),
(21, 21, 0, 0.00, 0, NULL, '2025-09-06 18:49:21', '2025-09-06 18:49:21');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
