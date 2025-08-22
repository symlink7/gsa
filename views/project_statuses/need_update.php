<? 
$page_title = "Projects needing update";
include(VIEWS."project_statuses/header.php");
include(VIEWS."project_statuses/dep_filter.php");
?>

            <div class="example-box-wrapper">
            <div class="panel-group" id="accordion">
<? 
if (sizeof($tudo) < 1) {
  echo "<h3>There're no projects needing update at this time.</h3>";
}

// loop through projects 
foreach ($tudo as $project) { 
  extract($project["project_info"]);
  $in_class = ($selected ? " in" : "");
  // calculate number of pending statuses
  $num_statuses = sizeof($project["statuses"]);
?>
  
              <div class="panel project-panel dep-<?=$project_department?>">
                <div class="panel-heading content-box-header" style="background-color:#e1e1e1">
                  <h3 class="panel-title font-size-18 font-black">
                    <a href="index.php?q=projects/details/<?=$project_id?>"><?=($project_number ? "$project_number, " : "").$project_name?></a>
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapse-<?=$project_id?>"><i class="glyph-icon icon-chevron-down float-right"></i><span class="badge bg-black float-right mrg20R"><?=$num_statuses?></span></a>
                  </h3>
                </div><!-- panel-heading -->
                
                <div id="collapse-<?=$project_id?>" class="panel-collapse collapse<?=$in_class?>">
                  <? echo panel_body($project["statuses"], $buttons, $project_id); ?>
                </div><!-- .panel-collapse -->  
              </div><!-- .panel -->
<? } /* end of looping through projects */ ?>
            </div><!-- .panel-group -->
          </div><!-- example-box-wrapper -->

