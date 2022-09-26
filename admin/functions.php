<?php

function showerr($errmsg, $text, $gif)
{
  global $mysqli;
  if (isset($mysqli)) {
    $mysqli->close();
  }
  echo '<div class="container">';
  echo "<div class='wrapper'><div class='test'><div class='msg'><h1>$errmsg</h1>";
  echo "<div class='back'><a href=javascript:history.back()><img src='./images/expand_circle_down_FILL0_wght300_GRAD0_opsz48.svg'>Zurück</a></div><div><p>$text</p></div><div class='gif'>$gif</div></div></div></div></div></body></html>";
  exit();
}

function checkrolled()
{
  $rolled = 0;
  $res = selectsql("SELECT wichtel from zuweisungen");
  $check = array();
  while ($row = $res->fetch_assoc()) {
    $x = $row["wichtel"];
    array_push($check, $x);
  }

  foreach ($check as &$value) {
    if (!is_null($value)) {
      $rolled = 1;
    }
  }
  return $rolled;
}

function selectsql($query)
{
  global $mysqli;
  $sql = $query;
  $res = $mysqli->query($sql);
  return $res;
}

function setwichtel($teilnehmer, $wichtel)
{
  global $mysqli;
  $sql = "UPDATE zuweisungen set wichtel='$wichtel' where teilnehmer='$teilnehmer'";

  if ($mysqli->query($sql) === TRUE) {
    echo "$teilnehmer => $wichtel\n";
  } else {
    echo "Error: " . $sql . "<br>" . $mysqli->error;
  }
}

function randomCode()
{
  global $CODELENGTH;
  $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
  $pass = array(); //remember to declare $pass as an array
  $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
  for ($i = 0; $i < $CODELENGTH; $i++) {
    $n = rand(0, $alphaLength);
    $pass[] = $alphabet[$n];
  }
  return implode($pass); //turn the array into a string
}

function validateTrackingCode($code){
  $pattern = "/^\w+$/";
  return preg_match("$pattern", $code);

}

function sendTrackingMail($to, $tracking, $discordname)
{
  global $MAILFROM;
  global $MAILNAME;
  $headers[] = 'MIME-Version: 1.0';
  $headers[] = 'Content-type: text/html; charset=utf-8';
  // $headers[] = "To: <$to>";
  $headers[] = "From: $MAILNAME <$MAILFROM>";
  $subject = 'Dein Wichtelpaket ist auf dem Weg!';
  $message = "<html>
  <head>
    <title>Dein Wichtelpaket ist auf dem Weg!</title>
  </head>
  <body>
  Hallo $discordname,<br>
  dein Wichtelpaket ist auf dem Weg, hurra!<br><br>
  Deine Trackingnummer lautet: $tracking<br><br>
  <a href=https://nolp.dhl.de/nextt-online-public/set_identcodes.do?lang=de&idc=$tracking>DHL Tracking Link</a>
  </body>
  </html>
  ";


  mail($to, $subject, $message, implode("\r\n", $headers), "-f $MAILFROM");
}
