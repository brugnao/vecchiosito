<?php
/*
  $Id: header.php,v 1.19 2002/04/13 16:11:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  if ($messageStack->size > 0) {
    echo $messageStack->output();
  }
			// link sito di supporto 
			if (file_exists(DIR_FS_CATALOG . DIR_WS_INCLUDES ."oem.php"))
			{
				$support_site = "http://" .  OEM_SITE_URL;
				
			}
			else  {	$support_site = 'http://www.modulioscommerce.com';
			}
			 ?>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php echo tep_image(DIR_WS_IMAGES . 'oscommerce.gif', 'osCommerce', '204', '50'); ?></td>
    <td align="right"><?php 
    
    echo '<a href="'.$support_site.'" target="_blank">' . tep_image(DIR_WS_IMAGES . 'header_support.gif', HEADER_TITLE_SUPPORT_SITE, '50', '50') . '</a>&nbsp;&nbsp;<a href="' . tep_catalog_href_link() . '">' . tep_image(DIR_WS_IMAGES . 'header_checkout.gif', HEADER_TITLE_ONLINE_CATALOG, '53', '50') . '</a>&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'header_administration.gif', HEADER_TITLE_ADMINISTRATION, '50', '50') . '</a>'; ?>&nbsp;&nbsp;</td>
  </tr>
  <tr class="headerBar">
    <td class="headerBarContent">&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '" class="headerLink">' . HEADER_TITLE_TOP . '</a>'; ?></td>
 
   
    <td class="headerBarContent" align="right"><?php echo '<a href="'.$support_site.'" class="headerLink">' . HEADER_TITLE_SUPPORT_SITE . '</a> &nbsp;|&nbsp; <a href="' . tep_catalog_href_link() . '" class="headerLink">' . HEADER_TITLE_ONLINE_CATALOG . '</a> &nbsp;|&nbsp; <a href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '" class="headerLink">' . HEADER_TITLE_ADMINISTRATION . '</a>'; ?>&nbsp;&nbsp;</td>
  </tr>
</table>