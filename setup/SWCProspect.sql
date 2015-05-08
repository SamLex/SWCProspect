-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 08, 2015 at 03:18 PM
-- Server version: 5.5.43-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `SWCProspect`
--

-- --------------------------------------------------------

--
-- Table structure for table `Planet`
--

CREATE TABLE IF NOT EXISTS `Planet` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(254) COLLATE utf8_unicode_ci NOT NULL,
  `Size` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `Result`
--

CREATE TABLE IF NOT EXISTS `Result` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `PlanetID` int(11) NOT NULL,
  `Material` varchar(254) COLLATE utf8_unicode_ci NOT NULL,
  `Size` int(11) NOT NULL DEFAULT '0',
  `LocationX` int(11) NOT NULL DEFAULT '0',
  `LocationY` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `PlanetID` (`PlanetID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Constraints for table `Result`
--
ALTER TABLE `Result`
  ADD CONSTRAINT `Result_ibfk_1` FOREIGN KEY (`PlanetID`) REFERENCES `Planet` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
