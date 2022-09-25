<!DOCTYPE html>
<html lang="de">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="./src/wichtel.css">
	<link rel="stylesheet" href="./src/universal-styles.css">
	<title>Informationen zu deinem Wichtel</title>
</head>
<body>

	<?php 
	require 'admin/config.php';
	require 'admin/functions.php';
	$cookie = 0;

	if ($_GET['delcookie']) {
		setcookie("wichtelcode", "", time() - 3600);
		Header("Location:wichtel.php");
	}

	if (!isset($_POST['code']) && (!isset($_COOKIE['wichtelcode']))) {
		
		echo "<div class='container'>
		<div class='image'>
		  <img src='./images/christmas-celebration.png' alt='santa flying over house'>
		  </div>
		<div class='form teilnehmer'>
        <form action='wichtel.php' method='post'>
          <div class='banner'>
            <h1>INFORMATIONEN ZU DEINEM WICHTEL</h1>
          </div>
          <div class='colums row'>
            <div class='item'>
              <label for='code'>Dein Teilnehmercode <span>*</span></label>
              <input class='codeInput' id='code' type='text' name='code' required/>
            </div>
			<div class='item btn-block'>
            <button type='submit'>Zeig her!</button>
          </div>
            <div class='item save'>
			  <input class='end' id='save' type='checkbox' name='save'/>
              <label class='end checkbox'  for='save'>Code merken (Setzt einen Cookie)</label>
            </div>   
          </div>
        </form>
      </div>
    </div>";
	}

	else{
		if (isset($_COOKIE['wichtelcode'])) {
			$code = $_COOKIE['wichtelcode'];
			$cookie = 1;
		}
		else {
			$code=$_POST['code'];
		}



		$mysqli = new mysqli("$DBHOST", "$DBUSER", "$DBPASS", "$DBNAME");
		if ($mysqli->connect_errno) {
			die('mysqli connection error: ' . $mysqli->connect_error);
		}

		$code=$mysqli->real_escape_string($code);
		$res=selectsql("SELECT * from zuweisungen where teilnehmer='$code'");
		$row=$res->fetch_assoc();
		$wichtel=$row['wichtel'];
		$teilnehmer=$row['teilnehmer'];

		if (is_null($teilnehmer)) {
			showerr("FALSCHER TEILNEHMERCODE", "Entweder du hast dich vertippt, oder dir wurde noch kein Wichtel zugeteilt!", "<img src='./images/gifs/kermit-the-frog-looking-for-directions.gif' width='160%'>");
			exit();
		}

		if (is_null($wichtel)) {
			showerr("NICHT SO SCHNELL", "Die Wichtel wurden noch nicht zugeteilt!<br>Am <strong>$ROLLDATE</strong> um <strong>$ROLLTIME Uhr</strong> wird dir dein Wichtel zugeteilt! ❄", "<img src='./images/gifs/spongebob-cant-wait.gif' width='120%'>");
			exit();
		}


///WICHTEL INFO
		if ($_POST['save']) {
			setcookie("wichtelcode", $code, time() + (3600*30) * 30);
			$cookie = 1;
		}
		
		$wichtel = selectsql("SELECT * FROM teilnehmer where code='$wichtel'")->fetch_assoc();

		$dname = $wichtel['dname'];
		$wishlist = nl2br($wichtel['wishlist']);
		$adresse = nl2br($wichtel['adresse']);
		$interesse = nl2br($wichtel['interesse']);
		$favs = nl2br($wichtel['favs']);
		$notlike = nl2br($wichtel['notlike']);
		$email = $wichtel['email'];
		$res=selectsql("SELECT trackingid from zuweisungen where teilnehmer='$code'");
		$row=$res->fetch_assoc();
		$trackingid = $row['trackingid'];


//*****



		?>


		<?php 
		echo"<div class='container'>
		<div class='image'>
		  <img src='./images/christmas-celebration.png' alt='santa flying over house'>
		  </div>
		<div class='form teilnehmer'>
          <div class='banner'>
            <h1>INFORMATIONEN ZU DEINEM WICHTEL</h1>
          </div>
          <div class='colums row'>
            <div class='item info'>
			<img class='icon' src='./images/account_circle_FILL0_wght300_GRAD0_opsz48.svg' alt='account icon'>
			<h3>Discord-Name</h3>
			 <p>$dname</p>
            </div>
			<div class='item info'>
			<img class='icon' src='./images/home_pin_FILL0_wght300_GRAD0_opsz48.svg' alt='adress icon'>
			<h3>Adresse</h3>
			 <p>$adresse</p>
            </div>
			<div class='item info'>
			<img class='icon' src='./images/event_note_FILL0_wght300_GRAD0_opsz48.svg' alt='list icon'>
			<h3>Wishlist</h3>
			 <p><a href='$wishlist'>$wishlist</a></p>
            </div>
			<div class='item info'>
			<img class='icon' src='./images/favorite_FILL0_wght300_GRAD0_opsz48.svg' alt='heart icon'>
			<h3>Lieblingsdinge</h3>
			 <p>$favs</p>
            </div>
			<div class='item info'>
			<img class='icon' src='./images/sentiment_satisfied_FILL0_wght300_GRAD0_opsz48.svg' alt='smiling face icon'>
			<h3>Interessen</h3>
			 <p>$interesse</p>
            </div>
			<div class='item info'>
			<img class='icon' src='./images/cancel_FILL0_wght300_GRAD0_opsz48.svg' alt='cancel icon'>
			<h3>Abneigungen/Allergien</h3>
			 <p>$notlike</p>
            </div>
          </div>";
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		 	echo "<div class='subbanner'>
			 <h2>DER WEIHNACHTSMANN KANN KOMMEN!</h2>
		   </div>
		   <div class='item'>
		   <p class='wichteltracking'>Du hast das perfekte Geschenk für deinen Wichtel verschickt? Super! <br>
		   Lass die Trackingnummer dazu hier und wir benachrichtigen deinen Wichtel, dass sein Wichtelgeschenk auf dem Weg ist!  🎁</p>
		   <form action='#' method='post'><label class='smallerlabel' for='trackcode'>Trackingcode</label>
			 <input class='trackingcode' id='trackcode' type='text' name='trackcode'/>
			 <div class='item btn-block btn-tracking'>
		   		<button type='submit'>Absenden</button>
			 </div>
			 </form>
		   </div>";
		}

		if ($cookie) {
			echo '<p><a class="smallerlink" href="wichtel.php?delcookie=1">Cookie löschen</a></p>';
		}
		echo "</div>
		</div>";
	}

	?>
</body>
</html>