<?php
/*
  $Id: order_history.php,v 1.5 2003/06/09 22:18:30 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

	if (tep_session_is_registered('customer_id')) {
// retreive the last x products purchased
		$orders_query = tep_db_query("select distinct op.products_id from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_PRODUCTS . " p where o.customers_id = '" . (int)$customer_id . "' and o.orders_id = op.orders_id and op.products_id = p.products_id and p.products_status = '1' group by products_id order by o.date_purchased desc limit " . MAX_DISPLAY_PRODUCTS_IN_ORDER_HISTORY_BOX);
		if (tep_db_num_rows($orders_query)) {
			$skin=new pws_skin('boxes/'.basename(__FILE__,'.php').'.htm');
			$skin->set('box_heading',BOX_HEADING_CUSTOMER_ORDERS);
			$skin->set('cart_image',array(
				'src'=>DIR_WS_ICONS . 'cart.gif'
				,'alt'=>ICON_CART
				,'title'=>ICON_CART
				)
			);
			$product_ids = '';
			while ($orders = tep_db_fetch_array($orders_query)) {
				$product_ids .= (int)$orders['products_id'] . ',';
			}
			$product_ids = substr($product_ids, 0, -1);
			$products_query = tep_db_query("select products_id, products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id in (" . $product_ids . ") and language_id = '" . (int)$languages_id . "' order by products_name");
			$orders=array();
			while ($products=tep_db_fetch_array($products_query)) {
				$orders[]=array(
					'products_link'=>tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products['products_id'])
					,'products_name'=>$products['products_name']
					,'link'=>tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'products_id=' . $products['products_id']) 
				);
			}
			$skin->set('orders',$orders);
			echo $skin->execute();
		}
	}
?>

