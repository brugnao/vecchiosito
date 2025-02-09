<?php
/*
 * @filename:	specials.php
 * @version:	1.00
 * @project:	PWS
 *
 * @author:		Riccardo Roscilli <info@oscommerce.it>
 * @created:	31/mag/07
 * @modified:	31/mag/07 16:26:33
 *
 * @copyright:	2006-2007	Riccardo Roscilli @ PWS
 *
 * @desc:	Gestione delle offerte speciali, utilizzando il plugin pws_prices_specials
 *
 * @TODO:		
 */
/*
  $Id: specials.php,v 1.41 2003/06/29 22:50:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  $pws_specials=$pws_engine->getPlugin('pws_prices_specials','prices');

  if (isset($_REQUEST['products_id']) && !isset($_REQUEST['pws_specials_id'])){
  	$specials_query=tep_db_query("select pws_specials_id from ".TABLE_PWS_SPECIALS." where products_id=".$_REQUEST['products_id']);
	$pws_specials_id=$_REQUEST['pws_specials_id']=$specials=tep_db_fetch_array($specials_query) ? $specials['pws_specials_id']:NULL;
  }
  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'setflag':
        $pws_specials->setSpecialsStatus($HTTP_GET_VARS['products_id'], $HTTP_GET_VARS['flag']);
        tep_redirect(tep_href_link(FILENAME_SPECIALS, (isset($HTTP_GET_VARS['page']) ? 'page=' . $HTTP_GET_VARS['page'] . '&' : '') . 'products_id=' . $HTTP_GET_VARS['products_id'], 'NONSSL'));
        break;
/*      case 'insert':
        $products_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
        $pws_specials->adminUpdateProduct($_REQUEST['products_id']);
        tep_redirect(tep_href_link(FILENAME_SPECIALS, 'page=' . $HTTP_GET_VARS['page']));
        break;
*/    case 'update':
        $pws_specials->adminUpdateProduct($_REQUEST['products_id']);
        $pws_specials->setSpecialsStatus($_REQUEST['products_id'], isset($_REQUEST['pws_specials_status'])?'1':'0');
        $pws_specials->adminUpdateProduct($_REQUEST['products_id']);
        tep_redirect(tep_href_link(FILENAME_SPECIALS, 'page=' . $HTTP_GET_VARS['page'] . '&pws_specials_id=' . $pws_specials_id));
        break;
      case 'deleteconfirm':
        $pws_specials->adminUpdateProduct($_REQUEST['pws_specials_id']);
        tep_redirect(tep_href_link(FILENAME_SPECIALS, 'page=' . $HTTP_GET_VARS['page']));
        break;
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<!-- <link rel="stylesheet" type="text/css" href="includes/javascript/calendar.css"> -->
<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<script language="javascript" src="includes/general.js"></script>
<?php
  if ( ($action == 'new') || ($action == 'edit') ) {
    $form_action = 'insert';
    if ( ($action == 'edit') && isset($HTTP_GET_VARS['pws_specials_id']) ) {
      $form_action = 'update';
	$product_query=tep_db_query("select products_id from ".TABLE_PWS_SPECIALS." where pws_specials_id=".$_REQUEST['pws_specials_id']);
    $product_query=tep_db_fetch_array($product_query);
    $product_query = tep_db_query("select p.products_id, pd.products_name, p.products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id=".$product_query['products_id']);

 	$product = tep_db_fetch_array($product_query);

      $sInfo = new objectInfo($product);
      $pws_specials->adminLoadProduct(&$sInfo);
    } /*else {
      $sInfo = new objectInfo(array());
      $pws_specials->adminNewProduct(&$sInfo);

// create an array of products on special, which will be excluded from the pull down menu of products
// (when creating a new product on special)
      $specials_array = array();
      $specials_query = tep_db_query("select products_id from " . TABLE_PWS_SPECIALS);
      while ($specials = tep_db_fetch_array($specials_query)) {
        $specials_array[] = $specials['products_id'];
      }
    }*/
    
?>
<script language="JavaScript" type="text/javascript" defer><!-- 
	function pwsSpecialsStatusChange(){
		var specialdiv=document.getElementById("pwsSpecialDiv");
		var pws_specials_status=document.getElementById('pws_specials_status');
		specialdiv.style.display=pws_specials_status.checked ? 'block' : 'none';
	}
	var pwsSpecialsExpires = new ctlSpiffyCalendarBox("pwsSpecialsExpires", "new_special", "pws_specials_expires","btnDate1","<?=$pws_engine->formatDate($sInfo->pws_specials_expires,DATE_PWS_FORMAT)?>",scBTNMODE_CUSTOMBLUE);
//--></script>
<!-- <script language="JavaScript" src="includes/javascript/calendarcode.js"></script> -->
<?php
  }
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<div id="spiffycalendar" class="text"></div>
<div id="popupcalendar" class="text"></div>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  if ( ($action == 'new') || ($action == 'edit') ) {
?>
      <tr><form name="new_special" <?php echo 'action="' . tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params(array('action', 'info', 'pws_specials_id')) . 'action=' . $form_action, 'NONSSL') . '"'; ?> method="post"><?php if ($form_action == 'update') echo tep_draw_hidden_field('pws_specials_id', $HTTP_GET_VARS['pws_specials_id']); ?>
        <td><br><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_SPECIALS_PRODUCT; ?>&nbsp;
            	<?=tep_draw_hidden_field('products_id',$sInfo->products_id,'id="'.$sInfo->products_id.'"')?></td>
            <td class="main"><?php echo (isset($sInfo->products_name)) ? $sInfo->products_name . ' <small>(' . $currencies->format($sInfo->products_price) . ')</small>' : tep_draw_products_pull_down('products_id', 'style="font-size:10px"', $specials_array); echo tep_draw_hidden_field('products_price', (isset($sInfo->products_price) ? $sInfo->products_price : '')); ?></td>
          </tr>
<?=$pws_specials->adminEditProduct($sInfo->products_id,&$sInfo)?>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="2" class="main" align="right" valign="top"><br><?php echo (($form_action == 'insert') ? tep_image_submit('button_insert.gif', IMAGE_INSERT) : tep_image_submit('button_update.gif', IMAGE_UPDATE)). '&nbsp;&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_SPECIALS, 'page=' . $HTTP_GET_VARS['page'] . (isset($HTTP_GET_VARS['pws_specials_id']) ? '&pws_specials_id=' . $HTTP_GET_VARS['pws_specials_id'] : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
          </tr>
        </table></td>
      </form></tr>
<?php
  } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRODUCTS_PRICE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $specials_query_raw = "select * from  " . TABLE_PRODUCTS . " p, " . TABLE_PWS_SPECIALS . " s , " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id=pd.products_id and p.products_id=s.products_id and pd.language_id = '" . (int)$languages_id . "' order by pd.products_name";
    $specials_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $specials_query_raw, $specials_query_numrows);
    $specials_query = tep_db_query($specials_query_raw);
    while ($specials = tep_db_fetch_array($specials_query)) {
      if ((!isset($HTTP_GET_VARS['pws_specials_id']) || (isset($HTTP_GET_VARS['pws_specials_id']) && ($HTTP_GET_VARS['pws_specials_id'] == $specials['pws_specials_id']))) && !isset($sInfo)) {
        $products_query = tep_db_query("select products_image from " . TABLE_PRODUCTS . " where products_id = '" . (int)$specials['products_id'] . "'");
        $products = tep_db_fetch_array($products_query);
        $sInfo_array = array_merge($specials, $products);
        $sInfo = new objectInfo($sInfo_array);
      }
      if (isset($sInfo) && is_object($sInfo) && ($specials['pws_specials_id'] == $sInfo->pws_specials_id)) {
        echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_SPECIALS, 'page=' . $HTTP_GET_VARS['page'] . '&pws_specials_id=' . $sInfo->pws_specials_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_SPECIALS, 'page=' . $HTTP_GET_VARS['page'] . '&pws_specials_id=' . $specials['pws_specials_id']) . '\'">' . "\n";
      }
?>
                <td  class="dataTableContent"><?php echo $specials['products_name']; ?></td>
                <td  class="dataTableContent" align="right"><span class="oldPrice"><?php echo $pws_prices->formatPrice($pws_prices->getFirstPrice($specials['products_id']),$specials['products_id']);?></span> <span class="specialPrice"><?php echo '-&nbsp;'.($pws_prices->formatPercentage($specials['pws_specials_discount']));?></span></td>
                <td  class="dataTableContent" align="right">

<?php
      if ($specials['pws_specials_status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_SPECIALS, 'action=setflag&flag=0&products_id=' . $specials['products_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_SPECIALS, 'action=setflag&flag=1&products_id=' . $specials['products_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
      }
?></td>
                <td class="dataTableContent" align="right"><?php if (isset($sInfo) && is_object($sInfo) && ($specials['pws_specials_id'] == $sInfo->pws_specials_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_SPECIALS, 'page=' . $HTTP_GET_VARS['page'] . '&pws_specials_id=' . $specials['pws_specials_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
      </tr>
<?php
    }
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellpadding="0"cellspacing="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $specials_split->display_count($specials_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_SPECIALS); ?></td>
                    <td class="smallText" align="right"><?php echo $specials_split->display_links($specials_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
                  </tr>
<?php
  if (empty($action)) {
?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_SPECIALS, 'page=' . $HTTP_GET_VARS['page'] . '&action=new') . '">' . tep_image_button('button_new_product.gif', IMAGE_NEW_PRODUCT) . '</a>'; ?></td>
                  </tr>
<?php
  }
?>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_SPECIALS . '</b>');

      $contents = array('form' => tep_draw_form('specials', FILENAME_SPECIALS, 'page=' . $HTTP_GET_VARS['page'] . '&pws_specials_id=' . $sInfo->pws_specials_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $sInfo->products_name . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . '&nbsp;<a href="' . tep_href_link(FILENAME_SPECIALS, 'page=' . $HTTP_GET_VARS['page'] . '&pws_specials_id=' . $sInfo->pws_specials_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($sInfo)) {
        $heading[] = array('text' => '<b>' . $sInfo->products_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_SPECIALS, 'page=' . $HTTP_GET_VARS['page'] . '&pws_specials_id=' . $sInfo->pws_specials_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_SPECIALS, 'page=' . $HTTP_GET_VARS['page'] . '&pws_specials_id=' . $sInfo->pws_specials_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
//        $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_ADDED . ' ' . tep_date_short($sInfo->specials_date_added));
//        $contents[] = array('text' => '' . TEXT_INFO_LAST_MODIFIED . ' ' . tep_date_short($sInfo->specials_last_modified));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_info_image($sInfo->products_image, $sInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT));
        $contents[] = array('text' => '<br>' . TEXT_INFO_ORIGINAL_PRICE . ' ' . $pws_prices->formatPrice($sInfo->products_price,$sInfo->products_id));
//        $contents[] = array('text' => '' . TEXT_INFO_NEW_PRICE . ' ' . $currencies->format($sInfo->specials_new_products_price));
        $contents[] = array('text' => '' . TEXT_INFO_PERCENTAGE . ' ' . $pws_prices->formatPercentage($sInfo->pws_specials_discount));

        $contents[] = array('text' => '<br>' . TEXT_INFO_EXPIRES_DATE . ' <b>' . tep_date_short($sInfo->pws_specials_expires) . '</b>');
//        $contents[] = array('text' => '' . TEXT_INFO_STATUS_CHANGE . ' ' . tep_date_short($sInfo->date_status_change));
      }
      break;
  }
  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
}
?>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
