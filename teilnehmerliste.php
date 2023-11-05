<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/src/universal-styles.css">
    <style>
        @counter-style weihnachten {
            system: cyclic;
            symbols: "❄️";
            suffix: " ";
        }
        ul {
            list-style: weihnachten;
        }
    </style>
    <title>Anmeldeliste</title>

</head>
<body>
    <ul>
<?php
require 'admin/config.php';
require 'admin/functions.php';

$mysqli = new mysqli("$DBHOST", "$DBUSER", "$DBPASS", "$DBNAME");
if ($mysqli->connect_errno) {
  die('mysqli connection error: ' . $mysqli->connect_error);
}

$t=0;
$codes = array();
$res = selectsql("SELECT dname, code from ${DBPREFIX}_teilnehmer");
while ($row = $res->fetch_assoc()) {
$dname = $row['dname'];
$c = $row['code'];
array_push($codes, $c);
$i++;
echo "<li>$dname</li>\n";
}
echo "</ul>\n<p>Teilnehmer: $i<br>";

$i=0;
$res = selectsql("SELECT teilnehmer from ${DBPREFIX}_zuweisungen");
while ($row = $res->fetch_assoc()){
    $c=$row['teilnehmer'];
    if (!(in_array($c, $codes))) {
        $i++;
    }
}
echo "Noch nicht angemeldet: $i</p>";

?>
</body>
</html>
