<?php
/*
  PayPal module for osCommerce, Open Source E-Commerce Solutions
  Author: Riccardo Roscilli
  email: info@oscommerce.it
  
*/


  class ppec {
    var $code, $title, $description, $enabled, $businessid, $apiusername, $apipassword, $apisignature, $purl;

// class constructor
    function ppec() {
      global $order;

      $this->code = 'ppec';
    
      $this->title = MODULE_PAYMENT_PPEC_TEXT_TITLE .  '<br>
      <!-- PayPal Logo --><table border="0" cellpadding="0" cellspacing="0" align="left"><tr><td align="left"><img  src="http://images.paypal.com/it_IT/i/bnr/paypal_mrb_banner.gif" border="0" alt="PayPal" width="200"></td></tr></table><!-- PayPal Logo -->';
      $this->description = MODULE_PAYMENT_PPEC_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_PPEC_SORT_ORDER;
      $this->apiusername = MODULE_PAYMENT_PPEC_USERNAME;
      $this->apipassword = MODULE_PAYMENT_PPEC_PASSWORD;
      $this->apisignature = MODULE_PAYMENT_PPEC_SIGNATURE;
      $this->businessid = MODULE_PAYMENT_PPEC_ID;
      $this->alertmail = MODULE_PAYMENT_PPEC_MAIL;
      $this->enabled = ((MODULE_PAYMENT_PPEC_STATUS == 'True') ? true : false);

      if ((int)MODULE_PAYMENT_PPEC_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_PPEC_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();
          
      global $purl;
      $doppec = '1';

      $this->form_action_url = tep_href_link('ppeb.php', 'doppec='.$doppec, 'SSL');
    }

// class methods
    function update_status() {
      global $order;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_PPEC_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_PPEC_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
        while ($check = tep_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check['zone_id'] == $order->billing['zone_id']) {
            $check_flag = true;
            break;
          }
        }

        if ($check_flag == false) {
          $this->enabled = false;
        }
      }
    }

    function javascript_validation() {
      return false;
    }

    function selection() {
      return array('id' => $this->code,
                   'module' => $this->title);
    }

    function pre_confirmation_check() {
      return false;
    }

    function confirmation() {
      return false;
    }

    function process_button() {
      global $order, $currencies, $currency;

      if (MODULE_PAYMENT_PPEC_CURRENCY == 'Selected Currency') {
        $my_currency = $currency;
      } else {
        $my_currency = substr(MODULE_PAYMENT_PPEC_CURRENCY, 5);
      }
      if (!in_array($my_currency, array('CAD', 'EUR', 'GBP', 'JPY', 'USD'))) {
        $my_currency = 'USD';
      }

      return false;
    }

    function before_process() {
      return false;
    }

    function after_process() {
      return false;
    }

    function output_error() {
      return false;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PPEC_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) 
                                                     values ('PayPal Express Checkout activation', 'MODULE_PAYMENT_PPEC_STATUS', 'True', 'Do you want to activate PayPal Express Checkout?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) 

                                                     values ('Order number', 'MODULE_PAYMENT_PPEC_SORT_ORDER', '0', 'Enter an order number of display:', '6', '2', now())");     

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) 

                                                     values ('E-Mail Address', 'MODULE_PAYMENT_PPEC_ID', '', 'Enter the E-Mail address of your PayPal account:', '6', '3', now())");

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) 

                                                     values ('Transaction Currency', 'MODULE_PAYMENT_PPEC_CURRENCY', 'Selected Currency', 'The currency to use for credit card transactions', '6', '4', 'tep_cfg_select_option(array(\'Selected Currency\',\'Only USD\',\'Only CAD\',\'Only EUR\',\'Only GBP\',\'Only JPY\'), ', now())");

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) 

                                                     values ('Transaction Server', 'MODULE_PAYMENT_PPEC_TRANSACTION_SERVER', 'PayPal Live Server', 'Select the server to use to process transactions:', '6', '5', 'tep_cfg_select_option(array(\'PayPal Live Server\', \'PayPal Sandbox Server (for simulated transactions)\'), ', now())");

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) 

                                                     values ('Mark or Mark + Express', 'MODULE_PAYMENT_PPEC_ONLY_MARK', 'Payment Version', 'Select if you want only mark payment (not express) or both:', '6', '5', 'tep_cfg_select_option(array(\'Mark\', \'Express\'), ', now())");
      
      
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('API Username', 'MODULE_PAYMENT_PPEC_USERNAME', 'info_api1.promowebstudio.net', 'Enter the API username of your PayPal account:', '6', '4', now())");

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('API Password', 'MODULE_PAYMENT_PPEC_PASSWORD', 'ELSN2VPTGL5UDXD6', 'Enter the API password of your PayPal account:', '6', '4', now())");

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('API Signature', 'MODULE_PAYMENT_PPEC_SIGNATURE', 'AyT-1w-5Oeg32BnlA7id9d1W4Vf4A8E3ayYEBzrUe8gG6GXn1gUW1ymd', 'Enter the API signature of your PayPal account:', '6', '4', now())");

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Notification address email (optional)', 'MODULE_PAYMENT_PPEC_MAIL', '', 'The email address where you wish to receive transaction notifications:', '6', '4', now())");

	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('SSL Certificate (optional)', 'MODULE_PAYMENT_PPEC_CERT_FILE', '', 'Type in the API certificate filename(extension included)<br>It must be located in the following path: ppec/ppec_cert/' , '6', '2', now())");

	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Logo image (optional)', 'MODULE_PAYMENT_PPEC_LOGO', '', 'Type in the URL logo<br>the URL must be HTTPS, and the maximum size is 750 pixels wide by 90 pixels high' , '6', '2', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      //return array('MODULE_PAYMENT_PPEC_STATUS', 'MODULE_PAYMENT_PPEC_ID', 'MODULE_PAYMENT_PPEC_CURRENCY', 'MODULE_PAYMENT_PPEC_TRANSACTION_SERVER', 'MODULE_PAYMENT_PPEC_ZONE', 'MODULE_PAYMENT_PPEC_ORDER_STATUS_ID', 'MODULE_PAYMENT_PPEC_SORT_ORDER');

      return array('MODULE_PAYMENT_PPEC_STATUS', 'MODULE_PAYMENT_PPEC_SORT_ORDER', 'MODULE_PAYMENT_PPEC_ID', 'MODULE_PAYMENT_PPEC_TRANSACTION_SERVER', 'MODULE_PAYMENT_PPEC_ONLY_MARK', 'MODULE_PAYMENT_PPEC_USERNAME', 'MODULE_PAYMENT_PPEC_PASSWORD', 'MODULE_PAYMENT_PPEC_SIGNATURE', 'MODULE_PAYMENT_PPEC_MAIL', 'MODULE_PAYMENT_PPEC_CERT_FILE', 'MODULE_PAYMENT_PPEC_LOGO');
    }
  }
?>