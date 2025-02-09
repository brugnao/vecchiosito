<?php
// construct a header for our request
// $host = "www.bestdigit.it";

// $url = 'http://194.185.157.5/area_informativa/ricerca_schede/SchedeStdAjax.xml.asp';

 
  

// funzione callback che intercetta la scheda non trovata su icecat
function ob_file_callback($buffer)
{
  global $ob_file, $the_product_info, $the_manufacturers ;
/*
  $buffer = str_replace("<a href=\"#descrizione\" onClick=\"cambiaTab('tabsDett','Descrizione')\">Leggi la descrizione completa&nbsp;&gt;</a>", '', $buffer);
  $buffer = str_replace("<a href=\"#schedaEstesa\"><span>Scheda completa</span></a>", '', $buffer);
  $buffer = str_replace("<a href=\"#opzioni\"><span>Opzioni</span></a>", '', $buffer);
  $buffer = str_replace("<a name=\"schedaEstesa\"></a>", '', $buffer);
  $buffer = str_replace("<li id=\"tabSchedaEstesa\" 	class=\"spento\">	<a href=\"javascript:void(0);\" onClick=\"CompilaTecnica();cambiaTab('tabsDett','SchedaEstesa')\"><span>Scheda completa</span></a></li>", '', $buffer);
  $buffer = str_replace("<li id=\"tabOpzioni\" 		class=\"spento\">	<a href=\"javascript:void(0);\" onClick=\"cambiaTab('tabsDett','Opzioni')\"><span>Opzioni</span></a></li>", "<li id=\"tabOpzioni\" 		class=\"spento\">	<a href=\"javascript:void(0);\" onClick=\"cambiaTab('tabsDett','Opzioni')\"></a></li>", $buffer);
  */
  $buffer = utf8_decode($buffer); // converte i caratteri utf in iso8859 
 // $buffer = str_replace('charset=utf-8', '', $buffer);

    fwrite($ob_file,$buffer);
}

// hack the referer!!!
$hdrs = array( 'http' => array(

    'method' => "GET",

    'header'=> "accept-language: en\r\n" .   "Referer: http://$host\r\n"   // Setting the http-referer

)

);

$context = stream_context_create($hdrs);
/*
 print $distLink;
 http://194.185.157.5/area_informativa/ricerca_schede/risultati_std.xml.asp?codice=LX.R9702.006
 exit;
 */
$fp = fopen( $icecatLink , 'r', false, $context);
if (!$fp)
die ("risorsa non trovata " . $icecatLink);
ob_start('ob_file_callback'); // scrive l'output sul buffer


$ob_file = fopen("cache/".$products_id."_lang".$languages_id. ".html","w"); // apre il file di cache

fpassthru($fp); // manda tutto al puntatore  

ob_end_flush(); // svuota il buffer
fclose($ob_file);
fclose($fp);


 $icecatLink = "cache/".$products_id."_lang".$languages_id. ".html";
 
 $buffer = file_get_contents($icecatLink);
 
 
 
 // se non trova nulla fa un secondo tentativo con il vpn
 if (strstr($buffer, 'reindirizzato') )
  {
  	// cerco con il vpn+vendor
  	unlink("cache/".$products_id."_lang".$languages_id. ".html");
  	$icecatLink = 'http://prf.icecat.biz/index.cgi?prod_id=' . urlencode($the_product_info['vpn']) . ';vendor=' . urlencode($the_manufacturers['manufacturers_name']) . ';shopname='. ICECAT_SHOPNAME . ';lang='.$lang_array['code'];
	 $fp = fopen( $icecatLink , 'r', false, $context);
	if (!$fp)
	die ("risorsa non trovata " . $icecatLink);
	ob_start('ob_file_callback'); // scrive l'output sul buffer
	$ob_file = fopen("cache/".$products_id."_lang".$languages_id. ".html","w"); // apre il file di cache
	fpassthru($fp); // manda tutto al puntatore  

	ob_end_flush(); // svuota il buffer
	fclose($ob_file);
	fclose($fp);

  }
  
  $icecatLink = "cache/".$products_id."_lang".$languages_id. ".html";
  
 $buffer = file_get_contents($icecatLink);

   
 // se la scheda è stata scaricata prova rintracciare l'immagine principale di icecat
 $start_point = strpos($buffer, "javascript:openimage('") + 22;
 // $link_string = substr($buffer, $start_point, 70);
 $end_point = strpos($buffer, '.jpg', $start_point) + 4 ; // cerca .jpg a partire dall'offset start_point
 $lenght_link = $end_point - $start_point; 
 $img_link = substr($buffer, $start_point, $lenght_link);
 // print $img_link;
 // print "lunghezza" . $lenght_link;
 // exit;
 $old_image_query = tep_db_query('select products_image from products where products_id = ' . $products_id . '' );
 $old_image_array = tep_db_fetch_array($old_image_query);
 
 
 if ( ($lenght_link >= '30')  ) // se ha trovato un link valido aggiorna l'immagine nel DB
	 {
	 
	 	
	 	$query = "update products set products_image = '" .  $img_link . "' where products_id = '" . $products_id . "'";

	 	if ( !tep_not_null($old_image_array['products_image'] = '') )
	 	{
	 		tep_db_query($query) ;
	 
	 		
	 	}
		elseif (ICECAT_IMAGE == 'True') // Sostituisce sempre l'immagine con quella di icecat
		{
	 		tep_db_query($query) ;
	 	
		}
		
	 }

 
 // se ancora non trova nulla mette la descrizione del prodotto da db ed invia mail
 if (strstr($buffer, 'reindirizzato') )
  {
  		unlink("cache/".$products_id."_lang".$languages_id. ".html");
  		$ob_file = fopen("cache/".$products_id."_lang".$languages_id. ".html","w"); // apre il file di cache
  	// sostituisce il buffer con la descrizione prodotto ed invia un'email all'admin
  	$buffer_desc =  $the_product_info['products_description']; 
  	$body = 'Informazioni aggiuntive
  	VPN = ' . $the_product_info['vpn'] . '
  	Vendor = ' . $the_manufacturers['manufacturers_name'] . '';
  	 tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, 'Scheda IceCat non trovata EAN ' . $the_product_info['EAN'], $body, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
	  fwrite($ob_file,$buffer_desc);
	  fclose($ob_file);
  }
 ?>