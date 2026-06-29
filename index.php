<?
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);

session_start();

require_once(dirname(__FILE__)."/platform/config.php");

require_once(LIBRARY."core.php");
require_once(LIBRARY."mvc.php");
require_once(CLASSES."model.php");
require_once(CLASSES."controller.php");
require_once(CLASSES."template.php");
require_once(CLASSES."db.php");

// check session
require_once(CLASSES."session.php");
$session = new Session(is_var_valid($_GET["logout"]) ? 1 : 0);

// connect to the database
require_once(LIBRARY."connect.php");

// figure out which page to show
if ($session->get_session_on() == 0) {
  if (is_var_valid($_REQUEST["q"])) {
    $arr = explode("/", $_REQUEST["q"]);
    $str = (sizeof($arr) > 1 ? $arr[0]."/".$arr[1] : $arr[0]);
    if (in_array($str, $nologin)) {
      include("nologin.php");
      exit;
    }  
  }
  $_GET["q"] = "users/login";
}  

// check url and call_hook
$q = (is_var_valid($_GET["q"]) ? strip($_GET["q"]) : "projects/view_approved");
if (($error_msg = call_hook($q)) != "") {
  include(TEMPLATES."error.php");
}

// disconnect from database
mysql_close($db_link);

?>
