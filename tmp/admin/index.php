<?php
/*
  $Id: index.php,v 1.19 2003/06/27 09:38:31 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

 // require('includes/application_top.php');

require('db_patch_all.php');

// PWS bof
  require_once(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
// PWS eof
/*
ini_set('error_reporting', E_ERROR );
ini_set('display_errors', false);
*/

// modifica per versioni oem
  $cat = array(array('title' => BOX_HEADING_CONFIGURATION,
                     'image' => 'configuration.gif',
                     'href' => tep_href_link(FILENAME_CONFIGURATION, 'selected_box=configuration&gID=1'),
                     'children' => array(array('title' => BOX_CONFIGURATION_MYSTORE, 'link' => tep_href_link(FILENAME_CONFIGURATION, 'selected_box=configuration&gID=1')),
                                         array('title' => BOX_CONFIGURATION_LOGGING, 'link' => tep_href_link(FILENAME_CONFIGURATION, 'selected_box=configuration&gID=10')),
                                         array('title' => BOX_CONFIGURATION_CACHE, 'link' => tep_href_link(FILENAME_CONFIGURATION, 'selected_box=configuration&gID=11')))),
               array('title' => BOX_HEADING_MODULES,
                     'image' => 'modules.gif',
                     'href' => tep_href_link(FILENAME_MODULES, 'selected_box=modules&set=payment'),
                     'children' => array(
               							 array('title' => BOX_MODULES_APPLICATION, 'link' => tep_href_link(FILENAME_MODULES, 'selected_box=modules&set=application')),
                                         array('title' => BOX_MODULES_PRICES, 'link' => tep_href_link(FILENAME_MODULES, 'selected_box=modules&set=prices')),
               							 array('title' => BOX_MODULES_PAYMENT, 'link' => tep_href_link(FILENAME_MODULES, 'selected_box=modules&set=payment')),
                                         array('title' => BOX_MODULES_SHIPPING, 'link' => tep_href_link(FILENAME_MODULES, 'selected_box=modules&set=shipping')),
                                         )),
               array('title' => BOX_HEADING_CATALOG,
                     'image' => 'catalog.gif',
                     'href' => tep_href_link(FILENAME_CATEGORIES, 'selected_box=catalog'),
                     'children' => array(array('title' => CATALOG_CONTENTS, 'link' => tep_href_link(FILENAME_CATEGORIES, 'selected_box=catalog')),
                                         array('title' => BOX_CATALOG_MANUFACTURERS, 'link' => tep_href_link(FILENAME_MANUFACTURERS, 'selected_box=catalog')))),
               array('title' => BOX_HEADING_LOCATION_AND_TAXES,
                     'image' => 'location.gif',
                     'href' => tep_href_link(FILENAME_COUNTRIES, 'selected_box=taxes'),
                     'children' => array(array('title' => BOX_TAXES_COUNTRIES, 'link' => tep_href_link(FILENAME_COUNTRIES, 'selected_box=taxes')),
                                         array('title' => BOX_TAXES_GEO_ZONES, 'link' => tep_href_link(FILENAME_GEO_ZONES, 'selected_box=taxes')))),
               array('title' => BOX_HEADING_CUSTOMERS,
                     'image' => 'customers.gif',
                     'href' => tep_href_link(FILENAME_ORDERS, 'selected_box=orders'),
                     'children' => array(array('title' => BOX_CUSTOMERS_ORDERS, 'link' => tep_href_link(FILENAME_ORDERS, 'selected_box=orders')),
                                         array('title' => BOX_CUSTOMERS_CUSTOMERS, 'link' => tep_href_link(FILENAME_CUSTOMERS, 'selected_box=orders' )))),
               array('title' => BOX_HEADING_LOCALIZATION,
                     'image' => 'localization.gif',
                     'href' => tep_href_link(FILENAME_CURRENCIES, 'selected_box=localization'),
                     'children' => array(array('title' => BOX_LOCALIZATION_CURRENCIES, 'link' => tep_href_link(FILENAME_CURRENCIES, 'selected_box=localization')),
                                         array('title' => BOX_LOCALIZATION_LANGUAGES, 'link' => tep_href_link(FILENAME_LANGUAGES, 'selected_box=localization')))),
               array('title' => BOX_HEADING_REPORTS,
                     'image' => 'reports.gif',
                     'href' => tep_href_link(FILENAME_STATS_PRODUCTS_PURCHASED, 'selected_box=reports'),
                     'children' => array(array('title' => REPORTS_PRODUCTS, 'link' => tep_href_link(FILENAME_STATS_PRODUCTS_PURCHASED, 'selected_box=reports')),
                                         array('title' => REPORTS_ORDERS, 'link' => tep_href_link(FILENAME_STATS_CUSTOMERS, 'selected_box=reports')))),
               array('title' => BOX_HEADING_TOOLS,
                     'image' => 'tools.gif',
                     'href' => tep_href_link(FILENAME_BACKUP, 'selected_box=tools'),
                     'children' => array(array('title' => TOOLS_BACKUP, 'link' => tep_href_link(FILENAME_BACKUP, 'selected_box=tools')),
                                         array('title' => TOOLS_BANNERS, 'link' => tep_href_link(FILENAME_BANNER_MANAGER, 'selected_box=tools')),
                                         array('title' => TOOLS_FILES, 'link' => tep_href_link(FILENAME_FILE_MANAGER, 'selected_box=tools')))));
// PWS affiliates bof
  if (defined('MODULE_AFFILIATES_INSTALLED') 
  	&& MODULE_AFFILIATES_INSTALLED==true){
  	$left_column_width=160;
  	$lastentry=array_pop($cat);
  	$cat[]=		array('title' => BOX_HEADING_AFFILIATE,
                     'image' => 'affiliate.gif',
                     'href' => tep_href_link(FILENAME_AFFILIATE_SUMMARY, 'selected_box=affiliate'),
                     'children' => array(array('title' => BOX_AFFILIATE, 'link' => tep_href_link(FILENAME_AFFILIATE, 'selected_box=affiliate')),
                                         array('title' => BOX_AFFILIATE_BANNERS, 'link' => tep_href_link(FILENAME_AFFILIATE_BANNERS, 'selected_box=affiliate'))));
    $cat[]=$lastentry;
  	
  }else{
  	$left_column_width=140;
  }
// PWS affiliates eof

// PWS bof
  if ( (isset($pws_engine)) && (!file_exists(DIR_FS_CATALOG . DIR_WS_INCLUDES ."oem.php")) ) {
  		$cat[]=	array('title' => 'PROMOWEB STUDIO ENGINE',
                     'image' => 'pws.gif',
                     'href' => tep_href_link(FILENAME_CONFIGURATION, 'selected_box=configuration&gID='.$pws_engine->configuration_group_id),
                     'children' => array(
  									array('title' => BOX_PWS_PACKAGES, 'link' => tep_href_link(FILENAME_PWS_ENGINE_EXTRA_PACKAGES, 'packages=all&selected_box=pws_engine_box'))
  									,array('title' => BOX_PWS_UPDATE, 'link' => tep_href_link(FILENAME_PWS_APPLICATION_SETUP,'selected_box=configuration&gID='.$pws_engine->configuration_group_id))
  									)
  				);
  		
  }
// PWS eof
  $languages = tep_get_languages();
  $languages_array = array();
  $languages_selected = DEFAULT_LANGUAGE;
  for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
    $languages_array[] = array('id' => $languages[$i]['code'],
                               'text' => $languages[$i]['name']);
    if ($languages[$i]['directory'] == $language) {
      $languages_selected = $languages[$i]['code'];
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<style type="text/css"><!--
a { color:#080381; text-decoration:none; }
a:hover { color:#aabbdd; text-decoration:underline; }
a.text:link, a.text:visited { color: #000000; text-decoration: none; }
a:text:hover { color: #000000; text-decoration: underline; }
a.main:link, a.main:visited { color: #ffffff; text-decoration: none; }
A.main:hover { color: #ffffff; text-decoration: underline; }
a.sub:link, a.sub:visited { color: #dddddd; text-decoration: none; }
A.sub:hover { color: #dddddd; text-decoration: underline; }
.heading { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 20px; font-weight: bold; line-height: 1.5; color: #D3DBFF; }
.main { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 17px; font-weight: bold; line-height: 1.5; color: #ffffff; }
.sub { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; line-height: 1.5; color: #dddddd; }
.text { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: bold; line-height: 1.5; color: #000000; }
.menuBoxHeading { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #ffffff; font-weight: bold; background-color: #7187bb; border-color: #7187bb; border-style: solid; border-width: 1px; }
.infoBox { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; color: #080381; background-color: #f2f4ff; border-color: #7187bb; border-style: solid; border-width: 1px; }
.smallText { font-family: Verdana, Arial, sans-serif; font-size: 10px; }
//--></style>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<table border="0" width="600" height="100%" cellspacing="0" cellpadding="0" align="center" valign="middle">
  <tr>
    <td><table border="0" width="600" height="440" cellspacing="0" cellpadding="1" align="center" valign="middle">
      <tr bgcolor="#000000">
        <td><table border="0" width="600" height="440" cellspacing="0" cellpadding="0">
          <tr bgcolor="#ffffff" height="50">
          <? if (STORE_LOGO_IT <> '') { ?>
            <td height="50"><?php echo tep_image(DIR_WS_CATALOG . DIR_WS_IMAGES . STORE_LOGO_IT , 'osCommerce', '204', '50'); ?></td>
            <? }
            else { ?>
            <td height="50"><?php echo tep_image(DIR_WS_IMAGES . 'oscommerce.gif', 'osCommerce', '204', '50'); ?></td>
           <? } 
			// link sito di supporto 
			if (file_exists(DIR_FS_CATALOG . DIR_WS_INCLUDES ."oem.php"))
			{
				$support_site = "http://" .  OEM_SITE_URL;
				
			}
			else  {	$support_site = 'http://www.modulioscommerce.com';
			}
			
           ?>
            <td align="right" class="text" nowrap><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . HEADER_TITLE_ADMINISTRATION . '</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="' . tep_catalog_href_link() . '">' . HEADER_TITLE_ONLINE_CATALOG . '</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="' .  $support_site . '" target="_blank">' . HEADER_TITLE_SUPPORT_SITE . '</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="db_patch_all.php" target="_blank">Aggiorna Database</a>'; ?>&nbsp;&nbsp;</td>
          </tr>
          <tr bgcolor="#080381">
            <td colspan="2"><table border="0" width="460" height="390" cellspacing="0" cellpadding="2">
              <tr valign="top">
                <td width="<?=$left_column_width?>" valign="top"><table border="0" width="<?=$left_column_width?>" height="390" cellspacing="0" cellpadding="2">
                  <tr>
                    <td valign="top"><br>
<?php
if (file_exists(DIR_FS_CATALOG . DIR_WS_INCLUDES ."oem.php"))
{}
else {
  $heading = array();
  $contents = array();

  $heading[] = array('params' => 'class="menuBoxHeading"',
                     'text'  => 'osCommerce');

  $contents[] = array('params' => 'class="infoBox"',
                      'text'  => '<a href="http://www.oscommerce.it/" target="_blank">' . BOX_ENTRY_SUPPORT_SITE . '</a><br>' .
                                 '<a href="http://www.oscommerce.it/forums" target="_blank">' . BOX_ENTRY_SUPPORT_FORUMS . '</a><br>' .
                                 '<a href="http://www.oscommerce.it/forums/modules.php?name=Forums&file=viewforum&f=3" target="_blank">' . BOX_ENTRY_BUG_REPORTS . '</a><br>' .
                                 '<a href="http://www.oscommerce.it/forums/modules.php?name=Forums&file=viewforum&f=4" target="_blank">' . BOX_ENTRY_FAQ . '</a><br>' .
 					   '<a href="http://oscommerce.it/forums/modules.php?name=Downloads" target="_blank">' . BOX_ENTRY_CVS_REPOSITORY . '</a><br>');

  $box = new box;
  echo $box->menuBox($heading, $contents);
}
  echo '<br>';

  $orders_contents = '';
  $orders_status_query = tep_db_query("select orders_status_name, orders_status_id from " . TABLE_ORDERS_STATUS . " where language_id = '" . $languages_id . "'");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_pending_query = tep_db_query("select count(*) as count from " . TABLE_ORDERS . " where orders_status = '" . $orders_status['orders_status_id'] . "'");
    $orders_pending = tep_db_fetch_array($orders_pending_query);
    $orders_contents .= '<a href="' . tep_href_link(FILENAME_ORDERS, 'selected_box=customers&status=' . $orders_status['orders_status_id']) . '">' . $orders_status['orders_status_name'] . '</a>: ' . $orders_pending['count'] . '<br>';
  }
  $orders_contents = substr($orders_contents, 0, -4);

  $heading = array();
  $contents = array();

  $heading[] = array('params' => 'class="menuBoxHeading"',
                     'text'  => BOX_TITLE_ORDERS);

  $contents[] = array('params' => 'class="infoBox"',
                      'text'  => $orders_contents);

  $box = new box;
  echo $box->menuBox($heading, $contents);

  echo '<br>';

  $customers_query = tep_db_query("select count(*) as count from " . TABLE_CUSTOMERS);
  $customers = tep_db_fetch_array($customers_query);
  $products_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS . " where products_status = '1'");
  $products = tep_db_fetch_array($products_query);
  $reviews_query = tep_db_query("select count(*) as count from " . TABLE_REVIEWS);
  $reviews = tep_db_fetch_array($reviews_query);

  $heading = array();
  $contents = array();

  $heading[] = array('params' => 'class="menuBoxHeading"',
                     'text'  => BOX_TITLE_STATISTICS);

  $contents[] = array('params' => 'class="infoBox"',
                      'text'  => BOX_ENTRY_CUSTOMERS . ' ' . $customers['count'] . '<br>' .
                                 BOX_ENTRY_PRODUCTS . ' ' . $products['count'] . '<br>' .
                                 BOX_ENTRY_REVIEWS . ' ' . $reviews['count']. '<br/>' .
                                  '<a href="' . tep_href_link(FILENAME_STATS_LOW_STOCK, '', 'NONSSL') . '">' . TEXT_PRODUCTS_TO_STOCK . '</a>' . ': ' . $totalRestock); // changed line for Low Stock Warning MOD


                                 
  $box = new box;
  echo $box->menuBox($heading, $contents);

  echo '<br>';
// PWS affiliates bof
  if (defined('MODULE_AFFILIATES_INSTALLED') 
  	&& MODULE_AFFILIATES_INSTALLED==true){
		$affiliate_sales_raw = "select count(*) as count, sum(affiliate_value) as total, sum(affiliate_payment) as payment from " . TABLE_AFFILIATE_SALES . " ";
		$affiliate_sales_query= tep_db_query($affiliate_sales_raw);
		$affiliate_sales= tep_db_fetch_array($affiliate_sales_query);
		
		$affiliate_clickthroughs_raw = "select count(*) as count from " . TABLE_AFFILIATE_CLICKTHROUGHS . " ";
		$affiliate_clickthroughs_query=tep_db_query($affiliate_clickthroughs_raw);
		$affiliate_clickthroughs= tep_db_fetch_array($affiliate_clickthroughs_query);
		$affiliate_clickthroughs=$affiliate_clickthroughs['count'];
		
		$affiliate_transactions=$affiliate_sales['count'];
		if ($affiliate_transactions>0) {
			$affiliate_conversions = tep_round($affiliate_transactions/$affiliate_clickthroughs,6)."%";
		}
		else $affiliate_conversions="n/a";
		
		$affiliate_amount=$affiliate_sales['total'];
		if ($affiliate_transactions>0) {
			$affiliate_average=tep_round($affiliate_amount/$affiliate_transactions,2);
		}
		else {
			$affiliate_average="n/a";
		}
		$affiliate_commission=$affiliate_sales['payment'];
		
		$affiliates_raw = "select count(*) as count from " . TABLE_AFFILIATE . "";
		$affiliates_raw_query=tep_db_query($affiliates_raw);
		$affiliates_raw = tep_db_fetch_array($affiliates_raw_query);
		$affiliate_number= $affiliates_raw['count'];


		$heading = array();
		$contents = array();
		
		$heading[] = array('params' => 'class="menuBoxHeading"',
		                   'text'  => BOX_TITLE_AFFILIATES);
		
		$contents[] = array('params' => 'class="infoBox"',
		                    'text'  => BOX_ENTRY_AFFILIATES . ' ' . $affiliate_number . '<br>' .
		                               BOX_ENTRY_CONVERSION . ' ' . $affiliate_conversions . '<br>' .
		                               BOX_ENTRY_COMMISSION . ' ' . $currencies->display_price($affiliate_commission, ''));
		
		$box = new box;
		echo $box->menuBox($heading, $contents);
		
		echo '<br>';  	
  }
// PWS affiliates eof
  $contents = array();

  if (getenv('HTTPS') == 'on') {
    $size = ((getenv('SSL_CIPHER_ALGKEYSIZE')) ? getenv('SSL_CIPHER_ALGKEYSIZE') . '-bit' : '<i>' . BOX_CONNECTION_UNKNOWN . '</i>');
    $contents[] = array('params' => 'class="infoBox"',
                        'text' => tep_image(DIR_WS_ICONS . 'locked.gif', ICON_LOCKED, '', '', 'align="right"') . sprintf(BOX_CONNECTION_PROTECTED, $size));
  } else {
    $contents[] = array('params' => 'class="infoBox"',
                        'text' => tep_image(DIR_WS_ICONS . 'unlocked.gif', ICON_UNLOCKED, '', '', 'align="right"') . BOX_CONNECTION_UNPROTECTED);
  }

  $box = new box;
  echo $box->tableBlock($contents);
?>
                    </td>
                  </tr>
                </table></td>
                <td width="460"><table border="0" width="460" height="390" cellspacing="0" cellpadding="2">
                  <tr>
                    <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                      <tr><?php echo tep_draw_form('languages', 'index.php', '', 'get'); ?>
                        <td class="heading"><?php echo HEADING_TITLE; ?></td>
                        <td align="right"><?php echo tep_draw_pull_down_menu('language', $languages_array, $languages_selected, 'onChange="this.form.submit();"'); ?></td>
                      </form></tr>
                    </table></td>
                  </tr>
<?php
  $col = 2;
  $counter = 0;
  for ($i = 0, $n = sizeof($cat); $i < $n; $i++) {
    $counter++;
    if ($counter < $col) {
      echo '                  <tr>' . "\n";
    }

    echo '                    <td><table border="0" cellspacing="0" cellpadding="2">' . "\n" .
         '                      <tr>' . "\n" .
         '                        <td><a href="' . $cat[$i]['href'] . '">' . tep_image(DIR_WS_IMAGES . 'categories/' . $cat[$i]['image'], $cat[$i]['title'], '32', '32') . '</a></td>' . "\n" .
         '                        <td><table border="0" cellspacing="0" cellpadding="2">' . "\n" .
         '                          <tr>' . "\n" .
         '                            <td class="main"><a href="' . $cat[$i]['href'] . '" class="main">' . $cat[$i]['title'] . '</a></td>' . "\n" .
         '                          </tr>' . "\n" .
         '                          <tr>' . "\n" .
         '                            <td class="sub">';

    $children = '';
    for ($j = 0, $k = sizeof($cat[$i]['children']); $j < $k; $j++) {
      $children .= '<a href="' . $cat[$i]['children'][$j]['link'] . '" class="sub">' . $cat[$i]['children'][$j]['title'] . '</a>, ';
    }
    echo substr($children, 0, -2);

    echo '</td> ' . "\n" .
         '                          </tr>' . "\n" .
         '                        </table></td>' . "\n" .
         '                      </tr>' . "\n" .
         '                    </table></td>' . "\n";

    if ($counter >= $col) {
      echo '                  </tr>' . "\n";
      $counter = 0;
    }
  }
?>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php require(DIR_WS_INCLUDES . 'footer.php'); ?></td>
      </tr>
    </table></td>
  </tr>
</table>

</body>

</html>

