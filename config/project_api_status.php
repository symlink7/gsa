<?

$table = "project_api_status";
$var_pfx = "project_";
$primkey = $var_pfx."id";

$descr_field = $primkey;
$order_by = $primkey;

$db_fields = array(
  $primkey => array("int not null", "", "Project ID", 0, 0),
  "project_show_to" => array("set", "'public','intranet'", "Show to", 1, 1),
);  

?>
