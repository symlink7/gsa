<?
/* this is only used for overall/budget/scope/schedule */
function status_fields($status_type, $status_name, 
                       $status_name_short = "", $no_border = false) {
  include(CONFIG."project_statuses.php");
  $str = '
                          <div class="form-group"'.
                          ($no_border ? ' style="border-top:0px;"' : "").'>
                            <label class="col-sm-3 control-label" id="'.
                            $status_type.'_status_req">'.$status_name.
                            ' status <span style="color:red">*</span></label>
                            <div class="col-sm-6">
                              <select name="'.$status_type.'_status"
                               class="chosen-select">
                                <option value=""></option>
                                ';
                                
  if (is_arr_valid($status_options)) {
    foreach($status_options as $option => $value) {
      $str .= '
                                <option value="'.$option.'">'.
                                  htmlspecialchars($value).'
                                </option>';
    }
  }
  $str .= '
                              </select>
                            </div><!-- col sm 6-->
                          </div><!-- form group -->
  ';
  /*
                          <div class="form-group">
                            <label class="col-sm-3 control-label" id="'.
                            $status_type.'_status_descr_req">'.
                            (is_var_valid($status_name_short) ? 
                              $status_name_short : $status_name).
                            ' status explanation <span style="color:red">*</span></label>
                            <div class="col-sm-6">
                              <textarea name="'.$status_type.
                              '_status_descr" id="'.$status_type.
                              '_status_descr" class="ckeditor form-control"></textarea>
                            </div><!-- col sm 6-->
                          </div><!-- form group -->
  ';
  */
  return $str;
}  
