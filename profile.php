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

//deletar quizzet om man valt att göra det
		if(isset($_GET['delete']))
		{
		$deleteQuiz = $_GET['delete'];
		$deleteQuizsql = "DELETE FROM quiz
		WHERE quizID = $deleteQuiz";
		mysqli_query($dbConn, $deleteQuizsql);
		$deleteQuestionsql = "DELETE FROM question
		WHERE quizID = $deleteQuiz";
		mysqli_query($dbConn, $deleteQuestionsql);
		}
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
	</div>
	<div id="profcolumn">
<?php
	// visar bild och skriver ut username.
			$userID = $_SESSION['userID'];
			$userName = $_SESSION['userName'];

			// visa bild
			$getImagesql = "SELECT userID, thumbSource, imageSource FROM image
			WHERE userID = $userID";
			$res = mysqli_query($dbConn, $getImagesql);
			$row = mysqli_fetch_assoc($res);
			if($row['thumbSource'] != NULL)
			{
				$thumb = $row['thumbSource'];
				$image = $row['imageSource'];
				echo "<img src='".$thumb."'><br>";
				echo $userName."<br>";
				echo "<a href='".$image."'>Se originalbilden</a><br>";
			}
			else
			{
				echo "<img src='images/default.png'>";
				echo $userName."<br>";
				echo "<a href='upload.php?userID=".$userID."'>Ladda upp en bild</a><br>";
			}

			//skriver ut länk till att skapa quiz om admin
			$userID = $_SESSION['userID'];
			$checkAdmin = "SELECT admin FROM user
			WHERE userID = $userID";
			$res = mysqli_query($dbConn, $checkAdmin);
			$row = mysqli_fetch_assoc($res);
			$admin = $row['admin'];
			if ($admin == 1)
			{
				echo "<a href='create.php?userID=".$userID."'>Skapa nytt quiz</a>";
			}
			logOutLink();//logga ut länk
?>
	</div>
	<div id="content">
		<div class="infobox">
		<h3>Information</h3>
<?php
		// skriver ut om man är admin eller inte
		
			echo "Admin: ";
			if($admin == 1)
				{echo "Ja<br>";}
			else{echo "Nej<br>";}

		//skriver ut datum man registrerade sig
		$joinedsql = "SELECT joined FROM user
		WHERE userID = $userID";
		$res = mysqli_query($dbConn, $joinedsql);
		while ($row = mysqli_fetch_assoc($res))
		{echo "Medlem sedan: ".$row['joined']."<br>";}

		//räknar ut hur många quiz man gjort
		$doneQuizsql = "SELECT COUNT(DISTINCT start) AS numQuiz FROM results 
		WHERE userID = $userID";
		$res = mysqli_query($dbConn, $doneQuizsql);
		$row = mysqli_fetch_assoc($res);
		echo "Antal genomförda quizszs: ".$row['numQuiz']."<br>";

		// räknar ut hur många frågor man svarat på
		$numQuestionsql = "SELECT COUNT('questionID') as numQuestion FROM results
		WHERE userID = $userID";
		$res = mysqli_query($dbConn, $numQuestionsql);
		$row = mysqli_fetch_assoc($res);
				$numQuestion = $row['numQuestion'];

		//räknar ut hur många rätt man fått. om inga svarade frågor ett meddelande om detta
		if ($numQuestion == 0)
		{
			echo "Genomsnitt rätta svar: Inga svarade frågor<br>";
		}
		else
		{
		$numCorrectsql = "SELECT COUNT('questionID') as numCorrect FROM question
			INNER JOIN results
			ON question.questionID = results.questionID
			WHERE userID = $userID
			AND question.correct = results.answer";
			$res = mysqli_query($dbConn, $numCorrectsql);
			$row = mysqli_fetch_assoc($res);
				$numCorrect = $row['numCorrect'];
				$procent = round(($numCorrect / $numQuestion) * 100);// räknar ut procent rätt man fått och skriver ut
				echo "Genomsnitt rätta svar: ".$procent."%<br>";
		}


		//räknar ut hur många quiz man skapat
		$createdQuizsql = "SELECT COUNT(quizID) as createdQuiz FROM quiz 
		WHERE userID = $userID";
		$res = mysqli_query($dbConn, $createdQuizsql);
		$row = mysqli_fetch_assoc($res);
		echo "Antal skapade quizh: ".$row['createdQuiz'];
?>
		</div><!-- close infobox -->
		<div class="resultsbox">
		<h3>Resultat</h3>
<?php
	// lista med resultat av quiz man genomfört

	// anger de olika variablerna för att kunna sortera listan. sätts senare in i order by sqlsatsen
	if(isset($_GET['order']))
	{
	if ($_GET['order'] == "startasc")
		{$orderParam = "start asc";
		$startbyt = "startdesc";
		$namnbyt = "nameasc";}
	elseif ($_GET['order'] == "namedesc")
		{$orderParam = "quizName desc";
		$startbyt = "startasc";
		$namnbyt = "nameasc";}
	elseif ($_GET['order'] == "nameasc")
		{$orderParam = "quizName asc";
		$startbyt = "startdesc";
		$namnbyt = "namedesc";}
	else {$orderParam = "start desc";
		$startbyt = "startasc";
		$namnbyt = "namedesc";}
	}

	
	echo "<a href='profile.php?userID=".$userID."&order=".$namnbyt."'>Quiznamn</a>		";
	echo "<span class='middlerow'>Rätt/Antal</span>";
	echo "<span class='lastrow'><a href='profile.php?userID=".$userID."&order=".$startbyt."'>Genomfört</a></span><br>";




		// lista för namn, poäng och tid
		// hämta namn och tid med distinkt start
		$distinctStartsql = "SELECT * FROM quiz
		INNER JOIN question
		ON quiz.quizID = question.quizID
		INNER JOIN results
		ON results.questionID = question.questionID
		WHERE results.userID = $userID
		GROUP BY start
		ORDER BY $orderParam";
		$res = mysqli_query($dbConn, $distinctStartsql);
		while ($row = mysqli_fetch_assoc($res))
		{
			$currentStart = $row['start'];
			echo $row['quizName'];// skriv ut namn

			// räkna rätt
			$correctCountsql = "SELECT COUNT('questionID') as correctCount FROM question
			INNER JOIN results
			ON question.questionID = results.questionID
			WHERE start = $currentStart
			AND question.correct = results.answer";
			$result = mysqli_query($dbConn, $correctCountsql);
			$row = mysqli_fetch_assoc($result);
			echo "<span class='middlerow'>".$row['correctCount'];// skriver ut antal rätt

			// rätt antal
			$questionCountsql = "SELECT COUNT('questionID') as questionCount FROM results
			WHERE start = $currentStart";
			$result = mysqli_query($dbConn, $questionCountsql);
			$row = mysqli_fetch_assoc($result);
			echo "/".$row['questionCount']."</span>";// skriver ut antal frågor

			// skriver ut starten lite finare och förståligare
			$year = substr($currentStart, 4, 2);
			$month = substr($currentStart, 2, 2);
			$day = substr($currentStart, 0, 2);
			$hour = substr($currentStart, 6, 2)+2;
			$min = substr($currentStart, 8, 2);
			$sec = substr($currentStart, 10, 2);
			echo "<span class='lastrow'>20".$year."-".$month."-".$day." ".$hour.":".$min.":".$sec."</span><br>";			
			
			
		}
?>
		</div><!-- close resultsbox -->
	</div>
<?php
	if ($admin == 1)
	{
		echo "<div class='myquizbox'>";
		echo "<h3>Mina quiz!</h3>";

		//deletar quizzet om man valt att göra det
		if(isset($_GET['delete']))
		{
		$deleteQuiz = $_GET['delete'];
		$deleteQuizsql = "DELETE FROM quiz
		WHERE quizID = $deleteQuiz";
		mysqli_query($dbConn, $deleteQuizsql);
		$deleteQuestionsql = "DELETE FROM question
		WHERE quizID = $deleteQuiz";
		mysqli_query($dbConn, $deleteQuestionsql);
		}

		// ger en varning om man valt att deleta quiz. annars visar quizena
		if(isset($_GET['warning']))
		{
		$warningQuiz = $_GET['warning'];
		$warningsql = "SELECT quizID, quizName FROM quiz
		WHERE quizID = $warningQuiz";
		$res = mysqli_query($dbConn, $warningsql);
		if ($row = mysqli_fetch_assoc($res))
			{
				echo "Är du säker på att du vill ta bort ".$row['quizName']." ";
				echo "<a href='profile.php?order=ble&delete=".$row['quizID']."'>Ja</a> ";
				echo "<a href='profile.php?order=blu'>Nej</a>";
			}
		}

		else
		{
			// hämtar mina quiz och länk till att deleta eller ändra
			$myQuizsql = "SELECT quizID, quizName FROM quiz
			WHERE userID = $userID";
			$res = mysqli_query($dbConn, $myQuizsql);
			$num_rows = mysqli_num_rows($res);
			
			
			if ($num_rows > 0)
			{
				while($row = mysqli_fetch_assoc($res))
				{
				echo $row['quizName'];
				
				echo "<span class='andra'><a href='edit.php?quizID=".$row['quizID']."'>Ändra</a></span>";
				
				echo "<span class='tabort'><a href='profile.php?order=bla&warning=".$row['quizID']."'>Ta bort</a></span><br>";
				}
			}
			else {echo "Du har inte skapat några quiz än";}
		}

	echo "</div>";// close my quizbox
	}
?>
</div>
</body>
</html>