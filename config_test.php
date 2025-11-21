<?
date_default_timezone_set("America/Los_Angeles");
set_time_limit(0);

define('TEST_SITE', 1);
define('ADMIN_URL', "https://carriedelucchi.com/gsa-test/index.php");
define('EMAIL_SENDER', '"GSA" <cardel42@vps16809.dreamhostps.com>'); // admin@cardel42.dreamhosters.com>');

define('HOME', dirname(__FILE__));
define('LIBRARY', HOME."/lib/");
define('CLASSES', LIBRARY."classes/");
define('TEMPLATES', HOME."/templates/");
define('MODELS', HOME."/models/");
define('CONTROLLERS', HOME."/controllers/");
define('VIEWS', HOME."/views/");
define('CONFIG', HOME."/config/");
define('UPLOADS', HOME."/../uploads/");
define('UPLOADS_WEB', "uploads/");

define('DB_HOST', "mysql.cardel42.dreamhosters.com");
define('DB_USER', "gsatest");
define('DB_PASS', "rH5iT4mu84dq");
define('DB_NAME', "gsaprojects_test");

$nologin = array("users/reset_pass", "users/reset_pass1", "users/reset_pass2");
?>
