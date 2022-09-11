<?php 
//Generiert Teilnehmercodes
require 'config.php';
$ANZAHL=5;

$mysqli = new mysqli("$DBHOST", "$DBUSER", "$DBPASS", "$DBNAME");
if ($mysqli->connect_errno) {
  die('mysqli connection error: ' . $mysqli->connect_error);
}

for ($i=0; $i < $ANZAHL; $i++) { 
	
	$code = randomCode();
	$sql = "INSERT INTO zuweisungen (teilnehmer)
VALUES ('$code')";

if ($mysqli->query($sql) === TRUE) {
  echo "Teilnehmercode $code wurde angelegt!\n";
} else {
  echo "Error: " . $sql . "<br>" . $mysqli->error;
}

}
$mysqli->close();

function randomCode() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 32; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

 ?>
