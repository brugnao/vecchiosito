<?php
/**
 * Created on 9 Oct. 2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
chdir('../');

require('includes/application_top.php');
 
	$relprod_query="select * from pws_related_products where products_id='".$_GET['pid']."' order by prodrel_order";
	$relprod_query=tep_db_query($relprod_query);
	if (mysql_num_rows($relprod_query) == '0')
	exit;
 //  print_r($_SESSION);
// 	echo $pws_engine->triggerHook('CATALOG_PRODUCT_INFO_SELECT_PRODUCTS');
//  exit;

	
	require_once('icecat/include.php');
	

	
//*/
  require_once DIR_WS_ICECAT_LIBS . 'jshttprequest/config.php';
  require_once DIR_WS_ICECAT_LIBS . 'jshttprequest/Subsys/JsHttpRequest/JsHttpRequest.php';

  if(file_exists(DIR_FS_ICECAT . 'languages/' . $language . '.php')) {
	require_once(DIR_FS_ICECAT . 'languages/' . $language . '.php');
  } elseif(file_exists(DIR_FS_ICECAT . 'languages/english.php')) {
	require_once(DIR_FS_ICECAT . 'languages/english.php');
  }
	function getJSString($arr = array()) {
		$text = 'value=new Array();';
		foreach ($arr as $k => $v) $text .= 'value[' . $k . ']=new Array();'.
		'value[' . $k . '].name="' . $v['name'] . '";'.
		'value[' . $k . '].text="' . addslashes($v['text']) . '";' . (strlen(trim($v['js']))>0 ? $v['js']  .';' : '');
		return $text;
	}
	function prepareString($str) {
		$str = str_replace("\n", ' ', $str);
		$str = str_replace("\r", ' ', $str);
		return $str;
	}
	$jsText = array();
	$mArr = array();
	define('DEBUG_MOD', false);

	$JsHttpRequest =& new JsHttpRequest("ISO-8859-1");
	$q = $_REQUEST['q'];
  	if(tep_not_null($_GET['pid'])) {
  		
  	if(file_exists("cache/".$_GET['pid']."_lang".$languages_id. ".html"))
  	{
  		$p_descriprion = file_get_contents(	"cache/".$_GET['pid']."_lang".$languages_id. ".html" );
  		  $row['products_description'] =  $p_descriprion;
  		
  	}					
  	else
  	{	//
  		$sql = 'SELECT p.vpn, pd.products_description, m.manufacturers_name ' .
  				'FROM products p LEFT JOIN (manufacturers m) ON (m.manufacturers_id=p.manufacturers_id), products_description pd ' .
  				'WHERE p.products_id=\'' . (int)$pid . '\' AND pd.products_id=p.products_id AND pd.language_id=\'' . $languages_id . '\'';
  		
  		$sql = tep_db_query($sql);
  		$row = tep_db_fetch_array($sql);	
  		// crea la cache per la descrizione del prodotto
	  		$fp = fopen("cache/".$_GET['pid']."_lang".$languages_id. ".html","w");
		    fwrite($fp, $row['products_description']);
			// close the file
	        fclose($fp);  
  	}
  		
	        
	
  		$lang = tep_db_fetch_array(tep_db_query('SELECT code FROM languages WHERE languages_id=\'' . $languages_id . '\''));
  		$lang = $lang['code'];

  		$strMan = $row['manufacturers_name'];
  		if(tep_not_null($mID)) {
  			$sql = 'SELECT m.manufacturers_name FROM manufacturers m WHERE m.manufacturers_id=\'' . $mID . '\'';
  			$arrMan = tep_db_fetch_array(tep_db_query($sql));
  			$strMan = $arrMan['manufacturers_name'];
  		}
  		if(!tep_not_null($vpn)) $vpn=$row['vpn'];
  		
 		

	  	switch ( $action ) {
			case 'getDesc':
				$jsText[] = 'var arrICEMenu=new Array();';
				$arrMenuIter = 0;
				if(strlen(trim($row['products_description']))>0 && trim($row['products_description'])) {
					$jsText[] = 'arrICEMenu['.$arrMenuIter.']=new Array();';
					$jsText[] = 'arrICEMenu['.$arrMenuIter.'][\'name\']=\''.addslashes(ICECAT_TAB_DESCRIPTION).'\';';
					$jsText[] = 'arrICEMenu['.$arrMenuIter.'][\'js\']=\'doLoad(\\\'pid='.(int)$pid.'&languages_id='.$languages_id.'\\\',null,\\\'productDesc\\\');\';';
					$arrMenuIter++;
				}
				if(tep_not_null($arrICProd)||(int)tep_db_num_rows($relprod_query)>0) {
					if(tep_not_null($arrICProd)) {
						$jsText[] = 'arrICEMenu['.$arrMenuIter.']=new Array();';
						$jsText[] = 'arrICEMenu['.$arrMenuIter.'][\'name\']=\''.addslashes(ICECAT_TAB_SPECS).'\';';
						$jsText[] = 'arrICEMenu['.$arrMenuIter.'][\'js\']=\'doLoad(\\\'action=getDesc&pid='.(int)$pid.'&languages_id='.$languages_id.'\\\',null,\\\'productDesc\\\');\';';
						$jsText[] = 'arrICEMenu['.$arrMenuIter.'][\'css\']=\' selected\';';
						$arrMenuIter++;
					}
					
					if((int)tep_db_num_rows($relprod_query)>0) {
						$jsText[] = 'arrICEMenu['.$arrMenuIter.']=new Array();';
						$jsText[] = 'arrICEMenu['.$arrMenuIter.'][\'name\']=\''.addslashes(ICECAT_TAB_RELATED_PRODUCTS).'\';';
						$jsText[] = 'arrICEMenu['.$arrMenuIter.'][\'js\']=\'doLoad(\\\'action=getRelated&pid='.(int)$pid.'&languages_id='.$languages_id.'\\\',null,\\\'productDesc\\\');\';';
						$arrMenuIter++;
					}
					$arrFeatures = array();
					foreach($arrICProd['Product']['ProductFeature'] as $arrFeature) {
						if(!is_array($arrFeatures[$arrFeature[1]['CategoryFeatureGroup_ID']])) $arrFeatures[$arrFeature[1]['CategoryFeatureGroup_ID']] = array();
						$arrFeatures[$arrFeature[1]['CategoryFeatureGroup_ID']][] = array(
										'name'=>$arrFeature['Feature']['Name'][1]['Value'],
										'value'=>$arrFeature[1]['Presentation_Value']
										);
					}
					$jsText[] = 'var arrIceCatProducts = new Array();';
					$xy = 0;
					foreach($arrICProd['Product']['CategoryFeatureGroup'] as $arrGroup) {
						if($arrFeatures[$arrGroup[1]['ID']]) {
							$jsText[] = 'arrIceCatProducts['.$xy.']=new Array();';
							$jsText[] = 'arrIceCatProducts['.$xy.'][\'fgVal\']=\'' . addslashes($arrGroup['FeatureGroup']['Name'][1]['Value']) . '\';';
							$fg = 0;
							$jsText[] = 'arrIceCatProducts['.$xy.'][\'fgArr\']=new Array();';
							foreach($arrFeatures[$arrGroup[1]['ID']] as $arrFeature) {
								$jsText[] = 'arrIceCatProducts['.$xy.'][\'fgArr\']['.$fg.']=new Array();';
								$jsText[] = 'arrIceCatProducts['.$xy.'][\'fgArr\']['.$fg.'][\'fname\']=\'' . addslashes($arrFeature['name']) . '\';';
								if($arrFeature['value']=='Y') {
									$jsText[] = 'arrIceCatProducts['.$xy.'][\'fgArr\']['.$fg.'][\'img\']=\''.DIR_WS_ICECAT.'images/yes.gif\';';
								} else {
									$jsText[] = 'arrIceCatProducts['.$xy.'][\'fgArr\']['.$fg.'][\'fvalue\']=\''.addslashes(str_replace('\n','<br/>',$arrFeature['value'])).'\';';
								}
								$fg++;
							}
							$xy++;
						}
					}
				}
				$jsText[]='buildICMenu(arrICEMenu);showICDesc(arrIceCatProducts,null,null);';
				break;
			case 'getAdminDesc':
				$objIceCat = new icecat();
  				$objIceCat->init();
				$arrICProd = $objIceCat->getProduct($vpn,$strMan,$lang,null,$pid);
  				
//				$jsText[]='el.innerHTML=\'' . addslashes($row['products_description']) . '\';';
				if(tep_not_null($arrICProd)) {
					$sql = 'SELECT p.vpn,m.manufacturers_name FROM products p, manufacturers m WHERE p.products_id=\'' . (int)$pid . '\' AND m.manufacturers_id=p.manufacturers_id';
			  	  $sql = tep_db_query($sql);
			  	  $product_info = tep_db_fetch_array($sql);
				  $lang = tep_db_fetch_array(tep_db_query('SELECT code FROM languages WHERE languages_id=\'' . $languages_id . '\''));
				  $lang = $lang['code'];
				  if(tep_not_null($vpn) && tep_not_null($product_info['manufacturers_name'])) {
				  		
//						$objIceCat->clearAdditionalImages();
//						$arrICProd = $objIceCat->getProduct($product_info['vpn'],$product_info['manufacturers_name'],'en');
						if(tep_not_null($arrICProd['Product']) && tep_not_null($arrICProd['Product']['ProductGallery']) && tep_not_null($arrICProd['Product']['ProductGallery']['ProductPicture'])) {
							$imgExists = false;
							$sql = 'SELECT products_image FROM products WHERE products_id=\'' . (int)$pid . '\'';
							$row = tep_db_fetch_array(tep_db_query($sql));
							if(tep_not_null($row['products_image'])) $imgExists = true;
							$i=0;
							$objIceCat->clearAdditionalImages($pid);
							// print_r($arrICProd);
							
							foreach($arrICProd['Product']['ProductGallery']['ProductPicture'] as $arrPic) {
							//	print_r($arrPic);
				//				print_r($product_info);
				//				exit;
								$objIceCat->downloadImage($arrPic[1]['Pic'], $product_info['manufacturers_name'], (int)$pid, $imgExists, $i);
								$imgExists=true;
								$i++;
							}
						}
				  }
					$arrFeatures = array();
					foreach($arrICProd['Product']['ProductFeature'] as $arrFeature) {
						if(!is_array($arrFeatures[$arrFeature[1]['CategoryFeatureGroup_ID']])) $arrFeatures[$arrFeature[1]['CategoryFeatureGroup_ID']] = array();
						$arrFeatures[$arrFeature[1]['CategoryFeatureGroup_ID']][] = array(
										'name'=>$arrFeature['Feature']['Name'][1]['Value'],
										'value'=>$arrFeature[1]['Presentation_Value']
										);
					}
					$jsText[]='var txtArea=document.createElement(\'div\');';
//					$jsText[]='txtArea.appendChild(el);';
					$jsText[]='txtArea.className=\'icecatDescArea\';';
					foreach($arrICProd['Product']['CategoryFeatureGroup'] as $arrGroup) {
						if($arrFeatures[$arrGroup[1]['ID']]) {
							$jsText[] = 'var dv=document.createElement(\'div\');';
							$jsText[] = 'dv.className=\'featureGroup\';';
							$jsText[] = 'txtArea.appendChild(dv);';
//							TABLE {{
							$jsText[] = 'var theTbl=document.createElement(\'table\');';
							$jsText[] = 'theTbl.className=\'desc\';';
							$jsText[] = 'theTbl.width=\'100%\';';
							$jsText[] = 'theTbl.cellSpacing=\'1\';';
							$jsText[] = 'theTbl.cellPadding=\'0\';';
							
							$jsText[] = 'var theRow=document.createElement(\'tr\');';
							$jsText[] = 'var theCol=document.createElement(\'th\');';
							$jsText[] = 'theCol.colSpan=\'2\';';
							$jsText[] = 'theCol.appendChild(document.createTextNode(\'' . addslashes($arrGroup['FeatureGroup']['Name'][1]['Value']) . '\'));';
							$jsText[] = 'theRow.appendChild(theCol);';
							$jsText[] = 'theTbl.appendChild(theRow);';
							
							foreach($arrFeatures[$arrGroup[1]['ID']] as $arrFeature) {
								$jsText[] = 'var dvc=document.createElement(\'div\');';
								$jsText[] = 'var theRow=document.createElement(\'tr\');';
								$jsText[] = 'var theCol=document.createElement(\'td\');';
								$jsText[] = 'theCol.appendChild(document.createTextNode(\'' . addslashes($arrFeature['name']) . '\'));';
								$jsText[] = 'theCol.className=\'main name\';';
								$jsText[] = 'theCol.vAlign=\'top\';';
								$jsText[] = 'theRow.appendChild(theCol);';
								$jsText[] = 'var theCol=document.createElement(\'td\');';
								if($arrFeature['value']=='Y') {
									$jsText[] = 'var val=document.createElement(\'img\');';
									$jsText[] = 'val.src=\'/' . DIR_WS_ICECAT . 'images/yes.gif\';';
								} else {
									$jsText[] = 'var val=document.createElement(\'span\');';
									$jsText[] = 'val.innerHTML=\'' . addslashes(str_replace('\n','<br/>',$arrFeature['value'])) . '\';';
								}
								$jsText[] = 'theCol.appendChild(val);';
								$jsText[] = 'theCol.className=\'main value\';';
								$jsText[] = 'theRow.appendChild(theCol);';
								
								$jsText[] = 'theTbl.appendChild(theRow);';
							}
							
							$jsText[] = 'dvc.appendChild(theTbl);';
							$jsText[] = 'dvc.className=\'content\';';
							$jsText[] = 'dv.appendChild(dvc);';
//							TABLE }}
						}
					}
//					$jsText[] = 'col.appendChild(document.createTextNode(\'' . addslashes($arrICProd) . '\'));';
//					$jsText[] = 'row.appendChild(col);';
				} elseif(sizeof($objIceCat->arrErrors)>0) {
					$jsText[]='var txtArea=document.createElement(\'div\');';
					$jsText[]='txtArea.className=\'errorText\';';
					$jsText[]='var el=document.createElement(\'div\');';
					$jsText[]='el.innerHTML=\'ICECAT Errors:\';';
					$jsText[]='txtArea.appendChild(el);';
					foreach($objIceCat->arrErrors as $k=>$v) {
						$jsText[]='var el=document.createElement(\'div\');';
						$jsText[]='el.innerHTML=\'' . addslashes($v) . '\';';
						$jsText[]='txtArea.appendChild(el);';
					}
				}
				$jsText[]='var area=document.getElementById(\'' . $area . '\');';
				$jsText[]='area.appendChild(txtArea);';
				break;
			case 'getRelated':
				$jsText[] = 'var arrICEMenu=new Array();';
				$arrMenuIter=0;
				if(strlen(trim($row['products_description']))>0 && trim($row['products_description'])) {
					$jsText[] = 'arrICEMenu['.$arrMenuIter.']=new Array();';
					$jsText[] = 'arrICEMenu['.$arrMenuIter.'][\'name\']=\''.addslashes(ICECAT_TAB_DESCRIPTION).'\';';
					$jsText[] = 'arrICEMenu['.$arrMenuIter.'][\'js\']=\'doLoad(\\\'pid='.(int)$pid.'&languages_id='.$languages_id.'\\\',null,\\\'productDesc\\\');\';';
					$arrMenuIter++;
				}
				$relprod_query="select * from pws_related_products where products_id='$pid' order by prodrel_order";
				$relprod_query=tep_db_query($relprod_query);
				if(tep_not_null($arrICProd) || (int)tep_db_num_rows($relprod_query)>0) {
					if(tep_not_null($arrICProd)) {
						$jsText[] = 'arrICEMenu['.$arrMenuIter.']=new Array();';
						$jsText[] = 'arrICEMenu['.$arrMenuIter.'][\'name\']=\''.addslashes(ICECAT_TAB_SPECS).'\';';
						$jsText[] = 'arrICEMenu['.$arrMenuIter.'][\'js\']=\'doLoad(\\\'action=getDesc&pid='.(int)$pid.'&languages_id='.$languages_id.'\\\',null,\\\'productDesc\\\');\';';
						$arrMenuIter++;
					}
					
					if((int)tep_db_num_rows($relprod_query)>0) {
						$jsText[] = 'arrICEMenu['.$arrMenuIter.']=new Array();';
						$jsText[] = 'arrICEMenu['.$arrMenuIter.'][\'name\']=\''.addslashes(ICECAT_TAB_RELATED_PRODUCTS).'\';';
						$jsText[] = 'arrICEMenu['.$arrMenuIter.'][\'js\']=\'doLoad(\\\'action=getRelated&pid='.(int)$pid.'&languages_id='.$languages_id.'\\\',null,\\\'productDesc\\\');\';';
						$jsText[] = 'arrICEMenu['.$arrMenuIter.'][\'css\']=\' selected\';';
						$arrMenuIter++;
					}
					// tab prodotti correlati
					chdir(DIR_FS_CATALOG);
					
					// inizio tabella correlati. usiamo il modulo del pws engine
					$jsText[] = 'var arrRelProducts=\'' . addslashes($pws_engine->triggerHook('CATALOG_PRODUCT_INFO_SELECT_PRODUCTS')) . '\';';
			//		mail ("info@oscommerce.it",'jstext',addslashes($pws_engine->triggerHook('CATALOG_PRODUCT_INFO_SELECT_PRODUCTS')) );
			/*		while($thres=tep_db_fetch_array($relprod_query)) {
						$product = tep_db_fetch_array(tep_db_query('SELECT pd.products_name,p.products_image,p.products_id,p.products_model FROM products p, products_description pd WHERE pd.products_id=p.products_id AND p.products_id=\'' . (int)$thres['to_products_id'] . '\' AND pd.language_id=\'' . $languages_id . '\''));
						$jsText[] = 'var div=document.createElement(\'div\');';
						$jsText[] = 'div.className=\'icecatRelProd\';';
						$jsText[] = 'var imgarea=document.createElement(\'a\');';
						$jsText[] = 'imgarea.href=\'' . addslashes(DIR_WS_IMAGES . $product['products_image']) . '\';';
						$jsText[] = 'imgarea.rel=\'lightbox[np' . addslashes($product['products_id']). ']\';';
						$jsText[] = 'imgarea.innerHTML=\''.addslashes(tep_image(DIR_FS_CATALOG.DIR_WS_IMAGES.$product['products_image'],$product['products_name'],SMALL_IMAGE_WIDTH,SMALL_IMAGE_HEIGHT,'border="0"')).'\';';
						$jsText[] = 'var tbl=document.createElement(\'table\');var tbdx=document.createElement(\'tbody\');var tr=document.createElement(\'tr\');var td1=document.createElement(\'td\');var td2=document.createElement(\'td\');';
						$jsText[] = 'tbl.cellSpacing=\'0\';tbl.cellPadding=\'0\';tbl.border=\'0\';td1.style.borderWidth=\'0px\';td2.style.borderWidth=\'0px\';';
						$jsText[] = 'tbl.appendChild(tbdx);tbdx.appendChild(tr);tr.appendChild(td1);tr.appendChild(td2);';
						$jsText[] = 'td1.appendChild(imgarea);';
						$jsText[] = 'var a=document.createElement(\'a\');a.innerHTML=\''.addslashes($product['products_name']).'<br />\';td2.appendChild(a);';
						$jsText[] = 'a.href=\'' . addslashes(tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $product['products_id'])) . '\';';
						$jsText[] = 'var img=document.createElement(\'img\');img.src=\'/' . DIR_WS_LANGUAGES . $language . '/images/buttons/button_details.gif\';img.alt=\'' . addslashes(IMAGE_BUTTON_DETAILS) . '\';img.border=\'0\';';
						$jsText[] = 'var a2=document.createElement(\'a\');a2.href=a.href;a2.appendChild(img);';
						$jsText[] = 'var desc=document.createElement(\'div\');desc.innerHTML=\'' . str_replace('\'','&39;',$product['products_model']) . '\';';
						$jsText[] = 'td2.appendChild(desc);';
						$jsText[] = 'td2.appendChild(a2);';
						$jsText[] = 'div.appendChild(tbl);';
						$jsText[] = 'col.appendChild(div);';
					}
				*/
				} else {
					$jsText[] = 'var arrRelProducts=\'&nbsp;\';';
				}
				$jsText[] = 'buildICMenu(arrICEMenu);showICDesc(null,arrRelProducts,null);';
				break;
			default:
				$jsText[] = 'var arrICEMenu=new Array();';
				$arrMenuIter=0;
				if(strlen(trim($row['products_description']))>0 && trim($row['products_description'])!='<br />') {
					$jsText[] = 'arrICEMenu['.$arrMenuIter.']=new Array();';
					$jsText[] = 'arrICEMenu['.$arrMenuIter.'][\'name\']=\''.addslashes(ICECAT_TAB_DESCRIPTION).'\';';
					$jsText[] = 'arrICEMenu['.$arrMenuIter.'][\'js\']=\'doLoad(\\\'pid='.(int)$pid.'&languages_id='.$languages_id.'\\\',null,\\\'productDesc\\\');\';';
					$jsText[] = 'arrICEMenu['.$arrMenuIter.'][\'css\']=\' selected\';';
					$arrMenuIter++;
				}
				$relprod_query="select * from pws_related_products where products_id='$pid' order by prodrel_order";
				$relprod_query=tep_db_query($relprod_query);
				if(tep_not_null($arrICProd) || (int)tep_db_num_rows($relprod_query)>0) {
					if(tep_not_null($arrICProd)) {
						$jsText[] = 'arrICEMenu['.$arrMenuIter.']=new Array();';
						$jsText[] = 'arrICEMenu['.$arrMenuIter.'][\'name\']=\''.addslashes(ICECAT_TAB_SPECS).'\';';
						$jsText[] = 'arrICEMenu['.$arrMenuIter.'][\'js\']=\'doLoad(\\\'action=getDesc&pid='.(int)$pid.'&languages_id='.$languages_id.'\\\',null,\\\'productDesc\\\');\';';
						$arrMenuIter++;
					}
					
					if((int)tep_db_num_rows($relprod_query)>0) {
						$jsText[] = 'arrICEMenu['.$arrMenuIter.']=new Array();';
						$jsText[] = 'arrICEMenu['.$arrMenuIter.'][\'name\']=\''.addslashes(ICECAT_TAB_RELATED_PRODUCTS).'\';';
						$jsText[] = 'arrICEMenu['.$arrMenuIter.'][\'js\']=\'doLoad(\\\'action=getRelated&pid='.(int)$pid.'&languages_id='.$languages_id.'\\\',null,\\\'productDesc\\\');\';';
						$arrMenuIter++;
					}
				}
				if(strlen(trim($row['products_description']))>0 && trim($row['products_description'])) {
					$jsText[] = 'var strdescription=\'' . addslashes($row['products_description']) . '\';';
				}
				$jsText[] = 'buildICMenu(arrICEMenu);showICDesc(null,null,strdescription);';
				break;
		}  
  	}
	$_RESULT = array(
	  "q"   => $q,
	  "text" => prepareString(getJSString($mArr)),
	  "jsText" =>implode('',prepareString($jsText))
	);
	
	if (strpos($q, 'error') !== false) {
	  callUndefinedFunction();
	}
	if (@$_REQUEST['dt']) {
	  sleep($_REQUEST['dt']);
	}
	
//  	tep_db_close($link);
?>