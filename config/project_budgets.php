<?

$table = "project_budgets";
$var_pfx = "budget_";
$primkey = "project_id";

$descr_field = $primkey;
$order_by = $primkey;

$db_fields = array(
  $primkey => array( 
    "int not null", "", "Project ID", 0, 0, "foreign", "projects(project_id)"),
  $var_pfx."approved_orig" => array("bigint", "", "CAO Original Budget", 1, 1),
  $var_pfx."approved_revised" => array("bigint", "", "Board Approved Revised Budget", 1, 1),
  $var_pfx."available" => array("bigint", "", "Available Budget", 1, 1),
);  

$permissions = array(
  "add" => array("A", "PA", "S"),
  "edit" => array("A", "PA", "S"),
  // add permissions for methods as you add methods
);

$add_form_req_fields = array(
  "budget_status",
//  "budget_status_descr"
);  
$edit_form_req_fields = array();

$status_fields = array("budget");
?>
