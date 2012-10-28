<?php
header("Content_type: text/html; charset=utf-8");
require_once("conn.php");
require_once("functions.php");

$dbConn = mysqli_connect($host, $user, $password, $db);
	if (mysqli_connect_errno())
	{
		echo "Det blev fel. Felkod: ".mysqli_connect_errno();
		exit;
	}
charset();
session_start();
?>
<!doctype html>
<html>
<head>
<link href="style.css" rel="stylesheet" type="text/css">
<meta charset="UTF-8">
<title>1X2</title>
<style>
body {font-family: verdana;}
</style>
</head>
<body>
<div id="wrap">
	<div id="header">
		<h1>1<a href="index.php">X</a>2</h1>
	</div><!-- close header  -->
	<div id="profcolumn">
<?php
	if(isset($_POST['regUser']))
	{
		echo "<form method='post' action='index.php'>
				Användarnamn:<br>
				<input type='text' name='logUser'><br>
				Lösenord:<br>
				<input type='password' name='logPass'><br>
				<input type='submit' value='Logga in!'>
			</form>";
	}
			

?>
	</div><!-- close profcolumn -->
	<div id="content">
	<div class="box">
<?php
		// här registrerar man sig
		if(isset($_POST['regUser']))
		{
		// kollar att användarnamnet inte finns
		$checkUsersql = "SELECT userName FROM user";
		$res = mysqli_query($dbConn, $checkUsersql);		
		$row = mysqli_fetch_assoc($res);
		if ($_POST['regUser'] != $row['userName'])
		{
			if($_POST['regPass'] == $_POST['reregPass']) // kollar att båda lösenorden är samm
			{
				$regUser = $_POST['regUser'];
				$regPass = $_POST['regPass'];
				$admin = $_POST['admin'];
				
				// saltar lite
				$slump = time()."nubben".$regUser;
				$salt = hash('sha256', $slump);
				$regPass = hash('sha256', $regPass);
				$regPass = $salt.$regPass;
				$regUser = mysqli_real_escape_string($dbConn, $regUser);
				$regUser = htmlspecialchars($regUser);
		
				$regInsertsql = "INSERT INTO user (userName, password, joined, admin) 
				VALUES ('$regUser', '$regPass', NOW(), '$admin')";
				mysqli_query($dbConn, $regInsertsql);

				// hämtar nya namnet och meddelar att allt gått fint till
				$getRegsql = "SELECT userName FROM user
				WHERE userName = '$regUser'";
				$res = mysqli_query($dbConn, $getRegsql);
				if ($row = mysqli_fetch_assoc($res))
				{
					echo "Du har registrerat dig som ".$row['userName'];
					echo "<br>Logga in till vänster om du vågar!";
				}

			}
			else 
			{
				echo "Lösenorden matchade inte. Försök igen!";
				echo "<form method='post' action='reg.php'>
				Användarnamn:<br>
				<input type='text' name='regUser'><br>
				Lösenord:<br>
				<input type='password' name='regPass'><br>
				Upprepa lösenord:<br>
				<input type='password' name='reregPass'><br>
				Vill du bli admin?<br>
				<input type='radio' name='admin' value='1'>Ja<br>
				<input type='radio' name='admin' value='0'>Nej<br>
				<input type='submit' value='Registrera dig!''>
				</form>";
			}
		}
		else
		{
			echo "Användarnamnet är redan taget. Välj ett annat vetja!";
			echo "<form method='post' action='reg.php'>
				Användarnamn:<br>
				<input type='text' name='regUser'><br>
				Lösenord:<br>
				<input type='password' name='regPass'><br>
				Upprepa lösenord:<br>
				<input type='password' name='reregPass'><br>
				Vill du bli admin?<br>
				<input type='radio' name='admin' value='1'>Ja<br>
				<input type='radio' name='admin' value='0'>Nej<br>
				<input type='submit' value='Registrera dig!''>
				</form>";
		}
		}
		else // regformen kommer upp om man inte skrivit nåt i den
		{
		echo "<h3>Registrera dig!</h3><br>";
		echo "<form method='post' action='reg.php'>
				Användarnamn:<br>
				<input type='text' name='regUser'><br>
				Lösenord:<br>
				<input type='password' name='regPass'><br>
				Upprepa lösenord:<br>
				<input type='password' name='reregPass'><br>
				Vill du bli admin?<br>
				<input type='radio' name='admin' value='1'>Ja<br>
				<input type='radio' name='admin' value='0'>Nej<br>
				<input type='submit' value='Registrera dig!''>
			</form>";
		}
?>

	</div> <!-- close box -->
	</div> <!-- close content -->
	
</div><!-- close wrap -->
</body>
</html>