<?php
/*
  $Id: product_notifications.php,v 1.8 2003/06/09 22:19:07 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

	if (isset($HTTP_GET_VARS['products_id'])) {
		$skin=new pws_skin('boxes/'.basename(__FILE__,'.php').'.htm');
		$skin->set('box_heading',BOX_HEADING_NOTIFICATIONS);
		if (tep_session_is_registered('customer_id')) {
			$check_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and customers_id = '" . (int)$customer_id . "'");
			$check = tep_db_fetch_array($check_query);
			
			$notification_exists = (($check['count'] > 0) ? true : false);
		} else {
			$notification_exists = false;
		}
		if ($notification_exists == true) {
			$skin->set('submit_image',array(
					'link'=>tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=notify_remove', $request_type)
					,'src'=>DIR_WS_IMAGES . 'box_products_notifications_remove.gif'
					,'alt'=>IMAGE_BUTTON_REMOVE_NOTIFICATIONS
					,'title'=>IMAGE_BUTTON_REMOVE_NOTIFICATIONS
				)
			);
			$skin->set('text',sprintf(BOX_NOTIFICATIONS_NOTIFY_REMOVE, tep_get_products_name($HTTP_GET_VARS['products_id'])));
		}else{
			$skin->set('submit_image',array(
					'link'=>tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=notify', $request_type) 
					,'src'=>DIR_WS_IMAGES . 'box_products_notifications.gif'
					,'alt'=>IMAGE_BUTTON_NOTIFICATIONS
					,'title'=>IMAGE_BUTTON_NOTIFICATIONS
				)
			);
			$skin->set('text',sprintf(BOX_NOTIFICATIONS_NOTIFY, tep_get_products_name($HTTP_GET_VARS['products_id'])) );
		}
		echo $skin->execute();
	}
?>