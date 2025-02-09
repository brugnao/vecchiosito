<?php
/*
  $Id: product_listing.php,v 1.44 2003/06/09 22:49:59 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  $listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_SEARCH_RESULTS, 'p.products_id');
  $skin=new pws_skin('modules/'.basename(__FILE__,'.php').'.htm');
  $skin->set('top_page_links_show',($listing_split->number_of_rows > 0) && ( (PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3') ) );
  $skin->set('bottom_page_links_show',($listing_split->number_of_rows > 0) && ( (PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3') ) );
  $skin->set('number_of_products',$listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS));
  if ( ($listing_split->number_of_rows > 0) && ( (PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3') ) ) {
	$skin->set('top_page_links', TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))));
  }
  if ( ($listing_split->number_of_rows > 0) && ( (PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3') ) ) {
	$skin->set('bottom_page_links', TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))));
  }
  $skin->set('text_no_products',TEXT_NO_PRODUCTS);
  $skin->set('no_products',$listing_split->number_of_rows==0);
  $skin->set('legend_availability_show',$listing_split->number_of_rows>0 && PRODUCT_LIST_AVAILABILITY_LEGEND=='true');
  if (!$GLOBALS['pws_prices']->displayPrices() && array_search('PRODUCT_LIST_BUY_NOW',$column_list)){
  	unset($column_list[array_search('PRODUCT_LIST_BUY_NOW',$column_list)]);
  }
  $headings=array();
  for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
    switch ($column_list[$col]) {
	// AVAILABILITY start
      case 'PRODUCT_LIST_AVAILABILITY':
        $lc_text = TABLE_HEADING_AVAILABILITY;
        $lc_align = '';
        break;
	// AVAILABILITY stop
        case 'PRODUCT_LIST_MODEL':
        $lc_text = TABLE_HEADING_MODEL;
        $lc_align = '';
        break;
      case 'PRODUCT_LIST_NAME':
        $lc_text = TABLE_HEADING_PRODUCTS;
        $lc_align = '';
        break;
      case 'PRODUCT_LIST_MANUFACTURER':
        $lc_text = TABLE_HEADING_MANUFACTURER;
        $lc_align = '';
        break;
      case 'PRODUCT_LIST_PRICE':
        $lc_text = TABLE_HEADING_PRICE;
        $lc_align = '';
        break;
      case 'PRODUCT_LIST_QUANTITY':
        $lc_text = TABLE_HEADING_QUANTITY;
        $lc_align = 'center';
        break;
        // gestione arrivi con modal window
     case 'PRODUCT_LIST_INCOMINGS':
        $lc_text = TABLE_HEADING_INCOMINGS;
        $lc_align = 'center';
        break;       
        // fine gestione arrivi
        
      case 'PRODUCT_LIST_WEIGHT':
        $lc_text = TABLE_HEADING_WEIGHT;
        $lc_align = '';
        break;
      case 'PRODUCT_LIST_IMAGE':
        $lc_text = TABLE_HEADING_IMAGE;
        $lc_align = 'center';
        break;
      case 'PRODUCT_LIST_BUY_NOW':
        $lc_text = TABLE_HEADING_BUY_NOW;
        $lc_align = 'center';
        break;
    }
	
    if ( ($column_list[$col] != 'PRODUCT_LIST_BUY_NOW') && ($column_list[$col] != 'PRODUCT_LIST_IMAGE') && ($column_list[$col] != 'PRODUCT_LIST_AVAILABILITY') ) {
          $lc_text = tep_create_sort_heading($HTTP_GET_VARS['sort'], $col+1, $lc_text);
    }
	// print_r ($column_list);
	
    $headings[] = array('align' => $lc_align,
                        'text' => '&nbsp;' . $lc_text . '&nbsp;');
  }
  
 
  $skin->set('headings',$headings);
  $rawquery="select * from ".TABLE_PRODUCTS." p left join ".TABLE_PRODUCTS_DESCRIPTION." pd on (p.products_id=pd.products_id and language_id=$languages_id) left join
	".TABLE_MANUFACTURERS." m on (m.manufacturers_id=p.manufacturers_id) where p.products_id=";
  if ($listing_split->number_of_rows > 0) {
    $rows = 0;
    $listing_query = tep_db_query($listing_split->sql_query);
   //  print_r($listing_split->sql_query);
    
    while ($listing = tep_db_fetch_array($listing_query)) {
      $rows++;

      if (($rows/2) == floor($rows/2)) {
        $list_box_contents[]['class'] ='productListing-even';
      } else {
        $list_box_contents[]['class'] ='productListing-odd';
      }

      $cur_row = sizeof($list_box_contents) - 1;
	  $list_box_contents[$cur_row]['rawdata']=tep_db_fetch_array(tep_db_query($rawquery.$listing['products_id']));

	  
	  // elenco variabili necessarie per la nuova gestione delle skin
	  
	  $list_box_contents[$cur_row]['rawdata']['first_price']=$pws_prices->getFirstPrice($listing['products_id']);
      $list_box_contents[$cur_row]['rawdata']['best_price']=$pws_prices->getBestPrice($listing['products_id']);
      $list_box_contents[$cur_row]['rawdata']['product_htmlprice'] = $pws_prices->getHtmlPriceWithDiscount($listing['products_id']);
      $list_box_contents[$cur_row]['rawdata']['price'] = $pws_prices->formatPrice($list_box_contents[$cur_row]['rawdata']['first_price'], $listing['products_id']);
	  
      if ($list_box_contents[$cur_row]['rawdata']['first_price'] <> $list_box_contents[$cur_row]['rawdata']['best_price'])
    	  $list_box_contents[$cur_row]['rawdata']['specialprice'] = $pws_prices->formatPrice($list_box_contents[$cur_row]['rawdata']['best_price'], $listing['products_id']);
      else  $list_box_contents[$cur_row]['rawdata']['specialprice'] = 0;
      /*
      if(strstr($listing['products_image'], 'http')) 
	        	$lc_text = tep_output_image($listing['products_id'],SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);
      else 
      {
      $checkMultiImage = tep_db_query("select * from ".TABLE_PWS_PLUGINS." where plugin_code = 'pws_products_images'");
		   if(tep_db_num_rows($checkMultiImage) >= '1' &&  !(strstr($listing['products_image'], 'http')) )  // è installato il plugin multimage?
	            {
            	$image_query=tep_db_query("select * from ".TABLE_PWS_PRODUCTS_IMAGES." where products_id='" . $listing['products_id'] . "' order by sort_order");
					
	            	if (tep_db_num_rows($image_query) >= '1')
	            		$lc_text = '<a href="' . DIR_WS_IMAGES . $listing['products_image'] . '" rel="lightbox[' . $listing['products_id']. ']"  title="Click on the left/right side of image" target="_blank">' . $GLOBALS['pws_html']->getHtmlProductsImage($listing['products_id'], $listing['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>&nbsp;';
					else 	
	            		$lc_text = '<a href="' . DIR_WS_IMAGES . $listing['products_image'] . '" rel="lightbox[' . $listing['products_id']. ']"   target="_blank">' . $GLOBALS['pws_html']->getHtmlProductsImage($listing['products_id'], $listing['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>&nbsp;';
	            	
	            	
	            	while ($image_array = tep_db_fetch_array($image_query))
					{
						$lc_text .= '<a href="' . DIR_WS_IMAGES . $image_array['products_image'] . '" rel="lightbox[' . $listing['products_id']. ']"  title="Click on the left/right side of image" target="_blank"></a>';			
					}
	            }
	        else     
	        {      
	        	$lc_text = '<a href="' . DIR_WS_IMAGES . $listing['products_image'] . '" rel="lightbox[' . $listing['products_id']. ']" target="_blank">' . $GLOBALS['pws_html']->getHtmlProductsImage($listing['products_id'], $listing['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>&nbsp;';
	        }
	        

       }
		//	$list_box_contents[$cur_row]['rawdata']['product_multimages'] = $lc_text;
           
      */
      	
      	
	  for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
        $lc_align = '';
        switch ($column_list[$col]) {
         case 'PRODUCT_LIST_MODEL':
            $lc_align = '';
            $lc_text = '&nbsp;' . $listing['products_model'] . '&nbsp;';
           
            break;
          case 'PRODUCT_LIST_NAME':
            $lc_align = '';
            $lc_text = '';
            // logo del produttore se esiste
            if (PRODUCT_LIST_MANUFACTURER == '0'  )
            {
            	// query per ricavere i dati del produttore
            	$manufacturer_query = tep_db_query("select m.manufacturers_id, m.manufacturers_name, m.manufacturers_image, mi.manufacturers_url from " . TABLE_MANUFACTURERS . " m left join " . TABLE_MANUFACTURERS_INFO . " mi on (m.manufacturers_id = mi.manufacturers_id and mi.languages_id = '" . (int)$languages_id . "'), " . TABLE_PRODUCTS . " p  where p.products_id = '" . (int)$listing['products_id'] . "' and p.manufacturers_id = m.manufacturers_id");
            	    $manufacturer = tep_db_fetch_array($manufacturer_query);
            	   if (tep_db_num_rows($manufacturer_query) >= '1')
			            	   {
			            	  	if ($manufacturer['manufacturers_image']!='')
					            	  {
						  				//$manufacturer['manufacturers_image']='<img src='.DIR_WS_IMAGES.$manufacturer['manufacturers_image'].'>';
						  				
					            	 
							  			 $manufacturer['link_to_other_products']=tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturer['manufacturers_id']);	
						                // $lc_text .= $manufacturer['link_to_other_products'] . $manufacturer['manufacturers_image']; 
					   
						               // $lc_text .=  TABLE_HEADING_MANUFACTURER . ": " .'<a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturer['manufacturers_id']) . '">'.  $manufacturer['manufacturers_name']  . '</a>&nbsp;&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES. $manufacturer['manufacturers_image'], $manufacturer['manufacturers_name'], 20, 20)  . '';
					                // 25-02-2010 inserimento logo produttore pi� grande al posto del nome e loghetto invisibile
					              	    $lc_text .=  '<a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturer['manufacturers_id']) . '">'. tep_image(DIR_WS_IMAGES. $manufacturer['manufacturers_image'], $manufacturer['manufacturers_name'], 80, 80)  . '</a>';
						                $lc_text .= '<br><br>';
					            	   }
			            	   }
            }
            
            if (isset($HTTP_GET_VARS['manufacturers_id'])) {
              $lc_product_link = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'] . '&products_id=' . $listing['products_id']) . '">';
			  $list_box_contents[$cur_row]['rawdata']['product_link'] = tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'] . '&products_id=' . $listing['products_id']);
            } 
            else 
            	{
                $lc_product_link = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id']) . '">';
            	$list_box_contents[$cur_row]['rawdata']['product_link'] = tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id']);
            	}
            	
              $lc_text .= $lc_product_link . $listing['products_name'] . '</a>';
              $lc_text .= '<br>';
            //  print_r($listing);
              
          if (PRODUCT_LIST_MODEL == '0' &&   $listing['products_model'] <> '')
            {
            	
               	$lc_text .= TABLE_HEADING_MODEL .": " . $listing['products_model'] ;
               	$lc_text .= '<br>';

            }
            if(file_exists("admin/product_extra_fields.php"))
            {
				// PRODUCT EXTRA FIELDS IN PRODUCT LISTING - BEGIN             
				          $extra_fields_text = '';
				          $extra_fields_query = tep_db_query("
				                      SELECT pef.products_extra_fields_status as status, pef.products_extra_fields_name as name, ptf.products_extra_fields_value as value
				                      FROM ". TABLE_PRODUCTS_EXTRA_FIELDS ." pef
				             LEFT JOIN  ". TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS ." ptf
				            ON ptf.products_extra_fields_id=pef.products_extra_fields_id
				            WHERE ptf.products_id=". (int) $listing['products_id'] ." and ptf.products_extra_fields_value<>'' and (pef.languages_id='0' or pef.languages_id='".$languages_id."')
				            ORDER BY products_extra_fields_order");
						 if (tep_db_num_rows($extra_fields_query))
						  while ($extra_fields = tep_db_fetch_array($extra_fields_query)) {
						        if (! $extra_fields['status'])
						           continue;
								   $extra_fields_text .= $extra_fields['name'].': ' .'' .$extra_fields['value'].''; 
								  }		
							
				              $lc_text .=  $extra_fields_text ;
				              $lc_text .= '<br>';
				// PRODUCT EXTRA FIELDS IN PRODUCT LISTING - END  
            }
            

            if (PRODUCT_LIST_PRICE == '0'  )
            {
            
           //   	$lc_text .= '&nbsp;'.$pws_prices->getHtmlPriceWithDiscount($listing['products_id']);
			//	$lc_text .= $pws_prices->getHtmlDiscountInfo($listing['products_id']);
			 //   $lc_text .= '<br>';
				$lc_text .= $pws_prices->getHtmlPriceDiscounts($listing['products_id']);
                $lc_text .= '<br>';
              }
     
            //  $lc_text .=    getProductsImage($manufacturer['manufacturers_id'], $width=20,$height=20,$path=true, 'manufacturers_id' , 'manufacturers_image', TABLE_MANUFACTURERS);
              
            $lc_text .= $lc_product_link . tep_image_button('button_details.gif', IMAGE_BUTTON_DETAILS) . '</a>';
            break;
          case 'PRODUCT_LIST_MANUFACTURER':
            $lc_align = '';
            $lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $listing['manufacturers_id']) . '">' . $listing['manufacturers_name'] . '</a>&nbsp;';
            break;
          case 'PRODUCT_LIST_PRICE':
            $lc_align = 'left';
//PWS bof
			$lc_text = '&nbsp;'.$pws_prices->getHtmlPriceWithDiscount($listing['products_id']);
	//		$lc_text.=$pws_prices->getHtmlDiscountInfo($listing['products_id']);
	//	    $lc_text.=$pws_prices->getHtmlPriceDiscounts($listing['products_id']);
//PWS eof
            break;
          case 'PRODUCT_LIST_QUANTITY':
            $lc_align = 'left';
            $lc_text = '&nbsp;Disp.:' . $listing['products_quantity'] . '&nbsp;';
            break;

          case 'PRODUCT_LIST_INCOMINGS':           
            // inserimento modulo per gestione arrivi ajax 
   			// controllo se il modulo è installato
            if(file_exists(DIR_FS_CATALOG . 'admin/incoming_products.php'))
            {
            	// ricavo tutti i record per il prodotto, facendo la differenza delle somme fra 
            	// DeliveredQty e ConfirmedDeliveryDate
            	// campo chiave che relaziona i prodotti agli arrivi

					
            	$product_array = tep_get_row(TABLE_PRODUCTS, 'products_id', $listing['products_id'] );
            	 
            	if($product_array['Codice'] <> '') // esprinet
					$product_info['Item'] = $product_array['Codice'];
				else 
					$product_info['Item'] = $product_array['products_model'];
            	
            	$in_arrivo_query = tep_db_query("Select SUM(Qty) as total_ordered, SUM(DeliveredQty) as total_delivered from products_incomings where Item = '". $product_info['Item'] ."'");
            	$totals_incoming_array = tep_db_fetch_array($in_arrivo_query);
            	$total_incoming = $totals_incoming_array['total_ordered'] - $totals_incoming_array['total_delivered'];
 
           if ($total_incoming >= '1')
			           {
			            $lc_text = '&nbsp;In arrivo:' . $total_incoming;
			          
			            $http_user_agent = getenv('HTTP_USER_AGENT');
/*						if (strstr($http_user_agent,'MSIE')) 
							$lc_text .= '<br><a href="javascript:void(0)"><img border="0" src="'. DIR_WS_ICONS . 'Information_icon.jpg" alt="Date di arrivo" align="absbottom" id="incoming_info"  onClick="loadIncomings( \'' . $listing["products_model"] . '\', \'' . $listing["products_id"] . '\')"></a>';
						else 
							$lc_text .= '<br><img border="0" src="'. DIR_WS_ICONS . 'Information_icon.jpg" alt="Date di arrivo" align="absbottom" id="incoming_info"  onClick="loadIncomings( \'' . $listing["products_model"] . '\',\'' . $listing["products_id"] . '\')">';
*/
			          // 	$lc_text .= '<a href="About.html" mce_href="About.html" class="lbOn">About Me</a>';
			            $lc_text .= '<br><a href="incomings_modal.php?products_id=' . $listing['products_id'] . '" class="lightwindow"><img border="0" src="'. DIR_WS_ICONS . 'Information_icon.jpg"></a>';
			           	$lc_text .= '<br><div class="productListing-data" id="incomings' . $listing['products_id'] . '"></div>';
							
			           }
			           else 
			         //  $lc_text = '&nbsp;' . $total_incoming;
			         $lc_text = '';			           
             }     

           	break;    
            
          case 'PRODUCT_LIST_WEIGHT':
            $lc_align = 'right';
            $lc_text = '&nbsp;' . $listing['products_weight'] . '&nbsp;';
            break;
          case 'PRODUCT_LIST_IMAGE':
            $lc_align = 'center';
/*	        if (tep_not_null($listing['products_image'])) {
	        $lc_text .= '<script language="javascript"><!--';
			 $lc_text .= "
			 document.write('<a href=\"javascript:popupWindow(\'" . tep_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $listing['products_id']) . '\')\">' . $GLOBALS['pws_html']->getHtmlProductsImage( (int)$listing['products_id'], addslashes($listing['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>' . TEXT_CLICK_TO_ENLARGE . '</a>';
			 $lc_text .="
			 //--></script>";
			 $lc_text .="<noscript>";
			 $lc_text .='<a href="' . tep_href_link(DIR_WS_IMAGES . $listing['products_image']) . '" target="_blank">' . $GLOBALS['pws_html']->getHtmlProductsImage((int)$listing['products_id'], $listing['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>' . TEXT_CLICK_TO_ENLARGE . '</a>';
			 $lc_text .="</noscript>";
	        }
*/
	 /*       
            if (isset($HTTP_GET_VARS['manufacturers_id'])) {
              $lc_text = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'] . '&products_id=' . $listing['products_id']) . '">' 
              	. $GLOBALS['pws_html']->getHtmlProductsImage($listing['products_id'], $listing['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
            } else {
              $lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id']) . '">' 
                . $GLOBALS['pws_html']->getHtmlProductsImage($listing['products_id'], $listing['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>&nbsp;';
            }
			*/
           $lc_align = 'center';
           
  //         if(strstr($listing['products_image'], 'http') )  // esprinet
//	        	$lc_text = tep_output_image($listing['products_id'],SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, ' rel="lightbox[' . $listing['products_id'] . ']" target="_blank"');
  //         else
  //         {
           $checkMultiImage = tep_db_query("select * from ".TABLE_PWS_PLUGINS." where plugin_code = 'pws_products_images'");
		   if(tep_db_num_rows($checkMultiImage) >= '1')  // è installato il plugin multimage?
	            {
            	$image_query=tep_db_query("select * from ".TABLE_PWS_PRODUCTS_IMAGES." where products_id='" . $listing['products_id'] . "' order by sort_order");
					
	            	if (tep_db_num_rows($image_query) >= '1')
	            	//	$lc_text = '<a href="' . DIR_WS_IMAGES . $listing['products_image'] . '" rel="lightbox[' . $listing['products_id']. ']"  title="Click on the left/right side of image" target="_blank">' . $GLOBALS['pws_html']->getHtmlProductsImage($listing['products_id'], $listing['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>&nbsp;';
					$lc_text = tep_output_image($listing['products_id'],SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, ' rel="lightbox[pl' . $listing['products_id'] . ']" title="Click on the left/right side of image" target="_blank"');
	            	
	            	else 	
	            	//	$lc_text = '<a href="' . DIR_WS_IMAGES . $listing['products_image'] . '" rel="lightbox[' . $listing['products_id']. ']"   target="_blank">' . $GLOBALS['pws_html']->getHtmlProductsImage($listing['products_id'], $listing['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>&nbsp;';
	            	$lc_text = tep_output_image($listing['products_id'],SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, ' rel="lightbox[pl' . $listing['products_id'] . ']" target="_blank"');
	            	
	            	
	            	while ($image_array = tep_db_fetch_array($image_query))
					{
						$lc_text .= '<a href="' . DIR_WS_IMAGES . $image_array['products_image'] . '" rel="lightbox[pl' . $listing['products_id']. ']"  title="Click on the left/right side of image" target="_blank"></a>';			
					}
	            }
	        else     
	        {      
	            	$lc_text = tep_output_image($listing['products_id'],SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, ' rel="lightbox[pl' . $listing['products_id'] . ']" target="_blank"');
	         }
	        
    //       }

			$list_box_contents[$cur_row]['rawdata']['product_multimages'] = $lc_text;
           
            break;
          case 'PRODUCT_LIST_BUY_NOW':
            $lc_align = 'center';
        	if (tep_has_product_attributes($listing['products_id'])) {
                                   $lc_text = '<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $listing['products_id']) . '">' . tep_image_button('button_buy_now.gif', IMAGE_BUTTON_BUY_NOW) . '</a>&nbsp;';
                                }
                       else
                       			{
                                 	
                             	if (AJAX_CART_ENABLED == 'false') //vecchia gestione carrello
                             	{
                             		
						        $lc_text = '<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $listing['products_id']) . '">' . tep_image_button('button_buy_now.gif', IMAGE_BUTTON_BUY_NOW) . '</a>&nbsp;';
                             	
                             	}
                             	else 
									{	if (file_exists(DIR_WS_LANGUAGES . $language . '/images/buttons/button_in_cart_ajax.gif'))
											$button_in_cart_ajax = 'button_in_cart_ajax.gif';
										else  $button_in_cart_ajax = 'button_buy_now.gif';
							        //   $lc_text = '<form name="cart_quantity" method="post" action="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now', 'NONSSL'). '">';
							            $lc_text = '<div><form action="">';
							            $lc_text .= '<input type="hidden" id="products_id' . $listing['products_id'] . '" name="products_id" value="' . $listing['products_id'] . '"><input type="text" id="quantity' . $listing['products_id'] . '" name="quantity" value="1" maxlength="5" size="1" "></form>';
									// distinguo tra Firefox e IE che fa lo stronzo
									$http_user_agent = getenv('HTTP_USER_AGENT');
									if (strstr($http_user_agent,'MSIE')) 
							            $lc_text .= '<a href="#"><img border="0" src="'. DIR_WS_LANGUAGES . $language . '/images/buttons/' . $button_in_cart_ajax . '" alt="'. IMAGE_BUTTON_BUY_NOW . '" align="absbottom" id="cart-submit" onmousedown="loading( ' . $listing["products_id"] . ', \'loading\')" onmouseup="addtoCart( ' . $listing["products_id"] . ' , document.getElementById(\'quantity' . $listing['products_id'] . '\').value)" onMouseOut="loadCartItem( ' . $listing["products_id"] . ', \'add\')"></a>';
									else 
										$lc_text .= '<img border="0" src="'. DIR_WS_LANGUAGES . $language . '/images/buttons/' . $button_in_cart_ajax . '" alt="'. IMAGE_BUTTON_BUY_NOW . '" align="absbottom" id="cart-submit" onmousedown="loading( ' . $listing["products_id"] . ', \'loading\')" onmouseup="addtoCart( ' . $listing["products_id"] . ' , document.getElementById(\'quantity' . $listing['products_id'] . '\').value)" onMouseOut="loadCartItem( ' . $listing["products_id"] . ', \'add\')">';
									//print "browser:" . $http_user_agent;
						
							            //    $lc_text .= '<a href=""><img border="0" src="'. DIR_WS_LANGUAGES . $language . '/images/buttons/' . $button_in_cart_ajax . '"> alt="'. IMAGE_BUTTON_BUY_NOW . '" align="absbottom" id="cart-submit" onmousedown="loading( ' . $listing["products_id"] . ', \'loading\')" onmouseup="addtoCart( ' . $listing["products_id"] . ' , document.getElementById(\'quantity' . $listing['products_id'] . '\').value)" onClick="loadCartItem( ' . $listing["products_id"] . ', \'add\')"></a>';
									//	$lc_text .= '<a href="">' . tep_image_button($button_in_cart_ajax, IMAGE_BUTTON_BUY_NOW) . '</a>';
							            $lc_text.= '<br><div class="productListing-data" id="cartquantity' . $listing['products_id'] . '"></a></div>';
	                               	 }
	                               	 
                             	}
                             	$list_box_contents[$cur_row]['rawdata']['buy_now'] = $lc_text;
         //   $lc_text = '<form name="cart_quantity" method="post" action="' . tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=add_product', 'NONSSL'). '"><input type="hidden" name="products_id" value="' . $listing['products_id'] . '"><input type="text" name="quantity" value="1" maxlength="5" size="1">' . tep_image_submit($button_in_cart_ajax, IMAGE_BUTTON_BUY_NOW, 'valign=bottom onSubmit=""') . '</form>';
            break;

		// AVAILABILITY start
          case 'PRODUCT_LIST_AVAILABILITY':
            $lc_align = 'center';
	if ($listing['products_date_available']!=''
		&& substr($listing['products_date_available'],0,10)!='0000-00-00'
		&& date('Y-m-d') < substr($listing['products_date_available'],0,10))	{
		$image_location=DIR_WS_ICONS.'sem_blue.gif';
		$image_title=TEXT_AVAILABILITY_SCHEDULED;
	} else if ($listing['products_quantity']>=PRODUCT_LIST_AVAILABILITY_GREEN)	{
		$image_location=DIR_WS_ICONS.'sem_green.gif';
		$image_title=TEXT_AVAILABILITY_GREEN;
	} else if ($listing['products_quantity']>=PRODUCT_LIST_AVAILABILITY_YELLOW) {
		$image_location=DIR_WS_ICONS.'sem_yellow.gif';
		$image_title=TEXT_AVAILABILITY_YELLOW;
	} else {
		$image_location=DIR_WS_ICONS.'sem_red.gif';
		$image_title=TEXT_AVAILABILITY_RED;
	}
            if (PRODUCT_LIST_AVAILABILITY_TEXT=='true')	{
            	$lc_text='<table><tr><td><img border="0" src="'.$image_location.'" title="'.$image_title.'"/>'
            		.'</td><td><small>'.$image_title.'</small></td></tr></table>';
            }
            else
	            $lc_text = '<img border="0" src="'.$image_location.'" title="'.$image_title.'"/>';
            break;
		// AVAILABILITY stop
        }

        $list_box_contents[$cur_row]['data'][] = array('align' => $lc_align,
                                               'class' => 'productListing-data',
                                               'text'  => $lc_text
        //  i dati spacchettati per maggiore flessibilit� del template
 		//	sono nell'array rawdata							       ,
 		//								       'image' => $listing['image']
        										);
        //print_r($list_box_contents); 
      }
    }
  }else{
  }
  //  print_r($list_box_contents);
  $skin->set('products',$list_box_contents); // setta l'array products 
  
  $skin->set('legend_availability_heading',LEGEND_AVAILABILITY);
  $skin->set('legend_availability_green_image',DIR_WS_ICONS.'sem_green.gif');
  $skin->set('legend_availability_green_text',LEGEND_AVAILABILITY_GREEN);
  $skin->set('legend_availability_blue_image',DIR_WS_ICONS.'sem_blue.gif');
  $skin->set('legend_availability_blue_text',LEGEND_AVAILABILITY_SCHEDULED);
  $skin->set('legend_availability_yellow_image',DIR_WS_ICONS.'sem_yellow.gif');
  $skin->set('legend_availability_yellow_text',LEGEND_AVAILABILITY_YELLOW);
  $skin->set('legend_availability_red_image',DIR_WS_ICONS.'sem_red.gif');
  $skin->set('legend_availability_red_text',LEGEND_AVAILABILITY_RED);
  echo $skin->execute();
?>