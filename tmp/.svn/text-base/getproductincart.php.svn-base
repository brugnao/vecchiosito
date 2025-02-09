<?php  
 require('includes/application_top.php');

 
// /* debug
if ($GLOBALS['pws_prices']->displayPrices()==false)	{
				//	echo "msg: " . $GLOBALS['pws_prices']->getHtmlDiscountInfo((int)$_POST['products_id']);
			 	
		}
  else {   
			// mail("info@oscommerce.it", "ajax tornado bianco", "messaggio di chiamata POST:" . $_POST['azione'] . " POST:" . $_POST['products_id']);
			if (isset($_POST['products_id']) && $_POST['azione']=='add') // lo chiama quando col mouse passa esce dal pulsante buy
					{	
						if ($cart->get_quantity($_POST['products_id']) >= 1)
							echo $cart->get_quantity($_POST['products_id']) . ' x <a href="shopping_cart.php" title="'. BOX_HEADING_SHOPPING_CART . '" ><img border="0" alt="'. BOX_HEADING_SHOPPING_CART . '" src="' . DIR_WS_ICONS . 'cart_added_icon.gif"></a>';
					 		
					}
			if (isset($_POST['products_id']) && $_POST['azione']=='loading') //visualizza l'icona "loading..."
					{
							echo '<img  border="0" src="' . DIR_WS_ICONS . 'ajax_loader.gif"> loading...';
					}
  		}					
?>
