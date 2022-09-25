<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./src/style.css">
  <link rel="stylesheet" href="./src/universal-styles.css">
  <title>Wichteln</title>
</head>
<body>
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
$email=$_POST['email'];


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
  showerr("CODE UNGÜLTIG!", "Gefettfingert? Versuch’s einfach nochmal.", "<img src='./images/gifs/type-computer.gif' width='160%'>");
}


$sql = "SELECT code from teilnehmer";
$res = selectsql("SELECT code from teilnehmer");
$usedcodes = array();
while($row = $res->fetch_assoc()) {
  $x = $row["code"];
  array_push($usedcodes, $x);
}
if (in_array($code, $usedcodes)) {
  showerr("UH, OH. DU BIST SCHON FÜR’S 
  WICHTELN EINGETRAGEN!", "Hast du nach <a href='wichtel.php'>dieser Seite</a> gesucht? <br>
  Falls du einfach wissen wolltest, was passiert: Dies. Spannend, nicht wahr?", "<img src='./images/gifs/kermit-the-frog-looking-for-directions.gif' width='160%'>");
}


$code=$mysqli->real_escape_string($code);
$dname=$mysqli->real_escape_string($dname);
$wishlist=$mysqli->real_escape_string($wishlist);
$adresse=$mysqli->real_escape_string($adresse);
$interesse=$mysqli->real_escape_string($interesse);
$favs=$mysqli->real_escape_string($favs);
$notlike=$mysqli->real_escape_string($notlike);
$email=$mysqli->real_escape_string($email);


$sql = "INSERT INTO teilnehmer (code, dname, wishlist, adresse, interesse, favs, notlike, email)
VALUES ('$code', '$dname', '$wishlist', '$adresse', '$interesse','$favs', '$notlike', '$email')";



if ($mysqli->query($sql) === TRUE) {
  echo '<div class="container">
  <div class="image">
    <img src="./images/christmas-decoration.png" alt="girl decorating a christmas tree">
  </div>';
  echo "<div class='wrapper'><div class='test'><div class='msg'><h1>DANKE, DU BIST NUN FÜR DAS 
  WICHTELN EINGETRAGEN!</h1>";
  echo "<div class='back'><a href=anmeldung.php><img src='./images/expand_circle_down_FILL0_wght300_GRAD0_opsz48.svg'>Zurück</a></div><div><p>Am <strong>$ROLLDATE</strong> um <strong>$ROLLTIME Uhr</strong> wird dir dein Wichtel zugeteilt! ❄ <br>
  Ab diesem Zeitpunkt kannst du dir die Informationen zu deinem Wichtel jederzeit unter <br><a href='wichtel.php'>diesem Link</a> anschauen.</p></div><div class='gif'><img src='./images/gifs/fist.gif' width='160%'></div></div></div></div></div>";
} else {
  echo "Error: " . $sql . "<br>" . $mysqli->error;
}
$mysqli->close();
?>

</body>
</html>