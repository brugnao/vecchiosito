<?php
/*
  $Id: page_manager.php,v 1.73 2003/06/29 22:50:51 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');
 include("fckeditor/fckeditor.php") ;

$action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');
$languages = tep_get_languages();

// query di bonifica del database, la tab manufcturers_info non � mai stata usata, dobbiamo riempirla con i dati dei produttori
$man_query = tep_db_query("Select * from ". TABLE_MANUFACTURERS . " where 1");


while ( $man_row = tep_db_fetch_array($man_query) )
{
  $maninfo_query = tep_db_query("select * from ". TABLE_MANUFACTURERS_INFO . " where manufacturers_id = '" . $man_row['manufacturers_id'] . "'");
  $maninfo_array = tep_db_fetch_array($maninfo_query);

 if ($maninfo_array['manufacturers_id'] == '') // il record relativo alla tabella info non c'�
  {
  	for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
                $language_id = $languages[$i]['id'];
			  	$maninfo_sql_array = array ('manufacturers_id' => $man_row['manufacturers_id'],
			  								'languages_id' => $language_id
			  								);
			  	tep_db_perform(TABLE_MANUFACTURERS_INFO, $maninfo_sql_array);
  	}
  }
  	
}


if (tep_not_null($action)) {
	
	tep_reset_turbo_cache();
	
switch ($action) {
    case 'setflag':
        if ( ($HTTP_GET_VARS['flag'] == '0') || ($HTTP_GET_VARS['flag'] == '1') ) {
            tep_set_page_status($HTTP_GET_VARS['bID'], $HTTP_GET_VARS['flag']);
            $messageStack->add_session(SUCCESS_PAGE_STATUS_UPDATED, 'success');
        } else {
            $messageStack->add_session(ERROR_UNKNOWN_STATUS_FLAG, 'error');
        }
        tep_redirect(tep_href_link(FILENAME_PAGE_MANAGER, 'page=' . $HTTP_GET_VARS['page'] . '&bID=' . $HTTP_GET_VARS['bID']));
        break;
    case 'insert':
    case 'update':
        if (isset($HTTP_POST_VARS['mID'])) $manufacturer_id = tep_db_prepare_input($HTTP_POST_VARS['mID']);
        $manufacturer_error = false;
 		
        
            $manufacturer_name=tep_db_prepare_input($HTTP_POST_VARS['manufacturer_name']);
            if (empty($manufacturer_name)) {
                $messageStack->add(ERROR_MANUFACTURER_NAME_REQUIRED, 'error');
                 $manufacturer_error = true;
       		 }
        if (empty($manufacturer_description)) { // non genero errori se la descrizone è vuota
        	
        }
        if ($manufacturer_error == false) {

            for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
                $language_id = $languages[$i]['id'];
                $sql_data_array_manufacturers = array('manufacturers_name' => tep_db_prepare_input($HTTP_POST_VARS['manufacturer_name']),
                                              		   'manufacturers_image' => $HTTP_POST_VARS['manufacturers_image']
                										);

                $sql_data_array_manufacturers_info = array('manufacturers_description' => tep_db_prepare_input($HTTP_POST_VARS['manufacturer_description'][$language_id]),
                                                           'manufacturers_url' => tep_db_prepare_input($HTTP_POST_VARS['manufacturer_url'])
                             								);
                if ($action == 'insert') {
                    $mID="";
                    if ($i == 0)  {
                        tep_db_perform(TABLE_MANUFACTURERS, $sql_data_array_manufacturers);
                        $manufacturers_id = tep_db_insert_id();
                    }
                    $manufacturerid_merge= array('manufacturers_id' => $manufacturers_id,
                    						//	 'date_added' => 'now()',
                   							//	 'last_modified' => 'now()',
                                         'languages_id' => $language_id);
  
                    $sql_data_array_manufacturers_info = array_merge($sql_data_array_manufacturers_info, $manufacturerid_merge);
         			//	print_r($sql_data_array_manufacturers_info);
                    //    exit;
                    tep_db_perform(TABLE_MANUFACTURERS_INFO, $sql_data_array_manufacturers_info);
                    $messageStack->add_session(SUCCESS_MANUFACTURER_INSERTED, 'success');
                } elseif ($action == 'update') {

                    $selectexists=tep_db_query("select count( * ) as `countrecords` from `".TABLE_MANUFACTURERS_INFO."` where manufacturers_id='" . (int)$manufacturer_id . "' and languages_id='". $language_id ."'");
                    $recordexists = tep_db_fetch_array($selectexists);

               		$update_sql_data = array('last_modified' => 'now()'); 
                    $sql_data_array_manufacturers = array_merge($sql_data_array_manufacturers, $update_sql_data);
                    
                    if($recordexists['countrecords'] >= 1 )  {
                    	 
             	         tep_db_perform(TABLE_MANUFACTURERS, $sql_data_array_manufacturers, 'update', "manufacturers_id = '" . (int)$manufacturer_id . "'");
                         tep_db_perform(TABLE_MANUFACTURERS_INFO, $sql_data_array_manufacturers_info, 'update', "manufacturers_id = '" . (int)$manufacturer_id . "' and languages_id='".$language_id."'");
                    } else  {
            			
                        tep_db_perform(TABLE_MANUFACTURERS, $sql_data_array_manufacturers, 'update', "manufacturers_id = '" . (int)$manufacturer_id . "'");

                        $manufacturerid_merge= array('manufacturers_id' => $manufacturers_id,
                                             'languages_id' => $language_id);
             
                        $sql_data_array_manufacturers_info = array_merge($sql_data_array_manufacturers_info, $manufacturerid_merge);
                        tep_db_perform(TABLE_MANUFACTURERS_INFO, $sql_data_array_manufacturers_info);
                    }
                    $messageStack->add_session(SUCCESS_MANUFACTURER_UPDATED, 'success');
                }
            } //for
            tep_redirect(tep_href_link(FILENAME_MANUFACTURERS, (isset($HTTP_GET_VARS['manufacturer']) ? 'manufacturer=' . $HTTP_GET_VARS['manufacturer'] . '&' : '') . 'mID=' . $mID));
        } else {
            $action = 'new';
        }
        break;
    case 'deleteconfirm':
        $manufacturer_id = tep_db_prepare_input($HTTP_GET_VARS['mID']);
        tep_db_query("delete from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$manufacturer_id . "'");
        tep_db_query("delete from " . TABLE_MANUFACTURERS_INFO. " where manufacturers_id = '" . (int)$manufacturer_id . "'");
        $messageStack->add_session(SUCCESS_MANUFACTURER_REMOVED, 'success');
        tep_redirect(tep_href_link(FILENAME_MANUFACTURERS, 'manufacturer=' . $HTTP_GET_VARS['manufacturer']));
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
<? if ($action == 'new' || $action == 'edit') {  // carica tutti i javascript solo se � in modifica ?>

<style type="text/css">
<?=$pws_engine->triggerHook('ADMIN_STYLESHEET'). $pws_prices->adminStylesheet(&$pInfo)?>
</style>

<script language="javascript" src="includes/general.js" type="text/javascript"></script>
<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript"><!--
  var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "new_product", "products_date_available","btnDate1","<?php echo $pInfo->products_date_available; ?>",scBTNMODE_CUSTOMBLUE);
//--></script>
<?=$pws_engine->triggerHook('ADMIN_CATEGORIES_HEAD')?>
<script language="javascript" type="text/javascript" defer><!-- 
<?=$pws_engine->triggerHook('ADMIN_CATEGORIES_JAVASCRIPT')?>
// --></script>

<script language="javascript" type="text/javascript"><!--
<?php
    $tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
    $tax_class_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
    while ($tax_class = tep_db_fetch_array($tax_class_query)) {
      $tax_class_array[] = array('id' => $tax_class['tax_class_id'],
                                 'text' => $tax_class['tax_class_title']);
    }
?>
var tax_rates = new Array();
<?php
	echo "//	Tax Rates\r\n";
	for ($i=0, $n=sizeof($tax_class_array); $i<$n; $i++) {
      if ($tax_class_array[$i]['id'] > 0) {
        echo 'tax_rates["' . $tax_class_array[$i]['id'] . '"] = ' . tep_get_tax_rate_value($tax_class_array[$i]['id']) . ';' . "\n";
      }
    }

?>
//--></script>
<script language="javascript" type="text/javascript" defer><!-- 
<?=$pws_prices->adminJavascript(&$pInfo)?>
// --></script>
<script language="javascript" type="text/javascript"><!-- 
<?=$pws_prices->adminJavascriptPre(&$pInfo)?>
// --></script>

<? } ?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<div id="spiffycalendar" class="text"></div>
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
<?php


if ($action == 'new' || $action == 'edit') {
    $form_action = 'insert';
/*    $parameters = array('manufacturers_name' => '',
                        'manufacturers_description' => '',
    					'manufacturers_image'
    					);
    $mInfo = new objectInfo($parameters);
*/
    if (isset($HTTP_GET_VARS['mID'])) {
        $form_action = 'update';
        $mID = tep_db_prepare_input($HTTP_GET_VARS['mID']);
        $manufacturer_query = tep_db_query("select m.manufacturers_name, m.manufacturers_image, mi.manufacturers_description, mi.manufacturers_url, mi.languages_id from " 
                . TABLE_MANUFACTURERS . " m left join " . TABLE_MANUFACTURERS_INFO . " mi on m.manufacturers_id=mi.manufacturers_id
                where m.manufacturers_id = '" . (int)$mID . "'");


        while($manufacturer = tep_db_fetch_array($manufacturer_query))  {
        
            $languageid = $manufacturer['languages_id'];
            $manufacturer_name = $manufacturer['manufacturers_name'];
            $manufacturer_description[$languageid] = $manufacturer['manufacturers_description'];
            $manufacturer_url = $manufacturer['manufacturers_url'];
            $manufacturer_image = $manufacturer['manufacturers_image'];

        }
    } elseif (tep_not_null($HTTP_POST_VARS)) {
        $mInfo->objectInfo($HTTP_POST_VARS);
    }
    $mIDif="";
    if(!empty($mID) && $mID != "")  {
        $mIDif='&mID='.$mID;
    }
?>


      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr><?php echo tep_draw_form('new_manufacturer', FILENAME_MANUFACTURERS, (isset($HTTP_GET_VARS['manufacturer']) ? 'manufacturer=' . $HTTP_GET_VARS['manufacturer'] . '&' : '') . 'action=' . $form_action.$mIDif, 'post', 'enctype="multipart/form-data"'); if ($form_action == 'update') echo tep_draw_hidden_field('manufacturer_id', $mID); ?>
        <td><table border="0" cellspacing="0" cellpadding="2">

          <tr>
            <td class="main"><?php echo TEXT_MANUFACTURER_NAME; ?></td>
            <td class="main">

<?php 
        echo tep_draw_input_field('manufacturer_name', $manufacturer_name, '', true); 
?>
</td>
          </tr>
          <tr>
            <td class="main">&nbsp;</td>
            <td class="main">&nbsp;</td>
          </tr>
         <tr>
            <td class="main"><?php  echo TEXT_MANUFACTURER_URL; ?></td>
            <td class="main">

<?php 
        echo tep_draw_input_field('manufacturer_url', $manufacturer_url, '', false); 
?>
</td>
          </tr>
 
<tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>

<?php for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { ?>
     <tr>
            <td valign="top" class="main"><?php if ($i == 0) echo TEXT_MANUFACTURER_DESCRIPTION; ?>
</td>

            <td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']). '&nbsp;'. tep_draw_fckeditor('manufacturer_description['.$languages[$i]['id'] . ']', '700', '300', $manufacturer_description[$languages[$i]['id']]);
         //																																									   tep_draw_textarea_field('manufacturer_description['.$languages[$i]['id'] . ']', 'soft', '80', '20', $manufacturer_description[$languages[$i]['id']]); ?>
			</td>
<?php } ?>

<?php if (HTML_AREA_WYSIWYG_DISABLE_PAGEMANAGER == 'Enable') 
{ 
	?>
	
	<script language="JavaScript1.2" defer>
	// MaxiDVD Added WYSIWYG HTML Area Box + Admin Function v1.7 - 2.2 MS2 HTML PAGEMANAGER <body>
	var config = new Object(); // create new config object
	config.width = "<?php echo PAGEMANAGER_WYSIWYG_WIDTH; ?>px";
	config.height = "<?php echo PAGEMANAGER_WYSIWYG_HEIGHT; ?>px";
	config.bodyStyle = 'background-color: <?php echo HTML_AREA_WYSIWYG_BG_COLOUR; ?>; font-family: "<?php echo HTML_AREA_WYSIWYG_FONT_TYPE; ?>"; color: <?php echo HTML_AREA_WYSIWYG_FONT_COLOUR; ?>; font-size: <?php echo HTML_AREA_WYSIWYG_FONT_SIZE; ?>pt;';
	config.debug = <?php echo HTML_AREA_WYSIWYG_DEBUG; ?>;
	// Begin Solution multilingual
	<?php for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { ?>
	editor_generate('manufacturer_decription[<?php echo $languages[$i]['id']; ?>]',config);
	<?php } ?>
	// End Solution multilingual
	</script>
	

<?php 
}
// MaxiDVD Added HTML is ON when WYSIWYG BOX Enabled, HTML is OFF when WYSIWYG Disabled
?>

</tr>
<?
// }
?>

      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>

            <td class="main" align="center"><?php echo TEXT_MANUFACTURER_IMAGE . '<br>'; ?></td>
            <? // getProductsImage($field_index_value, $width=NULL,$height=NULL,$path=true, $field_index = 'products_id' , $field_image = 'products_image', $table = TABLE_PRODUCTS)
           ?>
            <td class="main"><?=$pws_html->drawImagePickerPlacehold('manufacturers_image',$manufacturer_image, 'manufacturers')?></td>
      
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main" align="center"></td>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main" align="center"></td>
            <td class="main" align="right" valign="top" nowrap><?php echo (($form_action == 'insert') ? tep_image_submit('button_insert.gif', IMAGE_INSERT) : tep_image_submit('button_update.gif', IMAGE_UPDATE)). '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_MANUFACTURERS, (isset($HTTP_GET_VARS['manufacturer']) ? 'manufacturer=' . $HTTP_GET_VARS['manufacturer'] . '&' : '') . (!empty($mID) and $mID != "" ? 'mID=' . $mID : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
          </tr>
        </table>

<input type="hidden" name="bID" value="<? echo $mID; ?>">
</td>
      </form></tr>
<?php
  } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow" width="100%">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_MANUFACTURERS; ?></td>
 
              </tr>

<?php
    $manufacturers_query_raw = "select 
                           m.manufacturers_id, 
                           m.manufacturers_name,
                           m.manufacturers_image, 
                           m.date_added,
                           m.last_modified,
                           mi.manufacturers_url,
                           mi.url_clicked,
                           mi.date_last_click,
                           mi.manufacturers_description
                        from 
                           " . TABLE_MANUFACTURERS. " m LEFT JOIN " .TABLE_MANUFACTURERS_INFO . " mi on m.manufacturers_id = mi.manufacturers_id
                        where
                           mi.languages_id='" . (int)$languages_id . "'
                        order by 
                           m.manufacturers_name";

//	  if ($manufacturers_query_raw <= 0)
//   $manufacturers_query_raw = 0; 
	
  $manufacturers_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $manufacturers_query_raw, $manufacturers_query_numrows);
  
    $manufacturers_query = tep_db_query($manufacturers_query_raw);
    
    while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
  
   
    if ((!isset($HTTP_GET_VARS['mID']) || (isset($HTTP_GET_VARS['mID']) && ($HTTP_GET_VARS['mID'] == $manufacturers['manufacturers_id']))) && !isset($mInfo) && (substr($action, 0, 3) != 'new')) {
      $manufacturer_products_query = tep_db_query("select count(*) as products_count from " . TABLE_PRODUCTS . " where manufacturers_id = '" . (int)$manufacturers['manufacturers_id'] . "'");
    	 $manufacturer_products = tep_db_fetch_array($manufacturer_products_query);
//      if ((!isset($HTTP_GET_VARS['mID']) || (isset($HTTP_GET_VARS['mID']) && ($HTTP_GET_VARS['mID'] == $manufacturers['manufacturers_id']))) && !isset($mInfo) && (substr($action, 0, 3) != 'new')) {
//        $bInfo_array = array_merge($pages, $info);
//        $bInfo = new objectInfo($bInfo_array);
      $mInfo_array = array_merge($manufacturers, $manufacturer_products);
      $mInfo = new objectInfo($mInfo_array);

      }


      if (isset($mInfo) && is_object($mInfo) && ($manufacturers['manufacturers_id'] == $mInfo->manufacturers_id)) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $manufacturers['manufacturers_id']) . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $manufacturers['manufacturers_id']) . '\'">' . "\n";
      }
?>


                 <td class="dataTableContent" align="left"><? echo $manufacturers['manufacturers_name'] ?></td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $manufacturers_split->display_count($manufacturers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_PAGES); ?></td>
                    <td class="smallText" align="right"><?php echo $manufacturers_split->display_links($manufacturers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
                  </tr>
                  <tr>
                    <td align="right" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_MANUFACTURERS, 'action=new') . '">' . tep_image_button('button_new_file.gif', TEXT_HEADING_NEW_MANUFACTURER) . '</a>'; ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();
  // print_r($mInfo);
  switch ($action) {
 
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_DELETE_MANUFACTURER . '</b>');

      $contents = array('form' => tep_draw_form('manufacturers', FILENAME_MANUFACTURERS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->manufacturers_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $mInfo->manufacturers_name . '</b>');
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('delete_image', '', true) . ' ' . TEXT_DELETE_IMAGE);
  	  $contents[] = array('text' => '<br>' . tep_info_image($mInfo->manufacturers_image, $mInfo->manufacturers_name));
      
  	  if ($mInfo->products_count > 0) {
        $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('delete_products') . ' ' . TEXT_DELETE_PRODUCTS);
        $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $mInfo->products_count));
      }

      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->manufacturers_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
    	
      if (isset($mInfo) && is_object($mInfo)) {
        $heading[] = array('text' => '<b>' . $mInfo->manufacturers_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->manufacturers_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->manufacturers_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($mInfo->date_added));
        
         if (tep_not_null($mInfo->last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($mInfo->last_modified));

         $contents[] = array('text' => '<br>' . TEXT_CLICKS . ' ' . $mInfo->url_clicked);
        if (tep_not_null($mInfo->date_last_click)) $contents[] = array('text' => '<br>' . TEXT_LAST_CLICKED . ' ' . tep_date_short($mInfo->date_last_click) );
        
         $contents[] = array('text' => '<br>' . tep_info_image($mInfo->manufacturers_image, $mInfo->manufacturers_name));

//PWS bof
        $contents[] = array('text' => $pws_engine->triggerHook('ADMIN_MANUFACTURERS_DISPLAY'));
//PWS eof
        $contents[] = array('text' => '<br>' . TEXT_PRODUCTS . ' ' . $mInfo->products_count);
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
    </table></td>

<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
