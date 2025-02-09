<?php
/*
 * @filename:	pws_prices_categories.php
 * @version:	0.23
 * @project:	PWS
 *
 * @author:		Riccardo Roscilli <info@oscommerce.it>
 * @created:	29/mag/07
 * @modified:	06/giu/08 17:30:53
 *
 * @copyright:	2006-2008	Riccardo Roscilli @ PWS
 *
 * @desc:	
 *
 * @TODO:		
 */

define('TABLE_PWS_CATEGORIES',TABLE_PWS_PREFIX.'categories_discounts');
define('TABLE_PWS_CATEGORIES_PRODUCTS',TABLE_PWS_PREFIX.'categories_products');

class	pws_prices_categories	extends pws_plugin_price {
	// Variabili del plugin
	var $plugin_code='pws_prices_categories';			// Codice univoco del plugin ( deve coincidere con il nome del file )
	var $plugin_name=TEXT_PWS_PRICES_CATEGORIES_NAME;		// Nome del plugin
	var $plugin_description=TEXT_PWS_PRICES_CATEGORIES_DESCRIPTION;	// Descrizione del plugin
	var $plugin_version_const='0.23';		// Versione del codice
	var $plugin_needs=array('pws_prices_specials');//,'pws_prices_quantities');//,'pws_prices_manufacturers');	// Codici dei plugin da cui dipende=>istanza del plugin
	var	$plugin_excludes=array('pws_prices_specials');//,'pws_prices_quantities');//,'pws_prices_manufacturers');		// Contiene i plugin_code dei plugin che vanno esclusi quando viene attivato questo plugin
	var	$plugin_conflicts=array('pws_prices_quantities','pws_prices_manufacturers');
	var	$plugin_editPage='';
	var $plugin_sort_order=6;
	var $plugin_configKeys=array();	// Chiavi di configurazione del plugin, in forma chiave=>array(field1=>value1, ..., fieldn=>valuen)
	var $plugin_tables=array(
		TABLE_PWS_CATEGORIES=>"
(
  categories_id int(11) NOT NULL default '0',
  pws_categories_discount decimal(6,2) NOT NULL default '0.00',
  pws_categories_discount_parent decimal(6,2) NOT NULL default '0.00',
  UNIQUE KEY  (categories_id)
)"
		,TABLE_PWS_CATEGORIES_PRODUCTS=>"
(
  products_id int(11) NOT NULL default '0',
  pws_categories_products_status enum('0','1') NOT NULL default '1',
  UNIQUE KEY  (products_id)
)"
	);	// Tables utilizzate dal plugin
	var $plugin_sql_install='';	// Codice sql da applicare in fase di installazione del plugin

	var $plugin_sql_remove='';	// Codice sql da applicare in fase di rimozione del plugin
	
	// Definizione dei punti di intervento
	var	$plugin_hooks=array(
		'ADMIN_CATEGORIES_EDIT'=>'adminEditCategories'
		,'ADMIN_CATEGORIES_NEW'=>'adminNewCategories'
		,'ADMIN_CATEGORIES_INSERT'=>'adminInsertCategories'
		,'ADMIN_CATEGORIES_UPDATE'=>'adminUpdateCategories'
		,'ADMIN_CATEGORIES_DELETE'=>'adminDeleteCategories'
		,'ADMIN_CATEGORIES_DISPLAY'=>'adminDisplayCategories'
	);	// Definizione dei punti di intervento del plugin: array associativo -- codice della funzione=>nome del metodo da chiamare

	// Variabili usate in admin durante l'editing dei prezzi
	var $varname_products_price_to='pws_categories_products_price';		// Deve essere impostato dal plugin con il nome dell'input field contenente il nuovo prezzo netto
	var $varname_products_price_gross_to='pws_categories_products_price_gross';		// Deve essere impostato dal plugin con il nome dell'input field contenente il nuovo prezzo lordo
	var $js_edit_product_init='pwsInitCategoriesDiscount();';		// Codice javascript da lanciare a caricamento ultimato della pagina
	
	
	
	
	function pws_prices_categories(&$pws_engine){
		parent::pws_plugin_price(&$pws_engine);
	}

	function init(){
		parent::init();
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
		$checkQuery=tep_db_query("select pws_categories_products_status from ".TABLE_PWS_CATEGORIES_PRODUCTS." where products_id=$products_id and pws_categories_products_status='1'");
		if (!tep_db_num_rows($checkQuery))
			return 0.0;
		$categoriesQuery=tep_db_query("select * from ".TABLE_PRODUCTS_TO_CATEGORIES." p2c left join ".TABLE_PWS_CATEGORIES." pc on(p2c.categories_id=pc.categories_id) where p2c.products_id=$products_id");
		if ($categories=tep_db_fetch_array($categoriesQuery)){
			$discount=$categories['pws_categories_discount']!=0.0?$categories['pws_categories_discount']:$categories['pws_categories_discount_parent'];
			return $discount;
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
		$checkQuery=tep_db_query("select pws_categories_products_status from ".TABLE_PWS_CATEGORIES_PRODUCTS." where products_id=$products_id and pws_categories_products_status='1'");
		if (!tep_db_num_rows($checkQuery))
			return $products_price;
		$categoriesQuery=tep_db_query("select * from ".TABLE_PRODUCTS_TO_CATEGORIES." p2c left join ".TABLE_PWS_CATEGORIES." pc on(p2c.categories_id=pc.categories_id) where p2c.products_id=$products_id");
		if ($categories=tep_db_fetch_array($categoriesQuery)){
			$discount=$categories['pws_categories_discount']!=0.0?$categories['pws_categories_discount']:$categories['pws_categories_discount_parent'];
			$products_price*=(1-$discount/100.0);
		}
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
		$checkQuery=tep_db_query("select pws_categories_products_status from ".TABLE_PWS_CATEGORIES_PRODUCTS." where products_id=$products_id and pws_categories_products_status='1'");
		if (!tep_db_num_rows($checkQuery))
			return NULL;
		$categoriesQuery=tep_db_query("select * from ".TABLE_PRODUCTS_TO_CATEGORIES." p2c left join ".TABLE_PWS_CATEGORIES." pc on(p2c.categories_id=pc.categories_id) where p2c.products_id=$products_id");
		$categories=tep_db_fetch_array($categoriesQuery);
		$discount=$categories['pws_categories_discount']!=0.0?$categories['pws_categories_discount']:$categories['pws_categories_discount_parent'];
		$products_price*=(1-$discount/100.0);
		if ($discount==0.0)
			return NULL;
		else
			return array('text'=>TEXT_PWS_CATEGORIES_COMPACT_TITLE
				,'products_price'=>$products_price
				,'discount_perc'=>$discount
				,'tax_exempt'=>false
			);
	}
	//	@function	getHtmlPriceDiscounts
	//	@desc		Restituisce il codice html contenente tutti gli sconti disponibili sul prodotto
	//	@param		int		$products_id		Id del prodotto per cui calcolare il prezzo successivo
	//	@param		float	$products_price		Prezzo	precedente del prodotto
	//	@return		array('text'=>string,'discount_perc'=>float,['content'=>string]) Codice html che visualizza le opzioni prezzo possibili per questo prodotto
	function getHtmlPriceDiscounts($products_id, &$products_price)	{
		$checkQuery=tep_db_query("select pws_categories_products_status from ".TABLE_PWS_CATEGORIES_PRODUCTS." where products_id=$products_id and pws_categories_products_status='1'");
		if (!tep_db_num_rows($checkQuery))
			return NULL;
		$categoriesQuery=tep_db_query("select * from ".TABLE_PRODUCTS_TO_CATEGORIES." p2c left join ".TABLE_PWS_CATEGORIES." pc on(p2c.categories_id=pc.categories_id) where p2c.products_id=$products_id");
		$categories=tep_db_fetch_array($categoriesQuery);
		$discount=$categories['pws_categories_discount']!=0.0?$categories['pws_categories_discount']:$categories['pws_categories_discount_parent'];
		if ($discount==0.0)
			return NULL;
		else{
			$products_price*=(1-$discount/100.0);
			return array('text'=>TEXT_PWS_CATEGORIES_COMPACT_TITLE
				,'discount_perc'=>$discount
				,'tax_exempt'=>false
			);
		}
	}

	//	@function	statusOnProduct
	//	@param		int		$products_id		Id del prodotto su cui è richiesto lo stato del plugin
	//	@return 	bool				Stato del plugin
	function statusOnProduct($products_id){
		$statusQuery=tep_db_query("select pws_categories_products_status from ".TABLE_PWS_CATEGORIES_PRODUCTS." where products_id=".$products_id);
		$status=tep_db_fetch_array($statusQuery);
		$status=$status['pws_categories_products_status'];
		return $status=='1';
	}
	//	@function	adminDisableOnProduct
	//	@param		int		$products_id		Id del prodotto su cui disabilitare eventuali offerte
	//	@return		bool						Restituisce true se è stato disabilitato uno sconto
	function adminDisableOnProduct($products_id){
		$statusQuery=tep_db_query("select pws_categories_products_status from ".TABLE_PWS_CATEGORIES_PRODUCTS." where products_id=".$products_id);
		$status=tep_db_fetch_array($statusQuery);
		$status=$status['pws_categories_products_status'];
		if ($status=='1'){
			tep_db_query("update ".TABLE_PWS_CATEGORIES_PRODUCTS." set pws_categories_products_status='0' where products_id=$products_id");
			return true;
		}else
			return false;
	}
	//	@function adminNewProduct
	//	@desc		Crea nella struttura pInfo i propri dati relativi al prodotto
	function adminNewProduct(&$pInfo){
		global $current_category_id;
		$categoriesQuery=tep_db_query("select * from ".TABLE_PWS_CATEGORIES." where categories_id=".$current_category_id);
		$categories=tep_db_fetch_array($categoriesQuery);
		$discount=$categories['pws_categories_discount']!=0.0?$categories['pws_categories_discount']:$categories['pws_categories_discount_parent'];
		$pInfo->pws_categories_discount=$discount;
		$pInfo->pws_categories_products_status=true;
		return true;
	}
	//	@function	getStatus
	//	@desc	Restituisce true se il plugin applica qualche modifica al prezzo
	//	@param	int	$products_id
	function	getStatus($products_id){
		$check_query=tep_db_query("select * from ".TABLE_PWS_CATEGORIES_PRODUCTS." where products_id='".$products_id."' and pws_categories_products_status");
		return tep_db_num_rows($check_query)>0;
	}
	//	@function adminNewProductSetDefault
	//	@desc		Crea nella struttura pInfo i propri dati relativi al prodotto
	function	adminNewProductSetDefault($products_id){
		$discount_status=parent::getStatus($products_id);
		$sql_data_array=array(
			'pws_categories_products_status'=>$discount_status ? '1':'0'
		);
		$productsQuery=tep_db_query("select * from ".TABLE_PWS_CATEGORIES_PRODUCTS." where products_id='$products_id'");
		if ($product=tep_db_fetch_array($productsQuery)){
			if ($product['pws_categories_products_status']=='1' && !$discount_status)
				tep_db_perform(TABLE_PWS_CATEGORIES_PRODUCTS,$sql_data_array,'update',"products_id=$products_id");
		}
		else{
			$sql_data_array['products_id']=$products_id;
			tep_db_perform(TABLE_PWS_CATEGORIES_PRODUCTS,$sql_data_array);
		}
	}
	//	@function adminJavascript
	//	@desc		Restituisce il codice javascript utilizzato nell'editing dei parametri
	function adminJavascript(&$pInfo){
?>
		function pwsInitCategoriesDiscount(){
			var tolisten;
<?			reset($this->price_editing_fields_chain);
			foreach($this->price_editing_fields_chain as $elementname){?>
			tolisten=$('<?=$elementname?>');
			pwsAttachEvent(tolisten,'change',pwsUpdateCategoriesDiscountPrice);
			pwsAttachEvent(tolisten,'blur',pwsUpdateCategoriesDiscountPrice);
			pwsAttachEvent(tolisten,'keyup',pwsUpdateCategoriesDiscountPrice);
<?			}?>
			pwsUpdateCategoriesDiscountPrice();
		}
		function pwsUpdateCategoriesDiscountPrice(){
			var categoriesstatus=$('pws_categories_products_status').checked;
			var pricenet_from=$('<?=$this->varname_products_price?>');
			var pricegross_from=$('<?=$this->varname_products_price_gross?>');
			var pricenet=$('<?=$this->varname_products_price_to?>');
			var pricegross=$('<?=$this->varname_products_price_gross_to?>');
			var discount=$('pws_categories_discount');
			discount=categoriesstatus ? parseFloat(discount.value) : 0.0;
			pricenet_from=parseFloat(pricenet_from.value);
			pricegross_from=parseFloat(pricegross_from.value);
			pricenet.value=doRound(pricenet_from*(1-discount/100.0),2);
			pricegross.value=doRound(pricegross_from*(1-discount/100.0),2);
		}
		function onCategoriesDiscountChange(){
			var pws_prices_categories_div=$("pws_prices_categories_div");
			var pws_categories_products_status = $("pws_categories_products_status");
			pws_prices_categories_div.style.display=pws_categories_products_status.checked ? 'block' : 'none';
<?	if ($this->plugin_using['pws_prices_specials']!=NULL){?>
			var specials_status=$('pws_specials_status');
			if (specials_status && specials_status.checked && pws_categories_products_status.checked){
				specials_status.checked=false;
				pwsSpecialsStatusChange();
			}
<?	}?>
<?	if ($this->plugin_using['pws_prices_quantities']!=NULL){?>
			var quantities_status=$('pws_quantities_status');
			if (quantities_status && quantities_status.checked && pws_categories_products_status.checked){
				quantities_status.checked=false;
				onQuantityDiscountsChange();
			}
<?	}?>
<?	if ($this->plugin_using['pws_prices_manufacturers']!=NULL){?>
			var manufacturers_status=$('pws_manufacturers_products_status');
			if (manufacturers_status && manufacturers_status.checked && pws_categories_products_status.checked){
				manufacturers_status.checked=false;
				onManufacturersDiscountChange();
			}
<?	}?>
			pwsUpdateCategoriesDiscountPrice();
		}
<?
	}
	//	@function adminStylesheet
	//	@desc		Restituisce il codice css utilizzato nell'editing dei parametri
	function adminStylesheet(&$pInfo){
		return '';//file_get_contents(DIR_FS_PWS_STYLESHEETS.'pws_categories_admin.css');
	}
	//	@function adminLoadProduct
	//	@desc		Carica nella struttura pInfo i propri dati relativi al prodotto
	function adminLoadProduct(&$pInfo){
		$categoriesQuery=tep_db_query("select * from ".TABLE_PRODUCTS_TO_CATEGORIES." p2c left join ".TABLE_PWS_CATEGORIES." pc on(p2c.categories_id=pc.categories_id) where p2c.products_id=".$pInfo->products_id);
		$categories=tep_db_fetch_array($categoriesQuery);
		$discount=$categories['pws_categories_discount']!=0.0?$categories['pws_categories_discount']:$categories['pws_categories_discount_parent'];
		$pInfo->pws_categories_discount=$discount;
		$productsQuery=tep_db_query("select pws_categories_products_status from ".TABLE_PWS_CATEGORIES_PRODUCTS." where products_id=".$pInfo->products_id);
		$product=tep_db_fetch_array($productsQuery);
		$pInfo->pws_categories_products_status=$product['pws_categories_products_status']=='1';
		return true;
	}
	//	@function adminEditProduct
	//	@desc		Crea il codice html per l'editing del prodotto, nella sezione admin
	//	@notes		Creazione del codice html da inserire nella form di editing del prodotto, nel lato amministrazione (file categories.php)
	function adminEditProduct($products_id,&$pInfo){
		if (true || $pInfo->pws_categories_discount!=0.0){
?>
	<tr bgcolor="#ebebff"><td colspan="2">
		 <fieldset><legend><input type="checkbox" id="pws_categories_products_status" name="pws_categories_products_status" value="1" <?=$pInfo->pws_categories_products_status? 'checked="true" ':''?> onClick="onCategoriesDiscountChange()"/><label for="pws_categories_products_status" class="pws_fieldset_label"><?=TEXT_PWS_CATEGORIES_PRODUCTS_STATUS?></label></legend>
		 <div id="pws_prices_categories_div" <?=$pInfo->pws_categories_products_status?'style="display:block"':'style="display:none"'?>>
			<table>
			 	<tr>
			 		<td class="main"><?=TEXT_PWS_CATEGORY_DISCOUNT?></td>
					<td class="main"><input type="text" readonly="true" class="pws_textfield_disabled" name="pws_categories_discount" id="pws_categories_discount" value="<?=$pInfo->pws_categories_discount?>"/>&nbsp; <b>%</b></td>
				</tr>
			 	<tr>
			 		<td class="main"><?=TEXT_PWS_CATEGORY_DISCOUNT_PRICE?></td>
					<td class="main"><input type="text" readonly="true" class="pws_textfield_disabled" name="<?=$this->varname_products_price_to?>" id="<?=$this->varname_products_price_to?>"/></td>
				</tr>
			 	<tr>
			 		<td class="main"><?=TEXT_PWS_CATEGORY_DISCOUNT_PRICE_GROSS?></td>
					<td class="main"><input type="text" readonly="true" class="pws_textfield_disabled" name="<?=$this->varname_products_price_gross_to?>" id="<?=$this->varname_products_price_gross_to?>"/></td>
				</tr>
			</table>
		</div></fieldset></td>
	</tr>
<?		}
	}

	function adminUpdateProduct($products_id){
		$productsQuery=tep_db_query("select pws_categories_products_status from ".TABLE_PWS_CATEGORIES_PRODUCTS." where products_id=".$products_id);
		$sql_data_array=array(
			'pws_categories_products_status'=>(isset($_REQUEST['pws_categories_products_status'])?'1':'0')
		);
		if ($product=tep_db_fetch_array($productsQuery))
			tep_db_perform(TABLE_PWS_CATEGORIES_PRODUCTS,$sql_data_array,'update',"products_id=$products_id");
		else{
			$sql_data_array['products_id']=$products_id;
			tep_db_perform(TABLE_PWS_CATEGORIES_PRODUCTS,$sql_data_array);
		}
		return true;
	}
	function adminDeleteProduct($products_id){
		tep_db_query("delete from ".TABLE_PWS_CATEGORIES_PRODUCTS." where products_id=$products_id");
		return true;
	}
	//	@function	adminCopyProduct
	//	@desc		Duplica i dati relativi ad un prodotto
	function adminCopyProduct($products_id,$dup_products_id){
		$sql_data_array=array(
			'products_id'=>$dup_products_id
			,'pws_categories_products_status'=>$this->getStatus($products_id)?'1':'0'
		);
		tep_db_perform(TABLE_PWS_CATEGORIES_PRODUCTS,$sql_data_array);
		return true;
	}

	//	@function	adminLoadCategory
	//	@desc		Carica le informazioni di questo plugin sulla struttura della categoria
	function adminLoadCategory($categories_id=NULL,&$cInfo,$parent_categories_id){
		if (!is_null($categories_id)){
			$query=tep_db_query("select pws_categories_discount, pws_categories_discount_parent from ".TABLE_PWS_CATEGORIES." where categories_id=$categories_id");
			$cInfo->objectInfo(tep_db_fetch_array($query));
		}else{
			$query=tep_db_query("select pws_categories_discount, pws_categories_discount_parent from ".TABLE_PWS_CATEGORIES." where categories_id=$parent_categories_id");
			$discount=tep_db_fetch_array($query);
			$discount= ($discount['pws_categories_discount']!=0) ? $discount['pws_categories_discount'] : $discount['pws_categories_discount_parent'];
			$cInfo->objectInfo(array('pws_categories_discount_parent'=>$discount));
			$cInfo->pws_categories_discount=0.0;
		}
	}
	function adminCategoriesJs(){
?>
<script type="text/javascript" language="javascript"><!--
		function pwsCategoriesInputStart(){
			var textfield = $("pws_categories_discount");
			textfield.style.color="black";
		}
		function pwsCategoriesInputStop(){
			var textfield = $("pws_categories_discount");
			var defaultvalue=$("pws_categories_discount_parent");
			if (textfield.value=='' || textfield.value==defaultvalue.value || isNaN(textfield.value) || parseFloat(textfield.value)==parseFloat(defaultvalue.value)){
				textfield.style.color="gray";
				textfield.value=defaultvalue.value;
			}else{
				textfield.style.color="black";
			}
		}
//--></script>
<?
	}
	//	@function	adminEditCategories
	//	@desc		Restituisce il codice per impostare lo sconto di categoria
	function adminEditCategories(){
		global $cInfo,$current_category_id;
		$this->adminCategoriesJs();
		$this->adminLoadCategory($cInfo->categories_id,&$cInfo,$current_category_id);
		$output='<br/>' . TEXT_PWS_CATEGORY_DISCOUNT_INPUT . '<br/><input type="text" id="pws_categories_discount" name="pws_categories_discount" value="'.($cInfo->pws_categories_discount!=0?$cInfo->pws_categories_discount:$cInfo->pws_categories_discount_parent).'" size="4" style="color:'.($cInfo->pws_categories_discount==0?'gray':'black').'" onClick="pwsCategoriesInputStart()" onFocus="pwsCategoriesInputStart()" onBlur="pwsCategoriesInputStop()"/><b>&nbsp;%</b>'
			.'<input type="hidden" name="pws_categories_discount_parent" id="pws_categories_discount_parent" value="'.$cInfo->pws_categories_discount_parent.'"/>';
		return $output;
	}
	//	@function	adminNewCategories
	//	@desc		Restituisce il codice per impostare lo sconto di categoria
	function adminNewCategories(){
		global $cInfo,$current_category_id;
		if (!isset($cInfo))
			$cInfo=new objectInfo(array());
		$this->adminCategoriesJs();
		$this->adminLoadCategory(NULL,&$cInfo,$current_category_id);
		$output='<br/>' . TEXT_PWS_CATEGORY_DISCOUNT_INPUT . '<br/><input type="text" id="pws_categories_discount" name="pws_categories_discount" value="'.$cInfo->pws_categories_discount_parent.'" size="4" style="color:gray" onClick="pwsCategoriesInputStart()" onFocus="pwsCategoriesInputStart()" onBlur="pwsCategoriesInputStop()"/><b>&nbsp;%</b>'
			.'<input type="hidden" name="pws_categories_discount_parent" id="pws_categories_discount_parent" value="'.$cInfo->pws_categories_discount_parent.'"/>';
		return $output;
	}
	//	@function	adminEditCategories
	//	@desc		Modifica i dati di una categoria
	function adminUpdateCategories(){
		global $categories_id;
		$pws_categories_discount=$_REQUEST['pws_categories_discount']?$_REQUEST['pws_categories_discount']:'0';
		$pws_categories_discount_parent=$_REQUEST['pws_categories_discount_parent']?$_REQUEST['pws_categories_discount_parent']:'0';
		tep_db_query("update ".TABLE_PWS_CATEGORIES." set pws_categories_discount=".($pws_categories_discount!=$pws_categories_discount_parent?$pws_categories_discount:'0.0').",pws_categories_discount_parent=$pws_categories_discount_parent where categories_id=$categories_id");
		$this->propagateCategoryDiscount($categories_id,$pws_categories_discount);
	}
	function getCategoryDiscount($categories_id){
		$categoriesQuery=tep_db_query("select * from ".TABLE_PWS_CATEGORIES." pc where pc.categories_id='$categories_id'");
		if ($categories=tep_db_fetch_array($categoriesQuery)){
			$discount=$categories['pws_categories_discount']!=0.0?$categories['pws_categories_discount']:$categories['pws_categories_discount_parent'];
			return $discount;
		}
		else
			return 0.0;
	}
	//	@function	adminInsertCategories
	//	@desc		Crea i dati per una nuova categoria
	function adminInsertCategories($catid=NULL){
		global $categories_id;
		$cID=is_null($catid) ? $categories_id : $catid;
		if (isset($_REQUEST['pws_categories_discount']))
			$pws_categories_discount=$_REQUEST['pws_categories_discount']?$_REQUEST['pws_categories_discount']:'0';
		else
			$pws_categories_discount='0';
		if (isset($_REQUEST['pws_categories_discount_parent']))
			$pws_categories_discount_parent=$_REQUEST['pws_categories_discount_parent']?$_REQUEST['pws_categories_discount_parent']:'0';
		else{
			$query=tep_db_query("select parent_id from ".TABLE_CATEGORIES." where categories_id='$cID'");
			$parent_id=tep_db_fetch_array($query);
			$parent_id=$parent_id['parent_id'];
			$pws_categories_discount_parent=$this->getCategoryDiscount($parent_id);
		}
		tep_db_query("insert into ".TABLE_PWS_CATEGORIES." set pws_categories_discount=".($pws_categories_discount!=$pws_categories_discount_parent?$pws_categories_discount:'0.0').",pws_categories_discount_parent=$pws_categories_discount_parent,categories_id=$cID");
	}
	//	@function	adminDisplayCategories
	//	@desc		Restituisce il codice per visualizzare lo sconto di una categoria
	function adminDisplayCategories(){
		global $cInfo;
		global $current_category_id;
		$this->adminLoadCategory($cInfo->categories_id,&$cInfo,$current_category_id);
		if ($cInfo->pws_categories_discount!=0.0){
			$output='<br/>'.TEXT_PWS_CATEGORY_DISCOUNT.$cInfo->pws_categories_discount.'&nbsp;%';

		}else{
			$output='<br/>'.TEXT_PWS_CATEGORY_DISCOUNT.$cInfo->pws_categories_discount_parent.'&nbsp;%'
				.'<br/>'.TEXT_PWS_CATEGORIES_INHERITED_DISCOUNT;
		}
		//$cdiscount=$cInfo->pws_categories_discount!=0.0?$cInfo->pws_categories_discount:$cInfo->pws_categories_discount_parent;
		return $output;
	}
	//	@function	adminDeleteCategories
	//	@desc		Elimina i dati per lo sconto di una categoria
	function adminDeleteCategories(){
		global $categories_id;
		tep_db_query("delete from ".TABLE_PWS_CATEGORIES." where categories_id=$categories_id");
	}
	//	@function	propagateCategoryDiscount
	//	@desc		Diffonde lo sconto di una categoria sulle categorie figlie
	//	@param		int	$categories_id
	//	@param		int	$pws_categories_discount
	function propagateCategoryDiscount($categories_id, $pws_categories_discount)
	{
		$query = tep_db_query("select c.categories_id, ce.pws_categories_discount from ".TABLE_CATEGORIES." c left join "
			.TABLE_PWS_CATEGORIES." ce on (c.categories_id=ce.categories_id) where c.parent_id=$categories_id");
		while (tep_not_null($cat=tep_db_fetch_array($query)))
		{
			if ($cat['pws_categories_discount']!=0)
				continue;
			$update_query = tep_db_query("update ".TABLE_PWS_CATEGORIES." set pws_categories_discount_parent=$pws_categories_discount where categories_id=".$cat['categories_id']);
			$this->propagateCategoryDiscount($cat['categories_id'], $pws_categories_discount);
		}
	}
	//	@function install
	//	@desc	Funzione di installazione del plugin
	function install(){
		$query=tep_db_query("select * from ".TABLE_CATEGORIES);
		while($category=tep_db_fetch_array($query)){
			$checkQuery=tep_db_query("select * from ".TABLE_PWS_CATEGORIES." where categories_id=".$category['categories_id']);
			if (!tep_db_num_rows($checkQuery))
				tep_db_query("insert into ".TABLE_PWS_CATEGORIES." set categories_id=".$category['categories_id']);
		}
		$query=tep_db_query("select * from ".TABLE_CATEGORIES);
		while($category=tep_db_fetch_array($query)){
			$checkQuery=tep_db_query("select * from ".TABLE_PWS_CATEGORIES." where categories_id=".$category['categories_id']);
			tep_db_query("update ".TABLE_PWS_CATEGORIES." set pws_categories_discount_parent='".$this->getCategoryDiscount($category['parent_id'])."'");
		}
		$quantities=$this->plugin_using['pws_prices_quantities'];
		$specials=$this->plugin_using['pws_prices_specials'];
		$query=tep_db_query("select * from ".TABLE_PRODUCTS);
		while($product=tep_db_fetch_array($query)){
			$status=true;
			if (NULL!=$specials)
				$status=$status && !$specials->statusOnProduct($product['products_id']);
			if (NULL!=$quantities)
				$status=$status && !$quantities->statusOnProduct($product['products_id']);
			$checkQuery=tep_db_query("select products_id from ".TABLE_PWS_CATEGORIES_PRODUCTS." where products_id=".$product['products_id']);
			if (!tep_db_num_rows($checkQuery))
				tep_db_query("insert into ".TABLE_PWS_CATEGORIES_PRODUCTS." set products_id=".$product['products_id'].", pws_categories_products_status='".($status?'1':'0')."'");
			else
				tep_db_query("update ".TABLE_PWS_CATEGORIES_PRODUCTS." set pws_categories_products_status='".($status?'1':'0')."' where products_id=".$product['products_id']);
		}
		return true;
	}
	//	@function update
	//	@desc	Funzione di aggiornamento del plugin
	//	@param	string $fromVersion		Vecchia versione
	function update($fromVersion)	{
		switch ($fromVersion){
			case '0.22':
			default:
				tep_db_query("alter table ".TABLE_PWS_CATEGORIES." modify column pws_categories_discount decimal(6,2) NOT NULL default '0.00'");
				tep_db_query("alter table ".TABLE_PWS_CATEGORIES." modify column pws_categories_discount_parent decimal(6,2) NOT NULL default '0.00'");
				break;
		}
	}
	///////////////////////////////////////////////////////////////////////////////////////////
	// Funzioni private
}


?>