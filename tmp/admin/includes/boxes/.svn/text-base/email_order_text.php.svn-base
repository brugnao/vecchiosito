<?php
/*
  $Id: catalog.php,v 1.21 2003/07/09 01:18:53 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
  
  Dies ist eine eigenständig arbeitende Softwareproduktion von Dirk Jäger
  Sie darf weder weitergegeben werden noch weiterverkauft werden, 
  ohne ausdrückliche genehmigung des Entwicklers
  
*/
?>
<!-- catalog //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_EMAIL_ORDER_TEXT,
                     'link'  => tep_href_link(FILENAME_EMAIL_ORDER_TEXT, 'selected_box=emailtext'));

  if ($selected_box == 'emailtext') {
    $contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_EMAIL_ORDER_TEXT, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_EMAIL_ORDER_TEXT . '</a><br>');  
    }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- catalog_eof //-->

<!-- reports //-->
          <tr>
            <td>

