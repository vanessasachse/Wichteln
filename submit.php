<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./src/style.css">
  <link rel="stylesheet" href="./src/universal-styles.css">
  <link rel="stylesheet" href="./src/mobile_submit.css">
  
  <link rel="icon" type="image/png" href="/images/favicon/favicon-32x32.png" sizes="32x32">
  <link rel="icon" type="image/png" href="/images/favicon/favicon-16x16.png" sizes="16x16">
  <link rel="apple-touch-icon" type="image/png" href="/images/favicon/apple-touch-icon.png" sizes="180x180">
  <title>Wichteln</title>
</head>

<body>
  <?php
  require 'admin/config.php';
  require 'admin/functions.php';
  $dname = $_POST['dname'];
  $wishlist = $_POST['wishlist'];
  $adresse = $_POST['adresse'];
  $interesse = $_POST['interesse'];
  $favs = $_POST['like'];
  $notlike = $_POST['notLike'];
  $code = $_POST['code'];
  $email = $_POST['email'];


  // Direktes aufrufen verhindern
  if (!isset($_POST['code'])) {
    header("location:.");
  }
  if (checkbanned($_SERVER['REMOTE_ADDR'])) {
    showerr("ZU OFT VERSUCHT", "Versuch's später nochmal</body></html>", null);
    exit();
  }

  $mysqli = new mysqli("$DBHOST", "$DBUSER", "$DBPASS", "$DBNAME");
  if ($mysqli->connect_errno) {
    die('mysqli connection error: ' . $mysqli->connect_error);
  }


  $sql=$mysqli->prepare("SELECT teilnehmer from ${DBPREFIX}_zuweisungen where teilnehmer=?");
  $sql->bind_param("s",$code);
  $sql->execute();
  $row=($sql->get_result())->fetch_assoc();
  $rolled = checkrolled();
  if (is_null($row)) {
    logFail($_SERVER['REMOTE_ADDR'], $code);
    showerr("CODE UNGÜLTIG!", "Gefettfingert? Versuch’s einfach nochmal.", "<img class='cat' src='./images/gifs/type-computer.gif' width='160%'>");
  }



  $res = selectsql("SELECT code from ${DBPREFIX}_teilnehmer");
  $usedcodes = array();
  while ($row = $res->fetch_assoc()) {
    $x = $row["code"];
    array_push($usedcodes, $x);
  }
  if (in_array($code, $usedcodes)) {
    showerr("UH, OH. DU BIST SCHON FÜR’S 
  WICHTELN EINGETRAGEN!", "Hast du nach <a href='edit/$code'>dieser Seite</a> gesucht? <br>
  Falls du einfach wissen wolltest, was passiert: Dies. Spannend, nicht wahr?", "<img class='kermit' src='./images/gifs/kermit-the-frog-looking-for-directions.gif' width='160%'>");
  }


$sql = $mysqli->prepare("INSERT INTO ${DBPREFIX}_teilnehmer (code, dname, wishlist, adresse, interesse, favs, notlike, email) VALUES (?,?,?,?,?,?,?,?)");
$sql->bind_param("ssssssss", $code, $dname, $wishlist, $adresse, $interesse, $favs, $notlike, $email);

  if ($sql->execute() === TRUE) {
    echo '<div class="container">
  <div class="image">
    <img class="sideimg" src="./images/christmas-decoration.png" alt="girl decorating a christmas tree">
  </div>';
    echo "<div class='wrapper'><div class='test'><div class='msg'><h1>DANKE, DU BIST NUN FÜR DAS 
  WICHTELN EINGETRAGEN!</h1>";
    echo "<div class='back'><a href=anmeldung.php><img src='./images/expand_circle_down_FILL0_wght300_GRAD0_opsz48.svg'>Zurück</a></div><div><p>Am <strong>$ROLLDATE</strong> um <strong>$ROLLTIME Uhr</strong> wird dir dein Wichtel zugeteilt! ❄ <br>
  Bis zu diesem Zeitpunkt kannst du deine Daten jederzeit unter deinem persönlichen Link bearbeiten:
  <input type='text' onClick='this.select()' readonly value='https://$SITEURL/edit/$code'></p></div><div class='gif'><img class='party' src='./images/gifs/fist.gif' width='160%'></div></div></div></div></div>";
  } else {
    echo "Error: " . $sql . "<br>" . $sql->error;
  }
  $mysqli->close();
  ?>

</body>

</html>