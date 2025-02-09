<?php
/*
 $Id: quick_updates.php for SPPC, v 1.0 2005/11/27 $
 based on version 2.6 of quick_updates.php

 osCommerce, Open Source E-Commerce Solutions
 http://www.oscommerce.com

 Based on the original script contributed by Burt (burt@xwww.co.uk)
 and by Henri Bredehoeft (hrb@nermica.net)

 This version was contributed by Mathieu (contact@mathieueylert.com)

 (http://www.oscommerce-fr.info/forums)

 Copyright (c) 2005 osCommerce

 Released under the GNU General Public License
 */

require('includes/application_top.php');
require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();

if (isset($pws_engine) && is_object($pws_engine)){
	$plugin_purchase_installed=$pws_engine->isInstalledPlugin('pws_prices_purchase_price');
	$plugin_tax_raee_installed=$pws_engine->isInstalledPlugin('pws_prices_tax_raee');
	$plugin_clothing_installed=$pws_engine->isInstalledPlugin('pws_clothing');
	if ($plugin_tax_raee_installed){
		$plugin_tax_raee=$pws_engine->getPlugin('pws_prices_tax_raee','prices');
	}
	if ($plugin_clothing_installed){
		$plugin_clothing=$pws_engine->getPlugin('pws_clothing','application');
	}
	$plugin_cgroups=&$GLOBALS['pws_engine']->getPlugin('pws_prices_customers_groups','prices');
	if (is_null($plugin_cgroups)){
		$pws_engine->reportWarning(sprintf(PWS_HOW_TO_INSTALL_PRODUCTS_GROUPS,tep_href_link(FILENAME_MODULES,'set=prices&plugin_code=pws_prices_customers_groups')));
	}else{

	}
}else{
	$plugin_purchase_installed=defined('PWS_PRICES_PURCHASE_PRICE_DEFAULT_COMMISSION');
	$plugin_tax_raee_installed=isset($pws_prices_tax_raee);
	$plugin_clothing_installed=isset($pws_clothing);
	if ($plugin_tax_raee_installed){
		$plugin_tax_raee=$pws_prices_tax_raee;
	}
	if ($plugin_clothing_installed){
		$plugin_clothing=$pws_clothing;
	}
	$plugin_cgroups=NULL;
}

  if (file_exists(DIR_FS_CATALOG_MODULES . 'product_makeoffer.php'))
      {
      	$plugin_makeoffer_installed = true; 
      }

$plugin_shopwindow_installed=defined('SHOPWINDOW_ENABLED') && SHOPWINDOW_ENABLED=='true';
if (!defined('DISPLAY_SHOPWINDOW')){
	define('DISPLAY_SHOPWINDOW',$plugin_shopwindow_installed ? 'true' : 'false');
}


($row_by_page) ? define('MAX_DISPLAY_ROW_BY_PAGE' , $row_by_page ) : $row_by_page = MAX_DISPLAY_SEARCH_RESULTS; define('MAX_DISPLAY_ROW_BY_PAGE' , MAX_DISPLAY_SEARCH_RESULTS );

//// Tax Row
$tax_class_array = array(array('id' => '0', 'text' => NO_TAX_TEXT));
$tax_class_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
while ($tax_class = tep_db_fetch_array($tax_class_query)) {
	$tax_class_array[] = array('id' => $tax_class['tax_class_id'],
                                 'text' => $tax_class['tax_class_title']);
}

////Info Row pour le champ fabriquant
$manufacturers_array = array(array('id' => '0', 'text' => NO_MANUFACTURER));
$manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
	$manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'],
                'text' => $manufacturers['manufacturers_name']);
}


// Update database
// @TODO: Finire le operazioni multiple sui prodotti (eliminazione/copia/spostamento)
$action=isset($_REQUEST['multi_action']) && $_REQUEST['multi_action']!='' ? $_REQUEST['multi_action']:$_REQUEST['action'];
$to_category_id=isset($_REQUEST['to_category_id'])?$_REQUEST['to_category_id']:'';
$product_selected=isset($_REQUEST['product_selected']) ? $_REQUEST['product_selected'] : array();
switch ($action) {
	case 'abort':
		$action='';
		break;
	// Aggiornamento dei prodotti
	case 'update' :
		update_products();
		tep_reset_turbo_cache();
		break;
	// Anteprima nuovi prezzi
	case 'calcul' :
		if ($HTTP_POST_VARS['spec_price']) $preview_global_price = 'true';
		break;
	// Anteprima ricarico prezzi
	case 'markup' :
		if ($HTTP_POST_VARS['markup']) $preview_global_price = 'true';
		break;		
	// Anteprima RAEE
	case 'raee' :
		$preview_raee_class = 'true';
		$flag_spec_raee='true';
		break;
	// Richiesta di conferma per eliminazione multipla
	case 'multi_delete':
		$num_products_2_delete=sizeof($product_selected);
		break;
	case 'multi_activate':
		$num_products_2_activate=sizeof($product_selected);
		break;	
	// Eliminazione multipla
	case 'multi_delete_confirm':
		//print_r($_REQUEST);exit;
		//if (USE_CACHE=='true'){ 
		if (false){
			$messageStack->add_session(TEXT_WARNING_CACHE_TURNED_OFF,'warning');
			tep_db_query("update ".TABLE_CONFIGURATION." set configuration_value='false' where configuration_key='USE_CACHE'");
			reset($product_selected);
			$params_pids='';
			foreach ($product_selected as $pid=>$dummy){
				$params_pids.="&product_selected[$pid]=1";
			}
			tep_redirect(tep_href_link(FILENAME_QUICK_UPDATES, tep_get_all_get_params(array('pID','action', 'USE_CACHE', 'product_selected')) . "action=multi_delete_confirm&USE_CACHE=1$params_pids"));
		}else{
			$messageStack->add(TEXT_WARNING_CACHE_TURNED_ON,'warning');
			reset($product_selected);
			foreach ($product_selected as $pid=>$dummy){
				//$messageStack->add("[debug] rimozione del prodotto:$pid",'success');
				tep_remove_product($pid);
			}
			$messageStack->add(sprintf(TEXT_PRODUCTS_DELETED,sizeof($product_selected)), 'success');
			if (isset($_REQUEST['USE_CACHE']) && $_REQUEST['USE_CACHE']=='1'){
				tep_db_query("update ".TABLE_CONFIGURATION." set configuration_value='true' where configuration_key='USE_CACHE'");
				tep_reset_cache_block('categories');
				tep_reset_cache_block('also_purchased');
				tep_reset_cache_block('manufacturers');
				tep_reset_cache_block('thumbnails');
				tep_reset_cache_block('skins');
			}
			$product_selected=array();
			tep_redirect(tep_href_link(FILENAME_QUICK_UPDATES, tep_get_all_get_params(array( 'pID', 'action', 'USE_CACHE','product_selected')) . "action="));
		}
		break;
case 'multi_activate_confirm':
		
			//if (USE_CACHE=='true'){ 
		if (false){
			$messageStack->add_session(TEXT_WARNING_CACHE_TURNED_OFF,'warning');
			tep_db_query("update ".TABLE_CONFIGURATION." set configuration_value='false' where configuration_key='USE_CACHE'");
			reset($product_selected);
			$params_pids='';
			foreach ($product_selected as $pid=>$dummy){
				$params_pids.="&product_selected[$pid]=1";
			//	$sql_data = array ('products_status' => '1' );
			//	tep_db_perform(TABLE_PRODUCTS, $sql_data, 'update', 'products_id=\'' . $pid . '\'');
			}
			tep_redirect(tep_href_link(FILENAME_QUICK_UPDATES, tep_get_all_get_params(array('pID','action', 'USE_CACHE', 'product_selected')) . "action=multi_activate_confirm&USE_CACHE=1$params_pids"));
		}else{
			$messageStack->add(TEXT_WARNING_CACHE_TURNED_ON,'warning');
			reset($product_selected);

			foreach ($product_selected as $pid=>$dummy){
				//$messageStack->add("[debug] rimozione del prodotto:$pid",'success');
				// tep_remove_product($pid);
				$sql_data = array ('products_status' => '1');
				tep_db_perform(TABLE_PRODUCTS, $sql_data,'update','products_id = \'' . $pid . '\'' );
			}
			$messageStack->add(sprintf(TEXT_PRODUCTS_ACTIVATED,sizeof($product_selected)), 'success');
			if (isset($_REQUEST['USE_CACHE']) && $_REQUEST['USE_CACHE']=='1'){
				tep_db_query("update ".TABLE_CONFIGURATION." set configuration_value='true' where configuration_key='USE_CACHE'");
				tep_reset_cache_block('categories');
				tep_reset_cache_block('also_purchased');
				tep_reset_cache_block('manufacturers');
				tep_reset_cache_block('thumbnails');
				tep_reset_cache_block('skins');
			}
			$product_selected=array();
			//tep_redirect(tep_href_link(FILENAME_QUICK_UPDATES, tep_get_all_get_params(array( 'pID', 'action', 'USE_CACHE','product_selected')) . "action="));
		}
		break;
		
	// Richiesta di conferma per spostamento multiplo
	case 'multi_move':
		$num_products_2_move=sizeof($product_selected);
		break;
	// Spostamento multiplo
	case 'multi_move_confirm':
		//print_r($product_selected);exit;
		
		// if (USE_CACHE=='true'){
		if (false){
			$messageStack->add_session(TEXT_WARNING_CACHE_TURNED_OFF,'warning');
			tep_db_query("update ".TABLE_CONFIGURATION." set configuration_value='false' where configuration_key='USE_CACHE'");
			reset($product_selected);
			$params_pids='';
			foreach ($product_selected as $pid=>$dummy){
				$params_pids.="&product_selected[$pid]=1";
			}
			// tep_redirect(tep_href_link(FILENAME_QUICK_UPDATES, tep_get_all_get_params(array('pID','action', 'USE_CACHE' ,'product_selected' ,'to_category_id')) . "action=multi_move_confirm&to_category_id=$to_category_id&USE_CACHE=1$params_pids"));
		}else{
			//print_r($_REQUEST);exit;
			$messageStack->add(TEXT_WARNING_CACHE_TURNED_ON,'warning');
			reset($product_selected);
			$count_moved=0;
			foreach ($product_selected as $pid=>$dummy){
				//if (true)$messageStack->add("[debug] spostamento del prodotto:$pid in ".tep_get_category_name($to_category_id,$languages_id),'success');else
				if (move_product($pid,$to_category_id))
					$count_moved++;
			}
			$messageStack->add(sprintf(TEXT_PRODUCTS_MOVED,$count_moved), 'success');
			if (isset($_REQUEST['USE_CACHE']) && $_REQUEST['USE_CACHE']=='1'){
				tep_db_query("update ".TABLE_CONFIGURATION." set configuration_value='true' where configuration_key='USE_CACHE'");
				tep_reset_cache_block('categories');
				tep_reset_cache_block('also_purchased');
				tep_reset_cache_block('manufacturers');
				tep_reset_cache_block('thumbnails');
				tep_reset_cache_block('skins');
			}
			$product_selected=array();
			//tep_redirect(tep_href_link(FILENAME_QUICK_UPDATES, tep_get_all_get_params(array('pID', 'action', 'USE_CACHE','product_selected')) . "action="));
		}
		break;
	// Richiesta di conferma per copia multipla
	case 'multi_copy':
		$num_products_2_copy=sizeof($product_selected);
		break;
	// Copia multipla
	case 'multi_copy_confirm':
		//print_r($product_selected);exit;
				//if (USE_CACHE=='true'){ 
		if (false){
			$messageStack->add_session(TEXT_WARNING_CACHE_TURNED_OFF,'warning');
			tep_db_query("update ".TABLE_CONFIGURATION." set configuration_value='false' where configuration_key='USE_CACHE'");
			reset($product_selected);
			$params_pids='';
			foreach ($product_selected as $pid=>$dummy){
				$params_pids.="&product_selected[$pid]=1";
			}
			tep_redirect(tep_href_link(FILENAME_QUICK_UPDATES, tep_get_all_get_params(array('pID','action', 'USE_CACHE' ,'product_selected' ,'to_category_id')) . "action=multi_copy_confirm&to_category_id=$to_category_id&USE_CACHE=1$params_pids"));
		}else{
			//print_r($_REQUEST);exit;
			$messageStack->add(TEXT_WARNING_CACHE_TURNED_ON,'warning');
			reset($product_selected);
			$count_copied=0;
			foreach ($product_selected as $pid=>$dummy){
				//if (true)$messageStack->add("[debug] copia del prodotto:$pid in ".tep_get_category_name($to_category_id,$languages_id),'success');else
				if (copy_product($pid,$to_category_id))
					$count_copied++;
			}
			$messageStack->add(sprintf(TEXT_PRODUCTS_COPIED,$count_copied), 'success');
			if (isset($_REQUEST['USE_CACHE']) && $_REQUEST['USE_CACHE']=='1'){
				tep_db_query("update ".TABLE_CONFIGURATION." set configuration_value='true' where configuration_key='USE_CACHE'");
				tep_reset_cache_block('categories');
				tep_reset_cache_block('also_purchased');
				tep_reset_cache_block('manufacturers');
				tep_reset_cache_block('thumbnails');
				tep_reset_cache_block('skins');
			}
			$product_selected=array();
			//tep_redirect(tep_href_link(FILENAME_QUICK_UPDATES, tep_get_all_get_params(array('pID', 'action', 'USE_CACHE','product_selected')) . "action="));
		}
		break;
	// Richiesta di conferma per copia (collegamento) multipla
	case 'multi_link':
		$num_products_2_link=sizeof($product_selected);
		break;
	// Copia (collegamento) multipla
	case 'multi_link_confirm':
		//print_r($product_selected);exit;
				//if (USE_CACHE=='true'){ 
		if (false){
			$messageStack->add_session(TEXT_WARNING_CACHE_TURNED_OFF,'warning');
			tep_db_query("update ".TABLE_CONFIGURATION." set configuration_value='false' where configuration_key='USE_CACHE'");
			reset($product_selected);
			$params_pids='';
			foreach ($product_selected as $pid=>$dummy){
				$params_pids.="&product_selected[$pid]=1";
			}
			tep_redirect(tep_href_link(FILENAME_QUICK_UPDATES, tep_get_all_get_params(array('pID','action', 'USE_CACHE' ,'product_selected' ,'to_category_id')) . "action=multi_link_confirm&to_category_id=$to_category_id&USE_CACHE=1$params_pids"));
		}else{
			//print_r($_REQUEST);exit;
			$messageStack->add(TEXT_WARNING_CACHE_TURNED_ON,'warning');
			reset($product_selected);
			$count_linked=0;
			foreach ($product_selected as $pid=>$dummy){
				//if (true)$messageStack->add("[debug] collegamento del prodotto:$pid in ".tep_get_category_name($to_category_id,$languages_id),'success');else
				if (link_product($pid,$to_category_id))
					$count_linked++;
			}
			$messageStack->add(sprintf(TEXT_PRODUCTS_LINKED,$count_linked), 'success');
			if (isset($_REQUEST['USE_CACHE']) && $_REQUEST['USE_CACHE']=='1'){
				tep_db_query("update ".TABLE_CONFIGURATION." set configuration_value='true' where configuration_key='USE_CACHE'");
				tep_reset_cache_block('categories');
				tep_reset_cache_block('also_purchased');
				tep_reset_cache_block('manufacturers');
				tep_reset_cache_block('thumbnails');
				tep_reset_cache_block('skins');
			}
			$product_selected=array();
			//tep_redirect(tep_href_link(FILENAME_QUICK_UPDATES, tep_get_all_get_params(array('pID', 'action', 'USE_CACHE','product_selected')) . "action="));
		}
		break;
}

//// explode string parameters from preview product
if($info_back && $info_back!="-") {
	$infoback = explode('-',$info_back);
	$sort_by = $infoback[0];
	$page =  $infoback[1];
	$current_category_id = $infoback[2];
	$row_by_page = $infoback[3];
	$manufacturer = $infoback[4];
	$customers_group_id = $infoback[5];
}
if (is_null($plugin_cgroups)){
	$customers_group_id='';
}else{
	$plugin_cgroups->setCustomersGroup(strval($customers_group_id));
}
//// define the step for rollover lines per page
$row_bypage_array = array(array());
for ($i = 50; $i <=1000 ; $i=$i+50) {
	$row_bypage_array[] = array('id' => $i,
                                  'text' => $i);
}

// PWS bof
// Se impostato a true mostra le percentuali di sconto per gruppi, altrimenti prezzi e tasse
$show_cg_discounts=!is_null($plugin_cgroups) && isset($customers_group_id) && $customers_group_id != '0' && $customers_group_id != '';
// PWS eof
##// Let's start displaying page with forms
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>"/>
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css"/>
<style type="text/css">
.multi_div{
	float:left;
	padding: 2px 2px 2px 2px;
	margin: 0px 10px 0px 10px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
}
</style>
<script type="text/javascript" language="javascript" src="includes/javascript/prototype.js"></script>
<script type="text/javascript" language="javascript"><!--
var browser_family;
var up = 1;

if (document.all && !document.getElementById){
	browser_family = "dom2";
}else if (document.layers){
	browser_family = "ns4";
}else if (document.getElementById){
	browser_family = "dom2";
}else{
	browser_family = "other";
}
function init_multi_operations(){
	count_selected_products();
	multi_change_operation();
}
function products_selectall(){
	var product_counter=$('multi_products_number');
	var product;
	var checked=$('product_selectall').checked;
	var i=0;
	while (product=$('product_select['+i+']')){
		product.checked=checked;
		i++;
	}
	count_selected_products();
}
function count_selected_products(){
	var product_counter=$('multi_products_number');
	var product;
	var i=0;
	var count=0;
	while (product=$('product_select['+i+']')){
		if (product.checked){
			count++;
		}
		i++
	}
	product_counter.value=count;
	return count;
}
function multi_operation_confirm(){
	var multi_action_select=$('multi_action_select');
	var multi_action=$('multi_action');
	multi_action.value=multi_action_select.value;
	switch (multi_action.value){
		case 'multi_copy':
			$('to_category_id').value=$('copy_to_category_id').value;
			break;
		case 'multi_link':
			$('to_category_id').value=$('link_to_category_id').value;
			break;
		case 'multi_move':
			$('to_category_id').value=$('move_to_category_id').value;
			break;
		case 'copy_to_category_id':
			$('to_category_id').value=$('copy_to_category_id').value;
			break;
	}
	if (count_selected_products()>0){
		document.forms['update'].submit()
	}else{
		alert('<?=TEXT_WARNING_NO_PRODUCTS_SELECTED?>');
	}
}
function multi_change_operation(){
	var multi_action_select=$('multi_action_select');
	var multi_action=$('multi_action');
	var multi_delete=$('multi_div_delete');
	var multi_activate=$('multi_div_activate');
	var multi_move=$('multi_div_move');
	var multi_copy=$('multi_div_copy');
	var multi_link=$('multi_div_link');
	//multi_action.value=multi_action_select.value;
	multi_delete.style.display='none';
//	multi_activate.style.display='none';
	multi_move.style.display='none';
	multi_copy.style.display='none';
	multi_link.style.display='none';
	switch (multi_action_select.value){
		case 'multi_delete':
			multi_delete.style.display='block';
			break;
		case 'multi_activate':
			multi_delete.style.display='block';
			break;
		case 'multi_move':
			multi_move.style.display='block';
			break;
		case 'multi_copy':
			multi_copy.style.display='block';
			break;
		case 'multi_link':
			multi_link.style.display='block';
			break;
	}
}

<? if (!$show_cg_discounts) {?>
function display_ttc(action, prix, taxe, up){
	if(action == 'display'){
		if(up != 1)
		valeur = Math.round((prix + (taxe / 100) * prix) * 100) / 100;
	}else{
		if(action == 'keyup'){
			valeur = Math.round((parseFloat(prix) + (taxe / 100) * parseFloat(prix)) * 100) / 100;
		}else{
			valeur = '0';
		}
	}
	switch (browser_family){
		case 'dom2':
			document.getElementById('descDiv').innerHTML = "<?=TOTAL_COST?> : "+valeur;
			break;
		case 'ie4': document.all.descDiv.innerHTML = "<?=TOTAL_COST?>: "+valeur;
			break;
		case 'ns4':
			document.descDiv.document.descDiv_sub.document.write(valeur);
			document.descDiv.document.descDiv_sub.document.close();
			break;
		case 'other':
			break;
	} 
}
<?}?>
//--></script>
</head>
<body
	marginwidth="0" marginheight="0" topmargin="0" bottommargin="0"
	leftmargin="0" rightmargin="0" bgcolor="#FFFFFF"
	onload="init_multi_operations()">
<!-- header //-->
	<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->


<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
	<tr>
		<td width="<?php echo BOX_WIDTH; ?>" valign="top">
		<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1"
			cellpadding="1" class="columnLeft">
			<!-- left_navigation //-->
			<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
			<!-- left_navigation_eof //-->
		</table>
		</td>
		<!-- body_text //-->

		<td width="100%" valign="top">
		<table border="0" width="100%" cellspacing="0" cellpadding="2">
			<tr>
				<td>
				<table border="0" width="100%" cellspacing="0" cellpadding="0">
					<tr>
						<td class="pageHeading" colspan="3" valign="top"><?php echo HEADING_TITLE; ?></td>
						<td class="pageHeading" align="right"><?php
						if($current_category_id != 0){
							$image_query = tep_db_query("select c.categories_image from " . TABLE_CATEGORIES . " c where c.categories_id=" . $current_category_id);
							$image = tep_db_fetch_array($image_query);
							echo tep_image(DIR_WS_CATALOG . DIR_WS_IMAGES . $image['categories_image'], '', 40);
						}else{
							if($manufacturer){
								$image_query = tep_db_query("select manufacturers_image from " . TABLE_MANUFACTURERS . " where manufacturers_id=" . $manufacturer);
								$image = tep_db_fetch_array($image_query);
								echo tep_image(DIR_WS_CATALOG . DIR_WS_IMAGES . $image['manufacturers_image'], '', 40);
							}
						}
						?></td>
					</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td align="center">
				<table width="100%" cellspacing="0" cellpadding="0" border="1"
					bgcolor="#F3F9FB" bordercolor="#D1E7EF" height="100">
					<tr align="left">
						<td valign="middle">
						<table width="100%" cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td colspan="5" class="smallText">&nbsp;</td>
							</tr>
							<!-- Inizio Sezione spostamento/copia/eliminazione prodotti in serie -->
<?php
switch ($action){
	case 'multi_copy':
		/////////////////////////////////////////////////////////
		// Pannello operazioni multiple sui prodotti (copia/eliminazione/spostamento)
		// ### Conferma alla copia prodotti in una altra categoria###
		$product_count='<input type="text" size="2" readonly="true"
											name="multi_products_number" id="multi_products_number"
											value="'.$num_products_2_copy.'" style="border:dotted gray 1px;background:transparent!important"/>';
?>
							<tr align="center">
								<td class="smallText" colspan="5"><?=sprintf(TEXT_CONFIRM_PRODUCTS_COPY,$product_count, tep_get_category_name($to_category_id,$languages_id))?>
								&nbsp; <a href="javascript:void(0)"
									onclick="$('multi_action').value='multi_copy_confirm';document.forms['update'].submit()">
									<?=tep_image_button('button_confirm.gif',sprintf(TEXT_SELECTED_PRODUCTS_COPY, tep_get_category_name($to_category_id,$languages_id)))?>
								</a>
								&nbsp;&nbsp; <a href="javascript:void(0)"
									onclick="$('multi_action').value='abort';document.forms['update'].submit()">
									<?=tep_image_button('button_cancel.gif',TEXT_SELECTED_PRODUCTS_CANCEL)?>
								</a>
								</td>
							</tr>
<?php
		break;
	case 'multi_link':
		/////////////////////////////////////////////////////////
		// Pannello operazioni multiple sui prodotti (copia/eliminazione/spostamento)
		// ### Conferma alla copia prodotti in una altra categoria###
		$product_count='<input type="text" size="2" readonly="true"
											name="multi_products_number" id="multi_products_number"
											value="'.$num_products_2_link.'" style="border:dotted gray 1px;background:transparent!important"/>';
?>
							<tr align="center">
								<td class="smallText" colspan="5"><?=sprintf(TEXT_CONFIRM_PRODUCTS_LINK,$product_count, tep_get_category_name($to_category_id,$languages_id))?>
								&nbsp; <a href="javascript:void(0)"
									onclick="$('multi_action').value='multi_link_confirm';document.forms['update'].submit()">
									<?=tep_image_button('button_confirm.gif',sprintf(TEXT_SELECTED_PRODUCTS_LINK, tep_get_category_name($to_category_id,$languages_id)))?>
								</a>
								&nbsp;&nbsp; <a href="javascript:void(0)"
									onclick="$('multi_action').value='abort';document.forms['update'].submit()">
									<?=tep_image_button('button_cancel.gif',TEXT_SELECTED_PRODUCTS_CANCEL)?>
								</a>
								</td>
							</tr>
<?php
		break;
	case 'multi_move':
		/////////////////////////////////////////////////////////
		// Pannello operazioni multiple sui prodotti (copia/eliminazione/spostamento)
		// ### Conferma allo spostamento prodotti in una altra categoria###
		$product_count='<input type="text" size="2" readonly="true"
											name="multi_products_number" id="multi_products_number"
											value="'.$num_products_2_move.'" style="border:dotted gray 1px;background:transparent!important"/>';
?>
							<tr align="center">
								<td class="smallText" colspan="5"><?=sprintf(TEXT_CONFIRM_PRODUCTS_MOVE,$product_count, tep_get_category_name($to_category_id,$languages_id))?>
								&nbsp; <a href="javascript:void(0)"
									onclick="$('multi_action').value='multi_move_confirm';document.forms['update'].submit()">
									<?=tep_image_button('button_confirm.gif',sprintf(TEXT_SELECTED_PRODUCTS_MOVE, tep_get_category_name($to_category_id,$languages_id)))?>
								</a>
								&nbsp;&nbsp; <a href="javascript:void(0)"
									onclick="$('multi_action').value='abort';document.forms['update'].submit()">
									<?=tep_image_button('button_cancel.gif',TEXT_SELECTED_PRODUCTS_CANCEL)?>
								</a>
								</td>
							</tr>
<?php
		break;
	case 'multi_delete':
	/////////////////////////////////////////////////////////
	// Pannello operazioni multiple sui prodotti (copia/eliminazione/spostamento)
	// ### Conferma alla eliminazione multipla ###
		$product_count='<input type="text" size="2" readonly="true"
											name="multi_products_number" id="multi_products_number"
											value="'.$num_products_2_delete.'" style="border:dotted gray 1px;background:transparent!important"/>';
?>
							<tr align="center">
								<td class="smallText" colspan="5"><?=sprintf(TEXT_CONFIRM_PRODUCTS_DELETION,$product_count)?>
								&nbsp; <a href="javascript:void(0)"
									onclick="$('multi_action').value='multi_delete_confirm';document.forms['update'].submit()">
									<?=tep_image_button('button_confirm.gif',TEXT_SELECTED_PRODUCTS_DELETE)?>
								</a>
								&nbsp;&nbsp; <a href="javascript:void(0)"
									onclick="$('multi_action').value='abort';document.forms['update'].submit()">
									<?=tep_image_button('button_cancel.gif',TEXT_SELECTED_PRODUCTS_CANCEL)?>
								</a>
								</td>
							</tr>
<?php
		break;
	case 'multi_activate':
	/////////////////////////////////////////////////////////
	// Pannello operazioni multiple sui prodotti (copia/eliminazione/spostamento)
	// ### Conferma alla eliminazione multipla ###
		$product_count='<input type="text" size="2" readonly="true"
											name="multi_products_number" id="multi_products_number"
											value="'.$num_products_2_activate.'" style="border:dotted gray 1px;background:transparent!important"/>';
?>
							<tr align="center">
								<td class="smallText" colspan="5"><?=sprintf(TEXT_CONFIRM_PRODUCTS_ACTIVATION,$product_count)?>
								&nbsp; <a href="javascript:void(0)"
									onclick="$('multi_action').value='multi_activate_confirm';document.forms['update'].submit()">
									<?=tep_image_button('button_confirm.gif',TEXT_SELECTED_PRODUCTS_ACTIVATE)?>
								</a>
								&nbsp;&nbsp; <a href="javascript:void(0)"
									onclick="$('multi_action').value='abort';document.forms['update'].submit()">
									<?=tep_image_button('button_cancel.gif',TEXT_SELECTED_PRODUCTS_CANCEL)?>
								</a>
								</td>
							</tr>
<?php
		break;
	default:
	/////////////////////////////////////////////////////////
	// Pannello operazioni multiple sui prodotti (copia/eliminazione/spostamento)
	// ### Default ###
?>
							<tr align="center" valign="middle">
								<td colspan="5" align="left" class="smallText" valign="middle">
									<div class="multi_div"><?=TEXT_SELECTED_PRODUCTS_OPERATIONS?></div>
									<div class="multi_div">
										<select name="multi_action_select" id="multi_action_select"
											onchange="multi_change_operation()"
											onkeyup="multi_change_operation()"
											onload="multi_change_operation()">
											<option value="multi_activate"><?=TEXT_SELECTED_PRODUCTS_ACTION_ACTIVATE?></option>
											<option value="multi_move"><?=TEXT_SELECTED_PRODUCTS_ACTION_MOVE?></option>
											<option value="multi_copy"><?=TEXT_SELECTED_PRODUCTS_ACTION_COPY?></option>
											<option value="multi_link"><?=TEXT_SELECTED_PRODUCTS_ACTION_LINK?></option>
											<option value="multi_delete"><?=TEXT_SELECTED_PRODUCTS_ACTION_DELETE?></option>
										</select>
									</div>
									<div class="multi_div">
										<label for="multi_products_number"><input type="text" size="2" readonly="true"
											name="multi_products_number" id="multi_products_number"
											value="<?=sizeof($product_selected)?>" style="border:dotted gray 1px;background:transparent!important"/>
										<?=TEXT_SELECTED_PRODUCTS_LABEL?></label>
									</div>
									<div class="multi_div" id="multi_div_delete" name="multi_div_delete" style="display:none">
									</div>
									<div class="multi_div" id="multi_div_move" name="multi_div_move" style="display:none">
										<?=sprintf(TEXT_SELECTED_PRODUCTS_MOVE,'')
											.tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree('0', '', $current_category_id, '', true), '')?>
									</div>
									<div class="multi_div" id="multi_div_link" name="multi_div_link" style="display:none">
										<?=sprintf(TEXT_SELECTED_PRODUCTS_LINK,'')
											.tep_draw_pull_down_menu('link_to_category_id', tep_get_category_tree('0', '', $current_category_id, '', true), '')?>
									</div>
									<div class="multi_div" id="multi_div_copy" name="multi_div_copy" style="display:none">
										<?=sprintf(TEXT_SELECTED_PRODUCTS_COPY,'')
											.tep_draw_pull_down_menu('copy_to_category_id', tep_get_category_tree('0', '', $current_category_id, '', true), '')?>
									</div>
									<div id="multi_div_confirm" class="multi_div">
										<a href="javascript:void(0)" onclick="multi_operation_confirm()">
											<?=tep_image_button('button_confirm.gif',TEXT_SELECTED_PRODUCTS_EXECUTE)?>
										</a>
									</div>
								</td>
							</tr>

							<?php		}?>
							<!-- Fine Sezione spostamento/copia/eliminazione prodotti in serie -->
							<tr>
								<td colspan="5" class="smallText">&nbsp;</td>
							</tr>
						</table>


						<table width="100%" cellspacing="0" cellpadding="0" border="0">
							<!-- SPPC mod: next tr changed for better HTML -->
							<tr>
								<td colspan="5" class="smallText">&nbsp;</td>
							</tr>
							<tr align="center">
								<td class="smallText"><?php echo tep_draw_form('row_by_page', FILENAME_QUICK_UPDATES, '', 'get'); echo tep_draw_hidden_field( 'manufacturer', $manufacturer); echo tep_draw_hidden_field( 'cPath', $current_category_id);
								// BOF Separate Pricing Per Customer
								echo tep_draw_hidden_field('customers_group_id', $customers_group_id);
								?></td>
								<td class="smallText"><?php echo TEXT_MAXI_ROW_BY_PAGE . '&nbsp;&nbsp;' . tep_draw_pull_down_menu('row_by_page', $row_bypage_array, $row_by_page, 'onChange="this.form.submit();"'); ?>
								</form>
								</td>
								<?php echo tep_draw_form('categorie', FILENAME_QUICK_UPDATES, '', 'get'); echo tep_draw_hidden_field( 'row_by_page', $row_by_page); echo tep_draw_hidden_field( 'manufacturer', $manufacturer); echo tep_draw_hidden_field('customers_group_id', $customers_group_id);?>
								<td class="smallText" align="center" valign="top"><?php echo DISPLAY_CATEGORIES . '&nbsp;&nbsp;' . tep_draw_pull_down_menu('cPath', tep_get_category_tree(), $current_category_id, 'onChange="this.form.submit();"'); ?></td>
								</form>
								<?php echo tep_draw_form('manufacturers', FILENAME_QUICK_UPDATES, '', 'get'); echo tep_draw_hidden_field( 'row_by_page', $row_by_page); echo tep_draw_hidden_field( 'cPath', $current_category_id); echo tep_draw_hidden_field('customers_group_id', $customers_group_id);?>
								<td class="smallText" align="center" valign="top"><?php echo DISPLAY_MANUFACTURERS . '&nbsp;&nbsp' . manufacturers_list(); ?></td>
								</form>
								<? if (!is_null($plugin_cgroups)){?>
								<td class="smallText" align="center" valign="top"><?php 
								echo tep_draw_form('customers_groups', FILENAME_QUICK_UPDATES, '', 'get'); echo tep_draw_hidden_field( 'row_by_page', $row_by_page); echo tep_draw_hidden_field( 'cPath', $current_category_id); echo tep_draw_hidden_field( 'manufacturer', $manufacturer);
								echo DISPLAY_CUSTOMERS_GROUPS . '&nbsp;&nbsp' . customers_groups_list(); ?>
								</form>
								</td>
								<? } ?>
							</tr>
						</table>

						<table width="100%" cellspacing="0" cellpadding="0" border="0">
							<tr align="center">


								<td align="center">
								<table border="0" cellspacing="0">
								<?	if ($plugin_tax_raee_installed){?>
									<tr>
										<td>
										<form name="raee_class"
										<?php echo 'action="' . tep_href_link(FILENAME_QUICK_UPDATES, tep_get_all_get_params(array('action', 'info', 'pID')) . "action=raee&page=" . $page . "&sort_by=" . $sort_by . "&cPath=" . $current_category_id . "&row_by_page=". $row_by_page . "&manufacturer=" . $manufacturer."&customers_group_id=" . $customers_group_id ."" , 'NONSSL') . '"'; ?>
											method="post">
										<table border="0" cellspacing="0">
											<tr>
												<td class="main" align="left" valign="middle" nowrap><?php echo TEXT_INPUT_RAEE_CLASS; ?></td>
												<td align="center" valign="middle"><?=$plugin_tax_raee->getTaxRaeeDropDownMenu("spec_raee_class",0) ?>
												</td>
												<td class="smallText" align="center" valign="middle"><?php
												if ($preview_raee_class != 'true') {
													echo '&nbsp;&nbsp;' . tep_image_submit('button_preview.gif', IMAGE_PREVIEW, "page=$page&sort_by=$sort_by&cPath=$current_category_id&row_by_page=$row_by_page&manufacturer=$manufacturer&customers_group_id=" . $customers_group_id ."");
												} else {
													echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_QUICK_UPDATES, "page=$page&sort_by=$sort_by&cPath=$current_category_id&row_by_page=$row_by_page&manufacturer=$manufacturer&customers_group_id=" . $customers_group_id ."") . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
												}
												?></td>
											</tr>
											<tr>
												<td class="smallText" align="center" valign="middle"
													colspan="3" nowrap><?php if ($preview_raee_class == 'true') echo TEXT_SPEC_RAEE_CLASS;?>
												</td>
											</tr>
										</table>
										</form>
										</td>
									</tr>
									<?	}
									if (!$show_cg_discounts){ // viene visualizzata solo se siamo nell'aggiornamento del gruppo clienti finali
										// distinguiamo 2 casi: presenza del plugin prezzi di acquisto o no
										if ($plugin_purchase_installed) // il campo per la modifica percentuale va a modificare il ricarico
										{?>
									<tr>
										<td>
										<form name="spec_price"
										<?php echo 'action="' . tep_href_link(FILENAME_QUICK_UPDATES, tep_get_all_get_params(array('action', 'info', 'pID')) . "action=markup&page=" . $page . "&sort_by=" . $sort_by . "&cPath=" . $current_category_id . "&row_by_page=". $row_by_page . "&manufacturer=" . $manufacturer."&customers_group_id=" . $customers_group_id ."" , 'NONSSL') . '"'; ?>
											method="post">
										<table border="0" cellspacing="0">
											<tr>
												<td class="main" align="center" valign="middle" nowrap><?php echo TEXT_INPUT_MARKUP; ?></td>
												<td align="center" valign="middle"><?php echo tep_draw_input_field('markup_price',0,'size="5"'); ?>
												</td>
												<td class="smallText" align="center" valign="middle"><?php
												if ($preview_global_price != 'true') {
													echo '&nbsp;&nbsp;' . tep_image_submit('button_preview.gif', IMAGE_PREVIEW, "page=$page&sort_by=$sort_by&cPath=$current_category_id&row_by_page=$row_by_page&manufacturer=$manufacturer&customers_group_id=" . $customers_group_id ."");
												} else {
													echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_QUICK_UPDATES, "page=$page&sort_by=$sort_by&cPath=$current_category_id&row_by_page=$row_by_page&manufacturer=$manufacturer&customers_group_id=" . $customers_group_id ."") . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
												}
												?></td>
												<?php if(ACTIVATE_COMMERCIAL_MARGIN == 'true') {
													echo '<td class="smallText" align="center" valign="middle">&nbsp;&nbsp;&nbsp;&nbsp;' . tep_draw_checkbox_field('marge','yes','','no') . '&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', TEXT_MARGE_INFO) . '</td>';}
													?>
											</tr>
											<tr>
												<td class="smallText" align="center" valign="middle"
													colspan="3" nowrap><?php if ($preview_global_price != 'true') {
														echo TEXT_SPEC_PRICE_INFO1 ;
													} else {
														echo TEXT_SPEC_PRICE_INFO2;
													}?>
									</tr>
									<? 	}
										else {	?>
									<tr>
										<td>
										<form name="spec_price"
										<?php echo 'action="' . tep_href_link(FILENAME_QUICK_UPDATES, tep_get_all_get_params(array('action', 'info', 'pID')) . "action=calcul&page=" . $page . "&sort_by=" . $sort_by . "&cPath=" . $current_category_id . "&row_by_page=". $row_by_page . "&manufacturer=" . $manufacturer."&customers_group_id=" . $customers_group_id ."" , 'NONSSL') . '"'; ?>
											method="post">
										<table border="0" cellspacing="0">
											<tr>
												<td class="main" align="center" valign="middle" nowrap><?php  echo TEXT_INPUT_SPEC_PRICE; ?></td>
												<td align="center" valign="middle"><?php echo tep_draw_input_field('spec_price',0,'size="5"'); ?>
												</td>
												<td class="smallText" align="center" valign="middle"><?php
												if ($preview_global_price != 'true') {
													echo '&nbsp;&nbsp;' . tep_image_submit('button_preview.gif', IMAGE_PREVIEW, "page=$page&sort_by=$sort_by&cPath=$current_category_id&row_by_page=$row_by_page&manufacturer=$manufacturer&customers_group_id=" . $customers_group_id ."");
												} else {
													echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_QUICK_UPDATES, "page=$page&sort_by=$sort_by&cPath=$current_category_id&row_by_page=$row_by_page&manufacturer=$manufacturer&customers_group_id=" . $customers_group_id ."") . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
												}
												?></td>
												<?php if(ACTIVATE_COMMERCIAL_MARGIN == 'true') {
													echo '<td class="smallText" align="center" valign="middle">&nbsp;&nbsp;&nbsp;&nbsp;' . tep_draw_checkbox_field('marge','yes','','no') . '&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', TEXT_MARGE_INFO) . '</td>';}
													?>
											</tr>
											<tr>
												<td class="smallText" align="center" valign="middle"
													colspan="3" nowrap><?php if ($preview_global_price != 'true') {
														echo TEXT_SPEC_PRICE_INFO1 ;
													} else {
														echo TEXT_SPEC_PRICE_INFO2;
													}
										} // fine else prezzi di acquisto	?></td>
											</tr>
										</table>
										</form>
										</td>
									</tr>
									<? } ?>
								</table>
								</td>
							</tr>
							<tr>
								<td height="5"></td>
							</tr>

							</td>
							</tr>
							<br>
							<table width="100%" cellspacing="0" cellpadding="0" border="0">
								<tr align="center">
									<form name="update" method="POST"
										action="<?php echo "$PHP_SELF?action=update&page=$page&sort_by=$sort_by&cPath=$current_category_id&row_by_page=$row_by_page&manufacturer=$manufacturer&customers_group_id=" . $customers_group_id .""; ?>">
									<input type="hidden" name="multi_action" id="multi_action" value="" />
									<input type="hidden" name="to_category_id" id="to_category_id" value="<?=$to_category_id?>" />
									
									
									<td class="smallText" align="middle"><?php echo WARNING_MESSAGE; ?></td>
									<td class="pageHeading" align="right">
<script language="javascript" type="text/javascript" ><!--
switch (browser_family)
{
	case "dom2":
	case "ie4":
		document.write('<div id="descDiv">');
		break;
	default:
		document.write('<ilayer id="descDiv"><layer id="descDiv_sub">');
		break;
}
//--></script>
									</td>
									<td align="right" valign="middle"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE, "action=update&cPath=$current_category_id&page=$page&sort_by=$sort_by&row_by_page=$row_by_page");?></td>
									<!-- question: why no manufacturer above? -->
								</tr>
							</table>
							</td>
							</tr>
							<tr>
								<td>
								<table border="0" width="100%" cellspacing="0" cellpadding="2">
									<tr>
										<td valign="top">
										<table border="0" width="100%" cellspacing="0" cellpadding="2">
											<tr class="dataTableHeadingRow">
												<td class="dataTableHeadingContent" align="center"
													valign="middle">&nbsp;&nbsp;<input id="product_selectall"
													type="checkbox" onclick="products_selectall()" /></td>
												</td>
												<td class="dataTableHeadingContent" align="left"
													valign="middle">
												<table border="0" cellspacing="0" cellpadding="0">
													<tr class="dataTableHeadingRow">
														<td class="dataTableHeadingContent" align="left"
															valign="middle"><?php if(DISPLAY_MODEL == 'true') echo " <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_model ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_MODEL . ' ' . TEXT_ASCENDINGLY)."</a>
                     <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_model DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_MODEL . ' ' . TEXT_DESCENDINGLY)."</a>
                     <br>"  .TABLE_HEADING_MODEL . "</td>" ; ?>
													
													</tr>
												</table>
												</td>
												<td class="dataTableHeadingContent" align="left"
													valign="middle">
												<table border="0" cellspacing="0" cellpadding="0">
													<tr class="dataTableHeadingRow">
														<td class="dataTableHeadingContent" align="left"
															valign="middle"><?php echo " <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=pd.products_name ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_PRODUCTS . TEXT_ASCENDINGLY)."</a>
                     <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=pd.products_name DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_PRODUCTS . ' ' . TEXT_DESCENDINGLY)."</a>
                     <br>"  .TABLE_HEADING_PRODUCTS . "</td>" ; ?>
													
													</tr>
												</table>
												</td>
												<td class="dataTableHeadingContent" align="center"
													valign="middle">
												<table border="0" cellspacing="0" cellpadding="0">
													<tr class="dataTableHeadingRow">
														<td class="dataTableHeadingContent" align="center"
															valign="middle"><?php if(DISPLAY_STATUT == 'true')echo " <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_status ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . 'OFF ' . TEXT_ASCENDINGLY)."</a>
                     <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_status DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . 'ON ' . TEXT_ASCENDINGLY)."</a>
                     <br>off / on</td>" ; ?>
													
													</tr>
												</table>
												</td>
												<td class="dataTableHeadingContent" align="center"
													valign="middle">
												<table border="0" cellspacing="0" cellpadding="0">
													<tr class="dataTableHeadingRow">
														<td class="dataTableHeadingContent" align="center"
															valign="middle"><?php if($plugin_shopwindow_installed==true) echo " <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_shopwindow ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . 'OFF ' . TEXT_ASCENDINGLY)."</a>
                     <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_shopwindow DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . 'ON ' . TEXT_ASCENDINGLY)."</a>
                     <br>Mostra in vetrina<br/>off / on</td>" ; ?>
													
													</tr>
												</table>
												</td>
												<td class="dataTableHeadingContent" align="center"
													valign="middle">
												<table border="0" cellspacing="0" cellpadding="0">
													<tr class="dataTableHeadingRow">
														<td class="dataTableHeadingContent" align="center"
															valign="middle"><?php if($plugin_makeoffer_installed==true) echo " <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_makeoffer ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . 'OFF ' . TEXT_ASCENDINGLY)."</a>
                     <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_makeoffer DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . 'ON ' . TEXT_ASCENDINGLY)."</a>
                     <br>Proposta di acquisto<br/>off / on</td>" ; ?>
													
													</tr>
												</table>
												</td>
												<td class="dataTableHeadingContent" align="center"
													valign="middle">
												<table border="0" cellspacing="0" cellpadding="0">
													<tr class="dataTableHeadingRow">
														<td class="dataTableHeadingContent" align="center"
															valign="middle"><?php if(DISPLAY_WEIGHT == 'true')echo " <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_weight ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_WEIGHT . ' ' . TEXT_ASCENDINGLY)."</a>
                     <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_weight DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_WEIGHT . ' ' . TEXT_DESCENDINGLY)."</a>
                     <br>" . TABLE_HEADING_WEIGHT . "</td>" ; ?>
													
													</tr>
												</table>
												</td>
												<td class="dataTableHeadingContent" align="center"
													valign="middle">
												<table border="0" cellspacing="0" cellpadding="0">
													<tr class="dataTableHeadingRow">
														<td class="dataTableHeadingContent" align="center"
															valign="middle" width="50"><?php if(DISPLAY_QUANTITY == 'true')echo " <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_quantity ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_QUANTITY . ' ' . TEXT_ASCENDINGLY)."</a>
                     <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_quantity DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_QUANTITY . ' ' . TEXT_DESCENDINGLY)."</a>
                     <br>" . TABLE_HEADING_QUANTITY . "</td>" ; ?>
													
													</tr>
												</table>
												</td>
												<td class="dataTableHeadingContent" align="left"
													valign="middle">
												<table border="0" cellspacing="0" cellpadding="0">
													<tr class="dataTableHeadingRow">
														<td class="dataTableHeadingContent" align="left"
															valign="middle"><?php if(DISPLAY_IMAGE == 'true')echo "&nbsp; <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_image ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_IMAGE . ' ' . TEXT_ASCENDINGLY)."</a>
                     <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_image DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_IMAGE . ' ' . TEXT_DESCENDINGLY)."</a>
                     <br>&nbsp; " . TABLE_HEADING_IMAGE . "</td>" ; ?>
													
													</tr>
												</table>
												</td>
												<td class="dataTableHeadingContent" align="left"
													valign="middle">
												<table border="0" cellspacing="0" cellpadding="0">
													<tr class="dataTableHeadingRow">
														<td class="dataTableHeadingContent" align="left"
															valign="middle"><?php if(DISPLAY_MANUFACTURER == 'true')echo "&nbsp;&nbsp; <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=manufacturers_id ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_MANUFACTURERS . ' ' . TEXT_ASCENDINGLY)."</a>
                     <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=manufacturers_id DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_MANUFACTURERS . ' ' . TEXT_DESCENDINGLY)."</a>
                     <br>&nbsp;&nbsp; " . TABLE_HEADING_MANUFACTURERS . "</td>" ; ?>
													
													</tr>
												</table>
												</td>
												<?	if ($plugin_purchase_installed){?>
												<td class="dataTableHeadingContent" align="center"
													valign="middle">
												<table border="0" cellspacing="0" cellpadding="0">
													<tr class="dataTableHeadingRow">
														<td class="dataTableHeadingContent" align="center"
															valign="middle"><?php if(DISPLAY_PRICE_COMMISSION == 'true')echo " <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=pp.pws_purchase_price_commission ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_PRICE_COMMISSION . ' ' . TEXT_ASCENDINGLY)."</a>
                     <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=pp.pws_purchase_price_commission DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_PRICE_COMMISSION . ' ' . TEXT_DESCENDINGLY)."</a>
                     <br>" . TABLE_HEADING_PRICE_COMMISSION . "</td>" ; ?>
													
													</tr>
												</table>
												</td>
												<?  } ?>
												<?	if ($plugin_clothing_installed){
													if (PWS_CLOTHING_QU_DISPLAY_GENDER=='true'){
														?>
												<td class="dataTableHeadingContent" align="center"
													valign="middle">
												<table border="0" cellspacing="0" cellpadding="0">
													<tr class="dataTableHeadingRow">
														<td class="dataTableHeadingContent" align="center"
															valign="middle"><?= " <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_gender ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_PRODUCTS_GENDER . ' ' . TEXT_ASCENDINGLY)."</a>
                     <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_gender DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_PRODUCTS_GENDER . ' ' . TEXT_DESCENDINGLY)."</a>
                     <br>" . TABLE_HEADING_PRODUCTS_GENDER . "</td>" ; ?>
													
													</tr>
												</table>
												</td>
												<?		}
												if (PWS_CLOTHING_QU_DISPLAY_SEASON=='true'){
													?>
												<td class="dataTableHeadingContent" align="center"
													valign="middle">
												<table border="0" cellspacing="0" cellpadding="0">
													<tr class="dataTableHeadingRow">
														<td class="dataTableHeadingContent" align="center"
															valign="middle"><?= " <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_season ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_PRODUCTS_SEASON . ' ' . TEXT_ASCENDINGLY)."</a>
                     <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_season DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_PRODUCTS_SEASON . ' ' . TEXT_DESCENDINGLY)."</a>
                     <br>" . TABLE_HEADING_PRODUCTS_SEASON . "</td>" ; ?>
													
													</tr>
												</table>
												</td>
												<?		}
} ?>
<?	if ($plugin_tax_raee_installed){?>
												<td class="dataTableHeadingContent" align="center"
													valign="middle">
												<table border="0" cellspacing="0" cellpadding="0">
													<tr class="dataTableHeadingRow">
														<td class="dataTableHeadingContent" align="center"
															valign="middle"><?php if(DISPLAY_TAX_RAEE == 'true')echo " <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=pp.pws_tax_raee_description ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_TAX_RAEE . ' ' . TEXT_ASCENDINGLY)."</a>
                     <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=pp.pws_tax_raee_description DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_TAX_RAEE . ' ' . TEXT_DESCENDINGLY)."</a>
                     <br>" . TABLE_HEADING_TAX_RAEE . "</td>" ; ?>
													
													</tr>
												</table>
												</td>
												<?  } ?>

												<?php
												if ($show_cg_discounts){
													echo '<td class="dataTableHeadingContent" align="left" valign="middle">'."&nbsp;&nbsp;&nbsp; <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_price ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id) ."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_INIT_PRICE . ' ' . TEXT_ASCENDINGLY)."</a>
                    <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_price DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id) ."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_INIT_PRICE . ' ' . TEXT_DESCENDINGLY)."</a>
                     <br>&nbsp;&nbsp;&nbsp; " . TABLE_HEADING_INIT_PRICE . "</td>";
													echo '<td class="dataTableHeadingContent" align="left" valign="middle">'."&nbsp;&nbsp;&nbsp; <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_price ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id) ."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_CG_DISCOUNT . ' ' . TEXT_ASCENDINGLY)."</a>
                    <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_price DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id) ."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_CG_DISCOUNT . ' ' . TEXT_DESCENDINGLY)."</a>
                     <br>&nbsp;&nbsp;&nbsp; " . TABLE_HEADING_CG_DISCOUNT . "</td>";

												}else{
													echo '<td class="dataTableHeadingContent" align="left" valign="middle">'."&nbsp;&nbsp;&nbsp; <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_price ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id) ."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_PRICE . ' ' . TEXT_ASCENDINGLY)."</a>
                    <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_price DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id) ."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_PRICE . ' ' . TEXT_DESCENDINGLY)."</a>
                     <br>&nbsp;&nbsp;&nbsp; " . TABLE_HEADING_PRICE . "</td>";
												}
												?>

												<? if ($show_cg_discounts){?>
												<td class="dataTableHeadingContent" align="left"
													valign="middle"><?php if(DISPLAY_TAX == 'true')echo " <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_price ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id)."\" >".tep_image(DIR_WS_IMAGES  . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_FINAL_PRICE . ' ' . TEXT_ASCENDINGLY)."</a>
                    <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_price DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id)."\" >".tep_image(DIR_WS_IMAGES  . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_FINAL_PRICE . ' ' . TEXT_DESCENDINGLY)."</a>
                    <br>" . TABLE_HEADING_FINAL_PRICE . " </td> " ; 
}else{?>
												
												
												<td class="dataTableHeadingContent" align="left"
													valign="middle"><?php if(DISPLAY_TAX == 'true')echo " <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_tax_class_id ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id)."\" >".tep_image(DIR_WS_IMAGES  . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_TAX . ' ' . TEXT_ASCENDINGLY)."</a>
                    <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_tax_class_id DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id)."\" >".tep_image(DIR_WS_IMAGES  . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_TAX . ' ' . TEXT_DESCENDINGLY)."</a>
                    <br>" . TABLE_HEADING_TAX . " </td> " ; 
}?>
												
												
												<td class="dataTableHeadingContent" align="center"
													valign="middle"></td>
												<td class="dataTableHeadingContent" align="center"
													valign="middle"></td>
											</tr>
											<tr class="datatableRow">
											<?php
											//// control string sort page
											if ($sort_by && !ereg('order by',$sort_by)) $sort_by = 'order by '.$sort_by ;
											//// define the string parameters for good back preview product
											$origin = FILENAME_QUICK_UPDATES."?info_back=$sort_by-$page-$current_category_id-$row_by_page-$manufacturer-$customers_group_id";
											//// controle length (lines per page)
											$split_page = $page;
											if ($split_page > 1) $rows = $split_page * MAX_DISPLAY_ROW_BY_PAGE - MAX_DISPLAY_ROW_BY_PAGE;

											////  select categories
											if ($current_category_id == 0){
												if($manufacturer){
													$products_query_raw = "select p.products_id, p.products_image, p.products_model, pd.products_name, p.products_status, p.products_weight, p.products_quantity, p.manufacturers_id, p.products_price";
				if ($plugin_shopwindow_installed)
            	$products_query_raw .= ", p.products_shopwindow";
				if ($plugin_makeoffer_installed)
            	$products_query_raw .= ", p.products_makeoffer";
            	if ($plugin_purchase_installed)
            	$products_query_raw .= ", if (pp.pws_purchase_price_status='1',pp.pws_purchase_price_commission,".PWS_PRICES_PURCHASE_PRICE_DEFAULT_COMMISSION.") as pws_purchase_price_commission";
            	if ($plugin_tax_raee_installed)
            	$products_query_raw .= ", if (ptrs.pws_tax_raee_status='1',ptrs.pws_tax_raee_id,0) as pws_tax_raee_id";
            	if ($plugin_clothing_installed)
            	$products_query_raw .= ", p.products_gender, p.products_season";
            	$products_query_raw .= ", p.products_tax_class_id from  " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION .  " pd on (p.products_id = pd.products_id and pd.language_id = '$languages_id')";
            	if ($plugin_purchase_installed)
            	$products_query_raw .= " left join ".TABLE_PWS_PURCHASE_PRICE." pp on (p.products_id=pp.products_id)";
            	if ($plugin_tax_raee_installed)
            	$products_query_raw .= " left join ".TABLE_PWS_TAX_RAEE_STATUS." ptrs on (p.products_id=ptrs.products_id) left join ".TABLE_PWS_TAX_RAEE." ptr on (ptrs.pws_tax_raee_id=ptr.pws_tax_raee_id)";
            	$products_query_raw .= " where p.manufacturers_id = " . $manufacturer . " $sort_by ";
												}else{
													$products_query_raw = "select p.products_id, p.products_image, p.products_model, pd.products_name, p.products_status, p.products_weight, p.products_quantity, p.manufacturers_id, p.products_price";
													if ($plugin_shopwindow_installed)
            	$products_query_raw .= ", p.products_shopwindow";
 				if ($plugin_makeoffer_installed)
            	$products_query_raw .= ", p.products_makeoffer";
            	if ($plugin_purchase_installed)
            	$products_query_raw .= ", if (pp.pws_purchase_price_status='1',pp.pws_purchase_price_commission,".PWS_PRICES_PURCHASE_PRICE_DEFAULT_COMMISSION.") as pws_purchase_price_commission";
            	if ($plugin_tax_raee_installed)
            	$products_query_raw .= ", if (ptrs.pws_tax_raee_status='1',ptrs.pws_tax_raee_id,0) as pws_tax_raee_id";
            	if ($plugin_clothing_installed)
            	$products_query_raw .= ", p.products_gender, p.products_season";
            	$products_query_raw .= ", p.products_tax_class_id from  " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION .  " pd on (p.products_id = pd.products_id and pd.language_id = '$languages_id')";
            	if ($plugin_purchase_installed)
            	$products_query_raw .= " left join ".TABLE_PWS_PURCHASE_PRICE." pp on (p.products_id=pp.products_id)";
            	if ($plugin_tax_raee_installed)
            	$products_query_raw .= " left join ".TABLE_PWS_TAX_RAEE_STATUS." ptrs on (p.products_id=ptrs.products_id) left join ".TABLE_PWS_TAX_RAEE." ptr on (ptrs.pws_tax_raee_id=ptr.pws_tax_raee_id)";
            	$products_query_raw .= " where 1 $sort_by ";
												}
											} // end if ($current_category_id == 0)
											else {
												if($manufacturer){
													$products_query_raw = "select p.products_id, p.products_image, p.products_model, pd.products_name, p.products_status, p.products_weight, p.products_quantity, p.manufacturers_id, p.products_price";
													if ($plugin_shopwindow_installed)
            	$products_query_raw .= ", p.products_shopwindow";
 				if ($plugin_makeoffer_installed)
            	$products_query_raw .= ", p.products_makeoffer";
            	if ($plugin_purchase_installed)
            	$products_query_raw .= ", if (pp.pws_purchase_price_status='1',pp.pws_purchase_price_commission,".PWS_PRICES_PURCHASE_PRICE_DEFAULT_COMMISSION.") as pws_purchase_price_commission";
            	if ($plugin_tax_raee_installed)
            	$products_query_raw .= ", if (ptrs.pws_tax_raee_status='1',ptrs.pws_tax_raee_id,0) as pws_tax_raee_id";
            	if ($plugin_clothing_installed)
            	$products_query_raw .= ", p.products_gender, p.products_season";
            	$products_query_raw .= ", p.products_tax_class_id from  " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION .  " pd on (p.products_id = pd.products_id and pd.language_id = '$languages_id')";
            	if ($plugin_purchase_installed)
            	$products_query_raw .= " left join ".TABLE_PWS_PURCHASE_PRICE." pp on (p.products_id=pp.products_id)";
            	if ($plugin_tax_raee_installed)
            	$products_query_raw .= " left join ".TABLE_PWS_TAX_RAEE_STATUS." ptrs on (p.products_id=ptrs.products_id) left join ".TABLE_PWS_TAX_RAEE." ptr on (ptrs.pws_tax_raee_id=ptr.pws_tax_raee_id)";
            	$products_query_raw .= " left join ".TABLE_PRODUCTS_TO_CATEGORIES." pc on (p.products_id = pc.products_id) where  p.manufacturers_id = " . $manufacturer . " and pc.categories_id = '" . $current_category_id . "'  $sort_by ";
												}else{
													$products_query_raw = "select p.products_id, p.products_image, p.products_model, pd.products_name, p.products_status, p.products_weight, p.products_quantity, p.manufacturers_id, p.products_price";
													if ($plugin_shopwindow_installed)
            	$products_query_raw .= ", p.products_shopwindow";
 				if ($plugin_makeoffer_installed)
            	$products_query_raw .= ", p.products_makeoffer";
            	if ($plugin_purchase_installed)
            	$products_query_raw .= ", if (pp.pws_purchase_price_status='1',pp.pws_purchase_price_commission,".PWS_PRICES_PURCHASE_PRICE_DEFAULT_COMMISSION.") as pws_purchase_price_commission";
            	if ($plugin_tax_raee_installed)
            	$products_query_raw .= ", if (ptrs.pws_tax_raee_status='1',ptrs.pws_tax_raee_id,0) as pws_tax_raee_id";
            	if ($plugin_clothing_installed)
            	$products_query_raw .= ", p.products_gender, p.products_season";
            	$products_query_raw .= ", p.products_tax_class_id from  " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION .  " pd on (p.products_id = pd.products_id and pd.language_id = '$languages_id')";
            	if ($plugin_purchase_installed)
            	$products_query_raw .= " left join ".TABLE_PWS_PURCHASE_PRICE." pp on (p.products_id=pp.products_id)";
            	if ($plugin_tax_raee_installed)
            	$products_query_raw .= " left join ".TABLE_PWS_TAX_RAEE_STATUS." ptrs on (p.products_id=ptrs.products_id) left join ".TABLE_PWS_TAX_RAEE." ptr on (ptrs.pws_tax_raee_id=ptr.pws_tax_raee_id)";
            	$products_query_raw .= " left join ".TABLE_PRODUCTS_TO_CATEGORIES." pc on (p.products_id = pc.products_id ) where pc.categories_id = '" . $current_category_id . "'  $sort_by ";
												}
											}
											//// page splitter and display each products info
											$products_split = new splitPageResults($split_page, MAX_DISPLAY_ROW_BY_PAGE, $products_query_raw, $products_query_numrows);
											$products_query = tep_db_query($products_query_raw);
											while ($_products = tep_db_fetch_array($products_query)) {
												$products[] = $_products;
												$list_of_products_ids[] = $_products['products_id'];
											}
											if (tep_not_null($list_of_products_ids)) {
												if ($show_cg_discounts) {
													//PWS bof
													if (!is_null($plugin_cgroups)){
														 // print "ok siamo qui" .$products[$x]['products_price'];
														// exit;
														
														for ($x = 0, $n=sizeof($list_of_products_ids); $x<$n; $x++){
															$products[$x]['cg_price_in_db'] = $plugin_cgroups->hasGroupDiscount($products[$x]['products_id'],$customers_group_id);
															$products[$x]['products_price'] = $plugin_cgroups->getDiscountPercentageByGroup($products[$x]['products_id'],$customers_group_id);
															// print '<br>products_price' . $products[$x]['products_price'];
															// print 'cg_price_in_db' . $products[$x]['cg_price_in_db'];
															if ($products[$x]['products_price']==0.0){
																$products[$x]['products_price']='0.0';
															}
															// echo "Sconto:".$products[$x]['products_price'].'<br>';
														}
													}else{
														$pg_query = tep_db_query("select pg.products_id, customers_group_discount as cg_discount from " . TABLE_PRODUCTS_GROUPS . " pg where products_id in ('".implode("','",$list_of_products_ids)."') and pg.customers_group_id = '".$customers_group_id."' ");
														while ($pg_array = tep_db_fetch_array($pg_query)) {
															$new_prices[] = array ('products_id' => $pg_array['products_id'], 'cg_discount' => $pg_array['cg_discount']);
														}

														for ($x = 0; $x < count($list_of_products_ids); $x++) {
															// delete products_price (retail) first
															$products[$x]['products_price'] = '';
															// need to know whether a customer group price is in the table products_groups or not
															// (for choosing update or insert)
															$products[$x]['cg_price_in_db'] = 'no';
															// replace products prices with those from customers_group table
															if(!empty($new_prices)) {
																for ($i = 0; $i < count($new_prices); $i++) {
																	if ($products[$x]['products_id'] == $new_prices[$i]['products_id'] ) {
																		//$products[$x]['products_price'] = $new_prices[$i]['products_price'];
																		$products[$x]['products_price']=$new_prices[$i]['cg_discount'];
																		$products[$x]['cg_price_in_db'] = 'yes';
																	}
																} // end for ($i = 0; $i < count($new_prices); $i++)
															} // end if(!empty($new_prices))

														} // end for ($x = 0; $x < count($list_of_products_ids); $x++)
													} // end if (!is_null($plugin_cgroups))
												} // end if ($show_cg_discounts)
													
												// now make sure we get all the specials_id and specials_prices in one query instead of one by one
												if ($show_cg_discounts) {
													$specials_query = tep_db_query("select products_id, specials_id from " . TABLE_SPECIALS . " where products_id in ('".implode("','",$list_of_products_ids)."') and status = '1' and customers_group_id = '" .$customers_group_id. "'");
												} else {
													$specials_query = tep_db_query("select products_id, specials_id from " . TABLE_SPECIALS . " where products_id in ('".implode("','",$list_of_products_ids)."') and status = '1' and customers_group_id = '0'");
												}
												while ($specials_array = tep_db_fetch_array($specials_query)) {
													$new_s_prices[] = array ('products_id' => $specials_array['products_id'], 'specials_id' => $specials_array['specials_id']);
												}
												// @note: Attenzione: questa riga l'ho messa io. Se è specificato un gruppo cliente, non si mostrano più i prezzi, ma le percentuali di sconto
												if (!$show_cg_discounts){
													// put in the specials id's
													for ($x = 0; $x < count($list_of_products_ids); $x++) {
														// make sure a value for special price and specials_id is added
														$products[$x]['specials_id'] = '';
														if(!empty($new_s_prices)) {
															for ($i = 0; $i < count($new_s_prices); $i++) {
																if ($products[$x]['products_id'] == $new_s_prices[$i]['products_id'] ) {
																	$products[$x]['specials_id'] = $new_s_prices[$i]['specials_id'];
																}
															} // end for ($i = 0; $i < count($new_prices); $i++)
														} // end if(!empty($new_s_prices))
													} // end ($x = 0; $x < count($list_of_products_ids); $x++)
												} // end if (!(isset($customers_group_id) && $customers_group_id != '0' && $customers_group_id != '')){
													
												// debug:   echo '<pre>products array'; print_r($products);
												
												for ($x = 0; $x < count($list_of_products_ids); $x++) {
													$rows++;
													if (strlen($rows) < 2) {
														$rows = '0' . $rows;
													}
													//// check for global add value or rates, calcul and round values rates
													$GLOBALS['pws_prices']->admin_side=false;
													if ($show_cg_discounts){
														$price = $products[$x]['products_price'];
													}else{
														if ($HTTP_POST_VARS['spec_price']){
															$flag_spec = 'true' ;
															if (substr($HTTP_POST_VARS['spec_price'],-1) == '%') {
																if($HTTP_POST_VARS['marge'] && substr($HTTP_POST_VARS['spec_price'],0,1) != '-'){
																	$valeur = (1 - (ereg_replace("%", "", $HTTP_POST_VARS['spec_price']) / 100));
																	$price = sprintf("%01.2f", round($products[$x]['products_price'] / $valeur,2));
																}else{
	                $price = sprintf("%01.2f", round($products[$x]['products_price'] + (($spec_price / 100) * $products[$x]['products_price']),2));
																}
															} else {
																$price = sprintf("%01.2f", round($products[$x]['products_price'] + $spec_price,2));
															}
														} else {
															$price = $products[$x]['products_price'] ;
														}

														//// Check Tax_rate for displaying TTC
														$tax_query = tep_db_query("select r.tax_rate, c.tax_class_title from " . TABLE_TAX_RATES . " r, " . TABLE_TAX_CLASS . " c where r.tax_class_id=" . $products[$x]['products_tax_class_id'] . " and c.tax_class_id=" . $products[$x]['products_tax_class_id']);
														$tax_rate = tep_db_fetch_array($tax_query);
														if($tax_rate['tax_rate'] == ''){
															$tax_rate['tax_rate'] = 0;
														}
													}
													// SPPC v1.0: added && DISPLAY_MANUFACTURER == 'true'
													if (MODIFY_MANUFACTURER == 'false' && DISPLAY_MANUFACTURER == 'true') {
														$manufacturer_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id=" . $products[$x]['manufacturers_id']);
														// mixing of global manufacturer and local manufacturer in original quick_updates
														// change original $manufacturer to another variable
														$products_manufacturer = tep_db_fetch_array($manufacturer_query);
													}
													//// display infos per row
													if ($show_cg_discounts){
														echo '<tr class="dataTableRow">';
													}else{
														if($flag_spec){
															echo '<tr class="dataTableRow" onmouseover="';
															if(DISPLAY_TVA_OVER == 'true'){
																echo 'display_ttc(\'display\', ' . $price . ', ' . $tax_rate['tax_rate'] . ');';
															}
															echo 'this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="';
															if(DISPLAY_TVA_OVER == 'true'){
																echo 'display_ttc(\'delete\');';
															}
															echo 'this.className=\'dataTableRow\'">';
														}else{
															echo '<tr class="dataTableRow" onmouseover="';
															if(DISPLAY_TVA_OVER == 'true'){
																echo 'display_ttc(\'display\', ' . $products[$x]['products_price'] . ', ' . $tax_rate['tax_rate'] . ');';
															}
															echo 'this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="';
															if(DISPLAY_TVA_OVER == 'true'){
																echo 'display_ttc(\'delete\', \'\', \'\', 0);';
															}
															echo 'this.className=\'dataTableRow\'">';
														}
													}
													echo "<td class=\"smallText\" align=\"center\">&nbsp;&nbsp;<input id=\"product_select[$x]\" type=\"checkbox\" size=\"8\" name=\"product_selected[".$products[$x]['products_id']."]\" value=\"1\"".(isset($product_selected[$products[$x]['products_id']])?'checked':'')." onclick=\"count_selected_products()\" onchange=\"count_selected_products()\"></td>\n";
													if(DISPLAY_MODEL == 'true'){
														if(MODIFY_MODEL == 'true')
														echo "<td class=\"smallText\" align=\"center\"><input type=\"text\" size=\"8\" name=\"product_new_model[".$products[$x]['products_id']."]\" value=\"".$products[$x]['products_model']."\"></td>\n";
														else
														echo "<td class=\"smallText\" align=\"left\">" . $products[$x]['products_model'] . "</td>\n";
													}else{
														echo "<td class=\"smallText\" align=\"left\">";
													}
													if(MODIFY_NAME == 'true'){
														echo "<td class=\"smallText\" align=\"center\"><input type=\"text\" size=\"30\" name=\"product_new_name[".$products[$x]['products_id']."]\" value=\"".str_replace("\"","&quot;",$products[$x]['products_name'])."\"></td>\n";
													}else{
														echo "<td class=\"smallText\" align=\"left\">".$products[$x]['products_name']."</td>\n";
													}
													//// Product status radio button
													if(DISPLAY_STATUT == 'true'){
														if ($products[$x]['products_status'] == '1') {
															echo "<td class=\"smallText\" align=\"center\"><input  type=\"radio\" name=\"product_new_status[".$products[$x]['products_id']."]\" value=\"0\" ><input type=\"radio\" name=\"product_new_status[".$products[$x]['products_id']."]\" value=\"1\" checked ></td>\n";
														} else {
															echo "<td class=\"smallText\" align=\"center\"><input type=\"radio\" style=\"background-color: #EEEEEE\" name=\"product_new_status[".$products[$x]['products_id']."]\" value=\"0\" checked ><input type=\"radio\" style=\"background-color: #EEEEEE\" name=\"product_new_status[".$products[$x]['products_id']."]\" value=\"1\"></td>\n";
														}
													}else{
														echo "<td class=\"smallText\" align=\"center\"></td>";
													}
													if($plugin_shopwindow_installed == 'true'){
														if ($products[$x]['products_shopwindow'] == '1') {
															echo "<td class=\"smallText\" align=\"center\"><input  type=\"radio\" name=\"product_new_shopwindow[".$products[$x]['products_id']."]\" value=\"0\" ><input type=\"radio\" name=\"product_new_shopwindow[".$products[$x]['products_id']."]\" value=\"1\" checked ></td>\n";
														} else {
															echo "<td class=\"smallText\" align=\"center\"><input type=\"radio\" style=\"background-color: #EEEEEE\" name=\"product_new_shopwindow[".$products[$x]['products_id']."]\" value=\"0\" checked ><input type=\"radio\" style=\"background-color: #EEEEEE\" name=\"product_new_shopwindow[".$products[$x]['products_id']."]\" value=\"1\"></td>\n";
														}
													}else{
														echo "<td class=\"smallText\" align=\"center\"></td>";
													}
													if($plugin_makeoffer_installed == 'true'){
														if ($products[$x]['products_makeoffer'] == '1') {
															echo "<td class=\"smallText\" align=\"center\"><input  type=\"radio\" name=\"product_new_makeoffer[".$products[$x]['products_id']."]\" value=\"0\" ><input type=\"radio\" name=\"product_new_makeoffer[".$products[$x]['products_id']."]\" value=\"1\" checked ></td>\n";
														} else {
															echo "<td class=\"smallText\" align=\"center\"><input type=\"radio\" style=\"background-color: #EEEEEE\" name=\"product_new_makeoffer[".$products[$x]['products_id']."]\" value=\"0\" checked ><input type=\"radio\" style=\"background-color: #EEEEEE\" name=\"product_new_makeoffer[".$products[$x]['products_id']."]\" value=\"1\"></td>\n";
														}
													}else{
														echo "<td class=\"smallText\" align=\"center\"></td>";
													}													
													if(DISPLAY_WEIGHT == 'true'){
														echo "<td class=\"smallText\" align=\"center\"><input type=\"text\" size=\"5\" name=\"product_new_weight[".$products[$x]['products_id']."]\" value=\"".$products[$x]['products_weight']."\"></td>\n";
													}else{
														echo "<td class=\"smallText\" align=\"center\"></td>";
													}
													if(DISPLAY_QUANTITY == 'true'){
														echo "<td class=\"smallText\" align=\"center\"><input type=\"text\" size=\"3\" name=\"product_new_quantity[".$products[$x]['products_id']."]\" value=\"".$products[$x]['products_quantity']."\"></td>\n";
													}else{
														echo "<td class=\"smallText\" align=\"center\"></td>";
													}
													if(DISPLAY_IMAGE == 'true'){
														echo "<td class=\"smallText\" align=\"center\"><input type=\"text\" size=\"8\" name=\"product_new_image[".$products[$x]['products_id']."]\" value=\"".$products[$x]['products_image']."\"></td>\n";
													}else{
														echo "<td class=\"smallText\" align=\"center\"></td>";
													}
													if(DISPLAY_MANUFACTURER == 'true'){
														if(MODIFY_MANUFACTURER == 'true'){
															echo "<td class=\"smallText\" align=\"center\">".tep_draw_pull_down_menu("product_new_manufacturer[".$products[$x]['products_id']."]", $manufacturers_array, $products[$x]['manufacturers_id'])."</td>\n";
														}else{
															echo "<td class=\"smallText\" align=\"center\">" . $products_manufacturer['manufacturers_name'] . "</td>";
														}
													}else{
														echo "<td class=\"smallText\" align=\"center\"></td>";
													}
													if(DISPLAY_PRICE_COMMISSION == 'true' && $plugin_purchase_installed)
													echo "<td class=\"smallText\" align=\"center\"><input type=\"text\" size=\"5\" name=\"product_new_price_commission[".$products[$x]['products_id']."]\" value=\"".$products[$x]['pws_purchase_price_commission']."\"></td>\n";
													if($plugin_clothing_installed){
														if (PWS_CLOTHING_QU_DISPLAY_GENDER=='true'){
															echo "<td class=\"smallText\" align=\"center\">".$plugin_clothing->getGenderPullDownMenu('product_new_gender['.$products[$x]['products_id'].']',$products[$x]['products_gender'],false)."</td>\n";
														}
														if (PWS_CLOTHING_QU_DISPLAY_SEASON=='true'){
															echo "<td class=\"smallText\" align=\"center\">".$plugin_clothing->getSeasonPullDownMenu('product_new_season['.$products[$x]['products_id'].']',$products[$x]['products_season'],false)."</td>\n";
														}
													}
													if(DISPLAY_TAX_RAEE == 'true' && $plugin_tax_raee_installed){
														if ($flag_spec_raee == 'true') {
															echo "<td class=\"smallText\" align=\"left\">".$plugin_tax_raee->getTaxRaeeDropDownMenu("product_new_tax_raee_id[".$products[$x]['products_id']."]",$HTTP_POST_VARS['spec_raee_class']). tep_draw_checkbox_field('update_raee['. $products[$x]['products_id'] .']','yes','checked','no')."</td>\n";
														} else {
															echo "<td class=\"smallText\" align=\"left\">".$plugin_tax_raee->getTaxRaeeDropDownMenu("product_new_tax_raee_id[".$products[$x]['products_id']."]",$products[$x]['pws_tax_raee_id']).tep_draw_hidden_field('update_raee['.$products[$x]['products_id'].']','yes')."</td>\n";
														}
													}
													//// get the specials products list
													/*   deleted code */
													//// check specials
													//  original: if ( in_array($products[$x]['products_id'],$specials_array)) {
													///////////////////////////////////
													// Visualizzazione del prezzo
													if ($show_cg_discounts){
														// $price corrisponde alla percentuale di sconto per il gruppo
														$pws_prices->admin_side=false;
														echo "<td class=\"smallText\" align=\"left\">&nbsp;&nbsp;&nbsp;&nbsp;";
														echo $pws_prices->formatPrice($pws_prices->getFirstPrice($products[$x]['products_id']),$products[$x]['products_id'],true) ."</td>\n";
														echo "<td class=\"smallText\" align=\"left\">&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"text\" size=\"8\" name=\"product_new_price[". $products[$x]['products_id'] ."]\" ";
														echo " value=\"".$price ."\"> %".tep_draw_hidden_field('update_price['.$products[$x]['products_id'].']','yes'). "</td>\n";
														$pws_prices->admin_side=true;
													}else{
														if (tep_not_null($products[$x]['specials_id'])) {
															/* deleted code */
															echo "<td class=\"smallText\" align=\"left\">&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"text\" size=\"8\" name=\"product_new_price[".$products[$x]['products_id']."]\" value=\"".$products[$x]['products_price']."\" disabled >&nbsp;<a target=blank href=\"".tep_href_link (FILENAME_SPECIALS, 'sID='.$products[$x]['specials_id']).'&action=edit'."\">". tep_image(DIR_WS_IMAGES . 'icon_info.gif', TEXT_SPECIALS_PRODUCTS) ."</a></td>\n";
														} else {
															if ($flag_spec == 'true') {
																echo "<td class=\"smallText\" align=\"left\">&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"text\" size=\"8\" name=\"product_new_price[".$products[$x]['products_id']."]\" ";
																if(DISPLAY_TVA_UP == 'true'){
																	echo "onKeyUp=\"display_ttc('keyup', this.value" . ", " . $tax_rate['tax_rate'] . ", 1);\"";
																}
																echo " value=\"".$price ."\">". tep_draw_checkbox_field('update_price['. $products[$x]['products_id'] .']','yes','checked','no')."</td>\n";
															} else {
																echo "<td class=\"smallText\" align=\"left\">&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"text\" size=\"8\" name=\"product_new_price[". $products[$x]['products_id'] ."]\" ";
																if(DISPLAY_TVA_UP == 'true'){
																	echo "onKeyUp=\"display_ttc('keyup', this.value" . ", " . $tax_rate['tax_rate'] . ", 1);\"";
																}
																echo " value=\"".$price ."\">".tep_draw_hidden_field('update_price['.$products[$x]['products_id'].']','yes'). "</td>\n";
															}
														} // end if-else (tep_not_null($products[$x]['specials_id']))
													}
													///////////////////////////////////
													// Visualizzazione delle tasse
													if ($show_cg_discounts){
														$pws_prices->admin_side=false;
														echo "<td class=\"smallText\" align=\"left\">".$pws_prices->formatPrice($pws_prices->getLastPrice($products[$x]['products_id']),$products[$x]['products_id'],true)."</td>";
														$pws_prices->admin_side=true;
													}else{
														if (DISPLAY_TAX == 'true') {
															if (MODIFY_TAX == 'true') {
																echo "<td class=\"smallText\" align=\"left\">". tep_draw_pull_down_menu("product_new_tax[". $products[$x]['products_id'] ."]", $tax_class_array, $products[$x]['products_tax_class_id'])."</td>\n";
															} else {
																echo "<td class=\"smallText\" align=\"left\">" . $tax_rate['tax_class_title'] . "</td>";
															} // end if-else (MODIFY_TAX == 'true')
														}  else {
															echo "<td class=\"smallText\" align=\"center\"></td>";
														}
													}
													//// links to preview or full edit
													if (DISPLAY_PREVIEW == 'true') {
														echo "<td class=\"smallText\" align=\"left\"><a href=\"". tep_href_link (FILENAME_CATEGORIES, 'pID='. $products[$x]['products_id'] .'&action=new_product_preview&read=only&sort_by='. $sort_by .'&page='. $split_page .'&origin='. $origin)."\">". tep_image(DIR_WS_IMAGES . 'icon_info.gif', TEXT_IMAGE_PREVIEW) ."</a></td>\n";
													} // end if(DISPLAY_PREVIEW == 'true')
													if (DISPLAY_EDIT == 'true') {
														echo "<td class=\"smallText\" align=\"left\"><a href=\"". tep_href_link (FILENAME_CATEGORIES, 'pID='. $products[$x]['products_id'] .'&cPath='. $categories_products[0] .'&action=new_product')."\">". tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', TEXT_IMAGE_SWITCH_EDIT) ."</a></td>\n";
													} // end if (DISPLAY_EDIT == 'true')
													//// Hidden parameters for cache old values
													if (MODIFY_NAME == 'true') {
														echo tep_draw_hidden_field('product_old_name['.$products[$x]['products_id'].'] ',$products[$x]['products_name']);
													} // end if (MODIFY_NAME == 'true')
													if (MODIFY_MODEL == 'true') {
														echo tep_draw_hidden_field('product_old_model['.$products[$x]['products_id'].'] ',$products[$x]['products_model']);
													} // end if (MODIFY_MODEL == 'true')
													echo tep_draw_hidden_field('product_old_status['. $products[$x]['products_id'] .']',$products[$x]['products_status']);
													if ($plugin_shopwindow_installed){
														echo tep_draw_hidden_field('product_old_shopwindow['. $products[$x]['products_id'] .']',$products[$x]['products_shopwindow']);
													}
													if ($plugin_makeoffer_installed){
														echo tep_draw_hidden_field('product_old_makeoffer['. $products[$x]['products_id'] .']',$products[$x]['products_makeoffer']);
													}													
													echo tep_draw_hidden_field('product_old_quantity['. $products[$x]['products_id'] .']',$products[$x]['products_quantity']);
													echo tep_draw_hidden_field('product_old_image['. $products[$x]['products_id'] .']',$products[$x]['products_image']);
													if (MODIFY_MANUFACTURER == 'true') {
														echo tep_draw_hidden_field('product_old_manufacturer['. $products[$x]['products_id'] .']',$products[$x]['manufacturers_id']);
													} // end if (MODIFY_MANUFACTURER == 'true')
													echo tep_draw_hidden_field('product_old_weight['. $products[$x]['products_id'] .']',$products[$x]['products_weight']);
													echo tep_draw_hidden_field('product_old_price_commission['. $products[$x]['products_id'] .']',$products[$x]['pws_purchase_price_commission']);
													if ($plugin_clothing_installed){
														echo tep_draw_hidden_field('product_old_gender['.$products[$x]['products_id'] .']',$products[$x]['products_gender']);
														echo tep_draw_hidden_field('product_old_season['.$products[$x]['products_id'] .']',$products[$x]['products_season']);
													}
													echo tep_draw_hidden_field('product_old_tax_raee_id['. $products[$x]['products_id'] .']',$products[$x]['pws_tax_raee_id']);
													echo tep_draw_hidden_field('product_old_price['. $products[$x]['products_id'] .']',$products[$x]['products_price']);
													echo tep_draw_hidden_field('cg_price_in_db['. $products[$x]['products_id'] .']',$products[$x]['cg_price_in_db']);
													if (MODIFY_TAX == 'true') {
														echo tep_draw_hidden_field('product_old_tax['. $products[$x]['products_id'] .']',$products[$x]['products_tax_class_id']);
													} // end if (MODIFY_TAX == 'true')
												} // end for ($x = 0; $x < count($list_of_products_ids); $x++)
												//// hidden display parameters (only once)
												echo tep_draw_hidden_field( 'row_by_page', $row_by_page);
												echo tep_draw_hidden_field( 'sort_by', $sort_by);
												echo tep_draw_hidden_field( 'page', $split_page);
												if (isset($customers_group_id) && $customers_group_id !='') {
													echo tep_draw_hidden_field( 'customers_group_id', $customers_group_id);
												} else {
													echo tep_draw_hidden_field( 'customers_group_id', '0');
												}

											} // end if (tep_not_null($list_of_products_ids)
											echo "</table>\n";

?>
</td>
</tr>
</table></td>
</tr>
<tr>
<td align="right">
<?php
//// display bottom page buttons
echo '<a href="javascript:window.print()">' . PRINT_TEXT . '</a>&nbsp;&nbsp;';
echo tep_image_submit('button_update.gif', IMAGE_UPDATE);
echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_QUICK_UPDATES,"row_by_page=".$row_by_page."&customers_group_id=" . $customers_group_id . "") . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
?></td>
</tr>
</form>
<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<td class="smallText" valign="top"><?php echo $products_split->display_count($products_query_numrows, MAX_DISPLAY_ROW_BY_PAGE, $split_page, TEXT_DISPLAY_NUMBER_OF_PRODUCTS);  ?></td>
<td class="smallText" align="right"><?php echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_ROW_BY_PAGE, MAX_DISPLAY_PAGE_LINKS, $split_page, '&cPath='. $current_category_id .'&sort_by='.$sort_by . '&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id); ?></td>
</table></td>
</tr>
</table></td>
</tr>
</table></td>
<!-- body_text_eof //-->
</tr>
</table>
<!-- body_eof //-->
</tr>
</table>

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');
/////////////////////////////////////////////////////////////////////////
// Funzioni
// Display the list of the manufacturers
function manufacturers_list(){
	global $manufacturer;

	$manufacturers_query = tep_db_query("select m.manufacturers_id, m.manufacturers_name from " . TABLE_MANUFACTURERS . " m order by m.manufacturers_name ASC");
	$return_string = '<select name="manufacturer" onChange="this.form.submit();">';
	$return_string .= '<option value="' . 0 . '">' . TEXT_ALL_MANUFACTURERS . '</option>';
	while($manufacturers = tep_db_fetch_array($manufacturers_query)){
		$return_string .= '<option value="' . $manufacturers['manufacturers_id'] . '"';
		if($manufacturer && $manufacturers['manufacturers_id'] == $manufacturer) $return_string .= ' SELECTED';
		$return_string .= '>' . $manufacturers['manufacturers_name'] . '</option>';
	}
	$return_string .= '</select>';
	return $return_string;
}
// display the customer groups

function customers_groups_list(){
	global $customers_group_id;

	$customers_group_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " order by customers_group_id");
	$return_string = '<select name="customers_group_id" onChange="this.form.submit();">';
	while($customers_groups = tep_db_fetch_array($customers_group_query)){
		$return_string .= '<option value="' . $customers_groups['customers_group_id'] . '"';
		if($customers_group_id && $customers_groups['customers_group_id'] == $customers_group_id) $return_string .= ' SELECTED';
		$return_string .= '>' . $customers_groups['customers_group_name'] . '</option>';
	}
	$return_string .= '</select>';
	return $return_string;
}
function move_product($products_id,$new_parent_id){
	$duplicate_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$new_parent_id . "'");
	$duplicate_check = tep_db_fetch_array($duplicate_check_query);
	if ($duplicate_check['total'] < 1) {
		tep_db_query("update " . TABLE_PRODUCTS_TO_CATEGORIES . " set categories_id = '" . (int)$new_parent_id . "' where products_id = '" . (int)$products_id . "' limit 1");
		return true;
	}else{
		return false;
	}
}
function copy_product($pid,$cid){
	global $products_id,$categories_id,$dup_products_id,$pws_engine,$pws_prices;
	$products_id=$pid;
	$categories_id=$cid;
	
	$product_query = tep_db_query("select products_quantity, products_model, products_image, products_price, products_date_available, products_weight, products_tax_class_id, manufacturers_id from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
	$product = tep_db_fetch_array($product_query);

//SEO	tep_db_query("insert into " . TABLE_PRODUCTS . " (products_quantity, products_model,products_image, products_price, products_date_added, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id) values ('" . tep_db_input($product['products_quantity']) . "', '" . tep_db_input($product['products_model']) . "', '" . tep_db_input($product['products_image']) . "', '" . tep_db_input($product['products_price']) . "',  now(), '" . tep_db_input($product['products_date_available']) . "', '" . tep_db_input($product['products_weight']) . "', '0', '" . (int)$product['products_tax_class_id'] . "', '" . (int)$product['manufacturers_id'] . "')");
	tep_db_query("insert into " . TABLE_PRODUCTS . " (products_quantity, products_model,products_image, products_price, products_date_added, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id) values ('" . tep_db_input($product['products_quantity']) . "', '" . tep_db_input($product['products_model']) . "', '" . tep_db_input($product['products_image']) . "', '" . tep_db_input($product['products_price']) . "',  now(), " . (empty($product['products_date_available']) ? "null" : "'" . tep_db_input($product['products_date_available']) . "'") . ", '" . tep_db_input($product['products_weight']) . "', '0', '" . (int)$product['products_tax_class_id'] . "', '" . (int)$product['manufacturers_id'] . "')");
	$dup_products_id = tep_db_insert_id();

//SEO	$description_query = tep_db_query("select language_id, products_name, products_description, products_url from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$products_id . "'");
	$description_query = tep_db_query("select language_id, products_name, products_seo_url, products_description, products_url from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$products_id . "'");
	while ($description = tep_db_fetch_array($description_query)) {
//SEO	tep_db_query("insert into " . TABLE_PRODUCTS_DESCRIPTION . " (products_id, language_id, products_name, products_description, products_url, products_viewed) values ('" . (int)$dup_products_id . "', '" . (int)$description['language_id'] . "', '" . tep_db_input($description['products_name']) . "', '" . tep_db_input($description['products_description']) . "', '" . tep_db_input($description['products_url']) . "', '0')");
		tep_db_query("insert into " . TABLE_PRODUCTS_DESCRIPTION . " (products_id, language_id, products_name, products_seo_url, products_description, products_url, products_viewed) values ('" . (int)$dup_products_id . "', '" . (int)$description['language_id'] . "', '" . tep_db_input($description['products_name']) . "', '" . tep_db_input($description['products_seo_url']) . "','" . tep_db_input($description['products_description']) . "', '" . tep_db_input($description['products_url']) . "', '0')");
	}

	tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$dup_products_id . "', '" . (int)$categories_id . "')");
	if (isset($GLOBALS['pws_engine'])){
		$pws_engine->triggerHook('ADMIN_COPY_PRODUCT');
	}
	if (isset($GLOBALS['pws_prices'])){
		$pws_prices->adminCopyProduct($products_id,$dup_products_id);
	}
	return true;	
}
function link_product($products_id,$categories_id){
	$check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$categories_id . "'");
	$check = tep_db_fetch_array($check_query);
	if ($check['total'] < '1') {
		tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$products_id . "', '" . (int)$categories_id . "')");
		return true;
	}else{
		return false;
	}
}
function update_products(){
	global $HTTP_POST_VARS,$_POST,$_GET,$HTTP_GET_VARS,$messageStack;
	global $plugin_purchase_installed,$plugin_tax_raee_installed,$plugin_clothing_installed;
	global $plugin_cgroups,$languages_id;
	$count_update=0;
	$item_updated = array();

	if($HTTP_POST_VARS['product_new_model']){
		foreach($HTTP_POST_VARS['product_new_model'] as $id => $new_model) {
			// print_r($HTTP_POST_VARS);
			
			if (trim($HTTP_POST_VARS['product_new_model'][$id]) !== trim($HTTP_POST_VARS['product_old_model'][$id])) {
				$count_update++;
				$item_updated[$id] = 'updated';
				tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET products_model='" . $new_model . "', products_last_modified=now() WHERE products_id='" . $id . "'");
			}
		}
	}
	if($HTTP_POST_VARS['product_new_name']){
		foreach($HTTP_POST_VARS['product_new_name'] as $id => $new_name) {
			if (trim($HTTP_POST_VARS['product_new_name'][$id]) != trim($HTTP_POST_VARS['product_old_name'][$id])) {
				$count_update++;
				$item_updated[$id] = 'updated';
				tep_db_query("UPDATE " . TABLE_PRODUCTS_DESCRIPTION . " SET products_name='" . $new_name . "' WHERE products_id=$id and language_id=" . $languages_id);
				tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET products_last_modified=now() WHERE products_id='" . $id . "'");
			}
		}
	}
	if ($plugin_purchase_installed){
		if($HTTP_POST_VARS['product_new_price_commission']){
			foreach($HTTP_POST_VARS['product_new_price_commission'] as $id => $new_price_commission) {
				if ($HTTP_POST_VARS['product_new_price_commission'][$id] != $HTTP_POST_VARS['product_old_price_commission'][$id]) {
					$count_update++;
					$item_updated[$id] = 'updated';
					$status=$new_price_commission!=PWS_PRICES_PURCHASE_PRICE_DEFAULT_COMMISSION? '1':'0';
					tep_db_query("UPDATE " . TABLE_PWS_PURCHASE_PRICE . " SET pws_purchase_price_commission='$new_price_commission', pws_purchase_price_status='$status' WHERE products_id='$id'");
				}
			}
		}
	}
	if ($plugin_tax_raee_installed){
		if($HTTP_POST_VARS['product_new_tax_raee_id']){
			foreach($HTTP_POST_VARS['product_new_tax_raee_id'] as $id => $new_tax_raee_id) {
				if ($HTTP_POST_VARS['product_new_tax_raee_id'][$id] != $HTTP_POST_VARS['product_old_tax_raee_id'][$id] && $HTTP_POST_VARS['update_raee'][$id] == 'yes') {
					$count_update++;
					$item_updated[$id] = 'updated';
					$status=$new_tax_raee_id!=0 ? '1':'0';
					tep_db_query("UPDATE " . TABLE_PWS_TAX_RAEE_STATUS . " SET pws_tax_raee_id='$new_tax_raee_id', pws_tax_raee_status='$status' WHERE products_id='$id'");
				}
			}
		}
	}
	if ($plugin_clothing_installed){
		if($HTTP_POST_VARS['product_new_gender']){
			foreach($HTTP_POST_VARS['product_new_gender'] as $id => $new_products_gender) {
				if ($HTTP_POST_VARS['product_new_gender'][$id] != $HTTP_POST_VARS['product_old_gender'][$id]) {
					$count_update++;
					$item_updated[$id] = 'updated';
					tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET products_gender='$new_products_gender' WHERE products_id='$id'");
				}
			}
		}
		if($HTTP_POST_VARS['product_new_season']){
			foreach($HTTP_POST_VARS['product_new_season'] as $id => $new_products_season) {
				if ($HTTP_POST_VARS['product_new_season'][$id] != $HTTP_POST_VARS['product_old_season'][$id]) {
					$count_update++;
					$item_updated[$id] = 'updated';
					tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET products_season='$new_products_season' WHERE products_id='$id'");
				}
			}
		}
	}
	if($HTTP_POST_VARS['product_new_price']){
		foreach($HTTP_POST_VARS['product_new_price'] as $id => $new_price) {
			if ($HTTP_POST_VARS['product_new_price'][$id] != $HTTP_POST_VARS['product_old_price'][$id] && $HTTP_POST_VARS['update_price'][$id] == 'yes') {
				$count_update++;
				$item_updated[$id] = 'updated';
				if ($_POST['customers_group_id'] == '0') {
					tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET products_price='" . $new_price . "', products_last_modified=now() WHERE products_id='" . $id . "'");
				} else { // se è impostato il gruppo cliente lavora sulla percentuale di sconto
					if (!is_null($plugin_cgroups)){
						//print $_POST['customers_group_id'];
						//exit;
						$plugin_cgroups->adminSetProductGroupDiscount($id,$_POST['customers_group_id'],$new_price);
					}else if (false){ // condizione mai vera
						if ($_POST['cg_price_in_db'][$id] == 'yes') {
							if (trim($_POST['product_new_price'][$id]) == '') {
								tep_db_query("DELETE FROM " . TABLE_PRODUCTS_GROUPS . " WHERE products_id='" . $id . "' AND customers_group_id = '" . $_POST['customers_group_id'] ."'");
							} else {
								tep_db_query("UPDATE " . TABLE_PRODUCTS_GROUPS . " SET customers_group_price='" . $new_price . "' WHERE products_id='" . $id . "' AND customers_group_id = '" . $_POST['customers_group_id'] ."'");
							}
						} elseif ($_POST['cg_price_in_db'][$id] == 'no') {
							tep_db_query("INSERT INTO " . TABLE_PRODUCTS_GROUPS . " SET products_id='" . $id . "', customers_group_price='" . $new_price . "', customers_group_id = '" . $_POST['customers_group_id'] ."'");
						}
					}
				} // end if-else ($_POST['customers_group_id'] == '0')
			}
		}
	}
	if($HTTP_POST_VARS['product_new_weight']){
		foreach($HTTP_POST_VARS['product_new_weight'] as $id => $new_weight) {
			if ($HTTP_POST_VARS['product_new_weight'][$id] != $HTTP_POST_VARS['product_old_weight'][$id]) {
				$count_update++;
				$item_updated[$id] = 'updated';
				tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET products_weight='" . $new_weight . "', products_last_modified=now() WHERE products_id='" . $id . "'");
			}
		}
	}
	if($HTTP_POST_VARS['product_new_quantity']){
		foreach($HTTP_POST_VARS['product_new_quantity'] as $id => $new_quantity) {
			if ($HTTP_POST_VARS['product_new_quantity'][$id] != $HTTP_POST_VARS['product_old_quantity'][$id]) {
				$count_update++;
				$item_updated[$id] = 'updated';
				tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET products_quantity='". $new_quantity . "', products_last_modified=now() WHERE products_id='" . $id . "'");
			}
		}
	}
	if($HTTP_POST_VARS['product_new_manufacturer']){
		foreach($HTTP_POST_VARS['product_new_manufacturer'] as $id => $new_manufacturer) {
			if ($HTTP_POST_VARS['product_new_manufacturer'][$id] != $HTTP_POST_VARS['product_old_manufacturer'][$id]) {
				$count_update++;
				$item_updated[$id] = 'updated';
				tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET manufacturers_id='" . $new_manufacturer . "', products_last_modified=now() WHERE products_id='" . $id . "'");
			}
		}
	}
	if($HTTP_POST_VARS['product_new_image']){
		foreach($HTTP_POST_VARS['product_new_image'] as $id => $new_image) {
			if (trim($HTTP_POST_VARS['product_new_image'][$id]) != trim($HTTP_POST_VARS['product_old_image'][$id])) {
				$count_update++;
				$item_updated[$id] = 'updated';
				tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET products_image='" . $new_image . "', products_last_modified=now() WHERE products_id='" . $id . "'");
			}
		}
	}
	if($HTTP_POST_VARS['product_new_status']){
		foreach($HTTP_POST_VARS['product_new_status'] as $id => $new_status) {
			if ($HTTP_POST_VARS['product_new_status'][$id] != $HTTP_POST_VARS['product_old_status'][$id]) {
				$count_update++;
				$item_updated[$id] = 'updated';
				tep_set_product_status($id, $new_status);
				tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET products_last_modified=now() WHERE products_id='" . $id . "'");
			}
		}
	}
	if($HTTP_POST_VARS['product_new_shopwindow']){
		foreach($HTTP_POST_VARS['product_new_shopwindow'] as $id => $new_status) {
			if ($HTTP_POST_VARS['product_new_shopwindow'][$id] != $HTTP_POST_VARS['product_old_shopwindow'][$id]) {
				$count_update++;
				$item_updated[$id] = 'updated';
				tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET products_last_modified=now(),products_shopwindow='$new_status' WHERE products_id='" . $id . "'");

			}
		}
	}
	if($HTTP_POST_VARS['product_new_makeoffer']){
		foreach($HTTP_POST_VARS['product_new_makeoffer'] as $id => $new_status) {
			if ($HTTP_POST_VARS['product_new_makeoffer'][$id] != $HTTP_POST_VARS['product_old_makeoffer'][$id]) {
				$count_update++;
				$item_updated[$id] = 'updated';
				tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET products_last_modified=now(),products_makeoffer='$new_status' WHERE products_id='" . $id . "'");

			}
		}
	}
	if($HTTP_POST_VARS['product_new_tax']){
		foreach($HTTP_POST_VARS['product_new_tax'] as $id => $new_tax_id) {
			if ($HTTP_POST_VARS['product_new_tax'][$id] != $HTTP_POST_VARS['product_old_tax'][$id]) {
				$count_update++;
				$item_updated[$id] = 'updated';
				tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET products_tax_class_id='" . $new_tax_id . "', products_last_modified=now() WHERE products_id='" . $id . "'");
			}
		}
	}
	$count_item = array_count_values($item_updated);
	if ($count_item['updated'] > 0){
		$messageStack->add($count_item['updated'].' '.TEXT_PRODUCTS_UPDATED . " $count_update " . TEXT_QTY_UPDATED, 'success');
		if (USE_CACHE == 'true')
		{
			tep_reset_cache_block('categories');
			tep_reset_cache_block('also_purchased');
			tep_reset_cache_block('manufacturers');
		}
	}
}

?>