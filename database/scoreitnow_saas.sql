-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 25, 2023 at 06:33 AM
-- Server version: 8.0.33
-- PHP Version: 8.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scoreitnow_saas`
--

-- --------------------------------------------------------

--
-- Table structure for table `api_calls_count`
--

CREATE TABLE `api_calls_count` (
  `id` bigint UNSIGNED NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
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
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint UNSIGNED NOT NULL,
  `invoice_package_type_id` bigint UNSIGNED NOT NULL COMMENT 'This is the invoice package type it looks like the type of package like Tutoring Package or Monthly Invoice etc',
  `due_date` timestamp NULL DEFAULT NULL,
  `fully_paid_at` timestamp NULL DEFAULT NULL,
  `general_description` text COLLATE utf8mb4_unicode_ci,
  `detailed_description` text COLLATE utf8mb4_unicode_ci,
  `email_to_parent` tinyint(1) NOT NULL DEFAULT '0',
  `email_to_student` tinyint(1) NOT NULL DEFAULT '0',
  `amount_paid` double(8,2) NOT NULL DEFAULT '0.00',
  `paid_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `paid_by_modal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_by_id` bigint DEFAULT NULL,
  `invoiceable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'This is the type of package like Tutoring Package or Monthly Invoice etc',
  `invoiceable_id` bigint NOT NULL COMMENT 'This is the primary key of above type of package',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `auth_guard` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `added_by` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `invoice_package_type_id`, `due_date`, `fully_paid_at`, `general_description`, `detailed_description`, `email_to_parent`, `email_to_student`, `amount_paid`, `paid_status`, `paid_by_modal`, `paid_by_id`, `invoiceable_type`, `invoiceable_id`, `status`, `auth_guard`, `added_by`, `created_at`, `updated_at`) VALUES
(1, 1, '2023-09-26 19:00:00', NULL, 'Sunt voluptatum quam', 'Corrupti velit prov', 0, 0, 0.00, '1', NULL, NULL, 'App\\Models\\StudentTutoringPackage', 12, 1, 'web', 1, '2023-09-24 13:16:12', '2023-09-24 13:16:12'),
(2, 1, '2023-09-24 19:00:00', NULL, 'Ut ex eaque dolor qu', 'Ipsa quod qui velit', 1, 0, 0.00, '1', NULL, NULL, 'App\\Models\\StudentTutoringPackage', 13, 1, 'web', 1, '2023-09-24 13:18:57', '2023-09-24 13:18:57'),
(3, 1, '2023-09-24 19:00:00', NULL, 'Unde a irure impedit', 'Eos aute quis repreh', 1, 0, 0.00, '1', NULL, NULL, 'App\\Models\\StudentTutoringPackage', 14, 1, 'web', 1, '2023-09-24 13:21:10', '2023-09-24 13:21:10'),
(4, 1, '2023-09-24 19:00:00', NULL, 'Ea adipisci doloribu', 'Nihil elit alias et', 1, 0, 0.00, '1', NULL, NULL, 'App\\Models\\StudentTutoringPackage', 15, 1, 'web', 1, '2023-09-24 13:23:13', '2023-09-24 13:23:13'),
(5, 1, '2023-09-24 19:00:00', NULL, 'Dolore aspernatur qu', 'Est earum unde nisi', 1, 0, 0.00, '1', NULL, NULL, 'App\\Models\\StudentTutoringPackage', 16, 1, 'web', 1, '2023-09-24 13:25:01', '2023-09-24 13:25:01'),
(6, 1, '2023-09-24 19:00:00', NULL, 'Accusamus et nemo la', 'Sit pariatur Proid', 1, 0, 0.00, '1', NULL, NULL, 'App\\Models\\StudentTutoringPackage', 17, 1, 'web', 1, '2023-09-24 13:26:07', '2023-09-24 13:26:07');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_package_types`
--

CREATE TABLE `invoice_package_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_package_types`
--

INSERT INTO `invoice_package_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Tutoring Package', '2023-09-24 02:26:03', '2023-09-24 02:26:03'),
(2, 'Monthly Invoice Tutoring', '2023-09-24 02:32:01', '2023-09-24 02:32:01'),
(3, 'Mock Test', '2023-09-24 02:32:07', '2023-09-24 02:32:07'),
(4, 'At Home Sessions', '2023-09-24 02:32:12', '2023-09-24 02:32:12'),
(5, 'Other', '2023-09-24 02:32:17', '2023-09-24 02:32:17');

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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2014_10_12_100000_create_password_resets_table', 1),
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(6, '2023_09_05_224118_create_parents_table', 1),
(7, '2023_09_05_225540_create_api_calls_count_table', 1),
(8, '2023_09_06_201010_laratrust_setup_tables', 1),
(9, '2023_09_08_121921_create_schools_table', 1),
(10, '2023_09_10_183358_create_students_table', 1),
(11, '2023_09_11_171946_create_jobs_table', 1),
(12, '2023_09_12_111004_add_resource_column_to_permissions_table', 1),
(13, '2023_09_17_105332_create_tutors_table', 1),
(14, '2023_09_19_225043_create_package_types_table', 1),
(15, '2023_09_19_225218_create_subjects_table', 1),
(16, '2023_09_19_225331_create_tutoring_locations_table', 1),
(17, '2023_09_20_213205_create_student_tutoring_packages_table', 1),
(18, '2023_09_22_012921_student_tutoring_package_tutor', 1),
(21, '2023_09_22_025414_subject_student_tutoring_package', 2),
(22, '2023_09_24_013317_add_hourly_rate_column_to_tutors_table', 3),
(23, '2023_09_23_212143_create_package_types_table', 4),
(24, '2023_09_24_065754_create_package_types_table', 5),
(25, '2023_09_24_070024_create_invoice_package_types_table', 6),
(27, '2023_09_24_194305_add_is_completed_column_to_student_tutoring_packages_table', 8),
(29, '2023_09_24_084300_create_invoices_table', 9);

-- --------------------------------------------------------

--
-- Table structure for table `package_types`
--

CREATE TABLE `package_types` (
  `id` int UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parents`
--

CREATE TABLE `parents` (
  `id` bigint UNSIGNED NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secondary_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `address2` text COLLATE utf8mb4_unicode_ci,
  `phone_alternate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referral_source` text COLLATE utf8mb4_unicode_ci,
  `referral_from_positive_experience_with_tutor` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `auth_guard` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `added_by` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parents`
--

INSERT INTO `parents` (`id`, `first_name`, `last_name`, `email`, `email_verified_at`, `password`, `remember_token`, `secondary_email`, `phone`, `address`, `address2`, `phone_alternate`, `referral_source`, `referral_from_positive_experience_with_tutor`, `status`, `auth_guard`, `added_by`, `created_at`, `updated_at`) VALUES
(1, 'Keiko', 'Decker', 'cogikoloxi@mailinator.com', NULL, '$2y$10$DYEqNPdXJx3wLTMp5GenZeDT14OikqjICwvgpDq/X0hDxz2WXngpi', NULL, NULL, '+1 (467) 951-1227', 'Pariatur Ipsum qua', 'Id exercitationem of', '+1 (498) 635-3598', 'Repudiandae dolores', 0, 1, 'web', 1, '2023-09-21 16:18:57', '2023-09-21 16:18:57'),
(2, 'Paloma', 'Sweet', 'hures@mailinator.com', NULL, '$2y$10$VTwVL0oOFFR5rYIrweD8e.bbi9rt2CXHKz5j9tJ9aoG827bi.q14u', NULL, NULL, '+1 (309) 814-3702', 'Consectetur labore s', 'Iusto est exercitati', '+1 (356) 893-9874', 'Sint iure ducimus d', 1, 1, 'web', 1, '2023-09-24 13:17:27', '2023-09-24 13:17:27'),
(3, 'Renee', 'Daniel', 'zivogu@mailinator.com', NULL, '$2y$10$QGXTNa/C6Nd3zBVJ5rz68urJWaEJjGiodhG70nYcDuprBDSPa5qAa', NULL, NULL, '+1 (908) 145-8141', 'Proident maiores et', 'Iste qui eu minus it', '+1 (926) 649-9936', 'Fugiat possimus au', 0, 1, 'web', 1, '2023-09-24 13:17:37', '2023-09-24 13:17:37');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
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
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resource` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `resource`, `created_at`, `updated_at`) VALUES
(1, 'parent-index', 'Parent Index', 'Show all parents', 'Parent', '2023-09-07 01:26:21', '2023-09-11 23:54:16'),
(2, 'parent-create', 'Parent Create', 'create or add a new parent', 'Parent', '2023-09-07 01:26:41', '2023-09-11 23:54:34'),
(3, 'parent-edit', 'Parent Edit', 'Edit parent Details', 'Parent', '2023-09-07 01:28:29', '2023-09-11 23:54:37'),
(4, 'parent-show', 'Parent Show', 'show parrent details', 'Parent', '2023-09-07 01:28:48', '2023-09-11 23:54:40'),
(5, 'parent-destroy', 'Parent Destroy', 'Delete parent', 'Parent', '2023-09-07 01:29:08', '2023-09-11 23:54:44'),
(6, 'student-index', 'Student Index', 'Show all students', 'Student', '2023-09-07 01:29:08', '2023-09-11 23:54:52'),
(7, 'student-create', 'Student Create', 'Create or add a new student', 'Student', '2023-09-07 01:29:08', '2023-09-11 23:54:57'),
(8, 'student-edit', 'Student Edit', 'Student Edit', 'Student', '2023-09-07 01:29:08', '2023-09-11 23:55:03'),
(9, 'student-show', 'Student Show', 'Show student detail', 'Student', '2023-09-07 01:29:08', '2023-09-11 23:55:08'),
(10, 'student-destroy', 'Student Destroy', 'Delete student', 'Student', '2023-09-07 01:29:08', '2023-09-11 23:55:11'),
(11, 'school-index', 'School Index', 'Show all schools', 'School', '2023-09-07 01:29:08', '2023-09-11 23:55:25'),
(12, 'school-create', 'School Create', 'Create or add a new school', 'School', '2023-09-07 01:29:08', '2023-09-11 23:55:37'),
(13, 'school-edit', 'School Edit', 'Edit school detail', 'School', '2023-09-07 01:29:08', '2023-09-11 23:55:43'),
(14, 'school-show', 'School Show', 'Show school detail', 'School', '2023-09-07 01:29:08', '2023-09-11 23:55:51'),
(15, 'school-destroy', 'School Destroy', 'Delete school', 'School', '2023-09-07 01:29:08', '2023-09-11 23:55:56'),
(16, 'tutor-index', 'Tutor Index', 'Tutor Index', 'Tutor', '2023-09-16 19:54:45', '2023-09-16 19:54:45'),
(17, 'tutor-create', 'Tutor Create', 'Create or add a new tutor', 'Tutor', '2023-09-07 01:29:08', '2023-09-07 01:29:08'),
(18, 'tutor-edit', 'Tutor Edit', 'Edit tutor detail', 'Tutor', '2023-09-07 01:29:08', '2023-09-07 01:29:08'),
(19, 'tutor-show', 'Tutor Show', 'Show tutor detail', 'Tutor', '2023-09-07 01:29:08', '2023-09-07 01:29:08'),
(20, 'tutor-destroy', 'Tutor Destroy', 'Delete tutor', 'Tutor', '2023-09-07 01:29:08', '2023-09-07 01:29:08'),
(21, 'proctor-index', 'Proctor Index', 'Show all proctors', 'Proctor', '2023-09-17 01:04:40', '2023-09-17 01:04:40'),
(22, 'proctor-create', 'Proctor Create', 'Create or add a new proctor', 'Proctor', '2023-09-17 01:04:40', '2023-09-17 01:04:40'),
(23, 'proctor-edit', 'Proctor Edit', 'Edit proctor detail', 'Proctor', '2023-09-17 01:04:40', '2023-09-17 01:04:40'),
(24, 'proctor-show', 'Proctor Show', 'Show proctor detail', 'Proctor', '2023-09-17 01:04:40', '2023-09-17 01:04:40'),
(25, 'proctor-destroy', 'Proctor Destroy', 'Delete proctor', 'Proctor', '2023-09-17 01:04:40', '2023-09-17 01:04:40'),
(26, 'student_tutoring_package-index', 'Student Tutoring Package Index', 'Show all packages', 'Student Tutoring Package', '2023-09-19 11:27:37', '2023-09-19 11:27:37'),
(27, 'student_tutoring_package-create', 'Student Tutoring Package Create', 'Create or add a new package', 'Student Tutoring Package', '2023-09-19 11:27:37', '2023-09-19 11:27:37'),
(28, 'student_tutoring_package-edit', 'Student Tutoring Package Edit', 'Edit package detail', 'Student Tutoring Package', '2023-09-19 11:27:37', '2023-09-19 11:27:37'),
(29, 'student_tutoring_package-show', 'Student Tutoring Package Show', 'Show package detail', 'Student Tutoring Package', '2023-09-19 11:27:37', '2023-09-19 11:27:37'),
(30, 'student_tutoring_package-destroy', 'Student Tutoring Package Destroy', 'Delete package', 'Student Tutoring Package', '2023-09-19 11:27:37', '2023-09-19 11:27:37'),
(31, 'subject-index', 'Subject Index', 'Show all subjects', 'Subject', '2023-09-19 11:28:24', '2023-09-19 11:28:24'),
(32, 'subject-create', 'Subject Create', 'Create or add a new subject', 'Subject', '2023-09-19 11:28:24', '2023-09-19 11:28:24'),
(33, 'subject-edit', 'Subject Edit', 'Edit subject detail', 'Subject', '2023-09-19 11:28:24', '2023-09-19 11:28:24'),
(34, 'subject-show', 'Subject Show', 'Show subject detail', 'Subject', '2023-09-19 11:28:24', '2023-09-19 11:28:24'),
(35, 'subject-destroy', 'Subject Destroy', 'Delete subject', 'Subject', '2023-09-19 11:28:24', '2023-09-19 11:28:24'),
(36, 'tutoring_location-index', 'Tutoring Location Index', 'Show all tutoring \n locations', 'Tutoring Location', '2023-09-19 11:29:08', '2023-09-19 11:29:08'),
(37, 'tutoring_location-create', 'Tutoring Location Create', 'Create or add a new tutoring location', 'Tutoring Location', '2023-09-19 11:29:08', '2023-09-19 11:29:08'),
(38, 'tutoring_location-edit', 'Tutoring Location Edit', 'Edit tutoring location detail', 'Tutoring Location', '2023-09-19 11:29:08', '2023-09-19 11:29:08'),
(39, 'tutoring_location-show', 'Tutoring Location Show', 'Show Tutoring location detail', 'Tutoring Location', '2023-09-19 11:29:08', '2023-09-19 11:29:08'),
(40, 'tutoring_location-destroy', 'Location Tutoring Destroy', 'Delete tutoring location', 'Tutoring Location', '2023-09-19 11:29:08', '2023-09-19 11:29:08'),
(41, 'tutoring_package_type-index', 'Tutoring Package Type Index', 'Show all Tutoring  package_types', 'Tutoring Package Type', '2023-09-19 12:41:29', '2023-09-19 12:41:29'),
(42, 'tutoring_package_type-create', 'Tutoring Package Type Create', 'Create or add a new package_type', 'Tutoring Package Type', '2023-09-19 12:41:29', '2023-09-19 12:41:29'),
(43, 'tutoring_package_type-edit', 'Tutoring Package Type Edit', 'Edit Tutoring  package_type detail', 'Tutoring Package Type', '2023-09-19 12:41:29', '2023-09-19 12:41:29'),
(44, 'tutoring_package_type-show', 'Tutoring Package Type Show', 'Show Tutoring  package_type detail', 'Tutoring Package Type', '2023-09-19 12:41:29', '2023-09-19 12:41:29'),
(45, 'tutoring_package_type-destroy', 'Tutoring Package Type Destroy', 'Delete Tutoring  package_type', 'Tutoring Package Type', '2023-09-19 12:41:29', '2023-09-19 12:41:29'),
(46, 'package_type-index', 'Package Type Index', 'Show all package types', 'Package Type', '2023-09-23 21:26:02', '2023-09-23 21:26:02'),
(47, 'package_type-create', 'Package Type Create', 'Create or add a new package type', 'Package Type', '2023-09-23 21:26:02', '2023-09-23 21:26:02'),
(48, 'package_type-edit', 'Package Type Edit', 'Edit package type detail', 'Package Type', '2023-09-23 21:26:02', '2023-09-23 21:26:02'),
(49, 'package_type-show', 'Package Type Show', 'Show package type detail', 'Package Type', '2023-09-23 21:26:02', '2023-09-23 21:26:02'),
(50, 'package_type-destroy', 'Package Type Destroy', 'Delete package type', 'Package Type', '2023-09-23 21:26:02', '2023-09-23 21:26:02'),
(51, 'invoice_package_type-index', 'Invoice Package Type Index', 'Show all package types', 'Invoice Package Type', '2023-09-24 07:05:31', '2023-09-24 07:05:31'),
(52, 'invoice_package_type-create', 'Invoice Package Type Create', 'Create or add a new invoice package type', 'Invoice Package Type', '2023-09-24 07:05:31', '2023-09-24 07:05:31'),
(53, 'invoice_package_type-edit', 'Invoice Package Type Edit', 'Edit invoice package type detail', 'Invoice Package Type', '2023-09-24 07:05:31', '2023-09-24 07:05:31'),
(54, 'invoice_package_type-show', 'Invoice Package Type Show', 'Show invoice package type detail', 'Invoice Package Type', '2023-09-24 07:05:31', '2023-09-24 07:05:31'),
(55, 'invoice_package_type-destroy', 'Invoice Package Type Destroy', 'Delete invoice invoice package type', 'Invoice Package Type', '2023-09-24 07:05:31', '2023-09-24 07:05:31'),
(56, 'invoice-index', 'Invoice Index', 'Show all invoices', 'Invoice', '2023-09-24 08:51:08', '2023-09-24 08:51:08'),
(57, 'invoice-create', 'Invoice Create', 'Create or add a new invoice', 'Invoice', '2023-09-24 08:51:08', '2023-09-24 08:51:08'),
(58, 'invoice-edit', 'Invoice Edit', 'Edit invoice detail', 'Invoice', '2023-09-24 08:51:08', '2023-09-24 08:51:08'),
(59, 'invoice-show', 'Invoice Show', 'Show invoice detail', 'Invoice', '2023-09-24 08:51:08', '2023-09-24 08:51:08'),
(60, 'invoice-destroy', 'Invoice Destroy', 'Delete invoice', 'Invoice', '2023-09-24 08:51:08', '2023-09-24 08:51:08');

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permission_role`
--

INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES
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
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(52, 1),
(53, 1),
(54, 1),
(55, 1),
(56, 1),
(57, 1),
(58, 1),
(59, 1),
(60, 1);

-- --------------------------------------------------------

--
-- Table structure for table `permission_user`
--

CREATE TABLE `permission_user` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `user_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'super-admin', 'Super Admin', 'Super man', '2023-09-07 01:30:19', '2023-09-07 01:30:19'),
(2, 'admin', 'Admin', 'Role of Admin', '2023-09-10 01:46:16', '2023-09-10 01:46:16'),
(3, 'parent', 'Parent', 'Role of Parent', '2023-09-10 01:46:50', '2023-09-10 01:46:50'),
(4, 'student', 'Student', 'Role of student', '2023-09-10 01:47:08', '2023-09-10 01:47:08'),
(5, 'tutor', 'Tutor', 'Role of tutor', '2023-09-10 01:47:32', '2023-09-10 01:47:32'),
(6, 'proctor', 'Proctor', 'Role of Proctor', '2023-09-10 01:47:53', '2023-09-10 01:47:53'),
(7, 'client', 'Client', 'Role of client', '2023-09-10 01:48:23', '2023-09-10 01:48:23'),
(8, 'developer', 'Developer', 'Developers', '2023-09-10 23:48:10', '2023-09-11 00:06:46');

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `role_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `user_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`role_id`, `user_id`, `user_type`) VALUES
(1, 1, 'App\\Models\\User'),
(3, 1, 'App\\Models\\ParentUser'),
(3, 2, 'App\\Models\\ParentUser'),
(3, 3, 'App\\Models\\ParentUser'),
(4, 1, 'App\\Models\\Student'),
(4, 2, 'App\\Models\\Student'),
(4, 3, 'App\\Models\\Student'),
(5, 1, 'App\\Models\\Tutor'),
(5, 2, 'App\\Models\\Tutor'),
(5, 3, 'App\\Models\\Tutor'),
(5, 4, 'App\\Models\\Tutor');

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE `schools` (
  `id` bigint UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `auth_guard` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `added_by` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `schools`
--

INSERT INTO `schools` (`id`, `name`, `address`, `status`, `auth_guard`, `added_by`, `created_at`, `updated_at`) VALUES
(1, 'Aurora Tran', 'Fuga Nostrum numqua', 1, 'web', 1, '2023-09-21 16:19:08', '2023-09-21 16:19:08');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_known` tinyint(1) DEFAULT NULL,
  `testing_accommodation` tinyint(1) DEFAULT NULL,
  `testing_accommodation_nature` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `official_baseline_act_score` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `official_baseline_sat_score` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `test_anxiety_challenge` tinyint(1) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `auth_guard` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `added_by` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `school_id`, `parent_id`, `first_name`, `last_name`, `email`, `email_verified_at`, `password`, `remember_token`, `email_known`, `testing_accommodation`, `testing_accommodation_nature`, `official_baseline_act_score`, `official_baseline_sat_score`, `test_anxiety_challenge`, `status`, `auth_guard`, `added_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Xerxes', 'Hodges', 'jenerot@mailinator.com', NULL, '$2y$10$g8uPS7UW0f/C1GhYg83bBegoH32DFRYXJfSiwUcN1KN/j7QwFcklu', NULL, 0, 1, 'At totam qui volupta', 'Facere id Nam quis a', 'Mollit placeat cons', 0, 1, 'web', 1, '2023-09-21 16:19:32', '2023-09-21 16:19:32'),
(2, 1, 1, 'Omar', 'Ortega', 'cotafil@mailinator.com', NULL, '$2y$10$3ZffpGli2YcooKHO6B9C/OUy5m/vo8f.PbOlzEl2ymDunj92GExOe', NULL, 0, 0, 'Quia quam quae accus', 'Vel irure adipisicin', 'Laborum Fugit plac', 0, 1, 'web', 1, '2023-09-21 17:39:18', '2023-09-21 17:39:18'),
(3, 1, 2, 'Tanner', 'Medina', 'horo@mailinator.com', NULL, '$2y$10$KXhllkdviCEqdEApNzITAe4ynDs5rIcM3Mc/cIbuNoLBCYfeCUVoO', NULL, 1, 1, 'Neque id amet inve', 'Enim est id aut susc', 'Ad sunt incidunt la', 1, 1, 'web', 1, '2023-09-24 13:18:02', '2023-09-24 13:18:02');

-- --------------------------------------------------------

--
-- Table structure for table `student_tutoring_packages`
--

CREATE TABLE `student_tutoring_packages` (
  `id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `tutoring_package_type_id` bigint UNSIGNED NOT NULL,
  `tutoring_location_id` bigint UNSIGNED NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `internal_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `hours` int NOT NULL,
  `hourly_rate` int NOT NULL,
  `discount` int DEFAULT NULL,
  `discount_type` int DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `tutor_hourly_rate` int NOT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `auth_guard` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `added_by` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_tutoring_packages`
--

INSERT INTO `student_tutoring_packages` (`id`, `student_id`, `tutoring_package_type_id`, `tutoring_location_id`, `notes`, `internal_notes`, `hours`, `hourly_rate`, `discount`, `discount_type`, `start_date`, `tutor_hourly_rate`, `is_completed`, `status`, `auth_guard`, `added_by`, `created_at`, `updated_at`) VALUES
(12, 2, 1, 4, 'Magni aut dolore id', 'Ut culpa et excepte', 24, 100, 2, 2, '2023-09-27', 95, 0, 1, 'web', 1, '2023-09-24 13:16:12', '2023-09-24 13:16:12'),
(13, 3, 1, 4, 'Est beatae qui non v', 'Aut qui illum expli', 24, 150, 30, 1, '2023-09-25', 50, 0, 1, 'web', 1, '2023-09-24 13:18:57', '2023-09-24 13:18:57'),
(14, 3, 1, 6, 'Doloribus ut deserun', 'Eaque et vel et earu', 24, 160, 30, 1, '2023-09-25', 60, 0, 1, 'web', 1, '2023-09-24 13:21:10', '2023-09-24 13:21:10'),
(15, 3, 4, 4, 'Molestias similique', 'Sit in illum rem lo', 72, 180, 4, 2, '2023-09-25', 60, 0, 1, 'web', 1, '2023-09-24 13:23:13', '2023-09-24 13:23:13'),
(16, 3, 2, 6, 'Et assumenda eligend', 'Soluta autem minima', 48, 60, 1, 2, '2023-09-25', 30, 0, 1, 'web', 1, '2023-09-24 13:25:01', '2023-09-24 13:25:01'),
(17, 3, 2, 4, 'Ut esse eaque tempo', 'Blanditiis tenetur a', 48, 200, 100, 1, '2023-09-25', 70, 0, 1, 'web', 1, '2023-09-24 13:26:07', '2023-09-24 13:26:07');

-- --------------------------------------------------------

--
-- Table structure for table `student_tutoring_package_subject`
--

CREATE TABLE `student_tutoring_package_subject` (
  `subject_id` bigint UNSIGNED NOT NULL,
  `student_tutoring_package_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_tutoring_package_subject`
--

INSERT INTO `student_tutoring_package_subject` (`subject_id`, `student_tutoring_package_id`) VALUES
(1, 12),
(3, 12),
(5, 12),
(7, 12),
(8, 12),
(9, 12),
(12, 12),
(1, 13),
(3, 13),
(4, 13),
(6, 13),
(7, 13),
(9, 13),
(12, 13),
(1, 14),
(2, 14),
(6, 14),
(8, 14),
(9, 14),
(12, 14),
(1, 15),
(2, 15),
(5, 15),
(6, 15),
(7, 15),
(8, 15),
(11, 15),
(12, 15),
(2, 16),
(3, 16),
(6, 16),
(8, 16),
(9, 16),
(10, 16),
(1, 17),
(3, 17),
(6, 17),
(7, 17),
(8, 17),
(12, 17);

-- --------------------------------------------------------

--
-- Table structure for table `student_tutoring_package_tutor`
--

CREATE TABLE `student_tutoring_package_tutor` (
  `tutor_id` bigint UNSIGNED NOT NULL,
  `student_tutoring_package_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_tutoring_package_tutor`
--

INSERT INTO `student_tutoring_package_tutor` (`tutor_id`, `student_tutoring_package_id`) VALUES
(1, 12),
(3, 13),
(4, 14),
(3, 15),
(3, 16),
(3, 17);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` bigint UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `auth_guard` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `added_by` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `name`, `status`, `auth_guard`, `added_by`, `created_at`, `updated_at`) VALUES
(1, 'ACT', 1, 'web', 1, '2023-09-21 16:21:03', '2023-09-21 16:21:29'),
(2, 'SAT', 1, 'web', 1, '2023-09-21 16:21:46', '2023-09-21 16:21:55'),
(3, 'HS Math', 1, 'web', 1, '2023-09-21 16:22:06', '2023-09-21 16:22:06'),
(4, 'English', 1, 'web', 1, '2023-09-23 12:28:18', '2023-09-23 12:28:18'),
(5, 'Applied Math', 1, 'web', 1, '2023-09-23 12:30:37', '2023-09-23 12:30:37'),
(6, 'Physics', 1, 'web', 1, '2023-09-23 12:47:41', '2023-09-23 12:47:41'),
(7, 'Urdu', 1, 'web', 1, '2023-09-23 15:16:29', '2023-09-23 15:16:29'),
(8, 'CS101', 1, 'web', 1, '2023-09-23 15:18:47', '2023-09-23 15:18:47'),
(9, 'CS909', 1, 'web', 1, '2023-09-23 15:19:20', '2023-09-23 15:19:20'),
(10, 'DS101', 1, 'web', 1, '2023-09-23 15:21:14', '2023-09-23 15:21:14'),
(11, 'DS102', 1, 'web', 1, '2023-09-23 15:21:43', '2023-09-23 15:21:43'),
(12, 'DS109', 1, 'web', 1, '2023-09-23 15:21:53', '2023-09-23 15:21:53');

-- --------------------------------------------------------

--
-- Table structure for table `tutoring_locations`
--

CREATE TABLE `tutoring_locations` (
  `id` bigint UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `auth_guard` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `added_by` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tutoring_locations`
--

INSERT INTO `tutoring_locations` (`id`, `name`, `status`, `auth_guard`, `added_by`, `created_at`, `updated_at`) VALUES
(1, 'Faith Dorsey', 1, 'web', 1, '2023-09-21 16:22:15', '2023-09-21 16:22:15'),
(2, 'Winter Barrett', 1, 'web', 1, '2023-09-21 16:22:18', '2023-09-21 16:22:18'),
(3, 'Tanisha Dudley', 1, 'web', 1, '2023-09-21 16:22:21', '2023-09-21 16:22:21'),
(4, 'Home', 1, 'web', 1, '2023-09-23 12:28:32', '2023-09-23 12:28:32'),
(5, 'Matthew Holder', 1, 'web', 1, '2023-09-23 12:30:47', '2023-09-23 12:30:47'),
(6, 'Onsite', 1, 'web', 1, '2023-09-23 12:47:59', '2023-09-23 12:47:59');

-- --------------------------------------------------------

--
-- Table structure for table `tutoring_package_types`
--

CREATE TABLE `tutoring_package_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `hours` int NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `auth_guard` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `added_by` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tutoring_package_types`
--

INSERT INTO `tutoring_package_types` (`id`, `name`, `hours`, `status`, `auth_guard`, `added_by`, `created_at`, `updated_at`) VALUES
(1, '24 Hours', 24, 1, 'web', 1, '2023-09-21 16:20:57', '2023-09-21 16:21:14'),
(2, '48 hours', 48, 1, 'web', 1, '2023-09-21 16:21:23', '2023-09-21 16:21:23'),
(3, 'Default 24 hours Package', 24, 1, 'web', 1, '2023-09-23 12:28:04', '2023-09-23 12:28:04'),
(4, '72 Hours Default Pacakge', 72, 1, 'web', 1, '2023-09-23 12:30:11', '2023-09-23 12:30:11'),
(5, 'Custome Premium Package', 48, 1, 'web', 1, '2023-09-23 12:47:33', '2023-09-23 12:47:33'),
(6, 'Donovan Guerra', 540, 1, 'web', 1, '2023-09-23 16:13:16', '2023-09-23 16:13:28');

-- --------------------------------------------------------

--
-- Table structure for table `tutors`
--

CREATE TABLE `tutors` (
  `id` bigint UNSIGNED NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secondary_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secondary_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resume` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `auth_guard` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `added_by` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `hourly_rate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tutors`
--

INSERT INTO `tutors` (`id`, `first_name`, `last_name`, `email`, `email_verified_at`, `password`, `remember_token`, `secondary_email`, `phone`, `secondary_phone`, `picture`, `resume`, `start_date`, `status`, `auth_guard`, `added_by`, `created_at`, `updated_at`, `hourly_rate`) VALUES
(1, 'Olivia', 'Richmond', 'gakyru@mailinator.com', NULL, '$2y$10$aQHq1GpCuMeWCyqpJEoK6OePfHTsOoGi3JkwyKHrbWIpBCv73nkmO', NULL, 'gakyru@mailinator.com', '+1 (199) 334-1164', '+1 (199) 334-1164', NULL, NULL, '1984-04-18', 1, 'web', 1, '2023-09-21 16:20:26', '2023-09-21 16:20:26', NULL),
(2, 'Debra', 'Jordan', 'kocerede@mailinator.com', NULL, '$2y$10$Cb/ZEk2LitbiEOcB.D0P8ey52lKSU5UAiHyHuBAbCNwTcPRWghz/i', NULL, 'kocerede@mailinator.com', '+1 (816) 293-6964', '+1 (816) 293-6964', NULL, NULL, '2020-01-23', 1, 'web', 1, '2023-09-21 16:30:31', '2023-09-21 16:30:31', NULL),
(3, 'Kimberley', 'Snider', 'zygabyv@mailinator.com', NULL, '$2y$10$nUr6IBdoGf3Fnv7svLftYe7tL9VqHoyUiYCZyxC/Lz5yz1NSt.ya2', NULL, 'zygabyv@mailinator.com', '+1 (645) 801-2808', '+1 (645) 801-2808', NULL, NULL, '1982-09-18', 1, 'web', 1, '2023-09-21 16:30:35', '2023-09-21 16:31:03', NULL),
(4, 'Ainsley', 'Hill', 'byrypa@mailinator.com', NULL, '$2y$10$BtrnBg2uzJCB6zjCUgyY1.sffeDC/A9DjuRbnjcpsq2ZdOTh7fz2q', NULL, 'byrypa@mailinator.com', '+1 (487) 931-5815', '+1 (487) 931-5815', NULL, NULL, '1981-07-27', 1, 'web', 1, '2023-09-23 15:35:01', '2023-09-23 15:35:01', '55');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super', 'Admin', 'admin@admin.com', '2023-09-21 16:17:34', '$2y$10$.dUPpMo9NrDyOBtC7wBS/.3zLwaNyffqqyCs30YoutcWJhimRjTPO', 'aGo2484Jzvd5y8iOpz6VgjCbTY8axkQWKATj6pN2BZxytzRmSPbyKVTLSGud', '2023-09-21 16:17:34', '2023-09-21 16:17:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `api_calls_count`
--
ALTER TABLE `api_calls_count`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoices_invoice_package_type_id_foreign` (`invoice_package_type_id`);

--
-- Indexes for table `invoice_package_types`
--
ALTER TABLE `invoice_package_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `package_types`
--
ALTER TABLE `package_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parents`
--
ALTER TABLE `parents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `parents_email_unique` (`email`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_unique` (`name`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `permission_role_role_id_foreign` (`role_id`);

--
-- Indexes for table `permission_user`
--
ALTER TABLE `permission_user`
  ADD PRIMARY KEY (`user_id`,`permission_id`,`user_type`),
  ADD KEY `permission_user_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`user_id`,`role_id`,`user_type`),
  ADD KEY `role_user_role_id_foreign` (`role_id`);

--
-- Indexes for table `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `students_email_unique` (`email`),
  ADD KEY `students_school_id_foreign` (`school_id`),
  ADD KEY `students_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `student_tutoring_packages`
--
ALTER TABLE `student_tutoring_packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_tutoring_packages_student_id_foreign` (`student_id`),
  ADD KEY `student_tutoring_packages_package_type_id_foreign` (`tutoring_package_type_id`),
  ADD KEY `student_tutoring_packages_tutoring_location_id_foreign` (`tutoring_location_id`);

--
-- Indexes for table `student_tutoring_package_subject`
--
ALTER TABLE `student_tutoring_package_subject`
  ADD KEY `tutoring_package_subject` (`subject_id`),
  ADD KEY `tutoring_package_id_subject` (`student_tutoring_package_id`);

--
-- Indexes for table `student_tutoring_package_tutor`
--
ALTER TABLE `student_tutoring_package_tutor`
  ADD KEY `tutoring_package_tutor` (`tutor_id`),
  ADD KEY `tutoring_package_id` (`student_tutoring_package_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tutoring_locations`
--
ALTER TABLE `tutoring_locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tutoring_package_types`
--
ALTER TABLE `tutoring_package_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tutors`
--
ALTER TABLE `tutors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `api_calls_count`
--
ALTER TABLE `api_calls_count`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `invoice_package_types`
--
ALTER TABLE `invoice_package_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `package_types`
--
ALTER TABLE `package_types`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `parents`
--
ALTER TABLE `parents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `schools`
--
ALTER TABLE `schools`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `student_tutoring_packages`
--
ALTER TABLE `student_tutoring_packages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tutoring_locations`
--
ALTER TABLE `tutoring_locations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tutoring_package_types`
--
ALTER TABLE `tutoring_package_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tutors`
--
ALTER TABLE `tutors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_invoice_package_type_id_foreign` FOREIGN KEY (`invoice_package_type_id`) REFERENCES `invoice_package_types` (`id`);

--
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `permission_user`
--
ALTER TABLE `permission_user`
  ADD CONSTRAINT `permission_user_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `parents` (`id`),
  ADD CONSTRAINT `students_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`);

--
-- Constraints for table `student_tutoring_packages`
--
ALTER TABLE `student_tutoring_packages`
  ADD CONSTRAINT `student_tutoring_packages_package_type_id_foreign` FOREIGN KEY (`tutoring_package_type_id`) REFERENCES `tutoring_package_types` (`id`),
  ADD CONSTRAINT `student_tutoring_packages_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`),
  ADD CONSTRAINT `student_tutoring_packages_tutoring_location_id_foreign` FOREIGN KEY (`tutoring_location_id`) REFERENCES `tutoring_locations` (`id`);

--
-- Constraints for table `student_tutoring_package_subject`
--
ALTER TABLE `student_tutoring_package_subject`
  ADD CONSTRAINT `tutoring_package_id_subject` FOREIGN KEY (`student_tutoring_package_id`) REFERENCES `student_tutoring_packages` (`id`),
  ADD CONSTRAINT `tutoring_package_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`);

--
-- Constraints for table `student_tutoring_package_tutor`
--
ALTER TABLE `student_tutoring_package_tutor`
  ADD CONSTRAINT `tutoring_package_id` FOREIGN KEY (`student_tutoring_package_id`) REFERENCES `student_tutoring_packages` (`id`),
  ADD CONSTRAINT `tutoring_package_tutor` FOREIGN KEY (`tutor_id`) REFERENCES `tutors` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
