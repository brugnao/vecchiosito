<?php
/*
 * @filename:	pws_html.php
 * @version:	0.72
 * @project:	PWS
 *
 * @author:		Riccardo Roscilli <info@oscommerce.it>
 * @created:	16/nov/07
 * @modified:	22/gen/08 11:37:40
 *
 * @copyright:	2006-2008	Riccardo Roscilli @ PWS
 *
 * @desc:	Html Helper. Contiene alcune funzioni utili alla produzione di codice html
 *
 * @TODO:		
 */
if (defined('DIR_FS_CATALOG_IMAGES'))
	require_once DIR_FS_CATALOG.'includes/functions/thumbnails.php';
else
	require_once DIR_WS_FUNCTIONS.'thumbnails.php';

define('DIR_FS_TINY_MCE',DIR_FS_CATALOG.'tinymce');
define('FILENAME_DOWNLOAD_REMOTE_IMAGE','pws_download_remote_image.php');
define('URL_DOWNLOAD_REMOTE_IMAGE',HTTP_SERVER.DIR_WS_CATALOG.DIR_WS_PWS.FILENAME_DOWNLOAD_REMOTE_IMAGE);

if (!function_exists('file_put_contents')) {
    function file_put_contents($filename, $data, $respect_lock = true)
    {
        // Open the file for writing
        $fh = @fopen($filename, 'w');
        if ($fh === false) {
            return false;
        }

        // Check to see if we want to make sure the file is locked before we write to it
        if ($respect_lock === true && !flock($fh, LOCK_EX)) {
            fclose($fh);
            return false;
        }

        // Convert the data to an acceptable string format
        if (is_array($data)) {
            $data = implode('', $data);
        } else {
            $data = (string) $data;
        }

        // Write the data to the file and close it
        $bytes = fwrite($fh, $data);

        // This will implicitly unlock the file if it's locked
        fclose($fh);

        return $bytes;
    }
}
	
class	pws_html extends pws_plugin {
// Variabili private
	var	$noimg;					// url dell'immagine del thumbnail dell'immagine che indica nessuna immagine disponibile/selezionata
	var $images_directory;		// path assoluto della directory images del catalogo
	var $tinymce_installed=false;	// flag di installazione di tinymce
	var	$prototype_requested=0;	// Contatore delle richieste per prototype.js
// Variabili del plugin
	var $plugin_type='application';			// Tipo del plugin
	var $plugin_code='pws_html';			// Codice univoco del plugin ( deve coincidere con il nome del file )
	var $plugin_configurable=true;	// Plugin di tipo configurabile ? (se true, comparirà nella lista dei moduli di tipo "sistema")
	var $plugin_name=TEXT_PWS_HTML_NAME;		// Nome del plugin
	var $plugin_description=TEXT_PWS_HTML_DESCRIPTION;	// Descrizione del plugin
	var $plugin_version_const='0.72';		// Versione del codice
	var $plugin_needs=array();	// Codici dei plugin da cui dipende=>istanza del plugin
	var	$plugin_conflicts=array();
	var	$plugin_editPage='';
	var $plugin_sort_order=1;
	var $plugin_configKeys=array(
		'PWS_HTML_NO_IMAGE_LOCATION'=>array(
				'configuration_title'=>TEXT_PWS_HTML_NO_IMAGE_LOCATION
				,'configuration_value'=>'icons/no_picture.jpg'
				,'configuration_description'=>TEXT_PWS_HTML_NO_IMAGE_LOCATION_DESC
			)
		);	// Chiavi di configurazione del plugin, in forma chiave=>array(field1=>value1, ..., fieldn=>valuen)
	var $plugin_tables=array();
	var $plugin_sql_install='';	// Codice sql da applicare in fase di installazione del plugin

	var $plugin_sql_remove='';	// Codice sql da applicare in fase di rimozione del plugin
	var $plugin_removable=false;	// Questo plugin non può essere rimosso e viene installato di default	

	// Definizione dei punti di intervento
	var	$plugin_hooks=array(
		'ADMIN_STYLESHEET'=>'adminStylesheet'
		,'ADMIN_CATEGORIES_HEAD'=>'adminCategoriesHead'
		,'ADMIN_MAXI_DVD_POST_JAVASCRIPT'=>'adminMaxiDVDPostJavascript'
		,'ADMIN_NEW_PRODUCT_PREVIEW'=>'adminNewProductPreview'
		,'CATALOG_PRODUCT_INFO_JAVASCRIPT'=>'catalogProductInfoJavascript'
		,'CATALOG_HEAD'=>'catalogHead'
	);
	//////////////////////////////////////////////////////////////////////
	// Funzioni
	function pws_html(&$pws_engine){
		parent::pws_plugin(&$pws_engine);
	}

	//	@function init
	//	@desc	Funzione di inizializzazione del plugin
	function init(){
		parent::init();
		$this->admin_side=$this->_pws_engine->isAdminSideRunning();
		if ($this->admin_side)
			$this->images_directory=DIR_FS_CATALOG_IMAGES;
		else
			$this->images_directory=DIR_FS_CATALOG.DIR_WS_IMAGES;

		$this->tinymce_installed=file_exists(DIR_FS_TINY_MCE) && is_dir(DIR_FS_TINY_MCE);
		$this->noimg=getThumbnail($this->images_directory.PWS_HTML_NO_IMAGE_LOCATION,SMALL_IMAGE_WIDTH,SMALL_IMAGE_HEIGHT);
		$this->noimg='../'.$this->noimg['baseFileName'];
	}
	//	@function isRemoteImagesDownloadInstalled
	//	@desc	Controlla se è installato il modulo per il download di immagini remote automatizzato
	//	@return		bool		true se installato, false altrimenti
	function isRemoteImagesDownloadInstalled(){
		static	$response=NULL;
		if (is_null($response))
			$response=file_exists(DIR_FS_PWS.FILENAME_DOWNLOAD_REMOTE_IMAGE);
		return $response;
	}
	//	@function install
	//	@desc	Funzione di installazione del plugin
	function install(){
		tep_db_query("delete from ".TABLE_CONFIGURATION." where configuration_key in ('HTML_EDITOR_CHOICE','IMAGE_PICKER_CHOICE')");
		if (tep_db_num_rows(tep_db_query("select * from ".TABLE_CONFIGURATION." where configuration_group_id=112 and sort_order<=2"))>0)
			tep_db_query("update ".TABLE_CONFIGURATION." set sort_order=sort_order+3 where configuration_group_id=112");
		if (!$this->_pws_engine->configurationKeyExists('HTML_EDITOR_CHOICE')){
			tep_db_query("insert  into `".TABLE_CONFIGURATION."`(`configuration_title`,`configuration_key`,`configuration_value`,`configuration_description`,`configuration_group_id`,`sort_order`,`date_added`,`set_function`) values ('Editor HTML', 'HTML_EDITOR_CHOICE', 'TinyMCE', 'Selezionare l\'editor HTML da utilizzare:<br/>HTML Area - compatibile solo con I.Explorer<br/>TinyMCE - compatibile con tutti i browsers',112, 1, now(), 'tep_cfg_select_option(array(\'HTML_AREA\', \'TinyMCE\'),')");
		}
		if (!$this->_pws_engine->configurationKeyExists('IMAGE_PICKER_CHOICE')){
			tep_db_query("insert  into `".TABLE_CONFIGURATION."`(`configuration_title`,`configuration_key`,`configuration_value`,`configuration_description`,`configuration_group_id`,`sort_order`,`date_added`,`set_function`) values ('Picker Immagini', 'IMAGE_PICKER_CHOICE', 'PWS Picker', 'Selezionare il picker immagini da utilizzare:<br/>HTML Area - compatibile solo con I.Explorer<br/>PWS Picker - compatibile con tutti i browsers',112, 2, now(), 'tep_cfg_select_option(array(\'HTML_AREA\', \'PWS Picker\'),')");
		}
		$this->installStoreLogos();
		return parent::install();
	}
	function installStoreLogos(){
		$languages=$this->_pws_engine->tep_get_languages();
		$configkey=array(
			'configuration_title'=>'Logo Negozio - %s'
			,'configuration_key'=>'STORE_LOGO_%s'
			,'configuration_value'=>'oscommerce.gif'
			,'configuration_description'=>'Impostare il logo da utilizzare per la versione del sito in:<br/><b>%s</b>.'
			,'configuration_group_id'=>1
			,'date_added'=>'now()'
			,'set_function'=>'$GLOBALS[\'pws_html\']->setStoreLogo(\'%s\', '
		);
		reset($languages);
		for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
			$key=sprintf($configkey['configuration_key'],strtoupper($languages[$i]['code']));
			if (!$this->_pws_engine->configurationKeyExists($key)){
				$sort_order=tep_db_fetch_array(tep_db_query("select (max(sort_order)+1) as sort_order from ".TABLE_CONFIGURATION." where configuration_group_id='1'"));
				$sort_order=$sort_order['sort_order'];
				$cfgkey=$configkey;
				$cfgkey['sort_order']=$sort_order;
				$cfgkey['configuration_key']=$key;
				$cfgkey['configuration_title']=sprintf($cfgkey['configuration_title'], $languages[$i]['name']);
				$cfgkey['configuration_description']=sprintf($cfgkey['configuration_description'], $languages[$i]['name']);
				$cfgkey['set_function']=sprintf($cfgkey['set_function'], $languages[$i]['code']);
				tep_db_perform(TABLE_CONFIGURATION,$cfgkey);
			}
			//insert  into `configuration`(`configuration_title`,`configuration_key`,`configuration_value`,`configuration_description`,`configuration_group_id`,`sort_order`,`date_added`,`set_function`) values ('Logo Negozio - Italiano', 'STORE_LOGO_IT', 'oscommerce.gif', 'Impostare il logo da utilizzare per la versione del sito in:<br/><b>Italiano</b>.',1, 1000, now(), '$GLOBALS[\'pws_html\']->setStoreLogo(\'it\', ')
		}
		
	}
	//	@function remove
	//	@desc	Funzione di rimozione del plugin
	function remove(){
		tep_db_query("delete from ".TABLE_CONFIGURATION." where configuration_key='HTML_EDITOR_CHOICE'");
		tep_db_query("delete from ".TABLE_CONFIGURATION." where configuration_key='IMAGE_PICKER_CHOICE'");
		return parent::remove();
	}
	function getThumbnailLocation($src,$width,$height){
		$src=urldecode($src);
		$imgattrs=getThumbnail($this->images_directory.$src,$width,$height);
		if ($this->admin_side)
			$src=/*'../'.*/$imgattrs['baseFileName'];
		else
			$src=$imgattrs['baseFileName'];
		return $src;
	}
	function drawThumbnail($id,$src,$width,$height,$params=''){
	  	$src=urldecode($src);
		$imgattrs=getThumbnail($this->images_directory.$src,$width,$height);
		if ($this->admin_side)
			$src=/*'../'.*/$imgattrs['baseFileName'];
		else
			$src=$imgattrs['baseFileName'];
		return '<img id="'.$id.'" src="' . tep_output_string($src) . '" alt="'.$src.'" title="'.$src.'" '.$params.'/>';
	}
	/*
	 * @function setStoreLogo
	 * @desc	Crea il codice per l'impostazione del logo del negozio dalla configurazione
	 * @param (string)	$language_code				Codice della lingua (es.: it)
	 * @param (string)	$imgsrc						Path dell'immagine relativa a DIR_WS_IMAGES
	 * 
	 */
	function setStoreLogo($language_code,$imgsrc){
	$imgid='configuration_value';
	$output='<style type="text/css">'.$this->adminStylesheet().'</style>';
	$dir_ws_catalog_images=DIR_WS_CATALOG_IMAGES;
	$fck_filebrowser=($this->admin_side ? '../':'').DIR_WS_PWS_FILEBROWSER.'browser.html?Type=Image&Connector=connectors/php/connector.php';
	$noimg=$this->noimg;
	$urldownloadimg=URL_DOWNLOAD_REMOTE_IMAGE;
	$output.=<<<EOT
<!--PWS src="includes/javascript/prototype.js" bof-->
<script language="javascript" type="text/javascript">
function $(element) {
  if (arguments.length > 1) {
    for (var i = 0, elements = [], length = arguments.length; i < length; i++)
      elements.push($(arguments[i]));
    return elements;
  }
  if (typeof element == "string")
    element = document.getElementById(element);
//  return Element.extend(element);
	return element;
}
</script>
<!--PWS eof-->
<script language="javascript" type="text/javascript">
	var pwsImagePickerWindow=null;
	var pwsImagePickerCurrentId=null;
	window.imagePickerCallback=function(imgurl){
		var dir_ws_images='$dir_ws_catalog_images';
		if (imgurl.indexOf(dir_ws_images)==0){
			imgurl=imgurl.substr(dir_ws_images.length,imgurl.length-dir_ws_images.length);
		}else if (imgurl.indexOf(dir_ws_images='/$dir_ws_catalog_images')==0){
			imgurl=imgurl.substr(dir_ws_images.length,imgurl.length-dir_ws_images.length);
		}
		$(pwsImagePickerCurrentId).value=imgurl;
		$(pwsImagePickerCurrentId+'_thumb').src='$dir_ws_catalog_images'+imgurl;
		$(pwsImagePickerCurrentId+'_thumb').alt=imgurl;
	}
	function pwsLaunchImagePicker(imgid){
		var title="UploadImmagine";
		if (pwsImagePickerWindow!=null)
			pwsImagePickerWindow.close();
		pwsImagePickerCurrentId=imgid;
		pwsImagePickerWindow=window.open("$fck_filebrowser","subWnd","width=800,height=600,resizable=true");
		if (pwsImagePickerWindow.opener == null)
			pwsImagePickerWindow.opener = self;
		pwsImagePickerWindow.thumbnail_id=imgid+'_thumb';
		pwsImagePickerWindow.image_id=imgid;
	}
	function pwsResetImagePicker(imgid,thumb){
		var img=$(imgid);
		img.value='';
		thumb=$(thumb);
		thumb.src='$noimg';
		return false;
	}
EOT;
	if ($this->isRemoteImagesDownloadInstalled()){
	$output.=<<<EOT
	function pwsDownloadRemoteImage(imgid){
		var title="UploadImmagine";
		var imgurl=$('pws_products_image_url['+imgid+']').value;
		window.imagePickerDownloadCallback=function(imgsrc,imgthumb){
			if (imgsrc && imgthumb){
				var dir_ws_images='$dir_ws_catalog_images';
				if (imgsrc.indexOf(dir_ws_images)==0){
					imgsrc=imgsrc.substr(dir_ws_images.length,imgsrc.length-dir_ws_images.length);
				}else if (imgsrc.indexOf(dir_ws_images='/dir_ws_catalog_images')==0){
					imgsrc=imgsrc.substr(dir_ws_images.length,imgsrc.length-dir_ws_images.length);
				}
				$(pwsImagePickerCurrentId).value=imgsrc;
				$(pwsImagePickerCurrentId+'_thumb').src=imgthumb;
				$(pwsImagePickerCurrentId+'_thumb').alt=imgthumb;
			}
		}
		if (pwsImagePickerWindow!=null)
			pwsImagePickerWindow.close();
		pwsImagePickerCurrentId=imgid;
		pwsImagePickerWindow=window.open("$urldownloadimg"+"?imgurl="+escape(imgurl),"subWnd","width=10,height=10,screenX=0,screenY=0,resizable=false");
		if (pwsImagePickerWindow.opener == null)
			pwsImagePickerWindow.opener = self;
		pwsImagePickerWindow.thumbnail_id=imgid+'_thumb';
		pwsImagePickerWindow.image_id=imgid;
	}
EOT;
	}
	$output.=<<<EOT
</script>
EOT;
			
		$imgsrc=urldecode($imgsrc);
		if ($imgsrc==''){
			$imgthumb=PWS_HTML_NO_IMAGE_LOCATION;
		}else{
			$imgthumb=($imgsrc!='') ? $imgsrc:PWS_HTML_NO_IMAGE_LOCATION;
		}
		$imgattrs=getThumbnail($this->images_directory.$imgthumb,SMALL_IMAGE_WIDTH,SMALL_IMAGE_HEIGHT);
		if ($this->admin_side)
			$imgthumb='../'.$imgattrs['baseFileName'];
			
			//$img=$this->getThumbnail($imgid.'_thumb',$imgthumb,SMALL_IMAGE_WIDTH,SMALL_IMAGE_HEIGHT);
		$text_enter_image_url=TEXT_ENTER_IMAGE_URL;
		$text_enter_image_url_download=TEXT_ENTER_IMAGE_URL_DOWNLOAD;
		$text_click_to_select_image=TEXT_CLICK_TO_SELECT_IMAGE;
		$imgid_thumb=$imgid.'_thumb';
		$icon_delete=DIR_WS_ICONS.'icon_delete.gif';
		$output.=<<<EOT
		<div class="pws_imagePickerOverContainer">
EOT;
		if ($this->isRemoteImagesDownloadInstalled()){
			$output.=<<<EOT
			<div class="pws_imagePickerURLDiv">
				<label for="pws_products_image_url">$text_enter_image_url<br/>
					<input id="pws_products_image_url[$imgid]" name="pws_products_image_url[$imgid]" class="pwsImagePickerURL" value="$imgurl"/>
				</label>
				<button type="button" class="pws_imagePickerButton" onclick="pwsDownloadRemoteImage('$imgid')">$text_enter_image_url_download</button>
			</div>
EOT;
		}
		$output.=<<<EOT
			<br/>
			<div class="pws_imagePickerContainer">
				<img src="$icon_delete" class="pwsResetImagePicker" onclick="pwsResetImagePicker('$imgid','$imgid_thumb')"/>
				<div class="pws_imagePickerPlacehold">
					<img class="pws_imagePickerThumbnail" id="$imgid_thumb" src="$imgthumb" alt="$text_click_to_select_image" title="$text_click_to_select_image" onclick="pwsLaunchImagePicker('$imgid')"/>
				</div>
			</div>
			<input type="hidden" id="$imgid" name="$imgid" value="$imgsrc"/>
		</div>
EOT;
		return $output;
	}
	
	

	function drawImagePickerPlacehold($imgid,$imgsrc, $imagetype = 'product' )
		{
		if ($imagetype == 'product')
			global $pInfo;
			
		if ($this->tinymce_installed && IMAGE_PICKER_CHOICE=='PWS Picker')
			{
			$imgsrc=urldecode($imgsrc);
			if (isset($_REQUEST["pws_products_image_url"][$imgid])){
				$imgurl=$_REQUEST["pws_products_image_url"][$imgid];
			}else{
				$imgurl='http://';
			}
			if ($imgsrc==''){
				$imgthumb=PWS_HTML_NO_IMAGE_LOCATION;
			}else{
				if (isset($_REQUEST[$imgid])){
					$imgsrc=$_REQUEST[$imgid];
				}else{
					if ($imagetype == 'product')
						$imgsrc=$this->getProductsImage($pInfo->products_id,NULL,NULL,false);
						// funzione getProductsImage($field_index_value, $width=NULL,$height=NULL,$path=true, $field_index = 'products_id' , $field_image = 'products_image', $table = TABLE_PRODUCTS)
						// print ($imgsrc);
					elseif ($imagetype == 'manufacturers') // purtroppo non è possibile risalire al manufacturer_id e quindi devo usare una global
						{
						global $mID;
						$imgsrc=$this->getProductsImage($mID,NULL,NULL,false, $imagetype . '_id', $imagetype . '_image', $imagetype);
						}
				}
			}
					
					
			$imgthumb=($imgsrc!='') ? $imgsrc:PWS_HTML_NO_IMAGE_LOCATION;
			
				if ($imagetype == 'product')
					$pInfo->products_image=$imgsrc;
			
				
		
			
			$imgattrs=getThumbnail($this->images_directory.$imgthumb,SMALL_IMAGE_WIDTH,SMALL_IMAGE_HEIGHT);
			if ($this->admin_side)
				$imgthumb='../'.$imgattrs['baseFileName'];
			
			//$img=$this->getThumbnail($imgid.'_thumb',$imgthumb,SMALL_IMAGE_WIDTH,SMALL_IMAGE_HEIGHT);
	?>
		<div class="pws_imagePickerOverContainer">
	<?	if ($this->isRemoteImagesDownloadInstalled()){?>
			<div class="pws_imagePickerURLDiv">
				<label for="pws_products_image_url"><?=TEXT_ENTER_IMAGE_URL?><br/>
					<input id="pws_products_image_url[<?=$imgid?>]" name="pws_products_image_url[<?=$imgid?>]" class="pwsImagePickerURL" value="<?=$imgurl?>"/>
				</label>
				<button type="button" class="pws_imagePickerButton" onclick="pwsDownloadRemoteImage('<?=$imgid?>')"><?=TEXT_ENTER_IMAGE_URL_DOWNLOAD?></button>
			</div>
	<?	}?>
			<br/>
			<div class="pws_imagePickerContainer">
				<img src="<?=DIR_WS_ICONS.'icon_delete.gif'?>" class="pwsResetImagePicker" onclick="pwsResetImagePicker('<?=$imgid?>','<?=$imgid.'_thumb'?>')"/>
				<div class="pws_imagePickerPlacehold">
					<img class="pws_imagePickerThumbnail" id="<?=$imgid.'_thumb'?>" src="<?=$imgthumb?>" alt="<?=TEXT_CLICK_TO_SELECT_IMAGE?>" title="<?=TEXT_CLICK_TO_SELECT_IMAGE?>" onclick="pwsLaunchImagePicker('<?=$imgid?>')"/>
				</div>
			</div>
			<input type="hidden" id="<?=$imgid?>" name="<?=$imgid?>" value="<?=$imgsrc?>"/>
		</div>
	<?
			
		
	}
	elseif	// MaxiDVD HTML AREA
	(IMAGE_PICKER_CHOICE == 'Upload Standard') 
			{
				echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_file_field('products_image') . '<br>' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . $pInfo->products_image . tep_draw_hidden_field('products_previous_image', $pInfo->products_image);
			}
	else{ // upload standard
				echo '<table border="0" cellspacing="0" cellpadding="0"><tr><td class="main">' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp; </td><td class="main">' . tep_draw_textarea_field('products_image', 'soft', '70', '2', $pInfo->products_image) . tep_draw_hidden_field('products_previous_image', $pInfo->products_image) . '</td></tr></table>';
		}
		

}

	
	function drawAdditionalImagePickerPlacehold($number,$imgsrc){
		$imgid="products_images[$i]";
		$sortorder_id="products_images_sort_order[$i]";
		$oldsortorder_id="products_images_sort_order_old[$i]";
		$container_id="products_images_container[$i]";
		$imgthumb=($imgsrc!='') ? $imgsrc:PWS_HTML_NO_IMAGE_LOCATION;
		$imgattrs=getThumbnail($this->images_directory.$imgthumb,SMALL_IMAGE_WIDTH,SMALL_IMAGE_HEIGHT);
		if ($this->admin_side)
			$imgthumb='../'.$imgattrs['baseFileName'];
		//$img=$this->getThumbnail($imgid.'_thumb',$imgthumb,SMALL_IMAGE_WIDTH,SMALL_IMAGE_HEIGHT);
	?>
	
		<div class="pws_imagePickerContainer" id="<?=$container_id?>">
			<div class="pws_imageButtonsPanel">
				<img src="<?=DIR_WS_ICONS.'icon_new.gif'?>" class="pwsImagePickerButton" onclick="pwsAdditionalImageAdd(<?=$number?>)"/>
				<img src="<?=DIR_WS_ICONS.'icon_delete.gif'?>" class="pwsImagePickerButton" onclick="pwsAdditionalImageDelete(<?=$number?>)"/>
				<img src="<?=DIR_WS_ICONS.'icon_order_left.gif'?>" class="pwsImagePickerButton" onclick="pwsAdditionalImageMoveLeft(<?=$number?>)"/>
				<img src="<?=DIR_WS_ICONS.'icon_order_right.gif'?>" class="pwsImagePickerButton" onclick="pwsAdditionalImageMoveRight(<?=$number?>)"/>
			</div>
			<div class="pws_imagePickerPlacehold">
				<img class="pws_imagePickerThumbnail" id="<?=$imgid.'_thumb'?>" src="<?=$imgthumb?>" alt="<?=TEXT_CLICK_TO_SELECT_IMAGE?>" title="<?=TEXT_CLICK_TO_SELECT_IMAGE?>"/>
			</div>
			<input type="hidden" id="<?=$sortorder_id?>" name="<?=$sortorder_id?>" value="<?=$number?>"/>
			<input type="hidden" id="<?=$oldsortorder_id?>" name="<?=$oldsortorder_id?>" value="<?=$number?>"/>
			<input type="hidden" id="<?=$imgid?>" name="<?=$imgid?>" value="<?=$imgsrc?>"/>
		</div>
	<?
	}
	
/////////////////////////////////////////////////////////////////////////////////////
// Catalog
	//	@function	prototypeRequired
	//	@desc	Segnala a questo plugin la necessità di includere il framework prototype.js
	function	prototypeRequired(){
		$this->prototype_requested++;
	}
	//	@function catalogStylesheet
	//	@desc	Restituisce il codice css utilizzato nel front end da tutti i plugin prezzi
	function	catalogStylesheet(){
		return '';
	}
	function	catalogHead(){
		if ($this->prototype_requested>0)
	//		return '<script language="javascript" src="includes/prototype.js" type="text/javascript"></script>';
	return '<script language="javascript" src="lightbox/prototype.js" type="text/javascript"></script>';
		else
			return '';
	}
	function	catalogProductInfoJavascript(){
?>
	function popupWindow(url) {
	  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
	}
<?
	}
/////////////////////////////////////////////////////////////////////////////////////
// Admin
	function adminStylesheet(){
		$small_image_width=SMALL_IMAGE_WIDTH.'px';
		$small_image_height=SMALL_IMAGE_HEIGHT.'px';
		$small_image_container_width=(SMALL_IMAGE_WIDTH+20).'px';
		$small_image_container_height=(SMALL_IMAGE_HEIGHT+20).'px';
		$output=<<<EOT
.pws_imageButtonsPanel{
	float:left;
	width:20px;
	min-width:20px;
	height:100px;
	min-height:100px;
}
.pws_imagePickerHiddenDiv{
	display:block;
}
.pws_imagePickerOverContainer{
	width:100%;
	height:auto;
}
.pws_imagePickerURLDiv,.pwsImagePickerURL,.pws_imagePickerButton{
	font-family:Arial,Verdana,Sans-Serif;
	font-size:10px;
}
.pwsImagePickerURL{
	width:300px;
}
.pws_imagePickerContainer{
	float:left;
	clear:both;
	height:$small_image_container_width;
	width:$small_image_container_height;
}
.pws_imagePickerPlacehold{
	float:left;
	border:1px dotted #aaaaaa;
}
.pws_imagePickerThumbnail{
	float:left;
	border:1px dotted #aaaaaa;
	max-width:$small_image_width;
	max-height:$small_image_height;
}
.pwsImagePickerButton{
	float:none;
	clear:both;
}
.pwsResetImagePicker{
	float:left;
	border:1px dotted #FF9999;
}
.pwsResetImagePicker:hover{
	float:left;
	border:1px solid #FF9999;
}
EOT;
		return $output;
	}
function adminCategoriesHead(){
?>
<script language="javascript" type="text/javascript">//<!--
function pwsAttachEvent(element,eventName,handler){
	//var name = getDOMEventName(eventName);
	if (element.addEventListener)
		element.addEventListener(eventName,handler,false);
	else
		element.attachEvent("on" + eventName,handler);
}
//--></script>
<?
		if (HTML_EDITOR_CHOICE=='TinyMCE' && IMAGE_PICKER_CHOICE=='PWS Picker'){
?>
<!--PWS bof-->
<script language="javascript" src="includes/javascript/prototype.js"></script>
<!--PWS eof-->
<?
		}else{
?>
<!--PWS src="includes/javascript/prototype.js" bof-->
<script language="javascript" type="text/javascript">
function $(element) {
  if (arguments.length > 1) {
    for (var i = 0, elements = [], length = arguments.length; i < length; i++)
      elements.push($(arguments[i]));
    return elements;
  }
  if (typeof element == "string")
    element = document.getElementById(element);
//  return Element.extend(element);
	return element;
}
</script>
<!--PWS eof-->
<?		}
		if ($this->tinymce_installed && HTML_EDITOR_CHOICE=='TinyMCE' && 0==1){
			
?>
<script language="javascript" type="text/javascript" src="../tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<? 			if (isset($_REQUEST['action']) && $_REQUEST['action']=='new_product' && 0==1){?>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		plugins : "simplebrowser,ibrowser,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,compat2x",
//		plugins : "ibrowser,simplebrowser,table,save,advhr,advimage,advlink,searchreplace,emotions,iespell,paste,fullscreen,compat2x",
//plugins : "simplebrowser",
//plugin_simplebrowser_width : '800', //default
//plugin_simplebrowser_height : '600', //default1
//      plugin_simplebrowser_width : '800'; //default
//      plugin_simplebrowser_height : '600'; //default
//plugin_simplebrowser_browselinkurl : 'simplebrowser/browser.html?Connector=connectors/php/connector.php',
//plugin_simplebrowser_browseimageurl : 'simplebrowser/browser.html?Type=Image&Connector=connectors/php/connector.php',
// plugin_simplebrowser_browseflashurl : 'simplebrowser/browser.html?Type=Flash&Connector=connectors/php/connector.php',
		theme_advanced_buttons1_add_before : "save,newdocument,separator",
//		theme_advanced_buttons1_add : "fontselect,fontsizeselect",
		theme_advanced_buttons1_add : "fontselect,fontsizeselect,ibrowser,separator",
		theme_advanced_buttons2_add : "separator,insertdate,inserttime,preview,zoom,separator,forecolor,backcolor",
		theme_advanced_buttons2_add_before: "cut,copy,paste,pastetext,pasteword,separator,search,replace,separator",
		theme_advanced_buttons3_add_before : "tablecontrols,separator",
		theme_advanced_buttons3_add : "emotions,iespell,media,advhr,separator,print,separator,ltr,rtl,separator,fullscreen",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		content_css : "example_word.css",
	    plugi2n_insertdate_dateFormat : "%Y-%m-%d",
	    plugi2n_insertdate_timeFormat : "%H:%M:%S",
		external_link_list_url : "example_link_list.js",
		external_image_list_url : "example_image_list.js",
		media_external_list_url : "example_media_list.js",
		file_browser_callback : "simplebrowser_browse",
		//file_browser_callback : "fileBrowserCallBack",
		//file_browser_callback : "mcFileManager.filebrowserCallBack",
		paste_use_dialog : false,
		theme_advanced_resizing : true,
		theme_advanced_resize_horizontal : false,
		theme_advanced_link_targets : "_something=My somthing;_something2=My somthing2;_something3=My somthing3;",
		paste_auto_cleanup_on_paste : true,
		paste_convert_headers_to_strong : false,
		paste_strip_class_attributes : "all",
		paste_remove_spans : false,
		paste_remove_styles : false		
	});

</script>
<?			}
		}
		if (IMAGE_PICKER_CHOICE=='HTML_AREA' || HTML_EDITOR_CHOICE=='HTML_AREA'){	// HTML_AREA come editor
?>
 <script language="Javascript1.2"><!-- // load htmlarea
// MaxiDVD Added WYSIWYG HTML Area Box + Admin Function v1.7 - 2.2 MS2 Products Description HTML - Head
        _editor_url = "<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN; ?>htmlarea/";  // URL to htmlarea files
          var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);
           if (navigator.userAgent.indexOf('Mac')        >= 0) { win_ie_ver = 0; }
            if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }
             if (navigator.userAgent.indexOf('Opera')      >= 0) { win_ie_ver = 0; }
         <?php if (HTML_AREA_WYSIWYG_BASIC_PD == 'Basic'){ ?>  if (win_ie_ver >= 5.5) {
         document.write('<scr' + 'ipt src="' +_editor_url+ 'editor_basic.js"');
         document.write(' language="Javascript1.2"></scr' + 'ipt>');
            } else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
         <?php } else{ ?> if (win_ie_ver >= 5.5) {
         document.write('<scr' + 'ipt src="' +_editor_url+ 'editor_advanced.js"');
         document.write(' language="Javascript1.2"></scr' + 'ipt>');
            } else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
         <?php }?>
// --></script>
<?
		}
		if ($this->tinymce_installed && IMAGE_PICKER_CHOICE=='PWS Picker'){
?>
<script language="javascript" type="text/javascript">
	var pwsImagePickerWindow=null;
	var pwsImagePickerCurrentId=null;
	window.imagePickerCallback=function(imgurl){
		var dir_ws_images='<?=DIR_WS_CATALOG_IMAGES?>';
		if (imgurl.indexOf(dir_ws_images)==0){
			imgurl=imgurl.substr(dir_ws_images.length,imgurl.length-dir_ws_images.length);
		}else if (imgurl.indexOf(dir_ws_images='/<?=DIR_WS_IMAGES?>')==0){
			imgurl=imgurl.substr(dir_ws_images.length,imgurl.length-dir_ws_images.length);
		}
		$(pwsImagePickerCurrentId).value=imgurl;
		$(pwsImagePickerCurrentId+'_thumb').src='<?=($this->admin_side ? DIR_WS_CATALOG_IMAGES:DIR_WS_IMAGES)?>'+imgurl;
		$(pwsImagePickerCurrentId+'_thumb').alt=imgurl;
	}
	function pwsLaunchImagePicker(imgid){
		var title="UploadImmagine";
		if (pwsImagePickerWindow!=null)
			pwsImagePickerWindow.close();
		pwsImagePickerCurrentId=imgid;
		pwsImagePickerWindow=window.open("<?=($this->admin_side ? '../':'').DIR_WS_PWS_FILEBROWSER.'browser.html?Type=Image&Connector=connectors/php/connector.php'?>","subWnd","width=800,height=600,resizable=true");
		if (pwsImagePickerWindow.opener == null)
			pwsImagePickerWindow.opener = self;
		pwsImagePickerWindow.thumbnail_id=imgid+'_thumb';
		pwsImagePickerWindow.image_id=imgid;
	}
	function pwsResetImagePicker(imgid,thumb){
		var img=$(imgid);
		img.value='';
		thumb=$(thumb);
		thumb.src='<?=$this->noimg?>';
		return false;
	}
<?	if ($this->isRemoteImagesDownloadInstalled()){?>

	function pwsDownloadRemoteImage(imgid){
		var title="UploadImmagine";
		var imgurl=$('pws_products_image_url['+imgid+']').value;
		window.imagePickerDownloadCallback=function(imgsrc,imgthumb){
			if (imgsrc && imgthumb){
				var dir_ws_images='<?=DIR_WS_CATALOG_IMAGES?>';
				if (imgsrc.indexOf(dir_ws_images)==0){
					imgsrc=imgsrc.substr(dir_ws_images.length,imgsrc.length-dir_ws_images.length);
				}else if (imgsrc.indexOf(dir_ws_images='/<?=DIR_WS_IMAGES?>')==0){
					imgsrc=imgsrc.substr(dir_ws_images.length,imgsrc.length-dir_ws_images.length);
				}
				$(pwsImagePickerCurrentId).value=imgsrc;
				$(pwsImagePickerCurrentId+'_thumb').src=imgthumb;
				$(pwsImagePickerCurrentId+'_thumb').alt=imgthumb;
			}
		}
		if (pwsImagePickerWindow!=null)
			pwsImagePickerWindow.close();
		pwsImagePickerCurrentId=imgid;
		pwsImagePickerWindow=window.open("<?=URL_DOWNLOAD_REMOTE_IMAGE?>"+"?imgurl="+escape(imgurl),"subWnd","width=10,height=10,screenX=0,screenY=0,resizable=false");
		if (pwsImagePickerWindow.opener == null)
			pwsImagePickerWindow.opener = self;
		pwsImagePickerWindow.thumbnail_id=imgid+'_thumb';
		pwsImagePickerWindow.image_id=imgid;
	}
<?	}	?>
</script>
<?		
		}
	}
	function adminMaxiDVDPostJavascript(){
		$no_editor=($this->tinymce_installed && HTML_EDITOR_CHOICE=='TinyMCE');
		$no_picker=($this->tinymce_installed && IMAGE_PICKER_CHOICE=='PWS Picker');
//MaxiDVD Added WYSIWYG HTML Area Box + Admin Function v1.7 - 2.2 MS2 Products Description HTML - </form>
		if (($no_editor && $no_picker) || HTML_AREA_WYSIWYG_DISABLE == 'Disable' || isset($_REQUEST['subaction'])) {} 
		else {
			global $languages;
   	
?>
            <script language="JavaScript1.2" defer>
             var config = new Object();  // create new config object
             config.width = "<?php echo HTML_AREA_WYSIWYG_WIDTH; ?>px";
             config.height = "<?php echo HTML_AREA_WYSIWYG_HEIGHT; ?>px";
             config.bodyStyle = 'background-color: <?php echo HTML_AREA_WYSIWYG_BG_COLOUR; ?>; font-family: "<?php echo HTML_AREA_WYSIWYG_FONT_TYPE; ?>"; color: <?php echo HTML_AREA_WYSIWYG_FONT_COLOUR; ?>; font-size: <?php echo HTML_AREA_WYSIWYG_FONT_SIZE; ?>pt;';
             config.debug = <?php echo HTML_AREA_WYSIWYG_DEBUG; ?>;
          <?php if (!$no_editor) {
          		for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { ?>
             editor_generate('products_description[<?php echo $languages[$i]['id']; ?>]',config);
          <?php } 
          		}?>
             config.height = "35px";
             config.bodyStyle = 'background-color: white; font-family: Arial; color: black; font-size: 12px;';
             config.toolbar = [ ['InsertImageURL'] ];
             config.OscImageRoot = '<?= trim(HTTP_SERVER . DIR_WS_CATALOG_IMAGES) ?>';
          <?php if (!$no_picker) {?>
             editor_generate('products_image',config);
          <?php }?>  </script>
<?php
		}
	}
	function adminNewProductPreview(){
		global $HTTP_POST_VARS;
		if (!$this->tinymce_installed || IMAGE_PICKER_CHOICE=='HTML_AREA'){
			if (HTML_AREA_WYSIWYG_DISABLE == 'Disable') {
			  // copy image only if modified
			  $products_image = new upload('products_image');
			  $products_image->set_destination(DIR_FS_CATALOG_IMAGES);
			  if ($products_image->parse() && $products_image->save()) {
			    $_REQUEST['products_image']=$HTTP_POST_VARS['products_image'] =  $products_image->filename;
			  } else {
			    $_REQUEST['products_image']=$HTTP_POST_VARS['products_image'] = (isset($HTTP_POST_VARS['products_previous_image']) ? $HTTP_POST_VARS['products_previous_image'] : '');
			  }
			} else {
			  if (isset($HTTP_POST_VARS['products_image']) && tep_not_null($HTTP_POST_VARS['products_image']) && ($HTTP_POST_VARS['products_image'] != 'none')) {
			    //$products_image_name = $HTTP_POST_VARS['products_image'];
			  } else {
			    $_REQUEST['products_image']=$HTTP_POST_VARS['products_image'] = (isset($HTTP_POST_VARS['products_previous_image']) ? $HTTP_POST_VARS['products_previous_image'] : '');
			  }
			}
		}
	}
	// @function getHtmlProductsImage
	// @desc Funzione usata per produrre il codice html per un'immagine di un prodotto
	// @desc Se l'immagine memorizzata nel db è un url, verrà scaricata in locale ed aggiornato il db con il nome del file locale
	// @return	(string)	Codice html dell'immagine
	function getHtmlProductsImage($products_id,$alt,$width=NULL,$height=NULL,$params=NULL){
		$products_id=tep_get_prid($products_id);
		$query=tep_db_query("select products_image from ".TABLE_PRODUCTS." where products_id=$products_id");
		$image=tep_db_fetch_array($query);
		if (NULL==$image)
			return NULL;
		$image=$image['products_image'];
		if ($image==''){ //immagine di default
			$image="icons/no_picture.jpg";
			return tep_image((defined('DIR_WS_CATALOG_IMAGES') ? DIR_WS_CATALOG_IMAGES : DIR_WS_IMAGES).$image,$alt,$width,$height,$params);
		}
		// custom mod per esprinet
		// es. http://194.185.157.5/Controllo_img/img.asp?codice=000031&partic=0&zoom=1
		// l'image � sempre 250x250
		if (strstr($image,'194.185')){
				return tep_image($image,$alt,$width,$height,$params);
		}
		
		// download immagine remota normale
		if (strstr($image,'http://')){
			$parts  = parse_url($image);
			$path = substr($parts['path'],1);
			//  print_r($parts);
			// print $path;
			$path = dirname($path);
			if(!file_exists($path))
				mkdir( DIR_FS_CATALOG . DIR_WS_IMAGES . $path, 0777 , true );
				
			// print  DIR_FS_CATALOG . DIR_WS_IMAGES . $path;
			$image=$this->downloadImage($image, $path . '/' . basename($image),true,false);

	//		print  basename($image);
	//		exit;
			
			if ($image==false)
				return NULL;
	
			tep_db_query("update ".TABLE_PRODUCTS." set products_image='".addslashes($image)."' where products_id='".$products_id."'");
		}
		return tep_image((defined('DIR_WS_CATALOG_IMAGES') ? DIR_WS_CATALOG_IMAGES : DIR_WS_IMAGES).$image,$alt,$width,$height,$params);
	}
	// @function getProductsImage
	// @desc Funzione usata per avere il nome del file di un'immagine di un prodotto
	// @desc Se l'immagine memorizzata nel db è un url, verrà scaricata in locale ed aggiornato il db con il nome del file locale
	// @return	(string)	nome del file dell'immagine nella directory images del catalog
/*	function getProductsImage($products_id,$width=NULL,$height=NULL,$path=true){
		$img_directory=$this->_pws_engine->isAdminSideRunning()?DIR_WS_CATALOG_IMAGES:DIR_WS_IMAGES;
		$products_id=tep_get_prid($products_id);
		$query=tep_db_query("select products_image from ".TABLE_PRODUCTS." where products_id=$products_id");
		$image=tep_db_fetch_array($query);
		if (NULL==$image)
			return NULL;
		$image=$image['products_image'];
		if ($image=='')
			return NULL;
		if (strstr($image,'http://')){
			$image=$this->downloadImage($image,basename($image),true,false);
			if ($image==false)
				return NULL;
			tep_db_query("update ".TABLE_PRODUCTS." set products_image='".addslashes($image)."' where products_id='".$products_id."'");
		}
		if (!is_null($width) && !is_null($height))
			return $this->getThumbnailLocation($image,$width,$height);
		else
			return ($path ? $img_directory:'').$image;
	}
	*/
	
	// generalizza la precedente per tutte le tabelle 
	// @function getProductsImage
	// @desc Funzione usata per avere il nome del file di un'immagine di un prodotto
	// @desc Se l'immagine memorizzata nel db è un url, verrà scaricata in locale ed aggiornato il db con il nome del file locale
	// @return	(string)	nome del file dell'immagine nella directory images del catalog	
	function getProductsImage($field_index_value, $width=NULL,$height=NULL,$path=true, $field_index = 'products_id' , $field_image = 'products_image', $table = TABLE_PRODUCTS){
		$img_directory=$this->_pws_engine->isAdminSideRunning()?DIR_WS_CATALOG_IMAGES:DIR_WS_IMAGES;
	// 	$products_id=tep_get_prid($products_id);
	/*	
	 	print "Var image: " . $field_image . "<br>";
		print "Var tabella: " . $table . "<br>";
		print "Var Indice: " . $field_index . "<br>";
		print "Var Valore Indice: " . $field_index_value . "<br>";
	*/
		$query=tep_db_query("select ". $field_image ." from ". $table ." where $field_index = $field_index_value");
		$image=tep_db_fetch_array($query);
		// print_r($image);
		
		if (NULL==$image)
			return NULL;
		$image=$image[$field_image];
		
		if ($image=='')
			return NULL;
		if (strstr($image,'http://') && (strstr($image,'194.185') === false)  ){ // se � remota ma non esprinet
			$image=$this->downloadImage($image,basename($image),true,false);
			if ($image==false)
				return NULL;
			tep_db_query("update ". $table ." set $field_image = '".addslashes($image)."' where $field_index = '".$field_index_value."'");
		}
		elseif (strstr($image,'http://') && (strstr($image,'194.185'))  ){ // esprinet
			return '';
		}
		if (!is_null($width) && !is_null($height))
			return $this->getThumbnailLocation($image,$width,$height);
		else
			return ($path ? $img_directory:'').$image;
	}	
	
//
//	@function 	downloadImage
//	@desc		Tenta di scaricare un'immagine
//	@param		(string) $imageurl			Indirizzo dell'immagine
//	@param		(string) $filename			Nome locale dell'immagine
//	@param		(string) $forcedownload		Se true scarica anche se il file in locale esiste già
//	@param		(string) $overwrite			Se true sostituisce il file locale, altrimenti scarica il file rinominandolo
//	@return		(bool) false se fallisce lo scaricamento, (bool) true altrimenti
//
	function downloadImage($imageurl,$filename,$forcedownload=true,$overwrite=false){
		if (false!==($image=@file_get_contents($imageurl))){
			if (file_exists($this->images_directory.$filename)){
				if (!$forcedownload)
					return $filename;
				else{
					if ($overwrite){
						chmod($this->images_directory.$filename,0777);
						unlink($this->images_directory.$filename);
					}else{
						$i=0;
						do{
							$i++;
							$filen=explode('.',$filename);
							$ext=array_pop($filen);
							if (sizeof($filen)){
								$fname=array_pop($filen);
								$fname.="_$i";
								array_push($filen,$fname);
							}else{
								$ext.="_$i";
							}
							array_push($filen,$ext);
						}while (file_exists($this->images_directory.implode('.',$filen)));
						$filename=implode('.',$filen);
					}
				}
			}
			file_put_contents($this->images_directory.$filename,$image);
			return $filename;
		}else{
			return false;
		}
	}
	//	@function update
	//	@desc	Funzione di aggiornamento del plugin
	//	@param	string $fromVersion		Vecchia versione
	function update($fromVersion)	{
		switch ($fromVersion){
			case $this->plugin_version_const:
				return false;
			case '0.31':
			case '0.51':
			case '0.61':
			case '0.62':
				$this->installStoreLogos();
			default:
				if ($this->_pws_engine->fieldLength('products_image',TABLE_PRODUCTS)<255)
					tep_db_query("alter table ".TABLE_PRODUCTS." modify column products_image tinytext NULL default ''");
				break;
		}
		parent::update($fromVersion);
	}
	
}
?>