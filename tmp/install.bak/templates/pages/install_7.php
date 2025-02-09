<?php
/*
  $Id: install_7.php,v 1.1 2003/07/09 01:11:06 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  $dir_fs_document_root = $HTTP_POST_VARS['DIR_FS_DOCUMENT_ROOT'];
  if ((substr($dir_fs_document_root, -1) != '/') && (substr($dir_fs_document_root, -1) != '/')) {
    $where = strrpos($dir_fs_document_root, '\\');
    if (is_string($where) && !$where) {
      $dir_fs_document_root .= '/';
    } else {
      $dir_fs_document_root .= '\\';
    }
  }
?>

<p class="pageTitle">Nuova Installazione</p>

<p><b>osCommerce Configuration</b></p>

<?php
  $db = array();
  $db['DB_SERVER'] = trim(stripslashes($HTTP_POST_VARS['DB_SERVER']));
  $db['DB_SERVER_USERNAME'] = trim(stripslashes($HTTP_POST_VARS['DB_SERVER_USERNAME']));
  $db['DB_SERVER_PASSWORD'] = trim(stripslashes($HTTP_POST_VARS['DB_SERVER_PASSWORD']));
  $db['DB_DATABASE'] = trim(stripslashes($HTTP_POST_VARS['DB_DATABASE']));

  $db_error = false;
  osc_db_connect($db['DB_SERVER'], $db['DB_SERVER_USERNAME'], $db['DB_SERVER_PASSWORD']);

  if ($db_error == false) {
    osc_db_test_connection($db['DB_DATABASE']);
  }

  if ($db_error != false) {
?>
<form name="install" action="install.php?step=6" method="post">

<table width="95%" border="0" cellpadding="2" class="formPage">
  <tr>
    <td>
      <p>A test connection made to the database was <b>NOT</b> successful.</p>
      <p>The error message returned is:</p>
      <p class="boxme"><?php echo $db_error; ?></p>
      <p>Please click on the <i>Back</i> button below to review your database server settings.</p>
      <p>If you require help with your database server settings, please consult your hosting company.</p>
    </td>
  </tr>
</table>

<p>&nbsp;</p>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><a href="index.php"><img src="images/button_cancel.gif" border="0" alt="Cancel"></a></td>
    <td align="center"><input type="image" src="images/button_back.gif" border="0" alt="Back"></td>
  </tr>
</table>

<?php
    reset($HTTP_POST_VARS);
    while (list($key, $value) = each($HTTP_POST_VARS)) {
      if ($key != 'x' && $key != 'y') {
        if (is_array($value)) {
          for ($i=0; $i<sizeof($value); $i++) {
            echo osc_draw_hidden_field($key . '[]', $value[$i]);
          }
        } else {
          echo osc_draw_hidden_field($key, $value);
        }
      }
    }
?>

</form>

<?php
  } elseif ( ( (file_exists($dir_fs_document_root . 'includes/configure.php')) && (!is_writeable($dir_fs_document_root . 'includes/configure.php')) ) || ( (file_exists($dir_fs_document_root . '/admin/includes/configure.php')) && (!is_writeable($dir_fs_document_root . '/admin/includes/configure.php')) ) ) {
?>
<form name="install" action="install.php?step=7" method="post">

<table width="95%" border="0" cellpadding="2" class="formPage">
  <tr>
    <td>
      <p>Si � verificato il seguente errore:</p>
      <p><div class="boxMe"><b>Il file di configurazione non esiste o i permessi in scrittura non sono abilitati.</b><br><br>Prova a verificare con le seguenti azioni:
        <ul class="boxMe"><li>cd <?php echo $dir_fs_document_root; ?>includes/</li><li>touch configure.php</li><li>chmod 706 configure.php</li></ul>
        <ul class="boxMe"><li>cd <?php echo $dir_fs_document_root; ?>admin/includes/</li><li>touch configure.php</li><li>chmod 706 configure.php</li></ul></div>
      </p>
      <p class="noteBox">Se <i>chmod 706</i> non funziona, prova con <i>chmod 777</i>.</p>
      <p class="noteBox">Se stai utilizzando Microsoft Windows, prova a rinominare gli eventuali file di configurazione gi� presenti, ne verranno creati 2 nuovi.</p>
    </td>
  </tr>
</table>

<p>&nbsp;</p>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><a href="index.php"><img src="images/button_cancel.gif" border="0" alt="Cancel"></a></td>
    <td align="center"><input type="image" src="images/button_retry.gif" border="0" alt="Retry"></td>
  </tr>
</table>

<?php
    reset($HTTP_POST_VARS);
    while (list($key, $value) = each($HTTP_POST_VARS)) {
      if ($key != 'x' && $key != 'y') {
        if (is_array($value)) {
          for ($i=0; $i<sizeof($value); $i++) {
            echo osc_draw_hidden_field($key . '[]', $value[$i]);
          }
        } else {
          echo osc_draw_hidden_field($key, $value);
        }
      }
    }
?>

</form>

<?php
  } else {
    $http_url = parse_url($HTTP_POST_VARS['HTTP_WWW_ADDRESS']);
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
    if (isset($HTTP_POST_VARS['HTTPS_WWW_ADDRESS']) && !empty($HTTP_POST_VARS['HTTPS_WWW_ADDRESS'])) {
      $https_url = parse_url($HTTP_POST_VARS['HTTPS_WWW_ADDRESS']);
      $https_server = $https_url['scheme'] . '://' . $https_url['host'];
      $https_catalog = $https_url['path'];

      if (isset($https_url['port']) && !empty($https_url['port'])) {
        $https_server .= ':' . $https_url['port'];
      }

      if (substr($https_catalog, -1) != '/') {
        $https_catalog .= '/';
      }
    }

    $enable_ssl = (isset($HTTP_POST_VARS['ENABLE_SSL']) && ($HTTP_POST_VARS['ENABLE_SSL'] == 'true') ? 'true' : 'false');
    $http_cookie_domain = $HTTP_POST_VARS['HTTP_COOKIE_DOMAIN'];
    $https_cookie_domain = (isset($HTTP_POST_VARS['HTTPS_COOKIE_DOMAIN']) ? $HTTP_POST_VARS['HTTPS_COOKIE_DOMAIN'] : '');
    $http_cookie_path = $HTTP_POST_VARS['HTTP_COOKIE_PATH'];
    $https_cookie_path = (isset($HTTP_POST_VARS['HTTPS_COOKIE_PATH']) ? $HTTP_POST_VARS['HTTPS_COOKIE_PATH'] : '');
	$db_server = $HTTP_POST_VARS['DB_SERVER'];
	$db_server_username = $HTTP_POST_VARS['DB_SERVER_USERNAME'];
	$db_server_password = $HTTP_POST_VARS['DB_SERVER_PASSWORD'];
	$db_server_db_name = $HTTP_POST_VARS['DB_DATABASE'];
	$db_server_use_pconnect = ($HTTP_POST_VARS['USE_PCONNECT'] == 'true' ? 'true' : 'false');
	$db_server_store_sessions = ($HTTP_POST_VARS['STORE_SESSIONS'] == 'files' ? '' : 'mysql');
    
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
	$file_contents="<?php\r\n".$file_contents."?>";
    $fp = fopen($dir_fs_document_root . 'includes/configure.php', 'w');
    if (!$fp) { ?>
    	
    <table width="95%" border="0" cellpadding="2" class="formPage">
  <tr>
    <td>
      <p>Si � verificato il seguente errore:</p>
      <p><div class="boxMe"><b>Il file di configurazione non esiste o i permessi in scrittura non sono abilitati.</b><br><br>Prova a verificare con le seguenti azioni:
        <ul class="boxMe"><li>cd <?php echo $dir_fs_document_root; ?>includes/</li><li>touch configure.php</li><li>chmod 706 configure.php</li></ul>
        <ul class="boxMe"><li>cd <?php echo $dir_fs_document_root; ?>admin/includes/</li><li>touch configure.php</li><li>chmod 706 configure.php</li></ul></div>
      </p>
      <p class="noteBox">Se <i>chmod 706</i> non funziona, prova con <i>chmod 777</i>.</p>
      <p class="noteBox">Se <i>chmod 777</i> non funziona e sei su un server di test, prova con <i><br>chmod -R 777 *<br> su tutta la dir <?php echo $dir_fs_document_root; ?></i>.</p>
      <p class="noteBox">Se stai utilizzando Microsoft Windows, prova a rinominare gli eventuali file di configurazione gi� presenti, ne verranno creati 2 nuovi.</p>
    </td>
  </tr>
</table>
    	
    <?	
    }
    fputs($fp, $file_contents);
    fclose($fp);
    @chmod($dir_fs_document_root . 'includes/configure.php', 0555);

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
	$file_contents="<?php\r\n".$file_contents."?>";
    $fp = fopen($dir_fs_document_root . 'admin/includes/configure.php', 'w');
    fputs($fp, $file_contents);
    fclose($fp);
    @chmod($dir_fs_document_root . 'admin/includes/configure.php', 0555);
/*
 * PWS bof
 * Creazione della directory cache in /tmp
 * ed impostazione della variabile di configurazione DIR_FS_CACHE
 */
	$cache_dir=$dir_fs_document_root.'tmp';
	if (!file_exists($cache_dir)){
		mkdir($cache_dir, 0777);
	}else{
		if (!is_dir($cache_dir))
			die("La directory $cache_dir non può essere creata perchè esiste gi&agrave; un file con lo stesso nome<br/>\r\n");
		else
			chmod($cache_dir, 0777);
	}
	$cache_dir.='/';
	if (osc_db_num_rows(osc_db_query("select * from configuration where configuration_key='DIR_FS_CACHE'"))){
		osc_db_query("update configuration set configuration_value='$cache_dir' where configuration_key='DIR_FS_CACHE'");
	}else{
		osc_db_query("insert into configuration set configuration_title='Cache Directory', configuration_description='The directory where the cached files are saved', configuration_group_id='11', sort_order='2', date_added=now(), configuration_value='$cache_dir', configuration_key='DIR_FS_CACHE'");
	}
	osc_db_query("update configuration set configuration_value='$cache_dir' where configuration_key='SESSION_WRITE_DIRECTORY'");
	//////////////////////////////////////////////////////////////////
	// Copia delle directories delle risorse iniziali nella root del sito
	$resources_dir=$dir_fs_document_root.'install/init_resources/';
	$destination_dir=$dir_fs_document_root;
?>
<table width="95%" border="0" cellpadding="2" class="formPage">
  <tr>
    <td>
<?php
	$error=move_dir_contents($resources_dir,$destination_dir,true,false,false,array('.svn'));
?>
</td>
 </tr>
</table>
<?php
	if ($error==false){
	//////////////////////////////////////////////////////////////////
	// Copia delle directories dei sorgenti aggiornabili nella root del sito
	$resources_dir=$dir_fs_document_root.'install/updated_sources/';
	$destination_dir=$dir_fs_document_root;
?>
<table width="95%" border="0" cellpadding="2" class="formPage">
  <tr>
    <td>
<?php
	$error=move_dir_contents($resources_dir,$destination_dir,true,false,true,array('.svn'));
?>
</td>
 </tr>
</table>
<?php
	}
	if ($error!=false){
?>

<table width="95%" border="0" cellpadding="2" class="formPage">
  <tr>
    <td>Si � verificato un problema durante l'installazione:</td>
 </tr>
 <tr>
 	<td><?=$error?></td>
 </tr>
</table>
<?php
	}else{
/*
 * PWS eof
 */
?>

<table width="95%" border="0" cellpadding="2" class="formPage">
  <tr>
    <td>The configuration was successful!</td>
 </tr>
</table>

<p>&nbsp;</p>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><a href="<?php echo $http_server . $http_catalog . 'index.php'; ?>" target="_blank"><img src="images/button_catalog.gif" border="0" alt="Catalog"></a></td>
    <td align="center"><a href="<?php echo $http_server . $http_catalog . 'admin/index.php'; ?>" target="_blank"><img src="images/button_administration_tool.gif" border="0" alt="Administration Tool"></a></td>
  </tr>
</table>

<?php
	}
  }
?>