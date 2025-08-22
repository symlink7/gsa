<?
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);

date_default_timezone_set("America/Los_Angeles");

define(AUTH_KEY_PUBLIC, "pc5-Zx_7y4uDNTvF");
define(AUTH_KEY_INT, "xyz_2Ndg34-CbHb-fdh");

if (!isset($_POST) || !is_array($_POST)) {
  json_die("Invalid request.");
}

if (!isset($_POST["k"]) || 
    ($_POST["k"] != AUTH_KEY_PUBLIC && $_POST["k"] != AUTH_KEY_INT)) {
  json_die("Wrong authentication key.");
}

$_POST["version"] = ($_POST["k"] == AUTH_KEY_INT ? "intranet" : "public");

// admittable parameters
$admit = array(
  "c" => array("projects", "departments"), // controllers
  "m" => array("tudo", "list", "details"), // methods
  "out" => array("min", "pretty"), // output format
);

foreach ($admit as $param => $arr) {
  if (!array_key_exists($param, $_POST) || 
      !in_array($_POST[$param], $arr)) {
    $_POST[$param] = $arr[0]; // this makes the first element default
  }
}  

require_once(dirname(__FILE__)."/platform/config.php");
require_once(LIBRARY."core.php");
require_once(LIBRARY."mvc.php");
require_once(CLASSES."model.php");
require_once(CLASSES."controller.php");
require_once(CLASSES."template.php");
require_once(CLASSES."db.php");

require_once(LIBRARY."connect.php");

$q = $_POST["c"]."/api_".$_POST["m"];

if (($error_msg = call_hook($q)) != "") {
  json_die($error_msg);
}

mysql_close($db_link);

function json_die($error_msg) {
  if ($_POST["out"] == "pretty") {
    die(json_encode(array("error_msg" => $error_msg),
        JSON_PRETTY_PRINT));
  }
  else {
    die(json_encode(array("error_msg" => $error_msg)));
  }  
}

?>
