<?php
//	require('includes/database_tables.php'); 	//include le costanti con i nomi delle tabelle del DB
//	include('includes/configure.php'); 			// include i dati per la connessione al DB
	require 'includes/application_top.php'; 

	$site_url = HTTP_SERVER; 				// <--- Inserire qui l'URL del sito SENZA SLASH FINALE es: "http://www.undominio.com/catalog"
	$language_id = "4";								// <--- Inserire qui l'ID della lingua utilizzata

	/*********************************** NON MODIFICARE ALTRO ***************************************/
	global $rescount;
	//$rescount=0;
	$listing_sql = "
	SELECT
	p.products_id,
	p.products_image,
	p.manufacturers_id, 
	p.products_price,
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
	IF(s.status, s.specials_new_products_price, p.products_price) AS final_price
	FROM
	".TABLE_PRODUCTS." p
	LEFT JOIN ".TABLE_SPECIALS." s ON p.products_id = s.products_id
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
	cd.language_id = '$language_id'
	ORDER BY final_price DESC
	";
	// echo $listing_sql;
	if($result = tep_db_query( $listing_sql/*, DbConnection()*/ ) ){
		
		$filestring = "";
		while($row=tep_db_fetch_array($result)){
			$descrizionehtml=$row["products_description"];	
			$descrizionehtml=strip_tags($descrizionehtml); 	
			$descrizionehtml=substr($descrizionehtml,0,4000);
			$descrizione1 = CleanHtml($descrizionehtml);
			$cat_arr = CatString($row["categories_id"]);
			$cat_arr = array_reverse($cat_arr);
			$cat_list = implode(";",$cat_arr);
			//$final_price = Tasse($row['tax_id'], $row['final_price']);
		 	//$final_price = round($pws_prices->getBestPrice($row['products_id']),2);
			$final_price = $pws_prices->calculatePrice($pws_prices->getBestPrice($row['products_id']),0,$row['products_id'],true);
         
			
	
			
			// $filestring.=$row["products_id"]."|".$row["products_name"]."|".$site_url."/images/".$row["products_image"]."|".$site_url."/product_info.php?products_id=".$row["products_id"]."|".$cat_list."|".$final_price."|".$descrizione1."|".$row["marca"]."<endrecord>\r\n";
			
						// merchant-category	offer-id			label					offer-url															prices			description			image-url									product-parameters	delivery-charge	  mfpn	 mfname	  			product-type	brand			delivery-period	ships-in	promotional-text	old-prices	condition	deal-type	
			$filestring.= $cat_list ."|". $row["products_id"]."|".$row["products_name"]."|".$site_url."/product_info.php?products_id=".$row["products_id"]."&from=PANGORA|".$final_price."|".$descrizione1."|".$site_url."/images/".$row["products_image"]."|".                   "|". 			"|".   "|".$row["marca"]."|PRODUCT|".		$row["marca"]."|D1|".			"|D1|".						"|".			"|NEW|FIXED_PRICE \r\n";
			$rescount += 1;// $rescount++;
		}	
		echo $filestring;
	}else{
		echo "Errore nell'esecuzione della query:<br>".$listing_sql."<br>".mysql_errno().": ".mysql_error()."<br>";
	}
	if($_GET['debug']=="debugme") {
		echo "<p><b>Num risultati query:</b>$rescount</p>";
	//echo "<pre>$listing_sql</pre>";
		$countres = TpDebug();
		echo "<p><b>Num prodotti attivi:</b>".$countres[0]."</p>";
		echo "<p><b>Num prodotti inattivi</b>".$countres[1]."</p>";
	}
	/******************************** CONNESSIONE AL DATABASE ***************************************/	
	/* Ritorna la risorsa di connessione $link														*/
	function DbConnection(){
		return;//
		$link = mysql_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD)					//
		or die(	"Errore nella connessione al database ".mysql_errno().": ".mysql_error()  );		//
		mysql_select_db(DB_DATABASE) 																//
		or die( "Errore nella selezione del database ".mysql_errno().": ".mysql_error() );			//
		return $link;																				//
	}																								//
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
   	
	/******************************* FORMATTAZIONE STRINGA DESCRIZIONE ******************************/
	function CleanHtml($string){
		$string = strip_tags($string);						  //elimina i tags html
		$string = substr($string,0,255);					  //taglia la stringa fino a max 255 caratteri		
		$search = array ("'<script[^>]*?>.*?</script>'si",	  // Rimozione del javascript
 						 "'<[\/\!]*?[^<>]*?>'si",   	      // Rimozione dei tag HTML
                  		 "'([\r\n])[\s]+'",     	          // Rimozione degli spazi bianchi
		      			 "'([\r])+'",  			              // Rimozione degli spazi bianchi
		      			 "'([\n])+'",                		  // Rimozione degli spazi bianchi
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
	mail ("info@oscommerce.it", "Import Pangora da ".HTTP_SERVER ,"");
?>