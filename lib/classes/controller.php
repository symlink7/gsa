<?

class Controller {
  protected $_model_name;
  protected $_controller_name;
  protected $_method_name;
  protected $_template;
 
  function __construct($model_name, $controller_name, $method_name) {
    $this->_model_name = $model_name;
    $this->_controller_name = $controller_name;
    $this->_method_name = $method_name;
    
    $this->$model_name = new $model_name($controller_name);

    $this->_template = new Template($controller_name, $method_name);
  }
 
  protected function set_var($varname, $value) {
    $this->_template->set_var($varname, $value);
  }
  protected function get_var($varname) {
    return $this->_template->get_var($varname);
  }  

  public function add_form($args = array()) {
    if (!$this->has_permissions("add")) {
      $this->set_var("error_msg", 
        "You have no permissions to add new {$this->_controller_name}.");
      $this->set_var("generic_template", "error.php");
    }
    else {
      $this->set_var("meta_title", "Admin - Add a ".$this->_model_name);
    }
    $this->render();
  }

  public function add($args = array()) {
    if ($this->validate_add_form($_POST) == 0) {
      $this->set_var("generic_template", "error_ajax.php");
    }
    else if ($this->{$this->_model_name}->add($_POST) == 0) {
      $error_msg = $this->{$this->_model_name}->get_error_msg();
      if ($error_msg == "") {
        $error_msg = "Couldn't add {$this->_model_name}.";
      }
      $this->set_var("errors", array($error_msg));
      $this->set_var("generic_template", "error_ajax.php");
    }
    else {
      $this->set_var("generic_template", "success_ajax.php");
    }
    $this->render();
  } // method add

  public function edit_form($args = array()) {
    $error_msg = "";
    if (!$this->has_permissions("edit")) {
      $error_msg =
        "You have no permissions to edit {$this->_controller_name}.";
    }
    else if (!is_var_valid($args[0])) {
      $error_msg = "Missing ID.";
    }  
    else {
      $id = $args[0];
      $this->{$this->_model_name}->set_info($id);
      $tudo = $this->{$this->_model_name}->get_info();
      
      if (sizeof($tudo) < 1) {
        $error_msg = "No record found for ID $id";
      }
      else {
        $this->set_var("tudo", $tudo);
        $this->set_var("meta_title", "Admin - Edit ".$this->_model_name);
      }
    }

    if ($error_msg != "") {
      $this->set_var("error_msg", $error_msg);
      $this->set_var("generic_template", "error.php");
    }
    
    $this->render();
  }

  function edit($args = array()) {
    if ($this->validate_edit_form($_POST) == 0) {
      $this->set_var("generic_template", "error_ajax.php");
    }
    else if ($this->{$this->_model_name}->edit($_POST) == 0) {
      $error_msg = $this->{$this->_model_name}->get_error_msg();
      if ($error_msg == "") {
        $error_msg = "Couldn't edit {$this->_model_name}.";
      }
      $this->set_var("errors", array($error_msg));
      $this->set_var("generic_template", "error_ajax.php");
    }
    else {
      $this->set_var("generic_template", "success_ajax.php");
    }
    $this->render();
  } // edit()


  protected function validate_add_form($vars, $check_permissions = 1) {
    // checking for missing required fields
    $errors = array();
    $missing = array();

    if (is_file(CONFIG.$this->_controller_name.".php")) {
      include(CONFIG.$this->_controller_name.".php");
    } 
    else {
      $this->set_var("errors", array("Couldn't find configuration file."));
      $this->set_var("missing", $missing);
      return 0;
    }

    if ($check_permissions && !$this->has_permissions("add")) {
      $this->set_var("errors", array(
        "You have no permissions to add {$this->_controller_name}."));
      return 0;
    }

    if (is_arr_valid($add_form_req_fields)) {
      foreach ($add_form_req_fields as $varname) {
        if (!is_var_valid($vars[$varname])) {
          $missing[] = $varname;
        }
      }

      if (sizeof($missing) > 0){
        $errors[] = "Some required fields are missing.";
        $this->set_var("errors", $errors);
        $this->set_var("missing", $missing);
        return 0;
      }
    }
    if (is_var_valid($email_field) &&
        is_var_valid($vars[$email_field]) &&
        !valid_email($vars[$email_field])){
      $errors[] = "Invalid email.";
      $missing[] = $email_field;
      $this->set_var("errors", $errors);
      $this->set_var("missing", $missing);
      return 0;
    }
    return 1;
  } // validate_add_form

  protected function validate_steps_add_form($vars, $section) {
    // checking for missing required fields
    $errors = array();
    $missing = array();

    if (is_file(CONFIG.$section.".php")) {
      include(CONFIG.$section.".php");
    }
    else {
      $this->set_var("errors", array("Couldn't find configuration file."));
      $this->set_var("missing", $missing);
      return 0;
    }
    
    if (is_arr_valid($add_form_req_fields)) {
      foreach ($add_form_req_fields as $varname) {
        if (!is_var_valid($vars[$varname])) {
          $missing[] = $varname;
        }
      }
      if (sizeof($missing) > 0){
        $errors[] = "Some required fields are missing.";
        $this->set_var("errors", $errors);
        $this->set_var("missing", $missing);
        return 0;
      }
    }

    if (is_var_valid($email_field) &&
        is_var_valid($vars[$email_field]) &&
        !valid_email($vars[$email_field])){
      $errors[] = "Invalid email.";
      $missing[] = $email_field;
      $this->set_var("errors", $errors);
      $this->set_var("missing", $missing);
      return 0;
    }
    return 1;
  } // validate_sub_add_form

  protected function validate_edit_form($vars, $check_permissions = 1) {
    if (is_file(CONFIG.$this->_controller_name.".php")) {
      include(CONFIG.$this->_controller_name.".php");
    }
    else {
      $this->set_var("errors", array("Couldn't find configuration file."));
      $this->set_var("missing", array());
      return 0;
    }

    if ($check_permissions && !$this->has_permissions("edit")) {
      $this->set_var("errors", array(
        "You have no permissions to edit {$this->_controller_name}."));
      return 0;
    }

    // checking for missing id
    if (!is_var_valid($vars[$primkey])) {
      $this->set_var("errors", array("Invalid ID."));
      $this->set_var("missing", array());
      return 0;
    }
    else {
      return $this->validate_add_form($vars, 0);
    }
  }

  function view_all($args = array()) {
    if (!$this->has_permissions("view_all")) {
      $this->set_var("error_msg",
        "You have no permissions to view {$this->_controller_name}.");
      $this->set_var("generic_template", "error.php");
    }
    else {
      $this->set_var("meta_title", 
        "Admin - View all ".ucfirst($this->_controller_name));
      $arr = array("order" => $this->{$this->_model_name}->_order_by);
      $this->set_var("tudo", $this->{$this->_model_name}->get_all($arr));
    }
    $this->render();
  } // end of view_all()

  public function delete($args = array()) {
    $id = is_var_valid($args[0]) ? $args[0] : "";
    $error_msg = "";

    if ($id == "") {
      $error_msg = "Invalid ID.";
    }          
    else if (!$this->has_permissions("delete")) {
      $error_msg = "You have no permissions to delete {$this->_controller_name}.";
    }
    else if ($this->{$this->_model_name}->delete($id) == 0) {
      $error_msg = $this->{$this->_model_name}->get_error_msg();
    }
    else { 
      $this->set_var("success_msg", $this->_model_name." successfully deleted.");
    }
 
    $template = ($error_msg != "" ? "error.php" : "success.php");    
    $this->set_var("error_msg", $error_msg);
    $this->set_var("generic_template", $template);

    $this->render();
  } // end of delete()

  protected function has_permissions($method) {
    $permissions = $this->{$this->_model_name}->get_permissions();
    if (is_arr_valid($permissions) &&
        is_arr_valid($permissions[$method]) &&
        !in_array($_SESSION["user_info"]["user_role"], $permissions[$method])
    ) {
      return false;
    }
    else {
      return true;
    }  
  }

  protected function render() {
    $this->_template->render();
  }

  protected function die_ajax($error_msg = "") {
    if (is_arr_valid($error_msg)) {
      $this->set_var("errors", $error_msg);
    }  
    else if (is_var_valid($error_msg)) {
      $this->set_var("errors", array($error_msg));
    }
    $this->set_var("generic_template", "error_ajax.php");
    $this->_template->render();
    exit;
  }

  protected function die_json($error_msg = "") {
    $this->set_var("error_msg", $error_msg);
    $this->set_var("generic_template", "error_json.php"); 
    $this->_template->render();
    exit;
  }

  function __destruct() {
  }
}
