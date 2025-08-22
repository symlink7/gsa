<?

  if (is_var_valid($_REQUEST["q"])) {
    strip($_REQUEST["q"]);
    if (($error_msg = call_hook($_REQUEST["q"])) != "") {
      include(TEMPLATES."error.php");
    }
  }
  else {
    $error_msg = "Invalid request.";
    include(TEMPLATES."error.php");
  }

// otherwise closed in ajax.php / index.php
mysql_close($db_link);
