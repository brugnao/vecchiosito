<?php
/*
 * coupons_exclusions.php
 * September 26, 2006
 * author: Kristen G. Thorson
 * ot_discount_coupon_codes version 3.0
 *
 *
 * Released under the GNU General Public License
 *
 */

define('HEADING_TITLE', 'Discount Coupons Exclusions for Coupon %s');
define('HEADING_TITLE_VIEW_MANUAL', 'Click here to read the Discount Coupon Codes manual for help editing coupons.');
if( isset( $HTTP_GET_VARS['type'] ) && $HTTP_GET_VARS['type'] != '' ) {
	switch( $HTTP_GET_VARS['type'] ) {
		//category exclusions
		case 'categories':
			$heading_available = 'This coupon may be applied to products in these categories.';
			$heading_selected = 'This coupon may <b>not</b> be applied to products in these categories.';
			break;
		//end category exclusions
		//manufacturer exclusions
		case 'manufacturers':
			$heading_available = 'This coupon may be applied to products assigned to these manufacturers.';
			$heading_selected = 'This coupon may <b>not</b> be applied to products assigned to these manufacturers.';
			break;
		//end manufacturer exclusions
    //customer exclusions
		case 'customers':
			$heading_available = 'This coupon may be used by these customers.';
			$heading_selected = 'This coupon may <b>not</b> be used by these customers.';
			break;
		//end customer exclusions
		//product exclusions
		case 'products':
      $heading_available = 'This coupon may be applied to these products.';
			$heading_selected = 'This coupon may <b>not</b> be applied to these products.';
			break;
		//end product exclusions
    //shipping zone exclusions
    case 'zones' :
      $heading_available = 'This coupon may be used in these shipping zones.';
      $heading_selected = 'This coupon may <b>not</b> be used in these shipping zones.';
      break;
    //end zone exclusions
	}
}
define('HEADING_AVAILABLE', $heading_available);
define('HEADING_SELECTED', $heading_selected);

define('MESSAGE_DISCOUNT_COUPONS_EXCLUSIONS_SAVED', 'New exclusion rules saved.');

define('ERROR_DISCOUNT_COUPONS_NO_COUPON_CODE', 'No coupon selected.' );
define('ERROR_DISCOUNT_COUPONS_INVALID_TYPE', 'Cannot create exclusions of that type.');
define('ERROR_DISCOUNT_COUPONS_SELECTED_LIST', 'There has been an error determining the already excluded '.$HTTP_GET_VARS['type'].'.');
define('ERROR_DISCOUNT_COUPONS_ALL_LIST', 'There has been an error determining the available '.$HTTP_GET_VARS['type'].'.');
define('ERROR_DISCOUNT_COUPONS_SAVE', 'Error saving new exclusion rules.');

?>
