<?php
$db_host="localhost";
$db_user="root";
$db_password="";

$lnk=mysqli_connect($db_host,$db_user,$db_password);
if (!$lnk)
	die("Database connection failed");

mysqli_select_db($lnk, "puzzlecam") or die ("Failed to select DB");

if (isset($_GET["info"])) {
	$info = json_decode($_GET["info"], true);
	if (addScore($info,$lnk)) {
		echo "Score inserted!";
	} else {
		echo "Score insertion FAILED!";
	}
} else {
	$result=getAllScores($lnk);
	echo json_encode($result);
}

function addScore($info, $lnk) {
	$query="INSERT INTO Scores (Name, Time, Difficulty) VALUES ".
		"('".$info["name"]."',".$info["time"].",'".$info["difficulty"]."')";
	echo $query;
		$rs=mysqli_query($lnk, $query);
	if (!$rs) {
		return false;
	}
	return true;
}

function getAllScores($lnk) {
	$easy = getScoresWithDifficulty("Easy", $lnk);
	$medium = getScoresWithDifficulty("Medium", $lnk);
	$hard = getScoresWithDifficulty("Hard", $lnk);
	$insane = getScoresWithDifficulty("Insane", $lnk);
	return array("easy"=>$easy, "medium"=>$medium, "hard"=>$hard, "insane"=>$insane);
}

function getScoresWithDifficulty($difficulty, $lnk) {
	$query = "SELECT Name, Time FROM Scores WHERE Difficulty LIKE '".$difficulty."' ORDER BY Time";
	$rs=mysqli_query($lnk, $query);

	$results = array();
	if(mysqli_num_rows($rs)>0) {
		while($row=mysqli_fetch_assoc($rs)) {
			array_push($results, $row);
		}
	}
	return $results;
}

?>