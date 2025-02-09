<?php
/*
 $Id: easypopulate.php,v 2.76g-PWS 2007/01/20 22:50:52 surfalot Exp $

 Designed for osCommerce, Open Source E-Commerce Solutions
 http://www.oscommerce.com

 Modified by Riccardo Roscilli @ PWS 
 Based on easypopulate by  Todd Holforty mtholforty(at)surfalot(dot)com
 Copyright (c) 2008 Riccardo Roscilli <info@oscommerce.it> @ Power Web Studio

 Released under the GNU General Public License
 */
ini_set('max_execution_time',36000);

require('includes/application_top.php');
require_once('easypopulate_functions.php');
require_once('easypopulate_configuration.php');
if (!function_exists('fputcsv'))	{
	require_once DIR_FS_CATALOG.'fputcsv_func.php';
}


//*******************************
//*******************************
// S T A R T
// INITIALIZATION
//*******************************
//*******************************
ep_init();




//*******************************
//*******************************
// DOWNLOAD FILE (EXPORT)
//*******************************
//*******************************
if ( !empty($_GET['download']) && ($_GET['download'] == 'stream' or $_GET['download'] == 'activestream' or $_GET['download'] == 'tempfile') ){
	ep_download_file();
}



//*******************************
//*******************************
// S T A R T
// PAGE DELIVERY
//*******************************
//*******************************
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script type="text/javascript"><!--
function switchForm( field ) {
	var d = document;
	var frm = field.form;
	var tbl = d.getElementById('customtable');

	if(d.getElementById)
	{
		if(field.options[field.selectedIndex].text == 'Complete' || field.options[field.selectedIndex].text == 'Froogle')
		{
			tbl.style.backgroundColor='lightgrey';
			for ( var index = 0; index < frm.elements.length; index++ )
			{
				var oElement = frm.elements[ index ];
				if ( oElement.type == "checkbox" )
				{
					if ( oElement.checked )
					{
						oElement.checked = false;
					}
					oElement.disabled = true;
				}
			}
		}
		else if(field.options[field.selectedIndex].text == 'Price/Qty' || field.options[field.selectedIndex].text == 'Categories' || field.options[field.selectedIndex].text == 'Attributes' )
		{
			tbl.style.backgroundColor='lightgrey';
			for ( var index = 0; index < frm.elements.length; index++ )
			{
				var oElement = frm.elements[ index ];
				if ( oElement.type == "checkbox" )
				{
					if ( oElement.checked )
					{
						oElement.checked = false;
					}
					if ( (oElement.name == 'epcust_price' || oElement.name == 'epcust_quantity') && field.options[field.selectedIndex].text == 'Price/Qty' )
					{
						oElement.disabled = false;
						oElement.checked = true;
					}
					else if ( oElement.name == 'epcust_category' && field.options[field.selectedIndex].text == 'Categories' )
					{
						oElement.disabled = false;
						oElement.checked = true;
					}
					else if ( oElement.name == 'epcust_attributes' && field.options[field.selectedIndex].text == 'Attributes' )
					{
						oElement.disabled = false;
						oElement.checked = true;
					}
					else
					{
						oElement.disabled = true;
					}
				}
			}
		}
		else
		{
			tbl.style.backgroundColor='white';
			for ( var index = 0; index < frm.elements.length; index++ )
			{
				var oElement = frm.elements[ index ];
				if ( oElement.type == "checkbox" )
				{
					oElement.disabled = false;
				}
			}
		}
	}
}
//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<table border="0" width="100%" cellspacing="2" cellpadding="2">
<tr>
<td width="<?php echo BOX_WIDTH; ?>" valign="top" height="27">
<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<?php require(DIR_WS_INCLUDES . 'column_left.php');?>
</table></td>
<td class="pageHeading" valign="top"><?php
echo sprintf(HEADING_TITLE_EP,EP_CURRENT_VERSION,EP_DEFAULT_LANGUAGE_NAME, EP_DEFAULT_LANGUAGE_ID) ;
?>

<p class="smallText"><?php


//*******************************
//*******************************
// UPLOAD AND INSERT FILE
//*******************************
//*******************************
	if ((isset($_FILES['usrfl']) && isset($_GET['split']) && $_GET['split']==1) ||
		(!empty($_POST['localfile']) or (isset($_FILES['usrfl']) && isset($_GET['split']) && $_GET['split']==0))){
		ep_upload_file();
	}

//*******************************
//*******************************
// MAIN MENU
//*******************************
//*******************************
?>
</p>

<table width="<?php if (EP_SHOW_EP_SETTINGS == true) { echo '95'; } else { echo '75'; } ?>%" cellpadding="5" cellspacing="0" style="border-collapse:collapse;">
<tr>
<td width="75%" style="border-style:solid; border-width:thin; border-color:#CCCCCC;">
<span style="font-size:10px;background-color:#FFFFCC; width:100%;">&nbsp; &nbsp;
<a href="backup.php<?php if (defined('SID') && tep_not_null(SID)) { echo '?'.tep_session_name().'='.tep_session_id(); } ?>" style="font-size:10px;background-color:#FFFFCC;text-decoration:underline;"><?=TEXT_BACKUP_DATABASE_EP?></a>
<?=TEXT_WARNING_DATABASE_OPERATIONS_EP?>&nbsp; &nbsp;
</span><br />
<form enctype="multipart/form-data" action="easypopulate.php?split=0<?php if (defined('SID') && tep_not_null(SID)) { echo '&'.tep_session_name().'='.tep_session_id(); } ?>" method="post">
<?php if (defined('SID') && tep_not_null(SID)) { echo tep_draw_hidden_field(tep_session_name(), tep_session_id()); } ?>
<p style="margin-top: 18px; margin-bottom: 8px;"><b><?=TEXT_HEADING_UPLOAD_AND_IMPORT_EP?></b></p>
<p style="margin-top: 0px;">
<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
<input name="usrfl" type="file" size="50">
<select name="input_mode">
<option value="normal"><?=TEXT_OPTION_UPLOAD_NORMAL_EP?></option>
<option value="addnew"><?=TEXT_OPTION_UPLOAD_NEW_ONLY_EP?></option>
<option value="update"><?=TEXT_OPTION_UPLOAD_UPDATE_ONLY_EP?></option>
</select>
<input type="submit" name="buttoninsert" value="<?=TEXT_BUTTON_INSERT_INTO_DB_EP?>">
<br />
</p>
</form>

<form enctype="multipart/form-data" action="easypopulate.php?split=1<?php if (defined('SID') && tep_not_null(SID)) { echo '&'.tep_session_name().'='.tep_session_id(); } ?>" method="post">
<?php if (defined('SID') && tep_not_null(SID)) { echo tep_draw_hidden_field(tep_session_name(), tep_session_id()); } ?>
<p style="margin-top: 18px; margin-bottom: 8px;"><b><?=TEXT_HEADING_UPLOAD_AND_SPLIT_EP?></b></p>
<p style="margin-top: 0px;">
<input type="hidden" name="MAX_FILE_SIZE" value="1000000000">
<input name="usrfl" type="file" size="50">
<input type="submit" name="buttonsplit" value="Split file">
<br />
</p>
</form>

<form enctype="multipart/form-data" action="easypopulate.php<?php if (defined('SID') && tep_not_null(SID)) { echo '?'.tep_session_name().'='.tep_session_id(); } ?>" method="post">
<?php if (defined('SID') && tep_not_null(SID)) { echo tep_draw_hidden_field(tep_session_name(), tep_session_id()); } ?>
<p style="margin-top: 18px; margin-bottom: 8px;"><b><?=TEXT_HEADING_IMPORT_EP_FROM_TEMP?></b></p>
<p style="margin-top: 0px;">
<?PHP
//<input type="text" name="localfile" size="50">
$the_array = Array();
if (is_readable(EP_TEMP_DIRECTORY)) {
	$handle = opendir(EP_TEMP_DIRECTORY);
	while (false!== ($file = readdir($handle))) {
		if ($file!= "." && $file!= ".." &&!is_dir($file)) {
			$namearr = split('\.',$file);
			if ($namearr[count($namearr)-1] == ((EP_EXCEL_SAFE_OUTPUT==true)?'csv':'txt')) $the_array[] = $file;
		}
	}
	closedir($handle);
}

$array_size = count($the_array);
if($array_size == 0){
	echo ('            <input type="text" name="localfile" size="50">' . "\n");
} else {
	echo ('            <select name="localfile" size="1">' . "\n");
	foreach ($the_array as $list){
		echo ('                <option value="' . $list . '">' . $list . '</option>' . "\n");
	}
	echo ('            </select>' . "\n");
}
?>
<select name="input_mode">
<option value="normal"><?=TEXT_OPTION_UPLOAD_NORMAL_EP?></option>
<option value="addnew"><?=TEXT_OPTION_UPLOAD_NEW_ONLY_EP?></option>
<option value="update"><?=TEXT_OPTION_UPLOAD_UPDATE_ONLY_EP?></option>
</select>
<input type="submit" name="buttoninsert" value="<?=TEXT_BUTTON_INSERT_INTO_DB_EP?>">
<br />
</p>
</form>

<p style="margin-top: 24px; margin-bottom: 8px;"><b><?=TEXT_HEADING_EXPORT_EP_OR_FROOGLE?></b></p>
<p style="margin-top: 0px;"><!-- Download file links -  Add your custom fields here -->
<table border="0" cellpadding="0" cellspacing="0" style="border: 1px solid #666666; padding: 3px;">
<?php echo tep_draw_form('custom', 'easypopulate.php', 'id="custom"' . ((defined('SID') && tep_not_null(SID)) ? '&'.tep_session_name().'='.tep_session_id() : ''), 'get'); ?>
<?php if (defined('SID') && tep_not_null(SID)) { echo tep_draw_hidden_field(tep_session_name(), tep_session_id()); } ?>
<tr><td class="smallText"><?php

echo tep_draw_pull_down_menu('download',
array( 	0 => array(
						"id" => 'activestream',
          				'text' => TEXT_DOWNLOAD_TYPE_ON_THE_FLY_EP )
, 1 => array(
						"id" => 'stream',
						'text' => TEXT_DOWNLOAD_TYPE_SAVE_AND_DOWNLOAD_EP )
, 2 => array(
          				"id" => 'tempfile',
						'text' => TEXT_DOWNLOAD_TYPE_SAVE_IN_TEMP_EP )
)
);
echo '&nbsp;'.TEXT_A_EP.'&nbsp;' . tep_draw_pull_down_menu('dltype',
array( 	0 => array(
						"id" => 'full',
          				'text' => TEXT_FILE_TYPE_COMPLETE_EP )
, 1 => array(
						"id" => 'custom', 
						'text' => TEXT_FILE_TYPE_CUSTOM_EP )
, 2 => array(
						"id" => 'priceqty', 
						'text' => TEXT_FILE_TYPE_PRICE_QTY_EP )
, 3 => array(
						"id" => 'category', 
						'text' => TEXT_FILE_TYPE_CATEGORIES_EP )
, 4 => array(
						"id" => 'attrib', 
						'text' => TEXT_FILE_TYPE_ATTRIBUTES_EP )
, 5 => array(
						"id" => 'froogle', 
						'text' => TEXT_FILE_TYPE_FROOGLE_EP )
),'custom','onChange="return switchForm(this);"');
echo '&nbsp;' . sprintf(TEXT_FILE_FORMAT_EP,((EP_EXCEL_SAFE_OUTPUT == true)?".csv":".txt"));
$cells = array();
$cells[0][] = array('text' => '<table border="0" cellpadding="0" cellspacing="0"><tr><td class="smallText">' . tep_draw_checkbox_field('epcust_name', 'show', (!empty($_GET['epcust_name'])?true:false)) . '</td><td class="smallText"> '.TEXT_FIELDNAME_NAME . '</td></tr></table>');
$cells[0][] = array('text' => '<table border="0" cellpadding="0" cellspacing="0"><tr><td class="smallText">' . tep_draw_checkbox_field('epcust_description', 'show', (!empty($_GET['epcust_description'])?true:false)) . '</td><td class="smallText"> '.TEXT_FIELDNAME_DESCRIPTION . '</td></tr></table>');
$cells[0][] = array('text' => '<table border="0" cellpadding="0" cellspacing="0"><tr><td class="smallText">' . tep_draw_checkbox_field('epcust_url', 'show', (!empty($_GET['epcust_url'])?true:false)) . '</td><td class="smallText"> '.TEXT_FIELDNAME_URL . '</td></tr></table>');
$cells[0][] = array('text' => '<table border="0" cellpadding="0" cellspacing="0"><tr><td class="smallText">' . tep_draw_checkbox_field('epcust_image', 'show', (!empty($_GET['epcust_image'])?true:false)) . '</td><td class="smallText"> '.TEXT_FIELDNAME_IMAGE . '</td></tr></table>');
if (EP_PRODUCTS_WITH_ATTRIBUTES == true) {
	$cells[0][] = array('text' => '<table border="0" cellpadding="0" cellspacing="0"><tr><td class="smallText">' . tep_draw_checkbox_field('epcust_attributes', 'show', (!empty($_GET['epcust_attributes'])?true:false)) . '</td><td class="smallText"> '.TEXT_FIELDNAME_ATTRIBUTES . '</td></tr></table>');
}
$cells[0][] = array('text' => '<table border="0" cellpadding="0" cellspacing="0"><tr><td class="smallText">' . tep_draw_checkbox_field('epcust_category', 'show', (!empty($_GET['epcust_category'])?true:false)) . '</td><td class="smallText"> '.TEXT_FIELDNAME_CATEGORIES . '</td></tr></table>');
$cells[0][] = array('text' => '<table border="0" cellpadding="0" cellspacing="0"><tr><td class="smallText">' . tep_draw_checkbox_field('epcust_manufacturer', 'show', (!empty($_GET['epcust_manufacturer'])?true:false)) . '</td><td class="smallText"> '.TEXT_FIELDNAME_MANUFACTURER . '</td></tr></table>');

$cells[1][] = array('text' => '<table border="0" cellpadding="0" cellspacing="0"><tr><td class="smallText">' . tep_draw_checkbox_field('epcust_price', 'show', (!empty($_GET['epcust_price'])?true:false)) . '</td><td class="smallText"> '.TEXT_FIELDNAME_PRICE . '</td></tr></table>');
$cells[1][] = array('text' => '<table border="0" cellpadding="0" cellspacing="0"><tr><td class="smallText">' . tep_draw_checkbox_field('epcust_quantity', 'show', (!empty($_GET['epcust_quantity'])?true:false)) . '</td><td class="smallText"> '.TEXT_FIELDNAME_QUANTITY . '</td></tr></table>');
$cells[1][] = array('text' => '<table border="0" cellpadding="0" cellspacing="0"><tr><td class="smallText">' . tep_draw_checkbox_field('epcust_weight', 'show', (!empty($_GET['epcust_weight'])?true:false)) . '</td><td class="smallText"> '.TEXT_FIELDNAME_WEIGHT . '</td></tr></table>');
$cells[1][] = array('text' => '<table border="0" cellpadding="0" cellspacing="0"><tr><td class="smallText">' . tep_draw_checkbox_field('epcust_tax_class', 'show', (!empty($_GET['epcust_tax_class'])?true:false)) . '</td><td class="smallText"> '.TEXT_FIELDNAME_TAX_CLASS . '</td></tr></table>');
$cells[1][] = array('text' => '<table border="0" cellpadding="0" cellspacing="0"><tr><td class="smallText">' . tep_draw_checkbox_field('epcust_avail', 'show', (!empty($_GET['epcust_avail'])?true:false)) . '</td><td class="smallText"> '.TEXT_FIELDNAME_AVAILABLE . '</td></tr></table>');
$cells[1][] = array('text' => '<table border="0" cellpadding="0" cellspacing="0"><tr><td class="smallText">' . tep_draw_checkbox_field('epcust_date_added', 'show', (!empty($_GET['epcust_date_added'])?true:false)) . '</td><td class="smallText"> '.TEXT_FIELDNAME_DATE_ADDED . '</td></tr></table>');
$cells[1][] = array('text' => '<table border="0" cellpadding="0" cellspacing="0"><tr><td class="smallText">' . tep_draw_checkbox_field('epcust_status', 'show', (!empty($_GET['epcust_status'])?true:false)) . '</td><td class="smallText"> '.TEXT_FIELDNAME_STATUS . '</td></tr></table>');

$tmp_row_count = 2;
$tmp_col_count = 0;
foreach ($custom_fields[TABLE_PRODUCTS] as $key => $name) {
	$cells[$tmp_row_count][] = array('text' => '<table border="0" cellpadding="0" cellspacing="0"><tr><td class="smallText">' . tep_draw_checkbox_field('epcust_' . $key, 'show', (!empty($_GET['epcust_' . $key])?true:false)) . '</td><td class="smallText"> ' . $name . '</td></tr></table>');
	$tmp_col_count += 1;
	if ($tmp_col_count >= 7) {
		$tmp_row_count += 1;
		$tmp_col_count = 0;
	}
}

foreach ($custom_fields[TABLE_PRODUCTS_DESCRIPTION] as $key => $name) {
	$cells[$tmp_row_count][] = array('text' => '<table border="0" cellpadding="0" cellspacing="0"><tr><td class="smallText">' . tep_draw_checkbox_field('epcust_' . $key, 'show', (!empty($_GET['epcust_' . $key])?true:false)) . '</td><td class="smallText"> ' . $name . '</td></tr></table>');
	$tmp_col_count += 1;
	if ($tmp_col_count >= 7) {
		$tmp_row_count += 1;
		$tmp_col_count = 0;
	}
}

$bigbox = new epbox('',false);
$bigbox->table_parameters = 'id="customtable" style="border: 1px solid #CCCCCC; padding: 2px; margin: 3px;"';
echo $bigbox->output($cells);

$manufacturers_array = array();
$manufacturers_array[] = array( "id" => '', 'text' => TEXT_OPTION_MANUFACTURER );
$manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
	$manufacturers_array[] = array( "id" => $manufacturers['manufacturers_id'], 'text' => $manufacturers['manufacturers_name'] );
}

$status_array = array(array( "id" => '', 'text' => TEXT_OPTION_STATUS ),array( "id" => '1', 'text' => TEXT_STATUS_ACTIVE ),array( "id" => '0', 'text' => TEXT_STATUS_DISABLED ));

echo LABEL_FILTER_BY_EP . tep_draw_pull_down_menu('epcust_category_filter', array_merge(array( 0 => array( "id" => '', 'text' => TEXT_OPTION_CATEGORY )), tep_get_category_tree()));
echo ' ' . tep_draw_pull_down_menu('epcust_manufacturer_filter', $manufacturers_array) . ' ';
echo ' ' . tep_draw_pull_down_menu('epcust_status_filter', $status_array) . ' ';

echo tep_draw_input_field('submit', TEXT_BUTTON_BUILD_FILE, ' style="padding: 0px"', false, 'submit');
?></td></tr>
</form>
</table>
</p><br /><br />

<font size="-2"><?=TEXT_HEADING_QUICK_LINKS?></font>
<table width="100%" border="0" cellpadding="3" cellspacing="3"><tr><td width="50%" valign="top" bgcolor="#EEEEEE">
<p style="margin-top: 8px;"><b><?=TEXT_HEADING_CREATE_THEN_DOWNLOAD?></b><br />
<font size="-2"><?=TEXT_DESCRIPTION_CREATE_THEN_DOWNLOAD?></font></p>
<p><!-- Download file links -  Add your custom fields here -->
<a href="easypopulate.php?download=stream&dltype=full<?php if (defined('SID') && tep_not_null(SID)) { echo '&'.tep_session_name().'='.tep_session_id(); } ?>"><?=sprintf(TEXT_DOWNLOAD_COMPLETE_FILE_EP,((EP_SPPC_SUPPORT == true) ? ' '.TEXT_DOWNLOAD_FILE_WITH_SPPC:''),((EP_EXCEL_SAFE_OUTPUT == true)?".csv":".txt"))?></a><br />
<?php if (EP_EXTRA_FIELDS_SUPPORT == true) { ?>
<a href="easypopulate.php?download=stream&dltype=extra_fields<?php if (defined('SID') && tep_not_null(SID)) { echo '&'.tep_session_name().'='.tep_session_id(); } ?>"><?=sprintf(TEXT_DOWNLOAD_EXTRA_FIELDS_EP,((EP_EXCEL_SAFE_OUTPUT == true)?".csv":".txt"))?></a><br />
<?php } ?>
<a href="easypopulate.php?download=stream&dltype=priceqty<?php if (defined('SID') && tep_not_null(SID)) { echo '&'.tep_session_name().'='.tep_session_id(); } ?>"><?=sprintf(TEXT_DOWNLOAD_MODEL_PRICE_QTY_EP,((EP_SPPC_SUPPORT == true) ? ' '.TEXT_DOWNLOAD_FILE_WITH_SPPC:''),((EP_EXCEL_SAFE_OUTPUT == true)?".csv":".txt"))?></a><br />
<a href="easypopulate.php?download=stream&dltype=category<?php if (defined('SID') && tep_not_null(SID)) { echo '&'.tep_session_name().'='.tep_session_id(); } ?>"><?=sprintf(TEXT_DOWNLOAD_MODEL_CATEGORIES_EP,((EP_EXCEL_SAFE_OUTPUT == true)?".csv":".txt"))?></a><br />
<a href="easypopulate.php?download=stream&dltype=froogle<?php if (defined('SID') && tep_not_null(SID)) { echo '&'.tep_session_name().'='.tep_session_id(); } ?>"><?=sprintf(TEXT_DOWNLOAD_FROOGLE_EP,((EP_EXCEL_SAFE_OUTPUT == true)?".csv":".txt"))?></a><br />
<!-- VJ product attributes begin //-->
<?php if (EP_PRODUCTS_WITH_ATTRIBUTES == true) { ?>
<a href="easypopulate.php?download=stream&dltype=attrib<?php if (defined('SID') && tep_not_null(SID)) { echo '&'.tep_session_name().'='.tep_session_id(); } ?>"><?=sprintf(TEXT_DOWNLOAD_MODEL_ATTRIBUTES_EP,((EP_EXCEL_SAFE_OUTPUT == true)?".csv":".txt"))?></a><br />
<?php } ?>
<!-- VJ product attributes end //-->
</p><br />
</td><td width="50%" valign="top" bgcolor="#EEEEEE">
<p style="margin-top: 8px;"><b><?=TEXT_HEADING_CREATE_TEMP_FILES_EP?></b><br />
<font size="-2"><?=TEXT_DESCRIPTION_CREATE_TEMP_FILES_EP?></font></p>
<p>
<a href="easypopulate.php?download=tempfile&dltype=full<?php if (defined('SID') && tep_not_null(SID)) { echo '&'.tep_session_name().'='.tep_session_id(); } ?>"><?=sprintf(TEXT_CREATE_COMPLETE_FILE_EP,((EP_EXCEL_SAFE_OUTPUT == true)?".csv":".txt"))?></a><br />
<a href="easypopulate.php?download=tempfile&dltype=priceqty<?php if (defined('SID') && tep_not_null(SID)) { echo '&'.tep_session_name().'='.tep_session_id(); } ?>"><?=sprintf(TEXT_CREATE_MODEL_PRICE_QTY_FILE_EP,((EP_EXCEL_SAFE_OUTPUT == true)?".csv":".txt"))?></a><br />
<a href="easypopulate.php?download=tempfile&dltype=category<?php if (defined('SID') && tep_not_null(SID)) { echo '&'.tep_session_name().'='.tep_session_id(); } ?>"><?=sprintf(TEXT_CREATE_MODEL_CATEGORIES_EP,((EP_EXCEL_SAFE_OUTPUT == true)?".csv":".txt"))?></a><br />
<a href="easypopulate.php?download=tempfile&dltype=froogle<?php if (defined('SID') && tep_not_null(SID)) { echo '&'.tep_session_name().'='.tep_session_id(); } ?>"><?=sprintf(TEXT_CREATE_FROOGLE_FILE_EP,((EP_EXCEL_SAFE_OUTPUT == true)?".csv":".txt"))?></a><br />
<!-- VJ product attributes begin //-->
<?php if (EP_PRODUCTS_WITH_ATTRIBUTES == true) { ?>
<a href="easypopulate.php?download=tempfile&dltype=attrib<?php if (defined('SID') && tep_not_null(SID)) { echo '&'.tep_session_name().'='.tep_session_id(); } ?>"><?=sprintf(TEXT_CREATE_MODEL_ATTRIBUTES_EP,((EP_EXCEL_SAFE_OUTPUT == true)?".csv":".txt"))?></a><br />
<?php } ?>
<!-- VJ product attributes end //-->
</p><br />
</td></tr></table>

</td>

<?php if (EP_SHOW_EP_SETTINGS == true) { ?>
<td width="25%" valign="top" style="border-style:solid; border-width:thin; border-color:#CCCCCC;">
<p style="margin-top: 8px;"><b><?=TEXT_HEADING_SETTINGS_INFO?></b></p>
<table border="0" cellpadding="0" cellspacing="0"><tr><td class="smallText">

<p>EP vers: <?php echo EP_CURRENT_VERSION; ?><br />
<?php if (defined('PROJECT_VERSION')) { echo PROJECT_VERSION . '<br>'; } ?>
<?php echo 'OS: ' . $system['system'] . ' ' . $system['kernel'] . '<br>'; ?>
<?php echo 'HTTP: ' . $system['http_server'] . '<br>'; ?>
<?php echo 'DB: ' . $system['db_version'] . '<br>'; ?>
<?php echo 'PHP: ' . $system['php'] . ' (Zend: ' . $system['zend'] . ')' . '<br>'; ?>
<br /><?=sprintf(TEXT_SETTINGS_TEMP_DIRECTORY,EP_TEMP_DIRECTORY)?><br />
<?php if (is_writable(EP_TEMP_DIRECTORY)) { ?><font color="#009900"><?=TEXT_SETTINGS_TEMP_DIR_WRITEABLE?></font><?php } else { ?><font color="#FF0000"><?=TEXT_SETTINGS_TEMP_DIR_UNWRITEABLE?></font><?php } ?><br />
Magic Quotes : <?php if (ini_get('magic_quotes_runtime') == 1){ echo TEXT_OPTION_ON; } else { echo TEXT_OPTION_OFF; } ?><br />
register_globals : <?php if (ini_get(register_globals)) { echo TEXT_OPTION_ON; } else { echo TEXT_OPTION_OFF; } ?><br />
<?= sprintf(TEXT_SETTINGS_SPLIT_FILES,EP_SPLIT_MAX_RECORDS) ?><br />
<?= sprintf(TEXT_SETTINGS_MODEL_FIELDSIZE,EP_MODEL_NUMBER_SIZE) ?><br />
<?= sprintf(TEXT_SETTINGS_TAX_INCLUDED,(EP_PRICE_WITH_TAX?TEXT_OPTION_TRUE:TEXT_OPTION_FALSE)) ?><br />
<?= sprintf(TEXT_SETTINGS_CALC_PRECISION,EP_PRECISION) ?><br />
<?= sprintf(TEXT_SETTINGS_REPLACE_QUOTES,(EP_REPLACE_QUOTES?TEXT_OPTION_TRUE:TEXT_OPTION_FALSE)) ?><br />
<?php echo TEXT_SETTINGS_FIELD_DELIMITER;
switch ($ep_separator) {
	case "\t";
	echo 'tab';
	break;
	case ",";
	echo 'comma';
	break;
	case ";";
	echo 'semi-colon';
	break;
	case "~";
	echo 'tilde';
	break;
	case "-";
	echo 'dash';
	break;
	case "*";
	echo 'splat';
	break;
}
?><br />
<?= sprintf(TEXT_SETTINGS_EXCEL_SAFE,(EP_EXCEL_SAFE_OUTPUT?TEXT_OPTION_TRUE:TEXT_OPTION_FALSE)) ?><br />
<?= sprintf(TEXT_SETTINGS_PRESERVE_WHITESPACE,(EP_PRESERVE_TABS_CR_LF?TEXT_OPTION_TRUE:TEXT_OPTION_FALSE)) ?><br />
<?= sprintf(TEXT_SETTINGS_CATEGORY_DEPTH,EP_MAX_CATEGORIES) ?><br />
<?= sprintf(TEXT_SETTINGS_ENABLE_ATTRIBUTES,(EP_PRODUCTS_WITH_ATTRIBUTES?TEXT_OPTION_TRUE:TEXT_OPTION_FALSE)) ?><br />
<?= sprintf(TEXT_SETTINGS_SEF_URLS,(EP_FROOGLE_SEF_URLS?TEXT_OPTION_TRUE:TEXT_OPTION_FALSE)) ?><br />
<?= sprintf(TEXT_SETTINGS_MORE_PICS_6_SUPPORT,(EP_MORE_PICS_6_SUPPORT?TEXT_OPTION_TRUE:TEXT_OPTION_FALSE)) ?><br />
<?= sprintf(TEXT_SETTINGS_UNKNOWN_ADD_IMAGES_SUPPORT,(EP_UNKNOWN_ADD_IMAGES_SUPPORT?TEXT_OPTION_TRUE:TEXT_OPTION_FALSE)) ?><br />
<?= sprintf(TEXT_SETTINGS_HTC_SUPPORT,(EP_HTC_SUPPORT?TEXT_OPTION_TRUE:TEXT_OPTION_FALSE)) ?><br />
<?= sprintf(TEXT_SETTINGS_SPPC_SUPPORT,(EP_SPPC_SUPPORT?TEXT_OPTION_TRUE:TEXT_OPTION_FALSE)) ?><br />
<?= sprintf(TEXT_SETTINGS_EXTRA_FIELDS_SUPPORT,(EP_EXTRA_FIELDS_SUPPORT?TEXT_OPTION_TRUE:TEXT_OPTION_FALSE)) ?><br />
<br />
<div style="padding: 10px; background-color: #ffffCC"><?=sprintf(TEXT_SETTINGS_HOW_TO_CHANGE,tep_href_link(FILENAME_CONFIGURATION,'gID=17'))?></div>
</td></tr></table>


</td>
<?php } ?>
</tr>
</table>
</td>
</tr>
</table>

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

<p></p>
<p></p><p><br />
</p></body>
</html>

<?php
//*******************************
//*******************************
// end: MAIN MENU
//*******************************
//*******************************






///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
//
// ep_init()
//
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
function ep_init(){
	global $system,$language,$languages,$languages_id,$epdlanguage
		,$attribute_options_array,$attribute_options_select
		,$default_these,$filelayout,$fileheaders,$filelayout_count,$filelayout_sql
		,$custom_fields,$ep_separator;
	$system = tep_get_system_information();
	
	if (!empty($languages_id) && !empty($language)) {
		define ('EP_DEFAULT_LANGUAGE_ID', $languages_id);
		define ('EP_DEFAULT_LANGUAGE_NAME', $language);
	} else {
		//elari check default language_id from configuration table DEFAULT_LANGUAGE
		$epdlanguage_query = tep_db_query("select languages_id, name from " . TABLE_LANGUAGES . " where code = '" . DEFAULT_LANGUAGE . "'");
		if (tep_db_num_rows($epdlanguage_query) > 0) {
			$epdlanguage = tep_db_fetch_array($epdlanguage_query);
			define ('EP_DEFAULT_LANGUAGE_ID', $epdlanguage['languages_id']);
			define ('EP_DEFAULT_LANGUAGE_NAME', $epdlanguage['name']);
		} else {
			echo 'Strange but there is no default language to work... That may not happen, just in case... ';
		}
	}
	
	$languages = tep_get_languages();
	
	// VJ product attributes begin
	$attribute_options_array = array();
	
	if (EP_PRODUCTS_WITH_ATTRIBUTES == true) {
		if (is_array($attribute_options_select) && (count($attribute_options_select) > 0)) {
			foreach ($attribute_options_select as $value) {
				$attribute_options_query = "select distinct products_options_id from " . TABLE_PRODUCTS_OPTIONS . " where products_options_name = '" . $value . "'";
	
				$attribute_options_values = tep_db_query($attribute_options_query);
	
				if ($attribute_options = tep_db_fetch_array($attribute_options_values)){
					$attribute_options_array[] = array('products_options_id' => $attribute_options['products_options_id']);
				}
			}
		} else {
			$attribute_options_query = "select distinct products_options_id from " . TABLE_PRODUCTS_OPTIONS . " order by products_options_id";
	
			$attribute_options_values = tep_db_query($attribute_options_query);
	
			while ($attribute_options = tep_db_fetch_array($attribute_options_values)){
				$attribute_options_array[] = array('products_options_id' => $attribute_options['products_options_id']);
			}
		}
	}
	// VJ product attributes end
	
	
	// these are the fields that will be defaulted to the current values in
	// the database if they are not found in the incoming file
	$default_these = array();
	foreach ($languages as $key => $lang){
		$default_these[] = 'v_products_name_' . $lang['id'];
		$default_these[] = 'v_products_description_' . $lang['id'];
		$default_these[] = 'v_products_url_' . $lang['id'];
		foreach ($custom_fields[TABLE_PRODUCTS_DESCRIPTION] as $key => $name) {
			$default_these[] = 'v_' . $key . '_' . $lang['id'];
		}
		if (EP_HTC_SUPPORT == true) {
			$default_these[] = 'v_products_head_title_tag_' . $lang['id'];
			$default_these[] = 'v_products_head_desc_tag_' . $lang['id'];
			$default_these[] = 'v_products_head_keywords_tag_' . $lang['id'];
		}
	}
	$default_these[] = 'v_products_image';
	foreach ($custom_fields[TABLE_PRODUCTS] as $key => $name) {
		$default_these[] = 'v_' . $key;
	}
	if (EP_MORE_PICS_6_SUPPORT == true) {
		$default_these[] = 'v_products_subimage1';
		$default_these[] = 'v_products_subimage2';
		$default_these[] = 'v_products_subimage3';
		$default_these[] = 'v_products_subimage4';
		$default_these[] = 'v_products_subimage5';
		$default_these[] = 'v_products_subimage6';
	}
	if (EP_UNKNOWN_ADD_IMAGES_SUPPORT == true) {
		$default_these[] = 'v_products_mimage';
		$default_these[] = 'v_products_bimage';
		$default_these[] = 'v_products_subimage1';
		$default_these[] = 'v_products_bsubimage1';
		$default_these[] = 'v_products_subimage2';
		$default_these[] = 'v_products_bsubimage2';
		$default_these[] = 'v_products_subimage3';
		$default_these[] = 'v_products_bsubimage3';
	}
	$default_these[] = 'v_categories_id';
	$default_these[] = 'v_products_price';
	$default_these[] = 'v_products_quantity';
	$default_these[] = 'v_products_weight';
	$default_these[] = 'v_status_current';
	$default_these[] = 'v_date_avail';
	$default_these[] = 'v_date_added';
	$default_these[] = 'v_tax_class_title';
	$default_these[] = 'v_manufacturers_name';
	$default_these[] = 'v_manufacturers_id';
	
	$filelayout = '';
	$filelayout_count = '';
	$filelayout_sql = '';
	$fileheaders = '';
	
}
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
//
// ep_upload_file()
//
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
function ep_upload_file(){
	global $pws_engine;
	global $_POST,$_GET;
	global $system,$language,$languages,$languages_id,$epdlanguage
		,$attribute_options_array,$attribute_options_select
		,$default_these,$filelayout,$fileheaders,$filelayout_count,$filelayout_sql
		,$custom_fields,$ep_separator;
	if (!empty($_POST['localfile']) or (isset($_FILES['usrfl']) && isset($_GET['split']) && $_GET['split']==0)) {
		if (isset($_FILES['usrfl'])){
			// move the file to where we can work with it
			$file = tep_get_uploaded_file('usrfl');
			if (is_uploaded_file($file['tmp_name'])) {
				tep_copy_uploaded_file($file, EP_TEMP_DIRECTORY);
			}
	
			echo "<p class=smallText>";
			echo "File uploaded. <br />";
			echo "Temporary filename: " . $file['tmp_name'] . "<br />";
			echo "User filename: " . $file['name'] . "<br />";
			echo "Size: " . $file['size'] . "<br />";
	
			// get the entire file into an array
			//$readed = file(EP_TEMP_DIRECTORY . $file['name']);
			$filename=EP_TEMP_DIRECTORY.$file['name'];
		}
		if (!empty($_POST['localfile'])){
			// move the file to where we can work with it
			//$file = tep_get_uploaded_file('usrfl');
	
			//$attribute_options_query = "select distinct products_options_id from " . TABLE_PRODUCTS_OPTIONS . " order by products_options_id";
			//$attribute_options_values = tep_db_query($attribute_options_query);
			//$attribute_options_count = 1;
			//while ($attribute_options = tep_db_fetch_array($attribute_options_values)){
	
			//if (is_uploaded_file($file['tmp_name'])) {
			//    tep_copy_uploaded_file($file, EP_TEMP_DIRECTORY);
			//}
	
			echo "<p class=smallText>";
			echo "Filename: " . $_POST['localfile'] . "<br />";
	
			// get the entire file into an array
			//$readed = file(EP_TEMP_DIRECTORY . $_POST['localfile']);
			$filename=EP_TEMP_DIRECTORY . $_POST['localfile'];
			
		}
		$fp=fopen( $filename, 'r');
		if (!$fp){
			echo "<p class=smallText><font color=red>".sprintf(EP_ERROR_OPENING_FILE_READ,$filename)."</font></p>";
			die();
		}
		$header_line = fgets($fp);
		if (strpos($header_line,',') !== false) { $ep_separator = ','; }
		if (strpos($header_line,';') !== false) { $ep_separator = ';'; }
		if (strpos($header_line,"\t") !== false) { $ep_separator = "\t"; }
		if (strpos($header_line,'~') !== false) { $ep_separator = '~'; }
		if (strpos($header_line,'-') !== false) { $ep_separator = '-'; }
		if (strpos($header_line,'*') !== false) { $ep_separator = '*'; }
		fclose($fp);
		$fp=fopen( $filename, 'r');
		if (!$fp){
			echo "<p class=smallText><font color=red>".sprintf(EP_ERROR_OPENING_FILE_READ,$filename)."</font></p>";
			die();
		}
		$theheaders_array=fgetcsv($fp,1024000,$ep_separator);
		
		$lll = 0;
		$filelayout = array();
		foreach( $theheaders_array as $header ){
			
			//$cleanheader = str_replace( '"', '', $header);
			// echo "Fileheader was $header<br /><br /><br />";
			//$filelayout[ $cleanheader ] = $lll++; //
			$filelayout[ $header ] = $lll++; //
		}
//		unset($readed[0]); //  we don't want to process the headers with the data
	
		// now we've got the array broken into parts by the expicit end-of-row marker.
		//foreach ($readed as $tkey => $readed_row) {
		while (($readed_row = fgetcsv($fp, 1024000, $ep_separator)) !== FALSE) {
			process_row($readed_row, $filelayout, $filelayout_count, $default_these, $ep_separator, $languages, $custom_fields);
		}
		
		tep_reset_turbo_cache();
		// isn't working in PHP 5
		// array_walk($readed, $filelayout, $filelayout_count, $default_these, 'process_row');
	
	
	
		//*******************************
		//*******************************
		// UPLOAD AND SPLIT FILE
		//*******************************
		//*******************************
	} elseif (isset($_FILES['usrfl']) && isset($_GET['split']) && $_GET['split']==1) {
		// move the file to where we can work with it
		$file = tep_get_uploaded_file('usrfl');
		//echo "Trying to move file...";
		if (is_uploaded_file($file['tmp_name'])) {
			tep_copy_uploaded_file($file, EP_TEMP_DIRECTORY);
		}
	
		$infp = fopen(EP_TEMP_DIRECTORY . $file['name'], "r");
	
		//toprow has the field headers
		$toprow = fgets($infp,32768);
	
		$filecount = 1;
	
		echo TEXT_CREATING_TEMP_FILE_EP . $filecount . '.'.((EP_EXCEL_SAFE_OUTPUT==true)?'csv':'txt').' ...  ';
		$tmpfname = EP_TEMP_DIRECTORY . "EP_Split" . $filecount.'.'.((EP_EXCEL_SAFE_OUTPUT==true)?'csv':'txt');
		$fp = fopen( $tmpfname, "w+");
		fwrite($fp, $toprow);
	
		$linecount = 0;
		$line = fgets($infp,32768);
		while ($line){
			// walking the entire file one row at a time
			// but a line is not necessarily a complete row, we need to split on rows that have "EOREOR" at the end
			$line = str_replace('"EOREOR"', 'EOREOR', $line);
			fwrite($fp, $line);
			if (strpos($line, 'EOREOR')){
				// we found the end of a line of data, store it
				$linecount++; // increment our line counter
				if ($linecount >= EP_SPLIT_MAX_RECORDS){
					echo sprintf(TEXT_ADDED_RECORDS_CLOSING_FILE_EP,$linecount)."<br />";
					$linecount = 0; // reset our line counter
					// close the existing file and open another;
					fclose($fp);
					// increment filecount
					$filecount++;
					echo TEXT_CREATING_TEMP_FILE_EP . $filecount . '.'.((EP_EXCEL_SAFE_OUTPUT==true)?'csv':'txt').' ...  ';
					$tmpfname = EP_TEMP_DIRECTORY . "EP_Split" . $filecount.'.'.((EP_EXCEL_SAFE_OUTPUT==true)?'csv':'txt');
					//Open next file name
					$fp = fopen( $tmpfname, "w+");
					fwrite($fp, $toprow);
				}
			}
			$line=fgets($infp,32768);
		}
		echo sprintf(TEXT_ADDED_RECORDS_CLOSING_FILE_EP,$linecount)."<br /><br /> ";
		fclose($fp);
		fclose($infp);
	
		echo sprintf(TEXT_YOU_CAN_DOWNLOAD_YOUR_SPLIT_FILES_EP, EP_TEMP_DIRECTORY);
	}
}
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
//
// ep_download_file()
//
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
function ep_download_file(){
	global $pws_engine;
	global $_POST,$_GET;
	global $system,$language,$languages,$languages_id,$epdlanguage
		,$attribute_options_array,$attribute_options_select
		,$default_these,$filelayout,$fileheaders,$filelayout_count,$filelayout_sql
		,$custom_fields,$ep_separator;
	$dltype=$_GET['dltype'];
	if ( !empty($_GET['dltype']) ) {
		// if dltype is set, then create the filelayout.  Otherwise it gets read from the uploaded file
		list($filelayout, $filelayout_count, $filelayout_sql, $fileheaders) = ep_create_filelayout($dltype, $attribute_options_array, $languages, $custom_fields); // get the right filelayout for this download
	}
	// $EXPORT_TIME=time();  // start export time when export is started.
	$EXPORT_TIME = strftime('%Y%b%d-%H%I');
	if ($dltype=='froogle'){
		$EXPORT_TIME = "FroogleEP" . $EXPORT_TIME;
	} else {
		$EXPORT_TIME = "EP" . $EXPORT_TIME;
	}
	// set the type
	if ( $dltype != 'froogle' ){
		$endofrow = false;
	} else {
		// default to normal end of row
		$endofrow = 'EOREOR';
	}
	switch ($_GET['download']){
		case 'stream':
		case 'activestream':
			/*******************************
			 * Stream file (lo stream file sarebbe buffered, ma l'ho soppresso perchè pericoloso
			 * per la memoria e quindi l'efficenza del server
			 *******************************/
			header("Content-type: application/vnd.ms-excel");
			header("Content-disposition: attachment; filename=$EXPORT_TIME" . ((EP_EXCEL_SAFE_OUTPUT == true)?".csv":".txt"));
			// Changed if using SSL, helps prevent program delay/timeout (add to backup.php also)
			//    header("Pragma: no-cache");
			if ($request_type== 'NONSSL'){
				header("Pragma: no-cache");
			} else {
				header("Pragma: ");
			}
			header("Expires: 0");
			//echo $filestring;
			$fp=fopen('php://output','w');
			break;
		case 'tempfile':
			//*******************************
			// PUT FILE IN TEMP DIR
			//*******************************
			$tmpfname = EP_TEMP_DIRECTORY . "$EXPORT_TIME" . ((EP_EXCEL_SAFE_OUTPUT == true)?".csv":".txt");
			//unlink($tmpfname);
			$fp = fopen( $tmpfname, "w+");
			if (!$fp){
				echo "<p class=smallText><font color=red>".sprintf(EP_ERROR_OPENING_FILE_WRITE,$file['name'])."</font></p>";
				return false;
			}
			break;
		
	}
	// Here we need to allow for the mapping of internal field names to external field names
	// default to all headers named like the internal ones
	// the field mapping array only needs to cover those fields that need to have their name changed
	if ( count($fileheaders) != 0 ){
		$filelayout_header = $fileheaders; // if they gave us fileheaders for the dl, then use them
	} else {
		$filelayout_header = $filelayout; // if no mapping was spec'd use the internal field names for header names
	}
	$filelayout_header=array_flip($filelayout_header);
	if ($endofrow!=false)
		array_push($filelayout_header,$endofrow);
	fputcsv($fp,$filelayout_header,$ep_separator);
	



	$num_of_langs = count($languages);
	$result = tep_db_query($filelayout_sql);
	while ($row =  tep_db_fetch_array($result)){


		// if the filelayout says we need a products_name, get it
		// build the long full froogle image path
		$row['v_products_fullpath_image'] = EP_FROOGLE_IMAGE_PATH . $row['v_products_image'];
		// Other froogle defaults go here for now
		$row['v_froogle_quantitylevel']     = $row['v_products_quantity'];
		$row['v_froogle_manufacturer_id']   = '';
		$row['v_froogle_exp_date']          = date('Y-m-d', strtotime('+30 days'));
		$row['v_froogle_product_type']      = $row['v_categories_id'];
		$row['v_froogle_product_id']        = $row['v_products_model'];
		$row['v_froogle_currency']          = EP_FROOGLE_CURRENCY;

		// names and descriptions require that we loop thru all languages that are turned on in the store
		foreach ($languages as $key => $lang){
			$lid = $lang['id'];

			// for each language, get the description and set the vals
			$sql2 = "SELECT *
                FROM ".TABLE_PRODUCTS_DESCRIPTION."
                WHERE
                    products_id = " . $row['v_products_id'] . " AND
                    language_id = '" . $lid . "'
                ";
			$result2 = tep_db_query($sql2);
			$row2 =  tep_db_fetch_array($result2);

			// I'm only doing this for the first language, since right now froogle is US only.. Fix later!
			// adding url for froogle, but it should be available no matter what
			if (EP_FROOGLE_SEF_URLS == true){
				// if only one language
				if ($num_of_langs == 1){
					$row['v_froogle_products_url_' . $lid] = EP_FROOGLE_PRODUCT_INFO_PATH . '/products_id/' . $row['v_products_id'];
				} else {
					$row['v_froogle_products_url_' . $lid] = EP_FROOGLE_PRODUCT_INFO_PATH . '/products_id/' . $row['v_products_id'] . '/language/' . $lid;
				}
			} else {
				if ($num_of_langs == 1){
					$row['v_froogle_products_url_' . $lid] = EP_FROOGLE_PRODUCT_INFO_PATH . '?products_id=' . $row['v_products_id'];
				} else {
					$row['v_froogle_products_url_' . $lid] = EP_FROOGLE_PRODUCT_INFO_PATH . '?products_id=' . $row['v_products_id'] . '&language=' . $lid;
				}
			}

			$row['v_products_name_' . $lid]          = $row2['products_name'];
			$row['v_products_description_' . $lid]   = $row2['products_description'];
			$row['v_products_url_' . $lid]           = $row2['products_url'];
			foreach ($custom_fields[TABLE_PRODUCTS_DESCRIPTION] as $key => $name) {
				$row['v_' . $key . '_' . $lid]           = $row2[$key];
			}
			// froogle advanced format needs the quotes around the name and desc
			$row['v_froogle_products_name_' . $lid] = '"' . strip_tags(str_replace('"','""',$row2['products_name'])) . '"';
			$row['v_froogle_products_description_' . $lid] = '"' . strip_tags(str_replace('"','""',$row2['products_description'])) . '"';

			// support for Linda's Header Controller 2.0 here
			if(isset($filelayout['v_products_head_title_tag_' . $lid])){
				$row['v_products_head_title_tag_' . $lid]     = $row2['products_head_title_tag'];
				$row['v_products_head_desc_tag_' . $lid]      = $row2['products_head_desc_tag'];
				$row['v_products_head_keywords_tag_' . $lid]  = $row2['products_head_keywords_tag'];
			}
			// end support for Header Controller 2.0

		}

		// for the categories, we need to keep looping until we find the root category
		// start with v_categories_id
		// Get the category description
		// set the appropriate variable name
		// if parent_id is not null, then follow it up.
		// we'll populate an aray first, then decide where it goes in the
		$thecategory_id = $row['v_categories_id'];
		$fullcategory = ''; // this will have the entire category stack for froogle
		for( $categorylevel=1; $categorylevel<=EP_MAX_CATEGORIES; $categorylevel++){
			if ($thecategory_id){

				$sql3 = "SELECT parent_id,
								categories_image
						 FROM ".TABLE_CATEGORIES."
						 WHERE    
								categories_id = " . $thecategory_id . '';
				$result3 = tep_db_query($sql3);
				if ($row3 = tep_db_fetch_array($result3)) {
					$temprow['v_categories_image_' . $categorylevel] = $row3['categories_image'];
				}

				foreach ($languages as $key => $lang){
					$sql2 = "SELECT categories_name
							 FROM ".TABLE_CATEGORIES_DESCRIPTION."
							 WHERE    
									categories_id = " . $thecategory_id . " AND
									language_id = " . $lang['id'];
					$result2 = tep_db_query($sql2);
					if ($row2 =  tep_db_fetch_array($result2)) {
						$temprow['v_categories_name_' . $categorylevel . '_' . $lang['id']] = $row2['categories_name'];
					}
					if ($lang['id'] == '1') {
						//$fullcategory .= " > " . $row2['categories_name'];
						$fullcategory = $row2['categories_name'] . " > " . $fullcategory;
					}

				}

				// now get the parent ID if there was one
				$theparent_id = $row3['parent_id'];
				if ($theparent_id != ''){
					// there was a parent ID, lets set thecategoryid to get the next level
					$thecategory_id = $theparent_id;
				} else {
					// we have found the top level category for this item,
					$thecategory_id = false;
				}

			} else {
				$temprow['v_categories_image_' . $categorylevel] = '';
				foreach ($languages as $key => $lang){
					$temprow['v_categories_name_' . $categorylevel . '_' . $lang['id']] = '';
				}
			}
		}

		// now trim off the last ">" from the category stack
		$row['v_category_fullpath'] = substr($fullcategory,0,strlen($fullcategory)-3);

		// temprow has the old style low to high level categories.
		$newlevel = 1;
		// let's turn them into high to low level categories
		for( $categorylevel=EP_MAX_CATEGORIES; $categorylevel>0; $categorylevel--){
			$found = false;
			if ($temprow['v_categories_image_' . $categorylevel] != ''){
				$row['v_categories_image_' . $newlevel] = $temprow['v_categories_image_' . $categorylevel];
				$found = true;
			}
			foreach ($languages as $key => $lang){
				if ($temprow['v_categories_name_' . $categorylevel . '_' . $lang['id']] != ''){
					$row['v_categories_name_' . $newlevel . '_' . $lang['id']] = $temprow['v_categories_name_' . $categorylevel . '_' . $lang['id']];
					$found = true;
				}
			}
			if ($found == true) {
				$newlevel++;
			}
		}


		// if the filelayout says we need a manufacturers name, get it
		if (isset($filelayout['v_manufacturers_name'])){
			if ($row['v_manufacturers_id'] != ''){
				$sql2 = "SELECT manufacturers_name
                    FROM ".TABLE_MANUFACTURERS."
                    WHERE
                    manufacturers_id = " . $row['v_manufacturers_id']
				;
				$result2 = tep_db_query($sql2);
				$row2 =  tep_db_fetch_array($result2);
				$row['v_manufacturers_name'] = $row2['manufacturers_name'];
			}
		}


		// If you have other modules that need to be available, put them here

		// VJ product attribs begin
		if (isset($filelayout['v_attribute_options_id_1'])){

			$attribute_options_count = 1;
			foreach ($attribute_options_array as $attribute_options) {
				$row['v_attribute_options_id_' . $attribute_options_count]     = $attribute_options['products_options_id'];

				for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
					$lid = $languages[$i]['id'];

					$attribute_options_languages_query = "select products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$attribute_options['products_options_id'] . "' and language_id = '" . (int)$lid . "'";

					$attribute_options_languages_values = tep_db_query($attribute_options_languages_query);

					$attribute_options_languages = tep_db_fetch_array($attribute_options_languages_values);

					$row['v_attribute_options_name_' . $attribute_options_count . '_' . $lid] = $attribute_options_languages['products_options_name'];
				}

				$attribute_values_query = "select products_options_values_id from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$attribute_options['products_options_id'] . "' order by products_options_values_id";

				$attribute_values_values = tep_db_query($attribute_values_query);

				$attribute_values_count = 1;
				while ($attribute_values = tep_db_fetch_array($attribute_values_values)) {
					$row['v_attribute_values_id_' . $attribute_options_count . '_' . $attribute_values_count]     = $attribute_values['products_options_values_id'];

					$attribute_values_price_query = "select options_values_price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$row['v_products_id'] . "' and options_id = '" . (int)$attribute_options['products_options_id'] . "' and options_values_id = '" . (int)$attribute_values['products_options_values_id'] . "'";

					$attribute_values_price_values = tep_db_query($attribute_values_price_query);

					$attribute_values_price = tep_db_fetch_array($attribute_values_price_values);

					$row['v_attribute_values_price_' . $attribute_options_count . '_' . $attribute_values_count]     = $attribute_values_price['price_prefix'] . $attribute_values_price['options_values_price'];

					//// attributes stock add start
					if ( EP_PRODUCTS_ATTRIBUTES_STOCK    == true ) {
						$stock_attributes = $attribute_options['products_options_id'].'-'.$attribute_values['products_options_values_id'];
						 
						$stock_quantity_query = tep_db_query("select products_stock_quantity from " . TABLE_PRODUCTS_STOCK . " where products_id = '" . (int)$row['v_products_id'] . "' and products_stock_attributes = '" . $stock_attributes . "'");
						$stock_quantity = tep_db_fetch_array($stock_quantity_query);
						 
						$row['v_attribute_values_stock_' . $attribute_options_count . '_' . $attribute_values_count] = $stock_quantity['products_stock_quantity'];
     }
     //// attributes stock add end


     for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
     	$lid = $languages[$i]['id'];

     	$attribute_values_languages_query = "select products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$attribute_values['products_options_values_id'] . "' and language_id = '" . (int)$lid . "'";

     	$attribute_values_languages_values = tep_db_query($attribute_values_languages_query);

     	$attribute_values_languages = tep_db_fetch_array($attribute_values_languages_values);

     	$row['v_attribute_values_name_' . $attribute_options_count . '_' . $attribute_values_count . '_' . $lid] = $attribute_values_languages['products_options_values_name'];
     }

     $attribute_values_count++;
				}

				$attribute_options_count++;
			}
		}
		// VJ product attribs end

		// this is for the separate price per customer module
		if (isset($filelayout['v_customer_discount_1'])){
			$sql2 = "SELECT
                    customers_group_price,
                    customers_group_id
                FROM
                    ".TABLE_PRODUCTS_GROUPS."
                WHERE
                products_id = " . $row['v_products_id'] . "
                ORDER BY
                customers_group_id"
                ;
                $result2 = tep_db_query($sql2);
                $ll = 1;
                $row2 =  tep_db_fetch_array($result2);
                while( $row2 ){
                	$row['v_customer_group_id_' . $ll]     = $row2['customers_group_id'];
                	//$row['v_customer_discount_' . $ll]     = $row2['customers_group_price'];
                	$row['v_customer_discount_' . $ll]     = $row2['customers_group_discount'];
                	$row2 = tep_db_fetch_array($result2);
                	$ll++;
                }
		}

		if ($dltype == 'froogle'){
			// For froogle, we check the specials prices for any applicable specials, and use that price
			// by grabbing the specials id descending, we always get the most recently added special price
			// I'm checking status because I think you can turn off specials
			$sql2 = "SELECT
                    specials_new_products_price
                FROM
                    ".TABLE_SPECIALS."
                WHERE
                products_id = " . $row['v_products_id'] . " and
                status = 1 and
                expires_date < CURRENT_TIMESTAMP
                ORDER BY
                    specials_id DESC"
                    ;
                    $result2 = tep_db_query($sql2);
                    $ll = 1;
                    $row2 =  tep_db_fetch_array($result2);
                    if( $row2 ){
                    	// reset the products price to our special price if there is one for this product
                    	$row['v_products_price']     = $row2['specials_new_products_price'];
                    }
		}

		//elari -
		//We check the value of tax class and title instead of the id
		//Then we add the tax to price if EP_PRICE_WITH_TAX is set to true
		$row_tax_multiplier         = tep_get_tax_class_rate($row['v_tax_class_id']);
		$row['v_tax_class_title']   = tep_get_tax_class_title($row['v_tax_class_id']);
		$row['v_products_price']    = $row['v_products_price'] +
		(EP_PRICE_WITH_TAX == true ? round( ($row['v_products_price'] * $row_tax_multiplier / 100), EP_PRECISION) : 0);


		// Now set the status to a word the user specd in the config vars
		if ( $row['v_status'] == '1' ){
			$row['v_status'] = EP_TEXT_ACTIVE;
		} else {
			$row['v_status'] = EP_TEXT_INACTIVE;
		}

//		// remove any bad things in the texts that could confuse EasyPopulate
//		$therow = '';
//		foreach( $filelayout as $key => $value ){
//			//echo "The field was $key<br />";
//
//			$thetext = $row[$key];
//			// kill the carriage returns and tabs in the descriptions, they're killing me!
//			if (EP_PRESERVE_TABS_CR_LF == false || $dltype == 'froogle') {
//				$thetext = str_replace("\r",' ',$thetext);
//				$thetext = str_replace("\n",' ',$thetext);
//				$thetext = str_replace("\t",' ',$thetext);
//			}
//			if (EP_EXCEL_SAFE_OUTPUT == true && $dltype != 'froogle') {
//				// use quoted values and escape the embedded quotes for excel safe output.
//				$therow .= '"'.str_replace('"','""',$thetext).'"' . $ep_separator;
//			} else {
//				// and put the text into the output separated by $ep_separator defined above
//				$therow .= $thetext . $ep_separator;
//			}
//		}
//
//		// lop off the trailing separator, then append the end of row indicator
//		$therow = substr($therow,0,strlen($therow)-1) . $endofrow;
		$therow=array();
		reset($filelayout);
		foreach( $filelayout as $key => $value ){
			$thetext = $row[$key];
			if (EP_CONVERT_EXCEL_FLOATS
				&& strpos($key,'model')===false
				&& strpos($key,'name')===false
				&& strpos($key,'image')===false
				&& strpos($key,'description')===false
				){
				$float=$thetext;
				$floatval=floatval($float);
				$floatvalstr=strval($floatval);
				if (is_float($floatval) && $floatvalstr==$float){
					$thetext=str_replace('.',',',$floatvalstr);
				}
			}
				
			// kill the carriage returns and tabs in the descriptions, they're killing me!
			if (EP_PRESERVE_TABS_CR_LF == false || $dltype == 'froogle') {
				$thetext = str_replace("\r",' ',$thetext);
				$thetext = str_replace("\n",' ',$thetext);
				$thetext = str_replace("\t",' ',$thetext);
			}
			array_push($therow,$thetext);
		}
		
		if ($endofrow!=false)
			array_push($therow,$endofrow);
		fputcsv($fp,$therow,$ep_separator);
		
//		if ($_GET['download'] == 'activestream'){
//			echo $therow;
//		} else {
//			$filestring .= $therow;
//		}
		// grab the next row from the db
		//$row =  tep_db_fetch_array($result);
	}

	// now either stream it to them or put it in the temp directory
	switch ($_GET['download']){
		case 'activestream':
		case 'stream':
			die();
		case 'tempfile':
		echo "You can get your file in the Tools/File Manager here: " . EP_TEMP_DIRECTORY . "EP" . $EXPORT_TIME . ((EP_EXCEL_SAFE_OUTPUT == true)?".csv":".txt");
		die();
	}
}
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
//
// ep_create_filelayout()
//
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
function ep_create_filelayout($dltype, $attribute_options_array, $languages, $custom_fields){

	// depending on the type of the download the user wanted, create a file layout for it.
	$fieldmap = array(); // default to no mapping to change internal field names to external.

	// build filters
	$sql_filter = '';
	if (!empty($_GET['epcust_category_filter'])) {
		$sub_categories = array();
		$categories_query_addition = 'ptoc.categories_id = ' . (int)$_GET['epcust_category_filter'] . '';
		tep_get_sub_categories($sub_categories, $_GET['epcust_category_filter']);
		foreach ($sub_categories AS $ckey => $category ) {
			$categories_query_addition .= ' or ptoc.categories_id = ' . (int)$category . '';
		}
		$sql_filter .= ' and (' . $categories_query_addition . ')';
	}
	if ($_GET['epcust_manufacturer_filter']!='') {
		$sql_filter .= ' and p.manufacturers_id = ' . (int)$_GET['epcust_manufacturer_filter'];
	}
	if ($_GET['epcust_status_filter']!='') {
		$sql_filter .= ' and p.products_status = ' . (int)$_GET['epcust_status_filter'];
	}

	// /////////////////////////////////////////////////////////////////////
	//
	// Start: Support for other contributions
	//
	// /////////////////////////////////////////////////////////////////////

	$ep_additional_layout_product = '';
	$ep_additional_layout_product_select = '';
	$ep_additional_layout_product_description = '';
	$ep_additional_layout_pricing = '';

	if ( $dltype == 'full'){
		foreach ($custom_fields[TABLE_PRODUCTS] as $key => $name) {
			$ep_additional_layout_product .= '$filelayout[\'v_' . $key . '\'] = $iii++;
                                            ';
			$ep_additional_layout_product_select .= 'p.' . $key . ' as v_' . $key . ',';
		}
	}

	if (EP_MORE_PICS_6_SUPPORT == true) {
		$ep_additional_layout_product .= '$filelayout[\'v_products_subimage1\'] = $iii++;
                                        $filelayout[\'v_products_subimage2\'] = $iii++;
                                        $filelayout[\'v_products_subimage3\'] = $iii++;
                                        $filelayout[\'v_products_subimage4\'] = $iii++;
                                        $filelayout[\'v_products_subimage5\'] = $iii++;
                                        $filelayout[\'v_products_subimage6\'] = $iii++;
                                        ';
		$ep_additional_layout_product_select .= 'p.products_subimage1 as v_products_subimage1, p.products_subimage2 as v_products_subimage2, p.products_subimage3 as v_products_subimage3, p.products_subimage4 as v_products_subimage4, p.products_subimage5 as v_products_subimage5, p.products_subimage6 as v_products_subimage6,';
	}

	if (EP_UNKNOWN_ADD_IMAGES_SUPPORT == true) {
		$ep_additional_layout_product .= '$filelayout[\'v_products_mimage\'] = $iii++;
                                        $filelayout[\'v_products_bimage\'] = $iii++;
                                        $filelayout[\'v_products_subimage1\'] = $iii++;
                                        $filelayout[\'v_products_bsubimage1\'] = $iii++;
                                        $filelayout[\'v_products_subimage2\'] = $iii++;
                                        $filelayout[\'v_products_bsubimage2\'] = $iii++;
                                        $filelayout[\'v_products_subimage3\'] = $iii++;
                                        $filelayout[\'v_products_bsubimage3\'] = $iii++;
                                        ';
		$ep_additional_layout_product_select .= 'p.products_mimage as v_products_mimage, p.products_bimage as v_products_bimage, p.products_subimage1 as v_products_subimage1, p.products_bsubimage1 as v_products_bsubimage1, p.products_subimage2 as v_products_subimage2, p.products_bsubimage2 as v_products_bsubimage2, p.products_subimage3 as v_products_subimage3, p.products_bsubimage3 as v_products_bsubimage3,';
	}

	if (EP_SPPC_SUPPORT == true) {
		$ep_additional_layout_pricing .= '$filelayout[\'v_customer_discount_1\'] = $iii++;
                                        $filelayout[\'v_customer_group_id_1\'] = $iii++;
                                        $filelayout[\'v_customer_discount_2\'] = $iii++;
                                        $filelayout[\'v_customer_group_id_2\'] = $iii++;
                                        $filelayout[\'v_customer_discount_3\'] = $iii++;
                                        $filelayout[\'v_customer_group_id_3\'] = $iii++;
                                        $filelayout[\'v_customer_discount_4\'] = $iii++;
                                        $filelayout[\'v_customer_group_id_4\'] = $iii++;
                                        ';
//		$ep_additional_layout_pricing .= '$filelayout[\'v_customer_price_1\'] = $iii++;
//                                        $filelayout[\'v_customer_group_id_1\'] = $iii++;
//                                        $filelayout[\'v_customer_price_2\'] = $iii++;
//                                        $filelayout[\'v_customer_group_id_2\'] = $iii++;
//                                        $filelayout[\'v_customer_price_3\'] = $iii++;
//                                        $filelayout[\'v_customer_group_id_3\'] = $iii++;
//                                        $filelayout[\'v_customer_price_4\'] = $iii++;
//                                        $filelayout[\'v_customer_group_id_4\'] = $iii++;
//                                        ';
	}

	if (EP_HTC_SUPPORT == true) {
		$ep_additional_layout_product_description .= '$filelayout[\'v_products_head_title_tag_\'.$lang[\'id\']]    = $iii++;
                                                    $filelayout[\'v_products_head_desc_tag_\'.$lang[\'id\']]     = $iii++;
                                                    $filelayout[\'v_products_head_keywords_tag_\'.$lang[\'id\']] = $iii++;
                                                    ';
	}

	// /////////////////////////////////////////////////////////////////////
	// End: Support for other contributions
	// /////////////////////////////////////////////////////////////////////



	switch( $dltype ){

		case 'full':
			// The file layout is dynamically made depending on the number of languages
			$iii = 0;
			$filelayout = array();

			$filelayout['v_products_model'] = $iii++;

			foreach ($languages as $key => $lang){
				$filelayout['v_products_name_'.$lang['id']]        = $iii++;
				$filelayout['v_products_description_'.$lang['id']] = $iii++;
				$filelayout['v_products_url_'.$lang['id']]         = $iii++;
				foreach ($custom_fields[TABLE_PRODUCTS_DESCRIPTION] as $key => $name) {
					$filelayout['v_' . $key . '_'.$lang['id']]         = $iii++;
				}
				if (!empty($ep_additional_layout_product_description)) {
					eval($ep_additional_layout_product_description);
				}
					
			}

			$filelayout['v_products_image'] = $iii++;

			if (!empty($ep_additional_layout_product)) {
				eval($ep_additional_layout_product);
			}

			$filelayout['v_products_price']    = $iii++;

			if (!empty($ep_additional_layout_pricing)) {
				eval($ep_additional_layout_pricing);
			}

			$filelayout['v_products_quantity'] = $iii++;
			$filelayout['v_products_weight']   = $iii++;
			$filelayout['v_date_avail']        = $iii++;
			$filelayout['v_date_added']        = $iii++;

			// build the categories name section of the array based on the number of categores the user wants to have
			for($i=1; $i<EP_MAX_CATEGORIES+1; $i++){
				$filelayout['v_categories_image_' . $i] = $iii++;
				foreach ($languages as $key => $lang){
					$filelayout['v_categories_name_' . $i . '_' . $lang['id']] = $iii++;
				}
			}

			$filelayout['v_manufacturers_name'] = $iii++;

			// VJ product attribs begin
			$attribute_options_count = 1;
			foreach ($attribute_options_array as $tkey => $attribute_options_values) {
				$filelayout['v_attribute_options_id_'.$attribute_options_count] = $iii++;
				foreach ($languages as $tkey => $lang ) {
					$filelayout['v_attribute_options_name_'.$attribute_options_count.'_'.$lang['id']] = $iii++;
				}

				$attribute_values_query = "select products_options_values_id  from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$attribute_options_values['products_options_id'] . "' order by products_options_values_id";
				$attribute_values_values = tep_db_query($attribute_values_query);

				$attribute_values_count = 1;
				while ($attribute_values = tep_db_fetch_array($attribute_values_values)) {
					$filelayout['v_attribute_values_id_'.$attribute_options_count.'_'.$attribute_values_count] = $iii++;
					foreach ($languages as $tkey => $lang ) {
						$filelayout['v_attribute_values_name_'.$attribute_options_count.'_'.$attribute_values_count.'_'.$lang['id']] = $iii++;
					}
					$filelayout['v_attribute_values_price_'.$attribute_options_count.'_'.$attribute_values_count] = $iii++;
					//// attributes stock add start
					if ( EP_PRODUCTS_ATTRIBUTES_STOCK == true ) {
						$filelayout['v_attribute_values_stock_'.$attribute_options_count.'_'.$attribute_values_count] = $iii++;
					}
					//// attributes stock add end
					$attribute_values_count++;
				}
				$attribute_options_count++;
			}
			// VJ product attribs end

			$filelayout['v_tax_class_title']  = $iii++;
			$filelayout['v_status']           = $iii++;

			$filelayout_sql = "SELECT
            p.products_id as v_products_id,
            p.products_model as v_products_model,
            p.products_image as v_products_image,
            $ep_additional_layout_product_select
            p.products_price as v_products_price,
            p.products_weight as v_products_weight,
            p.products_date_available as v_date_avail,
            p.products_date_added as v_date_added,
            p.products_tax_class_id as v_tax_class_id,
            p.products_quantity as v_products_quantity,
            p.manufacturers_id as v_manufacturers_id,
            subc.categories_id as v_categories_id,
            p.products_status as v_status
            FROM
            ".TABLE_PRODUCTS." as p,
            ".TABLE_CATEGORIES." as subc,
            ".TABLE_PRODUCTS_TO_CATEGORIES." as ptoc
            WHERE
            p.products_id = ptoc.products_id AND
            ptoc.categories_id = subc.categories_id
            " . $sql_filter;

            break;

		case 'priceqty':
			$iii = 0;
			$filelayout = array();

			$filelayout['v_products_model']    = $iii++;
			$filelayout['v_products_price']    = $iii++;
			$filelayout['v_products_quantity'] = $iii++;

			if (!empty($ep_additional_layout_pricing)) {
				eval($ep_additional_layout_pricing);
			}

			$filelayout_sql = "SELECT
            p.products_id as v_products_id,
            p.products_model as v_products_model,
            p.products_price as v_products_price,
            p.products_tax_class_id as v_tax_class_id,
            p.products_quantity as v_products_quantity
            FROM
            ".TABLE_PRODUCTS." as p
            ";
			break;

		case 'custom':
			$iii = 0;
			$filelayout = array();

			$filelayout['v_products_model'] = $iii++;

			if (!empty($_GET['epcust_status'])) { $filelayout['v_status'] = $iii++; }

			foreach ($languages as $key => $lang){
				if (!empty($_GET['epcust_name'])) { $filelayout['v_products_name_'.$lang['id']] = $iii++; }
				if (!empty($_GET['epcust_description'])) { $filelayout['v_products_description_'.$lang['id']] = $iii++; }
				if (!empty($_GET['epcust_url'])) { $filelayout['v_products_url_'.$lang['id']] = $iii++; }
				foreach ($custom_fields[TABLE_PRODUCTS_DESCRIPTION] as $key => $name) {
					if (!empty($_GET['epcust_' . $key])) { $filelayout['v_' . $key . '_'.$lang['id']] = $iii++; }
				}
			}

			if (!empty($_GET['epcust_image'])) {
				$filelayout['v_products_image'] = $iii++;

				if (!empty($ep_additional_layout_product)) {
					eval($ep_additional_layout_product);
				}
			}

			foreach ($custom_fields[TABLE_PRODUCTS] as $key => $name) {
				if (!empty($_GET['epcust_' . $key])) {
					$filelayout['v_' . $key] = $iii++;
					$ep_additional_layout_product_select .= 'p.' . $key . ' as v_' . $key . ',';
				}
			}

			if (!empty($_GET['epcust_price'])) { $filelayout['v_products_price'] = $iii++; }
			if (!empty($_GET['epcust_quantity'])) { $filelayout['v_products_quantity'] = $iii++; }
			if (!empty($_GET['epcust_weight'])) { $filelayout['v_products_weight'] = $iii++; }
			if (!empty($_GET['epcust_avail'])) { $filelayout['v_date_avail'] = $iii++; }
			if (!empty($_GET['epcust_date_added'])) { $filelayout['v_date_added'] = $iii++; }

			if (!empty($_GET['epcust_category'])) {
		  // build the categories name section of the array based on the number
		  // of categores the user wants to have
		  for($i=1; $i<=EP_MAX_CATEGORIES; $i++){
		  	$filelayout['v_categories_image_'.$i] = $iii++;
		  	foreach ($languages as $key => $lang){
		  		$filelayout['v_categories_name_'.$i.'_'.$lang['id']] = $iii++;
		  	}
		  }
			}

			if (!empty($_GET['epcust_manufacturer'])) { $filelayout['v_manufacturers_name'] = $iii++; }

			if (!empty($_GET['epcust_attributes'])) {
				// VJ product attribs begin
				$attribute_options_count = 1;
				foreach ($attribute_options_array as $tkey => $attribute_options_values) {
					$filelayout['v_attribute_options_id_'.$attribute_options_count] = $iii++;
					foreach ($languages as $tkey => $lang ) {
						$filelayout['v_attribute_options_name_'.$attribute_options_count.'_'.$lang['id']] = $iii++;
					}

					$attribute_values_query = "select products_options_values_id  from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$attribute_options_values['products_options_id'] . "' order by products_options_values_id";
					$attribute_values_values = tep_db_query($attribute_values_query);

					$attribute_values_count = 1;
					while ($attribute_values = tep_db_fetch_array($attribute_values_values)) {
						$filelayout['v_attribute_values_id_'.$attribute_options_count.'_'.$attribute_values_count] = $iii++;
						foreach ($languages as $tkey => $lang ) {
							$filelayout['v_attribute_values_name_'.$attribute_options_count.'_'.$attribute_values_count.'_'.$lang['id']] = $iii++;
						}
						$filelayout['v_attribute_values_price_'.$attribute_options_count.'_'.$attribute_values_count] = $iii++;
						//// attributes stock add start
						if ( EP_PRODUCTS_ATTRIBUTES_STOCK == true ) {
							$filelayout['v_attribute_values_stock_'.$attribute_options_count.'_'.$attribute_values_count] = $iii++;
						}
						//// attributes stock add end
						$attribute_values_count++;
					}
					$attribute_options_count++;
				}
				// VJ product attribs end
			}
			if (!empty($_GET['epcust_tax_class'])) { $filelayout['v_tax_class_title'] = $iii++; }
			if (!empty($_GET['epcust_comment'])) { $filelayout['v_products_comment'] = $iii++; }

			$filelayout_sql = "SELECT
            p.products_id as v_products_id,
            p.products_model as v_products_model,
            p.products_status as v_status,
            p.products_price as v_products_price,
            p.products_quantity as v_products_quantity,
            p.products_weight as v_products_weight,
            p.products_image as v_products_image,
            $ep_additional_layout_product_select
            p.manufacturers_id as v_manufacturers_id,
            p.products_date_available as v_date_avail,
            p.products_date_added as v_date_added,
            p.products_tax_class_id as v_tax_class_id,
            subc.categories_id as v_categories_id
            FROM
            ".TABLE_PRODUCTS." as p,
            ".TABLE_CATEGORIES." as subc,
            ".TABLE_PRODUCTS_TO_CATEGORIES." as ptoc
            WHERE
            p.products_id = ptoc.products_id AND
            ptoc.categories_id = subc.categories_id
            " . $sql_filter;
            break;

		case 'category':
			$iii = 0;
			$filelayout = array();

			$filelayout['v_products_model'] = $iii++;

			for($i=1; $i<EP_MAX_CATEGORIES+1; $i++){
				$filelayout['v_categories_image_'.$i] = $iii++;
				foreach ($languages as $key => $lang){
					$filelayout['v_categories_name_'.$i.'_'.$lang['id']] = $iii++;
				}
			}

			$filelayout_sql = "SELECT
            p.products_id as v_products_id,
            p.products_model as v_products_model,
            subc.categories_id as v_categories_id
            FROM
            ".TABLE_PRODUCTS." as p,
            ".TABLE_CATEGORIES." as subc,
            ".TABLE_PRODUCTS_TO_CATEGORIES." as ptoc            
            WHERE
            p.products_id = ptoc.products_id AND
            ptoc.categories_id = subc.categories_id
            ";
			break;

		case 'extra_fields':
			// start EP for product extra field ============================= DEVSOFTVN - 10/20/2005
			$iii = 0;
			$filelayout = array(
            'v_products_model'        => $iii++,
            'v_products_extra_fields_name'        => $iii++, 
            'v_products_extra_fields_id'        => $iii++,
			//            'v_products_id'        => $iii++,
            'v_products_extra_fields_value'        => $iii++,
			);

			$filelayout_sql = "SELECT
                        p.products_id as v_products_id,
                        p.products_model as v_products_model,
                        subc.products_extra_fields_id as v_products_extra_fields_id,
                        subc.products_extra_fields_value as v_products_extra_fields_value,
                        ptoc.products_extra_fields_name as v_products_extra_fields_name
                        FROM
                        ".TABLE_PRODUCTS." as p,
                        ".TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS." as subc,
                        ".TABLE_PRODUCTS_EXTRA_FIELDS." as ptoc
                        WHERE
                        p.products_id = subc.products_id AND
                        ptoc.products_extra_fields_id = subc.products_extra_fields_id
                        ";    
			// end of EP for extra field code ======= DEVSOFTVN================
			break;


		case 'froogle':
			// this is going to be a little interesting because we need
			// a way to map from internal names to external names
			//
			// Before it didn't matter, but with froogle needing particular headers,
			// The file layout is dynamically made depending on the number of languages
			$iii = 0;
			$filelayout = array();

			$filelayout['v_froogle_products_url_1'] = $iii++;
			$filelayout['v_froogle_products_name_'.EP_DEFAULT_LANGUAGE_ID] = $iii++;
			$filelayout['v_froogle_products_description_'.EP_DEFAULT_LANGUAGE_ID] = $iii++;
			$filelayout['v_products_price'] = $iii++;
			$filelayout['v_products_fullpath_image'] = $iii++;
			$filelayout['v_froogle_product_id'] = $iii++;
			$filelayout['v_froogle_quantitylevel'] = $iii++;
			$filelayout['v_category_fullpath'] = $iii++;
			$filelayout['v_froogle_exp_date'] = $iii++;
			$filelayout['v_froogle_currency'] = $iii++;

			$iii=0;
			$fileheaders = array();

			// EP Support mapping new names to the export headers.
			// use the $fileheaders[''] vars to do that.
			$fileheaders['link'] = $iii++;
			$fileheaders['title'] = $iii++;
			$fileheaders['description'] = $iii++;
			$fileheaders['price'] = $iii++;
			$fileheaders['image_link'] = $iii++;
			$fileheaders['id'] = $iii++;
			$fileheaders['quantity'] = $iii++;
			$fileheaders['product_type'] = $iii++;
			$fileheaders['expiration_date'] = $iii++;
			$fileheaders['currency'] = $iii++;

			$filelayout_sql = "SELECT
            p.products_id as v_products_id,
            p.products_model as v_products_model,
            p.products_image as v_products_image,
            p.products_price as v_products_price,
            p.products_weight as v_products_weight,
            p.products_date_added as v_date_added,
            p.products_date_available as v_date_avail,
            p.products_tax_class_id as v_tax_class_id,
            p.products_quantity as v_products_quantity,
            p.manufacturers_id as v_manufacturers_id,
            subc.categories_id as v_categories_id
            FROM
            ".TABLE_PRODUCTS." as p,
            ".TABLE_CATEGORIES." as subc,
            ".TABLE_PRODUCTS_TO_CATEGORIES." as ptoc
            WHERE
            p.products_id = ptoc.products_id AND
            ptoc.categories_id = subc.categories_id
            " . $sql_filter;
			break;

			// VJ product attributes begin
		case 'attrib':
			$iii = 0;
			$filelayout = array();

			$filelayout['v_products_model'] = $iii++;

			$attribute_options_count = 1;
			foreach ($attribute_options_array as $tkey1 => $attribute_options_values) {
				$filelayout['v_attribute_options_id_'.$attribute_options_count] = $iii++;
				foreach ($languages as $tkey => $lang ) {
					$filelayout['v_attribute_options_name_'.$attribute_options_count.'_'.$lang['id']] = $iii++;
				}

				$attribute_values_query = "select products_options_values_id  from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$attribute_options_values['products_options_id'] . "' order by products_options_values_id";
				$attribute_values_values = tep_db_query($attribute_values_query);

				$attribute_values_count = 1;
				while ($attribute_values = tep_db_fetch_array($attribute_values_values)) {
					$filelayout['v_attribute_values_id_'.$attribute_options_count.'_'.$attribute_values_count] = $iii++;
					foreach ($languages as $tkey2 => $lang ) {
						$filelayout['v_attribute_values_name_'.$attribute_options_count.'_'.$attribute_values_count.'_'.$lang['id']] = $iii++;
					}
					$filelayout['v_attribute_values_price_'.$attribute_options_count.'_'.$attribute_values_count] = $iii++;
					//// attributes stock add start
					if ( EP_PRODUCTS_ATTRIBUTES_STOCK    == true ) {
						$header_array['v_attribute_values_stock_'.$attribute_options_count.'_'.$attribute_values_count] = $iii++;
					}
					//// attributes stock add end
					$attribute_values_count++;
				}
				$attribute_options_count++;
			}

			$filelayout_sql = "SELECT
                            p.products_id as v_products_id,
                            p.products_model as v_products_model
                            FROM
                            ".TABLE_PRODUCTS." as p
                            ";

			break;
			// VJ product attributes end
	}


	$filelayout_count = count($filelayout);

	return array($filelayout, $filelayout_count, $filelayout_sql, $fileheaders);

}






///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
//
// process_row()
//
//   Processes one row of the import file
//
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
function process_row( $item1, $filelayout, $filelayout_count, $default_these, $ep_separator, $languages, $custom_fields ) {
	global $pws_engine,$languages_id;
	$items = $item1;

	// make sure all non-set things are set to '';
	// and strip the quotes from the start and end of the strings.
	// escape any special chars for the database.
	foreach( $filelayout as $key => $value){
		$i = $filelayout[$key];
		if (isset($items[$i]) == false) {
			$items[$i]='';
		} else {
			if (EP_CONVERT_EXCEL_FLOATS
				&& strpos($key,'model')===false
				&& strpos($key,'name')===false
				&& strpos($key,'image')===false
				&& strpos($key,'description')===false
				&& strpos($key,'categories')===false
				){
				$float=$items[$i];
				$floatval=floatval(str_replace(',','.',strval($float)));
				$floatvalstr=str_replace('.',',',strval($floatval));
				if (is_float($floatval) && $floatvalstr==$float){
					$items[$i]=$floatval;
				}
			}else{
				$items[$i]=trim($items[$i]);
			}
			// Check to see if either of the magic_quotes are turned on or off;
			// And apply filtering accordingly.
//			if (function_exists('ini_get')) {
//				//echo "Getting ready to check magic quotes<br />";
//				if (ini_get('magic_quotes_runtime') == 1){
//					// The magic_quotes_runtime are on, so lets account for them
//					// check if the first & last character are quotes;
//					// if it is, chop off the quotes.
//					if (substr($items[$i],-1) == '"' && substr($items[$i],0,1) == '"'){
//						$items[$i] = substr($items[$i],2,strlen($items[$i])-4);
//					}
//					// now any remaining doubled double quotes should be converted to one doublequote
//					if (EP_REPLACE_QUOTES == true){
//						if (EP_EXCEL_SAFE_OUTPUT == true) {
//							$items[$i] = str_replace('\"\"',"&#34;",$items[$i]);
//						}
//						$items[$i] = str_replace('\"',"&#34;",$items[$i]);
//						$items[$i] = str_replace("\'","&#39;",$items[$i]);
//					}
//				} else { // no magic_quotes are on
//					// check if the last character is a quote;
//					// if it is, chop off the 1st and last character of the string.
//					if (substr($items[$i],-1) == '"' && substr($items[$i],0,1) == '"'){
//						$items[$i] = substr($items[$i],1,strlen($items[$i])-2);
//					}
//					// now any remaining doubled double quotes should be converted to one doublequote
//					if (EP_REPLACE_QUOTES == true){
//						if (EP_EXCEL_SAFE_OUTPUT == true) {
//							$items[$i] = str_replace('""',"&#34;",$items[$i]);
//						}
//						$items[$i] = str_replace('"',"&#34;",$items[$i]);
//						$items[$i] = str_replace("'","&#39;",$items[$i]);
//					}
//				}
//			}
		}
	}


	// /////////////////////////////////////////////////////////////
	// Do specific functions without processing entire range of vars
	// /////////////////////////////
	// first do product extra fields
	if (isset($items[$filelayout['v_products_extra_fields_id']]) ){
		
		// EP for product extra fields Contrib by minhmaster DEVSOFTVN ==========
		$v_products_model = $items[$filelayout['v_products_model']];
		
		$v_products_extra_fields_id = $items[$filelayout['v_products_extra_fields_id']];
		//        $v_products_id    =    $items[$filelayout['v_products_id']];
		$v_products_extra_fields_value    =    $items[$filelayout['v_products_extra_fields_value']];

		$sql = "SELECT p.products_id as v_products_id FROM ".TABLE_PRODUCTS." as p WHERE p.products_model = '" . $v_products_model . "'";
		$result = tep_db_query($sql);
		$row =  tep_db_fetch_array($result);


		$sql_exist	=	"SELECT products_extra_fields_value FROM ".TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS. " WHERE (products_id ='".$row['v_products_id']. "') AND (products_extra_fields_id ='".$v_products_extra_fields_id ."')";

		if (tep_db_num_rows(tep_db_query($sql_exist)) > 0) {
			$sql_extra_field	=	"UPDATE ".TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS." SET products_extra_fields_value='".$v_products_extra_fields_value."' WHERE (products_id ='". $row['v_products_id'] . "') AND (products_extra_fields_id ='".$v_products_extra_fields_id ."')";
			$str_err_report= " $v_products_extra_fields_id | $v_products_id  | $v_products_model | $v_products_extra_fields_value | <b><font color=black>Product Extra Fields Updated</font></b><br />";
		} else {
			$sql_extra_field	=	"INSERT INTO ".TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS."(products_id,products_extra_fields_id,products_extra_fields_value) VALUES ('". $row['v_products_id'] ."','".$v_products_extra_fields_id."','".$v_products_extra_fields_value."')";
			$str_err_report= " $v_products_extra_fields_id | $v_products_id | $v_products_model | $v_products_extra_fields_value | <b><font color=green>Product Extra Fields Inserted</font></b><br />";
		}

		$result = tep_db_query($sql_extra_field);

		echo $str_err_report;
		// end (EP for product extra fields Contrib by minhmt DEVSOFTVN) ============

		// /////////////////////
		// or do product deletes
	} elseif ( $items[$filelayout['v_status']] == EP_DELETE_IT ) {
		// Get the ID
		$sql = "SELECT p.products_id as v_products_id    FROM ".TABLE_PRODUCTS." as p WHERE p.products_model = '" . $items[$filelayout['v_products_model']] . "'";
		$result = tep_db_query($sql);
		$row =  tep_db_fetch_array($result);
		if (tep_db_num_rows($result) == 1 ) {
			tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $row['v_products_id'] . "'");

			$product_categories_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $row['v_products_id'] . "'");
			$product_categories = tep_db_fetch_array($product_categories_query);

			if ($product_categories['total'] == '0') {
				tep_remove_product($row['v_products_id']);
			}

			if (USE_CACHE == 'true') {
				tep_reset_cache_block('categories');
				tep_reset_cache_block('also_purchased');
			}
			echo "Deleted product " . $items[$filelayout['v_products_model']] . " from the database<br />";

		} else {
			echo "Did not delete " . $items['v_products_model'] . " since it is not unique ";
		}

		// //////////////////////////////////
		// or do regular product processing
		// //////////////////////////////////
	} else {

		// /////////////////////////////////////////////////////////////////////
		//
		// Start: Support for other contributions in products table
		//
		// /////////////////////////////////////////////////////////////////////
		$ep_additional_select = '';

		if (EP_MORE_PICS_6_SUPPORT == true) {
			$ep_additional_select .= 'p.products_subimage1 as v_products_subimage1,p.products_subimage2 as v_products_subimage2,p.products_subimage3 as v_products_subimage3,p.products_subimage4 as v_products_subimage4,p.products_subimage5 as v_products_subimage5,p.products_subimage6 as v_products_subimage6,';
		}

		if (EP_UNKNOWN_ADD_IMAGES_SUPPORT == true) {
			$ep_additional_select .= 'p.products_mimage as v_products_mimage,p.products_bimage as v_products_bimage,p.products_subimage1 as v_products_subimage1,p.products_bsubimage1 as v_products_bsubimage1,p.products_subimage2 as v_products_subimage2,p.products_bsubimage2 as v_products_bsubimage2,p.products_subimage3 as v_products_subimage3,p.products_bsubimage3 as v_products_bsubimage3,';
		}

		foreach ($custom_fields[TABLE_PRODUCTS] as $key => $name) {
			$ep_additional_select .= 'p.' . $key . ' as v_' . $key . ',';
		}


		// /////////////////////////////////////////////////////////////////////
		// End: Support for other contributions in products table
		// /////////////////////////////////////////////////////////////////////


		// now do a query to get the record's current contents
		$sql = "SELECT
                    p.products_id as v_products_id,
                    p.products_model as v_products_model,
                    p.products_image as v_products_image,
                    $ep_additional_select
                    p.products_price as v_products_price,
                    p.products_weight as v_products_weight,
                    p.products_date_available as v_date_avail,
                    p.products_date_added as v_date_added,
                    p.products_tax_class_id as v_tax_class_id,
                    p.products_quantity as v_products_quantity,
                    p.manufacturers_id as v_manufacturers_id,
                    subc.categories_id as v_categories_id,
                    p.products_status as v_status_current
                FROM
                    ".TABLE_PRODUCTS." as p,
                    ".TABLE_CATEGORIES." as subc,
                    ".TABLE_PRODUCTS_TO_CATEGORIES." as ptoc
                WHERE
                    p.products_model = '" . $items[$filelayout['v_products_model']] . "' AND
                    p.products_id = ptoc.products_id AND
                    ptoc.categories_id = subc.categories_id
                LIMIT 1
            ";

                   $result = tep_db_query($sql);
                   $row =  tep_db_fetch_array($result);

                   // determine processing status based on dropdown choice on EP menu
                   if ((sizeof($row) > 1) && ($_POST['input_mode'] == "normal" || $_POST['input_mode'] == "update")) {
                   	$process_product = true;
                   } elseif ((sizeof($row) == 1) && ($_POST['input_mode'] == "normal" || $_POST['input_mode'] == "addnew")) {
                   	$process_product = true;
                   } else {
                   	$process_product = false;
                   }

                   if ($process_product == true) {

                   	while ($row){
                   		// OK, since we got a row, the item already exists.
                   		// Let's get all the data we need and fill in all the fields that need to be defaulted
                   		// to the current values for each language, get the description and set the vals
                   		foreach ($languages as $key => $lang){
                   			// products_name, products_description, products_url
                   			$sql2 = "SELECT *
                           FROM ".TABLE_PRODUCTS_DESCRIPTION."
                           WHERE
                               products_id = " . $row['v_products_id'] . " AND
                               language_id = '" . $lang['id'] . "'
                           LIMIT 1
                           ";
                   			$result2 = tep_db_query($sql2);
                   			$row2 =  tep_db_fetch_array($result2);
                   			// Need to report from ......_name_1 not ..._name_0
                   			$row['v_products_name_' . $lang['id']]         = $row2['products_name'];
                   			$row['v_products_description_' . $lang['id']]     = $row2['products_description'];
                   			$row['v_products_url_' . $lang['id']]         = $row2['products_url'];
                   			foreach ($custom_fields[TABLE_PRODUCTS_DESCRIPTION] as $key => $name) {
                   				$row['v_' . $key . '_' . $lang['id']]         = $row2[$key];
                   			}
                   			// header tags controller support
                   			if(isset($filelayout['v_products_head_title_tag_' . $lang['id'] ])){
                       $row['v_products_head_title_tag_' . $lang['id']]     = $row2['products_head_title_tag'];
                       $row['v_products_head_desc_tag_' . $lang['id']]     = $row2['products_head_desc_tag'];
                       $row['v_products_head_keywords_tag_' . $lang['id']]     = $row2['products_head_keywords_tag'];
                   			}
                   			// end: header tags controller support
                   		}

                   		// start with v_categories_id
                   		// Get the category description
                   		// set the appropriate variable name
                   		// if parent_id is not null, then follow it up.
                   		$thecategory_id = $row['v_categories_id'];
                   		for( $categorylevel=1; $categorylevel<=(EP_MAX_CATEGORIES+1); $categorylevel++){
                   			if ($thecategory_id){

                   				$sql3 = "SELECT parent_id,
					                categories_image
						     FROM ".TABLE_CATEGORIES."
						     WHERE    
							        categories_id = " . $thecategory_id . '';
                   				$result3 = tep_db_query($sql3);
                   				if ($row3 = tep_db_fetch_array($result3)) {
                   					$temprow['v_categories_image_' . $categorylevel] = $row3['categories_image'];
                   				}

                   				foreach ($languages as $key => $lang){
                   					$sql2 = "SELECT categories_name
							     FROM ".TABLE_CATEGORIES_DESCRIPTION."
							     WHERE    
								        categories_id = " . $thecategory_id . " AND
								        language_id = " . $lang['id'];
                   					$result2 = tep_db_query($sql2);
                   					if ($row2 =  tep_db_fetch_array($result2)) {
                   						$temprow['v_categories_name_' . $categorylevel . '_' . $lang['id']] = $row2['categories_name'];
                   					}
                   				}

                   				// now get the parent ID if there was one
                   				$theparent_id = $row3['parent_id'];
                   				if ($theparent_id != ''){
                   					// there was a parent ID, lets set thecategoryid to get the next level
                   					$thecategory_id = $theparent_id;
                   				} else {
                   					// we have found the top level category for this item,
                   					$thecategory_id = false;
                   				}
                   					
                   			} else {
                   				$temprow['v_categories_image_' . $categorylevel] = '';
                   				foreach ($languages as $key => $lang){
                   					$temprow['v_categories_name_' . $categorylevel . '_' . $lang['id']] = '';
                   					// Versione dei nomi categoria senza lang_id
                   					$temprow['v_categories_name_' . $categorylevel ] = '';
                   				}
                   			}
                   		}
                   		// temprow has the old style low to high level categories.
                   		$newlevel = 1;
                   		// let's turn them into high to low level categories
                   		for( $categorylevel=EP_MAX_CATEGORIES+1; $categorylevel>0; $categorylevel--){
                   			$found = false;
                   			if ($temprow['v_categories_image_' . $categorylevel] != ''){
                   				$row['v_categories_image_' . $newlevel] = $temprow['v_categories_image_' . $categorylevel];
                   				$found = true;
                   			}
                   			foreach ($languages as $key => $lang){
                   				if ($temprow['v_categories_name_' . $categorylevel . '_' . $lang['id']] != ''){
                   					$row['v_categories_name_' . $newlevel . '_' . $lang['id']] = $temprow['v_categories_name_' . $categorylevel . '_' . $lang['id']];
                   					// Versione dei nomi categoria senza lang_id
                   					$row['v_categories_name_' . $newlevel ] = $temprow['v_categories_name_' . $categorylevel ];
                   					$found = true;
                   				}
                   			}
                   			if ($found == true) {
                   				$newlevel++;
                   			}
                   		}


                   		// default the manufacturer
                   		if ($row['v_manufacturers_id'] != ''){
                   			$sql2 = "SELECT manufacturers_name
                       FROM ".TABLE_MANUFACTURERS."
                       WHERE
                       manufacturers_id = " . $row['v_manufacturers_id']
                   			;
                   			$result2 = tep_db_query($sql2);
                   			$row2 =  tep_db_fetch_array($result2);
                   			$row['v_manufacturers_name'] = $row2['manufacturers_name'];
                   		}


                   		//elari -
                   		//We check the value of tax class and title instead of the id
                   		//Then we add the tax to price if EP_PRICE_WITH_TAX is set to true
                   		$row_tax_multiplier = tep_get_tax_class_rate($row['v_tax_class_id']);
                   		$row['v_tax_class_title'] = tep_get_tax_class_title($row['v_tax_class_id']);
                   		if (EP_PRICE_WITH_TAX == true){
                   			$row['v_products_price'] = $row['v_products_price'] + round(($row['v_products_price'] * $row_tax_multiplier / 100), EP_PRECISION);
                   		}
                   		// now create the internal variables that will be used
                   		// the $$thisvar is on purpose: it creates a variable named what ever was in $thisvar and sets the value
                   		foreach ($default_these as $tkey => $thisvar){
                   			$$thisvar = $row[$thisvar];
                   		}

                   		$row =  tep_db_fetch_array($result);
                   	}

                   	// this is an important loop.  What it does is go thru all the fields in the incoming
                   	// file and set the internal vars. Internal vars not set here are either set in the
                   	// loop above for existing records, or not set at all (null values) the array values
                   	// are handled separatly, although they will set variables in this loop, we won't use them.
                   	foreach( $filelayout as $key => $value ){
                   		$$key = trim($items[ $value ]);
                   	}

                   	//elari... we get the tax_clas_id from the tax_title
                   	//on screen will still be displayed the tax_class_title instead of the id....
                   	if ( isset( $v_tax_class_title) ){
                   		$v_tax_class_id          = tep_get_tax_title_class_id($v_tax_class_title);
                   	}
                   	//we check the tax rate of this tax_class_id
                   	$row_tax_multiplier = tep_get_tax_class_rate($v_tax_class_id);

                   	//And we recalculate price without the included tax...
                   	//Since it seems display is made before, the displayed price will still include tax
                   	//This is same problem for the tax_clas_id that display tax_class_title
                   	if (EP_PRICE_WITH_TAX == true){
                   		$v_products_price = round( $v_products_price / (1 + ($row_tax_multiplier * .01)), EP_PRECISION);
                   	}

                   	// if they give us one category, they give us all categories. convert data structure to a multi-dim array
                   	unset ($v_categories_name); // default to not set.
                   	unset ($v_categories_image); // default to not set.
                   	foreach ($languages as $key => $lang){
                   		$baselang_id = $lang['id'];
                   		break;
                   	}
                   	if ( isset( $filelayout['v_categories_name_1_' . $baselang_id] ) ){
                   		$v_categories_name = array();
                   		$v_categories_image = array();
                   		$newlevel = 1;
                   		for( $categorylevel=EP_MAX_CATEGORIES; $categorylevel>0; $categorylevel--){
                   			$found = false;
                   			if ($items[$filelayout['v_categories_image_' . $categorylevel]] != '') {
                   				$v_categories_image[$newlevel] = $items[$filelayout['v_categories_image_' . $categorylevel]];
                   				$found = true;
                   			}
                   			foreach ($languages as $key => $lang){
                   				if ( $items[$filelayout['v_categories_name_' . $categorylevel . '_' . $lang['id']]] != ''){
                   					$v_categories_name[$newlevel][$lang['id']] = $items[$filelayout['v_categories_name_' . $categorylevel . '_' . $lang['id']]];
                   					$found = true;
                   				}
                   			}
                   			if ($found == true) {
                   				$newlevel++;
                   			}
                   		}
                   		while( $newlevel < EP_MAX_CATEGORIES+1){
                   			$v_categories_image[$newlevel] = ''; // default the remaining items to nothing
                   			foreach ($languages as $key => $lang){
                   				$v_categories_name[$newlevel][$lang['id']] = ''; // default the remaining items to nothing
                   			}
                   			$newlevel++;
                   		}
                   	}else if (isset($filelayout['v_categories_name_1'] ) ){
                   		$v_categories_name = array();
                   		$v_categories_image = array();
                   		$newlevel = 1;
                   		for( $categorylevel=EP_MAX_CATEGORIES; $categorylevel>0; $categorylevel--){
                   			$found = false;
                   			if ($items[$filelayout['v_categories_image_' . $categorylevel]] != '') {
                   				$v_categories_image[$newlevel] = $items[$filelayout['v_categories_image_' . $categorylevel]];
                   				$found = true;
                   			}
                  				if ( $items[$filelayout['v_categories_name_' . $categorylevel]] != ''){
                  					$v_categories_name[$newlevel] = $items[$filelayout['v_categories_name_' . $categorylevel]];
                  					$found = true;
                  				}
                   			if ($found == true) {
                   				$newlevel++;
                   			}
                   		}
                   		while( $newlevel < EP_MAX_CATEGORIES+1){
                   			$v_categories_image[$newlevel] = ''; // default the remaining items to nothing
                   			$v_categories_name[$newlevel] = ''; // default the remaining items to nothing
                   			$newlevel++;
                   		}
                   	}

                   	if (ltrim(rtrim($v_products_quantity)) == '') {
                   		$v_products_quantity = 1;
                   	}

                   	if (empty($v_date_avail)) {
                   		$v_date_avail = "'" . '0000-00-00 00:00:00' . "'";
                   	} else {
                   		$v_date_avail = "'" . date("Y-m-d H:i:s",strtotime($v_date_avail)) . "'";
                   	}

                   	if (empty($v_date_added)) {
                   		$v_date_added = "'" . date("Y-m-d H:i:s") . "'";
                   	} else {
                   		$v_date_added = "'" . date("Y-m-d H:i:s",strtotime($v_date_added)) . "'";
                   	}

                   	// default the stock if they spec'd it or if it's blank
                   	if (isset($v_status_current)){
                   		$v_db_status = strval($v_status_current); // default to current value
                   	} else {
                   		$v_db_status = '1'; // default to active
                   	}
                   	if (trim($v_status) == EP_TEXT_INACTIVE){
                   		// they told us to deactivate this item
                   		$v_db_status = '0';
                   	} elseif (trim($v_status) == EP_TEXT_ACTIVE) {
                   		$v_db_status = '1';
                   	}

                   	if (EP_INACTIVATE_ZERO_QUANTITIES == true && $v_products_quantity == 0) {
                   		// if they said that zero qty products should be deactivated, let's deactivate if the qty is zero
                   		$v_db_status = '0';
                   	}

                   	if ($v_manufacturer_id==''){
                   		$v_manufacturer_id="NULL";
                   	}

                   	if (trim($v_products_image)==''){
                   		$v_products_image = EP_DEFAULT_IMAGE_PRODUCT;
                   	}

                   	if (strlen($v_products_model) > EP_MODEL_NUMBER_SIZE ){
                   		echo "<font color='red'>" . strlen($v_products_model) . $v_products_model . "... ERROR! - Too many characters in the model number.<br />
                   12 is the maximum on a standard OSC install.<br />
                   Your maximum product_model length is set to ".EP_MODEL_NUMBER_SIZE.".<br />
                   You can either shorten your model numbers or increase the size of the<br />
                   \"products_model\" field of the \"products\" table in the database.<br />
                   Then change the setting at the top of the easypopulate.php file.</font>";
                   		die();
                   	}


                   	// OK, we need to convert the manufacturer's name into id's for the database
                   	if ( isset($v_manufacturers_name) && $v_manufacturers_name != '' ){
                   		$sql = "SELECT man.manufacturers_id
                   FROM ".TABLE_MANUFACTURERS." as man
                   WHERE
                       man.manufacturers_name = '" . tep_db_input($v_manufacturers_name) . "'";
                   		$result = tep_db_query($sql);
                   		$row =  tep_db_fetch_array($result);
                   		if ( $row != '' ){
                   			foreach( $row as $item ){
                       $v_manufacturer_id = $item;
                   			}
                   		} else {
                   			// to add, we need to put stuff in categories and categories_description
                   			$sql = "SELECT MAX( manufacturers_id) max FROM ".TABLE_MANUFACTURERS;
                   			$result = tep_db_query($sql);
                   			$row =  tep_db_fetch_array($result);
                   			$max_mfg_id = $row['max']+1;
                   			// default the id if there are no manufacturers yet
                   			if (!is_numeric($max_mfg_id) ){
                       $max_mfg_id=1;
                   			}

                   			// Uncomment this query if you have an older 2.2 codebase
                   			/*
                   			$sql = "INSERT INTO ".TABLE_MANUFACTURERS."(
                   			manufacturers_id,
                   			manufacturers_name,
                   			manufacturers_image
                   			) VALUES (
                   			$max_mfg_id,
                   			'$v_manufacturers_name',
                   			'".EP_DEFAULT_IMAGE_MANUFACTURER."'
                   			)";
                   			*/

                   			// Comment this query out if you have an older 2.2 codebase
                   			$sql = "INSERT INTO ".TABLE_MANUFACTURERS."(
                       manufacturers_id,
                       manufacturers_name,
                       manufacturers_image,
                       date_added,
                       last_modified
                       ) VALUES (
                       $max_mfg_id,
                       '".tep_db_input($v_manufacturers_name)."',
                       '".EP_DEFAULT_IMAGE_MANUFACTURER."',
                       '".date("Y-m-d H:i:s")."',
                       '".date("Y-m-d H:i:s")."'
                       )";
                       $result = tep_db_query($sql);
                       $v_manufacturer_id = $max_mfg_id;

                       $sql = "INSERT INTO ".TABLE_MANUFACTURERS_INFO."(
                       manufacturers_id,
                       manufacturers_url,
                       languages_id
                       ) VALUES (
                       $max_mfg_id,
                       '',
                       '".EP_DEFAULT_LANGUAGE_ID."'
                       )";
                       $result = tep_db_query($sql);
                   		}
                   	}

                   	// if the categories names are set then try to update them
                   	foreach ($languages as $key => $lang){
                   		$baselang_id = $lang['id'];
                   		break;
                   	}
                   	if ( isset( $filelayout['v_categories_name_1_' . $baselang_id] ) ){
                   		// start from the highest possible category and work our way down from the parent
                   		$v_categories_id = 0;
                   		$theparent_id = 0;
                   		for ( $categorylevel=EP_MAX_CATEGORIES+1; $categorylevel>0; $categorylevel-- ){
                   			//foreach ($languages as $key => $lang){
                   			$thiscategoryname = $v_categories_name[$categorylevel][$baselang_id];
                   			if ( $thiscategoryname != ''){
		                        // we found a category name in this field, look for database entry
		                        $sql = "SELECT cat.categories_id
		                            FROM ".TABLE_CATEGORIES." as cat, 
		                                 ".TABLE_CATEGORIES_DESCRIPTION." as des
		                            WHERE
		                                cat.categories_id = des.categories_id AND
		                                des.language_id = " . $lang['id'] . " AND
		                                cat.parent_id = " . $theparent_id . " AND
		                                des.categories_name like '" . tep_db_input($thiscategoryname) . "'";
		                        $result = tep_db_query($sql);
		                        $row =  tep_db_fetch_array($result);
		
		                        if ( $row != '' ){
		                        	// we have an existing category, update image and date
		                        	foreach( $row as $item ){
		                        		$thiscategoryid = $item;
		                        	}
		                        	$query = "UPDATE ".TABLE_CATEGORIES."
		                                      SET 
		                                        categories_image='".tep_db_input($v_categories_image[$categorylevel])."', 
		                                        last_modified = '".date("Y-m-d H:i:s")."'
		                                      WHERE 
		                                        categories_id = '".$row['categories_id']."'
		                                      LIMIT 1";
		
		                        	tep_db_query($query);
		                        } else {
		                        	// to add, we need to put stuff in categories and categories_description
		                        	$sql = "SELECT MAX( categories_id) max FROM ".TABLE_CATEGORIES;
		                        	$result = tep_db_query($sql);
		                        	$row =  tep_db_fetch_array($result);
		                        	$max_category_id = $row['max']+1;
		                        	if (!is_numeric($max_category_id) ){
		                        		$max_category_id=1;
		                        	}
		                        	$sql = "INSERT INTO ".TABLE_CATEGORIES." (
		                                        categories_id,
		                                        parent_id,
		                                        categories_image,
		                                        sort_order,
		                                        date_added,
		                                        last_modified
		                                   ) VALUES (
		                                   $max_category_id,
		                                   $theparent_id,
		                                        '".tep_db_input($v_categories_image[$categorylevel])."',
		                                        0,
		                                        '".date("Y-m-d H:i:s")."',
		                                        '".date("Y-m-d H:i:s")."'
		                                   )";
	                                   $result = tep_db_query($sql);
	
	                                   foreach ($languages as $key => $lang){
	                                   	$sql = "INSERT INTO ".TABLE_CATEGORIES_DESCRIPTION." (
	                                                categories_id,
	                                                language_id,
	                                                categories_name
	                                       ) VALUES (
	                                       $max_category_id,
	                                                '".$lang['id']."',
	                                                '".(!empty($v_categories_name[$categorylevel][$lang['id']])?tep_db_input($v_categories_name[$categorylevel][$lang['id']]):'')."'
	                                       )";
	                                       tep_db_query($sql);
	                                   }
	
	                                   $thiscategoryid = $max_category_id;
	                                   //PWS bof
	                                   if(isset($GLOBALS['pws_engine'])){
	                                   	$pws_categories=$pws_engine->getPlugin('pws_prices_categories','prices');
	                                   	if (!is_null($pws_categories))
	                                   	$pws_categories->adminInsertCategories($thiscategoryid);
	                                   }
	                                   //PWS eof
		                        }
                       // the current catid is the next level's parent
                       $theparent_id = $thiscategoryid;
                       $v_categories_id = $thiscategoryid; // keep setting this, we need the lowest level category ID later
                   			}
                   			// }
                   		}
                   	}else if ( isset( $filelayout['v_categories_name_1'] ) ){
                   		// start from the highest possible category and work our way down from the parent
                   		$v_categories_id = 0;
                   		$theparent_id = 0;
                   		for ( $categorylevel=EP_MAX_CATEGORIES+1; $categorylevel>0; $categorylevel-- ){
                   			//foreach ($languages as $key => $lang){
                   			$thiscategoryname = $v_categories_name[$categorylevel];
                   			if ( $thiscategoryname != ''){
		                        // we found a category name in this field, look for database entry
		                        $sql = "SELECT cat.categories_id
		                            FROM ".TABLE_CATEGORIES." as cat, 
		                                 ".TABLE_CATEGORIES_DESCRIPTION." as des
		                            WHERE
		                                cat.categories_id = des.categories_id AND
		                                des.language_id = " . $baselang_id . " AND
		                                cat.parent_id = " . $theparent_id . " AND
		                                des.categories_name like '" . tep_db_input($thiscategoryname) . "'";
		                        $result = tep_db_query($sql);
		                        $row =  tep_db_fetch_array($result);
		
		                        if ( $row != '' ){
		                        	// we have an existing category, update image and date
		                        	foreach( $row as $item ){
		                        		$thiscategoryid = $item;
		                        	}
		                        	$query = "UPDATE ".TABLE_CATEGORIES."
		                                      SET 
		                                        categories_image='".tep_db_input($v_categories_image[$categorylevel])."', 
		                                        last_modified = '".date("Y-m-d H:i:s")."'
		                                      WHERE 
		                                        categories_id = '".$row['categories_id']."'
		                                      LIMIT 1";
		
		                        	tep_db_query($query);
		                        } else {
		                        	// to add, we need to put stuff in categories and categories_description
		                        	$sql = "SELECT MAX( categories_id) max FROM ".TABLE_CATEGORIES;
		                        	$result = tep_db_query($sql);
		                        	$row =  tep_db_fetch_array($result);
		                        	$max_category_id = $row['max']+1;
		                        	if (!is_numeric($max_category_id) ){
		                        		$max_category_id=1;
		                        	}
		                        	$sql = "INSERT INTO ".TABLE_CATEGORIES." (
		                                        categories_id,
		                                        parent_id,
		                                        categories_image,
		                                        sort_order,
		                                        date_added,
		                                        last_modified
		                                   ) VALUES (
		                                   $max_category_id,
		                                   $theparent_id,
		                                        '".tep_db_input($v_categories_image[$categorylevel])."',
		                                        0,
		                                        '".date("Y-m-d H:i:s")."',
		                                        '".date("Y-m-d H:i:s")."'
		                                   )";
	                                   $result = tep_db_query($sql);
	
	                                   foreach ($languages as $key => $lang){
	                                   	$sql = "INSERT INTO ".TABLE_CATEGORIES_DESCRIPTION." (
	                                                categories_id,
	                                                language_id,
	                                                categories_name
	                                       ) VALUES (
	                                       $max_category_id,
	                                                '".$lang['id']."',
	                                                '".(!empty($v_categories_name[$categorylevel])?tep_db_input($v_categories_name[$categorylevel]):'')."'
	                                       )";
	                                       tep_db_query($sql);
	                                   }
	
	                                   $thiscategoryid = $max_category_id;
	                                   //PWS bof
	                                   if(isset($GLOBALS['pws_engine'])){
	                                   	$pws_categories=$pws_engine->getPlugin('pws_prices_categories','prices');
	                                   	if (!is_null($pws_categories))
	                                   	$pws_categories->adminInsertCategories($thiscategoryid);
	                                   }
	                                   //PWS eof
		                        }
                       // the current catid is the next level's parent
                       $theparent_id = $thiscategoryid;
                       $v_categories_id = $thiscategoryid; // keep setting this, we need the lowest level category ID later
                   			}
                   			// }
                   		}
                   		
                   	}


                   	if ($v_products_model != "") {
                   		//   products_model exists!
                   		foreach ($items as $tkey => $item) {
                   			print_el($item);
                   		}

                   		// process the PRODUCTS table
                   		$result = tep_db_query("SELECT products_id FROM ".TABLE_PRODUCTS." WHERE (products_model = '". $v_products_model . "')");

                   		// First we check to see if this is a product in the current db.
                   		if (tep_db_num_rows($result) == 0)  {

                   			//   insert into products
                   			echo "<font color='green'> !New Product!</font><br />";

                   			// /////////////////////////////////////////////////////////////////////
                   			//
                   			// Start: Support for other contributions
                   			//
                   			// /////////////////////////////////////////////////////////////////////
                   			$ep_additional_fields = '';
                   			$ep_additional_data = '';

                   			foreach ($custom_fields[TABLE_PRODUCTS] as $key => $name) {
                   				$ep_additional_fields .= $key . ',';
                   			}

                   			foreach ($custom_fields[TABLE_PRODUCTS] as $key => $name) {
                   				$tmp_var = 'v_' . $key;
                   				$ep_additional_data .= "'" . $$tmp_var . "',";
                   			}

                   			if (EP_MORE_PICS_6_SUPPORT == true) {
                   				$ep_additional_fields .= 'products_subimage1,products_subimage2,products_subimage3,products_subimage4,products_subimage5,products_subimage6,';
                   				$ep_additional_data .= "'$v_products_subimage1','$v_products_subimage2','$v_products_subimage3','$v_products_subimage4','$v_products_subimage5','$v_products_subimage6',";
                   			}

                   			if (EP_UNKNOWN_ADD_IMAGES_SUPPORT == true) {
                   				$ep_additional_fields .= 'products_mimage,products_bimage,products_subimage1,products_bsubimage1,products_subimage2,products_bsubimage2,products_subimage3,products_bsubimage3,';
                   				$ep_additional_data .= "'$v_products_mimage','$v_products_bimage','$v_products_subimage1','$v_products_bsubimage1','$v_products_subimage2','$v_products_bsubimage2','$v_products_subimage3','$v_products_bsubimage3',";
                   			}
                   			// /////////////////////////////////////////////////////////////////////
                   			// End: Support for other contributions
                   			// /////////////////////////////////////////////////////////////////////

                   			$query = "INSERT INTO ".TABLE_PRODUCTS." (
                               products_image,
                               $ep_additional_fields
                               products_model,
                               products_price,
                               products_status,
                               products_last_modified,
                               products_date_added,
                               products_date_available,
                               products_tax_class_id,
                               products_weight,
                               products_quantity,
                               manufacturers_id )
                             VALUES (
                               ".(!empty($v_products_image)?"'".$v_products_image."'":'NULL').",
                               $ep_additional_data
                               '$v_products_model',
                               '$v_products_price',
                               '$v_db_status',
                               '".date("Y-m-d H:i:s")."',
                               ".$v_date_added.",
                               ".$v_date_avail.",
                               '$v_tax_class_id',
                               '$v_products_weight',
                               '$v_products_quantity',
                               ".(!empty($v_manufacturer_id)?$v_manufacturer_id:'NULL').")
                               ";
                               $result = tep_db_query($query);

                               $v_products_id = tep_db_insert_id();

                   		} else {

                   			// existing product(s), get the id from the query
                   			// and update the product data
                   			while ($row = tep_db_fetch_array($result)) {

                   				$v_products_id = $row['products_id'];
                   				echo "<font color='black'> Updated</font><br />";

                   				// /////////////////////////////////////////////////////////////////////
                   				//
                   				// Start: Support for other contributions
                   				//
                   				// /////////////////////////////////////////////////////////////////////
                   				$ep_additional_updates = '';

                   				foreach ($custom_fields[TABLE_PRODUCTS] as $key => $name) {
                   					$tmp_var = 'v_' . $key;
                   					$ep_additional_updates .= $key . "='" . $$tmp_var . "',";
                   				}

                   				if (EP_MORE_PICS_6_SUPPORT == true) {
                   					$ep_additional_updates .= "products_subimage1='$v_products_subimage1',products_subimage2='$v_products_subimage2',products_subimage3='$v_products_subimage3',products_subimage4='$v_products_subimage4',products_subimage5='$v_products_subimage5',products_subimage6='$v_products_subimage6',";
                   				}

                   				if (EP_UNKNOWN_ADD_IMAGES_SUPPORT == true) {
                   					$ep_additional_updates .= "products_mimage='$v_products_mimage',products_bimage='$v_products_bimage',products_subimage1='$v_products_subimage1',products_bsubimage1='$v_products_bsubimage1',products_subimage2='$v_products_subimage2',products_bsubimage2='$v_products_bsubimage2',products_subimage3='$v_products_subimage3',products_bsubimage3='$v_products_bsubimage3',";
                   				}
                   				// /////////////////////////////////////////////////////////////////////
                   				// End: Support for other contributions
                   				// /////////////////////////////////////////////////////////////////////


                   				$query = "UPDATE ".TABLE_PRODUCTS."
                             SET
                               products_price='$v_products_price', 
                               products_image=".(!empty($v_products_image)?"'".$v_products_image."'":'NULL').", 
                               $ep_additional_updates
                               products_weight='$v_products_weight', 
                               products_tax_class_id='$v_tax_class_id', 
                               products_date_available=".$v_date_avail.", 
                               products_date_added=".$v_date_added.", 
                               products_last_modified='".date("Y-m-d H:i:s")."', 
                               products_quantity = $v_products_quantity, 
                               manufacturers_id = ".(!empty($v_manufacturer_id)?$v_manufacturer_id:'NULL').", 
                               products_status = $v_db_status
                             WHERE
                               (products_id = $v_products_id)
                             LIMIT 1";

                               tep_db_query($query);
                   			}
                   		}


                   		// process the PRODUCTS_DESCRIPTION table
                   		foreach ($languages as $tkey => $lang){

                   			$doit = false;
                   			foreach ($custom_fields[TABLE_PRODUCTS_DESCRIPTION] as $key => $name) {
                   				if (isset($filelayout['v_' . $key . '_'.$lang['id']])) { $doit = true; }
                   			}

                   			if ( isset($filelayout['v_products_name_'.$lang['id']]) || isset($filelayout['v_products_description_'.$lang['id']]) || isset($filelayout['v_products_url_'.$lang['id']]) || isset($filelayout['v_products_head_title_tag_'.$lang['id']]) || $doit == true ) {

		                        $sql = "SELECT * FROM ".TABLE_PRODUCTS_DESCRIPTION." WHERE
		                                products_id = $v_products_id AND
		                                language_id = " . $lang['id'];
		                        $result = tep_db_query($sql);
		
		                        $products_var = 'v_products_name_'.$lang['id'];
		                        $description_var = 'v_products_description_'.$lang['id'];
		                        $url_var = 'v_products_url_'.$lang['id'];
		
		                        // /////////////////////////////////////////////////////////////////////
		                        //
		                        // Start: Support for other contributions
		                        //
		                        // /////////////////////////////////////////////////////////////////////
		                        $ep_additional_updates = '';
		                        $ep_additional_fields = '';
		                        $ep_additional_data = '';
		
		                        foreach ($custom_fields[TABLE_PRODUCTS_DESCRIPTION] as $key => $name) {
		                        	$tmp_var = 'v_' . $key . '_' . $lang['id'];
		                        	$ep_additional_updates .= $key . " = '" . tep_db_input($$tmp_var) ."',";
		                        	$ep_additional_fields .= $key . ",";
		                        	$ep_additional_data .= "'". tep_db_input($$tmp_var) . "',";
		                        }
		
		                        // header tags controller support
		                        if (isset($filelayout['v_products_head_title_tag_'.$lang['id']])){
		                        	$head_title_tag_var = 'v_products_head_title_tag_'.$lang['id'];
		                        	$head_desc_tag_var = 'v_products_head_desc_tag_'.$lang['id'];
		                        	$head_keywords_tag_var = 'v_products_head_keywords_tag_'.$lang['id'];
		
		                        	$ep_additional_updates .= "products_head_title_tag = '" . tep_db_input($$head_title_tag_var) ."', products_head_desc_tag = '" . tep_db_input($$head_desc_tag_var) ."', products_head_keywords_tag = '" . tep_db_input($$head_keywords_tag_var) ."',";
		                        	$ep_additional_fields .= "products_head_title_tag,products_head_desc_tag,products_head_keywords_tag,";
		                        	$ep_additional_data .= "'". tep_db_input($$head_title_tag_var) . "','". tep_db_input($$head_desc_tag_var) . "','". tep_db_input($$head_keywords_tag_var) . "',";
		                        }
		                        // end: header tags controller support
		
		                        // /////////////////////////////////////////////////////////////////////
		                        // End: Support for other contributions
		                        // /////////////////////////////////////////////////////////////////////
		
		
		                        // existing product?
		                        if (tep_db_num_rows($result) > 0) {
		                        	// already in the description, let's just update it
		                        	$sql =
		                                "UPDATE ".TABLE_PRODUCTS_DESCRIPTION." 
		                                 SET
		                                    products_name='" . tep_db_input($$products_var) . "',
		                                    products_description='" . tep_db_input($$description_var) . "',
		                                    $ep_additional_updates
		                                    products_url='" . $$url_var . "'
		                                 WHERE
		                                    products_id = '$v_products_id' AND
		                                    language_id = '".$lang['id']."'
		                                 LIMIT 1";
		                                    $result = tep_db_query($sql);
		
		                        } else {
		
		                        	// nope, this is a new product description
		                        	$result = tep_db_query($sql);
		                        	$sql =
		                                "INSERT INTO ".TABLE_PRODUCTS_DESCRIPTION."
		                                    ( products_id,
		                                      language_id,
		                                      products_name,
		                                      products_description,
			                                      $ep_additional_fields
		                                      products_url
		                                    )
		                                 VALUES (
		                                        '" . $v_products_id . "',
		                                        " . $lang['id'] . ",
		                                        '". tep_db_input($$products_var) . "',
		                                        '". tep_db_input($$description_var) . "',
		                                        $ep_additional_data
		                                        '". $$url_var . "'
		                                        )";
		                                        $result = tep_db_query($sql);
		                        }
                   			}
                   		}



                   		if (isset($v_categories_id)){
                   			//find out if this product is listed in the category given
                   			$result_incategory = tep_db_query('SELECT
                               '.TABLE_PRODUCTS_TO_CATEGORIES.'.products_id,
                               '.TABLE_PRODUCTS_TO_CATEGORIES.'.categories_id
                               FROM
                                   '.TABLE_PRODUCTS_TO_CATEGORIES.'
                               WHERE
                               '.TABLE_PRODUCTS_TO_CATEGORIES.'.products_id='.$v_products_id.' AND
                               '.TABLE_PRODUCTS_TO_CATEGORIES.'.categories_id='.$v_categories_id);

                   			if (tep_db_num_rows($result_incategory) == 0) {
                       // nope, this is a new category for this product
                       $res1 = tep_db_query('INSERT INTO '.TABLE_PRODUCTS_TO_CATEGORIES.' (products_id, categories_id)
                                             VALUES ("' . $v_products_id . '", "' . $v_categories_id . '")');
                   			} else {
                       // already in this category, nothing to do!
                   			}
                   		}
                   		//PWS bof
                   		if (isset($GLOBALS['pws_prices'])){
                   			global $pws_prices;
                   			$pws_prices->adminNewProductSetDefault($v_products_id);
                   		}
                   		//PWS eof

                   		// for the separate prices per customer (SPPC) module
                   		$ll=1;
                   		if (isset($v_customer_discount_1)){

                   			if (($v_customer_group_id_1 == '') AND ($v_customer_discount_1 != ''))  {
                       echo "<font color=red>ERROR - v_customer_group_id and v_customer_discount must occur in pairs</font>";
                       die();
                   			}
                   			// they spec'd some prices, so clear all existing entries
                   			$result = tep_db_query('
                               DELETE
                               FROM
                                   '.TABLE_PRODUCTS_GROUPS.'
                               WHERE
                                   products_id = ' . $v_products_id
                   			);
                   			// and insert the new record
                   			if ($v_customer_discount_1 != ''){
                       $result = tep_db_query('
                                   INSERT INTO
                                       '.TABLE_PRODUCTS_GROUPS.'
                                   VALUES
                                   (
                                       ' . $v_customer_group_id_1 . ',
                                       ' . $v_customer_discount_1 . ',
                                       ' . $v_products_id . '
                                       )'
                                       );
                   			}
                   			if ($v_customer_discount_2 != ''){
                       $result = tep_db_query('
                                   INSERT INTO
                                       '.TABLE_PRODUCTS_GROUPS.'
                                   VALUES
                                   (
                                       ' . $v_customer_group_id_2 . ',
                                       ' . $v_customer_discount_2 . ',
                                       ' . $v_products_id . '
                                       )'
                                       );
                   			}
                   			if ($v_customer_discount_3 != ''){
                       $result = tep_db_query('
                                   INSERT INTO
                                       '.TABLE_PRODUCTS_GROUPS.'
                                   VALUES
                                   (
                                       ' . $v_customer_group_id_3 . ',
                                       ' . $v_customer_discount_3 . ',
                                       ' . $v_products_id . '
                                       )'
                                       );
                   			}
                   			if ($v_customer_discount_4 != ''){
                       $result = tep_db_query('
                                   INSERT INTO
                                       '.TABLE_PRODUCTS_GROUPS.'
                                   VALUES
                                   (
                                       ' . $v_customer_group_id_4 . ',
                                       ' . $v_customer_discount_4 . ',
                                       ' . $v_products_id . '
                                       )'
                                       );
                   			}

                   		}
                   		// end: separate prices per customer (SPPC) module


                   		// VJ product attribs begin
                   		if (isset($v_attribute_options_id_1)){
                   			$attribute_rows = 1; // master row count

                   			// product options count
                   			$attribute_options_count = 1;
                   			$v_attribute_options_id_var = 'v_attribute_options_id_' . $attribute_options_count;

                   			while (isset($$v_attribute_options_id_var) && !empty($$v_attribute_options_id_var)) {
                       // remove product attribute options linked to this product before proceeding further
                       // this is useful for removing attributes linked to a product
                       $attributes_clean_query = "delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$v_products_id . "' and options_id = '" . (int)$$v_attribute_options_id_var . "'";

                       tep_db_query($attributes_clean_query);

                       $attribute_options_query = "select products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$$v_attribute_options_id_var . "'";

                       $attribute_options_values = tep_db_query($attribute_options_query);

                       // option table update begin
                       if ($attribute_rows == 1) {
                       	// insert into options table if no option exists
                       	if (tep_db_num_rows($attribute_options_values) <= 0) {
                       		for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
                       			$lid = $languages[$i]['id'];

                       			$v_attribute_options_name_var = 'v_attribute_options_name_' . $attribute_options_count . '_' . $lid;

                       			if (isset($$v_attribute_options_name_var)) {
                       				$attribute_options_insert_query = "insert into " . TABLE_PRODUCTS_OPTIONS . " (products_options_id, language_id, products_options_name) values ('" . (int)$$v_attribute_options_id_var . "', '" . (int)$lid . "', '" . $$v_attribute_options_name_var . "')";

                       				$attribute_options_insert = tep_db_query($attribute_options_insert_query);
                       			}
                       		}
                       	} else { // update options table, if options already exists
                       		for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
                       			$lid = $languages[$i]['id'];

                       			$v_attribute_options_name_var = 'v_attribute_options_name_' . $attribute_options_count . '_' . $lid;

                       			if (isset($$v_attribute_options_name_var)) {
                       				$attribute_options_update_lang_query = "select products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$$v_attribute_options_id_var . "' and language_id ='" . (int)$lid . "'";

                       				$attribute_options_update_lang_values = tep_db_query($attribute_options_update_lang_query);

                       				// if option name doesn't exist for particular language, insert value
                       				if (tep_db_num_rows($attribute_options_update_lang_values) <= 0) {
                       					$attribute_options_lang_insert_query = "insert into " . TABLE_PRODUCTS_OPTIONS . " (products_options_id, language_id, products_options_name) values ('" . (int)$$v_attribute_options_id_var . "', '" . (int)$lid . "', '" . $$v_attribute_options_name_var . "')";

                       					$attribute_options_lang_insert = tep_db_query($attribute_options_lang_insert_query);
                       				} else { // if option name exists for particular language, update table
                       					$attribute_options_update_query = "update " . TABLE_PRODUCTS_OPTIONS . " set products_options_name = '" . $$v_attribute_options_name_var . "' where products_options_id ='" . (int)$$v_attribute_options_id_var . "' and language_id = '" . (int)$lid . "'";

                       					$attribute_options_update = tep_db_query($attribute_options_update_query);
                       				}
                       			}
                       		}
                       	}
                       }
                       // option table update end

                       // product option values count
                       $attribute_values_count = 1;
                       $v_attribute_values_id_var = 'v_attribute_values_id_' . $attribute_options_count . '_' . $attribute_values_count;

                       while (isset($$v_attribute_values_id_var) && !empty($$v_attribute_values_id_var)) {
                       	$attribute_values_query = "select products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$$v_attribute_values_id_var . "'";

                       	$attribute_values_values = tep_db_query($attribute_values_query);

                       	// options_values table update begin
                       	if ($attribute_rows == 1) {
                       		// insert into options_values table if no option exists
                       		if (tep_db_num_rows($attribute_values_values) <= 0) {
                       			for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
                       				$lid = $languages[$i]['id'];

                       				$v_attribute_values_name_var = 'v_attribute_values_name_' . $attribute_options_count . '_' . $attribute_values_count . '_' . $lid;

                       				if (isset($$v_attribute_values_name_var)) {
                       					$attribute_values_insert_query = "insert into " . TABLE_PRODUCTS_OPTIONS_VALUES . " (products_options_values_id, language_id, products_options_values_name) values ('" . (int)$$v_attribute_values_id_var . "', '" . (int)$lid . "', '" . tep_db_input($$v_attribute_values_name_var) . "')";

                       					$attribute_values_insert = tep_db_query($attribute_values_insert_query);
                       				}
                       			}


                       			// insert values to pov2po table
                       			$attribute_values_pov2po_query = "insert into " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " (products_options_id, products_options_values_id) values ('" . (int)$$v_attribute_options_id_var . "', '" . (int)$$v_attribute_values_id_var . "')";

                       			$attribute_values_pov2po = tep_db_query($attribute_values_pov2po_query);
                       		} else { // update options table, if options already exists
                       			for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
                       				$lid = $languages[$i]['id'];

                       				$v_attribute_values_name_var = 'v_attribute_values_name_' . $attribute_options_count . '_' . $attribute_values_count . '_' . $lid;

                       				if (isset($$v_attribute_values_name_var)) {
                       					$attribute_values_update_lang_query = "select products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$$v_attribute_values_id_var . "' and language_id ='" . (int)$lid . "'";

                       					$attribute_values_update_lang_values = tep_db_query($attribute_values_update_lang_query);

                       					// if options_values name doesn't exist for particular language, insert value
                       					if (tep_db_num_rows($attribute_values_update_lang_values) <= 0) {
                       						$attribute_values_lang_insert_query = "insert into " . TABLE_PRODUCTS_OPTIONS_VALUES . " (products_options_values_id, language_id, products_options_values_name) values ('" . (int)$$v_attribute_values_id_var . "', '" . (int)$lid . "', '" . tep_db_input($$v_attribute_values_name_var) . "')";

                       						$attribute_values_lang_insert = tep_db_query($attribute_values_lang_insert_query);
                       					} else { // if options_values name exists for particular language, update table
                       						$attribute_values_update_query = "update " . TABLE_PRODUCTS_OPTIONS_VALUES . " set products_options_values_name = '" . tep_db_input($$v_attribute_values_name_var) . "' where products_options_values_id ='" . (int)$$v_attribute_values_id_var . "' and language_id = '" . (int)$lid . "'";

                       						$attribute_values_update = tep_db_query($attribute_values_update_query);
                       					}
                       				}
                       			}
                       		}
                       	}
                       	// options_values table update end

                       	// options_values price update begin
                       	$v_attribute_values_price_var = 'v_attribute_values_price_' . $attribute_options_count . '_' . $attribute_values_count;

                       	if (isset($$v_attribute_values_price_var) && ($$v_attribute_values_price_var != '')) {
                       		$attribute_prices_query = "select options_values_price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$v_products_id . "' and options_id ='" . (int)$$v_attribute_options_id_var . "' and options_values_id = '" . (int)$$v_attribute_values_id_var . "'";

                       		$attribute_prices_values = tep_db_query($attribute_prices_query);

                       		$attribute_values_price_prefix = ($$v_attribute_values_price_var < 0) ? '-' : '+';
                       		// if negative, remove the negative sign for storing since the prefix is stored in another field.
                       		if ( $$v_attribute_values_price_var < 0 ) $$v_attribute_values_price_var = strval(-((int)$$v_attribute_values_price_var));

                       		// options_values_prices table update begin
                       		// insert into options_values_prices table if no price exists
                       		if (tep_db_num_rows($attribute_prices_values) <= 0) {
                       			$attribute_prices_insert_query = "insert into " . TABLE_PRODUCTS_ATTRIBUTES . " (products_id, options_id, options_values_id, options_values_price, price_prefix) values ('" . (int)$v_products_id . "', '" . (int)$$v_attribute_options_id_var . "', '" . (int)$$v_attribute_values_id_var . "', '" . (float)$$v_attribute_values_price_var . "', '" . $attribute_values_price_prefix . "')";

                       			$attribute_prices_insert = tep_db_query($attribute_prices_insert_query);
                       		} else { // update options table, if options already exists
                       			$attribute_prices_update_query = "update " . TABLE_PRODUCTS_ATTRIBUTES . " set options_values_price = '" . $$v_attribute_values_price_var . "', price_prefix = '" . $attribute_values_price_prefix . "' where products_id = '" . (int)$v_products_id . "' and options_id = '" . (int)$$v_attribute_options_id_var . "' and options_values_id ='" . (int)$$v_attribute_values_id_var . "'";

                       			$attribute_prices_update = tep_db_query($attribute_prices_update_query);
                       		}
                       	}
                       	// options_values price update end

                       	//////// attributes stock add start
                       	$v_attribute_values_stock_var = 'v_attribute_values_stock_' . $attribute_options_count . '_' . $attribute_values_count;

                       	if (isset($$v_attribute_values_stock_var) && ($$v_attribute_values_stock_var != '')) {

                       		$stock_attributes = $$v_attribute_options_id_var.'-'.$$v_attribute_values_id_var;
                       		$attribute_stock_query = tep_db_query("select products_stock_quantity from " . TABLE_PRODUCTS_STOCK . " where products_id = '" . (int)$v_products_id . "' and products_stock_attributes ='" . $stock_attributes . "'");

                       		// insert into products_stock_quantity table if no stock exists
                       		if (tep_db_num_rows($attribute_stock_query) <= 0) {
                       			$attribute_stock_insert_query =tep_db_query("insert into " . TABLE_PRODUCTS_STOCK . " (products_id, products_stock_attributes, products_stock_quantity) values ('" . (int)$v_products_id . "', '" . $stock_attributes . "', '" . (int)$$v_attribute_values_stock_var . "')");

                       		} else { // update options table, if options already exists
                       			$attribute_stock_insert_query = tep_db_query("update " . TABLE_PRODUCTS_STOCK. " set products_stock_quantity = '" . (int)$$v_attribute_values_stock_var . "' where products_id = '" . (int)$v_products_id . "' and products_stock_attributes = '" . $stock_attributes . "'");

                       			// turn on stock tracking on products_options table
                       			$stock_tracking_query = tep_db_query("update " . TABLE_PRODUCTS_OPTIONS . " set products_options_track_stock = '1' where products_options_id = '" . (int)$$v_attribute_options_id_var . "'");

                       		}
                       	}
                       	//////// attributes stock add end

                       	$attribute_values_count++;
                       	$v_attribute_values_id_var = 'v_attribute_values_id_' . $attribute_options_count . '_' . $attribute_values_count;
                       }

                       $attribute_options_count++;
                       $v_attribute_options_id_var = 'v_attribute_options_id_' . $attribute_options_count;
                   			}

                   			$attribute_rows++;
                   		}
                   		// VJ product attribs end

                   	} else {
                   		// this record was missing the product_model
                   		echo "<p class=smallText>No products_model field in record. This line was not imported: ";
                   		foreach ($items as $tkey => $item) {
                   			print_el($item);
                   		}
                   		echo "<br /><br /></p>";
                   	}
                   	// end of row insertion code

                   }

                    // EP for product extra fields Contrib by minhmaster DEVSOFTVN ==========
	}
	// end (EP for product extra fields Contrib by minhmt DEVSOFTVN) ============

}
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
//
// ep_format_excel_decimal()
//
//   Formatta il prezzo da/per Excel
//
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
function ep_format_excel_decimal($price,$from_excel=false){
	$price=strval($price);
	if ($from_excel)
		return str_replace(',','.',$price);
	else
		return str_replace('.',',',$price);
}
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
