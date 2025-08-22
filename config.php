<?
date_default_timezone_set("America/Los_Angeles");
set_time_limit(0);
define('TEST_SITE', 0);
define(ADMIN_URL, "https://gsaprojects.acgov.org/index.php");
define(EMAIL_SENDER, '"GSA" <cardel42@vps16809.dreamhostps.com>'); // admin@cardel42.dreamhosters.com>');

define(HOME, dirname(__FILE__));
define(LIBRARY, HOME."/lib/");
define(CLASSES, LIBRARY."classes/");
define(TEMPLATES, HOME."/templates/");
define(MODELS, HOME."/models/");
define(CONTROLLERS, HOME."/controllers/");
define(VIEWS, HOME."/views/");
define(CONFIG, HOME."/config/");
define(UPLOADS, HOME."/../uploads/");
define(UPLOADS_WEB, "uploads/");
/*
define(DB_HOST, "internal-db.s170385.gridserver.com");
define(DB_USER, "db170385");
define(DB_PASS, "pass4mtcDB");
define(DB_NAME, "db170385_gsa");
 */
define(DB_HOST, "mysql.cardel42.dreamhosters.com");
// define(DB_HOST, "pogar.pdx1-mysql-a7-6a.dreamhost.com");
define(DB_USER, "gsa");
define(DB_PASS, "93hUshKfwj@");
define(DB_NAME, "gsaprojects");

$nologin = array("users/reset_pass", "users/reset_pass1", "users/reset_pass2");
?>
