<!DOCTYPE html>
<html lang="de">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="./src/style.css">
	<link rel="stylesheet" href="./src/universal-styles.css">
	<title>Wichtel</title>
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
		echo '<p>Gib deinen Teilnehmercode ein';

		echo '<div class=form><form action="wichtel.php" method="post">
		<div class="colums">
		<input name="code" type="text" placeholder="code" required />
		<input name="save" type="checkbox" />
		<label for="save">Code merken (Setzt einen Cookie)</label>

		<input type="submit" value="ZEIG HER" />
		</form>
		</div>';


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
		$res=selectsql("SELECT wichtel from zuweisungen where teilnehmer='$code'");
		$row=$res->fetch_assoc();
		$wichtel=$row['wichtel'];

		if (is_null($wichtel)) {
			showerr("Falscher Teilnehmercode", "Entweder du hast dich vertippt, oder dir wurde noch kein Wichtel zugeteilt!", "<img src='./images/gifs/kermit-the-frog-looking-for-directions.gif' width='160%'>");
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
		echo '<h3>Discord-Name</h3>';
		echo "<p>$dname</p>";
		echo '<h3>Wunschliste</h3>';
		echo "<p>$wishlist</p>";
		echo '<h3>Adresse</h3>';
		echo "<p>$adresse</p>";
		echo '<h3>Interessen</h3>';
		echo "<p>$interesse</p>";
		echo '<h3>Lieblings...</h3>';
		echo "<p>$favs</p>";
		echo '<h3>Abneigungen/Allergien</h3>';
		echo "<p>$notlike</p>";


		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			echo "Mail wurde angegeben!";


			// TRACKING FELD EINBLENDEN
		}

		if ($cookie) {
			echo '<a href="wichtel.php?delcookie=1">Cookie löschen</a>';
		}
	}
	?>

</body>
</html>
