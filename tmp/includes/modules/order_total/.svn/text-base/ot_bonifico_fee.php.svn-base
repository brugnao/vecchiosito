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

  class ot_bonifico_fee {
    var $title, $output;

    function ot_bonifico_fee() {
      $this->code = 'ot_bonifico_fee';
      $this->title = MODULE_ORDER_TOTAL_BONIFICO_TITLE;
      $this->description = MODULE_ORDER_TOTAL_BONIFICO_DESCRIPTION;
      $this->enabled = ((MODULE_ORDER_TOTAL_BONIFICO_STATUS == 'true') ? true : false);
      $this->sort_order = MODULE_ORDER_TOTAL_BONIFICO_SORT_ORDER;
      $this->output = array();
    }

    function process() {
      global $order, $currencies, $bonifico_fee;

      if (MODULE_ORDER_TOTAL_BONIFICO_STATUS == 'true') {
        //check if payment method is bonifico. If yes, add fee.
        if ( ($GLOBALS['payment'] == 'bonifico') ) {
		$bonifico_fee = tep_round(((MODULE_ORDER_TOTAL_BONIFICO_FEE/100) * $order->info['total']), $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
            $order->info['total'] += $bonifico_fee;
            $this->output[] = array('title' => $this->title . ' (' . MODULE_ORDER_TOTAL_BONIFICO_FEE . '%):',
                                    'text' => $currencies->format($bonifico_fee, true,  $order->info['currency'], $order->info['currency_value']),
                                    'value' => $bonifico_fee);
        } 
      }
	}

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_BONIFICO_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }

      return $this->_check;
    }

    function keys() {
      return array('MODULE_ORDER_TOTAL_BONIFICO_STATUS', 'MODULE_ORDER_TOTAL_BONIFICO_SORT_ORDER', 'MODULE_ORDER_TOTAL_BONIFICO_FEE');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Commissione BONIFICO', 'MODULE_ORDER_TOTAL_BONIFICO_STATUS', 'true', 'Vuoi mostrare questo modulo?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Ordine di visualizzazione', 'MODULE_ORDER_TOTAL_BONIFICO_SORT_ORDER', '4', 'Ordine di visualizzazione', '6', '2', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Commissione BONIFICO (in %)', 'MODULE_ORDER_TOTAL_BONIFICO_FEE', '1', 'Commissione o sconto per il Bonifico, inserire il numero con - se sconto (es. -2 per sconto 2%)', '6', '3', now())");
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
