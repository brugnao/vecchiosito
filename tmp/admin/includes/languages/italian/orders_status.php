<?php
/*
  $Id: orders_status.php,v 1.5 2002/01/29 14:43:00 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce 

  Released under the GNU General Public License 
*/

define('HEADING_TITLE', 'Stato Ordini');

define('TABLE_HEADING_ORDERS_STATUS', 'Stato Ordini');
define('TABLE_HEADING_PUBLIC_STATUS', 'Stato Pubblico');
define('TABLE_HEADING_DOWNLOADS_STATUS', 'Stato Downloads');
define('TABLE_HEADING_ACTION', 'Azione');

define('TEXT_INFO_EDIT_INTRO', 'Effettua i cambiamenti necessari');
define('TEXT_INFO_ORDERS_STATUS_NAME', 'Stato Ordini:');
define('TEXT_INFO_INSERT_INTRO', 'Inserisci il nuovo stato dell\'Ordine con i relativi dati');
define('TEXT_INFO_DELETE_INTRO', 'Sicuro di voler cancellare lo stato dell\' Ordine?');
define('TEXT_INFO_HEADING_NEW_ORDERS_STATUS', 'Nuovo Stato');
define('TEXT_INFO_HEADING_EDIT_ORDERS_STATUS', 'Modifica Stato');
define('TEXT_INFO_HEADING_DELETE_ORDERS_STATUS', 'Cancella Stato');

define('TEXT_SET_PUBLIC_STATUS', 'Mostrare l\'ordine ai clienti a questo livello dello stato');
define('TEXT_SET_DOWNLOADS_STATUS', 'Permettere i download di prodotti virtuali a questo livello dello stato dell\'ordine');

define('ERROR_REMOVE_DEFAULT_ORDER_STATUS', 'Errore: Lo stato dell\' ordine impostato di default non pu&ograve; essere cancellato. Abilita un altro Stato di default e riprova.');
define('ERROR_STATUS_USED_IN_ORDERS', 'Errore: Questo stato dell\'ordine &egrave; correntemente usato negli Ordini.');
define('ERROR_STATUS_USED_IN_HISTORY', 'Errore: Questo stato dell\'ordine &egrave; correntemente usato nella Cronologia degli Stati.');
?>