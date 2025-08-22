<?

class Project_status extends Model {

  // uses generic methods from Model
  function project_info($project_id, $include_statuses = true) {
    $tudo = array();
    $where = array(
      "project_id = '".mysql_escape_string($project_id)."'"
    );
    
    $project_info = DB::get_fields(array(
      "model" => "projects",
      "where" => array(
        $where[0],
        "project_steps_completed = '4'",
      )
    ));
    
    if (sizeof($project_info) < 1) {
      $this->_error_msg = "Project not found.";
    }
    else {
      $tudo = $project_info[0];
      if ($include_statuses) {
        $arr = DB::get_fields(array(
          "model" => "project_statuses",
          "where" => $where,
        ));
        $tudo["statuses"] = array();
        foreach ($arr as $status) {
          $tudo["statuses"][$status["status_type"]] = $status;
        }
      }  
    }
    return $tudo;
  } // project_info

  function update_status($vars) {
    $vars = escape($vars);
    extract($vars);
    
    $fields = array(
      "status_id" => $status_id,
      "project_id" => $project_id,
      "status_type" => $status_type,
      "status_color" => 
        (array_key_exists($status_type."_status", $vars) ? 
          $vars[$status_type."_status"] : ""
        ),
      "status_descr" => 
        (array_key_exists($status_type."_status_descr", $vars) ?
          $vars[$status_type."_status_descr"] : ""
        ),
      "status_approved" => "N",
      "status_needs_update" => "N",
      "status_created_time" => "now()",
      "status_creator_id" => $_SESSION["user_id"],
      "status_approved_time" => "",
      "status_approver_id" => "",
    );
    
    if ($this->update($fields)) {
      $this->set_info($status_id);
      $fields = $this->get_info();
      $fields = escape($fields);

      $fields = enclose_in_quotes($fields,
        array(
          "status_type",
          "status_color", "status_descr", "status_approved",
          "status_needs_update", "status_created_time",
          "status_approved_time", "status_approver_id"
        )
      );
      $insert_id = DB::insert("project_statuses_archive", $fields);
      return 1;
    }
    else {
      return 0;
    }  
  } // update_status

  function edit_status($vars) {
    $vars = escape($vars);
    extract($vars);
    
    $fields = array(
      "status_id" => $status_id,
      "project_id" => $project_id,
      "status_type" => $status_type,
      "status_color" => 
        (array_key_exists($status_type."_status", $vars) ? 
          $vars[$status_type."_status"] : ""
        ),
      "status_descr" => 
        (array_key_exists($status_type."_status_descr", $vars) ?
          $vars[$status_type."_status_descr"] : ""
        ),
      "status_approved" => "Y",
      "status_needs_update" => "N",
      "status_created_time" => "now()",
      "status_creator_id" => $_SESSION["user_id"],
      "status_approved_time" => "now()",
      "status_approver_id" => $_SESSION["user_id"],
    );
    if ($this->update($fields)) {
      $this->set_info($status_id);
      $fields = $this->get_info();
      $fields = escape($fields);

      $fields = enclose_in_quotes($fields,
        array(
          "status_type",
          "status_color", "status_descr", "status_approved",
          "status_needs_update", "status_created_time",
          "status_approved_time", "status_approver_id"
        )
      );
      $insert_id = DB::insert("project_statuses_archive", $fields);
      return 1;
    }
    else {
      return 0;
    }  
  } // end of edit_status()

  function notify_approver($vars) {
    include(CONFIG."project_statuses.php"); 
    // get supervisor info
    $args = array(
      "model" => "users",
      "where" => array("user_id='{$vars["project_supervisor"]}'"),
    );
    $supervisor = DB::get_fields($args, true);

    $vars["link_to_project"] = ADMIN_URL."?q=projects/details/".
      $vars["project_id"];
    $vars["sender_name"] = $_SESSION["user_info"]["user_first_name"].
      " ".$_SESSION["user_info"]["user_last_name"];
    $vars["supervisor_name"] = $supervisor["user_first_name"].
      " ".$supervisor["user_last_name"];
    $vars["status_name"] = $project_status_names[$vars["status_type"]];
    if ($vars["status_color"] != "") {
      $vars["status_name"] .= " Status";
      $vars["status_color"] = "<p>The status has been changed to '".
        ucfirst($vars[$vars["status_type"]."_status"])."'</p>";
    }
    $vars["status_descr"] = 
      (array_key_exists($vars["status_type"]."_status_descr", $vars) ?
        $vars[$vars["status_type"]."_status_descr"] : "");
    $vars["status_time"] = date("m/d/y, h:ia");
    $vars["html"] = 1;

    require_once(CLASSES."mail.php");
    $body = Mail::prepare_and_send($supervisor["user_email"],   
                           "status_updated", $vars);
//    $this->set_error_msg("Sending to ".$supervisor["user_email"]."\n".$body);
  }

  function pending_statuses_short($project_id) {
    $status_ids = array();
    $arr = DB::get_fields(array(
      "model" => "project_statuses",
      "fields" => array($this->_primkey),
      "where" => array(
        "project_id='".(int)$project_id."'",
        "status_approved='N'",
      ))
    );
    if (sizeof($arr) > 0) {
      foreach ($arr as $status) {
        $status_ids[] = $status[$this->_primkey];
      }
    }
    return $status_ids;
  } // end of pending_statuses_short

  function pending_statuses($project_id = 0,
                            $project_manager = 0, 
                            $project_supervisor = 0) {
    $tudo = array();
    $where = array(
      "project_steps_completed = '4'",
      "project_archived != 'Y'"
    );
    if ($project_manager) {
      $where[] = "project_manager='".escape($project_manager)."'";
    }
    else if ($project_supervisor) {
      $where[] = "project_supervisor='".escape($project_supervisor)."'";
    }
    $projects = DB::get_fields(array(
      "model" => "projects",
      "where" => $where,
    ));
    $selected = ($project_id ? 0 : 1);
    foreach($projects as $project_info) {
      $arr = DB::get_fields(array(
        "model" => "project_statuses",
        "where" => array(
          "project_id='".$project_info["project_id"]."'",
          "status_approved='N'",
        ))
      );
      if (sizeof($arr) > 0) {
        $statuses = array();
        foreach ($arr as $status) {
          $statuses[$status["status_type"]] = $status;
        }
        // this would mark the 1st project as selected
        $project_info["selected"] = $selected;
        $selected = 0;
        // this would mark the selected project as selected
        if ($project_id && 
          $project_id == $project_info["project_id"]) {
          $project_info["selected"] = 1;
        }
        $tudo[] = array(
          "project_info" => $project_info,
          "statuses" => $statuses,
        );
      }
    }
    return $tudo; 
  } // pending_statuses ()
  
  function expired_statuses($project_manager = 0) {
    $tudo = array();
    $where = array(
      "project_steps_completed = '4'",
      "project_archived != 'Y'"
    );
    if ($project_manager) {
      $where[] = "project_manager='".escape($project_manager)."'";
    }
    $projects = DB::get_fields(array(
      "model" => "projects",
      "where" => $where,
    ));
    $selected = 1;
    foreach($projects as $project_info) {
      $arr = DB::get_fields(array(
        "model" => "project_statuses",
        "where" => array(
          "project_id='".$project_info["project_id"]."'",
          "status_needs_update='Y'",
        ))
      );
      if (sizeof($arr) > 0) {
        $statuses = array();
        foreach ($arr as $status) {
          $statuses[$status["status_type"]] = $status;
        }
        // this would mark the 1st project as selected
        $project_info["selected"] = $selected;
        $selected = 0;
        $tudo[] = array(
          "project_info" => $project_info,
          "statuses" => $statuses,
        );
      }
    }
    return $tudo; 
  } // expired_statuses ()

  function approve_status($status_id) {
    $query = "UPDATE {$this->_table}
      SET {$this->_var_pfx}approved='Y',
      {$this->_var_pfx}approved_time=now(),
      {$this->_var_pfx}approver_id='".escape($_SESSION["user_id"])."'
      WHERE {$this->_primkey} = '".escape($status_id)."'";

    if (!mysql_query($query)) {
      $this->_error_msg = mysql_error();
      return 0;
    }
    else {
      $this->set_info($status_id); 
      $fields = $this->get_info();
      $fields = escape($fields);
 
      $fields = enclose_in_quotes($fields,
        array(
          "status_type",
          "status_color", "status_descr", "status_approved",
          "status_needs_update", "status_created_time",
          "status_approved_time", "status_approver_id"
        )
      );
      $insert_id = DB::insert("project_statuses_archive", $fields);
      
      return 1;
    }

  } // approve_status
} // Project_status model
