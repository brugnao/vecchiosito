<?php
/******************************************************************************/
/* PDF Catalogs v.1.55 for osCommerce v.2.2 MS2                               */
/*                                                                            */
/* by Antonios THROUVALAS (antonios@throuvalas.net), April 2004               */
/* by Nicolas Hilly (n.hilly@laposte.net), August 2004                        */
/*                                                                            */
/* Based on PDF Catalogs v.1.4 by gurvan.riou@laposte.net                     */
/*                                                                            */
/* Uses FPDF (http://www.fpdf.org), Version 1.52, by Olivier PLATHEY          */
/*                                                                            */
/* Credit goes also to:                                                       */
/* - Yamasoft (http://www.yamasoft.com/php-gif.zip) for their GIF class,      */
/* - Jerome FENAL (jerome.fenal@logicacmg.com) for introducing GIF Support    */
/*   in the FPDF Class,                                                       */
/* - The osC forums members (forums.oscommerce.com)!                          */
/*                                                                            */
/* Please donate to the osCommerce Core Team!                                 */
/* Freeware, You may use, modify and redistribute this software as you wish!  */
/******************************************************************************/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PDF_CATALOGUE);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_PDF_CATALOGUE));

	// PDF Catalog v.1.51
		// Directory where the generated PDF files will be stored!
	// If you mofify the name of this directory, please modify accordingly the
	// catalog/admin/pdf_config.php file!!
	// Don't forget to change the permissions of this directory to 755!
	define('DIR_WS_PDF_CATALOGS','catalogues/');

	// Filename to use as a base for the name of the generated PDF files.
	// If you mofify the name of this directory, please modify accordingly the
	// catalog/admin/pdf_config.php file!!
	define('PDF_FILENAME','catalog');

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<?php
// BOF: WebMakers.com Changed: Header Tag Controller v1.0
// Replaced by header_tags.php
if ( file_exists(DIR_WS_INCLUDES . 'header_tags.php') ) {
  require(DIR_WS_INCLUDES . 'header_tags.php');
} else {
?>
  <title><?php echo TITLE; ?></title>
<?php
}
// EOF: WebMakers.com Changed: Header Tag Controller v1.0
?>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH_LEFT; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH_LEFT; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_specials.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <!--  Show the Intro File
      <tr>
        <td class="main"><br><?//php include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PDF_DEFINE_INTRO); ?><br></td>
      </tr> -->
			<!--  Show the Description in the pdf_catalogue_info.php -->
      <tr>
        <td class="main" align="left"><?php echo TEXT_PDF_DESCRIPTION; ?></td>
      </tr>
      <tr>
        <td><br><br></td>
      </tr>
      <tr>
	<td class="main" align="center"><?php
            $file = DIR_WS_PDF_CATALOGS . PDF_FILENAME . "_" . $languages_id . ".pdf";
	    $sizecatalog = filesize($file)/pow(2,20);
            $formatted = sprintf("%0.2f Mb", $sizecatalog);
            echo "<img width=16 height=16 src=\"images/adobe_pdf.gif\" align=middle>&nbsp;";
	    echo '<a href="' . $file . '" target="_blank\"><b>' . TEXT_PDF_FILE .'</b></a> (' . $formatted . ')';
?>
        <p><br>
	</td>
      </tr>
      <tr>
        <td class="main" align="center"><?php echo TEXT_PDF_DOWNLOAD; ?></td>
      </tr>
			<tr>
        <td class="main" align="center">
        <?php
            echo '<a href="https://www.adobe.com/products/acrobat/readstep2.html" target="_blank">';
	    echo tep_image(DIR_WS_IMAGES . 'getacro.gif');
	?></a>
	</td>
      </tr>
      <tr>
        <td class="main" align="center"><?php echo TEXT_PDF_END; ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH_RIGHT; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH_RIGHT; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
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