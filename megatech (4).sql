-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 16, 2025 at 04:52 AM
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
-- Database: `megatech`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `admin_username` varchar(60) NOT NULL,
  `admin_password` varchar(255) NOT NULL,
  `admin_fullname` varchar(60) NOT NULL,
  `admin_status` int(11) NOT NULL DEFAULT 1 COMMENT '0=deleted,1=active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `admin_username`, `admin_password`, `admin_fullname`, `admin_status`) VALUES
(1, 'admin', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', 'Juan Dela Cruz', 1),
(7, 'andy', '6177321eac992341d1ad0823a07e76bfc4ee6909db120e377ea303fdc216756c', 'Joshua Padilla', 1);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(60) NOT NULL,
  `category_description` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0=InActive 1=Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `category_description`, `status`) VALUES
(1, 'solar lights', NULL, 1),
(2, 'solar panels', NULL, 1),
(3, 'battery', NULL, 1),
(4, 'lamp pole', NULL, 1),
(5, 'electric pole', NULL, 1),
(6, 'commericial lights', NULL, 1),
(7, 'industrial lights', NULL, 1),
(8, 'Landscape lights', NULL, 1),
(9, 'Lightning fixture', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `prod_id` int(11) NOT NULL,
  `prod_code` varchar(60) NOT NULL,
  `prod_name` varchar(60) NOT NULL,
  `prod_description` text NOT NULL,
  `prod_specs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`prod_specs`)),
  `prod_stocks` int(11) NOT NULL,
  `prod_category_id` int(11) NOT NULL,
  `prod_price` decimal(10,2) NOT NULL,
  `prod_critical` int(11) NOT NULL,
  `prod_status` int(11) NOT NULL COMMENT '0=unactive,1=active',
  `prod_image` varchar(255) NOT NULL,
  `prod_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`prod_id`, `prod_code`, `prod_name`, `prod_description`, `prod_specs`, `prod_stocks`, `prod_category_id`, `prod_price`, `prod_critical`, `prod_status`, `prod_image`, `prod_added`) VALUES
(3, 'A0001', 'LED Industrial Grade Solar Street lights IP67', '300W Megalight High Brightness LED Industrial Grade Solar Street lights IP67', '[{\"Specs\":\"color\",\"value\":\"orange\"},{\"Specs\":\"brand\",\"value\":\"yamaha\"}]', 10, 2, 4500.00, 10, 0, 'product_687683437e3086.19790545.jpg', '2025-07-16 08:57:30'),
(4, 'A0002', 'Solar Panel', 'Transform your operations with robust Solar Panels technology for efficiency and savings', '[{\"Specs\":\"Panel type\",\"value\":\"Monocrystalline, Polycrystalline, Thin-film\"},{\"Specs\":\"Power output\",\"value\":\"50W, 100W, 150W, 300W\"},{\"Specs\":\"Voltage Rating\",\"value\":\"12V, 24V\"},{\"Specs\":\"Size\",\"value\":\"1200mm x 540mm x 35mm\"},{\"Specs\":\"Weight\",\"value\":\"7.5 kg\"},{\"Specs\":\"Mounting Type\",\"value\":\"Roof-mounted, Ground-mounted, Pole-mounted\"}]', 16, 2, 30000.00, 10, 1, 'product_687700c5b87b41.71450609.jpg', '2025-07-16 09:30:45'),
(5, 'A0003', 'Electric Pole', 'Installation Area – Highway, Subdivision, Industrial Area', '[{\"Specs\":\"Pole Type\",\"value\":\"Concrete Pole, Steel Pole\"},{\"Specs\":\"Height\",\"value\":\"7m, 10m, 12m\"},{\"Specs\":\"Load Capacity\",\"value\":\"300kg, 500kg\"}]', 100, 5, 50000.00, 10, 1, 'product_687712af7c7e43.51042686.jpg', '2025-07-16 10:47:11'),
(6, 'A0004', 'Akumulator AGM Monbat MEGALIGHT AGM 230Ah 12 V / 230 Ah', 'Akumulator AGM Megalight Power 12V 230AH.Akumulator głębokiego rozładowania. wymiary w mm: dł/szer/wys/ 518/274/242. Waga 59 kg. Układ biegunów 3.', '[{\"Specs\":\"Battery Type\",\"value\":\"Lithium-ion, LiFePO4, Lead Acid\"},{\"Specs\":\"Voltage Rating\",\"value\":\"3.7V, 6V, 12V\"},{\"Specs\":\"Capacity\",\"value\":\"5000mAh, 100Ah\"},{\"Specs\":\"Size\",\"value\":\"150mm x 65mm x 90mm\"}]', 100, 3, 29000.00, 15, 1, 'product_6877138e30f989.41089661.jpg', '2025-07-16 10:50:54');

-- --------------------------------------------------------

--
-- Table structure for table `stock_history`
--

CREATE TABLE `stock_history` (
  `stock_id` int(11) NOT NULL,
  `stock_prod_id` int(11) NOT NULL,
  `stock_admin_id` int(11) NOT NULL,
  `stock_type` varchar(60) NOT NULL,
  `stock_outQty` int(11) NOT NULL,
  `stock_changes` text NOT NULL,
  `stock_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_history`
--

INSERT INTO `stock_history` (`stock_id`, `stock_prod_id`, `stock_admin_id`, `stock_type`, `stock_outQty`, `stock_changes`, `stock_date`) VALUES
(31, 9, 1, 'Stock In', 10, '792 -> 802', '2025-05-29 12:42:13'),
(32, 4, 1, 'Stock In', 3, '13 -> 16', '2025-07-16 01:55:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`prod_id`);

--
-- Indexes for table `stock_history`
--
ALTER TABLE `stock_history`
  ADD PRIMARY KEY (`stock_id`),
  ADD KEY `stock_raw_id` (`stock_prod_id`),
  ADD KEY `stock_user_id` (`stock_admin_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `prod_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `stock_history`
--
ALTER TABLE `stock_history`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
