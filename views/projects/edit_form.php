<? 
require_once(VIEWS."projects/funcs.php");
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
<? 
extract($tudo); 
?>
        <div id="page-content-wrapper">
          <div id="page-content">
            <div class="container">

              <div id="page-title" tabindex="1">
                <h2 class="pad10B"><strong>EDIT PROJECT</strong></h2>
                <h5 class="pad10B">&nbsp;</h5>
              </div><!--Page Title-->

              <div class="panel">
                <div class="panel-body">
                  <h3 class="title-hero"><?=$project_name?></h3>
                  <div class="example-box-wrapper">
                    <?
                    include(VIEWS."projects/edit_form_wizard.php"); 
                    
                    include(CONFIG."projects.php");
                    ?>
                    <div class="content-box">
                      <h3 class="content-box-header bg-default">
                        <?=$step_titles[$step]?>
                      </h3> 
                      <div class="content-box-wrapper">
                      <div id="project-edit-form-div" class="example-box-wrapper">

                        <div id="project-edit-error-msg" class="alert alert-danger" style="display:none">
                        </div>
                        
                        <form id="project-edit-form" class="form-horizontal bordered-row" />
                          <input type="hidden" name="q" value="projects/edit" />
                          <input type="hidden" name="project_id" value="<?=$project_id?>" />
                          <input type="hidden" name="step" value="<?=$step?>" />
                          <?
                          require_once(CLASSES."form.php");

                          if (is_file(VIEWS."projects/form_fields_edit{$step}.php")) {
                            include(VIEWS."projects/form_fields_edit{$step}.php");
                          }
                          ?>
             
                          <div class="col-sm-9">
                            <input id="project-edit-form-submit" name="project-edit" class="btn btn-blue-alt float-right button" type="button" value="SAVE">
                          </div><!-- col sm 6-->
                          
                          <div class="col-sm-9" id="project-edit-please-wait" style="display:none">
                            <div class="alert alert-warning">
                              <h4 class="alert-title">Please wait!</h4>
                              <p>The form is being submitted, please wait...</p>
                            </div>
                          </div><!-- #project-edit-please-wait -->

                        </form>
                      </div><!--example-box-wrapper, #project-edit-form-div -->

                      <div id="project-edit-form-done" tabindex="1" class="example-box-wrapper" style="display:none">
                        <div class="alert alert-success">
                          <h4 class="alert-title">Success!</h4>
                          <p>Project has been edited successfully. Go to view <a href="index.php?q=projects/details/<?=$project_id?>" title="Project Details">project details</a>.</p>
                        </div><!-- .alert -->
                      </div><!-- #project-edit-form-done -->

                      </div><!-- content-box-wrapper-->
                    </div><!-- content-box -->
                  </div><!-- example-box-wrapper -->
                </div><!-- panel body-->
              </div><!--panel-->

            </div><!-- container -->
          </div><!-- page-content -->
        </div><!-- page-content-wrapper -->
      </div><!-- page_wrapper -->
    </div><!-- sb-site -->
        
