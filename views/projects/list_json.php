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
"projects" : ';  
  // JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
  $json_str = json_encode($tudo, JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
  $json_str = ltrim($json_str, "{");
  $json_str = rtrim($json_str, "}");
  $str .= $json_str;
  $str .= '
}';
}
else {
  $str = '{"projects":';
  $json_str = json_encode($tudo, JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);
  $json_str = ltrim($json_str, "{");
  $json_str = rtrim($json_str, "}");
  $str .= $json_str;
  $str .= "}";
}  
echo $str;
?>
