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

	
		if(isset($_GET['question'])) // om man ändrat quizgrej finns question. dags att ändra frågorna.
		{
			$quizID = $_GET['quizID'];
			$numQuestion= questionCount($quizID);// räknar antal frågor
			$currentQuestion = $question + 1;
			if ($question < $numQuestion)// så länge det finns frågor visa dem här
			{
			$getQuestionsql = "SELECT * FROM question
			WHERE quizID = $quizID
			LIMIT $question, 1";
			$res = mysqli_query($dbConn, $getQuestionsql);
			while ($row = mysqli_fetch_assoc($res))
				{
				$questionID = $row['questionID'];
				echo "<h3>Fråga ".$currentQuestion."</h3>";//skriv ut vilken fråga man är på
				echo "<form method='post' action='edit.php?question=".$next."&quizID=".$quizID."'>
				Frågan: <br>
				<textarea name='content'>".$row['content']."</textarea><br>
				Alternativ 1: <br>
				<input type='text' name='alt1' value='".$row['alt1']."'><br>
				Alternativ X: <br>
				<input type='text' name='altX' value='".$row['altX']."'><br>
				Alternativ 2: <br>
				<input type='text' name='alt2' value='".$row['alt2']."'><br>
				Rätt alternativ: (skriv alt1, altX eller alt2)<br>
				<input type='text' name='correct' value='".$row['correct']."'><br>
				<input type='hidden' name='questionID' value='".$questionID."'>
				<input type='submit' value='Ändra frågan'>";

				}
			}
			else // finns det inga frågor kvar får man reda på sitt resultat
			{
			echo "Du är färdig med ändrandet av quizzzzet. Duktig pojke/flicka!";
			}
		}
		elseif(isset($_GET['quizID'])) // kommer upp först. ändrar i info om quizzet
		{
				$quizID = $_GET['quizID'];
				$getQuizsql = "SELECT * FROM quiz
				WHERE quizID = $quizID";
				$res = mysqli_query($dbConn, $getQuizsql);
				if ($row = mysqli_fetch_assoc($res))
				{
				echo "<form method='post' action='edit.php?&quizID=".$quizID."&question=0'>
				Quiznamn: <br> 
				<input type='text' name='quizName' value='".$row['quizName']."'><br>
				Beskrivning: (max 100 ord)<br>
				<textarea name='description'>".$row['description']."</textarea><br>
				<input type='submit' value='Ändra quiz'>
				</form>";
				}
		}

		
	if(isset($_POST['quizName']))//om man tryckt vidare från quizdatan uppdateras den i databasen
	{
		// lägger in quizinfo i databasen
		
		$quizID = $_GET['quizID'];
		$quizName = $_POST['quizName'];
		$description = $_POST['description'];
		$updateQuizsql = "UPDATE quiz
		SET quizName='$quizName', description='$description'
		WHERE quizID = $quizID";
		mysqli_query($dbConn, $updateQuizsql);
	}

	if ($question > 0)// om man inte är på första frågan ändras förra frågan
				{

				$quizID = $_GET['quizID'];
				$content = $_POST['content'];
				$alt1 = $_POST['alt1'];
				$altX = $_POST['altX'];
				$alt2 = $_POST['alt2'];
				$correct = $_POST['correct'];
				$questionID = $_POST['questionID'];

				$updateQuestionsql = "UPDATE question
				SET content='$content', alt1='$alt1', altX='$altX', alt2='$alt2', correct='$correct'
				WHERE questionID = $questionID";
				mysqli_query($dbConn, $updateQuestionsql);
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