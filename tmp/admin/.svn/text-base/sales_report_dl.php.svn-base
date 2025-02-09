<?php
 require('includes/application_top.php');

 $file = DIR_FS_BACKUP . 'sales_report.txt'; // file di appoggio per effettuare il dl diretto del csv

 /* if (file_exists($file))
 {
  forceDownload('$file');
 }
  else 
  print "file export non trovato";
*/
 $filetemp = $file;
 $dim = filesize($filetemp);
 $unique_name = md5(uniqid(rand(), true)); 
 $filename = 'sales_report_'. date("Ymd_His") . ".csv";
 
header("Content-Type: application/csv; name=".$unique_name);
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".$dim);
header("Content-Disposition: attachment; filename=" .$filename);
header("Expires: 0");
header("Cache-Control: no-cache, must-revalidate");
header("Cache-Control: private");
header("Pragma: public");
readfile($filetemp); 
?>