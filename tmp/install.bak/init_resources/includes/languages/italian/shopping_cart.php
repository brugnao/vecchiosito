<?php
/*
  $Id: shopping_cart.php,v 1.13 2002/04/05 20:24:02 project3000 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce 

  Released under the GNU General Public License 
*/

define('NAVBAR_TITLE', 'Contenuto carrello');
define('HEADING_TITLE', 'Cosa c\'&egrave; nel mio carrello?');
define('TABLE_HEADING_REMOVE', 'Cancella');
define('TABLE_HEADING_QUANTITY', 'Quantit&agrave;');
define('TABLE_HEADING_MODEL', 'Modello');
define('TABLE_HEADING_PRODUCTS', 'Articolo');
define('TABLE_HEADING_TOTAL', 'Totale');
define('TEXT_CART_EMPTY', 'Il tuo carrello &egrave; vuoto!');
define('SUB_TITLE_SUB_TOTAL', 'Totale Imponibile:');
define('SUB_TITLE_TOTAL', 'Totale:');

define('OUT_OF_STOCK_CANT_CHECKOUT', 'I prodotti contrassegnati con ' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . ' non sono presenti nel nostro magazzino nella quantit&agrave; desiderata.<br>Cambia la quantit&agrave; del prodotto contrassegnato con (' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '), Grazie');
define('OUT_OF_STOCK_CAN_CHECKOUT', 'I prodotti contrassegnati con ' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . ' non sono presenti nel nostro magazzino nella quantit&agrave; desiderata.<br>Puoi acquistare questo prodotto in ogni momento controllando la disponibilit&agrave; per l\'immediata spedizione nel procedimento di acquisto.');
define('OUT_OF_STOCK_QUANTITY_MAX', 'Quantit&agrave; massima disp.: %s');

define('TEXT_PRODUCT_QUANTITY', 'Quantit&agrave; Disponibile: ');
// AVAILABILITY start
define('TEXT_AVAILABILITY_GREEN','Disponibilit&agrave;: piena');
define('TEXT_AVAILABILITY_YELLOW','Disponibilit&agrave;: scarsa');
define('TEXT_AVAILABILITY_RED','Disponibilit&agrave;: su ordinazione');
define('TEXT_AVAILABILITY_SCHEDULED','Disponibilit&agrave;: in arrivo');
// AVAILABILITY stop
//---PayPal WPP Modification START ---//
define('TEXT_PAYPALWPP_EC_HEADER', 'Checkout sicuro e veloce, con PayPal');
define('TEXT_PAYPALWPP_EC_BUTTON_TEXT', 'Risparmia tempo. Esegui il checkout in sicurezza. Paga senza rivelare le tue informazioni personali.');
define('MODULE_PAYMENT_PAYPAL_EC_SHORTCUT_OR', '- Oppure -');
define('MODULE_PAYMENT_PAYPAL_EC_BUTTON_IMG', 'https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif');

//---PayPal WPP Modification END---//
?>
