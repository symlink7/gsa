<? include (TEMPLATES."general_wrap.php"); ?>
<script type="text/javascript" src="platform/js/forms_submit.js"></script>

        <div id="page-content-wrapper">
          <div id="page-content">
            <div class="container">
              <div id="page-title">
                <h2>PROJECTS</h2>
              </div>
              <div class="example-box-wrapper clear">

                <div class="row">
                  <div class="col-md-12">
                    <div class="example-box-wrapper">
                      <div class="list-group">
                        <a href="#" class="list-group-item active">My Projects</a>
                        <? foreach ($published as $project) { ?>
                          <a href="index.php?q=projects/details/<?=$project["project_id"]?>" class="list-group-item"><?=$project["project_name"].($project["project_building"] ? 
                            ' — <span class="text-transform-upr font-size-10">'.
                            $project["project_building"].'</span>' : ""
                          )?><span class="badge bg-<?=$project["status_color"]?>"><i class="glyph-icon icon-status-<?=$project["status_color"]?>"></i></span>
                            <span class="badge bg-gray font-black font-none"><?=($project["status_needs_update"] == "Y" ? "Needs Update" : ($project["status_approved"] == "N" ? "Pending" : "Approved"))?></span></a>
                       <? } ?>
                      </div><!-- .list-group -->
                    </div><!-- .example-box-wrapper -->
                  </div><!-- .col-md-12 -->
                </div><!-- .row -->
                
                <? if (is_arr_valid($drafts)) { ?>
                <div class="row">
                  <div class="col-md-12">
                    <div class="example-box-wrapper">
                      <div class="list-group">
                        <a href="#" class="list-group-item active">Project Drafts</a>
                        <? 
                        foreach ($drafts as $draft) { 
                          echo '
                          <a href="#" name="'.
                          ($draft["project_steps_completed"] + 1).'/'.
                          $draft["project_id"].
                          '" class="list-group-item continue-project-add">'.
                          $draft["project_name"].
                          ($draft["project_building"] ?
                            ' — <span class="text-transform-upr font-size-10">'.
                            $draft["project_building"].'</span>' : ""
                          ).'</a>
                          ';
                        } 
                        ?>
                      </div><!-- .list-group -->
                    </div><!-- .example-box-wrapper -->
                  </div><!-- .col-md-12 -->
                </div><!-- .row -->
                <? } /* end of is_arr_valid($drafts) */ ?>

              </div><!-- .example-box-wrapper -->

            </div><!-- .container-->
          </div><!-- .page-content -->
        </div><!-- .page-content-wrapper-->
        <form id="project-add-redirect" method="POST" action="index.php?q=projects/add_form">
          <input type="hidden" id="redirect-vars" name="vars" value="" />
        </form>
                                                                                          </div><!-- #project-add-form-done -->

