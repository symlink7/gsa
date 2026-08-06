<?
class ProjectFuncs {
// some static funcs for project views
  public static function same_line_variables($tudo, $varnames) {
    $arr = array();
    foreach ($varnames as $varname) {
      if (is_var_valid($tudo[$varname])) {
        $arr[] = $tudo[$varname];
      }
    }
    return $arr;
  }

  public static function show_last_updated_time($status_arr) {
    return (is_var_valid($status_arr["status_created_time"]) &&
      substr($status_arr["status_created_time"], 0, 4) != "0000"
        ? true : false);
  }

  public static function show_last_approved_time($status_arr) {
    return ($status_arr["status_approved"] == "Y" &&
      is_var_valid($status_arr["status_approved_time"]) &&
      substr($status_arr["status_approved_time"], 0, 4) != "0000"
        ? true : false);
  }

  public static function format_datetime($date) {
    return date("m/d/y, h:ia", strtotime($date));
  }

  public static function status_colors($statuses) {
    return array(
      "color_o" => $statuses["overall"]["status_color"],
      "color_sco" => $statuses["scope"]["status_color"],
      "color_sch" => $statuses["schedule"]["status_color"],
      "color_b" => $statuses["budget"]["status_color"],
    );
  }
  
  public static function status_status($status_arr) {
    if ($status_arr["needs_update"] == "Y") {
      return "Needs Update";
    }
    else {
      return ($status_arr["status_approved"] == "Y" ?
        "Approved" : "Pending");
    }
  }
  public static function format_budget($str, $default = "N/A") {
    return ($str ? ely_money_format("%!.0i", $str) : $default);
  }  

  public static function building_info($building, $sep = " | ") {
    $str = "";
    if (is_arr_valid($building)) {
      $address = array();
      $all = array();
      if (is_var_valid($building["build_address"])) {
        $address[] = $building["build_address"];
      }
      if (is_var_valid($building["build_city"])) {
        $address[] = $building["build_city"];
      }
      if (sizeof($address) > 0) {
        $all[] = implode(", ", $address);
      }
      if (is_var_valid($building["build_type"])) {
        $all[] = $building["build_type"];
      }
    }
    if (is_arr_valid($all)) {
      $str = implode($sep, $all);
    }
    return $str;
  }

  // $options is optional because i only want to use get_var ONCE
  // when accessing this public static function from a loop (like export_all)
  public static function prepare_critical_activity($ca, $options = array()) {
    // exit early with an empty string if ca is empty
    if (!is_var_valid($ca)) {
      return "";
    }

    if (!is_arr_valid($options)) {
      $options = get_var("critical_activity_options", "project_statuses");
    }  
    // if it's the new type of c_a and more than one option
    if (preg_match("/\|\|/", $ca)) {
      $remove_other = 0;
      $ca_checked = explode("||", $ca);
      $arr = array();
      foreach ($ca_checked as $val) {
        if (!is_var_valid(trim($val))) {
          continue;
        }  
        if (array_key_exists($val, $options)) {
          $arr[] = $val;
        }
        else { // must be the other option
          $arr[] = "Other: ".strip_tags($val);
          $remove_other = 1;
        }
      }
      $ca_checked = $arr;
      if ($remove_other > 0) {
        $ca_checked = remove_element_by_value("other", $ca_checked);
      }
      return implode("||", $ca_checked);
    }
    else if (array_key_exists($ca, $options)) {
      return $ca;
    }
    else {
      return "Other: ".strip_tags($ca);
    }  
  } // prepare_critical_activity()

} // ProjectFuncs  
?>
