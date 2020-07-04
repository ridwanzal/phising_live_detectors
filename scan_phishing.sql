-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 18, 2020 at 05:51 PM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.2.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scan_phishing`
--

-- --------------------------------------------------------

--
-- Table structure for table `ph_features`
--

CREATE TABLE `ph_features` (
  `feature_id` float NOT NULL,
  `scan_id` int(11) NOT NULL,
  `sch_id` float NOT NULL,
  `url_link` float NOT NULL,
  `url_protocol` float NOT NULL,
  `url_favicon` float NOT NULL,
  `url_standard_port` float NOT NULL,
  `url_symbol` float NOT NULL,
  `url_subdomain` float NOT NULL,
  `url_length` float NOT NULL,
  `url_dot_total` float NOT NULL,
  `url_sensitive_char` float NOT NULL,
  `url_brandinfo` float NOT NULL,
  `html_alert` float NOT NULL,
  `html_login` float NOT NULL,
  `html_empty_link` float NOT NULL,
  `html_length` float NOT NULL,
  `html_is_consist` float NOT NULL,
  `html_js_list` float NOT NULL,
  `html_string_embed` float NOT NULL,
  `html_link_external_list` float NOT NULL,
  `html_redirect` float NOT NULL,
  `html_iframe` float NOT NULL,
  `html_mouseover` float NOT NULL,
  `html_popup` float NOT NULL,
  `html_favicon` float NOT NULL,
  `feature_type` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ph_scan`
--

CREATE TABLE `ph_scan` (
  `scan_id` int(11) NOT NULL,
  `dataset_url` text NOT NULL,
  `dataset_html_file` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp(),
  `date_deleted` datetime NOT NULL DEFAULT current_timestamp(),
  `scan_type` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ph_scan`
--

INSERT INTO `ph_scan` (`scan_id`, `dataset_url`, `dataset_html_file`, `date_created`, `date_updated`, `date_deleted`, `scan_type`) VALUES
(107309, 'http://rsarrasyid.id', 'rsarrasyid.id.html', '2020-05-18 22:42:52', '2020-05-18 22:42:52', '2020-05-18 22:42:52', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ph_smart_features`
--

CREATE TABLE `ph_smart_features` (
  `id` int(11) NOT NULL,
  `scan_id` int(11) NOT NULL,
  `features_a` int(11) NOT NULL,
  `features_b` int(11) NOT NULL,
  `features_c` int(11) NOT NULL,
  `features_d` int(11) NOT NULL,
  `features_e` int(11) NOT NULL,
  `features_f` int(11) NOT NULL,
  `features_g` int(11) NOT NULL,
  `features_h` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ph_smart_features`
--

INSERT INTO `ph_smart_features` (`id`, `scan_id`, `features_a`, `features_b`, `features_c`, `features_d`, `features_e`, `features_f`, `features_g`, `features_h`) VALUES
(8, 107309, 0, 0, 0, 0, 0, 0, 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ph_features`
--
ALTER TABLE `ph_features`
  ADD PRIMARY KEY (`feature_id`),
  ADD KEY `ph_feature_scan10` (`scan_id`);

--
-- Indexes for table `ph_scan`
--
ALTER TABLE `ph_scan`
  ADD PRIMARY KEY (`scan_id`) USING BTREE;

--
-- Indexes for table `ph_smart_features`
--
ALTER TABLE `ph_smart_features`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ph_smarfeature_scan1` (`scan_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ph_features`
--
ALTER TABLE `ph_features`
  MODIFY `feature_id` float NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10593;

--
-- AUTO_INCREMENT for table `ph_scan`
--
ALTER TABLE `ph_scan`
  MODIFY `scan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107310;

--
-- AUTO_INCREMENT for table `ph_smart_features`
--
ALTER TABLE `ph_smart_features`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ph_smart_features`
--
ALTER TABLE `ph_smart_features`
  ADD CONSTRAINT `ph_smarfeature_scan1` FOREIGN KEY (`scan_id`) REFERENCES `ph_scan` (`scan_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
