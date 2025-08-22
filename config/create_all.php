<?

$sections = array(
  "users", "clients",
  "projects", "clients_for_projects",
  "project_budgets", "project_details",
  "project_dates", "project_statuses",
  "departments",
  "project_statuses_archive",
  "buildings", "project_images",
  "project_api_status", "city_zips",
);  

for ($i = 0; $i < sizeof($sections); $i++){
  $secname = $sections[$i];
  include($secname . ".php");
  
  $fields = array();
  $keys = array();
  $auto_increment = 0;
  
  foreach ($db_fields as $varname => $varspecs){
    $fields[] = "$varname {$varspecs[0]}".
      ($varspecs[1] != "" ? "({$varspecs[1]})" : "").
      
      ($varspecs[0] == "varchar" || $varspecs[0] == "text" ? 
        " COLLATE utf8_bin NOT NULL" : "").
      ($varspecs[0] == "set" && isset($varspecs[5]) ? 
        " NOT NULL".
        " DEFAULT '{$varspecs[5]}'" : ""). 
      ($varspecs[0] == "date" || $varspecs[0] == "datetime" ?
        " NOT NULL" : "").
      (isset($varspecs[5]) && $varspecs[5] == "unique" ?
        " NOT NULL UNIQUE" : "").
      (isset($varspecs[5]) && $varspecs[5] == "primary" ?
        " NOT NULL PRIMARY KEY" : "").
      (isset($varspecs[5]) && $varspecs[5] == "not null" ?
        " NOT NULL" : "")  ;
    if (preg_match("/increment/", strtolower($varspecs[0]))) {
      $auto_increment = 1;
    }
    if (isset($varspecs[5]) && $varspecs[5] == "foreign" &&
        isset($varspecs[6])) {
      $keys[] = "FOREIGN KEY ($varname) REFERENCES {$varspecs[6]}";
    }  
  }        
  
  $query = "CREATE TABLE IF NOT EXISTS $table ".
           "(\n\t".
           implode(",\n\t", $fields).
           (sizeof($keys) > 0 ? 
             ",\n\t" . implode(",\n\t", $keys) : "").
           "\n\t)".
           ($auto_increment ? " AUTO_INCREMENT=10001" : "");

/*
  if (mysql_query($query))
    echo "Successfully created table $table for section $secname!<br>\n";
  else
    echo "Could not create table $table for section $secname: " . mysql_error() . "<br>\n";
*/
  echo "$query\n\n";
}

?>
