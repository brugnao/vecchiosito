<?php
/*
  $Id: database_tables.php,v 1.1 2003/03/14 02:10:58 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// define the database table names used in the project
  define('TABLE_ADDRESS_BOOK', 'address_book');
  define('TABLE_ADDRESS_FORMAT', 'address_format');
  define('TABLE_ADMINISTRATORS', 'administrators');
  define('TABLE_BANNERS', 'banners');
  define('TABLE_BANNERS_HISTORY', 'banners_history');
  define('TABLE_CATEGORIES', 'categories');
  define('TABLE_CATEGORIES_DESCRIPTION', 'categories_description');
  define('TABLE_CONFIGURATION', 'configuration');
  define('TABLE_CONFIGURATION_GROUP', 'configuration_group');
  define('TABLE_COUNTER', 'counter');
  define('TABLE_COUNTER_HISTORY', 'counter_history');
  define('TABLE_COUNTRIES', 'countries');
  define('TABLE_CURRENCIES', 'currencies');
  define('TABLE_CUSTOMERS', 'customers');
  define('TABLE_CUSTOMERS_BASKET', 'customers_basket');
  define('TABLE_CUSTOMERS_BASKET_ATTRIBUTES', 'customers_basket_attributes');
  define('TABLE_CUSTOMERS_INFO', 'customers_info');
  define('TABLE_LANGUAGES', 'languages');
  define('TABLE_MANUFACTURERS', 'manufacturers');
  define('TABLE_MANUFACTURERS_INFO', 'manufacturers_info');
  define('TABLE_ORDERS', 'orders');
  define('TABLE_ORDERS_PRODUCTS', 'orders_products');
  define('TABLE_ORDERS_PRODUCTS_ATTRIBUTES', 'orders_products_attributes');
  define('TABLE_ORDERS_PRODUCTS_DOWNLOAD', 'orders_products_download');
  define('TABLE_ORDERS_STATUS', 'orders_status');
  define('TABLE_ORDERS_STATUS_HISTORY', 'orders_status_history');
  define('TABLE_ORDERS_TOTAL', 'orders_total');
  define('TABLE_PRODUCTS', 'products');
  define('TABLE_PRODUCTS_ATTRIBUTES', 'products_attributes');
  define('TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD', 'products_attributes_download');
  define('TABLE_PRODUCTS_DESCRIPTION', 'products_description');
  define('TABLE_PRODUCTS_NOTIFICATIONS', 'products_notifications');
  define('TABLE_PRODUCTS_OPTIONS', 'products_options');
  define('TABLE_PRODUCTS_OPTIONS_VALUES', 'products_options_values');
  define('TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS', 'products_options_values_to_products_options');
  define('TABLE_PRODUCTS_TO_CATEGORIES', 'products_to_categories');
  define('TABLE_REVIEWS', 'reviews');
  define('TABLE_REVIEWS_DESCRIPTION', 'reviews_description');
  define('TABLE_SESSIONS', 'sessions');
  define('TABLE_SPECIALS', 'specials');
  define('TABLE_TAX_CLASS', 'tax_class');
  define('TABLE_TAX_RATES', 'tax_rates');
  define('TABLE_GEO_ZONES', 'geo_zones');
  define('TABLE_ZONES_TO_GEO_ZONES', 'zones_to_geo_zones');
  define('TABLE_WHOS_ONLINE', 'whos_online');
  define('TABLE_ZONES', 'zones');
define('TABLE_PAGES', 'pages');
define('TABLE_PAGES_DESCRIPTION', 'pages_description');
//++++ QT Pro: Begin Changed code
  define('TABLE_PRODUCTS_STOCK', 'products_stock');
//++++ QT Pro: End Changed Code
  //kgt - discount coupons
  define('TABLE_DISCOUNT_COUPONS', 'discount_coupons');
  define('TABLE_DISCOUNT_COUPONS_TO_ORDERS', 'discount_coupons_to_orders');
  define('TABLE_DISCOUNT_COUPONS_TO_CATEGORIES', 'discount_coupons_to_categories');
  define('TABLE_DISCOUNT_COUPONS_TO_PRODUCTS', 'discount_coupons_to_products');
  define('TABLE_DISCOUNT_COUPONS_TO_MANUFACTURERS', 'discount_coupons_to_manufacturers');
  define('TABLE_DISCOUNT_COUPONS_TO_CUSTOMERS', 'discount_coupons_to_customers');
  define('TABLE_DISCOUNT_COUPONS_TO_ZONES', 'discount_coupons_to_zones');
  define('TABLE_DISCOUNT_COUPONS_TO_CUSTOMER_GROUPS', 'discount_coupons_to_customer_groups');
  //end kgt - discount coupons
    // START: Product Extra Fields
    define('TABLE_PRODUCTS_EXTRA_FIELDS', 'products_extra_fields');
    define('TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS', 'products_to_products_extra_fields');
    // END: Product Extra Fields
?>
