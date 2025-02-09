<?php
/*
 * @filename:	pws_skin.php
 * @version:	1.00
 * @project:	PWS
 *
 * @author:		Riccardo Roscilli <info@oscommerce.it>
 * @created:	15/mag/07
 * @modified:	15/mag/07 16:27:02
 *
 * @copyright:	2006-2007	Riccardo Roscilli
 *
 * @desc:	
 *
 * @TODO:		
 */
require_once 'PHPTAL/Filter.php';

class pws_skin {
	var $fileName;
	var $_templClass;
	var $php5;

	function pws_skin($fileName, $templateString=NULL, $forceCreation=true /*false*/)
	{
		
	//	if (NEW_TEMPLATE_SYSTEM == 'true')
	//		$fileName = '';
		
			
		$this->php5=phpversion() >= 5;
/*		if ($forceCreation)
			echo "FORCE CREATION TRUE<br>";
		echo "CMS BASE DIR: ".CMS_BASE_DIR."/Skins<br>filename:$fileName<br>template string: $templateString<br>";
*/		if (!empty($templateString))
		{
			// memorizza la skin di defaul del componente
			// come file in cache per poi passarlo al template engine
			if (!file_exists(DIR_FS_CACHE.$fileName) || $forceCreation==true)
			{
			//	unlink(DIR_FS_CACHE.$fileName);
				$fp = @fopen(DIR_FS_CACHE.$fileName, "wb");
		        if ($fp)
		        {
		           	@flock($fp, LOCK_EX);
		            $len = strlen($templateString);
		            @fwrite($fp, $templateString, $len);
		            @flock($fp, LOCK_UN);
		            @fclose($fp);
		        }
		      	else
		      	{
					// TODO
					// visualizzare il messaggio di errore
					       trigger_error('Cache_Lite : Unable to write cache !', E_USER_ERROR);
			        return false;
				}
			}
			$this->fileName = $fileName;
			//echo "temp filename:".CMS_BASE_DIR."/siteContents/cache/$fileName<br>";
			if (!$this->php5)
			{
				$this->_templClass = new PHPTAL($fileName, DIR_FS_CACHE, DIR_FS_CACHE);
			}else{
				$this->_templClass = new PHPTAL(DIR_FS_CACHE);
				$this->_templClass->setSource($fileName);
				$this->_templClass->setTemplateRepository(DIR_FS_CACHE);
			}
			//$this->_templClass->setTemplateRepository(DIR_FS_CACHE);
			//$this->_templClass = new PHPTAL(DIR_FS_CACHE);
//			$this->_templClass->setSource($fileName);
//			$this->_templClass->setSource($templateString);
//			$this->_templClass->setPreFilter(new HtmlPortionPreFilter);
		}
		else
		{
			$this->fileName = $fileName;
			//$this->_templClass = new PHPTAL($fileName, CMS_BASE_DIR."Skins/", CMS_BASE_DIR.'/siteContents/cache/');
			if (file_exists(DIR_FS_STS_SKINS.$fileName))
				$this->_templClass = new PHPTAL(DIR_FS_STS_SKINS.$fileName,DIR_FS_CACHE,DIR_FS_CACHE);
			else if (file_exists(DIR_FS_PWS_SKINS.$fileName))
				$this->_templClass = new PHPTAL(DIR_FS_PWS_SKINS.$fileName,DIR_FS_CACHE,DIR_FS_CACHE);
			else
				echo "template non trovato: ".DIR_FS_STS_SKINS.$fileName.'<br/> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; oppure '.DIR_FS_PWS_SKINS.$fileName.'<br>';
//			$this->_templClass->setSource($fileName);
//			$this->_templClass->setTemplateRepository(DIR_FS_PWS_SKINS);
//			$this->_templClass->setCacheDir(CMS_BASE_DIR.'/siteContents/cache/');
		}
//		$this->_templClass->setPostFilter(new HtmlPortionPostFilter);
	}

	function set($theBlock, $theValue)
	{
		$this->_templClass->set($theBlock, $theValue);
	}	

	function execute()
	{
		$res = $this->_templClass->execute();
if (!$this->php5)
{
		if (PEAR::isError($res)) {
		   return $res->toString()."\n";
		} else {
			// prende solo il contenuto del body
			$res = preg_replace("/^.*<body[^>]*>(.*)<\/body>.*$/si", "$1", $res);
		    return $res;
		}
}else{
			$res = preg_replace("/^.*<body[^>]*>(.*)<\/body>.*$/si", "$1", $res);
		    return $res;
	
}
	}
}
if (phpversion() < 5)
{
	require_once 'pws_skin_filters.php';
}else{
	require_once 'pws_skin_filters5.php';
}
?>