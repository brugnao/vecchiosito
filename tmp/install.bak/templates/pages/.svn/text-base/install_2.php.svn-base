<?php
/*
  $Id: install_2.php,v 1.7 2003/07/12 08:10:08 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>

<p class="pageTitle">Nuova Installazione</p>

<p><b>Database Import</b></p>

<?php
  if (isset($HTTP_POST_VARS['DB_SERVER']) && !empty($HTTP_POST_VARS['DB_SERVER']) && isset($HTTP_POST_VARS['DB_TEST_CONNECTION']) && ($HTTP_POST_VARS['DB_TEST_CONNECTION'] == 'true')) {
    $db = array();
    $db['DB_SERVER'] = trim(stripslashes($HTTP_POST_VARS['DB_SERVER']));
    $db['DB_SERVER_USERNAME'] = trim(stripslashes($HTTP_POST_VARS['DB_SERVER_USERNAME']));
    $db['DB_SERVER_PASSWORD'] = trim(stripslashes($HTTP_POST_VARS['DB_SERVER_PASSWORD']));
    $db['DB_DATABASE'] = trim(stripslashes($HTTP_POST_VARS['DB_DATABASE']));

    $db_error = false;
    osc_db_connect($db['DB_SERVER'], $db['DB_SERVER_USERNAME'], $db['DB_SERVER_PASSWORD']);

    if ($db_error == false) {
      osc_db_test_create_db_permission($db['DB_DATABASE']);
    }

    if ($db_error != false) {
?>
<form name="install" action="install.php?step=2" method="post">

<table width="95%" border="0" cellpadding="2" class="formPage">
  <tr>
    <td>
      <p>Il test di connessione al database <b>NON</b> &egrave; avvenuto con successo.</p>
      <p>Ecco il messaggio di errore:</p>
      <p class="boxme"><?php echo $db_error; ?></p>
      <p>Clicca sul pulsante <i>Back</i> per rivedere i dati relativi al database.</p>
      <p>Per ulteriori informazioni su come creare e configurare il database consulta il provider del tuo hosting.</p>
    </td>
  </tr>
</table>

<?php
      reset($HTTP_POST_VARS);
      while (list($key, $value) = each($HTTP_POST_VARS)) {
        if (($key != 'x') && ($key != 'y') && ($key != 'DB_TEST_CONNECTION')) {
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

<p>&nbsp;</p>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><a href="index.php"><img src="images/button_cancel.gif" border="0" alt="Cancel"></a></td>
    <td align="center"><input type="image" src="images/button_back.gif" border="0" alt="Back"></td>
  </tr>
</table>

</form>

<?php
    } else {
      $script_filename = getenv('PATH_TRANSLATED');
      if (empty($script_filename)) {
        $script_filename = getenv('SCRIPT_FILENAME');
      }

      $script_filename = str_replace('\\', '/', $script_filename);
      $script_filename = str_replace('//', '/', $script_filename);

      $dir_fs_www_root_array = explode('/', dirname($script_filename));
      $dir_fs_www_root = array();
      for ($i=0, $n=sizeof($dir_fs_www_root_array)-1; $i<$n; $i++) {
        $dir_fs_www_root[] = $dir_fs_www_root_array[$i];
      }
      $dir_fs_www_root = implode('/', $dir_fs_www_root) . '/';
?>

<form name="install" action="install.php?step=3" method="post">

<table width="95%" border="0" cellpadding="2" class="formPage">
  <tr>
    <td>
      <p>Il test di connessione al tuo database &egrave; stato effettuato con <b>successo</b>.</p>
      <p>Prosegui l'installazione con l'importazione dei dati di esempio.</p>
      <p>E' importante che questa procedura non venga interrotta altrimenti il database potrebbe risultare corrotto.</p>
     </td>
  </tr>
</table>

<?php
      reset($HTTP_POST_VARS);
      while (list($key, $value) = each($HTTP_POST_VARS)) {
        if (($key != 'x') && ($key != 'y') && ($key != 'DB_TEST_CONNECTION')) {
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

<p>&nbsp;</p>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><a href="index.php"><img src="images/button_cancel.gif" border="0" alt="Cancel"></a></td>
    <td align="center"><input type="image" src="images/button_continue.gif" border="0" alt="Continue"></td>
  </tr>
</table>

</form>

<?php
    }
  } else {
?>

<form name="install" action="install.php?step=2" method="post">

<p><b>Inserisci i dati per l'installazione del database:</b></p>

<table width="95%" border="0" cellpadding="2" class="formPage">
  <tr>
    <td width="30%" valign="top">Database Server:</td>
    <td width="70%" class="smallDesc">
      <?php echo osc_draw_input_field( 'DB_SERVER','', '', 'value="localhost"'); ?>
      <img src="images/layout/help_icon.gif" onClick="toggleBox('dbHost');"><br>
      <div id="dbHostSD">Hostame o IP-address del database server</div>
      <div id="dbHost" class="longDescription">Il database server pu&ograve; essere sia nella forma di hostname che di IP. In generale il nome "localhost" dovrebbe essere buono per la configurazione. Nel caso non funzioni ecco di seguito l'host name e l'ip del tuo server:
      <br>Hostname: <? echo ($_SERVER['SERVER_NAME']); ?>
      <br>IP: <? echo ($_SERVER['SERVER_ADDR']); ?>
      </div>
    </td>
  </tr>
  <tr>
    <td width="30%" valign="top">Username:</td>
    <td width="70%" class="smallDesc">
      <?php echo osc_draw_input_field('DB_SERVER_USERNAME'); ?>
      <img src="images/layout/help_icon.gif"  onClick="toggleBox('dbUser');"><br>
      <div id="dbUserSD">Database username</div>
      <div id="dbUser" class="longDescription">L'utente utilizzato per la connessione al database. <br><br>n.b.: Create e Drop permissions <b>sono richieste</b> in questa fase dell'installazione.</div>
    </td>
  </tr>
  <tr>
    <td width="30%" valign="top">Password:</td>
    <td width="70%" class="smallDesc">
      <?php echo osc_draw_password_field('DB_SERVER_PASSWORD'); ?>
      <img src="images/layout/help_icon.gif" onClick="toggleBox('dbPass');"><br>
      <div id="dbPassSD">Database password</div>
      <div id="dbPass" class="longDescription">La password &egrave; usata in coppia con l'user name per l'accesso al database.</div>
    </td>
  </tr>
  <tr>
    <td width="30%" valign="top">Database Name:</td>
    <td width="70%" class="smallDesc">
      <?php echo osc_draw_input_field('DB_DATABASE'); ?>
      <img src="images/layout/help_icon.gif" onClick="toggleBox('dbName');"><br>
      <div id="dbNameSD">Database Name</div>
      <div id="dbName" class="longDescription">Il nome del db per gestire i dati. Un esempio pu&ograve; essere 'osCommerce'.</div>
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
      <?php echo osc_draw_radio_field('STORE_SESSIONS', 'files', true); ?>&nbsp;Files&nbsp;&nbsp;<?php echo osc_draw_radio_field('STORE_SESSIONS', 'mysql'); ?>&nbsp;Database&nbsp;&nbsp;
      <img src="images/layout/help_icon.gif" onClick="toggleBox('dbSess');"><br>
      <div id="dbSessSD"></div>
      <div id="dbSess" class="longDescription">Le sessioni degli utenti del sito possono essere gestire sia come dati che su file nel server.<br><br>Nota: la gestione delle sessoni su database pu&ograve; impattare sulle performance del server nel caso di siti con molte visite giornaliere.</td></div>
    </td>
  </tr>
</table>

<p>&nbsp;</p>

<table border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td align="center"><a href="index.php"><img src="images/button_cancel.gif" border="0" alt="Cancel"></a></td>
    <td align="center"><input type="image" src="images/button_continue.gif" border="0" alt="Continue"></td>
  </tr>
</table>

<?php
  reset($HTTP_POST_VARS);
  while (list($key, $value) = each($HTTP_POST_VARS)) {
    if (($key != 'x') && ($key != 'y') && ($key != 'DB_SERVER') && ($key != 'DB_SERVER_USERNAME') && ($key != 'DB_SERVER_PASSWORD') && ($key != 'DB_DATABASE') && ($key != 'USE_PCONNECT') && ($key != 'STORE_SESSIONS') && ($key != 'DB_TEST_CONNECTION')) {
      if (is_array($value)) {
        for ($i=0; $i<sizeof($value); $i++) {
          echo osc_draw_hidden_field($key . '[]', $value[$i]);
        }
      } else {
        echo osc_draw_hidden_field($key, $value);
      }
    }
  }

  echo osc_draw_hidden_field('DB_TEST_CONNECTION', 'true');
?>

</form>

<?php
  }
?>
