<link rel="stylesheet" type="text/css" href="assets/themes/admin/layout-for-print.css">
<? 
require_once(VIEWS."projects/funcs.php");
require_once(VIEWS."projects/widgets_details.php");
include(CONFIG."projects.php");

/* NO GENERAL WRAP !!!!
// include(TEMPLATES."general_wrap.php"); 
*/
// include(TEMPLATES."widgets/knobs.html");
// include(TEMPLATES."widgets/tabs.html");
// include(TEMPLATES."widgets/interactions.html");
// include(TEMPLATES."widgets/dialog.html");
?>
    <div id="sb-site">
      <div id="page-wrapper">
        <div id="page-header">
         <h4 class="mrg25L">ALAMEDA COUNTY GSA PROJECT STATUS DASHBOARD</h4>
        </div><!-- #page-header -->
        
        <!-- <div id="page-content-wrapper"> -->
          <div id="page-content">
            <div class="container">

<? 
// print_r($tudo); 
$tudo["project_number_text"] = 
  (!$tudo["project_number"] ? "" : "Project #".$tudo["project_number"]);

$tudo["project_district_name"] = 
  (is_var_valid($tudo["project_district"]) ?
    $project_district_options[$tudo["project_district"]] : "");

$project_category_options = get_var("project_category_options", "project_details");
$tudo["project_category"] = 
  (is_var_valid($tudo["project_category"]) ? 
    "Category ".$project_category_options[$tudo["project_category"]] : "");

extract($tudo);

$colors = ProjectFuncs::status_colors($statuses);
extract($colors);

?>
              <div class="row">

                <div class="col-md-9">
                  <div id="page-title">
                    <h5 class="pad10B">PROJECT DETAILS</h5>
                    <h2><strong><?=$project_name?></strong></h2>
                    <p class="font-size-13 pad10B">
                    <? echo ($project_building ? 'Building #'.
                    $project_building.
                    (is_arr_valid($building) ? 
                    ": ". ProjectFuncs::building_info($building) : "") 
                    : ""); // if $project_building
                    ?>
                    </p>
                    <?
                    $arr = ProjectFuncs::same_line_variables($tudo,
                      array("department_name", "project_number_text", 
                            "project_district_name", "project_type")
                    );  
                    if (sizeof($arr) > 0) { ?>
                    <h6 class="pad10B"><?=implode(" | ", $arr)?></h6>
                    <? } ?>
                    <h6 class="pad10B">
                      Project Manager: 
                      <?=$manager[0]["user_first_name"]?>
                      <?=$manager[0]["user_last_name"]?>
                    </h6>  
                    <h6>Stakeholder: <?=$clients?></h6>
                    <? 
                    $arr = ProjectFuncs::same_line_variables($tudo, 
                      array("project_client_contact", 
                            "project_client_department")
                    );  
                    if (sizeof($arr) > 0) { ?>
                    <h6 class="font-gray pad10B"><?=implode(" | ", $arr)?></h6>
                    <? } else { ?>
                    <h6 class="font-gray pad10B"></h6>
                    <? } ?>
                    <?=(is_var_valid($project_category) ? '<h6 class="pad10B">'.
                        $project_category."</h6>\n" : "")?>
                  </div><!-- page-title -->
                </div><!--col-md-9 -->
              </div><!-- .row (print-only) -->

              <div class="panel">
                <div class="panel-body bg-white">
                  <div class="row">
                    <div class="col-md-7">
                      <h5 class="pad10B">Approver: <?=$project_supervisor_name?></h5>
                      <h5 class="pad10B">Project Manager: <?=$project_manager_name?></h5>
                      <? 
                      if (substr(trim($project_descr), 0, 1) == '<') {
                        echo $project_descr;
                      } else { ?>
                      <p class="pad20B"><?=$project_descr?></p>
                      <? } ?>
                    </div><!-- .col-md-7 -->

                    <div class="col-md-3">
                      <div class="example-box-wrapper">
                        <h5 class="pad10B">MILESTONE SCHEDULE</h5>
                        <p class="pad20B">

                          <span class="font-size-10">Intake Date:</span>
                          <?=format_date($date_intake, "TBD")?><br/>
                          <span class="font-size-10">CAO Memo:</span>
                          <?=format_date($date_cao_memo, "TBD")?><br/>
                          <span class="font-size-10">Hand-over Date:</span>
                          <?=format_date($date_handover, "TBD")?><br/>
                          
                          <span class="font-size-10">Design Start Date:</span>
                          <?=format_date($date_design_start, "TBD")?><br/>
                          
                          <span class="font-size-10">Construction Start Date:</span>
                          <?=format_date($date_construct_start, "TBD")?><br/>

                          <span class="font-size-10">Project Completion Date:</span>
                          <?=format_date($date_completion_orig, "TBD")?><br/>
                          
                          <span class="font-size-10">Revised Completion Date:</span>
                          <?=format_date($date_completion_revised, "TBD")?><br/>
                          
                          <? if ($project_delivery_method != "") { ?>
                          <span class="font-size-10">Delivery Method:</span>
                          <?=$delivery_method_options[$project_delivery_method]?><br />
                          <? } ?>
                          
                          <span class="font-size-10">Project Phase:</span>
                          <?=$project_phase?><br />

                        </p>
                      </div><!-- .example-box-wrapper -->
                    </div><!-- .col-md-3 -->

                    <div class="col-md-2">
                      <div class="example-box-wrapper">
                        <h5 class="pad10B">BUDGET</h5>
                        <p>
                          <? setlocale(LC_MONETARY, 'en_US'); ?>
                          <span class="font-size-10">Board Approved Original Budget:</span>
                          <?=format_budget($budget_approved_orig)?>
                          <br />
                          <span class="font-size-10">Board Approved Revised Budget:</span>
                          <?=format_budget($budget_approved_revised)?>
                          <br />
                          <span class="font-size-10">Available Budget:</span>
                          <?=format_budget($budget_available)?>
                        </p>
                      </div><!-- .example-box-wrapper -->
                    </div><!-- .col-md-2 -->
                  </div><!-- description/schedule/budget .row -->

                  <div class="row mrg10T">
                    <div class="col-md-7">
                      <h5>Estimated Project Completion:
                        <?=$project_percent_complete?>%
                      </h5>
                    </div><!-- col-md-2 -->
                  </div><!-- row -->
                
                </div><!-- panel-body bg-white -->

                    <div class="col-md-10">
                      <div class="example-box-wrapper">
                        <div class="row mrg10T"></div>
<? 
$arr = array("overall", /*"scope",*/ "budget", "schedule", 
             "risk", "current_activity", "critical_activity",
             "bos_action");
foreach ($arr as $status_name) { 
  $status_arr = $statuses[$status_name];
?>
                        <div class="status-printer panel-body bg-white">
                          <div class="row">
                            <h3 class="pad10B">
                              <?=$project_status_names[$status_name]?>
                            </h3>
                            <p>
                              <?=($status_arr["status_color"] ?
                                strtoupper($status_arr["status_color"]) : "")?>
                              <span class="badge bg-gray font-black font-none">
                                <?=ProjectFuncs::status_status($status_arr)?>
                              </span>
<? 
if (ProjectFuncs::show_last_updated_time($status_arr)) { ?>
                              <span class="font-gray-dark small"> 
                                Last Updated:
<?=ProjectFuncs::format_datetime($status_arr["status_created_time"])?>
                              </span>
<? } ?>
                            </p>
                            <? 
                            if ($status_name == "critical_activity") {
                              $status_arr["status_descr"] =
                                options_to_ul(
                                  ProjectFuncs::prepare_critical_activity(
                                    $status_arr["status_descr"]
                                  ),
                                  "project_statuses", 
                                  "critical_activity_options"
                                ); 
														}
                            if ($status_name == "bos_action") {
                              $status_arr["status_descr"] =
															  options_to_ul(
																  ProjectFuncs::prepare_critical_activity(
																	  $status_arr["status_descr"],
                           				  get_var("bos_action_options", "project_statuses")
																  ),
																  "project_statuses",
																  "bos_action_options"
															  );
                            }
                            if (!$status_arr["status_color"]) {
                              if ($status_arr["status_type"] == "bos_action" &&
                                is_var_valid($status_arr["status_action_date"])) { ?>
                            <p>When does this go to the board?  
                              <b><?=format_date($status_arr["status_action_date"], "", "Y-m-d");?></b>
                            </p>
                              <?
                              }
                              
                              if (substr(trim($status_arr["status_descr"]), 0, 1) 
                                  == '<') {
                                echo $status_arr["status_descr"];
                              } 
                              else { 
                            ?>   
                            <p><?=$status_arr["status_descr"]?></p>
                            <? 
                              } 
                            } // end if it's not a color-status
                            ?>
                          </div><!-- row -->  
                        </div><!-- status-printer panel-body -->  
<? } /* end of looping through statuses */ ?>
                      
                      </div><!-- .example-box-wrapper -->
                    </div><!-- .col-md-10 -->
                  </div><!-- progress & statuses row -->
                  
                  <div class="row mrg20T">
                  </div> 

              </div><!-- .panel -->

<? if (is_arr_valid($related_projects)) { ?>
              <div class="row">
                <div class="col-md-6">
                  <div class="example-box-wrapper">
                    <div class="list-group">
                      <a href="#" class="list-group-item active">Building #<?=$project_building?></a>
<? foreach ($related_projects as $arr) { ?>
                      <a href="index.php?q=projects/details/<?=$arr["project_id"]?>" class="list-group-item"><?=$arr["project_name"]?><span class="badge bg-<?=$arr["status_color"]?>"><i class="glyph-icon icon-status-<?=$arr["status_color"]?>"></i></span><span class="badge bg-gray font-black font-normal"><?=($arr["status_needs_update"] == "Y" ? "Needs Update" : ($arr["status_approved"] == "Y" ? "Approved" : "Pending"))?></span></a>
<? } /* end of looping through related projects */ ?>                      
                    </div><!-- list-group -->
                  </div><!-- example-box-wrapper -->
                </div><!-- col-md-6 -->
              </div><!-- same-building-number-projects .row -->
<? } /* end if there're related projects */ ?>

            </div><!-- container -->
          </div><!-- page-content -->
        <!-- </div> --><!-- page-content-wrapper -->
      </div><!-- page_wrapper -->
    </div><!-- sb-site -->

