<?php
/*
  modulo di spedizione tramite PostaPrioritaria
  by hOZONE, hozone@tiscali.it, http://hozone.cjb.net

  visita osCommerceITalia, http://www.oscommerceitalia.com
  
  derivato dal modulo:
  $Id: zones.php,v 1.20 2003/06/15 19:48:09 thomasamoulton Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class postepostaprioritaria {
    var $code, $title, $description, $enabled, $num_zones;

// class constructor
    function postepostaprioritaria() {
      $this->code = 'postepostaprioritaria';
      $this->title = MODULE_SHIPPING_POSTEPOSTAPRIORITARIA_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_POSTEPOSTAPRIORITARIA_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_SHIPPING_POSTEPOSTAPRIORITARIA_SORT_ORDER;
      $this->icon = '';
      $this->tax_class = MODULE_SHIPPING_POSTEPOSTAPRIORITARIA_TAX_CLASS;
      $this->enabled = ((MODULE_SHIPPING_POSTEPOSTAPRIORITARIA_STATUS == 'True') ? true : false);

      // CUSTOMIZE THIS SETTING FOR THE NUMBER OF POSTEPOSTAPRIORITARIA NEEDED
      $this->num_zones = 3;
    }

// class methods
    function quote($method = '') {
      global $order, $shipping_weight, $shipping_num_boxes;

      $dest_country = $order->delivery['country']['iso_code_2'];
      $dest_zone = 0;
      $error = false;

      for ($i=1; $i<=$this->num_zones; $i++) {
        $countries_table = constant('MODULE_SHIPPING_POSTEPOSTAPRIORITARIA_COUNTRIES_' . $i);
        $country_zones = split("[,]", $countries_table);
        if (in_array($dest_country, $country_zones)) {
          $dest_zone = $i;
          break;
        }
      }

	  //Added to select default country if not in listing
      if ($dest_zone == 0) {
        for ($i=1; $i<=$this->num_zones; $i++) {
          $countries_table = constant('MODULE_SHIPPING_POSTEPOSTAPRIORITARIA_COUNTRIES_' . $i);
          $country_zones = split("[,]", $countries_table);
          if (in_array("*", $country_zones)) {
            $dest_zone = $i;
            break;
          }
		}
      }

      if ($dest_zone == 0) {
        $error = true;
		$error_text = MODULE_SHIPPING_POSTEPOSTAPRIORITARIA_INVALID_ZONE;
      } else {
        $shipping = -1;
        $postepostaprioritaria_cost = constant('MODULE_SHIPPING_POSTEPOSTAPRIORITARIA_COST_' . $dest_zone);

        $postepostaprioritaria_table = split("[:,]" , $postepostaprioritaria_cost);
        $size = sizeof($postepostaprioritaria_table);
        for ($i=0; $i<$size; $i+=2) {
          if ($shipping_weight <= $postepostaprioritaria_table[$i]) {
            $shipping = $postepostaprioritaria_table[$i+1];
            $shipping_method = MODULE_SHIPPING_POSTEPOSTAPRIORITARIA_TEXT_WAY;
            break;
          }
        }

        if ($shipping == -1) {
          $shipping_cost = 0;
		  $error = true;
          $error_text = MODULE_SHIPPING_POSTEPOSTAPRIORITARIA_UNDEFINED_RATE;
        } else {
          $shipping_cost = ($shipping * $shipping_num_boxes) + constant('MODULE_SHIPPING_POSTEPOSTAPRIORITARIA_HANDLING_' . $dest_zone);
        }
      }

      $this->quotes = array('id' => $this->code,
                            'module' => MODULE_SHIPPING_POSTEPOSTAPRIORITARIA_TEXT_TITLE,
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
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_POSTEPOSTAPRIORITARIA_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable PostaPrioritaria Method', 'MODULE_SHIPPING_POSTEPOSTAPRIORITARIA_STATUS', 'True', 'Do you want to offer zone rate shipping?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_POSTEPOSTAPRIORITARIA_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_POSTEPOSTAPRIORITARIA_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())");
      for ($i = 1; $i <= $this->num_zones; $i++) {
		$default_countries = '';
		if ($i == 1) {
		  //europa (zona 1)
		  $default_countries = 'FX,ES,GI,MC,BG,CH,HU,PL,CZ,VA,IT,AL,RU,FO,LT,AT,SI,GE,MT,BE,SJ,GR,NL,HR,UA,IS,PT,DK,YU,LV,AD,SM,FI,LU,AZ,GS,DE,MD,BA,SE,GL,NO,CY,GB,IE,RO,EE,LI,AM,SK,FR,MK,BY';
          $shipping_table = '0.02:0.62,0.1:1.24,0.349:1.86,1:4.96,2:8.06';
        }
        if ($i == 2) {
		  //oceania (zona 3)
		  $default_countries = 'NZ,AU,HM,MP,FJ,PN,KI,TK,NR,VU,AS,NU,CC,PW,PF,WS,MH,TO,NC,WF,AQ,NF,CK,PG,GU,SB,FM,TV';
          $shipping_table = '0.02:0.77,0.1:1.81,0.349:4.39,1:10.33,2:20.66';
		}
        if ($i == 3) {
		  //altri paesi (zona 2)
          $default_countries = '*';
          $shipping_table = '0.02:0.77,0.1:1.55,0.349:4.13,1:8.26,2:16.53';
		}
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Countries', 'MODULE_SHIPPING_POSTEPOSTAPRIORITARIA_COUNTRIES_" . $i ."', '" . $default_countries . "', 'Comma separated list of two character ISO country codes that are part of Zone " . $i . ".', '6', '0', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Shipping Table', 'MODULE_SHIPPING_POSTEPOSTAPRIORITARIA_COST_" . $i ."', '" . $shipping_table . "', 'Shipping rates to Zone " . $i . " destinations based on a group of maximum order weights. Example: 3:8.50,7:10.50,... Weights less than or equal to 3 would cost 8.50 for Zone " . $i . " destinations.', '6', '0', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Handling Fee', 'MODULE_SHIPPING_POSTEPOSTAPRIORITARIA_HANDLING_" . $i."', '0', 'Handling Fee for this shipping zone', '6', '0', now())");
      }
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      $keys = array('MODULE_SHIPPING_POSTEPOSTAPRIORITARIA_STATUS', 'MODULE_SHIPPING_POSTEPOSTAPRIORITARIA_TAX_CLASS', 'MODULE_SHIPPING_POSTEPOSTAPRIORITARIA_SORT_ORDER');

      for ($i=1; $i<=$this->num_zones; $i++) {
        $keys[] = 'MODULE_SHIPPING_POSTEPOSTAPRIORITARIA_COUNTRIES_' . $i;
        $keys[] = 'MODULE_SHIPPING_POSTEPOSTAPRIORITARIA_COST_' . $i;
        $keys[] = 'MODULE_SHIPPING_POSTEPOSTAPRIORITARIA_HANDLING_' . $i;
      }

      return $keys;
    }
  }
?>
