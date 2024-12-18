-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2024 at 06:29 AM
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
-- Database: `kimiogadgets`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `image`, `description`, `type`, `price`, `quantity`) VALUES
(29, 'Portable Power Bank 10000mAh', 'image7.jpg', 'Compact power bank with fast charging support.', 'Powerbank', 89.99, 75),
(30, 'Wireless Mouse', 'image8.jpg', 'Ergonomic design, wireless with USB receiver.', 'Computer Accessory', 39.99, 120),
(31, 'Bluetooth Speaker', 'image2.jpg', 'Portable Bluetooth speaker with 360° surround sound.', 'Audio Gadget', 119.99, 40),
(32, 'Laptop Cooling Pad', 'image9.jpg', 'Cooling pad with adjustable fan speeds for laptops.', 'Computer Accessory', 59.99, 30),
(33, 'USB-C Hub', 'image10.jpg', 'Multi-port USB-C hub for data transfer and video output.', 'Computer Accessory', 79.99, 60),
(34, 'Smartwatch Fitness Tracker', 'image3.jpg', 'Waterproof smartwatch with fitness tracking and heart rate monitor.', 'Wearable Gadget', 249.99, 25),
(35, 'VR Headset', 'image11.jpg', 'Virtual reality headset for immersive experiences.', 'Gaming Accessory', 499.99, 15),
(36, 'USB Flash Drive 64GB', 'image12.jpg', 'Compact USB flash drive with 64GB storage.', 'Storage Device', 29.99, 200),
(38, 'LED Ring Light', 'image14.jpg', 'Adjustable LED ring light for video calls and content creation.', 'Photography Accessory', 109.99, 20);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `created_at`) VALUES
(1, 'khiril hakimie', 'khiril123@gmail.com', '$2y$10$zXAUdDoRmzNcGN3PZojzXuCl4XI/hCN1MOzXOMtEjU3O/nAFDixau', '2024-12-17 18:50:31'),
(2, 'Muhd Khiril', 'khiril12@gmail.com', '$2y$10$vQviV9v74.mVkayhvMO06OWkQibqp9Q.Derc9fiKUzPD2qpQ.te8.', '2024-12-17 18:52:31'),
(3, 'Ahmad Ali', 'ali123@gmail.com', '$2y$10$H3fGow4RMCjyKXoKFTlAX.V0rK3PUfeIxNp3mKN.LZSgdZfequZUC', '2024-12-18 04:57:55'),
(4, 'Muhammad Khairil', 'khiril1@gmail.com', '$2y$10$9bK1jTxIe4ps8AgKa/T/iuhzhx0jvbDxdarJ5uBOmCEq5MGl8cRRO', '2024-12-18 05:10:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
