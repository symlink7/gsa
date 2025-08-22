<?

function call_hook($str) {
  $error_msg = "";
  $arr = array();
  $arr = explode("/", $str);
 
  $controller = "";
  $method_name = "";
  $query = array();

  if (is_var_valid($arr[0])) {
    $controller = array_shift($arr);
  }
  if (is_var_valid($arr[0])) {
    $method_name = array_shift($arr);
  }
  
  if (is_var_valid($arr[0])) {
    $query = $arr;
  }  

  $controller_name = $controller;
  $controller = ucfirst($controller).'Controller';
  
  $model_name = rtrim(ucfirst($controller_name), "s");
  if (preg_match("/se$/", $model_name)) {
    $model_name = rtrim($model_name, "se") . "s";
  }

  if (load_classes($controller_name)) {
    $dispatch = new $controller(
      $model_name,
      $controller_name, 
      $method_name
    );
    
    if ((int)method_exists($controller, $method_name)) {
      call_user_func(array($dispatch, $method_name), $query);
    } 
    else {
      $error_msg = "Couldn't find method $controller/$method_name.";
    }
  }
  else {
    $error_msg = "Couldn't find controller $controller_name.";
  }
  return $error_msg;
}

function load_classes($controller_name) {
  $success = 1;
  if (is_file(CONTROLLERS.$controller_name.".php")) {
    require_once(CONTROLLERS.$controller_name.".php");
  }
  else {
    $success = 0;
  }  
  
  $model_name = rtrim($controller_name, "s");
  if (preg_match("/se$/", $model_name)) {
    $model_name = rtrim($model_name, "se") . "s";
  }
  if (is_file(MODELS.$model_name.".php")) {
    require_once(MODELS.$model_name.".php");
  }
  else {
    $success = 0;
  }
  return $success;
}
