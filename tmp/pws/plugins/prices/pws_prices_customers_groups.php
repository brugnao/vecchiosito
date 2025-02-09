<?php
/*
 * @filename:	pws_prices_customers_groups.php
 * @version:	1.0
 * @project:	PWS
 *
 * @author:		Riccardo Roscilli <info@oscommerce.it>
 * @created:	15/mag/07
 * @modified:	06/giu/08 17:30:53
 * 
 * @copyright:	2006-2007	Riccardo Roscilli @ PWS
 *
 * @desc:	plugin sconti per gruppi clienti
 *
 * @TODO:		Continua da qui!
 */
  define('TABLE_PRODUCTS_GROUPS', 'products_groups');
  define('TABLE_CUSTOMERS_GROUPS', 'customers_groups');
  define('TABLE_PRODUCTS_GROUPS_STATUS',TABLE_PWS_PREFIX.'products_groups_status');
  define('TABLE_SPECIALS_RETAIL_PRICES', 'specials_retail_prices');
  define('TABLE_PRODUCTS_GROUP_PRICES', 'products_group_prices_cg_');
  // this will define the maximum time in minutes between updates of a products_group_prices_cg_# table
  // changes in table specials will trigger an immediate update if a query needs this particular table
  define('MAXIMUM_DELAY_UPDATE_PG_PRICES_TABLE', '15');
// BOF Separate Pricing Per Customer
// define the email address that can change customer_group_id on login
//define('SPPC_TOGGLE_LOGIN_PASSWORD', 'root@oscommerce.it');
// EOF Separate Pricing Per Customer
  
class	pws_prices_customers_groups	extends pws_plugin_price {
	// Variabili particolari per questo plugin
	var $admin_side;	// Boolean: true se il plugin sta girando dal lato amministrazione del sito
	// Variabili private
	var $customer_group_id='0';
	var $customer_group_default_discount=0.0;
	var $customer_group_show_tax='1';
	var $customer_group_tax_exempt='0';
	var $customer_group_show_prices='1';
	var $customer_group_hidden_prices_msg='Occorre registrarsi per vedere i prezzi';
	var $customer_groups;	// Gruppi cliente
// Variabili del plugin
	var $plugin_code='pws_prices_customers_groups';			// Codice univoco del plugin ( deve coincidere con il nome del file )
	var $plugin_name=TEXT_PWS_CUSTOMERS_GROUPS_NAME;		// Nome del plugin
	var $plugin_description=TEXT_PWS_CUSTOMERS_GROUPS_DESCRIPTION;	// Descrizione del plugin
	var $plugin_version_const='1.0';		// Versione del codice
	var	$plugin_excludes=array('pws_prices_specials');		// Contiene i plugin_code dei plugin che vanno esclusi quando viene attivato questo plugin
	var $plugin_needs=array('pws_prices_specials');	// Codici dei plugin da cui dipende=>istanza del plugin
	var	$plugin_editPage='customers_groups.php';
	var $plugin_configKeys=array();	// Chiavi di configurazione del plugin, in forma chiave=>array(field1=>value1, ..., fieldn=>valuen)
//	var	$plugin_conflicts=array('pws_prices_quantities');	// Questo plugin è incompatibile con il plugin sconti quantità
// modifica per  b2b e sconti quantit� con occultamento prezzi al pubblico.
	var	$plugin_conflicts=array('');	
	var $plugin_sort_order=4;
	// Definizione dei punti di intervento
	var	$plugin_hooks=array(
		'CATALOG_LOGIN'=>'catalogLogin'
		,'CATALOG_LOGOFF'=>'catalogLogoff'
//		,'CATALOG_ADDRESS_BOOK_UPDATE'=>'catalogAddressBookUpdate'
	);	// Definizione dei punti di intervento del plugin: array associativo -- codice della funzione=>nome del metodo da chiamare
	var $plugin_tables=array(
		TABLE_PRODUCTS_GROUPS=>"
(
  customers_group_id smallint UNSIGNED NOT NULL default '0',
  customers_group_discount decimal(6,2) NOT NULL default '0.00',
  products_id int(11) NOT NULL default '0',
  PRIMARY KEY  (customers_group_id, products_id)
)"
		,TABLE_PRODUCTS_GROUPS_STATUS=>"
(
  products_id int(11) NOT NULL default '0',
  pws_customers_groups_status enum('0','1') NOT NULL default '1',
  KEY  (products_id)
)"
		,TABLE_CUSTOMERS_GROUPS=>"
(
 customers_group_id smallint UNSIGNED NOT NULL,
 customers_group_name varchar(32) NOT NULL default '',
 customers_group_show_tax enum('1','0') NOT NULL,
 customers_group_tax_exempt enum('0','1') NOT NULL,
 customers_group_default_discount decimal(6,2) NOT NULL default '0.00',
 customers_group_show_prices enum('1','0') NOT NULL default '1',
 customers_group_hidden_prices_msg varchar(255) NOT NULL default 'Occorre registrarsi per vedere i prezzi',
 group_payment_allowed varchar(255) NOT NULL default '',
 group_shipment_allowed varchar(255) NOT NULL default '',
 PRIMARY KEY (customers_group_id)
)"
	);	// Tables utilizzate dal plugin
	
	var $js_edit_product_init='pwsInitGroupsDiscount();';		// Codice javascript da lanciare a caricamento ultimato della pagina
	
	
	function pws_prices_customers_groups(&$pws_engine){
		parent::pws_plugin_price(&$pws_engine);
	}

	function init(){
		global $sppc_customer_group_id;
		global $sppc_customer_group_show_tax;
		global $sppc_customer_group_show_prices;
		global $sppc_customer_group_hidden_prices_msg;
		global $sppc_customer_group_tax_exempt;
		global $customer_id;
		global $session_started;
		parent::init();

		$this->admin_side=$this->_pws_engine->isAdminSideRunning();
       	$this->customer_groups=array();
		$customers_group_query = tep_db_query("select * from " . TABLE_CUSTOMERS_GROUPS . " where 1 order by customers_group_id");
		while ($customers_group = tep_db_fetch_array($customers_group_query)) // Gets all of the customers groups
		{
			$customers_group_id=$customers_group['customers_group_id'];
			$this->customer_groups[$customers_group_id]=$customers_group;
		}
		
		if (!$this->admin_side){
			if(!tep_session_is_registered('sppc_customer_group_id')) {
				$this->customer_group_id = '0';
			} else {
				$this->customer_group_id = $sppc_customer_group_id;
			}
			if ($this->customer_group_id!='0'){
				$customers_group_discount_query=tep_db_query("select customers_group_default_discount from ".TABLE_CUSTOMERS_GROUPS." where customers_group_id='".$this->customer_group_id."'");
				if ($customers_group_discount=tep_db_fetch_array($customers_group_discount_query))
					$this->customer_group_default_discount=$customers_group_discount['customers_group_default_discount'];
			}
	        if(!tep_session_is_registered('sppc_customer_group_show_tax')) {
	        	$query=tep_db_query("select customers_group_show_tax from ".TABLE_CUSTOMERS_GROUPS." where customers_group_id='".$this->customer_group_id."'");
	        	$showtax=tep_db_fetch_array($query);
	        	$this->customer_group_show_tax = $showtax['customers_group_show_tax'];//'1';
	        } else {
	        	$this->customer_group_show_tax = $sppc_customer_group_show_tax;
	        }
	        if(!tep_session_is_registered('sppc_customer_group_show_prices')) {
	        	$query=tep_db_query("select customers_group_show_prices from ".TABLE_CUSTOMERS_GROUPS." where customers_group_id='".$this->customer_group_id."'");
	        	$showprices=tep_db_fetch_array($query);
	        	$this->customer_group_show_prices = $showprices['customers_group_show_prices'];//'1';
	        } else {
	        	$this->customer_group_show_prices = $sppc_customer_group_show_prices;
	        }
	        if(!tep_session_is_registered('sppc_customer_group_hidden_prices_msg')) {
	        	$query=tep_db_query("select customers_group_hidden_prices_msg from ".TABLE_CUSTOMERS_GROUPS." where customers_group_id='".$this->customer_group_id."'");
	        	$showprices=tep_db_fetch_array($query);
	        	$this->customer_group_hidden_prices_msg = $showprices['customers_group_hidden_prices_msg'];//'1';
	        } else {
	        	$this->customer_group_hidden_prices_msg = $sppc_customer_group_hidden_prices_msg;
	        }
	        if(!tep_session_is_registered('sppc_customer_group_tax_exempt')) {
	        	$query=tep_db_query("select customers_group_tax_exempt from ".TABLE_CUSTOMERS_GROUPS." where customers_group_id='".$this->customer_group_id."'");
	        	$taxexempt=tep_db_fetch_array($query);
	        	$this->customer_group_tax_exempt = $taxexempt['customers_group_tax_exempt'];// '0';
	        } else {
	        	$this->customer_group_tax_exempt = $sppc_customer_group_tax_exempt;
	        }
		}
 	}
 	function getCustomerGroupId($customer_id){
 		$query=tep_db_query("select customers_group_id from ".TABLE_CUSTOMERS." where customers_id='$customer_id'");
 		$customer=tep_db_fetch_array($query);
 		return $customer['customers_group_id'];
 	}
 	//	@function setCustomersGroup
	//	@desc	Imposta il gruppo cliente corrente. (è utilizzato in amministrazione per ottenere i prezzi di tutti i gruppi clienti, o altre impostazioni relative ai gruppi cliente)
 	function setCustomersGroup($customers_group_id){
 		$this->customer_group_id=$customers_group_id;
		if ($this->customer_group_id!='0'){
			$customers_group_discount_query=tep_db_query("select customers_group_default_discount from ".TABLE_CUSTOMERS_GROUPS." where customers_group_id='".$this->customer_group_id."'");
			if ($customers_group_discount=tep_db_fetch_array($customers_group_discount_query))
				$this->customer_group_default_discount=floatval($customers_group_discount['customers_group_default_discount']);
		}
       	$query=tep_db_query("select customers_group_show_tax from ".TABLE_CUSTOMERS_GROUPS." where customers_group_id='".$this->customer_group_id."'");
       	$showtax=tep_db_fetch_array($query);
       	$this->customer_group_show_tax = $showtax['customers_group_show_tax'];//'1';
       	$query=tep_db_query("select customers_group_show_prices from ".TABLE_CUSTOMERS_GROUPS." where customers_group_id='".$this->customer_group_id."'");
       	$showprices=tep_db_fetch_array($query);
       	$this->customer_group_show_prices = $showprices['customers_group_show_prices'];//'1';
       	$query=tep_db_query("select customers_group_hidden_prices_msg from ".TABLE_CUSTOMERS_GROUPS." where customers_group_id='".$this->customer_group_id."'");
       	$showprices=tep_db_fetch_array($query);
       	$this->customer_group_hidden_prices_msg = $showprices['customers_group_hidden_prices_msg'];//'1';
       	$query=tep_db_query("select customers_group_tax_exempt from ".TABLE_CUSTOMERS_GROUPS." where customers_group_id='".$this->customer_group_id."'");
       	$taxexempt=tep_db_fetch_array($query);
       	$this->customer_group_tax_exempt = $taxexempt['customers_group_tax_exempt'];// '0';
 	}
	//	@function	displayPriceWithTaxes()
	//	@desc		Restituisce un flag che rappresenta l'istruzione di visualizzare i prezzi tasse incluse
	//	@return		bool
	function	displayPriceWithTaxes(){
		return DISPLAY_PRICE_WITH_TAX == 'true' && $this->customer_group_show_tax == '1';
	}
	//	@function	displayPrices()
	//	@desc		Restituisce un flag che rappresenta l'istruzione di visualizzare i prezzi
	//	@return		bool
	function	displayPrices(){
		// return $this->customer_group_show_prices == '1';
		return $this->customer_group_show_prices;
	}
	//	@function	getHiddenPricesMessage
	//	@desc		Restituisce il messaggio da mostrare al posto dei prezzi
	//	@return		string
	function	getHiddenPricesMessage(){
		return $this->customer_group_hidden_prices_msg;
	}
	//	@function catalogStylesheet
	//	@desc	Restituisce il codice css utilizzato nel front end da tutti i plugin prezzi
	function	catalogStylesheet(){
		return '';
	}
	
	//	@function	getDiscountPercentage
	//	@desc		Restituisce la percentuale di sconto presente su un prodotto
	//	@param		int		$products_id		Id del prodotto per cui calcolare la percentuale di scontoo
	//	@param		int		$products_quantity	Numero colli
	//	@param		int		$customers_id		Id del cliente per cui generare la percentuale di sconto
	//	@return		float						la percentuale di sconto
	function getDiscountPercentage($products_id, $products_quantity=1, $customers_id=NULL)	{
		$status_query=tep_db_query("select pws_customers_groups_status from ".TABLE_PRODUCTS_GROUPS_STATUS." where products_id='$products_id'");
		$status=tep_db_fetch_array($status_query);
		if ($status['pws_customers_groups_status']=='1'){
			$customer_group_discount_query = tep_db_query("select customers_group_discount from " . TABLE_PRODUCTS_GROUPS . " where products_id = '$products_id' and customers_group_id =  '" . $this->customer_group_id. "'");
	        if ($customer_group_discount = tep_db_fetch_array($customer_group_discount_query))
	        	return $customer_group_discount['customers_group_discount'];
			else
				return $this->customer_group_default_discount;
		}
		else
			return 0.0;
	}
	//	@function	getDiscountPercentageByGroup
	//	@desc		Restituisce la percentuale di sconto presente su un prodotto per un dato gruppo clienti
	//	@param		int		$products_id		Id del prodotto per cui calcolare la percentuale di sconto
	//	@param		int		$customer_group_id	Id del gruppo clienti per cui generare la percentuale di sconto
	//	@return		float						la percentuale di sconto
	function getDiscountPercentageByGroup($products_id, $customer_group_id)	{
		// normalizzazione: se lo sconto è presente nella tabella products_groups, ma non è presente lo status, inseriamo il record nel db.
		// questo problema si verifica sopratutto nell'importazione dei dati da database esterni
		$customer_group_discount_query = tep_db_query("select customers_group_discount from " . TABLE_PRODUCTS_GROUPS . " where products_id = '$products_id' and customers_group_id =  '$customer_group_id'"); 
		$status_query=tep_db_query("select pws_customers_groups_status from ".TABLE_PRODUCTS_GROUPS_STATUS." where products_id='$products_id'");
		// print "funziona getdiscount percentage productid " . $products_id . " = " . tep_db_num_rows($status_query);
		//exit;
		
		$status=tep_db_fetch_array($status_query);
		if ($status['pws_customers_groups_status']=='1'){
		//	$customer_group_discount_query = tep_db_query("select customers_group_discount from " . TABLE_PRODUCTS_GROUPS . " where products_id = '$products_id' and customers_group_id =  '$customer_group_id'");
	        if ($customer_group_discount = tep_db_fetch_array($customer_group_discount_query))
	        	return $customer_group_discount['customers_group_discount'];
			else
				return $this->getCustomerGroupDiscount($customer_group_id);
		}
		else
			return 0.0;
	}
	//	@function	getNextPrice
	//	@desc		Elabora il prezzo successivo
	//	@param		int		$products_id		Id del prodotto per cui calcolare il prezzo successivo
	//	@param		float	$products_price		Prezzo	precedente del prodotto
	//	@param		int		$products_quantity	Numero colli
	//	@param		int		$customers_id		Id del cliente per cui generare il prezzo
	//	@return		float						Prezzo successivo del prodotto
	function getNextPrice($products_id, $products_price, $products_quantity=1, $customers_id=NULL)	{
        //echo "products_id=$products_id, products_price=$products_price<br>";
		//$status_query=tep_db_query("select pws_customers_groups_status from ".TABLE_PRODUCTS_GROUPS_STATUS." where products_id='$products_id'");
		//$status=tep_db_fetch_array($status_query);
		//if ($status['pws_customers_groups_status']=='1'){
		//echo "price before:$products_price<br/>";
		$status_query=tep_db_query("select pws_customers_groups_status from ".TABLE_PRODUCTS_GROUPS_STATUS." where products_id='$products_id' and pws_customers_groups_status='1'");
		if (tep_db_num_rows($status_query)>0){
			$customer_group_discount_query = tep_db_query("select customers_group_discount from " . TABLE_PRODUCTS_GROUPS . " where products_id = '$products_id' and customers_group_id =  '" . $this->customer_group_id. "'");
	        if ($customer_group_discount = tep_db_fetch_array($customer_group_discount_query)){
	        	$products_price=$products_price*(1.0-($customer_group_discount['customers_group_discount']/100.0));
	        }else{
				$products_price=$products_price*(1.0-$this->customer_group_default_discount/100.0);
	        }
		}
		//echo "discounted:$products_price<br>";
		return $products_price;
	}

	//	@function	getBestPrice
	//	@desc		Elabora il prezzo successivo migliore visualizzabile al cliente
	//	@param		int		$products_id		Id del prodotto per cui calcolare il prezzo successivo
	//	@param		float	$products_price		Prezzo	precedente del prodotto
	//	@param		int		$products_quantity	Numero colli
	//	@param		int		$customers_id		Id del cliente per cui generare il prezzo
	//	@return		float						Prezzo successivo del prodotto
	function getBestPrice($products_id, $products_price)	{
		return $this->getNextPrice($products_id,$products_price);
	}
	//	@function	getHtmlPriceResume
	//	@desc		Modifica il prezzo e restituisce il codice html contenente la descrizione dello sconto applicato sul prodotto
	//	@param		int		$products_id		Id del prodotto per cui calcolare il prezzo successivo
	//	@param		float	$products_price		Prezzo	precedente del prodotto
	//	@param		int		$products_quantity	Numero colli
	//	@param		int		$customers_id		Id del cliente per cui generare il prezzo
	//	@param		bool	$with_taxes			Per forzare la presentazione del prezzo con o senza tasse incluse
	//	@return		array('text'=>string,'content'=>string) Codice html che visualizza le opzioni prezzo possibili per questo prodotto
	function getHtmlPriceResume($products_id, &$products_price, $products_quantity=1, $customers_id=NULL, $with_taxes=true)	{
		$status_query=tep_db_query("select pws_customers_groups_status from ".TABLE_PRODUCTS_GROUPS_STATUS." where products_id='$products_id'");
		$status=tep_db_fetch_array($status_query);
		if ($status['pws_customers_groups_status']=='1'){
			$customer_group_discount_query = tep_db_query("select customers_group_discount from " . TABLE_PRODUCTS_GROUPS . " where products_id = '$products_id' and customers_group_id =  '" . $this->customer_group_id. "'");
	        if ($customer_group_discount = tep_db_fetch_array($customer_group_discount_query))
	        	$discount=$customer_group_discount['customers_group_discount'];
	        else
	        	$discount=$this->customer_group_default_discount;
		$products_price*=(1.0-($discount/100.0));
	        return $discount<>0?array('text'=>$this->admin_side ? TEXT_PWS_CUSTOMERS_GROUPS_COMPACT_TITLE_ADMIN:TEXT_PWS_CUSTOMERS_GROUPS_COMPACT_TITLE
				,'products_price'=>$products_price
	        	,'discount_perc'=>$discount
				,'tax_exempt'=>false
	        ):NULL;
		}else
			return NULL;
	}
	//	@function	getHtmlPriceDiscounts
	//	@desc		Restituisce il codice html contenente tutti gli sconti disponibili sul prodotto
	//	@param		int		$products_id		Id del prodotto per cui calcolare il prezzo successivo
	//	@param		float	$products_price		Prezzo	precedente del prodotto
	//	@return		array('text'=>string,'discount_perc'=>float,['content'=>string]) Codice html che visualizza le opzioni prezzo possibili per questo prodotto
	function getHtmlPriceDiscounts($products_id, &$products_price)	{
		$status_query=tep_db_query("select pws_customers_groups_status from ".TABLE_PRODUCTS_GROUPS_STATUS." where products_id='$products_id'");
		$status=tep_db_fetch_array($status_query);
		if ($status['pws_customers_groups_status']=='1'){
			$customer_group_discount_query = tep_db_query("select customers_group_discount from " . TABLE_PRODUCTS_GROUPS . " where products_id = '$products_id' and customers_group_id =  '" . $this->customer_group_id. "'");
	        if ($customer_group_discount = tep_db_fetch_array($customer_group_discount_query))
	        	$discount=$customer_group_discount['customers_group_discount'];
	        else
	        	$discount=$this->customer_group_default_discount;
	        $products_price*=(1-$discount/100.0);
			return $discount<>0 ? array('text'=>$this->admin_side ? TEXT_PWS_CUSTOMERS_GROUPS_COMPACT_TITLE_ADMIN:TEXT_PWS_CUSTOMERS_GROUPS_COMPACT_TITLE
				,'discount_perc'=>$discount
				,'tax_exempt'=>false
			):NULL;
		}else
			return NULL;
	}
	//	@functin	getShippingModules
	//	@desc		Restituisce i moduli shipping installati ed utilizzabili dal cliente loggato
	function getShippingModules(){
		global $customer_id;
      //   $this->modules = explode(';', MODULE_SHIPPING_INSTALLED);
		$customer_shipment_query = tep_db_query("select IF(c.customers_shipment_allowed <> '', c.customers_shipment_allowed, cg.group_shipment_allowed) as shipment_allowed from " . TABLE_CUSTOMERS . " c, " . TABLE_CUSTOMERS_GROUPS . " cg where c.customers_id = '" . $customer_id . "' and cg.customers_group_id =  '" . $this->customer_group_id. "'");
		if ($customer_shipment = tep_db_fetch_array($customer_shipment_query)  ) {
			if (tep_not_null($customer_shipment['shipment_allowed']) ) {
				$temp_shipment_array = explode(';', $customer_shipment['shipment_allowed']);
				$installed_modules = explode(';', MODULE_SHIPPING_INSTALLED);
				$shipment_array=array();
				for ($n = 0; $n < sizeof($installed_modules) ; $n++) {
					// check to see if a shipping module is not de-installed
					if ( in_array($installed_modules[$n], $temp_shipment_array ) ) {
						$shipment_array[] = $installed_modules[$n];
					}
				} // end for loop
				$modules = $shipment_array;
			} else {
				$modules = explode(';', MODULE_SHIPPING_INSTALLED);
			}
		} else { // default
			$modules = explode(';', MODULE_SHIPPING_INSTALLED);
		}
		return $modules;
	}
	//	@functin	getPaymentModules
	//	@desc		Restituisce i moduli pagamento installati ed utilizzabili dal cliente loggato
	function getPaymentModules(){
		global $customer_id;
      //   $this->modules = explode(';', MODULE_SHIPPING_INSTALLED);
		$customer_payment_query = tep_db_query("select IF(c.customers_payment_allowed <> '', c.customers_payment_allowed, cg.group_payment_allowed) as payment_allowed from " . TABLE_CUSTOMERS . " c, " . TABLE_CUSTOMERS_GROUPS . " cg where c.customers_id = '" . $customer_id . "' and cg.customers_group_id =  '" . $this->customer_group_id . "'");
		if ($customer_payment = tep_db_fetch_array($customer_payment_query)  ) {
			if (tep_not_null($customer_payment['payment_allowed'])) {
				$temp_payment_array = explode(';', $customer_payment['payment_allowed']);
				$installed_modules = explode(';', MODULE_PAYMENT_INSTALLED);
				$payment_array=array();
				for ($n = 0; $n < sizeof($installed_modules) ; $n++) {
					// check to see if a shipping module is not de-installed
					if ( in_array($installed_modules[$n], $temp_payment_array ) ) {
						$payment_array[] = $installed_modules[$n];
					}
				} // end for loop
				$modules = $payment_array;
			} else {
				$modules = explode(';', MODULE_PAYMENT_INSTALLED);
			}
		} else { // default
			$modules = explode(';', MODULE_PAYMENT_INSTALLED);
		}
		return $modules;
	}
	//	@function	statusOnProduct
	//	@param		int		$products_id		Id del prodotto su cui è richiesto lo stato del plugin
	//	@return 	bool				Stato del plugin
	function statusOnProduct($products_id){
		$statusQuery=tep_db_query("select pws_customers_groups_status from ".TABLE_PRODUCTS_GROUPS_STATUS." where products_id=".$products_id);
		$status=tep_db_fetch_array($statusQuery);
		$status=$status['pws_customers_groups_status'];
		return $status=='1';
	}
	//	@function	adminDisableOnProduct
	//	@param		int		$products_id		Id del prodotto su cui disabilitare eventuali offerte
	//	@return		bool						Restituisce true se è stato disabilitato uno sconto
	function adminDisableOnProduct($products_id){
		$statusQuery=tep_db_query("select pws_customers_groups_status from ".TABLE_PRODUCTS_GROUPS_STATUS." where products_id=".$products_id);
		$status=tep_db_fetch_array($statusQuery);
		$status=$status['pws_customers_groups_status'];
		if ($status=='1'){
			tep_db_query("update ".TABLE_PRODUCTS_GROUPS_STATUS." set pws_customers_groups_status='0' where products_id='$products_id'");
			return true;
		}else
			return false;
	}
	//	@function	getStatus
	//	@desc	Restituisce true se il plugin applica qualche modifica al prezzo
	//	@param	int	$products_id
	function	getStatus($products_id){
		$check_query=tep_db_query("select * from ".TABLE_PRODUCTS_GROUPS_STATUS." where products_id='".$products_id."' and pws_customers_groups_status");
		return tep_db_num_rows($check_query)>0;
	}
	// 	@function	hasGroupDiscount
	//	@desc	Restituisce true se per il prodotto e per il gruppo cliente, è definito uno sconto particolare
	//	@param	(int)	$products_id		Id del prodotto
	//	@param	(int)	$customer_group_id	Id del gruppo cliente
	//	@return	(bool)	true se e' definito uno sconto per il gruppo cliente su questo prodotto
	function hasGroupDiscount($products_id,$customer_group_id){
		return tep_db_num_rows(tep_db_query("select * from ".TABLE_PRODUCTS_GROUPS." where products_id='$products_id' and customers_group_id='$customer_group_id'"))>0;
	}
	//	@function adminNewProduct
	//	@desc		Crea nella struttura pInfo i propri dati relativi al prodotto
	function adminNewProduct(&$pInfo){
		$pInfo->objectInfo(array(
			'pws_customers_groups_status'=>!(isset($pInfo->pws_specials_status) && $pInfo->pws_specials_status=='1')
		));
		$pInfo->objectInfo($this->getCustomersGroupsDefaultDiscounts());
		return true;
	}
	//	@function adminNewProductSetDefault
	//	@desc		Crea nella struttura pInfo i propri dati relativi al prodotto
	function	adminNewProductSetDefault($products_id){
		$discount_status=parent::getStatus($products_id);
		$sql_data_array=array(
			'pws_customers_groups_status'=>$discount_status ? '1':'0'
		);
		$productsQuery=tep_db_query("select * from ".TABLE_PRODUCTS_GROUPS_STATUS." where products_id='$products_id'");
		if ($product=tep_db_fetch_array($productsQuery)){
			if ($product['pws_customers_groups_status']=='1' && !$discount_status)
				tep_db_perform(TABLE_PRODUCTS_GROUPS_STATUS,$sql_data_array,'update',"products_id=$products_id");
		}else{
			$sql_data_array['products_id']=$products_id;
			tep_db_perform(TABLE_PRODUCTS_GROUPS_STATUS,$sql_data_array);
		}
	}
	//	@function adminLoadProduct
	//	@desc		Carica nella struttura pInfo i propri dati relativi al prodotto
	function adminLoadProduct(&$pInfo){
		$statusQuery=tep_db_query("select pws_customers_groups_status from ".TABLE_PRODUCTS_GROUPS_STATUS." where products_id=".$pInfo->products_id);
		$status=tep_db_fetch_array($statusQuery);
		$status=$status['pws_customers_groups_status'];
		$pInfo->objectInfo(array(
			'pws_customers_groups_status'=>$status=='1'
		));
		$pInfo->objectInfo($this->getCustomersGroupsDefaultDiscounts($pInfo->products_id));
		return true;
	}
	//	@function adminJavascript
	//	@desc		Restituisce il codice javascript utilizzato nell'editing dei parametri
	function adminJavascript(&$pInfo){
?>
		function pwsInitGroupsDiscount(){
			var tolisten;
<?			reset($this->price_editing_fields_chain);
			foreach($this->price_editing_fields_chain as $elementname){?>
			tolisten=$('<?=$elementname?>');
			pwsAttachEvent(tolisten,'change',pwsUpdateGroupsDiscountPrice);
			pwsAttachEvent(tolisten,'blur',pwsUpdateGroupsDiscountPrice);
			pwsAttachEvent(tolisten,'keyup',pwsUpdateGroupsDiscountPrice);
<?			}?>
			pwsUpdateGroupsDiscountPrice();
		}
		function pwsUpdateGroupsDiscountPrice(){
			var pws_customers_groups_status=$('pws_customers_groups_status').checked;
			var pricenet_from=$('<?=$this->varname_products_price?>');
			var pricegross_from=$('<?=$this->varname_products_price_gross?>');
			var pricenet;
			var pricegross;
			var discount;
			pricenet_from=parseFloat(pricenet_from.value);
			pricegross_from=parseFloat(pricegross_from.value);
			
<?
			reset($this->varname_products_price_to);
			foreach($this->varname_products_price_to as $customer_group_id=>$varname){
?>
				pricenet=$('<?=$varname?>');
				discount=$('customers_group_discount[<?=$customer_group_id?>]');
				discount=pws_customers_groups_status ? parseFloat(discount.value) : 0.0;
				pricenet.value=doRound(pricenet_from*(1-discount/100.0),2);
<?			}
			reset($this->varname_products_price_gross_to);
			foreach($this->varname_products_price_gross_to as $customer_group_id=>$varname){
?>
				pricegross=$('<?=$varname?>');
				discount=$('customers_group_discount[<?=$customer_group_id?>]');
				discount=pws_customers_groups_status ? parseFloat(discount.value) : 0.0;
				pricegross.value=doRound(pricegross_from*(1-discount/100.0),2);
<?			}?>
		}
		function onCustomersGroupsChange(){
			var pws_customers_groups_div=$("pws_customers_groups_div");
			var theform = document.forms["new_product"];
			pws_customers_groups_div.style.display=theform.pws_customers_groups_status.checked ? 'block' : 'none';
<?	if ($this->plugin_using['pws_prices_specials']!=NULL){?>
			var specials_status=theform.pws_specials_status;
			if (specials_status && specials_status.checked && theform.pws_customers_groups_status.checked){
				specials_status.checked=false;
				pwsSpecialsStatusChange();
			}
<?	}?>
			pwsUpdateGroupsDiscountPrice();
		}
		function pwsCustomersGroupsInputStart(customers_id){
			var theform = document.forms["new_product"];
			var textfield = $("customers_group_discount["+customers_id+"]");
			textfield.style.color="black";
		}
		function pwsCustomersGroupsInputStop(customers_id){
			var theform = document.forms["new_product"];
			var textfield = $("customers_group_discount["+customers_id+"]");
			var defaultvalue=$("customers_group_default_discount["+customers_id+"]");
			if (textfield.value=='' || textfield.value==defaultvalue.value || isNaN(textfield.value) || parseFloat(textfield.value)==parseFloat(defaultvalue.value)){
				textfield.style.color="gray";
				textfield.value=defaultvalue.value;
			}else{
				textfield.style.color="black";
			}
			pwsUpdateGroupsDiscountPrice();
		}
		function pwsCustomersGroupsDiscountChange(i){
			var cg_price=$('pws_customer_group_price['+i+']');
			var cg_price_gross=$('pws_customer_group_price_gross['+i+']');
			var cg_discount=$('customers_group_discount['+i+']');
			var products_price=$('<?=$this->varname_products_price?>');
			var products_price_gross=$('<?=$this->varname_products_price_gross?>');
			cg_price.value=doRound(products_price.value*(1-parseFloat(cg_discount.value)/100.0),2);
			cg_price_gross.value=doRound(products_price_gross.value*(1-parseFloat(cg_discount.value)/100.0),2);
		}
<?
	}
	//	@function adminSetEditProductEventChain
	//	@desc		Imposta i nomi degli id dei textfields contenenti i valori dei prezzi elaborati dal plugin precedente
	//				ed esporta i nomi dei nuovi textfields contenenti i valori elaborati
	//				Aggiunge inoltre alla catena dei campi da tenere sotto osservazione,
	//				i campi che possono produrre variazioni dei prezzi a cascata
	//	@param	string	$varname_products_price			[I/O]	Nome del textfield contenente il prezzo netto
	//	@param	string	$varname_products_price_gross	[I/O]	Nome del textfield contenente il prezzo lordo
	//	@param	array	$fields_chain					[I/O]	Stringhe dei campi da tenere sotto osservazione
	function adminSetEditProductEventChain(&$varname_products_price,&$varname_products_price_gross,&$fields_chain){
		$this->varname_products_price=$varname_products_price;
		$this->varname_products_price_gross=$varname_products_price_gross;
		$this->varname_products_price_to=array();
		$this->varname_products_price_gross_to=array();
		$this->price_editing_fields_chain=$fields_chain;
		$query=tep_db_query("select customers_group_id from ".TABLE_CUSTOMERS_GROUPS." where 1");		
		while ($customer_group=tep_db_fetch_array($query)){
			$customer_group_id=$customer_group['customers_group_id'];
			$this->varname_products_price_to[$customer_group_id]="pws_customer_group_price[$customer_group_id]";
			$this->varname_products_price_gross_to[$customer_group_id]="pws_customer_group_price_gross[$customer_group_id]";
			$fields_chain[]="customers_group_discount[$customer_group_id]";
		}
		$varname_products_price=$this->varname_products_price_to[0];
		$varname_products_price_gross=$this->varname_products_price_gross_to[0];
	}
	//	@function adminEditProduct
	//	@desc		Crea il codice html per l'editing del prodotto, nella sezione admin
	function adminEditProduct($products_id,&$pInfo){
?>
<tr bgcolor="#ebebff">
		<td colspan="2">
		 <fieldset><legend><input type="checkbox" id="pws_customers_groups_status" name="pws_customers_groups_status" value="1" <?=$pInfo->pws_customers_groups_status? 'checked="true" ':''?> onClick="onCustomersGroupsChange()" tal:condition="pws_customers_groups_status"/><label for="pws_customers_groups_status" class="pws_fieldset_label"><?=TEXT_PWS_CUSTOMERS_GROUPS_STATUS?></label></legend>
		 <div id="pws_customers_groups_div" <?=$pInfo->pws_customers_groups_status?'style="display:block"':'style="display:none"'?>>
			<table>
			<tr>
				<th class="main"></th>
				<th class="main"><?=TEXT_PWS_CUSTOMERS_GROUPS_DISCOUNT?></th>
				<th class="main"><?=TEXT_PWS_CUSTOMERS_GROUPS_PRICE?></th>
				<th class="main"><?=TEXT_PWS_CUSTOMERS_GROUPS_PRICE_GROSS?></th>
			</tr>
<?

		reset($pInfo->customers_group_id);		
		foreach($pInfo->customers_group_id as $customers_group_id=>$id){
			$customers_group_discount=$pInfo->customers_group_discount[$id];
			$customers_group_default_discount=$pInfo->customers_group_default_discount[$id];
			$customers_group_name=$pInfo->customers_group_name[$id];
			$style=$customers_group_discount==''?'color:gray;':'color:black';
			$value=$customers_group_discount!=''?$customers_group_discount:$customers_group_default_discount;

?>
			<tr>
				<td class="main"><?=$customers_group_name?></td>
				<td class="main"><input type="text" name="customers_group_discount[<?=$customers_group_id?>]" id="customers_group_discount[<?=$customers_group_id?>]" value="<?=$value?>" style="<?=$style?>" onClick="pwsCustomersGroupsInputStart(<?=$customers_group_id?>)" onBlur="pwsCustomersGroupsInputStop(<?=$customers_group_id?>)" size="7"/>&nbsp;<b>%</b>
					<input type="hidden" name="customers_group_default_discount[<?=$customers_group_id?>]" id="customers_group_default_discount[<?=$customers_group_id?>]" value="<?=$customers_group_default_discount?>"/>
					<input type="hidden" name="customers_group_name[<?=$customers_group_id?>]" id="customers_group_name[<?=$customers_group_id?>]" value="<?=$customers_group_name?>"/>
					<input type="hidden" name="customers_group_id[<?=$customers_group_id?>]" id="customers_group_id[<?=$customers_group_id?>]" value="<?=$customers_group_id?>"/>
				</td>
				<td class="main"><input type="text" readonly="true" class="pws_textfield_disabled" name="<?=$this->varname_products_price_to[$customers_group_id]?>" id="<?=$this->varname_products_price_to[$customers_group_id]?>"/></td>
				<td class="main"><input type="text" readonly="true" class="pws_textfield_disabled" name="<?=$this->varname_products_price_gross_to[$customers_group_id]?>" id="<?=$this->varname_products_price_gross_to[$customers_group_id]?>"/></td>
			</tr>
<?
		}
?>
		 </table></div></fieldset>
		 </td></tr>
<?
	}
	// @function getCustomersGroupsDefaultDiscounts
	// @desc	Restituisce gli sconti di default per gruppi clienti (oppure gli sconti su un prodotto)
	// @param	(int)	$products_id		[Opzionale] Id del prodotto
	function getCustomersGroupsDefaultDiscounts($products_id=NULL){
		$customer_group_discount_query = tep_db_query("select * from " . TABLE_CUSTOMERS_GROUPS);
		$result=array(
			'customers_group_default_discount'=>array()
			,'customers_group_discount'=>array()
			,'customers_group_name'=>array()
			,'customers_group_id'=>array()
		);
		$discount='';
		while ($group=tep_db_fetch_array($customer_group_discount_query)){
			if (!is_null($products_id)){
				$discount='';
				$products_query=tep_db_query("select customers_group_discount from ".TABLE_PRODUCTS_GROUPS." where products_id='$products_id' and customers_group_id=".$group['customers_group_id']);
				if ($product=tep_db_fetch_array($products_query))
					$discount=$product['customers_group_discount'];
			}
			$result['customers_group_default_discount'][$group['customers_group_id']]=$group['customers_group_default_discount'];
			$result['customers_group_discount'][$group['customers_group_id']]=isset($_REQUEST['customers_group_discount'][$group['customers_group_id']])?$_REQUEST['customers_group_discount'][$group['customers_group_id']]:$discount;
			$result['customers_group_name'][$group['customers_group_id']]=$group['customers_group_name'];
			$result['customers_group_id'][$group['customers_group_id']]=$group['customers_group_id'];
		}
		return $result;
	}
	// @function getCustomerGroupDiscount
	// @desc	Restituisce lo sconto di default su un gruppo cliente
	// @param	(int)	$customer_group_id	Id del gruppo clienti
	function getCustomerGroupDiscount($customer_group_id){
		$customers_group_discount_query=tep_db_query("select customers_group_default_discount from ".TABLE_CUSTOMERS_GROUPS." where customers_group_id='$customer_group_id'");
		if ($customers_group_discount=tep_db_fetch_array($customers_group_discount_query))
			return $customers_group_discount['customers_group_default_discount'];
		else
			return 0.0;
	}
	//	@function getCustomersGroupsDescriptors
	//	@desc	Funzione utilizzata da altri plugin per avere informazioni sui gruppi clienti
	function getCustomersGroupsDescriptors(){
		$query=tep_db_query("select * from ".TABLE_CUSTOMERS_GROUPS." where 1 order by customers_group_id");
		$cgroups=array();
		while($cgroup=tep_db_fetch_array($query)){
			$cgroups[$cgroup['customers_group_id']]=$cgroup;
		}
		return $cgroups;
	}
	// @function adminSetProductGroupDiscount
	// @desc	Imposta uno sconto per un gruppo cliente su un prodotto
	// @param	(int)	$products_id		Id del prodotto
	// @param	(int)	$customer_group_id	Id del gruppo clienti
	// @param	(float)	$discount			Sconto sul prodotto
	// @return 	(bool)	Restituisce true se lo sconto e' cambiato
	function adminSetProductGroupDiscount($products_id,$customer_group_id,$discount){
		$default_discount = $this->getCustomerGroupDiscount($customer_group_id);
		$customer_group_discount_query = tep_db_query("select customers_group_discount from " . TABLE_PRODUCTS_GROUPS . " where products_id = '$products_id' and customers_group_id =  '$customer_group_id'");
		if ($customer_group_discount = tep_db_fetch_array($customer_group_discount_query))
			$old_discount=$customer_group_discount['customers_group_discount'];
		else
			$old_discount=false;
		if ($default_discount==$discount){
			tep_db_query("delete from ".TABLE_PRODUCTS_GROUPS." where products_id='$products_id' and customers_group_id='$customer_group_id'");
		}else{
		// controlla se la tabella groups_status è a posto	
		$check_query=tep_db_query("select * from ".TABLE_PRODUCTS_GROUPS_STATUS." where products_id='$products_id'");
		if (tep_db_num_rows($check_query))
			tep_db_query("update ".TABLE_PRODUCTS_GROUPS_STATUS." set pws_customers_groups_status='1' where products_id='$products_id'");
		else
			tep_db_query("insert into ".TABLE_PRODUCTS_GROUPS_STATUS." set pws_customers_groups_status='1', products_id='$products_id'");
		
			
			$sql_array=array(
				'customers_group_discount'=>$discount
			);
			if ($old_discount===false){
				$sql_array=array_merge($sql_array,array(
					'customers_group_id'=>$customer_group_id
					,'products_id'=>$products_id
				));
				tep_db_perform(TABLE_PRODUCTS_GROUPS,$sql_array);
				
			
				
			}else{
				tep_db_perform(TABLE_PRODUCTS_GROUPS,$sql_array,'update',"customers_group_id='$customer_group_id' and products_id='$products_id'");
			}
		}
	//	print "ok" . mysql_affected_rows();
	//	print "product id = ". $products_id;
		//exit;
		return $old_discount===$discount;
	}
	function adminUpdateProduct($products_id){
		$check_query=tep_db_query("select * from ".TABLE_PRODUCTS_GROUPS_STATUS." where products_id='$products_id'");
		if (tep_db_num_rows($check_query))
			tep_db_query("update ".TABLE_PRODUCTS_GROUPS_STATUS." set pws_customers_groups_status='".(isset($_REQUEST['pws_customers_groups_status'])?'1':'0')."' where products_id='$products_id'");
		else
			tep_db_query("insert into ".TABLE_PRODUCTS_GROUPS_STATUS." set pws_customers_groups_status='".(isset($_REQUEST['pws_customers_groups_status'])?'1':'0')."', products_id='$products_id'");
		
		tep_db_query("delete from ".TABLE_PRODUCTS_GROUPS." where products_id='$products_id'");
		$default_discounts=$_REQUEST['customers_group_default_discount'];
		$discounts=$_REQUEST['customers_group_discount'];
		reset($this->customer_groups);
		foreach($this->customer_groups as $customers_group_id=>$customers_group){
			//$customers_group_id=$customers_group['customers_group_id'];
			if ($discounts[$customers_group_id]!=$default_discounts[$customers_group_id])
				tep_db_query("insert into ".TABLE_PRODUCTS_GROUPS." set customers_group_discount='".$discounts[$customers_group_id]."',products_id='$products_id',customers_group_id='$customers_group_id'");
		}
	}
	//	@function adminDeleteProduct
	//	@desc	Funzione di eliminazione di un prodotto, innescata da pws_engine
	function adminDeleteProduct($products_id){
		tep_db_query("delete from " . TABLE_PRODUCTS_GROUPS . " where products_id = '" . (int)$products_id . "'");
		tep_db_query("delete from " . TABLE_PRODUCTS_GROUPS_STATUS . " where products_id = '" . (int)$products_id . "'");
		return true;
	}
	//	@function	adminCopyProduct
	//	@desc		Duplica i dati relativi ad un prodotto
	function adminCopyProduct($products_id,$dup_products_id){
		$status_query=tep_db_query("select * from ".TABLE_PRODUCTS_GROUPS_STATUS." where products_id='$products_id'");
		$status=tep_db_fetch_array($status_query);
		$status=$status['pws_customers_groups_status'];
		tep_db_query("insert into ".TABLE_PRODUCTS_GROUPS_STATUS." set pws_customers_groups_status='$status', products_id='$dup_products_id'");
		//$customers_group_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " where customers_group_id != '0' order by customers_group_id");
		$products_group_query=tep_db_query("select * from " . TABLE_PRODUCTS_GROUPS . " where products_id = '$products_id' order by customers_group_id");
		while ($products_group=tep_db_fetch_array($products_group_query)){
			$products_group['products_id']=$dup_products_id;
			tep_db_perform(TABLE_PRODUCTS_GROUPS,$products_group);
		}
	}
	
	//	@function catalogLogin
	//	@desc	Funzione richiamata da pws_engine durante il login
	function catalogLogin(){
		global $request_type;
		global $sppc_customer_group_id;
		global $sppc_customer_group_show_tax;
		global $sppc_customer_group_show_prices;
		global $sppc_customer_group_hidden_prices_msg;
		global $sppc_customer_group_tax_exempt;
		global $check_customer;
// BOF Separate Pricing Per Customer: choice for logging in under any customer_group_id
// note that tax rates depend on your registered address!
if ($_GET['skip'] != 'true' && $_POST['email_address'] == SPPC_TOGGLE_LOGIN_PASSWORD ) {
   $existing_customers_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " order by customers_group_id ");
echo '<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">';
print ("\n<html ");
echo HTML_PARAMS;
print (">\n<head>\n<title>Choose a Customer Group</title>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=");
echo CHARSET;
print ("\"\n<base href=\"");
echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG;
print ("\">\n<link rel=\"stylesheet\" type=\"text/css\" href=\"stylesheet.css\">\n");
echo '<body bgcolor="#ffffff" style="margin:0">';
print ("\n<table border=\"0\" width=\"100%\" height=\"100%\">\n<tr>\n<td style=\"vertical-align: middle\" align=\"middle\">\n");
echo tep_draw_form('login', tep_href_link(FILENAME_LOGIN, 'action=process&skip=true', 'SSL'));
print ("\n<table border=\"0\" bgcolor=\"#f1f9fe\" cellspacing=\"10\" style=\"border: 1px solid #7b9ebd;\">\n<tr>\n<td class=\"main\">\n");
  $index = 0;
  while ($existing_customers =  tep_db_fetch_array($existing_customers_query)) {
 $existing_customers_array[] = array("id" => $existing_customers['customers_group_id'], "text" => "&#160;".$existing_customers['customers_group_name']."&#160;");
    ++$index;
  }
print ("<h1>Choose a Customer Group</h1>\n</td>\n</tr>\n<tr>\n<td align=\"center\">\n");
echo tep_draw_pull_down_menu('new_customers_group_id', $existing_customers_array, $check_customer['customers_group_id']);
print ("\n<tr>\n<td class=\"main\">&#160;<br />\n&#160;");
print ("<input type=\"hidden\" name=\"email_address\" value=\"".$_POST['email_address']."\">");
print ("<input type=\"hidden\" name=\"password\" value=\"".$_POST['password']."\">\n</td>\n</tr>\n<tr>\n<td align=\"right\">\n");
echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE);
print ("</td>\n</tr>\n</table>\n</form>\n</td>\n</tr>\n</table>\n</body>\n</html>\n");
exit;
}

        // BOF Separate Pricing per Customer
	if ($_GET['skip'] == 'true' && $_POST['email_address'] == SPPC_TOGGLE_LOGIN_PASSWORD && isset($_POST['new_customers_group_id']))  {
	$sppc_customer_group_id = $_POST['new_customers_group_id'] ;
	$check_customer_group_tax = tep_db_query("select customers_group_hidden_prices_msg, customers_group_show_tax, customers_group_show_prices, customers_group_tax_exempt from " . TABLE_CUSTOMERS_GROUPS . " where customers_group_id = '" .(int)$_POST['new_customers_group_id'] . "'");
	} else {
	$sppc_customer_group_id = $check_customer['customers_group_id'];
	$check_customer_group_tax = tep_db_query("select customers_group_hidden_prices_msg, customers_group_show_tax, customers_group_show_prices, customers_group_tax_exempt from " . TABLE_CUSTOMERS_GROUPS . " where customers_group_id = '" .(int)$check_customer['customers_group_id'] . "'");
	}
	$customer_group_tax = tep_db_fetch_array($check_customer_group_tax);
	
	$this->customer_group_show_tax=$sppc_customer_group_show_tax = (int)$customer_group_tax['customers_group_show_tax'];
	$this->customer_group_show_prices=$sppc_customer_group_show_prices = (int)$customer_group_tax['customers_group_show_prices'];
	$this->customer_group_hidden_prices_msg=$sppc_customer_group_hidden_prices_msg = (int)$customer_group_tax['customers_group_hidden_prices_msg'];
	$this->customer_group_tax_exempt=$sppc_customer_group_tax_exempt = (int)$customer_group_tax['customers_group_tax_exempt'];
	$this->customer_group_id=$sppc_customer_group_id;	
	// EOF Separate Pricing per Customer
    // BOF Separate Pricing per Customer
	tep_session_register('sppc_customer_group_id');
	tep_session_register('sppc_customer_group_show_tax');
	tep_session_register('sppc_customer_group_show_prices');
	tep_session_register('sppc_customer_group_hidden_prices_msg');
	tep_session_register('sppc_customer_group_tax_exempt');
	// EOF Separate Pricing per Customer
	
// EOF Separate Pricing Per Customer: choice for logging in under any customer_group_id
	}
	//	@function catalogLogoff
	//	@desc	Funzione innescata da pws_engine durante il logoff
	function catalogLogoff(){
		global $sppc_customer_group_id;
		global $sppc_customer_group_show_tax;
		global $sppc_customer_group_show_prices;
		global $sppc_customer_group_hidden_prices_msg;
		global $sppc_customer_group_tax_exempt;
		$this->customer_group_show_tax='1';
		$this->customer_group_show_prices='1';
		$this->customer_group_hidden_prices_msg='Occorre registrarsi per vedere i prezzi';
		$this->customer_group_tax_exempt='0';
		$this->customer_group_id='0';
		// BOF Separate Pricing per Customer
		tep_session_unregister('sppc_customer_group_id');
		tep_session_unregister('sppc_customer_group_show_tax');
		tep_session_unregister('sppc_customer_group_show_prices');
		tep_session_unregister('sppc_customer_group_hidden_prices_msg');
		tep_session_unregister('sppc_customer_group_tax_exempt');
		// EOF Separate Pricing per Customer
	}
	function catalogAddressBookUpdate(){
		global $firstname,$lastname,$company,$company_tax_id,$customer_id;
// BOF Separate Pricing Per Customer: alert shop owner of tax id number added to an account
      if (ACCOUNT_COMPANY == 'true' && tep_not_null($company_tax_id)) {
	      $sql_data_array2['customers_group_ra'] = '1';
      tep_db_perform(TABLE_CUSTOMERS, $sql_data_array2, 'update', "customers_id ='" . (int)$customer_id . "'");

      // if you would *not* like to have an email when a tax id number has been entered in
      // the appropriate field, comment out this section. The alert in admin is raised anyway

      $alert_email_text = "Please note that " . $firstname . " " . $lastname . " of the company: " . $company . " has added a tax id number to his account information.";
      tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, 'Tax id number added', $alert_email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
      }
// EOF Separate Pricing Per Customer: alert shop owner of account created by a company
	}
	//	@function install
	//	@desc	adatta le tables già presenti per la contrib di base
	function install(){
		if (!$this->_pws_engine->fieldExists('customers_group_discount',TABLE_PRODUCTS_GROUPS)){
			tep_db_query("alter table ".TABLE_PRODUCTS_GROUPS." add customers_group_discount decimal(6,2) NOT NULL default '0.00' after customers_group_id");
		}
		if (!$this->_pws_engine->fieldExists('customers_group_default_discount',TABLE_CUSTOMERS_GROUPS)){
			tep_db_query("alter table ".TABLE_CUSTOMERS_GROUPS." add customers_group_default_discount decimal(6,2) NOT NULL default '0.00' after customers_group_tax_exempt");
		}
		if (!$this->_pws_engine->fieldExists('customers_group_show_prices',TABLE_CUSTOMERS_GROUPS)){
			tep_db_query("alter table ".TABLE_CUSTOMERS_GROUPS." add column customers_group_show_prices enum('1','0') NOT NULL default '1' after customers_group_default_discount");
		}
		if (!$this->_pws_engine->fieldExists('customers_group_hidden_prices_msg',TABLE_CUSTOMERS_GROUPS)){
			tep_db_query("alter table ".TABLE_CUSTOMERS_GROUPS." add column customers_group_hidden_prices_msg varchar(255) NOT NULL default 'Occorre registrarsi per vedere i prezzi' after customers_group_show_prices");
		}
		// popola la table TABLE_PRODUCTS_GROUPS_STATUS
		$query=tep_db_query("select distinct products_id from ".TABLE_PRODUCTS);
		while ($product=tep_db_fetch_array($query)){
			$specials_query=tep_db_query("select products_id from ".TABLE_SPECIALS." where products_id=".$product['products_id']." and status='1'");
			if (tep_db_num_rows($specials_query)){
				$query2=tep_db_query("select products_id from ".TABLE_PRODUCTS_GROUPS_STATUS." where products_id=".$product['products_id']);
				if (!tep_db_num_rows($query2)){
					tep_db_query("insert into ".TABLE_PRODUCTS_GROUPS_STATUS." set products_id=".$product['products_id'].", pws_customers_groups_status='0'");
				}else{
					tep_db_query("update ".TABLE_PRODUCTS_GROUPS_STATUS." set pws_customers_groups_status='0' where products_id=".$product['products_id']);
				}
			}else{
				$query2=tep_db_query("select products_id from ".TABLE_PRODUCTS_GROUPS_STATUS." where products_id=".$product['products_id']);
				if (!tep_db_num_rows($query2)){
					tep_db_query("insert into ".TABLE_PRODUCTS_GROUPS_STATUS." set products_id=".$product['products_id'].", pws_customers_groups_status='1'");
				}else{
					tep_db_query("update ".TABLE_PRODUCTS_GROUPS_STATUS." set pws_customers_groups_status='1' where products_id=".$product['products_id']);
				}
			}
		}
		
		if ($this->_pws_engine->fieldExists('customers_group_price',TABLE_PRODUCTS_GROUPS)){
			// modifica la table TABLE_PRODUCTS_GROUPS, memorizzando i prezzi come sconti
			$query=tep_db_query("select * from ".TABLE_PRODUCTS_GROUPS);
			while($product=tep_db_fetch_array($query)){
				$productQuery=tep_db_query("select products_price from ".TABLE_PRODUCTS." where products_id=".$product['products_id']);
				$product2=tep_db_fetch_array($productQuery);
				$discount=100.0*(1.0-$product2['products_price']/$product['customers_group_price']);
				tep_db_query("update ".TABLE_PRODUCTS_GROUPS." set customers_group_discount='".$discount."' where products_id=".$product['products_id']." and customers_group_id=".$product['customers_group_id']);
			}
			tep_db_query("alter table ".TABLE_PRODUCTS_GROUPS." drop column customers_group_price");
		}
		
		return true;
	}
	//	@function update
	//	@desc	Funzione di aggiornamento del plugin
	//	@param	string $fromVersion		Vecchia versione
	function update($fromVersion)	{
		switch ($fromVersion){
			case '0.21':
			case '0.22':
			case '0.23':
				tep_db_query("alter table ".TABLE_PRODUCTS_GROUPS." modify column customers_group_discount decimal(6,2) NOT NULL default '0.00'");
				tep_db_query("alter table ".TABLE_CUSTOMERS_GROUPS." modify column customers_group_default_discount decimal(6,2) NOT NULL default '0.00'");
				if (!$this->_pws_engine->fieldExists('customers_group_show_prices',TABLE_CUSTOMERS_GROUPS)){
					tep_db_query("alter table ".TABLE_CUSTOMERS_GROUPS." add column customers_group_show_prices enum('1','0') NOT NULL default '1' after customers_group_default_discount");
				}
				if (!$this->_pws_engine->fieldExists('customers_group_hidden_prices_msg',TABLE_CUSTOMERS_GROUPS)){
					tep_db_query("alter table ".TABLE_CUSTOMERS_GROUPS." add column customers_group_hidden_prices_msg varchar(255) NOT NULL default 'Occorre registrarsi per vedere i prezzi' after customers_group_show_prices");
				}
 				break;
			default:
				break;
		}
	}
}
?>