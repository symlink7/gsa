<?
function date_fields($date_type, $value = "", $req = false, $no_border = false) {
  include(CONFIG."project_dates.php");
  $varname = $var_pfx.$date_type;
  $descr = $db_fields[$varname][2];
  $value = ($value == "0000-00-00" ? "" : $value);
  $str = '
                          <div class="form-group"'.
                          ($no_border ? ' style="border-top:0px;"' : "").'>
                            <label class="col-sm-6 control-label"'.
                            ($req ? ' id="'.$varname.'_req"' : "").'>'.
                            $descr.
                            ($req ? ' <span style="color:red">*</span>' : "").'
                              <button class="btn btn-xs btn-round btn-gray tooltip-button" data-toggle="tooltip" data-placement="top" title="Descriptive note"><strong>?</strong></button>
                            </label>
                            <div class="col-sm-6">
                              <div class="input-prepend input-group">
                                <span class="add-on input-group-addon">
                                  <i class="glyph-icon icon-calendar"></i>
                                </span>
                                <input type="text" name="'.$varname.'"'.
                                ' id="'.$varname.'" class="bootstrap-datepicker'.
                                ' form-control" value="'.$value.'"'.
                                ' style="width:100px;"> 
                              </div><!-- input-group -->
                            </div><!-- col sm 6-->
                          </div><!-- form group -->
  ';
  return $str;
} 
