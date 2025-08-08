-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 03, 2025 at 11:52 AM
-- Server version: 10.11.13-MariaDB
-- PHP Version: 8.3.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kcgsedub_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_sessions`
--

CREATE TABLE `academic_sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `academic_sessions`
--

INSERT INTO `academic_sessions` (`id`, `name`, `start_date`, `end_date`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '2025', '2025-01-01', '2025-12-31', 1, '2025-08-02 10:46:19', '2025-08-02 10:46:19');

-- --------------------------------------------------------

--
-- Table structure for table `bloods`
--

CREATE TABLE `bloods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bloods`
--

INSERT INTO `bloods` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'A+', '2025-08-02 21:02:45', '2025-08-02 21:02:45'),
(2, 'A-', '2025-08-02 21:02:51', '2025-08-02 21:02:51'),
(3, 'B+', '2025-08-02 21:02:57', '2025-08-02 21:02:57'),
(4, 'B-', '2025-08-02 21:03:03', '2025-08-02 21:03:03'),
(5, 'O+', '2025-08-02 21:03:09', '2025-08-02 21:03:09'),
(6, 'O-', '2025-08-02 21:03:20', '2025-08-02 21:03:20'),
(7, 'AB+', '2025-08-02 21:03:27', '2025-08-02 21:03:27'),
(8, 'AB-', '2025-08-02 21:03:34', '2025-08-02 21:03:34');

-- --------------------------------------------------------

--
-- Table structure for table `class_rooms`
--

CREATE TABLE `class_rooms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_number` int(11) NOT NULL,
  `room_name` varchar(255) DEFAULT NULL,
  `room_type` enum('Lecture','Laboratory','Seminar','Conference') NOT NULL DEFAULT 'Lecture',
  `capacity` int(11) NOT NULL DEFAULT 30,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `location` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_sections`
--

CREATE TABLE `class_sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_class_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `numeric_name` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `class_sections`
--

INSERT INTO `class_sections` (`id`, `school_class_id`, `name`, `numeric_name`, `created_at`, `updated_at`) VALUES
(1, 1, 'A', NULL, '2025-07-29 10:17:55', '2025-07-29 10:17:55'),
(2, 2, 'A', NULL, '2025-08-01 11:48:11', '2025-08-01 11:48:11'),
(3, 3, 'A', NULL, '2025-08-01 11:48:15', '2025-08-01 11:48:15'),
(4, 4, 'A', NULL, '2025-08-01 11:48:19', '2025-08-01 11:48:19'),
(5, 5, 'A', NULL, '2025-08-01 11:48:21', '2025-08-01 11:48:21'),
(6, 6, 'A', NULL, '2025-08-01 11:48:26', '2025-08-01 11:48:26'),
(7, 7, 'A', NULL, '2025-08-01 11:48:31', '2025-08-01 11:48:31'),
(8, 8, 'A', NULL, '2025-08-01 11:48:34', '2025-08-01 11:48:34');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `designations`
--

CREATE TABLE `designations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE `exams` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `academic_session_id` bigint(20) UNSIGNED NOT NULL,
  `exam_category_id` bigint(20) UNSIGNED NOT NULL,
  `start_at` date NOT NULL,
  `end_at` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exams`
--

INSERT INTO `exams` (`id`, `academic_session_id`, `exam_category_id`, `start_at`, `end_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-06-24', '2025-06-30', '2025-08-03 10:32:42', '2025-08-03 10:32:42');

-- --------------------------------------------------------

--
-- Table structure for table `exam_categories`
--

CREATE TABLE `exam_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exam_categories`
--

INSERT INTO `exam_categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Half yearly', 'half-yearly', '2025-08-02 21:22:41', '2025-08-02 21:22:41'),
(2, 'Annual', 'annual', '2025-08-02 21:22:47', '2025-08-02 21:22:47');

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
-- Table structure for table `final_mark_configurations`
--

CREATE TABLE `final_mark_configurations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_class_id` bigint(20) UNSIGNED NOT NULL,
  `subject_id` bigint(20) UNSIGNED NOT NULL,
  `class_test_total` int(10) UNSIGNED NOT NULL DEFAULT 20,
  `other_parts_total` int(10) UNSIGNED NOT NULL DEFAULT 100,
  `final_result_weight_percentage` int(10) UNSIGNED NOT NULL DEFAULT 80,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `genders`
--

CREATE TABLE `genders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `genders`
--

INSERT INTO `genders` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Male', '2025-08-02 21:02:17', '2025-08-02 21:02:17'),
(2, 'Female', '2025-08-02 21:02:20', '2025-08-02 21:02:20'),
(3, 'Other', '2025-08-02 21:02:24', '2025-08-02 21:02:24');

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `grade_name` varchar(255) NOT NULL,
  `grade_point` decimal(4,2) NOT NULL,
  `start_marks` int(11) NOT NULL,
  `end_marks` int(11) NOT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`id`, `grade_name`, `grade_point`, `start_marks`, `end_marks`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'A+', 5.00, 80, 100, NULL, '2025-08-03 15:46:31', '2025-08-03 15:46:31'),
(2, 'A', 4.00, 70, 79, NULL, '2025-08-03 15:46:54', '2025-08-03 15:46:54'),
(3, 'A-', 3.50, 60, 69, NULL, '2025-08-03 15:47:12', '2025-08-03 15:47:12'),
(4, 'B', 3.00, 50, 59, NULL, '2025-08-03 15:47:27', '2025-08-03 15:47:27'),
(5, 'C', 2.00, 40, 49, NULL, '2025-08-03 15:47:45', '2025-08-03 15:47:45'),
(6, 'D', 1.00, 33, 39, NULL, '2025-08-03 15:48:03', '2025-08-03 15:48:03'),
(7, 'F', 0.00, 0, 32, NULL, '2025-08-03 15:48:15', '2025-08-03 15:48:15');

-- --------------------------------------------------------

--
-- Table structure for table `guardians`
--

CREATE TABLE `guardians` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `national_id` varchar(255) DEFAULT NULL,
  `place_of_birth` varchar(255) DEFAULT NULL,
  `nationality` varchar(255) DEFAULT NULL,
  `language` varchar(255) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `guardians`
--

INSERT INTO `guardians` (`id`, `user_id`, `phone`, `address`, `date_of_birth`, `occupation`, `national_id`, `place_of_birth`, `nationality`, `language`, `profile_picture`, `is_active`, `notes`, `created_at`, `updated_at`) VALUES
(1, 3, '01911418642', '1 Sib bari more', '1978-12-02', 'Farmer', '45790897676', 'Khulna', 'Bangaldeshi', 'Bangla', 'guardians/profile/0yeY75Y2YxvxOZCw5YlofN5yMxmNrn3SB1AE5649.jpg', 1, NULL, '2025-07-19 09:41:53', '2025-07-19 09:41:53');

-- --------------------------------------------------------

--
-- Table structure for table `mark_distributions`
--

CREATE TABLE `mark_distributions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mark_distributions`
--

INSERT INTO `mark_distributions` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Class Test', '2025-08-02 20:59:59', '2025-08-02 20:59:59'),
(2, 'Written', '2025-08-02 21:00:37', '2025-08-02 21:00:37'),
(3, 'MCQ', '2025-08-02 21:00:40', '2025-08-02 21:00:40'),
(4, 'Practical', '2025-08-02 21:00:45', '2025-08-02 21:00:45');

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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_07_13_044051_create_school_classes_table', 2),
(6, '2025_07_13_044801_create_class_sections_table', 2),
(7, '2025_07_13_050658_create_shifts_table', 2),
(8, '2025_07_13_050727_create_subjects_table', 2),
(9, '2025_07_13_051153_create_subject_assigns_table', 2),
(10, '2025_07_13_051820_create_subject_assign_items_table', 2),
(11, '2025_07_13_052940_create_designations_table', 2),
(12, '2025_07_13_060438_create_departments_table', 2),
(13, '2025_07_13_060612_create_genders_table', 2),
(14, '2025_07_13_060616_create_bloods_table', 2),
(15, '2025_07_13_060626_create_religions_table', 2),
(17, '2025_07_13_061722_create_students_table', 2),
(20, '2025_07_13_061903_create_guardians_table', 3),
(21, '2025_07_13_061644_create_staff_table', 4),
(22, '2025_07_20_173308_create_class_rooms_table', 5),
(23, '2025_07_24_042430_create_academic_sessions_table', 6),
(24, '2025_07_24_053104_add_academic_session_id_to_students_table', 6),
(25, '2025_07_24_060518_create_exam_categories_table', 6),
(26, '2025_07_24_090411_create_exams_table', 6),
(27, '2025_07_28_053829_create_mark_distributions_table', 7),
(28, '2025_07_28_053839_create_subject_mark_distributions_table', 7),
(29, '2025_07_29_060015_create_final_mark_configurations_table', 7),
(30, '2025_07_30_061235_create_grades_table', 8),
(31, '2025_07_30_062918_create_student_marks_table', 8);

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
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `religions`
--

CREATE TABLE `religions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `school_classes`
--

CREATE TABLE `school_classes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `numeric_name` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `school_classes`
--

INSERT INTO `school_classes` (`id`, `name`, `numeric_name`, `description`, `order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Three', 3, NULL, 0, 1, '2025-07-13 09:16:29', '2025-08-01 11:45:52'),
(2, 'Four', 4, NULL, 1, 1, '2025-07-13 09:19:43', '2025-08-01 11:46:00'),
(3, 'Five', 5, NULL, 2, 1, '2025-08-01 11:46:09', '2025-08-01 11:46:09'),
(4, 'Six', 6, NULL, 3, 1, '2025-08-01 11:46:16', '2025-08-01 11:46:16'),
(5, 'Seven', 7, NULL, 4, 1, '2025-08-01 11:46:26', '2025-08-01 11:46:26'),
(6, 'Eight', 8, NULL, 5, 1, '2025-08-01 11:46:33', '2025-08-01 11:46:33'),
(7, 'Nine', 9, NULL, 6, 1, '2025-08-01 11:46:43', '2025-08-01 11:46:43'),
(8, 'Ten', 10, NULL, 7, 1, '2025-08-01 11:46:50', '2025-08-01 11:46:50');

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

CREATE TABLE `shifts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `staff_id` varchar(255) NOT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `nid` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `current_address` varchar(255) DEFAULT NULL,
  `permanent_address` varchar(255) DEFAULT NULL,
  `designation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `gender_id` bigint(20) UNSIGNED DEFAULT NULL,
  `marital_status` enum('single','married','divorced','widowed') DEFAULT 'single',
  `basic_salary` decimal(10,2) DEFAULT NULL,
  `date_of_joining` date DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `user_id`, `staff_id`, `department_id`, `first_name`, `last_name`, `father_name`, `mother_name`, `email`, `phone`, `nid`, `date_of_birth`, `current_address`, `permanent_address`, `designation_id`, `gender_id`, `marital_status`, `basic_salary`, `date_of_joining`, `profile_picture`, `created_at`, `updated_at`) VALUES
(5, 9, 'STF-001', NULL, 'John', 'Doe', NULL, NULL, NULL, '1710000000', '1234567890', '1970-01-01', 'Dhaka', 'Barisal', NULL, NULL, 'married', 35000.00, '1970-01-01', NULL, '2025-07-30 11:41:24', '2025-07-30 11:43:29'),
(6, 10, '1', NULL, 'Soiyoda', 'Jahan', NULL, NULL, 'israt.ku.11@gmail.com', '01708447143', '5955632301', '1993-03-01', 'Khalishpur, khulna. ', 'Khalishpur, khulna', NULL, NULL, 'married', 16000.00, NULL, NULL, '2025-08-01 10:23:32', '2025-08-01 10:23:32'),
(7, 11, '2', NULL, 'Sheikh', 'Uzzal', NULL, NULL, 'uzzalmd2002@gmail.com', '01799031606', '4110463275162', '1989-02-11', 'Khalishpur,khulna', 'Siddipasha,abahyanagar,jashore', NULL, NULL, 'married', 16000.00, NULL, NULL, '2025-08-01 10:27:40', '2025-08-01 10:27:40'),
(8, 12, '3', NULL, 'Dinesh', 'Mollick', NULL, NULL, 'dineshmallick@099gmail.com', '01940847700', '4711271808944', '1984-12-10', 'Village : Sailmary, Post office :Koiya bazar, Upazila :Batiaghata, District : Khulna, Bangladesh ', 'Village :Sailmary, Post office :Koiya Bazar, Upazila : Batiaghata, District :Khulna, Bangladesh ', NULL, NULL, 'married', 16000.00, NULL, NULL, '2025-08-01 10:31:59', '2025-08-01 10:31:59'),
(9, 13, '4', NULL, 'Mukundo', 'Mondal', NULL, NULL, NULL, '01918091127', NULL, NULL, NULL, NULL, NULL, NULL, 'single', 12500.00, NULL, NULL, '2025-08-01 10:33:33', '2025-08-01 10:33:33'),
(10, 14, '5', NULL, 'Farjana', 'Yeasmin', NULL, NULL, 'farzananu12@gmail.com', '01928474892', '9134462937', '1994-02-08', 'Soyghoria, Batiaghata ', 'Soyghoria, Batiaghata ', NULL, NULL, 'single', 12500.00, NULL, NULL, '2025-08-01 10:37:43', '2025-08-01 10:37:43'),
(11, 15, '6', NULL, 'Sumaiya', 'Akter', NULL, NULL, NULL, '01980038225', NULL, NULL, NULL, NULL, NULL, NULL, 'single', 16000.00, NULL, NULL, '2025-08-01 11:10:55', '2025-08-01 11:10:55'),
(12, 16, '7', NULL, 'Md', 'Hasan', NULL, NULL, 'kamil161.ru@gmail.com', '01946425294', '8702500193', '2007-02-20', 'Khalishpur, Khulna', 'Khalishpur, Khulna', NULL, NULL, 'single', 16000.00, NULL, NULL, '2025-08-01 11:13:08', '2025-08-01 11:20:51'),
(13, 17, '8', NULL, 'Madhusudan', 'Gain', NULL, NULL, 'madhusudan.gain@gmail.com', '01930574330', '1900922137', NULL, '85/5, 1 no bankers goli, Bagmara, Khulna', 'Shalabunia, Digraj, Mongla, Bagerhat', NULL, NULL, 'married', 12500.00, NULL, NULL, '2025-08-01 11:24:06', '2025-08-01 11:24:06'),
(14, 18, '9', NULL, 'Alamin', 'Hazra', NULL, NULL, NULL, '01754448454', NULL, NULL, NULL, NULL, NULL, NULL, 'single', 12500.00, NULL, NULL, '2025-08-01 11:25:09', '2025-08-01 11:25:09'),
(15, 19, '10', NULL, 'Sukurunneca', 'Islam', NULL, NULL, 'sukurunnesaislamtitly@gmail.com', '01937701389', '8252972750', '1997-12-30', '3,Nazir ghat cross road,Baniakhamar, Khulna', '3,Nazir ghat cross road,Baniakhamar, Khulna', NULL, NULL, 'single', 12500.00, NULL, NULL, '2025-08-01 11:30:26', '2025-08-01 11:30:26'),
(16, 20, '11', NULL, 'Moumita', 'Roy', NULL, NULL, 'moumita_m200958@ku.ac.bd', '01780301098', '1507137501', '1997-12-30', 'Nirjon Abashik, Nirala', 'Tildanga, Dacope Khulna', NULL, NULL, 'married', 16000.00, NULL, NULL, '2025-08-01 11:33:11', '2025-08-01 11:33:35'),
(17, 21, '12', NULL, 'Mosarof', 'Hossen', NULL, NULL, NULL, '01911898227', NULL, NULL, NULL, NULL, NULL, NULL, 'single', 6000.00, NULL, NULL, '2025-08-01 11:34:43', '2025-08-01 11:34:43'),
(18, 22, '13', NULL, 'Radita', 'Islam', NULL, NULL, 'raditaruthba@gmail.com', '01903462252', '8702865059', '1997-08-17', 'House no-45,Charerhat main road,Khalishpur,Khulna', 'House no-45,Charerhat main road,Khalishpur,Khulna', NULL, NULL, 'married', 6000.00, NULL, NULL, '2025-08-01 11:36:38', '2025-08-01 11:36:38'),
(19, 23, '14', NULL, 'Md', 'Rahoman', NULL, NULL, NULL, '01720929459', NULL, NULL, NULL, NULL, NULL, NULL, 'single', 4000.00, NULL, NULL, '2025-08-01 11:39:16', '2025-08-01 11:39:16'),
(20, 24, '15', NULL, 'Chisty', 'Mahomud', NULL, NULL, NULL, '01984711871', NULL, NULL, NULL, NULL, NULL, NULL, 'single', NULL, NULL, NULL, '2025-08-01 11:39:51', '2025-08-01 11:40:02');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `roll_number` varchar(255) DEFAULT NULL,
  `school_class_id` bigint(20) UNSIGNED NOT NULL,
  `class_section_id` bigint(20) UNSIGNED DEFAULT NULL,
  `shift_id` bigint(20) UNSIGNED DEFAULT NULL,
  `guardian_id` bigint(20) UNSIGNED DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `admission_date` date DEFAULT NULL,
  `admission_number` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `gender_id` bigint(20) UNSIGNED DEFAULT NULL,
  `blood_id` bigint(20) UNSIGNED DEFAULT NULL,
  `religion_id` bigint(20) UNSIGNED DEFAULT NULL,
  `national_id` varchar(255) DEFAULT NULL,
  `place_of_birth` varchar(255) DEFAULT NULL,
  `nationality` varchar(255) DEFAULT NULL,
  `language` varchar(255) DEFAULT NULL,
  `health_status` text DEFAULT NULL,
  `rank_in_family` int(11) DEFAULT NULL,
  `number_of_siblings` int(11) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_contact_phone` varchar(255) DEFAULT NULL,
  `previous_school_attended` tinyint(1) NOT NULL DEFAULT 0,
  `previous_school` varchar(255) DEFAULT NULL,
  `previous_school_document` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `academic_session_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `user_id`, `roll_number`, `school_class_id`, `class_section_id`, `shift_id`, `guardian_id`, `email`, `phone`, `address`, `date_of_birth`, `admission_date`, `admission_number`, `category`, `gender_id`, `blood_id`, `religion_id`, `national_id`, `place_of_birth`, `nationality`, `language`, `health_status`, `rank_in_family`, `number_of_siblings`, `profile_picture`, `emergency_contact_name`, `emergency_contact_phone`, `previous_school_attended`, `previous_school`, `previous_school_document`, `is_active`, `notes`, `created_at`, `updated_at`, `academic_session_id`) VALUES
(2, 25, '3001', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:39:50', '2025-08-02 10:47:43', 1),
(3, 26, '3002', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:39:50', '2025-08-02 10:47:43', 1),
(4, 27, '3003', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:39:50', '2025-08-02 10:47:43', 1),
(5, 28, '3004', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:39:51', '2025-08-02 10:47:43', 1),
(6, 29, '3005', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:39:51', '2025-08-02 10:47:43', 1),
(7, 30, '3006', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:39:51', '2025-08-02 10:47:43', 1),
(8, 31, '3007', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:39:51', '2025-08-02 10:47:43', 1),
(9, 32, '3008', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:39:51', '2025-08-02 10:47:43', 1),
(10, 33, '3009', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:39:52', '2025-08-02 10:47:43', 1),
(11, 34, '3010', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:39:52', '2025-08-02 10:47:43', 1),
(12, 35, '3011', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:39:52', '2025-08-02 10:47:43', 1),
(13, 36, '3012', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:39:52', '2025-08-02 10:47:43', 1),
(14, 37, '3013', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:39:53', '2025-08-02 10:47:43', 1),
(15, 38, '3014', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:39:53', '2025-08-02 10:47:43', 1),
(16, 39, '3015', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:39:53', '2025-08-02 10:47:43', 1),
(17, 40, '3016', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:39:53', '2025-08-02 10:47:43', 1),
(18, 41, '3017', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:39:53', '2025-08-02 10:47:43', 1),
(19, 42, '3018', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:39:54', '2025-08-02 10:47:43', 1),
(20, 43, '3019', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:39:54', '2025-08-02 10:47:43', 1),
(21, 44, '3020', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:39:54', '2025-08-02 10:47:43', 1),
(22, 45, '3021', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:39:54', '2025-08-02 10:47:43', 1),
(23, 46, '3022', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:39:54', '2025-08-02 10:47:43', 1),
(24, 47, '3023', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:39:55', '2025-08-02 10:47:43', 1),
(25, 48, '3024', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:39:55', '2025-08-02 10:47:43', 1),
(26, 49, '3025', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:39:55', '2025-08-02 10:47:43', 1),
(27, 50, '3026', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:39:55', '2025-08-02 10:47:43', 1),
(28, 51, '3027', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:39:55', '2025-08-02 10:47:43', 1),
(29, 52, '3028', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:39:56', '2025-08-02 10:47:43', 1),
(30, 53, '3029', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:39:56', '2025-08-02 10:47:43', 1),
(31, 54, '3030', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:39:56', '2025-08-02 10:47:43', 1),
(32, 55, '3031', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:39:56', '2025-08-02 10:47:43', 1),
(33, 56, '4001', 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:44:56', '2025-08-02 10:47:43', 1),
(34, 57, '4002', 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:44:56', '2025-08-02 10:47:43', 1),
(35, 58, '4003', 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:44:56', '2025-08-02 10:47:43', 1),
(36, 59, '4004', 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:44:57', '2025-08-02 10:47:43', 1),
(37, 60, '4005', 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:44:57', '2025-08-02 10:47:43', 1),
(38, 61, '4006', 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:44:57', '2025-08-02 10:47:43', 1),
(39, 62, '4007', 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:44:57', '2025-08-02 10:47:43', 1),
(40, 63, '4008', 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:44:57', '2025-08-02 10:47:43', 1),
(41, 64, '4009', 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:44:58', '2025-08-02 10:47:43', 1),
(42, 65, '4010', 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:44:58', '2025-08-02 10:47:43', 1),
(43, 66, '4011', 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:44:58', '2025-08-02 10:47:43', 1),
(44, 67, '4012', 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:44:58', '2025-08-02 10:47:43', 1),
(45, 68, '4013', 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:44:58', '2025-08-02 10:47:43', 1),
(46, 69, '4014', 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:44:59', '2025-08-02 10:47:43', 1),
(47, 70, '4015', 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:44:59', '2025-08-02 10:47:43', 1),
(48, 71, '4016', 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:44:59', '2025-08-02 10:47:43', 1),
(49, 72, '4017', 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:44:59', '2025-08-02 10:47:43', 1),
(50, 73, '4018', 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:44:59', '2025-08-02 10:47:43', 1),
(51, 74, '4019', 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:45:00', '2025-08-02 10:47:43', 1),
(52, 75, '4020', 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:45:00', '2025-08-02 10:47:43', 1),
(53, 76, '4021', 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:45:00', '2025-08-02 10:47:43', 1),
(54, 77, '4022', 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:45:00', '2025-08-02 10:47:43', 1),
(55, 78, '4023', 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:45:00', '2025-08-02 10:47:43', 1),
(56, 79, '4024', 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:45:01', '2025-08-02 10:47:43', 1),
(57, 80, '4025', 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:45:01', '2025-08-02 10:47:43', 1),
(58, 81, '4026', 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:45:01', '2025-08-02 10:47:43', 1),
(59, 82, '4027', 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:45:01', '2025-08-02 10:47:43', 1),
(60, 83, '5001', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:26', '2025-08-02 10:47:43', 1),
(61, 84, '5002', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:26', '2025-08-02 10:47:43', 1),
(62, 85, '5003', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:27', '2025-08-02 10:47:43', 1),
(63, 86, '5004', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:27', '2025-08-02 10:47:43', 1),
(64, 87, '5005', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:27', '2025-08-02 10:47:43', 1),
(65, 88, '5006', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:27', '2025-08-02 10:47:43', 1),
(66, 89, '5007', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:27', '2025-08-02 10:47:43', 1),
(67, 90, '5008', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:28', '2025-08-02 10:47:43', 1),
(68, 91, '5009', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:28', '2025-08-02 10:47:43', 1),
(69, 92, '5010', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:28', '2025-08-02 10:47:43', 1),
(70, 93, '5011', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:28', '2025-08-02 10:47:43', 1),
(71, 94, '5012', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:29', '2025-08-02 10:47:43', 1),
(72, 95, '5013', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:29', '2025-08-02 10:47:43', 1),
(73, 96, '5014', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:29', '2025-08-02 10:47:43', 1),
(74, 97, '5015', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:29', '2025-08-02 10:47:43', 1),
(75, 98, '5016', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:29', '2025-08-02 10:47:43', 1),
(76, 99, '5017', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:30', '2025-08-02 10:47:43', 1),
(77, 100, '5018', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:30', '2025-08-02 10:47:43', 1),
(78, 101, '5019', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:30', '2025-08-02 10:47:43', 1),
(79, 102, '5020', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:30', '2025-08-02 10:47:43', 1),
(80, 103, '5021', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:30', '2025-08-02 10:47:43', 1),
(81, 104, '5022', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:31', '2025-08-02 10:47:43', 1),
(82, 105, '5023', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:31', '2025-08-02 10:47:43', 1),
(83, 106, '5024', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:31', '2025-08-02 10:47:43', 1),
(84, 107, '5025', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:31', '2025-08-02 10:47:43', 1),
(85, 108, '5026', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:31', '2025-08-02 10:47:43', 1),
(86, 109, '5027', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:32', '2025-08-02 10:47:43', 1),
(87, 110, '5028', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:32', '2025-08-02 10:47:43', 1),
(88, 111, '5029', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:32', '2025-08-02 10:47:43', 1),
(89, 112, '5030', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:32', '2025-08-02 10:47:43', 1),
(90, 113, '5031', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:32', '2025-08-02 10:47:43', 1),
(91, 114, '5032', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:33', '2025-08-02 10:47:43', 1),
(92, 115, '5033', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:33', '2025-08-02 10:47:43', 1),
(93, 116, '5034', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:33', '2025-08-02 10:47:43', 1),
(94, 117, '5035', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:33', '2025-08-02 10:47:43', 1),
(95, 118, '5036', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:34', '2025-08-02 10:47:43', 1),
(96, 119, '5037', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:34', '2025-08-02 10:47:43', 1),
(97, 120, '5038', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:34', '2025-08-02 10:47:43', 1),
(98, 121, '5039', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:34', '2025-08-02 10:47:43', 1),
(99, 122, '5040', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:34', '2025-08-02 10:47:43', 1),
(100, 123, '5041', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:35', '2025-08-02 10:47:43', 1),
(101, 124, '5042', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:35', '2025-08-02 10:47:43', 1),
(102, 125, '5043', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:35', '2025-08-02 10:47:43', 1),
(103, 126, '5044', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:35', '2025-08-02 10:47:43', 1),
(104, 127, '5045', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:35', '2025-08-02 10:47:43', 1),
(105, 128, '5046', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:36', '2025-08-02 10:47:43', 1),
(106, 129, '5047', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:36', '2025-08-02 10:47:43', 1),
(107, 130, '5048', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:36', '2025-08-02 10:47:43', 1),
(108, 131, '5049', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:36', '2025-08-02 10:47:43', 1),
(109, 132, '5050', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:36', '2025-08-02 10:47:43', 1),
(110, 133, '5051', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:37', '2025-08-02 10:47:43', 1),
(111, 134, '5052', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:49:37', '2025-08-02 10:47:43', 1),
(112, 135, '6001', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:41', '2025-08-02 10:47:43', 1),
(113, 136, '6002', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:41', '2025-08-02 10:47:43', 1),
(114, 137, '6003', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:41', '2025-08-02 10:47:43', 1),
(115, 138, '6004', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:41', '2025-08-02 10:47:43', 1),
(116, 139, '6005', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:42', '2025-08-02 10:47:43', 1),
(117, 140, '6006', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:42', '2025-08-02 10:47:43', 1),
(118, 141, '6007', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:42', '2025-08-02 10:47:43', 1),
(119, 142, '6008', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:42', '2025-08-02 10:47:43', 1),
(120, 143, '6009', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:42', '2025-08-02 10:47:43', 1),
(121, 144, '6010', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:43', '2025-08-02 10:47:43', 1),
(122, 145, '6011', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:43', '2025-08-02 10:47:43', 1),
(123, 146, '6012', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:43', '2025-08-02 10:47:43', 1),
(124, 147, '6013', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:43', '2025-08-02 10:47:43', 1),
(125, 148, '6014', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:43', '2025-08-02 10:47:43', 1),
(126, 149, '6015', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:44', '2025-08-02 10:47:43', 1),
(127, 150, '6016', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:44', '2025-08-02 10:47:43', 1),
(128, 151, '6017', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:44', '2025-08-02 10:47:43', 1),
(129, 152, '6018', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:44', '2025-08-02 10:47:43', 1),
(130, 153, '6019', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:44', '2025-08-02 10:47:43', 1),
(131, 154, '6020', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:45', '2025-08-02 10:47:43', 1),
(132, 155, '6021', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:45', '2025-08-02 10:47:43', 1),
(133, 156, '6022', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:45', '2025-08-02 10:47:43', 1),
(134, 157, '6023', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:45', '2025-08-02 10:47:43', 1),
(135, 158, '6024', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:46', '2025-08-02 10:47:43', 1),
(136, 159, '6025', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:46', '2025-08-02 10:47:43', 1),
(137, 160, '6026', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:46', '2025-08-02 10:47:43', 1),
(138, 161, '6027', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:46', '2025-08-02 10:47:43', 1),
(139, 162, '6028', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:46', '2025-08-02 10:47:43', 1),
(140, 163, '6029', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:47', '2025-08-02 10:47:43', 1),
(141, 164, '6030', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:47', '2025-08-02 10:47:43', 1),
(142, 165, '6031', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:47', '2025-08-02 10:47:43', 1),
(143, 166, '6032', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:47', '2025-08-02 10:47:43', 1),
(144, 167, '6033', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:47', '2025-08-02 10:47:43', 1),
(145, 168, '6034', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:48', '2025-08-02 10:47:43', 1),
(146, 169, '6035', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:48', '2025-08-02 10:47:43', 1),
(147, 170, '6036', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:48', '2025-08-02 10:47:43', 1),
(148, 171, '6037', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:48', '2025-08-02 10:47:43', 1),
(149, 172, '6038', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:49', '2025-08-02 10:47:43', 1),
(150, 173, '6039', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:49', '2025-08-02 10:47:43', 1),
(151, 174, '6040', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:49', '2025-08-02 10:47:43', 1),
(152, 175, '6041', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:49', '2025-08-02 10:47:43', 1),
(153, 176, '6042', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:49', '2025-08-02 10:47:44', 1),
(154, 177, '6043', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:50', '2025-08-02 10:47:44', 1),
(155, 178, '6044', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:50', '2025-08-02 10:47:44', 1),
(156, 179, '6045', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:50', '2025-08-02 10:47:44', 1),
(157, 180, '6046', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:50', '2025-08-02 10:47:44', 1),
(158, 181, '6047', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:51', '2025-08-02 10:47:44', 1),
(159, 182, '6048', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:51', '2025-08-02 10:47:44', 1),
(160, 183, '6049', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:51', '2025-08-02 10:47:44', 1),
(161, 184, '6050', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:51', '2025-08-02 10:47:44', 1),
(162, 185, '6051', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:51', '2025-08-02 10:47:44', 1),
(163, 186, '6052', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:52', '2025-08-02 10:47:44', 1),
(164, 187, '6053', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:52', '2025-08-02 10:47:44', 1),
(165, 188, '6054', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:52', '2025-08-02 10:47:44', 1),
(166, 189, '6055', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:52', '2025-08-02 10:47:44', 1),
(167, 190, '6056', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:53', '2025-08-02 10:47:44', 1),
(168, 191, '6057', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:53', '2025-08-02 10:47:44', 1),
(169, 192, '6058', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:53', '2025-08-02 10:47:44', 1),
(170, 193, '6059', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:53', '2025-08-02 10:47:44', 1),
(171, 194, '6060', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:53', '2025-08-02 10:47:44', 1),
(172, 195, '6061', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:54', '2025-08-02 10:47:44', 1),
(173, 196, '6062', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:54', '2025-08-02 10:47:44', 1),
(174, 197, '6063', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:54', '2025-08-02 10:47:44', 1),
(175, 198, '6064', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:54', '2025-08-02 10:47:44', 1),
(176, 199, '6065', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:54', '2025-08-02 10:47:44', 1),
(177, 200, '6066', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:55', '2025-08-02 10:47:44', 1),
(178, 201, '6067', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:55', '2025-08-02 10:47:44', 1),
(179, 202, '6068', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:55', '2025-08-02 10:47:44', 1),
(180, 203, '6069', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:55', '2025-08-02 10:47:44', 1),
(181, 204, '6070', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:56', '2025-08-02 10:47:44', 1),
(182, 205, '6071', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:56', '2025-08-02 10:47:44', 1),
(183, 206, '6072', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:56', '2025-08-02 10:47:44', 1),
(184, 207, '6073', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:56', '2025-08-02 10:47:44', 1),
(185, 208, '6074', 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:52:56', '2025-08-02 10:47:44', 1),
(186, 209, '7001', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:54:56', '2025-08-02 10:47:44', 1),
(187, 210, '7002', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:54:56', '2025-08-02 10:47:44', 1),
(188, 211, '7003', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:54:56', '2025-08-02 10:47:44', 1),
(189, 212, '7004', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:54:57', '2025-08-02 10:47:44', 1),
(190, 213, '7005', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:54:57', '2025-08-02 10:47:44', 1),
(191, 214, '7006', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:54:57', '2025-08-02 10:47:44', 1),
(192, 215, '7007', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:54:57', '2025-08-02 10:47:44', 1),
(193, 216, '7008', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:54:57', '2025-08-02 10:47:44', 1),
(194, 217, '7009', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:54:58', '2025-08-02 10:47:44', 1),
(195, 218, '7010', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:54:58', '2025-08-02 10:47:44', 1),
(196, 219, '7011', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:54:58', '2025-08-02 10:47:44', 1),
(197, 220, '7012', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:54:58', '2025-08-02 10:47:44', 1),
(198, 221, '7013', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:54:59', '2025-08-02 10:47:44', 1),
(199, 222, '7014', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:54:59', '2025-08-02 10:47:44', 1),
(200, 223, '7015', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:54:59', '2025-08-02 10:47:44', 1),
(201, 224, '7016', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:54:59', '2025-08-02 10:47:44', 1),
(202, 225, '7017', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:54:59', '2025-08-02 10:47:44', 1),
(203, 226, '7018', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:00', '2025-08-02 10:47:44', 1),
(204, 227, '7019', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:00', '2025-08-02 10:47:44', 1),
(205, 228, '7020', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:00', '2025-08-02 10:47:44', 1),
(206, 229, '7021', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:00', '2025-08-02 10:47:44', 1),
(207, 230, '7022', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:00', '2025-08-02 10:47:44', 1),
(208, 231, '7023', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:01', '2025-08-02 10:47:44', 1),
(209, 232, '7024', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:01', '2025-08-02 10:47:44', 1),
(210, 233, '7025', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:01', '2025-08-02 10:47:44', 1),
(211, 234, '7026', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:01', '2025-08-02 10:47:44', 1),
(212, 235, '7027', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:02', '2025-08-02 10:47:44', 1),
(213, 236, '7028', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:02', '2025-08-02 10:47:44', 1),
(214, 237, '7029', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:02', '2025-08-02 10:47:44', 1),
(215, 238, '7030', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:02', '2025-08-02 10:47:44', 1),
(216, 239, '7031', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:02', '2025-08-02 10:47:44', 1),
(217, 240, '7032', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:03', '2025-08-02 10:47:44', 1);
INSERT INTO `students` (`id`, `user_id`, `roll_number`, `school_class_id`, `class_section_id`, `shift_id`, `guardian_id`, `email`, `phone`, `address`, `date_of_birth`, `admission_date`, `admission_number`, `category`, `gender_id`, `blood_id`, `religion_id`, `national_id`, `place_of_birth`, `nationality`, `language`, `health_status`, `rank_in_family`, `number_of_siblings`, `profile_picture`, `emergency_contact_name`, `emergency_contact_phone`, `previous_school_attended`, `previous_school`, `previous_school_document`, `is_active`, `notes`, `created_at`, `updated_at`, `academic_session_id`) VALUES
(218, 241, '7033', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:03', '2025-08-02 10:47:44', 1),
(219, 242, '7034', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:03', '2025-08-02 10:47:44', 1),
(220, 243, '7035', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:03', '2025-08-02 10:47:44', 1),
(221, 244, '7036', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:03', '2025-08-02 10:47:44', 1),
(222, 245, '7037', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:04', '2025-08-02 10:47:44', 1),
(223, 246, '7038', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:04', '2025-08-02 10:47:44', 1),
(224, 247, '7039', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:04', '2025-08-02 10:47:44', 1),
(225, 248, '7040', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:04', '2025-08-02 10:47:44', 1),
(226, 249, '7041', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:05', '2025-08-02 10:47:44', 1),
(227, 250, '7042', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:05', '2025-08-02 10:47:44', 1),
(228, 251, '7043', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:05', '2025-08-02 10:47:44', 1),
(229, 252, '7044', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:05', '2025-08-02 10:47:44', 1),
(230, 253, '7045', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:05', '2025-08-02 10:47:44', 1),
(231, 254, '7046', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:06', '2025-08-02 10:47:44', 1),
(232, 255, '7047', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:06', '2025-08-02 10:47:44', 1),
(233, 256, '7048', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:06', '2025-08-02 10:47:44', 1),
(234, 257, '7049', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:06', '2025-08-02 10:47:44', 1),
(235, 258, '7050', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:06', '2025-08-02 10:47:44', 1),
(236, 259, '7051', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:07', '2025-08-02 10:47:44', 1),
(237, 260, '7052', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:07', '2025-08-02 10:47:44', 1),
(238, 261, '7053', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:07', '2025-08-02 10:47:44', 1),
(239, 262, '7054', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:07', '2025-08-02 10:47:44', 1),
(240, 263, '7055', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:08', '2025-08-02 10:47:44', 1),
(241, 264, '7056', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:08', '2025-08-02 10:47:44', 1),
(242, 265, '7057', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:08', '2025-08-02 10:47:44', 1),
(243, 266, '7058', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:08', '2025-08-02 10:47:44', 1),
(244, 267, '7059', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:08', '2025-08-02 10:47:44', 1),
(245, 268, '7060', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:09', '2025-08-02 10:47:44', 1),
(246, 269, '7061', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:09', '2025-08-02 10:47:44', 1),
(247, 270, '7062', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:09', '2025-08-02 10:47:44', 1),
(248, 271, '7063', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:09', '2025-08-02 10:47:44', 1),
(249, 272, '7064', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:09', '2025-08-02 10:47:44', 1),
(250, 273, '7065', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:10', '2025-08-02 10:47:44', 1),
(251, 274, '7066', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:10', '2025-08-02 10:47:44', 1),
(252, 275, '7067', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:10', '2025-08-02 10:47:44', 1),
(253, 276, '7068', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:10', '2025-08-02 10:47:44', 1),
(254, 277, '7069', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:10', '2025-08-02 10:47:44', 1),
(255, 278, '7070', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:11', '2025-08-02 10:47:44', 1),
(256, 279, '7071', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:11', '2025-08-02 10:47:44', 1),
(257, 280, '7072', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:11', '2025-08-02 10:47:44', 1),
(258, 281, '7073', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:11', '2025-08-02 10:47:44', 1),
(259, 282, '7074', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:12', '2025-08-02 10:47:44', 1),
(260, 283, '7075', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:12', '2025-08-02 10:47:44', 1),
(261, 284, '7076', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:12', '2025-08-02 10:47:44', 1),
(262, 285, '7077', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:12', '2025-08-02 10:47:44', 1),
(263, 286, '7078', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:12', '2025-08-02 10:47:44', 1),
(264, 287, '7079', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:13', '2025-08-02 10:47:44', 1),
(265, 288, '7080', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:13', '2025-08-02 10:47:44', 1),
(266, 289, '7081', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:13', '2025-08-02 10:47:44', 1),
(267, 290, '7082', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:13', '2025-08-02 10:47:44', 1),
(268, 291, '7083', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:13', '2025-08-02 10:47:44', 1),
(269, 292, '7084', 5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 09:55:14', '2025-08-02 10:47:44', 1),
(270, 293, '8001', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:00', '2025-08-02 10:47:44', 1),
(271, 294, '8002', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:00', '2025-08-02 10:47:44', 1),
(272, 295, '8003', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:00', '2025-08-02 10:47:44', 1),
(273, 296, '8004', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:00', '2025-08-02 10:47:44', 1),
(274, 297, '8005', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:00', '2025-08-02 10:47:44', 1),
(275, 298, '8006', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:01', '2025-08-02 10:47:44', 1),
(276, 299, '8007', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:01', '2025-08-02 10:47:44', 1),
(277, 300, '8008', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:01', '2025-08-02 10:47:44', 1),
(278, 301, '8009', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:01', '2025-08-02 10:47:44', 1),
(279, 302, '8010', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:01', '2025-08-02 10:47:44', 1),
(280, 303, '8011', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:02', '2025-08-02 10:47:44', 1),
(281, 304, '8012', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:02', '2025-08-02 10:47:44', 1),
(282, 305, '8013', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:02', '2025-08-02 10:47:44', 1),
(283, 306, '8014', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:02', '2025-08-02 10:47:44', 1),
(284, 307, '8015', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:03', '2025-08-02 10:47:44', 1),
(285, 308, '8016', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:03', '2025-08-02 10:47:44', 1),
(286, 309, '8017', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:03', '2025-08-02 10:47:44', 1),
(287, 310, '8018', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:03', '2025-08-02 10:47:44', 1),
(288, 311, '8019', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:03', '2025-08-02 10:47:44', 1),
(289, 312, '8020', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:04', '2025-08-02 10:47:44', 1),
(290, 313, '8021', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:04', '2025-08-02 10:47:44', 1),
(291, 314, '8022', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:04', '2025-08-02 10:47:44', 1),
(292, 315, '8023', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:04', '2025-08-02 10:47:44', 1),
(293, 316, '8024', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:04', '2025-08-02 10:47:44', 1),
(294, 317, '8025', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:05', '2025-08-02 10:47:44', 1),
(295, 318, '8026', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:05', '2025-08-02 10:47:44', 1),
(296, 319, '8027', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:05', '2025-08-02 10:47:44', 1),
(297, 320, '8028', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:05', '2025-08-02 10:47:44', 1),
(298, 321, '8029', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:06', '2025-08-02 10:47:44', 1),
(299, 322, '8030', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:06', '2025-08-02 10:47:44', 1),
(300, 323, '8031', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:06', '2025-08-02 10:47:44', 1),
(301, 324, '8032', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:06', '2025-08-02 10:47:44', 1),
(302, 325, '8033', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:06', '2025-08-02 10:47:44', 1),
(303, 326, '8034', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:07', '2025-08-02 10:47:44', 1),
(304, 327, '8035', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:07', '2025-08-02 10:47:44', 1),
(305, 328, '8036', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:07', '2025-08-02 10:47:44', 1),
(306, 329, '8037', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:07', '2025-08-02 10:47:44', 1),
(307, 330, '8038', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:07', '2025-08-02 10:47:44', 1),
(308, 331, '8039', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:08', '2025-08-02 10:47:44', 1),
(309, 332, '8040', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:08', '2025-08-02 10:47:44', 1),
(310, 333, '8041', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:08', '2025-08-02 10:47:44', 1),
(311, 334, '8042', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:08', '2025-08-02 10:47:44', 1),
(312, 335, '8043', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:09', '2025-08-02 10:47:44', 1),
(313, 336, '8044', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:09', '2025-08-02 10:47:44', 1),
(314, 337, '8045', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:09', '2025-08-02 10:47:44', 1),
(315, 338, '8046', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:09', '2025-08-02 10:47:44', 1),
(316, 339, '8047', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:09', '2025-08-02 10:47:44', 1),
(317, 340, '8048', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:10', '2025-08-02 10:47:44', 1),
(318, 341, '8049', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:10', '2025-08-02 10:47:44', 1),
(319, 342, '8050', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:10', '2025-08-02 10:47:44', 1),
(320, 343, '8051', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:10', '2025-08-02 10:47:44', 1),
(321, 344, '8052', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:10', '2025-08-02 10:47:44', 1),
(322, 345, '8053', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:11', '2025-08-02 10:47:44', 1),
(323, 346, '8054', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:11', '2025-08-02 10:47:44', 1),
(324, 347, '8055', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:11', '2025-08-02 10:47:44', 1),
(325, 348, '8056', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:11', '2025-08-02 10:47:44', 1),
(326, 349, '8057', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:11', '2025-08-02 10:47:44', 1),
(327, 350, '8058', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:12', '2025-08-02 10:47:44', 1),
(328, 351, '8059', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:12', '2025-08-02 10:47:44', 1),
(329, 352, '8060', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:12', '2025-08-02 10:47:44', 1),
(330, 353, '8061', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:12', '2025-08-02 10:47:44', 1),
(331, 354, '8062', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:13', '2025-08-02 10:47:44', 1),
(332, 355, '8063', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:13', '2025-08-02 10:47:44', 1),
(333, 356, '8064', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:13', '2025-08-02 10:47:44', 1),
(334, 357, '8065', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:13', '2025-08-02 10:47:44', 1),
(335, 358, '8066', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:13', '2025-08-02 10:47:44', 1),
(336, 359, '8067', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:14', '2025-08-02 10:47:44', 1),
(337, 360, '8068', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:14', '2025-08-02 10:47:44', 1),
(338, 361, '8069', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:14', '2025-08-02 10:47:44', 1),
(339, 362, '8070', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:14', '2025-08-02 10:47:44', 1),
(340, 363, '8071', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:14', '2025-08-02 10:47:44', 1),
(341, 364, '8072', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:15', '2025-08-02 10:47:44', 1),
(342, 365, '8073', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:15', '2025-08-02 10:47:44', 1),
(343, 366, '8074', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:15', '2025-08-02 10:47:44', 1),
(344, 367, '8075', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:15', '2025-08-02 10:47:44', 1),
(345, 368, '8076', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:15', '2025-08-02 10:47:44', 1),
(346, 369, '8077', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:16', '2025-08-02 10:47:44', 1),
(347, 370, '8078', 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:01:16', '2025-08-02 10:47:44', 1),
(348, 371, '9001', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:17', '2025-08-02 10:47:44', 1),
(349, 372, '9002', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:18', '2025-08-02 10:47:44', 1),
(350, 373, '9003', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:18', '2025-08-02 10:47:44', 1),
(351, 374, '9004', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:18', '2025-08-02 10:47:44', 1),
(352, 375, '9005', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:18', '2025-08-02 10:47:44', 1),
(353, 376, '9006', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:19', '2025-08-02 10:47:44', 1),
(354, 377, '9007', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:19', '2025-08-02 10:47:44', 1),
(355, 378, '9008', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:19', '2025-08-02 10:47:44', 1),
(356, 379, '9009', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:19', '2025-08-02 10:47:44', 1),
(357, 380, '9010', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:19', '2025-08-02 10:47:44', 1),
(358, 381, '9011', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:20', '2025-08-02 10:47:44', 1),
(359, 382, '9012', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:20', '2025-08-02 10:47:44', 1),
(360, 383, '9013', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:20', '2025-08-02 10:47:44', 1),
(361, 384, '9014', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:20', '2025-08-02 10:47:44', 1),
(362, 385, '9015', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:21', '2025-08-02 10:47:44', 1),
(363, 386, '9016', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:21', '2025-08-02 10:47:44', 1),
(364, 387, '9017', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:21', '2025-08-02 10:47:44', 1),
(365, 388, '9018', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:21', '2025-08-02 10:47:44', 1),
(366, 389, '9019', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:21', '2025-08-02 10:47:44', 1),
(367, 390, '9020', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:22', '2025-08-02 10:47:44', 1),
(368, 391, '9021', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:22', '2025-08-02 10:47:44', 1),
(369, 392, '9022', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:22', '2025-08-02 10:47:44', 1),
(370, 393, '9023', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:22', '2025-08-02 10:47:44', 1),
(371, 394, '9024', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:22', '2025-08-02 10:47:44', 1),
(372, 395, '9025', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:23', '2025-08-02 10:47:44', 1),
(373, 396, '9026', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:23', '2025-08-02 10:47:44', 1),
(374, 397, '9027', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:23', '2025-08-02 10:47:44', 1),
(375, 398, '9028', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:23', '2025-08-02 10:47:44', 1),
(376, 399, '9029', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:24', '2025-08-02 10:47:44', 1),
(377, 400, '9030', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:24', '2025-08-02 10:47:44', 1),
(378, 401, '9031', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:24', '2025-08-02 10:47:44', 1),
(379, 402, '9032', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:24', '2025-08-02 10:47:44', 1),
(380, 403, '9033', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:24', '2025-08-02 10:47:44', 1),
(381, 404, '9034', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:25', '2025-08-02 10:47:44', 1),
(382, 405, '9035', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:25', '2025-08-02 10:47:44', 1),
(383, 406, '9036', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:25', '2025-08-02 10:47:44', 1),
(384, 407, '9037', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:25', '2025-08-02 10:47:44', 1),
(385, 408, '9038', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:26', '2025-08-02 10:47:44', 1),
(386, 409, '9039', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:26', '2025-08-02 10:47:44', 1),
(387, 410, '9040', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:26', '2025-08-02 10:47:44', 1),
(388, 411, '9041', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:26', '2025-08-02 10:47:44', 1),
(389, 412, '9042', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:26', '2025-08-02 10:47:44', 1),
(390, 413, '9043', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:27', '2025-08-02 10:47:44', 1),
(391, 414, '9044', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:27', '2025-08-02 10:47:44', 1),
(392, 415, '9045', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:27', '2025-08-02 10:47:44', 1),
(393, 416, '9046', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:27', '2025-08-02 10:47:44', 1),
(394, 417, '9047', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:27', '2025-08-02 10:47:44', 1),
(395, 418, '9048', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:28', '2025-08-02 10:47:44', 1),
(396, 419, '9049', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:28', '2025-08-02 10:47:44', 1),
(397, 420, '9050', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:28', '2025-08-02 10:47:44', 1),
(398, 421, '9051', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:28', '2025-08-02 10:47:44', 1),
(399, 422, '9052', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:29', '2025-08-02 10:47:44', 1),
(400, 423, '9053', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:29', '2025-08-02 10:47:44', 1),
(401, 424, '9054', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:29', '2025-08-02 10:47:44', 1),
(402, 425, '9055', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:29', '2025-08-02 10:47:44', 1),
(403, 426, '9056', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:29', '2025-08-02 10:47:44', 1),
(404, 427, '9057', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:30', '2025-08-02 10:47:44', 1),
(405, 428, '9058', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:30', '2025-08-02 10:47:44', 1),
(406, 429, '9059', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:30', '2025-08-02 10:47:44', 1),
(407, 430, '9060', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:30', '2025-08-02 10:47:44', 1),
(408, 431, '9061', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:31', '2025-08-02 10:47:44', 1),
(409, 432, '9062', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:31', '2025-08-02 10:47:44', 1),
(410, 433, '9063', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:31', '2025-08-02 10:47:44', 1),
(411, 434, '9064', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:31', '2025-08-02 10:47:44', 1),
(412, 435, '9065', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:31', '2025-08-02 10:47:44', 1),
(413, 436, '9066', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:32', '2025-08-02 10:47:44', 1),
(414, 437, '9067', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:32', '2025-08-02 10:47:44', 1),
(415, 438, '9068', 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:31:32', '2025-08-02 10:47:44', 1),
(416, 439, '10001', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:37', '2025-08-02 10:47:44', 1),
(417, 440, '10002', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:37', '2025-08-02 10:47:44', 1),
(418, 441, '10003', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:38', '2025-08-02 10:47:44', 1),
(419, 442, '10004', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:38', '2025-08-02 10:47:44', 1),
(420, 443, '10005', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:38', '2025-08-02 10:47:44', 1),
(421, 444, '10006', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:38', '2025-08-02 10:47:44', 1),
(422, 445, '10007', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:39', '2025-08-02 10:47:44', 1),
(423, 446, '10008', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:39', '2025-08-02 10:47:44', 1),
(424, 447, '10009', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:39', '2025-08-02 10:47:44', 1),
(425, 448, '10010', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:39', '2025-08-02 10:47:44', 1),
(426, 449, '10011', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:39', '2025-08-02 10:47:44', 1),
(427, 450, '10012', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:40', '2025-08-02 10:47:44', 1),
(428, 451, '10013', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:40', '2025-08-02 10:47:44', 1),
(429, 452, '10014', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:40', '2025-08-02 10:47:44', 1),
(430, 453, '10015', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:40', '2025-08-02 10:47:44', 1),
(431, 454, '10016', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:40', '2025-08-02 10:47:44', 1),
(432, 455, '10017', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:41', '2025-08-02 10:47:44', 1);
INSERT INTO `students` (`id`, `user_id`, `roll_number`, `school_class_id`, `class_section_id`, `shift_id`, `guardian_id`, `email`, `phone`, `address`, `date_of_birth`, `admission_date`, `admission_number`, `category`, `gender_id`, `blood_id`, `religion_id`, `national_id`, `place_of_birth`, `nationality`, `language`, `health_status`, `rank_in_family`, `number_of_siblings`, `profile_picture`, `emergency_contact_name`, `emergency_contact_phone`, `previous_school_attended`, `previous_school`, `previous_school_document`, `is_active`, `notes`, `created_at`, `updated_at`, `academic_session_id`) VALUES
(433, 456, '10018', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:41', '2025-08-02 10:47:44', 1),
(434, 457, '10019', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:41', '2025-08-02 10:47:44', 1),
(435, 458, '10020', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:41', '2025-08-02 10:47:44', 1),
(436, 459, '10021', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:42', '2025-08-02 10:47:44', 1),
(437, 460, '10022', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:42', '2025-08-02 10:47:44', 1),
(438, 461, '10023', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:42', '2025-08-02 10:47:44', 1),
(439, 462, '10024', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:42', '2025-08-02 10:47:44', 1),
(440, 463, '10025', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:42', '2025-08-02 10:47:44', 1),
(441, 464, '10026', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:43', '2025-08-02 10:47:44', 1),
(442, 465, '10027', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:43', '2025-08-02 10:47:44', 1),
(443, 466, '10028', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:43', '2025-08-02 10:47:44', 1),
(444, 467, '10029', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:43', '2025-08-02 10:47:44', 1),
(445, 468, '10030', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:43', '2025-08-02 10:47:44', 1),
(446, 469, '10031', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:44', '2025-08-02 10:47:44', 1),
(447, 470, '10032', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:44', '2025-08-02 10:47:44', 1),
(448, 471, '10033', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:44', '2025-08-02 10:47:44', 1),
(449, 472, '10034', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:44', '2025-08-02 10:47:44', 1),
(450, 473, '10035', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:45', '2025-08-02 10:47:44', 1),
(451, 474, '10036', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:45', '2025-08-02 10:47:44', 1),
(452, 475, '10037', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:45', '2025-08-02 10:47:44', 1),
(453, 476, '10038', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:45', '2025-08-02 10:47:44', 1),
(454, 477, '10039', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:45', '2025-08-02 10:47:44', 1),
(455, 478, '10040', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:46', '2025-08-02 10:47:44', 1),
(456, 479, '10041', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:46', '2025-08-02 10:47:44', 1),
(457, 480, '10042', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:46', '2025-08-02 10:47:44', 1),
(458, 481, '10043', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:46', '2025-08-02 10:47:44', 1),
(459, 482, '10044', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:46', '2025-08-02 10:47:44', 1),
(460, 483, '10045', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:47', '2025-08-02 10:47:44', 1),
(461, 484, '10046', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:47', '2025-08-02 10:47:44', 1),
(462, 485, '10047', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:47', '2025-08-02 10:47:44', 1),
(463, 486, '10048', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:47', '2025-08-02 10:47:44', 1),
(464, 487, '10049', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:47', '2025-08-02 10:47:44', 1),
(465, 488, '10050', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:48', '2025-08-02 10:47:44', 1),
(466, 489, '10051', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:48', '2025-08-02 10:47:44', 1),
(467, 490, '10052', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:48', '2025-08-02 10:47:44', 1),
(468, 491, '10053', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:48', '2025-08-02 10:47:44', 1),
(469, 492, '10054', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:49', '2025-08-02 10:47:44', 1),
(470, 493, '10055', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:49', '2025-08-02 10:47:44', 1),
(471, 494, '10056', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:49', '2025-08-02 10:47:44', 1),
(472, 495, '10057', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:49', '2025-08-02 10:47:44', 1),
(473, 496, '10058', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:49', '2025-08-02 10:47:44', 1),
(474, 497, '10059', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:50', '2025-08-02 10:47:44', 1),
(475, 498, '10060', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:50', '2025-08-02 10:47:44', 1),
(476, 499, '10061', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:50', '2025-08-02 10:47:44', 1),
(477, 500, '10062', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:50', '2025-08-02 10:47:44', 1),
(478, 501, '10063', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:50', '2025-08-02 10:47:44', 1),
(479, 502, '10064', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:51', '2025-08-02 10:47:44', 1),
(480, 503, '10065', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:51', '2025-08-02 10:47:44', 1),
(481, 504, '10066', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:51', '2025-08-02 10:47:44', 1),
(482, 505, '10067', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:51', '2025-08-02 10:47:44', 1),
(483, 506, '10068', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:52', '2025-08-02 10:47:44', 1),
(484, 507, '10069', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:52', '2025-08-02 10:47:44', 1),
(485, 508, '10070', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:52', '2025-08-02 10:47:44', 1),
(486, 509, '10071', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:52', '2025-08-02 10:47:44', 1),
(487, 510, '10072', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:52', '2025-08-02 10:47:44', 1),
(488, 511, '10073', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:53', '2025-08-02 10:47:44', 1),
(489, 512, '10074', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:53', '2025-08-02 10:47:44', 1),
(490, 513, '10075', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:53', '2025-08-02 10:47:44', 1),
(491, 514, '10076', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:53', '2025-08-02 10:47:44', 1),
(492, 515, '10077', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:53', '2025-08-02 10:47:44', 1),
(493, 516, '10078', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:54', '2025-08-02 10:47:44', 1),
(494, 517, '10079', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:54', '2025-08-02 10:47:44', 1),
(495, 518, '10080', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:54', '2025-08-02 10:47:44', 1),
(496, 519, '10081', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:54', '2025-08-02 10:47:44', 1),
(497, 520, '10082', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:55', '2025-08-02 10:47:44', 1),
(498, 521, '10083', 8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, NULL, '2025-08-02 10:33:55', '2025-08-02 10:47:44', 1);

-- --------------------------------------------------------

--
-- Table structure for table `student_marks`
--

CREATE TABLE `student_marks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `school_class_id` bigint(20) UNSIGNED NOT NULL,
  `class_section_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subject_id` bigint(20) UNSIGNED NOT NULL,
  `exam_id` bigint(20) UNSIGNED NOT NULL,
  `mark_distribution_id` bigint(20) UNSIGNED NOT NULL,
  `marks_obtained` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_marks`
--

INSERT INTO `student_marks` (`id`, `student_id`, `school_class_id`, `class_section_id`, `subject_id`, `exam_id`, `mark_distribution_id`, `marks_obtained`, `created_at`, `updated_at`) VALUES
(1, 33, 2, 2, 1, 1, 1, 20, '2025-08-03 15:19:56', '2025-08-03 15:19:56'),
(2, 33, 2, 2, 1, 1, 2, 60, '2025-08-03 15:19:56', '2025-08-03 15:19:56'),
(3, 34, 2, 2, 1, 1, 1, 14, '2025-08-03 15:19:56', '2025-08-03 15:19:56'),
(4, 34, 2, 2, 1, 1, 2, 30, '2025-08-03 15:19:56', '2025-08-03 15:19:56'),
(5, 33, 2, 2, 2, 1, 1, 15, '2025-08-03 15:20:22', '2025-08-03 15:20:22'),
(6, 33, 2, 2, 2, 1, 2, 50, '2025-08-03 15:20:22', '2025-08-03 15:20:22'),
(7, 34, 2, 2, 2, 1, 1, 17, '2025-08-03 15:20:22', '2025-08-03 15:20:22'),
(8, 34, 2, 2, 2, 1, 2, 60, '2025-08-03 15:20:22', '2025-08-03 15:20:22'),
(9, 33, 2, 2, 3, 1, 1, 10, '2025-08-03 15:20:42', '2025-08-03 15:20:42'),
(10, 33, 2, 2, 3, 1, 2, 50, '2025-08-03 15:20:42', '2025-08-03 15:20:42'),
(11, 34, 2, 2, 3, 1, 1, 7, '2025-08-03 15:20:42', '2025-08-03 15:20:42'),
(12, 34, 2, 2, 3, 1, 2, 56, '2025-08-03 15:20:42', '2025-08-03 15:20:42'),
(13, 33, 2, 2, 4, 1, 1, 15, '2025-08-03 15:21:01', '2025-08-03 15:21:01'),
(14, 33, 2, 2, 4, 1, 2, 30, '2025-08-03 15:21:01', '2025-08-03 15:21:01'),
(15, 34, 2, 2, 4, 1, 1, 20, '2025-08-03 15:21:01', '2025-08-03 15:21:01'),
(16, 34, 2, 2, 4, 1, 2, 80, '2025-08-03 15:21:01', '2025-08-03 15:21:01'),
(17, 33, 2, 2, 5, 1, 1, 20, '2025-08-03 15:21:15', '2025-08-03 15:21:15'),
(18, 33, 2, 2, 5, 1, 2, 50, '2025-08-03 15:21:15', '2025-08-03 15:21:15'),
(19, 34, 2, 2, 5, 1, 1, 15, '2025-08-03 15:21:15', '2025-08-03 15:21:15'),
(20, 34, 2, 2, 5, 1, 2, 59, '2025-08-03 15:21:15', '2025-08-03 15:21:15'),
(21, 33, 2, 2, 6, 1, 1, 20, '2025-08-03 15:21:31', '2025-08-03 15:21:31'),
(22, 33, 2, 2, 6, 1, 2, 80, '2025-08-03 15:21:31', '2025-08-03 15:21:31'),
(23, 34, 2, 2, 6, 1, 1, 15, '2025-08-03 15:21:31', '2025-08-03 15:21:31'),
(24, 34, 2, 2, 6, 1, 2, 86, '2025-08-03 15:21:31', '2025-08-03 15:21:31'),
(25, 33, 2, 2, 7, 1, 1, 40, '2025-08-03 15:21:49', '2025-08-03 15:21:49'),
(26, 33, 2, 2, 7, 1, 2, 40, '2025-08-03 15:21:49', '2025-08-03 15:21:49'),
(27, 34, 2, 2, 7, 1, 1, 15, '2025-08-03 15:21:49', '2025-08-03 15:21:49'),
(28, 34, 2, 2, 7, 1, 2, 15, '2025-08-03 15:21:49', '2025-08-03 15:21:49');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'theory',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `name`, `code`, `type`, `created_at`, `updated_at`) VALUES
(1, 'Bangla', NULL, 'theory', '2025-08-01 11:49:51', '2025-08-01 11:49:51'),
(2, 'English', NULL, 'theory', '2025-08-01 11:50:01', '2025-08-01 11:50:01'),
(3, 'Mathematics', NULL, 'theory', '2025-08-01 11:50:18', '2025-08-01 11:50:18'),
(4, 'Science', NULL, 'theory', '2025-08-01 11:50:30', '2025-08-01 11:50:30'),
(5, 'Bangladesh and global Studies', NULL, 'theory', '2025-08-01 11:50:42', '2025-08-01 11:50:42'),
(6, 'Religion', NULL, 'theory', '2025-08-01 11:50:53', '2025-08-01 11:50:53'),
(7, 'Art', NULL, 'theory', '2025-08-01 11:50:59', '2025-08-01 11:50:59'),
(8, 'Bangla 1st Paper ', NULL, 'theory', '2025-08-01 11:51:20', '2025-08-01 11:51:20'),
(9, 'Bangla 2nd Paper ', NULL, 'theory', '2025-08-01 11:51:31', '2025-08-01 11:51:31'),
(10, 'English 1st Paper ', NULL, 'theory', '2025-08-01 11:52:00', '2025-08-01 11:52:00'),
(11, 'English 2nd Paper ', NULL, 'theory', '2025-08-01 11:52:07', '2025-08-01 11:52:07'),
(12, 'Information and communication Technology', NULL, 'theory', '2025-08-01 11:52:22', '2025-08-01 11:52:22'),
(13, 'Home Science', NULL, 'theory', '2025-08-01 11:52:32', '2025-08-01 11:52:32'),
(14, 'Physical education', NULL, 'theory', '2025-08-01 11:52:42', '2025-08-01 11:52:42'),
(15, 'Career education', NULL, 'theory', '2025-08-01 11:52:51', '2025-08-01 11:52:51'),
(16, 'Information and communication Technology (Practical)', NULL, 'practical', '2025-08-01 11:53:33', '2025-08-01 11:53:33'),
(17, 'Physic', NULL, 'theory', '2025-08-01 11:54:01', '2025-08-01 11:54:01'),
(18, 'Physic (Practical)', NULL, 'practical', '2025-08-01 11:54:15', '2025-08-01 11:54:15'),
(19, 'Chemistry', NULL, 'theory', '2025-08-01 11:55:08', '2025-08-01 11:55:08'),
(20, 'Chemistry (Practical)', NULL, 'practical', '2025-08-01 11:55:16', '2025-08-01 11:55:16'),
(21, 'Biology', NULL, 'theory', '2025-08-01 11:55:29', '2025-08-01 11:55:29'),
(22, 'Biology (Practical)', NULL, 'theory', '2025-08-01 11:55:36', '2025-08-01 11:55:36'),
(23, 'Higher Mathematics', NULL, 'theory', '2025-08-01 11:57:14', '2025-08-01 11:57:14'),
(24, 'Higher Mathematics (Practical)', NULL, 'practical', '2025-08-01 11:57:23', '2025-08-01 11:57:23'),
(25, 'Art & Craft ', NULL, 'theory', '2025-08-01 11:57:54', '2025-08-01 11:57:54'),
(26, 'History', NULL, 'theory', '2025-08-01 11:58:56', '2025-08-01 11:58:56'),
(27, 'Geography', NULL, 'theory', '2025-08-01 11:59:04', '2025-08-01 11:59:04'),
(28, 'Civics', NULL, 'theory', '2025-08-01 11:59:13', '2025-08-01 11:59:13'),
(29, 'Home Science (Practical)', NULL, 'practical', '2025-08-01 11:59:37', '2025-08-01 11:59:37'),
(30, 'Accounting', NULL, 'theory', '2025-08-01 11:59:51', '2025-08-01 11:59:51'),
(31, 'Finance', NULL, 'theory', '2025-08-01 12:00:00', '2025-08-01 12:00:00'),
(32, 'Entrepreneurship ', NULL, 'theory', '2025-08-01 12:00:06', '2025-08-01 12:00:06');

-- --------------------------------------------------------

--
-- Table structure for table `subject_assigns`
--

CREATE TABLE `subject_assigns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_class_id` bigint(20) UNSIGNED NOT NULL,
  `class_section_id` bigint(20) UNSIGNED NOT NULL,
  `shift_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subject_assign_items`
--

CREATE TABLE `subject_assign_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subject_assign_id` bigint(20) UNSIGNED NOT NULL,
  `subject_id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subject_mark_distributions`
--

CREATE TABLE `subject_mark_distributions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_class_id` bigint(20) UNSIGNED NOT NULL,
  `class_section_id` bigint(20) UNSIGNED NOT NULL,
  `subject_id` bigint(20) UNSIGNED NOT NULL,
  `mark_distribution_id` bigint(20) UNSIGNED NOT NULL,
  `mark` int(11) NOT NULL,
  `pass_mark` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subject_mark_distributions`
--

INSERT INTO `subject_mark_distributions` (`id`, `school_class_id`, `class_section_id`, `subject_id`, `mark_distribution_id`, `mark`, `pass_mark`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 1, 1, 20, 0, '2025-08-03 10:38:43', '2025-08-03 10:38:43'),
(2, 2, 1, 1, 2, 100, 33, '2025-08-03 10:38:43', '2025-08-03 10:38:43'),
(3, 2, 1, 2, 1, 20, 0, '2025-08-03 10:38:43', '2025-08-03 10:38:43'),
(4, 2, 1, 2, 2, 100, 33, '2025-08-03 10:38:43', '2025-08-03 10:38:43'),
(5, 2, 1, 3, 1, 20, 0, '2025-08-03 10:38:43', '2025-08-03 10:38:43'),
(6, 2, 1, 3, 2, 100, 33, '2025-08-03 10:38:43', '2025-08-03 10:38:43'),
(7, 2, 1, 4, 1, 20, 0, '2025-08-03 10:38:43', '2025-08-03 10:38:43'),
(8, 2, 1, 4, 2, 100, 33, '2025-08-03 10:38:43', '2025-08-03 10:38:43'),
(9, 2, 1, 5, 1, 20, 0, '2025-08-03 10:38:43', '2025-08-03 10:38:43'),
(10, 2, 1, 5, 2, 100, 33, '2025-08-03 10:38:43', '2025-08-03 10:38:43'),
(11, 2, 1, 6, 1, 20, 0, '2025-08-03 10:38:43', '2025-08-03 10:38:43'),
(12, 2, 1, 6, 2, 100, 33, '2025-08-03 10:38:43', '2025-08-03 10:38:43'),
(13, 2, 1, 7, 2, 50, 0, '2025-08-03 10:38:43', '2025-08-03 10:38:43');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','teacher','librarian','accountant') DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `is_parent` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `role`, `status`, `is_admin`, `is_parent`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin', '$2y$12$cLpUhBsBLXVuaRzqP/9wYuQPcTewid9alDJl2RqXw058LooT/spyW', 'admin', 1, 1, 0, 'lirV3lGz1aWq0QHUQHb10sXzVZC7r9DVEpXdbUYGIRYOUDGwcvynCH0rb1xt', '2025-07-09 11:17:17', '2025-07-09 11:17:17'),
(3, 'Rajyeswar Roy', 'rajyeswar@gmail.com', '$2y$12$OmH33BrTf20CrznXZctO6eHmUQ1M42n93wr6qoXavuwSiVQR3KIdq', NULL, 1, 0, 1, NULL, '2025-07-19 09:41:53', '2025-07-19 09:41:53'),
(9, 'John Doe', 'john.doe@example.com', '$2y$12$FSIaZQ37b90WJdsK9NgsuOa9pxqKs2LfHARu4tlWZMOKyCLK7mZWu', 'teacher', 1, 1, 0, NULL, '2025-07-30 11:41:24', '2025-07-30 11:43:29'),
(10, 'Soiyoda Jahan', 'soiyoda', '$2y$12$SMdRi3dqaL.cyhPoXR7tIuc7EEmTrRdX3xU44Btet5k.BYjuF8rHS', 'teacher', 1, 1, 0, NULL, '2025-08-01 10:23:32', '2025-08-01 10:23:32'),
(11, 'Sheikh Uzzal', 'uzzal', '$2y$12$Gt01V4Jjfcvtu9WbUEAzPeI/1fzzP32t8NMWrcppn2QlWA30b6A.S', 'teacher', 1, 1, 0, NULL, '2025-08-01 10:27:40', '2025-08-01 10:27:40'),
(12, 'Dinesh Mollick', 'dinesh', '$2y$12$PgiZxQajf3L3TA6ZhiXNBu4HZklLZC8DEMvJYWJX3QGtAKFUNdtai', 'teacher', 1, 1, 0, NULL, '2025-08-01 10:31:59', '2025-08-01 10:31:59'),
(13, 'Mukundo Mondal', 'mukundo', '$2y$12$6cC9sKtAKvs1xaJCbq3Dl.Sydr5oRIypPpF7kkYx4QpHzLOxKKG9y', 'teacher', 1, 1, 0, NULL, '2025-08-01 10:33:33', '2025-08-01 10:33:33'),
(14, 'Farjana Yeasmin', 'farjana', '$2y$12$TiEblgcRH.hVl0P43Xd1HeIEPSseJGLhV1U6xQiIfbq.A4ZC8ox4O', 'teacher', 1, 1, 0, NULL, '2025-08-01 10:37:43', '2025-08-01 10:37:43'),
(15, 'Sumaiya Akter', 'sumaiya', '$2y$12$Wb8I/mvJYR4fOP1yAmdBOeAlSHwHJ6iMpnyw2k9CztnqHIyp2RseG', 'teacher', 1, 1, 0, NULL, '2025-08-01 11:10:55', '2025-08-01 11:10:55'),
(16, 'Md Hasan', 'hasan', '$2y$12$HUhHbwcYBBYWjvD1FkvlCOZHgkz4vrPPKly.1WdTPCCYWgR4kRbFe', 'teacher', 1, 1, 0, NULL, '2025-08-01 11:13:08', '2025-08-01 11:20:51'),
(17, 'Madhusudan', 'madhu', '$2y$12$oPzTWxpzxPGJ.ye8vW6fUO0.1D5JeKp5RBgdYurnKlK40GElnCNli', 'admin', 1, 1, 0, NULL, '2025-08-01 11:24:06', '2025-08-01 11:24:06'),
(18, 'Alamin Hazra', 'alamin', '$2y$12$Bv5LxZD6MJeXUO5yEMl/7O9VD16lARN6hdpJgK30J8eSMjkxOBJpC', 'teacher', 1, 1, 0, NULL, '2025-08-01 11:25:09', '2025-08-01 11:25:09'),
(19, 'Sukurunneca Islam', 'sukurunneca', '$2y$12$0xyc9o7QWpjNfPEtBN7xauQ6nfliVuPweK/jY6m7oDBbBLoQg72De', 'teacher', 1, 1, 0, NULL, '2025-08-01 11:30:26', '2025-08-01 11:30:26'),
(20, 'Moumita Roy', 'moumita', '$2y$12$DGesdN23D7c0KpVnx9/zouG4yn9guzMrpGibPQXSxLRk3wrAEY0Km', 'admin', 1, 1, 0, NULL, '2025-08-01 11:33:11', '2025-08-01 11:33:35'),
(21, 'Mosarof Hossen', 'mosarof', '$2y$12$9cAwKOllxOfQvJA3X5YbkeiZh4cgPJqdqOvItqqVk/ZH8XAqqD3/e', 'teacher', 1, 1, 0, NULL, '2025-08-01 11:34:43', '2025-08-01 11:34:43'),
(22, 'Radita Islam', 'radita', '$2y$12$jqwoutbaua/VOybpCscx8OaQfIUq9NjaMn/z46TqHOf0gd4esBhUa', 'teacher', 1, 1, 0, NULL, '2025-08-01 11:36:38', '2025-08-01 11:38:19'),
(23, 'Md Rahoman', 'rahoman', '$2y$12$DOu5Qn8pWK/U49UErREzNeG/YEX4vKVx.1dPOvOoeJakPJdb98QVy', 'teacher', 1, 1, 0, NULL, '2025-08-01 11:39:16', '2025-08-01 11:39:16'),
(24, 'Chisty', 'chisty', '$2y$12$iFLb93KM6O0In3MykA7KJeZrHhzBQmfwG4zHlDy5xAjID0BBAW.UW', 'teacher', 1, 1, 0, NULL, '2025-08-01 11:39:51', '2025-08-01 11:40:02'),
(25, 'ROJA MONI', 'roja', '$2y$12$pG.9p5lcdjUNACUyyhXQKuzChnr.IAqKLLWeRwsR7H/ZPa1ysz5Aa', NULL, 1, 0, 0, NULL, '2025-08-02 09:39:50', '2025-08-02 09:39:50'),
(26, 'AFRA ISBNAT TAHA', 'afra', '$2y$12$v8WigmKHHGelN64oS0cNuOwBvYYqwcKzJevjBsFptadYw2C1lPCM2', NULL, 1, 0, 0, NULL, '2025-08-02 09:39:50', '2025-08-02 09:39:50'),
(27, 'TASKIA SULTANA NIHA', 'taskia', '$2y$12$SGwJWlgxMjJ.vy5yOruo4.QEikE73lWQuk6oPh3oPwDO0Hzbszjt.', NULL, 1, 0, 0, NULL, '2025-08-02 09:39:50', '2025-08-02 09:39:50'),
(28, 'MURSHINA JAMAN RUHI', 'murshina', '$2y$12$hiveMfdW6TER.Hbvmzlr5ec7MqXyjTki4mAQ0snF4rk.3wh1WmYvS', NULL, 1, 0, 0, NULL, '2025-08-02 09:39:51', '2025-08-02 09:39:51'),
(29, 'MOST. RIYA', 'most.', '$2y$12$thObTdY.od.rXjGJnFo8debsBTCzl9vW0.ff/mM.sHg4o9iJr3wzW', NULL, 1, 0, 0, NULL, '2025-08-02 09:39:51', '2025-08-02 09:39:51'),
(30, 'JANNATUL FERDOUS', 'jannatul', '$2y$12$QqerVdraFg.JmRP5HSZl6epWPKa.5nGsAB4yDVJStSDEF8AfT.mmG', NULL, 1, 0, 0, NULL, '2025-08-02 09:39:51', '2025-08-02 09:39:51'),
(31, 'MEHEJABIN SUBHA', 'mehejabin', '$2y$12$OyZ5FGO8xPSrtHMue37YsOM.jU.0evw/U05o1COYTxq9xdBZI9yZq', NULL, 1, 0, 0, NULL, '2025-08-02 09:39:51', '2025-08-02 09:39:51'),
(32, 'SAYEDA SHAMIMA NAJ', 'sayeda', '$2y$12$wlCRwT3Wpa3JCRdfNX3a1umLeGeioSyRpAH03V9m//fxMUGceJk6.', NULL, 1, 0, 0, NULL, '2025-08-02 09:39:51', '2025-08-02 09:39:51'),
(33, 'JAIRA JAMAN AKSHA', 'jaira', '$2y$12$XmLWmD0E1rYsRwNVdmWuN.DQQwAB7y943zRURlMQgvFutxMeL34he', NULL, 1, 0, 0, NULL, '2025-08-02 09:39:52', '2025-08-02 09:39:52'),
(34, 'AYESHA JARA', 'ayesha', '$2y$12$h7jzGvXvSBm67LtIQBP9ue75zDXwp1hex21xJD/VZGaPdMPjEo5/O', NULL, 1, 0, 0, NULL, '2025-08-02 09:39:52', '2025-08-02 09:39:52'),
(35, 'MOST HAFSA AFRIN', 'most', '$2y$12$BfgyKKppIraesSjs/vw7XuODtPfdW0zsaMWrk8n8IDlkRgq74XC/O', NULL, 1, 0, 0, NULL, '2025-08-02 09:39:52', '2025-08-02 09:39:52'),
(36, 'MAHIM KHANOM', 'mahim', '$2y$12$ycaJHJ.OUH2fGtdv.LTste.JQAaugITk.Bp8idS5fCxfsuN2Gq3L.', NULL, 1, 0, 0, NULL, '2025-08-02 09:39:52', '2025-08-02 09:39:52'),
(37, 'HAFSA ISLAM ', 'hafsa', '$2y$12$/9m2xi1EYoSLK6iyeZOfye3F9Os4oV4XUxZV/MsK1XAvUGKpdHUty', NULL, 1, 0, 0, NULL, '2025-08-02 09:39:53', '2025-08-02 09:39:53'),
(38, 'FATEMATUJ JOHORA JARA', 'fatematuj', '$2y$12$F/pAC/GkRi/p37r4a9EfkOa5yT.N5i4rDSgUQHNLVIEbEE1zChJq6', NULL, 1, 0, 0, NULL, '2025-08-02 09:39:53', '2025-08-02 09:39:53'),
(39, 'ISRAT PARVIN AFIA', 'israt', '$2y$12$Mdk/n9UsAME6tN.Ixp/b6Oz/UMyIQKZC1bEWkPf77sy1E9/eAxyDa', NULL, 1, 0, 0, NULL, '2025-08-02 09:39:53', '2025-08-02 09:39:53'),
(40, 'SABIRA JAHAN', 'sabira', '$2y$12$GiWXGFXStY3KFXO7Nor7sepR8M4rdpEiOmCU8CSqvsSFv6jiUCMmK', NULL, 1, 0, 0, NULL, '2025-08-02 09:39:53', '2025-08-02 09:39:53'),
(41, 'JANNATUL FERDOUS SUMAIYA', 'jannatul1', '$2y$12$jiU.42LpmV2JrICHdmU6lOj6D300XHBVHK720vxO6JviymIrmIOXC', NULL, 1, 0, 0, NULL, '2025-08-02 09:39:53', '2025-08-02 09:39:53'),
(42, 'FARIYA RAHMAN', 'fariya', '$2y$12$1XanpvMncEEw1Epip9D48OPdOsjfnlGwG4pYDD3HLEy2.r0/B7TGa', NULL, 1, 0, 0, NULL, '2025-08-02 09:39:54', '2025-08-02 09:39:54'),
(43, 'NAJIFA ALAM RAFA', 'najifa', '$2y$12$cBW9pgEURp/1XGtqpqqHyOZJeKWdlWXyjmvaBODUQapL1RQUxJ0fe', NULL, 1, 0, 0, NULL, '2025-08-02 09:39:54', '2025-08-02 09:39:54'),
(44, 'MEHEJABIN RIDIMA', 'mehejabin1', '$2y$12$FJ4Yo/kmwY1SVfSHKEjTI.Q6eH2KEqFXEFwbHfACWbOEGzhLyFP0K', NULL, 1, 0, 0, NULL, '2025-08-02 09:39:54', '2025-08-02 09:39:54'),
(45, 'NAJIFA NUSRAT', 'najifa1', '$2y$12$M8FVvjTYvoqvVN25Rj8TveL16bOfSwS2cIZMqRAyZTcjlnJTJqhqK', NULL, 1, 0, 0, NULL, '2025-08-02 09:39:54', '2025-08-02 09:39:54'),
(46, 'M A ATIK', 'atik', '$2y$12$eCht0RUxEk98cfCLb9rjgO5CCr.tTzRzJByyvY6N/hmARqICnMizG', NULL, 1, 0, 0, NULL, '2025-08-02 09:39:54', '2025-08-02 09:39:54'),
(47, 'SABIHA JANNAT SAVA', 'sabiha', '$2y$12$9R1GpBb1tX4heXqTRdaJZ.8cljCBmgQ09ntWMvhJ9sEhhijCw9gBe', NULL, 1, 0, 0, NULL, '2025-08-02 09:39:55', '2025-08-02 09:39:55'),
(48, 'TANISA ISLAM', 'tanisa', '$2y$12$sNuWDkKH4dSAnJflvFjFlufpW/3JB9Etcjm2FzQPMKt0MyEY72A.W', NULL, 1, 0, 0, NULL, '2025-08-02 09:39:55', '2025-08-02 09:39:55'),
(49, 'MOBASSHIRA MANHA', 'mobasshira', '$2y$12$10qkak1O9.WSY54xaak.n.i8IyUU64X5QOYysmKLIGF/JN2WsrHKe', NULL, 1, 0, 0, NULL, '2025-08-02 09:39:55', '2025-08-02 09:39:55'),
(50, 'SAIMA ISLAM SNEHA', 'saima', '$2y$12$q3yhyY6G0dw4..eEnlFIs.HblalJ7URqO8YfPCLFiEO2/22k6gq8K', NULL, 1, 0, 0, NULL, '2025-08-02 09:39:55', '2025-08-02 09:39:55'),
(51, 'RAISHA JAHAN', 'raisha', '$2y$12$38VrfU4a8HFI.YLRl/yDM.FRq10mBI0aW0.CG3cmPC.BpDiIGqRGK', NULL, 1, 0, 0, NULL, '2025-08-02 09:39:55', '2025-08-02 09:39:55'),
(52, 'AFIA AFRIN ROHANI', 'afia', '$2y$12$La4IYcVOm6qfzwDfc2sQVOenmZmbclm/9d8i.7YTnnHxBVxI4Z332', NULL, 1, 0, 0, NULL, '2025-08-02 09:39:56', '2025-08-02 09:39:56'),
(53, 'NUSRAT JAHAN FAHIMA', 'nusrat', '$2y$12$ZYcUlJOFVKHbIelmbbRHZuN3m2UELX8HgnQqT5l7Hp2kDSiluZnD2', NULL, 1, 0, 0, NULL, '2025-08-02 09:39:56', '2025-08-02 09:39:56'),
(54, 'MIRA AKTER MOU', 'mira', '$2y$12$22rAqjdblDXF8Lr2BQq5kOhiApYNs1hg6bIBfm3gDKEWhkDyJZIxG', NULL, 1, 0, 0, NULL, '2025-08-02 09:39:56', '2025-08-02 09:39:56'),
(55, 'NUSRAT JAHAN MAHIDA', 'nusrat1', '$2y$12$GHXGnkqRh35ZF4gBLS9ysuaIZvEVZxlhh4AXPnSF7zuTBs3yRsC3O', NULL, 1, 0, 0, NULL, '2025-08-02 09:39:56', '2025-08-02 09:39:56'),
(56, 'ABIDA ANJUM', 'abida', '$2y$12$i2mMe73MMv/b.ftziiN5jOwwyIWVRemcKyqu9fQvM94ROcnJgG1LC', NULL, 1, 0, 0, NULL, '2025-08-02 09:44:56', '2025-08-02 09:44:56'),
(57, 'AFIA ABIDA', 'afia1', '$2y$12$Tmk8.sSOdSsj4va5f3jtXeVTfZNQ3FVhkGCl5xAsJ6DrnuSm9WsKq', NULL, 1, 0, 0, NULL, '2025-08-02 09:44:56', '2025-08-02 09:44:56'),
(58, 'FABIA AFRIN FLORA', 'fabia', '$2y$12$MMMY8U7Em1L7LM83YpLpJOIPZZzjzyGll5/zPlASDOq0rptbVosAm', NULL, 1, 0, 0, NULL, '2025-08-02 09:44:56', '2025-08-02 09:44:56'),
(59, 'FABIHA ZANNAT', 'fabiha', '$2y$12$15RYTSSbcALK/.QEY9RAJuPI.H3Q.9RMx1WFvGN9so8hBjX9b6AzG', NULL, 1, 0, 0, NULL, '2025-08-02 09:44:57', '2025-08-02 09:44:57'),
(60, 'JANNATUL MAOWA', 'jannatul2', '$2y$12$DAJ3Aq/NulmudUFYnhzp7.3AzR3wPuW8wB2U1uNkT2XTXu5uRGv0e', NULL, 1, 0, 0, NULL, '2025-08-02 09:44:57', '2025-08-02 09:44:57'),
(61, 'KANIZ FATEMA', 'kaniz', '$2y$12$Biih7AcY.V7OVimhYPuG0OrQdlHyGAzxO4kt3Ntcu1je4lQGyF6My', NULL, 1, 0, 0, NULL, '2025-08-02 09:44:57', '2025-08-02 09:44:57'),
(62, 'MEHRUN NESAR RUKSAR', 'mehrun', '$2y$12$CRTax4oX8RM2ea5e4a74FuytSoFANBEHGzsVnler1jYvamvWw4Hd6', NULL, 1, 0, 0, NULL, '2025-08-02 09:44:57', '2025-08-02 09:44:57'),
(63, 'MST. SABIHA ZAHAN', 'mst.', '$2y$12$XrZ4e3YKDi7W2wSmeLJQYO3jd50d/rReKPecdWfAHIPQI/Lohf.zW', NULL, 1, 0, 0, NULL, '2025-08-02 09:44:57', '2025-08-02 09:44:57'),
(64, 'NOWSHIN ANBAR', 'nowshin', '$2y$12$sM5IqKuuE0cojOE/8GbEp.Z4lmJYXz8kdqnrO8nBpMtUjchpbcgw.', NULL, 1, 0, 0, NULL, '2025-08-02 09:44:58', '2025-08-02 09:44:58'),
(65, 'HRITOZA PAUL HIYA', 'hritoza', '$2y$12$Zpj67Fbpk2jFL/o6nV40UupUX5ULUfYla0dO6TF0FiP8ws5dsyYH2', NULL, 1, 0, 0, NULL, '2025-08-02 09:44:58', '2025-08-02 09:44:58'),
(66, 'SURAIYA ISLAM', 'suraiya', '$2y$12$dJYbYt4aFA1TY5vRzNslw.2mj2ddYpeTZPlk2r8.r7b/V5rx2FhJS', NULL, 1, 0, 0, NULL, '2025-08-02 09:44:58', '2025-08-02 09:44:58'),
(67, 'TANISHA JABIN', 'tanisha', '$2y$12$9rnnujSKl942Qc2rei4QjOjNfu/TVhQfcG3I9ipFL0zII.nD1Lc32', NULL, 1, 0, 0, NULL, '2025-08-02 09:44:58', '2025-08-02 09:44:58'),
(68, 'TASLIMA AKTER', 'taslima', '$2y$12$.KDdQ8cVdZfZ51qDEAfWhu8ySnb3PzeXy/Q133v4v0FM7Uy62PBq6', NULL, 1, 0, 0, NULL, '2025-08-02 09:44:58', '2025-08-02 09:44:58'),
(69, 'UMME HAFSA PATOYARI', 'umme', '$2y$12$To8GMegJOPvEiUyqhH.MouwUVwImvCs03n5z2TKxnZfY5fFJbAF7O', NULL, 1, 0, 0, NULL, '2025-08-02 09:44:59', '2025-08-02 09:44:59'),
(70, 'ISHRAT JAHAN', 'ishrat', '$2y$12$09RucmtO3Ro60VBoy5ghxeX8oCXK3gtD0xPH.fq5Xy.mXectJTnnu', NULL, 1, 0, 0, NULL, '2025-08-02 09:44:59', '2025-08-02 09:44:59'),
(71, 'MOUMITA MOSTAFIZ ILMA ', 'moumita1', '$2y$12$ajKBLQUCEL3M78sDwkPv0eyiOA/rdgYlXQthEc4YxrTiE0ubmSxoy', NULL, 1, 0, 0, NULL, '2025-08-02 09:44:59', '2025-08-02 09:44:59'),
(72, 'SARAFIN NANJIBA ', 'sarafin', '$2y$12$.lUHCpwbEzVy7PlwXOhofuKLNNXA6p9sCk2nF8/jrHthwnbe3IICm', NULL, 1, 0, 0, NULL, '2025-08-02 09:44:59', '2025-08-02 09:44:59'),
(73, 'WASFIA JARIN NAIZA ', 'wasfia', '$2y$12$9NEiFdEPQaeFlz4a9KqxGuRZ0zwrpd.Duw2SJ4xVaWNL9PKmKKbS6', NULL, 1, 0, 0, NULL, '2025-08-02 09:44:59', '2025-08-02 09:44:59'),
(74, 'SALMA AKTER SUJANA ', 'salma', '$2y$12$20T5qO5/CPa5xDYz.sTz4O/6BmQtSI5vBd.5X3AgN1DCmSyMwiWbe', NULL, 1, 0, 0, NULL, '2025-08-02 09:45:00', '2025-08-02 09:45:00'),
(75, 'FARIHA JAHAN OISHI ', 'fariha', '$2y$12$0rGwXa6PqjAyq6KCIYvKX.1Km/z8oLtFE5qCKF0LjwCmnCUGty0le', NULL, 1, 0, 0, NULL, '2025-08-02 09:45:00', '2025-08-02 09:45:00'),
(76, 'ANISHKA DEBNATH ', 'anishka', '$2y$12$HY8IBsZUZo0jdrh8jo6.duHB/ll139wm9IU7bVIkINntED2RaJ9fG', NULL, 1, 0, 0, NULL, '2025-08-02 09:45:00', '2025-08-02 09:45:00'),
(77, 'TAHMINA NIJHAT SAIFA', 'tahmina', '$2y$12$XDNOLC4Fe.1J6HprJi8TpuLe.M2fW2eunehjpr.vnyb5Q03Bh8cfG', NULL, 1, 0, 0, NULL, '2025-08-02 09:45:00', '2025-08-02 09:45:00'),
(78, 'NOWRIN AKTER ARFA', 'nowrin', '$2y$12$2HReD6QQSzY.2HmADFUhyuivzS0oEddNX29CCo2am3ceRdNgrYvry', NULL, 1, 0, 0, NULL, '2025-08-02 09:45:00', '2025-08-02 09:45:00'),
(79, 'SHARMIN AKTER ARFA', 'sharmin', '$2y$12$Agah1U4XgtYMQKfL8RuV.O4TXLmAtHyTmsKjGUxfBf7LzD6urVslq', NULL, 1, 0, 0, NULL, '2025-08-02 09:45:01', '2025-08-02 09:45:01'),
(80, 'SABIHA AFRIN TAHA', 'sabiha1', '$2y$12$Eq6lGy8TZ1bPerbprrKxYOIkamG4CXoVZ3T0r8LR6IXI.K0TPGifa', NULL, 1, 0, 0, NULL, '2025-08-02 09:45:01', '2025-08-02 09:45:01'),
(81, 'RITI PAL', 'riti', '$2y$12$6GUaphSSq1MnPfyQugIPsO0KOFXL1IVDJoSkq1xny6wGR/ZCwnZTS', NULL, 1, 0, 0, NULL, '2025-08-02 09:45:01', '2025-08-02 09:45:01'),
(82, 'MALIHA TANJIM SOHA', 'maliha', '$2y$12$LynktBWL5CqATSvvuqwldOzPRaN3dq0U5VI9usGe2m21DFJpFDrFu', NULL, 1, 0, 0, NULL, '2025-08-02 09:45:01', '2025-08-02 09:45:01'),
(83, 'AFRIN JAHAN TUMPA', 'afrin', '$2y$12$wZvxeSz0ucedykGyu5d4uOrxPhsfTFJolahnp2Z01VZezNtNT7yYy', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:26', '2025-08-02 09:49:26'),
(84, 'AFSANA NITI MIM', 'afsana', '$2y$12$oArSOQK6uDfb/DjXDR8B5.8.sPfMMMHH2ie9UgAqV2Ys/6bQhNg86', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:26', '2025-08-02 09:49:26'),
(85, 'FATEMA AKTER', 'fatema', '$2y$12$i3fxO1tL0FXlr3OeXNV2p.DJ7lVHcQ6ig9aXYThY7RNTeGjJlE8ie', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:27', '2025-08-02 09:49:27'),
(86, 'FATIMA TUZ BUSHRA', 'fatima', '$2y$12$oLmh4ZMzThC6xhjnoK1IMejvYKwUEOSY6K6DX63CHiAp1PZTb8X5C', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:27', '2025-08-02 09:49:27'),
(87, 'HIYA PODDER', 'hiya', '$2y$12$9Zay/2tNYCPlI1UIl0N7Bu6iIJ.FdxWFdb3y6aK5j.VTHhRMJCEbu', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:27', '2025-08-02 09:49:27'),
(88, 'KAMRUN NAHAR NAJAT', 'kamrun', '$2y$12$aOb/pxaztOKkLOZXk9.dXuParYO/vfYodGcGqrO.zW7m8HjoVUOlO', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:27', '2025-08-02 09:49:27'),
(89, 'LAMIA KHATUN OISHI', 'lamia', '$2y$12$OYQy89QBUcfsC0TODTUXIuVghEd41cEeFpKHpCqIJVJ2g382fYZ.2', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:27', '2025-08-02 09:49:27'),
(90, 'MEHZABIN ISLAM', 'mehzabin', '$2y$12$8GhSl0AmiAzHjqV8w9supOVz/elBW94qqn04kb7XfomqFHoAzg0DS', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:28', '2025-08-02 09:49:28'),
(91, 'MUNTAHA SAYOR', 'muntaha', '$2y$12$w7zCGUuTdh7EU.Pd6bKVPuXPWlECt4yVYFT7CITqIzeSS5holgOWW', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:28', '2025-08-02 09:49:28'),
(92, 'MUNTAHINA UJMA', 'muntahina', '$2y$12$qpAv5PcXrzEuIvlphKtRTOAMz3mSOPnvYivgdIk6RDNoNVARUNT6e', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:28', '2025-08-02 09:49:28'),
(93, 'NAZIFA REDWAN', 'nazifa', '$2y$12$lv19vmw2XTxLi1DGbzpIC.tgJPA3LbbrLWGzPC9CZxAANACkzn.WS', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:28', '2025-08-02 09:49:28'),
(94, 'NUR E JANNAT', 'nur', '$2y$12$Go9w6PwxqZ3uTtrz3CattO1Sl52XGaUpR0h2oz9fYWMJgPXO.iNYO', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:29', '2025-08-02 09:49:29'),
(95, 'NUSRAT JAHAN ANANYA', 'nusrat2', '$2y$12$tWjrX/heEcy1hYH3a2qdlenrHO8BvLJPSiysy58pNT9EbpFT9VRcq', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:29', '2025-08-02 09:49:29'),
(96, 'NUSRAT JAHAN ARFIN', 'nusrat3', '$2y$12$cJZR5fgh0pYJJ3PkfNmno.hLEMlr7zPHkf45cQwOON0hW5v7brgTm', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:29', '2025-08-02 09:49:29'),
(97, 'NUSRAT JAHAN NINID', 'nusrat4', '$2y$12$INOQH0.DJMZwHV.269ztaulgdcYPUDsJ8alnFbKnBmbm91Zd8Z33W', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:29', '2025-08-02 09:49:29'),
(98, 'NUSRAT JAHAN TUSME', 'nusrat5', '$2y$12$w8jJvjL1GtFT/Vf2d9BS9uswFkA3IrBI8ogmM3oZyxeJPm8wES4.a', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:29', '2025-08-02 09:49:29'),
(99, 'PRIYONTI PAUL', 'priyonti', '$2y$12$LQzWNSH3qFld5EFTUsh9Fe4tNMnM8sP/llWKmrZEc0HLJnOpg03oy', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:30', '2025-08-02 09:49:30'),
(100, 'RABEYA BOSRI', 'rabeya', '$2y$12$4COt/o0SBD8liVfOPGsDjuerSfw7lN1DsvSPwd6sB67SJW5Ax1eWe', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:30', '2025-08-02 09:49:30'),
(101, 'RAMISA AKTER TAFSI', 'ramisa', '$2y$12$VX6YWpzoFd2BESBzusLYYuOnJ7wc4GLm446vuc59b8DEnru7Zp/TS', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:30', '2025-08-02 09:49:30'),
(102, 'SABIHA SABRIN', 'sabiha2', '$2y$12$128Zrj4GAu/kin9WwIayU.BK3fHd0fzhNtqlkrBs5vorlvy67EKDy', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:30', '2025-08-02 09:49:30'),
(103, 'SADIA ANAM', 'sadia', '$2y$12$PiLG99TS7HDKpChM27Ry3ezJZRd1gLQpd08N2D4r5dIZZolv1Dtdm', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:30', '2025-08-02 09:49:30'),
(104, 'SAOMEE SAFA', 'saomee', '$2y$12$.4QByUmzoFJBo4Y.PYBM5OmlDwFsRNB6dRaKOs7pWNSV3BwbdH1XG', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:31', '2025-08-02 09:49:31'),
(105, 'SAFA ISLAM', 'safa', '$2y$12$FwEzjOSzQTNOghgurjAFUunXu9w2Dr3tc4jsgscK0zI8b7mwuS8n2', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:31', '2025-08-02 09:49:31'),
(106, 'SAMIHA AFRIN', 'samiha', '$2y$12$/u1URQp.vIM1.9L2WvKK8upk/XrB9Py13Uq4wBHPNWQUpb7KLTlCK', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:31', '2025-08-02 09:49:31'),
(107, 'SAMIYA RAHMAN', 'samiya', '$2y$12$kFvEzyfb/0hhW2Biy38ISOLD5iNs8U0gYbg28AKVtlmLU7VosfJOu', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:31', '2025-08-02 09:49:31'),
(108, 'SANJIDA HUSSAIN SAIKA', 'sanjida', '$2y$12$4DE8RCZ0Pn1ggGMyraNbNePyjwKoqfpI7yHW8aYhZZ.Rhd7tg9WaW', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:31', '2025-08-02 09:49:31'),
(109, 'SNEHA DAS', 'sneha', '$2y$12$0tMXvDSDWnMtau1/6IvjpONcxgJkuw46MxZAsuX/I36Gh0011pNuK', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:32', '2025-08-02 09:49:32'),
(110, 'SUMAIYA AKTER MIM', 'sumaiya1', '$2y$12$yqujCgbOnErOe0FyrmRDgu6VKkaUH/RazsukiPUJtaOB0b2A8bj9.', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:32', '2025-08-02 09:49:32'),
(111, 'TABACCHUM TANHA', 'tabacchum', '$2y$12$u2kzvm68zd42UOERX9Qvlunn5pawfQdlSpOwW4QZyxUVynyCo1ug2', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:32', '2025-08-02 09:49:32'),
(112, 'TABASSUM IKRA', 'tabassum', '$2y$12$RVfFvDxGHmVVVXFPMQ2oPeD70Uiy7SGqh0Y7cd8TInAVvJfcBL26u', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:32', '2025-08-02 09:49:32'),
(113, 'TAMANNA AKHTER', 'tamanna', '$2y$12$gCt74L9onRb98GuF8PyaserFCM96NvHbNNsyGmBgW8Bj0QbZ9lwOi', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:32', '2025-08-02 09:49:32'),
(114, 'TAMANNA AKTER', 'tamanna1', '$2y$12$nW/TJPaSBzR1xw9umJ5ZC.AugsV6G0DXGEHd6kQW.Om35MbdzjQI.', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:33', '2025-08-02 09:49:33'),
(115, 'TASFIA NAUSHIN SIMI', 'tasfia', '$2y$12$W6EBBOTNbtstdj9rui.4QeFsdzoBPHGw90aBeu9Ex.53uICGM8oua', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:33', '2025-08-02 09:49:33'),
(116, 'TASFIA TABASSUM TOA', 'tasfia1', '$2y$12$ZlWFQ/Vtk/4m5GSPoaoQYeMhbIccEcHZe2gXEmL/Hw9eDb/PV78pe', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:33', '2025-08-02 09:49:33'),
(117, 'TASNIA PUTUL', 'tasnia', '$2y$12$4ucZJBGQpzwpEM7wLyKmHu7I5krTu95bymkAhPoRbdXEihIgWmNcK', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:33', '2025-08-02 09:49:33'),
(118, 'TASNIM MARIAM', 'tasnim', '$2y$12$0bOqd6RioIH6SJ6ouEA4Gu90YZm/nEtL/whlN81NvEqXT9VrwSHPS', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:34', '2025-08-02 09:49:34'),
(119, 'WARISA TASNIM', 'warisa', '$2y$12$I4KBbB1INBRKC6ScY/MjaOe2/iJd1u3W1eR6BUgvbn9yA99FlqjP.', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:34', '2025-08-02 09:49:34'),
(120, 'SURAIYA HASAN', 'suraiya1', '$2y$12$zt/fBF8ikhCOucl0MCfUQeS507USdy8H2ZEVjv6Ch2ZY/8RlW.khi', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:34', '2025-08-02 09:49:34'),
(121, 'SAMIHA HASAN NEHA', 'samiha1', '$2y$12$eqMcK1qd9h4v6ziDC0rDg.9RbdmSrURVIQkhM0nlRb4Dii7BiNjpq', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:34', '2025-08-02 09:49:34'),
(122, 'SUMAIYA MEHJABIN ARSHI', 'sumaiya2', '$2y$12$LGvomPvKLfedS3s7RCqxxu.Z8zZGD2Q3ir59p3g7YxXdAElLHgcvy', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:34', '2025-08-02 09:49:34'),
(123, 'TAFIDA BINTE JAMAN ', 'tafida', '$2y$12$.NAN.RynMybAK1UCzJBdpuzmzC0BH3uXWXuP/lhOULghMJZY9RY0K', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:35', '2025-08-02 09:49:35'),
(124, 'FATIHA TASNIM NABA ', 'fatiha', '$2y$12$QejbaNZpsIFGAKSjutv4le9xiiCmU.XAWGr9etlT6p91EQHJ0iZpC', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:35', '2025-08-02 09:49:35'),
(125, 'ASFIA SULTANA ', 'asfia', '$2y$12$wLGcT4nTJ3/LIzbfOaIXsOa5OnrrEAwbEB3ZTlIsxW/p//q0lgO.q', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:35', '2025-08-02 09:49:35'),
(126, 'MAISHA TAHRIN CHOYA', 'maisha', '$2y$12$3CnBVyrAubdIYnSNTZaAH.EoSFM5A5lqJlm1GoBKO2PAC33dNkeGG', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:35', '2025-08-02 09:49:35'),
(127, 'SADIKA JANNAT RAHIMA ', 'sadika', '$2y$12$JnLg8dfja1sgGnIzdDVdqOyFIna1hgOrMUO1Byv6cMobCKYXX5r.6', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:35', '2025-08-02 09:49:35'),
(128, 'JAFRIN ANAM ', 'jafrin', '$2y$12$VclnPg9OLbBC01Il/YxQIu6sKOg98DRtmmGzL98omP6AhwtrAdez.', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:36', '2025-08-02 09:49:36'),
(129, 'AFSARA ISLAM ', 'afsara', '$2y$12$k3O.6nwQPmIjm0pYrkkxVedVsmOd/0Xgzyz9Co1u.I8kPiamgvJoi', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:36', '2025-08-02 09:49:36'),
(130, 'OISHI ISLAM ', 'oishi', '$2y$12$GXCOP5smnUowD8hHwW.PkO2vPjpsC9pEY0jv5iNh08nnbiTpHViLe', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:36', '2025-08-02 09:49:36'),
(131, 'AFIA MOBASSBIRA ', 'afia2', '$2y$12$U4KZOxP343fZuLyokTWTaeu61bDPX4V4YRPZmEeRWzQEvEd2F/FdG', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:36', '2025-08-02 09:49:36'),
(132, 'MAHZABIN AROB MALIHA', 'mahzabin', '$2y$12$l23EG.fw.DrAl6RZMiuW9eeZlIx303YN81/yDAKkorbtFaxHcoBTy', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:36', '2025-08-02 09:49:36'),
(133, 'FATEMA TUZ ZAHRA', 'fatema1', '$2y$12$yuAE8D76vl4VHp8Gc0hh3O.D1u5mKvog0lr.nu77CNyCjp5rXUHVC', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:37', '2025-08-02 09:49:37'),
(134, 'NUSHRAT JAHAN ISHRAT', 'nushrat', '$2y$12$jF7HpICeRlCEkTnhADEG8OFsxoJomOCopLfiUSXnAY8GeEdkjexCm', NULL, 1, 0, 0, NULL, '2025-08-02 09:49:37', '2025-08-02 09:49:37'),
(135, 'ADIYA AZMAIN SHOPNO', 'adiya', '$2y$12$RoPLqGKEVdSgNvX/uSCuj.GXYc3On3WDG.HrIoYCtzx.0Ai8t2/0a', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:41', '2025-08-02 09:52:41'),
(136, 'AFIA ANJUM', 'afia3', '$2y$12$KvHt0nVrzIgK37hcKUb7wuD5pFDt1YSUa2EdMU0U4H9le8utTWCHW', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:41', '2025-08-02 09:52:41'),
(137, 'AFIA SULTANA', 'afia4', '$2y$12$VoSnz2Mc1a2dUNW0bwsxqeTJ35Ux4WTeDm6Ot5LPOr8dvny4JPDCy', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:41', '2025-08-02 09:52:41'),
(138, 'AFRIN AKTER JANNATI', 'afrin1', '$2y$12$hmdbItwKxhzQ7lEt9b7PEefpIB92YvF6VjwO/DUKyqimztrsVlavi', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:41', '2025-08-02 09:52:41'),
(139, 'AYSHA AFRIN IKRA', 'aysha', '$2y$12$LSHTXuW9Mb1l5B148CHUz.NMBqpkC2RV2QBiKjQi7OQXiFtQ721A2', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:42', '2025-08-02 09:52:42'),
(140, 'FATIMA TUZ ZOHORA', 'fatima1', '$2y$12$MZ25nlSyAfVHpuO3s0w11uCZ217HDew3W2GurUYZ2pfmpcXvSGGwG', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:42', '2025-08-02 09:52:42'),
(141, 'ISRAT JAHAN', 'israt1', '$2y$12$MCCaZzn65QjeCp5NrmJjV.dlkTkl4iQxqeAbc4zQdJ7Nh9jlcL2LO', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:42', '2025-08-02 09:52:42'),
(142, 'JANNATUL FERDOUS', 'jannatul3', '$2y$12$qmaGNKg4A12gwfeXB1HOjeezOso0e3vP/dJvy6K55BmcDfTwIOrL.', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:42', '2025-08-02 09:52:42'),
(143, 'JANNATUL FERDOUS NISHITA', 'jannatul4', '$2y$12$9f8snViH8nGQn38u6wCVueRX9nZtk6QoDTzc2wPk/L2TXWKIGSfLC', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:42', '2025-08-02 09:52:42'),
(144, 'JANNATUL FERDOWS', 'jannatul5', '$2y$12$6Ecu2oxjlgZEpXM2ioQ50OIkv5bCxoLg108xvpqX.tILxfBNX0Uvm', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:43', '2025-08-02 09:52:43'),
(145, 'JARA AHMED', 'jara', '$2y$12$7Zw0dxQgG/KdtlwuFITDte0k6BH/yWmh2wUtp74fmGAiIS/Rhc.ky', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:43', '2025-08-02 09:52:43'),
(146, 'JARIA AZIM', 'jaria', '$2y$12$J8fy8dwUW21knBSdJ3ztmeqpvTOXkxliWOdDmYk9YicIgAGhBgPWK', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:43', '2025-08-02 09:52:43'),
(147, 'JARIN TASNIM', 'jarin', '$2y$12$UYn39eGe0xg2x3N77.7SMOj/C.x1xX9UONAxGM139gjeSZ/.sg6hq', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:43', '2025-08-02 09:52:43'),
(148, 'KHONDOKER ADIBA MEHJABIN', 'khondoker', '$2y$12$Aq8XpgfpDqouJGwpDWI1Ke707A.b3ckZYWMfSw6xNk97pwFzDWrX6', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:43', '2025-08-02 09:52:43'),
(149, 'MAHDIA TASNIM HIBBA', 'mahdia', '$2y$12$x4qvwV8U5SzI86pYnMtadOfm9zOGcsIJ0H5bpubjJiBwdBHt0zTE6', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:44', '2025-08-02 09:52:44'),
(150, 'MALAIKA AKTER IKRA', 'malaika', '$2y$12$jTtcj.sH2GoPTqiHceJlWeLYJjKqTlipGd8Oz6Y45KM0HvpoGcHpG', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:44', '2025-08-02 09:52:44'),
(151, 'MALIHA AL JANNAT', 'maliha1', '$2y$12$uWJnzbHSkMXydI0CKW97EeiKrTiSFCumJ7JF73BMIWDBca9Z87l9G', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:44', '2025-08-02 09:52:44'),
(152, 'MANSURA KHANM MIM', 'mansura', '$2y$12$9MzwBEngHtLu4azT.k8pgui4goGMfU97JOvADkfZ7a8jDf6.pk.Y6', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:44', '2025-08-02 09:52:44'),
(153, 'MARYUM AKTER', 'maryum', '$2y$12$8NSAm6nTR2pG3Bw6LNqAmu9uaitZnYXBRIwDYVwxpSyTpM2TjsXR2', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:44', '2025-08-02 09:52:44'),
(154, 'MARYAM JANNAT', 'maryam', '$2y$12$PLBIT.DYkhzayHBVmHpFGu/o2Z4ehk3Nc.S5GdAAVOf6NmR1u7LIS', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:45', '2025-08-02 09:52:45'),
(155, 'MASHIAT JAHAN TUBA', 'mashiat', '$2y$12$n0GlCxDIQ6KxBBzxFOkHCO7IEGmuyTVc85.QQjNIc7nYvJLwsUvxS', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:45', '2025-08-02 09:52:45'),
(156, 'MINHA AHMED MAZUMDER', 'minha', '$2y$12$f45ykGWs9f6ARN6kHkj.FOJSfaX7JyMPu5ZOHvKMvVGd.nTjBgyj.', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:45', '2025-08-02 09:52:45'),
(157, 'NABIA SULTANA ZARA', 'nabia', '$2y$12$H2GTND9QAZ83HFDmRo1XU.idbd6EIW6AlwPF/jT/u5rnSEDoQpLVO', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:45', '2025-08-02 09:52:45'),
(158, 'NAFEJA ISLAM', 'nafeja', '$2y$12$q1ZPvo81w9Hnp8hSO9OPOuHmaqw64LV3TF6reHDSlshcJ5TfIs7bK', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:46', '2025-08-02 09:52:46'),
(159, 'NIPUN TAHASIN', 'nipun', '$2y$12$fM12VJDluYR0SRDdubq8geL/wg0eWaXDs64.PO4nU5AqbtPAEwpku', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:46', '2025-08-02 09:52:46'),
(160, 'NURJAHAT LAMIA', 'nurjahat', '$2y$12$pQxWoBkKu5YuT9bmvKQgg.EPx0xv1NTPeIdKV6jt2u1A50z2dXHtC', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:46', '2025-08-02 09:52:46'),
(161, 'NUSRAT HOSSAIN TASMIA', 'nusrat6', '$2y$12$c53u8EgqqKLXkhhQuMxlSOfBLH.Oq3FRw6GJNuLJFVKaE4Z7vlzJm', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:46', '2025-08-02 09:52:46'),
(162, 'NUSRAT JAHAN', 'nusrat7', '$2y$12$nqTa5eNPVCdIyO9Jn0pbuOsT4A4jTtHb4K09vB7sL02JD5G2yU/eG', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:46', '2025-08-02 09:52:46'),
(163, 'NUSRAT MEHJABIN', 'nusrat8', '$2y$12$htTqCrLE6rA.NcCkjeqdcekE2Jilm9Mu.xm.vrdcSiKrdyf/JyMcS', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:47', '2025-08-02 09:52:47'),
(164, 'RABIYA JAHAN HAFSA', 'rabiya', '$2y$12$eTKUCrkoBkHY19GztLQLPeDLUh64U0xZa6xNqmmCwBhvNs1mRBxtO', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:47', '2025-08-02 09:52:47'),
(165, 'SABA UMME MARIAM', 'saba', '$2y$12$9RZuMEHGCgvupxqGcjoyI.jRVFF1PLcrb70rlkPR5pJvf5zV4qPbW', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:47', '2025-08-02 09:52:47'),
(166, 'SABIA BINTE HABIB', 'sabia', '$2y$12$uyUt6R1KshG5FI5LP1/b7O2doBgz8dlhjQhpuukiEVdUAEirDaxk6', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:47', '2025-08-02 09:52:47'),
(167, 'SAFIA JAHAN SINTHIA', 'safia', '$2y$12$4On92UpOR1cV0eY67SmBHe/NCNunDSiHdbcRwXj52LJx69dk6HKd.', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:47', '2025-08-02 09:52:47'),
(168, 'SAIARA MOHIUDDIN LINA', 'saiara', '$2y$12$w/yb7QTWXGoi8h2EyzlkXuRQJf3E9vG4I/LtYn9wX5haY7JkVPeNS', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:48', '2025-08-02 09:52:48'),
(169, 'SAIKH ESHA ISLAM', 'saikh', '$2y$12$gwIweEFfOCmmkNS7xBmDYu182uHi6nby/nsAMoc2fPm7hHVc4cVRK', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:48', '2025-08-02 09:52:48'),
(170, 'SAMRA PARVIN', 'samra', '$2y$12$/fgLUA8fNHgac6Q34s1g/.i2CI1rLyW1HNf2CagHn9ShQ66Gj60QG', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:48', '2025-08-02 09:52:48'),
(171, 'SARAF JAMAN SHARON', 'saraf', '$2y$12$zD.y1s2ZPgZ.JGZCxMA2Feco9/PRuagutLGjJJBoZUedZPOl.sszO', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:48', '2025-08-02 09:52:48'),
(172, 'SARAH ZAMAN', 'sarah', '$2y$12$u1XTsMbq.TjY6jbOOm3uJuuIPhHZrK1kEQRfwRfwIizyCgZmXDT.6', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:49', '2025-08-02 09:52:49'),
(173, 'SAYANTI DUTTA ESSAY', 'sayanti', '$2y$12$CnBPBKNNewl/NVEmMlAzxuisP.o9IpGNA6A7jkJYHp8cZR6iuW9OS', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:49', '2025-08-02 09:52:49'),
(174, 'SHAEKA ISLAM', 'shaeka', '$2y$12$eYcWdyB/HQyuBXrdVVev6epCukxFKRyjS7mXydhnNsVj07/NBJCLq', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:49', '2025-08-02 09:52:49'),
(175, 'SHOHANA AFRIN JUTHI', 'shohana', '$2y$12$dHz5oFMN/sSUO9WKoHcWg.HaXKpdwNOR7yLrZs8c0jvg2MI6fNqTu', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:49', '2025-08-02 09:52:49'),
(176, 'SOHA HOSSAIN', 'soha', '$2y$12$Bbovzc7021BfUkUaN4p0/OmC/5fubjXPrk47WQ0ielPJFRPK9PTWq', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:49', '2025-08-02 09:52:49'),
(177, 'SUMAIYA AKTER DOHA', 'sumaiya3', '$2y$12$mWSWEQml2ltdY8WHnUFdJuNwsrSVmxlCSjnSWFmRROvqYcEDh8NKe', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:50', '2025-08-02 09:52:50'),
(178, 'SUSMITA SAHA ATOSHI', 'susmita', '$2y$12$GBhE8dSuIADqqjZPiJosfOi9OEk6Dyl9E9GAAmLwiDazy/arBRB2q', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:50', '2025-08-02 09:52:50'),
(179, 'TABASSUM SAYEED', 'tabassum1', '$2y$12$urZexfcTCwDverEiWEcc6eYcO6e0J2LQuWvcxPCuI2zIs4afgrg/y', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:50', '2025-08-02 09:52:50'),
(180, 'URMI KHAN RAISA', 'urmi', '$2y$12$sLhctIRmhTHF6X.1ADFBCOgc3JCIBoaiJJJXEYgzpZ/XUJH0IGBg2', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:50', '2025-08-02 09:52:50'),
(181, 'WAFIA ZAMAN', 'wafia', '$2y$12$QQC36AHban5Y1HY2QX.U/uoTKaAa75mEG8QNoRwHMQDBpTNSgSzHu', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:51', '2025-08-02 09:52:51'),
(182, 'YASNA MAHRISH', 'yasna', '$2y$12$.ONDhcNrhtozgGNkASDjw..5Olj8h7BEIkXb8M5ikF0orEHggjtqK', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:51', '2025-08-02 09:52:51'),
(183, 'JAKIA ISLAM', 'jakia', '$2y$12$6WkgfltZATBTHZtDx.AsIuQEb.X/BvCLsdCOeV1UZC.SOfQlsEEt.', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:51', '2025-08-02 09:52:51'),
(184, 'JANNATUL NAIM MADURJO', 'jannatul6', '$2y$12$NcaoNyKW9BzCIbmJVGiv6.w1j62NZcFOUYQZ7gPwJw9S0a15OIn3O', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:51', '2025-08-02 09:52:51'),
(185, 'JAHIN NASIBA PRANON', 'jahin', '$2y$12$CLVdYQXbOu9zMifffsjXW.VKZ2NfwJNsBEycmNH/34ph1hEDpPEbq', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:51', '2025-08-02 09:52:51'),
(186, 'SUMAIYA AKTER ', 'sumaiya4', '$2y$12$DMhbTEk4KUzVO/VhZiLuoulbAagOzSCHgtV8G6/U0Krvj1YBBWxGK', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:52', '2025-08-02 09:52:52'),
(187, 'MAHNAZ RAHMAN ', 'mahnaz', '$2y$12$R00ikzki4.7PP0lPnCMt4.uXm7D6Qqvd/CPDXIwtw1qANBlvMzpwq', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:52', '2025-08-02 09:52:52'),
(188, 'SUMAIYA AKTER MINA', 'sumaiya5', '$2y$12$tD/UWm.KZrtyjez5rUvLienp72HMshHMjCNr6q6UTl.WO.ZbfWqtm', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:52', '2025-08-02 09:52:52'),
(189, 'SARA TAKIA', 'sara', '$2y$12$N0ReMrr93JBqgmFyGD5yxOQPIuK8zd2h7y8CPhs0FZD873aM8JPbq', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:52', '2025-08-02 09:52:52'),
(190, 'MARIA ISLAM SINHA ', 'maria', '$2y$12$2LpU6P8HR1FvgDDfGTCoKORakkOjfHFrNLieVnEpzESbqVei0UzsK', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:53', '2025-08-02 09:52:53'),
(191, 'JANNATUL FERDOUS ALEA', 'jannatul7', '$2y$12$tRSF9DtswQwdyZpJX.I8dO2a5MnYX/VDku9ZkMY8MqdRQ3mSl.wrO', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:53', '2025-08-02 09:52:53'),
(192, 'JARIN ANAN RAISA ', 'jarin1', '$2y$12$SWjBbSqBvnq.lPpMAoPORO9DkJjCX/XlQglgfdab5MnUF2eapbpSS', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:53', '2025-08-02 09:52:53'),
(193, 'SAMIHA HAULADAR ', 'samiha2', '$2y$12$1E8BiOy9kVxp5w9UR6tgWOKKRyZCUCCZg31o7tVLK8xoR2XqtfUkO', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:53', '2025-08-02 09:52:53'),
(194, 'SIPTI AFIA AKTER KHUSHI ', 'sipti', '$2y$12$pogtDAZ/U/INYZkuQYMHXe9REcvr6RbuyTbF2jjRY0rhKnGDtYxg2', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:53', '2025-08-02 09:52:53'),
(195, 'JANNATUL MAWA MALIHA ', 'jannatul8', '$2y$12$HRHMqHx4dFWIFGT241vVnO/ZfKwxLzkP6zcjMKb2CsLBBXj9bZmdu', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:54', '2025-08-02 09:52:54'),
(196, 'MARZIA ', 'marzia', '$2y$12$TC.hIbDBGpndFWwZktb2e.up0J9X245Lk.0ImShUc5UJfK3IBbEqy', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:54', '2025-08-02 09:52:54'),
(197, 'MUNTARIN JAHAN ETI ', 'muntarin', '$2y$12$dKlE1h3fY9N.YYgmgLaeIe3if6255Fm0kI5L.V9Tn3RLafV0zPSLu', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:54', '2025-08-02 09:52:54'),
(198, 'MAISHA AFRIN NILL ', 'maisha1', '$2y$12$nzhDuXhDF15BQ59qRUGmBuDhlr.J9lrAc0eod3TLab6ES8yvf46lG', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:54', '2025-08-02 09:52:54'),
(199, 'MAISHA TABASSUM OISHI', 'maisha2', '$2y$12$IMxsLrzofRy7BDbIa1Mzc.0CmzMvW3KZyx33cr2uBrkSI64DNSJ5S', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:54', '2025-08-02 09:52:54'),
(200, 'KHADIZA AFRIN RUMPA', 'khadiza', '$2y$12$WZL6FmEqxpYqk/M1EMGaD.0wJTCTwnzzp/fjmn5GuvQ5IwKMniSIq', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:55', '2025-08-02 09:52:55'),
(201, 'HAFSA ISLAM MOON ', 'hafsa1', '$2y$12$gwVc0odMG.WeKr9FPgfXm.F4Y9Tg3ZnTbeHwIF6ZzAlhPHZo7wU6y', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:55', '2025-08-02 09:52:55'),
(202, 'DULARI AKTER NASREEN', 'dulari', '$2y$12$bvv8LgQre4EVZwATjgkHZ.WA/OcUGEvZNf7VnGMbGohsnVRzWE.Lu', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:55', '2025-08-02 09:52:55'),
(203, 'YTABASSUM MINHA', 'ytabassum', '$2y$12$lbq3Lt7KmC7Vow77xF95POuwQCBTTvbGB2Tv80HTuNxVL6xUi7MgS', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:55', '2025-08-02 09:52:55'),
(204, 'AYESHA SADIA', 'ayesha1', '$2y$12$AJLdpY2gT.fBueczDpJ9nOG5SnPO50kUuOFj/pPIQh0E/nYtfvfby', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:56', '2025-08-02 09:52:56'),
(205, 'MARIUM AKTER ILMA', 'marium', '$2y$12$GaWnERrzHdmTBxftaQxG0eZrUfmYdcY/rGgpA1tN8hZ9DirEqZJlu', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:56', '2025-08-02 09:52:56'),
(206, 'AYNA MARZIA', 'ayna', '$2y$12$JJec5jUk4X63LYk6CsvEi.uMXUSJ4j3W2CLCFB1f7KN7YptjP2hRi', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:56', '2025-08-02 09:52:56'),
(207, 'SAFA GAZI', 'safa1', '$2y$12$7p8A93QJWPtFUAiUs.bI2eJSbcuGkLY/o8X8IPn9q1VvcRaPZp006', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:56', '2025-08-02 09:52:56'),
(208, 'JANNAT AFRIN', 'jannat', '$2y$12$5bQ.Aar16JeHNw/PIWKcj.HVWzS8l8WN74eg1z2BViK9ZOfL1ohTC', NULL, 1, 0, 0, NULL, '2025-08-02 09:52:56', '2025-08-02 09:52:56'),
(209, 'ADRITA DAS ORPA', 'adrita', '$2y$12$ZPEaYVowf0u9LD8.RkabHu8jDN1ogqcbB0pw3yEW6dWI4On8tmPta', NULL, 1, 0, 0, NULL, '2025-08-02 09:54:56', '2025-08-02 09:54:56'),
(210, 'AFIA ZAHIN FAIZA', 'afia5', '$2y$12$Qhlj0svqovwWMVRlFeV.R.iIF/KyuzMWJF9xl0.fpEAC/rqLKg4U6', NULL, 1, 0, 0, NULL, '2025-08-02 09:54:56', '2025-08-02 09:54:56'),
(211, 'AFIA TAHMID SABIHA', 'afia6', '$2y$12$YmKuXHkfAYWCOnG3a3RzW.fXBtYFnW9SAThrTBG8onP8O3j8d3LRW', NULL, 1, 0, 0, NULL, '2025-08-02 09:54:56', '2025-08-02 09:54:56'),
(212, 'AMINA BINTE MAMUN', 'amina', '$2y$12$7HnbrARhoxGZNFl6KNCU8OKjgD/xDuB0LWoVSVuauKWEfMd8ozKlO', NULL, 1, 0, 0, NULL, '2025-08-02 09:54:57', '2025-08-02 09:54:57'),
(213, 'ANANYA SARKAR', 'ananya', '$2y$12$xyYN3fBiT.VkPiMiut1OH.qrSS5xFx57wSptcPqODE23Blv7D/8hu', NULL, 1, 0, 0, NULL, '2025-08-02 09:54:57', '2025-08-02 09:54:57'),
(214, 'ANIKA MAKNUN', 'anika', '$2y$12$fBSAex325djaVXTqZu5af.1Yn5ML2hq0P52pSXSiUhokcgLtCQx.q', NULL, 1, 0, 0, NULL, '2025-08-02 09:54:57', '2025-08-02 09:54:57'),
(215, 'ANISHA HAQUE AUTHY', 'anisha', '$2y$12$PT3eFA4fM2SfN4Ax.SZdN.OEzjxUAu3vVkjHo4991v76g6FxH8b7O', NULL, 1, 0, 0, NULL, '2025-08-02 09:54:57', '2025-08-02 09:54:57'),
(216, 'ARIFA JAHAN POUSHY', 'arifa', '$2y$12$RARpNM3w7Q4V2tJ8iobNk.87/1z8tTnVGeF2QQj4jjB4aQeBthm5i', NULL, 1, 0, 0, NULL, '2025-08-02 09:54:57', '2025-08-02 09:54:57'),
(217, 'ARSHA MONDAL', 'arsha', '$2y$12$nAQOTco4egdGd2Jhkms3zuMhz66gG698s4FwA7OTU8V8PGDlzoEJK', NULL, 1, 0, 0, NULL, '2025-08-02 09:54:58', '2025-08-02 09:54:58'),
(218, 'ASFIYA ISLAM ISRA', 'asfiya', '$2y$12$3cp76rP9r9ranePJE8DYq.av7wlEAZB926gOaOKdTlrBq0O3y/0xS', NULL, 1, 0, 0, NULL, '2025-08-02 09:54:58', '2025-08-02 09:54:58'),
(219, 'AYSHA AFRIN', 'aysha1', '$2y$12$/9tfpB5Un520II7vvk7QK.YVjA4B8HLc7Se0QUkRzQi82JYAmMAyy', NULL, 1, 0, 0, NULL, '2025-08-02 09:54:58', '2025-08-02 09:54:58'),
(220, 'AYSHA BINTA ATIK', 'aysha2', '$2y$12$SaOvZw4vQsnCo.PC15lykuFoEbO/wMExSgguapRllLhdf2/wMBR/.', NULL, 1, 0, 0, NULL, '2025-08-02 09:54:58', '2025-08-02 09:54:58'),
(221, 'FAHMIN TABASSUM MASUMA', 'fahmin', '$2y$12$7o9SWhwTKHBY1gAo/48Gz.fd9B21nEZB8Uie7gRXuU.3iC.zCeZCW', NULL, 1, 0, 0, NULL, '2025-08-02 09:54:59', '2025-08-02 09:54:59'),
(222, 'FATEMA AKTER MAHFUJA', 'fatema2', '$2y$12$nWMb17Q1q3CN.5wN7Ub49uOwT2uHQTjKwPa2OLAm8xMvif6VL0A4W', NULL, 1, 0, 0, NULL, '2025-08-02 09:54:59', '2025-08-02 09:54:59'),
(223, 'FATEMA TUJ ZOHRA ZARA', 'fatema3', '$2y$12$loR4BLs4o8FVlryASmtfFeKvf0.HNGBPHhwfq.t0fl96s6dwFvGdi', NULL, 1, 0, 0, NULL, '2025-08-02 09:54:59', '2025-08-02 09:54:59'),
(224, 'FATEMA TUZ JOHRA HABIBA', 'fatema4', '$2y$12$WfoSp0PAwSf24eXZnrGrGeM08ML/ZU97bUlgrSPNZAFqicDkCERWu', NULL, 1, 0, 0, NULL, '2025-08-02 09:54:59', '2025-08-02 09:54:59'),
(225, 'FAZLIKA RAHMAN', 'fazlika', '$2y$12$mx7C1SZmpbsSINbTEa3uReo3YR/ZJRuVtspnmaKR6XVPTZmKlzwEi', NULL, 1, 0, 0, NULL, '2025-08-02 09:54:59', '2025-08-02 09:54:59'),
(226, 'HUMAIYRA ISLAM MOUMITA', 'humaiyra', '$2y$12$j1qbGHjuX/KghueXJoLkdu0/osr/iTgO3LiA0QRyNJ9hXP6qW28Qy', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:00', '2025-08-02 09:55:00'),
(227, 'IMI', 'imi', '$2y$12$Y0Ky9atuUkEQ2UrkIY4tBe/w9UzavMod7Llybiu4a9pauVpqaaOJS', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:00', '2025-08-02 09:55:00'),
(228, 'ISMAT ZARIN', 'ismat', '$2y$12$.5GY9Xzvk3ek8MKWwAgNjeiGNyP67lrFj9D.LM8jborQkdpCEsgyC', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:00', '2025-08-02 09:55:00'),
(229, 'JANNATUL FERDAUS', 'jannatul9', '$2y$12$eZMmNIxuzEz2swgT5ja42eQMDAaA2z2A3NyM5YsbelDT1g00cibB.', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:00', '2025-08-02 09:55:00'),
(230, 'JANNATUL FERDOUS ELMA', 'jannatul10', '$2y$12$hyL.Q7pGOxQyeUsqdR5uxOIGnKwIPlu3cYpsNlw8oIUadbDzh1Vq2', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:00', '2025-08-02 09:55:00'),
(231, 'JERIN SULTANA', 'jerin', '$2y$12$t0sXn1Pe2BBW5S0ZI6Wv/O/gSyiak.MWS3Pz0fzTbr80eZ8h3/52G', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:01', '2025-08-02 09:55:01'),
(232, 'JINNAT JAHAN HUMYRA', 'jinnat', '$2y$12$362qp7cWtWb7FEPMDZKsmuwLiVMj8Bl9jJmAda8xaEvJb9FyKF4l6', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:01', '2025-08-02 09:55:01'),
(233, 'KHADIZA ZAHAN', 'khadiza1', '$2y$12$YOI4cf7tOgfbneWJRLpl4.3O7sKmbVaoqeHEVaY4HS0QqPZTd7zLK', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:01', '2025-08-02 09:55:01'),
(234, 'KHAIRUNNAHAR MAYSHA', 'khairunnahar', '$2y$12$EBOqTQeJErUL9NRNzQwinOnos1kOQ7UC9x1DEXn/PxbKY65yG1eM.', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:01', '2025-08-02 09:55:01'),
(235, 'MST. LAMIYA AKTER', 'mst.1', '$2y$12$c0.SRSy5v3Cf190xPq9c2uC2YnkXMttdix9vl8PrjgN2X9zJ/1Rci', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:02', '2025-08-02 09:55:02'),
(236, 'MAHFUZA SIMA MAISHA', 'mahfuza', '$2y$12$P/20M/JFQDY.1RiKPwrYKuTpMjmtwAFa8B1n/k9P6OumQp1xM2HK2', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:02', '2025-08-02 09:55:02'),
(237, 'MAHIMA MURSALIN', 'mahima', '$2y$12$P0hMbsnWb26zIOcLw8L7buVl04WWZpCwa8kOe7HpG4jxCrmjwRZqC', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:02', '2025-08-02 09:55:02'),
(238, 'MARZIA BINTA HAFIZ', 'marzia1', '$2y$12$G2Z3xWJyHmg11wSuQCKjt.WEJoYYkG1Sw5alTbOFYRyhtEmg1j5de', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:02', '2025-08-02 09:55:02'),
(239, 'MARZIA RAHMAN MRIDU', 'marzia2', '$2y$12$7ECoSirOEB0ncP0pQTee2ebSZ7.lG9W4kkGq2ActwBzPoC6/8vTji', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:02', '2025-08-02 09:55:02'),
(240, 'MASUMA MAHERJABIN', 'masuma', '$2y$12$vWr6iF0teEeLWply0hhyTe.SlkgZo4bBBbuiVg6kg9LeKCPOBeg.q', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:03', '2025-08-02 09:55:03'),
(241, 'MEHENAJ KAYSAR PINKY', 'mehenaj', '$2y$12$1D46iGPa3JGvjwOJOlbVuuiRWjF8iHJN.XR.XZwdMsapdBFpLXzmu', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:03', '2025-08-02 09:55:03'),
(242, 'MST SAIKA', 'mst', '$2y$12$o9CPCdiL4cPVrLEf175T6OpdpRNx1t3ETn/AApuqqzJ4lQxwzTOoa', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:03', '2025-08-02 09:55:03'),
(243, 'MST. UMME HABIBA', 'mst.2', '$2y$12$m.bS2.3ecNI1Swn4EAQ6AuQFGWc/4JEZjdbBcSmP1ngEifoP9Nbfu', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:03', '2025-08-02 09:55:03'),
(244, 'MUNIA TABASSUM', 'munia', '$2y$12$uSq6ydydBAZv5H21D9byy.J.QT2vSTbuysT1cqJ17PILTcj0IxGnS', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:03', '2025-08-02 09:55:03'),
(245, 'NABIHA TASNIM', 'nabiha', '$2y$12$64wP6TFDBHXm0lC4AhfXhuOUI0Js9M/tEAwoCMBwsMc5jW62QGAnS', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:04', '2025-08-02 09:55:04'),
(246, 'NAZIA SIDDIQUI', 'nazia', '$2y$12$O2gfIPuimJy6Y6VsbICPHeI/Ob2KfzbByPCIYyK.xJp20i.wrRgQ6', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:04', '2025-08-02 09:55:04'),
(247, 'NAYMA AHMED', 'nayma', '$2y$12$2gO513OMCfnIr0N4FJF7iuJvcE2qqjKZF5FEnXKjbtQls/ltlYc56', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:04', '2025-08-02 09:55:04'),
(248, 'NISHAT TASNIM', 'nishat', '$2y$12$4M9ZaRXja1VhZbyuROInJ.kCq3YIShEkDT3bVKq8Vtvd90tUxlfpq', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:04', '2025-08-02 09:55:04'),
(249, 'NOWSHIN ARA SARIKA', 'nowshin1', '$2y$12$fmO6titJ8a356rz/Hb7N5uhnPTfzxRho1W7R9KJlGxlOhv5IX75Ii', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:05', '2025-08-02 09:55:05'),
(250, 'NUSAIFA KHAN', 'nusaifa', '$2y$12$bfu1IqGiOZAGdHXDdnyxxuJsc3U7dztlZWPWJU7gdDbzCR/iy9StO', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:05', '2025-08-02 09:55:05'),
(251, 'NUSRAT HOSSAIN', 'nusrat9', '$2y$12$QGYwDoKx5tJAlzkVFBn72OAgfa4fnMkrCm4K2OFyP1QO3KE8.eJt6', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:05', '2025-08-02 09:55:05'),
(252, 'NUSRAT RAHMAN RIHAN', 'nusrat10', '$2y$12$izwtXDb4o.H/OpF15obHOuFgOpOb7AVbUC5HGJgVO905syh7Hr0MO', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:05', '2025-08-02 09:55:05'),
(253, 'OARISHA TASNIM', 'oarisha', '$2y$12$L3JjIAIOrX2kt/aA4JjsR.LeKnmTuqaQyoPgt0a3LA57JMuLQm8H6', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:05', '2025-08-02 09:55:05'),
(254, 'ORIN AFRIN', 'orin', '$2y$12$dUx1n94ynIbXenzI3BwE2eFXFvGDm1XbaW3XqtyJU5J.HFwiPz1Ru', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:06', '2025-08-02 09:55:06'),
(255, 'PRIYONTI PAUL', 'priyonti1', '$2y$12$VbGQqGcbFOlVlDccGNUWKefLow1SO9UONiv6/F0xEx4X4ap56bkha', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:06', '2025-08-02 09:55:06'),
(256, 'RAISHA HASAN ESHA', 'raisha1', '$2y$12$4DKwPAPffEYVPqRQttZZm.EYx4OQh90V0oq3LJ/SvhdcZ/JDnfe0a', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:06', '2025-08-02 09:55:06'),
(257, 'RAISHA ISLAM ESHA', 'raisha2', '$2y$12$AwMZ8AakKjXtfryP/xCk8.J1GQwnXvxAiMA30NyAnvWA9XpAFfMX2', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:06', '2025-08-02 09:55:06'),
(258, 'RUBAIYA AKTER ', 'rubaiya', '$2y$12$qYNmCysQ2PjOxeNFIsXdQuAliPT/S5gxTEBtlNRcMzz6KCLRqnVd6', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:06', '2025-08-02 09:55:06'),
(259, 'RASHMI MONDAL', 'rashmi', '$2y$12$d1b3DPl6XtAziS2Nqnnai.x1P5H8PTy3zam6/6EBBkXRzoC5OdkLq', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:07', '2025-08-02 09:55:07'),
(260, 'RUYAIDA HAQUE', 'ruyaida', '$2y$12$e3ZxzI74ueei1g2DlKo.0O3ix/ZJPR5Rzyi3bdV9ddRoqr3NMSzIi', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:07', '2025-08-02 09:55:07'),
(261, 'SABERA AKTER', 'sabera', '$2y$12$yDGm6E77r.LL6JvQTvi3BOA4xBiXzoSi7ZFfSm4.Izy19rggnDTVO', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:07', '2025-08-02 09:55:07'),
(262, 'SADIYA KHATUN', 'sadiya', '$2y$12$ew1YpsKRG1k1GiMObk4A1eDwwmrPaRVVOuEFHxqORqsdnz1zJ5.5i', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:07', '2025-08-02 09:55:07'),
(263, 'SADIYA AKTER', 'sadiya1', '$2y$12$6oMFzjo5kZtTI3B3Z7pKzed8LwYSCXWT9oJODWBw47OcARFMD6czm', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:08', '2025-08-02 09:55:08'),
(264, 'MST. SAMIYA', 'mst.3', '$2y$12$aFy9m78uH46uZRE5M0G2WOpZyB9O6Fd4REQhegzVQSL9k8TwTgHYm', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:08', '2025-08-02 09:55:08'),
(265, 'SANA AKTER PRITI', 'sana', '$2y$12$83.ZEmUhLh0c.SZ2gpo86.csqFfwhE6SoCNmb1KohtDTFmexvMRAm', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:08', '2025-08-02 09:55:08'),
(266, 'SANGIDA AFRIN NAYNA', 'sangida', '$2y$12$mY5zSvTV3zQyvUaGv12lM.fBgT4c/hyEZMwU8Qhh0j5INRftJ7juC', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:08', '2025-08-02 09:55:08'),
(267, 'SANJIDA NUSRAT TRIPTI', 'sanjida1', '$2y$12$2mNWPXfwPfCy6tTqeAFMMOvpAQ6HxBvgK0RorZZy2m2Ck83//zSgy', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:08', '2025-08-02 09:55:08'),
(268, 'SANZIDA AKTER SATHI', 'sanzida', '$2y$12$pdbSwpCPVCm8/pmAeOfw8eJ8.xnspvsWOZekfMFA/IWef5lJgdDfa', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:09', '2025-08-02 09:55:09'),
(269, 'SAIMA ZAMAN', 'saima1', '$2y$12$tP96DRlNuSkb0qPLA6Pm7eAVDAF2UdI.FQeKLR4sFyUnIYTDA/w4K', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:09', '2025-08-02 09:55:09'),
(270, 'MST. SARIKA', 'mst.4', '$2y$12$bQ6dwJEyPPgLVpsfw0bzie1bfws8IYpAXoXqjnT.45xaBHuJf4P8O', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:09', '2025-08-02 09:55:09'),
(271, 'SONIA', 'sonia', '$2y$12$/l8Wlo1lggODob/jwINJfOaPZu.j3wVxwRuxkoacOvZg85ANRux9S', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:09', '2025-08-02 09:55:09'),
(272, 'SUHIRA SALOWAN SAOLWA', 'suhira', '$2y$12$s86CKwN3TyZq6VINk9BDIu1abqXK9rVlHCUDnMK/n5e03QCyYTh.C', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:09', '2025-08-02 09:55:09'),
(273, 'SUMAIYA ALAM', 'sumaiya6', '$2y$12$HfIhOLimvC8POrxE.VEX0OTZzw1DkV45vEcRP7kQy0H1B3Q4uz5a.', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:10', '2025-08-02 09:55:10'),
(274, 'SUPTA DAS', 'supta', '$2y$12$obovqe1IPmmoBdckKLFz3ObOCGDRzAhRpIzqWZF5zOh3uZBXIOFZa', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:10', '2025-08-02 09:55:10'),
(275, 'TAHIRA TASNIA', 'tahira', '$2y$12$rtNIosAs7WAcoLls4c7zGO6Yq1bGxjQGwx7wkJW1K0/ZMX4gq0tii', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:10', '2025-08-02 09:55:10'),
(276, 'TAMJIDA PARVIN', 'tamjida', '$2y$12$OugtAsugHRSFaZEgWkRrzO2zYptAp2GuRKUWSXDmgnjbXPm6vAZHG', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:10', '2025-08-02 09:55:10'),
(277, 'TASDIT SARDAR PRITY', 'tasdit', '$2y$12$rv3GB.wBrbkDdLjeJ6ncguzcRZrFCCSsgyZfQuKaq2uXoeSyYepoK', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:10', '2025-08-02 09:55:10'),
(278, 'TASFIA HOSSAIN MIMI', 'tasfia2', '$2y$12$8Id9L0IVaiq8Re5/r92ctusly9TBJAT5OAQdVpiJAxUkKtL39v0mC', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:11', '2025-08-02 09:55:11'),
(279, 'TASNIA ISLAM', 'tasnia1', '$2y$12$8IeUGIacEDDpszwyn4RxZeWEpSg3bNVylLQpM3lXGJot3L21C7YFO', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:11', '2025-08-02 09:55:11'),
(280, 'TASNIYA TASNIM ATIFA', 'tasniya', '$2y$12$I0Jb7Li4eVE3VDe7bFaDA.ySAEuZ/SmdBLBIEsuLu6RanjkMk3tl.', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:11', '2025-08-02 09:55:11'),
(281, 'JANNATUL MAWYA TISHA', 'jannatul11', '$2y$12$47A.GmsiW7N7xQ3ni7vPpetOMnu.eQQA7eSS6/4t9BxyTH6L.8GvK', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:11', '2025-08-02 09:55:11'),
(282, 'ZAIMA RAHMAN', 'zaima', '$2y$12$EXY0dfNBWY.K2wco1USz0eQxhvPhFeLWhY2s5o4eSfbydiLoVd5Cy', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:12', '2025-08-02 09:55:12'),
(283, 'JHULFATUN TASNIM', 'jhulfatun', '$2y$12$rJC3nQK1fAqhnZTLvb410eJVymFRm9bW4CmrKkOwbZd5evSbUphKW', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:12', '2025-08-02 09:55:12'),
(284, 'MOST. TAHMINA ', 'most.1', '$2y$12$drbwJ4gslmkzTz26.so1ru45Zb9DOz4VbWKkvldga1an8pNojgsF2', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:12', '2025-08-02 09:55:12'),
(285, 'SUMAIYA AKTER', 'sumaiya7', '$2y$12$6AophC2zCnqnVrGjNgDNmOfYn.tMdrbUJlr9eMZJ6lb4fBnHf9.4i', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:12', '2025-08-02 09:55:12'),
(286, 'SINTHIYA', 'sinthiya', '$2y$12$.TKXEj2tuKvr9hzbSacYV.4pj.4tTgcq0095AGAUMRrUf/gDp.oxa', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:12', '2025-08-02 09:55:12'),
(287, 'NUSRAT ISLAM ', 'nusrat11', '$2y$12$a3nH./xQO1ENrxImTtvsgOhh62824LwTNoqciNGjvRM1VEge5r2F2', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:13', '2025-08-02 09:55:13'),
(288, 'TAMANNA AKTER', 'tamanna2', '$2y$12$m8GbeOafTWs9h6zzUAcFA.kpCFi4sbpLmgKsgy6BkNqb5jP0bqV8S', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:13', '2025-08-02 09:55:13'),
(289, 'JANNATUL FERDOUSHI NISA', 'jannatul12', '$2y$12$TKFl3ceddKGfg4Q5fU2Gxewykfo0d8Pa8/3PesyWiLrTA/ayX0.d6', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:13', '2025-08-02 09:55:13'),
(290, 'TAJKIA AKTER TISHA', 'tajkia', '$2y$12$eapp4UHfP2DdgwMeRrydnOFbSLtYqmPG5NM.24a9ke6OOLN6iItci', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:13', '2025-08-02 09:55:13'),
(291, 'BRISTI AKTER ', 'bristi', '$2y$12$AJg.Lh8TzdOvt6YXBKSPCe/I/khtPbCBa7AZFhqNT9lDm98rIJWMq', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:13', '2025-08-02 09:55:13'),
(292, 'ANONNA AKTER ', 'anonna', '$2y$12$rq.ZFE5EQWZHKZ8KPa0iKet1LLUYLNA1W0C/pnmMCyo5KHBCB5axu', NULL, 1, 0, 0, NULL, '2025-08-02 09:55:14', '2025-08-02 09:55:14'),
(293, 'AJMERI ASHRAF', 'ajmeri', '$2y$12$GMq8PwcIQ17BvXbD81LBuexsMAfogZlBtMZw49bMU1cKAWCkaxUDS', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:00', '2025-08-02 10:01:00'),
(294, 'ANISA TAHSIN SNEHA KHAN', 'anisa', '$2y$12$MO6WQbEpDaZrsWBC0x.K7eJNySHvAHo8IvWq.P.k38oZBPyq9MRle', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:00', '2025-08-02 10:01:00'),
(295, 'ANISHA TASNIM SORNO', 'anisha1', '$2y$12$i9c5mdoyk0FT0EEtKtGX2.nSLETm8O1yLtn7C9bIGvjA47C/2c5uW', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:00', '2025-08-02 10:01:00'),
(296, 'ORTHI AFROZE MARIUM', 'orthi', '$2y$12$.UF/HB3fMeBQsE6mTvHjxOia5dQ50tRJAyqsbmP6N5cIWTYJVA5mW', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:00', '2025-08-02 10:01:00'),
(297, 'ASMAHUL HUSNA EYANA', 'asmahul', '$2y$12$oxSeLEfhLyA7YV7MF0IhSOygv3bS/05nJr1qhNP0BQC8LmTDKZqYy', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:00', '2025-08-02 10:01:00'),
(298, 'AYSHA AFRIN', 'aysha3', '$2y$12$TCrAozAsuOF0Sjsu7HGtu.X.P7a30/WkRxEBcTNqkE1CCwJ0gNy/y', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:01', '2025-08-02 10:01:01'),
(299, 'FABIHYA AFAF ZARA', 'fabihya', '$2y$12$FJIesqGhgZC3iOK7W1T1ruPY5CudffqZFaWG7u8Jv67dlEIw5faSG', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:01', '2025-08-02 10:01:01'),
(300, 'FAIJA THASANNUM', 'faija', '$2y$12$TcmkuVnm0avg67bquha55ewqfhkD2nXCn.MDyA4buOrTWydBKsCEu', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:01', '2025-08-02 10:01:01'),
(301, 'FAMIDA JAMAN', 'famida', '$2y$12$QYn1lnFR3zqbO8D2AmKE7.rPXJifFtN9AEvpG2Vd2W4gBz.9UZ0EC', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:01', '2025-08-02 10:01:01'),
(302, 'FATEMA KHATUN SURIYA', 'fatema5', '$2y$12$sHmjSbax5cXpb629tBwkjOu4Hx3bIPO0QtkLVMrFmIjXLInmCUrF6', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:01', '2025-08-02 10:01:01'),
(303, 'FATAMA TUJ JOHORA MITHILA', 'fatama', '$2y$12$rI6tdnw/vOBFsOUrMDyGReFVbC3K56KeLxBclmTRGzpdvNzN7TsOm', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:02', '2025-08-02 10:01:02'),
(304, 'HAFIZA SULTANA ZEBA', 'hafiza', '$2y$12$b8fktJU6nMWc3.5UDzOft.SEtwaBfaEfUlqpQadLwXJKxGDAz/c6G', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:02', '2025-08-02 10:01:02'),
(305, 'HAMIDA KHATUN KOMOL', 'hamida', '$2y$12$zuc50fjRjMwoIjJvQej0LuMiILW7ZRr3sJWj6pT9ZELOR8ixrtAE2', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:02', '2025-08-02 10:01:02'),
(306, 'HAZERA RAHMAN', 'hazera', '$2y$12$z/up7SFgEaQ51UXDIDVmMugEJZ69wJtidYrOJfHIA1rKo8oYVl5JO', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:02', '2025-08-02 10:01:02');
INSERT INTO `users` (`id`, `name`, `username`, `password`, `role`, `status`, `is_admin`, `is_parent`, `remember_token`, `created_at`, `updated_at`) VALUES
(307, 'HOMIRA AKTER', 'homira', '$2y$12$4tPLO9exsuKlklHq0L7IY.8zOu12n0tV4f8pjrMjiNfQEXLut1YyG', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:03', '2025-08-02 10:01:03'),
(308, 'HUMAIRA HOSSAIN AFIA', 'humaira', '$2y$12$zkdjht7yiucwdhnErnwws.a6fyVL1IbhpteBCGb7.EAXYpP4Fhexe', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:03', '2025-08-02 10:01:03'),
(309, 'IFFAT ARA JAHIN', 'iffat', '$2y$12$jugpvg7DhNhR7WfPp9MsjeXlwfv/K/TcSpcBNQp.Zr0A27JkzYG1q', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:03', '2025-08-02 10:01:03'),
(310, 'JANATUL FERDOUS TISHA', 'janatul', '$2y$12$6rwvHbJOD1TYPjwpPxzop.rdTnqWi3Hr0ti/.TrLAqQCVJ26QeoJ6', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:03', '2025-08-02 10:01:03'),
(311, 'JANNATUL FARDAOUSI', 'jannatul13', '$2y$12$MGZyS9taFUo0pVBYBpbCXef8kdDNkeHfp1oEVxEOyjl76kLxbBBja', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:03', '2025-08-02 10:01:03'),
(312, 'JANNATUL FERDOES ESHA', 'jannatul14', '$2y$12$TFWWpfkfYleuMx6nUaGRjO5QRfnlGDKa9fpOyWgkZprbH2pzTzGYO', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:04', '2025-08-02 10:01:04'),
(313, 'JANNATUL FERDOUS FIZA', 'jannatul15', '$2y$12$ClawPMvsSmrtk/cph/awJ.8UjQDYu3V/egmzwNWw44.gRYoU8gP7u', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:04', '2025-08-02 10:01:04'),
(314, 'JANNATUL FERDOUS MOINA', 'jannatul16', '$2y$12$JEK.jwHkB792aCFRouQPye.IqE1LaQyJ7oDKnYlJpzVCxMypTPGGW', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:04', '2025-08-02 10:01:04'),
(315, 'JANNATUL FERDOUS SNEHA', 'jannatul17', '$2y$12$uxor8DtoFmokJoIWHQOQkOD1pwkAsd4Xx3MfQsaKx.J40tt8LSuNi', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:04', '2025-08-02 10:01:04'),
(316, 'JANNATUL FERDOUSE MILA', 'jannatul18', '$2y$12$t0HaSjFSt03rYun8yDr6xORhFOyQFOQNtcqkwSdcvGnd9NmwRK/IC', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:04', '2025-08-02 10:01:04'),
(317, 'JHARA', 'jhara', '$2y$12$saJXm8YMex3BnRXTKmkaBO6gfnkof6KXBN17HZpgljm7eldjBnBFu', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:05', '2025-08-02 10:01:05'),
(318, 'JOITA SAHA', 'joita', '$2y$12$SFN5GOKPZcenhaEw3wHyBex.V68zYiBjF78Flmp0309B9xzWY3p1m', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:05', '2025-08-02 10:01:05'),
(319, 'KANIZ FATEMA', 'kaniz1', '$2y$12$6WWqnf/aOIra6PDI/oB6xOfFp0B.7zwTnpYE3ydd/B1YdtXbFdx7q', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:05', '2025-08-02 10:01:05'),
(320, 'KHADIZA AKTAR', 'khadiza2', '$2y$12$2bxO24XccT0Lo9Lkkjqza.EAF43CiMWX0PBzGKrScqAgI4lw3yJZy', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:05', '2025-08-02 10:01:05'),
(321, 'KHUSBU AKTER RIYA', 'khusbu', '$2y$12$l0aZlOunpq1768rNDRykQuhrcVzan1rGMTPoN3SybaGFYkML3s.8y', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:06', '2025-08-02 10:01:06'),
(322, 'LAMIA AKTER', 'lamia1', '$2y$12$NfcCa7mb/BMmehKdwkxXi.MBmIXxeD8A2tCUlW3CtsZrDAsOmXMTW', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:06', '2025-08-02 10:01:06'),
(323, 'LAMIA ISLAM', 'lamia2', '$2y$12$F5d5y9bNEcPmSkqGHSEUMOB78f3vdHfJYWZM7B7U5mRvn4.zf86pW', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:06', '2025-08-02 10:01:06'),
(324, 'LAMIYA AKTER RATRY', 'lamiya', '$2y$12$.hR7naP68qMWGB0ElVlAheqKkpomkrBqIsAG/kIyJJ6x9vdawFtxu', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:06', '2025-08-02 10:01:06'),
(325, 'MABRURA AFSHARI SHUCHI MONI', 'mabrura', '$2y$12$OosvnWvaxmaOnNeQpmVUae/CYHU5rGFnH2YAPqaldIvDmiiWLTVvS', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:06', '2025-08-02 10:01:06'),
(326, 'MAHENAZ AFRIN SADIA', 'mahenaz', '$2y$12$SVUdwsckOPPZqD6Gghyp4.TBI7zoKGmSTakw8jjGYnzcejBASvEYC', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:07', '2025-08-02 10:01:07'),
(327, 'MAHIA PARVIN TANISHA', 'mahia', '$2y$12$4XpDPXm/KxrmYcw3s/HTxOqV2NADhWZhb1fUgTx13TufHt.JN6Rq.', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:07', '2025-08-02 10:01:07'),
(328, 'MARIA SULTANA TRINA', 'maria1', '$2y$12$0Bf3S3r7nb0PbrQX4cVPt.pPuxKbGpB6SQA9gw89u/o8.tFKhsTtG', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:07', '2025-08-02 10:01:07'),
(329, 'MAHAJABIN HAQUE', 'mahajabin', '$2y$12$F8Ak7IM/L/1uBC7W9tJZruxmZw5zVA1WWoEF9RKtsofAe1W.hX6Fi', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:07', '2025-08-02 10:01:07'),
(330, 'MEHREN AFROZA MALIHA', 'mehren', '$2y$12$IhdbnkZ4IadmF8QOXgHqFuMSeN1dz.VZBReSJzKUfMGAA3pYj7NBy', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:07', '2025-08-02 10:01:07'),
(331, 'MUHTASHINAH MORMO', 'muhtashinah', '$2y$12$6XIc/AUoxSuKsEtf/7JWquSw061bcqrSUyRG5Q6QPS7sT3.tmbTCO', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:08', '2025-08-02 10:01:08'),
(332, 'NAFIJA JAHAN', 'nafija', '$2y$12$celdQlytPkq/cJGi3lVUtOqHGnUDrX8lYQojPCuj3goZS2pFyQ.Hi', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:08', '2025-08-02 10:01:08'),
(333, 'NAJMUNNESA NABILA', 'najmunnesa', '$2y$12$6bL3duTfXszasQcqb0ZSo.5Q1cp5vXwMXOGg1wEPgmoGtPQLLeW4W', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:08', '2025-08-02 10:01:08'),
(334, 'NEHA AKTHER', 'neha', '$2y$12$MXpRqXAAnsr6dJKcAbiwiOmrWTSvaVevy3cuHZmtdWaTps0ML6PQ2', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:08', '2025-08-02 10:01:08'),
(335, 'NUBAISA JUHANA AYAAT', 'nubaisa', '$2y$12$GSWtutiQnixUaVvw87G2F.4QSu9eTzIkwe13ISmtj12/JqzUAM9.K', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:09', '2025-08-02 10:01:09'),
(336, 'NUSRAT JAHAN MIMI', 'nusrat12', '$2y$12$.jWmGpvnU0LpW/Ms5sUPUupKDlvcrHtVuVIJfCNVgc5jS6juo.KcC', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:09', '2025-08-02 10:01:09'),
(337, 'RIMTI AKTER SHOHANA', 'rimti', '$2y$12$I6epZ2WCoraXOqlHYOx44OIOUIqDGwDEF2AUbJYG2Tf/NVzkqtmUa', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:09', '2025-08-02 10:01:09'),
(338, 'RUZAINA SABERA IRISHA', 'ruzaina', '$2y$12$BLfKS6HRBRQEZO.K/RQxU.9TKbZIrl/vPS2.tRtxDuT5KkP1gijne', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:09', '2025-08-02 10:01:09'),
(339, 'SADIA SULTANA', 'sadia1', '$2y$12$rLR2uX0QuV2Nh9.XrnsNPOzf/s4gM4tT7Ujs7OVgI2JWxRE9Rur.2', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:09', '2025-08-02 10:01:09'),
(340, 'SAMIYA ISLAM JUI', 'samiya1', '$2y$12$GYJW.H5GOye4vtAkPwA7XubzxyZAFSASkuHO7rX58W5CimZxQ8kye', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:10', '2025-08-02 10:01:10'),
(341, 'SARA SULTANA', 'sara1', '$2y$12$xoMsG2j3QhYy/.DbZnqtE.6PIRHb0BJyVw/7mriSrFfPN/oMaemHe', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:10', '2025-08-02 10:01:10'),
(342, 'SAYDA MAHJABIN', 'sayda', '$2y$12$bhHBuQnxErzwFUuimRzWSu86w9ulrOoKvxTtfpLGRgq13caLTCk4i', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:10', '2025-08-02 10:01:10'),
(343, 'SHAFA ISLAM', 'shafa', '$2y$12$vzxs9eRtJJfrUZCPTALTmufdLwgQULS5d51ycUMlI9RfPw6xZ5hb.', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:10', '2025-08-02 10:01:10'),
(344, 'SHAMIYA AKTER', 'shamiya', '$2y$12$/7Y.sfO39OVmGoO/JNOE/egDVjg5Rqyj.XosMTMiNddGoNZyQLMSS', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:10', '2025-08-02 10:01:10'),
(345, 'SHEFAT TASNIM SUBHA', 'shefat', '$2y$12$o1LSjx2HWI/Oe/yTQ123KeV6WU1rtQHCipdTu89ZhaP56CBo23lF.', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:11', '2025-08-02 10:01:11'),
(346, 'SUMAIAYA AKTER', 'sumaiaya', '$2y$12$M8EsqR18xXG30iF1e3S4sumDgbBrQ24IkDLgPOslIPG8rgTUjcaTy', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:11', '2025-08-02 10:01:11'),
(347, 'SUMAIYA AKTER', 'sumaiya8', '$2y$12$1ao3JYxu42zL/XFc2XpZze2ytT88WoznY.z21zslhFD1qSGsVI6Qe', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:11', '2025-08-02 10:01:11'),
(348, 'SUMAIYA ISLAM ARBA', 'sumaiya9', '$2y$12$9P8I3lqwu.BOOY8vqD0RSu.d.JdNhh1Z.YqwM.6v5/lGwNDXebwt.', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:11', '2025-08-02 10:01:11'),
(349, 'SUNJIDA AKTER TITHILA', 'sunjida', '$2y$12$Y1zxoN2a.ojYwVdAibTYkez40ydVXMnmUsCMvinX6cFy5USaTgHwO', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:11', '2025-08-02 10:01:11'),
(350, 'SURAIYA AKTER', 'suraiya2', '$2y$12$JO.YioJEJnzn0XPUVbhR9u241KWckIqXPlTSiDlRh2LFsO/F2ctE6', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:12', '2025-08-02 10:01:12'),
(351, 'SYEDA NUSAIBA NABI', 'syeda', '$2y$12$Qb2yDNTIQ9R/FAaDKNkWhOmCOZhvvDuuESHxkFxH.LiieehbPAwL6', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:12', '2025-08-02 10:01:12'),
(352, 'TAHIYA JAHARA SARIKA', 'tahiya', '$2y$12$QxT.uQTcMglq3jFBQokkYu7B98XjJk5OfQUM669YZjp0f24kmtxHu', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:12', '2025-08-02 10:01:12'),
(353, 'TAHMID TASNIM ZARIN', 'tahmid', '$2y$12$ru081Tu5dSQcGbBFpd7XHu0WCIyT7z6OybqYJKwLzTif1OzB6Ufkq', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:12', '2025-08-02 10:01:12'),
(354, 'TAIRIN AHMED', 'tairin', '$2y$12$DFT3sr41Q7Am9g/IOLvEMeemekutigH5yniRnZUY7o3kTZUZhvk06', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:13', '2025-08-02 10:01:13'),
(355, 'TANHA', 'tanha', '$2y$12$/EeDC/1v5traHY59CKFtzOmw6F7XbTTcr1Uyq63rsKdx73bvzWPFe', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:13', '2025-08-02 10:01:13'),
(356, 'TANHA JARIN TUSHTI', 'tanha1', '$2y$12$PIrl6rqmLP0WcdPS4QoLwuHxGl7gNv.rGjBQmnNQSCPKIPGrUYGh2', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:13', '2025-08-02 10:01:13'),
(357, 'TANJILA AKTER MARIA', 'tanjila', '$2y$12$Tj1RW1TTsc3/7AmL/zP.Pey69YxXckx6n9L19JepJfq7N3GCIwM.2', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:13', '2025-08-02 10:01:13'),
(358, 'TASFIA TASNIM TASFY', 'tasfia3', '$2y$12$RQ95wwGw5Vibzd2250Pp3uHtqJWBGve2EtRXt9JN/cpmZaTgEi1P2', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:13', '2025-08-02 10:01:13'),
(359, 'TASMIA', 'tasmia', '$2y$12$UcPN3Fz76YRwNzUu4vmbfeee//dXGvaJjlIUoRLPV4lceKSVm2sau', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:14', '2025-08-02 10:01:14'),
(360, 'TISABA ISLAM KOCHI', 'tisaba', '$2y$12$2QHxws9AaNo03VVFm/A6SOr9dkLkH/sepso2ZlLoTXC2RMvGoHM6O', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:14', '2025-08-02 10:01:14'),
(361, 'TOHURA JAHAN', 'tohura', '$2y$12$T.YgH9iiMSDYG8Xig.U4duPlLKAvEje2a5fKfdPbUtAF9cerhzgr6', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:14', '2025-08-02 10:01:14'),
(362, 'ZANNAT ARAFIN HIYA', 'zannat', '$2y$12$B.ytVDZwPGbmmlCG78deOu6kuGWECRA4p1AgMjbJPjK2iTytF/qPq', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:14', '2025-08-02 10:01:14'),
(363, 'SHREEJOYEE DAS', 'shreejoyee', '$2y$12$AQITVQSgYs.JXryDSQ3f9.CDLbf6M.pzSC9ijARw.1F/wjktvf7di', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:14', '2025-08-02 10:01:14'),
(364, 'SHAMIMA AKHTER SHORNA', 'shamima', '$2y$12$Ydhb.fFIZ/sedBUgT0PEgOPTC8I.Wi7wia3nQ68iZas37q7wlM2uO', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:15', '2025-08-02 10:01:15'),
(365, 'JANNATI ISLAM RITU', 'jannati', '$2y$12$QT9uBp4DSmKf3dw9k8O2feVONADGNAwIQ4VQgKJfrnm4pf.lUffWW', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:15', '2025-08-02 10:01:15'),
(366, 'LAMIA AKTER MIM ', 'lamia3', '$2y$12$TlK3yzB3KW5ifNek0HOCFOuHC6y9qLaurYiqneg6VWfYpm7kK8Ejm', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:15', '2025-08-02 10:01:15'),
(367, 'SUBORNA KHATUN', 'suborna', '$2y$12$Djd2e0Rby/zpAg5RiWE8weNa5ahM4k9pXVAnRfc0aOegKcNBD0dpu', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:15', '2025-08-02 10:01:15'),
(368, 'SOMPORNA MAZUMDER', 'somporna', '$2y$12$5te87AFLA43j6kluOqRI6uUvG7YILeCXp5naSQNBH5mOOUK098LYa', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:15', '2025-08-02 10:01:15'),
(369, 'SUMAYA AKTER TINNI', 'sumaya', '$2y$12$lJrw8NAODmbdU6WQgfQVH.i1TdUua8aNFSrpWjV..e6UawqqRAOGa', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:16', '2025-08-02 10:01:16'),
(370, 'SAIMA SIMRAN', 'saima2', '$2y$12$XsmeXDSs4mr0x4O1Ng0mQux7qqTyUt/U8oapmUuiXvep5KVTw4xz6', NULL, 1, 0, 0, NULL, '2025-08-02 10:01:16', '2025-08-02 10:01:16'),
(371, 'ADRIKA ZARA', 'adrika', '$2y$12$4czBXrfLCTvdDc2SvM1PuuocMN7oe.sjHw0EX9X1RnOf0HcEcknu.', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:17', '2025-08-02 10:31:17'),
(372, 'AHONA TASKIN KHAN', 'ahona', '$2y$12$GMKVX/rwgjEgd9CZ5k4rcubezQSwkLd421LS7DbCPwORMNmaZpN4S', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:18', '2025-08-02 10:31:18'),
(373, 'AQSA AMJAD', 'aqsa', '$2y$12$qfH1vXgA.0vc5Rz.t5LJu.H4fFb7rGQZP7LqZHh9GdxGPUkv1kP3G', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:18', '2025-08-02 10:31:18'),
(374, 'FARIHA JAHAN SAKIBA', 'fariha1', '$2y$12$p7xnPMIK3pztNOEyJ6hQkeBdkTQ4jcslewNYAqAHTDfKRNy22N09q', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:18', '2025-08-02 10:31:18'),
(375, 'MAYESHA AFRIN', 'mayesha', '$2y$12$oZuY72N99mrSVt/FZCp.g.1OfvTRCo91hMQCUHpTBA01oViC/q13W', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:18', '2025-08-02 10:31:18'),
(376, 'NABIHA TAHSIN RIFA', 'nabiha1', '$2y$12$MdRwarRUkgXFc/Wfy7BnEe.xewpSH/PKnNYE2aZYDWJbG5jR4G30S', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:19', '2025-08-02 10:31:19'),
(377, 'NOWSHIN BINTE RASEL', 'nowshin2', '$2y$12$5zdd4AWYLgRU/DbbH3Va4eKNiupFMQopbrrikHCBVU020JNVnZnqW', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:19', '2025-08-02 10:31:19'),
(378, 'SADIA JAHAN', 'sadia2', '$2y$12$23wY.o43ECspuhpgTiles.vi9GqIwx5/80FB4TE5HOXynZGcxmBuy', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:19', '2025-08-02 10:31:19'),
(379, 'SADIA ZAMAN', 'sadia3', '$2y$12$7h/kZJdYpyN1A/HX/neMBuCk/rJZ.836Tfn/SviV0r6DgQeBEgUMq', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:19', '2025-08-02 10:31:19'),
(380, 'SHAMIRAH ISLAM SHOVA', 'shamirah', '$2y$12$rr8sfAvIY4NK.bOBFQgzNeq4FN3OnYvPgNNW77rhu4ng/uj3pWJ8G', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:19', '2025-08-02 10:31:19'),
(381, 'MARIA AKTER MITHELA ', 'maria2', '$2y$12$rTmG77L67pQPTPTpqRoo5e1FyxibkTb.9r/88sGA0tm9sfNNake1G', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:20', '2025-08-02 10:31:20'),
(382, 'RIFA AFROJ PRANTI ', 'rifa', '$2y$12$pb6dDElANcJyQy1lMx1Qe.QgeUvRLeHPOouszHHd3hlIRyaCarnvC', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:20', '2025-08-02 10:31:20'),
(383, 'AFIYA AHMED', 'afiya', '$2y$12$NhqX.GiIoblK4USAjLRGB.J6MWtx4Fe2u0G5UMwZy72KyvFlXCaHi', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:20', '2025-08-02 10:31:20'),
(384, 'AFNAN ZAHAN SOVVOTA', 'afnan', '$2y$12$bRZRl58iFItzh/ZlRJ4Czu5eFk/jWT6nGaunFxX9XrjgOcdbMQZPu', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:20', '2025-08-02 10:31:20'),
(385, 'AKSHARA ISLAM SHIFA', 'akshara', '$2y$12$UzJMgcdYCCc3feuYzk20WeTs.dteM2iqrr5VvKEQyVdG5zAnH9UV6', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:21', '2025-08-02 10:31:21'),
(386, 'ANIKA ISLAM', 'anika1', '$2y$12$6iPvPiOi.8T3x1o.xjy4IemajyAGm7LH6YYEo0a1MRMuaVN2Mr7hq', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:21', '2025-08-02 10:31:21'),
(387, 'JANNATUL FERDOUS', 'jannatul19', '$2y$12$JEnw2qDsbjGMgUCtBpMWW.nkmKBc82rZVWp5/yYrEwORPOjFFuzeG', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:21', '2025-08-02 10:31:21'),
(388, 'ZANNATUL FERDOUS ESHA', 'zannatul', '$2y$12$0m41eHHHj18LrpaP/vI7leAnsawmo6RcPwArYdWFXaWboK0LK1XBi', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:21', '2025-08-02 10:31:21'),
(389, 'JANNATUL NAYMA', 'jannatul20', '$2y$12$4BKSDjJMwIhWzmVzkpO2bOsFQLPONCaXRaTuqvwSLUDsdk4neFV9m', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:21', '2025-08-02 10:31:21'),
(390, 'JINIYA RAHMAN', 'jiniya', '$2y$12$Hc4m9dwdDaGAnLXRmNh7XO.8lKLZc.jERxxH2cIDm/QqWKe3hBhb.', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:22', '2025-08-02 10:31:22'),
(391, 'METHILA ISLAM', 'methila', '$2y$12$1Xu1uHb7v0FRNfSp4smpYODwKc6g3p6uFCIEqv9wgXdMn8wE7P3gq', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:22', '2025-08-02 10:31:22'),
(392, 'MOMOTA HENA MALIHA', 'momota', '$2y$12$YCz9GhBtkA1UTwpdGASyiudXJW9C14I2GvSGdt/RspJq8TebXFlLK', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:22', '2025-08-02 10:31:22'),
(393, 'MORIUM AKTER MIM', 'morium', '$2y$12$mnklMZfv22A80zbypXCoUeXx9Mx3prtTdHJ/XQM8Aw2yCjwJWB38O', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:22', '2025-08-02 10:31:22'),
(394, 'MST ISRAT JAHAN ITU', 'mst1', '$2y$12$Y7CtSXn9MQ6kcXZ38GQNyeq7eRn1A3kFrTW/eXMaA9bopfCm.L35m', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:22', '2025-08-02 10:31:22'),
(395, 'MUNTAHA BINTE MIZAN', 'muntaha1', '$2y$12$5BY/kRFZJzCGv6YulfxqzOLLuyJ8JlYxr9y4aPb8CNgPc7EsBKQZK', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:23', '2025-08-02 10:31:23'),
(396, 'MUNTAHA JAHAN SAFA', 'muntaha2', '$2y$12$TMkmnVYSHmGn6ZG8hRPal.jOaC7pO1No5IdAz/63fz63b/hAK5o6S', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:23', '2025-08-02 10:31:23'),
(397, 'NUR JAHAN', 'nur1', '$2y$12$pUsfSw0wUq0HqTYOCUEj5.vynvjTzzc5V0wW9q/aqwRgZuLvsIc1S', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:23', '2025-08-02 10:31:23'),
(398, 'RABEYA AKTER', 'rabeya1', '$2y$12$yVlM7R/0jwGF8sOCpD7zgOpS4HPt4FNRpRGKfpL5iAiqE25oMYTUS', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:23', '2025-08-02 10:31:23'),
(399, 'RAFSANA TASNIM RAFIA', 'rafsana', '$2y$12$dYariFuO4rxV0hmqH3/mUuBRvXU0hRnZGzMkJ9fmiTwceiquc11Fu', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:24', '2025-08-02 10:31:24'),
(400, 'RAITA TASNIM', 'raita', '$2y$12$OcXlUkzx6ZtkDte6NYla9umo.fUxBD6c88GYuIjbP.MulOQvyDBQ6', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:24', '2025-08-02 10:31:24'),
(401, 'RAYANA RAHMAN', 'rayana', '$2y$12$hpUJKP49niA9Bjt0qmfeUuZdSnMlbmqR6.Qwq02PFPGMYsaiN3gHu', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:24', '2025-08-02 10:31:24'),
(402, 'RUHI SABERIN', 'ruhi', '$2y$12$esPHarVShwnivpWC4iwHs.Z7JctTBVtuYYuwHYxhFea3LauARRcR2', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:24', '2025-08-02 10:31:24'),
(403, 'JANNAT ARA RUMMANI', 'jannat1', '$2y$12$bY6.dK77dbGkdIgBWbuLl.sb5/QkPSRKD3yXQozWgdHTNb9QyY/ti', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:24', '2025-08-02 10:31:24'),
(404, 'RUSABA KHAN AFNAN', 'rusaba', '$2y$12$eTXVKPy/cdke7Ea1KlIccupdr91/atlJZgGClqX/p1zXsTid1Rv/C', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:25', '2025-08-02 10:31:25'),
(405, 'SADIA ALI SUROVI', 'sadia4', '$2y$12$t.bzTpEbqBpCxincvZIfi.cIBR9NsoI/VB5isBwyORfFDhOJqzhbW', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:25', '2025-08-02 10:31:25'),
(406, 'SUROVI ISLAM', 'surovi', '$2y$12$PubMNwIzh0W5kjxR77F/g.J7FS8VocnhDxJNdHTGIv/Pya2gV9BpO', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:25', '2025-08-02 10:31:25'),
(407, 'TAHSINA BINTE OBAYED', 'tahsina', '$2y$12$QDvHClS54.dEZLA6AFC5NeQaBgeZNYREWPS9ZO9fPQuwipnZOrlEO', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:25', '2025-08-02 10:31:25'),
(408, 'TANHA BINTA JAMAN', 'tanha2', '$2y$12$J43bjVS/p.TwzOqyUm50V.y3yJGZp.M2aNP9P2NYB8xmy3su2IWNu', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:26', '2025-08-02 10:31:26'),
(409, 'AKSHARA KHANOM', 'akshara1', '$2y$12$uVZD0dOtGT9kkhgaY0/7Au55d1Ikt.LszrK3ZGWeWPVtnQYaXBvWW', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:26', '2025-08-02 10:31:26'),
(410, 'FAHIMA YEASMIN MAHI', 'fahima', '$2y$12$mMIOwLrEQij5biDu71JXjepepMbUTk.q8sdt/9nvN.RFLY2U/EMKS', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:26', '2025-08-02 10:31:26'),
(411, 'FATIMA NEYAZ', 'fatima2', '$2y$12$rdv3R6rrRi93gRwsDvBTz.xtlSqry4P1/dk6LVdmijx4IdrBvMbu.', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:26', '2025-08-02 10:31:26'),
(412, 'JANNATUL MAWA', 'jannatul21', '$2y$12$/.JIJx7Md0ktTwwRmHqkB.umZKFavmZwgNLmn8h9TMytmnKQ4OOs2', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:26', '2025-08-02 10:31:26'),
(413, 'JAYNAB KHATUN', 'jaynab', '$2y$12$GAKTDT5Wrvvo0NAEJzW1KurWI6bF.ntHRic3wixOytCSbumyWU0La', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:27', '2025-08-02 10:31:27'),
(414, 'JULIA JESMIN ASHA', 'julia', '$2y$12$vbZHbNcxSbmbuPPVtREmNuW1MDhwki9SgmIdEg7pJ3HFxyBR8jEwK', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:27', '2025-08-02 10:31:27'),
(415, 'LAMIA AKTER NIJHUM', 'lamia4', '$2y$12$Jl2CKGuV/DUkl9HoRvpJa.wE4qHngki7UvvOhsCx82dXzYhv2N6D.', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:27', '2025-08-02 10:31:27'),
(416, 'MAISHA FARZANA ANIKA', 'maisha3', '$2y$12$YNzVOPvK9ZedyUdZuzYB4.AW0kxtUMfco6qL5Mw7OvR6dh22io9KW', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:27', '2025-08-02 10:31:27'),
(417, 'MARIUM AKTHER MITHILA', 'marium1', '$2y$12$p7Mpzh7UyAna0WubG4ZLKeoMV8v0Jbv52AKsFWVTo2Zkt5A2/FH66', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:27', '2025-08-02 10:31:27'),
(418, 'MARZIA ISLAM RATRI', 'marzia3', '$2y$12$sLTNE8sgSPhBlk769ie2J.Y7Az0EmlMP9yEoGWv5bYiYGeil1HIdO', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:28', '2025-08-02 10:31:28'),
(419, 'MINTHA USHA', 'mintha', '$2y$12$e.mPVXuWtVQeY/E85EYvyuGHMA/IjuUa/OGWHpG3O65a1s/0.7OSG', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:28', '2025-08-02 10:31:28'),
(420, 'MIS ESHA MONY', 'mis', '$2y$12$paMuOFp1uWKS64SHoaoZ3Or4xZmfj/o1EIPUPQRUwsv/.2r0PExfW', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:28', '2025-08-02 10:31:28'),
(421, 'MISS LABONI AKTER', 'miss', '$2y$12$SybvvcBu5np0ujTz36NnnunkdW.XClmZmyhKh1RBGBqJ7yZoQouL6', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:28', '2025-08-02 10:31:28'),
(422, 'MUNIYA AKTER MOU', 'muniya', '$2y$12$WpXkNWf4963mPEhgPcLcI.0GX2D5lHmovUi5XDzHIFSFlU9XwUhte', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:29', '2025-08-02 10:31:29'),
(423, 'NUR-E-JANNAT BUSHRA', 'nur-e-jannat', '$2y$12$4jjr6vex0H.mYnlbevq59up/r7g5qMQK6eP0WVe8lHXCvAgc08haO', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:29', '2025-08-02 10:31:29'),
(424, 'NUSRAT JAHAN', 'nusrat13', '$2y$12$mUsAlHEgwfghwr.gsLWIrOBkDCjXNH6TB3q1RLn1nnzDfkElAdQQa', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:29', '2025-08-02 10:31:29'),
(425, 'PARMITA AKTER BOISAKHI', 'parmita', '$2y$12$zxAbEUhqLAmtQz.iunA9cOMPmS74FliviEqd6rzwE/SQThYZs.qSC', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:29', '2025-08-02 10:31:29'),
(426, 'RASHEDA AKTER', 'rasheda', '$2y$12$Ugtnafu9jJDwr/3GwV8qoucU41VtFNRttlWKlA3JWfEkYxddy21rG', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:29', '2025-08-02 10:31:29'),
(427, 'ROJA AFRIN', 'roja1', '$2y$12$pN/hzZkF0BWq2Y9uptG.d.V5D5hWam.CAtb692DyySn46yTcbyu/u', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:30', '2025-08-02 10:31:30'),
(428, 'ROKSANA AKTER JASSEYA', 'roksana', '$2y$12$w0OwqNsk1ePJ2gr5eFB2mOXTNf4TaXp/YYevyq/8KVayH7i9Bis5.', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:30', '2025-08-02 10:31:30'),
(429, 'ROZA AFRIN MIMI', 'roza', '$2y$12$LnAeDUPpf.dGU3euChxbRe2Zu8P.gHPQKzafGRpI7W/Bkxy67EU.6', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:30', '2025-08-02 10:31:30'),
(430, 'SADIA AFRIN', 'sadia5', '$2y$12$U0eI.SRg0gSz5rLk7Apk9.mL3zvh4/6LNE7hDyU3QNehA5HmR2Jba', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:30', '2025-08-02 10:31:30'),
(431, 'SADIA AKTER', 'sadia6', '$2y$12$5e6U0346vGg6vMOxathhwuZ/SeNW1ANOYT/ujP6kyVh5nKIF307gO', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:31', '2025-08-02 10:31:31'),
(432, 'SAMIYA AKTHER', 'samiya2', '$2y$12$VTCQzpLeWqm434NMo4OxW.ah7LtMgZuHxAFoHym68NZFHnPtyIjsu', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:31', '2025-08-02 10:31:31'),
(433, 'TASFIA FERDOWS ROZA', 'tasfia4', '$2y$12$ektnBh9AVazo/qTGzXi.v.rdmVeBH6.cdPUpzgrq.9cwu4rg1uOYu', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:31', '2025-08-02 10:31:31'),
(434, 'RAISA ISLAM ', 'raisa', '$2y$12$DaiTdP4f1wXYULvj9rxi4uiCsOpBJMEhYucund8efM7E2Zk.DevlW', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:31', '2025-08-02 10:31:31'),
(435, 'AMIRA SULTANA ', 'amira', '$2y$12$z8zRD8Y1OURBYffP6Wb23esbHU8qDWOCJJHjV6CUd2BllnKmGfmP2', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:31', '2025-08-02 10:31:31'),
(436, 'SUROVI AKTER SAPNA ', 'surovi1', '$2y$12$vjF//iFYPHeQB7EehGJzxuFnoFbx6RbPQemHXczVg/NyKeHSJj7MG', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:32', '2025-08-02 10:31:32'),
(437, 'SANJIDA SUMA ', 'sanjida2', '$2y$12$RhV8t7/2LJhNnUJFxT0cqeS6sCPwNaEmXZ/9gNpDDvoltifzyu5Ta', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:32', '2025-08-02 10:31:32'),
(438, 'SURIYA AKTER MOHUA', 'suriya', '$2y$12$oCXL5Iu83v7SbGke500V9Ofy.jV9WruQo6I38OF7EGYeHQyM/oYm6', NULL, 1, 0, 0, NULL, '2025-08-02 10:31:32', '2025-08-02 10:31:32'),
(439, 'ANIKA TASNIM ', 'anika2', '$2y$12$RV9fVXUX7Oy/n7S15peJ2uelu7qSYJEtRio71v612YlTPAihhEsAS', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:37', '2025-08-02 10:33:37'),
(440, 'PRITY ADHIKARY ', 'prity', '$2y$12$3CrBEeBErjGHCbJTpqA7ZOULiJGaz1CQMpEHmyS3H/LkJlGTttKr6', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:37', '2025-08-02 10:33:37'),
(441, 'MST. JANNATUL FERDOUS', 'mst.5', '$2y$12$SVhVOUeIO/W4YWPBduGbaOaH/li3A84YEnw6wlQionVQSKejz0cUS', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:38', '2025-08-02 10:33:38'),
(442, 'NAWSHIN SHAIYARA AURPITA ', 'nawshin', '$2y$12$innnIj1eqtYG87Y93aLEyOGTlHbKMwQyOa88B/fawzzDk5e/MbHwq', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:38', '2025-08-02 10:33:38'),
(443, 'LAMIA ISLAM MAMOTA', 'lamia5', '$2y$12$j2VmL6SUq2zD5h/UYJ4/3uHqWx9AXbJEqpcZgY7R09U5sJd5b3ytS', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:38', '2025-08-02 10:33:38'),
(444, 'FARIA ISLAM', 'faria', '$2y$12$n7vzhytFomfM94/LKxXaAuawWq4y1zppNtz/N6GSEi.lGK9o7.wwC', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:38', '2025-08-02 10:33:38'),
(445, 'SHAFAKAT TAYEBA BUBUN ', 'shafakat', '$2y$12$/Rjfg/azITofYOhQYR/32.jLQBN.aKzMl49bZVYp22PCAxBWNj15K', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:39', '2025-08-02 10:33:39'),
(446, 'MST TABASSUM ZAMAN FAIZA ', 'mst2', '$2y$12$YgWWY3vMChdh0NP8AS34Y.IsvbEqFwt0Cp8yDOVXM/optoVVN6ws6', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:39', '2025-08-02 10:33:39'),
(447, 'SAPTOSHI DATTO SITHI ', 'saptoshi', '$2y$12$bvPpe7eBPOGxX1pHWJmXe.mUrdbbva9swwCG.gmoG5l5MVxkAIlMW', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:39', '2025-08-02 10:33:39'),
(448, 'MUSFIKA ZAMAN DISHA ', 'musfika', '$2y$12$H5V4KaDdDqtdfEbHgSA79eITY1zByCoqZMcrrwV69XFLv4pI4Fdu2', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:39', '2025-08-02 10:33:39'),
(449, 'KHADIZATUL ELISA ', 'khadizatul', '$2y$12$60vcEDCNIG.sZL/g0WdNKeEqFrFukLDXQuQoT8mMc/gDvbiijYd6O', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:39', '2025-08-02 10:33:39'),
(450, 'MST TAZKIA TOHSIN ', 'mst3', '$2y$12$.2AUT64e8HX/NMUgdoAoEeD0CxL7BD9ycBTcZBRykUPPXetTfVqyC', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:40', '2025-08-02 10:33:40'),
(451, 'MARIAM HOSSAIN', 'mariam', '$2y$12$ybJExeC/i5vfCu4YUThYz.b5M7vbYXJCyEAQTfRx.0SrARI.Vqa3y', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:40', '2025-08-02 10:33:40'),
(452, 'SUMAIYA HASSAN AZMARY ', 'sumaiya10', '$2y$12$hUXz11oyOtn12MY15e3wy.Qdzwe65DLNckBKseJrufF6nFy9jBUcu', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:40', '2025-08-02 10:33:40'),
(453, 'SAIFIA ISLAM SAIFA', 'saifia', '$2y$12$uTlU1Jg5dqE/OeWKUkCieuc4tkKIheNolHwiGd3PGwEq99P9QDHny', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:40', '2025-08-02 10:33:40'),
(454, 'SAODA HOSSAIN', 'saoda', '$2y$12$8ffNMTVePDXNRv8HDym/9.1Htwsne6KMRATCqv06eYJX3opIU5FzS', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:40', '2025-08-02 10:33:40'),
(455, 'SADIA AFRIN TASHPIA ', 'sadia7', '$2y$12$TnrbQuzvNeKpLqs9bWKEpe5QL8wKYR84jWzHS/.SkQB6zJUOYRaJG', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:41', '2025-08-02 10:33:41'),
(456, 'ANISHA ANJUM', 'anisha2', '$2y$12$uXh1jNNiztKqyRLYsGzXIO4rCzwpup7nBHdEZ0SSs3M.VoOAgF2my', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:41', '2025-08-02 10:33:41'),
(457, 'SANJIDA ALAM ROZA', 'sanjida3', '$2y$12$KBpsWQO4q132SriAVoym4.p7LLCqAhRYj2Y7Cm/Q21RQzrdpWjPhS', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:41', '2025-08-02 10:33:41'),
(458, 'ANUSHA ISLAM', 'anusha', '$2y$12$mIEkENq9QUcaFgSdtGfFqeQHSOr3pdvR1OEIEdI9JOKACeQR3KGCi', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:41', '2025-08-02 10:33:41'),
(459, 'MASNIA JEBIN ANTU', 'masnia', '$2y$12$6j6gDL1j3QH2ALw5D.8ojeC9L1jdPa.a86IuUw/tqLyd1ZcviWvE2', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:42', '2025-08-02 10:33:42'),
(460, 'NUSRAT AFRIN SHINHA', 'nusrat14', '$2y$12$OHX/tvEPWTuYD2.Wj5nM.e8MlRcjmOm.jPrpge39gjlfN0epp1mi.', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:42', '2025-08-02 10:33:42'),
(461, 'ISHRAT JAHAN ', 'ishrat1', '$2y$12$yVHHRgGnSg3WD5sPsgGEvefyeeJbGf01hDpcClpyq1ZSER3BZr3g2', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:42', '2025-08-02 10:33:42'),
(462, 'SHIFAT JAHAN SILVIA', 'shifat', '$2y$12$v34yWlPgVU.VW5IrcVx9neDY7O9pwCPLKtgyfdRYCEvz51Q7Yt2gC', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:42', '2025-08-02 10:33:42'),
(463, 'MST SAMIYA AKTER ', 'mst4', '$2y$12$05MI86zNkbDWx/AhydF.0u8bdEx9rwReN.XRlw5dE7BHGtEpp5N.e', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:42', '2025-08-02 10:33:42'),
(464, 'SADIA JAHAN SRABONY ', 'sadia8', '$2y$12$LzRfRYkpC3HceZzQPxG22etdeIeCCwmJbwuDrLiwqKJqy0QQqDgaS', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:43', '2025-08-02 10:33:43'),
(465, 'USRAT JARRIN', 'usrat', '$2y$12$nxFLyxJe6RAEEPa0ZDdQjO6Xpa6Bb4h3woTKZKkl0pBZ6NUjGSmP.', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:43', '2025-08-02 10:33:43'),
(466, 'AFIA AKTER ESHA', 'afia7', '$2y$12$.Tu.wgaeXHOIjQbBziKL5ua.JstktF8QbfbBtcDJ28fKjTujy9BYq', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:43', '2025-08-02 10:33:43'),
(467, 'SAMIA', 'samia', '$2y$12$5T1uOH7Zigobgze7qJIVa.kj44dLEx58M.xjqbD6FZ90QHRWXbBN.', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:43', '2025-08-02 10:33:43'),
(468, 'JANNATUL FERDOUS ', 'jannatul22', '$2y$12$169HqyqypOPK3.o0.P0/9.luCpi640mFsYjg2fY16BitBYKXB5Lka', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:43', '2025-08-02 10:33:43'),
(469, 'SAYEDA ISRAT JAHAN', 'sayeda1', '$2y$12$Ke7FkG.p8WPZ7z639Yi5Iew7.fCAoB0ngXzaqBW1O2Kc5eE5nb37C', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:44', '2025-08-02 10:33:44'),
(470, 'JAHAN TABASSUM LAILA NAYNA', 'jahan', '$2y$12$VIkSd5m7/6V4Amy75/Vuge1pGbvvg3hd9X4GLzHxewtiYBeNtn/Oy', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:44', '2025-08-02 10:33:44'),
(471, 'JAFRIN SULTANA ', 'jafrin1', '$2y$12$fCYFIPuH1sHYLzIbsT.aoOCufyhH9QzfeeViQjQ2sCybtCv/IZdfy', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:44', '2025-08-02 10:33:44'),
(472, 'SAHRINA', 'sahrina', '$2y$12$yMxRKxqbDQ/FUaVHZNM0xOvpU6jvvKaARKk3wFk9gLvI8NBj/f.IK', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:44', '2025-08-02 10:33:44'),
(473, 'FERDOUSI RAHMAN ANONNA ', 'ferdousi', '$2y$12$BUjlmadpO0H8p96Ec/O5COjXrD0tXhG.lGpJRfbHUW6DYGCiGftBK', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:45', '2025-08-02 10:33:45'),
(474, 'AFIA JAHIN KHUSHBU', 'afia8', '$2y$12$en1/G.WQULex1Bxt4Ruel.M.aKJ85S1B4B4.IlJ/WfoD6IChLb4tS', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:45', '2025-08-02 10:33:45'),
(475, 'ALIFA AKTER MEGHLA', 'alifa', '$2y$12$GGNSIm1uQdMiFEe49SEpNucvGA1svVSp6JZZZeZwK5f.Jah2dJrt2', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:45', '2025-08-02 10:33:45'),
(476, 'ALISHA QURISHI ', 'alisha', '$2y$12$iw34lINidXmwv3Aq8fqmgOHD9q5DJxTl.ZiUTJcqU5k.nkWZMl1b6', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:45', '2025-08-02 10:33:45'),
(477, 'SHAMIMA SULTANA ', 'shamima1', '$2y$12$6TJbmuQvLCgCG8156Tdrqu46ixMeAbzrkqXg0890hOaUpWXoTnBgm', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:45', '2025-08-02 10:33:45'),
(478, 'SRISHTI GOLDER ', 'srishti', '$2y$12$PvxLB1O/eqgDYvUhwXaqeOJcPIqpFqSBEWnmrHCi7ud3MelpFzHMS', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:46', '2025-08-02 10:33:46'),
(479, 'JUTHI AKTER', 'juthi', '$2y$12$r4VkhpMD2x28p6HLzeQ5EOYL824eQrgSYdQTVGU0Lcgl.CUbn6Xua', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:46', '2025-08-02 10:33:46'),
(480, 'MARIA TABASSUM MOUSHI ', 'maria3', '$2y$12$yRebZxGPGA0cOFLDq6.y7ei6hpNtCVp9uvsddmTcpj9/QUVdN5xUe', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:46', '2025-08-02 10:33:46'),
(481, 'MUNTAHA AHMED', 'muntaha3', '$2y$12$Hb.FCcp07PkiH6f810v6N.Dyfwa22w5DN2WsTpavNSos66MlDRrUy', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:46', '2025-08-02 10:33:46'),
(482, 'SUMONA AKTAR JEBA', 'sumona', '$2y$12$SE/CJNhwgjk3R7868Zxyt.dtUiVFmHIXMdVgO/b2pRIce/ogggZYq', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:46', '2025-08-02 10:33:46'),
(483, 'NASIMA AKTER', 'nasima', '$2y$12$RpjwoMJ.hJouu/MMyL1NfO0yd76mICrrpiGVEyHBNht.hAMJTUgwW', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:47', '2025-08-02 10:33:47'),
(484, 'REZWANA HOSSAIN TAJRI ', 'rezwana', '$2y$12$GDqaftCSmG6IgEn4A4POiO1F0iJYKdT51vFxLY3Zsm4JvKlJNwr0K', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:47', '2025-08-02 10:33:47'),
(485, 'AIRIN AKTER ', 'airin', '$2y$12$LW2oM3531gG8ldeZV7exWeLBOtUHiMDCJDEEToJFDU9eRBoOQkG52', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:47', '2025-08-02 10:33:47'),
(486, 'TANHA ISLAM SILME ', 'tanha3', '$2y$12$Lxq4vNeV5W7BamPbHTJVCOoN7daKhOROQWhvIOfIBdNLFLZiqyaLe', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:47', '2025-08-02 10:33:47'),
(487, 'SUMAIYA CHOYA', 'sumaiya11', '$2y$12$X4TjYgQrAmAaKbK3r1E0J.uk.MXIMTnX7qtgxxYKYaQmqJsxrpf4W', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:47', '2025-08-02 10:33:47'),
(488, 'JANNATUL FERDOUSH MAHI ', 'jannatul23', '$2y$12$gcvTu1dMgqV4vfELPBbeKOCqKhjHLGtLC3EYCD8UrDCypmbaR2emi', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:48', '2025-08-02 10:33:48'),
(489, 'NOWRIN NUR SHAIARA ', 'nowrin1', '$2y$12$wljPOkktBkuDR6c/7Qdihe3/P2L3MV5ebcJGXxP36XskEvzPGvTZW', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:48', '2025-08-02 10:33:48'),
(490, 'SABIHA RAHMAN', 'sabiha3', '$2y$12$SX2M0yJwXUstclNGddsDT.XgIWgqt9afUN4k85jJeR38DvEdp2TWi', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:48', '2025-08-02 10:33:48'),
(491, 'MAHZABIN MAHMUD AHONA', 'mahzabin1', '$2y$12$xlmaV4RDjp4pJPCYC7/1L.MDysiUZjSteOpzzKzoqiAmYgTSV1ZNG', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:48', '2025-08-02 10:33:48'),
(492, 'ANISA AFRIN', 'anisa1', '$2y$12$4kfZT/O5pLqwvjbcZ019IuClf8I2VDRIIHfC5JEhdwh7R7.c1ucTu', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:49', '2025-08-02 10:33:49'),
(493, 'AYSHA', 'aysha4', '$2y$12$gmdGqrxKFhpxbVMSwXUcoOgJ.Zi5KZSzNLiEiPxdWpvhtpnucjq/.', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:49', '2025-08-02 10:33:49'),
(494, 'MALIHA FARRHA FAIZA ', 'maliha2', '$2y$12$EuvHq/WuIllZrZIaPt1qF.uqiXVwkw3w6VUPeon/CSEPpJ44WNLk.', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:49', '2025-08-02 10:33:49'),
(495, 'SHOHA BINTAY SHOFIQ', 'shoha', '$2y$12$2nyiETZc7DcpnrfIulUwWOMo5af9njbdhIyfVb.yKXdSc29t.TfOm', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:49', '2025-08-02 10:33:49'),
(496, 'HAMIDA AKTER', 'hamida1', '$2y$12$SoLA2EUMykp/JZjhoMU0Iu5C5iqjUZtFzf87lhTZ.ofl0Lb2SDDNu', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:49', '2025-08-02 10:33:49'),
(497, 'JOBAYEDA AKTER', 'jobayeda', '$2y$12$hiJNU/uip1paUVQSrbCu8ebRZLENM69lq7do63r4DHtwxnvTwyrzG', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:50', '2025-08-02 10:33:50'),
(498, 'ZAYMA ISLAM LAMIA', 'zayma', '$2y$12$l8NpUaoSzsOymiV5F6XufOof82g6s7x0PjFP8pmH0AC9O26gVtmv.', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:50', '2025-08-02 10:33:50'),
(499, 'MUNTAHA ISLAM ', 'muntaha4', '$2y$12$0T/pZzlCh1HaL//KmiE9u.b4qNji428s2jdlPTeYZ8mKO/GCiLf4y', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:50', '2025-08-02 10:33:50'),
(500, 'SUMAYA AKTER LAKI ', 'sumaya1', '$2y$12$l1TmeHx/NGI5O08BFoCDHOEwaFh2v3VuXSeIUA/BBCSDP2EDQ.HRe', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:50', '2025-08-02 10:33:50'),
(501, 'TASNIM AKTER SHARA', 'tasnim1', '$2y$12$w8HQDFNUFXY1GXVFE04Ice6pqaZOm/8p8LC2jQoX9ZGiFIZcS1E2W', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:50', '2025-08-02 10:33:50'),
(502, 'AKHI AKTER', 'akhi', '$2y$12$pz1hMgFnQwXMLCaC1/BRMOfBo9mv9kwul32K3NLpefBwhX3.ZCFM.', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:51', '2025-08-02 10:33:51'),
(503, 'TANISHA ISLAM', 'tanisha1', '$2y$12$sb9thf1jt1uMlO3MOrCm6O2A1ERwhdt0qXsm9oSKb5FpYlkGpqcUW', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:51', '2025-08-02 10:33:51'),
(504, 'SAMIYA AKTER ', 'samiya3', '$2y$12$KzIchrjk4SG4oiDjNF4nXeEK9boh9JoPflVUXdMCIe6uFArgC0gZi', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:51', '2025-08-02 10:33:51'),
(505, 'SHAHINUR AKTER', 'shahinur', '$2y$12$dgtNBHLNye2.nDBJQUV7JeXLImfO02faq0bhKYDjDytlpto8koK2O', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:51', '2025-08-02 10:33:51'),
(506, 'UMME KHAYAR SABEEN ', 'umme1', '$2y$12$dqXLSfIxsPBo30o1DN03se2q8QLFhbTaEOiJ2QrzoSE6tz6Oh6UJC', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:52', '2025-08-02 10:33:52'),
(507, 'HUSNA ARA HENA', 'husna', '$2y$12$JaDbMnLK1vHG3etFNOViteVojT.mRVp6GrNEnS1cxRt5g4S7qfh6e', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:52', '2025-08-02 10:33:52'),
(508, 'MST JABIBA SULTANA BIBA ', 'mst5', '$2y$12$0hLcMBaN.l1FqqCD7/fvwePSEvWf4NYMrQYFz4NftUndG3yXOMAiO', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:52', '2025-08-02 10:33:52'),
(509, 'PIU PODDAR', 'piu', '$2y$12$EnocWRymI6vAvkJsEUki3uBzcH9P7AQ/JtNFWdwrwoAb017.aV//.', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:52', '2025-08-02 10:33:52'),
(510, 'SANJIDA AKTER RISA ', 'sanjida4', '$2y$12$ao3gHMpFkc1r93sEmQR4LeEv6oSs7eKFUDtQC9lo1BrFelyIb7vvu', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:52', '2025-08-02 10:33:52'),
(511, 'TANJILA JAHAN ZEBA', 'tanjila1', '$2y$12$AjeXtFif.tZlE8FJGxijbuvRgf7aFYQYqzzSDVPe4lcsf9WxROfVO', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:53', '2025-08-02 10:33:53'),
(512, 'JANNATUL BUSHRA JAISHA ', 'jannatul24', '$2y$12$RvbxBKw/GbqJ/hZkO1soOeovEUEw58ECE2WZXAhFdY9BCEek10Qcu', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:53', '2025-08-02 10:33:53'),
(513, 'SUMAIYA ADHORA', 'sumaiya12', '$2y$12$5ImM.W9Ovh/yvC0N8Izh1e65IxxKyJTangKSdC9ULGoXmNggClFfC', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:53', '2025-08-02 10:33:53'),
(514, 'SOBA', 'soba', '$2y$12$3FQ2Hb.l6gBMR76mJ6PPuelAgIKTscnDWuInk1qtLwptLpRLXk2Ly', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:53', '2025-08-02 10:33:53'),
(515, 'TANISA AKTER', 'tanisa1', '$2y$12$W4nHwQGNJBwkBujfLQ8ZpOEkscl1BAaDBNepBkNXzLwe5r0IqX5FK', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:53', '2025-08-02 10:33:53'),
(516, 'MST. SHARIFA AKTER', 'mst.6', '$2y$12$mlSBFNk0ar6Zd0v0jECY3O87hU4k/FxPkTrg77BbrFid758/zT1CC', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:54', '2025-08-02 10:33:54'),
(517, 'AYSHA AKTAR', 'aysha5', '$2y$12$Sz7Qq2M7njRlVOubol8iQum9CppDF.f7kIM6pNnNdFIXcwNrfOgJm', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:54', '2025-08-02 10:33:54'),
(518, 'JANNATUL NAYEMA MIMI', 'jannatul25', '$2y$12$hxAAPdDyOvIBJyDTz6gr9edMjdCi/RPk67Sz.BBJNAHuZB0aZdsg6', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:54', '2025-08-02 10:33:54'),
(519, 'SURAYA YEASMIN ', 'suraya', '$2y$12$/tJrf65sJ0ni6p9ajHfdmuTv9EAUOhmeG6cSMS7hRRX/ulW2BbDiW', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:54', '2025-08-02 10:33:54'),
(520, 'JANNATUL FERDOUS ', 'jannatul26', '$2y$12$nJt9wlGyaiFNqhxhNmCJl.xMri5ZRYVaCK.vwUK7T6CyBETsrTLae', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:55', '2025-08-02 10:33:55'),
(521, 'MIFTAUL JANNAT MITHILA ', 'miftaul', '$2y$12$bi9OJVQshFjbWUj4BF6sX.U0N3tWSgaFZYAvjTV.Nli8pydPcoD2e', NULL, 1, 0, 0, NULL, '2025-08-02 10:33:55', '2025-08-02 10:33:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_sessions`
--
ALTER TABLE `academic_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `academic_sessions_name_unique` (`name`);

--
-- Indexes for table `bloods`
--
ALTER TABLE `bloods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bloods_name_unique` (`name`);

--
-- Indexes for table `class_rooms`
--
ALTER TABLE `class_rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `class_rooms_room_number_unique` (`room_number`);

--
-- Indexes for table `class_sections`
--
ALTER TABLE `class_sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_sections_school_class_id_foreign` (`school_class_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `departments_name_unique` (`name`),
  ADD UNIQUE KEY `departments_code_unique` (`code`);

--
-- Indexes for table `designations`
--
ALTER TABLE `designations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `designations_name_unique` (`name`);

--
-- Indexes for table `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exams_academic_session_id_foreign` (`academic_session_id`),
  ADD KEY `exams_exam_category_id_foreign` (`exam_category_id`);

--
-- Indexes for table `exam_categories`
--
ALTER TABLE `exam_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `exam_categories_slug_unique` (`slug`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `final_mark_configurations`
--
ALTER TABLE `final_mark_configurations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `final_mark_configurations_school_class_id_foreign` (`school_class_id`),
  ADD KEY `final_mark_configurations_subject_id_foreign` (`subject_id`);

--
-- Indexes for table `genders`
--
ALTER TABLE `genders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `genders_name_unique` (`name`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guardians`
--
ALTER TABLE `guardians`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guardians_user_id_foreign` (`user_id`);

--
-- Indexes for table `mark_distributions`
--
ALTER TABLE `mark_distributions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mark_distributions_name_unique` (`name`);

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
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `religions`
--
ALTER TABLE `religions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `religions_name_unique` (`name`);

--
-- Indexes for table `school_classes`
--
ALTER TABLE `school_classes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `school_classes_name_unique` (`name`),
  ADD UNIQUE KEY `school_classes_numeric_name_unique` (`numeric_name`);

--
-- Indexes for table `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shifts_name_unique` (`name`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `staff_staff_id_unique` (`staff_id`),
  ADD UNIQUE KEY `staff_nid_unique` (`nid`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `staff_user_id_foreign` (`user_id`),
  ADD KEY `staff_department_id_foreign` (`department_id`),
  ADD KEY `staff_designation_id_foreign` (`designation_id`),
  ADD KEY `staff_gender_id_foreign` (`gender_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `students_roll_number_unique` (`roll_number`),
  ADD UNIQUE KEY `students_email_unique` (`email`),
  ADD UNIQUE KEY `students_admission_number_unique` (`admission_number`),
  ADD UNIQUE KEY `students_national_id_unique` (`national_id`),
  ADD KEY `students_user_id_foreign` (`user_id`),
  ADD KEY `students_school_class_id_foreign` (`school_class_id`),
  ADD KEY `students_class_section_id_foreign` (`class_section_id`),
  ADD KEY `students_shift_id_foreign` (`shift_id`),
  ADD KEY `students_guardian_id_foreign` (`guardian_id`),
  ADD KEY `students_gender_id_foreign` (`gender_id`),
  ADD KEY `students_blood_id_foreign` (`blood_id`),
  ADD KEY `students_religion_id_foreign` (`religion_id`),
  ADD KEY `students_academic_session_id_foreign` (`academic_session_id`);

--
-- Indexes for table `student_marks`
--
ALTER TABLE `student_marks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_mark_unique` (`student_id`,`exam_id`,`subject_id`,`mark_distribution_id`),
  ADD KEY `student_marks_school_class_id_foreign` (`school_class_id`),
  ADD KEY `student_marks_class_section_id_foreign` (`class_section_id`),
  ADD KEY `student_marks_subject_id_foreign` (`subject_id`),
  ADD KEY `student_marks_exam_id_foreign` (`exam_id`),
  ADD KEY `student_marks_mark_distribution_id_foreign` (`mark_distribution_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subjects_name_unique` (`name`),
  ADD UNIQUE KEY `subjects_code_unique` (`code`);

--
-- Indexes for table `subject_assigns`
--
ALTER TABLE `subject_assigns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_assigns_school_class_id_foreign` (`school_class_id`),
  ADD KEY `subject_assigns_class_section_id_foreign` (`class_section_id`),
  ADD KEY `subject_assigns_shift_id_foreign` (`shift_id`);

--
-- Indexes for table `subject_assign_items`
--
ALTER TABLE `subject_assign_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_assign_items_subject_assign_id_foreign` (`subject_assign_id`),
  ADD KEY `subject_assign_items_subject_id_foreign` (`subject_id`),
  ADD KEY `subject_assign_items_teacher_id_foreign` (`teacher_id`);

--
-- Indexes for table `subject_mark_distributions`
--
ALTER TABLE `subject_mark_distributions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_mark_distributions_school_class_id_foreign` (`school_class_id`),
  ADD KEY `subject_mark_distributions_class_section_id_foreign` (`class_section_id`),
  ADD KEY `subject_mark_distributions_subject_id_foreign` (`subject_id`),
  ADD KEY `subject_mark_distributions_mark_distribution_id_foreign` (`mark_distribution_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_sessions`
--
ALTER TABLE `academic_sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bloods`
--
ALTER TABLE `bloods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `class_rooms`
--
ALTER TABLE `class_rooms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_sections`
--
ALTER TABLE `class_sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `designations`
--
ALTER TABLE `designations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exams`
--
ALTER TABLE `exams`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `exam_categories`
--
ALTER TABLE `exam_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `final_mark_configurations`
--
ALTER TABLE `final_mark_configurations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `genders`
--
ALTER TABLE `genders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `guardians`
--
ALTER TABLE `guardians`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mark_distributions`
--
ALTER TABLE `mark_distributions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `religions`
--
ALTER TABLE `religions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `school_classes`
--
ALTER TABLE `school_classes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `shifts`
--
ALTER TABLE `shifts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=499;

--
-- AUTO_INCREMENT for table `student_marks`
--
ALTER TABLE `student_marks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `subject_assigns`
--
ALTER TABLE `subject_assigns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subject_assign_items`
--
ALTER TABLE `subject_assign_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subject_mark_distributions`
--
ALTER TABLE `subject_mark_distributions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=522;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `class_sections`
--
ALTER TABLE `class_sections`
  ADD CONSTRAINT `class_sections_school_class_id_foreign` FOREIGN KEY (`school_class_id`) REFERENCES `school_classes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `exams`
--
ALTER TABLE `exams`
  ADD CONSTRAINT `exams_academic_session_id_foreign` FOREIGN KEY (`academic_session_id`) REFERENCES `academic_sessions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exams_exam_category_id_foreign` FOREIGN KEY (`exam_category_id`) REFERENCES `exam_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `final_mark_configurations`
--
ALTER TABLE `final_mark_configurations`
  ADD CONSTRAINT `final_mark_configurations_school_class_id_foreign` FOREIGN KEY (`school_class_id`) REFERENCES `school_classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `final_mark_configurations_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `guardians`
--
ALTER TABLE `guardians`
  ADD CONSTRAINT `guardians_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `staff_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `staff_designation_id_foreign` FOREIGN KEY (`designation_id`) REFERENCES `designations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `staff_gender_id_foreign` FOREIGN KEY (`gender_id`) REFERENCES `genders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `staff_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_academic_session_id_foreign` FOREIGN KEY (`academic_session_id`) REFERENCES `academic_sessions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `students_blood_id_foreign` FOREIGN KEY (`blood_id`) REFERENCES `bloods` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `students_class_section_id_foreign` FOREIGN KEY (`class_section_id`) REFERENCES `class_sections` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `students_gender_id_foreign` FOREIGN KEY (`gender_id`) REFERENCES `genders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `students_guardian_id_foreign` FOREIGN KEY (`guardian_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `students_religion_id_foreign` FOREIGN KEY (`religion_id`) REFERENCES `religions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `students_school_class_id_foreign` FOREIGN KEY (`school_class_id`) REFERENCES `school_classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_marks`
--
ALTER TABLE `student_marks`
  ADD CONSTRAINT `student_marks_class_section_id_foreign` FOREIGN KEY (`class_section_id`) REFERENCES `class_sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_marks_exam_id_foreign` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_marks_mark_distribution_id_foreign` FOREIGN KEY (`mark_distribution_id`) REFERENCES `mark_distributions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_marks_school_class_id_foreign` FOREIGN KEY (`school_class_id`) REFERENCES `school_classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_marks_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_marks_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subject_assigns`
--
ALTER TABLE `subject_assigns`
  ADD CONSTRAINT `subject_assigns_class_section_id_foreign` FOREIGN KEY (`class_section_id`) REFERENCES `class_sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subject_assigns_school_class_id_foreign` FOREIGN KEY (`school_class_id`) REFERENCES `school_classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subject_assigns_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `subject_assign_items`
--
ALTER TABLE `subject_assign_items`
  ADD CONSTRAINT `subject_assign_items_subject_assign_id_foreign` FOREIGN KEY (`subject_assign_id`) REFERENCES `subject_assigns` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subject_assign_items_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subject_assign_items_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `subject_mark_distributions`
--
ALTER TABLE `subject_mark_distributions`
  ADD CONSTRAINT `subject_mark_distributions_class_section_id_foreign` FOREIGN KEY (`class_section_id`) REFERENCES `class_sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subject_mark_distributions_mark_distribution_id_foreign` FOREIGN KEY (`mark_distribution_id`) REFERENCES `mark_distributions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subject_mark_distributions_school_class_id_foreign` FOREIGN KEY (`school_class_id`) REFERENCES `school_classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subject_mark_distributions_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
