-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 02, 2026 at 10:31 PM
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
-- Database: `clothingstore`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `adminID` int(11) NOT NULL,
  `adminEmail` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`adminID`, `adminEmail`, `password`) VALUES
(1, 'admin@pastimes.co.za', '$2y$10$UbTVGvHB9Mbqqriw9y1xfO0KlOcLdAxLjI2psBpawDfb5bGufBPzO');

-- --------------------------------------------------------

--
-- Table structure for table `tblcart`
--

CREATE TABLE `tblcart` (
  `cartID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `clothesID` int(11) NOT NULL,
  `addedAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblclothes`
--

CREATE TABLE `tblclothes` (
  `clothesID` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `brand` varchar(50) NOT NULL,
  `size` varchar(10) NOT NULL,
  `condition` varchar(20) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('available','sold') DEFAULT 'available',
  `createdAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblclothes`
--

INSERT INTO `tblclothes` (`clothesID`, `title`, `brand`, `size`, `condition`, `price`, `image`, `status`, `createdAt`) VALUES
(1, 'Nike Hoodie', 'Nike', 'L', 'Good', 350.00, 'nike_hoodie.jpg', 'sold', '2026-05-01 22:50:38'),
(2, 'Adidas Tracksuit', 'Adidas', 'M', 'New', 500.00, 'adidas_tracksuit.jpg', 'available', '2026-05-01 22:50:39'),
(3, 'Levi Jeans', 'Levis', '32', 'Good', 280.00, 'levi_jeans.jpg', 'available', '2026-05-01 22:50:39'),
(4, 'Puma T-Shirt', 'Puma', 'S', 'Fair', 150.00, 'puma_tshirt.jpg', 'available', '2026-05-01 22:50:39'),
(5, 'H&M Dress', 'H&M', 'M', 'New', 220.00, 'hm_dress.jpg', 'available', '2026-05-01 22:50:39');

-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

CREATE TABLE `tbluser` (
  `userID` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('pending','verified') DEFAULT 'pending',
  `createdAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbluser`
--

INSERT INTO `tbluser` (`userID`, `firstName`, `lastName`, `email`, `username`, `password`, `status`, `createdAt`) VALUES
(1, 'John', 'Doe', 'johndoe@gmail.com', 'johndoe', '29ef52e7563626a96cea7f4b4085c124', 'verified', '2026-05-02 22:30:32'),
(2, 'Jane', 'Smith', 'janesmith@gmail.com', 'janesmith', '29ef52e7563626a96cea7f4b4085c124', 'verified', '2026-05-02 22:30:32'),
(3, 'Thabo', 'Mokoena', 'thabo@gmail.com', 'thabom', '29ef52e7563626a96cea7f4b4085c124', 'verified', '2026-05-02 22:30:32'),
(4, 'Sarah', 'Jones', 'sarah@gmail.com', 'sarahj', '29ef52e7563626a96cea7f4b4085c124', 'verified', '2026-05-02 22:30:32'),
(5, 'Karabo', 'Tshivhase', 'karabo@gmail.com', 'karabod', '29ef52e7563626a96cea7f4b4085c124', 'verified', '2026-05-02 22:30:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`adminID`),
  ADD UNIQUE KEY `adminEmail` (`adminEmail`);

--
-- Indexes for table `tblcart`
--
ALTER TABLE `tblcart`
  ADD PRIMARY KEY (`cartID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `clothesID` (`clothesID`);

--
-- Indexes for table `tblclothes`
--
ALTER TABLE `tblclothes`
  ADD PRIMARY KEY (`clothesID`);

--
-- Indexes for table `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `adminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblcart`
--
ALTER TABLE `tblcart`
  MODIFY `cartID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblclothes`
--
ALTER TABLE `tblclothes`
  MODIFY `clothesID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblcart`
--
ALTER TABLE `tblcart`
  ADD CONSTRAINT `tblcart_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `tbluser` (`userID`),
  ADD CONSTRAINT `tblcart_ibfk_2` FOREIGN KEY (`clothesID`) REFERENCES `tblclothes` (`clothesID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
