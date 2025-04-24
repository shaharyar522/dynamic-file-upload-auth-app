-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 23, 2025 at 02:05 PM
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
-- Table structure for table `tickts`
--

CREATE TABLE `tickts` (
  `id` int(11) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `pdf_path` varchar(255) DEFAULT NULL,
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

INSERT INTO `tickts` (`id`, `image_path`, `pdf_path`, `status`, `qr_code`, `title`, `timestamp`, `name`, `entrance`, `section`, `row`, `seat`) VALUES
(1, 'upload/image/6808d7336072f.jpeg', NULL, 1, '11040063006005880143131825', 'Manchester United v Olympique Lyonnais', '17/04/2025 20:00', 'Paul Moorley', 'N44', 'N4401 m', '12', '225');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tickts`
--
ALTER TABLE `tickts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tickts`
--
ALTER TABLE `tickts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
