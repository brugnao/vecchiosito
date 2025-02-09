<?php

define('API_USERNAME', MODULE_PAYMENT_PPEC_USERNAME);
define('API_PASSWORD', MODULE_PAYMENT_PPEC_PASSWORD);
define('API_SIGNATURE', MODULE_PAYMENT_PPEC_SIGNATURE);
define('ALERTMAIL', MODULE_PAYMENT_PPEC_MAIL);
define('PPEC_DEBUG',false);
define('CUSTOMER_MAIL',true);

$serverName = $_SERVER['SERVER_NAME'];
$serverPort = $_SERVER['SERVER_PORT'];
// set the type of request (secure or not)
$request_type = ($_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
$url=dirname($request_type.$serverName.':'.$serverPort.$_SERVER['REQUEST_URI']);

$PPEC_PATH_IPN = $url."/ppeb.php";
define('MODULE_PAYMENT_PPEC_IPN', $PPEC_PATH_IPN);
// print_r ($_SERVER);

$PPEC_PATH = $url."/ppec/ppec_cert/".MODULE_PAYMENT_PPEC_CERT_FILE;
// meglio inserire il certificato in una dir fuori da httpdocs
//$PPEC_PATH = "/var/www/vhosts/modulioscommerce.com/httpdocs/ppec/ppec_cert/".MODULE_PAYMENT_PPEC_CERT_FILE;
define('MODULE_PAYMENT_PPEC_CERT_PATH', $PPEC_PATH);
define('LOGO', MODULE_PAYMENT_PPEC_LOGO);

// set the type of request (secure or not)
$request_type = ($_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
define('CCURL', dirname($request_type.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$_SERVER['PHP_SELF']));

if(MODULE_PAYMENT_PPEC_TRANSACTION_SERVER == "PayPal Live Server") {

       if (!file_exists($PPEC_PATH))
       {	   
           define('API_ENDPOINT', 'https://api-3t.paypal.com/2.0/');   
	   }
	   else{
	          define('API_ENDPOINT', 'https://api.paypal.com/2.0/');         
	       }
		   
	   define('PAYPAL_URL', 'https://www.paypal.com/webscr?cmd=_express-checkout&token=');
       define('PAYPAL_URL_AUT', 'https://www.paypal.com/webscr?cmd=_accountauthenticate-login&token=');
}
else{        
       define('API_ENDPOINT', 'https://api.sandbox.paypal.com/2.0/');
       define('PAYPAL_URL', 'https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&token=');
       define('PAYPAL_URL_AUT', 'https://www.sandbox.paypal.com/webscr?cmd=_accountauthenticate-login&token=');
    }

  
define('USE_PROXY',FALSE);
define('PROXY_HOST', '');
define('PROXY_PORT', '');
define('VERSION', '3.0');


?>