<?php
/**
 * Created on 9 Oct. 2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 chdir('../');
// Set the local configuration parameters - mainly for developers
  if (file_exists('includes/local/configure.php'))
  	include('includes/local/configure.php');

// include server parameters
  if (file_exists('includes/configure.php'))
  	require('includes/configure.php');
  	
// some code to solve compatibility issues
  require(DIR_WS_FUNCTIONS . 'compatibility.php');
  
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

// define general functions used application-wide
  require(DIR_WS_FUNCTIONS . 'general.php');
  require(DIR_WS_FUNCTIONS . 'html_output.php');

// check to see if php implemented session management functions - if not, include php3/php4 compatible session class
  if (!function_exists('session_start')) {
    define('PHP_SESSION_NAME', 'osCAdminID');
    define('PHP_SESSION_PATH', '/');
    define('PHP_SESSION_SAVE_PATH', SESSION_WRITE_DIRECTORY);

    include(DIR_WS_CLASSES . 'sessions.php');
  }

// define how the session functions will be used
  require(DIR_WS_FUNCTIONS . 'sessions.php');

// set the session name and save path
  tep_session_name('osCAdminID');
  tep_session_save_path(SESSION_WRITE_DIRECTORY);

// set the session cookie parameters
   if (function_exists('session_set_cookie_params')) {
    session_set_cookie_params(0, DIR_WS_ADMIN);
  } elseif (function_exists('ini_set')) {
    ini_set('session.cookie_lifetime', '0');
    ini_set('session.cookie_path', DIR_WS_ADMIN);
  }

// lets start our session
  tep_session_start();

  if ( (PHP_VERSION >= 4.3) && function_exists('ini_get') && (ini_get('register_globals') == false) ) {
    extract($_SESSION, EXTR_OVERWRITE+EXTR_REFS);
  }

// set the language
  if (!tep_session_is_registered('language') || isset($HTTP_GET_VARS['language'])) {
    if (!tep_session_is_registered('language')) {
      tep_session_register('language');
      tep_session_register('languages_id');
    }

    include(DIR_WS_CLASSES . 'language.php');
    $lng = new language();

    if (isset($HTTP_GET_VARS['language']) && tep_not_null($HTTP_GET_VARS['language'])) {
      $lng->set_language($HTTP_GET_VARS['language']);
    } else {
      $lng->get_browser_language();
    }

    $language = $lng->language['directory'];
    $languages_id = $lng->language['id'];
  }
  if($HTTP_GET_VARS['languages_id']) $languages_id = $HTTP_GET_VARS['languages_id'];

// include the language translations
  require(DIR_WS_LANGUAGES . $language . '.php');
  $current_page = basename($PHP_SELF);
  
  if (file_exists(DIR_WS_LANGUAGES . $language . '/' . $current_page)) {
    include(DIR_WS_LANGUAGES . $language . '/' . $current_page);
  }

  require_once('icecat/include.php');
  require_once DIR_WS_ICECAT_LIBS . 'jshttprequest/config.php';
  require_once DIR_WS_ICECAT_LIBS . 'jshttprequest/Subsys/JsHttpRequest/JsHttpRequest.php';
  
	function getJSString($arr = array()) {
		$text = 'value=new Array();';
		foreach ($arr as $k => $v) $text .= 'value[' . $k . ']=new Array();'.
		'value[' . $k . '].name="' . $v['name'] . '";'.
		'value[' . $k . '].text="' . addslashes($v['text']) . '";' . (strlen(trim($v['js']))>0 ? $v['js']  .';' : '');
		return $text;
	}
	function prepareString($str) {
		$str = str_replace("\n", ' ', $str);
		$str = str_replace("\r", ' ', $str);
		return $str;
	}
	$jsText = array();
	$mArr = array();
	define('DEBUG_MOD', false);
	$JsHttpRequest =& new JsHttpRequest("ISO-8859-1");
	$q = $_REQUEST['q'];
  	if(tep_not_null($pid)) {
  		switch ( $action ) {
			case 'psearch':
				if($val && strlen($val)>0) {
//					$jsText[]='alert(\'div\');';
					$sql = 'SELECT products_id, products_name FROM products_description WHERE  language_id=' . (int)$languages_id . ' AND products_name LIKE \'%' . tep_db_input($val) . '%\' LIMIT 10';
//					
					$sql = tep_db_query($sql);
					
					$jsText[]='var ps=document.getElementById(\'pSearchMenuRelProd\');';
					$jsText[]='while(ps.childNodes.length>0) ps.removeChild(ps.lastChild);';
					while($row = tep_db_fetch_array($sql)) {
						$jsText[]='var pss=document.createElement(\'div\');';
						$jsText[]='pss.style.padding=\'4px\';';
						$jsText[]='var a=document.createElement(\'a\');';
						$jsText[]='a.innerHTML=\'' . addslashes($row['products_name']) . '\';';
						$jsText[]='a.href=\'javascript:doLoad(\\\'action=save&pid=' . $pid . '&tpid=' . $row['products_id'] . '\\\',\\\'pof\\\');\';';
						$jsText[]='pss.appendChild(a);';
						$jsText[]='ps.appendChild(pss);';
					}
					$jsText[]='doLoad(\'pid=' . $pid . '\',\'\');';
				}
				break;
			case 'clear':
				$jsText[]='var ps=document.getElementById(\'pSearchMenuRelProd\');';
				$jsText[]='while(ps.childNodes.length>0) ps.removeChild(ps.lastChild);';
				$jsText[]='doLoad(\'pid=' . $pid . '\',\'\');';
				break;
			case 'save':
				$jsText[]='var ps=document.getElementById(\'pSearchMenuRelProd\');';
				$jsText[]='while(ps.childNodes.length>0) ps.removeChild(ps.lastChild);';
				if($pid && $tpid) {
					$arrSql = array(
						'products_id'=>$pid,
						'to_products_id'=>$tpid
					);
					tep_db_perform('pws_related_products', $arrSql);
				}
				$jsText[]='doLoad(\'pid=' . $pid . '\',\'\');';
				break;
			case 'srchArea':
				$sql = 'SELECT products_id, products_name FROM products_description WHERE  language_id=' . (int)$languages_id . ' ORDER by products_name';
				$sql = tep_db_query($sql);
				$jsText[]='var ps2=document.getElementById(\'prdID\');';
				$jsText[]='var chld=document.createElement(\'option\');';
				$jsText[]='chld.value=\'\';';
				$jsText[]='ps2.appendChild(chld);';
				while($row=tep_db_fetch_array($sql)) {
					$jsText[]='var chld=document.createElement(\'option\');';
					$jsText[]='chld.value=\'' . $row['products_id'] . '\';';
					$jsText[]='chld.innerHTML=\'' . addslashes($row['products_name']) . '\';';
					$jsText[]='ps2.appendChild(chld);';
				}
				break;
			case 'delete':
				if($tpid) {
					$sql = 'DELETE FROM pws_related_products WHERE products_id=\'' . $pid . '\' AND to_products_id=\'' . $tpid . '\'';
					tep_db_query($sql);
					$jsText[]='doLoad(\'pid=' . $pid . '\',\'\');';
				}
				break;
			default:
				$jsText[]='var ps=document.getElementById(\'contRelProd\');ps.style.marginTop=\'10px\';';
				$jsText[]='while(ps.childNodes.length>0) ps.removeChild(ps.lastChild);';
//				$jsText[]='var ps=document.getElementById(\'contRelProd\');';
				$sql = 'SELECT pd.products_id, pd.products_name FROM products_description pd, pws_related_products prp WHERE prp.products_id=\''.$pid.'\' AND pd.products_id=prp.to_products_id AND pd.language_id=' . (int)$languages_id;
				$sql = tep_db_query($sql);
				while($row=tep_db_fetch_array($sql)) {
					$jsText[] = 'var p=document.createElement(\'div\');p.style.padding=\'4px\';';
					$jsText[] = 'var a=document.createElement(\'a\');';
					$jsText[] = 'a.href=\'javascript:doLoad(\\\'pid=' . $pid . '&tpid=' . $row['products_id'] . '&action=delete\\\',\\\'asd\\\');\';';
					$jsText[] = 'p.appendChild(a);';
					$jsText[] = 'var img=document.createElement(\'img\');';
					$jsText[] = 'img.src=\'images/icons/icon_delete.gif\';';
					$jsText[] = 'img.border=\'0px\';';
					$jsText[] = 'img.align=\'absmiddle\';';
					$jsText[] = 'a.appendChild(img);';
					$jsText[] = 'var a=document.createElement(\'a\');';
					$jsText[] = 'a.innerHTML=\'' . addslashes($row['products_name']) . '\';';
					$jsText[] = 'p.appendChild(a);';
					$jsText[] = 'ps.appendChild(p);';
				}
				break;
		}
  	}
	$_RESULT = array(
	  "q"   => $q,
	  "text" => prepareString(getJSString($mArr)),
	  "jsText" =>implode('',prepareString($jsText))
	);
	
	if (strpos($q, 'error') !== false) {
	  callUndefinedFunction();
	}
	if (@$_REQUEST['dt']) {
	  sleep($_REQUEST['dt']);
	}
	
//  	tep_db_close($link);
?>