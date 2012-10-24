<?php
require_once("conn.php");//för att charset funktionen behöver koppling till databasen.
	$dbConn = mysqli_connect($host, $user, $password, $db);
		if (mysqli_connect_errno())
			{echo "Det blev fel. Felkod:".mysqli_connect_errno();
			exit ;}

function charset()
	{
		global $dbConn;
		$utf = "SET NAMES UTF8";
		mysqli_query($dbConn, $utf);
	}
function logOutLink()
	{
		if(isset($_SESSION['userID']) )
			{echo "<div id='logout'>";
			echo "<a href='main.php?log=out'>Logga ut</a>";
			echo "</div>";}
	}
function logOut()
	{
		if(isset ($_GET['log']))
		
			if ($_GET['log'] == 'out')
			{
				session_unset();
				session_destroy();
			}
		
	}
function checkInput($string)
{
	global $dbConn;
	$string = mysqli_real_escape_string($dbConn, $string);
	$string = htmlentities($string);
	return $string;}
?>