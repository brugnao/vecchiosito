<?php
/*
  $Id: create_account.php,v 1.65 2003/06/09 23:03:54 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// needs to be included earlier to set the success message in the messageStack
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT);

  $process = false;
	if (empty($_REQUEST['entry_type']))
		$entry_type='private';
	else
		$entry_type=$_REQUEST['entry_type'];

	if (empty($HTTP_POST_VARS['country']) && $language == 'italian' )
 	{
 		$country = 105;
		$entry_state_has_zones = true;
 	}else{
		 $country = tep_db_prepare_input($HTTP_POST_VARS['country']);
 	}

  if (isset($HTTP_POST_VARS['action']) && ($HTTP_POST_VARS['action'] == 'process')) {
    $process = true;

    if (ACCOUNT_GENDER == 'true') {
      if (isset($HTTP_POST_VARS['gender'])) {
        $gender = tep_db_prepare_input($HTTP_POST_VARS['gender']);
      } else {
        $gender = false;
      }
    }
    $firstname = tep_db_prepare_input($HTTP_POST_VARS['firstname']);
    $lastname = tep_db_prepare_input($HTTP_POST_VARS['lastname']);
    if (ACCOUNT_DOB == 'true') $dob = tep_db_prepare_input($HTTP_POST_VARS['dob']);
    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
  //  if (ACCOUNT_COMPANY == 'true') $company = tep_db_prepare_input($HTTP_POST_VARS['company']);	
	// BOF Separate Pricing Per Customer, added: field for tax id number
    if ($entry_type=='company' && ACCOUNT_COMPANY == 'true') {
    $company = tep_db_prepare_input($HTTP_POST_VARS['company']);
    $company_tax_id = tep_db_prepare_input($HTTP_POST_VARS['company_tax_id']);
    }
    // EOF Separate Pricing Per Customer, added: field for tax id number

	//PIVACF start
	if ($entry_type=='company' && ACCOUNT_PIVA == 'true') $piva = tep_db_prepare_input($HTTP_POST_VARS['piva']);
	if (ACCOUNT_CF == 'true') $cf = tep_db_prepare_input($HTTP_POST_VARS['cf']);
    //PIVACF end
	// BERSANI start
	if ($entry_type=='company' && ACCOUNT_COMPANY_CF == 'true') $company_cf=tep_db_prepare_input($_REQUEST['company_cf']);
	// BERSANI stop
    
	$street_address = tep_db_prepare_input($HTTP_POST_VARS['street_address']);
    if (ACCOUNT_SUBURB == 'true') $suburb = tep_db_prepare_input($HTTP_POST_VARS['suburb']);
    $postcode = tep_db_prepare_input($HTTP_POST_VARS['postcode']);
    $city = tep_db_prepare_input($HTTP_POST_VARS['city']);
    if (ACCOUNT_STATE == 'true') {
      $state = tep_db_prepare_input($HTTP_POST_VARS['state']);
      if (isset($HTTP_POST_VARS['zone_id'])) {
        $zone_id = tep_db_prepare_input($HTTP_POST_VARS['zone_id']);
      } else {
        $zone_id = false;
      }
    }
    $telephone = tep_db_prepare_input($HTTP_POST_VARS['telephone']);
    $fax = tep_db_prepare_input($HTTP_POST_VARS['fax']);
    if (isset($HTTP_POST_VARS['newsletter'])) {
      $newsletter = tep_db_prepare_input($HTTP_POST_VARS['newsletter']);
    } else {
      $newsletter = false;
    }
    $password = tep_db_prepare_input($HTTP_POST_VARS['password']);
    $confirmation = tep_db_prepare_input($HTTP_POST_VARS['confirmation']);

    $error = false;
    //PrivacyStart

    if (ACCOUNT_PRIVACY == 'true') { 
	if ( !isset($HTTP_POST_VARS['privacy_accept']) || (isset($HTTP_POST_VARS['privacy_accept']) && ($HTTP_POST_VARS['privacy_accept']=='false')) ){
        	$error = true;

        	$messageStack->add('create_account', ENTRY_PRIVACY_ERROR);
    	}
    }

    //PrivacyEnd

    if (ACCOUNT_GENDER == 'true') {
      if ( ($gender != 'm') && ($gender != 'f') ) {
        $error = true;

        $messageStack->add('create_account', ENTRY_GENDER_ERROR);
      }
    }

    if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_FIRST_NAME_ERROR);
    }

    if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_LAST_NAME_ERROR);
    }

    if (ACCOUNT_DOB == 'true') {
      if (checkdate(substr(tep_date_raw($dob), 6, 2), substr(tep_date_raw($dob), 4, 2), substr(tep_date_raw($dob), 0, 4)) == false) {
        $error = true;

        $messageStack->add('create_account', ENTRY_DATE_OF_BIRTH_ERROR);
      }
    }

    if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR);
    } elseif (tep_validate_email($email_address) == false) {
      $error = true;

      $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
    } else {
//---PayPal WPP Modification START ---//	
      //$check_email_query = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");
      $check_email_query = tep_db_query("select customers_id as id, customers_paypal_ec as ec from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");
      if (tep_db_num_rows($check_email_query) > 0) {
        $check_email = tep_db_fetch_array($check_email_query);
        if ($check_email['ec'] == '1') {
          //It's a temp account, so delete it and let the user create a new one
          tep_db_query("delete from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$check_email['id'] . "'");
          tep_db_query("delete from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$check_email['id'] . "'");
          tep_db_query("delete from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$check_email['id'] . "'");
          tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$check_email['id'] . "'");
          tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$check_email['id'] . "'");
          tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where customer_id = '" . (int)$check_email['id'] . "'");
        } else {
          $error = true;

          $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
        }
//---PayPal WPP Modification END---//	
      }
    }

	//PIVACF start controlli solo per utenti italiani
	
	if ( $entry_type=='company' && (ACCOUNT_PIVA == 'true') && ($country==105) ){
	  if (($piva == "") && (ACCOUNT_PIVA_REQ == 'true')) {
	    $error = true;
		$messageStack->add('create_account', ENTRY_PIVA_ERROR);
	  } else if ((strlen($piva) != 11) && ($piva != ""))  {
        $error = true;
        $messageStack->add('create_account', ENTRY_PIVA_ERROR);
      } else if (strlen($piva) == 11) {
	    if( ! ereg("^[0-9]+$", $piva) ) {
	      $error = true;
	      $messageStack->add('create_account', ENTRY_PIVA_ERROR);
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
            $messageStack->add('create_account', ENTRY_PIVA_ERROR);
          }
	    }
	  }	
	}
	if (ACCOUNT_CF == 'true' && $country==105 &&  $entry_type=='private') {
	  if (($cf == "") && (ACCOUNT_CF_REQ == 'true')) {
	    $error = true;
		$messageStack->add('create_account', ENTRY_CF_ERROR);
	  } else if ((strlen($cf) != 16) && ($cf != "")) {
	    $error = true;
		$messageStack->add('create_account', ENTRY_CF_ERROR);
	  } else if (strlen($cf) == 16) {
		$cf = strtoupper($cf);
		if( ! ereg("^[A-Z0-9]+$", $cf) ){
		  $error = true;
		  $messageStack->add('create_account', ENTRY_CF_ERROR);
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
		    $messageStack->add('create_account', ENTRY_CF_ERROR);
		  }
	    }
	  }
    }
	//PIVACF end

	// BERSANI start
	if ( $entry_type=='company' && (ACCOUNT_COMPANY_CF == 'true')){
	  if (($company_cf == "")){
	  	if ((ACCOUNT_COMPANY_CF_REQ == 'true') && $country==105) {
	      $error = true;
		  $messageStack->add('create_account', ENTRY_COMPANY_CF_ERROR);
	  	}
	  } else if ((strlen($company_cf) != 11) && (strlen($company_cf)!=16) && $company_cf!='')  {
        $error = true;
        $messageStack->add('create_account', ENTRY_COMPANY_CF_ERROR);
      } else if (strlen($company_cf) == 11) {
	    if( ! ereg("^[0-9]+$", $company_cf) ) {
	      $error = true;
	      $messageStack->add('create_account', ENTRY_COMPANY_CF_ERROR);
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
            $messageStack->add('create_account', ENTRY_COMPANY_CF_ERROR);
          }
	    }
	  } else if (strlen($company_cf) == 16)	{
		$company_cf = strtoupper($company_cf);
		if( ! ereg("^[A-Z0-9]+$", $company_cf) ){
		  $error = true;
		  $messageStack->add('create_account', ENTRY_COMPANY_CF_ERROR);
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
		    $messageStack->add('create_account', ENTRY_COMPANY_CF_ERROR);
		  }
	    }
	  }
	}
	// BERSANI stop
	if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_STREET_ADDRESS_ERROR);
    }

    if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_POST_CODE_ERROR);
    }

    if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_CITY_ERROR);
    }

    if (is_numeric($country) == false) {
      $error = true;

      $messageStack->add('create_account', ENTRY_COUNTRY_ERROR);
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

          $messageStack->add('create_account', ENTRY_STATE_ERROR_SELECT);
        }
      } else {
        if (strlen($state) <= ENTRY_STATE_MIN_LENGTH) {
          $error = true;

          $messageStack->add('create_account', ENTRY_STATE_ERROR);
        }
      }
    }

    if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_TELEPHONE_NUMBER_ERROR);
    }


    if (strlen($password) < ENTRY_PASSWORD_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_PASSWORD_ERROR);
    } elseif ($password != $confirmation) {
      $error = true;

      $messageStack->add('create_account', ENTRY_PASSWORD_ERROR_NOT_MATCHING);
    }

    if ($error == false) {
      $sql_data_array = array(
							  'customers_firstname' => $firstname,
                              'customers_lastname' => $lastname,
                              'customers_email_address' => $email_address,
                              'customers_telephone' => $telephone,
                              'customers_fax' => $fax,
                              'customers_newsletter' => $newsletter,
                              'customers_password' => tep_encrypt_password($password));

      if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
     if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($dob);
// BOF Separate Pricing Per Customer
      // if you would like to have an alert in the admin section when either a company name has been entered in
      // the appropriate field or a tax id number, or both then uncomment the next line and comment the default
      // setting: only alert when a tax_id number has been given
      if ( (ACCOUNT_COMPANY == 'true' && tep_not_null($company) ) || (ACCOUNT_COMPANY == 'true' && tep_not_null($company_tax_id) ) ) {
	//  if ( ACCOUNT_COMPANY == 'true' && tep_not_null($company_tax_id)  ) {
      $sql_data_array['customers_group_ra'] = '1';
      }
      // EOF Separate Pricing Per Customer
      tep_db_perform(TABLE_CUSTOMERS, $sql_data_array);

      $customer_id = tep_db_insert_id();

      $sql_data_array = array('customers_id' => $customer_id,
                              'entry_type' => $entry_type,
							  'entry_firstname' => $firstname,
                              'entry_lastname' => $lastname,
                              'entry_street_address' => $street_address,
                              'entry_postcode' => $postcode,
                              'entry_city' => $city,
                              'entry_country_id' => $country);

      if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
   //   if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
	 if (ACCOUNT_COMPANY == 'true') { // BOF adapted for Separate Pricing Per Customer
      $sql_data_array['entry_company'] = $company;
      $sql_data_array['entry_company_tax_id'] = $company_tax_id;
      } // EOF adapted for Separate Pricing Per Customer
 
	  //PIVACF start
	  if (ACCOUNT_PIVA == 'true') $sql_data_array['entry_piva'] = $piva;
	  if (ACCOUNT_CF == 'true') $sql_data_array['entry_cf'] = $cf;
      //PIVACF end

      // BERSANI start
      if (ACCOUNT_COMPANY_CF == 'true') $sql_data_array['entry_company_cf']=$company_cf;
      // BERSANI stop

	  if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;
      if (ACCOUNT_STATE == 'true') {
        if ($zone_id > 0) {
          $sql_data_array['entry_zone_id'] = $zone_id;
          $sql_data_array['entry_state'] = '';
        } else {
          $sql_data_array['entry_zone_id'] = '0';
          $sql_data_array['entry_state'] = $state;
        }
      }

      tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

      $address_id = tep_db_insert_id();

      tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . (int)$address_id . "' where customers_id = '" . (int)$customer_id . "'");

      tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . (int)$customer_id . "', '0', now())");

      if (SESSION_RECREATE == 'True') {
        tep_session_recreate();
      }

      $customer_first_name = $firstname;
      $customer_default_address_id = $address_id;
      $customer_country_id = $country;
      $customer_zone_id = $zone_id;
      tep_session_register('customer_id');
      tep_session_register('customer_first_name');
      tep_session_register('customer_default_address_id');
      tep_session_register('customer_country_id');
      tep_session_register('customer_zone_id');

// restore cart contents
      $cart->restore_contents();

//PWS bof
        $pws_engine->triggerHook('CATALOG_ACCOUNT_CREATED');
//PWS eof
	
// build the message content
      $name = $firstname . ' ' . $lastname;

      if (ACCOUNT_GENDER == 'true') {
         if ($gender == 'm') {
           $email_text = sprintf(EMAIL_GREET_MR, $lastname);
         } else {
           $email_text = sprintf(EMAIL_GREET_MS, $lastname);
         }
      } else {
        $email_text = sprintf(EMAIL_GREET_NONE, $firstname);
      }

      $email_text .= EMAIL_WELCOME . EMAIL_TEXT . EMAIL_CONTACT . EMAIL_WARNING;
      tep_mail($name, $email_address, EMAIL_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
      // BOF Separate Pricing Per Customer: alert shop owner of account created by a company
      // if you would like to have an email when either a company name has been entered in
      // the appropriate field or a tax id number, or both then uncomment the next line and comment the default
      // setting: only email when a tax_id number has been given
  	    if ( (ACCOUNT_COMPANY == 'true' && tep_not_null($company) ) || (ACCOUNT_COMPANY == 'true' && tep_not_null($company_tax_id) ) ) {
    //  if ( ACCOUNT_COMPANY == 'true' && tep_not_null($company_tax_id) ) {
      $alert_email_text = "Notifica di registrazione:  " . $firstname . " " . $lastname . " della società: " . $company . " ha creato un nuovo account.";
      tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, 'Un nuovo account società è stato creato', $alert_email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
      }
	  // EOF Separate Pricing Per Customer: alert shop owner of account created by a company
	else
	  tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, "Registrazione Nuovo cliente su OSCommerce", "Nuova registrazione da ". $firstname . " " . $lastname ."." , $name, $email_address); 	
	
	  tep_redirect(tep_href_link(FILENAME_CREATE_ACCOUNT_SUCCESS, '', 'SSL'));
	}
  }

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<?php require('includes/form_check.js.php'); ?>
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
    <td width="100%" valign="top"><?php echo tep_draw_form('create_account', tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'), 'post', 'onSubmit="return check_form(create_account);"') . tep_draw_hidden_field('action', 'process'); ?><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_account.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="smallText"><br><?php echo sprintf(TEXT_ORIGIN_LOGIN, tep_href_link(FILENAME_LOGIN, tep_get_all_get_params(), 'SSL')); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  if ($messageStack->size('create_account') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('create_account'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }

  
if ( ALLOW_FINAL_CUSTOMERS == 'true')
{
  ?>
     
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="main"><b><?php echo CATEGORY_TYPE; ?></b></td>
           <td class="inputRequirement" align="right"><?php echo FORM_REQUIRED_INFORMATION; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" cellspacing="2" cellpadding="2">
              <tr>
                <td class="main"><?php echo ENTRY_TYPE; ?></td>
                <td class="main"><label for="entry_type_private"><input type="radio" name="entry_type" id="entry_type_private" value="private" <?=$entry_type=='private'?'checked':''?> onClick="set_entry_type('private')"/>&nbsp;&nbsp;<?=ENTRY_TYPE_PRIVATE?></label>&nbsp;&nbsp;<label for="entry_type_company"><input type="radio" name="entry_type" id="entry_type_company" value="company" <?=$entry_type=='company'?'checked':''?> onClick="set_entry_type('company')"/>&nbsp;&nbsp;<?=ENTRY_TYPE_COMPANY?></label>&nbsp;<span class="inputRequirement">*</span></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?
}
 ?>
      <tr>
        <td class="main"><b><?php echo CATEGORY_PERSONAL; ?></b></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" cellspacing="2" cellpadding="2">
<?php
  if (ACCOUNT_GENDER == 'true') {
?>
              <tr>
                <td class="main"><?php echo ENTRY_GENDER; ?></td>
                <td class="main"><?php echo tep_draw_radio_field('gender', 'm') . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f') . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . (tep_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">' . ENTRY_GENDER_TEXT . '</span>': ''); ?></td>
              </tr>
<?php
  }
?>
              <tr>
                <td class="main"><?php echo ENTRY_FIRST_NAME; ?></td>
                <td class="main"><?php echo tep_draw_input_field('firstname') . '&nbsp;' . (tep_not_null(ENTRY_FIRST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_FIRST_NAME_TEXT . '</span>': ''); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_LAST_NAME; ?></td>
                <td class="main"><?php echo tep_draw_input_field('lastname') . '&nbsp;' . (tep_not_null(ENTRY_LAST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_LAST_NAME_TEXT . '</span>': ''); ?></td>
              </tr>
<?php
  if (ACCOUNT_DOB == 'true') {
?>
              <tr>
                <td class="main"><?php echo ENTRY_DATE_OF_BIRTH; ?></td>
                <td class="main"><?php echo tep_draw_input_field('dob') . '&nbsp;' . (tep_not_null(ENTRY_DATE_OF_BIRTH_TEXT) ? '<span class="inputRequirement">' . ENTRY_DATE_OF_BIRTH_TEXT . '</span>': ''); ?></td>
              </tr>
<?php
  }
?>
              <tr>
                <td class="main"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
                <td class="main"><?php echo tep_draw_input_field('email_address') . '&nbsp;' . (tep_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>': ''); ?></td>
              </tr>

<!--PIVACF start-->
<?php  if (ACCOUNT_CF == 'true' ) {?>							
			  <tr>
                <td class="main"><?php echo ENTRY_CF; ?></td>
                <td class="main"><?php echo tep_draw_input_field('cf') . '&nbsp;' . ((tep_not_null(ENTRY_CF_TEXT) && (ACCOUNT_CF_REQ == 'true'))? '<span class="inputRequirement">' . ENTRY_CF_TEXT . '</span>': ''); ?></td>
              </tr>
<?php  }?>
<!--PIVACF end-->

            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
  if (ACCOUNT_COMPANY == 'true') {
?>
      <tr>
        <td><div id="companydiv" style="display:<?=$entry_type=='company'?'block':'none'?>"><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr><td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td></tr>
      	  <tr ><td class="main"><b><?php echo CATEGORY_COMPANY; ?></b></td></tr>
          <tr class="infoBoxContents">
            <td><table border="0" cellspacing="2" cellpadding="2">
              <tr>
                <td class="main"><?php echo ENTRY_COMPANY; ?></td>
 <td class="main"><?php echo tep_draw_input_field('company') . '&nbsp;' . (tep_not_null(ENTRY_COMPANY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COMPANY_TEXT . '</span>': ''); ?></td>
              </tr>
<!-- BOF Separate Pricing Per Customer: field for tax id number -->
              <tr>
                <td class="main"><?php echo ENTRY_COMPANY_TAX_ID; ?></td>
                <td class="main"><?php echo tep_draw_input_field('company_tax_id') . '&nbsp;' . (tep_not_null(ENTRY_COMPANY_TAX_ID_TEXT) ? '<span class="inputRequirement">' . ENTRY_COMPANY_TAX_ID_TEXT . '</span>': ''); ?></td>
              </tr>
<!-- EOF Separate Pricing Per Customer: field for tax id number -->



<!--PIVACF start-->
<?php  if (ACCOUNT_PIVA == 'true') { ?>
			  <tr>
                <td class="main"><?php echo ENTRY_PIVA; ?></td>
                <td class="main"><?php echo tep_draw_input_field('piva') . '&nbsp;' . ((tep_not_null(ENTRY_PIVA_TEXT) && (ACCOUNT_PIVA_REQ == 'true')) ? '<span class="inputRequirement">' . ENTRY_PIVA_TEXT . '</span>': ''); ?></td>
              </tr>
<?php  }?>
<!--PIVACF end-->
<!--BERSANI start-->
<?php  if (ACCOUNT_COMPANY_CF == 'true') {?>							
			  <tr>
                <td class="main"><?php echo ENTRY_COMPANY_CF; ?></td>
                <td class="main"><?php echo tep_draw_input_field('company_cf') . '&nbsp;' . ((tep_not_null(ENTRY_COMPANY_CF_TEXT) && (ACCOUNT_COMPANY_CF_REQ == 'true'))? '<span class="inputRequirement">' . ENTRY_COMPANY_CF_TEXT . '</span>': ''); ?></td>
              </tr>
<?php  }?>
<!--BERSANI end-->

            </table></td>
          </tr>
        </table></div></td>
      </tr>
<?php
  }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo CATEGORY_ADDRESS; ?></b></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" cellspacing="2" cellpadding="2">
              <tr>
                <td class="main"><?php echo ENTRY_STREET_ADDRESS; ?></td>
                <td class="main"><?php echo tep_draw_input_field('street_address') . '&nbsp;' . (tep_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_STREET_ADDRESS_TEXT . '</span>': ''); ?></td>
              </tr>
<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
              <tr>
                <td class="main"><?php echo ENTRY_SUBURB; ?></td>
                <td class="main"><?php echo tep_draw_input_field('suburb') . '&nbsp;' . (tep_not_null(ENTRY_SUBURB_TEXT) ? '<span class="inputRequirement">' . ENTRY_SUBURB_TEXT . '</span>': ''); ?></td>
              </tr>
<?php
  }
?>
              <tr>
                <td class="main"><?php echo ENTRY_POST_CODE; ?></td>
                <td class="main"><?php echo tep_draw_input_field('postcode') . '&nbsp;' . (tep_not_null(ENTRY_POST_CODE_TEXT) ? '<span class="inputRequirement">' . ENTRY_POST_CODE_TEXT . '</span>': ''); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_CITY; ?></td>
                <td class="main"><?php echo tep_draw_input_field('city') . '&nbsp;' . (tep_not_null(ENTRY_CITY_TEXT) ? '<span class="inputRequirement">' . ENTRY_CITY_TEXT . '</span>': ''); ?></td>
              </tr>
<?php
  if (ACCOUNT_STATE == 'true') {
?>
              <tr>
                <td class="main"><?php echo ENTRY_STATE; ?></td>
                <td class="main">
<?php
// modifica per far appartire di default le zones italia

// if ($process == true  ) 
// 	{
      if ($entry_state_has_zones == true) {
        $zones_array = array();
        $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' order by zone_name");
        while ($zones_values = tep_db_fetch_array($zones_query)) {
          $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
        }
        echo tep_draw_pull_down_menu('state', $zones_array);
      } else {
        echo tep_draw_input_field('state');
      }
//    } 
//    else 
//   {
//      echo tep_draw_input_field('state');
//    }
  if (tep_not_null(ENTRY_STATE_TEXT)) echo '&nbsp;<span class="inputRequirement">' . ENTRY_STATE_TEXT;
?>
                </td>
              </tr>
<?php
  }
?>
              <tr>
                <td class="main"><?php echo ENTRY_COUNTRY; ?></td>
                <td class="main"><?php echo tep_get_country_list('country',$country) . '&nbsp;' . (tep_not_null(ENTRY_COUNTRY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COUNTRY_TEXT . '</span>': ''); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo CATEGORY_CONTACT; ?></b></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" cellspacing="2" cellpadding="2">
              <tr>
                <td class="main"><?php echo ENTRY_TELEPHONE_NUMBER; ?></td>
                <td class="main"><?php echo tep_draw_input_field('telephone') . '&nbsp;' . (tep_not_null(ENTRY_TELEPHONE_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_TELEPHONE_NUMBER_TEXT . '</span>': ''); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_FAX_NUMBER; ?></td>
                <td class="main"><?php echo tep_draw_input_field('fax') . '&nbsp;' . (tep_not_null(ENTRY_FAX_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_FAX_NUMBER_TEXT . '</span>': ''); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo CATEGORY_OPTIONS; ?></b></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" cellspacing="2" cellpadding="2">
              <tr>
                <td class="main"><?php echo ENTRY_NEWSLETTER; ?></td>
                <td class="main"><?php echo tep_draw_checkbox_field('newsletter', '1', true) . '&nbsp;' . (tep_not_null(ENTRY_NEWSLETTER_TEXT) ? '<span class="inputRequirement">' . ENTRY_NEWSLETTER_TEXT . '</span>': ''); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo CATEGORY_PASSWORD; ?></b></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" cellspacing="2" cellpadding="2">
              <tr>
                <td class="main"><?php echo ENTRY_PASSWORD; ?></td>
                <td class="main"><?php echo tep_draw_password_field('password') . '&nbsp;' . (tep_not_null(ENTRY_PASSWORD_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_TEXT . '</span>': ''); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_PASSWORD_CONFIRMATION; ?></td>
                <td class="main"><?php echo tep_draw_password_field('confirmation') . '&nbsp;' . (tep_not_null(ENTRY_PASSWORD_CONFIRMATION_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_CONFIRMATION_TEXT . '</span>': ''); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <!--PrivacyStart-->
<?php  if (ACCOUNT_PRIVACY == 'true') { ?>
      <tr>
        <td class="main"><b><?php echo MUST_AGREE_TO_PRIVACY; ?></b></td>
      </tr>
      <tr>
        <td align=center><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td align=center><table  width="400" border="0" cellspacing="2" cellpadding="2">
              <tr>
                <td class="main" colspan="4"><?php echo tep_draw_textarea_field('privacy', 'soft', '48', '15', html_entity_decode(strip_tags(TEXT_PRIVACY_INFORMATION)), 'readonly', 'false'); ?></td>
              </tr>
              <tr>
                <td class="main" align=right><?php echo tep_draw_radio_field('privacy_accept', 'true'); ?></td>
                <td class="main"><?php echo AGREE; ?></td>
                <td class="main" align=right><?php echo tep_draw_radio_field('privacy_accept', 'false'); ?></td>
                <td class="main"><?php echo NOT_AGREE; ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php  }?>
<!--PrivacyEnd-->

      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td><?php if (file_exists(DIR_WS_LANGUAGES . $language . '/images/buttons/button_subscribe.gif'))
                			echo tep_image_submit('button_subscribe.gif', IMAGE_BUTTON_CONTINUE);
                		  else
                			echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></form></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php include(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php include(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
