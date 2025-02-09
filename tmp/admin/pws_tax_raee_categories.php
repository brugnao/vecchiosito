<?php
/*
 * @filename:	pws_tax_raee_categories.php
 * @version:	1.00
 * @project:	PWS
 *
 * @author:		Riccardo Roscilli <info@oscommerce.it>
 * @created:	11/gen/08
 * @modified:	11/gen/08 17:26:18
 *
 * @copyright:	2006-2008	Riccardo Roscilli @ PWS
 *
 * @desc:	Editing delle categorie degli eco-contributi RAEE
 *
 * @TODO:		
 */

require('includes/application_top.php');
require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();
define('FILENAME_PWS_TAX_RAEE_CATEGORIES','pws_tax_raee_categories.php');

$pws_prices_tax_raee=$pws_engine->getPlugin('pws_prices_tax_raee','prices');
if (NULL==$pws_prices_tax_raee){
	$messageStack->add_session(TEXT_PWS_TAX_RAEE_NOT_INSTALLED,'error');
	tep_redirect(tep_href_link(FILENAME_DEFAULT));
}

switch ($action = isset($_REQUEST['action']) ? $_REQUEST['action'] : ''){
	case 'insert':
		tep_db_query("insert into ".TABLE_PWS_TAX_RAEE." set pws_tax_raee_amount='".$_REQUEST['pws_tax_raee_amount']."'");
		$pws_tax_raee_id=tep_db_insert_id();
		foreach($_REQUEST['pws_tax_raee_description'] as $language_id=>$description){
			$sql_data_array=array(
				'pws_tax_raee_id'=>$pws_tax_raee_id
				,'language_id'=>$language_id
				,'pws_tax_raee_description'=>$description
			);
			tep_db_perform(TABLE_PWS_TAX_RAEE_DESCRIPTION,$sql_data_array);
		}
		
		break;
	case 'update':
		$insert=0==tep_db_num_rows(tep_db_query("select * from ".TABLE_PWS_TAX_RAEE." where pws_tax_raee_id='".$_REQUEST['pws_tax_raee_id']."'"));
		if ($insert){
			tep_db_query("insert into ".TABLE_PWS_TAX_RAEE." set pws_tax_raee_amount='".$_REQUEST['pws_tax_raee_amount']."'");
			$pws_tax_raee_id=tep_db_insert_id();
		}else{
			$pws_tax_raee_id=$_REQUEST['pws_tax_raee_id'];
			tep_db_query("update ".TABLE_PWS_TAX_RAEE." set pws_tax_raee_amount='".$_REQUEST['pws_tax_raee_amount']."' where pws_tax_raee_id='$pws_tax_raee_id'");
		}
		foreach($_REQUEST['pws_tax_raee_description'] as $language_id=>$description){
			$sql_data_array=array(
				'pws_tax_raee_description'=>$description
			);
			$insert=0==tep_db_num_rows(tep_db_query("select * from ".TABLE_PWS_TAX_RAEE_DESCRIPTION." where pws_tax_raee_id='$pws_tax_raee_id' and language_id='$language_id'"));
			if ($insert){
				$sql_data_array['language_id']=$language_id;
				$sql_data_array['pws_tax_raee_id']=$pws_tax_raee_id;
				tep_db_perform(TABLE_PWS_TAX_RAEE_DESCRIPTION,$sql_data_array);
			}else{
				tep_db_perform(TABLE_PWS_TAX_RAEE_DESCRIPTION,$sql_data_array,'update',"pws_tax_raee_id='$pws_tax_raee_id' and language_id='$language_id'");
			}
		}
		break;
	case 'delete_confirm':
		tep_db_query("delete from ".TABLE_PWS_TAX_RAEE." where pws_tax_raee_id=".$_REQUEST['pws_tax_raee_id']);
		tep_db_query("delete from ".TABLE_PWS_TAX_RAEE_DESCRIPTION." where pws_tax_raee_id=".$_REQUEST['pws_tax_raee_id']);
		tep_db_query("update ".TABLE_PWS_TAX_RAEE_STATUS." set pws_tax_raee_status='0' where pws_tax_raee_id=".$_REQUEST['pws_tax_raee_id']);
		break;
	default:
		break;
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<style type="text/css">
</style>
<script language="javascript" src="includes/general.js" type="text/javascript"></script>
<?=$pws_engine->triggerHook('ADMIN_CATEGORIES_HEAD')?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
<!-- body_text //-->
    <td width="100%" valign="top">
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DESCRIPTION; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_AMOUNT; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $categories_count = 0;
    $rows = 0;
    $categories_query = tep_db_query("select tr.pws_tax_raee_id, tr.pws_tax_raee_amount, trd.language_id, trd.pws_tax_raee_description from " . TABLE_PWS_TAX_RAEE . " tr left join ".TABLE_PWS_TAX_RAEE_DESCRIPTION." trd on (tr.pws_tax_raee_id=trd.pws_tax_raee_id and trd.language_id='$languages_id') where 1 order by trd.pws_tax_raee_description");
    while ($categories = tep_db_fetch_array($categories_query)) {
      $categories_count++;
      $rows++;

      if ((!isset($HTTP_GET_VARS['trID']) || (isset($HTTP_GET_VARS['trID']) && ($HTTP_GET_VARS['trID'] == $categories['pws_tax_raee_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
        $cInfo = new objectInfo($categories);
        $cInfo->products_count=array_pop(tep_db_fetch_array(tep_db_query("select count(*) from ".TABLE_PWS_TAX_RAEE_STATUS." where pws_tax_raee_id='".$cInfo->pws_tax_raee_id."'")));
      }

      if (isset($cInfo) && is_object($cInfo) && ($categories['pws_tax_raee_id'] == $cInfo->pws_tax_raee_id) ) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PWS_TAX_RAEE_CATEGORIES, 'trID='.$categories['pws_tax_raee_id']) . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PWS_TAX_RAEE_CATEGORIES,  'trID=' . $categories['pws_tax_raee_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_PWS_TAX_RAEE_CATEGORIES,'trID=' . $categories['pws_tax_raee_id']) . '"><b>' . $categories['pws_tax_raee_description'] . '</b></a>'?></td>
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_PWS_TAX_RAEE_CATEGORIES,'trID=' . $categories['pws_tax_raee_id']) . '"><b>' . $categories['pws_tax_raee_amount'] . '</b></a>'?></td>

                <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($categories['pws_tax_raee_id'] == $cInfo->pws_tax_raee_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_PWS_TAX_RAEE_CATEGORIES,  'trID=' . $categories['pws_tax_raee_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }

?>
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText"><?php echo TEXT_CATEGORIES . '&nbsp;' . $categories_count . '<br>'  ?></td>
                    <td align="right" class="smallText"><?php echo '<a href="' . tep_href_link(FILENAME_PWS_TAX_RAEE_CATEGORIES,  '&action=new_category') . '">' . tep_image_button('button_new_category.gif', IMAGE_NEW_CATEGORY) . '</a>'; ?>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
    $heading = array();
    $contents = array();
    switch ($action) {
     case 'new_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_CATEGORY . '</b>');

        $contents = array('form' => tep_draw_form('newcategory', FILENAME_PWS_TAX_RAEE_CATEGORIES, 'action=insert', 'post', 'enctype="multipart/form-data"'));
        $contents[] = array('text' => TEXT_NEW_CATEGORY_INTRO);

        $category_inputs_string = '';
        $languages = tep_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $category_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('pws_tax_raee_description[' . $languages[$i]['id'] . ']');
        }
        
        $contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_NAME . '<br>' . $category_inputs_string);
//        $contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_NAME . '<br>' . tep_draw_input_field('pws_tax_raee_description'));
        $contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_AMOUNT . '<br>' . tep_draw_input_field('pws_tax_raee_amount'));
		$contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_PWS_TAX_RAEE_CATEGORIES, 'cPath=' . $cPath) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'edit_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_CATEGORY . '</b>');

        $contents = array('form' => tep_draw_form('categories', FILENAME_PWS_TAX_RAEE_CATEGORIES, 'action=update', 'post', 'enctype="multipart/form-data"') . tep_draw_hidden_field('pws_tax_raee_id', $cInfo->pws_tax_raee_id));
        $contents[] = array('text' => TEXT_EDIT_INTRO);

        $category_inputs_string = '';
        $languages = tep_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $category_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('pws_tax_raee_description[' . $languages[$i]['id'] . ']', $pws_prices_tax_raee->getRAEETaxName($cInfo->pws_tax_raee_id, $languages[$i]['id']));
        }

        $contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_NAME . '<br>' . $category_inputs_string);
//        $contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_NAME . '<br>' . tep_draw_input_field('pws_tax_raee_description', $cInfo->pws_tax_raee_description));
        $contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_AMOUNT . '<br>' . tep_draw_input_field('pws_tax_raee_amount', $cInfo->pws_tax_raee_amount));
        
		$contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_PWS_TAX_RAEE_CATEGORIES,  '&trID=' . $cInfo->pws_tax_raee_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'delete_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CATEGORY . '</b>');

        $contents = array('form' => tep_draw_form('categories', FILENAME_PWS_TAX_RAEE_CATEGORIES, 'action=delete_confirm') . tep_draw_hidden_field('pws_tax_raee_id', $cInfo->pws_tax_raee_id));
        $contents[] = array('text' => TEXT_DELETE_CATEGORY_INTRO);
        $contents[] = array('text' => '<br><b>' . $cInfo->pws_tax_raee_description . '</b>');
        if ($cInfo->products_count > 0) $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $cInfo->products_count));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_PWS_TAX_RAEE_CATEGORIES,  '&trID=' . $cInfo->pws_tax_raee_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      default:
        if ($rows > 0) {
          if (isset($cInfo) && is_object($cInfo)) { // category info box contents
            $heading[] = array('text' => '<b>' . $cInfo->pws_tax_raee_description . '</b>');
            $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_PWS_TAX_RAEE_CATEGORIES,  'trID=' . $cInfo->pws_tax_raee_id . '&action=edit_category') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_PWS_TAX_RAEE_CATEGORIES,  'trID=' . $cInfo->pws_tax_raee_id . '&action=delete_category') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
            $contents[] = array('text' => TEXT_PRODUCTS . ' ' . $cInfo->products_count);
          }
   		} else { // create category/product info
          $heading[] = array('text' => '<b>' . EMPTY_CATEGORY . '</b>');

          $contents[] = array('text' => TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS);
        }
        break;
    }

    if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
      echo '            <td width="25%" valign="top">' . "\n";

      $box = new box;
      echo $box->infoBox($heading, $contents);

      echo '            </td>' . "\n";

      // Add neccessary JS for WYSIWYG editor of category image
//      if($action=='edit_category'){
//        if (HTML_AREA_WYSIWYG_DISABLE != 'Disable'){
//          echo '
//                  <script language="JavaScript1.2" defer>
//                  var config = new Object();  // create new config object
//                  config.width  = "250px";
//                  config.height = "35px";
//                  config.bodyStyle = "background-color: white; font-family: Arial; color: black; font-size: 12px;";
//                  config.debug = ' . HTML_AREA_WYSIWYG_DEBUG . ';
//                  config.toolbar = [ ["InsertImageURL"] ];
//                  config.OscImageRoot = "' . trim(HTTP_SERVER . DIR_WS_CATALOG_IMAGES) . '";
//                  editor_generate("categories_image",config);
//                 </script>
//               ';
//        }
//      }

    }
?>
          </tr>
        </table></td>
      </tr>
    </table>

    </td>
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
