<?

$table = "project_statuses_archive";
$var_pfx = "status_";
$primkey = "archive_status_id";

$descr_field = "project_id, {$var_pfx}$type";
$order_by = "project_id, {$var_pfx}$type";

$db_fields = array(
  $primkey => array(
    "int auto_increment", "", "Archive Status ID", 0, 0, "primary"),
  "status_id" => array(
    "int not null", "", "Status ID", 0, 0, "foreign", "project_statuses(status_id)"),
  "project_id" => array(  
    "int not null", "", "Project ID", 0, 0, "foreign", "projects(project_id)"),
  $var_pfx."type" => array(
    "set", "'budget','scope','schedule','overall','current_activity','critical_activity','risk','bos_action'", "Status type", 0, 0),
  $var_pfx."color" => array(
    "set", "'green', 'yellow', 'red'", "Status color", 1, 1, "green"),
  $var_pfx."action_date" => array("text", "", "Action date", 1, 1),
  $var_pfx."descr" => array("text", "", "Status explanation", 1, 1),
  $var_pfx."approved" => array("set", "'Y','N'", "Approved", 1, 1, "N"),
  $var_pfx."needs_update" => array("set", "'Y','N'", "Needs Update", 1, 1, "N"),
  $var_pfx."created_time" => array("datetime", "", "Date Created", 0, 0, "auto"),
  $var_pfx."creator_id" => array(
    "int not null", "", "Creator ID", 0, 0, "foreign", "users(user_id)"),
  $var_pfx."approved_time" => array("datetime", "", "Date Approved", 0, 0),
  $var_pfx."approver_id" => array(
    "int not null", "", "Approver ID", 0, 0),
);  

?>
