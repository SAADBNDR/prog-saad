-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 10 ديسمبر 2024 الساعة 19:54
-- إصدار الخادم: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `car_park`
--

-- --------------------------------------------------------

--
-- بنية الجدول `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `parking_location` varchar(100) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `parking_id` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `start_datetime`, `end_datetime`, `parking_location`, `total_price`, `parking_id`, `status`) VALUES
(1, 1, '2024-10-25 20:23:00', '2024-10-26 20:22:00', 'الرياض بارك', 359.75, NULL, NULL),
(2, 1, '2024-10-29 14:12:00', '2024-10-29 15:13:00', 'العثيم مول', 10.17, NULL, NULL),
(3, 1, '2024-11-05 14:33:00', '2024-11-05 17:33:00', 'السينيما', 60.00, NULL, NULL),
(6, 1, '2024-11-26 15:56:00', '2024-11-26 17:56:00', '', 20.00, 1, 'booked'),
(9, 3, '2024-12-04 10:19:00', '2024-12-04 11:19:00', '', 15.00, 2, 'booked');

-- --------------------------------------------------------

--
-- بنية الجدول `parkings`
--

CREATE TABLE `parkings` (
  `id` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('available','booked') NOT NULL DEFAULT 'available',
  `user_id` int(11) DEFAULT NULL,
  `available_spots` int(11) DEFAULT 50,
  `total_spots` int(11) DEFAULT 50
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `parkings`
--

INSERT INTO `parkings` (`id`, `location`, `price`, `status`, `user_id`, `available_spots`, `total_spots`) VALUES
(1, 'مستشفى التخصصي', 10.00, 'booked', 1, 46, 50),
(2, 'النخيل مول ', 15.00, 'available', NULL, 49, 50);

-- --------------------------------------------------------

--
-- بنية الجدول `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `total_parking_spots` int(11) NOT NULL DEFAULT 50
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `settings`
--

INSERT INTO `settings` (`id`, `total_parking_spots`) VALUES
(1, 50);

-- --------------------------------------------------------

--
-- بنية الجدول `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `email`, `username`, `password`, `role`) VALUES
(1, 'saadf', '0501836138', 'saad505bndr@gmail.com', 'saad', '$2y$10$mkQ1jqhEf6KaFP4tWFHLqexZj0TBeNVAxAv0VyPsoqkeIM4PATFXC', 1),
(2, 'سعد ', '0501836139', 'admin@gmail.com', 'saaaad', '$2y$10$fVoootGu2L3YF2UPhm4i2uUSoI2xDwsecc3JOyvwVPlnBn1FHo1K2', 0),
(3, 'salman', '050183688', '0550068444@gmail.com', 'moh', '$2y$10$U.WAZ6SY/bZ0GY.1UdoJKOUN79BBAFpmMPO8McNz2FOKUUwLEXdGW', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `parkings`
--
ALTER TABLE `parkings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `parkings`
--
ALTER TABLE `parkings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- قيود الجداول المُلقاة.
--

--
-- قيود الجداول `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
