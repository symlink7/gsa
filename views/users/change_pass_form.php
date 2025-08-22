              <div class="panel">
                <div class="panel-body">
                  <h3 class="title-hero">Change password</h3>
                    <div class="example-box-wrapper">
                      <div class="content-box-wrapper">

                        <div id="change-pass-form-div" class="example-box-wrapper">
                        <div id="change-pass-error-msg" class="alert alert-danger" style="display:none">
                        </div>

                        <form id="change-pass-form" class="form-horizontal bordered-row">
                          <input type="hidden" name="q" value="users/change_pass" />
                          <input type="hidden" name="user_id" value="<?=(is_var_valid($tudo["user_id"]) ? $tudo["user_id"] : "")?>" />

                          <div class="form-group">
                            <label class="col-sm-3 control-label" id="new_password_req">Create new password
                              <span style="color:red">*</span>
                            </label>
                            <div class="col-sm-6">
                              <input type="password" name="new_password" id="new_password" required class="form-control" />
                              <div class="help-block">8-16 characters, starting with a letter or a number; can contain also . - _ # +</div>
                            </div><!-- col sm 6-->
                          </div><!-- form group -->
              
                          <div class="form-group">
                            <label class="col-sm-3 control-label" id="repeat_password_req">Repeat password
                              <span style="color:red">*</span>
                            </label>
                            <div class="col-sm-6">
                              <input type="password" name="repeat_password" id="repeat_password" required class="form-control" />
                            </div><!-- col sm 6-->
                          </div><!-- form group -->
             
                          <div class="col-sm-9">
                            <input id="change-pass-form-submit" name="change-pass" class="button btn btn-blue-alt float-right mrg20R" type="button" value="Save Changes">
                          </div><!-- col sm 6-->

                          <div class="col-sm-9" id="change-pass-please-wait" style="display:none">
                            <div class="alert alert-warning">
                              <h4 class="alert-title">Please wait!</h4>
                              <p>The form is being submitted, please wait...</p>
                            </div>
                          </div><!-- #user-edit-please-wait -->
                        </form>
                      </div><!--example-box-wrapper, #user-pass-form-div -->

                      <div id="change-pass-form-done" class="example-box-wrapper" style="display:none">
                        <div class="alert alert-success">
                          <h4 class="alert-title">Success!</h4>
                          <p>Password successfully changed.</p>
                        </div>
                      </div><!-- #user-edit-form-done -->

                    </div><!--content box border-->
                  </div><!--example box wrapper-->
                </div><!-- panel body-->
              </div><!--panel-->
