<?php
/*
 * @filename:	*.php
 * @version:	1.00
 * @project:	PWS
 *
 * @author:		Riccardo Roscilli <info@oscommerce.it>
 * @created:	20/mag/08
 * @modified:	20/mag/08 12:47:46
 *
 * @copyright:	2006-2008	Riccardo Roscilli @ PWS
 *
 * @desc:	
 *
 * @TODO:		
 */
class HtmlPortionPreFilter extends PHPTAL_Filter {
    var $HEADER='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /></head><body>';
	var $FOOTER='</body></html>';
	function filter($source){
		return $HEADER.$source.$FOOTER;
	}
}

class HtmlPortionPostFilter extends PHPTAL_Filter {
    function filter($xhtml){
		return preg_replace("/^.*<body[^>]*>(.*)<\/body>.*$/si", "$1", $xhtml);
    }
}

?>