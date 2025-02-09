<?php
/*
  PayPal module for osCommerce, Open Source E-Commerce Solutions
  Author: Riccardo Roscilli
  email: info@oscommerce.it
  
  v2.20b - IT-commerce
*/

require('includes/application_top.php');
require('includes/classes/http_client.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT); // per l'invio della mail di conferma
include('constants.php');


if(MODULE_PAYMENT_PPEC_TRANSACTION_SERVER == "PayPal Live Server") 
	{

	$API_UserName=API_USERNAME;
	$API_Password=API_PASSWORD;
	$API_Signature=API_SIGNATURE;

	$subject = MODULE_PAYMENT_PPEC_ID;
	}
else // sandbox api credentials 
	{
	
	$API_UserName="info_1189066795_biz_api1.promowebstudio.net";
	$API_Password="1189066814";
	$API_Signature="AVzMfDfEWL1LDRKh-nRbFKVeUsUiApBgLHd5ltxSer10HkfoqCQMnhsN";
	$subject = 'info@promowebstudio.net'; // questo account ha concesso le autorizzazioni al PPEC per info_1189066795_biz_api1.promowebstudio.net
	}
	
	

$API_Endpoint =API_ENDPOINT;
$ALERTMAIL = ALERTMAIL;
$version = VERSION;
$CERT_FILE_PATH = MODULE_PAYMENT_PPEC_CERT_PATH;

$notif = MODULE_PAYMENT_PPEC_IPN;
$LOGO = LOGO;
$ccurl = CCURL;
$com = '';
$lg;
$im;
$currtag;
$arrtag = array();
$ctotal = 0; $cshipping = 0; $classot = array();

$error_show_array = array('SHORTMESSAGE','LONGMESSAGE','ERRORCODE','TIMESTAMP','ACK');
$ppec_debug = PPEC_DEBUG;



$con = $_REQUEST['con'];
if( isset($con))
{
	ppecon();
} else{  // prima chiamata dal carrello

	$notif = $_REQUEST['notify_version'];
	/*
	print_r($_REQUEST);
			Array
			(
			    [shec] => a
			    [osCsid] => khnrua6g7ig7r540b7dqdc4e43
			    [cookie_test] => please_accept_for_session
			)
	exit;
	*/
	if( isset($notif)) // non e settato dalla chiamata dell express, salta alla riga 209
	{ 
							$req = 'cmd=_notify-validate';
						
							foreach ($_POST as $key => $value)
							{
								$value = urlencode(stripslashes($value));
								$req .= "&$key=$value";
							}
						
							// post back to PayPal system to validate
							$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
							$header .= "Host: www.paypal.com:80\r\n";
							$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
							$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
							$fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);
						
							// assign posted variables to local variables
							$item_name = $_POST['item_name'];
							$item_number = $_POST['item_number'];
							$payment_status = $_POST['payment_status'];
							$payment_amount = $_POST['mc_gross'];
							$payment_currency = $_POST['mc_currency'];
							$txn_id = $_POST['txn_id'];
							$receiver_email = $_POST['receiver_email'];
							$payer_email = $_POST['payer_email'];
						
						
							if (!$fp)
							{
								// HTTP ERROR
							} else {
								fputs ($fp, $header . $req);
								while (!feof($fp)) {
								$res = fgets ($fp, 1024);
								if (strcmp ($res, "VERIFIED") == 0)
								{
						
									$req .= "Transaction Details: \n\n";
									foreach ($_POST as $key => $value)
									{
										$value = urldecode(stripslashes($value));
										$req .=  $value."\n";
							
										switch ($key) {
											case 'txn_id': 
													$code = $value; 									               
													break;
											case 'payment_status': 
													$status = $value; 
													break;
										}
									}
									
									
									tep_db_query("update ppec_transaction set paymentstatus = '".$status."' where transactionid = '" .$code. "'");
									$status = strtoupper($status);
									$ord_id = tep_db_query("SELECT status_id as st FROM ppec_transaction_status Where transaction_status = '" .$status. "'");
									$ordid = tep_db_fetch_array($ord_id);
									$st = $ordid['st'];
									$ord_id = tep_db_query("SELECT orders_id as or FROM ppec_transaction Where transactionid = '" .$code. "'");
									$ordid = tep_db_fetch_array($ord_id);
									$or = $ordid['or'];
									tep_db_query("update ".TABLE_ORDERS_STATUS." set orders_status = '".$st."' where orders_id = '" .$or. "'");
									$sql_data_array = array('orders_id' => $or,
																'orders_status_id' => $st,
																'date_added' => 'now()',
																'customer_notified' => '0',
																'comments' => '');
									tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
									
									$ord_id = tep_db_query("SELECT languages_id as la FROM languages Where code = 'fr'");
									$ordid = tep_db_fetch_array($ord_id);
									$la = $ordid['la'];
									$ord_id = tep_db_query("SELECT orders_status_name as na FROM ".TABLE_ORDERS_STATUS." Where orders_status_id = ".$st." and language_id = ".$la);
									$ordid = tep_db_fetch_array($ord_id);
									$na = $ordid['na'];
									
									
									global $ALERTMAIL;
									mail($ALERTMAIL, "PayPal Notification", $req);
								} else if (strcmp ($res, "INVALID") == 0) {
									// log for manual investigation
						
									$mail_From = $From_email;
									$mail_To = $receiver_email;
									$mail_Subject = "INVALID IPN POST";
									$mail_Body = "INVALID IPN POST. The raw POST string is below.\n\n" . $req;
									
									mail($ALERTMAIL, $mail_Subject, $mail_Body);
								}
							}
							fclose ($fp);
						}
					
						$req .= "Transaction Details: \n\n";
						foreach ($_POST as $key => $value) {
							$value = urldecode(stripslashes($value));
							$req .=  $value."\n";
							
							switch ($key) {
								case 'txn_id': 
									$code = $value; 									               
									break;
								case 'payment_status': 
									$status = $value; 
									break;
							}
						}
						 
						tep_db_query("update ppec_transaction set paymentstatus = '".$status."' where transactionid = '" .$code. "'");
						$status = strtoupper($status);
						$ord_id = tep_db_query("SELECT status_id as st FROM ppec_transaction_status Where transaction_status = '" .$status. "'");
						$ordid = tep_db_fetch_array($ord_id);
						$st = $ordid['st'];
						$ord_id = tep_db_query("SELECT orders_id as or FROM ppec_transaction Where transactionid = '" .$code. "'");
						$ordid = tep_db_fetch_array($ord_id);
						$or = $ordid['or'];
						tep_db_query("update ".TABLE_ORDERS_STATUS." set orders_status = '".$st."' where orders_id = '" .$or. "'");
						$sql_data_array = array('orders_id' => $or,
														'orders_status_id' => $st,
														'date_added' => 'now()',
														'customer_notified' => '0',
														'comments' => '');
						tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
						
						$ord_id = tep_db_query("SELECT languages_id as la FROM languages Where code = 'fr'");
						$ordid = tep_db_fetch_array($ord_id);
						$la = $ordid['la'];
						$ord_id = tep_db_query("SELECT orders_status_name as na FROM ".TABLE_ORDERS_STATUS." Where orders_status_id = ".$st." and language_id = ".$la);
						$ordid = tep_db_fetch_array($ord_id);
						$na = $ordid['na'];
						
						
						global $ALERTMAIL;
						mail($ALERTMAIL, "PayPal Notification", $req);
	} 
	else // prima chiamata entry point
	{
		
	// il geppec viene impostato solo al primo ritorno da paypal quando vengono impostati i dati per la spedizione	
	$geppec = $_REQUEST['geppec']; // in questo caso vale 1 almeno da quello che si vede nel dump del request
	/*
	 * Array
			(
			    [geppec] => 1
			    [cusid] => 5
			    [addid] => 8
			    [fn] => TEST
			    [cou] => 105
			    [zonid] => 0
			    [payid] => UNNFXQR52QEC6
			    [tok] => EC-3HU574976A400440B
			    [Amt] => 
			    [cur] => 
			    [payt] => 
			    [shec] => A
			    [osCsid] => khnrua6g7ig7r540b7dqdc4e43
			    [cookie_test] => please_accept_for_session
			)
	*/
	if( isset($geppec) ){  // alla prima chiamata non viene impostato il geppec a solo shec=a, quindi salta tutto questo if
	
									if(isset($shec) ) // in teoria sarebbe settato ad A ma c'è un &amp; prima quindi cade nella condizione ma sbaglia la variabile
									{
										
										$arruri = explode('&', urldecode($_SERVER['REQUEST_URI']));
										
										foreach ($arruri as $key => $value) 
										{
											//echo $key.":   ".$value."<br>";
											// start - fix to detect & and &amp; correctly								
											if (substr($value, 0, 4) == "amp;") {
												$valeu = substr($value, 4, strlen($value));
											}
											// end - fix to detect & and &amp; correctly									
											
											if (substr($value, 0, 6) == "cusid=")
											{
											  $customer_id = substr($value, 6, strlen($value));
											}
											else if (substr($value, 0, 4) == "tok=")	
												{									  
													$token = substr($value, 4, strlen($value));	
												}	
												else  if (substr($value, 0, 5) == "shec=")	
												{									  
													$shppec = substr($value, 5, strlen($value));	
												}	
												else  if (substr($value, 0, 6) == "payid=")	
												{									  
													$payerid = substr($value, 6, strlen($value));	
												}
												else  if (substr($value, 0, 4) == "Amt=")	
												{									  
													$paymentAmount = substr($value, 4, strlen($value));	
												}
												else  if (substr($value, 0, 4) == "cur=")	
												{									  
													$currencyCodeType = substr($value, 4, strlen($value));	
												}
												else  if (substr($value, 0, 5) == "payt=")	
												{									  
													$currencyCodeType = substr($value, 5, strlen($value));	
												}
										}  
									} else // tutte le volte che sbaglia il decode delle variabili in get cade qui 
										
									{
										// dobbiamo intercettare il $_REQUEST e riscriverlo correttamente oppure cambiare array
										 $arruri = explode('&amp;', urldecode($_SERVER['REQUEST_URI']));
										 foreach($arruri as $pair) // per ogni coppia nome valore... 
										 {
										 	$start = strpos($pair, "?");
										 	if($start >= 1)
										 	{
										 	$pair = substr($pair,$start + 1);
										 	}
							
										 	
										 	$key = explode('=', $pair);
										 
										 	$start = 0;
										 	// sviluppo dell'array associativo
										 	$array_get[$key[0]] = $key[1];
										 	
										 }
										 
										 $customer_id = $array_get['cusid'];
										 $token = $array_get['tok'];
										 $shppec = $array_get['shec'];
										 $payerid = $array_get['payid'];
										 
										 $paymentAmount = $array_get['Amt'];
										 $currencyCodeType = $array_get['cur'];
										 $paymentType = $array_get['payt'];
										 $payerid = $array_get['payid'];                      
									}
								
									if($shppec == 'A') // shipping express checkout ovvero interrogazione per le info di spedizione
									{
										 // dobbiamo intercettare il $_REQUEST e riscriverlo correttamente oppure cambiare array
										if (strstr($_SERVER['REQUEST_URI'], 'amp'))
										{
										
										$arruri = explode('&amp;', urldecode($_SERVER['REQUEST_URI']));
										
										
										foreach($arruri as $pair) // per ogni coppia nome valore... 
										 {
										 	$start = strpos($pair, "?");
										 	if($start >= 1)
										 	{
										 	$pair = substr($pair,$start + 1);
										 	}
							
										 	
										 	$key = explode('=', $pair);
										 
										 	$start = 0;
										 	// sviluppo dell'array associativo
										 	$array_get[$key[0]] = $key[1];
										 	
										 }
										 
										}
										else // se la codifica è corretta...
										{
											$array_get = $_REQUEST;
										}
										// print_r($array_get);
										// exit; 
										
										
										$cutomer_id = $array_get['cusid'];
										// $customer_default_address_id = $array_get['addid'];
										$sendto = $billto = $array_get['addid'];
										$customer_first_name = $array_get['fn'];    
										$customer_country_id = $array_get['cou'];    
										$customer_zone_id = $array_get['zonid']; 
										
										
										$paymentAmount = $array_get['Amt'];
										$currencyCodeType = $array_get['cur'];
										$paymentType = $array_get['payt'];
																				
										
										tep_session_register('customer_id');
										tep_session_register('payerid');
										tep_session_register('token');
										tep_session_register('paymentAmount');
										tep_session_register('currencyCodeType');
										tep_session_register('paymentType');
										
										tep_session_register('shppec');
										
										tep_session_register('customer_first_name');
										tep_session_register('customer_default_address_id');
										tep_session_register('customer_country_id');
										tep_session_register('customer_zone_id');
										
													   
										// $ord_id = tep_db_query("select customers_default_address_id as co from " .TABLE_CUSTOMERS." where customers_id = ".$customer_id);                        
										// $ordid = tep_db_fetch_array($ord_id);
										// $aid = $ordid['co'];
										
										
										
										// $sendto=$billto=$aid; 
										tep_session_register('sendto');
										tep_session_register('billto');
										
										
										$payment = 'ppec'; 
										tep_session_register('payment');  
										
										//print_r($_SESSION);
										//exit;
										
										tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
									} else {
										tep_session_register('payerid');
										tep_session_register('token');
										tep_session_register('paymentAmount');
										tep_session_register('currencyCodeType');
										tep_session_register('paymentType');
										tep_session_register('customer_id');
									/*	print "<pre>";
										print_r($_GET);
										print_r($_REQUEST);
										print "</pre>";
									 */
										 $addrid = tep_db_query("select customers_default_address_id from " .TABLE_CUSTOMERS." where customers_id = ".$customer_id);
										 $add = tep_db_fetch_array($addrid);
										 foreach ($add as $key => $value)
										 {                
											$aid = htmlspecialchars($value);                                
										 }
										  
										$sendto=$billto=$aid; 
										tep_session_register('sendto');
										tep_session_register('billto');
									//	$doppec = '1';
										
										global $ccurl;
										$shppec = 'A';
										
										tep_session_register('shppec');
										
										tep_redirect(tep_href_link('ppeb.php', 'doppec='.$doppec.'&shec='.$shppec, 'SSL'));
									}
	} else {
		// siamo sempre alla prima chiamata ancora non è stato fatto il post xml su paypal.

		$doppec = $_REQUEST['doppec'];
		
		
		
		if( isset($doppec) )  // momento in cui viene effettivamente chiesto il pagamento doppec = 1
										{
											
											if($shppec == 'A')
											{
												$cust_query = tep_db_query("show tables"); 
												
												$found = false;
												while($row = mysql_fetch_row($cust_query))
												{
													if($row[0] == "ppec_payer")
													{
														$found = true;
													}
												}
											
												/*
												if(!$found)  // se non trova le tabelle le ricrea (?? dovrebbe averle giè trovare al momento del get)
												{   
													tep_db_query("CREATE TABLE ppec_payer (customers_id int(15), payerid varchar(15))");
													tep_db_query("CREATE TABLE ppec_transaction (transactionid varchar(50), paymentstatus varchar(20), orders_id int(15))");
													tep_db_query("CREATE TABLE ppec_transaction_status (transaction_status varchar(20), status_id int(15))");
													
													$maxi = tep_db_query("SELECT MAX(orders_status_id) as max FROM " . TABLE_ORDERS_STATUS);
													$max_ar = tep_db_fetch_array($maxi);
													$max = $max_ar['max']+1;
													
													$cust_query = tep_db_query("SELECT languages_id, code FROM " . TABLE_LANGUAGES);  
													while($row = mysql_fetch_row($cust_query))  // inserisce gli status per i pagamenti paypal
														{           
															if(strtoupper($row[1]) == "FR")
																{
																	tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$max. "', '". (int)$row[0] ."', 'PayPal-Aucun')");  
																	$max++;
																	tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$max. "', '". (int)$row[0] ."', 'PayPal-Annulation')"); 
																	$max++;
																	tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$max. "', '". (int)$row[0] ."', 'PayPal-Terminee')");
																	$max++;			  
																	tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$max. "', '". (int)$row[0] ."', 'PayPal-Refusee')"); 
																	$max++;
																	tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$max. "', '". (int)$row[0] ."', 'PayPal-Expiree')"); 
																	$max++;
																	tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$max. "', '". (int)$row[0] ."', 'PayPal-Echouee')"); 
																	$max++;
																	tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$max. "', '". (int)$row[0] ."', 'PayPal-En traitement')");
																	$max++;			  
																	tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$max. "', '". (int)$row[0] ."', 'PayPal-Remboursee')"); 
																	$max++;
																	tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$max. "', '". (int)$row[0] ."', 'PayPal-Retournee')"); 
																	$max++;
																	tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$max. "', '". (int)$row[0] ."', 'PayPal-Traitee')"); 
																	$max++;
																	tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$max. "', '". (int)$row[0] ."', 'PayPal-Invalide')"); 
																}
															elseif(strtoupper($row[1]) == "IT")
																{
																	tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$max. "', '". (int)$row[0] ."', 'PayPal-Nessuno')");  
																	$max++;
																	tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$max. "', '". (int)$row[0] ."', 'PayPal-Annullamento')"); 
																	$max++;
																	tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$max. "', '". (int)$row[0] ."', 'PayPal-Completato')");
																	$max++;			  
																	tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$max. "', '". (int)$row[0] ."', 'PayPal-Rifiutato')"); 
																	$max++;
																	tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$max. "', '". (int)$row[0] ."', 'PayPal-Scaduto')"); 
																	$max++;
																	tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$max. "', '". (int)$row[0] ."', 'PayPal-Fallito')"); 
																	$max++;
																	tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$max. "', '". (int)$row[0] ."', 'PayPal-Pendente')");
																	$max++;			  
																	tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$max. "', '". (int)$row[0] ."', 'PayPal-Rimborsato')"); 
																	$max++;
																	tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$max. "', '". (int)$row[0] ."', 'PayPal-Respinto')"); 
																	$max++;
																	tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$max. "', '". (int)$row[0] ."', 'PayPal-Processato')"); 
																	$max++;
																	tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$max. "', '". (int)$row[0] ."', 'PayPal-Invalido')"); 
																}
																else /// default è inglese (strtoupper($row[1]) == "EN")
															{
																	tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$max. "', '". (int)$row[0] ."', 'PayPal-None')");  
																	$max++;
																	tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$max. "', '". (int)$row[0] ."', 'PayPal-Reversal')"); 
																	$max++;
																	tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$max. "', '". (int)$row[0] ."', 'PayPal-Completed')");
																	$max++;			  
																	tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$max. "', '". (int)$row[0] ."', 'PayPal-Denied')"); 
																	$max++;
																	tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$max. "', '". (int)$row[0] ."', 'PayPal-Expired')"); 
																	$max++;
																	tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$max. "', '". (int)$row[0] ."', 'PayPal-Failed')"); 
																	$max++;
																	tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$max. "', '". (int)$row[0] ."', 'PayPal-Pending')");
																	$max++;			  
																	tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$max. "', '". (int)$row[0] ."', 'PayPal-Refunded')"); 
																	$max++;
																	tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$max. "', '". (int)$row[0] ."', 'PayPal-Reversed')"); 
																	$max++;
																	tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$max. "', '". (int)$row[0] ."', 'PayPal-Processed')"); 
																	$max++;
																	tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$max. "', '". (int)$row[0] ."', 'Voided')"); 			 
																}
																
																
													}	   
													$max = $max - 10;
													tep_db_query("insert into ppec_transaction_status (transaction_status, status_id) values ('NONE', '" . (int)$max . "')");
													$max++;
													tep_db_query("insert into ppec_transaction_status (transaction_status, status_id) values ('CANCELED-REVERSAL', '" . (int)$max . "')");
													$max++;
													tep_db_query("insert into ppec_transaction_status (transaction_status, status_id) values ('COMPLETED', '" . (int)$max . "')");
													$max++;
													tep_db_query("insert into ppec_transaction_status (transaction_status, status_id) values ('DENIED', '" . (int)$max . "')");
													$max++;
													tep_db_query("insert into ppec_transaction_status (transaction_status, status_id) values ('EXPIRED', '" . (int)$max . "')");
													$max++;
													tep_db_query("insert into ppec_transaction_status (transaction_status, status_id) values ('FAILED', '" . (int)$max . "')");
													$max++;
													tep_db_query("insert into ppec_transaction_status (transaction_status, status_id) values ('PENDING', '" . (int)$max . "')");
													$max++;
													tep_db_query("insert into ppec_transaction_status (transaction_status, status_id) values ('REFUNDED', '" . (int)$max . "')");
													$max++;
													tep_db_query("insert into ppec_transaction_status (transaction_status, status_id) values ('REVERSED', '" . (int)$max . "')");
													$max++;
													tep_db_query("insert into ppec_transaction_status (transaction_status, status_id) values ('PROCESSED', '" . (int)$max . "')");
													$max++;
													tep_db_query("insert into ppec_transaction_status (transaction_status, status_id) values ('VOIDED', '" . (int)$max . "')");
												}
												*/
												$custid =urlencode( $_SESSION['customer_id']);
												$cust_add_id =urlencode( $_SESSION['customer_default_address_id']);
												$cust_country = urlencode($_SESSION['customer_country_id']);
												$customer_first_name = urlencode($_SESSION['customer_first_name']);
												$customer_zone_id = urlencode($_SESSION['customer_zone_id']);
												$payerid =urlencode( $_SESSION['payerid']);
												$token =urlencode( $_SESSION['token']);
												$paymentAmount = urlencode($_SESSION['paymentAmount']);
												$currencyCodeType = urlencode($_SESSION['currencyCodeType']);
												$paymentType = urlencode($_SESSION['paymentType']);
												$serverName = urlencode($_SERVER['SERVER_NAME']);
									
												reviewbasket();
															
												reviewtotal();
												
												global $ccurl;
												global $ctotal;
												global $cshipping;
												global $order;
												global $paypal_fee;  // commissione per ordini con paypal se esiste
												
												$mt = $ctotal - $cshipping;
												
												/* DATI per Wax Booster                               
								                  
													SHIPTONAME       Name                Lunghezza e limitazioni: 32 caratteri alfanumerici
													                                  Prima riga dell'indirizzo del cliente.
													                  Street1
													SHIPTOSTREET                      Lunghezza e limitazioni: 100 caratteri alfanumerici
													                                  Seconda riga dell'indirizzo del cliente.
													                  Street2
													SHIPTOSTREET2                     Lunghezza e limitazioni: 100 caratteri alfanumerici
													                                  Cittè dell'indirizzo del cliente
													                  CityName
													SHIPTOCITY                        Lunghezza e limitazioni: 40 caratteri alfanumerici
													                                  Provincia dell'indirizzo del cliente
													                  StateOrProvince
													SHIPTOSTATE
													                                  Lunghezza e limitazioni: 40 caratteri alfanumerici
													                                  Nazione del cliente.
													                  Country
													SHIPTOCOUNTRYCODE
													                                  2 caratteri alfanumerici, clicca qui per la lista
													                                  CAP dell'indirizzo del cliente
													                  PostalCode
													SHIPTOZIP
													                                  Lunghezza e limitazioni: 20 caratteri alfanumerici
													                                  Numero di telefono del cliente
													                  Phone
													SHIPTOPHONENUM
													                                  Indirizzo mail del cliente
													                  BuyerEmail
													EMAIL
										
												 
												
												 */
												
												
												 // modifica per i beni virtuali
												 // se     $shipping = false; e    $sendto = false; allora si tratta di beni virtuali, quindi 
												 // le info di spedizione sono le stesse del default address_book_id
												 if ( $shipping == false || $shipping == '') // beni solo per download
													 {
													 // 	print "ok siamo nel caso solo download";
													 	//print_r($_SESSION);
													 	// exit;
													 	$customer_address_query = tep_db_query("SELECT * from " . TABLE_ADDRESS_BOOK . " WHERE address_book_id = '" . $_SESSION['customer_default_address_id'] . "'"); 
														$customer_address = tep_db_fetch_array($customer_address_query); 
														
													 	$SHIPTONAME = $order->delivery['firstname'] . ' ' . $order->delivery['lastname']; 
															$SHIPTOSTREET = $customer_address['entry_street_address'];
															$SHIPTOSTREET2 = $customer_address['entry_suburb'];
															$SHIPTOCITY = $customer_address['entry_city'];
															$SHIPTOZIP = $customer_address['entry_postcode'];
															$SHIPTOSTATE = $customer_address['entry_state'];
															$EMAIL = $order->customer['email_address'];
															$PHONENUM = $order->customer['telephone'];
															$SHIPTOPHONENUM = $order->customer['telephone'];
															$ord_id = tep_db_query("SELECT countries_iso_code_2 as co FROM ".TABLE_COUNTRIES." Where countries_id = '".$customer_address['entry_country_id']."'");
															$ordid = tep_db_fetch_array($ord_id);
											
															if(strtoupper($ordid['co']) == "FX")
																$SHIPTOCOUNTRYCODE = "FR";
															else
																$SHIPTOCOUNTRYCODE = $ordid['co'];
													 	
													 }
												 else 
												 	{
															$SHIPTONAME = $order->delivery['firstname'] . ' ' . $order->delivery['lastname']; 
															$SHIPTOSTREET = $order->delivery['street_address'];
															$SHIPTOSTREET2 = $order->delivery['suburb'];
															$SHIPTOCITY = $order->delivery['city']; 
															$SHIPTOZIP = $order->delivery['postcode']; 
															$SHIPTOSTATE = $order->delivery['state'];
															$EMAIL = $order->customer['email_address'];
															$PHONENUM = $order->customer['telephone'];
															$SHIPTOPHONENUM = $order->customer['telephone'];							
															$ord_id = tep_db_query("SELECT countries_iso_code_2 as co FROM ".TABLE_COUNTRIES." Where countries_id = '".$order->delivery['country']['id']."'");
															$ordid = tep_db_fetch_array($ord_id);
														
															if(strtoupper($ordid['co']) == "FX")
																$SHIPTOCOUNTRYCODE = "FR";
															else
																$SHIPTOCOUNTRYCODE = $ordid['co'];
													}
												
												 
												if(round($mt, 2) <= 0)
												{
													tep_redirect(tep_href_link(FILENAME_SHOPPING_CART, '', 'SSL'));
												}
											
												$msg = "<?xml version='1.0' encoding='utf-8'?>".
												"<soap:Envelope xmlns:soap='http://schemas.xmlsoap.org/soap/envelope/' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xmlns:xsd='http://www.w3.org/2001/XMLSchema'>".
												"<soap:Header>".
												"<RequesterCredentials xmlns='urn:ebay:api:PayPalAPI'>".
												"<Credentials xmlns='urn:ebay:apis:eBLBaseComponents'>".
												"<Username>".$API_UserName."</Username>".
												"<Password>".$API_Password."</Password>";
												
												if (!file_exists(MODULE_PAYMENT_PPEC_CERT_PATH))    
													$msg .= "<Signature>".$API_Signature."</Signature>";
									
												$msg .= "<Subject>". $subject ."</Subject>".
												"</Credentials>".
												"</RequesterCredentials>".
												"</soap:Header>".
												"<soap:Body>".
												  "<DoExpressCheckoutPaymentReq xmlns='urn:ebay:api:PayPalAPI'>".
													"<DoExpressCheckoutPaymentRequest>".
													  "<Version xmlns='urn:ebay:apis:eBLBaseComponents'>3</Version>".
													  "<DoExpressCheckoutPaymentRequestDetails xmlns='urn:ebay:apis:eBLBaseComponents'>".
														"<PaymentAction>Sale</PaymentAction>".
														"<Token>".$token."</Token>".
														"<PayerID>".$payerid."</PayerID>".
														"<PaymentDetails>".
														  "<OrderTotal currencyID='".$currency."'>".round($ctotal,2)."</OrderTotal>".
														  "<OrderDescription></OrderDescription>".
														  "<ItemTotal currencyID='".$currency."'>".round($mt,2)."</ItemTotal>".
														  "<ShippingTotal currencyID='".$currency."'>".round($cshipping,2)."</ShippingTotal>".
														  "<HandlingTotal currencyID='".$currency."'></HandlingTotal>".
														  "<TaxTotal currencyID='".$currency."'></TaxTotal>".
														  "<Custom></Custom>".
														  "<InvoiceID></InvoiceID>";
								
												// separiamo i bncode
												if($ppec_mode == 'mark') // arriva dal mark
													$msg .=	 "<ButtonSource>IT_oscommercepws-ECM</ButtonSource>";
												else // arriva dallo short
													$msg .=	 "<ButtonSource>IT_oscommercepws-ECS</ButtonSource>";
								
													$msg .=	  "<NotifyURL>".MODULE_PAYMENT_PPEC_IPN."</NotifyURL>";
																				
												
														 $msg .= "<ShipToAddress>".
															"<Name>".$SHIPTONAME."</Name>".
															"<Street1>".$SHIPTOSTREET."</Street1>".
															"<Street2>".$SHIPTOSTREET2."</Street2>".
															"<CityName>".$SHIPTOCITY."</CityName>". 
															"<StateOrProvince>".$SHIPTOSTATE."</StateOrProvince>".
															"<Country>".$SHIPTOCOUNTRYCODE."</Country>".
														 	"<PostalCode>".$SHIPTOZIP."</PostalCode>".
														 	"<Phone>".$PHONENUM."</Phone>".
														 	"<BuyerEmail>".$EMAIL."</BuyerEmail>".
														  "</ShipToAddress>".
														"</PaymentDetails>".
													  "</DoExpressCheckoutPaymentRequestDetails>".
													"</DoExpressCheckoutPaymentRequest>".
												  "</DoExpressCheckoutPaymentReq>".	  
												"</soap:Body>".
												"</soap:Envelope>";  
												
												// tep_mail( 'oscommerce', 'info@oscommerce.it','BN Code', $msg, 'ppec','info@modulioscommerce.biz');
												
										
												$ch = curl_init();
												curl_setopt($ch, CURLOPT_POST,1);  
												curl_setopt($ch, CURLOPT_URL,$API_Endpoint);  
												curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
												curl_setopt($ch, CURLOPT_VERBOSE, 1);
													
												if(USE_PROXY)
													curl_setopt ($ch, CURLOPT_PROXY, PROXY_HOST.":".PROXY_PORT); 
																	
												if (file_exists(MODULE_PAYMENT_PPEC_CERT_PATH))
												{        
													curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
													curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
													curl_setopt($ch, CURLOPT_TIMEOUT, 180);
													curl_setopt($ch, CURLOPT_SSLCERTTYPE, "PEM"); 
													curl_setopt($ch, CURLOPT_SSLCERT, MODULE_PAYMENT_PPEC_CERT_PATH); 
												} else {
													curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  
													curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
												}
										
												curl_setopt($ch,CURLOPT_POSTFIELDS,$msg);	
									
												$result=curl_exec ($ch);
												curl_close ($ch);
											
												$sax = xml_parser_create();
												xml_parser_set_option($sax, XML_OPTION_CASE_FOLDING, false);
												xml_parser_set_option($sax, XML_OPTION_SKIP_WHITE, true);
												xml_set_element_handler($sax, 'sax_start', 'sax_end');
												xml_set_character_data_handler($sax, 'sax_cdata');
												xml_parse($sax, $result, true);
												xml_parser_free($sax);		
												sax_start($sax, $name, $attributes);
												$resArray = $arrtag;
											
												$_SESSION['reshash']=$resArray;	  	  
									
												$ack = strtoupper($resArray["ACK"]);
												
												tep_session_unregister('comments');
												tep_session_unregister('shppec');
															
												if($ack!="SUCCESS")
												{
													echo "ERROR:"."<br>";
													foreach ($arrtag as $key => $value) 
													{
														if (in_array($key, $error_show_array) || $ppec_debug) 
														{
															echo $key.": ".$value."<br>";     
														}	
													} 
												} else {		
													PPECprocess($resArray['TRANSACTIONID'], strtoupper($resArray['PAYMENTSTATUS']));
													tep_session_unregister('comments');
													tep_session_unregister('shppec');
													$cart->reset(true); // 
													tep_redirect(tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL'));
												}
											} else {
												$shppec = 'b'; 
											 
												global $order;
												require(DIR_WS_CLASSES . 'payment.php');
												require(DIR_WS_CLASSES . 'shipping.php');
												$shipping_modules = new shipping($shipping);
												
												
												reviewbasket();                          
												reviewtotal();
												global $ctotal;
												global $cshipping;
												$mt = $ctotal - $cshipping;
																 
												tep_session_register('shppec');                
												
												tep_redirect(tep_href_link('ppeb.php', 'shec=b', 'SSL'));        
											}		
										} 
								else // alternativo al doppec, primo post xml su paypal SetExpressCheckoutRequest 
										{
											// ci inventiamo una sessione
												
												session_name('ppec');
												session_id('12345');
											 
												$token = $_REQUEST['token']; // lo restituisce dopo il primo post XML
										
												if(! isset($token)) // siamo qui alla prima chiamata
												{
													$serverName = $_SERVER['SERVER_NAME'];
													$serverPort = $_SERVER['SERVER_PORT'];
													// set the type of request (secure or not)
													$request_type = ($_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
													$url=dirname($request_type.$serverName.':'.$serverPort.$_SERVER['REQUEST_URI']);
													
													global $cart;
													
													if($cart->show_total() <= 0)  // se il carrello è vuoto ...
													{
														tep_redirect(tep_href_link(FILENAME_SHOPPING_CART, '', 'SSL'));
													}
												   
													$paymentAmount=$cart->show_total();
													
													$currencyCodeType=$currency;
													
													$paymentType='Sale';
													
													$shppec=$_REQUEST['shec'];
													
													tep_session_register('paymentAmount');
													tep_session_register('currencyCodeType');
													tep_session_register('paymentType');
													
													tep_session_register('shppec'); // shipping ec
													
													//global $customer_id; // nello shortcut non è valorizzato

													$request_SSL = ($_SERVER['HTTPS'] == 'on') ? 'SSL' : 'NONSSL';
													
													//$returnURL = $url.'/ppeb.php';
													$returnURL = tep_href_link('ppeb.php', '', $request_SSL);       
													
													//$cancelURL = $url.'/'.FILENAME_SHOPPING_CART;
													
													$cancelURL = tep_href_link(FILENAME_SHOPPING_CART, '', $request_SSL);	
														  
													global $lg;
													ppebin();  // imposta il linguaggio per la pagina di pagamento PP
													
													
													global $com;
													if($shppec == 'b') // solo al secondo giro quando si procede con il pagamento oppure dal MARK
														{
															require(DIR_WS_CLASSES . 'order.php');
															$order = new order;
															
															$paymentAmount = $order->info['total']* (1 + $paypal_fee) ;	  			
															
															$com = "&useraction=commit";
																			
															$SHIPTONAME = $order->delivery['firstname'] . ' ' . $order->delivery['lastname']; 
															$SHIPTOSTREET = $order->delivery['street_address'];
															$SHIPTOSTREET2 = $order->delivery['suburb'];
															$SHIPTOCITY = $order->delivery['city']; 
															$SHIPTOZIP = $order->delivery['postcode']; 
															$SHIPTOSTATE = $order->delivery['state'];
															$EMAIL = $order->customer['email_address'];
															$PHONENUM = $order->customer['telephone'];
															$ord_id = tep_db_query("SELECT countries_iso_code_2 as co FROM ".TABLE_COUNTRIES." Where countries_id = '".$order->delivery['country_id']."'");
															$ordid = tep_db_fetch_array($ord_id);
															if(strtoupper($ordid['co']) == "FX")
																$SHIPTOCOUNTRYCODE = "FR";
															else
																$SHIPTOCOUNTRYCODE = $ordid['co'];
															
															$nvpstr = $nvpstr."&EMAIL=".$EMAIL."&SHIPTOCOUNTRYCODE=".$SHIPTOCOUNTRYCODE."&SHIPTOZIP=".$SHIPTOZIP."&SHIPTOCITY=".$SHIPTOCITY."&SHIPTOSTREET=".$SHIPTOSTREET."&SHIPTOSTREET2=".$SHIPTOSTREET2."&SHIPTONAME=".$SHIPTONAME;
														}
												
														$nvpstr = $nvpstr.$com;
												 
														$msg = "<?xml version='1.0' encoding='utf-8'?>".
														"<soap:Envelope xmlns:soap='http://schemas.xmlsoap.org/soap/envelope/' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xmlns:xsd='http://www.w3.org/2001/XMLSchema'>".
														"<soap:Header>".
														"<RequesterCredentials xmlns='urn:ebay:api:PayPalAPI'>".
														"<Credentials xmlns='urn:ebay:apis:eBLBaseComponents'>".
														"<Username>".$API_UserName."</Username>".
														"<Password>".$API_Password."</Password>";
														
														if (!file_exists(MODULE_PAYMENT_PPEC_CERT_PATH))    
															$msg .= "<Signature>".$API_Signature."</Signature>";
										
														$msg .= "<Subject>". $subject ."</Subject>".
														"</Credentials>".
														"</RequesterCredentials>".
														"</soap:Header>".
														"<soap:Body>".
														"<SetExpressCheckoutReq xmlns='urn:ebay:api:PayPalAPI'>".
														"<SetExpressCheckoutRequest>".
														"<Version xmlns='urn:ebay:apis:eBLBaseComponents'>".$version."</Version>".
														"<SetExpressCheckoutRequestDetails xmlns='urn:ebay:apis:eBLBaseComponents'>".
														"<OrderTotal currencyID='".$currencyCodeType."'>".round($paymentAmount, 2)."</OrderTotal>".
														"<Custom>".$shppec.$customer_id."</Custom>".
														"<ButtonSource>IT_oscommercepws-ECS</ButtonSource>".
														"<ReturnURL>".$returnURL."</ReturnURL>".
														"<CancelURL>".$cancelURL."</CancelURL>".
														"<ReqConfirmShipping>"."0"."</ReqConfirmShipping>".
														"<NoShipping>"."0"."</NoShipping>".
														"<AddressOverride>"."1"."</AddressOverride>".
														"<LocaleCode>".$lg."</LocaleCode>".
														"<PageStyle>"."</PageStyle>";
										
														$serverName = $_SERVER['SERVER_NAME'];
														$serverPort = $_SERVER['SERVER_PORT'];
														// set the type of request (secure or not)
														$request_type = ($_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';	  
														$url=dirname($request_type.$serverName.':'.$serverPort.$_SERVER['REQUEST_URI']);
														 
														$msg .="<cpp-header-image>".$url."/ppec/ppec_image/".LOGO."</cpp-header-image>".
														"<cpp-header-border-color>"."</cpp-header-border-color>".
														"<cpp-header-back-color>"."</cpp-header-back-color>".
														"<cpp-payflow-color>"."</cpp-payflow-color>".
														"<PaymentAction>"."Sale"."</PaymentAction>".
														"<Address>".
														"<Name>".$SHIPTONAME."</Name>".
														"<Street1>".$SHIPTOSTREET."</Street1>".
														"<Street2>".$SHIPTOSTREET2."</Street2>".
														"<CityName>".$SHIPTOCITY."</CityName>". 
														"<StateOrProvince>".$SHIPTOSTATE."</StateOrProvince>".
														"<PostalCode>".$SHIPTOZIP."</PostalCode>".
														"<Country>".$SHIPTOCOUNTRYCODE."</Country>".
														"</Address>".
														"<SolutionType>"."Sole"."</SolutionType>".
														"<LandingPage>"."Billing"."</LandingPage>".
														"</SetExpressCheckoutRequestDetails>".
														"</SetExpressCheckoutRequest>". 
														"</SetExpressCheckoutReq>".
														"</soap:Body>".
														"</soap:Envelope>";  
														
														
														
														$ch = curl_init();
														curl_setopt($ch, CURLOPT_POST,1);  
														curl_setopt($ch, CURLOPT_URL,$API_Endpoint);  
														curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
														curl_setopt($ch, CURLOPT_VERBOSE, 1);
														
														if(USE_PROXY)
															curl_setopt ($ch, CURLOPT_PROXY, PROXY_HOST.":".PROXY_PORT); 
															
													
														if (file_exists(MODULE_PAYMENT_PPEC_CERT_PATH))
														{    
														   
															curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
															curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
															curl_setopt($ch, CURLOPT_TIMEOUT, 180);
															curl_setopt($ch, CURLOPT_SSLCERTTYPE, "PEM"); 
															curl_setopt($ch, CURLOPT_SSLCERT, MODULE_PAYMENT_PPEC_CERT_PATH); 
														} else {
															curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  
															curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
														}
											
														curl_setopt($ch,CURLOPT_POSTFIELDS,$msg);	
														
														$result=curl_exec ($ch);
														curl_close ($ch);
														
														$sax = xml_parser_create();
														xml_parser_set_option($sax, XML_OPTION_CASE_FOLDING, false);
														xml_parser_set_option($sax, XML_OPTION_SKIP_WHITE, true);
														
														xml_set_element_handler($sax, 'sax_start', 'sax_end');
														xml_set_character_data_handler($sax, 'sax_cdata');
														xml_parse($sax, $result, true);
														xml_parser_free($sax);		
														sax_start($sax, $name, $attributes);
														$resArray = $arrtag;
														
														$_SESSION['reshash']=$resArray;
														$ack = strtoupper($resArray["ACK"]);
														
														if($ack=="SUCCESS") // al primo giro non fa molto... serve solo per avere indietro il token da PP
															{
																$token = urldecode($resArray["TOKEN"]);
																$ecom = $com;
																
																$payPalURL = PAYPAL_URL.$token.$ecom;
														 
																if($shppec == 'b')
																	{
																	echo "<meta http-equiv='refresh' content='0;url=".$payPalURL."'/>";
																	echo "PayPal server is processing, please wait ....</meta>"; 
																	} 
																else 
																	{
																	header("Location: ".$payPalURL);	
																	}					                   
															 } 
														 else  
														 	{	// errore 	stampa un po di roba           
																	if ($ppec_debug)
																			{
																				echo "<br>";
																				echo "montant: ".$paymentAmount."<br>";
																				echo "OrderTotal currencyID=".$currencyCodeType." ".$paymentAmount." OrderTotal "."<br>";
																				print_r($resArray);
																				foreach ($order->info as $key => $value)
																				{
																					echo $key.": ".$value."<br>";    
																				}
																			}
																	echo "ERROR:"."<br>";
																	foreach ($arrtag as $key => $value)
																			{
																				if (in_array($key, $error_show_array) || $ppec_debug)
																				{
																					 echo $key.": ".$value."<br>";    
																				}
																			}
															}
												} 
											else // se è già stato impostato il token inviamo una nuova richiesta per i dati del cliente
												{  
														
														$msg = "<?xml version='1.0' encoding='utf-8'?>".
														"<soap:Envelope xmlns:soap='http://schemas.xmlsoap.org/soap/envelope/' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xmlns:xsd='http://www.w3.org/2001/XMLSchema'>".
														"<soap:Header>".
														"<RequesterCredentials xmlns='urn:ebay:api:PayPalAPI'>".
														"<Credentials xmlns='urn:ebay:apis:eBLBaseComponents'>".
														"<Username>".$API_UserName."</Username>".
														"<Password>".$API_Password."</Password>";
														
														if (!file_exists(MODULE_PAYMENT_PPEC_CERT_PATH))    
															$msg .= "<Signature>".$API_Signature."</Signature>";
										
														$msg .= "<Subject>". $subject ."</Subject>".
														"</Credentials>".
														"</RequesterCredentials>".
														"</soap:Header>".
														"<soap:Body>".
														"<GetExpressCheckoutDetailsReq xmlns='urn:ebay:api:PayPalAPI'>".
														"<GetExpressCheckoutDetailsRequest>".
														"<Version xmlns='urn:ebay:apis:eBLBaseComponents'>".$version."</Version>".
														"<Token>".$token."</Token>".
														"</GetExpressCheckoutDetailsRequest>".
														"</GetExpressCheckoutDetailsReq>".
														"</soap:Body>".
														"</soap:Envelope>";
												
														
														$ch = curl_init();
														curl_setopt($ch, CURLOPT_POST,1);  
														curl_setopt($ch, CURLOPT_URL,$API_Endpoint);  
														curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
														curl_setopt($ch, CURLOPT_VERBOSE, 1);
														
														if(USE_PROXY)
															curl_setopt ($ch, CURLOPT_PROXY, PROXY_HOST.":".PROXY_PORT); 
															
														if (file_exists(MODULE_PAYMENT_PPEC_CERT_PATH))
															{        
																curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
																curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
																curl_setopt($ch, CURLOPT_TIMEOUT, 180);
																curl_setopt($ch, CURLOPT_SSLCERTTYPE, "PEM"); 
																curl_setopt($ch, CURLOPT_SSLCERT, MODULE_PAYMENT_PPEC_CERT_PATH); 
															} 
														else 
															{
																curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  
																curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
															}
											
														curl_setopt($ch,CURLOPT_POSTFIELDS,$msg);	
														
														$result=curl_exec ($ch);
														curl_close ($ch);

														$sax = xml_parser_create();
														xml_parser_set_option($sax, XML_OPTION_CASE_FOLDING, false);
														xml_parser_set_option($sax, XML_OPTION_SKIP_WHITE, true);
														xml_parser_set_option($sax, XML_OPTION_TARGET_ENCODING, 'ISO-8859-1');
														xml_set_element_handler($sax, 'sax_start', 'sax_end');														xml_set_element_handler($sax, 'sax_start', 'sax_end');
														xml_set_character_data_handler($sax, 'sax_cdata');
														xml_parse($sax, $result, true);
														xml_parser_free($sax);		
														sax_start($sax, $name, $attributes);
														$resArray = $arrtag;
													
														require(DIR_WS_CLASSES . 'order.php');
														$order = new order;  // simuliamo un ordine tipico
														
														$_SESSION['reshash']=$resArray;
														//print_r($resArray);
														
														$ack = strtoupper($resArray["ACK"]);
															/*
															 Array
																(
																    [SIGNATURE] => AVZMFDFEWL1LDRKH-NRBFKVEUSUIAPBGLHD5LTXSER10HKFOQCQMNHSN
																    [TIMESTAMP] => 2009-12-16T14:56:02Z
																    [ACK] => SUCCESS
																    [CORRELATIONID] => 2FF3821156423
																    [VERSION] => 3.0
																    [BUILD] => 1105502
																    [TOKEN] => EC-7BJ09658C9247722T
																    [PAYER] => INFO_1259441171_PER@PROMOWEBSTUDIO.NET
																    [PAYERID] => UNNFXQR52QEC6
																    [PAYERSTATUS] => VERIFIED
																    [FIRSTNAME] => TEST
																    [LASTNAME] => USER
																    [PAYERCOUNTRY] => IT
																    [NAME] => TEST USER
																    [STREET1] => VIA TEST
																    [STREET2] => 545
																    [CITYNAME] => ROMA
																    [STATEORPROVINCE] => ROMA
																    [COUNTRY] => IT
																    [COUNTRYNAME] => ITALY
																    [POSTALCODE] => 00100
																    [ADDRESSOWNER] => PAYPAL
																    [ADDRESSSTATUS] => UNCONFIRMED
																    [CUSTOM] => A
																)

															 */
														// print_r($resArray);
														// exit;
														
														if( $ack == "SUCCESS" )
															{	
																$ci = substr($resArray["CUSTOM"], 1, strlen($resArray["CUSTOM"]));
																$sh = substr($resArray["CUSTOM"], 0, 1);
																$sh = strtoupper($sh);
																if($sh == 'A') // primo giro inserisce i dati nel database e crea eventualmente le tabelle
																{
																	$_SESSION['shppec'] = 'A';						 
																	
																	databasemodif($resArray, $token, $paymentAmount, $currencyCodeType, $paymentType, $sh);
																	
																} 
																else
																{// secondo giro 
																	$_SESSION['shppec'] = 'b';
																	$payerID = htmlspecialchars($resArray['PAYERID']);
																	global $ccurl;
																	
																	if(strlen($ci)==0)									  
																		tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
																	else
																		tep_redirect(tep_href_link('ppeb.php', 'geppec=1&cusid='.$ci.'&payid='.$payerID.'&tok='.$token.'&cur='.$currencyCodeType.'&payt='.$paymentType.'&shec='.$shppec, 'SSL'));
																}
															} 
														else  // errore nella risposta PP
															{
																echo "ERROR:"."<br>";
																foreach ($arrtag as $key => $value)
																{
																	if (in_array($key, $error_show_array) || $ppec_debug) 
																	{
																		echo $key.": ".$value."<br>";   
																	} 
																}
															}
													}// fine richiesta Details                      
												}
		}
	}
}

function sax_start($parser, $name, $attributes) { 
	global $currtag;		
	$currtag = strtoupper($name);	
} 
 
function sax_end($parser, $name) { 
} 

function sax_cdata($parser, $cdata) {
	global $arrtag, $currtag;   
	$arrtag[$currtag] .= $cdata;
	/* print($currtag);
	print '-';
	print($cdata);
	print "<br>";
*/
} 

function databasemodif($resArray, $token, $paymentAmount, $currencyCodeType, $paymentType, $shppec){
/*
 	print "array di ritorno da paypal, verificare la partita iva o codice fiscale";
	print_r ($resArray);
exit;

 array di ritorno da paypal, verificare la partita iva o codice fiscaleArray
(
    [SIGNATURE] => AVZMFDFEWL1LDRKH-NRBFKVEUSUIAPBGLHD5LTXSER10HKFOQCQMNHSN
    [TIMESTAMP] => 2009-12-17T09:02:51Z
    [ACK] => SUCCESS
    [CORRELATIONID] => 61D7C09CB4D72
    [VERSION] => 3.0
    [BUILD] => 1105502
    [TOKEN] => EC-4W2836098M929681D
    [PAYER] => INFO_1259441171_PER@PROMOWEBSTUDIO.NET
    [PAYERID] => UNNFXQR52QEC6
    [PAYERSTATUS] => VERIFIED
    [FIRSTNAME] => TEST
    [LASTNAME] => USER
    [PAYERCOUNTRY] => IT
    [NAME] => TEST TEST
    [STREET1] => VIA DEI TEST E DELLE PROVE
    [CITYNAME] => MASSA
    [STATEORPROVINCE] => MASSA-CARRARA
    [COUNTRY] => IT
    [COUNTRYNAME] => ITALY
    [POSTALCODE] => 54100
    [ADDRESSOWNER] => PAYPAL
    [ADDRESSSTATUS] => UNCONFIRMED
    [CUSTOM] => A6
)

 */	
	$payerid = $resArray['PAYERID'];
	$_SESSION['payerID'] = $resArray['PAYERID'];
	
	// verificare se funziona con gli account business
	if ($resArray['PayerBusiness'] <> '')
	{
		$company = $resArray['PayerBusiness'];
		$entry_type = 'company';  // ma lo aggiorniamo solo al momento dell'inserimento della partita iva nel checkout payment
	}
	$firstname = $resArray['FIRSTNAME'];
	$lastname = $resArray['LASTNAME'];
	$email_address = strtolower($resArray['PAYER']); // la mail viene sempre convertita in lower case
	$fax = $newsletter =  $dob = $company = $suburb="";
	$password = tep_create_random_value(8); // crea una password fittizia di 8 caratteri
	$telephone = $resArray['CONTACTPHONE'];
	$gender = "";
	
	$street_address = $resArray['STREET1']." ".$resArray['STREET2'];
	$postcode = $resArray['POSTALCODE'];
	$city = $resArray['CITYNAME'];
	
	$codec = $resArray['COUNTRY'];
	
	
	$ord_id = tep_db_query("select countries_id as co from " .TABLE_COUNTRIES." where countries_iso_code_2 = '".$codec."'");
	$ordid = tep_db_fetch_array($ord_id);
	$countryid = $ordid['co'];
	
	
	$state = htmlspecialchars($resArray['STATEORPROVINCE']);
	// Uncomment line below to revert to v2.21
	// $state = htmlspecialchars($resArray['SHIPTOSTATE']); 
    $zone_query = tep_db_query("select * from " . TABLE_ZONES . " where zone_name = '" . $state . "' ");
    if (tep_db_num_rows($zone_query)) {
      $zone = tep_db_fetch_array($zone_query);
      $zone_id =  $zone['zone_id'];
    } else {
     $zone_id = "0";
    }

 // controlla se esiste già il cliente con lo stesso customers_id  ma in realtà dovrebbe controllare se esiste la mail nel database visto che è campo chiave, perchè può darsi che l'utente sia già iscritto ma scelgo ugualmente paypal EC
// $cust_query = tep_db_query("select ".TABLE_CUSTOMERS.".customers_id as customers_id, ".TABLE_CUSTOMERS.".customers_default_address_id as customers_default_address_id from ".TABLE_CUSTOMERS.", ppec_payer where ".TABLE_CUSTOMERS.".customers_id = ppec_payer.customers_id and ppec_payer.payerid = '".$payerid."'");
 $cust_query = tep_db_query("select ".TABLE_CUSTOMERS.".customers_id as customers_id, ".TABLE_CUSTOMERS.".customers_default_address_id as customers_default_address_id from ".TABLE_CUSTOMERS." where ".TABLE_CUSTOMERS.".customers_email_address = '".$email_address."'");
 
if (tep_db_num_rows($cust_query) >= 1) // c'è già un cliente con la stessa email, ricavo quindi id e default address_id
    {
       $row = mysql_fetch_array($cust_query);
       
       $customer_id = $row['customers_id'];
       // prepara i dati per l'address book, in questo caso si deve controllare se i dati sono giè stati inseriti per quel cliente, altrimenti
       // proliferano gli indirizzi in rubrica di osc perchè si inserisce un indirizzo ad ogni pagamento express
       // 1. select su tutti gli indirizzi della rubrica per vedere se ce n'è uno che è uguale a quello di paypal 
       // se non c'è 
       // 2. insert dei dati nell'address_book oppure valorizzazione dell'address_id con quello trovato
       
       // punto 1 come campi prendiamo nome, cognome, via e cap: se coincidono preleviamo l'address_book_id, altrimenti ne inseriamo uno nuovo
	   $firstname = addslashes($firstname);
	   $lastname = addslashes($lastname);
	   $street_address = addslashes($street_address);
	  
       
       $customer_query = "select * from ".TABLE_ADDRESS_BOOK." where customers_id = '".$customer_id."' AND entry_firstname = '". $firstname . "' AND entry_lastname = '" . $lastname  ."' AND entry_street_address = '" . $street_address ."' AND entry_postcode = '" . $postcode . "'";
       $check_address_book_query = tep_db_query($customer_query);
/*		print mysql_num_rows($check_address_book_query);
		print "questo è il numero di record trovati nell'address book";
		exit;
*/
		if(mysql_num_rows($check_address_book_query)>= 1)
       {
       	$address_book_array = mysql_fetch_array($check_address_book_query);
       
       	$address_id = $address_book_array['address_book_id'];
       /*	print "ok ci siamo ha trovato l'address book e lo associa all'ordine";
       	print $address_id;
       	exit;
		*/
       }
    
 else // indirizzo non trovato lo inserisco
       {
 
       		// prepara l'insert per l'address_book    
		      $sql_data_array = array('customers_id' => $customer_id,
		      						  'entry_firstname' => $firstname,
		                              'entry_lastname' => $lastname,
		                              'entry_street_address' => $street_address,
		                              'entry_postcode' => $postcode,
		                              'entry_city' => $city,
		                              'entry_country_id' => $countryid);
		
		      if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
		      if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
		      if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;
		      if (ACCOUNT_STATE == 'true') {
		        if ($zone_id > 0) { // dovrebbe già essere valorizzata da sopra
		          $sql_data_array['entry_zone_id'] = $zone_id;
		          $sql_data_array['entry_state'] = $state;
		        } else {
		          $sql_data_array['entry_zone_id'] = '0';
		          $sql_data_array['entry_state'] = $state;
		        }
     		}
             	// se è una società
     		  if ($resArray['PayerBusiness'] <> '')	$sql_data_array['entry_company'] = $company;
													
     
      tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

      $address_id = tep_db_insert_id();
 
       }
    
       // $address_id = $row['customers_default_address_id'];      
    } 
   else // il cliente non c'è nel db (mail non trovata)
   	{
     $sql_data_array = array('customers_firstname' => $firstname,
                              'customers_lastname' => $lastname,
                              'customers_email_address' => $email_address,
                              'customers_telephone' => $telephone,
                              'customers_fax' => $fax,
                              'customers_newsletter' => $newsletter,
                              'customers_password' => tep_encrypt_password($password));

      if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
      if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($dob);
      

      tep_db_perform(TABLE_CUSTOMERS, $sql_data_array);



      $customer_id = tep_db_insert_id();

 
 		// prepara l'insert per l'address_book    
      $sql_data_array = array('customers_id' => $customer_id,
                              'entry_firstname' => $firstname,
                              'entry_lastname' => $lastname,
                              'entry_street_address' => $street_address,
                              'entry_postcode' => $postcode,
                              'entry_city' => $city,
                              'entry_country_id' => $countryid);

      if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
      if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
      if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;
      if (ACCOUNT_STATE == 'true') {
        if ($zone_id > 0) {
          $sql_data_array['entry_zone_id'] = $zone_id;
          $sql_data_array['entry_state'] = '';
        } else {
          $sql_data_array['entry_zone_id'] = '0';
          $sql_data_array['entry_state'] = $state;
        }
      }
	  
     
      tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

      $address_id = tep_db_insert_id();

      tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . (int)$address_id . "' where customers_id = '" . (int)$customer_id . "'");

      tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . (int)$customer_id . "', '0', now())");

      if (SESSION_RECREATE == 'True') {
        tep_session_recreate();
      }      
      
      tep_db_query("INSERT ppec_payer values ('".$customer_id."', '".$payerid."')");
     
    }
      // mando l'email di nuova registrazione
      // per il momento disabilitata perchè qualcuno potrebbe innervosirsi
      // todo: mail di conferma personalizzata per comunicare la password e la possibilità di avere lo storico degli ordini
     /*
      $name = $firstname . ' ' . $lastname; 
      $email_text .= EMAIL_WELCOME . EMAIL_TEXT . EMAIL_CONTACT . EMAIL_WARNING;
      tep_mail($name, $email_address, EMAIL_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
      tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, "Registrazione Nuovo cliente su OSCommerce", "Nuova registrazione da ". $firstname . " " . $lastname .". Alert by http://www.oscommerce.it/" , $name, $email_address); 	
      */
 	// geppec=1&cusid=6&addid=20&fn=TEST&cou=105&zonid=0&payid=UNNFXQR52QEC6&tok=EC-8KS078037R9210329&Amt=&cur=&payt=&shec=A
    tep_redirect(tep_href_link('ppeb.php', 'geppec=1&cusid='.$customer_id.'&addid='.$address_id.'&fn='.$firstname.'&cou='.$countryid.'&zonid='.$zone_id.'&payid='.$payerid.'&tok='.$token.'&Amt='.$paymentAmount.'&cur='.$currencyCodeType.'&payt='.$paymentType.'&shec='.$shppec, 'SSL'));
	
   //  tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
   
}



function PPECprocess($TRANSACTIONID, $PAYMENTSTATUS)
{
	global $HTTP_POST_VARS, $customer_id, $sendto, $billto, $cart, $languages_id, $language, $classot, $order, $currency, $currencies, $shipping, $payment, $comments;
	global $ctotal, $cshipping, $order_totals, $ALERTMAIL;
	global $ccurl;
	
	
	tep_db_connect();
 
// 	$ord_id = tep_db_query("SELECT status_id as stid FROM ppec_transaction_status Where transaction_status = '".$PAYMENTSTATUS."' AND language_id = '" . $languages_id . "'");
    $ord_id = tep_db_query("SELECT orders_status_id as stid FROM orders_status Where orders_status_name like '%".$PAYMENTSTATUS."%' AND language_id = '" . $languages_id . "'");
	$ordid = tep_db_fetch_array($ord_id);
	$o_id = $ordid['stid'];
 
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
                          'delivery_name' => $order->delivery['firstname'] . ' ' . $order->delivery['lastname'], 
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
                          'payment_method' => $order->info['payment_method'], 
                          'cc_type' => $order->info['cc_type'], 
                          'cc_owner' => $order->info['cc_owner'], 
                          'cc_number' => $order->info['cc_number'], 
                          'cc_expires' => $order->info['cc_expires'], 
                          'date_purchased' => 'now()', 
                          'orders_status' => $o_id, 
                          'currency' => $order->info['currency'], 
                          'currency_value' => $order->info['currency_value']);

				   //PIVACF start
					if ($order->billing['piva'] || $order->billing['cf'])
							{
						  		 $sql_data_array['billing_cf'] = $order->billing['cf'];
						  		 $sql_data_array['billing_piva'] = $order->billing['piva'];
						  		 $sql_data_array['billing_type'] = $order->billing['entry_type'];
						  		 $sql_data_array['billing_company_cf'] = $order->billing['company_cf'];
							}
	
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
			//------insert customer choosen option eof ----			
			 // start -- tax & products ordered
			    $total_weight += ($order->products[$i]['qty'] * $order->products[$i]['weight']);
			    $total_tax += tep_calculate_tax($total_products_price, $products_tax) * $order->products[$i]['qty'];
			    $total_cost += $total_products_price;
			
			    $products_ordered .= $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ' (' . $order->products[$i]['model'] . ') = ' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . $products_ordered_attributes . "\n";
			
			// end -- tax & products ordered 
          
	      tep_db_query("insert into ppec_transaction (transactionid, paymentstatus, orders_id) values ('" .$TRANSACTIONID. "', '".$PAYMENTSTATUS."', '".$insert_id."')"); 
	 	
// -----------------------------------------		
// initialized for the email confirmation
  $products_ordered = '';
  $subtotal = 0;
  $total_tax = 0;		
// ---end -----------------------------------				

 for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {  // Stock Update - Joao Correia
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
//            if ($attribute['track_stock'] == 1) 
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
							'products_price' => $GLOBALS['pws_prices']->getFirstPrice($order->products[$i]['id'])/*$order->products[$i]['price']*/, 
                            'final_price' => $GLOBALS['pws_prices']->getLastPrice($order->products[$i]['id'],$order->products[$i]['qty'],$attributes)/*$order->products[$i]['final_price']*/, 
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
    $details=unserialize($GLOBALS['pws_prices']->getPriceResume($order->products[$i]['id'],$order->products[$i]['qty'],$attributes/*,false*/));
//    print_r($details);
//    $products_ordered .= $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ' (' . $order->products[$i]['model'] . ') = ' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . $products_ordered_attributes . "\n";
//    $products_ordered .= $order->products[$i]['name'] . ' (' . $order->products[$i]['model'] . ')'. $products_ordered_attributes . "\n";
// 	  $products_ordered .= $pws_prices->formatTextPriceResume($details)."\n";

if(PRODUCT_INFO_MODEL == 'true')			
     $products_ordered .= $order->products[$i]['model'] . ' | ' . $order->products[$i]['name'] . ' | ' . $order->products[$i]['qty'] . ' | ' . $GLOBALS['pws_prices']->formatTextPriceResume($details)."\n";
else 
     $products_ordered .= $order->products[$i]['name'] . ' | ' . $order->products[$i]['qty'] . ' | ' . $GLOBALS['pws_prices']->formatTextPriceResume($details)."\n";

  if ($products_ordered_attributes <> '')  $products_ordered .= $products_ordered_attributes . "\n";
    
//PWS eof
  }

   // dati aggiuntivi cliente
  $customers_info_query = tep_db_query("select customers_code, customers_group_id from customers where customers_id = '" .$customer_id . "'");
  $customers_info = tep_db_fetch_array($customers_info_query);
  
  
  if(defined(TABLE_CUSTOMERS_GROUPS))
  {
  
  $group_info_query = tep_db_query("select customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " where customers_group_id = '" . $customers_info['customers_group_id'] . "'");
  $group_info = tep_db_fetch_array($group_info_query);
  }
  
          
           $sql_data_array = array('orders_id' => $insert_id,
                                    'orders_status_id' => $o_id,
                                    'date_added' => 'now()',
						            'customer_notified' => '0',
                                    'comments' => $order->info['comments']);
          tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);


/// --- start ---- checkout process email function		  
  include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PROCESS);

  $customer_notification = (SEND_EMAILS == 'true') ? '1' : '0';		  
		  // lets start with the email confirmation
  $email_order = STORE_NAME . "\n" . 
                 EMAIL_SEPARATOR . "\n" . 
                 EMAIL_TEXT_ORDER_NUMBER . ' ' . $insert_id . "\n" .
                 EMAIL_TEXT_INVOICE_URL . ' ' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $insert_id, 'SSL', false) . "\n" .
                 EMAIL_TEXT_DATE_ORDERED . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n";
  if ($order->info['comments']) {
    $email_order .= tep_db_output($order->info['comments']) . "\n\n";
  }
  $email_order .= EMAIL_TEXT_PRODUCTS . "\n" . 
                  EMAIL_SEPARATOR . "\n" . 
                  $products_ordered . 
                  EMAIL_SEPARATOR . "\n";

  for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
    $email_order .= strip_tags($order_totals[$i]['title']) . ' ' . strip_tags($order_totals[$i]['text']) . "\n";
  }

  if ($order->content_type != 'virtual') {
    $email_order .= "\n" . EMAIL_TEXT_DELIVERY_ADDRESS . "\n" . 
                    EMAIL_SEPARATOR . "\n" .
                    tep_address_label($customer_id, $sendto, 0, '', "\n") . "\n";
  }

  $email_order .= "\n" . EMAIL_TEXT_BILLING_ADDRESS . "\n" .
                  EMAIL_SEPARATOR . "\n" .
                  tep_address_label($customer_id, $billto, 0, '', "\n") . "\n\n";
  //if (is_object($$payment)) {
    $email_order .= EMAIL_TEXT_PAYMENT_METHOD . "\n" . 
                    EMAIL_SEPARATOR . "\n";
    //$payment_class = $$payment;
    $email_order .= "PayPal Express Checkout" . "\n\n";
   // if ($payment_class->email_footer) { 
    //  $email_order .= $payment_class->email_footer . "\n\n";
 
  
  if ((CUSTOMER_MAIL) && tep_not_null($order->customer['email_address'])) {    	
  	tep_mail($order->customer['firstname'] . ' ' . $order->customer['lastname'], $order->customer['email_address'], EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
  }

/// --- end ---- checkout process email function


// send emails to other people
  if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
    tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
  }

 }


 function reviewtotal()
 {
  global $ctotal, $cshipping, $classot, $language, $order_totals;                       

  if (is_array($classot)) {
      for ($j=0, $k=sizeof($classot); $j<$k; $j++) {
               $class = $classot[$j];
               if ($GLOBALS[$class]->enabled) {                 
                  $GLOBALS[$class]->process();
                  include(DIR_WS_LANGUAGES.$language.'/modules/order_total/'.$class.'.php');

                  for ($i=0, $n=sizeof($GLOBALS[$class]->output); $i<$n; $i++) {

                             $order_totals[] = array('code' => $GLOBALS[$class]->code,
                                                     'title' => $GLOBALS[$class]->output[$i]['title'],
                                                     'text' => $GLOBALS[$class]->output[$i]['text'],
                                                     'value' => $GLOBALS[$class]->output[$i]['value'],
                                                     'sort_order' => $GLOBALS[$class]->sort_order);
 
                             if($GLOBALS[$class]->code == 'ot_total')
                             {
                                     $ctotal = $GLOBALS[$class]->output[$i]['value'];
                             }
                             else if($GLOBALS[$class]->code == 'ot_shipping')
                                  {
                                     $cshipping = $GLOBALS[$class]->output[$i]['value'];
                                  }


                 } 
              }
        }
   }

 }


 function reviewbasket()
 {
    global $classot, $order, $shipping, $cart, $order_total_modules;                  

    require(DIR_WS_CLASSES . 'order.php');
    $order = new order;
     
    require(DIR_WS_CLASSES . 'order_total.php');
    $order_total_modules = new order_total;   
                                   
    $classot = array();
    if (is_array($order_total_modules->modules)) {
           reset($order_total_modules->modules);
           while (list(, $value) = each($order_total_modules->modules)) {
                 $classot[] = substr($value, 0, strrpos($value, '.'));
           }
    }

 }

function ppebin()
 {
    global $language, $lg;                  

	if($language == "french")
	{
	   $lg = "FR";
	}elseif($language == "german")
	{
	   $lg = "DE";
	}elseif($language == "italian")
	{
	   $lg = "IT";
	}elseif($language == "spanish")
	{
	   $lg = "ES";
	}elseif($language == "netherlands")
	{
	   $lg = "NL";
	}elseif($language == "polish")
	{
	   $lg = "PL";
	}elseif($language == "japanese")
	{
	   $lg = "JP";
	}else
	{
	   $lg = "US";
	}
	
   
 }
 
 
 // funzione per la connessione a paypal e invio delle richieste
 // variabili global carrello e linguaggio
 function ppecon()
 {
 global $cart, $lg;
 $serverName = $_SERVER['SERVER_NAME'];
 $serverPort = $_SERVER['SERVER_PORT'];
  // set the type of request (secure or not)
  $request_type = ($_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';	  
  $url = dirname($request_type.$serverName.':'.$serverPort.$_SERVER['REQUEST_URI']);
	


$request_SSL = ($_SERVER['HTTPS'] == 'on') ? 'SSL' : 'NONSSL';
				
 if($cart->show_total()>0)
     //$returnURL = urlencode($url.'/'.FILENAME_CHECKOUT_SHIPPING);	
	 $returnURL =  urlencode(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', $request_SSL)); 	         
 else 	
	 //$returnURL = urlencode($url.'/'.FILENAME_ACCOUNT);
	 $returnURL =  urlencode(tep_href_link(FILENAME_ACCOUNT, '', $request_SSL)); 	         
	
	$cancelURL =  urlencode(tep_href_link(FILENAME_SHOPPING_CART, '', $request_SSL));
 $logoutURL =  urlencode(tep_href_link(FILENAME_LOGOFF, '', $request_SSL)); 
// $cancelURL = urlencode($url.'/'.FILENAME_SHOPPING_CART);
 //$logoutURL = urlencode($url.'/'.FILENAME_LOGOFF);	
  
 ppebin(); // setta il linguaggio come codice per paypal
 
 $nvpstr="&ReturnUrl=".$returnURL."&CANCELURL=".$cancelURL ."&LOGOUTURL=".$logoutURL."&LOCALECODE=".$lg;		   
  
      	 
		   $resArray=hash_call("SetAuthFlowParam",$nvpstr);
		   $_SESSION['reshash']=$resArray;
		   $ack = strtoupper($resArray["ACK"]);
		   if($ack=="SUCCESS"){
			 $token = urldecode($resArray["TOKEN"]);
			 $payPalURL = PAYPAL_URL_AUT.$token;
					
					header("Location: ".$payPalURL);			
                          
				  } else  {
				             
					       $_SESSION['reshash']=$resArray;
                                     $ack = strtoupper($resArray["ACK"]);
                                     $cor = strtoupper($resArray['CORRELATIONID']);
                                     $ver = strtoupper($resArray['VERSION']);
                                     echo('ACK: '.$ack.'<br>');
                                     echo('Correlation: '.$cor.'<br>');
                                     echo('version: '.$ver.'<br>');
                                     
                                     $count=0;

                                     while (isset($resArray["L_SHORTMESSAGE".$count])) {		
	 	                               $errorCode    = $resArray["L_ERRORCODE".$count];
		                               $shortMessage = $resArray["L_SHORTMESSAGE".$count];
		                               $longMessage  = $resArray["L_LONGMESSAGE".$count]; 
                                           echo('ACK: '.$errorCode.'<br>');
                                           echo('Correlation: '.$shortMessage.'<br>');
                                           echo('version: '.$longMessage.'<br>');

		                                   $count=$count+1; 
                                     }
						   }
	

 $payerid  = "";	
 $cust_query = tep_db_query("select ".TABLE_CUSTOMERS.".customers_id, ".TABLE_CUSTOMERS.".customers_default_address_id from ".TABLE_CUSTOMERS.", ppec_payer where ".TABLE_CUSTOMERS.".customers_id = ppec_payer.customers_id and ppec_payer.payerid = '".$payerid."'");
    if (tep_db_num_rows($cust_query)) 
    {
       $row = mysql_fetch_row($cust_query);
      // print_r($row);
       
       $customer_id = $row[0];
       $address_id = $row[1];      
    } 
   else{
   }
   
  }
?>
