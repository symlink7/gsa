                          
                          <div class="form-group" style="border-top:0px;">
                            <label class="col-sm-3 control-label" id="project_name_req">Project Name <span style="color:red">*</span></label>
                            <div class="col-sm-6">
                              <input type="text" name="project_name" id="project_name" class="form-control" />
                            </div><!-- col sm 6-->
                          </div><!-- form group -->

                          <?
                          echo Form::row(
                            Form::label("project_building", "Building Number"),
                            Form::input_col(
                              Form::select_from_id_name_array(
                                "project_building", $buildings 
                              )
                            )
                          );  

                          echo Form::row(
                            Form::label("project_number", "Project Number"),
                            Form::input_col(Form::textbox("project_number"))
                          );

                          echo Form::row(
                            Form::label("project_department", 
                              "Project Department", true),
                            Form::input_col(
                              Form::select_from_id_name_array(
                                "project_department", $departments
                              )
                            )
                          );
                          sort($project_type_options);
                          echo Form::row(
                            Form::label("project_type",
                              "Project Type", true),
                            Form::input_col(
                              Form::select_from_array(
                                "project_type", $project_type_options
                              )
                            )  
                          );

                          ?>
                          
                          <div class="form-group">
                            <label class="col-sm-3 control-label">Choose client(s)</label>
                            <div class="col-sm-6">
                              <select name="project_clients_arr[]" multiple 
                                data-placeholder="Click to see available options."
                                class="chosen-select">
                                <?
                                if (is_arr_valid($clients)) {
                                  foreach($clients as $client) {
                                    echo '
                                <option value="'.$client["id"].'">'.
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
                                "project_client_id", $clients
                              )
                            )
                          );
                          ?>

                          <div class="form-group">
                            <label class="col-sm-3 control-label">Client Department</label>
                            <div class="col-sm-6">
                              <input type="text" name="project_client_department" id="project_client_department" class="form-control" />
                            </div><!-- col sm 6-->
                          </div><!-- form group -->

                          <div class="form-group">
                            <label class="col-sm-3 control-label">Client Contact</label>
                            <div class="col-sm-6">
                              <input type="text" name="project_client_contact" id="project_client_contact" class="form-control" />
                            </div><!-- col sm 6-->
                          </div><!-- form group -->

                          <?
                          echo Form::row(
                            Form::label("project_district", "Select District", true),
                            Form::input_col(
                              Form::select_from_rel_array(
                                "project_district", $project_district_options, 
                                "", "custom-select"
                              )
                            )
                          );
  
                          echo Form::row(
                            Form::label("project_delivery_method", "Delivery Method"),
                            Form::input_col(
                              Form::select_from_rel_array(
                                "project_delivery_method", $delivery_method_options
                              )
                            )
                          );

                          echo Form::row(
                            Form::label("project_descr", "Project Description", true),
                            Form::input_col(
                              Form::wysiwyg("project_descr")
                            ),
                            true
                          );
                          ?>

                          <div class="form-group">
                            <label class="col-sm-3 control-label" id="project_manager_req">Project Manager <span style="color:red">*</span></label>
                            <div class="col-sm-6">
                              <select name="project_manager" 
                                class="chosen-select">
                                <option value=""></option>
                                <?
                                if (is_arr_valid($managers)) {
                                  foreach($managers as $manager) {
                                    echo '
                                <option value="'.$manager["id"].'">'.
                                  htmlspecialchars($manager["name"]).'
                                </option>';
                                  }
                                }
                                ?>
                              </select>
                            </div><!-- col sm 6-->
                          </div><!-- form group -->

                          <div class="form-group">
                            <label class="col-sm-3 control-label" id="project_supervisor_req">Approver <span style="color:red">*</span></label>
                            <div class="col-sm-6">
                              <select name="project_supervisor" 
                                class="chosen-select">
                                <option value=""></option>
                                <?
                                if (is_arr_valid($supervisors)) {
                                  foreach($supervisors as $supervisor) {
                                    echo '
                                <option value="'.$supervisor["id"].'"'.
                                  ($_SESSION["user_info"]["user_role"] == "S" &&
                                   $_SESSION["user_id"] == $supervisor["id"] ?
                                    " selected" : ""
                                  ).
                                  '>'.
                                  htmlspecialchars($supervisor["name"]).'
                                </option>';
                                  }
                                }
                                ?>
                              </select>
                            </div><!-- col sm 6-->
                          </div><!-- form group -->

