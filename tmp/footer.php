<?php
/*
  $Id: footer.php,v 1.1 2003/09/08 19:26:06 jhtalk Exp jhtalk $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  // STS: ADD
  // Get the output between column_right.php and footer.php
  $sts_block_name = 'columnright2footer';
  require(STS_RESTART_CAPTURE);
  // STS: EOADD

  require(DIR_WS_INCLUDES . 'counter.php');

  // STS: ADD
  $sts_block_name = 'counter';
  require(STS_RESTART_CAPTURE);
  // STS: EOADD

?>
<table border="0" width="100%" cellspacing="0" cellpadding="1">
  <tr class="footer">
    <td class="footer">&nbsp;&nbsp;<?php echo strftime(DATE_FORMAT_LONG); ?>&nbsp;&nbsp;</td>
    <td align="right" class="footer">&nbsp;&nbsp;<?php echo $counter_now . ' ' . FOOTER_TEXT_REQUESTS_SINCE . ' ' . $counter_startdate_formatted; ?>&nbsp;&nbsp;</td>
  </tr>
</table>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" class="smallText">
<?php
/*
  The following copyright announcement can only be
  appropriately modified or removed if the layout of
  the site theme has been modified to distinguish
  itself from the default osCommerce-copyrighted
  theme.

  For more information please read the following
  Frequently Asked Questions entry on the osCommerce
  support site:

  http://www.oscommerce.com/community.php/faq,26/q,50

  Please leave this comment intact together with the
  following copyright announcement.
*/

  echo FOOTER_TEXT_BODY;
  echo 'Powered by <a href="http://www.oscommerce.it" target="_blank">osCommerce.it</a>';
?>
    </td>
  </tr>
</table>
<?php

  // STS: ADD
  $sts_block_name = 'footer';
  require(STS_RESTART_CAPTURE);
  // STS: EOADD
  

  if ($banner = tep_banner_exists('dynamic', '468x50')) {
  	
?>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><?php echo tep_display_banner('static', $banner); ?></td>
  </tr>
</table>
<?php

  // STS: ADD
  $sts_block_name = 'banner';
  require(STS_RESTART_CAPTURE);
  // STS: EOADD
  
  }

?>
