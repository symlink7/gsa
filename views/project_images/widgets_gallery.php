<?
function existing_images($images, $project_id) {
  $str = '
        <div class="row">
          <div class="col-md-12 project-images">
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
      '" onClick="FWDRL.show('."'rlobj_images', '{$i}');".
      '">
    </div>
    ';
    $i++;
  }
  $str .= 
    (is_arr_valid($valid_images) ?  json_object($valid_images) : "").
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
    $orig_image = UPLOADS.$arr["orig_image"];
    if (file_exists($orig_image)) {
      $size = getimagesize($orig_image);
      $size_text = $size[0]."x".$size[1];
    }
  
    $els[] = "
    {
      url: '".UPLOADS_WEB.$arr["image"]."',
      thumbnailPath: '".UPLOADS_WEB.$arr["thumb"]."',
      description: '".
        '<div class="rl-description">'.
        '<a target="_blank" href="'.
        UPLOADS_WEB.$arr["orig_image"].'">'.
        '<i class="glyph-icon icon-download download-image-rl"'.
        ' title="Download Image"></i>'.
        " Download Full-Size Image ($size_text)".
        "</a></div>'
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
