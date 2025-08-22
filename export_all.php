<?
date_default_timezone_set("America/Los_Angeles");
require_once(dirname(__FILE__)."/config.php");
require_once(LIBRARY."core.php");
require_once(LIBRARY."connect.php");
$tudo = export_all();
print_r($tudo);

  function export_all() {
    $tudo = array();
    $query = "
      SELECT 
        p.*,
        d.dep_name,
        cp.project_id, 
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
      LEFT JOIN project_budgets as pb
      on p.project_id = pb.project_id
      LEFT JOIN project_dates as pdates
      on p.project_id = pdates.project_id
      LEFT JOIN project_details as pd
      on p.project_id = pd.project_id
      WHERE
        p.project_deleted != 'Y'
      AND
        p.project_steps_completed = 4 
      GROUP BY p.project_id
      ORDER BY p.project_building";
    
    if (!($res = mysql_query($query))) {
      echo "$query: ".mysql_error();
      // $this->_error_msg = mysql_error();
      return $tudo;
    }

    // get an array from table users
    $users = get_all_user_names();
    $statuses = get_all_project_statuses();

    while ($row = mysql_fetch_assoc($res)) {
      $tudo[$row["project_id"]] = strip($row);
      $tudo[$row["project_id"]]["project_manager"] = $users[$tudo[$row["project_id"]]["project_manager"]];
      $tudo[$row["project_id"]]["project_supervisor"] = $users[$tudo[$row["project_id"]]["project_supervisor"]];
      $tudo[$row["project_id"]]["statuses"] = $statuses[$row["project_id"]];
    }

    return $tudo;
  } // export_all

  function get_all_project_statuses() {
    // get an array from table project_statuses    
    $tudo = array();
    $query = "
      SELECT ps.*,
        p.project_id
      FROM project_statuses as ps
      LEFT JOIN projects as p
      ON ps.project_id = p.project_id
      WHERE
        p.project_deleted != 'Y'
      AND
        p.project_steps_completed = 4
      ORDER BY p.project_id";
    
    if (!($res = mysql_query($query))) {
      echo "$query: ".mysql_error();
      // $this->_error_msg = mysql_error();
      return $tudo;
    }
    while ($row = mysql_fetch_assoc($res)) {
      $tudo[$row["project_id"]] = strip($row);
    }
    return $tudo;
  } // get_all_project_statuses

  function get_all_user_names() {
    // get an array from table users
    $tudo = array();
    $query = "
      SELECT user_id,
        CONCAT(user_last_name, ', ', user_first_name) as user_name 
      FROM users
      ORDER BY user_id";

    if (!($res = mysql_query($query))) {
      echo "$query: ".mysql_error();
      // $this->_error_msg = mysql_error();
      return $tudo;
    }
    while ($row = mysql_fetch_assoc($res)) {
      $tudo[$row["user_id"]] = stripslashes($row["user_name"]);
    }
    return $tudo;
  }
