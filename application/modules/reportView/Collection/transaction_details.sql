-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 09, 2015 at 04:55 AM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `hospital_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bf_transaction_details`
--

CREATE TABLE IF NOT EXISTS `bf_transaction_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL DEFAULT '1',
  `project_id` int(11) NOT NULL DEFAULT '1',
  `counter_id` int(11) NOT NULL DEFAULT '1',
  `patient_id` int(11) NOT NULL,
  `transaction_type` int(2) NOT NULL DEFAULT '1' COMMENT '1=Collection, 2=Refund, 3=Commission, 4=Discount, 5=Payment',
  `source_id` int(11) NOT NULL,
  `source_name` int(11) NOT NULL COMMENT '1=Ambulance, 2=Ticket, 3=Diagnosis, 4=Admission, 5=Discharge',
  `amount` decimal(14,2) NOT NULL DEFAULT '0.00',
  `paid_by` varchar(35) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `collection_by` int(11) NOT NULL,
  `collection_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `remarks` varchar(150) DEFAULT NULL,
  `is_delete` int(11) NOT NULL DEFAULT '0' COMMENT '0=No, 1=Yes',
  `deleted_by` int(11) DEFAULT NULL,
  `delete_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=41 ;

--
-- Dumping data for table `bf_transaction_details`
--

INSERT INTO `bf_transaction_details` (`id`, `company_id`, `project_id`, `counter_id`, `patient_id`, `transaction_type`, `source_id`, `source_name`, `amount`, `paid_by`, `collection_by`, `collection_date`, `remarks`, `is_delete`, `deleted_by`, `delete_date`) VALUES
(1, 1, 1, 1, 5, 1, 10, 3, '300.00', 'Ibrahim Kabir', 1, '2015-02-25 12:33:50', NULL, 0, NULL, NULL),
(2, 1, 1, 1, 5, 1, 10, 3, '10.00', 'Aslam', 1, '2015-03-04 11:00:26', NULL, 0, NULL, NULL),
(5, 1, 1, 1, 5, 1, 10, 3, '10.00', 'cfgdfg', 1, '2015-03-04 11:46:43', NULL, 0, NULL, NULL),
(6, 1, 1, 1, 5, 1, 10, 3, '10.00', 'dfgdfg', 1, '2015-03-04 11:49:33', NULL, 0, NULL, NULL),
(7, 1, 1, 1, 5, 1, 10, 3, '10.00', 'hfhfhfgh', 1, '2015-03-04 11:51:52', NULL, 0, NULL, NULL),
(8, 1, 1, 1, 4, 1, 9, 3, '10.00', 'gfhfgh', 1, '2015-03-04 11:52:55', NULL, 0, NULL, NULL),
(9, 1, 1, 1, 4, 1, 3, 3, '10.00', 'dfgdg', 1, '2015-03-04 11:58:08', NULL, 0, NULL, NULL),
(10, 1, 1, 1, 5, 1, 3, 3, '10.00', 'fdgdfg', 1, '2015-03-04 12:01:17', NULL, 0, NULL, NULL),
(11, 1, 1, 1, 4, 1, 9, 3, '15.00', 'Ysdsd', 1, '2015-03-04 12:20:25', NULL, 0, NULL, NULL),
(12, 1, 1, 1, 5, 1, 10, 3, '9.00', 'Asdfdf', 1, '2015-03-04 12:26:26', NULL, 0, NULL, NULL),
(13, 1, 1, 1, 5, 1, 10, 3, '9.00', 'dgdg', 1, '2015-03-04 12:27:16', NULL, 0, NULL, NULL),
(14, 1, 1, 1, 5, 1, 10, 3, '10.00', 'fgdfgdfg', 1, '2015-03-04 12:29:52', NULL, 0, NULL, NULL),
(15, 1, 1, 1, 5, 1, 10, 3, '10.00', 'ghfghfg', 1, '2015-03-04 12:30:38', NULL, 0, NULL, NULL),
(16, 1, 1, 1, 5, 1, 10, 3, '10.00', 'ghfghfg', 1, '2015-03-04 12:30:40', NULL, 0, NULL, NULL),
(17, 1, 1, 1, 5, 1, 10, 3, '1.00', 'vbnvbn', 1, '2015-03-04 12:31:12', NULL, 0, NULL, NULL),
(18, 1, 1, 1, 5, 1, 10, 3, '10.00', 'gdfgdg', 1, '2015-03-04 12:42:20', NULL, 0, NULL, NULL),
(19, 1, 1, 1, 5, 1, 10, 3, '10.00', 'khk', 1, '2015-03-04 12:45:01', NULL, 0, NULL, NULL),
(20, 1, 1, 1, 1, 1, 11, 3, '100.00', 'Salam', 1, '2015-03-16 11:19:22', NULL, 0, NULL, NULL),
(21, 1, 1, 1, 1, 1, 11, 3, '100.00', 'Salam', 1, '2015-03-16 11:21:36', NULL, 0, NULL, NULL),
(22, 1, 1, 1, 3, 1, 12, 3, '80.00', 'Asma Akter', 1, '2015-03-16 11:47:06', NULL, 0, NULL, NULL),
(23, 1, 1, 1, 8, 1, 6, 2, '25.00', 'Self', 1, '2015-03-18 11:30:32', NULL, 0, NULL, NULL),
(24, 1, 1, 1, 9, 1, 7, 2, '400.00', 'Self', 1, '2015-03-18 11:44:27', NULL, 0, NULL, NULL),
(25, 1, 1, 1, 9, 1, 13, 3, '100.00', 'Aslam Pervaze', 1, '2015-03-18 11:51:55', NULL, 0, NULL, NULL),
(26, 1, 1, 1, 10, 1, 14, 3, '100.00', '-', 1, '2015-03-22 10:43:02', NULL, 0, NULL, NULL),
(27, 1, 1, 1, 10, 1, 14, 3, '94.00', '', 1, '2015-03-22 10:48:31', NULL, 0, NULL, NULL),
(28, 1, 1, 1, 10, 1, 14, 3, '50.00', '', 1, '2015-03-22 11:36:32', NULL, 0, NULL, NULL),
(29, 1, 1, 1, 9, 1, 15, 3, '100.00', 'Bilas', 1, '2015-03-22 12:22:49', NULL, 0, NULL, NULL),
(30, 1, 1, 1, 11, 1, 16, 3, '200.00', '', 1, '2015-03-22 16:11:04', NULL, 0, NULL, NULL),
(31, 1, 1, 1, 12, 1, 17, 3, '250.00', 'Jamal', 1, '2015-03-23 11:31:19', NULL, 0, NULL, NULL),
(32, 1, 1, 1, 12, 2, 17, 3, '49.00', NULL, 1, '2015-03-23 11:39:58', NULL, 0, NULL, NULL),
(33, 1, 1, 1, 17, 1, 18, 3, '245.00', 'Kamal', 1, '2015-05-04 13:42:51', NULL, 0, NULL, NULL),
(35, 1, 1, 1, 1, 1, 25, 3, '238.50', '0', 1, '2015-05-05 12:54:34', NULL, 0, NULL, NULL),
(37, 1, 1, 1, 1, 2, 25, 3, '220.50', NULL, 1, '2015-05-06 15:33:25', NULL, 0, NULL, NULL),
(38, 1, 1, 1, 5, 1, 26, 3, '564.00', '0', 1, '2015-05-17 11:56:53', NULL, 0, NULL, NULL),
(39, 1, 1, 1, 1, 1, 64, 3, '220.50', '0', 1, '2015-05-24 09:33:18', NULL, 0, NULL, NULL),
(40, 1, 1, 1, 18, 1, 8, 2, '25.00', 'Self', 1, '2015-05-27 11:26:42', NULL, 0, NULL, NULL);
