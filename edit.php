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
  <link rel="icon" type="image/png" href="../images/favicon/favicon-32x32.png" sizes="32x32">
  <link rel="icon" type="image/png" href="../images/favicon/favicon-16x16.png" sizes="16x16">
  <link rel="apple-touch-icon" type="image/png" href="../images/favicon/apple-touch-icon.png" sizes="180x180">
</head>

<body>
<?php
function gtfo(){
  Header("Location:/");
  exit();
}
require 'admin/config.php';
require 'admin/functions.php';

if (isset($_GET['code'])) $code = $_GET['code'];
else gtfo();


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
  $sql = "UPDATE teilnehmer SET dname='$dname', wishlist='$wishlist', adresse='$adresse', interesse='$interesse',favs='$favs', notlike='$notlike', email='$email' 
  WHERE code='$code'";
    if ($mysqli->query($sql) === TRUE) {
      echo "<p class='editsuccess'>Deine Daten wurden erfolgreich aktualisiert!</p>";
    } else {
      echo "Error: " . $sql . "<br>" . $mysqli->error;
    }

}


// if (checkrolled()) gtfo();
$code = $mysqli->real_escape_string($code);
$res = selectsql("SELECT * from teilnehmer where code='$code'");
$row = $res->fetch_assoc();



$teilnehmer = $row['code'];
if (is_null($teilnehmer)) {
  http_response_code(404);
  include('404.html');
  exit();
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
        <div class="colums">

          <div class="item">
            <label  for="dname">Discord Name <span>*</span></label>
            <?php echo'
            <input  id="dname" type="text" name="dname" required value="'.$dname.'" />';
            ?>
          </div>
          <div class="item">
            <label class="end" for="wishlist">Wishlist</label>
            <?php echo'
            <input class="end" id="wishlist" type="text" name="wishlist" value="'.$wishlist.'" />';
            ?>
          </div>
          <div class="item">
            <label for="adresse">Adresse <span>*</span></label>
            <?php 
            echo '
            <textarea name="adresse" id="adresse" cols="30" rows="3" required placeholder=" (Privat oder Packstation)">'.$adresse .'</textarea>';
            ?>
          </div>
          <div class="item">
            <label class="end" for="interesse">Interessen <span>*</span></label>
            <?php echo'
            <textarea class="end" name="interesse" id="interesse" cols="30" rows="3" required>'.$interesse.'</textarea>';
            ?>
          </div>
          <div class="item">
            <label for="notLike">Abneigungen/Allergien <span>*</span></label>
            <?php echo '
            <textarea name="notLike" id="notLike" cols="30" rows="3" required>'.$notlike.'</textarea>';
            ?>
          </div>
          <div class="item">
            <label class="end" for="like">Lieblings... </label>
            <?php echo '
            <textarea class="end" name="like" id="like" cols="30" rows="3" placeholder="(Anime, Manga, Spiel, Essen, ...)">'.$favs.'</textarea>';
            ?>
          </div>

          <div class="item">
            <label for="email">E-Mail <span data-text="Falls du per E-Mail darüber informiert werden möchtest, wenn dein Paket auf dem Weg ist. Dein Wichtel sieht deine E-Mail Adresse nicht." class="tooltip"><img src="../images/help_FILL0_wght400_GRAD0_opsz48.svg" alt="help" width="18px" class="help"></span></label>
            <?php echo '
            <input id="email" type="email" name="email" value="'.$email.'" />';
            ?>
          </div>

        </div>
        <?php echo"
        <input type='text' class='hidden' name='code' required value=$code />";
        ?>
        <div class="btn-block">
          <button type="submit">Absenden</button>
        </div>
      </form>
    </div>
  </div>
</body>

</html>