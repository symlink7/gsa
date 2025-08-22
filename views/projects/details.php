<? 
if ($printer_version) {
  include(VIEWS."projects/print_details.php");
  exit;
}  
require_once(VIEWS."projects/funcs.php");
require_once(VIEWS."projects/widgets_details.php");
include(CONFIG."projects.php");

include(TEMPLATES."general_wrap.php"); 
include(TEMPLATES."widgets/knobs.html");
include(TEMPLATES."widgets/tabs.html");
include(TEMPLATES."widgets/interactions.html");
include(TEMPLATES."widgets/dialog.html");
// include(TEMPLATES."widgets/rl.html");
?>
<script type="text/javascript" src="platform/js/forms_submit.js"></script>

        <div id="page-content-wrapper">
          <div id="page-content">
            <div class="container">

<? 
// print_r($tudo); 

$tudo["project_district_name"] = 
  (is_var_valid($tudo["project_district"]) ?
    $project_district_options[$tudo["project_district"]] : "");
extract($tudo);

$colors = ProjectFuncs::status_colors($statuses);
extract($colors);

?>
              <div class="row">
                <div class="col-md-9">
                  <div id="page-title">
                    <h5 class="pad10B">PROJECT DETAILS</h5>
                    <h2><strong><?=$project_name?></strong></h2>
                  </div><!-- page-title -->
                </div><!-- col-md-9 -->

                <div class="col-md-3">
                  <div class="content-box" style="background-color:#de6a1b; color:#ffffff; font-weight:bold; -webkit-border-radius: 12px 12px 12px 12px; border-radius: 12px 12px 12px 12px; height:96px; padding:25px 10px 10px 10px;">
                    <h3>
                      <span style="font-size:14px;">Estimated <br />Project<br /> Completion </span>
                      <div class="header-buttons-separator" style="background-color:#323232; font-size:28px; font-weight:bold; padding:28px 10px 10px 10px; -webkit-border-radius: 0px 12px 12px 0px; border-radius: 0px 12px 12px 0px;">
                        <?=$project_percent_complete?>%
                      </div><!-- header-buttons-sparator -->
                    </h3>
                  </div><!-- content-box -->
                </div><!-- col-med-3 -->
              </div><!-- row -->
              
              <div class="row">
                <div class="col-md-12">
                  <div class="box-line" style="height:4px;">
                  </div><!-- box-line -->
                </div><!-- col-med-12 -->
              </div><!-- row -->

              <div class="row mrg20B">
                <div class="col-md-5">
                  <i class="glyph-icon icon-status-light-blue icon-print" title=".icon-print"></i>
                  <a href="index.php?q=projects/details/<?=$project_id?>/printer" style="color:#323232">PRINT PROJECT</a>
                  &nbsp;
                  <i class="glyph-icon icon-status-light-blue icon-external-link-square" title=".icon-external-link-square"></i>
                  <a href="index.php?q=projects/export/<?=$project_id?>" style="color:#323232">EXPORT CSV</a>
                </div><!-- col-md-5 -->

                <div class="col-md-7">
                  <div class="float-right-box">
                    <? if ($can_edit) { ?>
                    <a href="index.php?q=projects/edit_form/<?=$project_id?>" class="btn btn-light-green font-bold pad5A pad10B pad20L pad20R">Edit Project</a>
                    <? } if ($can_update) { ?>
                    <a href="index.php?q=project_statuses/for_project/<?=$project_id?>" class="btn btn-light-green font-bold pad5A pad10B pad20L pad20R">Status Updates</a>
                    <? } if ($can_approve) { ?>
                    <a href="index.php?q=project_statuses/pending/<?=$project_id?>" class="btn btn-light-green font-bold pad5A pad10B pad20L pad20R">Approve Statuses</a>
                    <? } ?>
                  </div><!-- float-right-box -->
                <!-- wtf?! </div> content-box-->
                </div><!-- .col-md-7 -->
              </div><!-- .row -->
      
              <div class="row">
                <div class="col-md-6">
                  <div class="layout-box blue-border">
                    <h5>
                    <? 
                    echo ($project_building ? 
                      'Building #'.$project_building.
                      (is_arr_valid($building) ? 
                        "<br />". 
                        ProjectFuncs::building_info(
                          array_map('ucwords',
                            array_map('strtolower', $building)
                          ), "<br />") 
                        : "") 
                    : ""); // if $project_building
                    ?>
                    </h5>
                    <div class="row">
                      <div class="col-md-6">
                      
                        <?
                        $tudo["project_number_text"] = add_prefix(
                          add_tag($tudo["project_number"], "strong"), 
                          "Project #"
                        );
                        $tudo["department_name"] = add_prefix(
                          add_tag($tudo["department_name"], "strong"),
                          "Dept: "
                        );
                        $tudo["project_type"] = add_prefix(
                          add_tag($tudo["project_type"], "strong"), 
                          "Project type: "
                        );
                        $tudo["manager_name"] = add_prefix(
                          add_tag($manager[0]["user_first_name"]." ". 
                                  $manager[0]["user_last_name"], 
                                  "strong"
                          ), "Project Manager: "
                        );
                        $arr = ProjectFuncs::same_line_variables($tudo,
                          array("department_name", "project_number_text", 
                            add_tag("project_district_name", "strong"), 
                            "project_type", "manager_name"
                          )
                        );  
                        if (sizeof($arr) > 0) { ?>
                          <h7 class="pad10B">
                            <?=implode("<br />", $arr)?>
                          </h7>
                        <? } ?>
                      </div><!--col-md-6-->
                      
                      <div class="col-md-6">
                        <h7>
                          Stakeholder: 
                          <?
                          echo add_tag(
                            str_replace("; ", "<br />", $clients), "strong"
                          );
                          if (is_var_valid($tudo["project_client_contact"])) {
                            $tudo["project_client_contact"] =
                              "Client Contact: ".
                              add_tag($tudo["project_client_contact"], "strong");
                          }  
                          $arr = ProjectFuncs::same_line_variables($tudo, 
                            array("project_client_contact", 
                              add_tag("project_client_department", "strong")
                            )  
                          );  
                          if (sizeof($arr) > 0) { 
                            echo "<br />\n".
                                  implode("<br />", $arr);
                          } 
                          ?>
                        </h7>
                      </div><!-- col-md-6 -->
                    </div><!-- row -->
                  </div><!-- layout-box -->
                  
                  <div class="layout-box green-border">
                    <?
                    echo text_status_div("Current Activity",
                      $statuses["current_activity"],
                      '<i class="glyph-icon icon-status-light-blue icon-gears" title=".icon-gears"></i>'
                    );
                    ?>
                    <div class="width-100" style="padding-bottom:30px;">
                    </div>
                    <?
                    $statuses["critical_activity"]["status_descr"] =
                      options_to_ul(
                        ProjectFuncs::prepare_critical_activity(
                          $statuses["critical_activity"]["status_descr"]
                        ),
                        "project_statuses", 
                        "critical_activity_options"
                      );  
                    echo text_status_div("Critical Activity", 
                      $statuses["critical_activity"],
                      '<i class="glyph-icon icon-status-light-blue icon-exclamation-triangle" title=".icon-exclamation-triangle"></i>'
                    );
                    ?>
                    <div class="width-100" style="padding-bottom:30px;">
                    </div>
                    <?
                    echo text_status_div("BOS Action",
                      $statuses["bos_action"],
                      '<i class="glyph-icon icon-status-light-blue icon-legal" title=".icon-legal"></i>'
                    );
                    ?>
                  </div><!-- layout-box green-border -->
                  
                  <div class="layout-box grey-border">
                    <h5 class="pad10B"><i class="glyph-icon icon-status-light-blue icon-building" title=".icon-building"></i> Project Description</h5>
                    <p class="project-in-building">
                      <a data-toggle="collapse" data-target="#demo-2">
                        <?=$project_name?>
                      </a>
                    </p>
                    <div id="demo-2" class="collapse">
                      <? 
                      echo $project_descr;
                      ?>
                    </div><!-- #demo-2 -->
                  </div><!-- layout-box grey-border -->

                  <? if (is_arr_valid($related_projects)) { ?> 
                  <div class="layout-box blue-border">
                    <h5 class="pad10B">
                      <i class="glyph-icon icon-status-light-blue icon-check-square-o" title=".icon-check-square-o"></i>
                      Projects in Building #<?=$project_building?>
                    </h5>
                    <p class="project-in-building">
                    <? foreach ($related_projects as $arr) { ?>
                      <a href="index.php?q=projects/details/<?=$arr["project_id"]?>"><?=$arr["project_name"]?></a><br />
                    <? } ?>
                    </p>
                  </div><!-- layout-box blue-border -->
                  <? } ?>
                
                </div><!-- col-md-6 -->

                <!-- right column now -->
                <div class="col-md-6">
                  <div class="row">
                    <div class="col-md-6">
                      
                      <div class="layout-box light-blue-border">
                        <h6 class="pad10B">
                          <i class="glyph-icon icon-status-light-blue icon-print" title=".icon-print"></i>
                          Budget
                        </h6>
                        <? setlocale(LC_MONETARY, 'en_US'); ?>
                        <p class="date-budget">
                          Board Approved Original Budget:
                          <strong>
                            <?=format_budget($budget_approved_orig)?>
                          </strong>
                        </p>
                        <p class="date-budget">
                          Board Approved Revised Budget:
                          <strong>
                            <?=format_budget($budget_approved_revised)?>
                          </strong>
                        </p>
                        <p class="date-budget">
                          Available Budget:
                          <strong>
                            <?=format_budget($budget_available)?>
                          </strong>  
                        </p>
                      </div><!-- layout-box light-blue-border -->
                    </div><!-- .col-md-6 -->

                    <div class="col-md-6">
                      <div class="layout-box green-border">
                        <h6 class="pad10B">
                          <i class="glyph-icon icon-status-light-blue icon-print" title=".icon-print"></i>
                          Milestone Schedule
                        </h6>
                        <p class="date-budget">  
                          Intake Date:<br/>
                          <strong>
                            <?=format_date($date_intake, "TBD")?>
                          </strong>
                        </p>  
                        <p class="date-budget">  
                          CAO Memo:<br/>
                          <strong>
                            <?=format_date($date_cao_memo, "TBD")?>
                          </strong>
                        </p>  
                        <p class="date-budget">  
                          Hand-over Date:<br/>
                          <strong>
                            <?=format_date($date_handover, "TBD")?>
                          </strong>
                        </p>  

                        <p class="date-budget">  
                          Design Start Date:<br/>
                          <strong>
                            <?=format_date($date_design_start, "TBD")?>
                          </strong>
                        </p>  
                        <p class="date-budget">  
                          Construction Start Date:<br />
                          <strong>
                            <?=format_date($date_construct_start, "TBD")?>
                          </strong>  
                        </p>
                        <p class="date-budget">
                          Project Completion Date:<br />
                          <strong>
                            <?=format_date($date_completion_orig, "TBD")?>
                          </strong>
                        </p>
                        <p class="date-budget">
                          Revised Completion Date:<br />
                          <strong>
                            <?=format_date($date_completion_revised, "TBD")?>
                          </strong>
                        </p>
                        <? if ($project_delivery_method != "") { ?>
                        <p class="date-budget">  
                          Delivery Method:<br />
                          <strong>
                            <?=$delivery_method_options[$project_delivery_method]?>
                          </strong>
                        </p>  
                        <? } ?>
                        <p class="date-budget">
                          Project Phase:<br />
                          <strong>
                            <?=$project_phase?>
                          </strong>
                        </p>
                      </div><!-- layout-box green-border -->
                    </div><!-- .col-md-6 -->
                  </div><!-- end milestones budget row -->

                  <div class="layout-box purple-border">
                    <? 
                    echo text_status_div("Risk",
                      $statuses["risk"],
                      '<i class="glyph-icon icon-status-light-blue icon-flag" title=".icon-flag"></i>');
                    ?>
                  </div><!-- layout-box purple-border -->

                  <div class="layout-box-tab grey-border">
                    <ul id="myTab" class="nav clearfix nav-tabs">
                      <li class="active">
                        <a href="#overall" data-toggle="tab">Scope/Overall <span class="bs-badge bg-<?=$color_o?>"><i class="glyph-icon icon-status-<?=$color_o?>"></i></span></a>
                      </li>
                      <!--
                      <li class="active">
                        <a href="#scope" data-toggle="tab">Scope <span class="bs-badge bg-<?=$color_sco?>"><i class="glyph-icon icon-status-<?=$color_sco?>"></i></span></a>
                      </li>
                      -->
                      <li>
                        <a href="#budget" data-toggle="tab">Budget <span class="bs-badge bg-<?=$color_b?>"><i class="glyph-icon icon-status-<?=$color_b?>"></i></span></a>
                      </li>
                      <li>
                        <a href="#schedule" data-toggle="tab">Schedule <span class="bs-badge bg-<?=$color_sch?>"><i class="glyph-icon icon-status-<?=$color_sch?>"></i></span></a>
                      </li>
                    </ul><!-- #myTab -->

                    <div id="myTabContent" class="tab-content">
                      <?
                      $arr = array("overall", "budget", "schedule");
                      foreach ($arr as $status_name) {
                        $status_arr = $statuses[$status_name];
                        $active = ($status_name=="overall" ? " active" : "");
                      ?>
                      <div class="tab-pane fade in<?=$active?>" id="<?=$status_name?>">
                        <h5><?=$project_status_names[$status_name]?></h5>
                        <p class="last-updated">
<? if (ProjectFuncs::show_last_updated_time($status_arr)) { ?>
                          Last Updated:
<?=ProjectFuncs::format_datetime($status_arr["status_created_time"])?>
<? } ?>
                          <span class="bs-badge bg-<?=$status_arr["status_color"]?>">
                            <i class="glyph-icon icon-status-<?=$status_arr["status_color"]?>"></i>
                          </span>
                          <span class="badge bg-gray font-black font-none">
                            <?=ProjectFuncs::status_status($status_arr)?>
                          </span>
                        </p><!-- last-updated -->
                      </div><!-- .tab-pane -->
                      <? } ?>
                    </div><!-- myTabContent -->
                  </div><!-- .layout-box-tab -->


                  <? include(VIEWS."projects/delete.php"); ?>
                  <? include(VIEWS."projects/archive.php"); ?>
                </div><!-- col-md-6-->
                <? 
                
                require_once(VIEWS."projects/project_images.php");
                echo existing_images($images, $project_id);
                ?>
              </div><!-- end row of column left and column right -->

            </div><!-- container -->
          </div><!-- page-content -->
        </div><!-- page-content-wrapper -->
      </div><!-- page_wrapper -->
    </div><!-- sb-site -->
