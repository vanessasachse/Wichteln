<?php
Include 'config.php';
$dname = $_POST['dname'];
$wishlist = $_POST['wishlist'];
$adresse = $_POST['adresse'];
$interesse = addslashes($_POST['interesse']);
$favs = $_POST['like'];
$notlike = $_POST['notLike'];
$code=$_POST['code'];

$mysqli = new mysqli("$DBHOST", "$DBUSER", "$DBPASS", "$DBNAME");
if ($mysqli->connect_errno) {
    throw new RuntimeException('mysqli connection error: ' . $mysqli->connect_error);
}

#print nl2br("$dname \n $wishlist \n $adresse \n $favs \n $notlike $code\n");

if (!in_array($code, $CODES)) {
	print "Code nicht gültig!!";

	exit();
}




$sql = "INSERT INTO teilnehmer (code, dname, wishlist, adresse, interesse, favs, notlike)
VALUES ('$code', '$dname', '$wishlist', '$adresse', '$interesse','$favs', '$notlike')";

if ($mysqli->query($sql) === TRUE) {
  echo "New record created successfully";
} else {
  echo "Error: " . $sql . "<br>" . $mysqli->error;
}
$mysqli->close();

?>
