              <? if ($can_delete) { ?>
              <div class="layout-box red-border">
                <form id="project-delete-form" action="index.php" method="GET" />
                  <input type="hidden" name="q" value="projects/delete/<?=$project_id?>" /
>
                </form>
                <p>If you wish to delete this project 
                  from the dashboard permenantly,
                  choose the 'delete project' button below.
                </p>
                <p>This action can not be undone.</p>
                
                <input type="button" class="confirm-button btn btn-danger mrg20T" value="DELETE PROJECT" data-toggle="modal" data-target="#project-delete-confirm-dialog" />
              </div><!-- layout-box red-border -->

<div id="project-delete-confirm-dialog" class="confirm-dialog modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Confirm Delete</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this project?</p>
        <p>This action cannot be undone.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
        <button type="button" id="project-delete-form-submit" name="project-delete" class="confirm-yes btn btn-primary">Yes, Delete Project</button>
      </div>
    </div>
  </div>
</div><!-- project-delete-confirm-dialog -->
<? } ?>
