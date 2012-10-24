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
		<h1>1<a href="main.php">X</a>2</h1>
	</div><!-- close header  -->
	<div id="profcolumn">
<?php
		logOut(); //loggar ut en om man klickat på log ut länken
		
		if(isset($_POST['logUser'])) //inloggning
			{
				$logUser = checkInput($_POST['logUser']);
				$logPass = checkInput($_POST['logPass']);
				$logsql = "SELECT userID, userName, password FROM user
				WHERE userName = '$logUser'
				AND password = '$logPass'";
				$res = mysqli_query($dbConn, $logsql);
				$num_rows = mysqli_num_rows($res);
				
			if ($num_rows == 1)
				{
				$row = mysqli_fetch_assoc($res);
				$_SESSION['userName'] = $row['userName'];
				$_SESSION['userID'] = $row['userID'];}
			else {echo "Inloggningen misslyckades! Försök igen.<br>";}
			}
	


		if(isset($_SESSION['userName']))// visar bild och skriver ut username/länk om man är inloggad. annars form för inloggning.
		{
			$userID = $_SESSION['userID'];// visa bild
			$userName = $_SESSION['userName'];
			$getImagesql = "SELECT userID, thumbSource FROM image
			WHERE userID = $userID";
			$res = mysqli_query($dbConn, $getImagesql);
			$row = mysqli_fetch_assoc($res);
			if($row['thumbSource'] != NULL)
			{
				$thumb = $row['thumbSource'];
				echo "<img src='".$thumb."'><br>";
			}
			else
			{
				echo "<img src='images/default.png'>";			}

			echo "<a href='profile.php?userID=".$userID."&order=default'>".$userName."</a><br>";
			logOutLink();//logga ut länk
		}
		else
		{
			echo "<form method='post' action='main.php'>
				Användarnamn:<br>
				<input type='text' name='logUser'><br>
				Lösenord:<br>
				<input type='password' name='logPass'><br>
				<input type='submit' value='Logga in!''>
			</form>";

		
		echo "Är du inte registrerad än? Klicka <a href='reg.php'>här</a> för att registrera dig.";// regglänk
		}
?>
			
	</div><!-- close profcolumn -->
	<div id="content">
	
<?php
		// här hämtas alla quizen
		$sql = "SELECT * FROM quiz
		INNER JOIN user
		ON quiz.userID = user.userID
		ORDER BY created DESC";
		$res = mysqli_query($dbConn, $sql);
		while ($row = mysqli_fetch_assoc($res))
		{
			echo "<div class='box'>";
			echo "<h3><a href='quiz.php?quizID=".$row['quizID']."'>".$row['quizName']."</a></h3>";
			echo "Skapat ".$row['created']." av ".$row['userName']."<br>";
			echo "</div>";
		}
?>
	
	</div> <!-- close content -->
</div><!-- close wrap -->
</body>
</html>