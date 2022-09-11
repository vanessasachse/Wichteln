<?php
Include 'config.php';
$dname = $_POST['dname'];
$wishlist = $_POST['wishlist'];
$adresse = $_POST['adresse'];
$interesse = $_POST['interesse'];
$favs = $_POST['like'];
$notlike = $_POST['notLike'];
$code=$_POST['code'];


function showerr($errmsg){
  global $mysqli;
  if (isset($mysqli)) {
    $mysqli->close(); 
  }
  echo "<h1>$errmsg</h1>";
  echo "<a href=.>Zurück</a>";
  exit();
}


// Direktes aufrufen verhindern
if (!isset($_POST['code'])) {
  header("location:.");
}



#print nl2br("$dname \n $wishlist \n $adresse \n $favs \n $notlike $code\n");

if (!in_array($code, $CODES)) {
  showerr("Code nicht gültig!");
}
$mysqli = new mysqli("$DBHOST", "$DBUSER", "$DBPASS", "$DBNAME");
if ($mysqli->connect_errno) {
  die('mysqli connection error: ' . $mysqli->connect_error);
}

  $sql = "SELECT code from teilnehmer";
  $res = $mysqli->query($sql);
  $usedcodes = array();
  while($row = $res->fetch_assoc()) {
    $x = $row["code"];
    array_push($usedcodes, $x);
  }
  if (in_array($code, $usedcodes)) {
    showerr("Code wurde bereits genutzt!");
  }


$code=$mysqli->real_escape_string($code);
$dname=$mysqli->real_escape_string($dname);
$wishlist=$mysqli->real_escape_string($wishlist);
$adresse=$mysqli->real_escape_string($adresse);
$interesse=$mysqli->real_escape_string($interesse);
$favs=$mysqli->real_escape_string($favs);
$notlike=$mysqli->real_escape_string($notlike);


$sql = "INSERT INTO teilnehmer (code, dname, wishlist, adresse, interesse, favs, notlike)
VALUES ('$code', '$dname', '$wishlist', '$adresse', '$interesse','$favs', '$notlike')";



if ($mysqli->query($sql) === TRUE) {
  echo "New record created successfully";
} else {
  echo "Error: " . $sql . "<br>" . $mysqli->error;
}
$mysqli->close();


