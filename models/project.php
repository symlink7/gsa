<?

class Project extends Model {

  public function before_validation($vars) {
    if (is_arr_valid($vars["project_clients_arr"]) &&
        sizeof($vars["project_clients_arr"]) > 0) {
      $vars["project_clients"] = implode(",", $vars["project_clients_arr"]);
    }
    else {
      $vars["project_clients"] = "";
    }  
    return $vars;
  }

  public function add($vars) {
    $vars["project_author"] = $_SESSION["user_id"];
    $vars["project_steps_completed"] = 1;
    $vars["project_deleted"] = "N";
    $vars["project_archived"] = "N";

    if ($this->insert(escape($vars)) == 0) {
      return 0;
    }
    // insert successful
    if ($this->_id == 0) {
      $this->set_error_msg("Couldn't fetch project ID");
      return 0;
    }

    if (is_arr_valid($vars["project_clients_arr"])) {
      foreach ($vars["project_clients_arr"] as $client_id) {
        $resp = DB::insert("clients_for_projects", array(
          "project_id" => $this->_id,
          "client_id" => escape($client_id)
        ));
        if ($resp != "") {
          $this->set_error_msg($resp);
          //$this->set_error_msg("Couldn't save client $client_id.");
          return 0;
        }
      } 
    }  
    // if we got this far without returning, we're good      
    return 1;
  } // end of add()

  function add_statuses($model, $vars) {
    $vars = escape($vars);
    extract($vars);

    include(CONFIG.$model.".php");
    
    if (is_arr_valid($status_fields)) {
      foreach ($status_fields as $status_type) {
        $fields = array(
          "project_id" => $project_id,
          "status_type" => "'$status_type'",
          "status_color" => "'".
            (array_key_exists($status_type."_status", $vars) ? 
              $vars[$status_type."_status"] : ""
            )."'",
          "status_descr" => "'".
            (array_key_exists($status_type."_status_descr", $vars) ?
              $vars[$status_type."_status_descr"] : ""
            )."'",
          "status_approved" => "'Y'",
          "status_needs_update" => "'N'",
          "status_created_time" => "now()",
          "status_creator_id" => $_SESSION["user_id"],
          "status_approved_time" => "now()",
          "status_approver_id" => $_SESSION["user_id"],
        );
        $resp = DB::insert("project_statuses", $fields, true);
        if (!preg_match("/^[0-9]{5,12}$/", $resp)) {
          $this->set_error_msg($resp);
          //$this->set_error_msg("Couldn't save status.");
          return 0;
        }
        else { // duplicate to archives table
          $fields["status_id"] = $resp;
          DB::insert("project_statuses_archive", $fields);
        }  
      } // end of looping through status_fields
    } // and of if we have status_fields
    return 1;
  } // add_statuses

  function update_steps_completed($project_id, $step) {
    $query = "UPDATE {$this->_table}
      SET {$this->_var_pfx}steps_completed='".escape($step)."'
      WHERE {$this->_primkey} = '".escape($project_id)."'";

    if (!mysql_query($query)) {
      $this->_error_msg = mysql_error();
      return 0;
    }
    else {
      return 1;
    }
  } // update steps_completed  

  function view_mine_published() {
    $tudo = array();

    if ($_SESSION["user_info"]["user_role"] == "M") {
      $author_rule = "p.project_manager = '{$_SESSION["user_id"]}'";
    }
    else if ($_SESSION["user_info"]["user_role"] == "S") {
      $author_rule =
        "(p.project_supervisor = '{$_SESSION["user_id"]}' 
          OR
          p.project_author = '{$_SESSION["user_id"]}')";
    }
    else {
      $author_rule = "p.project_author = '{$_SESSION["user_id"]}'";
    }

    $query = "
      SELECT 
        p.project_id,
        p.project_name,
        p.project_building,
        p.project_number,
        ps.status_color,
        ps.status_approved,
        ps.status_needs_update
      FROM projects AS p
      LEFT JOIN project_statuses AS ps
      ON p.project_id=ps.project_id
      WHERE 
        $author_rule
      AND
        p.project_steps_completed = 4
      AND
        p.project_deleted != 'Y'
      AND
        p.project_archived != 'Y'
      AND
        ps.status_type='overall'
      GROUP BY p.project_id
      ORDER BY p.project_building";

    if (!($res = mysql_query($query))) {
      $this->_error_msg = mysql_error();
      return $tudo;
    }
    
    while ($row = mysql_fetch_assoc($res)) {
      $tudo[$row["project_id"]] = strip($row);
    }
    return $tudo;
  } // end of view_mine_published()

  function view_all($archived = 0) {
    $tudo = array();
    $query = "
      SELECT 
        p.project_id,
        p.project_name,
        p.project_building,
        p.project_number,
        p.project_type,
        p.project_department,
        d.dep_name,
        CONCAT(u.user_first_name, ' ', u.user_last_name) 
        AS project_manager,
        GROUP_CONCAT(DISTINCT c.client_name 
                     ORDER BY c.client_name 
                     SEPARATOR '; '
        ) as client_list,
        ps.status_color,
        ps.status_approved, 
        ps.status_needs_update
      FROM projects AS p
      LEFT JOIN clients_for_projects AS cp
      ON p.project_id=cp.project_id
      LEFT JOIN clients AS c
      ON cp.client_id=c.client_id
      LEFT JOIN project_statuses AS ps
      ON p.project_id=ps.project_id
      LEFT JOIN departments as d
      ON p.project_department=d.dep_id
      LEFT JOIN users as u
      ON p.project_manager=u.user_id
      WHERE
        p.project_deleted != 'Y'
      AND
        p.project_steps_completed = 4
      AND 
        p.project_archived ".
        (!$archived ? "!=" : "=")." 'Y'
      AND 
        ps.status_type='overall'
      GROUP BY p.project_id
      ORDER BY p.project_building, client_list";
    
    if (!($res = mysql_query($query))) {
      echo $this->_error_msg = mysql_error();
      return $tudo;
    }

    while ($row = mysql_fetch_assoc($res)) {
      $tudo[$row["project_id"]] = strip($row);
    }
    return $tudo;
     
  } // view_all

  function view_approved($color = "", $archived = 0, 
                         $more_filters = array()) {
    $tudo = array();
    // calculate how man projects per color
    $colors = array(
      "green" => 0,
      "red" => 0,
      "yellow" => 0
    );

    $query = "
      SELECT p.project_id, 
        approved.status_color
      FROM (
        SELECT * 
        FROM project_statuses_archive
        WHERE status_approved = 'Y'
        ORDER BY project_id, archive_status_id DESC
      ) AS approved
      LEFT JOIN projects as p
      ON approved.project_id = p.project_id
      WHERE 
        approved.status_type = 'overall'
      AND
        p.project_deleted != 'Y'
      AND 
        p.project_steps_completed = 4
      AND
        p.project_archived".
          ($archived ? ' = ' : " != ")."'Y'".
      (is_arr_valid($more_filters) ? "
      AND ".implode(" AND ", $more_filters) : "")."
      GROUP BY approved.status_id
      ORDER BY p.project_id";

    if (!($res = mysql_query($query))) {
      $this->_error_msg = mysql_error();
      return compact("tudo", "$colors");
    }

    $project_ids = array();
    while ($row = mysql_fetch_assoc($res)) {
      if (array_key_exists($row["status_color"], $colors)) {
        $colors[$row["status_color"]] += 1;
      } 
      // color == "" for main window with all colors
      // otherwise for views by color
      if ($color == "" || $color == $row["status_color"]) {
        $project_ids[] = $row["project_id"];
      }
    }

    if (sizeof($project_ids) > 0) {
      $query = "
      SELECT 
        p.project_id,
        p.project_number,
        p.project_name,
        p.project_building,
        p.project_number,
        p.project_district,
        p.project_type,
        p.project_department,
        d.dep_name,
        b.budget_approved_orig,
        b.budget_approved_revised,
        pd.project_phase,
        pd.project_category,
        cl.client_name,
        p.project_client_id,
        GROUP_CONCAT(DISTINCT c.client_name 
                     ORDER BY c.client_name 
                     SEPARATOR '; '
        ) as client_list
      FROM projects AS p
      LEFT JOIN project_budgets as b
      ON p.project_id=b.project_id
      LEFT JOIN project_details as pd
      on pd.project_id=pd.project_id
      LEFT JOIN clients_for_projects AS cp
      ON p.project_id=cp.project_id
      LEFT JOIN clients AS c
      ON cp.client_id=c.client_id
      LEFT JOIN clients AS cl
      ON p.project_client_id=cl.client_id
      LEFT JOIN departments as d
      ON p.project_department=d.dep_id
      WHERE
        p.project_deleted != 'Y'
      AND
        p.project_steps_completed = 4 
      AND 
        p.project_id in ('".implode("', '", $project_ids)."')
      GROUP BY p.project_id
      ORDER BY p.project_building, client_list";

      if (!($res = mysql_query($query))) {
        $this->_error_msg = mysql_error();
        return compact("tudo", "colors");
      }

      while ($row = mysql_fetch_assoc($res)) {
        $tudo[$row["project_id"]] = strip($row);
      }
    }
    return compact("tudo", "colors"/*, "project_ids"*/);
  } // view_approved()

  function details($project_id) {
    // coded in this stupid long way with many calls to DB::get_fields
    // and DB::get_id_name instead of one good query with many tables
    // joined, and the reason is to avoid searchig the db if the 
    // project is not valid
    // and also so that each part can be modified without affecting
    // the other parts, for example, getting older statuses if it's
    // a director view

    $tudo = array();
    $where = array(
      "project_id = '".escape($project_id)."'"
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
      // get the other info
      $tudo = array_merge($tudo, DB::get_fields(array(
        "model" => "project_budgets",
        "where" => $where,
      ), true));
      $tudo = array_merge($tudo, DB::get_fields(array(
        "model" => "project_dates",
        "where" => $where,
      ), true));
      $tudo = array_merge($tudo, DB::get_fields(array(
        "model" => "project_details",
        "where" => $where,
      ), true));  
      $tudo["images"] = DB::get_fields(array(
        "model" => "project_images",
        "where" => $where,
      ));  
      $tudo["manager"] = DB::get_fields(array(
        "model" => "users",
        "where" => array("user_id = '".$project_info[0]["project_manager"]."'"),
      ));  

      if ($_SESSION["user_info"]["user_role"] == "D") {
        $query = "
        SELECT approved.* FROM (
          SELECT *
          FROM project_statuses_archive
          WHERE status_approved = 'Y'
          ORDER BY project_id, archive_status_id DESC
        ) AS approved
        WHERE approved.project_id = '".escape($project_id)."'
        GROUP BY approved.status_id";
        if (!$res = mysql_query($query)) {
          $this->set_error_msg("$query: ".mysql_query());
        }
        else {
          $tudo["statuses"] = array();
          while ($row = mysql_fetch_assoc($res)) {
            $tudo["statuses"][$row["status_type"]] = $row;
          }
        }
      } // end of getting statuses for D
      else {
        $arr = DB::get_fields(array(
          "model" => "project_statuses",
          "where" => $where,
        ));
        $tudo["statuses"] = array();
        foreach ($arr as $status) {
          $tudo["statuses"][$status["status_type"]] = $status;
        }
      } // end of getting statuses for A, S, M users

      $tudo["clients"] = $this->get_client_list($project_id);
      
      $arr = DB::get_id_name(array(
        "model" => "users",
        "where" => array(
          "user_id = '{$tudo["project_manager"]}'",
        ),  
      ), true);
      if (is_arr_valid($arr)) {
        $tudo["project_manager_name"] = $arr["name"];
      }

      $arr = DB::get_id_name(array(
        "model" => "users",
        "where" => array(
          "user_id = '{$tudo["project_supervisor"]}'",
        )
      ), true);
      if (is_arr_valid($arr)) {
        $tudo["project_supervisor_name"] = $arr["name"];
      }

      if ($tudo["project_department"] != "") {
        $arr = DB::get_id_name(array(
          "model" => "departments",
          "where" => array(
            "dep_id = '".$tudo["project_department"]."'"
          ),
        ), true);
        if (is_arr_valid($arr)) {
          $tudo["department_name"] = $arr["name"];
        }
      }
      if ($tudo["project_building"] != "") {
        $arr = DB::get_fields(array(
          "model" => "buildings",
          "where" => array(
            "build_number = '".$tudo["project_building"]."'"
          ),
        ), true);
        if (is_arr_valid($arr)) {
          $tudo["building"] = $arr;
        }
      }
    }  
    return $tudo;
  } // details

  private function get_client_list($project_id) {
    $str = "";
    $query = "
      SELECT 
        cp.project_id, 
        GROUP_CONCAT(DISTINCT c.client_name 
                     ORDER BY c.client_name 
                     SEPARATOR '; '
        ) as client_list
      FROM clients_for_projects AS cp
      LEFT JOIN clients AS c
      ON cp.client_id = c.client_id
      WHERE cp.project_id='".escape($project_id)."'
      GROUP BY cp.project_id";

    if (!($res = mysql_query($query))) {
      $this->_error_msg = mysql_error();
    }
    else if ($row = mysql_fetch_assoc($res)) {
      $str = strip($row["client_list"]);
    }  
    return $str;
  } // get_client_list

  function projects_by_building($building, $project_id) {
    $tudo = array();
    $query = "
      SELECT 
        p.project_id,
        p.project_name,
        ps.status_color,
        ps.status_approved,
        ps.status_needs_update
      FROM projects AS p
      LEFT JOIN project_statuses AS ps
      ON p.project_id=ps.project_id
      WHERE 
        p.project_steps_completed = 4
      AND
        p.project_deleted != 'Y'
      AND 
        p.project_archived != 'Y'
      AND
        p.project_building = '".escape($building)."' 
      AND 
        p.project_id != '".escape($project_id)."'
      AND  
        ps.status_type='overall'
      GROUP BY p.project_id
      ORDER BY p.project_building";

    if (!($res = mysql_query($query))) {
      $this->_error_msg = mysql_error();
      return $tudo;
    }

    while ($row = mysql_fetch_assoc($res)) {
      $tudo[] = strip($row);
    }
    return $tudo;
  } // end of projects_by_building()

  function project_info($project_id, $sections = array()) {
    $tudo = array();
    $where = array(
      "project_id = '".escape($project_id)."'"
    );

    if (in_array("projects", $sections)) {
      $project_info = DB::get_fields(array(
        "model" => "projects",
        "where" => array(
          $where[0],
          "project_steps_completed = '4'",
        )
      ));
      if (sizeof($project_info) < 1) {
        $this->_error_msg = "Project not found. (Or it might still be a Draft.)";
      }
      else {
        $tudo = $project_info[0];
      }
    }
    if (in_array("budgets", $sections)) {
      $tudo = array_merge($tudo, 
          DB::get_fields(array(
          "model" => "project_budgets",
          "where" => $where,
        ), true)
      );
    }
    if (in_array("dates", $sections)) {
      $tudo = array_merge($tudo, 
        DB::get_fields(array(
          "model" => "project_dates",
          "where" => $where,
        ), true)
      );
    }
    if (in_array("details", $sections)) {
      $tudo = array_merge($tudo, 
        DB::get_fields(array(
          "model" => "project_details",
          "where" => $where,
        ), true)
      );
    }
    if (in_array("statuses", $sections)) {
      $arr = DB::get_fields(array(
        "model" => "project_statuses",
        "where" => $where,
      ));
      $tudo["statuses"] = array();
      foreach ($arr as $status) {
        $tudo["statuses"][$status["status_type"]] = $status;
      }
    }
    if (in_array("clients", $sections)) {
      $tudo["clients"] = DB::get_fields(array(
        "model" => "clients_for_projects",
        "where" => $where,
      ));
    }
    return $tudo;
  }

  function edit($vars) {
    // get existing values
    $this->set_id($vars["project_id"]);
    $this->set_info($this->_id);
    $tudo = $this->get_info();

    // replace existing values with those from $vars
    foreach ($tudo as $varname => $value) {
      if (array_key_exists($varname, $vars)) {
        $tudo[$varname] = $vars[$varname];
      }
    }

    // update    
    if ($this->update(escape($vars)) == 0) {
      return 0;
    }
    
    // delete existing clients    
    $query = "DELETE FROM clients_for_projects 
              WHERE project_id='".mysql_escape_string($this->_id)."'";
    if (!mysql_query($query)) {
      $this->set_error_msg(mysql_error());
      return 0;
    }

    // add clients
    if (is_arr_valid($vars["project_clients_arr"])) {
      foreach ($vars["project_clients_arr"] as $client_id) {
        $resp = DB::insert("clients_for_projects", array(
          "project_id" => $this->_id,
          "client_id" => escape($client_id)
        ));
        if ($resp != "") {
          $this->set_error_msg($resp);
          //$this->set_error_msg("Couldn't save client $client_id.");
          return 0;
        }
      }
    }
    // if we got this far without returning, we're good      
    return 1;
  } // end of edit()

  function export_all($args = array()) {
    if (is_arr_valid($args)) {
      extract($args);
    }
    if (!is_arr_valid($where)) {
      $where = array();
    }  
    $tudo = array();
    $query = "
      SELECT 
        p.*,
        d.dep_name,
        cl.client_name,
        p.project_id, 
        GROUP_CONCAT(DISTINCT c.client_name 
                     ORDER BY c.client_name 
                     SEPARATOR '; '
        ) as client_list,
        pb.*,
        pdates.*,
        pd.*
      FROM projects AS p
      LEFT JOIN clients_for_projects AS cp
      ON p.project_id=cp.project_id
      LEFT JOIN clients AS c
      ON cp.client_id=c.client_id
      LEFT JOIN departments as d 
      on p.project_department = d.dep_id
      LEFT JOIN clients as cl
      on p.project_client_id = cl.client_id
      LEFT JOIN project_budgets as pb
      on p.project_id = pb.project_id
      LEFT JOIN project_dates as pdates
      on p.project_id = pdates.project_id
      LEFT JOIN project_details as pd
      on p.project_id = pd.project_id
      WHERE
        p.project_deleted != 'Y'
      AND
        p.project_steps_completed = 4".
// this should come from $where
//      AND
//        p.project_archived != 'Y'".
      (sizeof($where) > 0 ? "
      AND 
        ".implode("\nAND ", $where) : "").
      ($project_id != "" ? "
      AND
        p.project_id = '".escape($project_id)."'" : "")."
      GROUP BY p.project_id
      ORDER BY ".($order ? "$order, " : "")."p.project_building";
    
    if (!($res = mysql_query($query))) {
      // echo "$query: ".mysql_error();
      $this->_error_msg = mysql_error();
      return $tudo;
    }

    // get an array from table users
    $users = $this->get_all_user_names();
    $statuses = $this->get_all_project_statuses($project_id);

    while ($row = mysql_fetch_assoc($res)) {
      $tudo[$row["project_id"]] = strip($row);
      $tudo[$row["project_id"]]["project_manager"] = $users[$tudo[$row["project_id"]]["project_manager"]];
      $tudo[$row["project_id"]]["project_supervisor"] = $users[$tudo[$row["project_id"]]["project_supervisor"]];
      $tudo[$row["project_id"]]["statuses"] = $statuses[$row["project_id"]];
    }

    return $tudo;
  } // export_all

  private function get_all_project_statuses($project_id = "") {
    // get an array from table project_statuses    
    $tudo = array();
    $query = "
      SELECT ps.status_type, 
        ps.status_color,
        ps.status_descr,
        p.project_id
      FROM project_statuses as ps
      LEFT JOIN projects as p
      ON ps.project_id = p.project_id
      WHERE
        p.project_deleted != 'Y'
      AND
        p.project_steps_completed = 4".
      ($project_id != "" ? "
      AND
        p.project_id = '".escape($project_id)."'" : "")."
      ORDER BY p.project_id";
    
    if (!($res = mysql_query($query))) {
      // echo "$query: ".mysql_error();
      $this->_error_msg = mysql_error();
      return $tudo;
    }
    while ($row = mysql_fetch_assoc($res)) {
      if (!is_array($tudo[$row["project_id"]])) {
        $tudo[$row["project_id"]] = array();
      }  
      $tudo[$row["project_id"]][$row["status_type"]] = strip($row);
    }
    return $tudo;
  } // get_all_project_statuses

  private function get_all_user_names() {
    // get an array from table users
    $tudo = array();
    $query = "
      SELECT user_id,
        CONCAT(user_last_name, ', ', user_first_name) as user_name 
      FROM users
      ORDER BY user_id";

    if (!($res = mysql_query($query))) {
      // echo "$query: ".mysql_error();
      $this->_error_msg = mysql_error();
      return $tudo;
    }
    while ($row = mysql_fetch_assoc($res)) {
      $tudo[$row["user_id"]] = stripslashes($row["user_name"]);
    }
    return $tudo;
  }

  function archive($id, $flag) {
    $query = "
      UPDATE {$this->_table} 
      SET
      {$this->_var_pfx}archived = '".escape($flag)."'
      WHERE
      {$this->_primkey}='".escape($id)."'";

    // unique field check
    if (!mysql_query($query)) {
      $this->set_error_msg($query.": ".mysql_error());
      return 0;
    }
    else {
      return 1;
    }
  } // end of archive()

  function simple_list() {
    $tudo = array();
    $query = "SELECT
      project_id, project_name, project_number
      FROM projects 
      WHERE
        project_deleted != 'Y'
      AND
        project_steps_completed = 4
      AND 
        project_archived != 'Y'
      ORDER BY project_number, project_name";
    if (!($res = mysql_query($query))) {
      echo $this->_error_msg = mysql_error();
      return $tudo;
    }

    while ($row = mysql_fetch_assoc($res)) {
      $tudo[$row["project_id"]] = strip($row);
    }
    return $tudo;
  } // simple_list()

  function get_api_projects($status = "public") {
    $tudo = array();
    $query = "SELECT * from project_api_status
      WHERE 
        project_show_to = '".
        ($status != "public" ? "intranet" : "public")."'";
    if (!($res = mysql_query($query))) {
      echo $this->_error_msg = mysql_error();
      return $tudo;
    }
    while ($row = mysql_fetch_assoc($res)) {
      $tudo[] = strip($row["project_id"]);
    }
    return $tudo;
  } // public_projects()
  
  function set_api_status($arr, $status) {
    $status = mysql_escape_string(stripslashes($status));
    $query = "DELETE from project_api_status
      WHERE project_show_to = '".$status."'";
    if (mysql_query($query) == 0) {
      echo $this->_error_msg = mysql_error();
      return 0;
    }
    if (is_arr_valid($arr) && sizeof($arr) > 0) {
      $arr = array_map('mysql_escape_string', $arr);
      // $arr = array_map('enclose_in_brackets', $arr);
      $query = "INSERT into project_api_status(project_id, project_show_to)
                VALUES (".implode(", '$status'), (", $arr). ", '$status')";
      if (mysql_query($query) == 0) {
        echo $this->_error_msg = $query.": ". mysql_error();
        return 0;
      }
    }
    return 1;
  } // set_public_projects()

  function api_list($args = array(), $version = "public") {
    // project_id, project_name, building, department, budget, stakeholder
    // sort by project_name
    if (is_arr_valid($args)) {
      extract($args);
    }

    $tudo = array();    
    $query = "
    SELECT
      api.project_id as project_id,
      p.project_name, p.project_number, 
      p.project_building as build_num, 
      p.project_department as dep_id,
      d.dep_name as dep_name, 
      ".(is_arr_valid($extra_fields) && 
        in_array("project_manager", $extra_fields) ? "
      CONCAT(u.user_first_name, ' ', u.user_last_name) 
      AS project_manager," : "")."
      GROUP_CONCAT(DISTINCT c.client_name 
                   ORDER BY c.client_name 
                   SEPARATOR '; '
      ) as client_list,
      if (b.budget_approved_revised is not null
      and b.budget_approved_revised != 0, b.budget_approved_revised, 
      b.budget_approved_orig) as budget
      FROM project_api_status AS api
      LEFT JOIN projects AS p
      ON api.project_id = p.project_id
      LEFT JOIN clients_for_projects AS cp
      ON p.project_id=cp.project_id
      LEFT JOIN clients AS c
      ON cp.client_id=c.client_id
      LEFT JOIN departments as d
      ON p.project_department=d.dep_id
      ".(is_arr_valid($extra_fields) && 
        in_array("project_manager", $extra_fields) ? "
      LEFT JOIN users as u
      ON p.project_manager=u.user_id" : "")."
      LEFT JOIN project_budgets as b
      ON p.project_id=b.project_id
      WHERE
        api.project_show_to = '".
          ($version != "public" ? "intranet" : "public")."'
      AND
        p.project_deleted != 'Y'
      AND
        p.project_steps_completed = 4
      AND 
        p.project_archived != 'Y'
      GROUP BY api.project_id".
      (sizeof($where) > 0 ? "
      HAVING
        ".implode("\nAND ", $where) : "")."
      ORDER BY p.project_name";
    
    if (($res = mysql_query($query)) == 0) {
      echo $this->_error_msg = "$query: ".mysql_error();
      return $tudo;
    }
    
    while ($row = mysql_fetch_assoc($res)) {
      $tudo[] = strip($row);
    }
    
    return $tudo;
  } // api_list()

  function api_details($args, $version = "public") {
    if (is_arr_valid($args)) {
      extract($args);
    }

    $tudo = array();
    $query = "
    SELECT 
      api.project_id as project_id,
      p.project_name, p.project_number, p.project_descr,
      p.project_type, p.project_delivery_method,
      p.project_building as build_num,
      bld.build_address, bld.build_city, bld.build_zip,
      cz.project_city, cz.project_zip,
      p.project_department as dep_id,
      dep.dep_name as dep_name, 
      ".(is_arr_valid($extra_fields) && 
        in_array("project_manager", $extra_fields) ? "
      CONCAT(u.user_first_name, ' ', u.user_last_name) 
      AS project_manager," : "")."
      GROUP_CONCAT(DISTINCT c.client_name 
                   ORDER BY c.client_name 
                   SEPARATOR '; '
      ) as client_list,
      GROUP_CONCAT(
        CONCAT(i.image_file, ',', i.image_infeed) 
        SEPARATOR ';'
      ) as images,
      d.date_design_start, d.date_construct_start,
      if(d.date_completion_revised != '0000-00-00',
         d.date_completion_revised, d.date_completion_orig)
      as date_completion,   
      det.project_phase, det.project_percent_complete,
      det.project_category,
      if (b.budget_approved_revised is not null
      and b.budget_approved_revised != 0, b.budget_approved_revised, 
      b.budget_approved_orig) as budget
      FROM project_api_status AS api
      LEFT JOIN projects AS p
      ON api.project_id = p.project_id
      LEFT JOIN clients_for_projects AS cp
      ON p.project_id=cp.project_id
      LEFT JOIN clients AS c
      ON cp.client_id=c.client_id
      LEFT JOIN projects_images as i
      on p.project_id=i.project_id
      LEFT JOIN departments as dep
      ON p.project_department=dep.dep_id
      ".(is_arr_valid($extra_fields) && 
        in_array("project_manager", $extra_fields) ? "
      LEFT JOIN users as u
      ON p.project_manager=u.user_id" : "")."
      LEFT JOIN project_budgets as b
      ON p.project_id=b.project_id
      LEFT JOIN project_dates as d
      ON p.project_id=d.project_id
      LEFT JOIN project_details as det
      ON p.project_id=det.project_id
      LEFT JOIN buildings as bld
      ON p.project_building=bld.build_number
      LEFT JOIN city_zips as cz
      on p.project_id=cz.project_id
      WHERE
        api.project_show_to = '".
        ($version != "public" ? "intranet" : "public")."'
      AND  
        p.project_deleted != 'Y'
      AND
        p.project_steps_completed = 4
      AND 
        p.project_archived != 'Y'".
      (!$pull_all && is_var_valid($project_id) ? "  
      AND
        p.project_id = '".escape($project_id)."'" 
      : "")."  
      GROUP BY api.project_id".
      (is_arr_valid($where) ? "
      HAVING
        ".implode("\nAND ", $where) : "")."  
      ORDER BY p.project_name";
    
    if (($res = mysql_query($query)) == 0) {
      echo $this->_error_msg = "$query: ".mysql_error();
      return compact($tudo, $images);
    }

    include(CONFIG."projects.php");
    while ($row = mysql_fetch_assoc($res)) {
      $dm = strip($row["project_delivery_method"]);
      if (array_key_exists($dm, $delivery_method_options)) {
        $row["project_delivery_method"] = $delivery_method_options[$dm];
      }  
      $row["images"] = (is_var_valid($row["images"]) ? 
        $this->public_images($row["images"]) : array()
      ); 
      $tudo[] = strip($row);
    }

    return $tudo;
  } // api_details() 
  
  function public_images($str) {
    $images = array();
    if ($str == "") {
      return $images;
    }  
    $arr = explode(";", $str);
    // now we should have strings like image_id,0
    for ($i = 0; $i < sizeof($arr); $i++) {
      $arr2 = explode(",",$arr[$i]);
      if (is_array($arr2) && (int)$arr2[1] > 0) {
        $images[] = preg_replace("/index\.php/", "", ADMIN_URL).
                    UPLOADS_WEB."images/".$arr2[0];
      }  
    }  
    return $images;
  } // public_images()

} // class Project
