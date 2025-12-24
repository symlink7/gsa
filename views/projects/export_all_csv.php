<?
require_once(VIEWS."projects/funcs.php");
require_once(VIEWS."projects/export_funcs.php");
include(CONFIG."projects.php");

$db_fields_all = ExportFuncs::get_db_fields_all();
$fields = ExportFuncs::get_fields();

// filter out fields
if (is_array($include_fields) && sizeof($include_fields) > 0 &&
   !in_array("all", $include_fields)) {
  $fields = ExportFuncs::filter_funcs($fields, $include_fields);
}

$str = "";

$header_fields = array();
foreach ($fields as $varname) {
  $header_fields[] = '"'.$db_fields_all[$varname][2].'"';
}

$str .= implode("\t", $header_fields)."\n";

$critical_activity_options = get_var("critical_activity_options", "project_statuses");
$bos_action_options = get_var("bos_action_options", "project_statuses");
$project_category_options = get_var("project_category_options", "project_details");

// get encoding
$os = check_os();
switch ($os) {
  case "Mac":
    $enc = "MacRoman";
    break;
  case "Windows":
    $enc = "ISO-8859-1";
    break;
  default:
    $enc = "UTF-8";
}    

foreach ($tudo as $project) {
  $line = array();
  foreach ($fields as $varname) {
    // most basic version
    if (array_key_exists($varname, $project)) {
      if ($varname == "project_district") {
        $line[] = '"'.$project_district_options[$project[$varname]].'"';
      }
      else if ($varname == "project_delivery_method") {
        $line[] = '"'.$delivery_method_options[$project[$varname]].'"';
      }
      else if ($varname == "project_category") {
        $line[] = '"'.$project_category_options[$project[$varname]].'"';
      }  
      else {
        $line[] = prepare_field(
                    $project[$varname], 
                    $db_fields_all[$varname],
                    $enc
                  );
      }
    }
    else { // if it's a status field
      if (preg_match("/\_status\_color/", $varname)) {
        $status_name = preg_replace("/\_status\_color/", "", $varname);
        $line[] = '"'.ucfirst(
          $project["statuses"][$status_name]["status_color"]
        ) .'"';
      }
      else if ($varname == "bos_action_status_action_date") {
        $status_name = "bos_action";
        $line[] = 
          format_date($project["statuses"][$status_name]["status_action_date"],
            "", "Y-m-d");
      }
      else if (preg_match("/\_status\_descr/", $varname)) {
        $status_name = preg_replace("/\_status\_descr/", "", $varname);
        
        if ($status_name == "critical_activity") {
          $project["statuses"][$status_name]["status_descr"] =        
            options_to_cdl(
              ProjectFuncs::prepare_critical_activity(
                $project["statuses"][$status_name]["status_descr"],
                $critical_activity_options
              ),  
              "project_statuses", 
              "critical_activity_options"
            );  
        }

        else if ($status_name == "bos_action") {
          $project["statuses"][$status_name]["status_descr"] =
            options_to_cdl(
              ProjectFuncs::prepare_critical_activity(
                $project["statuses"][$status_name]["status_descr"],
                $bos_action_options
              ),
              "project_statuses",
              "bos_action_options"
            );
        }

        $line[] = prepare_field(
          $project["statuses"][$status_name]["status_descr"], 
          array("text"), $enc
        );
      }  
    }
  }
  $str .= implode("\t", $line)."\n";
}

header("Content-type: application/x-msdownload; charset=utf-8");
header("Content-Disposition: attachment; filename=".
  (is_var_valid($filename) ? "$filename-".date("Y-m-d") : 
                             "projects-".date("Y-m-d")
  )."-$enc.csv");
header("Pragma: no-cache");
header("Expires: 0");
echo $str;

function prepare_field($value, $specs, $enc = "UTF-8") {
  if ($specs[0] == "text") {
    $value = strip_tags($value);

    $value = html_entity_decode($value, ENT_QUOTES|ENT_HTML401, $enc);
//    $value = preg_replace("/\r/", "  ", $value);
//    $value = preg_replace("/\n/", "  ", $value);
    $value = preg_replace("/\"/", "'", $value);
    $value = '"'.trim($value).'"';
  }  
  else if ($specs[0] == "varchar" || $specs[0] == "int not null") {
    $value = preg_replace("/\"/", "'", $value);
    $value = '"'.trim($value).'"';
  }
  else if ($specs[0] == "date" && $value == "0000-00-00") {
    $value = "";
  }
//  else if ($specs[0] == "bigint") {
//    $value = ($value ? "$".money_format("%!.0i", $value) : '"N/A"');
//  }
  return $value;
}

?>
