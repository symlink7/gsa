<?
define('DEBUG_MODE', 0);

function set_reporting($dev = 0) {
  if ($dev) {
    error_reporting(E_ALL & ~E_NOTICE);
    ini_set('display_errors', 1);
  }
  else {
    error_reporting(E_ALL & ~E_NOTICE);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
  }
}

function strip($value) {
  $value = is_array($value) ? 
            array_map('strip', $value) : stripslashes($value);
  return $value;
}

function escape($value) {
  $value = is_array($value) ? 
            array_map('escape', $value) : mysql_escape_string($value);
  return $value;
}

function remove_magic_quotes() {
  $_GET = strip($_GET);
  $_POST = strip($_POST);
//  $_COOKIE = strip($_COOKIE);
}

function is_var_valid($var) {
  if (!isset($var) || $var == "")
    return 0;
  else
    return 1;
}

function is_arr_valid($arr) {
  if (!isset($arr) || !is_array($arr) || empty($arr))
    return 0;
  else
    return 1;
}

function valid_email($email){
  $parts1 = explode("@", $email);
  $parts2 = explode(".", $parts1[1]);
  if (sizeof($parts1) < 2 || sizeof($parts1) > 2 || sizeof($parts2) < 2)
    return false;
  else if (!preg_match("/^[A-Za-z0-9]{1}[A-Za-z0-9\.\-\_]{1,50}$/", trim($parts1[0])))
    return false;
  else {  
    $dom = array_pop($parts2);
    $domain = array_pop($parts2);
    if (!preg_match("/^[A-Za-z]{2,4}$/", $dom))
      return false;
    else if (!preg_match("/^[A-Za-z0-9]{1}[A-Za-z0-9\-\_]{1,50}$/", $domain))  
      return false;
    else
      return true;
  }
}  

function valid_password($str) {
  if (!preg_match("/^[A-Za-z0-9]{1}[A-Za-z0-9\.\-\_\#\+]{7,15}$/", $str)) {
    return false;
  }
  else {
    return true;
  }
}

function valid_date($date) {
  $arr = explode("-", $date);

  if (sizeof($arr) != 3) {
    debug_print('sizeof($arr) != 3'); 
    return false;
  }
  
  $year = $arr[0];
  $month = $arr[1];
  $day = $arr[2];

  if (strlen($year) != 4 || strlen($month) != 2 || strlen($day) != 2) {
    debug_print('strlen($year) != 4 || strlen($month) != 2 || strlen($day) != 2'); 
    return false;
  }

  $year = (int)$year;
  if ($year < 2000 || $year > date("Y") + 20) {
    debug_print('$year < 2000 || $year > date("Y") + 20');
    return false;
  }

  $month = (int)$month;
  if ($month < 1 || $month > 12) {
    debug_print('$month < 1 || $month > 12');
    return false;
  }

  $day = (int)$day;
  if ($day < 1 || $day > 31) {
    debug_print('$day < 1 || $day > 31');
    return false;
  }
  if (in_array($month, array(4, 6, 9, 11)) && $day > 30) {
    debug_print('in_array($month, array(4, 6, 9, 11)) && $day > 30');
    return false;
  }
  if ($month == 2) {
    if ($year % 4 > 0 && $day > 28) {
      debug_print('$month == 2 && $year % 4 > 0 & $day > 28');
      return false;
    }
    else if ($day > 29) {
      debug_print('$month == 2 && $day > 29');
      return false;
    }
  }

  return true;
}

function debug_print($error_msg) {
  if (DEBUG_MODE) {
    print("$error_msg\n");
  }
}

function format_date($date, $default = "N/A", $format = "m-d-y") {
  $str = $default;
  if (is_var_valid($date) && $date != "0000-00-00") {
    $str = date($format, strtotime($date));
  }
  return $str;
}

function format_budget($str, $default = "N/A") {
  return ($str ? ely_money_format("%!.0i", $str) : $default);
}  

function serialize_array($arr, $sep = "\n") {
  $ready = array();
  foreach ($arr as $key => $value) {
    $ready[] = "$key = $value";
  }
  return implode($sep, $ready);
}

function missing_fields($vars, $req = array()) {
  $missing = array();
  foreach ($req as $varname) {
    if (!is_var_valid($vars[$varname])) {
      $missing[] = $varname;
    }
  }
  return $missing;
}

function enclose_in_quotes($from_arr, $fields) {
  foreach ($fields as $varname) {
    $from_arr[$varname] = "'".$from_arr[$varname]."'";
  }
  return $from_arr;
}

function round_down($n) {
  $factor = 0;
  for ($factor = 0; $n > 10; $factor++) {
    $n = floor($n / 10);
  }
  return $n * pow(10, $factor);
}

function round_up($n) {
  $factor = 0;
  for ($factor = 0; $n > 10; $factor++) {
    $n = floor($n / 10);
  }
  return ($n * pow(10, $factor) + 1 * pow(10, $factor));
}

function checkboxes_to_string($arr) {
  $arr = array_map('stripslashes', 
          array_map('escape', $arr));
  if (empty($arr[sizeof($arr)-1])) {
    array_pop($arr);
  }
  return (is_arr_valid($arr) ? implode("||", $arr) : $arr);
}

function options_to_ul($str, $section, $options_arr_name) {
  include(CONFIG.$section.".php");
  $options = ${$options_arr_name};

  if (!is_var_valid($str)) { 
    return $str;
  }  
  if (!preg_match("/\|\|/", $str) && 
      !array_key_exists($str, $options)) {
    return $str;
  }  
  $arr = explode("||", $str);
  $str = "";
  foreach ($arr as $value) {
    $str .= "<li>".
      (array_key_exists($value, $options) ?
        $options[$value] : htmlspecialchars($value))."</li>\n";
  }
  return "<ul>".$str."</ul>\n";
}

function options_to_cdl($str, $section, $options_arr_name) {
  include(CONFIG.$section.".php");
  $options = ${$options_arr_name};
  if (!is_var_valid($str)) {
    return $str;
  }
  if (!preg_match("/\|\|/", $str) &&
      !array_key_exists($str, $options)) {
    return $str;
  }
  $arr = explode("||", $str);
  $arr2 = array();
  foreach ($arr as $value) {
    $arr2[] = (array_key_exists($value, $options) ?
      $options[$value] : $value);
  }
  return implode(", ", $arr2);
}

function add_prefix($var, $prefix) {
  return (is_var_valid($var) ? $prefix.$var : "");
}

function add_suffix($var, $suffix) {
  return (is_var_valid($var) ? $var.$suffix : "");
}

function add_tag($var, $tag) {
  return (is_var_valid($var) ? "<$tag>$var</$tag>" : "");
}

function get_var($varname, $config_file) {
  $var = "";
  if (is_var_valid($config_file) && is_file(CONFIG.$config_file.".php")) {
    include(CONFIG.$config_file.".php");
    $var = ${$varname};
  }
  return $var;
}

function check_for_missing($vars = array(), $req = array()) {
	$missing = array();
  foreach ($req as $varname) {
  	if (!is_var_valid($vars[$varname])) {
    	$missing[] = $varname;
    }
  }
	return $missing;
} // end of check_for_missing

function check_os() {
  $agent = $_SERVER["HTTP_USER_AGENT"];
  if (preg_match("/Windows/", $agent))
    $os = "Windows";
  else if (preg_match("/Mac OS/", $agent))
    $os = "Mac";
  else
    $os = "other";
  return $os;
}

function neutralize($arr) {
  $o_arr = array();
  foreach ($arr as $el) {
    if ((int)$el > 0) {
      $o_arr[] = $el;
    }
  }
  return $o_arr;
}

function enclose_in_brackets($str) {
  return "(".$str.")";
}

function remove_element_by_value($val, $arr) {
  $arr2 = array();
  foreach($arr as $el) {
    if ($el != $val) {
      $arr2[] = $el;
    }
  }
  return $arr2;
}

function ely_money_format($format, $number, $currency = 'USD') {
  $fmt = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
  if (strpos($format, '.0') !== false) {
    $fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, 0);
  }
  return $fmt->formatCurrency($number, $currency);
}
