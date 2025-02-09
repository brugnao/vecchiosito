<?php
/*
 * @filename:	
 * @version:	1.00
 * @project:	PWS
 *
 * @author:		Riccardo Roscilli <info@oscommerce.it>
 * @created:	20/nov/07
 * @modified:	20/nov/07 12:18:41
 *
 * @copyright:	2006-2007	Riccardo Roscilli @ PWS
 *
 * @desc:	
 *
 * @TODO:		
 */
	chdir('../');
	require('includes/application_top.php');
	$navigation->remove_current_page();
	$products_id=$_REQUEST['pID'];
	$sort_order=isset($_REQUEST['amp;s']) ? $_REQUEST['amp;s'] : $_REQUEST['s'];
	$image_query=tep_db_query("select products_image from ".TABLE_PWS_PRODUCTS_IMAGES." where products_id='$products_id' and sort_order='$sort_order'");
	$image=tep_db_fetch_array($image_query);
	$products_name=tep_get_products_name($products_id);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo $products['products_name']; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<script language="javascript"><!--
var i=0;
function resize() {
  if (navigator.appName == 'Netscape') i=40;
  if (document.images[0]) window.resizeTo(document.images[0].width +30, document.images[0].height+110-i);
  self.focus();
}
//--></script>
</head>
<body onload="resize();">
<?php echo tep_image(DIR_WS_IMAGES . urldecode($image['products_image']), $products_name); ?>
</body>
</html>
<?php require('includes/application_bottom.php'); ?>
