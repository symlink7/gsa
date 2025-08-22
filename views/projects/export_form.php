<? 
include(TEMPLATES."general_wrap.php"); 
// include(TEMPLATES."widgets/chosen.html");
include(TEMPLATES."widgets/multi_select.html");

// needed for the list of fields to include
require_once(VIEWS."projects/export_funcs.php");
$db_fields_all = ExportFuncs::get_db_fields_all();
$fields = ExportFuncs::get_fields();

?>

        <div id="page-content-wrapper">
          <div id="page-content">
            <div class="container">

              <div id="page-title">
                <h2 class="pad10B"><strong>EXPORT PROJECTS</strong></h2>
                <h5 class="pad10B">&nbsp;</h5>
              </div><!--Page Title-->

              <div class="panel">
                <div class="panel-body">
                  <!-- <h3 class="title-hero"></h3>-->
                  <div class="example-box-wrapper">
                    <?
                    include(CONFIG."projects.php");
                    ?>
                    <div class="content-box">
                      <div class="content-box-wrapper">
                      <div class="example-box-wrapper">
                        <form method="post" action="index.php?q=projects/export_all" class="form-horizontal bordered-row" />
                          <div class="form-group">
                            <label class="col-sm-3 control-label">
                              Filter by Department(s)
                            </label>
                            <div class="col-sm-6">
                              <select name="project_department_arr[]" multiple 
                                data-placeholder="Click to see available options."
                                class="multi-select" style="resize:vertical">
                                <option value="all">All Departments</option>
                                <?
                                if (is_arr_valid($departments)) {
                                  foreach($departments as $dep) {
                                    echo '
                                <option value="'.$dep["id"].'">'.
                                  htmlspecialchars($dep["name"]).'
                                </option>';
                                  }
                                }
                                ?>
                              </select>
                            </div><!-- col sm 6-->
                          </div><!-- form group -->

                          <div class="form-group">
                            <label class="col-sm-3 control-label">Include field(s)
                            </label>
                            <div class="col-sm-6">
                              <select name="include_fields[]" multiple 
                                data-placeholder="Click to see available options."
                                class="multi-select">
                                <option value="all">All Fields</option>
                                <?
                                foreach($fields as $varname) {
                                  echo '
                                <option value="'.$varname.'">'.
                                  htmlspecialchars($db_fields_all[$varname][2]).'
                                </option>';
                                }
                                ?>
                              </select>
                            </div><!-- col sm 6-->
                          </div><!-- form group -->
                          
                          <div class="form-group">
                            <div class="col-sm-3">
                            </div>
                            <div class="col-sm-6">
                              <input id="projects-export-form-submit" name="projects-export" class="btn btn-blue-alt button" type="submit" value="EXPORT">
                            </div><!-- col sm 6-->
                          </div><!-- form group -->  
                        
                        </form>

                      </div><!--example-box-wrapper, #project-add-form-div -->
                      
                      </div><!-- #project-add-form-done -->

                    </div><!--example-box-wrapper-->
                    </div><!--content-box-wrapper-->
                  </div><!--content-box-->
   
                </div><!-- panel body-->
              </div><!--panel-->

            </div><!-- container -->
          </div><!-- page-content -->
        </div><!-- page-content-wrapper -->
      </div><!-- page_wrapper -->
    </div><!-- sb-site -->

