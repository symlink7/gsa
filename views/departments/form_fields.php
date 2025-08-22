                          <div class="form-group">
                            <label class="col-sm-3 control-label" id="dep_name_req">
                              Department Name
                              <span style="color:red">*</span>
                            </label>
                            <div class="col-sm-6">
                              <input type="text" class="form-control" name="dep_name" id="dep_name" value="<?=($edit && is_var_valid($dep_name) ? $dep_name : "")?>" />
                            </div><!-- col sm 6-->
                          </div><!-- form group -->
