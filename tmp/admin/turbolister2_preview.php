<?php
/**
 * File: turbolister2_preview.php
 * Created on Oct 10, 2010
 *
 *
 * @category   
 * @package    
 * @author      Stas <faust@advancewebsoft.com>
 */
require('includes/application_top.php');
$sql = 'SELECT p.*, pd.* FROM pages p, pages_description pd WHERE pd.pages_id=p.pages_id AND pd.pages_title LIKE \'Turbolister Header\' AND pd.language_id=\''.$languages_id.'\'';
$query = tep_db_query($sql);
$header = tep_db_fetch_array($query);
$sql = 'SELECT p.*, pd.* FROM pages p, pages_description pd WHERE pd.pages_id=p.pages_id AND pd.pages_title LIKE \'Turbolister Footer\' AND pd.language_id=\''.$languages_id.'\'';
$query = tep_db_query($sql);
$footer = tep_db_fetch_array($query);
echo $header['pages_html_text'] . $footer['pages_html_text'];
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>