<?
$str = '{ "error" : 1, "msg" : "'.$error_msg.'" }';
header('Content-type: text/json');
// header('Content-type: application/json');
echo $str;
exit;
?>
