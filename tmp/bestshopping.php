<?php
//   require('includes/database_tables.php');  //include le costanti con i nomi delle tabelle del DB
//   include('includes/configure.php');        // include i dati per la connessione al DB
  require 'includes/application_top.php'; 
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
  //  $cachefile = "temp/". $PHP_SELF."_" . $from . ".html";
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
   $language_id = "4";                   	    // <--- Inserire qui l'ID della lingua utilizzata
	if ($from == '')
		 $from = 'bestshopping';
   
   /*********************************** NON MODIFICARE ALTRO ***************************************/
   $shippingmethods = getshippingmodes(); // Ottiene una sola volta i metodi di spedizione diponibili da db
   $shippingdetails = getshippingsdetails($shippingmethods); // Ottiene le spese di spedizione relative ai metodi di spedizione disponibili
   global $rescount;
   //$rescount=0;
   $listing_sql = "
   SELECT";
  // modifica per esprinet
  $sqlespri="SHOW COLUMNS FROM products like 'Codice' ";
  $query_espri = tep_db_query( $sqlespri );
  $field_array = tep_db_fetch_array($query_espri);
  if ($field_array['Field'] = 'Codice')
  $listing_sql .= "
   p.Codice,";
  
  // end modifica per esprinet
  
   $listing_sql .= "
   p.EAN,
   p.products_id,
   p.products_image,
   p.manufacturers_id, 
   p.products_price,
   p.products_weight,
   p.products_tax_class_id AS tax_id,
   pd.products_name,
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
   p.products_id = p2c.products_id AND
   pd.products_id = p2c.products_id AND
   p.products_status = '1' AND
   pd.language_id = '$language_id' AND 
   cd.language_id = '$language_id'";
   if ($pws_engine->isInstalledPlugin('pws_traffic_report','application') && $pws_engine->fieldExists('products_export',TABLE_PRODUCTS)){
   	$listing_sql.= " AND p.products_export = '1' ";
   }
 /*  $listing_sql.="
   ORDER BY final_price DESC
   ";
*/
   //echo $listing_sql;
   if($result = tep_db_query( $listing_sql/*, DbConnection()*/ ) ){
      //tep_db_close();
      $filestring = "";
      while($row=tep_db_fetch_array($result)){
         $descrizionehtml=$row["products_description"];  
         $descrizionehtml=strip_tags($descrizionehtml);  
         $descrizionehtml=substr($descrizionehtml,0,255);
         $descrizione1 = CleanHtml($descrizionehtml);
         $cat_arr = CatString($row["categories_id"]);
         $cat_arr = array_reverse($cat_arr);
         $cat_list = implode(">",$cat_arr);
         //$final_price = Tasse($row['tax_id'], $row['final_price']);
		 //$final_price = round($pws_prices->getBestPrice($row['products_id']),2);
		 $final_price = $pws_prices->calculatePrice($pws_prices->getBestPrice($row['products_id']),0,$row['products_id'],true);
         if (isset($_GET['noshipping']))
         {
            if ($_GET['noshipping'] == '1')
            {
               $shippingprice = -1;
            }
            else
            {
               $shippingprice = getshippingprice($row["products_weight"],$row["products_price"],$shippingmethods,$shippingdetails);
            }
         }
         else
         {
            $shippingprice = getshippingprice($row["products_weight"],$row["products_price"],$shippingmethods,$shippingdetails);
         }
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
	     	 
	     	 
         $filestring = $row["products_id"]."|".$row["products_name"]."|". $image ."|".$site_url."/product_info.php?products_id=".$row["products_id"]."&from=". $from . "|".$cat_list."|".$final_price."|".$descrizione1."|".$row["marca"]."|" . $row["availability"] ."|" . $shippingprice ."|" . $row["codprod"] . "|" . $row["EAN"] . "|" . $row["Codice"] . "\r\n";
         echo $filestring; // stampa riga per riga e resetta la variabiel così non ci sono problemi di memoria
         $rescount += 1;// $rescount++;

 
	    fwrite($fp, $filestring); // salvo la riga nella cache

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
   function getshippingmodes(){
      $metodiattivi = array();      // Array contenente i metodi di spedizione
      $methodsql = "SELECT configuration_value FROM ".TABLE_CONFIGURATION." WHERE configuration_key=\"MODULE_SHIPPING_INSTALLED\""; // Ottiene i moduli installati per le spese di spedizione //
      if($res = tep_db_query($methodsql/*, DbConnetep_db_)*/ ) ){
         
         $row = tep_db_fetch_array($res);
      }

      $freeshippingsql = "SELECT configuration_value FROM " . TABLE_CONFIGURATION. " WHERE configuration_key=\"MODULE_ORDER_TOTAL_INSTALLED\""; // Ottiene i metodi di visualizzazione del totale per determinare se � attivo il free shipping

      if($res = tep_db_query($freeshippingsql/*, DbConnection()*/ ) ){
         
         $rowfree = tep_db_fetch_array($res);
      }

      if ( strpos($rowfree['configuration_value'],"ot_shipping.php") === false)
      {
      }
      else
      {
         $freeshippingactivesql = "SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key=\"MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING\""; // Verifica che sia attivo il free shipping
         if($res = tep_db_query($freeshippingactivesql /*, DbConnection()*/ ) )
         {
            
            $rowfreeactive = tep_db_fetch_array($res);
         }
         if ($rowfreeactive['configuration_value'] == "true")
         {
            array_push($metodiattivi,"free"); // E' Stata configurata la spedizione gratuita se l'ordine supera un certo prezzo
         }
      }


      $metodi = explode(";",$row['configuration_value']);  // Ottiene i metodi di pagamento
      

      foreach ($metodi as $metodo){    // Calcolo le spese di spedizione in base ai metodi installati
         switch ($metodo){

         case "flat.php" :       // Spese di spedizione flat
            $activesql = "SELECT configuration_value FROM ".TABLE_CONFIGURATION." WHERE configuration_key=\"MODULE_SHIPPING_FLAT_STATUS\""; 
            if($res = tep_db_query($activesql/*, DbConnection()*/ ) ){
               
               $row = tep_db_fetch_array($res);
               if ($row['configuration_value'] == "True"){
                  array_push($metodiattivi,"flat");
               }
            }
            break;

         case "item.php" :       // spese di spedizione in base al numero di oggetti
            $activesql = "SELECT configuration_value FROM ".TABLE_CONFIGURATION." WHERE configuration_key=\"MODULE_SHIPPING_ITEM_STATUS\""; 
            if($res = tep_db_query($activesql/*, DbConnection()*/ ) ){
               
               $row = tep_db_fetch_array($res);
               if ($row['configuration_value'] == "True"){
                  array_push($metodiattivi,"item");
               }
            }
            break;

         case "table.php" :      //Spese di spedizione in base al costo o in base al peso
            $activesql = "SELECT configuration_value FROM ".TABLE_CONFIGURATION." WHERE configuration_key=\"MODULE_SHIPPING_TABLE_STATUS\""; 
            if($res = tep_db_query($activesql/*, DbConnection()*/ ) ){
               
               $row = tep_db_fetch_array($res);
               if ($row['configuration_value'] == "True"){
                  array_push($metodiattivi,"table");
               }
            }
            break;

         case "zones.php" :      // Spese di spedizione in base alla destinazione e al peso
            $activesql = "SELECT configuration_value FROM ".TABLE_CONFIGURATION." WHERE configuration_key=\"MODULE_SHIPPING_ZONES_STATUS\""; 
            if($res = tep_db_query($activesql/*, DbConnection()*/ ) ){
               
               $row = tep_db_fetch_array($res);
               if ($row['configuration_value'] == "True"){
                  array_push($metodiattivi,"zones");
               }
            }
            break;
         default :         // Spese di spedizione calcolate con un modulo custom
            array_push($metodiattivi,"custom");
         }
      }
      return $metodiattivi;
   }

   /************** OTTIENE I PREZZI RELATIVI AI METODI DI SPEDIZIONE ATTIVI ******************************/
   function getshippingsdetails($shippings_methods){

      $shippingsdetails = array();

      foreach ($shippings_methods as $metodo){
         switch ($metodo){

         case "flat" :     //Spese di spedizione flat
            $costsql = "SELECT configuration_value FROM ".TABLE_CONFIGURATION." WHERE configuration_key=\"MODULE_SHIPPING_FLAT_COST\"";
            $handlingsql = "SELECT configuration_value FROM ".TABLE_CONFIGURATION." WHERE configuration_key=\"MODULE_SHIPPING_FLAT_HANDLING\"";
            
            if($res = tep_db_query($costsql/*, DbConnection()*/ ) ){
               
               $row = tep_db_fetch_array($res);
               $shippingdetails["flat"]["cost"] = $row['configuration_value'];
            }
            if($res = tep_db_query($handlingsql/*, DbConnection()*/ ) ){
               
               $row = tep_db_fetch_array($res);
               $shippingdetails["flat"]["handling"] = $row['configuration_value'];
            }
            break;

         case "item" :     // Spese di spedizione in base al numero di oggetti
            $costsql = "SELECT configuration_value FROM ".TABLE_CONFIGURATION." WHERE configuration_key=\"MODULE_SHIPPING_ITEM_COST\"";
            $handlingsql = "SELECT configuration_value FROM ".TABLE_CONFIGURATION." WHERE configuration_key=\"MODULE_SHIPPING_ITEM_HANDLING\"";
            
            if($res = tep_db_query($costsql/*, DbConnection()*/ ) ){
               //tep_db_close();
               $row = tep_db_fetch_array($res);
               $shippingdetails["item"]["cost"] = $row['configuration_value'];
            }
            
            if($res = tep_db_query($handlingsql/*, DbConnection()*/ ) ){
               
               $row = tep_db_fetch_array($res);
               $shippingdetails["item"]["handling"] = $row['configuration_value'];
            }
            break;

         case "table" :    // Spese di spedizione in base al prezzo o al peso
            $costsql = "SELECT configuration_value FROM ".TABLE_CONFIGURATION." WHERE configuration_key=\"MODULE_SHIPPING_TABLE_COST\"";
            $handlingsql = "SELECT configuration_value FROM ".TABLE_CONFIGURATION." WHERE configuration_key=\"MODULE_SHIPPING_TABLE_HANDLING\"";
            $tablemodesql = "SELECT configuration_value FROM ".TABLE_CONFIGURATION." WHERE configuration_key=\"MODULE_SHIPPING_TABLE_MODE\"";
            $boxweightsql = "SELECT configuration_value FROM ".TABLE_CONFIGURATION." WHERE configuration_key=\"SHIPPING_BOX_WEIGHT\"";
            
            if($res = tep_db_query($tablemodesql/*, DbConnection()*/ ) ){
               
               $row = tep_db_fetch_array($res);
               $shippingdetails["table"]["mode"] = $row['configuration_value'];
            }
            
            if($res = tep_db_query($costsql/*, DbConnection()*/ ) ){
               
               $row = tep_db_fetch_array($res);
               $shippingdetails["table"]["cost"] = $row['configuration_value'];
            }
            
            if($res = tep_db_query($handlingsql/*, DbConnection()*/ ) ){
               
               $row = tep_db_fetch_array($res);
               $shippingdetails["table"]["handling"] = $row['configuration_value'];
            }
            
            if ($shippingdetails["table"]["mode"] == "weight"){ // Se il metodo di calcolo per le spese di spedizione e' in base al peso ottengo il peso dell'imballo
               if($res = tep_db_query($boxweightsql/*, DbConnection()*/ ) ){
                  
                  $row = tep_db_fetch_array($res);
                  $shippingdetails["table"]["boxweight"] = $row['configuration_value'];
               }
            }
            break;
         
         case "zones" :    // Spese di spedizione in base alla zona e al peso
            $costsql = "SELECT configuration_value FROM ".TABLE_CONFIGURATION." WHERE configuration_key=\"MODULE_SHIPPING_ZONES_COST_1\"";
            $handlingsql = "SELECT configuration_value FROM ".TABLE_CONFIGURATION." WHERE configuration_key=\"MODULE_SHIPPING_ZONES_HANDLING_1\"";
            $boxweightsql = "SELECT configuration_value FROM ".TABLE_CONFIGURATION." WHERE configuration_key=\"SHIPPING_BOX_WEIGHT\"";
            
            if($res = tep_db_query($costsql/*, DbConnection()*/ ) ){
               
               $row = tep_db_fetch_array($res);
               $shippingdetails["zones"]["cost"] = $row['configuration_value'];
            }
            
            if($res = tep_db_query($handlingsql/*, DbConnection()*/ ) ){
               
               $row = tep_db_fetch_array($res);
               $shippingdetails["zones"]["handling"] = $row['configuration_value'];
            }
            
            if($res = tep_db_query($boxweightsql/*, DbConnection()*/ ) ){
               
               $row = tep_db_fetch_array($res);
               $shippingdetails["zones"]["boxweight"] = $row['configuration_value'];
            }
            break;
         case "free":      // Spedizione gratuita nel caso il prezzo superi una certa soglia
            $costosql = "SELECT configuration_VALUE FROM ".TABLE_CONFIGURATION." WHERE configuration_key=\"MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER\"";
            if ($res = tep_db_query($costosql/*,DbConnection()*/ ) )
            {
               $row = mysql_fetch_array($res);
               $shippingdetails["free"]["limit"] = $row['configuration_value'];
            }
            break;
         }
      }
      return $shippingdetails;
   }

   /******************* OTTIENE LE SPESE DI SPEDIZIONE PER IL PRODOTTO ****************************/
   function getshippingprice($product_weight='',$product_price='',$shippingmethods,$shippingdetails){
      $costo = array();       // Array contenente i costi calcolati in base ai metodi
      foreach ($shippingmethods as $metodo){
         switch ($metodo){
         case "flat" :     // Spese di spedizione "flat"
            array_push($costo,$shippingdetails["flat"]["cost"] + $shippingdetails["flat"]["handling"]);
            break;
            
         case "item" :     // Spese di spedizione in base al numero di oggetti
            array_push($costo,$shippingdetails["item"]["cost"] + $shippingdetails["item"]["handling"] );
            break;
         
         case "table" :    // Spese di spedizione calcolate in base al prezzo o al peso
            if ($shippingdetails["table"]["mode"] == "weight"){ // Spese di spedizione in base al peso
               $totale = $product_weight + $shippingdetails["table"]["boxweight"];
            }
            else{       // Spese di spedizione in base al prezzo
               $totale = $product_price;
            }
            
            $table_cost = split("[:,]" ,$shippingdetails["table"]["cost"]);
            $size = sizeof($table_cost);
            
            for ($i=0, $n=$size; $i<$n; $i+=2) { // Trova la classe di peso o di prezzo e calcola le spese
               if ($totale <= $table_cost[$i]) {
                  $spesa = $table_cost[$i+1];
                  break;
               }
            }
            array_push($costo,$spesa + $shippingdetails["table"]["handling"]);
            break;

         case "zones" :    // Spese di spedizione in base al peso e alla zona di spedizione, viene considerata la zona piu' vicina
            $totale = $product_weight + $shippingdetails["zones"]["boxweight"];
            $table_cost = split("[:,]" ,$shippingdetails["zones"]["cost"]);
            $size = sizeof($table_cost);
            for ($i=0, $n=$size; $i<$n; $i+=2) { // Trova la classe di peso e calcola il prezzo
               if ($totale <= $table_cost[$i]) {
                  $spesa = $table_cost[$i+1];
                  break;
               }
            }
            
            array_push($costo,$spesa +  $shippingdetails["zones"]["handling"]);
            break;
         case "free" :     // Spese di spedizione gratuite oltre ad un certo prezzo
            if ($product_price > $shippingdetails["free"]["limit"])
               array_push($costo,0);
            break;
         case "custom" :      // Modulo per il calcolo delle spese personalizzato
            array_push($costo,-1);
            break;
         }
      }
      sort($costo); // Ordino i costi ottenendo cosi' quello piu' basso 
      return ($costo[0]);
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
         pd.language_id = '$language_id' AND 
         cd.language_id = '$language_id'
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
