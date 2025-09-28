-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 28, 2025 at 01:16 PM
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
-- Database: `ura_crm_new`
--

-- --------------------------------------------------------

--
-- Table structure for table `unauthorized_accesses`
--

CREATE TABLE `unauthorized_accesses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `route_name` varchar(255) NOT NULL,
  `url_attempted` varchar(255) NOT NULL,
  `method` varchar(255) NOT NULL DEFAULT 'GET',
  `user_role` varchar(255) DEFAULT NULL,
  `required_roles` varchar(255) DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `user_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`user_details`)),
  `attempted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `unauthorized_accesses`
--

INSERT INTO `unauthorized_accesses` (`id`, `user_id`, `route_name`, `url_attempted`, `method`, `user_role`, `required_roles`, `ip_address`, `user_agent`, `user_details`, `attempted_at`, `created_at`, `updated_at`) VALUES
(1, 42, 'users.security-audit', 'http://127.0.0.1:8000/users/security-audit', 'POST', 'registrar_hq', 'admin,superadmin,system_admin', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', '{\"name\":\"registrar_hq1\",\"email\":\"registrar_hq1@tpf.go.tz\",\"phone_number\":\"255681946659\",\"branch\":\"dodoma\",\"region\":\"Arusha\",\"department\":\"Loan\",\"district\":\"Arusha Rural\"}', '2025-09-28 11:01:34', '2025-09-28 11:01:34', '2025-09-28 11:01:34'),
(2, 4, 'dashboard', 'http://127.0.0.1:8000/dashboard', 'GET', 'superadmin', 'general_manager', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', '{\"name\":\"samir\",\"email\":\"shamili.selemani@tpf.go.tz\",\"phone_number\":\"255675839840\",\"branch\":\"dodoma\",\"region\":\"Dar es Salaam\",\"department\":\"ICT\",\"district\":\"Temeke\"}', '2025-09-28 11:03:35', '2025-09-28 11:03:35', '2025-09-28 11:03:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `unauthorized_accesses`
--
ALTER TABLE `unauthorized_accesses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unauthorized_accesses_user_id_attempted_at_index` (`user_id`,`attempted_at`),
  ADD KEY `unauthorized_accesses_route_name_attempted_at_index` (`route_name`,`attempted_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `unauthorized_accesses`
--
ALTER TABLE `unauthorized_accesses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `unauthorized_accesses`
--
ALTER TABLE `unauthorized_accesses`
  ADD CONSTRAINT `unauthorized_accesses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
