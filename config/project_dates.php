<?

$table = "project_dates";
$var_pfx = "date_";
$primkey = "project_id";

$descr_field = $primkey;
$order_by = $primkey;

$db_fields = array(
  $primkey => array( 
    "int not null", "", "Project ID", 0, 0, "foreign", "projects(project_id)"),
  $var_pfx."intake" => array(
    "date", "", "Intake Date", 1, 1),
  $var_pfx."cao_memo" => array(  
    "date", "", "CAO Memo", 1, 1),
  $var_pfx."handover" => array(
    "date", "", "Hand-over Date", 1, 1),
  $var_pfx."design_start" => array(
    "date", "", "Project Design Start Date", 1, 1),
  $var_pfx."construct_start" => array(
    "date", "", "Project Construction Start Date", 1, 1),
  $var_pfx."completion_orig" => array(
    "date", "", "Project Completion Date", 1, 1),
  $var_pfx."completion_revised" => array(
    "date", "", "Revised Completion Date", 1, 1),
);  

$permissions = array(
  "add" => array("A", "PA", "S"),
  "edit" => array("A", "PA", "S", "M"),
  // add permissions for methods as you add methods
);

$add_form_req_fields = array(
//  $var_pfx."intake",
  "schedule_status",
//  "schedule_status_descr"
);  
$edit_form_req_fields = array();

$status_fields = array("schedule");

?>
