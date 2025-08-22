<? 
require_once(VIEWS."project_images/widgets_gallery.php");
include(TEMPLATES."general_wrap.php"); 
include(TEMPLATES."widgets/rl.html");
extract($tudo);
?>
        <div id="page-content-wrapper">
          <div id="page-content">
            <div class="container">

              <div class="panel">
                <div class="panel-heading content-box-header" style="background-color:#e1e1e1">
                  <h3 class="panel-title font-size-18 font-black">
                    <a href="index.php?q=projects/details/<?=$project_id?>"><?=$project_name?></a>
                  </h3>
                </div>
                <div class="panel-body">

              <div class="row">
                <? 
                echo existing_images($images, $project_id);
                ?>
              </div><!-- end row of column left and column right -->
            
                </div><!-- panel-body -->
              </div><!-- panel -->

            </div><!-- container -->
          </div><!-- page-content -->
        </div><!-- page-content-wrapper -->
      </div><!-- page_wrapper -->
    </div><!-- sb-site -->
