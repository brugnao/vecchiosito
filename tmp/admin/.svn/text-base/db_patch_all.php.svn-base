<?php
/*
 * @filename:	db_patch_all.php
 * @version:	3.00
 * @project:	PSW
 *
 * @author:		Riccardo Roscilli info@oscommerce.it
 * @created:	10/mag/07
 * @modified:	03/Nov/2008
 *
 * @copyright:	2006-2010	PSW
 *
 * 
 * @desc:	
 *
 * @TODO:		
 */
require('includes/application_top.php');

$num_patches=0;
function checkColumn($column,$table){
	global $num_patches;
	$tableQuery=tep_db_query("show columns from $table");
	$found=false;
	while (!$found && $col=tep_db_fetch_array($tableQuery))
	{
		
		$found=$col['Field']==$column;
	}
	if (!$found){
		echo "Colonna '$column' non trovata nella tabella '$table'<br/>\r\n";
		$num_patches++;
	}
	return $found;
}

function checkConfigKey($key)	{
	global $num_patches;
	$query=tep_db_query("select * from ". TABLE_CONFIGURATION ." where configuration_key='$key'");
	if (!tep_db_num_rows($query)){
		echo "Aggiunta Chiave di configurazione '$key'<br/>\r\n";
		$num_patches++;
	}
	return tep_db_num_rows($query);
}
function fieldLength($fieldname,$tablename){
	$found=false;
	$query=tep_db_query("show columns from $tablename");
	while (!$found && $field=tep_db_fetch_array($query)){
		$found = $fieldname==$field['Field'];
		$length=array();
		preg_match("/.*\(([0-9]*)\).*/",$field['Type'],$length);
		$length=$length[1];
	}
	return $found?$length:false;
}

function indexExists($fieldname,$tablename){
	global $num_patches;
	$found=false;
	$query=tep_db_query("show indexes from $tablename");
	while (!$found && $index=tep_db_fetch_array($query)){
		$found = $fieldname==$index['Column_name'];
	}
	if (!$found){
		echo "Indice '$fieldname' non trovato nella tabella '$tablename'<br/>\r\n";
		$num_patches++;
	}
	return $found;
}

// Patch varie per aggiornare all'ultima release di osc 2.2ms
if (true)	{


	// ancora sui gruppi, controlla alcune colonne sulla tabella customers
	
if (!checkColumn('customers_group_id',TABLE_CUSTOMERS)){
		tep_db_query("alter table ".TABLE_CUSTOMERS." add column `customers_group_id` smallint(5) unsigned NOT NULL default '0'");

// crea le tabella per i gruppi se non esistono
 	$create_groups = tep_db_query("CREATE TABLE IF NOT EXISTS `customers_groups` (
			  `customers_group_id` smallint(5) unsigned NOT NULL default '0',
			  `customers_group_name` varchar(32) NOT NULL default '',
			  `customers_group_show_tax` enum('1','0') NOT NULL default '1',
			  `customers_group_tax_exempt` enum('0','1') NOT NULL default '0',
			  `group_payment_allowed` varchar(255) NOT NULL default '',
			  `group_shipment_allowed` varchar(255) NOT NULL default '',
			  `customers_group_default_discount` decimal(6,2) NOT NULL default '0.00',
			  `customers_group_show_prices` enum('1','0') NOT NULL default '1',
			  `customers_group_hidden_prices_msg` varchar(255) NOT NULL default 'Occorre registrarsi per vedere i prezzi',
			  PRIMARY KEY  (`customers_group_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;");

	$create_products_groups = tep_db_query("
			CREATE TABLE IF NOT EXISTS `products_groups` (
			  `customers_group_id` smallint(5) unsigned NOT NULL default '0',
			  `customers_group_price` decimal(15,4) NOT NULL default '0.0000',
			  `products_id` int(11) NOT NULL default '0',
			  `customers_group_discount` decimal(6,2) NOT NULL default '0.00',
			  PRIMARY KEY  (`customers_group_id`,`products_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;
	");
		


}
if (!checkColumn('customers_group_ra',TABLE_CUSTOMERS)){
		tep_db_query("alter table ".TABLE_CUSTOMERS." add column `customers_group_ra` enum('0','1') NOT NULL default '0'");
	}
if (!checkColumn('customers_payment_allowed',TABLE_CUSTOMERS)){
		tep_db_query("alter table ".TABLE_CUSTOMERS." add column  `customers_payment_allowed` varchar(255) NOT NULL default ''");
	}	
if (!checkColumn('customers_shipment_allowed',TABLE_CUSTOMERS)){
		tep_db_query("alter table ".TABLE_CUSTOMERS." add column  `customers_shipment_allowed` varchar(255) NOT NULL default ''");
	}	
if (!checkColumn('customers_paypal_payerid',TABLE_CUSTOMERS)){
		tep_db_query("alter table ".TABLE_CUSTOMERS." add column  `customers_paypal_payerid` varchar(20) default NULL");
	}	
if (!checkColumn('customers_paypal_ec',TABLE_CUSTOMERS)){
		tep_db_query("alter table ".TABLE_CUSTOMERS." add column  `customers_paypal_ec` tinyint(1) unsigned NOT NULL default '0'");
	}	
// aggiunta codice cliente per importazione/esportazione da gestionali
if (!checkColumn('customers_code',TABLE_CUSTOMERS)){
		tep_db_query("alter table ".TABLE_CUSTOMERS." add column  `customers_code`  varchar(50) default NULL");
	}	
	
if (!checkColumn('customers_group_id',TABLE_CUSTOMERS_GROUPS)){
		tep_db_query("alter table ".TABLE_CUSTOMERS_GROUPS." add column `customers_group_id` smallint(5) unsigned NOT NULL default '0'");
	}
	// `customers_group_name` varchar(32) NOT NULL default '',
if (!checkColumn('customers_group_name',TABLE_CUSTOMERS_GROUPS)){
		tep_db_query("alter table ".TABLE_CUSTOMERS_GROUPS." add column `customers_group_name` varchar(32) NOT NULL default ''");
	}
if (!checkColumn('customers_group_show_tax',TABLE_CUSTOMERS_GROUPS)){
		tep_db_query("alter table ".TABLE_CUSTOMERS_GROUPS." add column  `customers_group_show_tax` enum('1','0') NOT NULL default '1'");
	}	
if (!checkColumn('customers_group_tax_exempt',TABLE_CUSTOMERS_GROUPS)){
		tep_db_query("alter table ".TABLE_CUSTOMERS_GROUPS." add column  `customers_group_tax_exempt` enum('1','0') NOT NULL default '1'");
	}
if (!checkColumn('group_payment_allowed',TABLE_CUSTOMERS_GROUPS)){
		tep_db_query("alter table ".TABLE_CUSTOMERS_GROUPS." add column  `group_payment_allowed` varchar(255) NOT NULL default ''");
	}
if (!checkColumn('group_shipment_allowed',TABLE_CUSTOMERS_GROUPS)){
		tep_db_query("alter table ".TABLE_CUSTOMERS_GROUPS." add column  `group_shipment_allowed` varchar(255) NOT NULL default ''");
	}	
if (!checkColumn('customers_group_default_discount',TABLE_CUSTOMERS_GROUPS)){
		tep_db_query("alter table ".TABLE_CUSTOMERS_GROUPS." add column  `customers_group_default_discount` decimal(6,2) NOT NULL default '0.00'");
	}
if (!checkColumn('customers_group_show_prices',TABLE_CUSTOMERS_GROUPS)){
		tep_db_query("alter table ".TABLE_CUSTOMERS_GROUPS." add column  `customers_group_show_prices` enum('1','0') NOT NULL default '1'");
	}
if (!checkColumn('customers_group_hidden_prices_msg',TABLE_CUSTOMERS_GROUPS)){
		tep_db_query("alter table ".TABLE_CUSTOMERS_GROUPS." add column  `customers_group_hidden_prices_msg` varchar(255) NOT NULL default 'Occorre registrarsi per vedere i prezzi'");
	}	
	
//  aggiunta campo categories_status_pc per i comparatori
if (!checkColumn('categories_status_pc',TABLE_CATEGORIES)){
		tep_db_query("alter table ".TABLE_CATEGORIES." add column  `categories_status_pc` tinyint(1) NOT NULL default '1'");
	}	


// campi aggiuntivi per la pagina html produttori
if (!checkColumn('manufacturers_description',TABLE_MANUFACTURERS_INFO)){
		tep_db_query("alter table ".TABLE_MANUFACTURERS_INFO." add column  `manufacturers_description` text");
	}		
	
				
/*
 *			  `customers_group_default_discount` decimal(6,2) NOT NULL default '0.00',
			  `customers_group_show_prices` enum('1','0') NOT NULL default '1',
			  `customers_group_hidden_prices_msg` varchar(255) NOT NULL default 'Occorre registrarsi per vedere i prezzi',
 * 
 */	
	
	
/*customers_group_show_tax
 *  `customers_payment_allowed` varchar(255) NOT NULL default '',
  `customers_shipment_allowed` varchar(255) NOT NULL default '',
  `customers_paypal_payerid` varchar(20) default NULL,
  `customers_paypal_ec` tinyint(1) unsigned NOT NULL default '0',
 */	
	
	$create_pages = tep_db_query ("CREATE TABLE IF NOT EXISTS `pages` (
  `pages_id` int(11) NOT NULL auto_increment,
  `sort_order` int(3) default NULL,
  `status` int(1) NOT NULL default '1',
  `page_type` char(1) default NULL,
  PRIMARY KEY  (`pages_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;");

$test_pages_query = tep_db_query("Select *  from pages");
	
if (tep_db_num_rows($test_pages_query) <= 0) // controlliamo se la tabella è stata già creata o meno
{
	
	
tep_db_query ("INSERT INTO `pages` (`pages_id`, `sort_order`, `status`, `page_type`) VALUES (1, 0, 1, '3');");
tep_db_query ("INSERT INTO `pages` (`pages_id`, `sort_order`, `status`, `page_type`) VALUES (2, 2, 1, '3');");
tep_db_query ("INSERT INTO `pages` (`pages_id`, `sort_order`, `status`, `page_type`) VALUES (3, 1, 1, '3');");
tep_db_query ("INSERT INTO `pages` (`pages_id`, `sort_order`, `status`, `page_type`) VALUES (5, 1, 1, '2');");
tep_db_query ("INSERT INTO `pages` (`pages_id`, `sort_order`, `status`, `page_type`) VALUES (6, 2, 1, '3');");
tep_db_query ("INSERT INTO `pages` (`pages_id`, `sort_order`, `status`, `page_type`) VALUES (8, 4, 1, '3');");
tep_db_query ("INSERT INTO `pages` (`pages_id`, `sort_order`, `status`, `page_type`) VALUES (9, 5, 1, '3');");
tep_db_query ("INSERT INTO `pages` (`pages_id`, `sort_order`, `status`, `page_type`) VALUES (10, 6, 1, '3');");
	

tep_db_query ("CREATE TABLE IF NOT EXISTS `pages_description` (
  `id` int(11) NOT NULL auto_increment,
  `pages_id` int(11) default NULL,
  `pages_title` varchar(64) NOT NULL default '',
  `pages_html_text` text,
  `intorext` char(1) default NULL,
  `externallink` varchar(255) default NULL,
  `link_target` char(1) default NULL,
  `language_id` int(3) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;");


tep_db_query ("INSERT INTO `pages_description` (`id`, `pages_id`, `pages_title`, `pages_html_text`, `intorext`, `externallink`, `link_target`, `language_id`) VALUES (1, 1, 'Index', 'Index page for English pages...This text can be changed from the admin section...', '0', '', '0', 1);");
tep_db_query ("INSERT INTO `pages_description` (`id`, `pages_id`, `pages_title`, `pages_html_text`, `intorext`, `externallink`, `link_target`, `language_id`) VALUES (2, 1, 'Index', 'Index page for Deutsch pages...This text can be changed from the admin section...', '0', '', '0', 2);");
tep_db_query ("INSERT INTO `pages_description` (`id`, `pages_id`, `pages_title`, `pages_html_text`, `intorext`, `externallink`, `link_target`, `language_id`) VALUES (3, 1, 'Index', 'Index page for Espanol pages...This text can be changed from the admin section...', '0', '', '0', 3);");
tep_db_query ("INSERT INTO `pages_description` (`id`, `pages_id`, `pages_title`, `pages_html_text`, `intorext`, `externallink`, `link_target`, `language_id`) VALUES (4, 2, 'Contact Us', 'Contact us page for english language', '0', '', '0', 1);");
tep_db_query ("INSERT INTO `pages_description` (`id`, `pages_id`, `pages_title`, `pages_html_text`, `intorext`, `externallink`, `link_target`, `language_id`) VALUES (5, 2, 'Contact Us', 'Contact Page for Deutsch pages..This text can be changed from admin section.', '0', '', '0', 2);");
tep_db_query ("INSERT INTO `pages_description` (`id`, `pages_id`, `pages_title`, `pages_html_text`, `intorext`, `externallink`, `link_target`, `language_id`) VALUES (6, 2, 'Contact Us', 'Contact Page for Espanol pages..This text can be changed from admin section.', '0', '', '0', 3);");
tep_db_query ("INSERT INTO `pages_description` (`id`, `pages_id`, `pages_title`, `pages_html_text`, `intorext`, `externallink`, `link_target`, `language_id`) VALUES (7, 3, 'Contact Us', 'Chi Siamo pagina in Italiano', '0', '', '0', 1);");
tep_db_query ("INSERT INTO `pages_description` (`id`, `pages_id`, `pages_title`, `pages_html_text`, `intorext`, `externallink`, `link_target`, `language_id`) VALUES (8, 3, 'About Us', 'About Us Deutsch', '0', '', '0', 2);");
tep_db_query ("INSERT INTO `pages_description` (`id`, `pages_id`, `pages_title`, `pages_html_text`, `intorext`, `externallink`, `link_target`, `language_id`) VALUES (9, 3, 'About Us', 'About Us Espanol', '0', '', '0', 3);");
tep_db_query ("INSERT INTO `pages_description` (`id`, `pages_id`, `pages_title`, `pages_html_text`, `intorext`, `externallink`, `link_target`, `language_id`) VALUES (10, 1, 'Index', 'Index page per le pagine in Italiano...Pu&ograve; essere cambiata dall''admin....\r\nby Oscommerce.it', '0', '', '0', 1);");
tep_db_query ("INSERT INTO `pages_description` (`id`, `pages_id`, `pages_title`, `pages_html_text`, `intorext`, `externallink`, `link_target`, `language_id`) VALUES (11, 2, 'Contattaci', 'Pagina Conttataci per la lingua italiana... pu&ograve; essere cambiata da amministrazione.\r\nby OSCommerce.it', '0', '', '0', 1);");
tep_db_query ("INSERT INTO `pages_description` (`id`, `pages_id`, `pages_title`, `pages_html_text`, `intorext`, `externallink`, `link_target`, `language_id`) VALUES (13, 5, 'Contattaci', 'Cerca di contattarci in qualche modo!!!', '0', '', '0', 4);");
tep_db_query ("INSERT INTO `pages_description` (`id`, `pages_id`, `pages_title`, `pages_html_text`, `intorext`, `externallink`, `link_target`, `language_id`) VALUES (14, 6, 'Privacy', 'Gentile utente, desideriamo informarLa che i Suoi dati personali - raccolti direttamente presso di Lei - non saranno mai ceduti o distribuiti a terzi ma utilizzati nel pieno rispetto dei principi fondamentali, dettati dalla direttiva 95/46/CE e dal D.Lgs. 30 giugno 2003 NÂ°196 per la protezione dei dati personali.<BR><BR><BR><B>OPERAZIONI DI TRATTAMENTO DEI DATI PERSONALI E RELATIVI SCOPI<BR></B>Di seguito, Le riepiloghiamo tutte le operazioni da noi svolte che implicano raccolta, conservazione o elaborazione dei Suoi dati personali, e gli scopi che perseguiamo con ciascuna di esse: \r\n<UL>\r\n<LI>raccolta e conservazione dei Suoi dati personali al fine dell''invio, per posta elettronica, delle informative commerciali; \r\n<LI>elaborazione interna dei dati personali da Lei forniti allo scopo di definire il Suo profilo commerciale; \r\n<LI>utilizzo del Suo profilo commerciale per finalit&agrave; di marketing e promozionali di suo interesse; </LI></UL>\r\n<P><BR><B>MODALITA'' DEL TRATTAMENTO</B><BR>Il trattamento avverr&agrave; con modalit&agrave; totalmente automatizzate. La nostra societ&agrave;,&nbsp; mediante il sistema di trattamento dei dati assicura e garantisce che le informazioni trattate non comprendono argomenti riguardanti dati sensibili ai sensi dell''art. 95/46/CE e dal D.Lgs. 30 giugno 2003 NÂ°196. Pertanto verr&agrave; escluso a priori ogni trattamento che possa riguardare direttamente o indirettamente dati sensibili. <BR><BR><B>LIBERTA'' DI RILASCIARE IL CONSENSO E CONSEGUENZE DI UN RIFIUTO</B><BR>Il conferimento dei Suoi dati &egrave; facoltativo. Tuttavia, in caso di rifiuto del consenso per gli scopi indicati, ci troveremo nell''impossibilit&agrave; di erogarLe i servizi di informazione per i quali il consenso viene richiesto, ivi compresa la registrazione su questo sito.</P>\r\n<P>&nbsp;<BR><B>TITOLARE E RESPONSABILE DEL TRATTAMENTO</B><BR>Titolare del trattamento &egrave; la societ&agrave; titolare di questo sito di commercio elettronico. Responsabili del trattamento dei dati personali sono i funzionari e i soggetti addetti alla gestione dei database, in relazione al rispettivo settore di competenza. <BR><BR><B>DIRITTI DELL''INTERESSATO</B><BR>La informiamo inoltre che ogni interessato pu&ograve; esercitare i diritti di cui all''art.7 del D.Lgs. 30 giugno 2003 NÂ°196 che di seguito riassumiamo: (Diritto di accesso ai dati personali ed altri diritti) L''interessato ha diritto di ottenere la conferma dell''esistenza o meno di dati personali che lo riguardano, anche se non ancora registrati, e la loro comunicazione in forma intelligibile. L''interessato ha diritto di ottenere l''indicazione: dell''origine dei dati personali; delle finalit&agrave; e modalit&agrave; del trattamento; della logica applicata in caso di trattamento effettuato con l''ausilio di strumenti elettronici; degli estremi identificativi del titolare, dei responsabili e del rappresentante designato ai sensi dell''articolo 5, comma 2; dei soggetti o delle categorie di soggetti ai quali i dati personali possono essere comunicati o che possono venirne a conoscenza in qualit&agrave; di rappresentante designato nel territorio dello Stato, di responsabili o incaricati. L''interessato ha diritto di ottenere: l''aggiornamento, la rettificazione ovvero, quando vi ha interesse, l''integrazione dei dati; la cancellazione, la trasformazione in forma anonima o il blocco dei dati trattati in violazione di legge, compresi quelli di cui non &egrave; necessaria la conservazione in relazione agli scopi per i quali i dati sono stati raccolti o successivamente trattati; l''attestazione che le operazioni di cui alle lettere a) e b) sono state portate a conoscenza, anche per quanto riguarda il loro contenuto, di coloro ai quali i dati sono stati comunicati o diffusi, eccettuato il caso in cui tale adempimento si rivela impossibile o comporta un impiego di mezzi manifestamente sproporzionato rispetto al diritto tutelato. L''interessato ha diritto di opporsi, in tutto o in parte: per motivi legittimi al trattamento dei dati personali che lo riguardano, ancorch&egrave; pertinenti allo scopo della raccolta; al trattamento di dati personali che lo riguardano a fini di invio di materiale pubblicitario o di vendita diretta o per il compimento di ricerche di mercato o di comunicazione commerciale.</TD> </P>', '0', '', '0', 4);");
tep_db_query ("INSERT INTO `pages_description` (`id`, `pages_id`, `pages_title`, `pages_html_text`, `intorext`, `externallink`, `link_target`, `language_id`) VALUES (16, 6, 'Privacy', '', '0', '', '0', 5);");
tep_db_query ("INSERT INTO `pages_description` (`id`, `pages_id`, `pages_title`, `pages_html_text`, `intorext`, `externallink`, `link_target`, `language_id`) VALUES (17, 8, 'Come Registrarsi', '<P>Registrarsi su questo sito di Commercio Elettronico&nbsp;&egrave; semplice e veloce&nbsp;e ti consentir&agrave; di aquistare i prodotti,&nbsp;avere un tuo account personale, verificare lo stato&nbsp;degli ordini, avere una rubrica di indirizzi per i&nbsp;tuoi regali,&nbsp;venire a conoscenza&nbsp;tempestivamente via email di eventuali promozioni.<BR><BR>Per iscriversi &egrave; sufficiente seguire il link \"Il Mio Account\"&nbsp;che trovi in home page oppure procedere all''acquisto dopo aver inserito i prodotti nel carrello.<BR><BR>Dopo avere compilato il form di registrazione, il sistema ti invier&agrave; una e-mail di conferma.&nbsp; </P>\r\n<P><BR>Ãˆ comunque possibile navigare nel sito di commercio elettronico senza registrarsi. Ãˆ necessaria l''iscrizione tuttavia per poter&nbsp;acquistare.<BR><BR>Buona Navigazione!</P>', '0', '', '0', 4);");
tep_db_query ("INSERT INTO `pages_description` (`id`, `pages_id`, `pages_title`, `pages_html_text`, `intorext`, `externallink`, `link_target`, `language_id`) VALUES (18, 8, 'How To Register', '', '0', '', '0', 5);");
tep_db_query ("INSERT INTO `pages_description` (`id`, `pages_id`, `pages_title`, `pages_html_text`, `intorext`, `externallink`, `link_target`, `language_id`) VALUES (19, 9, 'Come Acquistare', '<P>Ci sono diversi modi per trovare i prodotti e i servizi presenti nel catalogo. Puoi scegliere una delle categorie, puoi utilizzare il motore di ricerca, che ti permetter&agrave; di trovare i risultati corrispondenti alle tue necessit&agrave;. </P>\r\n<P>LA SCHEDA PRODOTTO </P>\r\n<P>Una volta cliccato su un prodotto/servizio&nbsp;visualizzerai la sua scheda. La scheda prodotto ti offre: una descrizione del prodotto, un elenco approfondito delle sue funzionalit&agrave;, la tipologia le modalit&agrave; di consegna da parte del sistema (download on-line, spedizione), lo stato della disponibilit&agrave;, il prezzo (ed eventuali sconti o offerte), le informazioni relative alla garanzia, le informazioni sul post-vendita, e&nbsp;in genere la scheda tecnica.&nbsp;In corrispondenza di ogni scheda prodotto &egrave; possibile anche leggere le recensioni sul prodotto lasciate da altri utenti del network. E''&nbsp;possibile inoltre inviare una notifica sul prodotto ad un amico,&nbsp;e se si &egrave; registrati, sottoscrivere una newsletter sugli aggironamenti del prodotto.&nbsp;</P>\r\n<P>IL CARRELLO </P>\r\n<P>Una volta trovato e scelto il prodotto, nei modi che hai visto in precedenza, per acquistarlo non devi fare altro che cliccare sul pulsante ''Aggiungi al carrello''. Il carrello riassume in poche righe tutte le informazioni di cui hai bisogno: l''elenco dei prodotti scelti, la quantit&agrave;, il prezzo. Inserire un prodotto nel carrello non implica l''acquisto. Sono disponibili le funzionalit&agrave; Svuota carrello, Aggiorna carrello e si pu&ograve; anche lasciare in stand by l''acquisto ed effettuare il logoff. Al successivo accesso, l''utente trover&agrave; il carrello \"pieno\", ossia con memoria delle precedenti scelte. </P>', '0', '', '0', 4);");
tep_db_query ("INSERT INTO `pages_description` (`id`, `pages_id`, `pages_title`, `pages_html_text`, `intorext`, `externallink`, `link_target`, `language_id`) VALUES (20, 9, 'How to Buy', '', '0', '', '0', 5);");
tep_db_query ("INSERT INTO `pages_description` (`id`, `pages_id`, `pages_title`, `pages_html_text`, `intorext`, `externallink`, `link_target`, `language_id`) VALUES (21, 10, 'Pagamento e Consegna', '', '0', '', '0', 4);");
tep_db_query ("INSERT INTO `pages_description` (`id`, `pages_id`, `pages_title`, `pages_html_text`, `intorext`, `externallink`, `link_target`, `language_id`) VALUES (22, 10, 'Shipping and Payments', '', '0', '', '0', 5);");


}
// cancello la nesty page del catalogo
tep_db_query ("delete FROM `pages` where pages_id = '7'");


 //`categories_status` tinyint(1) unsigned NOT NULL default '0',
if (!checkColumn('categories_status',TABLE_CATEGORIES)){
	tep_db_query("alter table ".TABLE_CATEGORIES." add column categories_status  tinyint(1) unsigned NOT NULL default '1'");
	
}
	if (!checkColumn('customers_group_discount',TABLE_PRODUCTS_GROUPS)){
	// inserisce i dati di default dei gruppi se non ci sono
	tep_db_query("alter table ".TABLE_PRODUCTS_GROUPS." add column customers_group_discount decimal(6,2) NOT NULL default '0.00'");
	
	tep_db_query("INSERT INTO `customers_groups` (`customers_group_id`, `customers_group_name`, `customers_group_show_tax`, `customers_group_tax_exempt`, `group_payment_allowed`, `group_shipment_allowed`, `customers_group_default_discount`, `customers_group_show_prices`, `customers_group_hidden_prices_msg`) VALUES (0, 'Clienti Finali', '1', '0', '', '', 0.00, '1', 'Occorre registrarsi per vedere i prezzi'),
			(1, 'Rivenditori', '0', '0', 'bonifico.php', '', 0.00, '1', 'Occorre registrarsi per vedere i prezzi');");
	
	}
	else {	tep_db_query("alter table ".TABLE_PRODUCTS_GROUPS." modify column customers_group_discount decimal(6,2) NOT NULL default '0.00'");
	}
	if (!checkColumn('customers_group_default_discount',TABLE_CUSTOMERS_GROUPS)){
	tep_db_query("alter table ".TABLE_CUSTOMERS_GROUPS." add column customers_group_default_discount decimal(6,2) NOT NULL default '0.00'");
	}
	else 
	{
		tep_db_query("alter table ".TABLE_CUSTOMERS_GROUPS." modify column customers_group_default_discount decimal(6,2) NOT NULL default '0.00'");
	}
	if (!checkColumn('customers_group_show_prices',TABLE_CUSTOMERS_GROUPS)){
		tep_db_query("alter table ".TABLE_CUSTOMERS_GROUPS." add column customers_group_show_prices enum('1','0') NOT NULL default '1' after customers_group_default_discount");
	}
	if (!checkColumn('customers_group_hidden_prices_msg',TABLE_CUSTOMERS_GROUPS)){
		tep_db_query("alter table ".TABLE_CUSTOMERS_GROUPS." add column customers_group_hidden_prices_msg varchar(255) NOT NULL default 'Occorre registrarsi per vedere i prezzi' after customers_group_show_prices");
	}
	if (!checkColumn('customers_group_id',TABLE_SPECIALS)){
		tep_db_query("alter table ".TABLE_SPECIALS." add column customers_group_id int(12) NOT NULL default '0'");
	}
	if (!checkColumn('banners_open_new_windows',TABLE_BANNERS)){
		tep_db_query("alter table ".TABLE_BANNERS." add banners_open_new_windows VARCHAR(12) NOT NULL default '0'");
	}
	if (!checkColumn('banners_on_ssl',TABLE_BANNERS)){
		tep_db_query("alter table ".TABLE_BANNERS." add banners_on_ssl TINYINT(1) NOT NULL default '0'");
	}
	
	if (fieldLength('products_model',TABLE_PRODUCTS)!=255){
		tep_db_query("alter table ".TABLE_PRODUCTS." modify column products_model varchar(255) default NULL");
	}
	if (fieldLength('products_image',TABLE_PRODUCTS)!=255){
		tep_db_query("alter table ".TABLE_PRODUCTS." modify column products_image varchar(255) default NULL");
	}	
	if (fieldLength('pws_specials_discount',TABLE_PWS_SPECIALS)!='6,2'){
		tep_db_query("alter table ".TABLE_PWS_SPECIALS." modify column pws_specials_discount decimal(6,2) default NULL");
	}

	if (fieldLength('products_name',TABLE_PRODUCTS_DESCRIPTION)!=155){
		tep_db_query("alter table ".TABLE_PRODUCTS_DESCRIPTION." modify column products_name varchar(155) default NULL");
	}

	if (fieldLength('categories_name',TABLE_CATEGORIES_DESCRIPTION)!=255){
		tep_db_query("alter table ".TABLE_CATEGORIES_DESCRIPTION." modify column categories_name varchar(255)");
	}
	
	if (!checkColumn('EAN',TABLE_PRODUCTS)){
		tep_db_query("alter table ".TABLE_PRODUCTS." add EAN VARCHAR(24) NULL default NULL");
	}	

	if (!checkColumn('link',TABLE_PRODUCTS)){
		tep_db_query("alter table ".TABLE_PRODUCTS." add link VARCHAR(255) NULL default NULL");
	}	
	
	tep_db_query("alter table ".TABLE_CATEGORIES." modify column  categories_status   TINYINT(1) DEFAULT '1' ");
	tep_db_query("ALTER TABLE ".TABLE_ORDERS." CHANGE orders_status orders_status INT( 5 ) NOT NULL DEFAULT '1'");
	
	// Aggiunta degli indici per ottimizzazione queries mysql
	if (!checkColumn('public_flag',TABLE_ORDERS_STATUS)){
		tep_db_query("alter table ".TABLE_ORDERS_STATUS." add public_flag int DEFAULT '1'");
	}
	if (!checkColumn('downloads_flag',TABLE_ORDERS_STATUS)){
		tep_db_query("alter table ".TABLE_ORDERS_STATUS." add downloads_flag int DEFAULT '0'");
	}
	if (fieldLength('payment_method',TABLE_ORDERS)!=255){
		tep_db_query("alter table ".TABLE_ORDERS." modify payment_method varchar(255) NOT NULL");
	}
	// allargamento campo telefono per includere più recapiti telefonici
	if (fieldLength('customers_telephone',TABLE_ORDERS)!=255){
		tep_db_query("alter table ".TABLE_ORDERS." modify customers_telephone varchar(255) NOT NULL");
	}
	
	
	// modifiche per ulteriori campi nella tabella ordini per il b2b
	// riferimento ordine e contatto (Ordine_effetuato_da)
	if (!checkColumn('Ordine_effetuato_da',TABLE_ORDERS)){
		tep_db_query("alter table ".TABLE_ORDERS." add Ordine_effetuato_da varchar(255) default NULL");
	}	
	
	if (!checkColumn('Vs_rif_ordine',TABLE_ORDERS)){
		tep_db_query("alter table ".TABLE_ORDERS." add Vs_rif_ordine varchar(255) default NULL");
	}	


	// gruppo clienti di appartenenza al momento dell'ordine
	
	if (!checkColumn('customers_group_id',TABLE_ORDERS)){
		tep_db_query("alter table ".TABLE_ORDERS." add customers_group_id int(12) NOT NULL default '0'");
	}
	
	// codice cliente per software contabilità
	if (!checkColumn('customers_code',TABLE_ORDERS)){
		tep_db_query("alter table ".TABLE_ORDERS." add column  `customers_code`  varchar(50) default NULL");
	}	
		

	
	tep_db_query("alter table ".TABLE_WHOS_ONLINE." modify last_page_url text NOT NULL");
	///////////////////////////////
	// Aggiunta degli indici
	if (!indexExists('banners_group',TABLE_BANNERS))
		tep_db_query("alter table ".TABLE_BANNERS." add index idx_banners_group (banners_group)");
	if (!indexExists('banners_id',TABLE_BANNERS_HISTORY))
		tep_db_query("alter table ".TABLE_BANNERS_HISTORY." add index idx_banners_history_banners_id (banners_id)");
	if (!indexExists('code',TABLE_CURRENCIES))
		tep_db_query("alter table ".TABLE_CURRENCIES." add index idx_currencies_code (code)");
	if (!indexExists('customers_email_address',TABLE_CUSTOMERS))
		tep_db_query("alter table ".TABLE_CUSTOMERS." add index idx_customers_email_address (customers_email_address)");
	if (!indexExists('customers_id',TABLE_CUSTOMERS_BASKET))
		tep_db_query("alter table ".TABLE_CUSTOMERS_BASKET." add index idx_customers_basket_customers_id (customers_id)");
	if (!indexExists('customers_id',TABLE_CUSTOMERS_BASKET_ATTRIBUTES))
		tep_db_query("alter table ".TABLE_CUSTOMERS_BASKET_ATTRIBUTES." add index idx_customers_basket_att_customers_id (customers_id)");
	if (!indexExists('customers_id',TABLE_ORDERS))
		tep_db_query("alter table ".TABLE_ORDERS." add index idx_orders_customers_id (customers_id)");
	if (!indexExists('orders_id',TABLE_ORDERS_PRODUCTS))
		tep_db_query("alter table ".TABLE_ORDERS_PRODUCTS." add index idx_orders_products_orders_id (orders_id)");
	if (!indexExists('products_id',TABLE_ORDERS_PRODUCTS))
		tep_db_query("alter table ".TABLE_ORDERS_PRODUCTS." add index idx_orders_products_products_id (products_id)");
	if (!indexExists('orders_id',TABLE_ORDERS_STATUS_HISTORY))
		tep_db_query("alter table ".TABLE_ORDERS_STATUS_HISTORY." add index idx_orders_status_history_orders_id (orders_id)");
	if (!indexExists('orders_id',TABLE_ORDERS_PRODUCTS_ATTRIBUTES))
		tep_db_query("alter table ".TABLE_ORDERS_PRODUCTS_ATTRIBUTES." add index idx_orders_products_att_orders_id (orders_id)");
	if (!indexExists('orders_id',TABLE_ORDERS_PRODUCTS_DOWNLOAD))
		tep_db_query("alter table ".TABLE_ORDERS_PRODUCTS_DOWNLOAD." add index idx_orders_products_download_orders_id (orders_id)");
	if (!indexExists('products_model',TABLE_PRODUCTS))
		tep_db_query("alter table ".TABLE_PRODUCTS." add index idx_products_model (products_model)");
	if (!indexExists('products_id',TABLE_PRODUCTS_ATTRIBUTES))
		tep_db_query("alter table ".TABLE_PRODUCTS_ATTRIBUTES." add index idx_products_attributes_products_id (products_id)");
	if (!indexExists('products_id',TABLE_REVIEWS))
		tep_db_query("alter table ".TABLE_REVIEWS." add index idx_reviews_products_id (products_id)");
	if (!indexExists('customers_id',TABLE_REVIEWS))
		tep_db_query("alter table ".TABLE_REVIEWS." add index idx_reviews_customers_id (customers_id)");
	if (!indexExists('products_id',TABLE_SPECIALS))
		tep_db_query("alter table ".TABLE_SPECIALS." add index idx_specials_products_id (products_id)");
	if (!indexExists('zone_country_id',TABLE_ZONES))
		tep_db_query("alter table ".TABLE_ZONES." add index idx_zones_to_geo_zones_country_id (zone_country_id)");
	
}
// seo url patch
if (true)	{
	if (!checkColumn('products_seo_url',TABLE_PRODUCTS_DESCRIPTION))
		tep_db_query("ALTER TABLE ".TABLE_PRODUCTS_DESCRIPTION." ADD products_seo_url varchar(255) default NULL AFTER products_url"); 
	if (!checkColumn('categories_seo_url',TABLE_CATEGORIES_DESCRIPTION))
		tep_db_query("ALTER TABLE ".TABLE_CATEGORIES_DESCRIPTION." ADD categories_seo_url varchar(255) default NULL AFTER categories_name"); 

// youtube patch
	if (!checkColumn('products_youtube_url',TABLE_PRODUCTS_DESCRIPTION))
		tep_db_query("ALTER TABLE ".TABLE_PRODUCTS_DESCRIPTION." ADD products_youtube_url varchar(255) default NULL AFTER products_seo_url"); 
}



// phpmailer config per smtp
if (!checkConfigKey('SMTP_MAIL_SERVER'))
	tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, 								 `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) 
									VALUES ('SMTP Server',			'SMTP_MAIL_SERVER',	''					  ,'server per invio email tramite smtp, per es. smtp.gmail.com',12,6,'2009-09-28 12:54:39',NULL, NULL)");
	
if (!checkConfigKey('SMTP_PORT_NUMBER'))
	tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, 								 `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) 
									VALUES ('SMTP Port Number',			'SMTP_PORT_NUMBER',	''					  ,'Porta del server SMTP, in genere 25. Per SSL 465 o 587',12,7,'2009-09-28 12:54:39',NULL, NULL)");

if (!checkConfigKey('SMTP_SENDMAIL_FROM'))
	tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, 								 `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) 
									VALUES ('SMTP SENDMAIL FROM',			'SMTP_SENDMAIL_FROM',	''					  ,'Indirizzo email che compare nel mittente, dovrebbe essere lo stesso impostato sulla configurazione del Mio Negozio',12,8,'2009-09-28 12:54:39',NULL, NULL)");
		
if (!checkConfigKey('SMTP_FROMEMAIL_NAME'))
	tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, 								 `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) 
									VALUES ('SMTP FROM EMAIL NAME',			'SMTP_FROMEMAIL_NAME',	''					  ,'Nome che compare nel mittente, es. Staff Nome Sito oppure Web Master',12,9,'2009-09-28 12:54:39',NULL, NULL)");

if (!checkConfigKey('SMTP_SECURE'))
	tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, 								 `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) 
									VALUES ('SMTP use SSL',			'SMTP_SECURE',	''					  ,'Usa connessioni SSL',12,10,'2009-09-28 12:54:39',NULL, 'tep_cfg_select_option(array(''True'', ''False''),')");

if (!checkConfigKey('SMTP_SENDMAIL_USERNAME'))
	tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, 								 `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) 
									VALUES ('SMTP SENDMAIL USERNAME',			'SMTP_SENDMAIL_USERNAME',	''					  ,'Nome utente per autenticazione sul server SMTP',12,11,'2009-09-28 12:54:39',NULL, NULL)");

if (!checkConfigKey('SMTP_SENDMAIL_PASSWORD'))
	tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, 								 `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) 
									VALUES ('SMTP SENDMAIL PASSWORD',			'SMTP_SENDMAIL_PASSWORD',	''					  ,'Password per autenticazione sul server SMTP',12,12,'2009-09-28 12:54:39',NULL, NULL)");
	
	
// stock attributes patch
if (true)	{
	if (!checkColumn('products_stock_attributes',TABLE_ORDERS_PRODUCTS))
	tep_db_query("ALTER TABLE ".TABLE_ORDERS_PRODUCTS." ADD products_stock_attributes varchar(255) default NULL AFTER products_quantity");

if (!checkConfigKey('PRODINFO_ATTRIBUTE_PLUGIN'))
	tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) VALUES ('Product Info Attribute Display Plugin','PRODINFO_ATTRIBUTE_PLUGIN','multiple_dropdowns','The plugin used for displaying attributes on the product information page.',888001,1,'2006-11-28 17:09:39',NULL,'tep_cfg_pull_down_class_files(''pad_'',')");
if (!checkConfigKey('PRODINFO_ATTRIBUTE_SHOW_OUT_OF_STOCK'))
	tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) VALUES ('Show Out of Stock Attributes','PRODINFO_ATTRIBUTE_SHOW_OUT_OF_STOCK','True','Controls the display of out of stock attributes.',888001,10,'2006-11-28 17:09:39',NULL,'tep_cfg_select_option(array(''True'', ''False''),')");	
if (!checkConfigKey('PRODINFO_ATTRIBUTE_MARK_OUT_OF_STOCK'))
	tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) VALUES ('Mark Out of Stock Attributes','PRODINFO_ATTRIBUTE_MARK_OUT_OF_STOCK','Right','Controls how out of stock attributes are marked as out of stock.',888001,20,'2006-11-28 17:09:39',NULL,'tep_cfg_select_option(array(''None'', ''Right'', ''Left''),')");	
if (!checkConfigKey('PRODINFO_ATTRIBUTE_OUT_OF_STOCK_MSGLINE'))
	tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) VALUES ('Display Out of Stock Message Line','PRODINFO_ATTRIBUTE_OUT_OF_STOCK_MSGLINE','True','Controls the display of a message line indicating an out of stock attributes is selected.',888001,30,'2006-11-28 17:09:39',NULL,'tep_cfg_select_option(array(''True'', ''False''),')");	
if (!checkConfigKey('PRODINFO_ATTRIBUTE_NO_ADD_OUT_OF_STOCK'))
	tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) VALUES ('Prevent Adding Out of Stock to Cart','PRODINFO_ATTRIBUTE_NO_ADD_OUT_OF_STOCK','True','Prevents adding an out of stock attribute combination to the cart.',888001,40,'2006-11-28 17:09:39',NULL,'tep_cfg_select_option(array(''True'', ''False''),')");	
	
}
// piva cf patch

if (!checkColumn('entry_piva',TABLE_ADDRESS_BOOK)) // la contrib piva non è installata
{
tep_db_query("ALTER TABLE address_book ADD column entry_piva varchar(11) NOT NULL AFTER entry_company");
tep_db_query("ALTER TABLE address_book ADD column entry_cf varchar(16) NOT NULL AFTER entry_company");
tep_db_query("ALTER TABLE orders ADD billing_piva VARCHAR( 11 ) AFTER billing_company ");
tep_db_query("ALTER TABLE orders ADD billing_cf VARCHAR( 16 ) AFTER billing_company ");
// chiavi di configurazione
tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) VALUES ('Partita IVA', 'ACCOUNT_PIVA', 'true', 'Decidi se mostrare il campo Partita Iva', 5, 1, NULL, '2003-06-01 17:41:12', 'tep_cfg_select_option(array(''true'', ''false''),')");
tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `insert_id`, `sort_order`, `date_added`, `use_function`, `set_function`) VALUES ('Partita IVA Richiesta', 'ACCOUNT_PIVA_REQ', 'false', 'Decidi se il campo Partita Iva deve essere inserito obbligatoriamente', 5, 1,  '2003-06-01 17:41:12', NULL, 'tep_cfg_select_option(array(''true', ''false''),')");
tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) VALUES ('Codice Fiscale', 'ACCOUNT_CF', 'true', 'Decidi se mostrare il campo Codice Fiscale', 5, 1, NULL, '2003-06-01 17:41:12',  'tep_cfg_select_option(array(''true', ''false''),')");
tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) VALUES ('Codice Fiscale Richiesto', 'ACCOUNT_CF_REQ', 'true', 'Decidi se il campo Codice Fiscale deve essere inserito obbligatoriamente', 5, 1,  '2003-06-01 17:41:12', NULL, 'tep_cfg_select_option(array(''true', ''false''),')");

}

// voce di configurazione per  la visualizzazione dei prezzi scontati o netti per versioni B2B
// in pratica quando questa voce è abilitata, vengono mostrati i prezzi riservati al gruppo senza far vedere lo sconto
if (!checkConfigKey('SHOW_GROUP_NET_PRICES'))
	tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) VALUES ('Mostra solo prezzi scontati per il gruppo', 'SHOW_GROUP_NET_PRICES', 'false', 'Se abilitato, il gruppo vede solo i prezzi riservati, anzich� visualizzare: prezzo di Listino-> Sconto -> Prezzo Riservato', 1, 19, now(), NULL, 'tep_cfg_select_option(array(''true'', ''false''),')");

// randomize
if (!checkConfigKey('RANDOMIZE'))
	tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) VALUES ('Mostra i prodotti in home e nelle categorie in ordine casuale', 'RANDOMIZE', 'off', 'Se abilitato (on), i prodotti verranno mostrati in ordine casuale anzich� per data di arrivo.', 1, 19, now(), NULL, 'tep_cfg_select_option(array(''on'', ''off''),')");
	
// TURBOSCOMMERCE
if (!checkConfigKey('TURBO'))
	tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) VALUES ('Abilita la modalit� TURBO', 'TURBO', 'off', 'Se abilitato (on), si attiva l\'ottimizzazione dell\'utilizzo delle risorse php e mysql velocizzando il caricamento delle pagine .', 1, 19, now(), NULL, 'tep_cfg_select_option(array(''on'', ''off''),')");
	
	
// bersani patch
if (true)	{
	if (!checkColumn('entry_company_cf',TABLE_ADDRESS_BOOK))
		tep_db_query("alter table ".TABLE_ADDRESS_BOOK." add column entry_company_cf varchar(16) not null after entry_cf");

	if (!checkColumn('entry_company_tax_id',TABLE_ADDRESS_BOOK))
		tep_db_query("alter table ".TABLE_ADDRESS_BOOK." add column entry_company_tax_id varchar(32) not null after entry_company_cf");
		
	if (!checkColumn('billing_company_cf',TABLE_ORDERS))
		tep_db_query("alter table ".TABLE_ORDERS." add column billing_company_cf varchar(16) not null after billing_cf");
	if (!checkConfigKey('ACCOUNT_COMPANY_CF'))
		tep_db_query("INSERT INTO ".TABLE_CONFIGURATION." (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) VALUES ('Codice Fiscale Azienda', 'ACCOUNT_COMPANY_CF', 'true', 'Decidi se mostrare il campo Codice Fiscale dell\'azienda', 5, 1, now(), NULL, 'tep_cfg_select_option(array(''true'', ''false''),')");
	if (!checkConfigKey('ACCOUNT_COMPANY_CF_REQ'))
		tep_db_query("INSERT INTO ".TABLE_CONFIGURATION." (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) VALUES ('C.F. Azienda Richiesto', 'ACCOUNT_COMPANY_CF_REQ', 'true', 'Decidi se il campo Codice Fiscale dell\'azienda deve essere inserito obbligatoriamente', 5, 1, now(), NULL, 'tep_cfg_select_option(array(''true'', ''false''),')");
	if (!checkColumn('products_options_track_stock','products_options'))
		tep_db_query("ALTER TABLE products_options ADD products_options_track_stock tinyint(4) default '0' not null AFTER products_options_name");
}

// codice cliente per gestionale esterno
	if (!checkConfigKey('ACCOUNT_CUSTOMER_CODE'))
		tep_db_query("INSERT INTO ".TABLE_CONFIGURATION." (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) VALUES ('Codice Cliente (solo per form lato admin)', 'ACCOUNT_CUSTOMER_CODE', 'false', 'Decidi se mostrare il campo Codice Cliente nella sezione admin', 5, 1, now(), NULL, 'tep_cfg_select_option(array(''true'', ''false''),')");
	if (!checkConfigKey('ACCOUNT_CUSTOMER_CODE_REQ'))
		tep_db_query("INSERT INTO ".TABLE_CONFIGURATION." (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) VALUES ('Codice Cliente Richiesto', 'ACCOUNT_CUSTOMER_CODE_REQ', 'false', 'Decidi se il campo Codice Cliente deve essere inserito obbligatoriamente', 5, 1, now(), NULL, 'tep_cfg_select_option(array(''true'', ''false''),')");
		

// availability/disponibilit� patch
if (true)	{
	if (!checkConfigKey('PRODUCT_LIST_AVAILABILITY'))
		tep_db_query("insert configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Mostra Disponibilit&agrave;','PRODUCT_LIST_AVAILABILITY',3,'Mostra la disponibilit&agrave; del prodotto',8,5,now())");
	if (!checkConfigKey('PRODUCT_LIST_AVAILABILITY_LEGEND'))
		tep_db_query("insert configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Mostra Legenda Disponibilit&agrave;','PRODUCT_LIST_AVAILABILITY_LEGEND','true','Mostra la legenda di spiegazione dei semaforini della disponibilit&agrave; del prodotto',8,15,'tep_cfg_select_option(array(''true'', ''false''),',now())");
	if (!checkConfigKey('PRODUCT_LIST_AVAILABILITY_GREEN'))
		tep_db_query("insert configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Semaforo Verde','PRODUCT_LIST_AVAILABILITY_GREEN',5,'Mostra il semaforo verde per una quantit&agrave; uguale o superiore a...',8,11,now())");
	if (!checkConfigKey('PRODUCT_LIST_AVAILABILITY_YELLOW'))
		tep_db_query("insert configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Semaforo Giallo','PRODUCT_LIST_AVAILABILITY_YELLOW',1,'Mostra il semaforo giallo per una quantit&agrave; uguale o superiore a...',8,12,now())");
	if (!checkConfigKey('PRODUCT_LIST_AVAILABILITY_RED'))
		tep_db_query("insert configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Semaforo Rosso','PRODUCT_LIST_AVAILABILITY_RED',0,'Mostra il semaforo rosso per una quantit&agrave; uguale o superiore a...',8,13,now())");
	if (!checkConfigKey('PRODUCT_LIST_AVAILABILITY_TEXT'))
		tep_db_query("insert configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Semaforo con testo','PRODUCT_LIST_AVAILABILITY_TEXT','false','Mostra la spiegazione accanto al semaforo della disponibilit&agrave;',8,14,'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
}




// chiavi di configurazione per gestire la disponibilit� grafica e numerica dei prodotti
	if (!checkConfigKey('PRODUCT_INFO_QUANTITY'))
		tep_db_query("insert configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Mostra quantit� disponibile nella scheda prodotto','PRODUCT_INFO_QUANTITY','false','Mostra la quantit� disponibile all\'interno della scheda prodotto',8,16,'tep_cfg_select_option(array(\'true\', \'false\'), ',now())");
	
	if (!checkConfigKey('PRODUCT_INFO_AVAILABILITY'))
		tep_db_query("insert configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Mostra disponibilit� grafica nella scheda prodotto','PRODUCT_INFO_AVAILABILITY','true','Mostra la disponibilit� grafica all\'interno della scheda prodotto',8,17,'tep_cfg_select_option(array(\'true\', \'false\'), ',now())");

// chiavi di configurazione per gestire il campo modello prodotto nella scheda prodotto pubblica e nelle email di conferma
	if (!checkConfigKey('PRODUCT_INFO_MODEL'))
		tep_db_query("insert configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Mostra campo Modello nella scheda prodotto','PRODUCT_INFO_MODEL','true','Mostra il campo Modello prodoto (codice) all\'interno della scheda prodotto, se a false, il campo modello verr� tolto anche dalla mail di conferma ordine',8,18,'tep_cfg_select_option(array(\'true\', \'false\'), ',now())");

// chiavi di configurazione per gestire il campo 'prodotto aggiunto il...'  nella scheda prodotto pubblica 
	if (!checkConfigKey('PRODUCT_INFO_DATE_ADDED'))
		tep_db_query("insert configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Mostra campo data inserimento nella scheda prodotto','PRODUCT_INFO_DATE_ADDED','true','Mostra la data di inserimento in catalogo dell\'articolo all\'interno della scheda prodotto, se a false, il campo modello verr� tolto anche dalla mail di conferma ordine',8,19,'tep_cfg_select_option(array(\'true\', \'false\'), ',now())");

		
// chiavi di configurazione per gestire la tabella spedizioni nella scheda prodotto
	if (!checkConfigKey('ENABLE_PRODUCT_SHIPPING_COST'))
		tep_db_query("insert configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Mostra tabella costi di spedizione','ENABLE_PRODUCT_SHIPPING_COST','true','Mostra la tabella dei costi di spedizione all\'interno della scheda prodotto, se a false, la tabella non viene mostrata',8,20,'tep_cfg_select_option(array(\'true\', \'false\'), ',now())");

// chiave di configurazione per i link istituzionali nella scheda prodotto
	if (!checkConfigKey('ENABLE_PRODUCT_GENERIC_LINKS'))
		tep_db_query("insert configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Mostra tabella Link Generici','ENABLE_PRODUCT_GENERIC_LINKS','true','Mostra la tabella dei link generici (Notifica aggiornamenti, Invia ad un amico etc.) all\'interno della scheda prodotto, se a false, la tabella non viene mostrata',8,21,'tep_cfg_select_option(array(\'true\', \'false\'), ',now())");
		
		
// chiavi di configurazione per gestire le recensioni 
	if (!checkConfigKey('REVIEWS_ENALBED'))
		tep_db_query("insert configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Abilita le Recensioni sugli articoli','REVIEWS_ENALBED','true','Abilita la possibilit� per gli utenti di scrivere recensioni sugli articoli a catalogo',8,20,'tep_cfg_select_option(array(\'true\', \'false\'), ',now())");
		
		
// consenti la registrazione anche ai privati. Se a false, impedisce che i privati si registrino sul sito (pure B2B).		
	if (!checkConfigKey('ALLOW_FINAL_CUSTOMERS'))
		tep_db_query("insert configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Permetti la registerazione ai Clienti Privati','ALLOW_FINAL_CUSTOMERS','true','Se abilitato, mostra il radio button per la scelta Privato/Azienda',5,'','tep_cfg_select_option(array(\'true\', \'false\'), ',now())");
				

// zones 2 geo zones patch
if (true)	{
	$query=tep_db_query("select * from ".TABLE_ZONES_TO_GEO_ZONES." where 1");
	if (!tep_db_num_rows($query)){
		echo "GEO ZONES non associato in '".TABLE_ZONES_TO_GEO_ZONES."'<br/>\r\n";
		$num_patches++;
		$geo_zone_id=array_pop(tep_db_fetch_array(tep_db_query("select geo_zone_id from ".TABLE_GEO_ZONES." where geo_zone_name like '%italia%'")));
		tep_db_query("INSERT INTO ".TABLE_ZONES_TO_GEO_ZONES." (`zone_country_id`, `zone_id`, `geo_zone_id`, `last_modified`, `date_added`) VALUES (0, 0, '$geo_zone_id', NULL, now())");
	
	// se geozones � configurato male
	$query=tep_db_query("update ".TABLE_ZONES_TO_GEO_ZONES." set zone_country_id = '105' where geo_zone_id = '1'");
	
	}
	// modifica zona forl�
	$query=tep_db_query("update ".TABLE_ZONES." set zone_name = 'Forl�' WHERE zone_name = 'Forl&igrave;' OR zone_name = 'Forl�' ");
	if (mysql_affected_rows($query) >= '1')
		echo "Provincia Forl� aggiornata in '".TABLE_ZONES."'<br/>\r\n";

	// aggiunta nuove province	
	// 1)Monza Brianza (MB)
    // 2)Olbia Tempio (OT)
    $query=tep_db_query("select * from ". TABLE_ZONES ." where zone_country_id=105 and zone_code = 'MB' and zone_name = 'Monza Brianza'");
    	if (!tep_db_num_rows($query)){
    		tep_db_query("INSERT INTO ". TABLE_ZONES ."  (`zone_id` ,`zone_country_id` ,`zone_code` ,`zone_name`)
															VALUES (NULL , '105', 'MB', 'Monza Brianza')");
    		echo "Provincia Monza Brianza aggiunta in '".TABLE_ZONES."'<br/>\r\n";
    	}
   $query=tep_db_query("select * from ". TABLE_ZONES ." where zone_country_id=105 and zone_code = 'MB' and zone_name = 'Monza Brianza'");
    	if (!tep_db_num_rows($query)){
    		tep_db_query("INSERT INTO ". TABLE_ZONES ."  (`zone_id` ,`zone_country_id` ,`zone_code` ,`zone_name`)
															VALUES (NULL , '105', 'MB', 'Monza Brianza')");
    		echo "Provincia Monza Brianza aggiunta in '".TABLE_ZONES."'<br/>\r\n";
    	}
    	
   $query=tep_db_query("select * from ". TABLE_ZONES ." where zone_country_id=105 and zone_code = 'BT' and zone_name = 'Barletta-Andria-Trani'");
    	if (!tep_db_num_rows($query)){
    		tep_db_query("INSERT INTO ". TABLE_ZONES ."  (`zone_id` ,`zone_country_id` ,`zone_code` ,`zone_name`)
															VALUES (NULL , '105', 'BT', 'Barletta-Andria-Trani')");
    		echo "Provincia Barletta-Andria-Trani aggiunta in '".TABLE_ZONES."'<br/>\r\n";
    	} 	
    	
   $query=tep_db_query("select * from ". TABLE_ZONES ." where zone_country_id=105 and zone_code = 'CI' and zone_name = 'Carbonia-Iglesias'");
    	if (!tep_db_num_rows($query)){
    		tep_db_query("INSERT INTO ". TABLE_ZONES ."  (`zone_id` ,`zone_country_id` ,`zone_code` ,`zone_name`)
															VALUES (NULL , '105', 'CI', 'Carbonia-Iglesias')");
    		echo "Provincia Carbonia-Iglesias aggiunta in '".TABLE_ZONES."'<br/>\r\n";
    	} 	
    	
   $query=tep_db_query("select * from ". TABLE_ZONES ." where zone_country_id=105 and zone_code = 'FM' and zone_name = 'Fermo'");
    	if (!tep_db_num_rows($query)){
    		tep_db_query("INSERT INTO ". TABLE_ZONES ."  (`zone_id` ,`zone_country_id` ,`zone_code` ,`zone_name`)
															VALUES (NULL , '105', 'FM', 'Fermo')");
    		echo "Provincia Fermo aggiunta in '".TABLE_ZONES."'<br/>\r\n";
    	} 	  	

    $query=tep_db_query("select * from ". TABLE_ZONES ." where zone_country_id=105 and zone_code = 'OT' and zone_name = 'Olbia-Tempio'");
    	if (!tep_db_num_rows($query)){
    		tep_db_query("INSERT INTO ". TABLE_ZONES ."  (`zone_id` ,`zone_country_id` ,`zone_code` ,`zone_name`)
															VALUES (NULL , '105', 'OT', 'Olbia-Tempio')");
    		echo "Provincia Olbia-Tempio aggiunta in '".TABLE_ZONES."'<br/>\r\n";
    	} 	  	
    	
    $query=tep_db_query("select * from ". TABLE_ZONES ." where zone_country_id=105 and zone_code = 'VS' and zone_name = 'Medio Campidano'");
    	if (!tep_db_num_rows($query)){
    		tep_db_query("INSERT INTO ". TABLE_ZONES ."  (`zone_id` ,`zone_country_id` ,`zone_code` ,`zone_name`)
															VALUES (NULL , '105', 'VS', 'Medio Campidano')");
    		echo "Provincia Medio Campidano aggiunta in '".TABLE_ZONES."'<br/>\r\n";
    	} 	  	
    	
    	
    $query=tep_db_query("select * from ". TABLE_ZONES ." where zone_country_id=105 and zone_code = 'OG' and zone_name = 'Ogliastra'");
    	if (!tep_db_num_rows($query)){
    		tep_db_query("INSERT INTO ". TABLE_ZONES ."  (`zone_id` ,`zone_country_id` ,`zone_code` ,`zone_name`)
															VALUES (NULL , '105', 'OG', 'Ogliastra')");
    		echo "Provincia Ogliastra aggiunta in '".TABLE_ZONES."'<br/>\r\n";
    	} 	  	
    	
    	
}
// paypal ec
if (true)	{
	if (!checkColumn('customers_paypal_payerid','customers'))
		tep_db_query("ALTER TABLE `customers` ADD `customers_paypal_payerid` VARCHAR( 20 )");
	if (!checkColumn('customers_paypal_ec','customers'))
		tep_db_query("ALTER TABLE `customers` ADD `customers_paypal_ec` TINYINT (1) UNSIGNED DEFAULT '0' NOT NULL");
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////
// Modifiche Opzionali per Versioni osc_psw >= 3
// modifica azienda/privato
if (true)	{
	if (!checkColumn('entry_type',TABLE_ADDRESS_BOOK)){
		tep_db_query("alter table ".TABLE_ADDRESS_BOOK." add column entry_type enum('company','private') default null after customers_id");
		tep_db_query("update ".TABLE_ADDRESS_BOOK." set entry_type=if(entry_company_cf<>'','company',if(entry_piva<>'','company','private')),entry_company_cf=if(entry_company_cf<>'',entry_company_cf,if(entry_piva<>'',entry_piva,entry_cf))");
	}else{
		tep_db_query("update ".TABLE_ADDRESS_BOOK." set entry_type=if(entry_type<>'',entry_type,if(entry_company_cf<>'','company',if(entry_piva<>'','company','private'))),entry_company_cf=if(entry_company_cf<>'',entry_company_cf,if(entry_piva<>'',entry_piva,entry_cf))");
	}
	if
	 (!checkColumn('billing_type',TABLE_ORDERS)){
		tep_db_query("alter table ".TABLE_ORDERS." add column billing_type enum('company','private') default null after billing_company");
	}
}
// proposta di acquisto stile ebay
	if (!checkColumn('products_makeoffer',TABLE_PRODUCTS))
		tep_db_query("alter table ".TABLE_PRODUCTS." add column products_makeoffer char(1) not null default '0' after products_status");

// Vetrina
if (true)	{
	if (!checkColumn('products_shopwindow',TABLE_PRODUCTS))
		tep_db_query("alter table ".TABLE_PRODUCTS." add column products_shopwindow char(1) not null default '0' after products_status");
	if (file_exists(DIR_FS_CATALOG_MODULES.FILENAME_SHOPWINDOW_FLASH)
		|| file_exists(DIR_FS_CATALOG_MODULES.FILENAME_SHOPWINDOW))	{
		if (tep_db_num_rows(tep_db_query("select * from ".TABLE_CONFIGURATION." where configuration_key='SHOPWINDOW_SKIN' and configuration_title<>'Vetrina: Tipo Vetrina'")))
			tep_db_query("delete from ".TABLE_CONFIGURATION." where configuration_key like '%SHOPWINDOW%'");
		if (!checkConfigKey('SHOPWINDOW_ENABLED'))
			tep_db_query("INSERT INTO ".TABLE_CONFIGURATION." (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) VALUES ('Vetrina: Abilitazione', 'SHOPWINDOW_ENABLED', 'true', 'Abilita la visualizzazione della vetrina', 8, 50, now(), NULL, 'tep_cfg_select_option(array(\'true\', \'false\'), ')");
		if (!checkConfigKey('SHOPWINDOW_SKIN')){
			if (file_exists(DIR_FS_CATALOG_MODULES.FILENAME_SHOPWINDOW_FLASH)){
				$default_shopwindow_skin='FLASH';
				$set_function="tep_cfg_select_option(array(\'HTML\', \'FLASH\'), ";
			}else{
				$default_shopwindow_skin='HTML';
				$set_function="tep_cfg_select_option(array(\'HTML\', \'FLASH (non installata)\'), ";
			}
			tep_db_query("INSERT INTO ".TABLE_CONFIGURATION." (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) VALUES ('Vetrina: Tipo Vetrina', 'SHOPWINDOW_SKIN', '$default_shopwindow_skin', 'Selezionare il tipo di vetrina da visualizzare', 8, 51, now(), NULL, '$set_function')");
		}
		if (!checkConfigKey('SHOPWINDOW_NUM_COLUMNS'))
			tep_db_query("INSERT INTO ".TABLE_CONFIGURATION." (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) VALUES ('Vetrina: Numero Colonne', 'SHOPWINDOW_NUM_COLUMNS', '3', 'Numero di colonne che appaiono nella vetrina', 8, 52, now(), NULL, NULL)");
		if (!checkConfigKey('SHOPWINDOW_MAX_PRODUCTS'))
			tep_db_query("INSERT INTO ".TABLE_CONFIGURATION." (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) VALUES ('Vetrina: Max Numero Prodotti', 'SHOPWINDOW_MAX_PRODUCTS', '9', 'Numero massimo di prodotti che appaiono in vetrina', 8, 53, now(), NULL, NULL)");
		if (!checkConfigKey('SHOPWINDOW_PRODUCT_IMAGE_WIDTH'))
			tep_db_query("INSERT INTO ".TABLE_CONFIGURATION." (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) VALUES ('Vetrina: Max Larghezza Immagini Prodotto ', 'SHOPWINDOW_PRODUCT_IMAGE_WIDTH', '200', 'Larghezza massima delle immagini dei prodotti in vetrina', 8, 54, now(), NULL, NULL)");
		if (!checkConfigKey('SHOPWINDOW_PRODUCT_IMAGE_HEIGHT'))
			tep_db_query("INSERT INTO ".TABLE_CONFIGURATION." (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) VALUES ('Vetrina: Max Altezza Immagini Prodotto ', 'SHOPWINDOW_PRODUCT_IMAGE_HEIGHT', '200', 'Altezza massima delle immagini dei prodotti in vetrina', 8, 55, now(), NULL, NULL)");
		if (!checkConfigKey('SHOPWINDOW_PRODUCT_IMAGE_HEIGHT'))
			tep_db_query("INSERT INTO ".TABLE_CONFIGURATION." (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) VALUES ('Vetrina: Max Altezza Immagini Prodotto ', 'SHOPWINDOW_PRODUCT_IMAGE_HEIGHT', '200', 'Altezza massima delle immagini dei prodotti in vetrina', 8, 55, now(), NULL, NULL)");
			
		// Vetrina -> Aggiornamento Veloce
		if (!checkConfigKey('DISPLAY_SHOPWINDOW'))
			tep_db_query("insert  into ".TABLE_CONFIGURATION." (`configuration_title`,`configuration_key`,`configuration_value`,`configuration_description`,`configuration_group_id`,`sort_order`,`last_modified`,`date_added`,`use_function`,`set_function`) values ('Modifica la presenza in vetrina del prodotto.','DISPLAY_SHOPWINDOW','true','Abilita/Disabilita la presenza in vetrina',300,4,NULL,now(),NULL,'tep_cfg_select_option(array(\'true\', \'false\'),')");
	}
}

if(file_exists(DIR_FS_ADMIN . 'incoming_products.php'))
{
		// colonna in product listing per gli arrivi
	if (!checkConfigKey('PRODUCT_LIST_INCOMINGS'))
		tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Mostra colonna prodotti in arrivo','PRODUCT_LIST_INCOMINGS',4,'Mostra le quantit� in arrivo del prodotto',8,5,now())");
	
	/*	if (!checkConfigKey('PRODUCT_LIST_INCOMINGS'))
		tep_db_query("insert  into ".TABLE_CONFIGURATION." (`configuration_title`,`configuration_key`,`configuration_value`,`configuration_description`,`configuration_group_id`,`sort_order`,`last_modified`,`date_added`,`use_function`,`set_function`) values ('Modifica la presenza in vetrina del prodotto.','DISPLAY_SHOPWINDOW','true','Abilita/Disabilita la presenza in vetrina',300,4,NULL,now(),NULL,'tep_cfg_select_option(array(\'true\', \'false\'),')");
	*/
}

// Plugin pws_prices_purchase_price
if (file_exists(DIR_FS_PWS_PLUGINS_PRICES.'pws_prices_purchase_price.php')) {
	if (!checkConfigKey('DISPLAY_PRICE_COMMISSION'))
		tep_db_query("insert  into `configuration`(`configuration_title`,`configuration_key`,`configuration_value`,`configuration_description`,`configuration_group_id`,`sort_order`,`date_added`,`use_function`,`set_function`) values ('Ricarico del prezzo prodotto.','DISPLAY_PRICE_COMMISSION','true','Abilita/Disabilita la presenza del campo ricarico',300,8,now(),NULL,'tep_cfg_select_option(array(\'true\', \'false\'),');");
}

if (!checkColumn('pws_price_resume',TABLE_ORDERS_PRODUCTS))
		tep_db_query("alter table ".TABLE_ORDERS_PRODUCTS." add column pws_price_resume blob");

// crea le tabelle per i campi extra se installato il modulo
if (file_exists(FILENAME_PRODUCTS_EXTRA_FIELDS))
	{
		  
		$create_extrafields = tep_db_query("CREATE TABLE IF NOT EXISTS  products_extra_fields (
		  products_extra_fields_id int NOT NULL auto_increment,
		  products_extra_fields_name varchar(64) NOT NULL default '',
		  products_extra_fields_order int(3) NOT NULL default '0',
		  products_extra_fields_status tinyint(1) NOT NULL default '1',
		  languages_id INT( 11 ) DEFAULT '0' NOT NULL,
		  category_id INT( 6 ) DEFAULT '0' NOT NULL,
		  products_extra_fields_show_in_order tinyint(1) NOT NULL default '0',	  
		  PRIMARY KEY (products_extra_fields_id)
		);");
			
		$create_products_to_extrafields = tep_db_query("CREATE TABLE IF NOT EXISTS products_to_products_extra_fields (
		  products_id int NOT NULL,
		  products_extra_fields_id int NOT NULL,
		  products_extra_fields_value varchar(64),
		  PRIMARY KEY (products_id,products_extra_fields_id)
		);");
		
	}

// modulo master password 
if (file_exists("mp.php") &&  (!checkConfigKey('MASTER_PASS')))
	tep_db_query("INSERT INTO configuration ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ( 'Master Password', 'MASTER_PASS', 'yourpassword', 'Questa password ti consente di accedere in ogni account utente della sezione pubblica, cambia la password di default immediatamente!', 1, 23, '2008-12-05 07:10:52', '2008-06-15 07:10:52', NULL, NULL)");

// anomalia easypopulate 
if (!checkConfigKey('EP_TEMP_DIRECTORY'))
	tep_db_query("INSERT INTO configuration ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ( 'Directory di Esportazione', 'EP_TEMP_DIRECTORY',  '" . DIR_FS_CATALOG . "temp/', 'Inserire il percorso completo della directory dove salvare i files esportati', 17, 3, '2008-12-05 07:10:52', '2008-06-15 07:10:52', NULL, NULL)");
else // corregge la dir 
	tep_db_query("update `configuration` set `configuration_value` =  '" . DIR_FS_CATALOG . "temp/' where `configuration_key` = 'EP_TEMP_DIRECTORY' ");		

// aggiornamento chiave di configurazione per mostrare i loghi dei produttori.	
	tep_db_query("update `configuration` set `configuration_title` =  'Mostra Filtro Produttore (0=No; 1=Menu, 2=Elenco loghi)',  `configuration_description` =  'Mostra Filtro Produttore nelle sottocategorie (0=disabilita; 1=abilita il menu a tendina, 2=abilita elenco dei loghi)' where `configuration_key` = 'PRODUCT_LIST_FILTER' ");		
	
if (!checkConfigKey('IMAGE_PICKER_CHOICE'))	
	tep_db_query("INSERT INTO configuration ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ( 'Picker Immagini', 'IMAGE_PICKER_CHOICE',  'PWS Picker', 'Selezionare il picker immagini da utilizzare:<br/>HTML Area - compatibile solo con I.Explorer<br/>PWS Picker - compatibile con tutti i browsers<br>Upload Standard - Upload originale Oscommerce', 112, 2, '2008-12-05 07:10:52', '2008-06-15 07:10:52', NULL, NULL)");
	

if (!checkConfigKey('STORE_LOGO_IT'))
	tep_db_query('INSERT INTO configuration ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ( \'Logo Negozio - Italiano\', \'STORE_LOGO_IT\',  \'oscommerce.gif\', \'Impostare il logo da utilizzare per la versione del sito in:<br/><b>Italiano</b>.\', 1, 22, \'2008-12-05 07:10:52\', \'2008-06-15 07:10:52\', NULL, \'$GLOBALS[\\\'pws_html\\\']->setStoreLogo(\\\'it\\\',\')'); 
	
// aggiornamento image picker
$image_picker_query = tep_db_query("SELECT * FROM `configuration` WHERE configuration_key like '%picker%' ");
$image_picker_array = tep_db_fetch_array($image_picker_query);

if (!strstr($image_picker_array['set_function'],'Upload') )
{
tep_db_query("UPDATE `configuration` SET `sort_order` = '2',
`last_modified` = now(),
`use_function` = NULL ,
`configuration_description` = 'Selezionare il picker immagini da utilizzare:<br/>HTML Area - compatibile solo con I.Explorer<br/>PWS Picker - compatibile con tutti i browsers<br>Upload Standard - Upload originale Oscommerce',
`set_function` = 'tep_cfg_select_option(array(''HTML_AREA'', ''PWS Picker'', ''Upload Standard''),' WHERE `configuration_id` = " . $image_picker_array['configuration_id'] . "  ");	
}

// nuovi prodotti correlati
if(!checkColumn("products_id_master", TABLE_PWS_RELATED_PRODUCTS))
		tep_db_query("alter table ".TABLE_PWS_RELATED_PRODUCTS." add column  `products_id_master` smallint(5) unsigned NOT NULL default '0'");

// tep_redirect("index.php");
if (!checkConfigKey('AJAX_CART_ENABLED'))
	tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) VALUES ('Abilita il carrello Ajax', 'AJAX_CART_ENABLED', 'true', 'Se abilitato (true), permette di inserire i prodotti dalla lista degli articoli per categoria senza ricaricare la pagina.', 1, 19, now(), NULL, 'tep_cfg_select_option(array(''true'', ''false''),')");

// tep_redirect("index.php");
if (!checkConfigKey('NEW_TEMPLATE_SYSTEM'))
	tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) VALUES ('Abilita la nuova gestione dei template', 'NEW_TEMPLATE_SYSTEM', 'false', 'Se abilitato (true), permette la nuova gestione dei template con selezione da admin.', 1, 19, now(), NULL, 'tep_cfg_select_option(array(''true'', ''false''),')");

// coupons: esclusione per gruppi clienti
tep_db_query("CREATE TABLE IF NOT EXISTS `discount_coupons_to_customer_groups` (
`coupons_id` VARCHAR( 33 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`customers_group_id` VARCHAR( 11 ) NOT NULL ,
PRIMARY KEY (`coupons_id`,`customers_group_id`)
) ENGINE = MYISAM");

// bonifica tabella immagini multiple
$check_table = mysql_query("show tables like 'pws_products_images' ");
if (mysql_num_rows($check_table) >= 1)
	tep_db_query("update pws_products_images set products_image = REPLACE(products_image, '../images/', '') WHERE 1	");
	
	
/*
 * nuovi banner
 * */
$banner_group_query = tep_db_query("select banners_group  from " . TABLE_BANNERS . " where banners_group = 'paypal' "); 
if (tep_db_num_rows($banner_group_query) <= 0)
tep_db_query("INSERT INTO `banners` ( `banners_title`, `banners_url`, `banners_image`, `banners_group`, `banners_html_text`, `expires_impressions`, `expires_date`, `date_scheduled`, `date_added`, `date_status_change`, `status`, `banners_open_new_windows`, `banners_on_ssl`) VALUES ( 'PayPal', '', '', 'paypal', '<!-- PayPal Logo --><table border=\"0\" cellpadding=\"10\" cellspacing=\"0\" align=\"center\"><tr><td align=\"center\"></td></tr>\r\n<tr><td align=\"center\"><a href=\"#\" onclick=\"javascript:window.open(''https://www.paypal.com/it/cgi-bin/webscr?cmd=xpt/cps/popup/OLCWhatIsPayPal-outside'',''olcwhatispaypal'',''toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=400, height=350'');\"><img  src=\"https://www.paypal.com/it_IT/IT/i/logo/PayPal_mark_180x113.gif\" border=\"0\" alt=\"Marchio di accettazione\"></a></td></tr></table><!-- PayPal Logo -->', 0, NULL, NULL, '2009-10-29 18:12:44', NULL, 1, 1, 1)");

$banner_group_query = tep_db_query("select banners_group  from " . TABLE_BANNERS . " where banners_group = 'trovaprezz' "); 
if (tep_db_num_rows($banner_group_query) <= 0)
tep_db_query("INSERT INTO `banners` ( `banners_title`, `banners_url`, `banners_image`, `banners_group`, `banners_html_text`, `expires_impressions`, `expires_date`, `date_scheduled`, `date_added`, `date_status_change`, `status`, `banners_open_new_windows`, `banners_on_ssl`) VALUES ( 'Trovaprezzi', '', '', 'trovaprezz', '<p align=\"center\"><a href=\"http://www.trovaprezzi.it\" title=\"Presente su TrovaPrezzi\" target=\"_blank\"><img src=\"http://img.trovaprezzi.it/buttons/recommendedby/120x60_v1.gif\" style=\"border:0px\" alt=\"Presente su TrovaPrezzi\"></a>\r\n</p>', 0, NULL, NULL, '2009-10-29 18:25:26', NULL, 1, 1, 1)");

$banner_group_query = tep_db_query("select banners_group  from " . TABLE_BANNERS . " where banners_group = 'kelkoo' "); 
if (tep_db_num_rows($banner_group_query) <= 0)
tep_db_query("INSERT INTO `banners` ( `banners_title`, `banners_url`, `banners_image`, `banners_group`, `banners_html_text`, `expires_impressions`, `expires_date`, `date_scheduled`, `date_added`, `date_status_change`, `status`, `banners_open_new_windows`, `banners_on_ssl`) VALUES ( 'Kelkoo', '', '', 'kelkoo', '<p align=\"center\"><a href=\"http://www.kelkoo.it\" title=\"Presente su Kelkoo\" target=\"_blank\"><img src=\"http://support.kelkoo.no/support/disp/logo.gif\" style=\"border:0px\" alt=\"Presente su Kelkoo\"></a>\r\n</p>', 0, NULL, NULL, '2009-10-29 18:26:58', NULL, 1, 1, 1)");

$banner_group_query = tep_db_query("select banners_group  from " . TABLE_BANNERS . " where banners_group = 'header' "); 
if (tep_db_num_rows($banner_group_query) <= 0)
tep_db_query("INSERT INTO `banners` ( `banners_title`, `banners_url`, `banners_image`, `banners_group`, `banners_html_text`, `expires_impressions`, `expires_date`, `date_scheduled`, `date_added`, `date_status_change`, `status`, `banners_open_new_windows`, `banners_on_ssl`) VALUES ( 'Banner Header', 'index.php', 'banner-iphone3g.jpg', 'header', '', 0, NULL, NULL, '2009-10-30 18:06:19', NULL, 0, 1, 1)");


/* modifiche varie alla tabella config */
tep_db_query("UPDATE `countries` SET `countries_name` = 'Italia' WHERE `countries`.`countries_id` =105 LIMIT 1 ");

/* modifiche per icecat e nuova scheda prodotto */
if(!checkColumn('vpn',TABLE_PRODUCTS))
tep_db_query("ALTER TABLE `products` ADD COLUMN `vpn` VARCHAR(64) NULL");

// deve essere installato il modulo immagini multiple insieme ad icecat  altrimenti non funziona
/*
if(file_exists(DIR_FS_PWS_PLUGINS_APPLICATION.'pws_products_images.php'))
{
tep_db_query("CREATE TABLE IF NOT EXISTS `icecat_products` (`products_id` INT( 11 ) NOT NULL ,`prod_id` VARCHAR( 64 ), `vendor` VARCHAR( 64 ) NOT NULL ,`lang` VARCHAR( 3 ) NOT NULL,`changed` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,`data` TEXT NOT NULL, UNIQUE KEY `products_vendors` (`products_id`, `vendor`, `prod_id`, `lang`))");
if (!checkConfigKey("ICECAT_USER")){
	tep_db_query("INSERT INTO `configuration_group` (`configuration_group_id`, `configuration_group_title`, `configuration_group_description`, `sort_order`, `visible`) VALUES (714, 'ICEcat', 'ICEcat plugin', 417, 0)");
	tep_db_query("INSERT INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`,`sort_order`) VALUES ('ICEcat login', 'ICECAT_USER', 'rroscilli', 'Enter your ICEcat username', 714, 1)");
	tep_db_query("INSERT INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`,`sort_order`) VALUES ('ICEcat password', 'ICECAT_PASS', 'BnfvFd', 'Enter your ICEcat password', 714, 2)");
	}
}

*/

//option sort order
if(!checkColumn('attribute_sort',TABLE_PRODUCTS_OPTIONS_VALUES))
tep_db_query("ALTER TABLE " . TABLE_PRODUCTS_OPTIONS_VALUES . " ADD COLUMN attribute_sort INT UNSIGNED NOT NULL DEFAULT '0'");

if(!checkColumn('attribute_sort',TABLE_PRODUCTS_OPTIONS))
tep_db_query("ALTER TABLE " . TABLE_PRODUCTS_OPTIONS . " ADD COLUMN attribute_sort INT UNSIGNED NOT NULL DEFAULT '0'");

// attribute sort order
if(!checkColumn('products_options_sort_order',TABLE_PRODUCTS_ATTRIBUTES))
tep_db_query("ALTER TABLE products_attributes ADD COLUMN products_options_sort_order INT UNSIGNED NOT NULL DEFAULT '0'");
   
// ajax attribute manager
   			tep_db_query("CREATE TABLE IF NOT EXISTS am_templates (
					`template_id` INT( 5 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`template_name` VARCHAR( 255 ) NOT NULL
				)");
			tep_db_query("CREATE TABLE IF NOT EXISTS am_attributes_to_templates (
					`template_id` INT( 5 ) UNSIGNED NOT NULL ,
					`options_id` INT( 5 ) UNSIGNED NOT NULL ,
					`option_values_id` INT( 5 ) UNSIGNED NOT NULL ,
					`price_prefix` char(1) default '+',
					`options_values_price` decimal(15,4) default 0,
					`products_options_sort_order` int default 0,
                    `weight_prefix` char(1) default '+',
                    `options_values_weight` decimal(6,3) default '0.000',
					INDEX ( `template_id` )
				)");

// icecat data 
// ALTER TABLE `icecat_products` CHANGE `data` `data` TEXT CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL 			

// tep_db_query("CREATE TABLE IF NOT EXISTS `icecat_products` (`products_id` INT( 11 ) NOT NULL ,`prod_id` VARCHAR( 64 ), `vendor` VARCHAR( 64 ) NOT NULL ,`lang` VARCHAR( 3 ) NOT NULL,`changed` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,`data` TEXT NOT NULL, UNIQUE KEY `products_vendors` (`products_id`, `vendor`, `prod_id`, `lang`))");
			
// modifica impostazioni immagine principale nella scheda prodotto
if (!checkConfigKey('PRODUCT_IMAGE_WIDTH'))
	tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, 								 `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) 
									VALUES ('Larghezza immagine scheda prodotto',			'PRODUCT_IMAGE_WIDTH',	'320'					  ,'Larghezza dell\'immagine principale nella scheda prodotto',4,0,'2010-02-25 14:58:39',NULL, NULL)");

if (!checkConfigKey('PRODUCT_IMAGE_HEIGHT'))
	tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, 								 `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) 
									VALUES ('Lunghezza immagine scheda prodotto',			'PRODUCT_IMAGE_HEIGHT',	'320'					  ,'Lughezza dell\'immagine principale nella scheda prodotto',4,0,'2010-02-25 14:58:39',NULL, NULL)");
	
// se � installato il plugin delle immagini multiple 
$check_table = mysql_query("show tables like 'pws_products_images' ");
if (mysql_num_rows($check_table) >= 1)
{
	if (!checkConfigKey('PRODUCT_IMAGE_WIDTH_THUMB'))
	tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, 								 `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) 
									VALUES ('Larghezza anteprime immagini multiple',			'PRODUCT_IMAGE_WIDTH_THUMB',	'80'					  ,'Larghezza delle anteprime del modulo multi image nella scheda prodotto',4,15,'2010-02-25 14:58:39',NULL, NULL)");
	
	if (!checkConfigKey('PRODUCT_IMAGE_HEIGHT_THUMB'))
	tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, 								 `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) 
									VALUES ('Lunghezza anteprime immagini multiple',			'PRODUCT_IMAGE_HEIGHT_THUMB',	'80'					  ,'Larghezza delle anteprime del modulo multi image nella scheda prodotto',4,16,'2010-02-25 14:58:39',NULL, NULL)");
	
}

// patch per mysql 5.0.1, si blocca su default value = CURRENT_TIMESTAMP
//	tep_db_query("ALTER TABLE `icecat_products` CHANGE `changed` `changed` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NULL DEFAULT NULL");

// ppec 
// crea le tabelle per ppec
 tep_db_query("
		CREATE TABLE IF NOT EXISTS `ppec_payer` (
		  `customers_id` int(15) default NULL,
		  `payerid` varchar(15) default NULL,
		  KEY `payerid` (`payerid`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1;
 	");

tep_db_query("
		CREATE TABLE IF NOT EXISTS `ppec_transaction` (
		  `transactionid` varchar(50) default NULL,
		  `paymentstatus` varchar(20) default NULL,
		  `orders_id` int(15) default NULL,
		  KEY `transactionid` (`transactionid`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1;
 	 	");
 tep_db_query("	
		CREATE TABLE IF NOT EXISTS `ppec_transaction_status` (
		  `transaction_status` varchar(20) default NULL,
		  `status_id` int(15) NOT NULL default '0',
		  PRIMARY KEY  (`status_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;
 	 	");
 
 
 
// copia il paypal id (mail) dal vecchio modulo
$paypal_ppec_id_query =  tep_db_query("select configuration_value from configuration where configuration_key = 'MODULE_PAYMENT_PPEC_ID'");
$paypal_ppec_id_array = tep_db_fetch_array($paypal_ppec_id_query);

if ( ($paypal_ppec_id_array['configuration_value'] == '') && (tep_db_num_rows($paypal_ppec_id_query) >= '1') )
{
$paypal_id_query = tep_db_query("select configuration_value from configuration where configuration_key = 'MODULE_PAYMENT_PAYPAL_ID'");
$paypal_id_array = tep_db_fetch_array($paypal_id_query);
tep_db_query("update configuration set configuration_value = '". $paypal_id_array['configuration_value']  ."' where configuration_key = 'MODULE_PAYMENT_PPEC_ID' ");
}


 // inserisce status per ppec
 $res = tep_db_query("select * from orders_status where orders_status_name = 'PayPal-Denied' ");
 
if (mysql_num_rows($res) <= 0)
{
	tep_db_query("ALTER TABLE `orders_status` CHANGE `orders_status_id` `orders_status_id` INT( 11 ) NOT NULL AUTO_INCREMENT ;");
	tep_db_query("INSERT INTO `orders_status` (`orders_status_id`, `language_id`, `orders_status_name`, `public_flag`, `downloads_flag`) VALUES ('', 4, 'PayPal-Completed', 1, 0);");
	tep_db_query("INSERT INTO `orders_status` (`orders_status_id`, `language_id`, `orders_status_name`, `public_flag`, `downloads_flag`) VALUES ('', 5, 'PayPal-Completed', 1, 0);");
	tep_db_query("INSERT INTO `orders_status` (`orders_status_id`, `language_id`, `orders_status_name`, `public_flag`, `downloads_flag`) VALUES ('', 4, 'PayPal-Denied', 1, 0);");
	tep_db_query("INSERT INTO `orders_status` (`orders_status_id`, `language_id`, `orders_status_name`, `public_flag`, `downloads_flag`) VALUES ('', 5, 'PayPal-Denied', 1, 0);");
	tep_db_query("INSERT INTO `orders_status` (`orders_status_id`, `language_id`, `orders_status_name`, `public_flag`, `downloads_flag`) VALUES ('', 4, 'PayPal-Expired', 1, 0);");
	tep_db_query("INSERT INTO `orders_status` (`orders_status_id`, `language_id`, `orders_status_name`, `public_flag`, `downloads_flag`) VALUES ('', 5, 'PayPal-Expired', 1, 0);");
	tep_db_query("INSERT INTO `orders_status` (`orders_status_id`, `language_id`, `orders_status_name`, `public_flag`, `downloads_flag`) VALUES ('', 4, 'PayPal-Failed', 1, 0);");
	tep_db_query("INSERT INTO `orders_status` (`orders_status_id`, `language_id`, `orders_status_name`, `public_flag`, `downloads_flag`) VALUES ('', 5, 'PayPal-Failed', 1, 0);");
	tep_db_query("INSERT INTO `orders_status` (`orders_status_id`, `language_id`, `orders_status_name`, `public_flag`, `downloads_flag`) VALUES ('', 4, 'PayPal-None', 1, 0);");
	tep_db_query("INSERT INTO `orders_status` (`orders_status_id`, `language_id`, `orders_status_name`, `public_flag`, `downloads_flag`) VALUES ('', 5, 'PayPal-None', 1, 0);");
	tep_db_query("INSERT INTO `orders_status` (`orders_status_id`, `language_id`, `orders_status_name`, `public_flag`, `downloads_flag`) VALUES ('', 4, 'PayPal-Pending', 1, 0);");
	tep_db_query("INSERT INTO `orders_status` (`orders_status_id`, `language_id`, `orders_status_name`, `public_flag`, `downloads_flag`) VALUES ('', 5, 'PayPal-Pending', 1, 0);");
	tep_db_query("INSERT INTO `orders_status` (`orders_status_id`, `language_id`, `orders_status_name`, `public_flag`, `downloads_flag`) VALUES ('', 4, 'PayPal-Processed', 1, 0);");
	tep_db_query("INSERT INTO `orders_status` (`orders_status_id`, `language_id`, `orders_status_name`, `public_flag`, `downloads_flag`) VALUES ('', 5, 'PayPal-Processed', 1, 0);");
	tep_db_query("INSERT INTO `orders_status` (`orders_status_id`, `language_id`, `orders_status_name`, `public_flag`, `downloads_flag`) VALUES ('', 4, 'PayPal-Refunded', 1, 0);");
	tep_db_query("INSERT INTO `orders_status` (`orders_status_id`, `language_id`, `orders_status_name`, `public_flag`, `downloads_flag`) VALUES ('', 5, 'PayPal-Refunded', 1, 0);");
	tep_db_query("INSERT INTO `orders_status` (`orders_status_id`, `language_id`, `orders_status_name`, `public_flag`, `downloads_flag`) VALUES ('', 4, 'PayPal-Reversal', 1, 0);");
	tep_db_query("INSERT INTO `orders_status` (`orders_status_id`, `language_id`, `orders_status_name`, `public_flag`, `downloads_flag`) VALUES ('', 5, 'PayPal-Reversal', 1, 0);");
	tep_db_query("INSERT INTO `orders_status` (`orders_status_id`, `language_id`, `orders_status_name`, `public_flag`, `downloads_flag`) VALUES ('', 4, 'PayPal-Reversed', 1, 0);");
	tep_db_query("INSERT INTO `orders_status` (`orders_status_id`, `language_id`, `orders_status_name`, `public_flag`, `downloads_flag`) VALUES ('', 5, 'PayPal-Reversed', 1, 0);");
	tep_db_query("INSERT INTO `orders_status` (`orders_status_id`, `language_id`, `orders_status_name`, `public_flag`, `downloads_flag`) VALUES ('', 4, 'Voided', 1, 0);");
	tep_db_query("INSERT INTO `orders_status` (`orders_status_id`, `language_id`, `orders_status_name`, `public_flag`, `downloads_flag`) VALUES ('', 5, 'Voided', 1, 0);");
}

// gruppo per google adwords e analytics
if (!checkConfigKey('GOOGLE_UA'))
{
	// creo il gruppo Google
	tep_db_query("INSERT INTO configuration_group (
													`configuration_group_id` ,
													`configuration_group_title` ,
													`configuration_group_description` ,
													`sort_order` ,
													`visible`
													)
													VALUES (
													'600613', 'Google', 'Parametri per google analytics e adwords', '310', '1'
													)
				");
	
	tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, 								 `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) 
									VALUES ('Google Analytics UA',			'GOOGLE_UA',	''					  ,'Inserisci il tuo UA fornito da google analytics, es. UA-600613-1',600613,0,'2010-07-01 18:09:06',NULL, NULL)");
	
	tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, 								 `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) 
									VALUES ('Google Convertion ID',	'GOOGLE_CONVERSION_ID',	''					  ,'Inserisci il tuo ID fornito da google ADWords nella pagina delle conversioni, es. 1039600613',600613,1,'2010-07-01 18:09:06',NULL, NULL)");
		
	tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, 								 `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) 
									VALUES ('Google Conversione Contatti',	'GOOGLE_CONTACT_LABEL',	''		   ,'Etichetta per la conversione delle richieste sul form Contattaci, es. KEghCNPO2wEQrN_f7wM',600613,2,'2010-07-01 18:09:06',NULL, NULL)");
	tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, 								 `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) 
									VALUES ('Google Conversione Registrazioni',	'GOOGLE_REGISTRATION_LABEL', '','Etichetta per la conversione delle registrazioni, es. PONnkjnkO2wEQrN_f7wM',600613,3,'2010-07-01 18:09:06',NULL, NULL)");
	tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, 								 `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) 
									VALUES ('Google Conversione Acquisti',	'GOOGLE_ACQUISTO_LABEL',	''	    ,'Etichetta per la conversione delle vendite, es. pojnoinNIOnKDJBKd_f7wM',600613,4,'2010-07-01 18:09:06',NULL, NULL)");
		
}

// gruppo per comparatori prezzo
if (!checkConfigKey('PRICE_COMP_ONLYAVAILABLE'))
{
	// creo il gruppo Comparatori
	tep_db_query("INSERT INTO configuration_group (
													`configuration_group_id` ,
													`configuration_group_title` ,
													`configuration_group_description` ,
													`sort_order` ,
													`visible`
													)
													VALUES (
													'35909', 'Comparatori', 'Parametri per export su comparatori prezzo (es. Trovaprezzi)', '500', '1'
													)
				");
	
	tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, 								 `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) 
									VALUES ('Esporta solo Disponibili',			'PRICE_COMP_ONLYAVAILABLE',	''					  ,'Se abiliti questa voce, solo i prodotti disponibili verranno esportati sui comparatori',35909,0,'2010-07-01 18:09:06',NULL, 'tep_cfg_select_option(array(''True'', ''False''),')");
}
if (!checkConfigKey('PRICE_COMP_CLAIM'))
	tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, 								 `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) 
									VALUES ('Frase da inserire prima della descrizione (max 255 caratteri) ',			'PRICE_COMP_CLAIM',	''					  ,'Questa frase sar� inserita all\'inizio della descrizione di ogni articolo',35909,1,'2010-07-01 18:09:06',NULL, NULL)");

	
if (!checkConfigKey('KELKOO_ORGANIZATION'))
		tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, 								 `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) 
									VALUES ('ID Merchant fornito da Kelkoo per il TradeDoubler',			'KELKOO_ORGANIZATION',	''					  ,'Codice che identifica univocamente il merchant necessario per l\'invio dei dati a TradeDoubler (in mancanza di questo codice il TD non funzioner�!)',35909,2,'2010-07-01 18:09:06',NULL, NULL)");

if (!checkConfigKey('KELKOO_EVENT'))
		tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, 								 `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) 
									VALUES ('ID Evento fornito da Kelkoo per il TradeDoubler',			'KELKOO_EVENT',	''					  ,'Codice che identifica univocamente l\'evento Kelkoo, necessario per l\'invio dei dati a TradeDoubler (in mancanza di questo codice il TD non funzioner�!)',35909,3,'2010-07-01 18:09:06',NULL, NULL)");
		
	
// integrazione con icecat FULL solo URL
// if (!checkConfigKey('ICECAT_SHOPNAME'))
if (isset($_GET['icecat']) ) // attivazione icecat modulo a pagamento
{
	// creo il gruppo ICECAT 
	tep_db_query("INSERT INTO configuration_group (
													`configuration_group_id` ,
													`configuration_group_title` ,
													`configuration_group_description` ,
													`sort_order` ,
													`visible`
													)
													VALUES (
													'163647', 'IceCat', 'Parametri per la visualizzazione delle schede prodotto IceCat.', '501', '1'
													)
				");
	
		
		

		tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, 								 `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) 
									VALUES ('ID Registrazione su Icecat detto anche Shopname',			'ICECAT_SHOPNAME',	''					  ,'Codice che identifica univocamente il merchant presso Icecat, consigliato l\'abbonamento FULL di Icecat. Per informazioni <a href=\"http://www.icecat.biz/\" target=\"_blank\">http://www.icecat.biz/</a>',163647,1,'2010-07-01 18:09:06',NULL, NULL)");
	
		tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, 								 `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) 
									VALUES ('Sostituisci sempre l\'immagine del prodotto con quella di icecat', 'ICECAT_IMAGE',	''					  ,'Se impostato a true, l\'immagine IceCat verr� inserita come immagine del prodotto. In caso contrario verr� inserita solo nei prodotti che non hanno immagine',163647,2,'2010-07-01 18:09:06',NULL, 'tep_cfg_select_option(array(''True'', ''False''),')");
		
}
		
// mail di conferma d'ordine in html editabile da admin
tep_db_query("
CREATE TABLE IF NOT EXISTS `eorder_text` (
  `eorder_text_id` tinyint(3) unsigned NOT NULL default '0',
  `language_id` tinyint(3) unsigned NOT NULL default '1',
  `eorder_text_one` text
)") ;

tep_db_query(" UPDATE `configuration` SET `configuration_value` = 'true' WHERE configuration_key = 'EMAIL_USE_HTML'");

if (mysql_numrows(tep_db_query("select * from eorder_text where 1")) <= 0 )
{
tep_db_query("ALTER TABLE `eorder_text` ADD PRIMARY KEY ( `eorder_text_id` , `language_id` ) ");	
tep_db_query("INSERT INTO `eorder_text` (`eorder_text_id`, `language_id`, `eorder_text_one`) VALUES(2, 5, '<br /> <br /> <table width=\"808\" border=\"0\">     <tbody>         <tr>             <td width=\"800\" height=\"20\" bgcolor=\"#3287c8\" colspan=\"2\">             <p align=\"center\"><font size=\"3\" face=\"Calibri\"><b><-STORE_NAME->&nbsp; -&nbsp; ORDER CONFIRMATION </b></font></p>             </td>         </tr>         <tr>             <td width=\"800\" height=\"10\" colspan=\"2\">&nbsp;</td>         </tr>         <tr>             <td width=\"800\" bgcolor=\"#cbcbf3\" colspan=\"2\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font face=\"Calibri\"><b>Order''s Data<br />             </b></font></p>             </td>         </tr>         <tr>             <td width=\"179\" bgcolor=\"#ebebeb\" align=\"left\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><b>Order Number<br />             </b></font></p>             </td>             <td width=\"615\" bgcolor=\"#ebebeb\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><-insert_id-></font></p>             </td>         </tr>         <tr>             <td width=\"179\" bgcolor=\"#ebebeb\" align=\"left\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><b>Order Details<br />             </b></font></p>             </td>             <td width=\"615\" bgcolor=\"#ebebeb\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><a href=\"<-INVOICE_URL->\" target=\"_blank\"><font size=\"2\" face=\"Calibri\"><-INVOICE_URL-></font></a></p>             </td>         </tr>         <tr>             <td width=\"179\" bgcolor=\"#ebebeb\" align=\"left\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><b>Order Date<br />             </b></font></p>             </td>             <td width=\"615\" bgcolor=\"#ebebeb\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><-DATE_ORDERED-></font></p>             </td>         </tr>     </tbody> </table> <table width=\"808\" border=\"0\">     <tbody>         <tr>             <td width=\"800\" bgcolor=\"#cbcbf3\" colspan=\"4\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font face=\"Calibri\"><b>Items</b></font></p>             </td>         </tr>         <tr>             <td width=\"140\" bgcolor=\"#ebebeb\" style=\"\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font face=\"Calibri\"><b>Code</b></font></p>             </td>             <td width=\"540\" bgcolor=\"#ebebeb\" style=\"\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><span style=\"font-weight: bold;\">Item</span></p>             </td>             <td width=\"40\" bgcolor=\"#ebebeb\" style=\"\">             <p align=\"center\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt;\"><font face=\"Calibri\"><b>Q.ty</b></font></p>             </td>             <td width=\"80\" bgcolor=\"#ebebeb\" style=\"\">             <p align=\"right\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 4px 0pt 0pt;\"><font face=\"Calibri\"><b>Price</b></font></p>             </td>         </tr>         <-Item_List->     </tbody> </table> <table width=\"808\" border=\"0\">     <tbody>         <tr>             <-List_Total->         </tr>     </tbody> </table> <table width=\"808\" border=\"0\">     <tbody>         <tr>             <td width=\"800\" height=\"10\">             <p style=\"word-spacing: 0pt; line-height: 100%; margin: 0pt;\">&nbsp;</p>             </td>         </tr>     </tbody> </table> <table width=\"808\" border=\"0\">     <tbody>         <tr>             <td width=\"50%\" bgcolor=\"#cbcbf3\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font face=\"Calibri\"><b>Billing Address<br />             </b></font></p>             </td>             <td width=\"50%\" bgcolor=\"#cbcbf3\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font face=\"Calibri\"><b>Delivery Address<br />             </b></font></p>             </td>         </tr>         <tr>             <td width=\"50%\" bgcolor=\"#ebebeb\" align=\"left\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><b><-BILL_Adress-></b></font></p>             </td>             <td width=\"50%\" bgcolor=\"#ebebeb\" align=\"left\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><b><-DELIVERY_Adress-></b></font></p>             </td>         </tr>     </tbody> </table> <table width=\"808\" border=\"0\">     <tbody>         <tr>             <td width=\"800\" bgcolor=\"#cbcbf3\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font face=\"Calibri\"><b>Customer''s Comment<br />             </b></font></p>             </td>         </tr>         <tr>             <td width=\"800\">             <p style=\"word-spacing: 0pt; line-height: 100%; margin: 0pt;\"><-Customer_Comments->&nbsp;</p>             </td>         </tr>     </tbody> </table> <table width=\"808\" border=\"0\">     <tbody>         <tr>             <td width=\"30%\" bgcolor=\"#ebebeb\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><b>Payment Method<br />             </b></font></p>             </td>             <td width=\"70%\" bgcolor=\"#ebebeb\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><-Payment_Modul_Text-><-Payment_Modul_Text_Footer-> </font></p>             </td>         </tr>     </tbody> </table>')");
tep_db_query("INSERT INTO `eorder_text` (`eorder_text_id`, `language_id`, `eorder_text_one`) VALUES(2, 4, '<br /> <br /> <table width=\"808\" border=\"0\">     <tbody>         <tr>             <td width=\"800\" height=\"20\" bgcolor=\"#3287c8\" colspan=\"2\">             <p align=\"center\"><font size=\"3\" face=\"Calibri\"><b><-STORE_NAME->&nbsp; -&nbsp;       CONFERMA ORDINE </b></font></p>             </td>         </tr>         <tr>             <td width=\"800\" height=\"10\" colspan=\"2\">&nbsp;</td>         </tr>         <tr>             <td width=\"800\" bgcolor=\"#cbcbf3\" colspan=\"2\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font face=\"Calibri\"><b>Dati       Ordine</b></font></p>             </td>         </tr>         <tr>             <td width=\"179\" bgcolor=\"#ebebeb\" align=\"left\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><b>Ordine       Numero</b></font></p>             </td>             <td width=\"615\" bgcolor=\"#ebebeb\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><-insert_id-></font></p>             </td>         </tr>         <tr>             <td width=\"179\" bgcolor=\"#ebebeb\" align=\"left\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><b>Dettaglio       ordine</b></font></p>             </td>             <td width=\"615\" bgcolor=\"#ebebeb\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><a href=\"<-INVOICE_URL->\" target=\"_blank\"><font size=\"2\" face=\"Calibri\"><-INVOICE_URL-></font></a></p>             </td>         </tr>         <tr>             <td width=\"179\" bgcolor=\"#ebebeb\" align=\"left\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><b>Data       ordine</b></font></p>             </td>             <td width=\"615\" bgcolor=\"#ebebeb\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><-DATE_ORDERED-></font></p>             </td>         </tr>     </tbody> </table> <table width=\"808\" border=\"0\">     <tbody>         <tr>             <td width=\"800\" bgcolor=\"#cbcbf3\" colspan=\"4\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font face=\"Calibri\"><b>Prodotti</b></font></p>             </td>         </tr>         <tr>             <td width=\"140\" bgcolor=\"#ebebeb\" style=\"\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font face=\"Calibri\"><b>Codice</b></font></p>             </td>             <td width=\"540\" bgcolor=\"#ebebeb\" style=\"\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font face=\"Calibri\"><b>Articolo</b></font></p>             </td>             <td width=\"40\" bgcolor=\"#ebebeb\" style=\"\">             <p align=\"center\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt;\"><font face=\"Calibri\"><b>Q.t&agrave;</b></font></p>             </td>             <td width=\"80\" bgcolor=\"#ebebeb\" style=\"\">             <p align=\"right\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 4px 0pt 0pt;\"><font face=\"Calibri\"><b>Importo</b></font></p>             </td>         </tr>         <-Item_List->     </tbody> </table> <table width=\"808\" border=\"0\">     <tbody>         <tr>             <-List_Total->         </tr>     </tbody> </table> <table width=\"808\" border=\"0\">     <tbody>         <tr>             <td width=\"800\" height=\"10\">             <p style=\"word-spacing: 0pt; line-height: 100%; margin: 0pt;\">&nbsp;</p>             </td>         </tr>     </tbody> </table> <table width=\"808\" border=\"0\">     <tbody>         <tr>             <td width=\"50%\" bgcolor=\"#cbcbf3\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font face=\"Calibri\"><b>Indirizzo       di Fatturazione</b></font></p>             </td>             <td width=\"50%\" bgcolor=\"#cbcbf3\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font face=\"Calibri\"><b>Indirizzo       di Spedizione</b></font></p>             </td>         </tr>         <tr>             <td width=\"50%\" bgcolor=\"#ebebeb\" align=\"left\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><b><-BILL_Adress-></b></font></p>             </td>             <td width=\"50%\" bgcolor=\"#ebebeb\" align=\"left\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><b><-DELIVERY_Adress-></b></font></p>             </td>         </tr>     </tbody> </table> <table width=\"808\" border=\"0\">     <tbody>         <tr>             <td width=\"800\" bgcolor=\"#cbcbf3\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font face=\"Calibri\"><b>Commenti del Cliente</b></font></p>             </td>         </tr>         <tr>             <td width=\"800\" bgcolor=\"#ebebeb\" align=\"left\">             <p style=\"word-spacing: 0pt; line-height: 100%; margin: 0pt;\"><-Customer_Comments->&nbsp;</p>             </td>         </tr>     </tbody> </table> <table width=\"808\" border=\"0\">     <tbody>         <tr>             <td width=\"30%\" bgcolor=\"#ebebeb\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><b>Metodo       di pagamento</b></font></p>             </td>             <td width=\"70%\" bgcolor=\"#ebebeb\">             <p align=\"left\" style=\"word-spacing: 0pt; text-indent: 0pt; line-height: 100%; margin: 0pt 0pt 0pt 10px;\"><font size=\"2\" face=\"Calibri\"><-Payment_Modul_Text-><-Payment_Modul_Text_Footer-> </font></p>             </td>         </tr>     </tbody> </table>')");
}


// pulizia per bug address_book
 tep_db_query("delete FROM `customers` WHERE customers_default_address_id = '0'");
 tep_db_query("delete FROM `customers` WHERE customers_email_address = ''");
 
 // bonifica produttori importati da distributori
 tep_db_query("ALTER TABLE `manufacturers_info` CHANGE `languages_id` `languages_id` INT( 11 ) NOT NULL DEFAULT '4'");
 tep_db_query(" UPDATE `manufacturers_info` SET languages_id = '4' WHERE languages_id = '0' ");
 
// cancella seo url originali
  tep_db_query("delete FROM " .TABLE_CONFIGURATION ." WHERE configuration_key = 'SEARCH_ENGINE_FRIENDLY_URLS'");
 
// prodotto solo catalogo non in vendita
	if (!checkColumn('products_onlyshow',TABLE_PRODUCTS))
		tep_db_query("alter table ".TABLE_PRODUCTS." add column products_onlyshow char(1) not null default '0' after products_status");
  
// configurazione aspetto prodotto non in vendita: se a true mostra comunque il prezzo anche se non si può acquistare
	if (!checkConfigKey('PRODUCTS_ONLYSHOW_PRICE'))
		tep_db_query("insert configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Mostra il prezzo dei prodotti non in vendita diretta','PRODUCTS_ONLYSHOW_PRICE','false','Se abilitato (true) mostra i prezzi dei prodotti non in vendita diretta',8,30,'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
		
		
  // fix degli specials
  if (isset($_GET['Specialfix']) && ($_GET['Specialfix'] == 'true') )
  {
  	// rimuove i duplicati
  		$duplicates=array();
		$fixquery=tep_db_query("SELECT products_id, COUNT(products_id) AS NumOccurrences FROM ".TABLE_SPECIALS." GROUP BY specials_id HAVING ( COUNT(products_id) > 1 )");
		while ($dup=tep_db_fetch_array($fixquery))
			$duplicates[$dup['products_id']]=$dup['NumOccurrences']-1;
		reset($duplicates);
		foreach ($duplicates as $products_id=>$limit)
			tep_db_query("delete from ".TABLE_SPECIALS." where products_id=$products_id order by specials_date_added limit $limit");

		tep_db_query("delete from ".TABLE_PWS_SPECIALS);
  	
  	
  		$specialsQuery=tep_db_query("select * from ".TABLE_SPECIALS." s left join ".TABLE_PRODUCTS." p on (p.products_id=s.products_id)");
		while ($special=tep_db_fetch_array($specialsQuery)){
			$sql_data_array=array(
				'specials_id'=>$special['specials_id']
				,'products_id'=>$special['products_id']
				,'pws_specials_discount'=>100.0*(1.0-$special['specials_new_products_price']/$special['products_price'])
			);
			tep_db_perform(TABLE_PWS_SPECIALS,$sql_data_array);
		}
  }
  
  // configurazione bartolini easyspedweb
  	// creo il gruppo BARTOLINI
 if (file_exists('./bartolinicsv.php') )
  	{
  	if (!checkConfigKey('BARTOLINI_CODICE_UTENTE'))
  	{	
	tep_db_query("INSERT INTO configuration_group (
													`configuration_group_id` ,
													`configuration_group_title` ,
													`configuration_group_description` ,
													`sort_order` ,
													`visible`
													)
													VALUES (
													'84970', 'Bartolini EasyspedWeb', 'Parametri per modulo Bartolini EasyspedWeb.', '600', '1'
													)
				");
  	}
	if (!checkConfigKey('BARTOLINI_CODICE_UTENTE'))
	tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, 								 `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) 
									VALUES ('Codice Utente Bartolini',			'BARTOLINI_CODICE_UTENTE',	''					  ,'Indicare il codice utente fornito da Bartolini di 7 cifre (es. 1122334) ',84970,1,'2011-08-09 12:41:45',NULL, NULL)");
	
	if (!checkConfigKey('BARTOLINI_FILIALE_PARTENZA'))
	tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, 								 `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) 
									VALUES ('Codice Filiale di partenza',	'BARTOLINI_FILIALE_PARTENZA',	''					  ,'Indicare il codice filiale di partenza fornito da Bartolini di 3 cifre (es. 109)',84970,2,'2011-08-09 12:41:45',NULL, NULL)");

	if (!checkConfigKey('BARTOLINI_RIFERIMENTO_MITTENTE'))
	tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, 								 `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) 
									VALUES ('Riferimento mittente alfanumerico',	'BARTOLINI_RIFERIMENTO_MITTENTE',	''					  ,'Indicare il riferimento mittente di 3 lettere alfanumerico (Es. ABC) in genere abbreviazione del nome della ditta',84970,3,'2011-08-09 12:41:45',NULL, NULL)");

	if (!checkConfigKey('BARTOLINI_ASSICURATA'))
			tep_db_query("INSERT INTO configuration (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, 								 `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) 
									VALUES ('Assicurazione sulle spedizioni?', 'BARTOLINI_ASSICURATA',	''	 ,'Se impostato a true, le spedizioni verranno tutte inviate come assicurate per l\'importo degli articoli ordinati. In caso contrario verr� inserita solo negli ordini che hanno spedizione espressamente assicurata',84970,4,'2011-08-09 12:41:45',NULL, 'tep_cfg_select_option(array(''True'', ''False''),')");
	
	
  	}
 echo "Database aggiornato<br>";
 echo "Per sistemare le tabelle delle offerte, passare il parametro Specialfix in get = a true <br>";
 echo "Per riparare e ottimizzare il db inviare il parametro optimize = true <br>";
	
 
 if (isset($_GET['optimize']) && ($_GET['optimize'] == 'true') )
  {
 echo "Ottimizzazione DataBase in corso...<br>";
   $SQL = "OPTIMIZE TABLE ";
   $result = mysql_list_tables(DB_DATABASE);
   // print_r(mysql_fetch_array($result));
   $i = 0;
   while ($i < mysql_num_rows($result)) {
      $name_table = mysql_tablename($result, $i);
   //   echo "Ottimizzazione tabella: $name_table .....<BR>";
      $SQL .=  $name_table;
      $SQL .= ",";
      $i++;
   }
   $SQL = substr($SQL,0,strlen($SQL)-1);
   $result_set = mysql_query($SQL);
  // print_r(mysql_fetch_array($result_set));
   
   mysql_free_result($result_set);
   echo "Ottimizzazione terminata con successo";
  }
/*
$url = 'http://svn.pwshosting.net:8080/svn/oscommerce_pws';
$command = `svn info`;
$output = shell_exec($command);
print_r($output);

print "status ". $var;
*/
 
// $output = shell_exec('sudo -u root -S /usr/bin/svn info');
// echo "<pre>$output</pre>";

// echo exec('echo "annarita" | sudo -u root -S /usr/bin/svn info', $output);
// print_r($output);


?>