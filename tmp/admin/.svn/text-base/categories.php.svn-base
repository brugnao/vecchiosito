<?php
/*
  $Id: categories.php,v 1.146 2003/07/11 14:40:27 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  /*
  $curprod = tep_db_fetch_array(tep_db_query('select t1.products_id, products_name, t1.products_quantity, t2.products_description,'
			.'t1.products_price, t1.products_model, t1.products_weight, '
			.'t1.products_shopwindow, t1.products_onlyshow, t1.products_tax_class_id, t1.products_image, '
			//.'t4.specials_new_products_price,t4.status as specials_price_status,'
			.'t3.manufacturers_name, t3.manufacturers_image '
			.' from '.TABLE_PRODUCTS.' t1'
			.' left join '.TABLE_PRODUCTS_DESCRIPTION.' t2 on (t1.products_id=t2.products_id)'
			.' left join '.TABLE_MANUFACTURERS.' t3 on (t1.manufacturers_id=t3.manufacturers_id)'
			//.' left join '.TABLE_SPECIALS.' t4 on (t1.products_id=t4.products_id)'
			.' where t2.language_id=' . $languages_id . ' and t1.products_id='.$_GET['pID']));
	//print_r($curprod);
  $pInfo = new objectInfo($curprod);
  
  //$pws_engine->triggerHook('ADMIN_LOAD_PRODUCT');
  $pws_prices->adminLoadProduct($pInfo);
  $price = $pws_prices->getBestPrice($pInfo->products_id);
  $rate = tep_get_tax_rate_value($pInfo->products_tax_class_id);
  if($rate>0) {
    $price = $price * (($rate / 100)+1);
  }
  echo $price . ' ('.($price*(1+10/100.0)).')';
  //exit;
  //echo '<table>';
  //$pws_prices->adminEditProduct($curprod['products_id'], $pInfo);
  //echo '</table>';
  //exit;
  //*/
// ICECAT {{
      $objIceCat = new icecat();
 //     $iceCat = $objIceCat->checkIfInstalled();
 $iceCat = file_exists("icecat_setup.php");
 
// ICECAT }}
  include("fckeditor/fckeditor.php") ;
// PRODUCT ATTRIBUTES CONTRIB
  if (file_exists(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCTS_ATTRIBUTES)) {
    include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCTS_ATTRIBUTES);
  }
// PRODUCT ATTRIBUTES CONTRIB

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $action = (isset($_REQUEST['action']) ? $_REQUEST['action'] : '');

// Ultimate SEO URLs BEGIN
// If the action will affect the cache entries
    if (eregi("(insert|update|setflag)", $action))
        include_once ('includes/reset_seo_cache.php');
// Ultimate SEO URLs END

  if (tep_not_null($action)) {
  // se esiste il file google_sitemap.php in admin genero la mappa su sitemap.xml ogni volta che aggiorna le categorie
if (file_exists( DIR_FS_ADMIN . "google_sitemap.php"))
	{
		$_GET["f"] = "sitemap.xml";
		include("google_sitemap.php");
	}  
	
// resetto la turbocache
	tep_reset_turbo_cache();
	
    switch ($action) {
      case 'setflag':
        if ( ($HTTP_GET_VARS['flag'] == '0') || ($HTTP_GET_VARS['flag'] == '1') ) {
          if (isset($HTTP_GET_VARS['pID'])) {
            tep_set_product_status($HTTP_GET_VARS['pID'], $HTTP_GET_VARS['flag']);
          }

          if (USE_CACHE == 'true') {
            tep_reset_cache_block('categories');
            tep_reset_cache_block('also_purchased');
	    tep_reset_cache_block('manufacturers');
          }
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $HTTP_GET_VARS['cPath'] . '&pID=' . $HTTP_GET_VARS['pID']));
        break;
// ####################### Added Categories Enable / Disable ###############
		case 'setflag_cat':
        		if ( ($HTTP_GET_VARS['flag'] == '0') || ($HTTP_GET_VARS['flag'] == '1') )
			{
	      		if (isset($HTTP_GET_VARS['cID']))
				{
            		tep_set_categories_status($HTTP_GET_VARS['cID'], $HTTP_GET_VARS['flag']);
    				}

          		if (USE_CACHE == 'true')
				{
            			tep_reset_cache_block('categories');
            			tep_reset_cache_block('also_purchased');
				tep_reset_cache_block('manufacturers');
          			}
        		}

	tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $HTTP_GET_VARS['cPath'] . '&cID=' . $HTTP_GET_VARS['cID']));
	break;
// ####################### End Categories Enable / Disable ###############
      case 'insert_category':
      case 'update_category':
        if (isset($HTTP_POST_VARS['categories_id'])) $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);
        $sort_order = tep_db_prepare_input($HTTP_POST_VARS['sort_order']);

// ####################### Added Categories Enable / Disable ###############
//        $sql_data_array = array('sort_order' => (int)$sort_order);
        $categories_status = tep_db_prepare_input($HTTP_POST_VARS['categories_status']);
        $sql_data_array = array('sort_order' => (int)$sort_order, 'categories_status' => $categories_status);
// ####################### End Added Categories Enable / Disable ###############

        if ($action == 'insert_category') {
          $insert_sql_data = array('parent_id' => $current_category_id,
                                   'date_added' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

          tep_db_perform(TABLE_CATEGORIES, $sql_data_array);

          $categories_id = tep_db_insert_id();
        } elseif ($action == 'update_category') {
          $update_sql_data = array('last_modified' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $update_sql_data);

          tep_db_perform(TABLE_CATEGORIES, $sql_data_array, 'update', "categories_id = '" . (int)$categories_id . "'");
        }

        $languages = tep_get_languages();
        $categories_name_array = $_REQUEST['categories_name'];
        $categories_seo_url_array = $_REQUEST['categories_seo_url'];
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          
          $language_id = $languages[$i]['id'];
          $sql_data_array = array('categories_name' => tep_db_prepare_input($categories_name_array[$language_id]),
                                  'categories_seo_url' => tep_db_prepare_input($categories_seo_url_array[$language_id]));
          if (0==tep_db_num_rows(tep_db_query("select categories_name from ".TABLE_CATEGORIES_DESCRIPTION." where categories_id='$categories_id' and language_id='$language_id'"))) {
            $insert_sql_data = array('categories_id' => $categories_id,
                                     'language_id' => $languages[$i]['id']);

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array);
          } elseif ($action == 'update_category') {
            tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array, 'update', "categories_id = '" . (int)$categories_id . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
          }
        }
		$categories_image = new upload('categories_image');
		$categories_image->set_destination( DIR_FS_CATALOG_IMAGES);
        if ($categories_image->parse() && $categories_image->save()) {
        	tep_db_query("update " . TABLE_CATEGORIES . " set categories_image = '" . tep_db_input($categories_image->filename) . "' where categories_id = '" . (int)$categories_id . "'");
        }
        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
	  	  tep_reset_cache_block('manufacturers');
        }
//PWS bof
		if ($action == 'insert_category')
		  $pws_engine->triggerHook('ADMIN_CATEGORIES_INSERT');
		else
		  $pws_engine->triggerHook('ADMIN_CATEGORIES_UPDATE');
//PWS eof
        
        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories_id));
        break;
      case 'delete_category_confirm':
        if (isset($HTTP_POST_VARS['categories_id'])) {
          $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);
          
          $categories = tep_get_category_tree($categories_id, '', '0', '', true);
          $products = array();
          $products_delete = array();

          for ($i=0, $n=sizeof($categories); $i<$n; $i++) {
            $product_ids_query = tep_db_query("select products_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$categories[$i]['id'] . "'");

            while ($product_ids = tep_db_fetch_array($product_ids_query)) {
              $products[$product_ids['products_id']]['categories'][] = $categories[$i]['id'];
            }
          }

          reset($products);
          while (list($key, $value) = each($products)) {
            $category_ids = '';

            for ($i=0, $n=sizeof($value['categories']); $i<$n; $i++) {
              $category_ids .= "'" . (int)$value['categories'][$i] . "', ";
            }
            $category_ids = substr($category_ids, 0, -2);

            $check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$key . "' and categories_id not in (" . $category_ids . ")");
            $check = tep_db_fetch_array($check_query);
            if ($check['total'] < '1') {
              $products_delete[$key] = $key;
            }
          }

// removing categories can be a lengthy process
          tep_set_time_limit(0);
          for ($i=0, $n=sizeof($categories); $i<$n; $i++) {
            tep_remove_category($categories[$i]['id']);
          }

          reset($products_delete);
          while (list($key) = each($products_delete)) {
            tep_remove_product($key);
          }
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
	  tep_reset_cache_block('manufacturers');
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath));
        break;
      case 'delete_product_confirm':
        if (isset($HTTP_POST_VARS['products_id']) && isset($HTTP_POST_VARS['product_categories']) && is_array($HTTP_POST_VARS['product_categories'])) {
          $product_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
          $product_categories = $HTTP_POST_VARS['product_categories'];

          for ($i=0, $n=sizeof($product_categories); $i<$n; $i++) {
            tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "' and categories_id = '" . (int)$product_categories[$i] . "'");
          }

          $product_categories_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "'");
          $product_categories = tep_db_fetch_array($product_categories_query);

          if ($product_categories['total'] == '0') {
            tep_remove_product($product_id);
            if(file_exists("product_extra_fields.php"))
                // START: Extra Fields Contribution
   				 tep_db_query("delete from " . TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS . " where products_id = " . (int)$product_id);
   				 // END: Extra Fields Contribution
            
          }
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
	  tep_reset_cache_block('manufacturers');
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath));
        break;
      case 'move_category_confirm':
        if (isset($HTTP_POST_VARS['categories_id']) && ($HTTP_POST_VARS['categories_id'] != $HTTP_POST_VARS['move_to_category_id'])) {
          $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);
          $new_parent_id = tep_db_prepare_input($HTTP_POST_VARS['move_to_category_id']);

          $path = explode('_', tep_get_generated_category_path_ids($new_parent_id));

          if (in_array($categories_id, $path)) {
            $messageStack->add_session(ERROR_CANNOT_MOVE_CATEGORY_TO_PARENT, 'error');

            tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories_id));
          } else {
            tep_db_query("update " . TABLE_CATEGORIES . " set parent_id = '" . (int)$new_parent_id . "', last_modified = now() where categories_id = '" . (int)$categories_id . "'");

            if (USE_CACHE == 'true') {
              tep_reset_cache_block('categories');
              tep_reset_cache_block('also_purchased');
	      tep_reset_cache_block('manufacturers');
            }

            tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $new_parent_id . '&cID=' . $categories_id));
          }
        }

        break;
      case 'move_product_confirm':
        $products_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
        $new_parent_id = tep_db_prepare_input($HTTP_POST_VARS['move_to_category_id']);

        $duplicate_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$new_parent_id . "'");
        $duplicate_check = tep_db_fetch_array($duplicate_check_query);
        if ($duplicate_check['total'] < 1) tep_db_query("update " . TABLE_PRODUCTS_TO_CATEGORIES . " set categories_id = '" . (int)$new_parent_id . "' where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$current_category_id . "'");

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
	  tep_reset_cache_block('manufacturers');
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $new_parent_id . '&pID=' . $products_id));
        break;
      case 'insert_product':
      case 'update_product':
      	
//PWS bof
      	if (isset($_REQUEST['subaction'])){
      		$products_id=$_REQUEST['pID'];
      		$pws_engine->triggerHook('ADMIN_UPDATE_PRODUCT_SUBSECTION');
			tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products_id));
      	}else{
//PWS eof
			if (isset($HTTP_POST_VARS['edit_x']) || isset($HTTP_POST_VARS['edit_y'])) {
				$action = 'new_product';
			} else {
				if (isset($HTTP_GET_VARS['pID'])) $products_id = tep_db_prepare_input($HTTP_GET_VARS['pID']);
					$products_date_available = tep_db_prepare_input($HTTP_POST_VARS['products_date_available']);

					$products_date_available = (date('Y-m-d') < $products_date_available) ? $products_date_available : 'null';

					$sql_data_array = array('products_quantity' => (int)tep_db_prepare_input($HTTP_POST_VARS['products_quantity']),
                                  'products_model' => tep_db_prepare_input($HTTP_POST_VARS['products_model']),
                                  'products_price' => tep_db_prepare_input($HTTP_POST_VARS['products_price']),
                                  'products_date_available' => $products_date_available,
                                  'products_weight' => (float)tep_db_prepare_input($HTTP_POST_VARS['products_weight']),
                                  'products_status' => tep_db_prepare_input($HTTP_POST_VARS['products_status']),
                                  'products_tax_class_id' => tep_db_prepare_input($HTTP_POST_VARS['products_tax_class_id']),
                                  'products_shopwindow'=>tep_db_prepare_input($_REQUEST['products_shopwindow']),
                                  'products_onlyshow'=>tep_db_prepare_input($_REQUEST['products_onlyshow']),
								  'manufacturers_id' => (int)tep_db_prepare_input($HTTP_POST_VARS['manufacturers_id']),
                                  'products_image'=>tep_db_prepare_input($HTTP_POST_VARS['products_image'])
					);
// ICECAT {{ vpn 
     				 $sql_data_array['vpn'] = tep_db_prepare_input($HTTP_POST_VARS['vpn']);
     				 $sql_data_array['EAN'] = tep_db_prepare_input($HTTP_POST_VARS['EAN']);
// ICECAT }}

					//if (isset($HTTP_POST_VARS['products_image']) && tep_not_null($HTTP_POST_VARS['products_image']) && ($HTTP_POST_VARS['products_image'] != 'none')) {
					//	$sql_data_array['products_image'] = tep_db_prepare_input($HTTP_POST_VARS['products_image']);
					//}

					if ($action == 'insert_product') {
						$insert_sql_data = array('products_date_added' => 'now()');

					$sql_data_array = array_merge($sql_data_array, $insert_sql_data);

					tep_db_perform(TABLE_PRODUCTS, $sql_data_array);
					$products_id = tep_db_insert_id();
            
            

					tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$products_id . "', '" . (int)$current_category_id . "')");
				} elseif ($action == 'update_product') {
					$update_sql_data = array('products_last_modified' => 'now()');

					// se l'immaine è remota non la aggiorno, caso esprinet
					$product_image_query = tep_db_query("select products_image from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "' ");
					$product_image_array = tep_db_fetch_array($product_image_query);
					
					if(strstr($product_image_array['products_image'],'http://'))
							unset ($sql_data_array['products_image']);
							
					$sql_data_array = array_merge($sql_data_array, $update_sql_data);

					tep_db_perform(TABLE_PRODUCTS, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "'");
				}
					/** AJAX Attribute Manager  **/ 
					  require_once('attributeManager/includes/attributeManagerUpdateAtomic.inc.php'); 
					/** AJAX Attribute Manager  end **/

				$languages = tep_get_languages();
				for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
					$language_id = $languages[$i]['id'];

					$sql_data_array = array('products_name' => tep_db_prepare_input($HTTP_POST_VARS['products_name'][$language_id]),
                                    'products_description' => tep_db_prepare_input($HTTP_POST_VARS['products_description'][$language_id]),
                                    'products_url' => tep_db_prepare_input($HTTP_POST_VARS['products_url'][$language_id]),
									'products_youtube_url' => tep_db_prepare_input($HTTP_POST_VARS['products_youtube_url'][$language_id]),
                                    'products_seo_url' => tep_db_prepare_input($HTTP_POST_VARS['products_seo_url'][$language_id]));
                                    

					if ($action == 'insert_product') {
						$insert_sql_data = array('products_id' => $products_id,
                                       'language_id' => $language_id);

						$sql_data_array = array_merge($sql_data_array, $insert_sql_data);

						tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array);
					} elseif ($action == 'update_product') {
						tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and language_id = '" . (int)$language_id . "'");
					}
					 @unlink(DIR_FS_CATALOG . "cache/".$products_id."_lang".$language_id. ".html");	   
   					 
				}

	 if(file_exists("product_extra_fields.php"))		
	 {
		  // START: Extra Fields Contribution
          $extra_fields_query = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS . " WHERE products_id = " . (int)$products_id);
          while ($products_extra_fields = tep_db_fetch_array($extra_fields_query)) {
            $extra_product_entry[$products_extra_fields['products_extra_fields_id']] = $products_extra_fields['products_extra_fields_value'];
          }

          if ($HTTP_POST_VARS['extra_field']) { // Check to see if there are any need to update extra fields.
            foreach ($HTTP_POST_VARS['extra_field'] as $key=>$val) {
              if (isset($extra_product_entry[$key])) { // an entry exists
                if ($val == '') tep_db_query("DELETE FROM " . TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS . " where products_id = " . (int)$products_id . " AND  products_extra_fields_id = " . (int)$key);
                else tep_db_query("UPDATE " . TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS . " SET products_extra_fields_value = '" . tep_db_input($val) . "' WHERE products_id = " . (int)$products_id . " AND  products_extra_fields_id = " . (int)$key);
              }
              else { // an entry does not exist
                if ($val != '') tep_db_query("INSERT INTO " . TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS . " (products_id, products_extra_fields_id, products_extra_fields_value) VALUES ('" . (int)$products_id . "', '" . (int)$key . "', '" . tep_db_input($val) . "')");
              }
            }
          } 
          // END: Extra Fields Contribution
	 }
// RELATED PRODUCTS start
//          mod_related_products::setRelatedProductsFromRequestVars($products_id);
// RELATED PRODUCTS stop
				$pws_prices->adminUpdateProduct($products_id);
        		$pws_engine->triggerHook('ADMIN_UPDATE_PRODUCT');
 
				if (USE_CACHE == 'true') {
					tep_reset_cache_block('categories');
					tep_reset_cache_block('also_purchased');
					tep_reset_cache_block('manufacturers');
				}

 // commented and replaced for product attributes contrib
				tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products_id));
//          tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products_id . '&action=new_product'));
// end replacement
			}
        }
        break;
      case 'copy_to_confirm':
        if (isset($HTTP_POST_VARS['products_id']) && isset($HTTP_POST_VARS['categories_id'])) {
          $products_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
          $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);

          if ($HTTP_POST_VARS['copy_as'] == 'link') {
            if ($categories_id != $current_category_id) {
              $check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$categories_id . "'");
              $check = tep_db_fetch_array($check_query);
              if ($check['total'] < '1') {
                tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$products_id . "', '" . (int)$categories_id . "')");
              }
            } else {
              $messageStack->add_session(ERROR_CANNOT_LINK_TO_SAME_CATEGORY, 'error');
            }
          } elseif ($HTTP_POST_VARS['copy_as'] == 'duplicate') {
          	
          $fields_query = mysql_query("SHOW COLUMNS FROM  " . TABLE_PRODUCTS );
			if (mysql_num_rows($fields_query) > 0) {
			
				$field_names = '';
			    while ($row = mysql_fetch_array($fields_query)) {
			    	/*
					    [0] => products_id
					    [Field] => products_id
					    [1] => int(11)
					    [Type] => int(11)
					    [2] => NO
					    [Null] => NO
					    [3] => PRI
					    [Key] => PRI
					    [4] => 
					    [Default] => 
					    [5] => auto_increment
					    [Extra] => auto_increment

				*/
			    	
			  if( ($row['Key'] == 'PRI')  && ($row['Extra'] == 'auto_increment') )  
							  $field_names .= 'NULL,';	
			  else $field_names .= $row['Field'] . ',';
			 
			    }
			}
			$field_names = substr($field_names, 0, -1);
			
			tep_db_query("insert into " . TABLE_PRODUCTS . "
			select " . $field_names . "
			from  " . TABLE_PRODUCTS . "
			where products_id = '" . (int)$products_id . "'");
			
			$dup_products_id  = mysql_insert_id();
			
			// metto la data a now del nuovo prodotto
			
			tep_db_query("update " . TABLE_PRODUCTS . "
			set products_date_added = now(), products_last_modified = NULL, products_date_available = NULL
			where products_id = '" . (int)$dup_products_id . "'");
			
			
		// Products description 
			
         $fields_query = mysql_query("SHOW COLUMNS FROM  " . TABLE_PRODUCTS_DESCRIPTION );
			if (mysql_num_rows($fields_query) > 0) {

				$field_names = '';
				
			    while ($row = mysql_fetch_array($fields_query)) {
	
	//	   if( ($row['Key'] == 'PRI')  && ($row['Extra'] == 'auto_increment') )  
	//						  $field_names .= 'NULL,';	
			 $field_names .= $row['Field'] . ',';
			 // print_r($row);
			    }
			}
			$field_names = substr($field_names, 0, -1);

//SEO            $description_query = tep_db_query("select language_id, products_name, products_description, products_url from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$products_id . "'");
            $description_query = tep_db_query("select " . $field_names . " from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$products_id . "'");
            $field_values = '';
             
            while ($description = tep_db_fetch_array($description_query)) {
            	// print_r($description);
            	$field_values = '';
            	foreach($description as $key => $value )
            	{
            	if($key == 'products_id') $value = $dup_products_id;
            	
            	 $field_values .= tep_db_input($value) . "', '";
            	}
              $field_values = substr($field_values, 0, -3);
              $field_values = "'".$field_values;
 
              //SEO              tep_db_query("insert into " . TABLE_PRODUCTS_DESCRIPTION . " (products_id, language_id, products_name, products_description, products_url, products_viewed) values ('" . (int)$dup_products_id . "', '" . (int)$description['language_id'] . "', '" . tep_db_input($description['products_name']) . "', '" . tep_db_input($description['products_description']) . "', '" . tep_db_input($description['products_url']) . "', '0')");
              tep_db_query("insert into " . TABLE_PRODUCTS_DESCRIPTION . " (" . $field_names . ") values (" . $field_values . ")");
            	
            }
           
            
            
            tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$dup_products_id . "', '" . (int)$categories_id . "')");
// RELATED PRODUCTS start
//			$mod_relatedproducts->copyReleatedProducts($products_id,$dup_products_id);
// RELATED PRODUCTS stop
			$pws_engine->triggerHook('ADMIN_COPY_PRODUCT');
			$pws_prices->adminCopyProduct($products_id,$dup_products_id);
            $products_id = $dup_products_id;
          }

          if (USE_CACHE == 'true') {
            tep_reset_cache_block('categories');
            tep_reset_cache_block('also_purchased');
	   		tep_reset_cache_block('manufacturers');
          }
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $categories_id . '&pID=' . $products_id));
        break;
      case 'new_product_preview':
			$pws_engine->triggerHook('ADMIN_NEW_PRODUCT_PREVIEW');
        break;
    }
  }
  if ($action == 'new_product') {
    $parameters = array('products_name' => '',
                       'products_description' => '',
                       'products_url' => '',
//SEO start
                       'products_seo_url' => '',
//SEO stop
// youtube	
					   'products_youtube_url',
                       'products_id' => '',
                       'products_quantity' => '',
                       'products_model' => '',
                       'products_image' => '',
                       'products_price' => '',
                       'products_weight' => '',
                       'products_date_added' => '',
                       'products_last_modified' => '',
                       'products_date_available' => '',
                       'products_status' => '',
                           'products_tax_class_id' => '',
    					'products_shopwindow'=>'0',
    					'products_onlyshow'=>'0',
                       'manufacturers_id' => '');
// ICECAT {{
     $parameters['vpn'] = '';
     $parameters['EAN'] = '';
// ICECAT }}
    $pInfo = new objectInfo($parameters);
// commented and replaced for product attributes contrib
//    if (isset($HTTP_GET_VARS['pID']) && empty($HTTP_POST_VARS)) {
    if (isset($HTTP_GET_VARS['pID']) && ( empty($HTTP_POST_VARS) || $HTTP_GET_VARS['action_att']) ) {
// end replacement         
//SEO $product_query = tep_db_query("select pd.products_name, pd.products_description, pd.products_url, p.products_id, p.products_quantity, p.products_model, p.products_image, p.products_price, p.products_weight, p.products_date_added, p.products_last_modified, date_format(p.products_date_available, '%Y-%m-%d') as products_date_available, p.products_status, p.products_tax_class_id, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$HTTP_GET_VARS['pID'] . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");
      $product_query = tep_db_query("select p.vpn, p.EAN, p.products_shopwindow, p.products_onlyshow, pd.products_name, pd.products_seo_url, pd.products_description, pd.products_url, pd.products_youtube_url, p.products_id, p.products_quantity, p.products_model, p.products_image, p.products_price, p.products_weight, p.products_date_added, p.products_last_modified, date_format(p.products_date_available, '%Y-%m-%d') as products_date_available, p.products_status, p.products_tax_class_id, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$HTTP_GET_VARS['pID'] . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");
      $product = tep_db_fetch_array($product_query);

 if(file_exists("product_extra_fields.php"))   
 {  
// START: Extra Fields Contribution	  
      $products_extra_fields_query = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS . " WHERE products_id=" . (int)$HTTP_GET_VARS['pID']);
      while ($products_extra_fields = tep_db_fetch_array($products_extra_fields_query)) {
        $extra_field[$products_extra_fields['products_extra_fields_id']] = $products_extra_fields['products_extra_fields_value'];
      }
	  $extra_field_array=array('extra_field'=>$extra_field);
	  $pInfo->objectInfo($extra_field_array);
// END: Extra Fields Contribution	
    }//*/
      
      $pInfo->objectInfo($product);
      $pws_engine->triggerHook('ADMIN_LOAD_PRODUCT');
      $pws_prices->adminLoadProduct(&$pInfo);
    } elseif (tep_not_null($HTTP_POST_VARS)) {
      $pInfo->objectInfo($HTTP_POST_VARS);
      $pInfo->products_id=$_REQUEST['pID'];
      $products_name = $HTTP_POST_VARS['products_name'];
      $products_description = $HTTP_POST_VARS['products_description'];
      $products_url = $HTTP_POST_VARS['products_url'];
      $products_seo_url = $HTTP_POST_VARS['products_seo_url'];
    }else{
    	$pws_prices->adminNewProduct(&$pInfo);
    	$pws_engine->triggerHook('ADMIN_NEW_PRODUCT');
    }
  }
// check if the catalog image directory exists
  if (is_dir(DIR_FS_CATALOG_IMAGES)) {
    if (!is_writeable(DIR_FS_CATALOG_IMAGES)) $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
  } else {
    $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>



<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<style type="text/css">
<?=$pws_engine->triggerHook('ADMIN_STYLESHEET').
$pws_prices->adminStylesheet(&$pInfo)?>
</style>

<script language="javascript" src="includes/general.js" type="text/javascript"></script>
<!-- AJAX Attribute Manager  -->
<?php require_once( 'attributeManager/includes/attributeManagerHeader.inc.php' )?>
<!-- AJAX Attribute Manager  end -->

<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript"><!--
  var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "new_product", "products_date_available","btnDate1","<?php echo $pInfo->products_date_available; ?>",scBTNMODE_CUSTOMBLUE);
//--></script>
<?  if (isset($HTTP_GET_VARS['pID']) || ($HTTP_GET_VARS['action'] == 'new_product') )
		echo $pws_engine->triggerHook('ADMIN_CATEGORIES_HEAD')?>
<script language="javascript" type="text/javascript" defer><!-- 
<?  if (isset($HTTP_GET_VARS['pID']) || ($HTTP_GET_VARS['action'] == 'new_product') )
 		echo $pws_engine->triggerHook('ADMIN_CATEGORIES_JAVASCRIPT')?>
// --></script>

<script language="javascript" type="text/javascript"><!--
<?php
    $tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
    $tax_class_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
    while ($tax_class = tep_db_fetch_array($tax_class_query)) {
      $tax_class_array[] = array('id' => $tax_class['tax_class_id'],
                                 'text' => $tax_class['tax_class_title']);
    }
?>
var tax_rates = new Array();
<?php
	echo "//	Tax Rates\r\n";
	for ($i=0, $n=sizeof($tax_class_array); $i<$n; $i++) {
      if ($tax_class_array[$i]['id'] > 0) {
        echo 'tax_rates["' . $tax_class_array[$i]['id'] . '"] = ' . tep_get_tax_rate_value($tax_class_array[$i]['id']) . ';' . "\n";
      }
    }
?>

//--></script>
<script language="javascript" type="text/javascript" defer><!-- 
<?=$pws_prices->adminJavascript(&$pInfo)?>
// --></script>
<script language="javascript" type="text/javascript"><!-- 
<?=$pws_prices->adminJavascriptPre(&$pInfo)?>
// --></script>
<script type="text/javascript">
var ajaxFile = '<?=($HTTP_GET_VARS['action']!='new_product'?'related_ajax.php':'/'.DIR_WS_ICECAT.'/icecat_ajax.php')?>';
</script>
<?php
// ICECAT {{
require_once(DIR_FS_ICECAT . 'admin/header.php');
// ICECAT }}
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="goOnLoad();">
<div id="spiffycalendar" class="text"></div>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top">
<?php
  if ($action == 'new_product') {
    $manufacturers_array = array(array('id' => '', 'text' => TEXT_NONE));
    $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
    while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
      $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'],
                                     'text' => $manufacturers['manufacturers_name']);
    }

    $languages = tep_get_languages();

    if (!isset($pInfo->products_status)) $pInfo->products_status = '1';
    switch ($pInfo->products_status) {
      case '0': $in_status = false; $out_status = true; break;
      case '1':
      default: $in_status = true; $out_status = false;
    }
	if (!isset($pInfo->products_shopwindow)) $pInfo->products_shopwindow = '0';
    switch ($pInfo->products_shopwindow) {
      case '0': $sw_yep = false; $sw_nay = true; break;
      case '1':
      default: $sw_yep = true; $sw_nay = false;
    }
  	if (!isset($pInfo->products_onlyshow)) $pInfo->products_onlyshow = '0';
    switch ($pInfo->products_onlyshow) {
      case '0': $onlyshow_status_yes = false; $onlyshow_status_no = true; break;
      case '1':
      default: $onlyshow_status_yes = true; $onlyshow_status_no = false;
    }
?>
    <?php echo tep_draw_form('new_product', FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '') /*. '&action=new_product_preview'*/, 'post', 'enctype="multipart/form-data"'); ?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo sprintf(TEXT_NEW_PRODUCT, tep_output_generated_category_path($current_category_id)); ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
<?php if (!isset($_REQUEST['subaction'])){?>
			<input type="hidden" name="action" value="new_product_preview"/>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_STATUS; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('products_status', '1', $in_status) . '&nbsp;' . TEXT_PRODUCT_AVAILABLE . '&nbsp;' . tep_draw_radio_field('products_status', '0', $out_status) . '&nbsp;' . TEXT_PRODUCT_NOT_AVAILABLE; ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_SHOPWINDOW; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . ' ' . tep_draw_radio_field('products_shopwindow', '1', $sw_yep) . ' ' . TEXT_YES . ' ' . tep_draw_radio_field('products_shopwindow', '0', $sw_nay) . ' ' . TEXT_NO; ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_ONLYSHOW; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . ' ' . tep_draw_radio_field('products_onlyshow', '1', $onlyshow_status_yes) . ' ' . TEXT_YES . ' ' . tep_draw_radio_field('products_onlyshow', '0', $onlyshow_status_no) . ' ' . TEXT_NO; ?></td>
          </tr>

          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_DATE_AVAILABLE; ?><br><small>(YYYY-MM-DD)</small></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;'; ?><script language="javascript">dateAvailable.writeControl(); dateAvailable.dateFormat="yyyy-MM-dd";</script></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_MANUFACTURER; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('manufacturers_id', $manufacturers_array, $pInfo->manufacturers_id); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<?php
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td class="main"><?php if ($i == 0) echo TEXT_PRODUCTS_NAME; ?></td>
            <td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('products_name[' . $languages[$i]['id'] . ']', (isset($products_name[$languages[$i]['id']]) ? $products_name[$languages[$i]['id']] : tep_get_products_name($pInfo->products_id, $languages[$i]['id']))); ?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<?php
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
      <tr>
        <td class="main"><?php if ($i == 0) echo TEXT_PRODUCTS_SEO_URL; ?></td>
        <td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('products_seo_url[' . $languages[$i]['id'] . ']', (isset($products_seo_url[$languages[$i]['id']]) ? $products_seo_url[$languages[$i]['id']] : tep_get_products_seo_url($pInfo->products_id, $languages[$i]['id']))); ?></td>
       </tr>
<?php
    }
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr bgcolor="#ebebff">
            <td class="main"><?php echo TEXT_PRODUCTS_TAX_CLASS; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('products_tax_class_id', $tax_class_array, $pInfo->products_tax_class_id, 'onchange="updateGross()"'); ?></td>
          </tr>
          <tr bgcolor="#ebebff">
            <td class="main"><?php echo TEXT_PRODUCTS_PRICE_NET; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_price', $pInfo->products_price, 'onKeyUp="updateGross()"'); ?></td>
          </tr>
          <tr bgcolor="#ebebff">
            <td class="main"><?php echo TEXT_PRODUCTS_PRICE_GROSS; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_price_gross', $pInfo->products_price, 'onKeyUp="updateNet()"'); ?></td>
          </tr>
          <tr bgcolor="#ebebff">
            <td class="main"><?php echo VPN; ?></td>
            <td class="main" valign="top"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' .
		     tep_draw_input_field('vpn', $pInfo->vpn, 'id="vpn"') ;
            ?></td>
          </tr>
         <tr bgcolor="#ebebff">
            <td class="main"><?php echo EAN; ?></td>
            <td class="main" valign="top"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' .
		     tep_draw_input_field('EAN', $pInfo->EAN, 'id="EAN"') ;
            ?></td>
          </tr> 
          
          
<?php
// ICECAT {{
          if(false) {
         // 	print "icecat = " . $iceCat;
          	
     
          	for($i=0;$i<sizeof($languages);$i++) {
          		?><tr bgcolor="#ebebff" valign="top" align="right"><td><?php
          		echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;
          	</td>
          	<td class="prodDescArea"><div id="icecatADesc<?=$languages[$i]['code']?>" class="icecatADesc"></div></td>
          </tr><?php
          	}
          	?>
<script type="text/javascript">
var vpnVal = document.getElementById('vpn');
vpnVal = encodeURIComponent(vpnVal.value);
var mVal = document.getElementById('manufacturers_id');
mVal = mVal.value;
doLoadLang('pid=<?=$pInfo->products_id?>&vpn='+vpnVal+'&mID='+mVal,null);
</script>
          <?php
          }
// ICECAT }}
          ?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<script language="javascript" type="text/javascript" defer>//<!--
updateGross();
//--></script>


<?=$pws_prices->adminEditProduct($pInfo->products_id,$pInfo)?>




<?php
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td class="main" valign="top"><?php if ($i == 0) echo TEXT_PRODUCTS_DESCRIPTION; ?></td>
            <td><table border="0" cellspacing="0" cellpadding="0" width="680">
              <tr>
                <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
                <td class="main"><?php
                   tep_draw_fckeditor('products_description[' . $languages[$i]['id'] . ']', '650', '300', (isset($products_description[$languages[$i]['id']]) ? $products_description[$languages[$i]['id']] : tep_get_products_description($pInfo->products_id, $languages[$i]['id']))); 
                // echo tep_draw_textarea_field('products_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($products_description[$languages[$i]['id']]) ? $products_description[$languages[$i]['id']] : tep_get_products_description($pInfo->products_id, $languages[$i]['id']))); ?></td>
              </tr>
            </table></td>
          </tr>
<?php
    }
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_QUANTITY; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_quantity', $pInfo->products_quantity); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_MODEL; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_model', $pInfo->products_model); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		  <tr valign="top">
		  	<td class="main"><?=TEXT_PRODUCTS_IMAGE?></td>
		    <td class="main"><?=$pws_html->drawImagePickerPlacehold('products_image',$pInfo->products_image)?></td>
		  </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<?=$pws_engine->triggerHook('ADMIN_EDIT_PRODUCTS_IMAGES')?>
<?php
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td class="main"><?php if ($i == 0) echo TEXT_PRODUCTS_URL . '<br><small>' . TEXT_PRODUCTS_URL_WITHOUT_HTTP . '</small>'; ?></td>
            <td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('products_url[' . $languages[$i]['id'] . ']', (isset($products_url[$languages[$i]['id']]) ? $products_url[$languages[$i]['id']] : tep_get_products_url($pInfo->products_id, $languages[$i]['id'])), 'size=100'); ?></td>
          </tr>
<?php
    }
    
// link youtube
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td class="main"><?php if ($i == 0) echo TEXT_PRODUCTS_YOUTUBE . '<br><small>' . TEXT_PRODUCTS_YOUTUBE_URL . '</small>'; ?></td>
            <td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('products_youtube_url[' . $languages[$i]['id'] . ']', (isset($products_youtube_url[$languages[$i]['id']]) ? $products_youtube_url[$languages[$i]['id']] : tep_get_products_youtube_url($pInfo->products_id, $languages[$i]['id'])), 'size=100'); ?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_WEIGHT; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_weight', $pInfo->products_weight); ?></td>
          </tr>
          <!-- AJAX Attribute Manager  -->
          <tr>
            <td class="main">Opzioni</td>
          	<td colspan="1"><?php require_once( 'attributeManager/includes/attributeManagerPlaceHolder.inc.php' )?></td>
          </tr>
		  <!-- AJAX Attribute Manager end -->
         
<?php
if(file_exists("product_extra_fields.php"))
{
// START: Extra Fields Contribution (chapter 1.4)
      // Sort language by ID  
	  for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
	    $languages_array[$languages[$i]['id']]=$languages[$i];
	  }
      $extra_fields_query = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_EXTRA_FIELDS . " ORDER BY products_extra_fields_order");
      while ($extra_fields = tep_db_fetch_array($extra_fields_query)) {
	  // Display language icon or blank space
        if ($extra_fields['languages_id']==0) {
	      $m=tep_draw_separator('pixel_trans.gif', '24', '15');
	    } else $m= tep_image(DIR_WS_CATALOG_LANGUAGES . $languages_array[$extra_fields['languages_id']]['directory'] . '/images/' . $languages_array[$extra_fields['languages_id']]['image'], $languages_array[$extra_fields['languages_id']]['name']);
?>
          <tr bgcolor="#ebebff">
            <td class="main"><?php echo $extra_fields['products_extra_fields_name']; ?>:</td>
            <td class="main"><?php echo $m . '&nbsp;' . tep_draw_input_field("extra_field[".$extra_fields['products_extra_fields_id']."]", $pInfo->extra_field[$extra_fields['products_extra_fields_id']]); ?></td>
          </tr>
<?php
}
// END: Extra Fields Contribution
}
?>
          
          
<?php  } else { ?>
<?=$pws_engine->triggerHook('ADMIN_EDIT_PRODUCT')?>
<?=''/*mod_related_products::editRelatedProducts($pInfo->products_id)*/?>
<?php	}?>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main" align="right"><?php echo tep_draw_hidden_field('products_date_added', (tep_not_null($pInfo->products_date_added) ? $pInfo->products_date_added : date('Y-m-d'))) . tep_image_submit('button_preview.gif', IMAGE_PREVIEW) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </tr>
    </table></form>
<? echo $pws_engine->triggerHook('ADMIN_MAXI_DVD_POST_JAVASCRIPT');?>
<script language="javascript" type="text/javascript" defer><!--
<?=$pws_prices->adminJavascriptInit(&$pInfo)?>
// --></script>
<?php
  } elseif ($action == 'new_product_preview') {
    if (tep_not_null($HTTP_POST_VARS)) {
      $pInfo = new objectInfo($HTTP_POST_VARS);
      $products_name = $HTTP_POST_VARS['products_name'];
      $products_description = $HTTP_POST_VARS['products_description'];
      $products_url = $HTTP_POST_VARS['products_url'];
      $products_seo_url = $HTTP_POST_VARS['products_seo_url'];
    } else {
//SEO      $product_query = tep_db_query("select p.products_id, pd.language_id, pd.products_name, pd.products_description, pd.products_url, p.products_quantity, p.products_model, p.products_image, p.products_price, p.products_weight, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.manufacturers_id  from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and p.products_id = '" . (int)$HTTP_GET_VARS['pID'] . "'");
      $product_query = tep_db_query("select p.vpn, p.EAN, p.products_shopwindow,p.products_id, pd.language_id, pd.products_name, pd.products_seo_url, pd.products_description, pd.products_url, p.products_quantity, p.products_model, p.products_image, p.products_price, p.products_weight, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.manufacturers_id  from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and p.products_id = '" . (int)$HTTP_GET_VARS['pID'] . "'");
      $product = tep_db_fetch_array($product_query);

      $pInfo = new objectInfo($product);
      //$products_image_name = $pInfo->products_image;
    }

    $form_action = (isset($HTTP_GET_VARS['pID'])) ? 'update_product' : 'insert_product';

    echo tep_draw_form($form_action, FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '') . '&action=' . $form_action, 'post', 'enctype="multipart/form-data"');

    $languages = tep_get_languages();
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
      if (isset($HTTP_GET_VARS['read']) && ($HTTP_GET_VARS['read'] == 'only')) {
        $pInfo->products_name = tep_get_products_name($pInfo->products_id, $languages[$i]['id']);
        $pInfo->products_description = tep_get_products_description($pInfo->products_id, $languages[$i]['id']);
        $pInfo->products_url = tep_get_products_url($pInfo->products_id, $languages[$i]['id']);
        $pInfo->products_seo_url = tep_get_products_seo_url($pInfo->products_id, $languages[$i]['id']);
      } else {
        $pInfo->products_name = tep_db_prepare_input($products_name[$languages[$i]['id']]);
        $pInfo->products_description = tep_db_prepare_input($products_description[$languages[$i]['id']]);
        $pInfo->products_url = tep_db_prepare_input($products_url[$languages[$i]['id']]);
        $pInfo->products_seo_url = tep_db_prepare_input($products_seo_url[$languages[$i]['id']]);
      }
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . $pInfo->products_name; ?></td>
            <td class="pageHeading" align="right"><?php echo $currencies->format($pInfo->products_price); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"');?> 
 <?    
 if(file_exists("product_extra_fields.php"))
 {
      // START: Extra Fields Contribution
          if ($HTTP_GET_VARS['read'] == 'only') {
            $products_extra_fields_query = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS . " WHERE products_id=" . (int)$HTTP_GET_VARS['pID']);
            while ($products_extra_fields = tep_db_fetch_array($products_extra_fields_query)) {
              $extra_fields_array[$products_extra_fields['products_extra_fields_id']] = $products_extra_fields['products_extra_fields_value'];
            }
          }
          else {
            $extra_fields_array = $HTTP_POST_VARS['extra_field'];
          }

          $extra_fields_names_query = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_EXTRA_FIELDS. " WHERE languages_id='0' or languages_id='".(int)$languages[$i]['id']."' ORDER BY products_extra_fields_order");
          while ($extra_fields_names = tep_db_fetch_array($extra_fields_names_query)) {
            $extra_field_name[$extra_fields_names['products_extra_fields_id']] = $extra_fields_names['products_extra_fields_name'];
			echo '<B>'.$extra_fields_names['products_extra_fields_name'].':</B>&nbsp;'.stripslashes($extra_fields_array[$extra_fields_names['products_extra_fields_id']]).'<BR>'."\n";
          }		  
// END: Extra Fields Contribution
}
  echo "<br />" . $pInfo->products_description;
  ?></td>
      </tr>
<?php
      if ($pInfo->products_url) {
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo sprintf(TEXT_PRODUCT_MORE_INFORMATION, $pInfo->products_url); ?></td>
      </tr>
<?php
      }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
<?php
      if ($pInfo->products_date_available > date('Y-m-d')) {
?>
      <tr>
        <td align="center" class="smallText"><?php echo sprintf(TEXT_PRODUCT_DATE_AVAILABLE, tep_date_long($pInfo->products_date_available)); ?></td>
      </tr>
<?php
      } else {
?>
      <tr>
        <td align="center" class="smallText"><?php echo sprintf(TEXT_PRODUCT_DATE_ADDED, tep_date_long($pInfo->products_date_added)); ?></td>
      </tr>
<?php
      }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
<?php
    }

    if (isset($HTTP_GET_VARS['read']) && ($HTTP_GET_VARS['read'] == 'only')) {
      if (isset($HTTP_GET_VARS['origin'])) {
        $pos_params = strpos($HTTP_GET_VARS['origin'], '?', 0);
        if ($pos_params != false) {
          $back_url = substr($HTTP_GET_VARS['origin'], 0, $pos_params);
          $back_url_params = substr($HTTP_GET_VARS['origin'], $pos_params + 1);
        } else {
          $back_url = $HTTP_GET_VARS['origin'];
          $back_url_params = '';
        }
      } else {
        $back_url = FILENAME_CATEGORIES;
        $back_url_params = 'cPath=' . $cPath . '&pID=' . $pInfo->products_id;
      }
?>
      <tr>
        <td align="right"><?php echo '<a href="' . tep_href_link($back_url, $back_url_params, 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
      </tr>
<?php
    } else {
?>
      <tr>
        <td align="right" class="smallText">
<?php
/* Re-Post all POST'ed variables */
      reset($HTTP_POST_VARS);
      unset($HTTP_POST_VARS['action']);
      unset($HTTP_POST_VARS['subaction']);
      while (list($key, $value) = each($HTTP_POST_VARS)) {
       //        if (!is_array($HTTP_POST_VARS[$key])) {
				// BOF Separate Pricing per Customer
			if (is_array($value)) {
			  while (list($k, $v) = each($value)) {
			  echo tep_draw_hidden_field($key . '[' . $k . ']', htmlspecialchars(stripslashes($v)));
			  }
			} else {
	// EOF Separate Pricing per Customer

          echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
        }
      }
      $languages = tep_get_languages();
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        echo tep_draw_hidden_field('products_name[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_name[$languages[$i]['id']])));
        echo tep_draw_hidden_field('products_description[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_description[$languages[$i]['id']])));
        echo tep_draw_hidden_field('products_url[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_url[$languages[$i]['id']])));
//SEO
        echo tep_draw_hidden_field('products_seo_url[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_seo_url[$languages[$i]['id']])));
      }
      //echo tep_draw_hidden_field('products_image', stripslashes($products_image_name));

      echo tep_image_submit('button_back.gif', IMAGE_BACK, 'name="edit"') . '&nbsp;&nbsp;';

      if (isset($HTTP_GET_VARS['pID'])) {
        echo tep_image_submit('button_update.gif', IMAGE_UPDATE);
      } else {
        echo tep_image_submit('button_insert.gif', IMAGE_INSERT);
      }
      echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
?></td>
      </tr>
    </table></form>
<?php
    }
  } else {
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="smallText" align="right">
<?php
    echo tep_draw_form('search', FILENAME_CATEGORIES, '', 'get');
    echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search');
    echo '</form>';
?>
                </td>
              </tr>
              <tr>
                <td class="smallText" align="right">
<?php
    echo tep_draw_form('goto', FILENAME_CATEGORIES, '', 'get');
    echo HEADING_TITLE_GOTO . ' ' . tep_draw_pull_down_menu('cPath', tep_get_category_tree(), $current_category_id, 'onChange="this.form.submit();"');
    echo '</form>';
?>
                </td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CATEGORIES_PRODUCTS; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $categories_count = 0;
    $rows = 0;
    if (isset($HTTP_GET_VARS['search'])) {
      $search = tep_db_prepare_input($HTTP_GET_VARS['search']);

// #################### Added Categorie Enable / Disable ##################
//      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and cd.categories_name like '%" . tep_db_input($search) . "%' order by c.sort_order, cd.categories_name");
        $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified, c.categories_status from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and cd.categories_name like '%" . tep_db_input($search) . "%' order by c.sort_order, cd.categories_name");
// #################### End Added Categorie Enable / Disable ##################
    } else {
// #################### Added Categorie Enable / Disable ##################
//      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by c.sort_order, cd.categories_name");
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified, c.categories_status from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by c.sort_order, cd.categories_name");
// #################### End Added Categorie Enable / Disable ##################
    }
    while ($categories = tep_db_fetch_array($categories_query)) {
      $categories_count++;
      $rows++;

// Get parent_id for subcategories if search
      if (isset($HTTP_GET_VARS['search'])) $cPath= $categories['parent_id'];

      if ((!isset($HTTP_GET_VARS['cID']) && !isset($HTTP_GET_VARS['pID']) || (isset($HTTP_GET_VARS['cID']) && ($HTTP_GET_VARS['cID'] == $categories['categories_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
        $category_childs = array('childs_count' => tep_childs_in_category_count($categories['categories_id']));
        $category_products = array('products_count' => tep_products_in_category_count($categories['categories_id']));

        $cInfo_array = array_merge($categories, $category_childs, $category_products);
        $cInfo = new objectInfo($cInfo_array);
      }

      if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id) ) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id'])) . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id'])) . '">' . tep_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER) . '</a>&nbsp;<b>' . $categories['categories_name'] . '</b>'; ?></td>
<!-- // ################" Added Categories Disable #############
                <td class="dataTableContent" align="center">&nbsp;</td>
-->
                <td class="dataTableContent" align="center">
<?php
      if ($categories['categories_status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag_cat&flag=0&cID=' . $categories['categories_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag_cat&flag=1&cID=' . $categories['categories_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
      }
?>
                </td>
<!-- // ################" End Added Categories Disable ############# -->
                <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }

    $products_count = 0;
    if (isset($HTTP_GET_VARS['search'])) {
//SEO      $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_quantity, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and pd.products_name like '%" . tep_db_input($search) . "%' order by pd.products_name");
      $products_query = tep_db_query("select p.vpn, p.EAN, p.products_shopwindow, p.products_id, pd.products_name, pd.products_seo_url, p.products_quantity, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and (pd.products_name like '%" . tep_db_input($search) . "%' or p.products_model like '%" . tep_db_input($search) . "%') order by pd.products_name");
    } else {
//SEO      $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_quantity, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' order by pd.products_name");
      $products_query = tep_db_query("select p.vpn, p.EAN, p.products_shopwindow, p.products_id, pd.products_name, pd.products_seo_url, p.products_quantity, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' order by pd.products_name");
    }
    while ($products = tep_db_fetch_array($products_query)) {
      $products_count++;
      $rows++;

// Get categories_id for product if search
      if (isset($HTTP_GET_VARS['search'])) $cPath = $products['categories_id'];

      if ( (!isset($HTTP_GET_VARS['pID']) && !isset($HTTP_GET_VARS['cID']) || (isset($HTTP_GET_VARS['pID']) && ($HTTP_GET_VARS['pID'] == $products['products_id']))) && !isset($pInfo) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
// find out the rating average from customer reviews
        $reviews_query = tep_db_query("select (avg(reviews_rating) / 5 * 100) as average_rating from " . TABLE_REVIEWS . " where products_id = '" . (int)$products['products_id'] . "'");
        $reviews = tep_db_fetch_array($reviews_query);
        $pInfo_array = array_merge($products, $reviews);
        $pInfo = new objectInfo($pInfo_array);
      }

      if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id) ) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product_preview&read=only') . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product_preview&read=only') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>&nbsp;' . $products['products_name']; ?></td>
                <td class="dataTableContent" align="center">
<?php
      if ($products['products_status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=0&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=1&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
      }
?></td>
                <td class="dataTableContent" align="right"><?php if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }

    $cPath_back = '';
    if (sizeof($cPath_array) > 0) {
      for ($i=0, $n=sizeof($cPath_array)-1; $i<$n; $i++) {
        if (empty($cPath_back)) {
          $cPath_back .= $cPath_array[$i];
        } else {
          $cPath_back .= '_' . $cPath_array[$i];
        }
      }
    }

    $cPath_back = (tep_not_null($cPath_back)) ? 'cPath=' . $cPath_back . '&' : '';
?>
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText"><?php echo TEXT_CATEGORIES . '&nbsp;' . $categories_count . '<br>' . TEXT_PRODUCTS . '&nbsp;' . $products_count; ?></td>
                    <td align="right" class="smallText"><?php if (sizeof($cPath_array) > 0) echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, $cPath_back . 'cID=' . $current_category_id) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>&nbsp;'; if (!isset($HTTP_GET_VARS['search'])) echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_category') . '">' . tep_image_button('button_new_category.gif', IMAGE_NEW_CATEGORY) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_product') . '">' . tep_image_button('button_new_product.gif', IMAGE_NEW_PRODUCT) . '</a>'; ?>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
    $heading = array();
    $contents = array();
    switch ($action) {
      case 'new_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_CATEGORY . '</b>');

        $contents = array('form' => tep_draw_form('newcategory', FILENAME_CATEGORIES, 'action=insert_category&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"'));
        $contents[] = array('text' => TEXT_NEW_CATEGORY_INTRO);

        $category_inputs_string = '';
        $languages = tep_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $category_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']');
        }

        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $category_seo_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_seo_url[' . $languages[$i]['id'] . ']');
        }
        $contents[] = array('text' => '<br>' . TEXT_CATEGORIES_NAME . $category_inputs_string);
        $contents[] = array('text' => '<br>' . TEXT_CATEGORIES_SEO_URL . $category_seo_string);
        $contents[] = array('text' => '<br>' . TEXT_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('categories_image'));
        $contents[] = array('text' => '<br>' . TEXT_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', '', 'size="2"'));
//PWS bof
		$contents[] = array('text' => $pws_engine->triggerHook('ADMIN_CATEGORIES_NEW'));
//PWS eof
// ###################### Added Categories Enable / Disable #################
        $contents[] = array('text' => '<br>' . TEXT_EDIT_STATUS . '<br>' . tep_draw_input_field('categories_status', '1', 'size="2"') . '1=Enabled 0=Disabled');
// ##################### End Added Categories Enable / Disable ###################
		$contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'edit_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_CATEGORY . '</b>');

        $contents = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=update_category&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"') . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
        $contents[] = array('text' => TEXT_EDIT_INTRO);

        $category_inputs_string = '';
        $languages = tep_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $category_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']', tep_get_category_name($cInfo->categories_id, $languages[$i]['id']));
        }

        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $category_seo_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_seo_url[' . $languages[$i]['id'] . ']', tep_get_category_seo_url($cInfo->categories_id, $languages[$i]['id']));
        }

        $contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_NAME . $category_inputs_string);
        $contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_SEO_URL . $category_seo_string);
        
        $contents[] = array('text' => '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $cInfo->categories_image, $cInfo->categories_name) . '<br>' . DIR_WS_CATALOG_IMAGES . '<br><b>' . $cInfo->categories_image . '</b>');
        if (HTML_AREA_WYSIWYG_DISABLE == 'Disable') {
          $contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('categories_image'));
        }else{
          $contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_IMAGE . '<br>' . tep_draw_textarea_field('categories_image', 'soft', '30', '1', $cInfo->categories_image));
        }
        $contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('categories_image'));
        $contents[] = array('text' => '<br>' . TEXT_EDIT_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', $cInfo->sort_order, 'size="2"'));
//PWS bof
		$contents[] = array('text' => $pws_engine->triggerHook('ADMIN_CATEGORIES_EDIT'));
//PWS eof
// ###################### Added Categories Enable / Disable #################
//        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_EDIT_STATUS . '<br>' . tep_draw_input_field('categories_status', $cInfo->categories_status, 'size="2"') . '1=Enabled 0=Disabled');
		$contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
// ##################### End Added Categories Enable / Disable ###################
        break;
      case 'delete_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CATEGORY . '</b>');

        $contents = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=delete_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
        $contents[] = array('text' => TEXT_DELETE_CATEGORY_INTRO);
        $contents[] = array('text' => '<br><b>' . $cInfo->categories_name . '</b>');
        if ($cInfo->childs_count > 0) $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_CHILDS, $cInfo->childs_count));
        if ($cInfo->products_count > 0) $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $cInfo->products_count));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'move_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_CATEGORY . '</b>');

        $contents = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=move_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
        $contents[] = array('text' => sprintf(TEXT_MOVE_CATEGORIES_INTRO, $cInfo->categories_name));
        $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $cInfo->categories_name) . '<br>' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'delete_product':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_PRODUCT . '</b>');

        $contents = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=delete_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
        $contents[] = array('text' => TEXT_DELETE_PRODUCT_INTRO);
        $contents[] = array('text' => '<br><b>' . $pInfo->products_name . '</b>');

        $product_categories_string = '';
        $product_categories = tep_generate_category_path($pInfo->products_id, 'product');
        for ($i = 0, $n = sizeof($product_categories); $i < $n; $i++) {
          $category_path = '';
          for ($j = 0, $k = sizeof($product_categories[$i]); $j < $k; $j++) {
            $category_path .= $product_categories[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
          }
          $category_path = substr($category_path, 0, -16);
          $product_categories_string .= tep_draw_checkbox_field('product_categories[]', $product_categories[$i][sizeof($product_categories[$i])-1]['id'], true) . '&nbsp;' . $category_path . '<br>';
        }
        $product_categories_string = substr($product_categories_string, 0, -4);

        $contents[] = array('text' => '<br>' . $product_categories_string);
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'move_product':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_PRODUCT . '</b>');

        $contents = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=move_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
        $contents[] = array('text' => sprintf(TEXT_MOVE_PRODUCTS_INTRO, $pInfo->products_name));
        $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENT_CATEGORIES . '<br><b>' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</b>');
        $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $pInfo->products_name) . '<br>' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'copy_to':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_COPY_TO . '</b>');

        $contents = array('form' => tep_draw_form('copy_to', FILENAME_CATEGORIES, 'action=copy_to_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
        $contents[] = array('text' => TEXT_INFO_COPY_TO_INTRO);
        $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENT_CATEGORIES . '<br><b>' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</b>');
        $contents[] = array('text' => '<br>' . TEXT_CATEGORIES . '<br>' . tep_draw_pull_down_menu('categories_id', tep_get_category_tree(), $current_category_id));
        $contents[] = array('text' => '<br>' . TEXT_HOW_TO_COPY . '<br>' . tep_draw_radio_field('copy_as', 'link', true) . ' ' . TEXT_COPY_AS_LINK . '<br>' . tep_draw_radio_field('copy_as', 'duplicate') . ' ' . TEXT_COPY_AS_DUPLICATE);
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_copy.gif', IMAGE_COPY) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      default:
        if ($rows > 0) {
          if (isset($cInfo) && is_object($cInfo)) { // category info box contents
            $category_path_string = '';
            $category_path = tep_generate_category_path($cInfo->categories_id);
            for ($i=(sizeof($category_path[0])-1); $i>0; $i--) {
              $category_path_string .= $category_path[0][$i]['id'] . '_';
            }
            $category_path_string = substr($category_path_string, 0, -1);

            $heading[] = array('text' => '<b>' . $cInfo->categories_name . '</b>');

            $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $category_path_string . '&cID=' . $cInfo->categories_id . '&action=edit_category') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $category_path_string . '&cID=' . $cInfo->categories_id . '&action=delete_category') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $category_path_string . '&cID=' . $cInfo->categories_id . '&action=move_category') . '">' . tep_image_button('button_move.gif', IMAGE_MOVE) . '</a>');
            $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($cInfo->date_added));
            if (tep_not_null($cInfo->last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($cInfo->last_modified));
            $contents[] = array('text' => '<br>' . tep_info_image($cInfo->categories_image, $cInfo->categories_name, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '<br>' . $cInfo->categories_image);
//PWS bof
			$contents[] = array('text' => $pws_engine->triggerHook('ADMIN_CATEGORIES_DISPLAY'));
//PWS eof
            $contents[] = array('text' => '<br>' . TEXT_SUBCATEGORIES . ' ' . $cInfo->childs_count . '<br>' . TEXT_PRODUCTS . ' ' . $cInfo->products_count);
          } elseif (isset($pInfo) && is_object($pInfo)) { // product info box contents
            $heading[] = array('text' => '<b>' . tep_get_products_name($pInfo->products_id, $languages_id) . '</b>');
            
     //   $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=new_product&subaction=edit_related') . '">' . tep_image_button('button_related.gif', IMAGE_RELATED) . '</a> ');
//PWS bof
		//	$contents[] = array('text' => $pws_engine->triggerHook('ADMIN_PRODUCTS_DISPLAY_BUTTONS'));
//PWS eof
//++++ QT Pro: Begin Changed code
            $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=new_product') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=delete_product') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=move_product') . '">' . tep_image_button('button_move.gif', IMAGE_MOVE) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=copy_to') . '">' . tep_image_button('button_copy_to.gif', IMAGE_COPY_TO) . '</a><a href="' . tep_href_link("stock.php", 'product_id=' . $pInfo->products_id) . '">' . tep_image_button('button_stock.gif', "Stock") . '</a>');
//++++ QT Pro: End Changed Code
            $contents[] = array('text' => '<br>' . tep_info_image($pInfo->products_image, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '<br>' . $pInfo->products_image);
//PWS bof
			$contents[] = array('text' => $pws_engine->triggerHook('ADMIN_PRODUCTS_DISPLAY'));
//PWS eof
            $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($pInfo->products_date_added));
            if (tep_not_null($pInfo->products_last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($pInfo->products_last_modified));
            if (date('Y-m-d') < $pInfo->products_date_available) $contents[] = array('text' => TEXT_DATE_AVAILABLE . ' ' . tep_date_short($pInfo->products_date_available));
//PWS bof
//            $contents[] = array('text' => '<br>' . TEXT_PRODUCTS_PRICE_INFO . ' ' . $currencies->format($pInfo->products_price) . '<br>' . TEXT_PRODUCTS_QUANTITY_INFO . ' ' . $pInfo->products_quantity);
            $contents[] = array('text' => '<br>' . TEXT_PRODUCTS_PRICE_INFO . ' ' . $pws_prices->getHtmlPriceDiscounts($pInfo->products_id). '<br>' . TEXT_PRODUCTS_QUANTITY_INFO . ' ' . $pInfo->products_quantity);
//PWS eof
// ICECAT {{
 			 $contents[] = array('text' => '<br/><b>' . VPN. '</b><br/>' . $pInfo->vpn . '<br />');
 			 $contents[] = array('text' => '<br/><b>' . EAN. '</b><br/>' . $pInfo->EAN . '<br />');
// ICECAT }}
			if ($pInfo->products_shopwindow=='1')
	           $contents[] = array('text' => '<br/><b>' . TEXT_PRODUCTS_SHOPWINDOW. '</b><br/>');
            $contents[] = array('text' => '<br>' . TEXT_PRODUCTS_AVERAGE_RATING . ' ' . number_format($pInfo->average_rating, 2) . '%');
            
// AAX Related Products {{
			$contents[] = array('text' => '
<style type="text/css">
#relProd{padding:6px;border:1px dotted #aaaaaa;}
.hid {
	visibility:hidden;
	display:none;
}
.vis {
	visibility:visible;
	display:block;
}
.dropAjax {
	border:1px dushed #cccccc;
	background-color: #ffffff;
}
#pSearchRelProd div, #pSearchRelProd {
	background-color: white;
}
#pSearchRelProd .notselected {
	background-color: #DEE4E8;
}
</style>
<div id="relProd">'.TITLE_RELATED_PRODUCTS_MODULE.'<p />
	<div id="pSearchRelProd">
			<table cellspacing="0" cellpadding="4" width="100%">
				<tr>
				<td class="selected main"><a href="javascript:;" onclick="this.parentNode.parentNode.cells[1].className=\'notselected main\';' .
						'this.parentNode.className=\'selected main\';var dv1=document.getElementById(\'srch1\');var dv2=document.getElementById(\'srch2\');dv1.className=\'vis\';dv2.className=\'hid\';">Cerca</a></td>
				<td class="notselected main"><a href="javascript:;" onclick="this.parentNode.parentNode.cells[0].className=\'notselected main\';' .
						'this.parentNode.className=\'selected main\';var dv1=document.getElementById(\'srch1\');var dv2=document.getElementById(\'srch2\');dv2.className=\'vis\';dv1.className=\'hid\';">Tutti i Prodotti (menu)</a></td>
				</tr>
				<tr>
				<td colspan="2">
				<div id="srch1" class="vis">
				<input type="text" value="" onkeyup="doLoad(\'pid=' . $pInfo->products_id . '&action=psearch&val=\'+this.value,null, \'contRelProd\');" onclick="doLoad(\'pid=' . $pInfo->products_id . '&action=clear\', \'contRelProd\');">
				<div id="pSearchMenuRelProd" style="border:1px dushed #cccccc;background-color: #ffffff;"></div>
				</div>
				<div id="srch2" class="hid">
				<select id="prdID" onchange="doLoad(\'action=save&pid=' . $pInfo->products_id . '&tpid=\'+this.value)">
				</select>
				</div>
				</td></tr></table>
	</div>
	<div id="contRelProd"></div>
</div>
<script type="text/javascript">
doLoad(\'pid=' . $pInfo->products_id . '\',\'\',\'\');
doLoad(\'pid=' . $pInfo->products_id . '&action=srchArea\',\'\',\'\');
</script>
');
// AAX Related Products }}
            
          }
   } else { // create category/product info
          $heading[] = array('text' => '<b>' . EMPTY_CATEGORY . '</b>');

          $contents[] = array('text' => TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS);
        }
        break;
    }

    if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
      echo '            <td width="25%" valign="top">' . "\n";

      $box = new box;
      echo $box->infoBox($heading, $contents);

      echo '            </td>' . "\n";

      // Add neccessary JS for WYSIWYG editor of category image
      if($action=='edit_category'){
        if (HTML_AREA_WYSIWYG_DISABLE != 'Disable'){
          echo '
                  <script language="JavaScript1.2" defer>
                  var config = new Object();  // create new config object
                  config.width  = "250px";
                  config.height = "35px";
                  config.bodyStyle = "background-color: white; font-family: Arial; color: black; font-size: 12px;";
                  config.debug = ' . HTML_AREA_WYSIWYG_DEBUG . ';
                  config.toolbar = [ ["InsertImageURL"] ];
                  config.OscImageRoot = "' . trim(HTTP_SERVER . DIR_WS_CATALOG_IMAGES) . '";
                  editor_generate("categories_image",config);
                 </script>
               ';
        }
      }

    }
?>
          </tr>
        </table></td>
      </tr>
    </table>
<?php
  }
?>
 


    </td>
<!-- body_text_eof //-->
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
