<?php
/*
 * @filename:	easypopulate_configuration.php
 * @version:	1.00
 * @project:	PWS
 *
 * @author:		Riccardo Roscilli <info@oscommerce.it>
 * @created:	28/mar/08
 * @modified:	28/mar/08 15:08:16
 *
 * @copyright:	2006-2008	Riccardo Roscilli @ PWS
 *
 * @desc:	Configurazione standard di easypopulate
 *
 * @TODO:
 */
//
//*******************************
//*******************************
// C O N F I G U R A T I O N
// V A R I A B L E S
//*******************************
//*******************************
// Current EP Version
define ('EP_CURRENT_VERSION', '2.76g-MS2');


//////////////////////////////////////////////////////
// *** Show all these settings on EP main page ***
// use this to debug your settings. Copy the settings
// to your post on the forum if you need help.
//////////////////////////////////////////////////////
define ('EP_SHOW_EP_SETTINGS', true); // default is: false


// **** Temp directory ****
/* ////////////////////////////////////////////////////////////////////////
 //
 // *IF* you changed your directory structure from stock and do not
 // have /catalog/temp/, then you'll need to change this accordingly.
 //
 // *IF* your shop is in the default /catalog/ installation directory
 // on your website, skip this Temp Directory settings info.
 //
 ///////////////////////////////////////////////////////////////////////////

 CREATING THE TEMP DIRECTORY

 If your shop is in the root of your public site ( /home/myaccount/public_html/index.php ),
 you should create a folder called temp from the root of your web space so that the
 full path looks like this: /home/myaccount/public_html/temp/

 Then you must set the permissions to 777. If you don't know how, ask your host.


 THE DIR_FS_DOCUMENT_ROOT SETTING

 DIR_FS_DOCUMENT_ROOT is set in your /catalog/admin/includes/configure.php
 You should look at the setting DIR_FS_DOCUMENT_ROOT setting.
 if it looks like this (recommended, but doesn't always work):

 define ('DIR_FS_DOCUMENT_ROOT', $DOCUMENT_ROOT);

 then leave it alone. If it looks like this:

 define ('DIR_FS_DOCUMENT_ROOT', '/home/myaccount/public_html');

 ask your host if the "/home/myaccount/public_html" portion points to your public
 web space and is correct. Whether you add the trailing slash on the
 path or not doesn't matter to this contrib, as long as you make the
 right choice on the following setting. The best thing is to leave it
 alone as long as your host can confirm it is correct and everything else
 is working fine. Having said that, NO trailing slash is technically correct.



 THE DIR_WS_CATALOG & DIR_FS_CATALOG SETTINGS

 DIR_WS_CATALOG & DIR_FS_CATALOG are set in your /catalog/admin/includes/configure.php
 They may look like this if your shop is in the root of your web space.
 If you have something different, don't just change it to this.
 There is probably a good reason. I'm providing this as a reference
 to you-all. The DIR_FS_DOCUMENT_ROOT, the DIR_WS_CATALOG, and the
 DIR_FS_CATALOG settings all combine to create the temp location below.

 define('DIR_WS_CATALOG', '/');
 define('DIR_FS_CATALOG', DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG);



 THIS EP_TEMP_DIRECTORY SETTING

 Next, the following setting should set so that the DIR_FS_CATALOG setting
 plus this following setting makes a correct full path to your temporary
 location, like this: /home/myaccount/public_html/temp/

 if /home/myaccount/public_html/temp/ is the correct full path to your temp
 location, then:

 define ('EP_TEMP_DIRECTORY', DIR_FS_CATALOG . 'temp/');

 is the correct setting here.  Wow, I really hope this stops the forum traffic about this !!

 ////////////////////////////////////////////////////////////////////////// */
// **** Temp directory ****
define ('EP_TEMP_DIRECTORY', DIR_FS_CATALOG . 'temp/');


//**** File Splitting Configuration ****
// we attempt to set the timeout limit longer for this script to avoid having to split the files
// NOTE:  If your server is running in safe mode, this setting cannot override the timeout set in php.ini
// uncomment this if you are not on a safe mode server and you are getting timeouts
// set_time_limit(330);

// if you are splitting files, this will set the maximum number of records to put in each file.
// if you set your php.ini to a long time, you can make this number bigger
define ('EP_SPLIT_MAX_RECORDS', 300);  // default, seems to work for most people.  Reduce if you hit timeouts
//define ('EP_SPLIT_MAX_RECORDS', 4); // for testing


//**** Image Defaulting ****
// set them to your own default "We don't have any picture" gif
//define ('EP_DEFAULT_IMAGE_MANUFACTURER', 'no_image_manufacturer.gif');
//define ('EP_DEFAULT_IMAGE_PRODUCT', 'no_image_product.gif');
//define ('EP_DEFAULT_IMAGE_CATEGORY', 'no_image_category.gif');

// or let them get set to nothing
define ('EP_DEFAULT_IMAGE_MANUFACTURER', '');
define ('EP_DEFAULT_IMAGE_PRODUCT', '');
define ('EP_DEFAULT_IMAGE_CATEGORY', '');


//**** Status Field Setting ****
// Set the v_status field to "Inactive" if you want the status=0 in the system
define ('EP_TEXT_ACTIVE', 'Active');
define ('EP_TEXT_INACTIVE', 'Inactive');

// Set the v_status field to "Delete" if you want to remove the item from the system
define ('EP_DELETE_IT', 'Delete');


// If zero_qty_inactive is true, then items with zero qty will automatically be inactive in the store.
define ('EP_INACTIVATE_ZERO_QUANTITIES', false);  // default is false


//**** Size of products_model in products table ****
// set this to the size of your model number field in the db.  We check to make
// sure all models are no longer than this value. this prevents the database from
// getting fubared.  Just making this number bigger won't help your database!  They must match!
// If you increase the Model Number size, you must increase the size of the field
// in the database. Use a SQL tool like phpMyAdmin (see your host) and change the
// "products_model" field of the "products" table in your osCommerce Database.
if (isset($GLOBALS['pws_engine'])){
	define ('EP_MODEL_NUMBER_SIZE',$pws_engine->fieldLength('products_model',TABLE_PRODUCTS));
}else{
	define ('EP_MODEL_NUMBER_SIZE', 12); // default is 12
}

//**** Price includes tax? ****
// Set the EP_PRICE_WITH_TAX to
// false if you want the price that is exported to be the same value as stored in the database (no tax added).
// true if you want the tax to be added to the export price and subtracted from the import price.
define ('EP_PRICE_WITH_TAX', false);  // default is false


//**** Price calculation precision ****
// NOTE: when entering into the database all prices will be converted to 4 decimal places.
define ('EP_PRECISION', 2);  // default is 2


// **** Quote -> Escape character conversion ****
// If you have extensive html in your descriptions and it's getting mangled on upload, turn this off
// set to true = replace quotes with escape characters
// set to false = no quote replacement
define ('EP_REPLACE_QUOTES', true);  // default is false


// **** Field Separator ****
// change this if you can't use the default of tabs
// Tab is the default, comma and semicolon are commonly supported by various progs
// Remember, if your descriptions contain this character, you will confuse EP!
// if EP_EXCEL_SAFE_OUTPUT if false (below) you must make EP_PRESERVE_TABS_CR_LF false also.

$ep_separator = ";"; // tab is default
//$ep_separator = ',';  // comma
//$ep_separator = ';';  // semi-colon
//$ep_separator = '~';  // tilde
//$ep_separator = '*';  // splat


// *** Excel safe output ***
// this setting will supersede the previous $ep_separator setting and create a file
// that excel will import without spanning cells from embedded commas or tabs in your products.
// if EP_EXCEL_SAFE_OUTPUT if false (below) you must make EP_PRESERVE_TABS_CR_LF false also.
define ('EP_EXCEL_SAFE_OUTPUT', true); // default is: true

if (EP_EXCEL_SAFE_OUTPUT == true) {
	if ($language == 'english') {
		$ep_separator = ',';  // comma
	} elseif ($language == 'german') {
		$ep_separator = ';';  // semi-colon
	} elseif ($language == 'italian') {
		$ep_separator = ';';  // semi-colon
	} elseif ($language == 'french') {
		$ep_separator = ';';  // semi-colon
	} else {
		$ep_separator = ',';  // comma  // default for all others.
	}
}
// $ep_separator=EP_SEPARATOR;
if ($ep_separator=='tab'){
	$ep_separator="\t";
}

// Conversione dei valori in virgola mobile al formato excel italiano x,y invece di x.y
// e viceversa
define('EP_CONVERT_EXCEL_FLOATS',true);

// if EP_EXCEL_SAFE_OUTPUT if true (above) there is an alternative line parsing routine
//  provided by Maynard that will use a manual php approach.  There is a bug in some
// PHP versions that may require you to use this routine.  This should also provide proper
// parsing when quotes are used within a string. I suspect this should also resolve an issue
// recently reported in which characters with a german "Umlaute" like ������ at the Beginning
// of some text, they will disappear when importing some csv-file, reported by TurboTB.
define ('EP_EXCEL_SAFE_OUTPUT_ALT_PARCE', false); // default is: false


// *** Preserve Tabs, Carriage returns and Line feeds ***
// this setting will preserve the special chars that can cause problems in
// a text based output. When used with EP_EXCEL_SAFE_OUTPUT, it will safely
// preserve these elements in the export and import.
define ('EP_PRESERVE_TABS_CR_LF', true); // default is: false


// **** Max Category Levels ****
// change this if you need more or fewer categories.
// set this to the maximum depth of your categories.
define ('EP_MAX_CATEGORIES', 7); // default is 7


// VJ product attributes begin
// **** Product Attributes ****
// change this to false, if do not want to download product attributes
define ('EP_PRODUCTS_WITH_ATTRIBUTES', false);  // default is true

// change this to true, if you use QTYpro and want to set attributes stock with EP.
define ('EP_PRODUCTS_ATTRIBUTES_STOCK', false); // default is false

// change this if you want to download only selected product options (attributes).
// If you have a lot of product options, and your output file exceeds 256 columns,
// which is the max. limit MS Excel is able to handle, then load-up this array with
// attributes to skip when generating the export.
$attribute_options_select = '';
// $attribute_options_select = array('Size', 'Model'); // uncomment and fill with product options name you wish to download // comment this line, if you wish to download all product options
// VJ product attributes end


// ******************************************************************
// BEGIN Define Custom Fields for your products database
// ******************************************************************
// the following line is always left as is.
$custom_fields = array();
//
// The following setup will allow you to define any additional
// field into the "products" and "products_description" tables
// in your shop. If you have  installed a custom contribution
// that adds fields to these tables you may simply and easily add
// them to the EasyPopulate system.
//
// ********************
// ** products table **
// Lets say you have added a field to your "products" table called
// "products_upc". The header name in your import file will be
// called "v_products_upc".  Then below you will change the line
// that looks like this (without the comment double-slash at the beginning):
// $custom_fields[TABLE_PRODUCTS] = array(); // this line is used if you have no custom fields to import/export
//
$custom_fields[TABLE_PRODUCTS] = array(); // this line is used if you have no custom fields to import/export

$rs_products_fields = tep_db_query("SHOW COLUMNS FROM  " .TABLE_PRODUCTS);
while ($field = tep_db_fetch_array($rs_products_fields))
{
	if ($field['Field'] == 'products_icecat')
	{
		$custom_fields[TABLE_PRODUCTS] = array( 'products_icecat' => 'ICECAT' );
	}

}

// TO:
// $custom_fields[TABLE_PRODUCTS] = array( 'products_upc' => 'UPC' );
//
// If you have multiple fields this is what it would look like:
// $custom_fields[TABLE_PRODUCTS] = array( 'products_upc' => 'UPC', 'products_restock_quantity' => 'Restock' );
//
// ********************************
// ** products_description table **
// Lets say you have added a field to your "products_description" table called
// "products_short_description". The header name in your import file will be
// called "v_products_short_description_1" for English, "v_products_short_description_2" for German,
// "v_products_short_description_3" for Spanish. Other languages will vary. Be sure to use the
// langugage ID of the custom language you installed if it is other then the original
// 3 installed languages of osCommerce. If you are unsure what language ID you need to
// use, do a complete export and examine the file headers EasyPopulate produces.
//
// Then below you will change the line that looks like this (without the comment double-slash at the beginning):
// $custom_fields[TABLE_PRODUCTS_DESCRIPTION] = array(); // this line is used if you have no custom fields to import/export
//
// TO:
// $custom_fields[TABLE_PRODUCTS_DESCRIPTION] = array( 'products_short_description' => 'short' );
//
// If you have multiple fields this is what it would look like:
// $custom_fields[TABLE_PRODUCTS_DESCRIPTION] = array( 'products_short_description' => 'short', 'products_viewed' => 'Viewed' );
//
// the array format is: array( 'table_field_name' => 'Familiar Name' )
// the array key ('table_field_name') is always the exact name of the
// field in the table. The array value ('Familiar Name') is any text
// name that will be used in the custom EP export download checkbox.
//
// I believe this will only work for text/varchar and numeric field
// types.  If your custom field is a date/time or any other type, you
// may need to incorporate custom code to correctly import your data.
//


$custom_fields[TABLE_PRODUCTS_DESCRIPTION] = array(); // this line is used if you have no custom fields to import/export
if (isset($pws_engine)){
	if ($pws_engine->fieldExists('products_shopwindow',TABLE_PRODUCTS)){
		$custom_fields[TABLE_PRODUCTS]['products_shopwindow']='vetrina';
	}
}
//
// FINAL NOTE: this currently only works with the "products" & "products_description" table.
// If it works well and I don't get a plethora of problems reported,
// I may expand it to more tables. Feel free to make requests, but
// as always, only as me free time allows.
//
// ******************************************************************
// END Define Custom Fields for your products database
// ******************************************************************



// ****************************************
// Froogle configuration variables
// Here are some links regarding Bulk uploads
// http://www.google.com/base/attributes.html
// http://www.google.com/base/help/custom-attributes.html
// ****************************************

// **** Froogle product info page path ****
// We can't use the tep functions to create the link, because the links will point to the
// admin, since that's where we're at. So put the entire path to your product_info.php page here
define ('EP_FROOGLE_PRODUCT_INFO_PATH', HTTP_CATALOG_SERVER . DIR_WS_CATALOG . "product_info.php");

// **** Froogle product image path ****
// Set this to the path to your images directory
define ('EP_FROOGLE_IMAGE_PATH', HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES);

// **** Froogle - search engine friendly setting
// if your store has SEARCH ENGINE FRIENDLY URLS set, then turn this to true
// I did it this way because I'm having trouble with the code seeing the constants
// that are defined in other places.
define ('EP_FROOGLE_SEF_URLS', false);  // default is false

// **** Froogle Currency Setting
define ('EP_FROOGLE_CURRENCY', 'EUR');  // default is 'USD'

// ****************************************
// End: Froogle configuration variables
//


// ***********************************
// *** Other Contributions Support ***
// ***********************************

// More Pics 6 v1.3
define ('EP_MORE_PICS_6_SUPPORT', false);  // default is false
//
// Header Tags Controller Support v2.0
define ('EP_HTC_SUPPORT', false);  // default is false
//
// Separate Pricing Per Customer (SPPC)
define ('EP_SPPC_SUPPORT', false);  // default is false

// ///////////////////////////////////////////////////////////////////////////////
// The following items are not complete and untested. Experiment at your own risk.
// ///////////////////////////////////////////////////////////////////////////////

//
// Extra Fields Contribution (***UNTESTED AND MAY NOT BE FUNCTIONAL***)
if(file_exists(FILENAME_PRODUCTS_EXTRA_FIELDS))
{
	define ('EP_EXTRA_FIELDS_SUPPORT', true);  // default is false
}
else
define ('EP_EXTRA_FIELDS_SUPPORT', false);  // default is false
//
// Unknown Image Contrib (***UNTESTED AND MAY NOT BE FUNCTIONAL***)
define ('EP_UNKNOWN_ADD_IMAGES_SUPPORT', false);  // default is false


//*******************************
//*******************************
// E N D
// C O N F I G U R A T I O N
// V A R I A B L E S
//*******************************
//*******************************


?>