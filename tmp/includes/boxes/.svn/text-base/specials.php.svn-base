<?php
/*
  $Id: specials.php,v 1.31 2003/06/09 22:21:03 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
	global $pws_prices;
	if ($random_product = tep_random_select("select p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s, " . TABLE_CATEGORIES . " c, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c  where c.categories_status='1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and p.products_status = '1' and p.products_id = s.products_id and pd.products_id = s.products_id and pd.language_id = '" . (int)$languages_id . "' and s.status = '1' order by s.specials_date_added desc limit " . MAX_RANDOM_SELECT_SPECIALS)) {
		$skin=new pws_skin('boxes/'.basename(__FILE__,'.php').'.htm');
		$skin->set('box_heading',BOX_HEADING_SPECIALS);
		$skin->set('more',array(
			'visible'=>true
			,'link'=>tep_href_link(FILENAME_SPECIALS)
			,'title'=>ICON_ARROW_RIGHT
			,'image'=>DIR_WS_IMAGES . 'infobox/arrow_right.gif'
			,'long_text'=>BOX_HEADING_SPECIALS
			)
		);
		$skin->set('link',tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product["products_id"]));
		$skin->set('products_name',$random_product['products_name']);
		
				$checkMultiImage = tep_db_query("select * from ".TABLE_PWS_PLUGINS." where plugin_code = 'pws_products_images'");
				   if(tep_db_num_rows($checkMultiImage) >= '1')  // è installato il plugin multimage?
			            {
		            	$image_query=tep_db_query("select * from ".TABLE_PWS_PRODUCTS_IMAGES." where products_id='" . $random_product['products_id'] . "' order by sort_order");
							
			            	if (tep_db_num_rows($image_query) >= '1') // modifico il primo lightbox (testo) se ci sono multi image
			            		//  $lc_text = '<a href="' . DIR_WS_IMAGES . $random_product['products_image'] . '" rel="lightbox[boxspecial' .$random_product['products_id']. ']"  title="Click on the left/right side of image" target="_blank">' . $GLOBALS['pws_html']->getHtmlProductsImage($random_product['products_id'], $random_product['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
								 $lc_text = tep_output_image($random_product['products_id'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, ' rel="lightbox[boxspecial' .$random_product['products_id']. ']" title="Click on the left/right side of image" target="_blank"' );
			            	else 	
			            	//	$lc_text = '<a href="' . DIR_WS_IMAGES . $random_product['products_image'] . '" rel="lightbox[boxspecial' . $random_product['products_id']. ']"   target="_blank">' . $GLOBALS['pws_html']->getHtmlProductsImage($random_product['products_id'], $random_product['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
			            		$lc_text = tep_output_image($random_product['products_id'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, ' rel="lightbox[boxspecial' . $random_product['products_id'] . ']"');

			            	
			            	while ($image_array = tep_db_fetch_array($image_query))
							{
								$lc_text .= '<a href="' . DIR_WS_IMAGES . $image_array['products_image'] . '" rel="lightbox[boxspecial' . $random_product['products_id']. ']"  title="Click on the left/right side of image" target="_blank"></a>';			
							}
			            }
			        else   
			       	//   $lc_text = '<a href="' . DIR_WS_IMAGES . $random_product['products_image'] . '" rel="lightbox[boxspecial' . $random_product['products_id']. ']" target="_blank">' . $GLOBALS['pws_html']->getHtmlProductsImage($random_product['products_id'], $random_product['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
								$lc_text = tep_output_image($random_product['products_id'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, ' rel="lightbox[boxspecial' . $random_product['products_id'] . ']"');								
								
		$product['products_image_lightbox'] = $lc_text;
	
	//	$skin->set('image',$GLOBALS['pws_html']->getProductsImage($random_product['products_id'],SMALL_IMAGE_WIDTH,SMALL_IMAGE_HEIGHT));
		$skin->set('image',$product['products_image_lightbox']);
		$skin->set('final_price',$pws_prices->getHtmlPriceWithDiscount($random_product['products_id']));
		echo $skin->execute();
	}
?>