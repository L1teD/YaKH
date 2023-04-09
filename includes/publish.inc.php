<?php 
session_start();
require_once 'dbh.inc.php';
require_once 'functions.inc.php';

if(isset($_POST['submit'])) {

	if (isset($_POST['donate'])) {
		$donate = 1;
	} else {
		$donate = 0;
	}
	$name = $_SESSION['username'];
	$nameid =  $_SESSION['userid'];
	$id = $_SESSION['userid'];
	$title = $_POST['title'];
	$shortdesc = $_POST['shdesc'];
	$desc = $_POST['desc'];
	$fileName = $_FILES['image']['name'];
	$tmpName = $_FILES['image']['tmp_name'];
	$hashtags = $_POST['hashtags'];

	$validImageExtension = ['jpg','jpeg','png','webp'];
	$imageExtension = explode('.', $fileName);
	$imageExtension = strtolower(end($imageExtension));

	$newImageName = uniqid();
	$newImageName .= '.' . $imageExtension;

	move_uploaded_file($tmpName, '../uploads/' . $newImageName);
	$query = "INSERT INTO projects (name, shortdescr, descr, img, isDonate, author, authorId, hashtags) VALUES ('$title','$shortdesc', '$desc', '$newImageName', '$donate', '$name', '$nameid', '$hashtags');";

	mysqli_query($conn, $query);
	mysqli_query($conn, "UPDATE users SET usersCountP=usersCountP+1 WHERE usersId = '$id';");
	header("location: ../index.php");
	exit();
} else {
	header("location: ../publish.php?error=empty");
	exit();
}