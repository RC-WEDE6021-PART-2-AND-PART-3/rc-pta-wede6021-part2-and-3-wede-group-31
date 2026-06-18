-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 18, 2026 at 10:50 PM
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
(1, 'admin@pastimes.co.za', '$2y$10$Opj/Ub4ewhwS0Z7sIjC7a.v0qv7IavjrSukeRIj/3OVmf0UG/3eBS');

-- --------------------------------------------------------

--
-- Table structure for table `tblaorder`
--

CREATE TABLE `tblaorder` (
  `orderID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `clothesID` int(11) NOT NULL,
  `orderDate` datetime DEFAULT current_timestamp(),
  `totalAmount` decimal(10,2) NOT NULL,
  `addressType` enum('residential','work') NOT NULL,
  `streetAddress` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `postalCode` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblcart`
--

CREATE TABLE `tblcart` (
  `cartID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `clothesID` int(11) NOT NULL,
  `addedAt` datetime DEFAULT current_timestamp(),
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblcart`
--

INSERT INTO `tblcart` (`cartID`, `userID`, `clothesID`, `addedAt`, `quantity`) VALUES
(3, 6, 12, '2026-06-18 22:01:47', 1);

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
(1, 'Nike Hoodie', 'Nike', 'L', 'Good', 350.00, 'nike_hoodie.jpg', 'available', '2026-05-04 20:25:16'),
(2, 'Adidas Tracksuit', 'Adidas', 'M', 'New', 500.00, 'adidas_tracksuit.jpg', 'available', '2026-05-04 20:25:16'),
(3, 'Levi Jeans', 'Levis', '32', 'Good', 280.00, 'levi_jeans.jpg', 'available', '2026-05-04 20:25:16'),
(4, 'Puma T-Shirt', 'Puma', 'S', 'Fair', 150.00, 'puma_tshirt.jpg', 'available', '2026-05-04 20:25:16'),
(8, 'Swoosh Cap', 'Nike', 'M', 'New', 599.00, 'nike_cap.jpg', 'available', '2026-06-18 01:57:55'),
(11, 'Adidas Hoodie', 'Adidas', 'L', 'New', 459.00, 'adidas_hoodie.png', 'available', '2026-06-18 21:45:35'),
(12, 'Max90 White T-Shirt', 'Nike', 'M', 'New', 300.00, 'nike_shirt.png', 'available', '2026-06-18 22:00:35');

-- --------------------------------------------------------

--
-- Table structure for table `tblmessages`
--

CREATE TABLE `tblmessages` (
  `messageID` int(11) NOT NULL,
  `senderID` int(11) NOT NULL,
  `senderType` enum('admin','user') NOT NULL,
  `receiverID` int(11) NOT NULL,
  `subject` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `isRead` tinyint(1) DEFAULT 0,
  `sentAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblmessages`
--

INSERT INTO `tblmessages` (`messageID`, `senderID`, `senderType`, `receiverID`, `subject`, `message`, `isRead`, `sentAt`) VALUES
(1, 1, 'admin', 6, 'your item has been delivered', 'the user received the package', 1, '2026-06-18 02:10:18'),
(2, 1, 'admin', 6, 'item approved', 'the item you want to is sell is now available for purchase', 1, '2026-06-18 21:46:16'),
(3, 1, 'admin', 6, 'item approved', 'the item you listed is now available for purchase', 1, '2026-06-18 22:01:08');

-- --------------------------------------------------------

--
-- Table structure for table `tblsellerrequest`
--

CREATE TABLE `tblsellerrequest` (
  `requestID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `brand` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `size` varchar(10) NOT NULL,
  `condition` varchar(20) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `submittedAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblsellerrequest`
--

INSERT INTO `tblsellerrequest` (`requestID`, `userID`, `title`, `description`, `brand`, `image`, `price`, `size`, `condition`, `status`, `submittedAt`) VALUES
(1, 6, 'Vintage Denim Jacket', 'the item is fairly used', 'Levi\'s', 'my_jacket.jpg', 250.00, 'M', 'Fair', 'rejected', '2026-06-18 02:02:11'),
(2, 6, 'nike cap', 'very new', 'Nike', 'nike_cap.jpg', 250.00, 'M', 'New', 'approved', '2026-06-18 02:05:59'),
(3, 6, 'Adidas Hoodie', 'this item is in a fresh new condition', 'Adidas', 'adidas_hoodie.png', 459.00, 'L', 'New', 'approved', '2026-06-18 21:43:51'),
(4, 6, 'Max90 White T-Shirt', 'this is in a new condition', 'Nike', 'nike_shirt.png', 300.00, 'M', 'New', 'approved', '2026-06-18 21:59:42');

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
(1, 'John', 'Doe', 'johndoe@gmail.com', 'johndoe', '29ef52e7563626a96cea7f4b4085c124', 'verified', '2026-05-04 20:25:48'),
(2, 'Jane', 'Smith', 'janesmith@gmail.com', 'janesmith', '29ef52e7563626a96cea7f4b4085c124', 'verified', '2026-05-04 20:25:48'),
(3, 'Thabo', 'Mokoena', 'thabo@gmail.com', 'thabom', '29ef52e7563626a96cea7f4b4085c124', 'verified', '2026-05-04 20:25:48'),
(4, 'Sarah', 'Jones', 'sarah@gmail.com', 'sarahj', '29ef52e7563626a96cea7f4b4085c124', 'verified', '2026-05-04 20:25:48'),
(6, 'karabo', 'tshivhase', 'karabotshivhase2@gmail.com', 'karabo', '$2y$10$DTK3fvQtIojhVlWXz4zKJuzr/GJ8o6HN99XB.WEau4LfW/rDGMNV2', 'verified', '2026-06-18 01:43:55'),
(7, 'peter', 'john', 'peter@gmail.com', 'peter', '$2y$10$MGzhYB2BrhBp2AfkSrPbI.8nSQ9U.MqWKHDyc9zgfNJsvgRg6Exa2', 'verified', '2026-06-18 02:16:09');

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
-- Indexes for table `tblaorder`
--
ALTER TABLE `tblaorder`
  ADD PRIMARY KEY (`orderID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `clothesID` (`clothesID`);

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
-- Indexes for table `tblmessages`
--
ALTER TABLE `tblmessages`
  ADD PRIMARY KEY (`messageID`);

--
-- Indexes for table `tblsellerrequest`
--
ALTER TABLE `tblsellerrequest`
  ADD PRIMARY KEY (`requestID`),
  ADD KEY `userID` (`userID`);

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
-- AUTO_INCREMENT for table `tblaorder`
--
ALTER TABLE `tblaorder`
  MODIFY `orderID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblcart`
--
ALTER TABLE `tblcart`
  MODIFY `cartID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblclothes`
--
ALTER TABLE `tblclothes`
  MODIFY `clothesID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tblmessages`
--
ALTER TABLE `tblmessages`
  MODIFY `messageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblsellerrequest`
--
ALTER TABLE `tblsellerrequest`
  MODIFY `requestID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblaorder`
--
ALTER TABLE `tblaorder`
  ADD CONSTRAINT `tblaorder_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `tbluser` (`userID`),
  ADD CONSTRAINT `tblaorder_ibfk_2` FOREIGN KEY (`clothesID`) REFERENCES `tblclothes` (`clothesID`);

--
-- Constraints for table `tblcart`
--
ALTER TABLE `tblcart`
  ADD CONSTRAINT `tblcart_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `tbluser` (`userID`),
  ADD CONSTRAINT `tblcart_ibfk_2` FOREIGN KEY (`clothesID`) REFERENCES `tblclothes` (`clothesID`);

--
-- Constraints for table `tblsellerrequest`
--
ALTER TABLE `tblsellerrequest`
  ADD CONSTRAINT `tblsellerrequest_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `tbluser` (`userID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
