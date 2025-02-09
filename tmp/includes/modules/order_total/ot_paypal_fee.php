<?php
/*
  $Id: ot_paypal_fee.php,v 1.00 2004/08/13 17:02:00 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

 
  
*/
/********************************************************************
*
*                    All rights reserved. 
*
* This program is free software licensed under the GNU General Public License (GPL).
*
*    This program is free software; you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation; either version 2 of the License, or
*    (at your option) any later version.
*
*    This program is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with this program; if not, write to the Free Software
*    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307
*    USA
*
*********************************************************************/

  class ot_paypal_fee {
    var $title, $output;

    function ot_paypal_fee() {
      $this->code = 'ot_paypal_fee';
      $this->title = MODULE_ORDER_TOTAL_PAYPAL_TITLE;
      $this->description = MODULE_ORDER_TOTAL_PAYPAL_DESCRIPTION;
      $this->enabled = ((MODULE_ORDER_TOTAL_PAYPAL_STATUS == 'true') ? true : false);
      $this->sort_order = MODULE_ORDER_TOTAL_PAYPAL_SORT_ORDER;
      $this->output = array();
    }

    function process() {
      global $order, $currencies, $paypal_fee;

      if (MODULE_ORDER_TOTAL_PAYPAL_STATUS == 'true') {
        //check if payment method is paypal. If yes, add fee.
        if ( ($GLOBALS['payment'] == 'paypal_ipn') || ($GLOBALS['payment'] == 'ppec') || ($GLOBALS['payment'] == 'paypal_pro_standard') ) {
		$paypal_fee = tep_round(((MODULE_ORDER_TOTAL_PAYPAL_FEE/100) * $order->info['total']), $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
            $order->info['total'] += $paypal_fee;
            $this->output[] = array('title' => $this->title . ' (' . MODULE_ORDER_TOTAL_PAYPAL_FEE . '%):',
                                    'text' => $currencies->format($paypal_fee, true,  $order->info['currency'], $order->info['currency_value']),
                                    'value' => $paypal_fee);
        } 
      }
	}

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_PAYPAL_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }

      return $this->_check;
    }

    function keys() {
      return array('MODULE_ORDER_TOTAL_PAYPAL_STATUS', 'MODULE_ORDER_TOTAL_PAYPAL_SORT_ORDER', 'MODULE_ORDER_TOTAL_PAYPAL_FEE');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display PAYPAL FEE', 'MODULE_ORDER_TOTAL_PAYPAL_STATUS', 'true', 'Do you want this module to display?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_TOTAL_PAYPAL_SORT_ORDER', '4', 'Sort order of display.', '6', '2', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('PAYPAL Fee (%)', 'MODULE_ORDER_TOTAL_PAYPAL_FEE', '1', 'Your PayPal fee in percent (%)', '6', '3', now())");
    }
    

    function remove() {
      $keys = '';
      $keys_array = $this->keys();
      $keys_size = sizeof($keys_array);
      for ($i=0; $i<$keys_size; $i++) {
        $keys .= "'" . $keys_array[$i] . "',";
      }
      $keys = substr($keys, 0, -1);

      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in (" . $keys . ")");
    }
  }
?>
