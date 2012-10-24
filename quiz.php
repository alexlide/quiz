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
		<h1>1<a href="main.php">X</a>2</h1>
	</div><!-- close main -->
	<div id="profcolumn">
<?php
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
				echo "<img src='images/default.png'>";
			}

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
		<div class="box">
<?php
if(isset($_SESSION['userID']))
{
	if(isset($_GET['question']))//om man valt skapa ett nytt quiz får question. då får man fråga. 
	{
		$quizID = $_GET['quizID'];
		$numQuestion = questionCount($quizID); // räknar antal frågor
		if($question < $numQuestion) // om det finns frågor kvar att skapa får man fram formuläret
		{
			echo "<h3>Fråga ".$next."</h3>";//skriv ut vilken fråga man är på	

			if(isset($_GET['alt'])) // om alt är satt får man fram grejer och insert
			{
				// insert resultat i databasen
				$questionID = $_GET['questionID'];
				$userID = $_SESSION['userID'];
				$start = $_GET['start'];
				$answer = $_GET['alt'];
				$insertResultsql = "INSERT INTO results (questionID, start, userID, answer)
				VALUES ($questionID, $start, $userID, '$answer')";
				mysqli_query($dbConn, $insertResultsql);

				// hämtar vilket svar man valt
				$questionID = $_GET['questionID'];
				$getAnswer = "SELECT * FROM results
				INNER JOIN question
				ON question.questionID = results.questionID
				WHERE results.questionID = $questionID
				AND start = $start
				AND userID = $userID";
				$res = mysqli_query($dbConn, $getAnswer);
				if ($row = mysqli_fetch_assoc($res))
				{
					if($_GET['alt'] == 'alt1')
						{$alt = $row['alt1'];}
					elseif($_GET['alt'] == 'altX')
						{$alt = $row['altX'];}
					else {$alt = $row['alt2'];}
					echo "Du har svarat: ".$alt."<br>";
				}
				
				if ($row['correct'] == $row['answer'])
				{
					$correct = $row['correct'];
					echo "Du svarade rätt<br>";
				}
				else
				{
					$correct = $row['correct'];
					if($correct == 'alt1')
						{$correct = $row['alt1'];}
					elseif($correct == 'altX')
						{$correct = $row['altX'];}
					else {$correct = $row['alt2'];}
					echo "Tyvärr svarade du fel. Rätt svar är: ".$correct."<br>";
				}

				echo "<a href='quiz.php?quizID=".$quizID."&question=".$next."&start=".$start."'>Nästa fråga</a>";
			}

			else
			{
				// hämtar data om question
				$start = $_GET['start'];
				$quizID = $_GET['quizID'];
				$getQuestionsql = "SELECT * FROM question
				WHERE quizID = $quizID
				LIMIT $question, 1";
				$res = mysqli_query($dbConn, $getQuestionsql);
				while ($row = mysqli_fetch_assoc($res))
				{
					echo $row['content']."<br>";
					echo "<a href='quiz.php?question=".$question."&start=".$start."&questionID=".$row['questionID']."&quizID=".$quizID."&alt=alt1'>1.</a> ".$row['alt1']."<br>";
					echo "<a href='quiz.php?question=".$question."&start=".$start."&questionID=".$row['questionID']."&quizID=".$quizID."&alt=altX'>X.</a> ".$row['altX']."<br>";
					echo "<a href='quiz.php?question=".$question."&start=".$start."&questionID=".$row['questionID']."&quizID=".$quizID."&alt=alt2'>2.</a> ".$row['alt2']."<br>";
				}
			}	
			// här görs form för ifyllning av var fråga
	
		}
		else // finns det inga frågor kvar får man reda på sitt resultat
		{
			$userID = $_SESSION['userID'];
			$start = $_GET['start'];
			$quizID = $_GET['quizID'];
			$resultsql = "SELECT COUNT('questionID') as antalRatt FROM question
			INNER JOIN results
			ON question.questionID = results.questionID
			WHERE quizID = $quizID
			AND userID = $userID
			AND start = $start
			AND question.correct = results.answer";
			$res = mysqli_query($dbConn, $resultsql);
			while ($row = mysqli_fetch_assoc($res))
			{
				$antalRatt = $row['antalRatt'];
				echo "Du är färdig med quizzzzet.<br>";
				echo "Du fick ".$antalRatt." rätt av ".$numQuestion." möjliga.<br>";
				$procent = $antalRatt / $numQuestion;
				if ($procent > 0.7) // har man bra procent av rätta svar får man bra jobbat meddelande, annars ganska bra eller dåligt.
					{echo "Bra jobbat!<br>";}
				elseif($procent > 0.4)
					{echo "Ganska bra jobbat!<br>";}
				else {echo "Dåligt jobbat!<br>";}
				echo "Gå tillbaka till quizsidan<a href='main.php'>?</a><br>";
			}

		}
			
	}
	if(!isset($_GET['question']))
	{
				//hämtar quizdata
				$quizID = $_GET['quizID'];
				$start = date('dmyHis');
				$numQuestion = questionCount($quizID);
				$getQuizsql = "SELECT * FROM quiz
				WHERE quizID = $quizID";
				$res = mysqli_query($dbConn, $getQuizsql);
				if($row = mysqli_fetch_assoc($res))
					{
					echo "<h3>".$row['quizName']."</h3>";
					echo $row['description']."<br>";
					if ($numQuestion == 1)
					{
						echo $numQuestion." fråga<br>";
					}
					else{echo $numQuestion." frågor<br>";}
				echo "<a href='quiz.php?question=0&start=".$start."&quizID=".$quizID."'>Starta quiz</a>";
				}
	}

	if(isset($_POST['quizName'])) // om satt quizName så sätter man in quizdata i databasen
	{
		$quizName = $_POST['quizName'];
		$description = $_POST['description'];
		$userID = $_SESSION['userID'];
		$insertQuizsql = "INSERT INTO quiz (quizName, description, userID, created)
		VALUES ('$quizName', '$description', $userID, NOW() )";
		mysqli_query($dbConn, $insertQuizsql);
	}
}

else //om man inte är inloggad får man ett litet meddelande.
{
	echo "Du är inte inloggad. Logga in till vänster.";
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