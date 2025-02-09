<?php
/*
  $Id: categories.php,v 1.25 2003/07/09 01:13:58 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  global $pws_engine;
  $output=$pws_engine->triggerHook('CATALOG_CATEGORIES_BOX');
  if ($output!=''){
	  echo $output;
  }else{
	  $skin=new pws_skin('boxes/'.basename(__FILE__,'.php').'.htm');
	  $skin->set('box_heading',BOX_HEADING_CATEGORIES);
	  $skin->set('categories',$pws_engine->getCategoriesTree(NULL,$cPath,false));
	  echo $skin->execute();
  }

?>
            </td>
          </tr>
<!-- categories_eof //-->
