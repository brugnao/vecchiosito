<?php
/*
$Id: sts_product_info.php,v 1.3 2004/02/05 09:36:00 jhtalk Exp jhtalk $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
*/

/* 

  Simple Template System (STS) - Copyright (c) 2004 Brian Gallagher - brian@diamondsea.com

*/
//begin dynamic meta tags query -->

$the_product_info_query = tep_db_query("select pd.language_id, p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "'" . " and pd.language_id ='" .  (int)$languages_id . "'"); 
    $the_product_info = tep_db_fetch_array($the_product_info_query);
	$the_product_name = strip_tags ($the_product_info['products_name'], "");
	$the_product_description = strip_tags ($the_product_info['products_description'], "");
	$the_product_model = strip_tags ($the_product_info['products_model'], "");

$the_manufacturer_query = tep_db_query("select m.manufacturers_id, m.manufacturers_name from " . TABLE_MANUFACTURERS . " m left join " . TABLE_MANUFACTURERS_INFO . " mi on (m.manufacturers_id = mi.manufacturers_id and mi.languages_id = '" . (int)$languages_id . "'), " . TABLE_PRODUCTS . " p  where p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and p.manufacturers_id = m.manufacturers_id"); 
    $the_manufacturers = tep_db_fetch_array($the_manufacturer_query);

// end dynamic meta tags query -->
$template['metatag'] = '<meta name="keywords" content="' . TITLE .', ' . $the_product_name .', ' . $the_product_model . ', ' . $the_manufacturers['manufacturers_name'] .'">
<meta name="description" content="' . $the_product_description . '","' . $the_product_name . '">
<title>' . $the_product_name .' - ' . TITLE . '</title>'
//.'<style type="text/css">'.$pws_prices->catalogStylesheet().'</style>'
;




// This program is designed to build template variables for the product_info.php page template
// This code was modified from product_info.php

// Start the "Add to Cart" form
$template['startform'] = tep_draw_form('cart_quantity', tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=add_product'));
// Add the hidden form variable for the Product_ID
$template['startform'] .= tep_draw_hidden_field('products_id', $product_info['products_id']);
$template['endform'] = "</form>";

// Get product information from products_id parameter
$product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
$product_info = tep_db_fetch_array($product_info_query);

if (isset($pws_prices)){
	$template['regularprice'] = $pws_prices->formatPrice($pws_prices->getFirstPrice($product_info['products_id']), $product_info['products_id']);
	$template['specialprice'] = $pws_prices->formatPrice($pws_prices->getBestPrice($product_info['products_id']), $product_info['products_id']);
	if ($template['regularprice']==$template['specialprice']){
		$template['regularpriceFormatted']='<span class="productsPrice">'.$template['regularprice'].'</span>';
		$template['specialprice']='';
		$template['specialpriceFormatted']='';
	}else{
		$template['regularpriceFormatted']='<span class="productsPriceSlashed">'.$template['regularprice'].'</span>';
		$template['specialpriceFormatted']='<span class="SpecialPrice">'.$template['specialprice'].'</span>';
	}
	$template['priceresume']=$pws_prices->getHtmlPriceWithDiscount($product_info['products_id']);
}else{
	$template['regularprice'] = $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));
	if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
		$template['regularpriceFormatted']='<span class="productsPriceSlashed">'.$template['regularprice'].'</span>';
		$template['specialprice'] = $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id']));
		$template['specialpriceFormatted']='<span class="SpecialPrice">'.$template['specialprice'].'</span>';
	} else {
		$template['regularpriceFormatted']='<span class="productsPrice">'.$template['regularprice'].'</span>';
		$template['specialprice'] = '';
		$template['specialpriceFormatted']='';
	}
}
$template['productname'] = $product_info['products_name'];
if (tep_not_null($product_info['products_model'])) {
  $template['productmodel'] =  $product_info['products_model'];
} 

if (tep_not_null($product_info['products_image'])) {
  $template['imagesmall'] = $GLOBALS['pws_html']->getHtmlProductsImage($product_info['products_id'], addslashes($product_info['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"');
  $template['imagelarge'] = $GLOBALS['pws_html']->getHtmlProductsImage($product_info['products_id'], addslashes($product_info['products_name']), '','','');
  $template['imagesmall_src']=$GLOBALS['pws_html']->getProductsImage($product_info['products_id'],SMALL_IMAGE_WIDTH,SMALL_IMAGE_HEIGHT);
  $template['imagelarge_src']=$GLOBALS['pws_html']->getProductsImage($product_info['products_id']);
//  $template['imagepopup']="document.write('<a href=\"javascript:popupWindow(\\'" . tep_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $product_info['products_id']) . "\\')\">" . $template['imagesmall'] . "<br/><center>" . TEXT_CLICK_TO_ENLARGE . "</center></a>')";

  $checkMultiImage = tep_db_query("select * from ".TABLE_PWS_PLUGINS." where plugin_code = 'pws_products_images'");
		   if(tep_db_num_rows($checkMultiImage) >= '1')  // è installato il plugin multimage?
	            {
	            	$image_query=tep_db_query("select * from ".TABLE_PWS_PRODUCTS_IMAGES." where products_id='" . $HTTP_GET_VARS['products_id'] . "' order by sort_order");
	            	if (tep_db_fetch_array($image_query) >= '1')
	            		$lc_text = '<a href="' . DIR_WS_IMAGES . $product_info['products_image'] . '" rel="lightbox[' . $HTTP_GET_VARS['products_id']. ']"  title="Click on the left/right side of image" target="_blank">' . $GLOBALS['pws_html']->getHtmlProductsImage($HTTP_GET_VARS['products_id'], $product_info['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>&nbsp;';
					else 	
	            		$lc_text = '<a href="' . DIR_WS_IMAGES . $product_info['products_image'] . '" rel="lightbox[' . $HTTP_GET_VARS['products_id']. ']"  target="_blank">' . $GLOBALS['pws_html']->getHtmlProductsImage($HTTP_GET_VARS['products_id'], $product_info['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>&nbsp;';
					
					while ($image_array = tep_db_fetch_array($image_query))
					{
						$lc_text .= '<a href="' . DIR_WS_IMAGES . $image_array['products_image'] . '" rel="lightbox[' . $HTTP_GET_VARS['products_id']. ']"  title="Click on the left/right side of image" target="_blank"></a>';			
					}
					// echo $lc_text;
	            }
	        else           
	        	$lc_text = '<a href="' . DIR_WS_IMAGES . $product_info['products_image'] . '" rel="lightbox[' . $HTTP_GET_VARS['products_id']. ']" target="_blank">' . $GLOBALS['pws_html']->getHtmlProductsImage($HTTP_GET_VARS['products_id'], $product_info['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>&nbsp;';
  
  
  $template['imagepopup']=$lc_text;
  
}

if (isset($pws_engine)){
	$template['pwsstylesheets']=$pws_engine->triggerHook('CATALOG_PRODUCT_INFO_STYLESHEET');
	$template['pwsslideshow']=$pws_engine->triggerHook('CATALOG_PRODUCT_INFO_SLIDESHOW');
}

// ICECAT {{
  if(tep_not_null($HTTP_GET_VARS['products_id'])) {
  	$template['bodyparams'] = ' onload="doLoad(\'pid='.(int)$HTTP_GET_VARS['products_id'].'&languages_id='.$languages_id.'\',null,\'productDesc\;countdown(year,month,day,hour,minute)"';
  }
// ICECAT }} onload="countdown(year,month,day,hour,minute)"

$template['productdesc'] = stripslashes($product_info['products_description']);
//$template['productdesc'] .= $template['pwsslideshow'];

// Get the number of product attributes (the select list options)
$products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
$products_attributes = tep_db_fetch_array($products_attributes_query);
// If there are attributes (options), then...
if ($products_attributes['total'] > 0) {
  // Print the options header
  $template['optionheader'] = TEXT_PRODUCT_OPTIONS;
  $template['optioncontent'] = '<table class="optionsTable">';

  // Select the list of attribute (option) names
  $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_name");

  while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
    $products_options_array = array();
    $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "'");

    // For each option name, get the individual attribute (option) choices
    while ($products_options = tep_db_fetch_array($products_options_query)) {
      $products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']);

      // If the attribute (option) has a price modifier, include it
      if ($products_options['options_values_price'] != '0') {
        $products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options['price_prefix'] . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';
      }

    }
 
    // If we should select a default attribute (option), do it here
    if (isset($cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']])) {
      $selected_attribute = $cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']];
    } else {
      $selected_attribute = false;
    }

    $template['optionnames'] .= $products_options_name['products_options_name'] . ':<br>'; 
    $template['optionchoices'] .=  tep_draw_pull_down_menu('id[' . $products_options_name['products_options_id'] . ']', $products_options_array, $selected_attribute) . "<br>"; 
    $template['optioncontent'] .= '<tr><td>'.$products_options_name['products_options_name'] . ':</td><td>'. tep_draw_pull_down_menu('id[' . $products_options_name['products_options_id'] . ']', $products_options_array, $selected_attribute) . "</td></tr>"; 
    
  }
  $template['optioncontent'] .= '</table>';
} else {
  // No options, blank out the template variables for them
  $template['optionheader'] = '';
  $template['optionnames'] = '';
  $template['optionchoices'] = '';
}

// See if there are any reviews
$reviews_query = tep_db_query("select count(*) as count from " . TABLE_REVIEWS . " where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "'");
$reviews = tep_db_fetch_array($reviews_query);
if ($reviews['count'] > 0) {
  $template['reviews'] = TEXT_CURRENT_REVIEWS . ' ' . $reviews['count']; 
} else {
  $template['reviews'] = '';
}

// See if there is a product URL
if (tep_not_null($product_info['products_url'])) {
  $template['moreinfolabel'] = TEXT_MORE_INFORMATION;
  $template['moreinfourl'] = tep_href_link(FILENAME_REDIRECT, 'goto=' . urlencode($product_info['products_url']).'&action=url', 'NONSSL', true, false); 
} else {
  $template['moreinfolabel'] = '';
  $template['moreinfourl'] = '';
}

$template['moreinfolabel'] = str_replace('%s', $template['moreinfourl'], $template['moreinfolabel']);

// See if product is not yet available
if ($product_info['products_date_available'] > date('Y-m-d H:i:s')) {
  $template['productdatelabel'] = TEXT_DATE_AVAILABLE;
  $template['productdate'] = tep_date_long($product_info['products_date_available']);
} else {
  $template['productdatelabel'] = TEXT_DATE_ADDED;
  $template['productdate'] = tep_date_long($product_info['products_date_added']); 
}

// Strip out %s values
$template['productdatelabel'] = str_replace('%s.', '', $template['productdatelabel']);

// See if any product reviews
$template['reviewsurl'] = tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params());
$template['reviewsbutton'] = tep_image_button('button_reviews.gif', IMAGE_BUTTON_REVIEWS);
$template['addtocartbutton'] = tep_image_submit('button_in_cart.gif', IMAGE_BUTTON_IN_CART);

// See if any "Also Purchased" items
// I suspect that this won't work yet
// if ((USE_CACHE == 'true') && empty($SID)) {
//   $template['alsopurchased'] = tep_cache_also_purchased(3600);
// } else {
//   $template['alsopurchased'] = include(DIR_WS_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS);
// }

//PWS bof (related products)
$template['relatedproducts']=$pws_engine->triggerHook('CATALOG_PRODUCT_INFO_SELECT_PRODUCTS');
//PWS eof (related products)
?>
