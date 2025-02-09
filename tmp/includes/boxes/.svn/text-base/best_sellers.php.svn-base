<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

	$rp_query = tep_db_query("select p.products_id, pd.products_name,  p.products_image, p.products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1'  and pd.language_id = '" . (int)$languages_id . "' and p.products_id = pd.products_id order by p.products_ordered desc limit " . MAX_DISPLAY_BESTSELLERS);

	if (tep_db_num_rows($rp_query)) {
		$skin=new pws_skin('boxes/'.basename(__FILE__,'.php').'.htm');
		$skin->set('box_heading',BOX_HEADING_BESTSELLERS);
		$products=array();
		while ($product=tep_db_fetch_array($rp_query)){
//PWS bof
//			$product['products_image']=tep_image(DIR_WS_IMAGES . $product['products_image'], $product['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);
		//	$product['products_image']=$GLOBALS['pws_html']->getHtmlProductsImage($product['products_id'], $product['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);
//PWS eof

		//		$product['products_image'] = DIR_WS_IMAGES . $product['products_image'];

				$checkMultiImage = tep_db_query("select * from ".TABLE_PWS_PLUGINS." where plugin_code = 'pws_products_images'");
				   if(tep_db_num_rows($checkMultiImage) >= '1')  // è installato il plugin multimage?
			            {
		            	$image_query=tep_db_query("select * from ".TABLE_PWS_PRODUCTS_IMAGES." where products_id='" . $product['products_id'] . "' order by sort_order");
							
			            	if (tep_db_num_rows($image_query) >= '1') // modifico il primo lightbox (testo) se ci sono multi image
			           // 		$lc_text = '<a href="' . DIR_WS_IMAGES . $product['products_image'] . '" rel="lightbox[bestseller' .$product['products_id']. ']"  title="Click on the left/right side of image" target="_blank">' . $GLOBALS['pws_html']->getHtmlProductsImage($product['products_id'], $product['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
					  			$lc_text = tep_output_image($product['products_id'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, ' rel="lightbox[bestseller' .$product['products_id']. ']"  title="Click on the left/right side of image" target="_blank"' );
			            	
			            	else 	
					  			$lc_text = tep_output_image($product['products_id'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, ' rel="lightbox[bestseller' .$product['products_id']. ']"  target="_blank"' );
			            				            	
			            	
			            	while ($image_array = tep_db_fetch_array($image_query))
							{
								$lc_text .= '<a href="' . DIR_WS_IMAGES . $image_array['products_image'] . '" rel="lightbox[bestseller' . $product['products_id']. ']"  title="Click on the left/right side of image" target="_blank"></a>';			
							}
			            }
			        else           
					  			$lc_text = tep_output_image($product['products_id'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, ' rel="lightbox[bestseller' .$product['products_id']. ']"  target="_blank"' );
			        			
			$product['products_image_lightbox'] .= $lc_text;
			
			$product['products_link']=tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product["products_id"]);
			$product['products_price']=$pws_prices->getHtmlPriceWithDiscount($product['products_id']);
			$products[]=$product;
		}
		$skin->set('products',$products);
		echo $skin->execute();
	}
?>
