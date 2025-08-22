<?
// disable this until we need it again
exit;

require_once("./../config.php");

require_once(LIBRARY."core.php");
require_once(LIBRARY."connect.php");

$query = "SELECT count(*) as c, project_id, client_id FROM `clients_for_projects` group by project_id having c < 2";
$res = mysql_query($query) or die("$query: ".mysql_error());

while ($row = mysql_fetch_assoc($res)) {
  $query = "update projects 
            set 
            project_client_id='".$row["client_id"]."'
            where project_id='".$row["project_id"]."'
           "; 
  mysql_query($query) or die("$query: ".mysql_error());           
}
mysql_close($link);
?>
