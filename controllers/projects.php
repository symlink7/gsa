<?

class ProjectsController extends Controller {

  function add_form($args = array()) {
    if (!$this->has_permissions("add")) {
      $this->set_var("error_msg", 
        "You have no permissions to add projects.");
      $this->set_var("generic_template", "error.php");
    }
    else {
      $step = 1; // default step is 1
      if (is_var_valid($_POST["vars"])) {
        $arr = explode("/", $_POST["vars"]);
        $step = (is_var_valid($arr[0]) ? $arr[0] : 1);
        $project_id = (is_var_valid($arr[1]) ? $arr[1] : 0);
        $this->set_var("project_id", $project_id);
      }
      if ($step == 1) {
        $this->get_step1_data("add");
      } 
      if ($step < 5) {
        $this->set_var("step", $step);
        $this->set_var("meta_title", "Admin - Add a Project");
      }  
      else {
        $this->set_var("success_msg", "Project successfully added.");
        $this->set_var("generic_template", "success.php");
      }  
    }
    $this->render();
  }

  function add($args = array()) {
    $_POST = (is_arr_valid($_POST) ? strip($_POST) : array());
    extract($_POST);

    // global validation for all 4 steps
    if (!$this->has_permissions("add")) {
      $this->die_ajax("You have no permissions to add $this->_controller_name.");
    }
    
    if (!is_var_valid($step)) {
      $this->die_ajax("Direct access to this script is denied!");
    }
    
    if ($step == 1) {
      // prepare the special variables
      $vars = $this->{$this->_model_name}->before_validation($_POST);      
      
      // validate the standard way
      if ($this->validate_add_form($vars) == 0) {
        $this->die_ajax();
      }
      
      // insert the record
      if ($this->{$this->_model_name}->add($vars) == 0) {
        $error_msg = $this->{$this->_model_name}->get_error_msg();
        if ($error_msg == "") {
          $error_msg = "Couldn't add {$this->_model_name}.";
        }
        $this->die_ajax($error_msg);
      }
      $project_id = $this->{$this->_model_name}->get_id();
    } 
    else {
      // steps 2-4, need project_id, validate project_author
      if (!is_var_valid($project_id)) {
        $this->die_ajax("Unknown Project ID!");
      }
      
      $this->{$this->_model_name}->set_info($project_id);
      $project_info = $this->{$this->_model_name}->get_info();
      
      if (empty($project_info)) {
        $this->die_ajax("Project doesn't exist.");
      }  
      
      if ($project_info["project_author"] != $_SESSION["user_id"]) {
        $this->die_ajax("You have no permissions to publish this project.");
      }  
      
      if ($step != ($project_info["project_steps_completed"] + 1)) {
        $this->die_ajax("You came to a wrong step of the form.");
      }

      include(CONFIG.$this->_controller_name.".php");
      $model = new Model($step_controllers[$step]);

      // project statuses
      if ($step == 4) {
        // transform critical_activity here
        if (is_arr_valid($critical_activity)) {
          if (in_array("other", $critical_activity) &&
            is_var_valid($critical_activity_other)) {
              $critical_activity[] = strip_tags($critical_activity_other);
          }  
          $_POST["critical_activity_status_descr"] =
            $critical_activity_status_descr = 
              checkboxes_to_string($critical_activity);
        }
        else {
          $_POST["critical_activity_status_descr"] =
            $critical_activity_status_descr = "";
        }

        if (is_arr_valid($bos_action)) {
          if (in_array("other", $bos_action) &&
            is_var_valid($bos_action_other)) {
              $bos_action[] = strip_tags($bos_action_other);
          }  
          $_POST["bos_action_status_descr"] =
            $bos_action_status_descr = 
              checkboxes_to_string($bos_action);
        }
        else {
          $_POST["bos_action_status_descr"] =
            $bos_action_status_descr = "";
        }    
      }  
      if ($this->validate_steps_add_form(
        $_POST, $step_controllers[$step]) == 0
      ) {
        $this->die_ajax();
      }

      // insert the record
      if ($model->add($_POST) == 0) {
        $error_msg = $model->get_error_msg();
        if ($error_msg == "") {
          $error_msg = "Couldn't add {$step_titles[$step]}.";
        }
        $this->die_ajax($error_msg);
      }

      // insert eventual statuses

      if ($this->{$this->_model_name}->add_statuses(
        $step_controllers[$step], $_POST) == 0
      ) {
        $error_msg = $this->{$this->_model_name}->get_error_msg();
        if ($error_msg == "") {
          $error_msg = "Couldn't add status.";
        }
        $this->die_ajax($error_msg);
      }
    }

    // update project_steps_completed
    if ($this->{$this->_model_name}->update_steps_completed(
      $project_id, $step) == 0
    ) {
      $error_msg = $this->{$this->_model_name}->get_error_msg();
      if ($error_msg == "") {
        $error_msg = "Couldn't update steps completed.";
      }
      $this->die_ajax($error_msg);
    }

    // all cool
    $step++;

    // if all is good, set step and project id
    $this->set_var("redirect_vars", "$step/$project_id");
    $this->set_var("generic_template", "success_ajax.php");
    $this->render();
  } // add()

  private function get_step1_data($ref) {
    // grab departments, clients, managers, supervisors
    $this->set_var("departments", DB::get_id_name(
      array("model" => "departments", "order" => "dep_name")
    )); 
    $this->set_var("buildings", DB::get_id_name(
      array(
        "model" => "buildings", 
        "order" => "build_number",
        "where" => ($ref == "add" ? array("build_old != 1") : array()),
      )
    ));  
    $this->set_var("clients", DB::get_id_name(
      array("model" => "clients")
    ));
    $this->set_var("managers", DB::get_id_name(
      array("model" => "users",
        "where" => array("user_role = 'M'"),
      )
    ));
    $this->set_var("supervisors", DB::get_id_name(
      array("model" => "users",
        "where" => array("user_role = 'S'"),
      )
    ));  
  } // get_step1_data

  function view_mine() {
    if (!$this->has_permissions("view_mine")) {
      $this->set_var("error_msg",
        "You have no permissions to this functionality.");
      $this->set_var("generic_template", "error.php");
      $this->render();
      return;
    }

    $this->set_var("published", 
      $this->{$this->_model_name}->view_mine_published()
    );
    
    // drafts
    $this->set_var("drafts", DB::get_fields(
      array(
        "model" => "projects", 
        "fields" => array(
          "project_id", "project_name", "project_building",
          "project_steps_completed"
        ),
        "where" => array(
          "project_author = '{$_SESSION["user_id"]}'",
          "project_steps_completed < 4"
        )
      )
    ));
    $this->set_var("meta_title", "My Projects");
    $this->render();
  } // view_mine()

  function view_all($args = array()) {
    if (!$this->has_permissions("view_all")) {
      $this->set_var("error_msg",
        "You have no permissions to this functionality.");
      $this->set_var("generic_template", "error.php");
    }
    else {
      if (is_var_valid($args[0]) && $args[0]=="archived") {
        $this->set_var("tudo", $this->{$this->_model_name}->view_all(1));
        $this->set_var("meta_title", "Archived Projects");
        $this->set_var("title", "ARCHIVED PROJECTS");
      }  
      else {
        $this->set_var("tudo", $this->{$this->_model_name}->view_all());
        $this->set_var("meta_title", "All Projects");
        $this->set_var("title", "ALL PROJECTS");
      }  
    }
    $this->render();
  } // view_all (for all users except director)

  function api_list() {
    // project_id, project_name, building, department, budget, stakeholder
    // sort by project_name
    $this->_template = new Template($this->_controller_name, "list_json");

    $where = array();
    $filters = array("dep_name", "dep_id", "build_num");
    if (is_var_valid($_POST["filter"])) {
      $filter = escape($_POST["filter"]);
      if (!in_array($filter, $filters)) {
        $this->set_var("error_msg", 
          "Invalid filter '{$_POST["filter"]}'.");
        $this->render();
        return;
      }
      else if (!is_var_valid($_POST[$filter])) {
        $this->set_var("error_msg", 
          "Invalid value '{$_POST[$filter]}' for filter '{$_POST["filter"]}'.");
        $this->render();
        return;
      }
      else {
        $where[] = "$filter = '".escape($_POST[$filter])."'";
      }  
    }  
    
    $extra_fields = array();
    $allowed_extra_fields = array("project_manager");
    if (is_var_valid($_POST["extra_fields"])) {
      $more_fields = explode(",", $_POST["extra_fields"]);
      foreach ($more_fields as $f) {
        if (in_array($f, $allowed_extra_fields)) {
          $extra_fields[] = $f;
        }
      }
    }  
    $args = compact("where", "extra_fields"); 
    $this->set_var("tudo", $this->{$this->_model_name}->api_list($args, $_POST["version"]));
    $this->render();
  } // api_list()

  function api_details($args = array()) {
    $this->_template = new Template($this->_controller_name, "details_json");
    if (!is_var_valid($_POST["project_id"])) {
      $this->set_var("error_msg", 
          "Invalid Project ID.");
      $this->render();
      return;
    }
    $extra_fields = array();
    $allowed_extra_fields = array("project_manager");
    if (is_var_valid($_POST["extra_fields"])) {
      $more_fields = explode(",", $_POST["extra_fields"]);
      foreach ($more_fields as $f) {
        if (in_array($f, $allowed_extra_fields)) {
          $extra_fields[] = $f;
        }
      }
    }  
    $project_id = strip($_POST["project_id"]);
    $args = compact("project_id", "extra_fields"); 
    $this->set_var("tudo", $this->{$this->_model_name}->api_details($args, $_POST["version"]));
    $this->render();
  } // api_details()

  function api_tudo($args = array()) {
    $this->_template = new Template($this->_controller_name, "tudo_json");
    $extra_fields = array("project_manager");
    $pull_all = 1;
    $args = compact("extra_fields", "pull_all");
    $this->set_var("projects", $this->{$this->_model_name}->api_details($args, $_POST["version"]));
    $this->set_var("departments", DB::get_id_name(
      array("model" => "departments", "order" => "dep_name")
    ));
    $this->render();
  } // api_tudo()

  function view_approved($args = array()) {
    if (!$this->has_permissions("view_approved")) {
      $this->set_var("error_msg",
        "You have no permissions to this functionality.");
      $this->set_var("generic_template", "error.php");
    }
    else {
      $color = "";
      $archived = 0;
      if ($args[0]) {
        // it can be red/green/yellow/archived
        if (in_array($args[0], array("red", "green", "yellow"))) {
          $color = $args[0];
          $this->set_var("color", $args[0]);
          // if the first arg is the color, archived could be 2nd
          if ($args[1] && $args[1] == "archived") {
            $archived = 1;
          }
        }
        else if ($args[0] == "archived") {
          $archived = 1;
        }  
      }  
      $info = $this->{$this->_model_name}->view_approved($color, $archived);
      $this->set_var("archived", $archived);
      $this->set_var("tudo", $info["tudo"]);
      $this->set_var("colors", $info["colors"]);
      $this->set_statistics_vars($info["tudo"]);
      // if (is_arr_valid($info["project_ids"])) {
      //  $lead_client = $this->{$this->_model_name}->lead_client($info["project_ids"]);
      //}
      $this->set_var("meta_title", "GSA Project Status Dashboard");
    }
    $this->render();
  } // view_approved (for director)

  function view_approved_by_building($args = array()) {
    if (!$this->has_permissions("view_approved")) {
      $this->set_var("error_msg",
        "You have no permissions to this functionality.");
      $this->set_var("generic_template", "error.php");
    }
    else if (!$args[0]) {
      $this->set_var("error_msg", "Missing building #.");
      $this->set_var("generic_template", "error.php");
    }
    else {
      $info = $this->{$this->_model_name}->view_approved("", 0, 
        array("p.project_building='".strip($args[0])."'"));
      $this->set_var("tudo", $info["tudo"]);
      $this->set_var("building_num", $args[0]);
      $this->set_var("meta_title", "GSA Project Status Dashboard");
    }
    $this->render();
  } // view_approved_by_building (for director)
  
  function details($args = array()) {
    $error_msg = "";
    if (!$this->has_permissions("details")) {
      $error_msg = "You have no permissions to this functionality.";
    }
    else if (!is_var_valid($args[0])) {
      $error_msg = "Invalid request.";
    }
    else {
      $tudo = $this->{$this->_model_name}->details($args[0]);
      if (sizeof($tudo) < 1) {
        $error_msg = $this->{$this->_model_name}->get_error_msg();
      }
      else {
        $this->set_var("tudo", $tudo);
        $related_projects = array();
        if ($tudo["project_building"]) {
          $related_projects = $this->{$this->_model_name}->projects_by_building($tudo["project_building"], $args[0]);
        }
        $this->set_var("related_projects", $related_projects);
        // figure out who can see edit buttons and set_var's 
        $this->edit_update_button_perm($tudo);
      }  
    }
    if ($error_msg != "") {  
      $this->set_var("error_msg", $error_msg);
      $this->set_var("generic_template", "error.php");
    }
    if (isset($args[1]) && $args[1] == "printer") {
      $this->set_var("printer_version", 1);
    }  
    $this->render();
  } // details()

  function edit_form($args = array()) {
    $error_msg = "";
    if (!$this->has_permissions("edit")) {
      $error_msg = "You have no permissions to edit projects.";
    }
    else {
      $step = 1; // default step is 1
      if (!is_var_valid($args)) {
        $error_msg = "Invalid request.";
      }
      else {
        $project_id = (is_var_valid($args[0]) ? $args[0] : 0);
        // check project_id and permissions here
        if (!$project_id) {
          $error_msg = "Invalid request.";
        }  
        else {
          $tudo = $this->{$this->_model_name}->project_info(
            $project_id, array("projects", "details"));
          if (sizeof($tudo) < 1) {
            $error_msg = $this->{$this->_model_name}->get_error_msg();
          }  
          else if ($tudo["project_archived"] == "Y") {
            $error_msg = "Project is archived and cannot be edited.";
          }  
          /* S-UPDATE-ALL
          else if ($_SESSION["user_info"]["user_role"] == "S" &&
            !$this->may_supervisor_edit($tudo)) { 
              $error_msg = "You have no permissions to edit this project.";
          } */
          else if ($_SESSION["user_info"]["user_role"] == "M" &&
            $_SESSION["user_id"] != $tudo["project_manager"]) {
            $error_msg = "You have no permissions to edit this project.";
          }  
          else {
            $step = (is_var_valid($args[1]) ? $args[1] : 1);
            $step = ((int)$step > 3 || (int)$step < 1 ? 1 : $step);
          
            if ($step == 1) {
              $this->get_step1_data("edit");
              /*
              $tudo = array_merge($tudo, 
                $this->{$this->_model_name}->project_info(
                  $project_id, array("details")));
              */
              $cl = $this->{$this->_model_name}->project_info(
                $project_id, array("clients"));
              $clients = array();
              foreach ($cl["clients"] as $client) {
                $clients[] = $client["client_id"];
              }  
              $this->set_var("clients_for_project", $clients); 
            }
            else if ($step == 2) {
              $tudo = array_merge($tudo, 
                $this->{$this->_model_name}->project_info(
                  $project_id, array("budgets")));
            }
            else if ($step == 3) {
              $tudo = array_merge($tudo, 
                $this->{$this->_model_name}->project_info(
                  $project_id, array("dates")));
            }      
            $this->set_var("step", $step);
            $this->set_var("project_id", $project_id);
            $this->set_var("tudo", $tudo);
          } // else if it's admin or the project's supervisor  
        } // else if project_id is set
      } // else if $args is valid
    } // else if has permissions
    if ($error_msg != "") {
      $this->set_var("error_msg", $error_msg);
      $this->set_var("generic_template", "error.php");
    }
    else {
      $this->set_var("meta_title", "Admin - Edit Project");
    }
    $this->render();
  } // edit_form()
  
  function edit($args = array()) {
    $_POST = (is_arr_valid($_POST) ? strip($_POST) : array());
    extract($_POST);

    // global validation for all 4 steps
    if (!$this->has_permissions("edit")) {
      $this->die_ajax("You have no permissions to edit $this->_controller_name.");
    }
    
    if (!is_var_valid($step) || !is_var_valid($project_id)) {
      $this->die_ajax("Invalid request!");
    }
    
    // $this->die_ajax(serialize_array($_POST));
    // get project info from database to check permissions
    $tudo = $this->{$this->_model_name}->project_info(
      $project_id, array("projects"));
    if (sizeof($tudo) < 1) {
      $error_msg = $this->{$this->_model_name}->get_error_msg();
      if ($error_msg == "") {
        $error_msg = "Project was not found.";
      }  
      $this->die_ajax($error_msg);
    }  
    else if ($tudo["project_archived"] == "Y") {
      $this->die_ajax("Project is archived and cannot be edited.");
    }
    /* S-UPDATE-ALL
    else if ($_SESSION["user_info"]["user_role"] == "S" && 
      !$this->may_supervisor_edit($tudo)) {
        $this->die_ajax("You have no permissions to edit this project.");
    } */
    else if ($_SESSION["user_info"]["user_role"] == "M" &&
      $_SESSION["user_id"] != $tudo["project_manager"]) {
      $this->die_ajax("You have no permissions to edit this project.");
    }

    $vars = ($step == 1 ? 
      $this->{$this->_model_name}->before_validation($_POST) : $_POST);
    
    include(CONFIG.$this->_controller_name.".php");
    
    $model = ($step == 1 ? 
                $this->{$this->_model_name} : 
                new Model($step_controllers[$step])
              );

    if ($this->validate_project_edit_form(
        $vars, $step_controllers[$step]) == 0
      ) {
      $this->die_ajax();
    }
    
    if ($model->edit($_POST) == 0) {
      $error_msg = $model->get_error_msg();
      if ($error_msg == "") {
        $error_msg = "Couldn't edit {$step_titles[$step]}.";
      }
      $this->die_ajax($error_msg);
    }
    
    // step 1 has some fields from project_details
    if ($step == 1) {
      $model = new Model("project_details");
      if ($model->edit($_POST) == 0) {
        $error_msg = $model->get_error_msg();
        if ($error_msg == "") {
          $error_msg = "Couldn't edit {$step_titles[4]}.";
        }
        $this->die_ajax($error_msg);
      }
    }  
    $this->set_var("generic_template", "success_ajax.php");
    $this->render();
  } // edit()

  private function may_supervisor_edit($tudo) {
    if ($_SESSION["user_id"] == $tudo["project_supervisor"]) {
      return true;
    }
    else if (is_var_valid($tudo["project_type"]) &&
      $tudo["project_type"] == $_SESSION["user_info"]["user_project_type"]) {
      return true;
    }
    else {
      return false;
    }
  }

  function validate_project_edit_form($vars, $section) {
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

    if (is_arr_valid($edit_form_req_fields)) {
      foreach ($edit_form_req_fields as $varname) {
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
    return 1;
  } // validate_project_edit_form()

  private function edit_update_button_perm($tudo) {
    $can_edit = 0;
    $can_update = 0;
    $can_approve = 0;
    $can_delete = 0;
    $can_archive = 0;
    $approval_needed = 0;
   
    // if there's any one pending status, show the approve link
    foreach($tudo["statuses"] as $status) {
      if ($status["status_approved"] == "N") {
        $approval_needed = 1;
        break;
      }
    }
    if (in_array($_SESSION["user_info"]["user_role"], array("A", "PA"))) {
      $can_edit = 1;
      $can_update = 1;
      $can_approve = ($approval_needed ? 1 : 0);
      $can_delete = 1;
      $can_archive = 1;
    }
    else if ($_SESSION["user_info"]["user_role"] == "S") {
      $can_update = 1; // S-UPDATE-ALL
      $can_archive = 1;
      if ($_SESSION["user_id"] == $tudo["project_supervisor"]) {
        $can_edit = 1;
        $can_approve = ($approval_needed ? 1 : 0);
      }
      if (is_var_valid($tudo["project_type"]) && 
        $tudo["project_type"] == $_SESSION["user_info"]["user_project_type"]
      ) { 
        $can_edit = 1;
      }
    }
    else if ($_SESSION["user_info"]["user_role"] == "M" &&
             $tudo["project_manager"] == $_SESSION["user_id"]) {
      $can_edit = 1;
      $can_update = 1;
    }

    if ($tudo["project_archived"] == "Y") {
      $can_edit = 0;
      $can_update = 0;
      $can_approve = 0;
      // keep delete link maybe
    }  

    $this->set_var("can_edit", $can_edit);
    $this->set_var("can_update", $can_update);
    $this->set_var("can_approve", $can_approve);
    $this->set_var("can_delete", $can_delete);
    $this->set_var("can_archive", $can_archive);
  }

  function export_form($args = array()) {
    if (!$this->has_permissions("export_all")) {
      $this->set_var("error_msg",
        "You have no permissions to this functionality.");
      $this->set_var("generic_template", "error.php");
    }
    $this->set_var("departments", DB::get_id_name(
      array("model" => "departments", "order" => "dep_name")
    ));
    $this->render();
  } // export_form (for all users except director)

  function export_all($args = array()) {
    if (!$this->has_permissions("export_all")) {
      $this->set_var("error_msg",
        "You have no permissions to this functionality.");
      $this->set_var("generic_template", "error.php");
    }
    else {
      $params = array("where" => array(), "order" => "");
      if (is_arr_valid($_POST) && is_arr_valid($_POST["project_department_arr"])
        && !in_array("all", $_POST["project_department_arr"])) {
        $arr = neutralize($_POST["project_department_arr"]);
        $params["where"] = array("p.project_department in (".implode(", ", $arr).")");
        $params["order"] = "d.dep_name";
      }  
      if (!is_var_valid($_POST["include_archived"])) {
        $params["where"][] = "p.project_archived != 'Y'";
      }
      // change the action name to something 
      // that is not a method of ProjectsController
      // to avoid getting the header and footer
      $tudo = $this->{$this->_model_name}->export_all($params);
      if (sizeof($tudo) < 1) {
        $this->set_var("error_msg", "No matching projects were found.");
        $this->set_var("generic_template", "error.php");
      }  
      else {
        if (is_var_valid($_POST["include_archived"])) {
          $_POST["include_fields"][] = "project_archived";
        }  
        $this->_template = new Template($this->_controller_name, "export_all_csv");
        $this->set_var("tudo", $this->{$this->_model_name}->export_all($params));
        $this->set_var("include_fields", $_POST["include_fields"]);
        $this->set_var("meta_title", "Export Projects");
      }
    }
    $this->render();
  } // export_all (for all users except director)

  function export($args = array()) {
    $id = (is_var_valid($args[0]) ? $args[0] : "");
    if ($id == "") {
      $this->set_var("error_msg", "Invalid Project ID.");
      $this->set_var("generic_template", "error.php");
    }
    else if (!$this->has_permissions("export_all")) {
      $this->set_var("error_msg",
        "You have no permissions to this functionality.");
      $this->set_var("generic_template", "error.php");
    }
    else {
      $this->_template = new Template($this->_controller_name, "export_all_csv");
      $this->set_var("tudo", $this->{$this->_model_name}->export_all(
        array("project_id"=>$id)
      ));
      $this->set_var("filename", "project-$id");
      $this->set_var("meta_title", "Export Project");
    }
    $this->render();
  } // export_all (for all users except director)

  function archive($args = array()) {
    $id = (is_var_valid($args[0]) ? $args[0] : "");
    $flag = (is_var_valid($args[1]) ? $args[1] : "Y");
    $tag = ($flag == "Y" ? "archive" : "un-archive");

    $error_msg = "";

    if ($id == "") {
      $error_msg = "Invalid Project ID.";
    }          
    else if (!$this->has_permissions("archive")) {
      $error_msg = "You have no permissions to $tag ".
                   $this->_controller_name . ".";
    }
    else if ($this->{$this->_model_name}->archive($id, $flag) == 0) {
      $error_msg = $this->{$this->_model_name}->get_error_msg();
    }
    else { 
      $this->set_var("success_msg", $this->_model_name.
        " successfully {$tag}d.");
    }
 
    $template = ($error_msg != "" ? "error.php" : "success.php");    
    $this->set_var("error_msg", $error_msg);
    $this->set_var("generic_template", $template);

    $this->render();
  } // end of archive()

  function set_statistics_vars($tudo) {
    $budgets_by_district = array();
    $budgets_by_client = array();
    $budgets_by_building = array();
    $client_names = array();

    foreach ($tudo as $project) {
      extract($project);
      
      $budget = ($budget_approved_revised ?
        $budget_approved_revised : $budget_approved_orig);
 
      if ($project_district) {
        if (!array_key_exists($project_district, $budgets_by_district)) {
          $budgets_by_district[$project_district] = 0;
        }
        if ($budget > 0) {
          $budgets_by_district[$project_district] += $budget;
        }
      } 

      if ($project_client_id) {
        if (!array_key_exists($project_client_id, $budgets_by_client)) {
          $budgets_by_client[$project_client_id] = 0;
          $client_names[$project_client_id] = $client_name;
        }
        if ($budget > 0) {
          $budgets_by_client[$project_client_id] += $budget;
        }
      }

      if ($project_building) {
        if (!array_key_exists($project_building, $budgets_by_building)) {
          $budgets_by_building[$project_building] = 0;
        }
        if ($budget > 0) {
          $budgets_by_building[$project_building] += $budget;
        }
      }
    } // end of looping through projects
    arsort($budgets_by_district);
    arsort($budgets_by_client);
    arsort($budgets_by_building);
    $this->set_var("budgets_by_district", $budgets_by_district);
    $this->set_var("budgets_by_client", $budgets_by_client);
    $this->set_var("client_names", $client_names);
    $this->set_var("budgets_by_building", 
      array_slice($budgets_by_building, 0, 20, TRUE)
    );
  } // set_statistics_vars

  function bulk_api_status($args = array()) {
    $version = (sizeof($args) < 1 || 
      ($args[0] != "public" && $args[0] != "intranet")
      ? "public" : $args[0]);
    if (!$this->has_permissions("api_status")) {
      $this->set_var("error_msg",
        "You have no permissions to this functionality.");
      $this->set_var("generic_template", "error.php");
    }
    if (is_arr_valid($_POST)) {
      $public_projects = (is_arr_valid($_POST["public_projects"]) ?
          $_POST["public_projects"] : array()
      );
      $this->{$this->_model_name}->set_api_status($public_projects, $version);
    }  
    $this->_template = new Template($this->_controller_name, "bulk_api_status");
    $this->set_var("version", $version);
    $this->set_var("all_projects", $this->{$this->_model_name}->simple_list());
    $this->set_var("public_projects", $this->{$this->_model_name}->get_api_projects($version));
    $this->render();
  } // bulk_api_status()

} // class Projects  
