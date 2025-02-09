<?php
/*
 $Id: ot_cod_fee.php,v 1.00 2002/11/30 17:02:00 harley_vb Exp $
 $Id: ot_cod_fee.php,v 1.10 2005/12/11 20:02:00 rroscilli Exp $


 Modificato per l'italia e i corrieri italiani da Riccardo Roscilli
 http://www.oscommerce.it

 osCommerce, Open Source E-Commerce Solutions
 http://www.oscommerce.com

 Copyright (c) 2002 osCommerce

 Copyright (c) 2005 osCommerce.it

 Released under the GNU General Public License
 */
 /********************************************************************
  *	Copyright (C) 2002 TheMedia, Dipl.-Ing Thomas Pl"nkers.
  *       http://www.themedia.at & http://www.oscommerce.at
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

class ot_cod_fee {
	var $title, $output;

	function ot_cod_fee() {
		$this->code = 'ot_cod_fee';
		$this->title = MODULE_ORDER_TOTAL_COD_TITLE;
		$this->description = MODULE_ORDER_TOTAL_COD_DESCRIPTION;
		$this->enabled = ((MODULE_ORDER_TOTAL_COD_STATUS == 'true') ? true : false);
		$this->sort_order = MODULE_ORDER_TOTAL_COD_SORT_ORDER;
		$this->tax_id = MODULE_ORDER_TOTAL_COD_TAX_CLASS;

		$this->output = array();
	}

	function process() {
		global $order, $currencies, $cod_cost, $cod_country, $shipping;

		if (MODULE_ORDER_TOTAL_COD_STATUS == 'true') {
			$tax = tep_get_tax_rate(MODULE_ORDER_TOTAL_COD_TAX_CLASS);
			$tax_description = tep_get_tax_description($this->tax_id, $order->delivery['country']['id'], $order->delivery['zone_id']);


			//Will become true, if cod can be processed.
			$cod_country = false;

			//check if payment method is cod. If yes, check if cod is possible.
			if ($GLOBALS['payment'] == 'cod') {
				//process installed shipping modules
				if (substr_count($shipping['id'], 'flat') !=0) $cod_zones = split("[:,]", MODULE_ORDER_TOTAL_COD_FEE_FLAT);
				if (substr_count($shipping['id'], 'item') !=0) $cod_zones = split("[:,]", MODULE_ORDER_TOTAL_COD_FEE_ITEM);
				if (substr_count($shipping['id'], 'table') !=0) $cod_zones = split("[:,]", MODULE_ORDER_TOTAL_COD_FEE_TABLE);
				if (substr_count($shipping['id'], 'ups') !=0) $cod_zones = split("[:,]", MODULE_ORDER_TOTAL_COD_FEE_UPS);
				//         if (substr_count($shipping['id'], 'usps') !=0) $cod_zones = split("[:,]", MODULE_ORDER_TOTAL_COD_FEE_USPS);
				//         if (substr_count($shipping['id'], 'fedex') !=0) $cod_zones = split("[:,]", MODULE_ORDER_TOTAL_COD_FEE_FEDEX);
				if (substr_count($shipping['id'], 'zones') !=0) $cod_zones = split("[:,]", MODULE_ORDER_TOTAL_COD_FEE_ZONES);
				if (substr_count($shipping['id'], 'corriere1') !=0) $cod_zones = split("[:,]", MODULE_ORDER_TOTAL_COD_FEE_CORRIERE1);
				if (substr_count($shipping['id'], 'tnt') !=0) $cod_zones = split("[:,]", MODULE_ORDER_TOTAL_COD_FEE_TNT);
				if (substr_count($shipping['id'], 'bartolini') !=0) $cod_zones = split("[:,]", MODULE_ORDER_TOTAL_COD_FEE_BARTOLINI);
				if (substr_count($shipping['id'], 'postepaccocelere1') !=0) $cod_zones = split("[:,]", MODULE_ORDER_TOTAL_COD_FEE_POSTEPACCOCELERE1);
				if (substr_count($shipping['id'], 'postepaccocelere3') !=0) $cod_zones = split("[:,]", MODULE_ORDER_TOTAL_COD_FEE_POSTEPACCOCELERE3);
				if (substr_count($shipping['id'], 'postepaccoordinario') !=0) $cod_zones = split("[:,]", MODULE_ORDER_TOTAL_COD_FEE_POSTEPACCOORDINARIO);
				//        if (substr_count($shipping['id'], 'postepostaordinaria') !=0) $cod_zones = split("[:,]", MODULE_ORDER_TOTAL_COD_FEE_POSTEPOSTAORDINARIA);
				//        if (substr_count($shipping['id'], 'postepostaprioritaria') !=0) $cod_zones = split("[:,]", MODULE_ORDER_TOTAL_COD_FEE_POSTEPOSTAPRIORITARIA);
				if (substr_count($shipping['id'], 'postepostaraccomandata') !=0) $cod_zones = split("[:,]", MODULE_ORDER_TOTAL_COD_FEE_POSTEPOSTARACCOMANDATA);
				//satt inn av Pompel
				if (substr_count($shipping['id'], 'servicepakke') !=0) $cod_zones = split("[:,]", MODULE_ORDER_TOTAL_COD_FEE_SERVICEPAKKE);
				 
				for ($i = 0; $i < count($cod_zones); $i++) {
					if ($cod_zones[$i] == $order->delivery['country']['iso_code_2']) {
						$cod_cost = $cod_zones[$i + 1];
						$cod_country = true;
						//print('match' . $i . ': ' . $cod_cost);
						break;
					} elseif ($cod_zones[$i] == '00') {
						$cod_cost = $cod_zones[$i + 1];
						$cod_country = true;
						//print('match' . $i . ': ' . $cod_cost);
						break;
					} else {
						//print('no match');
					}
					$i++;
				}
			} else {
				//COD selected, but no shipping module which offers COD
			}

			if ($cod_country) {
				$order->info['tax'] += tep_calculate_tax($cod_cost, $tax);
				$order->info['tax_groups']["$tax_description"] += tep_calculate_tax($cod_cost, $tax);
				$order->info['total'] += $cod_cost + tep_calculate_tax($cod_cost, $tax);

				$this->output[] = array('title' => $this->title . ':',
                                    'text' => $currencies->format(tep_add_tax($cod_cost, $tax), true,  $order->info['currency'], $order->info['currency_value']),
                                    'value' => tep_add_tax($cod_cost, $tax));
			} else {
				//Following code should be improved if we can't get the shipping modules disabled, who don't allow COD
				// as well as countries who do not have cod
				//          $this->output[] = array('title' => $this->title . ':',
				//                                  'text' => 'No COD for this module.',
				//                                  'value' => '');
			}
		}
	}

	function check() {
		if (!isset($this->_check)) {
			$check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_COD_STATUS'");
			$this->_check = tep_db_num_rows($check_query);
		}

		return $this->_check;
	}
	//lagt tilk servicepakke her!!!!
	function keys() {
		return array('MODULE_ORDER_TOTAL_COD_STATUS', 'MODULE_ORDER_TOTAL_COD_SORT_ORDER', 'MODULE_ORDER_TOTAL_COD_FEE_FLAT', 'MODULE_ORDER_TOTAL_COD_FEE_ITEM', 'MODULE_ORDER_TOTAL_COD_FEE_TABLE', 'MODULE_ORDER_TOTAL_COD_FEE_UPS', 'MODULE_ORDER_TOTAL_COD_FEE_ZONES','MODULE_ORDER_TOTAL_COD_FEE_CORRIERE1','MODULE_ORDER_TOTAL_COD_FEE_POSTEPACCOCELERE1','MODULE_ORDER_TOTAL_COD_FEE_POSTEPACCOCELERE3','MODULE_ORDER_TOTAL_COD_FEE_POSTEPACCOORDINARIO','MODULE_ORDER_TOTAL_COD_FEE_POSTEPOSTARACCOMANDATA','MODULE_ORDER_TOTAL_COD_FEE_BARTOLINI','MODULE_ORDER_TOTAL_COD_FEE_TNT', 'MODULE_ORDER_TOTAL_COD_TAX_CLASS');
	}

	function install() {
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Mostra contrassegno', 'MODULE_ORDER_TOTAL_COD_STATUS', 'true', 'Vuoi mostrare questo modulo?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Ordine', 'MODULE_ORDER_TOTAL_COD_SORT_ORDER', '0', 'Ordine di apparizione.', '6', '2', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Supplemento contrassegno per spedizione Tariffa Fissa', 'MODULE_ORDER_TOTAL_COD_FEE_FLAT', 'IT:3.00,00:9.99', 'FLAT: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)', '6', '3', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Supplemento contrassegno per spedizione Per Articolo', 'MODULE_ORDER_TOTAL_COD_FEE_ITEM', 'AT:3.00,DE:3.58,00:9.99', 'ITEM: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)', '6', '4', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Supplemento contrassegno per spedizione Tariffa in base al peso', 'MODULE_ORDER_TOTAL_COD_FEE_TABLE', 'AT:3.00,DE:3.58,00:9.99', 'TABLE: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)', '6', '5', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Supplemento contrassegno per spedizione UPS', 'MODULE_ORDER_TOTAL_COD_FEE_UPS', 'CA:4.50,US:3.00,00:9.99', 'UPS: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)', '6', '6', now())");
		//     tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Supplemento contrassegno per spedizione USPS', 'MODULE_ORDER_TOTAL_COD_FEE_USPS', 'CA:4.50,US:3.00,00:9.99', 'USPS: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)', '6', '7', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Supplemento contrassegno per spedizione Zone', 'MODULE_ORDER_TOTAL_COD_FEE_ZONES', 'CA:4.50,US:3.00,00:9.99', 'ZONES: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)', '6', '8', now())");
		//     tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Supplemento contrassegno per spedizione Post', 'MODULE_ORDER_TOTAL_COD_FEE_AP', 'AT:3.63,00:9.99', 'Austrian Post: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)', '6', '9', now())");
		//     tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Supplemento contrassegno per spedizione German Post', 'MODULE_ORDER_TOTAL_COD_FEE_DP', 'DE:3.58,00:9.99', 'German Post: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)', '6', '10', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Supplemento contrassegno per spedizione CORRIERE1', 'MODULE_ORDER_TOTAL_COD_FEE_CORRIERE1', 'IT:7.00,00:9.99', 'CORRIERE1: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)', '6', '11', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Supplemento contrassegno per spedizione POSTEPACCOCELERE1', 'MODULE_ORDER_TOTAL_COD_FEE_POSTEPACCOCELERE1', 'IT:7.50,00:9.99', 'POSTEPACCOCELERE1: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)', '6', '12', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Supplemento contrassegno per spedizione POSTEPACCOCELERE3', 'MODULE_ORDER_TOTAL_COD_FEE_POSTEPACCOCELERE3', 'IT:7.50,00:9.99', 'POSTEPACCOCELERE3: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)', '6', '13', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Supplemento contrassegno per spedizione POSTEPACCOORDINARIO', 'MODULE_ORDER_TOTAL_COD_FEE_POSTEPACCOORDINARIO', 'IT:7.50,00:9.99', 'POSTEPACCOORDINARIO: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)', '6', '14', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Supplemento contrassegno per spedizione BARTOLINI', 'MODULE_ORDER_TOTAL_COD_FEE_BARTOLINI', 'IT:10.00', 'BARTOLINI: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)', '6', '14', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Supplemento contrassegno per spedizione TNT TRACO', 'MODULE_ORDER_TOTAL_COD_FEE_TNT', 'IT:6.00', 'TNT: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)', '6', '14', now())");
		//      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Supplemento contrassegno per spedizione POSTEPOSTAORDINARIA', 'MODULE_ORDER_TOTAL_COD_FEE_POSTEPOSTAORDINARIA', 'IT:7.50,00:9.99', 'POSTEPOSTAORDINARIA: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)', '6', '15', now())");
		//      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Supplemento contrassegno per spedizione POSTEPOSTAPRIORITARIA', 'MODULE_ORDER_TOTAL_COD_FEE_POSTEPOSTAPRIORITARIA', 'IT:7.50,00:9.99', 'POSTEPOSTAPRIORITARIA: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)', '6', '16', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Supplemento contrassegno per spedizione POSTEPOSTARACCOMANDATA', 'MODULE_ORDER_TOTAL_COD_FEE_POSTEPOSTARACCOMANDATA', 'IT:7.50,00:9.99', 'POSTEPOSTARACCOMANADATA: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)', '6', '17', now())");
		//      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Supplemento contrassegno per spedizione Servicepakke', 'MODULE_ORDER_TOTAL_COD_FEE_SERVICEPAKKE', 'NO:69', 'Servicepakke: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)', '6', '18', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Supplemento contrassegno per spedizione FedEx', 'MODULE_ORDER_TOTAL_COD_FEE_FEDEX', 'US:3.00', 'FedEx: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)', '6', '19', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_ORDER_TOTAL_COD_TAX_CLASS', '0', 'Usa questa tassa per il contrassegno.', '6', '11', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
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
