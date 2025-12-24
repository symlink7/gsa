<?

$table = "projects_images";
$var_pfx = "image_";
$primkey = $var_pfx."id";

$order_by = "project_id";

$db_fields = array(
  $primkey => array( 
    "int auto_increment", "", "Image ID", 0, 0, "primary"),
  "project_id" => array(
    "int not null", "", "Project ID", 1, 1, "foreign", "projects(project_id)"),
  $var_pfx."file" => array("varchar", "16", "Image File"),
  $var_pfx."orig_filename" => array("varchar", "128", "Original Filename"),
  $var_pfx."infeed" => array("tinyint", "1", "Show in Feed"), 
);

$permissions = array(
  "for_project" => array("A", "M", "S"),
  "update" => array("A", "M", "S"),
  "delete" => array("A", "M", "S"),
  "infeed" => array("A", "M", "S"), 
);  

$image_settings = array(
  "varname" => $var_pfx."file",
  "extensions" => "jpg,png,gif",
  "max_images" => 6,
  "max_size" => 2, // 2 mb
  "max_width" => 480,
  "max_height" => 480,
  "dsize" => "both",
  "thumb_width" => 135,
  "thumb_height" => 135,
  "crop" => 1,
);  
?>
