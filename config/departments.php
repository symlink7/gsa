<?

$table = "departments";
$var_pfx = "dep_";
$primkey = $var_pfx."id";

$descr_field = $var_pfx."name";
$order_by = $var_pfx."name";

$db_fields = array(
  $primkey => array("int auto_increment", "", "Dep ID", 0, 0, "primary"),
  $var_pfx."name" => array("varchar", 32, "Department Name", 1, 1),
);

$permissions = array(
  "add" => array("A"),
  "edit" => array("A"),
  "delete" => array("A"),
  "view_all" => array("A"),
);

$add_form_req_fields = array(
  $var_pfx."name",
);

?>
