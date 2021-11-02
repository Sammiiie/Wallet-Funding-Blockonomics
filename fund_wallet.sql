-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 02, 2021 at 01:22 PM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.2.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fund_wallet`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `account_no` varchar(45) DEFAULT NULL,
  `balance` decimal(19,2) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `total_deposit` decimal(19,2) DEFAULT NULL,
  `total_withdrawal` decimal(19,2) DEFAULT NULL,
  `users_id` int(11) NOT NULL,
  `uuid` text NOT NULL,
  `uuid_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `account_no`, `balance`, `status`, `total_deposit`, `total_withdrawal`, `users_id`, `uuid`, `uuid_status`) VALUES
(1, '0988403712', '90.00', 'active', '0.00', '0.00', 3, '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(150) NOT NULL,
  `product_type` varchar(45) NOT NULL,
  `price` decimal(19,2) NOT NULL,
  `value` varchar(45) NOT NULL,
  `description` longtext NOT NULL,
  `country` varchar(60) NOT NULL,
  `meta` longtext NOT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `product_type`, `price`, `value`, `description`, `country`, `meta`, `status`) VALUES
(2, 'Faccebook', 'account', '20.00', '', 'Another facebook account', 'germany', '{\"url\":\"www.facebook.com\",\"email\":\"dunce@email.com\",\"password\":\"asdhsaud\"}', '1'),
(277, 'rdp', 'rdp', '20.00', '', 'Another rdpw', 'germany', '{\"ip\":\"12901.3432.43\",\"username\":\"junce\",\"password\":\"asdhsaud\"}', '1'),
(278, 'rdp', 'rdp', '20.00', '', 'GAnother rdpw', 'germany', '{\"ip\":\"17901.3432.43\",\"username\":\"bunce\",\"password\":\"asdhsaud\"}', '0'),
(279, 'rdp', 'rdp', '20.00', '', 'Hnother rdpw', 'germany', '{\"ip\":\"52901.3432.43\",\"username\":\"hunce\",\"password\":\"asdhsaud\"}', '0'),
(280, 'rdp', 'rdp', '20.00', '', 'Another rdpw', 'germany', '{\"ip\":\"45901.3432.43\",\"username\":\"vunce\",\"password\":\"asdhsaud\"}', '0'),
(281, 'card', 'card', '20.00', '', 'Got a new card', 'germany', '{\"card_no\":\"2323-3223-3233-3232\",\"expiry_date\":\"20-20\",\"cvv\":\"932\"}', '1'),
(282, 'card', 'card', '20.00', '', 'Got a new card', 'germany', '{\"card_no\":\"2383-3603-3233-3232\",\"expiry_date\":\"20-20\",\"cvv\":\"992\"}', '0'),
(283, 'card', 'card', '20.00', '', 'Got a new card', 'germany', '{\"card_no\":\"2323-3223-3233-3232\",\"expiry_date\":\"20-20\",\"cvv\":\"932\"}', '0'),
(284, 'card', 'card', '20.00', '', 'Got a new card', 'germany', '{\"card_no\":\"2323-3223-3233-3232\",\"cvv\":\"932\",\"expiry_date\":\"20-20\"}', '1'),
(285, 'rdp', 'rdp', '20.00', '', 'Another rdpw', 'germany', '', '0'),
(286, 'Facebook', 'account', '20.00', '', 'Another facebook account', 'germany', '{\"url\":\"www.facebook.com\",\"email\":\"dunced@email.com\",\"password\":\"asdhsaud\"}', '0'),
(287, 'Faccebook', 'account', '20.00', '', 'Another facebook account', 'germany', '{\"url\":\"www.facebook.com\",\"email\":\"duncef@email.com\",\"password\":\"asdhsaud\"}', '0'),
(288, 'Faccebook', 'account', '20.00', '', 'Another facebook account', 'germany', '{\"url\":\"www.facebook.com\",\"email\":\"duvnce@email.com\",\"password\":\"asdhsaud\"}', '0');

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

CREATE TABLE `purchase` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `purchase_date` datetime DEFAULT current_timestamp(),
  `product_type` varchar(60) NOT NULL,
  `value` varchar(60) NOT NULL,
  `refund` int(11) NOT NULL,
  `country` varchar(60) NOT NULL,
  `price` decimal(19,2) NOT NULL,
  `products_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` int(11) NOT NULL,
  `amount` decimal(19,2) DEFAULT NULL,
  `transaction_type` varchar(20) DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `reference_id` text DEFAULT NULL,
  `accounts_id` int(11) NOT NULL,
  `purchase_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(45) DEFAULT NULL,
  `fullname` varchar(80) DEFAULT NULL,
  `status` varchar(8) DEFAULT 'ACTIVE',
  `rank` int(11) NOT NULL,
  `country` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `usertype` varchar(45) DEFAULT NULL,
  `password` varchar(800) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `fullname`, `status`, `rank`, `country`, `email`, `phone`, `usertype`, `password`) VALUES
(1, 'gamji', 'EJIGA PAUL', 'ACTIVE', 1, 'NIGERIA', 'olochesamuel2@gmail.com', '07056658235', 'ADMIN', '$2y$10$XOVZGYjqOJVeQORPeSlFE.aoK7mKrDL9J0riwhjPkTrGjmcjY8bHq'),
(2, 'sammie', NULL, NULL, 0, NULL, 'manjigs@gmail.com', NULL, 'user', '$2y$10$Nfyxkd66l7LzzCU9MtEVG.rZFAlcKw42e28KQTDu5d.WnFR0VKSbe'),
(3, 'bambam', NULL, 'ACTIVE', 0, NULL, 'bam@gmail.com', NULL, 'user', '$2y$10$H4jh81JII.xnxc9FlyqJk.LrLGGLMgc6hOVxSkM8CR5z9lwmNXnj.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase`
--
ALTER TABLE `purchase`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=289;

--
-- AUTO_INCREMENT for table `purchase`
--
ALTER TABLE `purchase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
