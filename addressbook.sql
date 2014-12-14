-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 14 Dec 2014 la 10:18
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `addressbook`
--

-- --------------------------------------------------------

--
-- Structura de tabel pentru tabelul `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address1` varchar(100) NOT NULL,
  `address2` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `zipcode` varchar(30) NOT NULL,
  `notes` text NOT NULL,
  `contact_group` varchar(100) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Salvarea datelor din tabel `contacts`
--

INSERT INTO `contacts` (`id`, `first_name`, `last_name`, `email`, `phone`, `address1`, `address2`, `city`, `state`, `zipcode`, `notes`, `contact_group`, `date_added`) VALUES
(1, 'Blade', 'Daywalker', 'blade@hotmail.com', '1234567', 'underground discoteque', 'garage', 'Detroit', 'TM', '300200', 'daywalker', 'Friends', '2014-12-13 07:28:35'),
(2, 'Iron', 'Man', 'ironman@gmail.com', '654321', 'huge house on the cliff', 'under the huge house on the cliff', 'Log Angeles', 'TM', '300111', 'dont forget the oil', 'Family', '2014-12-13 07:30:32'),
(3, 'Poison', 'Ivy', 'poison.ivy@gmail.com', '123456', 'creapy garden', 'creapy biodome', 'Gotham', 'HD', '300200', 'prety poisonous', 'Friends', '2014-12-13 21:31:15');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
