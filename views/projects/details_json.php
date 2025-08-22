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

if (sizeof($tudo) > 0) {
  $project = $tudo[0];
  if ($_POST["out"] == "pretty") {
    echo $json_str = json_encode($project, JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
  }
  else {
    echo $json_str = json_encode($project, JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);
  }  
}
else {
  if ($_POST["out"] == "pretty") {
    echo json_encode(array("error_msg" => "Project not found or not public."),
                     JSON_PRETTY_PRINT);
  }
  else {
    echo json_encode(array("error_msg" => "Project not found or not public."));
  }  
}                    
?>
