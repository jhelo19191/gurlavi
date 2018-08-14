-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 05, 2018 at 10:17 PM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `glc_monitoring_v2`
--

-- --------------------------------------------------------

--
-- Table structure for table `cash_info`
--

CREATE TABLE IF NOT EXISTS `cash_info` (
  `cash_id` int(12) NOT NULL AUTO_INCREMENT,
  `sales_order_id` int(12) NOT NULL,
  `amount` text NOT NULL,
  `payment_date` date NOT NULL,
  `image_path` text NOT NULL,
  `flag` int(1) NOT NULL,
  `status` int(1) NOT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cash_id`),
  KEY `fk_cash_info_sales_order1_idx` (`sales_order_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `cash_info`
--

INSERT INTO `cash_info` (`cash_id`, `sales_order_id`, `amount`, `payment_date`, `image_path`, `flag`, `status`, `created_date`) VALUES
(8, 42, '44', '2018-08-06', 'uploads/cash/20180805082945.jpg', 3, 6, '2018-08-05 06:29:45');

-- --------------------------------------------------------

--
-- Table structure for table `collection`
--

CREATE TABLE IF NOT EXISTS `collection` (
  `sales_order_id` int(12) NOT NULL,
  `cr_no` varchar(50) NOT NULL,
  `collect_date` date NOT NULL,
  `message` text NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `fk_collection_sales_order1_idx` (`sales_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `collection`
--

INSERT INTO `collection` (`sales_order_id`, `cr_no`, `collect_date`, `message`, `created_date`) VALUES
(42, '23123123123', '2018-08-13', 'asdas asdasd', '2018-08-05 06:31:01');

-- --------------------------------------------------------

--
-- Table structure for table `credit_card_info`
--

CREATE TABLE IF NOT EXISTS `credit_card_info` (
  `card_id` int(12) NOT NULL AUTO_INCREMENT,
  `sales_order_id` int(12) NOT NULL,
  `card_no` varchar(45) NOT NULL,
  `batch_no` varchar(45) NOT NULL,
  `approval_code` varchar(45) NOT NULL,
  `bank_name` text NOT NULL,
  `credit_card_amount` varchar(45) NOT NULL,
  `settlement_date` date NOT NULL,
  `terms` text NOT NULL,
  `image_path` text NOT NULL,
  `status` int(1) NOT NULL,
  `flag` int(1) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`card_id`),
  KEY `fk_credit_card_info_sales_order1_idx` (`sales_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
  `customers_id` int(12) NOT NULL AUTO_INCREMENT,
  `customer_name` text NOT NULL,
  `address` text NOT NULL,
  `contact_no` varchar(25) NOT NULL,
  `tin` varchar(25) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`customers_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customers_id`, `customer_name`, `address`, `contact_no`, `tin`, `created_date`) VALUES
(4, 'Shana delos Santos', 'asdasd', '4353453453454', '123123123123', '2018-08-05 01:21:06'),
(5, 'Jane Santos', 'asd asd', '12312312222', '2333333333', '2018-08-05 01:21:15');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_request`
--

CREATE TABLE IF NOT EXISTS `delivery_request` (
  `sales_order_id` int(12) NOT NULL,
  `actual_delivery_date` date NOT NULL,
  `message` text NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `fk_delivery_request_sales_order1_idx` (`sales_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `delivery_request`
--

INSERT INTO `delivery_request` (`sales_order_id`, `actual_delivery_date`, `message`, `created_date`) VALUES
(42, '2018-08-13', 'wqevqweqweqwe', '2018-08-05 05:32:14');

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE IF NOT EXISTS `images` (
  `sales_order_id` int(12) NOT NULL,
  `process_type` text NOT NULL,
  `image_path` text NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `fk_images_sales_order1_idx` (`sales_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`sales_order_id`, `process_type`, `image_path`, `created_date`) VALUES
(40, 'sales_order', 'uploads/sales_order/20180805032245.jpg', '2018-08-05 01:22:45'),
(40, 'sales_order', 'uploads/sales_order/20180805032245.jpg', '2018-08-05 01:22:45'),
(41, 'sales_order', 'uploads/sales_order/20180805032553.jpg', '2018-08-05 01:25:53'),
(42, 'sales_order', 'uploads/sales_order/20180805033149.jpg', '2018-08-05 01:31:49'),
(43, 'sales_order', 'uploads/sales_order/20180805033203.jpg', '2018-08-05 01:32:03'),
(44, 'sales_order', 'uploads/sales_order/20180805033210.jpg', '2018-08-05 01:32:10'),
(45, 'sales_order', 'uploads/sales_order/20180805033217.jpg', '2018-08-05 01:32:17'),
(46, 'sales_order', 'uploads/sales_order/20180805033229.jpg', '2018-08-05 01:32:29'),
(47, 'sales_order', 'uploads/sales_order/20180805033236.jpg', '2018-08-05 01:32:36'),
(42, 'invoice', 'uploads/invoice/20180805033604.jpg', '2018-08-05 01:36:04'),
(42, 'inventory', 'uploads/inventory/20180805033715.jpg', '2018-08-05 01:37:15'),
(42, 'delivery', 'uploads/delivery/20180805073214.jpg', '2018-08-05 05:32:14'),
(48, 'sales_order', 'uploads/sales_order/20180805134004.jpg', '2018-08-05 11:40:04');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE IF NOT EXISTS `inventory` (
  `sales_order_id` int(12) NOT NULL,
  `carrier_name` text NOT NULL,
  `position` text NOT NULL,
  `message` text NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `fk_inventory_sales_order1_idx` (`sales_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`sales_order_id`, `carrier_name`, `position`, `message`, `created_date`) VALUES
(42, 'Shane Anne', 'test admin', 'asdasdasd', '2018-08-05 01:37:16');

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE IF NOT EXISTS `notification` (
  `notification_id` int(12) NOT NULL AUTO_INCREMENT,
  `sales_order_id` int(12) NOT NULL,
  `message` text NOT NULL,
  `status` int(1) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`notification_id`),
  KEY `fk_notification_sales_order1_idx` (`sales_order_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`notification_id`, `sales_order_id`, `message`, `status`, `created_date`) VALUES
(5, 40, 'Angelo delos Santos created a sales order with the number: 444444444444', 1, '2018-08-05 01:22:45'),
(6, 41, 'Angelo delos Santos created a sales order with the number: 555555555555', 1, '2018-08-05 01:25:53'),
(7, 42, 'Angelo delos Santos created a sales order with the number: 563333333333', 1, '2018-08-05 01:31:50'),
(8, 43, 'Angelo delos Santos created a sales order with the number: 222222222222', 0, '2018-08-05 01:32:05'),
(9, 44, 'Angelo delos Santos created a sales order with the number: 111111111111', 1, '2018-08-05 01:32:12'),
(10, 45, 'Angelo delos Santos created a sales order with the number: 777777777777', 0, '2018-08-05 01:32:19'),
(11, 46, 'Angelo delos Santos created a sales order with the number: 888888888888', 1, '2018-08-05 01:32:30'),
(12, 47, 'Angelo delos Santos created a sales order with the number: 999999999999', 0, '2018-08-05 01:32:38'),
(13, 48, 'sales admin created a sales order with the number: 898989898898', 0, '2018-08-05 11:40:06');

-- --------------------------------------------------------

--
-- Table structure for table `onhold`
--

CREATE TABLE IF NOT EXISTS `onhold` (
  `transaction_id` int(50) NOT NULL AUTO_INCREMENT,
  `receiver_id` int(12) NOT NULL,
  `payment_type` text NOT NULL,
  `amount` double NOT NULL,
  `created_by` text NOT NULL,
  `updated_date` date NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`transaction_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `onhold`
--

INSERT INTO `onhold` (`transaction_id`, `receiver_id`, `payment_type`, `amount`, `created_by`, `updated_date`, `created_date`) VALUES
(4, 9, 'pdc', 21312313, 'Accounting', '2018-08-05', '2018-08-05 13:22:38');

-- --------------------------------------------------------

--
-- Table structure for table `payment_comments`
--

CREATE TABLE IF NOT EXISTS `payment_comments` (
  `payment_id` int(12) NOT NULL,
  `payment_type` varchar(25) NOT NULL,
  `message` text NOT NULL,
  `sender_id` int(12) NOT NULL,
  `receiver_id` int(12) NOT NULL,
  `flag` int(1) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment_comments`
--

INSERT INTO `payment_comments` (`payment_id`, `payment_type`, `message`, `sender_id`, `receiver_id`, `flag`, `created_date`) VALUES
(19, '2', 'pdc check and approved', 7, 7, 0, '2018-08-05 05:58:30'),
(20, '2', 'pdc check and approved', 7, 7, 0, '2018-08-05 05:58:49'),
(21, '2', 'pdc check and approved', 7, 7, 0, '2018-08-05 05:58:54'),
(22, '2', 'pdc check and approved', 7, 7, 0, '2018-08-05 05:58:58'),
(23, '2', 'pdc check and approved', 7, 7, 0, '2018-08-05 05:59:01'),
(24, '2', 'pdc check and approved', 7, 7, 0, '2018-08-05 05:59:05'),
(24, '2', 'please remove this payment', 7, 7, 0, '2018-08-05 06:10:06'),
(23, '2', 'please remove this payment', 7, 7, 0, '2018-08-05 06:10:29'),
(22, '2', 'Please remove this payment', 7, 7, 0, '2018-08-05 06:10:51'),
(21, '2', 'update this payment', 7, 7, 0, '2018-08-05 06:11:10'),
(21, '2', 'change amount to exact value', 7, 7, 0, '2018-08-05 06:13:30'),
(19, '2', 'bounce', 7, 7, 0, '2018-08-05 06:17:21'),
(20, '2', 'wrong date', 7, 7, 0, '2018-08-05 06:17:29'),
(19, '2', 'check has been changed', 7, 7, 0, '2018-08-05 06:20:03'),
(25, '2', 'asda asdasd', 7, 7, 0, '2018-08-05 06:28:49'),
(26, '2', 'asda asdasd', 7, 7, 0, '2018-08-05 06:29:00'),
(27, '2', 'asda asdasd', 7, 7, 0, '2018-08-05 06:29:17'),
(8, '1', '32132121', 7, 7, 0, '2018-08-05 06:29:45'),
(25, '2', 'wrong date', 7, 7, 0, '2018-08-05 06:32:15'),
(26, '2', 'wrong amount', 7, 7, 0, '2018-08-05 06:32:26'),
(26, '2', 'the amount of cheque is wrong', 7, 7, 0, '2018-08-05 06:34:11'),
(26, '2', 'asdasdasd asd', 7, 7, 0, '2018-08-05 07:05:58'),
(27, '2', 'asdasdasd ssssssss', 7, 7, 0, '2018-08-05 07:06:04'),
(25, '2', 'bounce', 7, 7, 0, '2018-08-05 07:08:55'),
(26, '2', 'wrong date', 7, 7, 0, '2018-08-05 07:09:03'),
(25, '2', 'cheque change', 7, 7, 0, '2018-08-05 07:11:34');

-- --------------------------------------------------------

--
-- Table structure for table `pdc_info`
--

CREATE TABLE IF NOT EXISTS `pdc_info` (
  `pdc_id` int(25) NOT NULL AUTO_INCREMENT,
  `sales_order_id` int(12) NOT NULL,
  `account_no` varchar(45) NOT NULL,
  `check_no` varchar(45) NOT NULL,
  `account_name` text NOT NULL,
  `bank_name` text NOT NULL,
  `branch` text NOT NULL,
  `cheque_date` date NOT NULL,
  `amount` double NOT NULL,
  `status` int(1) NOT NULL,
  `terms` text,
  `flag` int(1) NOT NULL COMMENT '0=PENDING, 1=REJECT, 2=ACCEPT',
  `image_path` text NOT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pdc_id`),
  KEY `fk_pdc_info_sales_order1_idx` (`sales_order_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `pdc_info`
--

INSERT INTO `pdc_info` (`pdc_id`, `sales_order_id`, `account_no`, `check_no`, `account_name`, `bank_name`, `branch`, `cheque_date`, `amount`, `status`, `terms`, `flag`, `image_path`, `created_date`) VALUES
(25, 42, '123123', '12345', '123123123123', 'BDO', 'San Juan Metro Manila', '2018-08-08', 1212, 7, '3days', 2, 'uploads/pdc/20180805091133.jpg', '2018-08-05 06:28:49'),
(26, 42, '123123', '12346', '123123123123', 'BDO', 'San Juan Metro Manila', '2018-08-13', 1212, 7, '3days', 2, 'uploads/pdc/20180805083411.jpg', '2018-08-05 06:29:00'),
(27, 42, '123123', '12347', '123123123123', 'BDO', 'San Juan Metro Manila', '2018-08-10', 1212, 7, '3days', 2, 'uploads/pdc/20180805082917.jpg', '2018-08-05 06:29:17');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `product_id` int(12) NOT NULL AUTO_INCREMENT,
  `product_name` text NOT NULL,
  `description` text NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `description`, `created_date`) VALUES
(8, 'BUN002MED', 'asd asdasd', '2018-08-05 01:20:33'),
(9, 'ccccccccc', 'ccccccccccc', '2018-08-05 01:20:39');

-- --------------------------------------------------------

--
-- Table structure for table `reg_products`
--

CREATE TABLE IF NOT EXISTS `reg_products` (
  `sales_order_id` int(12) NOT NULL,
  `product_id` int(12) NOT NULL,
  `quantity` int(20) NOT NULL,
  `unit` varchar(25) NOT NULL,
  `price` varchar(25) NOT NULL,
  KEY `fk_reg_products_products1_idx` (`product_id`),
  KEY `fk_reg_products_sales_order1_idx` (`sales_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reg_products`
--

INSERT INTO `reg_products` (`sales_order_id`, `product_id`, `quantity`, `unit`, `price`) VALUES
(40, 8, 23, 'box', '233'),
(40, 8, 23, 'box', '233'),
(40, 8, 23, 'box', '233'),
(40, 8, 23, 'box', '233'),
(41, 9, 33, 'box', '232'),
(41, 9, 33, 'box', '232'),
(41, 9, 33, 'box', '232'),
(41, 9, 33, 'box', '232'),
(41, 9, 33, 'box', '232'),
(42, 8, 23, 'asdasdas', '32'),
(42, 8, 23, 'asdasdas', '32'),
(42, 8, 23, 'asdasdas', '32'),
(42, 8, 23, 'asdasdas', '32'),
(42, 8, 23, 'asdasdas', '32'),
(43, 8, 23, 'asdasdas', '32'),
(43, 8, 23, 'asdasdas', '32'),
(43, 8, 23, 'asdasdas', '32'),
(43, 8, 23, 'asdasdas', '32'),
(43, 8, 23, 'asdasdas', '32'),
(44, 8, 23, 'asdasdas', '32'),
(44, 8, 23, 'asdasdas', '32'),
(44, 8, 23, 'asdasdas', '32'),
(44, 8, 23, 'asdasdas', '32'),
(44, 8, 23, 'asdasdas', '32'),
(45, 8, 23, 'asdasdas', '32'),
(45, 8, 23, 'asdasdas', '32'),
(45, 8, 23, 'asdasdas', '32'),
(45, 8, 23, 'asdasdas', '32'),
(45, 8, 23, 'asdasdas', '32'),
(46, 8, 23, 'asdasdas', '32'),
(46, 8, 23, 'asdasdas', '32'),
(46, 8, 23, 'asdasdas', '32'),
(46, 8, 23, 'asdasdas', '32'),
(46, 8, 23, 'asdasdas', '32'),
(47, 8, 23, 'asdasdas', '32'),
(47, 8, 23, 'asdasdas', '32'),
(47, 8, 23, 'asdasdas', '32'),
(47, 8, 23, 'asdasdas', '32'),
(47, 8, 23, 'asdasdas', '32'),
(48, 8, 32, 'asdasd', '2323'),
(48, 8, 32, 'asdasd', '2323'),
(48, 8, 32, 'asdasd', '2323');

-- --------------------------------------------------------

--
-- Table structure for table `remittance`
--

CREATE TABLE IF NOT EXISTS `remittance` (
  `sales_order_id` int(12) NOT NULL,
  `remittance_no` varchar(50) NOT NULL,
  `remittance_date` date NOT NULL,
  `message` text NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `fk_remittance_sales_order1_idx` (`sales_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `remittance`
--

INSERT INTO `remittance` (`sales_order_id`, `remittance_no`, `remittance_date`, `message`, `created_date`) VALUES
(42, '666666666666666666', '2018-08-15', 'sad a sd asda sdasd', '2018-08-05 06:16:26');

-- --------------------------------------------------------

--
-- Table structure for table `sales_invoice`
--

CREATE TABLE IF NOT EXISTS `sales_invoice` (
  `sales_order_id` int(12) NOT NULL,
  `invoice_no` varchar(45) NOT NULL,
  `invoice_date` date NOT NULL,
  `message` text NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `fk_sales_invoice_sales_order1_idx` (`sales_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sales_invoice`
--

INSERT INTO `sales_invoice` (`sales_order_id`, `invoice_no`, `invoice_date`, `message`, `created_date`) VALUES
(42, '3333333', '2018-08-05', 'asd asdasd', '2018-08-05 01:36:04');

-- --------------------------------------------------------

--
-- Table structure for table `sales_order`
--

CREATE TABLE IF NOT EXISTS `sales_order` (
  `sales_order_id` int(12) NOT NULL AUTO_INCREMENT,
  `customers_id` int(12) NOT NULL,
  `account_id` int(12) NOT NULL,
  `sales_order_no` varchar(50) NOT NULL,
  `psr_name` varchar(150) NOT NULL,
  `si_number` varchar(12) DEFAULT NULL,
  `approved_date` date NOT NULL,
  `delivery_date` date NOT NULL,
  `so_date` date NOT NULL,
  `ship_to` text NOT NULL,
  `status` int(1) NOT NULL,
  `comments` text NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sales_order_id`),
  KEY `fk_sales_order_customers1_idx` (`customers_id`),
  KEY `fk_sales_order_system_accounts1_idx` (`account_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=49 ;

--
-- Dumping data for table `sales_order`
--

INSERT INTO `sales_order` (`sales_order_id`, `customers_id`, `account_id`, `sales_order_no`, `psr_name`, `si_number`, `approved_date`, `delivery_date`, `so_date`, `ship_to`, `status`, `comments`, `created_date`) VALUES
(40, 4, 7, '444444444444', 'Lucy', '', '2018-08-05', '2018-08-10', '2018-08-05', 'asd asdasd asdasdasd', 0, 'asda sdasda sdasd', '2018-08-05 01:22:45'),
(41, 5, 7, '555555555555', 'shane', '', '2018-08-05', '2018-08-10', '2018-08-05', 'asd asd asd asda sd asd asd', 0, 'asd asdasdasd', '2018-08-05 01:25:52'),
(42, 4, 7, '563333333333', 'gggggg', '', '2018-08-05', '2018-08-10', '2018-08-05', 'asd as dasdasd', 9, 'asd asd asd a sd', '2018-08-05 01:31:49'),
(43, 4, 7, '222222222222', 'gggggg', '', '2018-08-05', '2018-08-10', '2018-08-05', 'asd as dasdasd', 0, 'asd asd asd a sd', '2018-08-05 01:32:03'),
(44, 4, 7, '111111111111', 'gggggg', '', '2018-08-05', '2018-08-10', '2018-08-05', 'asd as dasdasd', 0, 'asd asd asd a sd', '2018-08-05 01:32:10'),
(45, 4, 7, '777777777777', 'gggggg', '', '2018-08-05', '2018-08-10', '2018-08-05', 'asd as dasdasd', 0, 'asd asd asd a sd', '2018-08-05 01:32:17'),
(46, 4, 7, '888888888888', 'gggggg', '', '2018-08-05', '2018-08-10', '2018-08-05', 'asd as dasdasd', 0, 'asd asd asd a sd', '2018-08-05 01:32:29'),
(47, 4, 7, '999999999999', 'gggggg', '', '2018-08-05', '2018-08-10', '2018-08-05', 'asd as dasdasd', 0, 'asd asd asd a sd', '2018-08-05 01:32:36'),
(48, 4, 9, '898989898898', 'asdasdasd', '', '2018-08-05', '2018-08-10', '2018-08-05', 'qweqweq', 0, 'asdasdasd', '2018-08-05 11:40:04');

-- --------------------------------------------------------

--
-- Table structure for table `system_accounts`
--

CREATE TABLE IF NOT EXISTS `system_accounts` (
  `account_id` int(12) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `email` text NOT NULL,
  `c_number` varchar(25) DEFAULT NULL,
  `position` text,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `user_level` int(1) NOT NULL,
  `account_status` int(1) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`account_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `system_accounts`
--

INSERT INTO `system_accounts` (`account_id`, `name`, `email`, `c_number`, `position`, `username`, `password`, `user_level`, `account_status`, `created_date`) VALUES
(7, 'Angelo delos Santos', 'ankeo19angelo@gmail.com', 'null', 'null', 'admin', '2w0jNNAQ7oNTo0koYCkcv0Ri7Qzq6L1/Xqp8KaMW8tA7KJoFyzCtR/6Vth1g2xDZjmHj9xYuerzI+qZhdISUow==', 0, 1, '2018-08-05 01:19:06'),
(8, 'PSR', 'psr@gmail.com', '213123123', 'PSR', 'pnspns', '6UatKFKOrWx0grGG9E2pPqejs5tUMv2W3ii3AcrzsQ1n161OVCRsZ2OF5mKp02pb4coe/KDYlTNNiqOUuejRfA==', 3, 1, '2018-08-05 10:49:07'),
(9, 'sales admin', 'sales_admin@gmail.com', '23123123', 'sales admin', 'sasa', 'yaXq0TNzOfsmkgv0psWfgGlovJtfSdwQQmPMG/VgvAVuMsE6R/J0oBV55TJS9UhMDscTp+otzsPYQnIfV7R7sA==', 4, 1, '2018-08-05 10:50:39'),
(10, 'Accounting', 'accounting@gmail.com', '123132123123', 'accounting', 'accounting', 'd1pDIj/q2FMnZn+kIf7pTT530jstgHXCACx3t1tVk0aq3b9K3RPXRy4rOPadQYmyAQuQJBbrgjASpzYCCVi/dg==', 1, 1, '2018-08-05 10:51:23');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cash_info`
--
ALTER TABLE `cash_info`
  ADD CONSTRAINT `fk_cash_info_sales_order1` FOREIGN KEY (`sales_order_id`) REFERENCES `sales_order` (`sales_order_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `collection`
--
ALTER TABLE `collection`
  ADD CONSTRAINT `fk_collection_sales_order1` FOREIGN KEY (`sales_order_id`) REFERENCES `sales_order` (`sales_order_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `credit_card_info`
--
ALTER TABLE `credit_card_info`
  ADD CONSTRAINT `fk_credit_card_info_sales_order1` FOREIGN KEY (`sales_order_id`) REFERENCES `sales_order` (`sales_order_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `delivery_request`
--
ALTER TABLE `delivery_request`
  ADD CONSTRAINT `fk_delivery_request_sales_order1` FOREIGN KEY (`sales_order_id`) REFERENCES `sales_order` (`sales_order_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `fk_images_sales_order1` FOREIGN KEY (`sales_order_id`) REFERENCES `sales_order` (`sales_order_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `fk_inventory_sales_order1` FOREIGN KEY (`sales_order_id`) REFERENCES `sales_order` (`sales_order_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `fk_notification_sales_order1` FOREIGN KEY (`sales_order_id`) REFERENCES `sales_order` (`sales_order_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pdc_info`
--
ALTER TABLE `pdc_info`
  ADD CONSTRAINT `fk_pdc_info_sales_order1` FOREIGN KEY (`sales_order_id`) REFERENCES `sales_order` (`sales_order_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `reg_products`
--
ALTER TABLE `reg_products`
  ADD CONSTRAINT `fk_reg_products_products1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_reg_products_sales_order1` FOREIGN KEY (`sales_order_id`) REFERENCES `sales_order` (`sales_order_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `remittance`
--
ALTER TABLE `remittance`
  ADD CONSTRAINT `fk_remittance_sales_order1` FOREIGN KEY (`sales_order_id`) REFERENCES `sales_order` (`sales_order_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `sales_invoice`
--
ALTER TABLE `sales_invoice`
  ADD CONSTRAINT `fk_sales_invoice_sales_order1` FOREIGN KEY (`sales_order_id`) REFERENCES `sales_order` (`sales_order_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `sales_order`
--
ALTER TABLE `sales_order`
  ADD CONSTRAINT `fk_sales_order_customers1` FOREIGN KEY (`customers_id`) REFERENCES `customers` (`customers_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_sales_order_system_accounts1` FOREIGN KEY (`account_id`) REFERENCES `system_accounts` (`account_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
