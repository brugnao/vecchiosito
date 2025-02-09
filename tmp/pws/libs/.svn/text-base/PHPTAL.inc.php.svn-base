<?php
if (phpversion() < 5)
{
	set_include_path(get_include_path() . PATH_SEPARATOR . DIR_FS_PWS_LIBS. 'PHPTAL/');
	require_once('PHPTAL/PHPTAL.php');
}
else
{
	define('PHPTAL_PHP_CODE_DESTINATION',DIR_FS_CACHE);
	set_include_path(get_include_path() . PATH_SEPARATOR . DIR_FS_PWS_LIBS . 'PHPTAL5/');
	set_include_path(get_include_path() . PATH_SEPARATOR . DIR_FS_PWS_LIBS . 'PHPTAL5/PHPTAL/');
	require_once('PHPTAL5/PHPTAL.php');
}
?>