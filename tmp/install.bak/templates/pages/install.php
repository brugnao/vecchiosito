<?php
/*
  $Id: install.php,v 1.8 2003/07/09 01:11:06 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>

<p class="pageTitle">Nuova Installazione</p>

<form name="install" action="install.php?step=2" method="post">

<p><b>
Scegli il tipo di installazione:</b></p>

<table width="95%" border="0" cellpadding="2" class="formPage">
  <tr>
    <td width="30%" valign="top">Importa i dati nel database:</td>
    <td width="70%" class="smallDesc">
      <?php echo osc_draw_checkbox_field('install[]', 'database', true); ?>
      <img src="images/layout/help_icon.gif" onClick="toggleBox('dbImport');"><br>
      <div id="dbImportSD">Aggiunge dati di esempio nel database</div>
      <div id="dbImport" class="longDescription">Spuntando questa casella il programma creerà la struttura del database ed importerà alcuni dati di esempio. (opzione richiesta se è una nuova installazione)</div>
    </td>
  </tr>
  <tr>
    <td width="30%" valign="top">Confgurazione Automatica (consigliata):</td>
    <td width="70%" class="smallDesc">
      <?php echo osc_draw_checkbox_field('install[]', 'configure', true); ?>
      <img src="images/layout/help_icon.gif" onClick="toggleBox('autoConfig');"><br>
      <div id="autoConfigSD">Salva i valori della configuurazione </div>
      <div id="autoConfig" class="longDescription">Spuntando questa casella il programma salverà tutte le voci di configurazione immesse durante la fase di installazione nei file di confgurazione.</div>
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

</form>
