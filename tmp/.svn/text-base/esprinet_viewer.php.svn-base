<?php
// construct a header for our request
// bestdigit



$host = "www.bestdigit.it";


// $url = 'http://194.185.157.5/area_informativa/ricerca_schede/SchedeStdAjax.xml.asp';
  $url = $distLink;


function ob_file_callback($buffer)
{
  global $ob_file;
  $buffer = str_replace("<a href=\"#descrizione\" onClick=\"cambiaTab('tabsDett','Descrizione')\">Leggi la descrizione completa&nbsp;&gt;</a>", '', $buffer);
  $buffer = str_replace("<a href=\"#schedaEstesa\"><span>Scheda completa</span></a>", '', $buffer);
  $buffer = str_replace("<a href=\"#opzioni\"><span>Opzioni</span></a>", '', $buffer);
  $buffer = str_replace("<a name=\"schedaEstesa\"></a>", '', $buffer);
  $buffer = str_replace("<li id=\"tabSchedaEstesa\" 	class=\"spento\">	<a href=\"javascript:void(0);\" onClick=\"CompilaTecnica();cambiaTab('tabsDett','SchedaEstesa')\"><span>Scheda completa</span></a></li>", '', $buffer);
  $buffer = str_replace("<li id=\"tabOpzioni\" 		class=\"spento\">	<a href=\"javascript:void(0);\" onClick=\"cambiaTab('tabsDett','Opzioni')\"><span>Opzioni</span></a></li>", "<li id=\"tabOpzioni\" 		class=\"spento\">	<a href=\"javascript:void(0);\" onClick=\"cambiaTab('tabsDett','Opzioni')\"></a></li>", $buffer);
  
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
$fp = fopen( $distLink , 'r', false, $context);
if (!$fp)
die ("risorsa non trovata " . $distLink);
ob_start('ob_file_callback');
$ob_file = fopen("cache/".$products_id."_lang".$languages_id. ".html","w");
fpassthru($fp);   

ob_end_flush();
fclose($ob_file);
?>
