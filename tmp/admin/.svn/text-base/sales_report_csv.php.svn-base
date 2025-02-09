<?php
  require('includes/application_top.php');
$file = 'sales_report.txt'; // file di appoggio per effettuare il dl diretto del csv
$filedir = DIR_FS_BACKUP ; //dir di appoggio deve avere i permessi 777
$file_ws_dir = DIR_WS_ADMIN . 'backups/';
// print_r($_REQUEST);
// exit;

/*
 campi da esportare nel file
modello     descrizione prodotto  quantità visite   numero pz venduti dal  al   fatturato totale  dal  al  per singolo modello  incidenza in percentuale visite/vendite  data inizio/fine fatturazione      data inizio/ultima modifica prezzo

- partire dalla tabella orders e caricare tutti gli orders_id 
- dall'array degli orders_id fare loop che trova gli articoli venduti nella tabella orders_products
che contiene nome, modello, prezzo di vendita, prezzo in offerta, quantità
- ricavare le visite totali (non del periodo) relative al prodotto dalla tabella products, data inserimento, data ultima modifica


 */

// carico i dati dal database
/*
[orders_id] => 1
    [customers_id] => 4
    [customers_name] => Test Test
    [customers_company] => PWS
    [customers_street_address] => Via dei test e delle prove
    [customers_suburb] => 
    [customers_city] => Massa
    [customers_postcode] => 54100
    [customers_state] => Massa-Carrara
    [customers_country] => Italy
    [customers_telephone] => 3293965918
    [customers_email_address] => info@oscommerce.com
    [customers_address_format_id] => 1
    [delivery_name] => Test Test
    [delivery_company] => PWS
    [delivery_street_address] => Via dei test e delle prove
    [delivery_suburb] => 
    [delivery_city] => Massa
    [delivery_postcode] => 54100
    [delivery_state] => Massa-Carrara
    [delivery_country] => Italy
    [delivery_address_format_id] => 1
    [billing_name] => Test Test
    [billing_company] => PWS
    [billing_type] => 
    [billing_cf] => 
    [billing_company_cf] => RSCRCR69P17H501F
    [billing_piva] => 
    [billing_street_address] => Via dei test e delle prove
    [billing_suburb] => 
    [billing_city] => Massa
    [billing_postcode] => 54100
    [billing_state] => Massa-Carrara
    [billing_country] => Italy
    [billing_address_format_id] => 1
    [payment_method] => Pagamento in Contrassegno
    [cc_type] => 
    [cc_owner] => 
    [cc_number] => 
    [cc_expires] => 
    [last_modified] => 2009-06-15 17:19:59
    [date_purchased] => 2009-06-15 17:17:33
    [orders_status] => 2
    [orders_date_finished] => 
    [currency] => EUR
    [currency_value] => 1.000000
    [Ordine_effetuato_da] => 
    [Vs_rif_ordine] => 
    [customers_group_id] => 0
    [customers_code] => 


*/
$fp = fopen($filedir . $file , 'w') or die ('Impossibile aprire il file csv in scrittura');

// intestazione
// modello     descrizione prodotto  quantità visite   numero pz venduti dal  al   fatturato totale  dal  al  per singolo modello  
// incidenza in percentuale visite/vendite  data inserimento  ultima modifica prezzo

$string .= '"Modello";' . '"Descrizione prodotto";'. 
			   '"Quantita";'.'"Visite";'. 
			   '"Numero pz venduti Dal ' . $_REQUEST['data_inizio'] . ' Al ' . $_REQUEST['data_fine'] . '";"'. 
			   '"Fatturato Totale Dal ' . $_REQUEST['data_inizio'] . ' Al ' . $_REQUEST['data_fine'] . '";"'.  		 
			   '"Incidenza in percentuale visite/vendite";'.
			   '"Data inserimento";'.
			   '"Ultima modifica prezzo"';
	$string .= "\r\n";
fwrite($fp,$string);
/*
$sales = tep_db_query("select orders_id from orders where date_purchased >= '" . $_REQUEST['data_inizio'] . "' AND date_purchased <= '" . $_REQUEST['data_fine'] . "'");
print mysql_num_rows($sales);

while ($order_raw = tep_db_fetch_array($sales)) // loop sul vettore degli orders_id
{
	$string = ''; //resetto la stringa
	// trova gli articoli venduti per ogni singolo ordine
	$order_products_query = tep_db_query("select products_id, products_model, products_name, final_price, products_quantity from " . TABLE_ORDERS_PRODUCTS .  " where orders_id = '" . $order_raw['orders_id'] . "' ORDER BY products_id");
// 	print_r($products=mysql_fetch_array($order_products_query));
//	exit;
}
	
*/
$first_order_id_query = tep_db_query("select min(orders_id) as first_id from orders where date_purchased >= '" . $_REQUEST['data_inizio'] . "' AND date_purchased <= '" . $_REQUEST['data_fine'] . "'");
$first_order_id_ary = tep_db_fetch_array($first_order_id_query);

$last_order_id_query = tep_db_query("select max(orders_id) as last_id from orders where date_purchased >= '" . $_REQUEST['data_inizio'] . "' AND date_purchased <= '" . $_REQUEST['data_fine'] . "'");
$last_order_id_ary = tep_db_fetch_array($last_order_id_query);

$first_order_id = $first_order_id_ary['first_id'];
$last_order_id = $last_order_id_ary['last_id'];
// print $first_order_id;
// lista tutti i products_id presenti negli ordini selezionati, ordinandoli per products_id
$orders_products_id_query = tep_db_query("select DISTINCT(products_id) from " . TABLE_ORDERS_PRODUCTS .  " where orders_id <=  '" . $last_order_id . "' AND orders_id >=  '" . $first_order_id . "' ORDER BY products_id");

// print(mysql_num_rows($orders_products_id_query));
// exit;

while($orders_product_id = tep_db_fetch_array($orders_products_id_query)) // loop su tutti i products_id trovati
{
	
		$product_data_query = tep_db_query("select products_model, products_quantity, products_date_added,	products_last_modified, products_price from ". TABLE_PRODUCTS . " WHERE products_id = '" . $orders_product_id['products_id'] . "'" );
		$product_data = tep_db_fetch_array($product_data_query);
		$product_desc_query = tep_db_query("select products_name, products_viewed from " . TABLE_PRODUCTS_DESCRIPTION ." where products_id = '" . $orders_product_id['products_id'] . "' AND language_id = '4' ");
		$product_desc = tep_db_fetch_array($product_desc_query);
		$special_price_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = '" . $orders_product_id['products_id'] . "' and Status = '1'");
		$special_price = tep_db_fetch_array($special_price_query);
		$total_sales_query = tep_db_query("select SUM(products_quantity) as pz_venduti from " . TABLE_ORDERS_PRODUCTS . "  where products_id = '" . $orders_product_id['products_id'] ."' AND orders_id <=  '" . $last_order_id . "' AND orders_id >=  '" . $first_order_id . "'");
		$total_sales = tep_db_fetch_array($total_sales_query);
		$total_revenue_query =  tep_db_query("select SUM(final_price) as fatturato from " . TABLE_ORDERS_PRODUCTS . "  where products_id = '" . $orders_product_id['products_id'] ."' AND orders_id <=  '" . $last_order_id . "' AND orders_id >=  '" . $first_order_id . "'");
		$total_revenue = tep_db_fetch_array($total_revenue_query);
		
// modello     descrizione prodotto  quantità visite   numero pz venduti dal  al   fatturato totale  dal  al  per singolo modello  
// incidenza in percentuale visite/vendite  data inserimento  ultima modifica prezzo
		$modello = $product_data['products_model'];
		$nome = str_replace("\"","'",$product_desc['products_name']);
		$quantita = $product_data['products_quantity'];
		$visite = $product_desc['products_viewed'];
		$pz_venduti = $total_sales['pz_venduti'];
		$fatturato_totale = str_replace(".",",",$total_revenue['fatturato']);
		$percentuale_visite_vendite = str_replace(".",",",$pz_venduti / $visite * 100) . '%';
		$data_inserimento = $product_data['products_date_added'];
		$data_ultima_modifica = $product_data['products_last_modified'];
		
		$string = ''; //resetta la riga
			$string .= '"' . $modello . '";' . '"' . $nome . '";'.  '"' . $quantita . '";'. 
					   '"' . $visite . '";'.'"' . $pz_venduti . '";'. 
					   '"' . $fatturato_totale . '";'. '"' . $percentuale_visite_vendite . '";'. 
					   '"' . $data_inserimento . '";'. '"' . $data_ultima_modifica . '"';
			$string .= "\r\n";
			
			fwrite($fp,$string);
			// print $string;
//	exit;
	
}

//	$string .= $codice . $anno_riferimento . $numero_bolla . $data_bolla . $vostro_riferimento . $totale_peso . $totale_volume . $totale_colli . $totale_pedane . $importo_contrassegno . $destinatario . $indirizzo . $cap . $localita . $provincia . $annotazioni . "\n";

fclose($fp);
tep_redirect('sales_report_dl.php');
//print "<a href=\"sales_report_dl.php\" taget=_new >clicca per scaricare il Report sulle vendite</a>";
   
?>
