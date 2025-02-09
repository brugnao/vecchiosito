<?php
/*
  $Id: install_6.php,v 1.2 2003/07/12 08:10:08 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<p class="pageTitle">Nuova Installazione</p>

<p><b>osCommerce Configuration</b></p>

<form name="install" action="install.php?step=7" method="post">

<table width="95%" border="0" cellpadding="2" class="formPage">
  <tr>
    <td width="30%" valign="top">Database Server:</td>
    <td width="70%" class="smallDesc">
      <?php echo osc_draw_input_field('DB_SERVER'); ?>
      <img src="images/layout/help_icon.gif" onClick="toggleBox('dbHost');"><br>
      <div id="dbHostSD">Hostame o IP del database server</div>
      <div id="dbHost" class="longDescription">The database server can be in the form of a hostname, such as db1.myserver.com, or as an IP-address, such as 192.168.0.1</div>
    </td>
  </tr>
  <tr>
    <td width="30%" valign="top">Username:</td>
    <td width="70%" class="smallDesc">
      <?php echo osc_draw_input_field('DB_SERVER_USERNAME'); ?>
      <img src="images/layout/help_icon.gif"  onClick="toggleBox('dbUser');"><br>
      <div id="dbUserSD">Database username</div>
      <div id="dbUser" class="longDescription">The username used to connect to the database server. An example username is 'mysql_10'.<br><br>Note: Create and Drop permissions <b>are not required</b> for the general use of osCommerce.</div>
    </td>
  </tr>
  <tr>
    <td width="30%" valign="top">Password:</td>
    <td width="70%" class="smallDesc">
      <?php echo osc_draw_password_field('DB_SERVER_PASSWORD'); ?>
      <img src="images/layout/help_icon.gif" onClick="toggleBox('dbPass');"><br>
      <div id="dbPassSD">Database password</div>
      <div id="dbPass" class="longDescription">La password è usata in coppia con l'user name per l'accesso al database.</div>
    </td>
  </tr>
  <tr>
    <td width="30%" valign="top">Database Name:</td>
    <td width="70%" class="smallDesc">
      <?php echo osc_draw_input_field('DB_DATABASE'); ?>
      <img src="images/layout/help_icon.gif" onClick="toggleBox('dbName');"><br>
      <div id="dbNameSD">Database Name</div>
      <div id="dbName" class="longDescription">Il nome del db per gestire i dati. Un esempio può essere 'osCommerce'.</div>
    </td>
  </tr>
  <tr>
    <td width="30%" valign="top">Persistent Connections:</td>
    <td width="70%" class="smallDesc">
      <?php echo osc_draw_checkbox_field('USE_PCONNECT', 'true'); ?>
      <img src="images/layout/help_icon.gif" onClick="toggleBox('dbConn');"><br>
      <div id="dbConnSD"></div>
      <div id="dbConn" class="longDescription">Abilita le connessioni persistenti del database.<br><br>Nota: Persistent connections dovrebbero essere disabilitate in caso di hosting condiviso.</div>
    </td>
  </tr>
  <tr>
    <td width="30%" valign="top">Gestione delle sessioni:</td>
    <td width="70%" class="smallDesc">
      <?php echo osc_draw_radio_field('STORE_SESSIONS', 'files', (isset($HTTP_POST_VARS['STORE_SESSIONS']) ? '' : true)); ?>&nbsp;Files&nbsp;&nbsp;<?php echo osc_draw_radio_field('STORE_SESSIONS', 'mysql'); ?>&nbsp;Database&nbsp;&nbsp;
      <img src="images/layout/help_icon.gif" onClick="toggleBox('dbSess');"><br>
      <div id="dbSessSD"></div>
      <div id="dbSess" class="longDescription">Le sessioni degli utenti del sito possono essere gestire sia come dati che su file nel server.<br><br>Nota: la gestione delle sessoni su database può impattare sulle performance del server nel caso di siti con molte visite giornaliere.</td></div>
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
    if (($key != 'x') && ($key != 'y') && ($key != 'DB_SERVER') && ($key != 'DB_SERVER_USERNAME') && ($key != 'DB_SERVER_PASSWORD') && ($key != 'DB_DATABASE') && ($key != 'USE_PCONNECT') && ($key != 'STORE_SESSIONS')) {
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
