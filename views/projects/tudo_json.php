<?

if (is_var_valid($error_msg)) {
  if ($_POST["out"] == "pretty") {
    echo json_encode(array("error_msg" => $error_msg), 
                     JSON_PRETTY_PRINT);
  }
  else {
    echo json_encode(array("error_msg" => $error_msg));
  }  
  exit;
}

if ($_POST["out"] == "pretty") {
  $str = '
{
"departments" : ';  
  $json_str = json_encode($departments, JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
  $json_str = ltrim($json_str, "{");
  $json_str = rtrim($json_str, "}");

  $str .= $json_str;

  $str .= ', ';

  $str2 = '
"projects" : ';
  $json_str = json_encode($projects, JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
  $json_str = ltrim($json_str, "{");
  $json_str = rtrim($json_str, "}");
  $str2 .= $json_str;
  $str2 .= '
}'; 
}
else {
  $str = '{"departments":';
  $json_str = json_encode($departments, JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);
  $json_str = ltrim($json_str, "{");
  $json_str = rtrim($json_str, "}");
  $str .= $json_str;
  $str .= ",";

  $str2 = '"projects":';
  $json_str = json_encode($projects, JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);
  $json_str = ltrim($json_str, "{");
  $json_str = rtrim($json_str, "}");
  $str2 .= $json_str;
  $str2 .= '}';
}

echo $str.$str2;
?>
