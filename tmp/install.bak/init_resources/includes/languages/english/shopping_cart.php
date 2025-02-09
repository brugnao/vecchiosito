<?php
/*
  $Id: shopping_cart.php,v 1.13 2002/04/05 20:24:02 project3000 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Cart Contents');
define('HEADING_TITLE', 'What\'s In My Cart?');
define('TABLE_HEADING_REMOVE', 'Remove');
define('TABLE_HEADING_QUANTITY', 'Qty.');
define('TABLE_HEADING_MODEL', 'Model');
define('TABLE_HEADING_PRODUCTS', 'Product(s)');
define('TABLE_HEADING_TOTAL', 'Total');
define('TEXT_CART_EMPTY', 'Your Shopping Cart is empty!');
define('SUB_TITLE_SUB_TOTAL', 'Sub-Total:');
define('SUB_TITLE_TOTAL', 'Total:');

define('OUT_OF_STOCK_CANT_CHECKOUT', 'Products marked with ' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . ' dont exist in desired quantity in our stock.<br>Please alter the quantity of products marked with (' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '), Thank you');
define('OUT_OF_STOCK_CAN_CHECKOUT', 'Products marked with ' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . ' dont exist in desired quantity in our stock.<br>You can buy them anyway and check the quantity we have in stock for immediate deliver in the checkout process.');
// AVAILABILITY start
define('TEXT_AVAILABILITY_GREEN','Full availability');
define('TEXT_AVAILABILITY_YELLOW','Scarce availability');
define('TEXT_AVAILABILITY_RED','To be ordered');
define('TEXT_AVAILABILITY_SCHEDULED','Incoming');
// AVAILABILITY stop
//---PayPal WPP Modification START ---//
define('TEXT_PAYPALWPP_EC_HEADER', 'Fast, Secure Checkout with PayPal');
define('TEXT_PAYPALWPP_EC_BUTTON_TEXT', 'Save time. Checkout securely. Pay without sharing your financial information.');
define('MODULE_PAYMENT_PAYPAL_EC_SHORTCUT_OR', '- OR -');
define('MODULE_PAYMENT_PAYPAL_EC_BUTTON_IMG', 'https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif');

//---PayPal WPP Modification END---//
?>