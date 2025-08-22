                          <div class="form-group">
                            <label class="col-sm-3 control-label" id="client_name_req">
                              Client Name
                              <span style="color:red">*</span>
                            </label>
                            <div class="col-sm-6">
                              <input type="text" class="form-control" name="client_name" id="client_name" value="<?=($edit && is_var_valid($client_name) ? $client_name : "")?>" />
                              <div class="help-block">
                                i.e. Social Services Agency
                              </div>
                            </div><!-- col sm 6-->
                          </div><!-- form group -->
                          
                          <div class="form-group">
                            <label class="col-sm-3 control-label">
                              Client Code 
                            </label>
                            <div class="col-sm-6">
                              <input type="text" class="form-control" name="client_code" id="client_code" value="<?=($edit && is_var_valid($client_code) ? $client_code : "")?>" />
                              <div class="help-block">i.e. SSA</div>
                            </div><!-- col sm 6-->
                          </div><!-- form group -->
