-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 30, 2025 at 04:30 AM
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
-- Database: `my-list`
--

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `task_id` bigint(20) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id_task` bigint(20) NOT NULL,
  `id_user` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `priority` enum('rendah','sedang','tinggi') DEFAULT 'sedang',
  `status` enum('belum','selesai') DEFAULT 'belum',
  `deadline` datetime DEFAULT NULL,
  `reminder` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id_task`, `id_user`, `name`, `description`, `category`, `priority`, `status`, `deadline`, `reminder`, `created_at`, `updated_at`) VALUES
(6, 2, 'Tugas Kuliah RPL', 'Membuat UI Website RPL', 'Kuliah', 'tinggi', 'belum', '2025-01-11 00:01:00', '2025-12-31 00:01:00', '2025-12-30 02:58:08', '2025-12-30 03:24:53'),
(8, 2, 'Kerjakan laporan RPL Bab 1', 'Susun latar belakang, rumusan masalah, dan tujuan penelitian.', 'Kuliah', 'tinggi', 'belum', '2025-12-31 23:59:00', '2025-12-31 20:00:00', '2025-12-30 03:26:25', '2025-12-30 03:26:25'),
(9, 2, 'Revisi UI Dashboard To-do', 'Rapikan tampilan list, badge status/prioritas, dan tombol detail/hapus.', 'Project', 'sedang', 'belum', '2025-12-30 18:00:00', '2025-12-30 15:00:00', '2025-12-30 03:26:25', '2025-12-30 03:26:25'),
(10, 2, 'Belajar MySQL JOIN', 'Latihan query JOIN untuk relasi users-tasks dan filtering.', 'Belajar', 'rendah', 'belum', '2026-01-02 10:00:00', '2026-01-02 08:30:00', '2025-12-30 03:26:25', '2025-12-30 03:26:25'),
(11, 2, 'Push ke GitHub', 'Commit perubahan function_task.php, ajax_task.php, dan dashboard.php.', 'Project', 'sedang', 'selesai', '2025-12-29 21:00:00', '2025-12-29 19:30:00', '2025-12-30 03:26:25', '2025-12-30 03:29:14'),
(12, 2, 'Backup database my-list', 'Export database sebelum melakukan perubahan struktur tabel.', 'Maintenance', 'rendah', 'selesai', '2025-12-28 22:00:00', '2025-12-28 21:30:00', '2025-12-30 03:26:25', '2025-12-30 03:29:12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES
(2, 'Adinata', 'adinata@gmail.com', 'adinata@gmail.com', '2025-12-30 02:12:06', '2025-12-30 03:28:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_notifications_task` (`task_id`),
  ADD KEY `idx_notifications_user_read` (`user_id`,`is_read`,`created_at`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id_task`),
  ADD KEY `idx_tasks_user_status_deadline` (`id_user`,`status`,`deadline`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id_task` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notifications_task` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id_task`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_notifications_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `fk_tasks_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
