<?
ini_set('error_reporting', "E_ALL ^ E_NOTICE");
ini_set('display_errors', 1);
session_start();

require_once(dirname(__FILE__)."/platform/config.php");

require_once(LIBRARY."core.php");
require_once(LIBRARY."mvc.php");
require_once(CLASSES."model.php");
require_once(CLASSES."controller.php");
require_once(CLASSES."template.php");
require_once(CLASSES."db.php");

$errors = array();

// check session
require_once(CLASSES."session.php");
$session = new Session(isset($_GET["logout"]) ? 1 : 0);

// connect to the database
require_once(LIBRARY."connect.php");

// figure out which page to show
if ($session->get_session_on() == 0) {
  if (is_var_valid($_POST["q"]) && in_array($_POST["q"], $nologin)) {
    include("nologin_ajax.php");
    exit;
  }
  $errors[] = "You're not logged in.";
  include(TEMPLATES."error_ajax.php");
}  
else {
  // check url and call_hook
  if (is_var_valid($_POST["q"])) {
    strip($_POST["q"]);
    if (($error_msg = call_hook($_POST["q"])) != "") {
      $errors[] = $error_msg;
      include(TEMPLATES."error_ajax.php");
    }   
  }
  else {
    $errors[] = "Invalid request.";
    include(TEMPLATES."error_ajax.php");
  }  
}

// disconnect from database
mysql_close($db_link);

?>
