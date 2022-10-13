<?php
require 'config.php';

$code = $_GET['code'];
if (isset($code) && $code != "") {
    $mysqli = new mysqli("$DBHOST", "$DBUSER", "$DBPASS", "$DBNAME");
    $sql = $mysqli->prepare("DELETE FROM ${DBPREFIX}_zuweisungen WHERE teilnehmer=?");
    $sql2 = $mysqli->prepare("DELETE FROM ${DBPREFIX}_teilnehmer WHERE code=?");
    $sql->bind_param("s", $code);
    $sql2->bind_param("s", $code);
    if (($sql->execute()) === TRUE && ($sql2->execute() === TRUE)) {
        echo "OK";
    }
}
