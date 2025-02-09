<?php
/*
  $Id: index.php,v 1.1 2003/06/11 17:38:00 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce 

  Released under the GNU General Public License 
*/

define('TEXT_MAIN', 'PromoWebStudio.Net ti da il benvenuto sul tuo nuovo negozio online, <b>i prodotti visualizzati sono solo un esempio, puoi procedere fin da ora al caricamento dei tuoi articoli</b>. Tutte le informazioni sui prodotti sono esposte col solo intento dimostrativo.<br><br>Per qualsiasi problema puoi rivolgerti al nostro supporto tecnico <b><a href="mailto:support@promowebstudio.net">support@promowebstudio.net</a></b>. Questo negozio &egrave; basato su <font color="#f0000"><b>' . PROJECT_VERSION . '</b></font>. Questa versione &egrave; stata tradotta da <a href="http://www.promowebstudio.net">PromoWebStudio.Net</a>. Ti ricordiamo che per qualsiasi evoluzione del tuo negozio puoi contattarci e avere un preventivo, sia per la grafica che per lo svilupop di moduli aggiuntivi. Ti ringraziamo fin d\'ora per la fiducia dimostrata nei nostri servizi. <br><br> Per modificare questo testo devi editare il file index.php nella dir /includes/languages/italian/ del tuo account. ');
define('TABLE_HEADING_NEW_PRODUCTS', 'Nuovi prodotti per %s');
define('TABLE_HEADING_UPCOMING_PRODUCTS', 'Prodotti in arrivo');
define('TABLE_HEADING_INCOMINGS', 'In Arrivo');
define('TABLE_HEADING_DATE_EXPECTED', 'Data di arrivo');

if ( ($category_depth == 'products') || (isset($HTTP_GET_VARS['manufacturers_id'])) ) {
    // AVAILABILITY start
  define('TABLE_HEADING_AVAILABILITY','Disponibilit&agrave;');
  define('TEXT_AVAILABILITY_GREEN','Disp. piena');
  define('TEXT_AVAILABILITY_YELLOW','Disp. scarsa');
  define('TEXT_AVAILABILITY_RED','Su ordinazione');
  define('TEXT_AVAILABILITY_SCHEDULED','In arrivo');
    // AVAILABILITY stop
  define('HEADING_TITLE','' );//'Vediamo cosa c\'&egrave; qui');
  define('TABLE_HEADING_IMAGE', '');
  define('TABLE_HEADING_MODEL', 'Modello');
  define('TABLE_HEADING_PRODUCTS', 'Nome prodotto');
  define('TABLE_HEADING_MANUFACTURER', 'Produttore');
  define('TABLE_HEADING_QUANTITY', 'Quantit&agrave;');
  define('TABLE_HEADING_PRICE', 'Prezzo');
  define('TABLE_HEADING_WEIGHT', 'Dimensioni');
  define('TABLE_HEADING_PRICE_COMMISSION', 'Ricarico');
  define('TABLE_HEADING_BUY_NOW', 'Acquista adesso');
  define('TEXT_NO_PRODUCTS', 'Non ci sono prodotti in questa categoria.');
  define('TEXT_NO_PRODUCTS2', 'Non ci sono prodotti per questo produttore.');
  define('TEXT_NUMBER_OF_PRODUCTS', 'Numero di prodotti: ');
  define('TEXT_SHOW', '<b>Mostra:</b>');
  define('TEXT_BUY', 'Acquista 1 \'');
  define('TEXT_NOW', '\' Ora');
  define('TEXT_ALL_CATEGORIES', 'Tutte le categoria');
  define('TEXT_ALL_MANUFACTURERS', 'Tutti i produttori');
} elseif ($category_depth == 'top') {
  define('HEADING_TITLE', 'Cosa c\' &egrave; di nuovo?');
} elseif ($category_depth == 'nested') {
  define('HEADING_TITLE', 'Categorie');
}
?>
