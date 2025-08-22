<?

$table = "clients";
$var_pfx = "client_";
$primkey = $var_pfx."id";

$descr_field = $var_pfx."name";
$order_by = $var_pfx."name";

$db_fields = array(
  $primkey => array("int auto_increment", "", "User ID", 0, 0, "primary"),
  $var_pfx."name" => array("varchar", 32, "Client Name", 1, 1),
  $var_pfx."code" => array("varchar", 16, "Client Code", 1, 1),
//  $var_pfx."deleted" =>array("set", "'Y','N'", "Deleted", 0, 0, "N"),
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
