<?php
/*
 * @filename:	pws_prices_specials.php
 * @version:	0.24
 * @project:	PWS
 *
 * @author:		Riccardo Roscilli <info@oscommerce.it>
 * @created:	23/mag/07
 * @modified:	23/mag/07 12:01:05
 *
 * @copyright:	2006-2007	Riccardo Roscilli @ PWS
 *
 * @desc:	Plugin per le offerte speciali, con sconti in percentuale
 *
 * @TODO:		
 */

define('TABLE_PWS_SPECIALS',TABLE_PWS_PREFIX.'specials');

class	pws_prices_specials	extends pws_plugin_price {
	// Variabili del plugin
	var $plugin_code='pws_prices_specials';			// Codice univoco del plugin ( deve coincidere con il nome del file )
	var $plugin_name=TEXT_PWS_SPECIALS_NAME;		// Nome del plugin
	var $plugin_description=TEXT_PWS_SPECIALS_DESCRIPTION;	// Descrizione del plugin
	var $plugin_version_const='0.24';		// Versione del codice
	var	$plugin_excludes=array('pws_prices_quantities','pws_prices_customers_groups','pws_prices_categories','pws_prices_manufacturers');		// Contiene i plugin_code dei plugin che vanno esclusi quando viene attivato questo plugin
	var $plugin_needs=array('pws_prices_quantities','pws_prices_customers_groups','pws_prices_categories','pws_prices_manufacturers');	// Codici dei plugin da cui dipende=>istanza del plugin
	var	$plugin_editPage='';
	var $plugin_sort_order=1;
	var $plugin_configKeys=array();	// Chiavi di configurazione del plugin, in forma chiave=>array(field1=>value1, ..., fieldn=>valuen)
	var	$plugin_config=array();	// Lista chiave di configurazione=>valore corrispondente
	var $plugin_tables=array(
		TABLE_PWS_SPECIALS=>"
(
  specials_id int(11) NOT NULL default '0',
  products_id int(11) NOT NULL default '0',
  pws_specials_discount decimal(6,2) NOT NULL default '0.00',
  KEY  (products_id),
  UNIQUE KEY  (specials_id)
)"
	);	// Tables utilizzate dal plugin
	var $plugin_sql_install="";	// Codice sql da applicare in fase di installazione del plugin

	var $plugin_removable=false;	// Questo plugin non può essere rimosso e viene installato di default	

	
	// Variabili usate in admin durante l'editing dei prezzi
	var $varname_products_price_to='pws_specials_price';		// (può essere un array) Deve essere impostato dal plugin con il nome dell'input field contenente il nuovo prezzo netto
	var $varname_products_price_gross_to='pws_specials_price_gross';		// (può essere un array) Deve essere impostato dal plugin con il nome dell'input field contenente il nuovo prezzo lordo
	var $js_edit_product_init='pwsInitSpecialsDiscount();';		// Codice javascript da lanciare a caricamento ultimato della pagina
	var $js_edit_product_init_pre='';		// Codice javascript da lanciare prima del caricamento della pagina
	
	
	
	function pws_prices_specials(&$pws_engine){
		parent::pws_plugin_price(&$pws_engine);
	}

	function init(){
		parent::init();
	}
	//	@function catalogStylesheet
	//	@desc	Restituisce il codice css utilizzato nel front end da tutti i plugin prezzi
	function	catalogStylesheet(){
		return '';//@file_get_contents(DIR_FS_PWS_STYLESHEETS.'pws_prices_quantities_info.css');
	}
	//	@function	getDiscountPercentage
	//	@desc		Restituisce la percentuale di sconto presente su un prodotto
	//	@param		int		$products_id		Id del prodotto per cui calcolare la percentuale di scontoo
	//	@param		int		$products_quantity	Numero colli
	//	@param		int		$customers_id		Id del cliente per cui generare la percentuale di sconto
	//	@return		float						la percentuale di sconto
	function getDiscountPercentage($products_id, $products_quantity=1, $customers_id=NULL)	{
		$specialsQuery=tep_db_query("select pws_specials_discount from ".TABLE_PWS_SPECIALS." ps left join ".TABLE_SPECIALS." s on (s.specials_id=ps.specials_id) where s.products_id='$products_id' and s.status='1' and (expires_date=NULL or expires_date='0000-00-00' or now()<=expires_date)");
		if ($special=tep_db_fetch_array($specialsQuery)){
			$discount=$special['pws_specials_discount'];
			return $discount;
		}else
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
		$specialsQuery=tep_db_query("select pws_specials_discount from ".TABLE_PWS_SPECIALS." ps left join ".TABLE_SPECIALS." s on (s.specials_id=ps.specials_id) where s.products_id='$products_id' and s.status='1' and (expires_date=NULL or expires_date='0000-00-00' or now()<=expires_date)");
		if ($special=tep_db_fetch_array($specialsQuery)){
			$discount=$special['pws_specials_discount'];
			$products_price*=(1-$discount/100.0);
		}
		return $products_price;
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
		$specialsQuery=tep_db_query("select pws_specials_discount from ".TABLE_PWS_SPECIALS." ps left join ".TABLE_SPECIALS." s on (s.specials_id=ps.specials_id) where s.products_id='$products_id' and s.status='1' and (expires_date=NULL or expires_date='0000-00-00' or now()<=expires_date)");
		if ($special=tep_db_fetch_array($specialsQuery)){
			$discount=$special['pws_specials_discount'];
			$products_price*=(1-$discount/100.0);
			return array('text'=>TEXT_PWS_SPECIALS_COMPACT_TITLE
				,'products_price'=>$products_price
				,'discount_perc'=>$discount
				,'tax_exempt'=>false
			);
		}else
			return NULL;
	}
	//	@function	getHtmlPriceDiscounts
	//	@desc		Restituisce il codice html contenente tutti gli sconti disponibili sul prodotto
	//	@param		int		$products_id		Id del prodotto per cui calcolare il prezzo successivo
	//	@param		float	$products_price		Prezzo	precedente del prodotto
	//	@return		array('text'=>string,'discount_perc'=>float,['content'=>string]) Codice html che visualizza le opzioni prezzo possibili per questo prodotto
	function getHtmlPriceDiscounts($products_id, &$products_price)	{
		$specialsQuery=tep_db_query("select pws_specials_discount from ".TABLE_PWS_SPECIALS." ps left join ".TABLE_SPECIALS." s on (s.specials_id=ps.specials_id) where s.products_id='$products_id' and s.status='1' and (s.expires_date  is NULL or s.expires_date='0000-00-00' or now()<= s.expires_date)");
		if ($special=tep_db_fetch_array($specialsQuery)){
			$discount=$special['pws_specials_discount'];
			$products_price*=(1-$discount/100.0);
			return array('text'=>TEXT_PWS_SPECIALS_COMPACT_TITLE
				,'discount_perc'=>$discount
				,'tax_exempt'=>false
			);
		}else
			return NULL;
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
	//	@function	statusOnProduct
	//	@param		int		$products_id		Id del prodotto su cui è richiesto lo stato del plugin
	//	@return 	bool				Stato del plugin
	function statusOnProduct($products_id){
		$specialsQuery=tep_db_query("select status from ".TABLE_SPECIALS." where products_id=$products_id and status='1' and (expires_date=NULL or expires_date='0000-00-00' or now()<=expires_date)");
		if ($special=tep_db_fetch_array($specialsQuery)){
				return $special['status']=='1';
		}else
			return false;
	}
	//	@function	adminDisableOnProduct
	//	@param		int		$products_id		Id del prodotto su cui disabilitare eventuali offerte
	//	@return		bool						Restituisce true se è stato disabilitato uno sconto
	function adminDisableOnProduct($products_id){
		$specialsQuery=tep_db_query("select status from ".TABLE_SPECIALS." where products_id=$products_id and status='1' and (expires_date=NULL or expires_date='0000-00-00' or now()<=expires_date)");
		if ($special=tep_db_fetch_array($specialsQuery)){
			if ($special['status']=='1'){
				tep_db_query("update ".TABLE_PWS_SPECIALS." set status='0' where products_id=$products_id");
				return true;
			}else
				return false;
		}else
			return false;
	}
	//	@function adminNewProduct
	//	@desc		Crea nella struttura pInfo i propri dati relativi al prodotto
	function adminNewProduct(&$pInfo){
		$pInfo->objectInfo(array(
			'specials_id'=>0
			,'pws_specials_status'=>'0'
			,'pws_specials_discount'=>'0.0'
			,'pws_specials_expires'=>NULL
		));
		return true;
	}
	//	@function adminNewProductSetDefault
	//	@desc		Crea i valori di default relativi ad un plugin prezzo, per un nuovo prodotto
	function	adminNewProductSetDefault($products_id){
		$discount_status=parent::getStatus($products_id);
		$sql_data_array=array(
			'status'=>$discount_status ? '1':'0'
		);
		$productsQuery=tep_db_query("select * from ".TABLE_SPECIALS." where products_id='$products_id'");
		if ($product=tep_db_fetch_array($productsQuery))
			tep_db_perform(TABLE_SPECIALS,$sql_data_array,'update',"products_id=$products_id");
	}
	//	@function adminLoadProduct
	//	@desc		Carica nella struttura pInfo i propri dati relativi al prodotto
	function adminLoadProduct(&$pInfo){
		$specialsQuery=tep_db_query("select * from ".TABLE_PWS_SPECIALS." ps left join ".TABLE_SPECIALS." s on (s.specials_id=ps.specials_id) where s.products_id='".$pInfo->products_id."'");
		if ($special=tep_db_fetch_array($specialsQuery)){
			$discount=array(
				'specials_id'=>$special['specials_id']
				,'pws_specials_discount'=>$special['pws_specials_discount']
				,'pws_specials_status'=>$special['status']
				,'pws_specials_expires'=>substr($special['expires_date'],0,10)=='0000-00-00'?'':substr($special['expires_date'],0,10)
			);
			$pInfo->objectInfo($discount);
		}else
			return $this->adminNewProduct(&$pInfo);
		return true;
	}
	//	@function adminJavascript
	//	@desc		Restituisce il codice javascript utilizzato nell'editing dei parametri
	function adminJavascript(&$pInfo){
?>
		function pwsInitSpecialsDiscount(){
			var tolisten;
<?			reset($this->price_editing_fields_chain);
			foreach($this->price_editing_fields_chain as $elementname){?>
			tolisten=$('<?=$elementname?>');
			pwsAttachEvent(tolisten,'change',pwsUpdateSpecialsDiscountPrice);
			pwsAttachEvent(tolisten,'blur',pwsUpdateSpecialsDiscountPrice);
			pwsAttachEvent(tolisten,'keyup',pwsUpdateSpecialsDiscountPrice);
<?			}?>
			pwsUpdateSpecialsDiscountPrice();
		}
		function pwsUpdateSpecialsDiscountPrice(){
			var pws_specials_status=$('pws_specials_status').checked;
			var pricenet_from=$('<?=$this->varname_products_price?>');
			var pricegross_from=$('<?=$this->varname_products_price_gross?>');
			var pricenet=$('<?=$this->varname_products_price_to?>');
			var pricegross=$('<?=$this->varname_products_price_gross_to?>');
			var discount=$('pws_specials_discount');
			discount=pws_specials_status ? parseFloat(discount.value) : 0.0;
			pricenet_from=parseFloat(pricenet_from.value);
			pricegross_from=parseFloat(pricegross_from.value);
			pricenet.value=doRound(pricenet_from*(1-discount/100.0),2);
			pricegross.value=doRound(pricegross_from*(1-discount/100.0),2);
		}
		function pwsSpecialsDiscountChange(){
			var specials_price=$("pws_specials_price");
			var specials_discount=$("pws_specials_discount");
			var specials_price_gross=$("pws_specials_price_gross");
			var products_price=$('<?=$this->varname_products_price?>');//$("products_price");
			var products_price_gross=$('<?=$this->varname_products_price_gross?>');
			specials_price.value=doRound(products_price.value*(1-specials_discount.value/100.0),2);
			specials_price_gross.value=doRound(products_price_gross.value*(1-specials_discount.value/100.0),2);
			
		}
		function pwsSpecialsPriceChange(){
			var specials_price=$("pws_specials_price");
			var specials_discount=$("pws_specials_discount");
			var specials_price_gross=$("pws_specials_price_gross");
			var products_price=$('<?=$this->varname_products_price?>');//$("products_price");
			specials_discount.value=doRound((1-specials_price.value/products_price.value)*100.0,2);
			specials_price_gross.value=doRound(products_price_gross.value*(1-specials_discount.value/100.0),2);
		}
		function pwsSpecialsStatusChange(){
			var specialdiv=$("pwsSpecialDiv");
			var pws_specials_status=$('pws_specials_status');
			specialdiv.style.display=pws_specials_status.checked ? 'block' : 'none';
<?	if ($this->plugin_using['pws_prices_quantities']!=NULL){?>
			var quantities_status=$('pws_quantities_status');
			if (quantities_status && quantities_status.checked && pws_specials_status.checked){
				quantities_status.checked=false;
				onQuantityDiscountsChange();
			}
<?	}?>
<?	if ($this->plugin_using['pws_prices_customers_groups']!=NULL){?>
			var customers_status=$('pws_customers_groups_status');
			if (customers_status.checked && pws_specials_status.checked){
				customers_status.checked=false;
				onCustomersGroupsChange();
			}
<?	}?>
<?	if ($this->plugin_using['pws_prices_categories']!=NULL){?>
			var pws_categories_products_status=$('pws_categories_products_status');
			if (pws_categories_products_status.checked && pws_specials_status.checked){
				pws_categories_products_status.checked=false;
				onCategoriesDiscountChange();
			}
<?	}?>
<?	if ($this->plugin_using['pws_prices_manufacturers']!=NULL){?>
			var manufacturers_status=$('pws_manufacturers_products_status');
			if (manufacturers_status && manufacturers_status.checked && pws_specials_status.checked){
				manufacturers_status.checked=false;
				onManufacturersDiscountChange();
			}
<?	}?>
			pwsUpdateSpecialsDiscountPrice();
		}
<?
		$this->js_edit_product_init_pre='var pwsSpecialsExpires = new ctlSpiffyCalendarBox("pwsSpecialsExpires", "new_product", "pws_specials_expires","btnDate1","'.$this->_pws_engine->formatDate($pInfo->pws_specials_expires,DATE_PWS_FORMAT).'",scBTNMODE_CUSTOMBLUE);';
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
		$fields_chain[]="pws_specials_discount";
		$fields_chain[]=$this->varname_products_price_to;
		$fields_chain[]=$this->varname_products_price_gross_to;
	}
	//	@function adminEditProduct
	//	@desc		Crea il codice html per l'editing del prodotto, nella sezione admin
	function adminEditProduct($products_id,&$pInfo){
?>
<!-- BOF pws_prices_specials -->
    <tr bgcolor="#ebebff" >
       <td colspan="2"><fieldset name="pws_specials"><legend ><input type="checkbox" name="pws_specials_status" id="pws_specials_status" value="1" <?=$pInfo->pws_specials_status ? 'checked="true"':''?> onClick="pwsSpecialsStatusChange();"/><label for="pws_specials_status" class="pws_fieldset_label"><?=TEXT_PWS_SPECIALS_ACTIVATE?></label></legend>
       	<div id="pwsSpecialDiv" style="display:<?=($pInfo->pws_specials_status=='1' ? 'block' : 'none')?>"><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><strong><?php echo TEXT_PWS_SPECIALS_DISCOUNT; ?>&nbsp;</strong></td>
            <td class="main"><?php echo tep_draw_input_field('pws_specials_discount', $pInfo->pws_specials_discount ,'size="6" maxlength="6" onKeyUp="pwsSpecialsDiscountChange()" onBlur="pwsSpecialsDiscountChange()"'); ?>&nbsp;<b>%</b>&nbsp;</td>
          </tr>
          <tr>
            <td class="main"><strong><?php echo TEXT_PWS_SPECIALS_PRICE; ?>&nbsp;</strong></td>
            <td class="main"><?php echo tep_draw_input_field($this->varname_products_price_to, (''.$pInfo->products_price*(1-$pInfo->pws_specials_discount/100.0)),'size="10" maxlength="10" onKeyUp="pwsSpecialsPriceChange()" onBlur="pwsSpecialsPriceChange()"'); ?></td>
          </tr>
          <tr>
            <td class="main"><strong><?php echo TEXT_PWS_SPECIALS_PRICE_GROSS; ?>&nbsp;</strong></td>
            <td class="main"><?php echo tep_draw_input_field($this->varname_products_price_gross_to, (''.$pInfo->products_price*(1-$pInfo->pws_specials_discount/100.0)),'size="10" maxlength="10" onKeyUp="pwsSpecialsPriceChange()" onBlur="pwsSpecialsPriceChange()" readonly="true" class="pws_textfield_disabled"'); ?></td>
          </tr>
          <tr>
            <td class="main"><?=TEXT_PWS_SPECIALS_EXPIRES_DATE?><br/><small>(<?=DATE_PWS_FORMAT?>)</small></td>
            <td class="main"><script language="javascript">pwsSpecialsExpires.writeControl(); pwsSpecialsExpires.dateFormat="<?=DATE_PWS_FORMAT?>";</script></td>
          </tr>
        </table>
        </div></fieldset></td>
      </tr>
<!-- EOF pws_prices_specials -->
<?
	}

	function adminUpdateProduct($products_id){
		global $pws_prices;
		$pws_specials_status=isset($_REQUEST['pws_specials_status']) ? '1' : '0';
		$products_price=$pws_prices->getFirstPrice($products_id);
		$pws_specials_discount=$_REQUEST['pws_specials_discount'];
		$pws_specials_discount;

		if (!strlen($_REQUEST['pws_specials_expires'])){
			$pws_specials_expires=NULL;
		}else{
			$pws_specials_expires=substr($_REQUEST['pws_specials_expires'],strpos(DATE_PWS_FORMAT,'yyyy'),4);
			$pws_specials_expires.='-'.substr($_REQUEST['pws_specials_expires'],strpos(DATE_PWS_FORMAT,'MM'),2);
			$pws_specials_expires.='-'.substr($_REQUEST['pws_specials_expires'],strpos(DATE_PWS_FORMAT,'dd'),2);
		}

		$sql_data_array=array(
			'specials_new_products_price'=>$products_price*(1-$pws_specials_discount/100.0)
			,'expires_date'=>$pws_specials_expires
			,'status'=>$pws_specials_status
		);
		
		$specialsQuery=tep_db_query("select specials_id from ".TABLE_PWS_SPECIALS." where products_id=$products_id");
		if ($special=tep_db_fetch_array($specialsQuery)){
			$specials_id=$special['specials_id'];
			$sql_data_array['specials_last_modified']='now()';
			tep_db_perform(TABLE_SPECIALS,$sql_data_array,'update',"specials_id=$specials_id");
			$insert=false;
		}else{
			if ($pws_specials_status=='1'){
				$sql_data_array['specials_date_added']='now()';
				$sql_data_array['products_id']=$products_id;
				tep_db_perform(TABLE_SPECIALS,$sql_data_array);
				$specials_id=tep_db_insert_id();
			
			}
			$insert=true;
		}
		
		$sql_data_array=array(
			'products_id'=>$products_id
			,'pws_specials_discount'=>$pws_specials_discount
		);
		if (!$insert)
			tep_db_perform(TABLE_PWS_SPECIALS,$sql_data_array,'update',"specials_id=$specials_id");
		else{
			$sql_data_array['specials_id']=$specials_id;
			if ($pws_specials_status=='1'){
				tep_db_perform(TABLE_PWS_SPECIALS,$sql_data_array);
			}
		}
//		tep_db_query("delete from ".TABLE_PWS_SPECIALS." where products_id=$products_id");
	}
	function adminDeleteProduct($products_id){
	    tep_db_query("delete from " . TABLE_PWS_SPECIALS . " where products_id = '" . (int)$products_id . "'");
		return true;
	}
	//	@function	adminCopyProduct
	//	@desc		Duplica i dati relativi ad un prodotto
	function adminCopyProduct($products_id,$dup_products_id){
		/*$specials_query=tep_db_query("select from ".TABLE_PWS_SPECIALS." where products_id=$products_id");
		if ($specials_data=tep_db_fetch_array($specials_query)){
			$specials_data['products_id']=$dup_products_id;
			tep_db_perform(TABLE_PWS_SPECIALS,$specials_data);
		}*/
	}
	function deleteSpecial($specials_id){
		tep_db_query("delete from ".TABLE_PWS_SPECIALS." where specials_id=$specials_id");
		tep_db_query("delete from ".TABLE_SPECIALS." where specials_id=$specials_id");
	}
	//	@function	getStatus
	//	@desc	Restituisce true se il plugin applica qualche modifica al prezzo
	//	@param	int	$products_id
	function	getStatus($products_id){
		$check_query=tep_db_query("select * from ".TABLE_SPECIALS." where products_id='".$products_id."' and status");
		return tep_db_num_rows($check_query)>0;
	}
	function setSpecialsStatus($specials_id,$status){
		$specialQuery=tep_db_query("select products_id from ".TABLE_PWS_SPECIALS." where specials_id=$specials_id");
		$special=tep_db_fetch_array($specialQuery);
		$products_id=$special['products_id'];
		if ($status == '1') {
			reset($this->plugin_using);
			foreach($this->plugin_using as $plugin_code=>$plugin){
				if ($plugin!=NULL && method_exists($plugin,'adminDisableOnProduct')){
					$plugin->adminDisableOnProduct($products_id);
				}
			}
			return tep_db_query("update " . TABLE_SPECIALS . " set status = '1', expires_date = NULL where specials_id = '" . (int)$specials_id . "'");
		} elseif ($status == '0') {
			return tep_db_query("update " . TABLE_SPECIALS . " set status = '0' where specials_id = '" . (int)$specials_id . "'");
		} else {
			return -1;
		}
	}
	//	@function install
	//	@desc	Funzione di installazione del plugin
	//	@notes	Converte gli speciali della tabella TABLE_SPECIALS in speciali della tabella TABLE_PWS_SPECIALS
	function install(){
		// Fix della tabella specials: rimuove i duplicati
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
		return true;
	}
	//	@function remove
	//	@desc	Funzione di rimozione del plugin
	//	@notes	Converte gli speciali della tabella TABLE_PWS_SPECIALS in speciali della tabella TABLE_SPECIALS
	function remove(){
		return true;
	}
	//	@function update
	//	@desc	Funzione di aggiornamento del plugin
	//	@param	string $fromVersion		Vecchia versione
	function update($fromVersion)	{
		switch ($fromVersion){
			case $this->plugin_version_const:
				return false;
			case '0.22':
			case '0.23':
			default:
				tep_db_query("alter table ".TABLE_PWS_SPECIALS." modify column pws_specials_discount decimal(6,2) NOT NULL default '0.00'");
				break;
		}
		parent::update($fromVersion);
	}
	
}
?>