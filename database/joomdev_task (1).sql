-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 18, 2024 at 07:25 PM
-- Server version: 8.3.0
-- PHP Version: 8.1.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `joomdev_task`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$8sA2N5Sx/1zMQv2yrTDAaOFlbGWECrrgB68axL.hBb78NhQdyAqWm');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `stop_time` timestamp NULL DEFAULT NULL,
  `notes` text,
  `description` text,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `user_id`, `start_time`, `stop_time`, `notes`, `description`) VALUES
(1, 1, '2024-09-17 15:40:00', '2024-09-17 17:40:00', 'test', 'test1232'),
(7, 11, '2024-09-17 21:43:00', '2024-09-19 22:44:00', 'Testing please ignore it.', 'Testing please ignore it. Testing please ignore it. Testing please ignore it. Testing please ignore it.'),
(8, 13, '2024-09-18 18:23:00', '2024-09-17 20:25:00', 'Testing 1 please ignore it.', 'Testing 1 please ignore it. Testing 1 please ignore it. Testing 1 please ignore it. Testing 1 please ignore it. Testing 1 please ignore it. Testing 1 please ignore it.'),
(9, 13, '2024-09-17 20:28:00', '2024-09-17 22:28:00', 'Testing 2 please ignore it.', 'Testing 2 please ignore it. Testing 2 please ignore it. Testing 2 please ignore it. Testing 2 please ignore it. Testing 2 please ignore it.'),
(4, 3, '2024-09-17 21:06:00', '2024-09-17 22:07:00', 'testing please ignore.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s.'),
(5, 3, '2024-09-17 21:11:00', '2024-09-17 22:11:00', 'testing please ignore it.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s.'),
(6, 3, '2024-09-17 21:12:00', '2024-09-17 23:13:00', 'testing please ignore', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s.');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `last_password_change` timestamp NULL DEFAULT NULL,
  `md5_password` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `phone`, `password`, `last_login`, `last_password_change`, `md5_password`) VALUES
(1, 'User1', 'sharma', 'user@email.com', '9999999999', '$2y$10$SyLTbRBEEWrAz.Jy6uX1qe20S.KbfXtfTJUNiER.PD52gCIBeaGdK', '2024-09-17 15:51:43', '2024-09-17 18:01:40', NULL),
(2, 'User2', 'sharma', 'user2@email.com', '9999999998', '$2y$10$/kyHNOOXoeCl5a3AxZLjberha3omXO70P3YJVaE2g9YJLpOKdOoj2', '2024-09-18 10:40:24', '2024-09-17 18:00:26', NULL),
(3, 'User3', 'sharma', 'user3@email.com', '9999999997', '$2y$10$kSMoJwl3/PqE07HLe1OxKuu4kMBoQT3I423ZtoWqn8Mc03YdgCE82', '2024-09-17 15:36:55', '2024-09-17 19:12:27', '7de9c5aa3565704d840c6de420e1cb22'),
(9, 'User4', 'sharma', 'user4@email.com', '9999999996', '$2y$10$Kl0ZuNGHTU5Ey0vuYeaGpeovvYupppZPrBEpUjlRPtaD5OwIQaNgG', '2024-09-17 15:23:49', '2024-09-17 20:35:34', '0a110b93835a069391da243370e7939d'),
(10, 'User5', 'sharma', 'user5@email.com', '9999999996', '$2y$10$v9kIkZ85dVrIyZi9KqveA.SeoczaWjqJtwWIcwVFOB/1R8/kjPuyK', NULL, '2024-09-17 21:30:18', '8532671ea3306cdffe62d089b9cef65c'),
(11, 'User10', 'Sharma', 'User10@email.com', '9015305843', '$2y$10$EtXx/6a/Hhb6qpIyCWwCwuc.E1psZIIwnY.bEH8/3WawWXIHLz.HW', NULL, '2024-09-17 21:43:50', 'bc7b92bba19ec2dbfb8831afce797087'),
(12, 'User7', 'sharma', 'user7@email.com', '9999999911', '$2y$10$BOx6fYh61dnRida/s.e11.PnDJ8VDNEMxOPLrv1FLafURfbcgtBbS', NULL, NULL, 'd16f58cf16099a05d9fac3e0794015b1'),
(13, 'User8', 'sharma', 'user8@email.com', '9999999912', '$2y$10$Odc/JpW0SrBuNYL.3Dz1a.hqTlwiDxuRNu7mpC2WtFuNADS5x..sO', '2024-09-18 12:42:25', '2024-09-18 17:53:30', '99b935215805c37778557665c9364518'),
(15, 'User9', 'sharma', 'user9@email.com', '9999999990', '$2y$10$O1vmMEr96mEYPkhZ8Yj5Zuz78CzL7agVhC4Ms9sDh7MocBiynM/1q', NULL, '2024-09-18 19:10:23', '4915b970803e2a2fda5dac712a3e6a50');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
