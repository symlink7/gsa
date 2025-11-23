<?
require_once(CLASSES."form.php");
function modal_error($error_msg) {
  $str = '
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Error!</h4>
      </div><!-- .modal-header-->

      <div class="modal-body">
        <h3>'.$error_msg.'</h3>
      </div><!-- .modal-body-->
      
      <div class="modal-footer clearfix">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div><!-- .modal-footer-->
  ';
  return $str;
} // modal_error()

function modal_update_content($project_info, $status_info, $edit = false) {
  include(CONFIG."project_statuses.php");
  $status_type = $status_info["status_type"];
  $status_name = $project_status_names[$status_type];

  if ($status_type == "critical_activity") {
    extract(split_critical_activity(
      $status_info["status_descr"], $critical_activity_options)
    );
  }
  else if ($status_type == "bos_action") {
    extract(split_critical_activity(
      $status_info["status_descr"], $bos_action_options)
    );
  }  
  $str .= '
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">'.$project_info["project_name"].
          ($project_info["project_number"] ? 
            ", Project #".$project_info["project_number"] : ""
          ).'
        </h4>
      </div><!-- .modal-header-->
      
      <div class="modal-body">
        <form id="update-status-form">
        <input type="hidden" name="q" value="project_statuses/'.
        ($edit ? 'edit' : 'update').'/'.
          $status_info["status_id"].'" />
        <h3 class="content-box-header clearfix font-size-14 pad0A mrg0B pad20B">
          '.($edit ? "Edit" : "Update")." ".strtoupper($status_name).
          ($status_info["status_color"] != "" ? " Status" : "").'
        </h3>
        <p>&nbsp;</p>
        <div id="update-status-error-msg" class="alert alert-danger" style="display:none">
        </div>
        <div id="update-status-success-msg" class="alert alert-success" style="display:none"></div>
        ';
  if ($status_info["status_color"] != "") {
    $str .= '
        <div class="form-group pad20B">
          '.Form::label(
            $status_type.'_status', // $varname
            $status_name.
            ($status_info["status_color"] != "" ? " Status" : ""), // $descr
            (in_array($status_type."_status", $update_req_fields[$status_type]) ?
              true : false
            ) // $req
          )
          .Form::input_col(
            Form::select_from_rel_array(
              $status_type.'_status', // $varname
              $status_options, // $options
              $status_info["status_color"] # $val
            )
          ).'
        </div><!-- .form-group -->
    ';
  } // end of status has color

  // add the date picket field for BOS Action
  if ($status_type == "bos_action") {
    $str .= '
        <div class="form-group clear">
      '.
      Form::label("status_action_date",
        "When does this go to the board?",
        (in_array("status_action_date", 
          $update_req_fields[$status_type]) ? true : false
        ) // $req
      ).
      Form::input_col(
        Form::date_field("status_action_date", $status_action_date)
      ).'
        </div><!-- .form-group -->
    ';
  }

  $str .= '
        <div class="form-group clear">
          '.
    (!$status_info["status_color"] ?
      Form::label(
        $status_type.'_status_descr', // $varname
        $status_name.
        ($status_info["status_color"] != "" ? " status explanation" : ""),
        (in_array($status_type."_status_descr", 
          $update_req_fields[$status_type]) ? true : false
        ) // $req
      ) : 
    "").
    Form::input_col(
      (!$status_info["status_color"] ?
        ($status_type == "critical_activity" ?
          Form::checkboxes(
            "critical_activity",
            $critical_activity_options,
            $ca_checked
          ).
          '
          <input type="text" name="critical_activity_other" value="'.
          ($ca_other ? htmlentities($ca_other) : "").'" />
          '
          : // if it's not critical activity 
          ($status_type == "bos_action" ?
            Form::checkboxes(
              "bos_action",
              $bos_action_options,
              $ca_checked
            ).
            '
            <input type="text" name="bos_action_other" value="'.
              ($ca_other ? htmlentities($ca_other) : "").'" />
            '
            : // if it's not bos_action
            Form::wysiwyg(
              $status_type.'_status_descr', // $varname
              $status_info["status_descr"]
            ).
            "<script>CKEDITOR.replace('".$status_type.'_status_descr'."');
            </script>"
          ) // end of bos_action vs other statuses
        )
        : ""
      ). // end of if it's descr-only status field
      ($edit ? "" : ' 
          <div class="checkbox'.
            ($status_type == "critical_activity" || $status_type == "bos_action" ? 
              " after-checkboxes" : "").'">
            <label>
              <input type="checkbox" name="notify_approver" value="1">
              Please notify approver
            </label>
          </div>'
      ) // end of if !$edit
    ). // end of input_col
    '
        </div><!--form group-->
        
        <p>&nbsp;</p>
        </form>
      </div><!-- .modal-body-->
        
      <div class="modal-footer clear mrg20T">
        <div id="update-status-please-wait" style="display:none">Submitting, please wait...</div>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-info button" name="update-status" id="update-status-form-submit">'.($edit ? "EDIT STATUS" : "SUBMIT FOR APPROVAL").'</button>
      </div><!-- modal-footer-->
';
  return $str;
} // modal_update

function modal_update($edit = false) {
  $str = '<div class="modal fade gsa-modal" id="modalUpdate" tabindex="-1" role="dialog" aria-labelledby="modalUpdateLabel" aria-hidden="true">
  ';
  $str2 = '
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">'.($edit ? "Edit" : "Update").' Status</h4>
      </div><!-- .modal-header-->

      <div class="modal-body">
        <h3 class="content-box-header clearfix font-size-14 pad0A mrg0B">
          Loading, please wait...
        </h3>
      </div><!-- .modal-body-->

      <div class="modal-footer clearfix">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div><!-- .modal-footer-->
      
    </div><!-- .modal-content-->
  </div><!-- .modal-dialogue-->
</div><!-- .modal-->';
  $str .= $str2.'
<div id="modalUpdate-orig" style="display:none">'.$str2;
  return $str;
} // modal_update

// if ul = true, remove other if it exists and create
// Other: $ca_other
function split_critical_activity($ca, 
          $critical_activity_options, $ul = false) {
    $ca_checked = array();
    // we need $ca_other for the checkbox version
    // for the ul version we need everything in ca_checked
    $ca_other = "";
    $remove_other = 0;

    // exit early if ca is empty
    if (!is_var_valid($ca)) {
      return (compact("ca_checked", "ca_other"));
    }

    // if it's the new type of c_a and more than one option
    if (preg_match("/\|\|/", $ca)) {
      $arr = array();
      $ca_checked = explode("||", $ca);
      foreach ($ca_checked as $val) {
        if (!is_var_valid(trim($val))) {
          continue;
        }  
        if (!$ul) { // for the checkboxes
          if (!array_key_exists($val, $critical_activity_options)) { 
            $ca_other = strip_tags($val);
          }
        } 
        else { // for the ul list
          if (array_key_exists($val, $critical_activity_options)) {
            $arr[] = $val;
          }
          else { // must be the other option
            $arr[] = "Other: ".strip_tags($val);
            $remove_other = 1;
          }
          $ca_checked = $arr;
        }  // end of with other for the ul list
      }  
    }
    // no || can mean only one option was checked
    else if (array_key_exists($ca, $critical_activity_options)) {
      $ca_checked[] = $ca;
    }
    else { // it's other without a checked-other or the old c_a
      if ($ul) {
        $ca_checked[] = "Other: ".strip_tags($ca);
      }
      else {
        $ca_other = strip_tags($ca);
      }  
    }
    if (!$ul) {
      if (is_var_valid($ca_other) && !in_array("other", $ca_checked)) {
        $ca_checked[] = "other";
      }
      //else if (!is_var_valid($ca_other) && in_array("other", $ca_checked)) {
      //  $ca_checked = remove_element_by_value("other", $ca_checked);
      //}
    }
    else if ($remove_other > 0) {
      $ca_checked = remove_element_by_value("other", $ca_checked);
    }  
    return (compact("ca_checked", "ca_other"));
}
