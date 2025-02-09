<?php
/*
  $Id: login.php,v 1.80 2003/06/05 23:28:24 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require_once('includes/application_top.php');

// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled (or the session has not started)
  if ($session_started == false) {
    tep_redirect(tep_href_link(FILENAME_COOKIE_USAGE));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);

  $error = false;
  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) {
    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
    $password = tep_db_prepare_input($HTTP_POST_VARS['password']);
// Check if email exists
 //   $check_customer_query = tep_db_query("select customers_id, customers_firstname, customers_password, customers_email_address, customers_default_address_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");
// BOF Separate Pricing per Customer
/*    $check_customer_query = tep_db_query("select customers_id, customers_firstname, customers_password, customers_email_address, customers_default_address_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'"); */
    $check_customer_query = tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");
// EOF Separate Pricing Per Customer

	if (!tep_db_num_rows($check_customer_query)) {
      $error = true;
    } else {
      $check_customer = tep_db_fetch_array($check_customer_query);
// Check that password is good
      if (!tep_validate_password($password, $check_customer['customers_password'])) {
        $error = true;
      } else {
      	
        if (SESSION_RECREATE == 'True') {
          tep_session_recreate();

        }

		// verifica codice cliente per i b2b puri 
		// requisiti per autenticare il cliente forzando il codice cliente:
		// 1. prezzi nascosti al gruppo clienti finali
		// 2. utente appartenente al gruppo utenti finali
		// 3. Codice cliente obbligatorio lato admin
		$customer_group_query = tep_db_query("Select * from customers_groups where customers_group_id = '" . $check_customer['customers_group_id'] . "'");
        $customer_group_array = tep_db_fetch_array($customer_group_query);

        if (tep_db_num_rows($customers_groups_query) == '0')
        	$sppc_customer_group_id = 0;
        	
     /* debug   
        print ACCOUNT_CUSTOMER_CODE_REQ;
        print "<br>";
        print $check_customer['customers_code'];
                print "<br>";
        print $check_customer['customers_group_id'];
                print "<br>";
        print $customer_group_array['customers_group_show_prices'];
                print "<br>";
        exit;
		*/
		if (  (ACCOUNT_CUSTOMER_CODE_REQ == 'true') && ($check_customer['customers_code'] == '') && ($check_customer['customers_group_id'] == '0') && ( $customer_group_array['customers_group_show_prices'] == '0') )
		{
			// lo reindirizziamo alla pagina di alert in attesa dell'assegnazione del codice
			 $page_query = tep_db_query("Select * from ". TABLE_PAGES_DESCRIPTION ." where pages_title like '%attivazione%' And language_id = '4'");
			 if(tep_db_num_rows($page_query) == '1')		 
			 {
			 	$page_array = tep_db_fetch_array($page_query);
			 }
			 else 	
			 {
			 	 $page_query = tep_db_query("Select * from ". TABLE_PAGES_DESCRIPTION ." where pages_title like '%registrarsi%' And language_id = '4'");
				 $page_array = tep_db_fetch_array($page_query);
			 }
			 
				// link 	 info_pages.php?pages_id=6
			 tep_redirect(tep_href_link("info_pages.php?pages_id=" . $page_array['pages_id'] .""));
		}
		
		
        $check_country_query = tep_db_query("select entry_country_id, entry_zone_id, entry_company_cf, entry_piva from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$check_customer['customers_id'] . "' and address_book_id = '" . (int)$check_customer['customers_default_address_id'] . "'");
        $check_country = tep_db_fetch_array($check_country_query);

        $customer_id = $check_customer['customers_id'];
        $customer_default_address_id = $check_customer['customers_default_address_id'];
        $customer_first_name = $check_customer['customers_firstname'];

        
        $customer_company_cf = $check_country['entry_company_cf'];
        $customer_piva = $check_country['entry_piva'];
        $customer_country_id = $check_country['entry_country_id'];
        $customer_zone_id = $check_country['entry_zone_id'];
        tep_session_register('customer_id');
        tep_session_register('customer_default_address_id');
        tep_session_register('customer_first_name');

        tep_session_register('customer_company_cf');
         tep_session_register('customer_piva');
        tep_session_register('customer_country_id');
        tep_session_register('customer_zone_id');
        tep_session_register('sppc_customer_group_id');
        
        // paypal express
		$ppec_mode = 'mark';
        tep_session_register('ppec_mode');

        // cookie da loggato serve per la cache
        setcookie('LoggedIn','yes',0,'/');
        
//PWS bof
        $pws_engine->triggerHook('CATALOG_LOGIN');
//PWS eof
        tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_of_last_logon = now(), customers_info_number_of_logons = customers_info_number_of_logons+1 where customers_info_id = '" . (int)$customer_id . "'");
       
// restore cart contents
        $cart->restore_contents();
        
        if (sizeof($navigation->snapshot) > 0) {
        	$origin_href = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), $navigation->snapshot['mode']);
          	//exit($origin_href);
          	$navigation->clear_snapshot();
          tep_redirect($origin_href);
        } else {
          tep_redirect(tep_href_link(FILENAME_DEFAULT));
        }
      }
    }
  }

  if ($error == true) {
    $messageStack->add('login', TEXT_LOGIN_ERROR);
  }

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_LOGIN, '', 'SSL'));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--
function session_win() {
  window.open("<?php echo tep_href_link(FILENAME_INFO_SHOPPING_CART); ?>","info_shopping_cart","height=460,width=430,toolbar=no,statusbar=no,scrollbars=yes").focus();
}
//--></script>
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
    <td width="100%" valign="top"><?php echo tep_draw_form('login', tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL')); ?><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_login.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  if ($messageStack->size('login') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('login'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }

  if ($cart->count_contents() > 0) {
?>
      <tr>
        <td class="smallText"><?php echo TEXT_VISITORS_CART; ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main" width="50%" valign="top"><b><?php echo HEADING_NEW_CUSTOMER; ?></b></td>
            <td class="main" width="50%" valign="top"><b><?php echo HEADING_RETURNING_CUSTOMER; ?></b></td>
          </tr>
          <tr>
            <td width="50%" height="100%" valign="top"><table border="0" width="100%" height="100%" cellspacing="1" cellpadding="2" class="infoBox">
              <tr class="infoBoxContents">
                <td><table border="0" width="100%" height="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                  </tr>
                  <tr>
                    <td class="main" valign="top"><?php echo TEXT_NEW_CUSTOMER . '<br><br>' . TEXT_NEW_CUSTOMER_INTRODUCTION; ?></td>
                  </tr>
                  <tr>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                  </tr>
                  <tr>
                    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                      <tr>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                        <td align="right">
                        <?php echo '<a href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL') . '">';
                         if (file_exists(DIR_WS_LANGUAGES . $language . '/images/buttons/button_subscribe.gif'))
                			echo '<img border="0" src="' . DIR_WS_LANGUAGES . $language . '/images/buttons/button_subscribe.gif">';
                		  else
                		  	echo '<img border="0" src="' . DIR_WS_LANGUAGES . $language . '/images/buttons/button_continue.gif">';
                		
                			
                			echo  '</a>'; ?>
                			</td>
                      
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                      </tr>
                      <!--Paypal start -->
                      <? // if  ($ec_enabled = tep_paypal_wpp_enabled())
                      if (MODULE_PAYMENT_PPEC_STATUS == "True" && ($cart->count_contents() > 0 ) && MODULE_PAYMENT_PPEC_ONLY_MARK == "Express" )
                      {
                       require(DIR_WS_LANGUAGES . $language . '/modules/payment/ppec.php');
                      	?>
                        <tr>
                          <td colspan="3" class="smallText"><hr /><?php echo TEXT_PAYPALWPP_EC_HEADER; ?></td>
                          
                        </tr>
                        <tr><?
                 			require('ppesetup.php'); 
							$ppesetup = new ppesetup($language);
							echo "<td align='right'><a href='".tep_href_link('ppeb.php', 'shec=a', 'SSL')."'>".$ppesetup->ima."</a></td>"; 
							?>    
				   </tr>
                      <?}?>
                      <!--Paypal end -->
                      
                    
                    </table></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
            <td width="50%" height="100%" valign="top"><table border="0" width="100%" height="100%" cellspacing="1" cellpadding="2" class="infoBox">
              <tr class="infoBoxContents">
                <td><table border="0" width="100%" height="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                  </tr>
                  <tr>
                    <td class="main" colspan="2"><?php echo TEXT_RETURNING_CUSTOMER; ?></td>
                  </tr>
                  <tr>
                    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><b><?php echo ENTRY_EMAIL_ADDRESS; ?></b></td>
                    <td class="main"><?php echo tep_draw_input_field('email_address'); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><b><?php echo ENTRY_PASSWORD; ?></b></td>
                    <td class="main"><?php echo tep_draw_password_field('password'); ?></td>
                  </tr>
                  <tr>
                    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                  </tr>
                  <tr>
                    <td class="smallText" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '">' . TEXT_PASSWORD_FORGOTTEN . '</a>'; ?></td>
                  </tr>
                  <tr>
                    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                  </tr>
                  <tr>
                    <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                      <tr>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                        <td align="right"><?php echo tep_image_submit('button_login.gif', IMAGE_BUTTON_LOGIN); ?></td>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                      </tr>
                     </table></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></form></td>
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
