<?
class ExportFuncs {

  public static function get_db_fields_all() {
    include(CONFIG."projects.php");

    $db_fields_all = $db_fields;
    $sections = array("project_details", 
      "project_dates", "project_budgets"); 
    foreach ($sections as $section) {
      include(CONFIG.$section.".php");
      $db_fields_all = array_merge($db_fields_all, $db_fields);
    }

    $db_fields = array(
      "dep_name" => $db_fields_all["project_department"],
      "client_name" => $db_fields_all["project_client_id"],
      "client_list" => array("text", "", "Joint Clients"),
      "bos_action_status_descr" => array("text", "", "BOS Action"),
      "bos_action_status_action_date" => array("text", "", "BOS Action - date"),
      "budget_status_color" => array(0, 0, "Budget status"),
    //  "budget_status_descr" => array(0, 0, "Budget status explanation"), 
      "schedule_status_color" => array(0, 0, "Schedule status"), 
    //  "schedule_status_descr" => array(0, 0, "Schedule status explanation"),
      "overall_status_color" => array(0, 0, "Scope/Overall status"), 
    //  "overall_status_descr" => array(0, 0, "Scope/Overall status explanation"),
    //  "scope_status_color" => array(0, 0, "Scope status"), 
    //  "scope_status_descr" =>
    //    array(0, 0, "Scope status explanation"),
      "current_activity_status_descr" => 
        array("text", "", "Current Activity"),
      "critical_activity_status_descr" => 
        array("text", "", "Critical Activity"),
      "risk_status_descr" =>
        array("text", "", "Project Risk"),
    );
    $db_fields_all = array_merge($db_fields_all, $db_fields);
    
    return $db_fields_all;
  } // get_db_fields_all()

  public static function get_fields() {
    $fields = array(
      "project_number", "project_name", "project_building",
      "dep_name", "project_type", "client_name", "client_list", 
      "project_client_department", "project_client_contact",
      "project_district", "project_delivery_method",
      /***/  "budget_status_color", // "budget_status_descr",
      /***/  "schedule_status_color", // "schedule_status_descr",
      /***/  "overall_status_color", // "overall_status_descr",
      /***///  "scope_status_color", // "scope_status_descr",
      "project_descr", "project_manager", "project_supervisor",
      "budget_approved_orig", "budget_approved_revised",
      "budget_available",
      "date_intake", "date_cao_memo", "date_handover",
      "date_design_start", "date_construct_start",
      "date_completion_orig", "date_completion_revised",
      "project_phase", "project_category", "project_percent_complete",
      "bos_action_status_descr", 
      "bos_action_status_action_date",
      "current_activity_status_descr",
      "critical_activity_status_descr","risk_status_descr", 
      "project_archived"
    ); 
    
    return $fields;
  } // get_fields

  public static function filter_funcs($orig_fields, $include_fields) {
    $fields = array();
    foreach ($orig_fields as $varname) {
      if (in_array($varname, $include_fields)) {
        $fields[] = $varname;
      }
    }  
    return $fields;
  }    
} // ExportFuncs  

?>
