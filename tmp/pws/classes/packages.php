<?php
/*
 * @filename:	packages.php
 * @version:	1.00
 * @project:	PWS
 *
 * @author:		Riccardo Roscilli <info@oscommerce.it>
 * @created:	21/apr/08
 * @modified:	21/apr/08 15:53:52
 *
 * @copyright:	2006-2008	Riccardo Roscilli @ PWS
 *
 * @desc:	
 *
 * @TODO:		
 */

// Parser xml per i prodotti generati da Danea
class pws_packages_xml_parser{
	// Referenza al pws_engine
	var $pws_engine=NULL;
	// Importazione articoli tramite expat xml
	var $package,$packagesList=array();		// qui vengono memorizzati i dati dei prodotti durante l'importazione da xml
	var $xml_current_tag;	// Qui viene memorizzato il tag correntemente aperto
	var $xml_current_data;	// Qui viene memorizzato il contenuto del tag correntemente aperto
	var $xml_current_attribs=array();	// Qui viene memorizzato il contenuto degli attributi del tag correntemente aperto
	function pws_packages_xml_parser(&$pws_engine){
		$this->pws_engine=&$pws_engine;
	}
	
	//	@function importProductTagOpen
	//	@desc	Handler dell'apertura di un tag xml durante l'importazione prodotti
	function importProductTagOpen($parser, $tag, $attribs){
		switch ($tag){
			case 'PWSModule':
				$this->package=array();
				foreach($attribs as $attrname=>$attrvalue){
					$this->package[$attrname]=$attrvalue;
				}
				break;
			case 'PWSPackages':
				$this->package=NULL;
			default:
				break;
		}
		$this->xml_current_data='';
		$this->xml_current_attribs=$attribs;
	}
	//	@function importProductTagClose
	//	@desc	Gestore della chiusura di un tag
	function importProductHandleCharacterData(/*resource*/ $parser, /*string*/ $dati ){
		$this->xml_current_data.=trim($dati);
	}
	function importProductTagClose($parser, $tag){
		switch ($tag){
			case 'PWSModule':
				// Controlla se il pacchetto è presente
				if (isset($this->package['dirname'])){
					$dirname=$this->package['dirname'];
					if (is_array($dirname)){
						$present=true;
						reset($dirname);
						foreach($dirname as $dir){
							$present=$present && (is_dir(DIR_FS_PWS_EXTRAS.$dir));
						}
						$this->package['isPresent']=$present?'yes':'no';
					}else{
						if (is_dir(DIR_FS_PWS_EXTRAS.$dirname)){
							$this->package['isPresent']='yes';
						}else{
							$this->package['isPresent']='no';
						}
					}
				}else{
					$this->package['isPresent']='no';
				}
				// Controlla se il pacchetto è installato
				if ($this->package['isPresent']=='yes'){
					$installed=false;
					if (isset($this->package['checkInstallation'])){
						eval($this->package['checkInstallation']);
						$this->package['isInstalled']=$installed ? 'yes':'no';
					}else{
						$this->package['isInstalled']='maybe';
					}
				}
				$this->packagesList[]=$this->package;
				$this->package=NULL;
				break;
			default:
				if (!is_null($this->package)){
					if (sizeof($this->xml_current_attribs)){
						$this->package[$tag]=array('content'=>$this->xml_current_data);
						reset($this->xml_current_attribs);
						foreach($this->xml_current_attribs as $attrname=>$attrvalue){
							$this->package[$tag][$attrname]=$attrvalue;
						}
					}else{
						if (isset($this->package[$tag])){
							if (is_array($this->package[$tag])){
								$this->package[$tag]=array_merge($this->package[$tag],$this->xml_current_data);
							}else{
								$this->package[$tag]=array($this->package[$tag],$this->xml_current_data);
							}
						}else{
							$this->package[$tag]=$this->xml_current_data;
						}
					}
				}
				break;
		}
	}	
}

?>