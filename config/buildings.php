<?

$table = "buildings";
$var_pfx = "build_";
$primkey = $var_pfx."number";

$descr_field = "concat($primkey, ': ', ".
               "{$var_pfx}address, ', ', {$var_pfx}city)";
$order_by = $var_pfx."number";

$db_fields = array(
  $primkey => array("int not null", "", "Building Number", 0, 0, "primary"),
  $var_pfx."address" => array("varchar", 64, "Address", 1, 1),
  $var_pfx."city" => array("varchar", 24, "City", 1, 1),
  $var_pfx."zip" => array("varchar", 10, "Zipcode", 1, 1),
  $var_pfx."type" => array("varchar", 24, "Property Type", 1, 1),
);

$permissions = array(
  "add" => array("A"),
  "edit" => array("A"),
  "delete" => array("A"),
  "view_all" => array("A"),
);

$add_form_req_fields = array(
  $var_pfx."number",
//  $var_pfx."address",
//  $var_pfx."city",
//  $var_pfx."type"
);

?>
