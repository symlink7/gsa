<? 
$page_title = "Projects awaiting approval";
include(VIEWS."project_statuses/header.php");
include(VIEWS."project_statuses/dep_filter.php");
?>

            <div class="example-box-wrapper">
            <div class="panel-group" id="accordion">
<? 
if (sizeof($tudo) < 1) {
  echo "<h3>There're no projects awaiting approval at this time.</h3>";
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
                    <a id="project-name-<?=$project_id?>" href="index.php?q=projects/details/<?=$project_id?>"><?=($project_number ? "$project_number, " : "").$project_name?></a>
                    <? /*if ($buttons["can_approve"] && $num_statuses > 1) { ?>
                    <a id="bulk-approve-<?=$project_id?>" href="#" link="q=project_statuses/bulk_approve/<?=$project_id?>" class="btn btn-primary bulk-approve-button float-right" data-toggle="modal" data-target="#bulk-approve-confirm-dialog"<?=($selected ? ' style="display: block"' : "")?>>Approve All</a>
                    <? } */?>
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse-<?=$project_id?>"><i class="glyph-icon icon-chevron-down float-right"></i><span class="badge bg-black float-right mrg20R"><?=$num_statuses?></span></a>
                  </h3>
                </div><!-- panel-heading -->
                
                <div id="collapse-<?=$project_id?>" class="panel-collapse collapse<?=$in_class?> approve-statuses-collapse" name="<?=$project_id?>">
                  <? echo panel_body($project["statuses"], $buttons, $project_id); ?>
                </div><!-- .panel-collapse -->  
              </div><!-- .panel -->
<? } /* end of looping through projects */ ?>
            </div><!-- .panel-group -->
          </div><!-- example-box-wrapper -->
<? 
// BULK-APPROVE
include(VIEWS."project_statuses/bulk_approve_confirm.php");
?>
