-- MySQL dump 10.13  Distrib 5.5.37, for Linux (x86_64)
--
-- Host: localhost    Database: newchexian
-- ------------------------------------------------------
-- Server version	5.5.37

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `account_appointment`
--

DROP TABLE IF EXISTS `account_appointment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account_appointment` (
  `id` int(11) NOT NULL,
  `accountid` int(11) NOT NULL,
  `appointment_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `state` enum('Waiting','Handled','Cancel') DEFAULT NULL,
  `remark` char(255) NOT NULL DEFAULT '',
  `user_handle` int(11) NOT NULL DEFAULT '-1',
  `date_handle` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_create` int(11) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id`),
  KEY `accountid` (`accountid`),
  KEY `user_handle` (`user_handle`),
  KEY `date_handle` (`date_handle`),
  KEY `appointment_time` (`appointment_time`),
  KEY `state` (`state`),
  KEY `date_create` (`date_create`),
  KEY `user_create` (`user_create`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `account_appointment_seq`
--

DROP TABLE IF EXISTS `account_appointment_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account_appointment_seq` (
  `id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `account_track`
--

DROP TABLE IF EXISTS `account_track`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account_track` (
  `id` int(11) NOT NULL,
  `accountid` int(11) NOT NULL COMMENT 'å®¢æˆ·ID',
  `user_create` int(11) NOT NULL DEFAULT '-1' COMMENT 'åˆ›å»ºäºº',
  `status` char(30) NOT NULL DEFAULT 'APPOINTMENT_QUOTATION' COMMENT 'é”€å”®ç»“æžœ',
  `report` char(30) DEFAULT NULL COMMENT 'é”€å”®è¯´æ˜Ž',
  `remark` text COMMENT 'å¤‡æ³¨',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'åˆ›å»ºæ—¶é—´',
  `preset_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'é¢„çº¦æ—¶é—´',
  PRIMARY KEY (`id`),
  KEY `accountid` (`accountid`),
  KEY `status` (`status`),
  KEY `report` (`report`),
  KEY `preset_time` (`preset_time`),
  KEY `date_create` (`date_create`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `account_track_seq`
--

DROP TABLE IF EXISTS `account_track_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account_track_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `owner` char(100) NOT NULL COMMENT 'å®¢æˆ·åç§°',
  `contact` char(50) NOT NULL DEFAULT '' COMMENT 'è”ç³»äºº',
  `telphone` char(50) NOT NULL DEFAULT '' COMMENT 'å›ºå®šç”µè¯',
  `mobile` char(20) DEFAULT NULL COMMENT 'ç”µè¯å·ç ',
  `address` char(255) DEFAULT NULL COMMENT 'åœ°å€',
  `id_code` char(100) DEFAULT NULL COMMENT 'èº«ä»½è¯/æœºæž„ä»£ç ',
  `lending_bank` char(50) DEFAULT NULL COMMENT 'è´·æ¬¾é“¶è¡Œ',
  `intention` char(10) DEFAULT NULL COMMENT 'æ„å‘ç¨‹åº¦',
  `last_policy` char(100) DEFAULT NULL COMMENT 'åŽ»å¹´ä¿å•å·',
  `team` char(100) DEFAULT NULL COMMENT 'å“ç‰Œå›¢é˜Ÿ',
  `area` char(50) DEFAULT NULL COMMENT 'ç‰‡åŒº',
  `park` char(50) DEFAULT NULL COMMENT 'å›­åŒº',
  `purchase_price` int(11) NOT NULL DEFAULT '0' COMMENT 'æ–°è½¦è´­ç½®ä»·(å…ƒ)',
  `plate_no` char(20) DEFAULT NULL COMMENT 'è½¦ç‰Œå·',
  `vehicle_type` char(20) DEFAULT NULL COMMENT 'è½¦è¾†ç§ç±»',
  `use_character` char(20) NOT NULL COMMENT 'ä½¿ç”¨æ€§è´¨',
  `model` char(50) NOT NULL COMMENT 'å“ç‰Œåž‹å·',
  `vin` char(50) NOT NULL COMMENT 'è½¦è¾†è¯†åˆ«ä»£ç /è½¦æž¶å·',
  `engine_no` char(50) DEFAULT '' COMMENT 'å‘åŠ¨æœºå·',
  `register_date` date DEFAULT '0000-00-00' COMMENT 'æ³¨å†Œæ—¥æœŸ',
  `register_address` char(100) DEFAULT '' COMMENT 'æ³¨å†Œåœ°',
  `seats` int(11) DEFAULT '0' COMMENT 'æ ¸å®šè½½äººæ•°',
  `kerb_mass` int(11) DEFAULT '0' COMMENT 'æ•´å¤‡è´¨é‡(KG)',
  `total_mass` int(11) DEFAULT '0' COMMENT 'æ€»è´¨é‡(KG)',
  `ratify_load` int(11) DEFAULT '0' COMMENT 'æ ¸å®šè½½é‡(KG)',
  `tow_mass` int(11) DEFAULT '0' COMMENT 'å‡†ç‰µæ€»è´¨é‡',
  `engine` int(11) DEFAULT '0' COMMENT 'å‘åŠ¨æœºæŽ’æ°”é‡(ML)',
  `power` int(11) DEFAULT '0' COMMENT 'åŠŸçŽ‡(KW)',
  `body_size` char(50) DEFAULT '' COMMENT 'è½¦èº«å°ºå¯¸',
  `body_color` char(10) DEFAULT '' COMMENT 'è½¦èº«é¢œè‰²',
  `origin` char(20) NOT NULL DEFAULT 'DOMESTIC' COMMENT 'äº§åœ°',
  `company` int(11) DEFAULT '0' COMMENT 'ä¿é™©å…¬å¸',
  `batch` char(10) NOT NULL DEFAULT 'A' COMMENT 'æ‰¹æ¬¡',
  `type` char(20) NOT NULL DEFAULT 'FIRST_YEAR' COMMENT 'ç±»åž‹',
  `status` char(30) NOT NULL DEFAULT 'FIRST_DIAL' COMMENT 'çŠ¶æ€',
  `user_attach` int(11) NOT NULL DEFAULT '-1' COMMENT 'å½’å±žäºŽ',
  `user_create` int(11) NOT NULL DEFAULT '-1' COMMENT 'åˆ›å»ºäºº',
  `user_modify` int(11) NOT NULL DEFAULT '-1' COMMENT 'ä¿®æ”¹äºº',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'åˆ›å»ºæ—¶é—´',
  `date_modify` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'ä¿®æ”¹æ—¶é—´',
  `expiration_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'ä¿é™©åˆ°æœŸæ—¥æœŸ',
  `preset_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'é¢„çº¦æ—¶é—´',
  `remark` text,
  `report` char(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vin` (`vin`),
  KEY `owner` (`owner`),
  KEY `mobile` (`mobile`),
  KEY `id_code` (`id_code`),
  KEY `intention` (`intention`),
  KEY `lending_bank` (`lending_bank`),
  KEY `plate_no` (`plate_no`),
  KEY `engine_no` (`engine_no`),
  KEY `vehicle_type` (`vehicle_type`),
  KEY `use_character` (`use_character`),
  KEY `telphone` (`telphone`),
  KEY `company` (`company`),
  KEY `batch` (`batch`),
  KEY `type` (`type`),
  KEY `status` (`status`),
  KEY `user_attach` (`user_attach`),
  KEY `user_create` (`user_create`),
  KEY `user_modify` (`user_modify`),
  KEY `date_create` (`date_create`),
  KEY `date_modify` (`date_modify`),
  KEY `register_date` (`register_date`),
  KEY `expiration_date` (`expiration_date`),
  KEY `preset_time` (`preset_time`),
  KEY `status_user` (`status`,`user_attach`),
  KEY `comx_index` (`expiration_date`,`status`,`user_attach`,`register_date`),
  KEY `comx_sue_index` (`status`,`user_attach`,`expiration_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `accounts_seq`
--

DROP TABLE IF EXISTS `accounts_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accounts_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `app_version`
--

DROP TABLE IF EXISTS `app_version`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_version` (
  `current_version` varchar(20) DEFAULT NULL,
  `product_name` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company` (
  `id` int(11) NOT NULL,
  `name` char(100) NOT NULL COMMENT 'ä¿é™©å…¬å¸',
  `description` char(200) NOT NULL COMMENT 'ç®€ä»‹',
  `user_attach` int(11) NOT NULL DEFAULT '-1' COMMENT 'å½’å±žäºŽ',
  `user_create` int(11) NOT NULL DEFAULT '-1' COMMENT 'åˆ›å»ºäºº',
  `user_modify` int(11) NOT NULL DEFAULT '-1' COMMENT 'ä¿®æ”¹äºº',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'åˆ›å»ºæ—¶é—´',
  `date_modify` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'ä¿®æ”¹æ—¶é—´',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `user_attach` (`user_attach`),
  KEY `user_create` (`user_create`),
  KEY `user_modify` (`user_modify`),
  KEY `date_create` (`date_create`),
  KEY `date_modify` (`date_modify`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `company_seq`
--

DROP TABLE IF EXISTS `company_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contract_income`
--

DROP TABLE IF EXISTS `contract_income`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contract_income` (
  `id` int(11) NOT NULL,
  `flow_no` char(50) NOT NULL,
  `contractid` int(11) NOT NULL DEFAULT '-1',
  `money` float NOT NULL DEFAULT '0',
  `mode_payment` char(100) NOT NULL DEFAULT 'transfer',
  `payee` char(200) NOT NULL DEFAULT '',
  `payee_bank` char(200) DEFAULT NULL,
  `payee_account` char(100) DEFAULT NULL,
  `payee_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `payer` char(200) NOT NULL DEFAULT '',
  `payer_bank` char(200) DEFAULT NULL,
  `payer_account` char(100) DEFAULT NULL,
  `payer_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_attach` int(11) NOT NULL DEFAULT '-1',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_create` int(11) NOT NULL DEFAULT '-1',
  `date_modify` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_modify` int(11) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id`),
  KEY `contractid` (`contractid`),
  KEY `mode_payment` (`mode_payment`),
  KEY `money` (`money`),
  KEY `payee` (`payee`),
  KEY `payee_time` (`payee_time`),
  KEY `payer` (`payer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contract_income_seq`
--

DROP TABLE IF EXISTS `contract_income_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contract_income_seq` (
  `id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contracts`
--

DROP TABLE IF EXISTS `contracts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contracts` (
  `id` int(11) NOT NULL,
  `accountid` int(11) NOT NULL DEFAULT '-1',
  `contract_name` char(200) NOT NULL,
  `contract_no` char(100) NOT NULL,
  `contract_type` char(100) NOT NULL DEFAULT '',
  `contract_target` char(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `sum_money` float NOT NULL DEFAULT '0',
  `mode_parment` char(100) NOT NULL DEFAULT '',
  `first_bank_name` char(200) DEFAULT NULL,
  `first_bank_account` char(100) DEFAULT NULL,
  `second_bank_name` char(200) DEFAULT NULL,
  `second_bank_account` char(100) DEFAULT NULL,
  `first_party` char(100) NOT NULL,
  `first_deputy` char(100) NOT NULL,
  `second_party` char(100) NOT NULL,
  `second_deputy` char(100) NOT NULL,
  `third_party` char(100) DEFAULT NULL,
  `third_deputy` char(100) DEFAULT NULL,
  `summary` text,
  `date_signing` date NOT NULL DEFAULT '0000-00-00',
  `remark` text,
  `user_attach` int(11) NOT NULL DEFAULT '-1',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_create` int(11) NOT NULL DEFAULT '-1',
  `date_modify` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_modify` int(11) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id`),
  KEY `accountid` (`accountid`),
  KEY `contract_name` (`contract_name`),
  KEY `contract_no` (`contract_no`),
  KEY `contract_type` (`contract_type`),
  KEY `start_date` (`start_date`),
  KEY `end_date` (`end_date`),
  KEY `sum_money` (`sum_money`),
  KEY `first_party` (`first_party`),
  KEY `second_party` (`second_party`),
  KEY `mode_parment` (`mode_parment`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contracts_seq`
--

DROP TABLE IF EXISTS `contracts_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contracts_seq` (
  `id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `daily_analysis`
--

DROP TABLE IF EXISTS `daily_analysis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `daily_analysis` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `groupid` int(11) NOT NULL DEFAULT '-1',
  `first_dial` int(11) DEFAULT '0',
  `appointment` int(11) DEFAULT '0',
  `success` int(11) DEFAULT '0',
  `failure` int(11) DEFAULT '0',
  `create_date` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `userid` (`userid`,`create_date`),
  KEY `groupid` (`groupid`),
  KEY `first_dial` (`first_dial`),
  KEY `appointment` (`appointment`),
  KEY `success` (`success`),
  KEY `failure` (`failure`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `daily_analysis_seq`
--

DROP TABLE IF EXISTS `daily_analysis_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `daily_analysis_seq` (
  `id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `filters`
--

DROP TABLE IF EXISTS `filters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `filters` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `name` char(50) NOT NULL,
  `module_name` char(50) NOT NULL,
  `filter_where` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `module_name` (`module_name`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` char(50) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `groups_seq`
--

DROP TABLE IF EXISTS `groups_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups_seq` (
  `id` int(11) DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `homes`
--

DROP TABLE IF EXISTS `homes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `homes` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `name` char(50) NOT NULL,
  `cols` int(11) NOT NULL DEFAULT '3',
  `rows` int(11) NOT NULL DEFAULT '2',
  `default_home` enum('NO','YES') DEFAULT 'NO',
  `cell1_title` char(50) DEFAULT NULL,
  `cell1_url` char(255) DEFAULT NULL,
  `cell2_title` char(50) DEFAULT NULL,
  `cell2_url` char(255) DEFAULT NULL,
  `cell3_title` char(50) DEFAULT NULL,
  `cell3_url` char(255) DEFAULT NULL,
  `cell4_title` char(50) DEFAULT NULL,
  `cell4_url` char(255) DEFAULT NULL,
  `cell5_title` char(50) DEFAULT NULL,
  `cell5_url` char(255) DEFAULT NULL,
  `cell6_title` char(50) DEFAULT NULL,
  `cell6_url` char(255) DEFAULT NULL,
  `cell7_title` char(50) DEFAULT NULL,
  `cell7_url` char(255) DEFAULT NULL,
  `cell8_title` char(50) DEFAULT NULL,
  `cell8_url` char(255) DEFAULT NULL,
  `cell9_title` char(50) DEFAULT NULL,
  `cell9_url` char(255) DEFAULT NULL,
  `cell10_title` char(50) DEFAULT NULL,
  `cell10_url` char(255) DEFAULT NULL,
  `cell11_title` char(50) DEFAULT NULL,
  `cell11_url` char(255) DEFAULT NULL,
  `cell12_title` char(50) DEFAULT NULL,
  `cell12_url` char(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `homes_seq`
--

DROP TABLE IF EXISTS `homes_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `homes_seq` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `insurance_calculate_log`
--

DROP TABLE IF EXISTS `insurance_calculate_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `insurance_calculate_log` (
  `id` int(10) unsigned NOT NULL,
  `autoid` int(11) NOT NULL DEFAULT '-1' COMMENT 'insurance_auto_info.id',
  `calculate_no` char(100) NOT NULL COMMENT 'ç®—ä»·çºªå½•ç¼–å·',
  `insurance_company` char(10) NOT NULL DEFAULT 'PICC' COMMENT 'ä¿é™©å…¬å¸(ç®—ä»·ç§ç±»)',
  `glass_origin` char(10) NOT NULL DEFAULT 'DOMESTIC' COMMENT 'çŽ»ç’ƒç±»åž‹',
  `floating_rate` char(10) NOT NULL DEFAULT 'A4' COMMENT 'äº¤å¼ºé™©æµ®åŠ¨å› å­(äº‹æ•…è®°å½•)',
  `claim_records` char(20) NOT NULL DEFAULT 'LAST_YEAR_CLAIM_ONE' COMMENT 'ç´¢èµ”è®°å½•',
  `years_of_insurance` char(20) NOT NULL DEFAULT 'RENEWAL_OF_INSURANCE' COMMENT 'æŠ•ä¿å¹´åº¦',
  `designated_driver` char(20) NOT NULL DEFAULT 'NO_DESIGNATED_DRIVER' COMMENT 'æŒ‡å®šé©¾é©¶äºº',
  `driver_age` char(20) NOT NULL DEFAULT '25_30_AGE' COMMENT 'é©¾é©¶äººå¹´é¾„',
  `driver_sex` char(10) NOT NULL DEFAULT 'MALE' COMMENT 'é©¾é©¶äººæ€§åˆ«',
  `driving_years` char(20) NOT NULL DEFAULT 'GREATER_3_YEARS' COMMENT 'é©¾é©¶äººé©¾é¾„',
  `driving_area` char(20) NOT NULL DEFAULT 'CHINA_TERRITORY' COMMENT 'è¡Œé©¶åŒºåŸŸ',
  `average_annual_mileage` char(20) NOT NULL DEFAULT 'LESS_30000_KM' COMMENT 'å¹´å‡è¡Œé©¶é‡Œç¨‹',
  `multiple_insurance` char(20) NOT NULL DEFAULT 'MULTIPLE_INSURANCE_1' COMMENT 'å¤šé™©ç§ä¼˜æƒ ',
  `buy_types` char(255) NOT NULL DEFAULT '' COMMENT 'è´­ä¹°é™©ç§åˆ—è¡¨',
  `mvtalci_start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'äº¤å¼ºé™©ç”Ÿæ•ˆæ—¶é—´',
  `mvtalci_months` int(11) NOT NULL DEFAULT '12' COMMENT 'äº¤å¼ºé™©æœ‰æ•ˆæœˆæ•°',
  `other_start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'å•†ä¸šé™©ç”Ÿæ•ˆæ—¶é—´',
  `other_months` int(11) NOT NULL DEFAULT '12' COMMENT 'å•†ä¸šé™©æœ‰æ•ˆæœˆæ•°',
  `tvdi_insurance_amount` int(11) NOT NULL DEFAULT '0' COMMENT 'è½¦æŸé™©ä¿é¢',
  `doc_amount` int(11) NOT NULL DEFAULT '0' COMMENT 'è½¦æŸé™©å…èµ”é¢',
  `ttbli_insurance_amount` int(11) NOT NULL DEFAULT '0' COMMENT 'ä¸‰è€…é™©ä¿é¢',
  `twcdmvi_insurance_amount` int(11) NOT NULL DEFAULT '0' COMMENT 'ç›—æŠ¢é™©ä¿é¢',
  `tcpli_insurance_driver_amount` int(11) NOT NULL DEFAULT '0' COMMENT 'åº§ä½é™©(å¸æœº)ä¿é¢',
  `tcpli_insurance_passenger_amount` int(11) NOT NULL DEFAULT '0' COMMENT 'åº§ä½é™©(ä¹˜å®¢)ä¿é¢',
  `passengers` int(11) NOT NULL DEFAULT '0' COMMENT 'åº§ä½é™©ä¹˜å®¢æ•°é‡',
  `bsdi_insurance_amount` int(11) NOT NULL DEFAULT '0' COMMENT 'åˆ’ç—•é™©ä¿é¢',
  `nieli_insurance_amount` int(11) NOT NULL DEFAULT '0' COMMENT 'æ–°å¢žè®¾å¤‡æŸå¤±é™©ä¿é¢',
  `bgai_insurance_amount` int(11) NOT NULL DEFAULT '0' COMMENT 'çŽ»ç’ƒé™©ä¿é¢',
  `stsfs_rate` int(11) NOT NULL DEFAULT '0' COMMENT 'æŒ‡å®šä¸“ä¿®åŽ‚ä¸Šæµ®æ¯”ä¾‹',
  `associate_userid` int(11) DEFAULT '-1',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_userid` int(11) NOT NULL DEFAULT '-1',
  `modify_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modify_userid` int(11) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id`),
  KEY `autoid` (`autoid`),
  KEY `associate_userid` (`associate_userid`),
  KEY `create_time` (`create_time`),
  KEY `create_userid` (`create_userid`),
  KEY `modify_time` (`modify_time`),
  KEY `modify_userid` (`modify_userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `insurance_calculate_log_seq`
--

DROP TABLE IF EXISTS `insurance_calculate_log_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `insurance_calculate_log_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `library_category`
--

DROP TABLE IF EXISTS `library_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `library_category` (
  `id` int(11) NOT NULL,
  `name` char(100) NOT NULL COMMENT 'åˆ†ç±»åç§°',
  `parent` int(11) NOT NULL DEFAULT '0' COMMENT 'çˆ¶çº§id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `parent` (`parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `library_category_seq`
--

DROP TABLE IF EXISTS `library_category_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `library_category_seq` (
  `id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `library_post`
--

DROP TABLE IF EXISTS `library_post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `library_post` (
  `id` int(11) NOT NULL,
  `title` char(200) NOT NULL COMMENT 'æ ‡é¢˜',
  `content` longtext COMMENT 'å†…å®¹',
  `categoryid` int(11) DEFAULT '0',
  `status` char(10) NOT NULL DEFAULT 'PUBLISH' COMMENT 'æ–‡ç« çŠ¶æ€',
  `user_attach` int(11) NOT NULL DEFAULT '-1' COMMENT 'å½’å±žç»„/ç”¨æˆ·',
  `user_create` int(11) NOT NULL DEFAULT '-1' COMMENT 'åˆ›å»ºäºº',
  `user_modify` int(11) NOT NULL DEFAULT '-1' COMMENT 'ä¿®æ”¹äºº',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'åˆ›å»ºæ—¶é—´',
  `date_modify` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'ä¿®æ”¹æ—¶é—´',
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `categoryid` (`categoryid`),
  KEY `status` (`status`),
  KEY `user_create` (`user_create`),
  KEY `user_modify` (`user_modify`),
  KEY `date_create` (`date_create`),
  KEY `date_modify` (`date_modify`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `library_post_seq`
--

DROP TABLE IF EXISTS `library_post_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `library_post_seq` (
  `id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `name` char(50) NOT NULL,
  `title` char(100) DEFAULT NULL,
  `seq` int(11) DEFAULT '0',
  `action` enum('SUB_MENU','OPEN_MODULE','OPEN_WINDOWS') DEFAULT 'SUB_MENU',
  `target` char(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `seq` (`seq`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `menus_seq`
--

DROP TABLE IF EXISTS `menus_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menus_seq` (
  `id` int(11) NOT NULL DEFAULT '5'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menuid` int(11) NOT NULL,
  `module_name` char(50) NOT NULL,
  `module_describe` char(250) DEFAULT NULL,
  `default_action` char(50) NOT NULL DEFAULT 'index',
  `seq` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `menuid` (`menuid`),
  KEY `seq` (`seq`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notices`
--

DROP TABLE IF EXISTS `notices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notices` (
  `id` int(11) NOT NULL,
  `title` char(60) NOT NULL,
  `tag` enum('general','important','emergent') DEFAULT NULL,
  `contant` text,
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_create` int(11) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id`),
  KEY `date_create` (`date_create`),
  KEY `title` (`title`),
  KEY `tag` (`tag`),
  KEY `user_create` (`user_create`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notices_seq`
--

DROP TABLE IF EXISTS `notices_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notices_seq` (
  `id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `park`
--

DROP TABLE IF EXISTS `park`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `park` (
  `id` int(11) NOT NULL,
  `area` char(50) NOT NULL COMMENT 'ç‰‡åŒº',
  `park` char(50) NOT NULL COMMENT 'å›­åŒº',
  PRIMARY KEY (`id`),
  KEY `area` (`area`),
  KEY `park` (`park`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `park_seq`
--

DROP TABLE IF EXISTS `park_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `park_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `permission`
--

DROP TABLE IF EXISTS `permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission` (
  `id` int(11) NOT NULL,
  `name` char(50) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `permission_actions`
--

DROP TABLE IF EXISTS `permission_actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission_actions` (
  `permissionid` int(11) NOT NULL,
  `module_name` char(50) NOT NULL,
  `action_name` char(50) NOT NULL,
  `is_allow` enum('YES','NO') DEFAULT 'YES',
  KEY `permissionid` (`permissionid`),
  KEY `module_name` (`module_name`),
  KEY `action_name` (`action_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `permission_fields`
--

DROP TABLE IF EXISTS `permission_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission_fields` (
  `permissionid` int(11) NOT NULL,
  `module_name` char(50) NOT NULL,
  `field_name` char(50) NOT NULL,
  `is_show` enum('YES','NO') DEFAULT 'YES',
  `is_modify` enum('YES','NO') DEFAULT 'YES',
  `hidden_start` int(11) DEFAULT NULL,
  `hidden_end` int(11) DEFAULT NULL,
  KEY `permissionid` (`permissionid`),
  KEY `module_name` (`module_name`),
  KEY `field_name` (`field_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `permission_modules`
--

DROP TABLE IF EXISTS `permission_modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission_modules` (
  `permissionid` int(11) NOT NULL,
  `module_name` char(50) NOT NULL,
  `is_allow` enum('YES','NO') DEFAULT 'YES',
  `recordset_groups` text,
  `recordset_users` text,
  KEY `permissionid` (`permissionid`),
  KEY `module_name` (`module_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `permission_seq`
--

DROP TABLE IF EXISTS `permission_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission_seq` (
  `id` int(11) DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `result_report`
--

DROP TABLE IF EXISTS `result_report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `result_report` (
  `id` int(11) NOT NULL,
  `status` char(30) NOT NULL DEFAULT 'APPOINTMENT_QUOTATION' COMMENT 'é”€å”®ç»“æžœ',
  `report` char(100) NOT NULL COMMENT 'é”€å”®è¯´æ˜Ž',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `report` (`report`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `result_report_seq`
--

DROP TABLE IF EXISTS `result_report_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `result_report_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sms_mas_status`
--

DROP TABLE IF EXISTS `sms_mas_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sms_mas_status` (
  `sid` int(11) NOT NULL,
  `passport` char(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sms_notify`
--

DROP TABLE IF EXISTS `sms_notify`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sms_notify` (
  `id` int(11) NOT NULL,
  `userid` int(11) DEFAULT NULL,
  `dir` enum('send','receive') DEFAULT NULL,
  `callerid` char(50) DEFAULT NULL,
  `calleeid` char(50) DEFAULT NULL,
  `msgid` int(11) DEFAULT NULL,
  `state` enum('wait','success','failure') DEFAULT NULL,
  `errmsg` char(255) DEFAULT NULL,
  `send_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `content` text,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `msgid` (`msgid`),
  KEY `state` (`state`),
  KEY `send_time` (`send_time`),
  KEY `callerid` (`callerid`),
  KEY `calleeid` (`calleeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sms_notify_seq`
--

DROP TABLE IF EXISTS `sms_notify_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sms_notify_seq` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_login_log`
--

DROP TABLE IF EXISTS `user_login_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_login_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `user_name` char(20) NOT NULL,
  `state` enum('LOGIN','LOGOUT') NOT NULL,
  `oper_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip_address` char(30) DEFAULT NULL,
  `user_agent` char(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `user_name` (`user_name`),
  KEY `oper_time` (`oper_time`)
) ENGINE=MyISAM AUTO_INCREMENT=11596 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_operation_log`
--

DROP TABLE IF EXISTS `user_operation_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_operation_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `user_name` char(20) NOT NULL,
  `module_name` char(50) NOT NULL,
  `action_name` char(50) NOT NULL,
  `oper_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `log_info` text,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `user_name` (`user_name`),
  KEY `oper_time` (`oper_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_name` char(20) NOT NULL,
  `user_password` char(40) NOT NULL DEFAULT '',
  `is_admin` enum('NO','YES') NOT NULL DEFAULT 'NO',
  `name` char(50) DEFAULT '',
  `description` text,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_user_id` int(11) DEFAULT '-1',
  `title` char(50) DEFAULT NULL,
  `department` char(50) DEFAULT NULL,
  `post` char(50) DEFAULT NULL,
  `phone_home` char(50) DEFAULT NULL,
  `phone_mobile` char(50) DEFAULT NULL,
  `phone_work` char(50) DEFAULT NULL,
  `phone_other` char(50) DEFAULT NULL,
  `phone_fax` char(50) DEFAULT NULL,
  `email` char(50) DEFAULT NULL,
  `qq_number` char(50) DEFAULT NULL,
  `status` enum('Active','Activing','Invalid') DEFAULT 'Active',
  `agent_have` enum('NO','YES') DEFAULT 'NO',
  `agent_number` char(50) DEFAULT '',
  `agent_login` enum('NO','YES') DEFAULT 'NO',
  `agent_popup` enum('NO','YES') DEFAULT 'NO',
  `agent_status` enum('ONLINE','OFFLINE') DEFAULT 'ONLINE',
  `accesskey` char(50) DEFAULT NULL,
  `address_street` char(150) DEFAULT NULL,
  `address_city` char(100) DEFAULT NULL,
  `address_state` char(100) DEFAULT NULL,
  `address_country` char(25) DEFAULT NULL,
  `address_postalcode` char(9) DEFAULT NULL,
  `imagename` char(255) DEFAULT NULL,
  `user_preferences` text,
  `birthday` date DEFAULT NULL,
  `groupid` int(11) DEFAULT '-1',
  `permissionid` int(11) DEFAULT '-1',
  `agent_queue` char(50) DEFAULT 'NONE',
  `agent_workno` char(50) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `groupid` (`groupid`),
  KEY `permissionid` (`permissionid`),
  KEY `user_user_name_idx` (`user_name`),
  KEY `user_agent_number_idx` (`agent_number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users_seq`
--

DROP TABLE IF EXISTS `users_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_seq` (
  `id` int(11) DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `zswitch_call_details`
--

DROP TABLE IF EXISTS `zswitch_call_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zswitch_call_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '-1',
  `direction` char(20) DEFAULT NULL,
  `caller_id_number` char(100) DEFAULT NULL,
  `callee_id_number` char(100) DEFAULT NULL,
  `destination_number` char(100) DEFAULT NULL,
  `uuid` char(50) NOT NULL,
  `source` char(50) DEFAULT NULL,
  `context` char(50) DEFAULT NULL,
  `channel_name` char(100) DEFAULT NULL,
  `channel_created_datetime` datetime DEFAULT NULL,
  `channel_answered_datetime` datetime DEFAULT NULL,
  `channel_hangup_datetime` datetime DEFAULT NULL,
  `bleg_uuid` char(50) DEFAULT NULL,
  `hangup_cause` char(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `direction` (`direction`),
  KEY `caller_id_number` (`caller_id_number`),
  KEY `callee_id_number` (`callee_id_number`),
  KEY `destination_number` (`destination_number`),
  KEY `source` (`source`),
  KEY `context` (`context`),
  KEY `channel_name` (`channel_name`),
  KEY `channel_created_datetime` (`channel_created_datetime`),
  KEY `channel_answered_datetime` (`channel_answered_datetime`),
  KEY `channel_hangup_datetime` (`channel_hangup_datetime`),
  KEY `hangup_cause` (`hangup_cause`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `zswitch_call_event`
--

DROP TABLE IF EXISTS `zswitch_call_event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zswitch_call_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `callerid` char(50) NOT NULL DEFAULT '',
  `calleeid` char(50) NOT NULL DEFAULT '',
  `event_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `event_type` enum('callin_ringing','answered','hangup','callout_ringing') DEFAULT NULL,
  `agent` char(100) NOT NULL DEFAULT '',
  `queue` char(100) NOT NULL DEFAULT '',
  `userid` int(11) NOT NULL DEFAULT '-1',
  `uuid` char(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `zswitch_cc_account_evaluate`
--

DROP TABLE IF EXISTS `zswitch_cc_account_evaluate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zswitch_cc_account_evaluate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '-1',
  `caller` char(100) DEFAULT NULL,
  `callee` char(100) DEFAULT NULL,
  `uuid` char(50) NOT NULL,
  `agent` char(100) NOT NULL DEFAULT '',
  `ptime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dtmf` char(5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ptime` (`ptime`),
  KEY `userid` (`userid`),
  KEY `uuid` (`uuid`),
  KEY `agent` (`agent`),
  KEY `caller` (`caller`),
  KEY `callee` (`callee`),
  KEY `dtmf` (`dtmf`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `zswitch_cc_agent_cdr`
--

DROP TABLE IF EXISTS `zswitch_cc_agent_cdr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zswitch_cc_agent_cdr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '-1',
  `queue` char(100) NOT NULL DEFAULT '',
  `agent_name` char(50) NOT NULL DEFAULT '',
  `dir` char(20) NOT NULL DEFAULT 'callin',
  `other_number` char(50) NOT NULL DEFAULT '',
  `uuid` char(50) NOT NULL,
  `source` char(50) DEFAULT '',
  `context` char(50) DEFAULT '',
  `channel_name` char(100) DEFAULT '',
  `created_datetime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `answered_datetime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `hangup_datetime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `bleg_uuid` char(50) DEFAULT '',
  `hangup_cause` char(50) DEFAULT '',
  `total_timed` int(11) NOT NULL DEFAULT '0',
  `talk_timed` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `agent_name` (`agent_name`),
  KEY `dir` (`dir`),
  KEY `other_number` (`other_number`),
  KEY `created_datetime` (`created_datetime`),
  KEY `answered_datetime` (`answered_datetime`),
  KEY `hangup_datetime` (`hangup_datetime`),
  KEY `hangup_cause` (`hangup_cause`),
  KEY `talk_timed` (`talk_timed`),
  KEY `total_timed` (`total_timed`)
) ENGINE=InnoDB AUTO_INCREMENT=351801 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `zswitch_cc_agent_state`
--

DROP TABLE IF EXISTS `zswitch_cc_agent_state`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zswitch_cc_agent_state` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '-1',
  `name` char(100) NOT NULL DEFAULT '',
  `contact` char(200) NOT NULL DEFAULT '',
  `status` char(20) NOT NULL DEFAULT '',
  `state` char(20) NOT NULL DEFAULT '',
  `queue` char(100) NOT NULL DEFAULT '',
  `uuid` char(100) NOT NULL DEFAULT '',
  `other_uuid` char(100) NOT NULL DEFAULT '',
  `other_number` char(50) NOT NULL DEFAULT '',
  `dir` char(20) NOT NULL DEFAULT '',
  `start_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `answer_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `hangup_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `hangup_cause` char(50) NOT NULL DEFAULT '',
  `total_callins_answered` int(11) NOT NULL DEFAULT '0',
  `total_callins_no_answer` int(11) NOT NULL DEFAULT '0',
  `today_callins_answered` int(11) NOT NULL DEFAULT '0',
  `today_callins_no_answer` int(11) NOT NULL DEFAULT '0',
  `total_callin_talk_time` int(11) NOT NULL DEFAULT '0',
  `today_callin_talk_time` int(11) NOT NULL DEFAULT '0',
  `total_callouts_answered` int(11) NOT NULL DEFAULT '0',
  `total_callouts_no_answer` int(11) NOT NULL DEFAULT '0',
  `today_callouts_answered` int(11) NOT NULL DEFAULT '0',
  `today_callouts_no_answer` int(11) NOT NULL DEFAULT '0',
  `total_callout_talk_time` int(11) NOT NULL DEFAULT '0',
  `today_callout_talk_time` int(11) NOT NULL DEFAULT '0',
  `total_calls` int(11) NOT NULL DEFAULT '0',
  `today_calls` int(11) NOT NULL DEFAULT '0',
  `total_talk_time` int(11) NOT NULL DEFAULT '0',
  `today_talk_time` int(11) NOT NULL DEFAULT '0',
  `workno` char(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=7114 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `zswitch_cc_member_state`
--

DROP TABLE IF EXISTS `zswitch_cc_member_state`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zswitch_cc_member_state` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `queue` char(100) NOT NULL DEFAULT '',
  `uuid` char(100) NOT NULL DEFAULT '',
  `caller_number` char(50) NOT NULL DEFAULT '',
  `joined_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `bridge_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `agent_name` char(50) NOT NULL DEFAULT '',
  `state` char(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `queue` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `zswitch_cc_queue_cdr`
--

DROP TABLE IF EXISTS `zswitch_cc_queue_cdr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zswitch_cc_queue_cdr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(100) NOT NULL DEFAULT '',
  `queue` char(100) NOT NULL DEFAULT '',
  `caller_number` char(50) NOT NULL DEFAULT '',
  `agent_name` char(50) NOT NULL DEFAULT '',
  `joined_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `bridge_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `state` char(50) NOT NULL DEFAULT '',
  `total_timed` int(11) NOT NULL DEFAULT '0',
  `wait_timed` int(11) NOT NULL DEFAULT '0',
  `talk_timed` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `queue` (`queue`),
  KEY `caller_number` (`caller_number`),
  KEY `agent_name` (`agent_name`),
  KEY `joined_time` (`joined_time`),
  KEY `bridge_time` (`bridge_time`),
  KEY `end_time` (`end_time`),
  KEY `state` (`state`),
  KEY `talk_timed` (`talk_timed`),
  KEY `wait_timed` (`wait_timed`),
  KEY `total_timed` (`total_timed`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `zswitch_cc_queue_state`
--

DROP TABLE IF EXISTS `zswitch_cc_queue_state`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zswitch_cc_queue_state` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(100) NOT NULL DEFAULT '',
  `state` enum('ON','OFF') NOT NULL DEFAULT 'ON',
  `total_calls_answered` int(11) NOT NULL DEFAULT '0',
  `total_calls_no_answer` int(11) NOT NULL DEFAULT '0',
  `today_calls_answered` int(11) NOT NULL DEFAULT '0',
  `today_calls_no_answer` int(11) NOT NULL DEFAULT '0',
  `total_talk_time` int(11) NOT NULL DEFAULT '0',
  `today_talk_time` int(11) NOT NULL DEFAULT '0',
  `current_members` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=210 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `zswitch_ps_autodial_job`
--

DROP TABLE IF EXISTS `zswitch_ps_autodial_job`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zswitch_ps_autodial_job` (
  `userid` int(11) NOT NULL,
  `groupid` int(11) NOT NULL,
  `agent` char(50) NOT NULL,
  `number` char(50) DEFAULT '',
  `numberid` int(11) DEFAULT '-1',
  `state` enum('calling','answered') DEFAULT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `zswitch_ps_autodial_number`
--

DROP TABLE IF EXISTS `zswitch_ps_autodial_number`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zswitch_ps_autodial_number` (
  `id` int(11) NOT NULL,
  `number` char(50) NOT NULL,
  `accountid` int(11) DEFAULT '-1',
  `taskid` int(11) NOT NULL DEFAULT '-1',
  `status` enum('Waiting','Handling','Handled') DEFAULT 'Waiting',
  `result` enum('Talk','No answer','Busy','Empty number','Other','No call') DEFAULT NULL,
  `call_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `agent` char(50) DEFAULT '',
  `userid` int(11) DEFAULT '-1',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_create` int(11) NOT NULL DEFAULT '-1',
  `date_modify` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_modify` int(11) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id`),
  KEY `number` (`number`),
  KEY `taskid` (`taskid`),
  KEY `status` (`status`),
  KEY `result` (`result`),
  KEY `call_time` (`call_time`),
  KEY `userid` (`userid`),
  KEY `accountid` (`accountid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `zswitch_ps_autodial_number_seq`
--

DROP TABLE IF EXISTS `zswitch_ps_autodial_number_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zswitch_ps_autodial_number_seq` (
  `id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `zswitch_ps_autodial_tasks`
--

DROP TABLE IF EXISTS `zswitch_ps_autodial_tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zswitch_ps_autodial_tasks` (
  `id` int(11) NOT NULL,
  `name` char(255) NOT NULL,
  `state` enum('Stop','Runing') DEFAULT 'Stop',
  `groupid` int(11) NOT NULL DEFAULT '-1',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_create` int(11) NOT NULL DEFAULT '-1',
  `date_modify` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_modify` int(11) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id`),
  KEY `groupid` (`groupid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `zswitch_ps_autodial_tasks_seq`
--

DROP TABLE IF EXISTS `zswitch_ps_autodial_tasks_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zswitch_ps_autodial_tasks_seq` (
  `id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-07-28 14:57:41
