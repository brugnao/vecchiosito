<?php
/*
  $Id: checkout_process.php,v 1.26 2002/11/01 04:22:05 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce 

  Released under the GNU General Public License 
*/

define('EMAIL_TEXT_SUBJECT', 'Verifica ordine');
define('EMAIL_TEXT_ORDER_NUMBER', 'Ordine Numero:');
define('EMAIL_TEXT_INVOICE_URL', 'Dettaglio ordine:');
define('EMAIL_TEXT_DATE_ORDERED', 'Data Ordine:');
define('EMAIL_TEXT_PRODUCTS', 'Prodotti');
define('EMAIL_TEXT_SUBTOTAL', 'Totale Imponibile:');
define('EMAIL_TEXT_TAX', 'Tasse:        ');
define('EMAIL_TEXT_SHIPPING', 'Spedizione: ');
define('EMAIL_TEXT_TOTAL', 'Totale:    ');
define('EMAIL_TEXT_DELIVERY_ADDRESS', 'Indirizzo per la consegna');
define('EMAIL_TEXT_BILLING_ADDRESS', 'Indirizzo di fatturazione');
define('EMAIL_TEXT_PAYMENT_METHOD', 'Metodo di pagamento');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('TEXT_EMAIL_VIA', 'via');
define('EMAIL_CUSTOMER_CODE', 'Codice Cliente: ');
define('EMAIL_ORDER_REFERENCE', 'Vs Riferimento Ordine: ');
define('EMAIL_ORDER_CONTACT', 'Ordine Effettuato da: ');
define('EMAIL_TEXT_PRODUCTS_MODEL', 'Codice Articolo');
define('EMAIL_TEXT_PRODUCTS_NAME', 'Articolo');
define('EMAIL_TEXT_PRODUCTS_QUANTITY', 'Quantità ');
define('EMAIL_TEXT_PRODUCTS_PRICE', 'Prezzo');
?>