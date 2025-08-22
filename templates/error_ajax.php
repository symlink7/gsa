<?
$str = '<?xml version="1.0"?>';
$str .= "<response>";

// if there're errors, show them
if (sizeof($errors) > 0){
  $str .= "<error_msg><![CDATA[".implode("\n\n", $errors)."]]></error_msg>";
  $str .= "<error_fields>";
  if (is_arr_valid($missing) && sizeof($missing) > 0){
    foreach ($missing as $error_field)  
      $str .= "<field>$error_field</field>";
  }
  $str .= "</error_fields>";
}
  
$str .= "</response>";  
header ("Content-type: text/xml");
echo $str;
exit;
?>
