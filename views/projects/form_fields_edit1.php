<?

                          echo Form::row(
                            Form::label("project_name", "Project Name", true),
                            Form::input_col(
                              Form::textbox("project_name", $project_name)
                            ), 
                            false 
                          );
                          
                          echo Form::row(
                            Form::label("project_building", "Building Number"),
                            Form::input_col(
                              Form::select_from_id_name_array(
                                "project_building", 
                                ProjectFuncs::prepare_buildings_arr($buildings),
                                $project_building
                              )
                            )
                          );  
                            
                          echo Form::row(
                            Form::label("project_number", "Project Number"),
                            Form::input_col(
                              Form::textbox(
                                "project_number", 
                                $project_number,
                                'maxlength="16" '
                              )
                            )
                          );
                          
                          echo Form::row(
                            Form::label("project_department", 
                              "Project Department", true),
                            Form::input_col(
                              Form::select_from_id_name_array(
                                "project_department", $departments,
                                $project_department
                              )
                            )
                          );
                          sort($project_type_options);
                          $gbo_types = get_var(
                            "gbo_project_type_options",
                            "project_gbo"
                          );
                          echo Form::row(
                            Form::label("project_type",
                              "Project Type", true),
                            Form::input_col(
                              Form::select_from_array(
                                "project_type", 
                                $project_type_options,
                                $project_type, 
                                "chosen-select", // default option
                                // array $extras
                                array(
                                  "special_options" => $gbo_types,
                                  "option_class" => "gbo"
                                )
                              )
                            )  
                          );
                          ?>
                          <? 
                          $hidden = ' style="display:none"';
                          ?>
                          <div id="gbo-applies" class="form-group gbo-field"<?=$hidden?>>
                            <?
                            $project_gbo_applies_options = 
                              get_var("project_gbo_applies_options", "project_gbo");

                            echo Form::label(
                              "project_gbo_applies", 
                              "Do any sections of Green Building Ordinance apply?",
                              true
                            ).
                            Form::input_col(Form::select_from_rel_array(
                              "project_gbo_applies", 
                              $project_gbo_applies_options,
                              $project_gbo_applies
                            ));
                            ?>
                          </div><!-- form group -->
              
                          <div id="gbo-seeking-waiver" class="form-group gbo-field"<?=$hidden?>>
                            <?
                            $project_seeking_waiver_options =
                              get_var("project_seeking_waiver_options", "project_gbo");

                            echo Form::label(
                              "project_seeking_waiver",
                              "Is project seeking a waiver?",
                              true
                            ).
                            Form::input_col(Form::select_from_rel_array(
                              "project_seeking_waiver", 
                              $project_seeking_waiver_options,
                              $project_seeking_waiver
                            ));
                            ?>
                          </div><!-- form group -->
                          
                          <div id="gbo-cat" class="form-group gbo-field"<?=$hidden?>>
                            <?
                            $project_gbo_cat_options = 
                              get_var("project_gbo_cat_options", "project_gbo");

                              echo Form::label(
                                "project_gbo_cat", 
                                "Identify Project Category", 
                                true
                              ).
                              Form::input_col(Form::select_from_rel_array(
                                "project_gbo_cat",
                                $project_gbo_cat_options,
                                $project_gbo_cat
                              ));
                            ?>
                          </div><!-- form group -->

                          <div class="form-group">
                            <?=Form::label("project_clients", 
                              "Add Joint Clients")?>
                            <div class="col-sm-6">
                              <select name="project_clients_arr[]" multiple 
                                data-placeholder="Click to see available options."
                                class="chosen-select">
                                <?
                                if (is_arr_valid($clients)) {
                                  foreach($clients as $client) {
                                    echo '
                                <option value="'.$client["id"].'"'.
                                (in_array($client["id"], $clients_for_project) ?
                                  " selected" : "").
                                '>'.
                                  htmlspecialchars($client["name"]).'
                                </option>';
                                  }
                                }
                                ?>
                              </select>
                              <div class="help-block">If the client is not listed, <a href="mailto:">contact admin</a> to add client..</div>
                            </div><!-- col sm 6-->
                          </div><!-- form group -->

                          <?
                          echo Form::row(
                            Form::label("project_client_id",
                              "Primary County Agency", true),
                            Form::input_col(
                              Form::select_from_id_name_array(
                                "project_client_id", $clients, 
                                $project_client_id
                              )
                            )
                          );

                          echo Form::row(
                            Form::label("project_client_department", "Client Department"),
                            Form::input_col(
                              Form::textbox("project_client_department", $project_client_department)
                            ), 
                            true
                          );

                          echo Form::row(
                            Form::label("project_client_contact", "Client Contact"),
                            Form::input_col(
                              Form::textbox("project_client_contact", $project_client_contact)
                            ), 
                            true
                          );

                          echo Form::row(
                            Form::label("project_district", "Select District", true),
                            Form::input_col(Form::select_from_rel_array("project_district", $project_district_options, $project_district, "custom-select"))
                          );  

                          echo Form::row(
                            Form::label("project_delivery_method", "Delivery Method"),
                            Form::input_col(Form::select_from_rel_array("project_delivery_method", $delivery_method_options, $project_delivery_method))
                          );

                          echo Form::row(
                            Form::label("project_descr", "Project Description", true),
                            Form::input_col(
                              Form::wysiwyg("project_descr", $project_descr)
                              // Form::textarea("project_descr", $project_descr)
                            ), 
                            true
                          );
                          
                          ?>
                          <div class="form-group">
                            <?=Form::label("project_manager", "Project Manager", true)?>
                            <div class="col-sm-6">
                              <select name="project_manager" 
                                class="chosen-select">
                                <option value=""></option>
                                <?
                                if (is_arr_valid($managers)) {
                                  foreach($managers as $manager) {
                                    echo '
                                <option value="'.$manager["id"].'"'.
                                  ($project_manager == $manager["id"] ?
                                    " selected" : "").
                                  '>'.
                                  htmlspecialchars($manager["name"]).'
                                </option>';
                                  }
                                }
                                ?>
                              </select>
                            </div><!-- col sm 6-->
                          </div><!-- form group -->

                          <div class="form-group">
                            <?=Form::label("project_supervisor", "Approver", true)?>
                            <div class="col-sm-6">
                              <select name="project_supervisor" 
                                class="chosen-select">
                                <option value=""></option>
                                <?
                                if (is_arr_valid($supervisors)) {
                                  foreach($supervisors as $supervisor) {
                                    echo '
                                <option value="'.$supervisor["id"].'"'.
                                  ($project_supervisor == $supervisor["id"] ?
                                    " selected" : "").
                                  '>'.
                                  htmlspecialchars($supervisor["name"]).'
                                </option>';
                                  }
                                }
                                ?>
                              </select>
                            </div><!-- col sm 6-->
                          </div><!-- form group -->
<? require_once(VIEWS."projects/form_fields_edit4.php"); ?>

