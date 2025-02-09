<?php
/*
 * @filename:	
 * @version:	1.00
 * @project:	Isy
 *
 * @author:		Riccardo Roscilli <info@oscommerce.it>
 * @created:	20/apr/07
 * @modified:	20/apr/07 18:11:58
 *
 * @copyright:	2006-2007	Riccardo Roscilli
 *
 * @desc:	
 *
 * @TODO:		
 */
$eol="<br/>\r\n";
$eol="\r\n";
//echo "__FILE__:".__FILE__.$eol;
//echo "LOCK_EX:".LOCK_EX.$eol;
//echo "FILE_USE_INCLUDE_PATH:".FILE_USE_INCLUDE_PATH.$eol;
//echo "FILE_APPEND:".FILE_APPEND.$eol;
//$constants=get_defined_constants(true);
//print_r($constants);
$constants=get_defined_constants();
$output='';
foreach($constants as $constant_name=>$constant_value){
	$output.=<<<EOT
	if (!defined('$constant_name'))
		define('$constant_name','$constant_value');$eol
EOT;
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset='ISO-8951-15'">
<base href="http://localhost">
<title>Test PHP5</title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>
<?=$output?>
</body>
</html>