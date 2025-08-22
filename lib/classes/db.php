<?
class DB {

  public static function get_id_name($args = array(), $return_first = false) {
    $tudo = array();
    if (sizeof($args) < 1 || 
        !is_var_valid($args["model"]) ||
        !is_file(CONFIG.$args["model"].".php")
       ) {
      return $tudo;
    }

    extract($args);
    include (CONFIG.$model.".php");

    if (!is_arr_valid($where)) {
      $where = array();
    }

    if (array_key_exists($var_pfx."deleted", $db_fields)) {
      $where[] = "{$var_pfx}deleted != 'Y'";
    }
    $query = "SELECT $primkey AS id, 
              $descr_field AS name 
              FROM ".$table.
              (sizeof($where) > 0 ? "
              WHERE ".
                implode("\nAND ", $where) : ""
              ).
              (is_var_valid($order) ? " 
              ORDER BY $order" : ""
              );

    $res = mysql_query($query) or die(mysql_error());
    while ($row = mysql_fetch_assoc($res)) {
      $tudo[] = strip($row);
    }
    if ($return_first && sizeof($tudo) > 0) {
      $tudo = $tudo[0];
    } 
    return $tudo;
  } // get_id_name

  public static function get_fields($args = array(), $return_first = false) {
    $tudo = array();
    if (sizeof($args) < 1 ||
        !is_var_valid($args["model"]) ||
        !is_file(CONFIG.$args["model"].".php")
       ) {
      return $tudo;
    }

    extract($args);
    include (CONFIG.$model.".php");

    if (!is_arr_valid($where)) {
      $where = array();
    }

    if (array_key_exists($var_pfx."deleted", $db_fields)) {
      $where[] = "{$var_pfx}deleted != 'Y'";
    }

    $query = "SELECT ".
              (!is_arr_valid($fields) ? "*" : implode(", ", $fields))." 
              FROM ".$table.
              (sizeof($where) > 0 ? "
              WHERE ".
                implode("\nAND ", $where) : ""
              ).
              (is_var_valid($order) ? " 
              ORDER BY $order" : ""
              );

    $res = mysql_query($query) or die(mysql_error());
    while ($row = mysql_fetch_assoc($res)) {
      $tudo[] = strip($row);
    }
    if ($return_first && sizeof($tudo) > 0) {
      $tudo = $tudo[0];
    }
    return $tudo;
  } // get_fields

  public static function insert($table, $arr, $get_insert_id = false) {
    $field_names = array_keys($arr);
    $query = "INSERT INTO $table
             (".implode(", ", $field_names).")
             VALUES
             (".implode(", ", $arr).")
             ";
    if (!mysql_query($query)) {
      return "$query: ".mysql_error();
    }
    else {
      return ($get_insert_id ? mysql_insert_id() : "");
    }  
  } // insert

  public static function query($query) {
    $tudo = array();
    $res = mysql_query($query) or die(mysql_error());
    while ($row = mysql_fetch_assoc($res)) {
      $tudo[] = strip($row);
    }
    return $tudo;
  }
}
