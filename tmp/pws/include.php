<?php
/*
 * @filename:	include.php
 * @version:	1.00
 * @project:	PWS
 *
 * @author:		Riccardo Roscilli <info@oscommerce.it>
 * @created:	15/mag/07
 * @modified:	15/mag/07 12:28:52
 *
 * @copyright:	2006-2007	Riccardo Roscilli
 *
 * @desc:	
 *
 * @TODO:		
 */

// Directories
// controllo se la lingua usata è default
$lang_array = tep_get_row(TABLE_LANGUAGES, 'languages_id', $_SESSION['languages_id']);

if (NEW_TEMPLATE_SYSTEM == 'true')
  {
  	define('STS_TEMPLATE_DIR', 'templates/speedracer/sts_templates/');
  }
  	else 
  	{
		
  		
  		
	if (!defined('STS_TEMPLATE_DIR'))
		{
			if ($lang_array['code'] == DEFAULT_LANGUAGE || $lang_array['code'] == '') 
				define('STS_TEMPLATE_DIR', DIR_WS_INCLUDES . 'sts_templates/');
			else 
				define('STS_TEMPLATE_DIR', DIR_WS_INCLUDES . 'sts_templates_' . $lang_array['name'] .'/');
		}
  	}
// echo STS_TEMPLATE_DIR;
//print_r( $_SESSION );
// print_r(tep_get_row(TABLE_LANGUAGES, 'languages_id', $_SESSION['languages_id'])) ;
// exit;
define('DIR_WS_PWS','pws/');
define('DIR_WS_PWS_EXTRAS', 'pws_extras/');
//define('DIR_WS_RESOURCES', 'resources/');
define('DIR_WS_PWS_ADMIN',DIR_WS_PWS.'admin/');
define('DIR_WS_PWS_CLASSES',DIR_WS_PWS.'classes/');
define('DIR_WS_PWS_INCLUDES',DIR_WS_PWS.'includes/');
define('DIR_WS_PWS_PLUGINS',DIR_WS_PWS.'plugins/');
define('DIR_WS_PWS_PLUGIN_PRICES',DIR_WS_PWS_PLUGINS.'prices/');
define('DIR_WS_PWS_PLUGIN_APPLICATION',DIR_WS_PWS_PLUGINS.'application/');
define('DIR_WS_STS_SKINS',STS_TEMPLATE_DIR.'skins/');
define('DIR_WS_PWS_SKINS',DIR_WS_STS_SKINS.'pws/');
define('DIR_WS_STYLESHEETS','stylesheets/');
define('DIR_WS_PWS_STYLESHEETS',DIR_WS_STYLESHEETS.'pws/');
define('DIR_WS_PWS_LIBS',DIR_WS_PWS.'libs/');
//	define('DIR_WS_PWS_LANGUAGE',DIR_WS_PWS."languages/$language/");
define('DIR_WS_PWS_LANGUAGE',(defined('DIR_WS_CATALOG_LANGUAGES')?DIR_WS_CATALOG_LANGUAGES:DIR_WS_LANGUAGES).$language.'/'.DIR_WS_PWS);
define('DIR_WS_PWS_APPLICATION',DIR_WS_PWS.'application/');
define('DIR_WS_PWS_FILEBROWSER',DIR_WS_PWS_LIBS.'filebrowser/');

define('DIR_FS_PWS',realpath(dirname(__FILE__).'/../').'/'.DIR_WS_PWS);
define('DIR_FS_PWS_EXTRAS',realpath(dirname(__FILE__).'/../'). '/'. DIR_WS_PWS_EXTRAS);
//define('DIR_FS_RESOURCES', realpath(dirname(__FILE__).'/../'). '/'.DIR_WS_RESOURCES );
define('DIR_FS_PWS_ADMIN',DIR_FS_PWS.'admin/');
define('DIR_FS_PWS_CLASSES',DIR_FS_PWS.'classes/');
define('DIR_FS_PWS_INCLUDES',DIR_FS_PWS.'includes/');
define('DIR_FS_PWS_PLUGINS',DIR_FS_PWS.'plugins/');
define('DIR_FS_PWS_PLUGINS_PRICES',DIR_FS_PWS_PLUGINS.'prices/');
define('DIR_FS_PWS_PLUGINS_APPLICATION',DIR_FS_PWS_PLUGINS.'application/');
define('DIR_FS_PWS_SKINS',DIR_FS_CATALOG.DIR_WS_PWS_SKINS);
define('DIR_FS_STS_SKINS',DIR_FS_CATALOG.DIR_WS_STS_SKINS);
define('DIR_FS_STYLESHEETS',DIR_FS_CATALOG.DIR_WS_STYLESHEETS);
define('DIR_FS_PWS_STYLESHEETS',DIR_FS_CATALOG.DIR_WS_PWS_STYLESHEETS);
define('DIR_FS_PWS_LIBS',DIR_FS_PWS.'libs/');
define('DIR_FS_PWS_FILEBROWSER',DIR_FS_CATALOG.DIR_WS_PWS_FILEBROWSER);
//define('DIR_FS_PWS_LANGUAGE',DIR_FS_PWS."languages/$language/");
define('DIR_FS_PWS_LANGUAGE',DIR_FS_CATALOG.((substr(DIR_WS_PWS_LANGUAGE,0,1)=='/' || substr(DIR_WS_PWS_LANGUAGE,0,1)=='\\')?substr(DIR_WS_PWS_LANGUAGE,1):DIR_WS_PWS_LANGUAGE));
define('DIR_FS_PWS_APPLICATION',DIR_FS_PWS.'application/');

// Tables
define('TABLE_PWS_PREFIX','pws_');
define('TABLE_PWS_CONFIGURATION',TABLE_PWS_PREFIX.'configuration');
define('TABLE_PWS_PLUGINS',TABLE_PWS_PREFIX.'plugins');
define('TABLE_PWS_LANGUAGES',TABLE_PWS_PREFIX.'languages');
define('TABLE_PWS_STRINGS',TABLE_PWS_PREFIX.'strings');

// Filenames
if (defined('FILENAME_MODULES'))
	define('FILENAME_PLUGINS',FILENAME_MODULES);
define('FILENAME_PWS_APPLICATION_SETUP','pws_setup.php');
//Skins front end
define('FILENAME_SHOPWINDOW', 'product_shopwindow.php');
define('FILENAME_SHOPWINDOW_FLASH', 'product_shopwindow_flash.php');
define('FILENAME_CATEGORIES_LISTING', 'categories_listing.php');
//define('FILENAME_CATEGORIES_LISTING_FLASH', 'categories_listing_flash.php');
define('FILENAME_PRODUCT_FILTERS', 'product_filters.php');
//define('FILENAME_PRODUCT_FILTERS_FLASH', 'product_filters_flash.php');

// Includes comuni
require_once DIR_FS_PWS_LIBS.'PHPTAL.inc.php';
require_once DIR_FS_PWS_LIBS.'phpmailer/class.phpmailer.php';
require_once DIR_FS_PWS_APPLICATION.'pws_skin.php';
require_once DIR_FS_PWS_APPLICATION.'pws_module.php';
require_once DIR_FS_PWS_APPLICATION.'pws_module_payment.php';

set_include_path(get_include_path() . PATH_SEPARATOR . DIR_FS_PWS_FILEBROWSER);
//require_once 'file_browser.class.php';

if (defined('DIR_FS_CATALOG_MODULES')){
	require_once DIR_FS_CATALOG_LANGUAGES.$language.'/modules/pws_engine.php';
	require_once DIR_FS_CATALOG_MODULES.'pws_engine.php';
}else{
	require_once DIR_WS_LANGUAGES.$language.'/modules/pws_engine.php';
	require_once DIR_WS_MODULES.'pws_engine.php';
}
if (!defined('DIR_FS_CATALOG_MODULES'))
	define('DIR_FS_CATALOG_MODULES', DIR_FS_CATALOG . 'includes/modules/');

//require_once DIR_FS_PWS_LIBS.'main.php';
//require_once DIR_FS_PWS_INCLUDES.'engine.php';
require_once DIR_FS_PWS_APPLICATION.'pws_plugin.php';
require_once DIR_FS_PWS_APPLICATION.'pws_plugin_price.php';
require_once DIR_FS_PWS_PLUGINS_APPLICATION.'pws_prices.php';


$pws_engine=new pws_engine();
$pws_prices=&$pws_engine->getPlugin('pws_prices','application');
$pws_html=&$pws_engine->getPlugin('pws_html','application');
?>