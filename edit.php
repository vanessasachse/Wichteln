<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bearbeiten</title>
  <link rel="stylesheet" href="../src/style.css">
  <link rel="stylesheet" href="../src/mobile_anmeldung.css">
  <link rel="stylesheet" href="../src/universal-styles.css">
  <link rel="stylesheet" href="../src/edit.css">
  <link rel="icon" type="image/png" href="../images/favicon/favicon-32x32.png" sizes="32x32">
  <link rel="icon" type="image/png" href="../images/favicon/favicon-16x16.png" sizes="16x16">
  <link rel="apple-touch-icon" type="image/png" href="../images/favicon/apple-touch-icon.png" sizes="180x180">
</head>

<body>
<?php
$updateSuccess=0;
function gtfo(){
  Header("Location:javascript:history.back()");
  exit();
}
require 'admin/config.php';
require 'admin/functions.php';

if (isset($_GET['code'])) $code = $_GET['code'];
else gtfo();

if (checkbanned($_SERVER['REMOTE_ADDR'])) gtfo();

$mysqli = new mysqli("$DBHOST", "$DBUSER", "$DBPASS", "$DBNAME");
if ($mysqli->connect_errno) {
  die('mysqli connection error: ' . $mysqli->connect_error);
}

if (isset($_POST['code'])){
  
  $code = $mysqli->real_escape_string($_POST['code']);
  $dname = $mysqli->real_escape_string($_POST['dname']);
  $wishlist = $mysqli->real_escape_string($_POST['wishlist']);
  $adresse = $mysqli->real_escape_string($_POST['adresse']);
  $interesse = $mysqli->real_escape_string($_POST['interesse']);
  $favs = $mysqli->real_escape_string($_POST['like']);
  $notlike = $mysqli->real_escape_string($_POST['notLike']);
  $email = $mysqli->real_escape_string($_POST['email']);
  $sql = "UPDATE ${DBPREFIX}_teilnehmer SET dname='$dname', wishlist='$wishlist', adresse='$adresse', interesse='$interesse',favs='$favs', notlike='$notlike', email='$email' 
  WHERE code='$code'";
    if ($mysqli->query($sql) === TRUE) {
      $updateSuccess=1;
    } else {
      echo "Error: " . $sql . "<br>" . $mysqli->error;
    }

}


if (checkrolled()) gtfo();
$code = $mysqli->real_escape_string($code);
$res = selectsql("SELECT * from ${DBPREFIX}_teilnehmer where code='$code'");
$row = $res->fetch_assoc();



$teilnehmer = $row['code'];
if (is_null($teilnehmer)) {
  logFail($_SERVER['REMOTE_ADDR'], $code);
  showerr("FALSCHER TEILNEHMERCODE", "Gefettfingert? Versuch’s einfach nochmal.", "<img class='cat' src='./images/gifs/type-computer.gif' width='160%'>");
}
else{
  
$dname = $row['dname'];
$wishlist = ($row['wishlist']);
$adresse = ($row['adresse']);
$interesse = ($row['interesse']);
$favs = ($row['favs']);
$notlike = ($row['notlike']);
$email = $row['email'];
}
?>

  <div class="container">
    <div class="image">
      <img src="../images/christmas-decoration.png" alt="girl decorating a christmas tree">
    </div>
    <div class="form">
      <form action="#" method="post">
        <div class="banner">
          <h1>BEARBEITUNG</h1>  
        </div>
        <div class='back'><a href='../wichtel.php'><img src='../images/expand_circle_down_FILL0_wght300_GRAD0_opsz48.svg'>Zurück</a></div>
        
        <div class="colums">

          <div class="item">
            <label  for="dname">Discord Name <span>*</span></label>
            <?php echo'
            <input  id="dname" type="text" name="dname" required value="'.htmlspecialchars($dname,ENT_QUOTES).'" />';
            ?>
          </div>
          <div class="item">
            <label class="end" for="wishlist">Wishlist</label>
            <?php echo'
            <input class="end" id="wishlist" type="text" name="wishlist" value="'.htmlspecialchars($wishlist,ENT_QUOTES).'" />';
            ?>
          </div>
          <div class="item">
            <label for="adresse">Adresse <span>*</span></label>
            <?php 
            echo '
            <textarea name="adresse" id="adresse" cols="30" rows="3" required placeholder=" (Privat oder Packstation)">'.htmlspecialchars($adresse,ENT_QUOTES).'</textarea>';
            ?>
          </div>
          <div class="item">
            <label class="end" for="interesse">Interessen <span>*</span></label>
            <?php echo'
            <textarea class="end" name="interesse" id="interesse" cols="30" rows="3" required>'.htmlspecialchars($interesse,ENT_QUOTES).'</textarea>';
            ?>
          </div>
          <div class="item">
            <label for="notLike">Abneigungen/Allergien <span>*</span></label>
            <?php echo '
            <textarea name="notLike" id="notLike" cols="30" rows="3" required>'.htmlspecialchars($notlike,ENT_QUOTES).'</textarea>';
            ?>
          </div>
          <div class="item">
            <label class="end" for="like">Lieblings... </label>
            <?php echo '
            <textarea class="end" name="like" id="like" cols="30" rows="3" placeholder="(Anime, Manga, Spiel, Essen, ...)">'.htmlspecialchars($favs,ENT_QUOTES).'</textarea>';
            ?>
          </div>
          <?php 
          if ($ALLOWMAIL) {
            echo '
          <div class="item">
            <label for="email">E-Mail <span data-text="Falls du per E-Mail darüber informiert werden möchtest, wenn dein Paket auf dem Weg ist. Dein Wichtel sieht deine E-Mail Adresse nicht." class="tooltip"><img src="../images/help_FILL0_wght400_GRAD0_opsz48.svg" alt="help" width="18px" class="help"></span></label>
            <input id="email" type="email" name="email" value="'.htmlspecialchars($email,ENT_QUOTES).'" />
          </div>';
          }
          ?>
        </div>
        <?php echo"
        <input type='text' class='hidden' name='code' required value='".htmlspecialchars($code, ENT_QUOTES). "' />";
        ?>
        <div class="btn-block">
          <button type="submit">Absenden</button>
        </div>
        <?php
        if ($updateSuccess) {
          echo '<p class="editsuccess">🎉 Deine Daten wurden erfolgreich aktualisiert!</p>';
        }        
        ?>
      </form>
    </div>
  </div>

</body>

</html>