<?php
/*
 * @filename:	pws_plugin_price.php
 * @version:	1.00
 * @project:	PWS
 *
 * @author:		Riccardo Roscilli <info@oscommerce.it>
 * @created:	16/mag/07
 * @modified:	16/mag/07 11:44:48
 *
 * @copyright:	2006-2007	Riccardo Roscilli @ PWS
 *
 * @desc:	
 *
 * @TODO:		
 */

class pws_plugin_price	extends pws_plugin	{
	// Variabili di configurazione del plugin
	// @TODO: implementare l'esclusione automatica dei plugin conflittuali da parte di pws_prices,
	//	levandola dai singoli plugins.
	var $plugin_taxes_exempt=false;		// I prezzi del plugin sono soggetti a tasse
	var	$plugin_excludes=array();		// Contiene i plugin_code dei plugin che vanno esclusi quando viene attivato questo plugin
	var $plugin_js_disable='';			// Codice js per escludere questo plugin, su richiesta di altri plugins

	// Variabili pubbliche del plugin_price
	// Variabili usate in admin durante l'editing dei prezzi
	var $varname_products_price;		// (può essere un array) Viene impostata con il nome del textfield contenente il prezzo netto da usare per preview
	var $varname_products_price_gross;	// (può essere un array) Viene impostata con il nome del textfield contenente il prezzo lordo da usare per preview
	var $varname_products_price_to;		// (può essere un array) Deve essere impostato dal plugin con il nome dell'input field contenente il nuovo prezzo netto
	var $varname_products_price_gross_to;		// (può essere un array) Deve essere impostato dal plugin con il nome dell'input field contenente il nuovo prezzo lordo
	var $js_edit_product_init='';		// Codice javascript da lanciare a caricamento ultimato della pagina
	var $js_edit_product_init_pre='';		// Codice javascript da lanciare prima del caricamento della pagina
	var $price_editing_fields_chain=array();	//	Array contenente i nomi dei campi da tenere sotto osservazione per la modifica dei prezzi

	function pws_plugin_price(&$pws_engine){
		parent::pws_plugin(&$pws_engine);
		$this->plugin_type='prices';
		array_push($this->plugin_needs,'pws_prices');
	}
	//	@function catalogJavascript
	//	@desc	Restituisce il codice javascript utilizzato in products_listings di catalog
	function	catalogJavascript(){
		return '';
	}
	//	@function catalogProductInfoJavascript
	//	@desc	Restituisce il codice javascript utilizzato in product_info.php di catalog
	function	catalogProductInfoJavascript($products_id,&$products_price){
		return '';
	}
	//	@function catalogStylesheet
	//	@desc	Restituisce il codice css utilizzato nel front end da tutti i plugin prezzi
	function	catalogStylesheet(){
		return '';
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
		$varname_products_price=$this->varname_products_price_to;
		$varname_products_price_gross=$this->varname_products_price_gross_to;
		$this->price_editing_fields_chain=$fields_chain;
		$fields_chain[]=$this->varname_products_price_to;
		$fields_chain[]=$this->varname_products_price_gross_to;
	}
	//	@function	getStatus
	//	@desc	Restituisce true se il plugin applica qualche modifica al prezzo
	//	@param	int	$products_id
	function	getStatus($products_id){
		$discount_status=true;
		reset($this->plugin_excludes);
		foreach($this->plugin_excludes as $plugin_code){
			$plugin=$this->_pws_engine->getPlugin($plugin_code,'prices');
			if ($plugin!=NULL){
				$discount_status=$discount_status && !$plugin->getStatus($products_id);
			}
		}
		return $discount_status;
	}
	//	@function adminNewProductSetDefault
	//	@desc		Crea i valori di default relativi ad un plugin prezzo, per un nuovo prodotto
	function adminNewProductSetDefault($products_id){
		return true;
	}
	//	@function adminNewProduct
	//	@desc		Crea nella struttura pInfo i propri dati relativi al prodotto
	function adminNewProduct(&$pInfo){
		return true;
	}
	//	@function adminLoadProduct
	//	@desc		Carica nella struttura pInfo i propri dati relativi al prodotto
	function adminLoadProduct(&$pInfo){
		return true;
	}
	//	@function adminJavascript
	//	@desc		Restituisce il codice javascript utilizzato nell'editing dei parametri
	function adminJavascript(&$pInfo){
		return '';
	}
	//	@function adminStylesheet
	//	@desc		Restituisce il codice css utilizzato nell'editing dei parametri
	function adminStylesheet(&$pInfo){
		return '';
	}
	//	@function adminEditProduct
	//	@desc		Crea il codice html per l'editing del prodotto, nella sezione admin
	function adminEditProduct($products_id,&$pInfo){
		return '';
	}
	//	@function adminUpdateProduct
	//	@desc		Salva i parametri editati per il prodotto, nella sezione admin
	function adminUpdateProduct($products_id){
		return true;
	}
	//	@function adminDisplayProduct
	//	@desc		Visualizza i parametri relativi ad un prodotto, nella sezione admin
	function adminDisplayProduct($products_id){
		return '';
	}
	//	@function adminDeleteProduct
	//	@desc		Elimina i dati memorizzati relativamente ad un prodotto
	function adminDeleteProduct($products_id){
		return true;
	}
	//	@function	adminCopyProduct
	//	@desc		Duplica i dati relativi ad un prodotto
	function adminCopyProduct($products_id,$dup_products_id){
		return true;
	}

	//	@function	adminDisableOnProduct
	//	@param		int		$products_id		Id del prodotto su cui disabilitare eventuali offerte
	//	@return		bool						Restituisce true se è stato disabilitato uno sconto
	function adminDisableOnProduct($products_id){
		return false;
	}
	//	@function	statusOnProduct
	//	@param		int		$products_id		Id del prodotto su cui è richiesto lo stato del plugin
	//	@return 	bool				Stato del plugin
	function statusOnProduct($products_id){
		return false;
	}
	//	@function	getDiscountPercentage
	//	@desc		Restituisce la percentuale di sconto presente su un prodotto
	//	@param		int		$products_id		Id del prodotto per cui calcolare la percentuale di scontoo
	//	@param		int		$products_quantity	Numero colli
	//	@param		int		$customers_id		Id del cliente per cui generare la percentuale di sconto
	//	@return		float						la percentuale di sconto
	function getDiscountPercentage($products_id, $products_quantity=1, $customers_id=NULL)	{
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
		return $products_price;
	}
	//	@function	getBestPrice
	//	@desc		Elabora il prezzo successivo migliore visualizzabile al cliente
	//	@param		int		$products_id		Id del prodotto per cui calcolare il prezzo successivo
	//	@param		float	$products_price		Prezzo	precedente del prodotto
	//	@return		float						Prezzo successivo del prodotto
	function getBestPrice($products_id, $products_price)	{
		return $products_price;
	}
	//	@function	getHtmlPriceDiscounts
	//	@desc		Restituisce il codice html contenente tutti gli sconti disponibili sul prodotto
	//	@param		int		$products_id		Id del prodotto per cui calcolare il prezzo successivo
	//	@param		float	$products_price		Prezzo	precedente del prodotto
	//	@return		array('text'=>string,'discount_perc'=>float,['content'=>string]) Codice html che visualizza le opzioni prezzo possibili per questo prodotto
	function getHtmlPriceDiscounts($products_id, &$products_price)	{
		return NULL;
	}
	//	@function	getHtmlPriceResume
	//	@desc		Modifica il prezzo e restituisce il codice html contenente la descrizione dello sconto applicato sul prodotto
	//	@param		int		$products_id		Id del prodotto per cui calcolare il prezzo successivo
	//	@param		float	$products_price		Prezzo	precedente del prodotto
	//	@param		int		$products_quantity	Numero colli
	//	@param		int		$customers_id		Id del cliente per cui generare il prezzo
	//	@param		bool	$with_taxes			Per forzare la presentazione del prezzo con o senza tasse incluse
	//	@return		array('text'=>string,'discount_perc'=>float,['content'=>string]) Codice html che visualizza le opzioni prezzo possibili per questo prodotto
	function getHtmlPriceResume($products_id, &$products_price, $products_quantity=1, $customers_id=NULL, $with_taxes=true)	{
		return NULL;
	}
	//	@function	getHtmlDiscountInfo
	//	@desc		Restituisce un avviso (in html) per la presenza di uno sconto (se c'è) sul prodotto passato come parametro
	//	@param		int		$products_id		Id del prodotto per cui generare l'avviso
	//	@param		float	$products_price		Prezzo	precedente del prodotto
	//	@return		string					Codice html
	function	getHtmlDiscountInfo($products_id,&$products_price){
		return '';
	}
	
	function	displayPriceWithTaxes(){
		global $customer_company_cf, $customer_country_id, $sppc_customer_group_show_tax, $sppc_customer_group_tax_exempt;

		// verifico se il plugin gruppi � installato o meno
		$cg_plungin_query = tep_db_query("select * from pws_plugins where plugin_code = 'pws_prices_customers_groups'");

		if(tep_db_num_rows($cg_plungin_query) >= 1)
			$customer_groups_installed = true;
		else 	
			$customer_groups_installed = false;
	/*		
		print "gruppo e partita iva del cliente: ";
		print $sppc_customer_group_show_tax. "<br>";
		print $customer_company_cf;
		print "Mostra tassa per il guppo: " . $sppc_customer_group_show_tax. "<br>";
		print "Iva Esente: ". $sppc_customer_group_tax_exempt. "<br>";
		print DISPLAY_PRICE_WITH_TAX;
		*/
	// se � installato il plugin gruppi faccio riferimento alle impostazioni di gruppo, altrimenti a quelle della configurazione
	if ($customer_groups_installed == true && isset($sppc_customer_group_show_tax))
	  {
	 
		if ($sppc_customer_group_show_tax == '0' || $sppc_customer_group_tax_exempt == '1' ) 
				// se il gruppo di appartenenza � esente o impostato con tasse escluse
			return false;
		else
			{
			 return true;
			}
		
		}
	 else  // segue le impostazioni della configurazione
		{
				
		 if(DISPLAY_PRICE_WITH_TAX == 'false')
		 	return false;
		 else 
		 	return true;	
		}

	}

}
?>