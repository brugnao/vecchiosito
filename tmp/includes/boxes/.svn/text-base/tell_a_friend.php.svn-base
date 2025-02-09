<?php
/*
  $Id: tell_a_friend.php,v 1.16 2003/06/10 18:26:33 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
	$skin=new pws_skin('boxes/'.basename(__FILE__,'.php').'.htm');
	$skin->set('box_heading',BOX_HEADING_TELL_A_FRIEND);
	$skin->set('form', array(
		'name'=>'tell_a_friend'
		,'id'=>'tell_a_friend'
		,'method'=>'get'
		,'action'=>	tep_href_link(FILENAME_TELL_A_FRIEND, '', 'NONSSL', false)
		)
	);
	$skin->set('session', array(
		'name'=>tep_session_name()
		,'id'=>tep_session_id()
		)
	);
	$skin->set('submit_image',tep_image_submit('button_tell_a_friend.gif', BOX_HEADING_TELL_A_FRIEND) );
	$skin->set('products_id', $HTTP_GET_VARS['products_id']);
	$skin->set('text',BOX_TELL_A_FRIEND_TEXT);
	echo $skin->execute();
?>