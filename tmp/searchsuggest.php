<?php
/**
 * osCommerce: Example XML Document for OSCFieldSuggest JS class
 *
 * File: searchsuggest.php
 * Version: 1.0
 * Date: 2007-03-28 17:49
 * Author: Timo Kiefer - timo.kiefer_(at)_kmcs.de
 * Organisation: KMCS - www.kmcs.de
 * Licence: General Public Licence 2.0
 */


/*
	This is the back-end PHP file for the osCommerce AJAX Search Suggest

	You may use this code in your own projects as long as this
	copyright is left	in place.  All code is provided AS-IS.
	This code is distributed in the hope that it will be useful,
 	but WITHOUT ANY WARRANTY; without even the implied warranty of
 	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

	The complete tutorial on how this works can be found at:
	http://www.dynamicajax.com/fr/AJAX_Suggest_Tutorial-271_290_312.html

	For more AJAX code and tutorials visit http://www.DynamicAJAX.com
	For more osCommerce related tutorials and code examples visit http://www.osCommerce-SSL.com

	Copyright 2006 Ryan Smith / 345 Technical / 345 Group.
	Rivisto, modificato e debuggato da Riccardo Roscilli 2009 info@oscommerce.it
	Adesso funziona anche con & e caratteri speciali nella ricerca

*/
	require('includes/application_top.php');
	///Make sure that a value was sent.
    if (isset($_GET['keywords']) && $_GET['keywords'] != '')  {
    	// mail('info@oscommerce.it','query',utf8_decode($_GET['keywords']));
	   //Add slashes to any quotes to avoid SQL problems.
	   //  $search = rawurldecode($_GET['keywords']);
	     $search = utf8_decode($_GET['keywords']);
	     $search = addslashes($search);
	     // mail('info@oscommerce.it','query',$search);
	  // $search = rawurldecode($search);
	   //Get every page title for the site.
       // $sql = "SELECT * FROM products_description join products on products_description.products_id=products.products_id WHERE products.products_status=1 AND products.products_carrot=0 and products.products_id = products_description.products_id and products_description.language_id = '" . (int)$languages_id . "' and products_description.products_name like('%" . tep_db_input($_GET['search']) . "%') LIMIT 15";
       $sql = "select pd.products_name, p.products_id from " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_PRODUCTS . " p on pd.products_id = p.products_id WHERE p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and pd.products_name like('%" . tep_db_input($search) . "%') LIMIT 10";
	  
	
/**
 * Set XML HTTP Header for ajax response
 */
header('Content-Type: text/xml;');
header('Cache-Control: no-cache;');
echo '<?xml version="1.0" encoding="iso-8859-1" ?>' . "\n";

echo '<response>' . "\n";
echo ' <suggestlist>' . "\n";
       $product_query = tep_db_query($sql);
	   while($product_array = tep_db_fetch_array($product_query)) { 
           //$product_array['products_name'] = htmlentities($product_array['products_name']);
		 //echo '<a href="' . tep_href_link('product_info.php', 'products_id=' . $product_array['products_id']) . '">' . $product_array['products_name'] . '</a>' . "\n";
                 echo '<item>' . "\n";
                 echo ' <name><![CDATA['  . $product_array['products_name'] . ']]></name>' . "\n";
                 echo ' <url><![CDATA[' . tep_href_link('product_info.php', 'products_id=' . $product_array['products_id']) .   ']]></url>' . "\n";
 				 echo ' <image><![CDATA[' . $GLOBALS['pws_html']->getProductsImage($product_array['products_id'], 30, 30) . ']]></image>' . "\n";
                 echo '</item>' . "\n";
	   }
   }
echo ' </suggestlist>' . "\n";
echo '</response>' . "\n";

// require(DIR_WS_INCLUDES . 'application_bottom.php');
 ?>