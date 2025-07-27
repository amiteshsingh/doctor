-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 27, 2025 at 10:40 AM
-- Server version: 9.1.0
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `doctor`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone_no` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `profile_pic` varchar(225) DEFAULT NULL,
  `latitude` decimal(9,6) DEFAULT NULL,
  `longitude` decimal(9,6) DEFAULT NULL,
  `hospital_id` int DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `approval_status` int NOT NULL DEFAULT '0',
  `added_on` datetime DEFAULT NULL,
  `added_by` int NOT NULL,
  `updated_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `name`, `phone_no`, `email`, `profile_pic`, `latitude`, `longitude`, `hospital_id`, `status`, `approval_status`, `added_on`, `added_by`, `updated_on`, `updated_by`) VALUES
(1, 'demo Doctor', '9898989898', 'demo@gmail.com', '1753602818_6885db02dbd87.jpg', 0.000000, 0.000000, NULL, 1, 1, '2025-07-13 08:31:48', 1, '2025-07-27 08:07:18', 1);

-- --------------------------------------------------------

--
-- Table structure for table `doctor_availability`
--

CREATE TABLE `doctor_availability` (
  `id` bigint UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `day` varchar(20) NOT NULL,
  `start_time` varchar(100) DEFAULT NULL,
  `end_time` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `doctor_availability`
--

INSERT INTO `doctor_availability` (`id`, `doctor_id`, `day`, `start_time`, `end_time`, `created_at`, `updated_at`) VALUES
(56, 1, 'Monday', '2:56 PM', '2:57 PM', '2025-07-26 10:26:08', '2025-07-26 10:26:08'),
(57, 1, 'Tuesday', '3:04 PM', '3:05 PM', '2025-07-26 10:26:08', '2025-07-26 10:26:08'),
(58, 1, 'Wednesday', '3:05 PM', '3:05 PM', '2025-07-26 10:26:08', '2025-07-26 10:26:08'),
(59, 1, 'Thursday', '3:05 PM', '3:05 PM', '2025-07-26 10:26:08', '2025-07-26 10:26:08'),
(60, 1, 'Friday', '3:05 PM', '3:05 PM', '2025-07-26 10:26:08', '2025-07-26 10:26:08'),
(61, 1, 'Saturday', 'Closed', 'Closed', '2025-07-26 10:26:08', '2025-07-26 10:26:08'),
(62, 1, 'Sunday', 'Closed', 'Closed', '2025-07-26 10:26:08', '2025-07-26 10:26:08');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_educations`
--

CREATE TABLE `doctor_educations` (
  `id` bigint NOT NULL,
  `doctor_id` int DEFAULT NULL,
  `degree_type` varchar(100) DEFAULT NULL,
  `institution_name` varchar(255) DEFAULT NULL,
  `graduation_year` int DEFAULT NULL,
  `details` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `doctor_educations`
--

INSERT INTO `doctor_educations` (`id`, `doctor_id`, `degree_type`, `institution_name`, `graduation_year`, `details`) VALUES
(10, 1, 'hjkjlk', 'gfhjhkl', 3332, 'fghjdsafadsfadssdf');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_languages`
--

CREATE TABLE `doctor_languages` (
  `id` bigint NOT NULL,
  `doctor_id` int DEFAULT NULL,
  `language_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `doctor_languages`
--

INSERT INTO `doctor_languages` (`id`, `doctor_id`, `language_id`) VALUES
(16, 1, 24),
(17, 1, 8);

-- --------------------------------------------------------

--
-- Table structure for table `doctor_locations`
--

CREATE TABLE `doctor_locations` (
  `id` int NOT NULL,
  `doctor_id` int DEFAULT NULL,
  `practice_name` varchar(255) DEFAULT NULL,
  `address` text,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `zip_code` varchar(20) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `website` text,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `doctor_locations`
--

INSERT INTO `doctor_locations` (`id`, `doctor_id`, `practice_name`, `address`, `city`, `state`, `zip_code`, `phone`, `website`, `created_at`, `updated_at`) VALUES
(1, 1, 'Dr. ABC', 'gisdjgkl', 'gfadklgjakl', 'Bihar', '123456', 'gsgderewrw', NULL, '2025-07-16 02:26:02', '2025-07-17 09:35:33');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_specializations`
--

CREATE TABLE `doctor_specializations` (
  `id` bigint NOT NULL,
  `doctor_id` int NOT NULL,
  `specialization_id` int NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `doctor_specializations`
--

INSERT INTO `doctor_specializations` (`id`, `doctor_id`, `specialization_id`, `created_at`) VALUES
(6, 1, 8, '2025-07-13 04:07:24');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hospitals`
--

CREATE TABLE `hospitals` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `phone_no` varchar(20) DEFAULT NULL,
  `address` text NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `city` varchar(45) NOT NULL,
  `state` varchar(45) NOT NULL,
  `zip_code` varchar(45) DEFAULT NULL,
  `latitude` decimal(9,6) DEFAULT NULL,
  `longitude` decimal(9,6) DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `approval_status` int NOT NULL DEFAULT '0',
  `added_on` datetime DEFAULT NULL,
  `updated_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `added_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `hospitals`
--

INSERT INTO `hospitals` (`id`, `name`, `image`, `phone_no`, `address`, `email`, `city`, `state`, `zip_code`, `latitude`, `longitude`, `status`, `approval_status`, `added_on`, `updated_on`, `added_by`, `updated_by`) VALUES
(1, 'AIIMS Delhi', NULL, '011-26588500', 'Ansari Nagar, New Delhi', NULL, 'New Delhi', 'Delhi', '110029', 28.567200, 77.210000, 1, 1, NULL, '2025-06-20 17:58:13', NULL, NULL),
(2, 'Fortis Hospital', NULL, '011-47135000', 'Sector B, Pocket 1, Aruna Asaf Ali Marg', NULL, 'New Delhi', 'Delhi', '110070', 28.513500, 77.167400, 1, 0, NULL, '2025-04-24 18:05:36', NULL, NULL),
(3, 'Apollo Hospital Chennai', NULL, '044-28293333', '21, Greams Lane, Off Greams Road', NULL, 'Chennai', 'Tamil Nadu', '600006', 13.060400, 80.254900, 0, 0, NULL, '2025-04-24 18:05:36', NULL, NULL),
(4, 'Narayana Health Bangalore', NULL, '080-71222222', '258/A, Bommasandra Industrial Area', NULL, 'Bangalore', 'Karnataka', '560099', 12.834200, 77.677000, 0, 0, NULL, '2025-04-24 18:05:36', NULL, NULL),
(5, 'Kokilaben Dhirubhai Ambani Hospital', NULL, '022-30999999', 'Four Bungalows, Andheri West', NULL, 'Mumbai', 'Maharashtra', '400053', 19.134300, 72.827500, 0, 0, NULL, '2025-04-24 18:05:36', NULL, NULL),
(6, 'Tata Memorial Hospital', NULL, '022-24177000', 'Dr. E Borges Road, Parel', NULL, 'Mumbai', 'Maharashtra', '400012', 18.998600, 72.841600, 0, 0, NULL, '2025-04-24 18:05:36', NULL, NULL),
(7, 'Medanta The Medicity', NULL, '0124-4141414', 'CH Baktawar Singh Road, Sector 38', NULL, 'Gurugram', 'Haryana', '122001', 28.459500, 77.026600, 0, 0, NULL, '2025-04-24 18:05:36', NULL, NULL),
(8, 'Max Super Speciality Hospital', NULL, '011-26515050', 'Press Enclave Road, Saket', NULL, 'New Delhi', 'Delhi', '110017', 28.524000, 77.206100, 0, 0, NULL, '2025-04-24 18:05:36', NULL, NULL),
(9, 'CMC Vellore', NULL, '0416-2281000', 'IDA Scudder Road', NULL, 'Vellore', 'Tamil Nadu', '632004', 12.916500, 79.132500, 0, 0, NULL, '2025-04-24 18:05:36', NULL, NULL),
(10, 'Sankara Nethralaya', NULL, '044-28271616', 'College Road, Nungambakkam', NULL, 'Chennai', 'Tamil Nadu', '600006', 13.064500, 80.248400, 0, 0, NULL, '2025-04-24 18:05:36', NULL, NULL),
(11, 'Artemis Hospital', NULL, '0124-4511111', 'Sector 51, Gurgaon', NULL, 'Gurugram', 'Haryana', '122001', 28.430400, 77.048700, 0, 0, NULL, '2025-04-24 18:05:36', NULL, NULL),
(12, 'Columbia Asia Hospital', NULL, '080-61656666', 'Kirloskar Business Park, Bellary Road', NULL, 'Bangalore', 'Karnataka', '560024', 13.037600, 77.592600, 0, 0, NULL, '2025-04-24 18:05:36', NULL, NULL),
(13, 'Rainbow Children’s Hospital', NULL, '040-44442424', 'Banjara Hills, Hyderabad', NULL, 'Hyderabad', 'Telangana', '500034', 17.420800, 78.438300, 0, 0, NULL, '2025-04-24 18:05:36', NULL, NULL),
(14, 'Sir Ganga Ram Hospital', NULL, '011-25750000', 'Rajinder Nagar, New Delhi', NULL, 'New Delhi', 'Delhi', '110060', 28.639800, 77.189600, 0, 0, NULL, '2025-04-24 18:05:36', NULL, NULL),
(15, 'Yashoda Hospital', NULL, '040-45674567', 'Alexander Road, Secunderabad', NULL, 'Hyderabad', 'Telangana', '500003', 17.436100, 78.503500, 0, 0, NULL, '2025-04-24 18:05:36', NULL, NULL),
(16, 'Amrita Institute of Medical Sciences', NULL, '0484-2851234', 'Ponekkara, Kochi', NULL, 'Kochi', 'Kerala', '682041', 10.031800, 76.308700, 0, 0, NULL, '2025-04-24 18:05:36', NULL, NULL),
(17, 'Ruby Hall Clinic', NULL, '020-26163391', '40, Sassoon Road', NULL, 'Pune', 'Maharashtra', '411001', 18.530000, 73.876500, 0, 0, NULL, '2025-04-24 18:05:36', NULL, NULL),
(18, 'Jehangir Hospital', NULL, '020-66819999', '32, Sassoon Road', NULL, 'Pune', 'Maharashtra', '411001', 18.529800, 73.874400, 0, 0, NULL, '2025-05-23 17:56:05', NULL, NULL),
(19, 'BM Birla Heart Research Centre', NULL, '033-30403040', '1/1 National Library Ave, Alipore', NULL, 'Kolkata', 'West Bengal', '700027', 22.537000, 88.329400, 1, 1, NULL, '2025-05-23 17:58:11', NULL, NULL),
(20, 'Woodlands Hospital', NULL, '033-40330000', '8/5, Alipore Road', NULL, 'Kolkata', 'West Bengal', '700027', 22.533300, 88.326500, 1, 1, NULL, '2025-06-20 18:25:56', NULL, NULL),
(25, 'demo hospital', '1753605602_6885e5e2d0cec.webp', '43534532', 'demo address', 'adfgs@gdf.com', 'fgdfgsf', 'gsfgdf', '32433', NULL, NULL, 0, 0, '2025-07-03 00:59:18', '2025-07-27 08:40:02', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `hospital_specializations`
--

CREATE TABLE `hospital_specializations` (
  `id` int NOT NULL,
  `hospital_id` int NOT NULL,
  `specialization_id` int NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `hospital_specializations`
--

INSERT INTO `hospital_specializations` (`id`, `hospital_id`, `specialization_id`, `created_at`) VALUES
(96, 25, 1, '2025-07-04 01:10:20'),
(97, 25, 2, '2025-07-04 01:10:20'),
(98, 25, 3, '2025-07-04 01:10:20'),
(99, 25, 4, '2025-07-04 01:10:20'),
(100, 25, 5, '2025-07-04 01:10:20'),
(101, 25, 6, '2025-07-04 01:10:20'),
(102, 25, 7, '2025-07-04 01:10:20'),
(103, 25, 8, '2025-07-04 01:10:20'),
(104, 25, 9, '2025-07-04 01:10:20'),
(105, 25, 10, '2025-07-04 01:10:20'),
(106, 25, 25, '2025-07-04 01:10:20'),
(107, 25, 26, '2025-07-04 01:10:20'),
(108, 25, 27, '2025-07-04 01:10:20'),
(109, 25, 28, '2025-07-04 01:10:20'),
(110, 25, 29, '2025-07-04 01:10:20'),
(111, 25, 30, '2025-07-04 01:10:20'),
(112, 1, 1, '2025-07-17 04:49:20'),
(113, 1, 2, '2025-07-17 04:49:20'),
(114, 1, 3, '2025-07-17 04:49:20'),
(115, 1, 4, '2025-07-17 04:49:20'),
(116, 1, 5, '2025-07-17 04:49:20'),
(117, 1, 17, '2025-07-17 04:49:20');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
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
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`) VALUES
(13, 'Assamese'),
(3, 'Bengali'),
(24, 'Bhojpuri'),
(20, 'Bodo'),
(18, 'Dogri'),
(1, 'English'),
(8, 'Gujarati'),
(2, 'Hindi'),
(9, 'Kannada'),
(22, 'Kashmiri'),
(16, 'Konkani'),
(14, 'Maithili'),
(11, 'Malayalam'),
(19, 'Manipuri'),
(5, 'Marathi'),
(23, 'Nepali'),
(10, 'Odia'),
(12, 'Punjabi'),
(15, 'Sanskrit'),
(21, 'Santhali'),
(17, 'Sindhi'),
(6, 'Tamil'),
(4, 'Telugu'),
(7, 'Urdu');

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
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_03_27_102609_create_user_roles_table', 2);

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
('FKr2RZD5r007vfNESKzn8SaCZsNmcW9Czf0NX1Ga', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiZDFPTE5jUlVtb0E2Q2JkWGFjN0xaNVZmQ1JUSDcwemhRQ01yMzhwVyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjc6InVzZXJfaWQiO2k6MTtzOjEwOiJ1c2VyX2VtYWlsIjtzOjI2OiJhbWl0ZXNoc2luZ2g5NEBvdXRsb29rLmNvbSI7czo5OiJ1c2VyX3JvbGUiO3M6NToiYWRtaW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM0OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vZG9jdG9yIjt9fQ==', 1753548307),
('wOOE6V6e4KHgA5Ihp4oiFCRMo0ZeO5HRZdnqgR87', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiSEZWUG1GM2tWeXJhRlUwanYyZ2V3WXJrczBhUUlDcmVFNmxmenh0USI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9ob3NwaXRhbC9hZGQ/aWQ9MjUiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NzoidXNlcl9pZCI7aToxO3M6MTA6InVzZXJfZW1haWwiO3M6MjY6ImFtaXRlc2hzaW5naDk0QG91dGxvb2suY29tIjtzOjk6InVzZXJfcm9sZSI7czo1OiJhZG1pbiI7fQ==', 1753605608);

-- --------------------------------------------------------

--
-- Table structure for table `specializations`
--

CREATE TABLE `specializations` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `specializations`
--

INSERT INTO `specializations` (`id`, `name`, `status`) VALUES
(1, 'Cardiology – Heart & blood vessel disorders', 1),
(2, 'Neurology – Brain & nervous system', 1),
(3, 'Orthopedics – Bones, joints & muscles', 1),
(4, 'Gynecology & Obstetrics – Female reproductive health & childbirth', 1),
(5, 'Pediatrics – Child health', 1),
(6, 'Dermatology – Skin, hair & nails', 1),
(7, 'Oncology – Cancer treatment', 1),
(8, 'Gastroenterology – Digestive system', 1),
(9, 'Urology – Urinary tract & male reproductive system', 1),
(10, 'Nephrology – Kidneys', 1),
(11, 'Endocrinology – Hormones & glands', 1),
(12, 'ENT – Ear, Nose & Throat', 1),
(13, 'Pulmonology – Lungs & respiratory system', 1),
(14, 'Rheumatology – Joints, muscles & autoimmune diseases', 1),
(15, 'General Surgery – Common surgical procedures', 1),
(16, 'Plastic Surgery – Cosmetic & reconstructive surgery', 1),
(17, 'Psychiatry – Mental health', 1),
(18, 'Radiology – Imaging (X-ray, MRI, CT)', 1),
(19, 'Anesthesiology – Pain management during surgery', 1),
(20, 'Hematology – Blood disorders', 1),
(21, 'Infectious Diseases – Infections (bacterial, viral, etc.)', 1),
(22, 'Ophthalmology – Eye care and surgery', 1),
(23, 'Dentistry – Oral health', 1),
(24, 'Pathology – Diagnostic lab testing', 1),
(25, 'Emergency Medicine – Urgent and trauma care', 1),
(26, 'Internal Medicine – Adult general health', 1),
(27, 'Critical Care – Intensive Care Unit (ICU)', 1),
(28, 'Geriatrics – Elderly care', 1),
(29, 'Rehabilitation Medicine – Physical recovery and therapy', 1),
(30, 'Allergy & Immunology – Allergies and immune disorders', 1);

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` int NOT NULL,
  `state_name` varchar(100) NOT NULL,
  `state_code` varchar(5) DEFAULT NULL,
  `is_union_territory` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `state_name`, `state_code`, `is_union_territory`) VALUES
(1, 'Andhra Pradesh', 'AP', 0),
(2, 'Arunachal Pradesh', 'AR', 0),
(3, 'Assam', 'AS', 0),
(4, 'Bihar', 'BR', 0),
(5, 'Chhattisgarh', 'CG', 0),
(6, 'Goa', 'GA', 0),
(7, 'Gujarat', 'GJ', 0),
(8, 'Haryana', 'HR', 0),
(9, 'Himachal Pradesh', 'HP', 0),
(10, 'Jharkhand', 'JH', 0),
(11, 'Karnataka', 'KA', 0),
(12, 'Kerala', 'KL', 0),
(13, 'Madhya Pradesh', 'MP', 0),
(14, 'Maharashtra', 'MH', 0),
(15, 'Manipur', 'MN', 0),
(16, 'Meghalaya', 'ML', 0),
(17, 'Mizoram', 'MZ', 0),
(18, 'Nagaland', 'NL', 0),
(19, 'Odisha', 'OR', 0),
(20, 'Punjab', 'PB', 0),
(21, 'Rajasthan', 'RJ', 0),
(22, 'Sikkim', 'SK', 0),
(23, 'Tamil Nadu', 'TN', 0),
(24, 'Telangana', 'TG', 0),
(25, 'Tripura', 'TR', 0),
(26, 'Uttar Pradesh', 'UP', 0),
(27, 'Uttarakhand', 'UK', 0),
(28, 'West Bengal', 'WB', 0),
(29, 'Andaman and Nicobar Islands', 'AN', 1),
(30, 'Chandigarh', 'CH', 1),
(31, 'Dadra and Nagar Haveli and Daman and Diu', 'DN', 1),
(32, 'Delhi', 'DL', 1),
(33, 'Jammu and Kashmir', 'JK', 1),
(34, 'Ladakh', 'LA', 1),
(35, 'Lakshadweep', 'LD', 1),
(36, 'Puducherry', 'PY', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Amitesh Singh', 'amiteshsingh94@outlook.com', NULL, '$2y$12$ohQ1JDBCoQUBef5sLboZA.muGrTSFilE83Xx3NmQHK02nXQLSKlJm', NULL, '2025-03-27 06:32:28', '2025-03-27 06:32:28'),
(3, 'test', 'test@gmail.com', NULL, '$2y$12$ohQ1JDBCoQUBef5sLboZA.muGrTSFilE83Xx3NmQHK02nXQLSKlJm', NULL, '2025-03-27 06:47:49', '2025-03-27 06:47:49'),
(4, 'test1', 'test1@gmail.com', NULL, '$2y$12$ohQ1JDBCoQUBef5sLboZA.muGrTSFilE83Xx3NmQHK02nXQLSKlJm', NULL, '2025-03-29 08:21:05', '2025-03-29 08:21:05');

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `role` enum('admin','doctor') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`id`, `user_id`, `role`, `created_at`, `updated_at`) VALUES
(1, 1, 'admin', '2025-03-27 06:32:28', '2025-03-27 06:32:28'),
(2, 3, 'doctor', '2025-03-27 06:47:49', '2025-03-27 06:47:49'),
(3, 4, 'doctor', '2025-03-29 08:21:05', '2025-03-29 08:21:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone_no`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `hospital_id` (`hospital_id`);

--
-- Indexes for table `doctor_availability`
--
ALTER TABLE `doctor_availability`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctor_educations`
--
ALTER TABLE `doctor_educations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `doctor_languages`
--
ALTER TABLE `doctor_languages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `doctor_locations`
--
ALTER TABLE `doctor_locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `doctor_specializations`
--
ALTER TABLE `doctor_specializations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `specialization_id` (`specialization_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `hospitals`
--
ALTER TABLE `hospitals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone_no`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `hospital_specializations`
--
ALTER TABLE `hospital_specializations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hospital_id` (`hospital_id`),
  ADD KEY `specialization_id` (`specialization_id`);

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
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `specializations`
--
ALTER TABLE `specializations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_roles_user_id_unique` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `doctor_availability`
--
ALTER TABLE `doctor_availability`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `doctor_educations`
--
ALTER TABLE `doctor_educations`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `doctor_languages`
--
ALTER TABLE `doctor_languages`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `doctor_locations`
--
ALTER TABLE `doctor_locations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `doctor_specializations`
--
ALTER TABLE `doctor_specializations`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hospitals`
--
ALTER TABLE `hospitals`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `hospital_specializations`
--
ALTER TABLE `hospital_specializations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `specializations`
--
ALTER TABLE `specializations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `doctor_educations`
--
ALTER TABLE `doctor_educations`
  ADD CONSTRAINT `doctor_educations_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctor_languages`
--
ALTER TABLE `doctor_languages`
  ADD CONSTRAINT `doctor_languages_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `doctor_languages_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctor_locations`
--
ALTER TABLE `doctor_locations`
  ADD CONSTRAINT `doctor_locations_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctor_specializations`
--
ALTER TABLE `doctor_specializations`
  ADD CONSTRAINT `doctor_specializations_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `doctor_specializations_ibfk_2` FOREIGN KEY (`specialization_id`) REFERENCES `specializations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hospital_specializations`
--
ALTER TABLE `hospital_specializations`
  ADD CONSTRAINT `hospital_specializations_ibfk_1` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hospital_specializations_ibfk_2` FOREIGN KEY (`specialization_id`) REFERENCES `specializations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
