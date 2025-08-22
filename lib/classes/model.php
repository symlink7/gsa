<?
class Model {
  protected $_model_name;
  public $_config_file;
  // this come from config file
  public $_table;
  public $_primkey;
  public $_var_pfx;
  public $_db_fields;
  public $_order_by;
  public $_permissions;

  protected $_id;
  protected $_info;
  protected $_error_msg;

  public function __construct($controller_name) {
    $this->_model_name = strtolower(get_class($this));

    if (is_file(CONFIG.$controller_name.".php")) {
      $this->_config_file = CONFIG.$controller_name.".php";
      include($this->_config_file);
      $this->_table = $table;
      $this->_primkey = $primkey;
      $this->_var_pfx = $var_pfx;
      $this->_db_fields = $db_fields;
      $this->_order_by = $order_by;
      $this->_permissions = $permissions;
    }
    else {
      echo "Configuration file doesn't exist.";
      $this->_config_file = "";
      $this->_table = $controller_name;
      $this->_primkey = $this->_model_name."_id";
      $this->_db_fields = array();
      $this->_order_by = "";
      $this->_permissions = array();
    }
    $this->_id = 0;
    $this->_info = array();
    $this->_error_msg = "";
  }

  public function set_id($id) {
    $this->_id = $id;
  }

  public function set_info($id) {
    $real_delete =    
      (!array_key_exists($this->_var_pfx."deleted", $this->_db_fields) ?
        true : false);

    $query = "SELECT * FROM ".$this->_table."
              WHERE ".
              $this->_primkey."='".escape($id)."'".
              (!$real_delete ? "
              AND {$this->_var_pfx}deleted != 'Y'" : "");

    $res = mysql_query($query) or die(mysql_error());
    if ($row = mysql_fetch_assoc($res)) {
      $this->_info = strip($row);
    }
  }

  public function get_id() {
    return $this->_id;
  }

  public function get_info() {
    return $this->_info;
  }

  public function get_permissions() {
    return $this->_permissions;
  }

  public function get_error_msg() {
    return $this->_error_msg;
  }  
  
  protected function set_error_msg($error_msg) {
    $this->_error_msg = $error_msg;
  }

  public function get_all($args = array(), $use_primkey = false) {
    $real_delete =     
      (!array_key_exists($this->_var_pfx."deleted", $this->_db_fields) ?
        true : false);

    if (sizeof($args) > 0) {
      extract($args);
    }  
    $query = "SELECT * FROM ".$this->_table.
             (!$real_delete ? "
             WHERE {$this->_var_pfx}deleted != 'Y'" : "").
             (is_var_valid($order) ? " 
             ORDER BY $order" : "");
    $res = mysql_query($query) or die(mysql_error());
    $tudo = array();
    while ($row = mysql_fetch_assoc($res)) {
      if ($use_primkey) {
        $tudo[$row[$this->_primkey]] = strip($row);
      }
      else {
        $tudo[] = strip($row);
      }  
    }
    return $tudo;
  } // get_all

  // generic add function, should work for most models
  public function add($vars) {
    return $this->insert(escape($vars));
  } // end of add()

  public function edit($vars) {
    return $this->update(escape($vars));
  }

  // on success returns 1 and saves insert id as $this->_id
  protected function insert($vars) {
    $values = array();
    foreach ($this->_db_fields as $varname => $varspecs) {
      if ($varspecs[0] == "date" && $varspecs[5] == "auto") {
        $values[] = "CURDATE()";
      }  
      else if ($varspecs[0] == "datetime" && $varspecs[5] == "auto") {
        $values[] = "NOW()";
      }  
      else if ($varspecs[5] == "password") {
        $values[] = "MD5('{$vars[$varname]}')";
      }  
      else if ($varspecs[0] == "set" && 
               !array_key_exists($varname, $vars) &&
               is_var_valid($varspecs[5])) {
        $values[] = "'{$varspecs[5]}'";
      }  
      else {
        $values[] = "'{$vars[$varname]}'";
      }  
    }
    $query = "INSERT INTO {$this->_table} 
              VALUES (".
                implode(", ", $values).
              ")";
    
    // unique field check
    if (!mysql_query($query)) {
      $this->set_error_msg(mysql_error());
      return 0;
    }
    
    if ($this->_primkey != "" && 
        preg_match("/increment/", $this->_db_fields[$this->_primkey][0])
    ) { 
      $id = mysql_insert_id();
      if ($id > 0) {
        $this->set_id($id);
      }
      else {
        return 0;
      }
    }
    return 1;
  }

  protected function update($vars) {
    $values = array();
    foreach ($this->_db_fields as $varname => $varspecs) {
      if ($varspecs[4] != 1) {
        continue; // not to be edited
      }
      if ($varspecs[0] == "date" && $varspecs[5] == "auto") {
        $values[] = "{$varname} = CURDATE()";
      }  
      else if ($varspecs[0] == "datetime" && $varspecs[5] == "auto") {
        $values[] = "{$varname} = NOW()";
      }
      else if ($varspecs[0] == "datetime" && $varspecs[5] == "on-demand") {
        $values[] = "{$varname} = ".
          ($vars[$varname] != "" ? "NOW()" : "''");
      }  
      else if ($varspecs[5] == "password") {
        $values[] = "{$varname} = MD5('{$vars[$varname]}')";
      }  
      else {
        $values[] = "{$varname} = '{$vars[$varname]}'";
      }  
    }
    $query = "UPDATE {$this->_table} 
              SET ".
                implode(",\n", $values)."
              WHERE 
                {$this->_primkey} = '{$vars[$this->_primkey]}'";

    // unique field check
    if (!mysql_query($query)) {
      $this->set_error_msg($query.": ".mysql_error());
      return 0;
    }
    else { // mysql_addected_rows() unreliable
      return 1;
    }  
  } // end of update()

  public function delete($id) {
    $real_delete = 
      (!array_key_exists($this->_var_pfx."deleted", $this->_db_fields) ? 
        true : false);
    
    $query = ($real_delete ? 
                "DELETE from {$this->_table}"
                :
                "UPDATE {$this->_table} 
                 SET
                  {$this->_var_pfx}deleted = 'Y'"
             )."
             WHERE
             {$this->_primkey}='".escape($id)."'";

    // unique field check
    if (!mysql_query($query)) {
      $this->set_error_msg($query.": ".mysql_error());
      return 0;
    }
    else if ($real_delete && mysql_affected_rows() < 1) {
      $this->set_error_msg("No matching records found, nothing to delete.");
      return 0;
    }
    else {
      return 1;
    }
  } // end of delete()

}
