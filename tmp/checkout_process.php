<?php
/*
      QT Pro Version 4.1 modifications
     
      Copyright (c) 2004, 2005 Ralph Day
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
                  Allow attribute stock to go negative
                  Save products_stock_attributes in order to facilitate remove order & restock
                  Miscellaneous cleanup
        02/2005 - Fix to allow downloads to be set to true
        04/2005 - Fix to properly save information in order for order removal, particularly for
                  downloadable products
       14-11-05 - Fix to properly save all missing att lists from orders_products.products_stock_attributes
                  mod was by Si - scranmer@consultant.com or simon@ibridge.co.uk
  
*******************************************************************************************

  $Id: checkout_process.php,v 1.128 2003/05/28 18:00:29 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  
  Amended for Attributes Inventory - FREEZEHELL - 08/11/2003 freezehell@hotmail.com
  Copyright (c) 2003 IBWO


  Released under the GNU General Public License
*/

  include('includes/application_top.php');

// if the customer is not logged on, redirect them to the login page
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_CHECKOUT_PAYMENT));
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
  
  if (!tep_session_is_registered('sendto')) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
  }

  if ( (tep_not_null(MODULE_PAYMENT_INSTALLED)) && (!tep_session_is_registered('payment')) ) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
 }

// avoid hack attempts during the checkout procedure by checking the internal cartID
  if (isset($cart->cartID) && tep_session_is_registered('cartID')) {
    if ($cart->cartID != $cartID) {
      tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
    }
  }

  include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PROCESS);
 
  
// load selected payment module
  require(DIR_WS_CLASSES . 'payment.php');
  $payment_modules = new payment($payment);

// load the selected shipping module
  require(DIR_WS_CLASSES . 'shipping.php');
  $shipping_modules = new shipping($shipping);

  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;


// load the before_process function from the payment modules
  $payment_modules->before_process();

  require(DIR_WS_CLASSES . 'order_total.php');
  $order_total_modules = new order_total;

  $order_totals = $order_total_modules->process();
  
   $sql_data_array = array('customers_id' => $customer_id,
                          'customers_name' => $order->customer['firstname'] . ' ' . $order->customer['lastname'],
                          'customers_company' => $order->customer['company'],
                          'customers_street_address' => $order->customer['street_address'],
                          'customers_suburb' => $order->customer['suburb'],
                          'customers_city' => $order->customer['city'],
                          'customers_postcode' => $order->customer['postcode'], 
                          'customers_state' => $order->customer['state'], 
                          'customers_country' => $order->customer['country']['title'], 
                          'customers_telephone' => $order->customer['telephone'], 
                          'customers_email_address' => $order->customer['email_address'],
                          'customers_address_format_id' => $order->customer['format_id'], 
                          'delivery_name' => trim($order->delivery['firstname'] . ' ' . $order->delivery['lastname']),
                          'delivery_company' => $order->delivery['company'],
                          'delivery_street_address' => $order->delivery['street_address'], 
                          'delivery_suburb' => $order->delivery['suburb'], 
                          'delivery_city' => $order->delivery['city'], 
                          'delivery_postcode' => $order->delivery['postcode'], 
                          'delivery_state' => $order->delivery['state'], 
                          'delivery_country' => $order->delivery['country']['title'], 
                          'delivery_address_format_id' => $order->delivery['format_id'], 
                          'billing_name' => $order->billing['firstname'] . ' ' . $order->billing['lastname'], 
                          'billing_company' => $order->billing['company'],
                          'billing_street_address' => $order->billing['street_address'], 
                          'billing_suburb' => $order->billing['suburb'], 
                          'billing_city' => $order->billing['city'], 
                          'billing_postcode' => $order->billing['postcode'], 
                          'billing_state' => $order->billing['state'], 
                          'billing_country' => $order->billing['country']['title'], 
                          'billing_address_format_id' => $order->billing['format_id'], 
    //PIVACF start
                          'billing_piva' => $order->billing['piva'],
                          'billing_cf' => $order->billing['cf'],
  						  'billing_type' => $order->billing['entry_type'],
    //PIVACF end
    // BERSANI start
                          'billing_company_cf' => $order->billing['company_cf'],
	// BERSANI stop
                          'payment_method' => $order->info['payment_method'], 
                          'cc_type' => $order->info['cc_type'], 
                          'cc_owner' => $order->info['cc_owner'], 
                          'cc_number' => $order->info['cc_number'], 
                          'cc_expires' => $order->info['cc_expires'], 
                          'date_purchased' => 'now()', 
                          'orders_status' => $order->info['order_status'], 
                          'currency' => $order->info['currency'], 
    					  'Vs_rif_ordine' =>  $order_reference,
  						  'Ordine_effetuato_da' => $order_contact,
                          'currency_value' => $order->info['currency_value']);
  
 
   
  tep_db_perform(TABLE_ORDERS, $sql_data_array);
  
  $insert_id = tep_db_insert_id();
  for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
    $sql_data_array = array('orders_id' => $insert_id,
                            'title' => $order_totals[$i]['title'],
                            'text' => $order_totals[$i]['text'],
                            'value' => $order_totals[$i]['value'], 
                            'class' => $order_totals[$i]['code'], 
                            'sort_order' => $order_totals[$i]['sort_order']);
    tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
  }

  $customer_notification = (SEND_EMAILS == 'true') ? '1' : '0';
  $sql_data_array = array('orders_id' => $insert_id, 
                          'orders_status_id' => $order->info['order_status'], 
                          'date_added' => 'now()', 
                          'customer_notified' => $customer_notification,
                          'comments' => $order->info['comments']);
  tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
  //kgt - discount coupons
  if (( defined('MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS') && MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS == 'true' )){
	  if( tep_session_is_registered( 'coupon' ) && is_object( $order->coupon ) ) {
		  $sql_data_array = array( 'coupons_id' => $order->coupon->coupon['coupons_id'],
	                             'orders_id' => $insert_id );
		  tep_db_perform( TABLE_DISCOUNT_COUPONS_TO_ORDERS, $sql_data_array );
	  }
  }
  //end kgt - discount coupons

// initialized for the email confirmation
/*  $products_ordered = '';
  $subtotal = 0;
  $total_tax = 0;
*/
  
 // modifica order email da admin 
$order_totals_table_beginn = "<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\">
";
$order_totals_zelle_beginn = "<tr><td  bgcolor=\"#ebebeb\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\" align=\"right\" Colspan=\"2\">";
$order_totals_zelle_mitte = "</td><td bgcolor=\"#ebebeb\"  style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\" align=\"right\" Colspan=\"2\">";
$order_totals_zelle_end = '</td></tr>';
$order_totals_table_end = '</table>';

$table_row_begin = '<tr>';
$table_data_begin = "<td  bgcolor=\"#ebebeb\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\">";
$table_data_end = "</td>";
$table_row_end = "</tr>
";
 


// initialized for the email confirmation
if (EMAIL_USE_HTML == 'true'){
//   $products_ordered = $order_totals_table_beginn . $table_row_begin ;

}
else{
  $products_ordered = '';
}

  $subtotal = 0;
  $total_tax = 0;

  // fine order email da admin
  
  
  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
// Stock Update - Joao Correia
//++++ QT Pro: Begin Changed code
    $products_stock_attributes=null;
    if (STOCK_LIMITED == 'true') {
        $products_attributes = $order->products[$i]['attributes'];
//      if (DOWNLOAD_ENABLED == 'true') {
//++++ QT Pro: End Changed Code
      $stock_query_raw = "SELECT products_quantity, pad.products_attributes_filename 
                          FROM " . TABLE_PRODUCTS . " p
                          LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                           ON p.products_id=pa.products_id
                          LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                           ON pa.products_attributes_id=pad.products_attributes_id
                          WHERE p.products_id = '" . tep_get_prid($order->products[$i]['id']) . "'";
// Will work with only one option for downloadable products
// otherwise, we have to build the query dynamically with a loop
//++++ QT Pro: Begin Changed code
//      $products_attributes = $order->products[$i]['attributes'];
//++++ QT Pro: End Changed Code
      if (is_array($products_attributes)) {
        $stock_query_raw .= " AND pa.options_id = '" . $products_attributes[0]['option_id'] . "' AND pa.options_values_id = '" . $products_attributes[0]['value_id'] . "'";
      }
      $stock_query = tep_db_query($stock_query_raw);
      if (tep_db_num_rows($stock_query) > 0) {
        $stock_values = tep_db_fetch_array($stock_query);
//++++ QT Pro: Begin Changed code
        $actual_stock_bought = $order->products[$i]['qty'];
        $download_selected = false;
        if ((DOWNLOAD_ENABLED == 'true') && isset($stock_values['products_attributes_filename']) && tep_not_null($stock_values['products_attributes_filename'])) {
          $download_selected = true;
          $products_stock_attributes='$$DOWNLOAD$$';
        }
//      If not downloadable and attributes present, adjust attribute stock
        if (!$download_selected && is_array($products_attributes)) {
          $all_nonstocked = true;
          $products_stock_attributes_array = array();
          foreach ($products_attributes as $attribute) {

//**si** 14-11-05 fix missing att list
//            if ($attribute['track_stock'] == 1) {
//              $products_stock_attributes_array[] = $attribute['option_id'] . "-" . $attribute['value_id'];
$products_stock_attributes_array[] = $attribute['option_id'] . "-" . $attribute['value_id'];
if ($attribute['track_stock'] == 1) {
//**si** 14-11-05 end

              $all_nonstocked = false;
            }
          } 
          if ($all_nonstocked) {
            $actual_stock_bought = $order->products[$i]['qty'];

//**si** 14-11-05 fix missing att list
asort($products_stock_attributes_array, SORT_NUMERIC);
$products_stock_attributes = implode(",", $products_stock_attributes_array);
//**si** 14-11-05 end

          }  else {
            asort($products_stock_attributes_array, SORT_NUMERIC);
            $products_stock_attributes = implode(",", $products_stock_attributes_array);
            $attributes_stock_query = tep_db_query("select products_stock_quantity from " . TABLE_PRODUCTS_STOCK . " where products_stock_attributes = '$products_stock_attributes' AND products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
            if (tep_db_num_rows($attributes_stock_query) > 0) {
              $attributes_stock_values = tep_db_fetch_array($attributes_stock_query);
              $attributes_stock_left = $attributes_stock_values['products_stock_quantity'] - $order->products[$i]['qty'];
              tep_db_query("update " . TABLE_PRODUCTS_STOCK . " set products_stock_quantity = '" . $attributes_stock_left . "' where products_stock_attributes = '$products_stock_attributes' AND products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
              $actual_stock_bought = ($attributes_stock_left < 1) ? $attributes_stock_values['products_stock_quantity'] : $order->products[$i]['qty'];
            } else {
              $attributes_stock_left = 0 - $order->products[$i]['qty'];
              tep_db_query("insert into " . TABLE_PRODUCTS_STOCK . " (products_id, products_stock_attributes, products_stock_quantity) values ('" . tep_get_prid($order->products[$i]['id']) . "', '" . $products_stock_attributes . "', '" . $attributes_stock_left . "')");
              $actual_stock_bought = 0;
            }
          }
        }
//        $stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
//      }
//      if (tep_db_num_rows($stock_query) > 0) {
//        $stock_values = tep_db_fetch_array($stock_query);
// do not decrement quantities if products_attributes_filename exists
        if (!$download_selected) {
          $stock_left = $stock_values['products_quantity'] - $actual_stock_bought;
          tep_db_query("UPDATE " . TABLE_PRODUCTS . " 
                        SET products_quantity = products_quantity - '" . $actual_stock_bought . "' 
                        WHERE products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
//++++ QT Pro: End Changed Code
          if ( ($stock_left < 1) && (STOCK_ALLOW_CHECKOUT == 'false') ) {
            tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
          }
        }
      }
//++++ QT Pro: Begin Changed code
    }


//**si** 14-11-05 fix missing att list
else {
	if ( is_array($order->products[$i]['attributes']) ) {
	  $products_stock_attributes_array = array();
	  foreach ($order->products[$i]['attributes'] as $attribute) {
	      $products_stock_attributes_array[] = $attribute['option_id'] . "-" . $attribute['value_id'];
		}
		asort($products_stock_attributes_array, SORT_NUMERIC);
		$products_stock_attributes = implode(",", $products_stock_attributes_array);
	}
}
//**si** 14-11-05 end



//++++ QT Pro: End Changed Code
// Update products_ordered (for bestsellers list)
    tep_db_query("update " . TABLE_PRODUCTS . " set products_ordered = products_ordered + " . sprintf('%d', $order->products[$i]['qty']) . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");

//PWS bof
	$attributes=NULL;
    if (isset($order->products[$i]['attributes'])){
	    $attributes=array();
    	for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
	    	$attributes[$order->products[$i]['attributes'][$j]['option_id']]=$order->products[$i]['attributes'][$j]['value_id'];
    	}
    }
//PWS eof
//++++ QT Pro: Begin Changed code
    if (!isset($products_stock_attributes)) $products_stock_attributes=null;
    $sql_data_array = array('orders_id' => $insert_id, 
                            'products_id' => tep_get_prid($order->products[$i]['id']), 
                            'products_model' => $order->products[$i]['model'], 
                            'products_name' => $order->products[$i]['name'], 
//PWS bof
							'products_price' => $pws_prices->getFirstPrice($order->products[$i]['id'])/*$order->products[$i]['price']*/, 
                            'final_price' => $pws_prices->getLastPrice($order->products[$i]['id'],$order->products[$i]['qty'],$attributes)/*$order->products[$i]['final_price']*/, 
//PWS eof
                            'products_tax' => $order->products[$i]['tax'], 
                            'products_quantity' => $order->products[$i]['qty'],
                            'products_stock_attributes' => $products_stock_attributes
//PWS bof
						//	,'pws_price_resume'=>$pws_prices->getPriceResume($order->products[$i]['id'],$order->products[$i]['qty'],$attributes/*,false*/)
//PWS eof
    );
//++++ QT Pro: End Changed Code
    tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);
    $order_products_id = tep_db_insert_id();

//------insert customer choosen option to order--------
    $attributes_exist = '0';
    $products_ordered_attributes = '';
    if (isset($order->products[$i]['attributes'])) {
      $attributes_exist = '1';
      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
        if (DOWNLOAD_ENABLED == 'true') {
          $attributes_query = "select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix, pad.products_attributes_maxdays, pad.products_attributes_maxcount , pad.products_attributes_filename 
                               from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa 
                               left join " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                                on pa.products_attributes_id=pad.products_attributes_id
                               where pa.products_id = '" . $order->products[$i]['id'] . "' 
                                and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' 
                                and pa.options_id = popt.products_options_id 
                                and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' 
                                and pa.options_values_id = poval.products_options_values_id 
                                and popt.language_id = '" . $languages_id . "' 
                                and poval.language_id = '" . $languages_id . "'";
          $attributes = tep_db_query($attributes_query);
        } else {
          $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $order->products[$i]['id'] . "' and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "'");
        }
        $attributes_values = tep_db_fetch_array($attributes);

        $sql_data_array = array('orders_id' => $insert_id, 
                                'orders_products_id' => $order_products_id, 
                                'products_options' => $attributes_values['products_options_name'],
                                'products_options_values' => $attributes_values['products_options_values_name'], 
                                'options_values_price' => $attributes_values['options_values_price'], 
                                'price_prefix' => $attributes_values['price_prefix']);
        tep_db_perform(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);

        if ((DOWNLOAD_ENABLED == 'true') && isset($attributes_values['products_attributes_filename']) && tep_not_null($attributes_values['products_attributes_filename'])) {
          $sql_data_array = array('orders_id' => $insert_id, 
                                  'orders_products_id' => $order_products_id, 
                                  'orders_products_filename' => $attributes_values['products_attributes_filename'], 
                                  'download_maxdays' => $attributes_values['products_attributes_maxdays'], 
                                  'download_count' => $attributes_values['products_attributes_maxcount']);
          tep_db_perform(TABLE_ORDERS_PRODUCTS_DOWNLOAD, $sql_data_array);
        }
        $products_ordered_attributes .= "\n\t* " . $attributes_values['products_options_name'] . ' ' . $attributes_values['products_options_values_name'];
      }
    }
//------insert customer choosen option eof ----


// modifica mail edit da admin   

    $total_weight += ($order->products[$i]['qty'] * $order->products[$i]['weight']);
    $total_tax += tep_calculate_tax($total_products_price, $products_tax) * $order->products[$i]['qty'];
    $total_cost += $total_products_price;
    
    //PWS bof
	$attributes=NULL;
    if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
        $attributes[$order->products[$i]['attributes'][$j]['option_id']]=$order->products[$i]['attributes'][$j]['value_id'];
      }
    }
    $details=unserialize($pws_prices->getPriceResume($order->products[$i]['id'],$order->products[$i]['qty'],$attributes/*,false*/));
    
    
      if (EMAIL_USE_HTML == 'true'){ // mail in formato html
         //if(PRODUCT_INFO_MODEL == 'true') {
 		$products_ordered .= $table_row_begin;
 		
        $products_ordered .= $table_data_begin;
 		$products_ordered .=  $order->products[$i]['model'];
		$products_ordered .= $table_data_end;

		$products_ordered .= $table_data_begin;
 		$products_ordered .=  $order->products[$i]['name'] . $products_ordered_attributes ;
		$products_ordered .= $table_data_end;		
		
		$products_ordered .= $table_data_begin;
 		$products_ordered .=  $order->products[$i]['qty'] ;
		$products_ordered .= $table_data_end;		

		$products_ordered .= $table_data_begin;
 		$products_ordered .=  $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) ;
		$products_ordered .= $table_data_end ;	
		
		
		$products_ordered .= $table_row_end;
 		//	$products_ordered .= $order_totals_zelle_beginn . $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ' (' . $order->products[$i]['model'] . ') = ' . $order_totals_zelle_mitte . $pws_prices->formatTextPriceResume($details) . $products_ordered_attributes . $order_totals_zelle_end;
	  	// } else {
       //   $products_ordered .= $order_totals_zelle_beginn . $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ' = ' . $order_totals_zelle_mitte . $pws_prices->formatTextPriceResume($details) . $products_ordered_attributes . $order_totals_zelle_end;
	 // 	}	
      } else {
       if ($order->products[$i]['model']) { 
	$products_ordered .= $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ' (' . $order->products[$i]['model'] . ') = ' . $pws_prices->formatTextPriceResume($details) . $products_ordered_attributes . "\n";	
	} else {
	$products_ordered .= $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ' = ' . $pws_prices->formatTextPriceResume($details) . $products_ordered_attributes . "\n";	
	}
   }

 } 

  if (EMAIL_USE_HTML == 'true'){
  $products_ordered .=  $order_totals_table_end; // chiusura tabella prodotti
}
 if (EMAIL_USE_HTML == 'true'){
  $text_query = tep_db_query("SELECT * FROM eorder_text where eorder_text_id = '2' and language_id = '" . $languages_id . "'");	
}
else{
  $text_query = tep_db_query("SELECT * FROM eorder_text where eorder_text_id = '1' and language_id = '" . $languages_id . "'");
}
        $werte = tep_db_fetch_array($text_query);
        $text = $werte["eorder_text_one"];
	$text = preg_replace('/<-STORE_NAME->/', STORE_NAME, $text);
	$text = preg_replace('/<-insert_id->/', $insert_id , $text);
	$text = preg_replace('/<-INVOICE_URL->/', tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $insert_id, 'SSL', false), $text);
	$text = preg_replace('/<-DATE_ORDERED->/', strftime(DATE_FORMAT_LONG) . ' IP:' . $_SERVER[ 'REMOTE_ADDR' ] , $text);
	  if ($order->info['comments']) {
		$text = preg_replace('/<-Customer_Comments->/', tep_db_output($order->info['comments']), $text);
	  }
	  else{
	  	$text = preg_replace('/<-Customer_Comments->/', '', $text);
	  }  
	$text = preg_replace('/<-Item_List->/', $products_ordered, $text);
if (EMAIL_USE_HTML == 'true'){	// totali ordine
	
	//    $list_total = $order_totals_table_beginn;
	    for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
	    	
		$list_total .= $order_totals_zelle_beginn . strip_tags($order_totals[$i]['title']) . $order_totals_zelle_mitte . strip_tags($order_totals[$i]['text']) . $order_totals_zelle_end;
		}
	//    $list_total .= $order_totals_table_end;
}
else{
	    for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
		$list_total .= strip_tags($order_totals[$i]['title']) . ' ' . strip_tags($order_totals[$i]['text']) . "\n";
		}
}	
	$text = preg_replace('/<-List_Total->/', $list_total, $text); 
	if ($order->content_type != 'virtual') {
		$text = preg_replace('/<-DELIVERY_Adress->/', tep_address_label($customer_id, $sendto, 0, '', "\n"), $text);
	}
	elseif($order->content_type == 'virtual') {	
		if ((DOWNLOAD_ENABLED == 'true') && isset($attributes_values['products_attributes_filename']) && tep_not_null($attributes_values['products_attributes_filename'])) {
		  $text = preg_replace('/<-DELIVERY_Adress->/', EMAIL_TEXT_DOWNLOAD_SHIPPING . "\n" . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $insert_id, 'SSL', false), $text);
		}
		else{
		  $text = preg_replace('/<-DELIVERY_Adress->/', '', $text);
		}	
	}
	else{
	  $text = preg_replace('/<-DELIVERY_Adress->/', '', $text);
	}
	$text = preg_replace('/<-BILL_Adress->/', tep_address_label($customer_id, $billto, 0, '', "\n"), $text);  
	  if (is_object($$payment)) {
	    $payment_class = $$payment;
	    $text = preg_replace('/<-Payment_Modul_Text->/', $payment_class->title, $text);
	    if ($payment_class->email_footer) { 
	      $text = preg_replace('/<-Payment_Modul_Text_Footer->/', $payment_class->email_footer, $text);
	    } else {
              $text = preg_replace('/<-Payment_Modul_Text_Footer->/', '', $text); //rg add to email conf mod
	    }		  
	  }
	  
    $email_order = $text; 
 
    
 //   $email_order = wordwrap( $email_order, 80);
   
   // fine modifica mail edit da admin 
    
  
//
 // $email_order = @html_entity_decode($email_order,ENT_QUOTES,'ISO8859-15');
  // var_dump($email_order);die("<br/>\nSTOP<br/>");
  tep_mail($order->customer['firstname'] . ' ' . $order->customer['lastname'], $order->customer['email_address'], EMAIL_TEXT_SUBJECT . '  ' . $insert_id , $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

// send emails to other people
  if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
    tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, EMAIL_TEXT_SUBJECT . '  ' . $insert_id, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
  }

//PWS bof
        $pws_engine->triggerHook('CATALOG_CHECKOUT_PROCESSED');
//PWS eof
  
// PWS affiliates bof
  if (file_exists(DIR_WS_INCLUDES . 'affiliate_checkout_process.php')){
	require(DIR_WS_INCLUDES . 'affiliate_checkout_process.php');
  }
// PWS affiliates eof
  
// load the after_process function from the payment modules
  $payment_modules->after_process();
  
  // caso numera post finale delle variabili
  /*
if ($payment == 'numera')
{
  $postvars = 'pol_vendor=' . $payment_modules->pol_vendor . '&pol_keyord=' . $oid;
  $payment_modules->postXML('https://web.numera.it/npgw3/www/numgwp1.asp', $postvars );
} 
*/
  $cart->reset(true);

// unregister session variables used during checkout
  tep_session_unregister('sendto');
  tep_session_unregister('billto');
  tep_session_unregister('shipping');
  tep_session_unregister('payment');
  tep_session_unregister('comments');
  tep_session_unregister('order_reference');
  tep_session_unregister('order_contact');
  //kgt - discount coupons
  if (( defined('MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS') && MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS == 'true' )){
  	tep_session_unregister('coupon');
  }
	//end kgt - discount coupons 
  
  // aggiungere alri metodi di pagamento con redirect qui
  
   if ($payment == 'agos')
            { 
            	Header ("Location: agos_redir.php?oid=$insert_id");
            	exit;
            }        
   elseif ($payment == 'consel')
            { 
            	Header ("Location: consel_redir.php?oid=$insert_id");
            	exit;
            }         
   elseif ($payment == 'cimitalia') { 
	
				Header ("Location: cimitalia_redir.php?oid=$insert_id");
				exit;		
   			}
   elseif ($payment == 'bankpass') { 
	
				Header ("Location: bankpass_redir.php?oid=$insert_id");
				exit;		
   			}
   elseif ($payment == 'virtualpos')
	            {

	  		   Header ("Location: virtualpos_redir.php?oid=$insert_id");
	            exit;
	            } 			
   elseif ($payment == 'numera')
	            {

	  		   Header ("Location: numera_redir.php?oid=$insert_id");
	            exit;
	            } 	
   elseif ($payment == 'xpay')
	            {

	  		   Header ("Location: xpay_redir.php?oid=$insert_id");
	            exit;
	            } 	      
  elseif ($payment == 'pagoonline')
	            {

	  		   Header ("Location: pagoonline_redir.php?oid=$insert_id");
	            exit;
	            } 	     	
  elseif ($payment == 'payway')
	            {

	  		   Header ("Location: payway_redir.php?oid=$insert_id");
	            exit;
	            } 	                       
  else 
  {    
   	tep_redirect(tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL'));
  }

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
