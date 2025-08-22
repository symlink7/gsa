<?
function existing_images($images, $project_id) {
  $str = '
        <div class="row">
          <div class="col-md-12 project-images">
            <div class="box-line gsa-blue" style="height:4px;">
            </div><!-- box-line -->
            <h6>
              <i class="glyph-icon icon-status-light-blue icon-photo" title=".icon-photo"></i>
              Project Images
              <a href="index.php?q=project_images/for_project/'.
              $project_id.'">(manage images)</a>
            </h6>
  ';
  $i = 0;
  $valid_images = array();
  $str2 = "";
  foreach ($images as $arr) {
    $image = "images/".$arr["image_file"];
    $thumb = "thumbs/".$arr["image_file"];
    if (!is_file(UPLOADS.$image) || !is_file(UPLOADS.$thumb)) {
      continue;
    }
    $size = getimagesize(UPLOADS.$thumb);
    $w = $size[0];
    $h = $size[1];
    $valid_images[] = array(
      "image" => $image,
      "thumb" => $thumb,
      "orig_image" => "orig_images/".$arr["image_file"],
    );

    $str2 .= '
    <div class="project-image">
      <img src="'.UPLOADS_WEB.$thumb.'" width="'.$w.
      '" height="'.$h.
      //'" onClick="FWDRL.show('."'rlobj_images', '{$i}');".
      '" onClick="location.href='.
      "'index.php?q=project_images/gallery/$project_id".
      "#RL?rl_playlist=rlobj_images&rl_id=$i'".
      '">
    </div>
    ';
    $i++;
  }
  $str .= 
//    (is_arr_valid($valid_images) ?  json_object($valid_images) : "").
    $str2.
          '
          </div><!-- col-med-12 -->
        </div><!-- row -->
  ';

  return $str;
} // existing images

function json_object($items, $obj_name = "rlobj_images") {
  $str = "";
  $els = array();
  foreach ($items as $arr) {
    $els[] = "
    {
      url: '".UPLOADS_WEB.$arr["image"]."',
      thumbnailPath: '".UPLOADS_WEB.$arr["thumb"]."',
      description: '<div class=\"rl-description\">'+
        '<a href=\"".UPLOADS_WEB.$arr["orig_image"].'">'.
        "Download Image</a>'+
        '</div>'
    }";
  }
  if (sizeof($els) > 0) {
    $str = "
  <script>  
  var $obj_name = {
    playlistItems: [
  ". implode(", ", $els)."
    ]
  };
  </script>
  ";
  }
  return $str;
} // end of json_object()

?>
