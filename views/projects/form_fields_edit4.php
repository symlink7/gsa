<? include(CONFIG."project_details.php"); ?>                          
                          <? 
                          $project_phase_options[] = "Complete";
                          echo Form::row(
                            Form::label("project_phase", "Project phase", true),
                            Form::input_col(
                              Form::select_from_array("project_phase",
                                $project_phase_options, $project_phase,
                                "custom-select"
                              )  
                            )
                          );
                          
                          echo Form::row(
                            Form::label("project_category", "Categories I - VI", true),
                            Form::input_col(
                              Form::select_from_rel_array("project_category",
                                $project_category_options, $project_category,
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
                                <option value="'.$i.'"'.
                                ($project_percent_complete == $i ? " selected" : "").
                                '>'.$i.'%</option>';
                                }  
                                ?>
                              </select>
                            </div><!-- col sm 6-->
                          </div><!-- form group -->
                          
