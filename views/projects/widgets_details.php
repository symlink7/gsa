<?
function text_status_div($status_name, $status_arr, $icon) {
  $str = '
    <h6 class="pad10B">
      '.$icon.' '.strtoupper($status_name).'
    </h6>
    '.(ProjectFuncs::show_last_updated_time($status_arr) ? 
    '<p class="last-updated">Last Updated: '. 
      ProjectFuncs::format_datetime($status_arr["status_created_time"])."
    </p>\n" : "").
    ($status_arr["status_type"] == "bos_action" && 
      is_var_valid($status_arr["status_action_date"]) ?
    '<p>When does this go to the board?
      <b>'.
        format_date($status_arr["status_action_date"], "", "Y-m-d").
      "</b>
     </p>" : "").
    (substr(trim($status_arr["status_descr"]), 0, 1) == '<' ? 
      $status_arr["status_descr"] :
        '<p class="pad20B">'.$status_arr["status_descr"].'</p>'
    );
  return $str;
}

function black_header_status_box($status_name, $status_arr) {
  $str = '
                    <div class="col-md-6">
                      <div class="content-box">
                        <h3 class="content-box-header bg-black">
                          '.strtoupper($status_name).'
                        </h3>
                        <div class="content-box-wrapper">
  '.
  (ProjectFuncs::show_last_updated_time($status_arr) ? 
  '
                          <h6 class="font-gray-dark pad10B">Last Updated: '. 
    ProjectFuncs::format_datetime($status_arr["status_created_time"]) 
    : "").'
                          </h6>
                          '.
                          (substr(trim($status_arr["status_descr"]), 0, 1) 
                            == '<' ? $status_arr["status_descr"] : 
                          '<p class="pad20B">'.$status_arr["status_descr"].'</p>').'
                        </div><!-- .content-box-wrapper -->
                      </div><!-- .content-box -->
                    </div><!-- .col-md-6 -->
  ';
  return $str;
}

function black_header_box($name, $descr) {
  $str = '
                    <div class="col-md-6">
                      <div class="content-box">
                        <h3 class="content-box-header bg-black">
                          '.strtoupper($name).'
                        </h3>
                        <div class="content-box-wrapper">
                          '.(substr(trim($descr), 0, 1) == '<' ? $descr :
                          '<p class="pad20B">'.$descr.'</p>').'
                        </div><!-- .content-box-wrapper -->
                      </div><!-- .content-box -->
                    </div><!-- .col-md-6 -->
  ';
  return $str;
}
?>
