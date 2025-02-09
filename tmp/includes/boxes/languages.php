<?php
/*
  $Id: languages.php,v 1.15 2003/06/09 22:10:48 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
	$skin=new pws_skin('boxes/'.basename(__FILE__,'.php').'.htm');
	$skin->set('box_heading',BOX_HEADING_LANGUAGES);
	if (!isset($lng) || (isset($lng) && !is_object($lng))) {
		include(DIR_WS_CLASSES . 'language.php');
		$lng = new language;
	}
	$languages=array();
	$languages_string = '';
	reset($lng->catalog_languages);
	while (list($key, $value) = each($lng->catalog_languages)) {
		$languages[]=array(
			'link'=>tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('language', 'currency')) . 'language=' . $key, $request_type)
			,'image'=>tep_image(DIR_WS_LANGUAGES .  $value['directory'] . '/images/' . $value['image'], $value['name'])
		);
	}
	$skin->set('languages',$languages);
	reset($currencies->currencies);
	$currencies_array = array();
	while (list($key, $value) = each($currencies->currencies)) {
		$currencies_array[] = array(
			'value' => $key
			,'content' => $value['title']
			,'selected' => $key==$currency
		);
	}
	$skin->set('currencies',array('options'=>$currencies_array,'name'=>'selectcurrencies'));
	$skin->set('form',array(
			'name'=>'currencies'
			,'method'=>'get'
			,'action'=>tep_href_link(basename($PHP_SELF), '', $request_type, false)
		)
	);
	reset($HTTP_GET_VARS);
	$params=array();
	while (list($key, $value) = each($HTTP_GET_VARS)) {
		if ( ($key != 'currency') && ($key != 'x') && ($key != 'y') ) {
			$params[]=array('name'=>$key,'value'=>$value);
		}
	}
	$skin->set('hiddenparams',$params);
	echo $skin->execute();
?>