-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 25 окт 2019 в 11:28
-- Версия на сървъра: 10.4.6-MariaDB
-- PHP Version: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `task_manager`
--

-- --------------------------------------------------------

--
-- Структура на таблица `tasks`
--

CREATE TABLE `tasks` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `status_id` int(10) NOT NULL,
  `place_id` int(10) NOT NULL,
  `name` varchar(64) NOT NULL,
  `task_description` varchar(64) NOT NULL,
  `due_date` date NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура на таблица `task_places`
--

CREATE TABLE `task_places` (
  `id` int(10) UNSIGNED NOT NULL,
  `task_place` varchar(32) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Схема на данните от таблица `task_places`
--

INSERT INTO `task_places` (`id`, `task_place`, `created_at`, `updated_at`) VALUES
(1, 'Bulgaria', '2019-10-20 20:34:33', '2019-10-20 20:34:33'),
(2, 'Germany', '2019-10-20 20:34:33', '2019-10-20 20:34:33'),
(3, 'Great Britain', '2019-10-20 20:34:33', '2019-10-20 20:34:33'),
(4, 'France', '2019-10-20 20:34:33', '2019-10-20 20:34:33'),
(5, 'Ukraine', '2019-10-20 20:34:33', '2019-10-20 20:34:33'),
(6, 'Spain', '2019-10-20 20:34:33', '2019-10-20 20:34:33'),
(7, 'USA', '2019-10-20 20:34:33', '2019-10-20 20:34:33');

-- --------------------------------------------------------

--
-- Структура на таблица `task_status`
--

CREATE TABLE `task_status` (
  `id` int(10) UNSIGNED NOT NULL,
  `status_name` varchar(64) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Схема на данните от таблица `task_status`
--

INSERT INTO `task_status` (`id`, `status_name`, `created_at`, `updated_at`) VALUES
(1, 'new', '2019-10-20 20:22:17', '2019-10-20 20:22:17'),
(2, 'urgent', '2019-10-20 20:22:17', '2019-10-20 20:22:17'),
(3, 'completed', '2019-10-20 20:22:17', '2019-10-20 20:22:17'),
(4, 'critical', '2019-10-20 20:22:17', '2019-10-20 20:22:17');

-- --------------------------------------------------------

--
-- Структура на таблица `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(64) NOT NULL,
  `last_name` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `age` int(10) UNSIGNED NOT NULL,
  `password` varchar(64) NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_places`
--
ALTER TABLE `task_places`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_status`
--
ALTER TABLE `task_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_places`
--
ALTER TABLE `task_places`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `task_status`
--
ALTER TABLE `task_status`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
