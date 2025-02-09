<?php
/*
 * @filename:
 * @version:	1.00
 * @project:	PWS
 *
 * @author:		Riccardo Roscilli <info@oscommerce.it>
 * @created:	18/lug/07
 * @modified:	18/lug/07 16:08:20
 *
 * @copyright:	2006-2007	Riccardo Roscilli @ PWS
 *
 * @desc:
 *
 * @TODO:
 */
if (!function_exists('file_put_contents')){
	function file_put_contents($filename,$content){
		$fp=fopen($filename,'w');
		fputs($fp,$content);
		fclose($fp);
	}
}

	chdir('../../../../');
	ob_start();
	require('includes/application_top.php');
	include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PROCESS);
	require(DIR_WS_MODULES.'payment/iwsmile.php');
	$iwsmile = new iwsmile();
	$iwsmile->notify();
	
	require('includes/application_bottom.php');
	ob_end_clean();
?>