<?php
/*
  $Id: also_purchased_products.php,v 1.21 2003/02/12 23:55:58 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
	if (isset($HTTP_GET_VARS['products_id'])) {
	
	// ######################## Added Enable / Disable Categorie ################
	//	$orders_query = tep_db_query("select p.products_id, p.products_image from " . TABLE_ORDERS_PRODUCTS . " opa, " . TABLE_ORDERS_PRODUCTS . " opb, " . TABLE_ORDERS . " o, " . TABLE_PRODUCTS . " p where opa.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and opa.orders_id = opb.orders_id and opb.products_id != '" . (int)$HTTP_GET_VARS['products_id'] . "' and opb.products_id = p.products_id and opb.orders_id = o.orders_id and p.products_status = '1' group by p.products_id order by o.date_purchased desc limit " . MAX_DISPLAY_ALSO_PURCHASED);
		$orders_query = tep_db_query("select p.products_id, p.products_image from " . TABLE_ORDERS_PRODUCTS . " opa, " . TABLE_ORDERS_PRODUCTS . " opb, " . TABLE_ORDERS . " o, " . TABLE_PRODUCTS . " p, " . TABLE_CATEGORIES . " c, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c  where c.categories_status='1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and opa.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and opa.orders_id = opb.orders_id and opb.products_id != '" . (int)$HTTP_GET_VARS['products_id'] . "' and opb.products_id = p.products_id and opb.orders_id = o.orders_id and p.products_status = '1' group by p.products_id order by o.date_purchased desc limit " . MAX_DISPLAY_ALSO_PURCHASED);
	// ######################## End Added Enable / Disable Categorie ################
	
		$num_products_ordered = tep_db_num_rows($orders_query);
		if ($num_products_ordered >= MIN_DISPLAY_ALSO_PURCHASED) {
			$skin=new pws_skin('modules/'.basename(__FILE__,'.php').'.htm');
			$skin->set('box_heading',TEXT_ALSO_PURCHASED_PRODUCTS);
			$row = 0;
			$col = 0;
			$products=array();
			$products_row=array();
			while ($new_products = tep_db_fetch_array($orders_query)) {
				$product=array();
				$product['present']=true;
				$product['products_name'] = tep_get_products_name($new_products['products_id']);
				$product['products_url'] = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']);
				$product['products_image_html'] = $GLOBALS['pws_html']->getHtmlProductsImage($new_products['products_id'], $new_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);
			
				$product['products_link'] = $product['products_url'];
				$product['products_image'] = DIR_WS_IMAGES . $new_products['products_image'];
				
				$checkMultiImage = tep_db_query("select * from ".TABLE_PWS_PLUGINS." where plugin_code = 'pws_products_images'");
				   if(tep_db_num_rows($checkMultiImage) >= '1')  // è installato il plugin multimage?
			            {
		            	$image_query=tep_db_query("select * from ".TABLE_PWS_PRODUCTS_IMAGES." where products_id='" . $new_products['products_id'] . "' order by sort_order");
							
			            	if (tep_db_num_rows($image_query) >= '1') // modifico il primo lightbox (testo) se ci sono multi image
			            		$lc_text_np = '<a href="' . DIR_WS_IMAGES . $new_products['products_image'] . '" rel="lightbox[np'.$new_products['products_id']. ']"  title="Click on the left/right side of image" target="_blank">' . $GLOBALS['pws_html']->getHtmlProductsImage($new_products['products_id'], $new_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
							else 	
			            		$lc_text_np = '<a href="' . DIR_WS_IMAGES . $new_products['products_image'] . '" rel="lightbox[np' . $new_products['products_id']. ']"   target="_blank">' . $GLOBALS['pws_html']->getHtmlProductsImage($new_products['products_id'], $new_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
			            	
			            	
			            	while ($image_array = tep_db_fetch_array($image_query))
							{
								$lc_text_np .= '<a href="' . DIR_WS_IMAGES . $image_array['products_image'] . '" rel="lightbox[np' . $new_products['products_id']. ']"  title="Click on the left/right side of image" target="_blank"></a>';			
							}
			            }
			        else           
			        	$lc_text_np = '<a href="' . DIR_WS_IMAGES . $new_products['products_image'] . '" rel="lightbox[np' . $new_products['products_id']. ']" target="_blank">' . $GLOBALS['pws_html']->getHtmlProductsImage($new_products['products_id'], $new_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
			
			$product['products_image_lightbox'] .= $lc_text_np;				
				
				$product['products_price_formatted'] = $GLOBALS['pws_prices']->getHtmlPriceWithDiscount($new_products['products_id']);
				array_push($products_row,$product);
				if (++$col > 2) {
					$col = 0;
					$row ++;
					array_push($products,$products_row);
					$products_row=array();
				}
			}
			if (sizeof($products_row)){
				while (++$col<3){
					array_push($products_row,array('present'=>false));
				}
				array_push($products,$products_row);
			}
			$skin->set('products_rows',$products);
			echo $skin->execute();
		}
	}
?>
