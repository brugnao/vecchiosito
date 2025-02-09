<?php
/*
  $Id: popup_search_help.php,v 1.4 2003/06/05 23:26:23 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $navigation->remove_current_page();

    require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_CONFIRMATION);
  
  // select pagina termini e condizioni impostata nel page manager, tipo 4
  $page_id_query = tep_db_query("select pages_id from " . TABLE_PAGES . " where page_type = 4");
  $pages_id_array = tep_db_fetch_array($page_id_query);
 
    // carica la descrizione nella lingua impostata 
   $page_query = tep_db_query("select 
                                   s.pages_title,
                                   s.pages_html_text,
                                   s.intorext,
                                   s.externallink,
                                   s.link_target  
                                from 
                                   " .TABLE_PAGES_DESCRIPTION . " s
                                where 
                                    s.language_id = '" . (int)$languages_id . "'
                                and 
                                	s.pages_id = '" . $pages_id_array['pages_id'] . "'
                                ");
   $page_array = tep_db_fetch_array($page_query);
  
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="10" marginheight="10" topmargin="10" bottommargin="10" leftmargin="10" rightmargin="10">
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => $page_array['pages_title'] );

  new infoBoxHeading($info_box_contents, true, true);

  $info_box_contents = array();
  $info_box_contents[] = array('text' => $page_array['pages_html_text']);

  new infoBox($info_box_contents);
?>

<p class="smallText" align="right"><?php echo '<a href="javascript:window.close()">' . TEXT_CLOSE_WINDOW . '</a>'; ?></p>

</body>
</html>
<?php require('includes/application_bottom.php'); ?>
