<? 
function panel_body($statuses, $buttons, $project_id) {
  include(CONFIG."project_statuses.php");
  $str = '
                <div class="panel-body">
                  <div class="content-box-wrapper">
                    <!-- this part repeats for all statuses -->
                    '; 
  // eval('$status_types = array('.$db_fields["status_type"][1].");");
  
  $status_types = array(
    "current_activity", "critical_activity",
    "bos_action", "risk", 
    "overall", /*"scope",*/ "budget", "schedule",
  );

  foreach($status_types as $status_type) {
    if (!array_key_exists($status_type, $statuses)) {
      continue;
    }  
    $status_info = $statuses[$status_type];

    if ($status_type == "critical_activity") {
      extract(split_critical_activity(
        $status_info["status_descr"], 
        $critical_activity_options, 
        true)
      );
      $status_info["status_descr"] =
        options_to_ul(
          ($ca_old ? $ca_old : implode("||", $ca_checked)),
          // $status_info["status_descr"],
          "project_statuses", 
          "critical_activity_options"
      );
    } 
    if ($status_type == "bos_action") {
      extract(split_critical_activity(
        $status_info["status_descr"],
        $bos_action_options,
        true)
      );
      $status_info["status_descr"] =
        options_to_ul(
          ($ca_old ? $ca_old : implode("||", $ca_checked)),
          // $status_info["status_descr"],
          "project_statuses",
          "bos_action_options"
      );
    }
    $status_name = strtoupper($project_status_names[$status_type]);
    $str .= '
                    <h3 class="content-box-header clearfix font-size-14 pad0A mrg0B">
                      '.$status_name.'
                      '.($status_info["status_color"] ? "Status" : "");
    if ($status_info["status_color"]) { 
      $str .= '
                      <span class="bs-label bg-'.
                      $status_info["status_color"].'">'.
                      strtoupper($status_info["status_color"]).'</span>
                      ';
    }
    $str .= '
                      <span class="btn-group btn-group-sm float-right" data-toggle="buttons">';
                      if ($buttons["can_approve"]) {
    $str .= '         
                        <a href="q=project_statuses/approve/'.$status_info["status_id"].'" class="btn btn-primary display-block approve-button">Approve</a>';
                      }
                      if ($buttons["can_update"]) {
    $str .= '                  
                        <a href="#" link="index.php?q=project_statuses/update_form/'.$status_info["status_id"].'" class="btn btn-primary" data-toggle="modal" data-target="#modalUpdate">UPDATE</a>';
                      }
                      else if ($buttons["can_edit"]) {
    $str .= '
                        <a href="#" link="index.php?q=project_statuses/edit_form/'.$status_info["status_id"].'" class="btn btn-primary" data-toggle="modal" data-target="#modalUpdate">Edit</a>';
                      }
    $str .= '                  
                      </span>
                    </h3>
                    <p>
                      '.(!$status_info["status_color"] ?
                      $status_info["status_descr"] : "");
    if (ProjectFuncs::show_last_updated_time($status_info)) { 
      $str .= '
                      <span class="font-gray-dark pad10B font-size-10">
                        Last Updated:
                        '.ProjectFuncs::format_datetime(
                          $status_info["status_created_time"]).'
                      </span>
                      ';
    }
    $str .= '
                    </p>
                    <div class="divider mrg20A"></div>
                    ';
  } /* end of looping through status_types */
  $str .= '
                    <!-- end of part that repeats for all statuses -->
                    <!-- BULK-APPROVE -->
                    '.($buttons["can_approve"] && sizeof($statuses) > 1 ?
                    '<p align="center"><a href="#" link="q=project_statuses/bulk_approve/'.$project_id.'" class="btn btn-primary bulk-approve-button display-block" data-toggle="modal" data-target="#bulk-approve-confirm-dialog" name="'.$project_id.'">APPROVE ALL</a></p>' : "").'
                  
                  </div><!-- .content-box-wrapper" -->
                </div><!-- .panel-body -->  
  ';
  return $str;
}

