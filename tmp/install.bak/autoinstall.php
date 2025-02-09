<?php
/*
 * @filename:	autoinstall.php
 * @version:	1.00
 * @project:	PWS
 *
 * @author:		Riccardo Roscilli <info@oscommerce.it>
 * @created:	17/feb/08
 * @modified:	17/feb/08 16:38:16
 *
 * @copyright:	2006-2008	Riccardo Roscilli @ PWS
 *
 * @desc:	Script di installazione automatica senza interazione
 * @desc:	I parametri vanno passati via post o via get
 *
 * @params:
 * DB_SERVER				Indirizzo del server del database mysql (di solito è localhost)
 * DB_SERVER_USERNAME		Utente mysql
 * DB_SERVER_PASSWORD		Password mysql
 * DB_DATABASE				Nome del database (se non esiste, lo script tenterà di crearlo)
 * USE_PCONNECT				Utilizza connessioni persistenti. Può essere: 'false' oppure 'true'
 * STORE_SESSIONS			Dove memorizzare le sessioni. Può essere: 'files' oppure 'mysql'
 * 
 * @TODO:		
 */
	require('includes/application.php');
	require('includes/functions/files.php');
	osc_set_time_limit(0);
	$db = array();
	$db['DB_SERVER'] = trim(stripslashes($_REQUEST['DB_SERVER']));
	$db['DB_SERVER_USERNAME'] = trim(stripslashes($_REQUEST['DB_SERVER_USERNAME']));
	$db['DB_SERVER_PASSWORD'] = trim(stripslashes($_REQUEST['DB_SERVER_PASSWORD']));
	$db['DB_DATABASE'] = trim(stripslashes($_REQUEST['DB_DATABASE']));

	$db_error = false;
	osc_db_connect($db['DB_SERVER'], $db['DB_SERVER_USERNAME'], $db['DB_SERVER_PASSWORD']);

	if ($db_error == false) {
		osc_db_test_create_db_permission($db['DB_DATABASE']);
	}
	if ($db_error != false)	{
		die("KO");
	}

	// Trova la root del sito
	$script_filename = getenv('PATH_TRANSLATED');
	if (empty($script_filename)) {
		$script_filename = getenv('SCRIPT_FILENAME');
	}
	$script_filename = str_replace('\\', '/', $script_filename);
	$script_filename = str_replace('//', '/', $script_filename);

	$dir_fs_www_root_array = explode('/', dirname($script_filename));
	$dir_fs_www_root = array();
	for ($i=0, $n=sizeof($dir_fs_www_root_array)-1; $i<$n; $i++) {
		$dir_fs_www_root[] = $dir_fs_www_root_array[$i];
	}
	$dir_fs_www_root = implode('/', $dir_fs_www_root) . '/';
	
	//////////////////////////////////////////////////////////////////
	// Installazione di oscommerce.sql
	$sql_file = $dir_fs_www_root . 'install/oscommerce.sql';
	osc_db_install($db['DB_DATABASE'], $sql_file);
	if ($db_error != false) {
		die("KO");
	}
	
	
	//////////////////////////////////////////////////////////////////
	// Creazione dei files di configurazione
	$dir_fs_document_root=$dir_fs_www_root;

	$www_location = 'http://' . getenv('HTTP_HOST') . getenv('SCRIPT_NAME');
	$www_location = substr($www_location, 0, strpos($www_location, 'install'));

	$https_www_address = str_replace('http://', 'https://', $www_location);

	$http_url = parse_url($www_location);
	$http_server = $http_url['scheme'] . '://' . $http_url['host'];
	$http_catalog = $http_url['path'];
	if (isset($http_url['port']) && !empty($http_url['port'])) {
		$http_server .= ':' . $http_url['port'];
	}
	if (substr($http_catalog, -1) != '/') {
		$http_catalog .= '/';
	}
	
	$https_server = '';
	$https_catalog = '';
	if (!empty($https_www_address)) {
		$https_url = parse_url($https_www_address);
		$https_server = $https_url['scheme'] . '://' . $https_url['host'];
		$https_catalog = $https_url['path'];

		if (isset($https_url['port']) && !empty($https_url['port'])) {
			$https_server .= ':' . $https_url['port'];
		}

		if (substr($https_catalog, -1) != '/') {
			$https_catalog .= '/';
		}
	}
	$enable_ssl = 'false';
	$http_cookie_domain = getenv('HTTP_HOST');
	$https_cookie_domain = (!empty($https_www_address) ? getenv('HTTP_HOST') : '');
	$http_cookie_path = substr(dirname(getenv('SCRIPT_NAME')), 0, -7);
	$https_cookie_path = (!empty($https_www_address) ? $http_cookie_path : '');
	$db_server = $db['DB_SERVER'];
	$db_server_username = $db['DB_SERVER_USERNAME'];
	$db_server_password = $db['DB_SERVER_PASSWORD'];
	$db_server_db_name = $db['DB_DATABASE'];
	$db_server_use_pconnect = ($_REQUEST['USE_PCONNECT'] == 'true' ? 'true' : 'false');
	$db_server_store_sessions = ($_REQUEST['STORE_SESSIONS'] == 'files' ? '' : 'mysql');
	$file_contents = <<<EOT
/*
  osCommerce, Power Web Studio
  http://www.oscommerce.it
  Copyright (c) 2007-2008 PWS
  
  based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// Define the webserver and path parameters
// * DIR_FS_* = Filesystem directories (local/physical)
// * DIR_WS_* = Webserver directories (virtual/URL)
  define('HTTP_SERVER', '$http_server'); // eg, http://localhost - should not be empty for productive servers
  define('HTTPS_SERVER', '$https_server'); // eg, https://localhost - should not be empty for productive servers
  define('ENABLE_SSL', $enable_ssl); // secure webserver for checkout procedure?
  define('HTTP_COOKIE_DOMAIN', '$http_cookie_domain');
  define('HTTPS_COOKIE_DOMAIN', '$https_cookie_domain');
  define('HTTP_COOKIE_PATH', '$http_cookie_path');
  define('HTTPS_COOKIE_PATH', '$https_cookie_path');
  define('DIR_WS_HTTP_CATALOG', '$http_catalog');
  define('DIR_WS_HTTPS_CATALOG', '$https_catalog');
  define('DIR_WS_IMAGES', 'images/');
  define('DIR_WS_CACHE', 'tmp/');
  define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
  define('DIR_WS_INCLUDES', 'includes/');
  define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
  define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
  define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
  define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
  define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');

  define('DIR_WS_DOWNLOAD_PUBLIC', 'pub/');
  define('DIR_FS_CATALOG', '$dir_fs_document_root');
  define('DIR_FS_CACHE', DIR_FS_CATALOG.DIR_WS_CACHE);
  define('SESSION_WRITE_DIRECTORY', DIR_FS_CACHE);
  define('DIR_FS_DOWNLOAD', DIR_FS_CATALOG . 'download/');
  define('DIR_FS_DOWNLOAD_PUBLIC', DIR_FS_CATALOG . 'pub/');

// define our database connection
  define('DB_SERVER', '$db_server'); // eg, localhost - should not be empty for productive servers
  define('DB_SERVER_USERNAME', '$db_server_username');
  define('DB_SERVER_PASSWORD', '$db_server_password');
  define('DB_DATABASE', '$db_server_db_name');
  define('USE_PCONNECT', '$db_server_use_pconnect'); // use persistent connections?
  define('STORE_SESSIONS', '$db_server_store_sessions'); // leave empty '' for default handler or set to 'mysql'
EOT;
	$file_contents="<?php\r\n".$file_contents."\r\n?>";
	$filename=$dir_fs_document_root . 'includes/configure.php';
	$fp = fopen($filename, 'w');
	fputs($fp, $file_contents);
	fclose($fp);
	@chmod($filename, 0555);

	$file_contents = <<<EOT
/*
  osCommerce, Power Web Studio
  http://www.oscommerce.it
  Copyright (c) 2007-2008 PWS
  
  based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// Define the webserver and path parameters
// * DIR_FS_* = Filesystem directories (local/physical)
// * DIR_WS_* = Webserver directories (virtual/URL)
  define('HTTP_SERVER', '$http_server'); // eg, http://localhost - should not be empty for productive servers
  define('HTTP_CATALOG_SERVER', '$http_server');
  define('HTTPS_CATALOG_SERVER', '$https_server');
  define('ENABLE_SSL_CATALOG', '$enable_ssl'); // secure webserver for catalog module
  define('DIR_FS_DOCUMENT_ROOT', '$dir_fs_document_root'); // where the pages are located on the server
  define('DIR_WS_ADMIN', '$http_catalog'.'admin/'); // absolute path required
  define('DIR_FS_ADMIN', '$dir_fs_document_root'.'admin/'); // absolute pate required
  define('DIR_WS_CATALOG', '$http_catalog'); // absolute path required
  define('DIR_FS_CATALOG', '$dir_fs_document_root'); // absolute path required
  define('DIR_WS_IMAGES', 'images/');
  define('DIR_WS_CACHE', '../tmp/');
  define('DIR_FS_CACHE', DIR_FS_CATALOG.'tmp/');
  define('SESSION_WRITE_DIRECTORY', DIR_FS_CACHE);
  define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
  define('DIR_WS_CATALOG_IMAGES', DIR_WS_CATALOG . 'images/');
  define('DIR_WS_INCLUDES', 'includes/');
  define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
  define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
  define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
  define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
  define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');
  define('DIR_WS_CATALOG_LANGUAGES', DIR_WS_CATALOG . 'includes/languages/');
  define('DIR_FS_CATALOG_LANGUAGES', DIR_FS_CATALOG . 'includes/languages/');
  define('DIR_FS_CATALOG_IMAGES', DIR_FS_CATALOG . 'images/');
  define('DIR_FS_CATALOG_MODULES', DIR_FS_CATALOG . 'includes/modules/');
  define('DIR_FS_BACKUP', DIR_FS_ADMIN . 'backups/');

// define our database connection
  define('DB_SERVER', '$db_server'); // eg, localhost - should not be empty for productive servers
  define('DB_SERVER_USERNAME', '$db_server_username');
  define('DB_SERVER_PASSWORD', '$db_server_password');
  define('DB_DATABASE', '$db_server_db_name');
  define('USE_PCONNECT', '$db_server_use_pconnect'); // use persistent connections?
  define('STORE_SESSIONS', '$db_server_store_sessions'); // leave empty '' for default handler or set to 'mysql'
EOT;
	$file_contents="<?php\r\n".$file_contents."\r\n?>";
	$filename=$dir_fs_document_root . 'admin/includes/configure.php';
	$fp = fopen($filename, 'w');
	fputs($fp, $file_contents);
	fclose($fp);
	@chmod($filename, 0555);
	//////////////////////////////////////////////////////////////////
	// Creazione della directory cache e setting della chiave di configurazione
	// corrispondente nel db
	$cache_dir=$dir_fs_document_root.'tmp';
	if (!file_exists($cache_dir)){
		mkdir($cache_dir, 0777);
	}else{
		if (!is_dir($cache_dir))
			die("KO");//die("La directory $cache_dir non può essere creata perchè esiste gi&agrave; un file con lo stesso nome<br/>\r\n");
		else
			chmod($cache_dir, 0777);
	}
	$cache_dir.='/';
//	if (osc_db_num_rows(osc_db_query("select * from configuration where configuration_key='DIR_FS_CACHE'"))){
//		osc_db_query("update configuration set configuration_value='$cache_dir' where configuration_key='DIR_FS_CACHE'");
//	}else{
//		osc_db_query("insert into configuration set configuration_title='Cache Directory', configuration_description='The directory where the cached files are saved', configuration_group_id='11', sort_order='2', date_added=now(), configuration_value='$cache_dir', configuration_key='DIR_FS_CACHE'");
//	}
	osc_db_query("update configuration set configuration_value='$cache_dir' where configuration_key='SESSION_WRITE_DIRECTORY'");
	//////////////////////////////////////////////////////////////////
	// Copia delle directories delle risorse iniziali nella root del sito
//	$resources_dir=$dir_fs_document_root.'install/init_resources/';
//	$destination_dir=$dir_fs_document_root;
	$destination_dir=realpath(dirname(__FILE__).'/../').'/';
	$destination_dir=str_replace('\\','/',$destination_dir);
	$resources_dir=realpath(dirname(__FILE__)).'/init_resources/';
	$resources_dir=str_replace('\\','/',$resources_dir);
	$error=move_dir_contents($resources_dir,$destination_dir,true,false,false,array('.svn'));
	$result= ($error!=false) ? "KO" : "OK";
	exit($result);
?>