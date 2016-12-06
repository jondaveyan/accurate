-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2016 at 07:56 PM
-- Server version: 5.6.26
-- PHP Version: 5.5.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `accurate`
--

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `own` enum('yes','no','','') CHARACTER SET latin1 NOT NULL,
  `debt` float NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `name`, `own`, `debt`) VALUES
(17, 'Արսեն', 'no', 0),
(18, 'Էրիկ', 'no', 111),
(19, 'Նելլի', 'yes', 12),
(20, 'Անդո', 'no', 1),
(21, 'Արբի', 'no', 20);

-- --------------------------------------------------------

--
-- Table structure for table `giveback`
--

CREATE TABLE IF NOT EXISTS `giveback` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` float NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `giveback`
--

INSERT INTO `giveback` (`id`, `client_id`, `product_id`, `quantity`, `date`) VALUES
(10, 19, 7, 2, '2016-12-04'),
(11, 19, 7, 2, '2016-12-04');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_quantity` int(11) NOT NULL,
  `sale_price` float NOT NULL,
  `daily_price` float NOT NULL,
  `daily_sale` enum('daily','sale','','') NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `client_id`, `product_id`, `product_quantity`, `sale_price`, `daily_price`, `daily_sale`, `date`) VALUES
(32, 17, 2, 3, 0, 100, 'daily', '2016-12-01'),
(46, 19, 7, 1, 0, 0, 'sale', '2016-12-03'),
(47, 19, 7, 1, 0, 0, 'daily', '2016-12-03'),
(48, 19, 7, 2, 0, 0, 'daily', '2016-12-03'),
(49, 19, 7, 2, 0, 0, 'daily', '2016-12-03'),
(50, 17, 1, 0, 0, 0, 'daily', '2016-12-03'),
(52, 17, 7, 3, 0, 0, 'sale', '2016-12-03'),
(53, 19, 7, 10, 0, 0, 'sale', '2016-12-03'),
(54, 19, 7, 3, 0, 0, 'sale', '2016-12-03');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE IF NOT EXISTS `payment` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `amount` float NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `quantity` float NOT NULL,
  `type` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `sold_quantity` int(11) NOT NULL,
  `new_quantity` int(1) NOT NULL,
  `bad_quantity` int(11) NOT NULL,
  `useless_quantity` int(11) NOT NULL,
  `daily_order` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `quantity`, `type`, `sold_quantity`, `new_quantity`, `bad_quantity`, `useless_quantity`, `daily_order`) VALUES
(1, 'Հեծան 200/360', 300, 'մետր', 0, 20, 0, 0, 0),
(2, 'Հենակ 180/300', 300, 'հատ', 0, 20, 0, 0, 3),
(3, 'Հենակ 250/350', 300, 'հատ', 0, 20, 1, 0, 0),
(4, 'Հենակ 200/360', 300, 'հատ', 0, 3, 0, 0, 0),
(5, 'Հեծան 3,0', 300, 'մետր', 0, 10, 0, 0, 0),
(6, 'Քառոտանի', 500, 'հատ', 0, 100, 0, 0, 0),
(7, 'Եռոտանի', 475, 'հատ', 13, 10, 2, 2, 62),
(8, 'Հենակ 325/500', 500, 'հատ', 0, 10, 0, 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `giveback`
--
ALTER TABLE `giveback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `giveback`
--
ALTER TABLE `giveback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=55;
--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
