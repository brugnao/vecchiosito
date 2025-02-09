<?php
/*
 * @filename:	
 * @version:	1.00
 * @project:	PWS
 *
 * @author:		Riccardo Roscilli <info@oscommerce.it>
 * @created:	02/lug/07
 * @modified:	02/lug/07 11:25:25
 *
 * @copyright:	2006-2007	Riccardo Roscilli @ PWS
 *
 * @desc:	
 *
 * @TODO:		
 */
//require_once('phpThumb.config.php');	
	
	function getThumbnail($fileName, $width, $height, $crop=false, $cropOffset = 1, $forceSize=false)
	{
//		if (!file_exists($fileName))	{
//			$fileName=DIR_FS_CATALOG.$fileName;
//		}
	//	$hash=md5_file($fileName);
		$cachedir=DIR_FS_CACHE;
		if (!file_exists($cachedir)){
			mkdir($cachedir);
			chmod($cachedir,0777);
		}
		$basename=basename($fileName);
		$basename=explode('.',$basename);
//		$fname=array_shift($basename);
		$fname=strstr($fileName,DIR_WS_IMAGES);
		$fname=str_replace('\\','_',$fname);
		$fname=str_replace('/','_',$fname);
		$ext=array_pop($basename);
		
		if (file_exists($fileName) && !is_dir($fileName))
		{
			//list($originalWidth, $originalHeight, $imagetypes) = @getImageSize($fileName);
			$size = @getImageSize($fileName);
			if ($size===false)
				return false;
			$originalWidth=array_shift($size);
			$originalHeight=array_shift($size);
			$imagetypes=array_shift($size);
			$ws_cacheFileName = $fname.'_'.$width.'_'.$height.'_'.$crop.'_'.$cropOffset.'_'.$forceSize.'_'.$hash;//.'.'.$ext;
			$cacheFileName = $cachedir.$ws_cacheFileName;
			$ws_cacheFileName = DIR_WS_CACHE.$ws_cacheFileName;
			if (file_exists($cacheFileName))
			{
				list($originalWidth, $originalHeight, $imagetypes) = getImageSize($fileName);
			//	list($destWidth, $destHeight) = getImageSize($cacheFileName);
				return array('imageType' => $imagetypes, 'fileName' => $cacheFileName, 'baseFileName'=> $ws_cacheFileName, 'width' => $destWidth, 'height' => $destHeight, 'originalWidth'=> $originalWidth, 'originalHeight'=>  $originalHeight);
			}
			require_once('phpthumb.class.php');
			
			$phpThumb = new phpThumb();

			// set data
			$phpThumb->setSourceFilename($fileName);
			
			// set parameters (see "URL Parameters" in phpthumb.readme.txt)
			$phpThumb->setParameter('w', $width);
			$phpThumb->setParameter('h', $height);
			if ($imagetypes==IMG_GIF || $imagestypes==IMG_PNG) {
				$phpThumb->thumbnailFormat='png';
				$phpThumb->setParameter('config_output_format', 'png');
			}else{
				$phpThumb->thumbnailFormat='jpeg';
				$phpThumb->setParameter('config_output_format', 'jpeg');
			}
			// generate & output thumbnail
			$phpThumb->GenerateThumbnail(); // this line is VERY important, do not remove it!
			$phpThumb->RenderToFile($cacheFileName);
			unset($phpThumb);
			return array('imageType' => $imagetypes, 'fileName' => $cacheFileName, 'baseFileName'=> $ws_cacheFileName, 'width' => $destWidth, 'height' => $destHeight, 'originalWidth'=> $originalWidth, 'originalHeight'=>  $originalHeight);
		}
		else
			return false;
	}
?>