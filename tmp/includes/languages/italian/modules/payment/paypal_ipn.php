<?php
/*
  $Id: paypal_ipn.php,v 2.1.0.0 13/01/2007 16:30:37 Edith Karnitsch Exp $

  Copyright (c) 2004 osCommerce
  Released under the GNU General Public License
  
  Original Authors: Harald Ponce de Leon, Mark Evans 
  Updates by PandA.nl, Navyhost, Zoeticlight, David, gravyface, AlexStudio, windfjf and Terra
    
*/

  define('MODULE_PAYMENT_PAYPAL_IPN_TEXT_TITLE', 'PayPal');
  define('MODULE_PAYMENT_PAYPAL_IPN_TEXT_DESCRIPTION', 'PayPal');

  // Sets the text for the "continue" button on the PayPal Payment Complete Page
  // Maximum of 60 characters!  
  define('CONFIRMATION_BUTTON_TEXT', 'Conferma Ordine');
  
define('EMAIL_PAYPAL_PENDING_NOTICE', 'Il tuo pagamento &egrave; al momento in fase di registrazione. Ti invieremo un\'email con la copia dell\'ordine effettuato non appena completato.');
  
define('EMAIL_TEXT_SUBJECT', 'Lavorazione Ordine');
define('EMAIL_TEXT_ORDER_NUMBER', 'Ordine Numero:');
define('EMAIL_TEXT_INVOICE_URL', 'Dettaglio:');
define('EMAIL_TEXT_DATE_ORDERED', 'Data Ordine:');
define('EMAIL_TEXT_PRODUCTS', 'Prodotti');
define('EMAIL_TEXT_SUBTOTAL', 'Subtotale:');
define('EMAIL_TEXT_TAX', 'Tasse:        ');
define('EMAIL_TEXT_SHIPPING', 'Spedizione: ');
define('EMAIL_TEXT_TOTAL', 'Totale:    ');
define('EMAIL_TEXT_DELIVERY_ADDRESS', 'Indirizzo di spedizione');
define('EMAIL_TEXT_BILLING_ADDRESS', 'Indirizzo di fatturazione');
define('EMAIL_TEXT_PAYMENT_METHOD', 'Metodo di pagamento');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('TEXT_EMAIL_VIA', 'via'); 

define('PAYPAL_ADDRESS', 'Indirizzo PayPal del cliente');

//define('PAYPAL_IPN_NEW_TEXT', 'Pagamento via carta bancaria');
define('PAYPAL_IPN_NEW_TEXT', 'PayPal');

/* If you want to include a message with the order email, enter text here: */
/* Use \n for line breaks */
define('MODULE_PAYMENT_PAYPAL_IPN_TEXT_EMAIL_FOOTER', 'La ringraziamo per aver scelto il nostro negozio on line!');
  
  
?>
