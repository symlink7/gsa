<?
// disable this until we need it again
exit;

require_once("./../config.php");

require_once(LIBRARY."core.php");
require_once(CLASSES."db.php");
require_once(LIBRARY."connect.php");

$query = "
  SELECT approved.project_id,
    approved.status_created_time,
    approved.status_creator_id,
    approved.status_approved_time,
    approved.status_approver_id,
    pd.project_bos_action AS status_descr
  FROM (
    SELECT *
    FROM project_statuses_archive
    WHERE status_approved = 'Y'
    AND status_type = 'overall'
    ORDER BY project_id, archive_status_id ASC
  ) AS approved
  LEFT JOIN project_details AS pd ON approved.project_id = pd.project_id
  GROUP BY approved.status_id
  ORDER BY approved.project_id";

$res = mysql_query($query) or die(mysql_error());
while ($row = mysql_fetch_assoc($res)) {
  $fields = array(
    "project_id" => "'".$row["project_id"]."'",
    "status_type" => "'bos_action'",
    "status_color" => "''",
    "status_descr" => "'".$row["status_descr"]."'",
    "status_approved" => "'Y'",
    "status_needs_update" => "'N'",
    "status_created_time" => "'".$row["status_created_time"]."'",
    "status_creator_id" => "'".$row["status_creator_id"]."'",
    "status_approved_time" => "'".$row["status_approved_time"]."'",
    "status_approver_id" => "'".$row["status_approver_id"]."'",
  );
  $resp = DB::insert("project_statuses", $fields, true);
  if (!preg_match("/^[0-9]{5,12}$/", $resp)) {
    echo "Error: ".$resp;  
  }
  else { // duplicate to archives table
    $fields["status_id"] = $resp;
    DB::insert("project_statuses_archive", $fields);
    echo "inserted bos_action for project_id #$project_id<br />\n";
  }  
}
mysql_close($db_link);

?>
