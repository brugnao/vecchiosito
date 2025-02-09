<?php
/**
 * iceosc class for oscommerce, it used specific oscommerce functions to connect into database
 * 
 * @author Mikel Annjuk <anmishael@gmail.com>
 */
class iceosc {
	var $arrConfig=array();
	var $params=array();
	/**
	 * icecat class constructor
	 */
    function iceosc($data=array()) {
    	$this->init($data);
    }
    /**
     * init method for class, to allow fill config data
     * 
     */
    function init($data=array()) {
    	$this->arrConfig = $data;
    	$this->checkIfInstalled();
    }
    /**
     * getProductFromDatabase method get ICCAT data from database
     * 
     * @param array $params array with parameters (like product id, vendor) to get specified product
     * @return array all product info from ICECAT
     */
    function getProductFromDatabase($params = array()) {
    	if(sizeof($params)>0) {
    		array_merge($this->params, $params);
    	}
    	$sql = 'SELECT * FROM `icecat_products` WHERE products_id=\'' . tep_db_input($this->params['products_id']) . '\' ' .
    			'AND prod_id=\'' . tep_db_input($this->params['prod_id']) . '\'' .
				'AND vendor=\'' . tep_db_input($this->params['vendor']) . '\' ' .
    			'AND lang=\'' . tep_db_input($this->params['lang']) . '\'';
//    	echo $sql . "                     \n";
    	$sql = tep_db_query($sql);
    	$arrProd = tep_db_fetch_array($sql);
    	$arrProd['data'] = unserialize(stripslashes($arrProd['data']));
//    	tep_db_query('alter table pws_products_images drop primary key');
//    	tep_db_query('alter table pws_products_images add unique key pws_products_images_u (products_id,products_image)');
    	return $arrProd['data'];
    }
    /**
     * saveToDatabase method save ICECAT data into database
     * 
     * @param array $data	data array with next indexes:
     * 						'prod_id'=> product ID
     * 						'vendor' => vendor name
     * 						'lang'   => language code like en, it, uk etc.
     * 						'data'   => serialized array of full product info from ICECAT
     */
    function saveToDatabase($data) {
    	$arrSql = array(
    			'prod_id'=>$this->params['prod_id'],
    			'products_id'=>$this->params['products_id'],
    			'vendor'=>tep_db_input($this->params['vendor']),
    			'lang'=>tep_db_input($this->params['lang']),
    			'data'=>tep_db_input(serialize($data)) 
    		);
    	$arrSql['data'] = str_replace('Ã','à',$arrSql['data']);
     	tep_db_perform('icecat_products', $arrSql);
    }
    /**
     * checkIfInstalled method check database if icecat_products table exists
     * 
     * @return boolean checking result
     */
    function checkIfInstalled() {
//		echo 'checking... step 1';
    	$sql = 'SHOW TABLES';
    	$sql = tep_db_query($sql);
    	$result = false;
    	$this->arrTables = array();
    	while($row=tep_db_fetch_array($sql)) {
    		// save table names into database
			$this->arrTables[]=$row['Tables_in_'.DB_DATABASE];
    	}
//		echo 'checking... step 2';
    	// check if ICECAT products table exists
    	if(in_array('icecat_products', $this->arrTables)) $result=true;
    	// check if PWS product images table exists,- for additional images
    	if(in_array('pws_products_images', $this->arrTables)) $this->pws_images_installed=true;
    	return $result;
    }
    function clearAdditionalImages($pid) {
    	if($this->pws_images_installed) {
    		tep_db_query('DELETE FROM pws_products_images WHERE products_id=\'' . (int)$pid . '\'');
    	}
//    	echo '!tables!{<pre>';
//    	print_r($this->arrTables);
//    	echo '</pre>}!tables!';
    }
    /**
     * downloadImage method get images from ICECAT server, copy them into images/{$vendor}/ location
     * 				 and save info into database
     * 
     * @param string $url		image URL
     * @param string $vendor	vendor name
     * @param int $pid			product ID
     * @param boolean $imgExists
     * @param int $order
     */
    function downloadImage($url, $vendor, $pid, $imgExists, $order) {
    	$folder = DIR_FS_CATALOG . 'images/' . str_replace(' ', '_',strtolower($vendor) ) . '/';
    	$urlPath = pathinfo($url);
    	$image = strtolower($vendor) . '/' . $urlPath['basename'];
    	if(!file_exists($folder) || !is_dir($folder)) { 
    		if(!mkdir($folder, 0777)) {
    			echo '!!! Could not create folder ' . $folder . ' !!!';
    		}
    	}
    	// mail('info@oscommerce.it','icecat images',$url. $vendor. $pid.$imgExists. $order);
    	
    	// echo 'Dir di destinazione delle immagini ' . $folder . ' !!!';
    	if(!file_exists($folder . $urlPath['basename'])) {
	    	$ch = curl_init($url);
			$fp = fopen($folder . $urlPath['basename'], "w");
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_exec($ch);
			curl_close($ch);
			fclose($fp);
			echo $folder . $urlPath['basename']."uploaded...\n";
    	}
    	// save image into database
    	$this->saveImageToDatabase($pid, $image, $imgExists, $order);
    }
    /**
     * saveImageToDatabase method for class
     * 
     * @param int $pid				product ID
     * @param string $image			folder name+image name
     * @param boolean $imgExists	true if any image exists in products table or false in other case
     * @param int $order			image order position
     */
    function saveImageToDatabase($pid, $image, $imgExists, $order) {
    	if(!$imgExists) {
			$arrSql = array('products_image'=>$image);
    		tep_db_perform('products', $arrSql, 'update', 'products_id=\'' . $pid . '\'');
    	}
    	// save additional images if PWS installed
		if($this->pws_images_installed) {
    		tep_db_query('INSERT IGNORE pws_products_images SET products_id=\'' . (int)$pid . '\', products_image=\'' . tep_db_input($image) . '\',sort_order=\'' . (int)$order . '\'');
		}
    }
    /**
     * install method create DB table/s, column/s etc.
     * Information about DB changes located in icecat.xml file
     * in install section.
     */
    function install($arrSql) {
    	if(is_array($arrSql)) {
    		foreach($arrSql as $sql) {
    			tep_db_query($sql);
    		}
    	} else {
    		tep_db_query($arrSql);
    	}
    }
    /**
     * uninstall method remove DB tabel/s, column/s etc.
     * Information about DB changes located in icecat.xml file
     * in uninstall section.
     */
    function uninstall($arrSql) {
    	if(is_array($arrSql)) {
    		foreach($arrSql as $sql) {
    			tep_db_query($sql);
    		}
    	} else {
    		tep_db_query($arrSql);
    	}
    }
    function defineVars() {
    	$sql = 'SELECT `configuration_key`, `configuration_value` FROM configuration WHERE `configuration_group_id`=714';
    	$sql = tep_db_query($sql);
    	while($row=tep_db_fetch_array($sql)) {
    		define($row['configuration_key'], $row['configuration_value']);
    	}
    }
}
?>