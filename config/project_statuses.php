<?

$table = "project_statuses";
$var_pfx = "status_";
$primkey = $var_pfx."id";

$descr_field = "project_id, {$var_pfx}$type";
$order_by = "project_id, {$var_pfx}$type";

$db_fields = array(
  $primkey => array(
    "int auto_increment", "", "Status ID", 0, 0, "primary"),
  "project_id" => array(  
    "int not null", "", "Project ID", 0, 0, "foreign", "projects(project_id)"),
  $var_pfx."type" => array(
    "set", "'budget','scope','schedule','overall','current_activity','critical_activity','risk','bos_action'", "Status type", 0, 0),
  $var_pfx."color" => array(
    "set", "'green', 'yellow', 'red'", "Status color", 1, 1, "green"),
  $var_pfx."descr" => array("text", "", "Status explanation", 1, 1),
  $var_pfx."approved" => array("set", "'Y','N'", "Approved", 1, 1, "N"),
  $var_pfx."needs_update" => array("set", "'Y','N'", "Needs Update", 1, 1, "N"),
  $var_pfx."created_time" => array("datetime", "", "Date Created", 1, 1, "auto"),
  $var_pfx."creator_id" => array(
    "int not null", "", "Creator ID", 1, 1, "foreign", "users(user_id)"),
  $var_pfx."approved_time" => array("datetime", "", "Date Approved", 1, 1, "on-demand"),
  $var_pfx."approver_id" => array(
    "int not null", "", "Approver ID", 1, 1),
  /* initially all statuses will be show_gray=Y. 
     once they get approved once, they will never again show as gray */
);  

$permissions = array(
  "add" => array("A", "S"),
  "edit" => array("A", "S"),
  // S-UPDATE-ALL : added S in for_project & update
  "for_project" => array("A", "M", "S"), // update status for project
  "update" => array("A", "M", "S"),
  "pending" => array("A", "S", "M"), // projects awaiting approval
  "approve" => array("A", "S"), 
  "delete" => array(),
  // add permissions for methods as you add methods
);

$status_options = array(
  "green" => "Green",
  "yellow" => "Yellow",
  "red" => "Red",
);

$update_req_fields = array(
  "budget" => array("budget_status"), // "budget_status_descr"),
//  "scope" => array("scope_status"), // "scope_status_descr"),
  "schedule" => array("schedule_status"), // "schedule_status_descr"),
  "overall" => array("overall_status"), // "overall_status_descr"),
  "risk" => array("risk_status_descr"),
  "current_activity" => array(),
  "critical_activity" => array(),
  "bos_action" => array("bos_action_status_descr"),
);  

$critical_activity_options = array(
  "complete_drawings" => "Complete Design Drawings",
  "issue_rfqp" => "Issue RFQ/P",
  "request_approval" => "Request Board Approval",
  "needs_cc_review" => "Needs County Counsel Review",
  "award_contract" => "Award Construction Contract",
  "increase_contract" => "Need to Increase Contract",
  "extend_contract" => "Need to Extend Contract",
  "change_order" => "Negotiate Change Order",
  "change_schedule" => "Change Project Schedule",
  "increase_budget" => "Increase Project Budget",
  "terminate_contract" => "Terminate Contract",
  "negot_lease_agree" => "Negotiate Lease Agreement",
  "obtain_memo_approval" => "Obtain CAO Memo Approval",
  "request_appraisal" => "Request Appraisal",
  "real_estate_due_dil" => "Real Estate Due Diligence",
  "ceqa" => "CEQA",
  "none" => "None",
  "other" => "Other: ",
);

$bos_action_options = array(
  "amend" => "Amend",
  "award" => "Award",
  "add_to_cip" => "Add to CIP Capital Improvement Plan",
  "other" => "Other: ",
);  

$project_status_names = array(
  'budget' => "Budget",
  // 'scope' => "Scope",
  'schedule' => "Schedule",
  'overall' => "Scope/Overall",
  'current_activity' => "Current Activity",
  'critical_activity' => "Critical Activity",
  'risk' => "Risk",
  'bos_action' => "BOS Action",
);

// eval('$arr = array('.$db_fields[$var_pfx."type"][1].");");
// print_r($arr);
?>
