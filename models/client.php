<?

class Client extends Model {
  
  // uses generic methods from Model
  public function delete($id) {
    $query = "DELETE from clients_for_projects
              WHERE
              {$this->_primkey}='".escape($id)."'";
    mysql_query($query);

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
