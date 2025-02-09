<?php
/*
      QT Pro Version 4.0 modifications
     
      Copyright (c) 2004 Ralph Day
      Released under the GNU General Public License
  
      Based on prior works released under the GNU General Public License:
        QT Pro prior versions
          Ralph Day, October 2004
          Tom Wojcik aka TomThumb 2004/07/03 based on work by Michael Coffman aka coffman
          FREEZEHELL - 08/11/2003 freezehell@hotmail.com Copyright (c) 2003 IBWO
          Joseph Shain, January 2003
        osCommerce MS2
          Copyright (c) 2003 osCommerce
          
      Modifications made:
        11/2004 - Rename products_options column from special to products_options_track_stock
                  Change tep_check_stock_new call to new combined tep_check_stock
  
*******************************************************************************************

  $Id: shopping_cart.php,v 1.73 2003/06/09 23:03:56 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Amended for Attributes Inventory - FREEZEHELL - 08/11/2003 freezehell@hotmail.com
  Copyright (c) 2003 IBWO

  Released under the GNU General Public License
*/

  require("includes/application_top.php");

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SHOPPING_CART);

//---PayPal WPP Modification START ---//	
  //Assign a variable to cut down on database calls
  //Don't show checkout option if cart is empty.  It does not satisfy the paypal
  $ec_enabled = tep_paypal_wpp_enabled() && ($cart->count_contents() > 0);
  if ($ec_enabled) {
    //If they're here, they're either about to go to paypal or were sent back by an error, so clear these session vars
    if (tep_session_is_registered('paypal_ec_temp')) tep_session_unregister('paypal_ec_temp');
    if (tep_session_is_registered('paypal_ec_token')) tep_session_unregister('paypal_ec_token');
    if (tep_session_is_registered('paypal_ec_payer_id')) tep_session_unregister('paypal_ec_payer_id');
    if (tep_session_is_registered('paypal_ec_payer_info')) tep_session_unregister('paypal_ec_payer_info');
  }
//---PayPal WPP Modification END---//
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_SHOPPING_CART));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><?php echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_SHOPPING_CART, 'action=update_product')); ?><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_cart.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
// print_r ($cart);
//  exit;
  if ($cart->count_contents() > 0) {
	$skin=new pws_skin(basename(__FILE__,'.php').'.htm');
  	
?>
      <tr valign="abs-middle">
        <td valign="abs-middle"><style type="text/css"><?=''/*$pws_prices->catalogStylesheet()*/?></style>
        
<?php
//	$products_rows=array();
//	$product_row=array();
//	$product_row[]=array(
//		'class'=>"productListing-heading"
//		,'align'=>'center'
//		,'text'=>TABLE_HEADING_REMOVE
//	);
//	$product_row[]=array(
//		'class'=>"productListing-heading"
//		,'align'=>'center'
//		,'text'=>TABLE_HEADING_PRODUCTS
//	);
//	$product_row[]=array(
//		'class'=>"productListing-heading"
//		,'align'=>'center'
//		,'text'=>TABLE_HEADING_QUANTITY
//	);
//	$product_row[]=array(
//		'class'=>"productListing-heading"
//		,'align'=>'center'
//		,'text'=>TABLE_HEADING_TOTAL
//	);
//	array_push($products_row,$product_row);

	$any_out_of_stock = 0;
    $products = $cart->get_products();
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
// Push all attributes information in an array
      if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
        while (list($option, $value) = each($products[$i]['attributes'])) {
          echo tep_draw_hidden_field('id[' . $products[$i]['id'] . '][' . $option . ']', $value);
//++++ QT Pro: Begin Changed code
          $attributes = tep_db_query("select popt.products_options_name, popt.products_options_track_stock, poval.products_options_values_name, pa.options_values_price, pa.price_prefix
                                      from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                                      where pa.products_id = '" . $products[$i]['id'] . "'
                                       and pa.options_id = '" . $option . "'
                                       and pa.options_id = popt.products_options_id
                                       and pa.options_values_id = '" . $value . "'
                                       and pa.options_values_id = poval.products_options_values_id
                                       and popt.language_id = '" . $languages_id . "'
                                       and poval.language_id = '" . $languages_id . "'");
//++++ QT Pro: End Changed Code
          $attributes_values = tep_db_fetch_array($attributes);

          $products[$i][$option]['products_options_name'] = $attributes_values['products_options_name'];
          $products[$i][$option]['options_values_id'] = $value;
          $products[$i][$option]['products_options_values_name'] = $attributes_values['products_options_values_name'];
          $products[$i][$option]['options_values_price'] = $attributes_values['options_values_price'];
          $products[$i][$option]['price_prefix'] = $attributes_values['price_prefix'];
//++++ QT Pro: Begin Changed code
          $products[$i][$option]['track_stock'] = $attributes_values['products_options_track_stock'];
//++++ QT Pro: End Changed Code
        }
      }
    }
	$products_array=array();
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
    	$product=$products[$i];
    	$products_id=tep_get_prid($products[$i]['id']);
    	$class=(($i/2) == floor($i/2)) ? "productListing-even" : "productListing-odd";
    	$remove_checkbox=tep_draw_checkbox_field('cart_delete[]', $products[$i]['id']);
		$product['input_remove_checkbox']=$remove_checkbox;
		// $product['thumbnail']=tep_image(DIR_WS_IMAGES . $products[$i]['image'], $products[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);
	
		 $product['products_url']=tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']);
		$product['thumbnail']=tep_output_image( $products[$i]['id'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, ' rel="lightbox[sc' .$products[$i]['id']. ']" target="_blank"');
		$stock_check='';
		if (STOCK_CHECK == 'true') {
			if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
				$stock_check = tep_check_stock($products[$i]['id'], $products[$i]['quantity'], $products[$i]['attributes']); 
			}else{
				$stock_check = tep_check_stock($products[$i]['id'], $products[$i]['quantity']);
			}
			if (tep_not_null($stock_check)) {
				$any_out_of_stock = 1;
				// $stock_check .= '<br/>'.sprintf(OUT_OF_STOCK_QUANTITY_MAX,tep_get_products_stock($products[$i]['id'], $products[$i]['attributes']));
			 $stock_check .= '';
			}
		}
		$product['stock_check']=$stock_check;
		$product['attributes_list'] = '';
		if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
			reset($products[$i]['attributes']);
			while (list($option, $value) = each($products[$i]['attributes'])) {
				$product['attributes_list'].='<br/><small><i> - ' . $products[$i][$option]['products_options_name'] . ' ' . $products[$i][$option]['products_options_values_name'] . '</i></small>';
			}
		}
		$query=tep_db_query("select products_quantity,products_date_available from ".TABLE_PRODUCTS." where products_id=".$products_id);
		$info=tep_db_fetch_array($query);
		$product=array_merge($product,$info);
	// AVAILABILITY start
	if ($product['products_date_available']!=''
		&& substr($product['products_date_available'],0,10)!='0000-00-00'
		&& date('Y-m-d') < substr($product['products_date_available'],0,10))	{
		$image_location=DIR_WS_ICONS.'sem_blue.gif';
		$image_title=TEXT_AVAILABILITY_SCHEDULED;
	} else if ($product['products_quantity']>=PRODUCT_LIST_AVAILABILITY_GREEN)	{
		$image_location=DIR_WS_ICONS.'sem_green.gif';
		$image_title=TEXT_AVAILABILITY_GREEN;
	} else if ($product['products_quantity']>=PRODUCT_LIST_AVAILABILITY_YELLOW) {
		$image_location=DIR_WS_ICONS.'sem_yellow.gif';
		$image_title=TEXT_AVAILABILITY_YELLOW;
	} else {
		$image_location=DIR_WS_ICONS.'sem_red.gif';
		$image_title=TEXT_AVAILABILITY_RED;
	}
	// AVAILABILITY stop
	 if (PRODUCT_INFO_AVAILABILITY == 'true')
	 {
        $product['availability']='<table><tr><td><img border="0" src="'.$image_location.'" title="'.$image_title.'"/></td>
								<td><small>'.$image_title.'</small></td></tr></table>';
	 }
	 elseif (PRODUCT_INFO_QUANTITY == 'true' && $product['products_quantity'] >= 1)// disponibilità numerica
	 {
		 $product['availability']= "<br><small>" . TEXT_PRODUCT_QUANTITY . ' ' .$product['products_quantity'] . "</small>";
	 }
	 else 
	 {
	 	$product['availability']= '';
	 }
	 
        $product['input_quantity']=tep_draw_input_field('cart_quantity[]', $products[$i]['quantity'], 'size="4"') . tep_draw_hidden_field('products_id[]', $products[$i]['id']);
        $product['price_resume']=$pws_prices->getHtmlPriceResume($products_id, $products[$i]['quantity'],isset($products[$i]['attributes'])?$products[$i]['attributes']:NULL);
        $product['row']=$i;
        $product['row_class']=$i%2==0?'productListing-even':'productListing-odd';
        array_push($products_array,$product);
	}
	$skin->set('products',$products_array);
	$skin->set('headers',array(
		TABLE_HEADING_REMOVE, TABLE_HEADING_PRODUCTS,TABLE_HEADING_QUANTITY,TABLE_HEADING_TOTAL
	));
    echo $skin->execute();
    ?>
        </td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td align="right" class="main"><b><?php echo SUB_TITLE_SUB_TOTAL; ?> <?php echo $currencies->format($cart->show_total()); ?></b></td>
      </tr>
<?php
    if ($any_out_of_stock == 1) {
      if (STOCK_ALLOW_CHECKOUT == 'true') {
?>
      <tr>
        <td class="stockWarning" align="center"><br><?php echo OUT_OF_STOCK_CAN_CHECKOUT; ?></td>
      </tr>
<?php
      } else {
?>
      <tr>
        <td class="stockWarning" align="center"><br><?php echo OUT_OF_STOCK_CANT_CHECKOUT; ?></td>
      </tr>
<?php
      }
    }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                
                <td class="main"><?php echo tep_image_submit('button_update_cart.gif', IMAGE_BUTTON_UPDATE_CART); ?></td>
<?php
    $back = sizeof($navigation->path)-2;
    if (isset($navigation->path[$back]))
     {
     //	print_r($navigation->path[$back]);
    	if($navigation->path[$back]['page'] == 'getproductincart.php') $navigation->path[$back]['page'] = 'index.php';
?>
                <td class="main"><?php echo '<a href="' . tep_href_link($navigation->path[$back]['page'], tep_array_to_string($navigation->path[$back]['get'], array('action')), $navigation->path[$back]['mode']) . '">' . tep_image_button('button_continue_shopping.gif', IMAGE_BUTTON_CONTINUE_SHOPPING) . '</a>'; ?></td>
<?php
    }
//PWS bof
//                <td align="right" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '">' . tep_image_button('button_checkout.gif', IMAGE_BUTTON_CHECKOUT) . '</a>'; </td>
?>
                
<td align="right">
                   
	<a href="<?=tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL')?>">
	<?=tep_image(DIR_WS_LANGUAGES . $language . '/images/buttons/button_checkout.gif', IMAGE_BUTTON_CHECKOUT) ; ?>
	</a>
			     </td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
  } else {
?>
      <tr>
        <td align="center" class="main"><?php new infoBox(array(array('text' => TEXT_CART_EMPTY))); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="right" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table>
        </td>
      </tr>
<?php
  }
?>
	  <tr>
	  <td>

           	<?php 
		// se � installato il modulo ppec, il cliente NON � loggato e il carrello non � vuoto, visualizzo il pulsante
			if (MODULE_PAYMENT_PPEC_STATUS == "True" && ($cart->count_contents() > 0 ) && MODULE_PAYMENT_PPEC_ONLY_MARK == "Express" )
			{
			
				if ($customer_first_name <> '')
				{
					// no cassa veloce, passa per il mark perch� si � gi� loggato
				//	print_R($_SESSION);
					
				}
				else 
				{
					?><table border="0" width="100%" cellspacing="0" cellpadding="2">
					    <tr>
		                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
					
					<?	
					require_once('ppesetup.php'); 
					$ppesetup = new ppesetup($language);
						echo "<td align='right'><a href='".tep_href_link('ppeb.php', 'shec=a', 'SSL')."'>".$ppesetup->ima."</a></td>"; 
						?>
					  <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
		 			  </tr>
						</table>
					<?
				}
			}
			?>
		</td>
		</tr>


    </table></form></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
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
