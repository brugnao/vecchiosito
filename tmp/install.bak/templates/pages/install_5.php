<?php
/*
  $Id: install_5.php,v 1.22 2003/07/09 01:11:06 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  $https_www_address = str_replace('http://', 'https://', $HTTP_POST_VARS['HTTP_WWW_ADDRESS']);
?>

<p class="pageTitle">Nuova Installazione</p>

<p><b>osCommerce Configurtion</b></p>

<form name="install" action="install.php?step=6" method="post">

<p><b>Inserisci le informazioni sul server sicuro (https/ssl):</b></p>

<table width="95%" border="0" cellpadding="2" class="formPage">
  <tr>
    <td width="30%" valign="top">Indirizzo HTTPS:</td>
    <td width="70%" class="smallDesc">
      <?php echo osc_draw_input_field('HTTPS_WWW_ADDRESS', $https_www_address); ?>
      <img src="images/layout/help_icon.gif" onClick="toggleBox('httpsWWW');"><br>
      <div id="httpsWWWSD">L'indirizzo https completo dello store sul server sicuro</div>
      <div id="httpsWWW" class="longDescription">L'indirizzo https dello store, per esempio <i>https://ssl.il-mio-store.com/catalog/</i></div>
    </td>
  </tr>
  <tr>
    <td width="30%" valign="top">Secure Cookie Domain:</td>
    <td width="70%" class="smallDesc">
      <?php echo osc_draw_input_field('HTTPS_COOKIE_DOMAIN', $HTTP_POST_VARS['HTTP_COOKIE_DOMAIN']); ?>
      <img src="images/layout/help_icon.gif" onClick="toggleBox('httpsCookieD');"><br>
      <div id="httpsCookieDSD">IL dominio https per le cookies</div>
      <div id="httpsCookieD" class="longDescription">L'indirizzo completo del dominio del server sicuro per salvare le cookies, per esempio <i>ssl.il-mio-store.com</i></div>
    </td>
  </tr>
  <tr>
    <td width="30%" valign="top">Percorso sul server sicure delle cookies:</td>
    <td width="70%" class="smallDesc">
      <?php echo osc_draw_input_field('HTTPS_COOKIE_PATH', $HTTP_POST_VARS['HTTP_COOKIE_PATH']); ?>
      <img src="images/layout/help_icon.gif" onClick="toggleBox('dbCookieP');"><br>
      <div id="dbCookiePSD">Il persorso sul server sicuro su cui salvare le cookies</div>
      <div id="dbCookieP" class="longDescription">Il percorso in cui includere le cookies sul server sicuro, per esempio <i>/catalog/</i></div>
    </td>
  </tr>
</table>

<p>&nbsp;</p>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><a href="index.php"><img src="images/button_cancel.gif" border="0" alt="Cancel"></a></td>
    <td align="center"><input type="image" src="images/button_continue.gif" border="0" alt="Continue"></td>
  </tr>
</table>

<?php
  reset($HTTP_POST_VARS);
  while (list($key, $value) = each($HTTP_POST_VARS)) {
    if (($key != 'x') && ($key != 'y')) {
      if (is_array($value)) {
        for ($i=0; $i<sizeof($value); $i++) {
          echo osc_draw_hidden_field($key . '[]', $value[$i]);
        }
      } else {
        echo osc_draw_hidden_field($key, $value);
      }
    }
  }
?>

</form>
