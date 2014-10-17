<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Installer's SQL View
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.0
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */
?>
--
-- Table structure for table `ci_session`
--

CREATE TABLE IF NOT EXISTS `<?php echo config_item('sess_table_name'); ?>` (
  `session_id` varchar(40) DEFAULT '0' NOT NULL,
  `ip_address` varchar(45) DEFAULT '0' NOT NULL,
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned DEFAULT 0 NOT NULL,
  `user_data` text NOT NULL,
  PRIMARY KEY (session_id),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ips_on_hold`
--

CREATE TABLE IF NOT EXISTS `<?php echo config_item('IP_hold_table'); ?>` (
  `IP_address` varchar(45) NOT NULL,
  `time` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `login_errors`
--

CREATE TABLE IF NOT EXISTS `<?php echo config_item('errors_table'); ?>` (
  `username_or_email` varchar(255) NOT NULL,
  `IP_address` varchar(45) NOT NULL,
  `time` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `denied_access`
--

CREATE TABLE IF NOT EXISTS `<?php echo config_item('denied_access_table'); ?>` (
  `IP_address` varchar(45) NOT NULL,
  `time` int(10) NOT NULL,
  `reason_code` tinyint(2) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE IF NOT EXISTS `<?php echo config_item('registration_table'); ?>` (
  `reg_mode` int(1) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `registration` (`reg_mode`) VALUES
(0);

-- --------------------------------------------------------

--
-- Table structure for table `temp_registration_data`
--

CREATE TABLE IF NOT EXISTS `<?php echo config_item('temp_reg_data_table'); ?>` (
  `reg_id` int(10) unsigned NOT NULL,
  `reg_time` int(10) NOT NULL,
  `user_name` varchar(12) NOT NULL,
  `user_pass` mediumtext NOT NULL,
  `user_salt` varchar(32) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `street_address` varchar(60) NOT NULL,
  `city` varchar(60) NOT NULL,
  `state` varchar(50) NOT NULL,
  `zip` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `username_or_email_on_hold`
--

CREATE TABLE IF NOT EXISTS `<?php echo config_item('username_or_email_hold_table'); ?>` (
  `username_or_email` varchar(255) NOT NULL,
  `time` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `<?php echo config_item('user_table'); ?>` (
  `user_id` int(10) unsigned NOT NULL,
  `user_name` varchar(12) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_pass` varchar(60) NOT NULL,
  `user_salt` varchar(32) NOT NULL,
  `user_last_login` int(10) DEFAULT NULL,
  `user_login_time` int(10) DEFAULT NULL,
  `user_session_id` varchar(40) DEFAULT NULL,
  `user_date` int(10) NOT NULL,
  `user_modified` int(10) NOT NULL,
  `user_agent_string` varchar(32) DEFAULT NULL,
  `user_level` tinyint(2) unsigned NOT NULL,
  `user_banned` enum('0','1') NOT NULL DEFAULT '0',
  `passwd_recovery_code` varchar(60) DEFAULT NULL,
  `passwd_recovery_date` int(10) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customer_profiles`
--

CREATE TABLE IF NOT EXISTS `<?php echo config_item('customer_profiles_table'); ?>` (
  `user_id` int(10) unsigned NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `street_address` varchar(60) NOT NULL,
  `city` varchar(60) NOT NULL,
  `state` varchar(50) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `profile_image` text,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `manager_profiles`
--

CREATE TABLE IF NOT EXISTS `<?php echo config_item('manager_profiles_table'); ?>` (
  `user_id` int(10) unsigned NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `license_number` varchar(30) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `profile_image` text,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `admin_profiles`
--

CREATE TABLE IF NOT EXISTS `<?php echo config_item('admin_profiles_table'); ?>` (
  `user_id` int(10) unsigned NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `profile_image` text,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `custom_uploader_table`
--

CREATE TABLE IF NOT EXISTS `<?php echo config_item('custom_uploader_table'); ?>` (
  `user_id` int(10) unsigned NOT NULL,
  `images_data` text,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `auto_populate`
--

CREATE TABLE IF NOT EXISTS `<?php echo config_item('auto_populate_table'); ?>` (
  `vehicle_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(5) NOT NULL,
  `make` varchar(36) NOT NULL,
  `model` varchar(36) NOT NULL,
  `color` varchar(36) NOT NULL,
  PRIMARY KEY (`vehicle_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `<?php echo config_item('auto_populate_table'); ?>` (`vehicle_id`, `type`, `make`, `model`, `color`) VALUES
(1, 'Car', 'Hyundai', 'Sonata', 'Green'),
(2, 'Car', 'Hyundai', 'Sonata', 'Black'),
(3, 'Car', 'Hyundai', 'Sonata', 'Blue'),
(4, 'Car', 'Ford', 'Fiesta', 'White'),
(5, 'Car', 'Ford', 'Fiesta', 'Silver'),
(6, 'Car', 'Ford', 'Fiesta', 'Red'),
(7, 'Truck', 'Toyota', 'Tacoma', 'Black'),
(8, 'Truck', 'Toyota', 'Tacoma', 'Pink'),
(9, 'Truck', 'Toyota', 'Tacoma', 'Burgundy'),
(10, 'Car', 'Toyota', 'Tercel', 'Red'),
(11, 'Car', 'Toyota', 'Tercel', 'Yellow'),
(12, 'Car', 'Toyota', 'Tercel', 'White'),
(13, 'Truck', 'Ford', 'F-150', 'Blue'),
(14, 'Truck', 'Ford', 'F-150', 'Charcoal'),
(15, 'Truck', 'Ford', 'F-150', 'Orange'),
(16, 'Car', 'Honda', 'Civic', 'White'),
(17, 'Car', 'Honda', 'Civic', 'Green'),
(18, 'Car', 'Honda', 'Civic', 'Red'),
(19, 'Car', 'Chevrolet', 'Nova', 'Yellow'),
(20, 'Car', 'Chevrolet', 'Nova', 'Blue'),
(21, 'Car', 'Chevrolet', 'Nova', 'Purple'),
(22, 'Car', 'Ford', 'Mustang', 'Gray'),
(23, 'Car', 'Ford', 'Mustang', 'Black'),
(24, 'Car', 'Ford', 'Mustang', 'White'),
(25, 'Truck', 'Toyota', 'Tundra', 'Red'),
(26, 'Truck', 'Toyota', 'Tundra', 'Orange'),
(27, 'Truck', 'Toyota', 'Tundra', 'Yellow'),
(28, 'Truck', 'Ford', 'F-250', 'Green'),
(29, 'Truck', 'Ford', 'F-250', 'Blue'),
(30, 'Truck', 'Ford', 'F-250', 'Purple'),
(31, 'Car', 'Hyundai', 'Accent', 'Gray'),
(32, 'Car', 'Hyundai', 'Accent', 'Silver'),
(33, 'Car', 'Hyundai', 'Accent', 'Black'),
(34, 'Car', 'Toyota', 'Corolla', 'White'),
(35, 'Car', 'Toyota', 'Corolla', 'Red'),
(36, 'Car', 'Toyota', 'Corolla', 'Orange'),
(37, 'Car', 'Honda', 'Accord', 'Yellow'),
(38, 'Car', 'Honda', 'Accord', 'Green'),
(39, 'Car', 'Honda', 'Accord', 'Blue'),
(40, 'Car', 'Honda', 'Fit', 'Purple'),
(41, 'Car', 'Honda', 'Fit', 'Gray'),
(42, 'Car', 'Honda', 'Fit', 'Silver'),
(43, 'Truck', 'Honda', 'Ridgeline', 'Black'),
(44, 'Truck', 'Honda', 'Ridgeline', 'White'),
(45, 'Truck', 'Honda', 'Ridgeline', 'Burgundy'),
(46, 'Truck', 'Ford', 'Ranger', 'Charcoal'),
(47, 'Truck', 'Ford', 'Ranger', 'Red'),
(48, 'Truck', 'Ford', 'Ranger', 'Orange'),
(49, 'Truck', 'Chevrolet', 'Colorado', 'Yellow'),
(50, 'Truck', 'Chevrolet', 'Colorado', 'Green'),
(51, 'Truck', 'Chevrolet', 'Colorado', 'Blue'),
(52, 'Truck', 'Chevrolet', 'Silverado', 'Purple'),
(53, 'Truck', 'Chevrolet', 'Silverado', 'Gray'),
(54, 'Truck', 'Chevrolet', 'Silverado', 'Black'),
(55, 'Car', 'Chevrolet', 'Impala', 'Pink'),
(56, 'Car', 'Chevrolet', 'Impala', 'Baby Blue'),
(57, 'Car', 'Chevrolet', 'Impala', 'Candy Red'),
(58, 'Car', 'Chevrolet', 'Corvette', 'Black'),
(59, 'Car', 'Chevrolet', 'Corvette', 'White'),
(60, 'Car', 'Chevrolet', 'Corvette', 'Tan'),
(61, 'Car', 'Mazda', 'RX-8', 'Brown'),
(62, 'Car', 'Mazda', 'RX-8', 'Red'),
(63, 'Car', 'Mazda', 'RX-8', 'Orange'),
(64, 'Car', 'Mazda', 'Miata', 'Yellow'),
(65, 'Car', 'Mazda', 'Miata', 'Green'),
(66, 'Car', 'Mazda', 'Miata', 'Black');

-- --------------------------------------------------------

--
-- Table structure for table `category_menu`
--

CREATE TABLE IF NOT EXISTS `<?php echo config_item('category_menu_table'); ?>` (
  `category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(56) NOT NULL,
  `parent_id` int(10) DEFAULT 0,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `<?php echo config_item('category_menu_table'); ?>` (`category_id`, `name`, `parent_id`) VALUES
(1, 'Food', 0),
(2, 'Mexican', 1),
(3, 'Italian', 1),
(4, 'American', 1),
(5, 'Tacos', 2),
(6, 'Burritos', 2),
(7, 'Enchiladas', 2),
(8, 'Spaghetti', 3),
(9, 'Lasagna', 3),
(10, 'Hamburgers', 4),
(11, 'Fries', 4);

<?php

/* End of file db.php */
/* Location: /application/views/sql/db.php */