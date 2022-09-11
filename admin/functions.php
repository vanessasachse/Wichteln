<?php

function showerr($errmsg){
  global $mysqli;
  if (isset($mysqli)) {
    $mysqli->close(); 
  }
  echo "<h1>$errmsg</h1>";
  echo "<a href=.>Zurück</a>";
  exit();
}

function selectsql($query){
  global $mysqli;
  $sql = $query;
  $res = $mysqli->query($sql);
  return $res;
}
function setwichtel($teilnehmer, $wichtel){
  global $mysqli;
  $sql = "UPDATE zuweisungen set wichtel='$wichtel' where teilnehmer='$teilnehmer'";

  if ($mysqli->query($sql) === TRUE) {
  echo "$wichtel => $teilnehmer\n";
} else {
  echo "Error: " . $sql . "<br>" . $mysqli->error;
}

}

?>