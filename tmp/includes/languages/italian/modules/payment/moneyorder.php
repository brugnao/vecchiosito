<?php
/*
  $Id: moneyorder.php,v 1.6 2003/01/24 21:36:04 thomasamoulton Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce 

  Released under the GNU General Public License 
*/
  define('MODULE_PAYMENT_MONEYORDER_PAYTO', 'Inserire qui gli estremi bancari per il pagamento');
  define('MODULE_PAYMENT_MONEYORDER_TEXT_TITLE', 'Bonifico Bancario Anticipato');
  define('MODULE_PAYMENT_MONEYORDER_TEXT_DESCRIPTION', 'Da pagare a:&nbsp;' . MODULE_PAYMENT_MONEYORDER_PAYTO . '<br><br>' . 'Il tuo ordine non verr&agrave; spedito finch&egrave; non riceveremo il pagamento.');
  define('MODULE_PAYMENT_MONEYORDER_TEXT_EMAIL_FOOTER', "Da pagare a:&nbsp;". MODULE_PAYMENT_MONEYORDER_PAYTO . "\n\n" . 'Il tuo ordine verr&agrave; spedito non appena riceveremo il pagamento. Clicca sul pulsante Conferma Ordine in basso per completare il tuo Ordine.');
?>