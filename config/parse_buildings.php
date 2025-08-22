<?
$file = "buildings.csv";
$fp = fopen($file, "r");

$tudo = array();

$header = fgetcsv($fp, 1024, ",", '"');
while (!feof($fp)){
  $line = fgetcsv($fp, 1024, ",", '"');
  if ($line[0]=="" && $line[1]=="" && $line[2]=="")
    continue;
  $tudo[] = fieldname_fieldvalue($line);
}
fclose($fp);

for ($i = 0; $i < sizeof($tudo); $i++) {
  $tudo[$i] = "'".implode("', '", escape($tudo[$i]))."'";
}
echo $query = "
use db170385_gsa;
insert into buildings values \n(".
              implode("), \n(", $tudo).
");";

function fieldname_fieldvalue($line){
  global $header;
  $arr = array();
  for ($i = 0; $i < sizeof($line); $i++){
    if (array_key_exists($i, $header))
      $arr[$header[$i]] = $line[$i];
  }
  return $arr;
}

function escape($value) {
  $value = is_array($value) ? 
            array_map('escape', $value) : mysql_escape_string($value);
  return $value;
}

?>
