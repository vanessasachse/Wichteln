<?php
Require 'admin/config.php';
Require 'admin/functions.php';
$dname = $_POST['dname'];
$wishlist = $_POST['wishlist'];
$adresse = $_POST['adresse'];
$interesse = $_POST['interesse'];
$favs = $_POST['like'];
$notlike = $_POST['notLike'];
$code=$_POST['code'];



// Direktes aufrufen verhindern
if (!isset($_POST['code'])) {
  header("location:.");
}

$mysqli = new mysqli("$DBHOST", "$DBUSER", "$DBPASS", "$DBNAME");
if ($mysqli->connect_errno) {
  die('mysqli connection error: ' . $mysqli->connect_error);
}


$res = selectsql("SELECT teilnehmer from zuweisungen where teilnehmer='$code'");
$row = $res->fetch_assoc();
if (is_null($row)) {
  showerr("Code nicht gültig!");
}


$sql = "SELECT code from teilnehmer";
$res = selectsql("SELECT code from teilnehmer");
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
  echo "Danke, du bist nun für das Wichteln angemeldet!";
} else {
  echo "Error: " . $sql . "<br>" . $mysqli->error;
}
$mysqli->close();


