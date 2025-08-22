<?
class Cron {

  public static function tue_before_last_monday($today = "") {
    if (!is_var_valid($today)) {
      $today = time();
    }
    if (date("Ymd", (strtotime("last Monday of this month", $today) - 3600*24*6)) ==
        date("Ymd", $today)) {
      return true;
    }
    else {
      return false;
    }
  } // end of tue_before_last_monday()

  public static function last_monday($today = "") {
    if (!is_var_valid($today)) {
      $today = time();
    }
    if (date("Ymd", strtotime("last Monday of this month", $today)) ==
        date("Ymd", $today)) {
      return true;
    }
    else {
      return false;
    }
  } // last_monday()

  public static function tue_after_last_monday($today = "") {
    if (!is_var_valid($today)) {
      $today = time();
    }
    if ( 
        (date("Ymd", 3600*24 + strtotime("last Monday of this month", $today)) ==
         date("Ymd", $today)) ||
        (date("Ymd", 3600*24 + strtotime("last Monday of previous month", $today)) ==
         date("Ymd", $today))
       ) {
      return true;
    }
    else {
      return false;
    }
  } // last_monday()

  public static function thu_after_last_monday($today = "") {
    if (!is_var_valid($today)) {
      $today = time();
    }
    if (
        (date("Ymd", 3600*24*3 + strtotime("last Monday of this month", $today)) ==
         date("Ymd", $today)) ||
        (date("Ymd", 3600*24*3 + strtotime("last Monday of previous month", $today)) ==
         date("Ymd", $today))
    ) {
      return true;
    }
    else {
      return false;
    }
  } // last_monday()

  public static function all_existing_projects() {
    // a list of all completed existing projects
    $tudo = array();
    $query = "SELECT project_id FROM projects 
      WHERE project_deleted != 'Y'
      AND project_steps_completed = 4
      AND project_archived != 'Y'";
    $res = mysql_query($query) or die("$query: ".mysql_error());
    while ($row = mysql_fetch_assoc($res)) {
      $tudo[] = $row["project_id"];
    }
    return $tudo;  
  }

  public static function all_project_managers() {
    // a list of all project managers with completed existing projects
    $tudo = array();
    $query = "
      SELECT p.project_manager, 
        u.user_first_name, u.user_last_name, 
        u.user_email
      FROM projects as p 
      LEFT JOIN users as u
      ON p.project_manager = u.user_id
      WHERE p.project_deleted != 'Y'
      AND p.project_steps_completed = 4
      AND p.project_archived != 'Y'
      AND u.user_deleted != 'Y'
      GROUP BY p.project_manager";
    $res = mysql_query($query) or die("$query: ".mysql_error());
    while ($row = mysql_fetch_assoc($res)) {
      $tudo[] = strip($row);
    }
    return $tudo;
  }

  public static function all_project_supervisors() {
    // a list of all project supervisors with completed existing projects
    $tudo = array();
    $query = "
      SELECT p.project_supervisor, 
        u.user_first_name, u.user_last_name, 
        u.user_email
      FROM projects as p 
      LEFT JOIN users as u
      ON p.project_supervisor = u.user_id
      WHERE p.project_deleted != 'Y'
      AND p.project_steps_completed = 4
      AND p.project_archived != 'Y'
      AND u.user_deleted != 'Y'
      GROUP BY p.project_supervisor";
    $res = mysql_query($query) or die("$query: ".mysql_error());
    while ($row = mysql_fetch_assoc($res)) {
      $tudo[] = strip($row);
    }
    return $tudo;
  }

  public static function expire_all_statuses($project_ids) {
    $query = "UPDATE project_statuses
      SET status_needs_update='Y'
      WHERE 
        project_id in ('".implode("', '", $project_ids)."')";
    if (!mysql_query($query)) {
      echo "$query: ".mysql_error();
      return false;
    }
    else {
      return true;
    }
  }

  public static function projects_by_flag($which) {
    $tudo = array();
    $where = array(
      "p.project_deleted != 'Y'",
      "p.project_steps_completed = 4",
      "p.project_archived != 'Y'",
    );
    if ($which == "pending")
      $where[] = "ps.status_approved='N'";
    else if ($which == "need_update")
      $where[] = "ps.status_needs_update='Y'";
    $query = "
      SELECT ps.project_id, 
      p.project_name, 
      p.project_building,
      cp.project_id,
      GROUP_CONCAT(DISTINCT c.client_name 
                   ORDER BY c.client_name 
                   SEPARATOR '; '
      ) as client_list
      FROM project_statuses AS ps
      LEFT JOIN projects AS p
        ON ps.project_id = p.project_id
      LEFT JOIN clients_for_projects AS cp
        ON p.project_id=cp.project_id
      LEFT JOIN clients AS c
        ON cp.client_id=c.client_id
      WHERE ".implode("\nAND ", $where)."
      GROUP BY ps.project_id
      ORDER BY p.project_building, client_list";

    $res = mysql_query($query) or die("$query: ".mysql_error());
    while ($row = mysql_fetch_assoc($res)) {
      $tudo[] = strip($row);
    }
    return $tudo;
  } // end of projects_by_flag

  public static function all_admin_users() {
    $tudo = array();
    $query = "
      SELECT 
        user_first_name, user_last_name, 
        user_email
      FROM users 
      WHERE user_role = 'A' 
      AND user_deleted != 'Y'";
    $res = mysql_query($query) or die("$query: ".mysql_error());
    while ($row = mysql_fetch_assoc($res)) {
      $tudo[] = strip($row);
    }
    return $tudo;
  } // all_admin_users

} // end of class Cron
?>
