<?php
require 'admin/config.php';
require 'admin/functions.php';
$mysqli = new mysqli("$DBHOST", "$DBUSER", "$DBPASS", "$DBNAME");
$res = selectsql("SELECT trackingid from zuweisungen where teilnehmer='$code'");
$row = $res->fetch_assoc();
$trackingid = $row['trackingid'];
$trackingsent=0;

if ($trackingid != null) {

    $trackingsent = 1;
    return "Fail!";
}

if (isset($_POST['code']) && (!$trackingsent)) {
    $trackingcode = $_POST['tracking'];
    $code = $_POST['code'];
    
    $trackingcode = $mysqli->real_escape_string($trackingcode);
    $sql = "UPDATE zuweisungen set trackingid='$trackingcode' where teilnehmer='$code'";
    if ($mysqli->query($sql) === TRUE) {
        $trackingsent = 1;
        echo 1;
    }
    $mysqli->close();
}
