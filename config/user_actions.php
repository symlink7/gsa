<?
$section_name = "User Activity";
$record_name = "record";

$table = "user_actions";
$primkey = "user_action_id";

$db_fields = array(
  $primkey => array("int auto_increment not null", "", "ID", ""),
  "user_id" => array("int not null", "", "User ID", ""),
  "action_descr" => array("varchar", "255", "Action", "1", ""),
  "action_time" => array("datetime", "", "Time", "1", "", "auto"),
  "action_ip" => array("varchar", "15", "IP", "1", "", "auto"),
);

?>
