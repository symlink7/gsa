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
                <h2 class="pad10B"><strong>CLIENT DETAILS</strong></h2>
                <h5 class="pad10B">&nbsp;</h5>
              </div><!--Page Title-->

              <div class="panel">
                <div class="panel-body">
                  <h3 class="title-hero">EDIT CLIENT</h3>
                  <div class="example-box-wrapper">
                    <div class="content-box-wrapper">
                      
                      <div id="client-edit-form-div" class="example-box-wrapper">

                        <div id="client-edit-error-msg" class="alert alert-danger" style="display:none">
                        </div>
                        <form id="client-delete-form" action="index.php" method="GET" class="form-horizontal bordered-row" />
                          <input type="hidden" name="q" value="clients/delete/<?=(is_var_valid($tudo["client_id"]) ? $tudo["client_id"] : "")?>" />
                        </form>

                        <form id="client-edit-form" class="form-horizontal bordered-row" />
				                  <input type="hidden" name="q" value="clients/edit" />
                          <input type="hidden" name="client_id" value="<?=(is_var_valid($tudo["client_id"]) ? $tudo["client_id"] : "")?>" />
                          <?
                          $edit = 1;
                          if (is_arr_valid($tudo)) {
                            extract($tudo);
                          }
                          include(VIEWS."clients/form_fields.php");
                          ?>
                          
                          <div class="col-sm-9">
                            <input id="client-delete-form-submit" name="client-delete" class="confirm-button btn btn-danger float-right" type="button" value="Delete Client" data-toggle="modal" data-target="#client-delete-confirm-dialog" />
                            <input id="client-edit-form-submit" name="client-edit" class="button btn btn-blue-alt float-right mrg20R" type="button" value="Save Changes">                            
                          </div><!-- col sm 6-->
                          
                          <div class="col-sm-9" id="client-edit-please-wait" style="display:none">
                            <div class="alert alert-warning">
                              <h4 class="alert-title">Please wait!</h4>
                              <p>The form is being submitted, please wait...</p>
                            </div>
                          </div><!-- #client-edit-please-wait -->

                        </form>
                      </div><!--example-box-wrapper, #client-edit-form-div -->

                      <div id="client-delete-confirm-dialog" class="confirm-dialog modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                              <h4 class="modal-title">Confirm Delete</h4>
                            </div>
                            <div class="modal-body">
                              <p>Are you sure you want to delete this client?</p>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                              <button type="button" name="client-delete" class="confirm-yes btn btn-primary">Yes</button>
                            </div>
                          </div>
                        </div>
                      </div><!-- client-delete-confirm-dialog -->

                      <div id="client-edit-form-done" class="example-box-wrapper" style="display:none">
                        <div class="alert alert-success">
                          <h4 class="alert-title">Success!</h4>
                          <p>Client edited successfully. Go to <a href="index.php?q=clients/view_all" title="View Clients">View Clients</a>.</p>
                                                                                                                  </div>
                      </div><!-- #client-edit-form-done -->

                    </div><!--content-box-wrapper-->
                  </div><!--example-box-wrapper-->
   
                </div><!-- panel body-->
              </div><!--panel-->

            </div><!-- container -->
          </div><!-- page-content -->
        </div><!-- page-content-wrapper -->
      </div><!-- page_wrapper -->
    </div><!-- sb-site -->
        
