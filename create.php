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

if(isset($_GET['question']))
{
	$question = $_GET['question'];
}
else {$question = 0;}
$next = $question + 1;

if(isset($_GET['start']))
{$start = $_GET['start'];}

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
	</div><!-- close header -->
	<div id="profcolumn">
<?php
		
			logOut(); //loggar ut en om man klickat på log ut länken
			$userID = $_SESSION['userID'];// visa bild och profillänk
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
				echo "<img src='images/default.png'>";
			}
			echo "<a href='profile.php?userID=".$userID."&order=default'>".$userName."</a><br>";
			logOutLink();//logga ut länken
		
?>
			
	</div><!-- close profcolumn -->
	<div id="content">
		<div class="box">
<?php

	if(isset($_GET['quizID']))// quizID är som i adressen om den är där
		{$quizID = $_GET['quizID'];}

	
		if(isset($_GET['question'])) // ifyllt quizgrej finns question. fram med frågan
		{
				{$numQuestion = $_GET['numQuestion'];}

			$currentQuestion = $question + 1;
			if ($question < $numQuestion)
			{
			echo "<h3>Fråga ".$currentQuestion."</h3>";//skriv ut vilken fråga man är på
			echo "<form method='post' action='create.php?question=".$next."&userID=".$userID."&numQuestion=".$numQuestion."&quizID=".$quizID."'>
			Frågan: <br>
			<textarea name='content'></textarea><br>
			Alternativ 1: <br>
			<input type='text' name='alt1'><br>
			Alternativ X: <br>
			<input type='text' name='altX'><br>
			Alternativ 2: <br>
			<input type='text' name='alt2'><br>
			Rätt alternativ: <br>
			<select name='correct'>
				<option value='alt1'>alt1</option>
				<option value='altX'>altX</option>
				<option value='alt2'>alt2</option>
			</select><br>
			<input type='submit' value='Spara frågan'>";
			}
			else // finns det inga frågor kvar får man reda på sitt resultat
			{
			echo "Du är färdig med skapandet av quizzzzet. Duktig pojke/flicka!<br>";
			echo "Gå tillbaka till profilsidan<a href='profile.php?userID=".$userID."&order='default'>?</a>";
			}
		}
		elseif(isset($_POST['quizName']))//om man valt skapa ett nytt quiz stoppas quizdata in
		{
		// lägger in quizinfo i databasen
		$quizName = $_POST['quizName'];
		$description = $_POST['description'];
		$newQuizsql = "INSERT INTO quiz (quizName, userID, description, created)
		VALUES ('$quizName', $userID, '$description', NOW() )";
		mysqli_query($dbConn, $newQuizsql);

		// hämtar quizID
		$numQuestion = $_POST['numQuestion'];
		$getQuizID = "SELECT * FROM quiz
		WHERE quizName = '$quizName'";
		$res = mysqli_query($dbConn, $getQuizID);
		$row = mysqli_fetch_assoc($res);
		$quizID = $row['quizID'];
		echo "Du har skapat quizzet ".$row['quizName'].".<br>";
		echo "Lägg in frågorna <a href='create.php?&question=0userID=".$userID."&quizID=".$quizID."&numQuestion=".$numQuestion."'>här</a>";
		}
		else // kommer upp om man inte fyllt i info om quiz än. form för info om quiz
		{
				
				echo "<form method='post' action='create.php?&userID=".$userID."'>
				Quiznamn: <br> 
				<input type='text' name='quizName'><br>
				Beskrivning: (max 100 ord)<br>
				<textarea name='description'></textarea><br>
				Antal frågor:<br>
				<input type='text' name='numQuestion'><br>
				<input type='submit' value='Skapa quiz'>
				</form>";
		}

		
	
	if ($question > 0)// om man inte är på första frågan lägger man in data om frågorna
	{
		$content = $_POST['content'];
		$alt1 = $_POST['alt1'];
		$altX = $_POST['altX'];
		$alt2 = $_POST['alt2'];
		$correct = $_POST['correct'];


		$insertQuestionsql = "INSERT INTO question (content, alt1, altX, alt2, correct, quizID)
		VALUES ('$content', '$alt1', '$altX', '$alt2', '$correct', $quizID)";
		mysqli_query($dbConn, $insertQuestionsql);
	}
?>
		</div><!-- close box -->
		</div><!-- close content -->
	</div><!-- close wrap -->
</body>
</html>
<?php
function questionCount($currentQuiz) // räknar antalet frågor
{
	global $dbConn;
	$sql = "SELECT COUNT(questionID) AS Antal
	FROM question
	WHERE quizID = $currentQuiz";
	$res = mysqli_query($dbConn, $sql);
	$row = mysqli_fetch_assoc($res);
	return $row['Antal'];

}
?>