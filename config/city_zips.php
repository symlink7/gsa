<?

$table = "city_zips";
$var_pfx = "project_";
$primkey = $var_pfx."id";

$order_by = "project_id";

$db_fields = array(
  $primkey => array(
    "int not null", "", "Project ID", 1, 1, "foreign", "projects(project_id)"),
  $var_pfx."city" => array("varchar", "32", "City", 1, 1),
  $var_pfx."zip" => array("varchar", "10", "Zip", 1, 1),
);

$permissions = array(
  "main_form" => array("A"),
  "add" => array("A"),
  "edit" => array("A"),
);  

$add_form_req_fields = array(
  $primkey,
);

$add_form_req_fields = array(
  $primkey,
);  
?>
