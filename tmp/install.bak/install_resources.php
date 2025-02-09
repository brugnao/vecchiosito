<?php
/*
 * @filename:	install_resources.php
 * @version:	1.00
 * @project:	PWS
 *
 * @author:		Riccardo Roscilli <info@oscommerce.it>
 * @created:	19/feb/08
 * @modified:	19/feb/08 15:04:36
 *
 * @copyright:	2006-2008	Riccardo Roscilli @ PWS
 *
 * @desc:	Installa il contenuto di init_resources nella root del sito
 * @desc:	(Può essere richiamato anche per aggiornare i files)
 *
 * @TODO:		
 */

	require('includes/application.php');
	$overwrite=isset($_REQUEST['overwrite']);
	$exclude_dirs=array('.svn');
	//////////////////////////////////////////////////////////////////////
	// Copia dei files
	$destination_dir=realpath(dirname(__FILE__).'/../').'/';
	$destination_dir=str_replace('\\','/',$destination_dir);
	$resources_dir=realpath(dirname(__FILE__)).'/init_resources/';
	$resources_dir=str_replace('\\','/',$resources_dir);
	$error=move_dir_contents($resources_dir,$destination_dir,true,false,$overwrite,$exclude_dirs);
	$result= ($error!=false) ? 1 : 0;
	//////////////////////////////////////////////////////////////////////
	// Copia dei files delle risorse aggiornabili (CON overwrite)
	$destination_dir=realpath(dirname(__FILE__).'/../').'/';
	$destination_dir=str_replace('\\','/',$destination_dir);
	$resources_dir=realpath(dirname(__FILE__)).'/updated_sources/';
	$resources_dir=str_replace('\\','/',$resources_dir);
	$error=move_dir_contents($resources_dir,$destination_dir,true,false,true,$exclude_dirs);
	$result= ($error!=false || $result==1) ? 1 : 0;
	
	//////////////////////////////////////////////////////////////////////
	// Copia dei pacchetti aggiuntivi in pws_extras
	$pws_extras_dir=realpath(dirname(__FILE__).'/../').'/pws_extras/';
	$pws_extras_dir=str_replace('\\','/',$pws_extras_dir);
	$dh=opendir($resources_dir);
	while ($entry=readdir($dh)){
		switch ($entry){
			case '.':
			case '..':
				break;
			default:
				$resources_dir=$pws_extras_dir.$entry;
				if (is_dir($resources_dir)){
					if (file_exists($resources_dir.'/content/init_resources') && is_dir($resources_dir.'/content/init_resources')){
						$error=move_dir_contents($resources_dir.'/content/init_resources/',$destination_dir,true,false,false,$exclude_dirs);
						$result= ($error!=false || $result==1) ? 1 : 0;
					}
					if (file_exists($resources_dir.'/content/updated_sources') && is_dir($resources_dir.'/content/updated_sources')){
						$error=move_dir_contents($resources_dir.'/content/updated_sources/',$destination_dir,true,false,true,$exclude_dirs);
						$result= ($error!=false || $result==1) ? 1 : 0;
					}
				}
				break;
		}
	}
	exit($result?'KO':'OK');

?>