                          <?
                          require_once(CLASSES."form.php");
                          echo Form::row(
                            Form::label("user_first_name", "First Name", true),
                            Form::input_col(
                              Form::textbox("user_first_name", 
                                ($edit && 
                                  is_var_valid($user_first_name) ? 
                                    $user_first_name : "")
                              )
                            )
                          );

                          echo Form::row(
                            Form::label("user_last_name", "Last Name", true),
                            Form::input_col(
                              Form::textbox("user_last_name", 
                                ($edit && 
                                  is_var_valid($user_last_name) ? 
                                    $user_last_name : "")
                              )
                            )
                          );

                          echo Form::row(
                            Form::label("user_email", "Email", true),
                            Form::input_col(
                              Form::textbox("user_email", 
                                ($edit && 
                                  is_var_valid($user_email) ? 
                                    $user_email : "")
                              )
                            )
                          );

                          ?>
                          
                          <div class="form-group">
                            <label class="col-sm-3 control-label" id="user_phone_req">Phone <span style="color:red">*</span></label>
                            <div class="col-sm-6">
                              <input type="text" name="user_phone" id="user_phone" class="input-mask form-control" data-inputmask="&apos;mask&apos;:&apos;(999) 999-9999&apos;" value="<?=($edit && is_var_valid($user_phone) ? $user_phone : "")?>" />
                              <div class="help-block">(999) 999-9999</div>
                            </div><!--col sm 6-->
                          </div><!-- form group -->
                
                          <div class="form-group">
                            <label class="col-sm-3 control-label" id="user_role_req">Select Role <span style="color:red">*</span></label>
                            <div class="col-sm-6">
                              <?
                              if ($_SESSION["user_info"]["user_role"] == "A") {
                              ?>
                              <select name="user_role" class="custom-select">
                                <option value="">Please choose...</option>
                                <?
                                foreach ($user_role_options as $option => $value) {
                                ?>
                                <option value="<?=$option?>"<?=($edit && is_var_valid($user_role) && $user_role==$option ? " selected" : "")?>><?=$value?></option>
                                <? } ?>
                              </select>
                              <? } else { /* if it's not admin */ ?>
                              <input type="hidden" name="user_role" value="<?=$user_role?>" />
                              <span><?=$user_role_options[$user_role]?></span>
                              <? } ?>
                            </div><!-- col sm 6-->
                          </div><!-- form group -->

                          <? 
                          $hidden = ' style="display:none"';
                          if ($edit && $user_role == "S") {
                            $hidden = "";
                          }
                          ?>
                          <div id="user_project_type" class="form-group"<?=$hidden?>>
                            <?
                            $project_type_options = 
                              get_var("project_type_options", "projects");
                            sort($project_type_options); 

                            echo Form::label("user_project_type", "Project Type").
                              Form::input_col(
                                ($_SESSION["user_info"]["user_role"] == "A" ?
                                  Form::select_from_array(
                                    "user_project_type", 
                                    $project_type_options,
                                    $user_project_type
                                  )
                                  :
                                "<span><?=$user_project_type?></span>"
                                )
                              );  
                            ?>
                          </div><!-- form group -->
