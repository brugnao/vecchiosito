<?php
/**
 * icecat class to connect/get data from ICEcat
 * @require installed CURL on server
 * 
 * @author mikel <anmishael@gmail.com>
 * @version 1.0
 */
 
 // load XML Unserializer PEAR class
require_once(DIR_FS_ICECAT . 'libs/PEAR/XML/Unserializer.php');
class icecat extends iceosc {
	// define variables
	var $icecat_product_list, $icecat_refs_xml,$icecat_prod_xml;
	var $params=array('output'=>'productxml');
	var $user, $pass;
	var $arrErrors = array();
	var $arrConfig = array();
	
	/**
	 * icecat constructor method for class
	 */
    function icecat() {
    	// add XML configuration (from icecat.xml file)
    	$this->arrConfig = array_merge($this->arrConfig, $this->getXMLDataAsArray(ICECAT_XML_CONFIG, true));
    	// ICECAT access section
    	$this->user = ICECAT_USER;
    	$this->pass = ICECAT_PASS;
    	// product list URL
    	$this->icecat_product_list 	= 'data.icecat.biz/export/freeurls/export_urls.txt';
    	// refs URL
    	$this->icecat_refs_xml = 'data.icecat.biz/export/freexml.int/refs.xml';
    	$this->icecat_daily = 'data.icecat.biz/export/freexml/daily.index.xml';
    	$this->icecat_files = 'data.icecat.biz/export/freexml/files.index.xml';
    	
    	$this->icecat_daily = 'http://' . $this->user . ':' . $this->pass . '@' . $this->icecat_daily;
    	$this->icecat_files = 'http://' . $this->user . ':' . $this->pass . '@' . $this->icecat_files;
    	// search product description URL
    	$this->icecat_prod_xml = 'http://' . $this->user . ':' . $this->pass . '@data.icecat.biz/xml_s3/xml_server3.cgi';//'?prod_id=1447B006AA;vendor=Canon;shopname=openICEcat-xml;lang=en;output=productxml';
    }
    function getCategories(){
    	
    }
    function downloadFiles($type) {
    	$dtype = 'icecat_' . $type;
    	if($this->$dtype) {
    		$resFile = DIR_FS_CATALOG . 'icecat/xml/' . $type . '.index.xml';
    		if(!file_exists($resFile)) {
    			$header = array();
				$header[] = "Accept: text/html;q=0.9, text/plain;q=0.8, image/png, */*;q=0.5" ;
				$header[] = "Accept_charset: utf-8;q=0.6, *;q=0.1";
				    // say, that browser do not read gzip format
				$header[] = "Accept_encoding: identity";
				$header[] = "Accept_language: en-us,en;it-it,it;q=0.5";
				$header[] = "Connection: close";
				$header[] = "Cache-Control: no-store, no-cache, must-revalidate";
				$header[] = "Keep_alive: 300";
				$header[] = "Expires: Thu, 01 Jan 2010 00:00:01 GMT";
				$res = $this->sendPostData($this->$dtype, null, null, $header, null, null, $resFile);
    		}
    	} else {
    		return false;
    	}
    	if(file_exists($resFile)) {
    		$res = $this->getXMLDataAsArray($resFile, true);
    		if(!is_array($res)) {
    			return $res;
    		} else {
    			return $res;
    		}
    	}
    }
    /**
     * getProduct method for class. it search product description in ICECAT table 
     * and return description array. If there is no records in database it call getXML
     * method to get it from ICECAT server.
     * 
     * @param string $VPN		Vendor Part Number
     * @param string $Vendor	Vendor name
     * @param string $lang		language ISO code (en, it, ch etc.)
     * @param string $shopName	shopname
     * @param int $pID			own catalog product ID
     * 
     * @return array product data
     */ 
    function getProduct($VPN, $Vendor, $lang, $shopName = 'openICEcat-xml',$pID) {
    	// prepare parameters
    	$this->params['prod_id'] = $VPN;
    	$this->params['vendor'] = $Vendor;
    	$this->params['lang'] = $lang;
    	$this->params['shopname'] = $shopName;
    	$this->params['products_id'] = $pID;
    	// try to get product info from database
    	$arrProduct = $this->getProductFromDatabase();
    	// check if product array is empty
    	if((!$arrProduct || sizeof($arrProduct)<=0) && sizeof($this->arrErrors)<=0) {
    		// refer to ICECAT server and save result (product info) into database
    		$this->getXML();
    		// get product ICECAT data from database 
    		$arrProduct = $this->getProductFromDatabase();
    	}
    	return $arrProduct;
    }
    /**
     * getXML method used for referring to ICECAT server and saving results into database. Connection params 
     * should be present in icecat object (via constructor usually) 
     */
    function getXML() {
    	$arrUrl = array();
    	// create parameters URL part, if they present
    	if(sizeof($this->params)>0) {
			foreach($this->params as $k=>$v) {
				$arrUrl[] = $k . '='.$v;
			}
		}
		reset($this->params);
		// define XML filename where result should be saved
		$resFile = DIR_FS_CATALOG . 'icecat/xml/' . $this->params['vendor'] . '-' . $this->params['prod_id'] . '-' . $this->params['lang'] . '.xml';
		// sending data via sendPostData method
		$header = array();
		$header[] = "Accept: text/html;q=0.9, text/plain;q=0.8, image/png, */*;q=0.5" ;
			$header[] = "Accept_charset: utf-8;q=0.6, *;q=0.1";
			    // say, that browser do not read gzip format
			$header[] = "Accept_encoding: identity";
			$header[] = "Accept_language: en-us,en;it-it,it;q=0.5";
			$header[] = "Connection: close";
			$header[] = "Cache-Control: no-store, no-cache, must-revalidate";
			$header[] = "Keep_alive: 300";
			$header[] = "Expires: Thu, 01 Jan 2010 00:00:01 GMT";
		$res = $this->sendPostData($this->icecat_prod_xml . '?' . implode(';', $arrUrl), null, null, $header, null, null, $resFile);

//		!!! DO NOT DELETE !!! {{
//		ICECAT do not give correct UTF-8 XML file, but Latin-1 encoding. Also they do not add encoding info into XML header
//		That's why we change first line (< xml version="1.0" >) to < xml version="1.0" encoding="iso-8859-1" >
		$fo=file($resFile);
		if($this->notNull($fo[0]) && substr($fo[0],0,5)=='<?xml') {
		$fo[0]='<?xml version="1.0" encoding="iso-8859-1"?>'."\n";
		$fp = @fopen($resFile,"w");
		foreach($fo as $val) {
			fwrite($fp,$val);
		}
		fclose($fp);
		} else {
			foreach($fo as $val) { 
				$this->arrErrors[] = str_replace("\n", '', strip_tags($val));
			}
		}
		unset($fo);
//		!!! DO NOT DELETE !!! }}

		$res = $this->getXMLDataAsArray($resFile, true);
		// check if we have an error in result file
		if(!is_array($res) || $res['Product'][1]['Code']!=1) {
			// save error to object
			$this->arrErrors[] = $res['Product'][1]['ErrorMessage'];
		} else {
			// save results to database
			$this->saveToDatabase($res);
		}
    }
    /**
     * getXMLDataAsArray method parse XML data (or XML file) and return data as array.
     * 
     * @param string $data		XML file content or XML file path
     * @param boolean $isFile	say if $data is file path or not
     * 
     * @return array unserialized data
     */
    function getXMLDataAsArray($data, $isFile = true) {
    	$arrObj = null;
     	if(true && file_exists($data)) {
    		// initialize unserializer
    		$objArray = new XML_Unserializer();
    		// prepare unserializer options,- allow to parse attributes
    		$arrOptions = array(
	 			XML_UNSERIALIZER_OPTION_OVERRIDE_OPTIONS=>true,
	 			XML_UNSERIALIZER_OPTION_ATTRIBUTES_PARSE=>true,
	 			XML_UNSERIALIZER_OPTION_ATTRIBUTES_ARRAYKEY=>true,
//	 			,
	 			XML_UNSERIALIZER_OPTION_ENCODING_TARGET=>'ISO-8859-1',
	 			XML_UNSERIALIZER_OPTION_ENCODING_SOURCE=>'ISO-8859-1'
 			);
 			// unserialize it
    		$status = $objArray->unserialize($data, $isFile, $arrOptions);
    		// get unserialized data
    		if (PEAR::isError($status)) {  
			   $this->arrErrors[] = $status->getMessage();
			} else {
    			$arrObj = $objArray->getUnserializedData();
    		}
      	}
    	return $arrObj;
    }
    /**
     * sendPostData method send data to server and get result.
     * 
     * @param string $url			request URL
     * @param array $data			POST data
     * @param string $agent			browser info, agent string
     * @param array $header			browser headers
     * @param array $arr_cookie		cookie data
     * @param string $cookie_file	cookie file path (if needed sure)
     * @param boolean $saveToFile	true if results should be saved to file
     * 
     * @return string result page
     */
    function sendPostData($url,$data = array(), $agent='',$header=array(), $arr_cookie = array(), $cookie_file = 'cookies/curlcookies.txt', $saveToFile = false) {
		// initialize CURL
		$Curl_Session = curl_init($url);
		$arrUrl = array();
		if(sizeof($data)>0) {
			foreach($data as $k=>$v) {
				$arrUrl[] = $k . '='.$v;
			}
		}
		echo $url;
		if(!$this->notNull($agent))
			$agent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru-RU; rv:1.7.12) Gecko/20050919 Firefox/1.0.7" ;
		if(!$this->notNull($header)) {
	    		// ower browser read only in html format
			$header[] = "Accept: text/html;q=0.9, text/plain;q=0.8, image/png, */*;q=0.5" ;
			$header[] = "Accept_charset: iso-8859-1, windows-1251, utf-8, utf-16;q=0.6, *;q=0.1";
			    // say, that browser do not read gzip format
			$header[] = "Accept_encoding: identity";
			$header[] = "Accept_language: en-us,en;q=0.5";
			$header[] = "Connection: close";
			$header[] = "Cache-Control: no-store, no-cache, must-revalidate";
			$header[] = "Keep_alive: 300";
			$header[] = "Expires: Thu, 01 Jan 2010 00:00:01 GMT";
		}
		$COKKIES = '';
		if ( is_array ($arr_cookie) && !$this->notNull($arr_cookie) ){
	    	while (list($key, $val) = @each ($arr_cookie)){
	      		$COKKIES .= trim ($val[0])."=". trim ($val[1])."; ";
	    	}
	    }
		ob_start();
		$this->strUrl = implode('&', $arrUrl);
		if(sizeof($data)>0) {
			curl_setopt ($Curl_Session, CURLOPT_POST, 1);
			curl_setopt ($Curl_Session, CURLOPT_POSTFIELDS, $this->strUrl);
		}
		
		if(ereg("^(https)",$url)) {
    		curl_setopt($Curl_Session,CURLOPT_SSL_VERIFYPEER,false);
    		curl_setopt ($Curl_Session , CURLOPT_SSL_VERIFYHOST, false);
		}
		curl_setopt ( $Curl_Session , CURLOPT_HEADER , 0 );
		curl_setopt ( $Curl_Session , CURLOPT_USERAGENT , $agent );
		curl_setopt ( $Curl_Session , CURLOPT_HTTPHEADER , $header );
		curl_setopt ( $Curl_Session , CURLOPT_COOKIE , $COKKIES." expires=Mon, 14-Apr-20 10:34:13 GMT" );
			// curl_setopt ($Curl_Session, CURLOPT_FOLLOWLOCATION, 1);
		    // if we have cookie, then save into $cookie_file
		curl_setopt ( $Curl_Session , CURLOPT_COOKIEJAR , $cookie_file );
		curl_setopt ( $Curl_Session , CURLOPT_COOKIEFILE , $cookie_file );
		if($saveToFile) {
//			echo $saveToFile;
			$fp = @fopen($saveToFile,"w");
			curl_setopt( $Curl_Session, CURLOPT_FILE, $fp );
		}
		$res = curl_exec ($Curl_Session);
		
		$output = ob_get_contents();
		ob_end_clean();
		if(!$saveToFile) {
			$this->cUrlOutput = $output;
			$this->cUrlInfo = curl_getinfo($Curl_Session);
			$this->cUrlError = curl_error($Curl_Session);
		}
		if(ICECAT_DEBUG == 'true') {
			echo '<pre>';
			print_r($output);
			echo '</pre>';
		}
		curl_close ($Curl_Session);
		if($saveToFile) {
			@fclose($fp);
		}
		return $res;
    }
    /**
     * notNull method check variable if it is not NULL,void etc.
     * 
     * @param NULL|integer|boolean|array|string|double $variable
     * @return boolean true if variable has non zero value and false in other case
     */
    function notNull($variable=NULL) {
    	$type = gettype($variable);
    	$res = true;
    	switch ($type) {
			case 'NULL':
				$res = false;
				break;
			case 'string':
				if(strlen(trim($variable))<1) $res = false;
				break;
			case 'array':
				if(sizeof($variable)<=0) {
		          $res = false;
		        } else {
		          foreach($variable as $k=>$v) {
		            if(is_array($v)) {
		            	$res = $this->notNull($v);
		            } else {
		            	$res = $this->notNull(trim($v));
		            }
		            if($res) return true;
		          }
		        }
				break;
			case 'integer':
			case 'double':
				if($variable == 0) $res = false;
				break;
				break;
			case 'boolean': $res = $variable;
			default:
				if($variable == NULL || strlen(trim($variable) )<=0) $res = false;
				break;
		}
		return $res;
    }
}
?>