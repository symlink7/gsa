<? 
include(TEMPLATES."general_wrap.php"); 
include(TEMPLATES."widgets/input_mask.html");
include(TEMPLATES."widgets/uniform.html");
?>
<!-- ely's form submit script -->
<script type="text/javascript" src="platform/js/forms_submit.js"></script>

        <div id="page-content-wrapper">
          <div id="page-content">
            <div class="container">

              <div id="page-title">
                <h2 class="pad10B"><strong>ADD A CLIENT</strong></h2>
                <h5 class="pad10B">&nbsp;</h5>
              </div><!--Page Title-->

              <div class="panel">
                <div class="panel-body">
                  <h3 class="title-hero">NEW CLIENT</h3>
                  <div class="example-box-wrapper">
                    <div class="content-box-wrapper">
                      
                      <div id="client-add-form-div" class="example-box-wrapper">

                        <div id="client-add-error-msg" class="alert alert-danger" style="display:none">
                        </div>
                        
                        <form id="client-add-form" class="form-horizontal bordered-row" />
				                  <input type="hidden" name="q" value="clients/add" />
                          <? include(VIEWS."clients/form_fields.php") ?> 
                          <div class="col-sm-9">
                            <input id="client-add-form-submit" name="client-add" class="btn btn-blue-alt float-right button" type="button" value="Submit">
                          </div><!-- col sm 6-->
                          
                          <div class="col-sm-9" id="client-add-please-wait" style="display:none">
                            <div class="alert alert-warning">
                              <h4 class="alert-title">Please wait!</h4>
                              <p>The form is being submitted, please wait...</p>
                            </div>
                          </div><!-- #client-add-please-wait -->

                        </form>
                      </div><!--example-box-wrapper, #client-add-form-div -->

                      <div id="client-add-form-done" class="example-box-wrapper" style="display:none">
                        <div class="alert alert-success">
                          <h4 class="alert-title">Success!</h4>
                          <p>Client added successfully. Go to <a href="index.php?q=clients/view_all" title="View Clients">View Clients</a>.</p>
                                                                                                                  </div>
                      </div><!-- #client-add-form-done -->

                    </div><!--content-box-wrapper-->
                  </div><!--example-box-wrapper-->
   
                </div><!-- panel body-->
              </div><!--panel-->

            </div><!-- container -->
          </div><!-- page-content -->
        </div><!-- page-content-wrapper -->
      </div><!-- page_wrapper -->
    </div><!-- sb-site -->
        
