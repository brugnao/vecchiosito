<?php
/*
  $Id: tools.php,v 1.21 2003/07/09 01:18:53 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- tools //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_TOOLS,
                     'link'  => tep_href_link(FILENAME_BACKUP, 'selected_box=tools'));

  if ($selected_box == 'tools') {
    $contents[] = array('text'  => 
    '<a href="' . tep_href_link(FILENAME_BACKUP) . '" class="menuBoxContentLink">' . BOX_TOOLS_BACKUP . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_BANNER_MANAGER) . '" class="menuBoxContentLink">' . BOX_TOOLS_BANNER_MANAGER . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_CACHE) . '" class="menuBoxContentLink">' . BOX_TOOLS_CACHE . '</a><br>' .
'<a href="' . tep_href_link(FILENAME_DEFINE_LANGUAGE) . '" class="menuBoxContentLink">' . BOX_TOOLS_DEFINE_LANGUAGE . '</a><br>' .
'<a href="' . tep_href_link(FILENAME_PAGE_MANAGER) . '" class="menuBoxContentLink">' . BOX_TOOLS_PAGE_MANAGER . '</a><br>' .
'<a href="' . tep_href_link(FILENAME_EMAIL_ORDER_TEXT, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_EMAIL_ORDER_TEXT . '</a><br>' . 
'<a href="' . tep_href_link(FILENAME_FILE_MANAGER) . '" class="menuBoxContentLink">' . BOX_TOOLS_FILE_MANAGER . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_MAIL) . '" class="menuBoxContentLink">' . BOX_TOOLS_MAIL . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_NEWSLETTERS) . '" class="menuBoxContentLink">' . BOX_TOOLS_NEWSLETTER_MANAGER . '</a><br>' .
              //                     '<a href="' . tep_href_link(FILENAME_PDF_CATALOGUE) . '" class="menuBoxContentLink">' . BOX_TOOLS_PDF_CATALOGUE . '</a><br>' .
// '<a href="' . tep_href_link(FILENAME_PDF_DEFINE_INTRO) . '" class="menuBoxContentLink">' . BOX_INFORMATION_PDF_DEFINE_INTRO . '</a><br>' .
// '<a href="' . tep_href_link(FILENAME_PDF_LINK) . '"class="menuBoxContentLink" target="_blank" >' . BOX_TOOLS_PDF_LINK. '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_SERVER_INFO) . '" class="menuBoxContentLink">' . BOX_TOOLS_SERVER_INFO . '</a><br>' .       
  								   '<a href="' . tep_href_link(FILENAME_STATS_LOW_STOCK, '', 'NONSSL') . '">' . BOX_REPORTS_STOCK_LEVEL . '</a><br>'.
                                   '<a href="' . tep_href_link(FILENAME_WHOS_ONLINE) . '" class="menuBoxContentLink">' . BOX_TOOLS_WHOS_ONLINE . '</a>');

  
}

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- tools_eof //-->
