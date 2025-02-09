<?php


  require('includes/application_top.php');

   
  $sql=tep_db_query("SELECT * FROM configuration where configuration_key = 'EMAIL_USE_HTML'");

   $configuration_group=tep_db_fetch_array($sql);
   $configuration_group = $configuration_group["configuration_group_id"];          

  $action = (isset($_REQUEST['action']) ? $_REQUEST['action'] : '');
	
  
    if (($action == 'update') && isset($_POST))  {

	$languages = tep_get_languages();
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
	$languages_id=$languages[$i]['id'];

	$aboutus=$_POST['aboutus'][$languages_id];

	if (EMAIL_USE_HTML == 'true'){	

		$aboutus = str_replace("&lt;-", "<-", $aboutus);
		$aboutus = str_replace("-&gt;", "->", $aboutus);
		$aboutus = preg_replace('/\r\n|\r|\n/', ' ', $aboutus);

 		if (tep_db_query("update " . TABLE_EMAIL_ORDER_TEXT . " set eorder_text_one = '" . $aboutus . "' where eorder_text_id = '2' and language_id='" . $languages_id . "'")) {
            $messageStack->add(SUCCESS_EMAIL_ORDER_TEXT, 'success');
        } else {
   			$messageStack->add(ERROR_EMAIL_ORDER_TEXT, 'error'); 
    	} 
  } else {

 		if (tep_db_query("update " . TABLE_EMAIL_ORDER_TEXT . " set eorder_text_one = '" . $aboutus . "' where eorder_text_id = '1' and language_id='" . $languages_id . "'")) {
            $messageStack->add(SUCCESS_EMAIL_ORDER_TEXT, 'success');
        } else {
   			$messageStack->add(ERROR_EMAIL_ORDER_TEXT, 'error'); 
    	} 
    	
  	  }  
    }
  } 


?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!-- bof EmailOrderText 1 6  -->
<script type="text/javascript" src="includes/javascript/tabpane/local/webfxlayout.js"></script>
<link type="text/css" rel="stylesheet" href="includes/javascript/tabpane/tab.webfx.css">
<style type="text/css">
.dynamic-tab-pane-control h2 {
  text-align: center;
  width:    auto;
}

.dynamic-tab-pane-control h2 a {
  display:  inline;
  width:    auto;
}

.dynamic-tab-pane-control a:hover {
  background: transparent;
}
</style>
<script type="text/javascript" src="includes/javascript/tabpane/tabpane.js"></script>
<!-- eof EmailOrderText 1 6  -->
<script language="JavaScript" type="text/JavaScript">
<!--
function ow(seite)
  { 
	w1 = window.open(seite, 'LEAF', 'toolbar=no,status=no,width=700,height=500,directories=no,scrollbars=yes,location=no,resize=yes,menubar=no');
  	w1.focus();
}

//-->
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
          <tr>        
          <td class="smalltext"><? echo INFO  ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
          
          </tr>
          <tr>
            <td><table border="0" cellpadding="0" cellspacing="2">
              <tr>
 <!-- <table border="1" cellpadding="5" cellspacing="1" style="border-collapse: collapse" width="100%" id="AutoNumber1"> -->
              <tr>
		<td align="center">
		
<br><? echo tep_image(DIR_WS_IMAGES . 'icon_info.gif'); ?> <a href="javascript:ow('email_order_text_popup.php');"><? echo VAR_LIST_POPUP		?></a>
		
<?php 

    echo tep_draw_form('aboutusform', FILENAME_EMAIL_ORDER_TEXT,'action=update');   
    
//  bof EmailOrderText 1 6          
//    for ($i=0, $n=sizeof($languages); $i<$n; $i++) { 
//    $languages_id=$languages[$i]['id'];	
//	
//    if (EMAIL_USE_HTML == 'true'){
//    $sql=tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = 2 AND language_id = '" . $languages_id . "'");
//	} else {    
//    $sql=tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = 1 AND language_id = '" . $languages_id . "'");
//	}   
// 
//	$row=tep_db_fetch_array($sql);
//  $text = $row['eorder_text_one'];
?>	
<!--


	<table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
                <td class="main">
                <?php 
				if (EMAIL_USE_HTML == 'true'){
					  include("fckeditor/fckeditor.php") ;

				echo tep_draw_fckeditor('aboutus[' . $languages[$i]['id'] . ']', '600', '300', $text);
           	//	 echo tep_draw_fckeditor('pages_html_text['.$languages[$i]['id'] . ']', '500', '300', $pages_html_text[$languages[$i]['id']]); 
				
				} else { 

				echo tep_draw_textarea_field('aboutus[' . $languages[$i]['id'] . ']','soft','75','15', $text);
				
				}
				?>               
                </td>
              </tr>
            </table>
-->
         </table>
           <tr>
             <td colspan="2">
            <table border="0" cellspacing="0" cellpadding="2" width="100%" align="center">
              <tr>
            <td class="main" valign="top" width="100%"><div class="tab-pane" id="tabPane1">
              <script type="text/javascript">tp1 = new WebFXTabPane( document.getElementById( "tabPane1" ) );</script>
<?php
$languages = tep_get_languages();
     for ($i=0, $n=sizeof($languages); $i<$n; $i++) {   	
      $languages_id=$languages[$i]['id'];	
	
      if (EMAIL_USE_HTML == 'true'){
        $sql=tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = 2 AND language_id = '" . $languages_id . "'");
      } else {    
        $sql=tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = 1 AND language_id = '" . $languages_id . "'");
      }   
 
	    $row=tep_db_fetch_array($sql);
      $text = $row['eorder_text_one'];	    
?>
            <div class="tab-page" id="<?php echo $languages[$i]['name'];?>">
               <h2 class="tab"><nobr><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'],'align="absmiddle" style="height:20px; width:30px;"') . '&nbsp;' .$languages[$i]['name'];?></nobr></h2>
            <script type="text/javascript">tp1.addTabPage( document.getElementById( "<?php echo $languages[$i]['name'];?>" ) );</script>
            <table width="100%"  border="0" cellspacing="0" cellpadding="0" summary="TabEdit">
             <tr>
            <td valign="top"><table border="0" cellspacing="4" cellpadding="0" summary="Info Titre">
             <tr>
              <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '1'); ?></td>
             </tr>
              </table>
             <table width="100%"  border="0" cellspacing="4" cellpadding="0" summary="Info Description">
              <tr valign="top">
               <td class="main"><?php echo TEXT_EMAIL_ORDER_TEXT; ?></td>
             </tr>
              <tr>
<!--               <td class="main"><?php echo tep_draw_textarea_field('products_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($products_description[$languages[$i]['id']]) ? ereg_replace('& ', '&amp; ', trim(stripslashes($products_description[$languages[$i]['id']]))) : tep_get_products_description($pInfo->products_id, $languages[$i]['id'])),'style="width:100%"'); ?></td> -->
                <td class="main">
                <?php 
				if (EMAIL_USE_HTML == 'true'){ 
				  echo tep_draw_fckeditor('aboutus[' . $languages[$i]['id'] . ']', '900', '700', $text);
				} else { 
				  echo tep_draw_textarea_field('aboutus[' . $languages[$i]['id'] . ']','soft','75','15', $text);				
				}
				?>               
                </td>
              </tr>
            </table>
           </tr>
          </table>
         </div>
<?php
}
?>
         </div>
          <script type="text/javascript">
            setupAllTabs();
          </script>
            </td>
           </tr>
         </table>
<!--        <table border="0" summary="" cellspacing="0" cellpadding="2"> -->
<!-- /*** EOF EmailOrderTExt 1 6  ***/ //-->

<?php    
  
     echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?>
</td>
</form>		
		</td>
              </tr>
            </table>               
                </td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
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