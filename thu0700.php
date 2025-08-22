<?
ini_set('error_reporting', "E_ALL ^ E_NOTICE");
ini_set('display_errors', 1);
date_default_timezone_set("America/Los_Angeles");

// temporary, only when running the first time
/*
if ($argc < 4) {
  die("Usage: php tue0700.php d m Y\n");
}
$today = mktime(0, 0, 0, $argv[2], $argv[1], $argv[3]);
*/

$today = mktime();

require_once(dirname(__FILE__)."/config.php");
require_once(LIBRARY."core.php");
require_once(LIBRARY."classes/cron.php");

/* on the thursday after last Monday of the month
  we also send emails to approvers */
if (Cron::thu_after_last_monday($today)) {  // remove $today later
  require_once(LIBRARY."connect.php");

  // get the ids of all existing completed projects
  $project_ids = Cron::all_existing_projects();
  if (sizeof($project_ids) > 0) {
    // get all approvers
    $supervisors = Cron::all_project_supervisors();
    
    // some common variables
    $headers = "From: ".EMAIL_SENDER;
    $link_to_pending_statuses = ADMIN_URL."?q=project_statuses/pending";
      
    // send an email to each
    foreach ($supervisors as $supervisor) {
      // if ($supervisor["user_email"] != "symlink7@gmail.com")
      //  continue;
      $approver_name = "$supervisor[user_first_name] $supervisor[user_last_name]";
      include(CONFIG."bulk_mail_templates.php");
      $body = $bulk_mail_templates["statuses_need_approval2"]["body"];
      mail($supervisor["user_email"],
        $bulk_mail_templates["statuses_need_approval2"]["subject"],
	$body, $headers);
      // echo "$headers, $body to ".$supervisor["user_email"]."\n\n";
    }
  }    
  mysql_close($db_link);
} // thu after last monday routine
?>
