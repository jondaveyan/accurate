-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 04, 2016 at 09:18 AM
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
  `name` varchar(255) NOT NULL,
  `own` enum('yes','no','','') NOT NULL,
  `debt` float NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `name`, `own`, `debt`) VALUES
(1, 'client1', 'no', 100),
(2, 'client2', 'yes', 0),
(3, 'client3', 'no', 0),
(4, 'new_client', 'no', 0),
(5, 'own_client', 'yes', 0),
(6, 'test_client', 'no', 0),
(7, 'Jon', 'no', 0);

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `giveback`
--

INSERT INTO `giveback` (`id`, `client_id`, `product_id`, `quantity`, `date`) VALUES
(1, 7, 5, 10, '2016-11-01');

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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `client_id`, `product_id`, `product_quantity`, `sale_price`, `daily_price`, `daily_sale`, `date`) VALUES
(1, 1, 3, 2, 0, 11, 'daily', '2016-09-01'),
(2, 2, 4, 12, 0, 0, 'daily', '2016-09-02'),
(3, 4, 3, 2, 0, 11, 'daily', '2016-09-10'),
(4, 5, 2, 1, 0, 0, 'daily', '2016-09-11'),
(5, 1, 4, 3, 0, 22, 'daily', '2016-09-21'),
(6, 1, 4, 4, 0, 33, 'daily', '2016-09-21'),
(7, 1, 4, 3, 0, 22, 'daily', '2016-10-01'),
(8, 1, 4, 4, 0, 44, 'daily', '2016-11-01'),
(9, 1, 2, 2, 0, 22, 'daily', '2016-11-01'),
(10, 1, 2, 12, 0, 11, 'daily', '2016-11-01'),
(11, 1, 4, 3, 0, 11, 'daily', '2016-11-01'),
(12, 1, 1, 1, 3333, 0, 'sale', '2016-11-01'),
(13, 5, 1, 15, 0, 0, 'daily', '2016-11-01'),
(14, 1, 1, 4, 0, 44, 'daily', '2016-11-01'),
(15, 6, 1, 10, 0, 333, 'daily', '2016-11-01'),
(16, 6, 4, 1, 0, 11, 'daily', '2016-11-01'),
(17, 7, 5, 50, 0, 10000, 'daily', '2016-11-01'),
(18, 1, 5, 10, 11, 0, 'sale', '2016-11-01'),
(19, 1, 5, 10, 0, 11, 'daily', '2016-11-01'),
(20, 1, 5, 10, 11, 0, 'sale', '2016-11-01'),
(21, 7, 4, 12, 0, 1220, 'daily', '2016-11-01');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE IF NOT EXISTS `payment` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `amount` float NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `client_id`, `amount`, `date`) VALUES
(1, 1, 1000, '2016-11-01'),
(2, 7, 150000, '2016-11-01');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `quantity` float NOT NULL,
  `type` varchar(25) NOT NULL,
  `new_quantity` int(1) NOT NULL,
  `bad_quantity` int(11) NOT NULL,
  `useless_quantity` int(11) NOT NULL,
  `daily_order` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `quantity`, `type`, `new_quantity`, `bad_quantity`, `useless_quantity`, `daily_order`) VALUES
(1, 'test1', 55, 'test1', 1, 0, 0, 29),
(2, 'test2', 44, 'test2', 2, 0, 0, 15),
(3, 'test3', 34, 'test3', 3, 1, 0, 4),
(4, 'henak200360', 100, 'hat', 3, 0, 0, 42),
(5, 'heraxos', 90, 'hat', 10, 0, 0, 60);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `giveback`
--
ALTER TABLE `giveback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
