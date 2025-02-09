<?php
/*
  $Id: application_top.php,v 1.1 2003/09/08 19:25:43 jhtalk Exp jhtalk $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/

// require_once('includes/classes/class.compressor.php'); //Include the class. The full path may be required
// $compressor = new compressor('css,javascript,page');


// start the timer for the page parse time log
//  define('PAGE_PARSE_START_TIME', microtime(true));
//  define('PERFORMANCE', false);
//  if (PERFORMANCE) echo "start" . microtime(true);
// set the level of error reporting
//  error_reporting(E_ALL & ~E_NOTICE);
//  error_reporting(E_ERROR);
//  error_reporting(E_ALL);

// check support for register_globals
  if (function_exists('ini_get') && (ini_get('register_globals') == false) && (PHP_VERSION < 4.3) ) {
    exit('Server Requirement Error: register_globals is disabled in your PHP configuration. This can be enabled in your php.ini configuration file or in the .htaccess file in your catalog directory. Please use PHP 4.3+ if register_globals cannot be enabled on the server.');
  }

// Set the local configuration parameters - mainly for developers
  if (file_exists('includes/local/configure.php'))
  	include('includes/local/configure.php');

// include server parameters
  if (file_exists('includes/configure.php'))
  	require('includes/configure.php');
  else{
    if (is_dir('install')) {
      header('Location: install/index.php');
    }
  }
  if (constant("DB_SERVER") == '' ){
//  if (strlen(DB_SERVER) < 1) {
    if (is_dir('install')) {
      header('Location: install/index.php');
    }
  }

// define the project version
  define('PROJECT_VERSION', 'osCommerce Online Merchant v2.2 RC1');

// some code to solve compatibility issues
  require(DIR_WS_FUNCTIONS . 'compatibility.php');

// set the type of request (secure or not)
  $request_type = (getenv('HTTPS') == 'on') ? 'SSL' : 'NONSSL';

// set php_self in the local scope
  if (!isset($PHP_SELF)) $PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'];

  if ($request_type == 'NONSSL') {
    define('DIR_WS_CATALOG', DIR_WS_HTTP_CATALOG);
  } else {
    define('DIR_WS_CATALOG', DIR_WS_HTTPS_CATALOG);
  }

// include the list of project filenames
  require(DIR_WS_INCLUDES . 'filenames.php');

// include the list of project database tables
  require(DIR_WS_INCLUDES . 'database_tables.php');

// customization for the design layout
  define('BOX_WIDTH', 125); // how wide the boxes should be in pixels (default: 125)

// include the database functions
  require(DIR_WS_FUNCTIONS . 'database.php');

// make a connection to the database... now
  tep_db_connect() or die('Unable to connect to database server!');

//  if(PERFORMANCE) echo "dopo db_connect" . microtime(true);

// Controlla che il database sia importato
  if (tep_db_num_rows(tep_db_query('show tables'))==0){
     // controllo piattaforma, se è win === cinema 
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        echo "Questo &egrave; un sistema Windows, l'importazione automatica del db non funziona";
	}
	else
	{
   	// if (strpos(php_uname('s'),'nix')!==false ||	strpos(php_uname('s'),'FreeBSD')!==false){
  		$sql_file=DIR_FS_CATALOG.'oscommerce.sql';
  		if (file_exists($sql_file)){
			$command = "mysql -h ".DB_SERVER." -u ".DB_SERVER_USERNAME." --password=\"".DB_SERVER_PASSWORD."\" ".DB_DATABASE." < $sql_file";
			exec($command);
  		}else{
  			die("Il database appare vuoto ed il file sql del db ('$sql_file') non esiste.");
  		}
  	}
  }
  
/*
// set the application parameters
  $configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);
  while ($configuration = tep_db_fetch_array($configuration_query)) {
   if (!defined($configuration['cfgKey']))
   		 define($configuration['cfgKey'], $configuration['cfgValue']);
  }    
  */
   // set application wide parameters
// Configuration Cache modification start
  require ('includes/configuration_cache_read.php');
// Configuration Cache modification end 
  
	//define('DIR_WS_CACHE', DIR_WS_IMAGES . 'imagecache/');
	if (!defined('DIR_WS_CACHE')){
		if (false!==strpos(DIR_FS_CACHE,DIR_FS_CATALOG) && is_dir(DIR_FS_CACHE)){
			define('DIR_WS_CACHE', substr(DIR_FS_CACHE,strpos(DIR_FS_CACHE,DIR_FS_CATALOG)+strlen(DIR_FS_CATALOG)));
		}else{
			define('DIR_WS_CACHE','tmp/');
		}
	}
	 $flag_first_cart = false;
  // Shopping cart cookie
  if (isset($_GET['action'])) { // prima aggiunta al carrello
  	   	// creo un COOKIE per togliere il turbo
     	setcookie('Cart','yes',0,'/');
     	$flag_first_cart = true;
  }
  elseif (isset($_COOKIE['Cart'])) // pagine successive
  {
  	 $flag_first_cart = true;
  }
  
   if (isset($_GET['tid_bs'])) { // cookie bestshopping
  	   	// creo un COOKIE per bestshopping se il cliente arriva da lì
     	setcookie('tid_bs',$_GET['tid_bs'],time()+60*60*24*30,'/');
   }
  
  
$cache_enabled = 'false';
// print_r($_COOKIE);

		if (TURBO == 'on' && ($flag_first_cart == false) && (!isset($_COOKIE['Cart'])) && (!isset($_COOKIE['LoggedIn'])) && (!isset($_COOKIE['AdminLoggedIn'])) && ( (strstr($PHP_SELF,"index")) || (strstr($PHP_SELF,"product_info"))  ))
				{
				   if(!isset($customer_group_id)) { $group_id = '0'; }
				   
				      $cache_dir = EP_TEMP_DIRECTORY;

				      
				      $cachefile =  $cache_dir . $PHP_SELF. "_turbo_" . $language. "_" . $group_id . "___" . $cPath. "__" . $HTTP_GET_VARS['filter_id'] . "__" .$HTTP_GET_VARS['manufacturers_id']. "__" .$HTTP_GET_VARS['products_id'] . "___" . $HTTP_GET_VARS['page'].$HTTP_GET_VARS['sort'] . ".html";
				      
				     
				      $cachetime =  30 * 60 ; // in secondi (30x60 = 30 minuti) 
				      						 // da parametrizzare in base al traffico, si potrebbero contare gli hits sul file
				      						 // oppure gestire gli eventi come modifica da admin -> categories.php
				      						 // acquisto da parte di cliente (aggiornamento sulle schede prodotto, ma anche sulle categorie
				      						 // tuttavia la disponibilità con 10 min di refresh potrebbe essere un problema marginale
	
				      // Serve from the cache if it is younger than $cachetime e non � loggato e siamo solo su index.php o product_info.php
				      if (file_exists($cachefile) && (time() - $cachetime < filemtime($cachefile)) )
				      {
						
					       	 // calc an offset of 24 hours
						//		 $offset = 3600 * 24;
								 // calc the string in GMT not localtime and add the offset
							//	 $expire = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
								 //output the HTTP header
								//  Header($expire);       
					 			  readfile($cachefile);
				     			  // echo $template_html;
				        echo "<!-- Cached ".date('jS F Y H:i', filemtime($cachefile))."-->\n";
				       exit;
				      }
				      $cache_enabled = 'true';
				}
// /* debug

if (MODULE_PWS_DEBUG == 'true'){
	ini_set("display_errors", true);
	ini_set('error_reporting',  E_ALL & ~E_NOTICE);
}else{
	ini_set("display_errors", true);
//	ini_set('error_reporting', 0);
	error_reporting(0);
}
// */

// if gzip_compression is enabled, start to buffer the output
  if ( (GZIP_COMPRESSION == 'true') && ($ext_zlib_loaded = extension_loaded('zlib')) && (PHP_VERSION >= '4') ) {
    if (($ini_zlib_output_compression = (int)ini_get('zlib.output_compression')) < 1) {
      if (PHP_VERSION >= '4.0.4') {
        ob_start('ob_gzhandler');
      } else {
        include(DIR_WS_FUNCTIONS . 'gzip_compression.php');
        ob_start();
        ob_implicit_flush();
      }
    } else {
      ini_set('zlib.output_compression_level', GZIP_LEVEL);
    }
  }

// set the HTTP GET parameters manually if search_engine_friendly_urls is enabled
  if (SEARCH_ENGINE_FRIENDLY_URLS == 'true') {
    if (strlen(getenv('PATH_INFO')) > 1) {
      $GET_array = array();
      $PHP_SELF = str_replace(getenv('PATH_INFO'), '', $PHP_SELF);
      $vars = explode('/', substr(getenv('PATH_INFO'), 1));
      for ($i=0, $n=sizeof($vars); $i<$n; $i++) {
        if (strpos($vars[$i], '[]')) {
          $GET_array[substr($vars[$i], 0, -2)][] = $vars[$i+1];
        } else {
          $_GET[$vars[$i]] = $vars[$i+1];
        }
        $i++;
      }

      if (sizeof($GET_array) > 0) {
        while (list($key, $value) = each($GET_array)) {
          $_GET[$key] = $value;
        }
      }
    }
  }

// define general functions used application-wide
  require(DIR_WS_FUNCTIONS . 'general.php');
  require(DIR_WS_FUNCTIONS . 'html_output.php');

 
  

// set the cookie domain
  $cookie_domain = (($request_type == 'NONSSL') ? HTTP_COOKIE_DOMAIN : HTTPS_COOKIE_DOMAIN);
  $cookie_path = (($request_type == 'NONSSL') ? HTTP_COOKIE_PATH : HTTPS_COOKIE_PATH);

// include cache functions if enabled
  if (USE_CACHE == 'true') include(DIR_WS_FUNCTIONS . 'cache.php');

// include shopping cart class
  require(DIR_WS_CLASSES . 'shopping_cart.php');

// include navigation history class
  require(DIR_WS_CLASSES . 'navigation_history.php');

// check if sessions are supported, otherwise use the php3 compatible session class
  if (!function_exists('session_start')) {
    define('PHP_SESSION_NAME', 'osCsid');
    define('PHP_SESSION_PATH', $cookie_path);
    define('PHP_SESSION_DOMAIN', $cookie_domain);
    define('PHP_SESSION_SAVE_PATH', SESSION_WRITE_DIRECTORY);

    include(DIR_WS_CLASSES . 'sessions.php');
    
  }

// define how the session functions will be used
  require(DIR_WS_FUNCTIONS . 'sessions.php');

// set the session name and save path
  tep_session_name('osCsid');
  tep_session_save_path(SESSION_WRITE_DIRECTORY);


// set the session cookie parameters
   if (function_exists('session_set_cookie_params')) {
    session_set_cookie_params(0, $cookie_path, $cookie_domain);
  } elseif (function_exists('ini_set')) {
    ini_set('session.cookie_lifetime', '0');
    ini_set('session.cookie_path', $cookie_path);
    ini_set('session.cookie_domain', $cookie_domain);
  }
 
// set the session ID if it exists
   if (isset($HTTP_POST_VARS[tep_session_name()])) {
     tep_session_id($HTTP_POST_VARS[tep_session_name()]);
   } elseif ( ($request_type == 'SSL') && isset($_GET[tep_session_name()]) ) {
     tep_session_id($_GET[tep_session_name()]);
   }


// start the session
  $session_started = false;
  if (SESSION_FORCE_COOKIE_USE == 'True') {
    tep_setcookie('cookie_test', 'please_accept_for_session', time()+60*60*24*30, $cookie_path, $cookie_domain);

   // print_r($HTTP_COOKIE_VARS);
    
    if (isset($HTTP_COOKIE_VARS['cookie_test'])) {
      tep_session_start();
      $session_started = true;
    }
  } elseif (SESSION_BLOCK_SPIDERS == 'True') {
    $user_agent = strtolower(getenv('HTTP_USER_AGENT'));
    $spider_flag = false;

    if (tep_not_null($user_agent)) {
      $spiders = file(DIR_WS_INCLUDES . 'spiders.txt');

      for ($i=0, $n=sizeof($spiders); $i<$n; $i++) {
        if (tep_not_null($spiders[$i])) {
          if (is_integer(strpos($user_agent, trim($spiders[$i])))) {
            $spider_flag = true;
            break;
          }
        }
      }
    }

    if ($spider_flag == false) {
      tep_session_start();
      $session_started = true;
    }
  } else {
    tep_session_start();
    $session_started = true;
  }

  
  if ( ($session_started == true) && (PHP_VERSION >= 4.3) && function_exists('ini_get') && (ini_get('register_globals') == false) ) {
    extract($_SESSION, EXTR_OVERWRITE+EXTR_REFS);
  }

// set SID once, even if empty
  $SID = (defined('SID') ? SID : '');
 

// verify the ssl_session_id if the feature is enabled
  if ( ($request_type == 'SSL') && (SESSION_CHECK_SSL_SESSION_ID == 'True') && (ENABLE_SSL == true) && ($session_started == true) ) {
    $ssl_session_id = getenv('SSL_SESSION_ID');
    if (!tep_session_is_registered('SSL_SESSION_ID')) {
      $SESSION_SSL_ID = $ssl_session_id;
      tep_session_register('SESSION_SSL_ID');
    }

    if ($SESSION_SSL_ID != $ssl_session_id) {
      tep_session_destroy();
      tep_redirect(tep_href_link(FILENAME_SSL_CHECK));
    }
  }

// verify the browser user agent if the feature is enabled
  if (SESSION_CHECK_USER_AGENT == 'True') {
    $http_user_agent = getenv('HTTP_USER_AGENT');
    if (!tep_session_is_registered('SESSION_USER_AGENT')) {
      $SESSION_USER_AGENT = $http_user_agent;
      tep_session_register('SESSION_USER_AGENT');
    }

    if ($SESSION_USER_AGENT != $http_user_agent) {
      tep_session_destroy();
      tep_redirect(tep_href_link(FILENAME_LOGIN));
    }
  }

// verify the IP address if the feature is enabled
  if (SESSION_CHECK_IP_ADDRESS == 'True') {
    $ip_address = tep_get_ip_address();
    if (!tep_session_is_registered('SESSION_IP_ADDRESS')) {
      $SESSION_IP_ADDRESS = $ip_address;
      tep_session_register('SESSION_IP_ADDRESS');
    }

    if ($SESSION_IP_ADDRESS != $ip_address) {
      tep_session_destroy();
      tep_redirect(tep_href_link(FILENAME_LOGIN));
    }
  }

// create the shopping cart & fix the cart if necesary
  if (tep_session_is_registered('cart') && is_object($cart)) {
    if (PHP_VERSION < 4) {
      $broken_cart = $cart;
      $cart = new shoppingCart;
      $cart->unserialize($broken_cart);
    }
  } else {
    tep_session_register('cart');
    $cart = new shoppingCart;
  }

// include currencies class and create an instance
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

// include the mail classes
//  require(DIR_WS_CLASSES . 'mime.php');
//  require(DIR_WS_CLASSES . 'email.php');
  require(DIR_WS_CLASSES . 'class.phpmailer.php');
  
  
// set the language
  if (!tep_session_is_registered('language') || isset($_GET['language'])) {
    if (!tep_session_is_registered('language')) {
      tep_session_register('language');
      tep_session_register('languages_id');
    }

    include(DIR_WS_CLASSES . 'language.php');
    $lng = new language();

    if (isset($_GET['language']) && tep_not_null($_GET['language'])) {
      $lng->set_language($_GET['language']);
    } else {
      $lng->get_browser_language();
    }

    $language = $lng->language['directory'];
    $languages_id = $lng->language['id'];
  }

// include the language translations
  require(DIR_WS_LANGUAGES . $language . '.php');

// Ultimate SEO URLs BEGIN
    include_once(DIR_WS_CLASSES . 'seo.class.php');
    if (!is_object($seo_urls)) {
        $seo_urls = new SEO_URL($languages_id);
    }
// Ultimate SEO URLs END

// currency
  if (!tep_session_is_registered('currency') || isset($_GET['currency']) || ( (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') && (LANGUAGE_CURRENCY != $currency) ) ) {
    if (!tep_session_is_registered('currency')) tep_session_register('currency');

    if (isset($_GET['currency']) && $currencies->is_set($_GET['currency'])) {
      $currency = $_GET['currency'];
    } else {
      $currency = (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') ? LANGUAGE_CURRENCY : DEFAULT_CURRENCY;
    }
  }

// navigation history
  if (tep_session_is_registered('navigation')) {
    if (PHP_VERSION < 4) {
      $broken_navigation = $navigation;
      $navigation = new navigationHistory;
      $navigation->unserialize($broken_navigation);
    }
  } else {
    tep_session_register('navigation');
    $navigation = new navigationHistory;
  }
  $navigation->add_current_page();

// PWS bof
	require_once DIR_FS_CATALOG.'pws/include.php';
	$pws_engine->triggerHook('CATALOG_APPLICATION_TOP');
// PWS eof

// STS: ADD: Define Simple Template System files
  define('STS_START_CAPTURE', DIR_WS_INCLUDES . 'sts_start_capture.php');
  define('STS_STOP_CAPTURE', DIR_WS_INCLUDES . 'sts_stop_capture.php'); 
  define('STS_RESTART_CAPTURE', DIR_WS_INCLUDES . 'sts_restart_capture.php');
  define('STS_DISPLAY_OUTPUT', DIR_WS_INCLUDES . 'sts_display_output.php');
  define('STS_USER_CODE', DIR_WS_INCLUDES . 'sts_user_code.php');
  define('STS_PRODUCT_INFO', DIR_WS_INCLUDES . 'sts_product_info.php');
  
  // nuovo template system
if (NEW_TEMPLATE_SYSTEM == 'true')
{
	if (!isset($template_dir))
		$template_dir = 'speedracer/'; // inserire la variabile dir selezionata da admin
		define (STS_TEMPLATE_DIR , 'templates/' . $template_dir);
	$sts_template_file = 'templates/'. $template_dir . 'sts_template.html';
	if ($sts_custom_template!=''){
	  	$sts_custom_template_base=basename($sts_custom_template,'.html');
	}
}
else  // modalit� standard sts
{

  //die("select count(*) from ".TABLE_LANGUAGES." where code='".DEFAULT_LANGUAGE."' and directory='$language'");
  $sts_custom_template=$pws_engine->triggerHook('CATALOG_CUSTOM_GROUP_TEMPLATE');
  if ($sts_custom_template!=''){
	  $sts_custom_template_base=basename($sts_custom_template,'.html');
	  if (1==tep_db_num_rows(tep_db_query("select * from ".TABLE_LANGUAGES." where code='".DEFAULT_LANGUAGE."' and directory='$language'"))){
		define('STS_DEFAULT_TEMPLATE', DIR_WS_INCLUDES . $sts_custom_template); 
		define('STS_TEMPLATE_DIR', DIR_WS_INCLUDES . 'sts_templates/');
	  }else{
	  	if (!file_exists(DIR_FS_CATALOG.DIR_WS_INCLUDES."{$sts_custom_template_base}_{$language}.html")){
			define('STS_DEFAULT_TEMPLATE', DIR_WS_INCLUDES . $sts_custom_template); 
	  	}else{
			define('STS_DEFAULT_TEMPLATE', DIR_WS_INCLUDES . "{$sts_custom_template_base}_{$language}.html"); 
	  	}
	  	if (file_exists(DIR_FS_CATALOG.DIR_WS_INCLUDES."sts_templates_$language") && is_dir(DIR_FS_CATALOG.DIR_WS_INCLUDES."sts_templates_$language")) {
		  	define('STS_TEMPLATE_DIR', DIR_WS_INCLUDES . "sts_templates_$language/");
	    }else{
			define('STS_TEMPLATE_DIR', DIR_WS_INCLUDES . 'sts_templates/');
	    }
	  }
  }else{
	  if (basename($PHP_SELF,'.php')=='index' && !isset($_GET['cPath']) && !isset($_GET['manufacturers_id']) && !isset($_GET['keywords'])){
		  if (1==tep_db_num_rows(tep_db_query("select * from ".TABLE_LANGUAGES." where code='".DEFAULT_LANGUAGE."' and directory='$language'"))){
			if (file_exists(DIR_FS_CATALOG.DIR_WS_INCLUDES.'sts_template_homepage.html')){
			  	define('STS_DEFAULT_TEMPLATE', DIR_WS_INCLUDES . 'sts_template_homepage.html'); 
				define('STS_TEMPLATE_DIR', DIR_WS_INCLUDES . 'sts_templates/');
			}
		  }else{
			if (file_exists(DIR_FS_CATALOG.DIR_WS_INCLUDES."sts_template_homepage_$language.html")){
			  	define('STS_DEFAULT_TEMPLATE', DIR_WS_INCLUDES . "sts_template_homepage_$language.html"); 
			  	define('STS_TEMPLATE_DIR', DIR_WS_INCLUDES . "sts_templates_$language/");
			}
		  }
	  }
	  if (!defined('STS_DEFAULT_TEMPLATE') ){
		  if (1==tep_db_num_rows(tep_db_query("select * from ".TABLE_LANGUAGES." where code='".DEFAULT_LANGUAGE."' and directory='$language'"))){
			define('STS_DEFAULT_TEMPLATE', DIR_WS_INCLUDES . 'sts_template.html'); 
			define('STS_TEMPLATE_DIR', DIR_WS_INCLUDES . 'sts_templates/');
		  }else{
		  	if(file_exists(DIR_WS_INCLUDES . "sts_template_$language.html"))
		  	{
				define('STS_DEFAULT_TEMPLATE', DIR_WS_INCLUDES . "sts_template_$language.html"); 
			  	define('STS_TEMPLATE_DIR', DIR_WS_INCLUDES . "sts_templates_$language/");
		  	}
		  	else {
					define('STS_DEFAULT_TEMPLATE', DIR_WS_INCLUDES . 'sts_template.html'); 
					define('STS_TEMPLATE_DIR', DIR_WS_INCLUDES . 'sts_templates/');
		  		
		  	}
		  }
	  }
  }
}
// STS: EOADD
  	
  if (!$pws_prices->displayPrices()){
  	switch (basename($PHP_SELF)){
  		case FILENAME_SHOPPING_CART:
  		case FILENAME_CHECKOUT_SHIPPING:
			tep_redirect(tep_href_link(FILENAME_LOGIN,'error_message='.$pws_prices->getHiddenPricesMessage()));
  			break;
  		default:
  			break;
  	}
  }

// Shopping cart actions
  if (isset($_GET['action'])) {
  	   	// creo un COOKIE per togliere il turbo
     	setcookie('Cart','yes',0,'/');
     	
  	if (!$pws_prices->displayPrices() && $_GET['action']!='process' && $_GET['action']!='banner'){
  	//	tep_redirect(tep_href_link(FILENAME_LOGIN,'error_message='.$pws_prices->getHiddenPricesMessage()));
  	}
// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled
    if ($session_started == false) {
      tep_redirect(tep_href_link(FILENAME_COOKIE_USAGE));
    }

    if (DISPLAY_CART == 'true' && basename($PHP_SELF)!=FILENAME_CHECKOUT_SHIPPING) {
      $goto =  FILENAME_SHOPPING_CART;
      $parameters = array('action', 'cPath', 'products_id', 'pid');
    } else {
      $goto = basename($PHP_SELF);
      if ($_GET['action'] == 'buy_now') {
        $parameters = array('action', 'pid', 'products_id');
      } else {
        $parameters = array('action', 'pid');
      }
    }
    switch ($_GET['action']) {
      // customer wants to update the product quantity in their shopping cart
      case 'update_product' : for ($i=0, $n=sizeof($HTTP_POST_VARS['products_id']); $i<$n; $i++) {
                                if (in_array($HTTP_POST_VARS['products_id'][$i], (is_array($HTTP_POST_VARS['cart_delete']) ? $HTTP_POST_VARS['cart_delete'] : array()))) {
                                  $cart->remove($HTTP_POST_VARS['products_id'][$i]);
                                } else {
                                  if (PHP_VERSION < 4) {
                                    // if PHP3, make correction for lack of multidimensional array.
                                    reset($HTTP_POST_VARS);
                                    while (list($key, $value) = each($HTTP_POST_VARS)) {
                                      if (is_array($value)) {
                                        while (list($key2, $value2) = each($value)) {
                                          if (ereg ("(.*)\]\[(.*)", $key2, $var)) {
                                            $id2[$var[1]][$var[2]] = $value2;
                                          }
                                        }
                                      }
                                    }
                                    $attributes = ($id2[$HTTP_POST_VARS['products_id'][$i]]) ? $id2[$HTTP_POST_VARS['products_id'][$i]] : '';
                                  } else {
                                    $attributes = ($HTTP_POST_VARS['id'][$HTTP_POST_VARS['products_id'][$i]]) ? $HTTP_POST_VARS['id'][$HTTP_POST_VARS['products_id'][$i]] : '';
                                  }
                                  $cart->add_cart($HTTP_POST_VARS['products_id'][$i], $HTTP_POST_VARS['cart_quantity'][$i], $attributes, false);
                                }
                              }
                              if (isset($_REQUEST['toppec']))
                              	  tep_redirect(tep_href_link(FILENAME_EC_PROCESS,tep_get_all_get_params($parameters),'SSL'));
                              else
	                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
                              break;
      // customer adds a product from the products page
      case 'add_product' :    if (isset($HTTP_POST_VARS['products_id']) && is_numeric($HTTP_POST_VARS['products_id'])) {
//++++ QT Pro: Begin Changed code
                                $attributes=array();
                                if (isset($HTTP_POST_VARS['attrcomb']) && (preg_match("/^\d{1,10}-\d{1,10}(,\d{1,10}-\d{1,10})*$/",$HTTP_POST_VARS['attrcomb']))) {
                                  $attrlist=explode(',',$HTTP_POST_VARS['attrcomb']);
                                  foreach ($attrlist as $attr) {
                                    list($oid, $oval)=explode('-',$attr);
                                    if (is_numeric($oid) && $oid==(int)$oid && is_numeric($oval) && $oval==(int)$oval)
                                      $attributes[$oid]=$oval;
                                  }
                                }
                                if (isset($HTTP_POST_VARS['id']) && is_array($HTTP_POST_VARS['id'])) {
                                  foreach ($HTTP_POST_VARS['id'] as $key=>$val) {
                                    if (is_numeric($key) && $key==(int)$key && is_numeric($val) && $val==(int)$val)
                                      $attributes=$attributes + $HTTP_POST_VARS['id'];
                                  }
                                }
                                $quantities=&$_REQUEST['quantity'];
                                $cart->add_cart($HTTP_POST_VARS['products_id'], $cart->get_quantity(tep_get_uprid($HTTP_POST_VARS['products_id'], $attributes))+$quantities, $attributes);
//++++ QT Pro: End Changed Code
                              }
                              else if (isset($_REQUEST['products_id']) && is_array($_REQUEST['products_id']))	{
                              	$prids=&$_REQUEST['products_id'];
                              	$quantities=&$_REQUEST['quantity'];
                              	for ($i=0;$i<sizeof($quantities) && $quantities[$i]==0;$i++);
                              	if ($i==sizeof($quantities))
                              		$quantities[0]=1;
                                $attributes=array();
                                if (isset($HTTP_POST_VARS['attrcomb']) && (preg_match("/^\d{1,10}-\d{1,10}(,\d{1,10}-\d{1,10})*$/",$HTTP_POST_VARS['attrcomb']))) {
                                  $attrlist=explode(',',$HTTP_POST_VARS['attrcomb']);
                                  foreach ($attrlist as $attr) {
                                    list($oid, $oval)=explode('-',$attr);
                                    if (is_numeric($oid) && $oid==(int)$oid && is_numeric($oval) && $oval==(int)$oval)
                                      $attributes[$oid]=$oval;
                                  }
                                }
                                if (isset($HTTP_POST_VARS['id']) && is_array($HTTP_POST_VARS['id'])) {
                                  foreach ($HTTP_POST_VARS['id'] as $key=>$val) {
                                    if (is_numeric($key) && $key==(int)$key && is_numeric($val) && $val==(int)$val)
                                      $attributes=$attributes + $HTTP_POST_VARS['id'];
                                  }
                                }
                            	$cart->add_cart(tep_get_uprid($prids[0],$attributes), $cart->get_quantity(tep_get_uprid($prids[0],$attributes))+$quantities[0],$attributes);
                              	for ($i=1;$i<sizeof($quantities);$i++)
                              		$cart->add_cart($prids[$i], $cart->get_quantity($prids[$i])+$quantities[$i]);
                              }
                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
                              break;
      // performed by the 'buy now' button in product listings and review page
      case 'buy_now' :        if (isset($_POST['products_id'])) {
                                if (tep_has_product_attributes($_GET['products_id'])) {
                                  tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $_POST['products_id']));
                                } else {
                                
                                  if ($task == 'add')
                                  {	
                                 		 $cart->add_cart($_POST['products_id'], $_POST['quantity']);
                                  }
                                }
                              }
                              elseif (isset($_GET['products_id']))
                                  {	
                               if (tep_has_product_attributes($_GET['products_id'])) {
                                  tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $_GET['products_id']));
                                } else
                                  	$products_id=$_GET['products_id'];
                                 		  $cart->add_cart($_GET['products_id'], $cart->get_quantity(tep_get_uprid($_GET['products_id'], $attributes))+1);
                                  }                     
                              
                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
                              break;
      case 'notify' :         if (tep_session_is_registered('customer_id')) {
                                if (isset($_GET['products_id'])) {
                                  $notify = $_GET['products_id'];
                                } elseif (isset($_GET['notify'])) {
                                  $notify = $_GET['notify'];
                                } elseif (isset($HTTP_POST_VARS['notify'])) {
                                  $notify = $HTTP_POST_VARS['notify'];
                                } else {
                                  tep_redirect(tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action', 'notify'))));
                                }
                                if (!is_array($notify)) $notify = array($notify);
                                for ($i=0, $n=sizeof($notify); $i<$n; $i++) {
                                  $check_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . $notify[$i] . "' and customers_id = '" . $customer_id . "'");
                                  $check = tep_db_fetch_array($check_query);
                                  if ($check['count'] < 1) {
                                    tep_db_query("insert into " . TABLE_PRODUCTS_NOTIFICATIONS . " (products_id, customers_id, date_added) values ('" . $notify[$i] . "', '" . $customer_id . "', now())");
                                  }
                                }
                                tep_redirect(tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action', 'notify'))));
                              } else {
                                $navigation->set_snapshot();
                                tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
                              }
                              break;
      case 'notify_remove' :  if (tep_session_is_registered('customer_id') && isset($_GET['products_id'])) {
                                $check_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . $_GET['products_id'] . "' and customers_id = '" . $customer_id . "'");
                                $check = tep_db_fetch_array($check_query);
                                if ($check['count'] > 0) {
                                  tep_db_query("delete from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . $_GET['products_id'] . "' and customers_id = '" . $customer_id . "'");
                                }
                                tep_redirect(tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action'))));
                              } else {
                                $navigation->set_snapshot();
                                tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
                              }
                              break;
      case 'cust_order' :     if (tep_session_is_registered('customer_id') && isset($_GET['pid'])) {
                                if (tep_has_product_attributes($_GET['pid'])) {
                                  tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $_GET['pid']));
                                } else {
                                  $cart->add_cart($_GET['pid'], $cart->get_quantity($_GET['pid'])+1);
                                }
                              }
                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
                              break;
    }
  }
if (basename($PHP_SELF,'.php')=='index'){
	if (isset($_GET['products_id'])){
		unset($_GET['products_id']);
	}
	if (isset($_REQUEST['products_id'])){
		unset($_REQUEST['products_id']);
	}
	if (isset($HTTP_GET_VARS['products_id'])){
		unset($HTTP_GET_VARS['products_id']);
	}
}
// include the who's online functions
  require(DIR_WS_FUNCTIONS . 'whos_online.php');
  tep_update_whos_online();

// include the password crypto functions
  require(DIR_WS_FUNCTIONS . 'password_funcs.php');

// include validation functions (right now only email address)
  require(DIR_WS_FUNCTIONS . 'validations.php');

// split-page-results
  require(DIR_WS_CLASSES . 'split_page_results.php');

// infobox
  require(DIR_WS_CLASSES . 'boxes.php');

// auto activate and expire banners
  require(DIR_WS_FUNCTIONS . 'banner.php');
  tep_activate_banners();
  tep_expire_banners();

// auto expire special products
  require(DIR_WS_FUNCTIONS . 'specials.php');
  tep_expire_specials();

// calculate category path
  if (isset($_GET['cPath'])) {
    $cPath = $_GET['cPath'];
  } elseif (isset($_GET['products_id']) && !isset($_GET['manufacturers_id'])) {
    $cPath = tep_get_product_path($_GET['products_id']);
  } else {
    $cPath = '';
  }

  if (tep_not_null($cPath)) {
    $cPath_array = tep_parse_category_path($cPath);
    $cPath = implode('_', $cPath_array);
    $current_category_id = $cPath_array[(sizeof($cPath_array)-1)];
  } else {
    $current_category_id = 0;
  }

// include the breadcrumb class and start the breadcrumb trail
  require(DIR_WS_CLASSES . 'breadcrumb.php');
  $breadcrumb = new breadcrumb;

  $breadcrumb->add(HEADER_TITLE_TOP, HTTP_SERVER);
  $breadcrumb->add(HEADER_TITLE_CATALOG, tep_href_link(FILENAME_DEFAULT));

// add category names or the manufacturer name to the breadcrumb trail
  if (isset($cPath_array)) {
    for ($i=0, $n=sizeof($cPath_array); $i<$n; $i++) {
      $categories_query = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$cPath_array[$i] . "' and language_id = '" . (int)$languages_id . "'");
      if (tep_db_num_rows($categories_query) > 0) {
        $categories = tep_db_fetch_array($categories_query);
        $breadcrumb->add($categories['categories_name'], tep_href_link(FILENAME_DEFAULT, 'cPath=' . implode('_', array_slice($cPath_array, 0, ($i+1)))));
      } else {
        break;
      }
    }
  } elseif (isset($_GET['manufacturers_id'])) {
    $manufacturers_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "'");
    if (tep_db_num_rows($manufacturers_query)) {
      $manufacturers = tep_db_fetch_array($manufacturers_query);
      $breadcrumb->add($manufacturers['manufacturers_name'], tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $_GET['manufacturers_id']));
    }
  }

// add the products model to the breadcrumb trail
// non lo mettiamo più, mettiamo solo la categoria o il produttore
/*
  if (isset($_GET['products_id'])) {
    $model_query = tep_db_query("select products_model from " . TABLE_PRODUCTS . " where products_id = '" . (int)$_GET['products_id'] . "'");
    if (tep_db_num_rows($model_query)) {
      $model = tep_db_fetch_array($model_query);
      $breadcrumb->add($model['products_model'], tep_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $cPath . '&products_id=' . $_GET['products_id']));
    }
  }
*/
// initialize the message stack for output messages
  require(DIR_WS_CLASSES . 'message_stack.php');
  $messageStack = new messageStack;

// set which precautions should be checked
  define('WARN_INSTALL_EXISTENCE', 'true');
  define('WARN_CONFIG_WRITEABLE', 'false');
  define('WARN_SESSION_DIRECTORY_NOT_WRITEABLE', 'true');
  define('WARN_SESSION_AUTO_START', 'true');
  define('WARN_DOWNLOAD_DIRECTORY_NOT_READABLE', 'true');

// Include OSC-AFFILIATE
  if (file_exists(DIR_WS_INCLUDES . 'affiliate_application_top.php'))
	require(DIR_WS_INCLUDES . 'affiliate_application_top.php');

	
  // STS: ADD
  // Capture text between application_top.php and header.php
  require(STS_START_CAPTURE);
  // STS: EOADD
  // ICECAT: ADD
	require_once(DIR_FS_CATALOG . 'icecat/include.php');
  //ICECAT: EOADD
  // CART MODAL: ADD
//	require_once(DIR_FS_CATALOG . 'cart_modal/include.php');
  // CART MODAL: EOADD
  
	
?>