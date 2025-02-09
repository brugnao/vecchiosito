<?php
/*
  $Id: login.php,v 1.14 2003/06/09 22:46:46 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce 

  Released under the GNU General Public License 
*/

define('NAVBAR_TITLE', 'Login');
define('HEADING_TITLE', 'Benvenuto, Accedi');

define('HEADING_NEW_CUSTOMER', 'Nuovo Cliente');
define('TEXT_NEW_CUSTOMER', 'Sono un nuovo cliente.');
define('TEXT_NEW_CUSTOMER_INTRODUCTION', 'Creando un nuovo account da ' . STORE_NAME . ' sarai in grado di acquistare velocemente, essere sempre aggiornato sullo stato dei tuoi ordini, e rivedere la cronologia degli ordini che hai effettuato.');

define('HEADING_RETURNING_CUSTOMER', 'Vecchio Cliente');
define('TEXT_RETURNING_CUSTOMER', 'Sono gi&agrave; stato vostro cliente.');

define('TEXT_PASSWORD_FORGOTTEN', 'Dimenticato la password? Clicca qui.');

define('TEXT_LOGIN_ERROR', 'Errore: Nessun indirizzo E-Mail e/o password corrispondenti a quelli inseriti.');
define('TEXT_VISITORS_CART', '<font color="#ff0000"><b>Note:</b></font> Il contenuto del suo &quot;Carrello ospiti&quot; sar&agrave; inserito nel suo &quot;Carrello membri&quot; appena acceder&agrave; tramite il suo account. <a href="javascript:session_win();">[Ulteriori Informazioni Qui]</a>');
// BOF Separate Pricing Per Customer
// define the email address that can change customer_group_id on login
define('SPPC_TOGGLE_LOGIN_PASSWORD', 'root@oscommerce.it');
// EOF Separate Pricing Per Customer


//---PayPal WPP Modification START ---//
define('TEXT_PAYPALWPP_EC_HEADER', 'Checkout sicuro e veloce, con PayPal');
define('TEXT_PAYPALWPP_EC_BUTTON_TEXT', 'Risparmia tempo. Esegui il checkout in sicurezza. Paga senza rivelare le tue informazioni personali.');
define('TEXT_PAYPALWPP_EC_BUTTON_TEXT2', 'Risparmia tempo. Esegui il checkout in sicurezza. Paga senza rivelare le tue informazioni personali.');
define('MODULE_PAYMENT_PAYPAL_EC_BUTTON_IMG', 'https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif');
//---PayPal WPP Modification END---//

?>
