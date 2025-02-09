<?php

class ppesetup {

    var $txt, $ima;

    function ppesetup($language) {
      $this->txt = string;      
      $this->ima = string;   
      $this->query($language);     
    }

    function query($language) {
    
    if($language == "french")
	{
	   $im = "https://www.paypal.com/fr_FR/i/btn/btn_xpressCheckout.gif";
	   $tx = "Vous pouvez payer avec PayPal, sans avoir besoin de créer un compte manuellement";
	  
	}elseif($language == "german")
	{
	   $im = "https://www.paypal.com/de_DE/i/btn/btn_xpressCheckout.gif";
	   $tx = "Bezahlen mit PayPal ohne manuell ein Konto anlegen";
	   
	}elseif($language == "italian")
	{
	   $im = "https://www.paypal.com/it_IT/i/btn/btn_xpressCheckout.gif";
	   $tx = "Pagare con PayPal senza bisogno di creare manualmente un account";
	   
	}elseif($language == "spanish")
		{
	   $im = "https://www.paypal.com/es_ES/i/btn/btn_xpressCheckout.gif";
	   $tx = "Pagar con PayPal sin necesidad de crear manualmente una cuenta";
	   
	}elseif($language == "dutch")
	{
	   $im = "https://www.paypal.com/nl_NL/i/btn/btn_xpressCheckout.gif";
	   $tx = "Betalen met PayPal zonder handmatig Maak een account aan";
	   
	}elseif($language == "polish")
	{
	   $im = "https://www.paypal.com/pl_PL/i/btn/btn_xpressCheckout.gif";
	   $tx = "Wy mozecie placic z PayPal bez potrzebujacy recznie tworza rachunek";
	   
	}elseif($language == "japanese")
	{
	   $im = "https://www.paypal.com/ja_JP/i/btn/btn_xpressCheckout.gif";
	   $tx = "Pay with PayPal without needing to manually create an account";
	}else
	{
	   $im = "https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif";
	   $tx = "Pay with PayPal without needing to manually create an account";
	}	
	
	$serverName = $_SERVER['SERVER_NAME'];
	$serverPort = $_SERVER['SERVER_PORT'];
	$url=dirname('http://'.$serverName.':'.$serverPort.$_SERVER['REQUEST_URI']);
     
	  $this->txt = "<tr><td class='main' valign='top'>".$tx."<br></td></tr>";
	  
	  $this->ima = "<img align = 'center' border = '0' src='".$im."'/>";
	  
    }
}
?>