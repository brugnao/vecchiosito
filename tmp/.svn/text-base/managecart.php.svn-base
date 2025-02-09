<?php  
 require('includes/application_top.php');

// /* debug

	
     
//	mail("info@oscommerce.it", "ajax tornado bianco", "messaggio di chiamata " .$_POST['products_id'] . $_POST['quantity']);
if (isset($_POST['products_id'])) // lo chiama quando aggiunge un prodotto al carrello
		{
	if ($GLOBALS['pws_prices']->displayPrices()==false)
			{
				
			}
	else
			{
				if ($_POST['quantity'] == '0')
						$cart->remove($_POST['products_id']);
					else
				 		$cart->add_cart($_POST['products_id'], $cart->get_quantity($_POST['products_id']) + $_POST['quantity']);
					
			}
		}


					// comportamento di default, quando viene chiamato da un visualizzatore del carrello					 
 										   $cart_contents_string = '';
										  if ($cart->count_contents() > 0) {
										    $cart_contents_string = '<table border="0" width="100%" cellspacing="0" cellpadding="0">';
										    $products = $cart->get_products();
										    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
										      $cart_contents_string .= '<tr><td align="right" valign="top" class="infoBoxContents">';
										
										      if ((tep_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $products[$i]['id'])) {
										        $cart_contents_string .= '<span class="newItemInCart">';
										      } else {
										        $cart_contents_string .= '<span class="infoBoxContents">';
										      }
										
										      $cart_contents_string .= $products[$i]['quantity'] . '&nbsp;x&nbsp;</span></td><td valign="top" class="infoBoxContents"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '">';
										
										      if ((tep_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $products[$i]['id'])) {
										        $cart_contents_string .= '<span class="newItemInCart">';
										      } else {
										        $cart_contents_string .= '<span class="infoBoxContents">';
										      }
										
										      $cart_contents_string .= $products[$i]['name'] . '</span></a></td></tr>';
										
										      if ((tep_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $products[$i]['id'])) {
										        tep_session_unregister('new_products_id_in_cart');
										      }
										    }
										    $cart_contents_string .= '</table>';
										  } else {
										    $cart_contents_string .= BOX_SHOPPING_CART_EMPTY;
										  }									  
										  
		 echo $cart_contents_string; 

										
?>