<?

  if (is_var_valid($_POST["q"]) && in_array($_POST["q"], $nologin)) {
    strip($_POST["q"]);
    if (($error_msg = call_hook($_POST["q"])) != "") {
      $errors[] = $error_msg;
      include(TEMPLATES."error_ajax.php");
    }
  }
  else {
    $errors[] = "Invalid request.";
    include(TEMPLATES."error_ajax.php");
  }

// otherwise closed in ajax.php / index.php
mysql_close($db_link);
