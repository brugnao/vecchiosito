<?php
/*
 * @filename:	pws_products_images.php
 * @version:	1.00
 * @project:	PWS
 *
 * @author:		Riccardo Roscilli
 *
 * @created:	16/nov/07
 * @modified:	16/nov/07 17:01:51
 *
 * @copyright:	2006-2007	Riccardo Roscilli @ PWS
 *
 * @desc:	Editing e gestioni di immagini aggiuntive dei prodotti
 *
 * @TODO:		
 */
if (defined('DIR_FS_CATALOG_IMAGES'))
	require_once DIR_FS_CATALOG.'includes/functions/thumbnails.php';
else
	require_once DIR_WS_FUNCTIONS.'thumbnails.php';

define('TABLE_PWS_PRODUCTS_IMAGES',TABLE_PWS_PREFIX.'products_images');
define('FILENAME_POPUP_ADDITIONAL_IMAGE',DIR_WS_PWS.'pws_additional_image_popup.php');

class	pws_products_images extends pws_plugin {
// Variabili private
	var	$noimg;					// url dell'immagine del thumbnail dell'immagine che indica nessuna immagine disponibile/selezionata
	var $images_directory;		// path assoluto della directory images del catalogo
// Variabili del plugin
	var $plugin_type='application';			// Tipo del plugin
	var $plugin_code='pws_products_images';			// Codice univoco del plugin ( deve coincidere con il nome del file )
	var $plugin_configurable=false;	// Plugin di tipo configurabile ? (se true, comparirà nella lista dei moduli di tipo "sistema")
	var $plugin_name=TEXT_PWS_PRODUCTS_IMAGES_NAME;		// Nome del plugin
	var $plugin_description=TEXT_PWS_PRODUCTS_IMAGES_DESCRIPTION;	// Descrizione del plugin
	var $plugin_version_const='0.2';		// Versione del codice
	var $plugin_needs=array('pws_html');	// Codici dei plugin da cui dipende=>istanza del plugin
	var	$plugin_conflicts=array();
	var	$plugin_editPage='';
	var $plugin_sort_order=3;
	var $plugin_configKeys=array(
//		'PWS_PRODUCTS_IMAGES_MAX_IMAGES_NUM'=>array(
//				'configuration_title'=>TEXT_PWS_PRODUCTS_IMAGES_MAX_IMAGES_NUM
//				,'configuration_value'=>'10'
//				,'configuration_description'=>TEXT_PWS_PRODUCTS_IMAGES_MAX_IMAGES_NUM_DESC
//				,'sort_order'=>'1'
//			)
		);	// Chiavi di configurazione del plugin, in forma chiave=>array(field1=>value1, ..., fieldn=>valuen)
	var $plugin_tables=array(
		TABLE_PWS_PRODUCTS_IMAGES	=>"(
  `products_id` int(11) NOT NULL default '0',
  `products_image` varchar(255) NOT NULL default '',
  `sort_order` int(2) default NULL
)"
 	);
	var $plugin_sql_install='';	// Codice sql da applicare in fase di installazione del plugin

	var $plugin_sql_remove='';	// Codice sql da applicare in fase di rimozione del plugin
	var $plugin_removable=false;	// Questo plugin non può essere rimosso e viene installato di default	

	// Definizione dei punti di intervento
	var	$plugin_hooks=array(
		'CATALOG_PRODUCT_INFO_JAVASCRIPT'=>'catalogProductInfoJavascript'
		,'CATALOG_PRODUCT_INFO_SLIDESHOW'=>'catalogProductInfoSlideshow'
		,'CATALOG_PRODUCT_INFO_STYLESHEET'=>'catalogProductInfoStylesheet'
		,'ADMIN_STYLESHEET'=>'adminStylesheet'
		,'ADMIN_CATEGORIES_HEAD'=>'adminCategoriesHead'
		,'ADMIN_UPDATE_PRODUCT'=>'adminUpdateProduct'
		,'ADMIN_COPY_PRODUCT'=>'adminCopyProduct'
		,'ADMIN_LOAD_PRODUCT'=>'adminLoadProduct'
		,'ADMIN_NEW_PRODUCT'=>'adminNewProduct'
		,'ADMIN_EDIT_PRODUCTS_IMAGES'=>'adminEditProductsImages'
		,'ADMIN_PRODUCTS_DISPLAY'=>'adminProductsDisplay'
		,'ADMIN_DELETE_PRODUCT'=>'adminDeleteProduct'
	);
	//////////////////////////////////////////////////////////////////////
	// Funzioni
	function pws_products_images(&$pws_engine){
		parent::pws_plugin(&$pws_engine);
	}

	function init(){
		parent::init();
		if (is_object($this->_pws_engine))
			$this->admin_side=$this->_pws_engine->isAdminSideRunning();
		else
			$this->admin_side=defined('DIR_WS_ADMIN') && false!==strpos($GLOBALS['PHP_SELF'],DIR_WS_ADMIN);
		if ($this->admin_side)
			$this->images_directory=DIR_FS_CATALOG_IMAGES;
		else
			$this->images_directory=DIR_FS_CATALOG.DIR_WS_IMAGES;

		$this->noimg=getThumbnail($this->images_directory.PWS_HTML_NO_IMAGE_LOCATION,SMALL_IMAGE_WIDTH,SMALL_IMAGE_HEIGHT);
		$this->noimg='../'.$this->noimg['baseFileName'];
	}

/////////////////////////////////////////////////////////////////////////////////////
// Catalog
	//	@function catalogStylesheet
	//	@desc	Restituisce il codice css utilizzato nel front end da tutti i plugin prezzi
	function	catalogProductInfoStylesheet(){
//		global $_GET;
//		$products_id=$_GET['products_id'];
//		$query=tep_db_query("select products_image from ".TABLE_PWS_PRODUCTS_IMAGES." where products_id='$products_id' order by sort_order asc");
//		if (!tep_db_num_rows($query))
//			return '';
		$output='<link rel="stylesheet" type="text/css" href="'.DIR_WS_PWS_STYLESHEETS.'pws_products_images_product_info.css'.'"/>'."\r\n";
		
//		$filename=DIR_WS_PWS_STYLESHEETS.'pws_products_images_product_info.css';
//		$thumbheight=SMALL_IMAGE_HEIGHT+10;
//		$output = "<style type=\"text/css\">".@file_get_contents($filename)
//			."
//#pwsAdditionalImagesSlideshow{
//	float:left;
//}
//</style>";
		return $output;
	}
	function	catalogProductInfoJavascript(){
	
	}
	function	productHasAdditionalImages($products_id){
		return tep_db_num_rows(tep_db_query("select count(*) from ".TABLE_PWS_PRODUCTS_IMAGES." where products_id='$products_id'"))>0;
	}
	function	catalogProductInfoSlideshow(){
		global $_GET,$pws_html;
		$products_id=$_GET['products_id'];
		if (!$this->productHasAdditionalImages($products_id))
			return '';
		$query=tep_db_query("select products_image,sort_order from ".TABLE_PWS_PRODUCTS_IMAGES." where products_id='$products_id' order by sort_order asc");
		if (!tep_db_num_rows($query))
			return '';
		$images=array();
		while ($image=tep_db_fetch_array($query)){
			
			$lk_lightbox = '<a href="' . DIR_WS_IMAGES . $image['products_image'] . '" rel="lightbox[' . $products_id. ']"  title="Click on the left/right side of image" target="_blank"><img border="0" src="' . $pws_html->getThumbnailLocation($image['products_image'],SMALL_IMAGE_WIDTH,SMALL_IMAGE_HEIGHT) . '"></a>';

			//print $pws_html->getThumbnailLocation($image['products_image'],SMALL_IMAGE_WIDTH,SMALL_IMAGE_HEIGHT).'<br>';
	

	
	
			$images[]=array(
				'src'=>$image['products_image']
				,'thumbsrc'=>$pws_html->getThumbnailLocation($image['products_image'],SMALL_IMAGE_WIDTH,SMALL_IMAGE_HEIGHT) //$imgthumb
				,'alt'=>$image['products_image']
				,'onclick'=>'popupWindow(\'' . tep_href_link(FILENAME_POPUP_ADDITIONAL_IMAGE, 'pID=' . $products_id.'&s='.$image['sort_order']) . '\')'
				,'products_image_lightbox' => $lk_lightbox
				);
		
		
			
			
			//	$images['products_image_lightbox']=>$lk_lightbox;
				
			
		}
		$skin=new pws_skin('pws_products_images_slideshow.htm');
		$skin->set('images',$images);
		
		return $skin->execute();
	}
	function catalogProductInfoSlideshowFlash($products_id=NULL,$nojs=false){
		global $_GET,$pws_html;
		if (is_null($products_id))
			$products_id=$_GET['products_id'];
		if (!$this->productHasAdditionalImages($products_id))
			;//return '';
		$xmlpath=urlencode(HTTP_SERVER.DIR_WS_CATALOG.DIR_WS_PWS.'pws_additional_images_xml.php?pID='.$products_id);
		$swfpath=HTTP_SERVER.DIR_WS_CATALOG.'pws/ssp';
		if (!$nojs){
			$output = <<<EOT
<script language="javascript">
	if (AC_FL_RunContent == 0) {
		alert("This page requires AC_RunActiveContent.js.");
	} else {
		AC_FL_RunContent(
			'getparams', 'xmlpath=$xmlpath',
			'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0',
			'width', '800',
			'height', '600',
			'src', 'pws/ssp',
			'quality', 'high',
			'pluginspage', 'http://www.macromedia.com/go/getflashplayer',
			'align', 'middle',
			'play', 'true',
			'loop', 'true',
			'scale', 'showall',
			'wmode', 'window',
			'devicefont', 'false',
			'id', 'pws/ssp',
			'bgcolor', '#ffffff',
			'name', 'pws/ssp',
			'menu', 'true',
			'allowFullScreen', 'true',
			'allowScriptAccess','sameDomain',
			'movie', '$swfpath',
			'salign', ''
			); //end AC code
	}
</script>
<noscript>
	<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="800" height="600" id="pws/ssp" align="middle">
	<param name="allowScriptAccess" value="sameDomain" />
	<param name="allowFullScreen" value="true" />
	<param name="movie" value="$swfpath.swf?xmlpath=$xmlpath" />
	<param name="quality" value="high" /><param name="bgcolor" value="#ffffff" />
	<embed src="$swfpath.swf?xmlpath=$xmlpath" quality="high" bgcolor="#ffffff" width="800" height="600" name="$swfpath" align="middle" allowScriptAccess="sameDomain" allowFullScreen="true" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
	</object>
</noscript>
EOT;
		}else{
			$output=<<<EOT
	<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="800" height="600" id="pws/ssp" align="middle">
	<param name="allowScriptAccess" value="sameDomain" />
	<param name="allowFullScreen" value="true" />
	<param name="movie" value="$swfpath.swf?xmlpath=$xmlpath" /><param name="quality" value="high" /><param name="bgcolor" value="#ffffff" />
	<embed src="$swfpath.swf?xmlpath=$xmlpath" quality="high" bgcolor="#ffffff" width="800" height="600" name="$swfpath" align="middle" allowScriptAccess="sameDomain" allowFullScreen="true" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
	</object>
EOT;
		}
		return $output;
	}
/////////////////////////////////////////////////////////////////////////////////////
// Admin
	function adminStylesheet(){
?>
.pwsProductsImagesEditScrollablePan{
	width:650px;
	min-width:650px;
	max-width:650px;
	border:1px dotted navy;
	background:#efefff;
	padding:5px 5px 5px 5px;
	height:<?=(SMALL_IMAGE_HEIGHT+42)*2?>px;
	min-height:<?=(SMALL_IMAGE_HEIGHT+42)*2?>px;
	max-height:<?=(SMALL_IMAGE_HEIGHT+42)*4?>px;
	overflow-y:scroll;
}
.pwsProductsImagesPreviewScrollablePan{
	width:<?=SMALL_IMAGE_WIDTH+12?>px;
	min-width:<?=SMALL_IMAGE_WIDTH+12?>px;
	max-width:<?=SMALL_IMAGE_WIDTH+12?>px;
	height:<?=(SMALL_IMAGE_HEIGHT+12)*3?>px;
	min-height:<?=(SMALL_IMAGE_HEIGHT+12)*3?>px;
	max-height:<?=(SMALL_IMAGE_HEIGHT+12)*3?>px;
	overflow-y:scroll;
	border:1px dotted navy;
	background:#eeeeee;
	padding:5px 5px 5px 5px;
}
#pwsProductsImagePreview{
	margin-bottom:10px;
	border:1px dotted #aaaaaa;
}
.pws_additionalImagePickerContainer{
	float:left;
	margin-left:10px;
	border-left:1px dotted #ccbbbb;
	//border:1px dotted black;
	height:<?=SMALL_IMAGE_HEIGHT+24?>;
	width:<?=SMALL_IMAGE_WIDTH+24?>;
	text-align:center;
}
.pws_additionalImagePickerPlacehold{
	float:left;
	border:1px dotted #aaaaaa;
	width:<?=SMALL_IMAGE_WIDTH?>px;
	min-width:<?=SMALL_IMAGE_WIDTH?>px;
	max-width:<?=SMALL_IMAGE_WIDTH?>px;
	height:<?=SMALL_IMAGE_HEIGHT?>px;
	min-height:<?=SMALL_IMAGE_HEIGHT?>px;
	max-height:<?=SMALL_IMAGE_HEIGHT?>px;
}
.pws_additionalImagePickerThumbnail{
	border:1px dotted #aaaaaa;
	max-width:<?=SMALL_IMAGE_WIDTH?>px;
	max-height:<?=SMALL_IMAGE_HEIGHT?>px;

}
<?
	}
	function adminCategoriesHead(){
		global $pws_html;

// Codice

?>
<script language="javascript1.5" type="text/javascript"><!--
	var pwsAdditionalImagesCounter=0;
	var pwsAdditionalImagesCurrent=0;
	var pwsAdditionalImagesNewElement=null;
	function drawAdditionalImagePickerPlacehold(number,onlyinnerhtml){
		var imgid="products_images["+pwsAdditionalImagesCounter+"]";
		var sortorder_id="products_images_sort_order["+pwsAdditionalImagesCounter+"]";
		//var oldsortorder_id="products_images_sort_order_old["+pwsAdditionalImagesCounter+"]";
		var container_id="products_images_container["+pwsAdditionalImagesCounter+"]";
		var imgthumb='<?=$this->noimg?>';
		var imgsrc='';
		var containercode='<div class="pws_additionalImagePickerContainer" id="'+container_id+'">';
		var code='<div class="pws_imageButtonsPanel"><img src="<?=DIR_WS_ICONS.'icon_new.gif'?>" class="pwsImagePickerButton" onclick="pwsAdditionalImageAdd('+pwsAdditionalImagesCounter+')"/><img src="<?=DIR_WS_ICONS.'icon_delete.gif'?>" class="pwsImagePickerButton" onclick="pwsAdditionalImageDelete('+pwsAdditionalImagesCounter+')"/><img src="<?=DIR_WS_ICONS.'icon_order_left.gif'?>" class="pwsImagePickerButton" onclick="pwsAdditionalImageMoveLeft('+pwsAdditionalImagesCounter+')"/><img src="<?=DIR_WS_ICONS.'icon_order_right.gif'?>" class="pwsImagePickerButton" onclick="pwsAdditionalImageMoveRight('+pwsAdditionalImagesCounter+')"/></div><div class="pws_additionalImagePickerPlacehold"><center><img class="pws_additionalImagePickerThumbnail" id="'+imgid+'_thumb" src="'+imgthumb+'" alt="<?=tep_db_input(TEXT_CLICK_TO_SELECT_IMAGE)?>" title="<?=tep_db_input(TEXT_CLICK_TO_SELECT_IMAGE)?>" onclick="pwsLaunchImagePicker(\''+imgid+'\')"/></center></div><input type="hidden" id="'+sortorder_id+'" name="'+sortorder_id+'" value="'+number+'"/><input type="hidden" id="'+imgid+'" name="'+imgid+'" value="'+imgsrc+'"/>';
		
		pwsAdditionalImagesCounter++;
		return (onlyinnerhtml) ? code : containercode+code+'</div>';
	}
	function pwsAdditionalImageSetImages(number, src, thumbsrc){
		var img=$("products_images["+number+"]");
		var thumbimg=$("products_images["+number+"]"+"_thumb");
		img.value=src;
		thumbimg.src=thumbsrc;
	}
	function pwsAdditionalImageAdd(number){
		var container_id="products_images_container["+number+"]";
		var imgcontainer=$('pwsAdditionalImagesScrollablePan');
		var newelement=document.createElement("div");
		newelement.setAttribute('class','pws_additionalImagePickerContainer');
		newelement.setAttribute('id',"products_images_container["+pwsAdditionalImagesCounter+"]");
		newelement.innerHTML=drawAdditionalImagePickerPlacehold(pwsAdditionalImagesCounter,true);
		for (i=0;i<imgcontainer.childNodes.length;i++){
			if (imgcontainer.childNodes[i].id==container_id){
				imgcontainer.insertBefore(newelement,imgcontainer.childNodes[i]);
				break;
			}
		}
		pwsAdditionalImagesCounter++;
		pwsAdditionalImageStoreOrder();
	}
	function pwsAdditionalImageManualAdd() {
		var imgcontainer=$('pwsAdditionalImagesScrollablePan');
		var newelement=document.createElement("div");
		var number = pwsAdditionalImagesCounter;
		var src = $('pwsProductsImageNewPath').value;
		var relpath = '../<?=DIR_WS_IMAGES?>';
		newelement.setAttribute('class','pws_additionalImagePickerContainer');
		newelement.setAttribute('id',"products_images_container["+pwsAdditionalImagesCounter+"]");
		newelement.innerHTML=drawAdditionalImagePickerPlacehold(pwsAdditionalImagesCounter,true);
		imgcontainer.appendChild(newelement);
		pwsAdditionalImageSetImages(number,relpath+src,relpath+src);
		pwsAdditionalImagesCounter++;
		pwsAdditionalImageStoreOrder();
	}
<?	if ($pws_html->isRemoteImagesDownloadInstalled()){?>
	function pwsAdditionalImageRemoteDownload(){
		var imgcontainer=$('pwsAdditionalImagesScrollablePan');
		var newelement=document.createElement("div");
		var number = pwsAdditionalImagesCounter;
		var divid="products_images_container["+pwsAdditionalImagesCounter+"]";
		pwsAdditionalImagesCurrent=number;
		newelement.setAttribute('class','pws_additionalImagePickerContainer');
		newelement.setAttribute('id',divid);
		newelement.innerHTML=drawAdditionalImagePickerPlacehold(pwsAdditionalImagesCounter,true);
		pwsAdditionalImagesNewElement=newelement;
		
		var title="UploadImmagine";
		var imgurl=$('pwsProductsImageRemotePath').value;
		window.imagePickerDownloadCallback=function(imgsrc,imgthumb){
			if (imgsrc && imgthumb){
				var dir_ws_images='<?=DIR_WS_CATALOG_IMAGES?>';
				if (imgsrc.indexOf(dir_ws_images)==0){
					imgsrc=imgsrc.substr(dir_ws_images.length,imgsrc.length-dir_ws_images.length);
				}else if (imgsrc.indexOf(dir_ws_images='/<?=DIR_WS_IMAGES?>')==0){
					imgsrc=imgsrc.substr(dir_ws_images.length,imgsrc.length-dir_ws_images.length);
				}
				imgcontainer.appendChild(pwsAdditionalImagesNewElement);
				pwsAdditionalImageSetImages(pwsAdditionalImagesCurrent,imgsrc,imgthumb);
				pwsAdditionalImagesCounter++;
				pwsAdditionalImageStoreOrder();
			}else{
				delete(pwsAdditionalImagesNewElement);
			}
		}
		if (pwsImagePickerWindow!=null)
			pwsImagePickerWindow.close();
		pwsImagePickerWindow=window.open("<?=URL_DOWNLOAD_REMOTE_IMAGE?>"+"?imgurl="+escape(imgurl),"subWnd","width=10,height=10,screenX=0,screenY=0,resizable=false");
		if (pwsImagePickerWindow.opener == null)
			pwsImagePickerWindow.opener = self;
		
	}
<?	}	?>
	function pwsAdditionalImageDelete(number){
		var imgcontainer=$('pwsAdditionalImagesScrollablePan');
		var container_id="products_images_container["+number+"]";
		var element=$(container_id);
		imgcontainer.removeChild(element);
		pwsAdditionalImageStoreOrder();
	}
	function pwsAdditionalImageStoreOrder(){
		var imgcontainer=$('pwsAdditionalImagesScrollablePan');
		var container_id="products_images_container[";
		var sortorder_id="products_images_sort_order[";
		var foundid;
		var number;
		var element;
		var order;
		var container_id="products_images_container["
		var sort_order=0;
		for (i=0;i<imgcontainer.childNodes.length;i++){
			element=imgcontainer.childNodes[i];
			if (element.hasAttribute && element.tagName.toLowerCase()=='div'){
				foundid=imgcontainer.childNodes[i].id;
				if (foundid.indexOf(container_id)!=-1){
					number=foundid.substr(container_id.length);
					number=number.substr(0,number.length-1);
					number=parseInt(number);
					order=$(sortorder_id+number+"]");
					order.value=sort_order++;
				}
			}
		}
	}
	function pwsAdditionalImageMoveLeft(number){
		var imgcontainer=$('pwsAdditionalImagesScrollablePan');
		var container_id="products_images_container[";
		var prevcont=null,curcont=null;
		var foundid;
		var curnumber=-1,prevnumber=-1;
		var elementtomove;
		var elementnext;
		var element;
		for (i=0;i<imgcontainer.childNodes.length;i++){
			element=imgcontainer.childNodes[i];
			if (element.hasAttribute && element.tagName.toLowerCase()=='div'){
				foundid=imgcontainer.childNodes[i].id;
				if (foundid.indexOf(container_id)!=-1){
					curnumber=foundid.substr(container_id.length);
					curnumber=curnumber.substr(0,curnumber.length-1);
					curnumber=parseInt(curnumber);
					if (curnumber==number){
						break;
					}else{
						prevnumber=curnumber;
					}
				}
			}
		}
		if (prevnumber>=0 && curnumber>=0){
			elementtomove=$(container_id+curnumber+']');
			elementnext=$(container_id+prevnumber+']');
			//element=elementtomove.cloneNode(true);
			imgcontainer.removeChild(elementtomove);
			imgcontainer.insertBefore(elementtomove,elementnext);
			pwsAdditionalImageStoreOrder();
		}
	}
	function pwsAdditionalImageMoveRight(number){
		var imgcontainer=$('pwsAdditionalImagesScrollablePan');
		var container_id="products_images_container[";
		var prevcont=null,curcont=null;
		var foundid;
		var curnumber=-1,nextnumber=-1;
		var elementtomove;
		var elementnext;
		var element;
		for (i=0;i<imgcontainer.childNodes.length;i++){
			element=imgcontainer.childNodes[i];
			if (element.hasAttribute && element.tagName.toLowerCase()=='div'){
				foundid=imgcontainer.childNodes[i].id;
				if (foundid.indexOf(container_id)!=-1){
					curnumber=foundid.substr(container_id.length);
					curnumber=curnumber.substr(0,curnumber.length-1);
					curnumber=parseInt(curnumber);
					if (curnumber==number){
						break;
					}
				}
			}
		}
		for (i++;i<imgcontainer.childNodes.length;i++){
			element=imgcontainer.childNodes[i];
			if (element.hasAttribute && element.tagName.toLowerCase()=='div'){
				foundid=imgcontainer.childNodes[i].id;
				if (foundid.indexOf(container_id)!=-1){
					nextnumber=foundid.substr(container_id.length);
					nextnumber=nextnumber.substr(0,nextnumber.length-1);
					nextnumber=parseInt(nextnumber);
					break;
				}
			}
		}
		if (nextnumber>=0 && curnumber>=0){
			elementnext=$(container_id+curnumber+']');
			elementtomove=$(container_id+nextnumber+']');
			//element=elementtomove.cloneNode(true);
			imgcontainer.removeChild(elementtomove);
			imgcontainer.insertBefore(elementtomove,elementnext);
			pwsAdditionalImageStoreOrder();
		}
	}
--></script>
<?		
	}
	//	@function adminNewProduct
	//	@desc		Crea nella struttura pInfo i propri dati relativi al prodotto
	function	adminNewProduct(){
		global $pInfo;
		$pInfo->products_images=array();
	}
	//	@function adminLoadProduct
	//	@desc		Carica nella struttura pInfo i propri dati relativi al prodotto
	function	adminLoadProduct(){
		global $pInfo;
		$pInfo->products_images=array();
		$query=tep_db_query("select * from ".TABLE_PWS_PRODUCTS_IMAGES." where products_id='".$pInfo->products_id."' order by sort_order asc");
		while ($image=tep_db_fetch_array($query)){
			$pInfo->products_images[$image['sort_order']]=$image['products_image'];
		}
	}
	function adminEditProductsImages(){
		global $pInfo,$pws_html;
		$images=array();
		//print_r($_REQUEST);print_r($pInfo);exit;
		if (isset($_REQUEST['pwsProductsImageRemotePath']))
			$imgurl=$_REQUEST['pwsProductsImageRemotePath'];
		else
			$imgurl='http://';
?>
		  <tr valign="top">
		  	<td class="main"><?=TEXT_PWS_PRODUCTS_IMAGES_PAN_LABEL?></td>
		    <td class="main">
	<?	if ($pws_html->isRemoteImagesDownloadInstalled()){?>
		    	<div class="pws_imagePickerURLDiv">
					<label for="pws_products_image_url"><?=TEXT_PWS_PRODUCTS_IMAGES_REMOTE_URL_INSERT?><br/>
						<input id="pwsProductsImageRemotePath" name="pwsProductsImageRemotePath" class="pwsImagePickerURL" value=""/>
					</label>
					<button type="button" class="pws_imagePickerButton" onclick="pwsAdditionalImageRemoteDownload()"><?=TEXT_ENTER_IMAGE_URL_DOWNLOAD?></button>
				</div>
	<?	}	?>
				<div class="pws_imagePickerURLDiv">
					<label for="pwsProductsImageNewPath"><?=TEXT_PWS_PRODUCTS_IMAGES_MANUAL_INSERT?><br/>
		    			<input type="text" size="20" name="pwsProductsImageNewPath" id="pwsProductsImageNewPath" class="pwsImagePickerURL"/>
		    		</label>
		    		<button type="button" class="pws_imagePickerButton"  onclick="pwsAdditionalImageManualAdd()"><?=BUTTON_ADD_IMAGE?></button>
		    	</div>
		    	<div class="pwsProductsImagesEditScrollablePan" id="pwsAdditionalImagesScrollablePan">
<script type="text/javascript" language="javascript1.5" ><!--
<?
	reset($pInfo->products_images);
	$i=0;
	foreach($pInfo->products_images as $number=>$image){
		$imgthumb=$GLOBALS['pws_html']->getThumbnailLocation($image,SMALL_IMAGE_WIDTH,SMALL_IMAGE_HEIGHT);
		 $imgsrc='..'.DIR_WS_CATALOG_IMAGES.$image;
		// $imgsrc= $image;
		$number=$i++;
?>
		document.write(drawAdditionalImagePickerPlacehold(<?=$number?>,false));
		pwsAdditionalImageSetImages(<?=$number?>,'<?=$imgsrc?>','<?=$imgthumb?>');
<?
	}
	$number++;
?>
		document.write(drawAdditionalImagePickerPlacehold(<?=$number?>,false));
--></script>
		    </div></td>
		  </tr>

<?	}
	//	@function adminUpdateProduct
	//	@desc		Salva i parametri editati per il prodotto, nella sezione admin
	function	adminUpdateProduct(){
		global $products_id,$pInfo;
		$result = tep_db_query("delete from ".TABLE_PWS_PRODUCTS_IMAGES." where products_id='$products_id'");
		// print (mysql_affected_rows());
		// print $products_id;
		
		$sort_order=0;
		array_multisort($_REQUEST['products_images_sort_order'],SORT_ASC,SORT_REGULAR,$_REQUEST['products_images']);
//		print_r($_REQUEST);
//		print_r($_REQUEST['products_images_sort_order']);
//	print_r($_REQUEST['products_images']);
	
//	exit;
		reset($_REQUEST['products_images']);
		foreach($_REQUEST['products_images'] as $img_url){
			if ($img_url!=''){
			//echo $img_url."products_id:$products_id";exit;
				//die("insert into ".TABLE_PWS_PRODUCTS_IMAGES." set products_id='$products_id', products_image='$img_url', sort_order='$sort_order'");
//				if ($img_url[0]=='/')
//					$img_url=substr($img_url,1);
//				if (substr($img_url,0,strlen(DIR_WS_IMAGES))==DIR_WS_IMAGES)
//					$img_url=substr($img_url,strlen(DIR_WS_IMAGES));
				tep_db_query("insert into ".TABLE_PWS_PRODUCTS_IMAGES." set products_id='$products_id', products_image='$img_url', sort_order='$sort_order'");
				$sort_order++;
			}
		}
	}
	//	@function adminProductsDisplay
	//	@desc		Visualizza le immagini aggiuntive nel riassunto del prodotto
	function	adminProductsDisplay(){
		global $pInfo,$pws_html;
		$products_id=$pInfo->products_id;
		$output='<div class="pwsProductsImagesPreviewScrollablePan">';
		$query=tep_db_query("select * from ".TABLE_PWS_PRODUCTS_IMAGES." where products_id='$products_id' order by sort_order asc");
		if (!tep_db_num_rows($query))
			return '';
		while ($image=tep_db_fetch_array($query)){
			$output.=$pws_html->drawThumbnail('pwsProductsImagePreview',$image['products_image'],SMALL_IMAGE_WIDTH,SMALL_IMAGE_HEIGHT);
		}
		$output.='</div>';
		return $output;
	}
	//	@function adminDeleteProduct
	//	@desc		Elimina i dati memorizzati relativamente ad un prodotto
	function	adminDeleteProduct(){
		global $products_id,$pInfo;
		tep_db_query("delete from ".TABLE_PWS_PRODUCTS_IMAGES." where products_id='$products_id'");
	}
	//	@function	adminCopyProduct
	//	@desc		Duplica i dati relativi ad un prodotto
	function	adminCopyProduct(){
		global $products_id,$dup_products_id;
		$query=tep_db_query("select * from ".TABLE_PWS_PRODUCTS_IMAGES." where products_id='$products_id' order by sort_order asc");
		if (!tep_db_num_rows($query))
			return;
		while ($image=tep_db_fetch_array($query)){
			$image['products_id']=$dup_products_id;
			tep_db_perform(TABLE_PWS_PRODUCTS_IMAGES,$image);
		}
	}
}
?>
