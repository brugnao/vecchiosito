<?php
/*
  $Id: column_left.php,v 1.15 2003/07/01 14:34:54 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  // STS: ADD
  $sts_block_name = 'header2columnleft';
  require(STS_RESTART_CAPTURE);
  // STS: EOADD

  if ((USE_CACHE == 'true') && empty($SID) && !$pws_engine->isInstalledPlugin('pws_dropdownmenu','application')) {
    echo tep_cache_categories_box();
  } else {
    include(DIR_WS_BOXES . 'categories.php');
  }

  // STS: ADD
  $sts_block_name = 'categorybox';
  require(STS_RESTART_CAPTURE);
  // STS: EOADD

  if ((USE_CACHE == 'true') && empty($SID)) {
    echo tep_cache_manufacturers_box();
  } else {
    include(DIR_WS_BOXES . 'manufacturers.php');
  }

  // STS: ADD
  $sts_block_name = 'manufacturerbox';
  require(STS_RESTART_CAPTURE);
  // STS: EOADD

  if (file_exists(DIR_WS_BOXES . 'affiliate.php')){
	require(DIR_WS_BOXES . 'affiliate.php');
	  // STS: ADD
	  $sts_block_name = 'affiliatesbox';
	  require(STS_RESTART_CAPTURE);
	  // STS: EOADD
  }
  require(DIR_WS_BOXES . 'whats_new.php');

  // STS: ADD
  $sts_block_name = 'whatsnewbox';
  require(STS_RESTART_CAPTURE);
  // STS: EOADD

  require(DIR_WS_BOXES . 'search.php');

  // STS: ADD
  $sts_block_name = 'searchbox';
  require(STS_RESTART_CAPTURE);
  // STS: EOADD

  require(DIR_WS_BOXES . 'info_pages.php');

  // STS: ADD
  $sts_block_name = 'informationbox';
  require(STS_RESTART_CAPTURE);
  // STS: EOADD

  require(DIR_WS_BOXES . 'shopping_cart.php');

  // STS: ADD
  $sts_block_name = 'cartbox';
  require(STS_RESTART_CAPTURE);
  // STS: EOADD

  if (isset($HTTP_GET_VARS['products_id'])) include(DIR_WS_BOXES . 'manufacturer_info.php');

  // STS: ADD
  $sts_block_name = 'maninfobox';
  require(STS_RESTART_CAPTURE);
  // STS: EOADD

  if (tep_session_is_registered('customer_id')) include(DIR_WS_BOXES . 'order_history.php');

  // STS: ADD
  $sts_block_name = 'orderhistorybox';
  require(STS_RESTART_CAPTURE);
  // STS: EOADD

  if (isset($HTTP_GET_VARS['products_id'])) {
    if (tep_session_is_registered('customer_id')) {
      $check_query = tep_db_query("select count(*) as count from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$customer_id . "' and global_product_notifications = '1'");
      $check = tep_db_fetch_array($check_query);
      if ($check['count'] > 0) {
  		include(DIR_WS_BOXES . 'best_sellers.php');
     
      } else {
        include(DIR_WS_BOXES . 'product_notifications.php');
      }
    } else {
      include(DIR_WS_BOXES . 'product_notifications.php');
    }
  } else {
      include(DIR_WS_BOXES . 'best_sellers.php');
     
  }

  // STS: ADD
  $sts_block_name = 'bestsellersbox';
  require(STS_RESTART_CAPTURE);
  // STS: EOADD

  // la query è piuttosto pesante per cui carichiamo il php solo se esiste la skin
 if (file_exists( DIR_FS_STS_SKINS. 'boxes/accessories.htm')){
 	
	require(DIR_WS_BOXES . 'accessories.php');
	  // STS: ADD
	  $sts_block_name = 'accessoriesbox';
	  require(STS_RESTART_CAPTURE);
	  // STS: EOADD
  }
  
  if (file_exists( DIR_FS_STS_SKINS. 'boxes/specialsscroll.htm')){
  // STS: ADD
  require(DIR_WS_BOXES . 'specialsscroll.php');
  $sts_block_name = 'specialsscroll';
  require(STS_RESTART_CAPTURE);
  // STS: EOADD 
  }
  
  if (file_exists( DIR_FS_STS_SKINS. 'boxes/whats_new_scroll.htm')){
  // STS: ADD
  require(DIR_WS_BOXES . 'whats_new_scroll.php');
  $sts_block_name = 'whats_new_scroll';
  require(STS_RESTART_CAPTURE);
  // STS: EOADD 
  }
  
  if (isset($HTTP_GET_VARS['products_id'])) {
    if (basename($PHP_SELF) != FILENAME_TELL_A_FRIEND) include(DIR_WS_BOXES . 'tell_a_friend.php');
  } else {
    include(DIR_WS_BOXES . 'specials.php');
  }

  // STS: ADD
  $sts_block_name = 'specialfriendbox';
  require(STS_RESTART_CAPTURE);
  // STS: EOADD

  if (substr(basename($PHP_SELF), 0, 8) != 'checkout') {
    include(DIR_WS_BOXES . 'languages.php');

    // STS: ADD
    $sts_block_name = 'languagebox';
    require(STS_RESTART_CAPTURE);
    // STS: EOADD

    include(DIR_WS_BOXES . 'currencies.php');

    // STS: ADD
    $sts_block_name = 'currenciesbox';
    require(STS_RESTART_CAPTURE);
    // STS: EOADD

  }

?>
