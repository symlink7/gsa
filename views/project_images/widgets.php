<?
function existing_images($images) {
  $str = '
        <div class="row">
          <div class="col-md-12 project-images">
  ';
  foreach ($images as $arr) {
    $image = "images/".$arr["image_file"];
    $thumb = "thumbs/".$arr["image_file"];
    if (!is_file(UPLOADS.$image) || !is_file(UPLOADS.$thumb)) {
      continue;
    }
    $size = getimagesize(UPLOADS.$thumb);
    $w = $size[0];
    $h = $size[1];

    $str .= '
    <div class="project-image">
      <a href="'.UPLOADS_WEB.$image.'" target="_blank">
        <img src="'.UPLOADS_WEB.$thumb.'" width="'.$w.
        '" height="'.$h.'">
      </a>
      <i class="glyph-icon icon-trash delete-image-icon"'.
      ' title="Delete Image" data-toggle="modal"'.
      ' data-target="#image-delete-confirm-dialog"'.
      ' image-id="'.$arr["image_id"].'"></i>
      <i class="glyph-icon tooltip-button icon-newspaper-o infeed'.
      ' plug-'.($arr["image_infeed"] > 0 ? "on" : "off").'"'.
      ' data-original-title=".icon-newspaper-o"'.
      ' title="Public Status"'.
      ' data-toggle="modal"'.
      ' data-target="#image-infeed-confirm-dialog"'.
      ' image-id="'.$arr["image_id"].'"'.
      ' image-infeed="'.$arr["image_infeed"].'"></i>
    </div>
    ';
  }
  $str .= '
          </div><!-- col-med-12 -->
        </div><!-- row -->

<div id="image-delete-confirm-dialog" class="confirm-dialog modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <form id="image-delete-form" action="" method="POST" />
          <input type="hidden" name="q" value="project_images/delete/'.
          $arr["project_id"].'" />
          <input id="image-to-delete" type="hidden" name="image_id" value="" />
        </form>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Confirm Delete</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this image?</p>
        <p>This action cannot be undone.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
        <button type="button" id="image-delete-form-submit" name="image-delete" class="btn btn-primary" data-dismiss="modal">Yes, Delete Image</button>
      </div>
    </div>
  </div>
</div><!-- image-delete-confirm-dialog -->
  ';
  $str .= '
<div id="image-infeed-confirm-dialog" class="confirm-dialog modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <form id="image-infeed-form" action="" method="POST" />
          <input type="hidden" name="q" value="project_images/infeed/'.
          $arr["project_id"].'" />
          <input id="image-to-infeed" type="hidden" name="image_id" value="" />
        </form>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Confirm Change of Image\'s Public Status</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to make image 
           <span id="public-private"><!-- dyn gen based on image-infeed --></span>?
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
        <button type="button" id="image-infeed-form-submit" name="image-infeed" class="btn btn-primary" data-dismiss="modal"><!-- dyn gen based on image-infeed --></button>
      </div>
    </div>
  </div>
</div><!-- image-infeed-confirm-dialog -->
  ';  
  return $str;
}

function existing_images_json($images) {
  $items = array();
  foreach ($images as $arr) {
    $image = "images/".$arr["image_file"];
    $thumb = "thumbs/".$arr["image_file"];
    if (!is_file(UPLOADS.$image) || !is_file(UPLOADS.$thumb)) {
      continue;
    }
    $filesize = filesize(UPLOADS."orig_images/".$arr["image_file"]);
    $items[] = '{name:"'.UPLOADS_WEB.$thumb.'",size:'.$filesize."}";
  }
  return (sizeof($items) > 0 ?
    "[".implode(",", $items)."]" : "");
}

?>
