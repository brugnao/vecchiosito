<?php
/*
 * @filename:	
 * @version:	1.00
 * @project:	PWS
 *
 * @author:		Riccardo Roscilli <info@oscommerce.it>
 * @created:	25/ott/07
 * @modified:	25/ott/07 16:45:51
 *
 * @copyright:	2006-2007	Riccardo Roscilli @ PWS
 *
 * @desc:	
 *
 * @TODO:		
 */

    if (isset($cPath) && strpos('_', $cPath)) {
// check to see if there are deeper categories within the current category
      $category_links = array_reverse($cPath_array);
      for($i=0, $n=sizeof($category_links); $i<$n; $i++) {
// ################## Added Enable Disable Categorie #################
//        $categories_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$category_links[$i] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "'");
        $categories_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_status = '1' and c.parent_id = '" . (int)$category_links[$i] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "'");
        $categories = tep_db_fetch_array($categories_query);
        if ($categories['total'] < 1) {
          // do nothing, go through the loop
        } else {
// ################## Added Enable Disable Categorie #################
//          $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$category_links[$i] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
		$categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_status = '1' and c.parent_id = '" . (int)$category_links[$i] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
// ################## End Added Enable Disable Categorie #################
          break; // we've found the deepest category the customer is in
        }
      }
    } else {
// ################## End Added Enable Disable Categorie #################
//      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_status = '1' and c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
// ################## End Added Enable Disable Categorie #################
    }

    $number_of_categories = tep_db_num_rows($categories_query);

    $categories_ary=array();
    $categories_row=array();
    while ($categories = tep_db_fetch_array($categories_query)) {
      $cPath_new = tep_get_path($categories['categories_id']);
      $imgattrs=getThumbnail(DIR_WS_IMAGES . $categories['categories_image'],SUBCATEGORY_IMAGE_WIDTH,SUBCATEGORY_IMAGE_HEIGHT);
      $categories['empty_padding_cell']=false;
      $categories['categories_image']=$imgattrs['baseFileName'];
      $categories['categories_image_width']=$imgattrs['width'];
      $categories['categories_image_height']=$imgattrs['height'];
      $categories['categories_link']=tep_href_link(FILENAME_DEFAULT, $cPath_new);
      if (sizeof($categories_row)==MAX_DISPLAY_CATEGORIES_PER_ROW){
      	$categories_ary[]=$categories_row;
      	$categories_row=array();
      }
      $categories_row[]=$categories;
    }
    if (sizeof($categories_row)){
    	while (sizeof($categories_row)<MAX_DISPLAY_CATEGORIES_PER_ROW)
    		$categories_row[]=array('empty_padding_cell'=>true);
    	$categories_ary[]=$categories_row;
    }

// needed for the new products module shown below
    $new_products_category_id = $current_category_id;
    $skin=new pws_skin('modules/'.basename(__FILE__,'.php').'.htm');
	$skin->set('categories',$categories_ary);
	$skin->set('column_width',(int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW) . '%');
	echo $skin->execute();
?>