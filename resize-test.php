<?
require_once("config_test.php");
require_once(CONFIG."project_images.php");
require_once(CLASSES."image.php");
extract($image_settings);
$newname = "11370-10313.png";
    

    if ($max_width) {
      $imgrc = new image(UPLOADS."orig_images/".$newname, 
                         UPLOADS."images/".$newname,
                         $max_width, $max_height, "both");
      $imgrc->resize();
    }  
    else {
      copy(UPLOADS."orig_images/".$newname, 
           UPLOADS."images/".$newname);
    }

    if ($thumb_width) {
      $imgrc = new image(UPLOADS."images/".$newname, 
                         UPLOADS."thumbs/".$newname, 
                         $thumb_width, 
                         $thumb_height,
                         $crop ? "crop" : $dside); 
      $imgrc->resize();
      if ($crop) {
        $imgrc = new image(UPLOADS."thumbs/".$newname,
                           UPLOADS."thumbs/".$newname,
                           $thumb_width,
                           $thumb_height); 
        $imgrc->crop();
      }
    } // if $thumb_width
?>
