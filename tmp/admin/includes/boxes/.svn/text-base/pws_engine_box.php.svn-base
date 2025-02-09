<?php
/*
 * @filename:	pws_engine_box.php
 * @version:	1.00
 * @project:	PWS
 *
 * @author:		Riccardo Roscilli <info@oscommerce.it>
 * @created:	21/apr/08
 * @modified:	21/apr/08 15:28:56
 *
 * @copyright:	2006-2008	Riccardo Roscilli @ PWS
 *
 * @desc:	
 *
 * @TODO:		
 */

?>
<!-- pws_engine_box //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_PWS_ENGINE,
                     'link'  => tep_href_link(FILENAME_PWS_ENGINE_EXTRA_PACKAGES, 'packages=all&selected_box=pws_engine_box')
  					);

  if ($selected_box == 'pws_engine_box') {
    $contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_PWS_ENGINE_EXTRA_PACKAGES, 'packages=all&selected_box=pws_engine_box', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_PWS_ENGINE_EXTRA_PACKAGES . '</a><br/>' .
                                   '<a href="' . tep_href_link(FILENAME_PWS_ENGINE_EXTRA_PACKAGES, 'packages=installed&selected_box=pws_engine_box', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_PWS_ENGINE_EXTRA_PACKAGES_INSTALLED . '</a><br/>' .
                                   '<a href="' . tep_href_link(FILENAME_PWS_ENGINE_EXTRA_PACKAGES, 'packages=present&selected_box=pws_engine_box', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_PWS_ENGINE_EXTRA_PACKAGES_PRESENT . '</a><br/>'.
                                   '<a href="' . tep_href_link(FILENAME_PWS_ENGINE_EXTRA_PACKAGES, 'packages=new&selected_box=pws_engine_box', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_PWS_ENGINE_EXTRA_PACKAGES_NEW . '</a><br/>'
    );
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<?=$pws_engine->triggerHook('ADMIN_BOX')?>
<!-- pws_engine_box_eof //-->
