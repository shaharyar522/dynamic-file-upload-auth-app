-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2025 at 12:34 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tickting`
--

-- --------------------------------------------------------

--
-- Table structure for table `log_in`
--

CREATE TABLE `log_in` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `emailverify` enum('none','verify') DEFAULT 'none',
  `status` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `log_in`
--

INSERT INTO `log_in` (`id`, `email`, `password`, `emailverify`, `status`, `created_at`) VALUES
(5, 'a@gmail.com', '222', 'verify', 1, '2025-04-17 11:36:43'),
(6, 'n@gmail.com', '222', 'verify', 1, '2025-04-17 13:18:24');

-- --------------------------------------------------------

--
-- Table structure for table `tickts`
--

CREATE TABLE `tickts` (
  `id` int(11) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `pdf_path` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `badge_id` varchar(100) DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `qr_code` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `timestamp` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `entrance` varchar(50) DEFAULT NULL,
  `section` varchar(50) DEFAULT NULL,
  `row` varchar(50) DEFAULT NULL,
  `seat` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tickts`
--

INSERT INTO `tickts` (`id`, `image_path`, `pdf_path`, `date`, `badge_id`, `status`, `qr_code`, `title`, `timestamp`, `name`, `entrance`, `section`, `row`, `seat`) VALUES
(1, 'upload/image/6809fdee79a0b.jpeg', NULL, '2025-04-25', '2', 1, '11040063003005880191919518', 'Manchester United v Olympique Lyonnais', '17/04/2025 20:00', 'Connor Jones', 'N44', 'N4401 - .', '12', '222'),
(3, 'upload/image/680a0b6e38e1a.jpeg', '1', '2025-04-25', '2', 1, '11040063006005880143131825', 'Manchester United v Olympique Lyonnais', '17/04/2025 20:00', 'Paul Moorley', 'N44', 'N4401 m', '12', '225'),
(4, 'upload/image/680a0b6e39283.jpeg', '1', '2025-04-24', '1', 1, '11040063006005880143131825', 'Manchester United v Olympique Lyonnais', '17/04/2025 20:00', 'Paul Moorley', 'N44', 'N4401 m', '12', '225'),
(6, 'upload/image/680a1345b03a4.jpeg', NULL, NULL, NULL, 1, '11040063003005880191919518', 'Manchester United v Olympique Lyonnais', '17/04/2025 20:00', 'Connor Jones', 'N44', 'N4401 - .', '12', '222'),
(7, 'upload/image/680a13ac7ca11.jpeg', NULL, NULL, NULL, 1, '11040063002005880138982295', 'Manchester United v Olympique Lyonnais', '17/04/2025 20:00', 'owen elliott', 'N44', 'N4401 e', '12', '221');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `log_in`
--
ALTER TABLE `log_in`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tickts`
--
ALTER TABLE `tickts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `log_in`
--
ALTER TABLE `log_in`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tickts`
--
ALTER TABLE `tickts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
