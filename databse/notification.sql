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
-- Table structure for table `notification`
--

CREATE TABLE IF NOT EXISTS `notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `l_user_id` int(11) NOT NULL,
  `like_id` int(11) NOT NULL,
  `feed_user_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`id`, `l_user_id`, `like_id`, `feed_user_id`, `status`) VALUES
(1, 2, 23, 2, 1),
(2, 2, 23, 2, 1),
(3, 2, 23, 2, 1),
(4, 2, 23, 2, 1),
(5, 2, 23, 2, 1),
(6, 2, 23, 2, 1),
(7, 2, 23, 2, 1),
(8, 2, 23, 2, 1),
(9, 2, 26, 2, 1),
(10, 2, 26, 2, 1),
(11, 2, 26, 2, 1),
(12, 2, 26, 2, 1),
(13, 0, 26, 2, 1),
(14, 0, 26, 2, 1),
(15, 0, 26, 2, 1),
(16, 0, 26, 2, 1),
(17, 0, 26, 2, 1),
(18, 0, 26, 2, 1),
(19, 0, 25, 2, 1),
(20, 0, 26, 2, 1),
(21, 0, 26, 2, 1),
(22, 0, 25, 2, 1),
(23, 0, 25, 2, 1),
(24, 0, 26, 2, 1),
(25, 3, 40, 3, 1),
(26, 4, 29, 4, 1),
(27, 4, 28, 4, 1),
(28, 4, 27, 4, 1),
(29, 4, 29, 4, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
