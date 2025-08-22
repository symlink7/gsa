<?
class Upload {

  public static function is_file_valid($settings) {
    $error_msg = "";
    extract($settings);
    
    if (!is_arr_valid($_FILES) || !is_arr_valid($_FILES[$varname])) {
      return "Upload didn't work.";
    }  
    if (is_var_valid($_FILES[$varname]['error']) && 
        $_FILES[$varname]['error'] > 0) {
      switch ($_FILES[$varname]['error']) {
        case "1":
          return "Filesize cannon exceed ".$max_size."MB.";
        case "2": 
          return "Filesize cannot exceed ".$max_size."MB.";
        case "3":
          return "The file was only partially uploaded.";
        default:
          return "Upload didn't work.";
      }
    }
    
    $aexts = array_map('trim', explode(",", $extensions));
    $ext = Upload::file_extension($_FILES[$varname]['name']);
    if (!in_array($ext, $aexts)) {
      return "You can only upload files with extension(s) ".
             $extensions."!";
    } 
    return $error_msg;
  } // is_file_valid()

  public static function file_extension($filename) {
    $ext = strtolower(array_pop(explode(".", $filename)));
    return ($ext == "jpeg" ? "jpg" : $ext);
  }

  public static function save_image($varname, $newname) {
    copy($_FILES[$varname]["tmp_name"], 
         UPLOADS."orig_images/".$newname);
    chmod(UPLOADS."orig_images/".$newname, 0777);
  }  

  public static function resize_crop($settings, $newname) {
    extract($settings);
    
    require_once(CLASSES."image.php");

    if ($max_width) {
      $imgrc = new image(UPLOADS."orig_images/".$newname, 
                         UPLOADS."images/".$newname,
                         $max_width, $max_height, $dside);
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
      //else {
      //  $imgrc->resize();
      // }  
    } // if $thumb_width
  } // resize_crop
} // Upload
?>
