<?php
/*
 $Id: paypal_ipn.php,v 2.1.0.0 13/01/2007 16:30:21 Edith Karnitsch Exp $

 Copyright (c) 2004 osCommerce
 Released under the GNU General Public License

 Original Authors: Harald Ponce de Leon, Mark Evans
 Updates by PandA.nl, Navyhost, Zoeticlight, David, gravyface, AlexStudio, windfjf, Monika in Germany and Terra

 */
if (!function_exists('file_put_contents')){
	function file_put_contents($filename,$content){
		$fp=fopen($filename,'w');
		fputs($fp,$content);
		fclose($fp);
	}
}

chdir('../../../../');
require('includes/application_top.php');
include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PROCESS);

$email_cr="\r\n";
$parameters = 'cmd=_notify-validate';

foreach ($_POST as $key => $value) {
	$parameters .= '&' . $key . '=' . urlencode(stripslashes($value));
}

if (MODULE_PAYMENT_PAYPAL_DP_SERVER == 'live') {
	$server = 'www.paypal.com';
} else {
	$server = 'www.sandbox.paypal.com';
}
$debug_email_body = '$_POST:' . $email_cr.$email_cr;
foreach ($_POST as $key => $value) {
	$debug_email_body .= $key . '=' . $value . $email_cr;
}
$debug_email_body .= $email_cr . '$_GET:' . $email_cr.$email_cr;
foreach ($_GET as $key => $value) {
	$debug_email_body .= $key . '=' . $value . $email_cr;
}
$debug_email_body.=$email_cr.$email_cr."Http Referer: ".$_SERVER['HTTP_REFERER'].$email_cr;
$debug_email_body.="Remote Host: ".$_SERVER['REMOTE_HOST'].$email_cr;
$debug_email_body.="Remote IP: ".$_SERVER['REMOTE_ADDR'].$email_cr;
$debug_email_body.="Parameters:$parameters$email_cr";
$debug_email_body.="Method for callback:";
$fsocket = false;
$curl = false;
$result = false;

if ( (PHP_VERSION >= 4.3) && ($fp = @fsockopen('ssl://' . $server, 443, $errno, $errstr, 30)) ) {
	$fsocket = true;
	$debug_email_body.='fsocket';
} elseif (function_exists('curl_exec')) {
	$curl = true;
	$debug_email_body.='curl';
} elseif ($fp = @fsockopen($server, 80, $errno, $errstr, 30)) {
	$fsocket = true;
	$debug_email_body.='fsocket';
} else {
	$debug_email_body.='none!';
}
$debug_email_body.=$email_cr;
if ($fsocket == true) {
	$header = 'POST /cgi-bin/webscr HTTP/1.0' . "\r\n" .
              'Host: ' . $server . "\r\n" .
              'Content-Type: application/x-www-form-urlencoded' . "\r\n" .
              'Content-Length: ' . strlen($parameters) . "\r\n" .
              'Connection: close' . "\r\n\r\n";

	@fputs($fp, $header . $parameters);

	$string = '';
	while (!@feof($fp)) {
		$res = @fgets($fp, 1024);
		$string .= $res;

		if ( ($res == 'VERIFIED') || ($res == 'INVALID') ) {
			$result = $res;

			break;
		}
	}

	@fclose($fp);
} elseif ($curl == true) {
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, 'https://' . $server . '/cgi-bin/webscr');
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	$result = curl_exec($ch);

	curl_close($ch);
}
$debug_email_body.="Result:$result$email_cr";
if (strlen(MODULE_PAYMENT_PAYPAL_IPN_DEBUG_EMAIL)){
	file_put_contents(DIR_FS_CACHE.'paypal_test_notify_'.date('Ymd_his').'.txt',$debug_email_body);
	tep_mail('giulio',MODULE_PAYMENT_PAYPAL_IPN_DEBUG_EMAIL,'debug ipn',$debug_email_body,'giulio',MODULE_PAYMENT_PAYPAL_IPN_DEBUG_EMAIL);
}

if ($result == 'VERIFIED') {
	if (isset($_POST['invoice']) && is_numeric($_POST['invoice']) && ($_POST['invoice'] > 0)) {
		$order_query = tep_db_query("select * from " . TABLE_ORDERS . " where orders_id = '" . $_POST['invoice'] . "' and customers_id = '" . (int)$_POST['custom'] . "'");
		if (tep_db_num_rows($order_query) > 0) {
			$order_db = tep_db_fetch_array($order_query);

			require(DIR_WS_CLASSES . 'payment.php');
			$payment_modules = new payment('paypal_ipn');

			// let's re-create the required arrays
			require(DIR_WS_CLASSES . 'order.php');
			$order = new order($_POST['invoice']);

			// Carica la configurazione del gruppo clienti, se installato il plugin
			if ($pws_engine->isInstalledPlugin('pws_prices_customers_groups','prices')){
				$plugin_cgroups=$pws_engine->getPlugin('pws_prices_customers_groups','prices');
				$plugin_cgroups->setCustomersGroup($plugin_cgroups->getCustomerGroupId($_POST['custom']));
			}
			// Controlla se questo ordine è già stato confermato
			$duplicate_confirmation = $_POST['payment_status'] == 'Completed' && 
				( (MODULE_PAYMENT_PAYPAL_IPN_COMP_ORDER_STATUS_ID > 0 && $order_db['orders_status']==MODULE_PAYMENT_PAYPAL_IPN_COMP_ORDER_STATUS_ID)
				|| (!(MODULE_PAYMENT_PAYPAL_IPN_COMP_ORDER_STATUS_ID > 0) && MODULE_PAYMENT_PAYPAL_IPN_ORDER_STATUS_ID > 0 && $order_db['orders_status']==MODULE_PAYMENT_PAYPAL_IPN_ORDER_STATUS_ID));


			// let's update the order status
			$total_query = tep_db_query("select value from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . $_POST['invoice'] . "' and class = 'ot_total' limit 1");
			$total = tep_db_fetch_array($total_query);

			$comment_status = $_POST['payment_status'] . ' (' . ucfirst($_POST['payer_status']) . '; ' . $currencies->format($_POST['mc_gross'], false, $_POST['mc_currency']) . ')';

			if ($_POST['payment_status'] == 'Pending') {
				$comment_status .= '; ' . $_POST['pending_reason'];
			} elseif ( ($_POST['payment_status'] == 'Reversed') || ($_POST['payment_status'] == 'Refunded') ) {
				$comment_status .= '; ' . $_POST['reason_code'];
			} elseif ( ($_POST['payment_status'] == 'Completed') && (MODULE_PAYMENT_PAYPAL_IPN_SHIPPING == 'True') ) {
				$comment_status .= ", \n" . PAYPAL_ADDRESS . ": " . $_POST['address_name'] . ", " . $_POST['address_street'] . ", " . $_POST['address_city'] . ", " . $_POST['address_zip'] . ", " . $_POST['address_state'] . ", " . $_POST['address_country'] . ", " . $_POST['address_country_code'] . ", " . $_POST['address_status'];
			}
			if ($duplicate_confirmation){
				$comment_status .= ", redundant notification";
			}
				
			$order_status_id = DEFAULT_ORDERS_STATUS_ID;

			// modified AlexStudio's Rounding error bug fix
			// variances of up to 0.05 on either side (plus / minus) are ignored
			if (
			(((number_format($total['value'] * $order_db['currency_value'], $currencies->get_decimal_places($order_db['currency']))) -  $_POST['mc_gross']) <= 0.05)
			&&
			(((number_format($total['value'] * $order_db['currency_value'], $currencies->get_decimal_places($order_db['currency']))) -  $_POST['mc_gross']) >= -0.05)
			) {

				// previous validation
				//        if ($_POST['mc_gross'] == number_format($total['value'] * $order_db['currency_value'], $currencies->get_decimal_places($order_db['currency']))) {

				// Terra -> modified update. If payment status is "completed" than a completed order status is chosen based on the admin settings
				if ( (MODULE_PAYMENT_PAYPAL_IPN_COMP_ORDER_STATUS_ID > 0) && ($_POST['payment_status'] == 'Completed') ) {
					$order_status_id = MODULE_PAYMENT_PAYPAL_IPN_COMP_ORDER_STATUS_ID;
				} elseif (MODULE_PAYMENT_PAYPAL_IPN_ORDER_STATUS_ID > 0) {
					$order_status_id = MODULE_PAYMENT_PAYPAL_IPN_ORDER_STATUS_ID;
				}

			}

			// Let's see what the PayPal payment status is and set the notification accordingly
			// more info: https://www.paypal.com/IntegrationCenter/ic_ipn-pdt-variable-reference.html
			if ( ($_POST['payment_status'] == 'Pending') || ($_POST['payment_status'] == 'Completed')) {
				$customer_notified = '1';
			} else {
				$customer_notified = '0';
			}


			tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . $order_status_id . "', last_modified = now() where orders_id = '" . $_POST['invoice'] . "'");

			$sql_data_array = array('orders_id' => $_POST['invoice'],
                                'orders_status_id' => $order_status_id,
                                'date_added' => 'now()',
                                'customer_notified' => $customer_notified,
                                'comments' => 'PayPal IPN Verified [' . $comment_status . ']');

			tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);

			// If the order is pending, then we want to send a notification email to the customer
				
			// If the order is completed, then we want to send the order email and update the stock
			if (!$duplicate_confirmation && $_POST['payment_status'] == 'Completed') { // START STATUS == COMPLETED LOOP

				// initialized for the email confirmation
				$products_ordered = '';
				$total_tax = 0;


				// let's update the stock
				#######################################################
				for ($i=0, $n=sizeof($order->products); $i<$n; $i++) { // PRODUCT LOOP STARTS HERE
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



					// Let's get all the info together for the email
					$total_weight += ($order->products[$i]['qty'] * $order->products[$i]['weight']);
					$total_tax += tep_calculate_tax($total_products_price, $products_tax) * $order->products[$i]['qty'];
					$total_cost += $total_products_price;

					// Let's get the attributes
					$products_ordered_attributes = '';
					if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
						for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
							$products_ordered_attributes .= "\n\t" . $order->products[$i]['attributes'][$j]['option'] . ' ' . $order->products[$i]['attributes'][$j]['value'];
						}
					}

					// Let's format the products model
					$products_model = '';
					if ( !empty($order->products[$i]['model']) ) {
						$products_model = ' (' . $order->products[$i]['model'] . ')';
					}

					// Let's put all the product info together into a string
					//PWS bof
					$attributes=NULL;
					if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
						for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
							$attributes[$order->products[$i]['attributes'][$j]['option_id']]=$order->products[$i]['attributes'][$j]['value_id'];
						}
					}
					$details=unserialize($pws_prices->getPriceResume($order->products[$i]['id'],$order->products[$i]['qty'],$attributes/*,false*/));
					//    print_r($details);
					//      $products_ordered .= $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . $products_model . ' = ' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . $products_ordered_attributes . "\n";
					$products_ordered .= $order->products[$i]['name'] . ' (' . $order->products[$i]['model'] . ')'. "\n";
					$products_ordered .= $pws_prices->formatTextPriceResume($details)."\n";
					//PWS eof


				}        // PRODUCT LOOP ENDS HERE
				#######################################################


				// lets start with the email confirmation
				// $order variables have been changed from checkout_process to work with the variables from the function query () instead of cart () in the order class
				$email_order = STORE_NAME . "\n" .
				EMAIL_SEPARATOR . "\n" .
				EMAIL_TEXT_ORDER_NUMBER . ' ' . $_POST['invoice'] . "\n" .
				EMAIL_TEXT_INVOICE_URL . ' ' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $_POST['invoice'], 'SSL', false) . "\n" .
				EMAIL_TEXT_DATE_ORDERED . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n";
				if ($order->info['comments']) {
					$email_order .= tep_db_output($order->info['comments']) . "\n\n";
				}
				$email_order .= EMAIL_TEXT_PRODUCTS . "\n" .
				EMAIL_SEPARATOR . "\n" .
				$products_ordered .
				EMAIL_SEPARATOR . "\n";

				for ($i=0, $n=sizeof($order->totals); $i<$n; $i++) {
					$email_order .= strip_tags($order->totals[$i]['title']) . ' ' . strip_tags($order->totals[$i]['text']) . "\n";
				}

				if ($order->content_type != 'virtual') {
					$email_order .= "\n" . EMAIL_TEXT_DELIVERY_ADDRESS . "\n" .
					EMAIL_SEPARATOR . "\n" .
					tep_address_format($order->delivery['format_id'], $order->delivery,  0, '', "\n") . "\n";
				}
				$email_order .= ENTRY_EMAIL_ADDRESS ."\t{$order->customer['email_address']}\n";
				if ($order->customer['telephone']!=''){
					$email_order .= ENTRY_TELEPHONE_NUMBER ."\t{$order->customer['telephone']}\n\n";
				}else{
  					$email_order .= "\n";
 				}
				
				$email_order .= "\n" . EMAIL_TEXT_BILLING_ADDRESS . "\n" .
				EMAIL_SEPARATOR . "\n" .
				tep_address_format($order->billing['format_id'], $order->billing, 0, '', "\n") . "\n\n";
				 if ($order->billing['entry_type']=='company'){
				 	if ($order->billing['company_cf']!=''){
				 		$email_order .=  ENTRY_COMPANY_CF."\t{$order->billing['company_cf']}\n\n";
				} else if ($order->billing['piva']!=''){
				 		$email_order .=  ENTRY_PIVA."\t{$order->billing['piva']}\n\n";
				 	}
				 }else if ($order->billing['cf']!=''){
				 	$email_order .=  ENTRY_CF."\t{$order->billing['cf']}\n\n";
				 }
				 
				if (is_object($$payment)) {
					$email_order .= EMAIL_TEXT_PAYMENT_METHOD . "\n" .
					EMAIL_SEPARATOR . "\n";
					$payment_class = $$payment;
					$email_order .= $payment_class->title . "\n\n";
					if ($payment_class->email_footer) {
						$email_order .= $payment_class->email_footer . "\n\n";
					}
				}
				$email_order=@html_entity_decode($email_order,ENT_QUOTES,'UTF-8');
				tep_mail($order->customer['name'], $order->customer['email_address'], EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);


				// send emails to other people
				if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
					tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
				}

				//emptying cart for everyone! by Monika in Germany
				tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$_POST['custom'] . "'");
				tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$_POST['custom'] . "'");
				//end emptying cart for everyone


			} // END STATUS == COMPLETED LOOP

			if ($_POST['payment_status'] == 'Pending') { // START STATUS == PENDING LOOP

				$email_order = STORE_NAME . "\n" .
				EMAIL_SEPARATOR . "\n" .
				EMAIL_TEXT_ORDER_NUMBER . ' ' . $_POST['invoice'] . "\n" .
				EMAIL_TEXT_INVOICE_URL . ' ' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $_POST['invoice'], 'SSL', false) . "\n" .
				EMAIL_TEXT_DATE_ORDERED . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n" .
				EMAIL_SEPARATOR . "\n" .
				EMAIL_PAYPAL_PENDING_NOTICE . "\n\n";

				tep_mail($order->customer['name'], $order->customer['email_address'], EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);


				// send emails to other people
				if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
					tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
				}
			} // END STATUS == PENDING LOOP

		}
	}
} else {
	if (tep_not_null(MODULE_PAYMENT_PAYPAL_IPN_DEBUG_EMAIL)) {
		$email_body = '$_POST:' . "\n\n";
		foreach ($_POST as $key => $value) {
			$email_body .= $key . '=' . $value . "\n";
		}
		$email_body .= "\n" . '$_GET:' . "\n\n";
		foreach ($_GET as $key => $value) {
			$email_body .= $key . '=' . $value . "\n";
		}

		tep_mail('', MODULE_PAYMENT_PAYPAL_IPN_DEBUG_EMAIL, 'PayPal IPN Invalid Process', $email_body, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
	}

	if (isset($_POST['invoice']) && is_numeric($_POST['invoice']) && ($_POST['invoice'] > 0)) {
		$check_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . $_POST['invoice'] . "' and customers_id = '" . (int)$_POST['custom'] . "'");
		if (tep_db_num_rows($check_query) > 0) {
			$comment_status = $_POST['payment_status'];

			if ($_POST['payment_status'] == 'Pending') {
				$comment_status .= '; ' . $_POST['pending_reason'];
			} elseif ( ($_POST['payment_status'] == 'Reversed') || ($_POST['payment_status'] == 'Refunded') ) {
				$comment_status .= '; ' . $_POST['reason_code'];
			}

			tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . ((MODULE_PAYMENT_PAYPAL_IPN_ORDER_STATUS_ID > 0) ? MODULE_PAYMENT_PAYPAL_IPN_ORDER_STATUS_ID : DEFAULT_ORDERS_STATUS_ID) . "', last_modified = now() where orders_id = '" . $_POST['invoice'] . "'");

			$sql_data_array = array('orders_id' => $_POST['invoice'],
                                'orders_status_id' => (MODULE_PAYMENT_PAYPAL_IPN_ORDER_STATUS_ID > 0) ? MODULE_PAYMENT_PAYPAL_IPN_ORDER_STATUS_ID : DEFAULT_ORDERS_STATUS_ID,
                                'date_added' => 'now()',
                                'customer_notified' => '0',
                                'comments' => 'PayPal IPN Invalid [' . $comment_status . ']');

			tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
		}
	}
}
require('includes/application_bottom.php');
?>
