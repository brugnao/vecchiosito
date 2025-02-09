<?php
/*
  $Id: allmanufacturers.php,v 1.0 2003/11/25 10:12:10

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  All Manufacturers by Alex Kaiser
  alex@pooliestudios.com
  www.pooliestudios.com


  Rivisto, corretto e adattato ad oscommerce ita b2b by
  Riccardo Roscilli @ PWS
  http://www.modulioscommerce.com/

  Released under the GNU General Public License
*/


  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_MANUFACTURERS);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_MANUFACTURERS));

define('COLUMN_LISTING', 'false');  // added by azer change to false for columns listing 

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE ?>: <?php echo HEADING_TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table width="100%" border="0" align="center" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><h1><?php echo HEADING_TITLE; ?></h1></td>
            <td class="pageHeading" align="right">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><br>
   
         	
        	<table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
          	
        
 <!-- all manufacturers begin //-->
 
 <?php if (COLUMN_LISTING=='true') {  ?>    
                 <center>
 <?php
   $manufacturers_query = tep_db_query("select manufacturers_name, manufacturers_id, manufacturers_image from " . TABLE_MANUFACTURERS . " order by manufacturers_name" );

   if (tep_db_num_rows($manufacturers_query) >= '1') {
       while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {

     echo '<a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturers['manufacturers_id'] . '=' . $manufacturers['manufacturers_name']) . '">'. $manufacturers['manufacturers_name'] . ' <br> ' . tep_image(DIR_WS_IMAGES . $manufacturers['manufacturers_image'], $manufacturers['manufacturers_name']) . "</a><br><br>\n";
       }
   }
 ?>
 </center>
 <?php
} else {
// column
 
$row = 0;
$manufacturers_query = tep_db_query("select manufacturers_name, manufacturers_id, manufacturers_image from " . TABLE_MANUFACTURERS . " order by manufacturers_name" );
while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
$row++;
echo '<td align="center" valign="top" width="25%" class="smallText">';
 echo '<a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturers['manufacturers_id'] . '=' . $manufacturers['manufacturers_name'], 'NONSSL') . '">'. $manufacturers['manufacturers_name'] . ' <br> ';
if ($manufacturers['manufacturers_image']) {
echo tep_image(DIR_WS_IMAGES . $manufacturers['manufacturers_image'], $manufacturers['manufacturers_name']);
}
echo "</a><br><br>\n";
echo '</td>';

if ((($row / 4) == floor($row / 4))) {
?>
<tr>


<?php
}
} 
}
//end column listing
?> 

 <!-- all manufacturers end //-->
</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td align="right" class="main"><br><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>  <tr>
      <td valign="top">&nbsp;</td>
    </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>