<?

$table = "project_details";
$var_pfx = "project_";
$primkey = $var_pfx."id";

$descr_field = $primkey;
$order_by = $primkey;

$db_fields = array(
  $primkey => array( 
    "int not null", "", "Project ID", 0, 0, "foreign", "projects(project_id)"),
  $var_pfx."phase" => array("varchar", 64, "Project Phase", 1, 1),
  $var_pfx."percent_complete" => array(
    "tinyint not null", "", "Percent Complete", 1, 1),
  $var_pfx."category" => array("set", "'1','2','3','4','5','6'", "Categories I - VI", 1, 1),
);  

$permissions = array(
  "add" => array("A", "S"),
  "edit" => array("A", "S"),
  // add permissions for methods as you add methods
);

$add_form_req_fields = array(
  $var_pfx."phase",
  $var_pfx."percent_complete",
//  $var_pfx."category",
//  "current_activity_status_descr",
//  "critical_activity_status_descr",
  "risk_status_descr",
//  "scope_status",
//  "scope_status_descr",
  "overall_status",
//  "overall_status_descr",
  "bos_action_status_descr",
);  

$project_phase_options = array(
  "Intake",
  "CAO/Board Approval",
  "Development",
  "Design",
  "Construction",
  "Close out",
  "On Hold",
  "Negotiation",
  "Execution",
  "Hold Over",
  "Market Search",
);

$project_category_options = array(
  "1" => "I",
  "2" => "II",
  "3" => "III",
  "4" => "IV",
  "5" => "V",
  "6" => "VI",
);

$status_fields = array(/*"scope", */"overall", "current_activity", 
                       "critical_activity", "risk", "bos_action");
?>
