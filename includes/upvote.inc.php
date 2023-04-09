<?php
session_start();
require_once 'dbh.inc.php';

$uid = $_GET['uid'];
$pid = $_GET['pid'];

$sql = "SELECT vote FROM uservotes WHERE userid = '$uid' AND postid = '$pid';";
$query = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($query);

if (empty($row['vote'])) {
	$sqlinto = "INSERT INTO uservotes (postid, userid) VALUES ('$pid', '$uid')";
	mysqli_query($conn, $sqlinto);
	$sqladdto = "UPDATE uservotes SET vote=vote+1 WHERE userid = '$uid' AND postid = '$pid';";
	mysqli_query($conn, $sqladdto);
	$sqladdtop = "UPDATE projects SET rating=rating+1 WHERE id = '$pid';";
	mysqli_query($conn, $sqladdtop);

	header("location: ../index.php");
	exit();
} else {
	$sqldel = "DELETE FROM uservotes WHERE userid = '$uid' AND postid = '$pid';";
	mysqli_query($conn, $sqldel);
	$sqladdtop = "UPDATE projects SET rating=rating+1 WHERE id = '$pid';";
	mysqli_query($conn, $sqladdtop);

	header("location: ../index.php");
	exit();
}