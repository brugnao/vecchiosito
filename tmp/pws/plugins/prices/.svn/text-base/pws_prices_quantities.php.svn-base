<?php
/*
 * @filename:	pws_prices_quantities.php
 * @version:	0.23
 * @project:	PWS
 *
 * @author:		Riccardo Roscilli <info@oscommerce.it>
 * @created:	23/mag/07
 * @modified:	06/giu/08 16:32:02
 *
 * @copyright:	2006-2007	Riccardo Roscilli @ PWS
 *
 * @desc:	
 *
 * @TODO:		
 */

define('TABLE_PWS_QUANTITIES',TABLE_PWS_PREFIX.'quantities');
define('TABLE_PWS_QUANTITIES_STATUS',TABLE_PWS_PREFIX.'quantities_status');

class	pws_prices_quantities	extends pws_plugin_price {
	// Variabili private
	var $idCount=0;	
	// Variabili del plugin
	var $plugin_code='pws_prices_quantities';			// Codice univoco del plugin ( deve coincidere con il nome del file )
	var $plugin_name=TEXT_PWS_QUANTITIES_NAME;		// Nome del plugin
	var $plugin_description=TEXT_PWS_QUANTITIES_DESCRIPTION;	// Descrizione del plugin
	var $plugin_version_const='0.23';		// Versione del codice
	var	$plugin_excludes=array('pws_prices_specials'); //,'pws_prices_manufacturers','pws_prices_categories');		// Contiene i plugin_code dei plugin che vanno esclusi quando viene attivato questo plugin
	var $plugin_needs=array('pws_prices_specials'); //,'pws_prices_manufacturers'); //,'pws_prices_categories');		// Codici dei plugin da cui dipende=>istanza del plugin
//	var	$plugin_conflicts=array('pws_prices_customers_groups','pws_prices_categories','pws_prices_manufacturers');
// modifica per abilitare il b2b con scoti per quantit�
	var	$plugin_conflicts=array('pws_prices_categories','pws_prices_manufacturers');
	var	$plugin_editPage='';
	var $plugin_sort_order=4;
	var $plugin_configKeys=array(
		'PWS_PRICES_QUANTITIES_NUM_STRIPS'=>array(
				'configuration_title'=>TEXT_PWS_PRICES_QUANTITIES_NUM_STRIPS
				,'configuration_value'=>'3'
				,'configuration_description'=>TEXT_PWS_PRICES_QUANTITIES_NUM_STRIPS_DESC
				,'sort_order'=>'1'
			)
	);	// Chiavi di configurazione del plugin, in forma chiave=>array(field1=>value1, ..., fieldn=>valuen)
	var	$plugin_config=array();	// Lista chiave di configurazione=>valore corrispondente
	var $plugin_tables=array(
		TABLE_PWS_QUANTITIES=>"
(
  products_id int(11) NOT NULL default '0',
  pws_quantities_discount decimal(6,2) NOT NULL default '0.00',
  pws_quantities_min smallint(5) default NULL,
  pws_quantities_max smallint(5) default NULL,
  KEY  (products_id)
)"
		,TABLE_PWS_QUANTITIES_STATUS=>"
(
  products_id int(11) NOT NULL default '0',
  pws_quantities_status enum('0','1') NOT NULL default '1',
  UNIQUE KEY  (products_id)
)"
	);	// Tables utilizzate dal plugin
	var $plugin_sql_install='';	// Codice sql da applicare in fase di installazione del plugin

	var $plugin_sql_remove='';	// Codice sql da applicare in fase di rimozione del plugin
	
	
	
	
	
	function pws_prices_quantities(&$pws_engine){
		parent::pws_plugin_price(&$pws_engine);
	}

	function init(){
		parent::init();
	}
	//	@function catalogStylesheet
	//	@desc	Restituisce il codice css utilizzato nel front end da tutti i plugin prezzi
	function	catalogStylesheet(){
//		return @file_get_contents(DIR_FS_PWS_STYLESHEETS.'pws_prices_quantities_info.css');
		return '<link rel="stylesheet" type="text/css" href="'.DIR_WS_PWS_STYLESHEETS.'pws_prices_quantities_info.css'.'"/>'."\r\n";
	}
	//	@function	hasQuantityDiscounts
	//	@desc		Restituisce true se sul prodotto sono presenti sconti quantità
	//	@param		int		$products_id		Id del prodotto da controllare
	//	@return		bool						true se esistono sconti quantità, false altrimenti
	function hasQuantityDiscounts($products_id){
		$query=tep_db_query("select * from ".TABLE_PWS_QUANTITIES." q left join ".TABLE_PWS_QUANTITIES_STATUS." qs on (q.products_id=qs.products_id) where q.products_id=$products_id and qs.pws_quantities_status");
		return tep_db_num_rows($query);
	}
	//	@function	getDiscountPercentage
	//	@desc		Restituisce la percentuale di sconto presente su un prodotto
	//	@param		int		$products_id		Id del prodotto per cui calcolare la percentuale di scontoo
	//	@param		int		$products_quantity	Numero colli
	//	@param		int		$customers_id		Id del cliente per cui generare la percentuale di sconto
	//	@return		float						la percentuale di sconto
	function getDiscountPercentage($products_id, $products_quantity=1, $customers_id=NULL)	{
		$quantitiesQuery=tep_db_query("select * from ".TABLE_PWS_QUANTITIES." q left join ".TABLE_PWS_QUANTITIES_STATUS." qs on (qs.products_id=q.products_id) where qs.pws_quantities_status='1' and q.products_id=$products_id and $products_quantity>=pws_quantities_min and $products_quantity<=pws_quantities_max");
		if ($quantities=tep_db_fetch_array($quantitiesQuery)){
			$discount=$quantities['pws_quantities_discount'];
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
		$quantitiesQuery=tep_db_query("select * from ".TABLE_PWS_QUANTITIES." q left join ".TABLE_PWS_QUANTITIES_STATUS." qs on (qs.products_id=q.products_id) where qs.pws_quantities_status='1' and q.products_id=$products_id and $products_quantity>=pws_quantities_min and $products_quantity<=pws_quantities_max");
		if ($quantities=tep_db_fetch_array($quantitiesQuery)){
			$discount=$quantities['pws_quantities_discount'];
			return $products_price*(1-$discount/100.0);
		}else
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
		$quantitiesQuery=tep_db_query("select MAX(pws_quantities_discount) as pws_quantities_discount from ".TABLE_PWS_QUANTITIES." where products_id=$products_id");
		if ($quantities=tep_db_fetch_array($quantitiesQuery)){
			$discount=$quantities['pws_quantities_discount'];
			return $products_price*(1-$discount/100.0);
		}else
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
		$quantitiesQuery=tep_db_query("select * from ".TABLE_PWS_QUANTITIES." q left join ".TABLE_PWS_QUANTITIES_STATUS." qs on (qs.products_id=q.products_id) where qs.pws_quantities_status='1' and q.products_id=$products_id and $products_quantity>=pws_quantities_min and $products_quantity<=pws_quantities_max");
		if ($quantities=tep_db_fetch_array($quantitiesQuery)){
			$discount=$quantities['pws_quantities_discount'];
			$products_price*=(1-$discount/100.0);
			return array('text'=>TEXT_PWS_PRICES_QUANTITIES_COMPACT_TITLE
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
		global $pws_prices;
		$quantitiesStatus=tep_db_query("select pws_quantities_status from ".TABLE_PWS_QUANTITIES_STATUS." where products_id=".$products_id);
		$pws_quantities_status=($quantitiesStatus=tep_db_fetch_array($quantitiesStatus))?$quantitiesStatus['pws_quantities_status']:'0';
		if ($pws_quantities_status=='0')
			return NULL;
		$quantitiesQuery=tep_db_query("select pws_quantities_min, pws_quantities_max, pws_quantities_discount from ".TABLE_PWS_QUANTITIES." where products_id=".$products_id." order by pws_quantities_min");
		$quantities=array();
		$i=0;
		while ($i++<PWS_PRICES_QUANTITIES_NUM_STRIPS && $strip=tep_db_fetch_array($quantitiesQuery)){
			array_push ($quantities , array(
				'qty_start'=>max(1,$strip['pws_quantities_min']).'+&nbsp;'.TEXT_PWS_PRICES_QUANTITIES_NUM_PIECES_COMPACT
				,'discount'=>'-&nbsp;'.$pws_prices->formatPercentage($strip['pws_quantities_discount'])
				,'final_price'=>$pws_prices->formatPrice($products_price*(1-$strip['pws_quantities_discount']/100.0),$products_id)
			));
		}
		$skin=new pws_skin('pws_quantities_info.htm', $template_dir);
		$skin->set('condition',false);
		$skin->set('strips',$quantities);
		return array('text'=>TEXT_PWS_PRICES_QUANTITIES_COMPACT_TITLE
			,'content'=>$skin->execute()
			,'tax_exempt'=>false
		);
	}

	//	@function catalogJavascript
	//	@desc	Restituisce il codice javascript utilizzato in product_info.php di catalog
	function	catalogJavascript(){
?>
var pwsQuantitiesFollowupDivId;
function mouseX(evt) {if (!evt) evt = window.event; if (evt.pageX) return evt.pageX; else if (evt.clientX)return evt.clientX + (document.documentElement.scrollLeft ?  document.documentElement.scrollLeft : document.body.scrollLeft); else return 0;}
function mouseY(evt) {if (!evt) evt = window.event; if (evt.pageY) return evt.pageY; else if (evt.clientY)return evt.clientY + (document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop); else return 0;}
function follow(evt) {
	var obj = $(pwsQuantitiesFollowupDivId).style;
	var width = Element.getWidth(pwsQuantitiesFollowupDivId);
	var height = Element.getHeight(pwsQuantitiesFollowupDivId);
	obj.visibility = 'visible';
	obj.left = (parseInt(mouseX(evt))-(width/2)) + 'px';
	obj.top = (parseInt(mouseY(evt))-height-10) + 'px';
}
function pwsQuantitiesDivOn(divid,onoff){
	var thediv=$(divid);
	var thetag=$(divid+'tag');
	thediv.style.display=onoff ? 'inline':'none';
	if (onoff){
		pwsQuantitiesFollowupDivId=divid;
		document.onmousemove=follow;
	}
	else
		document.onmousemove='';
}
<?
	}
	//	@function	getHtmlDiscountInfo
	//	@desc		Restituisce un avviso (in html) per la presenza di uno sconto (se c'�) sul prodotto passato come parametro
	//	@param		int		$products_id		Id del prodotto per cui generare l'avviso
	//	@param		float	$products_price		Prezzo	precedente del prodotto
	//	@return		string					Codice html
	function	getHtmlDiscountInfo($products_id,&$products_price){
		global $pws_prices;
		$quantitiesStatus=tep_db_query("select pws_quantities_status from ".TABLE_PWS_QUANTITIES_STATUS." where products_id=".$products_id);
		$pws_quantities_status=($quantitiesStatus=tep_db_fetch_array($quantitiesStatus))?$quantitiesStatus['pws_quantities_status']:'0';
		if ($pws_quantities_status=='0')
			return NULL;
		$quantitiesQuery=tep_db_query("select pws_quantities_min, pws_quantities_max, pws_quantities_discount from ".TABLE_PWS_QUANTITIES." where products_id=".$products_id." order by pws_quantities_min");
		$quantities=array();
		$i=0;
		while ($i++<PWS_PRICES_QUANTITIES_NUM_STRIPS && $strip=tep_db_fetch_array($quantitiesQuery)){
			array_push ($quantities , array(
				'qty_start'=>max(1,$strip['pws_quantities_min']).'+&nbsp;'.TEXT_PWS_PRICES_QUANTITIES_NUM_PIECES_COMPACT
				,'discount'=>'-&nbsp;'.$pws_prices->formatPercentage($strip['pws_quantities_discount'])
				,'final_price'=>$pws_prices->formatPrice($products_price*(1-$strip['pws_quantities_discount']/100.0),$products_id)
			));
		}
		$this->idCount++;
		$skin=new pws_skin('pws_quantities_infotag.htm');
		$skin->set('strips',$quantities);
		$skin->set('tagtitle',TEXT_PWS_PRICES_QUANTITIES_COMPACT_TITLE);
		$skin->set('divid','pws_quantities_div_'.$this->idCount);
		$skin->set('tagid','pws_quantities_div_'.$this->idCount.'tag');
		$skin->set('mouseon','this.style.cursor=\'hand\';pwsQuantitiesDivOn(\'pws_quantities_div_'.$this->idCount.'\',true)');
		$skin->set('mouseoff','this.style.cursor=\'pointer\';pwsQuantitiesDivOn(\'pws_quantities_div_'.$this->idCount.'\',false)');
		return $skin->execute();
	}
	//	@function	statusOnProduct
	//	@param		int		$products_id		Id del prodotto su cui è richiesto lo stato del plugin
	//	@return 	bool				Stato del plugin
	function statusOnProduct($products_id){
		$quantitiesStatus=tep_db_query("select pws_quantities_status from ".TABLE_PWS_QUANTITIES_STATUS." where products_id=".$products_id);
		$pws_quantities_status=($quantitiesStatus=tep_db_fetch_array($quantitiesStatus))?$quantitiesStatus['pws_quantities_status']:'0';
		return $pws_quantities_status=='1';
	}
	//	@function	adminDisableOnProduct
	//	@param		int		$products_id		Id del prodotto su cui disabilitare eventuali offerte
	//	@return		bool						Restituisce true se è stato disabilitato uno sconto
	function adminDisableOnProduct($products_id){
		$quantitiesStatus=tep_db_query("select pws_quantities_status from ".TABLE_PWS_QUANTITIES_STATUS." where products_id=".$products_id);
		$pws_quantities_status=($quantitiesStatus=tep_db_fetch_array($quantitiesStatus))?$quantitiesStatus['pws_quantities_status']:'0';
		if ($pws_quantities_status=='1'){
			tep_db_query("update ".TABLE_PWS_QUANTITIES_STATUS." set pws_quantities_status='0' where products_id=$products_id");
			return true;
		}else
			return false;
	}
	//	@function	getStatus
	//	@desc	Restituisce true se il plugin applica qualche modifica al prezzo
	//	@param	int	$products_id
	function	getStatus($products_id){
		$check_query=tep_db_query("select * from ".TABLE_PWS_QUANTITIES_STATUS." where products_id='".$products_id."' and pws_quantities_status");
		return tep_db_num_rows($check_query)>0;
	}
	//	@function adminNewProduct
	//	@desc		Crea nella struttura pInfo i propri dati relativi al prodotto
	function adminNewProduct(&$pInfo){
		$pInfo->objectInfo(array(
			'pws_quantities_status'=>'0'
			,'pws_quantities_strips'=>array()
			,'pws_quantities_num_strips'=>0
		));
		return true;
	}
	//	@function adminNewProductSetDefault
	//	@desc		Crea i valori di default relativi ad un plugin prezzo, per un nuovo prodotto
	function	adminNewProductSetDefault($products_id){
		$discount_status=parent::getStatus($products_id) && $this->hasQuantityDiscounts($products_id);
		$sql_data_array=array(
			'pws_quantities_status'=>$discount_status ? '1':'0'
		);
		$productsQuery=tep_db_query("select * from ".TABLE_PWS_QUANTITIES_STATUS." where products_id='$products_id'");
		if ($product=tep_db_fetch_array($productsQuery)){
			if ($product['pws_quantities_status']=='1' && !$discount_status)
				tep_db_perform(TABLE_PWS_QUANTITIES_STATUS,$sql_data_array,'update',"products_id=$products_id");
		}else{
			$sql_data_array['products_id']=$products_id;
			tep_db_perform(TABLE_PWS_QUANTITIES_STATUS,$sql_data_array);
		}
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
		for ($i=0; $i<PWS_PRICES_QUANTITIES_NUM_STRIPS; $i++){
			$this->varname_products_price_to[]="pws_quantities_price_$i";
			$this->varname_products_price_gross_to[]="pws_quantities_price_gross_$i";
			$fields_chain[]="pws_quantities_discount_$i";
		}
		$varname_products_price=$this->varname_products_price_to[0];
		$varname_products_price_gross=$this->varname_products_price_gross_to[0];
	}
	//	@function adminJavascript
	//	@desc		Restituisce il codice javascript utilizzato nell'editing dei parametri
	function adminJavascript(&$pInfo){
?>
var pws_quantities_totstrips = <?=$pInfo->pws_quantities_num_strips ? $pInfo->pws_quantities_num_strips:'0'?>;
var pws_quantities_oldtotstrips;
function initDiscounts()
{
			var tolisten;
<?			reset($this->price_editing_fields_chain);
			foreach($this->price_editing_fields_chain as $elementname){?>
			tolisten=$('<?=$elementname?>');
			pwsAttachEvent(tolisten,'change',updateQuantitiesDiscounts);
			pwsAttachEvent(tolisten,'blur',updateQuantitiesDiscounts);
			pwsAttachEvent(tolisten,'keyup',updateQuantitiesDiscounts);
<?			}?>
	updateQuantitiesDiscounts();
}

function checkStripsLimits(numstrip,stoporstart,finalcheck)
{
	var i,t;
	var ss,st,pss=null,pst=null;
	var prevstart,prevstop,curstart,curstop;
	var checkOtherWay=false;
	if (!finalcheck)
		return;
	if (stoporstart==false)
	{//start changed for numstrip
		if (numstrip>pws_quantities_totstrips-1) return;
		switch (numstrip)
		{
<?php
		for ($i=PWS_PRICES_QUANTITIES_NUM_STRIPS-1; $i>=0; $i--)	{
?>
			case <?=$i?>:
				ss=$('pws_quantities_min_<?=$i?>');
				st=$('pws_quantities_max_<?=$i?>');
				curstart=parseInt(ss.value);
				curstop=parseInt(st.value);
				if (numstrip==<?=$i?>)
				{// Inizio propagazione

<?php if (false && $i==0){?>
					alert("non va!");
					curstart=0;
					ss.value='*';
					break;
<?php } else { ?>

					if (curstart < <?=$i?> || isNaN(curstart))
					{
						if (finalcheck)
						{
							t=$('pws_quantities_max_<?=($i-1)>0?$i-1:0?>').value;
							t=parseInt(t);
							if (!isNaN(t))
								curstart=t+1;
							else
								curstart=<?=$i?>;//'*';
							ss.value=curstart;
						}
						else
						{
							alert('Intervallo '+<?=$i?>+'\nInserire un numero intero positivo');
							break;
						}
					}
					if (!isNaN(curstart) && !isNaN(curstop) && curstop < curstart)
					{
						if (<?=$i?> >= pws_quantities_totstrips-1)
							st.value=curstop='*';
						else
						{
							t = parseInt($('pws_quantities_min_<?=($i+1)?>').value);
							checkOtherWay=(!isNaN(t) && curstart >= t) || isNaN(t);
							
							if (false==(checkOtherWay))
								st.value=curstop=t-1;
							else
							{
								st.value=curstop=curstart;
								checkStripsLimits(numstrip,true,finalcheck);
							}
						}
					}
					else if (<?=$i?> >= pws_quantities_totstrips-1)
						st.value=curstop='*';
<?php }?>
				}
				else
				{
					//alert("prevstart="+prevstart+",curstart="+curstart);
					if (isNaN(prevstart))
						curstop=st.value='*';
					else
						st.value=curstop=prevstart-1;
				}
<?php	if ($i==0) { ?> ss.value=numstrip!=<?=$i?> && !isNaN(curstop) && curstart>curstop ? curstop:curstart;ss.hiddenval=curstart;
				st.hiddenval=curstop;
				/*ss.value='*';*/ <?php } else { ?>
				t=parseInt($('pws_quantities_max_<?=($i-1)>=0?$i-1:0?>').value);
				if (!isNaN(curstop) && curstart > curstop)
					ss.value=curstart=(!isNaN(t) && t < curstop)?t+1:curstop;
				ss.hiddenval=curstart;
				st.hiddenval=curstop;
				pss=ss;
				pst=st;
				prevstart=curstart;
				prevstop=curstop;
<?php }}?>
			default:
		}
	}
	else
	{// stop changed for numstrip
		//if (numstrip > pws_quantities_totstrips-1) return;
		switch (numstrip)
		{

<?php	for ($i=0; $i<PWS_PRICES_QUANTITIES_NUM_STRIPS; $i++){?>


			case <?=$i?>:
				ss=$('pws_quantities_min_<?=$i?>');
				st=$('pws_quantities_max_<?=$i?>');
				curstart=parseInt(ss.value);
				curstop=parseInt(st.value);
				if (numstrip==<?=$i?>)
				{// Inizio propagazione

<?php if ($i==PWS_PRICES_QUANTITIES_NUM_STRIPS-1){?>
					st.value=curstop='*';
					break;
<?php } else { ?>

					if (curstop < <?=$i?> || (isNaN(curstop) && <?=$i?>!=pws_quantities_totstrips-1))
					{
						if (finalcheck)
						{
							t=$('pws_quantities_min_<?=($i+1)?>').value;
							t=parseInt(t);
							if (!isNaN(t))
								curstop=t-1;
							else
								curstop='*';
							st.value=curstop;
						}
						else
						{
							alert('Intervallo '+<?=$i?>+'\nInserire un numero intero positivo.');
							break;
						}
					}
					else if (<?=$i?> >= pws_quantities_totstrips-1)
					{
						st.value=curstop='*';
					}

					if (!isNaN(curstop) && !isNaN(curstart) && curstop < curstart)
					{
<? if ($i==0)	{ ?> ss.value=curstart=curstop; <? } else { ?>
						t = parseInt($('pws_quantities_max_<?=$i-1?>').value);
						checkOtherWay=(!isNaN(t) && t >= curstop) || isNaN(t);
						if (false==(checkOtherWay))
							ss.value=curstart=t+1;
						else
						{
							ss.value=curstart=curstop;
							checkStripsLimits(numstrip,false,finalcheck);
						}
<?php }?>
					}
<?php }?>
				}
				else
				{
					if (isNaN(prevstop))
						curstart=ss.value='*';
					else
						ss.value=curstart=prevstop+1;
				}
<?php	if ($i==PWS_PRICES_QUANTITIES_NUM_STRIPS-1) { ?> st.hiddenval=curstop;ss.hiddenval=curstart;st.value='*'; <?php } else { ?>
				t=parseInt($('pws_quantities_min_<?=$i+1?>').value);
				if (!isNaN(curstart) && curstop < curstart)
					st.value=curstop=(!isNaN(t) && curstart < t)?t-1:curstart;
				ss.hiddenval=curstart;
				st.hiddenval=curstop;
				if (<?=$i?> >= pws_quantities_totstrips-1)
					st.value=curstop='*';

				pss=ss;
				pst=st;
				prevstart=curstart;
				prevstop=curstop;
<?php }}?>
			default:
		}
	}
}

function onQuantityDiscountsChange()
{
	var onoff=$('pws_quantities_status').checked;

	if (onoff)
	{
<?	if ($this->plugin_using['pws_prices_specials']!=NULL){
?>
		var specials_status=$('pws_specials_status');
		if (specials_status && specials_status.checked){
			specials_status.checked=false;
			pwsSpecialsStatusChange();
		}
<?
	}
?>
<?	if ($this->plugin_using['pws_prices_categories']!=NULL){?>
			var pws_categories_products_status=$('pws_categories_products_status');
			if (pws_categories_products_status && pws_categories_products_status.checked){
				pws_categories_products_status.checked=false;
				onCategoriesDiscountChange();
			}
<?	}?>
<?	if ($this->plugin_using['pws_prices_manufacturers']!=NULL){?>
			var manufacturers_status=$('pws_manufacturers_products_status');
			if (manufacturers_status && manufacturers_status.checked){
				manufacturers_status.checked=false;
				onManufacturersDiscountChange();
			}
<?	}?>
		if (pws_quantities_oldtotstrips>0)
			updateNumStrips(pws_quantities_oldtotstrips);
	}
	else
	{
		pws_quantities_oldtotstrips=pws_quantities_totstrips;
		updateNumStrips(0);
	}
	hideShowQuantityDiscounts(onoff);
	updateQuantitiesDiscounts();
}
function hideShowQuantityDiscounts(onoff)
{
	var pws_quantities_div=$("pws_quantities_div");
	pws_quantities_div.style.display= onoff ? 'block' : 'none';

}
function updateQuantitiesDiscounts(){
	var quantitiesstatus=$('pws_quantities_status').checked;
	var grossValue = $('<?=$this->varname_products_price_gross?>').value;
	var webValue = $('<?=$this->varname_products_price?>').value;

<?php
	for ($i=0; $i<PWS_PRICES_QUANTITIES_NUM_STRIPS; $i++)
	{
		
?>
		if (quantitiesstatus){
			if (<?=$i?> < pws_quantities_totstrips){
			  $('pws_quantities_price_<?=$i?>').value = doRound(webValue*(1-($('pws_quantities_discount_<?=$i?>').value/100.0)), 2);
			  $('pws_quantities_price_gross_<?=$i?>').value = doRound(grossValue*(1-($('pws_quantities_discount_<?=$i?>').value/100.0)), 2);
			}
			else
			{
			  $('pws_quantities_price_<?=$i?>').value = '-';
			  $('pws_quantities_price_gross_<?=$i?>').value = '-';
			}
		}else{
			if (<?=$i?> < pws_quantities_totstrips){
			  $('pws_quantities_price_<?=$i?>').value = webValue;
			  $('pws_quantities_price_gross_<?=$i?>').value = grossValue;
			}
			else
			{
			  $('pws_quantities_price_<?=$i?>').value = '-';
			  $('pws_quantities_price_gross_<?=$i?>').value = '-';
			}
		}
<?php			
	}
?>
}

function updateNumStrips(totstrips)
{
	var i,t;
	var ss,st,sd,sdp,hss,hst;
	var curstart, curstop, prevstart, prevstop;
	pws_quantities_totstrips=totstrips;

	$('pws_quantities_num_strips').value=totstrips;
	curstart=parseInt($('pws_quantities_min_0').value);
	curstop=parseInt($('pws_quantities_max_0').value);
	if (isNaN(curstart))
		curstart=0;
	if (isNaN(curstop))
		curstop=0;
	prevstart=-2;
	prevstop=-1;

<?php
	for ($i=0; $i<PWS_PRICES_QUANTITIES_NUM_STRIPS; $i++)
	{
?>
		ss=$('pws_quantities_min_<?=$i?>');
		st=$('pws_quantities_max_<?=$i?>');
		sd=$('pws_quantities_discount_<?=$i?>');
		if (!ss.hiddenval)
			ss.hiddenval=!isNaN(parseInt(ss.value)) ? ss.value : prevstop+1;
		if (!st.hiddenval)
			st.hiddenval=!isNaN(parseInt(st.value)) ? st.value : ss.hiddenval;
		hss=parseInt(ss.hiddenval);
		hst=parseInt(st.hiddenval);
		curstart=parseInt(ss.value);
		curstop=parseInt(st.value);
		if (isNaN(curstart) || (!isNaN(curstart) && curstart<=prevstop))
		{
			//if (<?=$i?>==0) alert("prima:"+curstart+",prevstop="+prevstop);
			ss.hiddenval=ss.value=curstart=isNaN(hss) || (!isNaN(hss) && hss <= prevstop) ? prevstop+1 : hss;
			//if (<?=$i?>==0) alert("dopo:"+curstart);
		}
		if (isNaN(curstop) || (!isNaN(curstop) && curstop < curstart))
			st.hiddenval=st.value=curstop=isNaN(hst) || (!isNaN(hst) && hst < curstart) ? curstart : hst;
		prevstart=curstart;
		prevstop=curstop;
		if (<?=$i?> >= totstrips)
		{
			ss.disabled=true;
			st.disabled=true;
			sd.disabled=true;
			ss.value=st.value='*';
		}
		else
		{
			ss.disabled=false;
			st.disabled=false;
			sd.disabled=false;
			if (<?=$i?> == totstrips-1)
			{
				st.value='*';
				st.disabled=true;
			}
		}

<?php
	}
?>
/*	if (totstrips>0)
		checkStripsLimits(totstrips-1,false,true);
	else
	{
		st.disabled=true;
		sd.disabled=true;
	}
*/
	$('pws_quantities_status').checked=$('pws_quantities_status').checked && totstrips>0;
	if (totstrips==0)
		hideShowQuantityDiscounts(false);

	updateQuantitiesDiscounts();
}


<?
	}
	//	@function adminStylesheet
	//	@desc		Restituisce il codice css utilizzato nell'editing dei parametri
	function adminStylesheet(&$pInfo){
		return file_get_contents(DIR_FS_PWS_STYLESHEETS.'pws_quantities_admin.css');
	}
	//	@function adminEditProduct
	//	@desc		Crea il codice html per l'editing del prodotto, nella sezione admin
	//	@notes		Creazione del codice html da inserire nella form di editing del prodotto, nel lato amministrazione (file categories.php)
	//	@function adminLoadProduct
	//	@desc		Carica nella struttura pInfo i propri dati relativi al prodotto
	function adminLoadProduct(&$pInfo){
		$quantitiesStatus=tep_db_query("select pws_quantities_status from ".TABLE_PWS_QUANTITIES_STATUS." where products_id=".$pInfo->products_id);
		$pws_quantities_status=($quantitiesStatus=tep_db_fetch_array($quantitiesStatus))?$quantitiesStatus['pws_quantities_status']:'0';
		$quantitiesQuery=tep_db_query("select pws_quantities_min, pws_quantities_max, pws_quantities_discount from ".TABLE_PWS_QUANTITIES." where products_id=".$pInfo->products_id." order by pws_quantities_min");
		$quantities=array();
		$i=0;
		while ($i++<PWS_PRICES_QUANTITIES_NUM_STRIPS && $strip=tep_db_fetch_array($quantitiesQuery)){
			if ($strip['pws_quantities_max']==0)
				$strip['pws_quantities_max']='*';
			if ($strip['pws_quantities_min']==65535)
				$strip['pws_quantities_min']='*';
			$strip['pws_quantities_price']=$pInfo->products_price*(1-$strip['pws_quantities_discount']);
			array_push ($quantities , $strip);
		}
		$pInfo->objectInfo(array(
			'pws_quantities_status'=>$pws_quantities_status
			,'pws_quantities_strips'=>$quantities
			,'pws_quantities_num_strips'=>sizeof($quantities)));
		return true;
	}
	function adminEditProduct($products_id,&$pInfo)
	{
		$qdiscounts_on=$pInfo->pws_quantities_status=='1' && $pInfo->pws_quantities_num_strips!=0;
		$skin = new pws_skin('pws_quantities_admin_edit.htm', NULL, true);
//		$skin->set('pws_quantities_status', sprintf(TEXT_PWS_QUANTITIES_OFFER_FLAG,'<input type="checkbox" class="pws_fieldset_label" id="pws_quantities_status" name="pws_quantities_status" value="1" '. ($pInfo->pws_quantities_status && $pInfo->pws_quantities_num_strips!=0 ? 'checked' : '').' onClick="onQuantityDiscountsChange()"/>'));
		$skin->set('pws_quantities_status', $qdiscounts_on);
		$skin->set('panelstyle',$qdiscounts_on ? 'display:block' : 'display:none');
		$headingrow = array (
			TEXT_PWS_QUANTITIES_DISCOUNT_STRIPS_NUM,
			TEXT_PWS_QUANTITIES_DISCOUNT_START,
			TEXT_PWS_QUANTITIES_DISCOUNT_STOP,
			TEXT_PWS_QUANTITIES_DISCOUNT_PERCENTAGE,
		//	TEXT_PWS_QUANTITIES_DISCOUNT_PRICE_GROSS,
			TEXT_PWS_QUANTITIES_DISCOUNT_PRICE_WEB,
			TEXT_PWS_QUANTITIES_DISCOUNT_PRICE_GROSS
		);
		$skin->set('headingsarray', $headingrow);
		$strips=array();
		$strip = array(
			'numstrip_onchange'=>"updateNumStrips(0)",
			'void'=>'',
			'first'=>true,
			'numstrip_checked'=>$pInfo->pws_quantities_num_strips==0
		);
		array_push($strips,$strip);
		$strip['first']=false;
		$pstrips=&$pInfo->pws_quantities_strips;
		for ($i=0, $laststop=-1; $i<PWS_PRICES_QUANTITIES_NUM_STRIPS; $i++, $laststop=$strip['qty_stop'],array_push($strips, $strip))
		{
			$pstrip = isset($pstrips[$i]) ? $pstrips[$i]
					: array(
					'pws_quantities_min'=>max($i,$laststop+1),
					'pws_quantities_max'=>($i==PWS_PRICES_QUANTITIES_NUM_STRIPS)?'*':max($i,$laststop+1),
					'discount'=>0);
			$strip['numstrip_checked'] = $pInfo->pws_quantities_num_strips-1==$i;
			$strip['numstrip_onchange'] = "updateNumStrips(".($i+1).")";
			$strip['start_id'] = $strip['start_name'] = "pws_quantities_min_$i";
			$strip['qty_start'] = $pstrip['pws_quantities_min']!='*' ? $pstrip['pws_quantities_min'] : 0;
			$strip['start_onkeyup'] = "checkStripsLimits($i, false, false)";
			$strip['start_onblur'] = "checkStripsLimits($i, false, true)";
			$strip['stop_id'] = $strip['stop_name'] = "pws_quantities_max_$i";
			$strip['qty_stop'] = $pstrip['pws_quantities_max'];
			$strip['stop_onkeyup'] = "checkStripsLimits($i, true, false)";
			$strip['stop_onblur'] = "checkStripsLimits($i, true, true)";
			$strip['discount_id'] = $strip['discount_name'] = "pws_quantities_discount_$i";
			$strip['discount'] = $pstrip['pws_quantities_discount'];
			$strip['dpriceweb_id'] = $strip['dpriceweb_name'] = "pws_quantities_price_$i";
			$strip['dpricegross_id'] = $strip['dpricegross_name'] = "pws_quantities_price_gross_$i";
		}
		$skin->set('text_pws_quantities_status',TEXT_PWS_QUANTITIES_STATUS);
		$skin->set('strips', $strips);
		$this->js_edit_product_init='updateNumStrips('.$pInfo->pws_quantities_num_strips.');initDiscounts();';
		return $skin->execute();
	}

	function adminUpdateProduct($products_id){
		tep_db_query("delete from ".TABLE_PWS_QUANTITIES_STATUS." where products_id=$products_id");
		tep_db_query("delete from ".TABLE_PWS_QUANTITIES." where products_id=$products_id");

		$pws_quantities_status=isset($_REQUEST['pws_quantities_status']) && $_REQUEST['pws_quantities_status']=='1' && $_REQUEST['pws_quantities_num_strips'];
		$numstrips = $_REQUEST['pws_quantities_num_strips'];
		//var_dump($numstrips);exit;
		tep_db_query("insert into ".TABLE_PWS_QUANTITIES_STATUS." set products_id=$products_id, pws_quantities_status='".($pws_quantities_status?'1':'0')."'");
		for ($i=0;$i < $numstrips; $i++)
		{
			$limit_min = $_REQUEST["pws_quantities_min_$i"];
			$limit_max = $_REQUEST["pws_quantities_max_$i"];
			$discount = (float) $_REQUEST["pws_quantities_discount_$i"];
			if ($limit_min == '*' || is_null($limit_min))
				$limit_min = 0;
			else
				$limit_min = (int)$limit_min;

			if ($limit_max == '*' || is_null($limit_max))
				$limit_max = 65535;
			else
				$limit_max = (int)$limit_max;

			$strip = array(
				'products_id'=>$products_id,
				'pws_quantities_min'=>$limit_min,
				'pws_quantities_max'=>$limit_max,
				'pws_quantities_discount'=>$discount
			);
			tep_db_perform(TABLE_PWS_QUANTITIES, $strip);
		}
		return true;
	}
	function adminDeleteProduct($products_id){
	    tep_db_query("delete from " . TABLE_PWS_QUANTITIES . " where products_id = '" . (int)$products_id . "'");
	    tep_db_query("delete from " . TABLE_PWS_QUANTITIES_STATUS . " where products_id = '" . (int)$products_id . "'");
		return true;
	}
	//	@function	adminCopyProduct
	//	@desc		Duplica i dati relativi ad un prodotto
	function adminCopyProduct($products_id,$dup_products_id){

		$quantitiesStatus=tep_db_query("select pws_quantities_status from ".TABLE_PWS_QUANTITIES_STATUS." where products_id=' " . $products_id. "'");

		$pws_quantities_status=($quantitiesStatus=tep_db_fetch_array($quantitiesStatus))?$quantitiesStatus['pws_quantities_status']:'0';
		if (!$pws_quantities_status) $pws_quantities_status='0';
		tep_db_query("insert into ".TABLE_PWS_QUANTITIES_STATUS." set products_id=$dup_products_id, pws_quantities_status='$pws_quantities_status'");
		$quantitiesQuery=tep_db_query("select pws_quantities_min, pws_quantities_max, pws_quantities_discount from ".TABLE_PWS_QUANTITIES." where products_id=".$products_id." order by pws_quantities_min");
		$i=0;
		while ($i++<PWS_PRICES_QUANTITIES_NUM_STRIPS && $strip=tep_db_fetch_array($quantitiesQuery)){
			$strip['products_id']=$dup_products_id;
			tep_db_perform(TABLE_PWS_QUANTITIES,$strip);
		}
	}
	
	//	@function install
	//	@desc	Funzione di installazione del plugin
	//	@notes	Converte gli speciali
	function install(){
		$productsQuery=tep_db_query("select products_id from ".TABLE_PRODUCTS);
		while ($product=tep_db_fetch_array($productsQuery)){
			$checkQuery=tep_db_query("select products_id from ".TABLE_PWS_QUANTITIES_STATUS." where products_id=".$product['products_id']);
			if (!tep_db_num_rows($checkQuery))
				tep_db_query("insert into ".TABLE_PWS_QUANTITIES_STATUS." set products_id=".$product['products_id'].", pws_quantities_status='0'");
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
				tep_db_query("alter table ".TABLE_PWS_QUANTITIES." modify column pws_quantities_discount decimal(6,2) NOT NULL default '0.00'");
				break;
		}
	}
	
	///////////////////////////////////////////////////////////////////////////////////////////
	// Funzioni private
}


?>
