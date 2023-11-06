<?php
require 'config.php';
require 'functions.php';
error_reporting(E_ERROR | E_PARSE);
if ($DEBUG) {
	$mysqli = new mysqli("$DBHOST", "$DBUSER", "$DBPASS", "$DBNAME");
	if ($mysqli->connect_errno) {
		die('mysqli connection error: ' . $mysqli->connect_error);
	}



	$sql1=$mysqli->prepare("TRUNCATE ${DBPREFIX}_teilnehmer");
	$res1=$sql1->execute();


	$sql2=$mysqli->prepare("TRUNCATE ${DBPREFIX}_zuweisungen");
	$res2=$sql2->execute();

	unlink(".rolled");

	if ($res1 === TRUE && $res2 === TRUE) {
		echo "Ok";
	}
}
else {
	echo "Debug Modus ist deaktiviert!";
}
echo '<br><a href="/admin">Zurück</a>';
