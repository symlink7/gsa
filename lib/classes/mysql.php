<?

function mysql_connect($host, $user, $pass) {
  $db_link = new mysqli($host, $user, $pass);
  return ($db_link->connect_error ? false : $db_link);
}

function mysql_select_db($db_name) {
  global $db_link;
  return $db_link->select_db($db_name);
}

function mysql_error() {
  global $db_link;
  return (!$db_link ? mysqli_connect_error() : $db_link->error);
}

// $db_connect is just for compliance with some
// crappy code on grantj
function mysql_query($query, $db_connect = "") {
  global $db_link;
  return $db_link->query($query);
}

function mysql_num_rows($res) {
  return $res->num_rows;
}

function mysql_fetch_assoc($res) {
  return $res->fetch_assoc();
}

function mysql_fetch_row($res) {
  return $res->fetch_row();
}

function mysql_fetch_object($res) {
  return $res->fetch_object();
}

function mysql_fetch_array($res) {
  return $res->fetch_array();
}

function mysql_insert_id() {
  global $db_link;
  return $db_link->insert_id;
}

function mysql_affected_rows() {
  global $db_link;
  return $db_link->affected_rows;
}

function mysql_escape_string($str) {
  global $db_link;
  return $db_link->escape_string($str);
}

function mysql_close($db_link) {
  $db_link->close();
}  
?>
