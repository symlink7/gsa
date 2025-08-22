<?
include(TEMPLATES."general_wrap.php"); 
include(TEMPLATES."widgets/input_mask.html");
include(TEMPLATES."widgets/uniform.html");
include(CONFIG."users.php");
?>
<!-- ely's form submit script -->
<script type="text/javascript" src="platform/js/forms_submit.js"></script>
<script type="text/javascript" src="platform/js/users_form.js"></script>

        <div id="page-content-wrapper">
          <div id="page-content">
            <div class="container">

              <div id="page-title">
                <h2 class="pad10B"><strong>ADD A USER</strong></h2>
                <h5 class="pad10B">&nbsp;</h5>
              </div><!--Page Title-->

              <div class="panel">
                <div class="panel-body">
                  <h3 class="title-hero">NEW USER</h3>
                  <div class="example-box-wrapper">
                    <div class="content-box-wrapper">
                      
                      <div id="user-add-form-div" class="example-box-wrapper">

                        <div id="user-add-error-msg" class="alert alert-danger" style="display:none">
                        </div>
                        
                        <form id="user-add-form" class="form-horizontal bordered-row" />
				                  <input type="hidden" name="q" value="users/add" />  
                          <? include(VIEWS."users/form_fields.php") ?>
             
                          <div class="col-sm-9">
                            <input id="user-add-form-submit" name="user-add" class="btn btn-blue-alt float-right button" type="button" value="Submit">
                          </div><!-- col sm 6-->
                          
                          <div class="col-sm-9" id="user-add-please-wait" style="display:none">
                            <div class="alert alert-warning">
                              <h4 class="alert-title">Please wait!</h4>
                              <p>The form is being submitted, please wait...</p>
                            </div>
                          </div><!-- #user-add-please-wait -->

                        </form>
                      </div><!--example-box-wrapper, #user-add-form-div -->

                      <div id="user-add-form-done" class="example-box-wrapper" style="display:none">
                        <div class="alert alert-success">
                          <h4 class="alert-title">Success!</h4>
                          <p>User added successfully. Go to <a href="index.php?q=users/view_all" title="View Users">View Users</a>.</p>
                                                                                                                  </div>
                      </div><!-- #user-add-form-done -->

                    </div><!--content-box-wrapper-->
                  </div><!--example-box-wrapper-->
   
                </div><!-- panel body-->
              </div><!--panel-->

            </div><!-- container -->
          </div><!-- page-content -->
        </div><!-- page-content-wrapper -->
      </div><!-- page_wrapper -->
    </div><!-- sb-site -->
        
