<?php 
require 'admin/config.php';
require 'admin/functions.php';

if (!isset($_GET['code'])) {

}

$code=$_GET['code'];



$mysqli = new mysqli("$DBHOST", "$DBUSER", "$DBPASS", "$DBNAME");
if ($mysqli->connect_errno) {
	die('mysqli connection error: ' . $mysqli->connect_error);
}

$res=selectsql("SELECT wichtel from zuweisungen where teilnehmer='$code'");
$row=$res->fetch_assoc();
$wichtel=$row['wichtel'];

if (is_null($wichtel)) {
	echo '<h1>Falscher Teilnehmercode!</h1>';
	exit();
}


///WICHTEL INFO
$wichtel = selectsql("SELECT * FROM teilnehmer where code='$wichtel'")->fetch_assoc();

$dname = $wichtel['dname'];
$wishlist = nl2br($wichtel['wishlist']);
$adresse = nl2br($wichtel['adresse']);
$interesse = nl2br($wichtel['interesse']);
$favs = nl2br($wichtel['favs']);
$notlike = nl2br($wichtel['notlike']);

//*****



?>

<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Wichtel</title>
</head>
<body>
	<?php 
	echo '<h3>Discord-Name</h3>';
	echo "<p>$dname</p>";
	echo '<h3>Wunschliste</h3>';
	echo "<p>$wishlist</p>";
	echo '<h3>Adresse</h3>';
	echo "<p>$adresse</p>";
	echo '<h3>Interessen</h3>';
	echo "<p>$interesse</p>";
	echo '<h3>Lieblings...</h3>';
	echo "<p>$favs</p>";
	echo '<h3>Abneigungen/Allergien</h3>';
	echo "<p>$notlike</p>";
	?>

</body>
</html>
