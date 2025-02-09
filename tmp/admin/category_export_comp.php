<?php
/*
 * @filename:	category_export_comp.php
 * @version:	0.1
 * @project:	Category comparison price export
 *
 * @created:	05.04.2011
 *
 */

	require('includes/application_top.php');

// FINE CACHE FILE PHP

set_time_limit(0);
ini_set('display_errors', true);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
require_once(DIR_WS_CLASSES . 'currencies.php');
    $currencies = new currencies();

    require_once(DIR_WS_CLASSES . 'Category.php');
    require_once(DIR_WS_CLASSES . 'Categories.php');
$objCategories = new Categories();


if($_GET['action']) {
    switch ($_GET['action']) {
        case 'updateCategoryPC':
            $id = substr(str_replace('export_categories', '', $_GET['id']), 1, -1);
            $objCategory = new Category(array('categories_id'=>(int)$id, 'categories_status_pc'=>(($_GET['flag']=='true')?1:0)) );
            $objCategory->save();
            $objCategory->refresh();
            echo $objCategory->get('categories_status_pc');
            break;
        case 'export':
        	tep_redirect("../trovaprezzi12.php?nocache=true");
        	exit;
        	
            break;
        default:
            break;
    }
exit;
}

$numfolders=$parent_id=$level=0;
$categories_tree=$export_categories=array();
$objCategories->getCategoriesTree($categories_tree,&$numfolders,$parent_id,$level,$export_categories);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<script language="javascript" src="includes/javascript/jquery.js"></script>
<script language="javascript" src="includes/javascript/jquery-ui.min.js"></script>
<script language="javascript" src="includes/javascript/jquery.form.js"></script>
<script language=javascript src="editor/inhtml.js"></script>
<script>
function disableIt(a){
	document.getElementById(a).disabled=true;
}

function enableIt(a){
	document.getElementById(a).disabled=false;
}
</script>
<style title="mod_turbolister2_css" type="text/css">
table.thead.th,H1,.headerSmall,.headerBig{
	font-family:Arial, Helvetica, sans-serif !important;
	font-weight:bold;
}
.headerSmall {
	font-size:12px;
	color:Navy;
}
.headerBig {
	font-size:16px;
}
.btn_proceed {
	margin-left:20px;padding-left:20px;padding-right:20px;
	cursor:pointer;
}
.btn_main {
	margin:0;
	width:150px;
	margin-top:5px;
	margin-bottom:5px;
	cursor:pointer;
}
.btn_tables {
	width:40px;
	cursor:pointer;
}
.previewLink{
	font-family:Arial, Helvetica, sans-serif !important;
	font-weight:bold !important;
	font-size:14px !important;
	margin:0;
	padding:0;
	margin-left:15px;margin-right:15px;
	margin-bottom:6px;
	padding:5px;
	border: ridge 2px #0099CC;
	font-variant:small-caps;
}
.categories_tree{
	height:400px;
	width:500px;
	margin: 0;
	padding: 0;
	overflow:scroll;
}
.treeFolder {
	padding: 0;
	margin: 0;
}
.treerow {
	white-space:nowrap;
	height:18px;
	min-height:18px;
	max-height:18px;
	padding:0;
	margin:0;
	overflow-y:hidden;
}
.treegifs,.treegifsInteractive {
	float:left;
	padding:0 !important;
	margin:0 !important;
	/*border:1px solid black;*/

}
.treegifsInteractive {
	cursor:pointer;
}
.treecheckbox{
	float:left;
	height:10px;
	width:10px;
	padding:0;
	margin:4px 5px 0px 0px;
}
.treecheckboxtext{
	float:left;
	height:10px;
	max-height:10px;
	min-height:10px;
	margin:4px 0px 0px 0px;
	padding:0;
	font-size:10px;
	line-height:12px;
	overflow-y:hidden;
	cursor:pointer;
}

.selected_category, .unselected_category{
	float:left;
	line-height:18px;
	height:18px;
	min-height:18px;
	max-height:18px;
	font-family:Arial, Helvetica, sans-serif;
	font-size:10px;
	padding:0;
	margin:0;
}
.selected_category{
}
.unselected_category{
}
</style>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<table  width="100%" cellspacing="2" cellpadding="2">
  <tr>
  
  <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table  width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
      <!-- left_navigation //-->
      <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
      <!-- left_navigation_eof //-->
    </table></td>
  <!-- body_text //-->
  <td width="100%" valign="top">
  <table  width="100%" cellspacing="0" cellpadding="2">
    <tr>
      <td><table  width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, 2); ?></td>
          </tr>
        </table></td>
    </tr>
    <tr>
    
    <td>
    
   <table cellspacing="10" cellpadding="5" >
     <tr>
        <td>
        	<form name="export_form" id="export_form" method="post" action="<?=tep_href_link('category_export_comp.php')?>" target="_blank">
			<table cellspacing="0" cellpadding="5"><tr><td><fieldset>
				<legend class="headerSmall"><?=TEXT_TURBOLISTER2_BOX_CATEGORIES?></legend>
				<div class="categories_tree">
				<?php
//	$numBaseEntries = tep_db_query("select count(categories_id) as numentries from " . TABLE_CATEGORIES ." where parent_id=0");
//	$numBaseEntries = tep_db_fetch_array($numBaseEntries);
//	$numBaseEntries = $numBaseEntries['numentries'];
//	$countBase = 1;
    
	$nsize=sizeof($categories_tree);
	$siblings=array();
	$curCnt=1;
	$lastChild=false;
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
		//echo $cat['name'].':'.$cat['level'].'<br/>';
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
			<img hspace="0" src="<?=DIR_WS_IMAGES.'turbolister2/'?>expand.gif" id="nodeid_<?=$i?>" onClick="toggleFolder('nodeid_<?=$i?>','horlink_<?=$i?>','folder_<?=$i?>');" class="treegifsInteractive" />
			<img hspace="0" src="<?=DIR_WS_IMAGES.'turbolister2/'?>horlink.gif" class="treegifs" id="horlink_<?=$i?>" />
						<?php } ?>
			<label for="<?=$cat['nodename']?>" class="<?=$cat['selected']?'selected_category':'unselected_category'?>">
			<input type="checkbox" onchange="var el = document.getElementById('folder_<?=$i?>'); checkChild(el, this.checked); updateCategoryPC(this, this.checked);" class="treecheckbox" id="<?=$cat['nodename']?>" name="<?=$cat['nodename']?>" value="1" <?php if((int)$cat['status_pc']>0) { echo ' checked="checked"'; } ?> /><?=htmlentities($cat['name'])?>
			</label></div>
					<?php
					if ($lastChild) { ?>
			</div>
					<?php } else if ($hasChildren) {?>
			<div id="folder_<?=$i?>" class="treeFolder">
					<?php } 
  
	}
					?>
				</div>
            </fieldset></td></tr></table>
          </form>
        </td>
        <td>Per impostare l'export dei soli prodotti disponibili, modifica questa voce della configurazione:<br><br>
        <a href="configuration.php?gID=35909" target=_blank>Configurazione Comparatori</a><br><br>
        Al termine della selezione delle categorie clicca sul pulsante qui sotto per aggiornare i tracciati<br><br>
        <a href="?action=export"><?php echo tep_image_button('button_save.gif', 'Aggiorna Tracciati')?></a></td>
      </tr>
    </table>
	<form id="editTurboForm" action="turbolister2_ajax.php" name="editTurboForm" method="post">
        <div id="editArea">
        </div>
	</form>
    <script language="javascript" type="text/javascript">
    $(document).ready(function () {
    	$.fn.getHeader = function() {
    		var arrToSend = {
				url:'turbolister2_ajax.php',
			    data:'action=getHeader',
			    dataType:"html",
			    type: "get",
			    success:function (data, textStatus) {
					$('#editArea').html(data);
			    }
    		}
    		$.ajax(arrToSend);
    		$('#editArea').html('<img src="images/loading.gif">');
    	}
    	$.fn.getFooter = function() {
    		var arrToSend = {
				url:'turbolister2_ajax.php',
			    data:'action=getFooter',
			    dataType:"html",
			    type: "get",
			    success:function (data, textStatus) {
					$('#editArea').html(data);
			    }
    		}
    		$.ajax(arrToSend);
    		$('#editArea').html('<img src="images/loading.gif">');
    	}
    	function showResponse(responseText, statusText, xhr, $form)  {
			eval(responseText); 
		}
    });
	var numvoid=1;
	var numcust=1;
	var subWindow=null;
	function checkChild(el,val) {
		if(el) {
			for(k in el.childNodes) {
				if(el.childNodes[k].tagName && el.childNodes[k].tagName.toLowerCase() == 'input' && el.childNodes[k].type.toLowerCase()=='checkbox') {
					el.childNodes[k].checked = val;
                    updateCategoryPC(el.childNodes[k],val);
				}
				if(el.childNodes[k].childNodes) {
					checkChild(el.childNodes[k], val);
				}
			}
		}
	}
    function updateCategoryPC(el,val) {
        var arrToSend = {
                url: 'category_export_comp.php',
                data: 'action=updateCategoryPC&flag='+val+'&id='+el.id,
                dataType: "html",
                type: "get",
                success:function (data, textStatus) {
                    el.checked = data;
                    if(data=='1') {
                        el.checked = true;
                    } else {
                        el.checked = false;
                    }
                    $(el).fadeTo('fast', 1);
                    
                    //console.log(data);
                }
            }
            $.ajax(arrToSend);
            $(el).fadeTo('fast', 0.2);
    }
	function copyLinkToClipboard(address)
	{
		clipb=window.clipboardData;
		if (clipb)
			clipb.setData("Text",address);
		else
			alert("<?=TEXT_TURBOLISTER2_ALERT_FUNCTION_NOT_SUPPORTED?>");
	}

	function toggleFolder(imgID,hlinkID,divID)
	{		
		var div = document.getElementById(divID);
		var img1 = document.getElementById(imgID);
		var img2 = document.getElementById(hlinkID);
		
		if(div.style.display == "none")
		{
			div.style.display = "inline";
			img1.src = "<?=DIR_WS_IMAGES.'turbolister2'?>/expand.gif";
			img2.src = "<?=DIR_WS_IMAGES.'turbolister2'?>/horlink.gif";
		}
		else
		{
			div.style.display = "none";
			img1.src = "<?=DIR_WS_IMAGES.'turbolister2'?>/collapse.gif";
			img2.src = "<?=DIR_WS_IMAGES.'turbolister2'?>/horlink_closed.gif";
		}
	}
</script>
    </td>
    
    </tr>
    
  </table>
  </td>
  
  <!-- body_text_eof //-->
  </tr>
  
</table>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>