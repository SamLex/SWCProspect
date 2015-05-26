-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 26, 2015 at 11:18 AM
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
-- Table structure for table `Deposit`
--

CREATE TABLE IF NOT EXISTS `Deposit` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Size` int(11) NOT NULL,
  `LocationX` int(11) NOT NULL,
  `LocationY` int(11) NOT NULL,
  `PlanetID` int(11) NOT NULL,
  `DepositTypeID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `PlanetID` (`PlanetID`,`DepositTypeID`),
  KEY `DepositTypeID` (`DepositTypeID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=30 ;

-- --------------------------------------------------------

--
-- Table structure for table `DepositType`
--

CREATE TABLE IF NOT EXISTS `DepositType` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Material` varchar(254) COLLATE utf8_unicode_ci NOT NULL,
  `HTMLColour` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#000000',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=22 ;

--
-- Dumping data for table `DepositType`
--

INSERT INTO `DepositType` (`ID`, `Material`, `HTMLColour`) VALUES
(1, 'Quantum', '#996600'),
(2, 'Meleenium', '#404040'),
(3, 'Ardanium', '#339900'),
(4, 'Rudic', '#000066'),
(5, 'Ryll', '#0066CC'),
(6, 'Duracrete', '#383838'),
(7, 'Alazhi', '#FF3300'),
(8, 'Laboi', '#FFCCFF'),
(9, 'Adegan', '#CCFFFF'),
(10, 'Rockivory', '#996633'),
(11, 'Tibannagas', '#FFCC33'),
(12, 'Nova', '#00FF00'),
(13, 'Varium', '#0000CC'),
(14, 'Varmigio', '#FFFF00'),
(15, 'Lommite', '#888888'),
(16, 'Hibridium', '#A8A8A8'),
(17, 'Durelium', '#00FFFF'),
(18, 'Lowickan', '#CC0000'),
(19, 'Vertex', '#333399'),
(20, 'Berubian', '#00FFCC'),
(21, 'Bacta', '#CC3333');

-- --------------------------------------------------------

--
-- Table structure for table `Planet`
--

CREATE TABLE IF NOT EXISTS `Planet` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(254) COLLATE utf8_unicode_ci NOT NULL,
  `Size` int(11) NOT NULL,
  `PlanetTypeID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `PlanetTypeID` (`PlanetTypeID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=34 ;

-- --------------------------------------------------------

--
-- Table structure for table `PlanetType`
--

CREATE TABLE IF NOT EXISTS `PlanetType` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Description` varchar(254) COLLATE utf8_unicode_ci NOT NULL,
  `HTMLColour` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#000000',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

--
-- Dumping data for table `PlanetType`
--

INSERT INTO `PlanetType` (`ID`, `Description`, `HTMLColour`) VALUES
(1, 'Hot/No Atmosphere', '#993300'),
(2, 'Hot/Toxic Atmosphere', '#996633'),
(3, 'Hot/Breathable', '#999966'),
(4, 'Temperate/Breathable', '#3333FF'),
(5, 'Cold/Breathable', '#330099'),
(6, 'Cold/Toxic Atmosphere', '#00CCCC'),
(7, 'Cold/No Atmosphere', '#000099'),
(8, 'Gas Giant', '#FF9900'),
(9, 'Moon', '#A0A0A0'),
(10, 'Asteroid Field', '#606060'),
(11, 'Comet', '#66FFFF'),
(12, 'Temperate/Toxic Atmosphere', '#339999');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Deposit`
--
ALTER TABLE `Deposit`
  ADD CONSTRAINT `Deposit_ibfk_1` FOREIGN KEY (`PlanetID`) REFERENCES `Planet` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Deposit_ibfk_2` FOREIGN KEY (`DepositTypeID`) REFERENCES `DepositType` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `Planet`
--
ALTER TABLE `Planet`
  ADD CONSTRAINT `Planet_ibfk_1` FOREIGN KEY (`PlanetTypeID`) REFERENCES `PlanetType` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
