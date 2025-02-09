-- MySQL dump 10.11
--
-- Host: localhost    Database: pallo15694_osc
-- ------------------------------------------------------
-- Server version	5.0.77-log

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
-- Table structure for table `address_book`
--

DROP TABLE IF EXISTS `address_book`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `address_book` (
  `address_book_id` int(11) NOT NULL auto_increment,
  `customers_id` int(11) NOT NULL default '0',
  `entry_type` enum('company','private') default NULL,
  `entry_gender` char(1) NOT NULL,
  `entry_company` varchar(32) default NULL,
  `entry_company_tax_id` varchar(32) default NULL,
  `entry_cf` varchar(16) NOT NULL,
  `entry_company_cf` varchar(16) NOT NULL,
  `entry_piva` varchar(11) NOT NULL,
  `entry_firstname` varchar(32) NOT NULL,
  `entry_lastname` varchar(32) NOT NULL,
  `entry_street_address` varchar(64) NOT NULL,
  `entry_suburb` varchar(32) default NULL,
  `entry_postcode` varchar(10) NOT NULL,
  `entry_city` varchar(32) NOT NULL,
  `entry_state` varchar(32) default NULL,
  `entry_country_id` int(11) NOT NULL default '0',
  `entry_zone_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`address_book_id`),
  KEY `idx_address_book_customers_id` (`customers_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `address_book`
--

LOCK TABLES `address_book` WRITE;
/*!40000 ALTER TABLE `address_book` DISABLE KEYS */;
INSERT INTO `address_book` VALUES (1,1,'private','m','ACME Inc.',NULL,'','','','John','Doe','1 Way Street','','12345','NeverNever','',105,232),(13,2,'private','','','','','','','test','test','prova prova prova',NULL,'54100','massa','',105,232),(14,2,'private','','',NULL,'','','','test1','test1','test test test',NULL,'45100','mirteto','',105,232),(15,3,'private','','','','','','','Giulio','D\'Ambrosio','Via prova delle prove',NULL,'54100','Massa','',105,232),(16,4,'company','','PWS','','','RSCRCR69P17H501F','','Test','Test','Via dei test e delle prove',NULL,'54100','Massa','Massa-Carrara',105,0);
/*!40000 ALTER TABLE `address_book` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `address_format`
--

DROP TABLE IF EXISTS `address_format`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `address_format` (
  `address_format_id` int(11) NOT NULL auto_increment,
  `address_format` varchar(128) NOT NULL,
  `address_summary` varchar(48) NOT NULL,
  PRIMARY KEY  (`address_format_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `address_format`
--

LOCK TABLES `address_format` WRITE;
/*!40000 ALTER TABLE `address_format` DISABLE KEYS */;
INSERT INTO `address_format` VALUES (1,'$firstname $lastname$cr$streets$cr$city, $postcode$cr$statecomma$country','$city / $country'),(2,'$firstname $lastname$cr$streets$cr$city, $state    $postcode$cr$country','$city, $state / $country'),(3,'$firstname $lastname$cr$streets$cr$city$cr$postcode - $statecomma$country','$state / $country'),(4,'$firstname $lastname$cr$streets$cr$city ($postcode)$cr$country','$postcode / $country'),(5,'$firstname $lastname$cr$streets$cr$postcode $city$cr$country','$city / $country');
/*!40000 ALTER TABLE `address_format` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `am_attributes_to_templates`
--

DROP TABLE IF EXISTS `am_attributes_to_templates`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `am_attributes_to_templates` (
  `template_id` int(5) unsigned NOT NULL,
  `options_id` int(5) unsigned NOT NULL,
  `option_values_id` int(5) unsigned NOT NULL,
  `price_prefix` char(1) default '+',
  `options_values_price` decimal(15,4) default '0.0000',
  `products_options_sort_order` int(11) default '0',
  `weight_prefix` char(1) default '+',
  `options_values_weight` decimal(6,3) default '0.000',
  KEY `template_id` (`template_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `am_attributes_to_templates`
--

LOCK TABLES `am_attributes_to_templates` WRITE;
/*!40000 ALTER TABLE `am_attributes_to_templates` DISABLE KEYS */;
/*!40000 ALTER TABLE `am_attributes_to_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `am_templates`
--

DROP TABLE IF EXISTS `am_templates`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `am_templates` (
  `template_id` int(5) unsigned NOT NULL auto_increment,
  `template_name` varchar(255) NOT NULL,
  PRIMARY KEY  (`template_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `am_templates`
--

LOCK TABLES `am_templates` WRITE;
/*!40000 ALTER TABLE `am_templates` DISABLE KEYS */;
/*!40000 ALTER TABLE `am_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `banners` (
  `banners_id` int(11) NOT NULL auto_increment,
  `banners_title` varchar(64) NOT NULL,
  `banners_url` varchar(255) NOT NULL,
  `banners_image` varchar(64) NOT NULL,
  `banners_group` varchar(10) NOT NULL,
  `banners_html_text` text,
  `expires_impressions` int(7) default '0',
  `expires_date` datetime default NULL,
  `date_scheduled` datetime default NULL,
  `date_added` datetime NOT NULL default '0000-00-00 00:00:00',
  `date_status_change` datetime default NULL,
  `status` int(1) NOT NULL default '1',
  `banners_open_new_windows` tinyint(4) NOT NULL default '1',
  `banners_on_ssl` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`banners_id`),
  KEY `idx_banners_group` (`banners_group`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `banners`
--

LOCK TABLES `banners` WRITE;
/*!40000 ALTER TABLE `banners` DISABLE KEYS */;
INSERT INTO `banners` VALUES (1,'osCommerce','http://www.oscommerce.it','oscommerce.gif','468x50','',0,NULL,NULL,'2005-03-08 03:43:29',NULL,1,1,1),(2,'PayPal','','','paypal','<!-- PayPal Logo --><table border=\"0\" cellpadding=\"10\" cellspacing=\"0\" align=\"center\"><tr><td align=\"center\"></td></tr>\n<tr><td align=\"center\"><a href=\"#\" onclick=\"javascript:window.open(\'https://www.paypal.com/it/cgi-bin/webscr?cmd=xpt/cps/popup/OLCWhatIsPayPal-outside\',\'olcwhatispaypal\',\'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=400, height=350\');\"><img  src=\"https://www.paypal.com/it_IT/IT/i/logo/PayPal_mark_180x113.gif\" border=\"0\" alt=\"Marchio di accettazione\"></a></td></tr></table><!-- PayPal Logo -->',0,NULL,NULL,'2009-10-29 18:12:44',NULL,1,1,1),(3,'Trovaprezzi','','','trovaprezz','<p align=\"center\"><a href=\"http://www.trovaprezzi.it\" title=\"Presente su TrovaPrezzi\" target=\"_blank\"><img src=\"http://img.trovaprezzi.it/buttons/recommendedby/120x60_v1.gif\" style=\"border:0px\" alt=\"Presente su TrovaPrezzi\"></a>\n</p>',0,NULL,NULL,'2009-10-29 18:25:26',NULL,1,1,1),(4,'Kelkoo','','','kelkoo','<p align=\"center\"><a href=\"http://www.kelkoo.it\" title=\"Presente su Kelkoo\" target=\"_blank\"><img src=\"http://support.kelkoo.no/support/disp/logo.gif\" style=\"border:0px\" alt=\"Presente su Kelkoo\"></a>\n</p>',0,NULL,NULL,'2009-10-29 18:26:58',NULL,1,1,1),(5,'Banner Header','index.php','banner-iphone3g.jpg','header','',0,NULL,NULL,'2009-10-30 18:06:19',NULL,0,1,1);
/*!40000 ALTER TABLE `banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banners_history`
--

DROP TABLE IF EXISTS `banners_history`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `banners_history` (
  `banners_history_id` int(11) NOT NULL auto_increment,
  `banners_id` int(11) NOT NULL default '0',
  `banners_shown` int(5) NOT NULL default '0',
  `banners_clicked` int(5) NOT NULL default '0',
  `banners_history_date` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`banners_history_id`),
  KEY `idx_banners_history_banners_id` (`banners_id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `banners_history`
--

LOCK TABLES `banners_history` WRITE;
/*!40000 ALTER TABLE `banners_history` DISABLE KEYS */;
INSERT INTO `banners_history` VALUES (1,1,24,0,'2005-03-08 04:07:55'),(2,1,66,0,'2005-06-18 09:32:14'),(3,1,14,1,'2005-06-19 03:46:51'),(4,2,5,0,'2005-06-20 01:45:41'),(5,1,6,0,'2005-06-20 01:45:58'),(6,1,1,0,'2005-06-22 01:31:46'),(7,1,5,0,'2005-07-08 09:31:20'),(8,2,2,0,'2005-07-08 09:38:45'),(9,2,11,0,'2005-07-09 10:09:27'),(10,1,9,0,'2005-07-09 10:12:12'),(11,1,4,0,'2006-01-19 11:50:57'),(12,1,148,1,'2006-01-20 10:35:47'),(13,1,167,1,'2006-01-21 00:51:50'),(14,1,30,1,'2006-01-22 02:35:08'),(15,1,153,1,'2006-01-23 01:43:38'),(16,1,1,0,'2007-10-19 19:51:59'),(17,1,116,0,'2007-10-22 11:26:36'),(18,1,113,0,'2007-10-23 10:53:27'),(19,1,88,0,'2007-10-24 11:30:05'),(20,1,193,0,'2007-10-25 00:01:16'),(21,1,64,0,'2007-10-26 11:02:12'),(22,1,108,0,'2007-10-29 11:56:52'),(23,1,80,0,'2007-10-30 00:00:59');
/*!40000 ALTER TABLE `banners_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cache` (
  `cache_id` varchar(32) NOT NULL,
  `cache_language_id` tinyint(1) NOT NULL default '0',
  `cache_name` varchar(255) NOT NULL,
  `cache_data` mediumtext NOT NULL,
  `cache_global` tinyint(1) NOT NULL default '1',
  `cache_gzip` tinyint(1) NOT NULL default '1',
  `cache_method` varchar(20) NOT NULL default 'RETURN',
  `cache_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `cache_expires` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`cache_id`,`cache_language_id`),
  KEY `cache_id` (`cache_id`),
  KEY `cache_language_id` (`cache_language_id`),
  KEY `cache_global` (`cache_global`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('92ad15f7ce8e6f57860eca2222c23db8',4,'seo_urls_v2_pages','S0lNy8xL1VAPcHR3DY73c/R1jTdV11FQT87PK0ksKUlMzlTXtOZKwVRlBlJVUJRZlphciUOJOdigxJLEnPz0fN2ClDQc6iwgFuam6halpmcWlxQlFhXjstYSrjYxubAUqDixKBWHsYYGYCcmpifmpuaV5OsCvVScmp6XCFIOAA==',1,1,'EVAL','2011-09-20 12:53:42','2011-10-20 12:53:42'),('4404c1df54fdb1291c8dd9bb259f32a9',4,'seo_urls_v2_manufacturers','hc09D4IwEMbx3U/hVk1k8F3i1BjcYCAyk0t7hUZszXERP745d+v++z+PRecDLlSpq+aqL7emLuq20mXRrtVqrh7AFN9qeZ7Zn3Dzhd5QHKPjtN2KnYACUhruBLp/13tRQ+w8o+nTgwehBkIMaXcUN3okgjQ8Cew484GRwLB/YTrIJehxGpA5e4K5A1kpPg==',1,1,'EVAL','2011-09-20 12:53:42','2011-10-20 12:53:42'),('a93b9170a03ff54d81e95917742ea01b',4,'seo_urls_v2_categories','hZJNSwMxEIbv/gpvq2DAbP2oeCpSPKkgXjyFIZntDmwSSbIr9NebjdviFju95fA+eTPzxGBDDi+qp9XH+vnt/VO9rl7WSlZX51ULwXxDwOry8cz8G6vHWPRN4mOLMWYGI6wfCOPx+6S6+Vssom7RoNgEaCgfOfB2DiawX+ASccjdDLHeUfKBA+5ngIYYUUBvyHPQwwyyvo/MHAslr8f876IEbMk7Pl5MTfEmTwxRE7otHH9S7ijeJkh7i4b4kiJwl4eQ8qoEOLLALTj3FJsTl9pAXYfMgjNQLE6ACWDHBs1KlAcW0frATSOVPNBogrfCBBq4n1kruRzF7D67iGT77pSfTBX9e+o0UBf/eyCmAAk3VHz+AA==',1,1,'EVAL','2011-09-20 12:53:42','2011-10-20 12:53:42'),('ca34fbe5f9a075091ad59abf02c259a7',4,'seo_urls_v2_products','fZRLj9QwEITv/ApuAxItJdnlJU4IOPIQgvPKsXsSM44d2vZk5t/Tbh5iEZ17lV3tr9oOjz7io8Onzx/ffn3z5e7D6/fv7vrDk4eHxRRKF5iGrjs8fvXA/U84/C287Tq4GZZRVd+I2ltKOR0L+FgwBL+kmlH13DYP4RqMxQVjgZMPASmrhqfNMAbjEKjGiATOE9qSdjzPmqfMCG1mf1HPft5011TPTemDqnshGeqUIfijPtvLJqvRccbscdKFffePEpyhExQk8jzYVQ3SC8kjPwC4tEUYMaRNVwvO2ZCDzZcZzhgnNNHuBBOkActsAmxo1hT103+hdGATMRddKAiPZGLxVlcJNJsqmQnh5yu2QXWD0MsrotM1Qk40YKn6jBw28h7orHuhyOUhzJDTwm/h4wRmTLVwS3bgDEK1ITnvRBoEYd5MARsSB/peDTH5DKMpJejzDkKzRkJmU/idomkLpM4+CMttRmS5X3YOFpDOZ+vX0IY2ltBBMNHpazkI1OXe8nMLeJ/xOiaunJ5LQN93/v42AC9rSLRTpkGoz7hxSQusxp5avYPJSN/49r7vuovX75Y+/PmhfgA=',1,1,'EVAL','2011-09-20 12:53:42','2011-10-20 12:53:42');
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `categories` (
  `categories_id` int(11) NOT NULL auto_increment,
  `categories_image` varchar(64) default NULL,
  `parent_id` int(11) NOT NULL default '0',
  `sort_order` int(3) default NULL,
  `date_added` datetime default NULL,
  `last_modified` datetime default NULL,
  `categories_status` tinyint(1) default '1',
  `categories_status_pc` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`categories_id`),
  KEY `idx_categories_parent_id` (`parent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'category_hardware.gif',0,1,'2005-03-08 03:43:29','2005-07-09 10:11:55',1,1),(2,'category_software.gif',0,2,'2005-03-08 03:43:29','2005-07-09 10:11:57',1,1),(3,'category_dvd_movies.gif',0,3,'2005-03-08 03:43:29','2005-07-09 10:11:58',1,1),(4,'subcategory_graphic_cards.gif',1,0,'2005-03-08 03:43:29','2005-07-09 10:11:55',1,1),(5,'subcategory_printers.gif',1,0,'2005-03-08 03:43:29','2005-07-09 10:11:55',1,1),(6,'subcategory_monitors.gif',1,0,'2005-03-08 03:43:29','2005-07-09 10:11:55',1,1),(7,'subcategory_speakers.gif',1,0,'2005-03-08 03:43:29','2006-01-20 18:36:59',1,1),(8,'subcategory_keyboards.gif',1,0,'2005-03-08 03:43:29',NULL,0,1),(9,'subcategory_mice.gif',1,0,'2005-03-08 03:43:29','2005-07-09 10:11:55',1,1),(10,'subcategory_action.gif',3,0,'2005-03-08 03:43:29','2005-07-09 10:11:58',1,1),(11,'subcategory_science_fiction.gif',3,0,'2005-03-08 03:43:29','2005-07-09 10:11:58',1,1),(12,'subcategory_comedy.gif',3,0,'2005-03-08 03:43:29','2005-07-09 10:11:58',1,1),(13,'subcategory_cartoons.gif',3,0,'2005-03-08 03:43:29','2005-07-09 10:11:58',1,1),(14,'subcategory_thriller.gif',3,0,'2005-03-08 03:43:29','2005-07-09 10:11:58',1,1),(15,'subcategory_drama.gif',3,0,'2005-03-08 03:43:29','2005-07-09 10:11:58',1,1),(16,'subcategory_memory.gif',1,0,'2005-03-08 03:43:29','2006-01-22 03:05:43',1,1),(17,'subcategory_cdrom_drives.gif',1,0,'2005-03-08 03:43:29','2006-01-20 18:36:58',1,1),(18,'subcategory_simulation.gif',2,0,'2005-03-08 03:43:29','2005-07-09 10:11:57',1,1),(19,'subcategory_action_games.gif',2,0,'2005-03-08 03:43:29','2005-07-09 10:11:57',1,1),(20,'subcategory_strategy.gif',2,0,'2005-03-08 03:43:29','2005-07-09 10:11:57',1,1);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories_description`
--

DROP TABLE IF EXISTS `categories_description`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `categories_description` (
  `categories_id` int(11) NOT NULL default '0',
  `language_id` int(11) NOT NULL default '1',
  `categories_name` varchar(255) default NULL,
  `categories_seo_url` varchar(255) default NULL,
  PRIMARY KEY  (`categories_id`,`language_id`),
  KEY `idx_categories_name` (`categories_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `categories_description`
--

LOCK TABLES `categories_description` WRITE;
/*!40000 ALTER TABLE `categories_description` DISABLE KEYS */;
INSERT INTO `categories_description` VALUES (1,4,'Hardware',''),(1,5,'Hardware',''),(2,4,'Software',''),(2,5,'Software',''),(3,4,'DVD Movies',''),(3,5,'DVD Movies',''),(4,4,'Schede Grafiche',''),(4,5,'Schede Grafiche',''),(5,4,'Stampanti',''),(5,5,'Stampanti',''),(6,4,'Monitor',''),(6,5,'Monitor',''),(7,4,'Casse Audio',''),(7,5,'Casse Audio',''),(9,4,'Mouse',''),(9,5,'Mouse',''),(10,4,'Azione',''),(10,5,'Azione',''),(11,4,'Fantascienza',''),(11,5,'Fantascienza',''),(12,4,'Comedie',''),(12,5,'Comedie',''),(13,4,'Cartoni Animati',''),(13,5,'Cartoni Animati',''),(14,4,'Thriller',''),(14,5,'Thriller',''),(15,4,'Drammatici',''),(15,5,'Drammatici',''),(16,4,'Memorie',''),(16,5,'Ram Memory',''),(17,4,'CDROM Drives',''),(17,5,'CDROM Drives',''),(18,4,'Simulazione',''),(18,5,'Simulazione',''),(19,4,'Azione',''),(19,5,'Azione',''),(20,4,'Strategia',''),(20,5,'Strategia','');
/*!40000 ALTER TABLE `categories_description` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `configuration`
--

DROP TABLE IF EXISTS `configuration`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `configuration` (
  `configuration_id` int(11) NOT NULL auto_increment,
  `configuration_title` varchar(64) NOT NULL,
  `configuration_key` varchar(64) NOT NULL,
  `configuration_value` varchar(255) NOT NULL,
  `configuration_description` varchar(255) NOT NULL,
  `configuration_group_id` int(11) NOT NULL default '0',
  `sort_order` int(5) default NULL,
  `last_modified` datetime default NULL,
  `date_added` datetime NOT NULL default '0000-00-00 00:00:00',
  `use_function` varchar(255) default NULL,
  `set_function` varchar(255) default NULL,
  PRIMARY KEY  (`configuration_id`)
) ENGINE=MyISAM AUTO_INCREMENT=336 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `configuration`
--

LOCK TABLES `configuration` WRITE;
/*!40000 ALTER TABLE `configuration` DISABLE KEYS */;
INSERT INTO `configuration` VALUES (1,'Nome del Sito','STORE_NAME','OSCommerce Demo by PromoWebStudio.net','Il nome del mio sito',1,1,'2004-07-21 05:26:35','2004-04-17 14:30:19',NULL,NULL),(2,'Webmaster','STORE_OWNER','Staff PromoWebStudio.Net','Il nome del webmaster o dello staff',1,2,'2004-07-20 03:33:53','2004-04-17 14:30:19',NULL,NULL),(3,'Indirizzo E-Mail','STORE_OWNER_EMAIL_ADDRESS','info@promowebstudio.net','Indirizzo email del webmaster',1,3,'2004-07-20 03:34:05','2004-04-17 14:30:19',NULL,NULL),(4,'Email Da','EMAIL_FROM','staff PWS <info@promowebstudio.net>','L\'indirizzo usato nelle mail inviate dal sito',1,4,'2004-07-20 03:34:36','2004-04-17 14:30:19',NULL,NULL),(5,'Stato','STORE_COUNTRY','105','Lo Stato in cui &egrave; situato il negozio <br><br><b>Nota: ricordati di aggiornare anche la zona del negozio.</b>',1,6,'2004-04-17 14:42:53','2004-04-17 14:30:19','tep_get_country_name','tep_cfg_pull_down_country_list('),(6,'Zona','STORE_ZONE','260','La zona del negozio',1,7,'2004-07-20 03:38:00','2004-04-17 14:30:19','tep_cfg_get_zone_name','tep_cfg_pull_down_zone_list('),(7,'Ordinamento dei prodotti in arrivo','EXPECTED_PRODUCTS_SORT','desc','Ordinamento del box prodotti in arrivo',1,8,NULL,'2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'asc\', \'desc\'),'),(8,'Campo prodotti in arrivo','EXPECTED_PRODUCTS_FIELD','date_expected','Campo scelto per l\'ordinamento.',1,9,NULL,'2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'products_name\', \'date_expected\'),'),(9,'Scambia autmaticamente alla valuta della lingua','USE_DEFAULT_LANGUAGE_CURRENCY','false','Se imopstato modifica la valuta in base alla lingua scelta',1,10,NULL,'2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(10,'Invia mail di notifica per l\'ordine','SEND_EXTRA_ORDER_EMAILS_TO','info@oscommerce.it,info@promowebstudio.net','Invia mail di notifica per nuovi ordini agli indirizzi elencati utilizzando il formato: email@indirizzo1,email@indirizzo2',1,11,'2006-01-22 03:22:58','2004-04-17 14:30:19',NULL,NULL),(335,'Mostra il prezzo dei prodotti non in vendita diretta','PRODUCTS_ONLYSHOW_PRICE','false','Se abilitato (true) mostra i prezzi dei prodotti non in vendita diretta',8,30,NULL,'2011-09-20 12:53:56',NULL,'tep_cfg_select_option(array(\'true\', \'false\'), '),(12,'Mostra il Carrello dopo l\'inserimento di un prodotto','DISPLAY_CART','true','Mostra il carrello della spesa dopo aver inserito un prodotto (oppure torna indietro alla pagina precedente)',1,14,NULL,'2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(13,'Permette agli ospiti di inviare ad un amico','ALLOW_GUEST_TO_TELL_A_FRIEND','true','Permette anche ad utenti non registrati di inviare le segnalazioni su un prodotto',1,15,'2004-04-17 14:46:15','2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(14,'Operatore di ricerca di default','ADVANCED_SEARCH_DEFAULT_OPERATOR','and','Operatore di ricerca di default',1,17,NULL,'2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'and\', \'or\'),'),(15,'Indirizzo e Telefono del Negozio','STORE_NAME_ADDRESS','OSCommerce by PWS\nP.zza F. De Lucia, 20\nItaly\n199 - 444 542','Questo &egrave; l\'indirizzo il telefono e il nome usato nei documenti stampabili e mostrato on line',1,18,'2005-07-09 10:11:34','2004-04-17 14:30:19',NULL,'tep_cfg_textarea('),(16,'Mostra numero per categoria','SHOW_COUNTS','false','Conta ricorsivamente quanti articoli ci sono in ogni categoria',1,19,'2004-04-17 14:46:56','2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(17,'Cifre Decimali nelle Tasse','TAX_DECIMAL_PLACES','2','Fa il pad delle cifre decimali nelle tasse',1,20,'2005-06-18 09:24:54','2004-04-17 14:30:19',NULL,NULL),(18,'Mostra prezzi con tassa incusa','DISPLAY_PRICE_WITH_TAX','true','Mostra i prezzi IVa compresa(vero) oppure iva esclusa (falso)',1,21,'2004-04-25 09:32:00','2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(19,'Nome','ENTRY_FIRST_NAME_MIN_LENGTH','2','Lunghezza Minima per il Nome',2,1,NULL,'2004-04-17 14:30:19',NULL,NULL),(20,'Cognome','ENTRY_LAST_NAME_MIN_LENGTH','2','Lungheza Minima per il Cognome',2,2,NULL,'2004-04-17 14:30:19',NULL,NULL),(21,'Data di nascita','ENTRY_DOB_MIN_LENGTH','10','Lunghezza minima per la data di nascita',2,3,NULL,'2004-04-17 14:30:19',NULL,NULL),(22,'Indirizzo email','ENTRY_EMAIL_ADDRESS_MIN_LENGTH','6','Lunghezza minima per l\'indirizzo email',2,4,NULL,'2004-04-17 14:30:19',NULL,NULL),(23,'Indirizzo','ENTRY_STREET_ADDRESS_MIN_LENGTH','5','Lunghezza minima per l\'indirizzo',2,5,NULL,'2004-04-17 14:30:19',NULL,NULL),(24,'Societ&agrave;','ENTRY_COMPANY_MIN_LENGTH','2','Lunghezza minima per la societ&agrave;',2,6,NULL,'2004-04-17 14:30:19',NULL,NULL),(25,'CAP','ENTRY_POSTCODE_MIN_LENGTH','4','Lunghezza minima per il CAP',2,7,NULL,'2004-04-17 14:30:19',NULL,NULL),(26,'Citt&agrave;','ENTRY_CITY_MIN_LENGTH','3','Lunghezza minima per la citt&agrave;',2,8,NULL,'2004-04-17 14:30:19',NULL,NULL),(27,'Stato','ENTRY_STATE_MIN_LENGTH','2','Lunghezza minima per lo stato',2,9,NULL,'2004-04-17 14:30:19',NULL,NULL),(28,'Numero di telefono','ENTRY_TELEPHONE_MIN_LENGTH','3','Lunghezza minima per il numero di telefono',2,10,NULL,'2004-04-17 14:30:19',NULL,NULL),(29,'Password','ENTRY_PASSWORD_MIN_LENGTH','5','Lunghezza minima per password',2,11,NULL,'2004-04-17 14:30:19',NULL,NULL),(30,'Nome proprietario carta di credito','CC_OWNER_MIN_LENGTH','3','Lunghezza minima per proprietario della carta di credito',2,12,NULL,'2004-04-17 14:30:19',NULL,NULL),(31,'Numero carta di credito','CC_NUMBER_MIN_LENGTH','10','Lunghezza minima per il numero carta di credito',2,13,NULL,'2004-04-17 14:30:19',NULL,NULL),(32,'Testo Recensioni','REVIEW_TEXT_MIN_LENGTH','50','Lunghezza minima per le recenzioni',2,14,NULL,'2004-04-17 14:30:19',NULL,NULL),(33,'I pi&ugrave; venduti','MIN_DISPLAY_BESTSELLERS','1','Numero minimo di prodtti pi&ugrave; venduti',2,15,NULL,'2004-04-17 14:30:19',NULL,NULL),(34,'Acquistato anche','MIN_DISPLAY_ALSO_PURCHASED','1','Numero minimo di prodotti da mostrare nell\'area Chi ha comprato questo articolo compra anche',2,16,NULL,'2004-04-17 14:30:19',NULL,NULL),(35,'Record Rubrica','MAX_ADDRESS_BOOK_ENTRIES','5','Numero massimo di record che possono essere inseriti nella rubrica utente',3,1,NULL,'2004-04-17 14:30:19',NULL,NULL),(36,'Risultato della ricerca','MAX_DISPLAY_SEARCH_RESULTS','20','Numero di prodotti elencati per pagina',3,2,NULL,'2004-04-17 14:30:19',NULL,NULL),(37,'Links per pagina','MAX_DISPLAY_PAGE_LINKS','5','Numero di links usati per ogni pagina di rimando ad altre pagine nella ricerca',3,3,NULL,'2004-04-17 14:30:19',NULL,NULL),(38,'Prodotti in offerta','MAX_DISPLAY_SPECIAL_PRODUCTS','9','Numero massimo di prodotti da mostrare nel box offerte speciali',3,4,NULL,'2004-04-17 14:30:19',NULL,NULL),(39,'Modulo nuovi prodotti','MAX_DISPLAY_NEW_PRODUCTS','9','Numero massimo di prodotti da mostrare come novit&agrave;',3,5,NULL,'2004-04-17 14:30:19',NULL,NULL),(40,'Prodotti in arrivo','MAX_DISPLAY_UPCOMING_PRODUCTS','10','Numero massimo di prodotti in arrivo da mostrare',3,6,NULL,'2004-04-17 14:30:19',NULL,NULL),(41,'Elenco produttori','MAX_DISPLAY_MANUFACTURERS_IN_A_LIST','0','Utilizzato nel menu produttori-marche; quando il numero di produttori supera questo numero, viene mostrato un menu a tendina invece dell\'elenco',3,7,NULL,'2004-04-17 14:30:19',NULL,NULL),(42,'Grandezza del select produttori','MAX_MANUFACTURERS_LIST','1','Utilizzato nel box produttori; quando questo valore &egrave; \'1\' viene  mostrato il classico menu a tendina. Altrimenti, viene mostato un elenco con i produttori.',3,7,NULL,'2004-04-17 14:30:19',NULL,NULL),(43,'Lunghezza del nome produttore','MAX_DISPLAY_MANUFACTURER_NAME_LEN','15','Utilizzato nel box produttori; lunghezza massima del nome produttore da mostrare',3,8,NULL,'2004-04-17 14:30:19',NULL,NULL),(44,'Nuove recensioni','MAX_DISPLAY_NEW_REVIEWS','6','Numero massimo di recensioni da mostrare nel box',3,9,NULL,'2004-04-17 14:30:19',NULL,NULL),(45,'Selezione casuale di recensioni','MAX_RANDOM_SELECT_REVIEWS','10','Quanti record selezionare per mostrare la recensione di un prodotto a caso',3,10,NULL,'2004-04-17 14:30:19',NULL,NULL),(46,'Selezione casuale di recensioni nuovi prodotti','MAX_RANDOM_SELECT_NEW','10','Quanti record selezionare per mostrare la recensione di un nuovo prodotto a caso',3,11,NULL,'2004-04-17 14:30:19',NULL,NULL),(47,'Selezione di prodotti in offerta','MAX_RANDOM_SELECT_SPECIALS','10','Quanti record selezionare per mostrare la recensione di un nuovo prodotto in offerta a caso',3,12,NULL,'2004-04-17 14:30:19',NULL,NULL),(48,'Categorie da elencare per riga','MAX_DISPLAY_CATEGORIES_PER_ROW','3','Quante categorie mostrare per riga',3,13,NULL,'2004-04-17 14:30:19',NULL,NULL),(49,'Elenco nuovi prodotti','MAX_DISPLAY_PRODUCTS_NEW','10','Numero massimo di prodotti da mostrare nella pagina novit&agrave;',3,14,NULL,'2004-04-17 14:30:19',NULL,NULL),(50,'I pi&ugrave; venduti','MAX_DISPLAY_BESTSELLERS','10','Numero massimo di prodotti  pi&ugrave; venduti da mostrare',3,15,NULL,'2004-04-17 14:30:19',NULL,NULL),(51,'Ha acquistato anche','MAX_DISPLAY_ALSO_PURCHASED','6','Numero massimo di prodotti  da mostrare nel box  \'Chi ha comprato questo articolo compra anche\'',3,16,NULL,'2004-04-17 14:30:19',NULL,NULL),(52,'Ordini del cliente','MAX_DISPLAY_PRODUCTS_IN_ORDER_HISTORY_BOX','6','Numero massimo di prodotti  da mostrare nel box storico ordini del cliente',3,17,NULL,'2004-04-17 14:30:19',NULL,NULL),(53,'Storico Ordini','MAX_DISPLAY_ORDER_HISTORY','10','Numero massimo di ordini  da mostrare nel box storico ordini',3,18,NULL,'2004-04-17 14:30:19',NULL,NULL),(54,'Larghezza delle anteprime','SMALL_IMAGE_WIDTH','110','Larghezza in pixel delle anteprime',4,1,'2006-01-21 04:16:29','2004-04-17 14:30:19',NULL,NULL),(55,'Altezza delle anteprime','SMALL_IMAGE_HEIGHT','80','Altezza in pixel delle anteprime',4,2,'2006-01-21 04:16:37','2004-04-17 14:30:19',NULL,NULL),(56,'Larghezza delle intestazioni','HEADING_IMAGE_WIDTH','57','Larghezza in pixel delle immagini per le categorie',4,3,NULL,'2004-04-17 14:30:19',NULL,NULL),(57,'Altezza delle intestazioni','HEADING_IMAGE_HEIGHT','40','Altezza in pixel delle intestazioni',4,4,NULL,'2004-04-17 14:30:19',NULL,NULL),(58,'Larghezza delle immagini per sottocategorie','SUBCATEGORY_IMAGE_WIDTH','100','Larghezza in pixel delle immagini per sottocategorie',4,5,NULL,'2004-04-17 14:30:19',NULL,NULL),(59,'Altezza delle immagini per sottocategorie','SUBCATEGORY_IMAGE_HEIGHT','57','Altezza in pixel delle immagini per sottocategorie',4,6,NULL,'2004-04-17 14:30:19',NULL,NULL),(60,'Calcola le dimensioni delle immagini','CONFIG_CALCULATE_IMAGE_SIZE','true','Calcolare le dimensioni delle immagini?',4,7,'2006-01-21 00:46:10','2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(61,'Immagine obbligatoria','IMAGE_REQUIRED','true','Abilita a mostrare le immagini corrotte. Ottimo per lo sviluppo grafico.',4,8,NULL,'2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(62,'Sesso','ACCOUNT_GENDER','false','Richiedi il sesso nella registrazione utente',5,1,'2004-04-17 14:47:48','2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(63,'Data di nascita','ACCOUNT_DOB','false','Richiedi Data di nascita nella registrazione utente',5,2,'2004-04-17 14:47:55','2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(64,'Societ&agrave;','ACCOUNT_COMPANY','true','Richiedi Societ&agrave; nella registrazione utente',5,3,NULL,'2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(65,'Quartiere','ACCOUNT_SUBURB','false','Richiedi Quartiere nella registrazione utente',5,4,'2004-04-17 14:48:11','2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(66,'Stato','ACCOUNT_STATE','true','Richiedi Stato nella registrazione utente',5,5,NULL,'2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(67,'Moduli pagamento installati','MODULE_PAYMENT_INSTALLED','ccp.php;postepay.php','elenco dei moduli di pagamento separati da punto e virgola. Questo campo &egrave; aggiornato automaticamente. Non c\'&egrave; bisogno di editarlo. (Esempio: cc.php;cod.php;paypal.php)',6,0,'2007-10-19 17:41:10','2004-04-17 14:30:19',NULL,NULL),(68,'Moduli nel totale ordine installati','MODULE_ORDER_TOTAL_INSTALLED','ot_subtotal.php;ot_shipping.php;ot_tax.php;ot_total.php','Elenco dei moduli installati nel totale ordine. Questo campo &egrave; aggiornato automaticamente. Non c\'&egrave; bisogno di editarlo. (Esempio: ot_subtotal.php;ot_tax.php;ot_shipping.php;ot_total.php)',6,0,'2004-04-25 21:46:04','2004-04-17 14:30:19',NULL,NULL),(69,'Moduli spedizione installati','MODULE_SHIPPING_INSTALLED','bartolini.php;consegnainsede.php;flat.php;table.php','List of shipping module filenames separated by a semi-colon. This is automatically updated. No need to edit. (Example: ups.php;flat.php;item.php)',6,0,'2006-01-23 02:15:51','2004-04-17 14:30:19',NULL,NULL),(70,'Abilita modulo in contrassegno','MODULE_PAYMENT_COD_STATUS','True','Do you want to accept Cash On Delevery payments?',6,1,NULL,'2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'True\', \'False\'),'),(71,'Pagamento per zona','MODULE_PAYMENT_COD_ZONE','0','If a zone is selected, only enable this payment method for that zone.',6,2,NULL,'2004-04-17 14:30:19','tep_get_zone_class_title','tep_cfg_pull_down_zone_classes('),(72,'Ordinamento dei moduli di pagamento.','MODULE_PAYMENT_COD_SORT_ORDER','0','Ordinamento dei moduli di pagamento.',6,0,NULL,'2004-04-17 14:30:19',NULL,NULL),(73,'Modifica lo stato dell\'ordine','MODULE_PAYMENT_COD_ORDER_STATUS_ID','0','Modifica lo stato dell\'ordine con questo modulo di pagamento con questo valore',6,0,NULL,'2004-04-17 14:30:19','tep_get_order_status_name','tep_cfg_pull_down_order_statuses('),(74,'Valuta di default','DEFAULT_CURRENCY','EUR','Valuta di default',6,0,NULL,'2004-04-17 14:30:19',NULL,NULL),(75,'Lingua di default','DEFAULT_LANGUAGE','it','Lingua di default',6,0,NULL,'2004-04-17 14:30:19',NULL,NULL),(76,'Stato di default per i nuovi ordini','DEFAULT_ORDERS_STATUS_ID','1','Quando un nuovo ordine &egrave; inoltrato, questo stato sar&agrave; assegnato automaticamente.',6,0,NULL,'2004-04-17 14:30:19',NULL,NULL),(77,'Vuoi mostrare il costo di spedizione?','MODULE_ORDER_TOTAL_SHIPPING_STATUS','true','Vuoi mostrare il costo di spedizione?',6,1,NULL,'2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(78,'Ordinamento','MODULE_ORDER_TOTAL_SHIPPING_SORT_ORDER','2','Ordinamento dei moduli di spedizione',6,2,NULL,'2004-04-17 14:30:19',NULL,NULL),(79,'Permetti spedizioni gratis','MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING','false','Vuoi inserire spedizioni gratuite?',6,3,NULL,'2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(80,'Spedizioni gratis per ordini sopra','MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER','50','Consenti spedizioni gratuite per ordini che superano',6,4,NULL,'2004-04-17 14:30:19','currencies->format',NULL),(81,'Spedizioni gratuite per destinazione','MODULE_ORDER_TOTAL_SHIPPING_DESTINATION','national','Consenti spedizioni gratis per destinazione.',6,5,NULL,'2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'national\', \'international\', \'both\'),'),(82,'Mostra il sub totale','MODULE_ORDER_TOTAL_SUBTOTAL_STATUS','true','Mostra il sub totale ordine',6,1,NULL,'2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(83,'Ordinamento','MODULE_ORDER_TOTAL_SUBTOTAL_SORT_ORDER','1','Ordinamento per il sub totale.',6,2,NULL,'2004-04-17 14:30:19',NULL,NULL),(84,'Mostra tasse','MODULE_ORDER_TOTAL_TAX_STATUS','true','Vuoi mostrare anche il totale delle imposte per l\'ordine?',6,1,NULL,'2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(85,'Ordinamento','MODULE_ORDER_TOTAL_TAX_SORT_ORDER','3','Ordinamento delle tasse.',6,2,NULL,'2004-04-17 14:30:19',NULL,NULL),(86,'Mostra totale','MODULE_ORDER_TOTAL_TOTAL_STATUS','true','Mostrare il totale dell\'ordine?',6,1,NULL,'2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(87,'Ordinamento','MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER','4','Ordinamento del totale ordine.',6,2,NULL,'2004-04-17 14:30:19',NULL,NULL),(88,'Paese di origine','SHIPPING_ORIGIN_COUNTRY','105','Seleziona il paese di origine utilizzato per il calcolo delle spese di spedizione.',7,1,'2004-04-17 14:48:43','2004-04-17 14:30:19','tep_get_country_name','tep_cfg_pull_down_country_list('),(89,'Codice Postale','SHIPPING_ORIGIN_ZIP','00100','Inserisci il CAP del negozio usato nelle calcolo delle spese di spedizione.',7,2,'2004-04-17 14:48:58','2004-04-17 14:30:19',NULL,NULL),(90,'Inserisci il peso massimo per singola spedizione','SHIPPING_MAX_WEIGHT','30','Inserisci il peso massimo per singola spedizione.',7,3,'2004-04-27 13:24:24','2004-04-17 14:30:19',NULL,NULL),(91,'Peso della tara.','SHIPPING_BOX_WEIGHT','0.01','Qual &egrave; il peso medio della tara negli imballaggi?',7,4,'2004-04-27 13:23:51','2004-04-17 14:30:19',NULL,NULL),(92,'Percentuale di aumento per pacchi voluminosi.','SHIPPING_BOX_PADDING','1','Esempio, per 10% inserire 10',7,5,'2004-04-27 13:24:53','2004-04-17 14:30:19',NULL,NULL),(93,'Mostra immagine prodotto','PRODUCT_LIST_IMAGE','1','Mostra immagine prodotto',8,1,NULL,'2004-04-17 14:30:19',NULL,NULL),(94,'Mostra Produttore','PRODUCT_LIST_MANUFACTURER','0','Mostra Produttore',8,2,NULL,'2004-04-17 14:30:19',NULL,NULL),(95,'Mostra Modello prodotto','PRODUCT_LIST_MODEL','0','Mostra Modello prodotto',8,3,NULL,'2004-04-17 14:30:19',NULL,NULL),(96,'Mostra Nome prodotto','PRODUCT_LIST_NAME','2','Mostra Nome prodotto',8,4,NULL,'2004-04-17 14:30:19',NULL,NULL),(97,'Mostra Prezzo','PRODUCT_LIST_PRICE','2','Mostra Prezzo',8,5,'2004-04-23 13:08:52','2004-04-17 14:30:19',NULL,NULL),(98,'Mostra Quantit&agrave;','PRODUCT_LIST_QUANTITY','0','Mostra Quantit&agrave;',8,6,NULL,'2004-04-17 14:30:19',NULL,NULL),(99,'Mostra Peso','PRODUCT_LIST_WEIGHT','0','Mostra Peso',8,7,NULL,'2004-04-17 14:30:19',NULL,NULL),(100,'Mostra colonna ordina ora','PRODUCT_LIST_BUY_NOW','4','Mostra colonna ordina ora',8,8,NULL,'2004-04-17 14:30:19',NULL,NULL),(101,'Mostra Filtro Produttore (0=No; 1=Menu, 2=Elenco loghi)','PRODUCT_LIST_FILTER','1','Mostra Filtro Produttore nelle sottocategorie (0=disabilita; 1=abilita il menu a tendina, 2=abilita elenco dei loghi)',8,9,NULL,'2004-04-17 14:30:19',NULL,NULL),(102,'Posizione della barra di navigazione (1-alto, 2-basso, 3-entramb','PREV_NEXT_BAR_LOCATION','2','Posizione della barra di navigazione (1-alto, 2-basso, 3-entrambi)',8,10,NULL,'2004-04-17 14:30:19',NULL,NULL),(103,'Controlla lo stock di magazzino','STOCK_CHECK','true','Controlla lo stock di magazzino',9,1,NULL,'2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(104,'Sottrai stock','STOCK_LIMITED','true','Sottrai allo stock al momento dell\'ordine',9,2,NULL,'2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(105,'Permetti di completare l\'ordine','STOCK_ALLOW_CHECKOUT','true','Permetti all\'utente di completare l\'ordine anche se lo stock &egrave; insufficiente',9,3,NULL,'2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(106,'Marca i prodotti fuori stock','STOCK_MARK_PRODUCT_OUT_OF_STOCK','Coming soon / In arrivo','Mostra qualcosa a schermo per indicare che il prodotto &egrave; in arrivo o fuori stock',9,4,'2004-04-27 10:37:14','2004-04-17 14:30:19',NULL,NULL),(107,'Livello di riordino','STOCK_REORDER_LEVEL','5','Definisci il livello di riordino per il magazzino',9,5,NULL,'2004-04-17 14:30:19',NULL,NULL),(108,'Memorizza il tempo di parsing per pagina','STORE_PAGE_PARSE_TIME','false','Memorizza il tempo di parsing per pagina',10,1,NULL,'2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(109,'Percorso del log','STORE_PAGE_PARSE_TIME_LOG','/var/log/www/tep/page_parse_time.log','Direcrtory e file per il log del tempo di parsing per pagina',10,2,NULL,'2004-04-17 14:30:19',NULL,NULL),(110,'Formato data del log','STORE_PARSE_DATE_TIME_FORMAT','%d/%m/%Y %H:%M:%S','Formato data del log',10,3,NULL,'2004-04-17 14:30:19',NULL,NULL),(111,'Mostra il tempo di parsing per la pagina','DISPLAY_PAGE_PARSE_TIME','false','Mostra il tempo di parsing per la pagina (Memorizza il parsing deve essere abilitato)',10,4,'2004-04-17 14:53:13','2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(112,'Memoriza le query','STORE_DB_TRANSACTIONS','false','Memoriza le query nel log del parsing (solo PHP4)',10,5,NULL,'2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(113,'Usa Cache','USE_CACHE','true','Usa Cache',11,1,'2004-04-17 14:53:34','2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(114,'Metodo di inotro delle email','EMAIL_TRANSPORT','sendmail','Definisce se usare una connessione locale al sendmail oppure una connessione SMTP via TCP/IP. I server Windows e MacOS devono usare SMTP.',12,1,NULL,'2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'sendmail\', \'smtp\'),'),(115,'Ritorno a capo email','EMAIL_LINEFEED','LF','Definisce la sequenza di caratteri per separare le intestazioni delle mail.',12,2,NULL,'2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'LF\', \'CRLF\'),'),(116,'Usa MIME HTML quando invia email','EMAIL_USE_HTML','true','Invia le email in formato HTML',12,3,NULL,'2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(117,'Verifica indirizzo email con i DNS','ENTRY_EMAIL_ADDRESS_CHECK','false','Verifica indirizzo email con i DNS',12,4,NULL,'2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(118,'Invia email','SEND_EMAILS','true','Invia email',12,5,'2004-07-21 08:57:29','2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(119,'Abilita i download','DOWNLOAD_ENABLED','true','Abilita la funziona download per i prodotti.',13,1,'2004-04-17 14:55:00','2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(120,'Download con redirect','DOWNLOAD_BY_REDIRECT','false','Usa il redirect del browser per i download. Disabilitare sui sistemi non Unix.',13,2,NULL,'2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(121,'Scadenza download','DOWNLOAD_MAX_DAYS','0','Inserisci un numero di giorni dopo il quale il downolad viene disabilitato. 0 per non disabilitare mai.',13,3,'2004-04-17 14:55:39','2004-04-17 14:30:19',NULL,''),(122,'Numero massimo di download','DOWNLOAD_MAX_COUNT','10','Numero massimo di download. 0 significa nessun download autorizzato.',13,4,'2004-04-17 14:55:16','2004-04-17 14:30:19',NULL,''),(123,'Abilita compressione GZip','GZIP_COMPRESSION','false','Abilita compressione GZip',14,1,NULL,'2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(124,'Livello di compressione','GZIP_LEVEL','5','Livello di compressione  0-9 (0 = minimo, 9 = massimo).',14,2,NULL,'2004-04-17 14:30:19',NULL,NULL),(125,'Forza utilizzo di cookie','SESSION_FORCE_COOKIE_USE','True','Forza utilizzo di cookie.',15,2,NULL,'2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'True\', \'False\'),'),(126,'Controlla SSL Sessiono ID','SESSION_CHECK_SSL_SESSION_ID','False','controlla  SSL_SESSION_ID su ogni pagina richiesta con protocollo HTTPS.',15,3,NULL,'2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'True\', \'False\'),'),(127,'Controlla User agent','SESSION_CHECK_USER_AGENT','False','Controlla l\'user agent per il browser su ogni pagina richiesta.',15,4,NULL,'2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'True\', \'False\'),'),(128,'Controlla indirizzo IP','SESSION_CHECK_IP_ADDRESS','False','Controlla l\'IP su ogni pagina richiesta.',15,5,NULL,'2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'True\', \'False\'),'),(129,'Previeni le sessioni per gli spider','SESSION_BLOCK_SPIDERS','False','Impedisci che gli spider conosciuti abilitino una sessione.',15,6,NULL,'2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'True\', \'False\'),'),(130,'Ricrea sessione','SESSION_RECREATE','True','Ricrea una sessione quando il cliente si logga o crea un nuovo account (PHP >=4.1).',15,7,'2004-07-20 11:34:10','2004-04-17 14:30:19',NULL,'tep_cfg_select_option(array(\'True\', \'False\'),'),(131,'Abilita il modulo PayPal','MODULE_PAYMENT_PAYPAL_STATUS','True','Accetti pagamenti con PayPal?',6,3,NULL,'2004-04-23 19:56:04',NULL,'tep_cfg_select_option(array(\'True\', \'False\'),'),(132,'Indirizzo email','MODULE_PAYMENT_PAYPAL_ID','sales@miosito.com','Indirizzo email da utilizzare per PayPal',6,4,NULL,'2004-04-23 19:56:04',NULL,NULL),(133,'Valuta per la transazione','MODULE_PAYMENT_PAYPAL_CURRENCY','Only EUR','Valuta per la transazione con PayPal',6,6,NULL,'2004-04-23 19:56:04',NULL,'tep_cfg_select_option(array(\'Selected Currency\',\'Only USD\',\'Only CAD\',\'Only EUR\',\'Only GBP\',\'Only JPY\'),'),(134,'Ordinamento.','MODULE_PAYMENT_PAYPAL_SORT_ORDER','1','Ordinamento modulo PayPal.',6,0,NULL,'2004-04-23 19:56:04',NULL,NULL),(135,'Zona PayPal','MODULE_PAYMENT_PAYPAL_ZONE','0','Se una zona &egrave; selezionata, abilita il pagamento con PayPal solo per questa zona.',6,2,NULL,'2004-04-23 19:56:04','tep_get_zone_class_title','tep_cfg_pull_down_zone_classes('),(136,'Modifica lo stato dell\'ordine','MODULE_PAYMENT_PAYPAL_ORDER_STATUS_ID','0','Imposta lo stato dell\'ordine con questo valore se il pagamento &egrave; fatto con PayPal',6,0,NULL,'2004-04-23 19:56:04','tep_get_order_status_name','tep_cfg_pull_down_order_statuses('),(137,'Abilita tabella spedizioni','MODULE_SHIPPING_TABLE_STATUS','True','Vuoi usare la tabella costi per le spedizioni?',6,0,NULL,'2004-04-23 20:13:25',NULL,'tep_cfg_select_option(array(\'True\', \'False\'),'),(138,'Tabella spedizioni','MODULE_SHIPPING_TABLE_COST','0:8.00,3.00:8.00,30.00:12.00','Il costo della spedizione &egrave; basato sul totale ordine o sul peso totale degli articoli. Esempio: 25:8.50,50:5.50,etc.. Fino a 25 carica 8.50, da li fino a 50 carica 5.50, etc',6,0,NULL,'2004-04-23 20:13:25',NULL,NULL),(139,'Tabella peso-costo','MODULE_SHIPPING_TABLE_MODE','weight','Il costo di spedizione &egrave; basato sul totale ordine o peso totale degli articoli ordinati.',6,0,NULL,'2004-04-23 20:13:25',NULL,'tep_cfg_select_option(array(\'weight\', \'price\'),'),(140,'Costo per la lavorazione','MODULE_SHIPPING_TABLE_HANDLING','5.00','Costo per la lavorazione in questo metodo di spedizione.',6,0,NULL,'2004-04-23 20:13:25',NULL,NULL),(141,'Classi per le tasse','MODULE_SHIPPING_TABLE_TAX_CLASS','0','Utlizza questa classe per le tasse da includere nella tabella spedizioni.',6,0,NULL,'2004-04-23 20:13:25','tep_get_tax_class_title','tep_cfg_pull_down_tax_classes('),(142,'Zona spedizione','MODULE_SHIPPING_TABLE_ZONE','0','Se una zona &egrave; selezionata, abilita il pagamento solo per questa zona.',6,0,NULL,'2004-04-23 20:13:25','tep_get_zone_class_title','tep_cfg_pull_down_zone_classes('),(143,'Ordinamento','MODULE_SHIPPING_TABLE_SORT_ORDER','0','Ordinamento tabella spedizioni.',6,0,NULL,'2004-04-23 20:13:25',NULL,NULL),(144,'Abilita spese fisse','MODULE_SHIPPING_FLAT_STATUS','True','Vuoi offrire spese fisse per le spedizioni?',6,0,NULL,'2004-04-28 11:07:19',NULL,'tep_cfg_select_option(array(\'True\', \'False\'),'),(145,'Costi di Spedizione Fissi','MODULE_SHIPPING_FLAT_COST','12.00','Il costo di spedizione &egrave; fisso con questo modulo di spedizione.',6,0,NULL,'2004-04-28 11:07:19',NULL,NULL),(146,'Classi per le tasse','MODULE_SHIPPING_FLAT_TAX_CLASS','0','Usa la seguente classe per le tasse sulla spedizione.',6,0,NULL,'2004-04-28 11:07:19','tep_get_tax_class_title','tep_cfg_pull_down_tax_classes('),(147,'Spedizioni per zone','MODULE_SHIPPING_FLAT_ZONE','0','Se una zona &egrave; selezonata, abilita questa spedizione solo per questa zona.',6,0,NULL,'2004-04-28 11:07:19','tep_get_zone_class_title','tep_cfg_pull_down_zone_classes('),(148,'Ordinamento','MODULE_SHIPPING_FLAT_SORT_ORDER','0','Ordinamento tabella costo forfait per la spedizione',6,0,NULL,'2004-04-28 11:07:19',NULL,NULL),(149,'Partita IVA','ACCOUNT_PIVA','true','Decidi se mostrare il campo Partita Iva',5,1,NULL,'2003-06-01 17:41:12',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(150,'Partita IVA Richiesta','ACCOUNT_PIVA_REQ','false','Decidi se il campo Partita Iva deve essere inserito obbligatoriamente',5,1,NULL,'2003-06-01 17:41:12',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(151,'Codice Fiscale','ACCOUNT_CF','true','Decidi se mostrare il campo Codice Fiscale',5,1,NULL,'2003-06-01 17:41:12',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(152,'Codice Fiscale Richiesto','ACCOUNT_CF_REQ','false','Decidi se il campo Codice Fiscale deve essere inserito obbligatoriamente',5,1,'2005-06-18 09:25:31','2003-06-01 17:41:12',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(153,'PRODUCT DESCRIPTIONS use WYSIWYG HTMLAREA?','HTML_AREA_WYSIWYG_DISABLE','Enable','Enable/Disable WYSIWYG box',112,3,'2005-06-20 01:42:59','2005-06-18 09:53:30',NULL,'tep_cfg_select_option(array(\'Enable\', \'Disable\'),'),(154,'Product Description Basic/Advanced Version?','HTML_AREA_WYSIWYG_BASIC_PD','Basic','Basic Features FASTER<br>Advanced Features SLOWER',112,13,'2005-06-18 09:53:30','2005-06-18 09:53:30',NULL,'tep_cfg_select_option(array(\'Basic\', \'Advanced\'),'),(155,'Product Description Layout Width','HTML_AREA_WYSIWYG_WIDTH','505','How WIDE should the HTMLAREA be in pixels (default: 505)',112,18,'2005-06-18 09:53:30','2005-06-18 09:53:30',NULL,''),(156,'Product Description Layout Height','HTML_AREA_WYSIWYG_HEIGHT','240','How HIGH should the HTMLAREA be in pixels (default: 240)',112,22,'2005-06-18 09:53:30','2005-06-18 09:53:30',NULL,''),(157,'CUSTOMER EMAILS use WYSIWYG HTMLAREA?','HTML_AREA_WYSIWYG_DISABLE_EMAIL','Enable','Use WYSIWYG Area in Email Customers',112,23,'2005-06-18 09:53:30','2005-06-18 09:53:30',NULL,'tep_cfg_select_option(array(\'Enable\', \'Disable\'),'),(158,'Customer Email Basic/Advanced Version?','HTML_AREA_WYSIWYG_BASIC_EMAIL','Basic','Basic Features FASTER<br>Advanced Features SLOWER',112,24,'2005-06-18 09:53:30','2005-06-18 09:53:30',NULL,'tep_cfg_select_option(array(\'Basic\', \'Advanced\'),'),(159,'Customer Email Layout Width','EMAIL_AREA_WYSIWYG_WIDTH','505','How WIDE should the HTMLAREA be in pixels (default: 505)',112,28,'2005-06-18 09:53:30','2005-06-18 09:53:30',NULL,''),(160,'Customer Email Layout Height','EMAIL_AREA_WYSIWYG_HEIGHT','140','How HIGH should the HTMLAREA be in pixels (default: 140)',112,32,'2005-06-18 09:53:30','2005-06-18 09:53:30',NULL,''),(161,'NEWSLETTER EMAILS use WYSIWYG HTMLAREA?','HTML_AREA_WYSIWYG_DISABLE_NEWSLETTER','Enable','Use WYSIWYG Area in Email Newsletter',112,33,'2005-06-18 09:53:30','2005-06-18 09:53:30',NULL,'tep_cfg_select_option(array(\'Enable\', \'Disable\'),'),(162,'Newsletter Email Basic/Advanced Version?','HTML_AREA_WYSIWYG_BASIC_NEWSLETTER','Basic','Basic Features FASTER<br>Advanced Features SLOWER',112,35,'2005-06-18 09:53:30','2005-06-18 09:53:30',NULL,'tep_cfg_select_option(array(\'Basic\', \'Advanced\'),'),(163,'Newsletter Email Layout Width','NEWSLETTER_EMAIL_WYSIWYG_WIDTH','505','How WIDE should the HTMLAREA be in pixels (default: 505)',112,38,'2005-06-18 09:53:30','2005-06-18 09:53:30',NULL,''),(164,'Newsletter Email Layout Height','NEWSLETTER_EMAIL_WYSIWYG_HEIGHT','140','How HIGH should the HTMLAREA be in pixels (default: 140)',112,42,'2005-06-18 09:53:30','2005-06-18 09:53:30',NULL,''),(165,'DEFINE MAINPAGE use WYSIWYG HTMLAREA?','HTML_AREA_WYSIWYG_DISABLE_DEFINE','Enable','Use WYSIWYG Area in Define Mainpage',112,43,'2005-06-18 09:53:30','2005-06-18 09:53:30',NULL,'tep_cfg_select_option(array(\'Enable\', \'Disable\'),'),(166,'Define Mainpage Basic/Advanced Version?','HTML_AREA_WYSIWYG_BASIC_DEFINE','Basic','Basic Features FASTER<br>Advanced Features SLOWER',112,44,'2005-06-18 09:53:30','2005-06-18 09:53:30',NULL,'tep_cfg_select_option(array(\'Basic\', \'Advanced\'),'),(167,'Define Mainpage Layout Width','DEFINE_MAINPAGE_WYSIWYG_WIDTH','605','How WIDE should the HTMLAREA be in pixels (default: 505)',112,45,'2005-06-18 09:53:30','2005-06-18 09:53:30',NULL,''),(168,'Define Mainpage Layout Height','DEFINE_MAINPAGE_WYSIWYG_HEIGHT','300','How HIGH should the HTMLAREA be in pixels (default: 140)',112,46,'2005-06-18 09:53:30','2005-06-18 09:53:30',NULL,''),(169,'GLOBAL - User Interface Font Type','HTML_AREA_WYSIWYG_FONT_TYPE','Times New Roman','User Interface Font Type<br>(not saved to product description)',112,48,'2005-06-18 09:53:30','2005-06-18 09:53:30',NULL,'tep_cfg_select_option(array(\'Arial\', \'Courier New\', \'Georgia\', \'Impact\', \'Tahoma\', \'Times New Roman\', \'Verdana\', \'Wingdings\'),'),(170,'GLOBAL - User Interface Font Size','HTML_AREA_WYSIWYG_FONT_SIZE','12','User Interface Font Size (not saved to product description)<p><b>10 Equals 10 pt',112,53,'2005-06-18 09:53:30','2005-06-18 09:53:30',NULL,'tep_cfg_select_option(array(\\\'8\\\', \\\'10\\\', \\\'12\\\', \\\'14\\\', \\\'18\\\', \\\'24\\\', \\\'36\\\'),'),(171,'GLOBAL - User Interface Font Colour','HTML_AREA_WYSIWYG_FONT_COLOUR','Black','White, Black, C0C0C0, Red, FFFFFF, Yellow, Pink, Blue, Gray, 000000, ect..<br>basically any colour or HTML colour code!<br>(not saved to product description)',112,58,'2005-06-18 09:53:30','2005-06-18 09:53:30',NULL,''),(172,'GLOBAL - User Interface Background Colour','HTML_AREA_WYSIWYG_BG_COLOUR','White','White, Black, C0C0C0, Red, FFFFFF, Yellow, Pink, Blue, Gray, 000000, ect..<br>basically any colour or html colour code!<br>(not saved to product description)',112,63,'2005-06-18 09:53:30','2005-06-18 09:53:30',NULL,''),(173,'GLOBAL - ALLOW DEBUG MODE?','HTML_AREA_WYSIWYG_DEBUG','0','Moniter Live-html, It updates as you type in a 2nd field above it.<p>Disable Debug = 0<br>Enable Debug = 1<br>Default = 0 OFF',112,68,'2005-06-18 09:53:30','2005-06-18 09:53:30',NULL,'tep_cfg_select_option(array(\'0\', \'1\'),'),(174,'PAGE MANAGER use WYSIWYG HTMLAREA?','HTML_AREA_WYSIWYG_DISABLE_PAGEMANAGER','Enable','Use WYSIWYG Area in Define Mainpage',112,43,'2005-06-18 11:56:32','2005-06-18 10:02:51',NULL,'tep_cfg_select_option(array(\'Enable\', \'Disable\'),'),(175,'Page Manager Basic/Advanced Version?','HTML_AREA_WYSIWYG_BASIC_PAGEMANAGER','Advanced','Basic Features FASTER<br>Advanced Features SLOWER',112,44,'2005-06-18 11:56:47','2005-06-18 10:02:51',NULL,'tep_cfg_select_option(array(\'Basic\', \'Advanced\'),'),(176,'Page Manager Layout Width','PAGEMANAGER_WYSIWYG_WIDTH','605','How WIDE should the HTMLAREA be in pixels (default: 505)',112,45,'2005-06-18 10:02:51','2005-06-18 10:02:51',NULL,''),(177,'Page Manager Layout Height','PAGEMANAGER_WYSIWYG_HEIGHT','300','How HIGH should the HTMLAREA be in pixels (default: 140)',112,46,'2005-06-18 10:02:51','2005-06-18 10:02:51',NULL,''),(178,'Abilita il pagamento tramite Bonifico Bancario','MODULE_PAYMENT_BONIFICO_STATUS','True','Vuoi accettare pagamenti tramite Bonifico Bancario?',6,1,NULL,'2006-01-20 11:30:52',NULL,'tep_cfg_select_option(array(\'True\', \'False\'),'),(179,'Intestatario','MODULE_PAYMENT_BONIFICO_INTESTATARIO','','Intestario del conto',6,3,NULL,'2006-01-20 11:30:52',NULL,NULL),(180,'Banca','MODULE_PAYMENT_BONIFICO_BANCA','','Banca',6,4,NULL,'2006-01-20 11:30:52',NULL,NULL),(181,'CAB','MODULE_PAYMENT_BONIFICO_CAB','','CAB della banca',6,5,NULL,'2006-01-20 11:30:52',NULL,NULL),(182,'ABI','MODULE_PAYMENT_BONIFICO_ABI','','ABI della banca',6,6,NULL,'2006-01-20 11:30:52',NULL,NULL),(183,'CIN','MODULE_PAYMENT_BONIFICO_CIN','','CIN',6,7,NULL,'2006-01-20 11:30:52',NULL,NULL),(184,'CC','MODULE_PAYMENT_BONIFICO_CC','','numero di Conto Corrente',6,8,NULL,'2006-01-20 11:30:52',NULL,NULL),(185,'IBAN','MODULE_PAYMENT_BONIFICO_IBAN','','codice IBAN',6,9,NULL,'2006-01-20 11:30:52',NULL,NULL),(186,'SWIFT','MODULE_PAYMENT_BONIFICO_SWIFT','','codice SWIFT',6,10,NULL,'2006-01-20 11:30:52',NULL,NULL),(187,'Payment Zone','MODULE_PAYMENT_BONIFICO_ZONE','0','If a zone is selected, only enable this payment method for that zone.',6,2,NULL,'2006-01-20 11:30:52','tep_get_zone_class_title','tep_cfg_pull_down_zone_classes('),(188,'Set Order Status','MODULE_PAYMENT_BONIFICO_ORDER_STATUS_ID','0','Set the status of orders made with this payment module to this value',6,0,NULL,'2006-01-20 11:30:52','tep_get_order_status_name','tep_cfg_pull_down_order_statuses('),(189,'Sort order of display.','MODULE_PAYMENT_BONIFICO_SORT_ORDER','2','Sort order of display. Lowest is displayed first.',6,0,NULL,'2006-01-20 11:30:52',NULL,NULL),(190,'Abilita il pagamento tramite Conto Corrente Postale','MODULE_PAYMENT_CCP_STATUS','True','Vuoi accettare pagamenti tramite Conto Corrente Postale?',6,1,NULL,'2006-01-20 11:31:16',NULL,'tep_cfg_select_option(array(\'True\', \'False\'),'),(191,'Intestatario','MODULE_PAYMENT_CCP_INTESTATARIO','','Intestario del conto (Cognome Nome/Societ&agrave;)',6,3,NULL,'2006-01-20 11:31:16',NULL,NULL),(192,'Indirizzo','MODULE_PAYMENT_CCP_INDIRIZZO','','Indirizzo intestatario del conto',6,4,NULL,'2006-01-20 11:31:16',NULL,NULL),(193,'CC','MODULE_PAYMENT_CCP_CC','','numero di Conto Corrente Postale',6,5,NULL,'2006-01-20 11:31:16',NULL,NULL),(194,'Zona','MODULE_PAYMENT_CCP_ZONE','0','Se &egrave; selezionata una zona, il pagamento viene abilitato solo per questa zona.',6,2,NULL,'2006-01-20 11:31:16','tep_get_zone_class_title','tep_cfg_pull_down_zone_classes('),(195,'Status Ordine','MODULE_PAYMENT_CCP_ORDER_STATUS_ID','0','Imposta lo stato dell\'ordine a questo valore',6,0,NULL,'2006-01-20 11:31:16','tep_get_order_status_name','tep_cfg_pull_down_order_statuses('),(196,'Ordine di visualizzazione.','MODULE_PAYMENT_CCP_SORT_ORDER','3','Ordine di visualizzazione. I numeri pi&ugrave; bassi sono mostrati prima.',6,0,NULL,'2006-01-20 11:31:16',NULL,NULL),(197,'Abilita il pagamento tramite Ricarica PostePay','MODULE_PAYMENT_POSTEPAY_STATUS','True','Vuoi accettare pagamenti tramite Ricarica PostePay?',6,1,NULL,'2006-01-20 11:31:24',NULL,'tep_cfg_select_option(array(\'True\', \'False\'),'),(198,'Intestatario Carta PostePay','MODULE_PAYMENT_POSTEPAY_INTESTATARIO','','Intestario della Carta PostePay',6,3,NULL,'2006-01-20 11:31:24',NULL,NULL),(199,'Numero Carta PostePay','MODULE_PAYMENT_POSTEPAY_NUMERO_CARTA','','Numero della carta ricaricabile PostePay',6,7,NULL,'2006-01-20 11:31:24',NULL,NULL),(200,'Massimo ammontare accettato.','MODULE_PAYMENT_POSTEPAY_MAX_AMOUNT_ALLOWED','99999','Valore massimo accettato, in Euro, per questo modulo.',6,0,NULL,'2006-01-20 11:31:24',NULL,NULL),(201,'Zona abilitata','MODULE_PAYMENT_POSTEPAY_ZONE','0','Se una zona &egrave; abilitata, solo da questa &egrave; possibile utilizzare questo metodo di pagamento.',6,2,NULL,'2006-01-20 11:31:24','tep_get_zone_class_title','tep_cfg_pull_down_zone_classes('),(202,'Stato Ordine','MODULE_PAYMENT_POSTEPAY_ORDER_STATUS_ID','0','Imposta lo stato di un ordine dopo aver utilizzato questo pagamento',6,0,NULL,'2006-01-20 11:31:24','tep_get_order_status_name','tep_cfg_pull_down_order_statuses('),(203,'Ordine di visualizzazione.','MODULE_PAYMENT_POSTEPAY_SORT_ORDER','4','Ordine di visualizzazione. Pi&ugrave; il valore &egrave; basso, prima viene visualizzato.',6,0,NULL,'2006-01-20 11:31:24',NULL,NULL),(204,'Enable Consegna in Sede Shipping','MODULE_SHIPPING_CONSEGNAINSEDE_STATUS','True','Do you want to offer consegnainsede rate shipping?',6,0,NULL,'2006-01-20 11:31:40',NULL,'tep_cfg_select_option(array(\'True\', \'False\'),'),(205,'Shipping Cost','MODULE_SHIPPING_CONSEGNAINSEDE_COST','0.00','The shipping cost for all orders using this shipping method.',6,0,NULL,'2006-01-20 11:31:40',NULL,NULL),(206,'Strada del sito di consegna','MODULE_SHIPPING_CONSEGNAINSEDE_ADDR_VIA','','Specificare la via e il numero civico del sito di consegna.',6,3,NULL,'2006-01-20 11:31:40',NULL,NULL),(207,'Citt&agrave; del sito di consegna','MODULE_SHIPPING_CONSEGNAINSEDE_ADDR_CITTA','','Specificare la citt&agrave; e la provincia del sito di consegna.',6,4,NULL,'2006-01-20 11:31:40',NULL,NULL),(208,'CAP del sito di consegna','MODULE_SHIPPING_CONSEGNAINSEDE_ADDR_CAP','','Specificare il CAP del sito di consegna.',6,5,NULL,'2006-01-20 11:31:40',NULL,NULL),(209,'Tax Class','MODULE_SHIPPING_CONSEGNAINSEDE_TAX_CLASS','0','Use the following tax class on the shipping fee.',6,0,NULL,'2006-01-20 11:31:40','tep_get_tax_class_title','tep_cfg_pull_down_tax_classes('),(210,'Shipping Zone','MODULE_SHIPPING_CONSEGNAINSEDE_ZONE','0','If a zone is selected, only enable this shipping method for that zone.',6,0,NULL,'2006-01-20 11:31:40','tep_get_zone_class_title','tep_cfg_pull_down_zone_classes('),(211,'Sort Order','MODULE_SHIPPING_CONSEGNAINSEDE_SORT_ORDER','0','Sort order of display.',6,0,NULL,'2006-01-20 11:31:40',NULL,NULL),(212,'Privacy','ACCOUNT_PRIVACY','true','Richiedi accettazione delle condizioni sulla privacy',5,1,NULL,'2005-01-01 00:00:00',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(213,'Mostra Modello.','DISPLAY_MODEL','true','Abilita/Disabilita la visualizzazione del modello',300,1,'2003-06-04 05:04:11','2003-06-04 04:18:06',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(214,'Modifica il Modello.','MODIFY_MODEL','false','Abilita/Disabilita la modifica del modello',300,2,'2006-01-22 03:34:18','2003-06-04 04:25:57',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(215,'Modifica il nome Prodotto.','MODIFY_NAME','false','Abilita/Disabilita la modifica del nome',300,3,'2003-06-04 05:04:01','2003-06-04 04:30:31',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(216,'Modifica lo status del Prodotto.','DISPLAY_STATUT','true','Abilita/Disabilita lo status',300,4,'2003-06-04 05:07:11','2003-06-04 05:00:58',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(217,'Modifica il peso del Prodotto.','DISPLAY_WEIGHT','true','Abilita/Disabilita il peso',300,5,'2003-06-04 05:06:44','2003-06-04 04:33:16',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(218,'Modifica la quantit&agrave;.','DISPLAY_QUANTITY','true','Abilita/Disabilita la quantit&agrave;',300,6,'2003-06-04 05:06:48','2003-06-04 04:34:34',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(219,'Modifica l\'immagine.','DISPLAY_IMAGE','false','Abilita/Disabilita l\'immagine',300,7,'2003-06-04 05:06:52','2003-06-04 04:36:57',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(220,'Modifica lo status del Ricarico Prezzo.','DISPLAY_PRICE_COMMISSION','true','Abilita/Disabilita la presenza del campo ricarico',300,8,NULL,'2010-09-08 15:54:49','','tep_cfg_select_option(array(\'true\', \'false\'),'),(221,'Modifica il produttore.','MODIFY_MANUFACTURER','false','Abilita/Disabilita il produttore',300,8,'2003-06-04 05:06:57','2003-06-04 04:37:40',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(222,'Modifica la tassa.','MODIFY_TAX','false','Abilita/Disabilita la tassa/imposta',300,9,'2003-06-04 05:06:40','2003-06-04 04:31:53',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(223,'Mostra prezzo con tassa inclusa se mouse over.','DISPLAY_TVA_OVER','true','Abilita/Disabilita la visualizzazione del prezzo iva inclusa quando il mouse passa sopra un prodotto',300,10,'2003-06-04 05:07:02','2003-06-04 04:38:45',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(224,'Mostra prezzo con tassa inclusa se input.','DISPLAY_TVA_UP','true','Abilita/Disabilita la visualizzazione del prezzo iva inclusa quando stai modificanto il prezzo',300,11,'2003-06-04 05:07:06','2003-06-04 04:40:12',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(225,'Mostra il link alla pagina del prodotto.','DISPLAY_PREVIEW','false','Abilita/Disabilita la visualizzazione del link verso la pagina di informazione del prodotto',300,12,'2003-06-04 05:19:13','2003-06-04 05:15:50',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(226,'Mostra il link verso il form di modifica del prodotto.','DISPLAY_EDIT','true','Abilita/Disabilita la visualizzazione del link al form di modifica del prodotto',300,13,'2003-06-04 05:19:08','2003-06-04 05:17:05',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(227,'Mostra il produttore.','DISPLAY_MANUFACTURER','false','Vuoi mostrare solo il produttore?',300,7,'2003-06-04 05:19:08','2003-06-04 05:17:05',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(228,'Mostra la tassa.','DISPLAY_TAX','true','Vuoi mostrare solo l\'imposta ?',300,8,'2003-06-04 05:19:08','2003-06-04 05:17:05',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(229,'Abilita/Disabilita la visualizz. delle offerte.','DISPLAY_ANGEBOT','true','Abilita/Disabilita la visualizz. delle offerte',300,15,'2006-01-22 03:28:15','2003-06-04 05:17:05',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(230,'Abilita/Disabilita l\'ordinamento dei prodotti.','DISPLAY_SORT','true','Abilita/Disabilita l\'ordinamento dei prodotti',300,16,'2006-01-22 03:28:06','2003-06-04 05:17:05',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(231,'Abilita Bartolini','MODULE_SHIPPING_BARTOLINI_STATUS','True','Vuoi abilitare il modulo Bartolini?',6,0,NULL,'2006-01-23 02:15:50',NULL,'tep_cfg_select_option(array(\'True\', \'False\'),'),(232,'Tassa','MODULE_SHIPPING_BARTOLINI_TAX_CLASS','0','Usa la seguente tassa per i costi di spedizionie con Bartolini.',6,0,NULL,'2006-01-23 02:15:50','tep_get_tax_class_title','tep_cfg_pull_down_tax_classes('),(233,'Ordine','MODULE_SHIPPING_BARTOLINI_SORT_ORDER','0','Ordine di visualizzazione.',6,0,NULL,'2006-01-23 02:15:50',NULL,NULL),(234,'Zona 1 Paesi','MODULE_SHIPPING_BARTOLINI_COUNTRIES_1','IT','Lista separata da virgole, dei paesi espressi con i codici ISO (2 caratteri) 1.',6,0,NULL,'2006-01-23 02:15:50',NULL,NULL),(235,'Zona 1 Tabella tariffe','MODULE_SHIPPING_BARTOLINI_COST_1','10:12.00,20:17.00,50:26.00,100:35.00,200:65.00,300:100.00','Tariffe per la zona 1 basate su una gruppo di pesi per prezzi. Es: 3:8.50,7:10.50,... Pesi inferiori o uguali a 3kg costeranno 8.50 eur. Pesi compresi dra 3 e 7kg costeranno 10.50 e cos&igrave; via.',6,0,NULL,'2006-01-23 02:15:50',NULL,NULL),(236,'Zona 1 Costo di imballaggio','MODULE_SHIPPING_BARTOLINI_HANDLING_1','0','Costo di imballaggio per questa zona',6,0,NULL,'2006-01-23 02:15:50',NULL,NULL),(237,'Product Info Attribute Display Plugin','PRODINFO_ATTRIBUTE_PLUGIN','multiple_dropdowns','The plugin used for displaying attributes on the product information page.',888001,1,NULL,'2006-11-28 17:09:39',NULL,'tep_cfg_pull_down_class_files(\'pad_\','),(238,'Show Out of Stock Attributes','PRODINFO_ATTRIBUTE_SHOW_OUT_OF_STOCK','True','Controls the display of out of stock attributes.',888001,10,NULL,'2006-11-28 17:09:39',NULL,'tep_cfg_select_option(array(\'True\', \'False\'),'),(239,'Mark Out of Stock Attributes','PRODINFO_ATTRIBUTE_MARK_OUT_OF_STOCK','Right','Controls how out of stock attributes are marked as out of stock.',888001,20,NULL,'2006-11-28 17:09:39',NULL,'tep_cfg_select_option(array(\'None\', \'Right\', \'Left\'),'),(240,'Display Out of Stock Message Line','PRODINFO_ATTRIBUTE_OUT_OF_STOCK_MSGLINE','True','Controls the display of a message line indicating an out of stock attributes is selected.',888001,30,NULL,'2006-11-28 17:09:39',NULL,'tep_cfg_select_option(array(\'True\', \'False\'),'),(241,'Prevent Adding Out of Stock to Cart','PRODINFO_ATTRIBUTE_NO_ADD_OUT_OF_STOCK','True','Prevents adding an out of stock attribute combination to the cart.',888001,40,NULL,'2006-11-28 17:09:39',NULL,'tep_cfg_select_option(array(\'True\', \'False\'),'),(242,'EasyPopulate','EP_CURRENT_VERSION','2.76g-MS2','Versione di EasyPopulate',17,0,'2008-03-28 13:04:31','2008-03-28 12:56:25',NULL,'dont_set('),(243,'Mostra le impostazioni','EP_SHOW_EP_SETTINGS','1','Se impostato su \'1\' mostra le impostazioni nella pagina principale di EasyPopulate',17,1,NULL,'2008-03-28 12:56:25',NULL,'tep_cfg_select_option(array(1,0),'),(244,'Directory di Esportazione','EP_TEMP_DIRECTORY','/var/www/vhosts/palloncini.modulioscommerce.com/httpdocs/temp/','Inserire il percorso completo della directory dove salvare i files esportati',17,2,NULL,'2008-03-28 12:56:25',NULL,NULL),(245,'Suddivisione Files Esportati','EP_SPLIT_MAX_RECORDS','300','Inserire la lunghezza di ogni file di esportazione suddiviso (numero di righe=prodotti)',17,3,NULL,'2008-03-28 12:56:25',NULL,NULL),(246,'Immagine Produttore [default]','EP_DEFAULT_IMAGE_MANUFACTURER','','Inserire il path relativo alla directory images per il produttore, in caso non esista l\'immagine',17,4,NULL,'2008-03-28 12:56:25',NULL,NULL),(247,'Immagine Prodotto [default]','EP_DEFAULT_IMAGE_PRODUCT','','Inserire il path relativo alla directory images per il prodotto, in caso non esista l\'immagine',17,5,NULL,'2008-03-28 12:56:25',NULL,NULL),(248,'Immagine Categoria [default]','EP_DEFAULT_IMAGE_CATEGORY','','Inserire il path relativo alla directory images per la categoria, in caso non esista l\'immagine',17,6,NULL,'2008-03-28 12:56:25',NULL,NULL),(249,'Saltare Prodotti Esauriti','EP_INACTIVATE_ZERO_QUANTITIES','0','Se impostato su \'1\', i prodotti con quantit&agrave; pari o inferiore a 0 saranno considerati disattivati',17,7,NULL,'2008-03-28 12:56:25',NULL,'tep_cfg_select_option(array(1,0),'),(250,'Prezzi Tasse Incluse','EP_PRICE_WITH_TAX','1','Se impostato su \'1\', i prezzi verranno gestiti come comprensivi delle tasse, sia in importazione che in esportazione',17,8,'2008-03-28 13:05:30','2008-03-28 12:56:25',NULL,'tep_cfg_select_option(array(1,0),'),(251,'Decimali nei Prezzi','EP_PRECISION','2','Impostare il numero di decimali dopo la virgola da calcolare nei prezzi durante l\'inserimento nel database',17,9,NULL,'2008-03-28 12:56:25',NULL,NULL),(252,'Massimo numero di livelli Categorie','EP_MAX_CATEGORIES','7','Impostare il massimo numero di livelli di categorie nel percorso dei prodotti',17,10,NULL,'2008-03-28 12:56:25',NULL,NULL),(253,'Campo v_status: valore per i prodotti attivi','EP_TEXT_ACTIVE','Active','Impostare il valore da inserire nella colonna v_status per i prodotti attivi',17,11,NULL,'2008-03-28 12:56:25',NULL,NULL),(254,'Campo v_status: valore per i prodotti disattivati','EP_TEXT_INACTIVE','Inactive','Impostare il valore da inserire nella colonna v_status per i prodotti disattivati',17,12,NULL,'2008-03-28 12:56:25',NULL,NULL),(255,'Campo v_status: valore per i prodotti da eliminare','EP_DELETE_IT','Delete','Impostare il valore da inserire nella colonna v_status per i prodotti da eliminare',17,13,NULL,'2008-03-28 12:56:25',NULL,NULL),(256,'Froogle: valuta di default','EP_FROOGLE_CURRENCY','EUR','Selezionare la valuata da utilizzare per le esportazioni per Froogle',17,14,'2008-03-28 13:05:43','2008-03-28 12:56:25',NULL,'tep_cfg_select_option(array_keys($currencies->currencies),'),(257,'Mostra Disponibilit&agrave;','PRODUCT_LIST_AVAILABILITY','3','Mostra la disponibilit&agrave; del prodotto',8,5,NULL,'2007-10-19 16:53:46',NULL,NULL),(258,'Semaforo Verde','PRODUCT_LIST_AVAILABILITY_GREEN','5','Mostra il semaforo verde per una quantit&agrave; uguale o superiore a...',8,11,NULL,'2007-10-19 16:53:46',NULL,NULL),(259,'Semaforo Giallo','PRODUCT_LIST_AVAILABILITY_YELLOW','1','Mostra il semaforo giallo per una quantit&agrave; uguale o superiore a...',8,12,NULL,'2007-10-19 16:53:46',NULL,NULL),(260,'Semaforo Rosso','PRODUCT_LIST_AVAILABILITY_RED','0','Mostra il semaforo rosso per una quantit&agrave; uguale o superiore a...',8,13,NULL,'2007-10-19 16:53:46',NULL,NULL),(261,'Semaforo con testo','PRODUCT_LIST_AVAILABILITY_TEXT','false','Mostra la spiegazione accanto al semaforo della disponibilit&agrave;',8,14,NULL,'2007-10-19 16:53:46',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(262,'Codice Fiscale Azienda','ACCOUNT_COMPANY_CF','true','Decidi se mostrare il campo Codice Fiscale dell\'azienda',5,1,NULL,'2007-10-19 16:53:46',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(263,'C.F. Azienda Richiesto','ACCOUNT_COMPANY_CF_REQ','true','Decidi se il campo Codice Fiscale dell\'azienda deve essere inserito obbligatoriamente',5,1,NULL,'2007-10-19 16:53:46',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(264,'Enable SEO URLs?','SEO_ENABLED','true','Enable the SEO URLs?  This is a global setting and will turn them off completely.',888002,0,'2007-10-19 19:51:57','2007-10-19 19:51:57',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(265,'Add cPath to product URLs?','SEO_ADD_CPATH_TO_PRODUCT_URLS','false','This setting will append the cPath to the end of product URLs (i.e. - some-product-p-1.html?cPath=xx).',888002,1,'2007-10-19 19:51:57','2007-10-19 19:51:57',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(266,'Add category parent to begining of URLs?','SEO_ADD_CAT_PARENT','true','This setting will add the category parent name to the beginning of the category URLs (i.e. - parent-category-c-1.html).',888002,2,'2007-10-19 19:51:57','2007-10-19 19:51:57',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(267,'Filter Short Words','SEO_URLS_FILTER_SHORT_WORDS','3','This setting will filter words less than or equal to the value from the URL.',888002,3,'2007-10-19 19:51:57','2007-10-19 19:51:57',NULL,NULL),(268,'Output W3C valid URLs (parameter string)?','SEO_URLS_USE_W3C_VALID','true','This setting will output W3C valid URLs.',888002,4,'2007-10-19 19:51:57','2007-10-19 19:51:57',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(269,'Enable SEO cache to save queries?','USE_SEO_CACHE_GLOBAL','true','This is a global setting and will turn off caching completely.',888002,5,'2007-10-19 19:51:57','2007-10-19 19:51:57',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(270,'Enable product cache?','USE_SEO_CACHE_PRODUCTS','true','This will turn off caching for the products.',888002,6,'2007-10-19 19:51:57','2007-10-19 19:51:57',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(271,'Enable categories cache?','USE_SEO_CACHE_CATEGORIES','true','This will turn off caching for the categories.',888002,7,'2007-10-19 19:51:57','2007-10-19 19:51:57',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(272,'Enable manufacturers cache?','USE_SEO_CACHE_MANUFACTURERS','true','This will turn off caching for the manufacturers.',888002,8,'2007-10-19 19:51:57','2007-10-19 19:51:57',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(273,'Enable articles cache?','USE_SEO_CACHE_ARTICLES','true','This will turn off caching for the articles.',888002,9,'2007-10-19 19:51:57','2007-10-19 19:51:57',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(274,'Enable topics cache?','USE_SEO_CACHE_TOPICS','true','This will turn off caching for the article topics.',888002,10,'2007-10-19 19:51:57','2007-10-19 19:51:57',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(275,'Enable information cache?','USE_SEO_CACHE_INFO_PAGES','true','This will turn off caching for the information pages.',888002,11,'2007-10-19 19:51:57','2007-10-19 19:51:57',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(276,'Enable automatic redirects?','USE_SEO_REDIRECT','true','This will activate the automatic redirect code and send 301 headers for old to new URLs.',888002,12,'2007-10-19 19:51:57','2007-10-19 19:51:57',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(277,'Choose URL Rewrite Type','SEO_REWRITE_TYPE','Rewrite','Choose which SEO URL format to use.',888002,13,'2007-10-19 19:51:57','2007-10-19 19:51:57',NULL,'tep_cfg_select_option(array(\'Rewrite\'),'),(278,'Enter special character conversions','SEO_CHAR_CONVERT_SET','','This setting will convert characters.<br><br>The format <b>MUST</b> be in the form: <b>char=>conv,char2=>conv2</b>',888002,14,'2007-10-19 19:51:57','2007-10-19 19:51:57',NULL,NULL),(279,'Remove all non-alphanumeric characters?','SEO_REMOVE_ALL_SPEC_CHARS','false','This will remove all non-letters and non-numbers.  This should be handy to remove all special characters with 1 setting.',888002,15,'2007-10-19 19:51:57','2007-10-19 19:51:57',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(280,'Reset SEO URLs Cache','SEO_URLS_CACHE_RESET','false','This will reset the cache data for SEO',888002,16,'2007-10-26 16:48:34','2007-10-19 19:51:57','tep_reset_cache_data_seo_urls','tep_cfg_select_option(array(\'reset\', \'false\'),'),(281,'Numero Colonne Vetrina','SHOPWINDOW_NUM_COLUMNS','3','Numero di colonne che appaiono nella vetrina',8,50,NULL,'2007-10-23 16:47:47',NULL,NULL),(282,'Numero Prodotti in Vetrina','SHOPWINDOW_MAX_PRODUCTS','9','Numero massimo di prodotti che appaiono in vetrina',8,51,NULL,'2007-10-23 16:47:47',NULL,NULL),(283,'Modifica la presenza in vetrina del prodotto.','DISPLAY_SHOPWINDOW','true','Abilita/Disabilita la presenza in vetrina',300,4,NULL,'2007-10-23 16:47:47',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(284,'Mostra Legenda Disponibilit&agrave;','PRODUCT_LIST_AVAILABILITY_LEGEND','true','Mostra la legenda di spiegazione dei semaforini della disponibilit&agrave; del prodotto',8,15,'2007-10-25 17:28:06','2007-10-25 17:24:17',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(285,'Vetrina: Abilitazione','SHOPWINDOW_ENABLED','true','Abilita la visualizzazione della vetrina',8,50,NULL,'2007-10-26 10:51:11',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(286,'Vetrina: Max Larghezza Immagini Prodotto','SHOPWINDOW_PRODUCT_IMAGE_WIDTH','200','Larghezza massima delle immagini dei prodotti in vetrina',8,54,NULL,'2007-10-26 10:51:11',NULL,NULL),(287,'Vetrina: Max Altezza Immagini Prodotto','SHOPWINDOW_PRODUCT_IMAGE_HEIGHT','200','Altezza massima delle immagini dei prodotti in vetrina',8,55,NULL,'2007-10-26 10:51:11',NULL,NULL),(288,'Vetrina: Tipo Vetrina','SHOPWINDOW_SKIN','FLASH','Selezionare il tipo di vetrina da visualizzare',8,51,NULL,'2007-10-26 10:57:10',NULL,'tep_cfg_select_option(array(\'HTML\', \'FLASH\'),'),(289,'Cache Directory','DIR_FS_CACHE','/var/www/vhosts/testgsc.it/httpdocs/tmp/','The directory where the cached files are saved',11,2,NULL,'2010-09-08 15:55:22',NULL,NULL),(290,'Stato sistema PWS','MODULE_PWS_STATUS','true','Stato del sistema PWS',555,1,NULL,'0000-00-00 00:00:00',NULL,'tep_cfg_select_option(array(\'true\', \'false\'), '),(291,'Versione del sistema PWS','MODULE_PWS_ENGINE_VERSION','0.47','Versione del sistema PWS',555,2,NULL,'0000-00-00 00:00:00',NULL,'dont_set('),(292,'Stato del debugging','MODULE_PWS_DEBUG','false','Abilitare la comparsa di messaggi di debug, utili allo sviluppo del sistema',555,3,NULL,'0000-00-00 00:00:00',NULL,'tep_cfg_select_option(array(\'true\', \'false\'), '),(293,'Icona per immagine non selezionata','PWS_HTML_NO_IMAGE_LOCATION','icons/no_picture.jpg','Inserire il percorso relativo alla base della directory catalog, che indica immagine non selezionata',6,1,NULL,'2010-09-08 15:58:45',NULL,NULL),(294,'Editor HTML','HTML_EDITOR_CHOICE','TinyMCE','Selezionare l\'editor HTML da utilizzare:<br/>HTML Area - compatibile solo con I.Explorer<br/>TinyMCE - compatibile con tutti i browsers',112,1,NULL,'2010-09-08 15:58:45',NULL,'tep_cfg_select_option(array(\'HTML_AREA\', \'TinyMCE\'),'),(295,'Picker Immagini','IMAGE_PICKER_CHOICE','PWS Picker','Selezionare il picker immagini da utilizzare:<br/>HTML Area - compatibile solo con I.Explorer<br/>PWS Picker - compatibile con tutti i browsers<br>Upload Standard - Upload originale Oscommerce',112,2,'2010-09-08 15:59:06','2010-09-08 15:58:45',NULL,'tep_cfg_select_option(array(\'HTML_AREA\', \'PWS Picker\', \'Upload Standard\'),'),(296,'Logo Negozio - Italiano','STORE_LOGO_IT','oscommerce.gif','Impostare il logo da utilizzare per la versione del sito in:<br/><b>Italiano</b>.',1,22,NULL,'2010-09-08 15:58:45',NULL,'$GLOBALS[\'pws_html\']->setStoreLogo(\'it\', '),(297,'Logo Negozio - english','STORE_LOGO_EN','oscommerce.gif','Impostare il logo da utilizzare per la versione del sito in:<br/><b>english</b>.',1,23,NULL,'2010-09-08 15:58:45',NULL,'$GLOBALS[\'pws_html\']->setStoreLogo(\'en\', '),(298,'SMTP Server','SMTP_MAIL_SERVER','','server per invio email tramite smtp, per es. smtp.gmail.com',12,6,NULL,'2009-09-28 12:54:39',NULL,NULL),(299,'SMTP Port Number','SMTP_PORT_NUMBER','','Porta del server SMTP, in genere 25. Per SSL 465 o 587',12,7,NULL,'2009-09-28 12:54:39',NULL,NULL),(300,'SMTP SENDMAIL FROM','SMTP_SENDMAIL_FROM','','Indirizzo email che compare nel mittente, dovrebbe essere lo stesso impostato sulla configurazione del Mio Negozio',12,8,NULL,'2009-09-28 12:54:39',NULL,NULL),(301,'SMTP FROM EMAIL NAME','SMTP_FROMEMAIL_NAME','','Nome che compare nel mittente, es. Staff Nome Sito oppure Web Master',12,9,NULL,'2009-09-28 12:54:39',NULL,NULL),(302,'SMTP use SSL','SMTP_SECURE','','Usa connessioni SSL',12,10,NULL,'2009-09-28 12:54:39',NULL,'tep_cfg_select_option(array(\'True\', \'False\'),'),(303,'SMTP SENDMAIL USERNAME','SMTP_SENDMAIL_USERNAME','','Nome utente per autenticazione sul server SMTP',12,11,NULL,'2009-09-28 12:54:39',NULL,NULL),(304,'SMTP SENDMAIL PASSWORD','SMTP_SENDMAIL_PASSWORD','','Password per autenticazione sul server SMTP',12,12,NULL,'2009-09-28 12:54:39',NULL,NULL),(305,'Mostra solo prezzi scontati per il gruppo','SHOW_GROUP_NET_PRICES','false','Se abilitato, il gruppo vede solo i prezzi riservati, anzichï¿½ visualizzare: prezzo di Listino-> Sconto -> Prezzo Riservato',1,19,NULL,'2010-09-08 15:59:06',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(306,'Mostra i prodotti in home e nelle categorie in ordine casuale','RANDOMIZE','off','Se abilitato (on), i prodotti verranno mostrati in ordine casuale anzichï¿½ per data di arrivo.',1,19,NULL,'2010-09-08 15:59:06',NULL,'tep_cfg_select_option(array(\'on\', \'off\'),'),(307,'Abilita la modalitï¿½ TURBO','TURBO','off','Se abilitato (on), si attiva l\'ottimizzazione dell\'utilizzo delle risorse php e mysql velocizzando il caricamento delle pagine .',1,19,NULL,'2010-09-08 15:59:06',NULL,'tep_cfg_select_option(array(\'on\', \'off\'),'),(308,'Codice Cliente (solo per form lato admin)','ACCOUNT_CUSTOMER_CODE','false','Decidi se mostrare il campo Codice Cliente nella sezione admin',5,1,NULL,'2010-09-08 15:59:06',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(309,'Codice Cliente Richiesto','ACCOUNT_CUSTOMER_CODE_REQ','false','Decidi se il campo Codice Cliente deve essere inserito obbligatoriamente',5,1,NULL,'2010-09-08 15:59:06',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(310,'Mostra quantitï¿½ disponibile nella scheda prodotto','PRODUCT_INFO_QUANTITY','false','Mostra la quantitï¿½ disponibile all\'interno della scheda prodotto',8,16,NULL,'2010-09-08 15:59:06',NULL,'tep_cfg_select_option(array(\'true\', \'false\'), '),(311,'Mostra disponibilitï¿½ grafica nella scheda prodotto','PRODUCT_INFO_AVAILABILITY','true','Mostra la disponibilitï¿½ grafica all\'interno della scheda prodotto',8,17,NULL,'2010-09-08 15:59:06',NULL,'tep_cfg_select_option(array(\'true\', \'false\'), '),(312,'Mostra campo Modello nella scheda prodotto','PRODUCT_INFO_MODEL','true','Mostra il campo Modello prodoto (codice) all\'interno della scheda prodotto, se a false, il campo modello verrï¿½ tolto anche dalla mail di conferma ordine',8,18,NULL,'2010-09-08 15:59:06',NULL,'tep_cfg_select_option(array(\'true\', \'false\'), '),(313,'Mostra campo data inserimento nella scheda prodotto','PRODUCT_INFO_DATE_ADDED','true','Mostra la data di inserimento in catalogo dell\'articolo all\'interno della scheda prodotto, se a false, il campo modello verrï¿½ tolto anche dalla mail di conferma ordine',8,19,NULL,'2010-09-08 15:59:06',NULL,'tep_cfg_select_option(array(\'true\', \'false\'), '),(314,'Mostra tabella costi di spedizione','ENABLE_PRODUCT_SHIPPING_COST','true','Mostra la tabella dei costi di spedizione all\'interno della scheda prodotto, se a false, la tabella non viene mostrata',8,20,NULL,'2010-09-08 15:59:06',NULL,'tep_cfg_select_option(array(\'true\', \'false\'), '),(315,'Mostra tabella Link Generici','ENABLE_PRODUCT_GENERIC_LINKS','true','Mostra la tabella dei link generici (Notifica aggiornamenti, Invia ad un amico etc.) all\'interno della scheda prodotto, se a false, la tabella non viene mostrata',8,21,NULL,'2010-09-08 15:59:06',NULL,'tep_cfg_select_option(array(\'true\', \'false\'), '),(316,'Abilita le Recensioni sugli articoli','REVIEWS_ENALBED','true','Abilita la possibilitï¿½ per gli utenti di scrivere recensioni sugli articoli a catalogo',8,20,NULL,'2010-09-08 15:59:06',NULL,'tep_cfg_select_option(array(\'true\', \'false\'), '),(317,'Permetti la registerazione ai Clienti Privati','ALLOW_FINAL_CUSTOMERS','true','Se abilitato, mostra il radio button per la scelta Privato/Azienda',5,0,NULL,'2010-09-08 15:59:06',NULL,'tep_cfg_select_option(array(\'true\', \'false\'), '),(318,'Abilita il carrello Ajax','AJAX_CART_ENABLED','true','Se abilitato (true), permette di inserire i prodotti dalla lista degli articoli per categoria senza ricaricare la pagina.',1,19,NULL,'2010-09-08 15:59:06',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(319,'Abilita la nuova gestione dei template','NEW_TEMPLATE_SYSTEM','false','Se abilitato (true), permette la nuova gestione dei template con selezione da admin.',1,19,NULL,'2010-09-08 15:59:06',NULL,'tep_cfg_select_option(array(\'true\', \'false\'),'),(320,'ICEcat login','ICECAT_USER','rroscilli','Enter your ICEcat username',714,1,NULL,'0000-00-00 00:00:00',NULL,NULL),(321,'ICEcat password','ICECAT_PASS','BnfvFd','Enter your ICEcat password',714,2,NULL,'0000-00-00 00:00:00',NULL,NULL),(322,'Larghezza immagine scheda prodotto','PRODUCT_IMAGE_WIDTH','320','Larghezza dell\'immagine principale nella scheda prodotto',4,0,NULL,'2010-02-25 14:58:39',NULL,NULL),(323,'Lunghezza immagine scheda prodotto','PRODUCT_IMAGE_HEIGHT','320','Lughezza dell\'immagine principale nella scheda prodotto',4,0,NULL,'2010-02-25 14:58:39',NULL,NULL),(324,'Larghezza anteprime immagini multiple','PRODUCT_IMAGE_WIDTH_THUMB','80','Larghezza delle anteprime del modulo multi image nella scheda prodotto',4,15,NULL,'2010-02-25 14:58:39',NULL,NULL),(325,'Lunghezza anteprime immagini multiple','PRODUCT_IMAGE_HEIGHT_THUMB','80','Larghezza delle anteprime del modulo multi image nella scheda prodotto',4,16,NULL,'2010-02-25 14:58:39',NULL,NULL),(326,'Google Analytics UA','GOOGLE_UA','','Inserisci il tuo UA fornito da google analytics, es. UA-600613-1',600613,0,NULL,'2010-07-01 18:09:06',NULL,NULL),(327,'Google Convertion ID','GOOGLE_CONVERSION_ID','','Inserisci il tuo ID fornito da google ADWords nella pagina delle conversioni, es. 1039600613',600613,1,NULL,'2010-07-01 18:09:06',NULL,NULL),(328,'Google Conversione Contatti','GOOGLE_CONTACT_LABEL','','Etichetta per la conversione delle richieste sul form Contattaci, es. KEghCNPO2wEQrN_f7wM',600613,2,NULL,'2010-07-01 18:09:06',NULL,NULL),(329,'Google Conversione Registrazioni','GOOGLE_REGISTRATION_LABEL','','Etichetta per la conversione delle registrazioni, es. PONnkjnkO2wEQrN_f7wM',600613,3,NULL,'2010-07-01 18:09:06',NULL,NULL),(330,'Google Conversione Acquisti','GOOGLE_ACQUISTO_LABEL','','Etichetta per la conversione delle vendite, es. pojnoinNIOnKDJBKd_f7wM',600613,4,NULL,'2010-07-01 18:09:06',NULL,NULL),(331,'Esporta solo Disponibili','PRICE_COMP_ONLYAVAILABLE','','Se abiliti questa voce, solo i prodotti disponibili verranno esportati sui comparatori',35909,0,NULL,'2010-07-01 18:09:06',NULL,'tep_cfg_select_option(array(\'True\', \'False\'),'),(332,'Frase da inserire prima della descrizione (max 255 caratteri) ','PRICE_COMP_CLAIM','','Questa frase sarà  inserita all\'inizio della descrizione di ogni articolo',35909,1,NULL,'2010-07-01 18:09:06',NULL,NULL),(333,'ID Merchant fornito da Kelkoo per il TradeDoubler','KELKOO_ORGANIZATION','','Codice che identifica univocamente il merchant necessario per l\'invio dei dati a TradeDoubler (in mancanza di questo codice il TD non funzionerà!)',35909,2,NULL,'2010-07-01 18:09:06',NULL,NULL),(334,'ID Evento fornito da Kelkoo per il TradeDoubler','KELKOO_EVENT','','Codice che identifica univocamente l\'evento Kelkoo, necessario per l\'invio dei dati a TradeDoubler (in mancanza di questo codice il TD non funzionerà!)',35909,3,NULL,'2010-07-01 18:09:06',NULL,NULL);
/*!40000 ALTER TABLE `configuration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `configuration_group`
--

DROP TABLE IF EXISTS `configuration_group`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `configuration_group` (
  `configuration_group_id` int(11) NOT NULL auto_increment,
  `configuration_group_title` varchar(64) NOT NULL,
  `configuration_group_description` varchar(255) NOT NULL,
  `sort_order` int(5) default NULL,
  `visible` int(1) default '1',
  PRIMARY KEY  (`configuration_group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=888003 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `configuration_group`
--

LOCK TABLES `configuration_group` WRITE;
/*!40000 ALTER TABLE `configuration_group` DISABLE KEYS */;
INSERT INTO `configuration_group` VALUES (1,'Il Mio Negozio','General information about my store',1,1),(2,'Valori Minimi','The minimum values for functions / data',2,1),(3,'Valori Massimi','The maximum values for functions / data',3,1),(4,'Immagini','Image parameters',4,1),(5,'Dettagli Cliente','Customer account configuration',5,1),(6,'Opzioni Moduli','Hidden from configuration',6,0),(7,'Spedizioni e Packaging','Shipping options available at my store',7,1),(8,'Opzioni elenco prodotti','Product Listing    configuration options',8,1),(9,'Magazzino','Stock configuration options',9,1),(10,'Opzioni di Log','Logging configuration options',10,1),(11,'Cache','Caching configuration options',11,1),(12,'Opzioni Email','General setting for E-Mail transport and HTML E-Mails',12,1),(13,'Download','Downloadable products options',13,1),(14,'Compressione GZip','GZip compression options',14,1),(15,'Sessioni','Session options',15,1),(17,'Easy Populate','Easy Populate',17,1),(112,'WYSIWYG Editor 1.7','HTMLArea 1.7 Options',15,1),(300,'Aggiornamento Veloce','Configura quali campi modificare nella sezione Aggioramento Veloce',300,1),(888001,'Product Information','Product Information page configuration options',8,1),(888002,'SEO URLs','Options for Ultimate SEO URLs by Chemo',301,1),(555,'PWS Engine','Sistema a plugins PWS',302,1),(714,'ICEcat','ICEcat plugin',417,0),(600613,'Google','Parametri per google analytics e adwords',310,1),(35909,'Comparatori','Parametri per export su comparatori prezzo (es. Trovaprezzi)',500,1);
/*!40000 ALTER TABLE `configuration_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `counter`
--

DROP TABLE IF EXISTS `counter`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `counter` (
  `startdate` char(8) default NULL,
  `counter` int(12) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `counter`
--

LOCK TABLES `counter` WRITE;
/*!40000 ALTER TABLE `counter` DISABLE KEYS */;
INSERT INTO `counter` VALUES ('20060122',934);
/*!40000 ALTER TABLE `counter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `counter_history`
--

DROP TABLE IF EXISTS `counter_history`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `counter_history` (
  `month` char(8) default NULL,
  `counter` int(12) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `counter_history`
--

LOCK TABLES `counter_history` WRITE;
/*!40000 ALTER TABLE `counter_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `counter_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `countries` (
  `countries_id` int(11) NOT NULL auto_increment,
  `countries_name` varchar(64) NOT NULL,
  `countries_iso_code_2` char(2) NOT NULL,
  `countries_iso_code_3` char(3) NOT NULL,
  `address_format_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`countries_id`),
  KEY `IDX_COUNTRIES_NAME` (`countries_name`)
) ENGINE=MyISAM AUTO_INCREMENT=240 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `countries`
--

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
INSERT INTO `countries` VALUES (1,'Afghanistan','AF','AFG',1),(2,'Albania','AL','ALB',1),(3,'Algeria','DZ','DZA',1),(4,'American Samoa','AS','ASM',1),(5,'Andorra','AD','AND',1),(6,'Angola','AO','AGO',1),(7,'Anguilla','AI','AIA',1),(8,'Antarctica','AQ','ATA',1),(9,'Antigua and Barbuda','AG','ATG',1),(10,'Argentina','AR','ARG',1),(11,'Armenia','AM','ARM',1),(12,'Aruba','AW','ABW',1),(13,'Australia','AU','AUS',1),(14,'Austria','AT','AUT',5),(15,'Azerbaijan','AZ','AZE',1),(16,'Bahamas','BS','BHS',1),(17,'Bahrain','BH','BHR',1),(18,'Bangladesh','BD','BGD',1),(19,'Barbados','BB','BRB',1),(20,'Belarus','BY','BLR',1),(21,'Belgium','BE','BEL',1),(22,'Belize','BZ','BLZ',1),(23,'Benin','BJ','BEN',1),(24,'Bermuda','BM','BMU',1),(25,'Bhutan','BT','BTN',1),(26,'Bolivia','BO','BOL',1),(27,'Bosnia and Herzegowina','BA','BIH',1),(28,'Botswana','BW','BWA',1),(29,'Bouvet Island','BV','BVT',1),(30,'Brazil','BR','BRA',1),(31,'British Indian Ocean Territory','IO','IOT',1),(32,'Brunei Darussalam','BN','BRN',1),(33,'Bulgaria','BG','BGR',1),(34,'Burkina Faso','BF','BFA',1),(35,'Burundi','BI','BDI',1),(36,'Cambodia','KH','KHM',1),(37,'Cameroon','CM','CMR',1),(38,'Canada','CA','CAN',1),(39,'Cape Verde','CV','CPV',1),(40,'Cayman Islands','KY','CYM',1),(41,'Central African Republic','CF','CAF',1),(42,'Chad','TD','TCD',1),(43,'Chile','CL','CHL',1),(44,'China','CN','CHN',1),(45,'Christmas Island','CX','CXR',1),(46,'Cocos (Keeling) Islands','CC','CCK',1),(47,'Colombia','CO','COL',1),(48,'Comoros','KM','COM',1),(49,'Congo','CG','COG',1),(50,'Cook Islands','CK','COK',1),(51,'Costa Rica','CR','CRI',1),(52,'Cote D\'Ivoire','CI','CIV',1),(53,'Croatia','HR','HRV',1),(54,'Cuba','CU','CUB',1),(55,'Cyprus','CY','CYP',1),(56,'Czech Republic','CZ','CZE',1),(57,'Denmark','DK','DNK',1),(58,'Djibouti','DJ','DJI',1),(59,'Dominica','DM','DMA',1),(60,'Dominican Republic','DO','DOM',1),(61,'East Timor','TP','TMP',1),(62,'Ecuador','EC','ECU',1),(63,'Egypt','EG','EGY',1),(64,'El Salvador','SV','SLV',1),(65,'Equatorial Guinea','GQ','GNQ',1),(66,'Eritrea','ER','ERI',1),(67,'Estonia','EE','EST',1),(68,'Ethiopia','ET','ETH',1),(69,'Falkland Islands (Malvinas)','FK','FLK',1),(70,'Faroe Islands','FO','FRO',1),(71,'Fiji','FJ','FJI',1),(72,'Finland','FI','FIN',1),(73,'France','FR','FRA',1),(74,'France, Metropolitan','FX','FXX',1),(75,'French Guiana','GF','GUF',1),(76,'French Polynesia','PF','PYF',1),(77,'French Southern Territories','TF','ATF',1),(78,'Gabon','GA','GAB',1),(79,'Gambia','GM','GMB',1),(80,'Georgia','GE','GEO',1),(81,'Germany','DE','DEU',5),(82,'Ghana','GH','GHA',1),(83,'Gibraltar','GI','GIB',1),(84,'Greece','GR','GRC',1),(85,'Greenland','GL','GRL',1),(86,'Grenada','GD','GRD',1),(87,'Guadeloupe','GP','GLP',1),(88,'Guam','GU','GUM',1),(89,'Guatemala','GT','GTM',1),(90,'Guinea','GN','GIN',1),(91,'Guinea-bissau','GW','GNB',1),(92,'Guyana','GY','GUY',1),(93,'Haiti','HT','HTI',1),(94,'Heard and Mc Donald Islands','HM','HMD',1),(95,'Honduras','HN','HND',1),(96,'Hong Kong','HK','HKG',1),(97,'Hungary','HU','HUN',1),(98,'Iceland','IS','ISL',1),(99,'India','IN','IND',1),(100,'Indonesia','ID','IDN',1),(101,'Iran (Islamic Republic of)','IR','IRN',1),(102,'Iraq','IQ','IRQ',1),(103,'Ireland','IE','IRL',1),(104,'Israel','IL','ISR',1),(105,'Italia','IT','ITA',1),(106,'Jamaica','JM','JAM',1),(107,'Japan','JP','JPN',1),(108,'Jordan','JO','JOR',1),(109,'Kazakhstan','KZ','KAZ',1),(110,'Kenya','KE','KEN',1),(111,'Kiribati','KI','KIR',1),(112,'Korea, Democratic People\'s Republic of','KP','PRK',1),(113,'Korea, Republic of','KR','KOR',1),(114,'Kuwait','KW','KWT',1),(115,'Kyrgyzstan','KG','KGZ',1),(116,'Lao People\'s Democratic Republic','LA','LAO',1),(117,'Latvia','LV','LVA',1),(118,'Lebanon','LB','LBN',1),(119,'Lesotho','LS','LSO',1),(120,'Liberia','LR','LBR',1),(121,'Libyan Arab Jamahiriya','LY','LBY',1),(122,'Liechtenstein','LI','LIE',1),(123,'Lithuania','LT','LTU',1),(124,'Luxembourg','LU','LUX',1),(125,'Macau','MO','MAC',1),(126,'Macedonia, The Former Yugoslav Republic of','MK','MKD',1),(127,'Madagascar','MG','MDG',1),(128,'Malawi','MW','MWI',1),(129,'Malaysia','MY','MYS',1),(130,'Maldives','MV','MDV',1),(131,'Mali','ML','MLI',1),(132,'Malta','MT','MLT',1),(133,'Marshall Islands','MH','MHL',1),(134,'Martinique','MQ','MTQ',1),(135,'Mauritania','MR','MRT',1),(136,'Mauritius','MU','MUS',1),(137,'Mayotte','YT','MYT',1),(138,'Mexico','MX','MEX',1),(139,'Micronesia, Federated States of','FM','FSM',1),(140,'Moldova, Republic of','MD','MDA',1),(141,'Monaco','MC','MCO',1),(142,'Mongolia','MN','MNG',1),(143,'Montserrat','MS','MSR',1),(144,'Morocco','MA','MAR',1),(145,'Mozambique','MZ','MOZ',1),(146,'Myanmar','MM','MMR',1),(147,'Namibia','NA','NAM',1),(148,'Nauru','NR','NRU',1),(149,'Nepal','NP','NPL',1),(150,'Netherlands','NL','NLD',1),(151,'Netherlands Antilles','AN','ANT',1),(152,'New Caledonia','NC','NCL',1),(153,'New Zealand','NZ','NZL',1),(154,'Nicaragua','NI','NIC',1),(155,'Niger','NE','NER',1),(156,'Nigeria','NG','NGA',1),(157,'Niue','NU','NIU',1),(158,'Norfolk Island','NF','NFK',1),(159,'Northern Mariana Islands','MP','MNP',1),(160,'Norway','NO','NOR',1),(161,'Oman','OM','OMN',1),(162,'Pakistan','PK','PAK',1),(163,'Palau','PW','PLW',1),(164,'Panama','PA','PAN',1),(165,'Papua New Guinea','PG','PNG',1),(166,'Paraguay','PY','PRY',1),(167,'Peru','PE','PER',1),(168,'Philippines','PH','PHL',1),(169,'Pitcairn','PN','PCN',1),(170,'Poland','PL','POL',1),(171,'Portugal','PT','PRT',1),(172,'Puerto Rico','PR','PRI',1),(173,'Qatar','QA','QAT',1),(174,'Reunion','RE','REU',1),(175,'Romania','RO','ROM',1),(176,'Russian Federation','RU','RUS',1),(177,'Rwanda','RW','RWA',1),(178,'Saint Kitts and Nevis','KN','KNA',1),(179,'Saint Lucia','LC','LCA',1),(180,'Saint Vincent and the Grenadines','VC','VCT',1),(181,'Samoa','WS','WSM',1),(182,'San Marino','SM','SMR',1),(183,'Sao Tome and Principe','ST','STP',1),(184,'Saudi Arabia','SA','SAU',1),(185,'Senegal','SN','SEN',1),(186,'Seychelles','SC','SYC',1),(187,'Sierra Leone','SL','SLE',1),(188,'Singapore','SG','SGP',4),(189,'Slovakia (Slovak Republic)','SK','SVK',1),(190,'Slovenia','SI','SVN',1),(191,'Solomon Islands','SB','SLB',1),(192,'Somalia','SO','SOM',1),(193,'South Africa','ZA','ZAF',1),(194,'South Georgia and the South Sandwich Islands','GS','SGS',1),(195,'Spain','ES','ESP',3),(196,'Sri Lanka','LK','LKA',1),(197,'St. Helena','SH','SHN',1),(198,'St. Pierre and Miquelon','PM','SPM',1),(199,'Sudan','SD','SDN',1),(200,'Suriname','SR','SUR',1),(201,'Svalbard and Jan Mayen Islands','SJ','SJM',1),(202,'Swaziland','SZ','SWZ',1),(203,'Sweden','SE','SWE',1),(204,'Switzerland','CH','CHE',1),(205,'Syrian Arab Republic','SY','SYR',1),(206,'Taiwan','TW','TWN',1),(207,'Tajikistan','TJ','TJK',1),(208,'Tanzania, United Republic of','TZ','TZA',1),(209,'Thailand','TH','THA',1),(210,'Togo','TG','TGO',1),(211,'Tokelau','TK','TKL',1),(212,'Tonga','TO','TON',1),(213,'Trinidad and Tobago','TT','TTO',1),(214,'Tunisia','TN','TUN',1),(215,'Turkey','TR','TUR',1),(216,'Turkmenistan','TM','TKM',1),(217,'Turks and Caicos Islands','TC','TCA',1),(218,'Tuvalu','TV','TUV',1),(219,'Uganda','UG','UGA',1),(220,'Ukraine','UA','UKR',1),(221,'United Arab Emirates','AE','ARE',1),(222,'United Kingdom','GB','GBR',1),(223,'United States','US','USA',2),(224,'United States Minor Outlying Islands','UM','UMI',1),(225,'Uruguay','UY','URY',1),(226,'Uzbekistan','UZ','UZB',1),(227,'Vanuatu','VU','VUT',1),(228,'Vatican City State (Holy See)','VA','VAT',1),(229,'Venezuela','VE','VEN',1),(230,'Viet Nam','VN','VNM',1),(231,'Virgin Islands (British)','VG','VGB',1),(232,'Virgin Islands (U.S.)','VI','VIR',1),(233,'Wallis and Futuna Islands','WF','WLF',1),(234,'Western Sahara','EH','ESH',1),(235,'Yemen','YE','YEM',1),(236,'Yugoslavia','YU','YUG',1),(237,'Zaire','ZR','ZAR',1),(238,'Zambia','ZM','ZMB',1),(239,'Zimbabwe','ZW','ZWE',1);
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currencies`
--

DROP TABLE IF EXISTS `currencies`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `currencies` (
  `currencies_id` int(11) NOT NULL auto_increment,
  `title` varchar(32) NOT NULL,
  `code` char(3) NOT NULL,
  `symbol_left` varchar(12) default NULL,
  `symbol_right` varchar(12) default NULL,
  `decimal_point` char(1) default NULL,
  `thousands_point` char(1) default NULL,
  `decimal_places` char(1) default NULL,
  `value` float(13,8) default NULL,
  `last_updated` datetime default NULL,
  PRIMARY KEY  (`currencies_id`),
  KEY `idx_currencies_code` (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `currencies`
--

LOCK TABLES `currencies` WRITE;
/*!40000 ALTER TABLE `currencies` DISABLE KEYS */;
INSERT INTO `currencies` VALUES (2,'Euro','EUR','','EUR','.',',','2',1.00000000,'2005-03-08 03:43:29');
/*!40000 ALTER TABLE `currencies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `customers` (
  `customers_id` int(11) NOT NULL auto_increment,
  `customers_gender` char(1) NOT NULL,
  `customers_firstname` varchar(32) NOT NULL,
  `customers_lastname` varchar(32) NOT NULL,
  `customers_dob` datetime NOT NULL default '0000-00-00 00:00:00',
  `customers_email_address` varchar(96) NOT NULL,
  `customers_default_address_id` int(11) NOT NULL default '0',
  `customers_telephone` varchar(32) NOT NULL,
  `customers_fax` varchar(32) default NULL,
  `customers_password` varchar(40) NOT NULL,
  `customers_newsletter` char(1) default NULL,
  `customers_group_id` smallint(5) unsigned NOT NULL default '0',
  `customers_group_ra` enum('0','1') NOT NULL default '0',
  `customers_payment_allowed` varchar(255) NOT NULL,
  `customers_shipment_allowed` varchar(255) NOT NULL,
  `customers_paypal_payerid` varchar(20) default NULL,
  `customers_paypal_ec` tinyint(1) unsigned NOT NULL default '0',
  `customers_code` varchar(50) default NULL,
  PRIMARY KEY  (`customers_id`),
  KEY `idx_customers_email_address` (`customers_email_address`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'m','John','doe','2001-01-01 00:00:00','root@localhost',1,'12345','','d95e8fa7f20a009372eb3477473fcd34:1c','0',0,'0','','',NULL,0,NULL),(2,'','test','test','0000-00-00 00:00:00','test@test.com',13,'13413561361361361','','61656cbac34d44aac18f4c3bd636ab67:7b','',0,'0','','',NULL,0,NULL),(3,'','Giulio','D\'Ambrosio','0000-00-00 00:00:00','prova@prova.com',15,'3293965918','','82f3dc94216e0ab15eb8140a67ca6243:b8','',0,'0','','',NULL,0,NULL),(4,'','Test','Test','0000-00-00 00:00:00','info@oscommerce.com',16,'3293965918','','1c81d60b58e0363eb63543ccd344d177:9d','',0,'0','','',NULL,0,NULL);
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers_basket`
--

DROP TABLE IF EXISTS `customers_basket`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `customers_basket` (
  `customers_basket_id` int(11) NOT NULL auto_increment,
  `customers_id` int(11) NOT NULL default '0',
  `products_id` tinytext NOT NULL,
  `customers_basket_quantity` int(2) NOT NULL default '0',
  `final_price` decimal(15,4) NOT NULL default '0.0000',
  `customers_basket_date_added` varchar(8) default NULL,
  PRIMARY KEY  (`customers_basket_id`),
  KEY `idx_customers_basket_customers_id` (`customers_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `customers_basket`
--

LOCK TABLES `customers_basket` WRITE;
/*!40000 ALTER TABLE `customers_basket` DISABLE KEYS */;
INSERT INTO `customers_basket` VALUES (1,3,'5',1,'0.0000','20071023'),(2,3,'1',1,'0.0000','20071023'),(3,3,'16',1,'0.0000','20071023'),(4,4,'24',1,'0.0000','20071024');
/*!40000 ALTER TABLE `customers_basket` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers_basket_attributes`
--

DROP TABLE IF EXISTS `customers_basket_attributes`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `customers_basket_attributes` (
  `customers_basket_attributes_id` int(11) NOT NULL auto_increment,
  `customers_id` int(11) NOT NULL default '0',
  `products_id` tinytext NOT NULL,
  `products_options_id` int(11) NOT NULL default '0',
  `products_options_value_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`customers_basket_attributes_id`),
  KEY `idx_customers_basket_att_customers_id` (`customers_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `customers_basket_attributes`
--

LOCK TABLES `customers_basket_attributes` WRITE;
/*!40000 ALTER TABLE `customers_basket_attributes` DISABLE KEYS */;
/*!40000 ALTER TABLE `customers_basket_attributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers_groups`
--

DROP TABLE IF EXISTS `customers_groups`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `customers_groups` (
  `customers_group_id` smallint(5) unsigned NOT NULL default '0',
  `customers_group_name` varchar(32) NOT NULL,
  `customers_group_show_tax` enum('1','0') NOT NULL default '1',
  `customers_group_tax_exempt` enum('0','1') NOT NULL default '0',
  `customers_group_default_discount` decimal(6,2) NOT NULL default '0.00',
  `group_payment_allowed` varchar(255) NOT NULL,
  `group_shipment_allowed` varchar(255) NOT NULL,
  `customers_group_show_prices` enum('1','0') NOT NULL default '1',
  `customers_group_hidden_prices_msg` varchar(255) NOT NULL default 'Occorre registrarsi per vedere i prezzi',
  PRIMARY KEY  (`customers_group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `customers_groups`
--

LOCK TABLES `customers_groups` WRITE;
/*!40000 ALTER TABLE `customers_groups` DISABLE KEYS */;
INSERT INTO `customers_groups` VALUES (0,'Clienti Finali','1','0','0.00','','','1','Occorre registrarsi per vedere i prezzi'),(1,'Rivenditori','0','0','0.00','bonifico.php','','1','Occorre registrarsi per vedere i prezzi');
/*!40000 ALTER TABLE `customers_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers_info`
--

DROP TABLE IF EXISTS `customers_info`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `customers_info` (
  `customers_info_id` int(11) NOT NULL default '0',
  `customers_info_date_of_last_logon` datetime default NULL,
  `customers_info_number_of_logons` int(5) default NULL,
  `customers_info_date_account_created` datetime default NULL,
  `customers_info_date_account_last_modified` datetime default NULL,
  `global_product_notifications` int(1) default '0',
  PRIMARY KEY  (`customers_info_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `customers_info`
--

LOCK TABLES `customers_info` WRITE;
/*!40000 ALTER TABLE `customers_info` DISABLE KEYS */;
INSERT INTO `customers_info` VALUES (1,'0000-00-00 00:00:00',0,'2005-03-08 03:43:29','0000-00-00 00:00:00',0),(2,'2007-10-23 17:56:34',4,'2007-10-22 12:03:38','2007-10-22 12:25:59',0),(3,'2007-10-29 15:46:26',12,'2007-10-23 10:54:04',NULL,0),(4,'2007-10-24 12:59:34',3,'2007-10-24 12:13:33','2007-10-24 12:13:46',0);
/*!40000 ALTER TABLE `customers_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `discount_coupons_to_customer_groups`
--

DROP TABLE IF EXISTS `discount_coupons_to_customer_groups`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `discount_coupons_to_customer_groups` (
  `coupons_id` varchar(33) NOT NULL,
  `customers_group_id` varchar(11) NOT NULL,
  PRIMARY KEY  (`coupons_id`,`customers_group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `discount_coupons_to_customer_groups`
--

LOCK TABLES `discount_coupons_to_customer_groups` WRITE;
/*!40000 ALTER TABLE `discount_coupons_to_customer_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `discount_coupons_to_customer_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eorder_text`
--

DROP TABLE IF EXISTS `eorder_text`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `eorder_text` (
  `eorder_text_id` tinyint(3) unsigned NOT NULL default '0',
  `language_id` tinyint(3) unsigned NOT NULL default '1',
  `eorder_text_one` text,
  PRIMARY KEY  (`eorder_text_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `eorder_text`
--

LOCK TABLES `eorder_text` WRITE;
/*!40000 ALTER TABLE `eorder_text` DISABLE KEYS */;
INSERT INTO `eorder_text` VALUES (2,5,'<br /> <br /> <table width=\"808\" border=\"0\">     <tbody>         <tr>             <td width=\"800\" height=\"20\" bgcolor=\"#3287c8\" colspan=\"2\">             <p align=\"center\"><font size=\"3\" face=\"Calibri\"><b><-STORE_NAME->&nbsp; -&nbsp; ORDER CONFIRMATION </b></font></p>             </td>         </tr>         <tr>             <td width=\"800\" height=\"10\" colspan=\"2\">&nbsp;</td>         </tr>         <tr>             <td width=\"800\" bgcolor=\"#cbcbf3\" colspan=\"2\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font face=\"Calibri\"><b>Order\'s Data<br />             </b></font></p>             </td>         </tr>         <tr>             <td width=\"179\" bgcolor=\"#ebebeb\" align=\"left\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><b>Order Number<br />             </b></font></p>             </td>             <td width=\"615\" bgcolor=\"#ebebeb\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><-insert_id-></font></p>             </td>         </tr>         <tr>             <td width=\"179\" bgcolor=\"#ebebeb\" align=\"left\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><b>Order Details<br />             </b></font></p>             </td>             <td width=\"615\" bgcolor=\"#ebebeb\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><a href=\"<-INVOICE_URL->\" target=\"_blank\"><font size=\"2\" face=\"Calibri\"><-INVOICE_URL-></font></a></p>             </td>         </tr>         <tr>             <td width=\"179\" bgcolor=\"#ebebeb\" align=\"left\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><b>Order Date<br />             </b></font></p>             </td>             <td width=\"615\" bgcolor=\"#ebebeb\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><-DATE_ORDERED-></font></p>             </td>         </tr>     </tbody> </table> <table width=\"808\" border=\"0\">     <tbody>         <tr>             <td width=\"800\" bgcolor=\"#cbcbf3\" colspan=\"4\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font face=\"Calibri\"><b>Items</b></font></p>             </td>         </tr>         <tr>             <td width=\"140\" bgcolor=\"#ebebeb\" style=\"\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font face=\"Calibri\"><b>Code</b></font></p>             </td>             <td width=\"540\" bgcolor=\"#ebebeb\" style=\"\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><span style=\"font-weight: bold;\">Item</span></p>             </td>             <td width=\"40\" bgcolor=\"#ebebeb\" style=\"\">             <p align=\"center\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt;\"><font face=\"Calibri\"><b>Q.ty</b></font></p>             </td>             <td width=\"80\" bgcolor=\"#ebebeb\" style=\"\">             <p align=\"right\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 4px 0pt 0pt;\"><font face=\"Calibri\"><b>Price</b></font></p>             </td>         </tr>         <-Item_List->     </tbody> </table> <table width=\"808\" border=\"0\">     <tbody>         <tr>             <-List_Total->         </tr>     </tbody> </table> <table width=\"808\" border=\"0\">     <tbody>         <tr>             <td width=\"800\" height=\"10\">             <p style=\"word-spacing: 0pt; line-height: 100%; margin: 0pt;\">&nbsp;</p>             </td>         </tr>     </tbody> </table> <table width=\"808\" border=\"0\">     <tbody>         <tr>             <td width=\"50%\" bgcolor=\"#cbcbf3\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font face=\"Calibri\"><b>Billing Address<br />             </b></font></p>             </td>             <td width=\"50%\" bgcolor=\"#cbcbf3\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font face=\"Calibri\"><b>Delivery Address<br />             </b></font></p>             </td>         </tr>         <tr>             <td width=\"50%\" bgcolor=\"#ebebeb\" align=\"left\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><b><-BILL_Adress-></b></font></p>             </td>             <td width=\"50%\" bgcolor=\"#ebebeb\" align=\"left\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><b><-DELIVERY_Adress-></b></font></p>             </td>         </tr>     </tbody> </table> <table width=\"808\" border=\"0\">     <tbody>         <tr>             <td width=\"800\" bgcolor=\"#cbcbf3\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font face=\"Calibri\"><b>Customer\'s Comment<br />             </b></font></p>             </td>         </tr>         <tr>             <td width=\"800\">             <p style=\"word-spacing: 0pt; line-height: 100%; margin: 0pt;\"><-Customer_Comments->&nbsp;</p>             </td>         </tr>     </tbody> </table> <table width=\"808\" border=\"0\">     <tbody>         <tr>             <td width=\"30%\" bgcolor=\"#ebebeb\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><b>Payment Method<br />             </b></font></p>             </td>             <td width=\"70%\" bgcolor=\"#ebebeb\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><-Payment_Modul_Text-><-Payment_Modul_Text_Footer-> </font></p>             </td>         </tr>     </tbody> </table>'),(2,4,'<br /> <br /> <table width=\"808\" border=\"0\">     <tbody>         <tr>             <td width=\"800\" height=\"20\" bgcolor=\"#3287c8\" colspan=\"2\">             <p align=\"center\"><font size=\"3\" face=\"Calibri\"><b><-STORE_NAME->&nbsp; -&nbsp;       CONFERMA ORDINE </b></font></p>             </td>         </tr>         <tr>             <td width=\"800\" height=\"10\" colspan=\"2\">&nbsp;</td>         </tr>         <tr>             <td width=\"800\" bgcolor=\"#cbcbf3\" colspan=\"2\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font face=\"Calibri\"><b>Dati       Ordine</b></font></p>             </td>         </tr>         <tr>             <td width=\"179\" bgcolor=\"#ebebeb\" align=\"left\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><b>Ordine       Numero</b></font></p>             </td>             <td width=\"615\" bgcolor=\"#ebebeb\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><-insert_id-></font></p>             </td>         </tr>         <tr>             <td width=\"179\" bgcolor=\"#ebebeb\" align=\"left\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><b>Dettaglio       ordine</b></font></p>             </td>             <td width=\"615\" bgcolor=\"#ebebeb\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><a href=\"<-INVOICE_URL->\" target=\"_blank\"><font size=\"2\" face=\"Calibri\"><-INVOICE_URL-></font></a></p>             </td>         </tr>         <tr>             <td width=\"179\" bgcolor=\"#ebebeb\" align=\"left\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><b>Data       ordine</b></font></p>             </td>             <td width=\"615\" bgcolor=\"#ebebeb\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><-DATE_ORDERED-></font></p>             </td>         </tr>     </tbody> </table> <table width=\"808\" border=\"0\">     <tbody>         <tr>             <td width=\"800\" bgcolor=\"#cbcbf3\" colspan=\"4\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font face=\"Calibri\"><b>Prodotti</b></font></p>             </td>         </tr>         <tr>             <td width=\"140\" bgcolor=\"#ebebeb\" style=\"\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font face=\"Calibri\"><b>Codice</b></font></p>             </td>             <td width=\"540\" bgcolor=\"#ebebeb\" style=\"\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font face=\"Calibri\"><b>Articolo</b></font></p>             </td>             <td width=\"40\" bgcolor=\"#ebebeb\" style=\"\">             <p align=\"center\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt;\"><font face=\"Calibri\"><b>Q.t&agrave;</b></font></p>             </td>             <td width=\"80\" bgcolor=\"#ebebeb\" style=\"\">             <p align=\"right\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 4px 0pt 0pt;\"><font face=\"Calibri\"><b>Importo</b></font></p>             </td>         </tr>         <-Item_List->     </tbody> </table> <table width=\"808\" border=\"0\">     <tbody>         <tr>             <-List_Total->         </tr>     </tbody> </table> <table width=\"808\" border=\"0\">     <tbody>         <tr>             <td width=\"800\" height=\"10\">             <p style=\"word-spacing: 0pt; line-height: 100%; margin: 0pt;\">&nbsp;</p>             </td>         </tr>     </tbody> </table> <table width=\"808\" border=\"0\">     <tbody>         <tr>             <td width=\"50%\" bgcolor=\"#cbcbf3\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font face=\"Calibri\"><b>Indirizzo       di Fatturazione</b></font></p>             </td>             <td width=\"50%\" bgcolor=\"#cbcbf3\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font face=\"Calibri\"><b>Indirizzo       di Spedizione</b></font></p>             </td>         </tr>         <tr>             <td width=\"50%\" bgcolor=\"#ebebeb\" align=\"left\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><b><-BILL_Adress-></b></font></p>             </td>             <td width=\"50%\" bgcolor=\"#ebebeb\" align=\"left\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><b><-DELIVERY_Adress-></b></font></p>             </td>         </tr>     </tbody> </table> <table width=\"808\" border=\"0\">     <tbody>         <tr>             <td width=\"800\" bgcolor=\"#cbcbf3\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font face=\"Calibri\"><b>Commenti del Cliente</b></font></p>             </td>         </tr>         <tr>             <td width=\"800\" bgcolor=\"#ebebeb\" align=\"left\">             <p style=\"word-spacing: 0pt; line-height: 100%; margin: 0pt;\"><-Customer_Comments->&nbsp;</p>             </td>         </tr>     </tbody> </table> <table width=\"808\" border=\"0\">     <tbody>         <tr>             <td width=\"30%\" bgcolor=\"#ebebeb\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><b>Metodo       di pagamento</b></font></p>             </td>             <td width=\"70%\" bgcolor=\"#ebebeb\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><-Payment_Modul_Text-><-Payment_Modul_Text_Footer-> </font></p>             </td>         </tr>     </tbody> </table>');
/*!40000 ALTER TABLE `eorder_text` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `geo_zones`
--

DROP TABLE IF EXISTS `geo_zones`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `geo_zones` (
  `geo_zone_id` int(11) NOT NULL auto_increment,
  `geo_zone_name` varchar(32) NOT NULL,
  `geo_zone_description` varchar(255) NOT NULL,
  `last_modified` datetime default NULL,
  `date_added` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`geo_zone_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `geo_zones`
--

LOCK TABLES `geo_zones` WRITE;
/*!40000 ALTER TABLE `geo_zones` DISABLE KEYS */;
INSERT INTO `geo_zones` VALUES (1,'Italia','IVA 20%','2005-03-08 03:48:11','2005-03-08 03:43:29');
/*!40000 ALTER TABLE `geo_zones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `icecat_products`
--

DROP TABLE IF EXISTS `icecat_products`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `icecat_products` (
  `products_id` int(11) NOT NULL,
  `prod_id` varchar(64) default NULL,
  `vendor` varchar(64) NOT NULL,
  `lang` varchar(3) NOT NULL,
  `changed` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `data` text NOT NULL,
  UNIQUE KEY `products_vendors` (`products_id`,`vendor`,`prod_id`,`lang`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `icecat_products`
--

LOCK TABLES `icecat_products` WRITE;
/*!40000 ALTER TABLE `icecat_products` DISABLE KEYS */;
/*!40000 ALTER TABLE `icecat_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `languages` (
  `languages_id` int(11) NOT NULL auto_increment,
  `name` varchar(32) NOT NULL,
  `code` char(2) NOT NULL,
  `image` varchar(64) default NULL,
  `directory` varchar(32) default NULL,
  `sort_order` int(3) default NULL,
  PRIMARY KEY  (`languages_id`),
  KEY `IDX_LANGUAGES_NAME` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `languages`
--

LOCK TABLES `languages` WRITE;
/*!40000 ALTER TABLE `languages` DISABLE KEYS */;
INSERT INTO `languages` VALUES (4,'Italiano','it','icon.gif','italian',0),(5,'english','en','icon.gif','english',1);
/*!40000 ALTER TABLE `languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `manufacturers`
--

DROP TABLE IF EXISTS `manufacturers`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `manufacturers` (
  `manufacturers_id` int(11) NOT NULL auto_increment,
  `manufacturers_name` varchar(32) NOT NULL,
  `manufacturers_image` varchar(64) default NULL,
  `date_added` datetime default NULL,
  `last_modified` datetime default NULL,
  PRIMARY KEY  (`manufacturers_id`),
  KEY `IDX_MANUFACTURERS_NAME` (`manufacturers_name`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `manufacturers`
--

LOCK TABLES `manufacturers` WRITE;
/*!40000 ALTER TABLE `manufacturers` DISABLE KEYS */;
INSERT INTO `manufacturers` VALUES (1,'Matrox','manufacturer_matrox.gif','2005-03-08 03:43:29',NULL),(2,'Microsoft','manufacturer_microsoft.gif','2005-03-08 03:43:29',NULL),(3,'Warner','manufacturer_warner.gif','2005-03-08 03:43:29',NULL),(4,'Fox','manufacturer_fox.gif','2005-03-08 03:43:29',NULL),(5,'Logitech','manufacturer_logitech.gif','2005-03-08 03:43:29',NULL),(6,'Canon','manufacturer_canon.gif','2005-03-08 03:43:29',NULL),(7,'Sierra','manufacturer_sierra.gif','2005-03-08 03:43:29',NULL),(8,'GT Interactive','manufacturer_gt_interactive.gif','2005-03-08 03:43:29',NULL),(9,'Hewlett Packard','manufacturer_hewlett_packard.gif','2005-03-08 03:43:29',NULL);
/*!40000 ALTER TABLE `manufacturers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `manufacturers_info`
--

DROP TABLE IF EXISTS `manufacturers_info`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `manufacturers_info` (
  `manufacturers_id` int(11) NOT NULL default '0',
  `languages_id` int(11) NOT NULL default '4',
  `manufacturers_url` varchar(255) NOT NULL,
  `url_clicked` int(5) NOT NULL default '0',
  `date_last_click` datetime default NULL,
  `manufacturers_description` text,
  PRIMARY KEY  (`manufacturers_id`,`languages_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `manufacturers_info`
--

LOCK TABLES `manufacturers_info` WRITE;
/*!40000 ALTER TABLE `manufacturers_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `manufacturers_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `newsletters`
--

DROP TABLE IF EXISTS `newsletters`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `newsletters` (
  `newsletters_id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `module` varchar(255) NOT NULL,
  `date_added` datetime NOT NULL default '0000-00-00 00:00:00',
  `date_sent` datetime default NULL,
  `status` int(1) default NULL,
  `locked` int(1) default '0',
  PRIMARY KEY  (`newsletters_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `newsletters`
--

LOCK TABLES `newsletters` WRITE;
/*!40000 ALTER TABLE `newsletters` DISABLE KEYS */;
/*!40000 ALTER TABLE `newsletters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `orders` (
  `orders_id` int(11) NOT NULL auto_increment,
  `customers_id` int(11) NOT NULL default '0',
  `customers_name` varchar(64) NOT NULL,
  `customers_company` varchar(32) default NULL,
  `customers_street_address` varchar(64) NOT NULL,
  `customers_suburb` varchar(32) default NULL,
  `customers_city` varchar(32) NOT NULL,
  `customers_postcode` varchar(10) NOT NULL,
  `customers_state` varchar(32) default NULL,
  `customers_country` varchar(32) NOT NULL,
  `customers_telephone` varchar(255) NOT NULL,
  `customers_email_address` varchar(96) NOT NULL,
  `customers_address_format_id` int(5) NOT NULL default '0',
  `delivery_name` varchar(64) NOT NULL,
  `delivery_company` varchar(32) default NULL,
  `delivery_street_address` varchar(64) NOT NULL,
  `delivery_suburb` varchar(32) default NULL,
  `delivery_city` varchar(32) NOT NULL,
  `delivery_postcode` varchar(10) NOT NULL,
  `delivery_state` varchar(32) default NULL,
  `delivery_country` varchar(32) NOT NULL,
  `delivery_address_format_id` int(5) NOT NULL default '0',
  `billing_name` varchar(64) NOT NULL,
  `billing_company` varchar(32) default NULL,
  `billing_type` enum('company','private') default NULL,
  `billing_cf` varchar(16) default NULL,
  `billing_company_cf` varchar(16) default NULL,
  `billing_piva` varchar(11) default NULL,
  `billing_street_address` varchar(64) NOT NULL,
  `billing_suburb` varchar(32) default NULL,
  `billing_city` varchar(32) NOT NULL,
  `billing_postcode` varchar(10) NOT NULL,
  `billing_state` varchar(32) default NULL,
  `billing_country` varchar(32) NOT NULL,
  `billing_address_format_id` int(5) NOT NULL default '0',
  `payment_method` varchar(255) NOT NULL,
  `cc_type` varchar(20) default NULL,
  `cc_owner` varchar(64) default NULL,
  `cc_number` varchar(32) default NULL,
  `cc_expires` varchar(4) default NULL,
  `last_modified` datetime default NULL,
  `date_purchased` datetime default NULL,
  `orders_status` int(5) NOT NULL default '1',
  `orders_date_finished` datetime default NULL,
  `currency` char(3) default NULL,
  `currency_value` decimal(14,6) default NULL,
  `Ordine_effetuato_da` varchar(255) default NULL,
  `Vs_rif_ordine` varchar(255) default NULL,
  `customers_group_id` int(12) NOT NULL default '0',
  `customers_code` varchar(50) default NULL,
  PRIMARY KEY  (`orders_id`),
  KEY `idx_orders_customers_id` (`customers_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders_products`
--

DROP TABLE IF EXISTS `orders_products`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `orders_products` (
  `orders_products_id` int(11) NOT NULL auto_increment,
  `orders_id` int(11) NOT NULL default '0',
  `products_id` int(11) NOT NULL default '0',
  `products_model` varchar(12) default NULL,
  `products_name` varchar(64) NOT NULL,
  `products_price` decimal(15,4) NOT NULL default '0.0000',
  `pws_price_resume` blob,
  `final_price` decimal(15,4) NOT NULL default '0.0000',
  `products_tax` decimal(7,4) NOT NULL default '0.0000',
  `products_quantity` int(2) NOT NULL default '0',
  `products_stock_attributes` varchar(255) default NULL,
  PRIMARY KEY  (`orders_products_id`),
  KEY `idx_orders_products_orders_id` (`orders_id`),
  KEY `idx_orders_products_products_id` (`products_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `orders_products`
--

LOCK TABLES `orders_products` WRITE;
/*!40000 ALTER TABLE `orders_products` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders_products_attributes`
--

DROP TABLE IF EXISTS `orders_products_attributes`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `orders_products_attributes` (
  `orders_products_attributes_id` int(11) NOT NULL auto_increment,
  `orders_id` int(11) NOT NULL default '0',
  `orders_products_id` int(11) NOT NULL default '0',
  `products_options` varchar(32) NOT NULL,
  `products_options_values` varchar(32) NOT NULL,
  `options_values_price` decimal(15,4) NOT NULL default '0.0000',
  `price_prefix` char(1) NOT NULL,
  PRIMARY KEY  (`orders_products_attributes_id`),
  KEY `idx_orders_products_att_orders_id` (`orders_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `orders_products_attributes`
--

LOCK TABLES `orders_products_attributes` WRITE;
/*!40000 ALTER TABLE `orders_products_attributes` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders_products_attributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders_products_download`
--

DROP TABLE IF EXISTS `orders_products_download`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `orders_products_download` (
  `orders_products_download_id` int(11) NOT NULL auto_increment,
  `orders_id` int(11) NOT NULL default '0',
  `orders_products_id` int(11) NOT NULL default '0',
  `orders_products_filename` varchar(255) NOT NULL,
  `download_maxdays` int(2) NOT NULL default '0',
  `download_count` int(2) NOT NULL default '0',
  PRIMARY KEY  (`orders_products_download_id`),
  KEY `idx_orders_products_download_orders_id` (`orders_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `orders_products_download`
--

LOCK TABLES `orders_products_download` WRITE;
/*!40000 ALTER TABLE `orders_products_download` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders_products_download` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders_status`
--

DROP TABLE IF EXISTS `orders_status`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `orders_status` (
  `orders_status_id` int(11) NOT NULL auto_increment,
  `language_id` int(11) NOT NULL default '1',
  `orders_status_name` varchar(32) NOT NULL,
  `public_flag` int(11) default '1',
  `downloads_flag` int(11) default '0',
  PRIMARY KEY  (`orders_status_id`,`language_id`),
  KEY `idx_orders_status_name` (`orders_status_name`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `orders_status`
--

LOCK TABLES `orders_status` WRITE;
/*!40000 ALTER TABLE `orders_status` DISABLE KEYS */;
INSERT INTO `orders_status` VALUES (1,4,'Inoltrato',1,0),(1,5,'Inoltrato',1,0),(2,4,'In lavorazione',1,0),(2,5,'In lavorazione',1,0),(3,4,'Spedito',1,0),(3,5,'Spedito',1,0),(4,4,'PayPal-Completed',1,0),(5,5,'PayPal-Completed',1,0),(6,4,'PayPal-Denied',1,0),(7,5,'PayPal-Denied',1,0),(8,4,'PayPal-Expired',1,0),(9,5,'PayPal-Expired',1,0),(10,4,'PayPal-Failed',1,0),(11,5,'PayPal-Failed',1,0),(12,4,'PayPal-None',1,0),(13,5,'PayPal-None',1,0),(14,4,'PayPal-Pending',1,0),(15,5,'PayPal-Pending',1,0),(16,4,'PayPal-Processed',1,0),(17,5,'PayPal-Processed',1,0),(18,4,'PayPal-Refunded',1,0),(19,5,'PayPal-Refunded',1,0),(20,4,'PayPal-Reversal',1,0),(21,5,'PayPal-Reversal',1,0),(22,4,'PayPal-Reversed',1,0),(23,5,'PayPal-Reversed',1,0),(24,4,'Voided',1,0),(25,5,'Voided',1,0);
/*!40000 ALTER TABLE `orders_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders_status_history`
--

DROP TABLE IF EXISTS `orders_status_history`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `orders_status_history` (
  `orders_status_history_id` int(11) NOT NULL auto_increment,
  `orders_id` int(11) NOT NULL default '0',
  `orders_status_id` int(5) NOT NULL default '0',
  `date_added` datetime NOT NULL default '0000-00-00 00:00:00',
  `customer_notified` int(1) default '0',
  `comments` text,
  PRIMARY KEY  (`orders_status_history_id`),
  KEY `idx_orders_status_history_orders_id` (`orders_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `orders_status_history`
--

LOCK TABLES `orders_status_history` WRITE;
/*!40000 ALTER TABLE `orders_status_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders_status_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders_total`
--

DROP TABLE IF EXISTS `orders_total`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `orders_total` (
  `orders_total_id` int(10) unsigned NOT NULL auto_increment,
  `orders_id` int(11) NOT NULL default '0',
  `title` varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  `value` decimal(15,4) NOT NULL default '0.0000',
  `class` varchar(32) NOT NULL,
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`orders_total_id`),
  KEY `idx_orders_total_orders_id` (`orders_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `orders_total`
--

LOCK TABLES `orders_total` WRITE;
/*!40000 ALTER TABLE `orders_total` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders_total` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `pages` (
  `pages_id` int(11) NOT NULL auto_increment,
  `sort_order` int(3) default NULL,
  `status` int(1) NOT NULL default '1',
  `page_type` char(1) default NULL,
  PRIMARY KEY  (`pages_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,0,1,'3'),(2,2,1,'3'),(3,1,1,'3'),(5,1,1,'2'),(6,2,1,'3'),(8,4,1,'3'),(9,5,1,'3'),(10,6,1,'3');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages_description`
--

DROP TABLE IF EXISTS `pages_description`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `pages_description` (
  `id` int(11) NOT NULL auto_increment,
  `pages_id` int(11) default NULL,
  `pages_title` varchar(64) NOT NULL,
  `pages_html_text` text,
  `intorext` char(1) default NULL,
  `externallink` varchar(255) default NULL,
  `link_target` char(1) default NULL,
  `language_id` int(3) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `pages_description`
--

LOCK TABLES `pages_description` WRITE;
/*!40000 ALTER TABLE `pages_description` DISABLE KEYS */;
INSERT INTO `pages_description` VALUES (1,1,'Index','Index page for English pages...This text can be changed from the admin section...','0','','0',1),(2,1,'Index','Index page for Deutsch pages...This text can be changed from the admin section...','0','','0',2),(3,1,'Index','Index page for Espanol pages...This text can be changed from the admin section...','0','','0',3),(4,2,'Contact Us','Contact us page for english language','0','','0',1),(5,2,'Contact Us','Contact Page for Deutsch pages..This text can be changed from admin section.','0','','0',2),(6,2,'Contact Us','Contact Page for Espanol pages..This text can be changed from admin section.','0','','0',3),(7,3,'Contact Us','Chi Siamo pagina in Italiano','0','','0',1),(8,3,'About Us','About Us Deutsch','0','','0',2),(9,3,'About Us','About Us Espanol','0','','0',3),(10,1,'Index','Index page per le pagine in Italiano...Pu&ograve; essere cambiata dall\'admin....\nby Oscommerce.it','0','','0',1),(11,2,'Contattaci','Pagina Conttataci per la lingua italiana... pu&ograve; essere cambiata da amministrazione.\nby OSCommerce.it','0','','0',1),(13,5,'Contattaci','Cerca di contattarci in qualche modo!!!','0','','0',4),(14,6,'Privacy','Gentile utente, desideriamo informarLa che i Suoi dati personali - raccolti direttamente presso di Lei - non saranno mai ceduti o distribuiti a terzi ma utilizzati nel pieno rispetto dei principi fondamentali, dettati dalla direttiva 95/46/CE e dal D.Lgs. 30 giugno 2003 NÂ°196 per la protezione dei dati personali.<BR><BR><BR><B>OPERAZIONI DI TRATTAMENTO DEI DATI PERSONALI E RELATIVI SCOPI<BR></B>Di seguito, Le riepiloghiamo tutte le operazioni da noi svolte che implicano raccolta, conservazione o elaborazione dei Suoi dati personali, e gli scopi che perseguiamo con ciascuna di esse: \n<UL>\n<LI>raccolta e conservazione dei Suoi dati personali al fine dell\'invio, per posta elettronica, delle informative commerciali; \n<LI>elaborazione interna dei dati personali da Lei forniti allo scopo di definire il Suo profilo commerciale; \n<LI>utilizzo del Suo profilo commerciale per finalit&agrave; di marketing e promozionali di suo interesse; </LI></UL>\n<P><BR><B>MODALITA\' DEL TRATTAMENTO</B><BR>Il trattamento avverr&agrave; con modalit&agrave; totalmente automatizzate. La nostra societ&agrave;,&nbsp; mediante il sistema di trattamento dei dati assicura e garantisce che le informazioni trattate non comprendono argomenti riguardanti dati sensibili ai sensi dell\'art. 95/46/CE e dal D.Lgs. 30 giugno 2003 NÂ°196. Pertanto verr&agrave; escluso a priori ogni trattamento che possa riguardare direttamente o indirettamente dati sensibili. <BR><BR><B>LIBERTA\' DI RILASCIARE IL CONSENSO E CONSEGUENZE DI UN RIFIUTO</B><BR>Il conferimento dei Suoi dati &egrave; facoltativo. Tuttavia, in caso di rifiuto del consenso per gli scopi indicati, ci troveremo nell\'impossibilit&agrave; di erogarLe i servizi di informazione per i quali il consenso viene richiesto, ivi compresa la registrazione su questo sito.</P>\n<P>&nbsp;<BR><B>TITOLARE E RESPONSABILE DEL TRATTAMENTO</B><BR>Titolare del trattamento &egrave; la societ&agrave; titolare di questo sito di commercio elettronico. Responsabili del trattamento dei dati personali sono i funzionari e i soggetti addetti alla gestione dei database, in relazione al rispettivo settore di competenza. <BR><BR><B>DIRITTI DELL\'INTERESSATO</B><BR>La informiamo inoltre che ogni interessato pu&ograve; esercitare i diritti di cui all\'art.7 del D.Lgs. 30 giugno 2003 NÂ°196 che di seguito riassumiamo: (Diritto di accesso ai dati personali ed altri diritti) L\'interessato ha diritto di ottenere la conferma dell\'esistenza o meno di dati personali che lo riguardano, anche se non ancora registrati, e la loro comunicazione in forma intelligibile. L\'interessato ha diritto di ottenere l\'indicazione: dell\'origine dei dati personali; delle finalit&agrave; e modalit&agrave; del trattamento; della logica applicata in caso di trattamento effettuato con l\'ausilio di strumenti elettronici; degli estremi identificativi del titolare, dei responsabili e del rappresentante designato ai sensi dell\'articolo 5, comma 2; dei soggetti o delle categorie di soggetti ai quali i dati personali possono essere comunicati o che possono venirne a conoscenza in qualit&agrave; di rappresentante designato nel territorio dello Stato, di responsabili o incaricati. L\'interessato ha diritto di ottenere: l\'aggiornamento, la rettificazione ovvero, quando vi ha interesse, l\'integrazione dei dati; la cancellazione, la trasformazione in forma anonima o il blocco dei dati trattati in violazione di legge, compresi quelli di cui non &egrave; necessaria la conservazione in relazione agli scopi per i quali i dati sono stati raccolti o successivamente trattati; l\'attestazione che le operazioni di cui alle lettere a) e b) sono state portate a conoscenza, anche per quanto riguarda il loro contenuto, di coloro ai quali i dati sono stati comunicati o diffusi, eccettuato il caso in cui tale adempimento si rivela impossibile o comporta un impiego di mezzi manifestamente sproporzionato rispetto al diritto tutelato. L\'interessato ha diritto di opporsi, in tutto o in parte: per motivi legittimi al trattamento dei dati personali che lo riguardano, ancorch&egrave; pertinenti allo scopo della raccolta; al trattamento di dati personali che lo riguardano a fini di invio di materiale pubblicitario o di vendita diretta o per il compimento di ricerche di mercato o di comunicazione commerciale.</TD> </P>','0','','0',4),(15,7,'Catalogo PDF','','1','catalogues/catalog_4.pdf','1',4),(16,6,'Privacy','','0','','0',5),(17,8,'Come Registrarsi','<P>Registrarsi su questo sito di Commercio Elettronico&nbsp;&egrave; semplice e veloce&nbsp;e ti consentir&agrave; di aquistare i prodotti,&nbsp;avere un tuo account personale, verificare lo stato&nbsp;degli ordini, avere una rubrica di indirizzi per i&nbsp;tuoi regali,&nbsp;venire a conoscenza&nbsp;tempestivamente via email di eventuali promozioni.<BR><BR>Per iscriversi &egrave; sufficiente seguire il link \"Il Mio Account\"&nbsp;che trovi in home page oppure procedere all\'acquisto dopo aver inserito i prodotti nel carrello.<BR><BR>Dopo avere compilato il form di registrazione, il sistema ti invier&agrave; una e-mail di conferma.&nbsp; </P>\n<P><BR>Ãˆ comunque possibile navigare nel sito di commercio elettronico senza registrarsi. Ãˆ necessaria l\'iscrizione tuttavia per poter&nbsp;acquistare.<BR><BR>Buona Navigazione!</P>','0','','0',4),(18,8,'How To Register','','0','','0',5),(19,9,'Come Acquistare','<P>Ci sono diversi modi per trovare i prodotti e i servizi presenti nel catalogo. Puoi scegliere una delle categorie, puoi utilizzare il motore di ricerca, che ti permetter&agrave; di trovare i risultati corrispondenti alle tue necessit&agrave;. </P>\n<P>LA SCHEDA PRODOTTO </P>\n<P>Una volta cliccato su un prodotto/servizio&nbsp;visualizzerai la sua scheda. La scheda prodotto ti offre: una descrizione del prodotto, un elenco approfondito delle sue funzionalit&agrave;, la tipologia le modalit&agrave; di consegna da parte del sistema (download on-line, spedizione), lo stato della disponibilit&agrave;, il prezzo (ed eventuali sconti o offerte), le informazioni relative alla garanzia, le informazioni sul post-vendita, e&nbsp;in genere la scheda tecnica.&nbsp;In corrispondenza di ogni scheda prodotto &egrave; possibile anche leggere le recensioni sul prodotto lasciate da altri utenti del network. E\'&nbsp;possibile inoltre inviare una notifica sul prodotto ad un amico,&nbsp;e se si &egrave; registrati, sottoscrivere una newsletter sugli aggironamenti del prodotto.&nbsp;</P>\n<P>IL CARRELLO </P>\n<P>Una volta trovato e scelto il prodotto, nei modi che hai visto in precedenza, per acquistarlo non devi fare altro che cliccare sul pulsante \'Aggiungi al carrello\'. Il carrello riassume in poche righe tutte le informazioni di cui hai bisogno: l\'elenco dei prodotti scelti, la quantit&agrave;, il prezzo. Inserire un prodotto nel carrello non implica l\'acquisto. Sono disponibili le funzionalit&agrave; Svuota carrello, Aggiorna carrello e si pu&ograve; anche lasciare in stand by l\'acquisto ed effettuare il logoff. Al successivo accesso, l\'utente trover&agrave; il carrello \"pieno\", ossia con memoria delle precedenti scelte. </P>','0','','0',4),(20,9,'How to Buy','','0','','0',5),(21,10,'Pagamento e Consegna','','0','','0',4),(22,10,'Shipping and Payments','','0','','0',5);
/*!40000 ALTER TABLE `pages_description` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ppec_payer`
--

DROP TABLE IF EXISTS `ppec_payer`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ppec_payer` (
  `customers_id` int(15) default NULL,
  `payerid` varchar(15) default NULL,
  KEY `payerid` (`payerid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `ppec_payer`
--

LOCK TABLES `ppec_payer` WRITE;
/*!40000 ALTER TABLE `ppec_payer` DISABLE KEYS */;
/*!40000 ALTER TABLE `ppec_payer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ppec_transaction`
--

DROP TABLE IF EXISTS `ppec_transaction`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ppec_transaction` (
  `transactionid` varchar(50) default NULL,
  `paymentstatus` varchar(20) default NULL,
  `orders_id` int(15) default NULL,
  KEY `transactionid` (`transactionid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `ppec_transaction`
--

LOCK TABLES `ppec_transaction` WRITE;
/*!40000 ALTER TABLE `ppec_transaction` DISABLE KEYS */;
/*!40000 ALTER TABLE `ppec_transaction` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ppec_transaction_status`
--

DROP TABLE IF EXISTS `ppec_transaction_status`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ppec_transaction_status` (
  `transaction_status` varchar(20) default NULL,
  `status_id` int(15) NOT NULL default '0',
  PRIMARY KEY  (`status_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `ppec_transaction_status`
--

LOCK TABLES `ppec_transaction_status` WRITE;
/*!40000 ALTER TABLE `ppec_transaction_status` DISABLE KEYS */;
/*!40000 ALTER TABLE `ppec_transaction_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `products` (
  `products_id` int(11) NOT NULL auto_increment,
  `products_quantity` int(4) NOT NULL default '0',
  `products_model` varchar(255) default NULL,
  `products_image` varchar(255) default NULL,
  `products_price` decimal(15,4) NOT NULL default '0.0000',
  `products_date_added` datetime NOT NULL default '0000-00-00 00:00:00',
  `products_last_modified` datetime default NULL,
  `products_date_available` datetime default NULL,
  `products_weight` decimal(5,2) NOT NULL default '0.00',
  `products_status` tinyint(1) NOT NULL default '0',
  `products_onlyshow` char(1) NOT NULL default '0',
  `products_makeoffer` char(1) NOT NULL default '0',
  `products_shopwindow` char(1) NOT NULL default '0',
  `products_tax_class_id` int(11) NOT NULL default '0',
  `manufacturers_id` int(11) default NULL,
  `products_ordered` int(11) NOT NULL default '0',
  `EAN` varchar(24) default NULL,
  `link` varchar(255) default NULL,
  `vpn` varchar(64) default NULL,
  PRIMARY KEY  (`products_id`),
  KEY `idx_products_date_added` (`products_date_added`),
  KEY `idx_products_model` (`products_model`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,32,'MG200MMS','matrox/mg200mms.gif','299.9900','2005-03-08 03:43:29','2007-10-23 16:49:59','0000-00-00 00:00:00','0.25',1,'0','0','1',1,1,0,NULL,NULL,NULL),(2,32,'MG400-32MB','matrox/mg400-32mb.gif','499.9900','2005-03-08 03:43:29','2006-01-21 15:55:06','0000-00-00 00:00:00','0.10',1,'0','0','0',1,1,0,NULL,NULL,NULL),(3,2,'MSIMPRO','microsoft/msimpro.gif','49.9900','2005-03-08 03:43:29','2006-01-21 05:10:18',NULL,'7.00',1,'0','0','0',1,3,0,NULL,NULL,NULL),(4,13,'DVD-RPMK','dvd/replacement_killers.gif','42.0000','2005-03-08 03:43:29',NULL,'0000-00-00 00:00:00','23.00',1,'0','0','0',1,2,0,NULL,NULL,NULL),(5,17,'DVD-BLDRNDC','dvd/blade_runner.gif','35.9900','2005-03-08 03:43:29','2007-10-23 16:49:59','0000-00-00 00:00:00','7.00',1,'0','0','1',1,3,0,NULL,NULL,NULL),(6,10,'DVD-MATR','dvd/the_matrix.gif','39.9900','2005-03-08 03:43:29',NULL,'0000-00-00 00:00:00','7.00',1,'0','0','0',1,3,0,NULL,NULL,NULL),(7,10,'DVD-YGEM','dvd/youve_got_mail.gif','34.9900','2005-03-08 03:43:29',NULL,'0000-00-00 00:00:00','7.00',1,'0','0','0',1,3,0,NULL,NULL,NULL),(8,10,'DVD-ABUG','dvd/a_bugs_life.gif','35.9900','2005-03-08 03:43:29',NULL,'0000-00-00 00:00:00','7.00',1,'0','0','0',1,3,0,NULL,NULL,NULL),(9,10,'DVD-UNSG','dvd/under_siege.gif','29.9900','2005-03-08 03:43:29','2007-10-23 16:49:59','0000-00-00 00:00:00','7.00',1,'0','0','1',1,3,0,NULL,NULL,NULL),(10,10,'DVD-UNSG2','dvd/under_siege2.gif','29.9900','2005-03-08 03:43:29',NULL,'0000-00-00 00:00:00','7.00',1,'0','0','0',1,3,0,NULL,NULL,NULL),(11,10,'DVD-FDBL','dvd/fire_down_below.gif','29.9900','2005-03-08 03:43:29',NULL,'0000-00-00 00:00:00','7.00',1,'0','0','0',1,3,0,NULL,NULL,NULL),(12,10,'DVD-DHWV','dvd/die_hard_3.gif','39.9900','2005-03-08 03:43:29','2007-10-23 16:49:59',NULL,'7.00',1,'0','0','1',1,4,0,NULL,NULL,NULL),(13,10,'DVD-LTWP','dvd/lethal_weapon.gif','34.9900','2005-03-08 03:43:29',NULL,'0000-00-00 00:00:00','7.00',1,'0','0','0',1,3,0,NULL,NULL,NULL),(14,10,'DVD-REDC','dvd/red_corner.gif','32.0000','2005-03-08 03:43:29',NULL,'0000-00-00 00:00:00','7.00',1,'0','0','0',1,3,0,NULL,NULL,NULL),(15,10,'DVD-FRAN','dvd/frantic.gif','35.0000','2005-03-08 03:43:29','2007-10-23 16:49:59','0000-00-00 00:00:00','7.00',1,'0','0','1',1,3,0,NULL,NULL,NULL),(16,10,'DVD-CUFI','dvd/courage_under_fire.gif','38.9900','2005-03-08 03:43:29',NULL,'0000-00-00 00:00:00','7.00',1,'0','0','0',1,4,0,NULL,NULL,NULL),(17,10,'DVD-SPEED','dvd/speed.gif','39.9900','2005-03-08 03:43:29',NULL,'0000-00-00 00:00:00','7.00',1,'0','0','0',1,4,0,NULL,NULL,NULL),(18,10,'DVD-SPEED2','dvd/speed_2.gif','42.0000','2005-03-08 03:43:29',NULL,'0000-00-00 00:00:00','7.00',1,'0','0','0',1,4,0,NULL,NULL,NULL),(19,10,'DVD-TSAB','dvd/theres_something_about_mary.gif','49.9900','2005-03-08 03:43:29',NULL,'0000-00-00 00:00:00','7.00',1,'0','0','0',1,4,0,NULL,NULL,NULL),(20,10,'DVD-BELOVED','dvd/beloved.gif','54.9900','2005-03-08 03:43:29',NULL,'0000-00-00 00:00:00','7.00',1,'0','0','0',1,3,0,NULL,NULL,NULL),(21,16,'PC-SWAT3','sierra/swat_3.gif','79.9900','2005-03-08 03:43:29',NULL,'0000-00-00 00:00:00','7.00',1,'0','0','0',1,7,0,NULL,NULL,NULL),(22,13,'PC-UNTM','gt_interactive/unreal_tournament.gif','89.9900','2005-03-08 03:43:29',NULL,'0000-00-00 00:00:00','7.00',1,'0','0','0',1,8,0,NULL,NULL,NULL),(23,16,'PC-TWOF','gt_interactive/wheel_of_time.gif','99.9900','2005-03-08 03:43:29',NULL,'0000-00-00 00:00:00','10.00',1,'0','0','0',1,8,0,NULL,NULL,NULL),(24,17,'PC-DISC','gt_interactive/disciples.gif','90.0000','2005-03-08 03:43:29',NULL,'0000-00-00 00:00:00','8.00',1,'0','0','0',1,8,0,NULL,NULL,NULL),(25,16,'MSINTKB','microsoft/intkeyboardps2.gif','69.9900','2005-03-08 03:43:29',NULL,'0000-00-00 00:00:00','8.00',1,'0','0','0',1,2,0,NULL,NULL,NULL),(26,10,'MSIMEXP','microsoft/imexplorer.gif','64.9500','2005-03-08 03:43:29','2006-01-21 00:35:38',NULL,'8.00',1,'0','0','0',1,2,0,NULL,NULL,NULL),(27,8,'HPLJ1100XI','hewlett_packard/lj1100xi.gif','499.9900','2005-03-08 03:43:29','2006-01-21 00:32:12',NULL,'45.00',1,'0','0','0',1,9,0,NULL,NULL,NULL),(28,100,'msmouse','imexplorer.gif','10.0000','2005-03-08 04:19:41',NULL,NULL,'0.50',1,'0','0','0',1,2,0,NULL,NULL,NULL);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products_attributes`
--

DROP TABLE IF EXISTS `products_attributes`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `products_attributes` (
  `products_attributes_id` int(11) NOT NULL auto_increment,
  `products_id` int(11) NOT NULL default '0',
  `options_id` int(11) NOT NULL default '0',
  `options_values_id` int(11) NOT NULL default '0',
  `options_values_price` decimal(15,4) NOT NULL default '0.0000',
  `price_prefix` char(1) NOT NULL,
  `products_options_sort_order` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`products_attributes_id`),
  KEY `idx_products_attributes_products_id` (`products_id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `products_attributes`
--

LOCK TABLES `products_attributes` WRITE;
/*!40000 ALTER TABLE `products_attributes` DISABLE KEYS */;
INSERT INTO `products_attributes` VALUES (1,1,4,1,'0.0000','+',0),(2,1,4,2,'50.0000','+',0),(3,1,4,3,'70.0000','+',0),(4,1,3,5,'0.0000','+',0),(5,1,3,6,'100.0000','+',0),(6,2,4,3,'10.0000','-',0),(7,2,4,4,'0.0000','+',0),(8,2,3,6,'0.0000','+',0),(9,2,3,7,'120.0000','+',0),(10,26,3,8,'0.0000','+',0),(11,26,3,9,'6.0000','+',0),(26,22,5,10,'0.0000','+',0),(27,22,5,13,'0.0000','+',0);
/*!40000 ALTER TABLE `products_attributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products_attributes_download`
--

DROP TABLE IF EXISTS `products_attributes_download`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `products_attributes_download` (
  `products_attributes_id` int(11) NOT NULL default '0',
  `products_attributes_filename` varchar(255) NOT NULL,
  `products_attributes_maxdays` int(2) default '0',
  `products_attributes_maxcount` int(2) default '0',
  PRIMARY KEY  (`products_attributes_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `products_attributes_download`
--

LOCK TABLES `products_attributes_download` WRITE;
/*!40000 ALTER TABLE `products_attributes_download` DISABLE KEYS */;
INSERT INTO `products_attributes_download` VALUES (26,'unreal.zip',7,3);
/*!40000 ALTER TABLE `products_attributes_download` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products_description`
--

DROP TABLE IF EXISTS `products_description`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `products_description` (
  `products_id` int(11) NOT NULL auto_increment,
  `language_id` int(11) NOT NULL default '1',
  `products_name` varchar(155) default NULL,
  `products_description` text,
  `products_url` varchar(255) default NULL,
  `products_seo_url` varchar(255) default NULL,
  `products_youtube_url` varchar(255) default NULL,
  `products_viewed` int(5) default '0',
  PRIMARY KEY  (`products_id`,`language_id`),
  KEY `products_name` (`products_name`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `products_description`
--

LOCK TABLES `products_description` WRITE;
/*!40000 ALTER TABLE `products_description` DISABLE KEYS */;
INSERT INTO `products_description` VALUES (1,2,'Matrox G200 MMS','<b>UnterstÃ¼tzung fÃ¼r zwei bzw. vier analoge oder digitale Monitore</b><br><br>\nDie Matrox G200 Multi-Monitor-Serie fÃ¼hrt die bewÃ¤hrte Matrox Tradition im Multi-Monitor- Bereich fort und bietet flexible und fortschrittliche LÃ¶sungen.Matrox stellt als erstes Unternehmen einen vierfachen digitalen PanelLinkÂ® DVI Flachbildschirm Ausgang zur VerfÃ¼gung. Mit den optional erhÃ¤ltlichen TV Tuner und Video-Capture MÃ¶glichkeiten stellt die Matrox G200 MMS eine alles umfassende Mehrschirm-LÃ¶sung dar.<br><br>\n<b>Leistungsmerkmale:</b>\n<ul>\n<li>PreisgekrÃ¶nter Matrox G200 128-Bit Grafikchip</li>\n<li>Schneller 8 MB SGRAM-Speicher pro Kanal</li>\n<li>Integrierter, leistungsstarker 250 MHz RAMDAC</li>\n<li>UnterstÃ¼tzung fÃ¼r bis zu 16 Bildschirme Ã¼ber 4 Quad-Karten (unter Win NT,ab Treiber 4.40)</li>\n<li>UnterstÃ¼tzung von 9 Monitoren unter Win 98</li>\n<li>2 bzw. 4 analoge oder digitale AusgabekanÃ¤le pro PCI-Karte</li>\n<li>Desktop-Darstellung von 1800 x 1440 oder 1920 x 1200 pro Chip</li>\n<li>AnschluÃŸmÃ¶glichkeit an einen 15-poligen VGA-Monitor oder an einen 30-poligen digitalen DVI-Flachbildschirm plus integriertem Composite-Video-Eingang (bei der TV-Version)</li>\n<li>PCI Grafikkarte, kurze BaulÃ¤nge</li>\n<li>TreiberunterstÃ¼tzung: WindowsÂ® 2000, Windows NTÂ® und WindowsÂ® 98</li>\n<li>Anwendungsbereiche: BÃ¶rsenumgebung mit zeitgleich groÃŸem Visualisierungsbedarf, VideoÃ¼berwachung, Video-Conferencing</li>\n</ul>','www.matrox.com/mga/deutsch/products/g200_mms/home.cfm',NULL,NULL,0),(1,3,'Matrox G200 MMS','Reinforcing its position as a multi-monitor trailblazer, Matrox Graphics Inc. has once again developed the most flexible and highly advanced solution in the industry. Introducing the new Matrox G200 Multi-Monitor Series; the first graphics card ever to support up to four DVI digital flat panel displays on a single 8&quot; PCI board.<br><br>With continuing demand for digital flat panels in the financial workplace, the Matrox G200 MMS is the ultimate in flexible solutions. The Matrox G200 MMS also supports the new digital video interface (DVI) created by the Digital Display Working Group (DDWG) designed to ease the adoption of digital flat panels. Other configurations include composite video capture ability and onboard TV tuner, making the Matrox G200 MMS the complete solution for business needs.<br><br>Based on the award-winning MGA-G200 graphics chip, the Matrox G200 Multi-Monitor Series provides superior 2D/3D graphics acceleration to meet the demanding needs of business applications such as real-time stock quotes (Versus), live video feeds (Reuters & Bloombergs), multiple windows applications, word processing, spreadsheets and CAD.','www.matrox.com/mga/products/g200_mms/home.cfm',NULL,NULL,0),(1,4,'Matrox G200 MMS','Reinforcing its position as a multi-monitor trailblazer, Matrox Graphics Inc. has once again developed the most flexible and highly advanced solution in the industry. Introducing the new Matrox G200 Multi-Monitor Series; the first graphics card ever to support up to four DVI digital flat panel displays on a single 8&quot; PCI board.<br><br>With continuing demand for digital flat panels in the financial workplace, the Matrox G200 MMS is the ultimate in flexible solutions. The Matrox G200 MMS also supports the new digital video interface (DVI) created by the Digital Display Working Group (DDWG) designed to ease the adoption of digital flat panels. Other configurations include composite video capture ability and onboard TV tuner, making the Matrox G200 MMS the complete solution for business needs.<br><br>Based on the award-winning MGA-G200 graphics chip, the Matrox G200 Multi-Monitor Series provides superior 2D/3D graphics acceleration to meet the demanding needs of business applications such as real-time stock quotes (Versus), live video feeds (Reuters & Bloombergs), multiple windows applications, word processing, spreadsheets and CAD.','www.matrox.com/mga/products/g200_mms/home.cfm',NULL,NULL,12),(1,5,'Matrox G200 MMS','Reinforcing its position as a multi-monitor trailblazer, Matrox Graphics Inc. has once again developed the most flexible and highly advanced solution in the industry. Introducing the new Matrox G200 Multi-Monitor Series; the first graphics card ever to support up to four DVI digital flat panel displays on a single 8&quot; PCI board.<br><br>With continuing demand for digital flat panels in the financial workplace, the Matrox G200 MMS is the ultimate in flexible solutions. The Matrox G200 MMS also supports the new digital video interface (DVI) created by the Digital Display Working Group (DDWG) designed to ease the adoption of digital flat panels. Other configurations include composite video capture ability and onboard TV tuner, making the Matrox G200 MMS the complete solution for business needs.<br><br>Based on the award-winning MGA-G200 graphics chip, the Matrox G200 Multi-Monitor Series provides superior 2D/3D graphics acceleration to meet the demanding needs of business applications such as real-time stock quotes (Versus), live video feeds (Reuters & Bloombergs), multiple windows applications, word processing, spreadsheets and CAD.','www.matrox.com/mga/products/g200_mms/home.cfm',NULL,NULL,0),(2,2,'Matrox G400 32 MB','<b>Neu! Matrox G400 &quot;all inclusive&quot; und vieles mehr...</b><br><br>\nDie neue Millennium G400-Serie - Hochleistungsgrafik mit dem sensationellen Unterschied. Ausgestattet mit dem neu eingefÃ¼hrten Matrox G400 Grafikchip, bietet die Millennium G400-Serie eine Ã¼berragende Beschleunigung inklusive bisher nie dagewesener Bildqualitat und enorm flexibler Darstellungsoptionen bei allen Ihren 3D-, 2D- und DVD-Anwendungen.<br><br>\n<ul>\n<li>DualHead Display und TV-Ausgang</li>\n<li>Environment Mapped Bump Mapping</li>\n<li>Matrox G400 256-Bit DualBus</li>\n<li>3D Rendering Array Prozessor</li>\n<li>Vibrant Color QualityÂ² (VCQÂ²)</li>\n<li>UltraSharp DAC</li>\n</ul>\n<i>&quot;Bleibt abschlieÃŸend festzustellen, daÃŸ die Matrox Millennium G400 Max als Allroundkarte fÃ¼r den Highend-PC derzeit unerreicht ist. Das ergibt den Testsieg und unsere wÃ¤rmste Empfehlung.&quot;</i><br>\n<b>GameStar 8/99 (S.184)</b>','www.matrox.com/mga/deutsch/products/mill_g400/home.cfm',NULL,NULL,0),(2,3,'Matrox G400 32MB','<b>Dramatically Different High Performance Graphics</b><br><br>Introducing the Millennium G400 Series - a dramatically different, high performance graphics experience. Armed with the industry\'s fastest graphics chip, the Millennium G400 Series takes explosive acceleration two steps further by adding unprecedented image quality, along with the most versatile display options for all your 3D, 2D and DVD applications. As the most powerful and innovative tools in your PC\'s arsenal, the Millennium G400 Series will not only change the way you see graphics, but will revolutionize the way you use your computer.<br><br><b>Key features:</b><ul><li>New Matrox G400 256-bit DualBus graphics chip</li><li>Explosive 3D, 2D and DVD performance</li><li>DualHead Display</li><li>Superior DVD and TV output</li><li>3D Environment-Mapped Bump Mapping</li><li>Vibrant Color Quality rendering </li><li>UltraSharp DAC of up to 360 MHz</li><li>3D Rendering Array Processor</li><li>Support for 16 or 32 MB of memory</li></ul>','www.matrox.com/mga/products/mill_g400/home.htm',NULL,NULL,0),(2,4,'Matrox G400 32MB','<b>Dramatically Different High Performance Graphics</b><br><br>Introducing the Millennium G400 Series - a dramatically different, high performance graphics experience. Armed with the industry\'s fastest graphics chip, the Millennium G400 Series takes explosive acceleration two steps further by adding unprecedented image quality, along with the most versatile display options for all your 3D, 2D and DVD applications. As the most powerful and innovative tools in your PC\'s arsenal, the Millennium G400 Series will not only change the way you see graphics, but will revolutionize the way you use your computer.<br><br><b>Key features:</b><ul><li>New Matrox G400 256-bit DualBus graphics chip</li><li>Explosive 3D, 2D and DVD performance</li><li>DualHead Display</li><li>Superior DVD and TV output</li><li>3D Environment-Mapped Bump Mapping</li><li>Vibrant Color Quality rendering </li><li>UltraSharp DAC of up to 360 MHz</li><li>3D Rendering Array Processor</li><li>Support for 16 or 32 MB of memory</li></ul>','www.matrox.com/mga/products/mill_g400/home.htm',NULL,NULL,10),(2,5,'Matrox G400 32MB','<b>Dramatically Different High Performance Graphics</b><br><br>Introducing the Millennium G400 Series - a dramatically different, high performance graphics experience. Armed with the industry\'s fastest graphics chip, the Millennium G400 Series takes explosive acceleration two steps further by adding unprecedented image quality, along with the most versatile display options for all your 3D, 2D and DVD applications. As the most powerful and innovative tools in your PC\'s arsenal, the Millennium G400 Series will not only change the way you see graphics, but will revolutionize the way you use your computer.<br><br><b>Key features:</b><ul><li>New Matrox G400 256-bit DualBus graphics chip</li><li>Explosive 3D, 2D and DVD performance</li><li>DualHead Display</li><li>Superior DVD and TV output</li><li>3D Environment-Mapped Bump Mapping</li><li>Vibrant Color Quality rendering </li><li>UltraSharp DAC of up to 360 MHz</li><li>3D Rendering Array Processor</li><li>Support for 16 or 32 MB of memory</li></ul>','www.matrox.com/mga/products/mill_g400/home.htm',NULL,NULL,0),(3,2,'Microsoft IntelliMouse Pro','Die IntelliMouse Pro hat mit der IntelliRad-Technologie einen neuen Standard gesetzt. Anwenderfreundliche Handhabung und produktiveres Arbeiten am PC zeichnen die IntelliMouse aus. Die gewÃ¶lbte Oberseite paÃŸt sich natÃ¼rlich in die HandflÃ¤che ein, die geschwungene Form erleichtert das Bedienen der Maus. Sie ist sowohl fÃ¼r Rechts- wie auch fÃ¼r LinkshÃ¤nder geeignet. Mit dem Rad der IntelliMouse kann der Anwender einfach und komfortabel durch Dokumente navigieren.<br><br>\n<b>Eigenschaften:</b>\n<ul>\n<li><b>ANSCHLUSS:</b> PS/2</li>\n<li><b>FARBE:</b> weiÃŸ</li>\n<li><b>TECHNIK:</b> Mauskugel</li>\n<li><b>TASTEN:</b> 3 (incl. Scrollrad)</li>\n<li><b>SCROLLRAD:</b> Ja</li>\n</ul>','',NULL,NULL,0),(3,3,'Microsoft IntelliMouse Pro','Every element of IntelliMouse Pro - from its unique arched shape to the texture of the rubber grip around its base - is the product of extensive customer and ergonomic research. Microsoft\'s popular wheel control, which now allows zooming and universal scrolling functions, gives IntelliMouse Pro outstanding comfort and efficiency.','www.microsoft.com/hardware/mouse/intellimouse.asp',NULL,NULL,0),(3,4,'Microsoft IntelliMouse Pro','Every element of IntelliMouse Pro - from its unique arched shape to the texture of the rubber grip around its base - is the product of extensive customer and ergonomic research. Microsoft\'s popular wheel control, which now allows zooming and universal scrolling functions, gives IntelliMouse Pro outstanding comfort and efficiency.','www.microsoft.com/hardware/mouse/intellimouse.asp',NULL,NULL,9),(3,5,'Microsoft IntelliMouse Pro','Every element of IntelliMouse Pro - from its unique arched shape to the texture of the rubber grip around its base - is the product of extensive customer and ergonomic research. Microsoft\'s popular wheel control, which now allows zooming and universal scrolling functions, gives IntelliMouse Pro outstanding comfort and efficiency.','www.microsoft.com/hardware/mouse/intellimouse.asp',NULL,NULL,0),(4,2,'Die Ersatzkiller','Originaltitel: &quot;The Replacement Killers&quot;<br><br>\nRegional Code: 2 (Japan, Europe, Middle East, South Africa).<br>\nSprachen: English, Deutsch.<br>\nUntertitel: English, Deutsch, Spanish.<br>\nAudio: Dolby Surround 5.1.<br>\nBildformat: 16:9 Wide-Screen.<br>\nDauer: (approx) 80 minutes.<br>\nAuÃŸerdem: Interaktive Menus, Kapitelauswahl, Untertitel.<br><br>\n(USA 1998). Til Schweiger schieÃŸt auf Hongkong-Star Chow Yun-Fat (&quot;Anna und der KÃ¶nig&quot;) Â­ ein Fehler ...','www.replacement-killers.com',NULL,NULL,0),(4,3,'The Replacement Killers','Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br>Languages: English, Deutsch.<br>Subtitles: English, Deutsch, Spanish.<br>Audio: Dolby Surround 5.1.<br>Picture Format: 16:9 Wide-Screen.<br>Length: (approx) 80 minutes.<br>Other: Interactive Menus, Chapter Selection, Subtitles (more languages).','www.replacement-killers.com',NULL,NULL,0),(4,4,'The Replacement Killers','Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br>Languages: English, Deutsch.<br>Subtitles: English, Deutsch, Spanish.<br>Audio: Dolby Surround 5.1.<br>Picture Format: 16:9 Wide-Screen.<br>Length: (approx) 80 minutes.<br>Other: Interactive Menus, Chapter Selection, Subtitles (more languages).','www.replacement-killers.com',NULL,NULL,0),(4,5,'The Replacement Killers','Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br>Languages: English, Deutsch.<br>Subtitles: English, Deutsch, Spanish.<br>Audio: Dolby Surround 5.1.<br>Picture Format: 16:9 Wide-Screen.<br>Length: (approx) 80 minutes.<br>Other: Interactive Menus, Chapter Selection, Subtitles (more languages).','www.replacement-killers.com',NULL,NULL,0),(5,2,'Blade Runner - Director\'s Cut','Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br>\nSprachen: English, Deutsch.<br>\nUntertitel: English, Deutsch, Spanish.<br>\nAudio: Dolby Surround 5.1.<br>\nBildformat: 16:9 Wide-Screen.<br>\nDauer: (approx) 112 minutes.<br>\nAuÃŸerdem: Interaktive Menus, Kapitelauswahl, Untertitel.<br><br>\n<b>Sci-Fi-Klassiker, USA 1983, 112 Min.</b><br><br>\nLos Angeles ist im Jahr 2019 ein Hexenkessel. Dauerregen und Smog tauchen den Ã¼berbevÃ¶lkerten Moloch in ewige DÃ¤mmerung. Polizeigleiter schwirren durch die Luft und Ã¼berwachen das grelle Ethnogemisch, das sich am FuÃŸe 400stÃ¶ckiger Stahlbeton-Pyramiden tummelt. Der abgehalfterte Ex-Cop und \"Blade Runner\" Rick Deckard ist Spezialist fÃ¼r die Beseitigung von Replikanten, kÃ¼nstlichen Menschen, geschaffen fÃ¼r harte Arbeit auf fremden Planeten. Nur ihm kann es gelingen, vier flÃ¼chtige, hochintelligente \"Nexus 6\"-Spezialmodelle zu stellen. Die sind mit ihrem starken und brandgefÃ¤hrlichen AnfÃ¼hrer Batty auf der Suche nach ihrem SchÃ¶pfer. Er soll ihnen eine lÃ¤ngere Lebenszeit schenken. Das muÃŸ Rick Deckard verhindern.  Als sich der eiskalte JÃ¤ger in Rachel, die SekretÃ¤rin seines Auftraggebers, verliebt, gerÃ¤t sein Weltbild jedoch ins Wanken. Er entdeckt, daÃŸ sie - vielleicht wie er selbst - ein Replikant ist ...<br><br>\nDie Konfrontation des Menschen mit \"RealitÃ¤t\" und \"VirtualitÃ¤t\" und das verstrickte Spiel mit getÃ¼rkten Erinnerungen zieht sich als roter Faden durch das Werk von Autor Philip K. Dick (\"Die totale Erinnerung\"). Sein Roman \"TrÃ¤umen Roboter von elektrischen Schafen?\" liefert die Vorlage fÃ¼r diesen doppelbÃ¶digen Thriller, der den Zuschauer mit faszinierenden\nZukunftsvisionen und der gigantischen Kulisse des GroÃŸstadtmolochs gefangen nimmt.','www.bladerunner.com',NULL,NULL,0),(5,3,'Blade Runner - Director\'s Cut','Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br>Languages: English, Deutsch.<br>Subtitles: English, Deutsch, Spanish.<br>Audio: Dolby Surround 5.1.<br>Picture Format: 16:9 Wide-Screen.<br>Length: (approx) 112 minutes.<br>Other: Interactive Menus, Chapter Selection, Subtitles (more languages).','www.bladerunner.com',NULL,NULL,0),(5,4,'Blade Runner - Director\'s Cut','Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br>Languages: English, Deutsch.<br>Subtitles: English, Deutsch, Spanish.<br>Audio: Dolby Surround 5.1.<br>Picture Format: 16:9 Wide-Screen.<br>Length: (approx) 112 minutes.<br>Other: Interactive Menus, Chapter Selection, Subtitles (more languages).','www.bladerunner.com',NULL,NULL,11),(5,5,'Blade Runner - Director\'s Cut','Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br>Languages: English, Deutsch.<br>Subtitles: English, Deutsch, Spanish.<br>Audio: Dolby Surround 5.1.<br>Picture Format: 16:9 Wide-Screen.<br>Length: (approx) 112 minutes.<br>Other: Interactive Menus, Chapter Selection, Subtitles (more languages).','www.bladerunner.com',NULL,NULL,0),(6,2,'Matrix','Originaltitel: &quot;The Matrix&quot;<br><br>\nRegional Code: 2 (Japan, Europe, Middle East, South Africa).<br>\nSprachen: English, Deutsch.<br>\nUntertitel: English, Deutsch, Spanish.<br>\nAudio: Dolby Surround 5.1.<br>\nBildformat: 16:9 Wide-Screen.<br>\nDauer: (approx) 136 minuten.<br>\nAuÃŸerdem: Interaktive Menus, Kapitelauswahl, Untertitel.<br><br>\n(USA 1999) Der Geniestreich der Wachowski-BrÃ¼der. In dieser technisch perfekten Utopie kÃ¤mpft Hacker Keanu Reeves gegen die Diktatur der Maschinen...','www.whatisthematrix.com',NULL,NULL,0),(6,3,'The Matrix','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch.\n<br>\nAudio: Dolby Surround.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 131 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Making Of.','www.thematrix.com',NULL,NULL,0),(6,4,'The Matrix','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch.\n<br>\nAudio: Dolby Surround.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 131 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Making Of.','www.thematrix.com',NULL,NULL,7),(6,5,'The Matrix','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch.\n<br>\nAudio: Dolby Surround.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 131 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Making Of.','www.thematrix.com',NULL,NULL,0),(7,2,'e-m@il fÃ¼r Dich','Original: &quot;You\'ve got mail&quot;<br>\nRegional Code: 2 (Japan, Europe, Middle East, South Africa).<br>\nSprachen: English, Deutsch.<br>\nUntertitel: English, Deutsch, Spanish.<br>\nAudio: Dolby Surround 5.1.<br>\nBildformat: 16:9 Wide-Screen.<br>\nDauer: (approx) 112 minutes.<br>\nAuÃŸerdem: Interaktive Menus, Kapitelauswahl, Untertitel.<br><br>\n(USA 1998) von Nora Ephron (&qout;Schlaflos in Seattle&quot;). Meg Ryan und Tom Hanks knÃ¼pfen per E-Mail zarte Bande. Dass sie sich schon kennen, ahnen sie nicht ...','www.youvegotmail.com',NULL,NULL,0),(7,3,'You\'ve Got Mail','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch, Spanish.\n<br>\nSubtitles: English, Deutsch, Spanish, French, Nordic, Polish.\n<br>\nAudio: Dolby Digital 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 115 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','www.youvegotmail.com',NULL,NULL,0),(7,4,'You\'ve Got Mail','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch, Spanish.\n<br>\nSubtitles: English, Deutsch, Spanish, French, Nordic, Polish.\n<br>\nAudio: Dolby Digital 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 115 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','www.youvegotmail.com',NULL,NULL,0),(7,5,'You\'ve Got Mail','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch, Spanish.\n<br>\nSubtitles: English, Deutsch, Spanish, French, Nordic, Polish.\n<br>\nAudio: Dolby Digital 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 115 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','www.youvegotmail.com',NULL,NULL,0),(8,2,'Das GroÃŸe Krabbeln','Originaltitel: &quot;A Bug\'s Life&quot;<br><br>\nRegional Code: 2 (Japan, Europe, Middle East, South Africa).<br>\nSprachen: English, Deutsch.<br>\nUntertitel: English, Deutsch, Spanish.<br>\nAudio: Dolby Surround 5.1.<br>\nBildformat: 16:9 Wide-Screen.<br>\nDauer: (approx) 96 minuten.<br>\nAuÃŸerdem: Interaktive Menus, Kapitelauswahl, Untertitel.<br><br>\n(USA 1998). Ameise Flik zettelt einen Aufstand gegen gefrÃ¤ÃŸige GrashÃ¼pfer an ... Eine fantastische Computeranimation des \"Toy Story\"-Teams. ','www.abugslife.com',NULL,NULL,0),(8,3,'A Bug\'s Life','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Digital 5.1 / Dobly Surround Stereo.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 91 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','www.abugslife.com',NULL,NULL,0),(8,4,'A Bug\'s Life','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Digital 5.1 / Dobly Surround Stereo.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 91 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','www.abugslife.com',NULL,NULL,1),(8,5,'A Bug\'s Life','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Digital 5.1 / Dobly Surround Stereo.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 91 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','www.abugslife.com',NULL,NULL,0),(9,2,'Alarmstufe: Rot','Originaltitel: &quot;Under Siege&quot;<br><br>\nRegional Code: 2 (Japan, Europe, Middle East, South Africa).<br>\nSprachen: English, Deutsch.<br>\nUntertitel: English, Deutsch, Spanish.<br>\nAudio: Dolby Surround 5.1.<br>\nBildformat: 16:9 Wide-Screen.<br>\nDauer: (approx) 96 minuten.<br>\nAuÃŸerdem: Interaktive Menus, Kapitelauswahl, Untertitel.<br><br>\n<b>Actionthriller. Smutje Steven Seagal versalzt Schurke Tommy Lee Jones die Suppe</b><br><br>\nKatastrophe ahoi: Kurz vor Ausmusterung der \"U.S.S. Missouri\" kapert die High-tech-Bande von Ex-CIA-Agent Strannix (Tommy Lee Jones) das Schlachtschiff. Strannix will die Nuklearraketen des Kreuzers klauen und verscherbeln. Mit Hilfe des irren Ersten Offiziers Krill (lustig: Gary Busey) killen die Gangster den KÃ¤ptâ€™n und sperren seine Crew unter Deck. BlÃ¶d, dass sie dabei Schiffskoch Rybak (Steven Seagal) vergessen. Der Ex-ElitekÃ¤mpfer knipst einen Schurken nach dem anderen aus. Eine VerbÃ¼ndete findet er in Stripperin Jordan (Ex-\"Baywatch\"-Biene Erika Eleniak). Die sollte eigentlich aus KÃ¤ptâ€™ns Geburtstagstorte hÃ¼pfen ... Klar: Seagal ist kein Edelmime. DafÃ¼r ist Regisseur Andrew Davis (\"Auf der Flucht\") ein KÃ¶nner: Er wÃ¼rzt die Action-Orgie mit Ironie und nutzt die imposante Schiffskulisse voll aus. FÃ¼r Effekte und Ton gab es 1993 Oscar-Nominierungen. ','',NULL,NULL,0),(9,3,'Under Siege','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Surround 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 98 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,0),(9,4,'Under Siege','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Surround 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 98 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,6),(9,5,'Under Siege','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Surround 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 98 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,0),(10,2,'Alarmstufe: Rot 2','Originaltitel: &quot;Under Siege 2: Dark Territory&quot;<br><br>\nRegional Code: 2 (Japan, Europe, Middle East, South Africa).<br>\nSprachen: English, Deutsch.<br>\nUntertitel: English, Deutsch, Spanish.<br>\nAudio: Dolby Surround 5.1.<br>\nBildformat: 16:9 Wide-Screen.<br>\nDauer: (approx) 96 minuten.<br>\nAuÃŸerdem: Interaktive Menus, Kapitelauswahl, Untertitel.<br><br>\n(USA â€™95). Von einem gekaperten Zug aus Ã¼bernimmt Computerspezi Dane die Kontrolle Ã¼ber einen Kampfsatelliten und erpresst das Pentagon. Aber auch Ex-Offizier Ryback (Steven Seagal) ist im Zug ...\n','',NULL,NULL,0),(10,3,'Under Siege 2 - Dark Territory','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Surround 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 98 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,0),(10,4,'Under Siege 2 - Dark Territory','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Surround 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 98 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,0),(10,5,'Under Siege 2 - Dark Territory','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Surround 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 98 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,0),(11,2,'Fire Down Below','Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br>\nSprachen: English, Deutsch.<br>\nUntertitel: English, Deutsch, Spanish.<br>\nAudio: Dolby Surround 5.1.<br>\nBildformat: 16:9 Wide-Screen.<br>\nDauer: (approx) 96 minuten.<br>\nAuÃŸerdem: Interaktive Menus, Kapitelauswahl, Untertitel.<br><br>\nEin mysteriÃ¶ser Mordfall fÃ¼hrt den Bundesmarschall Jack Taggert in eine Kleinstadt im US-Staat Kentucky. Doch bei seinen Ermittlungen stÃ¶ÃŸt er auf eine Mauer des Schweigens. Angst beherrscht die Stadt, und alle Spuren fÃ¼hren zu dem undurchsichtigen Minen-Tycoon Orin Hanner. Offenbar werden in der friedlichen Berglandschaft gigantische Mengen GiftmÃ¼lls verschoben, mit unkalkulierbaren Risiken. Um eine Katastrophe zu verhindern, rÃ¤umt Taggert gnadenlos auf ...','',NULL,NULL,0),(11,3,'Fire Down Below','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Surround 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 100 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,0),(11,4,'Fire Down Below','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Surround 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 100 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,0),(11,5,'Fire Down Below','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Surround 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 100 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,0),(12,2,'Stirb Langsam - Jetzt Erst Recht','Originaltitel: &quot;Die Hard With A Vengeance&quot;<br><br>\nRegional Code: 2 (Japan, Europe, Middle East, South Africa).<br>\nSprachen: English, Deutsch.<br>\nUntertitel: English, Deutsch, Spanish.<br>\nAudio: Dolby Surround 5.1.<br>\nBildformat: 16:9 Wide-Screen.<br>\nDauer: (approx) 96 minuten.<br>\nAuÃŸerdem: Interaktive Menus, Kapitelauswahl, Untertitel.<br><br>\nSo explosiv, so spannend, so rasant wie nie zuvor: Bruce Willis als Detectiv John McClane in einem Action-Thriller der Superlative! Das ist heute nicht McClanes Tag. Seine Frau hat ihn verlassen, sein BoÃŸ hat ihn vom Dienst suspendiert und irgendein VerrÃ¼ckter hat ihn gerade zum Gegenspieler in einem teuflischen Spiel erkoren - und der Einsatz ist New York selbst. Ein Kaufhaus ist explodiert, doch das ist nur der Auftakt. Der geniale Superverbrecher Simon droht, die ganze Stadt StÃ¼ck fÃ¼r StÃ¼ck in die Luft zu sprengen, wenn McClane und sein Partner wider Willen seine explosiven\" RÃ¤tsel nicht lÃ¶sen. Eine mÃ¶rderische Hetzjagd quer durch New York beginnt - bis McClane merkt, daÃŸ der Bombenterror eigentlich nur ein brillantes AblenkungsmanÃ¶ver ist...!<br><i>\"Perfekt gemacht und stark besetzt. Das Action-Highlight des Jahres!\"</i> <b>(Bild)</b>','',NULL,NULL,0),(12,3,'Die Hard With A Vengeance','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Surround 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 122 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,0),(12,4,'Die Hard With A Vengeance','Regional Code: 2 (Japan, Europe, Middle East, South Africa). <BR>Languages: English, Deutsch. <BR>Subtitles: English, Deutsch, Spanish. <BR>Audio: Dolby Surround 5.1. <BR>Picture Format: 16:9 Wide-Screen. <BR>Length: (approx) 122 minutes. <BR>Other: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,5),(12,5,'Die Hard With A Vengeance','Regional Code: 2 (Japan, Europe, Middle East, South Africa). <BR>Languages: English, Deutsch. <BR>Subtitles: English, Deutsch, Spanish. <BR>Audio: Dolby Surround 5.1. <BR>Picture Format: 16:9 Wide-Screen. <BR>Length: (approx) 122 minutes. <BR>Other: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,0),(13,2,'Zwei stahlharte Profis','Originaltitel: &quot;Lethal Weapon&quot;<br><br>\nRegional Code: 2 (Japan, Europe, Middle East, South Africa).<br>\nSprachen: English, Deutsch.<br>\nUntertitel: English, Deutsch, Spanish.<br>\nAudio: Dolby Surround 5.1.<br>\nBildformat: 16:9 Wide-Screen.<br>\nDauer: (approx) 96 minuten.<br>\nAuÃŸerdem: Interaktive Menus, Kapitelauswahl, Untertitel.<br><br>\nSie sind beide Cops in L.A.. Sie haben beide in Vietnam fÃ¼r eine Spezialeinheit gekÃ¤mpft. Und sie hassen es beide, mit einem Partner arbeiten zu mÃ¼ssen. Aber sie sind Partner: Martin Riggs, der Mann mit dem Todeswunsch, fÃ¼r wen auch immer. Und Roger Murtaugh, der besonnene Polizist. Gemeinsam enttarnen sie einen gigantischen Heroinschmuggel, hinter dem sich eine Gruppe ehemaliger CIA-SÃ¶ldner verbirgt. Eine Killerbande gegen zwei Profis ...','',NULL,NULL,0),(13,3,'Lethal Weapon','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Surround 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 100 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,0),(13,4,'Lethal Weapon','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Surround 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 100 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,0),(13,5,'Lethal Weapon','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Surround 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 100 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,0),(14,2,'Labyrinth ohne Ausweg','Originaltitel: &quot;Red Corner&quot;<br><br>\nRegional Code: 2 (Japan, Europe, Middle East, South Africa).<br>\nSprachen: English, Deutsch.<br>\nUntertitel: English, Deutsch, Spanish.<br>\nAudio: Dolby Surround 5.1.<br>\nBildformat: 16:9 Wide-Screen.<br>\nDauer: (approx) 96 minuten.<br>\nAuÃŸerdem: Interaktive Menus, Kapitelauswahl, Untertitel.<br><br>\nDem Amerikaner Jack Moore wird in China der bestialische Mord an einem Fotomodel angehÃ¤ngt. Brutale GefÃ¤ngnisschergen versuchen, ihn durch Folter zu einem GestÃ¤ndnis zu zwingen. Vor Gericht fordert die Anklage die Todesstrafe - Moore\'s Schicksal scheint besiegelt. Durch einen Zufall gelingt es ihm, aus der Haft zu fliehen, doch aus der feindseligen chinesischen Hauptstadt gibt es praktisch kein Entkommen ...','',NULL,NULL,0),(14,3,'Red Corner','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Surround 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 117 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,0),(14,4,'Red Corner','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Surround 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 117 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,0),(14,5,'Red Corner','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Surround 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 117 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,0),(15,2,'Frantic','Originaltitel: &quot;Frantic&quot;<br><br>\nRegional Code: 2 (Japan, Europe, Middle East, South Africa).<br>\nSprachen: English, Deutsch.<br>\nUntertitel: English, Deutsch, Spanish.<br>\nAudio: Dolby Surround 5.1.<br>\nBildformat: 16:9 Wide-Screen.<br>\nDauer: (approx) 96 minuten.<br>\nAuÃŸerdem: Interaktive Menus, Kapitelauswahl, Untertitel.<br><br>\nEin romantischer Urlaub in Paris, der sich in einen Alptraum verwandelt. Ein Mann auf der verzweifelten Suche nach seiner entfÃ¼hrten Frau. Ein dÃ¼ster-bedrohliches Paris, in dem nur ein Mensch Licht in die tÃ¶dliche AffÃ¤re bringen kann.','',NULL,NULL,0),(15,3,'Frantic','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Surround 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 115 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,0),(15,4,'Frantic','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Surround 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 115 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,5),(15,5,'Frantic','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Surround 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 115 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,0),(16,2,'Mut Zur Wahrheit','Originaltitel: &quot;Courage Under Fire&quot;<br><br>\nRegional Code: 2 (Japan, Europe, Middle East, South Africa).<br>\nSprachen: English, Deutsch.<br>\nUntertitel: English, Deutsch, Spanish.<br>\nAudio: Dolby Surround 5.1.<br>\nBildformat: 16:9 Wide-Screen.<br>\nDauer: (approx) 96 minuten.<br>\nAuÃŸerdem: Interaktive Menus, Kapitelauswahl, Untertitel.<br><br>\nLieutenant Colonel Nathaniel Serling (Denzel Washington) lÃ¤sst wÃ¤hrend einer Schlacht im Golfkrieg versehentlich auf einen US-Panzer schieÃŸen, dessen Mannschaft dabei ums Leben kommt. Ein Jahr nach diesem Vorfall wird Serling, der mittlerweile nach Washington D.C. versetzt wurde, die Aufgabe Ã¼bertragen, sich um einen Kandidaten zu kÃ¼mmern, der wÃ¤hrend des Krieges starb und dem der hÃ¶chste militÃ¤rische Orden zuteil werden soll. Allerdings sind sowohl der Fall und als auch der betreffende Soldat ein politisch heiÃŸes Eisen #Captain Karen Walden (Meg Ryan) ist Amerikas erster weiblicher Soldat, der im Kampf getÃ¶tet wurde.<br><br>\nSerling findet schnell heraus, dass es im Fall des im felsigen Gebiet von Kuwait abgestÃ¼rzten Rettungshubschraubers Diskrepanzen gibt. In Flashbacks werden von unterschiedlichen Personen verschiedene Versionen von Waldens Taktik, die Soldaten zu retten und den Absturz zu Ã¼berleben, dargestellt (&agrave; la Kurosawas Rashomon). Genau wie in Glory erweist sich Regisseur Edward Zwicks Zusammenstellung von bekannten und unbekannten Schauspielern als die richtige Mischung. Waldens Crew ist besonders Ã¼berzeugend. Matt Damon als der SanitÃ¤ter kommt gut als der leichtfertige Typ rÃ¼ber, als er Washington seine Geschichte erzÃ¤hlt. Im Kampf ist er ein mit Fehlern behafteter, humorvoller Soldat.<br><br>\nDie erstaunlichste Arbeit in Mut zur Wahrheit leistet Lou Diamond Phillips (als der SchÃ¼tze der Gruppe), dessen Karriere sich auf dem Weg in die direkt fÃ¼r den Videomarkt produzierten Filme befand. Und dann ist da noch Ryan. Sie hat sich in dramatischen Filmen in der Vergangenheit gut behauptet (Eine fast perfekte Liebe, Ein blutiges Erbe), es aber nie geschafft, ihrem Image zu entfliehen, das sie in die Ecke der romantischen KomÃ¶die steckte. Mit gefÃ¤rbtem Haar, einem leichten Akzent und der von ihr geforderten Darstellungskunst hat sie endlich einen langlebigen dramatischen Film. Obwohl sie nur halb so oft wie Washington im Film zu sehen ist, macht ihre mutige und beeindruckend nachhaltige Darstellung Mut zur Wahrheit zu einem speziellen Film bis hin zu dessen seltsamer, aber lohnender letzter Szene.','',NULL,NULL,0),(16,3,'Courage Under Fire','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Surround 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 112 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,0),(16,4,'Courage Under Fire','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Surround 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 112 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,3),(16,5,'Courage Under Fire','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Surround 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 112 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,0),(17,2,'Speed','Originaltitel: &quot;Speed&quot;<br><br>\nRegional Code: 2 (Japan, Europe, Middle East, South Africa).<br>\nSprachen: English, Deutsch.<br>\nUntertitel: English, Deutsch, Spanish.<br>\nAudio: Dolby Surround 5.1.<br>\nBildformat: 16:9 Wide-Screen.<br>\nDauer: (approx) 96 minuten.<br>\nAuÃŸerdem: Interaktive Menus, Kapitelauswahl, Untertitel.<br><br>\nEr ist ein Cop aus der Anti-Terror-Einheit von Los Angeles. Und so ist der Alarm fÃ¼r Jack Traven nichts UngewÃ¶hnliches: Ein Terrorist will drei Millionen Dollar erpressen, oder die zufÃ¤lligen Geiseln in einem Aufzug fallen 35 Stockwerke in die Tiefe. Doch Jack schafft das UnmÃ¶gliche - die Geiseln werden gerettet und der Terrorist stirbt an seiner eigenen Bombe. Scheinbar. Denn schon wenig spÃ¤ter steht Jack (Keanu Reeves) dem Bombenexperten Payne erneut gegenÃ¼ber. Diesmal hat sich der Erpresser eine ganz perfide Mordwaffe ausgedacht: Er plaziert eine Bombe in einem Ã¶ffentlichen Bus. Der Mechanismus der Sprengladung schaltet sich automatisch ein, sobald der Bus schneller als 50 Meilen in der Stunde fÃ¤hrt und detoniert sofort, sobald die Geschwindigkeit sinkt. PlÃ¶tzlich wird fÃ¼r eine Handvoll ahnungsloser DurchschnittsbÃ¼rger der Weg zur Arbeit zum HÃ¶llentrip - und nur Jack hat ihr Leben in der Hand. Als der Busfahrer verletzt wird, Ã¼bernimmt Fahrgast Annie (Sandra Bullock) das Steuer. Doch wohin mit einem Bus, der nicht bremsen kann in der Stadt der Staus? Doch es kommt noch schlimmer: Payne (Dennis Hopper) will jetzt nicht nur seine drei Millionen Dollar. Er will Jack. Um jeden Preis.','',NULL,NULL,0),(17,3,'Speed','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Surround 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 112 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,0),(17,4,'Speed','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Surround 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 112 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,2),(17,5,'Speed','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Surround 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 112 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,0),(18,2,'Speed 2: Cruise Control','Originaltitel: &quot;Speed 2 - Cruise Control&quot;<br><br>\nRegional Code: 2 (Japan, Europe, Middle East, South Africa).<br>\nSprachen: English, Deutsch.<br>\nUntertitel: English, Deutsch, Spanish.<br>\nAudio: Dolby Surround 5.1.<br>\nBildformat: 16:9 Wide-Screen.<br>\nDauer: (approx) 96 minuten.<br>\nAuÃŸerdem: Interaktive Menus, Kapitelauswahl, Untertitel.<br><br>\nHalten Sie ihre Schwimmwesten bereit, denn die actiongeladene Fortsetzung von Speed begibt sich auf Hochseekurs. Erleben Sie Sandra Bullock erneut in ihrer Star-Rolle als Annie Porter. Die Karibik-Kreuzfahrt mit ihrem Freund Alex entwickelt sich unaufhaltsam zur rasenden Todesfahrt, als ein wahnsinniger Computer-Spezialist den Luxusliner in seine Gewalt bringt und auf einen mÃ¶rderischen ZerstÃ¶rungskurs programmiert. Eine hochexplosive Reise, bei der kein geringerer als Action-Spezialist Jan De Bont das Ruder in die Hand nimmt. Speed 2: Cruise Controll lÃ¤ÃŸt ihre Adrenalin-Wellen in rasender Geschwindigkeit ganz nach oben schnellen.','',NULL,NULL,0),(18,3,'Speed 2: Cruise Control','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Surround 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 120 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,0),(18,4,'Speed 2: Cruise Control','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Surround 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 120 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,3),(18,5,'Speed 2: Cruise Control','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Surround 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 120 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,0),(19,2,'VerrÃ¼ckt nach Mary','Originaltitel: &quot;There\'s Something About Mary&quot;<br><br>\nRegional Code: 2 (Japan, Europe, Middle East, South Africa).<br>\nSprachen: English, Deutsch.<br>\nUntertitel: English, Deutsch, Spanish.<br>\nAudio: Dolby Surround 5.1.<br>\nBildformat: 16:9 Wide-Screen.<br>\nDauer: (approx) 96 minuten.<br>\nAuÃŸerdem: Interaktive Menus, Kapitelauswahl, Untertitel.<br><br>\n13 Jahre nachdem Teds Rendezvous mit seiner angebeteten Mary in einem peinlichen Fiasko endete, trÃ¤umt er immer noch von ihr und engagiert den windigen Privatdetektiv Healy um sie aufzuspÃ¼ren. Der findet Mary in Florida und verliebt sich auf den ersten Blick in die atemberaubende Traumfrau. Um Ted als Nebenbuhler auszuschalten, tischt er ihm dicke LÃ¼gen Ã¼ber Mary auf. Ted lÃ¤ÃŸt sich jedoch nicht abschrecken, eilt nach Miami und versucht nun alles, um Healy die Balztour zu vermasseln. Doch nicht nur Healy ist verrÃ¼ckt nach Mary und Ted bekommt es mit einer ganzen Legion liebeskranker Konkurrenten zu tun ...','',NULL,NULL,0),(19,3,'There\'s Something About Mary','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Surround 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 114 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,0),(19,4,'There\'s Something About Mary','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Surround 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 114 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,3),(19,5,'There\'s Something About Mary','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Surround 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 114 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,0),(20,2,'Menschenkind','Originaltitel: &quot;Beloved&quot;<br><br>\nRegional Code: 2 (Japan, Europe, Middle East, South Africa).<br>\nSprachen: English, Deutsch.<br>\nUntertitel: English, Deutsch, Spanish.<br>\nAudio: Dolby Surround 5.1.<br>\nBildformat: 16:9 Wide-Screen.<br>\nDauer: (approx) 96 minuten.<br>\nAuÃŸerdem: Interaktive Menus, Kapitelauswahl, Untertitel.<br><br>\nDieser vielschichtige Film ist eine Arbeit, die Regisseur Jonathan Demme und dem amerikanischen Talkshow-Star Oprah Winfrey sehr am Herzen lag. Der Film deckt im Verlauf seiner dreistÃ¼ndigen Spielzeit viele Bereiche ab. Menschenkind ist teils Sklavenepos, teils Mutter-Tochter-Drama und teils Geistergeschichte.<br><br>\nDer Film fordert vom Publikum hÃ¶chste Aufmerksamkeit, angefangen bei seiner dramatischen und etwas verwirrenden Eingangssequenz, in der die Bewohner eines Hauses von einem niedertrÃ¤chtigen Ã¼bersinnlichen Angriff heimgesucht werden. Aber Demme und seine talentierte Besetzung bereiten denen, die dabei bleiben ein unvergessliches Erlebnis. Der Film folgt den Spuren von Sethe (in ihren mittleren Jahren von Oprah Winfrey dargestellt), einer ehemaligen Sklavin, die sich scheinbar ein friedliches und produktives Leben in Ohio aufgebaut hat. Aber durch den erschreckenden und sparsamen Einsatz von RÃ¼ckblenden deckt Demme, genau wie das literarische Meisterwerk von Toni Morrison, auf dem der Film basiert, langsam die Schrecken von Sethes frÃ¼herem Leben auf und das schreckliche Ereignis, dass dazu fÃ¼hrte, dass Sethes Haus von Geistern heimgesucht wird.<br><br>\nWÃ¤hrend die GrÃ¤uel der Sklaverei und das blutige Ereignis in Sethes Familie unleugbar tief beeindrucken, ist die QualitÃ¤t des Film auch in kleineren, genauso befriedigenden Details sichtbar. Die geistlich beeinflusste Musik von Rachel Portman ist gleichzeitig befreiend und bedrÃ¼ckend, und der Einblick in die afro-amerikanische Gemeinschaft nach der Sklaverei #am Beispiel eines Familienausflugs zu einem Jahrmarkt, oder dem gospelsingenden NÃ¤hkrÃ¤nzchen #machen diesen Film zu einem speziellen VergnÃ¼gen sowohl fÃ¼r den Geist als auch fÃ¼r das Herz. Die Schauspieler, besonders Kimberley Elise als Sethes kÃ¤mpfende Tochter und Thandie Newton als der mysteriÃ¶se Titelcharakter, sind sehr rÃ¼hrend. Achten Sie auch auf Danny Glover (Lethal Weapon) als Paul D.','',NULL,NULL,0),(20,3,'Beloved','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Surround 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 164 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,0),(20,4,'Beloved','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Surround 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 164 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,4),(20,5,'Beloved','Regional Code: 2 (Japan, Europe, Middle East, South Africa).\n<br>\nLanguages: English, Deutsch.\n<br>\nSubtitles: English, Deutsch, Spanish.\n<br>\nAudio: Dolby Surround 5.1.\n<br>\nPicture Format: 16:9 Wide-Screen.\n<br>\nLength: (approx) 164 minutes.\n<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).','',NULL,NULL,0),(21,2,'SWAT 3: Elite Edition','<b>KEINE KOMPROMISSE!</b><br><i>KÃ¤mpfen Sie Seite an Seite mit Ihren LAPD SWAT-Kameraden gegen das organisierte Verbrechen!</i><br><br>\nEine der realistischsten 3D-Taktiksimulationen der letzten Zeit jetzt mit Multiplayer-Modus, 5 neuen Missionen und jede Menge nÃ¼tzliche Tools!<br><br>\nLos Angeles, 2005. In wenigen Tagen steht die Unterzeichnung des Abkommens der Vereinten Nationen zur Atom-Ã„chtung durch Vertreter aller Nationen der Welt an. Radikale terroristische Vereinigungen machen sich in der ganzen Stadt breit. Verantwortlich fÃ¼r die Sicherheit der Delegierten zeichnet sich eine Eliteeinheit der LAPD: das SWAT-Team. Das Schicksal der Stadt liegt in Ihren HÃ¤nden.<br><br>\n<b>Neue Features:</b>\n<ul>\n<li>Multiplayer-Modus (Co op-Modus, Deathmatch in den bekannten Variationen)</li>\n<li>5 neue Missionen an original Ã–rtlichkeiten Las (U-Bahn, Whitman Airport, etc.)</li>\n<li>neue Charakter</li>\n<li>neue Skins</li>\n<li>neue Waffen</li>\n<li>neue Sounds</li>\n<li>verbesserte KI</li>\n<li>Tools-Package</li>\n<li>Scenario-Editor</li>\n<li>Level-Editor</li>\n</ul>\nDie dritte Folge der Bestseller-Reihe im Bereich der 3D-Echtzeit-Simulationen prÃ¤sentiert sich mit einer neuen Spielengine mit extrem ausgeprÃ¤gtem Realismusgrad. Der Spieler Ã¼bernimmt das Kommando Ã¼ber eine der besten Polizei-Spezialeinheiten oder einer der Ã¼belsten Terroristen-Gangs der Welt. Er durchlÃ¤uft - als \"Guter\" oder \"BÃ¶ser\" - eine der hÃ¤rtesten und elitÃ¤rsten Kampfausbildungen, in der er lernt, mit jeder Art von Krisensituationen umzugehen. Bei diesem Action-Abenteuer geht es um das Leben prominenter Vertreter der Vereinten Nationen und bei 16 Missionen an OriginalschauplÃ¤tzen in LA gibt die \"lebensechte\" KI den Protagonisten jeder Seite so einige harte NÃ¼sse zu knacken.','www.swat3.com',NULL,NULL,0),(21,3,'SWAT 3: Close Quarters Battle','<b>Windows 95/98</b><br><br>211 in progress with shots fired. Officer down. Armed suspects with hostages. Respond Code 3! Los Angles, 2005, In the next seven days, representatives from every nation around the world will converge on Las Angles to witness the signing of the United Nations Nuclear Abolishment Treaty. The protection of these dignitaries falls on the shoulders of one organization, LAPD SWAT. As part of this elite tactical organization, you and your team have the weapons and all the training necessary to protect, to serve, and \"When needed\" to use deadly force to keep the peace. It takes more than weapons to make it through each mission. Your arsenal includes C2 charges, flashbangs, tactical grenades. opti-Wand mini-video cameras, and other devices critical to meeting your objectives and keeping your men free of injury. Uncompromised Duty, Honor and Valor!','www.swat3.com',NULL,NULL,0),(21,4,'SWAT 3: Close Quarters Battle','<b>Windows 95/98</b><br><br>211 in progress with shots fired. Officer down. Armed suspects with hostages. Respond Code 3! Los Angles, 2005, In the next seven days, representatives from every nation around the world will converge on Las Angles to witness the signing of the United Nations Nuclear Abolishment Treaty. The protection of these dignitaries falls on the shoulders of one organization, LAPD SWAT. As part of this elite tactical organization, you and your team have the weapons and all the training necessary to protect, to serve, and \"When needed\" to use deadly force to keep the peace. It takes more than weapons to make it through each mission. Your arsenal includes C2 charges, flashbangs, tactical grenades. opti-Wand mini-video cameras, and other devices critical to meeting your objectives and keeping your men free of injury. Uncompromised Duty, Honor and Valor!','www.swat3.com',NULL,NULL,4),(21,5,'SWAT 3: Close Quarters Battle','<b>Windows 95/98</b><br><br>211 in progress with shots fired. Officer down. Armed suspects with hostages. Respond Code 3! Los Angles, 2005, In the next seven days, representatives from every nation around the world will converge on Las Angles to witness the signing of the United Nations Nuclear Abolishment Treaty. The protection of these dignitaries falls on the shoulders of one organization, LAPD SWAT. As part of this elite tactical organization, you and your team have the weapons and all the training necessary to protect, to serve, and \"When needed\" to use deadly force to keep the peace. It takes more than weapons to make it through each mission. Your arsenal includes C2 charges, flashbangs, tactical grenades. opti-Wand mini-video cameras, and other devices critical to meeting your objectives and keeping your men free of injury. Uncompromised Duty, Honor and Valor!','www.swat3.com',NULL,NULL,0),(22,2,'Unreal Tournament','2341: Die Gewalt ist eine Lebensweise, die sich ihren Weg durch die dunklen Risse der Gesellschaft bahnt. Sie bedroht die Macht und den Einfluss der regierenden Firmen, die schnellstens ein Mittel finden mÃ¼ssen, die tobenden Massen zu besÃ¤nftigen - ein profitables Mittel ... GladiatorenkÃ¤mpfe sind die LÃ¶sung. Sie sollen den Durst der Menschen nach Blut stillen und sind die perfekte Gelegenheit, die AufstÃ¤ndischen, Kriminellen und Aliens zu beseitigen, die die Firmenelite bedrohen.<br><br>\nDas Turnier war geboren - ein Kampf auf Leben und Tod. Galaxisweit live und in Farbe! KÃ¤mpfen Sie fÃ¼r Freiheit, Ruhm und Ehre. Sie mÃ¼ssen stark, schnell und geschickt sein ... oder Sie bleiben auf der Strecke.<br><br>\nKÃ¤mpfen Sie allein oder kommandieren Sie ein Team gegen Armeen unbarmherziger Krieger, die alle nur ein Ziel vor Augen haben: Die Arenen lebend zu verlassen und sich dem Grand Champion zu stellen ... um ihn dann zu bezwingen!<br><br>\n<b>Features:</b>\n<ul>\n<li>Auf dem PC mehrfach als Spiel des Jahres ausgezeichnet!</li>\n<li>Mehr als 50 faszinierende Level - verlassene Raumstationen, gotische Kathedralen und graffitibedeckte GroÃŸstÃ¤dte.</li>\n<li>Vier actionreiche Spielmodi - Deathmatch, Capture the Flag, Assault und Domination werden Ihren Adrenalinpegel in die HÃ¶he schnellen lassen.</li>\n<li>Dramatische Mehrspieler-KÃ¤mpfe mit 2, 3 und 4 Spielern, auch Ã¼ber das Netzwerk</li>\n<li>Gnadenlos aggressive Computergegner verlangen Ihnen das Ã„uÃŸerste ab.</li>\n<li>Neuartiges Benutzerinterface und verbesserte Steuerung - auch mit USB-Maus und -Tastatur spielbar.</li>\n</ul>\nDer Nachfolger des Actionhits \"Unreal\" verspricht ein leichtes, intuitives Interface, um auch Einsteigern schnellen Zugang zu den Duellen gegen die Bots zu ermÃ¶glichen. Mit diesen KI-Kriegern kann man auch Teams bilden und im umfangreichen Multiplayermodus ohne Onlinekosten in den Kampf ziehen. 35 komplett neue Arenen und das erweiterte Waffenangebot bilden dazu den wÃ¼rdigen Rahmen.','www.unrealtournament.net',NULL,NULL,0),(22,3,'Unreal Tournament','From the creators of the best-selling Unreal, comes Unreal Tournament. A new kind of single player experience. A ruthless multiplayer revolution.<br><br>This stand-alone game showcases completely new team-based gameplay, groundbreaking multi-faceted single player action or dynamic multi-player mayhem. It\'s a fight to the finish for the title of Unreal Grand Master in the gladiatorial arena. A single player experience like no other! Guide your team of \'bots\' (virtual teamates) against the hardest criminals in the galaxy for the ultimate title - the Unreal Grand Master.','www.unrealtournament.net',NULL,NULL,0),(22,4,'Unreal Tournament','From the creators of the best-selling Unreal, comes Unreal Tournament. A new kind of single player experience. A ruthless multiplayer revolution.<br><br>This stand-alone game showcases completely new team-based gameplay, groundbreaking multi-faceted single player action or dynamic multi-player mayhem. It\'s a fight to the finish for the title of Unreal Grand Master in the gladiatorial arena. A single player experience like no other! Guide your team of \'bots\' (virtual teamates) against the hardest criminals in the galaxy for the ultimate title - the Unreal Grand Master.','www.unrealtournament.net',NULL,NULL,2),(22,5,'Unreal Tournament','From the creators of the best-selling Unreal, comes Unreal Tournament. A new kind of single player experience. A ruthless multiplayer revolution.<br><br>This stand-alone game showcases completely new team-based gameplay, groundbreaking multi-faceted single player action or dynamic multi-player mayhem. It\'s a fight to the finish for the title of Unreal Grand Master in the gladiatorial arena. A single player experience like no other! Guide your team of \'bots\' (virtual teamates) against the hardest criminals in the galaxy for the ultimate title - the Unreal Grand Master.','www.unrealtournament.net',NULL,NULL,0),(23,2,'The Wheel Of Time','<b><i>\"Wheel Of Time\"(Das Rad der Zeit)</i></b> basiert auf den Fantasy-Romanen von Kultautor Robert Jordan und stellt einen einzigartigen Mix aus Strategie-, Action- und Rollenspielelementen dar. Obwohl die Welt von \"Wheel of Time\" eng an die literarische Vorlage der Romane angelehnt ist, erzÃ¤hlt das Spiel keine lineare Geschichte. Die Story entwickelt sich abhÃ¤ngig von den Aktionen der Spieler, die jeweils die Rollen der Hauptcharaktere aus dem Roman Ã¼bernehmen. Jede Figur hat den Oberbefehl Ã¼ber eine groÃŸe Gefolgschaft, militÃ¤rische Einheiten und LÃ¤ndereien. Die Spieler kÃ¶nnen ihre eigenen Festungen konstruieren, individuell ausbauen, von dort aus das umliegende Land erforschen, magische GegenstÃ¤nde sammeln oder die gegnerischen Zitadellen erstÃ¼rmen. SelbstverstÃ¤ndlich kann man sich auch Ã¼ber LAN oder Internet gegenseitig Truppen auf den Hals hetzen und die Festungen seiner Mitspieler in Schutt und Asche legen. Dreh- und Anlegepunkt von \"Wheel of Time\" ist der Kampf um die finstere Macht \"The Dark One\", die vor langer Zeit die Menschheit beinahe ins Verderben stÃ¼rzte und nur mit Hilfe gewaltiger magischer Energie verbannt werden konnte. \"The Amyrlin Seat\" und \"The Children of the Night\" kÃ¤mpfen nur gegen \"The Forsaken\" und \"The Hound\" um den Besitz des SchlÃ¼ssels zu \"Shayol Ghul\" - dem magischen Siegel, mit dessen Hilfe \"The Dark One\" seinerzeit gebannt werden konnte.<br><br>\n<b>Features:</b> \n<ul>\n<li>Ego-Shooter mit Strategie-Elementen</li>\n<li>Spielumgebung in Echtzeit-3D</li>\n<li>Konstruktion aud Ausbau der eigenen Festung</li>\n<li>Einsatz von Ã¼ber 100 Artefakten und ZaubersprÃ¼chen</li>\n<li>Single- und Multiplayermodus</li>\n</ul>\nIm Mittelpunkt steht der Kampf gegen eine finstere Macht namens The Dark One. Deren Schergen mÃ¼ssen mit dem Einsatz von Ã¼ber 100 Artefakten und Zaubereien wie Blitzschlag oder Teleportation aus dem Weg gerÃ¤umt werden. Die opulente 3D-Grafik verbindet Strategie- und Rollenspielelemente. \n\n<b>Voraussetzungen</b>\nmind. P200, 32MB RAM, 4x CD-Rom, Win95/98, DirectX 5.0 komp.Grafikkarte und Soundkarte. ','www.wheeloftime.com',NULL,NULL,0),(23,3,'The Wheel Of Time','The world in which The Wheel of Time takes place is lifted directly out of Jordan\'s pages; it\'s huge and consists of many different environments. How you navigate the world will depend largely on which game - single player or multipayer - you\'re playing. The single player experience, with a few exceptions, will see Elayna traversing the world mainly by foot (with a couple notable exceptions). In the multiplayer experience, your character will have more access to travel via Ter\'angreal, Portal Stones, and the Ways. However you move around, though, you\'ll quickly discover that means of locomotion can easily become the least of the your worries...<br><br>During your travels, you quickly discover that four locations are crucial to your success in the game. Not surprisingly, these locations are the homes of The Wheel of Time\'s main characters. Some of these places are ripped directly from the pages of Jordan\'s books, made flesh with Legend\'s unparalleled pixel-pushing ways. Other places are specific to the game, conceived and executed with the intent of expanding this game world even further. Either way, they provide a backdrop for some of the most intense first person action and strategy you\'ll have this year.','www.wheeloftime.com',NULL,NULL,0),(23,4,'The Wheel Of Time','The world in which The Wheel of Time takes place is lifted directly out of Jordan\'s pages; it\'s huge and consists of many different environments. How you navigate the world will depend largely on which game - single player or multipayer - you\'re playing. The single player experience, with a few exceptions, will see Elayna traversing the world mainly by foot (with a couple notable exceptions). In the multiplayer experience, your character will have more access to travel via Ter\'angreal, Portal Stones, and the Ways. However you move around, though, you\'ll quickly discover that means of locomotion can easily become the least of the your worries...<br><br>During your travels, you quickly discover that four locations are crucial to your success in the game. Not surprisingly, these locations are the homes of The Wheel of Time\'s main characters. Some of these places are ripped directly from the pages of Jordan\'s books, made flesh with Legend\'s unparalleled pixel-pushing ways. Other places are specific to the game, conceived and executed with the intent of expanding this game world even further. Either way, they provide a backdrop for some of the most intense first person action and strategy you\'ll have this year.','www.wheeloftime.com',NULL,NULL,0),(23,5,'The Wheel Of Time','The world in which The Wheel of Time takes place is lifted directly out of Jordan\'s pages; it\'s huge and consists of many different environments. How you navigate the world will depend largely on which game - single player or multipayer - you\'re playing. The single player experience, with a few exceptions, will see Elayna traversing the world mainly by foot (with a couple notable exceptions). In the multiplayer experience, your character will have more access to travel via Ter\'angreal, Portal Stones, and the Ways. However you move around, though, you\'ll quickly discover that means of locomotion can easily become the least of the your worries...<br><br>During your travels, you quickly discover that four locations are crucial to your success in the game. Not surprisingly, these locations are the homes of The Wheel of Time\'s main characters. Some of these places are ripped directly from the pages of Jordan\'s books, made flesh with Legend\'s unparalleled pixel-pushing ways. Other places are specific to the game, conceived and executed with the intent of expanding this game world even further. Either way, they provide a backdrop for some of the most intense first person action and strategy you\'ll have this year.','www.wheeloftime.com',NULL,NULL,0),(24,2,'Disciples: Sacred Land','Rundenbasierende Fantasy/RTS-Strategie mit gutem Design (vor allem die Levels, 4 versch. Rassen, tolle Einheiten), fantastischer AtmosphÃ¤re und exzellentem Soundtrack. Grafisch leider auf das Niveau von 1990.','www.strategyfirst.com/disciples/welcome.html',NULL,NULL,0),(24,3,'Disciples: Sacred Lands','A new age is dawning...<br><br>Enter the realm of the Sacred Lands, where the dawn of a New Age has set in motion the most momentous of wars. As the prophecies long foretold, four races now clash with swords and sorcery in a desperate bid to control the destiny of their gods. Take on the quest as a champion of the Empire, the Mountain Clans, the Legions of the Damned, or the Undead Hordes and test your faith in battles of brute force, spellbinding magic and acts of guile. Slay demons, vanquish giants and combat merciless forces of the dead and undead. But to ensure the salvation of your god, the hero within must evolve.<br><br>The day of reckoning has come... and only the chosen will survive.','',NULL,NULL,0),(24,4,'Disciples: Sacred Lands','A new age is dawning...<br><br>Enter the realm of the Sacred Lands, where the dawn of a New Age has set in motion the most momentous of wars. As the prophecies long foretold, four races now clash with swords and sorcery in a desperate bid to control the destiny of their gods. Take on the quest as a champion of the Empire, the Mountain Clans, the Legions of the Damned, or the Undead Hordes and test your faith in battles of brute force, spellbinding magic and acts of guile. Slay demons, vanquish giants and combat merciless forces of the dead and undead. But to ensure the salvation of your god, the hero within must evolve.<br><br>The day of reckoning has come... and only the chosen will survive.','',NULL,NULL,3),(24,5,'Disciples: Sacred Lands','A new age is dawning...<br><br>Enter the realm of the Sacred Lands, where the dawn of a New Age has set in motion the most momentous of wars. As the prophecies long foretold, four races now clash with swords and sorcery in a desperate bid to control the destiny of their gods. Take on the quest as a champion of the Empire, the Mountain Clans, the Legions of the Damned, or the Undead Hordes and test your faith in battles of brute force, spellbinding magic and acts of guile. Slay demons, vanquish giants and combat merciless forces of the dead and undead. But to ensure the salvation of your god, the hero within must evolve.<br><br>The day of reckoning has come... and only the chosen will survive.','',NULL,NULL,0),(25,2,'Microsoft Internet Tastatur PS/2','<i>Microsoft Internet Keyboard,Windows-Tastatur mit 10 zusÃ¤tzl. Tasten,2 selbst programmierbar, abnehmbareHandgelenkauflage. Treiber im Lieferumfang.</i><br><br>\nEin-Klick-Zugriff auf das Internet und vieles mehr! Das Internet Keyboard verfÃ¼gt Ã¼ber 10 zusÃ¤tzliche AbkÃ¼rzungstasten auf einer benutzerfreundlichen Standardtastatur, die darÃ¼ber hinaus eine abnehmbare Handballenauflage aufweist. Ãœber die AbkÃ¼rzungstasten kÃ¶nnen Sie durch das Internet surfen oder direkt von der Tastatur aus auf E-Mails zugreifen. Die IntelliType Pro-Software ermÃ¶glicht auÃŸerdem das individuelle Belegen der Tasten.','',NULL,NULL,0),(25,3,'Microsoft Internet Keyboard PS/2','The Internet Keyboard has 10 Hot Keys on a comfortable standard keyboard design that also includes a detachable palm rest. The Hot Keys allow you to browse the web, or check e-mail directly from your keyboard. The IntelliType Pro software also allows you to customize your hot keys - make the Internet Keyboard work the way you want it to!','',NULL,NULL,0),(25,4,'Microsoft Internet Keyboard PS/2','The Internet Keyboard has 10 Hot Keys on a comfortable standard keyboard design that also includes a detachable palm rest. The Hot Keys allow you to browse the web, or check e-mail directly from your keyboard. The IntelliType Pro software also allows you to customize your hot keys - make the Internet Keyboard work the way you want it to!','',NULL,NULL,2),(25,5,'Microsoft Internet Keyboard PS/2','The Internet Keyboard has 10 Hot Keys on a comfortable standard keyboard design that also includes a detachable palm rest. The Hot Keys allow you to browse the web, or check e-mail directly from your keyboard. The IntelliType Pro software also allows you to customize your hot keys - make the Internet Keyboard work the way you want it to!','',NULL,NULL,0),(26,2,'Microsof IntelliMouse Explorer','Die IntelliMouse Explorer Ã¼berzeugt durch ihr modernes Design mit silberfarbenem GehÃ¤use, sowie rot schimmernder Unter- und RÃ¼ckseite. Die neuartige IntelliEye-Technologie sorgt fÃ¼r eine noch nie dagewesene PrÃ¤zision, da statt der beweglichen Teile (zum Abtasten der BewegungsÃ¤nderungen an der Unterseite der Maus) ein optischer Sensor die Bewegungen der Maus erfaÃŸt. Das HerzstÃ¼ck der Microsoft IntelliEye-Technologie ist ein kleiner Chip, der einen optischen Sensor und einen digitalen Signalprozessor (DSP) enthÃ¤lt.<br><br>\nDa auf bewegliche Teile, die Staub, Schmutz und Fett aufnehmen kÃ¶nnen, verzichtet wurde, muÃŸ die IntelliMouse Explorer nicht mehr gereinigt werden. DarÃ¼ber hinaus arbeitet die IntelliMouse Explorer auf nahezu jeder ArbeitsoberflÃ¤che, so daÃŸ kein Mauspad mehr erforderlich ist. Mit dem Rad und zwei neuen zusÃ¤tzlichen Maustasten ermÃ¶glicht sie effizientes und komfortables Arbeiten am PC.<br><br>\n<b>Eigenschaften:</b>\n<ul>\n<li><b>ANSCHLUSS:</b> USB (PS/2-Adapter enthalten)</li>\n<li><b>FARBE:</b> metallic-grau</li>\n<li><b>TECHNIK:</b> Optisch (Akt.: ca. 1500 Bilder/s)</li>\n<li><b>TASTEN:</b> 5 (incl. Scrollrad)</li>\n<li><b>SCROLLRAD:</b> Ja</li>\n</ul>\n<i><b>BEMERKUNG:</b><br>Keine Reinigung bewegter Teile mehr notwendig, da statt der Mauskugel ein FotoempfÃ¤nger benutzt wird.</i>','',NULL,NULL,0),(26,3,'Microsoft IntelliMouse Explorer','Microsoft introduces its most advanced mouse, the IntelliMouse Explorer! IntelliMouse Explorer features a sleek design, an industrial-silver finish, a glowing red underside and taillight, creating a style and look unlike any other mouse. IntelliMouse Explorer combines the accuracy and reliability of Microsoft IntelliEye optical tracking technology, the convenience of two new customizable function buttons, the efficiency of the scrolling wheel and the comfort of expert ergonomic design. All these great features make this the best mouse for the PC!','www.microsoft.com/hardware/mouse/explorer.asp',NULL,NULL,0),(26,4,'Microsoft IntelliMouse Explorer','Microsoft introduces its most advanced mouse, the IntelliMouse Explorer! IntelliMouse Explorer features a sleek design, an industrial-silver finish, a glowing red underside and taillight, creating a style and look unlike any other mouse. IntelliMouse Explorer combines the accuracy and reliability of Microsoft IntelliEye optical tracking technology, the convenience of two new customizable function buttons, the efficiency of the scrolling wheel and the comfort of expert ergonomic design. All these great features make this the best mouse for the PC!','www.microsoft.com/hardware/mouse/explorer.asp',NULL,NULL,14),(26,5,'Microsoft IntelliMouse Explorer','Microsoft introduces its most advanced mouse, the IntelliMouse Explorer! IntelliMouse Explorer features a sleek design, an industrial-silver finish, a glowing red underside and taillight, creating a style and look unlike any other mouse. IntelliMouse Explorer combines the accuracy and reliability of Microsoft IntelliEye optical tracking technology, the convenience of two new customizable function buttons, the efficiency of the scrolling wheel and the comfort of expert ergonomic design. All these great features make this the best mouse for the PC!','www.microsoft.com/hardware/mouse/explorer.asp',NULL,NULL,0),(27,2,'Hewlett-Packard LaserJet 1100Xi','<b>HP LaserJet fÃ¼r mehr ProduktivitÃ¤t und FlexibilitÃ¤t am Arbeitsplatz</b><br><br>\nDer HP LaserJet 1100Xi Drucker verbindet exzellente LaserdruckqualitÃ¤t mit hoher Geschwindigkeit fÃ¼r mehr Effizienz.<br><br>\n<b>Zielkunden</b>\n<ul><li>Einzelanwender, die Wert auf professionelle Ausdrucke in LaserqualitÃ¤t legen und schnelle Ergebnisse auch bei komplexen Dokumenten erwarten.</li>\n<li>Der HP LaserJet 1100 sorgt mit gestochen scharfen Texten und Grafiken fÃ¼r ein professionelles Erscheinungsbild Ihrer Arbeit und Ihres Unternehmens. Selbst bei komplexen Dokumenten liefert er schnelle Ergebnisse. Andere Medien - wie z.B. Transparentfolien und BriefumschlÃ¤ge, etc. - lassen sich problemlos bedrucken. Somit ist der HP LaserJet 1100 ein Multifunktionstalent im BÃ¼roalltag.</li>\n</ul>\n<b>Eigenschaften</b>\n<ul>\n<li><b>DruckqualitÃ¤t</b> SchwarzweiÃŸ: 600 x 600 dpi</li>\n<li><b>Monatliche Druckleistung</b> Bis zu 7000 Seiten</li>\n<li><b>Speicher</b> 2 MB Standardspeicher, erweiterbar auf 18 MB</li>\n<li><b>Schnittstelle/gemeinsame Nutzung</b> Parallel, IEEE 1284-kompatibel</li>\n<li><b>SoftwarekompatibilitÃ¤t</b> DOS/Win 3.1x/9x/NT 4.0</li>\n<li><b>PapierzufÃ¼hrung</b> 125-Blatt-PapierzufÃ¼hrung</li>\n<li><b>Druckmedien</b> Normalpapier, BriefumschlÃ¤ge, Transparentfolien, kartoniertes Papier, Postkarten und Etiketten</li>\n<li><b>NetzwerkfÃ¤hig</b> Ãœber HP JetDirect PrintServer</li>\n<li><b>Lieferumfang</b> HP LaserJet 1100Xi Drucker (Lieferumfang: Drucker, Tonerkassette, 2 m Parallelkabel, Netzkabel, Kurzbedienungsanleitung, Benutzerhandbuch, CD-ROM, 3,5\"-Disketten mit WindowsÂ® 3.1x, 9x, NT 4.0 Treibern und DOS-Dienstprogrammen)</li>\n<li><b>GewÃ¤hrleistung</b> Ein Jahr</li>\n</ul>\n','www.hp.com',NULL,NULL,0),(27,3,'Hewlett Packard LaserJet 1100Xi','HP has always set the pace in laser printing technology. The new generation HP LaserJet 1100 series sets another impressive pace, delivering a stunning 8 pages per minute print speed. The 600 dpi print resolution with HP\'s Resolution Enhancement technology (REt) makes every document more professional.<br><br>Enhanced print speed and laser quality results are just the beginning. With 2MB standard memory, HP LaserJet 1100xi users will be able to print increasingly complex pages. Memory can be increased to 18MB to tackle even more complex documents with ease. The HP LaserJet 1100xi supports key operating systems including Windows 3.1, 3.11, 95, 98, NT 4.0, OS/2 and DOS. Network compatibility available via the optional HP JetDirect External Print Servers.<br><br>HP LaserJet 1100xi also features The Document Builder for the Web Era from Trellix Corp. (featuring software to create Web documents).','www.pandi.hp.com/pandi-db/prodinfo.main?product=laserjet1100',NULL,NULL,0),(27,4,'Hewlett Packard LaserJet 1100Xi','HP has always set the pace in laser printing technology. The new generation HP LaserJet 1100 series sets another impressive pace, delivering a stunning 8 pages per minute print speed. The 600 dpi print resolution with HP\'s Resolution Enhancement technology (REt) makes every document more professional.<BR><BR>Enhanced print speed and laser quality results are just the beginning. With 2MB standard memory, HP LaserJet 1100xi users will be able to print increasingly complex pages. Memory can be increased to 18MB to tackle even more complex documents with ease. The HP LaserJet 1100xi supports key operating systems including Windows 3.1, 3.11, 95, 98, NT 4.0, OS/2 and DOS. Network compatibility available via the optional HP JetDirect External Print Servers.<BR><BR>HP LaserJet 1100xi also features The Document Builder for the Web Era from Trellix Corp. (featuring software to create Web documents).','www.pandi.hp.com/pandi-db/prodinfo.main?product=laserjet1100',NULL,NULL,2),(27,5,'Hewlett Packard LaserJet 1100Xi','HP has always set the pace in laser printing technology. The new generation HP LaserJet 1100 series sets another impressive pace, delivering a stunning 8 pages per minute print speed. The 600 dpi print resolution with HP\'s Resolution Enhancement technology (REt) makes every document more professional.<BR><BR>Enhanced print speed and laser quality results are just the beginning. With 2MB standard memory, HP LaserJet 1100xi users will be able to print increasingly complex pages. Memory can be increased to 18MB to tackle even more complex documents with ease. The HP LaserJet 1100xi supports key operating systems including Windows 3.1, 3.11, 95, 98, NT 4.0, OS/2 and DOS. Network compatibility available via the optional HP JetDirect External Print Servers.<BR><BR>HP LaserJet 1100xi also features The Document Builder for the Web Era from Trellix Corp. (featuring software to create Web documents).','www.pandi.hp.com/pandi-db/prodinfo.main?product=laserjet1100',NULL,NULL,0),(28,4,'Mouse','Test prodotto','http://www.microsoft.com',NULL,NULL,10),(28,5,'Mouse','Test prodotto','http://www.microsoft.com',NULL,NULL,0);
/*!40000 ALTER TABLE `products_description` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products_groups`
--

DROP TABLE IF EXISTS `products_groups`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `products_groups` (
  `customers_group_id` smallint(5) unsigned NOT NULL default '0',
  `customers_group_discount` decimal(6,2) NOT NULL default '0.00',
  `products_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`customers_group_id`,`products_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `products_groups`
--

LOCK TABLES `products_groups` WRITE;
/*!40000 ALTER TABLE `products_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `products_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products_notifications`
--

DROP TABLE IF EXISTS `products_notifications`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `products_notifications` (
  `products_id` int(11) NOT NULL default '0',
  `customers_id` int(11) NOT NULL default '0',
  `date_added` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`products_id`,`customers_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `products_notifications`
--

LOCK TABLES `products_notifications` WRITE;
/*!40000 ALTER TABLE `products_notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `products_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products_options`
--

DROP TABLE IF EXISTS `products_options`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `products_options` (
  `products_options_id` int(11) NOT NULL default '0',
  `language_id` int(11) NOT NULL default '1',
  `products_options_name` varchar(32) NOT NULL,
  `products_options_track_stock` tinyint(4) NOT NULL default '0',
  `attribute_sort` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`products_options_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `products_options`
--

LOCK TABLES `products_options` WRITE;
/*!40000 ALTER TABLE `products_options` DISABLE KEYS */;
/*!40000 ALTER TABLE `products_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products_options_values`
--

DROP TABLE IF EXISTS `products_options_values`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `products_options_values` (
  `products_options_values_id` int(11) NOT NULL default '0',
  `language_id` int(11) NOT NULL default '1',
  `products_options_values_name` varchar(64) NOT NULL,
  `attribute_sort` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`products_options_values_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `products_options_values`
--

LOCK TABLES `products_options_values` WRITE;
/*!40000 ALTER TABLE `products_options_values` DISABLE KEYS */;
/*!40000 ALTER TABLE `products_options_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products_options_values_to_products_options`
--

DROP TABLE IF EXISTS `products_options_values_to_products_options`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `products_options_values_to_products_options` (
  `products_options_values_to_products_options_id` int(11) NOT NULL auto_increment,
  `products_options_id` int(11) NOT NULL default '0',
  `products_options_values_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`products_options_values_to_products_options_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `products_options_values_to_products_options`
--

LOCK TABLES `products_options_values_to_products_options` WRITE;
/*!40000 ALTER TABLE `products_options_values_to_products_options` DISABLE KEYS */;
INSERT INTO `products_options_values_to_products_options` VALUES (1,4,1),(2,4,2),(3,4,3),(4,4,4),(5,3,5),(6,3,6),(7,3,7),(8,3,8),(9,3,9),(10,5,10),(13,5,13);
/*!40000 ALTER TABLE `products_options_values_to_products_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products_stock`
--

DROP TABLE IF EXISTS `products_stock`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `products_stock` (
  `products_stock_id` int(11) NOT NULL auto_increment,
  `products_id` int(11) NOT NULL default '0',
  `products_stock_attributes` varchar(255) NOT NULL,
  `products_stock_quantity` int(11) NOT NULL default '0',
  PRIMARY KEY  (`products_stock_id`),
  UNIQUE KEY `idx_products_stock_attributes` (`products_id`,`products_stock_attributes`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `products_stock`
--

LOCK TABLES `products_stock` WRITE;
/*!40000 ALTER TABLE `products_stock` DISABLE KEYS */;
/*!40000 ALTER TABLE `products_stock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products_to_categories`
--

DROP TABLE IF EXISTS `products_to_categories`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `products_to_categories` (
  `products_id` int(11) NOT NULL default '0',
  `categories_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`products_id`,`categories_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `products_to_categories`
--

LOCK TABLES `products_to_categories` WRITE;
/*!40000 ALTER TABLE `products_to_categories` DISABLE KEYS */;
INSERT INTO `products_to_categories` VALUES (1,4),(2,4),(3,9),(4,10),(5,11),(6,10),(7,12),(8,13),(9,10),(10,10),(11,10),(12,10),(13,10),(14,15),(15,14),(16,15),(17,10),(18,10),(19,12),(20,15),(21,18),(22,19),(23,20),(24,20),(25,8),(26,9),(27,5),(28,9);
/*!40000 ALTER TABLE `products_to_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pws_plugins`
--

DROP TABLE IF EXISTS `pws_plugins`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `pws_plugins` (
  `plugin_id` int(11) NOT NULL auto_increment,
  `plugin_code` varchar(64) NOT NULL,
  `plugin_version` varchar(64) NOT NULL,
  `plugin_type` varchar(64) NOT NULL,
  `plugin_status` char(1) default '1',
  `plugin_sort_order` int(5) default '0',
  `plugin_date_added` datetime default NULL,
  `plugin_date_updated` datetime default NULL,
  PRIMARY KEY  (`plugin_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `pws_plugins`
--

LOCK TABLES `pws_plugins` WRITE;
/*!40000 ALTER TABLE `pws_plugins` DISABLE KEYS */;
INSERT INTO `pws_plugins` VALUES (1,'pws_prices','0.2','application','1',0,'2010-09-08 15:58:45',NULL),(2,'pws_prices_specials','0.24','prices','1',1,'2010-09-08 15:58:45',NULL),(3,'pws_html','0.72','application','1',1,'2010-09-08 15:58:45',NULL),(4,'pws_products_selector','0.2','application','1',2,'2010-09-08 15:58:45',NULL),(5,'pws_products_images','0.2','application','1',3,'2010-09-08 15:58:45',NULL);
/*!40000 ALTER TABLE `pws_plugins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pws_products_groups_status`
--

DROP TABLE IF EXISTS `pws_products_groups_status`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `pws_products_groups_status` (
  `products_id` int(11) NOT NULL default '0',
  `pws_customers_groups_status` enum('0','1') NOT NULL default '1',
  KEY `products_id` (`products_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `pws_products_groups_status`
--

LOCK TABLES `pws_products_groups_status` WRITE;
/*!40000 ALTER TABLE `pws_products_groups_status` DISABLE KEYS */;
INSERT INTO `pws_products_groups_status` VALUES (1,'1'),(2,'1'),(4,'1'),(7,'1'),(8,'1'),(9,'1'),(10,'1'),(11,'1'),(12,'1'),(13,'1'),(14,'1'),(15,'1'),(17,'1'),(18,'1'),(19,'1'),(20,'1'),(21,'1'),(22,'1'),(23,'1'),(24,'1'),(25,'1'),(26,'1'),(27,'1'),(28,'1');
/*!40000 ALTER TABLE `pws_products_groups_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pws_products_images`
--

DROP TABLE IF EXISTS `pws_products_images`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `pws_products_images` (
  `products_id` int(11) NOT NULL default '0',
  `products_image` varchar(255) NOT NULL,
  `sort_order` int(2) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `pws_products_images`
--

LOCK TABLES `pws_products_images` WRITE;
/*!40000 ALTER TABLE `pws_products_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `pws_products_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pws_related_products`
--

DROP TABLE IF EXISTS `pws_related_products`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `pws_related_products` (
  `products_id` int(11) default NULL,
  `to_products_id` int(11) default NULL,
  `prodrel_order` tinyint(4) default NULL,
  `products_id_master` smallint(5) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `pws_related_products`
--

LOCK TABLES `pws_related_products` WRITE;
/*!40000 ALTER TABLE `pws_related_products` DISABLE KEYS */;
/*!40000 ALTER TABLE `pws_related_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pws_specials`
--

DROP TABLE IF EXISTS `pws_specials`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `pws_specials` (
  `specials_id` int(11) NOT NULL default '0',
  `products_id` int(11) NOT NULL default '0',
  `pws_specials_discount` decimal(6,2) default NULL,
  UNIQUE KEY `specials_id` (`specials_id`),
  KEY `products_id` (`products_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `pws_specials`
--

LOCK TABLES `pws_specials` WRITE;
/*!40000 ALTER TABLE `pws_specials` DISABLE KEYS */;
INSERT INTO `pws_specials` VALUES (1,3,'9.99'),(2,5,'9.99'),(3,6,'9.99'),(4,16,'9.99');
/*!40000 ALTER TABLE `pws_specials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `reviews` (
  `reviews_id` int(11) NOT NULL auto_increment,
  `products_id` int(11) NOT NULL default '0',
  `customers_id` int(11) default NULL,
  `customers_name` varchar(64) NOT NULL,
  `reviews_rating` int(1) default NULL,
  `date_added` datetime default NULL,
  `last_modified` datetime default NULL,
  `reviews_read` int(5) NOT NULL default '0',
  PRIMARY KEY  (`reviews_id`),
  KEY `idx_reviews_products_id` (`products_id`),
  KEY `idx_reviews_customers_id` (`customers_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (1,19,1,'John doe',5,'2005-03-08 03:43:29','0000-00-00 00:00:00',0),(2,6,3,'Giulio D\'Ambrosio',4,'2007-10-29 15:46:51',NULL,0);
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews_description`
--

DROP TABLE IF EXISTS `reviews_description`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `reviews_description` (
  `reviews_id` int(11) NOT NULL default '0',
  `languages_id` int(11) NOT NULL default '0',
  `reviews_text` text NOT NULL,
  PRIMARY KEY  (`reviews_id`,`languages_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `reviews_description`
--

LOCK TABLES `reviews_description` WRITE;
/*!40000 ALTER TABLE `reviews_description` DISABLE KEYS */;
INSERT INTO `reviews_description` VALUES (1,1,'this has to be one of the funniest movies released for 1999!'),(2,4,'Prova recensioneProva recensioneProva recensioneProva recensioneProva recensioneProva recensioneProva recensioneProva recensioneProva recensioneProva recensioneProva recensioneProva recensioneProva recensioneProva recensioneProva recensioneProva recensioneProva recensioneProva recensioneProva recensioneProva recensioneProva recensioneProva recensioneProva recensioneProva recensioneProva recensioneProva recensioneProva recensioneProva recensioneProva recensioneProva recensione');
/*!40000 ALTER TABLE `reviews_description` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `sessions` (
  `sesskey` varchar(32) NOT NULL,
  `expiry` int(11) unsigned NOT NULL default '0',
  `value` text NOT NULL,
  PRIMARY KEY  (`sesskey`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `specials`
--

DROP TABLE IF EXISTS `specials`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `specials` (
  `specials_id` int(11) NOT NULL auto_increment,
  `products_id` int(11) NOT NULL default '0',
  `specials_new_products_price` decimal(15,4) NOT NULL default '0.0000',
  `specials_date_added` datetime default NULL,
  `specials_last_modified` datetime default NULL,
  `expires_date` datetime default NULL,
  `date_status_change` datetime default NULL,
  `status` int(1) NOT NULL default '1',
  `customers_group_id` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`specials_id`),
  KEY `idx_specials_products_id` (`products_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `specials`
--

LOCK TABLES `specials` WRITE;
/*!40000 ALTER TABLE `specials` DISABLE KEYS */;
INSERT INTO `specials` VALUES (1,3,'39.9900','2005-03-08 03:43:29','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,0),(2,5,'30.0000','2005-03-08 03:43:29','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,0),(3,6,'30.0000','2005-03-08 03:43:29','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,0),(4,16,'29.9900','2005-03-08 03:43:29','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,0);
/*!40000 ALTER TABLE `specials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `specials_retail_prices`
--

DROP TABLE IF EXISTS `specials_retail_prices`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `specials_retail_prices` (
  `products_id` int(11) NOT NULL default '0',
  `specials_new_products_price` decimal(15,4) NOT NULL default '0.0000',
  `status` tinyint(4) default NULL,
  `customers_group_id` smallint(6) default NULL,
  PRIMARY KEY  (`products_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `specials_retail_prices`
--

LOCK TABLES `specials_retail_prices` WRITE;
/*!40000 ALTER TABLE `specials_retail_prices` DISABLE KEYS */;
INSERT INTO `specials_retail_prices` VALUES (3,'39.9900',1,0),(5,'30.0000',1,0),(6,'30.0000',1,0),(16,'29.9900',1,0);
/*!40000 ALTER TABLE `specials_retail_prices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `table_specials_retail_prices`
--

DROP TABLE IF EXISTS `table_specials_retail_prices`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `table_specials_retail_prices` (
  `products_id` int(11) NOT NULL default '0',
  `specials_new_products_price` decimal(15,4) NOT NULL default '0.0000',
  `status` tinyint(4) default NULL,
  `customers_group_id` smallint(6) default NULL,
  PRIMARY KEY  (`products_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `table_specials_retail_prices`
--

LOCK TABLES `table_specials_retail_prices` WRITE;
/*!40000 ALTER TABLE `table_specials_retail_prices` DISABLE KEYS */;
INSERT INTO `table_specials_retail_prices` VALUES (3,'39.9900',1,0),(5,'30.0000',1,0),(6,'30.0000',1,0),(16,'29.9900',1,0);
/*!40000 ALTER TABLE `table_specials_retail_prices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tax_class`
--

DROP TABLE IF EXISTS `tax_class`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tax_class` (
  `tax_class_id` int(11) NOT NULL auto_increment,
  `tax_class_title` varchar(32) NOT NULL,
  `tax_class_description` varchar(255) NOT NULL,
  `last_modified` datetime default NULL,
  `date_added` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`tax_class_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tax_class`
--

LOCK TABLES `tax_class` WRITE;
/*!40000 ALTER TABLE `tax_class` DISABLE KEYS */;
INSERT INTO `tax_class` VALUES (1,'IVA 20%','IVA applicabile a beni e servizi','2005-03-08 03:48:57','2005-03-08 03:43:29');
/*!40000 ALTER TABLE `tax_class` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tax_rates`
--

DROP TABLE IF EXISTS `tax_rates`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tax_rates` (
  `tax_rates_id` int(11) NOT NULL auto_increment,
  `tax_zone_id` int(11) NOT NULL default '0',
  `tax_class_id` int(11) NOT NULL default '0',
  `tax_priority` int(5) default '1',
  `tax_rate` decimal(7,4) NOT NULL default '0.0000',
  `tax_description` varchar(255) NOT NULL,
  `last_modified` datetime default NULL,
  `date_added` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`tax_rates_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tax_rates`
--

LOCK TABLES `tax_rates` WRITE;
/*!40000 ALTER TABLE `tax_rates` DISABLE KEYS */;
INSERT INTO `tax_rates` VALUES (1,1,1,1,'20.0000','IVA 20%','2005-03-08 03:49:19','2005-03-08 03:43:29');
/*!40000 ALTER TABLE `tax_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `whos_online`
--

DROP TABLE IF EXISTS `whos_online`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `whos_online` (
  `customer_id` int(11) default NULL,
  `full_name` varchar(64) NOT NULL,
  `session_id` varchar(128) NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `time_entry` varchar(14) NOT NULL,
  `time_last_click` varchar(14) NOT NULL,
  `last_page_url` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `whos_online`
--

LOCK TABLES `whos_online` WRITE;
/*!40000 ALTER TABLE `whos_online` DISABLE KEYS */;
INSERT INTO `whos_online` VALUES (0,'Guest','m0nkbhig2riq22cnhi6rhaqbk4','78.13.228.28','1316516022','1316516022','/');
/*!40000 ALTER TABLE `whos_online` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zones`
--

DROP TABLE IF EXISTS `zones`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `zones` (
  `zone_id` int(11) NOT NULL auto_increment,
  `zone_country_id` int(11) NOT NULL default '0',
  `zone_code` varchar(32) NOT NULL,
  `zone_name` varchar(32) NOT NULL,
  PRIMARY KEY  (`zone_id`),
  KEY `idx_zones_to_geo_zones_country_id` (`zone_country_id`)
) ENGINE=MyISAM AUTO_INCREMENT=293 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `zones`
--

LOCK TABLES `zones` WRITE;
/*!40000 ALTER TABLE `zones` DISABLE KEYS */;
INSERT INTO `zones` VALUES (1,223,'AL','Alabama'),(2,223,'AK','Alaska'),(3,223,'AS','American Samoa'),(4,223,'AZ','Arizona'),(5,223,'AR','Arkansas'),(6,223,'AF','Armed Forces Africa'),(7,223,'AA','Armed Forces Americas'),(8,223,'AC','Armed Forces Canada'),(9,223,'AE','Armed Forces Europe'),(10,223,'AM','Armed Forces Middle East'),(11,223,'AP','Armed Forces Pacific'),(12,223,'CA','California'),(13,223,'CO','Colorado'),(14,223,'CT','Connecticut'),(15,223,'DE','Delaware'),(16,223,'DC','District of Columbia'),(17,223,'FM','Federated States Of Micronesia'),(18,223,'FL','Florida'),(19,223,'GA','Georgia'),(20,223,'GU','Guam'),(21,223,'HI','Hawaii'),(22,223,'ID','Idaho'),(23,223,'IL','Illinois'),(24,223,'IN','Indiana'),(25,223,'IA','Iowa'),(26,223,'KS','Kansas'),(27,223,'KY','Kentucky'),(28,223,'LA','Louisiana'),(29,223,'ME','Maine'),(30,223,'MH','Marshall Islands'),(31,223,'MD','Maryland'),(32,223,'MA','Massachusetts'),(33,223,'MI','Michigan'),(34,223,'MN','Minnesota'),(35,223,'MS','Mississippi'),(36,223,'MO','Missouri'),(37,223,'MT','Montana'),(38,223,'NE','Nebraska'),(39,223,'NV','Nevada'),(40,223,'NH','New Hampshire'),(41,223,'NJ','New Jersey'),(42,223,'NM','New Mexico'),(43,223,'NY','New York'),(44,223,'NC','North Carolina'),(45,223,'ND','North Dakota'),(46,223,'MP','Northern Mariana Islands'),(47,223,'OH','Ohio'),(48,223,'OK','Oklahoma'),(49,223,'OR','Oregon'),(50,223,'PW','Palau'),(51,223,'PA','Pennsylvania'),(52,223,'PR','Puerto Rico'),(53,223,'RI','Rhode Island'),(54,223,'SC','South Carolina'),(55,223,'SD','South Dakota'),(56,223,'TN','Tennessee'),(57,223,'TX','Texas'),(58,223,'UT','Utah'),(59,223,'VT','Vermont'),(60,223,'VI','Virgin Islands'),(61,223,'VA','Virginia'),(62,223,'WA','Washington'),(63,223,'WV','West Virginia'),(64,223,'WI','Wisconsin'),(65,223,'WY','Wyoming'),(66,38,'AB','Alberta'),(67,38,'BC','British Columbia'),(68,38,'MB','Manitoba'),(69,38,'NF','Newfoundland'),(70,38,'NB','New Brunswick'),(71,38,'NS','Nova Scotia'),(72,38,'NT','Northwest Territories'),(73,38,'NU','Nunavut'),(74,38,'ON','Ontario'),(75,38,'PE','Prince Edward Island'),(76,38,'QC','Quebec'),(77,38,'SK','Saskatchewan'),(78,38,'YT','Yukon Territory'),(79,81,'NDS','Niedersachsen'),(80,81,'BAW','Baden-WÃ¼rttemberg'),(81,81,'BAY','Bayern'),(82,81,'BER','Berlin'),(83,81,'BRG','Brandenburg'),(84,81,'BRE','Bremen'),(85,81,'HAM','Hamburg'),(86,81,'HES','Hessen'),(87,81,'MEC','Mecklenburg-Vorpommern'),(88,81,'NRW','Nordrhein-Westfalen'),(89,81,'RHE','Rheinland-Pfalz'),(90,81,'SAR','Saarland'),(91,81,'SAS','Sachsen'),(92,81,'SAC','Sachsen-Anhalt'),(93,81,'SCN','Schleswig-Holstein'),(94,81,'THE','ThÃ¼ringen'),(95,14,'WI','Wien'),(96,14,'NO','NiederÃ¶sterreich'),(97,14,'OO','OberÃ¶sterreich'),(98,14,'SB','Salzburg'),(99,14,'KN','KÃ¤rnten'),(100,14,'ST','Steiermark'),(101,14,'TI','Tirol'),(102,14,'BL','Burgenland'),(103,14,'VB','Voralberg'),(104,204,'AG','Aargau'),(105,204,'AI','Appenzell Innerrhoden'),(106,204,'AR','Appenzell Ausserrhoden'),(107,204,'BE','Bern'),(108,204,'BL','Basel-Landschaft'),(109,204,'BS','Basel-Stadt'),(110,204,'FR','Freiburg'),(111,204,'GE','Genf'),(112,204,'GL','Glarus'),(113,204,'JU','GraubÃ¼nden'),(114,204,'JU','Jura'),(115,204,'LU','Luzern'),(116,204,'NE','Neuenburg'),(117,204,'NW','Nidwalden'),(118,204,'OW','Obwalden'),(119,204,'SG','St. Gallen'),(120,204,'SH','Schaffhausen'),(121,204,'SO','Solothurn'),(122,204,'SZ','Schwyz'),(123,204,'TG','Thurgau'),(124,204,'TI','Tessin'),(125,204,'UR','Uri'),(126,204,'VD','Waadt'),(127,204,'VS','Wallis'),(128,204,'ZG','Zug'),(129,204,'ZH','ZÃ¼rich'),(130,195,'A CoruÃ±a','A CoruÃ±a'),(131,195,'Alava','Alava'),(132,195,'Albacete','Albacete'),(133,195,'Alicante','Alicante'),(134,195,'Almeria','Almeria'),(135,195,'Asturias','Asturias'),(136,195,'Avila','Avila'),(137,195,'Badajoz','Badajoz'),(138,195,'Baleares','Baleares'),(139,195,'Barcelona','Barcelona'),(140,195,'Burgos','Burgos'),(141,195,'Caceres','Caceres'),(142,195,'Cadiz','Cadiz'),(143,195,'Cantabria','Cantabria'),(144,195,'Castellon','Castellon'),(145,195,'Ceuta','Ceuta'),(146,195,'Ciudad Real','Ciudad Real'),(147,195,'Cordoba','Cordoba'),(148,195,'Cuenca','Cuenca'),(149,195,'Girona','Girona'),(150,195,'Granada','Granada'),(151,195,'Guadalajara','Guadalajara'),(152,195,'Guipuzcoa','Guipuzcoa'),(153,195,'Huelva','Huelva'),(154,195,'Huesca','Huesca'),(155,195,'Jaen','Jaen'),(156,195,'La Rioja','La Rioja'),(157,195,'Las Palmas','Las Palmas'),(158,195,'Leon','Leon'),(159,195,'Lleida','Lleida'),(160,195,'Lugo','Lugo'),(161,195,'Madrid','Madrid'),(162,195,'Malaga','Malaga'),(163,195,'Melilla','Melilla'),(164,195,'Murcia','Murcia'),(165,195,'Navarra','Navarra'),(166,195,'Ourense','Ourense'),(167,195,'Palencia','Palencia'),(168,195,'Pontevedra','Pontevedra'),(169,195,'Salamanca','Salamanca'),(170,195,'Santa Cruz de Tenerife','Santa Cruz de Tenerife'),(171,195,'Segovia','Segovia'),(172,195,'Sevilla','Sevilla'),(173,195,'Soria','Soria'),(174,195,'Tarragona','Tarragona'),(175,195,'Teruel','Teruel'),(176,195,'Toledo','Toledo'),(177,195,'Valencia','Valencia'),(178,195,'Valladolid','Valladolid'),(179,195,'Vizcaya','Vizcaya'),(180,195,'Zamora','Zamora'),(181,195,'Zaragoza','Zaragoza'),(182,105,'AG','Agrigento'),(183,105,'AL','Alessandria'),(184,105,'AN','Ancona'),(185,105,'AO','Aosta'),(186,105,'AR','Arezzo'),(187,105,'AP','Ascoli Piceno'),(188,105,'AT','Asti'),(189,105,'AV','Avellino'),(190,105,'BA','Bari'),(191,105,'BL','Belluno'),(192,105,'BN','Benevento'),(193,105,'BG','Bergamo'),(194,105,'BI','Biella'),(195,105,'BO','Bologna'),(196,105,'BZ','Bolzano'),(197,105,'BS','Brescia'),(198,105,'BR','Brindisi'),(199,105,'CA','Cagliari'),(200,105,'CL','Caltanissetta'),(201,105,'CB','Campobasso'),(202,105,'CE','Caserta'),(203,105,'CT','Catania'),(204,105,'CZ','Catanzaro'),(205,105,'CH','Chieti'),(206,105,'CO','Como'),(207,105,'CS','Cosenza'),(208,105,'CR','Cremona'),(209,105,'KR','Crotone'),(210,105,'CN','Cuneo'),(211,105,'EN','Enna'),(212,105,'FE','Ferrara'),(213,105,'FI','Firenze'),(214,105,'FG','Foggia'),(215,105,'FO','Forlï¿½'),(216,105,'FR','Frosinone'),(217,105,'GE','Genova'),(218,105,'GO','Gorizia'),(219,105,'GR','Grosseto'),(220,105,'IM','Imperia'),(221,105,'IS','Isernia'),(222,105,'AQ','Aquila'),(223,105,'SP','La Spezia'),(224,105,'LT','Latina'),(225,105,'LE','Lecce'),(226,105,'LC','Lecco'),(227,105,'LI','Livorno'),(228,105,'LO','Lodi'),(229,105,'LU','Lucca'),(230,105,'MC','Macerata'),(231,105,'MN','Mantova'),(232,105,'MS','Massa-Carrara'),(233,105,'MT','Matera'),(234,105,'ME','Messina'),(235,105,'MI','Milano'),(236,105,'MO','Modena'),(237,105,'NA','Napoli'),(238,105,'NO','Novara'),(239,105,'NU','Nuoro'),(240,105,'OR','Oristano'),(241,105,'PD','Padova'),(242,105,'PA','Palermo'),(243,105,'PR','Parma'),(244,105,'PG','Perugia'),(245,105,'PV','Pavia'),(246,105,'PS','Pesaro e Urbino'),(247,105,'PE','Pescara'),(248,105,'PC','Piacenza'),(249,105,'PI','Pisa'),(250,105,'PT','Pistoia'),(251,105,'PN','Pordenone'),(252,105,'PZ','Potenza'),(253,105,'PO','Prato'),(254,105,'RG','Ragusa'),(255,105,'RA','Ravenna'),(256,105,'RC','Reggio di Calabria'),(257,105,'RE','Reggio Emilia'),(258,105,'RI','Rieti'),(259,105,'RN','Rimini'),(260,105,'RM','Roma'),(261,105,'RO','Rovigo'),(262,105,'SA','Salerno'),(263,105,'SS','Sassari'),(264,105,'SV','Savona'),(265,105,'SI','Siena'),(266,105,'SR','Siracusa'),(267,105,'SO','Sondrio'),(268,105,'TA','Taranto'),(269,105,'TE','Teramo'),(270,105,'TR','Terni'),(271,105,'TO','Torino'),(272,105,'TP','Trapani'),(273,105,'TN','Trento'),(274,105,'TV','Treviso'),(275,105,'TS','Trieste'),(276,105,'UD','Udine'),(277,105,'VA','Varese'),(278,105,'VE','Venezia'),(279,105,'VB','Verbania'),(280,105,'VC','Vercelli'),(281,105,'VR','Verona'),(282,105,'VV','Vibo Valentia'),(283,105,'VI','Vicenza'),(284,105,'VT','Viterbo'),(285,105,'',''),(286,105,'MB','Monza Brianza'),(287,105,'BT','Barletta-Andria-Trani'),(288,105,'CI','Carbonia-Iglesias'),(289,105,'FM','Fermo'),(290,105,'OT','Olbia-Tempio'),(291,105,'VS','Medio Campidano'),(292,105,'OG','Ogliastra');
/*!40000 ALTER TABLE `zones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zones_to_geo_zones`
--

DROP TABLE IF EXISTS `zones_to_geo_zones`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `zones_to_geo_zones` (
  `association_id` int(11) NOT NULL auto_increment,
  `zone_country_id` int(11) NOT NULL default '0',
  `zone_id` int(11) default NULL,
  `geo_zone_id` int(11) default NULL,
  `last_modified` datetime default NULL,
  `date_added` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`association_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `zones_to_geo_zones`
--

LOCK TABLES `zones_to_geo_zones` WRITE;
/*!40000 ALTER TABLE `zones_to_geo_zones` DISABLE KEYS */;
INSERT INTO `zones_to_geo_zones` VALUES (1,105,0,1,NULL,'2007-10-19 17:39:13');
/*!40000 ALTER TABLE `zones_to_geo_zones` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-09-20 10:55:17
