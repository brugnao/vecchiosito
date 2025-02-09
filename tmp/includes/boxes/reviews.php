<?php
/*
  $Id: reviews.php,v 1.37 2003/06/09 22:20:28 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
	$random_select = "select r.reviews_id, r.reviews_rating, p.products_id, p.products_image, pd.products_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = r.products_id and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'";
	if (isset($HTTP_GET_VARS['products_id'])) {
		$random_select .= " and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "'";
	}
	$random_select .= " order by r.reviews_id desc limit " . MAX_RANDOM_SELECT_REVIEWS;
	$random_product = tep_random_select($random_select);
	
	$skin=new pws_skin('boxes/'.basename(__FILE__,'.php').'.htm');
	$skin->set('box_heading',BOX_HEADING_REVIEWS);
	if ($random_product){
		// display random review box
		$review_query = tep_db_query("select substring(reviews_text, 1, 60) as reviews_text from " . TABLE_REVIEWS_DESCRIPTION . " where reviews_id = '" . (int)$random_product['reviews_id'] . "' and languages_id = '" . (int)$languages_id . "'");
		$review = tep_db_fetch_array($review_query);
		
		$review = tep_break_string(tep_output_string_protected($review['reviews_text']), 15, '-<br>');
		$skin->set('more',
			array(
				'visible'=>true
				,'link'=>tep_href_link(FILENAME_REVIEWS)
				,'title'=>ICON_ARROW_RIGHT
				,'image'=>DIR_WS_IMAGES . 'infobox/arrow_right.gif'
			)
		);
/*		$skin->set('content',
			'<div align="center"><a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $random_product['products_id'] . '&reviews_id=' . $random_product['reviews_id']) . '">' . 
		    $GLOBALS['pws_html']->getHtmlProductsImage($random_product['products_id'], $random_product['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></div>
			<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $random_product['products_id'] . '&reviews_id=' . $random_product['reviews_id']) . '">' . $review . ' ..</a>
			<br/>
			<div align="center">' . tep_image(DIR_WS_IMAGES . 'stars_' . $random_product['reviews_rating'] . '.gif' , sprintf(BOX_REVIEWS_TEXT_OF_5_STARS, $random_product['reviews_rating'])) . '</div>'
		);
*/		
	
		$lc_text = '';
		
			$checkMultiImage = tep_db_query("select * from ".TABLE_PWS_PLUGINS." where plugin_code = 'pws_products_images'");
				   if(tep_db_num_rows($checkMultiImage) >= '1')  // è installato il plugin multimage?
			            {
		            	$image_query=tep_db_query("select * from ".TABLE_PWS_PRODUCTS_IMAGES." where products_id='" . $random_product['products_id'] . "' order by sort_order");
							
			            	if (tep_db_num_rows($image_query) >= '1') // modifico il primo lightbox (testo) se ci sono multi image
			            		$lc_text = '<a href="' . DIR_WS_IMAGES . $random_product['products_image'] . '" rel="lightbox[boxreview' .$random_product['products_id']. ']"  title="Click on the left/right side of image" target="_blank">' . $GLOBALS['pws_html']->getHtmlProductsImage($random_product['products_id'], $random_product['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
							else 	
			            		$lc_text = '<a href="' . DIR_WS_IMAGES . $random_product['products_image'] . '" rel="lightbox[boxreview' . $random_product['products_id']. ']"   target="_blank">' . $GLOBALS['pws_html']->getHtmlProductsImage($random_product['products_id'], $random_product['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
			            	
			            	
			            	while ($image_array = tep_db_fetch_array($image_query))
							{
								$lc_text .= '<a href="' . DIR_WS_IMAGES . $image_array['products_image'] . '" rel="lightbox[boxreview' . $random_product['products_id']. ']"  title="Click on the left/right side of image" target="_blank"></a>';			
							}
			            }
			        else           
			        	$lc_text = '<a href="' . DIR_WS_IMAGES . $random_product['products_image'] . '" rel="lightbox[boxreview' . $random_product['products_id']. ']" target="_blank">' . $GLOBALS['pws_html']->getHtmlProductsImage($random_product['products_id'], $random_product['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
			
		
		
		//print_r($random_product);
		//print $review['products_image_lightbox'];
		//exit;
		
		$skin->set('content',
			'<div align="center">' . $lc_text .  '</div>
			<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $random_product['products_id'] . '&reviews_id=' . $random_product['reviews_id']) . '">' . $review . ' ..</a>
			<br/>
			<div align="center">' . tep_image(DIR_WS_IMAGES . 'stars_' . $random_product['reviews_rating'] . '.gif' , sprintf(BOX_REVIEWS_TEXT_OF_5_STARS, $random_product['reviews_rating'])) . '</div>'
		);
	}elseif (isset($HTTP_GET_VARS['products_id'])) {
		$skin->set('more',array('visible'=>false));
		$skin->set('content','<table border="0" cellspacing="0" cellpadding="2"><tr><td class="infoBoxContents"><a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'products_id=' . $HTTP_GET_VARS['products_id']) . '">' . tep_image(DIR_WS_IMAGES . 'box_write_review.gif', IMAGE_BUTTON_WRITE_REVIEW) . '</a></td><td class="infoBoxContents"><a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'products_id=' . $HTTP_GET_VARS['products_id']) . '">' . BOX_REVIEWS_WRITE_REVIEW .'</a></td></tr></table>');
	} else {
		$skin->set('more',array('visible'=>false));
		$skin->set('content',BOX_REVIEWS_NO_REVIEWS);
	}
	echo $skin->execute();
?>