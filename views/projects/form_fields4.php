<? 
include(CONFIG."project_details.php"); 
include(CONFIG."project_statuses.php");
?>                          
                          <input type="hidden" name="project_id" value="<?=$project_id?>" />
                          <?
                          echo status_fields("overall", "Scope/Overall project", 
                                             "Scope/Overall", true);
                          
                          echo Form::row(
                            Form::label("project_phase", "Project phase", true),
                            Form::input_col(
                              Form::select_from_array("project_phase",
                                $project_phase_options, "",
                                "custom-select"
                              )  
                            )
                          );

                          echo Form::row(
                            Form::label("project_category", "Categories I - VI"),
                            Form::input_col(
                              Form::select_from_rel_array("project_category",
                                $project_category_options, "",
                                "custom-select"
                              )
                            )
                          );

                          ?>

                          <div class="form-group">
                            <?=Form::label("project_percent_complete", 
                                           "Percent complete", true)?>
                            <div class="col-sm-6">
                              <select name="project_percent_complete"
                                class="chosen-select">
                                <option value=""></option>
                                <?
                                for ($i = 0; $i <= 100; $i+=5) {
                                  echo '
                                <option value="'.$i.'">'.$i.'%</option>
                                  ';
                                }  
                                ?>
                              </select>
                            </div><!-- col sm 6-->
                          </div><!-- form group -->    
                          
                          <?
                          // echo status_fields("scope", "Scope");
                          
                          echo Form::row(
                            Form::label("bos_action_status_descr", 
                                        "BOS Action", true),
                            Form::input_col(
                              // Form::wysiwyg("bos_action_status_descr")
                              Form::checkboxes("bos_action",
                                $bos_action_options).
                              '<input type="textbox" name="bos_action[]" />'
                            )
                          );                         

                          echo Form::row(
                            Form::label("current_activity_status_descr", 
                                        "Current Activity"),
                            Form::input_col(
                              Form::wysiwyg("current_activity_status_descr")
                            )
                          ); 
                          
                          echo Form::row(
                            Form::label("critical_activity_status_descr", 
                                        "Critical Activity"),
                            Form::input_col(
                              // Form::wysiwyg("critical_activity_status_descr")
                              Form::checkboxes("critical_activity", 
                                $critical_activity_options).
                              '<input type="textbox" name="critical_activity[]" />' 
                            )
                          );  
                          
                          echo Form::row(
                            Form::label("risk_status_descr", 
                                        "Project Risk", true),
                            Form::input_col(
                              Form::wysiwyg("risk_status_descr")
                            )
                          );
                          ?>
