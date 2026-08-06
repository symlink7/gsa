<?

class Project_imagesController extends Controller {
  private $project_info;

  private function std_check($args = array(), $method) {
    // check permissions
    $error_msg = "";
    if (!$this->has_permissions($method)) {
      $error_msg = "You have no permissions to use this functionality.";
    }
    else if (!is_var_valid($args[0])) {
      $error_msg = "Invalid request.";
    }
    else {
      $this->project_info = 
        $this->{$this->_model_name}->project_info($args[0]);
      if (sizeof($this->project_info) < 1) {
        $error_msg = $this->{$this->_model_name}->get_error_msg();
      }
      else if ($this->project_info["project_archived"] == "Y") {
        $error_msg = $this->error_messages("project_archived");
      }
      else if (!$this->has_permissions2()) {
        $error_msg = $this->error_messages("no_permissions");
      }
    }
    return $error_msg;
  } // std_check()

  function for_project($args = array()) {
    $error_msg = $this->std_check($args, "for_project");
    if ($error_msg == "") {
      $this->set_var("tudo", $this->project_info);
    }
    else {  
      $this->set_var("error_msg", $error_msg);
      $this->set_var("generic_template", "error.php");
    }
    $this->render();
  } // for_project()

  function delete($args = array()) {
    $error_msg = $this->std_check($args, "delete");
    if ($error_msg != "") {
      $this->die_ajax($error_msg);
    }

    if (!is_var_valid($_POST["image_id"])) {
      $this->die_ajax("Something went wrong. [No Image Id.]");
    }
    // check if image really belongs to that project
    foreach ($this->project_info["images"] as $images) {
      if ($images["image_id"] == $_POST["image_id"]) {
        $this_image = $images;
        break;
      }
    }
    if (!is_arr_valid($this_image)) {
      $this->die_ajax("Image doesn't exist.");
    }
    if ($this->{$this->_model_name}->delete($images["image_id"]) == 0) {
      $this->die_ajax("Couldn't delete image info from the database.");
    }
    if (is_file(UPLOADS."images/".$this_image["image_file"])) {
      unlink(UPLOADS."images/".$this_image["image_file"]);
    }
    else {
      $this->die_ajax("File ".UPLOADS."images/".
        $this_image["image_file"]." doesn't exist.");
    }    
    if (is_file(UPLOADS."orig_images/".$this_image["image_file"])) {
      unlink(UPLOADS."orig_images/".$this_image["image_file"]);
    }
    else {
      $this->die_ajax("File ".UPLOADS."orig_images/".
        $this_image["image_file"]." doesn't exist.");
    }    
    if (is_file(UPLOADS."thumbs/".$this_image["image_file"])) {
      unlink(UPLOADS."thumbs/".$this_image["image_file"]);
    }
    else {
      $this->die_ajax("File ".UPLOADS."thumbs/".
        $this_image["image_file"]." doesn't exist.");
    }    

    $this->set_var("refresh", "Image deleted successfully.");
    $this->set_var("generic_template", "success_ajax.php");
    $this->render();
  }

  function infeed($args = array()) {
    $error_msg = $this->std_check($args, "infeed");
    if ($error_msg != "") {
      $this->die_ajax($error_msg);
    }

    if (!is_var_valid($_POST["image_id"])) {
      $this->die_ajax("Something went wrong. [No Image Id.]");
    }
    
    // check if image really belongs to that project
    foreach ($this->project_info["images"] as $images) {
      if ($images["image_id"] == $_POST["image_id"]) {
        $this_image = $images;
        break;
      }
    }
    if (!is_arr_valid($this_image)) {
      $this->die_ajax("Image doesn't exist.");
    }
    if ($this->{$this->_model_name}->infeed($images["image_id"]) == 0) {
      $this->die_ajax("Couldn't change show-in-feed status of the image.");
    }
    $this->set_var("refresh", "Image's show-in-feed status changed successfully.");
    $this->set_var("generic_template", "success_ajax.php");
    $this->render();
  } // infeed()

  function gallery($args = array()) {
    // check permissions
    $error_msg = "";
    if (!$this->has_permissions("gallery")) {
      $error_msg = "You have no permissions to use this functionality.";
    }
    else if (!is_var_valid($args[0])) {
      $error_msg = "Invalid request.";
    }
    else {
      $this->project_info =
        $this->{$this->_model_name}->project_info($args[0]);
      if (sizeof($this->project_info) < 1) {
        $error_msg = $this->{$this->_model_name}->get_error_msg();
      }
    }
    if ($error_msg == "") {
      $this->set_var("tudo", $this->project_info);
    }
    else {
      $this->set_var("error_msg", $error_msg);
      $this->set_var("generic_template", "error.php");
    }
    $this->render();
  } // gallery ()

  function upload($args = array()) {
    $error_msg = $this->std_check($args, "upload");
    if ($error_msg != "") {
      $this->die_json($error_msg);
    }  
    
    include(CONFIG.$this->_controller_name.".php");
    require_once(CLASSES."upload.php");
    
    $error_msg = Upload::is_file_valid($image_settings);
    
    if ($error_msg != "") {
      $this->die_json($error_msg);
    }  
    
    if (sizeof($this->project_info["images"]) >= 
        $image_settings["max_images"]) {
      $this->die_json("You have already uploaded the ".
        "max number of images per project.");
    }
    
    $filename = $_FILES[$image_settings["varname"]]["name"];
    
    $vars = array(
      "project_id" => $args[0],
      "image_orig_filename" => $filename,
      "image_infeed" => "'N'",
    );

    if ($this->{$this->_model_name}->add($vars) == 0) {
      $error_msg = $this->{$this->_model_name}->get_error_msg();
      if ($error_msg == "") {
        $error_msg = "Unable to save image info into the database.";
      }
      $this->die_json($error_msg);
    }
    
    $image_id = $this->{$this->_model_name}->get_id();
    $newname = $this->project_info["project_id"]."-".$image_id.
      ".".Upload::file_extension($filename);

    // save the image from /tmp to its uploads/images_orig/
    Upload::save_image($image_settings["varname"], $newname);
    // update the value of image_file in the database
    if ($this->{$this->_model_name}->add_filename(
        $image_id, $newname) == 0) {
      $error_msg = $this->{$this->_model_name}->get_error_msg();
      if ($error_msg == "") {
        $error_msg = "Unable to update image info in the database.";
      }
      $this->die_json($error_msg);
    }  
    // resize and crop
    Upload::resize_crop($image_settings, $newname);
   
    $this->set_var("confirm_msg", "File saved as $newname.");
    $this->set_var("generic_template", "success_json.php");
    $this->render();
  } // upload()

  private function error_messages($type) {
    switch($type) {
      case "project_archived":
        return "Project is archived and its ".
               "images cannot be modified.";
        break;
      case "no_permissions":
        return "You have no permissions to modify ".
               "the images for this project.";
        break;
    }
  } // error_messages()

  private function has_permissions2() {
    return ((!in_array($_SESSION["user_info"]["user_role"], array("A", "PA")) &&
      (
        ($_SESSION["user_info"]["user_role"] == "M" &&
        $this->project_info["project_manager"] != $_SESSION["user_id"])
      ||
        ($_SESSION["user_info"]["user_role"] == "S" &&
        $this->project_info["project_supervisor"] != $_SESSION["user_id"])
      )
    ) ? false : true);
  } // has_permissions2()

} // Project_imagesController
?>
