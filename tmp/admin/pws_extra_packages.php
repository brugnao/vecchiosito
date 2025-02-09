<?php
/*
 * @filename:	pws_extra_packages.php
 * @version:	1.00
 * @project:	PWS
 *
 * @author:		Riccardo Roscilli <info@oscommerce.it>
 * @created:	21/apr/08
 * @modified:	21/apr/08 15:40:41
 *
 * @copyright:	2006-2008	Riccardo Roscilli @ PWS
 *
 * @desc:	
 *
 * @TODO:		
 */


require('includes/application_top.php');
if (isset($_REQUEST['action'])){
	switch ($_REQUEST['action']){
		case 'install_package':
			$packageCode=$_REQUEST['package_code'];
			$pws_engine->installExtraPackageByCode($packageCode);
			unset($_REQUEST['action']);
			unset($_POST['action']);
			unset($_GET['action']);
			break;
		default:
	}
}
$packagesFilter=isset($_REQUEST['packages'])?$_REQUEST['packages']:'';
$packagesList=$pws_engine->getExtraPackages($packagesFilter);
$heading_title=HEADING_TITLE;
switch ($packagesFilter){
	case 'present':
		$heading_title.=HEADING_TITLE_PRESENT;
		break;
	case 'installed':
		$heading_title.=HEADING_TITLE_INSTALLED;
		break;
	case 'new':
		$heading_title.=HEADING_TITLE_NEW;
		break;
	case 'all':
	default:
		break;
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<style type="text/css">
.carriage_return{
	font-size:0px;
	line-height:0px;
	clear:both;
}
.pws_packages_list_table{
	/*border: 1px dotted gray;*/
}
.pws_package_container{
	border: 1px dotted navy;
	width: 100%;
}
.pws_package_installed_container{
	float:left;
	width:20px;
	padding:2px;
}
.pws_package_title,.pws_package_short_description,.pws_package_title_link{
	font-family:Verdana,Arial,Sans-Serif !important;
}
.pws_package_title,.pws_package_title_link{
	font-size:12px !important;
	font-weight:bold !important;
}
.pws_package_title{
	float:left;
}
.pws_package_title_link:hover{
	font-size:12px !important;
	font-weight:bolder !important;
	text-style:normal !important;
	text-decoration:none !important;
}
.pws_package_short_description{
	font-size:12px;
	margin:5px;
	line-height:110%;
}
.pws_package_shoplink,.pws_package_shoplink:hover, .pws_package_shoplink:visited {
	color:blue !important;
}
.pws_packages_type_fieldset{
	margin-bottom:20px;
}
.pws_packages_type_fieldset legend{
	color:navy;
	font-family:Verdana,Arial,Sans-Serif !important;
	font-size:14px !important;
	font-weight:bold !important;
}
.pws_packages_new {
	float:left;
	font-family:Verdana,Arial,Sans-Serif !important;
	font-size:12px;
	font-weight:bold;
	color:red;
	text-decoration:underline;
	font-style:italic;
	margin-left:10px;
}
.pws_packages_installed,.pws_packages_present,.pws_packages_install_link,.pws_packages_install_link:hover, .pws_packages_install_link:visited {
	float:left;
	font-family:Verdana,Arial,Sans-Serif !important;
	font-size:11px;
	margin-left:25px;
	margin-top:2px;
	margin-right:10px;
	border:1px dotted gray;
}
.pws_packages_present{
	background:#ffffcc;
}
.pws_packages_installed{
	background:#ccffcc;
}

.pws_packages_install_link,.pws_packages_install_link:hover, .pws_packages_install_link:visited {
	color:blue !important;
}
</style>
<script language="JavaScript" type="text/JavaScript" defer>
	var subWindow=null;
	function OpenSubwindow(url,title)
	{
		if (typeof title=="undefined")
			title='';
		if (subWindow)
			subWindow.close();
		subWindow = null;
		subWindow = window.open(url,title,"width=800,height=800,innerWidth=800,innerHeight=800,menubar=false,alwaysRaised=true,copyhistory=false,dependent=true,directories=false,hotkeys");
		if (subWindow.opener == null)
			subWindow.opener = self;
	}
</script>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo $heading_title; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%">
<?php
	if (sizeof($packagesList)==0){
?>
		<div class="pageHeading"><?=HEADING_PACKAGES_NO_PRODUCTS?></div>
<?php
	}
	//print_r($packagesList);
	reset($packagesList);
	foreach($packagesList as $packageType=>$packages){
		switch ($packageType){
			case 'module':
				$heading=HEADING_PACKAGES_MODULES;
				break;
			case 'module_payment':
				$heading=HEADING_PACKAGES_MODULES_PAYMENT;
				break;
			case 'module_shipping':
				$heading=HEADING_PACKAGES_MODULES_SHIPPING;
				break;
			case 'module_order_total':
				$heading=HEADING_PACKAGES_MODULES_ORDER_TOTAL;
				break;
			case 'plugin_application':
				$heading=HEADING_PACKAGES_PLUGINS_APPLICATION;
				break;
			case 'plugin_prices':
				$heading=HEADING_PACKAGES_PLUGINS_PRICES;
				break;
		}
?>
			<fieldset class="pws_packages_type_fieldset"><legend><?=$heading?></legend>
        	<table class="pws_packages_list_table" id="pws_packages_list_table" width="100%">
<?php
		reset($packages);
		foreach($packages as $package){
?>
				<tr><td width="100%">
				<div class="pws_package_container">
					<div class="pws_package_installed_container"><?=tep_image(DIR_WS_IMAGES . ($package['isInstalled']=='yes'?'icon_status_green_light.gif':'icon_status_red_light.gif'), ($package['isInstalled']=='yes'?PACKAGE_PRESENT:PACKAGE_NOT_PRESENT), 10, 10)?></div>
					<div class="pws_package_title"><a target="_blank" class="pws_package_title_link" href="<?=$package['shoplink']['href']?>"><?=$package['title']['content']?></a></div>
					<? if ($package['isNew']=='yes'){?>
					<div class="pws_packages_new"><?=HEADING_PACKAGES_NEW_ENTRY?></div>
					<? } ?>
					<? if ($package['isPresent']=='yes'){?>
					<br class="carriage_return"/>
					<div class="pws_packages_present"><?=PACKAGE_PRESENT?></div>
					<? } ?>
					<? if ($package['isInstalled']=='yes'){?>
					<div class="pws_packages_installed"><?=PACKAGE_INSTALLED?></div>
					<? } ?>
					<br/>
					<br/>
					<div class="pws_package_short_description"><?=$package['shortdescription']['content']?></div>
					<br/>
					<div style="width:99%;min-width:99%;text-align:right;margin:3px 3px 3px 3px"><a target="_blank" class="pws_package_shoplink" href="<?=$package['shoplink']['href']?>"><?=$package['shoplink']['content']?></a></div>
					<? if ($package['isPresent']=='yes'){
						$packageCode=$package['dirname'];
						if (is_array($packageCode)){
							$packageCode='';
							foreach($package['dirname'] as $packCode){
								$packageCode.='&package_code[]='.$packCode;
							}
						}else{
							$packageCode='&package_code[]='.$packageCode;
						}
						?>
					<br class="carriage_return"/>
					<button onclick="javascript:location.href='<?=tep_href_link(FILENAME_PWS_ENGINE_EXTRA_PACKAGES,"action=install_package{$packageCode}&".tep_get_all_get_params(array('action','packageCode')))?>?>'"><?=$package['isInstalled']?UPDATE_PACKAGE:INSTALL_PACKAGE?></button>
					<?     if(isset($package['helppage']) && $package['helppage']!=''){?>
					<button onclick="javascript:OpenSubwindow('<?=HTTP_SERVER.DIR_WS_CATALOG.$package['helppage']?>')"><?=PACKAGE_HELP?></button>
					<?		} 
					} ?>
				</div>
				<br/>
				<br/>
				</td></tr>
<?		}?>
        	</table>
			</fieldset>
<?	}?>
		</td>
      </tr>
    </table></td>
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

<?php
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
