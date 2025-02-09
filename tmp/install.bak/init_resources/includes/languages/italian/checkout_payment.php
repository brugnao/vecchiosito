<?php
/*
  $Id: checkout_payment.php,v 1.14 2003/02/06 17:38:16 thomasamoulton Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce 

  Released under the GNU General Public License 
*/

define('NAVBAR_TITLE_1', 'Acquista');
define('NAVBAR_TITLE_2', 'Metodo di pagamento');

define('HEADING_TITLE', 'Informazioni pagamento');

define('TABLE_HEADING_BILLING_ADDRESS', 'Indirizzo per la fattura');
define('TEXT_SELECTED_BILLING_DESTINATION', 'Scegli dalla tua rubrica dove vuoi che sia spedita la fattura.');
define('TITLE_BILLING_ADDRESS', 'Indirizzo per la fattura:');

define('TABLE_HEADING_PAYMENT_METHOD', 'Metodo di pagamento');
define('TEXT_SELECT_PAYMENT_METHOD', 'Seleziona il metodo di pagamento che preferisci.');
define('TITLE_PLEASE_SELECT', 'Seleziona');
define('TEXT_ENTER_PAYMENT_INFORMATION', 'Questo &egrave; il metodo di pagamento che hai scelto per questo ordine.');

define('TABLE_HEADING_COMMENTS', 'Aggiungi commenti o richieste particolari riguardo il tuo ordine');

define('TITLE_CONTINUE_CHECKOUT_PROCEDURE', 'Continua la procedura di acquisto');
define('TEXT_CONTINUE_CHECKOUT_PROCEDURE', 'per confemare quest\' ordine.');
//kgt - discount coupons
define('TABLE_HEADING_COUPON', 'Hai ricevuto un codice promozionale  ?' );
define('ENTRY_DISCOUNT_COUPON', 'Inserisci qui il codice: ');
//end kgt - discount coupons
//---PayPal WPP Modification START ---//
define('TEXT_PAYPALWPP_EC_BUTTON_TEXT', 'Risparmia tempo. Esegui il checkout in sicurezza. Paga senza rivelare le tue informazioni personali.');
//---PayPal WPP Modification END---//
// modifica Pure B2B
define('TABLE_HEADING_ORDER_CONTACT', 'Ordine effetuato da: ');
define('TABLE_HEADING_ORDER_REFERENCE', 'VS Riferimento Ordine: ');
?>