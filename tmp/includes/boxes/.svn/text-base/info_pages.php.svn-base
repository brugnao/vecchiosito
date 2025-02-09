<?php
/*
  $Id: info_pages.php,v 1.21 2003/06/09 22:07:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
	$skin=new pws_skin('boxes/'.basename(__FILE__,'.php').'.htm');
	$skin->set('box_heading',BOX_HEADING_PAGES);
    $page_query = tep_db_query("select 
                                   p.pages_id, 
					     p.page_type,
                                   p.sort_order,
                                   p.status,
                                   s.pages_title,
                                   s.pages_html_text,
                                   s.intorext,
                                   s.externallink,
                                   s.link_target  
                                from 
                                   " . TABLE_PAGES . " p LEFT JOIN " .TABLE_PAGES_DESCRIPTION . " s on p.pages_id = s.pages_id
                                where 
                                   p.status = 1 
                                and 
                                   p.page_type != 1 
                                and 
                                   s.language_id = '" . (int)$languages_id . "'
                                order by 
                                   p.sort_order, s.pages_title");

	$pages=array();
    while ($page = tep_db_fetch_array($page_query)) {

		if($page['link_target']== 1)
			$page['target']="_blank";
		else
			$page['target']="";
		if($page['intorext'] == 1)
			$page['link']=$page['externallink'];
		else {
			if($page['page_type'] != '2'){
				$page['link']=tep_href_link(FILENAME_PAGES,'pages_id=' . $page['pages_id']);
			}else{
				$page['link']=tep_href_link(FILENAME_CONTACT_US);
			}
		}
		$pages[]=$page;
    }
    $skin->set('info_pages',$pages);
	echo $skin->execute();
?>