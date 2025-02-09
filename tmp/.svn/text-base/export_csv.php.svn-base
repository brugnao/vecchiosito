<?php
/*
 * @filename:	export_csv.php
 * @version:	1.00
 * @project:	Export CSV catalogue
 *
 * @author:		Riccardo Roscilli <info@oscommerce.it>
 * @created:	10/apr/07 16:52:41
 * @modified:	10/apr/07 16:52:41
 *
 * @copyright:	2007	Riccardo Roscilli	@ OsCommerceIT
 *
 * @desc:	
 *
 * @TODO:		
 */

  require_once('includes/application_top.php');
// if the customer is not logged on, redirect them to the login page
  if ( !tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
 
// Definizione della funzione fputcsv se non definita (phpver <5.0.0)
//fputcsv --  Format line as CSV and write to file pointer 
//Description
//int fputcsv ( resource handle [, array fields [, string delimiter [, string enclosure]]] )
//
//
//fputcsv() formats a line (passed as a fields array) as CSV and write it to the specified file handle. Returns the length of the written string, or FALSE on failure. 
//
//The optional delimiter parameter sets the field delimiter (one character only). Defaults as a comma: ,. 
if (!function_exists('fputcsv'))	{
	require_once 'fputcsv_func.php';
}

//class	export_csv	{
//	var $column_list=array(
//		TEXT_PRODUCTS_NAME=>'products_name'
//		,TEXT_PRODUCTS_MANUFACTURER=>'manufacturers_id'
//		,TEXT_PRODUCTS_PRICE=>'products_price'
//		);
//	var $products;
//	var	$tempfname;
//	function init()	{
//		$this->tempfname=DIR_FS_CATALOG.'temp/'.gmdate("dMYHis").'csv';
//	}
//}

// create column list
    $column_list = array(
		'PRODUCT_LIST_MODEL'
		,'PRODUCT_LIST_NAME'
		,'PRODUCT_LIST_MANUFACTURER'
		,'PRODUCT_LIST_PRICE'
		,'PRODUCT_LIST_QUANTITY'
//		,'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT
//		,'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE
//		,'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW
//		'PRODUCT_LIST_AVAILABILITY'=>PRODUCT_LIST_AVAILABILITY
		);
// BOF Separate Pricing Per Customer
   if(!tep_session_is_registered('sppc_customer_group_id')) {
     $customer_group_id = '0';
     } else {
      $customer_group_id = $sppc_customer_group_id;
   }
   // this will build the table with specials prices for the retail group or update it if needed
   // this function should have been added to includes/functions/database.php
   if ($customer_group_id == '0') {
   tep_db_check_age_specials_retail_table();
   }
   $status_product_prices_table = false;
   $status_need_to_get_prices = false;

   // find out if sorting by price has been requested
//   if ( (isset($HTTP_GET_VARS['sort'])) && (ereg('[1-8][ad]', $HTTP_GET_VARS['sort'])) && (substr($HTTP_GET_VARS['sort'], 0, 1) <= sizeof($column_list)) && $customer_group_id != '0' ){
//    $_sort_col = substr($HTTP_GET_VARS['sort'], 0 , 1);
//    if ($column_list[$_sort_col-1] == 'PRODUCT_LIST_PRICE') {
//      $status_need_to_get_prices = true;
//      }
//   }

   if ($status_need_to_get_prices == true && $customer_group_id != '0') {
   $product_prices_table = TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id;
   // the table with product prices for a particular customer group is re-built only a number of times per hour
   // (setting in /includes/database_tables.php called MAXIMUM_DELAY_UPDATE_PG_PRICES_TABLE, in minutes)
   // to trigger the update the next function is called (new function that should have been
   // added to includes/functions/database.php)
   tep_db_check_age_products_group_prices_cg_table($customer_group_id);
   $status_product_prices_table = true;

   } // end if ($status_need_to_get_prices == true && $customer_group_id != '0')
// EOF Separate Pricing Per Customer


    $select_column_list = '';

    for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {
      switch ($column_list[$i]) {
        case 'PRODUCT_LIST_MODEL':
          $select_column_list .= 'p.products_model, ';
          break;
        case 'PRODUCT_LIST_NAME':
          $select_column_list .= 'pd.products_name, ';
          break;
        case 'PRODUCT_LIST_MANUFACTURER':
          $select_column_list .= 'm.manufacturers_name, ';
          break;
        case 'PRODUCT_LIST_QUANTITY':
          $select_column_list .= 'p.products_quantity, ';
          break;
        case 'PRODUCT_LIST_IMAGE':
          $select_column_list .= 'p.products_image, ';
          break;
        case 'PRODUCT_LIST_WEIGHT':
          $select_column_list .= 'p.products_weight, ';
          break;
        case 'PRODUCT_LIST_AVAILABILITY':
          $select_column_list .= 'p.products_quantity, p.products_date_available, ';
          break;
      }
    }


// BOF Separate Pricing Per Customer
      if ($status_product_prices_table == true) {
  //     $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, m.manufacturers_name, tmp_pp.products_price, p.products_tax_class_id, IF(tmp_pp.status, tmp_pp.specials_new_products_price, NULL) as specials_new_products_price, IF(tmp_pp.status, tmp_pp.specials_new_products_price, tmp_pp.products_price) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . $product_prices_table . " as tmp_pp using(products_id), " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_status = '1' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "'";// and p2c.categories_id = '" . (int)$current_category_id . "'";
    $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id ,p.products_model, pd.products_name, pd.language_id from  " . TABLE_PRODUCTS . " p ,  " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1'  pd.language_id = '" . (int)$languages_id . "'";// and p2c.categories_id = '" . (int)$current_category_id . "'";
      	
      } else { // either retail or no need to get correct special prices
        $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, m.manufacturers_name, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c left join " . TABLE_SPECIALS_RETAIL_PRICES . " s on p2c.products_id = s.products_id where p.products_status = '1' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "'";// and p2c.categories_id = '" . (int)$current_category_id . "'";
      } // end else { // either retail...
// EOF Separate Pricing per Customer
   $listing_sql = "select p.products_id, p.manufacturers_id ,p.products_model, p.products_quantity, pd.products_name, pd.language_id from  " . TABLE_PRODUCTS . " p ,  " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and p.products_status = '1' and  pd.language_id = '" . (int)$languages_id . "'";// and p2c.categories_id = '" . (int)$current_category_id . "'";
 
// Creazione del file
	$tempfname=DIR_FS_CATALOG.'tmp/'.gmdate("dMYHis").'.csv';
	if (!($fp=@fopen($tempfname,'w')))	{
		exit("<h1>Server temporarily busy... please try again later...");
	}
	$sql=tep_db_query($listing_sql);
	while ($product=tep_db_fetch_array($sql))	{
		$fields=array();
		for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {
			switch ($column_list[$i]) {
//			case 'PRODUCT_LIST_MODEL':
//				$fields[]=$product['products_model'];
//				break;
			case 'PRODUCT_LIST_NAME':
				$fields[]=$product['products_name'];
				break;
			case 'PRODUCT_LIST_MANUFACTURER':
				$manuf_query = tep_db_query("select manufacturers_name from manufacturers where manufacturers_id = '" . $product['manufacturers_id'] . "'");
				$manuf_ary = tep_db_fetch_array($manuf_query);
				$fields[]=$manuf_ary['manufacturers_name'];
				break;
			case 'PRODUCT_LIST_PRICE':
			/*	$price_array = tep_get_product_price($product['products_id']);
				if($price_array[1] == true)
				$price = $price_array[1];
				else $price = $price_array[0];
				//print_r($price_array);		
				 $fields[]=$price;
				 */						
				$fields[]= number_format($pws_prices->getBestPrice($product['products_id']),2);
				break;
			case 'PRODUCT_LIST_QUANTITY':
				$fields[]=$product['products_quantity'];
				break;
				
			}
		}
		fputcsv($fp,$fields,';','"');
	}
	fclose($fp);
	chmod($tempfname,0777);
	$fp=fopen($tempfname,'r');
	$content=fread($fp,filesize($tempfname));
	fclose($tempfname);
	unlink($tempfname);
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");								// Date in the past
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");	// Always modified
	header("Cache-Control: no-store, no-cache, must-revalidate");		// HTTP/1.1
	header("Cache-Control: post-check=0, pre-check=0", false);			// HTTP/1.1
	header("Pragma: no-cache");																			// HTTP/1.0
	header('Content-Type: text/csv');
	header('Content-Description: File Transfer');
	// header("Filename: $tempfname");
	header('Content-disposition: attachment; filename='. STORE_NAME .'_' .  gmdate("d-M-Y-H-i-s") . '.csv');
	echo $content;
	exit;
	
      
?>