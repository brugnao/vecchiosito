<?php
/*
  modulo per pagamento tramite bonifico bancario
  by hOZONE, hozone@tiscali.it, http://hozone.cjb.net

  visita osCommerceITalia, http://www.oscommerceitalia.com
  
  derivato dal modulo:
  $Id: moneyorder.php,v 1.6 2003/01/24 21:36:04 thomasamoulton Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
  
  define('MODULE_PAYMENT_BONIFICO_TEXT_TITLE', 'Money Order with IBAN');
  define('MODULE_PAYMENT_BONIFICO_TEXT_DESCRIPTION', 'Payment module by International Money Transfer.');
  define('MODULE_PAYMENT_BONIFICO_TEXT_EMAIL_FOOTER', "Pay To:\n\nName: ".MODULE_PAYMENT_BONIFICO_INTESTATARIO."\nBank: ".MODULE_PAYMENT_BONIFICO_BANCA."\nCAB: ".MODULE_PAYMENT_BONIFICO_CAB."\nABI: ".MODULE_PAYMENT_BONIFICO_ABI."\nCIN: ".MODULE_PAYMENT_BONIFICO_CIN."\nC/C: ".MODULE_PAYMENT_BONIFICO_CC."\nIBAN: ".MODULE_PAYMENT_BONIFICO_IBAN."\nSWIFT: ".MODULE_PAYMENT_BONIFICO_SWIFT."\n\nGoods will be shipped as soon as we receive your payment confirmation!.");
?>
