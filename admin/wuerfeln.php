<?php 
require 'config.php';
require 'functions.php';
error_reporting(E_ERROR | E_PARSE);
$mysqli = new mysqli("$DBHOST", "$DBUSER", "$DBPASS", "$DBNAME");
if ($mysqli->connect_errno) {
	die('mysqli connection error: ' . $mysqli->connect_error);
}

$res = selectsql("SELECT code from teilnehmer");
$teilnehmer = array();
while($row = $res->fetch_assoc()) {
	$x = $row["code"];
	array_push($teilnehmer, $x);
}

if (checkrolled() && !$ALLOWREROLL) {
	echo "Wichtel wurden bereits zugeteilt!\n";
	exit();
}
elseif (checkrolled() && $ALLOWREROLL) {
	$sql="UPDATE zuweisungen SET wichtel=null";
	$mysqli->query($sql);
}


$anzahl = count($teilnehmer);
if ($anzahl <=1 ) {
	echo "Keine oder zu wenig Datensätze vorhanden\n";
	exit();
}

$err=1;
while ($err) {
	$c=0;
	$wichtel = $teilnehmer;
	
	$rmax = $anzahl;


	for ($i=0; $i <=  $anzahl-1; $i++) { 
		do {
			$rand = rand(0,$rmax);

			$c++;
			if ($teilnehmer[$i] != $wichtel[$rand] && $wichtel[$rand] != "") {
				setwichtel($teilnehmer[$i], $wichtel[$rand]);
			}
			if ($c==1000) {
				echo "Error.\n";
				$err=1;
				break;
			}
			$err=0;

		} while ($teilnehmer[$i] == $wichtel[$rand] || $wichtel[$rand] == "");
		unset($wichtel[$rand]);

		$wichtel = array_values($wichtel);
		$rmax = count($wichtel);

	}
}
$mysqli->close;
file_put_contents('.rolled', '');
?>