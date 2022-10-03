<?php 
//Generiert Teilnehmercodes
require 'config.php';
require 'functions.php';
if (isset($argv[2])) {
  $CODEBLOCKS = $argv[2];
}

if (isset($argv[1])) {
  $ANZAHL=$argv[1];
}
else {
  $ANZAHL=1;
}

$mysqli = new mysqli("$DBHOST", "$DBUSER", "$DBPASS", "$DBNAME");
if ($mysqli->connect_errno) {
  die('mysqli connection error: ' . $mysqli->connect_error);
}

for ($i=0; $i < $ANZAHL; $i++) { 
	
	$code = generateKey();
	$sql = "INSERT INTO ${DBPREFIX}_zuweisungen (teilnehmer)
  VALUES ('$code')";

  if ($mysqli->query($sql) === TRUE) {
    echo "Teilnehmercode $code wurde angelegt!\n";
  } else {
    echo "Error: " . $sql . "<br>" . $mysqli->error;
  }

}
$mysqli->close();
?>
