<?php
/*
 * @filename:	pws_plugin.php
 * @version:	1.00
 * @project:	PWS
 *
 * @author:		Riccardo Roscilli <info@oscommerce.it>
 * @created:	15/mag/07
 * @modified:	15/mag/07 16:40:25
 *
 * @copyright:	2006-2007	Riccardo Roscilli @ PWS
 *
 * @desc:	Classe per i plugin
 *
 * @TODO:		
 */

class	pws_plugin	{
	// Variabili da configurare in ogni plugin
	var $plugin_type;			// Tipo del plugin
	var $plugin_code;			// Codice univoco del plugin ( deve coincidere con il nome del file )
	var	$plugin_name;			// Nome del plugin
	var $plugin_configurable=false;	// Plugin di tipo configurabile ? (se true, comparirà nella lista dei moduli di tipo "sistema")
	var $plugin_description;	// Descrizione del plugin
	var $plugin_version_const;	// Versione del codice
	var $plugin_needs=array();	// Array di codici di plugin richiesti da questo plugin
	var $plugin_conflicts=array();	// Array di codici di plugin incompatibili con questo plugin
	var $plugin_configKeys=array();	// Chiavi di configurazione del plugin, in forma chiave=>array(field1=>value1, ..., fieldn=>valuen)
	var $plugin_tables=array();	// Tables utilizzate dal plugin
	var	$plugin_editPage='';	// Indirizzo della pagina da aprire per l'editing delle impostazioni. Se vuoto le impostazioni vengono modificate nella paginata dei plugins
	var $plugin_sql_install='';	// Codice sql da applicare in fase di installazione del plugin
	var $plugin_sql_remove='';	// Codice sql da applicare in fase di rimozione del plugin
	var $plugin_sort_order;		// Ordine del plugin.
	var	$plugin_removable=true;	// Da settare a false se il plugin non può essere rimosso
	var $plugin_editable=true;	// Da settare a false se il plugin non deve comparire nella relativa sezione editing dei plugins

	// Definizione dei punti di intervento
	var	$plugin_hooks=array();	// Definizione dei punti di intervento del plugin: array associativo -- codice della funzione=>nome del metodo da chiamare

	// Variabili inizializzate da pws_engine al caricamento del plugin
	var $plugin_id;				// Id del plugin
	var $plugin_version;		// Versione installata del plugin
	var $plugin_using=array();	// Codice del plugin da cui dipende=>istanza del plugin
	var $plugin_usedby=array();	// Codice del plugin che usa questo=>istanza del plugin
	var	$plugin_config=array();	// Lista chiave di configurazione=>valore corrispondente
	// Superclassi
	var $_pws_engine;
	
	function pws_plugin	(&$pws_engine){
		//$this->selfCheck();
		$this->_pws_engine=&$pws_engine;
		$this->init();
	}
	// @function check
	// @desc	funzione di compatibilità con i moduli di osc
	// @return	boolean
	function check()	{
		return pws_engine::checkPlugin($this) ? 1:0;
	}
	// @function needsUpdate
	// @desc	funzione di controllo sull'aggiornamento del plugin
	// @return	boolean
	function needsUpdate()	{
		return $this->plugin_version!=$this->plugin_version_const;
	}
	// @function	selfCheck
	// @desc	Controlla l'installazione del plugin ed esegue l'installazione o l'aggiornamento come necessario
	function selfCheck() {
		//$this->plugin_version=pws_engine::checkPlugin($this->plugin_code);
		if ($this->plugin_version)
			$this->install();
		else if ($this->plugin_version!=$this->plugin_version_const)
			$this->update($this->plugin_version);
	}
	//	@function parseConfigSwitch
	//	@desc	Legge il contenuto di una chiave bool di configurazione e restituisce true se attiva
	//	@param	(string)	$configValue		Valore della chiave di configurazione
	function parseConfigSwitch($configValue){
		$configValue=strtolower($configValue);
		return $configValue=='true' ||
								 $configValue=='on' ||
								 $configValue=='yes' ||
								 $configValue=='si';
	}
	//	@function install
	//	@desc	Funzione di installazione del plugin
	function install(){
		return true;
	}
	//	@function remove
	//	@desc	Funzione di rimozione del plugin
	function remove(){
		return true;
	}
	//	@function update
	//	@desc	Funzione di aggiornamento del plugin
	//	@param	string $fromVersion		Vecchia versione
	function update($fromVersion)	{
		//$this->plugin_version=$this->plugin_version_const;
		$this->remove();
		$this->install();
	}
	//	@function reportError
	//	@desc	Riporta un messaggio
	function reportError($message, $immediate=true){
		$this->_pws_engine->reportMessage($message, $immediate, 'error');
	}
	//	@function reportWarning
	//	@desc	Riporta un messaggio
	function reportWarning($message, $immediate=true){
		$this->_pws_engine->reportMessage($message, $immediate, 'warning');
	}
	//	@function reportSuccess
	//	@desc	Riporta un messaggio
	function reportSuccess($message, $immediate=true){
		$this->_pws_engine->reportMessage($message, $immediate, 'success');
	}
	
	//	@function init
	//	@desc	Funzione di inizializzazione del plugin
	function init()	{
		
	}
	
	//	@function loadConfiguration
	//	@desc	Carica le opzioni di configurazione memorizzate
	function loadConfiguration()	{
		$this->plugin_config=array();
		reset($this->plugin_configKeys);
		foreach($this->plugin_configKeys as $key=>$keydef){
			$confQuery=tep_db_query("select * from ".TABLE_CONFIGURATION." where configuration_value='$key'");
			if ($conf=tep_db_fetch_array($confQuery))
				$this->plugin_config[$key]=$conf['configuration_value'];
			else
				$this->plugin_config[$key]=$keydef['configuration_value'];
			$this->plugin_configKeys[$key]['configuration_value']=$this->plugin_config[$key];
		}
	}
	//	@function setKey
	//	@desc	Funzione per impostare un valore di configurazione
	//	@note	Overridare per cambiare il metodo di impostazione
	function setKey($key,$value)	{
		tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($value) . "' where configuration_key = '" . $key . "'");
	}

	//	@function editConfiguration
	//	@desc	Crea il codice html per l'editing delle impostazioni
	function editConfiguration(){
		$keys='';
		$heading = array();
		$contents = array();
		reset($this->plugin_configKeys);
		foreach($this->plugin_configKeys as $key=>$keydefs){
			$value=$this->plugin_config[$key];
			$keys .= '<b>' . $keydefs['configuration_title'] . '</b><br>' . $keydefs['configuration_description'] . '<br>';

			if ($keydefs['set_function']) {
				eval('$keys .= ' . $keydefs['set_function'] . "'" . $value . "', '" . $key . "');");
			} else {
				$keys .= tep_draw_input_field('configuration[' . $key . ']', $value);
			}
			$keys .= '<br><br>';
		}
		$keys = substr($keys, 0, strrpos($keys, '<br><br>'));
		
		$heading[] = array('text' => '<b>' . $this->plugin_name . '</b>');
		
		$contents = array('form' => tep_draw_form('plugins', FILENAME_PLUGINS, 'set=' . $_REQUEST['set'] . '&plugin_code=' . $this->plugin_code . '&action=save'));
		$contents[] = array('text' => $keys);
		$contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . tep_href_link(FILENAME_PLUGINS, 'set=' . $_REQUEST['set'] . '&plugin_code=' . $this->plugin_code) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');

		$box = new box;
	    return $box->infoBox($heading, $contents);
	}

	//	@function saveConfiguration
	//	@desc	Gestisce la modifica delle impostazioni
	function saveConfiguration(){
		$configuration=$_REQUEST['configuration'];
		reset($configuration);
		foreach($configuration as $key=>$value){
			tep_db_query("update ".TABLE_CONFIGURATION." set configuration_value='".tep_db_input($value)."' where configuration_key='$key'");
		}
	}

	//	@function displayConfiguration
	//	@desc	Crea il codice html per la visualizzazione delle impostazioni
	function displayConfiguration(){
		$keys='';
		$heading = array();
		$contents = array();
		$heading[] = array('text' => '<b>' . $this->plugin_name . '</b>');

		if ($this->check()) {
	        reset($this->plugin_configKeys);
	        foreach($this->plugin_configKeys as $key=>$keydef) {
				$value=$keydefs['configuration_value'];
				$keys .= '<b>' . $keydef['title'] . '</b><br>';
				if ($keydef['use_function']) {
					$use_function = $keydef['use_function'];
					if (ereg('->', $use_function)) {
						$class_method = explode('->', $use_function);
						if (!is_object(${$class_method[0]})) {
							include(DIR_WS_CLASSES . $class_method[0] . '.php');
							${$class_method[0]} = new $class_method[0]();
						}
						$keys .= tep_call_function($class_method[1], $value, ${$class_method[0]});
					} else {
					  $keys .= tep_call_function($use_function, $value);
					}
				} else {
				  $keys .= $value;
				}
				$keys .= '<br><br>';
	        }
			$keys = substr($keys, 0, strrpos($keys, '<br><br>'));
			if ($this->plugin_editPage!=''){
				$editurl=tep_href_link($this->plugin_editPage);
				$editbutton='<a href="javascript:void()" onclick="OpenSubwindow(\''.$editurl.'\',\''.$this->plugin_name.'\')">'.tep_image_button('button_edit.gif', IMAGE_EDIT).'</a>';
			}
			else
				$editbutton='<a href="' . tep_href_link(FILENAME_PLUGINS, 'set=' . $_REQUEST['set'] . (isset($_REQUEST['plugin_code']) ? '&plugin_code=' . $_REQUEST['plugin_code'] : '') . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a>';
			if ($this->plugin_removable)
				$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_PLUGINS, 'set=' . $_REQUEST['set'] . '&plugin_code=' . $this->plugin_code . '&action=remove') . '">' . tep_image_button('button_module_remove.gif', IMAGE_MODULE_REMOVE) . '</a>'
								.$editbutton);
			else
				$contents[] = array('align' => 'center', 'text' =>$editbutton);
			$contents[] = array('text' => '<br>' . $this->plugin_description);
			$contents[] = array('text' => '<br>' . $keys);
		} else {
			$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_PLUGINS, 'set=' . $_REQUEST['set'] . '&plugin_code=' . $this->plugin_code . '&action=install') . '">' . tep_image_button('button_module_install.gif', IMAGE_MODULE_INSTALL) . '</a>');
			$contents[] = array('text' => '<br>' . $this->plugin_description);
		}
	$box = new box;
	    return $box->infoBox($heading, $contents);
	}
	//	@function editKey
	//	@desc	Funzione che costruisce il codice html per l'editing di una impostazione
	//	@param	string	$key	Chiave del valore
	//	@return string			Codice html per l'impostazione
	function editKey($key)	{
		
	}
	// @function redirect
	// @param	(string)	$url				Indirizzo da raggiungere
	// @param	(string)	$method				[Opzionale]'get' oppure 'post'
	// @param	(array)		$params				[Opzionale]Array dei parametri da spedire
	// @param	(string)	$relay_params		[Opzionale]Se true, i parametri ricevuti in $_REQUEST vengono inoltrati
	// @param	(string)	$forget_history		[Opzionale]Se true, si tenterà di dimenticare questa pagina
	// @param	(string)	$redirect_message	[Opzionale]Messaggio da mostrare all'utente durante il redirect
	function redirect($url='',$method='post',$params=array(),$relay_params=false,$forget_history=true,$redirect_message='')	{
		$relay_params = $relay_params && sizeof(explode('?',$url))==1;
		$method=strtolower($method);
		$pars=explode('?',$url);
		
		if (isset($pars[1]))
			$pars=$pars[1];
		else
			$pars='';
		$str_pars=$pars;
		if ($relay_params){
			$str_pars.=(strlen($pars)?'&':'').$this->getParamsAsString();
			$params=array_merge($_REQUEST,$params);
		}
		$pars=explode('?',$url);
		if (isset($pars[1]))
		{
			$fpars=explode('&',$pars[1]);
			foreach($fpars as $par)
			{
				$couple=explode('=',$par);
				$params[$couple[0]]=$couple[1];
			}
		}
		$url_and_pars=$pars[0].'?'.$str_pars;

		if (!headers_sent() && !$forget_history && $method=='get')
		{
			header("Location: ".$url_and_pars,true);
			exit;
		}
		if ($need_header=(!headers_sent())){
			$header=<<<EOT
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Checkout process</title>
</head>
<body onload="jumpto()">
EOT;
			;
			$footer='</body>
</html>
';
		}else{
			$header='';
			$footer='</body>
</html>
';
		}
		if ($method=='get'){
			if ($forget_history)	// Bisogna dimenticare l'url corrente, headers forse spediti, forse no
			{
				if ('get'==$method)
				{
					$content='
						<script language="JavaScript" type="text/javascript">
							function jumpto(){
								location.replace("'.$url_and_pars.'");
							}
						</script>
					';
				}
			}else{
				$content='
					<script language="JavaScript" type="text/javascript">
						function jumpto(){
								location.href="'.$url_and_pars.'";
						}
					</script>
				';
			}
		}else{ // Non bisogna dimenticare l'url corrente, headers gia' spediti
			$content=<<<EOT
				<center><h3>$redirect_message</h3></center>
				<form id="navigateto" name="navigateto" action="$url" method="post">
EOT;
			reset($params);
			foreach($params as $key=>$value)
				$content.=<<<EOT
					<input type="hidden" name="$key" value="$value" />
EOT;
			$content.= <<<EOT
				</form>
				<script language="JavaScript" type="text/javascript">
							function jumpto(){
								document.forms['navigateto'].submit();
							}
				</script>
EOT;
		}
//		$output='<!--'.$header.$content.$footer.'-->';
		$output=$header.$content.$footer;
		echo $output;
		exit;
	}
	
}
?>