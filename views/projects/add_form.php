<? 
include(TEMPLATES."general_wrap.php"); 
include(TEMPLATES."widgets/chosen.html");
include(TEMPLATES."widgets/input_mask.html");
include(TEMPLATES."widgets/uniform.html");
include(TEMPLATES."widgets/textarea.html");
include(TEMPLATES."widgets/multi_select.html");
include(TEMPLATES."widgets/ckeditor.html");
?>
<!-- ely's form submit script -->
<script type="text/javascript" src="platform/js/forms_submit.js"></script>

        <div id="page-content-wrapper">
          <div id="page-content">
            <div class="container">

              <div id="page-title">
                <h2 class="pad10B"><strong>ADD A PROJECT</strong></h2>
                <h5 class="pad10B">&nbsp;</h5>
              </div><!--Page Title-->

              <div class="panel">
                <div class="panel-body">
                  <h3 class="title-hero">NEW PROJECT</h3>
                  <div class="example-box-wrapper">
                    <?
                    include(VIEWS."projects/add_form_wizard.php"); 
                    
                    include(CONFIG."projects.php");
                    ?>
                    <div class="content-box">
                      <h3 class="content-box-header bg-default">
                        <?=$step_titles[$step]?>
                      </h3> 
                      <div class="content-box-wrapper">
                      <div id="project-add-form-div" class="example-box-wrapper">

                        <div id="project-add-error-msg" class="alert alert-danger" style="display:none">
                        </div>
                        
                        <form id="project-add-form" class="form-horizontal bordered-row" />
                          <input type="hidden" name="q" value="projects/add" />
                          <input type="hidden" name="step" value="<?=$step?>" />
                          <?
                          require_once(CLASSES."form.php");

                          if ($step > 1) {
                            require_once(VIEWS."projects/status_fields.php");
                          }  
                          if (is_file(VIEWS."projects/form_fields{$step}.php")) {
                            include(VIEWS."projects/form_fields{$step}.php");
                          }
                          ?>
             
                          <div class="col-sm-9">
                            <input id="project-add-form-submit" name="project-add" class="btn btn-blue-alt float-right button" type="button" value="<?=($step < 4 ? "NEXT" : "SUBMIT")?>">
                          </div><!-- col sm 6-->
                          
                          <div class="col-sm-9" id="project-add-please-wait" style="display:none">
                            <div class="alert alert-warning">
                              <h4 class="alert-title">Please wait!</h4>
                              <p>The form is being submitted, please wait...</p>
                            </div>
                          </div><!-- #project-add-please-wait -->

                        </form>
                      </div><!--example-box-wrapper, #project-add-form-div -->
                      
                      <form id="project-add-redirect" method="POST" action="index.php?q=projects/add_form">
                        <input type="hidden" id="redirect-vars" name="vars" value="" />
                      </form>
                      </div><!-- #project-add-form-done -->

                    </div><!--example-box-wrapper-->
                    </div><!--content-box-wrapper-->
                  </div><!--content-box-->
   
                </div><!-- panel body-->
              </div><!--panel-->

            </div><!-- container -->
          </div><!-- page-content -->
        </div><!-- page-content-wrapper -->
      </div><!-- page_wrapper -->
    </div><!-- sb-site -->
        
