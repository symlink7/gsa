<?

class UsersController extends Controller {

  public function login($args = array()) {
    global $session;
    if (is_arr_valid($_POST)) {
      $_POST = strip($_POST);
      
      // process login form
      if ($this->User->check_login() == 1) {
        // login succesful
        $arr = array(
          "logged_in" => 1,
          "user_id" => $this->User->get_id(),
          "user_info" => $this->User->get_info(),
        ); 
        $session->start_session($arr);
       
        $index = "index"; //
        // (is_var_valid($_SESSION["staging"]) ?  "staging" : "index");
        // see first if redirection isn't necessary
        if (is_var_valid($_POST["q"])) {
          $arr = explode("=", $_POST["q"]);
          if ($arr[0] == "q" && is_var_valid($arr[1])) {
            header("Location: {$index}.php?".$_POST["q"]);
            exit;
          }  
        }
        // Director's front page
        if ($_SESSION["user_info"]["user_role"] == "D") {
          header("Location: {$index}.php?q=projects/view_approved");
          exit;
        }
        // display home instead of login
        $this->_template = new Template($this->_controller_name, "home");
      }
      else {
        // show login form with an error
        $this->set_var("login_email", $_POST["login_email"]);
        $this->set_var("login_password", $_POST["login_password"]);
        $this->set_var("error_msg", "Invalid email or password");
      }  
    }
    $this->render();
  }  

  public function home($args = array()) {
    // set some variables only
    $this->render();
  }

  public function edit_form($args = array()) {
    // users can only be added by admin
    if (is_var_valid($args[0]) &&   // user_id set
        $args[0] != $_SESSION["user_id"] && // someone else's acc 
        !$this->has_permissions("edit")
    ) {
      $this->set_var("error_msg", 
        "You have no permissions to edit another user's account.");
      $this->set_var("generic_template", "error.php");
    }
    else {
      $user_id = (is_var_valid($args[0]) ? $args[0] : $_SESSION["user_id"]);
      $this->User->set_info($user_id);
      $this->set_var("tudo", $this->User->get_info());
      $this->set_var("meta_title", "Admin - Account Details");
    }
    $this->render();
  }

  function edit($args = array()) {
    $errors = array();
    if ($this->validate_edit_form($_POST, 0) == 0) {
      $errors = $this->get_var("errors");
    }
    else if ($_POST["user_id"] != $_SESSION["user_id"] && // someone else's acc 
      !$this->has_permissions("edit")
    ) {
      $errors[] = "You have no permissions to edit another user's account.";
    }
    else if ($this->User->email_exists($_POST["user_email"], $_POST["user_id"]) == true) {
      $errors[] = "A user with this email already exists.";
      $errors[] = $this->User->get_error_msg();
    }  
    else if ($this->User->edit($_POST) == 0) {
      $error_msg = $this->User->get_error_msg();
      if ($error_msg == "") {
        $error_msg = "Couldn't edit user details.";
      }
      $errors[] = $error_msg;
    }
    else {
      if ($_POST["user_id"] == $_SESSION["user_id"]) {
        $this->User->set_info($_SESSION["user_id"]);
        $_SESSION["user_info"] = $this->User->get_info();
      }
      $this->set_var("generic_template", "success_ajax.php");
    }

    if (sizeof($errors) > 0) {
      $this->set_var("errors", $errors);
      $this->set_var("generic_template", "error_ajax.php");
    }

    $this->render();
  } // edit()

  public function delete($args = array()) {
    $user_id = is_var_valid($args[0]) ? $args[0] : "";
    $error_msg = "";    

    if ($user_id == "") {
      $error_msg = "Invalid ID.";
    }          
    else if ($user_id == $_SESSION["user_id"]) {
      $error_msg = "You're not allowed to delete your own account.";
    }
    else if (!$this->has_permissions("delete")) {
      $error_msg = "You have no permissions to delete user accounts.";
    }
    else if ($user_id == 10001) { // don't let them delete MY account!
      $error_msg = "You're not allowed to delete this account.";
    }  
    else if ($this->User->delete($user_id) == 0) {
      $error_msg = $this->User->get_error_msg();
    }
    else {
      $this->set_var("success_msg", "User account successfully deleted.");
    }
 
    $template = ($error_msg != "" ? "error.php" : "success.php");
    $this->set_var("error_msg", $error_msg);
    $this->set_var("generic_template", $template);

    $this->render();
  }

  function change_pass($args = array()) {
    $errors = array();
    $missing = array();

    if (!is_var_valid($_POST["user_id"])) {
      $errors[] = "Unknown ID.";
    }
    else if ($_POST["user_id"] != $_SESSION["user_id"] && // someone else's acc 
      !$this->has_permissions("change_pass")
    ) {
      $errors[] = "You have no permissions to change another user's password.";
    }
    else if ($_POST["user_id"] == 10001 && $_SESSION["user_id"] != 10001) { 
      // don't let them mess around with MY account!
      $errors[] = "You're not allowed to change the password for this account.";
    }
    // all fine with id's, let's check the passwords now
    else if (!is_var_valid($_POST["new_password"])) {
      $errors[] = "Please choose new password.";
      $missing[] = "new_password";
    }  
    else if (!valid_password($_POST["new_password"])) { 
      $errors[] = "Password needs to be in the format suggested.";
      $missing[] = "new_password";
    }
    else if ($_POST["new_password"] != $_POST["repeat_password"]) {
      $errors[] = "Passwords don't match.";
      $missing[] = "repeat_password";
    }
    // all fine with form submission, let's try the db now
    else if ($this->User->change_pass($_POST) == 0) {
      $error_msg = $this->User->get_error_msg();
      if ($error_msg == "") {
        $error_msg = "Couldn't change password.";
      }
      $errors[] = $error_msg;
    }
    if (sizeof($errors) > 0) {
      $this->set_var("errors", $errors);
      $this->set_var("missing", $missing);
      $this->set_var("generic_template", "error_ajax.php");
    }  
    else {
      $this->set_var("generic_template", "success_ajax.php");
    }  

    $this->render();
  } // end of change_pass()

  function reset_pass($args = array()) {
    $errors = array();
    $missing = array();

    if (!is_var_valid($_POST["user_email"])) {
      $errors[] = "Please enter email.";
			$missing[] = "user_email";
    }
		else {
      $email = stripslashes($_POST["user_email"]);
			$vars = $this->User->reset_pass($email);    
    	if (sizeof($vars) < 1) {
				$errors[] = "A user with email {$email} doesn't exist.";
				$missing[] = "user_email";
			}
			else {
				include(CONFIG."users.php");
    		$vars["user_role_name"] = $user_role_options[$vars["user_role"]];
    		$vars["admin_url"] = ADMIN_URL;
				$vars["reset_code"] = substr($vars["user_password"], 7, 10);
    		require_once(CLASSES."mail.php");
    		Mail::prepare_and_send($email, "reset_password", $vars);
   		}
		}
   	if (sizeof($errors) > 0) {
      $this->set_var("errors", $errors);
      $this->set_var("missing", $missing);
      $this->set_var("generic_template", "error_ajax.php");
    }
    else {
      $this->set_var("generic_template", "success_ajax.php");
    }

    $this->render();
	} // end of reset_pass()

  function reset_pass1($args = array()) {
    $this->set_var("user_email", $args[0]);
    $this->set_var("reset_code", $args[1]);
    $this->render();
  } // end of reset_pass1()

  function reset_pass2($args = array()) {
    $errors = array();

    $_POST = strip($_POST);
    extract($_POST);
    
    $missing = check_for_missing($_POST, 
      array("user_email", "reset_code", 
            "new_password", "new_password2"
           )
    );
    if (sizeof($missing) > 0) {
      $errors[] = "Some required fields are missing.";
    }
    else if ($new_password != $new_password2) {
      $errors[] = "Passwords don't match.";
      $missing[] = "new_password2";
    }
    else {
      $vars = $this->User->reset_pass($user_email);
      $_POST["user_id"] = $vars["user_id"];
      if (sizeof($vars) < 1) {
        $errors[] = "A user with email {$user_email} doesn't exist.";
        $missing[] = "user_email";
      }
      else if (substr($vars["user_password"], 7, 10) != $reset_code)  {
			  $errors[] = "Invalid reset code.";
        $missing[] = "reset_code";
			}
      else if ($this->User->change_pass($_POST) == 0) {
        $error_msg = $this->User->get_error_msg();
        if ($error_msg == "") {
          $error_msg = "Couldn't change password.";
        }
        $errors[] = $error_msg;
      }
		}

    if (sizeof($errors) > 0) {
      $this->set_var("errors", $errors);
      $this->set_var("missing", $missing);
      $this->set_var("generic_template", "error_ajax.php");
    }
    else {
      $this->set_var("generic_template", "success_ajax.php");
    }

    $this->render();
  } // end of reset_pass2()

}
