<?php
/*
  $Id: address_book_process.php,v 1.79 2003/06/09 23:03:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// needs to be included earlier to set the success message in the messageStack
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADDRESS_BOOK_PROCESS);

  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'deleteconfirm') && isset($HTTP_GET_VARS['delete']) && is_numeric($HTTP_GET_VARS['delete'])) {
    tep_db_query("delete from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . (int)$HTTP_GET_VARS['delete'] . "' and customers_id = '" . (int)$customer_id . "'");

    $messageStack->add_session('addressbook', SUCCESS_ADDRESS_BOOK_ENTRY_DELETED, 'success');

    tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
  }

// error checking when updating or adding an entry
  $process = false;
  if (isset($_REQUEST['edit'])){
  	if (empty($_REQUEST['entry_type'])){
  		$query=tep_db_query("select entry_type from ".TABLE_ADDRESS_BOOK." ab where ab.address_book_id='".$_REQUEST['edit']."'");
		$query=tep_db_fetch_array($query);
		$entry_type=$query['entry_type'];
	}else
		$entry_type=$_REQUEST['entry_type'];
  }
  if (isset($HTTP_POST_VARS['action']) && (($HTTP_POST_VARS['action'] == 'process') || ($HTTP_POST_VARS['action'] == 'update'))) {
    $process = true;
    $error = false;

/*	if (empty($_REQUEST['entry_type'])){
		$query=tep_db_query("select entry_type from ".TABLE_CUSTOMERS." where customers_id=$customer_id");
		$query=tep_db_fetch_array($query);
		$entry_type=$query['entry_type'];
	}else
		$entry_type=$_REQUEST['entry_type'];
*/
    if (ACCOUNT_GENDER == 'true') $gender = tep_db_prepare_input($HTTP_POST_VARS['gender']);
    if (ACCOUNT_COMPANY == 'true') $company = tep_db_prepare_input($HTTP_POST_VARS['company']);
	 // BOF Separate Pricing Per Customer
    if ($entry_type=='company' && ACCOUNT_COMPANY == 'true' && isset($HTTP_POST_VARS['company_tax_id'])) {
	$company_tax_id = tep_db_prepare_input($HTTP_POST_VARS['company_tax_id']);
    }
    // EOF Separate Pricing Per Customer

	//PIVACF start
	if ($entry_type=='company' && ACCOUNT_PIVA == 'true') $piva = tep_db_prepare_input($HTTP_POST_VARS['piva']);
	if (ACCOUNT_CF == 'true') $cf = tep_db_prepare_input($HTTP_POST_VARS['cf']);
    //PIVACF end
	//BERSANI start
	if ($entry_type=='company' && ACCOUNT_COMPANY_CF == 'true') $company_cf = tep_db_prepare_input($HTTP_POST_VARS['company_cf']);
    //BERSANI end
    
	$firstname = tep_db_prepare_input($HTTP_POST_VARS['firstname']);
    $lastname = tep_db_prepare_input($HTTP_POST_VARS['lastname']);
    $street_address = tep_db_prepare_input($HTTP_POST_VARS['street_address']);
    if (ACCOUNT_SUBURB == 'true') $suburb = tep_db_prepare_input($HTTP_POST_VARS['suburb']);
    $postcode = tep_db_prepare_input($HTTP_POST_VARS['postcode']);
    $city = tep_db_prepare_input($HTTP_POST_VARS['city']);
    $country = tep_db_prepare_input($HTTP_POST_VARS['country']);
    $piva = tep_db_prepare_input($HTTP_POST_VARS['piva']);
    $entry_cf = tep_db_prepare_input($HTTP_POST_VARS['entry_cf']);
    $company_tax_id = tep_db_prepare_input($HTTP_POST_VARS['company_tax_id']);
        
    
    
    if (ACCOUNT_STATE == 'true') {
      if (isset($HTTP_POST_VARS['zone_id'])) {
        $zone_id = tep_db_prepare_input($HTTP_POST_VARS['zone_id']);
      } else {
        $zone_id = false;
      }
      $state = tep_db_prepare_input($HTTP_POST_VARS['state']);
    }

    if (ACCOUNT_GENDER == 'true') {
      if ( ($gender != 'm') && ($gender != 'f') ) {
        $error = true;

        $messageStack->add('addressbook', ENTRY_GENDER_ERROR);
      }
    }
	
	//PIVACF start
	if ($entry_type=='company' && ACCOUNT_PIVA == 'true'){
	  if (($piva == "") && (ACCOUNT_PIVA_REQ == 'true') && $country==105) {
	    $error = true;
		$messageStack->add('addressbook', ENTRY_PIVA_ERROR);
	  } else if ((strlen($piva) != 11) && ($piva != ""))  {
        $error = true;
        $messageStack->add('addressbook', ENTRY_PIVA_ERROR);
      } else if (strlen($piva) == 11) {
	    if( ! ereg("^[0-9]+$", $piva) ) {
	      $error = true;
	      $messageStack->add('addressbook', ENTRY_PIVA_ERROR);
        } else {
	      $s = 0;
		  for( $i = 0; $i <= 9; $i += 2 ) $s += ord($piva[$i]) - ord('0');
		  for( $i = 1; $i <= 9; $i += 2 ) {
		    $c = 2*( ord($piva[$i]) - ord('0') );
		    if( $c > 9 ) $c = $c - 9;
		    $s += $c;
	      }
	      if( ( 10 - $s%10 )%10 != ord($piva[10]) - ord('0') ) {
            $error = true;
            $messageStack->add('addressbook', ENTRY_PIVA_ERROR);
          }
	    }
	  }	
	}
	if (ACCOUNT_CF == 'true') {
	  if (($cf == "") && (ACCOUNT_CF_REQ == 'true') && $country==105) {
	    $error = true;
		$messageStack->add('addressbook', ENTRY_CF_ERROR);
	  } else if ((strlen($cf) != 16) && ($cf != "")) {
	    $error = true;
		$messageStack->add('addressbook', ENTRY_CF_ERROR);
	  } else if (strlen($cf) == 16) {
		$cf = strtoupper($cf);
		if( ! ereg("^[A-Z0-9]+$", $cf) ){
		  $error = true;
		  $messageStack->add('addressbook', ENTRY_CF_ERROR);
	    } else { 
		  $s = 0;
		  for( $i = 1; $i <= 13; $i += 2 ){
		    $c = $cf[$i];
		    if( '0' <= $c && $c <= '9' )
			  $s += ord($c) - ord('0');
		    else
			  $s += ord($c) - ord('A');
		  }
		  for( $i = 0; $i <= 14; $i += 2 ){
		    $c = $cf[$i];
		    switch( $c ){
		      case '0':  $s += 1;  break;
		      case '1':  $s += 0;  break;
		      case '2':  $s += 5;  break;
		      case '3':  $s += 7;  break;
		      case '4':  $s += 9;  break;
		      case '5':  $s += 13;  break;
		      case '6':  $s += 15;  break;
		      case '7':  $s += 17;  break;
		      case '8':  $s += 19;  break;
		      case '9':  $s += 21;  break;
		      case 'A':  $s += 1;  break;
		      case 'B':  $s += 0;  break;
		      case 'C':  $s += 5;  break;
		      case 'D':  $s += 7;  break;
		      case 'E':  $s += 9;  break;
		      case 'F':  $s += 13;  break;
		      case 'G':  $s += 15;  break;
		      case 'H':  $s += 17;  break;
		      case 'I':  $s += 19;  break;
		      case 'J':  $s += 21;  break;
		      case 'K':  $s += 2;  break;
		      case 'L':  $s += 4;  break;
		      case 'M':  $s += 18;  break;
		      case 'N':  $s += 20;  break;
		      case 'O':  $s += 11;  break;
		      case 'P':  $s += 3;  break;
		      case 'Q':  $s += 6;  break;
		      case 'R':  $s += 8;  break;
		      case 'S':  $s += 12;  break;
		      case 'T':  $s += 14;  break;
		      case 'U':  $s += 16;  break;
		      case 'V':  $s += 10;  break;
		      case 'W':  $s += 22;  break;
		      case 'X':  $s += 25;  break;
		      case 'Y':  $s += 24;  break;
		      case 'Z':  $s += 23;  break;
		    }
	      }
	      if( chr($s%26 + ord('A')) != $cf[15] ){
		    $error = true;
		    $messageStack->add('addressbook', ENTRY_CF_ERROR);
		  }
	    }
	  }
    }
	//PIVACF end
	// BERSANI start
	if ($entry_type=='company' &&  (ACCOUNT_COMPANY_CF == 'true') ){
	  if (($company_cf == "") && (ACCOUNT_COMPANY_CF_REQ == 'true') && $country==105) {
	    $error = true;
		$messageStack->add('addressbook', ENTRY_COMPANY_CF_ERROR);
	  } else if ((strlen($company_cf) != 11) && (strlen($company_cf)!=16) && $company_cf!='')  {
        $error = true;
        $messageStack->add('addressbook', ENTRY_COMPANY_CF_ERROR);
      } else if (strlen($company_cf) == 11) {
	    if( ! ereg("^[0-9]+$", $company_cf) ) {
	      $error = true;
	      $messageStack->add('addressbook', ENTRY_COMPANY_CF_ERROR);
        } else {
	      $s = 0;
		  for( $i = 0; $i <= 9; $i += 2 ) $s += ord($company_cf[$i]) - ord('0');
		  for( $i = 1; $i <= 9; $i += 2 ) {
		    $c = 2*( ord($company_cf[$i]) - ord('0') );
		    if( $c > 9 ) $c = $c - 9;
		    $s += $c;
	      }
	      if( ( 10 - $s%10 )%10 != ord($company_cf[10]) - ord('0') ) {
            $error = true;
            $messageStack->add('addressbook', ENTRY_COMPANY_CF_ERROR);
          }
	    }
	  } else if (strlen($company_cf) == 16)	{
		$company_cf = strtoupper($company_cf);
		if( ! ereg("^[A-Z0-9]+$", $company_cf) ){
		  $error = true;
		  $messageStack->add('addressbook', ENTRY_COMPANY_CF_ERROR);
	    } else { 
		  $s = 0;
		  for( $i = 1; $i <= 13; $i += 2 ){
		    $c = $company_cf[$i];
		    if( '0' <= $c && $c <= '9' )
			  $s += ord($c) - ord('0');
		    else
			  $s += ord($c) - ord('A');
		  }
		  for( $i = 0; $i <= 14; $i += 2 ){
		    $c = $company_cf[$i];
		    switch( $c ){
		      case '0':  $s += 1;  break;
		      case '1':  $s += 0;  break;
		      case '2':  $s += 5;  break;
		      case '3':  $s += 7;  break;
		      case '4':  $s += 9;  break;
		      case '5':  $s += 13;  break;
		      case '6':  $s += 15;  break;
		      case '7':  $s += 17;  break;
		      case '8':  $s += 19;  break;
		      case '9':  $s += 21;  break;
		      case 'A':  $s += 1;  break;
		      case 'B':  $s += 0;  break;
		      case 'C':  $s += 5;  break;
		      case 'D':  $s += 7;  break;
		      case 'E':  $s += 9;  break;
		      case 'F':  $s += 13;  break;
		      case 'G':  $s += 15;  break;
		      case 'H':  $s += 17;  break;
		      case 'I':  $s += 19;  break;
		      case 'J':  $s += 21;  break;
		      case 'K':  $s += 2;  break;
		      case 'L':  $s += 4;  break;
		      case 'M':  $s += 18;  break;
		      case 'N':  $s += 20;  break;
		      case 'O':  $s += 11;  break;
		      case 'P':  $s += 3;  break;
		      case 'Q':  $s += 6;  break;
		      case 'R':  $s += 8;  break;
		      case 'S':  $s += 12;  break;
		      case 'T':  $s += 14;  break;
		      case 'U':  $s += 16;  break;
		      case 'V':  $s += 10;  break;
		      case 'W':  $s += 22;  break;
		      case 'X':  $s += 25;  break;
		      case 'Y':  $s += 24;  break;
		      case 'Z':  $s += 23;  break;
		    }
	      }
	      if( chr($s%26 + ord('A')) != $company_cf[15] ){
		    $error = true;
		    $messageStack->add('addressbook', ENTRY_COMPANY_CF_ERROR);
		  }
	    }
	  }
	}
	// BERSANI stop
	
    if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('addressbook', ENTRY_FIRST_NAME_ERROR);
    }

    if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('addressbook', ENTRY_LAST_NAME_ERROR);
    }

    if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
      $error = true;

      $messageStack->add('addressbook', ENTRY_STREET_ADDRESS_ERROR);
    }

    if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
      $error = true;

      $messageStack->add('addressbook', ENTRY_POST_CODE_ERROR);
    }

    if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
      $error = true;

      $messageStack->add('addressbook', ENTRY_CITY_ERROR);
    }

    if (!is_numeric($country)) {
      $error = true;

      $messageStack->add('addressbook', ENTRY_COUNTRY_ERROR);
    }

    if (ACCOUNT_STATE == 'true') {
      $zone_id = 0;
      $check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "'");
      $check = tep_db_fetch_array($check_query);
      $entry_state_has_zones = ($check['total'] > 0);
      if ($entry_state_has_zones == true) {
        $zone_query = tep_db_query("select distinct zone_id from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' and (zone_name like '" . tep_db_input($state) . "%' or zone_code like '%" . tep_db_input($state) . "%')");
        if (tep_db_num_rows($zone_query) == 1) {
          $zone = tep_db_fetch_array($zone_query);
          $zone_id = $zone['zone_id'];
        } else {
          $error = true;

          $messageStack->add('addressbook', ENTRY_STATE_ERROR_SELECT);
        }
      } else {
        if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
          $error = true;

          $messageStack->add('addressbook', ENTRY_STATE_ERROR);
        }
      }
    }

    if ($error == false) {
      $sql_data_array = array(
							  'entry_type' => $entry_type,
							  'entry_firstname' => $firstname,
                              'entry_lastname' => $lastname,
                              'entry_street_address' => $street_address,
                              'entry_postcode' => $postcode,
                              'entry_city' => $city,
                              'entry_country_id' => (int)$country);

      if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
      if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
	// BOF Separate Pricing Per Customer
    //  if (ACCOUNT_COMPANY == 'true' && tep_not_null($company_tax_id)) {
      if ($entry_type=='company' && ACCOUNT_COMPANY == 'true') {
	      $sql_data_array['entry_company_tax_id'] = $company_tax_id;
      }
      else 
      	$sql_data_array['entry_company_tax_id'] = '';
      // EOF Separate Pricing Per Customer

  
	  //PIVACF start
	  if ($entry_type=='company' && ACCOUNT_PIVA == 'true') 
	  	$sql_data_array['entry_piva'] = $piva;
	  	else 
	  	$sql_data_array['entry_piva'] = '';
	  	
	  if (ACCOUNT_CF == 'true') $sql_data_array['entry_cf'] = $cf;
      //PIVACF end

      // BERSANI start
      if ($entry_type=='company' && ACCOUNT_COMPANY_CF == 'true') 
      	$sql_data_array['entry_company_cf']=$HTTP_POST_VARS['company_cf'];
       else 
        $sql_data_array['entry_company_cf']= '';
       
      // BERSANI stop

      if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;
      if (ACCOUNT_STATE == 'true') {
        if ($zone_id > 0) {
          $sql_data_array['entry_zone_id'] = (int)$zone_id;
          $sql_data_array['entry_state'] = '';
        } else {
          $sql_data_array['entry_zone_id'] = '0';
          $sql_data_array['entry_state'] = $state;
        }
      }
/*
 * debug
 * */
 /*	print_r($sql_data_array);
		print_r($HTTP_POST_VARS);
		print_r($HTTP_GET_VARS);
*/


      if ($HTTP_POST_VARS['action'] == 'update') {
        //tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', "address_book_id = '" . (int)$HTTP_GET_VARS['edit'] . "' and customers_id ='" . (int)$customer_id . "'");
		$check_query = tep_db_query("select address_book_id from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . (int)$HTTP_GET_VARS['edit'] . "' and customers_id = '" . (int)$customer_id . "' limit 1");
		
		if (tep_db_num_rows($check_query) == 1) {	
			tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', "address_book_id = '" . $HTTP_GET_VARS['edit'] . "' and customers_id ='" . $customer_id . "'");

//			print "address_book_id = '" . $HTTP_GET_VARS['edit'] . "' and customers_id ='" . $customer_id . "'";
//			exit;
			$pws_engine->triggerHook('CATALOG_ADDRESS_BOOK_UPDATE');
			// BOF Separate Pricing Per Customer: alert shop owner of tax id number added to an account
			      if ($entry_type=='company' && ACCOUNT_COMPANY == 'true' && tep_not_null($company_tax_id)) {
				      $sql_data_array2['customers_group_ra'] = '1';
			      tep_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id ='" . (int)$customer_id . "'");
			
			      // if you would *not* like to have an email when a tax id number has been entered in
			      // the appropriate field, comment out this section. The alert in admin is raised anyway
			
			      $alert_email_text = "Please note that " . $firstname . " " . $lastname . " of the company: " . $company . " has added a tax id number to his account information.";
			      tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, 'Tax id number added', $alert_email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
			      }
			// EOF Separate Pricing Per Customer: alert shop owner of account created by a company
	// reregister session variables
	        if ( (isset($HTTP_POST_VARS['primary']) && ($HTTP_POST_VARS['primary'] == 'on')) || ($HTTP_GET_VARS['edit'] == $customer_default_address_id) ) {
	          $customer_first_name = $firstname;
	          $customer_country_id = $country;
	          $customer_zone_id = (($zone_id > 0) ? (int)$zone_id : '0');
	          $customer_default_address_id = (int)$HTTP_GET_VARS['edit'];
	
	          $sql_data_array = array('customers_firstname' => $firstname,
	                                  'customers_lastname' => $lastname,
	                                  'customers_default_address_id' => (int)$HTTP_GET_VARS['edit']);
	
	          if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
	
	          tep_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int)$customer_id . "'");
	        }
	        $messageStack->add_session('addressbook', SUCCESS_ADDRESS_BOOK_ENTRY_UPDATED, 'success');
      	}
      } else {
        if (tep_count_customer_address_book_entries() < MAX_ADDRESS_BOOK_ENTRIES) {
	        $sql_data_array['customers_id'] = (int)$customer_id;
	        tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);
	        $new_address_book_id = tep_db_insert_id();

	// reregister session variables
	        if (isset($HTTP_POST_VARS['primary']) && ($HTTP_POST_VARS['primary'] == 'on')) {
	          $customer_first_name = $firstname;
	          $customer_country_id = $country;
	          $customer_zone_id = (($zone_id > 0) ? (int)$zone_id : '0');
	          if (isset($HTTP_POST_VARS['primary']) && ($HTTP_POST_VARS['primary'] == 'on')) $customer_default_address_id = $new_address_book_id;
	
	          $sql_data_array = array('customers_firstname' => $firstname,
	                                  'customers_lastname' => $lastname);
	
	          if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
	          if (isset($HTTP_POST_VARS['primary']) && ($HTTP_POST_VARS['primary'] == 'on')) $sql_data_array['customers_default_address_id'] = $new_address_book_id;
	
	          tep_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int)$customer_id . "'");
	        }
	        $messageStack->add_session('addressbook', SUCCESS_ADDRESS_BOOK_ENTRY_UPDATED, 'success');
        }
      }

      tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
    }
  }

 // if (isset($HTTP_GET_VARS['edit']) && is_numeric($HTTP_GET_VARS['edit'])) {
    
	//PIVACF start
//	$entry_query = tep_db_query("select entry_gender, entry_company, entry_piva, entry_cf, entry_firstname, entry_lastname, entry_street_address, entry_suburb, entry_postcode, entry_city, entry_state, entry_zone_id, entry_country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$HTTP_GET_VARS['edit'] . "'");
    //PIVACF end

// BOF Separate Pricing Per Customer
  if (isset($HTTP_GET_VARS['edit']) && is_numeric($HTTP_GET_VARS['edit'])) {
    $entry_query = tep_db_query("select entry_gender, entry_company, entry_piva, entry_cf, entry_company_cf, entry_company_tax_id, entry_firstname, entry_lastname, entry_street_address, entry_suburb, entry_postcode, entry_city, entry_state, entry_zone_id, entry_country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$HTTP_GET_VARS['edit'] . "'");
// EOF Separate Pricing Per Customer
   
    if (!tep_db_num_rows($entry_query)) {
      $messageStack->add_session('addressbook', ERROR_NONEXISTING_ADDRESS_BOOK_ENTRY);

      tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
    }

    $entry = tep_db_fetch_array($entry_query);
  } elseif (isset($HTTP_GET_VARS['delete']) && is_numeric($HTTP_GET_VARS['delete'])) {
    if ($HTTP_GET_VARS['delete'] == $customer_default_address_id) {
      $messageStack->add_session('addressbook', WARNING_PRIMARY_ADDRESS_DELETION, 'warning');

      tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
    } else {
      $check_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . (int)$HTTP_GET_VARS['delete'] . "' and customers_id = '" . (int)$customer_id . "'");
      $check = tep_db_fetch_array($check_query);

      if ($check['total'] < 1) {
        $messageStack->add_session('addressbook', ERROR_NONEXISTING_ADDRESS_BOOK_ENTRY);

        tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
      }
    }
  } else {
    $entry = array();
  }

  if (!isset($HTTP_GET_VARS['delete']) && !isset($HTTP_GET_VARS['edit'])) {
    if (tep_count_customer_address_book_entries() >= MAX_ADDRESS_BOOK_ENTRIES) {
      $messageStack->add_session('addressbook', ERROR_ADDRESS_BOOK_FULL);

      tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
    }
  }

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));

  if (isset($HTTP_GET_VARS['edit']) && is_numeric($HTTP_GET_VARS['edit'])) {
    $breadcrumb->add(NAVBAR_TITLE_MODIFY_ENTRY, tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'edit=' . $HTTP_GET_VARS['edit'], 'SSL'));
  } elseif (isset($HTTP_GET_VARS['delete']) && is_numeric($HTTP_GET_VARS['delete'])) {
    $breadcrumb->add(NAVBAR_TITLE_DELETE_ENTRY, tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'delete=' . $HTTP_GET_VARS['delete'], 'SSL'));
  } else {
    $breadcrumb->add(NAVBAR_TITLE_ADD_ENTRY, tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, '', 'SSL'));
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<?php
  if (!isset($HTTP_GET_VARS['delete'])) {
    include('includes/form_check.js.php');
  }
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><?php if (!isset($HTTP_GET_VARS['delete'])) echo tep_draw_form('addressbook', tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, (isset($HTTP_GET_VARS['edit']) ? 'edit=' . $HTTP_GET_VARS['edit'] : ''), 'SSL'), 'post', 'onSubmit="return check_form(addressbook);"'); ?><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php if (isset($HTTP_GET_VARS['edit'])) { echo HEADING_TITLE_MODIFY_ENTRY; } elseif (isset($HTTP_GET_VARS['delete'])) { echo HEADING_TITLE_DELETE_ENTRY; } else { echo HEADING_TITLE_ADD_ENTRY; } ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_address_book.gif', (isset($HTTP_GET_VARS['edit']) ? HEADING_TITLE_MODIFY_ENTRY : HEADING_TITLE_ADD_ENTRY), HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  if ($messageStack->size('addressbook') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('addressbook'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }

  if (isset($HTTP_GET_VARS['delete'])) {
?>
      <tr>
        <td class="main"><b><?php echo DELETE_ADDRESS_TITLE; ?></b></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" width="50%" valign="top"><?php echo DELETE_ADDRESS_DESCRIPTION; ?></td>
                <td align="right" width="50%" valign="top"><table border="0" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="main" align="center" valign="top"><b><?php echo SELECTED_ADDRESS; ?></b><br><?php echo tep_image(DIR_WS_IMAGES . 'arrow_south_east.gif'); ?></td>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" valign="top"><?php echo tep_address_label($customer_id, $HTTP_GET_VARS['delete'], true, ' ', '<br>'); ?></td>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td><?php echo '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'delete=' . $HTTP_GET_VARS['delete'] . '&action=deleteconfirm', 'SSL') . '">' . tep_image_button('button_delete.gif', IMAGE_BUTTON_DELETE) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
  } else {
?>
      <tr>
        <td><?php include(DIR_WS_MODULES . 'address_book_details.php'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
    if (isset($HTTP_GET_VARS['edit']) && is_numeric($HTTP_GET_VARS['edit'])) {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td><?php echo '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                <td align="right"><?php echo tep_draw_hidden_field('action', 'update') . tep_draw_hidden_field('edit', $HTTP_GET_VARS['edit']) . tep_image_submit('button_update.gif', IMAGE_BUTTON_UPDATE); ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
    } else {
      if (sizeof($navigation->snapshot) > 0) {
        $back_link = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), $navigation->snapshot['mode']);
      } else {
        $back_link = tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL');
      }
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td><?php echo '<a href="' . $back_link . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                <td align="right"><?php echo tep_draw_hidden_field('action', 'process') . tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>

<?php
    }
  }
?>
    </table><?php if (!isset($HTTP_GET_VARS['delete'])) echo '</form>'; ?></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
