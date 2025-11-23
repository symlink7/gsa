<?
class Form {

  public static function row($label, $input_col, $border = true) {
    $str = '
      <div class="form-group"'.
      ($border ? "" : ' style="border-top:0px;"').">
        $label
        $input_col
      </div><!-- form group -->
    ";
    return $str;
  }

  public static function label($varname, $descr, $req = false) {
    $str = '
      <label class="col-sm-3 control-label"'.
      ($req ? ' id="'.$varname.'_req"' : "").">".
      $descr.
      ($req ? ' <span style="color:red">*</span>' : "").'
      </label>
  ';
    return $str;
  }

  public static function input_col($form_field) {
    $str = '
      <div class="col-sm-6">
        '.$form_field.'
      </div><!-- col-sm-6-->
    ';
    return $str;
  }
  
  public static function textarea($varname, $value = "", 
                    $extra_attr = "", $help_text = "") {
    $str = '
      <textarea name="'.$varname.'" id="'.$varname.
      '" class="form-control" '.$extra_attr.'>'.
      htmlspecialchars($value).'</textarea>
    ';
    return $str;
  } // textarea()

  public static function wysiwyg($varname, $value = "",
                   $extra_attr = "", $help_text = "") {
    $str = '
      <textarea name="'.$varname.'" id="'.$varname.
      '" class="ckeditor" '.$extra_attr.'>'.
      htmlspecialchars($value).'</textarea>
    ';
    return $str;
  } // textarea()


  public static function textbox($varname, $value = "", 
                   $extra_attr = "", $help_text = "") {
    $str = '
      <input type="text" name="'.$varname.'" id="'.$varname.
      '" value="'.htmlspecialchars($value).'" class="form-control" '.
      $extra_attr.'/>
      '.($help_text != "" ? '<div class="help-block">'.$help_text.'</div>' : "");
    return $str;
  } // textbox()

  public static function budget_box($varname, $value = "") {
    $str = '
      <div class="input-group">
        <span class="input-group-addon">$</span>
        <input type="text" data-inputmask="&apos;mask&apos;:&apos;9{3,12}&apos;" class="input-mask form-control" name="'.$varname.'" id="'.$varname.
        '" value="'.htmlspecialchars($value).'" />
      </div><!-- .input-group -->
    ';
    return $str;
  }

  public static function select_from_rel_array($varname, $options, $val = "",
                                 $class = "chosen-select") {
    $str = '
      <select name="'.$varname.'" class="'.$class.'">
        <option value=""></option>
        ';
    if (is_arr_valid($options)) {
      foreach($options as $option => $value) {
        $str .= '
        <option value="'.htmlspecialchars($option).'"'.
          ($val != "" && $option == $val ? " selected" : "").
        '>'.htmlspecialchars($value).'
        </option>';
      }
    }
    $str .= '
      </select>
    ';
    return $str;
  } // select_from_rel_array()    
  
  public static function select_from_array($varname, $options, $val = "",
                                 $class = "chosen-select") {
    $str = '
      <select name="'.$varname.'" class="'.$class.'">
        <option value=""></option>
        ';
    if (is_arr_valid($options)) {
      foreach($options as $value) {
        $str .= '
        <option value="'.htmlspecialchars($value).'"'.
          ($val != "" && $value == $val ? " selected" : "").
        '>'.htmlspecialchars($value).'
        </option>';
      }
    }
    $str .= '
      </select>
    ';
    return $str;
  } // select_from_array()    

  public static function select_from_id_name_array($varname, $options, $val = "",
                                     $class = "chosen-select") {
    $str = '
      <select name="'.$varname.'" class="'.$class.'">
        <option value=""></option>
        ';
    if (is_arr_valid($options)) {
      foreach($options as $option) {
        $str .= '
        <option value="'.htmlspecialchars($option["id"]).'"'.
          ($val != "" && $option["id"] == $val ? " selected" : "").
        '>'.htmlspecialchars($option["name"]).'
        </option>';
      }
    }
    $str .= '
      </select>
    ';
    return $str;
  } // select_from_id_name_array()    

  public static function checkboxes($varname, $options, $vals = array()) {
    $str = "";
    if (is_arr_valid($options)) {
      foreach($options as $option => $value) {
        $str .= '
      <div class="checkbox">  
        <label>
          <input type="checkbox" name="'.$varname.'[]" value="'.
            htmlspecialchars($option).'"'.
          (is_arr_valid($vals) && in_array($option, $vals) ? 
            " checked" : "").
          '> '.($value).'
        </label>
      </div><!-- .checkbox -->
      ';
      }
    }
    return $str;
  } // checkboxes()   

  public static function date_field($varname, $value = "") {
    $value = ($value == "0000-00-00" ? "" : $value);
    $str = '
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
    ';
    return $str;
  } // date_field 

} // Form class

?>
