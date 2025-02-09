<?php
/*
$Id: easypolulate.php,v 2.76g-MS2-PWS 2008/03/26 23:25:41 hpdl Exp $

Definizione del file di linguaggio inglese ed italiano
elaborata da Riccardo Roscilli giulio.dambrosio@poste.it per PowerWebStudio
Rilasciata sotto GNU General Public License
*/
define('HEADING_TITLE_EP', 'Easy Populate %s - Lingua di Default : %s(%s)');
define('TEXT_BACKUP_DATABASE_EP','Eseguire un backup del database');
define('TEXT_WARNING_DATABASE_OPERATIONS_EP','prima di eseguire qualsiasi operazione con EasyPopulate !');

define('TEXT_HEADING_UPLOAD_AND_IMPORT_EP','Upload ed Importazione file EP');

define('TEXT_OPTION_UPLOAD_NORMAL_EP','Normale');
define('TEXT_OPTION_UPLOAD_NEW_ONLY_EP','Solo Nuovi Prodotti');
define('TEXT_OPTION_UPLOAD_UPDATE_ONLY_EP','Solo Aggiornamento');

define('TEXT_CREATING_TEMP_FILE_EP','Creazione del file EP_Split');
define('TEXT_ADDED_RECORDS_CLOSING_FILE_EP','Aggiunti %s elementi e chiusura del file... ');
define('TEXT_YOU_CAN_DOWNLOAD_YOUR_SPLIT_FILES_EP','Si possono scaricare i files suddivisi tramite la sezione Tools/Files, nella directory \'%s\' ');

define('TEXT_HEADING_IMPORT_EP_FROM_TEMP','Importazione del file EP dalla Directory Temporanea');
define('TEXT_DOWNLOAD_TYPE_ON_THE_FLY_EP','Download diretto');
define('TEXT_DOWNLOAD_TYPE_SAVE_AND_DOWNLOAD_EP','Creazione e  Download');
define('TEXT_DOWNLOAD_TYPE_SAVE_IN_TEMP_EP','Creazione nella directory temporanea');

define('TEXT_HEADING_UPLOAD_AND_SPLIT_EP','Upload e Suddivisione di un file EP');
define('TEXT_BUTTON_INSERT_INTO_DB_EP','Inserimento nel db');

define('TEXT_HEADING_EXPORT_EP_OR_FROOGLE','Esportazione file prodotti EP o Froogle');
define('TEXT_FILE_TYPE_COMPLETE_EP','Completo');
define('TEXT_FILE_TYPE_CUSTOM_EP','Personalizzato');
define('TEXT_FILE_TYPE_PRICE_QTY_EP','Prezzo/Qt&agrave;');
define('TEXT_FILE_TYPE_CATEGORIES_EP','Categorie');
define('TEXT_FILE_TYPE_ATTRIBUTES_EP','Attributi');
define('TEXT_FILE_TYPE_FROOGLE_EP','Froogle');

define('TEXT_A_EP','di un file di tipo');
define('TEXT_FILE_FORMAT_EP',' %s (il modello &egrave; sempre presente).');

define('TEXT_FIELDNAME_NAME','nome');
define('TEXT_FIELDNAME_DESCRIPTION','descrizione');
define('TEXT_FIELDNAME_URL','url');
define('TEXT_FIELDNAME_IMAGE','immagine');
define('TEXT_FIELDNAME_ATTRIBUTES','attributi');
define('TEXT_FIELDNAME_CATEGORIES','categorie');
define('TEXT_FIELDNAME_MANUFACTURER','produttore');
define('TEXT_FIELDNAME_PRICE','prezzo');
define('TEXT_FIELDNAME_QUANTITY','quantit&agrave;');
define('TEXT_FIELDNAME_WEIGHT','peso');
define('TEXT_FIELDNAME_TAX_CLASS','tasse');
define('TEXT_FIELDNAME_AVAILABLE','disponibilit&agrave;');
define('TEXT_FIELDNAME_DATE_ADDED','data inserimento');
define('TEXT_FIELDNAME_STATUS','stato');
define('TEXT_STATUS_ACTIVE','attivo');
define('TEXT_STATUS_DISABLED','disabilitato');

define('LABEL_FILTER_BY_EP','filtra per: ');
define('TEXT_OPTION_CATEGORY','- categoria -');
define('TEXT_OPTION_STATUS','- stato -');
define('TEXT_OPTION_MANUFACTURER','- produttore -');
define('TEXT_BUTTON_BUILD_FILE','Creazione File');

define('TEXT_HEADING_QUICK_LINKS','Collegamenti Rapidi');

define('TEXT_HEADING_CREATE_THEN_DOWNLOAD','Creazione e  Download Files');
define('TEXT_DESCRIPTION_CREATE_THEN_DOWNLOAD','Creazione dell\'intero file nella memoria del server, quindi scaricamento in streaming al completamento.');
define('TEXT_DOWNLOAD_FILE_WITH_SPPC',' con contrib SPPC');
define('TEXT_DOWNLOAD_COMPLETE_FILE_EP','Download file <b>Completo %s</b> (%s)');
define('TEXT_DOWNLOAD_EXTRA_FIELDS_EP','Download file con <b>Extra Fields</b> (%s)');
define('TEXT_DOWNLOAD_MODEL_PRICE_QTY_EP','Download file  <b>Modello/Prezzo/Qt&agrave; %s</b> (%s)');
define('TEXT_DOWNLOAD_MODEL_CATEGORIES_EP','Download file  <b>Modello/Categoria</b> (%s)');
define('TEXT_DOWNLOAD_FROOGLE_EP','Download file <b>Froogle</b> (%s)');
define('TEXT_DOWNLOAD_MODEL_ATTRIBUTES_EP','Download file  <b>Modello/Attributi</b> (%s)');

define('TEXT_HEADING_CREATE_TEMP_FILES_EP','Creazione dei Files nella directory temporanea');
define('TEXT_DESCRIPTION_CREATE_TEMP_FILES_EP','Creazione dell\'intero file nella memoria del server e memorizzazione nella directory temporanea al completamento.');
define('TEXT_CREATE_COMPLETE_FILE_EP','Creazione file <b>Completo</b> (%s) nella dir. temp.');
define('TEXT_CREATE_MODEL_PRICE_QTY_FILE_EP','Creazione file  <b>Modello/Prezzo/Qt&agrave;</b> (%s) nella dir. temp.');
define('TEXT_CREATE_MODEL_CATEGORIES_EP','Creazione file  <b>Modello/Categoria</b> (%s) nella dir. temp.');
define('TEXT_CREATE_FROOGLE_FILE_EP','Creazione file <b>Froogle</b> (%s) nella dir. temp.');
define('TEXT_CREATE_MODEL_ATTRIBUTES_EP','Creazione file  <b>Modello/Attributi</b> (%s) nella dir. temp.');

define('TEXT_OPTION_ON','<b>attivato</b>');
define('TEXT_OPTION_OFF','<i>disattivato</i>');
define('TEXT_OPTION_TRUE','<b>si</b>');
define('TEXT_OPTION_FALSE','<i>no</i>');
define('TEXT_HEADING_SETTINGS_INFO','Impostazioni &amp; Informazioni');
define('TEXT_SETTINGS_TEMP_DIRECTORY','Directory Temporanea:<br/>%s');
define('TEXT_SETTINGS_TEMP_DIR_WRITEABLE','La Directory Temporanea &egrave; accessibile in scrittura');
define('TEXT_SETTINGS_TEMP_DIR_UNWRITEABLE','La Directory Temporanea NON &egrave; accessibile in scrittura');
define('TEXT_SETTINGS_SPLIT_FILES','Suddivisione files ogni: %s elementi');
define('TEXT_SETTINGS_MODEL_FIELDSIZE','Dimensione campo Modello: %s');
define('TEXT_SETTINGS_TAX_INCLUDED','Prezzi tasse incluse: %s');
define('TEXT_SETTINGS_CALC_PRECISION','Precisione di Calcolo: %s');
define('TEXT_SETTINGS_REPLACE_QUOTES','Sostituzione Apici: %s');
define('TEXT_SETTINGS_FIELD_DELIMITER','Separatore Campi: ');
define('TEXT_SETTINGS_EXCEL_SAFE','Output compatibile Excel: %s');
define('TEXT_SETTINGS_PRESERVE_WHITESPACE','Preservare tab/cr/lf: %s');
define('TEXT_SETTINGS_CATEGORY_DEPTH','Profondit&agrave; Categorie: %s');
define('TEXT_SETTINGS_ENABLE_ATTRIBUTES','Abilitazione attributi: %s');
define('TEXT_SETTINGS_SEF_URLS','SEF Froogle URLS: %s');
define('TEXT_SETTINGS_MORE_PICS_6_SUPPORT','More Pics: %s');
define('TEXT_SETTINGS_UNKNOWN_ADD_IMAGES_SUPPORT','Unknown Pics: %s');
define('TEXT_SETTINGS_HTC_SUPPORT','HTC: %s');
define('TEXT_SETTINGS_SPPC_SUPPORT','SPPC: %s');
define('TEXT_SETTINGS_EXTRA_FIELDS_SUPPORT','Extra Fields: %s');
define('TEXT_SETTINGS_HOW_TO_CHANGE','Per modificare le impostazioni, visitare la sezione <a href="%s" target="_blank">Configurazione/EasyPopulate</a>.');

//////////////////////////////////////////////////////////////
// Chiavi di configurazione
define('EP_ERROR_OPENING_FILE_READ','Errore nel tentativo di aprire il file in lettura \'%s\'<br/>Lo script viene terminato.');
define('EP_ERROR_OPENING_FILE_WRITE','Errore nel tentativo di aprire il file in scrittura \'%s\'<br/>Lo script viene terminato.');

//////////////////////////////////////////////////////////////
// Chiavi di configurazione
define('EP_CURRENT_VERSION_CONFIG_TITLE','EasyPopulate');
define('EP_CURRENT_VERSION_CONFIG_DESC','Versione di EasyPopulate');
define('EP_SHOW_EP_SETTINGS_CONFIG_TITLE','Mostra le impostazioni');
define('EP_SHOW_EP_SETTINGS_CONFIG_DESC','Se impostato su \'1\' mostra le impostazioni nella pagina principale di EasyPopulate');
define('EP_TEMP_DIRECTORY_CONFIG_TITLE','Directory di Esportazione');
define('EP_TEMP_DIRECTORY_CONFIG_DESC','Inserire il percorso completo della directory dove salvare i files esportati');
define('EP_SPLIT_MAX_RECORDS_CONFIG_TITLE','Suddivisione Files Esportati');
define('EP_SPLIT_MAX_RECORDS_CONFIG_DESC','Inserire la lunghezza di ogni file di esportazione suddiviso (numero di righe=prodotti)');
define('EP_DEFAULT_IMAGE_MANUFACTURER_CONFIG_TITLE','Immagine Produttore [default]');
define('EP_DEFAULT_IMAGE_MANUFACTURER_CONFIG_DESC','Inserire il path relativo alla directory images per il produttore, in caso non esista l\'immagine');
define('EP_DEFAULT_IMAGE_PRODUCT_CONFIG_TITLE','Immagine Prodotto [default]');
define('EP_DEFAULT_IMAGE_PRODUCT_CONFIG_DESC','Inserire il path relativo alla directory images per il prodotto, in caso non esista l\'immagine');
define('EP_DEFAULT_IMAGE_CATEGORY_CONFIG_TITLE','Immagine Categoria [default]');
define('EP_DEFAULT_IMAGE_CATEGORY_CONFIG_DESC','Inserire il path relativo alla directory images per la categoria, in caso non esista l\'immagine');
define('EP_INACTIVATE_ZERO_QUANTITIES_CONFIG_TITLE','Saltare Prodotti Esauriti');
define('EP_INACTIVATE_ZERO_QUANTITIES_CONFIG_DESC','Se impostato su \'1\', i prodotti con quantit&agrave; pari o inferiore a 0 saranno considerati disattivati');
define('EP_PRICE_WITH_TAX_CONFIG_TITLE','Prezzi Tasse Incluse');
define('EP_PRICE_WITH_TAX_CONFIG_DESC','Se impostato su \'1\', i prezzi verranno gestiti come comprensivi delle tasse, sia in importazione che in esportazione');
define('EP_PRECISION_CONFIG_TITLE','Decimali nei Prezzi');
define('EP_PRECISION_CONFIG_DESC','Impostare il numero di decimali dopo la virgola da calcolare nei prezzi durante l\'inserimento nel database');
define('EP_MAX_CATEGORIES_CONFIG_TITLE','Massimo numero di livelli Categorie');
define('EP_MAX_CATEGORIES_CONFIG_DESC','Impostare il massimo numero di livelli di categorie nel percorso dei prodotti');
define('EP_TEXT_ACTIVE_CONFIG_TITLE','Campo v_status: valore per i prodotti attivi');
define('EP_TEXT_ACTIVE_CONFIG_DESC','Impostare il valore da inserire nella colonna v_status per i prodotti attivi');
define('EP_TEXT_INACTIVE_CONFIG_TITLE','Campo v_status: valore per i prodotti disattivati');
define('EP_TEXT_INACTIVE_CONFIG_DESC','Impostare il valore da inserire nella colonna v_status per i prodotti disattivati');
define('EP_DELETE_IT_CONFIG_TITLE','Campo v_status: valore per i prodotti da eliminare');
define('EP_DELETE_IT_CONFIG_DESC','Impostare il valore da inserire nella colonna v_status per i prodotti da eliminare');
define('EP_FROOGLE_CURRENCY_CONFIG_TITLE','Froogle: valuta di default');
define('EP_FROOGLE_CURRENCY_CONFIG_DESC','Selezionare la valuta da utilizzare per le esportazioni per Froogle');
define('EP_SEPARATOR_CONFIG_TITLE','Separatore dei campi');
define('EP_SEPARATOR_CONFIG_DESC','Selezionare il carattere separatore fra i campi.');
?>
