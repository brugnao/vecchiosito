<?php
/*
  $Id: currencies.php,v 1.16 2003/06/05 23:16:46 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

////
// Class to handle currencies
// TABLES: currencies
  class currencies {
    var $currencies;

// class constructor
    function currencies() {
      $this->currencies = array();
      $currencies_query = tep_db_query("select currencies_id, code, title, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, value from " . TABLE_CURRENCIES);
      while ($currencies = tep_db_fetch_array($currencies_query)) {
        $this->currencies[$currencies['code']] = array('currencies_id'=>$currencies['currencies_id'],
													   'title' => $currencies['title'],
                                                       'symbol_left' => $currencies['symbol_left'],
                                                       'symbol_right' => $currencies['symbol_right'],
                                                       'decimal_point' => $currencies['decimal_point'],
                                                       'thousands_point' => $currencies['thousands_point'],
                                                       'decimal_places' => $currencies['decimal_places'],
                                                       'value' => $currencies['value']);
      }
    }

// class methods
    function format($number, $calculate_currency_value = true, $currency_type = '', $currency_value = '') {
      global $currency;

      if (empty($currency_type)) $currency_type = $currency;

      if ($calculate_currency_value == true) {
        $rate = (tep_not_null($currency_value)) ? $currency_value : $this->currencies[$currency_type]['value'];
        $format_string = $this->currencies[$currency_type]['symbol_left'] . number_format(tep_round($number * $rate, $this->currencies[$currency_type]['decimal_places']), $this->currencies[$currency_type]['decimal_places'], $this->currencies[$currency_type]['decimal_point'], $this->currencies[$currency_type]['thousands_point']) . $this->currencies[$currency_type]['symbol_right'];
// if the selected currency is in the european euro-conversion and the default currency is euro,
// the currency will displayed in the national currency and euro currency
        if ( (DEFAULT_CURRENCY == 'EUR') && ($currency_type == 'DEM' || $currency_type == 'BEF' || $currency_type == 'LUF' || $currency_type == 'ESP' || $currency_type == 'FRF' || $currency_type == 'IEP' || $currency_type == 'ITL' || $currency_type == 'NLG' || $currency_type == 'ATS' || $currency_type == 'PTE' || $currency_type == 'FIM' || $currency_type == 'GRD') ) {
          $format_string .= ' <small>[' . $this->format($number, true, 'EUR') . ']</small>';
        }
      } else {
        $format_string = $this->currencies[$currency_type]['symbol_left'] . number_format(tep_round($number, $this->currencies[$currency_type]['decimal_places']), $this->currencies[$currency_type]['decimal_places'], $this->currencies[$currency_type]['decimal_point'], $this->currencies[$currency_type]['thousands_point']) . $this->currencies[$currency_type]['symbol_right'];
      }

      return $format_string;
    }

    // funzione richiamata dalla classe shopping cart, calcola il totale dei prezzi in visualizzazione del carrello
    function calculate_price($products_price, $products_tax, $quantity = 1) {
      global $currency , $sppc_customer_group_show_tax ;

      if (isset($sppc_customer_group_show_tax) &&  $sppc_customer_group_show_tax == '0')
       return tep_round(tep_add_tax($products_price, 0), $this->currencies[$currency]['decimal_places']) * $quantity;
      elseif(DISPLAY_PRICE_WITH_TAX == 'false')
             return tep_round(tep_add_tax($products_price, 0), $this->currencies[$currency]['decimal_places']) * $quantity;
      else
      	 return tep_round(tep_add_tax($products_price, $products_tax), $this->currencies[$currency]['decimal_places']) * $quantity;
    }

    function is_set($code) {
      if (isset($this->currencies[$code]) && tep_not_null($this->currencies[$code])) {
        return true;
      } else {
        return false;
      }
    }

    function get_value($code) {
      return $this->currencies[$code]['value'];
    }

    function get_decimal_places($code) {
      return $this->currencies[$code]['decimal_places'];
    }
		
    function display_price($products_price, $products_tax, $quantity = 1) {
      return $this->format($this->calculate_price($products_price, $products_tax, $quantity));
    }
    /*
     * @function display_price2
     * @obsolete
     * @todo:	Lasciata per compatibilità con vecchie versioni di pws < 0.3
     */
    function display_price2($products_price, $products_tax, $quantity = 1) {
     global $currency;
	 $currency_type=$currency;
	  $number=tep_add_tax($products_price, $products_tax) * $quantity;
 
      return number_format(tep_round($number, $this->currencies[$currency_type]['decimal_places']), $this->currencies[$currency_type]['decimal_places'], $this->currencies[$currency_type]['decimal_point'], $this->currencies[$currency_type]['thousands_point']);
     
    }
    
  }
?>
