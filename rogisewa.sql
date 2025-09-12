-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Sep 12, 2025 at 09:34 PM
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
-- Database: `rogisewa`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone_no` varchar(20) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `profile_pic` varchar(225) DEFAULT NULL,
  `latitude` decimal(9,6) DEFAULT NULL,
  `longitude` decimal(9,6) DEFAULT NULL,
  `hospital_id` int(11) DEFAULT NULL,
  `experience` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `approval_status` int(11) NOT NULL DEFAULT 0,
  `added_on` datetime DEFAULT NULL,
  `added_by` int(11) NOT NULL,
  `updated_on` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `is_professional` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `name`, `phone_no`, `email`, `profile_pic`, `latitude`, `longitude`, `hospital_id`, `experience`, `status`, `approval_status`, `added_on`, `added_by`, `updated_on`, `updated_by`, `gender`, `is_professional`) VALUES
(1, 'demo Doctor', '9898989898', 'demo@gmail.com', '1753602818_6885db02dbd87.jpg', 0.000000, 0.000000, NULL, 2010, 1, 1, '2025-07-13 08:31:48', 1, '2025-07-27 08:07:18', 1, 'Male', 0),
(4, 'Dr. Amitesh  Kumar Singh', '9879879879', 'testdoctor@gmail.com', '1754158558_688e55de360ff.jpg', NULL, NULL, NULL, 2015, 1, 1, '2025-08-02 18:15:58', 3, '2025-09-12 18:36:16', 1, 'Male', 1),
(5, 'Cristina Groves', '35353453453', 'CristinaGroves@gmail.com', '1754162082_688e63a286b67.jpg', NULL, NULL, 25, 1995, 1, 1, '2025-08-02 19:14:42', 3, '2025-08-23 10:32:50', 1, 'Female', 0),
(6, 'Dr. ABCD', '234567890', 'abcd@gmail.com', '1754230353_688f6e5111ed5.jpg', NULL, NULL, 25, 2016, 1, 1, '2025-08-03 14:12:33', 3, '2025-09-12 18:36:02', 1, 'Male', 1),
(7, 'doctor 2', '34253232', 'sdads@gakl.com', '1754232857_688f7819ec1a3.jpg', NULL, NULL, NULL, 2012, 1, 1, '2025-08-03 14:54:17', 3, '2025-09-12 18:35:48', 1, 'Female', 1);

-- --------------------------------------------------------

--
-- Table structure for table `doctor_availability`
--

CREATE TABLE `doctor_availability` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `doctor_id` bigint(20) UNSIGNED NOT NULL,
  `day` varchar(20) NOT NULL,
  `start_time` varchar(100) DEFAULT NULL,
  `end_time` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_availability`
--

INSERT INTO `doctor_availability` (`id`, `doctor_id`, `day`, `start_time`, `end_time`, `created_at`, `updated_at`) VALUES
(63, 1, 'Monday', '2:56 PM', '2:57 PM', '2025-07-31 11:57:25', '2025-07-31 11:57:25'),
(64, 1, 'Tuesday', '3:04 PM', '3:05 PM', '2025-07-31 11:57:25', '2025-07-31 11:57:25'),
(65, 1, 'Wednesday', '3:05 PM', '3:05 PM', '2025-07-31 11:57:25', '2025-07-31 11:57:25'),
(66, 1, 'Thursday', '3:05 PM', '3:05 PM', '2025-07-31 11:57:25', '2025-07-31 11:57:25'),
(67, 1, 'Friday', '3:05 PM', '3:05 PM', '2025-07-31 11:57:25', '2025-07-31 11:57:25'),
(68, 1, 'Saturday', 'Closed', 'Closed', '2025-07-31 11:57:25', '2025-07-31 11:57:25'),
(69, 1, 'Sunday', 'Closed', 'Closed', '2025-07-31 11:57:25', '2025-07-31 11:57:25'),
(91, 5, 'Monday', '3:48 PM', '3:48 PM', '2025-08-16 04:48:16', '2025-08-16 04:48:16'),
(92, 5, 'Tuesday', '3:48 PM', '3:48 PM', '2025-08-16 04:48:16', '2025-08-16 04:48:16'),
(93, 5, 'Wednesday', 'Closed', 'Closed', '2025-08-16 04:48:16', '2025-08-16 04:48:16'),
(94, 5, 'Thursday', 'Closed', 'Closed', '2025-08-16 04:48:16', '2025-08-16 04:48:16'),
(95, 5, 'Friday', 'Closed', 'Closed', '2025-08-16 04:48:16', '2025-08-16 04:48:16'),
(96, 5, 'Saturday', 'Closed', 'Closed', '2025-08-16 04:48:16', '2025-08-16 04:48:16'),
(97, 5, 'Sunday', 'Closed', 'Closed', '2025-08-16 04:48:16', '2025-08-16 04:48:16'),
(105, 7, 'Monday', '3:56 PM', '3:57 PM', '2025-08-23 04:57:51', '2025-08-23 04:57:51'),
(106, 7, 'Tuesday', '3:57 PM', '3:57 PM', '2025-08-23 04:57:51', '2025-08-23 04:57:51'),
(107, 7, 'Wednesday', '3:57 PM', '3:57 PM', '2025-08-23 04:57:51', '2025-08-23 04:57:51'),
(108, 7, 'Thursday', '3:57 PM', '3:57 PM', '2025-08-23 04:57:51', '2025-08-23 04:57:51'),
(109, 7, 'Friday', '3:57 PM', '3:57 PM', '2025-08-23 04:57:51', '2025-08-23 04:57:51'),
(110, 7, 'Saturday', '3:57 PM', '3:57 PM', '2025-08-23 04:57:51', '2025-08-23 04:57:51'),
(111, 7, 'Sunday', 'Closed', 'Closed', '2025-08-23 04:57:51', '2025-08-23 04:57:51'),
(112, 6, 'Monday', '4:10 PM', '4:10 PM', '2025-08-23 05:10:22', '2025-08-23 05:10:22'),
(113, 6, 'Tuesday', '4:10 PM', '4:10 PM', '2025-08-23 05:10:22', '2025-08-23 05:10:22'),
(114, 6, 'Wednesday', '4:10 PM', '4:10 PM', '2025-08-23 05:10:22', '2025-08-23 05:10:22'),
(115, 6, 'Thursday', '4:10 PM', '4:10 PM', '2025-08-23 05:10:22', '2025-08-23 05:10:22'),
(116, 6, 'Friday', '4:10 PM', '4:10 PM', '2025-08-23 05:10:22', '2025-08-23 05:10:22'),
(117, 6, 'Saturday', '4:10 PM', '4:10 PM', '2025-08-23 05:10:22', '2025-08-23 05:10:22'),
(118, 6, 'Sunday', '4:10 PM', '4:10 PM', '2025-08-23 05:10:22', '2025-08-23 05:10:22'),
(119, 4, 'Monday', '8:23 PM', '8:23 PM', '2025-08-23 05:11:33', '2025-08-23 05:11:33'),
(120, 4, 'Tuesday', '8:23 PM', '8:23 PM', '2025-08-23 05:11:33', '2025-08-23 05:11:33'),
(121, 4, 'Wednesday', '8:23 PM', '8:23 PM', '2025-08-23 05:11:33', '2025-08-23 05:11:33'),
(122, 4, 'Thursday', '4:11 PM', '4:11 PM', '2025-08-23 05:11:33', '2025-08-23 05:11:33'),
(123, 4, 'Friday', '4:11 PM', '4:11 PM', '2025-08-23 05:11:33', '2025-08-23 05:11:33'),
(124, 4, 'Saturday', '4:11 PM', '4:11 PM', '2025-08-23 05:11:33', '2025-08-23 05:11:33'),
(125, 4, 'Sunday', '4:11 PM', '4:11 PM', '2025-08-23 05:11:33', '2025-08-23 05:11:33');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_educations`
--

CREATE TABLE `doctor_educations` (
  `id` bigint(20) NOT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `degree_type` varchar(100) DEFAULT NULL,
  `institution_name` varchar(255) DEFAULT NULL,
  `graduation_year` int(11) DEFAULT NULL,
  `details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_educations`
--

INSERT INTO `doctor_educations` (`id`, `doctor_id`, `degree_type`, `institution_name`, `graduation_year`, `details`) VALUES
(27, 7, 'MBBS', 'AIIMS', 2010, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.'),
(28, 6, 'MBBS', 'AIIMS', 2015, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.'),
(33, 4, 'MBBS', 'AIIMS', 2022, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.'),
(35, 1, 'MBBS', 'AIIMS', 2009, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.'),
(36, 5, 'MBBS', 'AIIMS', 1990, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_languages`
--

CREATE TABLE `doctor_languages` (
  `id` bigint(20) NOT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_languages`
--

INSERT INTO `doctor_languages` (`id`, `doctor_id`, `language_id`) VALUES
(64, 7, 3),
(65, 7, 24),
(66, 7, 1),
(67, 7, 2),
(68, 6, 24),
(69, 6, 1),
(70, 6, 2),
(84, 4, 24),
(85, 4, 1),
(86, 4, 2),
(89, 1, 24),
(90, 1, 8),
(91, 5, 3),
(92, 5, 24),
(93, 5, 1),
(94, 5, 2);

-- --------------------------------------------------------

--
-- Table structure for table `doctor_locations`
--

CREATE TABLE `doctor_locations` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `practice_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `zip_code` varchar(20) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `website` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_locations`
--

INSERT INTO `doctor_locations` (`id`, `doctor_id`, `practice_name`, `address`, `city`, `state`, `zip_code`, `phone`, `website`, `created_at`, `updated_at`) VALUES
(1, 1, 'Dr. ABC', 'Gandhi maidan', 'Patna', 'Bihar', '840001', '8768768766', NULL, '2025-07-16 02:26:02', '2025-08-24 16:46:57'),
(2, 4, 'Dr. Amitesh Singh', 'Barauli', 'Gopalganj', 'Bihar', '841405', '9999999999', NULL, '2025-08-03 14:51:43', '2025-08-23 10:58:48'),
(3, 7, 'Dr. doctor 2', 'Ashok Vihar Phase 3', 'Gurgram', 'Haryana', '545456', '999999999', NULL, '2025-08-03 14:55:49', '2025-08-23 10:32:23'),
(4, 5, 'Dr. Cristina Groves', 'Thane', 'Thane', 'Maharashtra', '400066', '986987698', NULL, '2025-08-16 10:18:03', '2025-08-24 17:10:04'),
(5, 6, 'Dr. ABCD', 'Ambendkar Bhawan', 'Gopalganj', 'Bihar', '841405', '9789879879', NULL, '2025-08-23 10:39:54', '2025-08-23 10:39:54');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_specializations`
--

CREATE TABLE `doctor_specializations` (
  `id` bigint(20) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `specialization_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_specializations`
--

INSERT INTO `doctor_specializations` (`id`, `doctor_id`, `specialization_id`, `created_at`) VALUES
(6, 1, 8, '2025-07-13 04:07:24'),
(19, 4, 2, '2025-08-10 05:14:52'),
(20, 4, 3, '2025-08-10 05:14:52'),
(21, 5, 5, '2025-08-16 10:19:05'),
(22, 5, 6, '2025-08-16 10:19:05'),
(25, 7, 3, '2025-08-23 10:17:26'),
(26, 7, 4, '2025-08-23 10:17:26'),
(27, 7, 5, '2025-08-23 10:17:26'),
(28, 7, 6, '2025-08-23 10:17:26'),
(29, 6, 5, '2025-08-23 10:36:42'),
(30, 6, 6, '2025-08-23 10:36:42');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hospitals`
--

CREATE TABLE `hospitals` (
  `id` int(11) NOT NULL,
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
  `status` int(11) NOT NULL DEFAULT 0,
  `about_us` text DEFAULT NULL,
  `approval_status` int(11) NOT NULL DEFAULT 0,
  `added_on` datetime DEFAULT NULL,
  `updated_on` datetime NOT NULL DEFAULT current_timestamp(),
  `added_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hospitals`
--

INSERT INTO `hospitals` (`id`, `name`, `image`, `phone_no`, `address`, `email`, `city`, `state`, `zip_code`, `latitude`, `longitude`, `status`, `about_us`, `approval_status`, `added_on`, `updated_on`, `added_by`, `updated_by`) VALUES
(1, 'AIIMS Delhi', '1756036727_68aafe770b0c0.jpeg', '011-26588500', 'Ansari Nagar, New Delhi', NULL, 'New Delhi', 'Delhi', '110029', 28.567200, 77.210000, 1, NULL, 1, NULL, '2025-08-24 11:58:47', NULL, 1),
(2, 'Fortis Hospital', NULL, '011-47135000', 'Sector B, Pocket 1, Aruna Asaf Ali Marg', NULL, 'New Delhi', 'Delhi', '110070', 28.513500, 77.167400, 1, NULL, 1, NULL, '2025-08-24 11:25:46', NULL, 1),
(3, 'Apollo Hospital Chennai', NULL, '044-28293333', '21, Greams Lane, Off Greams Road', NULL, 'Chennai', 'Tamil Nadu', '600006', 13.060400, 80.254900, 1, NULL, 1, NULL, '2025-08-24 11:17:03', NULL, 1),
(4, 'Narayana Health Bangalore', NULL, '080-71222222', '258/A, Bommasandra Industrial Area', NULL, 'Bangalore', 'Karnataka', '560099', 12.834200, 77.677000, 1, NULL, 1, NULL, '2025-08-24 11:16:25', NULL, 1),
(5, 'Kokilaben Dhirubhai Ambani Hospital', NULL, '022-30999999', 'Four Bungalows, Andheri West', NULL, 'Mumbai', 'Maharashtra', '400053', 19.134300, 72.827500, 1, NULL, 1, NULL, '2025-08-24 11:15:50', NULL, 1),
(6, 'Tata Memorial Hospital', NULL, '022-24177000', 'Dr. E Borges Road, Parel', NULL, 'Mumbai', 'Maharashtra', '400012', 18.998600, 72.841600, 1, NULL, 1, NULL, '2025-08-24 11:15:23', NULL, 1),
(7, 'Medanta The Medicity', NULL, '0124-4141414', 'CH Baktawar Singh Road, Sector 38', NULL, 'Gurugram', 'Haryana', '122001', 28.459500, 77.026600, 1, NULL, 1, NULL, '2025-08-24 11:14:35', NULL, 1),
(8, 'Max Super Speciality Hospital', NULL, '011-26515050', 'Press Enclave Road, Saket', NULL, 'New Delhi', 'Delhi', '110017', 28.524000, 77.206100, 1, NULL, 1, NULL, '2025-08-24 11:13:55', NULL, 1),
(9, 'CMC Vellore', NULL, '0416-2281000', 'IDA Scudder Road', NULL, 'Vellore', 'Tamil Nadu', '632004', 12.916500, 79.132500, 1, NULL, 1, NULL, '2025-08-24 11:13:35', NULL, 1),
(10, 'Sankara Nethralaya', NULL, '044-28271616', 'College Road, Nungambakkam', NULL, 'Chennai', 'Tamil Nadu', '600006', 13.064500, 80.248400, 1, NULL, 1, NULL, '2025-08-24 11:13:23', NULL, 1),
(11, 'Artemis Hospital', NULL, '0124-4511111', 'Sector 51, Gurgaon', NULL, 'Gurugram', 'Haryana', '122001', 28.430400, 77.048700, 1, NULL, 1, NULL, '2025-08-23 11:44:41', NULL, 1),
(12, 'Columbia Asia Hospital', NULL, '080-61656666', 'Kirloskar Business Park, Bellary Road', NULL, 'Bangalore', 'Karnataka', '560024', 13.037600, 77.592600, 1, NULL, 1, NULL, '2025-08-23 11:44:28', NULL, 1),
(13, 'Rainbow Children’s Hospital', NULL, '040-44442424', 'Banjara Hills, Hyderabad', NULL, 'Hyderabad', 'Telangana', '500034', 17.420800, 78.438300, 1, NULL, 1, NULL, '2025-08-23 11:44:02', NULL, 1),
(14, 'Sir Ganga Ram Hospital', NULL, '011-25750000', 'Rajinder Nagar, New Delhi', NULL, 'New Delhi', 'Delhi', '110060', 28.639800, 77.189600, 1, NULL, 1, NULL, '2025-08-23 11:43:46', NULL, 1),
(15, 'Yashoda Hospital', NULL, '040-45674567', 'Alexander Road, Secunderabad', NULL, 'Hyderabad', 'Telangana', '500003', 17.436100, 78.503500, 1, NULL, 1, NULL, '2025-08-23 11:43:25', NULL, 1),
(16, 'Amrita Institute of Medical Sciences', NULL, '0484-2851234', 'Ponekkara, Kochi', NULL, 'Kochi', 'Kerala', '682041', 10.031800, 76.308700, 1, NULL, 1, NULL, '2025-08-23 11:43:03', NULL, 1),
(17, 'Ruby Hall Clinic', NULL, '020-26163391', '40, Sassoon Road', NULL, 'Pune', 'Maharashtra', '411001', 18.530000, 73.876500, 1, NULL, 1, NULL, '2025-08-24 11:11:04', NULL, 1),
(18, 'Jehangir Hospital', NULL, '020-66819999', '32, Sassoon Road', NULL, 'Pune', 'Maharashtra', '411001', 18.529800, 73.874400, 1, NULL, 1, NULL, '2025-08-24 11:10:30', NULL, 1),
(19, 'BM Birla Heart Research Centre', NULL, '033-30403040', '1/1 National Library Ave, Alipore', NULL, 'Kolkata', 'West Bengal', '700027', 22.537000, 88.329400, 1, NULL, 1, NULL, '2025-05-23 17:58:11', NULL, NULL),
(20, 'Woodlands Hospital', NULL, '033-40330000', '8/5, Alipore Road', NULL, 'Kolkata', 'West Bengal', '700027', 22.533300, 88.326500, 1, NULL, 1, NULL, '2025-08-23 11:41:25', NULL, 1),
(25, 'demo hospital', '1753605602_6885e5e2d0cec.webp', '43534532', 'demo address', 'adfgs@gdf.com', 'fgdfgsf', 'gsfgdf', '32433', NULL, NULL, 1, NULL, 1, '2025-07-03 00:59:18', '2025-08-23 11:40:59', NULL, 1),
(26, 'asdfasdf', '1755482438_68a28946daaad.webp', '3456734583', 'sadfasdf', 'sdfas@gmail.com', 'sdfa', 'safasf', '345354', NULL, NULL, 1, NULL, 1, '2025-08-18 02:00:38', '2025-08-31 17:07:29', 3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `hospital_specializations`
--

CREATE TABLE `hospital_specializations` (
  `id` int(11) NOT NULL,
  `hospital_id` int(11) NOT NULL,
  `specialization_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(123, 26, 2, '2025-08-18 16:43:11'),
(124, 26, 3, '2025-08-18 16:43:11'),
(125, 26, 4, '2025-08-18 16:43:11'),
(126, 20, 1, '2025-08-23 11:41:21'),
(127, 20, 11, '2025-08-23 11:41:21'),
(128, 20, 21, '2025-08-23 11:41:21'),
(129, 20, 22, '2025-08-23 11:41:21'),
(130, 20, 24, '2025-08-23 11:41:21'),
(131, 20, 25, '2025-08-23 11:41:21'),
(132, 20, 26, '2025-08-23 11:41:21'),
(133, 20, 27, '2025-08-23 11:41:21'),
(134, 20, 28, '2025-08-23 11:41:21'),
(135, 19, 1, '2025-08-23 11:42:13'),
(136, 19, 2, '2025-08-23 11:42:13'),
(137, 19, 3, '2025-08-23 11:42:13'),
(138, 19, 4, '2025-08-23 11:42:13'),
(139, 19, 5, '2025-08-23 11:42:13'),
(140, 19, 6, '2025-08-23 11:42:13'),
(141, 19, 7, '2025-08-23 11:42:13'),
(142, 19, 8, '2025-08-23 11:42:13'),
(143, 19, 9, '2025-08-23 11:42:13'),
(144, 19, 10, '2025-08-23 11:42:13'),
(145, 19, 11, '2025-08-23 11:42:13'),
(146, 19, 12, '2025-08-23 11:42:13'),
(147, 19, 13, '2025-08-23 11:42:13'),
(148, 19, 14, '2025-08-23 11:42:13'),
(149, 19, 15, '2025-08-23 11:42:13'),
(150, 19, 16, '2025-08-23 11:42:13'),
(151, 19, 17, '2025-08-23 11:42:13'),
(152, 19, 18, '2025-08-23 11:42:13'),
(153, 19, 19, '2025-08-23 11:42:13'),
(154, 19, 20, '2025-08-23 11:42:13'),
(155, 19, 21, '2025-08-23 11:42:13'),
(156, 19, 22, '2025-08-23 11:42:13'),
(157, 19, 23, '2025-08-23 11:42:13'),
(158, 19, 24, '2025-08-23 11:42:13'),
(159, 19, 25, '2025-08-23 11:42:13'),
(160, 19, 26, '2025-08-23 11:42:13'),
(161, 19, 27, '2025-08-23 11:42:13'),
(162, 19, 28, '2025-08-23 11:42:13'),
(163, 19, 29, '2025-08-23 11:42:13'),
(164, 19, 30, '2025-08-23 11:42:13'),
(176, 17, 1, '2025-08-23 11:42:42'),
(177, 17, 2, '2025-08-23 11:42:42'),
(178, 17, 3, '2025-08-23 11:42:42'),
(179, 17, 5, '2025-08-23 11:42:42'),
(180, 17, 24, '2025-08-23 11:42:42'),
(181, 17, 25, '2025-08-23 11:42:42'),
(182, 17, 26, '2025-08-23 11:42:42'),
(183, 17, 27, '2025-08-23 11:42:42'),
(184, 17, 28, '2025-08-23 11:42:42'),
(185, 17, 29, '2025-08-23 11:42:42'),
(186, 17, 30, '2025-08-23 11:42:42'),
(187, 16, 12, '2025-08-23 11:42:57'),
(188, 16, 13, '2025-08-23 11:42:57'),
(189, 16, 14, '2025-08-23 11:42:57'),
(190, 15, 7, '2025-08-23 11:43:19'),
(191, 15, 8, '2025-08-23 11:43:19'),
(192, 15, 13, '2025-08-23 11:43:19'),
(193, 15, 14, '2025-08-23 11:43:19'),
(194, 14, 12, '2025-08-23 11:43:35'),
(195, 14, 13, '2025-08-23 11:43:35'),
(196, 14, 14, '2025-08-23 11:43:35'),
(197, 14, 15, '2025-08-23 11:43:35'),
(198, 14, 16, '2025-08-23 11:43:35'),
(199, 14, 17, '2025-08-23 11:43:35'),
(200, 13, 13, '2025-08-23 11:43:59'),
(201, 13, 14, '2025-08-23 11:43:59'),
(202, 13, 15, '2025-08-23 11:43:59'),
(203, 13, 16, '2025-08-23 11:43:59'),
(204, 12, 9, '2025-08-23 11:44:23'),
(205, 12, 10, '2025-08-23 11:44:23'),
(206, 12, 11, '2025-08-23 11:44:23'),
(207, 11, 3, '2025-08-23 11:44:39'),
(208, 11, 4, '2025-08-23 11:44:39'),
(209, 11, 5, '2025-08-23 11:44:39'),
(210, 10, 17, '2025-08-23 11:45:02'),
(211, 10, 18, '2025-08-23 11:45:02'),
(212, 10, 24, '2025-08-23 11:45:02'),
(213, 10, 25, '2025-08-23 11:45:02'),
(214, 10, 28, '2025-08-23 11:45:02'),
(215, 10, 29, '2025-08-23 11:45:02'),
(216, 10, 30, '2025-08-23 11:45:02'),
(217, 9, 10, '2025-08-23 11:45:11'),
(218, 9, 11, '2025-08-23 11:45:11'),
(219, 9, 12, '2025-08-23 11:45:11'),
(220, 18, 2, '2025-08-24 11:10:22'),
(221, 18, 3, '2025-08-24 11:10:22'),
(222, 18, 4, '2025-08-24 11:10:22'),
(223, 18, 5, '2025-08-24 11:10:22'),
(224, 18, 6, '2025-08-24 11:10:22'),
(225, 18, 7, '2025-08-24 11:10:22'),
(226, 18, 8, '2025-08-24 11:10:22'),
(227, 7, 20, '2025-08-24 11:14:50'),
(228, 7, 21, '2025-08-24 11:14:50'),
(229, 7, 22, '2025-08-24 11:14:50'),
(230, 6, 14, '2025-08-24 11:15:15'),
(231, 6, 15, '2025-08-24 11:15:15'),
(232, 6, 16, '2025-08-24 11:15:15'),
(233, 6, 17, '2025-08-24 11:15:15'),
(234, 4, 9, '2025-08-24 11:16:15'),
(235, 4, 10, '2025-08-24 11:16:15'),
(236, 4, 11, '2025-08-24 11:16:15'),
(237, 4, 12, '2025-08-24 11:16:15'),
(238, 3, 5, '2025-08-24 11:16:47'),
(239, 3, 6, '2025-08-24 11:16:47'),
(240, 3, 9, '2025-08-24 11:16:47'),
(241, 3, 20, '2025-08-24 11:16:47'),
(242, 2, 15, '2025-08-24 11:25:26'),
(243, 2, 17, '2025-08-24 11:25:26'),
(244, 2, 18, '2025-08-24 11:25:26'),
(245, 2, 19, '2025-08-24 11:25:26'),
(246, 1, 1, '2025-08-24 11:25:32'),
(247, 1, 2, '2025-08-24 11:25:32'),
(248, 1, 3, '2025-08-24 11:25:32'),
(249, 1, 4, '2025-08-24 11:25:32'),
(250, 1, 5, '2025-08-24 11:25:32'),
(251, 1, 17, '2025-08-24 11:25:32');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
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
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('3eKAD1hWKC5SvNsxlwyFDgtxWzCwnRGTyxqwSUIb', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoieVd0NUVpS1VnWE5XdmpoYXZJVmJDb3QzM3p3dXpqblZsR3FZcEt0cyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kb2N0b3JzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjc6InVzZXJfaWQiO2k6MTtzOjEwOiJ1c2VyX2VtYWlsIjtzOjI2OiJhbWl0ZXNoc2luZ2g5NEBvdXRsb29rLmNvbSI7czo5OiJ1c2VyX3JvbGUiO3M6NToiYWRtaW4iO30=', 1757705342),
('3yVaQhxbPXTOC7RoU8qDzE9mhEOrHFOti5RuSMEF', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiREhpTm42b1hLd1lMbmt5cUhvbll0d0pMWUJER1R5S1ZXajR3V1dIWiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NzoidXNlcl9pZCI7aToxMTtzOjEwOiJ1c2VyX2VtYWlsIjtzOjE1OiJUZXN0QGtsZmFzZC5jb20iO3M6OToidXNlcl9yb2xlIjtzOjY6ImRvY3RvciI7fQ==', 1757642018),
('7fKlGUeU8YZ7l7gFiYhprqd16xSGtKY11qBDYjP2', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQWU5WE91M0FHaEJMeTBBYkNVZFZUa3VJTUV5QWVrNlE4NG1xNlJyWSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZWdpc3RlciI7fX0=', 1757641689),
('swXgH7hmZaOdgpxLEIGBppHTwYBxdrEBdyGDoEZr', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiOWMxUm10SEVNb3BlcGZSZG9UTW1VajJ4ak1PUGJtaFBtekNVZjNVUCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NzoidXNlcl9pZCI7aTo4O3M6MTA6InVzZXJfZW1haWwiO3M6MTc6ImFtaXRlc2hAZ21haWwuY29tIjtzOjk6InVzZXJfcm9sZSI7czo2OiJkb2N0b3IiO30=', 1757641617),
('tIM1JGMR39Ht72hGApKbR3VPgLWqPfijBNN4PDYq', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiVExFaER4T0EyTWtJNEl6Q0RTdGUxUlBLTkZxaGxiMVpMTE1yMGxLZSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NzoidXNlcl9pZCI7aToxMDtzOjEwOiJ1c2VyX2VtYWlsIjtzOjE0OiJ0ZXN0QGdtYWlsLmNvbSI7czo5OiJ1c2VyX3JvbGUiO3M6NjoiZG9jdG9yIjt9', 1757641954);

-- --------------------------------------------------------

--
-- Table structure for table `specializations`
--

CREATE TABLE `specializations` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `id` int(11) NOT NULL,
  `state_name` varchar(100) NOT NULL,
  `state_code` varchar(5) DEFAULT NULL,
  `is_union_territory` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `pin_code` varchar(10) DEFAULT NULL,
  `phone_no` varchar(20) DEFAULT NULL,
  `profile_image` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `dob`, `gender`, `address`, `state`, `country`, `pin_code`, `phone_no`, `profile_image`) VALUES
(1, 'Amitesh Singh', 'amiteshsingh94@outlook.com', NULL, '$2y$12$ohQ1JDBCoQUBef5sLboZA.muGrTSFilE83Xx3NmQHK02nXQLSKlJm', NULL, '2025-03-27 06:32:28', '2025-03-27 06:32:28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'test', 'doctor@gmail.com', NULL, '$2y$12$UKHVooo2ixZuvyyFk4QwO.2wwHxYYnxa1NghVTzObEDxBKkX/3XkC', NULL, '2025-03-27 06:47:49', '2025-08-31 12:07:54', '1994-03-01', 'Male', 'rupanchhap, barauli', 'bihar', 'india', '841405', '9876987698', '1756659758.jpg'),
(11, 'test', 'Test@klfasd.com', NULL, '$2y$12$6igbCiS9/RaK9W60eczSo.h/G0w7dOpVvkIG5JDyZVWio39h8ZomS', NULL, '2025-09-11 20:23:23', '2025-09-11 20:23:23', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role` enum('admin','doctor') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`id`, `user_id`, `role`, `created_at`, `updated_at`) VALUES
(1, 1, 'admin', '2025-03-27 06:32:28', '2025-03-27 06:32:28'),
(2, 3, 'doctor', '2025-03-27 06:47:49', '2025-03-27 06:47:49'),
(10, 11, 'doctor', '2025-09-11 20:23:23', '2025-09-11 20:23:23');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `doctor_availability`
--
ALTER TABLE `doctor_availability`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT for table `doctor_educations`
--
ALTER TABLE `doctor_educations`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `doctor_languages`
--
ALTER TABLE `doctor_languages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `doctor_locations`
--
ALTER TABLE `doctor_locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `doctor_specializations`
--
ALTER TABLE `doctor_specializations`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hospitals`
--
ALTER TABLE `hospitals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `hospital_specializations`
--
ALTER TABLE `hospital_specializations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=252;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `specializations`
--
ALTER TABLE `specializations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
