-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 08, 2024 at 02:20 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `medico_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cid`, `uid`, `id`, `quantity`, `added_at`, `image`) VALUES
(1, 20, 17, 1, '2024-11-06 13:24:57', NULL),
(3, 20, 15, 1, '2024-11-06 13:28:17', 'uploads/1703678527_DNIE0101BB1_D1.avif'),
(4, 21, 17, 8, '2024-11-06 14:16:33', 'uploads/dsc_0219_1_8.jpg'),
(5, 21, 15, 1, '2024-11-06 14:46:31', 'uploads/1703678527_DNIE0101BB1_D1.avif'),
(9, 21, 25, 1, '2024-11-07 14:52:41', '');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `oid` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `shipping_address` varchar(255) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'Pending',
  `payment` varchar(20) NOT NULL,
  `uid` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`oid`, `id`, `quantity`, `shipping_address`, `contact_number`, `order_date`, `status`, `payment`, `uid`, `product_name`, `price`, `image`) VALUES
(3, 15, 1, '1hafhjfqh', '466768121', '2024-10-03 20:58:15', 'Dispatched', '', 0, '', NULL, NULL),
(4, 15, 6, 'wdasvasvnga', '87865456', '2024-10-03 21:08:30', 'Pending', '', 0, '', NULL, NULL),
(5, 15, 6, 'wdasvasvnga', '87865456', '2024-10-03 21:10:07', 'Pending', '', 0, '', NULL, NULL),
(6, 15, 6, 'wdasvasvnga', '87865456', '2024-10-03 21:10:42', 'Pending', '', 0, '', NULL, NULL),
(7, 15, 6, 'wdasvasvnga', '87865456', '2024-10-03 21:13:28', 'Pending', '', 0, '', NULL, NULL),
(8, 15, 6, 'jasna', '7736359227', '2024-10-03 21:15:06', 'Pending', '', 0, '', NULL, NULL),
(20, 15, 1, 'kmlmsdkg', '7342069', '2024-11-06 19:43:13', 'Pending', '', 0, '', NULL, NULL),
(21, 17, 8, 'lkdsfwnk', '3205-60', '2024-11-06 19:54:58', 'Pending', '', 0, '', NULL, NULL),
(22, 15, 1, '0', '57890987644', '2024-11-06 20:25:09', 'Pending', 'cod', 0, '', NULL, NULL),
(23, 15, 1, '0', '34567895', '2024-11-06 20:27:40', 'Pending', 'credit_card', 0, '', NULL, NULL),
(24, 15, 1, '0', '34567895', '2024-11-06 20:29:14', 'Pending', 'credit_card', 0, '', NULL, NULL),
(25, 15, 1, '0', '328790', '2024-11-07 10:10:03', 'Pending', 'Credit Card', 0, '', NULL, NULL),
(27, 15, 1, 'dgndzgj', '328790', '2024-11-07 10:17:44', 'Cancelled', 'Credit Card', 21, '', NULL, NULL),
(28, 15, 1, 'jknbvbhb', '54325788', '2024-11-07 10:26:16', 'Cancelled', 'PayPal', 21, 'Natural Glow Smooth Skin Deodorant Roll On For Women', 249.00, 'uploads/1703678527_DNIE0101BB1_D1.avif'),
(29, 17, 1, ' ,mnklmeopgw', '32554868', '2024-11-07 10:36:22', 'Pending', 'Cash On Delivery', 21, 'Sebamed Essential Baby Care Kit, Pack of 5 – Small', 2510.00, 'uploads/dsc_0219_1_8.jpg'),
(30, 17, 1, ' ,mnklmeopgw', '32554868', '2024-11-07 10:37:44', 'Pending', 'Cash On Delivery', 21, 'Sebamed Essential Baby Care Kit, Pack of 5 – Small', 2510.00, 'uploads/dsc_0219_1_8.jpg'),
(31, 17, 1, 'jnlkralgw;r', '93406-', '2024-11-07 10:37:58', 'Pending', 'Credit Card', 21, 'Sebamed Essential Baby Care Kit, Pack of 5 – Small', 2510.00, 'uploads/dsc_0219_1_8.jpg'),
(32, 17, 1, 'jnlkralgw;r', '93406-', '2024-11-07 10:38:22', 'Pending', 'Credit Card', 21, 'Sebamed Essential Baby Care Kit, Pack of 5 – Small', 2510.00, 'uploads/dsc_0219_1_8.jpg'),
(33, 17, 1, 'jnlkralgw;r', '93406-', '2024-11-07 10:39:00', 'Cancelled', 'Credit Card', 21, 'Sebamed Essential Baby Care Kit, Pack of 5 – Small', 2510.00, 'uploads/dsc_0219_1_8.jpg'),
(34, 15, 1, 'kjnkl', '890-98765432', '2024-11-07 10:55:29', 'Pending', 'Cash On Delivery', 21, 'Natural Glow Smooth Skin Deodorant Roll On For Women', 249.00, 'uploads/1703678527_DNIE0101BB1_D1.avif'),
(35, 15, 1, 'jnewftklksgd', '85402091287487', '2024-11-07 11:34:13', 'Pending', 'Credit Card', 21, 'Natural Glow Smooth Skin Deodorant Roll On For Women', 249.00, 'uploads/1703678527_DNIE0101BB1_D1.avif'),
(36, 17, 1, 'lkaslg;sG', '34567890', '2024-11-07 11:34:50', 'Pending', 'Cash On Delivery', 21, 'Sebamed Essential Baby Care Kit, Pack of 5 – Small', 2510.00, 'uploads/dsc_0219_1_8.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `prescription_path` varchar(255) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prescriptions`
--

INSERT INTO `prescriptions` (`id`, `user_id`, `product_id`, `prescription_path`, `status`) VALUES
(1, 21, 25, 'prescriptions/Screenshot 2024-08-04 165558.png', 'approved'),
(2, 21, 25, 'prescriptions/Screenshot 2024-08-04 165558.png', 'rejected'),
(3, 21, 25, 'prescriptions/Screenshot 2024-08-14 204403.png', 'approved'),
(4, 21, 25, 'prescriptions/Screenshot 2024-09-01 113138.png', 'approved'),
(5, 20, 25, 'prescriptions/Screenshot 2024-08-14 204403.png', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `discounted_price` int(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `requires_prescription` tinyint(1) DEFAULT 0,
  `expiry_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `stock`, `image`, `discounted_price`, `category`, `requires_prescription`, `expiry_date`) VALUES
(15, 'Natural Glow Smooth Skin Deodorant Roll On For Women', 249.00, 3, '', 0, 'medications', 0, '0000-00-00'),
(17, 'Sebamed Essential Baby Care Kit, Pack of 5 – Small', 2510.00, 0, '', 510, 'medications', 0, '2024-11-11'),
(20, 'jasna', 1234.00, 89, '', 100, 'medicines', 0, NULL),
(21, 'jasna', 1234.00, 89, '', 100, 'medicines', 0, NULL),
(22, 'jasna', 1234.00, 89, '', 100, 'medicines', 0, NULL),
(23, 'jasna', 1234.00, 89, '', 100, 'medicines', 0, NULL),
(24, 'jasna', 1234.00, 89, '', 100, 'medicines', 0, NULL),
(25, 'jasna', 1234.00, 89, '', 100, 'medications', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `staffattendance`
--

CREATE TABLE `staffattendance` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `shift` varchar(20) NOT NULL,
  `present` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staffattendance`
--

INSERT INTO `staffattendance` (`id`, `user_id`, `date`, `shift`, `present`, `created_at`) VALUES
(3, 22, '2024-10-06', 'Morning', 1, '2024-10-06 06:35:12'),
(4, 22, '2024-10-06', 'Morning', 1, '2024-10-06 06:40:59'),
(5, 22, '2024-10-06', 'Morning', 1, '2024-10-06 06:42:20'),
(6, 22, '2024-10-06', 'Morning', 1, '2024-10-06 12:34:47'),
(7, 22, '2024-10-06', 'Morning', 1, '2024-10-06 12:35:46'),
(8, 22, '2024-10-06', 'Morning', 1, '2024-10-06 12:52:32');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `username` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `password` varchar(30) NOT NULL,
  `confirm_password` varchar(30) NOT NULL,
  `uid` int(11) NOT NULL,
  `utype` varchar(50) NOT NULL,
  `position` varchar(100) NOT NULL,
  `is_blocked` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `email`, `phone_number`, `password`, `confirm_password`, `uid`, `utype`, `position`, `is_blocked`) VALUES
('devika', 'devikasuresh123@gmail.com', '9895359227', 'devika', '', 14, 'User', '', 1),
('jasnamol', 'jasnamol123@gmail.com', '9895359227', 'kunju', '', 15, 'User', '', 0),
('shihab', 'shihabea123@gmail.com', '9895359227', 'shihab', '', 16, 'User', '', 0),
('jasna', 'kunju123@gmail.com', '9895359227', 'kunju', '', 19, 'User', '', 0),
('sabeena', 'sabeenasiyad227@gmail.com', '9947147602', 'sabeena', '', 20, 'User', '', 0),
('shihab', 'shihabeda@gmail.com', '9895359227', 'shihab', '', 21, 'User', '', 0),
('jasna', 'jasnakunj@gmail.com', '9895359227', 'jasna', 'jasna', 22, 'staff', '', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cid`),
  ADD UNIQUE KEY `uid` (`uid`,`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`oid`),
  ADD KEY `id` (`id`),
  ADD KEY `fk_user_uid` (`uid`);

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staffattendance`
--
ALTER TABLE `staffattendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`uid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `oid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `staffattendance`
--
ALTER TABLE `staffattendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `contact_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`uid`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_user_uid` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD CONSTRAINT `prescriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`uid`),
  ADD CONSTRAINT `prescriptions_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `staffattendance`
--
ALTER TABLE `staffattendance`
  ADD CONSTRAINT `staffattendance_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`uid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
