<?php
/*
 * @filename:	pws_setup.php
 * @version:	1.00
 * @project:	PWS
 *
 * @author:		Riccardo Roscilli <info@oscommerce.it>
 * @created:	24/gen/08
 * @modified:	24/gen/08 16:21:30
 *
 * @copyright:	2006-2008	Riccardo Roscilli @ PWS
 *
 * @desc:	Controlla l'esistenza di nuovi plugin application e se li installa se ne trova
 *
 * @TODO:		
 */
require('includes/application_top.php');
switch ($action=$_REQUEST['action']){
	case 'process':
		$plugins=$_REQUEST['new_plugin'];
		$pws_engine->installApplicationPlugins(&$plugins);
		break;
	default:
		$plugins=$pws_engine->getNewApplicationPlugins();
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!-- PWS bof -->
<style type="text/css"><? $pInfo=NULL;echo $pws_prices->adminStylesheet(&$pInfo);?></style>
<!-- PWS eof -->
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
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
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
		  </tr>
        </table></td>
      </tr>
      <tr>
        <td><?=tep_draw_form('pws_setup_application',FILENAME_PWS_APPLICATION_SETUP,'action=process')?>
         <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <?php if ($action!='process'){?><td class="dataTableHeadingContent"></td><?}?>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PLUGIN_NAME; ?></td>
                <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_PLUGIN_STATUS; ?></td>
              </tr>
<?php
	reset($plugins);
	foreach($plugins as $plugin_code=>$plugin_status){
?>
			  <tr class="dataTableRow">
<?
		if ($action!='process'){?><td class="dataTableContent"><input type="checkbox" name="new_plugin[<?=$plugin_code?>]"/></td><?}?>
                <td class="dataTableContent"><?=$plugin_code?></td>
                <td class="dataTableContent" align="left"><?=$plugin_status ? STRING_PLUGIN_STATUS_ON : STRING_PLUGIN_STATUS_OFF?></td>
              </tr>
<?php
    }
?>
            </table></td>
    </table>
    <?=tep_image_submit('button_confirm.gif',IMAGE_CONFIRM)?></form></td>
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
