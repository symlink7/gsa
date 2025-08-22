<? 
require_once(CLASSES."form.php");
include(TEMPLATES."general_wrap.php"); 
include(TEMPLATES."widgets/input_mask.html");
?>
<!-- ely's form submit script -->
<script type="text/javascript" src="platform/js/forms_submit.js"></script>


        <div id="page-content-wrapper">
          <div id="page-content">
            <div class="container">

              <div id="page-title">
                <h2 class="pad10B"><strong>Cities & Zips for Projects</strong></h2>
                <h5 class="pad10B">&nbsp;</h5>
              </div><!--Page Title-->

              <div class="panel">
                <div class="panel-body">
                  <h3 class="title-hero">Add/Update City and Zip</h3>
                  <div class="example-box-wrapper">
                    <div class="content-box-wrapper">
                      <div id="city_zips-form-div" class="example-box-wrapper">
                        <div id="city_zips-error-msg" class="alert alert-danger" style="display:none">
                        </div>
                        <form id="city_zips-form" class="form-horizontal bordered-row" />
                        <input type="hidden" name="q" value="city_zips/add" />
                        <input type="hidden" name="refresh" value="" />
                        <div class="form-group">
                          <label class="col-sm-3 control-label" id="project_id_req">
                            Choose Project <span style="color:red">*</span>
                          </label>
                          <div class="col-sm-6">
                            <select id="project-id-select" name="project_id" class="select">
                              <option value=""></option>
                              <?
                              $str1 = $str2 = $str3 = $str4 = "";
                              if (is_arr_valid($all_projects)) {
                                foreach($all_projects as $project_id => $project) {
                                  $str = '
                              <option value="'.$project_id.'"'.
                                (in_array($project_id, $public_projects) ?
                                ' class="public"' : "").
                              '>'.
                                  htmlspecialchars(($project["project_number"] ?
                                    $project["project_number"].", " : "").
                                    $project["project_name"]).
                                  "</option>\n";
                                  if (in_array($project_id, $public_projects)) {
                                    if (array_key_exists($project_id, $city_zips))
                                      $str2 .= $str;
                                    else 
                                      $str1 .= $str;
                                  }
                                  else {
                                    if (array_key_exists($project_id, $city_zips))
                                      $str4 .= $str;
                                    else 
                                      $str3 .= $str;
                                  }
                                } // end of looping through all_projects
                              } // end of checking of all_projects exists  
                              if (is_var_valid($str1)) {
                                echo '<optgroup label="Public Projects - Add City/Zip">'.
                                  $str1;
                              }
                              if (is_var_valid($str2)) {
                                echo '<optgroup label="Public Projects - Edit City/Zip">'.
                                  $str2;
                              }
                              if (is_var_valid($str3)) {
                                echo '<optgroup label="Non-public Projects - Add City/Zip">'.$str3;
                              }
                              if (is_var_valid($str4)) {
                                echo '<optgroup label="Non-public Projects - Edit City/Zip">'.$str4;
                              }
                              ?>
                            </select>
                          </div><!-- col sm 6-->
                        </div><!-- form group -->
                        <?  
                        echo Form::row(
                          Form::label("project_city", "City"),
                          Form::input_col(Form::textbox("project_city"))
                        );
                        echo Form::row(
                          Form::label("project_zip", "Zip"),
                          Form::input_col(Form::textbox("project_zip"))
                        );
                        ?>
                        <div class="col-sm-9">
                          <input id="city_zips-form-submit" name="city_zips" class="btn btn-blue-alt float-right button" type="button" value="Submit">
                        </div><!-- col sm 6-->
                          
                        <div class="col-sm-9" id="city_zips-please-wait" style="display:none">
                          <div class="alert alert-warning">
                            <h4 class="alert-title">Please wait!</h4>
                            <p>The form is being submitted, please wait...</p>
                          </div>
                        </div><!-- #city_zips-please-wait -->

                        </form>
                      </div><!--example-box-wrapper, #city_zips-form-div -->
                    </div><!--content-box-wrapper-->
                  </div><!--example-box-wrapper-->
                </div><!-- panel body-->
              </div><!--panel-->

            </div><!-- container -->
          </div><!-- page-content -->
        </div><!-- page-content-wrapper -->
      </div><!-- page_wrapper -->
    </div><!-- sb-site -->
  <script>
  var city_zips = <? echo json_encode($city_zips); ?>;
  jQuery(document).ready(function() {
    jQuery("#project-id-select").change(function() {
      if (city_zips[this.value]) {
        jQuery("input[name=q]").val("city_zips/edit");
        jQuery("input[name=project_city]").val(city_zips[this.value].project_city);
        jQuery("input[name=project_zip]").val(city_zips[this.value].project_zip);
        jQuery("input[name=refresh]").val("Successfully edited city/zip.");
//        alert(city_zips[this.value].project_city);
//        alert(city_zips[this.value].project_zip);
      }
      else {
        jQuery("input[name=q]").val("city_zips/add");
        jQuery("input[name=project_city]").val("");
        jQuery("input[name=project_zip]").val("");
        jQuery("input[name=refresh]").val("Successfully added city/zip.");
  //      alert(this.value);
      }  
    });
  });  
  </script>
