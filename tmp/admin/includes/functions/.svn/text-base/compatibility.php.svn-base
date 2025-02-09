<?php
/*
  $Id: compatibility.php,v 1.10 2003/06/23 01:20:05 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/

////
// Recursively handle magic_quotes_gpc turned off.
// This is due to the possibility of have an array in
// $HTTP_xxx_VARS
// Ie, products attributes
  function do_magic_quotes_gpc(&$ar) {
    if (!is_array($ar)) return false;

    reset($ar);
    while (list($key, $value) = each($ar)) {
      if (is_array($ar[$key])) {
        do_magic_quotes_gpc($ar[$key]);
      } else {
        $ar[$key] = addslashes($value);
      }
    }
    reset($ar);
  }

  if (PHP_VERSION >= 4.1) {
    $HTTP_GET_VARS =& $_GET;
    $HTTP_POST_VARS =& $_POST;
    $HTTP_COOKIE_VARS =& $_COOKIE;
    $HTTP_SESSION_VARS =& $_SESSION;
    $HTTP_POST_FILES =& $_FILES;
    $HTTP_SERVER_VARS =& $_SERVER;
  } else {
  if (!is_array($HTTP_GET_VARS)) $HTTP_GET_VARS = array();
  if (!is_array($HTTP_POST_VARS)) $HTTP_POST_VARS = array();
  if (!is_array($HTTP_COOKIE_VARS)) $HTTP_COOKIE_VARS = array();
  }

// handle magic_quotes_gpc turned off.
  if (!get_magic_quotes_gpc()) {
    do_magic_quotes_gpc($HTTP_GET_VARS);
    do_magic_quotes_gpc($HTTP_POST_VARS);
    do_magic_quotes_gpc($HTTP_COOKIE_VARS);
  }

  if (!function_exists('is_numeric')) {
    function is_numeric($param) {
      return ereg("^[0-9]{1,50}.?[0-9]{0,50}$", $param);
    }
  }

  if (!function_exists('is_uploaded_file')) {
    function is_uploaded_file($filename) {
      if (!$tmp_file = get_cfg_var('upload_tmp_dir')) {
        $tmp_file = dirname(tempnam('', ''));
      }

      if (strchr($tmp_file, '/')) {
        if (substr($tmp_file, -1) != '/') $tmp_file .= '/';
      } elseif (strchr($tmp_file, '\\')) {
        if (substr($tmp_file, -1) != '\\') $tmp_file .= '\\';
      }

      return file_exists($tmp_file . basename($filename));
    }
  }

  if (!function_exists('move_uploaded_file')) {
    function move_uploaded_file($file, $target) {
      return copy($file, $target);
    }
  }

  if (!function_exists('checkdnsrr')) {
    function checkdnsrr($host, $type) {
      if(tep_not_null($host) && tep_not_null($type)) {
        @exec("nslookup -type=$type $host", $output);
        while(list($k, $line) = each($output)) {
          if(eregi("^$host", $line)) {
            return true;
          }
        }
      }
      return false;
    }
  }

  if (!function_exists('in_array')) {
    function in_array($lookup_value, $lookup_array) {
      reset($lookup_array);
      while (list($key, $value) = each($lookup_array)) {
        if ($value == $lookup_value) return true;
      }

      return false;
    }
  }

  if (!function_exists('array_merge')) {
    function array_merge($array1, $array2, $array3 = '') {
      if ($array3 == '') $array3 = array();

      while (list($key, $val) = each($array1)) $array_merged[$key] = $val;
      while (list($key, $val) = each($array2)) $array_merged[$key] = $val;

      if (sizeof($array3) > 0) while (list($key, $val) = each($array3)) $array_merged[$key] = $val;

      return (array)$array_merged;
    }
  }

  if (!function_exists('array_shift')) {
    function array_shift(&$array) {
      $i = 0;
      $shifted_array = array();
      reset($array);
      while (list($key, $value) = each($array)) {
        if ($i > 0) {
          $shifted_array[$key] = $value;
        } else {
          $return = $array[$key];
        }
        $i++;
      }
      $array = $shifted_array;

      return $return;
    }
  }

  if (!function_exists('array_reverse')) {
    function array_reverse($array) {
      $reversed_array = array();

      for ($i=sizeof($array)-1; $i>=0; $i--) {
        $reversed_array[] = $array[$i];
      }

      return $reversed_array;
    }
  }

  if (!function_exists('array_slice')) {
    function array_slice($array, $offset, $length = '0') {
      $length = abs($length);

      if ($length == 0) {
        $high = sizeof($array);
      } else {
        $high = $offset+$length;
      }

      for ($i=$offset; $i<$high; $i++) {
        $new_array[$i-$offset] = $array[$i];
      }

      return $new_array;
    }
  }

/*
 * http_build_query() natively supported from PHP 5.0
 * From Pear::PHP_Compat
 */

  if ( !function_exists('http_build_query') && (PHP_VERSION >= 4)) {
    function http_build_query($formdata, $numeric_prefix = null, $arg_separator = null) {
// If $formdata is an object, convert it to an array
      if ( is_object($formdata) ) {
        $formdata = get_object_vars($formdata);
      }

// Check we have an array to work with
      if ( !is_array($formdata) || !empty($formdata) ) {
        return false;
      }

// Argument seperator
      if ( empty($arg_separator) ) {
        $arg_separator = ini_get('arg_separator.output');

        if ( empty($arg_separator) ) {
          $arg_separator = '&';
        }
      }

// Start building the query
      $tmp = array();

      foreach ( $formdata as $key => $val ) {
        if ( is_null($val) ) {
          continue;
        }

        if ( is_integer($key) && ( $numeric_prefix != null ) ) {
          $key = $numeric_prefix . $key;
        }

        if ( is_scalar($val) ) {
          array_push($tmp, urlencode($key) . '=' . urlencode($val));
          continue;
        }

// If the value is an array, recursively parse it
        if ( is_array($val) || is_object($val) ) {
          array_push($tmp, http_build_query_helper($val, urlencode($key), $arg_separator));
          continue;
        }

// The value is a resource
        return null;
      }

      return implode($arg_separator, $tmp);
    }

// Helper function
    function http_build_query_helper($array, $name, $arg_separator) {
      $tmp = array();

      foreach ( $array as $key => $value ) {
        if ( is_array($value) ) {
          array_push($tmp, http_build_query_helper($value, sprintf('%s[%s]', $name, $key), $arg_separator));
        } elseif ( is_scalar($value) ) {
          array_push($tmp, sprintf('%s[%s]=%s', $name, urlencode($key), urlencode($value)));
        } elseif ( is_object($value) ) {
          array_push($tmp, http_build_query_helper(get_object_vars($value), sprintf('%s[%s]', $name, $key), $arg_separator));
        }
      }

      return implode($arg_separator, $tmp);
    }
  }
////////////////////////////////////////////////////////////////////////
//PWS bof
// PHP5 constants
  if (!defined('E_RECOVERABLE_ERROR'))
		define('E_RECOVERABLE_ERROR','4096');
	if (!defined('E_STRICT'))
		define('E_STRICT','2048');
	if (!defined('M_SQRTPI'))
		define('M_SQRTPI','1.77245385091');
	if (!defined('M_LNPI'))
		define('M_LNPI','1.14472988585');
	if (!defined('M_EULER'))
		define('M_EULER','0.577215664902');
	if (!defined('M_SQRT3'))
		define('M_SQRT3','1.73205080757');
	if (!defined('STREAM_FILTER_READ'))
		define('STREAM_FILTER_READ','1');
	if (!defined('STREAM_FILTER_WRITE'))
		define('STREAM_FILTER_WRITE','2');
	if (!defined('STREAM_FILTER_ALL'))
		define('STREAM_FILTER_ALL','3');
	if (!defined('STREAM_CLIENT_PERSISTENT'))
		define('STREAM_CLIENT_PERSISTENT','1');
	if (!defined('STREAM_CLIENT_ASYNC_CONNECT'))
		define('STREAM_CLIENT_ASYNC_CONNECT','2');
	if (!defined('STREAM_CLIENT_CONNECT'))
		define('STREAM_CLIENT_CONNECT','4');
	if (!defined('STREAM_CRYPTO_METHOD_SSLv2_CLIENT'))
		define('STREAM_CRYPTO_METHOD_SSLv2_CLIENT','0');
	if (!defined('STREAM_CRYPTO_METHOD_SSLv3_CLIENT'))
		define('STREAM_CRYPTO_METHOD_SSLv3_CLIENT','1');
	if (!defined('STREAM_CRYPTO_METHOD_SSLv23_CLIENT'))
		define('STREAM_CRYPTO_METHOD_SSLv23_CLIENT','2');
	if (!defined('STREAM_CRYPTO_METHOD_TLS_CLIENT'))
		define('STREAM_CRYPTO_METHOD_TLS_CLIENT','3');
	if (!defined('STREAM_CRYPTO_METHOD_SSLv2_SERVER'))
		define('STREAM_CRYPTO_METHOD_SSLv2_SERVER','4');
	if (!defined('STREAM_CRYPTO_METHOD_SSLv3_SERVER'))
		define('STREAM_CRYPTO_METHOD_SSLv3_SERVER','5');
	if (!defined('STREAM_CRYPTO_METHOD_SSLv23_SERVER'))
		define('STREAM_CRYPTO_METHOD_SSLv23_SERVER','6');
	if (!defined('STREAM_CRYPTO_METHOD_TLS_SERVER'))
		define('STREAM_CRYPTO_METHOD_TLS_SERVER','7');
	if (!defined('STREAM_SHUT_RD'))
		define('STREAM_SHUT_RD','0');
	if (!defined('STREAM_SHUT_WR'))
		define('STREAM_SHUT_WR','1');
	if (!defined('STREAM_SHUT_RDWR'))
		define('STREAM_SHUT_RDWR','2');
	if (!defined('STREAM_PF_INET'))
		define('STREAM_PF_INET','2');
	if (!defined('STREAM_PF_INET6'))
		define('STREAM_PF_INET6','23');
	if (!defined('STREAM_PF_UNIX'))
		define('STREAM_PF_UNIX','1');
	if (!defined('STREAM_IPPROTO_IP'))
		define('STREAM_IPPROTO_IP','0');
	if (!defined('STREAM_IPPROTO_TCP'))
		define('STREAM_IPPROTO_TCP','6');
	if (!defined('STREAM_IPPROTO_UDP'))
		define('STREAM_IPPROTO_UDP','17');
	if (!defined('STREAM_IPPROTO_ICMP'))
		define('STREAM_IPPROTO_ICMP','1');
	if (!defined('STREAM_IPPROTO_RAW'))
		define('STREAM_IPPROTO_RAW','255');
	if (!defined('STREAM_SOCK_STREAM'))
		define('STREAM_SOCK_STREAM','1');
	if (!defined('STREAM_SOCK_DGRAM'))
		define('STREAM_SOCK_DGRAM','2');
	if (!defined('STREAM_SOCK_RAW'))
		define('STREAM_SOCK_RAW','3');
	if (!defined('STREAM_SOCK_SEQPACKET'))
		define('STREAM_SOCK_SEQPACKET','5');
	if (!defined('STREAM_SOCK_RDM'))
		define('STREAM_SOCK_RDM','4');
	if (!defined('STREAM_PEEK'))
		define('STREAM_PEEK','2');
	if (!defined('STREAM_OOB'))
		define('STREAM_OOB','1');
	if (!defined('STREAM_SERVER_BIND'))
		define('STREAM_SERVER_BIND','4');
	if (!defined('STREAM_SERVER_LISTEN'))
		define('STREAM_SERVER_LISTEN','8');
	if (!defined('FILE_USE_INCLUDE_PATH'))
		define('FILE_USE_INCLUDE_PATH','1');
	if (!defined('FILE_IGNORE_NEW_LINES'))
		define('FILE_IGNORE_NEW_LINES','2');
	if (!defined('FILE_SKIP_EMPTY_LINES'))
		define('FILE_SKIP_EMPTY_LINES','4');
	if (!defined('FILE_APPEND'))
		define('FILE_APPEND','8');
	if (!defined('FILE_NO_DEFAULT_CONTEXT'))
		define('FILE_NO_DEFAULT_CONTEXT','16');
	if (!defined('PSFS_PASS_ON'))
		define('PSFS_PASS_ON','2');
	if (!defined('PSFS_FEED_ME'))
		define('PSFS_FEED_ME','1');
	if (!defined('PSFS_ERR_FATAL'))
		define('PSFS_ERR_FATAL','0');
	if (!defined('PSFS_FLAG_NORMAL'))
		define('PSFS_FLAG_NORMAL','0');
	if (!defined('PSFS_FLAG_FLUSH_INC'))
		define('PSFS_FLAG_FLUSH_INC','1');
	if (!defined('PSFS_FLAG_FLUSH_CLOSE'))
		define('PSFS_FLAG_FLUSH_CLOSE','2');

	if (!defined('STREAM_URL_STAT_LINK'))
		define('STREAM_URL_STAT_LINK','1');
	if (!defined('STREAM_URL_STAT_QUIET'))
		define('STREAM_URL_STAT_QUIET','2');
	if (!defined('STREAM_MKDIR_RECURSIVE'))
		define('STREAM_MKDIR_RECURSIVE','1');
	if (!defined('STREAM_IS_URL'))
		define('STREAM_IS_URL','1');
		
	if (!defined('GLOB_ERR'))
		define('GLOB_ERR','4');
	if (!defined('GLOB_AVAILABLE_FLAGS'))
		define('GLOB_AVAILABLE_FLAGS','1073746108');

	if (!defined('T_TRY'))
		define('T_TRY','336');
	if (!defined('T_CATCH'))
		define('T_CATCH','337');
	if (!defined('T_THROW'))
		define('T_THROW','338');

	if (!defined('T_PUBLIC'))
		define('T_PUBLIC','341');
	if (!defined('T_PROTECTED'))
		define('T_PROTECTED','342');
	if (!defined('T_PRIVATE'))
		define('T_PRIVATE','343');
	if (!defined('T_FINAL'))
		define('T_FINAL','344');
	if (!defined('T_ABSTRACT'))
		define('T_ABSTRACT','345');

	if (!defined('LOCK_SH'))
		define('LOCK_SH','1');
	if (!defined('LOCK_EX'))
		define('LOCK_EX','2');
	if (!defined('LOCK_UN'))
		define('LOCK_UN','3');
	if (!defined('LOCK_NB'))
		define('LOCK_NB','4');

if (!function_exists('file_put_contents')) {
	// @function file_put_contents
	// @note 	Funzione del php5
	// @param	(string)	$filename		The file name where to write the data 
	// @param	(mixed)		$data			The data to write. Can be either a string, an array or a stream resource. 
	// @param	(int)		$flags			[Opzionale]	flags can take FILE_USE_INCLUDE_PATH, FILE_APPEND and/or LOCK_EX (acquire an exclusive lock), however the FILE_USE_INCLUDE_PATH option should be used with caution
	// @param	(resource)	$context		[Opzionale] A context resource 
	// @return			The function returns the amount of bytes that were written to the file, or FALSE on failure. 
	function file_put_contents(/*string*/ $filename, /*mixed*/ $data , /*int*/ $flags=0 , /*resource*/ $context=NULL )
    {
        // Open the file for writing
        $fh = @fopen($filename, 'w');
        if ($fh === false) {
            return false;
        }
		$respect_lock = $flags & LOCK_EX;
		$use_include_path = $flags & FILE_USE_INCLUDE_PATH;
		$append = $flags & FILE_APPEND;
		// Check to see if we want to make sure the file is locked before we write to it
        if ($respect_lock === true && !flock($fh, LOCK_EX)) {
            fclose($fh);
            return false;
        }

        // Convert the data to an acceptable string format
        if (is_array($data)) {
            $data = implode('', $data);
        } else {
            $data = (string) $data;
        }

        // Write the data to the file and close it
        $bytes = fwrite($fh, $data);

        // This will implicitly unlock the file if it's locked
        fclose($fh);

        return $bytes;
    }
}
// PWS eof

/*
 * stripos() natively supported from PHP 5.0
 * From Pear::PHP_Compat
 */

  if (!function_exists('stripos')) {
    function stripos($haystack, $needle, $offset = null) {
      $fix = 0;

      if (!is_null($offset)) {
        if ($offset > 0) {
          $haystack = substr($haystack, $offset, strlen($haystack) - $offset);
          $fix = $offset;
        }
      }

      $segments = explode(strtolower($needle), strtolower($haystack), 2);

// Check there was a match
      if (count($segments) == 1) {
        return false;
      }

      $position = strlen($segments[0]) + $fix;

      return $position;
    }
  }
?>
