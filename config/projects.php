<?

$table = "projects";
$var_pfx = "project_";
$primkey = $var_pfx."id";

$descr_field = $var_pfx."name";
$order_by = $var_pfx."name";

$db_fields = array(
  $primkey => array( 
    "int auto_increment", "", "Project ID", 0, 0, "primary"),
  $var_pfx."name" => array("varchar", 128, "Project Name", 1, 1),
  $var_pfx."building" => array("int", "", "Building Number", 1, 1),
  $var_pfx."number" => array("varchar", "16", "Project Number", 1, 1),
  $var_pfx."descr" => array("text", "", "Project Description", 1, 1),
  $var_pfx."department" => array(
    "int not null", "", "Project Department", 1, 1, 
    /*"foreign", "departments(dep_id)"*/),
  $var_pfx."type" => array("varchar", 32, "Project Type", 1, 1),
  $var_pfx."client_id" => array(
    "int not null", "", "Primary County Agency", 1, 1),
  $var_pfx."client_department" => array("varchar", 128, "Client Department", 1, 1),
  $var_pfx."client_contact" => array("varchar", 128, "Client Contact", 1, 1),
  $var_pfx."district" => array("varchar", 8, "District", 1, 1),
  $var_pfx."delivery_method" => array("varchar", 8, "Delivery Method", 1, 1),
  $var_pfx."manager" => array(
    "int not null", "", "Project Manager", 1, 1, 
    "foreign", "users(user_id)"),
  $var_pfx."supervisor" => array(
    "int not null", "", "Approver", 1, 1, 
    "foreign", "users(user_id)"),
  $var_pfx."author" => array(
    "int not null", "", "Author", 0, 0, 
    "foreign", "users(user_id)"),
  $var_pfx."steps_completed" => array(
    "tinyint not null", "", "Steps Completed", 0, 0),
  $var_pfx."deleted" => array("set", "'Y','N'", "Deleted", 0, 0, "N"),
  $var_pfx."archived" => array("set", "'Y','N'", "Archived", 0, 0, "N"),
);

$permissions = array(
  "add" => array("A", "S"),
  "view_all" => array("A", "S", "M"),
  "view_mine" => array("A", "S", "M"),
  "view_approved" => array("A", "S", "M", "D"),
  "export_all" => array("A", "S", "M"),
  "details" => array(),
  "edit" => array("A", "S", "M"),
  "delete" => array("A"),
  "archive" => array("A", "S"),
  "api_status" => array("A"),
  // add permissions for methods as you add methods
);

$add_form_req_fields = array(
  $var_pfx."name",
  $var_pfx."descr",
  $var_pfx."department",
  $var_pfx."type",
  $var_pfx."district",
  $var_pfx."manager",
  $var_pfx."supervisor",
  $var_pfx."client_id",
//  $var_pfx."clients", // in the form it will be called project_clients_arr
);  

$edit_form_req_fields = array(
  $var_pfx."name",
  $var_pfx."descr",
  $var_pfx."department",
  $var_pfx."type",
  $var_pfx."district",
  $var_pfx."manager",
  $var_pfx."supervisor",
  $var_pfx."client_id",
//  $var_pfx."clients",
  $var_pfx."phase",
  $var_pfx."percent_complete",
);

$project_type_options = array(
  "Building Lease",
  "Reconfiguration",
  "Space Request",
  "Furniture",
  "Easement",
  "Lease Amendment",
  "Construction",
  "Maintenance",
  "Sublease",
  "Purchase",
  "Tenant Improvement",
  "License Agreement",
  "New Equipment",
  "Services Agreement",
);

$project_district_options = array(
  "1" => "District 1",
  "2" => "District 2",
  "3" => "District 3",
  "4" => "District 4",
  "5" => "District 5",
  "CW" => "Countywide",
  "CDP" => "County Department Project",
);

$delivery_method_options = array(
  "DB" => "Design - Build",
  "DBB" => "Design - Bid - Build",
//  "CMAR" => "Construction Manager At Risk",
  "JOC" => "Job Order Contract",
  "AUC" => "Auction",
  "PUR" => "Purchase",
  "SA" => "Sales Agreement",
  "DIS" => "Disposition",
  "PRDB" => "Progressive D/B",
  "IDSO" => "IDSO",
  "TBD" => "TBD",
  "NA" => "N/A"
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

$status_options = array(
  "green" => "Green",
  "yellow" => "Yellow",
  "red" => "Red",
);

$step_titles = array(
  1 => "Project Details",
  2 => "Budget Details",
  3 => "Schedule Details",
  4 => "Project Phase & Status"
);

$step_controllers = array(
  1 => "projects",
  2 => "project_budgets",
  3 => "project_dates",
  4 => "project_details",
);
?>
