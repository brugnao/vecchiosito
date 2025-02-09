<?php
/*
  modulo per pagamento tramite conto corrente  postale
  
  scritto da Riccardo Roscilli

  http://www.oscommerce.it
  
  per 
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class ccp {
    var $code, $title, $description, $enabled;

// class constructor
    function ccp() {
      global $order;

      $this->code = 'ccp';
      $this->title = MODULE_PAYMENT_CCP_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_CCP_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_CCP_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_CCP_STATUS == 'True') ? true : false);

      if ((int)MODULE_PAYMENT_CCP_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_CCP_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();
    
      $this->email_footer = MODULE_PAYMENT_CCP_TEXT_EMAIL_FOOTER;
    }

// class methods
    function update_status() {
      global $order;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_CCP_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_CCP_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
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
      return array('title' => 'Da pagare a:<br /><br/>Intestatario: '.MODULE_PAYMENT_CCP_INTESTATARIO.'<br />Indirizzo: '.MODULE_PAYMENT_CCP_INDIRIZZO.'<br />C/C: '.MODULE_PAYMENT_CCP_CC.'<br /><br />Non appena riceveremo il pagamento provvederemo alla spedizione della merce ordinata.');
    }

    function process_button() {
      return false;
    }

    function before_process() {
      return false;
    }

    function after_process() {
      return false;
    }

    function get_error() {
      return false;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CCP_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Abilita il pagamento tramite Conto Corrente Postale', 'MODULE_PAYMENT_CCP_STATUS', 'True', 'Vuoi accettare pagamenti tramite Conto Corrente Postale?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Intestatario', 'MODULE_PAYMENT_CCP_INTESTATARIO', '', 'Intestario del conto (Cognome Nome/Societ&agrave;)', '6', '3', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Indirizzo', 'MODULE_PAYMENT_CCP_INDIRIZZO', '', 'Indirizzo intestatario del conto', '6', '4', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('CC', 'MODULE_PAYMENT_CCP_CC', '', 'numero di Conto Corrente Postale', '6', '5', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Zona', 'MODULE_PAYMENT_CCP_ZONE', '0', 'Se &egrave; selezionata una zona, il pagamento viene abilitato solo per questa zona.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Status Ordine', 'MODULE_PAYMENT_CCP_ORDER_STATUS_ID', '0', 'Imposta lo stato dell\'ordine a questo valore', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Ordine di visualizzazione.', 'MODULE_PAYMENT_CCP_SORT_ORDER', '0', 'Ordine di visualizzazione. I numeri pi&ugrave; bassi sono mostrati prima.', '6', '0', now())");
	}

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_CCP_STATUS', 'MODULE_PAYMENT_CCP_INTESTATARIO', 'MODULE_PAYMENT_CCP_INDIRIZZO', 'MODULE_PAYMENT_CCP_CC','MODULE_PAYMENT_CCP_ZONE', 'MODULE_PAYMENT_CCP_ORDER_STATUS_ID', 'MODULE_PAYMENT_CCP_SORT_ORDER');
    }
  }
?>
