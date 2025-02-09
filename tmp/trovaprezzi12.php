<?php
//   require('includes/database_tables.php');  //include le costanti con i nomi delle tabelle del DB
//   include('includes/configure.php');        // include i dati per la connessione al DB
  require 'includes/application_top.php'; 
  require_once(DIR_WS_CLASSES . 'shipping.php');  

 $shipping_modules = new shipping;		

	ini_set('max_execution_time',36000);
//	ini_set('error_reporting', E_ALL);
//	ini_set("display_errors",true);
	
	// IMPLEMENTAZIONE DELLA CACHE	
	/*print_r($_GET);
	print_r($HTTP_GET_VARS);
	print_r($_POST);
	print_r($_REQUEST);
	print $from;
 

	exit;
	*/
	
	$cachefile = "temp/". $PHP_SELF."_" . $from . $language .  "_turbo_.html";

	if ($_GET['nocache'] == 'true') //rimuove la cache
			unlink($cachefile);
			
	if ($_GET['nocache'] == 'true') //rimuove la cache
  	{
		$filesDaCancellare = glob('temp/*trovaprezzi*');
		array_map('unlink', $filesDaCancellare);
  		//unlink('../temp/*turbo*');
  	}		
  	
  	
    if ($from == 'payvment')
      {
      	 $cachefile = '';
      }
    $cachetime = 10 * 60 * 60; //  10 ore
	
      // Serve from the cache if it is younger than $cachetime
      if (file_exists($cachefile) && (time() - $cachetime
         < filemtime($cachefile))) 
      {
      //	echo "<!-- Cached ".date('jS F Y H:i', filemtime($cachefile))."-->\n";
         // include($cachefile);
         tep_redirect(HTTP_SERVER . '/' . $cachefile);
         exit;
      }
        // open the cache file for writing
       $fp = fopen($cachefile, 'w'); 
       // save the contents of output buffer to the file 
     // ob_start(); // start the output buffer
	
	
	
	$site_url = HTTP_SERVER; 	// <--- Inserire qui l'URL del sito SENZA SLASH FINALE es: "http://www.undominio.com/catalog"
	//  print_r($_SESSION);
    // $language_id = $_SESSION['languages_id'];                   	    // <--- Inserire qui l'ID della lingua utilizzata

    
   //exit;
    
    if ($from == '')
		 $from = 'Trovaprezzi';
   
   /*********************************** NON MODIFICARE ALTRO ***************************************/
 //  $shippingmethods = getshippingmodes(); // Ottiene una sola volta i metodi di spedizione diponibili da db
 //  $shippingdetails = getshippingsdetails($shippingmethods); // Ottiene le spese di spedizione relative ai metodi di spedizione disponibili
   global $rescount;
   //$rescount=0;
   $listing_sql = "
   SELECT
   p.products_id,
   p.products_image,
   p.manufacturers_id, 
   p.products_price,
   p.products_weight,
   p.products_quantity as qty,
   p.products_tax_class_id AS tax_id,
   p.EAN,";
   
  if ($from == 'chapaki') // aggiungiamo il Codice esprinet che corrisponde al codice produttore 
		       {
   				 $listing_sql .= "p.Codice,";
		       }
  if ($from == 'googleshopping') // aggiungiamo il vpn
		       {
   				 $listing_sql .= "p.vpn,";
		       }		       
   $listing_sql .= "pd.products_name,
   pd.products_description,
   p2c.categories_id,
   c.parent_id,
   c.categories_id,
   cd.categories_name,
   m.manufacturers_id,
   IF(p.manufacturers_id = 0, NULL, m.manufacturers_name) AS marca,
   IF(s.status, s.specials_new_products_price, NULL) AS specials_new_products_price,
   IF(s.status, s.specials_new_products_price, p.products_price) AS final_price,
   IF(p.products_quantity > 0, 'disponibile','non disponibile') as availability,
   IF(p.products_model = NULL, -1, p.products_model) as codprod
   FROM
   ".TABLE_PRODUCTS." p
   LEFT JOIN ".TABLE_SPECIALS." s ON (p.products_id = s.products_id and s.customers_group_id=0)
   LEFT JOIN ".TABLE_MANUFACTURERS." m ON p.manufacturers_id = m.manufacturers_id,
   ".TABLE_PRODUCTS_DESCRIPTION." pd,
   ".TABLE_PRODUCTS_TO_CATEGORIES." p2c, 
   ".TABLE_CATEGORIES." c,
   ".TABLE_CATEGORIES_DESCRIPTION." cd
   
   WHERE
   p2c.categories_id = c.categories_id AND
   c.categories_id = cd.categories_id AND
   c.categories_status = '1' AND
   c.categories_status_pc = '1' AND
   p.products_id = p2c.products_id AND
   pd.products_id = p2c.products_id AND
   p.products_status = '1' AND
   pd.language_id = '$languages_id' AND 
   cd.language_id = '$languages_id'";
   if ($pws_engine->isInstalledPlugin('pws_traffic_report','application') && $pws_engine->fieldExists('products_export',TABLE_PRODUCTS)){
   	$listing_sql.= " AND p.products_export = '1' 
   	";
   }
 /*  $listing_sql.="
   ORDER BY final_price DESC
   ";
   
     echo $listing_sql;
  exit;
*/

  
  
   if($result = tep_db_query( $listing_sql/*, DbConnection()*/ ) ){
      //tep_db_close();
      $filestring = "";
      if ($from == 'payvment')
      {
      	echo	$header = '"name","description","price","currency","qty","is_qty_unlimited","weight","weight_unit","sku","type","available_date","expiration_date","client_product_id","image","state","tags","is_taxable","is_free_shipping","shipping_method","flat_rate","flat_rate_additional","is_featured","category_code","client_category_name"' ."\r\n";
          fwrite($fp, $header); // salvo l'header nella cache
      }
      if ($from == 'googleshopping')
      {
      	echo $file_header = "id\tcondizione\tlink\ttitolo\tmarca\tdescrizione\tprezzo\tpeso spedizione\tlink_immagine\tquantit&agrave;\tmpn\tean\n";
      	 		fwrite($fp, $file_header); // salvo l'header nella cache
      }
 
      
      while($row=tep_db_fetch_array($result)){
         $descrizionehtml=$row["products_description"];  
         $descrizionehtml=strip_tags($descrizionehtml);  
         $descrizionehtml=substr($descrizionehtml,0,255);
         $descrizione1 = CleanHtml($descrizionehtml);
         $cat_arr = CatString($row["categories_id"]);
         $cat_arr = array_reverse($cat_arr);
         $cat_list = implode(";",$cat_arr);
         //$final_price = Tasse($row['tax_id'], $row['final_price']);
		 //$final_price = round($pws_prices->getBestPrice($row['products_id']),2);
		 $final_price = $pws_prices->calculatePrice($pws_prices->getBestPrice($row['products_id']),0,$row['products_id'],true);
      	 $shippingprice = getShippingPrice($row['products_id'], $final_price);
      	 
         //$shippingprice = getshippingprice($row["products_weight"],$row["products_price"],$shippingmethods,$shippingdetails);
         
         
   
         /*$tablestring.="<tr>". // Scommentare per comporre una tabella con i dettagli dei prodotti utile per il debug
               "<td>" . $row["products_id"] . "</td>" .
               "<td>" . $row["products_name"] . "</td>" .
               "<td>" . $cat_list ."</td>" .
               "<td>" . $descrizione1 . "</td>".
               "<td>" . $row["products_weight"] . "</td>" .
               "<td>" . $row["products_price"] . "</td>" .
               "<td>" . $row["availability"] . "</td>" .
               "<td>" . $shippingprice . "</td>"  .
                 "</tr>"; */
         
         if (strstr($row["products_image"],"http://")) // itercetta il link ad immagini esterne
	         $image =  $row["products_image"];
	     else 
	     	 $image = $site_url."/images/".$row["products_image"];    
	     	 
	     if ($from == 'mrwallet')
	     	$fineriga = '';
	     else 
	     	$fineriga = '<endrecord>';
	     	 
	     // sostituiamo eventuali pipe nel nome e nella descrizione
	     $row["products_name"] = str_replace("|", "-",$row["products_name"]);
	     $descrizione1 = str_replace("|", "-",$descrizione1);

	 //    if(defined(PRICE_COMP_CLAIM) )
	     	$descrizione1 = trim( PRICE_COMP_CLAIM . ' '. $descrizione1 );
	     /* tracciato payvment per export su facebook
	     "name","description","price","currency","qty","is_qty_unlimited","weight","weight_unit","sku","type","available_date","expiration_date","client_product_id","image","state","tags","is_taxable","is_free_shipping","shipping_method","flat_rate","flat_rate_additional","is_featured","category_code","client_category_name"
		 "MyProductName1","MyProductNameDescription1",19.99,"USD",1,0,0.1,"LB","CSV000000000001","PHYSICAL",08/05/03 09:36 PM,01/01/20 12:00 AM,"CLPRODID000000000001","http://www.safmc.net/Portals/6/photo-not-available.jpg","NEW","dogs cats pets",1,0,"FLAT",1.1,0.1,1,"59D15041","Pet Supplies"
	      */
	     if ($from == 'payvment')
	     {
	     	$f_del = '"';
	     	$f_sep = ',';
	     	$name = str_replace("\"", "-",$row["products_name"]);
	     	$description = CleanHtml($descrizionehtml);
	     	$description = str_replace("\"", "-",$description);
	     	if($row["tax_id"] >= '1')
	     		$is_taxable = '1';
	     	else 
	     		$is_taxable = '0';
	     	
	     	if ($shippingprice <= '0')
	     		$is_free_shipping = '1';
	    	else
	    		$is_free_shipping = '0';
	    		
	    	$shipping_method = 'FLAT';
	    	
	    	$flat_rate = $shippingprice;
	    	$flat_rate_additional = '0';
	    	$is_featured = '0';
	    	$category_code = ''; //vedere tabella allegata per le categorie proprietarie 
	     	$client_category_name =	$cat_list;
	     	$state = 'AVAILABLE';
	     	
	     	
	        $filestring = $f_del . $name . $f_del . $f_sep .
	       				  $f_del . $description . $f_del . $f_sep . 
	       				  $f_del . $final_price . $f_del . $f_sep .
	       				  $f_del . 'EUR' . $f_del . $f_sep .
	       				  $f_del . $row["qty"] . $f_del . $f_sep .	       				  	       				  
	       				  $f_del . '0' . $f_del . $f_sep .	    
	       				  $f_del . $row["products_weight"] . $f_del . $f_sep .	       				  	       				  
	       				  $f_del . 'KG' . $f_del . $f_sep .	  
	       				  $f_del . $row["products_id"] . $f_del . $f_sep .	  
	       				  $f_del . 'PHYSICAL' . $f_del . $f_sep .	
	       				  $f_del . $available_date . $f_del . $f_sep .	  
	       				  $f_del . $expiration_date . $f_del . $f_sep .	  
	       				  $f_del . $row["products_id"] . $f_del . $f_sep .	  
	       				  $f_del . $image . $f_del . $f_sep .	  
	       				  $f_del . 'NEW' . $f_del . $f_sep .	  
						  $f_del . $name . $f_del . $f_sep .	       				  
						  $f_del . $is_taxable . $f_del . $f_sep .	       				  
						  $f_del . $is_free_shipping . $f_del . $f_sep .	       				  
						  $f_del . $shipping_method . $f_del . $f_sep .	
						  $f_del . $flat_rate . $f_del . $f_sep .	
						  $f_del . $flat_rate_additional . $f_del . $f_sep .							  
						  $f_del . $is_featured  . $f_del . $f_sep .	
						  $f_del . $category_code  . $f_del . $f_sep .						  						  
						  $f_del . $client_category_name  . $f_del . $f_sep .		
						  $f_del . $state  . $f_del . $f_sep .	
						  "\r\n";
	     }
	        else // tracciato trovaprezzi standard  e compatibili
	        {    
	          if ($from == 'chapaki')
	          /*
					struttura chapaki:
					
					Prodotto                                            (nome prodotto)
					Marca                                                  (marca del produttore)
					Descrizione                                       (max 255 caratteri)
					Prezzo                                                 Numerico comprensivo di Iva (senza separatore delle migliaia)
					Codice Prodotto                               OBBLIGATORIO (UNICO PER CIASCUN PRODOTTO; solitamente è il vostro codice interno)!
					Link                                                       link alla pagina del prodotto sul vostro sito
					Disponibilità                                      (Possibili Valori: numerico oppure disponibile, non disponibile, in arrivo, vedere sito, limitata)
					Categoria                                           categorie del vostro sito (esempio:  Fotografia,Macchine Digitali) (le macrocategorie andrebbero separate dalle sottocategorie o da , o ; )
					URL Immagine                                 link all'immagine del prodotto
					Spese Spedizione                           Numerico ( se incluse mettere 0)
					Codice Produttore                         (OBBLIGATORIO)
		       */
	          {
		       	$codice_produttore = $row['Codice'];
		       	if ($codice_produttore == '')
		       	{
		       $filestring = ''; // salta la riga se il codice produttore è vuoto
		       	}
		       	else
		       	$filestring = 
		       	$row["products_name"]."|". 
		       	$row["marca"]."|" . 
		       	$descrizione1."|".
		       	$final_price."|".
		       	$row["products_id"]."|".
		       	$site_url."/product_info.php?products_id=".$row["products_id"]."&from=". $from . "|".
		       	$row["availability"] ."|" . 
		       	$cat_list."|".
		       	$image ."|".
		       	$shippingprice ."|" . 
		       	$codice_produttore .
		       	$fineriga . "\r\n";
		       	
		       }
		       elseif ($from == 'googleshopping')
		       {
		       	$products = $row;
		   //   $file_header = "id\tcondizione\tlink\ttitolo\tmarca\tdescrizione\tprezzo\t																																															peso spedizione\tlink_immagine\tquantit&agrave;\tmpn\tean\n";
				$filestring = $products["products_id"] . "\tnuovo\t" . $site_url . "/product_info.php?products_id=" . $products["products_id"] . "\t" . strtolower($products["products_name"]) . "\t" . $products["marca"] . "\t" . substr($descrizione1, 0, 400). "\t" . $final_price . "\t" . $products["products_weight"] . "\t" . $image . "\t" . $products["qty"]."\t". $products["vpn"] . "\t". $products["EAN"] . "\n";	
		       	$filestring = htmlentities($filestring);
		       	
		       }
		       
		       else
         		$filestring = $row["products_id"]."|".$row["products_name"]."|". $image ."|".$site_url."/product_info.php?products_id=".$row["products_id"]."&from=". $from . "|".$cat_list."|".$final_price."|".$descrizione1."|".$row["marca"]."|" . $row["availability"] ."|" . $shippingprice ."|" . $row["codprod"] .$fineriga . "\r\n";
	        }
			
         // modifica che mostra opzionalmente solo i prodotti disponibili
        // print PRICE_COMP_ONLYAVAILABLE;
        // exit;
         
 		if (strstr(PRICE_COMP_ONLYAVAILABLE , 'rue') && ($row["availability"] == 'non disponibile' ))
 		{
 		
 			// salta la riga 
 			/*	echo $filestring; // stampa riga per riga e resetta la variabiel così non ci sono problemi di memoria
       		    $rescount += 1;// $rescount++;
         	    fwrite($fp, $filestring); // salvo la riga nella cache
 			*/
 		}
    	else
    	{	// funzionamento standard, esporta disponibile e indisponibili oppure il prodotto è disponibile       
    		echo $filestring; // stampa riga per riga e resetta la variabiel così non ci sono problemi di memoria
       	$rescount += 1;// $rescount++;
	    fwrite($fp, $filestring); // salvo la riga nella cache
    	}

      }  
   }else{
      echo "Errore nell'esecuzione della query:<br>".$listing_sql."<br>".mysql_errno().": ".mysql_error()."<br>";
   }
   
  		// close the file
        fclose($fp); 
		// Send the output to the browser
        // ob_end_flush(); 

        
   if($_GET['debug']=="debugme") {
      echo "<p><b>Num risultati query:</b>$rescount</p>";
      //echo "<pre>$listing_sql</pre>";
      $countres = TpDebug();
      echo "<p><b>Num prodotti attivi:</b>".$countres[0]."</p>";
      echo "<p><b>Num prodotti inattivi</b>".$countres[1]."</p>";
      /*foreach ($shippingmethods as $metodo) {
         echo "metodo" . $metodo . "</br>";
      }*/
      /*echo "<table>"; // Scommentare per stampare la tabella con i dettagli del prodotto
      echo $tablestring;
      echo "</table>";*/
      
   }
   /******************************** CONNESSIONE AL DATABASE ***************************************/ 
   /* Ritorna la risorsa di connessione $link                                          */
   function DbConnection(){                                                      //
    return;  
   	$link = mysql_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD)               //
      or die(  "Errore nella connessione al database ".mysql_errno().": ".mysql_error()  );     //
      mysql_select_db(DB_DATABASE)                                               //
      or die( "Errore nella selezione del database ".mysql_errno().": ".mysql_error() );        //
      return $link;                                                           //
   }                                                                       //
   /************************************************************************************************/
   
   /******************************* CATEGORIE ******************************************************/
   function CatString($cat) {
      $list = array();
      do{
         $catsql = "SELECT c.categories_id, c.parent_id, cd.categories_name FROM ".TABLE_CATEGORIES." c, ".TABLE_CATEGORIES_DESCRIPTION." cd WHERE c.categories_id = cd.categories_id AND c.categories_id = $cat";
         if($res = tep_db_query($catsql/*, DbConnection()*/ ) ){
			//
            $row = tep_db_fetch_array($res);
            //echo "ID:".$row['categories_id']." Parent:".$row['parent_id']." Nome:".$row['categories_name']."<br>";
            $cat = $row['parent_id'];
            $parent = $row['parent_id'];
            array_push($list,$row['categories_name']);
         }
      }while($parent != 0);
      return $list;
         
   }
   /******************************* CALCOLO IVA / TASSE ********************************************/
   function Tasse($tax_id, $price){
      if ($tax_id == 0){
         $final_price = $price;
      }else{
         $taxsql = "SELECT tax_rate FROM tax_rates WHERE tax_rates_id = $tax_id";
         if ($res = tep_db_query($taxsql/*, DbConnection()*/ ) ){
            
            $row = tep_db_fetch_array($res);
            $rate = $row['tax_rate'];
            $tax = ($price / 100) * $rate;
            $final_price = $price + $tax;
         }
      }
      return $final_price;
   }
   /******************************* METODI DI SPEDIZIONE *************************************/

 	
   function getShippingPrice($products_id, $products_price)
   {
	global $shipping_modules;
	// print_r($shipping_modules->quote_product($products_id));
	 // print tep_count_shipping_modules();
	
//	exit;
	//      require_once(DIR_WS_CLASSES . 'shipping.php');
	//		$shipping_modules = new shipping;
   	  // controlliamo anche se il prezzo del prodotto supera il totale ordine spedizione gratis
       if ( defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true') ) {

					$free_shipping = false;
				    if ( ($products_price >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) ) {
				      $free_shipping = true;
				    }	

                }
            
            if ( $free_shipping == true ) 
            { 
      			return $shippingprice = 0;
      		}
            elseif(tep_count_shipping_modules() >= 1) // ricavo il più economico fra i corrieri configurati escludendo quelli con costo zero (corriere cliente e ritiro in sede)
            {
            $cheapest = ''; 
   			foreach ($shipping_modules->quote_product($products_id) as $array)
	   			{
	   				if($array['methods']['0']['cost'] >= '0')
	   				{
	   					if($array['methods']['0']['cost'] >= '0' && $array['methods']['0']['cost'] << $cheapest)
	   						$cheapest = $array['methods']['0']['cost'];
	   					//	print_r ($array['methods']['0']['cost']);
	   					//	print_r($cheapest);
	   						// exit;
	   				}
	   				
					// return $shippingprice = $cheapest;
	   			}
	   			return $shippingprice = $cheapest;
            
            }
            else {
            	return $shippingprice = -1; // non sappiamo qual è il costo di spedizione
            }
   	
   }
   
   

   
   /******************************* FORMATTAZIONE STRINGA DESCRIZIONE ******************************/
   function CleanHtml($string){
      $string = strip_tags($string);                    //elimina i tags html
      $string = substr($string,0,255);               //taglia la stringa fino a max 255 caratteri     
      $search = array ("'<script[^>]*?>.*?</script>'si",   // Rimozione del javascript
                   "'<[\/\!]*?[^<>]*?>'si",           // Rimozione dei tag HTML
                         "'([\r\n])[\s]+'",                  // Rimozione degli spazi bianchi
                      "'([\r])+'",                         // Rimozione degli spazi bianchi
                      "'([\n])+'",                      // Rimozione degli spazi bianchi
                         "'&(quot|#34);'i",                   // Sostituzione delle entit� HTML
                         "'&(amp|#38);'i",
                         "'&(lt|#60);'i",
                       "'&(gt|#62);'i",
                     "'&(nbsp|#160);'i",
                      "'&(iexcl|#161);'i",
                      "'&(cent|#162);'i",
                      "'&(pound|#163);'i",
                      "'&(copy|#169);'i",
                      "'&#(\d+);'e");                      // Valuta come codice PHP

      $replace = array ("",
                 "",
                 "",
             "",
               "",
                 "",
                 "",
                 "",
                 "",
                 "",
                 chr(161),
                 chr(162),
                 chr(163),
                 chr(169),
                 "chr(\\1)");

      $descrizione1 = preg_replace($search, $replace, $string);
      return $descrizione1;
   }
   function TpDebug(){
      $results = array();
      $sql1 = "SELECT COUNT(products_id) FROM products WHERE products_status = 1";
      $sql2 = "SELECT COUNT(products_id) FROM products WHERE products_status = 0";
      $sql3 = "SELECT COUNT(p.products_id) FROM
         ".TABLE_PRODUCTS." p,
         ".TABLE_PRODUCTS_DESCRIPTION." pd,
         ".TABLE_PRODUCTS_TO_CATEGORIES." p2c, 
         ".TABLE_CATEGORIES." c,
         ".TABLE_CATEGORIES_DESCRIPTION." cd
         LEFT JOIN ".TABLE_SPECIALS." s ON p.products_id = pd.products_id
         LEFT JOIN ".TABLE_MANUFACTURERS." m ON p.manufacturers_id = m.manufacturers_id
         WHERE
         p2c.categories_id = c.categories_id AND
         c.categories_id = cd.categories_id AND
         p.products_id = p2c.products_id AND
         pd.products_id = p2c.products_id AND
         p.products_status = '1' AND
         pd.language_id = '$languages_id' AND 
         cd.language_id = '$languages_id'
         "; 
      if($result = tep_db_query( $sql1/*, DbConnection()*/ ) ){
         
         $row1=tep_db_fetch_array($result);
         $results[0] = array_pop($row1);
      }else{
         $results[0] = "<p>Huston, we got a problem: <b>".mysql_errno()."</b> - ".mysql_error();
      }
      //echo "<p>Risultati Query:$rescount</p>";
      
      if($result = tep_db_query( $sql2/*, DbConnection()*/ ) ){
         
         $row2=tep_db_fetch_array($result);
         $results[1] = array_pop($row2);
      }else{
         $results[1] =  "<p>Huston, we got a problem: <b>".mysql_errno()."</b> - ".mysql_error();
      }
      return $results;
   }
   
// FINE CACHE FILE PHP
 
?>
