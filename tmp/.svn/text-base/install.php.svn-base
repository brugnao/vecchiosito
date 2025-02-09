<?php
include('includes/configure.php');
$mysqlHandler = mysql_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
mysql_select_db (DB_DATABASE, $mysqlHandler);

$query[] = "ALTER TABLE `customers` ADD `customers_paypal_payerid` VARCHAR( 20 )";
$query[] = "ALTER TABLE `customers` ADD `customers_paypal_ec` TINYINT (1) UNSIGNED DEFAULT '0' NOT NULL";

foreach ($query as $sql)
{
  if (!mysql_query($sql, $mysqlHandler))
  {
    exit("ERROR WHILE IMPORTING DATA.<br />The server said :«".mysql_error()."»");
  }
}
mysql_close($mysqlHandler);
echo "The data importation was SUCCESSFUL";
