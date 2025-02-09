<?php
/*
  $Id: database.php,v 1.3 2003/07/09 01:11:05 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  function osc_db_connect($server, $username, $password, $link = 'db_link') {
    global $$link, $db_error;

    $db_error = false;

    if (!$server) {
      $db_error = 'No Server selected.';
      return false;
    }

    $$link = @mysql_connect($server, $username, $password) or $db_error = mysql_error();

    return $$link;
  }

  function osc_db_select_db($database) {
    return mysql_select_db($database);
  }

  function osc_db_close($link = 'db_link') {
    global $$link;

    return mysql_close($$link);
  }

  function osc_db_query($query, $link = 'db_link') {
    global $$link;

    return mysql_query($query, $$link);
  }

  function osc_db_fetch_array($db_query) {
    return mysql_fetch_array($db_query);
  }

  function osc_db_num_rows($db_query) {
    return mysql_num_rows($db_query);
  }
  
  function osc_db_affected_rows($db_query=NULL){
  	return mysql_affected_rows($db_query);
  }

  function osc_db_data_seek($db_query, $row_number) {
    return mysql_data_seek($db_query, $row_number);
  }

  function osc_db_insert_id() {
    return mysql_insert_id();
  }

  function osc_db_free_result($db_query) {
    return mysql_free_result($db_query);
  }

  function osc_db_test_create_db_permission($database) {
    global $db_error;

    $db_created = false;
    $db_error = false;

    if (!$database) {
      $db_error = 'No Database selected.';
      return false;
    }

    if (!$db_error) {
      if (!@osc_db_select_db($database)) {
        $db_created = true;
        if (!@osc_db_query('create database ' . $database)) {
          $db_error = mysql_error();
        }
      } else {
        $db_error = mysql_error();
      }
      if (!$db_error) {
        if (@osc_db_select_db($database)) {
          if (@osc_db_query('create table temp ( temp_id int(5) )')) {
            if (@osc_db_query('drop table temp')) {
              if ($db_created) {
                if (@osc_db_query('drop database ' . $database)) {
                } else {
                  $db_error = mysql_error();
                }
              }
            } else {
              $db_error = mysql_error();
            }
          } else {
            $db_error = mysql_error();
          }
        } else {
          $db_error = mysql_error();
        }
      }
    }

    if ($db_error) {
      return false;
    } else {
      return true;
    }
  }

  function osc_db_test_connection($database) {
    global $db_error;

    $db_error = false;

    if (!$db_error) {
      if (!@osc_db_select_db($database)) {
        $db_error = mysql_error();
      } else {
        if (!@osc_db_query('select count(*) from configuration')) {
          $db_error = mysql_error();
        }
      }
    }

    if ($db_error) {
      return false;
    } else {
      return true;
    }
  }

  function osc_db_install($database, $sql_file) {
    global $db_error,$db;

    $db_error = false;

    if (!@osc_db_select_db($database)) {
      if (@osc_db_query('create database ' . $database)) {
        osc_db_select_db($database);
      } else {
        $db_error = mysql_error();
      }
    }

    if (!$db_error) {
      if (!file_exists($sql_file)) {
        $db_error = 'SQL file does not exist: ' . $sql_file;
        return false;
      }
      if (false && strpos(php_uname('s'),'Windows')!==false){
//      	$fd = @fopen($sql_file, 'rb');
//        $restore_query = fread($fd, filesize($sql_file));
//        fclose($fd);
//        if (!osc_db_query($restore_query)){
//        	$db_error = mysql_error();
//        }
      	  //$command = "mysql.exe -u ".$db['DB_SERVER_USERNAME']." -p".$db['DB_SERVER_PASSWORD']." ".$db['DB_DATABASE']." < $sql_file";
//      	  $command = "\"C:\Program Files\EasyPHP 1-8\mysql\bin\mysql.exe\" -u ".$db['DB_SERVER_USERNAME'].(isset($db['DB_SERVER_PASSWORD']) && $db['DB_SERVER_PASSWORD']!='' ? " -p".$db['DB_SERVER_PASSWORD']:'')." ".$db['DB_DATABASE']." < \"$sql_file\"";
//      	  $command=str_replace('/','\\',$command);
      	  //var_dump($command);
      	  //$command_output=array();
      	  //unset($command_output);
      	  //$command_status=0;
      	  //$result=exec($command,$command_output,$command_status);
      	  //print_r($command_output);
      	  //var_dump($command_status);
//      	  $result=exec($command);
//      	  var_dump($result);
//      	  $result=exec($command);
//      	  var_dump($result);
//      	  exit;
//		if (filesize($sql_file)<(1024*100)){
//			$fd = fopen($sql_file, 'rb');
//        	$restore_query = fread($fd, filesize($sql_file));
//        	fclose($fd);
//        	osc_db_query($restore_query) or $db_error=mysql_error();
//		}else{
			if (true){	
				//$command = "\"C:\Program Files\EasyPHP 1-8\mysql\bin\mysql.exe -u ".$db['DB_SERVER_USERNAME']." -p".$db['DB_SERVER_PASSWORD']." ".$db['DB_DATABASE']."\" < \"".str_replace('/','\\',$sql_file)."\"";
				$mysql_path=osc_realpath('C:/Program Files/EasyPHP 1-8/mysql/bin/mysql');
				//$mysql_path=osc_realpath('C:/Program Files/EasyPHP 2.0b1/mysql/bin/mysql.exe');
				//$mysql_path='mysql';
				$command = "$mysql_path -h ".$db['DB_SERVER']." -u ".$db['DB_SERVER_USERNAME']." --password=\"".$db['DB_SERVER_PASSWORD']."\" ".$db['DB_DATABASE']." < ".osc_realpath($sql_file);
				//$command="echo < ".osc_realpath($sql_file);
				//$command = "\"C:\Program Files\EasyPHP 1-8\mysql\bin\mysqldump.exe\" -u ".$db['DB_SERVER_USERNAME']." --password=".$db['DB_SERVER_PASSWORD']." ".$db['DB_DATABASE'];//." < \"".str_replace('/','\\',$sql_file)."\"";
				//$command = "\"C:\Program Files\EasyPHP 1-8\mysql\bin\mysql.exe\" -h ".$db['DB_SERVER']." -u ".$db['DB_SERVER_USERNAME']." --password=".$db['DB_SERVER_PASSWORD']." ".$db['DB_DATABASE']." < \"".str_replace('/','\\',$sql_file)."\"";
				//$command="\"C:/Program Files/EasyPHP 1-8/mysql/bin/mysql\" -u ".$db['DB_SERVER_USERNAME']." --password=\"root".$db['DB_SERVER_PASSWORD']."\" ".$db['DB_DATABASE']." < ".osc_realpath($sql_file);
				var_dump($command);
				$result=passthru($command,$retval);
				//$result=exec($command,$result_array,$retval);
				//$result=exec("mysql -u ".$db['DB_SERVER_USERNAME']." --password=\"".$db['DB_SERVER_PASSWORD']."\" ".$db['DB_DATABASE']." < ".osc_realpath($sql_file));
				if (is_array($result_array) && sizeof($result_array))	print_r($result_array);
				//var_dump($result);var_dump($retval);
				//$result=system("echo ciao",$retval);
				//var_dump($result);var_dump($retval);
				if (isset($retval) && $retval!=0)$db_error="errore: ".$result;
			}else{
				$descriptorspec = array(
					0 => array("file", $sql_file, "rb"),  // stdin is a pipe that the child will read from
					1 => array("pipe", "z:/standard-output.txt", "a"),  // stdout is a pipe that the child will write to
					2 => array("file", "z:/error-output.txt", "a") // stderr is a file to write to
				);

				$mysql_path=osc_realpath('C:/Program Files/EasyPHP 1-8/mysql/bin/mysql');
				$command = "$mysql_path -h ".$db['DB_SERVER']." -u ".$db['DB_SERVER_USERNAME']." --password=\"asd".$db['DB_SERVER_PASSWORD']."\" ".$db['DB_DATABASE'];
				$process = proc_open($command, $descriptorspec, $pipes);//, $cwd, $env);
				if (is_resource($process)) {
					// $pipes now looks like this:
					// 0 => writeable handle connected to child stdin
					// 1 => readable handle connected to child stdout
					// Any error output will be appended to /tmp/error-output.txt
					
					
					//echo stream_get_contents($pipes[1]);
					//fclose($pipes[1]);
					
					// It is important that you close any pipes before calling
					// proc_close in order to avoid a deadlock
					$return_value = proc_close($process);
					
					echo "command returned $return_value\n";
				}
				
				
			}
      }else if (strpos(php_uname('s'),'nix')!==false ||	strpos(php_uname('s'),'FreeBSD')!==false){
      	  $command = "mysql -h ".$db['DB_SERVER']." -u ".$db['DB_SERVER_USERNAME']." --password=\"".$db['DB_SERVER_PASSWORD']."\" ".$db['DB_DATABASE']." < $sql_file";
      	  exec($command);
      }else if (false){
	      if (file_exists($sql_file)) {
	        $fd = fopen($sql_file, 'rb');
	        $restore_query = fread($fd, filesize($sql_file));
	        fclose($fd);
	      } else {
	        $db_error = 'SQL file does not exist: ' . $sql_file;
	        return false;
	      }
	
	      $sql_array = array();
	      $sql_length = strlen($restore_query);
	      $pos = strpos($restore_query, ';');
	      for ($i=$pos; $i<$sql_length; $i++) {
	        if ($restore_query[0] == '#') {
	          $restore_query = ltrim(substr($restore_query, strpos($restore_query, "\n")));
	          $sql_length = strlen($restore_query);
	          $i = strpos($restore_query, ';')-1;
	          continue;
	        }
	        if ($restore_query[($i+1)] == "\n") {
	          for ($j=($i+2); $j<$sql_length; $j++) {
	            if (trim($restore_query[$j]) != '') {
	              $next = substr($restore_query, $j, 6);
	              if ($next[0] == '#') {
	// find out where the break position is so we can remove this line (#comment line)
	                for ($k=$j; $k<$sql_length; $k++) {
	                  if ($restore_query[$k] == "\n") break;
	                }
	                $query = substr($restore_query, 0, $i+1);
	                $restore_query = substr($restore_query, $k);
	// join the query before the comment appeared, with the rest of the dump
	                $restore_query = $query . $restore_query;
	                $sql_length = strlen($restore_query);
	                $i = strpos($restore_query, ';')-1;
	                continue 2;
	              }
	              break;
	            }
	          }
	          if ($next == '') { // get the last insert query
	            $next = 'insert';
	          }
	          if ( (eregi('create', $next)) || (eregi('insert', $next)) || (eregi('drop t', $next)) ) {
	            $next = '';
	            $sql_array[] = substr($restore_query, 0, $i);
	            $restore_query = ltrim(substr($restore_query, $i+1));
	            $sql_length = strlen($restore_query);
	            $i = strpos($restore_query, ';')-1;
	          }
	        }
	      }
	
	      osc_db_query("drop table if exists address_book, address_format, banners, banners_history, categories, categories_description, configuration, configuration_group, counter, counter_history, countries, currencies, customers, customers_basket, customers_basket_attributes, customers_info, languages, manufacturers, manufacturers_info, orders, orders_products, orders_status, orders_status_history, orders_products_attributes, orders_products_download, products, products_attributes, products_attributes_download, prodcts_description, products_options, products_options_values, products_options_values_to_products_options, products_to_categories, reviews, reviews_description, sessions, specials, tax_class, tax_rates, geo_zones, whos_online, zones, zones_to_geo_zones");
	
	      for ($i=0; $i<sizeof($sql_array); $i++) {
	        osc_db_query($sql_array[$i]);
	      }
      }else{
      		$table_prefix=-1;
	      if (file_exists($sql_file)) {
	        $fd = fopen($sql_file, 'rb');
	        $import_queries = fread($fd, filesize($sql_file));
	        fclose($fd);
	      } else {
	        $db_error = 'SQL file does not exist: ' . $sql_file;
	        return false;
	      }
	
	        if (!get_cfg_var('safe_mode')) {
	          @set_time_limit(0);
	        }
	
	        $sql_queries = array();
	        $sql_length = strlen($import_queries);
	        $pos = strpos($import_queries, ';');
	
	        for ($i=0; $i<$sql_length; $i++) {
	// remove comments
	          if ($import_queries[0] == '#' || substr($import_queries,0,2) == '--') {
	            $import_queries = ltrim(substr($import_queries, strpos($import_queries, "\n")));
	            $sql_length = strlen($import_queries);
	            $i = strpos($import_queries, ';')-1;
	            continue;
	          }
	          if (substr($import_queries,0,2) == '/*') {
	            $import_queries = ltrim(substr($import_queries, strpos($import_queries, "*/")));
	            $import_queries = ltrim(substr($import_queries, strpos($import_queries, "\n")));
	            $sql_length = strlen($import_queries);
	            $i = strpos($import_queries, ';')-1;
	            continue;
	          }
	        	
	          if ($import_queries[($i+1)] == "\n") {
	            $next = '';
	
	            for ($j=($i+2); $j<$sql_length; $j++) {
	              if (!empty($import_queries[$j])) {
	                $next = substr($import_queries, $j, 6);
	
	                if ($next[0] == '#' || (substr($next,0,2)=='--')) {
	// find out where the break position is so we can remove this line (#comment line)
	                  for ($k=$j; $k<$sql_length; $k++) {
	                    if ($import_queries[$k] == "\n") {
	                      break;
	                    }
	                  }
	
	                  $query = substr($import_queries, 0, $i+1);
	
	                  $import_queries = substr($import_queries, $k);
	
	// join the query before the comment appeared, with the rest of the dump
	                  $import_queries = $query . $import_queries;
	                  $sql_length = strlen($import_queries);
	                  $i = strpos($import_queries, ';')-1;
	                  continue 2;
	                }
	
	                break;
	              }
	            }
	        		
	            if (empty($next)) { // get the last insert query
	              $next = 'insert';
	            }
	
	            if ((strtoupper($next) == 'DROP T') || (strtoupper($next) == 'CREATE') || (strtoupper($next) == 'INSERT')) {
	              $next = '';
	
	              $sql_query = substr($import_queries, 0, $i);
	
	              if ($table_prefix !== -1) {
	                if (strtoupper(substr($sql_query, 0, 25)) == 'DROP TABLE IF EXISTS OSC_') {
	                  $sql_query = 'DROP TABLE IF EXISTS ' . $table_prefix . substr($sql_query, 25);
	                } elseif (strtoupper(substr($sql_query, 0, 17)) == 'CREATE TABLE OSC_') {
	                  $sql_query = 'CREATE TABLE ' . $table_prefix . substr($sql_query, 17);
	                } elseif (strtoupper(substr($sql_query, 0, 16)) == 'INSERT INTO OSC_') {
	                  $sql_query = 'INSERT INTO ' . $table_prefix . substr($sql_query, 16);
	                }
	              }
	
	              $sql_queries[] = trim($sql_query);
	
	              $import_queries = ltrim(substr($import_queries, $i+1));
	              $sql_length = strlen($import_queries);
	              $i = strpos($import_queries, ';')-1;
	            }
	          }
	        }
	
	        for ($i=0, $n=sizeof($sql_queries); $i<$n; $i++) {
	          osc_db_query($sql_queries[$i]);
			  if (mysql_errno()){
			  	$db_error=mysql_error();
			  	break;
			  }
//	          if ($this->isError()) {
//	            break;
//	          }
	        }
	      
			return ($db_error=='');
      }
    } else {
      return false;
    }
  }
?>
