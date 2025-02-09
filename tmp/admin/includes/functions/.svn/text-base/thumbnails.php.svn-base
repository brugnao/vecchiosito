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
	require_once('phpthumb.class.php');
	
	function getThumbnail($fileName, $width, $height, $crop=false, $cropOffset = 1, $forceSize=false)
	{
//		if (!file_exists($fileName))	{
//			$fileName=DIR_FS_CATALOG.$fileName;
//		}
		$hash=md5_file($fileName);
		$cachedir=DIR_FS_CACHE;
		if (!file_exists($cachedir)){
			mkdir($cachedir);
			chmod($cachedir,0777);
		}
		$basename=basename($fileName);
		$basename=explode('.',$basename);
		$fname=array_shift($basename);
		$ext=array_pop($basename);
		
		if (file_exists($fileName))
		{
			list($originalWidth, $originalHeight, $imagetypes) = getImageSize($fileName);
			$ws_cacheFileName = $fname.'_'.$width.'_'.$height.'_'.$crop.'_'.$cropOffset.'_'.$forceSize.'_'.$hash.'.'.$ext;
			$cacheFileName = $cachedir.$ws_cacheFileName;
			$ws_cacheFileName = DIR_WS_CACHE.$ws_cacheFileName;
			if (file_exists($cacheFileName))
			{
				list($originalWidth, $originalHeight, $imagetypes) = getImageSize($fileName);
				list($destWidth, $destHeight) = getImageSize($cacheFileName);
				return array('imageType' => $imagetypes, 'fileName' => $cacheFileName, 'baseFileName'=> $ws_cacheFileName, 'width' => $destWidth, 'height' => $destHeight, 'originalWidth'=> $originalWidth, 'originalHeight'=>  $originalHeight);
			}

			$phpThumb = new phpThumb();

			// set data
			$phpThumb->setSourceFilename($fileName);

			// set parameters (see "URL Parameters" in phpthumb.readme.txt)
			$phpThumb->setParameter('w', $destWidth);

			// generate & output thumbnail
			$phpThumb->GenerateThumbnail(); // this line is VERY important, do not remove it!
			$phpThumb->RenderToFile($cacheFileName);
			return array('imageType' => $imagetypes, 'fileName' => $cacheFileName, 'baseFileName'=> $ws_cacheFileName, 'width' => $destWidth, 'height' => $destHeight, 'originalWidth'=> $originalWidth, 'originalHeight'=>  $originalHeight);
		}
		else
			return false;
	}
?>