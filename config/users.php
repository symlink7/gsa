<?

$table = "users";
$var_pfx = "user_";
$primkey = $var_pfx."id";

$email_field = $var_pfx."email";
$descr_field = "concat({$var_pfx}last_name, ', ', {$var_pfx}first_name)";
$order_by = "{$var_pfx}last_name, {$var_pfx}first_name";

$db_fields = array(
  // field descr, field descr, field name, view, edit, more
  $primkey => array("int auto_increment", "", "User ID", 0, 0, "primary"),
  $var_pfx."first_name" => array("varchar", 24, "First Name", 1, 1),
  $var_pfx."last_name" => array("varchar", 24, "Last Name", 1, 1),
  $var_pfx."phone" => array("varchar", 24, "Phone", 1, 1),
  $var_pfx."email" => array("varchar", 128, "Email", 1, 1),
  $var_pfx."password" => array("varchar", 128, "Password", 0, 0, "password"),
  $var_pfx."role" => array("set", "'A','PA','M','S','D'", "Role", 1, 1, "M"),
  $var_pfx."project_type" => array("varchar", 32, "Project Type", 1, 1),
  $var_pfx."deleted" =>array("set", "'Y','N'", "Deleted", 0, 0, "N"),

);

$user_role_options = array(
  "M" => "Project Manager",
  "PA" => "Project Admin",
  "A" => "Admin",
  "S" => "Approver",
  "D" => "Director",
);

$permissions = array(
  "add" => array("A"),
  "edit" => array("A"),
  "delete" => array("A"),
  "view_all" => array("A"),
  "change_pass" => array("A"),
);

$add_form_req_fields = array(
  $var_pfx."first_name", 
  $var_pfx."last_name", 
  $var_pfx."email", 
  $var_pfx."phone",
  $var_pfx."role"
);

?>
