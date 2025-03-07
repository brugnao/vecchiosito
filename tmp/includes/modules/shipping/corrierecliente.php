<?php
/*
  modulo di spedizione tramite Consegna in Sede
  by hOZONE, hozone@tiscali.it, http://hozone.cjb.net

  visita osCommerceITalia, http://www.oscommerceitalia.com
  
  derivato dal modulo:
  $Id: flat.php,v 1.40 2003/02/05 22:41:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class corrierecliente {
    var $code, $title, $description, $icon, $enabled;

// class constructor
    function corrierecliente() {
      global $order;

      $this->code = 'corrierecliente';
      $this->title = MODULE_SHIPPING_CORRIERECLIENTE_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_CORRIERECLIENTE_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_SHIPPING_CORRIERECLIENTE_SORT_ORDER;
      $this->icon = '';
      $this->tax_class = MODULE_SHIPPING_CORRIERECLIENTE_TAX_CLASS;
      $this->enabled = ((MODULE_SHIPPING_CORRIERECLIENTE_STATUS == 'True') ? true : false);

      if ( ($this->enabled == true) && ((int)MODULE_SHIPPING_CORRIERECLIENTE_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_SHIPPING_CORRIERECLIENTE_ZONE . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
        while ($check = tep_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check['zone_id'] == $order->delivery['zone_id']) {
            $check_flag = true;
            break;
          }
        }

        if ($check_flag == false) {
          $this->enabled = false;
        }
      }
    }

// class methods
    function quote($method = '') {
      global $order;

      $this->quotes = array('id' => $this->code,
                            'module' => MODULE_SHIPPING_CORRIERECLIENTE_TEXT_TITLE,
                            'methods' => array(array('id' => $this->code,
                                                     'title' => MODULE_SHIPPING_CORRIERECLIENTE_TEXT_WAY,
                                                     'cost' => MODULE_SHIPPING_CORRIERECLIENTE_COST)));

      if ($this->tax_class > 0) {
        $this->quotes['tax'] = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
      }

      if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);

      return $this->quotes;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_CORRIERECLIENTE_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Abilita Corriere del Cliente', 'MODULE_SHIPPING_CORRIERECLIENTE_STATUS', 'True', 'Vuoi consentire la spedizione con corriere del cliente?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Shipping Cost', 'MODULE_SHIPPING_CORRIERECLIENTE_COST', '0.00', 'Costo fisso per le spedizioni con questo metodo.', '6', '0', now())");
//	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Strada del sito di consegna', 'MODULE_SHIPPING_CORRIERECLIENTE_ADDR_VIA', '', 'Specificare la via e il numero civico del sito di consegna.', '6', '3', now())");
//	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Citt&agrave; del sito di consegna', 'MODULE_SHIPPING_CORRIERECLIENTE_ADDR_CITTA', '', 'Specificare la citt&agrave; e la provincia del sito di consegna.', '6', '4', now())");
//      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('CAP del sito di consegna', 'MODULE_SHIPPING_CORRIERECLIENTE_ADDR_CAP', '', 'Specificare il CAP del sito di consegna.', '6', '5', now())");
//      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_CORRIERECLIENTE_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
//      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'MODULE_SHIPPING_CORRIERECLIENTE_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_CORRIERECLIENTE_SORT_ORDER', '0', 'Ordine di visualizzazione.', '6', '0', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_SHIPPING_CORRIERECLIENTE_STATUS', 'MODULE_SHIPPING_CORRIERECLIENTE_COST',  'MODULE_SHIPPING_CORRIERECLIENTE_SORT_ORDER');
    }
  }
?>
