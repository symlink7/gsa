<?php
if (!function_exists('mysql_connect')) {
  require_once(CLASSES."mysql.php");
}  

$db_link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die(mysql_error());

mysql_select_db(DB_NAME) or die(mysql_error());

mysql_query("SET NAMES utf8");
?>
