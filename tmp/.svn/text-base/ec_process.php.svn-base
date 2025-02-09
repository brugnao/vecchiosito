<?php
/*
	Copyright (c) 2005 Mr. Brian Burton - brian@dynamoeffects.com

     Since an EC transaction is a two step process, this script

	Released under the GNU General Public License

	Copyright (c) 2007 SensioLabs - gregoire.hubert@sensio.com
*/


/**
 * the requires
 */
require('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_EC_PROCESS);
require(DIR_WS_CLASSES . 'payment.php');
/**
 * unset any paypal error so we can collect cleanly a new one
 */
if (tep_session_is_registered('paypal_error')) tep_session_unregister('paypal_error');
/**
 * see if we were sent a request to clear the session
 * for paypal
 */
if (isset($HTTP_GET_VARS['clearSess'])) {
    /**
     * unset the paypal information
     */
    if(array_key_exists('paypal_ec_token', $_SESSION)){
        unset($_SESSION['paypal_ec_token']);
    }
    tep_session_unregister('paypal_ec_temp');
	tep_session_unregister('paypal_ec_token');
	tep_session_unregister('paypal_ec_payer_id');
	tep_session_unregister('paypal_ec_payer_info');
}

/**
 * Is there a product on the command line ?
 * MODIFICATION WPP SENSIO START
 */
if (isset($HTTP_GET_VARS['products_id']))
{
  $cart = new shoppingCart();
  $cart->reset();
  $cart->add_cart($HTTP_GET_VARS['products_id']);
}
// MODIFICATION WPP SENSIO END
/**
 * see if paypal_wpp is enabled
 */
if(tep_paypal_wpp_enabled()){
    /**
  	 * init the payment object for paypal_wpp
  	 */
    $payment_modules = new payment('paypal_wpp');
    /**
     * set the payment, if their hitting ec_process.php then we
     * know the payment method selected right now.
     */
    tep_session_register('payment');
    $_SESSION['payment'] = 'paypal_wpp';
    /**
     * check to see if we have a token set back from Paypal
     */
    if(!tep_session_is_registered('paypal_ec_token')) {
    	/**
    	 * This push on the first step in the paypal wpp payment
    	 * module.  We have not went to paypal's website yet in
    	 * order to grab a token at this time.  This will send the
    	 * user on to paypals website for EC
    	 */
    	$payment_modules->ec_step1();
    }else{
      	/**
      	 * This will push on the second step of the paypal wpp
      	 * payment module, as we already have a paypal express
      	 * checkout token at this point.
      	 */
    	$payment_modules->ec_step2();
    }
}
?>
<html>
Processing...
</html>