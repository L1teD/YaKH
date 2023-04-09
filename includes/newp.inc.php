<?php
session_start();
$lat = $_POST['lat'];
echo "<br>";
$lon = $_POST['lon'];
echo "<br>";
$uid = $_SESSION['userid'];

include_once "dbh.inc.php";

mysqli_query($conn, "INSERT INTO newpoints (lat, lon, userid) VALUES ('$lat','$lon','$uid')");

header("location: ../work.php");