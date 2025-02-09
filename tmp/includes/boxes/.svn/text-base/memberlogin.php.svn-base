<?php
/*
  $Id: information.php,v 1.5 2002/01/11 22:04:06 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 osCommerce

  Released under the GNU General Public License
*/
	require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN_WELCOME);
	$skin=new pws_skin('boxes/'.basename(__FILE__,'.php').'.htm');
	$skin->set('user_is_logged', tep_session_is_registered('customer_id'));
	if (tep_session_is_registered('customer_id')) {
		$skin->set('user_is_logged', true);
		$skin->set('box_heading', BOX_HEADING_MEMBERLOGGED_IN);
		$query=tep_db_query("select customers_firstname from customers where customers_id='$customer_id'");
		$res=tep_db_fetch_array($query);
		$skin->set('text_welcome',LOGIN_BOX_WELCOME_TEXT);
		$skin->set('user_name',$res['customers_firstname']);
		$skin->set('myaccount', array(
			'text'=>LOGIN_BOX_WELCOME_MYACCOUNT
			,'link'=>tep_href_link(FILENAME_ACCOUNT, '', 'SSL')
			)
		);
		$skin->set('logout', array(
			'text'=>LOGIN_BOX_WELCOME_LOGOUT
			,'link'=>tep_href_link(FILENAME_LOGOFF)
			)
		);
	}else{
		$skin->set('user_is_logged', false);
		$skin->set('form',array(
				'name'=>'loginbox'
				,'id'=>'loginbox'
				,'action'=>tep_href_link(FILENAME_LOGIN,'action=process')
				,'method'=>'post'
			)
		);
		$skin->set('box_heading', BOX_HEADING_MEMBERLOGIN);
		$skin->set('text_email',LOGIN_BOX_WELCOME_EMAILADDRESS);
		$skin->set('text_password',LOGIN_BOX_WELCOME_PASSWORD);
		$skin->set('text_button_login',LOGIN_BOX_WELCOME_LOGIN);
		$skin->set('logout', array(
			'text'=>LOGIN_BOX_WELCOME_LOGOUT
			,'link'=>tep_href_link(FILENAME_LOGOFF)
			)
		);
		$skin->set('forgottenpwd', array(
			'text'=>LOGIN_BOX_PASSWORD_FORGOTTEN
			,'link'=>tep_href_link(FILENAME_PASSWORD_FORGOTTEN)
			)
		);
		$skin->set('register', array(
			'text'=>LOGIN_BOX_CREATE_ACCOUNT
			,'link'=>tep_href_link(FILENAME_CREATE_ACCOUNT)
			)
		);
	}
	echo $skin->execute();
?>