<?php
/*
  modulo di spedizione tramite SUSA Tariffe in base alle province
  by oscommerce.it
  http://www.oscommerce.it/
  

  
  Copyright (c) 2005 osCommerce.it

  Released under the GNU General Public License
*/

  class susa {
    var $code, $title, $description, $enabled, $num_zones;

// class constructor
    function susa() {
    	global $order;
    	
      $this->code = 'susa';
      $this->title = MODULE_SHIPPING_SUSA_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_SUSA_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_SHIPPING_SUSA_SORT_ORDER;
      $this->icon = '';
      $this->tax_class = MODULE_SHIPPING_SUSA_TAX_CLASS;
      $this->enabled = ((MODULE_SHIPPING_SUSA_STATUS == 'True') ? true : false);
  //   print MODULE_SHIPPING_SUSA_ZONE;
  //    exit;
      if ( ($this->enabled == true) && ((int)MODULE_SHIPPING_SUSA_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_SHIPPING_SUSA_ZONE . "' and zone_country_id = '" . $order->delivery['country_id'] . "' order by zone_id");
  /*      while ($check = tep_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check['zone_id'] == $order->delivery['zone_id']) {
            $check_flag = true;
            break;
          }
        }*/
        if (mysql_num_rows($check_query) >= 1) $check_flag = true;

        if ($check_flag == false) {
          $this->enabled = false;
        }
      }
    }

// class methods
    function quote($method = '') {
      global $order, $shipping_weight, $shipping_num_boxes;
/*
 parametri in entrata: Provincia di destinazione  Peso totale ordine
 calcoli: Trovare Regione di appartenenza, trovare tariffa per 100kg per la regione, calcolare le frazioni di 50kg fino a 500kg, dopo i 500kg le frazioni sono di 100Kg
 output: $quotes 
    $this->quotes = array('id' => $this->code,
                            'module' => MODULE_SHIPPING_SUSA_TEXT_TITLE,
                            'methods' => array(array('id' => $this->code,
                                                     'title' => $shipping_method,
                                                     'cost' => $shipping_cost)));

 */

/*	
 

print_r($order);
exit; 
  
  
 order Object
(
    [info] => Array
        (
            [order_status] => 1
            [currency] => EUR
            [currency_value] => 1.00000000
            [payment_method] => 
            [shipping_method] => 
            [shipping_cost] => 
            [subtotal] => 170.080898
            [subtotal_net] => 170.080898
            [tax] => 34.0161796
            [tax_groups] => Array
                (
                    [IVA 20%] => 34.0161796
                )

            [comments] => 
            [total] => 204.0970776
        )

    [totals] => Array
        (
        )

    [products] => Array
        (
            [0] => Array
                (
                    [qty] => 1
                    [name] => Unreal Tournament
                    [model] => PC-UNTM
                    [price] => 89.99
                    [final_price] => 89.99
                    [weight] => 7.00
                    [id] => 22
                    [tax] => 20
                    [tax_description] => IVA 20%
                )

            [1] => Array
                (
                    [qty] => 1
                    [name] => Microsoft IntelliMouse Pro
                    [model] => MSIMPRO
                    [price] => 44.995999
                    [final_price] => 44.995999
                    [weight] => 7.00
                    [id] => 3
                    [tax] => 20
                    [tax_description] => IVA 20%
                )

            [2] => Array
                (
                    [qty] => 1
                    [name] => Courage Under Fire
                    [model] => DVD-CUFI
                    [price] => 35.094899
                    [final_price] => 35.094899
                    [weight] => 7.00
                    [id] => 16
                    [tax] => 20
                    [tax_description] => IVA 20%
                )

        )

    [customer] => Array
        (
            [firstname] => Test
            [lastname] => Test
            [company] => PWS
            [piva] => 
            [company_cf] => 
            [entry_type] => 
            [customer_type] => 
            [street_address] => Via dei test e delle prove
            [suburb] => 
            [city] => Massa
            [postcode] => 54100
            [state] => Massa-Carrara
            [zone_id] => 232
            [country] => Array
                (
                    [id] => 105
                    [title] => Italia
                    [iso_code_2] => IT
                    [iso_code_3] => ITA
                )

            [format_id] => 1
            [telephone] => 3293965918
            [email_address] => info@oscommerce.com
        )

    [delivery] => Array
        (
            [firstname] => Test
            [lastname] => Test
            [company] => PWS
            [piva] => 
            [cf] => 
            [company_cf] => 
            [entry_type] => company
            [street_address] => Via dei test e delle prove
            [suburb] => 
            [city] => Massa
            [postcode] => 54100
            [state] => Massa-Carrara
            [zone_id] => 232
            [country] => Array
                (
                    [id] => 105
                    [title] => Italia
                    [iso_code_2] => IT
                    [iso_code_3] => ITA
                )

            [country_id] => 105
            [format_id] => 1
        )

    [content_type] => physical
    [billing] => Array
        (
            [firstname] => 
            [lastname] => 
            [company] => 
            [piva] => 
            [cf] => 
            [company_cf] => 
            [entry_type] => 
            [street_address] => 
            [suburb] => 
            [city] => 
            [postcode] => 
            [state] => 
            [zone_id] => 
            [country] => Array
                (
                    [id] => 
                    [title] => 
                    [iso_code_2] => 
                    [iso_code_3] => 
                )

            [country_id] => 
            [format_id] => 
        )

)

*/
      
      // ricavo la provincia di destino (codice a 2 lettere)
	  if (isset ($order->delivery['zone_id'])) // è nel checkout
     	 $zone_id = $order->delivery['zone_id'];
      elseif(isset($_SESSION['customer_zone_id'])) // è loggato ma non è nel checkout
      	 $zone_id = $_SESSION['customer_zone_id'];     
      else 
		 $zone_id = STORE_ZONE; 
      
	
      	 
      $array_provincia = tep_get_row(TABLE_ZONES, 'zone_id', $zone_id);
  //     print_r($array_provincia);
  //  exit;
      // apro il file delle tariffe alla ricerca della provincia di destino (codice)
     require_once('admin/includes/classes/CsvIterator.class.php');
     //  print_r($order);
      $csvIterator = new CsvIterator(DIR_FS_CATALOG . '/ext/modules/shipping/susa.csv', true, ';', '"');
      $dest_zone = 0;
      /*
      Array
		(
		    [Codice regione] => 1
		    [Denominazione regione] => Piemonte
		    [Sigla automobilistica] => TO
		    [Tariffa Susa] => 
		)
      */
      // print_r($array_provincia);
	  while ($csvIterator->next()) {
				$provincia  = ($csvIterator->current());
				if ($provincia['Sigla_automobilistica'] == $array_provincia['zone_code']) 
				{
					
					$provincia['Sigla_automobilistica'];
					$dest_zone =  $zone_id;
				
					break;
				}
			}     
		
			
      if ($dest_zone == 0) {
        $error = true;
		$error_text = MODULE_SHIPPING_SUSA_INVALID_ZONE;
      } else { 
      	// calcolo il costo della spedizione per il tizio con sovrapprezzo per inoltro se è fuori provincia
     	
      	// arrotondo il peso ai 100kg superiori e poi lo divido per 100, mi rende il moltiplicatore per la tariff
      	
        $shipping_multiplier = ceil($shipping_weight / 100);
        // per il momento aggiungiamo sempre l'inoltro
			// ($provincia['Tariffa Susa'] + constant('MODULE_SHIPPING_SUSA_INOLTRO'))  * $shipping_weight
			$provincia['Tariffa_Susa'] = str_replace(',','.',$provincia['Tariffa_Susa']);
          $shipping_cost = ($provincia['Tariffa_Susa'] * $shipping_multiplier) + str_replace(',','.',constant('MODULE_SHIPPING_SUSA_INOLTRO')) ;
          $shipping_cost += str_replace(',','.',constant('MODULE_SHIPPING_SUSA_HANDLING'));
      }

      $this->quotes = array('id' => $this->code,
                            'module' => MODULE_SHIPPING_SUSA_TEXT_TITLE,
                            'methods' => array(array('id' => $this->code,
                                                     'title' => MODULE_SHIPPING_SUSA_TEXT_WAY,
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
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_SUSA_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Abilita ', 'MODULE_SHIPPING_SUSA_STATUS', 'True', 'Vuoi abilitare il modulo ?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
  //    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tassa', 'MODULE_SHIPPING_SUSA_TAX_CLASS', '0', 'Usa la seguente tassa per i costi di spedizionie con .', '6', '0', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Ordine', 'MODULE_SHIPPING_SUSA_SORT_ORDER', '0', 'Ordine di visualizzazione.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Inoltro', 'MODULE_SHIPPING_SUSA_INOLTRO', '0', 'Inserire il sovrapprezzo per l\'inoltro.', '6', '0', now())");
      
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'MODULE_SHIPPING_SUSA_ZONE', '0', 'Se è selezionato un paese, abilita le spese di spedizione solo per quel paese.', '6', '0', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Diritto Fisso', 'MODULE_SHIPPING_SUSA_HANDLING', '0', 'Diritto Fisso per spedizione', '6', '0', now())");
      
      /*    for ($i = 1; $i <= $this->num_zones; $i++) {
		$default_countries = '';
		if ($i == 1) {
          $default_countries = 'IT';
          $shipping_table = '10:12.00,20:17.00,50:26.00,100:35.00,200:65.00,300:100.00';
        }
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zona " . $i ." Paesi', 'MODULE_SHIPPING_SUSA_COUNTRIES_" . $i ."', '" . $default_countries . "', 'Lista separata da virgole, dei paesi espressi con i codici ISO (2 caratteri) " . $i . ".', '6', '0', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zona " . $i ." Tabella tariffe', 'MODULE_SHIPPING_SUSA_COST_" . $i ."', '" . $shipping_table . "', 'Tariffe per la zona " . $i . " basate su una gruppo di pesi per prezzi. Es: 3:8.50,7:10.50,... Pesi inferiori o uguali a 3kg costeranno 8.50 eur. Pesi compresi dra 3 e 7kg costeranno 10.50 e cos&igrave; via.', '6', '0', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zona " . $i ." Costo di imballaggio', 'MODULE_SHIPPING_SUSA_HANDLING_" . $i."', '0', 'Costo di imballaggio per questa zona', '6', '0', now())");
      }
   */
     // importazione dati da file /ext/modules/shipping/susa.csv  regioni,province, tariffe
      
      
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      $keys = array('MODULE_SHIPPING_SUSA_STATUS', 'MODULE_SHIPPING_SUSA_INOLTRO', 'MODULE_SHIPPING_SUSA_ZONE', 'MODULE_SHIPPING_SUSA_HANDLING', 'MODULE_SHIPPING_SUSA_SORT_ORDER');

   
      return $keys;
    }
  }
?>
