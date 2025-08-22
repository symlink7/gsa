<? 
include(CONFIG."project_statuses.php");

require_once(VIEWS."projects/funcs.php");
require_once(VIEWS."project_statuses/widgets_modals.php");
require_once(VIEWS."project_statuses/panel_body.php");

include(TEMPLATES."general_wrap.php"); 
include(TEMPLATES."widgets/interactions.html");
include(TEMPLATES."widgets/dialog.html");
include(TEMPLATES."widgets/spinner.html");
include(TEMPLATES."widgets/accordion.html");
include(TEMPLATES."widgets/jgrowl.html");
include(TEMPLATES."widgets/ckeditor.html");
?>
<!-- ely's modal script -->
<script type="text/javascript" src="platform/js/modals.js"></script>
<script type="text/javascript" src="platform/js/forms_submit.js?v=<?=mktime()?>"></script>

        <div id="page-content-wrapper">
          <div id="page-content">
            <div class="container">
              <div id="page-title">
                <h2 class="pad10B"><strong><?=$page_title?></strong></h2>
                <h5 class="pad10B">&nbsp;</h5>
              </div><!--Page Title-->
              <?
              $buttons = array();
              if ($can_update) {
                echo modal_update();
                $buttons["can_update"] = 1;
              }
              else if ($can_edit) {
                echo modal_update(true); // true for $edit
                $buttons["can_edit"] = 1;
              }
              if ($can_approve) {
                $buttons["can_approve"] = 1;
              }  
              ?>
