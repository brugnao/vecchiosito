<?php
/*
 $Id: html_output.php,v 1.56 2003/07/09 01:15:48 hpdl Exp $

 osCommerce, Open Source E-Commerce Solutions
 http://www.oscommerce.com

 Copyright (c) 2007 osCommerce

 Released under the GNU General Public License
 */

////
// The HTML href link wrapper function
////
// Ultimate SEO URLs v2.1
// The HTML href link wrapper function
function tep_href_link($page = '', $parameters = '', $connection = 'NONSSL', $add_session_id = true, $search_engine_safe = true) {
	global $seo_urls;
	if ( !is_object($seo_urls) ){
		if ( !class_exists('SEO_URL') ){
			include_once(DIR_WS_CLASSES . 'seo.class.php');
		}
		global $languages_id;
		$seo_urls = new SEO_URL($languages_id);
	}
	return $seo_urls->href_link($page, $parameters, $connection, $add_session_id);
}

////
// The HTML image wrapper function
/*  function tep_image($src, $alt = '', $width = '', $height = '', $parameters = '') {
 if ( (empty($src) || ($src == DIR_WS_IMAGES)) && (IMAGE_REQUIRED == 'false') ) {
 return false;
 }
 */
// "On the Fly" Auto Thumbnailer using GD Library, servercaching and browsercaching
// Scales product images dynamically, resulting in smaller file sizes, and keeps
// proper image ratio. Used in conjunction with product_thumb.php t/n generator.
require_once DIR_WS_FUNCTIONS.'thumbnails.php';
function tep_image($src, $alt = '', $width = '', $height = '', $params = '') {
	$alt=strip_tags($alt);
	$alt=str_replace("\r",'',$alt);
	$alt=str_replace("\n",'. ',$alt);
	$src=urldecode($src);
	// if (!@getimagesize($src) && IMAGE_REQUIRED == 'false') {
	if (($src == '') && IMAGE_REQUIRED == 'false') {
		return '';
		break;
	}
	$thumbnail_ok=false;
	if ($width != '' && $height != '' && false===strpos($width,'%') && false===strpos($height,'%'))
	{
		//echo "imgsrc:$src<br/>";
		if (false!==($imgattrs=getThumbnail($src,$width,$height))){
			$src=$imgattrs['baseFileName'];
			//$f=(strtolower(substr($src,-3))=='gif')?'&f=gif':'';
			//if (strstr($src,'.jpg') || strstr($src,'.jpeg'))
			//$src=tep_href_link("includes/functions/phpThumb.php","src=$src&w=$width&h=$height&ws=$width&hs=$height&wp=$width&hp=$height&f=jpeg");
			//else
			//	$src=tep_href_link("includes/functions/phpThumb.php","src=$src&w=$width&h=$height");
			$thumbnail_ok=true;
		}
		//		echo $src.'<br>';
		//		$img_path='';
		//		$img_name='';
		//		$imgattrs=$gd_thumbnail->fitThumbnail($src,$width,$height, 1 , true, false, true);
		//		$src=substr($imgattrs,strlen(DIR_FS_CATALOG));
		//echo($src.'<br>'.DIR_WS_CACHE.'<br>'.strpos($imgattrs['filename'],DIR_WS_CACHE.'<br>'));print_r($imgattrs);
	}
	// _GD_ stop
	$image = '<img src="' . tep_output_string($src) . '" border="0" alt="' . tep_output_string($alt) . '"';

	if (tep_not_null($alt)) {
		$image .= ' title=" ' . tep_output_string($alt) . ' "';
	}

	if ( false && (CONFIG_CALCULATE_IMAGE_SIZE == 'true') && (empty($width) || empty($height)) ) {
		if ($image_size = @getimagesize($src)) {
			if (empty($width) && tep_not_null($height)) {
				$ratio = $height / $image_size[1];
				$width = $image_size[0] * $ratio;
			} elseif (tep_not_null($width) && empty($height)) {
				$ratio = $width / $image_size[0];
				$height = $image_size[1] * $ratio;
			} elseif (empty($width) && empty($height)) {
				$width = $image_size[0];
				$height = $image_size[1];
			}
		} elseif (IMAGE_REQUIRED == 'false') {
			return false;
		}
	}

	if (!$thumbnail_ok && tep_not_null($width) && tep_not_null($height)) {
		$image .= ' width="' . tep_output_string($width) . '" height="' . tep_output_string($height) . '"';
	}

	if (tep_not_null($params)) $image .= ' ' . $params;

	$image .= '>';

	return $image;
	/*
	 // Set default image variable and code
	 $image = '<img src="' . $src . '"';

	 // Don't calculate if the image is set to a "%" width
	 if (strstr($width,'%') == false || strstr($height,'%') == false) {
	 $dont_calculate = 0;
	 } else {
	 $dont_calculate = 1;
	 }

	 // Dont calculate if a pixel image is being passed (hope you dont have pixels for sale)
	 if (!strstr($image, 'pixel')) {
	 $dont_calculate = 0;
	 } else {
	 $dont_calculate = 1;
	 }

	 // Do we calculate the image size?
	 if (CONFIG_CALCULATE_IMAGE_SIZE && !$dont_calculate) {

	 // Get the image's information
	 if ($image_size = @getimagesize($src)) {

	 $ratio = $image_size[1] / $image_size[0];

	 // Set the width and height to the proper ratio
	 if (!$width && $height) {
	 $ratio = $height / $image_size[1];
	 $width = intval($image_size[0] * $ratio);
	 } elseif ($width && !$height) {
	 $ratio = $width / $image_size[0];
	 $height = intval($image_size[1] * $ratio);
	 } elseif (!$width && !$height) {
	 $width = $image_size[0];
	 $height = $image_size[1];
	 }

	 // Scale the image if not the original size
	 if ($image_size[0] != $width || $image_size[1] != $height) {
	 $rx = $image_size[0] / $width;
	 $ry = $image_size[1] / $height;

	 if ($rx < $ry) {
	 $width = intval($height / $ratio);
	 } else {
	 $height = intval($width * $ratio);
	 }

	 $image = '<img src="product_thumb.php?img='.$src.'&w='.
	 tep_output_string($width).'&h='.tep_output_string($height).'"';
	 }

	 } elseif (IMAGE_REQUIRED == 'false') {
	 return '';
	 }
	 }

	 // Add remaining image parameters if they exist
	 if ($width) {
	 $image .= ' width="' . tep_output_string($width) . '"';
	 }

	 if ($height) {
	 $image .= ' height="' . tep_output_string($height) . '"';
	 }

	 if (tep_not_null($params)) $image .= ' ' . $params;

	 $image .= ' border="0" alt="' . tep_output_string($alt) . '"';

	 if (tep_not_null($alt)) {
	 $image .= ' title="' . tep_output_string($alt) . '"';
	 }

	 $image .= '>';

	 return $image;
	 */
}


////
// The HTML form submit button wrapper function
// Outputs a button in the selected language
function tep_image_submit($image, $alt = '', $parameters = '') {
	global $language;

	$image_submit = '<input type="image" src="' . tep_output_string(DIR_WS_LANGUAGES . $language . '/images/buttons/' . $image) . '" border="0" alt="' . tep_output_string($alt) . '"';

	if (tep_not_null($alt)) $image_submit .= ' title=" ' . tep_output_string($alt) . ' "';

	if (tep_not_null($parameters)) $image_submit .= ' ' . $parameters;

	$image_submit .= '>';

	return $image_submit;
}

////
// Output a function button in the selected language
function tep_image_button($image, $alt = '', $parameters = '') {
	global $language;

	return tep_image(DIR_WS_LANGUAGES . $language . '/images/buttons/' . $image, $alt, '', '', $parameters);
}

////
// Output a separator either through whitespace, or with an image
function tep_draw_separator($image = 'pixel_black.gif', $width = '100%', $height = '1') {
	return tep_image(DIR_WS_IMAGES . $image, '', $width, $height);
}

////
// Output a form
function tep_draw_form($name, $action, $method = 'post', $parameters = '') {
	$form = '<form name="' . tep_output_string($name) . '" action="' . tep_output_string($action) . '" method="' . tep_output_string($method) . '"';

	if (tep_not_null($parameters)) $form .= ' ' . $parameters;

	$form .= '>';

	return $form;
}

////
// Output a form input field
function tep_draw_input_field($name, $value = '', $parameters = '', $type = 'text', $reinsert_value = true) {
	global $HTTP_GET_VARS, $HTTP_POST_VARS;

	$field = '<input type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';

	if ( ($reinsert_value == true) && ( (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name])) || (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name])) ) ) {
		if (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name])) {
			$value = stripslashes($HTTP_GET_VARS[$name]);
		} elseif (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name])) {
			$value = stripslashes($HTTP_POST_VARS[$name]);
		}
	}

	if (tep_not_null($value)) {
		$field .= ' value="' . tep_output_string($value) . '"';
	}

	if (tep_not_null($parameters)) $field .= ' ' . $parameters;

	$field .= '>';

	return $field;
}

////
// Output a form password field
function tep_draw_password_field($name, $value = '', $parameters = 'maxlength="40"') {
	return tep_draw_input_field($name, $value, $parameters, 'password', false);
}

////
// Output a selection field - alias function for tep_draw_checkbox_field() and tep_draw_radio_field()
function tep_draw_selection_field($name, $type, $value = '', $checked = false, $parameters = '') {
	global $HTTP_GET_VARS, $HTTP_POST_VARS;

	$selection = '<input type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';

	if (tep_not_null($value)) $selection .= ' value="' . tep_output_string($value) . '"';

	if ( ($checked == true) || (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name]) && (($HTTP_GET_VARS[$name] == 'on') || (stripslashes($HTTP_GET_VARS[$name]) == $value))) || (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name]) && (($HTTP_POST_VARS[$name] == 'on') || (stripslashes($HTTP_POST_VARS[$name]) == $value))) ) {
		$selection .= ' CHECKED';
	}

	if (tep_not_null($parameters)) $selection .= ' ' . $parameters;

	$selection .= '>';

	return $selection;
}

////
// Output a form checkbox field
function tep_draw_checkbox_field($name, $value = '', $checked = false, $parameters = '') {
	return tep_draw_selection_field($name, 'checkbox', $value, $checked, $parameters);
}

////
// Output a form radio field
function tep_draw_radio_field($name, $value = '', $checked = false, $parameters = '') {
	return tep_draw_selection_field($name, 'radio', $value, $checked, $parameters);
}

////
// Output a form textarea field
function tep_draw_textarea_field($name, $wrap, $width, $height, $text = '', $parameters = '', $reinsert_value = true) {
	global $HTTP_GET_VARS, $HTTP_POST_VARS;

	$field = '<textarea name="' . tep_output_string($name) . '" wrap="' . tep_output_string($wrap) . '" cols="' . tep_output_string($width) . '" rows="' . tep_output_string($height) . '"';

	if (tep_not_null($parameters)) $field .= ' ' . $parameters;

	$field .= '>';

	if ( ($reinsert_value == true) && ( (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name])) || (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name])) ) ) {
		if (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name])) {
			$field .= tep_output_string_protected(stripslashes($HTTP_GET_VARS[$name]));
		} elseif (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name])) {
			$field .= tep_output_string_protected(stripslashes($HTTP_POST_VARS[$name]));
		}
	} elseif (tep_not_null($text)) {
		$field .= tep_output_string_protected($text);
	}

	$field .= '</textarea>';

	return $field;
}

////
// Output a form hidden field
function tep_draw_hidden_field($name, $value = '', $parameters = '') {
	global $HTTP_GET_VARS, $HTTP_POST_VARS;

	$field = '<input type="hidden" name="' . tep_output_string($name) . '"';

	if (tep_not_null($value)) {
		$field .= ' value="' . tep_output_string($value) . '"';
	} elseif ( (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name])) || (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name])) ) {
		if ( (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name])) ) {
			$field .= ' value="' . tep_output_string(stripslashes($HTTP_GET_VARS[$name])) . '"';
		} elseif ( (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name])) ) {
			$field .= ' value="' . tep_output_string(stripslashes($HTTP_POST_VARS[$name])) . '"';
		}
	}

	if (tep_not_null($parameters)) $field .= ' ' . $parameters;

	$field .= '>';

	return $field;
}

////
// Hide form elements
function tep_hide_session_id() {
	global $session_started, $SID;

	if (($session_started == true) && tep_not_null($SID)) {
		return tep_draw_hidden_field(tep_session_name(), tep_session_id());
	}
}

////
// Output a form pull down menu
function tep_draw_pull_down_menu($name, $values, $default = '', $parameters = '', $required = false) {
	global $HTTP_GET_VARS, $HTTP_POST_VARS;

	$field = '<select name="' . tep_output_string($name) . '"';

	if (tep_not_null($parameters)) $field .= ' ' . $parameters;

	$field .= '>';

	if (empty($default) && ( (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name])) || (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name])) ) ) {
		if (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name])) {
			$default = stripslashes($HTTP_GET_VARS[$name]);
		} elseif (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name])) {
			$default = stripslashes($HTTP_POST_VARS[$name]);
		}
	}

	for ($i=0, $n=sizeof($values); $i<$n; $i++) {
		$field .= '<option value="' . tep_output_string($values[$i]['id']) . '"';
		if ($default == $values[$i]['id']) {
			$field .= ' SELECTED';
		}

		$field .= '>' . tep_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
	}
	$field .= '</select>';

	if ($required == true) $field .= TEXT_FIELD_REQUIRED;

	return $field;
}

////
// Creates a pull-down list of countries
function tep_get_country_list($name, $selected = '', $parameters = '') {
	$countries_array = array(array('id' => '', 'text' => PULL_DOWN_DEFAULT));
	$countries = tep_get_countries();

	for ($i=0, $n=sizeof($countries); $i<$n; $i++) {
		$countries_array[] = array('id' => $countries[$i]['countries_id'], 'text' => $countries[$i]['countries_name']);
	}

	return tep_draw_pull_down_menu($name, $countries_array, $selected, $parameters);
}
function forceDownload($file,$local='') {
	 
	if(!fopen($file,"r",$local))
	{
		//  $errors= error_get_last();
		//   echo "Errore nella copia del file: ".$errors['type'];
		//  echo "\n".$errors['message'];
		return false;
	} else {
			
		// echo "File copiato con successo!";
		return true;
	}
	 
	 
	/**
	 * Function forceDownload:
	 *	download any type of file if it exists and is readable
	 * -------------------------------------
	 * @author		Andrea Giammarchi
	 * @date		18/01/2005 [17/05/2006]
	 * @compatibility	PHP >= 4.3.0
	 */
	/*if(file_exists($file) && is_readable($file)) {
		$filename = basename($file);
		if(strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), 'MSIE') !== false && strpos($filename, '.') !== false) {
		$parsename = explode('.', $filename);
		$last = count($parsename) - 1;
		$filename = implode('%2E', array_slice($parsename, 0, $last));
		$filename .= '.'.$parsename[$last];
		}
		if ($local <> '')
		$filname = $local;
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		header('Content-Length:'.filesize($file));
		header('Content-Transfer-Encoding: binary');
		if(@$file = fopen($file, "rb")) {
		while(!feof($file))
		echo fread($file, 8192);
		fclose($file);
		}
		// exit(0);
		}
		*/
}



function copyFile($url,$dirname, $local=''){
	@$file = fopen ($url, "rb");
	// print $url;
	// exit;
	if (!$file) {
		echo"<font color=red>Failed to copy $url!</font><br>";
		return false;
	}else {
		$filename = basename($url);
		if ($local <> '') $filename = $local;

		$fc = fopen($dirname."$filename", "wb");
		while (!feof ($file)) {
			$line = fread ($file, 1028);
			fwrite($fc,$line);
		}
		fclose($fc);
		echo "<font color=blue>File $url saved to PC!</font><br>";
		return true;
	}
}



class RemoteFileReader{
	var $url = "";
	var $content = "";
	function __construct($url) {
		$this->url = $url;
		//Testo l'esistenza delle cURL lib
		if (function_exists('curl_init')) {
			//Inizializzo una nuova Risorsa
			$ch = curl_init();
			//Imposto l'URL da agganciare
			curl_setopt($ch, CURLOPT_URL, $url);
			//Siccome non mi interessa alcun Header
			//ma solo il contenuto del file remoto
			//imposto a zero la richiesta di Header
			curl_setopt($ch, CURLOPT_HEADER, 0);
			
			// referer scamuffo
			curl_setopt($ch, CURLOPT_REFERER, 'http://www.bestdigit.it/');
			
			//Siccome la risposta non la voglio visualizzare
			//sul browser ma la voglio conservare imposto 1
			//alla proprieta' CURLOPT_RETURNTRANSFER
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			//Imposto uno user agent per simulare un browser
			curl_setopt($ch, CURLOPT_USERAGENT,
				'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5)'
			. ' Gecko/20041107 Firefox/1.0');
			//Setto la proprieta' content della classe con
			//il contenuto della risorsa remota
			$this->content = curl_exec($ch);
			//Chiudo la connessione e rilascio la risorsa
			curl_close($ch);
		}
		else {
			//Le librerie non sono installate: restituisco FALSE
			$this->content = FALSE;
		}
	}
	//Metodi Getters/Setters
	 function getContent(){
		return $this->content;
	}
	 function setContent($c){
		$this->content = $c;
	}
	 function getUrl(){
		return $this->url;
	}
	 function setUrl($c){
		$this->url = $c;
	}
}

?>
