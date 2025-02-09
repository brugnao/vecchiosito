<?php
/*
 * @filename:	pws_plugins.php
 * @version:	1.00
 * @project:	PWS
 *
 * @author:		Riccardo Roscilli <info@oscommerce.it>
 * @created:	16/mag/07
 * @modified:	16/mag/07 11:37:47
 *
 * @copyright:	2006-2007	Riccardo Roscilli @ PWS
 *
 * @desc:	
 *
 * @TODO:		
 */
	require_once DIR_FS_PWS_LANGUAGE.'language_pws_plugins.php';
	
	$plugin_type = 'prices';
	$plugin_directory = DIR_FS_PWS_PLUGINS_PRICES;
	$plugin_key = 'PLUGIN_PRICES_INSTALLED';
	define('HEADING_TITLE', HEADING_TITLE_PLUGINS_PRICES);

	$action = (isset($_REQUEST['action']) ? $_REQUEST['action'] : '');
	$plugin_code = isset($_REQUEST['plugin_code']) ? $_REQUEST['plugin_code'] : '';
	if ($plugin_code){
		$plugin = &$pws_engine->getPlugin($plugin_code);
	}else{
		$plugin=NULL;
	}
  if (tep_not_null($action)) {
    switch ($action) {
      case 'save':
//        while (list($key, $value) = each($HTTP_POST_VARS['configuration'])) {
//          tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $value . "' where configuration_key = '" . $key . "'");
//        }
		$plugin->saveConfiguration();
        tep_redirect(tep_href_link(FILENAME_PLUGINS, 'set=' . $set . '&plugin_code=' . $_REQUEST['plugin_code']));
        break;
      case 'install':
      	if ($pws_engine->installPlugin($plugin_code,$plugin_type))
			tep_redirect(tep_href_link(FILENAME_PLUGINS, 'set=' . $set . '&plugin_code=' . $_REQUEST['plugin_code']));
		else
			$action='';
		break;
	  case 'remove':
		$pws_engine->removePlugin(&$plugin);
		tep_redirect(tep_href_link(FILENAME_PLUGINS, 'set=' . $set));
        break;
/*	  case 'sort_plus':
	  	$pws_engine->sortPluginHigher(&$plugin);
	  	tep_redirect(tep_href_link(FILENAME_PLUGINS, 'set=' . $set));
        break;
	  case 'sort_minus':
	  	$pws_engine->sortPluginLower(&$plugin);
	  	tep_redirect(tep_href_link(FILENAME_PLUGINS, 'set=' . $set));
        break;
*/    }
  }
//  require_once DIR_FS_PWS_LANGUAGE.'language_pws_plugins.php';
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<script language="JavaScript" type="text/JavaScript" defer>
	var subWindow=null;
	function OpenSubwindow(url,title)
	{
		if (subWindow)
			subWindow.close();
		subWindow = null;
		subWindow = window.open(url,title,"width=800,height=800,innerWidth=800,innerHeight=800,menubar=false,alwaysRaised=true,copyhistory=false,dependent=true,directories=false,hotkeys");
		if (subWindow.opener == null)
			subWindow.opener = self;
	}
</script>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
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
<td width="100%" valign="top">
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PLUGINS; ?></td>
				<td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTIVE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
  $directory_array = array();
  if ($dir = @dir($plugin_directory)) {
    while ($file = $dir->read()) {
      if (!is_dir($plugin_directory . $file)) {
        if (substr($file, strrpos($file, '.')) == $file_extension) {
          $directory_array[] = $file;
        }
      }
    }
    sort($directory_array);
    $dir->close();
  }

  for ($i=0, $n=sizeof($directory_array); $i<$n; $i++) {
    $file = $directory_array[$i];
    $class = substr($file, 0, strrpos($file, '.'));
    if ($pws_engine->isInstalledPlugin($class,$plugin_type))	{
    	$plin=$pws_engine->getPlugin($class,$plugin_type);
    }else{
	    require_once(DIR_FS_PWS_LANGUAGE . '/plugins/' . $plugin_type . '/language_' . $file);
	    require_once($plugin_directory . $file);
	    if (tep_class_exists($class)){
			$plin = new $class(&$pws_engine);
	    }
    }
    if (is_null($plugin)){
    	if (NULL==$plugin_code){
	    	$plugin=$plin;
    	}else if ($plugin_code==$plin->plugin_code)
	    	$plugin=$plin;
    }


      if ($plin->plugin_code==$plugin->plugin_code) {
        if ($plugin->check() > 0) {
          echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PLUGINS, 'set=' . $set . '&plugin_code=' . $class . '&action=edit') . '\'">' . "\n";
        } else {
          echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
        }
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PLUGINS, 'set=' . $set . '&plugin_code=' . $class) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo $plin->plugin_name; ?></td>
                <td class="dataTableContent" align="right"><?=$pws_engine->isInstalledPlugin($plin->plugin_code) ? tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', '', 10, 10) : tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', '', 10, 10) ?></td>                
                <td class="dataTableContent" align="right"><?php if ($plin->plugin_code==$plugin->plugin_code) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . tep_href_link(FILENAME_PLUGINS, 'set=' . $set . '&plugin_code=' . $class) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }

?>
              <tr>
                <td colspan="3" class="smallText"><?php echo TEXT_PLUGIN_DIRECTORY . ' ' . $plugin_directory; ?></td>
              </tr>
            </table></td>
<td width="25%" valign="top">
<?php
	switch($action)	{
		case 'edit':
			echo $plugin->editConfiguration();
			break;
		default:
			echo $plugin->displayConfiguration();
			break;
	}
?></td>
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
