<?

class Session {

  private $session_on;
  private $keys;

  public function __construct($logout = 0) {
    $this->keys = array("user_id", "user_info", "logged_in");
    $this->is_session_on();
    if ($logout) {
      if ($this->session_on == 1) {
        $this->end_session();
        $this->session_on = 0;
      }
    }  
  }
  
  private function is_session_on() {
    if (!is_var_valid($_SESSION)) {
      $this->session_on = 0;  
    }
    else {
      $this->session_on = 1;
      foreach ($this->keys as $varname) {
        if (!array_key_exists($varname, $_SESSION) || 
            (is_array($_SESSION[$varname]) && !is_arr_valid($_SESSION[$varname]))
            || !is_var_valid($_SESSION[$varname])
            ) {
          $this->session_on = 0;
        }  
      }
    }
  }
  
  public function get_session_on() {
    return $this->session_on;
  }

  public function start_session($arr){
    $success = 0;
    foreach ($this->keys as $varname) {
      if (array_key_exists($varname, $arr) && 
          ((is_array($arr[$varname]) && is_arr_valid($arr[$varname])) 
            || is_var_valid($arr[$varname])
           )
          ) {
        $_SESSION[$varname] = $arr[$varname];
        $success = 1;
      }
      else {
        $success = 0;
      }
    } // check success eventually  
    if ($success) {
      $this->session_on = 1;
    }  
  }

  private function end_session() {
    // reset session
    $_SESSION = array();
    $this->session_on = 0;
  }
}
