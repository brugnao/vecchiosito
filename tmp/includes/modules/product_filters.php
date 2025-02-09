<?php
/*
 * @filename:	
 * @version:	1.00
 * @project:	PWS
 *
 * @author:		Riccardo Roscilli <info@oscommerce.it>
 * @created:	25/ott/07
 * @modified:	25/ott/07 16:45:37
 *
 * @copyright:	2006-2007	Riccardo Roscilli @ PWS
 *
 * @desc:	
 *
 * @TODO:		
 */
// optional Product List Filter
	$skin=new pws_skin('modules/'.basename(__FILE__,'.php').'.htm');
	$skin->set('heading_title',HEADING_TITLE);
	$skin->set('params',$_REQUEST);
	$skin->set('show_form',false);
	if (PRODUCT_LIST_FILTER >= 1) {
		$skin->set('form',array(
			'method'=>'get'
			,'action'=>FILENAME_DEFAULT
			,'name'=>'filter_id'
			,'id'=>'filter_id'
	    	)
	    );
	    $skin->set('cPath',$cPath);
	    
		if (isset($HTTP_GET_VARS['manufacturers_id'])) 
			{
			 $manufacturer_query = tep_db_query ("select 
                           m.manufacturers_id, 
                           m.manufacturers_name,
                           m.manufacturers_image, 
                           m.date_added,
                           m.last_modified,
                           mi.manufacturers_url,
                           mi.url_clicked,
                           mi.date_last_click,
                           mi.manufacturers_description
                        from 
                           " . TABLE_MANUFACTURERS. " m LEFT JOIN " .TABLE_MANUFACTURERS_INFO . " mi on m.manufacturers_id = mi.manufacturers_id
                        where
                           mi.languages_id='" . (int)$languages_id . "' AND m.manufacturers_id = '" . $HTTP_GET_VARS['manufacturers_id'] . "'
                        order by 
                           m.manufacturers_name");

			 $manufacturer = tep_db_fetch_array($manufacturer_query);
	
			$filterlist_sql = "select distinct c.categories_id as id, cd.categories_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where p.products_status = '1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and p2c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and p.manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "' order by cd.categories_name";
		} else {
			// aggiungo anche l'immagine del produttore per mostrare i loghi nella skin
			$filterlist_sql= "select distinct m.manufacturers_id as id, m.manufacturers_name as name, m.manufacturers_image as image from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_MANUFACTURERS . " m where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' order by m.manufacturers_name";
		}
		$filterlist_query = tep_db_query($filterlist_sql);
		if (tep_db_num_rows($filterlist_query) > 1  ) {
			if (isset($HTTP_GET_VARS['manufacturers_id'])) {
				$options = array(array('id' => '', 'text' => TEXT_ALL_CATEGORIES));
			} else {
				$options = array(array('id' => '', 'text' => TEXT_ALL_MANUFACTURERS));
			}
			
			while ($filterlist = tep_db_fetch_array($filterlist_query)) {
				// $manufacturer_logos[] = $filterlist['image'];
				// logo produttore
			
				    

			$imgattrs=getThumbnail(DIR_WS_IMAGES . $filterlist['image'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);

			
			$link = '<a href="' .tep_href_link(FILENAME_DEFAULT, 'filter_id=' . $filterlist['id'].'&cPath='.$cPath) . '">'.  $filterlist['name']  . '</a>';
			$ref = '<a href="' .tep_href_link(FILENAME_DEFAULT, 'filter_id=' . $filterlist['id'].'&cPath='.$cPath) . '">';
			$image = $ref . '<img border="0" src="' . $imgattrs['baseFileName'] . '" width="' . $imgattrs['width'] . '" height="' . $imgattrs['height'] . '"></a>';	
			// img src="tmp/images_subcategory_action.gif_57_40__1__9ac01f455797d9b2014783a9e21df35d" width="57" height="32" title="" alt=""/	    
			
			if (isset($HTTP_GET_VARS['manufacturers_id']))
				$options[] = array('id' => $filterlist['id'], 'text' => $filterlist['name']);
			else 
				$options[] = array('id' => $filterlist['id'], 'text' => $filterlist['name'], 'image' => $image, 'manlink' => $link);			    	
				
			}
			 $skin->set('heading_title', $options['text']); // riassegno l'heading_title $filterlist['name']

		if (PRODUCT_LIST_FILTER == '1') //mostra il menu a tendina
			{
				$skin->set('manufacturers_menu',array(
					'label'=>TEXT_SHOW
		    		,'options'=>$options)
		    	);
		    	$skin->set('show_form',true);
		    	$skin->set('show_logos',false);
			}
	    	else  //mostra solo i loghi se l valore è maggiore di 1
	    	{
	    			$skin->set('manufacturers_menu',array(
					'label'=>TEXT_SHOW
		    		,'options'=>$options)
		    	);
	    	  $skin->set('show_form',false);
	    	  $skin->set('show_logos',true);
	    	}
		}else{
			$skin->set('show_form',false);
			$skin->set('show_logos',false);
		}
	}

// modifichiamo l'intestazione se Ã¨ stato selezionato un singolo produttore e la descrizione non Ã¨ vuota
if (isset($HTTP_GET_VARS['manufacturers_id']) && ($manufacturer['manufacturers_description'] <> ''))
{
	// mostra descrizione
	 $skin->set('show_manufacturer_description', true); // mostra descrizione produttore
	// nome produttore
	 $skin->set('heading_title', $manufacturer['manufacturers_name']); // riassegno l'heading_title
		
 // logo produttore
	 $image = $manufacturer['manufacturers_image'];

	 $imgattrs=getThumbnail(DIR_WS_IMAGES . $image, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);
	 
	    $skin->set('image',array(
    	'src'=>$imgattrs['baseFileName']
    	,'title'=>HEADING_TITLE
    	,'width'=>$imgattrs['width']
    	,'height'=>$imgattrs['height']
    	)
    	);
// descrizione 
	 $skin->set('manufacturer_description', $manufacturer['manufacturers_description']); 
	 
// link produttore
	$manufacturer_link = '<a href="' . $manufacturer['manufacturers_url'] . '"  class="main" target="_blank">' . $manufacturer['manufacturers_url'] . '</a>';
	$skin->set('manufacturer_url', $manufacturer_link); 
	 
	echo $skin->execute();
}
	else // caso in cui non è stata caricata la descrizione del produttore
	{
// Get the right image for the top-right
  $skin->set('show_manufacturer_description',false); 
    $image = DIR_WS_IMAGES . 'table_background_list.gif';
    if (isset($HTTP_GET_VARS['manufacturers_id'])) {
      $image = tep_db_query("select manufacturers_image from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'");
      $image = tep_db_fetch_array($image);
      $image = $image['manufacturers_image'];
    } elseif ($current_category_id) {
     // $image = tep_db_query("select distinct c.categories_id as id, cd.categories_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where p.products_status = '1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and p2c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and p.manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "' order by cd.categories_name");
      $image = tep_db_query("select cd.categories_name as name , c.categories_image from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$current_category_id . "' and cd.categories_id = '" . (int)$current_category_id . "' and cd.language_id = '" . (int)$languages_id . "'");
      $image = tep_db_fetch_array($image);
      $category_name = $image['name'];
      $image = $image['categories_image'];
    }
    // print_r ($image);
    $imgattrs=getThumbnail(DIR_WS_IMAGES . $image, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT);
    $skin->set('image',array(
    	'src'=>$imgattrs['baseFileName']
    	,'title'=>HEADING_TITLE
    	,'width'=>$imgattrs['width']
    	,'height'=>$imgattrs['height']
    	)
    );
     $skin->set('heading_title', $category_name); // riassegno l'heading_title
// print_r($skin);
   echo $skin->execute();
	}
?>