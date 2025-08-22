<?

class User extends Model {

  public function check_login() {
    include(CONFIG."users.php");
    $success = 0;
    
    if (!is_var_valid($_POST["login_email"]) || 
        !is_var_valid($_POST["login_password"])) {
      return 0;
    }
    
    $escaped = escape($_POST);
    $real_delete =
      (!array_key_exists($this->_var_pfx."deleted", $this->_db_fields) ?
        true : false);

    $query = "SELECT * FROM ".$this->_table."
              WHERE user_email = '".$escaped["login_email"]."'
              AND user_password = MD5('".$escaped["login_password"]."')".
              (!$real_delete ? "
              AND {$this->_var_pfx}deleted != 'Y'" : "");
    $res = mysql_query($query) or die(mysql_error());
    
    if (mysql_num_rows($res) > 0) {
      $success = 1;
      
      if ($row = mysql_fetch_assoc($res)) {
        $this->_id = $row[$this->_primkey];
        $this->_info = $row;
        $this->_info["user_role_name"] = $user_role_options[$row["user_role"]];
        $this->init_user();
      }  
    }
    
    return $success;
  }
  
  private function init_user() {
    // save the last login time and ip in the database
    $query = "INSERT INTO user_logins
              (user_id, user_login_time, user_login_ip)
              VALUES
              ('".(int)$this->_id."', 
              now(), '".getenv(REMOTE_ADDR)."')";
    mysql_query($query) or die(mysql_error());
    
    // save the last login time and ip in user_info
    $query = "SELECT * FROM user_logins
              WHERE user_id = '".(int)$this->_id."'
              ORDER BY user_login_time DESC
              LIMIT 2";
    $res = mysql_query($query) or die(mysql_error());
    
    $n = 0;
    while ($row = mysql_fetch_assoc($res)) {
      $time_var = ($n < 1 ? "login_time" : "last_login_time");
      $ip_var = ($n < 1 ? "login_ip" : "last_login_ip");
      $this->_info[$time_var] = $row["user_login_time"];
      $this->_info[$ip_var] = $row["user_login_ip"];
      $n++;
    }  
  } // end of init_user()

  public function email_exists($email, $user_id = 0) {
    $real_delete =
      (!array_key_exists($this->_var_pfx."deleted", $this->_db_fields) ?
        true : false);
    $query = "SELECT * FROM ".$this->_table."
              WHERE user_email = '".mysql_escape_string($email)."'".
              ($user_id > 0 ? "
              AND user_id != '".mysql_escape_string($user_id)."'" : "").
              (!$real_delete ? "
              AND {$this->_var_pfx}deleted != 'Y'" : "");
    $res = mysql_query($query) or die(mysql_error());

    if (mysql_num_rows($res) > 0) {
      return true;
    }
    return false;
  } // end of email_exists  

  public function add($vars) {
    // check if email doesn't already exist
    if ($this->email_exists($vars["user_email"])) {
      $this->set_error_msg("A user with this email already exists.");
			return 0;
		}
		// end of email check

    $vars["user_password"] = $this->generate_password();
    $escaped = escape($vars);

    if ($this->insert($escaped) == 0) {
      return 0;
    }
    else {
      // send password to user
      include(CONFIG."users.php");
      $vars["user_role_name"] = $user_role_options[$vars["user_role"]];
      $vars["admin_url"] = ADMIN_URL;
      
      require_once(CLASSES."mail.php");
      Mail::prepare_and_send($vars["user_email"], 
        "user_account_created", $vars);
      return 1;
    }
  } // end of add()

  public function change_pass() {
    $vars = escape(strip($_POST));
    $query = "UPDATE {$this->_table}
              SET user_password = MD5('{$vars["new_password"]}')
              WHERE {$this->_primkey} = '{$vars[$this->_primkey]}'";
    if (!mysql_query($query)) {
      $this->set_error_msg(mysql_error());
      return 0;
    }
    else {
      return 1;
    }  
  } // end of change_pass

  function reset_pass($email) {
    $tudo = array();
    $real_delete =
      (!array_key_exists($this->_var_pfx."deleted", $this->_db_fields) ?
        true : false);

    $query = "SELECT * FROM ".$this->_table."
              WHERE user_email = '".mysql_escape_string($email)."'".
              (!$real_delete ? "
              AND {$this->_var_pfx}deleted != 'Y'" : "");
    $res = mysql_query($query) or die(mysql_error());

    if (mysql_num_rows($res) > 0) {
      if ($row = mysql_fetch_assoc($res)) {
        $tudo = strip($row);
      }
    }
    return $tudo;
  } // end of reset_pass()

  private function generate_password() {
    $caps = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $lows = "abcdefghijklmnopqrstuvwxyz";
    $chars = "#+-";
    $nums = "0123456789";
    $let = strlen($caps) - 1;
    $str = $lows[rand(0,$let)].
           $lows[rand(0,$let)].
           $lows[rand(0,$let)].
           $caps[rand(0,$let)].
           $caps[rand(0,$let)].
           $lows[rand(0,$let)].
           $nums[rand(0,9)].
           $chars[rand(0,2)].
           $caps[rand(0,$let)].
           $caps[rand(0,$let)].
           $nums[rand(0,9)];
    return $str;
  } // generate_password

}
