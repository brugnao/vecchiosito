<?php
/*
 * @filename:	pws_products_selector.php
 * @version:	1.2
 * @project:	PWS
 *
 * @author:		Riccardo Roscilli <info@oscommerce.it>
 * @created:	04/giu/07
 * @modified:	04/giu/07 15:39:37
 *
 * @copyright:	2006-2007	Riccardo Roscilli @ PWS
 *
 * @desc:	Selettore dei prodotti nel product info
 *
 * @TODO:		
 */
define('TABLE_PWS_RELATED_PRODUCTS',TABLE_PWS_PREFIX.'related_products');
class	pws_products_selector	extends pws_plugin {
	// Variabili private
	var $max_products=10;
	var $pws_html=NULL;			// Indirizzo del plugin pws_html, se installato. Altrimenti NULL

// Variabili del plugin
	var $plugin_type='application';			// Tipo del plugin
	var $plugin_code='pws_products_selector';			// Codice univoco del plugin ( deve coincidere con il nome del file )
	var $plugin_configurable=false;	// Plugin di tipo configurabile ? (se true, comparirà nella lista dei moduli di tipo "sistema")
	var $plugin_name=TEXT_PWS_PRODUCTS_SELECTOR_NAME;		// Nome del plugin
	var $plugin_description=TEXT_PWS_PRODUCTS_SELECTOR_DESCRIPTION;	// Descrizione del plugin
	var $plugin_version_const='0.2';		// Versione del codice
	var $plugin_needs=array('pws_prices');	// Codici dei plugin da cui dipende=>istanza del plugin
	var	$plugin_conflicts=array();
	var	$plugin_editPage='';
	var $plugin_sort_order=2;
	var $plugin_configKeys=array();	// Chiavi di configurazione del plugin, in forma chiave=>array(field1=>value1, ..., fieldn=>valuen)
	var $plugin_tables=array(
		TABLE_PWS_RELATED_PRODUCTS=>"(
  `products_id` int( 11 ) default NULL,
  `to_products_id` int( 11 ) default NULL,
  `prodrel_order` tinyint(4) default NULL
)"
	);
	var $plugin_sql_install='';	// Codice sql da applicare in fase di installazione del plugin

	var $plugin_sql_remove='';	// Codice sql da applicare in fase di rimozione del plugin
	var $plugin_removable=false;	// Questo plugin non può essere rimosso e viene installato di default	

	// Definizione dei punti di intervento
	var	$plugin_hooks=array(
		'CATALOG_PRODUCT_INFO_JAVASCRIPT'=>'catalogJavascript'
		,'CATALOG_PRODUCT_INFO_SELECT_PRODUCTS'=>'catalogProductInfoSelectProducts'
		,'ADMIN_NEW_PRODUCT'=>'adminNewProduct'
		,'ADMIN_LOAD_PRODUCT'=>'adminLoadProduct'
		,'ADMIN_STYLESHEET'=>'adminStylesheet'
		,'ADMIN_CATEGORIES_JAVASCRIPT'=>'adminCategoriesJavascript'
		,'ADMIN_EDIT_PRODUCT'=>'adminEditProduct'
		//,'ADMIN_UPDATE_PRODUCT'=>'adminUpdateProduct'
		,'ADMIN_UPDATE_PRODUCT_SUBSECTION'=>'adminUpdateProduct'
		,'ADMIN_DELETE_PRODUCT'=>'adminDeleteProduct'
		,'ADMIN_COPY_PRODUCT'=>'adminCopyProduct'
		,'ADMIN_PRODUCTS_DISPLAY_BUTTONS'=>'adminProductsDisplayButtons'
	);
	//////////////////////////////////////////////////////////////////////
	// Funzioni
	function pws_products_selector(&$pws_engine){
		parent::pws_plugin(&$pws_engine);
	}

	function init(){
		parent::init();
		$this->pws_html = $this->_pws_engine->getPlugin('pws_html','application');
	}
	//	@function update
	//	@desc	Funzione di aggiornamento del plugin
	//	@param	string $fromVersion		Vecchia versione
	function update($fromVersion)	{
		switch ($fromVersion)	{
			case '0.1':
				if ($this->_pws_engine->tableExists('related_products'))
					tep_db_query("alter table related_products rename as ".TABLE_PWS_RELATED_PRODUCTS);
				break;
		}
		parent::update($this->version_const);
	}

	//	@function catalogStylesheet
	//	@desc	Restituisce il codice css utilizzato nel front end da tutti i plugin prezzi
	function	catalogStylesheet(){
		return '';
	}
	function catalogJavascript(){
?>
function add(num)	{
	var text=document.getElementById("quantity["+num+"]");
	var	val=parseInt(text.value);
	text.value=val+1;
}
function rem(num)	{
	var text=document.getElementById("quantity["+num+"]");
	var	val=parseInt(text.value);
	text.value=val-1>=0 ? val-1 : 0;
}
function popupWindowBig(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=860,height=600,screenX=150,screenY=150,top=150,left=150')
}
<?		
	}
	function catalogProductInfoSelectProducts(){
		global $language, $pid, $pws_prices;
		
		$products_id = $_REQUEST['products_id'];
		
		if($products_id=='')
			$products_id = $pid;
		
		//print $pws_prices->displayPrices();
		//print_r($GLOBALS['pws_prices']->displayPrices());
		if ($GLOBALS['pws_prices']->displayPrices()==false)
			// return '';
			$display_flag = 'false';
		$relprods=$this->getRelatedProducts(tep_get_prid($products_id));
		$skin=new pws_skin('pws_products_selector.htm');
	//	$skin=new pws_skin('product_listing.htm');
		$skin->set('button_in_cart',tep_image_submit('button_in_cart.gif', IMAGE_BUTTON_IN_CART));
		$skin->set('headers',array(
			''
			,TABLE_HEADING_RELATED_PRODUCTS_NAME
			,TABLE_HEADING_RELATED_PRODUCTS_PRICE
			,TABLE_HEADING_RELATED_PRODUCTS_QUANTITY
			)
		);
		$products=array();
		for($i=1,$row=2; $i<sizeof($relprods); $row++)	{
			if ($row==1){
				$products[]=array('row'=>$row);
				continue;
			}
			
			$product=$relprods[$i];
			$product['row']=$row;
		    if (($i/2) == floor($i/2)) {
    		    $product['class'] ='productListing-even';
   		   	} else {
  	 	        $product['class'] ='productListing-odd';
  		   	}
  		   	
			$checkQuery=tep_db_query("select products_id from ".TABLE_PRODUCTS_ATTRIBUTES." pa where pa.products_id=".$product['products_id']);
			$showSelection=$i==0 || 0==tep_db_num_rows($checkQuery);
			$imgplus=tep_image(DIR_WS_ICONS.'plus.gif',IMAGE_BUTTON_ADD,'','',"id=\"qtyadd_$i\" onclick=\"add($i)\" onmouseover=\"this.style.cursor='hand'\" onmouseout=\"this.style.cursor='pointer'\"");
			$imgminus=tep_image(DIR_WS_ICONS.'minus.gif',IMAGE_BUTTON_REMOVE,'','',"onclick=\"rem($i)\"  onmouseover=\"this.style.cursor='hand'\" onmouseout=\"this.style.cursor='pointer'\"");

			$product['showSelection']=$showSelection;
			// $product['products_price_display']=$product['products_price_display'].($product['discounts_info']?'<br/>'.$product['discounts_info']:'');
			//			$lc_text = '&nbsp;'.$pws_prices->getHtmlPriceWithDiscount($listing['products_id']);
			//   			$lc_text.=$pws_prices->getHtmlDiscountInfo($listing['products_id']);
		//	$product['products_price_display'] = $GLOBALS['pws_prices']->getHtmlPriceDiscounts((int)$product['products_id']);
			$product['products_price_display'] = $GLOBALS['pws_prices']->getHtmlPriceWithDiscount((int)$product['products_id']);
			$product['products_price_display'] .= $GLOBALS['pws_prices']->getHtmlDiscountInfo((int)$product['products_id']);
		//	$product['products_price_display'] .= $pws_prices->getHtmlDiscountInfo((int)$product['products_id']);
			$product['imgplus']=$imgplus;
			$product['imgminus']=$imgminus;
			$product['quantity_id']="quantity[$i]";
			$product['products_id_name']="products_id[$i]";
			
		//	if ($i==0)
		//		$product['products_image_thumb']='';
			
				$new_products = $product;
		//	$product['products_image'] = DIR_WS_IMAGES . $new_products['products_image'];
				
			$checkMultiImage = tep_db_query("select * from ".TABLE_PWS_PLUGINS." where plugin_code = 'pws_products_images'");
				   if(tep_db_num_rows($checkMultiImage) >= '1')  // è installato il plugin multimage?
			            {
		            	$image_query=tep_db_query("select * from ".TABLE_PWS_PRODUCTS_IMAGES." where products_id='" . $new_products['products_id'] . "' order by sort_order");
							
			            	if (tep_db_num_rows($image_query) >= '1') // modifico il primo lightbox (testo) se ci sono multi image
			            		$lc_text_np = '<a href="' . DIR_WS_IMAGES . $new_products['products_image'] . '" rel="lightbox[np'.$new_products['products_id']. ']"  title="Click on the left/right side of image" target="_blank">' . $GLOBALS['pws_html']->getHtmlProductsImage($new_products['products_id'], $new_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
							else 	
			            		$lc_text_np = '<a href="' . DIR_WS_IMAGES . $new_products['products_image'] . '" rel="lightbox[np' . $new_products['products_id']. ']"   target="_blank">' . $GLOBALS['pws_html']->getHtmlProductsImage($new_products['products_id'], $new_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
			            	
			            	
			            	while ($image_array = tep_db_fetch_array($image_query))
							{
								$lc_text_np .= '<a href="' . DIR_WS_IMAGES . $image_array['products_image'] . '" rel="lightbox[np' . $new_products['products_id']. ']"  title="Click on the left/right side of image" target="_blank"></a>';			
							}
			            }
			        else           
			        	$lc_text_np = '<a href="' . DIR_WS_IMAGES . $new_products['products_image'] . '" rel="lightbox[np' . $new_products['products_id']. ']" target="_blank">' . $GLOBALS['pws_html']->getHtmlProductsImage($new_products['products_id'], $new_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
			
			$product['products_image_lightbox'] .= $lc_text_np;				

			// $product['products_details_button']= '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $product['products_id']) . '">' .  tep_image_button('button_details.gif', IMAGE_BUTTON_DETAILS) . '</a>';	
				

			
			/// modifica qui per l'ajax button in cart
	
			        if (tep_has_product_attributes($product['products_id'])) {

			        	
			        	$lc_text = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $product['products_id']) . '">' . tep_image_button('button_details.gif', IMAGE_BUTTON_BUY_NOW) . '</a>&nbsp;';
                                }
                       else
                       {
                                 
                             if (AJAX_CART_ENABLED == 'false') //vecchia gestione carrello
                             {
                             
			        			$lc_text = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $product['products_id']) . '">' . tep_image_button('button_buy_now.gif', IMAGE_BUTTON_BUY_NOW) . '</a>&nbsp;';
                             
                             }
                             else 
						{	if (file_exists(DIR_WS_LANGUAGES . $language . '/images/buttons/button_in_cart_ajax.gif'))
								$button_in_cart_ajax = 'button_in_cart_ajax.gif';
							else  $button_in_cart_ajax = 'button_in_cart.png';
				        //   $lc_text = '<form name="cart_quantity" method="post" action="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now', 'NONSSL'). '">';
				            $lc_text = '<div><form action="">';
				            $lc_text .= '<input type="hidden" id="products_id' . $product['products_id'] . '" name="products_id" value="' . $product['products_id'] . '"><input type="text" id="quantity' . $product['products_id'] . '" name="quantity" value="1" maxlength="5" size="1" "></form>';
						// distinguo tra Firefox e IE che fa lo stronzo
						$http_user_agent = getenv('HTTP_USER_AGENT');
						if (strstr($http_user_agent,'MSIE')) 
				            $lc_text .= '<a href="#"><img border="0" src="'. DIR_WS_LANGUAGES . $language . '/images/buttons/' . $button_in_cart_ajax . '" alt="'. IMAGE_BUTTON_BUY_NOW . '" align="absbottom" id="cart-submit" onmousedown="loading( ' . $listing["products_id"] . ', \'loading\')" onmouseup="addtoCart( ' . $product["products_id"] . ' , document.getElementById(\'quantity' . $product['products_id'] . '\').value)" onMouseOut="loadCartItem( ' . $product["products_id"] . ', \'add\')"></a>';
						else 
							$lc_text .= '<img border="0" src="'. DIR_WS_LANGUAGES . $language . '/images/buttons/' . $button_in_cart_ajax . '" alt="'. IMAGE_BUTTON_BUY_NOW . '" align="absbottom" id="cart-submit" onmousedown="loading( ' . $product["products_id"] . ', \'loading\')" onmouseup="addtoCart( ' . $product["products_id"] . ' , document.getElementById(\'quantity' . $product['products_id'] . '\').value)" onMouseOut="loadCartItem( ' . $product["products_id"] . ', \'add\')">';
						//print "browser:" . $http_user_agent;
			
				            //    $lc_text .= '<a href=""><img border="0" src="'. DIR_WS_LANGUAGES . $language . '/images/buttons/' . $button_in_cart_ajax . '"> alt="'. IMAGE_BUTTON_BUY_NOW . '" align="absbottom" id="cart-submit" onmousedown="loading( ' . $listing["products_id"] . ', \'loading\')" onmouseup="addtoCart( ' . $listing["products_id"] . ' , document.getElementById(\'quantity' . $listing['products_id'] . '\').value)" onClick="loadCartItem( ' . $listing["products_id"] . ', \'add\')"></a>';
						//	$lc_text .= '<a href="">' . tep_image_button($button_in_cart_ajax, IMAGE_BUTTON_BUY_NOW) . '</a>';
				            $lc_text.= '<br><div class="productListing-data" id="cartquantity' . $product['products_id'] . '"></a></div>';
                                }
                                
                             }
                            // $list_box_contents[$cur_row]['rawdata']['buy_now'] = $lc_text;
			 $product['products_details_button'] = $lc_text;
			
			
			$products[]=$product;
			//print_r($products);
		
			$i++;
		}
		$skin->set('products',$products);
		
		return $skin->execute();
	}
	function hasRelatedProducts($products_id)
	{
		$products_id=tep_get_prid($products_id);
		$relprod_query="select * from ". TABLE_PWS_RELATED_PRODUCTS . " where products_id='".$products_id."' group by prodrel_order";
		$relprod_query=tep_db_query($relprod_query);
		return tep_db_num_rows($relprod_query)>0;
	}
	function get_related_products_ids($products_id,$padding=false)
	{
		$products_id=tep_get_prid($products_id);
		$products=array();
		if ($products_id!=0)	{
			$relprod_query="select * from ". TABLE_PWS_RELATED_PRODUCTS 
				. " where products_id='$products_id' order by prodrel_order";
			$relprod_query=tep_db_query($relprod_query);
			while (tep_not_null($prodid=tep_db_fetch_array($relprod_query))) {
				array_push($products,$prodid['to_products_id']);
			}
		}
		if ($padding)
			$products=array_pad($products,$this->max_products,0);
		return $products;
	}
	function getProductInfo($products_id){
		global	$pws_prices;
		global $customer_group_id,$currencies,$languages_id;
		$thumb_height=60;
		$thumb_width=70;
		$pinfo_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . $products_id . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
		if (!tep_db_num_rows($pinfo_query))
			return false;
		$pinfo = tep_db_fetch_array($pinfo_query);
	    $products_price=$pws_prices->getHtmlPriceWithDiscount($pinfo['products_id']);
		$pinfo['discounts_info']=$pws_prices->getHtmlDiscountInfo($pinfo['products_id']);
		$pinfo['products_price_display']=$products_price;
//	    $pinfo['products_image_thumb']="product_thumb.php?img=".DIR_WS_IMAGES.$pinfo['products_image']."&w=$thumb_width&h=$thumb_height";
//		$pinfo['products_image_thumb']=tep_image(DIR_WS_IMAGES.$pinfo['products_image'],'',$thumb_width,$thumb_height);
	    if (!is_null($this->pws_html)){
	    	$pinfo['products_image_thumb']=$this->pws_html->getThumbnailLocation($pinfo['products_image'],$thumb_width,$thumb_height);
	    }else{
			//$pinfo['products_image_thumb']=tep_image(DIR_WS_IMAGES.$pinfo['products_image'],'',$thumb_width,$thumb_height);
			$imgattrs=getThumbnail(urldecode(DIR_WS_IMAGES.$pinfo['products_image']),$thumb_width,$thumb_height);
			$pinfo['products_image_thumb']='<img src="'.$imgattrs['baseFileName'].'" border="0" />';
		}
    	$pinfo['products_info_url']='javascript:popupWindowBig(\'product_info.php?cPath='.tep_get_product_path($products_id)."&products_id=$products_id')";
//	    unset($pinfo['products_description']);
    	return $pinfo;
	}
////////////////////////////////////////////////////////////////////////////////////
// Front-end
	function getRelatedProducts($products_id, $includeMainProduct=true)
	{
		global $languages_id;
		$products_ids = $this->get_related_products_ids($products_id);
		array_unshift($products_ids, $products_id);
		$products = array();
		for ($i=0; $i<count($products_ids); $i++)
		{
			$product=$this->getProductInfo($products_ids[$i]);
//			$product['quantity_name_edit']="quantity_$i";
//			$product['products_id_name']="products_id_$i";
//			$product['product_url'] = 'product_info.php?cPath='.tep_get_product_path($prid)."&products_id=$prid";
			if ($product!==false)
				array_push($products, $product);
		}
		//print_r($products);
//		$skin = new Skin('related_products.htm');
//		$skin->set('numproducts',count($products));
//		$skin->set('buybutton',tep_image_submit('button_in_cart.gif', IMAGE_BUTTON_IN_CART));
//		$skin->set('firstProd',array_shift($products));
//		$skin->set('products',$products);
//		return html_entity_decode($skin->execute()).$jscript_code;
		return $products;
	}
	
	//	@function install
	//	@desc	Funzione di installazione del plugin
	function install(){
	
	}
/////////////////////////////////////////////////////////////////////////////////////
// Admin
	function adminProductsDisplayButtons(){
		global $cPath,$pInfo;
		return '<center><a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=new_product&subaction=edit_related') . '">'. tep_image_button('button_related.gif', IMAGE_RELATED) . '</a></center>';
	}
	function adminNewProduct(){
	}
	function adminLoadProduct(){
	}
	function adminStylesheet(){
	}
	function adminCategoriesJavascript(){
		if (isset($_REQUEST['action']) && $_REQUEST['action']=='new_product' && '1'=='0'){
			$products=$this->get_products_list(true,false);
?>
	var gd_relprods=new Array();
<?
		reset($products);
		foreach ($products as $pid=>$pdata)	{
?>			
			gd_relprods[<?=$pid?>]=[
				'<?=rawurlencode(html_entity_decode($pdata['products_name']))?>',
				'<?=html_entity_decode($pdata['products_price'])?>',
			];
<?		}	?>
	
	function ProdRelChanged(numrelprod)
	{
	
		var relprodid=document.getElementById("gd_relprod_id_"+numrelprod);
		var relprodhidid=document.getElementById("gd_relprod_id_hid_"+numrelprod);
//		var relprodname=document.getElementById("gd_relprod_name_"+numrelprod);
		var relprodprice=document.getElementById("gd_relprod_price_"+numrelprod);
		var relprod=gd_relprods[relprodid.value];
		relprodhidid.value=relprodid.value;
//		relprodname.value = unescape(relprod[0]);
		relprodprice.value = relprod[1];
	}
	function SelRelProd(numrelprod,pid)
	{
		var relprodid=document.getElementById("gd_relprod_id_"+numrelprod);
		relprodid.value=pid;
		ProdRelChanged(numrelprod);
	}
<?		}
	}
	function adminEditProduct(){
		global $pInfo;
		$products_id=$pInfo->products_id;
//		echo "products_id:$products_id<br/>";
		$products=$this->get_products_list(true,false);
		$relproducts=$this->get_related_products_ids($products_id,true);
		?>
<tr>
	<td class="main" colspan="2">
	<input type="hidden" name="subaction" value="set_related"/>
	<input type="hidden" name="action" value="update_product"/>
    <fieldset style="width:20%">
		<legend class="gd_fieldset_label"><?=TITLE_RELATED_PRODUCTS_MODULE.': <b>'.$pInfo->products_name.'</b>'?></legend>
		<table class="gd_relatedproducts_table" id="gd_relprod_table" cellpadding="1" cellspacing="0" style="border:thin #A8CECE">
		<tr>
			<th><?=TABLE_HEADING_RELATED_PRODUCTS_NAME?></th>
			<th></th>
		</tr>
<?	for($i=0; $i<$this->max_products; $i++) {
//		if(isset($products[$relproducts[$i]]))
			$relprodid = isset($_REQUEST["gd_relprod_id_hid_$i"]) ? $_REQUEST["gd_relprod_id_hid_$i"] : $relproducts[$i];
//		else
//			$relprodid = 0;
		$relprod = $products[$relprodid];
?>
		<tr>
		<td>
			<select name="gd_relprod_id_<?=$i?>" id="gd_relprod_id_<?=$i?>" >
<?
		//reset($products);
		foreach ($products as $pid=>$pdata){
?>
				<option value="<?=$pid?>" <?=$relprodid==$pid ? 'selected' : ''?>><?=htmlentities(html_entity_decode($pdata['products_name'].' [ '.$pdata['products_model'].' ]',ENT_QUOTES),ENT_QUOTES)?></option>
<?		} ?>
			</select>
			<input type="hidden" name="gd_relprod_id_hid_<?=$i?>" id="gd_relprod_id_hid_<?=$i?>" value="<?=$relprodid?>"/>
		</td>
	
		</tr>
<?	}?>		
		</table>
	</fieldset>

	</td>
</tr>
<?
	}
	function adminUpdateProduct(){
		global $products_id;
		if (isset($_REQUEST['subaction']) && $_REQUEST['subaction']!='set_related')
		{
			return;
		}

		tep_db_query("delete from ". TABLE_PWS_RELATED_PRODUCTS . " where products_id='$products_id'");
		$prodrel_order=0;

		for ($i=0; $i<$this->max_products;$i++)
		{

			if (isset($_REQUEST["gd_relprod_id_$i"]) && ($_REQUEST["gd_relprod_id_$i"]) >= 1)	{


			tep_db_query("insert " .TABLE_PWS_RELATED_PRODUCTS." set products_id='$products_id', to_products_id='" . $_REQUEST["gd_relprod_id_$i"] . "'");
											//	print_r($_REQUEST);

			}
		}
	}
	function adminDeleteProduct(){
		global $product_id;
		$prid=tep_get_prid($product_id);
		tep_db_query("delete from ". TABLE_PWS_RELATED_PRODUCTS . " where products_id='$prid'");
		tep_db_query("delete from ". TABLE_PWS_RELATED_PRODUCTS . " where to_products_id='$prid'");
	}
	function adminCopyProduct(){
		global $products_id,$dup_products_id;
		tep_db_query("delete from ".TABLE_PWS_RELATED_PRODUCTS." where products_id=$dup_products_id");
		$relprods_query = tep_db_query("select to_products_id from ". TABLE_PWS_RELATED_PRODUCTS . " where products_id='".$products_id."' group by prodrel_order");
		$prodrel_order=0;
		while ($relprodid = tep_db_fetch_array($relprods_query))
		{
			$relprodid = $relprodid['to_products_id'];
			tep_db_query("insert " .TABLE_PWS_RELATED_PRODUCTS." set products_id='$dup_products_id', to_products_id='$relprodid', prodrel_order='".($prodrel_order++)."'");		
		}
	}
	function get_products_list($null_product=true,$raw=false)
	{
		global $currencies,$languages_id;
		$products=array();
		if ($null_product)
		{
			$products[0]= array(
					'products_model'=>'Nessun Prodotto',
					'products_name' =>'Nessun prodotto selezionato'
			//		'products_price'=>'-'
			);
		}
		$products_query = tep_db_query("select p.products_id, p.products_model from ". TABLE_PRODUCTS. " p  "
		." where p.products_status = '1'");
		while ($product = tep_db_fetch_array($products_query))
		{
			$product_info = tep_get_row(TABLE_PRODUCTS_DESCRIPTION, "products_id", $product['products_id']);
		
			$products[$product['products_id']] =  array(
//				'products_model'=>htmlentities(html_entity_decode($product['products_model'],ENT_QUOTES),ENT_QUOTES),
//				'products_name'=>htmlentities(html_entity_decode($product['products_name'],ENT_QUOTES),ENT_QUOTES),
				'products_model'=>$product['products_model'],
				'products_name'=>$product_info['products_name'],
			//	'products_price'=>($raw ? $product['products_price'] : $currencies->display_price($product['products_price'],tep_get_tax_rate($product['products_tax_class_id'])))
			);
		}
		return $products;
	}
	
}
?>