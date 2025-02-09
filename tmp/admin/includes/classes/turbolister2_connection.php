<?php
/*
 * Created on 14 ����. 2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
 class turbolister2_connection {
 	var $_response = false;
 	function connection() {
 		$this->clearData();
 	}
 	function clearData() {
    	$this->_url = '';
		$this->_data = array();
		$this->_to_file = false;
    }
    function formBody($data) {
    	$str = '';
    	foreach($data as $name=>$val) {
    		$str .= '<'.$name.'>';
    		if(is_array($val)) {
    			$str .= $this->formBody($val);
    		} else {
    			$str .= $val;
    		}
    		$str .= '</'.$name.'>';
    	}
    	return $str;
    }
    function formAPIData($name) {
    	switch ( $name ) {
	case 'trading':
	if(MODULE_TURBOLISTER2_CONNECTION == 'production') {
		$this->_url = 'https://api.ebay.com/ws/api.dll';
	} else {
		$this->_url = 'https://api.sandbox.ebay.com/ws/api.dll';
	}
			$this->_headers = array(
'X-EBAY-API-COMPATIBILITY-LEVEL:687',
'X-EBAY-API-DEV-NAME:'.MODULE_TURBOLISTER2_DEV_NAME,
'X-EBAY-API-APP-NAME:'.MODULE_TURBOLISTER2_APP_NAME,
'X-EBAY-API-CERT-NAME:'.MODULE_TURBOLISTER2_CERT_NAME,
'X-EBAY-API-SITEID:'.MODULE_TURBOLISTER2_SITEID,
'X-EBAY-API-CALL-NAME:'.$this->_callname,
'X-EBAY-API-REQUEST-ENCODING:XML',    // for a POST request, the response by default is in the same format as the request
'Content-Type: text/xml;charset=utf-8',
    );
		break;
	case 'shopping':
	if(MODULE_TURBOLISTER2_CONNECTION == 'production') {
		$this->_url = 'http://open.api.ebay.com/shopping';
	} else {
		$this->_url = 'http://open.api.sandbox.ebay.com/shopping';
	}
	$this->_headers = array(
'X-EBAY-API-APP-ID:'.MODULE_TURBOLISTER2_APP_NAME,
'X-EBAY-API-VERSION:685',
'X-EBAY-API-SITE-ID:'.MODULE_TURBOLISTER2_SITEID,
'X-EBAY-API-CALL-NAME:'.$this->_callname,
'X-EBAY-API-REQUEST-ENCODING:XML',    // for a POST request, the response by default is in the same format as the request
'Content-Type: text/xml;charset=utf-8',
    );
		break;
	default:
		break;
}
    }
    function setDebug($debug=false) {
    	$this->_debug=(boolean)$debug;
    	return $this;
    }
 	function sendRequest($api,$callname, $data = array(), $saveToFile = false) {
		$this->_data = $data;
		$this->_callname = $callname;
		$this->_to_file = $saveToFile;
		$this->formAPIData($api);
 		// initialize CURL
		$Curl_Session = curl_init($this->_url);
        //echo MODULE_TURBOLISTER2_CONNECTION . '<br />' . $this->_url . '<br />';
		$this->strUrl = '<?xml version="1.0" encoding="utf-8"?>
<'.$callname.'Request xmlns="urn:ebay:apis:eBLBaseComponents">
'.$this->formBody($data).'
</'.$callname.'Request>';
		$COKKIES = '';
		if($this->_debug) {
			global $messageStack;
			$messageStack->add('Data: ' . '<pre>' . print_r($this->_data,1) . '</pre>', 'warning');
			$messageStack->add('Call name: ' . $this->_callname, 'warning');
			$messageStack->add('URL: ' . $this->_url, 'warning');
			$messageStack->add('API: ' . $api, 'warning');
			$messageStack->add('XML: ' . '<pre>'.htmlspecialchars($this->strUrl) . '</pre>', 'warning');
			$messageStack->add('Headers: ' . '<pre>'.print_r($this->_headers,1).'</pre>', 'warning');
		}
		ob_start();
		if(sizeof($data)>0) {
			curl_setopt ($Curl_Session, CURLOPT_POST, 1);
			curl_setopt ($Curl_Session, CURLOPT_POSTFIELDS, $this->strUrl);
		}
		
		if(ereg("^(https)",$this->_url)) {
    		curl_setopt($Curl_Session,CURLOPT_SSL_VERIFYPEER,false);
    		curl_setopt ($Curl_Session , CURLOPT_SSL_VERIFYHOST, false);
		}
		curl_setopt ( $Curl_Session , CURLOPT_HEADER , 0 );
		curl_setopt ( $Curl_Session , CURLOPT_HTTPHEADER , $this->_headers );
		if($saveToFile) {
//			echo $saveToFile;
			$fp = @fopen($saveToFile,"w");
			curl_setopt( $Curl_Session, CURLOPT_FILE, $fp );
		}
		$this->_response = curl_exec ($Curl_Session);
		
		$this->_response = ob_get_contents();
		ob_end_clean();
		if(!$saveToFile) {
			$this->cUrlOutput = $this->_response;
			$this->cUrlInfo = curl_getinfo($Curl_Session);
			$this->cUrlError = curl_error($Curl_Session);
		}
		curl_close ($Curl_Session);
		if($saveToFile) {
			@fclose($fp);
		}
		if($this->_debug) {
			$messageStack->add('Response: ' . '<pre>' . htmlspecialchars($this->_response).'</pre>', 'warning');
		}
		return $this;
 	}
 	function getResponse() {
 		return $this;
 	}
 	function asText() {
 		return print_r($this->cUrlInfo,1) . ' ' .$this->_response;
 	}
 	function asArray() {
 		$arrObj = null;
 		if($this->_to_file && !file_exists($this->_to_file)) {
 			$this->_to_file = false;
 		}
    	// initialize unserializer
    	$objArray = new XML_Unserializer();
    	// prepare unserializer options,- allow to parse attributes
    	$arrOptions = array(
	 		XML_UNSERIALIZER_OPTION_OVERRIDE_OPTIONS=>true,
	 		XML_UNSERIALIZER_OPTION_ATTRIBUTES_PARSE=>true,
	 		XML_UNSERIALIZER_OPTION_ATTRIBUTES_ARRAYKEY=>true,
//	 		,
	 		XML_UNSERIALIZER_OPTION_ENCODING_TARGET=>'ISO-8859-1',
	 		XML_UNSERIALIZER_OPTION_ENCODING_SOURCE=>'UTF-8'
 		);
 		// unserialize it
    	$status = $objArray->unserialize($this->_response, $this->_to_file, $arrOptions);
    	// get unserialized data
    	if (PEAR::isError($status)) {  
		   $this->arrErrors[] = $status->getMessage();
		} else {
    		$arrObj = $objArray->getUnserializedData();
    	}
    	return $arrObj;
 	}
 	function getOutput() {
 		$output = implode('<br />', $this->_output);
 		return $output;
 	}
 	function notNull($dt) {
 		if(is_null($dt)) return false;
 		if(is_array($dt) && sizeof($dt)==0) return false;
 		if(is_numeric($dt) && $dt==0) return false;
 		if(is_string($dt) && strlen(trim($dt))==0) return false;
 		return (bool)$dt;
 	}
 	function destroy() {
 		
 	}
 }
?>