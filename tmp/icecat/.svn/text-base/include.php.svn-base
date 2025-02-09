<?php
/**
 * Created on 8 Oct. 2009
 *
 * @author mikel <anmishael@gmail.com>
 * @version 1.0
 */
 
 // define constants for ICECAT contribution
 define('SYS_SLASH', $thslash);
 define('DIR_WS_ICECAT', 'icecat/');
 define('DIR_WS_ICECAT_LIBS', DIR_WS_ICECAT . 'libs/');
 define('DIR_FS_ICECAT', DIR_FS_CATALOG . 'icecat/');
 define('DIR_FS_ICECAT_LIBS', DIR_FS_ICECAT . 'libs/');
 
 define('ICECAT_DEBUG', 'false');
 define('ICECAT_XML_CONFIG', DIR_FS_ICECAT . 'icecat.xml');
 
 define('FILENAME_ICECAT_SETUP', 'icecat_setup.php');

 if(!defined('TABLE_PWS_PRODUCTS_IMAGES')) {
 	define('TABLE_PWS_PRODUCTS_IMAGES','pws_products_images');
 }

 if(file_exists(DIR_FS_ICECAT . 'admin/languages/' . $language . '.php')) {
 	require_once(DIR_FS_ICECAT . 'admin/languages/' . $language . '.php');
 } else {
 	require_once(DIR_FS_ICECAT . 'admin/languages/default.php');
 }
 require_once(DIR_FS_ICECAT . 'classes/iceosc.class.php');
 require_once(DIR_FS_ICECAT . 'classes/icecat.class.php');
 
 if(!defined('ICECAT_USER') || !defined('ICECAT_PASS')) {
	$objIceOsc = new iceosc();
	$objIceOsc->defineVars();
 }
?>