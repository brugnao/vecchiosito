<?php
/**
 * File: turbolister2_ajax.php
 * Created on Oct 10, 2010
 *
 *
 * @category   
 * @package    
 * @author      Mikel Annjuk <anmishael@advancewebsoft.com>
 */
require('includes/application_top.php');
$sql = 'SELECT * FROM ' . TABLE_CONFIGURATION . ' WHERE configuration_key LIKE \'MODULE_TURBOLISTER2_%\'';
$query = tep_db_query($sql);
while($row = tep_db_fetch_array($query)) {
  if(!defined($row['configuration_key'])) {
    define($row['configuration_key'], $row['configuration_value']);
  }
}
include(DIR_WS_CLASSES . "turbolister2_connection.php") ;
include(DIR_FS_ADMIN . "fckeditor/fckeditor.php") ;
require_once DIR_WS_LANGUAGES.$language.'/modules/mod_turbolister2.php';
require_once DIR_WS_LANGUAGES.$language.'/turbolister2_export.php';
require_once DIR_WS_MODULES . 'mod_turbolister2.php';
error_reporting(E_ALL);
ini_set('display_error', true);
set_time_limit(0);
ini_set("upload_max_filesize","256M");
ini_set("post_max_size","256M");
ini_set("memory_limit","256M");
ini_set("session.gc_maxlifetime","10800");
$languages = tep_get_languages();
function getConfigValue($key) {
	$sql = 'SELECT * FROM '.TABLE_CONFIGURATION.' WHERE configuration_key=\''.$key.'\'';
	$conf = tep_db_fetch_array(tep_db_query($sql));
	if(!$conf || !$conf['configuration_group_id']) {
		$sql = 'ALTER TABLE `configuration` CHANGE `configuration_value` `configuration_value` VARCHAR( 1024 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT \'\'';
		tep_db_query($sql);
		$sql = 'SELECT configuration_group_id FROM '.TABLE_CONFIGURATION_GROUP.' WHERE configuration_group_title LIKE \'Opzioni Moduli\'';
		$res = tep_db_fetch_array(tep_db_query($sql));
		$configuration_group_id = $res['configuration_group_id'];
		$arrSql = array(
					'configuration_key'=>$key,
					'configuration_value'=>'',
					'configuration_group_id'=>$configuration_group_id
				);
		tep_db_perform(TABLE_CONFIGURATION, $arrSql);
		$sql = 'SELECT * FROM '.TABLE_CONFIGURATION.' WHERE configuration_key=\''.$key.'\'';
		$conf = tep_db_fetch_array(tep_db_query($sql));
	}
	return $conf;
}
function importProduct($data = array()) {
	$res = false;
	$arrSql = array(
			'products_name'=>tep_db_input($data['Title']),
			'products_quantity'=>tep_db_input($data['Quantity']),
			'products_price'=>$data['StartPrice']
		);
	return $res;
}
function showTree(&$arrCat, $arr, &$res) {
	$res[] = '<td>' . $arr['name'] . '</td>';
	if( isset($arrCat[$arr['parent']]) && $arr['level'] > 1 ) {
		showTree($arrCat, $arrCat[$arr['parent']], $res);
	}
}
switch ( $_GET['action'] ) {
	case 'getHeader':
		$sql = 'SELECT pd.* FROM pages p, pages_description pd WHERE pd.pages_id=p.pages_id AND pd.pages_title LIKE \'Turbolister Header\'';
		$query = tep_db_query($sql);
		$pages_html_text = array();
		$pagetitle = array();
		while($page = tep_db_fetch_array($query))  {
            $languageid = $page['language_id'];
            $pID = $page['pages_id'];
            $pagetitle[$languageid] = $page['pages_title'];
            $pages_html_text[$languageid] = $page['pages_html_text'];
        }
        echo '<h2>Edit Header</h2><table>';
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
?>
            <tr>
            	<td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?></td>
            </tr>
            <tr>
            	<td class="main"><?php
            	echo tep_draw_fckeditor('pages_html_text['.$languages[$i]['id'] . ']', '900', '300', $pages_html_text[$languages[$i]['id']]);
//            	echo tep_draw_textarea_field('pages_html_text['.$languages[$i]['id'] . ']', 'on', '900', '300', $pages_html_text[$languages[$i]['id']])
            	?>
            	</td>
            </tr>
            <?php
		}
		echo '</table>' .
				'<input type="hidden" name="action" value="saveHeader" />' .
				'<input type="hidden" name="noajax" value="true" />' .
				'<input type="hidden" name="pages_id" value="'.$pID.'" />' .
				'<input type="submit" class="button" value="'.TEXT_TURBOLISTER2_UPDATE_BUTTON.'" />';
		break;
	case 'getFooter':
		$sql = 'SELECT p.*, pd.* FROM pages p, pages_description pd WHERE pd.pages_id=p.pages_id AND pd.pages_title LIKE \'Turbolister Footer\'';
		$query = tep_db_query($sql);
		$pages_html_text = array();
		$pagetitle = array();
		while($page = tep_db_fetch_array($query))  {
            $languageid = $page['language_id'];
            $pID = $page['pages_id'];
            $pagetitle[$languageid] = $page['pages_title'];
            $pages_html_text[$languageid] = $page['pages_html_text'];
        }
        echo '<h2>Edit Footer</h2><table>';
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
?>
            <tr>
            	<td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?></td>
            </tr>
            <tr>
            	<td class="main"><?php echo tep_draw_fckeditor('pages_html_text['.$languages[$i]['id'] . ']', '900', '300', $pages_html_text[$languages[$i]['id']]); ?></td>
            </tr>
            <?php
		}
		echo '</table>' .
				'<input type="hidden" name="action" value="saveFooter" />' .
				'<input type="hidden" name="noajax" value="true" />' .
				'<input type="hidden" name="pages_id" value="'.$pID.'" />' .
				'<input type="submit" class="button" value="'.TEXT_TURBOLISTER2_UPDATE_BUTTON.'" />';
		break;
	case 'getFee':
		$conf = getConfigValue('MODULE_TURBOLISTER2_FEE');
		?>
		<input type="text" class="text" name="turboFee" value="<?php echo $conf['configuration_value']?>" size="6" maxlength="3" /> %
		<input type="hidden" name="action" value="saveFee" />
		<input type="submit" class="button" value="<?php echo TEXT_TURBOLISTER2_UPDATE_BUTTON;?>" />
		<?php
		break;
	case 'getSettings':
		$confDevName = getConfigValue('MODULE_TURBOLISTER2_DEV_NAME');
		$confAppName = getConfigValue('MODULE_TURBOLISTER2_APP_NAME');
		$confCertName = getConfigValue('MODULE_TURBOLISTER2_CERT_NAME');
		$confAuthKey = getConfigValue('MODULE_TURBOLISTER2_AUTH_KEY');
		$confSiteID = getConfigValue('MODULE_TURBOLISTER2_SITEID');
		$confConn = getConfigValue('MODULE_TURBOLISTER2_CONNECTION');
		$confDuration = getConfigValue('MODULE_TURBOLISTER2_DURATION');
		$confComm = getConfigValue('MODULE_TURBOLISTER2_COMMISSIONE');
		$confMerZip = getConfigValue('MODULE_TURBOLISTER2_MERCHANT_ZIP');
		$confPayPalEmail = getConfigValue('MODULE_TURBOLISTER2_PAYPAL_EMAIL');
		$confCatLevel = getConfigValue('MODULE_TURBOLISTER2_CATEGORIES_LEVEL');
		?>
		<label for="MODULE_TURBOLISTER2_DEV_NAME">Dev ID</label><br />
		<input type="text" class="text" id="MODULE_TURBOLISTER2_DEV_NAME" name="MODULE_TURBOLISTER2_DEV_NAME" value="<?php echo $confDevName['configuration_value'];?>" /><br /><br />
		<label for="MODULE_TURBOLISTER2_APP_NAME">App ID</label><br />
		<input type="text" class="text" id="MODULE_TURBOLISTER2_APP_NAME" name="MODULE_TURBOLISTER2_APP_NAME" value="<?php echo $confAppName['configuration_value'];?>" /><br /><br />
		<label for="MODULE_TURBOLISTER2_CERT_NAME">Cert ID</label><br />
		<input type="text" class="text" id="MODULE_TURBOLISTER2_CERT_NAME" name="MODULE_TURBOLISTER2_CERT_NAME" value="<?php echo $confCertName['configuration_value'];?>" /><br /><br />
		<label for="MODULE_TURBOLISTER2_AUTH_KEY">Auth Key</label><br />
		<input type="text" class="text" id="MODULE_TURBOLISTER2_AUTH_KEY" name="MODULE_TURBOLISTER2_AUTH_KEY" value="<?php echo $confAuthKey['configuration_value'];?>" /><br /><br />
		<label for="MODULE_TURBOLISTER2_SITEID">Site ID</label><br />
		<input type="text" class="text" id="MODULE_TURBOLISTER2_SITEID" name="MODULE_TURBOLISTER2_SITEID" value="<?php echo $confSiteID['configuration_value'];?>" /><br /><br />
		<label for="MODULE_TURBOLISTER2_DURATION">Duration</label><br />
		<input type="text" class="text" id="MODULE_TURBOLISTER2_DURATION" name="MODULE_TURBOLISTER2_DURATION" value="<?php echo $confDuration['configuration_value'];?>" /><br /><br />
		<label for="MODULE_TURBOLISTER2_CATEGORIES_LEVEL">Categories Level (0 - no limit)</label><br />
		<input type="text" class="text" id="MODULE_TURBOLISTER2_CATEGORIES_LEVEL" name="MODULE_TURBOLISTER2_CATEGORIES_LEVEL" value="<?php echo $confCatLevel['configuration_value'];?>" /><br /><br />
		<label>Connection</label><br />
		<input type="radio" id="sandbox" class="text" name="MODULE_TURBOLISTER2_CONNECTION" value="sandbox" <?php echo ($confConn['configuration_value']=='sandbox')?' checked="checked"':'';?> /><label for="sandbox">Test Mode</label><br />
		<input type="radio" id="production" class="text" name="MODULE_TURBOLISTER2_CONNECTION" value="production" <?php echo ($confConn['configuration_value']=='production')?' checked="checked"':'';?> /><label for="production">Production Mode</label><br /><br />
		
		<label for="MODULE_TURBOLISTER2_COMMISSIONE">Commissione</label><br />
		<textarea id="MODULE_TURBOLISTER2_COMMISSIONE" name="MODULE_TURBOLISTER2_COMMISSIONE" cols="54" rows="3"><?php echo $confComm['configuration_value'];?></textarea><br />
		<label for="MODULE_TURBOLISTER2_MERCHANT_ZIP">Merchant ZIP</label><br />
		<input type="text" class="text" id="MODULE_TURBOLISTER2_MERCHANT_ZIP" name="MODULE_TURBOLISTER2_MERCHANT_ZIP" value="<?php echo $confMerZip['configuration_value'];?>" /><br /><br />
		<label for="MODULE_TURBOLISTER2_PAYPAL_EMAIL">PayPal email</label><br />
		<input type="text" class="text" id="MODULE_TURBOLISTER2_PAYPAL_EMAIL" name="MODULE_TURBOLISTER2_PAYPAL_EMAIL" value="<?php echo $confPayPalEmail['configuration_value'];?>" /><br /><br />
		<br />
		<input type="hidden" name="action" value="saveSettings" />
		<input type="submit" class="button" value="<?php echo TEXT_TURBOLISTER2_UPDATE_BUTTON; ?>" />
		<?php
		break;
	case 'getEBayCategories':
		$objConn = new turbolister2_connection();
		$objConn->setDebug(true);
		$arrData  = array(
				'DetailLevel'=>'ReturnAll',
				'RequesterCredentials'=>array('eBayAuthToken'=>MODULE_TURBOLISTER2_AUTH_KEY),
				'version'=>'687'
			);
		if(MODULE_TURBOLISTER2_CATEGORIES_LEVEL>0) {
			$arrData['LevelLimit'] = MODULE_TURBOLISTER2_CATEGORIES_LEVEL;
		}
		$arrCategories = $objConn->sendRequest('trading', 'GetCategories', $arrData)
			->getResponse()
			->asArray();
//			echo '<pre>'.print_r($arrCategories,1).'</pre>';exit;
//		echo $messageStack->output();
		if(sizeof($arrCategories['Errors'])>0 && ($arrCategories['Errors']['LongMessage'] || $arrCategories['Errors']['Error']['ShortMessage'])) {
			echo '<div class="error">'.$arrCategories['Errors']['Error']['ShortMessage'].'<hr />' . $arrCategories['Errors']['LongMessage'].'</div>';
		} elseif(is_array($arrCategories['CategoryArray']) && is_array($arrCategories['CategoryArray']['Category']) && sizeof($arrCategories['CategoryArray']['Category'])>0) {
			
			
	
			
	$arrCat = array();
	foreach($arrCategories['CategoryArray']['Category'] as $key=>$category) {
		$arrCat[$category['CategoryID']] = array(
					'name'=>$category['CategoryName'],
					'level'=>$category['CategoryLevel'],
					'parent'=>$category['CategoryParentID'],
					'LeafCategory'=>$category['LeafCategory']
					);
		unset($arrCategories['CategoryArray']['Category'][$key]);
	}
	?><div style="width:1020px"><div class="categories_tree" style="float:right;"><table>
	<?php
//	echo '<pre>{ '.print_r($arrCat,1).' }</pre>';
	$arrC = $arrCat;
	foreach($arrCat as $k=>$v) {
		$show = false;
		$output = '';
		/*
		if($v['level']==1) {
			if($v['LeafCategory'] && $v['LeafCategory']=='true') $show = true;
			if($show) $output .= '<td>'.$v['name'].'</td><td></td><td></td>';
		} elseif($v['level']==2) {
			if($v['LeafCategory'] && $v['LeafCategory']=='true') $show = true;
			if($show) $output .= '<td>'.$arrCat[$v['parent']]['name'].'</td>' .
					'<td>'.$v['name'].'</td><td></td>';
		} else {
			if($v['LeafCategory'] && $v['LeafCategory']=='true') $show = true;
			if($show) $output .= '<td>'.$arrCat[$arrCat[$v['parent']]['parent']]['name'].'</td>' .
					'<td>'.$arrCat[$v['parent']]['name'].'</td>' .
							'<td>'.$v['name'].'</td>';
		}
		//*/
//		echo $v['LeafCategory'];
		if($v['LeafCategory'] && $v['LeafCategory']=='true') {
			$show = true;
			$res = array();
			showTree($arrC, $v, $res);
			$res = array_reverse($res);
			$output = implode("\n", $res);
			if($show) echo '<tr><td>' .
				'<input type="button" value="'.TEXT_TURBOLISTER2_APPLY_BUTTON.'" onclick="applyEBayCategory(\''.$v['parent'].'\',\''.$k.'\',\''.addslashes(urlencode($arrCat[$v['parent']]['name'])).'\',\''.addslashes(urlencode($v['name'])).'\')" />' .
				'</td>' . $output .
				'</tr>';
		}
	}
	?>
	</table></div>
	<div class="categories_tree" style="float:left;" id="categoriesTreeArea"></div></div><?php
		}
		break;
	case 'showCategories':
	$arrApplied = array();
	$sql = 'SELECT * FROM tl2_categories';
	$query = tep_db_query($sql);
	while($row = tep_db_fetch_array($query)) {
		$arrApplied[$row['categories_id']] = $row;
	}
	$mod_tlist2=new turbolister2();
	$numfolders=0;
	$mod_tlist2->getCategoriesTree($categories_tree,&$numfolders,$parent_id,$level,$export_categories);
	$nsize=sizeof($categories_tree);
	$siblings=array();
	$curCnt=1;
	$lastChild=false;
	?><?php
	for($i=0; $i<$nsize ;$i++)
	{
		?><div class="treerow"><?php
		if ($lastChild)	{
			$curCnt=array_pop($siblings);
		}
		$cat=&$categories_tree[$i];
		$hasChildren = $i+1<$nsize && $cat['level']<$categories_tree[$i+1]['level'];
		$lastChild = $i+1==$nsize || ($i+1<$nsize && $cat['level']>$categories_tree[$i+1]['level']);
		$hasMoreSiblings=$curCnt>=1;
		$curCnt--;
		if ($hasChildren)	{
			array_push($siblings,$curCnt);
			$curCnt=$cat['subfolders'];
		}

		for ($in=0;$in<$cat['level'];$in++)	{?>
			<img nobr="true" hspace="0" src="<?=DIR_WS_IMAGES.'turbolister2/'?><?=$siblings[$in]>0?'vertline.gif':'blank.gif'?>" class="treegifs" />
					<?php }
					if ($lastChild) {?>
			<img nobr="true" hspace="0" src="<?=DIR_WS_IMAGES.'turbolister2/'?>lastnode.gif" class="treegifs" />
					<?php } else if (!$hasChildren)
					{
						if ($i==0) {?>
			<img hspace="0" src="<?=DIR_WS_IMAGES.'turbolister2/'?>firstnode.gif" class="treegifs" />
						<?php } else {?>
			<img hspace="0" src="<?=DIR_WS_IMAGES.'turbolister2/'?>node.gif" class="treegifs" />
						<?php }
					}
					if ($hasChildren) {?> 
			
						<?php } ?>
			<label for="e_cat_<?=$cat['id']?>" class="<?=$cat['selected']?'selected_category':'unselected_category'?>">
			<input type="checkbox" id="e_cat_<?=$cat['id']?>" onchange="checkChild(document.getElementById('e_folder_<?=$i?>'), this.checked)" name="categories[]" value="<?=$cat['id']?>" /> <?=htmlentities($cat['name'])?> <?php
			if($arrApplied[$cat['id']]) {
				echo '<span style="font-size:x-small;">('.$arrApplied[$cat['id']]['ebay_category_name'] . ($arrApplied[$cat['id']]['ebay_category2_name']?'=>' . $arrApplied[$cat['id']]['ebay_category2_name']:'').')</span>';
			}
			?>
			</label></div>
					<?php
					if ($lastChild) { ?>
			</div>
					<?php } else if ($hasChildren) {?>
			<div id="e_folder_<?=$i?>" class="treeFolder">
					<?php } 
  
	}
			?><?php
		break;
	case 'importProducts':
		$file = DIR_FS_ADMIN . 'turbolister/queue.csv';
		if(!file_exists($file)) {
			?>
			<label for="csvFile"><?php echo TEXT_TURBOLISTER2_SELECT_CSV_FILE?></label><br />
			<input type="file" name="file" id="csvFile" /><br />
			<input type="hidden" name="action" value="importProducts" />
			<input type="submit" value="<?php echo TEXT_TURBOLISTER2_UPLOAD_BUTTON;?>" />
			<?php
		} else {
//			$mod_tlist2=new turbolister2();
//			echo '<pre>'.print_r($mod_tlist2->getCategoryTree(39783),1).'</pre>';
//			echo '<pre>'.print_r($mod_tlist2->getCategoryQuickTree(39783),1).'</pre>';
//			if($messageStack->size>0) echo $messageStack->output();
			
			$mod_tlist2=new turbolister2();
//			print_r($_REQUEST['cat']);
			$res = $mod_tlist2->import(DIR_FS_ADMIN . 'turbolister/queue.csv');
			if($res && is_array($res) && sizeof($res)>0) {
				echo sprintf(TEXT_TURBOLISTER2_IMPORT_SUCCESSFULY, sizeof($res));
			} else {
				echo TEXT_TURBOLISTER2_IMPORT_NOTHING;
			}
			if(file_exists(DIR_FS_ADMIN . 'turbolister/queue.csv')) {
				echo TEXT_TURBOLISTER2_UPLOADED_QUEUE.'<br /><input onclick="$(\'#importProducts\').importFileDelete();" type="button" value="'.TEXT_TURBOLISTER2_UPLOADED_REMOVE_BUTTON.'" />';
			}
			if($messageStack->size>0) echo $messageStack->output();
//			echo '<pre>'.print_r($res,1).'</pre>';
			/*/
			/*
			$fp = fopen (DIR_FS_ADMIN . 'turbolister/queue.csv',"r");
			$cols = fgetcsv ($fp, 102400, ";");
			$arrColNames = array();
			foreach($cols as $k=>$v) {
				$arrColNames[$v] = $k;
			}
			reset($cols);
			$arrNeeded = array('Title', 'SubtitleText', 'Custom Label', 'Category 1', 'Category 2', 'PicURL', 'Store Category', 'Store Category 2', 'Starting Price');
			echo '<style>
#importArea table tr td {vertical-align:top;white-space:nowrap;}
div.topLabel:hover .under {display:inline;visibility:visible;position:absolute;background-color: #eee;border: 1px solid #ccc;margin-left: -50px;}
.under{display:none;visibility:hidden;}
</style>
<div id="importArea" style="overflow:auto;width:960px;border:1px solid #ccc;"><table><tr>';
			
			foreach($cols as $k=>$v) {
				if(in_array($v, $arrNeeded)) 
				echo '<td>'.$v.'</td>';
			}
			echo '</tr>';
			reset($cols);
			while ($data = fgetcsv ($fp, 10240, ";")) {
				echo '<tr>';
				$res = importProduct($data);
				foreach($data as $k=>$v) {
					if(in_array($cols[$k], $arrNeeded)) {
						echo '<td><div class="topLabel">'.substr(strip_tags($v), 0, 30).'<p class="under">'.str_replace("  ", "<br />", str_replace("   ","  ",htmlspecialchars(str_replace('""','"',$v)))).'</p></div></td>';
					}
				}
				echo '</tr>';
			}
			echo '</table></div>';
			fclose($fp);
			//*/
		}
		break;
	case 'importFileDelete':
		$file = DIR_FS_ADMIN . 'turbolister/queue.csv';
		if(unlink($file)) {
			echo TEXT_TURBOLISTER2_UPLOADED_REMOVED;
		}
		
		break;
	case 'applyEBayCategories':
		if($_GET['current'] && sizeof($_POST['categories'])>0) {
			$sql = 'DELETE FROM tl2_categories WHERE categories_id IN (\''.implode('\',\'', $_POST['categories']).'\')';
			tep_db_query($sql);
			$objConn = new turbolister2_connection();
			if($_GET['current']!=$_GET['parent']) {
				$arrData = array(
						'CategoryID'=>$_GET['current']
					);
				
				$arrCurrent = $objConn->sendRequest('shopping', 'GetCategoryInfo', $arrData)
				->getResponse()
				->asArray();
			}
			$arrData = array(
					'CategoryID'=>$_GET['parent']
				);
			
//			$arrParent = $objConn->sendRequest('shopping', 'GetCategoryInfo', $arrData)
//			->getResponse()
//			->asArray();
//			print_r($arrData);
//			echo print_r($arrParent,1).print_r($arrCurrent,1);
//			echo print_r($arrParent['CategoryArray'],1).print_r($arrCurrent['CategoryArray'],1);
			foreach($_POST['categories'] as $k=>$v) {
				$arrSql = array(
						'categories_id'=>$v,
						'ebay_category'=>$_GET['parent'],
						'ebay_category2'=>$_GET['current'],
						'ebay_category_name'=>$_GET['pname'],//($arrParent['CategoryArray']['Category']['CategoryName']?$arrParent['CategoryArray']['Category']['CategoryName']:''),
						'ebay_category2_name'=>$_GET['cname']//($arrCurrent['CategoryArray']['Category']['CategoryName']?$arrCurrent['CategoryArray']['Category']['CategoryName']:'')
					);
				tep_db_perform('tl2_categories', $arrSql);
			}
		}
		break;
	default:
		break;
}
switch ( $_POST['action'] ) {
	case 'saveHeader':
		if(is_array($_POST['pages_html_text']) && $_POST['pages_id']) {
			foreach($_POST['pages_html_text'] as $lang=>$val) {
				$arrSql = array(
						'pages_html_text'=>stripslashes($val)
					);
				$res = tep_db_perform(TABLE_PAGES_DESCRIPTION, $arrSql, 'update', 'pages_id=\''.$_POST['pages_id'].'\' and language_id=\''.$lang.'\'');
			}
			echo '$("#editFeeBtn").getHeader();';//alert(\''.addslashes(htmlspecialchars(print_r($_POST,1))).'\');';
		}
		break;
	case 'saveFooter':
		if(is_array($_POST['pages_html_text']) && $_POST['pages_id']) {
			foreach($_POST['pages_html_text'] as $lang=>$val) {
				$arrSql = array(
						'pages_html_text'=>stripslashes($val)
					);
				$res = tep_db_perform(TABLE_PAGES_DESCRIPTION, $arrSql, 'update', 'pages_id=\''.$_POST['pages_id'].'\' and language_id=\''.$lang.'\'');
			}
		}
		break;
	case 'saveFee':
		$arrSql = array(
						'configuration_value'=>(float)$_POST['turboFee']
					);
		tep_db_perform(TABLE_CONFIGURATION, $arrSql, 'update', 'configuration_key=\'MODULE_TURBOLISTER2_FEE\'');
		echo '$("#editFeeBtn").getFee();';
		break;
	case 'saveSettings':
		$arrSql = array(
						'configuration_value'=>$_POST['MODULE_TURBOLISTER2_DEV_NAME']
					);
		tep_db_perform(TABLE_CONFIGURATION, $arrSql, 'update', 'configuration_key=\'MODULE_TURBOLISTER2_DEV_NAME\'');
		$arrSql = array(
						'configuration_value'=>$_POST['MODULE_TURBOLISTER2_APP_NAME']
					);
		tep_db_perform(TABLE_CONFIGURATION, $arrSql, 'update', 'configuration_key=\'MODULE_TURBOLISTER2_APP_NAME\'');
		$arrSql = array(
						'configuration_value'=>$_POST['MODULE_TURBOLISTER2_CERT_NAME']
					);
		tep_db_perform(TABLE_CONFIGURATION, $arrSql, 'update', 'configuration_key=\'MODULE_TURBOLISTER2_CERT_NAME\'');
		$arrSql = array(
						'configuration_value'=>$_POST['MODULE_TURBOLISTER2_AUTH_KEY']
					);
		tep_db_perform(TABLE_CONFIGURATION, $arrSql, 'update', 'configuration_key=\'MODULE_TURBOLISTER2_AUTH_KEY\'');
		$arrSql = array(
						'configuration_value'=>(int)$_POST['MODULE_TURBOLISTER2_SITEID']
					);
		tep_db_perform(TABLE_CONFIGURATION, $arrSql, 'update', 'configuration_key=\'MODULE_TURBOLISTER2_SITEID\'');
		$arrSql = array(
						'configuration_value'=>(int)$_POST['MODULE_TURBOLISTER2_DURATION']
					);
		tep_db_perform(TABLE_CONFIGURATION, $arrSql, 'update', 'configuration_key=\'MODULE_TURBOLISTER2_DURATION\'');
		$arrSql = array(
						'configuration_value'=>$_POST['MODULE_TURBOLISTER2_CONNECTION']
					);
		tep_db_perform(TABLE_CONFIGURATION, $arrSql, 'update', 'configuration_key=\'MODULE_TURBOLISTER2_CONNECTION\'');
		
		$arrSql = array(
						'configuration_value'=>$_POST['MODULE_TURBOLISTER2_COMMISSIONE']
					);
		tep_db_perform(TABLE_CONFIGURATION, $arrSql, 'update', 'configuration_key=\'MODULE_TURBOLISTER2_COMMISSIONE\'');
		
		$arrSql = array(
						'configuration_value'=>$_POST['MODULE_TURBOLISTER2_MERCHANT_ZIP']
					);
		tep_db_perform(TABLE_CONFIGURATION, $arrSql, 'update', 'configuration_key=\'MODULE_TURBOLISTER2_MERCHANT_ZIP\'');
		
		$arrSql = array(
						'configuration_value'=>$_POST['MODULE_TURBOLISTER2_PAYPAL_EMAIL']
					);
		tep_db_perform(TABLE_CONFIGURATION, $arrSql, 'update', 'configuration_key=\'MODULE_TURBOLISTER2_PAYPAL_EMAIL\'');
		
		$arrSql = array(
						'configuration_value'=>$_POST['MODULE_TURBOLISTER2_CATEGORIES_LEVEL']
					);
		tep_db_perform(TABLE_CONFIGURATION, $arrSql, 'update', 'configuration_key=\'MODULE_TURBOLISTER2_CATEGORIES_LEVEL\'');
		
		echo '$("#editSettingsBtn").getSettings();';
		break;
	case 'importProducts':
		if($_FILES) {
			if(is_uploaded_file($_FILES['file']['tmp_name'])) {
				if(!move_uploaded_file($_FILES['file']['tmp_name'], DIR_FS_ADMIN . 'turbolister/queue.csv')) {
					echo TEXT_TURBOLISTER2_UPLOADING_ERROR.'!!!';
				} else {
					echo TEXT_TURBOLISTER2_UPLOADED . '<input onclick="$(\'#importProducts\').importProducts();" type="button" value="'.TEXT_TURBOLISTER2_IMPORT_BUTTON.'" />';
				}
			} else {
				echo TEXT_TURBOLISTER2_UPLOADING_ERROR.'!!!2';
			}
		} else {
			echo '<pre>Upload result:'.print_r($_POST,1).print_r($_GET,1).print_r($_FILES).'</pre>';
		}
		break;
	default:
		break;
}
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>