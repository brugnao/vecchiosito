<?php
/*
osCommerce 2.2MS2 Contribution

Easy advanced specials

This contribution lets you easily administrate your special offers and discounts.

- products filtering by category (can include subcategory products) and / or manufacturers
- can apply discounts (fixed price or percentual) and / or expiration dates at one time
- flexible inline edit of single product discount: can edit directly the rows of the filterd products list.

---------

Gestione avanzata offerte e sconti

Questa contribution permette di gestire gli sconti e le offerte speciali per categoria / produttore / tutti / singolarmente

- filtraggio dei prodotti per categoria (si possono includere anche i prodotti delle sottocategorie) e/o produttore
- applicazione di offerte con prezzo fisso o sconto percentuale alla lista filtrata
- applicazione della data di scadenza alla lista filtrata
- modifica diretta delle singole offerte: � possibile modificare direttamente le singole righe della lista filtrata

---------

05/07/2007
Eugenio Bonifacio (eugh@libero.it) - Comiso (RG) - Italy

The author is not responsible for any damage caused
by the use of this code and any derivatives of it.
So use it at your own risk.

This code is released under the terms of the GNU/GPL v2
http://www.gnu.org/copyleft/gpl.html

-------
INSTALLATION:

Just copy the following files in the corresponding folder:

/admin/specials.php
/admin/includes/languages/english/specials.php
/admin/includes/languages/italian/specials.php
/admin/includes/languages/italian/images/buttons/*.gif
/admin/includes/languages/english/images/buttons/*.gif
/admin/images/*.gif

WARNING: Starting from v1.3.2 this contribution overwrites the original oscommerce specials administration. So, backup your files first!

-------

CHANGES LOG

v1.3.2 (09/02/2010) Full Package
    - starting from this version the contributions replaces the original oscommerce specials administration. So BACKUP YOUR FILES first!!!
	- UI Improvements
	- Merges all the previous bugfixes, thanks to all contributors
	- Product Name Filtering

v1.3 (11/11/2008) Full Package
	- product name / model sorting added
	- spanish language (Thanks to Denox)

v1.2.1 (10/09/2007) Full Package
	- buttons images changed: their names could have been in conflict with other contribs

v1.2 (10/09/2007) Full package
	- few bug fixes
	- now reflects 100% current currency settings
	- Added Dutch translation, specials thanks to frankschoutens.

v1.1.1 (10/07/2007)
	- Added German translation, specials thanks to Craxx.

v1.1 (09/07/2007)
	- now it manages the tax value according to the 'Display prices with tax' global setting.

v1.0.1 (07/07/2007)
	- few bugs fixed (thanks to Craxx for his feedback, i was using a modified version of osCommerce)

v1.0 (05/07/2007)
	- first release	
*/

require('includes/application_top.php');

require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();

$action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : 'list');

$categories_head = array(
	array(
		'id' => '', 
		'text' => @SPECIALS_ENHANCED_CATEGORIES
	)
);

$categories_list = array_merge($categories_head, tep_get_active_category_tree());

$manufacturers_list = array(
	array(
		'id' => '', 
		'text' => @SPECIALS_ENHANCED_MANUFACTURERS
	)
);

$manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
while ($manufacturers = tep_db_fetch_array($manufacturers_query)) 
{
	$manufacturers_list[] = array(
		'id' => $manufacturers['manufacturers_id'],
		'text' => $manufacturers['manufacturers_name']
	);
}

$sort_fields = array(
	'product_id' => array(
		'text' => '',
		'field' => 'p.products_id'
 	),
	'product_model' => array(
		'text' => @SPECIALS_ENHANCED_TH_MODEL,
		'field' => 'p.products_model'
 	),
	'product_name' => array(
		'text' => @SPECIALS_ENHANCED_TH_PRODUCTS,
		'field' => 'pd.products_name'
 	)
);

$sort_list = array();
foreach($sort_fields as $k => $v) {
	$sort_list[] = array(
		'id' => $k,
		'text' => $v['text']
	);
}

$product_name =  (isset($HTTP_GET_VARS['product_name']) && !empty($HTTP_GET_VARS['product_name']) ? trim($HTTP_GET_VARS['product_name']):null);

$sort_type = (isset($HTTP_GET_VARS['sort_type']) && in_array($HTTP_GET_VARS['sort_type'], array('asc', 'desc')) ? $HTTP_GET_VARS['sort_type'] : 'asc');
$sort = (isset($HTTP_GET_VARS['sort']) && isset($sort_fields[$HTTP_GET_VARS['sort']]['field']) ? $HTTP_GET_VARS['sort'] : 'product_id');
$sort_field = $sort_fields[$sort]['field'] . ($sort != 'product_id' ? ' ' . $sort_type : '');

$category_id = (isset($HTTP_GET_VARS['cPath']) && $HTTP_GET_VARS['cPath'] != '' ? intval($HTTP_GET_VARS['cPath']) : null);
$subcats_flag = (isset($HTTP_GET_VARS['subcats_flag']) && $HTTP_GET_VARS['subcats_flag'] == '1' ? true:false);
$specials_flag = (isset($HTTP_GET_VARS['specials_flag']) && $HTTP_GET_VARS['specials_flag'] == '1' ? true:false);
$manufacturer_id = (isset($HTTP_GET_VARS['manufacturer_id']) && $HTTP_GET_VARS['manufacturer_id'] != '' ? intval($HTTP_GET_VARS['manufacturer_id']) : null);

$current_category_id = (isset($HTTP_GET_VARS['cPath']) && $HTTP_GET_VARS['cPath'] != '' ? intval($HTTP_GET_VARS['cPath']) : '');

$discount_percent = false;
$discount = (isset($HTTP_GET_VARS['discount']) && !empty($HTTP_GET_VARS['discount']) ? trim($HTTP_GET_VARS['discount']):null);
if($discount !== null)
{
	if(preg_match("/^(.*)%$/", $discount))
		$discount_percent = true;

	$discount = floatval(str_replace($currencies->currencies[DEFAULT_CURRENCY]["decimal_point"], ".", $discount));
}

$date = (isset($HTTP_GET_VARS['date']) && !empty($HTTP_GET_VARS['date']) ? trim($HTTP_GET_VARS['date']):null);
if(preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4,4})$/',$date))
{
	$date_array = explode('/',$date);
	$date = strftime("%Y-%m-%d %H:%M:%S", mktime(23, 59, 59, $date_array[1], $date_array[0], $date_array[2]));
}


$page = (isset($HTTP_GET_VARS['page']) && intval($HTTP_GET_VARS['page']) > 0 ? intval($HTTP_GET_VARS['page']):1);

/* FUNCTIONS *********************************************************************** */

/*
	gets the id list of the filtered products
*/
function specials_enhanced_get_all_id()
{
	global $category_id, $subcats_flag, $manufacturer_id, $specials_flag, $product_name, $languages_id;

	$tables = array();
	
	if($specials_flag)
		$tables[] = TABLE_PRODUCTS . ' p INNER JOIN ' . TABLE_SPECIALS . ' s ON p.products_id = s.products_id';
	else
		$tables[] = TABLE_PRODUCTS . ' p LEFT JOIN ' . TABLE_SPECIALS . ' s ON p.products_id = s.products_id';
	
	$clauses = array();

	if ($product_name != null)
	{
	   $tables[] = ' INNER JOIN ' . TABLE_PRODUCTS_DESCRIPTION . ' pd ON p.products_id = pd.products_id';
	   $clauses[] = "pd.products_name LIKE '%" .  $product_name . "%'";
	   $clauses[] = 'pd.language_id = '. (int)$languages_id;
	}
	
	if($category_id !== null && $category_id >= 0)
	{
		$tables[] = 'INNER JOIN ' . TABLE_PRODUCTS_TO_CATEGORIES . ' p2c ON p.products_id = p2c.products_id';

		if($subcats_flag)
		{
			$categories_array = tep_get_category_tree($category_id,'','0','', true);
			$cats = array();
			foreach($categories_array as $cat)
				$cats[] = $cat['id'];
			$clauses[] = "p2c.categories_id IN (" . implode(',',$cats) . ")";
		}
		else
			$clauses[] = "p2c.categories_id = '$category_id'";
	}
	
	if($manufacturer_id !== null)
	{
		$clauses[] = "p.manufacturers_id = '$manufacturer_id'";
	}
	
	$tables_text = implode(' ', $tables);
	
	$clauses_text = '1=1';
	if(count($clauses) > 0)
		$clauses_text = implode(' AND ', $clauses);

	$ids_query = tep_db_query("SELECT p.products_id AS pid, s.specials_id AS sid FROM $tables_text WHERE $clauses_text");
	
	$ids = array();
	while($id = tep_db_fetch_array($ids_query))
		$ids[] = $id;

	return $ids;
}

/*
	gets all the filtered products
*/
function specials_enhanced_get_all_products(&$products_split, &$products_query_numrows)
{
	global $category_id, $subcats_flag, $manufacturer_id, $specials_flag, $languages_id, $page, $sort_field, $product_name;

	$tables = array();
	$clauses = array();

	$fields = 'p.products_id, p.products_status, p.products_price, p.products_tax_class_id, p.products_model, pd.products_name, s.specials_id, s.specials_new_products_price, s.expires_date, s.status';
	
	if($specials_flag)
	{
		$tables[] = TABLE_PRODUCTS . ' p INNER JOIN ' . TABLE_SPECIALS . ' s ON p.products_id = s.products_id INNER JOIN ' . TABLE_PRODUCTS_DESCRIPTION . ' pd ON p.products_id = pd.products_id';
	}
	else
	{
		$tables[] = TABLE_PRODUCTS . ' p LEFT JOIN ' . TABLE_SPECIALS . ' s ON p.products_id = s.products_id INNER JOIN ' . TABLE_PRODUCTS_DESCRIPTION . ' pd ON p.products_id = pd.products_id';
	}
	
	$clauses[] = 'pd.language_id = '. (int)$languages_id . ' AND p.products_status = \'1\'';
	
	if($category_id !== null && $category_id >= 0)
	{
		$tables[] = 'INNER JOIN ' . TABLE_PRODUCTS_TO_CATEGORIES . ' p2c ON p.products_id = p2c.products_id';

		if($subcats_flag)
		{
			$categories_array = tep_get_category_tree($category_id,'','0','', true);
			$cats = array();
			foreach($categories_array as $cat)
				$cats[] = $cat['id'];
			$clauses[] = "p2c.categories_id IN (" . implode(',',$cats) . ")";
		}
		else
			$clauses[] = "p2c.categories_id = '$category_id'";
	}

	if($manufacturer_id !== null && $manufacturer_id > 0)
	{
		$clauses[] = "p.manufacturers_id = '$manufacturer_id'";
	}
	
	if ($product_name != null)
	{
	   $clauses[] = "pd.products_name LIKE '%" .  $product_name . "%'";
	}
	
	$tables_text = implode(' ', $tables);
	
	$clauses_text = '1=1';
	if(count($clauses) > 0)
		$clauses_text = implode(' AND ', $clauses);

	$products_query_text = "select $fields from $tables_text WHERE $clauses_text  ORDER BY $sort_field";
	
	$products_split = new splitPageResults($page, MAX_DISPLAY_SEARCH_RESULTS, $products_query_text, $products_query_numrows);
	$products_query = tep_db_query($products_query_text);
	
	$products = array();
	while ($product = tep_db_fetch_array($products_query))
		$products[] = $product;
	
	return $products;
}

/*
	rewrited tep_set_specials_status, the osCommerce one has a strange behaviour when setting the status to 1
*/
function specials_enhanced_set_status($specials_id, $status)
{
    if ($status == '1') 
	{
		return tep_db_query("update " . TABLE_SPECIALS . " set status = '1', date_status_change = NULL where specials_id = '" . (int)$specials_id . "'");
    } 
	elseif ($status == '0')
	{
      return tep_db_query("update " . TABLE_SPECIALS . " set status = '0', date_status_change = now() where specials_id = '" . (int)$specials_id . "'");
    } 
	else 
	{
		return -1;
    }
}

/*
	Updates a product special offer
*/
function specials_enhanced_update_product($product_id, $discount = null, $discount_percent = false, $date = null)
{
	global  $pws_prices;
	/*
	 $_REQUEST['pws_specials_expires'] 
	 $_REQUEST['pws_specials_status']
	 $_REQUEST['pws_specials_discount']
	 */
	$product_query = tep_db_query("SELECT p.products_id AS id, p.products_price AS price, p.products_tax_class_id AS tax FROM " . TABLE_PRODUCTS . " p WHERE p.products_id = $product_id");
	$product = tep_db_fetch_array($product_query);
	

	if($discount_percent ) // è stato inviato lo sconto in %
	{
		$_REQUEST['pws_specials_discount'] = $discount;
	}
	else 
	{
		if(DISPLAY_PRICE_WITH_TAX == 'true') 
			$_REQUEST['pws_specials_discount'] =  (1 - $discount/1.2/$product['price']) * 100  ;
		else 
			$_REQUEST['pws_specials_discount'] =  (1 - $discount/$product['price']) * 100  ;
	}
	 
	//  è stata inserita la data di scadenza
	if ($date <> null)
		$_REQUEST['pws_specials_expires'] = str_replace('/','-',$_REQUEST['date']);
		
		
	 $_REQUEST['pws_specials_status'] = 1;
	
	 $pws_prices->adminUpdateProduct($product_id);

	return true;
}

/* *********************************************************************** */

if (tep_not_null($action)) 
{
    switch ($action) 
	{
		// Enables/disables the special offer
		case 'setflag':
			specials_enhanced_set_status($HTTP_GET_VARS['id'], $HTTP_GET_VARS['flag']);
			tep_redirect(tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params(array('action','flag','id')), 'NONSSL'));
		break;
		
		// Enables/disables the specials of the filtered products
		case 'setflag_all':
			$specials_flag = 1;
			$ids = specials_enhanced_get_all_id();
		
			foreach($ids as $id)
			{
				if($id['sid'] != null)
					specials_enhanced_set_status($id['sid'], $HTTP_GET_VARS['flag']);
			}

			tep_redirect(tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params(array('action','flag','id')), 'NONSSL'));
		break;
		
		// Lists the filtered products
		case 'list':
			$specials_array = specials_enhanced_get_all_products($products_split, $products_query_numrows);
		break;
		
		// Updates a single product/special offer
		case 'update':
			$id = (isset($HTTP_GET_VARS['id']) ? intval($HTTP_GET_VARS['id']):0);
			
			if($id && $discount !== null)
				specials_enhanced_update_product($id, $discount, $discount_percent, $date);

			tep_redirect(tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params(array('action','id','discount','date')), 'NONSSL'));
		break;

		// Updates all the filtered products/special offers
		case 'update_all':
			if($discount !== null || $date !== null)
			{
				if($discount === null && $date != null)
					$specials_flag = 1;
					
				$ids = specials_enhanced_get_all_id();
				foreach($ids as $id)
					specials_enhanced_update_product($id['pid'], $discount, $discount_percent, $date);
			}

			tep_redirect(tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params(array('action','id','discount','date')), 'NONSSL'));
		break;
		
		// removes a single special offer
		case 'remove':
			$id = (isset($HTTP_GET_VARS['id']) ? intval($HTTP_GET_VARS['id']):0);
			tep_db_query("DELETE FROM " . TABLE_SPECIALS . " WHERE products_id = $id");
			tep_db_query("delete from " . TABLE_PWS_SPECIALS . " where products_id = $id");
			// print_r($pws_prices);
			// exit;
			$pws_prices->adminUpdateProduct($HTTP_GET_VARS['id']);
			tep_redirect(tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params(array('action','id')), 'NONSSL'));
		break;
		
		// removes all the filtered special offers
		case 'remove_all':
			$specials_flag = 1;
			$ids = specials_enhanced_get_all_id();

			foreach($ids as $id)
			{
				if($id['sid'] != null)
				{
					tep_db_query("DELETE FROM " . TABLE_SPECIALS . " WHERE specials_id = $id[sid]");
					tep_db_query("DELETE FROM " . TABLE_PWS_SPECIALS . " WHERE specials_id = $id[sid]");
				}
			}
			tep_redirect(tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params(array('action')), 'NONSSL'));
		break;
    }
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
		<title><?php echo TITLE; ?></title>
		<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
		<script language="javascript" src="includes/general.js"></script>
	</head>
	<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
		<div id="popupcalendar" class="text"></div>
		<!-- header //-->
		<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
		<!-- header_eof //-->

		<!-- body //-->
		<table border="0" width="100%" cellspacing="2" cellpadding="2">
			<tr>
				<td width="<?php echo BOX_WIDTH; ?>" valign="top">
					<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
					<!-- left_navigation //-->
					<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
					<!-- left_navigation_eof //-->
					</table>
				</td>
				<!-- body_text //-->
				<td width="100%" valign="top">
					<table border="0" width="100%" cellspacing="0" cellpadding="2">
						<tr>
							<td width="100%">
								<table border="0" width="100%" cellspacing="0" cellpadding="0">
									<tr>
										<td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
										<td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>
								<form>
									<input name="action" type="hidden" value="list"/>
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td>
												<table>
													<tr>
														<td class="main"><b><?php echo @SPECIALS_ENHANCED_FILTER;?></b></td>
														<td class="main">
														    <?php echo @SPECIALS_ENHANCED_NAME;?>
														    <input name="product_name" type="text" value="<?php echo $product_name; ?>" size="20" maxlength="100"/>
															<?php echo tep_draw_pull_down_menu('cPath', $categories_list, $current_category_id, '');?>
															<?php echo tep_draw_pull_down_menu('manufacturer_id', $manufacturers_list, $current_manufacturer_id);?>
														</td>
														<td><input type="image" src="includes/languages/<?php echo $language;?>/images/buttons/button_specials_enh_apply.gif" onclick="this.form.action.value='list';this.form.submit();" alt="<?php echo @SPECIALS_ENHANCED_LIST; ?>"/></td>
													</tr>
													<tr>
														<td class="main"><b><?php echo @SPECIALS_ENHANCED_ORDERING;?></b></td>
														<td>
															<?php echo tep_draw_pull_down_menu('sort', $sort_list, $sort, '');?>
															<?php echo tep_draw_pull_down_menu('sort_type', array(array('id' => 'asc', 'text' => @SPECIALS_ENHANCED_ASC), array('id' => 'desc', 'text' => @SPECIALS_ENHANCED_DESC)), $sort_type, '');?>
														</td>
														<td></td>
													</tr>
													<tr>
														<td></td>
														<td>
															<input type="checkbox" name="subcats_flag" value="1"<?php echo (isset($HTTP_GET_VARS['subcats_flag']) && $HTTP_GET_VARS['subcats_flag'] == '1' ? 'checked="checked"':'') ?>/><span class="main"><?php echo @SPECIALS_ENHANCED_INCLUDE_SUBCATEGORIES; ?></span>
															<br/>
															<input type="checkbox" name="specials_flag" value="1"<?php echo (isset($HTTP_GET_VARS['specials_flag']) && $HTTP_GET_VARS['specials_flag'] == '1' ? 'checked="checked"':'') ?>/><span class="main"><?php echo @SPECIALS_ENHANCED_ONLY_SPECIALS; ?></span>
														</td>
														<td></td>
													</tr>
												</table>
											</td>
										</tr>
										<tr>
											<td><hr/></td>
										</tr>
										<tr>
											<td>
												<input type="hidden" name="flag" value=""/>
												<table width="100%" cellspacing="0" cellpadding="0">
													<tr>
														<td>
															<table border="0" cellpadding="0" cellspacing="0">
																<tr>
																	<td><span class="main"><?php echo @SPECIALS_ENHANCED_DISCOUNT; ?></span></td>
																	<td><input name="discount" type="text" value="" size="10"/></td>
																	<td></td>
																</tr>
																<tr>
																	<td><span class="main"><?php echo @SPECIALS_ENHANCED_DATE; ?></span></td>
																	<td><input name="date" type="text" value="" size="10" maxlength="10"/></td>
																	<td>&nbsp;&nbsp;<input type="image" src="includes/languages/<?php echo $language;?>/images/buttons/button_specials_enh_apply_discount.gif" onclick="this.form.action.value='update_all';this.form.submit();" alt="<?php echo @SPECIALS_ENHANCED_APPLY_DISCOUNT; ?>"/></td>
																</tr>
																<tr>
																
																</tr>
															</table>
														</td>
														<td align="right" valign="bottom">
															<input type="image" src="includes/languages/<?php echo $language;?>/images/buttons/button_specials_enh_activate.gif" onclick="if(!confirm('<?php echo @SPECIALS_ENHANCED_GENERAL_CONFIRM;?>')) return false;this.form.action.value='setflag_all';this.form.flag.value='1';this.form.submit();" alt="<?php echo @SPECIALS_ENHANCED_ACTIVATE_ALL; ?>"/>
															<input type="image" src="includes/languages/<?php echo $language;?>/images/buttons/button_specials_enh_deactivate.gif" onclick="if(!confirm('<?php echo @SPECIALS_ENHANCED_GENERAL_CONFIRM;?>')) return false;this.form.action.value='setflag_all';this.form.flag.value='0';this.form.submit();" alt="<?php echo @SPECIALS_ENHANCED_DEACTIVATE_ALL; ?>"/>
															&nbsp;
															<input type="image" src="includes/languages/<?php echo $language;?>/images/buttons/button_specials_enh_remove.gif" onclick="if(!confirm('<?php echo @SPECIALS_ENHANCED_GENERAL_CONFIRM;?>')) return false;this.form.action.value='remove_all';this.form.submit();" alt="<?php echo @SPECIALS_ENHANCED_REMOVE_ALL; ?>"/>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</form>
							</td>
						</tr>
						<tr>
							<td>
								<table border="0" width="100%" cellspacing="0" cellpadding="0">
									<tr>
										<td valign="top">
											<table border="0" width="100%" cellspacing="0" cellpadding="2">
												<tr class="dataTableHeadingRow">
													<td width="15%" class="dataTableHeadingContent"><?php echo @SPECIALS_ENHANCED_TH_MODEL; ?></td>
													<td width="10%" class="dataTableHeadingContent"><?php echo @SPECIALS_ENHANCED_TH_PRODUCTS; ?></td>
													<td width="15%" class="dataTableHeadingContent" align="center"><?php echo @SPECIALS_ENHANCED_TH_PRICE . '(' . (DISPLAY_PRICE_WITH_TAX == 'true' ? SPECIALS_ENHANCED_TH_GROSS:SPECIALS_ENHANCED_TH_NET) . ')'; ?></td>
													<td width="15%" class="dataTableHeadingContent" align="center"><?php echo @SPECIALS_ENHANCED_TH_DISCOUNTED_PRICE . '(' . (DISPLAY_PRICE_WITH_TAX == 'true' ? SPECIALS_ENHANCED_TH_GROSS:SPECIALS_ENHANCED_TH_NET) . ') / %'; ?></td>
													<td width="8%" class="dataTableHeadingContent" align="center"><?php echo @SPECIALS_ENHANCED_TH_DISCOUNT_PERCENT; ?></td>
													<td width="15%" class="dataTableHeadingContent" align="center"><?php echo @SPECIALS_ENHANCED_TH_DATE; ?></td>
													<td width="8%" class="dataTableHeadingContent" align="center"><?php echo @SPECIALS_ENHANCED_TH_STATUS; ?></td>
													<td class="dataTableHeadingContent" align="right"><?php echo @SPECIALS_ENHANCED_TH_ACTIONS; ?></td>
												</tr>
												<?php
												foreach($specials_array as $specials) 
												{
													$tax_rate = tep_get_tax_rate_value($specials['products_tax_class_id']);
												?>
												<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">
													<td colspan="8">
														<form>
															<input type="hidden" name="cPath" value="<?php echo $category_id;?>"/>
															<input type="hidden" name="manufacturer_id" value="<?php echo $manufacturer_id;?>"/>
															<input type="hidden" name="subcats_flag" value="<?php echo ($subcats_flag ? '1':'');?>"/>
															<input type="hidden" name="specials_flag" value="<?php echo ($specials_flag ? '1':'');?>"/>
															<input type="hidden" name="sort" value="<?php echo $sort;?>"/>
															<input type="hidden" name="sort_type" value="<?php echo $sort_type;?>"/>
															<input type="hidden" name="page" value="<?php echo $page;?>"/>
															<input type="hidden" name="product_name" value="<?php echo $product_name;?>"/>
															<input type="hidden" name="action" value="update"/>
															<input type="hidden" name="id" value="<?php echo $specials['products_id'];?>"/>
															<table width="100%">
																<tr>
																	<td width="10%" class="dataTableContent"><?php echo $specials['products_model']; ?></td>
																	<td width="15%" class="dataTableContent"><?php echo $specials['products_name']; ?></td>
																	<td width="15%" class="dataTableContent" align="center"><?php echo $currencies->display_price($specials['products_price'], $tax_rate); ?></td>
																	<td width="15%" class="dataTableContent" align="center"><?php echo $currencies->currencies[DEFAULT_CURRENCY]['symbol_left']; ?><input name="discount" style="border:1px solid #ccc;text-align:right" type="text" size="8" value="<?php echo number_format(tep_add_tax($specials['specials_new_products_price'], $tax_rate),intval($currencies->currencies[DEFAULT_CURRENCY]["decimal_places"]), $currencies->currencies[DEFAULT_CURRENCY]["decimal_point"], $currencies->currencies[DEFAULT_CURRENCY]["thousands_point"]);?>"/> <?php echo $currencies->currencies[DEFAULT_CURRENCY]['symbol_right']; ?></td>
																	<td width="8%" class="dataTableContent" align="center"><?php if($specials['specials_new_products_price']){echo number_format(-1*($specials['products_price'] - $specials['specials_new_products_price'])*100/$specials['products_price'], intval($currencies->currencies[DEFAULT_CURRENCY]["decimal_places"]), $currencies->currencies[DEFAULT_CURRENCY]["decimal_point"], $currencies->currencies[DEFAULT_CURRENCY]["thousands_point"]).'%';}else{ echo '---';} ?></td>
																	<td width="15%" class="dataTableContent" align="center"><input name="date" style="border:1px solid #ccc;" type="text" value="<?php echo (!empty($specials['expires_date']) && $specials['expires_date'] != '0000-00-00 00:00:00' ? preg_replace('/([0-9]{4,4})-([0-9]{2,2})-([0-9]{2,2}) ([0-9]{2,2}):([0-9]{2,2}):([0-9]{2,2})/','$3/$2/$1', $specials['expires_date']):''); ?>" size="10" maxlength="10" onfocus="this.select();"/></td>
																	<td width="8%" class="dataTableContent" align="center">
																	<?php
																		if($specials['status'] != null)
																		{
																	      if ($specials['status'] == '1') {
																	        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_SPECIALS, 'action=setflag&flag=0&id=' . $specials['specials_id']. '&' . tep_get_all_get_params(array('id','flag','action','date','discount')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
																	      } else {
																	        echo '<a href="' . tep_href_link(FILENAME_SPECIALS, 'action=setflag&flag=1&id=' . $specials['specials_id'] . '&' . tep_get_all_get_params(array('id','flag','action','date','discount')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
																	      }
																		}
																		else
																			echo '----';
																	?>
																	</td>
																	<td align="right">
																		<input type="image" src="images/button_specials_enh_update.gif" alt="<?php echo @SPECIALS_ENHANCED_UPDATE; ?>" style="border:1px solid #ccc;margin-right:5px;"/><?php if($specials['specials_new_products_price']) { ?><input type="image" src="images/button_specials_enh_remove.gif" alt="<?php echo @SPECIALS_ENHANCED_REMOVE; ?>" onclick="if(!confirm('<?php echo @SPECIALS_ENHANCED_REMOVE_CONFIRM; ?>')) return false;this.form.action.value ='remove';this.form.submit();" style="border:1px solid #ccc;"/><?php }else{?><img src="images/button_specials_enh_no_remove.gif" alt="<?php echo @SPECIALS_ENHANCED_REMOVE; ?>" style="border:1px solid #ccc;"/><?php } ?>
																	</td>
																</tr>
															</table>
														</form>
													</td>
												</tr>
												<?php
												}
												?>
												<tr>
													<td colspan="4">
														<table border="0" width="100%" cellpadding="0"cellspacing="2">
															<tr>
																<td class="smallText" valign="top"><?php echo $products_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $page, TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
																<td class="smallText" align="right"><?php echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $page, tep_get_all_get_params(array('page', 'x', 'y'))); ?></td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
				<!-- body_text_eof //-->
			</tr>
		</table>
		<!-- footer //-->
		<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
		<!-- footer_eof //-->
	</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>