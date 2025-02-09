<?php
/*
  $Id: checkout_confirmation.php,v 1.139 2003/06/11 17:34:53 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');


// if the customer is not logged on, redirect them to the login page
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_CHECKOUT_PAYMENT));
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($cart->count_contents() < 1) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }

// avoid hack attempts during the checkout procedure by checking the internal cartID
  if (isset($cart->cartID) && tep_session_is_registered('cartID')) {
    if ($cart->cartID != $cartID) {
      tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
    }
  }

// if no shipping method has been selected, redirect the customer to the shipping method selection page
  if (!tep_session_is_registered('shipping')) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  }

  if (!tep_session_is_registered('payment')) tep_session_register('payment');
  if (isset($HTTP_POST_VARS['payment'])) $payment = $HTTP_POST_VARS['payment'];

  if (!tep_session_is_registered('comments')) tep_session_register('comments');
  if (!tep_session_is_registered('order_reference')) tep_session_register('order_reference');
  if (!tep_session_is_registered('order_contact')) tep_session_register('order_contact');
  
  if (tep_not_null($HTTP_POST_VARS['comments'])) {
    $comments = tep_db_prepare_input($HTTP_POST_VARS['comments']);
  }
  
 if (tep_not_null($HTTP_POST_VARS['order_reference'])) {
    $order_reference = tep_db_prepare_input($HTTP_POST_VARS['order_reference']);
  }
  
if (tep_not_null($HTTP_POST_VARS['order_contact'])) {
    $order_contact = tep_db_prepare_input($HTTP_POST_VARS['order_contact']);
  }
//---PayPal WPP Modification START ---//	
/*
  if ($ec_enabled=tep_paypal_wpp_enabled()) {
    if (!tep_session_is_registered('paypal_ec_token') && !tep_session_is_registered('paypal_ec_payer_id') && !tep_session_is_registered('paypal_ec_payer_info')) { 
      $ec_checkout = false;
      $show_payment_page = true;
    }else{
      $show_payment_page = MODULE_PAYMENT_PAYPAL_DP_DISPLAY_PAYMENT_PAGE=='Yes';
      $ec_checkout = true;
    }
  }
*/
//---PayPal WPP Modification END ---//
  //kgt - discount coupons
  if (( defined('MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS') && MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS == 'true' )){
  	if (!tep_session_is_registered('coupon')) tep_session_register('coupon');
  //this needs to be set before the order object is created, but we must process it after
  $coupon = tep_db_prepare_input($HTTP_POST_VARS['coupon']);
  }
  //end kgt - discount coupons

// load the selected payment module
  require(DIR_WS_CLASSES . 'payment.php');
  $payment_modules = new payment($payment);

  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;

  $payment_modules->update_status();

  if ( ( is_array($payment_modules->modules) && (sizeof($payment_modules->modules) > 1) && !is_object($$payment) ) || (is_object($$payment) && ($$payment->enabled == false)) ) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_NO_PAYMENT_MODULE_SELECTED), 'SSL'));
  }

  if (is_array($payment_modules->modules)) {
    $payment_modules->pre_confirmation_check();
  }
  //kgt - discount coupons
  if (( defined('MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS') && MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS == 'true' )){
  	if( tep_not_null( $coupon ) && is_object( $order->coupon ) ) { //if they have entered something in the coupon field
    $order->coupon->verify_code();
    if( MODULE_ORDER_TOTAL_DISCOUNT_COUPON_DEBUG != 'true' ) {
		  if( !$order->coupon->is_errors() ) { //if we have passed all tests (no error message), make sure we still meet free shipping requirements, if any
			  if( $order->coupon->is_recalc_shipping() ) tep_redirect( tep_href_link( FILENAME_CHECKOUT_SHIPPING, 'error_message=' . urlencode( ENTRY_DISCOUNT_COUPON_SHIPPING_CALC_ERROR ), 'SSL' ) ); //redirect to the shipping page to reselect the shipping method
		  } else {
			  if( tep_session_is_registered('coupon') ) tep_session_unregister('coupon'); //remove the coupon from the session
			  tep_redirect( tep_href_link( FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode( implode( ' ', $order->coupon->get_messages() ) ), 'SSL' ) ); //redirect to the payment page
		  }
    }
	} else { //if the coupon field is empty, unregister the coupon from the session
		if( tep_session_is_registered('coupon') ) { //we had a coupon entered before, so we need to unregister it
      tep_session_unregister('coupon');
      //now check to see if we need to recalculate shipping:
      require_once( DIR_WS_CLASSES.'discount_coupon.php' );
      if( discount_coupon::is_recalc_shipping() ) tep_redirect( tep_href_link( FILENAME_CHECKOUT_SHIPPING, 'error_message=' . urlencode( ENTRY_DISCOUNT_COUPON_SHIPPING_CALC_ERROR ), 'SSL' ) ); //redirect to the shipping page to reselect the shipping method
    }
	}
  }
	//end kgt - discount coupons


 // if ($payment == 'ppec'  && (strlen($HTTP_POST_VARS['customer_cf_piva']) <= '10') &&  (ACCOUNT_CF_REQ == 'true')  )
 if (   (ACCOUNT_CF_REQ == 'true') && $HTTP_POST_VARS['customer_type'] == 'private' )
	// se è un provato controllo formale sul CF
 	{
 		if (controllaCF($HTTP_POST_VARS['customer_cf_piva']) !== true ) 
   		//if  (strlen($HTTP_POST_VARS['customer_cf_piva']) <= '10')
   		tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ENTRY_CF_ERROR), 'SSL'));
   
   	}
 elseif  ($HTTP_POST_VARS['customer_type'] == 'company')// è una società oppure il CF non è obbligatorio da config
	 { 
		// aggiorno prima l'address book
	 	
	 	$customer_type = $HTTP_POST_VARS['customer_type'];
	 	
	 	  // è una società o un privato?
	 	  if ($customer_type == 'private')
	 	  {
			$customer_cf = $HTTP_POST_VARS['customer_cf_piva'];
	 	 	 tep_db_query("Update " . TABLE_ADDRESS_BOOK . " set entry_cf = '" . $customer_cf ."' WHERE address_book_id = '" . $billto . "'");
	 	  	 $order->billing['cf'] = $customer_cf;
	 	  
	 	  }
	 	  elseif ($customer_type == 'company' && ACCOUNT_PIVA_REQ == 'true' && trim($HTTP_POST_VARS['customer_cf_piva']) <> '')
	 	  { 
	 	   	$customer_cf_piva = $HTTP_POST_VARS['customer_cf_piva'];
	 	  	 tep_db_query("Update " . TABLE_ADDRESS_BOOK . " set entry_piva = '" . $customer_cf_piva ."' WHERE address_book_id = '" . $billto . "'");
	 	  	 $order->billing['piva'] = $customer_cf_piva;
	 	  }
	 	  else 
	 	  {
	 	  	 tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ENTRY_PIVA_ERROR), 'SSL'));
	 	  }

	 	  // devo usare le funzione standard di mysql altrimenti si blocca
	 	     mysql_query("Update " . TABLE_ADDRESS_BOOK . " set entry_type = '" . $customer_type ."' WHERE address_book_id = '" . $billto . "'");
                       
  			 $order->billing['entry_type'] = $customer_type;
	 
	 }
   
  
  
// load the selected shipping module
  require(DIR_WS_CLASSES . 'shipping.php');
  $shipping_modules = new shipping($shipping);

  require(DIR_WS_CLASSES . 'order_total.php');
  $order_total_modules = new order_total;

// Stock Check
  $any_out_of_stock = false;
  if (STOCK_CHECK == 'true') {
//++++ QT Pro: Begin Changed code
    $check_stock='';
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
      if (isset($order->products[$i]['attributes']) && is_array($order->products[$i]['attributes'])) {
        $attributes=array();
        foreach ($order->products[$i]['attributes'] as $attribute) {
          $attributes[$attribute['option_id']]=$attribute['value_id'];
        }
        $check_stock[$i] = tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty'], $attributes);
      } else {
        $check_stock[$i] = tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty']);
      }
      if ($check_stock[$i]) {
        $any_out_of_stock = true;
      }
//++++ QT Pro: End Changed Code

    }
    // Out of Stock
    if ( (STOCK_ALLOW_CHECKOUT != 'true') && ($any_out_of_stock == true) ) {
      tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
    }
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_CONFIRMATION);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2);
 
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_confirmation.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
<?php
  if ($sendto != false) {
?>
            <td width="30%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
<? //---PayPal WPP Modification START ---//-- ?>
<?php if ($ec_checkout && $ec_enabled && empty($_SESSION['paypal_ec_markflow'])) { ?>
                <td class="main"><?php echo '<b>' . HEADING_DELIVERY_ADDRESS . '</b> <a href="' . tep_href_link(FILENAME_EC_PROCESS, 'clearSess=1', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>        
<?php } else { ?>
                <td class="main"><?php echo '<b>' . HEADING_DELIVERY_ADDRESS . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
<?php } ?>
<? //---PayPal WPP Modification END ---//-- ?>
              </tr>
              <tr>
                <td class="main"><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br>'); ?></td>
              </tr>
<?php
    if ($order->info['shipping_method']) {
?>
              <tr>
                <td class="main"><?php echo '<b>' . HEADING_SHIPPING_METHOD . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo $order->info['shipping_method']; ?></td>
              </tr>
<?php
    }
?>
            </table></td>
<?php
  }
?>
            <td width="<?php echo (($sendto != false) ? '70%' : '100%'); ?>" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if (sizeof($order->info['tax_groups']) > 1) {
?>
                  <tr>
                    <td class="main" colspan="2"><?php echo '<b>' . HEADING_PRODUCTS . '</b> <a href="' . tep_href_link(FILENAME_SHOPPING_CART) . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
                    <td class="smallText" align="right"><b><?php echo HEADING_TAX; ?></b></td>
                    <td class="smallText" align="right"><b><?php echo HEADING_TOTAL; ?></b></td>
                  </tr>
<?php
  } else {
?>
                  <tr>
                    <td class="main" colspan="3"><?php echo '<b>' . HEADING_PRODUCTS . '</b> <a href="' . tep_href_link(FILENAME_SHOPPING_CART) . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
                  </tr>
<?php
  }

  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
    echo '          <tr>' . "\n" .
         '            <td class="main" align="right" valign="top" width="30">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
         '            <td class="main" valign="top">' . $order->products[$i]['name'];

    if (STOCK_CHECK == 'true') {
 //++++ QT Pro: Begin Changed code
      echo $check_stock[$i];
//++++ QT Pro: End Changed Code

    }
	$attributes=NULL;
    if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
        $attributes[$order->products[$i]['attributes'][$j]['option_id']]=$order->products[$i]['attributes'][$j]['value_id'];
      	echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . '</i></small></nobr>';
      }
    }

    echo '</td>' . "\n";

    if (sizeof($order->info['tax_groups']) > 1) echo '            <td class="main" valign="top" align="right">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n";

    echo '            <td class="main" align="right" valign="top">' . $pws_prices->getHtmlPriceResume($order->products[$i]['id'],$order->products[$i]['qty'],$attributes/*,false*/) /*$currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty'])*/ . '</td>' . "\n" .
         '          </tr>' . "\n";
  }
?>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo HEADING_BILLING_INFORMATION; ?></b></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td width="30%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
<? //---PayPal WPP Modification START ---// ?>
<? //I did this because $order->billing is getting erased somewhere and I haven't found where yet ?>
<?php if ($ec_checkout && $ec_enabled) { ?>
                <td class="main"><?php echo '<b>' . HEADING_BILLING_ADDRESS . '</b>'; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo ($ec_checkout ? '' : ''); ?></td>
              </tr>
              <tr>
<?php } else { ?>
<? //---PayPal WPP Modification END ---// ?>
                <td class="main"><?php echo '<b>' . HEADING_BILLING_ADDRESS . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo tep_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br>'); ?></td>
              </tr>
              <tr>
<? //---PayPal WPP Modification START ---// ?>
<?php }

      if ($show_payment_page || !$ec_enabled) { 
?> 
<? //---PayPal WPP Modification END ---// ?>
                <td class="main"><?php echo '<b>' . HEADING_PAYMENT_METHOD . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
<? //---PayPal WPP Modification START ---// ?>
<?php } else { ?>
                <td class="main"><?php echo '<b>' . HEADING_PAYMENT_METHOD . '</b>'; ?></td>
<?php } ?>
<? //---PayPal WPP Modification END ---// ?>
			  </tr>
              <tr>
                <td class="main"><?php echo $order->info['payment_method']; ?></td>
              </tr>
            </table></td>
            <td width="70%" valign="top" align="right"><table border="0" cellspacing="0" cellpadding="2" style="border-top:1px solid black">
<?php
  if (MODULE_ORDER_TOTAL_INSTALLED) {
    $order_total_modules->process();
    echo $order_total_modules->output();
  }
?>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
//---PayPal WPP Modification START ---//
if (!($ec_checkout && $ec_enabled)) {
  if (is_array($payment_modules->modules)) {
    if ($confirmation = $payment_modules->confirmation()) {
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo HEADING_PAYMENT_INFORMATION; ?></b></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" colspan="4"><?php echo $confirmation['title']; ?></td>
              </tr>
<?php
      for ($i=0, $n=sizeof($confirmation['fields']); $i<$n; $i++) {
?>
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main"><?php echo $confirmation['fields'][$i]['title']; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main"><?php echo $confirmation['fields'][$i]['field']; ?></td>
              </tr>
<?php
      }
?>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
    }
  }
}
//---PayPal WPP Modification END ---//
?>      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  if (tep_not_null($order->info['comments'])) {
?>
      <tr>
        <td class="main"><?php echo '<b>' . HEADING_ORDER_COMMENTS . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><?php echo nl2br(tep_output_string_protected($order->info['comments'])) . tep_draw_hidden_field('comments', $order->info['comments']); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>
<?php
  if (tep_not_null($order_reference)) {
?>
      <tr>
        <td class="main"><?php echo '<b>' . HEADING_ORDER_REFERENCE . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><?php echo nl2br(tep_output_string_protected($order_reference)) . tep_draw_hidden_field('order_reference', $order_reference); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>

<?php
  if (tep_not_null($order_contact)) {
?>
      <tr>
        <td class="main"><?php echo '<b>' . HEADING_ORDER_CONTACT . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><?php echo nl2br(tep_output_string_protected($order_contact)) . tep_draw_hidden_field('order_contact', $order_contact); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td align="right" class="main">
            
            <script type="text/javascript">
				function validate(form) {
				
				if(!document.getElementById('checkout_process').agree.checked){alert("<?php echo ALERT_TEXT_CONDITIONS ?>");
				return false; }
				
				
				return true;
				}
			</script>
			
			<script type="text/javascript">
			<!--
			 
			/*Popup javascript*/

			function popupWindow(url) {
			  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=450,height=280,screenX=150,screenY=150,top=150,left=150')
			}
			//-->
			</script>         
            
<?php

// if (TERMS_CONDITIONS == 'true')
// if (true)
$terms_query_on = tep_db_query("select pages_id from " . TABLE_PAGES . " where page_type = 4");
if(tep_db_num_rows($terms_query_on) >= '1')
{
	$terms_js = 'onsubmit="return validate(this)"';
     $checkbox_terms = "<input type=checkbox  name=agree value='yes'>";
     	 $checkbox_terms .= '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_TERMS_CONDITIONS_HELP) . '\')"> ' . TEXT_TERMS_CONDITIONS_LINK . '</a><br><br>';
     
}
  if (isset($$payment->form_action_url)) {
    $form_action_url = $$payment->form_action_url;
  } else {
    $form_action_url = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL');
  }

  echo tep_draw_form('checkout_confirmation', $form_action_url, 'post', 'id="checkout_process" ' .  $terms_js);

  echo $checkbox_terms ;
  
  if (is_array($payment_modules->modules)) {
    echo $payment_modules->process_button();
  }

  echo tep_image_submit('button_confirm_order.gif', IMAGE_BUTTON_CONFIRM_ORDER) . '</form>' . "\n";
?>
            </td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%" align="right"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
              </tr>
            </table></td>
<? //---PayPal WPP Modification START ---//-- ?>
<?php if ($show_payment_page || !$ec_enabled) { ?>
            <td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
<?php } ?>
<? //---PayPal WPP Modification END ---//-- ?>
            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
                <td><?php echo tep_image(DIR_WS_IMAGES . 'checkout_bullet.gif'); ?></td>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
              </tr>
            </table></td>
            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td align="center" width="25%" class="checkoutBarFrom"><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '" class="checkoutBarFrom">' . CHECKOUT_BAR_DELIVERY . '</a>'; ?></td>
<? //---PayPal WPP Modification START ---//-- ?>
<?php if ($show_payment_page || !$ec_enabled) { ?>
		<td align="center" width="25%" class="checkoutBarFrom"><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '" class="checkoutBarFrom">' . CHECKOUT_BAR_PAYMENT . '</a>'; ?></td>
<?php } ?>
<? //---e. WPP Modification END ---//-- ?>
            <td align="center" width="25%" class="checkoutBarCurrent"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></td>
            <td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_FINISHED; ?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
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
