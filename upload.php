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
$userID = $_SESSION['userID'];


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
	// om fil är satt: hämtar filnamn, döper om fil, flyttar fil och lägger in i databasen
	if (isset($_FILES['bild']) )
		{$tempfile = $_FILES['bild']['tmp_name'];
		$image = $_FILES['bild']['name'];
		
		if(checkFile($image) == 1)
		{
			$imageSource = "images/".$image;
		
			move_uploaded_file($tempfile, $imageSource);
			$thumbName = createThumb($imageSource);
			$thumbSource = $thumbName;
			$insertImagesql = "INSERT INTO image (imageSource, thumbSource, userID) VALUES ('$imageSource', '$thumbSource', $userID)";
			mysqli_query($dbConn, $insertImagesql);
		}
		else {echo "Uppladdningen misslyckades. Kolla ditt filformat.";}
	}

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
			logOutLink();//logga ut länk
		
?>
			
	</div><!-- close profcolumn -->
	<div id="content">
	<div class='box'>
<?php
	// har man laddat upp bild får man meddelande om detta. annars form för att ladda upp
	$checkImagesql = "SELECT imageSource, thumbSource, userID FROM image
	WHERE userID = $userID";
	$res = mysqli_query($dbConn, $checkImagesql);
	$row = mysqli_fetch_assoc($res);
	if ($row['imageSource'] != NULL)
	{
		echo "Du har laddat upp bilden ".$image." som din profilbild";
	}
	else
	{
		echo "<h3>Ladda upp en fin bild</h3>";
		echo "<form method='post' action='upload.php?userid=".$userID."' enctype='multipart/form-data'>
		<input type='file' name='bild'><br><br>
		<input type='submit' value='Spara bilden!!!'>
		</form>";
	}
?>
	</div> <!-- close box -->
	</div> <!-- close content -->
</div><!-- close wrap -->
</body>
</html>
<?php
function createThumb ($imageSource)
{
$image = imagecreatefromjpeg($imageSource);
$orgWidth = imagesx ($image);
$orgHeight =imagesy ($image);

$thumbWidth = floor ( ($orgWidth / $orgHeight) * 150 );
$thumbHeight = floor ( ($orgHeight / $orgWidth) * 150 );

if ($thumbWidth > $thumbHeight)
{
$thumb = imagecreatetruecolor($thumbWidth, 150);
$startWidth = ($thumbWidth - 150)/2;
$startHeight = 0;

imagecopyresampled ($thumb, $image, 0, 0, 0, 0, $thumbWidth, 150, $orgWidth, $orgHeight);
}
else
{
$thumb = imagecreatetruecolor(150, $thumbHeight);
$startHeight = ($thumbHeight - 150)/2;
$startWidth = 0;

imagecopyresampled ($thumb, $image, 0, 0, 0, 0, 150, $thumbHeight, $orgWidth, $orgHeight);
}

$cropThumb = imagecreatetruecolor(150, 150);

imagecopyresampled ($cropThumb, $thumb, 0, 0, $startWidth, $startHeight, 150, 150, 150, 150);	


$thumbname = "images/thumb_".$_FILES['bild']['name'];
imagejpeg ($cropThumb, $thumbname, 100);

return $thumbname;

imagedestroy ($thumb);
imagedestroy ($image);
}

function checkFile ($image)
{
	$end = strlen($image);
	$typeStart = $end - 3;
	$fileType = substr($image, $typeStart, 3);
	if($fileType == 'jpg')
		{return true;}
	elseif ($fileType == 'png')
		{return true;}
	else{return false;}
}
?>