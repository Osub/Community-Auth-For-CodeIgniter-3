--
-- Community Auth - MySQL database setup
--
-- Community Auth is an open source authentication application for CodeIgniter 3
--
-- @package     Community Auth
-- @author      Robert B Gottier
-- @copyright   Copyright (c) 2011 - 2016, Robert B Gottier. (http://brianswebdesign.com/)
-- @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
-- @link        http://community-auth.com
--

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `auth_sessions`
--
-- The IP address and user agent are only 
-- included so that sessions can be identified.
-- This can be helpful if you want to show the 
-- user their sessions, and allow them to terminate
-- selected sessions.
--

CREATE TABLE IF NOT EXISTS `auth_sessions` (
  `id` varchar(40) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `login_time` datetime DEFAULT NULL,
  `modified_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ips_on_hold`
--

CREATE TABLE IF NOT EXISTS `ips_on_hold` (
  `ai` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`ai`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `login_errors`
--

CREATE TABLE IF NOT EXISTS `login_errors` (
  `ai` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username_or_email` varchar(255) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`ai`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `denied_access`
--

CREATE TABLE IF NOT EXISTS `denied_access` (
  `ai` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `time` datetime NOT NULL,
  `reason_code` tinyint(1) unsigned DEFAULT 0,
  PRIMARY KEY (`ai`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `username_or_email_on_hold`
--

CREATE TABLE IF NOT EXISTS `username_or_email_on_hold` (
  `ai` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username_or_email` varchar(255) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`ai`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(10) unsigned NOT NULL,
  `username` varchar(12) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `auth_level` tinyint(3) unsigned NOT NULL,
  `banned` enum('0','1') NOT NULL DEFAULT '0',
  `passwd` varchar(60) NOT NULL,
  `passwd_recovery_code` varchar(60) DEFAULT NULL,
  `passwd_recovery_date` datetime DEFAULT NULL,
  `passwd_modified_at` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Trigger updates passwd_modified_at field if passwd modified
--

delimiter $$
DROP TRIGGER IF EXISTS ca_passwd_trigger ;
$$
CREATE TRIGGER ca_passwd_trigger BEFORE UPDATE ON users
FOR EACH ROW
BEGIN
    IF ((NEW.passwd <=> OLD.passwd) = 0) THEN
        SET NEW.passwd_modified_at = NOW();
    END IF;
END;$$
delimiter ;

-- --------------------------------------------------------

--
-- Table structure for table `acl_categories`
-- 

CREATE TABLE `acl_categories` (
  `category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `acl_actions`
-- 

CREATE TABLE `acl_actions` (
  `action_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `action_name` varchar(100) NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`action_id`),
  FOREIGN KEY (`category_id`) REFERENCES `acl_categories`(`category_id`) 
  ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `acl`
-- 

CREATE TABLE `acl` (
  `ai` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `action_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`ai`),
  FOREIGN KEY (`action_id`) REFERENCES `acl_actions`(`action_id`) 
  ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) 
  ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
