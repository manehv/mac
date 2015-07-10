-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 26, 2015 at 09:02 PM
-- Server version: 5.5.43-0ubuntu0.14.04.1-log
-- PHP Version: 5.5.9-1ubuntu4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `maccen_c0nt3nt`
--

-- --------------------------------------------------------

--
-- Table structure for table `wp_cities`
--

CREATE TABLE IF NOT EXISTS `wp_cities` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `zip` int(20) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state_id` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1103 ;

-- --------------------------------------------------------

--
-- Table structure for table `wp_states`
--

CREATE TABLE IF NOT EXISTS `wp_states` (
  `state_code` int(20) NOT NULL,
  `state_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
