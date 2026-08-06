<?
require_once(VIEWS."project_statuses/widgets_modals.php");

class Project_statusesController extends Controller {
  private $status_info;
  private $project_info;

  /* this is for the page Update Status for a selected project
     should come with project_id in the args */  
  function for_project($args = array()) {
    // check permissions
    $error_msg = "";
    if (!$this->has_permissions("for_project")) {
      $error_msg = "You have no permissions to use this functionality.";
    }
    else if (!is_var_valid($args[0])) {
      $error_msg = "Invalid request.";
    }
    else {
      $tudo = $this->{$this->_model_name}->project_info($args[0]);
      
      if (sizeof($tudo) < 1) {
        $error_msg = $this->{$this->_model_name}->get_error_msg();
      }
      else if ($tudo["project_archived"] == "Y") {
        $error_msg = "Project is archived and its ".
                     "statuses cannot be modified.";
      }  
      else if ($_SESSION["user_info"]["user_role"] == "M" &&
               $tudo["project_manager"] != $_SESSION["user_id"]
      ) {
        $error_msg = "You have no permissions to update statuses for this project.";
      }
      else {
        $this->set_var("tudo", $tudo);
      }  
    }
    
    if ($error_msg != "") {  
      $this->set_var("error_msg", $error_msg);
      $this->set_var("generic_template", "error.php");
    }

    $this->render();
  } // for_project()

  function pending($args = array()) {
    // check permissions
    $error_msg = "";
    if (!$this->has_permissions("pending")) {
      $error_msg = "You have no permissions to use this functionality.";
    }
    else {
      $can_update = 0;
      $can_edit = 0;
      $can_approve = 0;

      // we might have project_id as $args[0]
      $project_id = ($args[0] ? $args[0] : 0);
      if (in_array($_SESSION["user_info"]["user_role"], array("A", "PA"))) {
        // show all projects that have pending statuses
        $can_edit = 1;
        $can_approve = 1;
        $tudo = $this->{$this->_model_name}->pending_statuses($project_id);
      }
      else if ($_SESSION["user_info"]["user_role"] == "S") {
        // show all projects assigned to that supervisor
        // and having pending statuses
        $can_update = 1; // S-UPDATE-ALL
        $can_edit = 1;
        $can_approve = 1;
        $tudo = $this->{$this->_model_name}->pending_statuses(
          $project_id, 0, $_SESSION["user_id"]);
      }
      else if ($_SESSION["user_info"]["user_role"] == "M") {
        // show all projects assigned to this manager
        // and having pending statuses
        $can_update = 1;
        $tudo = $this->{$this->_model_name}->pending_statuses(
          $project_id, $_SESSION["user_id"], 0);
      }

      // get departments for the filter list
      $departments = DB::get_fields(array(
        "model" => "departments",
        "order" => "dep_name",
      ));  

      $this->set_var("can_edit", $can_edit);
      $this->set_var("can_update", $can_update);
      $this->set_var("can_approve", $can_approve);
      $this->set_var("tudo", $tudo);
      $this->set_var("departments", $departments);
    }
    
    $error_msg = $this->{$this->_model_name}->get_error_msg(); 
    if ($error_msg != "") {  
      $this->set_var("error_msg", $error_msg);
      $this->set_var("generic_template", "error.php");
    }
    $this->render();
  } // pending()

  function need_update($args = array()) {
    // check permissions
    $error_msg = "";
    if (!$this->has_permissions("need_update")) {
      $error_msg = "You have no permissions to use this functionality.";
    }
    else {
      $can_update = 0;
      $can_edit = 0;
      $can_approve = 0;

      if (in_array($_SESSION["user_info"]["user_role"], array("A", "PA", "S"))) {
        // show all projects that have pending statuses
        $can_update = 1;
        $tudo = $this->{$this->_model_name}->expired_statuses();
      }
      else if ($_SESSION["user_info"]["user_role"] == "M") {
        // show all projects assigned to this project manager
        // and having expired statuses
        $can_update = 1;
        $tudo = $this->{$this->_model_name}->expired_statuses($_SESSION["user_id"]);
      }

      // get departments for the filter list
      $departments = DB::get_fields(array(
        "model" => "departments",
        "order" => "dep_name",
      )); 

      $this->set_var("can_edit", $can_edit);
      $this->set_var("can_update", $can_update);
      $this->set_var("can_approve", $can_approve);
      $this->set_var("tudo", $tudo);
      $this->set_var("departments", $departments);
    }
    
    $error_msg = $this->{$this->_model_name}->get_error_msg(); 
    if ($error_msg != "") {  
      $this->set_var("error_msg", $error_msg);
      $this->set_var("generic_template", "error.php");
    }
    $this->render();
  } // need_update()

  function update_form($args = array()) {
    // check permissions
    $error_msg = $this->update_validation1($args);
    if ($error_msg != "") {
      echo modal_error($error_msg);
      exit;
    }
    echo modal_update_content($this->project_info, $this->status_info);
  } // end of update_form()

  function update($args = array()) {
    $error_msg = $this->update_validation1($args);
    if ($error_msg != "") {
      $this->die_ajax($error_msg);
    }  
    // if it worked this far, process the $_POST
    include(CONFIG.$this->_controller_name.".php");
    $req = $update_req_fields[$this->status_info["status_type"]];

    // transform critical_activity here

    if ($this->status_info["status_type"] == "critical_activity" ||
				$this->status_info["status_type"] == "bos_action") {
			$this->checkboxes_to_descr($this->status_info["status_type"]);
		}
    
    $missing = missing_fields($_POST, $req);
    if (sizeof($missing) > 0) {
      $this->set_var("missing", $missing);
      $this->die_ajax("Some required fields are missing.");
    } 

    if ($this->status_info["status_type"] == "bos_action" &&
      is_var_valid($_POST["status_action_date"])) {
      if (!valid_date($_POST["status_action_date"])) {
        $missing[] = "status_action_date";
        $this->set_var("missing", $missing);
        $this->die_ajax("Invalid date or format.<br />".
          "Valid date format: YYYY-mm-dd.<br />".
          "Valid year range: from 2000 to current year + 20"
        );
      }
    }  

    $vars = array_merge($this->status_info, $_POST); 

    if ($this->{$this->_model_name}->update_status($vars) == 0) {
      $error_msg = $this->{$this->_model_name}->get_error_msg();
      if ($error_msg == "") {
        $error_msg = "Couldn't update status for some reason.";
      }
      $this->die_ajax($error_msg);
    }
    else {
      if ($_POST["notify_approver"] == 1) {
        $vars = array_merge($vars, $this->project_info);
        $this->{$this->_model_name}->notify_approver($vars);
        // $this->die_ajax($this->{$this->_model_name}->get_error_msg());
      }  
      // remove the line above and populate instead variable refresh
      $this->set_var("refresh", "Status updated successfully.");
      $this->set_var("generic_template", "success_ajax.php");
      $this->render();
    } 
  } // end of update()

  function edit_form($args = array()) {
    // check permissions
    $error_msg = $this->edit_validation1($args);
    if ($error_msg != "") {
      echo modal_error($error_msg);
      exit;
    }
    echo modal_update_content(
      $this->project_info, 
      $this->status_info, 
      true // true for $edit
    );
  } // end of edit_form()

  function edit($args = array()) {
    $error_msg = $this->edit_validation1($args);
    if ($error_msg != "") {
      $this->die_ajax($error_msg);
    }  
    // if it worked this far, process the $_POST
    include(CONFIG.$this->_controller_name.".php");
    $req = $update_req_fields[$this->status_info["status_type"]];

    // transform critical_activity here
    if ($this->status_info["status_type"] == "critical_activity" ||
        $this->status_info["status_type"] == "bos_action") {
      $this->checkboxes_to_descr($this->status_info["status_type"]);
    }
    
    $missing = missing_fields($_POST, $req);
    if (sizeof($missing) > 0) {
      $this->set_var("missing", $missing);
      $this->die_ajax("Some required fields are missing.");
    }  

    if ($this->status_info["status_type"] == "bos_action" &&
      is_var_valid($_POST["status_action_date"])) {
      if (!valid_date($_POST["status_action_date"])) {
        $missing[] = "status_action_date"; 
        $this->set_var("missing", $missing);
        $this->die_ajax("Invalid date or format.<br />".
          "Valid date format: YYYY-mm-dd.<br />".
          "Valid year range: from 2000 to current year + 20"
				);
      }
    }  

    $vars = array_merge($this->status_info, $_POST);
    
    if ($this->{$this->_model_name}->edit_status($vars) == 0) {
      $error_msg = $this->{$this->_model_name}->get_error_msg();
      if ($error_msg == "") {
        $error_msg = "Couldn't edit status for some reason.";
      }
      $this->die_ajax($error_msg);
    }
    else {
      $this->set_var("refresh", "Status edited and approved successfully.");
      $this->set_var("generic_template", "success_ajax.php");
      $this->render();
    } 
  } // end of edit()


  private function update_validation1($args) {
    $error_msg = "";
    if (!$this->has_permissions("update")) {
      $error_msg = "You have no permissions to use this functionality.";
    }
    else if (!is_var_valid($args[0])) {
      $error_msg = "Invalid request.";
    }
    else {
      $this->{$this->_model_name}->set_info($args[0]);
      $status_info = $this->{$this->_model_name}->get_info();

      if (empty($status_info)) {
        $error_msg = "Status doesn't exist.";
      }
      else {
        $this->status_info = $status_info;    
        $project_info = $this->{$this->_model_name}->project_info(
          $status_info["project_id"], false
        );

        if (sizeof($project_info) < 1) {
          $error_msg = $this->{$this->_model_name}->get_error_msg();
        }
        else {
          $this->project_info = $project_info;
          // !!!!! make sure to also check if the person has permissions
          // to update status for this particular project
          if ($_SESSION["user_info"]["user_role"] == "M" &&
              $project_info["project_manager"] != $_SESSION["user_id"]
          ) {
            $error_msg = "You have no permissions to update this status.";
          }
        } // project exists
      } // status exists
    } // correct request
    return $error_msg;
  } // update_validation1()

  private function edit_validation1($args) {
    $error_msg = "";
    if (!$this->has_permissions("edit")) {
      $error_msg = "You have no permissions to use this functionality.";
    }
    else if (!is_var_valid($args[0])) {
      $error_msg = "Invalid request.";
    }
    else {
      $this->{$this->_model_name}->set_info($args[0]);
      $status_info = $this->{$this->_model_name}->get_info();

      if (empty($status_info)) {
        $error_msg = "Status doesn't exist.";
      }
      else {
        $this->status_info = $status_info;    
        $project_info = $this->{$this->_model_name}->project_info(
          $status_info["project_id"], false
        );

        if (sizeof($project_info) < 1) {
          $error_msg = $this->{$this->_model_name}->get_error_msg();
        }
        else {
          $this->project_info = $project_info;
          if ($project_info["project_archived"] == "Y") {
            $error_msg = "Project is archived and cannot be modified.";
          }  
          /* S-UPDATE-ALL
          else if ($_SESSION["user_info"]["user_role"] == "S" &&
                   $project_info["project_supervisor"] != $_SESSION["user_id"]
          ) {
            $error_msg = "You have no permissions to edit this status.";
          } */
        } // project exists
      } // status exists
    } // correct request
    return $error_msg;
  } // edit_validation1()

  function approve($args = array()) {
    // check permissions
    $error_msg = "";
    if (!$this->has_permissions("approve")) {
      $this->die_ajax("You have no permissions to use this functionality.");
    }
    if (!is_var_valid($args[0])) {
      $this->die_ajax("Invalid request.");
    }
    
    $this->{$this->_model_name}->set_info($args[0]);
    $status_info = $this->{$this->_model_name}->get_info();

    if (empty($status_info)) {
      $this->die_ajax("Status doesn't exist.");
    }
    $project_info = $this->{$this->_model_name}->project_info(
      $status_info["project_id"], false
    );

    if (sizeof($project_info) < 1) {
      $this->die_ajax($this->{$this->_model_name}->get_error_msg());
    }
    if ($project_info["project_archived"] == "Y") {
      die_ajax("Project is archived and its statuses cannot be modified.");
    } 
    if ($_SESSION["user_info"]["user_role"] == "S" &&
        $project_info["project_supervisor"] != $_SESSION["user_id"]
    ) {
      die_ajax("You have no permissions to approve this status.");
    }

    // update the status and copy to the archives table
    if ($this->{$this->_model_name}->approve_status($args[0]) == 0) {
      $this->die_ajax($this->{$this->_model_name}->get_error_msg());
    }  

    $this->set_var("refresh", "Status approved successfully.");
    $this->set_var("generic_template", "success_ajax.php");
    $this->render();
  } // end of approve()

  function bulk_approve($args = array()) {
    // check permissions
    $error_msg = "";
    if (!$this->has_permissions("approve")) {
      $this->die_ajax("You have no permissions to use bulk approve.");
    }
    if (!is_var_valid($args[0])) {
      $this->die_ajax("Invalid request.");
    }
    
    $project_info = $this->{$this->_model_name}->project_info(
      $args[0], false
    );

    if (sizeof($project_info) < 1) {
      $this->die_ajax($this->{$this->_model_name}->get_error_msg());
    }
    if ($project_info["project_archived"] == "Y") {
      die_ajax("Project is archived and its statuses cannot be modified.");
    }
    if ($_SESSION["user_info"]["user_role"] == "S" &&
        $project_info["project_supervisor"] != $_SESSION["user_id"]
    ) {
      die_ajax("You have no permissions to approve statuses for this project.");
    }

    // get all pending statuses for projects
    $statuses = $this->{$this->_model_name}->pending_statuses_short($args[0]);
    $errors = array();

    // update the status and copy to the archives table
    foreach ($statuses as $status_id) {
      if ($this->{$this->_model_name}->approve_status($status_id) == 0) {
        $errors[] = $this->{$this->_model_name}->get_error_msg();
      }
    }
    if (sizeof($errors) > 0) {
      $this->die_ajax($errors);
    }

    $this->set_var("refresh", "Statuses approved successfully.");
    $this->set_var("generic_template", "success_ajax.php");
    $this->render();
  } // bulk_approve()

	private static function checkboxes_to_descr($status_type) {
  	if (is_arr_valid($_POST[$status_type])) {
    	if (in_array("other", $_POST[$status_type]) &&
      	is_var_valid($_POST[$status_type."_other"])) {
        	$_POST[$status_type][] = 
          	strip_tags($_POST[$status_type."_other"]);
      }
      $_POST[$status_type."_status_descr"] =
      	checkboxes_to_string($_POST[$status_type]);
    }
    else {
      $_POST[$status_type] = "";
    }
	}

} // end of Project_statusController Class
