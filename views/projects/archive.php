              <? 
              if ($can_archive) { 
                $name = ($tudo["project_archived"] == "Y" ?
                          "Un-Archive" : "Archive");
              ?>
              <div class="layout-box red-border"> 
                <form id="project-archive-form" action="index.php" method="GET" />
                  <input type="hidden" name="q" value="projects/archive/<?=$project_id?>/<?=($tudo["project_archived"] == "Y" ? "N" : "Y")?>" /
>
                </form>
                <p><?=$name?> this project</p>

                <input type="button" class="confirm-button btn btn-danger mrg20T" value="<?=strtoupper($name)?> PROJECT" data-toggle="modal" data-target="#project-archive-confirm-dialog" />
              </div><!-- layout-box red-border -->

<div id="project-archive-confirm-dialog" class="confirm-dialog modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Confirm</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to <?=strtolower($name)?> this project?</p>
        <p>It's okay, this action can always be undone.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
        <button type="button" id="project-archive-form-submit" name="project-archive" class="confirm-yes btn btn-primary">Yes, <?=$name?> Project</button>
      </div>
    </div>
  </div>
</div><!-- project-archive-confirm-dialog -->
<? } ?>
