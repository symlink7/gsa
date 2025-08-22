<?

class City_zip extends Model {
  
  // uses generic methods from Model

  function all_projects() {
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

  function public_projects() {
    $tudo = array();
    $query = "SELECT * from project_api_status";
    if (!($res = mysql_query($query))) {
      echo $this->_error_msg = mysql_error();
      return $tudo;
    }
    while ($row = mysql_fetch_assoc($res)) {
      $tudo[] = strip($row["project_id"]);
    }
    return $tudo;
  } // public_projects()

}
