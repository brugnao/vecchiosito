<?php
/*
  modulo di spedizione tramite Bartolini Corriere Espresso
  by oscommerce.it
  http://www.oscommerce.it/
  

  
  Copyright (c) 2005 osCommerce.it

  Released under the GNU General Public License
*/

  class bartolini {
    var $code, $title, $description, $enabled, $num_zones;

// class constructor
    function bartolini() {
      $this->code = 'bartolini';
      $this->title = MODULE_SHIPPING_BARTOLINI_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_BARTOLINI_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_SHIPPING_BARTOLINI_SORT_ORDER;
      $this->icon = '';
      $this->tax_class = MODULE_SHIPPING_BARTOLINI_TAX_CLASS;
      $this->enabled = ((MODULE_SHIPPING_BARTOLINI_STATUS == 'True') ? true : false);

      // CUSTOMIZE THIS SETTING FOR THE NUMBER OF BARTOLINI NEEDED
      $this->num_zones = 1;
    }

// class methods
    function quote($method = '') {
      global $order, $shipping_weight, $shipping_num_boxes;

      $dest_country = $order->delivery['country']['iso_code_2'];
      $dest_zone = 0;
      $error = false;

      for ($i=1; $i<=$this->num_zones; $i++) {
        $countries_table = constant('MODULE_SHIPPING_BARTOLINI_COUNTRIES_' . $i);
        $country_zones = split("[,]", $countries_table);
        if (in_array($dest_country, $country_zones)) {
          $dest_zone = $i;
          break;
        }
      }

	  //Added to select default country if not in listing
      if ($dest_zone == 0) {
        for ($i=1; $i<=$this->num_zones; $i++) {
          $countries_table = constant('MODULE_SHIPPING_BARTOLINI_COUNTRIES_' . $i);
          $country_zones = split("[,]", $countries_table);
          if (in_array("*", $country_zones)) {
            $dest_zone = $i;
            break;
          }
		}
      }

      if ($dest_zone == 0) {
        $error = true;
		$error_text = MODULE_SHIPPING_BARTOLINI_INVALID_ZONE;
      } else {
        $shipping = -1;
        $bartolini_cost = constant('MODULE_SHIPPING_BARTOLINI_COST_' . $dest_zone);

        $bartolini_table = split("[:,]" , $bartolini_cost);
        $size = sizeof($bartolini_table);
        for ($i=0; $i<$size; $i+=2) {
          if ($shipping_weight <= $bartolini_table[$i]) {
            $shipping = $bartolini_table[$i+1];
            $shipping_method = MODULE_SHIPPING_BARTOLINI_TEXT_WAY;
            break;
          }
        }

        if ($shipping == -1) {
          $shipping_cost = 0;
		  $error = true;
          $error_text = MODULE_SHIPPING_BARTOLINI_UNDEFINED_RATE;
        } else {
          $shipping_cost = ($shipping * $shipping_num_boxes) + constant('MODULE_SHIPPING_BARTOLINI_HANDLING_' . $dest_zone);
        }
      }

      $this->quotes = array('id' => $this->code,
                            'module' => MODULE_SHIPPING_BARTOLINI_TEXT_TITLE,
                            'methods' => array(array('id' => $this->code,
                                                     'title' => $shipping_method,
                                                     'cost' => $shipping_cost)));

      if ($this->tax_class > 0) {
        $this->quotes['tax'] = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
      }

      if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);

      if ($error == true) $this->quotes['error'] = $error_text;

      return $this->quotes;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_BARTOLINI_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Abilita Bartolini', 'MODULE_SHIPPING_BARTOLINI_STATUS', 'True', 'Vuoi abilitare il modulo Bartolini?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tassa', 'MODULE_SHIPPING_BARTOLINI_TAX_CLASS', '0', 'Usa la seguente tassa per i costi di spedizionie con Bartolini.', '6', '0', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Ordine', 'MODULE_SHIPPING_BARTOLINI_SORT_ORDER', '0', 'Ordine di visualizzazione.', '6', '0', now())");
      for ($i = 1; $i <= $this->num_zones; $i++) {
		$default_countries = '';
		if ($i == 1) {
          $default_countries = 'IT';
          $shipping_table = '10:12.00,20:17.00,50:26.00,100:35.00,200:65.00,300:100.00';
        }
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zona " . $i ." Paesi', 'MODULE_SHIPPING_BARTOLINI_COUNTRIES_" . $i ."', '" . $default_countries . "', 'Lista separata da virgole, dei paesi espressi con i codici ISO (2 caratteri) " . $i . ".', '6', '0', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zona " . $i ." Tabella tariffe', 'MODULE_SHIPPING_BARTOLINI_COST_" . $i ."', '" . $shipping_table . "', 'Tariffe per la zona " . $i . " basate su una gruppo di pesi per prezzi. Es: 3:8.50,7:10.50,... Pesi inferiori o uguali a 3kg costeranno 8.50 eur. Pesi compresi dra 3 e 7kg costeranno 10.50 e cos&igrave; via.', '6', '0', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zona " . $i ." Costo di imballaggio', 'MODULE_SHIPPING_BARTOLINI_HANDLING_" . $i."', '0', 'Costo di imballaggio per questa zona', '6', '0', now())");
      }
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      $keys = array('MODULE_SHIPPING_BARTOLINI_STATUS', 'MODULE_SHIPPING_BARTOLINI_TAX_CLASS', 'MODULE_SHIPPING_BARTOLINI_SORT_ORDER');

      for ($i=1; $i<=$this->num_zones; $i++) {
        $keys[] = 'MODULE_SHIPPING_BARTOLINI_COUNTRIES_' . $i;
        $keys[] = 'MODULE_SHIPPING_BARTOLINI_COST_' . $i;
        $keys[] = 'MODULE_SHIPPING_BARTOLINI_HANDLING_' . $i;
      }

      return $keys;
    }
  }
?>
