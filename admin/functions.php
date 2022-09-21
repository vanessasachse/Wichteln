<?php

function showerr($errmsg, $text, $gif){
  global $mysqli;
  if (isset($mysqli)) {
    $mysqli->close(); 
  }
  echo '<div class="container">
  <div class="image">
    <img src="./images/christmas-decoration.png" alt="girl decorating a christmas tree">
  </div>';
  echo "<div class='wrapper'><div class='test'><div class='msg'><h1>$errmsg</h1>";
  echo "<div class='back'><a href=.><img src='./images/expand_circle_down_FILL0_wght300_GRAD0_opsz48.svg'>Zurück</a></div><div><p>$text</p></div><div class='gif'>$gif</div></div></div></div></div>";
  exit();
}

function checkrolled(){
  $rolled=0;
  $res = selectsql("SELECT wichtel from zuweisungen");
  $check=array();
  while($row = $res->fetch_assoc()) {
    $x = $row["wichtel"];
    array_push($check, $x);
  }

  foreach ($check as &$value) {
    if (!is_null($value)) {
      $rolled=1;
    }
  }
  return $rolled;
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