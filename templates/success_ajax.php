<?

if (!is_var_valid($refresh) && is_var_valid($_POST["refresh"])) {
  $refresh = strip($_POST["refresh"]);
}

$str = '<?xml version="1.0"?>';
$str .= "<response><done>yes</done>".
        (is_var_valid($redirect_vars) ? 
          "<redirect><![CDATA[$redirect_vars]]></redirect>" : ""
        ).
        (is_var_valid($refresh) ?
          "<refresh><![CDATA[$refresh]]></refresh>" : ""
        ).
        "</response>";

header ("Content-type: text/xml");
echo $str;
exit;
?>
