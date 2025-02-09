<?php
/*
 * @filename:	files.php
 * @version:	1.01
 * @project:	PWS
 *
 * @author:		Riccardo Roscilli <info@oscommerce.it>
 * @created:	18/feb/08
 * @modified:	18/feb/08 16:13:40
 *
 * @copyright:	2006-2008	Riccardo Roscilli @ PWS
 *
 * @desc:	Funzioni per la gestione dei files e delle directories
 *
 * @TODO:		
 */
// @function copy_file
// @desc	Copia o sposta un file in una directory di destinazione
// @param	(string)	$srcdir				File sorgente
// @param	(string)	$dstdir				Directory destinazione
// @param	(bool)		$overwrite			Se impostato a false non sovrascrive i files, altrimenti ci prova, ed in caso di fallimento esce con errore
// @param	(bool)		$delete_source		Se impostato a false esegue un copy, altrimenti un move
// @return	(mixed)		Restituisce false se non sono stati riscontrati errori, una stringa con l'errore, altrimenti
function copy_file($srcfile,$dstdir,$overwrite=false,$delete_source=false){
	if (substr($dstdir,-1)!='/'){
		$dstdir.='/';
	}
	$entry=basename($srcfile);
	$srcdir=dirname($srcfile).'/';
	//echo "file: $entry (da '$srcdir' a '$dstdir')";
	if (file_exists($dstdir.$entry)){
		if (!$overwrite)
			return false;
		else{
			@chmod($dstdir.$entry,0777);
			if (!@unlink($dstdir.$entry))
				return "Errore nella sovrascrittura del file destinazione '$dstdir$entry'";
			if (file_exists($dstdir.$entry))
				return "Errore nella sovrascrittura del file destinazione '$dstdir$entry'";
		}
	}
	if (!@copy($srcdir.$entry,$dstdir.$entry)){
		return "Errore nella copia del file '$srcdir$entry' in '$dstdir'";
	}else{
		//echo " --- ok<br/>\r\n";
	}
	if ($delete_source){
		@chmod($srcdir.$entry,0777);
		if (!@unlink($srcdir.$entry))
			return "Errore nell'eliminazione del file sorgente '$srcdir$entry'";
	}
	return false;
}

// @function move_dir_contents
// @desc	Sposta il contenuto della directory sorgente nella directory destinazione
// @param	(string)	$srcdir				Directory sorgente
// @param	(string)	$dstdir				Directory destinazione
// @param	(bool)		$recursion			Se impostato a true, copia ricorsivamente le sottodirectories ed il loro contenuto
// @param	(bool)		$delete_source		Se impostato a false esegue un copy, altrimenti un move
// @param	(bool)		$overwrite			Se impostato a false non sovrascrive i files, altrimenti ci prova, ed in caso di fallimento esce con errore
// @param	(array)		$exclude_dirs		Pu� contenere i nomi delle directories da ignorare
// @return	(mixed)		Restituisce false se non sono stati riscontrati errori, una stringa con l'errore, altrimenti
function move_dir_contents($srcdir, $dstdir, $recursion=true, $delete_source=true, $overwrite=false, $exclude_dirs=array(), $start=true){
	/*$numargs = func_num_args();
	for ($i=0;$i<$numargs;$i++){
		$arg=func_get_arg($i);
		var_dump($arg);
	}
	exit;
	*/
	$error=false;
	if ($start){
		$srcdir=str_replace('\\','/',$srcdir);
		$srcdir=str_replace('//','/',$srcdir);
		$dstdir=str_replace('\\','/',$dstdir);
		$dstdir=str_replace('//','/',$dstdir);
		if (substr($dstdir,-1)!='/')
			$dstdir.='/';
		if (substr($srcdir,-1)!='/')
			$srcdir.='/';
	}
	
	if (is_dir($srcdir)){
		$srcdir_array=explode('/',$srcdir);
		$current_directory=array_pop($srcdir_array);
		if (!strlen($current_directory))
			$current_directory=array_pop($srcdir_array);
		if (in_array($current_directory,$exclude_dirs)){
			return false;
		}
		//echo "copia di '$srcdir' in '$dstdir'<br>\r\n";
		if (!$start){
			if (!is_dir($dstdir)){
				if (!mkdir(substr($dstdir,0,-1),0777))
					return "Errore nella creazione della directory di destinazione '$dstdir'";
				@chmod(substr($dstdir,0,-1),0777);
			}
		}
		$dh=@opendir($srcdir);
		if (!$dh)
			return "Impossibile aprire la directory '$srcdir'";
		while (!$error && ($entry = readdir($dh)) !== false){
			if ($entry=='.' || $entry=='..')
				continue;
			$entry=str_replace('\\','/',$entry);
			if (is_dir($srcdir.$entry)){
				if (substr($entry,-1)!='/')
					$entry.='/';
				if ($recursion)
					move_dir_contents($srcdir.$entry,$dstdir.$entry,$recursion,$delete_source,$overwrite,$exclude_dirs,false);
			}else{
				if (file_exists($srcdir.$entry)){
					if (($error=copy_file($srcdir.$entry,$dstdir,$overwrite,$delete_source))!=false){
						return $error;
					}
				}
			}
		}
		@closedir($dh);
		if ($delete_source){
			@chmod($srcdir,0777);
			if (!rmdir($srcdir))
				return "Errore nell'eliminazione della directory sorgente '$srcdir'";
		}
		return false;
	}else{
		//echo "eccoci<br/>";
		if (file_exists($srcdir)){
			if (($error=copy_file($srcdir,$dstdir,$overwrite,$delete_source))!=false){
				return $error;
			}
		}
	}
}

?>
