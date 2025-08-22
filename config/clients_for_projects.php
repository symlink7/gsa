<?

$table = "clients_for_projects";
$var_pfx = "";
$primkey = "";

$order_by = "project_id, client_id";

$db_fields = array(
  "project_id" => array(
    "int not null", "", "Project ID", 1, 1, "foreign", "projects(project_id)"),
  "client_id" => array(
    "int not null", "", "Client ID", 1, 1, "foreign", "clients(client_id)"),
);

?>
