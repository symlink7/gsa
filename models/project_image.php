<?

class Project_image extends Model {

  // uses generic methods from Model
  function project_info($project_id, $include_images = true) {
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
      if ($include_images) {
        $tudo["images"] = DB::get_fields(array(
          "model" => "project_images",
          "where" => $where,
        ));
      }  
    }
    return $tudo;
  } // project_info

  function add($vars) {
    if ($this->insert(escape($vars)) == 0) {
      return 0;
    }
    // insert successful
    if ($this->_id == 0) {
      $this->set_error_msg("Couldn't fetch image ID");
      return 0;
    }
    return 1;
  } // add

  function add_filename($id, $filename) {
    $query = "UPDATE {$this->_table} 
      SET image_file='".escape($filename)."'
      WHERE {$this->_primkey} = '".escape($id)."'";
    if (!mysql_query($query)) {
      $this->set_error_msg("$query: ".mysql_error());
      return 0;
    }
    return 1;
  } // add_filename

  function infeed($id) {
    $query = "UPDATE {$this->_table}
      SET image_infeed = not `image_infeed`
      WHERE {$this->_primkey} = '".escape($id)."'";
    if (!mysql_query($query)) {
      $this->set_error_msg("$query: ".mysql_error());
      return 0;
    }
    return 1;
  } // infeed()  
}
?>
