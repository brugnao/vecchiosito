<?php
/*
  $Id: footer.php,v 1.12 2003/02/17 16:54:12 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td align="center" class="smallText">
<?php
/*
  The following copyright announcement is in compliance
  to section 2c of the GNU General Public License, and
  thus can not be removed, or can only be modified
  appropriately.

  For more information please read the following
  Frequently Asked Questions entry on the osCommerce
  support site:

  http://www.oscommerce.com/community.php/faq,26/q,50

  Please leave this comment intact together with the
  following copyright announcement.
*/
if (file_exists(DIR_FS_CATALOG . DIR_WS_INCLUDES ."oem.php"))
			{
				$support_site = "http://" .  OEM_SITE_URL;
			?>
	<tr>
    <td align="center" class="smallText">Powered by <a href="<? echo $support_site ?>" target="_blank"><? echo OEM_SITE_URL ?></a></td>
  </tr>
</table>
			<?
			}
else 
{
?>
E-Commerce Engine Copyright &copy; 2003 <a href="http://www.oscommerce.com" target="_blank">osCommerce</a><br>
osCommerce provides no warranty and is redistributable under the <a href="http://www.fsf.org/licenses/gpl.txt" target="_blank">GNU General Public License</a>
    </td>
  </tr>
  <tr>
    <td><?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '5'); ?></td>
  </tr>
  <tr>
    <td align="center" class="smallText">Powered by <a href="http://www.oscommerce.it" target="_blank">osCommerce.it</a></td>
  </tr>
</table>
			<?
			}