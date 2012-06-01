-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 01, 2012 at 10:35 AM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `joomla_15_22`
--

--
-- Dumping data for table `jos_simple_configs`
--

INSERT INTO `jos_simple_configs` (`config_id`, `config_name`, `config_short`, `config_text`, `config_type`, `which_config`, `config_comments`, `createdby`, `created`, `ordering`, `publish`, `params`) VALUES
(NULL, 'Club Player Level', 'club_player_level', '', '', 'TOPMOST', '', 62, '2012-05-31 09:15:41', 22, 1, 'sort_list_by=ordering\nconfig_type=list'),
(NULL, 'Amatuer', 'amatuer', '', '', 'club_player_level', '', 62, '2012-05-31 09:16:52', 1, 1, 'config_type=none\ncontrol_type=text\ncontrol_width=300px\nis_email=no'),
(NULL, 'Fee Paying', 'fee_paying', '', '', 'club_player_level', '', 62, '2012-05-31 09:17:29', 2, 1, 'config_type=none\ncontrol_type=text\ncontrol_width=300px\nis_email=no'),
(NULL, 'Club Registration Documents', 'club_documents', '', '', 'TOPMOST', '', 62, '2012-06-01 10:11:32', 23, 1, 'sort_list_by=ordering\nconfig_type=list'),
(NULL, 'Profile Pics', 'profile_pics', '', '', 'club_documents', '', 62, '2012-06-01 10:12:34', 1, 1, 'config_type=none\ncontrol_type=text\ncontrol_width=300px\nis_email=no'),
(NULL, 'Hold Hamless', 'hold_hamless', '', '', 'club_documents', '', 62, '2012-06-01 10:14:14', 2, 1, 'config_type=none\ncontrol_type=text\ncontrol_width=300px\nis_email=no'),
(NULL, 'Physical/Sports Clearance', 'physical_sports_clearance', '', '', 'club_documents', '', 62, '2012-06-01 10:14:53', 3, 1, 'config_type=none\ncontrol_type=text\ncontrol_width=300px\nis_email=no'),
(NULL, 'T-Shirt Sizes', 'tshirt_sizes', 'size 6\r\nsize 7\r\nsmall\r\nlarge\r\nextra large\r\n', '', 'club_player_details', '', 62, '2012-06-01 10:22:48', 1, 1, 'config_type=none\ncontrol_type=select\ncontrol_width=300px\nis_email=no'),
(NULL, 'Swimmer''s School', 'swimmers_school', '', '', 'club_player_details', '', 62, '2012-06-01 10:24:45', 2, 1, 'config_type=none\ncontrol_type=text\ncontrol_width=300px\nis_email=no'),
(NULL, 'Directory Listing Text', 'directory_listing_text', '', '', 'club_player_details', '', 62, '2012-06-01 10:27:00', 3, 1, 'config_type=none\ncontrol_type=textarea\ncontrol_width=300px\nis_email=no'),
(NULL, 'Archived', 'archived', 'no\r\nyes', '', 'club_player_details', '', 62, '2012-06-01 10:28:14', 4, 1, 'config_type=none\ncontrol_type=select\ncontrol_width=intext half\nis_email=no'),
(NULL, 'Membership Enabled', 'membership_enabled', 'no\r\nyes', '', 'club_player_details', '', 62, '2012-06-01 10:30:13', 5, 1, 'config_type=none\ncontrol_type=select\ncontrol_width=intext\nis_email=no'),
(NULL, 'Member Since', 'member_since', '', '', 'club_player_details', '', 62, '2012-06-01 10:31:12', 6, 1, 'config_type=none\ncontrol_type=monthyear\ncontrol_width=300px\nis_email=no');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
