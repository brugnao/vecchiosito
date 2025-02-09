<?php
/*
 * @filename:	pws_engine
 * @version:	0.47
 * @revision:	1347
 * @project:	PWS
 *
 * @author:		Riccardo Roscilli <info@oscommerce.it>
 * @created:	15/mag/07
 * @modified:	18/jan/08 13:00:38
 *
 * @copyright:	2006-2008	Riccardo Roscilli @ PWS
 *
 * @desc:	Modulo principale per il motore PWS
 *
 * @TODO:		
 */


class pws_engine extends pws_module{
	// Variabili private del modulo
	var	$nullByRef=NULL;

	// SuperClassi
	var $_pws_prices;		// Gestore dei plugins prezzi e gestore dei prezzi
	var $_pws_clothing=NULL;		// Gestore di negozi con capi di vestiario

	// Plugins
	var $_pluginsByType;	// Plugins installati e caricati. Sono raggruppati per tipo
	var $_pluginsById;		// Plugins installati e caricati, memorizzati per id

	// Mappatura delle funzioni
	var $plugin_hooks=array();		// Array associativo. Codice della funzione => array('plugin_code'=>codice del plugin, 'plugin_method'=>metodo da invocare)

	// Configurazione del motore
	var $extra_plugins_to_install=array(
	);
	
	// Configurazione Modulo
	var $version_const='0.47';	// Impostare qui la versione del codice del modulo
	var $versionKeyName='MODULE_PWS_ENGINE_VERSION';	// (String) Inserire qui il nome della chiave che identifica la versione del modulo
	var $statusKeyName='MODULE_PWS_STATUS';	// (String) Inserire qui il nome della chiave che identifica lo stato di abilitazione del modulo
	var $debugKeyName='MODULE_PWS_DEBUG';		// [Opzionale] (String) Inserire qui la chiave di configurazione usata dal modulo per settare lo stato di debug. Se inserita, questa classe imposterà automaticamente la variabile debugmode corentemente
	var $configuration_group_id='555';	// Impostare qui il configuration_group_id del modulo
	var $dropTablesOnRemove=false;	// Eseguire l'override per rimuovere le tables durante la rimozione del modulo
	var $deleteConfigKeysOnRemove=false;	// Eseguire l'override per rimuovere le impostazioni di configurazione durante la rimozione del modulo
	var $messageStackClassname='pws_engine';	// Classe da utilizzare per i messaggi di messageStack

	var $configuration_group_def=array(
		'555'=>array(
			'configuration_group_title'=>'PWS Engine'
			,'configuration_group_description'=>'Sistema a plugins PWS'
			,'visible'=>'1'
		)
	);	// Array Associativo : configuration_group_id => array(field_name => field_value)
	var $configKeys=array(
		'MODULE_PWS_STATUS'=>array(
				'configuration_title'=>STRING_PWS_STATUS
				,'configuration_value'=>'true'
				,'configuration_description'=>STRING_PWS_STATUS_DESC
				,'set_function'=>'tep_cfg_select_option(array(\'true\', \'false\'), '
			)
		,'MODULE_PWS_ENGINE_VERSION'=>array(
				'configuration_title'=>STRING_PWS_VERSION
				,'configuration_description'=>STRING_PWS_VERSION_DESC
			)
		,'MODULE_PWS_DEBUG'=>array(
				'configuration_title'=>STRING_PWS_DEBUG
				,'configuration_value'=>'false'
				,'configuration_description'=>STRING_PWS_DEBUG_DESC
				,'set_function'=>'tep_cfg_select_option(array(\'true\', \'false\'), '
			)
//		,'MODULE_PWS_UPDATE'=>array(
//				'configuration_title'=>STRING_PWS_UPDATE
//				,'configuration_description'=>STRING_PWS_UPDATE_DESC
//				,'use_function'=>'NULL'
//			)
	);	// Array associativo configuration_key => array(field_name=>field_value)
	var $tableDefines=array(
			TABLE_PWS_PLUGINS=>
"(
  `plugin_id` int(11) NOT NULL auto_increment,
  `plugin_code` varchar(64) NOT NULL default '',
  `plugin_version` varchar(64) NOT NULL default '',
  `plugin_type` varchar(64) NOT NULL default '',
  `plugin_status` char(1) default '1',
  `plugin_sort_order` int(5) default '0',
  `plugin_date_added` datetime default NULL,
  `plugin_date_updated` datetime default NULL,
  PRIMARY KEY  (`plugin_id`)
)"
/*			,TABLE_PWS_STRINGS=>
"(
  `string_id` int(10) default NULL,
  `string_key` varchar(255) default NULL,
  `language_id` char(3) default NULL,
  `plugin_id` int(10) default NULL,
  `content` text,
  PRIMARY KEY `string_id` (`string_id`),
  KEY `string_key` (`string_key`)
)"*/
/*			,TABLE_PWS_CONFIGURATION=>
"
(
  `plugin_config_id` int(11) NOT NULL auto_increment,
  `plugin_config_key` varchar(64) NOT NULL default '',
  `plugin_config_value` varchar(255) NOT NULL default '',
  `plugin_id` int(11) NOT NULL default '0',
  `plugin_sort_order` int(5) default NULL,
  PRIMARY KEY  (`plugin_config_id`)
)"*/
	);	// Array associativo table_name => sql_code per la creazione della table

	
	function pws_engine()
	{
		// Controlla che siano impostati i valori del modulo nel database, altrimenti li crea al volo
		//$this->remove();exit;
		//	error_reporting (E_ALL ^ E_NOTICE);
		//$this->installPlugin('pws_products_selector','application');
		//exit("test");
		parent::__constructor();
		//$this->removePlugin($this->getPlugin('pws_prices_specials','prices'));
		//$this->installPlugin('pws_prices_specials','prices');
		//die("ok");
	}
	function __constructor(){
		$this->pws_engine();
	}
	function __destructor(){
	}

////////////////////////////////////////////////////////////////////////////////////
// Creazione, inizializzazione, aggiornamento delle tabelle estensione dei prodotti e delle categorie
	function install() {
		if (!parent::install()){
			$this->reportError(ERROR_PWS_DATABASE_TABLE_CREATION);
			return false;
		}
		// Installazione dei moduli di default
		if (!is_dir(DIR_FS_PWS_PLUGINS_APPLICATION)){
			$this->reportWarning(sprintf(WARNING_PWS_INSTALL_DIR_NOT_FOUND,DIR_FS_PWS_PLUGINS_APPLICATION),false);
		}else{
			
		}
		//$dir=opendir(DIR_FS_PWS_PLUGINS_APPLICATION);
		// Installazione Plugin Fondamentali
		if (file_exists(DIR_FS_PWS_PLUGINS_APPLICATION.'pws_prices.php'))
			$this->installPlugin('pws_prices','application');
		else
			$this->reportError(sprintf(ERROR_PWS_MISSING_REQUIRED_PLUGIN,'pws_prices'));
		if (file_exists(DIR_FS_PWS_PLUGINS_PRICES.'pws_prices_specials.php'))
			$this->installPlugin('pws_prices_specials','prices');
		else
			$this->reportError(sprintf(ERROR_PWS_MISSING_REQUIRED_PLUGIN,'pws_prices_specials'));
		if (file_exists(DIR_FS_PWS_PLUGINS_APPLICATION.'pws_html.php'))
			$this->installPlugin('pws_html','application');
		else
			$this->reportError(sprintf(ERROR_PWS_MISSING_REQUIRED_PLUGIN,'pws_html'));

		// Installazione plugin opzionali
		if (file_exists(DIR_FS_PWS_PLUGINS_APPLICATION.'pws_products_selector.php'))
			$this->installPlugin('pws_products_selector','application');
		if (file_exists(DIR_FS_PWS_PLUGINS_APPLICATION.'pws_products_images.php'))
			$this->installPlugin('pws_products_images','application');
		if (file_exists(DIR_FS_CATALOG_MODULES.FILENAME_SHOPWINDOW_FLASH)
			|| file_exists(DIR_FS_CATALOG_MODULES.FILENAME_SHOPWINDOW))
			$this->installModule('shopwindow');
			
		$this->reportSuccess(SUCCESS_PWS_INSTALL,false);
		return true;
	}
	// @function getApplicationPluginsSetupPageUrl
	// @desc	Restituisce il codice html per visualizzare il link alla pagina di installazione
	//			dei plugin application extra, eventualmente presenti
	// @param	mixed	Deve prendere un parametro per essere compatibile con configuration.php, ma non serve
	function getApplicationPluginsSetupPageUrl($param){
		$url='<a href="'.tep_href_link(FILENAME_PWS_APPLICATION_SETUP).'" style="text-style=underlined;color:blue">'.STRING_PWS_UPDATE.'</a>';
		return $url;
	}
	function installApplicationPlugins($plugins){
		$installed_plugins=array();
		reset($plugins);
		foreach($plugins as $plugin_code=>$status){
			if (!$this->isInstalledPlugin($plugin_code,'application')){
				$success=$this->installPlugin($plugin_code,'application');
			}else
				$success=false;
			$installed_plugins[$plugin_code]=$success;
		}
		return $installed_plugins;
	}
	function getNewApplicationPlugins(){
		$new_plugins=array();
		$dir=opendir(DIR_FS_PWS_PLUGINS_APPLICATION);
		while (($file = readdir($dir)) !== false) {
			if ($file!='.' && $file!='..' && is_file(DIR_FS_PWS_PLUGINS_APPLICATION.$file)
				&& !$this->isInstalledPlugin(basename($file,'.php'),'application')){
				$new_plugins[basename($file,'.php')]=false;
			}
		}
		closedir($dir);
		return $new_plugins;
	}
	function remove()
	{
		if (parent::remove()){
			$this->reportSuccess(SUCCESS_PWS_UNINSTALL,false);
			return true;
		}else{
			return false;
		}
	}

	// Aggiorna il database da versioni precedenti a quella attuale
	function update($oldversion)
	{
		ini_set('max_execution_time',36000);
		$redirect=false;
		switch ($oldversion)
		{
			case $this->version_const:
				return true;
				break;
			case '0.1':
				if (!$this->fieldExists('plugin_date_added',TABLE_PWS_PLUGINS))
					tep_db_query("alter table ".TABLE_PWS_PLUGINS." add column `plugin_date_added` datetime default NULL after plugin_sort_order");
				if (!$this->fieldExists('plugin_date_updated',TABLE_PWS_PLUGINS))
					tep_db_query("alter table ".TABLE_PWS_PLUGINS." add column `plugin_date_updated` datetime default NULL after plugin_date_added");
				$redirect=true;
			case '0.2':
				if (file_exists(DIR_FS_PWS_PLUGINS_APPLICATION.'pws_html.php'))
					$this->installPlugin('pws_html','application');
				else
					$this->reportError(sprintf(ERROR_PWS_MISSING_REQUIRED_PLUGIN,'pws_html'));
			case '0.3':
				if (file_exists(DIR_FS_PWS_PLUGINS_APPLICATION.'pws_products_selector.php'))
					$this->installPlugin('pws_products_selector','application');
				if (file_exists(DIR_FS_PWS_PLUGINS_APPLICATION.'pws_products_images.php'))
					$this->installPlugin('pws_products_images','application');
			case '0.32':
				if (!$this->configurationKeyExists('MODULE_PWS_UPDATE'))
					tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, date_added) values ('".STRING_PWS_UPDATE."', 'MODULE_PWS_UPDATE', '', '".STRING_PWS_UPDATE_DESC."', '".$this->configuration_group_id."', '3','pws_engine->getApplicationPluginsSetupPageUrl', now())");
			case '0.33':
				$query=tep_db_query("select * from ".TABLE_CONFIGURATION." where configuration_key='MODULE_PWS_UPDATE' and use_function<>'pws_engine->getApplicationPluginsSetupPageUrl'");
				if (tep_db_num_rows($query)>0){
					tep_db_query("update ".TABLE_CONFIGURATION." set use_function='pws_engine->getApplicationPluginsSetupPageUrl',set_function='' where configuration_key='MODULE_PWS_UPDATE'");
				}
			case '0.34':
				tep_db_query("update ".TABLE_CONFIGURATION." set set_function='dont_set(' where configuration_key='".$this->versionKeyName."'");
			case '0.35':
				if (!$this->configurationKeyExists('MODULE_PWS_DEBUG')){
					if (!defined('STRING_PWS_DEBUG'))
						define('STRING_PWS_DEBUG','Stato del debugging');
					if (!defined('STRING_PWS_DEBUG_DESC'))
						define('STRING_PWS_DEBUG_DESC','Abilitare la comparsa di messaggi di debug, utili allo sviluppo del sistema');
					tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('".STRING_PWS_DEBUG."', 'MODULE_PWS_DEBUG', '', '".STRING_PWS_DEBUG_DESC."', 'false', '4','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
				}
			case '0.36':
			case '0.37':
				if ($this->fieldLength('products_model',TABLE_PRODUCTS)!=255){
					tep_db_query("alter table ".TABLE_PRODUCTS." modify column products_model varchar(255) default NULL");
				}
			case '0.38':
			case '0.39':
			case '0.40':
				// nuova versione di easy populate
				require_once DIR_FS_CATALOG.'admin/includes/languages/italian/easypopulate.php';
				$ep_configKeys=array(
					'EP_CURRENT_VERSION'=>array(
							'configuration_title'=>EP_CURRENT_VERSION_CONFIG_TITLE
							,'configuration_value'=>'2.76g-MS2'
							,'configuration_description'=>EP_CURRENT_VERSION_CONFIG_DESC
							,'set_function'=>'dont_set('
						)
					,'EP_SHOW_EP_SETTINGS'=>array(
							'configuration_title'=>EP_SHOW_EP_SETTINGS_CONFIG_TITLE
							,'configuration_value'=>1
							,'configuration_description'=>EP_SHOW_EP_SETTINGS_CONFIG_DESC
							,'set_function'=>'tep_cfg_select_option(array(1,0), '
						)
					,'EP_SEPARATOR'=>array(
							'configuration_title'=>EP_SEPARATOR_CONFIG_TITLE
							,'configuration_value'=>';'
							,'configuration_description'=>EP_SEPARATOR_CONFIG_DESC
							,'set_function'=>'tep_cfg_select_option(array(\';\',\',\',\'~\',\'*\',\'|\',\'tab\'), '
						)
					,'EP_TEMP_DIRECTORY'=>array(
							'configuration_title'=>EP_TEMP_DIRECTORY_CONFIG_TITLE
							,'configuration_value'=>DIR_FS_CATALOG . 'temp/'
							,'configuration_description'=>EP_TEMP_DIRECTORY_CONFIG_DESC
						)
					,'EP_SPLIT_MAX_RECORDS'=>array(
							'configuration_title'=>EP_SPLIT_MAX_RECORDS_CONFIG_TITLE
							,'configuration_value'=>300
							,'configuration_description'=>EP_SPLIT_MAX_RECORDS_CONFIG_DESC
						)
					,'EP_DEFAULT_IMAGE_MANUFACTURER'=>array(
							'configuration_title'=>EP_DEFAULT_IMAGE_MANUFACTURER_CONFIG_TITLE
							,'configuration_value'=>''
							,'configuration_description'=>EP_DEFAULT_IMAGE_MANUFACTURER_CONFIG_DESC
						)
					,'EP_DEFAULT_IMAGE_PRODUCT'=>array(
							'configuration_title'=>EP_DEFAULT_IMAGE_PRODUCT_CONFIG_TITLE
							,'configuration_value'=>''
							,'configuration_description'=>EP_DEFAULT_IMAGE_PRODUCT_CONFIG_DESC
						)
					,'EP_DEFAULT_IMAGE_CATEGORY'=>array(
							'configuration_title'=>EP_DEFAULT_IMAGE_CATEGORY_CONFIG_TITLE
							,'configuration_value'=>''
							,'configuration_description'=>EP_DEFAULT_IMAGE_CATEGORY_CONFIG_DESC
						)
					,'EP_INACTIVATE_ZERO_QUANTITIES'=>array(
							'configuration_title'=>EP_INACTIVATE_ZERO_QUANTITIES_CONFIG_TITLE
							,'configuration_value'=>0
							,'configuration_description'=>EP_INACTIVATE_ZERO_QUANTITIES_CONFIG_DESC
							,'set_function'=>'tep_cfg_select_option(array(1,0), '
						)
					,'EP_PRICE_WITH_TAX'=>array(
							'configuration_title'=>EP_PRICE_WITH_TAX_CONFIG_TITLE
							,'configuration_value'=>0
							,'configuration_description'=>EP_PRICE_WITH_TAX_CONFIG_DESC
							,'set_function'=>'tep_cfg_select_option(array(1,0), '
						)
					,'EP_PRECISION'=>array(
							'configuration_title'=>EP_PRECISION_CONFIG_TITLE
							,'configuration_value'=>2
							,'configuration_description'=>EP_PRECISION_CONFIG_DESC
						)
					,'EP_MAX_CATEGORIES'=>array(
							'configuration_title'=>EP_MAX_CATEGORIES_CONFIG_TITLE
							,'configuration_value'=>7
							,'configuration_description'=>EP_MAX_CATEGORIES_CONFIG_DESC
						)
					,'EP_TEXT_ACTIVE'=>array(
							'configuration_title'=>EP_TEXT_ACTIVE_CONFIG_TITLE
							,'configuration_value'=>'Active'
							,'configuration_description'=>EP_TEXT_ACTIVE_CONFIG_DESC
						)
					,'EP_TEXT_INACTIVE'=>array(
							'configuration_title'=>EP_TEXT_INACTIVE_CONFIG_TITLE
							,'configuration_value'=>'Inactive'
							,'configuration_description'=>EP_TEXT_INACTIVE_CONFIG_DESC
						)
					,'EP_DELETE_IT'=>array(
							'configuration_title'=>EP_DELETE_IT_CONFIG_TITLE
							,'configuration_value'=>'Delete'
							,'configuration_description'=>EP_DELETE_IT_CONFIG_DESC
						)
					,'EP_FROOGLE_CURRENCY'=>array(
							'configuration_title'=>EP_FROOGLE_CURRENCY_CONFIG_TITLE
							,'configuration_value'=>'EUR'
							,'configuration_description'=>EP_FROOGLE_CURRENCY_CONFIG_DESC
							,'set_function'=>'tep_cfg_select_option(array_keys($currencies->currencies), '//$currencies_value
						)
				);
				// Elimina le chiavi di configurazione per EP della versione precedente
				$checkQuery=tep_db_query("select configuration_group_id from ".TABLE_CONFIGURATION_GROUP." where configuration_group_title='Easy Populate'");
				if (tep_db_num_rows($checkQuery)>0){
					$ep_conf_group_id=tep_db_fetch_array($checkQuery);
					$ep_conf_group_id=$ep_conf_group_id['configuration_group_id'];
					tep_db_query("delete from ".TABLE_CONFIGURATION." where configuration_group_id='$ep_conf_group_id'");
				}else{
					tep_db_query("delete from ".TABLE_CONFIGURATION." where configuration_key like 'EP_%'");
					$ep_conf_group_id=170;
					tep_db_query("insert into ".TABLE_CONFIGURATION_GROUP." set configuration_group_id='$ep_conf_group_id',configuration_group_title='Easy Populate',configuration_group_description='Easy Populate',sort_order=17,visible=1");
				}
				foreach($ep_configKeys as $key=>$keydef)	{
					if (!$this->configurationKeyExists($key)){
						$keydef['configuration_group_id']=$ep_conf_group_id;
						if (!isset($keydef['sort_order'])){
							$query=tep_db_query("select (max(sort_order)+1) as sort_order from ".TABLE_CONFIGURATION." where configuration_group_id=".$keydef['configuration_group_id']);
							$sort_order=tep_db_fetch_array($query);
							$sort_order=$sort_order['sort_order'];
							$keydef['sort_order']=$sort_order;
						}
						if (!isset($keydef['date_added']))
							$keydef['date_added']='now()';
						$keydef['configuration_key']=$key;
						tep_db_perform(TABLE_CONFIGURATION,$keydef);
					}
				}
			case '0.41':
				// Aggiunta degli indici per ottimizzazione queries mysql
				if (!$this->fieldExists('public_flag',TABLE_ORDERS_STATUS)){
					tep_db_query("alter table ".TABLE_ORDERS_STATUS." add public_flag int DEFAULT '1'");
				}
				if (!$this->fieldExists('downloads_flag',TABLE_ORDERS_STATUS)){
					tep_db_query("alter table ".TABLE_ORDERS_STATUS." add downloads_flag int DEFAULT '0'");
				}
				if ($this->fieldLength('payment_method',TABLE_ORDERS)!=255){
					tep_db_query("alter table ".TABLE_ORDERS." modify payment_method varchar(255) NOT NULL");
				}
				tep_db_query("alter table ".TABLE_WHOS_ONLINE." modify last_page_url text NOT NULL");
				///////////////////////////////
				// Aggiunta degli indici
				if (!$this->indexExists('banners_group',TABLE_BANNERS))
					tep_db_query("alter table ".TABLE_BANNERS." add index idx_banners_group (banners_group)");
				if (!$this->indexExists('banners_id',TABLE_BANNERS_HISTORY))
					tep_db_query("alter table ".TABLE_BANNERS_HISTORY." add index idx_banners_history_banners_id (banners_id)");
				if (!$this->indexExists('code',TABLE_CURRENCIES))
					tep_db_query("alter table ".TABLE_CURRENCIES." add index idx_currencies_code (code)");
				if (!$this->indexExists('customers_email_address',TABLE_CUSTOMERS))
					tep_db_query("alter table ".TABLE_CUSTOMERS." add index idx_customers_email_address (customers_email_address)");
				if (!$this->indexExists('customers_id',TABLE_CUSTOMERS_BASKET))
					tep_db_query("alter table ".TABLE_CUSTOMERS_BASKET." add index idx_customers_basket_customers_id (customers_id)");
				if (!$this->indexExists('customers_id',TABLE_CUSTOMERS_BASKET_ATTRIBUTES))
					tep_db_query("alter table ".TABLE_CUSTOMERS_BASKET_ATTRIBUTES." add index idx_customers_basket_att_customers_id (customers_id)");
				if (!$this->indexExists('customers_id',TABLE_ORDERS))
					tep_db_query("alter table ".TABLE_ORDERS." add index idx_orders_customers_id (customers_id)");
				if (!$this->indexExists('orders_id',TABLE_ORDERS_PRODUCTS))
					tep_db_query("alter table ".TABLE_ORDERS_PRODUCTS." add index idx_orders_products_orders_id (orders_id)");
				if (!$this->indexExists('products_id',TABLE_ORDERS_PRODUCTS))
					tep_db_query("alter table ".TABLE_ORDERS_PRODUCTS." add index idx_orders_products_products_id (products_id)");
				if (!$this->indexExists('orders_id',TABLE_ORDERS_STATUS_HISTORY))
					tep_db_query("alter table ".TABLE_ORDERS_STATUS_HISTORY." add index idx_orders_status_history_orders_id (orders_id)");
				if (!$this->indexExists('orders_id',TABLE_ORDERS_PRODUCTS_ATTRIBUTES))
					tep_db_query("alter table ".TABLE_ORDERS_PRODUCTS_ATTRIBUTES." add index idx_orders_products_att_orders_id (orders_id)");
				if (!$this->indexExists('orders_id',TABLE_ORDERS_PRODUCTS_DOWNLOAD))
					tep_db_query("alter table ".TABLE_ORDERS_PRODUCTS_DOWNLOAD." add index idx_orders_products_download_orders_id (orders_id)");
				if (!$this->indexExists('products_model',TABLE_PRODUCTS))
					tep_db_query("alter table ".TABLE_PRODUCTS." add index idx_products_model (products_model)");
				if (!$this->indexExists('products_id',TABLE_PRODUCTS_ATTRIBUTES))
					tep_db_query("alter table ".TABLE_PRODUCTS_ATTRIBUTES." add index idx_products_attributes_products_id (products_id)");
				if (!$this->indexExists('products_id',TABLE_REVIEWS))
					tep_db_query("alter table ".TABLE_REVIEWS." add index idx_reviews_products_id (products_id)");
				if (!$this->indexExists('customers_id',TABLE_REVIEWS))
					tep_db_query("alter table ".TABLE_REVIEWS." add index idx_reviews_customers_id (customers_id)");
				if (!$this->indexExists('products_id',TABLE_SPECIALS))
					tep_db_query("alter table ".TABLE_SPECIALS." add index idx_specials_products_id (products_id)");
				if (!$this->indexExists('zone_country_id',TABLE_ZONES))
					tep_db_query("alter table ".TABLE_ZONES." add index idx_zones_to_geo_zones_country_id (zone_country_id)");
			case '0.42':
				// Sistemazione del path per il log dei tempi di caricamento delle pagine
				tep_db_query("update ".TABLE_CONFIGURATION." set configuration_value='".DIR_FS_CACHE."page_parse_time.log' where configuration_key='STORE_PAGE_PARSE_TIME_LOG'");
			case '0.43':
				// Rimozione di doppioni nella table pws_plugins
				$deleteIds=array();
				$plugin_check=tep_db_query("select * from ".TABLE_PWS_PLUGINS." where 1 order by plugin_id");
				while ($plugin=tep_db_fetch_array($plugin_check)){
					if (tep_db_num_rows(tep_db_query("select * from ".TABLE_PWS_PLUGINS." where plugin_code='".$plugin['plugin_code']."'"))>1){
						$deleteQuery=tep_db_query("select plugin_id from ".TABLE_PWS_PLUGINS." where plugin_code = '".$plugin['plugin_code']."' limit 1,1000");
						while($delete_id=tep_db_fetch_array($deleteQuery)){
							$deleteIds[]=$delete_id['plugin_id'];
						}
					}
				}
				while(sizeof($deleteIds)){
					tep_db_query("delete from ".TABLE_PWS_PLUGINS." where plugin_id='".array_pop($deleteIds)."'");
				}
			case '0.44':
				tep_db_query("delete from ".TABLE_PWS_PLUGINS." where plugin_code='pws_languages' or plugin_code='pws_export_products'");
			case '0.45':
				$this->installConfigurationKeys($this->configKeys,$this->configuration_group_id);
				tep_db_query("delete from ".TABLE_CONFIGURATION." where configuration_key='MODULE_PWS_UPDATE'");
			case '0.46':
				if (!checkColumn('entry_type',TABLE_ADDRESS_BOOK)){
					tep_db_query("alter table ".TABLE_ADDRESS_BOOK." add column entry_type enum('company','private') default null after customers_id");
					tep_db_query("update ".TABLE_ADDRESS_BOOK." set entry_type=if(entry_company_cf<>'','company',if(entry_piva<>'','company','private')),entry_company_cf=if(entry_company_cf<>'',entry_company_cf,if(entry_piva<>'',entry_piva,entry_cf))");
				}
				else tep_db_query("update ".TABLE_ADDRESS_BOOK." set entry_type=if(entry_type<>'',entry_type,if(entry_company_cf<>'','company',if(entry_piva<>'','company','private'))),entry_company_cf=if(entry_company_cf<>'',entry_company_cf,if(entry_piva<>'',entry_piva,entry_cf))");
				tep_db_query("alter table ".TABLE_ORDERS." add column billing_type enum('company','private') default null after billing_company");
				break;
			default:
				$this->remove();
				$this->check();
				break;
		}
		tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value='".$this->version_const."' where configuration_key='".$this->versionKeyName."'");
		$this->version=$this->version_const;
		$this->reportSuccess(sprintf(SUCCESS_PWS_ENGINE_UPDATE,$oldversion,$this->version_const),!$redirect);
		if ($redirect)
			$this->redirectToAdminConfigPage();
		
		return true;
	}
// Bug Fixings
// Creazione, inizializzazione, aggiornamento delle tabelle estensione dei prodotti e delle categorie
////////////////////////////////////////////////////////////////////////////////////
	function init(){
		$this->unloadPlugins();
		$this->loadPlugins();
//		$this->_pws_prices=new pws_prices(&$this);
		$this->_pws_prices=&$this->getPlugin('pws_prices','application');
		$this->_pws_clothing=&$this->getPlugin('pws_clothing','application');
	}
	function reportPluginUpdate($plugin_name,$oldversion,$newversion){
		$this->reportSuccess(sprintf(SUCCESS_PWS_PLUGIN_UPDATE,$plugin_name,$oldversion,$newversion));
	}
	
	function loadPlugins(){
		global	$pws_prices;
		$application_plugins=array();
		$pluginsQuery=tep_db_query("select * from ".TABLE_PWS_PLUGINS." where plugin_status='1' order by plugin_type,plugin_sort_order");
		while ($pluginData=tep_db_fetch_array($pluginsQuery)){
			if (file_exists(DIR_FS_PWS_LANGUAGE.'plugins/'.$pluginData['plugin_type'].'/language_'.$pluginData['plugin_code'].'.php'))
				require_once DIR_FS_PWS_LANGUAGE.'plugins/'.$pluginData['plugin_type'].'/language_'.$pluginData['plugin_code'].'.php';
			else
				exit("file language non trovato:".DIR_FS_PWS_LANGUAGE.'plugins/'.$pluginData['plugin_type'].'/language_'.$pluginData['plugin_code'].'.php');
			require_once DIR_FS_PWS_PLUGINS.$pluginData['plugin_type'].'/'.$pluginData['plugin_code'].'.php';
			$plugin=new $pluginData['plugin_code'](&$this);
			$plugin->plugin_id=$pluginData['plugin_id'];
			$plugin->plugin_sort_order=$pluginData['plugin_sort_order'];
			$plugin->plugin_version=$pluginData['plugin_version'];
			$plugin->plugin_status=$pluginData['plugin_status'];
			$plugin->configuration_group_id=$this->configuration_group_id+$plugin->plugin_id;
			reset($plugin->plugin_configKeys);
			foreach($plugin->plugin_configKeys as $key=>$keydef){
				if (defined($key))
					$plugin->plugin_config[$key]=constant($key);
				else
					$plugin->plugin_config[$key]=$keydef['configuration_value'];
//				$keyquery=tep_db_query("select configuration_value from ".TABLE_CONFIGURATION." where configuration_key='".$key."'");
//				if ($keyvalue=tep_db_fetch_array($keyquery))
//					$plugin->plugin_config[$key]=$keyvalue['configuration_value'];
//				else if (isset($keydef['configuration_value']))	// Imposta il valore di default, se non trova il valore nel database
//					$plugin->plugin_config[$key]=$keydef['configuration_value'];
			}
			$this->_pluginsById[$pluginData['plugin_id']]=$plugin;
			$this->_pluginsByType[$pluginData['plugin_type']][]=&$this->_pluginsById[$pluginData['plugin_id']];
			$this->loadPluginHooks($plugin);
			if ($plugin->needsUpdate()){
				$oldversion=$plugin->plugin_version;
				$newversion=$plugin->plugin_version_const;
				$plugin->update($pluginData['plugin_version']);
				$plugin->plugin_version=$plugin->plugin_version_const;
				// Controlla il numero di entries per questo plugin
				// se esiste più di una entry, elimina le successive alla prima
				if (tep_db_num_rows(tep_db_query("select * from ".TABLE_PWS_PLUGINS." where plugin_code='".$plugin->plugin_code."'"))>1){
					$deleteQuery=tep_db_query("select plugin_id from ".TABLE_PWS_PLUGINS." where plugin_code = '".$plugin->plugin_code."' limit 1,1000");
					$deleteIds=array();
					while($delete_id=tep_db_fetch_array($deleteQuery)){
						$deleteIds[]=$delete_id['plugin_id'];
					}
					while(sizeof($deleteIds)){
						tep_db_query("delete from ".TABLE_PWS_PLUGINS." where plugin_id='".array_pop($deleteIds)."'");
					}
				}
				tep_db_query("update ".TABLE_PWS_PLUGINS." set plugin_version='".$plugin->plugin_version_const."', plugin_date_updated=now() where plugin_code='".$plugin->plugin_code."'");
				$this->installConfigurationKeys($plugin->plugin_configKeys,'6');
				$this->reportPluginUpdate($plugin->plugin_name,$oldversion,$newversion);
			}
		}
		$this->setPluginsDependencies();
		$pws_prices=&$this->getPlugin('pws_prices','application');
		$pws_prices->setPluginsPrices($this->getPluginsByType('prices'));
	}
	function loadPluginHooks(&$plugin){
		reset($plugin->plugin_hooks);
		foreach($plugin->plugin_hooks as $function_code=>$method){
			$this->plugin_hooks[$function_code][$plugin->plugin_id]=$method;
		}
	}
	function triggerHook($hook_code){
		$output='';
		if (isset($this->plugin_hooks[$hook_code])){
			$hooks=&$this->plugin_hooks[$hook_code];
			foreach($hooks as $plugin_id=>$method){
				$plugin=&$this->getPluginById($plugin_id);
				if (!is_null($plugin)){
					//echo $plugin->plugin_code."->$method()<br/>";
					$output.=$plugin->$method();
				}
			}
		}
		return $output;
	}
	function unloadPlugins(){
		$this->_pluginsByType=array();
		$this->_pluginsById=array();
	}
	
	//	@function loadPlugin
	//	@desc	Carica un plugin singolo (deve essere già installato)
	function &loadPlugin($plugin_code){
		$pluginsQuery=tep_db_query("select * from ".TABLE_PWS_PLUGINS." where plugin_status='1' where plugin_code='$plugin_code'");
		if ($pluginData=tep_db_fetch_array($pluginsQuery)){
			if (file_exists(DIR_FS_PWS_LANGUAGE.'plugins/'.$pluginData['plugin_type'].'/language_'.$pluginData['plugin_code'].'.php'))
				require_once DIR_FS_PWS_LANGUAGE.'plugins/'.$pluginData['plugin_type'].'/language_'.$pluginData['plugin_code'].'.php';
			else
				exit("file language non trovato:".DIR_FS_PWS_LANGUAGE.'plugins/'.$pluginData['plugin_type'].'/language_'.$pluginData['plugin_code'].'.php');
			require_once DIR_FS_PWS_PLUGINS.$pluginData['plugin_type'].'/'.$pluginData['plugin_code'].'.php';
			$plugin=new $pluginData['plugin_code'](&$this);
			$plugin->plugin_id=$pluginData['plugin_id'];
			$plugin->plugin_sort_order=$pluginData['plugin_sort_order'];
			$plugin->plugin_version=$pluginData['plugin_version'];
			$plugin->plugin_status=$pluginData['plugin_status'];
			$plugin->configuration_group_id=$this->configuration_group_id+$plugin->plugin_id;
			$plugin->_pws_engine=&$this;
			reset($plugin->plugin_configKeys);
			foreach($plugin->plugin_configKeys as $key=>$keydef){
				$keyquery=tep_db_query("select configuration_value from ".TABLE_CONFIGURATION." where configuration_key='".$key."'");
				if ($keyvalue=tep_db_fetch_array($keyquery))
					$plugin->plugin_config[$key]=$keyvalue['configuration_value'];
				else if (isset($keydef['configuration_value']))	// Imposta il valore di default, se non trova il valore nel database
					$plugin->plugin_config[$key]=$keydef['configuration_value'];
			}
			$this->_pluginsById[$pluginData['plugin_id']]=$plugin;
			$this->_pluginsByType[$pluginData['plugin_type']][]=&$this->_pluginsById[$pluginData['plugin_id']];
		}
		else
			return $this->nullByRef;
	}
	//	@function 	setPluginsDependencies
	//	@desc		Imposta le dipendenze fra i plugins
	function setPluginsDependencies(){
		reset($this->_pluginsByType);
		foreach($this->_pluginsByType as $pluginType=>$plugins)	{
			for ($i=0;$i<sizeof($plugins);$i++)	{
				$plugin=&$plugins[$i];
				for($pn=0;$pn<sizeof($plugin->plugin_needs);$pn++){
					$plugin_code=$plugin->plugin_needs[$pn];
					if ($plugin_code=='pws_engine')
						$plugin->plugin_using['pws_engine']=&$this;
					else{
						$plugin->plugin_using[$plugin_code]=&$this->getPlugin($plugin_code);
						if ($plugin->plugin_using[$plugin_code]!=NULL){
							$used_plugin=&$plugin->plugin_using[$plugin_code];
							$used_plugin->plugin_usedby[$plugin->plugin_code]=&$plugin;
						}
					}
				}
			}
		}
	}
	function &getPluginsByType($pluginType){
		return $this->_pluginsByType[$pluginType];
	}
	
	function isInstalledPlugin($pluginCode,$pluginType=NULL){
		$checkQuery="select * from ".TABLE_PWS_PLUGINS." where plugin_code='".$pluginCode."'";
		if (!is_null($pluginType))
			$checkQuery.=" and plugin_type='".$pluginType."'";
		$checkQuery=tep_db_query($checkQuery);
		return tep_db_num_rows($checkQuery)>0;
		
		if (!is_null($pluginType)){
			$found=false;
			$plugins=&$this->_pluginsByType[$pluginType];
			for($i=0;!$found && $i<sizeof($plugins);$i++)
				$found=$pluginCode==$plugins[$i]->plugin_code;
			return $found;
		}
		else
		{
			$found=false;
			reset($this->_pluginsByType);
			foreach($this->_pluginsByType as $pluginType=>$plugins){
				if ($found)
					break;
				for ($i=0;!$found && $i<sizeof($plugins);$i++)
					$found=$pluginCode==$plugins[$i]->plugin_code;
			}
			return $found;
		}
	}
	function &getPluginById($plugin_id){
		if (isset($this->_pluginsById[$plugin_id]))
			return $this->_pluginsById[$plugin_id];
		else
			return $this->nullByRef;
	}
	function &getPlugin($pluginCode,$pluginType=NULL){
		if (!is_null($pluginType)){
			$plugins=&$this->_pluginsByType[$pluginType];
			for($i=0;$i<sizeof($plugins);$i++)
				if ($pluginCode==$plugins[$i]->plugin_code)
					return $plugins[$i];
			return $this->nullByRef;
		}
		else
		{
			$found=false;
			reset($this->_pluginsByType);
			foreach($this->_pluginsByType as $pluginType=>$plugins)
				for ($i=0;!$found && $i<sizeof($plugins);$i++)
					if ($pluginCode==$plugins[$i]->plugin_code)
						return $plugins[$i];
			return $this->nullByRef;
		}
	}
	
	// controlla se ci sono doppi nella tabella plugin, bella cazzata!
	function checkPlugin(&$plugin){
		$plugincode=$plugin->plugin_code;
		$checkQuery=tep_db_query("select * from ".TABLE_PWS_PLUGINS." where plugin_code='$plugincode'");
		return tep_db_num_rows($checkQuery);
//		if (tep_db_fetch_array($checkQuery))	{
//			return $checkQuery['plugin_version'];
//		}
//		else
//			return false;
	}
	//	@function installPlugin
	//	@desc		Installa un plugin, dato il codice del plugin ed il tipo
	//	@param		string	$plugin_code		Codice del plugin
	//	@param		string 	$plugin_type		Tipo di plugin
	//	@return		bool						Flag di successo/fallimento della funzione
	function installPlugin($plugin_code,$plugin_type){
		ini_set('max_execution_time',36000);
		if ($this->isInstalledPlugin($plugin_code, $plugin_type))
			return false;
		$filename=$plugin_code.'.php';
		if (file_exists(DIR_FS_PWS_LANGUAGE . 'plugins/' . $plugin_type . '/language_' . $filename))
      		require_once(DIR_FS_PWS_LANGUAGE . 'plugins/' . $plugin_type . '/language_' . $filename);
	    require_once(DIR_FS_PWS_PLUGINS. $plugin_type.'/'.$filename);
	    $plugin=new $plugin_code(&$this);

	    // Controlla la compatibilit� del plugin da installare
		if (sizeof($plugin->plugin_conflicts)){
			for ($i=0,$n=sizeof($plugin->plugin_conflicts);$i<$n;$i++){
				if ($this->isInstalledPlugin($plugin->plugin_conflicts[$i])){
					$conflict_plugin=&$this->getPlugin($plugin->plugin_conflicts[$i]);
					$message=sprintf(WARNING_PWS_PLUGIN_CONFLICT,$plugin->plugin_name,$conflict_plugin->plugin_name);
					$this->reportWarning($message);
					return false;
				}
			}
		}
		if (-1==($plugin_sort_order=$plugin->plugin_sort_order)){
			$sort_order_query=tep_db_query("select (max(plugin_sort_order)+1) as plugin_sort_order from ".TABLE_PWS_PLUGINS." where plugin_type='".$plugin->plugin_type."'");
			if (tep_db_num_rows($sort_order_query)>0){
				$sort_order=tep_db_fetch_array($sort_order_query);
				$plugin_sort_order=$plugin_sort_order['plugin_sort_order'];
			}else{
				$sort_order=1;
			}
		}
		if ($plugin_sort_order==0)
			$plugin_sort_order='0';
		
		tep_db_query("insert into ".TABLE_PWS_PLUGINS." set plugin_code='".$plugin->plugin_code."', plugin_version='".$plugin->plugin_version_const."', plugin_type='".$plugin->plugin_type."', plugin_sort_order='$plugin_sort_order', plugin_date_added=now()");
		$plugin->plugin_id=tep_db_insert_id();
		reset($plugin->plugin_tables);
		foreach ($plugin->plugin_tables as $tablename=>$tabledef)	{
			if (!$this->tableExists($tablename)){
				if (!tep_db_query("CREATE TABLE `$tablename` $tabledef"))	{
					$this->reportError(ERROR_PWS_DATABASE_TABLE_CREATION);
					return false;
				}
			}
		}
		$this->installConfigurationKeys($plugin->plugin_configKeys,'6');
		if ($plugin->plugin_sql_install!=''){
			if (!tep_db_query($plugin->plugin_sql_install))	{
				$this->reportError(ERROR_PWS_PLUGIN_SQL_INSTALL);
				return false;
			}
		}
		$this->unloadPlugins();	// Scarica i plugins
		$this->loadPlugins();	// Ricarica i plugins
		$plugin=&$this->getPlugin($plugin_code,$plugin_type);
		$plugin->install();
		return true;
	}
	// @function installConfigurationKeys
	// @desc	Installa una serie di chiavi di configurazione
	// @param	array	$config_keys	Formato: array(configuration_key=>array(field1=>value1,...,fieldn=>valuen))
	function installConfigurationKeys(&$config_keys,$configuration_group_id='6'){
		if (sizeof($config_keys)){
			reset($config_keys);
			$sort_order=1;
			foreach($config_keys as $key=>$keydef)	{
				if (!isset($keydef['configuration_group_id']))
					$keydef['configuration_group_id']=$configuration_group_id;
				if (!isset($keydef['sort_order'])){
					//$query=tep_db_query("select (max(sort_order)+1) as sort_order from ".TABLE_CONFIGURATION." where configuration_group_id=".$keydef['configuration_group_id']);
					//$sort_order=tep_db_fetch_array($query);
					//$sort_order=$sort_order['sort_order'];
					$keydef['sort_order']=$sort_order;
				}
				$sort_order++;
				if (!$this->configurationKeyExists($key)){
					$keydef['configuration_key']=$key;
					if (!isset($keydef['date_added']))
						$keydef['date_added']='now()';
					tep_db_perform(TABLE_CONFIGURATION,$keydef);
				}else{
					if (!isset($keydef['last_modified']))
						$keydef['last_modified']='now()';
					unset($keydef['configuration_key']);
					tep_db_perform(TABLE_CONFIGURATION,$keydef,'update',"configuration_key='$key'");
				}
			}
		}
	}
	function removePlugin(&$plugin){
		if (!$plugin->plugin_removable){
			$this->reportError(ERROR_PWS_PLUGIN_UNREMOVABLE);
			return false;
		}
		if (!$plugin->remove()){
			$this->reportError(ERROR_PWS_PLUGIN_UNREMOVABLE);
			return false;
		}
		// eliminazione delle tabelle usate dal plugin
		if (false)	{
			reset($plugin->plugin_tables);
			foreach ($plugin->plugin_tables as $tablename=>$tabledef)	{
				if (!tep_db_query("drop table `$tablename`"))	{
					$this->reportError(ERROR_PWS_DATABASE_TABLE_DELETION);
					return false;
				}
			}
		}
		reset($plugin->plugin_configKeys);
		foreach($plugin->plugin_configKeys as $key=>$keydef)
			tep_db_query("delete from ".TABLE_CONFIGURATION." where configuration_key='".$key."'");
		if ($plugin->plugin_sql_remove!='')
			tep_db_query($plugin->plugin_sql_remove);
		tep_db_query("delete from ".TABLE_PWS_PLUGINS." where plugin_code='".$plugin->plugin_code."'");
		return true;
	}
	function sortPluginHigher(&$plugin){
		tep_db_query("update ".TABLE_PWS_PLUGINS." set plugin_sort_order=plugin_sort_order+1 where plugin_code='".$plugin->plugin_code."'");
	}
	function sortPluginLower(&$plugin){
		tep_db_query("update ".TABLE_PWS_PLUGINS." set plugin_sort_order=plugin_sort_order-1 where plugin_code='".$plugin->plugin_code."'");
	}
	function installModule($moduleCode){
		switch ($moduleCode){
			case 'shopwindow':
				tep_db_query("delete from ".TABLE_CONFIGURATION." where configuration_key like '%SHOPWINDOW%'");
				if (!$this->fieldExists('products_shopwindow',TABLE_PRODUCTS))
					tep_db_query("alter table ".TABLE_PRODUCTS." add column products_shopwindow char(1) not null default '0' after products_status");
				if (!$this->configurationKeyExists('SHOPWINDOW_ENABLED'))
					tep_db_query("INSERT INTO ".TABLE_CONFIGURATION." (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) VALUES ('Vetrina: Abilitazione', 'SHOPWINDOW_ENABLED', 'true', 'Abilita la visualizzazione della vetrina', 8, 50, now(), NULL, 'tep_cfg_select_option(array(\'true\', \'false\'), ')");
				if (!$this->configurationKeyExists('SHOPWINDOW_SKIN')){
					if (file_exists(DIR_FS_CATALOG_MODULES.FILENAME_SHOPWINDOW_FLASH)){
						$default_shopwindow_skin='FLASH';
						$set_function="tep_cfg_select_option(array(\'HTML\', \'FLASH\'), ";
					}else{
						$default_shopwindow_skin='HTML';
						$set_function="tep_cfg_select_option(array(\'HTML\', \'FLASH (non installata)\'), ";
					}
					tep_db_query("INSERT INTO ".TABLE_CONFIGURATION." (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) VALUES ('Vetrina: Tipo Vetrina', 'SHOPWINDOW_SKIN', '$default_shopwindow_skin', 'Selezionare il tipo di vetrina da visualizzare', 8, 51, now(), NULL, '$set_function')");
				}
				if (!$this->configurationKeyExists('SHOPWINDOW_NUM_COLUMNS'))
					tep_db_query("INSERT INTO ".TABLE_CONFIGURATION." (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) VALUES ('Vetrina: Numero Colonne', 'SHOPWINDOW_NUM_COLUMNS', '3', 'Numero di colonne che appaiono nella vetrina', 8, 52, now(), NULL, NULL)");
				if (!$this->configurationKeyExists('SHOPWINDOW_MAX_PRODUCTS'))
					tep_db_query("INSERT INTO ".TABLE_CONFIGURATION." (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) VALUES ('Vetrina: Max Numero Prodotti', 'SHOPWINDOW_MAX_PRODUCTS', '9', 'Numero massimo di prodotti che appaiono in vetrina', 8, 53, now(), NULL, NULL)");
				if (!$this->configurationKeyExists('SHOPWINDOW_PRODUCT_IMAGE_WIDTH'))
					tep_db_query("INSERT INTO ".TABLE_CONFIGURATION." (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) VALUES ('Vetrina: Max Larghezza Immagini Prodotto ', 'SHOPWINDOW_PRODUCT_IMAGE_WIDTH', '200', 'Larghezza massima delle immagini dei prodotti in vetrina', 8, 54, now(), NULL, NULL)");
				if (!$this->configurationKeyExists('SHOPWINDOW_PRODUCT_IMAGE_HEIGHT'))
					tep_db_query("INSERT INTO ".TABLE_CONFIGURATION." (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `date_added`, `use_function`, `set_function`) VALUES ('Vetrina: Max Altezza Immagini Prodotto ', 'SHOPWINDOW_PRODUCT_IMAGE_HEIGHT', '200', 'Altezza massima delle immagini dei prodotti in vetrina', 8, 55, now(), NULL, NULL)");
					
				// Vetrina -> Aggiornamento Veloce
				if (!$this->configurationKeyExists('DISPLAY_SHOPWINDOW'))
					tep_db_query("insert  into ".TABLE_CONFIGURATION." (`configuration_title`,`configuration_key`,`configuration_value`,`configuration_description`,`configuration_group_id`,`sort_order`,`last_modified`,`date_added`,`use_function`,`set_function`) values ('Modifica la presenza in vetrina del prodotto.','DISPLAY_SHOPWINDOW','true','Abilita/Disabilita la presenza in vetrina',300,4,NULL,now(),NULL,'tep_cfg_select_option(array(\'true\', \'false\'),')");
				break;
			default:
				return false;
		}
		return true;
	}
	// Helper Functions
	function formatDate($mysqldate,$dateformat){
		if (!strlen($mysqldate))
			return '';
		$day=substr($mysqldate,8,2);
		$month=substr($mysqldate,5,2);
		$year=substr($mysqldate,0,4);
		$daypos=strpos($dateformat,'dd');
		$monthpos=strpos($dateformat,'MM');
		$yearpos=strpos($dateformat,'yyyy');
		$datepos=array($daypos=>$day,$monthpos=>$month,$yearpos=>$year);
		ksort($datepos);
		reset($datepos);
		$date='';
		foreach($datepos as $pos=>$digit)
			$date.=$digit.'-';
		return substr($date,0,10);
	}
	function formatDate2Mysql($date,$dateformat){
		if (!strlen($date))
			return '';
		$daypos=strpos($dateformat,'gg');
		$monthpos=strpos($dateformat,'mm');
		$yearpos=strpos($dateformat,'aaaa');
		$day=substr($date,$daypos,2);
		$month=substr($date,$monthpos,2);
		$year=substr($date,$yearpos,4);
		return "$year-$month-$day";
	}
	// Generic Functions
	/*
	 * @function getCategoriesTree
	 * @desc		Restituisce un array annidato delle categorie
	 * @param	int $parent_id		Id della categoria da descrivere
	 */
	function getCategoriesTree($parent_id=NULL,$cPath='',$include_disabled=true){
		global $cPath_array, $language;
		$cats=array();

		$cachefile =  DIR_WS_CACHE . 'categories_box-'. $language . '.cache-' . $cPath;
		
		if (file_exists($cachefile)  ) // se esiste la cache bypassa tutta la parte delle query join
		{
			$fp = fopen($cachefile, 'r');
			$cats_str = fread($fp, filesize($cachefile));
			$cats = unserialize($cats_str);
		}	
		else 
		{	
			$level=0;
			$counter=0;
			$parent_id=$parent_id==NULL ? 0:$parent_id;
			$catpath=array();
			//$cPath_array=explode('_',$cPath);
			$this->parseCategoryPath();
			$this->getCategoriesTreeSub(&$cats,$parent_id,&$counter,&$level,&$catpath,$cPath_array,$include_disabled);
			
			$cats_str = serialize($cats);
	 	    // open the cache file for writing
		    $fp = fopen($cachefile, 'w'); 
		      // save the contents of output buffer to the file
			  fwrite($fp, $cats_str);
			  // close the file
		      fclose($fp); 
		}
		return $cats;
	}
	function getCategoriesTreeSub(&$tree,$parent_id,&$counter,&$level,&$catpath,$cPath_array,$include_disabled=false)
	{
		global $languages_id;
		$categories_query = "select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c left join " . TABLE_CATEGORIES_DESCRIPTION . " cd on (c.categories_id = cd.categories_id and cd.language_id='$languages_id') where c.parent_id = '$parent_id'";
		//if (!$include_disabled)
			$categories_query .=" and c.categories_status = '1'";
		$categories_query .=" order by sort_order, cd.categories_name";
		$categories_query = tep_db_query($categories_query);
		while ($categories = tep_db_fetch_array($categories_query))  {
			$catid = $categories['categories_id'];
			if (!is_null($this->_pws_clothing) && !$this->_pws_clothing->isOkayWithCategory($catid,true))
				continue;
			$tree[$catid] = array(
				'name' => $categories['categories_name'],
				'nodename' => 'node_'.($counter++),
				'parent' => $categories['parent_id'],
				'level' => $level,
				'path' => sizeof($catpath)>0 ? implode('_',$catpath).'_'.$catid : $catid,
				'next_id' => false,
				'type'=>'category',
				'subcategories'=>array(),
				'products'=>array(),
				'num_products'=>$this->tep_count_products_in_category($catid),
				'href'=>tep_href_link(FILENAME_DEFAULT,'cPath='.(sizeof($catpath)>0 ? implode('_',$catpath).'_'.$catid : $catid)),
				'open'=>(in_array($catid, $cPath_array))
				);
	
			$catary = &$tree[$catid];
			// Ricursione per le sotto categorie
			$level++;
			array_push($catpath,$catid);
			$this->getCategoriesTreeSub(&$catary['subcategories'],$catid,&$counter,&$level,&$catpath,$cPath_array,$include_disabled);
			// Caricamento dei prodotti, se l'impostazione dal lato amministrazione è positiva
			array_pop($catpath);
			--$level;
		}
	}
  function tep_count_products_in_category($category_id, $include_inactive = false) {
    $products_count = 0;
    if ($include_inactive == true) {
      $products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$category_id . "'");
    } else {
      $products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p.products_status = '1' and p2c.categories_id = '" . (int)$category_id . "'");
    }
    $products = tep_db_fetch_array($products_query);
    $products_count += $products['total'];

    $child_categories_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$category_id . "'");
    if (tep_db_num_rows($child_categories_query)) {
      while ($child_categories = tep_db_fetch_array($child_categories_query)) {
        $products_count += tep_count_products_in_category($child_categories['categories_id'], $include_inactive);
      }
    }

    return $products_count;
  }
	
	// @function getCategoryPathName
	// @desc	Dato un'id di una categoria, restituisce il percorso della stessa, separata da un separatore fornito
	// @param	int		categories_id		Id della categoria
	// @param	string	separator			Separatore fra i nomi delle categorie
	function getCategoryPathName($categories_id, $separator=' / '){
		global $languages_id;
		$output=array();
		$rawquery="select c.parent_id, cd.categories_name from ".TABLE_CATEGORIES." c left join ".TABLE_CATEGORIES_DESCRIPTION." cd on (c.categories_id=cd.categories_id and cd.language_id='$languages_id') where c.categories_id=";
		while ($categories_id!=NULL){
			$query=tep_db_query($rawquery.$categories_id);
			if ($category=tep_db_fetch_array($query)){
				array_unshift($output,$category['categories_name']);
				$categories_id=$category['parent_id'];
			}else{
				$categories_id=NULL;
			}
		}
		return implode($separator,$output);
	}
	function parseCategoryPath()
	{
		//global $cPath, $cPath_array,$new_path, $languages_id;
		global $cPath, $cPath_array, $languages_id;
		$new_path = '';
		$first_id=false;
		$tree = array();
		$categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c left join " . TABLE_CATEGORIES_DESCRIPTION . " cd on (c.categories_id = cd.categories_id and cd.language_id='$languages_id' ) where c.parent_id = '0' order by sort_order, cd.categories_name");
		while ($categories = tep_db_fetch_array($categories_query))  {
			$tree[$categories['categories_id']] = array(
												'name' => $categories['categories_name'],
												'parent' => $categories['parent_id'],
												'level' => 0,
												'path' => $categories['categories_id'],
												'next_id' => false);
	
			if (isset($parent_id)) {
				$tree[$parent_id]['next_id'] = $categories['categories_id'];
			}
	
			$parent_id = $categories['categories_id'];
	
			if (!isset($first_element)) {
				$first_element = $categories['categories_id'];
			}
		}
		//------------------------
		if (tep_not_null($cPath)) {
			$new_path = '';
			reset($cPath_array);
			while (list($key, $value) = each($cPath_array)) {
				unset($parent_id);
				unset($first_id);
				$categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$value . "' and c.categories_id = cd.categories_id and cd.language_id='" . (int)$languages_id ."' order by sort_order, cd.categories_name");
				if (tep_db_num_rows($categories_query)) {
					$new_path .= $value;
					while ($row = tep_db_fetch_array($categories_query)) {
						$tree[$row['categories_id']] = array('name' => $row['categories_name'],
							   'parent' => $row['parent_id'],
							   'level' => $key+1,
							   'path' => $new_path . '_' . $row['categories_id'],
							   'next_id' => false);
						
						if (isset($parent_id)) {
							$tree[$parent_id]['next_id'] = $row['categories_id'];
						}
						
						$parent_id = $row['categories_id'];
						
						if (!isset($first_id)) {
							$first_id = $row['categories_id'];
						}
						
						$last_id = $row['categories_id'];
					}
					$tree[$last_id]['next_id'] = $tree[$value]['next_id'];
					$tree[$value]['next_id'] = $first_id;
					$new_path .= '_';
				} else {
					break;
				}
			}
		}
		return array($tree,$first_element,$new_path);
	}
	////////////////////////////////////////////////////////////////////
	// EXTRA PACKAGES
	////////////////////////////////////////////////////////////////////
	// @function getExtraPackageInfo
	// @desc	Estrae le informazioni di un pacchetto extra, dato il codice del pacchetto
	// @param	(string) 	$packageCode			Codice del pacchetto
	// @return	(array)		Array dom del pacchetto
	function getExtraPackageInfo($packageCode){
		require_once DIR_FS_PWS_CLASSES.'packages.php';
		$parser=xml_parser_create('ISO-8859-1');
		$xml_parser=new pws_packages_xml_parser($this);
		xml_set_object($parser, $xml_parser);
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_set_element_handler($parser, 'importProductTagOpen', 'importProductTagClose');
		xml_set_character_data_handler ($parser, 'importProductHandleCharacterData');
		$dtd=file_get_contents(DIR_FS_PWS.'extraPackages.dtd');
		$xmlcontent=file_get_contents(DIR_FS_PWS.'extraPackages.xml');
		$xmlcontent=preg_replace("/<!DOCTYPE[^>]*/",'',$xmlcontent);
		$xmlcontent=substr($xmlcontent,0,strpos($xmlcontent,'>')+1)
			."\r\n<!DOCTYPE PWSModule [\r\n".$dtd."\r\n]>\r\n"
			.substr($xmlcontent,strpos($xmlcontent,'<PWSPackages'));
		//var_dump($xmlcontent);exit;
		if (!xml_parse($parser, $xmlcontent))
		{
			$output=sprintf("errore nella lettura del file extraPackages.xml<br>linea:%s<br>col.:%s<br>errore:%s"
				,xml_get_current_line_number($parser)
				,xml_get_current_column_number($parser)
				,xml_error_string(xml_get_error_code($parser))
			 );
			echo $output;
			return false;
		}
		xml_parser_free($parser);
		reset($xml_parser->packagesList);
		$found=false;
		foreach($xml_parser->packagesList as $package){
			if ((isset($package['code']) && $package['code']==$packageCode)
				|| ($package['dirname']==$packageCode && $package['dirname']==$packageCode)){
				$found=true;
				break;
			}
		}
		
		return $found ? $package : false;
	}
	// @function getExtraPackages
	// @desc	Esegue il parsing del file extraPackages.xml per ottenere i pacchetti installati/installabili
	// @param	(string) 	$filter			installed|new|all
	// @return	(array)		Array di pacchetti
	function getExtraPackages($filter){
		require_once DIR_FS_PWS_CLASSES.'packages.php';
		$parser=xml_parser_create('ISO-8859-1');
		$xml_parser=new pws_packages_xml_parser($this);
		xml_set_object($parser, $xml_parser);
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_set_element_handler($parser, 'importProductTagOpen', 'importProductTagClose');
		xml_set_character_data_handler ($parser, 'importProductHandleCharacterData');
		$dtd=file_get_contents(DIR_FS_PWS.'extraPackages.dtd');
		$xmlcontent=file_get_contents(DIR_FS_PWS.'extraPackages.xml');
		$xmlcontent=preg_replace("/<!DOCTYPE[^>]*/",'',$xmlcontent);
		$xmlcontent=substr($xmlcontent,0,strpos($xmlcontent,'>')+1)
			."\r\n<!DOCTYPE PWSModule [\r\n".$dtd."\r\n]>\r\n"
			.substr($xmlcontent,strpos($xmlcontent,'<PWSPackages'));
		//var_dump($xmlcontent);exit;
		if (!xml_parse($parser, $xmlcontent))
		{
			$output=sprintf("errore nella lettura del file extraPackages.xml<br>linea:%s<br>col.:%s<br>errore:%s"
				,xml_get_current_line_number($parser)
				,xml_get_current_column_number($parser)
				,xml_error_string(xml_get_error_code($parser))
			 );
			echo $output;
			return false;
		}
		xml_parser_free($parser);
		$packages=array();
		reset($xml_parser->packagesList);
		foreach($xml_parser->packagesList as $package){
			switch ($filter){
				case 'new':
					if ($package['isNew']=='yes'){
						$packages[$package['type']][]=$package;
					}
					break;
				case 'present':
					if ($package['isPresent']=='yes'){
						$packages[$package['type']][]=$package;
					}
					break;
				case 'installed':
					if ($package['isInstalled']=='yes'){
						$packages[$package['type']][]=$package;
					}
					break;
				case 'all':
				default:
					$packages[$package['type']][]=$package;
			}
		}
		
		return $packages;
	}
	// @function installExtraPackage
	// @desc	Installa un modulo/plugin che deve essere presente nella directory pws_extras/$dirname
	// @param	(string)	$dirname		Nome della directory contenente il plugin
	// @return	(bool)		True se la copia non ha dato errori, false altrimenti
	function installExtraPackage($package){
		$dirname=$package['dirname'];
		$excluded_dirs=array(
			'.svn'
		);
		$result1=$result2=false;
		if (is_dir(DIR_FS_PWS_EXTRAS.$dirname.'/content/updated_sources/')){
			$result1=$this->move_dir_contents(DIR_FS_PWS_EXTRAS.$dirname.'/content/updated_sources/',DIR_FS_CATALOG,true,false,true,$excluded_dirs);
			if ($result1!=false)
				$this->reportError($result1);
		}
		if (is_dir(DIR_FS_PWS_EXTRAS.$dirname.'/content/init_resources/')){
			$result2=$this->move_dir_contents(DIR_FS_PWS_EXTRAS.$dirname.'/content/init_resources/',DIR_FS_CATALOG,true,false,false,$excluded_dirs);
			if ($result2!=false)
				$this->reportError($result2);
		}
		$success=$result1==false && $result2==false;
		if ($success){
			if (isset($package['install']['src'])
				&& $package['install']['src']!=''
				&& isset($package['auto_install'])
				&& $package['auto_install']=='yes'){
				$url=strpos($package['install']['src'],DIR_WS_ADMIN)?tep_href_link($package['install']['src']):HTTP_SERVER.DIR_WS_CATALOG.$package['install']['src'];
				if (function_exists('curl_exec')){
					$ch = curl_init();
					
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLOPT_TIMEOUT, 3600);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					
					$result = curl_exec($ch);
					
					curl_close($ch);
				}else{
					$result=file_get_contents($url);
				}
			}
			$this->reportSuccess("Pacchetto Installato. ($dirname)");
		}else{
			if (ini_get('safe_mode')=='1'){
				$this->reportError("Disabilitare la modalit&agrave; safe mode, e ritentare l'installazione");
			}
		}
		return $success;
	}
	// @function installExtraPackageByCode
	// @desc	Installa un modulo/plugin dal suo codice
	// @param	(string)	$packageCode		Codice del modulo / plugin
	// @return	(bool)		True se la copia non ha dato errori, false altrimenti
	function installExtraPackageByCode($packageCode){
		if (is_array($packageCode)){
			foreach($packageCode as $packCode){
				$this->installExtraPackageByCode($packCode);
			}
		}else{
			$package=$this->getExtraPackageInfo($packageCode);
			if (!$package){
				$this->reportError("Package: $packageCode not found");
				return false;
			}
			$this->installExtraPackage($package);
		}
		return true;
	}
	// @function copy_file
	// @desc	Copia o sposta un file in una directory di destinazione
	// @param	(string)	$srcdir				File sorgente
	// @param	(string)	$dstdir				Directory destinazione
	// @param	(bool)		$overwrite			Se impostato a false non sovrascrive i files, altrimenti ci prova, ed in caso di fallimento esce con errore
	// @param	(bool)		$delete_source		Se impostato a false esegue un copy, altrimenti un move
	// @return	(mixed)		Restituisce false se non sono stati riscontrati errori, una stringa con l'errore, altrimenti
	function copy_file($srcfile,$dstdir,$overwrite=false,$delete_source=false){
		if (substr($dstdir,-1)!='/'){
			$dstdir.='/';
		}
		$entry=basename($srcfile);
		$srcdir=dirname($srcfile).'/';
		//echo "file: $entry (da '$srcdir' a '$dstdir')<br/>\r\n";
		if (file_exists($dstdir.$entry)){
			if (!$overwrite)
				return false;
			else{
				@chmod($dstdir.$entry,0777);
				if (!@unlink($dstdir.$entry)){
					return sprintf(ERROR_PWS_MOVE_DIR_CONTENTS_FILE_OVERWRITE,"$dstdir$entry");
				}
			}
		}
		if (!@copy($srcdir.$entry,$dstdir.$entry)){
			return sprintf(ERROR_PWS_MOVE_DIR_CONTENTS_FILE_COPY,$entry,$dstdir);
		}
		if ($delete_source){
			@chmod($srcdir.$entry,0777);
			if (!@unlink($srcdir.$entry)){
				return sprintf(ERROR_PWS_MOVE_DIR_CONTENTS_FILE_DELETE,"$dstdir$entry");
			}
		}
		return false;
	}
	
	// @function move_dir_contents
	// @desc	Sposta il contenuto della directory sorgente nella directory destinazione
	// @param	(string)	$srcdir				Directory sorgente
	// @param	(string)	$dstdir				Directory destinazione
	// @param	(bool)		$recursion			Se impostato a true, copia ricorsivamente le sottodirectories ed il loro contenuto
	// @param	(bool)		$delete_source		Se impostato a false esegue un copy, altrimenti un move
	// @param	(bool)		$overwrite			Se impostato a false non sovrascrive i files, altrimenti ci prova, ed in caso di fallimento esce con errore
	// @param	(array)		$exclude_dirs		Pu� contenere i nomi delle directories da ignorare
	// @return	(mixed)		Restituisce false se non sono stati riscontrati errori, una stringa con l'errore, altrimenti
	function move_dir_contents($srcdir, $dstdir, $recursion=true, $delete_source=true, $overwrite=false, $exclude_dirs=array(), $start=true){
		/*$numargs = func_num_args();
		for ($i=0;$i<$numargs;$i++){
			$arg=func_get_arg($i);
			var_dump($arg);
		}
		exit;
		*/
		$error=false;
		if ($start){
			$srcdir=str_replace('\\','/',$srcdir);
			$srcdir=str_replace('//','/',$srcdir);
			$dstdir=str_replace('\\','/',$dstdir);
			$dstdir=str_replace('//','/',$dstdir);
			if (substr($srcdir,-1)!='/')
				$srcdir.='/';
			if (substr($dstdir,-1)!='/')
				$dstdir.='/';
		}
		
		if (is_dir($srcdir)){
			$srcdir_array=explode('/',$srcdir);
			$current_directory=array_pop($srcdir_array);
			if (!strlen($current_directory))
				$current_directory=array_pop($srcdir_array);
			if (in_array($current_directory,$exclude_dirs)){
				return false;
			}
			//echo "copia di '$srcdir' in '$dstdir'<br>\r\n";
			if (!$start){
				if (!is_dir($dstdir)){
					$oldumask = @umask(0777);
					//echo "mkdir $dstdir<br/>\r\n";
					if (!@mkdir(substr($dstdir,0,-1),0777)){
						return sprintf(ERROR_PWS_MOVE_DIR_CONTENTS_DIRECTORY_CREATE,$dstdir);
					}
					@chmod(substr($dstdir,0,-1),0777);
					@umask($oldumask);
				}
			}
			$dh=@opendir($srcdir);
			if (!$dh){
				return sprintf(ERROR_PWS_MOVE_DIR_CONTENTS_DIRECTORY_OPEN,$dstdir);
			}
			while (!$error && ($entry = readdir($dh)) !== false){
				if ($entry=='.' || $entry=='..')
					continue;
				$entry=str_replace('\\','/',$entry);
				if (is_dir($srcdir.$entry)){
					if (substr($entry,-1)!='/')
						$entry.='/';
					if ($recursion){
						if (false!=($error=$this->move_dir_contents($srcdir.$entry,$dstdir.$entry,$recursion,$delete_source,$overwrite,$exclude_dirs,false))){
							return $error;
						}
					}
				}else{
					if (file_exists($srcdir.$entry)){
						if (false!=($error=$this->copy_file($srcdir.$entry,$dstdir,$overwrite,$delete_source))){
							return $error;
						}
					}
				}
			}
			@closedir($dh);
			if ($delete_source){
				@chmod($srcdir,0777);
				if (!@rmdir($srcdir)){
					return sprintf(ERROR_PWS_MOVE_DIR_CONTENTS_DIRECTORY_DELETE,$srcdir);
				}
			}
			return false;
		}
	}
  function tep_get_languages() {
    $languages_query = tep_db_query("select languages_id, name, code, image, directory from " . TABLE_LANGUAGES . " order by sort_order");
    while ($languages = tep_db_fetch_array($languages_query)) {
      $languages_array[] = array('id' => $languages['languages_id'],
                                 'name' => $languages['name'],
                                 'code' => $languages['code'],
                                 'image' => $languages['image'],
                                 'directory' => $languages['directory']);
    }

    return $languages_array;
  }
	
}
?>