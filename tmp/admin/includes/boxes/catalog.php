<?php
/*
  $Id: catalog.php,v 1.21 2003/07/09 01:18:53 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- catalog //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_CATALOG,
                     'link'  => tep_href_link(FILENAME_CATEGORIES, 'selected_box=catalog'));

  if ($selected_box == 'catalog') {
    $contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_CATEGORIES, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_CATEGORIES_PRODUCTS . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_QUICK_UPDATES, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_QUICK_UPDATES . '</a><br>' .
								   // Easy populate									
								   '<a href="' . tep_href_link(FILENAME_IMP_EXP_CATALOG, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_IMP_EXP . '</a><br>' .
								   // END Easy populate	
					       	       //ISY start
								   //'<a href="' . tep_href_link(FILENAME_ISY_MAIN, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_ISY_MAIN . '</a><br>' .
    							   //ISY stop
    							   // danea start MODULE_GD_DANEA_VERSION >= '1'
								   ((defined(MODULE_GD_DANEA_STATUS))?'<a href="' . tep_href_link( '../mod_danea_multiprices/index.php', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_DANEA_IMPORT . '</a><br>':'').
    							   // danea stop
								   '<a href="' . tep_href_link(FILENAME_MANUFACTURERS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_MANUFACTURERS . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_REVIEWS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_REVIEWS . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_SPECIALS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_SPECIALS . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_PRODUCTS_EXPECTED . '</a><br>'.
                                   //kgt - discount coupons
                                   (( defined('MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS') && MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS == 'true' ) ? '<a href="' . tep_href_link(FILENAME_DISCOUNT_COUPONS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_DISCOUNT_COUPONS . '</a><br>':'').
                                   /***************
                                   '<a href="' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_PRODUCTS_EXPECTED . '</a>' );
                                   ***************/
                                   //end kgt - discount coupons 
								   // START: Product Extra Fields
								   ((file_exists("product_extra_fields.php"))?'<a href="' . tep_href_link(FILENAME_PRODUCTS_EXTRA_FIELDS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_CATEGORY_EXPORTO_COMP . '</a><br>':'').
								   ((file_exists("turbolister2_export.php"))?'<a href="' . tep_href_link('turbolister2_export.php', '', 'NONSSL') . '" class="menuBoxContentLink">Turbolister2 Export</a><br>':'').
								    // END: Product Extra Fields
                                   // MaxiDVD Added Line For WYSIWYG HTML Area: BOF
                                   '<a href="' . tep_href_link('category_export_comp.php', '', 'NONSSL') . '" class="menuBoxContentLink">Export Categorie Comparatori</a><br>'.
								   
                                   '<a href="' . tep_href_link(FILENAME_DEFINE_MAINPAGE, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_DEFINE_MAINPAGE . '</a>');
                                   // MaxiDVD Added Line For WYSIWYG HTML Area: EOF
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- catalog_eof //-->
