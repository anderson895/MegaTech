-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 23, 2025 at 10:34 AM
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
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `cart_user_id` int(11) NOT NULL,
  `cart_prod_id` int(11) NOT NULL,
  `cart_Qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(9, 'Lightning fixture', NULL, 1),
(10, 'Accessories', NULL, 1),
(11, 'Guard Rails', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `headstaff`
--

CREATE TABLE `headstaff` (
  `hs_id` int(11) NOT NULL,
  `hs_fullname` varchar(60) NOT NULL,
  `hs_username` varchar(60) NOT NULL,
  `hs_password` varchar(255) NOT NULL,
  `hs_status` int(11) NOT NULL DEFAULT 1 COMMENT '0=unactive.1=active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `headstaff`
--

INSERT INTO `headstaff` (`hs_id`, `hs_fullname`, `hs_username`, `hs_password`, `hs_status`) VALUES
(1, 'Ian Mark', 'headstaff', 'dca08eaca0ec6ccfe0b182d8adb36c19226445333b165402079aa7ecd7e231b4', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_code` varchar(60) NOT NULL,
  `order_user_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `order_payment_method` varchar(60) NOT NULL,
  `order_down_payment_receipt` varchar(255) NOT NULL,
  `order_pickup_date` date DEFAULT NULL,
  `order_pickup_time` time DEFAULT NULL,
  `order_total` decimal(10,2) NOT NULL,
  `order_balance` decimal(10,2) NOT NULL,
  `order_balance_payment_receipt` varchar(255) DEFAULT NULL,
  `order_status` varchar(60) NOT NULL DEFAULT 'pending',
  `order_proof_recieved` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_code`, `order_user_id`, `order_date`, `order_payment_method`, `order_down_payment_receipt`, `order_pickup_date`, `order_pickup_time`, `order_total`, `order_balance`, `order_balance_payment_receipt`, `order_status`, `order_proof_recieved`) VALUES
(24, 'ORD-D24F214C', 72, '2025-07-23 06:14:53', 'GCash', 'proof_68807b2b802832.48801730.jpg', '2025-07-23', '14:13:00', 79000.00, 39500.00, NULL, 'pickedup', 'pickedup_68807dddb52587.80788592.jpg'),
(25, 'ORD-7719DB04', 76, '2025-01-23 08:33:28', 'GCash', 'proof_68809ddc4031c6.16009405.jpg', '2025-07-23', '16:33:00', 238000.00, 119000.00, NULL, 'pickedup', 'pickedup_68809e58420215.75667259.png');

-- --------------------------------------------------------

--
-- Table structure for table `orders_item`
--

CREATE TABLE `orders_item` (
  `item_id` int(11) NOT NULL,
  `item_order_id` int(11) NOT NULL,
  `item_product_id` int(11) NOT NULL,
  `item_product_price` decimal(10,2) NOT NULL,
  `item_qty` int(11) NOT NULL,
  `item_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders_item`
--

INSERT INTO `orders_item` (`item_id`, `item_order_id`, `item_product_id`, `item_product_price`, `item_qty`, `item_total`) VALUES
(36, 24, 6, 29000.00, 1, 29000.00),
(37, 24, 5, 50000.00, 1, 50000.00),
(38, 25, 4, 30000.00, 1, 30000.00),
(39, 25, 5, 50000.00, 3, 150000.00),
(40, 25, 6, 29000.00, 2, 58000.00);

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
(4, 'A0002', 'Solar Panel', 'Transform your operations with robust Solar Panels technology for efficiency and savings', '[{\"Specs\":\"Panel type\",\"value\":\"Monocrystalline, Polycrystalline, Thin-film\"},{\"Specs\":\"Power output\",\"value\":\"50W, 100W, 150W, 300W\"},{\"Specs\":\"Voltage Rating\",\"value\":\"12V, 24V\"},{\"Specs\":\"Size\",\"value\":\"1200mm x 540mm x 35mm\"},{\"Specs\":\"Weight\",\"value\":\"7.5 kg\"},{\"Specs\":\"Mounting Type\",\"value\":\"Roof-mounted, Ground-mounted, Pole-mounted\"}]', 13, 2, 30000.00, 10, 1, 'product_687700c5b87b41.71450609.jpg', '2025-07-16 09:30:45'),
(5, 'A0003', 'Electric Pole', 'Installation Area – Highway, Subdivision, Industrial Area', '[{\"Specs\":\"Pole Type\",\"value\":\"Concrete Pole, Steel Pole\"},{\"Specs\":\"Height\",\"value\":\"7m, 10m, 12m\"},{\"Specs\":\"Load Capacity\",\"value\":\"300kg, 500kg\"}]', 87, 5, 50000.00, 10, 1, 'product_687712af7c7e43.51042686.jpg', '2025-07-16 10:47:11'),
(6, 'A0004', 'Akumulator AGM Monbat MEGALIGHT AGM 230Ah 12 V / 230 Ah', 'Akumulator AGM Megalight Power 12V 230AH.Akumulator głębokiego rozładowania. wymiary w mm: dł/szer/wys/ 518/274/242. Waga 59 kg. Układ biegunów 3.', '[{\"Specs\":\"Battery Type\",\"value\":\"Lithium-ion, LiFePO4, Lead Acid\"},{\"Specs\":\"Voltage Rating\",\"value\":\"3.7V, 6V, 12V\"},{\"Specs\":\"Capacity\",\"value\":\"5000mAh, 100Ah\"},{\"Specs\":\"Size\",\"value\":\"150mm x 65mm x 90mm\"}]', 98, 3, 29000.00, 15, 1, 'product_6877138e30f989.41089661.jpg', '2025-07-16 10:50:54');

-- --------------------------------------------------------

--
-- Table structure for table `return_order`
--

CREATE TABLE `return_order` (
  `return_id` int(11) NOT NULL,
  `return_item_id` int(11) NOT NULL,
  `return_qty` int(11) NOT NULL,
  `return_proof` varchar(255) NOT NULL,
  `return_reason` text NOT NULL,
  `return_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `return_status` int(11) NOT NULL DEFAULT 1 COMMENT '0=archived,1=request,2=cancel by admin, 3=cancel by customer,4=approve'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `return_order`
--

INSERT INTO `return_order` (`return_id`, `return_item_id`, `return_qty`, `return_proof`, `return_reason`, `return_date`, `return_status`) VALUES
(6, 36, 1, 'return_68807e4fb23f72.67171588.jpg', 'damaged', '2025-07-23 06:24:40', 2);

-- --------------------------------------------------------

--
-- Table structure for table `stock_history`
--

CREATE TABLE `stock_history` (
  `stock_id` int(11) NOT NULL,
  `stock_prod_id` int(11) NOT NULL,
  `stock_account_id` int(11) NOT NULL,
  `stock_type` varchar(20) NOT NULL,
  `user_type` varchar(20) NOT NULL,
  `stock_Qty` int(11) NOT NULL,
  `stock_changes` text NOT NULL,
  `stock_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_history`
--

INSERT INTO `stock_history` (`stock_id`, `stock_prod_id`, `stock_account_id`, `stock_type`, `user_type`, `stock_Qty`, `stock_changes`, `stock_date`) VALUES
(50, 6, 1, 'Stock Out', 'HeadStaff', 1, '90 -> 89', '2025-07-18 10:37:19'),
(51, 5, 1, 'Stock Out', 'HeadStaff', 1, '95 -> 94', '2025-07-18 10:37:19'),
(52, 4, 1, 'Stock Out', 'HeadStaff', 1, '21 -> 20', '2025-07-18 10:37:19'),
(53, 6, 1, 'Stock Out', 'HeadStaff', 1, '89 -> 88', '2025-07-18 10:38:52'),
(54, 5, 1, 'Stock Out', 'HeadStaff', 1, '94 -> 93', '2025-07-18 10:38:52'),
(55, 4, 1, 'Stock Out', 'HeadStaff', 1, '20 -> 19', '2025-07-18 10:38:52'),
(56, 4, 1, 'Stock Out', 'HeadStaff', 1, '19 -> 18', '2025-07-18 10:49:42'),
(57, 6, 1, 'Stock Out', 'HeadStaff', 1, '88 -> 87', '2025-07-18 10:49:42'),
(58, 5, 1, 'Stock Out', 'HeadStaff', 1, '93 -> 92', '2025-07-18 10:49:42'),
(59, 4, 1, 'Stock In', 'Administrator', 2, '18 -> 20', '2025-07-23 01:09:20'),
(60, 4, 1, 'Stock In', 'Administrator', 23, '20 -> 43', '2025-07-23 05:15:36'),
(61, 4, 1, 'Stock In', 'Administrator', 100, '43 -> 143', '2025-07-23 05:17:24'),
(62, 4, 1, 'Stock In', 'Administrator', 2, '143 -> 145', '2025-07-23 05:18:06'),
(63, 4, 1, 'Stock In', 'Administrator', 5, '9 -> 14', '2025-07-23 05:40:01'),
(64, 6, 1, 'Stock In', 'Administrator', 100, '2 -> 102', '2025-07-23 05:42:43'),
(65, 6, 1, 'Stock Out', 'HeadStaff', 1, '102 -> 101', '2025-07-23 06:10:49'),
(66, 5, 1, 'Stock Out', 'HeadStaff', 1, '92 -> 91', '2025-07-23 06:10:49'),
(67, 6, 1, 'Stock Out', 'HeadStaff', 1, '101 -> 100', '2025-07-23 06:12:04'),
(68, 5, 1, 'Stock Out', 'HeadStaff', 1, '91 -> 90', '2025-07-23 06:12:04'),
(69, 4, 1, 'Stock Out', 'HeadStaff', 1, '14 -> 13', '2025-07-23 08:32:12'),
(70, 5, 1, 'Stock Out', 'HeadStaff', 3, '90 -> 87', '2025-07-23 08:32:12'),
(71, 6, 1, 'Stock Out', 'HeadStaff', 2, '100 -> 98', '2025-07-23 08:32:12');

-- --------------------------------------------------------

--
-- Table structure for table `supply_logs`
--

CREATE TABLE `supply_logs` (
  `sl_id` int(11) NOT NULL,
  `sl_stock_id` int(11) NOT NULL,
  `sl_supplier_name` varchar(60) NOT NULL,
  `sl_supplier_price` decimal(10,2) NOT NULL,
  `sl_supplier_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supply_logs`
--

INSERT INTO `supply_logs` (`sl_id`, `sl_stock_id`, `sl_supplier_name`, `sl_supplier_price`, `sl_supplier_date`) VALUES
(1, 61, 'j supply', 66.00, '2025-07-23 05:17:24'),
(2, 62, 'j supply', 100.00, '2025-07-23 05:18:06'),
(3, 63, 'dawdaw', 10.00, '2025-07-23 05:40:01'),
(4, 64, 'j supply', 100.00, '2025-07-23 05:42:43');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `Fullname` varchar(60) NOT NULL,
  `Email` varchar(60) NOT NULL,
  `Phone` varchar(60) NOT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `user_type` varchar(11) NOT NULL,
  `Profile_images` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0=Not Verified,1=Verified'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `Fullname`, `Email`, `Phone`, `Password`, `user_type`, `Profile_images`, `status`) VALUES
(72, 'joshua padilla', 'andersonandy046@gmail.com', '09454454741', '61e36b4d463fcf248af31898805050d4b137bb54e74c4e7e9b95b35ccb0f9753', 'personal', NULL, 1),
(75, 'mary jane', 'maryjanedelacruz613@gmail.com', '09454454744', 'ecff9a01e0c1a3129a2bfaff0d90bdc3db6d5092c8ee42c94041425e236c02ec', 'business', NULL, 1),
(76, 'juan dela cruz', 'mypet02025@gmail.com', '09454454744', 'f21cc5777f91245e7b14d8721e7799fe9e17de2c053bb7fdb164c662dd1a6383', 'business', NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `cart_prod_id` (`cart_prod_id`),
  ADD KEY `cart_user_id` (`cart_user_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `headstaff`
--
ALTER TABLE `headstaff`
  ADD PRIMARY KEY (`hs_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `order_code` (`order_code`);

--
-- Indexes for table `orders_item`
--
ALTER TABLE `orders_item`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `item_order_id` (`item_order_id`),
  ADD KEY `item_product_id` (`item_product_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`prod_id`);

--
-- Indexes for table `return_order`
--
ALTER TABLE `return_order`
  ADD PRIMARY KEY (`return_id`),
  ADD KEY `return_item_id` (`return_item_id`);

--
-- Indexes for table `stock_history`
--
ALTER TABLE `stock_history`
  ADD PRIMARY KEY (`stock_id`),
  ADD KEY `stock_raw_id` (`stock_prod_id`),
  ADD KEY `stock_user_id` (`stock_account_id`);

--
-- Indexes for table `supply_logs`
--
ALTER TABLE `supply_logs`
  ADD PRIMARY KEY (`sl_id`),
  ADD KEY `sl_stock_id` (`sl_stock_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=304;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `headstaff`
--
ALTER TABLE `headstaff`
  MODIFY `hs_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `orders_item`
--
ALTER TABLE `orders_item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `prod_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `return_order`
--
ALTER TABLE `return_order`
  MODIFY `return_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `stock_history`
--
ALTER TABLE `stock_history`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `supply_logs`
--
ALTER TABLE `supply_logs`
  MODIFY `sl_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders_item`
--
ALTER TABLE `orders_item`
  ADD CONSTRAINT `orders_item_ibfk_1` FOREIGN KEY (`item_order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_item_ibfk_2` FOREIGN KEY (`item_product_id`) REFERENCES `product` (`prod_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `return_order`
--
ALTER TABLE `return_order`
  ADD CONSTRAINT `return_order_ibfk_1` FOREIGN KEY (`return_item_id`) REFERENCES `orders_item` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stock_history`
--
ALTER TABLE `stock_history`
  ADD CONSTRAINT `stock_history_ibfk_1` FOREIGN KEY (`stock_prod_id`) REFERENCES `product` (`prod_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `supply_logs`
--
ALTER TABLE `supply_logs`
  ADD CONSTRAINT `supply_logs_ibfk_1` FOREIGN KEY (`sl_stock_id`) REFERENCES `stock_history` (`stock_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
