<?php

function showerr($errmsg, $text, $gif)
{
  global $mysqli;
  if (isset($mysqli)) {
    $mysqli->close();
  }
  echo '<div class="container error">';
  echo "<div class='wrapper'><div class='test'><div class='msg'><h1>$errmsg</h1>";
  echo "<div class='back'><a href=javascript:history.back()><img class='backImg' src='./images/expand_circle_down_FILL0_wght300_GRAD0_opsz48.svg'>Zurück</a></div><div><p>$text</p></div><div class='gif'>$gif</div></div></div></div></div></body></html>";
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
    return true;
  } else {
    echo "Error: $mysqli->error\n";
    return false;
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
  Ho ho ho $discordname,<br>
  Ein Grund zum Freuen: Dein Wichtelpaket ist auf dem Weg, hurra!<br><br>
  Wir sagen dir natürlich nicht von wem, aber falls dich die Neugierde etwas zu sehr packt, darfst du mit der Trackingnummer $tracking oder unter<br>
  <a href=https://nolp.dhl.de/nextt-online-public/set_identcodes.do?lang=de&idc=$tracking>DHL Tracking Link</a> spicken!<br><br>
  Möge das Paket voller Freude sein!<br>
  </body>
  </html>";


  mail($to, $subject, $message, implode("\r\n", $headers), "-f $MAILFROM");
}

function logFail($ip, $code)
{
  $file = fopen('admin/fail.log', 'a');
  $dt = date('Y-m-d H:i:s');
  fwrite($file, "$dt Falscher Code \"$code\" von IP: $ip\n");
  fclose($file);
}
function checkbanned($ip)
{
  $banfile = 'admin/.banip';
  if (file_exists($banfile)) {
  $banned = 0;
  $file = fopen($banfile, 'r');
  while (($buffer = fgets($file)) !== false) {
    if (strpos($buffer, $ip) !== false) {
      $banned = 1;
    }
  }
  fclose($file);
  return $banned;
}
}
