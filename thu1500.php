<?
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);

date_default_timezone_set("America/Los_Angeles");

// temporary, only when running the first time
/*
if ($argc < 4) {
  die("Usage: php tue0700.php d m Y\n");
}
$today = mktime(0, 0, 0, $argv[2], $argv[1], $argv[3]);
*/
$today = time();

require_once(dirname(__FILE__)."/config.php");
require_once(LIBRARY."core.php");
require_once(LIBRARY."classes/cron.php");

/* on the Thursday after last Monday of the month
   we send emails to all admins */
if (Cron::thu_after_last_monday($today)) {  // remove $today later
  require_once(LIBRARY."connect.php");
  
  /*
  $project_lists = array();
  foreach (array("need_update", "pending") as $which) {
    $projects = Cron::projects_by_flag($which);
    if (sizeof($projects) > 0) {
      $project_lists[$which] = "";
      // $link = ADMIN_URL."?q=project_statuses/need_update";
      foreach ($projects as $project) {
        $project_lists[$which] .= 
          $project["project_name"]. ", ".
          $project["project_building"].", ".
          $project["client_list"]."\n";
      }
    }
  }
  */
  // default messages
  $need_update_text = "
It looks like there're no projects that require status updates.
";
  $need_approval_text = "
It looks like there're no projects that need status approval.
";

//  if (sizeof($project_lists) > 0) {
//    if (is_var_valid($project_lists["need_update"])) {
      $need_update_text = "
Here is a list of projects that require status updates. ".

// $project_lists["need_update"]."
"You can update project statuses here:
".ADMIN_URL."?q=project_statuses/need_update";
//    } // projects that need update
//    if (is_var_valid($project_lists["pending"])) {
      $need_approval_text = "
Here is a list of projects that need status approval. ".

// $project_lists["pending"]."
"You can approve project statuses here:
".ADMIN_URL."?q=project_statuses/pending
";
//    } // projects with pending statuses
//  } // $project_lists is not empty

  // whether or not there're any projects, we send an email
  $headers = "From: ".EMAIL_SENDER;
  $admins = Cron::all_admin_users();
  // send an email to each admin
  foreach ($admins as $admin) {
    // if ($admin["user_email"] != "symlink7@gmail.com")
    //  continue;
    $admin_name = "$admin[user_first_name] $admin[user_last_name]";
    
    include(CONFIG."bulk_mail_templates.php");
    $body = $bulk_mail_templates["admin_update_approve_statuses"]["body"];
    mail($admin["user_email"],
      $bulk_mail_templates["admin_update_approve_statuses"]["subject"],
      $body, $headers);
  } // end of looping through admins

  mysql_close($db_link);
} // thu after last monday 15:00 routine

?>
