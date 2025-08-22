<? 
include(TEMPLATES."general_wrap.php"); 
// include(TEMPLATES."widgets/chosen.html");
include(TEMPLATES."widgets/multi_select.html");

?>

        <div id="page-content-wrapper">
          <div id="page-content">
            <div class="container">

              <div id="page-title">
                <h2 class="pad10B"><strong><?=($version == "public" ? 
                "MAKE PROJECTS PUBLIC" : "SHOW PROJECTS ON INTRANET")?></strong></h2>
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
                        <form method="post" action="index.php?q=projects/bulk_api_status/<?=$version?>" class="form-horizontal bordered-row" />
                          <div class="form-group">
                            <label class="col-sm-3 control-label">
                              Choose Projects that should be 
                              <?=($version == "public" ?
                              "Public" : "shown on Intranet")?>
                            </label>
                            <div class="col-sm-6">
                              <select name="public_projects[]" multiple 
                                data-placeholder="Click to see available options."
                                class="multi-select" style="resize:vertical">
                                <?
                                if (is_arr_valid($all_projects)) {
                                  foreach($all_projects as $project_id => $project) {
                                    echo '
                                <option value="'.$project_id.'"'.
                                  (in_array($project_id, $public_projects) ?
                                  " selected" : "").
                                '>'.
                                  htmlspecialchars(($project["project_number"] ? 
                                    $project["project_number"].", " : "").
                                    $project["project_name"]).'
                                </option>';
                                  }
                                }
                                ?>
                              </select>
                            </div><!-- col sm 6-->
                          </div><!-- form group -->

                          <div class="form-group">
                            <div class="col-sm-3">
                            </div>
                            <div class="col-sm-6">
                              <input id="projects-export-form-submit" name="projects-export" class="btn btn-blue-alt button" type="submit" value="SAVE">
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

