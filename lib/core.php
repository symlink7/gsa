<?
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

function format_date($date, $default = "N/A") {
  $str = $default;
  if (is_var_valid($date) && $date != "0000-00-00") {
    $str = date("m-d-y", strtotime($date));
  }
  return $str;
}

function format_budget($str, $default = "N/A") {
  return ($str ? money_format("%!.0i", $str) : $default);
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

function money_format($format, $number, $currency = 'USD') {
  $fmt = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
  if (strpos($format, '.0') !== false) {
    $fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, 0);
  }
  return $fmt->formatCurrency($number, $currency);
}
