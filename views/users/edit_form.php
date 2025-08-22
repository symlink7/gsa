<?
include(TEMPLATES."general_wrap.php"); 
include(TEMPLATES."widgets/input_mask.html");
include(TEMPLATES."widgets/uniform.html");
include(TEMPLATES."widgets/interactions.html");
include(TEMPLATES."widgets/dialog.html");
include(CONFIG."users.php");
?>
<!-- ely's form submit script -->
<script type="text/javascript" src="platform/js/forms_submit.js"></script>
<script type="text/javascript" src="platform/js/users_form.js"></script>

        <div id="page-content-wrapper">
          <div id="page-content">
            <div class="container">

              <div id="page-title">
                <h2 class="pad10B"><strong>ACCOUNT DETAILS</strong></h2>
                <h5 class="pad10B">&nbsp;</h5>
              </div><!--Page Title-->

              <div class="panel">
                <div class="panel-body">
                  <h3 class="title-hero">EDIT USER DETAILS</h3>
                  <div class="example-box-wrapper">
                    <div class="content-box-wrapper">
                      
                      <div id="user-edit-form-div" class="example-box-wrapper">

                        <div id="user-edit-error-msg" class="alert alert-danger" style="display:none">
                        </div>
                        
                        <form id="user-delete-form" action="index.php" method="GET" class="form-horizontal bordered-row" />
                          <input type="hidden" name="q" value="users/delete/<?=(is_var_valid($tudo["user_id"]) ? $tudo["user_id"] : "")?>" />
                        </form>

                        <form id="user-edit-form" class="form-horizontal bordered-row" />
				                  <input type="hidden" name="q" value="users/edit" />  
                          <input type="hidden" name="user_id" value="<?=(is_var_valid($tudo["user_id"]) ? $tudo["user_id"] : "")?>" />
                          <? 
                          $edit = 1; 
                          if (is_arr_valid($tudo)) {
                            extract($tudo);
                          }  
                          include(VIEWS."users/form_fields.php"); 
                          ?>
             
                          <div class="col-sm-9">
                            <input type="button" id="user-delete-form-submit" name="user-delete" class="confirm-button btn btn-danger float-right" value="Delete User" data-toggle="modal" data-target="#user-delete-confirm-dialog" />
                            <input id="user-edit-form-submit" name="user-edit" class="button btn btn-blue-alt float-right mrg20R" type="button" value="Save Changes">
                          </div><!-- col sm 6-->
                          
                          <div class="col-sm-9" id="user-edit-please-wait" style="display:none">
                            <div class="alert alert-warning">
                              <h4 class="alert-title">Please wait!</h4>
                              <p>The form is being submitted, please wait...</p>
                            </div>
                          </div><!-- #user-edit-please-wait -->
              
                        </form>
                      </div><!--example-box-wrapper, #user-edit-form-div -->

                      <div id="user-delete-confirm-dialog" class="confirm-dialog modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                              <h4 class="modal-title">Confirm Delete</h4>
                            </div>
                            <div class="modal-body">
                              <p>Are you sure you want to delete this user account?</p>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                              <button type="button" name="user-delete" class="confirm-yes btn btn-primary">Yes</button>
                            </div>
                          </div>
                        </div>
                      </div><!-- user-delete-confirm-dialog -->

                      <div id="user-edit-form-done" class="example-box-wrapper" style="display:none">
                        <div class="alert alert-success">
                          <h4 class="alert-title">Success!</h4>
                          <? if ($tudo["user_id"] == $_SESSION["user_id"]) { ?>
                          <p>Your account details edited successfully.</p>
                          <? } else { ?>
                          <p>User account details edited successfully. Go to <a href="index.php?q=users/view_all" title="View Users">View Users</a>.</p>
                          <? } ?>
                        </div><!-- .alert -->
                      </div><!-- #user-edit-form-done -->

                    </div><!--content-box-wrapper-->
                  </div><!--example-box-wrapper-->
   
                </div><!-- panel body-->
              </div><!--panel-->

              <? include(VIEWS."users/change_pass_form.php"); ?>

            </div><!-- container -->
          </div><!-- page-content -->
        </div><!-- page-content-wrapper -->
      </div><!-- page_wrapper -->
    </div><!-- sb-site -->
