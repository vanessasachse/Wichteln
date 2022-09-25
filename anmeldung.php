<?php 
require 'admin/config.php';
if ($REGCLOSED) {
  Header("Location:closed.html");
}
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wichteln Anmeldung</title>
    <link rel="stylesheet" href="./src/universal-styles.css">
    <link rel="stylesheet" href="./src/style.css">
  </head>
  <body>
    <div class="container">
      <div class="image">
        <img src="./images/christmas-decoration.png" alt="girl decorating a christmas tree">
      </div>
      <div class="form">
        <form action="submit.php" method="post">
          <div class="banner">
            <h1>ANMELDUNG FÜR DAS TOLLSTE WICHTELN</h1>
          </div>
          <div class="colums">
            <div class="item">
              <label for="code">Code <span>*</span></label>
              <input id="code" type="text" name="code" required/>
            </div>
            <div class="item">
              <label class="end"  for="dname">Discord Name <span>*</span></label>
              <input class="end" id="dname" type="text" name="dname" required/>
            </div>
            <div class="item">
              <label for="adresse">Adresse <span>*</span></label>
              <textarea name="adresse" id="adresse" cols="30" rows="3" required placeholder=" (Privat oder Packstation)"></textarea>
            </div>
            <div class="item">
              <label class="end"  for="interesse">Interessen <span>*</span></label>
              <textarea class="end" name="interesse" id="interesse" cols="30" rows="3" required></textarea>
            </div>
            <div class="item">
              <label for="notLike">Abneigungen/Allergien <span>*</span></label>
              <textarea name="notLike" id="notLike" cols="30" rows="3" required></textarea>
            </div>
            <div class="item">
              <label class="end" for="like">Lieblings... </label>
              <textarea class="end" name="like" id="like" cols="30" rows="3" placeholder="(Anime, Manga, Spiel, Essen, ...)"></textarea>
            </div>
            <div class="item">
              <label for="wishlist">Wishlist</label>
              <input id="wishlist" type="text"   name="wishlist"/>
            </div>
            <div class="item">
              <label class="end" for="email">E-Mail <span data-text="Falls du per E-Mail darüber informiert werden möchtest, wenn dein Paket auf dem Weg ist. Dein Wichtel sieht deine E-Mail Adresse nicht." class="tooltip"><img src="./images/help_FILL0_wght400_GRAD0_opsz48.svg" alt="help" width="18px" class="help"></span></label>
              <input class="end" id="email" type="email"   name="email"/>
            </div>
            
          </div>
          <div class="btn-block">
            <button type="submit">Absenden</button>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>