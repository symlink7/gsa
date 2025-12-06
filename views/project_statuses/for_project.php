<? 
$page_title = "Project's Statuses";
$can_update = 1;
include(VIEWS."project_statuses/header.php");
extract($tudo);
?>
              <div class="panel">
                <div class="panel-heading content-box-header" style="background-color:#e1e1e1">
                  <h3 class="panel-title font-size-18 font-black">
                    <a href="index.php?q=projects/details/<?=$project_id?>"><?=$project_name?></a>
                  </h3>
                </div><!-- panel-heading -->
                <?=panel_body($statuses, $buttons, $project_id); ?>
              </div><!-- panel -->

