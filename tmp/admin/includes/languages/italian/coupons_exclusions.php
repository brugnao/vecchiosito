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

define('HEADING_TITLE', 'Esclusioni per il Coupon Sconto %s');
define('HEADING_TITLE_VIEW_MANUAL', 'Clicca qui per leggere il manuale Codici Coupon Sconto riguardo la modifica dei coupon.');
if( isset( $HTTP_GET_VARS['type'] ) && $HTTP_GET_VARS['type'] != '' ) {
	switch( $HTTP_GET_VARS['type'] ) {
		//category exclusions
		case 'categories':
			$heading_available = 'Questo coupon pu&ograve; essere applicato a queste categorie.';
			$heading_selected = 'Questo coupon <b>non</b> pu&ograve; essere applicato ai prodotti in queste categorie.';
			break;
		//end category exclusions
		//manufacturer exclusions
		case 'manufacturers':
			$heading_available = 'Questo coupon pu&ograve; essere applicato ai prodotti di questi produttori.';
			$heading_selected = 'Questo coupon <b>non</b> pu&ograve; essere applicato ai prodotti di questi produttori.';
			break;
		//end manufacturer exclusions
    //customer exclusions
		case 'customers':
			$heading_available = 'Questo coupon pu&ograve; essere utilizzato da questi clienti.';
			$heading_selected = 'Questo coupon <b>non</b> pu&ograve; essere utilizzato da questi clienti.';
			break;
		//end customer exclusions
//customer groups exclusions
		case 'customer_groups':
			$heading_available = 'Questo coupon pu&ograve; essere utilizzato da questi gruppi clienti.';
			$heading_selected = 'Questo coupon <b>non</b> pu&ograve; essere utilizzato da questi gruppi clienti.';
						break;
		//end customer group exclusions
			
			
		//product exclusions
		case 'products':
      $heading_available = 'Questo coupon pu&ograve; essere applicato a questi prodotti.';
			$heading_selected = 'Questo coupon <b>non</b> pu&ograve; essere applicato a questi prodotti.';
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

define('MESSAGE_DISCOUNT_COUPONS_EXCLUSIONS_SAVED', 'Salvate le nuove regole di esclusione.');

define('ERROR_DISCOUNT_COUPONS_NO_COUPON_CODE', 'Nessun coupon selezionato.' );
define('ERROR_DISCOUNT_COUPONS_INVALID_TYPE', 'Impossibile creare esclusioni di quel tipo.');
define('ERROR_DISCOUNT_COUPONS_SELECTED_LIST', 'C\'&egrave; stato un errore nel determinare il '.$HTTP_GET_VARS['type'].' gi&agrave; esclusi.');
define('ERROR_DISCOUNT_COUPONS_ALL_LIST', 'C\'&egrave; stato un errore nel determinare il '.$HTTP_GET_VARS['type'].' disponibile.');
define('ERROR_DISCOUNT_COUPONS_SAVE', 'Errore durante il salvataggio delle nuove regole di esclusione.');

?>
