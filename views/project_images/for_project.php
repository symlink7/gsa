<? 
include(CONFIG."project_images.php");
require_once(VIEWS."project_images/widgets.php");

include(TEMPLATES."general_wrap.php"); 
include(TEMPLATES."widgets/dropzone.html");

$max_images = $image_settings["max_images"];
if (is_arr_valid($tudo["images"])) {
  $max_images -= sizeof($tudo["images"]);
}  
$existing_images = ""; // existing_images_json($tudo["images"]);
include(VIEWS."project_images/dropzone.php");
include(TEMPLATES."widgets/jgrowl.html");
// print_r($tudo);
?>
<script type="text/javascript" src="platform/js/modals.js"></script>
<script type="text/javascript" src="platform/js/forms_submit.js"></script>
        <div id="page-content-wrapper">
          <div id="page-content">
            <div class="container">

              <div id="page-title">
                <h2 class="pad10B"><strong>Manage Images</strong></h2>
                <h5 class="pad10B">&nbsp;</h5>
              </div><!--Page Title-->

              <div class="panel">
                <div class="panel-heading content-box-header" style="background-color:#e1e1e1">
                  <h3 class="panel-title font-size-18 font-black">
                    <a href="index.php?q=projects/details/<?=$tudo["project_id"]?>"><?=$tudo["project_name"]?></a>
                  </h3>
                </div>
                <div class="panel-body">
                  <h3 class="title-hero"></h3>
                  <div class="example-box-wrapper">
                    <?
                    if (is_arr_valid($tudo["images"])) {
                      echo existing_images($tudo["images"]);
                    } 
                    ?>                    
                    <div class="row" id="dropzone-example">
                      <form action="ajax.php" class="dropzone bg-gray col-md-10 center-margin" id="project-photo-upload">
                      <input type="hidden" name="q" value="project_images/upload/<?=$tudo["project_id"]?>" />
                      </form>
                    </div><!-- dropzone example -->
                  </div><!-- example-box-wrapper -->
                </div><!-- panel body-->
              </div><!-- panel -->

            </div><!-- container -->
          </div><!-- page-content -->
        </div><!-- page-content-wrapper -->
      </div><!-- page_wrapper -->
    </div><!-- sb-site -->
