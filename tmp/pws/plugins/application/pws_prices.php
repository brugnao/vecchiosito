<?php
/*
 * @filename:	prices.php
 * @version:	1.00
 * @revision:	751
 * @project:	PWS
 *
 * @author:		Riccardo Roscilli info@oscommerce.it
 * @created:	07/mag/07
 * @modified:	07/mag/07 16:06:24
 *
 * @copyright:	2006-2007	Riccardo Roscilli (PWS)
 *
 * @desc:	
 *
 * @TODO:		
 */


class	pws_prices	extends pws_plugin {
	var $admin_side;	// Boolean: true se il plugin sta girando dal lato amministrazione del sito
	var $show_prices=true;	// Boolean: se false i prezzi non vengono mai mostrati, ma viene mostrato il messaggio
	var $show_hidden_prices_msg='';	//Messaggio mostrato al posto dei prezzi se show_prices=false
	// Configurazione del plugin
	var $plugin_type='application';			// Tipo del plugin
	var $plugin_code='pws_prices';			// Codice univoco del plugin ( deve coincidere con il nome del file )
	var $plugin_configurable=false;	// Plugin di tipo configurabile ? (se true, comparirà nella lista dei moduli di tipo "sistema")
	var	$plugin_name=TEXT_PWS_PRICES_NAME;			// Nome del plugin
	var $plugin_description=TEXT_PWS_PRICES_DESCRIPTION;	// Descrizione del plugin
	var $plugin_version_const='0.2';	// Versione del plugin
	var $plugin_needs=array('pws_engine','pws_products_selector');	// Array di codici di plugin richiesti da questo plugin
	var $plugin_conflicts=array();	// Array di codici di plugin incompatibili con questo plugin
	var $plugin_configKeys=array();	// Chiavi di configurazione del plugin, in forma chiave=>array(field1=>value1, ..., fieldn=>valuen)
	var $plugin_tables=array();	// Tables utilizzate dal plugin
	var	$plugin_editPage='';	// Indirizzo della pagina da aprire per l'editing delle impostazioni. Se vuoto le impostazioni vengono modificate nella paginata dei plugins
	var $plugin_sql_install='';	// Codice sql da applicare in fase di installazione del plugin
	var $plugin_sql_remove='';	// Codice sql da applicare in fase di rimozione del plugin
	var $plugin_sort_order=-1;		// Ordine del plugin.
	var $plugin_removable=false;	// Questo plugin non può essere rimosso e viene installato di default	
//	Istanze dei vari plugin
	var	$plugins_prices=array();
	var $plugin_quantities=NULL;	// @TODO: soluzione sporca. Riferimento diretto a pws_prices_quantities per potere visualizzare i prezzi come: "a partire da...". Risolvere generalizzando per plugins prezzi che restituiscono diversi prezzi in base a qualsiasi meccanismo di calcolo sconti
	var $plugin_groups=NULL;		// @TODO: soluzione sporca. Idem come sopra per pws_prices_customers_groups
	var $plugin_purchase=NULL;		// @TODO: soluzione sporca. Idem come sopra per pws_prices_customers_groups

	// Gestione dell'editing dei prezzi lato admin
	var $varname_products_price='products_price';		// @desc: Contiene il nome della variabile javascript che contiene il prezzo elaborato dal plugin precedente
	var $varname_products_price_gross='products_price_net';	// @desc: Contiene il nome della variabile javascript che contiene il prezzo (iva inclusa) elaborato dal plugin precedente
	var $funcname_products_price_changed;	// @desc: Contiene il nome della funzione da chiamare quando varia il prezzo
	function	pws_prices(&$pws_engine)	{
		parent::pws_plugin(&$pws_engine);
	}
	function	init(){
		parent::init();
		$this->admin_side=$this->_pws_engine->isAdminSideRunning();
	}
	//	@function	setPluginsPrices
	//	@desc	Funzione invocata da pws_engine per impostare i plugins prezzi
	//	@param	array	$plugin_prices		Array dei plugins prezzi
	//	@TODO:	Non è granchè come soluzione....
	function	setPluginsPrices(&$plugin_prices){
		$this->plugins_prices=&$plugin_prices;
		if (is_array($plugin_prices)){
			reset($this->plugins_prices);
			foreach ($this->plugins_prices as $i=>$plugin){
				switch($plugin->plugin_code){
					case 'pws_prices_quantities':
						$this->plugin_quantities=&$this->plugins_prices[$i];
						break;
					case 'pws_prices_customers_groups':
						$this->plugin_groups=&$this->plugins_prices[$i];
						$this->show_prices=$this->_pws_engine->isAdminSideRunning() || $this->plugin_groups->displayPrices();
						$this->show_hidden_prices_msg=$this->plugin_groups->getHiddenPricesMessage();
						break;
					case 'pws_prices_purchase_price':
						$this->plugin_purchase=&$this->plugins_prices[$i];
						break;
					default:
						break;
				}
			}
		}
	}

	//	@function	displayPriceWithTaxes()
	//	@desc		Restituisce un flag che rappresenta l'istruzione di visualizzare i prezzi tasse incluse
	//	@return		bool
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
		print "Iva Esente: ". $sppc_customer_group_tax_exempt;
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
	
	
	//	@function	getFirstPrice
	//	@desc		Elabora il prezzo di partenza (listino) di un prodotto
	//	@param		int		$products_id		Id del prodotto per cui calcolare il prezzo
	//	@param		int		$customers_id		Id del cliente per cui generare il prezzo
	//	@param		boolean	$purchased_price	Forza la restituzione del prezzo di listino (anche in presenza del plugin purchased_price)
	//	@return		float						prezzo di partenza (listino) del prodotto
	function	getFirstPrice($products_id, $customers_id=NULL, $purchased_price=false){
		$products_id=tep_get_prid($products_id);
		if (!is_null($this->plugin_purchase) && !$this->admin_side && !$purchased_price)
			return $this->plugin_purchase->getFirstPrice($products_id, $customers_id);
		else{
			$productsPriceQuery=tep_db_query("select products_price from ".TABLE_PRODUCTS." where products_id='$products_id'");
			if ($productPrice=tep_db_fetch_array($productsPriceQuery))
				return $productPrice['products_price'];
			else
				return NULL;
		}
	}
	//	@function	getLastPrice
	//	@desc		Elabora il prezzo finale del prodotto
	//	@param		int		$products_id		Id del prodotto per cui calcolare il prezzo finale
	//	@param		int		$products_quantity	Numero colli
	//	@param		array	$attributes			[Opzionale] Eventuali attributi presenti per il prodotto
	//	@param		int		$customers_id		Id del cliente per cui generare il prezzo
	//	@return		float						Prezzo finale del prodotto
	function	getLastPrice($products_id, $products_quantity=1, $attributes=NULL, $customers_id=NULL){
		$products_id=tep_get_prid($products_id);
		$products_price=$this->getFirstPrice($products_id);
		for($i=0;$i<sizeof($this->plugins_prices);$i++){
			$plugin=&$this->plugins_prices[$i];
			$products_price=$plugin->getNextPrice($products_id,$products_price,$products_quantity);
		}
		if (is_array($attributes)){
			reset($attributes);
			while (list($option, $value) = each($attributes)) {
				$attributes = tep_db_query("select pa.options_values_price, pa.price_prefix
					from " . TABLE_PRODUCTS_ATTRIBUTES . " pa
					where pa.products_id = '" . $products_id . "'
					 and pa.options_id = '" . $option . "'
					 and pa.options_values_id = '" . $value . "'");
				$attributes_values = tep_db_fetch_array($attributes);
				if ($attributes_values['price_prefix'] == '+') {
					$products_price += $attributes_values['options_values_price'];
				} else {
					$products_price -= $attributes_values['options_values_price'];
				}
			}
		}
		return $products_price;
	}
	//	@function	getBestPrice
	//	@desc		Elabora il miglior prezzo finale possibile del prodotto per il cliente attuale
	//	@param		int		$products_id		Id del prodotto per cui calcolare il prezzo finale
	//	@param		int		$products_quantity	Numero colli
	//	@param		int		$customers_id		Id del cliente per cui generare il prezzo
	//	@return		float						Prezzo finale del prodotto
	function	getBestPrice($products_id, $customers_id=NULL){
		$products_id=tep_get_prid($products_id);
		$products_price=$this->getFirstPrice($products_id);
		for($i=0;$i<sizeof($this->plugins_prices);$i++){
			$plugin=&$this->plugins_prices[$i]; 
			$products_price=$plugin->getBestPrice($products_id,&$products_price);
		}
		return $products_price;
	}

	//	@function catalogStylesheet
	//	@desc	Restituisce il codice css utilizzato nel front end da tutti i plugin prezzi
	function	catalogStylesheet(){
		//$styles='';
		$styles='<link rel="stylesheet" type="text/css" href="'.DIR_WS_PWS_STYLESHEETS.'pws_prices_catalog.css'.'"/>'."\r\n";
		for($i=0;$i<sizeof($this->plugins_prices);$i++)
			$styles.=$this->plugins_prices[$i]->catalogStylesheet();
		//$styles.=@file_get_contents(DIR_FS_PWS_STYLESHEETS.'pws_prices_catalog.css');
//		die($styles);
		return $styles;
	}
	//	@function catalogJavascript
	//	@desc	Restituisce il codice javascript utilizzato in product_info.php di catalog
	function	catalogJavascript(){
		$output='';
		for($i=0;$i<sizeof($this->plugins_prices);$i++){
			$output.=$this->plugins_prices[$i]->catalogJavascript();
		}
		return $output;
	}
	//	@function catalogProductInfoJavascript
	//	@desc	Restituisce il codice javascript utilizzato in product_info.php di catalog
	function	catalogProductInfoJavascript($products_id){
		$output='';
		for($i=0;$i<sizeof($this->plugins_prices);$i++){
			$old_price=$products_price;
			$output.=$this->plugins_prices[$i]->catalogProductInfoJavascript($products_id,&$products_price);
		}
		return $output;
	}
	//	@function	getHtmlPriceResume
	//	@desc		Elabora il prezzo finale del prodotto, e restituisce la descrizione delle elaborazioni
	//	@desc		effettuate
	//	@param		int		$products_id		Id del prodotto per cui calcolare il prezzo finale
	//	@param		int		$products_quantity	Numero colli
	//	@param		array	$attributes			Array degli attributi selezionati per il prodotto
	//	@param		bool	$with_taxes			[opzionale] Se true visualizza i prezzi con le tasse
	//	@param		int		$customers_id		Id del cliente per cui generare il prezzo
	//	@return		array(mixed)				Ogni elemento dell'array è un array associativo contenente i dettagli di ogni passaggio
	function	getHtmlPriceResume($products_id, $products_quantity=1, $attributes=NULL, $with_taxes=NULL, $customers_id=NULL){
		global $languages_id,$currencies;
		$products_id=tep_get_prid($products_id);
		$details=array();
		$extra_taxes=array();
		if (is_null($this->plugin_purchase) || $this->admin_side){
			$products_price=$this->getFirstPrice($products_id,NULL,true);
			$details[]=array(
					'text'=>TEXT_PWS_PRICES_DEFAULT
					,'products_price'=>$products_price
					,'content'=>$this->formatPrice($products_price,$products_id,$with_taxes)
			);
		}else{
			$products_price=$this->getFirstPrice($products_id);
		}
		for($i=0;$i<sizeof($this->plugins_prices);$i++){
			$old_price=$products_price;
			$det=$this->plugins_prices[$i]->getHtmlPriceResume($products_id,&$products_price,$products_quantity,$customers_id,$with_taxes);
			if (!is_null($det)){
				if (isset($det['tax_exempt']) && $det['tax_exempt']==true){
					$extra_taxes[]=$det;
				}else{
					if (isset($det['admin_text']) && !isset($det['admin_content'])){
						if ($det['discount_perc']!=0.0)
							$det['admin_content']='(-&nbsp;'.$this->formatPercentage($det['discount_perc']).')&nbsp;&nbsp;';
						$det['admin_content'].=$this->formatPrice(abs($old_price-$products_price),$products_id,$with_taxes);
					}
					if (isset($det['text']) && !isset($det['content'])){
						if ($det['discount_perc']!=0.0)
							$det['content']='(-&nbsp;'.$this->formatPercentage($det['discount_perc']).')&nbsp;&nbsp;';
						$det['content'].=$this->formatPrice(abs($old_price-$products_price),$products_id,$with_taxes);
					}
					$details[]=$det;
				}
			}
		/*	print $this->plugins_prices[$i]->plugin_name ;
			print_r($det);
			print "<br>";
			*/
			// modifica per mostrare listino secco ai gruppi senza prezzo barrato, sconto, prezzo netto e altre minchiate
			// seconda revisione questo pezzo di codice lo lascio per tutti i gruppi e per tutti gli sconti
			if ($this->plugins_prices[$i]->plugin_name == 'Gruppi Cliente' &&  $det['discount_perc']!=0.0 && SHOW_GROUP_NET_PRICES == 'true')
			{
				
				unset($details);
				//$details[0]['text'] = TEXT_PWS_PRICES_DEFAULT; 
				$details[0]['text'] = ''; 
				$details[0]['content'] = $this->formatPrice($products_price,$products_id) * $products_quantity;

			}
		}
		if (is_array($attributes)){
			reset($attributes);
			foreach ($attributes as $option=>$value){
				$attributes_query = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix
					from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
					where pa.products_id = '" . $products_id . "'
					 and pa.options_id = '" . $option . "'
					 and pa.options_id = popt.products_options_id
					 and pa.options_values_id = '" . $value . "'
					 and pa.options_values_id = poval.products_options_values_id
					 and popt.language_id = '" . $languages_id . "'
					 and poval.language_id = '" . $languages_id . "'");
				$attributes_values = tep_db_fetch_array($attributes_query);
				if ($attributes_values['price_prefix'] == '+') {
					$products_price += $attributes_values['options_values_price'];
				} else {
					$products_price -= $attributes_values['options_values_price'];
				}
				if ($attributes_values['options_values_price']!=0.00){
					$details[]=array('text'=>$attributes_values['products_options_name'].' '.$attributes_values['products_options_values_name'],
									'content'=>($attributes_values['price_prefix']=='-'?'-':'').$this->formatPrice($attributes_values['options_values_price'],$products_id,$with_taxes));
				}
			}
		}
		// print $products_price;
		/*
		$details[]=array('text'=>TEXT_PWS_PRICES_FINAL
				,'quantity'=>$products_quantity
				,'products_price'=>$products_price
			
			//	,'content'=>"$products_quantity x ".$this->formatPrice($products_price,$products_id,$with_taxes)
			    ,'content'=> $products_quantity * $this->formatPrice($products_price,$products_id,$with_taxes)
		);
*/
		
		// mostra solo il prezzo senza tabelle etc. moltiplicato per la quantità acquistata
		// dovrebbe includere anche il valore delle opzioni
		$details[]=array('text'=>' '
				,'quantity'=>$products_quantity
				,'products_price'=>$products_price
			
			//	,'content'=>"$products_quantity x ".$this->formatPrice($products_price,$products_id,$with_taxes)
			    ,'content'=> $products_quantity * $this->formatPrice($products_price,$products_id,$with_taxes)
		);		
		
		
		
		$additional_taxes=array();
		$products_price_tax_exempt=0.0;
		
		while (sizeof($extra_taxes)){
			$extra_tax=array_shift($extra_taxes);
			if ($extra_tax['add_to_sum']==true){
				$products_price_tax_exempt+=$extra_tax['amount'];
				$details[]=$extra_tax;
			}else{
				$additional_taxes[]=$extra_tax;
			}
		}
		// mostra solo il prezzo senza tabelle etc. moltiplicato per la quantità acquistata

				unset($details);
				//$details[0]['text'] = TEXT_PWS_PRICES_DEFAULT; 
				$details[0]['text'] = ''; 
				$details[0]['content'] = $this->formatPrice($products_price,$products_id) * $products_quantity;
		


		$skin=new pws_skin('pws_prices_resume.htm');
		$skin->set('pdetails',$details);
		$skin->set('admin_side',$this->admin_side);
		$skin->set('total',$currencies->display_price($this->calculatePrice($products_price*$products_quantity,$products_price_tax_exempt*$products_quantity,$products_id,$with_taxes),0));
		$skin->set('additional_taxes',$additional_taxes);
		return $skin->execute();

//		return $prezzo_per_quantita;
	}
	//	@function	getPriceResume
	//	@desc		Elabora il prezzo finale del prodotto, e restituisce la descrizione delle elaborazioni sia in html che in dati
	//	@desc		effettuate
	//	@param		int		$products_id		Id del prodotto per cui calcolare il prezzo finale
	//	@param		int		$products_quantity	Numero colli
	//	@param		array	$attributes			Array degli attributi selezionati per il prodotto
	//	@param		bool	$with_taxes			[opzionale] Se true visualizza i prezzi con le tasse
	//	@param		int		$customers_id		Id del cliente per cui generare il prezzo
	//	@return		array(mixed)				Contiene il risultato di getHtmlPriceResume sotto la chiave 'html', ed i dati sotto la chiave 'data'.
	function	getPriceResume($products_id, $products_quantity=1, $attributes=NULL, $with_taxes=NULL, $customers_id=NULL){
		global $languages_id,$currencies;
		$products_id=tep_get_prid($products_id);
		$data=array();
		$products_price=$this->getFirstPrice($products_id,NULL,true);
		if (is_null($this->plugin_purchase)){
			$details[]=array(
					'text'=>TEXT_PWS_PRICES_DEFAULT
					,'products_price'=>$products_price
					,'content'=>$this->formatPrice($products_price,$products_id,$with_taxes)
			);
		}else{
			$details[]=array(
					'text'=>''
					,'content'=>''
					,'admin_text'=>TEXT_PWS_PRICES_DEFAULT
					,'products_price'=>$products_price
					,'admin_content'=>$this->formatPrice($products_price,$products_id,$with_taxes)
			);
		}
		$extra_taxes=array();
		for($i=0;$i<sizeof($this->plugins_prices);$i++){
			$old_price=$products_price;
			$det=$this->plugins_prices[$i]->getHtmlPriceResume($products_id,&$products_price,$products_quantity,$customers_id);
			if (!is_null($det)){
				if (isset($det['tax_exempt']) && $det['tax_exempt']==true){
					$extra_taxes[]=$det;
				}else{
					if (isset($det['admin_text']) && !isset($det['admin_content'])){
						if ($det['discount_perc']!=0.0)
							$det['admin_content']='(-&nbsp;'.$this->formatPercentage($det['discount_perc']).')&nbsp;&nbsp;';
						$det['admin_content'].=$this->formatPrice(abs($old_price-$products_price),$products_id,$with_taxes);
					}
					if (isset($det['text']) && !isset($det['content'])){
						if ($det['discount_perc']!=0.0)
							$det['content']='(-&nbsp;'.$this->formatPercentage($det['discount_perc']).')&nbsp;&nbsp;';
						$det['content'].=$this->formatPrice(abs($old_price-$products_price),$products_id,$with_taxes);
					}
					if (isset($det['admin_content'])){
						$det['admin_content']=$det['admin_content']=='' ? '('.$this->formatPercentage($det['discount_perc']).')&nbsp;+'.$this->formatPrice($products_price-$old_price,$products_id,$with_taxes):$det['admin_content'];
					}else{
						$det['admin_content']='';
					}
					$details[]=$det;
				}
			}
			// modifica per mostrare listino secco ai gruppi senza prezzo barrato, sconto, prezzo netto anche nelle email
			if ($this->plugins_prices[$i]->plugin_name == 'Gruppi Cliente' &&  $det['discount_perc']!=0.0 && SHOW_GROUP_NET_PRICES == 'true')
			{
				unset($details);
				$details[0]['text'] = TEXT_PWS_PRICES_DEFAULT; 
				$details[0]['content'] = $this->formatPrice($products_price,$products_id);

			}
				
		}
		if (is_array($attributes)){
			reset($attributes);
			foreach ($attributes as $option=>$value){
				$attributes_query = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix
					from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
					where pa.products_id = '" . $products_id . "'
					 and pa.options_id = '" . $option . "'
					 and pa.options_id = popt.products_options_id
					 and pa.options_values_id = '" . $value . "'
					 and pa.options_values_id = poval.products_options_values_id
					 and popt.language_id = '" . $languages_id . "'
					 and poval.language_id = '" . $languages_id . "'");
				$attributes_values = tep_db_fetch_array($attributes_query);
				if ($attributes_values['price_prefix'] == '+') {
					$products_price += $attributes_values['options_values_price'];
				} else {
					$products_price -= $attributes_values['options_values_price'];
				}
				if ($attributes_values['options_values_price']!=0.00){
					$details[]=array('text'=>$attributes_values['products_options_name'].' '.$attributes_values['products_options_values_name'],
									'content'=>($attributes_values['price_prefix']=='-'?'-':'').$this->formatPrice($attributes_values['options_values_price'],$products_id,$with_taxes));
				}
			}
		}
		$det=array(
				'text'=>TEXT_PWS_PRICES_FINAL
				,'quantity'=>$products_quantity
				,'products_price'=>$products_price
				,'total_price'=>$products_price*$products_quantity
				,'content'=>"$products_quantity x ".$this->formatPrice($products_price,$products_id,$with_taxes)
					);
		if (false && $this->displayPriceWithTaxes()==false){
			$det['content'] .= ' ('.$this->formatPrice($products_price,$products_id,true).')';
		}
		$details[]=$det;
		$additional_taxes=array();
		$products_price_tax_exempt=0.0;
		while (sizeof($extra_taxes)){
			$extra_tax=array_shift($extra_taxes);
			if ($extra_tax['add_to_sum']){
				$products_price_tax_exempt+=$extra_tax['amount'];
				$details[]=$extra_tax;
			}else{
				$additional_taxes[]=$extra_tax;
			}
		}
		$skin=new pws_skin('pws_prices_resume.htm');
		$skin->set('admin_side',$this->admin_side);
		$skin->set('pdetails',$details);
		$skin->set('total',$currencies->display_price($this->calculatePrice($products_price*$products_quantity,$products_price_tax_exempt*$products_quantity,$products_id,$with_taxes),0));
		$skin->set('additional_taxes',$additional_taxes);
		//$skin->set('total',$this->formatPrice($products_price*$products_quantity,$products_id,$with_taxes));
		$html=$skin->execute();
		$result=array('html'=>$html,'data'=>array_merge($details,array('additional_taxes'=>$additional_taxes,'total'=>$currencies->display_price($this->calculatePrice($products_price*$products_quantity,$products_price_tax_exempt*$products_quantity,$products_id,$with_taxes),0))));
		return serialize($result);
	}
	//	@function	restorePriceResume
	//	@desc		Ricostruisce il riassunto dell'ordine dai dati serializzati
	//	@param		array	$data				Dati serializzati da getPriceResume
	//	@returns	string	html code
	function	restorePriceResume($data){
		$total=isset($data['total'])?$data['total']:'';
		$additional_taxes=isset($data['additional_taxes']) ? $data['additional_taxes'] : array();
		unset($data['total']);
		unset($data['additional_taxes']);
		$skin=new pws_skin('pws_prices_resume.htm');
		$skin->set('admin_side',$this->admin_side);
		$skin->set('pdetails',$data);
		$skin->set('total',$total);
		$skin->set('additional_taxes',$additional_taxes);
		return $skin->execute();
	}	
	//  @function	formatTextPriceResume
	//	@desc		Formatta in testo il riassunto dei dettagli sul prezzo passato come parametro
	//	@param		array(misc)		$resume			Array('html'=>...,'data'=>...) E' l'output della funzione getPriceResume
	//	@return		string			Il dettaglio del prezzo prodotto, formattato in versione testuale.
	function	formatTextPriceResume($resume){
		static $indents=array(20,40,77);	// Indentazione delle colonne
		$eoln="\r\n";
		$data=$resume['data'];
	/*
	 dump array $resume
Array
(
    [html] => 
<table class="pws_prices_resume_table" id="pws_prices_resume_table">
	<tr>
		<td nowrap="nowrap">Prezzo</td>
		<td nowrap="nowrap" align="right">30.29EUR</td>
	</tr>		
	<tr>
		<td nowrap="nowrap">Prezzo unit. finale</td>
		<td nowrap="nowrap" align="right">1 x 30.29EUR</td>

	</tr>	
<tr>
	<td colspan="2" style="border-top:1px solid black" align="right">30.29EUR</td>
</tr>
</table>

    [data] => Array
        (
            [0] => Array
                (
                    [text] => Prezzo
                    [content] => 30.29EUR
                )

            [1] => Array
                (
                    [text] => Prezzo unit. finale
                    [quantity] => 1
                    [products_price] => 30.2899
                    [total_price] => 30.2899
                    [content] => 1 x 30.29EUR
                )

            [additional_taxes] => Array
                (
                )

            [total] => 30.29EUR
        )

)

	 */
		$total=isset($data['total'])?$data['total']:'';
		$additional_taxes=isset($data['additional_taxes']) ? $data['additional_taxes'] : array();
		unset($data['total']);
		unset($data['additional_taxes']);
		$output='';
		$filter=array();
		for ($i=0;$i<sizeof($data);$i++){
			if(is_array($data[$i]) 
				&& $data[$i]['text']!=''
				&& $data[$i]['content']!='')
				$filter[]=$data[$i];
		}
		$data=$filter;
	
  /*
  Array $filter
(
    [0] => Array
        (
            [text] => Prezzo
            [products_price] => 35.9900
            [content] => 35.99EUR
        )

    [1] => Array
        (
            [text] => Sconto speciale
            [products_price] => 32.394599
            [discount_perc] => 9.99
            [tax_exempt] => 
            [content] => (-&nbsp;9.99&nbsp;%)&nbsp;&nbsp;3.60EUR
            [admin_content] => 
        )

    [2] => Array
        (
            [text] => Prezzo unit. finale
            [quantity] => 1
            [products_price] => 32.394599
            [total_price] => 32.394599
            [content] => 1 x 32.39EUR
        )

)*/
		
		for ($i=0,$n=sizeof($data);$i<$n;$i++){
			$output.=$this->inscribeText(array(html_entity_decode($data[$i]['text']),html_entity_decode($data[$i]['content'])),$indents,array('left','right')).$eoln;

		 // pesco il prezzo unitario finale
		// $prezzo_finale = TEXT_PWS_PRICES_DEFAULT . ' ' . $this->formatPrice( $data[$i]['products_price']);
		$prezzo_finale = $this->formatPrice( $data[$i]['products_price']);
		}
		$output.=$this->inscribeText(array('','--------------'),$indents,array('left','right')).$eoln;
		$i=sizeof($data)-1;
		$output.=$this->inscribeText(array('',$total),$indents,array('left','right')).$eoln;
		
		// prezzo unitario secco senza troppi casini già scontato 
		// 
 

		 return $prezzo_finale;
		
	}
	//	@function	getHtmlPriceDiscounts
	//	@desc		Restituisce il codice html contenente tutti gli sconti disponibili sul prodotto
	//	@param		int		$products_id		Id del prodotto per cui calcolare il prezzo successivo
	//	@param		float	$products_price		Prezzo	precedente del prodotto
	//	@return		string
	function getHtmlPriceDiscounts($products_id)	{

		// blocco la visualizzazione se i prezzi sono nascosti
		if (!$this->show_prices)
			return $this->show_hidden_prices_msg;
		
		// blocco visualizzazione prezzo prodotto soloin v	
		$onlyshow_qy = tep_db_query("select products_onlyshow from products where products_id = '".$products_id."'" );
		$onlyshow_ay = tep_db_fetch_array($onlyshow_qy);
		if($onlyshow_ay['products_onlyshow'] == '1')
			return NULL;
			
		// controllo se il prezzo � iva esclusa o inclusa	
		if ( $this->displayPriceWithTaxes() == true )
			$tax_text = TEXT_PWS_PRICES_WITH_VAT;
		else 
			$tax_text = TEXT_PWS_PRICES_WITHOUT_VAT;
				
		$products_id=tep_get_prid($products_id);
		$details=array();
		if (is_null($this->plugin_purchase) || $this->admin_side){
			$products_price=$this->getFirstPrice($products_id,NULL,true);
			$details[]=array('text'=>TEXT_PWS_PRICES_DEFAULT .  ' ' .  $tax_text 
				,'content'=>$this->formatPrice($products_price,$products_id)
			);
		}else{
			$products_price=$this->getFirstPrice($products_id);
		}
		$first_price=$products_price;

		for($i=0;$i<sizeof($this->plugins_prices);$i++){
			$old_price=$products_price;
			$det=$this->plugins_prices[$i]->getHtmlPriceDiscounts($products_id,&$products_price);
			// echo '['.$this->plugins_prices[$i]->plugin_name."]vecchio prezzo: $old_price. nuovo prezzo:$products_price<br/>";
			// echo "<br>";
			
			if (!is_null($det)){
				if (!isset($det['content'])){
					$det['content'] = $this->formatPrice($products_price,$products_id);
					if ($det['discount_perc']!=0.0)
						$det['content'].='<br>(-&nbsp;'.$this->formatPercentage($det['discount_perc']).')&nbsp;&nbsp;';
					
		
				}
				$details[]=$det;
			}

			// modifica per mostrare listino secco ai gruppi senza prezzo barrato, sconto, prezzo netto 
			if ($this->plugins_prices[$i]->plugin_name == 'Gruppi Cliente' &&  $det['discount_perc']!=0.0 && SHOW_GROUP_NET_PRICES == 'true')
				{
					unset($details);
					$details[0]['text'] = TEXT_PWS_PRICES_DEFAULT . ' ' .  $tax_text; 
					$details[0]['content'] = $this->formatPrice($products_price,$products_id);
	
				}
			// modifica per mostrare la data di scadenza dell'offerta speciale, se esiste
			if ($this->plugins_prices[$i]->plugin_name == TEXT_PWS_SPECIALS_NAME &&  $det['discount_perc']!=0.0)
				{
						// è una porcata ma molto veloce 
						$special_expires_date_query = tep_db_query("select expires_date from ".TABLE_SPECIALS." where products_id=$products_id and status='1' and (expires_date=NULL or expires_date='0000-00-00' or now()<=expires_date)");
						$special_expires_date_array = tep_db_fetch_array($special_expires_date_query);

					if ($special_expires_date_array['expires_date'] >= '1999-01-01')
					{
						$det['text'] = TEXT_PWS_SPECIALS_EXPIRES_DATE; 
						$det['content']=tep_date_short($special_expires_date_array['expires_date']);
					$details[]=$det;
					}
				}
	//	print_r($details);
	/*
	        Array
				(
				    [0] => Array
				        (
				            [text] => Prezzo
				            [content] => 289.00EUR
				        )
				
				    [1] => Array
				        (
				            [text] => Sconto speciale
				            [discount_perc] => 10.00
				            [tax_exempt] => 
				            [content] => (-&nbsp;10&nbsp;%)&nbsp;&nbsp;260.10EUR
				        )
				
				    [2] => Array
				        (
				            [text] => L'offerta scade il:
				            [discount_perc] => 10.00
				            [tax_exempt] => 
				            [content] => 06/02/2009
				        )
				
				)
	 */
	//	exit;
//			else echo "null ".$this->plugins_prices[$i]->plugin_name;
		}
		
		// Assegnazione delle classi css alle labels ed ai contenuti del riassunto del prezzo
		if (sizeof($details)>1 && $first_price>$products_price /*&& !$this->plugin_quantities->hasQuantityDiscounts($products_id)*/){
			$details[0]['text']='<span class="labelPrezzoListinoBarrato">'.$details[0]['text'].'</span>';
			$details[0]['content']='<span class="prezzoListinoBarrato">'.$details[0]['content'].'</span>';
		}else{
			$details[0]['text']='<span class="labelPrezzoListino">'.$details[0]['text'].'</span>';
			$details[0]['content']='<span class="prezzoListino">'.$details[0]['content'].'</span>';
		}
		for ($i=1;$i<sizeof($details);$i++){
			if (strstr($details[$i]['text'], TEXT_PWS_SPECIALS_EXPIRES_DATE) )
			{
				$details[$i]['text']='<span class="labelDataScadenza">'.$details[$i]['text'].'</span>';
				$details[$i]['content']='<span class="DataScadenza">'.$details[$i]['content'].'</span>';
			}
			else 
			{
				$details[$i]['text']='<span class="labelScontoPrezzo">'.$details[$i]['text'].'</span><br>';
				$details[$i]['content']='<span class="scontoPrezzo">'.$details[$i]['content'].'</span>';
			}
		}
		
		//	print_r($details);
		$skin=new pws_skin('pws_prices_discounts.htm');
		$skin->set('pdetails',$details);
		$result=$skin->execute();
		return $result;
	}
	
	//	@function	inscribeText
	//	@param		string	$texts				Testi da indentare
	//	@param		array(int(s))	$indents	Posizioni orizzontali delle colonne
	//	@param		string	$align				{'left','center','right'}	Allineamento del testo
	function	inscribeText($texts,$indents,$aligns){
		$sizes=array();
		$lastcol=$indents[0];
		for($i=1;$i<sizeof($indents);$i++){
			$sizes[]=$indents[$i]-$lastcol;
			$lastcol=$indents[$i];
		}
		$firstcol=$indents[0];
		$output='';
		for ($i=0;$i<$firstcol;$i++)
			$output.=' ';
		for ($c=0;$c<sizeof($texts);$c++){
			$size=$sizes[$c];
			$text=substr($texts[$c],0,$size);
			switch($aligns[$c]){
				case 'right':
					for($i=0;$i<$size-strlen($text);$i++)
						$output.=' ';
					$output.=$text;
					break;
				case 'left':
					$output.=$text;
					for($i=0;$i<$size-strlen($text);$i++)
						$output.=' ';
					break;
				case 'center':
					for($i=0;$i<floor(($size-strlen($text))/2);$i++)
						$output.=' ';
					$output.=$text;
					$size-=floor(($size-strlen($text))/2);
					for ($i=0;$i<$size;$i++)
						$output.=' ';
					break;
			}
		}
		return $output;
	}
	//	@function	getHtmlDiscountInfo
	//	@desc		Restituisce eventuali avvisi (in html) per la presenza di sconti sul prodotto passato come parametro
	//	@param		int		$products_id		Id del prodotto per cui generare l'avviso
	//	@return		string					Codice html
	function	getHtmlDiscountInfo($products_id){
		if (!$this->show_prices)
			return '';
		$products_id=tep_get_prid($products_id);
		// $products_price=$this->getFirstPrice($products_id);
		$products_price=$this->getBestPrice($products_id, $_SESSION['sppc_customer_group_id']);
	//	if ($this->displayPriceWithTaxes() == true)
		//	print $products_price =	$this->formatPrice($products_price,$products_id, true);
			
		$output='';
		for($i=0;$i<sizeof($this->plugins_prices);$i++){
			$old_price=$products_price;
			$output.=$this->plugins_prices[$i]->getHtmlDiscountInfo($products_id, $products_price);
		}
		// print_r ($this->plugins_prices);
		return $output;
	}
	
	//	@function	getHtmlPriceWithDiscount
	//	@desc		Restituisce il codice html contenente il prezzo originale e quello finale
	//	@param		int		$products_id		Id del prodotto
	//	@return		string
	function	getHtmlPriceWithDiscount($products_id)	{
		global $currencies;
		if (!$this->show_prices)
			return $this->show_hidden_prices_msg;

		// blocca visualizzazione del prezzo se è solo in mostra, non in vendita
		$query_onlyshow = tep_db_query("select products_onlyshow from " .TABLE_PRODUCTS . " where products_id=$products_id ");	
		$only_show = tep_db_fetch_array($query_onlyshow);
		 if ($only_show['products_onlyshow'] == '1')
			return '';
			
		$products_id=tep_get_prid($products_id);
			// controlliamo se è nelle offerte
		for($i=0;$i<sizeof($this->plugins_prices);$i++){
			$det=$this->plugins_prices[$i]->getHtmlPriceDiscounts($products_id,&$products_price);
			 if ($this->plugins_prices[$i]->plugin_name == TEXT_PWS_SPECIALS_NAME &&  $det['discount_perc']!=0.0 )
				{
					$flag_specials = 'ON';
					// è una porcata ma molto veloce 
					$special_expires_date_query = tep_db_query("select expires_date from ".TABLE_SPECIALS." where products_id=$products_id and status='1' and (expires_date=NULL or expires_date='0000-00-00' or now()<=expires_date)");
					$special_expires_date_array = tep_db_fetch_array($special_expires_date_query);
				}
		}
		$products_price=$this->getFirstPrice($products_id);
		$final_price=$this->getBestPrice($products_id);

		if ($products_price!=$final_price){
				if ($special_expires_date_array['expires_date'] >= '1999-01-01') // definisce la riga aggiuntiva per la scadenza dell'offerta speciale
					{
						$return_var = '<span class="productsPriceSlashed">'.$this->formatPrice($products_price,$products_id).'</span><br/>'
						.'<span class="productSpecialPrice">'.$this->formatPrice($final_price, $products_id).'</span><br/>'
						.'<span class="DataScadenza">'. TEXT_PWS_SPECIALS_EXPIRES_DATE . ' ' .tep_date_short($special_expires_date_array['expires_date']).'</span>';
					}
					else
					{
						$return_var = '<span class="productsPriceSlashed">'.$this->formatPrice($products_price,$products_id).'</span><br/>'
						.'<span class="productSpecialPrice">'.$this->formatPrice($final_price, $products_id).'</span><br/>'
						;
					}
				//print_r($this->plugin_quantities);exit;
			if (!is_null($this->plugin_quantities) && $this->plugin_quantities->hasQuantityDiscounts($products_id) && $flag_specials == '') 
			{
				return sprintf(TEXT_PWS_PRICES_STARTING_PRICE,$this->formatPrice($final_price, $products_id));
			}
			else{

				if ($final_price<$products_price &&  SHOW_GROUP_NET_PRICES == 'true' && $flag_specials == 'ON')
				{

					return $return_var;
				}
				elseif ($final_price<>$products_price &&  SHOW_GROUP_NET_PRICES == 'true' && $flag_specials == '')
					return '<span class="productsPrice">'.$this->formatPrice($final_price, $products_id).'</span>';
				else
					return $return_var;
			}
		}
		
		else
			return '<span class="productsPrice">'.$this->formatPrice($products_price, $products_id).'</span>';
	}

	//	@function	getShippingModules
	//	@desc		Restituisce l'array dei moduli spedizione installati ed utilizzabili
	function	getShippingModules(){
		if (!is_null($this->plugin_groups)){
			return $this->plugin_groups->getShippingModules();
		}
		else
		{
			return explode(';', MODULE_SHIPPING_INSTALLED);
		}
	}
	//	@function	getPaymentModules
	//	@desc		Restituisce l'array dei moduli pagamento installati ed utilizzabili
	function	getPaymentModules(){
		if (!is_null($this->plugin_groups)){
			return $this->plugin_groups->getPaymentModules();
		}
		else
		{
			return explode(';', MODULE_PAYMENT_INSTALLED);
		}
	}
	///////////////////////////
	// Sezione Admin

	//	@function adminNewProductSetDefault
	//	@desc		Crea nella struttura pInfo i propri dati relativi al prodotto
	function	adminNewProductSetDefault($products_id){
		for($i=0;$i<sizeof($this->plugins_prices);$i++){
			$this->plugins_prices[$i]->adminNewProductSetDefault($products_id);
		}
	}
	//	@function adminNewProduct
	//	@desc		Crea nella struttura pInfo i propri dati relativi al prodotto
	function	adminNewProduct(&$pInfo){
		for($i=0;$i<sizeof($this->plugins_prices);$i++){
			$this->plugins_prices[$i]->adminNewProduct(&$pInfo);
		}
	}
	//	@function adminLoadProduct
	//	@desc		Carica nella struttura pInfo i propri dati relativi al prodotto
	function	adminLoadProduct(&$pInfo){
		for($i=0;$i<sizeof($this->plugins_prices);$i++){
			$this->plugins_prices[$i]->adminLoadProduct(&$pInfo);
		}
	}
	//	@function adminJavascriptPre
	//	@desc		Restituisce il codice javascript utilizzato nell'editing dei parametri
	function	adminJavascriptPre(&$pInfo){
		$js_code='';
		for($i=0;$i<sizeof($this->plugins_prices);$i++){
			$js_code.=$this->plugins_prices[$i]->js_edit_product_init_pre;
		}
		return $js_code;
	}
	//	@function adminJavascriptInit
	//	@desc		Restituisce il codice javascript utilizzato nell'editing dei parametri
	function	adminJavascriptInit(&$pInfo){
		$js_code='';
		for($i=0;$i<sizeof($this->plugins_prices);$i++){
			$js_code.=$this->plugins_prices[$i]->js_edit_product_init;
		}
		return $js_code;
	}
	//	@function adminJavascript
	//	@desc		Restituisce il codice javascript utilizzato nell'editing dei parametri
	function	adminJavascript(&$pInfo){
		$this->varname_products_price='products_price';
		$this->varname_products_price_gross='products_price_gross';
?>
function doRound(x, places) {
  return Math.round(x * Math.pow(10, places)) / Math.pow(10, places);
}

function getTaxRate() {
  var products_tax_class=$('products_tax_class_id');
  var selected_value = products_tax_class.selectedIndex;
  var parameterVal = products_tax_class[selected_value].value;

  if ( (parameterVal > 0) && (tax_rates[parameterVal] > 0) ) {
    return tax_rates[parameterVal];
  } else {
    return 0;
  }
}

function updateGross() {
  var taxRate = getTaxRate();
  var netValue = $('<?=$this->varname_products_price?>').value;
  var grossValue = $('<?=$this->varname_products_price_gross?>');
  var result=netValue;
  if (taxRate > 0) {
    result = result * ((taxRate / 100) + 1);
  }
  grossValue.value=doRound(result,2);

}

function updateNet() {
  var taxRate = getTaxRate();
  var netValue = $('<?=$this->varname_products_price?>');
  var grossValue = $('<?=$this->varname_products_price_gross?>').value;
  var result=grossValue;
  if (taxRate > 0) {
    result = result / ((taxRate / 100) + 1);
  }

  netValue.value = doRound(result,2);
}
<?
		$element_chain=array('products_price','products_price_gross');
		for($i=0;$i<sizeof($this->plugins_prices);$i++){
			$this->plugins_prices[$i]->adminSetEditProductEventChain(&$this->varname_products_price,&$this->varname_products_price_gross,&$element_chain);
		}

		for($i=0;$i<sizeof($this->plugins_prices);$i++){
			echo $this->plugins_prices[$i]->adminJavascript(&$pInfo);
		}
	}
	//	@function adminStylesheet
	//	@desc		Restituisce il codice css utilizzato nell'editing dei parametri
	function	adminStylesheet(&$pInfo){
		echo file_get_contents(DIR_FS_PWS_STYLESHEETS.'pws_prices_admin.css');
		for($i=0;$i<sizeof($this->plugins_prices);$i++){
			echo $this->plugins_prices[$i]->adminStylesheet(&$pInfo);
		}
	}
	//	@function adminEditProduct
	//	@desc		Crea il codice html per l'editing del prodotto, nella sezione admin
	function	adminEditProduct($products_id,&$pInfo)	{
		for($i=0;$i<sizeof($this->plugins_prices);$i++){
			echo $this->plugins_prices[$i]->adminEditProduct($products_id,&$pInfo);
		}
	}
	//	@function adminPreview
	//	@desc		Restituisce il codice html da visualizzare per i prezzi
	function	adminPreview(&$pInfo){
	}
	//	@function adminUpdateProduct
	//	@desc		Salva i parametri editati per il prodotto, nella sezione admin
	function	adminUpdateProduct($products_id){
		for($i=0;$i<sizeof($this->plugins_prices);$i++){
			$this->plugins_prices[$i]->adminUpdateProduct($products_id);
		}
	}
	//	@function adminDeleteProduct
	//	@desc		Elimina i dati memorizzati relativamente ad un prodotto
	function	adminDeleteProduct($products_id){
		for($i=0;$i<sizeof($this->plugins_prices);$i++){
			$this->plugins_prices[$i]->adminDeleteProduct($products_id);
		}
		return true;	
	}
	//	@function	adminCopyProduct
	//	@desc		Duplica i dati relativi ad un prodotto
	function	adminCopyProduct($products_id,$dup_products_id){
		for($i=0;$i<sizeof($this->plugins_prices);$i++){
			$this->plugins_prices[$i]->adminCopyProduct($products_id,$dup_products_id);
		}
		return true;	
	}
	// Helper functions
	function formatPercentage($percentage){
		if (floor($percentage)==$percentage)
			$percentage=(int)$percentage;
		if (!$percentage)
			$percentage='0';
		return $percentage.'&nbsp;%';
	}
	// @function calculatePrice
	// @desc	Calcola il prezzo finale, composto dall'imponibile, dal fuori campo iva e dalle tasse sull'imponibile
	// @param	float	$products_price			Prezzo imponibile
	// @param	float	$products_price_tax_exempt	Parte del prezzo fuori campo iva
	// @param	int		$products_id	Id del prodotto
	// @param	bool	$with_taxes	[Opzionale] Flag per mostrare il prezzo tasse incluse/escluse
	function calculatePrice($products_price,$products_price_tax_exempt,$products_id,$with_taxes=NULL){
		global $currencies;
		if ($with_taxes===NULL)
			$with_taxes=$this->displayPriceWithTaxes();//DISPLAY_PRICE_WITH_TAX=='true';
		$products_id=tep_get_prid($products_id);
		$product_info_query = tep_db_query("select products_tax_class_id from " . TABLE_PRODUCTS . " where products_id = '$products_id'");
		$product_info = tep_db_fetch_array($product_info_query);
		$tax_rate=$with_taxes ? tep_get_tax_rate($product_info['products_tax_class_id']) : 0.0;
		return $currencies->calculate_price($products_price,$tax_rate)+$products_price_tax_exempt;
	}
	//	@function	displayPrices
	//	@desc		Restituisce il flag connesso alla visualizzazione dei prezzi
	//	@return 	bool
	function displayPrices(){
		return $this->show_prices;
	}
	//	@function	getHiddenPricesMessage
	//	@desc		Restituisce il messaggio da mostrare al posto dei prezzi
	//	@return		string
	function	getHiddenPricesMessage(){
		return $this->show_hidden_prices_msg;
	}
	
	// @function formatPrice
	// @desc	Restituisce il prezzo (con o senza tasse), formattato
	function formatPrice($price,$products_id,$with_taxes=NULL){
		global $currencies;
		if (!$this->show_prices)
			return $this->show_hidden_prices_msg;
		if ($with_taxes===NULL)
			$with_taxes=$this->displayPriceWithTaxes();//DISPLAY_PRICE_WITH_TAX=='true';
		$products_id=tep_get_prid($products_id);
		$product_info_query = tep_db_query("select products_tax_class_id from " . TABLE_PRODUCTS . " where products_id = '$products_id'");
		$product_info = tep_db_fetch_array($product_info_query);
//		print "con tasasse ".$with_taxes;
		// if ($with_taxes==1)
			// $tax_rate=tep_get_tax_rate($product_info['products_tax_class_id']);
			 $tax_rate=tep_get_tax_rate('1');
	    // else 
		//	$tax_rate=0.0;	
		$tax_rate=$with_taxes ? tep_get_tax_rate($product_info['products_tax_class_id']) : 0.0;
//		print "aliquota da pws_price" . $tax_rate;
		
//		print "Tax_class_id=".$product_info['products_tax_class_id']."<br>"; 
//		print "tasse? " . $with_taxes . " ". tep_get_tax_rate($product_info['products_tax_class_id']) . "<br>";
//		print "PREZZZZZO" .  $currencies->display_price($price,$tax_rate);
	//	exit;
		return $currencies->display_price($price,$tax_rate);
	}
	//	@function install
	//	@desc	Funzione di installazione del plugin
	function install(){
		if (!$this->_pws_engine->fieldExists('pws_price_resume',TABLE_ORDERS_PRODUCTS)){
			tep_db_query("alter table ".TABLE_ORDERS_PRODUCTS." add column pws_price_resume blob default null after products_price");
		}
		return true;
	}
	//	@function remove
	//	@desc	Funzione di rimozione del plugin
	function remove(){
		return true;
	}
}
?>