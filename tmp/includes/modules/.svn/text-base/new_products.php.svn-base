<?php
/*
	$Id: new_products.php,v 1.34 2003/06/09 22:49:58 hpdl Exp $

	osCommerce, Open Source E-Commerce Solutions
	http://www.oscommerce.com

	Copyright (c) 2003 osCommerce

	Released under the GNU General Public License
*/

	$skin=new pws_skin('modules/'.basename(__FILE__,'.php').'.htm');
	
	$skin->set('box_heading',sprintf(TABLE_HEADING_NEW_PRODUCTS, strftime('%B')));
	if (!isset($cPath) && isset($_REQUEST['cPath']))
		$cPath=$_REQUEST['cPath'];
	$lastProductQuery=tep_db_query("select min(to_days(now())-to_days(products_date_added)) as lastpinsert from ".TABLE_PRODUCTS." where 1");
	$lastpinsert=array_pop(tep_db_fetch_array($lastProductQuery));
	if ($lastpinsert==0) $lastpinsert='0';
	
	// variabile che arriva dal modulo categories_listing.php indica se l'utente è in home oppure all'interno di una categoria 
	if ( (!isset($new_products_category_id)) || ($new_products_category_id == '0') ) {
	//	$new_products_query = tep_db_query("select p.products_id, p.products_image, p.products_tax_class_id, pd.products_name, if(s.status, s.specials_new_products_price, p.products_price) as products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and to_days(now())-to_days(p.products_date_added)<=(31+$lastpinsert) order by rand() limit " . MAX_DISPLAY_NEW_PRODUCTS);
			if(RANDOMIZE == 'on')	$new_products_query = tep_db_query("select p.products_id, p.products_image, p.products_tax_class_id, pd.products_name, if(s.status, s.specials_new_products_price, p.products_price) as products_price from " . TABLE_PRODUCTS . " p left outer join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' AND p.products_image <> '' order by rand() limit " . MAX_DISPLAY_NEW_PRODUCTS);
			else 	$new_products_query = tep_db_query("select p.products_id, p.products_image, p.products_tax_class_id, pd.products_name, if(s.status, s.specials_new_products_price, p.products_price) as products_price from " . TABLE_PRODUCTS . " p left outer join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' AND p.products_image <> '' order by p.products_date_added Desc limit " . MAX_DISPLAY_NEW_PRODUCTS);
			//	$new_products_query = tep_db_query("select p.products_id, p.products_image, p.products_tax_class_id, pd.products_name, if(s.status, s.specials_new_products_price, p.products_price) as products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and (p.products_image IS NOT NULL AND p.products_image <> '') order by p.products_date_added desc limit " . MAX_DISPLAY_NEW_PRODUCTS);
	} else {
		$new_products_query = tep_db_query("select distinct p.products_id, p.products_image, p.products_tax_class_id, pd.products_name, if(s.status, s.specials_new_products_price, p.products_price) as products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c where p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and c.parent_id = '" . (int)$new_products_category_id . "' and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and (p.products_image IS NOT NULL AND p.products_image <> '') order by p.products_date_added desc limit " . MAX_DISPLAY_NEW_PRODUCTS);
	}
	$row = 0;
	$col = 0;
	$products=array();
	$products_row=array();
	if (tep_db_num_rows($new_products_query)){
		while ($new_products = tep_db_fetch_array($new_products_query)) {
			$product=array();
			$products_id=$new_products['products_id'];
			$product['present']=true;
			$product['products_name'] = $new_products['products_name'];
			$product['products_url'] = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_id);
			$product['products_link'] = $product['products_url'];
			$product['products_image'] = DIR_WS_IMAGES . $new_products['products_image'];

				$checkMultiImage = tep_db_query("select * from ".TABLE_PWS_PLUGINS." where plugin_code = 'pws_products_images'");
	/*			   if(tep_db_num_rows($checkMultiImage) >= '1')  // è installato il plugin multimage?
			            {
		            	$image_query=tep_db_query("select * from ".TABLE_PWS_PRODUCTS_IMAGES." where products_id='" . $new_products['products_id'] . "' order by sort_order");
							
			            	if (tep_db_num_rows($image_query) >= '1') // modifico il primo lightbox (testo) se ci sono multi image
			            		$lc_text_np = '<a href="' . DIR_WS_IMAGES . $new_products['products_image'] . '" rel="lightbox[np'.$new_products['products_id']. ']"  title="Click on the left/right side of image" target="_blank">' . $GLOBALS['pws_html']->getHtmlProductsImage($new_products['products_id'], $new_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
							else 	
			            	//	$lc_text_np = '<a href="' . DIR_WS_IMAGES . $new_products['products_image'] . '" rel="lightbox[np' . $new_products['products_id']. ']"   target="_blank">' . $GLOBALS['pws_html']->getHtmlProductsImage($new_products['products_id'], $new_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
			            		$lc_text_np = '<a href="' . DIR_WS_IMAGES . $new_products['products_image'] . '" rel="lightbox[np' . $new_products['products_id']. ']"   target="_blank">' .  tep_output_image($new_products['products_id'],SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
							
			            	
			            	while ($image_array = tep_db_fetch_array($image_query))
							{
								$lc_text_np .= '<a href="' . DIR_WS_IMAGES . $image_array['products_image'] . '" rel="lightbox[np' . $new_products['products_id']. ']"  title="Click on the left/right side of image" target="_blank"></a>';			
							}
			            }
			        else           
			            		$lc_text_np = '<a href="' . DIR_WS_IMAGES . $new_products['products_image'] . '" rel="lightbox[np' . $new_products['products_id']. ']"   target="_blank">' .  tep_output_image($new_products['products_id'],SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
	*/
				   if(tep_db_num_rows($checkMultiImage) >= '1')  // è installato il plugin multimage?
			            {
		            	$image_query=tep_db_query("select * from ".TABLE_PWS_PRODUCTS_IMAGES." where products_id='" . $new_products['products_id'] . "' order by sort_order");
							
			            	if (tep_db_num_rows($image_query) >= '1') // modifico il primo lightbox (testo) se ci sono multi image
			           //		$lc_text = '<a href="' . DIR_WS_IMAGES . $new_products['products_image'] . '" rel="lightbox[' .$new_products['products_id']. ']"  title="Click on the left/right side of image" target="_blank">' . $GLOBALS['pws_html']->getHtmlProductsImage($new_products['products_id'], $new_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
							   $lc_text_np = tep_output_image($new_products['products_id'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, ' rel="lightbox[np' .$new_products['products_id']. ']" title="Click on the left/right side of image" target="_blank"' );
			            	
			            	else 	
							    $lc_text_np = tep_output_image($new_products['products_id'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, ' rel="lightbox[np' .$new_products['products_id']. ']" target="_blank"' );
			            				            	
			            	
			            	while ($image_array = tep_db_fetch_array($image_query))
							{
								$lc_text_np .= '<a href="' . DIR_WS_IMAGES . $image_array['products_image'] . '" rel="lightbox[np' . $new_products['products_id']. ']"  title="Click on the left/right side of image" target="_blank"></a>';			
							}
			            }
			        else           
							    $lc_text_np = tep_output_image($new_products['products_id'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, ' rel="lightbox[np' .$new_products['products_id']. ']" target="_blank"' );
			        					        			
			$product['products_image_lightbox'] .= $lc_text_np;
			$product['products_image_html'] = $GLOBALS['pws_html']->getHtmlProductsImage($products_id, $new_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);
			$product['products_price_formatted'] = $GLOBALS['pws_prices']->getHtmlPriceWithDiscount($products_id);
			$linkname=urlencode(str_replace(array('-',' ','\\','/','&'),array('_','_','_','_','_'),$new_products['products_name']));
			$product['cart_link']=HTTP_SERVER.DIR_WS_CATALOG.$linkname."-buynow-$products_id-$cPath.html";
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
?>