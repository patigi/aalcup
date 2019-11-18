-- phpMyAdmin SQL Dump
-- version 2.11.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 14, 2001 at 05:59 AM
-- Server version: 5.0.51
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `pharm`
--

-- --------------------------------------------------------

--
-- Table structure for table `bincard`
--

DROP TABLE IF EXISTS `bincard`;
CREATE TABLE `bincard` (
  `binid` int(7) NOT NULL auto_increment,
  `bindid` int(3) NOT NULL,
  `qtyin` int(5) NOT NULL default '0',
  `qtyout` int(5) NOT NULL default '0',
  `qtybal` int(5) NOT NULL,
  `user` varchar(35) NOT NULL,
  PRIMARY KEY  (`binid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

--
-- Dumping data for table `bincard`
--

INSERT INTO `bincard` (`binid`, `bindid`, `qtyin`, `qtyout`, `qtybal`, `user`) VALUES
(1, 1, 0, 12, 1078, 'saheed'),
(2, 2, 0, 34, 416, 'saheed'),
(3, 1, 0, 45, 1033, 'saheed'),
(4, 1, 0, 0, 1033, 'saheed'),
(5, 1, 0, 0, 1033, 'saheed'),
(6, 1, 0, 0, 1033, 'saheed'),
(7, 1, 0, 0, 1033, 'saheed'),
(8, 1, 0, 0, 1033, 'saheed'),
(9, 1, 0, 0, 1033, 'saheed'),
(10, 1, 0, 0, 1033, 'saheed'),
(11, 1, 0, 0, 1033, 'saheed'),
(12, 1, 0, 0, 1033, 'saheed'),
(13, 1, 0, 0, 1033, 'saheed'),
(14, 1, 0, 0, 1033, 'saheed'),
(15, 1, 0, 0, 1033, 'saheed'),
(16, 1, 0, 0, 1033, 'saheed'),
(17, 1, 0, 0, 1033, 'saheed'),
(18, 1, 0, 0, 1033, 'saheed'),
(19, 1, 0, 0, 1033, 'saheed'),
(20, 1, 0, 0, 1033, 'saheed'),
(21, 1, 0, 0, 1033, 'saheed'),
(22, 1, 0, 0, 1033, 'saheed'),
(23, 1, 0, 0, 1033, 'saheed'),
(24, 1, 0, 0, 1033, 'saheed'),
(25, 1, 0, 0, 1000, 'saheed'),
(26, 1, 0, 0, 988, 'saheed'),
(27, 1, 0, 12, 976, 'saheed'),
(28, 1, 0, 12, 964, 'saheed'),
(29, 2, 0, 33, 931, 'saheed'),
(30, 1, 0, 3, 961, 'saheed'),
(31, 1, 0, 12, 949, 'saheed');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE `customer` (
  `id` int(7) NOT NULL auto_increment,
  `cusId` varchar(7) NOT NULL,
  `cusPid` varchar(7) NOT NULL,
  `cusFname` varchar(25) NOT NULL,
  `cusLname` varchar(25) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `cusId` (`cusId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `cusId`, `cusPid`, `cusFname`, `cusLname`) VALUES
(1, '265503', 'p879059', 'Saheed', 'Jimoh'),
(2, 'p354889', '261841', 'Saheed', 'Jimoh'),
(3, 'p823456', '571473', 'Saheed', 'Jimoh'),
(4, 'p11689', '645447', 'Saheed', 'Jimoh'),
(5, 'p199860', '41382', 'Saheed', 'Jimoh'),
(6, 'p974518', '719361', 'Saheed', 'Jimoh'),
(7, 'p602967', '653595', 'Saheed', 'Musa'),
(8, 'p9675', '270142', 'Saheed', 'Musa'),
(9, 'p740754', '827881', 'Saheed', 'Musa'),
(10, 'p635315', '14557', 'Saheed', 'Musa');

-- --------------------------------------------------------

--
-- Table structure for table `drug_category`
--

DROP TABLE IF EXISTS `drug_category`;
CREATE TABLE `drug_category` (
  `id` int(3) NOT NULL auto_increment,
  `cat_name` varchar(25) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `drug_category`
--

INSERT INTO `drug_category` (`id`, `cat_name`) VALUES
(1, 'Antipyretics'),
(2, 'Analgesics'),
(3, 'Antimalarial'),
(4, 'Antibiotics'),
(5, 'Antiseptics'),
(6, 'Mood stabilizers'),
(7, 'Hormone replacements'),
(8, 'Oral contraceptives'),
(9, 'Stimulants'),
(10, 'Tranquilizers'),
(11, 'Statins');

-- --------------------------------------------------------

--
-- Table structure for table `pharm_users`
--

DROP TABLE IF EXISTS `pharm_users`;
CREATE TABLE `pharm_users` (
  `user_id` int(3) NOT NULL auto_increment,
  `firstname` varchar(25) NOT NULL,
  `lastname` varchar(25) NOT NULL,
  `position` varchar(22) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_password` varchar(15) NOT NULL,
  `access_level` varchar(15) NOT NULL,
  `datead` date NOT NULL,
  PRIMARY KEY  (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `pharm_users`
--

INSERT INTO `pharm_users` (`user_id`, `firstname`, `lastname`, `position`, `user_name`, `user_password`, `access_level`, `datead`) VALUES
(4, 'Saheed', 'Jimoh', 'Director', 'saheed', 'ade', 'Director', '2019-06-10'),
(6, 'Musa', 'Al-ashary', 'Manager', 'alashary', 'ade', 'Manager', '2019-06-11'),
(7, 'Shukurah', 'Bello', 'Dispensary', 'sukura', 'biola', 'Dispensary', '2019-06-18');

-- --------------------------------------------------------

--
-- Table structure for table `purchasetbl`
--

DROP TABLE IF EXISTS `purchasetbl`;
CREATE TABLE `purchasetbl` (
  `id` int(7) NOT NULL auto_increment,
  `purchase_id` int(11) NOT NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `purchase_id` (`purchase_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `purchasetbl`
--


-- --------------------------------------------------------

--
-- Table structure for table `registerdrug`
--

DROP TABLE IF EXISTS `registerdrug`;
CREATE TABLE `registerdrug` (
  `drugid` int(5) NOT NULL auto_increment,
  `drugname` varchar(60) NOT NULL,
  `drugcategory` int(3) NOT NULL,
  `ddescription` text NOT NULL,
  PRIMARY KEY  (`drugid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `registerdrug`
--

INSERT INTO `registerdrug` (`drugid`, `drugname`, `drugcategory`, `ddescription`) VALUES
(1, 'Paracetamol', 2, 'Pain Relief'),
(2, 'Ampiclux', 4, 'Antibacteria'),
(3, 'Coflin', 10, 'Cough Relief'),
(4, 'Artesunate', 3, 'Antimalaria'),
(5, 'Vitamin C', 1, 'Vitamin c'),
(6, 'Ampicilin', 5, 'Antibacteria'),
(7, 'flagyl', 4, 'description'),
(8, 'Detol', 5, 'Detol solution'),
(9, 'Puretin', 4, 'Puretin');

-- --------------------------------------------------------

--
-- Table structure for table `sale`
--

DROP TABLE IF EXISTS `sale`;
CREATE TABLE `sale` (
  `saleId` int(11) NOT NULL auto_increment,
  `salecusId` varchar(7) NOT NULL,
  `scusPid` int(11) NOT NULL,
  `scusDid` int(3) default NULL,
  `scusQty` int(3) default NULL,
  `scusCost` int(6) default NULL,
  `scusDate` date NOT NULL,
  `username` varchar(35) NOT NULL,
  PRIMARY KEY  (`saleId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=117 ;

--
-- Dumping data for table `sale`
--

INSERT INTO `sale` (`saleId`, `salecusId`, `scusPid`, `scusDid`, `scusQty`, `scusCost`, `scusDate`, `username`) VALUES
(1, 'p199860', 41382, 1, 12, 20, '2019-03-28', 'saheed'),
(2, 'p199860', 41382, 1, 3, 20, '2019-03-28', 'saheed'),
(3, 'p199860', 41382, 1, 12, 20, '2019-03-28', 'saheed'),
(4, 'p199860', 41382, 1, 33, 20, '2019-03-28', 'saheed'),
(5, 'p199860', 41382, 1, 33, 20, '2019-03-28', 'saheed'),
(6, 'p199860', 41382, 1, 33, 20, '2019-03-28', 'saheed'),
(7, 'p199860', 41382, 1, 33, 20, '2019-03-28', 'saheed'),
(8, 'p199860', 41382, 1, 33, 20, '2019-03-28', 'saheed'),
(9, 'p199860', 41382, 1, 12, 20, '2019-03-28', 'saheed'),
(10, 'p199860', 41382, 1, 12, 20, '2019-03-28', 'saheed'),
(11, 'p974518', 719361, 1, 12, 20, '2019-03-28', 'saheed'),
(12, 'p974518', 719361, 2, 33, 300, '2019-03-28', 'saheed'),
(13, 'p974518', 719361, 1, 3, 30, '2019-03-28', 'saheed'),
(14, 'p974518', 719361, 1, 12, 30, '2019-03-29', 'saheed'),
(15, 'p974518', 719361, 1, 12, 60, '2019-04-24', 'saheed'),
(16, 'p974518', 719361, 1, 8, 60, '2019-04-25', 'saheed'),
(17, 'p974518', 719361, 3, 1, 65, '2019-04-25', 'saheed'),
(18, 'p974518', 719361, 1, 12, 60, '2019-04-25', 'saheed'),
(19, 'p974518', 719361, 1, 5, 60, '2019-04-25', 'saheed'),
(20, 'p974518', 719361, 1, 1, 60, '2019-04-25', 'saheed'),
(21, 'p974518', 719361, 1, 1, 60, '2019-04-25', 'saheed'),
(22, 'p974518', 719361, 1, 12, 60, '2019-05-08', 'saheed'),
(23, 'p974518', 719361, 1, 12, 60, '2019-05-08', 'saheed'),
(24, 'p974518', 719361, 1, 12, 60, '2019-05-08', 'saheed'),
(25, 'p974518', 719361, 1, 12, 60, '2019-05-08', 'saheed'),
(26, 'p974518', 719361, 1, 12, 60, '2019-05-08', 'saheed'),
(27, 'p974518', 719361, 1, 12, 60, '2019-05-08', 'saheed'),
(28, 'p974518', 719361, 1, 12, 60, '2019-05-08', 'saheed'),
(116, 'p602967', 653595, 3, 1, 65, '2019-06-07', 'saheed');

-- --------------------------------------------------------

--
-- Table structure for table `scheduleorder`
--

DROP TABLE IF EXISTS `scheduleorder`;
CREATE TABLE `scheduleorder` (
  `id` int(6) NOT NULL auto_increment,
  `order_id` varchar(15) NOT NULL,
  `Schdrugid` varchar(30) NOT NULL,
  `dSupplier` varchar(25) NOT NULL,
  `dUnit` varchar(33) NOT NULL,
  `dQty` int(5) NOT NULL,
  `dCost` varchar(6) NOT NULL,
  `in_store` varchar(5) NOT NULL default 'NO',
  `expiry` date default NULL,
  `datead` date NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `scheduleorder`
--

INSERT INTO `scheduleorder` (`id`, `order_id`, `Schdrugid`, `dSupplier`, `dUnit`, `dQty`, `dCost`, `in_store`, `expiry`, `datead`) VALUES
(3, '00003', '4', '1', '', 1000, '10000', 'YES', '2024-06-13', '2019-04-08'),
(4, '00004', '3', '2', 'Pack', 79, '7900', 'NO', '2024-12-31', '2019-04-08'),
(5, '00005', '3', '2', 'Bottle', 55, '50', 'YES', '2023-04-21', '2019-04-10'),
(6, '00006', '1', '1', 'Pcs', 1000, '50', 'YES', '2024-04-30', '2019-04-12'),
(7, '00007', '5', '2', 'Bottle', 55, '5500', 'YES', '2024-04-28', '2019-04-12');

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

DROP TABLE IF EXISTS `stock`;
CREATE TABLE `stock` (
  `stockid` int(7) NOT NULL auto_increment,
  `stockdid` int(5) NOT NULL,
  `stk_transaction_id` int(9) default NULL,
  `stockUnit` varchar(33) NOT NULL,
  `stockcost` varchar(6) NOT NULL default '0',
  `stockprice` varchar(6) NOT NULL default '0',
  `stock_qty_in` int(6) default '0',
  `stock_qty_out` int(6) default '0',
  `added_by` varchar(33) NOT NULL,
  `date_ad` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`stockid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=69 ;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`stockid`, `stockdid`, `stk_transaction_id`, `stockUnit`, `stockcost`, `stockprice`, `stock_qty_in`, `stock_qty_out`, `added_by`, `date_ad`) VALUES
(1, 1, 6, 'Pcs', '50', '60', 100, 0, 'saheed', '2019-04-24 14:36:06'),
(2, 1, 719361, 'Pcs', '50', '60', 0, 12, 'saheed', '2019-04-24 15:18:10'),
(3, 1, 719361, 'Pcs', '50', '60', 0, 8, 'saheed', '2019-04-25 12:07:40'),
(4, 3, 5, 'Bottle', '50', '65', 5, 0, 'saheed', '2019-04-25 12:08:30'),
(5, 3, 719361, 'Bottle', '50', '65', 0, 1, 'saheed', '2019-04-25 12:09:02'),
(6, 1, 719361, 'Pcs', '50', '60', 0, 12, 'saheed', '2019-04-25 13:43:04'),
(7, 1, 719361, 'Pcs', '50', '60', 0, 5, 'saheed', '2019-04-25 15:15:55'),
(8, 1, 719361, 'Pcs', '50', '60', 0, 1, 'saheed', '2019-04-25 15:32:46'),
(9, 1, 719361, 'Pcs', '50', '60', 0, 1, 'saheed', '2019-04-25 15:33:35'),
(10, 1, 719361, 'Pcs', '50', '60', 0, 12, 'saheed', '2019-05-08 10:35:32'),
(11, 1, 719361, 'Pcs', '50', '60', 0, 12, 'saheed', '2019-05-08 10:51:28'),
(12, 1, 719361, 'Pcs', '50', '60', 0, 12, 'saheed', '2019-05-08 10:56:07'),
(13, 1, 719361, 'Pcs', '50', '60', 0, 12, 'saheed', '2019-05-08 10:56:25'),
(14, 1, 719361, 'Pcs', '50', '60', 0, 12, 'saheed', '2019-05-08 10:58:04'),
(15, 1, 719361, 'Pcs', '50', '60', 0, 12, 'saheed', '2019-05-08 11:05:52'),
(16, 1, 719361, 'Pcs', '50', '60', 0, 12, 'saheed', '2019-05-08 12:50:37'),
(66, 3, 653595, 'Bottle', '50', '65', 0, 1, 'saheed', '2019-06-07 12:47:20'),
(67, 4, 3, '', '10000', '15', 100, 0, 'saheed', '2019-06-07 12:49:36'),
(68, 1, 6, 'Pcs', '50', '60', 30, 0, 'saheed', '2019-06-07 12:50:32');

-- --------------------------------------------------------

--
-- Table structure for table `store`
--

DROP TABLE IF EXISTS `store`;
CREATE TABLE `store` (
  `storeId` int(11) NOT NULL auto_increment,
  `store_order_id` varchar(11) NOT NULL,
  `storeDid` int(3) NOT NULL,
  `storedrgQty` int(5) default '0',
  `storedrgqtyout` int(5) default '0',
  `storedrgCost` int(6) NOT NULL,
  `storeDate` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `storedrgexpiry` date NOT NULL,
  `username` varchar(35) NOT NULL,
  `store_status` varchar(15) NOT NULL default 'remain',
  PRIMARY KEY  (`storeId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `store`
--

INSERT INTO `store` (`storeId`, `store_order_id`, `storeDid`, `storedrgQty`, `storedrgqtyout`, `storedrgCost`, `storeDate`, `storedrgexpiry`, `username`, `store_status`) VALUES
(1, '00007', 5, 55, 0, 5500, '2019-04-18 11:42:13', '2024-04-28', 'saheed', 'remain'),
(4, '00006', 1, 1000, 0, 50, '2019-04-18 11:51:15', '2024-04-30', 'saheed', 'remain'),
(13, '00005', 3, 55, 0, 50, '2019-04-23 11:31:31', '2023-04-21', 'saheed', 'remain'),
(19, '00006', 1, 0, 100, 50, '2019-04-24 14:36:05', '2024-04-30', 'saheed', 'remain'),
(20, '00005', 3, 0, 5, 50, '2019-04-25 12:08:30', '2023-04-21', 'saheed', 'remain'),
(21, '00003', 4, 1000, 0, 10000, '2019-06-07 12:48:17', '2024-06-13', 'saheed', 'remain'),
(22, '00003', 4, 0, 100, 10000, '2019-06-07 12:49:36', '2024-06-13', 'saheed', 'remain'),
(23, '00006', 1, 0, 30, 50, '2019-06-07 12:50:32', '2024-04-30', 'saheed', 'remain');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

DROP TABLE IF EXISTS `supplier`;
CREATE TABLE `supplier` (
  `supId` int(5) NOT NULL auto_increment,
  `supName` varchar(50) NOT NULL,
  `supContName` varchar(25) NOT NULL,
  `supContPhone` varchar(11) NOT NULL,
  `supAddress` text NOT NULL,
  PRIMARY KEY  (`supId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`supId`, `supName`, `supContName`, `supContPhone`, `supAddress`) VALUES
(1, 'Evans', 'Saheed Jimoh', '08063841614', 'Kaduna'),
(2, 'Emzor', 'Shukrat Bello', '07037093763', 'Offa'),
(3, 'Nass', 'Nasir', '0809999009', 'Kaduna'),
(4, 'zagbayi', 'Musa Abubakar', '08090745889', 'Kano');
