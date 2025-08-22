<?
$str = '{ "error" : 0, "msg" : "'.$confirm_msg.'" }';
header("Content-type: text/json");
// header('Content-type: application/json');
echo $str;
exit;
?>
