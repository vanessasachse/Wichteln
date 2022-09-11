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

?>