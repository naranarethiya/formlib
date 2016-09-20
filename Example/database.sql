-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 18, 2016 at 04:51 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `svs_crew_managment`
--

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `client_id` int(11) NOT NULL,
  `client_name` varchar(20) DEFAULT NULL,
  `client_address` varchar(30) DEFAULT NULL,
  `client_city` varchar(20) DEFAULT NULL,
  `client_country` varchar(10) DEFAULT NULL,
  `client_zip` int(10) DEFAULT NULL,
  `client_note` varchar(220) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`client_id`, `client_name`, `client_address`, `client_city`, `client_country`, `client_zip`, `client_note`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(4, 'Gordinateur', 'Belapur', NULL, 'India', 400614, '', NULL, NULL, NULL, NULL),
(5, 'Gordinateur Pvt Ltd', 'Belapur', NULL, 'India', 400614, '', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `client_people`
--

CREATE TABLE `client_people` (
  `client_people` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `people_name` varchar(220) NOT NULL,
  `people_designation` varchar(220) NOT NULL,
  `people_email` varchar(220) NOT NULL,
  `people_mobile` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `client_people`
--

INSERT INTO `client_people` (`client_people`, `client_id`, `people_name`, `people_designation`, `people_email`, `people_mobile`) VALUES
(20, 2, 'dsfs', 'dfsdfsd', 'naranarethiy@gmail.com', '08879331463'),
(23, 2, 'bharat', 'dskbfsd', 'kjbfjkd@sss.com', 'kjbnk'),
(24, 2, 'gunesh', 'manager', 'gunesh@gg.com', 'number'),
(25, 2, 'gunesh', 'manager', 'gunesh@gg.com', 'number'),
(26, 4, 'ddd', 'ddddddddd', 'naranarethiya@gmail.com', '08879331463'),
(27, 5, 'ff', 'ffff', 'naran@ggg.comn', '08879331463');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `client_people`
--
ALTER TABLE `client_people`
  ADD PRIMARY KEY (`client_people`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `client_people`
--
ALTER TABLE `client_people`
  MODIFY `client_people` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;