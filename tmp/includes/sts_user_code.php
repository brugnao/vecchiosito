<?php
/*
$Id: sts_user_code.php,v 1.2 2004/02/05 05:57:21 jhtalk Exp jhtalk $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
*/

/*

  Simple Template System (STS) - Copyright (c) 2004 Brian Gallagher - brian@diamondsea.com

*/

// PUT USER MODIFIED CODE IN HERE, SUCH AS NEW BOXES, ETC.

// The following code is a sample of how to add new boxes easily.
//  Just uncomment block below and tweak for your needs! 
//  Use as many blocks as you need and just change the block names.

  // $sts_block_name = 'newthingbox';
  // require(STS_START_CAPTURE);
  // require(DIR_WS_BOXES . 'new_thing_box.php');
  // require(STS_STOP_CAPTURE);
  // $template['newthingbox'] = strip_unwanted_tags($sts_block['newthingbox'], 'newthingbox');
 
   if (file_exists(DIR_FS_CATALOG.DIR_WS_BOXES.'affiliate.php')){   
   	$sts_block_name = 'affiliatebox';
   	require(STS_START_CAPTURE);
   	require(DIR_WS_BOXES . 'affiliate.php');
   	require(STS_STOP_CAPTURE);
   	$template['affiliatebox'] = strip_unwanted_tags($sts_block['affiliatebox'], 'affiliatebox');
   }else{
	$template['affiliatebox'] = '';
   }
 
   $sts_block_name = 'memberbox';
   require(STS_START_CAPTURE);
   require(DIR_WS_BOXES . 'memberlogin.php');
   require(STS_STOP_CAPTURE);
   $template['memberbox'] = strip_unwanted_tags($sts_block['memberbox'], 'memberbox');
 
   
    $sts_block_name = 'catmenu';
    require(STS_START_CAPTURE);
    echo "\n<!-- Start Category Menu -->\n";
    echo tep_draw_form('goto', FILENAME_DEFAULT, 'get', '');
    echo tep_draw_pull_down_menu('cPath', tep_get_category_tree(), $current_category_id, 'onChange="this.form.submit();"');
    echo "</form>\n";
    echo "<!-- End Category Menu -->\n";
    require(STS_STOP_CAPTURE);
    $template['catmenu'] = $sts_block['catmenu'];

// PWS clothing bof
if ($pws_engine->isInstalledPlugin('pws_clothing')){
	//var_dump($pws_products_gender);var_dump($pws_products_season);exit;
	$template['genderMale'] = '<a href="'.tep_href_link(basename($PHP_SELF),tep_get_all_get_params(array('action','gender')).'gender=m').'">'.tep_image_button('pws/gender_m'.($pws_products_gender=='m'?'_on':'').'.gif',PWS_CLOTHING_IMAGE_GENDER_MALE).'</a>';
	$template['genderWoman'] = '<a href="'.tep_href_link(basename($PHP_SELF),tep_get_all_get_params(array('action','gender')).'gender=w').'">'.tep_image_button('pws/gender_w'.($pws_products_gender=='w'?'_on':'').'.gif',PWS_CLOTHING_IMAGE_GENDER_WOMAN).'</a>';
	$template['genderUnisex'] = '<a href="'.tep_href_link(basename($PHP_SELF),tep_get_all_get_params(array('action','gender')).'gender=u').'">'.tep_image_button('pws/gender_u'.($pws_products_gender=='u'?'_on':'').'.gif',PWS_CLOTHING_IMAGE_GENDER_UNISEX).'</a>';
	$template['seasonSpring'] = '<a href="'.tep_href_link(basename($PHP_SELF),tep_get_all_get_params(array('action','season')).'season=pe').'">'.tep_image_button('pws/season_pe'.($pws_products_season=='pe'?'_on':'').'.gif',PWS_CLOTHING_IMAGE_SEASON_SPRING).'</a>';
	$template['seasonWinter'] = '<a href="'.tep_href_link(basename($PHP_SELF),tep_get_all_get_params(array('action','season')).'season=ai').'">'.tep_image_button('pws/season_ai'.($pws_products_season=='ai'?'_on':'').'.gif',PWS_CLOTHING_IMAGE_SEASON_WINTER).'</a>';
	$template['season4Seasons'] = '<a href="'.tep_href_link(basename($PHP_SELF),tep_get_all_get_params(array('action','season')).'season=4s').'">'.tep_image_button('pws/season_4s'.($pws_products_season=='4s'?'_on':'').'.gif',PWS_CLOTHING_IMAGE_SEASON_FOUR_SEASON).'</a>';
	//var_dump($template['genderMale']);exit;
}
// PWS clothing eof
    
  function tep_get_category_tree($parent_id = '0', $spacing = '', $exclude = '', $category_tree_array = '', $include_itself = false) {
    global $languages_id;

    if (!is_array($category_tree_array)) $category_tree_array = array();
    if ( (sizeof($category_tree_array) < 1) && ($exclude != '0') ) $category_tree_array[] = array('id' => '0', 'text' => "Catalog");

    if ($include_itself) {
      $category_query = tep_db_query("select cd.categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " cd where cd.language_id = '" . (int)$languages_id . "' and cd.categories_id = '" . (int)$parent_id . "'");
      $category = tep_db_fetch_array($category_query);
      $category_tree_array[] = array('id' => $parent_id, 'text' => $category['categories_name']);
    }

    $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and c.parent_id = '" . (int)$parent_id . "' and c.categories_status = '1' order by c.sort_order, cd.categories_name");
    while ($categories = tep_db_fetch_array($categories_query)) {
      if ($exclude != $categories['categories_id']) $category_tree_array[] = array('id' => $categories['categories_id'], 'text' => $spacing . $categories['categories_name']);
      $category_tree_array = tep_get_category_tree($categories['categories_id'], $spacing . '&nbsp;&nbsp;&nbsp;', $exclude, $category_tree_array);
    }

    return $category_tree_array;
  }
  
  
  $sts_block_name = 'bestshopping' ;
  // tracking code per bestshopping
  
  require(STS_START_CAPTURE);
  
  
if( strstr($PHP_SELF , 'checkout_success') && isset($_COOKIE['tid_bs']))
{	
	require (DIR_WS_CLASSES . 'image_bestshopping_php5.class.php');
	
	$order_total_query = tep_db_query("Select value from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . $orders['orders_id'] . "' AND class = 'ot_shipping' ");
	$order_total = tep_db_fetch_array($order_total_query);
	
	
	$id_ordine = $orders['orders_id'];
	$spese_spedizione = $order_total['value'];
	
	// creazione oggetto per l'immagine
	$img_bs = new OB_image_bestshopping();
	
	// setta l'id del vostro ordine
	$img_bs->SetOrderId($id_ordine);
	// setta le spese di spedizione
	$img_bs->SetShippingCost($spese_spedizione);
	
	mail('info@oscommerce.it','ordine e spedizone', $id_ordine . ' ' . $spese_spedizione);
	
	/*
	$lista_prodotti = array (
		array('id'=>'AQG456', 'prezzo'=>'8.8', 'quantita'=>9),
		array('id'=>56, 'prezzo'=>5, 'quantita'=>6),
		array('id'=>34, 'prezzo'=>3, 'quantita'=>9)
		);
		*/
	$lista_prodotti = $products_array;
	
	// $stringa_prodotti = implode(' ; ', $lista_prodotti);
	// mail('info@oscommerce.it','array bestshopping', $stringa_prodotti);
	
	
	// ciclo per l'inserimento dei vari prodotti comprati
	foreach ($lista_prodotti as $prodotto) {
		 
		// mail('info@oscommerce.it','array bestshopping', $prodotto['id'] . ' ' . $prodotto['prezzo'] . ' ' . $prodotto['quantita']);
		// creazioen dell'oggetto item
		$item = new OB_item_bestshopping();
		// setta l'id del prodotto
		$item->SetId($prodotto['id']);
		// setta il prezzo del singolo prodotto
		$item->SetPrice($prodotto['prezzo']);
		// setta la quantit�
		$item->SetQuantity($prodotto['quantita']);
		// inserisce l'item nell'oggetto dell'immagine
		$img_bs->AppendItem($item);
	}

	// variabile che conterr� l'html dell'immagine
	echo $img_bs->WriteImage();
	
 	require(STS_STOP_CAPTURE);
   $template['bestshopping'] = $sts_block['bestshopping'];
  
}
  
	  
   // google adwords conversione per gli acquisti
   $sts_block_name = 'google_acquisto';
     
  require(STS_START_CAPTURE);
  if( strstr($PHP_SELF , 'checkout_success') )
 
  {?>
  					<!-- Google Code for Acquisto Conversion Page -->
				
				<script type="text/javascript">
				
				/* <![CDATA[ */
				
				var google_conversion_id = <?php  echo GOOGLE_CONVERSION_ID ?>;
				
				var google_conversion_language = "it";
				
				var google_conversion_format = "2";
				
				var google_conversion_color = "ffffff";
				
				var google_conversion_label = "<?php  echo GOOGLE_ACQUISTO_LABEL ?>";
				
				var google_conversion_value = 0;
				
				/* ]]> */
				
				</script>
				
				<script type="text/javascript" src="http://www.googleadservices.com/pagead/conversion.js">
				
				</script>
				
				<noscript>
				
				<div style="display:inline;">
				
				<img height="1" width="1" style="border-style:none;" alt="" src="http://www.googleadservices.com/pagead/conversion/<?php  echo GOOGLE_CONVERSION_ID ?>/?label=<?php  echo GOOGLE_ACQUISTO_LABEL ?>&amp;guid=ON&amp;script=0"/>
				
				</div>
				
				</noscript>
  <?php }
  require(STS_STOP_CAPTURE);
   $template['google_acquisto'] = $sts_block['google_acquisto'];
   
   /*
   $sts_block_name = 'specials_scroll';
   require(STS_START_CAPTURE);
   require(DIR_WS_BOXES . 'specials_scroll.php');
   require(STS_STOP_CAPTURE);
   $template['specials_scroll'] = strip_unwanted_tags($sts_block['specials_scroll'], 'specials_scroll');
 */

   
   // kelkoo tradedoubler

    $sts_block_name = 'kelkooTD' ;
  // tracking code per kelkoo
  
  require(STS_START_CAPTURE);
    // verificare l'impostazione del cookie TD

  if( strstr($PHP_SELF , 'checkout_success') && KELKOO_ORGANIZATION <> '' && KELKOO_EVENT <> '' )
{	
	
	$order_total_query = tep_db_query("Select value from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . $orders['orders_id'] . "' AND class = 'ot_shipping' ");
	$order_total = tep_db_fetch_array($order_total_query);
	
	
	$id_ordine = $orders['orders_id'];
	$spese_spedizione = $order_total['value'];
		

//	mail('info@oscommerce.it','ordine e spedizone', $id_ordine . ' ' . $spese_spedizione);
	
	/*
	$lista_prodotti = array (
		array('id'=>'AQG456', 'prezzo'=>'8.8', 'quantita'=>9),
		array('id'=>56, 'prezzo'=>5, 'quantita'=>6),
		array('id'=>34, 'prezzo'=>3, 'quantita'=>9)
		);
		*/
	$lista_prodotti = $products_array;
	
	// $stringa_prodotti = implode(' ; ', $lista_prodotti);
	// mail('info@oscommerce.it','array bestshopping', $stringa_prodotti);
	
	$TotaleOrdine = 0;
	$f1 = 0; // prodotti ordinati
	
	// ciclo per l'inserimento dei vari prodotti comprati
	foreach ($lista_prodotti as $prodotto) {
		 
		
		$TotaleOrdine = $TotaleOrdine + $prodotto['prezzo'] * $prodotto['quantita'];
		
		$DettagliOrdine .= "f1=".$prodotto['quantita']."&f2=".$prodotto['text']."&f3=".$prodotto['prezzo']."|";
		
	}

	

// NumeroOrdine = Sostituire con il numero dell'ordine
$NumeroOrdine = $orders['orders_id'];

// Dettaglio ordini
// Esempio (puoi aggiungere piu' prodotti)
// Inserire | tra i prodotti se piu' di uno (f1=...&f2=...&f3=...|f1=...&f2=...&f3=...)
// f1 e' il numero di pezzi ordinati
// f2 e' il nome del prodotto
// f3 e' il prezzo del prodotto IVA compresa. Usare un . (punto) per separare i decimali e nessun separatore tra le migliaia.
// $DettagliOrdine = "f1=1&f2=Nikon LS 4000 SUPER COOLSCAN&f3=1815.84|f1=2&f2=Alcatel 5250&f3=142.68|f1=2&f2=Canon i350&f3=123.23";

// tagliamo l'ultimo carattere & del dettaglio
$DettagliOrdine = substr($DettagliOrdine, 0, strlen($DettagliOrdine)-1); 
// Importante! Il parametro DettagliOrdine deve essere encodato nel formato UTF-8.
$DettagliOrdine = urlencode($DettagliOrdine);

// VALUTAZIONE NEGOZIO
// name : il nome dell'utente
// email : la mail dell'utente
// expDeliveryDate : la data di spedizione prevista per l'ordine. Se non c'e' nessuna data prevista, 
// il campo puo' essere lasciato vuoto e la mail verra' spedita 10 giorni dopo. Formato: AAAA-MM-GG
// $Valutazione = "name=Nome Cognome&email=nome.cognome@dominio.it&expDeliveryDate=2005-11-30";

// dati cliente 
$customer_info_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from customers where customers_id = '". $_SESSION['customers_id'] . "'");
$customer_array = tep_db_fetch_array($customer_info_query);
$Valutazione = "name=".$customer_array['customers_firstname']." ". $customer_array['customers_lastname']."&email=".$customer_array['customers_email_address']."&expDeliveryDate=";

// Importante! Il parametro Valutazione deve essere encodato nel formato UTF-8.
$Valutazione = urlencode($Valutazione);

//ATTENZIONE: Importante! Nel link usare "&" e non il formato html "&amp;"
echo "<img src=\"http://tbs.tradedoubler.com/report?organization=".KELKOO_ORGANIZATION."&event=".KELKOO_EVENT."&currency=EUR&orderNumber=".$NumeroOrdine."&orderValue=".$TotaleOrdine."&reportInfo=".$DettagliOrdine."&review=".$Valutazione."\">";
   	
 	require(STS_STOP_CAPTURE);
   $template['kelkooTD'] = $sts_block['kelkooTD'];
}

if(file_exists(STS_TEMPLATE_DIR . 'skins/boxes/special_counter.htm'))
	{
	   $sts_block_name = 'special_counter';
	   require(STS_START_CAPTURE);
	   require(DIR_WS_BOXES . 'special_counter.php');

	   require(STS_STOP_CAPTURE);
	   $template['special_counter'] = strip_unwanted_tags($sts_block['special_counter'], 'special_counter');
	}
   
?>