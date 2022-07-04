-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 01, 2022 at 02:13 PM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `instello`
--

-- --------------------------------------------------------

--
-- Table structure for table `user_feed`
--

CREATE TABLE IF NOT EXISTS `user_feed` (
  `feed_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `feed_image` varchar(1000) NOT NULL,
  `like_status` varchar(100) NOT NULL DEFAULT '0',
  `like_id` varchar(100) NOT NULL DEFAULT '0',
  PRIMARY KEY (`feed_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;

--
-- Dumping data for table `user_feed`
--

INSERT INTO `user_feed` (`feed_id`, `user_id`, `feed_image`, `like_status`, `like_id`) VALUES
(24, 2, '919281piyush.jfif', '0', '0'),
(25, 2, 'k4.jpeg', 'like', '2'),
(26, 2, '588287WhatsApp Image 2022-05-31 at 7.18.00 PM (4).jpeg', 'like', '2'),
(27, 4, '858917a70d8e50-16c4-47ca-b57d-571df7842c71.jfif', 'liked', '4'),
(28, 4, '512451WhatsApp Image 2022-05-31 at 7.16.57 PM.jpeg', 'liked', '4'),
(29, 4, '56d07c25-d4e6-4341-8b4b-88af9bd23689.jfif', 'liked', '4'),
(30, 2, 'k3.jpeg', '0', '0'),
(40, 3, 'download.jfif', '0', '0');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
