-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 11, 2021 at 09:30 AM
-- Server version: 5.7.31
-- PHP Version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET timezone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `portal_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `azure_container`
--

DROP TABLE IF EXISTS `azure_container`;
CREATE TABLE IF NOT EXISTS `azure_container` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `azure_storage_account_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `azure_storage_account_id` (`azure_storage_account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `azure_container`
--

INSERT INTO `azure_container` (`id`, `azure_storage_account_id`, `name`, `region`, `created_at`, `updated_at`) VALUES
(1, 1, 'exchange', NULL, '2021-05-30 06:59:49', '2021-05-17 10:06:36'),
(2, 1, 'exchange-restore', NULL, '2021-05-30 06:59:49', '2021-05-17 10:06:36'),
(3, 1, 'exhange-restore', NULL, '2021-05-30 06:59:49', '2021-05-17 10:06:36'),
(4, 1, 'sharepoint-restore', NULL, '2021-05-30 06:59:49', '2021-05-17 10:06:36'),
(5, 2, 'exhange-restore', NULL, '2021-05-30 06:59:49', '2021-05-17 10:06:40'),
(6, 3, 'exhange-restore', NULL, '2021-05-30 06:59:49', '2021-05-17 10:06:44'),
(7, 4, 'exchange', NULL, '2021-05-30 06:59:49', '2021-05-17 10:06:47'),
(8, 4, 'exhange-restore', NULL, '2021-05-30 06:59:49', '2021-05-17 10:06:47'),
(9, 5, 'exchange', NULL, '2021-05-30 06:59:49', '2021-05-17 10:06:51'),
(10, 5, 'exchange-restore', NULL, '2021-05-30 06:59:49', '2021-05-17 10:06:52'),
(11, 5, 'onedrive', NULL, '2021-05-30 06:59:49', '2021-05-17 10:06:52'),
(12, 5, 'onedrive-restore', NULL, '2021-05-30 06:59:49', '2021-05-17 10:06:52'),
(13, 6, 'onedrive', NULL, '2021-05-30 06:59:49', '2021-05-17 10:06:57'),
(14, 6, 'onedrive-restore', NULL, '2021-05-30 06:59:49', '2021-05-17 10:06:57'),
(15, 7, 'exchange-restore', NULL, '2021-05-30 06:59:49', '2021-05-17 10:07:01'),
(16, 7, 'sharepoint', NULL, '2021-05-30 06:59:49', '2021-05-17 10:07:01'),
(17, 7, 'sharepoint-restore', NULL, '2021-05-30 06:59:49', '2021-05-17 10:07:01'),
(18, 8, 'exchange-restore', NULL, '2021-05-30 06:59:49', '2021-05-17 10:07:04'),
(19, 8, 'teams', NULL, '2021-05-30 06:59:49', '2021-05-17 10:07:04'),
(20, 8, 'teams-restore', NULL, '2021-05-30 06:59:49', '2021-05-17 10:07:04'),
(21, 9, 'sharepoint', NULL, '2021-05-30 06:59:49', '2021-05-17 10:07:08'),
(22, 9, 'sharepoint-restore', NULL, '2021-05-30 06:59:49', '2021-05-17 10:07:08'),
(23, 10, 'exchange', NULL, '2021-05-30 06:59:49', '2021-05-17 10:07:13'),
(24, 10, 'exchange-restore', NULL, '2021-05-30 06:59:49', '2021-05-17 10:07:13'),
(25, 11, 'exchange', NULL, '2021-05-30 06:59:49', '2021-05-17 10:07:16'),
(26, 11, 'exchange-restore', NULL, '2021-05-30 06:59:49', '2021-05-17 10:07:16'),
(27, 11, 'teams', NULL, '2021-05-30 06:59:49', '2021-05-17 10:07:16'),
(28, 11, 'teams-restore', NULL, '2021-05-30 06:59:49', '2021-05-17 10:07:16'),
(29, 12, 'teams', NULL, '2021-05-30 06:59:49', '2021-05-17 10:07:20'),
(30, 12, 'teams-restore', NULL, '2021-05-30 06:59:49', '2021-05-17 10:07:20'),
(31, 13, 'teams', NULL, '2021-05-30 06:59:49', '2021-05-17 10:07:24'),
(32, 13, 'teams-restore', NULL, '2021-05-30 06:59:49', '2021-05-17 10:07:25'),
(33, 14, 'teams', NULL, '2021-05-30 06:59:49', '2021-05-17 10:07:28'),
(34, 14, 'teams-restore', NULL, '2021-05-30 06:59:49', '2021-05-17 10:07:28'),
(35, 15, 'teams', NULL, '2021-05-30 06:59:49', '2021-05-17 10:07:32'),
(36, 15, 'teams-restore', NULL, '2021-05-30 06:59:49', '2021-05-17 10:07:32'),
(37, 16, 'teams', NULL, '2021-05-30 06:59:49', '2021-05-17 10:07:36'),
(38, 16, 'teams-restore', NULL, '2021-05-30 06:59:49', '2021-05-17 10:07:36'),
(39, 17, 'sharepoint', NULL, '2021-05-30 06:59:49', '2021-05-17 10:07:40'),
(40, 17, 'sharepoint-restore', NULL, '2021-05-30 06:59:49', '2021-05-17 10:07:40'),
(41, 18, 'teams', NULL, '2021-05-30 06:59:49', '2021-05-17 10:07:44'),
(42, 18, 'teams-restore', NULL, '2021-05-30 06:59:49', '2021-05-17 10:07:44'),
(43, 19, 'onedrive', NULL, '2021-05-30 06:59:49', '2021-05-17 10:07:47'),
(44, 19, 'onedrive-restore', NULL, '2021-05-30 06:59:49', '2021-05-17 10:07:47'),
(45, 20, 'exchange', NULL, '2021-05-30 06:59:49', '2021-05-17 10:07:51'),
(46, 20, 'exchange-restore', NULL, '2021-05-30 06:59:49', '2021-05-17 10:07:51'),
(47, 21, 'exchange', NULL, '2021-05-30 06:59:49', '2021-05-17 10:07:55'),
(48, 21, 'exchange-restore', NULL, '2021-05-30 06:59:49', '2021-05-17 10:07:55'),
(62, 28, 'exchange', 'global', '2021-06-27 12:46:14', '2021-06-27 12:46:14'),
(63, 28, 'exchange-restore', 'global', '2021-06-27 12:46:14', '2021-06-27 12:46:14'),
(64, 29, 'onedrive', 'global', '2021-06-27 13:06:07', '2021-06-27 13:06:07'),
(65, 29, 'onedrive-restore', 'global', '2021-06-27 13:06:08', '2021-06-27 13:06:08'),
(66, 30, 'sharepoint', 'global', '2021-06-27 13:21:52', '2021-06-27 13:21:52'),
(67, 30, 'sharepoint-restore', 'global', '2021-06-27 13:21:53', '2021-06-27 13:21:53'),
(68, 31, 'teams', 'global', '2021-06-28 13:26:59', '2021-06-28 13:26:59'),
(69, 31, 'teams-restore', 'global', '2021-06-28 13:26:59', '2021-06-28 13:26:59'),
(70, 32, 'exchange', 'global', '2021-06-28 14:51:51', '2021-06-28 14:51:51'),
(71, 32, 'exchange-restore', 'global', '2021-06-28 14:51:51', '2021-06-28 14:51:51'),
(72, 33, 'exchange', 'global', '2021-06-28 15:53:14', '2021-06-28 15:53:14'),
(73, 33, 'exchange-restore', 'global', '2021-06-28 15:53:14', '2021-06-28 15:53:14'),
(74, 34, 'exchange', 'global', '2021-06-30 12:19:39', '2021-06-30 12:19:39'),
(75, 34, 'exchange-restore', 'global', '2021-06-30 12:19:39', '2021-06-30 12:19:39'),
(76, 35, 'exchange', 'global', '2021-06-30 16:33:09', '2021-06-30 16:33:09'),
(77, 35, 'exchange-restore', 'global', '2021-06-30 16:33:10', '2021-06-30 16:33:10'),
(78, 36, 'exchange', 'global', '2021-07-28 08:28:51', '2021-07-28 08:28:51'),
(79, 36, 'exchange-restore', 'global', '2021-07-28 08:28:51', '2021-07-28 08:28:51'),
(80, 38, 'exchange', 'global', '2021-08-02 13:59:36', '2021-08-02 13:59:36'),
(81, 38, 'exchange-restore', 'global', '2021-08-02 13:59:37', '2021-08-02 13:59:37'),
(82, 39, 'onedrive', 'global', '2021-08-07 11:19:06', '2021-08-07 11:19:06'),
(83, 39, 'onedrive-restore', 'global', '2021-08-07 11:19:07', '2021-08-07 11:19:07'),
(84, 40, 'onedrive', 'global', '2021-08-07 11:20:35', '2021-08-07 11:20:35'),
(85, 40, 'onedrive-restore', 'global', '2021-08-07 11:20:36', '2021-08-07 11:20:36'),
(86, 41, 'sharepoint', 'global', '2021-08-08 12:50:34', '2021-08-08 12:50:34'),
(87, 41, 'sharepoint-restore', 'global', '2021-08-08 12:50:35', '2021-08-08 12:50:35'),
(88, 42, 'teams', 'global', '2021-08-10 10:31:23', '2021-08-10 10:31:23'),
(89, 42, 'teams-restore', 'global', '2021-08-10 10:31:23', '2021-08-10 10:31:23');

-- --------------------------------------------------------

--
-- Table structure for table `azure_resource_group`
--

DROP TABLE IF EXISTS `azure_resource_group`;
CREATE TABLE IF NOT EXISTS `azure_resource_group` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `organization_id` (`organization_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `azure_resource_group`
--

INSERT INTO `azure_resource_group` (`id`, `organization_id`, `name`, `location`, `created_at`, `updated_at`) VALUES
(1, 1, 'CO365Backup_RG', 'northeurope', '2021-05-17 10:04:44', '2021-05-17 10:04:44'),
(6, 19, 'CO365Backup_RG', 'northeurope', '2021-06-05 11:26:06', '2021-06-05 11:26:06'),
(7, 22, 'CO365Backup_RG', 'northeurope', '2021-06-23 17:36:09', '2021-06-23 17:36:09'),
(8, 23, 'CO365Backup_RG', 'northeurope', '2021-06-27 12:31:48', '2021-06-27 12:31:48');

-- --------------------------------------------------------

--
-- Table structure for table `azure_storage_account`
--

DROP TABLE IF EXISTS `azure_storage_account`;
CREATE TABLE IF NOT EXISTS `azure_storage_account` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `azure_resource_group_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kind` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key_1` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `key_2` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `azure_resource_group_id` (`azure_resource_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `azure_storage_account`
--

INSERT INTO `azure_storage_account` (`id`, `azure_resource_group_id`, `name`, `kind`, `location`, `key_1`, `key_2`, `created_at`, `updated_at`) VALUES
(1, 1, 'co365lamayouzn01', 'StorageV2', 'northeurope', '1WwRi2DOYHNIpDvyoQftkS1USIzGspXOegOG8olb6vrXUeScyIFO7iDG9b2quSAuEhyzOMDK0A02brPUATOmZQ==', 'dCp9WnLAUULNDbNszUxDLpJaKQtUm+2l/CVQmzUgrp6KnSOL41AFiRxgzgwn0lzqoHVIV3+kID4C2D1cNruJeg==', '2021-05-17 10:05:43', '2021-05-17 10:05:43'),
(2, 1, 'co365lamayouzn02', 'StorageV2', 'northeurope', '+l+DKdxCci2BXUj+To4WVKNvPaBtcXjaDgEk93C2sO1EDNdsMal5Rc2GtD5yY7n+2Oj6pCfYYoyF4CW2eFc8gA==', 'Q3ZjRJouTm6z/Uhu+rMO1E9tIpnZHsisxaEv8nO2k1JaN0Rpf/vfoXFgYC0atsnhKmD9h3N5+KoDv+eM+5Gnug==', '2021-05-17 10:06:38', '2021-05-17 10:06:38'),
(3, 1, 'co365lamayouzn03', 'StorageV2', 'northeurope', '1B754n1Yg9TnQYLjgldtPGuAFs07w+c/Qm9q82+jgBWLoNWxdl44cG5to00OjSw8pa3+pvJNhRKKgHDgyQ5WrA==', '7FMltRGJNUMv5UgVzR3oDiDNDCXSCtDVmkJtWX+uQvty86x8rgSJ30cCsbKkOiTCfYyk+HQPn7z4SzXHL78KzA==', '2021-05-17 10:06:42', '2021-05-17 10:06:42'),
(4, 1, 'co365lamayouzn04', 'StorageV2', 'northeurope', 'vq2twKK9NPDOVIazQkb53RrzQdFmPdciB9/LcGt/N0XO4Ag+JrspZ4yK2nGjc6nYlE+o3YS8hJgG6f16iZfzjw==', 'HYRsYgVGoxZNylIGYWF93fvZnZvREQ6ErXhFLW+oc2Ud79gLz82zuhPG6kuQCbqiSzlSZU7C3K1mWemP23bECw==', '2021-05-17 10:06:46', '2021-05-17 10:06:46'),
(5, 1, 'co365lamayouzn05', 'StorageV2', 'northeurope', 'fjoRhsu21He428IwdE2wzRgOfKc+laAWJyEcFExDeeXU3I2fbINLAX7g6fSNzzjRVf8bX0FMVRhRz2CGnYWY8Q==', 'g4uRxM1NMTL8UnWiFCwogOwezHJaH1LgQb8DrKbdsirvkabp89FdK5xsBjPsUM9EfPa2gJEBxQl1PsId8UT63g==', '2021-05-17 10:06:50', '2021-05-17 10:06:50'),
(6, 1, 'co365lamayouzn06', 'StorageV2', 'northeurope', '0+1D2E1WoX0xFw5ti93VT6vpmK1OTucEjGmcxeU+AwFzg8ODUdgP5kkyL9DmgqYtE4IUxpuAREnQdYv3IUEpMw==', 'zf3g2uc85uhu5Xt6QEzTL3gPiBg3GhHBjFbd5o7MIGQ++jQkfRZCIRhmNMO6twWh3BlW6hqkW0kD56KlluWSQw==', '2021-05-17 10:06:54', '2021-05-17 10:06:54'),
(7, 1, 'co365lamayouzn07', 'StorageV2', 'northeurope', 'AEPttribtIF6wn8p1MYGpIiqI5/IqYUR8ERFYDkZdqRZRFJMIHEBGux0B2350JZUhy/Ar4R/tzdLPNso9VS1kA==', 'Kra/XXxw2GuJMBwbi/A1dY/1elDAw38oq8ueedsGopkEJFCyNCrdo6ZcJv2ezp/vYzZxMQvZ4LH1TEjO4q3tPA==', '2021-05-17 10:06:59', '2021-05-17 10:06:59'),
(8, 1, 'co365lamayouzn08', 'StorageV2', 'northeurope', 'HCCqWW3SO4k+BEEHCjZCDdPRn0Y9ZEHBmwsHfn7rIkA5bXqL/Z4/5r4MH2XFY4cqOWYw6n+DXpniSpqaDDImcw==', 'ZoVVkj1L7fpDHRLMROuAZJHuQcHNZ1a6XCIGvNPnYXWLI0LFU94WrNu0D2dJFCFjyhoQ+P9jc5ekgWc9hpwHJg==', '2021-05-17 10:07:03', '2021-05-17 10:07:03'),
(9, 1, 'co365lamayouzn09', 'StorageV2', 'northeurope', '8V9l78+oSs2zsuHxDRls7ceddufRwloC7TpJ8ErVU3Pn/9WhajIXsqz2dGY+nXKglfKs8uaOA1ik/+unZ0P9Tg==', 'kuGrVrtN8ivmsJ3v0ky28ohjG5Namcu+2BPqRqj6WxO3B2JRQXzvQTYfpjs5DdHSMR1yqdG9A2Hdc2SrPqZnZA==', '2021-05-17 10:07:06', '2021-05-17 10:07:06'),
(10, 1, 'co365lamayouzn17', 'StorageV2', 'northeurope', 'OY0sUJX39SMVFvaO8oYyUa3s7LqnknKli8UKGb3rZl5mttzNQUaV16gur//J7zBL7cHHnyVAYD6qyKlKmLzlOA==', 'trQ8ksmODQ4hlh/ut+gqgQFK9ErMNc4lm6ueSYfl8bUFWKcfZOqAO9VuWFfNnHi2QfXVaCzv0QFzwa6OvwYEOQ==', '2021-05-17 10:07:11', '2021-05-17 10:07:11'),
(11, 1, 'co365lamayouzn18', 'StorageV2', 'northeurope', 'KdQpQgoDqz1Y5gAZa7yEd9ErqVnxFjdjNGVvv5PRSdxGVigutEDi6vRZ+D8V8YzfcmPl+c3zgoOp9/JrIkqTXg==', 'ywGVTibRO75P64uodoFdxu4csSHm/4AxFBEIKFH9TLvA1TFXky4o9o9x6jkpZ/oTSSizHDy+DSYFy8hGtm8Bqg==', '2021-05-17 10:07:15', '2021-05-17 10:07:15'),
(12, 1, 'co365lamayouzn40', 'StorageV2', 'northeurope', 'cSKnifKpjFTIDgKco188+qShclIRnI31VAieWzWYQK+aBN9zLgvIqXud+Tsbg+3Uxjkgf9vdQJegxZ4ub3ifeQ==', '5Er8HuhfWf6YV706Msq13s0lSZPLC+5gQLAQB/zhiYf2Y8G9diulsk2Q+uauKnNRUUQJpkUVD01OIN3ehHkrXA==', '2021-05-17 10:07:18', '2021-05-17 10:07:18'),
(13, 1, 'co365lamayouzn50', 'StorageV2', 'northeurope', 'N2rpmokAu43N6jPpgB5SZozvCb1tULv+oDM9oVtad/Iw/y4CSlIouOhEaraA8M/J5ewFU2OF4C0f2V7Au1wKaw==', 'D9LRHSEJbGZYlTD5YHh2LPgs4DUpWT8aFgkHRgoaeVtGaQO3FFbgohqFXaG5G/KVj3YVxT6EM8zpznC+bQAFCA==', '2021-05-17 10:07:22', '2021-05-17 10:07:22'),
(14, 1, 'co365lamayouzn51', 'StorageV2', 'northeurope', 'd55X8OgVNKkSWoe/VKgBfiH/S0KalVoZjv7tP5v3JBGwCWa31geHYAHmp99tXm8sNjhu5d+16IlOOTt8rxkyAA==', '3DlrJDEy72CnlWkGsXZ7CHw77eaOZ8wbMIdehhKSxbx/8/x2rMXRfqGNl+zZ1n+x+HC6789MazII8KfaW0Tx0g==', '2021-05-17 10:07:26', '2021-05-17 10:07:26'),
(15, 1, 'co365lamayouzn52', 'StorageV2', 'northeurope', 'W0754KKCIGncJiMP9rE23gWFkbWvpaZVr6htt/wLG7Efn6qEcVlPICUs/P5TpejCmPNiXdDcKtEhPCNdjYffXQ==', 'afCxFVpUpk8BThoFMlBp+HV6Z8Vu55GUwRy0/2WUrzsW+Xjz4zPoXagsK+07wkxP35rT4mUr9jGkMVEvMTQsrQ==', '2021-05-17 10:07:30', '2021-05-17 10:07:30'),
(16, 1, 'co365lamayouzn53', 'StorageV2', 'northeurope', 'DdYX54M0j6glbDygGSWDcRendYZoSxBBd1RpFoQ1fcYE+KhngO/frOLSMC7fLDZK7jYlMikCW8bRS+k4z/lk3A==', '4wrnJhAF6yJNbBY3LdsNlXmMK+HUValJjdpl8Y4penP4YP6urocEn6+gRolHzU8DncMKMy4rY4isE9IDjmrJBw==', '2021-05-17 10:07:34', '2021-05-17 10:07:34'),
(17, 1, 'co365lamayouzn54', 'StorageV2', 'northeurope', '2fJTTl2cH0+HMDM6j3FUZE4oeqTVVuwYPm71vvwvj1xEWJFW4dx+9Tm6QyeA5c1omJ2gqhS305pE5Yv5OlX5Pg==', 'qNlsJMvP8ih26iq/Y9NgfYsHR6wPp/90NR9LAt/1yz70/bPV3NSLI68kFO2/pBlYJ6lOGOmlZtYTgW7h5I3QyQ==', '2021-05-17 10:07:38', '2021-05-17 10:07:38'),
(18, 1, 'co365lamayouzn63', 'StorageV2', 'northeurope', 'hRAn6AnWycAOBoJJkXD33d8HkmI5wdh6oW5seBgUULexFvv0XEfQtQ3n2GcocQ9fYBZ+/mVYNubK2JhgaNiqZA==', 'OQAdkwXV1Z9cXPp8wO8SWAzyUeGXymMh0BpJdxd5cU6t95d092mJAkMcMJe0I+CGWRsas5Se0vf5/kkOVWufyQ==', '2021-05-17 10:07:42', '2021-05-17 10:07:42'),
(19, 1, 'co365lamayouzn64', 'StorageV2', 'northeurope', 'AQC8iv3C+LBsGY95zB9VDOO4IN00iLOqeihojnaOeKl4jrmQMg6wP3AFFuxxEuxEhmKBTz7RRJV+3k/lg0Pumw==', 'bRdrcTtHfrrXIdZN+LmB/ny2JUgFig2NZrkqkHnQcMTUvz1qfxUEHbEcM/3We7wKD9y8gER9riB6RIIoZg4WJA==', '2021-05-17 10:07:46', '2021-05-17 10:07:46'),
(20, 1, 'co365lamayouzn65', 'StorageV2', 'northeurope', 'XRVY9RAkorj+IJDdbf4X2GzMy1dBEP0ELe6NMZd2a7IipJZHXw1xr5ubIX8bKc6HNznl67bFDLTHvPLV6XaCHg==', 'Hq4fdEERQ6cPeeQdO4sdfbm1lAKfl69QY0Qyh2UYr7efo7M7gG0k4VaayJYF0v25MbURXQWrGJABxJlgSrPIGQ==', '2021-05-17 10:07:49', '2021-05-17 10:07:49'),
(21, 1, 'co365lamayouzn66', 'StorageV2', 'northeurope', 'o6Fn9gCSyBRmxGgOBg9W5mbte5rcBbLLkbsiQ70A7kqFazLwtpCZQMFwJMElhMI5ZcqK+NicPQizVkTQpaR5Rg==', 'aihFz/H2IjMy70PPD2UNswxU5apHoL/s5oba63LJaw+wyFlSUg/pEzrwDOffTiayH01mwXQafMDP4IEDNctOVQ==', '2021-05-17 10:07:53', '2021-05-17 10:07:53'),
(28, 8, 'co365yakuttest123n01', 'StorageV2', 'northeurope', 'Oz/mt05OyI04UA5XXf+xssgu6SjBy39Ayoxif45ueoa0wrejU308H/6x6mfc9AB2gSG26Y+l43CNE3UR9QvRpA==', 'HUA+3T6TqUd1s7VHydl5Zawm+4SJZLk2TYfX4jZLg9nDwP5OMm87eH5XJRZpCFKiPjqnvKecRH43sza3CcOweQ==', '2021-06-27 12:46:14', '2021-06-27 12:46:14'),
(29, 8, 'co365yakuttest123n02', 'StorageV2', 'northeurope', '3Q58RX1IqdUlC0IHV0laGxENQUcks53UkVA9mXwXz/5WvhuS2HZBDLA1GaOtk1ol4ZaJV1tJjpxbxu7wP3QWoQ==', '7D0fV4O5KtmamjcJESP8PMA45sASSjoWSbDg3QBBMXoOMZl3tyeXUx4hVSwteTecDluqWYxmGo/NULiJhrXAdw==', '2021-06-27 13:06:07', '2021-06-27 13:06:07'),
(30, 8, 'co365yakuttest123n03', 'StorageV2', 'northeurope', 'eudcL7ToVPSnw70Iue6jD2EU7fVRTd3QMcbc+z4Xjvtz17Z8FagkWLLcyUXZQizCX4Wa5AOhblJO9t6xakKUug==', 'FvNP59SQYhwPMxFAgylfQD7OfR7lrvBmnuxRcRSafVxXJ5d5YZ4yCSPwCkL+5eACKU3BJ3LXduYKRPzjB8JuPA==', '2021-06-27 13:21:52', '2021-06-27 13:21:52'),
(31, 8, 'co365yakuttest123n04', 'StorageV2', 'northeurope', 'nt7Dxx0NevsVrNgUKRcnrm4Dm3eT4Fj7B0kWGNt3wUiF4kRXdLmw4/G1dgxFbZBubBcCt3slVUuFpEhFwfH6Uw==', 'FzO50tFZRqom0zXi7g8VJAajkcH15pckqbZzgE8U9+Oy1Q6ToZR58bymErF4kwsQkvJUG94+KVxLpgtB5P965Q==', '2021-06-28 13:26:58', '2021-06-28 13:26:58'),
(32, 1, 'co365lamayouzn22', 'StorageV2', 'northeurope', '6yj6Q+1GzxAwnmd8J61+F6mWgUx8Fq3QjXTgZE7LIFDtSTPhlsgZx5jd+bddAFBw8r1RNEmnNTj73zUhKrFqXg==', 'BKJFySy4WBNudJKn2/s6I0uLeRV4dK2hsRdR9IlT0ct5J22piMtbedhEGe6KFVH2A0PClIt+wQK47aeUsjBCqA==', '2021-06-28 14:51:50', '2021-06-28 14:51:50'),
(33, 1, 'co365lamayouzn23', 'StorageV2', 'northeurope', 'jA39FpNhs5COL5035TxoA97A5vMJj5MlH5bZmfMHnJaTeZDOM/Gr5DgV2gdj/kdp7euwzhlrFWxfDtuqB/lZxw==', 'zfBkGtZrM8tRp0kJdfDIRek+xQ+EbSniyJhbfCjXSxnQL1dXT51TDMtVl2AFZnt46WlxyWzA+OGZG0BL7Cjbtg==', '2021-06-28 15:53:13', '2021-06-28 15:53:13'),
(34, 1, 'co365lamayouzn24', 'StorageV2', 'northeurope', 'kRTaD/kfim4xZLVtnfwORnJgFYCNqGe0PwYx5AzCdOo8gjBYww2jVbKWg3gsiZY3EDxPD8csfmYj8ml7YWzPbg==', 'bEYoO7wj2MdJKKLIqS7Ol5lHnAsd+/aFD7YqvyGYJb9+kjMopfV1mmt6B3osFEXeXXyI82EEY+TiWgtF7qwWPg==', '2021-06-30 12:19:39', '2021-06-30 12:19:39'),
(35, 8, 'co365yakuttest123n05', 'StorageV2', 'northeurope', 'D+FKMMU1kf2VvO9w7w0zlAx2XgQOCsUtM/itNSY2SldLiye9otTElQk07eiTsWaa4he02ouOUYWZZZgQJjeXfg==', 'PzcIf7YIXLfrrZ6GYLjOnlOQFUc6N45dEfH5ScrcWgKEZoP9EAmpX5GYoV8z7DilK7EGgcaDea5vbpoGV5WdKg==', '2021-06-30 16:33:09', '2021-06-30 16:33:09'),
(36, 1, 'co365lamayouzn25', 'StorageV2', 'northeurope', 'b25ub1fXc4N9KtAqOgFQ6Ri8U/ci0PYsveSILiatuvcdaxnQLbJX9LN9ycj6NvyUkpV+zkb2nWpMrz/cfeCu0g==', 'oe/XVs3Rw7C2J5vFssJQaEYdgOh/aIz6Rzgb1tMOhtuK8pQi8Z7YelNcZdkT//WnVgcKMdbA6lFcpXhVipJAkg==', '2021-07-28 08:28:50', '2021-07-28 08:28:50'),
(37, 7, 'co365newnewvoilan01', 'StorageV2', 'northeurope', 'BwUaDFf3N3k5uQVG5MBUoZr4jFpjTcTOJsLJarvOIELicMJPfqRvnpS7EEtjFL7/MESIYvTu47rwD6JMWOlEQg==', 'Cye8EnK51yG59WGcowTVy6OmHJS3Z6BpgaXa4nDe0bIh9TbWGU8eM4fSPtnxM+NFaNH8IqZFaHS+zsKmLR0lsA==', '2021-08-02 13:55:42', '2021-08-02 13:55:42'),
(38, 7, 'co365newnewvoilan02', 'StorageV2', 'northeurope', 'kvXFg/buqLP3qbQtuARiPbj7+RpXXv+Fkh+aaVzXDZGUI/TgUopeOwo2ct2ieAvzOTZ1qbOWIiz2yW/a+SLePw==', 'mMBJ9faYo8wKfPxNPIeOQdMotd07e7XX2qK0LOznbJp09/NXo2gLXI5+8OPzp2P/9zjwwdvBl0sIx/PTA5IRWA==', '2021-08-02 13:59:31', '2021-08-02 13:59:31'),
(39, 7, 'co365newnewvoilan03', 'StorageV2', 'northeurope', 'KOCETCympwGGxAW8Iv7CubP7rM8aCPUW3me7HLSd4F6IWAO3SyIZ/tlw4HxGcCw6LXJJhweReGhfg/GqnsSvFA==', 'ZcyUsiVqIbMgy2HgelHRlgj+U7wbeG+4yHE6vGw1G73XlaEcwimO48EkDs5dl4PSo4C/bPpqWLuwhUJsfvVW2g==', '2021-08-07 11:19:05', '2021-08-07 11:19:05'),
(40, 7, 'co365newnewvoilan04', 'StorageV2', 'northeurope', 'mzqzV+QbKwxslOvtQSosIANa7sZEBopJKEy6xoOB5biNqwaXDyTjrwoOgms6vLnoj0dyZyDzo17ufcUnrCtg7w==', 'x/uwxkDM/9kBYK6mHNXPZIyXbzVuCDOWdFHIXNwO3E3p3GQl5D+ziLDhd6RxMKXgYxjP8gdp9l6dVAO8L/4uBA==', '2021-08-07 11:20:34', '2021-08-07 11:20:34'),
(41, 7, 'co365newnewvoilan05', 'StorageV2', 'northeurope', 'mUMD3J5sib9f4kid/3unBV9VGLi0PIpa+AD0CD2+JS4tZsh9DejRGoq/xAmhvVDj+nTDg5iiRSfnHT/Cj4Ss0A==', 'GkZlSXZYZ+gvTL7/nw9+z+LCctP8/ZtSXTFy4tkZ7VqkLvC70rBh+XguP4slRkmggvR8NOz5Hnnk+FTRYn8ONQ==', '2021-08-08 12:50:33', '2021-08-08 12:50:33'),
(42, 7, 'co365newnewvoilan06', 'StorageV2', 'northeurope', '/kXhKUIXaQceMTwBpVkmHnSicrHHSSLW3SenAERwaX9ZrX+ii77/rIGayx8uAP+835gKaBgY+fY//ZMsrZ/Aag==', 'fW//q++89pR2nWgCsGgWJlNVl40LYeEsDoNko6At2uHcbfzFOIchB6jw1a3QdoZJO4x+ElWE7HNPc1BBQr6xPA==', '2021-08-10 10:31:21', '2021-08-10 10:31:21');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(1, 'Saudi Arabia', 'SA', NULL, NULL),
(2, 'Lebanon', 'LB', NULL, NULL),
(3, 'Kuwait', 'KW', NULL, NULL),
(4, 'Jordan', 'JO', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ediscovery_jobs`
--

DROP TABLE IF EXISTS `ediscovery_jobs`;
CREATE TABLE IF NOT EXISTS `ediscovery_jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `kind` text COLLATE utf8_unicode_ci NOT NULL,
  `status` text COLLATE utf8_unicode_ci NOT NULL,
  `backup_job_id` int(10) UNSIGNED NOT NULL,
  `restore_point_type` text COLLATE utf8_unicode_ci,
  `restore_point_time` text COLLATE utf8_unicode_ci NOT NULL,
  `is_restore_point_show_deleted` tinyint(1) NOT NULL,
  `is_restore_point_show_version` tinyint(1) NOT NULL,
  `search_criteria` json DEFAULT NULL,
  `search_data` json DEFAULT NULL,
  `restore_session_guid` text COLLATE utf8_unicode_ci,
  `request_time` datetime DEFAULT NULL,
  `completion_time` datetime DEFAULT NULL,
  `expiration_time` datetime DEFAULT NULL,
  `duration` text COLLATE utf8_unicode_ci,
  `total_items` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `JOB_FK` (`backup_job_id`),
  KEY `USER_FK` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ediscovery_jobs`
--

INSERT INTO `ediscovery_jobs` (`id`, `user_id`, `name`, `kind`, `status`, `backup_job_id`, `restore_point_type`, `restore_point_time`, `is_restore_point_show_deleted`, `is_restore_point_show_version`, `search_criteria`, `search_data`, `restore_session_guid`, `request_time`, `completion_time`, `expiration_time`, `duration`, `total_items`, `created_at`, `updated_at`) VALUES
(1, 231, 'fgffg', 'teams', 'Success', 27, 'single', '2021-08-10T10:37:26.6843310Z', 0, 0, '[{\"field\": \"Message\", \"value\": \"test8iiiiii\", \"category\": \"Posts Fields\", \"condition\": \"doesn\'t contain\"}]', '[{\"type\": \"posts\", \"count\": 4, \"email\": \"Communications@M365x210347.onmicrosoft.com\", \"teamId\": \"c0b404a6-6d8f-49a0-99c8-cf4913ec1195\", \"teamName\": \"Communications\", \"channelId\": \"19:97eb04ff0ab74781a86c550624d8d822@thread.tacv2\", \"channelName\": \"General\"}, {\"type\": \"posts\", \"count\": 6, \"email\": \"Communications@M365x210347.onmicrosoft.com\", \"teamId\": \"c0b404a6-6d8f-49a0-99c8-cf4913ec1195\", \"teamName\": \"Communications\", \"channelId\": \"19:76844b2a74714f30a6aace541efe27c9@thread.tacv2\", \"channelName\": \"UI UX copy guidelines\"}, {\"type\": \"posts\", \"count\": 1, \"email\": \"Contosomarketing@M365x210347.onmicrosoft.com\", \"teamId\": \"c53900a7-7531-44c9-8224-f2f68a0ebb58\", \"teamName\": \"Contoso marketing\", \"channelId\": \"-1\", \"channelName\": null}, {\"type\": \"posts\", \"count\": 28, \"email\": \"Design@M365x210347.onmicrosoft.com\", \"teamId\": \"c9183a7a-f44c-4706-ba56-c80b9205e40c\", \"teamName\": \"Design\", \"channelId\": \"-1\", \"channelName\": null}]', '8b590238-0504-4976-8d9c-f00b87d881c0', '2021-08-10 17:05:07', '2021-08-10 17:05:58', '2021-08-17 17:05:58', '40 seconds', 39, '2021-08-10 17:05:07', '2021-08-10 17:05:58'),
(2, 231, 'fgffg', 'teams', 'Success', 27, 'single', '2021-08-10T10:37:26.6843310Z', 0, 0, '[{\"field\": \"Name\", \"value\": \"testtesttest\", \"category\": \"Files Fields\", \"condition\": \"doesn\'t contain\"}]', '[{\"type\": \"files\", \"count\": 1, \"email\": \"Communications@M365x210347.onmicrosoft.com\", \"teamId\": \"c0b404a6-6d8f-49a0-99c8-cf4913ec1195\", \"teamName\": \"Communications\", \"channelId\": \"19:97eb04ff0ab74781a86c550624d8d822@thread.tacv2\", \"channelName\": \"General\"}, {\"type\": \"files\", \"count\": 1, \"email\": \"Communications@M365x210347.onmicrosoft.com\", \"teamId\": \"c0b404a6-6d8f-49a0-99c8-cf4913ec1195\", \"teamName\": \"Communications\", \"channelId\": \"19:76844b2a74714f30a6aace541efe27c9@thread.tacv2\", \"channelName\": \"UI UX copy guidelines\"}, {\"type\": \"files\", \"count\": 0, \"email\": \"Contosomarketing@M365x210347.onmicrosoft.com\", \"teamId\": \"c53900a7-7531-44c9-8224-f2f68a0ebb58\", \"teamName\": \"Contoso marketing\", \"channelId\": \"-1\", \"channelName\": null}, {\"type\": \"files\", \"count\": 3, \"email\": \"Design@M365x210347.onmicrosoft.com\", \"teamId\": \"c9183a7a-f44c-4706-ba56-c80b9205e40c\", \"teamName\": \"Design\", \"channelId\": \"-1\", \"channelName\": null}]', '550f5e61-fffe-40eb-a524-cb2c4222ab39', '2021-08-10 17:58:11', '2021-08-10 17:58:29', '2021-08-17 17:58:29', '10 seconds', 5, '2021-08-10 17:58:11', '2021-08-10 17:58:29'),
(3, 231, 'fgffg', 'teams', 'Success', 27, 'single', '2021-08-10T10:37:26.6843310Z', 0, 0, '[{\"field\": \"Subject\", \"value\": \"ssaasadasdasd\", \"category\": \"Posts Fields\", \"condition\": \"doesn\'t contain\"}]', '[{\"type\": \"posts\", \"count\": 4, \"email\": \"Communications@M365x210347.onmicrosoft.com\", \"teamId\": \"c0b404a6-6d8f-49a0-99c8-cf4913ec1195\", \"teamName\": \"Communications\", \"channelId\": \"19:97eb04ff0ab74781a86c550624d8d822@thread.tacv2\", \"channelName\": \"General\"}, {\"type\": \"posts\", \"count\": 6, \"email\": \"Communications@M365x210347.onmicrosoft.com\", \"teamId\": \"c0b404a6-6d8f-49a0-99c8-cf4913ec1195\", \"teamName\": \"Communications\", \"channelId\": \"19:76844b2a74714f30a6aace541efe27c9@thread.tacv2\", \"channelName\": \"UI UX copy guidelines\"}, {\"type\": \"posts\", \"count\": 1, \"email\": \"Contosomarketing@M365x210347.onmicrosoft.com\", \"teamId\": \"c53900a7-7531-44c9-8224-f2f68a0ebb58\", \"teamName\": \"Contoso marketing\", \"channelId\": \"-1\", \"channelName\": null}, {\"type\": \"posts\", \"count\": 28, \"email\": \"Design@M365x210347.onmicrosoft.com\", \"teamId\": \"c9183a7a-f44c-4706-ba56-c80b9205e40c\", \"teamName\": \"Design\", \"channelId\": \"-1\", \"channelName\": null}]', '6d157357-b169-48a1-ae2d-0c220a3e3748', '2021-08-10 18:12:40', '2021-08-10 18:12:59', '2021-08-17 18:12:59', '10 seconds', 39, '2021-08-10 18:12:40', '2021-08-10 18:12:59');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `failed_jobs`
--

INSERT INTO `failed_jobs` (`id`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(1, 'database', 'default', '{\"uuid\":\"22ef082a-8b87-4ab3-8f65-61bb3c509c70\",\"displayName\":\"App\\\\Jobs\\\\RestoreOnedriveBackground\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\RestoreOnedriveBackground\",\"command\":\"O:34:\\\"App\\\\Jobs\\\\RestoreOnedriveBackground\\\":14:{s:6:\\\"userId\\\";i:231;s:9:\\\"historyId\\\";i:7;s:11:\\\"sessionData\\\";a:4:{s:4:\\\"time\\\";s:16:\\\"2021-08-07 13:09\\\";s:11:\\\"showDeleted\\\";s:5:\\\"false\\\";s:12:\\\"showVersions\\\";s:5:\\\"false\\\";s:5:\\\"orgId\\\";s:36:\\\"dc3342d4-b8ab-4060-81c4-daa428e9adbc\\\";}s:12:\\\"onedriveData\\\";a:2:{s:8:\\\"username\\\";s:33:\\\"admin@M365x210347.onmicrosoft.com\\\";s:8:\\\"password\\\";s:10:\\\"0gz8Q36crH\\\";}s:12:\\\"functionName\\\";s:31:\\\"restoreOnedriveDocumentOriginal\\\";s:13:\\\"_managerVeeam\\\";O:29:\\\"App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\\":4:{s:42:\\\"\\u0000App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\u0000_urlManager\\\";O:26:\\\"App\\\\Engine\\\\Veeam\\\\UrlsVeeam\\\":0:{}s:38:\\\"\\u0000App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\u0000_caller\\\";O:25:\\\"App\\\\Engine\\\\Base\\\\CallerApi\\\":0:{}s:9:\\\"_username\\\";s:22:\\\"Veeam365-VM\\\\veeamadmin\\\";s:9:\\\"_password\\\";s:13:\\\"V##@M@word123\\\";}s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 'Exception: Error While Restoring Onedrive Documnets To Original in D:\\wamp64\\www\\Portal\\ctelecoms-portal\\app\\Jobs\\RestoreOnedriveBackground.php:291\nStack trace:\n#0 [internal function]: App\\Jobs\\RestoreOnedriveBackground->restoreOnedriveDocumentOriginal()\n#1 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\app\\Jobs\\RestoreOnedriveBackground.php(50): call_user_func(Array)\n#2 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): App\\Jobs\\RestoreOnedriveBackground->handle()\n#3 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(37): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#4 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#5 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#6 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(596): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#7 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)\n#8 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\RestoreOnedriveBackground))\n#9 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\RestoreOnedriveBackground))\n#10 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#11 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(83): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\RestoreOnedriveBackground), false)\n#12 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(128): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\RestoreOnedriveBackground))\n#13 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\RestoreOnedriveBackground))\n#14 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(85): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#15 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(59): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\RestoreOnedriveBackground))\n#16 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(98): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#17 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(356): Illuminate\\Queue\\Jobs\\Job->fire()\n#18 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(306): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#19 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(265): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#20 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(112): Illuminate\\Queue\\Worker->runNextJob(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#21 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#22 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#23 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(37): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#24 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#25 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#26 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(596): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#27 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(134): Illuminate\\Container\\Container->call(Array)\n#28 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#29 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(121): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#30 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(971): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#31 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(290): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#32 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(166): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#33 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(93): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#34 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(129): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#35 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#36 {main}', '2021-08-08 10:27:37'),
(2, 'database', 'default', '{\"uuid\":\"ff81d1ec-cdc4-4509-81d8-ef5cadb72a20\",\"displayName\":\"App\\\\Jobs\\\\RestoreOnedriveBackground\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\RestoreOnedriveBackground\",\"command\":\"O:34:\\\"App\\\\Jobs\\\\RestoreOnedriveBackground\\\":14:{s:6:\\\"userId\\\";i:231;s:9:\\\"historyId\\\";i:8;s:11:\\\"sessionData\\\";a:4:{s:4:\\\"time\\\";s:16:\\\"2021-08-07 13:09\\\";s:11:\\\"showDeleted\\\";s:5:\\\"false\\\";s:12:\\\"showVersions\\\";s:5:\\\"false\\\";s:5:\\\"orgId\\\";s:36:\\\"dc3342d4-b8ab-4060-81c4-daa428e9adbc\\\";}s:12:\\\"onedriveData\\\";a:2:{s:8:\\\"username\\\";s:33:\\\"admin@M365x210347.onmicrosoft.com\\\";s:8:\\\"password\\\";s:10:\\\"0gz8Q36crH\\\";}s:12:\\\"functionName\\\";s:31:\\\"restoreOnedriveDocumentOriginal\\\";s:13:\\\"_managerVeeam\\\";O:29:\\\"App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\\":4:{s:42:\\\"\\u0000App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\u0000_urlManager\\\";O:26:\\\"App\\\\Engine\\\\Veeam\\\\UrlsVeeam\\\":0:{}s:38:\\\"\\u0000App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\u0000_caller\\\";O:25:\\\"App\\\\Engine\\\\Base\\\\CallerApi\\\":0:{}s:9:\\\"_username\\\";s:22:\\\"Veeam365-VM\\\\veeamadmin\\\";s:9:\\\"_password\\\";s:13:\\\"V##@M@word123\\\";}s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 'Exception: Error While Restoring Onedrive Documnets To Original in D:\\wamp64\\www\\Portal\\ctelecoms-portal\\app\\Jobs\\RestoreOnedriveBackground.php:291\nStack trace:\n#0 [internal function]: App\\Jobs\\RestoreOnedriveBackground->restoreOnedriveDocumentOriginal()\n#1 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\app\\Jobs\\RestoreOnedriveBackground.php(50): call_user_func(Array)\n#2 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): App\\Jobs\\RestoreOnedriveBackground->handle()\n#3 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(37): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#4 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#5 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#6 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(596): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#7 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)\n#8 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\RestoreOnedriveBackground))\n#9 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\RestoreOnedriveBackground))\n#10 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#11 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(83): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\RestoreOnedriveBackground), false)\n#12 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(128): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\RestoreOnedriveBackground))\n#13 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\RestoreOnedriveBackground))\n#14 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(85): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#15 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(59): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\RestoreOnedriveBackground))\n#16 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(98): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#17 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(356): Illuminate\\Queue\\Jobs\\Job->fire()\n#18 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(306): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#19 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(265): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#20 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(112): Illuminate\\Queue\\Worker->runNextJob(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#21 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#22 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#23 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(37): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#24 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#25 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#26 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(596): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#27 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(134): Illuminate\\Container\\Container->call(Array)\n#28 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#29 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(121): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#30 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(971): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#31 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(290): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#32 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(166): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#33 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(93): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#34 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(129): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#35 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#36 {main}', '2021-08-08 10:28:20'),
(3, 'database', 'default', '{\"uuid\":\"af078196-27c6-4e62-9f39-ef83d2c4d599\",\"displayName\":\"App\\\\Jobs\\\\RestoreOnedriveBackground\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\RestoreOnedriveBackground\",\"command\":\"O:34:\\\"App\\\\Jobs\\\\RestoreOnedriveBackground\\\":14:{s:6:\\\"userId\\\";i:231;s:9:\\\"historyId\\\";i:9;s:11:\\\"sessionData\\\";a:4:{s:4:\\\"time\\\";s:16:\\\"2021-08-07 13:09\\\";s:11:\\\"showDeleted\\\";s:5:\\\"false\\\";s:12:\\\"showVersions\\\";s:5:\\\"false\\\";s:5:\\\"orgId\\\";s:36:\\\"dc3342d4-b8ab-4060-81c4-daa428e9adbc\\\";}s:12:\\\"onedriveData\\\";a:2:{s:8:\\\"username\\\";s:33:\\\"admin@M365x210347.onmicrosoft.com\\\";s:8:\\\"password\\\";s:10:\\\"0gz8Q36crH\\\";}s:12:\\\"functionName\\\";s:31:\\\"restoreOnedriveDocumentOriginal\\\";s:13:\\\"_managerVeeam\\\";O:29:\\\"App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\\":4:{s:42:\\\"\\u0000App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\u0000_urlManager\\\";O:26:\\\"App\\\\Engine\\\\Veeam\\\\UrlsVeeam\\\":0:{}s:38:\\\"\\u0000App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\u0000_caller\\\";O:25:\\\"App\\\\Engine\\\\Base\\\\CallerApi\\\":0:{}s:9:\\\"_username\\\";s:22:\\\"Veeam365-VM\\\\veeamadmin\\\";s:9:\\\"_password\\\";s:13:\\\"V##@M@word123\\\";}s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 'Exception: Error While Restoring Onedrive Documnets To Original in D:\\wamp64\\www\\Portal\\ctelecoms-portal\\app\\Jobs\\RestoreOnedriveBackground.php:291\nStack trace:\n#0 [internal function]: App\\Jobs\\RestoreOnedriveBackground->restoreOnedriveDocumentOriginal()\n#1 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\app\\Jobs\\RestoreOnedriveBackground.php(50): call_user_func(Array)\n#2 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): App\\Jobs\\RestoreOnedriveBackground->handle()\n#3 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(37): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#4 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#5 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#6 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(596): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#7 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)\n#8 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\RestoreOnedriveBackground))\n#9 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\RestoreOnedriveBackground))\n#10 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#11 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(83): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\RestoreOnedriveBackground), false)\n#12 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(128): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\RestoreOnedriveBackground))\n#13 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\RestoreOnedriveBackground))\n#14 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(85): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#15 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(59): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\RestoreOnedriveBackground))\n#16 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(98): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#17 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(356): Illuminate\\Queue\\Jobs\\Job->fire()\n#18 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(306): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#19 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(265): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#20 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(112): Illuminate\\Queue\\Worker->runNextJob(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#21 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#22 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#23 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(37): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#24 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#25 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#26 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(596): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#27 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(134): Illuminate\\Container\\Container->call(Array)\n#28 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#29 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(121): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#30 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(971): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#31 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(290): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#32 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(166): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#33 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(93): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#34 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(129): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#35 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#36 {main}', '2021-08-08 10:35:20'),
(4, 'database', 'default', '{\"uuid\":\"54a4c8c6-fe16-4935-8f4a-04224c3c60a5\",\"displayName\":\"App\\\\Jobs\\\\RestoreOnedriveBackground\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\RestoreOnedriveBackground\",\"command\":\"O:34:\\\"App\\\\Jobs\\\\RestoreOnedriveBackground\\\":14:{s:6:\\\"userId\\\";i:231;s:9:\\\"historyId\\\";i:10;s:11:\\\"sessionData\\\";a:4:{s:4:\\\"time\\\";s:16:\\\"2021-08-07 13:09\\\";s:11:\\\"showDeleted\\\";s:5:\\\"false\\\";s:12:\\\"showVersions\\\";s:5:\\\"false\\\";s:5:\\\"orgId\\\";s:36:\\\"dc3342d4-b8ab-4060-81c4-daa428e9adbc\\\";}s:12:\\\"onedriveData\\\";a:2:{s:8:\\\"username\\\";s:33:\\\"admin@M365x210347.onmicrosoft.com\\\";s:8:\\\"password\\\";s:10:\\\"0gz8Q36crH\\\";}s:12:\\\"functionName\\\";s:31:\\\"restoreOnedriveDocumentOriginal\\\";s:13:\\\"_managerVeeam\\\";O:29:\\\"App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\\":4:{s:42:\\\"\\u0000App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\u0000_urlManager\\\";O:26:\\\"App\\\\Engine\\\\Veeam\\\\UrlsVeeam\\\":0:{}s:38:\\\"\\u0000App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\u0000_caller\\\";O:25:\\\"App\\\\Engine\\\\Base\\\\CallerApi\\\":0:{}s:9:\\\"_username\\\";s:22:\\\"Veeam365-VM\\\\veeamadmin\\\";s:9:\\\"_password\\\";s:13:\\\"V##@M@word123\\\";}s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 'Exception: Error While Restoring Onedrive Documnets To Original in D:\\wamp64\\www\\Portal\\ctelecoms-portal\\app\\Jobs\\RestoreOnedriveBackground.php:291\nStack trace:\n#0 [internal function]: App\\Jobs\\RestoreOnedriveBackground->restoreOnedriveDocumentOriginal()\n#1 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\app\\Jobs\\RestoreOnedriveBackground.php(50): call_user_func(Array)\n#2 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): App\\Jobs\\RestoreOnedriveBackground->handle()\n#3 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(37): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#4 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#5 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#6 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(596): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#7 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)\n#8 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\RestoreOnedriveBackground))\n#9 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\RestoreOnedriveBackground))\n#10 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#11 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(83): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\RestoreOnedriveBackground), false)\n#12 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(128): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\RestoreOnedriveBackground))\n#13 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\RestoreOnedriveBackground))\n#14 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(85): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#15 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(59): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\RestoreOnedriveBackground))\n#16 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(98): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#17 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(356): Illuminate\\Queue\\Jobs\\Job->fire()\n#18 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(306): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#19 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(265): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#20 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(112): Illuminate\\Queue\\Worker->runNextJob(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#21 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#22 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#23 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(37): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#24 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#25 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#26 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(596): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#27 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(134): Illuminate\\Container\\Container->call(Array)\n#28 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#29 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(121): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#30 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(971): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#31 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(290): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#32 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(166): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#33 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(93): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#34 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(129): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#35 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#36 {main}', '2021-08-08 10:35:52'),
(5, 'database', 'default', '{\"uuid\":\"dc7683f0-ef21-403c-bcbd-66d499f2d491\",\"displayName\":\"App\\\\Jobs\\\\RestoreOnedriveBackground\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\RestoreOnedriveBackground\",\"command\":\"O:34:\\\"App\\\\Jobs\\\\RestoreOnedriveBackground\\\":14:{s:6:\\\"userId\\\";i:231;s:9:\\\"historyId\\\";i:11;s:11:\\\"sessionData\\\";a:4:{s:4:\\\"time\\\";s:16:\\\"2021-08-07 13:09\\\";s:11:\\\"showDeleted\\\";s:5:\\\"false\\\";s:12:\\\"showVersions\\\";s:5:\\\"false\\\";s:5:\\\"orgId\\\";s:36:\\\"dc3342d4-b8ab-4060-81c4-daa428e9adbc\\\";}s:12:\\\"onedriveData\\\";a:2:{s:8:\\\"username\\\";s:33:\\\"admin@M365x210347.onmicrosoft.com\\\";s:8:\\\"password\\\";s:10:\\\"0gz8Q36crH\\\";}s:12:\\\"functionName\\\";s:31:\\\"restoreOnedriveDocumentOriginal\\\";s:13:\\\"_managerVeeam\\\";O:29:\\\"App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\\":4:{s:42:\\\"\\u0000App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\u0000_urlManager\\\";O:26:\\\"App\\\\Engine\\\\Veeam\\\\UrlsVeeam\\\":0:{}s:38:\\\"\\u0000App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\u0000_caller\\\";O:25:\\\"App\\\\Engine\\\\Base\\\\CallerApi\\\":0:{}s:9:\\\"_username\\\";s:22:\\\"Veeam365-VM\\\\veeamadmin\\\";s:9:\\\"_password\\\";s:13:\\\"V##@M@word123\\\";}s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 'Exception: Error While Restoring Onedrive Documnets To Original in D:\\wamp64\\www\\Portal\\ctelecoms-portal\\app\\Jobs\\RestoreOnedriveBackground.php:291\nStack trace:\n#0 [internal function]: App\\Jobs\\RestoreOnedriveBackground->restoreOnedriveDocumentOriginal()\n#1 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\app\\Jobs\\RestoreOnedriveBackground.php(50): call_user_func(Array)\n#2 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): App\\Jobs\\RestoreOnedriveBackground->handle()\n#3 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(37): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#4 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#5 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#6 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(596): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#7 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)\n#8 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\RestoreOnedriveBackground))\n#9 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\RestoreOnedriveBackground))\n#10 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#11 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(83): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\RestoreOnedriveBackground), false)\n#12 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(128): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\RestoreOnedriveBackground))\n#13 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\RestoreOnedriveBackground))\n#14 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(85): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#15 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(59): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\RestoreOnedriveBackground))\n#16 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(98): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#17 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(356): Illuminate\\Queue\\Jobs\\Job->fire()\n#18 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(306): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#19 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(265): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#20 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(112): Illuminate\\Queue\\Worker->runNextJob(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#21 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#22 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#23 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(37): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#24 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#25 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#26 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(596): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#27 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(134): Illuminate\\Container\\Container->call(Array)\n#28 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#29 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(121): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#30 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(971): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#31 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(290): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#32 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(166): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#33 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(93): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#34 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(129): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#35 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#36 {main}', '2021-08-08 10:36:30');
INSERT INTO `failed_jobs` (`id`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(6, 'database', 'default', '{\"uuid\":\"7ae78df3-8a5b-480f-832c-7c2ff2e82438\",\"displayName\":\"App\\\\Jobs\\\\RestoreOnedriveBackground\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\RestoreOnedriveBackground\",\"command\":\"O:34:\\\"App\\\\Jobs\\\\RestoreOnedriveBackground\\\":14:{s:6:\\\"userId\\\";i:231;s:9:\\\"historyId\\\";i:12;s:11:\\\"sessionData\\\";a:4:{s:4:\\\"time\\\";s:16:\\\"2021-08-07 13:09\\\";s:11:\\\"showDeleted\\\";s:5:\\\"false\\\";s:12:\\\"showVersions\\\";s:5:\\\"false\\\";s:5:\\\"orgId\\\";s:36:\\\"dc3342d4-b8ab-4060-81c4-daa428e9adbc\\\";}s:12:\\\"onedriveData\\\";a:2:{s:8:\\\"username\\\";s:33:\\\"admin@M365x210347.onmicrosoft.com\\\";s:8:\\\"password\\\";s:10:\\\"0gz8Q36crH\\\";}s:12:\\\"functionName\\\";s:31:\\\"restoreOnedriveDocumentOriginal\\\";s:13:\\\"_managerVeeam\\\";O:29:\\\"App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\\":4:{s:42:\\\"\\u0000App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\u0000_urlManager\\\";O:26:\\\"App\\\\Engine\\\\Veeam\\\\UrlsVeeam\\\":0:{}s:38:\\\"\\u0000App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\u0000_caller\\\";O:25:\\\"App\\\\Engine\\\\Base\\\\CallerApi\\\":0:{}s:9:\\\"_username\\\";s:22:\\\"Veeam365-VM\\\\veeamadmin\\\";s:9:\\\"_password\\\";s:13:\\\"V##@M@word123\\\";}s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 'Illuminate\\Queue\\MaxAttemptsExceededException: App\\Jobs\\RestoreOnedriveBackground has been attempted too many times or run too long. The job may have previously timed out. in D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php:648\nStack trace:\n#0 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(436): Illuminate\\Queue\\Worker->maxAttemptsExceededException(Object(Illuminate\\Queue\\Jobs\\DatabaseJob))\n#1 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(346): Illuminate\\Queue\\Worker->markJobAsFailedIfAlreadyExceedsMaxAttempts(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), 1)\n#2 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(306): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#3 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(265): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#4 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(112): Illuminate\\Queue\\Worker->runNextJob(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#5 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#6 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#7 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(37): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#8 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#9 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#10 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(596): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#11 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(134): Illuminate\\Container\\Container->call(Array)\n#12 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#13 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(121): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#14 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(971): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#15 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(290): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#16 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(166): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#17 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(93): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#18 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(129): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#19 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#20 {main}', '2021-08-08 10:38:38'),
(7, 'database', 'default', '{\"uuid\":\"416739e1-34f3-4ddf-ac29-fb235384c635\",\"displayName\":\"App\\\\Jobs\\\\RestoreOnedriveBackground\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\RestoreOnedriveBackground\",\"command\":\"O:34:\\\"App\\\\Jobs\\\\RestoreOnedriveBackground\\\":14:{s:6:\\\"userId\\\";i:231;s:9:\\\"historyId\\\";i:20;s:11:\\\"sessionData\\\";a:4:{s:4:\\\"time\\\";s:16:\\\"2021-08-07 13:09\\\";s:11:\\\"showDeleted\\\";s:1:\\\"1\\\";s:12:\\\"showVersions\\\";s:1:\\\"1\\\";s:5:\\\"orgId\\\";s:36:\\\"dc3342d4-b8ab-4060-81c4-daa428e9adbc\\\";}s:12:\\\"onedriveData\\\";a:2:{s:8:\\\"username\\\";s:33:\\\"admin@M365x210347.onmicrosoft.com\\\";s:8:\\\"password\\\";s:10:\\\"0gz8Q36crH\\\";}s:12:\\\"functionName\\\";s:31:\\\"restoreOnedriveDocumentOriginal\\\";s:13:\\\"_managerVeeam\\\";O:29:\\\"App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\\":4:{s:42:\\\"\\u0000App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\u0000_urlManager\\\";O:26:\\\"App\\\\Engine\\\\Veeam\\\\UrlsVeeam\\\":0:{}s:38:\\\"\\u0000App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\u0000_caller\\\";O:25:\\\"App\\\\Engine\\\\Base\\\\CallerApi\\\":0:{}s:9:\\\"_username\\\";s:22:\\\"Veeam365-VM\\\\veeamadmin\\\";s:9:\\\"_password\\\";s:13:\\\"V##@M@word123\\\";}s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 'Exception: Error While Restoring Onedrive Documnets To Original in D:\\wamp64\\www\\Portal\\ctelecoms-portal\\app\\Jobs\\RestoreOnedriveBackground.php:291\nStack trace:\n#0 [internal function]: App\\Jobs\\RestoreOnedriveBackground->restoreOnedriveDocumentOriginal()\n#1 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\app\\Jobs\\RestoreOnedriveBackground.php(50): call_user_func(Array)\n#2 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): App\\Jobs\\RestoreOnedriveBackground->handle()\n#3 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(37): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#4 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#5 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#6 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(596): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#7 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)\n#8 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\RestoreOnedriveBackground))\n#9 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\RestoreOnedriveBackground))\n#10 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#11 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(83): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\RestoreOnedriveBackground), false)\n#12 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(128): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\RestoreOnedriveBackground))\n#13 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\RestoreOnedriveBackground))\n#14 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(85): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#15 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(59): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\RestoreOnedriveBackground))\n#16 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(98): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#17 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(356): Illuminate\\Queue\\Jobs\\Job->fire()\n#18 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(306): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#19 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(265): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#20 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(112): Illuminate\\Queue\\Worker->runNextJob(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#21 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#22 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#23 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(37): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#24 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#25 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#26 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(596): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#27 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(134): Illuminate\\Container\\Container->call(Array)\n#28 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#29 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(121): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#30 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(971): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#31 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(290): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#32 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(166): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#33 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(93): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#34 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(129): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#35 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#36 {main}', '2021-08-08 12:33:44'),
(8, 'database', 'default', '{\"uuid\":\"09733394-4f22-4b3e-9a7a-1c9f9a4136cd\",\"displayName\":\"App\\\\Jobs\\\\EDiscoveryTeamsBackground\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\EDiscoveryTeamsBackground\",\"command\":\"O:33:\\\"App\\\\Jobs\\\\EDiscoveryTeamsBackground\\\":15:{s:6:\\\"userId\\\";i:231;s:15:\\\"ediscoveryJobId\\\";i:45;s:14:\\\"searchQueryArr\\\";a:1:{i:0;s:12:\\\"-body:\\\"aaaa\\\"\\\";}s:12:\\\"managerVeeam\\\";O:29:\\\"App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\\":4:{s:42:\\\"\\u0000App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\u0000_urlManager\\\";O:26:\\\"App\\\\Engine\\\\Veeam\\\\UrlsVeeam\\\":0:{}s:38:\\\"\\u0000App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\u0000_caller\\\";O:25:\\\"App\\\\Engine\\\\Base\\\\CallerApi\\\":0:{}s:9:\\\"_username\\\";s:22:\\\"Veeam365-VM\\\\veeamadmin\\\";s:9:\\\"_password\\\";s:13:\\\"V##@M@word123\\\";}s:12:\\\"managerAzure\\\";O:29:\\\"App\\\\Engine\\\\Azure\\\\ManagerAzure\\\":7:{s:42:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_urlManager\\\";O:26:\\\"App\\\\Engine\\\\Azure\\\\UrlsAzure\\\":0:{}s:40:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_clientId\\\";s:36:\\\"611665ae-cedd-4e5f-b854-b6fff49f32b3\\\";s:38:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_secret\\\";s:34:\\\"Pr~Z7M5_6.4hi9mX.tNKc-trBQ0NVB3c3e\\\";s:42:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_grant_type\\\";s:13:\\\"refresh_token\\\";s:40:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_resource\\\";s:29:\\\"https:\\/\\/management.azure.com\\/\\\";s:38:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_caller\\\";O:25:\\\"App\\\\Engine\\\\Base\\\\CallerApi\\\":0:{}s:45:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_refresh_token\\\";s:1310:\\\"0.AYEANuxCvt-kjkGHRsqC8Zf_465lFmHdzl9OuFS2__SfMrOBAJA.AgABAAAAAAD--DLA3VO7QrddgJg7WevrAgDs_wQA9P_cBsJRAIsSDENqXagtpYFgkyRCKWKnKw3_h_sB1QZFJcsU5cJcb3Fu5hsWPzWLlrUsaamW9z9C4qwh6lnYPpHvQ0qPv8WdexEHzRHfo0RnmBV_Igp3zcv0Br6fzaHSdXJNoRIhdl4bSaKtTKZz40BsY3h1GxJ3TiQ2poP3Oebnpmndy-8gnjIYDYP3RQw4MttEwU7T2yI8bBSaNaFmHSLpzI7jvhFi8Qcy3Rv4SvVusCMOPIzMmT8w374XhtIUPye9w-RML7yHybOE3TMjP1V6PN7KVN16BiZ2F2_txEXPJRaYeO5YBEhIUQEsjsVTNgr2L_UkpYsCmy3wu0hV7cETXlytohzrxVfvfMfbZ2kyFJF4IqRSEnmIKsQcV3VjQVjNfNisrglSMoKtxA0JKkwfnUHu105dct8JfqNLMadW5Pe3bFhEaSdjRyX2XlaeAzSwK1ABWUBAXEEohIVslvoDgry9kAsJq12wivR8uTu1bnbNsMlPn9UyV8h0AREdxzArocsd9uOYqD7Z1zDVv1bzzP8feq2vgdobN11Z6qS8fNyg7xzAGceMDc9LNpeBpuqDN2FpJQ1ZJpMnuCZf9GTKre1uiUjg2E4CVbzdABQw5S5EqI9PLzxEPhhLp_7ZU8bd9mgQUuGSs6lO4dalIBAWMci0uCRGeSyMbDbfL9TUmTuMWd5MXq4oL7ZI2cP9lqduFHQTqtwajdkGinq8v1hMn8drlSuO7reWRjNNoVpUT20UZM52iH69dffpT6NmvMhB1o3niBDjAm7KHAg0VvA9t-3p3qvji7R2F5VIwzxzQRtpdjxqe4U91_tCv7HOCumuVkcgnS98QrPUkcZMx5HIoezTyIVwEJ9lQuoruZ2CNKu7TBMe7eJ9vAeHdbxx0AshW_G52MRErLlfMuOQ-9Bjqnsd2D62vpmmdhm34nS6pC2Hoe7mRYRZlwKFF7kh9yRmn_EKv86MEH44c57ocpql_hEP35dOXN1EF0WiFe3DjHCCQPTubXSYTDR0ScSUPbAKIMm1M-BEGEePDt5cMmvecXhZHkwbqmY4uebKtlZjG02N4Tyonmm5r8XnbU9hB2yGBuNiV27pFQBq4tlB6ps7SiA8-XQVweQehT51ft1iDW2KfVTNZimDBRVhX7xFFisdaxLkQzI49WnvgR4C7Gk5PrPYckoXfWP51u4mY0Q8IKpAcedlYqvpNtc0tn7G\\\";}s:9:\\\"azureData\\\";a:2:{s:11:\\\"accountName\\\";s:19:\\\"co365newnewvoilan06\\\";s:10:\\\"accountKey\\\";s:88:\\\"\\/kXhKUIXaQceMTwBpVkmHnSicrHHSSLW3SenAERwaX9ZrX+ii77\\/rIGayx8uAP+835gKaBgY+fY\\/\\/ZMsrZ\\/Aag==\\\";}s:10:\\\"searchType\\\";s:0:\\\"\\\";s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 'Illuminate\\Queue\\MaxAttemptsExceededException: App\\Jobs\\EDiscoveryTeamsBackground has been attempted too many times or run too long. The job may have previously timed out. in D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php:648\nStack trace:\n#0 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(436): Illuminate\\Queue\\Worker->maxAttemptsExceededException(Object(Illuminate\\Queue\\Jobs\\DatabaseJob))\n#1 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(346): Illuminate\\Queue\\Worker->markJobAsFailedIfAlreadyExceedsMaxAttempts(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), 1)\n#2 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(306): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#3 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(265): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#4 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(112): Illuminate\\Queue\\Worker->runNextJob(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#5 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#6 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#7 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(37): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#8 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#9 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#10 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(596): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#11 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(134): Illuminate\\Container\\Container->call(Array)\n#12 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#13 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(121): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#14 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(971): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#15 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(290): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#16 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(166): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#17 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(93): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#18 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(129): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#19 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#20 {main}', '2021-08-10 13:30:02'),
(9, 'database', 'default', '{\"uuid\":\"bc684835-dd8b-4701-9480-d7ba94c40cbf\",\"displayName\":\"App\\\\Jobs\\\\EDiscoveryTeamsBackground\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\EDiscoveryTeamsBackground\",\"command\":\"O:33:\\\"App\\\\Jobs\\\\EDiscoveryTeamsBackground\\\":15:{s:6:\\\"userId\\\";i:231;s:15:\\\"ediscoveryJobId\\\";i:49;s:14:\\\"searchQueryArr\\\";a:1:{i:0;s:19:\\\"-body:\\\"test8iiiiii\\\"\\\";}s:12:\\\"managerVeeam\\\";O:29:\\\"App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\\":4:{s:42:\\\"\\u0000App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\u0000_urlManager\\\";O:26:\\\"App\\\\Engine\\\\Veeam\\\\UrlsVeeam\\\":0:{}s:38:\\\"\\u0000App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\u0000_caller\\\";O:25:\\\"App\\\\Engine\\\\Base\\\\CallerApi\\\":0:{}s:9:\\\"_username\\\";s:22:\\\"Veeam365-VM\\\\veeamadmin\\\";s:9:\\\"_password\\\";s:13:\\\"V##@M@word123\\\";}s:12:\\\"managerAzure\\\";O:29:\\\"App\\\\Engine\\\\Azure\\\\ManagerAzure\\\":7:{s:42:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_urlManager\\\";O:26:\\\"App\\\\Engine\\\\Azure\\\\UrlsAzure\\\":0:{}s:40:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_clientId\\\";s:36:\\\"611665ae-cedd-4e5f-b854-b6fff49f32b3\\\";s:38:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_secret\\\";s:34:\\\"Pr~Z7M5_6.4hi9mX.tNKc-trBQ0NVB3c3e\\\";s:42:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_grant_type\\\";s:13:\\\"refresh_token\\\";s:40:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_resource\\\";s:29:\\\"https:\\/\\/management.azure.com\\/\\\";s:38:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_caller\\\";O:25:\\\"App\\\\Engine\\\\Base\\\\CallerApi\\\":0:{}s:45:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_refresh_token\\\";s:1310:\\\"0.AYEANuxCvt-kjkGHRsqC8Zf_465lFmHdzl9OuFS2__SfMrOBAJA.AgABAAAAAAD--DLA3VO7QrddgJg7WevrAgDs_wQA9P-VaiSs2iNk_dMVhOYzEtbZZQgmBFjjICPi9TVtQRndOgL9BBFDXVLZMwZQjObFp6XoS8CR7qeVI5MPdB_TboEncFnYpOrn7qOO0nos1F3qGvKgxVAOsD2KDG6dEd6dsGuwaZlIHrs0Hya5NxmTrkGX2fyJGfX6RPFw53JoE-_lVwgUHyAaNaHL0qxaX9OpS53L4k9dylFtJ0M0nFOhVO8wgINtiCzj2NVxqxG_dO1KmWKTM8B7C3Lv76QuT1ZV9QpLakPmf5RznWP8CW-eN4lt-F8UxR4Uxo2pRJ6mqD-tVZEkvWdyD9P8JSZyaJaN31fGjG1HR0jomGzFKfse1rMFSUxc8SKr6RqO4CKVCz2dlBwTNyjiVGoD8NaEfaYeDDU5tbOPLWhIgk6tV4XA4O4n7TWkvbWV0HVdnxuS4fHz49RM8rApgcr-znpkzXOWOxzB5qjFwgN6GtZzM1xyhhgdxs_LT_FeUwyqDkonOdKHeALs--DadAxsSiXS3VvamUWLtTThoWPJPZThtm61u_sPRZNppei5JOUBacq2IonOViisAatLfm6JkwrB0OYBHYIyCsCqMNbGLwH5pQOcRcabg9l2a3_yblm75wVmU8Zrg2n-01AiNLwQvFMJzQvQ8tP0V8dvYBQrHzssVNrcESipmwbviD1sXBUvR4R3u6H6sxz2msVoJFyttwTUOPiEDKO9S2GKoRKfDJV4feFDr-_typaCo3yCGeODbFgUuj8_ocNs9-weUaz6uvFFZrdA91fJpGrJ0IJ7cvRaxi7M1aG519ReZsu6KCogWR3r6entFwjErLrcXipFa-NtvJVSKMArKurQwOnAUUOf_dGPCz-e7P9IJE_WBeP7PebIOx9Kvk3U__qVDW6DTeJ2FYiMQi1GWWALl8hQFN1ozPR2AC1EsXZT2p3RggUGhYpsGov0ALo5BgWl_m5j-fxZnlX4CDugYPhNg3ZP7ztX_QhXLFPi_12Eh2lpCyrFGjxUYi2VEBNKgrTIT0zGWO3bcsImxPHLlFHTJudAqOW4MnYoH_ieHcGBAuwHJGve3OyxVDsvBMCIVueszyqzofCfh3jA_EbXS7kA-Q1hokfkA2-VXzsjpN-8iUcEjidTGeQ6E8xnlOwgQ4I3HwbTuU7lSIqqVqZAqiyCYjtnFLUv2c3hd60IKz-poSmFrKu6rnAKpChbcpo1QLIC7ToT-gySV0r6\\\";}s:9:\\\"azureData\\\";a:2:{s:11:\\\"accountName\\\";s:19:\\\"co365newnewvoilan06\\\";s:10:\\\"accountKey\\\";s:88:\\\"\\/kXhKUIXaQceMTwBpVkmHnSicrHHSSLW3SenAERwaX9ZrX+ii77\\/rIGayx8uAP+835gKaBgY+fY\\/\\/ZMsrZ\\/Aag==\\\";}s:4:\\\"type\\\";s:5:\\\"posts\\\";s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 'Illuminate\\Queue\\MaxAttemptsExceededException: App\\Jobs\\EDiscoveryTeamsBackground has been attempted too many times or run too long. The job may have previously timed out. in D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php:648\nStack trace:\n#0 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(436): Illuminate\\Queue\\Worker->maxAttemptsExceededException(Object(Illuminate\\Queue\\Jobs\\DatabaseJob))\n#1 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(346): Illuminate\\Queue\\Worker->markJobAsFailedIfAlreadyExceedsMaxAttempts(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), 1)\n#2 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(306): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#3 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(265): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#4 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(112): Illuminate\\Queue\\Worker->runNextJob(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#5 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#6 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#7 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(37): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#8 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#9 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#10 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(596): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#11 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(134): Illuminate\\Container\\Container->call(Array)\n#12 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#13 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(121): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#14 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(971): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#15 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(290): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#16 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(166): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#17 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(93): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#18 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(129): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#19 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#20 {main}', '2021-08-10 13:45:10'),
(10, 'database', 'default', '{\"uuid\":\"eeb7ff68-feb1-435e-b02d-3e9b8f0f5032\",\"displayName\":\"App\\\\Jobs\\\\EDiscoveryTeamsBackground\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\EDiscoveryTeamsBackground\",\"command\":\"O:33:\\\"App\\\\Jobs\\\\EDiscoveryTeamsBackground\\\":15:{s:6:\\\"userId\\\";i:231;s:15:\\\"ediscoveryJobId\\\";i:50;s:14:\\\"searchQueryArr\\\";a:1:{i:0;s:19:\\\"-body:\\\"test8iiiiii\\\"\\\";}s:12:\\\"managerVeeam\\\";O:29:\\\"App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\\":4:{s:42:\\\"\\u0000App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\u0000_urlManager\\\";O:26:\\\"App\\\\Engine\\\\Veeam\\\\UrlsVeeam\\\":0:{}s:38:\\\"\\u0000App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\u0000_caller\\\";O:25:\\\"App\\\\Engine\\\\Base\\\\CallerApi\\\":0:{}s:9:\\\"_username\\\";s:22:\\\"Veeam365-VM\\\\veeamadmin\\\";s:9:\\\"_password\\\";s:13:\\\"V##@M@word123\\\";}s:12:\\\"managerAzure\\\";O:29:\\\"App\\\\Engine\\\\Azure\\\\ManagerAzure\\\":7:{s:42:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_urlManager\\\";O:26:\\\"App\\\\Engine\\\\Azure\\\\UrlsAzure\\\":0:{}s:40:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_clientId\\\";s:36:\\\"611665ae-cedd-4e5f-b854-b6fff49f32b3\\\";s:38:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_secret\\\";s:34:\\\"Pr~Z7M5_6.4hi9mX.tNKc-trBQ0NVB3c3e\\\";s:42:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_grant_type\\\";s:13:\\\"refresh_token\\\";s:40:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_resource\\\";s:29:\\\"https:\\/\\/management.azure.com\\/\\\";s:38:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_caller\\\";O:25:\\\"App\\\\Engine\\\\Base\\\\CallerApi\\\":0:{}s:45:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_refresh_token\\\";s:1310:\\\"0.AYEANuxCvt-kjkGHRsqC8Zf_465lFmHdzl9OuFS2__SfMrOBAJA.AgABAAAAAAD--DLA3VO7QrddgJg7WevrAgDs_wQA9P8wLr-CcxpEsTOBZUOG4Kle8xG0zfiGzEWh2VlUq4XSxa2iD7dPtzRC7KEn9wX01rcgnZ1dXwajaL1WfEjaADHwhs_LYjqzJswAUoVDDH5bDaS3VfSJdj_TGpyUITH_3T_XygjVqWcBbqeQh6xCSshZUxeOgSNgz84ho33V8XbKqkmoAKjJwLX7UklostMEGfgvz1aIFjmyYgZXLtDcwKVgsWGSj9InIskWZNALZTlNi9Y6hdwJIRJqS9Rcpxpo6l4FmwmkMOj6NkGEBoPn5oM7fmEO30J_uQaqLOh14wZVR7wu877acHiRAr3zByy18LPgirwJuihqN3osGHM-efEBJu5hQYC2fV28hJuiPnTVX4LoK4DHr4rtBwpD3Zv-e2PWvungE-JfS-IWYVzhWl3XajDhdtZ6E5raA4DGISczngyjviBd54VbO1bkayun4fJZezWPw9vJkKrfQ0pp33Cgv7RLcRO_xp-VOTFIotpV4dfEjEOaUwPjKNcm_hkUgqho-DHX0Lkaz0GmxUFYvE7vFFhZ-M-OOcgmubySGZjgFozj7kXSQmKCSbsXmdMKB14iwATWX8BBrfEkamMgnIMbNLjfASjyWql6j_KgvfuGI8xwGMyX5zqDM2126CmNBfvPinKd8nDR74ZlW7UV6exRRZOJAJeZXfbQSjsqptx13KC0wwdd1at0QKs2G_M7JOfEiLYCCqvuWBHhd5zTAv-4xDvcTbhYe6sZxFEwNpaXmwlSXy79Zq5FqTG8jvvp-FO0pZIAwIEm3M5jvLfoNz7cJSCr5LeseTvNwKz9Z3PLFZ7snosB-oKvSuW7q7i9v14ZDSp6m_l48_OA83JsUVLCXibYT55avEHNMkeKtMvPgL2v8wH6U-qkkh6RTyP8GyD9imIMn9G1T4iLufH2-eF4U7Ln3798tVKJKkmr2p7swHAIe16eJj69pqZkVdJj3CD_6fBxkHkREgRw5RoD3EBFoSu-pTEMKqLqk5rILTtS5u2FCt1XZUO2iPH2aCo-oViJM8JeduZdKJnAkNd-Mn_plGILP649O_ZXgqQdPWHvRavjvoHPinPZz5Wn-iAZQNbAy-9uJJEik5YlrA3znROgGAPJ6c9QHRUmggxkat0STVImEefleymm54kD942DYwnO4vcmNwYtbjxa1R1C6WR2IFxKstnGqJ70lUKMtqfbINw3JJ9SYIQ5yoKV_6hU\\\";}s:9:\\\"azureData\\\";a:2:{s:11:\\\"accountName\\\";s:19:\\\"co365newnewvoilan06\\\";s:10:\\\"accountKey\\\";s:88:\\\"\\/kXhKUIXaQceMTwBpVkmHnSicrHHSSLW3SenAERwaX9ZrX+ii77\\/rIGayx8uAP+835gKaBgY+fY\\/\\/ZMsrZ\\/Aag==\\\";}s:4:\\\"type\\\";s:5:\\\"posts\\\";s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 'Illuminate\\Queue\\MaxAttemptsExceededException: App\\Jobs\\EDiscoveryTeamsBackground has been attempted too many times or run too long. The job may have previously timed out. in D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php:648\nStack trace:\n#0 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(436): Illuminate\\Queue\\Worker->maxAttemptsExceededException(Object(Illuminate\\Queue\\Jobs\\DatabaseJob))\n#1 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(346): Illuminate\\Queue\\Worker->markJobAsFailedIfAlreadyExceedsMaxAttempts(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), 1)\n#2 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(306): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#3 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(265): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#4 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(112): Illuminate\\Queue\\Worker->runNextJob(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#5 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#6 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#7 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(37): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#8 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#9 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#10 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(596): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#11 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(134): Illuminate\\Container\\Container->call(Array)\n#12 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#13 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(121): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#14 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(971): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#15 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(290): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#16 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(166): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#17 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(93): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#18 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(129): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#19 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#20 {main}', '2021-08-10 13:46:25');
INSERT INTO `failed_jobs` (`id`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(11, 'database', 'default', '{\"uuid\":\"c357088e-65ab-4299-b4b9-b5a4bf9bd5b4\",\"displayName\":\"App\\\\Jobs\\\\EDiscoveryTeamsBackground\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\EDiscoveryTeamsBackground\",\"command\":\"O:33:\\\"App\\\\Jobs\\\\EDiscoveryTeamsBackground\\\":15:{s:6:\\\"userId\\\";i:231;s:15:\\\"ediscoveryJobId\\\";i:51;s:14:\\\"searchQueryArr\\\";a:1:{i:0;s:19:\\\"-body:\\\"test8iiiiii\\\"\\\";}s:12:\\\"managerVeeam\\\";O:29:\\\"App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\\":4:{s:42:\\\"\\u0000App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\u0000_urlManager\\\";O:26:\\\"App\\\\Engine\\\\Veeam\\\\UrlsVeeam\\\":0:{}s:38:\\\"\\u0000App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\u0000_caller\\\";O:25:\\\"App\\\\Engine\\\\Base\\\\CallerApi\\\":0:{}s:9:\\\"_username\\\";s:22:\\\"Veeam365-VM\\\\veeamadmin\\\";s:9:\\\"_password\\\";s:13:\\\"V##@M@word123\\\";}s:12:\\\"managerAzure\\\";O:29:\\\"App\\\\Engine\\\\Azure\\\\ManagerAzure\\\":7:{s:42:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_urlManager\\\";O:26:\\\"App\\\\Engine\\\\Azure\\\\UrlsAzure\\\":0:{}s:40:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_clientId\\\";s:36:\\\"611665ae-cedd-4e5f-b854-b6fff49f32b3\\\";s:38:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_secret\\\";s:34:\\\"Pr~Z7M5_6.4hi9mX.tNKc-trBQ0NVB3c3e\\\";s:42:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_grant_type\\\";s:13:\\\"refresh_token\\\";s:40:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_resource\\\";s:29:\\\"https:\\/\\/management.azure.com\\/\\\";s:38:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_caller\\\";O:25:\\\"App\\\\Engine\\\\Base\\\\CallerApi\\\":0:{}s:45:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_refresh_token\\\";s:1310:\\\"0.AYEANuxCvt-kjkGHRsqC8Zf_465lFmHdzl9OuFS2__SfMrOBAJA.AgABAAAAAAD--DLA3VO7QrddgJg7WevrAgDs_wQA9P-wE78x-1SCuXdwdHEC4GCR8V8F6ZZQAI3Eak4d3ean7sKRMlcD3lsHzEu6CHpjEESntJhn6Nup29QZ7olITxnPzwTfFNBDLLSMoEWQFJQUAdhMuhY6yocXTKMRfNOfOKRuueSta6lrVY0MYDpdTQW_8WzCyR07CIpJ_O2jlUCNZcPhrxHRiSoGsGuRkmmurSzWKb2fs2iEDcocSt9g8wI2BiAvCIpSXuzjj1YIuwfUIj5em3TdJvHcR5hIIlfyWnhpn_diWSlT6n6zGdoRMoQAMGW4aqiOSFtwHFpCYWXzRIZz6TCVoYpueqpwUo59ooaovZALJFdszvgoMW_omaCRtIEz2DIhAnYdb1NaEu3u6O6cnkv-J_OhRVjC3zhnJswFpgku85r7VvnqCbQs9TWDbET0buXoyt2yPXLJMwtFPIqttJ72VfeR_z-gv7w8vkGWhcwN3n9Gg8pnFTmNtBm6wI9DWVHGAEE0Az9TB1IRMy9YEtKfMBYMdSftbDyULxCjqXdayHfHo1NjUdjRiuRIaXfcWAt1heF1YCHWF9pUQoC_TmZXtL1KyvDPmQO2cZbtasg7CzMs7XUIaDDMXVMpiYW6ZeUaE5K9gt_XoqhiFT-JI-3kGJPSSRDUSjVannB0m9J7yh_IKzLe4GK0OWjNsNrp_5ftNRbZ08LZF6AqluGHgKRfdyUxV4WOY6twGBFwFWVxGQK3DLvLrZWF5oiiSj9R3twU6J70s-0uoMdJsCkvHcIMvKIZFDxyZ-1Kq69Vl2ohAO69HXfgu06CbLmZg4QSx9inc2E5gbxDd3p628Lbm_1rMdz1AXwGEu8zjm_MFzQSnM-vhb3V_zXbDBwHY1qcbn02ZPSRjdM0TA6iM4iXASA8AWSomRR8ycTQrpx8nPpsJrYB4-rWjS2nGjqGK_jQVMhSAD_ZxpLXyO6dNR1lHJxpckMdFW3FPwL18V1ANh9Qa4Uo7wNFndRvblCI4FW6DqtXcrN08tudD67_qmz8I6uAILv-FQZTKXM61XQhl4rbXw0uq0uwseBA9Xjiu-xpGN0-Zzx0OLG-i9NMpBESq8GM-VyAZpGM9-YqogrbTv-HFj8eU6rIGyICO4HmLwFUSYv5ktEfHQol8e_4XRnF_6LWFd_Es_FZh2s5HaZGIn5GK0UA--pc733MFRcEsGz1nE_qaG_maKEsALSFbI8fM2JyfW6STzNKHPXA\\\";}s:9:\\\"azureData\\\";a:2:{s:11:\\\"accountName\\\";s:19:\\\"co365newnewvoilan06\\\";s:10:\\\"accountKey\\\";s:88:\\\"\\/kXhKUIXaQceMTwBpVkmHnSicrHHSSLW3SenAERwaX9ZrX+ii77\\/rIGayx8uAP+835gKaBgY+fY\\/\\/ZMsrZ\\/Aag==\\\";}s:4:\\\"type\\\";s:5:\\\"posts\\\";s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 'Illuminate\\Queue\\MaxAttemptsExceededException: App\\Jobs\\EDiscoveryTeamsBackground has been attempted too many times or run too long. The job may have previously timed out. in D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php:648\nStack trace:\n#0 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(436): Illuminate\\Queue\\Worker->maxAttemptsExceededException(Object(Illuminate\\Queue\\Jobs\\DatabaseJob))\n#1 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(346): Illuminate\\Queue\\Worker->markJobAsFailedIfAlreadyExceedsMaxAttempts(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), 1)\n#2 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(306): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#3 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(265): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#4 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(112): Illuminate\\Queue\\Worker->runNextJob(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#5 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#6 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#7 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(37): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#8 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#9 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#10 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(596): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#11 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(134): Illuminate\\Container\\Container->call(Array)\n#12 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#13 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(121): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#14 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(971): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#15 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(290): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#16 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(166): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#17 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(93): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#18 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(129): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#19 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#20 {main}', '2021-08-10 13:49:54'),
(12, 'database', 'default', '{\"uuid\":\"f0b41a89-ad24-481b-930d-b836a4adb5dc\",\"displayName\":\"App\\\\Jobs\\\\EDiscoveryTeamsBackground\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\EDiscoveryTeamsBackground\",\"command\":\"O:33:\\\"App\\\\Jobs\\\\EDiscoveryTeamsBackground\\\":15:{s:6:\\\"userId\\\";i:231;s:15:\\\"ediscoveryJobId\\\";i:53;s:14:\\\"searchQueryArr\\\";a:1:{i:0;s:19:\\\"-body:\\\"test8iiiiii\\\"\\\";}s:12:\\\"managerVeeam\\\";O:29:\\\"App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\\":4:{s:42:\\\"\\u0000App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\u0000_urlManager\\\";O:26:\\\"App\\\\Engine\\\\Veeam\\\\UrlsVeeam\\\":0:{}s:38:\\\"\\u0000App\\\\Engine\\\\Veeam\\\\ManagerVeeam\\u0000_caller\\\";O:25:\\\"App\\\\Engine\\\\Base\\\\CallerApi\\\":0:{}s:9:\\\"_username\\\";s:22:\\\"Veeam365-VM\\\\veeamadmin\\\";s:9:\\\"_password\\\";s:13:\\\"V##@M@word123\\\";}s:12:\\\"managerAzure\\\";O:29:\\\"App\\\\Engine\\\\Azure\\\\ManagerAzure\\\":7:{s:42:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_urlManager\\\";O:26:\\\"App\\\\Engine\\\\Azure\\\\UrlsAzure\\\":0:{}s:40:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_clientId\\\";s:36:\\\"611665ae-cedd-4e5f-b854-b6fff49f32b3\\\";s:38:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_secret\\\";s:34:\\\"Pr~Z7M5_6.4hi9mX.tNKc-trBQ0NVB3c3e\\\";s:42:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_grant_type\\\";s:13:\\\"refresh_token\\\";s:40:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_resource\\\";s:29:\\\"https:\\/\\/management.azure.com\\/\\\";s:38:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_caller\\\";O:25:\\\"App\\\\Engine\\\\Base\\\\CallerApi\\\":0:{}s:45:\\\"\\u0000App\\\\Engine\\\\Azure\\\\ManagerAzure\\u0000_refresh_token\\\";s:1310:\\\"0.AYEANuxCvt-kjkGHRsqC8Zf_465lFmHdzl9OuFS2__SfMrOBAJA.AgABAAAAAAD--DLA3VO7QrddgJg7WevrAgDs_wQA9P8PTs5GjOwQJjgcd9Ejs9j5WedJ-8M0PJ67MVULzt74vkKa3qL7IieziySjs-YR51zbRllnx5LMs0qBRzYcYvBwBsHyfAW-B1jy2zvYZJtwm3qdiIlc4S3iLOX1aetJSRQSQjsAI2tHci0yuyl_tIpKhovVFNQ0Hnw-Q53fhqsHEHOjGiwhDJYYmtHSwb1qSCVjR1LU_jLJehSXfBjtmiaJwAZxYYKzoQ7CVBhoImtP_y7YWOj9skXoaH79SiC1C2kkbKBqjnzBhRwO8WTck2GKkPiV5UobhsfSk4pD8oEQ-_l0TbdwxJ8KhZOQD1XQqkBhi7rdq4wPSMjVBFSbaJen7FtVX-HU5GT0DxqECymt3Spg8TaQdHTHxW7hpvIlIG9-GuNKoID3S5RQhLiURsgQ6IuSxZ69g10mT4RbnpSynPh-FG0zyQH1TEXad2XFQn6b43nwdpOWuTFCnYkLb9saV9GJF5jR4VeDU86joSY1AVbVLs0fSbiljIWcptdEx-YhEZPOvdEYRP8s9LBXrl3NrI30D1pvdcFHJ7Z-zIelPGbgyFZliTAJY96BnEbQ-q3xhDLusb6cifzGI7aEpsHsrq5vLi5ITw-qhPD9Igo7wi2Wu_bG1lTBVBSAHPoaWgqGydwlU2rMQ3LoYs1LM6d3AQxBLyBxUCg2d8UC3ndCNbSVzHhBRs5a3gikn_A7Deosgm6eM1clmjZ3eToFAxIdJWKY4l5Lq-cto1AZqnTqTc9bnQ0ZTjrsMhGeZwFsgs3Oj5Fr6OSq9pDHPlD4LcBTSvwaEf5DHq1RJ6VdFjLzMWTI8bgj1khiXRkm-9LBYHQyKy0hI-3UnYTyVKknakOY_3Ukb5dOZpThy0WhVLDGI0BZ0WANmU94NUGMv3CNAFKHSbafxHyYCvNvHSX3Rloxntr3E92koowQPnu2sDF5pqRXN-hbdFo8kUHr9v_m5rAwe1LBVjMt4UBWm0B-2asiTehX0LOirsWsue_qRdGOPpe2q3tIy7W_K3sK3_YyhtxGC3XTLtj1S1tHaE9VEBY6xKL0WjwM6M3yN6OQxosfwchh6vNZ8E5f-J8tOeyf-jnlj4Kn8OjTf_3uUCqmdJR163eRa6p4-JHDQYP2qhb6JHeBZL6beT7WUKWhgE2rIFVX-uCaLTm-sOkgDjWsk3G3H9YRxei1UQ22as_P6Yzw8-6K5n0965883RNWRB-Y\\\";}s:9:\\\"azureData\\\";a:2:{s:11:\\\"accountName\\\";s:19:\\\"co365newnewvoilan06\\\";s:10:\\\"accountKey\\\";s:88:\\\"\\/kXhKUIXaQceMTwBpVkmHnSicrHHSSLW3SenAERwaX9ZrX+ii77\\/rIGayx8uAP+835gKaBgY+fY\\/\\/ZMsrZ\\/Aag==\\\";}s:4:\\\"type\\\";s:5:\\\"posts\\\";s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 'Illuminate\\Queue\\MaxAttemptsExceededException: App\\Jobs\\EDiscoveryTeamsBackground has been attempted too many times or run too long. The job may have previously timed out. in D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php:648\nStack trace:\n#0 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(436): Illuminate\\Queue\\Worker->maxAttemptsExceededException(Object(Illuminate\\Queue\\Jobs\\DatabaseJob))\n#1 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(346): Illuminate\\Queue\\Worker->markJobAsFailedIfAlreadyExceedsMaxAttempts(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), 1)\n#2 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(306): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#3 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(265): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#4 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(112): Illuminate\\Queue\\Worker->runNextJob(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#5 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#6 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#7 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(37): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#8 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#9 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#10 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(596): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#11 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(134): Illuminate\\Container\\Container->call(Array)\n#12 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#13 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(121): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#14 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(971): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#15 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(290): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#16 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\symfony\\console\\Application.php(166): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#17 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(93): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#18 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(129): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#19 D:\\wamp64\\www\\Portal\\ctelecoms-portal\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#20 {main}', '2021-08-10 14:04:11');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2020_03_24_080856_verification_code_type', 1),
(5, '2020_03_25_080205_verification_code', 1),
(6, '2020_03_25_081418_verification_code_user', 1),
(7, '2020_04_15_160514_create_countries_table', 1),
(9, '2020_05_14_231041_create_parameters_table', 1),
(10, '2020_8_30_000000_azure_container', 2),
(11, '2020_8_30_000000_azure_resource_group', 2),
(12, '2020_8_30_000000_azure_storage_account', 2),
(13, '2020_8_30_000000_organization_details', 2),
(14, '2020_8_30_000000_veeam_cloud_credentional_account', 3),
(15, '2020_8_30_000000_veeam_folder', 4),
(16, '2020_8_30_000000_veeam_storage_object', 5),
(17, '2020_8_31_000000_veeam_backup_repository', 6),
(18, '2020_05_01_180135_create_organizations_table', 7),
(19, '2020_9_15_000000_naming_counter', 8),
(20, '2021_01_27_102652_create_jobs_table', 9),
(21, '2021_01_30_134426_create_jobs_table', 10),
(22, '2021_02_01_112924_create_failed_jobs_table', 11),
(23, '2021_04_18_170822_create_sessions_table', 12);

-- --------------------------------------------------------

--
-- Table structure for table `naming_counter`
--

DROP TABLE IF EXISTS `naming_counter`;
CREATE TABLE IF NOT EXISTS `naming_counter` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_user_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `naming_counter`
--

INSERT INTO `naming_counter` (`id`, `organization_user_id`, `value`, `created_at`, `updated_at`) VALUES
(1, '196', '26', '2021-05-30 06:59:51', '2021-07-28 08:28:16'),
(2, '208', '1', '2021-05-30 06:59:51', '2021-07-10 14:09:06'),
(3, '216', '5', '2021-05-30 06:59:51', '2021-07-10 14:09:06'),
(4, '220', '1', '2021-05-30 06:59:51', '2021-07-10 14:09:06'),
(5, '220', '1', '2021-05-30 06:59:51', '2021-07-10 14:09:06'),
(6, '220', '1', '2021-05-30 06:59:51', '2021-07-10 14:09:06'),
(7, '220', '1', '2021-05-30 06:59:51', '2021-07-10 14:09:06'),
(8, '221', '2', '2021-05-30 06:59:51', '2021-07-10 14:09:06'),
(9, '221', '1', '2021-05-30 06:59:51', '2021-07-10 14:09:06'),
(10, '221', '1', '2021-05-30 06:59:51', '2021-07-10 14:09:06'),
(11, '221', '1', '2021-05-30 06:59:51', '2021-07-10 14:09:06'),
(12, '221', '1', '2021-05-30 06:59:51', '2021-07-10 14:09:06'),
(13, '221', '1', '2021-05-30 06:59:51', '2021-07-10 14:09:06'),
(14, '221', '1', '2021-05-30 06:59:51', '2021-07-10 14:09:06'),
(15, '222', '1', '2021-05-30 06:59:51', '2021-07-10 14:09:06'),
(16, '223', '1', '2021-05-30 06:59:51', '2021-07-10 14:09:06'),
(17, '224', '1', '2021-05-30 06:59:51', '2021-07-10 14:09:06'),
(18, '225', '3', '2021-05-30 06:59:51', '2021-07-10 14:09:06'),
(19, '227', '1', '2021-06-05 11:19:16', '2021-07-10 14:09:06'),
(20, '230', '6', '2021-06-23 12:08:47', '2021-07-10 14:09:06'),
(21, '230', '1', '2021-06-23 12:11:28', '2021-07-10 14:09:06'),
(22, '231', '7', '2021-06-23 17:04:44', '2021-08-10 10:30:46'),
(23, '230', '1', '2021-06-27 12:24:54', '2021-07-10 14:09:06');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `code`, `text`, `created_at`, `updated_at`) VALUES
(1, 'REPOSITORIES', 'Repositories Actions', NULL, '2021-05-22 10:37:44'),
(2, 'BACKUP', 'Backup Actions', NULL, '2021-05-22 10:37:40'),
(3, 'BACKUP_JOB', 'Backup Jobs Notifications', NULL, '2021-05-22 10:37:40'),
(4, 'RESTORE', 'Restore Actions', NULL, '2021-05-22 10:37:40');

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

DROP TABLE IF EXISTS `organizations`;
CREATE TABLE IF NOT EXISTS `organizations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cloud_id` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `veeam_server_id` int(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `organizations_user_id_foreign` (`user_id`),
  KEY `veeam_server_id` (`veeam_server_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`id`, `cloud_id`, `name`, `user_id`, `veeam_server_id`, `created_at`, `updated_at`) VALUES
(1, 'eb862061-c2e1-49e1-9f9d-788c273162fd', 'M365x056839.onmicrosoft.com', 196, 1, '2021-02-16 08:43:08', '2021-02-16 08:43:08'),
(19, '1c47ccaf-ba61-41f2-8c92-351a062a9561', 'M365x210347.onmicrosoft.com', 227, 1, '2021-06-05 11:19:15', '2021-06-05 11:19:15'),
(22, 'dc3342d4-b8ab-4060-81c4-daa428e9adbc', 'M365x210347.onmicrosoft.com', 231, 1, '2021-06-23 17:04:44', '2021-06-23 17:04:44'),
(23, '7e5e72ce-4e13-4efd-8a64-e5dbfb57ad5f', 'M365x999717.onmicrosoft.com', 230, 1, '2021-06-27 12:24:54', '2021-06-27 12:24:54');

-- --------------------------------------------------------

--
-- Table structure for table `parameters`
--

DROP TABLE IF EXISTS `parameters`;
CREATE TABLE IF NOT EXISTS `parameters` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(4000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parameters`
--

INSERT INTO `parameters` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'PARTNER_CENTER_CLIENT_ID', '73da9650-0f42-4eda-ad0c-e2d6de9cf021', NULL, NULL),
(2, 'PARTNER_CENTER_GRANT_TYPE', 'refresh_token', NULL, NULL),
(3, 'PARTNER_CENTER_SECRET', '~LXI6sz0-oMajXLL7bO-2dt5A9rVy~UJ~J', NULL, NULL),
(5, 'PARTNER_CENTER_RESOURCE', 'https://api.partnercenter.microsoft.com', NULL, NULL),
(6, 'PARTNER_CENTER_TENANT_ID', 'c3fb3a01-c4df-40be-b0be-7e1185b52bf1', NULL, NULL),
(10, 'AZURE_URL', 'https://management.azure.com/subscriptions/', NULL, NULL),
(11, 'PARTNER_CENTER_URL', 'https://api.partnercenter.microsoft.com/v1/', NULL, NULL),
(14, 'AZURE_REFRESH_TOKEN', '0.AYEANuxCvt-kjkGHRsqC8Zf_465lFmHdzl9OuFS2__SfMrOBAJA.AgABAAAAAAD--DLA3VO7QrddgJg7WevrAgDs_wQA9P-g4vjRPfOMxdx7KJ2pYgHhweapcewJJZWg_9Op4gfSBEG5ydaTnzkze2GbLqsyP3tCJBD6aLU24vBblw6_oCS8HzjUF-ukd5XXw_sb85zQN5jO7E8wqzP1385esTN4W0CqOQyURHkJ8YkSoY8Xwiw0sJyr1x5_dcxY0wMK7wKeGKnSwvkHszjchY2XyMbqP8dfPcP4NoEhwdsdGH_vnZXnHHw5-xF3WwPQX7hrKnoHRBuVF4ByU3XWN4Eu8XqKyDX_OUTYyFHETGaf_DneBs8I_zcz-lPgEEoAapQB4NLo21Rx8qfqlNB0l_TbrfPY4aZx4D8KG-cUlZJwpEbiK5e_HDmlTulRn0fNEyhCKq8XjGu_LjV-1ixlJfbhRl0snyCaMz2bCS8kMMEORklX6MuP4N-jLfcRAfJsghQN00dGIWUpCXCc9cE5eNTDGJbwuXcH1QdkhH6GDCjVqnNQonUOpZ9kgLcRHmGpcwjl-X_cHGzQO6gjxkOgLjvth41XgnVVETzGM5JL6JoHIrWIT7WwFbqertvZMW82rdWg_mi4eqIQeWDXLs4q-VshfXtvljcl4IgSLeew0bkPbxsmPIcBFeqHr44CQsR0XbUJH1EDi9HVgXNy8cuAVUSYyYRVp4jUwM1BlsfuPxH0fSR9Bs0G3yPM33b_P6xF3xDdSx0upFdtB6JBvBbsfYPLiWjp7T-5LacuFZoOT6H7RNoDnz6TBAwulY9ACrlaAAXn6-oGWHh9TZ5RAW_9Afs1f19OxCrvRztZRzufrzqG0YYF3n8qt6dsCLtNNYnzvt8QYoOCaWbXJWswV16pKUXsK7iWKAVWKPKOniINjVJFsa3H4Y-vTkiwneIndez7H8wvDwykjNRB6By3JylZdWVLgpmTaqfiizsklidjlV26sBM60bhrWLIXZrSpVmBGn-6x9NnAJ5drBVrlXKd4GbqjgPAcN9UI2SQvRZHzYRaLc4i7Syxm47yb0ZKdOHAM3cgOzUhtPuURnblUMUPyzIlD7EO1ZByIwXGYpF9zXfgN870GLUlHMucVJEIJLNFVP48W8wzb0lhbnNrIKqC09T918KYrOaBaRhyyG924WVNfHqTrpVVreiayxKscisP1qSS8qipJjr55lqlMXII6c60fjpuuAPy0hFi0hCCmAw2ZjHtTEQmgzqLb82eKpXrwBis8_-3NLYr6D-nRbkTMOOe8c5Bd', NULL, '2021-08-11 07:34:48'),
(15, 'AZURE_CLIENT_ID', '611665ae-cedd-4e5f-b854-b6fff49f32b3', NULL, NULL),
(16, 'AZURE_SECRET', '1jHQf~puNyl._O2Jf-OAEOR0~p1jxG2VA3', NULL, NULL),
(17, 'AZURE_GRANT_TYPE', 'refresh_token', NULL, NULL),
(18, 'AZURE_RESOURCE', 'https://management.azure.com/', NULL, NULL),
(19, 'EXPORTED_FILES_EXPIRATION_DAYS', '7', NULL, NULL),
(20, 'UPLOAD_BLOB_BLOCK_MEGA_SIZE', '0.5', NULL, NULL),
(21, 'MINUTES_BEFORE_BLOB_LINK_EXPIRE', '60', NULL, NULL),
(22, 'EXPLORING_RESTORE_ITEMS_WARNING_COUNT', '20', NULL, NULL),
(23, 'EXPLORING_RESTORE_ITEMS_STOPPING_COUNT', '5', NULL, NULL),
(24, 'EXPLORING_RESTORE_ITEMS_LIMIT_COUNT', '10', NULL, NULL),
(25, 'DIRECT_DOWNLOAD_MEGA_LIMIT', '10', NULL, NULL),
(26, 'TEMP_BLOB_FILE_EXPIRATION_MINUTES', '1', NULL, NULL),
(27, 'DIRECT_DOWNLOAD_ITEMS_COUNT_LIMIT', '2', NULL, NULL),
(28, 'TRIAL_LICENSE_COUNT', '20', NULL, NULL),
(29, 'TRIAL_EXPIRY_DAYS', '20', NULL, NULL),
(30, 'ALLOWED_RESTORE_DAYS_AFTER_LICENSE_EXPIRED', '1', NULL, NULL),
(31, 'PARTNER_CENTER_REFRESH_TOKEN', '0.ATkAATr7w9_EvkCwvn4RhbUr8VCW2nNCD9pOrQzi1t6c8CE5AJA.AgABAAAAAAD--DLA3VO7QrddgJg7WevrAgDs_wQA9P_O0XP9mbiPEuuUT0HylXcaaWAhCzyb24CZgJe97iglDoAaDp_DS-Tglhw2ZaKY0S8YHpkqdHJ7XMk8cttUyiS6vYWem0YOgvUGTrFDs3Z34iKipygwFG4TFDPqyGHpCvcm_BGYqztssltR9yhRFzf1iW_oUlSFJNWAstqvx6m0RLVLnIcPdM8bPHatk82QUF6l5ul00H4z4nHCVVOMB9N6ejEZJpmf9R9Br6hFXLTOfnwKc8DJdOqxbJJP1a1-OrAJcQD4p9-lxHftEh8l5rAZJTuMQKxO2Kda5WhSqXXYTKrrBpb4QfDef6kQ4NvVtUao5UtXaBFHUp-7ii0RMGIfCL8ZnGP5Vu9t57kgkS0745IxJtkAT3HB1IjMA4SWXY5Fak2IKwapgFGcmeOPuwZLTz9la9IkOu4i_oyGAibmf49-3OXgU9I--KLEXJzf59HIs2BqmHfNSaW600iZKnVYqiYpbqVWVNeQR55P-Lu6JUcLFxe2phcoDqVfAiSNvmKFWqutY77u61Sd74a0jUe8ZOMQqdP9k5wln1oIdrb4tCEPpA5Ta6ysuuPzJcA9BxSS6ou3NpJI5-YKDepVkMiZ5tTP5wqDPZZmXIei_aUSd-GVxdsXPbazzcvIht1h5CWvQU8IQUEY7g4XSdjXbm_HVdmqyPHypePvX8p2TAEdwXJwQCdL4Qeh3oPFY-o4Jbn-YsML3uNds3gmSh8QNEC1ft1GvwTv4gLB1V348Qou0ZgDY1EAhvqXp_8JDUBvIGpc2krs-nyNdXW-QAULEW7nhgniFjV9IbKC5_R2kXiVRlRIO9Ydrg45xjEdP__2HrNUTXYiG_PmU-udQxiUV6syFnHJ8cxuARv46IlKfmfOwwzl8jPhQ245PLPUbdtwZDPXS7MW3EelVcqEJd1uV1hpCclWUPn6RbpSqxZ5q2hpxgGtr46V7Y-stnJQzJaCwGmQQOn3j35IuDQc0iZTggOSV7FqfOm6QJDgdP9WEjdZsJnaJxHYnCRwR2rZWKPY00QKviLiHS60Cu5cruMqYwTRLNH8i1WAeT1g-g_ipMvsEActPVxTyWoBDrkn68DeoPrfjCGBxiOoBU4P6mI9Y4Q', NULL, '2021-06-30 12:12:05'),
(32, 'REMAINING_DAYS_BEFORE_DATA_DELETE_AFTER_EXPIRED', '1', NULL, NULL),
(34, 'EXPLORING_EDISCOVERY_ITEMS_LIMIT_COUNT', '10', NULL, NULL),
(35, 'INSERTING_EDISCOVERY_ITEMS_LIMIT_COUNT', '100', NULL, NULL);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('achaban@ctelecoms.com.sa', '$2y$10$dR6FJ7shUnFLXfwgf4e/RO20MoBkoHQuJF1uRrLAIYhOf6aA1EjdW', '2021-06-30 11:22:33');

-- --------------------------------------------------------

--
-- Table structure for table `restore_history`
--

DROP TABLE IF EXISTS `restore_history`;
CREATE TABLE IF NOT EXISTS `restore_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `name` text COLLATE utf8mb4_unicode_ci,
  `kind` text COLLATE utf8mb4_unicode_ci,
  `type` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_type` text COLLATE utf8mb4_unicode_ci,
  `options` json DEFAULT NULL,
  `items_count` int(11) DEFAULT NULL,
  `status` text COLLATE utf8mb4_unicode_ci,
  `backup_job_id` int(10) UNSIGNED DEFAULT NULL,
  `restore_point_time` text COLLATE utf8mb4_unicode_ci,
  `is_restore_point_show_deleted` tinyint(1) DEFAULT NULL,
  `is_restore_point_show_version` tinyint(1) DEFAULT NULL,
  `restore_session_guid` text COLLATE utf8mb4_unicode_ci,
  `completion_time` datetime DEFAULT NULL,
  `expiration_time` datetime DEFAULT NULL,
  `request_time` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `RESTORE_JOB_CONSTRAINT` (`backup_job_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `restore_history`
--

INSERT INTO `restore_history` (`id`, `user_id`, `name`, `kind`, `type`, `sub_type`, `options`, `items_count`, `status`, `backup_job_id`, `restore_point_time`, `is_restore_point_show_deleted`, `is_restore_point_show_version`, `restore_session_guid`, `completion_time`, `expiration_time`, `request_time`, `created_at`, `updated_at`) VALUES
(1, 231, '3_MailboxItems_20210810_100402', 'Exchange', 'item', 'Restore Mailbox Items', '{\"toFolder\": null, \"toMailBox\": \"admin@M365x210347.onmicrosoft.com\", \"folderType\": \"original\", \"changedItems\": \"true\", \"deletedItems\": \"true\", \"markRestoredAsunread\": \"true\"}', 3, 'Success', 22, '2021-08-09T14:01:01.4590430Z', 1, 1, 'a8950f87-e4a6-4a08-8947-8728c0d284e3', '2021-08-10 10:04:28', NULL, '2021-08-10 10:04:02', '2021-08-10 10:04:02', '2021-08-10 10:04:28'),
(2, 231, '3_MailboxItems_20210810_101240', 'Exchange', 'item', 'Restore Mailbox Items', NULL, 3, 'Success', 22, '2021-08-09T14:01:01.4590430Z', 0, 0, '13526680-c009-49f6-afe9-054acb9f390a', '2021-08-10 10:12:48', NULL, '2021-08-10 10:12:40', '2021-08-10 10:12:40', '2021-08-10 10:12:48'),
(3, 231, '3_MailboxItems_20210810_101300', 'Exchange', 'item', 'Restore Mailbox Items', '{\"toFolder\": null, \"toMailBox\": \"admin@M365x210347.onmicrosoft.com\", \"folderType\": \"original\", \"changedItems\": \"true\", \"deletedItems\": \"true\", \"markRestoredAsunread\": \"true\"}', 3, 'Success', 22, '2021-08-09T14:01:01.4590430Z', 0, 0, 'bb6da776-9363-4a5f-8896-62f85dec8f9d', '2021-08-10 10:13:09', NULL, '2021-08-10 10:13:00', '2021-08-10 10:13:00', '2021-08-10 10:13:09'),
(4, 231, '3_MailboxItems_20210810_101309', 'Exchange', 'item', 'Export Mailbox Items', NULL, 3, 'Success', 22, '2021-08-09T14:01:01.4590430Z', 0, 0, '5fcc1c6b-80a6-40af-8d1c-034e97d00219', '2021-08-10 10:15:00', '2021-08-17 10:15:00', '2021-08-10 10:13:09', '2021-08-10 10:13:09', '2021-08-10 10:15:00'),
(5, 231, '3_MailboxItems_20210810_112056', 'Exchange', 'item', 'Restore Mailbox Items', '{\"toFolder\": null, \"toMailBox\": \"admin@M365x210347.onmicrosoft.com\", \"folderType\": \"original\", \"changedItems\": \"true\", \"deletedItems\": \"true\", \"markRestoredAsunread\": \"true\"}', 3, 'Success', 22, '2021-08-09T14:01:01.4590430Z', 0, 0, 'bdacb8a4-2f5d-45ba-b8f1-a1fb9b966de0', '2021-08-10 11:21:05', NULL, '2021-08-10 11:20:56', '2021-08-10 11:20:56', '2021-08-10 11:21:05');

-- --------------------------------------------------------

--
-- Table structure for table `restore_history_details`
--

DROP TABLE IF EXISTS `restore_history_details`;
CREATE TABLE IF NOT EXISTS `restore_history_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `history_id` int(11) NOT NULL,
  `item_id` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_name` text COLLATE utf8mb4_unicode_ci,
  `item_parent_id` text COLLATE utf8mb4_unicode_ci,
  `item_parent_name` text COLLATE utf8mb4_unicode_ci,
  `type` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `result` json DEFAULT NULL,
  `blob_name` text COLLATE utf8mb4_unicode_ci,
  `blob_size` double DEFAULT NULL,
  `last_download_date` datetime DEFAULT NULL,
  `duration` text COLLATE utf8mb4_unicode_ci,
  `error_response` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `log_details_ibfk_1` (`history_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `restore_history_details`
--

INSERT INTO `restore_history_details` (`id`, `history_id`, `item_id`, `item_name`, `item_parent_id`, `item_parent_name`, `type`, `status`, `result`, `blob_name`, `blob_size`, `last_download_date`, `duration`, `error_response`, `created_at`, `updated_at`) VALUES
(1, 1, '[{\"id\":\"LgAAAEYAAAAWAAAAAAAAAC0lne3awQFAnSduIU7fbo8BAD3kJbgZ5_5AnAJgiDhlJJIAAAAAAQwAAAAAAAAtJZ3t2sEBQJ0nbiFO326PBwA95CW4Gef-QJwCYIg4ZSSSAAAAAAEMAAA95CW4Gef-QJwCYIg4ZSSSAABCHmAgAAA95CW4Gef-QJwCYIg4ZSSSAABCEQ9q\",\"parentName\":\"Adele Vance-Inbox\",\"name\":\"You have late tasks!\",\"folderTitle\":\"Inbox\"},{\"id\":\"LgAAAEYAAAAWAAAAAAAAAC0lne3awQFAnSduIU7fbo8BAD3kJbgZ5_5AnAJgiDhlJJIAAAAAAQwAAAAAAAAtJZ3t2sEBQJ0nbiFO326PBwA95CW4Gef-QJwCYIg4ZSSSAAAAAAEMAAA95CW4Gef-QJwCYIg4ZSSSAABBPSEHAAA95CW4Gef-QJwCYIg4ZSSSAABBL9BW\",\"parentName\":\"Adele Vance-Inbox\",\"name\":\"You have late tasks!\",\"folderTitle\":\"Inbox\"},{\"id\":\"LgAAAEYAAAAWAAAAAAAAAC0lne3awQFAnSduIU7fbo8BAD3kJbgZ5_5AnAJgiDhlJJIAAAAAAQwAAAAAAAAtJZ3t2sEBQJ0nbiFO326PBwA95CW4Gef-QJwCYIg4ZSSSAAAAAAEMAAA95CW4Gef-QJwCYIg4ZSSSAAA-8XNYAAA95CW4Gef-QJwCYIg4ZSSSAAA-5CC-\",\"parentName\":\"Adele Vance-Inbox\",\"name\":\"You have late tasks!\",\"folderTitle\":\"Inbox\"}]', NULL, '4f807f98-7206-4edc-a2f7-51ff351b88d7', 'Adele Vance', 'item', 'Success', '{\"failedItemsCount\": 0, \"mergedItemsCount\": 0, \"createdItemsCount\": 3, \"skippedItemsCount\": 0}', NULL, NULL, NULL, '14 seconds', NULL, '2021-08-10 10:04:02', '2021-08-10 10:04:28'),
(2, 2, '[{\"id\":\"LgAAAEYAAAAWAAAAAAAAAC0lne3awQFAnSduIU7fbo8BAD3kJbgZ5_5AnAJgiDhlJJIAAAAAAQwAAAAAAAAtJZ3t2sEBQJ0nbiFO326PBwA95CW4Gef-QJwCYIg4ZSSSAAAAAAEMAAA95CW4Gef-QJwCYIg4ZSSSAABCHmAgAAA95CW4Gef-QJwCYIg4ZSSSAABCEQ9q\",\"parentName\":\"Adele Vance-Inbox\",\"name\":\"You have late tasks!\",\"folderTitle\":\"Inbox\"},{\"id\":\"LgAAAEYAAAAWAAAAAAAAAC0lne3awQFAnSduIU7fbo8BAD3kJbgZ5_5AnAJgiDhlJJIAAAAAAQwAAAAAAAAtJZ3t2sEBQJ0nbiFO326PBwA95CW4Gef-QJwCYIg4ZSSSAAAAAAEMAAA95CW4Gef-QJwCYIg4ZSSSAABBPSEHAAA95CW4Gef-QJwCYIg4ZSSSAABBL9BW\",\"parentName\":\"Adele Vance-Inbox\",\"name\":\"You have late tasks!\",\"folderTitle\":\"Inbox\"},{\"id\":\"LgAAAEYAAAAWAAAAAAAAAC0lne3awQFAnSduIU7fbo8BAD3kJbgZ5_5AnAJgiDhlJJIAAAAAAQwAAAAAAAAtJZ3t2sEBQJ0nbiFO326PBwA95CW4Gef-QJwCYIg4ZSSSAAAAAAEMAAA95CW4Gef-QJwCYIg4ZSSSAAA-8XNYAAA95CW4Gef-QJwCYIg4ZSSSAAA-5CC-\",\"parentName\":\"Adele Vance-Inbox\",\"name\":\"You have late tasks!\",\"folderTitle\":\"Inbox\"}]', NULL, '4f807f98-7206-4edc-a2f7-51ff351b88d7', 'Adele Vance', 'item', 'Success', '{\"failedItemsCount\": 0, \"mergedItemsCount\": 0, \"createdItemsCount\": 0, \"skippedItemsCount\": 3}', NULL, NULL, NULL, '3 seconds', NULL, '2021-08-10 10:12:40', '2021-08-10 10:12:48'),
(3, 3, '[{\"id\":\"LgAAAEYAAAAWAAAAAAAAAC0lne3awQFAnSduIU7fbo8BAD3kJbgZ5_5AnAJgiDhlJJIAAAAAAQwAAAAAAAAtJZ3t2sEBQJ0nbiFO326PBwA95CW4Gef-QJwCYIg4ZSSSAAAAAAEMAAA95CW4Gef-QJwCYIg4ZSSSAABCHmAgAAA95CW4Gef-QJwCYIg4ZSSSAABCEQ9q\",\"parentName\":\"Adele Vance-Inbox\",\"name\":\"You have late tasks!\",\"folderTitle\":\"Inbox\"},{\"id\":\"LgAAAEYAAAAWAAAAAAAAAC0lne3awQFAnSduIU7fbo8BAD3kJbgZ5_5AnAJgiDhlJJIAAAAAAQwAAAAAAAAtJZ3t2sEBQJ0nbiFO326PBwA95CW4Gef-QJwCYIg4ZSSSAAAAAAEMAAA95CW4Gef-QJwCYIg4ZSSSAABBPSEHAAA95CW4Gef-QJwCYIg4ZSSSAABBL9BW\",\"parentName\":\"Adele Vance-Inbox\",\"name\":\"You have late tasks!\",\"folderTitle\":\"Inbox\"},{\"id\":\"LgAAAEYAAAAWAAAAAAAAAC0lne3awQFAnSduIU7fbo8BAD3kJbgZ5_5AnAJgiDhlJJIAAAAAAQwAAAAAAAAtJZ3t2sEBQJ0nbiFO326PBwA95CW4Gef-QJwCYIg4ZSSSAAAAAAEMAAA95CW4Gef-QJwCYIg4ZSSSAAA-8XNYAAA95CW4Gef-QJwCYIg4ZSSSAAA-5CC-\",\"parentName\":\"Adele Vance-Inbox\",\"name\":\"You have late tasks!\",\"folderTitle\":\"Inbox\"}]', NULL, '4f807f98-7206-4edc-a2f7-51ff351b88d7', 'Adele Vance', 'item', 'Success', '{\"failedItemsCount\": 0, \"mergedItemsCount\": 3, \"createdItemsCount\": 0, \"skippedItemsCount\": 0}', NULL, NULL, NULL, '6 seconds', NULL, '2021-08-10 10:13:00', '2021-08-10 10:13:09'),
(4, 4, '[{\"id\":\"LgAAAEYAAAAWAAAAAAAAAC0lne3awQFAnSduIU7fbo8BAD3kJbgZ5_5AnAJgiDhlJJIAAAAAAQwAAAAAAAAtJZ3t2sEBQJ0nbiFO326PBwA95CW4Gef-QJwCYIg4ZSSSAAAAAAEMAAA95CW4Gef-QJwCYIg4ZSSSAABCHmAgAAA95CW4Gef-QJwCYIg4ZSSSAABCEQ9q\",\"parentName\":\"Adele Vance-Inbox\",\"name\":\"You have late tasks!\",\"folderTitle\":\"Inbox\"},{\"id\":\"LgAAAEYAAAAWAAAAAAAAAC0lne3awQFAnSduIU7fbo8BAD3kJbgZ5_5AnAJgiDhlJJIAAAAAAQwAAAAAAAAtJZ3t2sEBQJ0nbiFO326PBwA95CW4Gef-QJwCYIg4ZSSSAAAAAAEMAAA95CW4Gef-QJwCYIg4ZSSSAABBPSEHAAA95CW4Gef-QJwCYIg4ZSSSAABBL9BW\",\"parentName\":\"Adele Vance-Inbox\",\"name\":\"You have late tasks!\",\"folderTitle\":\"Inbox\"},{\"id\":\"LgAAAEYAAAAWAAAAAAAAAC0lne3awQFAnSduIU7fbo8BAD3kJbgZ5_5AnAJgiDhlJJIAAAAAAQwAAAAAAAAtJZ3t2sEBQJ0nbiFO326PBwA95CW4Gef-QJwCYIg4ZSSSAAAAAAEMAAA95CW4Gef-QJwCYIg4ZSSSAAA-8XNYAAA95CW4Gef-QJwCYIg4ZSSSAAA-5CC-\",\"parentName\":\"Adele Vance-Inbox\",\"name\":\"You have late tasks!\",\"folderTitle\":\"Inbox\"}]', 'Adele Vance-items', '4f807f98-7206-4edc-a2f7-51ff351b88d7', 'Adele Vance', 'item', 'Success', NULL, 'AdeleVanceitems_20210810_101431.pst', 2302976, '2021-08-10 11:21:03', '1 minute 47 seconds', NULL, '2021-08-10 10:13:09', '2021-08-10 11:21:03'),
(5, 5, '[{\"id\":\"LgAAAEYAAAAWAAAAAAAAAC0lne3awQFAnSduIU7fbo8BAD3kJbgZ5_5AnAJgiDhlJJIAAAAAAQwAAAAAAAAtJZ3t2sEBQJ0nbiFO326PBwA95CW4Gef-QJwCYIg4ZSSSAAAAAAEMAAA95CW4Gef-QJwCYIg4ZSSSAABCHmAgAAA95CW4Gef-QJwCYIg4ZSSSAABCEQ9q\",\"parentName\":\"Adele Vance\",\"name\":\"You have late tasks!\",\"folderTitle\":\"Adele Vance\"},{\"id\":\"LgAAAEYAAAAWAAAAAAAAAC0lne3awQFAnSduIU7fbo8BAD3kJbgZ5_5AnAJgiDhlJJIAAAAAAQwAAAAAAAAtJZ3t2sEBQJ0nbiFO326PBwA95CW4Gef-QJwCYIg4ZSSSAAAAAAEMAAA95CW4Gef-QJwCYIg4ZSSSAABBPSEHAAA95CW4Gef-QJwCYIg4ZSSSAABBL9BW\",\"parentName\":\"Adele Vance\",\"name\":\"You have late tasks!\",\"folderTitle\":\"Adele Vance\"},{\"id\":\"LgAAAEYAAAAWAAAAAAAAAC0lne3awQFAnSduIU7fbo8BAD3kJbgZ5_5AnAJgiDhlJJIAAAAAAQwAAAAAAAAtJZ3t2sEBQJ0nbiFO326PBwA95CW4Gef-QJwCYIg4ZSSSAAAAAAEMAAA95CW4Gef-QJwCYIg4ZSSSAAA-8XNYAAA95CW4Gef-QJwCYIg4ZSSSAAA-5CC-\",\"parentName\":\"Adele Vance\",\"name\":\"You have late tasks!\",\"folderTitle\":\"Adele Vance\"}]', NULL, '4f807f98-7206-4edc-a2f7-51ff351b88d7', 'Adele Vance', 'item', 'Success', '{\"failedItemsCount\": 0, \"mergedItemsCount\": 3, \"createdItemsCount\": 0, \"skippedItemsCount\": 0}', NULL, NULL, NULL, '6 seconds', NULL, '2021-08-10 11:20:56', '2021-08-10 11:21:05');

-- --------------------------------------------------------

--
-- Table structure for table `restore_temp_blob_file`
--

DROP TABLE IF EXISTS `restore_temp_blob_file`;
CREATE TABLE IF NOT EXISTS `restore_temp_blob_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `backup_job_id` int(10) UNSIGNED NOT NULL,
  `expiration_time` datetime NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `TEMP_JOB_CONSTRAINT` (`backup_job_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subscription_id` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `timezone` text COLLATE utf8mb4_unicode_ci,
  `step` int(11) NOT NULL,
  `portal_user_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'organization_admin',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `free_trial` tinyint(1) NOT NULL DEFAULT '0',
  `licensed_users_count` int(11) NOT NULL DEFAULT '0',
  `trial_users_count` int(11) NOT NULL DEFAULT '0',
  `license_allowed` int(11) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `send_emails` text COLLATE utf8mb4_unicode_ci,
  `alert` tinyint(1) NOT NULL DEFAULT '0',
  `alert_type` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=233 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `phone`, `company_name`, `tenant_id`, `tenant_name`, `subscription_id`, `password`, `timezone`, `step`, `portal_user_type`, `remember_token`, `free_trial`, `licensed_users_count`, `trial_users_count`, `license_allowed`, `expiry_date`, `send_emails`, `alert`, `alert_type`, `created_at`, `updated_at`) VALUES
(196, 'Lama', 'Youz', 'lama@voila.digital', '0123456789', 'Voila', '6fad61a2-0c0e-445b-8e35-6d7f32658210', 'LamaYouz.onmicrosoft.com', 'CE71209D-093E-4F28-BEEE-8B1EBC34C543', '$2y$10$Abo.sCCsdc.agCFppMjmZu0g5C5v69BLAysYf9HJW.JCgRWP0qb/q', 'Asia/Riyadh', 4, 'organization_admin', NULL, 0, 5, 0, 14, '2021-09-13', NULL, 1, 'LICENSE_EXCEEDED', '2021-02-16 10:38:31', '2021-06-28 14:53:55'),
(227, 'Lama', 'Voila', 'lama1@voila.digital', '0123456789', 'Voila New', '9b00287a-1c27-4ddd-b327-ecb005a9a1c6', 'NewVoilaTest.onmicrosoft.com', '440aa564-f9e4-4797-aed3-0547eafcd5a8', '$2y$10$bNvVelwr58U9Qv8ORj4eUOqw3HZIpPgSdCqyB2mTeUx1462WdTP7S', 'Europe/Dublin', 4, 'organization_admin', NULL, 1, 0, 0, 20, '2021-09-13', NULL, 0, NULL, '2021-06-05 11:17:34', '2021-06-05 11:26:02'),
(230, 'Enes', 'Kabakibo', 'achaban@ctelecoms.com.sa', '055225175587', 'Yakut', '979f9235-6f0c-41a4-8eaf-efb2a7160532', 'Yakuttest123.onmicrosoft.com', 'af1a2fe5-fd44-4f55-83ab-1cf572754b48', '$2y$10$SzKlASwFRu6Pk7RsELFVn.SNHYQA8PAE8Pr72rIGD6HHFGsQLaF2i', 'Europe/Istanbul', 4, 'organization_admin', NULL, 1, 0, 20, 20, '2021-09-13', 'achaban@ctelecoms.com.sa', 0, NULL, '2021-06-23 11:55:20', '2021-06-30 16:36:07'),
(231, 'Test', 'test', 'lama555@voila.digital', '01234564789', 'Test', 'be42ec36-a4df-418e-8746-ca82f197ffe3', 'newNewVoila.onmicrosoft.com', '0c0a4e6e-09c7-4cb0-8f47-3bd611d4f4da', '$2y$10$w3EYkhv5/uMJPmIdIVa7T.MFUNtZDeqKr7P9QuCbhm6DW/NgN3j5q', 'Europe/Dublin', 4, 'organization_admin', NULL, 1, 20, 0, 20, '2021-09-13', 'lama555@voila.digital', 0, NULL, '2021-06-23 16:58:15', '2021-08-10 14:40:47'),
(232, 'Test', '1223', 'cspadmin@ctelecoms.com.sa', '0567414963', 'Maaden Demo', '3bfa2b5e-fdd6-4b9f-a8c0-52d0f6fc97b6', 'maadentest123.onmicrosoft.com', NULL, '$2y$10$with/upyaB3XmQJqsJUnv.c4FueT.snJ4p4aKSh5TUr9KyMZ94i.i', 'Europe/Dublin', 3, 'organization_admin', NULL, 1, 0, 0, 20, '2021-09-13', NULL, 0, NULL, '2021-06-30 12:10:48', '2021-06-30 12:12:22');

-- --------------------------------------------------------

--
-- Table structure for table `user_notifications`
--

DROP TABLE IF EXISTS `user_notifications`;
CREATE TABLE IF NOT EXISTS `user_notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `notification_id` int(11) NOT NULL,
  `teams` tinyint(1) DEFAULT NULL,
  `email` tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `NOTIFICATION_FK` (`notification_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `user_notifications`
--

INSERT INTO `user_notifications` (`id`, `user_id`, `notification_id`, `teams`, `email`, `created_at`, `updated_at`) VALUES
(7, 196, 3, 1, 1, '2021-05-22 17:45:34', '2021-05-25 11:48:41'),
(8, 196, 4, 1, 1, '2021-05-22 17:45:34', '2021-05-25 11:48:41'),
(9, 230, 3, 0, 1, '2021-06-30 11:34:33', '2021-06-30 16:30:32'),
(10, 230, 4, 0, 1, '2021-06-30 11:34:33', '2021-06-30 16:30:32'),
(11, 230, 5, 0, 0, '2021-06-30 11:34:34', '2021-06-30 16:30:32'),
(12, 230, 6, 0, 0, '2021-06-30 11:34:34', '2021-06-30 16:30:33'),
(13, 231, 3, 1, 1, '2021-08-10 17:40:47', '2021-08-10 17:40:47'),
(14, 231, 4, 1, 1, '2021-08-10 17:40:47', '2021-08-10 17:40:47'),
(15, 231, 5, 1, 1, '2021-08-10 17:40:47', '2021-08-10 17:40:47'),
(16, 231, 6, 1, 1, '2021-08-10 17:40:47', '2021-08-10 17:40:47');

-- --------------------------------------------------------

--
-- Table structure for table `veeam_backup_job`
--

DROP TABLE IF EXISTS `veeam_backup_job`;
CREATE TABLE IF NOT EXISTS `veeam_backup_job` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cloud_id` varchar(191) NOT NULL,
  `repository_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(200) NOT NULL,
  `size` int(10) DEFAULT '0',
  `descr` text,
  `backedup_users` int(10) DEFAULT NULL,
  `backedup_groups` int(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `repository_id` (`repository_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 TABLESPACE `innodb_system`;

--
-- Dumping data for table `veeam_backup_job`
--

INSERT INTO `veeam_backup_job` (`id`, `cloud_id`, `repository_id`, `name`, `size`, `descr`, `backedup_users`, `backedup_groups`, `created_at`, `updated_at`) VALUES
(2, '93854dd7-d903-49d2-bea5-22c6404c3c94', 2, 'New Onedrive Job', 0, 'test', NULL, NULL, '2021-05-17 10:14:50', '2021-05-30 06:59:54'),
(3, '95b9a17e-0eeb-4038-ae7a-1c0b688d9636', 3, 'New Teams Job', 0, 'test', NULL, NULL, '2021-05-17 10:15:49', '2021-05-30 06:59:54'),
(4, '99b3c8e3-dbbf-4ecc-8dc5-66864277a856', 4, 'Sharepoint job 3', 0, 'test', NULL, NULL, '2021-05-17 10:16:24', '2021-05-30 06:59:54'),
(5, '73e8e1af-56ef-4953-bbb6-e4f36db3626a', 2, 'OneDrive', 0, 'ddwdwdw', NULL, NULL, '2021-05-17 10:18:27', '2021-05-30 06:59:54'),
(8, '335467bf-b7ee-4f03-b961-f2eb9bfa1c15', 2, 'New OneDrive Job', 0, 'New OneDrive Job', 3, 0, '2021-05-23 11:30:36', '2021-05-30 06:59:54'),
(9, 'ca901cab-a4bc-476c-bbad-dbe1e272360f', 1, 'Exchange Job 2', 0, 'Exchange Job 2', 0, 0, '2021-05-24 12:09:17', '2021-05-30 06:59:54'),
(10, '17d64f6c-7e19-4ade-8f48-aaa9873a3258', 3, 'New Teams Job', 0, 'New Teams Job', NULL, NULL, '2021-05-31 10:03:30', '2021-05-31 10:03:30'),
(11, 'c4046430-7431-499b-9108-ef880c1c2b81', 2, 'Lama backup Job', 0, 'Lama backup Job', 0, 0, '2021-06-01 10:51:05', '2021-06-01 12:56:20'),
(12, '1c74376c-4d3b-4808-b769-d904938e21b3', 1, 'GEMS Backup test', 0, 'GEMS Backup test', 4, 0, '2021-06-13 12:44:33', '2021-06-13 12:44:33'),
(13, 'c1a13284-e253-4061-a8c7-ecdf2183d110', 5, 'Exc-Test1', 0, 'AllUsers', 0, 0, '2021-06-27 13:04:46', '2021-06-27 13:04:46'),
(14, 'fd01b140-7f22-4a3e-a167-091dc0680175', 6, 'OneDrive-Test1-AllUsers', 0, 'OneDrive-Test1-AllUsers', 0, 0, '2021-06-27 13:17:33', '2021-06-27 13:20:20'),
(15, '503c07de-4fe1-4192-bc0c-0cf6e86b0a2b', 7, 'SharePoinet-Test1-All Sites', 0, 'All Sites', 0, 0, '2021-06-27 13:34:38', '2021-06-27 13:34:38'),
(16, '03440588-2957-4dd9-940c-f900ba78bcfd', 5, 'Exchange-Test2-AllUsers', 0, 'AllUsers', 0, 0, '2021-06-27 13:37:41', '2021-06-27 13:37:41'),
(17, 'd3f3c03b-fb9f-40ec-8c27-209b74c06021', 8, 'Teams-Test1-All team', 0, 'All Team', 0, 0, '2021-06-28 13:28:34', '2021-06-28 13:28:34'),
(18, '0facc286-2444-47ca-b1f1-8a96e6e82d3f', 1, 'exe backup job', 0, 'exe backup job', 2, 1, '2021-06-28 14:53:56', '2021-06-28 14:53:56'),
(19, '848e2a9d-aaab-4c31-9d13-5896ddb4d39f', 1, 'Maaden Exchange IT Test Backup Job', 0, 'Maaden Exchange IT Test Backup Job', 1, 1, '2021-06-30 12:23:41', '2021-06-30 12:23:41'),
(20, 'b621c14e-4821-4218-9b66-52bd871faac1', 12, 'Exch-NewStorage-Test1', 0, 'test', 1, 0, '2021-06-30 16:36:07', '2021-06-30 16:36:07'),
(21, '0259e4a6-87a4-4b65-866f-8874a7d82bca', 5, 'Exch-Test2', 0, 'test', 1, 0, '2021-06-30 16:38:04', '2021-06-30 16:38:04'),
(22, 'd7f9ae72-acbf-4b26-9d66-92d254b516b8', 14, 'Exchange Job New', 0, 'test', 0, 0, '2021-08-02 14:00:52', '2021-08-02 14:00:52'),
(23, '54441a8a-f76e-4ba8-835e-c836e54ffc4e', 15, 'Lama Onedrive Job', 0, 'Lama Onedrive Job', 0, 0, '2021-08-07 11:21:46', '2021-08-07 11:21:46'),
(24, 'b17ba480-6fce-4796-9d3b-a32e6eefc33f', 16, 'Lama Sharepoint Job', 0, 'Lama Sharepoint Job', 0, 0, '2021-08-08 12:54:59', '2021-08-08 12:54:59'),
(25, '1ee55334-2be4-418b-81f8-c48a7d32d35b', 4, 'New Lama Sharepoint Job', 0, 'New Lama Sharepoint Job', 0, 0, '2021-08-09 11:35:10', '2021-08-09 11:35:10'),
(26, '813390ce-50ab-4818-b383-1c1b517bad42', 16, 'New Lama Sharepoint Job', 0, 'New Lama Sharepoint Job', 0, 1, '2021-08-09 11:38:43', '2021-08-09 11:38:43'),
(27, '1157a261-0ecf-440f-b243-72d0b86c9b09', 17, 'Lama Teams Job', 0, 'Lama Teams Job', 0, 0, '2021-08-10 10:33:33', '2021-08-10 10:33:33');

-- --------------------------------------------------------

--
-- Table structure for table `veeam_backup_repository`
--

DROP TABLE IF EXISTS `veeam_backup_repository`;
CREATE TABLE IF NOT EXISTS `veeam_backup_repository` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cloud_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `veeam_object_storage_id` int(10) UNSIGNED NOT NULL,
  `repository_kind` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proxy_id` int(10) DEFAULT NULL,
  `organization_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `veeam_object_storage_id` (`veeam_object_storage_id`),
  KEY `proxy_id` (`proxy_id`),
  KEY `orgnization_id` (`organization_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `veeam_backup_repository`
--

INSERT INTO `veeam_backup_repository` (`id`, `cloud_id`, `name`, `display_name`, `veeam_object_storage_id`, `repository_kind`, `proxy_id`, `organization_id`, `created_at`, `updated_at`) VALUES
(1, '9aea8718-03fb-412c-b061-a3f65b4bb231', 'co365lamayouzn05', 'Exchange Rep', 6, 'Exchange', 1, 1, '2021-05-30 06:59:54', '2021-07-10 14:09:08'),
(2, 'cbbaf024-0376-4faa-b023-e1fd6dc4ced4', 'co365lamayouzn06', 'OneDrive Repo', 13, 'OneDrive', 1, 1, '2021-05-30 06:59:54', '2021-07-10 14:09:08'),
(3, '43566203-9668-4e90-a16c-4cce75b7d26e', 'co365lamayouzn53', 'New Teams Repo', 15, 'Teams', 1, 1, '2021-05-30 06:59:54', '2021-07-10 14:09:08'),
(4, 'd5817d9e-4dbd-4881-90af-27e6cecbbfdd', 'co365lamayouzn07', 'test', 16, 'SharePoint', 1, 1, '2021-05-30 06:59:54', '2021-07-10 14:09:08'),
(5, '7423dc8d-408b-4a72-81ba-19e4ce495dee', 'co365yakuttest123n01', 'Yakut-Storage1', 27, 'Exchange', 1, 23, '2021-06-27 12:46:18', '2021-07-10 14:09:08'),
(6, '3793cc5c-a655-4146-be38-4cda0083acef', 'co365yakuttest123n02', 'Yakut-Storage2', 28, 'OneDrive', 1, 23, '2021-06-27 13:06:11', '2021-07-10 14:09:08'),
(7, '5c11a954-677f-4794-a59d-8e1dd66e86ff', 'co365yakuttest123n03', 'Yakut-Storage3', 29, 'SharePoint', 1, 23, '2021-06-27 13:21:56', '2021-07-10 14:09:08'),
(8, 'fb1953a7-52cb-4551-9f70-095b6a723cef', 'co365yakuttest123n04', 'Yakut-Storage4', 30, 'Teams', 1, 23, '2021-06-28 13:27:02', '2021-07-10 14:09:08'),
(9, 'eea3b3a3-2244-49b7-bd0d-978680567945', 'co365lamayouzn22', 'Email backup for Exeuctive', 31, 'Exchange', 1, 1, '2021-06-28 14:51:55', '2021-07-10 14:09:08'),
(10, '93f83400-f2c3-4772-bf07-25168d23819a', 'co365lamayouzn23', 'abdulrahman test', 32, 'Exchange', 1, 1, '2021-06-28 15:53:17', '2021-07-10 14:09:08'),
(11, '96719a5f-28e5-4658-864c-6c76bc4a5145', 'co365lamayouzn24', 'Exchange backup for IT', 33, 'Exchange', 1, 1, '2021-06-30 12:19:44', '2021-07-10 14:09:08'),
(12, 'aabbbff2-8d8a-4991-ad0e-b204c18de3bf', 'co365yakuttest123n05', 'Yakut-Storage1-1', 34, 'Exchange', 1, 23, '2021-06-30 16:33:13', '2021-07-10 14:09:08'),
(13, '4e02a866-8e03-4db2-845d-730c44f19dd5', 'co365lamayouzn25', 'Lama Exchange Repo', 35, 'Exchange', 1, 1, '2021-07-28 08:28:58', '2021-07-28 08:28:58'),
(14, '2a84b36b-1473-4049-b968-46bf5d4f6d67', 'co365newnewvoilan02', 'Exchange Repo New', 36, 'Exchange', 1, 22, '2021-08-02 13:59:43', '2021-08-02 13:59:43'),
(15, 'fe904770-000b-4d03-800d-f0351e774b14', 'co365newnewvoilan04', 'Lama Onedrive Repo', 37, 'OneDrive', 1, 22, '2021-08-07 11:20:49', '2021-08-07 11:20:49'),
(16, '5073dd8e-2e61-4f2e-bc52-b1f05c8ded87', 'co365newnewvoilan05', 'Lama Sharepoint Repo', 38, 'SharePoint', 1, 22, '2021-08-08 12:50:49', '2021-08-08 12:50:49'),
(17, '929be89a-a452-45ba-a1e7-8f0e7bac4757', 'co365newnewvoilan06', 'Lama Teams Repo', 39, 'Teams', 1, 22, '2021-08-10 10:31:33', '2021-08-10 10:31:33');

-- --------------------------------------------------------

--
-- Table structure for table `veeam_cloud_credentials_account`
--

DROP TABLE IF EXISTS `veeam_cloud_credentials_account`;
CREATE TABLE IF NOT EXISTS `veeam_cloud_credentials_account` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `azure_storage_account_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cloud_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `accountType` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `azure_storage_account_id` (`azure_storage_account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `veeam_cloud_credentials_account`
--

INSERT INTO `veeam_cloud_credentials_account` (`id`, `azure_storage_account_id`, `name`, `cloud_id`, `accountType`, `description`, `created_at`, `updated_at`) VALUES
(1, 4, 'co365lamayouzn04', '30ee619d-fd07-4fe4-bb58-ff4ef70695c0', 'azureBlobAccount', 'A new Azure Account via portal', '2021-05-30 06:59:54', '2021-07-10 14:09:08'),
(2, 5, 'co365lamayouzn05', 'cdb38f58-8880-48d5-b5f6-f1b92285ed8b', 'azureBlobAccount', 'A new Azure Account via portal', '2021-05-30 06:59:54', '2021-07-10 14:09:08'),
(3, 6, 'co365lamayouzn06', '0c1f02c7-2d88-413a-9f58-9636d6d1cdfc', 'azureBlobAccount', 'A new Azure Account via portal', '2021-05-30 06:59:54', '2021-07-10 14:09:08'),
(4, 7, 'co365lamayouzn07', 'aed96be1-43a9-47d7-9037-f2d973552253', 'azureBlobAccount', 'A new Azure Account via portal', '2021-05-30 06:59:54', '2021-07-10 14:09:08'),
(5, 8, 'co365lamayouzn08', '22d895f0-f803-4e12-b606-1169ea68af81', 'azureBlobAccount', 'A new Azure Account via portal', '2021-05-30 06:59:54', '2021-07-10 14:09:08'),
(6, 9, 'co365lamayouzn09', '2ef73db5-4eb3-4cc2-8f3c-4a8386e57ec4', 'azureBlobAccount', 'A new Azure Account via portal', '2021-05-30 06:59:54', '2021-07-10 14:09:08'),
(7, 10, 'co365lamayouzn17', '9b3cf121-4b28-487a-bda7-ef8fbaecb77a', 'azureBlobAccount', 'A new Azure Account via portal', '2021-05-30 06:59:54', '2021-07-10 14:09:08'),
(8, 11, 'co365lamayouzn18', '28e78bed-51e1-4a03-b35b-2e9815b3770d', 'azureBlobAccount', 'A new Azure Account via portal', '2021-05-30 06:59:54', '2021-07-10 14:09:08'),
(9, 11, 'co365lamayouzn18', 'e3cb573f-340d-4c26-93e3-497d861d1a5f', 'azureBlobAccount', 'A new Azure Account via portal', '2021-05-30 06:59:54', '2021-07-10 14:09:08'),
(10, 11, 'co365lamayouzn18', '3683c281-a39e-43f5-a720-d95a0c6145d3', 'azureBlobAccount', 'A new Azure Account via portal', '2021-05-30 06:59:54', '2021-07-10 14:09:08'),
(11, 12, 'co365lamayouzn40', 'ddf88e4b-1cb9-489a-9140-5b763dc71fc1', 'azureBlobAccount', 'A new Azure Account via portal', '2021-05-30 06:59:54', '2021-07-10 14:09:08'),
(12, 13, 'co365lamayouzn50', '0590c23d-0d50-44eb-93ce-915518bfe664', 'azureBlobAccount', 'A new Azure Account via portal', '2021-05-30 06:59:54', '2021-07-10 14:09:08'),
(13, 14, 'co365lamayouzn51', '5df87fcd-f314-4b55-9222-f79065f8e183', 'azureBlobAccount', 'A new Azure Account via portal', '2021-05-30 06:59:54', '2021-07-10 14:09:08'),
(14, 15, 'co365lamayouzn52', '27b69091-f515-48e7-ac5f-3fd5e9869080', 'azureBlobAccount', 'A new Azure Account via portal', '2021-05-30 06:59:54', '2021-07-10 14:09:08'),
(15, 16, 'co365lamayouzn53', '0c1b04c6-0e7c-4a40-8553-c9b02c88fe89', 'azureBlobAccount', 'A new Azure Account via portal', '2021-05-30 06:59:54', '2021-07-10 14:09:08'),
(16, 17, 'co365lamayouzn54', '100cf561-bb5f-4a27-8466-3661bb0a02fa', 'azureBlobAccount', 'A new Azure Account via portal', '2021-05-30 06:59:54', '2021-07-10 14:09:08'),
(17, 18, 'co365lamayouzn63', '0920d8bf-eb10-41f5-8cb3-9c5391deca3c', 'azureBlobAccount', 'A new Azure Account via portal', '2021-05-30 06:59:54', '2021-07-10 14:09:08'),
(18, 19, 'co365lamayouzn64', '7becc883-4263-4e24-ae1b-6ee802c26704', 'azureBlobAccount', 'A new Azure Account via portal', '2021-05-30 06:59:54', '2021-07-10 14:09:08'),
(19, 20, 'co365lamayouzn65', 'e7cabb60-db03-4080-ae27-2797f3eaf88f', 'azureBlobAccount', 'A new Azure Account via portal', '2021-05-30 06:59:54', '2021-07-10 14:09:08'),
(20, 21, 'co365lamayouzn66', 'a0f5012c-9f04-4945-9e12-ab64641394f3', 'azureBlobAccount', 'A new Azure Account via portal', '2021-05-30 06:59:54', '2021-07-10 14:09:08'),
(27, 28, 'co365yakuttest123n01', '9c49af9c-9a1e-4fe5-a5ab-f2b97e81843d', 'azureBlobAccount', 'portal', '2021-06-27 12:46:15', '2021-07-10 14:09:08'),
(28, 29, 'co365yakuttest123n02', '865b734a-b08a-44aa-8137-835b409fb7ea', 'azureBlobAccount', 'portal', '2021-06-27 13:06:08', '2021-07-10 14:09:08'),
(29, 30, 'co365yakuttest123n03', '1d72f70a-33ed-498f-870f-b768d9a9e765', 'azureBlobAccount', 'portal', '2021-06-27 13:21:53', '2021-07-10 14:09:08'),
(30, 31, 'co365yakuttest123n04', 'e0a5e53c-7429-4f78-b72e-db38288d4b97', 'azureBlobAccount', 'portal', '2021-06-28 13:26:59', '2021-07-10 14:09:08'),
(31, 32, 'co365lamayouzn22', 'bec5191c-46b5-4f4c-9f1e-9c9acf36eefc', 'azureBlobAccount', 'portal', '2021-06-28 14:51:51', '2021-07-10 14:09:08'),
(32, 33, 'co365lamayouzn23', 'a50f75ef-42e8-4356-a9fd-19e89c17be48', 'azureBlobAccount', 'portal', '2021-06-28 15:53:14', '2021-07-10 14:09:08'),
(33, 34, 'co365lamayouzn24', '3d1db675-eb2f-4aba-aea0-73df1981e4e4', 'azureBlobAccount', 'portal', '2021-06-30 12:19:40', '2021-07-10 14:09:08'),
(34, 35, 'co365yakuttest123n05', '84284bbf-7850-48f3-8a5e-6a06048a282f', 'azureBlobAccount', 'portal', '2021-06-30 16:33:10', '2021-07-10 14:09:08'),
(35, 36, 'co365lamayouzn25', '265dac4f-8927-4e22-9656-52528c3216d1', 'azureBlobAccount', 'portal', '2021-07-28 08:28:52', '2021-07-28 08:28:52'),
(36, 37, 'co365newnewvoilan01', '981cdcbc-30fd-431d-b7ca-f09ed7a3aa37', 'azureBlobAccount', 'portal', '2021-08-02 13:55:48', '2021-08-02 13:55:48'),
(37, 38, 'co365newnewvoilan02', '4d3d1c06-7663-44a4-aa43-24b8dcff887c', 'azureBlobAccount', 'portal', '2021-08-02 13:59:38', '2021-08-02 13:59:38'),
(38, 39, 'co365newnewvoilan03', 'bb99f2a3-ce37-4b8c-b5e5-36df888e7092', 'azureBlobAccount', 'portal', '2021-08-07 11:19:08', '2021-08-07 11:19:08'),
(39, 40, 'co365newnewvoilan04', 'af7c9650-aab1-4106-a56c-3976df695efa', 'azureBlobAccount', 'portal', '2021-08-07 11:20:37', '2021-08-07 11:20:37'),
(40, 41, 'co365newnewvoilan05', 'a8e37e02-c381-4863-95d0-698a3ccca82a', 'azureBlobAccount', 'portal', '2021-08-08 12:50:36', '2021-08-08 12:50:36'),
(41, 42, 'co365newnewvoilan06', '7a1275bb-0fe7-4595-9363-67d8498982f7', 'azureBlobAccount', 'portal', '2021-08-10 10:31:25', '2021-08-10 10:31:25');

-- --------------------------------------------------------

--
-- Table structure for table `veeam_folder`
--

DROP TABLE IF EXISTS `veeam_folder`;
CREATE TABLE IF NOT EXISTS `veeam_folder` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `veeam_account_id` int(10) UNSIGNED NOT NULL,
  `azure_container_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `azure_container_id` (`azure_container_id`),
  KEY `veeam_account_id` (`veeam_account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `veeam_folder`
--

INSERT INTO `veeam_folder` (`id`, `veeam_account_id`, `azure_container_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 2, 9, 'Exchange Rep', '2021-05-30 06:59:54', '2021-07-10 14:09:08'),
(2, 3, 13, 'OneDrive Repo', '2021-05-30 06:59:54', '2021-07-10 14:09:08'),
(3, 15, 37, 'New Teams Repo', '2021-05-30 06:59:54', '2021-07-10 14:09:08'),
(4, 4, 16, 'test', '2021-05-30 06:59:54', '2021-07-10 14:09:08'),
(11, 27, 62, 'Yakut-Storage1', '2021-06-27 12:46:15', '2021-07-10 14:09:08'),
(12, 28, 64, 'Yakut-Storage2', '2021-06-27 13:06:09', '2021-07-10 14:09:08'),
(13, 29, 66, 'Yakut-Storage3', '2021-06-27 13:21:54', '2021-07-10 14:09:08'),
(14, 30, 68, 'Yakut-Storage4', '2021-06-28 13:26:59', '2021-07-10 14:09:08'),
(15, 31, 70, 'Email backup for Exeuctive', '2021-06-28 14:51:52', '2021-07-10 14:09:08'),
(16, 32, 72, 'abdulrahman test', '2021-06-28 15:53:15', '2021-07-10 14:09:08'),
(17, 33, 74, 'Exchange backup for IT', '2021-06-30 12:19:40', '2021-07-10 14:09:08'),
(18, 34, 76, 'Yakut-Storage1-1', '2021-06-30 16:33:11', '2021-07-10 14:09:08'),
(19, 35, 78, 'Lama Exchange Repo', '2021-07-28 08:28:54', '2021-07-28 08:28:54'),
(20, 37, 80, 'Exchange Repo New', '2021-08-02 13:59:38', '2021-08-02 13:59:38'),
(21, 38, 82, 'Lama Onedrive Repo', '2021-08-07 11:19:10', '2021-08-07 11:19:10'),
(22, 39, 84, 'Lama Onedrive Repo', '2021-08-07 11:20:39', '2021-08-07 11:20:39'),
(23, 40, 86, 'Lama Sharepoint Repo', '2021-08-08 12:50:38', '2021-08-08 12:50:38'),
(24, 41, 88, 'Lama Teams Repo', '2021-08-10 10:31:26', '2021-08-10 10:31:26');

-- --------------------------------------------------------

--
-- Table structure for table `veeam_object_storage`
--

DROP TABLE IF EXISTS `veeam_object_storage`;
CREATE TABLE IF NOT EXISTS `veeam_object_storage` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cloud_id` varchar(191) NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `veeam_account_id` int(10) UNSIGNED NOT NULL,
  `azure_container_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `azure_container_id` (`azure_container_id`),
  KEY `veeam_account_id` (`veeam_account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 TABLESPACE `innodb_system`;

--
-- Dumping data for table `veeam_object_storage`
--

INSERT INTO `veeam_object_storage` (`id`, `cloud_id`, `name`, `veeam_account_id`, `azure_container_id`, `created_at`, `updated_at`) VALUES
(1, '0d2f5454-136d-485c-845b-025ae1b56205', 'co365lamayouzn52', 14, 35, '2021-05-30 06:59:54', '2021-05-17 10:10:07'),
(2, '3d01e683-6df2-4628-ac79-0deb25802434', 'co365lamayouzn04', 1, 7, '2021-05-30 06:59:54', '2021-05-17 10:10:07'),
(3, '9d1a47b0-5417-40b0-b314-2e528e9757e6', 'co365lamayouzn66', 20, 47, '2021-05-30 06:59:54', '2021-05-17 10:10:07'),
(4, '54f3b736-d2f7-4d7a-b903-333d357b576f', 'co365lamayouzn54', 16, 39, '2021-05-30 06:59:54', '2021-05-17 10:10:07'),
(5, 'a2eb2123-5394-4583-8e8e-3c45030178c4', 'co365lamayouzn08', 5, 19, '2021-05-30 06:59:54', '2021-05-17 10:10:07'),
(6, 'c4721e2c-19f2-474f-853c-48af88845729', 'co365lamayouzn05', 2, 9, '2021-05-30 06:59:54', '2021-05-17 10:10:07'),
(7, 'efc72cc8-8472-4523-9484-4c9f683f7ce1', 'co365lamayouzn65', 19, 45, '2021-05-30 06:59:54', '2021-05-17 10:10:07'),
(8, '556cefde-b99b-4f11-bf59-52466fdf5c10', 'co365lamayouzn17', 7, 23, '2021-05-30 06:59:54', '2021-05-17 10:10:07'),
(9, '9fa38b85-2c5a-43a5-a365-6fc03058df51', 'co365lamayouzn64', 18, 43, '2021-05-30 06:59:54', '2021-05-17 10:10:07'),
(10, 'e50170d4-63c1-40c2-b076-75d0377f4247', 'co365lamayouzn51', 13, 33, '2021-05-30 06:59:54', '2021-05-17 10:10:07'),
(11, '61a92146-a095-4e81-a13e-822e62261a0b', 'co365lamayouzn50', 12, 31, '2021-05-30 06:59:54', '2021-05-17 10:10:07'),
(12, 'ed933d6e-e1cf-4f9d-8855-8c60cc427463', 'co365lamayouzn09', 6, 21, '2021-05-30 06:59:54', '2021-05-17 10:10:07'),
(13, '8be867df-b580-4010-b1e3-9c960fb3a5b5', 'co365lamayouzn06', 3, 13, '2021-05-30 06:59:54', '2021-05-17 10:10:07'),
(14, '0de7df65-a99b-41dc-b689-ceb3132ce8f2', 'co365lamayouzn40', 11, 29, '2021-05-30 06:59:54', '2021-05-17 10:10:07'),
(15, 'fe1c5f8f-abf6-4656-b9ec-d456fab4ba6e', 'co365lamayouzn53', 15, 37, '2021-05-30 06:59:54', '2021-05-17 10:10:07'),
(16, '4a90a812-f050-40b8-a04f-daae688d22a5', 'co365lamayouzn07', 4, 16, '2021-05-30 06:59:54', '2021-05-17 10:10:07'),
(17, '713df769-64a7-4dd8-8a1e-e62b3e60df4a', 'New Test Object Storage', 2, 11, '2021-05-30 06:59:54', '2021-05-17 10:10:07'),
(18, 'd3a2a6be-bcb2-433d-82ad-f4a45d9bc1be', 'co365lamayouzn18', 9, 25, '2021-05-30 06:59:54', '2021-05-17 10:10:08'),
(19, '021d0e7a-2229-43bb-8e9b-fdceb2609476', 'co365lamayouzn63', 17, 41, '2021-05-30 06:59:54', '2021-05-17 10:10:08'),
(27, '1b8f37ff-8af4-4c1b-8f77-953c98540edf', 'co365yakuttest123n01', 27, 62, '2021-06-27 12:46:16', '2021-06-27 12:46:16'),
(28, '3947ec0e-0681-4a1f-89a7-db13a1d90dd1', 'co365yakuttest123n02', 28, 64, '2021-06-27 13:06:09', '2021-06-27 13:06:09'),
(29, '4f5144f9-fa7d-48e9-8a96-053a8be44334', 'co365yakuttest123n03', 29, 66, '2021-06-27 13:21:54', '2021-06-27 13:21:54'),
(30, 'a8793683-8586-4fea-a1cf-d49b8d903a62', 'co365yakuttest123n04', 30, 68, '2021-06-28 13:27:00', '2021-06-28 13:27:00'),
(31, 'b10191cd-3e73-4e31-91ad-6a4e090ea881', 'co365lamayouzn22', 31, 70, '2021-06-28 14:51:53', '2021-06-28 14:51:53'),
(32, '7f44d2d1-8ae0-4eb9-9280-3b694932601d', 'co365lamayouzn23', 32, 72, '2021-06-28 15:53:15', '2021-06-28 15:53:15'),
(33, 'e291145d-b0e8-4270-b29c-adc8790dd091', 'co365lamayouzn24', 33, 74, '2021-06-30 12:19:41', '2021-06-30 12:19:41'),
(34, '4cb7bf89-70ff-4148-b5d9-86d9b3a5b97e', 'co365yakuttest123n05', 34, 76, '2021-06-30 16:33:11', '2021-06-30 16:33:11'),
(35, '0b1d6284-9b0b-4bdf-8d41-38322d06cd34', 'co365lamayouzn25', 35, 78, '2021-07-28 08:28:55', '2021-07-28 08:28:55'),
(36, 'a65724b2-1724-43b3-a92e-788114d316ca', 'co365newnewvoilan02', 37, 80, '2021-08-02 13:59:40', '2021-08-02 13:59:40'),
(37, '0a0bd0bd-ec3e-46c5-b909-34b10e438ae5', 'co365newnewvoilan04', 39, 84, '2021-08-07 11:20:42', '2021-08-07 11:20:42'),
(38, '197aea1d-c4ab-4198-ab66-487c502b3b63', 'co365newnewvoilan05', 40, 86, '2021-08-08 12:50:42', '2021-08-08 12:50:42'),
(39, 'b49f820d-6250-4d8b-b272-962cd6012dfa', 'co365newnewvoilan06', 41, 88, '2021-08-10 10:31:29', '2021-08-10 10:31:29');

-- --------------------------------------------------------

--
-- Table structure for table `veeam_proxies`
--

DROP TABLE IF EXISTS `veeam_proxies`;
CREATE TABLE IF NOT EXISTS `veeam_proxies` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `proxy_key` varchar(200) NOT NULL,
  `proxy_name` varchar(200) DEFAULT NULL,
  `veeam_server_id` int(10) NOT NULL,
  `cache_path` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `veeam_server_id` (`veeam_server_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 TABLESPACE `innodb_system`;

--
-- Dumping data for table `veeam_proxies`
--

INSERT INTO `veeam_proxies` (`id`, `proxy_key`, `proxy_name`, `veeam_server_id`, `cache_path`) VALUES
(1, 'ec28e9d0-3db7-4c40-88de-4ced25b55b52', 'Veeam365-VM', 1, 'D:');

-- --------------------------------------------------------

--
-- Table structure for table `veeam_server`
--

DROP TABLE IF EXISTS `veeam_server`;
CREATE TABLE IF NOT EXISTS `veeam_server` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `veeam_username` varchar(200) NOT NULL,
  `veeam_password` varchar(200) NOT NULL,
  `veeam_url` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `veeam_server`
--

INSERT INTO `veeam_server` (`id`, `veeam_username`, `veeam_password`, `veeam_url`) VALUES
(1, 'Veeam365-VM\\veeamadmin', 'V##@M@word123', 'https://ctcvbo365development.ctelecoms.com.sa:4443/v5');

-- --------------------------------------------------------

--
-- Table structure for table `verification_codes`
--

DROP TABLE IF EXISTS `verification_codes`;
CREATE TABLE IF NOT EXISTS `verification_codes` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `license_count` int(6) NOT NULL,
  `license_period` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activation_date` datetime DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) NOT NULL,
  `verification_code_period_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `verification_codes_verification_code_type_id_foreign` (`verification_code_period_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `verification_codes`
--

INSERT INTO `verification_codes` (`id`, `code`, `license_count`, `license_period`, `activation_date`, `expiry_date`, `user_id`, `active`, `verification_code_period_id`, `created_at`, `updated_at`) VALUES
(4, '2c9de8a5-8307-46be-a9c2-acf3ab926dc8', 12, '1 Month', '2021-05-05 13:59:13', '2021-06-05', 196, 1, 1, '2021-05-02 13:01:16', '2021-05-05 10:59:13'),
(5, 'c15b5628-214e-4f66-ab47-9c7e63c97a2f', 50, '1 Month', '2021-05-02 16:21:07', '2021-06-02', 196, 1, 1, '2021-05-02 13:07:41', '2021-05-02 13:21:07'),
(7, '08e5a8f0-6fe8-4b5f-81b1-6f6eb32ad974', 12, 'Aligned', '2021-05-02 16:16:19', '2021-06-02', 196, 1, NULL, '2021-05-02 13:15:41', '2021-05-02 13:16:19'),
(8, '1f349fa8-a3b2-48a9-b3dc-52cf0057b2f4', 1, '1 Year', '2021-05-02 16:16:58', '2022-05-02', 196, 1, 4, '2021-05-02 13:16:48', '2021-05-02 13:16:58'),
(9, '5ccb463e-3e81-4405-9515-d8367f6e9230', 1, '5 Years', '2021-05-02 16:20:23', '2027-05-02', 196, 1, 6, '2021-05-02 13:20:21', '2021-05-02 13:20:23');

-- --------------------------------------------------------

--
-- Table structure for table `verification_code_period`
--

DROP TABLE IF EXISTS `verification_code_period`;
CREATE TABLE IF NOT EXISTS `verification_code_period` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `verification_code_period`
--

INSERT INTO `verification_code_period` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, '1 Month', NULL, NULL),
(2, '3 Months', NULL, NULL),
(3, '6 Months', NULL, NULL),
(4, '1 Year', NULL, NULL),
(5, '3 Years', NULL, NULL),
(6, '5 Years', NULL, NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `azure_container`
--
ALTER TABLE `azure_container`
  ADD CONSTRAINT `azure_container_ibfk_1` FOREIGN KEY (`azure_storage_account_id`) REFERENCES `azure_storage_account` (`id`);

--
-- Constraints for table `azure_resource_group`
--
ALTER TABLE `azure_resource_group`
  ADD CONSTRAINT `azure_resource_group_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`);

--
-- Constraints for table `azure_storage_account`
--
ALTER TABLE `azure_storage_account`
  ADD CONSTRAINT `azure_storage_account_ibfk_1` FOREIGN KEY (`azure_resource_group_id`) REFERENCES `azure_resource_group` (`id`);

--
-- Constraints for table `ediscovery_jobs`
--
ALTER TABLE `ediscovery_jobs`
  ADD CONSTRAINT `JOB_FK` FOREIGN KEY (`backup_job_id`) REFERENCES `veeam_backup_job` (`id`),
  ADD CONSTRAINT `USER_FK` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `organizations`
--
ALTER TABLE `organizations`
  ADD CONSTRAINT `organizations_ibfk_1` FOREIGN KEY (`veeam_server_id`) REFERENCES `veeam_server` (`id`),
  ADD CONSTRAINT `organizations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `restore_history`
--
ALTER TABLE `restore_history`
  ADD CONSTRAINT `RESTORE_JOB_CONSTRAINT` FOREIGN KEY (`backup_job_id`) REFERENCES `veeam_backup_job` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `restore_history_details`
--
ALTER TABLE `restore_history_details`
  ADD CONSTRAINT `restore_history_details_ibfk_1` FOREIGN KEY (`history_id`) REFERENCES `restore_history` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `restore_temp_blob_file`
--
ALTER TABLE `restore_temp_blob_file`
  ADD CONSTRAINT `TEMP_JOB_CONSTRAINT` FOREIGN KEY (`backup_job_id`) REFERENCES `veeam_backup_job` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD CONSTRAINT `NOTIFICATION_FK` FOREIGN KEY (`notification_id`) REFERENCES `notifications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `veeam_backup_job`
--
ALTER TABLE `veeam_backup_job`
  ADD CONSTRAINT `veeam_backup_job_ibfk_1` FOREIGN KEY (`repository_id`) REFERENCES `veeam_backup_repository` (`id`);

--
-- Constraints for table `veeam_backup_repository`
--
ALTER TABLE `veeam_backup_repository`
  ADD CONSTRAINT `veeam_backup_repository_ibfk_1` FOREIGN KEY (`veeam_object_storage_id`) REFERENCES `veeam_object_storage` (`id`),
  ADD CONSTRAINT `veeam_backup_repository_ibfk_2` FOREIGN KEY (`proxy_id`) REFERENCES `veeam_proxies` (`id`),
  ADD CONSTRAINT `veeam_backup_repository_ibfk_3` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`);

--
-- Constraints for table `veeam_cloud_credentials_account`
--
ALTER TABLE `veeam_cloud_credentials_account`
  ADD CONSTRAINT `veeam_cloud_credentials_account_ibfk_1` FOREIGN KEY (`azure_storage_account_id`) REFERENCES `azure_storage_account` (`id`);

--
-- Constraints for table `veeam_folder`
--
ALTER TABLE `veeam_folder`
  ADD CONSTRAINT `veeam_folder_ibfk_1` FOREIGN KEY (`azure_container_id`) REFERENCES `azure_container` (`id`),
  ADD CONSTRAINT `veeam_folder_ibfk_2` FOREIGN KEY (`veeam_account_id`) REFERENCES `veeam_cloud_credentials_account` (`id`);

--
-- Constraints for table `veeam_object_storage`
--
ALTER TABLE `veeam_object_storage`
  ADD CONSTRAINT `veeam_object_storage_ibfk_1` FOREIGN KEY (`azure_container_id`) REFERENCES `azure_container` (`id`),
  ADD CONSTRAINT `veeam_object_storage_ibfk_2` FOREIGN KEY (`veeam_account_id`) REFERENCES `veeam_cloud_credentials_account` (`id`);

--
-- Constraints for table `veeam_proxies`
--
ALTER TABLE `veeam_proxies`
  ADD CONSTRAINT `veeam_proxies_ibfk_1` FOREIGN KEY (`veeam_server_id`) REFERENCES `veeam_server` (`id`);

--
-- Constraints for table `verification_codes`
--
ALTER TABLE `verification_codes`
  ADD CONSTRAINT `verification_codes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `verification_codes_verification_code_type_id_foreign` FOREIGN KEY (`verification_code_period_id`) REFERENCES `verification_code_period` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
