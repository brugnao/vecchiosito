<?php
/*
$Id: sts_display_output.php,v 1.2 2004/02/05 05:57:12 jhtalk Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
*/

/* 

  Simple Template System (STS) - Copyright (c) 2004 Brian Gallagher - brian@diamondsea.com

*/

// mettiamo il turbo
// todo: parametrizzare la durata della cache nelle varie pagine
// rendere il contenuto del carrello indipendente dalla cache  


// Used for debugging, please don't change
$sts_version = "2.01";
$sts_osc_version = PROJECT_VERSION;
$sts_osc_version_required = "osCommerce 2.2-MS2";

// Perform OSC version checking
if ($sts_osc_version != $sts_osc_version_required) {
  //echo "STS was designed to work with OSC version [$sts_osc_version_required].  This is version [$sts_osc_version].\n";
}

$template['debug'] .= ''; // Define as blank if not already defined

/////////////////////////////////////////////
// SELECT HOW TO DISPLAY THE OUTPUT
/////////////////////////////////////////////
$display_template_output = 1;
$display_normal_output = 0;
$display_debugging_output = 0;
$display_version_output = 0;

// Override if we need to show a pop-up window
$scriptname = $_SERVER['PHP_SELF'];
$scriptname = getenv('SCRIPT_NAME');
$scriptbasename = substr($scriptname, strrpos($scriptname, '/') + 1);
// If script name contains "popup" then turn off templates and display the normal output
// This is required to prevent display of standard page elements (header, footer, etc) from the template and allow javascript code to run properly
if (strpos($scriptname, "popup") !== false || strpos($scriptname, "info_shopping_cart") !== false || strpos($scriptname, "affiliate_valid") !== false || strpos($scriptname, "affiliate_help") !== false) {
$display_normal_output = 1;
$display_template_output = 0;
}


/////////////////////////////////////////////
// Allow the ability to turn on/off settings from the URL
// Set values to 0 or 1 as needed
/////////////////////////////////////////////

// Allow Template output control from the URL
if ($HTTP_GET_VARS['sts_template'] != "") {
$display_template_output = $HTTP_GET_VARS['sts_template'];
}
 
// Allow Normal output control from the URL
if ($HTTP_GET_VARS['sts_normal'] != "") {
$display_normal_output = $HTTP_GET_VARS['sts_normal'];
}

// Allow Debugging control from the URL
if ($HTTP_GET_VARS['sts_debug'] != "") {
$display_debugging_output = $HTTP_GET_VARS['sts_debug'];
}

// Allow Version control from the URL
if ($HTTP_GET_VARS['sts_version'] != "") {
$display_version_output = $HTTP_GET_VARS['sts_version'];
}

// Print out version number if needed
if ($display_version_output == 1 or $display_debugging_output == 1) {
print "STS_VERSION=[$sts_version]\n";
print "OSC_VERSION=[$sts_osc_version]\n";
}


if (NEW_TEMPLATE_SYSTEM == 'true')
{
	if (!isset($template_dir))
		$template_dir = 'speedracer/';
	//	define (STS_TEMPLATE_DIR , $template_dir);
	$sts_template_file = 'templates/'. $template_dir . 'sts_template.html';

}
else 
{
// Start with the default template
$sts_template_file = STS_DEFAULT_TEMPLATE;
}

// See if there is a custom template file for the currently running script
if ($sts_custom_template==''){
	$sts_check_file = STS_TEMPLATE_DIR . $scriptbasename . ".html";
	if (file_exists($sts_check_file)) {
	  // Use it
	  $sts_template_file = $sts_check_file;
	} 

	// Are we in the index.php script?  If so, what is our Category Path (cPath)?
	if ($scriptbasename == "index.php") {
	  // If no cPath defined, default to 0 (the home page)
	  if ($cPath == "") {
		$sts_cpath = 0; 
	  } else {
	        $sts_cpath = $cPath;
	  }
	
	  // Look for category-specific template file like "index.php_1_17.html"
	  $sts_check_file = STS_TEMPLATE_DIR . "index.php_$sts_cpath.html";
	
	  if (file_exists($sts_check_file)) {
	    // Use it
	    $sts_template_file = $sts_check_file;
	  } 
	
	}
}

// Open Template file and read into a variable
if (! file_exists($sts_template_file)) {
  echo "Template file doesn't exist: [$sts_template_file]";
}  else {
  // echo "<!-- Using Template File [$sts_template_file) -->\n";
}

if (! $fh = fopen($sts_template_file, 'r')) {
echo "Can't open Template file: [$sts_template_file]";
}

$template_html = fread($fh, filesize($sts_template_file));
fclose($fh);


/////////////////////////////////////////////
////// if product_info.php load data
/////////////////////////////////////////////
if ($scriptbasename == 'product_info.php') {
  require(STS_PRODUCT_INFO);
}

/////////////////////////////////////////////
////// Run any user code needed
/////////////////////////////////////////////
require(STS_USER_CODE);

/////////////////////////////////////////////
////// Set up template variables
/////////////////////////////////////////////

/////////////////////////////////////////////
////// Capture <title> and <meta> tags
/////////////////////////////////////////////

// STS: ADD: Support for WebMakers.com's Header Tag Controller contribution
  // Capture the output
  require(STS_START_CAPTURE);
  
   
   // BOF: WebMakers.com Changed: Header Tag Controller v1.0
  // Replaced by header_tags.php
  if ( file_exists(DIR_WS_INCLUDES . 'header_tags.php') ) {
    require(DIR_WS_FUNCTIONS . 'clean_html_comments.php');
    require(DIR_WS_FUNCTIONS . 'header_tags.php');
    require(DIR_WS_INCLUDES . 'header_tags.php');
  } else {
	if (!isset($template['metatag']) ||
		(isset($template['metatag']) && false===strpos($template['metatag'],'<title>'))){
		echo "<title>" . TITLE . "</title>";
	}
  }
  // EOF: WebMakers.com Changed: Header Tag Controller v1.0

  $sts_block_name = 'headertags';
  require(STS_STOP_CAPTURE);

// STS: EOADD: Support for WebMakers.com's Header Tag Controller contribution

/////////////////////////////////////////////
////// Set up template variables
/////////////////////////////////////////////

   $template['sid'] =  tep_session_name() . '=' . tep_session_id();

  // questa riga è fondamentale per far funzionare IE anche in modalità turbo, non deve esserci nessuna riga prima di questa
   $template['headcontent'] .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"  \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
   
   $template['headcontent'] .= "<!-- Template System by osCommerce.it - Realizza il tuo template con un solo file html! Per info http://www.oscommerce.it/ -->\n";
   $template['headcontent'] .= '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">';
   $template['headcontent'] .= '<head>';  
   // Strip out <title> variable
   $template['headcontent'] .= $template['metatag'];
  
   $template['title'] = str_between($sts_block['headertags'], "<title>", "</title>");

   // Load up the <head> content that we need to link up everything correctly.  Append to anything that may have been set in sts_user_code.php
   $template['headcontent'] .= '<meta http-equiv="Content-Type" content="text/html; charset=' . CHARSET . '">' . "\n"; 

 
  // compatibilit� con quella m... di IE8 <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" /> 
  // <meta http-equiv="X-UA-Compatible" content="IE=5" />   
 //  $template['headcontent'] .= '<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />' . "\n"; 
  $template['headcontent'] .= '<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />' . "\n"; 
  $template['headcontent'] .= $sts_block['headertags'];

  $template['headcontent'] .= '<base href="' . (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . '">' . "\n";
  
  // light box
  $template['headcontent'] .= '<link rel="stylesheet" href="lightbox.css" type="text/css" media="screen" />' . "\n";
 
  // ligth window
  $template['headcontent'] .= '<link rel="stylesheet" href="lightwindow.css" type="text/css" media="screen" />' . "\n";
 
  // countdown
  $template['headcontent'] .= '<link rel="stylesheet" href="lightbox/countdown.css" type="text/css" media="screen" />' . "\n";
  
  
  // search suggest
  $template['headcontent'] .= '<script language="JavaScript" type="text/javascript" src="includes/class.OSCFieldSuggest.js"></script>';
 
  
  if (is_object($pws_engine)){
  	$template['headcontent'] .= $pws_engine->triggerHook('CATALOG_HEAD');
  	if (!$pws_engine->isInstalledPlugin('pws_dropdownmenu','application')){
  		$template['headcontent'] .= '<link rel="stylesheet" type="text/css" href="stylesheets/box_categories.css"/>' . "\n";
  	}
  }
  $template['headcontent'] .= get_javascript($sts_block['applicationtop2header'],'get_javascript(applicationtop2header)');
  if (is_object($pws_engine)){
  	$template['headcontent'] .= $pws_engine->triggerHook('CATALOG_JAVASCRIPT');
   }
   
   // ligth box e altri script 
    	$template['headcontent'] .= '<link rel="stylesheet" href="lightbox.css" type="text/css" media="screen" />';
		   	
    //	if (!file_exists(DIR_FS_CATALOG . "pws/plugins/application/pws_dropdownmenu.php" ))
		$template['headcontent'] .= '<script type="text/javascript" src="lightbox/prototype.js"></script>';		
		$template['headcontent'] .= '<script type="text/javascript" src="lightbox/scriptaculous.js?load=effects,builder"></script>
								<script type="text/javascript" src="lightbox/builder.js"></script>
  								<script type="text/javascript" src="lightbox/lightbox.js"></script>
								<script type="text/javascript" src="lightbox/ajaxCart.js"></script>
    					
		
    							<script type="text/javascript" src="lightbox/lightwindow.js"></script>
    							<script type="text/javascript" src="lightbox/countdown.js"></script>';
		
		if (SHOPWINDOW_ENABLED=='true' && file_exists(DIR_FS_PWS_STYLESHEETS.'pws_module_shopwindow_html.css') )
    			$template['headcontent'] .= '<script type="text/javascript" src="lightbox/glider.js"></script>';
   	//	<script type="text/javascript" src="lightbox/swfobject.js"></script>'
   
//PWS bof
  if (is_object($pws_prices))
  	$template['headcontent'] .= $pws_prices->catalogStylesheet();
  if (isset($template['pwsstylesheets']))
  	$template['headcontent'] .= $template['pwsstylesheets'];
  if (is_object($pws_engine))
	$template['headcontent'].=$pws_engine->triggerHook('CATALOG_STYLESHEET');
  if (file_exists(DIR_FS_STYLESHEETS.basename($PHP_SELF,'.php').'.css')){
  	$template['headcontent'].='<link rel="stylesheet" type="text/css" href="'.DIR_WS_STYLESHEETS.basename($PHP_SELF,'.php').'.css'.'"/>'."\r\n";
  }
  if (SHOPWINDOW_ENABLED=='true' 
  	&& file_exists(DIR_FS_PWS_STYLESHEETS.'pws_module_shopwindow_html.css') 
  	&& basename($PHP_SELF,'.php')=='index'
  	&& !isset($_REQUEST['cPath'])
  	&& !isset($_GET['manufacturers_id'])
  	&& $category_depth != 'products'
  	&& $category_depth != 'nested'){
	  	$template['headcontent'].='<link rel="stylesheet" type="text/css" href="'.DIR_WS_PWS_STYLESHEETS.'pws_module_shopwindow_html.css'.'"/>'."\r\n";
	  	$template['headcontent'].='<link rel="stylesheet" type="text/css" href="stylesheets/glider.css"/>'."\r\n";
	  
	  		$template['headcontent'].='<link rel="stylesheet" type="text/css" href="stylesheets/coin-slider-styles.css"/>'."\r\n";
  	}
 	//  $template['headcontent'].='<body onload="my_glider.start();return false">';
 //   $template['headcontent'].='<body onload="countdown(2011,09,20,00,00,00)">';
//PWS eof
 // 	$template['bodyparams'] = ' onload="doLoad(\'pid='.(int)$HTTP_GET_VARS['products_id'].'&languages_id='.$languages_id.'\',null,\'productDesc\;countdown(year,month,day,hour,minute)"';
   	$template['bodyparams'] = 'onload="my_glider.start()"';
  // Note: These values lifted from the stock /catalog/includes/header.php script's HTML
  // catalogurl: url to catalog's home page
  // catalog: link to catalog's home page
  $language_code=array_pop(tep_db_fetch_array(tep_db_query("select upper(code) as code from ".TABLE_LANGUAGES." where languages_id='$languages_id'")));
  	
  $template['srccataloglogo'] = urldecode(constant('STORE_LOGO_'.$language_code));
  $template['cataloglogo'] = '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image(DIR_WS_IMAGES . urldecode(constant('STORE_LOGO_'.$language_code)), STORE_NAME) . '</a>';
  $template['urlcataloglogo'] = tep_href_link(FILENAME_DEFAULT);

  
  if (file_exists(DIR_FS_CATALOG . DIR_WS_INCLUDES . 'navbar.php'))
  {
  	include(DIR_FS_CATALOG . DIR_WS_INCLUDES . 'navbar.php');
  	
  }
  else // menu statico da eliminare al più presto!
  {
  
  // STAS modification start here 
  if (tep_session_is_registered('customer_id')) {
  	  $template['menu'] = '<table cellpadding="0" cellspacing="0" border="0">
						<tr>
						<td><a href="'. tep_href_link(FILENAME_DEFAULT) . '" class="menu_link"> '. HEADER_TITLE_TOP .'</a></td>
						<td class="menu_separator"><img src="images/menu_sep.gif" border="0" alt="" width="2" height="17"></td>
						<td><a href="'. tep_href_link(FILENAME_PRODUCTS_NEW) . '" class="menu_link">'. BOX_HEADING_WHATS_NEW .'</a></td>
						<td class="menu_separator"><img src="images/menu_sep.gif" border="0" alt="" width="2" height="17"></td>
						<td><a href="'. tep_href_link(FILENAME_SPECIALS) . '" class="menu_link">' . BOX_HEADING_SPECIALS . '</a></td>
						<td class="menu_separator"><img src="images/menu_sep.gif" border="0" alt="" width="2" height="17"></td>
						<td><a href="'. tep_href_link(FILENAME_ACCOUNT) . '" class="menu_link">'. HEADER_TITLE_MY_ACCOUNT .'</a></td>
						<td class="menu_separator"><img src="images/menu_sep.gif" border="0" alt="" width="2" height="17"></td>
						<td><a href="'. tep_href_link(FILENAME_LOGOFF) . '" class="menu_link">'. HEADER_TITLE_LOGOFF .'</a></td>
						<td class="menu_separator"><img src="images/menu_sep.gif" border="0" alt="" width="2" height="17"></td>
						<td><a href="'. tep_href_link(FILENAME_CONTACT_US) . '" class="menu_link">' . BOX_INFORMATION_CONTACT . '</a></td>
						<td class="menu_separator"><img src="images/menu_sep.gif" border="0" alt="" width="2" height="17"></td>
						<td><a href="'. tep_href_link(FILENAME_SHOPPING_CART) . '" class="menu_link">' . BOX_HEADING_SHOPPING_CART . '</a></td>
						<td class="menu_separator"><img src="images/menu_sep.gif" border="0" alt="" width="2" height="17"></td>
						</tr>
						</table>';
  	   }
   else
   {

  	   $template['menu'] = '<table cellpadding="0" cellspacing="0" border="0">
						<tr>
						<td><a href="'. tep_href_link(FILENAME_DEFAULT) . '" class="menu_link"> '. HEADER_TITLE_TOP .'</a></td>
						<td class="menu_separator"><img src="images/menu_sep.gif" border="0" alt="" width="2" height="17"></td>
						<td><a href="'. tep_href_link(FILENAME_PRODUCTS_NEW) . '" class="menu_link">'. BOX_HEADING_WHATS_NEW .'</a></td>
						<td class="menu_separator"><img src="images/menu_sep.gif" border="0" alt="" width="2" height="17"></td>
						<td><a href="'. tep_href_link(FILENAME_SPECIALS) . '" class="menu_link">' . BOX_HEADING_SPECIALS . '</a></td>
						<td class="menu_separator"><img src="images/menu_sep.gif" border="0" alt="" width="2" height="17"></td>
						<td><a href="'. tep_href_link(FILENAME_CREATE_ACCOUNT) . '" class="menu_link">'. HEADER_TITLE_CREATE_ACCOUNT .'</a></td>
						<td class="menu_separator"><img src="images/menu_sep.gif" border="0" alt="" width="2" height="17"></td>
						<td><a href="'. tep_href_link(FILENAME_LOGIN) . '" class="menu_link">'. HEADER_TITLE_LOGIN .'</a></td>
						<td class="menu_separator"><img src="images/menu_sep.gif" border="0" alt="" width="2" height="17"></td>
						<td><a href="'. tep_href_link(FILENAME_CONTACT_US) . '" class="menu_link">' . BOX_INFORMATION_CONTACT . '</a></td>
						<td class="menu_separator"><img src="images/menu_sep.gif" border="0" alt="" width="2" height="17"></td>
						<td><a href="'. tep_href_link(FILENAME_SHOPPING_CART) . '" class="menu_link">' . BOX_HEADING_SHOPPING_CART . '</a></td>
						<td class="menu_separator"><img src="images/menu_sep.gif" border="0" alt="" width="2" height="17"></td>
						</tr>
						</table>';
     }
  
  // menu stile wirmax
  
   if (tep_session_is_registered('customer_id')) {
    	 $template['sottomenu'] = '<table width="660" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="30">&nbsp;</td>
            <td width="150" valign="top"><table width="150" border="0" cellspacing="0" cellpadding="0">
			  <tr>
			    <td width="31" height="26"><img src="../images/account.jpg" width="44" height="62" alt="'. HEADER_TITLE_CREATE_ACCOUNT .'"></td>
			    <td align="left" valign="middle" class="txt_nero"><a href="'. tep_href_link(FILENAME_ACCOUNT) . '" class="txt_nero">'. HEADER_TITLE_MY_ACCOUNT .'</a></td>
			  </tr>
			</table>
			</td>
			            <td width="110"><table width="110" border="0" cellspacing="0" cellpadding="0">
			  <tr>
			    <td width="31" height="26"><img src="../images/login.jpg" width="44" height="62"></td>
			    <td align="left" valign="middle" class="txt_nero"><a href="'. tep_href_link(FILENAME_LOGOFF) . '" class="txt_nero">'. HEADER_TITLE_LOGOFF .'</a></td>
			  </tr>
			</table></td>
			            <td width="140"><table width="140" border="0" cellspacing="0" cellpadding="0">
			  <tr>
			    <td width="31" height="26"><img src="../images/carrello.jpg" width="44" height="62"></td>
			    <td align="left" valign="middle" class="txt_nero"><a href="'. tep_href_link(FILENAME_SHOPPING_CART) . '" class="txt_nero">' . BOX_HEADING_SHOPPING_CART . '</a></td>
			  </tr>
			</table></td>
           <td width="140"><table width="140" border="0" cellspacing="0" cellpadding="0">
             <tr>
               <td width="31" height="26"><img src="../images/aiuto.jpg" width="44" height="62"></td>
               <td align="left" valign="middle" class="txt_nero"><a href="'. tep_href_link(FILENAME_SHOPPING_AIUTO) . '" class="txt_nero">' . HEADER_TITLE_AIUTO . '</a></td>
             </tr>
			</table></td>
            <td width="90">&nbsp;</td>
          </tr>
 	       </table>';                      
   	
   }
   else
   {
   	 $template['sottomenu'] = '<table width="660" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="30">&nbsp;</td>
            <td width="150" valign="top"><table width="150" border="0" cellspacing="0" cellpadding="0">
			  <tr>
			    <td width="31" height="26"><img src="../images/account.jpg" width="44" height="62" alt="'. HEADER_TITLE_CREATE_ACCOUNT .'"></td>
			    <td align="left" valign="middle" class="txt_nero"><a href="'. tep_href_link(FILENAME_CREATE_ACCOUNT) . '" class="txt_nero">'. HEADER_TITLE_CREATE_ACCOUNT .'</a></td>
			  </tr>
			</table>
			</td>
			            <td width="110"><table width="110" border="0" cellspacing="0" cellpadding="0">
			  <tr>
			    <td width="31" height="26"><img src="../images/login.jpg" width="44" height="62"></td>
			    <td align="left" valign="middle" class="txt_nero"><a href="'. tep_href_link(FILENAME_LOGIN) . '" class="txt_nero">'. HEADER_TITLE_LOGIN .'</a></td>
			  </tr>
			</table></td>
			            <td width="140"><table width="140" border="0" cellspacing="0" cellpadding="0">
			  <tr>
			    <td width="31" height="26"><img src="../images/carrello.jpg" width="44" height="62"></td>
			    <td align="left" valign="middle" class="txt_nero"><a href="'. tep_href_link(FILENAME_SHOPPING_CART) . '" class="txt_nero">' . BOX_HEADING_SHOPPING_CART . '</a></td>
			  </tr>
			</table></td>
           <td width="140"><table width="140" border="0" cellspacing="0" cellpadding="0">
             <tr>
               <td width="31" height="26"><img src="../images/aiuto.jpg" width="44" height="62"></td>
               <td align="left" valign="middle" class="txt_nero"><a href="'. tep_href_link(FILENAME_SHOPPING_AIUTO) . '" class="txt_nero">' . HEADER_TITLE_AIUTO . '</a></td>
             </tr>
			</table></td>
            <td width="90">&nbsp;</td>
          </tr>
 	       </table>';                      
                        
   }
  }// fine else del navbar.php
  
   
  $template['headhome'] = '<a href="' . tep_href_link(FILENAME_DEFAULT, '') . '" class="head_line">' . HEADER_TITLE_TOP . '</a>';
  $template['headcart'] = '<a href="' . tep_href_link(FILENAME_SHOPPING_CART, '') . '" class="head_line">' . BOX_HEADING_SHOPPING_CART . '</a>';
  $template['headcontacts'] = '<a href="' . tep_href_link(FILENAME_CONTACT_US, '') . '" class="head_line">' . CATEGORY_CONTACT . '</a>';
  
  
  // STAS end here
  

  
  
  // nuova gestione banner everywhere
  // ricavo la lista dei gruppi e creo tanti tag quanti sono i gruppi
  $banner_group_query = tep_db_query("select DISTINCT banners_group  from " . TABLE_BANNERS . " where 1 "); 
 
  while($banner_group_array = tep_db_fetch_array($banner_group_query))
  {
  	$group = $banner_group_array['banners_group'];
   $template['ban'.$group] = tep_display_banner('dynamic', $group);
   // ogni tag quindi sar� ban+nome gruppo banner per es. ban468x50
  }
						  
  $template['myaccountlogo'] = '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . tep_image(DIR_WS_IMAGES . 'header_account.gif', HEADER_TITLE_MY_ACCOUNT) . '</a>';
  $template['urlmyaccountlogo'] = tep_href_link(FILENAME_ACCOUNT, '', 'SSL');

  $template['cartlogo'] = '<a href="' . tep_href_link(FILENAME_SHOPPING_CART) . '">' . tep_image(DIR_WS_IMAGES . 'header_cart.gif', HEADER_TITLE_CART_CONTENTS) . '</a>';
  $template['urlcartlogo'] = tep_href_link(FILENAME_SHOPPING_CART);

  $template['checkoutlogo'] = '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '">' . tep_image(DIR_WS_IMAGES . 'header_checkout.gif', HEADER_TITLE_CHECKOUT) . '</a>';
  $template['urlcheckoutlogo'] = tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL');

  $template['breadcrumbs'] = $breadcrumb->trail(' &raquo; ');

  if (tep_session_is_registered('customer_id')) {
  
    $template['myaccount'] = '<a href=' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . ' class="headerNavigation">' . HEADER_TITLE_MY_ACCOUNT . '</a>';
    $template['urlmyaccount'] = tep_href_link(FILENAME_ACCOUNT, '', 'SSL');
    $template['logoff'] = '<a href=' . tep_href_link(FILENAME_LOGOFF, '', 'SSL')  . ' class="headerNavigation">' . HEADER_TITLE_LOGOFF . '</a>';
    $template['urllogoff'] = tep_href_link(FILENAME_LOGOFF, '', 'SSL');
  // $template['myaccountlogoff'] =  $template['myaccount'] . " | " . $template['logoff'];
    $template['myaccountlogoff'] =  $template['myaccount'] ;
    $template['createaccount'] = $template['logoff'];
  } else {
  	$template['createaccount'] = '<a href=' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL') . ' class="headerNavigation">' . HEADER_TITLE_CREATE_ACCOUNT . '</a>';
    $template['myaccount'] = '<a href=' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . ' class="headerNavigation">' . HEADER_TITLE_MY_ACCOUNT . '</a>';
    $template['urlmyaccount'] = tep_href_link(FILENAME_ACCOUNT, '', 'SSL');
    $template['logoff'] = '';
    $template['urllogoff'] = '';
    $template['myaccountlogoff'] = $template['myaccount'];
  }

  $template['cartcontents']    = '<a href=' . tep_href_link(FILENAME_SHOPPING_CART) . ' class="headerNavigation">' . HEADER_TITLE_CART_CONTENTS . '</a>';
  $template['urlcartcontents'] = '<a href=' . tep_href_link(FILENAME_SHOPPING_CART) . ' class="headerNavigation">' . HEADER_TITLE_CART_CONTENTS . '</a>';

  $template['carttotals'] = '<a href=' . tep_href_link(FILENAME_SHOPPING_CART) . ' class="headerNavigation">&euro; ' . $cart->show_total() . '</a>'; 
  
  $template['checkout'] = '<a href=' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . ' class="headerNavigation">' . HEADER_TITLE_CHECKOUT . '</a>';
  $template['urlcheckout'] = tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL');

/////////////////////////////////////////////
////// Create custom boxes
/////////////////////////////////////////////
  $template['categorybox'] = strip_unwanted_tags($sts_block['categorybox'], 'categorybox');
  $template['manufacturerbox'] = strip_unwanted_tags($sts_block['manufacturerbox'], 'manufacturerbox');
  $template['affiliatesbox'] = strip_unwanted_tags($sts_block['affiliatesbox'], 'affiliatesbox');
  $template['whatsnewbox'] = strip_unwanted_tags($sts_block['whatsnewbox'], 'whatsnewbox');
  $template['searchbox'] = strip_unwanted_tags($sts_block['searchbox'], 'searchbox');
  $template['searchbox'] .= '<script language="JavaScript" type="text/javascript">
  /*<![CDATA[*/
  //Attention!!! put always this code above the HTML code of your field!!!
  var oscSearchSuggest = new OSCFieldSuggest(\'txtSearch\', \'includes/search_suggest.xsl\', \'searchsuggest.php\');
  /*]]>*/
</script>';
  
  $template['informationbox'] = strip_unwanted_tags($sts_block['informationbox'], 'informationbox');
  $template['cartbox'] = strip_unwanted_tags($sts_block['cartbox'], 'cartbox');
  $template['maninfobox'] = strip_unwanted_tags($sts_block['maninfobox'], 'maninfobox');
  $template['orderhistorybox'] = strip_unwanted_tags($sts_block['orderhistorybox'], 'orderhistorybox');
  $template['bestsellersbox'] = strip_unwanted_tags($sts_block['bestsellersbox'], 'bestsellersbox');
  // aggiunta lg
  $template['accessoriesbox'] = strip_unwanted_tags($sts_block['accessoriesbox'], 'accessoriesbox');
  $template['specialfriendbox'] = strip_unwanted_tags($sts_block['specialfriendbox'], 'specialfriendbox');
  $template['specialsscroll'] = strip_unwanted_tags($sts_block['specialsscroll'], 'specialsscroll');
  $template['whats_new_scroll'] = strip_unwanted_tags($sts_block['whats_new_scroll'], 'whats_new_scroll');

  if (REVIEWS_ENALBED == 'true')
 		 $template['reviewsbox'] = strip_unwanted_tags($sts_block['reviewsbox'], 'reviewsbox');
 		 else  $template['reviewsbox'] = '';
  $template['languagebox'] = strip_unwanted_tags($sts_block['languagebox'], 'languagebox');
  $template['currenciesbox'] = strip_unwanted_tags($sts_block['currenciesbox'], 'currenciesbox');
  $template['content'] = strip_content_tags($sts_block['columnleft2columnright'], 'content');
  // Prepend any error/warning messages to $content
  if ($messageStack->size('header') > 0) {
    $template['content'] = $messageStack->output('header') . $template['content'];
  }

  $template['date'] = strftime(DATE_FORMAT_LONG);
  $template['numrequests'] = $counter_now . ' ' . FOOTER_TEXT_REQUESTS_SINCE . ' ' . $counter_startdate_formatted;
  $template['counter'] = $sts_block['counter'];
  $template['footer'] = $sts_block['footer'];
 
  // bestshopping vedi codice in sts_user_code.php
  $template['content'] .= $template['bestshopping'];
  
  // google conversion per gli acquisti vedi sts_user_code.php
  $template['content'] .= $template['google_acquisto'];
   
  
  if (GOOGLE_UA <> '')  // start of google analytics
  {
  $template['content'] .= "  <script type=\"text/javascript\">
	
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', '".GOOGLE_UA."']);
	  _gaq.push(['_trackPageview']);
	
	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

</script>"; 
} // end of google analytics


  // kelkooTD vedi codice in sts_user_code.php
  $template['content'] .= $template['kelkooTD'];
  
  
  $template['banner'] = $sts_block['banner'];
  $template['topmenubox'] = strip_unwanted_tags($sts_block['topmenu'], 'topmenu');
 
  
  
//  $template['footer'] .= '<script type="text/javascript"
//							src="http://tracker.bestshopping.com/bestshopping.js"></script>';
  
 /*      if(FACEBOOK_STATUSBAR == 'on')
		 	 {
		  	// facebook status bar start container
		  	$template_html = $sts_block['facebook_bar_main_container_start'] . $template_html;
		  		$template['banner'] .=  $sts_block['facebook_bar']; // status bar like facebook
		        $template['banner'] .= $sts_block['facebook_bar_main_container_end'];
		  
		 	 }		
  
*/
  
/////////////////////////////////////////////
////// Get Categories
/////////////////////////////////////////////

$get_categories_description_query = tep_db_query("SELECT categories_id  FROM " . TABLE_CATEGORIES . " where categories_status = '1'");
// Loop through each category (in each language) and create template variables for each name and path
while ($categories_description = tep_db_fetch_array($get_categories_description_query)) {
      $cPath_new = tep_get_path($categories_description['categories_id']);
      $path = substr($cPath_new, 6); // Strip off the "cPath=" from string
      
      $catquery = tep_db_query("select categories_name from " .TABLE_CATEGORIES_DESCRIPTION . " where categories_id ='" . $categories_description['categories_id'] . "'");
      
      while ($catarray = tep_db_fetch_array($catquery))
      {
      $catname = $catarray['categories_name'];
      $catname = str_replace(" ", "_", $catname); // Replace Spaces in Category Name with Underscores

      $template["cat_" . $catname] = tep_href_link(FILENAME_DEFAULT, $cPath_new);
      $template["urlcat_" . $catname] = tep_href_link(FILENAME_DEFAULT, $cPath_new);
      $template["cat_" . $path] = tep_href_link(FILENAME_DEFAULT, $cPath_new);
      $template["urlcat_" . $path] = tep_href_link(FILENAME_DEFAULT, $cPath_new);
      }
      // print "<b>template[" . $categories_description['categories_name'] . "]=" . $template[$categories_description['categories_name']] . "<br>template[" . $path . "]=" . $template[$path] . "</b>";
}

/////////////////////////////////////////////
////// Display Template HTML
/////////////////////////////////////////////

  // Sort array by string length, so that longer strings are replaced first
  uksort($template, "sortbykeylength");

  // Manually replace the <!--$headcontent--> if present
    $template_html = str_replace('<!--$headcontent-->', $template['headcontent'], $template_html);

  // Automatically replace all the other template variables
  foreach ($template as $key=>$value) {
    $template_html = str_replace('$' . $key, $value, $template_html);
  }  
  
  if ($display_template_output == 1) {
//	if (TURBO == 'on'  && (!isset($customer_id)) && ( (strstr($PHP_SELF,"index")) || (strstr($PHP_SELF,"product_info"))  ))
if ($cache_enabled == 'true')
  	{

				      	    // open the cache file for writing
					       $fp = fopen($cachefile, 'w'); 

					       // save the contents of output buffer to the file
						    fwrite($fp, $template_html);
							// close the file
					        fclose($fp); 
			
				}
  	    echo $template_html;
  	}


/////////////////////////////////////////////
////// Display HTML
/////////////////////////////////////////////
 if ($display_normal_output == 1) {
  echo $sts_block['applicationtop2header'];
 flush(); // mando l'output intanto al browser
  echo $sts_block['header'];
 flush(); // mando l'output intanto al browser

  echo $sts_block['header2columnleft'];
 flush(); // mando l'output intanto al browser
  // print column_left stuff
  echo $sts_block['categorybox'];
   flush(); // mando l'output intanto al browser
  echo $sts_block['manufacturerbox'];
   flush(); // mando l'output intanto al browser
  echo $sts_block['affiliatesbox'];
   flush(); // mando l'output intanto al browser
  echo $sts_block['whatsnewbox'];
   flush(); // mando l'output intanto al browser
  echo $sts_block['searchbox'];
   flush(); // mando l'output intanto al browser
  echo $sts_block['informationbox'];
   flush(); // mando l'output intanto al browser
  
  echo $sts_block['columnleft2columnright'];
   flush(); // mando l'output intanto al browser

  // print column_right stuff
  echo $sts_block['cartbox'];
   flush(); // mando l'output intanto al browser
  echo $sts_block['maninfobox'];
   flush(); // mando l'output intanto al browser
  echo $sts_block['orderhistorybox'];
   flush(); // mando l'output intanto al browser
  echo $sts_block['bestsellersbox'];
   flush(); // mando l'output intanto al browser
  echo $sts_block['accessoriesbox'];
   flush(); // mando l'output intanto al browser 

    echo $sts_block['specialsscroll'];
   flush(); // mando l'output intanto al browser 
   
   echo $sts_block['whats_new_scroll'];  
   flush(); // mando l'output intanto al browser 
  echo $sts_block['specialfriendbox'];
   flush(); // mando l'output intanto al browser
  echo $sts_block['reviewsbox'];
   flush(); // mando l'output intanto al browser
  echo $sts_block['languagebox'];
   flush(); // mando l'output intanto al browser
  echo $sts_block['currenciesbox'];
   flush(); // mando l'output intanto al browser
  echo $sts_block['columnright2footer'];

  // print footer
  
  echo $sts_block['content'];
   flush(); // mando l'output intanto al browser
  echo $sts_block['counter'];
   flush(); // mando l'output intanto al browser
 
  

  

  echo $sts_block['footer'];
   flush(); // mando l'output intanto al browser
  echo $sts_block['banner'];
   flush(); // mando l'output intanto al browser
  
 }
/////////////////////////////////////////////
////// End Display HTML
/////////////////////////////////////////////

 if ($display_debugging_output == 1) {
  // Print Debugging Info
  print "\n<pre><hr>\n";
  print "STS_VERSION=[" . $sts_version . "]<br>\n";
  print "OSC_VERSION=[$sts_osc_version]\n";
  print "STS_TEMPLATE=[" . $sts_template_file . "]<hr>\n";
  // Replace $variable names in $sts_block_html_* with variables from the $template array
  foreach ($sts_block as $key=>$value) {
    print "<b>\$sts_block['$key']</b><hr>" . htmlspecialchars($value) . "<hr>\n";
  }

  foreach ($template as $key=>$value) {
    print "<b>\$template['$key']</b><hr>" . htmlspecialchars($value) . "<hr>\n";
  }

 }
 
 
 

 if ($display_normal_output == 1) {
  echo $sts_block['footer2applicationbottom'];
 }

// STRIP_UNWANTED_TAGS() - Remove leading and trailing <tr><td> from strings
function strip_unwanted_tags($tmpstr, $commentlabel) {
  // Now lets remove the <tr><td> that the require puts in front of the tableBox
  $tablestart = strpos($tmpstr, "<table");

  // If empty, return nothing
  if ($tablestart < 1) {
  	return  "\n<!-- start $commentlabel //-->\n$tmpstr\n<!-- end $commentlabel //-->\n";
  }

  $tmpstr = substr($tmpstr, $tablestart); // strip off stuff before <table>

  // Now lets remove the </td></tr> at the end of the tableBox output
  // strrpos only works for chars, not strings, so we'll cheat and reverse the string and then use strpos
  $tmpstr = strrev($tmpstr);

  $tableend = strpos($tmpstr, strrev("</table>"), 1);
  $tmpstr = substr($tmpstr, $tableend);  // strip off stuff after </table>

  // Now let's un-reverse it
  $tmpstr = strrev($tmpstr);

  // print "<hr>After cleaning tmpstr:" . strlen($tmpstr) . ": FULL=[".  htmlspecialchars($tmpstr) . "]<hr>\n";
  return  "\n<!-- start $commentlabel //-->\n$tmpstr\n<!-- end $commentlabel //-->\n";
}


// STRIP_CONTENT_TAGS() - Remove text before "body_text" and after "body_text_eof"
function strip_content_tags($tmpstr, $commentlabel) {
  // Now lets remove the <tr><td> that the require puts in front of the tableBox
  $tablestart = strpos($tmpstr, "<table");
  $formstart = strpos($tmpstr, "<form");

  // If there is a <form> tag before the <table> tag, keep it
  if ($formstart !== false and $formstart < $tablestart) {
     $tablestart = $formstart;
     $formfirst = true;
  }

  // If empty, return nothing
  if ($tablestart < 1) {
        return  "<!-- start $commentlabel //-->\n$tmpstr\n<!-- end $commentlabel //-->";
  }

  
  $tmpstr = substr($tmpstr, $tablestart); // strip off stuff before <table>

  // Now lets remove the </td></tr> at the end of the tableBox output
  // strrpos only works for chars, not strings, so we'll cheat and reverse the string and then use strpos
  $tmpstr = strrev($tmpstr);

  if ($formfirst == true) {
    $tableend = strpos($tmpstr, strrev("</form>"), 1);
  } else {
    $tableend = strpos($tmpstr, strrev("</table>"), 1);
  } 

  $tmpstr = substr($tmpstr, $tableend);  // strip off stuff after <!-- body_text_eof //-->

  // Now let's un-reverse it
  $tmpstr = strrev($tmpstr);

  // print "<hr>After cleaning tmpstr:" . strlen($tmpstr) . ": FULL=[".  htmlspecialchars($tmpstr) . "]<hr>\n";
  return  "<!-- start $commentlabel //-->\n$tmpstr\n<!-- end $commentlabel //-->";
}


function get_javascript($tmpstr, $commentlabel) {
  // Now lets remove the <tr><td> that the require puts in front of the tableBox
  $tablestart = strpos($tmpstr, "<script");

  // If empty, return nothing
  if ($tablestart === false) {
  	return  "\n<!-- start $commentlabel //-->\n\n<!-- end $commentlabel //-->\n";
  }

  $tmpstr = substr($tmpstr, $tablestart); // strip off stuff before <table>

  // Now lets remove the </td></tr> at the end of the tableBox output
  // strrpos only works for chars, not strings, so we'll cheat and reverse the string and then use strpos
  $tmpstr = strrev($tmpstr);

  $tableend = strpos($tmpstr, strrev("</script>"), 1);
  $tmpstr = substr($tmpstr, $tableend);  // strip off stuff after </table>

  // Now let's un-reverse it
  $tmpstr = strrev($tmpstr);

  // print "<hr>After cleaning tmpstr:" . strlen($tmpstr) . ": FULL=[".  htmlspecialchars($tmpstr) . "]<hr>\n";
  return  "\n<!-- start $commentlabel //-->\n$tmpstr\n<!-- end $commentlabel //-->\n";
}

// Return the value between $startstr and $endstr in $tmpstr
function str_between($tmpstr, $startstr, $endstr) {
  $startpos = strpos($tmpstr, $startstr);

  // If empty, return nothing
  if ($startpos === false) {
        return  "";
  }

  $tmpstr = substr($tmpstr, $startpos + strlen($startstr)); // strip off stuff before $start

  // Now lets remove the </td></tr> at the end of the tableBox output
  // strrpos only works for chars, not strings, so we'll cheat and reverse the string and then use strpos
  $tmpstr = strrev($tmpstr);

  $endpos = strpos($tmpstr, strrev($endstr), 1);

  $tmpstr = substr($tmpstr, $endpos + strlen($endstr));  // strip off stuff after </table>

  // Now let's un-reverse it
  $tmpstr = strrev($tmpstr);

  return  $tmpstr;
}

function sortbykeylength($a,$b) {
  $alen = strlen($a);
  $blen = strlen($b);
  if ($alen == $blen) $r = 0;
  if ($alen < $blen) $r = 1;
  if ($alen > $blen) $r = -1;
  return $r;
}

?>
