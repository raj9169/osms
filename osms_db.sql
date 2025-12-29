-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 07, 2025 at 04:16 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `osms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `adminlogin`
--

CREATE TABLE `adminlogin` (
  `a_login_id` int(11) NOT NULL,
  `a_name` varchar(60) NOT NULL,
  `a_email` varchar(60) NOT NULL,
  `a_password` int(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `adminlogin`
--

INSERT INTO `adminlogin` (`a_login_id`, `a_name`, `a_email`, `a_password`) VALUES
(1, 'Admin', 'admin@osms.com', 1234);

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `pid` int(11) NOT NULL,
  `pname` varchar(60) NOT NULL,
  `pdop` date NOT NULL,
  `pava` int(11) NOT NULL,
  `ptotal` int(11) NOT NULL,
  `poriginalcost` int(11) NOT NULL,
  `psellingcost` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`pid`, `pname`, `pdop`, `pava`, `ptotal`, `poriginalcost`, `psellingcost`) VALUES
(2, 'keyboard', '2021-07-27', 1, 13, 500, 600);

-- --------------------------------------------------------

--
-- Table structure for table `assignwork`
--

CREATE TABLE `assignwork` (
  `rno` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `request_info` text NOT NULL,
  `request_desc` text NOT NULL,
  `request_name` varchar(60) NOT NULL,
  `request_add1` text NOT NULL,
  `request_add2` text NOT NULL,
  `request_city` varchar(60) NOT NULL,
  `request_state` varchar(60) NOT NULL,
  `request_zip` int(11) NOT NULL,
  `request_email` varchar(60) NOT NULL,
  `request_mobile` bigint(20) NOT NULL,
  `request_tech` varchar(60) NOT NULL,
  `request_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `assignwork`
--

INSERT INTO `assignwork` (`rno`, `request_id`, `request_info`, `request_desc`, `request_name`, `request_add1`, `request_add2`, `request_city`, `request_state`, `request_zip`, `request_email`, `request_mobile`, `request_tech`, `request_date`) VALUES
(3, 2, 'washing machine not working', 'fan not move', 'Shankar Kumar', 'Gosain tola near mangal deep apartment', 'Patliputra colony', 'Patna', 'Bihar', 800013, 'shankarky99@gmail.com', 917050775803, 'vikas', '2021-08-24'),
(4, 26, 'TV', 'Tv is not working', 'Vikas', '266', 'sec 9', 'Ggg', 'HR', 122001, 'vikas@gmail.com', 88009668008, 'Pankaj', '2025-07-19'),
(6, 19, 'washing machine not working', 'Ac not cooling', 'Shankar Kumar', '', 'Patliputra colony', 'Patna', '', 0, '', 7050775803, 'Akash Kumar', '2025-09-04'),
(7, 20, 'washing machine not working', 'Ac not cooling', 'Shankar Kumar', '', 'Patliputra colony', 'Patna', '', 0, '', 7050775803, 'Vikas', '2025-09-04'),
(8, 21, 'tv not work', 'display are invisible', 'Shankar Kumar', 'Gosain tola near mangal deep apartment', 'Patliputra colony', 'Patna', 'Bihar', 800013, 'shankarky99@gmail.com', 7050775803, 'Akash Kumar', '2025-09-04'),
(9, 22, 'washing machine not working', 'fan not move', 'Shankar Kumar', 'Gosain tola near mangal deep apartment', 'Patliputra colony', 'Patna', 'Bihar', 800013, 'shankarky99@gmail.com', 917050775803, 'Akash Kumar', '2025-09-05'),
(10, 23, 'tv not work', 'display are invisible', 'Shankar Kumar', 'Gosain tola near mangal deep apartment', 'Patliputra colony', 'Patna', 'Bihar', 800013, 'shankarky99@gmail.com', 917050775803, 'Vikas', '2025-09-05'),
(11, 25, 'Fan not move', 'fan not move', 'Shankar Kumar', 'Gosain tola near mangal deep apartment', 'Patliputra colony', 'Patna', 'Bihar', 800013, 'shankarky99@gmail.com', 7050775803, 'Akash Kumar', '2025-09-05'),
(12, 26, 'TV', 'Tv is not working', 'Vikas', '266', 'sec 9', 'Ggg', 'HR', 122001, 'vikas@gmail.com', 88009668008, 'Vikas', '2025-09-03'),
(13, 27, 'Lap mother issue', 'Laptop mother not working properly', 'Raj', 'Udyog vihar', 'sector 18', 'Ggg', 'HR', 1220001, 'rajku@gmail.com', 7583698252, 'Akash Kumar', '2025-09-05'),
(14, 28, 'Laptop not working', 'laptop mother board not working', 'Shankar', '266', 'esic', 'Ggg', 'hr', 122001, 'shankarky99@gmail.com', 24586585666, 'Akash Kumar', '2025-09-05'),
(15, 29, 'Laptop not working', 'laptop mother board not working', 'Shankar', '266', 'esic', 'Ggg', 'hr', 122001, 'shankarky99@gmail.com', 24586585666, 'Vikas', '2025-09-05');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `custid` int(11) NOT NULL,
  `custname` varchar(60) NOT NULL,
  `custadd` varchar(60) NOT NULL,
  `cpname` varchar(60) NOT NULL,
  `cpquantity` int(11) NOT NULL,
  `cpeach` int(11) NOT NULL,
  `cptotal` int(11) NOT NULL,
  `cpdate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`custid`, `custname`, `custadd`, `cpname`, `cpquantity`, `cpeach`, `cptotal`, `cpdate`) VALUES
(6, 'Shankar', '266, Ggg', 'keyboard', 1, 600, 600, '2025-09-04'),
(7, 'Shankar', '266, Ggg', 'keyboard', 2, 600, 1200, '2025-09-04'),
(8, 'Shankar', '266, Ggg', 'keyboard', 6, 600, 3600, '2025-09-04'),
(9, 'Shankar', '266, Ggg', 'keyboard', 2, 600, 1200, '2025-09-04');

-- --------------------------------------------------------

--
-- Table structure for table `submitrequest`
--

CREATE TABLE `submitrequest` (
  `request_id` int(11) NOT NULL,
  `request_info` text NOT NULL,
  `request_desc` text NOT NULL,
  `request_name` varchar(50) NOT NULL,
  `request_add1` text NOT NULL,
  `request_add2` text NOT NULL,
  `request_city` varchar(50) NOT NULL,
  `request_state` varchar(50) NOT NULL,
  `request_zip` int(11) NOT NULL,
  `request_email` varchar(50) NOT NULL,
  `request_mobile` bigint(20) NOT NULL,
  `request_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `submitrequest`
--

INSERT INTO `submitrequest` (`request_id`, `request_info`, `request_desc`, `request_name`, `request_add1`, `request_add2`, `request_city`, `request_state`, `request_zip`, `request_email`, `request_mobile`, `request_date`) VALUES
(37, 'Chatbot Request', 'status', 'status', '', '', '', '', 0, 'status', 0, '0000-00-00'),
(38, 'Remote', 'Remote button not functioning', 'Shankar Kumar Yadav', 'H. No. 42, Gali No. 5, Ambedkar Nagar', 'sector - 9', 'Gurgaon', 'Haryana', 122001, 'shankarky99@gmail.com', 7050775803, '2025-09-13'),
(39, 'Mobile', 'Mobile mother board died', 'SK', 'H. No. 42, Gali No. 5, Ambedkar Nagar', 'sector', 'Gurgaon', 'Haryana', 847421, 'shankar@gmail.com', 1234567890, '2025-09-13'),
(40, 'Mouse', 'Mouse not work', 'Dev', 'Ggg', 'sector 7', 'Ggg', 'Hr', 122001, 'dev@gmail.com', 8800966808, '2025-09-13'),
(41, 'Chatbot Request', 'laptop screen issue', 'shankar', '', '', '', '', 0, 'shankar@gmail.com', 0, '0000-00-00'),
(42, 'Laptop mother board Issue', 'Laptop mother boarding is not working.', 'Arshad', 'Ggg', 'gg', 'Gurgaon', 'HR', 122001, 'arshad@gmail.com', 8523658958, '2025-10-01');

-- --------------------------------------------------------

--
-- Table structure for table `technician`
--

CREATE TABLE `technician` (
  `empid` int(11) NOT NULL,
  `empName` varchar(60) NOT NULL,
  `empCity` varchar(60) NOT NULL,
  `empMobile` bigint(20) NOT NULL,
  `empEmail` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `technician`
--

INSERT INTO `technician` (`empid`, `empName`, `empCity`, `empMobile`, `empEmail`) VALUES
(1, 'Akash Kumar', 'Patna', 8826598522, 'akash@gmail.com'),
(2, 'Vikas', 'Gurgaon', 8800966808, 'vikas@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `userlogin`
--

CREATE TABLE `userlogin` (
  `r_login_id` int(10) NOT NULL,
  `r_name` varchar(40) NOT NULL,
  `r_email` varchar(40) NOT NULL,
  `r_password` varchar(40) NOT NULL,
  `r_mobile` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `userlogin`
--

INSERT INTO `userlogin` (`r_login_id`, `r_name`, `r_email`, `r_password`, `r_mobile`) VALUES
(1, 'Shankar Kumar Yadav', 'shankarky99@gmail.com', '1234', 7050775803),
(43, 'Vikas Kumar', 'vikas@gmail.com', '7138919Aa@', 8800966808),
(44, 'Raj Kushwaha', 'raj@gmail.com', 'Raj@1234', 9006583586),
(45, 'Vinit', 'vinit@gmail.com', '1234', 0),
(46, 'Rohit', 'rohit@gmail.com', '1234', 2578485965),
(47, 'ravi', 'ravi@gmail.com', '01234', 1234567890);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adminlogin`
--
ALTER TABLE `adminlogin`
  ADD PRIMARY KEY (`a_login_id`);

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `assignwork`
--
ALTER TABLE `assignwork`
  ADD PRIMARY KEY (`rno`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`custid`);

--
-- Indexes for table `submitrequest`
--
ALTER TABLE `submitrequest`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `technician`
--
ALTER TABLE `technician`
  ADD PRIMARY KEY (`empid`);

--
-- Indexes for table `userlogin`
--
ALTER TABLE `userlogin`
  ADD PRIMARY KEY (`r_login_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adminlogin`
--
ALTER TABLE `adminlogin`
  MODIFY `a_login_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `assignwork`
--
ALTER TABLE `assignwork`
  MODIFY `rno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `custid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `submitrequest`
--
ALTER TABLE `submitrequest`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `technician`
--
ALTER TABLE `technician`
  MODIFY `empid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `userlogin`
--
ALTER TABLE `userlogin`
  MODIFY `r_login_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
