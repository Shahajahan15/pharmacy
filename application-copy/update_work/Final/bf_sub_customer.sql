-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 11, 2020 at 08:38 AM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pharmacy`
--

-- --------------------------------------------------------

--
-- Table structure for table `bf_sub_customer`
--

CREATE TABLE `bf_sub_customer` (
  `id` int(11) NOT NULL,
  `sub_customer_name` varchar(64) DEFAULT NULL,
  `sub_customer_phone_number` varchar(16) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `status` int(3) NOT NULL DEFAULT 1 COMMENT '1=active;0=Inactive',
  `created_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='0=inactive, 1= active';

--
-- Dumping data for table `bf_sub_customer`
--

INSERT INTO `bf_sub_customer` (`id`, `sub_customer_name`, `sub_customer_phone_number`, `customer_id`, `status`, `created_date`, `updated_date`) VALUES
(1, 'ss', 'ss', 1, 1, '2020-01-08 10:57:54', '2020-01-08 10:57:54'),
(2, 'okgggg', 'aaa', 284, 1, '2020-01-08 10:58:34', '2020-01-08 10:58:34'),
(3, 'okk', '789789', 788, 1, '2020-01-08 10:59:00', '2020-01-08 10:59:00'),
(4, 'Din Islam', '35345', 3, 1, '2020-01-09 06:10:12', '2020-01-09 06:10:12'),
(5, 'aa', 'aa', 17, 1, '2020-01-09 06:26:44', '2020-01-09 06:26:44'),
(6, 'ssss', 'sss', 1, 1, '2020-01-09 06:39:52', '2020-01-09 06:39:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bf_sub_customer`
--
ALTER TABLE `bf_sub_customer`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bf_sub_customer`
--
ALTER TABLE `bf_sub_customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
