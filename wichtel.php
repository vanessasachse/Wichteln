<!DOCTYPE html>
<html lang="de">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="./src/wichtel.css">
	<link rel="stylesheet" href="./src/universal-styles.css">
	<link rel="stylesheet" href="./src/mobile_wichtel.css">
	<link rel="icon" type="image/png" href="/images/favicon/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/images/favicon/favicon-16x16.png" sizes="16x16">
    <link rel="apple-touch-icon" type="image/png" href="/images/favicon/apple-touch-icon.png" sizes="180x180">
	<script src="./src/editButton.js" defer></script> 
	<title>Informationen zu deinem Wichtel</title>
	
</head>

<body>
	<?php
	require 'admin/config.php';
	require 'admin/functions.php';
	$cookie = 0;
	if (checkbanned($_SERVER['REMOTE_ADDR'])) {
		showerr("ZU OFT VERSUCHT", "Versuch's später nochmal</body></html>", null);
		exit();
	  }
	

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
            <h1>INFORMATIONEN ANZEIGEN ODER BEARBEITEN</h1>
          </div>
          <div class='colums row front'>
            <div class='item'>
              <label for='code'>Dein Teilnehmercode <span>*</span></label>
              <input class='codeInput' id='code' type='text' name='code' required/>
            </div>
			<div class='item btn-block'>
            <button type='submit'>Zeig mir meinen Wichtel!</button>
		</div>  
		<div class='item save'>
		<input class='end' id='save' type='checkbox' name='save'/>
		<label class='end checkbox'  for='save'>Code merken (Setzt einen Cookie)</label>
	  </div> 
          </div>
        </form>";
		if (!file_exists('admin/.rolled')) { // Hier ist noch keine DB Verbindung aufgebaut, so schmutzig, was soll's 😤
			echo "
		<div class='edit'>
		<p>> <a id='editButton'>Eigene Infos bearbeiten</a><br>(Teilnehmercode oben einfügen nicht vergessen!)</p> 
	  </div>";
		}
		echo "
      </div>
    </div>";
	} else {
		if (isset($_COOKIE['wichtelcode'])) {
			$code = $_COOKIE['wichtelcode'];
			$cookie = 1;
		} else {
			$code = $_POST['code'];
		}



		$mysqli = new mysqli("$DBHOST", "$DBUSER", "$DBPASS", "$DBNAME");
		if ($mysqli->connect_errno) {
			die('mysqli connection error: ' . $mysqli->connect_error);
		}
		$code = $mysqli->real_escape_string($code);
		$res = selectsql("SELECT * from ${DBPREFIX}_zuweisungen where teilnehmer='$code'");
		$row = $res->fetch_assoc();
		$wichtel = $row['wichtel'];
		$teilnehmer = $row['teilnehmer'];

		if (is_null($teilnehmer)) {
			logFail($_SERVER['REMOTE_ADDR'], $code);
			showerr("FALSCHER TEILNEHMERCODE", "Gefettfingert? Versuch’s einfach nochmal.", "<img class='cat' src='./images/gifs/type-computer.gif' width='160%'>");
			exit();
		}

		if (is_null($wichtel)) {
			if (checkrolled()) {
				
				showerr("ZU SPÄT 😭", "Anscheinend hast du das Anmeldeformular nicht rechtzeitig ausgefüllt.<br>Die Wichtel wurden bereits zugeteilt!", "<img class='kermit' src='./images/gifs/sad-pablo-lonely.gif' width='110%'>");
				
			} else {
				showerr("NICHT SO SCHNELL", "Die Wichtel wurden noch nicht zugeteilt!<br>Am <strong>$ROLLDATE</strong> um <strong>$ROLLTIME Uhr</strong> wird dir dein Wichtel zugeteilt! ❄", "<img class='spongebob' src='./images/gifs/spongebob-cant-wait.gif' width='120%'>");
			}
			exit();
		}



		///WICHTEL INFO
		if ($_POST['save']) {
			setcookie("wichtelcode", $code, time() + (3600 * 30) * 30);
			$cookie = 1;
		}

		$wichtel = selectsql("SELECT * FROM ${DBPREFIX}_teilnehmer where code='$wichtel'")->fetch_assoc();

		$dname = htmlspecialchars($wichtel['dname'], ENT_QUOTES);
		$wishlist = nl2br(htmlspecialchars($wichtel['wishlist'], ENT_QUOTES));
		$adresse = nl2br(htmlspecialchars($wichtel['adresse'],ENT_QUOTES));
		$interesse = nl2br(htmlspecialchars($wichtel['interesse'],ENT_QUOTES));
		$favs = nl2br(htmlspecialchars($wichtel['favs'],ENT_QUOTES));
		$notlike = nl2br(htmlspecialchars($wichtel['notlike'],ENT_QUOTES));
		$email = $wichtel['email'];
		$res = selectsql("SELECT trackingid from ${DBPREFIX}_zuweisungen where teilnehmer='$code'");
		$row = $res->fetch_assoc();
		$trackingid = $row['trackingid'];
		if ($trackingid != null) {

			$trackingsent = 1;
		}


		//*****

		if (isset($_POST['trackcode']) && (!$trackingsent)) {
			$trackingcode = $_POST['trackcode'];
			if (!validateTrackingCode($trackingcode)) {
				echo "<h1>Fehler!</h1>
				<p style='padding-top: 60px;'>Bitte nur Buchstaben und Zahlen eingeben!</p>
				<a href=wichtel.php>Zurück</a></body></html>";
				exit();
			}
			$trackingcode = $mysqli->real_escape_string($trackingcode);
			$sql = "UPDATE ${DBPREFIX}_zuweisungen set trackingid='$trackingcode' where teilnehmer='$code'";
			// $mysqli->$query($sql);
			if ($mysqli->query($sql) === TRUE) {
				$trackingsent = 1;
				// Mails verschicken klappt nur im Echtbetrieb
				if ($SENDMAIL && $ALLOWMAIL) {
					sendTrackingMail($email, $trackingcode, $dname);
				} else {
					echo "Mail versendet";
				}
			} else {
				echo "Error: " . $sql . "<br>" . $mysqli->error;
			}
		}

		echo "<div class='container'>
		<div class='image'>
		  <img src='./images/christmas-celebration.png' alt='santa flying over house'>
		  </div>
		<div class='form teilnehmer padding-mobile'>
          <div class='banner'>
            <h1>INFORMATIONEN ZU DEINEM WICHTEL</h1>
          </div>
          <div class='colums row mobile'>
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
			<h3>Wishlist</h3>";
		if (filter_var($wishlist, FILTER_VALIDATE_URL)) {
			echo "<p><a href='$wishlist' target='_blank'>$wishlist</a></p>";
		} else {
			echo " <p>$wishlist</p>";
		}
		echo "</div>
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
		   <div class='item subbanner-mobile-text'>
		   <p class='wichteltracking'>Du hast das perfekte Geschenk für deinen Wichtel verschickt? Super! <br>
		   Lass die Trackingnummer dazu hier und wir benachrichtigen deinen Wichtel, dass sein Wichtelgeschenk auf dem Weg ist!  🎁</p>";

			if (!$trackingsent) {
				echo "
		   <form class='emailtrack-padding' action='#' method='post'>
		   <label class='smallerlabel' for='trackcode'>Trackingcode</label>
			 <input class='trackingcode' id='trackcode' type='text' name='trackcode' required pattern=\"\w{3,}\" />";
				if (!$cookie) {
					echo " <input class='hidden'  name='code' value=\"$code\" />";
				}
				echo "<div class='item btn-block btn-tracking'>
		   		<button id='trackingbtn' type='submit'>Absenden</button>
			 </div>
			 </form>";
			} else {
				echo "<p class='trackingsent'>Du hast deinem Wichtel die Trackingnummer gesendet, toll!</p>";
			}
			echo '</div>';
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