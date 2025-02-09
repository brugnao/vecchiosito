<?php
/*
 $Id: product_info.php,v 1.97 2003/07/01 14:34:54 hpdl Exp $

 osCommerce, Open Source E-Commerce Solutions
 http://www.oscommerce.com

 Copyright (c) 2003 osCommerce

 Released under the GNU General Public License
 */

require('includes/application_top.php');

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);


$product_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
$product_check = tep_db_fetch_array($product_check_query);
// PAYPAL WPP MODIFICATION START
$ec_enabled = tep_paypal_wpp_enabled();

// PAYPAL MODIFICATION END
// BOF Separate Price per Customer
//     if(!tep_session_is_registered('sppc_customer_group_id')) {
//     $customer_group_id = '0';
//     } else {
//      $customer_group_id = $sppc_customer_group_id;
//     }
// EOF Separate Price per Customer

//begin dynamic meta tags query --> aggiunto l'EAN nella tabella prodcts
// scomposizione della query in base ai moduli installati


if ($product_check['total'] >= 1) {

// query standard

$product_select = 'pd.language_id, p.products_id, p.products_onlyshow, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, p.products_weight, pd.products_url, pd.products_youtube_url, p.products_price, p.products_tax_class_id,
	p.products_date_added, p.products_date_available, p.manufacturers_id ,p.vpn, p.EAN';

//modulo esprinet
if (file_exists(DIR_FS_CATALOG . 'distributors/config/esprinet.xml'))
$product_select  .= ' , p.Codice, p.link';

// astaingriglia
$rs_products_fields = tep_db_query("SHOW COLUMNS FROM  " .TABLE_PRODUCTS);
while ($field = tep_db_fetch_array($rs_products_fields))
{
	if ($field['Field'] == 'products_icecat')
	{
		$product_select  .= ', p.products_icecat';
	}
}


$the_product_info_query = tep_db_query("select " . $product_select . " from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "'" . " and pd.language_id ='" .  (int)$languages_id . "'");
$the_product_info = tep_db_fetch_array($the_product_info_query);
$the_product_name = strip_tags ($the_product_info['products_name'], "");
$the_product_description = strip_tags ($the_product_info['products_description'], "");
$the_product_model = strip_tags ($the_product_info['products_model'], "");

$the_manufacturer_query = tep_db_query("select m.manufacturers_id, m.manufacturers_name, m.manufacturers_image from " . TABLE_MANUFACTURERS . " m left join " . TABLE_MANUFACTURERS_INFO . " mi on (m.manufacturers_id = mi.manufacturers_id and mi.languages_id = '" . (int)$languages_id . "'), " . TABLE_PRODUCTS . " p  where p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and p.manufacturers_id = m.manufacturers_id");
$the_manufacturers = tep_db_fetch_array($the_manufacturer_query);
// end dynamic meta tags query -->

// distributors BOF
// codice per mostrare il link esprinet se esiste nella tabella distributors
$distLink = $the_product_info['link'];

// campo per la scheda icecat di astaingriglia
$astaingriglia = $the_product_info['products_icecat'];
$relprod_query="select * from pws_related_products where products_id='$products_id' order by prodrel_order";	
		 $relprod_query=tep_db_query($relprod_query);
		 
// icecat url
if ($the_product_info['EAN'] !='' && ICECAT_SHOPNAME !='') // ha gli EAN ed è abbonato ad icecat
	{	
		// language code da utilizzare per la richiesta 
		$lang_query = tep_db_query("select code from languages where languages_id = '". $languages_id ."'");
		$lang_array = tep_db_fetch_array($lang_query);
		
		// $icecatLink = 'http://prf.icecat.biz/index.cgi?prod_id=' . $the_product_info['vpn'] . ';vendor=' . $the_manufacturers['manufacturers_name'] . ';shopname='. ICECAT_SHOPNAME . '';
		 $icecatLink = 'http://prf.icecat.biz/?shopname='. ICECAT_SHOPNAME .';smi=product;ean_upc=' . urlencode($the_product_info['EAN']) . ';lang='.$lang_array['code'];
	
		if(file_exists(	"cache/".$products_id."_lang".$languages_id. ".html") )
			 $icecatLink = '';
		else 
			 include ("icecat_viewer.php");
	
	}
}

?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<!-- <title><?php echo $the_product_name; ?>: <?php echo TITLE ?></title> -->
<meta name="keywords"
	content="<?php echo TITLE ?>, <?php echo $the_product_name; ?>, <?php echo $the_product_model; ?>, <?php echo $the_manufacturers['manufacturers_name']; ?>">
<meta name="description"
	content="<?php echo $the_product_description . "," . $the_product_name; ?>">

<base
	href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<link rel="stylesheet" href="lightbox.css" type="text/css"
	media="screen" />
	

<?php

// ICECAT {{
if (mysql_num_rows($relprod_query) >= '1')
	require_once(DIR_WS_ICECAT . 'header.php');
// ICECAT }}
// CART MODAL {{
//	require_once(DIR_WS_CART_MODAL . 'header.php');
// CART MODAL }}

?>

</head>
<body
	onload="doLoad('pid=<?=(int)$HTTP_GET_VARS['products_id']?>&languages_id=<?=$languages_id?>',null,'productDesc');"
	marginwidth="0" marginheight="0" topmargin="0" bottommargin="0"
	leftmargin="0" rightmargin="0">

<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php');

?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
	<tr>
		<td width="<?php echo BOX_WIDTH; ?>" valign="top">
		<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0"
			cellpadding="2">
			<!-- left_navigation //-->
			<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
			<!-- left_navigation_eof //-->
		</table>
		</td>
		<!-- body_text //-->
		<td width="100%" valign="top">
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
		<?php
			
		if ($product_check['total'] <= 0) {
			
			?>
			<tr>
				<td><?php new infoBox(array(array('text' => TEXT_PRODUCT_NOT_FOUND))); ?></td>
			</tr>
			<tr>
				<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
			</tr>
			<tr>
				<td>
				<table border="0" width="100%" cellspacing="1" cellpadding="2"
					class="infoBox">
					<tr class="infoBoxContents">
						<td>
						<table width="100%" border="0" cellpadding="2" cellspacing="0">
							<tr>
								<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
								<td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
								<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
							</tr>
						</table>
						</td>
					</tr>
				</table>
				</td>
			</tr>
			<?php
			
		} else {
			
			$product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, pd.products_youtube_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
			$product_info = tep_db_fetch_array($product_info_query);

			tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and language_id = '" . (int)$languages_id . "'");

			$products_price=$pws_prices->getHtmlPriceDiscounts((int)$HTTP_GET_VARS['products_id']);

			if (tep_not_null($product_info['products_model']) && PRODUCT_INFO_MODEL == 'true') {
				$products_name = $product_info['products_name'] . '<br><span class="smallText">[' . $product_info['products_model'] . ']</span>';
			} else {
				$products_name = $product_info['products_name'];
			}
			?>
			<?php
			// START: Extra Fields Contribution v2.0b - mintpeel display fix
			if(file_exists("admin/product_extra_fields.php"))
			{
				$extra_fields_query = tep_db_query("
                      SELECT pef.products_extra_fields_status as status, pef.products_extra_fields_name as name, ptf.products_extra_fields_value as value
                      FROM ". TABLE_PRODUCTS_EXTRA_FIELDS ." pef
             LEFT JOIN  ". TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS ." ptf
            ON ptf.products_extra_fields_id=pef.products_extra_fields_id
            WHERE ptf.products_id=". (int)$HTTP_GET_VARS['products_id'] ." and ptf.products_extra_fields_value<>'' and (pef.languages_id='0' or pef.languages_id='".(int)$languages_id."')
            ORDER BY products_extra_fields_order");

				echo '<tr>
	  <td>
	  <table border="0" width="50%" cellspacing="0" cellpadding="2px">';
				while ($extra_fields = tep_db_fetch_array($extra_fields_query)) {
					if (! $extra_fields['status'])  // show only enabled extra field
					continue;
					echo'<tr><td class="main" align="left" valign="middle"><font size="1" color="#666666"><b>'.$extra_fields['name'].': </b>' . stripslashes($extra_fields['value']).'</font></td></tr>';
				}
				echo' </table>
	  </td>
      </tr>'; 
				// END: Extra Fields Contribution - mintpeel display fix
			}
			?>
			<?
			// AVAILABILITY start
			if (PRODUCT_INFO_AVAILABILITY == 'true')
			{
				if ($product_info['products_date_available']!=''
				&& substr($product_info['products_date_available'],0,10)!='0000-00-00'
				&& date('Y-m-d') < substr($product_info['products_date_available'],0,10))	{
					$image_location=DIR_WS_ICONS.'sem_blue.gif';
					$image_title=TEXT_AVAILABILITY_SCHEDULED . ' ' . tep_date_short($product_info['products_date_available']) ;
				} else if ($product_info['products_quantity']>=PRODUCT_LIST_AVAILABILITY_GREEN)	{
					$image_location=DIR_WS_ICONS.'sem_green.gif';
					$image_title=TEXT_AVAILABILITY_GREEN;
				} else if ($product_info['products_quantity']>=PRODUCT_LIST_AVAILABILITY_YELLOW) {
					$image_location=DIR_WS_ICONS.'sem_yellow.gif';
					$image_title=TEXT_AVAILABILITY_YELLOW;
				} else {
					$image_location=DIR_WS_ICONS.'sem_red.gif';
					$image_title=TEXT_AVAILABILITY_RED;
				}
			}
			// AVAILABILITY stop
			?>

			<tr>
				<td>
				<table border="0" width="100%" align="Center">
					<tr>

						<!--  sezione sinistra per le immagini START -->
						<td width="40%" valign="top"><!--  visualizzazione immagine e multimage se installato -->
						<table border="0" width="100%" cellspacing="0" cellpadding="2"
							align="center" class="infoBox">
							<tr>
								<td align="center" class="smallText"><?php 
								echo tep_output_image($HTTP_GET_VARS['products_id'], PRODUCT_IMAGE_WIDTH, PRODUCT_IMAGE_HEIGHT, 'rel="lightbox[' . $HTTP_GET_VARS['products_id']. ']" target="_blank"');
								//  echo '<a href="' . DIR_WS_IMAGES . $product_info['products_image'] . '" rel="lightbox[' . $HTTP_GET_VARS['products_id']. ']" target="_blank">' . $GLOBALS['pws_html']->getHtmlProductsImage($HTTP_GET_VARS['products_id'], $product_info['products_name'], PRODUCT_IMAGE_WIDTH, PRODUCT_IMAGE_HEIGHT, 'hspace="2" vspace="2"') . '</a>&nbsp;';
								?> <?=$pws_engine->triggerHook('CATALOG_PRODUCT_INFO_SLIDESHOW')?>
								</td>
							</tr>
						</table>
						<!--  link istituzionali --> <? 		
						// NOTIFICHE DEL PRODOTTO
						if (tep_session_is_registered('customer_id')) {
							$check_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and customers_id = '" . (int)$customer_id . "'");
							$check = tep_db_fetch_array($check_query);

							$notification_exists = (($check['count'] > 0) ? true : false);
						} else {
							$notification_exists = false;
						}
						if ($notification_exists == true)
						$link_notify = '<a href="'.tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=notify_remove', $request_type) . '">' . PRODUCT_NOTIFICATIONS_REMOVE  . ' <img border="0" valign="bottom" width="12" height="12" src="' . DIR_WS_ICONS . 'cross.gif"></a>';
						else
						$link_notify = '<a href="'.tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=notify', $request_type) . '">' . PRODUCT_NOTIFICATIONS. ' <img border="0" valign="bottom" width="12" height="12" src="' . DIR_WS_ICONS . 'tick.gif"></a>';


						// invia ad un amico
						$link_tellafriend = '<a href="'.tep_href_link(FILENAME_TELL_A_FRIEND, 'products_id='.$HTTP_GET_VARS['products_id'], 'NONSSL', false) . '">' . BOX_HEADING_TELL_A_FRIEND . ' <img border="0" valign="bottom" width="12" height="12" src="' . DIR_WS_LANGUAGES . $language . '/' . DIR_WS_IMAGES . '/buttons/' . 'button_tell_a_friend.gif"></a>';

						// richiedi info prodotto
						$link_product_info = '<a href="'.tep_href_link('request_product_info.php', 'products_id='.$HTTP_GET_VARS['products_id'], 'NONSSL', false) . '">' . REQUEST_PRODUCT_INFORMATIONS . ' <img border="0" valign="bottom" width="12" height="12" src="' . DIR_WS_LANGUAGES . $language . '/' . DIR_WS_IMAGES . '/buttons/' . 'button_tell_a_friend.gif"></a>';

						if (ENABLE_PRODUCT_GENERIC_LINKS == 'true')
						{

							?>


						<table border="0" width="100%" cellspacing="0" cellpadding="2"
							align="center" class="infoBox">
							<tr>
								<td align="left" class="smallText">
								<ul
									style="list-style-image: url('images/icons/button_prod_utility.gif')">

									<li><? echo $link_notify ?></li>
									<li><? echo $link_tellafriend ?></li>
									<? if (REVIEWS_ENALBED == 'true') { ?>
									<li><? echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $HTTP_GET_VARS['products_id'] . '&reviews_id=' . $random_product['reviews_id']) . '">' . BOX_HEADING_REVIEWS . '</a></li>'?>
									<?} ?>

									<li><? echo $link_product_info ?></li>
									<?
									if (tep_not_null($product_info['products_url'])) {
										?>
									<li class="listItem"><?php echo sprintf(TEXT_MORE_INFORMATION, tep_href_link(FILENAME_REDIRECT, 'goto=' . urlencode($product_info['products_url']).'&action=url', 'NONSSL', true, false)); ?></li>
									<?php
									}
									?>
								
								</ul>

								</td>
							</tr>
						</table>
						<? } ?></td>
						<!--  sezione sinistra per le immagini END -->

						<!--  sezione destra info prodotto sintetiche START -->
						<td width="60%" valign="top"><!-- INIZIO FORM PER AGGIUNTA AL CARRELLO -->
						<?php echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=add_product')); ?>
						<!--  dati riepilogativi del prodotto --> <? if (file_exists(DIR_WS_LANGUAGES . $language .  '/images/buttons/Bollo_Italia.jpg') ) 
						$colspan = "" ;
						else           					 $colspan = "2"; // allarghiamo il td del nome ?>
						<table width="100%" border="0" cellspacing="2" cellpadding="2"
							class="infoBox">
							<tr>
								<td align="left" class="prod-title-caption"
									colspan="<? echo $colspan  ?>"><?php echo $products_name; ?></td>

									<?php if (file_exists(DIR_WS_LANGUAGES . $language .  '/images/buttons/Bollo_Italia.jpg') ) { ?>
								<td align="center" class="prod-title-caption"><?	 echo tep_image_button('Bollo_Italia.jpg', 'Garanzia Italia'); ?>
								</td>
								<? }?>
							</tr>
							<tr>
								<td align="left" class="prod-title-caption"><!-- visualizzazione disponibilità  -->
								<? if (PRODUCT_INFO_AVAILABILITY == 'true')
								{?> <img border="0" src="<?=$image_location?>" />&nbsp;<small><?=$image_title?></small>
								<br>
								<?}
								if (PRODUCT_INFO_QUANTITY == 'true')
								echo "<small>" . TEXT_PRODUCT_QUANTITY . ' ' .$product_info['products_quantity'] . "</small><br>";

								// inserimento modulo per gestione arrivi ajax
								// controllo se il modulo è installato
								if(file_exists(DIR_FS_CATALOG . 'admin/incoming_products.php'))
								{

									// campo chiave che relaziona i prodotti agli arrivi
									if($the_product_info['Codice'] <> '') // esprinet
									$product_info['Item'] = $the_product_info['Codice'];
									else
									$product_info['Item'] = $the_product_info['products_model'];

									// ricavo tutti i record per il prodotto, facendo la differenza delle somme fra
									// DeliveredQty e ConfirmedDeliveryDate
									$in_arrivo_query = tep_db_query("Select SUM(Qty) as total_ordered, SUM(DeliveredQty) as total_delivered from products_incomings where Item = '". $product_info['Item'] ."'");
									$totals_incoming_array = tep_db_fetch_array($in_arrivo_query);
									$total_incoming = $totals_incoming_array['total_ordered'] - $totals_incoming_array['total_delivered'];

									if ($total_incoming >= '1')
									{
										$text_inarrivo .= 'In arrivo:' . $total_incoming;
											
											
										$http_user_agent = getenv('HTTP_USER_AGENT');

										$in_arrivo_query = tep_db_query("Select * from products_incomings where Item = '". $product_info['Item'] ."' order by ConfirmedDeliveryDate Asc");
											
										while ($a_row = tep_db_fetch_array($in_arrivo_query))
										{
											$text_inarrivo .=  "<br> " . ($a_row['Qty'] - $a_row['DeliveredQty']) .  " pezzi il " . tep_date_short($a_row['ConfirmedDeliveryDate']) . "";
										}
										/*	if (strstr($http_user_agent,'MSIE'))
										 $text_inarrivo .= '<br><img border="0" src="'. DIR_WS_ICONS . 'Information_icon.jpg" alt="Date di arrivo" align="absbottom" id="incoming_info">';
										 //	 $text_inarrivo .= '<br><img border="0" src="'. DIR_WS_ICONS . 'Information_icon.jpg" alt="Date di arrivo" align="absbottom" id="incoming_info"  onClick="loadIncomings( \'' . $product_info["Item"] . '\',\'' . $product_info["products_id"] . '\')">';
										 else
										 $text_inarrivo .= '<br><img border="0" src="'. DIR_WS_ICONS . 'Information_icon.jpg" alt="Date di arrivo" align="absbottom" id="incoming_info"  onClick="loadIncomings( \'' . $product_info["Item"] . '\',\'' . $product_info["products_id"] . '\')">';

										 $text_inarrivo .= '<br><div class="productListing-data" id="incomings' . $product_info['products_id'] . '"></div>';
										 */
										echo "<small>" . $text_inarrivo . "</small>";
									}
								}
									
								?></td>
								<td align="right" class="prod-title-caption" valign="top"><!--  produttore -->
								<?
								if ($the_manufacturers['manufacturers_id'] <> '')
								{
									$manufacturer['link_to_other_products']=tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $the_manufacturers['manufacturers_id']);
									// BOX_MANUFACTURER_INFO_OTHER_PRODUCTS
									 if (tep_not_null($manufacturer['manufacturers_image']))
										$manufacturer['manufacturers_image']=DIR_WS_IMAGES.$manufacturer['manufacturers_image'];
									else
										$the_manufacturers['manufacturers_image'] = MANUFACTURER_DEFAULT_IMAGE;
										
										
									// echo tep_image(DIR_WS_IMAGES . $the_manufacturers['manufacturers_image'], $the_manufacturers['manufacturers_name'], 120, 120);
									// echo BOX_MANUFACTURER_INFO_OTHER_PRODUCTS;
									//echo  BOX_HEADING_MANUFACTURER_INFO;
								 	echo '<a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturer['manufacturers_id']) . '" ">' . tep_image(DIR_WS_IMAGES . $the_manufacturers['manufacturers_image'], BOX_MANUFACTURER_INFO_OTHER_PRODUCTS . ' ' . $the_manufacturers['manufacturers_name'], 120, 120) . '</a>';
							// 	print  MANUFACTURER_DEFAULT_IMAGE . $the_manufacturers['manufacturers_image'];
							// 	exit;
								
								}
								?></td>
							</tr>
						</table>
						<!-- prezzo prodotto  offerta speciale  sconto quantità e sconto cliente  -->

						<table width="100%" border="0" cellspacing="2" cellpadding="2"
							class="main">
							<tr>
								<!--  tabella prezzi e sconti eventuali -->
							<?php
							// define(ENABLE_PRODUCT_SHIPPING_COST, 'true');
							//	if (ENABLE_PRODUCT_SHIPPING_COST == 'true') $colspan = '2' ?>
								<?php 
								 if ($the_product_info['products_onlyshow'] == '1') // mostra l'immagine richiesta info o nulla 
								 {
								 	echo '<td class="productsPrice" align="center">';
								 	echo  '<a href="'.tep_href_link('request_product_info.php', 'products_id='.$HTTP_GET_VARS['products_id'], 'NONSSL', false) . '">' . TEXT_PRODUCT_ONLYSHOW . ' </a>';
								 	echo '<br>';
								 	echo '<a href="'.tep_href_link('request_product_info.php', 'products_id='.$HTTP_GET_VARS['products_id'], 'NONSSL', false) . '"><img border="0" valign="top"  src="' . DIR_WS_IMAGES . 'icons/onlyshow_icon.jpg"></a>';
								 //	echo sprintf(TEXT_MORE_INFORMATION, tep_href_link(FILENAME_REDIRECT, 'goto=' . urlencode($product_info['products_url']).'&action=url', 'NONSSL', true, false));
									if (PRODUCTS_ONLYSHOW_PRICE == "true")
								 			echo $products_price; 
								 }
								 else
								 {
								 	echo '<td class="productsPrice">';
								    echo $products_price; 
								  }?>
								  </td>
							</tr>
							<tr>
								<!--  costi di spedizone -->
							<?php if(ENABLE_PRODUCT_SHIPPING_COST == 'true') {

								?>
								<td>
								<table width="100%" border="0" cellspacing="2" cellpadding="2"
									class="main">
									<?


									// controlliamo anche se il prezzo del prodotto supera il totale ordine spedizione gratis
									if ( defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true') ) {
										$pass = true;

										switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) {
											case 'national':
												if ($country_id == STORE_COUNTRY) {
													$pass = true;
												}
												break;
											case 'international':
												if ($order->delivery['country_id'] != STORE_COUNTRY) {
													$pass = true;
												}
												break;
											case 'both':
												$pass = true;
												break;
										}

										$free_shipping = false;
										if ( ($pass == true) && ($pws_prices->getBestPrice($HTTP_GET_VARS['products_id']) * (1 + tep_get_tax_rate($the_product_info['products_tax_class_id']) / 100) >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) ) {
											$free_shipping = true;
										}

									}

									if ($the_product_info['products_weight'] == '0' ||  $free_shipping == true )
									{ ?>
									<td align="center"><? if (file_exists(DIR_WS_LANGUAGES . $language .  '/images/buttons/spedizione.jpg') ) 
									{ echo tep_image_button('spedizione.jpg', FREE_SHIPPING_TITLE); }
									else {echo FREE_SHIPPING_TITLE;} ?></td>

									<?}
									else // tabella costi per i vari corrieri configurati
									{

										// istanzione l'order se non è un oggetto
										if(is_object($order))
										{

										}
										else
										{
											require(DIR_WS_CLASSES . 'order.php');
											$order = new order;
											$order->delivery['country']['iso_code_2'] = 'IT';
											$order->delivery['country']['id'] = '105';
											$order->delivery['zone_id'] = '185';
											
											// $shipping_weight =  $products_weight_array['products_weight'];
										}
										// print_r($order);
										require(DIR_WS_CLASSES . 'shipping.php');
										$shipping_modules = new shipping;
										
											
										if (tep_count_shipping_modules() > 0) {
											$quotes = $shipping_modules->quote_product($products_id);
										//	print_r($quotes);
											//		print_r($shipping_modules->cheapest()); //il meno caro
											//		exit;
											for ($i=0, $n=sizeof($quotes); $i<$n; $i++) {
												for ($j=0, $n2=sizeof($quotes[$i]['methods']); $j<$n2; $j++) {
													?>
									<tr>
										<td class="main" valign="middle"><?php echo $quotes[$i]['module']; ?>:
										</td>

										<td class="shipping_price" valign="middle" align="right"><?php 
										if (isset($quotes[$i]['error'])) echo $quotes[$i]['error'] ;
										else echo $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], $quotes[$i]['tax'])) . tep_draw_hidden_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id']); ?>
										</td>
									</tr>



									<? }
											}
										}
									}
									?>
								</table>
								</td>


								<?php } // fine tabella costi di spedizione ?>
							</tr>

						</table>


						<table width="100%" border="0" cellspacing="2" cellpadding="2"
							class="infoBox">
							<tr>
								<td align="left" class="productsPrice"><!-- opzioni prodotto -->
								<?php
									
								//   $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
								//   $products_attributes = tep_db_fetch_array($products_attributes_query);
								//++++ QT Pro: End Changed Code
								$products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
								//    $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id ");
								$products_attributes = tep_db_fetch_array($products_attributes_query);
								$query = "select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'";
								//    print $query;
								//    print_r($products_attributes);
								if ($products_attributes['total'] > 0) {
									//++++ QT Pro: Begin Changed code
									$products_id=(preg_match("/^\d{1,10}(\{\d{1,10}\}\d{1,10})*$/",$HTTP_GET_VARS['products_id']) ? $HTTP_GET_VARS['products_id'] : (int)$HTTP_GET_VARS['products_id']);
									require(DIR_WS_CLASSES . 'pad_' . PRODINFO_ATTRIBUTE_PLUGIN . '.php');
									$class = 'pad_' . PRODINFO_ATTRIBUTE_PLUGIN;
									$pad = new $class($products_id);
									echo $pad->draw();
									//++++ QT Pro: End Changed Code
								}
								?></td>
							</tr>
						</table>
						<?php if($GLOBALS['pws_prices']->displayPrices() &&  $the_product_info['products_onlyshow'] == '0')
						{
							?>
						<table width="100%" border="0" cellspacing="2" cellpadding="2"
							class="headerNavigation">
							<tr>
								<td align="center" class="headerNavigation" width="100%"><? echo IMAGE_BUTTON_IN_CART ?>
								<input type="text" size="3" class="relatedProductsQuantity"
									value="1" id="quantity" name="quantity" /> <?php echo tep_draw_hidden_field('products_id', $product_info['products_id']) . tep_image_submit('button_in_cart.png', IMAGE_BUTTON_IN_CART,'align=top'); ?>
								</td>
							</tr>
						</table>
						<?php
						}
						?>
						</form>
						<table width="100%" border="0" cellspacing="2" cellpadding="2"
							class="infoBox">
							<tr>
								<td align="left" class="productsPrice"><!-- spaziatore --></td>
							</tr>
						</table>

						<?
						// $product_info['products_youtube_url'] = 'LgFWeMGwCes';
						// print $product_info['products_youtube_url'];
							
						if ( strlen($product_info['products_youtube_url']) >= '10' ) // $product_info['youtube']) // LgFWeMGwCes
						{
							$youtube_code =  substr($product_info['products_youtube_url'], strpos($product_info['products_youtube_url'], '?v=') + 3);
							?>
						<table width="100%" border="0" cellspacing="2" cellpadding="2"
							class="infobox">
							<tr>
								<td align="center" class="infobox" width="100%"><object
									width="272" height="224">
									<param name="movie"
										value="http://www.youtube.com/v/<? echo  $youtube_code ?>?rel=0&showinfo=0"></param>
									<param name="wmode" value="transparent"></param>
									<embed
										src="http://www.youtube.com/v/<? echo  $youtube_code ?>?rel=0&showinfo=0"
										type="application/x-shockwave-flash" wmode="transparent"
										width="272" height="224">
									</embed> </object></td>
							</tr>
						</table>
						<? } ?>
				
				</table>
				<!-- FINE SEZIONE Dati prodotto sintetici --></td>
			</tr>

			<tr>
				<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
			</tr>

			<tr>
				<td>
				<table border="0" width="100%" cellspacing="0" cellpadding="0">
					<tr>
						<td><?php
		   			 // visualizza la scheda esprinet
						if(tep_not_null($distLink))
						{
							// $host = "www.bestdigit.it";
							// se è un sito parassita abilitiamo il visualizzatore esprinet
							if (strstr($_SERVER['SERVER_NAME'], 'gross' ) ) 
								$cache = 'true';
							if ($cache == 'true')
							{
								if (file_exists("cache/".$products_id."_lang".$languages_id. ".html"))
									 readfile(	"cache/".$products_id."_lang".$languages_id. ".html" ); 
								else 
								{
									include ("esprinet_viewer.php");
									readfile(	"cache/".$products_id."_lang".$languages_id. ".html" ); 
								}
							}
							else 
							{
							?> <iframe src="<?php echo $distLink ?>" width="100%"
							height="700" frameborder="0" style="border: 0px">
							
						<p>Your browser does not support iframes.</p>
						</iframe> <?php
							}
						}
						elseif(tep_not_null($astaingriglia))
						{
							?>
						<iframe src="<?php echo $astaingriglia;?>" width="100%"
							height="1280" frameborder="0" style="border: 0px">
						<p>Your browser does not support iframes.</p>
						</iframe> <?php

						}
/*						elseif(tep_not_null($icecatLink))
						{
							readfile(	"cache/".$products_id."_lang".$languages_id. ".html" ); 							
						}
						*/
/*						elseif(file_exists("cache/".$products_id.".html"))
						{
							// $fp = fopen("cache/".$productDescCache, "r");
							echo  $content = file_get_contents(	"cache/".$products_id.".html" );
												
						}
			*/
						else
						{ 
						// vediamo se ci sono dei correlati
						
						if (mysql_num_rows($relprod_query) >= '1') // se ci sono prodotti correlati stampa i tabs
							{
								?> <span id="productDesc"></span> <?
							}
						elseif(file_exists(	"cache/".$products_id."_lang".$languages_id. ".html")) // stampa la descrizione dalla cache senza tabs
							{
							  $content = readfile(	"cache/".$products_id."_lang".$languages_id. ".html" );
							}
						else // non esiste la cache e non ci sono correlati quindi stapa la descrizione e crea la cache
							{
							// crea la cache per la descrizione del prodotto
							 echo  $the_product_info['products_description'];
						  		$fp = fopen("cache/".$products_id."_lang".$languages_id. ".html","w");
							    fwrite($fp, $the_product_info['products_description']);
								// close the file
						        fclose($fp);  
							}	
						}
						?></td>
					</tr>
				</table>
				</td>
			</tr>

			<?php
			// echo $pws_engine->triggerHook('CATALOG_PRODUCT_INFO_SELECT_PRODUCTS');
			if ($product_info['products_date_available'] > date('Y-m-d H:i:s') && PRODUCT_INFO_DATE_ADDED == 'true') {
				// noop sostituito dalla data in arrivo mostrata in cima
			}
			elseif (PRODUCT_INFO_DATE_ADDED == 'true') {
				?>
			<tr>
				<td align="center" class="smallText"><?php echo sprintf(TEXT_DATE_ADDED, tep_date_long($product_info['products_date_added'])); ?></td>
			</tr>
			<?php
			}
			?>
			<tr>
				<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
			</tr>

			<?php
		}
		?>
		</table>
		</td>
		<!-- body_text_eof //-->
		<td width="<?php echo BOX_WIDTH; ?>" valign="top">
		<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0"
			cellpadding="2">
			<!-- right_navigation //-->
			<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
			<!-- right_navigation_eof //-->
		</table>
		</td>
	</tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
			<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
			<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
