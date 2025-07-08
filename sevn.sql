-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 05, 2025 at 09:16 AM
-- Server version: 9.3.0
-- PHP Version: 8.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sevn`
--

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bank` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bank_code` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bank_branches`
--

CREATE TABLE `bank_branches` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bank_id` bigint DEFAULT NULL,
  `branch` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `branch_code` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `businesses`
--

CREATE TABLE `businesses` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `business` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `business_branches`
--

CREATE TABLE `business_branches` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `business_id` bigint DEFAULT NULL,
  `business_branch` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `session_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `business_id` bigint DEFAULT NULL,
  `product_id` bigint DEFAULT NULL,
  `size_id` bigint DEFAULT NULL,
  `color_id` bigint DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `slug` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `category` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `display_order` int DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `district_id` bigint DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `zip` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

CREATE TABLE `colors` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `color` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `color_code` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `business_id` bigint DEFAULT NULL,
  `discount_rule_id` bigint DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `coupon` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_auth_required` tinyint DEFAULT NULL,
  `per_user` int DEFAULT NULL,
  `number_of_counpons` int DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `is_free_shipping` tinyint DEFAULT NULL,
  `is_all_products` tinyint DEFAULT NULL,
  `is_all_categories` tinyint DEFAULT NULL,
  `note` longtext COLLATE utf8mb4_general_ci,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_products`
--

CREATE TABLE `coupon_products` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `coupon_id` bigint DEFAULT NULL,
  `product_id` bigint DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_product_categories`
--

CREATE TABLE `coupon_product_categories` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `coupon_id` bigint DEFAULT NULL,
  `category_id` bigint DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_usages`
--

CREATE TABLE `coupon_usages` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `coupon_id` bigint DEFAULT NULL,
  `order_id` bigint DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courier_charges`
--

CREATE TABLE `courier_charges` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `courier_service_id` bigint DEFAULT NULL,
  `city_id` bigint DEFAULT NULL,
  `charge` float DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courier_services`
--

CREATE TABLE `courier_services` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `courier_service` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_default` tinyint DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_methods`
--

CREATE TABLE `delivery_methods` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `delivery_method` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `display_order` int DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `designations`
--

CREATE TABLE `designations` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `designation` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `business_id` bigint DEFAULT NULL,
  `discount_rule_id` bigint DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `is_auth_required` tinyint DEFAULT NULL,
  `per_user` int DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `is_free_shipping` tinyint DEFAULT NULL,
  `is_all_products` tinyint DEFAULT NULL,
  `is_all_categories` tinyint DEFAULT NULL,
  `note` longtext COLLATE utf8mb4_general_ci,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discount_methods`
--

CREATE TABLE `discount_methods` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `discount_method` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discount_products`
--

CREATE TABLE `discount_products` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `discount_id` bigint DEFAULT NULL,
  `product_id` bigint DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discount_product_categories`
--

CREATE TABLE `discount_product_categories` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `discount_id` bigint DEFAULT NULL,
  `category_id` bigint DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discount_rules`
--

CREATE TABLE `discount_rules` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `discount_rule` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `discount_method_id` bigint DEFAULT NULL,
  `discount_value` float DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` tinyint DEFAULT NULL,
  `updated_at` tinyint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discount_types`
--

CREATE TABLE `discount_types` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `discount_type` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `district` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Table structure for table `gender_categories`
--

CREATE TABLE `gender_categories` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gender_category` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `display_order` int DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Table structure for table `loyalties`
--

CREATE TABLE `loyalties` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `loyalty` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_general_ci,
  `per_amount` float DEFAULT NULL,
  `per_value` float DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(4, '2025_07_01_063347_create_personal_access_tokens_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `business_branch_id` bigint DEFAULT NULL,
  `payment_method_id` bigint DEFAULT NULL,
  `b_first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `b_last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `b_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `b_phone_1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `b_phone_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `b_phone_3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `b_street_address` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `b_address2` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `b_town` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `b_city` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `b_postcode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `b_city_id` bigint DEFAULT NULL,
  `b_country_id` bigint DEFAULT NULL,
  `s_first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `s_last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `s_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `s_phone_1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `s_phone_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `s_phone_3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `s_street_address` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `s_address2` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `s_town` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `s_state` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `s_postcode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `s_city_id` bigint DEFAULT NULL,
  `s_country_id` bigint DEFAULT NULL,
  `is_refunded` tinyint DEFAULT NULL,
  `refunded_by` bigint DEFAULT NULL,
  `refunded_at` datetime DEFAULT NULL,
  `is_cancelled` tinyint DEFAULT NULL,
  `cancelled_by` bigint DEFAULT NULL,
  `cancelled_at` datetime DEFAULT NULL,
  `discount_type_id` bigint DEFAULT NULL,
  `discount` float DEFAULT NULL,
  `discount_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_value` float DEFAULT NULL,
  `shipping_cost` float DEFAULT NULL,
  `vat` float DEFAULT NULL,
  `tax` float DEFAULT NULL,
  `other_charges` float DEFAULT NULL,
  `note` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `order_status_id` bigint DEFAULT NULL,
  `created_by` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `us_user_reference_userd` tinyint DEFAULT NULL,
  `delivery_method_id` bigint DEFAULT NULL,
  `courier_service_id` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_progresses`
--

CREATE TABLE `order_progresses` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `order_id` bigint DEFAULT NULL,
  `progress_id` bigint DEFAULT NULL,
  `progress_time` datetime DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_statuses`
--

CREATE TABLE `order_statuses` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_frontend` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `label_frontend` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_backend` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `label_backend` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `product_range_id` bigint DEFAULT NULL,
  `slug` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `product` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `short_description` text COLLATE utf8mb4_general_ci,
  `description` longtext COLLATE utf8mb4_general_ci,
  `stroke_price` float DEFAULT NULL,
  `price` float DEFAULT NULL,
  `meta_image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `meta_title` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_general_ci,
  `meta_keywords` text COLLATE utf8mb4_general_ci,
  `is_quantity_based_discount` tinyint DEFAULT NULL,
  `is_deleted` tinyint DEFAULT NULL,
  `deleted_by` bigint DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `is_in_stock` tinyint DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_by` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `product_id` bigint DEFAULT NULL,
  `category_id` bigint DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_gender_categories`
--

CREATE TABLE `product_gender_categories` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `product_id` bigint DEFAULT NULL,
  `gender_category_id` bigint DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `product_id` bigint DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_primary` tinyint DEFAULT NULL,
  `is_secondary` tinyint DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_inventories`
--

CREATE TABLE `product_inventories` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `product_id` bigint DEFAULT NULL,
  `product_size_id` bigint DEFAULT NULL,
  `product_color_id` bigint DEFAULT NULL,
  `created_by` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `manufacture_date` datetime DEFAULT NULL,
  `manufacture_cost` float DEFAULT NULL,
  `supplier_id` bigint DEFAULT NULL,
  `barcode` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `qr_code` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `batch_no` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `order_id` bigint DEFAULT NULL,
  `price` float DEFAULT NULL,
  `discount_type_id` bigint DEFAULT NULL,
  `discount` float DEFAULT NULL,
  `discount_method` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `discount_label` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `disccount_value` float DEFAULT NULL,
  `shipping_cost` float DEFAULT NULL,
  `is_refunded` tinyint DEFAULT NULL,
  `refunded_by` bigint DEFAULT NULL,
  `refunded_at` datetime DEFAULT NULL,
  `refund_note` longtext COLLATE utf8mb4_general_ci,
  `is_quantity_based_discount` tinyint DEFAULT NULL,
  `quantity_based_discount_method` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `quantity_based_discount_value` float DEFAULT NULL,
  `note` longtext COLLATE utf8mb4_general_ci,
  `status` tinyint DEFAULT NULL,
  `is_deleted` tinyint DEFAULT NULL,
  `deleted_by` bigint DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_quantity_based_discount`
--

CREATE TABLE `product_quantity_based_discount` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `product_id` bigint DEFAULT NULL,
  `discount_method_id` bigint DEFAULT NULL,
  `from_quantity` int DEFAULT NULL,
  `to_quantity` int DEFAULT NULL,
  `discount_value` float DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_ranges`
--

CREATE TABLE `product_ranges` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `product_range` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `label` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_related_products`
--

CREATE TABLE `product_related_products` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `product_id` bigint DEFAULT NULL,
  `related_product_id` bigint DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_tags`
--

CREATE TABLE `product_tags` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `product_id` bigint DEFAULT NULL,
  `tag_id` bigint DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `progresses`
--

CREATE TABLE `progresses` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `progress_frontend` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `label_frontend` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `progress_backend` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `label_backend` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `screens`
--

CREATE TABLE `screens` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `business_id` bigint DEFAULT NULL,
  `screen` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `screen_prefix` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `sizes`
--

CREATE TABLE `sizes` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `size` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tag` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `public_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_role_id` int DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_email_subscribed` tinyint DEFAULT NULL,
  `is_phone_subscribed` tinyint DEFAULT NULL,
  `is_whatsapp_subscribed` tinyint DEFAULT NULL,
  `profit_margin_on_sale` double DEFAULT NULL,
  `user_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_discount_available` tinyint DEFAULT NULL,
  `discount_rule_id` bigint DEFAULT NULL,
  `default_business_id` bigint DEFAULT NULL,
  `default_business_branch_id` bigint DEFAULT NULL,
  `registered_business_id` bigint DEFAULT NULL,
  `registered_business_branch_id` bigint DEFAULT NULL,
  `registered_by` bigint DEFAULT NULL,
  `last_activity_time` datetime DEFAULT NULL,
  `last_order_time` datetime DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `is_deleted` tinyint DEFAULT NULL,
  `deleted_by` bigint DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_businesses`
--

CREATE TABLE `user_businesses` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `business_id` bigint DEFAULT NULL,
  `designation_id` bigint DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_business_branches`
--

CREATE TABLE `user_business_branches` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `business_branch_id` bigint DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_contacts`
--

CREATE TABLE `user_contacts` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone_1` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone_2` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone_3` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_phone_1_verified` tinyint DEFAULT NULL,
  `is_phone_2_verified` tinyint DEFAULT NULL,
  `is_phone_3_verified` tinyint DEFAULT NULL,
  `is_email_verified` tinyint DEFAULT NULL,
  `phone_1_verified_at` datetime DEFAULT NULL,
  `phone_2_verified_at` datetime DEFAULT NULL,
  `phone_3_verified_at` datetime DEFAULT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `street_address` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address2` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `town` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `state` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `postcode` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `city_id` bigint DEFAULT NULL,
  `country_id` bigint DEFAULT NULL,
  `is_primary` tinyint DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `is_deleted` tinyint DEFAULT NULL,
  `deleted_by` bigint DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_contact_bank_account_details`
--

CREATE TABLE `user_contact_bank_account_details` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_contact_id` bigint DEFAULT NULL,
  `bank_id` bigint DEFAULT NULL,
  `bank_branch_id` bigint DEFAULT NULL,
  `account_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_primary` tinyint DEFAULT NULL,
  `status_` tinyint DEFAULT NULL,
  `is_deleted` tinyint DEFAULT NULL,
  `deleted_by` bigint DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_loyalties`
--

CREATE TABLE `user_loyalties` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `loyalty_id` bigint DEFAULT NULL,
  `is_active` tinyint DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_loyalty_earnings`
--

CREATE TABLE `user_loyalty_earnings` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `order_id` bigint DEFAULT NULL,
  `loyalty_id` bigint DEFAULT NULL,
  `order_total` float DEFAULT NULL,
  `loyalty_earnings` float DEFAULT NULL,
  `is_used` tinyint DEFAULT NULL,
  `used_order_id` bigint DEFAULT NULL,
  `used_at` datetime DEFAULT NULL,
  `used_by` bigint DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_role` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `label` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_screens`
--

CREATE TABLE `user_screens` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `screen_id` bigint DEFAULT NULL,
  `is_view` tinyint DEFAULT NULL,
  `is_create` tinyint DEFAULT NULL,
  `is_update` tinyint DEFAULT NULL,
  `is_delete` tinyint DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_tags`
--

CREATE TABLE `user_tags` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `tag_id` bigint DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` bigint NOT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `product_id` bigint DEFAULT NULL,
  `size_id` bigint DEFAULT NULL,
  `color_id` bigint DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank_branches`
--
ALTER TABLE `bank_branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `businesses`
--
ALTER TABLE `businesses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `business_branches`
--
ALTER TABLE `business_branches`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupon_products`
--
ALTER TABLE `coupon_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupon_product_categories`
--
ALTER TABLE `coupon_product_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupon_usages`
--
ALTER TABLE `coupon_usages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courier_charges`
--
ALTER TABLE `courier_charges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courier_services`
--
ALTER TABLE `courier_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_methods`
--
ALTER TABLE `delivery_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `designations`
--
ALTER TABLE `designations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discount_methods`
--
ALTER TABLE `discount_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discount_products`
--
ALTER TABLE `discount_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discount_product_categories`
--
ALTER TABLE `discount_product_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discount_rules`
--
ALTER TABLE `discount_rules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discount_types`
--
ALTER TABLE `discount_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `gender_categories`
--
ALTER TABLE `gender_categories`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `loyalties`
--
ALTER TABLE `loyalties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_progresses`
--
ALTER TABLE `order_progresses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_statuses`
--
ALTER TABLE `order_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_gender_categories`
--
ALTER TABLE `product_gender_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_inventories`
--
ALTER TABLE `product_inventories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_quantity_based_discount`
--
ALTER TABLE `product_quantity_based_discount`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_ranges`
--
ALTER TABLE `product_ranges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_related_products`
--
ALTER TABLE `product_related_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_tags`
--
ALTER TABLE `product_tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `progresses`
--
ALTER TABLE `progresses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `screens`
--
ALTER TABLE `screens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `sizes`
--
ALTER TABLE `sizes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_businesses`
--
ALTER TABLE `user_businesses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_business_branches`
--
ALTER TABLE `user_business_branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_contacts`
--
ALTER TABLE `user_contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_contact_bank_account_details`
--
ALTER TABLE `user_contact_bank_account_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_loyalties`
--
ALTER TABLE `user_loyalties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_loyalty_earnings`
--
ALTER TABLE `user_loyalty_earnings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_screens`
--
ALTER TABLE `user_screens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_tags`
--
ALTER TABLE `user_tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank_branches`
--
ALTER TABLE `bank_branches`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `businesses`
--
ALTER TABLE `businesses`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `business_branches`
--
ALTER TABLE `business_branches`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `colors`
--
ALTER TABLE `colors`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupon_products`
--
ALTER TABLE `coupon_products`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupon_product_categories`
--
ALTER TABLE `coupon_product_categories`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupon_usages`
--
ALTER TABLE `coupon_usages`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courier_charges`
--
ALTER TABLE `courier_charges`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courier_services`
--
ALTER TABLE `courier_services`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_methods`
--
ALTER TABLE `delivery_methods`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `designations`
--
ALTER TABLE `designations`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discount_methods`
--
ALTER TABLE `discount_methods`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discount_products`
--
ALTER TABLE `discount_products`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discount_product_categories`
--
ALTER TABLE `discount_product_categories`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discount_rules`
--
ALTER TABLE `discount_rules`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discount_types`
--
ALTER TABLE `discount_types`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gender_categories`
--
ALTER TABLE `gender_categories`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loyalties`
--
ALTER TABLE `loyalties`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_progresses`
--
ALTER TABLE `order_progresses`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_statuses`
--
ALTER TABLE `order_statuses`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_gender_categories`
--
ALTER TABLE `product_gender_categories`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_inventories`
--
ALTER TABLE `product_inventories`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_quantity_based_discount`
--
ALTER TABLE `product_quantity_based_discount`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_ranges`
--
ALTER TABLE `product_ranges`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_related_products`
--
ALTER TABLE `product_related_products`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_tags`
--
ALTER TABLE `product_tags`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `progresses`
--
ALTER TABLE `progresses`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `screens`
--
ALTER TABLE `screens`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sizes`
--
ALTER TABLE `sizes`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_businesses`
--
ALTER TABLE `user_businesses`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_business_branches`
--
ALTER TABLE `user_business_branches`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_contacts`
--
ALTER TABLE `user_contacts`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_contact_bank_account_details`
--
ALTER TABLE `user_contact_bank_account_details`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_loyalties`
--
ALTER TABLE `user_loyalties`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_loyalty_earnings`
--
ALTER TABLE `user_loyalty_earnings`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_screens`
--
ALTER TABLE `user_screens`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_tags`
--
ALTER TABLE `user_tags`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
